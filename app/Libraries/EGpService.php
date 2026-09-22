<?php

namespace App\Libraries;

use App\Models\ProcurementModel;

class EGpService
{
    private string $cacheFile;
    private int $cacheTtl = 300; // 5 minutes cache

    public function __construct(?string $token = null)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $this->cacheFile = $writableDir . DIRECTORY_SEPARATOR . 'egp_phatthalung_cache.json';
    }

    /**
     * ดึงข้อมูลโครงการจัดซื้อจัดจ้างจริงจากฐานข้อมูล procurements
     */
    public function getPhatthalungProjects(bool $forceRefresh = false): array
    {
        if (!$forceRefresh && file_exists($this->cacheFile)) {
            $lastModified = filemtime($this->cacheFile);
            if ((time() - $lastModified) < $this->cacheTtl) {
                $cached = json_decode((string)file_get_contents($this->cacheFile), true);
                if (is_array($cached) && !empty($cached)) {
                    return $cached;
                }
            }
        }

        return $this->refreshProjects();
    }

    /**
     * ดึงข้อมูลจริงจากตาราง procurements และอัปเดตแคช
     */
    public function refreshProjects(): array
    {
        $projects = $this->getRealPhatthalungProjects();

        // บันทึกแคช
        @file_put_contents($this->cacheFile, json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $projects;
    }

    /**
     * Server-side DataTables Processing
     */
    public function getDatatableData(array $input): array
    {
        $allProjects = $this->getPhatthalungProjects();
        $draw = isset($input['draw']) ? (int)$input['draw'] : 1;
        $start = isset($input['start']) ? (int)$input['start'] : 0;
        $length = isset($input['length']) ? (int)$input['length'] : 10;
        if ($length < 1) $length = 10;

        $searchValue = '';
        if (isset($input['search']['value']) && is_string($input['search']['value'])) {
            $searchValue = trim(mb_strtolower($input['search']['value']));
        }

        // 1. Search Filtering
        $filtered = [];
        foreach ($allProjects as $p) {
            if ($searchValue === '') {
                $filtered[] = $p;
                continue;
            }

            $searchStr = mb_strtolower(
                ($p['project_name'] ?? '') . ' ' .
                ($p['project_id'] ?? '') . ' ' .
                ($p['dept_name'] ?? '') . ' ' .
                ($p['procure_unit'] ?? '') . ' ' .
                ($p['status'] ?? '') . ' ' .
                ($p['method'] ?? '') . ' ' .
                ($p['budget'] ?? '')
            );

            if (mb_strpos($searchStr, $searchValue) !== false) {
                $filtered[] = $p;
            }
        }

        $recordsTotal = count($allProjects);
        $recordsFiltered = count($filtered);

        // 2. Sorting
        $orderColIdx = isset($input['order'][0]['column']) ? (int)$input['order'][0]['column'] : 0;
        $orderDir = isset($input['order'][0]['dir']) && strtolower($input['order'][0]['dir']) === 'desc' ? 'desc' : 'asc';

        $colKeys = [
            0 => 'no',
            1 => 'dept_name',
            2 => 'procure_unit',
            3 => 'project_name',
            4 => 'budget',
            5 => 'status'
        ];
        $sortKey = $colKeys[$orderColIdx] ?? 'no';

        usort($filtered, function($a, $b) use ($sortKey, $orderDir) {
            $valA = $a[$sortKey] ?? '';
            $valB = $b[$sortKey] ?? '';

            if ($sortKey === 'budget' || $sortKey === 'no') {
                $numA = (float)$valA;
                $numB = (float)$valB;
                return $orderDir === 'asc' ? ($numA <=> $numB) : ($numB <=> $numA);
            }

            $cmp = strcmp((string)$valA, (string)$valB);
            return $orderDir === 'asc' ? $cmp : -$cmp;
        });

        // 3. Slice for pagination
        $paged = array_slice($filtered, $start, $length);

        // 4. Render Data for DataTables
        $data = [];
        foreach ($paged as $idx => $row) {
            $currentNo = $start + $idx + 1;
            $docUrl = !empty($row['doc_url']) ? $row['doc_url'] : '#';
            $detailUrl = base_url('procurement/detail/' . ($row['project_id'] ?? ''));
            $targetUrl = ($docUrl !== '#') ? $docUrl : $detailUrl;

            $data[] = [
                'no'           => '<span class="text-secondary fw-bold">' . $currentNo . '</span>',
                'dept_name'    => '<span class="fw-semibold text-dark">' . esc($row['dept_name'] ?? 'สำนักงานจังหวัดพัทลุง') . '</span>',
                'procure_unit' => esc($row['procure_unit'] ?? $row['dept_name'] ?? 'สำนักงานจังหวัดพัทลุง'),
                'project_name' => '<a href="' . $targetUrl . '" target="_blank" class="text-dark text-decoration-none hover-primary fw-medium" style="line-height: 1.55; display: block;">' . esc($row['project_name'] ?? $row['title'] ?? '-') . '</a>',
                'budget'       => ((float)($row['budget'] ?? 0) > 0) ? number_format((float)$row['budget'], 2) : '-',
                'status'       => '<span class="badge bg-primary-subtle text-primary border" style="font-size: 0.85rem; font-weight: 500;">' . esc($row['status'] ?? '-') . '</span>',
                'action'       => '<a href="' . $targetUrl . '" target="_blank" class="egp-doc-btn shadow-xs" title="ดูเอกสารแนบ / รายละเอียด"><i class="fa-solid fa-file-pdf text-danger" style="font-size: 1.15rem;"></i></a>'
            ];
        }

        return [
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ];
    }

    /**
     * ดึงข้อมูลโครงการจัดซื้อจัดจ้างจริงจากฐานข้อมูล MySQL (ตาราง procurements)
     */
    public function getRealPhatthalungProjects(): array
    {
        try {
            $model = new ProcurementModel();
            $items = $model->where('status', 'active')
                           ->orderBy('published_date', 'DESC')
                           ->orderBy('id', 'DESC')
                           ->findAll();
        } catch (\Throwable $e) {
            $items = [];
        }

        $projects = [];
        foreach ($items as $idx => $item) {
            $docUrl = '#';
            if (!empty($item['doc_path'])) {
                if (strpos($item['doc_path'], 'http') === 0) {
                    $docUrl = $item['doc_path'];
                } else {
                    $docUrl = base_url($item['doc_path']);
                }
            }

            $dateStr = !empty($item['published_date']) ? date('d/m/Y', strtotime($item['published_date'])) : date('d/m/Y');

            $projects[] = [
                'no'           => $idx + 1,
                'id'           => (string)$item['id'],
                'project_id'   => (string)$item['id'],
                'dept_name'    => 'สำนักงานจังหวัดพัทลุง',
                'procure_unit' => 'จังหวัดพัทลุง',
                'project_name' => $item['title'],
                'title'        => $item['title'],
                'budget'       => (float)($item['budget'] ?? 0),
                'status'       => !empty($item['category']) ? $item['category'] : 'ประกาศจัดซื้อจัดจ้าง',
                'method'       => !empty($item['method']) ? $item['method'] : 'ทั่วไป',
                'date'         => $dateStr,
                'doc_url'      => $docUrl
            ];
        }

        return $projects;
    }
}

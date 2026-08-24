<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class StrategyManager extends BaseController
{
    /**
     * สตูดิโอบริหารจัดการยุทธศาสตร์และแผนพัฒนาจังหวัด
     */
    public function index()
    {
        helper(['settings']);
        $strategy = get_site_strategy();

        $data = [
            'title' => 'จัดการยุทธศาสตร์และแผนพัฒนาจังหวัด | Phatthalung Admin Studio',
            'activeMenu' => 'strategy_manager',
            'strategy' => $strategy,
            'vision' => $strategy['vision'] ?? [],
            'missions' => $strategy['missions'] ?? [],
            'kpis' => $strategy['kpis'] ?? [],
            'pillars' => $strategy['pillars'] ?? [],
            'documents' => $strategy['documents'] ?? []
        ];

        return view('admin/strategy_manager', $data);
    }

    /**
     * บันทึกข้อมูลวิสัยทัศน์ พันธกิจ และเป้าหมาย
     */
    public function saveVision()
    {
        helper(['settings']);
        $strategy = get_site_strategy();

        $statement = $this->request->getPost('statement') ?? $strategy['vision']['statement'];
        $motto = $this->request->getPost('motto') ?? $strategy['vision']['motto'];
        $period = $this->request->getPost('period') ?? $strategy['vision']['period'];
        $title = $this->request->getPost('title') ?? $strategy['vision']['title'];

        $strategy['vision']['statement'] = trim($statement);
        $strategy['vision']['motto'] = trim($motto);
        $strategy['vision']['period'] = trim($period);
        $strategy['vision']['title'] = trim($title);

        // Missions
        $missionsText = $this->request->getPost('missions');
        if (!empty($missionsText)) {
            $missionsArray = array_values(array_filter(array_map('trim', explode("\n", $missionsText))));
            if (!empty($missionsArray)) {
                $strategy['missions'] = $missionsArray;
            }
        }

        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'บันทึกวิสัยทัศน์และพันธกิจเรียบร้อยแล้ว'
        ]);
    }

    /**
     * เพิ่ม หรือ แก้ไข ตัวชี้วัด KPI รายตัว
     */
    public function saveKpi()
    {
        helper(['settings']);
        $strategy = get_site_strategy();
        $kpis = $strategy['kpis'] ?? [];

        $id = trim($this->request->getPost('id') ?? '');
        $title = trim($this->request->getPost('title') ?? '');
        $target = trim($this->request->getPost('target') ?? '');
        $current = trim($this->request->getPost('current') ?? '');
        $unit = trim($this->request->getPost('unit') ?? '');
        $icon = trim($this->request->getPost('icon') ?? 'fa-solid fa-chart-line');
        $color = trim($this->request->getPost('color') ?? '#2563eb');
        $desc = trim($this->request->getPost('desc') ?? '');

        if (empty($title)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อตัวชี้วัด']);
        }

        if (!empty($id)) {
            // Edit existing
            $found = false;
            foreach ($kpis as &$k) {
                if ($k['id'] === $id) {
                    $k['title'] = $title;
                    $k['target'] = $target;
                    $k['current'] = $current;
                    $k['unit'] = $unit;
                    $k['icon'] = $icon;
                    $k['color'] = $color;
                    $k['desc'] = $desc;
                    $found = true;
                    break;
                }
            }
            unset($k);
            if (!$found) {
                $kpis[] = [
                    'id' => $id,
                    'title' => $title,
                    'target' => $target,
                    'current' => $current,
                    'unit' => $unit,
                    'icon' => $icon,
                    'color' => $color,
                    'desc' => $desc
                ];
            }
        } else {
            // Add new
            $newId = 'kpi_' . uniqid();
            $kpis[] = [
                'id' => $newId,
                'title' => $title,
                'target' => $target,
                'current' => $current,
                'unit' => $unit,
                'icon' => $icon,
                'color' => $color,
                'desc' => $desc
            ];
        }

        $strategy['kpis'] = $kpis;
        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'บันทึกตัวชี้วัดเรียบร้อยแล้ว'
        ]);
    }

    /**
     * เพิ่ม หรือ แก้ไข ประเด็นการพัฒนาจังหวัด (Strategic Theme / Pillar)
     */
    public function savePillar()
    {
        helper(['settings']);
        $strategy = get_site_strategy();
        $pillars = $strategy['pillars'] ?? [];

        $id = trim($this->request->getPost('id') ?? '');
        $number = (int)$this->request->getPost('number') ?: count($pillars) + 1;
        $shortTitle = trim($this->request->getPost('short_title') ?? '');
        $title = trim($this->request->getPost('title') ?? '');
        $summary = trim($this->request->getPost('summary') ?? '');
        $icon = trim($this->request->getPost('icon') ?? 'fa-solid fa-seedling');
        $color = trim($this->request->getPost('color') ?? '#059669');
        $flagship = trim($this->request->getPost('flagship') ?? '');
        
        // Strategies list (multiline)
        $stratText = $this->request->getPost('strategies') ?? '';
        $strategiesArray = array_values(array_filter(array_map('trim', explode("\n", $stratText))));

        if (empty($shortTitle)) {
            $shortTitle = $title;
        }

        if (empty($title)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อประเด็นการพัฒนา']);
        }

        $bgGradient = 'linear-gradient(135deg, ' . $color . ' 0%, ' . $color . 'dd 100%)';

        if (!empty($id)) {
            // Edit existing
            $found = false;
            foreach ($pillars as &$p) {
                if ($p['id'] === $id) {
                    $p['number'] = $number;
                    $p['short_title'] = $shortTitle;
                    $p['title'] = $title;
                    $p['summary'] = $summary;
                    $p['strategies'] = $strategiesArray;
                    $p['flagship'] = $flagship;
                    $p['icon'] = $icon;
                    $p['color'] = $color;
                    $p['bg_gradient'] = $bgGradient;
                    $found = true;
                    break;
                }
            }
            unset($p);
            if (!$found) {
                $pillars[] = [
                    'id' => $id,
                    'number' => $number,
                    'short_title' => $shortTitle,
                    'title' => $title,
                    'summary' => $summary,
                    'strategies' => $strategiesArray,
                    'flagship' => $flagship,
                    'icon' => $icon,
                    'color' => $color,
                    'bg_gradient' => $bgGradient
                ];
            }
        } else {
            // Add new
            $newId = 'pillar_' . uniqid();
            $pillars[] = [
                'id' => $newId,
                'number' => $number,
                'short_title' => $shortTitle,
                'title' => $title,
                'summary' => $summary,
                'strategies' => $strategiesArray,
                'flagship' => $flagship,
                'icon' => $icon,
                'color' => $color,
                'bg_gradient' => $bgGradient
            ];
        }

        // Sort pillars by number
        usort($pillars, function($a, $b) {
            return ($a['number'] ?? 0) <=> ($b['number'] ?? 0);
        });

        $strategy['pillars'] = $pillars;
        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'บันทึกประเด็นการพัฒนาจังหวัดเรียบร้อยแล้ว'
        ]);
    }

    /**
     * ลบประเด็นการพัฒนาจังหวัด
     */
    public function deletePillar()
    {
        helper(['settings']);
        $strategy = get_site_strategy();
        $pillars = $strategy['pillars'] ?? [];

        $id = trim($this->request->getPost('id') ?? '');
        $filtered = array_values(array_filter($pillars, function($p) use ($id) {
            return $p['id'] !== $id;
        }));

        $strategy['pillars'] = $filtered;
        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'ลบประเด็นการพัฒนาจังหวัดเรียบร้อยแล้ว'
        ]);
    }

    /**
     * บันทึกตัวชี้วัด KPIs แบบรวดเร็ว
     */
    public function saveKpis()
    {
        helper(['settings']);
        $strategy = get_site_strategy();

        $kpi1 = $this->request->getPost('kpi_gpp');
        $kpi2 = $this->request->getPost('kpi_organic');
        $kpi3 = $this->request->getPost('kpi_tourism');
        $kpi4 = $this->request->getPost('kpi_ita');

        if (!empty($strategy['kpis'][0]) && $kpi1 !== null) $strategy['kpis'][0]['target'] = trim($kpi1);
        if (!empty($strategy['kpis'][1]) && $kpi2 !== null) $strategy['kpis'][1]['target'] = trim($kpi2);
        if (!empty($strategy['kpis'][2]) && $kpi3 !== null) $strategy['kpis'][2]['target'] = trim($kpi3);
        if (!empty($strategy['kpis'][3]) && $kpi4 !== null) $strategy['kpis'][3]['target'] = trim($kpi4);

        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'บันทึกตัวชี้วัดเป้าหมายเรียบร้อยแล้ว'
        ]);
    }

    /**
     * เพิ่ม/แก้ไขเอกสารแผนพัฒนาฯ
     */
    public function saveDocument()
    {
        helper(['settings']);
        $strategy = get_site_strategy();
        $docs = $strategy['documents'] ?? [];

        $id = $this->request->getPost('id');
        $title = trim($this->request->getPost('title') ?? '');
        $category = trim($this->request->getPost('category') ?? 'แผนปฏิบัติราชการประจำปี');
        $year = trim($this->request->getPost('year') ?? date('Y') + 543);
        $fileUrl = trim($this->request->getPost('file_url') ?? '');
        $fileSize = trim($this->request->getPost('file_size') ?? '5.0 MB');
        $pages = (int)$this->request->getPost('pages') ?: 100;
        $isFeatured = (bool)$this->request->getPost('is_featured');

        if (empty($title)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อเอกสาร']);
        }

        // File Upload
        $file = $this->request->getFile('doc_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/strategy/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $newName = 'plan_' . time() . '_' . $file->getRandomName();
            $file->move($uploadDir, $newName);
            $fileUrl = 'uploads/strategy/' . $newName;
            
            $sizeBytes = filesize($uploadDir . $newName);
            $fileSize = round($sizeBytes / (1024 * 1024), 1) . ' MB';
        }

        if (!empty($id)) {
            // Edit existing
            $found = false;
            foreach ($docs as &$d) {
                if ($d['id'] === $id) {
                    $d['title'] = $title;
                    $d['category'] = $category;
                    $d['year'] = $year;
                    if (!empty($fileUrl)) $d['file_url'] = $fileUrl;
                    $d['file_size'] = $fileSize;
                    $d['pages'] = $pages;
                    $d['is_featured'] = $isFeatured;
                    $d['updated_at'] = date('Y-m-d');
                    $found = true;
                    break;
                }
            }
            unset($d);
            if (!$found) {
                $docs[] = [
                    'id' => $id,
                    'title' => $title,
                    'category' => $category,
                    'year' => $year,
                    'file_url' => $fileUrl ?: '#',
                    'file_size' => $fileSize,
                    'file_type' => 'pdf',
                    'pages' => $pages,
                    'downloads' => 0,
                    'is_featured' => $isFeatured,
                    'updated_at' => date('Y-m-d')
                ];
            }
        } else {
            // Add new
            $newId = 'doc_' . uniqid();
            $docs[] = [
                'id' => $newId,
                'title' => $title,
                'category' => $category,
                'year' => $year,
                'file_url' => $fileUrl ?: '#',
                'file_size' => $fileSize,
                'file_type' => 'pdf',
                'pages' => $pages,
                'downloads' => 0,
                'is_featured' => $isFeatured,
                'updated_at' => date('Y-m-d')
            ];
        }

        $strategy['documents'] = $docs;
        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'บันทึกเอกสารแผนพัฒนาฯ เรียบร้อยแล้ว'
        ]);
    }

    /**
     * ลบเอกสาร
     */
    public function deleteDocument()
    {
        helper(['settings']);
        $strategy = get_site_strategy();
        $docs = $strategy['documents'] ?? [];

        $id = $this->request->getPost('id');
        $filtered = array_values(array_filter($docs, function($d) use ($id) {
            return $d['id'] !== $id;
        }));

        $strategy['documents'] = $filtered;
        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'ลบเอกสารเรียบร้อยแล้ว'
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use App\Libraries\EmenscrService;

class Projects extends BaseController
{
    protected $projectModel;
    protected $emenscrService;

    public function __construct()
    {
        helper(['settings', 'url']);
        $this->projectModel = new ProjectModel();
        $this->emenscrService = new EmenscrService();
    }

    /**
     * หน้าแรกของโครงการ (แผนที่ GIS เชิงพื้นที่)
     */
    public function index()
    {
        return $this->gis();
    }

    /**
     * หน้า Interactive GIS Map แผนที่โครงการจังหวัดพัทลุง
     */
    public function gis()
    {
        // Automatically sync initial dataset if table is empty
        if ($this->projectModel->countAllResults() === 0) {
            $this->emenscrService->syncProjectsFromApi();
        }

        $filters = [
            'year'     => $this->request->getGet('year'),
            'district' => $this->request->getGet('district'),
            'status'   => $this->request->getGet('status'),
            'pillar'   => $this->request->getGet('pillar'),
            'q'        => $this->request->getGet('q'),
        ];

        // Default year to latest available if not specified
        $allYears = $this->projectModel->builder()->select('fiscal_year')->distinct()->orderBy('fiscal_year', 'DESC')->get()->getResultArray();
        $yearsList = array_column($allYears, 'fiscal_year');

        $projects = $this->projectModel->getFilteredProjects($filters);
        $strategy = get_site_strategy();
        $settings = $this->emenscrService->getSettings();
        $summary = $this->projectModel->getExecutiveSummary($filters['year'] ?? null);

        $districts = [
            'เมืองพัทลุง', 'ควนขนุน', 'เขาชัยสน', 'ปากพะยูน', 
            'กงหรา', 'ตะโหมด', 'ป่าบอน', 'บางแก้ว', 
            'ศรีบรรพต', 'ป่าพะยอม', 'ศรีนครินทร์'
        ];

        $data = [
            'title'        => 'ระบบสารสนเทศภูมิศาสตร์โครงการพัฒนาจังหวัดพัทลุง (GIS Project Hub)',
            'projects'     => $projects,
            'filters'      => $filters,
            'yearsList'    => $yearsList,
            'districts'    => $districts,
            'pillars'      => $strategy['pillars'] ?? [],
            'settings'     => $settings,
            'summary'      => $summary,
            'totalCount'   => count($projects),
        ];

        return view('projects/gis_map', $data);
    }

    /**
     * หน้า Executive Dashboard วิเคราะห์ภาพรวมงบประมาณและผลสัมฤทธิ์
     */
    public function dashboard()
    {
        if ($this->projectModel->countAllResults() === 0) {
            $this->emenscrService->syncProjectsFromApi();
        }

        $selectedYear = $this->request->getGet('year');
        $allYears = $this->projectModel->builder()->select('fiscal_year')->distinct()->orderBy('fiscal_year', 'DESC')->get()->getResultArray();
        $yearsList = array_column($allYears, 'fiscal_year');

        $summary = $this->projectModel->getExecutiveSummary($selectedYear);
        $projects = $this->projectModel->getFilteredProjects(['year' => $selectedYear]);
        $strategy = get_site_strategy();
        $settings = $this->emenscrService->getSettings();

        $data = [
            'title'        => 'Executive Dashboard: รายงานติดตามและประเมินผลโครงการ eMENSCR',
            'summary'      => $summary,
            'projects'     => $projects,
            'selectedYear' => $selectedYear,
            'yearsList'    => $yearsList,
            'strategy'     => $strategy,
            'settings'     => $settings,
        ];

        return view('projects/dashboard', $data);
    }

    /**
     * API ดึงรายละเอียดโครงการเดี่ยว (JSON สำหรับ Modal)
     */
    public function getDetail($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลโครงการ']);
        }

        $project['photos_array'] = !empty($project['photos']) ? json_decode($project['photos'], true) : [];
        $project['documents_array'] = !empty($project['documents']) ? json_decode($project['documents'], true) : [];
        $project['disbursed_pct'] = ($project['budget'] > 0) ? round(($project['disbursed_budget'] / $project['budget']) * 100, 1) : 0;

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $project
        ]);
    }

    /**
     * API GeoJSON Export สำหรับแผนที่ GIS
     */
    public function apiGeojson()
    {
        $filters = [
            'year'     => $this->request->getGet('year'),
            'district' => $this->request->getGet('district'),
            'status'   => $this->request->getGet('status'),
            'pillar'   => $this->request->getGet('pillar'),
            'q'        => $this->request->getGet('q'),
        ];

        $projects = $this->projectModel->getFilteredProjects($filters);
        $features = [];

        foreach ($projects as $p) {
            if (empty($p['latitude']) || empty($p['longitude'])) {
                continue;
            }

            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$p['longitude'], (float)$p['latitude']]
                ],
                'properties' => [
                    'id'               => $p['id'],
                    'code'             => $p['emenscr_code'],
                    'year'             => $p['fiscal_year'],
                    'name'             => $p['project_name'],
                    'agency'           => $p['agency'],
                    'budget'           => (float)$p['budget'],
                    'budget_formatted' => number_format($p['budget'], 2),
                    'disbursed'        => (float)$p['disbursed_budget'],
                    'disbursed_pct'    => $p['disbursed_pct'],
                    'progress_pct'     => (int)$p['progress_pct'],
                    'status'           => $p['status'],
                    'status_desc'      => $p['status_desc'],
                    'district'         => $p['district'],
                    'location'         => $p['location_name'],
                    'pillar_number'    => $p['pillar_number'],
                    'pillar_title'     => $p['pillar_title'],
                    'photos'           => $p['photos_array'],
                    'documents'        => $p['documents_array'],
                ]
            ];
        }

        return $this->response->setJSON([
            'type'     => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}

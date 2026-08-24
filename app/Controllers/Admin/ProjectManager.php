<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use App\Libraries\EmenscrService;

class ProjectManager extends BaseController
{
    protected $projectModel;
    protected $emenscrService;

    public function __construct()
    {
        helper(['settings', 'url', 'filesystem']);
        $this->projectModel = new ProjectModel();
        $this->emenscrService = new EmenscrService();
    }

    /**
     * หน้าหลักสตูดิโอจัดการโครงการ GIS & eMENSCR
     */
    public function index()
    {
        // Auto-seed if empty
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
            'title'      => 'จัดการโครงการ GIS & ซิงค์ข้อมูล eMENSCR',
            'projects'   => $projects,
            'filters'    => $filters,
            'yearsList'  => $yearsList,
            'districts'  => $districts,
            'pillars'    => $strategy['pillars'] ?? [],
            'settings'   => $settings,
            'summary'    => $summary,
            'totalCount' => count($projects),
        ];

        return view('admin/project_manager', $data);
    }

    /**
     * บันทึกเพิ่ม หรือ แก้ไข โครงการ
     */
    public function save()
    {
        $id = $this->request->getPost('id');
        $projectName = trim($this->request->getPost('project_name') ?? '');
        $fiscalYear = (int)$this->request->getPost('fiscal_year') ?: 2568;

        if (empty($projectName)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อโครงการ']);
        }

        // Strategy Pillar
        $pillarNum = (int)$this->request->getPost('pillar_number') ?: 1;
        $strategy = get_site_strategy();
        $pillarTitle = 'ประเด็นที่ ' . $pillarNum;
        foreach ($strategy['pillars'] ?? [] as $pl) {
            if ((int)$pl['number'] === $pillarNum) {
                $pillarTitle = $pl['short_title'] ?: $pl['title'];
                break;
            }
        }

        // Photo Gallery URLs (JSON)
        $photosRaw = $this->request->getPost('photos') ?? [];
        if (is_string($photosRaw)) {
            $photosArr = array_values(array_filter(array_map('trim', explode("\n", $photosRaw))));
        } else {
            $photosArr = is_array($photosRaw) ? $photosRaw : [];
        }

        // Document Attachments (JSON)
        $docTitle = trim($this->request->getPost('doc_title') ?? '');
        $docUrl = trim($this->request->getPost('doc_file_url') ?? '');
        $docSize = trim($this->request->getPost('doc_file_size') ?? 'PDF');
        $docsArr = [];
        if (!empty($docUrl)) {
            $docsArr[] = [
                'title'     => $docTitle ?: 'เอกสารรายละเอียดโครงการ.pdf',
                'file_url'  => $docUrl,
                'file_size' => $docSize,
            ];
        }

        $data = [
            'emenscr_code'     => trim($this->request->getPost('emenscr_code') ?? '') ?: ('EM-' . $fiscalYear . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)),
            'fiscal_year'      => $fiscalYear,
            'project_name'     => $projectName,
            'agency'           => trim($this->request->getPost('agency') ?? 'สำนักงานจังหวัดพัทลุง'),
            'pillar_number'    => $pillarNum,
            'pillar_title'     => $pillarTitle,
            'budget'           => (float)str_replace(',', '', $this->request->getPost('budget') ?? 0),
            'disbursed_budget' => (float)str_replace(',', '', $this->request->getPost('disbursed_budget') ?? 0),
            'objectives'       => trim($this->request->getPost('objectives') ?? ''),
            'kpis'             => trim($this->request->getPost('kpis') ?? ''),
            'progress_pct'     => (int)$this->request->getPost('progress_pct') ?: 0,
            'status'           => $this->request->getPost('status') ?? 'in_progress',
            'status_desc'      => trim($this->request->getPost('status_desc') ?? ''),
            'district'         => $this->request->getPost('district') ?? 'เมืองพัทลุง',
            'subdistrict'      => trim($this->request->getPost('subdistrict') ?? ''),
            'location_name'    => trim($this->request->getPost('location_name') ?? ''),
            'latitude'         => !empty($this->request->getPost('latitude')) ? (float)$this->request->getPost('latitude') : null,
            'longitude'        => !empty($this->request->getPost('longitude')) ? (float)$this->request->getPost('longitude') : null,
            'photos'           => !empty($photosArr) ? json_encode($photosArr, JSON_UNESCAPED_UNICODE) : null,
            'documents'        => !empty($docsArr) ? json_encode($docsArr, JSON_UNESCAPED_UNICODE) : null,
            'is_featured'      => $this->request->getPost('is_featured') ? 1 : 0,
        ];

        if (!empty($id)) {
            $this->projectModel->update($id, $data);
            $msg = 'ปรับปรุงข้อมูลโครงการเรียบร้อยแล้ว';
        } else {
            $this->projectModel->insert($data);
            $msg = 'เพิ่มโครงการใหม่เรียบร้อยแล้ว';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบโครงการ
     */
    public function delete($id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลโครงการ']);
        }

        $this->projectModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบโครงการเรียบร้อยแล้ว']);
    }

    /**
     * ซิงค์ข้อมูลโครงการจาก eMENSCR API
     */
    public function syncEmenscr()
    {
        $year = $this->request->getPost('fiscal_year');
        $res = $this->emenscrService->syncProjectsFromApi($year);
        return $this->response->setJSON($res);
    }

    /**
     * บันทึกการตั้งค่า eMENSCR API
     */
    public function saveSettings()
    {
        $data = [
            'api_endpoint'   => $this->request->getPost('api_endpoint'),
            'api_token'      => $this->request->getPost('api_token'),
            'province_code'  => $this->request->getPost('province_code'),
            'province_name'  => $this->request->getPost('province_name'),
            'auto_sync'      => $this->request->getPost('auto_sync'),
            'sync_frequency' => $this->request->getPost('sync_frequency'),
        ];
        $this->emenscrService->saveSettings($data);
        return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกการตั้งค่า eMENSCR API สำเร็จ']);
    }

    /**
     * อัปโหลดภาพประกอบโครงการ
     */
    public function uploadPhoto()
    {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไฟล์รูปภาพไม่ถูกต้อง']);
        }

        $uploadPath = FCPATH . 'uploads/projects';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $url = base_url('uploads/projects/' . $newName);
        return $this->response->setJSON([
            'status' => 'success',
            'url'    => $url,
            'path'   => 'uploads/projects/' . $newName,
        ]);
    }

    /**
     * อัปโหลดเอกสาร PDF โครงการ
     */
    public function uploadDoc()
    {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไฟล์เอกสารไม่ถูกต้อง']);
        }

        $uploadPath = FCPATH . 'uploads/projects/docs';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $origName = $file->getClientName();
        $fileSize = round($file->getSize() / (1024 * 1024), 2) . ' MB';
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $url = 'uploads/projects/docs/' . $newName;
        return $this->response->setJSON([
            'status'    => 'success',
            'orig_name' => $origName,
            'file_size' => $fileSize,
            'file_url'  => $url,
        ]);
    }
}

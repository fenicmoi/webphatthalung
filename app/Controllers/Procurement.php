<?php

namespace App\Controllers;

use App\Libraries\EGpService;

class Procurement extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index($category = null)
    {
        $categories = get_procurement_categories();
        $selectedCat = $category ? urldecode((string)$category) : 'all';

        $egpService = new EGpService();
        $egpProjects = $egpService->getPhatthalungProjects();
        $items = get_procurement_items('all', true);

        $data = [
            'title'        => 'ศูนย์ข้อมูลข่าวจัดซื้อจัดจ้าง (e-GP) และราคากลาง | จังหวัดพัทลุง',
            'categories'   => $categories,
            'selectedCat'  => $selectedCat,
            'items'        => $items,
            'egpProjects'  => $egpProjects,
            'isOfficer'    => session()->get('isLoggedIn')
        ];

        return view('procurement_portal', $data);
    }

    public function detail($id = null)
    {
        if (empty($id)) {
            return redirect()->to(base_url('news?category=' . urlencode('ประกาศจัดซื้อจัดจ้าง (e-GP)')));
        }

        $egpService = new EGpService();
        $projects = $egpService->getPhatthalungProjects();
        $targetProject = null;

        foreach ($projects as $p) {
            if (($p['project_id'] ?? '') === (string)$id || ($p['id'] ?? '') === (string)$id) {
                $targetProject = $p;
                break;
            }
        }

        if (!$targetProject) {
            // Fallback check in standard procurement items
            $item = get_procurement_by_id($id);
            if ($item) {
                $targetProject = [
                    'project_id'   => $item['project_code'] ?? $item['id'],
                    'project_name' => $item['title'],
                    'dept_name'    => $item['department'] ?? 'สำนักงานจังหวัดพัทลุง',
                    'procure_unit' => $item['department'] ?? 'สำนักงานจังหวัดพัทลุง',
                    'budget'       => (float)($item['budget'] ?? 0),
                    'method'       => $item['category'] ?? 'เฉพาะเจาะจง',
                    'status'       => $item['status'] ?? 'ประกาศเชิญชวน',
                    'date'         => !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : date('d/m/Y'),
                    'doc_url'      => 'https://www.gprocurement.go.th'
                ];
            }
        }

        if (!$targetProject) {
            return redirect()->to(base_url('news?category=' . urlencode('ประกาศจัดซื้อจัดจ้าง (e-GP)')))->with('error', 'ไม่พบข้อมูลโครงการจัดซื้อจัดจ้าง');
        }

        return view('procurement/detail', [
            'project'   => $targetProject,
            'pageTitle' => ($targetProject['project_name'] ?? 'รายละเอียดโครงการ e-GP') . ' | จังหวัดพัทลุง'
        ]);
    }

    /**
     * DataTables Server-Side AJAX Endpoint
     */
    public function ajaxDatatable()
    {
        $egpService = new EGpService();
        $params = $this->request->getVar();
        $response = $egpService->getDatatableData((array)$params);

        return $this->response->setJSON($response);
    }
}

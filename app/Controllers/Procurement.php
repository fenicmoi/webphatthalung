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
                $docUrl = '#';
                if (!empty($item['attachment_url'])) {
                    $docUrl = (strpos($item['attachment_url'], 'http') === 0) ? $item['attachment_url'] : base_url($item['attachment_url']);
                }

                $targetProject = [
                    'id'           => (string)$item['id'],
                    'project_id'   => $item['id'],
                    'project_name' => $item['title'],
                    'dept_name'    => 'สำนักงานจังหวัดพัทลุง',
                    'procure_unit' => 'จังหวัดพัทลุง',
                    'budget'       => (float)preg_replace('/[^0-9.]/', '', $item['budget'] ?? '0'),
                    'method'       => 'ทั่วไป',
                    'status'       => $item['category'] ?? 'ประกาศจัดซื้อจัดจ้าง',
                    'date'         => !empty($item['date']) ? date('d/m/Y', strtotime($item['date'])) : date('d/m/Y'),
                    'doc_url'      => $docUrl
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

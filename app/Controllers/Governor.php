<?php

namespace App\Controllers;

class Governor extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url']);
    }

    /**
     * หน้าทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง (Hall of Governors)
     */
    public function index()
    {
        $search = trim((string)$this->request->getGet('q'));
        $era = trim((string)$this->request->getGet('era'));

        $governors = get_site_governors($search ?: null, $era ?: null);

        // จัดเรียงตามลำดับที่ (sequence / order_num)
        usort($governors, function ($a, $b) {
            return (int)($a['sequence'] ?? $a['order_num'] ?? 0) <=> (int)($b['sequence'] ?? $b['order_num'] ?? 0);
        });

        // ดึงรายชื่อยุคสมัยทั้งหมดเพื่อทำตัวกรอง
        $allGovs = get_site_governors();
        $eras = array_unique(array_filter(array_column($allGovs, 'era')));

        $data = [
            'title'        => 'ทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง | จังหวัดพัทลุง',
            'governors'    => $governors,
            'allGovernors' => $allGovs,
            'totalCount'   => count($allGovs),
            'eras'         => $eras,
            'selectedEra'  => $era ?: 'all',
            'searchQuery'  => $search,
            'isOfficer'    => (bool)session()->get('isLoggedIn')
        ];

        return view('governor_hall', $data);
    }
}

<?php

namespace App\Controllers;

class Ita extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index($category = null)
    {
        $selectedCat = $category !== null ? urldecode($category) : 'all';

        $items = get_ita_items($selectedCat === 'all' ? null : $selectedCat, false);
        $scorecard = get_ita_scorecard();
        $categories = get_ita_categories();
        $isOfficer = session()->get('isLoggedIn');

        $data = [
            'title'        => 'ศูนย์การประเมินคุณธรรม ความโปร่งใส (ITA/OIT) & ชุดข้อมูลเปิด (Open Data) | จังหวัดพัทลุง',
            'items'        => $items,
            'scorecard'    => $scorecard,
            'categories'   => $categories,
            'selectedCat'  => $selectedCat,
            'isOfficer'    => $isOfficer
        ];

        return view('ita_portal', $data);
    }

    public function countDownload($id)
    {
        $success = increment_ita_download($id);
        return $this->response->setJSON(['status' => $success ? 'success' : 'error']);
    }
}

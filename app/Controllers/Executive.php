<?php

namespace App\Controllers;

class Executive extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index($category = null)
    {
        $categories = get_executive_categories();
        $selectedCat = $category ? urldecode((string)$category) : 'all';
        $executives = get_site_executives(null, $selectedCat === 'all' ? null : $selectedCat, false);

        $data = [
            'title'       => 'ทำเนียบผู้บริหารและหัวหน้าส่วนราชการ (Executive Leadership) | จังหวัดพัทลุง',
            'categories'  => $categories,
            'selectedCat' => $selectedCat,
            'executives'  => $executives,
            'isOfficer'   => session()->get('isLoggedIn')
        ];

        return view('executive_portal', $data);
    }
}

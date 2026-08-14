<?php

namespace App\Controllers;

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

        // โหลดข้อมูลทั้งหมดให้ DataTables จัดการตัวกรองและการค้นหาสดบนหน้าเบราว์เซอร์
        $items = get_procurement_items('all', true);

        $data = [
            'title'        => 'ศูนย์ข้อมูลข่าวจัดซื้อจัดจ้าง (e-GP) และราคากลาง | จังหวัดพัทลุง',
            'categories'   => $categories,
            'selectedCat'  => $selectedCat,
            'items'        => $items,
            'isOfficer'    => session()->get('isLoggedIn')
        ];

        return view('procurement_portal', $data);
    }
}

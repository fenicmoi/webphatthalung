<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'แผงควบคุมภาพรวมหลังบ้าน | Phatthalung Admin Portal',
            'activeMenu' => 'dashboard',
            'stats' => [
                'users' => '18 ราย',
                'news' => '142 เรื่อง',
                'services_requests' => '36 รายการ',
                'monthly_visitors' => '24,590 ครั้ง'
            ]
        ];

        return view('admin/dashboard', $data);
    }
}

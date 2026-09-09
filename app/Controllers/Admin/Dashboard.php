<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $newsCount         = 0;
        $usersCount        = 0;
        $contactsCount     = 0;
        $emailsCount       = 0;
        $procurementsCount = 0;
        $totalViews        = 0;
        $recentNews        = [];

        try {
            $db = Database::connect();

            // 1. ข่าวสารในระบบ & สถิติการเข้าอ่าน
            if ($db->tableExists('news')) {
                $newsCount = $db->table('news')->countAllResults();
                $viewsRow  = $db->table('news')->selectSum('views_count')->get()->getRow();
                $totalViews = (int)($viewsRow->views_count ?? 0);

                // ดึงรายการข่าวสารล่าสุดจริงจาก Database
                $recentNews = $db->table('news')
                    ->orderBy('created_at', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();
            }

            // 2. ผู้ใช้งาน / เจ้าหน้าที่ในระบบ
            if ($db->tableExists('users')) {
                $usersCount = $db->table('users')->countAllResults();
            }

            // 3. เรื่องติดต่อและข้อร้องเรียนประชาชน
            if ($db->tableExists('citizen_contacts')) {
                $contactsCount = $db->table('citizen_contacts')->countAllResults();
            }

            // 4. จดหมายกลาง (MOI)
            if ($db->tableExists('official_emails')) {
                $emailsCount = $db->table('official_emails')->countAllResults();
            }

            // 5. ประกาศจัดซื้อจัดจ้าง e-GP
            if ($db->tableExists('procurements')) {
                $procurementsCount = $db->table('procurements')->countAllResults();
            }
        } catch (\Throwable $e) {
            log_message('error', 'Dashboard DB stats query error: ' . $e->getMessage());
        }

        // กรณีตาราง news ยังไม่มีข้อมูลแต่มี JSON
        if (empty($recentNews) && function_exists('get_site_news')) {
            $siteNews = get_site_news(5, null, false);
            foreach ($siteNews as $item) {
                $recentNews[] = [
                    'id'          => $item['id'] ?? 0,
                    'title'       => $item['title'] ?? '',
                    'category'    => $item['category'] ?? 'ข่าวประชาสัมพันธ์',
                    'status'      => ($item['active'] ?? true) ? 'published' : 'draft',
                    'views_count' => (int)($item['views'] ?? 0),
                    'created_at'  => $item['created_at'] ?? date('Y-m-d H:i:s'),
                ];
            }
            if ($newsCount === 0) {
                $newsCount = count($siteNews);
            }
        }

        $data = [
            'title'      => 'แผงควบคุมภาพรวมหลังบ้าน | Phatthalung Admin Portal',
            'activeMenu' => 'dashboard',
            'stats'      => [
                'news'              => number_format($newsCount) . ' เรื่อง',
                'news_raw'          => $newsCount,
                'services_requests' => number_format($contactsCount) . ' เรื่อง',
                'services_raw'      => $contactsCount,
                'users'             => number_format($usersCount) . ' ราย',
                'users_raw'         => $usersCount,
                'monthly_visitors'  => number_format(max($totalViews, 1)) . ' ครั้ง',
                'visitors_raw'      => $totalViews,
                'procurements'      => number_format($procurementsCount) . ' รายการ',
                'emails'            => number_format($emailsCount) . ' ฉบับ',
            ],
            'recentNews' => $recentNews,
        ];

        return view('admin/dashboard', $data);
    }
}


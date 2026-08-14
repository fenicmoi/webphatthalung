<?php

namespace App\Controllers;

class Video extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index($category = null)
    {
        $categories = [
            'ทั้งหมด' => 'all',
            'ท่องเที่ยวและธรรมชาติ' => 'ท่องเที่ยวและธรรมชาติ',
            'ศิลปวัฒนธรรมท้องถิ่น' => 'ศิลปวัฒนธรรมท้องถิ่น',
            'ภารกิจและกิจกรรมจังหวัด' => 'ภารกิจและกิจกรรมจังหวัด',
            'ส่งเสริมการท่องเที่ยว' => 'ส่งเสริมการท่องเที่ยว'
        ];
        
        $selectedCat = $category ? urldecode((string)$category) : 'all';
        $videos = get_site_videos(null, $selectedCat === 'all' ? null : $selectedCat, true);

        $data = [
            'title'       => 'ศูนย์รวมสื่อวิดีทัศน์และวีดีโอส่งเสริมการท่องเที่ยว (Phatthalung Web TV) | จังหวัดพัทลุง',
            'categories'  => $categories,
            'selectedCat' => $selectedCat,
            'videos'      => $videos,
            'isOfficer'   => session()->get('isLoggedIn')
        ];

        return view('video_portal', $data);
    }

    /**
     * เพิ่มยอดเข้าชมวิดีโอเมื่อกดเล่นผ่าน Modal
     */
    public function countView($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing ID']);
        }
        $videos = get_site_videos(null, null, false);
        $newCount = 1;
        foreach ($videos as &$v) {
            if ((string)$v['id'] === (string)$id) {
                $v['views'] = ($v['views'] ?? 0) + 1;
                $newCount = $v['views'];
                break;
            }
        }
        save_site_videos($videos);
        return $this->response->setJSON(['status' => 'success', 'views' => $newCount]);
    }
}

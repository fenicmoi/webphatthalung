<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class VideoManager extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url']);
    }

    private function checkOfficerAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized Access. กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
        }
        return null;
    }

    /**
     * หน้าแสดงผลระบบจัดการวิดีโอและสื่อ Web TV (Admin Video Studio)
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'กรุณาเข้าสู่ระบบก่อนเข้าใช้งาน');
        }

        $videos = get_site_videos(null, null, false);
        $categories = [
            'ท่องเที่ยวและธรรมชาติ',
            'ศิลปวัฒนธรรมท้องถิ่น',
            'ภารกิจและกิจกรรมจังหวัด',
            'ส่งเสริมการท่องเที่ยว',
            'ข่าวสารและสารคดีพิเศษ'
        ];

        $data = [
            'title'       => 'ระบบจัดการวิดีทัศน์และสื่อ Web TV | จังหวัดพัทลุง',
            'activeMenu'  => 'videos',
            'videos'      => $videos,
            'categories'  => $categories
        ];

        return view('admin/video_manager', $data);
    }

    /**
     * ดึงข้อมูลวิดีโอเดี่ยวเพื่อแก้ไข
     */
    public function getItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $videos = get_site_videos(null, null, false);
        foreach ($videos as $v) {
            if ((string)($v['id'] ?? '') === (string)$id) {
                return $this->response->setJSON(['status' => 'success', 'data' => $v]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลวิดีโอ']);
    }

    /**
     * บันทึกหรือแก้ไขวิดีโอ YouTube (รองรับการสกัด YouTube ID อัตโนมัติจาก URL)
     */
    public function saveItem(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $id = $this->request->getPost('id');
        $title = trim((string)$this->request->getPost('title'));
        $category = trim((string)$this->request->getPost('category'));
        $youtubeInput = trim((string)$this->request->getPost('youtube_url'));
        $desc = trim((string)$this->request->getPost('desc'));

        if (empty($title) || empty($youtubeInput)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อวิดีโอและลิงก์/ID YouTube']);
        }

        $youtubeId = extract_youtube_id($youtubeInput);
        if (empty($youtubeId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'รูปแบบลิงก์หรือ YouTube ID ไม่ถูกต้อง']);
        }

        $videos = get_site_videos(null, null, false);
        $existingIndex = null;

        if (!empty($id)) {
            foreach ($videos as $idx => $item) {
                if ((string)($item['id'] ?? '') === (string)$id) {
                    $existingIndex = $idx;
                    break;
                }
            }
        }

        $videoId = $id ?: 'vid_' . uniqid();
        $views = ($existingIndex !== null) ? ($videos[$existingIndex]['views'] ?? 0) : 0;

        $videoData = [
            'id'          => $videoId,
            'youtube_id'  => $youtubeId,
            'title'       => $title,
            'category'    => $category ?: 'ส่งเสริมการท่องเที่ยว',
            'views'       => $views,
            'date'        => ($existingIndex !== null) ? ($videos[$existingIndex]['date'] ?? date('Y-m-d')) : date('Y-m-d'),
            'desc'        => $desc ?: 'สื่อประชาสัมพันธ์และวีดิทัศน์ส่งเสริมการเรียนรู้และท่องเที่ยวจังหวัดพัทลุง',
            'active'      => true,
            'featured'    => true
        ];

        if ($existingIndex !== null) {
            $videos[$existingIndex] = $videoData;
            $msg = 'อัปเดตข้อมูลวิดีโอเรียบร้อยแล้ว';
        } else {
            array_unshift($videos, $videoData);
            $msg = 'เพิ่มวิดีโอ YouTube เข้าสู่ระบบ Web TV เรียบร้อยแล้ว';
        }

        save_site_videos($videos);
        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบรายการวิดีโอ
     */
    public function deleteItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่ระบุรหัสวิดีโอที่ต้องการลบ']);
        }

        $videos = get_site_videos(null, null, false);
        $newVideos = array_filter($videos, static function($item) use ($id) {
            return (string)($item['id'] ?? '') !== (string)$id;
        });

        if (count($videos) === count($newVideos)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบวิดีโอในระบบ']);
        }

        save_site_videos(array_values($newVideos));
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบวิดีโอออกจากระบบเรียบร้อยแล้ว']);
    }
}

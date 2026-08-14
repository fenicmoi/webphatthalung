<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class ServiceBannerManager extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('settings');
    }

    public function index()
    {
        $data = [
            'title'      => 'จัดการแบนเนอร์ศูนย์บริการประชาชนและลิงก์ระบบ | Phatthalung Admin',
            'activeMenu' => 'services',
            'banners'    => get_service_banners(false)
        ];

        return view('admin/service_banners', $data);
    }

    public function save()
    {
        $bannersJson = $this->request->getPost('banners_json');
        if (!empty($bannersJson)) {
            $decoded = json_decode($bannersJson, true);
            if (is_array($decoded)) {
                save_service_banners($decoded);
                return $this->respond([
                    'status'  => 'success',
                    'message' => '🎉 บันทึกการตั้งค่าแบนเนอร์และลิงก์บริการเรียบร้อยแล้ว! (มีผลบนหน้าเว็บทันที)'
                ], 200);
            }
        }

        return $this->respond([
            'status'  => 'error',
            'message' => 'ข้อมูลที่ส่งมาไม่ครบถ้วนหรือไม่ถูกต้อง'
        ], 400);
    }

    public function saveInline()
    {
        $id = trim((string)$this->request->getPost('id'));
        if (empty($id)) {
            $id = 'sb-' . time() . rand(10, 99);
        }

        $banners = get_service_banners(false);
        
        $item = [
            'id'          => $id,
            'title'       => trim((string)$this->request->getPost('title')),
            'desc'        => trim((string)$this->request->getPost('desc')),
            'badge'       => trim((string)$this->request->getPost('badge')) ?: 'บริการประชาชน',
            'badge_color' => trim((string)$this->request->getPost('badge_color')) ?: 'primary',
            'url'         => trim((string)$this->request->getPost('url')) ?: '#',
            'target'      => trim((string)$this->request->getPost('target')) ?: '_blank',
            'image'       => trim((string)$this->request->getPost('image')) ?: 'assets/images/banners/eservice_citizen.png',
            'active'      => $this->request->getPost('active') !== '0' && $this->request->getPost('active') !== 'false',
            'sort_order'  => (int)($this->request->getPost('sort_order') ?: (count($banners) + 1))
        ];

        $found = false;
        foreach ($banners as $index => $exist) {
            if (strval($exist['id']) === strval($id)) {
                $item['sort_order'] = $exist['sort_order']; // preserve sort order if editing unless explicitly changed
                if ($this->request->getPost('sort_order')) {
                    $item['sort_order'] = (int)$this->request->getPost('sort_order');
                }
                $banners[$index] = $item;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $banners[] = $item;
        }

        // re-sort by sort_order
        usort($banners, static function($a, $b) {
            return ($a['sort_order'] ?? 999) - ($b['sort_order'] ?? 999);
        });

        save_service_banners($banners);

        return $this->respond([
            'status'  => 'success',
            'id'      => $id,
            'item'    => $item,
            'message' => '🎉 บันทึกแบนเนอร์และลิงก์บริการ "' . $item['title'] . '" สำเร็จแล้ว!'
        ], 200);
    }

    public function deleteById($id)
    {
        $banners = get_service_banners(false);
        $newBanners = [];
        $deleted = false;

        foreach ($banners as $item) {
            if (strval($item['id']) !== strval($id)) {
                $newBanners[] = $item;
            } else {
                $deleted = true;
            }
        }

        if ($deleted) {
            save_service_banners($newBanners);
            return $this->respond([
                'status'  => 'success',
                'message' => '🗑️ ลบแบนเนอร์บริการเรียบร้อยแล้ว'
            ], 200);
        }

        return $this->respond([
            'status'  => 'error',
            'message' => 'ไม่พบแบนเนอร์ที่ต้องการลบ'
        ], 404);
    }

    public function getAllJson()
    {
        return $this->respond([
            'status'  => 'success',
            'banners' => get_service_banners(false)
        ], 200);
    }

    public function upload()
    {
        $file = $this->request->getFile('image') ?: $this->request->getFile('slide_image') ?: $this->request->getFile('file');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->respond(['status' => 'error', 'message' => 'การอัปโหลดไฟล์ภาพล้มเหลว กรุณาตรวจสอบไฟล์'], 400);
        }

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'service_banners';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $newName = 'banner_' . time() . '_' . rand(100, 999) . '.' . $file->getExtension();
        $file->move($uploadDir, $newName);
        $relativePath = 'uploads/service_banners/' . $newName;

        return $this->respond([
            'status' => 'success',
            'path'   => $relativePath,
            'url'    => base_url($relativePath),
            'message' => 'อัปโหลดรูปภาพแบนเนอร์สำเร็จ!'
        ], 200);
    }

    public function reset()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'service_banners.json';
        if (is_file($jsonPath)) {
            @unlink($jsonPath);
        }

        return $this->respond([
            'status'  => 'success',
            'message' => '🔄 คืนค่าแบนเนอร์บริการและลิงก์เป็นข้อมูลเริ่มต้นสำเร็จ'
        ], 200);
    }
}

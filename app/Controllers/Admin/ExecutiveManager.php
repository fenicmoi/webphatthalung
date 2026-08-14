<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ExecutiveManager extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url', 'form']);
    }

    private function checkOfficerAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized Access. กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
        }
        return null;
    }

    /**
     * ดึงข้อมูลผู้บริหารเพื่อแก้ไขใน Studio
     */
    public function getItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $execs = get_site_executives(null, null, false);
        foreach ($execs as $item) {
            if ((string)($item['id'] ?? '') === (string)$id) {
                return $this->response->setJSON(['status' => 'success', 'data' => $item]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้บริหารท่านนี้ในระบบ']);
    }

    /**
     * บันทึกหรือแก้ไขข้อมูลทำเนียบผู้บริหาร (รองรับอัปโหลดภาพถ่ายประจำตำแหน่ง)
     */
    public function saveItem(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $id = $this->request->getPost('id');
        $name = trim((string)$this->request->getPost('name'));
        $position = trim((string)$this->request->getPost('position'));
        $category = trim((string)$this->request->getPost('category'));
        $quote = trim((string)$this->request->getPost('quote'));
        $phone = trim((string)$this->request->getPost('phone'));
        $email = trim((string)$this->request->getPost('email'));
        $externalPhoto = trim((string)$this->request->getPost('photo_url'));
        $orderNum = (int)($this->request->getPost('order_num') ?? 99);
        $featured = !empty($this->request->getPost('featured'));

        if (empty($name) || empty($position)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อและตำแหน่งของผู้บริหาร']);
        }

        $execs = get_site_executives(null, null, false);
        $existingIndex = null;

        if (!empty($id)) {
            foreach ($execs as $idx => $item) {
                if ((string)($item['id'] ?? '') === (string)$id) {
                    $existingIndex = $idx;
                    break;
                }
            }
        }

        $execId = $id ?: 'exec_' . uniqid();
        $photo = ($existingIndex !== null) ? ($execs[$existingIndex]['photo'] ?? '') : ($externalPhoto ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop');

        // จัดการไฟล์อัปโหลดรูปภาพประจำตำแหน่ง
        $imgFile = $this->request->getFile('photo_file');
        if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/executives';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            $newName = 'exec_' . time() . '_' . $imgFile->getRandomName();
            $imgFile->move($uploadPath, $newName);
            $photo = 'uploads/executives/' . $newName;
        } elseif (!empty($externalPhoto) && $externalPhoto !== $photo) {
            $photo = $externalPhoto;
        }

        $execData = [
            'id'        => $execId,
            'name'      => $name,
            'position'  => $position,
            'category'  => $category ?: 'คณะผู้บริหารระดับสูง',
            'quote'     => $quote,
            'phone'     => $phone,
            'email'     => $email,
            'photo'     => $photo,
            'order_num' => $orderNum,
            'featured'  => $featured,
            'active'    => true
        ];

        if ($existingIndex !== null) {
            $execs[$existingIndex] = $execData;
            $msg = 'อัปเดตข้อมูลผู้บริหารและวิสัยทัศน์เรียบร้อยแล้ว';
        } else {
            $execs[] = $execData;
            $msg = 'เพิ่มรายนามเข้าสู่ระบบทำเนียบผู้บริหารเรียบร้อยแล้ว';
        }

        // จัดเรียงตามลำดับ order_num ใหม่
        usort($execs, static function($a, $b) {
            return (int)($a['order_num'] ?? 99) - (int)($b['order_num'] ?? 99);
        });

        save_site_executives($execs);
        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบรายการผู้บริหาร
     */
    public function deleteItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่ระบุรหัสรายการที่ต้องการลบ']);
        }

        $execs = get_site_executives(null, null, false);
        $newExecs = array_filter($execs, static function($item) use ($id) {
            return (string)($item['id'] ?? '') !== (string)$id;
        });

        if (count($execs) === count($newExecs)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้บริหารในระบบ']);
        }

        save_site_executives(array_values($newExecs));
        return $this->response->setJSON(['status' => 'success', 'message' => 'นำรายนามผู้บริหารออกจากระบบเรียบร้อยแล้ว']);
    }
}

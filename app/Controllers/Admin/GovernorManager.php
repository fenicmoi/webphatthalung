<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class GovernorManager extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url', 'form']);
    }

    private function checkAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized Access. กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
        }
        return null;
    }

    /**
     * หน้าจัดการทำเนียบผู้ว่าราชการจังหวัด (Admin Studio)
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $governors = get_site_governors();
        usort($governors, function ($a, $b) {
            return (int)($a['sequence'] ?? 0) <=> (int)($b['sequence'] ?? 0);
        });

        $data = [
            'title'     => 'จัดการทำเนียบผู้ว่าราชการจังหวัด | ระบบบริหารจัดการ',
            'governors' => $governors
        ];

        return view('admin/governor_manager', $data);
    }

    /**
     * ดึงข้อมูลผู้ว่าฯ 1 ท่านเพื่อแก้ไข
     */
    public function getItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkAuth()) return $auth;

        $governors = get_site_governors();
        foreach ($governors as $g) {
            if ((string)($g['id'] ?? '') === (string)$id) {
                return $this->response->setJSON(['status' => 'success', 'data' => $g]);
            }
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ว่าราชการจังหวัด']);
    }

    /**
     * บันทึกหรือแก้ไขข้อมูลทำเนียบผู้ว่าฯ
     */
    public function saveItem(): ResponseInterface
    {
        if ($auth = $this->checkAuth()) return $auth;

        $id          = $this->request->getPost('id') ?? $this->request->getVar('id') ?? ($_POST['id'] ?? null);
        $sequence    = (int)($this->request->getPost('sequence') ?? $this->request->getVar('sequence') ?? ($_POST['sequence'] ?? 1));
        $name        = trim((string)($this->request->getPost('name') ?? $this->request->getVar('name') ?? ($_POST['name'] ?? '')));
        $titleHonor  = trim((string)($this->request->getPost('title_honor') ?? $this->request->getVar('title_honor') ?? ($_POST['title_honor'] ?? '')));
        $period      = trim((string)($this->request->getPost('period') ?? $this->request->getVar('period') ?? ($_POST['period'] ?? '')));
        $era         = trim((string)($this->request->getPost('era') ?? $this->request->getVar('era') ?? ($_POST['era'] ?? '')));
        $achievement = trim((string)($this->request->getPost('achievement') ?? $this->request->getVar('achievement') ?? ($_POST['achievement'] ?? '')));
        $imageUrl    = trim((string)($this->request->getPost('image_url') ?? $this->request->getVar('image_url') ?? ($_POST['image_url'] ?? '')));
        $isCurrent   = (bool)($this->request->getPost('is_current') ?? $this->request->getVar('is_current') ?? ($_POST['is_current'] ?? false));

        if (empty($name) || empty($period)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกชื่อและระยะเวลาดำรงตำแหน่งให้ครบถ้วน']);
        }

        $governors = get_site_governors();
        $existingIndex = null;

        if (!empty($id)) {
            foreach ($governors as $idx => $g) {
                if ((string)($g['id'] ?? '') === (string)$id) {
                    $existingIndex = $idx;
                    break;
                }
            }
        }

        $finalImage = ($existingIndex !== null) ? ($governors[$existingIndex]['image'] ?? '') : ($imageUrl ?: '');

        // จัดการอัปโหลดไฟล์รูปภาพจริง (ถ้ามี)
        $imgFile = $this->request->getFile('image_file');
        if ($imgFile && $imgFile->isValid() && !$imgFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/governors';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            $newName = 'gov_' . time() . '_' . $imgFile->getRandomName();
            $imgFile->move($uploadPath, $newName);
            $finalImage = 'uploads/governors/' . $newName;
        } elseif (!empty($imageUrl)) {
            $finalImage = $imageUrl;
        }

        $govId = $id ?: 'gov_' . uniqid();

        $govData = [
            'id'          => $govId,
            'sequence'    => $sequence,
            'name'        => $name,
            'title_honor' => $titleHonor ?: 'ผู้ว่าราชการจังหวัดพัทลุง',
            'period'      => $period,
            'era'         => $era ?: 'ยุคปัจจุบัน',
            'image'       => $finalImage,
            'achievement' => $achievement,
            'is_current'  => $isCurrent,
            'order_num'   => $sequence
        ];

        // ถ้าตั้งให้เป็นคนปัจจุบัน ให้ปรับคนอื่นเป็น false
        if ($isCurrent) {
            foreach ($governors as &$otherGov) {
                if (($otherGov['id'] ?? '') !== $govId) {
                    $otherGov['is_current'] = false;
                }
            }
            unset($otherGov);
        }

        if ($existingIndex !== null) {
            $governors[$existingIndex] = $govData;
            $msg = 'อัปเดตข้อมูลทำเนียบผู้ว่าราชการจังหวัดเรียบร้อยแล้ว';
        } else {
            $governors[] = $govData;
            $msg = 'เพิ่มรายนามเข้าสู่ทำเนียบผู้ว่าราชการจังหวัดเรียบร้อยแล้ว';
        }

        // จัดเรียงตามลำดับที่
        usort($governors, function ($a, $b) {
            return (int)($a['sequence'] ?? 0) <=> (int)($b['sequence'] ?? 0);
        });

        save_site_governors($governors);

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบรายการทำเนียบผู้ว่าฯ
     */
    public function deleteItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkAuth()) return $auth;

        $governors = get_site_governors();
        $filtered = array_values(array_filter($governors, function ($g) use ($id) {
            return (string)($g['id'] ?? '') !== (string)$id;
        }));

        if (count($filtered) === count($governors)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการลบ']);
        }

        save_site_governors($filtered);
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบรายนามออกจากทำเนียบเรียบร้อยแล้ว']);
    }
}

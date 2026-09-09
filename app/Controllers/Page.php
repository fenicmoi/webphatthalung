<?php

namespace App\Controllers;

use App\Models\PageModel;

class Page extends BaseController
{
    public function _remap($method, ...$params)
    {
        if ($method === 'view') {
            return $this->view(...$params);
        }
        return $this->view($method);
    }

    public function view($slug = null)
    {
        if (empty($slug)) {
            return redirect()->to('/');
        }

        helper(['url', 'settings']);

        $page = null;
        $children = [];
        $pageModel = null;

        // 1. ลองดึงข้อมูลเพจจากฐานข้อมูล
        try {
            $pageModel = new PageModel();
            $page = $pageModel->where('slug', $slug)->first();
        } catch (\Throwable $e) {
            log_message('error', 'Page query error: ' . $e->getMessage());
        }

        // 2. หากยังไม่พบในฐานข้อมูล (เช่น ฐานข้อมูลยังไม่ได้นำเข้าตาราง pages หรือยังว่างเปล่า) ให้ดึงจาก dump_pages.json อัตโนมัติ
        if (empty($page)) {
            $jsonFile = WRITEPATH . 'dump_pages.json';
            if (file_exists($jsonFile)) {
                $allPages = json_decode(file_get_contents($jsonFile), true) ?: [];
                foreach ($allPages as $p) {
                    if (($p['slug'] ?? '') === $slug) {
                        $page = $p;
                        break;
                    }
                }
            }
        }

        // 3. Fallback ข้อมูลพื้นฐานในกรณีฉุกเฉิน (หากไม่มีทั้งใน DB และ dump file)
        if (empty($page) && $slug === 'general') {
            $page = [
                'id'           => 1,
                'parent_id'    => null,
                'order_num'    => 0,
                'title'        => 'ข้อมูลทั่วไปจังหวัดพัทลุง',
                'slug'         => 'general',
                'header_image' => 'uploads/pages/header_1787197577_7804.png',
                'content'      => '<p><img src="' . base_url('uploads/pages/page_1787280116_8602.png') . '" alt="ข้อมูลทั่วไปจังหวัดพัทลุง" class="img-fluid rounded shadow-sm"></p>',
                'views'        => 50,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        // หากไม่พบเพจจริงๆ
        if (!$page) {
            return redirect()->to(base_url())->with('error', 'ไม่พบหน้าที่ต้องการ');
        }

        // 4. อัปเดตยอดผู้เข้าชมอย่างปลอดภัย (ไม่กระทบการแสดงผลหาก DB มีปัญหา)
        if (!empty($pageModel) && !empty($page['id'])) {
            try {
                $pageModel->builder()
                          ->where('id', $page['id'])
                          ->set('views', 'views + 1', false)
                          ->update();
            } catch (\Throwable $e) {
                // ละเว้นหาก views column ไม่มีหรือ update ล้มเหลว
            }
        }

        // 5. ดึงเพจย่อย (Children) อย่างปลอดภัย
        if (!empty($page['id'])) {
            try {
                if (!empty($pageModel) && $pageModel->db->fieldExists('parent_id', 'pages')) {
                    $children = $pageModel->where('parent_id', $page['id'])
                                          ->orderBy('order_num', 'ASC')
                                          ->findAll();
                }
            } catch (\Throwable $e) {
                $children = [];
            }

            // หากไม่มีเพจย่อยใน DB ให้หาใน dump_pages.json
            if (empty($children)) {
                $jsonFile = WRITEPATH . 'dump_pages.json';
                if (file_exists($jsonFile)) {
                    $allPages = json_decode(file_get_contents($jsonFile), true) ?: [];
                    foreach ($allPages as $p) {
                        if (!empty($p['parent_id']) && (int)$p['parent_id'] === (int)$page['id']) {
                            $children[] = $p;
                        }
                    }
                }
            }
        }

        $data = [
            'title'    => $page['title'] ?? 'ข้อมูลทั่วไปจังหวัดพัทลุง',
            'page'     => $page,
            'children' => $children
        ];

        return view('pages/view', $data);
    }
}

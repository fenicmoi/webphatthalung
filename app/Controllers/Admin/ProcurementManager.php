<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class ProcurementManager extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('settings');
    }

    public function index()
    {
        $data = [
            'title'      => 'จัดการข้อมูลข่าวจัดซื้อจัดจ้างภาครัฐ (e-GP & Transparency) | Phatthalung Admin',
            'activeMenu' => 'procurement',
            'items'      => get_procurement_items(null, false),
            'categories' => get_procurement_categories()
        ];

        return view('admin/procurement_manager', $data);
    }

    public function getInline($id)
    {
        $item = get_procurement_by_id($id);
        if ($item) {
            return $this->respond([
                'status' => 'success',
                'item'   => $item
            ], 200);
        }
        return $this->respond([
            'status'  => 'error',
            'message' => 'ไม่พบรายการจัดซื้อจัดจ้างดังกล่าว'
        ], 404);
    }

    public function saveInline()
    {
        $id = trim((string)$this->request->getPost('id'));
        if (empty($id)) {
            $id = 'proc-' . time() . rand(10, 99);
        }

        $items = get_procurement_items(null, false);
        
        $attachmentUrl = trim((string)$this->request->getPost('attachment_url'));
        
        // Handle uploaded file if present
        $file = $this->request->getFile('doc_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadPath = ROOTPATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'docs';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            $attachmentUrl = 'uploads/docs/' . $newName;
        }

        if (empty($attachmentUrl)) {
            $attachmentUrl = 'assets/docs/egp_sample_101.pdf';
        }

        $newItem = [
            'id'             => $id,
            'title'          => trim((string)$this->request->getPost('title')),
            'category'       => trim((string)$this->request->getPost('category')) ?: 'ประกาศจัดซื้อจัดจ้าง',
            'date'           => trim((string)$this->request->getPost('date')) ?: date('Y-m-d'),
            'views'          => (int)($this->request->getPost('views') ?: 1),
            'budget'         => trim((string)$this->request->getPost('budget')) ?: '-',
            'attachment_url' => $attachmentUrl,
            'active'         => $this->request->getPost('active') !== '0' && $this->request->getPost('active') !== 'false'
        ];

        $found = false;
        foreach ($items as $index => $exist) {
            if (strval($exist['id']) === strval($id)) {
                $newItem['views'] = isset($exist['views']) ? (int)$exist['views'] : $newItem['views'];
                $items[$index] = $newItem;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $items[] = $newItem;
        }

        save_procurement_items($items);

        return $this->respond([
            'status'  => 'success',
            'id'      => $id,
            'item'    => $newItem,
            'message' => '🎉 บันทึกข้อมูลประกาศ "' . $newItem['title'] . '" สำเร็จแล้ว! (แสดงบนหน้าเว็บทันที)'
        ], 200);
    }

    public function deleteInline($id)
    {
        $id = trim((string)$id);
        $items = get_procurement_items(null, false);
        $newItems = [];
        $found = false;

        foreach ($items as $item) {
            if (strval($item['id']) !== strval($id)) {
                $newItems[] = $item;
            } else {
                $found = true;
            }
        }

        if ($found) {
            save_procurement_items($newItems);
            return $this->respond([
                'status'  => 'success',
                'message' => 'ลบประกาศจัดซื้อจัดจ้างเรียบร้อยแล้ว'
            ], 200);
        }

        return $this->respond([
            'status'  => 'error',
            'message' => 'ไม่พบประกาศที่ต้องการลบในระบบ'
        ], 404);
    }
}

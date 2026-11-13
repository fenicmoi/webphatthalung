<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProcurementModel;
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
        $model = new ProcurementModel();

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

        $budgetRaw = trim((string)$this->request->getPost('budget') ?? '');
        $budgetClean = preg_replace('/[^0-9.]/', '', $budgetRaw);
        $budgetValue = !empty($budgetClean) ? (float)$budgetClean : 0.0;

        $statusPost = $this->request->getPost('active');
        $status = ($statusPost === '0' || $statusPost === 'false' || $statusPost === false) ? 'inactive' : 'active';

        $data = [
            'title'          => trim((string)$this->request->getPost('title')),
            'category'       => trim((string)$this->request->getPost('category')) ?: 'ประกาศจัดซื้อจัดจ้าง',
            'published_date' => trim((string)$this->request->getPost('date')) ?: date('Y-m-d'),
            'budget'         => $budgetValue,
            'method'         => trim((string)$this->request->getPost('method')) ?: 'เฉพาะเจาะจง',
            'doc_path'       => $attachmentUrl,
            'status'         => $status
        ];

        if (!empty($id) && is_numeric($id) && (int)$id > 0) {
            $model->update((int)$id, $data);
            $itemId = (int)$id;
        } else {
            $itemId = $model->insert($data, true);
        }

        // Sync to JSON backup
        $allItems = get_procurement_items(null, false);
        save_procurement_items($allItems);

        $newItem = get_procurement_by_id($itemId) ?? [
            'id'             => $itemId,
            'title'          => $data['title'],
            'category'       => $data['category'],
            'date'           => $data['published_date'],
            'views'          => 1,
            'budget'         => number_format($budgetValue, 2) . ' บาท',
            'attachment_url' => $attachmentUrl,
            'active'         => ($status === 'active')
        ];

        return $this->respond([
            'status'  => 'success',
            'id'      => $itemId,
            'item'    => $newItem,
            'message' => '🎉 บันทึกข้อมูลประกาศ "' . $data['title'] . '" สำเร็จแล้ว! (แสดงบนหน้าเว็บทันที)'
        ], 200);
    }

    public function deleteInline($id)
    {
        $id = (int)$id;
        $model = new ProcurementModel();

        if ($id > 0 && $model->find($id)) {
            $model->delete($id);
            // Sync to JSON backup
            $allItems = get_procurement_items(null, false);
            save_procurement_items($allItems);

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

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CitizenContactModel;

class ContactManager extends BaseController
{
    /**
     * Admin Contact & Complaint Management Dashboard
     */
    public function index()
    {
        $contactModel = new CitizenContactModel();

        $search   = trim($this->request->getGet('q') ?? '');
        $status   = trim($this->request->getGet('status') ?? '');
        $category = trim($this->request->getGet('category') ?? '');

        $builder = $contactModel;

        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('tracking_code', $search)
                ->orLike('full_name', $search)
                ->orLike('phone', $search)
                ->orLike('subject', $search)
                ->orLike('message', $search)
                ->groupEnd();
        }

        if (!empty($status)) {
            $builder = $builder->where('status', $status);
        }

        if (!empty($category)) {
            $builder = $builder->where('category', $category);
        }

        $items = $builder->orderBy('created_at', 'DESC')->paginate(15);
        $pager = $contactModel->pager;

        // Statistics Summary
        $db = \Config\Database::connect();
        $stats = [
            'total'       => $db->table('citizen_contacts')->countAllResults(),
            'pending'     => $db->table('citizen_contacts')->where('status', 'pending')->countAllResults(),
            'in_progress' => $db->table('citizen_contacts')->where('status', 'in_progress')->countAllResults(),
            'resolved'    => $db->table('citizen_contacts')->where('status', 'resolved')->countAllResults(),
        ];

        $data = [
            'title'       => 'จัดการเรื่องติดต่อและข้อร้องเรียนประชาชน | Admin Portal',
            'activeMenu'  => 'contact_manager',
            'items'       => $items,
            'pager'       => $pager,
            'stats'       => $stats,
            'search'      => $search,
            'currStatus'  => $status,
            'currCat'     => $category,
            'categories'  => CitizenContactModel::getCategories(),
            'statuses'    => CitizenContactModel::getStatuses(),
            'districts'   => CitizenContactModel::getDistricts(),
        ];

        return view('admin/contact_manager', $data);
    }

    /**
     * Get Record Detail (JSON)
     */
    public function detail($id)
    {
        $contactModel = new CitizenContactModel();
        $item = $contactModel->find($id);

        if (!$item) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'ไม่พบข้อมูล'
            ]);
        }

        $categories = CitizenContactModel::getCategories();
        $statuses   = CitizenContactModel::getStatuses();

        $item['category_name'] = $categories[$item['category']]['name'] ?? $item['category'];
        $item['status_info']   = $statuses[$item['status']] ?? ['name' => $item['status']];
        $item['attachment_url'] = !empty($item['attachment']) ? base_url('uploads/contacts/' . $item['attachment']) : null;
        $item['created_at_fmt'] = date('d/m/Y H:i:s น.', strtotime($item['created_at']));
        $item['updated_at_fmt'] = !empty($item['updated_at']) ? date('d/m/Y H:i:s น.', strtotime($item['updated_at'])) : '-';

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $item
        ]);
    }

    /**
     * Update Status & Officer Notes
     */
    public function updateStatus($id)
    {
        $contactModel = new CitizenContactModel();
        $item = $contactModel->find($id);

        if (!$item) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ไม่พบข้อมูลคำร้อง'
            ]);
        }

        $newStatus   = $this->request->getPost('status');
        $officerNote = $this->request->getPost('officer_note');

        $updateData = [
            'status'       => $newStatus,
            'officer_note' => trim($officerNote ?? ''),
        ];

        if ($newStatus === 'resolved' && empty($item['resolved_at'])) {
            $updateData['resolved_at'] = date('Y-m-d H:i:s');
        }

        $contactModel->update($id, $updateData);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'อัปเดตสถานะและบันทึกข้อความตอบกลับเรียบร้อยแล้ว'
        ]);
    }

    /**
     * Delete Contact / Complaint Record
     */
    public function delete($id)
    {
        $contactModel = new CitizenContactModel();
        $item = $contactModel->find($id);

        if ($item) {
            if (!empty($item['attachment'])) {
                $filePath = FCPATH . 'uploads/contacts/' . $item['attachment'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            $contactModel->delete($id);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'ลบรายการเรียบร้อยแล้ว'
        ]);
    }
}

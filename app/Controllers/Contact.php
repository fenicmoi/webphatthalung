<?php

namespace App\Controllers;

use App\Models\CitizenContactModel;
use App\Libraries\LineNotifyService;
use CodeIgniter\HTTP\ResponseInterface;

class Contact extends BaseController
{
    /**
     * Display Public Citizen Contact & Complaint Portal
     */
    public function index()
    {
        $contactModel = new CitizenContactModel();

        $data = [
            'title'       => 'ติดต่อจังหวัด & ศูนย์บริการประชาชน | ศาลากลางจังหวัดพัทลุง',
            'categories'  => CitizenContactModel::getCategories(),
            'districts'   => CitizenContactModel::getDistricts(),
            'siteConfig'  => function_exists('get_site_settings') ? get_site_settings() : [],
        ];

        return view('contact_portal', $data);
    }

    /**
     * Handle Public Form Submission (Asynchronous JSON API)
     */
    public function submit()
    {
        if (!$this->request->isAJAX() && !$this->request->is('post')) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Invalid request method.'
            ]);
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'phone'     => 'required|min_length[8]|max_length[50]',
            'subject'   => 'required|min_length[4]|max_length[255]',
            'message'   => 'required|min_length[10]',
            'category'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => implode('<br>', $this->validator->getErrors())
            ]);
        }

        $contactModel = new CitizenContactModel();

        // Handle File Attachment (Image / PDF up to 10MB)
        $attachmentName = null;
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
            if (in_array($file->getMimeType(), $allowedMimes)) {
                $uploadPath = FCPATH . 'uploads/contacts/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $attachmentName = $file->getRandomName();
                $file->move($uploadPath, $attachmentName);
            }
        }

        $trackingCode = CitizenContactModel::generateTrackingCode();
        $categoryKey  = $this->request->getPost('category');
        $allCategories = CitizenContactModel::getCategories();
        $categoryName = $allCategories[$categoryKey]['name'] ?? $categoryKey;

        $insertData = [
            'tracking_code' => $trackingCode,
            'full_name'     => trim($this->request->getPost('full_name')),
            'phone'         => trim($this->request->getPost('phone')),
            'email'         => trim($this->request->getPost('email') ?? ''),
            'district'      => trim($this->request->getPost('district') ?? 'เมืองพัทลุง'),
            'category'      => $categoryKey,
            'subject'       => trim($this->request->getPost('subject')),
            'message'       => trim($this->request->getPost('message')),
            'attachment'    => $attachmentName,
            'status'        => 'pending',
            'ip_address'    => $this->request->getIPAddress(),
        ];

        try {
            $contactModel->insert($insertData);

            // Trigger Real-time Officer LINE and Official Email (phatthalung@moi.go.th)
            $insertData['category_name'] = $categoryName;
            $notifyResult = LineNotifyService::notifyNewContact($insertData);

            return $this->response->setJSON([
                'status'        => 'success',
                'tracking_code' => $trackingCode,
                'message'       => 'บันทึกเรื่องและส่งการแจ้งเตือนไปยังเจ้าหน้าที่เรียบร้อยแล้ว',
                'data'          => [
                    'tracking_code' => $trackingCode,
                    'created_at'    => date('d/m/Y H:i น.')
                ]
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Contact::submit] ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Real-time Tracking Status API
     */
    public function track($code = null)
    {
        $code = trim($code ?? $this->request->getGet('code') ?? '');
        if (empty($code)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'กรุณาระบุรหัสติดตามเรื่อง (Tracking ID)'
            ]);
        }

        $contactModel = new CitizenContactModel();
        $record = $contactModel->where('tracking_code', $code)->first();

        if (!$record) {
            return $this->response->setJSON([
                'status'  => 'not_found',
                'message' => 'ไม่พบข้อมูลคำร้องจากรหัส ' . htmlspecialchars($code) . ' กรุณาตรวจสอบรหัสอีกครั้ง'
            ]);
        }

        $statuses   = CitizenContactModel::getStatuses();
        $categories = CitizenContactModel::getCategories();

        return $this->response->setJSON([
            'status'        => 'success',
            'tracking_code' => $record['tracking_code'],
            'full_name'     => mb_substr($record['full_name'], 0, 3) . '***',
            'subject'       => $record['subject'],
            'category_name' => $categories[$record['category']]['name'] ?? $record['category'],
            'district'      => $record['district'],
            'status_key'    => $record['status'],
            'status_info'   => $statuses[$record['status']] ?? ['name' => $record['status'], 'badge' => 'bg-secondary text-white'],
            'officer_note'  => $record['officer_note'] ?? 'อยู่ระหว่างการตรวจสอบและประสานงานของเจ้าหน้าที่',
            'created_at'    => date('d/m/Y H:i น.', strtotime($record['created_at'])),
            'updated_at'    => !empty($record['updated_at']) ? date('d/m/Y H:i น.', strtotime($record['updated_at'])) : '-',
        ]);
    }
}

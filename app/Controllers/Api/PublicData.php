<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PublicData extends BaseController
{
    use ResponseTrait;

    /**
     * ดึงรายการข่าวสารตามหมวดหมู่ (แบบ Async / No-Reload) จากฐานข้อมูล MySQL เท่านั้น
     */
    public function getNews()
    {
        $category = $this->request->getGet('category');
        helper('settings');

        $newsList = get_site_news(20, $category, true);
        $formatted = [];
        foreach ($newsList as $item) {
            $formatted[] = [
                'id'             => $item['id'],
                'title'          => $item['title'],
                'category_label' => $item['category'],
                'date'           => !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : date('d/m/Y'),
                'views'          => $item['views'] ?? 0,
                'badge_color'    => '#10b981',
                'excerpt'        => $item['summary'] ?? '',
                'cover_image'    => $item['cover_image'] ?? '',
                'url'            => base_url('news/detail/' . $item['id']),
            ];
        }

        return $this->respond([
            'status'    => 200,
            'category'  => $category ?: 'all',
            'data'      => $formatted,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * บันทึกข้อมูลคำร้องบริการประชาชน (แบบ Async / No-Reload Form POST)
     */
    public function submitRequest()
    {
        $fullName    = trim($this->request->getPost('full_name') ?? '');
        $contactInfo = trim($this->request->getPost('contact_info') ?? '');
        $serviceType = trim($this->request->getPost('service_type') ?? 'บริการทั่วไป');
        $description = trim($this->request->getPost('description') ?? '');

        if (empty($fullName) || empty($description)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'กรุณากรอกชื่อ-นามสกุล และรายละเอียดคำร้องให้ครบถ้วนก่อนส่ง'
            ], 400);
        }

        $contactModel = new \App\Models\CitizenContactModel();
        $trackingCode = \App\Models\CitizenContactModel::generateTrackingCode();

        $phone = '';
        $email = '';
        if (str_contains($contactInfo, '@')) {
            $email = $contactInfo;
        } else {
            $phone = $contactInfo;
        }

        $insertData = [
            'tracking_code' => $trackingCode,
            'full_name'     => $fullName,
            'phone'         => !empty($phone) ? $phone : 'ระบุในข้อความ',
            'email'         => !empty($email) ? $email : null,
            'district'      => 'เมืองพัทลุง',
            'category'      => 'general',
            'subject'       => $serviceType,
            'message'       => $description,
            'status'        => 'pending',
            'ip_address'    => $this->request->getIPAddress(),
        ];

        try {
            $contactModel->insert($insertData);
            $insertData['category_name'] = $serviceType;
            \App\Libraries\LineNotifyService::notifyNewContact($insertData);
        } catch (\Throwable $e) {
            log_message('error', '[PublicData::submitRequest] ' . $e->getMessage());
        }

        return $this->respond([
            'status'        => 'success',
            'tracking_code' => $trackingCode,
            'message'       => 'ระบบทำการรับเรื่องเรียบร้อย! รหัสติดตามของคุณคือ ' . $trackingCode . ' เจ้าหน้าที่ที่เกี่ยวข้องจะเร่งดำเนินการภายใน 24 ชม.'
        ], 200);
    }
}

<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class PublicData extends BaseController
{
    use ResponseTrait;

    /**
     * ดึงรายการข่าวสารตามหมวดหมู่ (แบบ Async / No-Reload)
     */
    public function getNews()
    {
        $category = $this->request->getGet('category') ?? 'general';
        
        // จำลองข้อมูลข่าวสารตามหมวดหมู่ (พร้อมเปลี่ยนเป็น Model ได้ทันที)
        $allNews = [
            'general' => [
                [
                    'id' => 1,
                    'title' => 'ประกาศผลการคัดเลือกและมอบรางวัลชุมชนเกษตรก้าวหน้า ประจำปีงบประมาณ',
                    'category_label' => 'ข่าวประชาสัมพันธ์',
                    'date' => '03/08/2026',
                    'views' => 482,
                    'badge_color' => '#3b82f6',
                    'excerpt' => 'มุ่งเน้นการพัฒนานวัตกรรมการประยุกต์ใช้ภูมิปัญญาร่วมกับระบบเทคโนโลยีการเพาะปลูกอัจฉริยะในพื้นที่'
                ],
                [
                    'id' => 2,
                    'title' => 'ประชาสัมพันธ์เปิดจองสิทธิ์เข้าร่วมตลาดนัดสินค้าพื้นเมืองจังหวัดพัทลุงประจำเดือน',
                    'category_label' => 'ข่าวประชาสัมพันธ์',
                    'date' => '02/08/2026',
                    'views' => 315,
                    'badge_color' => '#3b82f6',
                    'excerpt' => 'ส่งเสริมวิสาหกิจชุมชนและผู้ประกอบการท้องถิ่นในการขยายช่องทางการจำหน่ายสินค้าคุณภาพสูง'
                ],
                [
                    'id' => 3,
                    'title' => 'แจ้งกำหนดการปรับปรุงระบบท่อส่งน้ำหลัก เพื่อเพิ่มศักยภาพการให้บริการ',
                    'category_label' => 'ประกาศสำนักชลประทาน',
                    'date' => '01/08/2026',
                    'views' => 890,
                    'badge_color' => '#f59e0b',
                    'excerpt' => 'ขอภัยในความไม่สะดวกมา ณ ที่นี้ ระบบจะกลับมาใช้งานได้อย่างเต็มรูปแบบภายในช่วงค่ำของวันนี้'
                ]
            ],
            'procurement' => [
                [
                    'id' => 4,
                    'title' => 'ประกวดราคาซื้อครุภัณฑ์คอมพิวเตอร์และเครือข่ายศูนย์บริการสาธารณะ ด้วยวิธี e-bidding',
                    'category_label' => 'จัดซื้อจัดจ้าง',
                    'date' => '03/08/2026',
                    'views' => 140,
                    'badge_color' => '#10b981',
                    'excerpt' => 'วงเงินงบประมาณ 1,450,000 บาท สิ้นสุดรับยื่นข้อเสนอผ่านระบบเครือข่ายอิเล็กทรอนิกส์ในสัปดาห์ถัดไป'
                ],
                [
                    'id' => 5,
                    'title' => 'สรุปผลการจัดซื้อจัดจ้างประจำเดือน (แบบ สขร.1) ประจำไตรมาสล่าสุด',
                    'category_label' => 'ผลจัดซื้อจัดจ้าง',
                    'date' => '28/07/2026',
                    'views' => 210,
                    'badge_color' => '#10b981',
                    'excerpt' => 'รายงานความโปร่งใสและเปิดเผยผลการปฏิบัติงานด้านงบประมาณตามหลักธรรมาภิบาลภาครัฐ'
                ]
            ],
            'tourism' => [
                [
                    'id' => 6,
                    'title' => 'ยลเสน่ห์ทะเลน้อย ดินแดนพื้นที่ชุ่มน้ำระดับโลก ชมนกน้ำและสายบัวสะพรั่ง',
                    'category_label' => 'การท่องเที่ยวพัทลุง',
                    'date' => '02/08/2026',
                    'views' => 1250,
                    'badge_color' => '#6366f1',
                    'excerpt' => 'สุดสัปดาห์นี้ขอเชิญเที่ยวชมวิถีชีวิตความอุดมสมบูรณ์ของระบบนิเวศทะเลน้อย พร้อมจุดถ่ายภาพและกาแฟริมหยาดน้ำทิพย์'
                ],
                [
                    'id' => 7,
                    'title' => 'เที่ยวถ้ำน้ำเย็น และเขาอกทะลุ สัญลักษณ์โดดเด่นแห่งนครพัทลุง',
                    'category_label' => 'เส้นทางสายธรรมชาติ',
                    'date' => '30/07/2026',
                    'views' => 980,
                    'badge_color' => '#6366f1',
                    'excerpt' => 'แนะนำ 5 จุดถ่ายภาพแสงอาทิตย์ยามรุ่งโรจน์บนความสูงเหนือระดับน้ำทะเลที่ผู้มารือนมิควรพลาด'
                ]
            ]
        ];

        $results = $allNews[$category] ?? $allNews['general'];

        return $this->respond([
            'status' => 200,
            'category' => $category,
            'data' => $results,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * บันทึกข้อมูลคำร้องบริการประชาชน (แบบ Async / No-Reload Form POST)
     */
    public function submitRequest()
    {
        $fullName = $this->request->getPost('full_name');
        $idCard = $this->request->getPost('id_card_last4');
        $serviceType = $this->request->getPost('service_type');
        $description = $this->request->getPost('description');

        if (empty($fullName) || empty($description)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'กรุณากรอกชื่อ-นามสกุล และรายละเอียดคำร้องให้ครบถ้วนก่อนส่ง'
            ], 400);
        }

        // สร้างรหัสอ้างอิงอัตโนมัติ (REQ-XXXXX)
        $trackingCode = 'PHAT-' . rand(10000, 99999);

        return $this->respond([
            'status' => 'success',
            'tracking_code' => $trackingCode,
            'message' => 'ระบบทำการรับเรื่องเรียบร้อย! รหัสติดตามของคุณคือ ' . $trackingCode . ' เจ้าหน้าที่ที่เกี่ยวข้องจะเร่งดำเนินการภายใน 24 ชม.'
        ], 200);
    }
}

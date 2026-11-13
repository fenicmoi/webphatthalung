<?php

namespace App\Controllers;

use App\Models\CitizenContactModel;
use App\Models\DamrongthamStatsModel;
use App\Models\DamrongthamDailyModel;
use App\Libraries\LineNotifyService;
use CodeIgniter\HTTP\ResponseInterface;

class Damrongtham extends BaseController
{
    /**
     * Dedicated Damrongtham Center Landing & E-Service Portal
     */
    public function index()
    {
        $contactModel = new CitizenContactModel();

        // Get official Damrongtham statistics data
        $drtStats = DamrongthamStatsModel::getStats();

        // Base statistics for Damrongtham Center Phatthalung
        $totalCases = (int)($drtStats['cumulative']['total_received'] ?? 0);
        $resolvedCases = (int)($drtStats['cumulative']['total_resolved'] ?? 0);
        $resolutionRate = $drtStats['cumulative']['total_resolved_percent'] ?? 0;

        $categories = [
            'damrongtham_officer' => [
                'name'     => 'ร้องเรียนเจ้าหน้าที่รัฐ / การทุจริตประพฤติมิชอบ',
                'desc'     => 'พฤติกรรมเจ้าหน้าที่ของรัฐ ละเว้นการปฏิบัติหน้าที่ ทุจริต หรือเรียกรับผลประโยชน์',
                'icon'     => 'fa-shield-halved',
                'color'    => '#7c3aed',
                'badge'    => 'ความลับสูงสุด',
                'image'    => 'assets/images/damrongtham/cat_officer.jpg',
                'gradient' => 'linear-gradient(135deg, rgba(30, 27, 75, 0.92) 0%, rgba(15, 23, 42, 0.82) 55%, rgba(124, 58, 237, 0.50) 100%)'
            ],
            'damrongtham_nuisance' => [
                'name'     => 'ความเดือดร้อนรำคาญ / มลพิษสิ่งแวดล้อม',
                'desc'     => 'กลิ่น เสียง น้ำเสีย ฝุ่นละออง สถานประกอบการรบกวน หรือสิ่งกีดขวางทางสาธารณะ',
                'icon'     => 'fa-triangle-exclamation',
                'color'    => '#ea580c',
                'badge'    => 'รับเรื่อง 24 ชม.',
                'image'    => 'assets/images/damrongtham/cat_environment.jpg',
                'gradient' => 'linear-gradient(135deg, rgba(20, 83, 45, 0.92) 0%, rgba(15, 23, 42, 0.82) 55%, rgba(234, 88, 12, 0.50) 100%)'
            ],
            'damrongtham_debt_land' => [
                'name'     => 'ปัญหาหนี้สินนอกระบบ / ข้อพิพาทเรื่องที่ดิน',
                'desc'     => 'การทวงหนี้ไม่เป็นธรรม ดอกเบี้ยเกินอัตรา การบุกรุกหรือรังวัดแนวเขตที่ดิน',
                'icon'     => 'fa-scale-balanced',
                'color'    => '#dc2626',
                'badge'    => 'เร่งด่วน',
                'image'    => 'assets/images/damrongtham/cat_debt_land.jpg',
                'gradient' => 'linear-gradient(135deg, rgba(69, 10, 10, 0.92) 0%, rgba(15, 23, 42, 0.82) 55%, rgba(220, 38, 38, 0.50) 100%)'
            ],
            'damrongtham_welfare' => [
                'name'     => 'ขอความช่วยเหลือสงเคราะห์ / สาธารณภัย',
                'desc'     => 'ผู้ยากไร้ ผู้ด้อยโอกาส การศึกษา หรือผู้ประสบภัยธรรมชาติในพื้นที่จังหวัดพัทลุง',
                'icon'     => 'fa-hand-holding-heart',
                'color'    => '#0284c7',
                'badge'    => 'สังคมสงเคราะห์',
                'image'    => 'assets/images/damrongtham/cat_welfare.jpg',
                'gradient' => 'linear-gradient(135deg, rgba(12, 74, 110, 0.92) 0%, rgba(15, 23, 42, 0.82) 55%, rgba(2, 132, 199, 0.50) 100%)'
            ],
            'damrongtham_legal' => [
                'name'     => 'ขอคำปรึกษาทางกฎหมาย / บริการไกล่เกลี่ยข้อพิพาท',
                'desc'     => 'ขอคำปรึกษาปัญหาข้อกฎหมายทั่วไป หรือขอรับบริการไกล่เกลี่ยประนอมข้อพิพาท',
                'icon'     => 'fa-comments',
                'color'    => '#059669',
                'badge'    => 'บริการฟรี',
                'image'    => 'assets/images/damrongtham/cat_legal.jpg',
                'gradient' => 'linear-gradient(135deg, rgba(6, 78, 59, 0.92) 0%, rgba(15, 23, 42, 0.82) 55%, rgba(5, 150, 105, 0.50) 100%)'
            ]
        ];

        // 11 Districts Network of Phatthalung Province
        $districts = [
            [
                'name'     => 'ศูนย์ดำรงธรรมจังหวัดพัทลุง (ศาลากลางจังหวัด)',
                'district' => 'เมืองพัทลุง',
                'location' => 'ชั้น 1 ศาลากลางจังหวัดพัทลุง (หลังเก่า) ถ.ราเมศวร์',
                'phone'    => '074-614130',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น. (สายด่วน 1567 ตลอด 24 ชม.)'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอเมืองพัทลุง',
                'district' => 'เมืองพัทลุง',
                'location' => 'ที่ว่าการอำเภอเมืองพัทลุง ถ.ราเมศวร์ ต.คูหาสวรรค์',
                'phone'    => '074-613007',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอควนขนุน',
                'district' => 'ควนขนุน',
                'location' => 'ที่ว่าการอำเภอควนขนุน หมู่ที่ 9 ต.ควนขนุน',
                'phone'    => '074-681023',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอเขาชัยสน',
                'district' => 'เขาชัยสน',
                'location' => 'ที่ว่าการอำเภอเขาชัยสน หมู่ที่ 5 ต.เขาชัยสน',
                'phone'    => '074-691060',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอกงหรา',
                'district' => 'กงหรา',
                'location' => 'ที่ว่าการอำเภอกงหรา หมู่ที่ 3 ต.คลองเฉลิม',
                'phone'    => '074-671288',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอตะโหมด',
                'district' => 'ตะโหมด',
                'location' => 'ที่ว่าการอำเภอตะโหมด หมู่ที่ 1 ต.แม่ขรี',
                'phone'    => '074-695026',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอบางแก้ว',
                'district' => 'บางแก้ว',
                'location' => 'ที่ว่าการอำเภอบางแก้ว หมู่ที่ 1 ต.ท่ามะเดื่อ',
                'phone'    => '074-697010',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอปากพะยูน',
                'district' => 'ปากพะยูน',
                'location' => 'ที่ว่าการอำเภอปากพะยูน หมู่ที่ 11 ต.ปากพะยูน',
                'phone'    => '074-699042',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอป่าบอน',
                'district' => 'ป่าบอน',
                'location' => 'ที่ว่าการอำเภอป่าบอน หมู่ที่ 7 ต.ป่าบอน',
                'phone'    => '074-651036',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอป่าพะยอม',
                'district' => 'ป่าพะยอม',
                'location' => 'ที่ว่าการอำเภอป่าพะยอม หมู่ที่ 1 ต.ป่าพะยอม',
                'phone'    => '074-624009',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอศรีนครินทร์',
                'district' => 'ศรีนครินทร์',
                'location' => 'ที่ว่าการอำเภอศรีนครินทร์ หมู่ที่ 1 ต.ชุมพล',
                'phone'    => '074-605553',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ],
            [
                'name'     => 'ศูนย์ดำรงธรรมอำเภอศรีบรรพต',
                'district' => 'ศรีบรรพต',
                'location' => 'ที่ว่าการอำเภอศรีบรรพต หมู่ที่ 1 ต.เขาย่า',
                'phone'    => '074-689033',
                'hotline'  => '1567',
                'hours'    => 'วันจันทร์ - ศุกร์ 08.30 - 16.30 น.'
            ]
        ];

        // FAQ Items
        $faqs = [
            [
                'q' => 'ศูนย์ดำรงธรรมจังหวัดพัทลุง ให้บริการด้านใดบ้าง?',
                'a' => 'ศูนย์ดำรงธรรมทำหน้าที่รับเรื่องร้องเรียนร้องทุกข์ของประชาชน, ให้บริการข้อมูลข่าวสารและคำปรึกษาทางกฎหมาย, ไกล่เกลี่ยประนอมข้อพิพาท, ประสานส่งต่อความช่วยเหลือ และเป็นศูนย์บริการเบ็ดเสร็จ (One Stop Service) เพื่อบำบัดทุกข์ บำรุงสุขแก่ประชาชนชาวจังหวัดพัทลุง'
            ],
            [
                'q' => 'เรื่องประเภทใดที่ศูนย์ดำรงธรรม "ไม่รับพิจารณา"?',
                'a' => '1) เรื่องที่อยู่ระหว่างการพิจารณาคดีของศาล หรือศาลมีคำพิพากษา/คำสั่งถึงที่สุดแล้ว<br>2) เรื่องที่องค์กรตามรัฐธรรมนูญหรือหน่วยงานที่มีอำนาจเฉพาะได้รับไว้พิจารณาแล้ว<br>3) เรื่องที่เป็นบัตรสนเท่ห์ที่ไม่มีพยานหลักฐานหรือเบาะแสเพียงพอ<br>4) เรื่องที่มีการยุติเรื่องไปแล้วและไม่มีข้อเท็จจริงใหม่'
            ],
            [
                'q' => 'หากต้องการขอปกปิดชื่อและข้อมูลผู้ร้องเรียน ทำได้หรือไม่?',
                'a' => 'ทำได้แน่นอนครับ โดยประชาชนสามารถเลือกเปิดสวิตช์ "ขอปกปิดข้อมูลผู้ร้องเรียนเป็นความลับ" ในฟอร์มยื่นเรื่องออนไลน์ ระบบจะคุ้มครองข้อมูลส่วนบุคคลตาม พ.ร.บ. ข้อมูลข่าวสารของราชการ พ.ศ. 2540 และระเบียบว่าด้วยการรักษาความลับของทางราชการอย่างเคร่งครัด'
            ],
            [
                'q' => 'ระยะเวลาในการดำเนินการแก้ไขเรื่องร้องเรียนนานเท่าใด?',
                'a' => 'เมื่อรับเรื่องเข้าสู่ระบบ เจ้าหน้าที่จะตรวจสอบข้อเท็จจริงเบื้องต้นและประสานหน่วยงานที่รับผิดชอบภายใน 1-3 วันทำการ และกำหนดกรอบเวลาการแก้ไขปัญหา/รายงานผลความคืบหน้าภายใน 15-30 วัน ขึ้นอยู่กับความซับซ้อนของแต่ละกรณี'
            ],
            [
                'q' => 'สามารถติดตามความคืบหน้าของเรื่องที่ร้องเรียนได้อย่างไร?',
                'a' => 'หลังจากยื่นเรื่อง ท่านจะได้รับรหัสติดตามเรื่อง (Tracking Code เช่น DRT-261019-XXXX) นำรหัสนี้มากรอกที่กล่อง "ตรวจสอบสถานะเรื่องร้องเรียน" ในหน้าเว็บไซต์นี้ได้ตลอด 24 ชั่วโมง หรือโทรสอบถามสายด่วน 1567 ได้ทุกวัน'
            ]
        ];

        // Fetch latest PR news and activities for Damrongtham Center
        $allNews = function_exists('get_site_news') ? get_site_news(12, 'ศูนย์ดำรงธรรมจังหวัด', true, false, true) : [];
        $newsList = array_slice($allNews, 0, 6);

        $data = [
            'title'          => 'ศูนย์ดำรงธรรมจังหวัดพัทลุง | Damrongtham Center of Phatthalung',
            'metaDesc'       => 'ศูนย์ดำรงธรรมจังหวัดพัทลุง บริการรับเรื่องร้องเรียน ร้องทุกข์ ไกล่เกลี่ยข้อพิพาท ให้คำปรึกษากฎหมาย และสายด่วน 1567 ตลอด 24 ชั่วโมง ศาลากลางจังหวัดพัทลุง',
            'categories'     => $categories,
            'districts'      => $districts,
            'districtNames'  => CitizenContactModel::getDistricts(),
            'drtStats'       => $drtStats,
            'fiscalYears'    => DamrongthamDailyModel::getFiscalYearStats(),
            'newsList'       => $newsList,
            'stats'          => [
                'total'      => number_format($totalCases),
                'resolved'   => number_format($resolvedCases),
                'rate'       => $resolutionRate,
                'districts'  => count($districts) - 1,
            ],
            'faqs'           => $faqs,
            'siteConfig'     => function_exists('get_site_settings') ? get_site_settings() : [],
        ];

        return view('damrongtham/index', $data);
    }

    /**
     * Web Presentation Deck for Executive / Official Briefings
     */
    public function presentation()
    {
        $drtStats = DamrongthamStatsModel::getStats();
        $fiscalYears = DamrongthamDailyModel::getFiscalYearStats();

        $data = [
            'title'       => 'การนำเสนอรายงานผลการดำเนินงาน ศูนย์ดำรงธรรมจังหวัดพัทลุง',
            'drtStats'    => $drtStats,
            'fiscalYears' => $fiscalYears,
            'updatedAt'   => $drtStats['updated_at'] ?? date('d/m/Y'),
        ];

        return view('damrongtham/presentation', $data);
    }

    /**
     * Statistics JSON API for Dashboard
     */
    public function statsApi()
    {
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => DamrongthamStatsModel::getStats()
        ]);
    }

    /**
     * Submit Damrongtham Complaint (AJAX API) - Temporarily Closed
     */
    public function submit()
    {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => '⚠️ ระบบยื่นเรื่องร้องเรียนผ่านเว็บไซต์ปิดให้บริการชั่วคราวเพื่อปรับปรุงระบบ หากท่านมีความประสงค์ยื่นเรื่องร้องเรียนหรือขอความช่วยเหลือ กรุณาติดต่อสายด่วนศูนย์ดำรงธรรม 1567 (โทรฟรีตลอด 24 ชั่วโมง) หรือติดต่อด้วยตนเอง ณ ศูนย์ดำรงธรรมจังหวัดพัทลุง ศาลากลางจังหวัดพัทลุง'
        ]);

        if (!$this->request->isAJAX() && !$this->request->is('post')) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'รูปแบบการร้องขอไม่ถูกต้อง (Invalid Request Method)'
            ]);
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'phone'     => 'required|min_length[8]|max_length[50]',
            'subject'   => 'required|min_length[4]|max_length[255]',
            'message'   => 'required|min_length[10]',
            'district'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => implode('<br>', $this->validator->getErrors())
            ]);
        }

        // File attachment handling
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

        // Generate Damrongtham tracking code (DRT-YYMMDD-XXXX)
        $trackingCode = 'DRT-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        
        $isConfidential = (bool)$this->request->getPost('is_confidential');
        $rawMessage = trim($this->request->getPost('message'));
        $categoryKey = $this->request->getPost('category') ?? 'damrongtham';
        $locationDetail = trim($this->request->getPost('location_detail') ?? '');

        $formattedMessage = $rawMessage;
        if (!empty($locationDetail)) {
            $formattedMessage .= "\n\n[สถานที่เกิดเหตุ/พิกัด]: " . $locationDetail;
        }
        if ($isConfidential) {
            $formattedMessage = "[* คำร้องขอปกปิดข้อมูลผู้ร้องเรียนเป็นความลับ ตาม พ.ร.บ. ข้อมูลข่าวสารฯ *]\n" . $formattedMessage;
        }

        $contactModel = new CitizenContactModel();

        $insertData = [
            'tracking_code' => $trackingCode,
            'full_name'     => trim($this->request->getPost('full_name')),
            'phone'         => trim($this->request->getPost('phone')),
            'email'         => trim($this->request->getPost('email') ?? ''),
            'district'      => trim($this->request->getPost('district') ?? 'เมืองพัทลุง'),
            'category'      => 'damrongtham',
            'subject'       => '[' . ($isConfidential ? 'ลับ-ศูนย์ดำรงธรรม' : 'ศูนย์ดำรงธรรม') . '] ' . trim($this->request->getPost('subject')),
            'message'       => $formattedMessage,
            'attachment'    => $attachmentName,
            'status'        => 'pending',
            'ip_address'    => $this->request->getIPAddress(),
        ];

        try {
            $contactModel->insert($insertData);

            // Notify Officers via LINE Notify and Saraban
            $insertData['category_name'] = 'ศูนย์ดำรงธรรมจังหวัดพัทลุง 1567 (' . $insertData['district'] . ')';
            LineNotifyService::notifyNewContact($insertData);

            return $this->response->setJSON([
                'status'        => 'success',
                'tracking_code' => $trackingCode,
                'message'       => 'ยื่นเรื่องร้องเรียนต่อศูนย์ดำรงธรรมจังหวัดพัทลุงเรียบร้อยแล้ว',
                'data'          => [
                    'tracking_code' => $trackingCode,
                    'created_at'    => date('d/m/Y H:i น.'),
                    'is_confidential' => $isConfidential
                ]
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Damrongtham::submit] ' . $e->getMessage());
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Real-time Damrongtham Status Tracking API
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
                'message' => 'ไม่พบข้อมูลคำร้องจากรหัส ' . htmlspecialchars($code) . ' กรุณาตรวจสอบรหัสอีกครั้ง หรือติดต่อสายด่วน 1567'
            ]);
        }

        $statuses = CitizenContactModel::getStatuses();

        // Step mapping for visual stepper (1 to 4)
        $stepMap = [
            'pending'     => 1, // รับเรื่องเข้าสู่ระบบ
            'in_progress' => 3, // กำลังดำเนินการ/ประสานหน่วยงาน
            'resolved'    => 4, // ดำเนินการเสร็จสิ้น/แจ้งผล
            'rejected'    => 4, // ยุติเรื่อง
        ];

        $currentStep = $stepMap[$record['status']] ?? 2;

        return $this->response->setJSON([
            'status'        => 'success',
            'tracking_code' => $record['tracking_code'],
            'full_name'     => mb_substr($record['full_name'], 0, 3) . '***',
            'subject'       => $record['subject'],
            'district'      => $record['district'],
            'status_key'    => $record['status'],
            'status_info'   => $statuses[$record['status']] ?? ['name' => $record['status'], 'badge' => 'bg-secondary text-white'],
            'current_step'  => $currentStep,
            'officer_note'  => $record['officer_note'] ?? 'ศูนย์ดำรงธรรมจังหวัดพัทลุงได้รับเรื่องแล้ว อยู่ระหว่างตรวจสอบข้อเท็จจริงและมอบหมายส่วนงานที่รับผิดชอบ',
            'created_at'    => date('d/m/Y H:i น.', strtotime($record['created_at'])),
            'updated_at'    => !empty($record['updated_at']) ? date('d/m/Y H:i น.', strtotime($record['updated_at'])) : '-',
        ]);
    }
}

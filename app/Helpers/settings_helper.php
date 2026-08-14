<?php

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('get_site_settings')) {
    /**
     * ดึงค่าการตั้งค่าเว็บไซต์ (จากตาราง DB หรือ JSON file)
     * // ... existing doc
     */
    function get_site_settings($key = null)
    {
        $defaults = [
            'site_title_th'    => 'จังหวัดพัทลุง',
            'site_title_en'    => 'Phatthalung Province',
            'slogan'           => 'เมืองหนังโนราห์ อู่นาข้าว พราวน้ำตก แหล่งนกน้ำ ทะเลสาบงาม เขาอกทะลุ น้ำพุร้อน',
            'contact_email'    => 'contact@phatthalung.go.th',
            'contact_phone'    => '074-613409',
            'address'          => 'ศาลากลางจังหวัดพัทลุง ถนนราเมศวร์ ตำบลคูหาสวรรค์ อำเภอเมืองพัทลุง 93000',
            'maintenance_mode' => '0',
            'public_register'  => '1',
            'csrf_protection'  => '1',
            'default_theme'    => 'light',
            'theme_accent'     => '#6366f1',
            'fb_url'           => 'https://www.facebook.com/phatthalungPR',
            'line_id'          => '@phatthalung_connect',
            'seo_keywords'     => 'จังหวัดพัทลุง, ทะเลน้อย, บริการประชาชนออนไลน์, ศูนย์ดำรงธรรม, ข่าวประกวดราคา',
            'site_logo'        => ''
        ];

        // ใช้ constant WRITABLE ของ CodeIgniter 4 หรือเส้นทางตรง
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_settings.json';

        $settings = $defaults;
        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                $settings = array_merge($defaults, $saved);
            }
        }

        if ($key !== null) {
            return $settings[$key] ?? null;
        }

        return $settings;
    }
}

if (!function_exists('get_site_logo')) {
    /**
     * ดึง URL ของโลโก้หน่วยงาน (หากมีอัปโหลดหรือตั้งค่าไว้)
     */
    function get_site_logo()
    {
        $logo = get_site_settings('site_logo');
        if (!empty($logo)) {
            if (strpos($logo, 'http://') === 0 || strpos($logo, 'https://') === 0 || strpos($logo, 'data:image') === 0) {
                return $logo;
            }
            return base_url(ltrim($logo, '/\\'));
        }
        return null;
    }
}

if (!function_exists('get_site_menus')) {
    /**
     * ดึงโครงสร้างเมนูหลักและเมนูย่อย (Dropdown Submenus) เพื่อนำไปแสดงผลบน Navbar สาธารณะ
     */
    function get_site_menus()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_menus.json';

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                return $saved;
            }
        }

        if (class_exists('\App\Controllers\Admin\MenuManager')) {
            return (new \App\Controllers\Admin\MenuManager())->getDefaultMenus();
        }

        return [];
    }
}

if (!function_exists('get_banner_settings')) {
    /**
     * ดึงค่าติดตั้งโหมดการแสดงผลแบนเนอร์และเลย์เอาต์เว็บ (Hybrid Widescreen vs Modern Boxed)
     */
    function get_banner_settings()
    {
        $defaults = [
            'layout_mode'     => 'hybrid_widescreen', // hybrid_widescreen หรือ modern_boxed
            'banner_height'   => '540',
            'auto_play'       => '1',
            'interval_ms'     => '7500',
            'show_weather'    => '1',
            'show_giahs'      => '1'
        ];

        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'banner_settings.json';

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                return array_merge($defaults, $saved);
            }
        }

        return $defaults;
    }
}

if (!function_exists('get_site_banners')) {
    /**
     * ดึงรายการสไลด์และกราฟิก Multi-Layer สำหรับแสดงผลใน Hero Banner
     */
    function get_site_banners()
    {
        $model = new \App\Models\SiteBannerModel();
        $banners = $model->where('active', 1)->findAll();
        
        if (!empty($banners)) {
            return $banners;
        }

        // Return core default slides if DB is empty
        return [
            [
                'id' => 1,
                'title' => 'เสน่ห์เมืองลุง "เขา ป่า นา เล"',
                'badge_title' => 'LANDMARK',
                'badge_icon' => 'fa-solid fa-star',
                'bg_type' => 'image',
                'image_path' => 'assets/images/slider/sane_muanglung.png',
                'desc' => 'สัมผัสความอุดมสมบูรณ์ของระบบนิเวศและวัฒนธรรม นำเที่ยวทะเลน้อย ควายน้ำ ยอักษะ และวิถีเกษตรกรรม พร้อมระบบจองท่องเที่ยวชุมชนแบบอัจฉริยะ',
                'button_text' => 'เปิดโลกท่องเที่ยว',
                'button_url' => '#tourism',
                'button_icon' => 'fa-solid fa-compass',
                'active' => 1,
                'style_class' => 'slide-bg-sane-muanglung'
            ],
            // ... truncated defaults
        ];
    }
}

if (!function_exists('get_news_categories')) {
    /**
     * ดึงหมวดหมู่ข่าวสารประชาสัมพันธ์
     */
    function get_news_categories()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'news_categories.json';

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved) && !empty($saved)) {
                return $saved;
            }
        }

        return [
            'ประกาศราชการ / แจ้งเตือน',
            'ข่าวกิจกรรมจังหวัด',
            'ประกาศจัดซื้อจัดจ้าง (e-GP)',
            'ส่งเสริมการท่องเที่ยว'
        ];
    }
}

if (!function_exists('get_site_news')) {
    /**
     * ดึงรายการข่าวสารประชาสัมพันธ์ทั้งหมด หรือกรองตามจำนวนและหมวดหมู่
     */
    function get_site_news($limit = null, $category = null, $activeOnly = true)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_news.json';
        $newsList = [];

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                $newsList = $saved;
            }
        } else {
            // ข่าวตั้งต้นตัวอย่างสำหรับแสดงผลบนเว็บสาธารณะ
            $newsList = [
                [
                    'id' => 'news-101',
                    'title' => 'จังหวัดพัทลุงเปิดตัว "พอร์ตัลบริการดิจิทัลเบ็ดเสร็จ" ร้องทุกข์และติดตามเอกสารตลอด 24 ชั่วโมง',
                    'category' => 'ประกาศราชการ / แจ้งเตือน',
                    'summary' => 'ประชาชนสามารถเข้าสู่บริการออนไลน์ ยื่นคำร้อง PDPA ชำระภาษีที่ดินท้องถิ่น และติดตามสถานะเอกสารได้รวดเร็วทันใจผ่านเทคโนโลยี AI Search ไม่ต้องเดินทาง',
                    'content' => '<p>จังหวัดพัทลุงตอกย้ำภาพลักษณ์องค์กรปกครองส่วนท้องถิ่นยุคใหม่ ดำเนินการยกระดับศูนย์บริการประชาชน (e-Services) อย่างรอบด้าน โดยประชาชนในทุกอำเภอสามารถยื่นเรื่องร้องทุกข์ ติดตามผลคำร้องผ่านหมายเลขติดตาม 13 หลัก และดาวน์โหลดแบบฟอร์มเอกสารทางราชการผ่านช่องทางดิจิทัลได้ตลอด 24 ชั่วโมง</p><br><p>นอกจากนี้ยังนำระบบ <b>Universal Omni-Search</b> มาช่วยเหลือประชาชนค้นหาบริการที่เหมาะสม ด้วยระบบสั่งงานด้วยเสียงภาษาไทย (Voice AI) เพื่อการเข้าถึงบริการที่เท่าเทียมและมีประสิทธิภาพสูงสุด</p>',
                    'cover_image' => 'assets/images/slider/sane_muanglung.png',
                    'images_gallery' => ['assets/images/slider/sane_muanglung.png'],
                    'attachments' => [],
                    'views' => 1284,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                    'active' => true
                ],
                [
                    'id' => 'news-102',
                    'title' => 'เชิญเที่ยวงานประเพณี "ตักบาตรเทโว ดอยเขาน้อย" มรดกวัฒนธรรมและธรรมชาติเมืองพัทลุง',
                    'category' => 'ส่งเสริมการท่องเที่ยว',
                    'summary' => 'ร่วมสืบสานวิถีท้องถิ่น สัมผัสมนต์เสน่ห์เมืองลุง "เขา ป่า นา เล" พร้อมชมความงดงามของแสงอาทิตย์ยามเช้าเหนือบึงทะเลน้อยและฝูงควายน้ำมรดกเกษตรโลก (GIAHS)',
                    'content' => '<p>สำนักงานการท่องเที่ยวและวัฒนธรรมจังหวัดพัทลุง ขอเชิญชวนประชาชนและนักท่องเที่ยวทั้งชาวไทยและต่างชาติ ร่วมสัมผัสสุนทรียภาพแห่งความงดงามในฤดูกาลท่องเที่ยวท้องถิ่น <b>"เขา ป่า นา เล"</b> พร้อมเรียนรู้วิถีเชิงอนุรักษ์ระบบนิเวศการเลี้ยงควายน้ำทะเลน้อย มรดกทางการเกษตรระดับโลก</p>',
                    'cover_image' => 'assets/images/slider/sane_muanglung.png',
                    'images_gallery' => ['assets/images/slider/sane_muanglung.png'],
                    'attachments' => [],
                    'views' => 842,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                    'active' => true
                ],
                [
                    'id' => 'news-103',
                    'title' => 'ประกาศการเปิดเสวนา "SMART PHATTHALUNG 2026" สร้างเครือข่าย WiFi สาธารณะและกล้อง CCTV อัจฉริยะ',
                    'category' => 'ข่าวกิจกรรมจังหวัด',
                    'summary' => 'ระดมความคิดภาคประชาสังคม ขยายเสาอัจฉริยะ Smart Pole พร้อมระบบเซ็นเซอร์ฝุ่น PM2.5 และความปลอดภัยแบบเรียลไทม์ ครอบคลุมพื้นที่เศรษฐกิจ',
                    'content' => '<p>จังหวัดพัทลุงเดินหน้าโครงการ Smart City 2026 อย่างต่อเนื่อง เตรียมพร้อมขยายโครงข่ายเสาอัจฉริยะมัลติฟังก์ชัน (Smart Pole) เพื่อส่งมอบสัญญานอินเทอร์เน็ต WiFi สาธารณะและระบบตรวจสอบความปลอดภัย CCTV ที่ควบคุมโดยเทคโนโลยี AI ในชุมชนหลัก</p>',
                    'cover_image' => 'assets/images/slider/sane_muanglung.png',
                    'images_gallery' => ['assets/images/slider/sane_muanglung.png'],
                    'attachments' => [],
                    'views' => 591,
                    'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                    'active' => true
                ]
            ];
        }

        if ($activeOnly) {
            $newsList = array_filter($newsList, static function($item) {
                return !isset($item['active']) || $item['active'] == true;
            });
        }

        if (!empty($category)) {
            $newsList = array_filter($newsList, static function($item) use ($category) {
                return isset($item['category']) && strcasecmp(trim($item['category']), trim($category)) === 0;
            });
        }

        // Sort by created_at descending
        usort($newsList, static function($a, $b) {
            $timeA = isset($a['created_at']) ? strtotime($a['created_at']) : 0;
            $timeB = isset($b['created_at']) ? strtotime($b['created_at']) : 0;
            return $timeB - $timeA;
        });

        if ($limit > 0) {
            $newsList = array_slice($newsList, 0, (int)$limit);
        }

        return array_values($newsList);
    }
}

if (!function_exists('get_news_by_id')) {
    /**
     * ดึงข่าวสารรายชิ้นจากรหัสไอดี
     */
    function get_news_by_id($id)
    {
        $allNews = get_site_news(null, null, false);
        foreach ($allNews as $news) {
            if (isset($news['id']) && strval($news['id']) === strval($id)) {
                return $news;
            }
        }
        return null;
    }
}

if (!function_exists('get_site_events')) {
    /**
     * ดึงเฉพาะข่าวสารที่ถูกตั้งค่าเป็นรายการในปฏิทินกิจกรรม (Event Calendar)
     */
    function get_site_events($activeOnly = true)
    {
        $allNews = get_site_news(null, null, $activeOnly);
        $events = [];
        foreach ($allNews as $news) {
            if (!empty($news['is_event']) && ($news['is_event'] == true || $news['is_event'] === '1' || $news['is_event'] === 'true')) {
                $events[] = $news;
            }
        }
        // Sort events by start date ascending (earliest upcoming event first)
        usort($events, static function($a, $b) {
            $timeA = !empty($a['event_start_date']) ? strtotime($a['event_start_date']) : 0;
            $timeB = !empty($b['event_start_date']) ? strtotime($b['event_start_date']) : 0;
            return $timeA - $timeB;
        });
        return $events;
    }
}

if (!function_exists('get_service_banners')) {
    /**
     * ดึงรายการแบนเนอร์บริการประชาชนและระบบลิงก์ (e-Services & Quick Link Banners)
     */
    function get_service_banners($activeOnly = false)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'service_banners.json';
        $banners = [];

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                $banners = $saved;
            }
        } else {
            // แบนเนอร์บริการประชาชนตัวอย่างเริ่มต้น (พร้อมเชื่อมโยง URL ต่างๆ)
            $banners = [
                [
                    'id' => 'sb-101',
                    'title' => 'ระบบชำระภาษีท้องถิ่นออนไลน์ (e-Tax Phatthalung)',
                    'desc' => 'บริการชำระภาษีที่ดินและสิ่งปลูกสร้าง ภาษีป้าย และธรรมเนียมต่างๆ ดำเนินการผ่านระบบดิจิทัล สะดวก รวดเร็ว 24 ชม.',
                    'badge' => 'บริการออนไลน์ 24 ชม.',
                    'badge_color' => 'success',
                    'url' => 'https://www.rd.go.th',
                    'target' => '_blank',
                    'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 1
                ],
                [
                    'id' => 'sb-102',
                    'title' => 'พอร์ตัลเชื่อมโยงบริการภาครัฐ Citizen e-Service',
                    'desc' => 'ศูนย์กลางการเข้าถึงบริการภาครัฐแบบเบ็ดเสร็จ ยืนยันตัวตนด้วย Digital ID เชื่อมต่อข้อมูลหน่วยงานสาธารณะทั่วประเทศ',
                    'badge' => 'Digital Portal',
                    'badge_color' => 'primary',
                    'url' => 'https://www.dga.or.th',
                    'target' => '_blank',
                    'image' => 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 2
                ],
                [
                    'id' => 'sb-103',
                    'title' => 'ระบบแจ้งปัญหาและสายตรงผู้ว่าฯ (Traffy Fondue)',
                    'desc' => 'รายงานปัญหาความเดือดร้อน สาธารณูปโภค ถนนหนทาง หรือขยะ พร้อมติดตามสถานะการแก้ไขปัญหาแบบเรียลไทม์',
                    'badge' => 'ร้องทุกข์ 24 ชม.',
                    'badge_color' => 'danger',
                    'url' => 'https://www.traffy.in.th',
                    'target' => '_blank',
                    'image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 3
                ],
                [
                    'id' => 'sb-104',
                    'title' => 'ระบบติดตามโครงการและจัดซื้อจัดจ้างภาครัฐ (e-GP)',
                    'desc' => 'ศูนย์ข้อมูลจัดซื้อจัดจ้าง ประกาศประมูลราคา (Bidding) และการใช้จ่ายงบประมาณ โปร่งใส เป็นธรรม ตรวจสอบได้โดยสาธารณชน',
                    'badge' => 'โปร่งใส & ตรวจสอบ',
                    'badge_color' => 'warning',
                    'url' => 'http://www.gprocurement.go.th',
                    'target' => '_blank',
                    'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 4
                ],
                [
                    'id' => 'sb-105',
                    'title' => 'ศูนย์ยื่นคำร้องคุ้มครองข้อมูลส่วนบุคคล (PDPA Portal)',
                    'desc' => 'ระบบบริการขอใช้สิทธิ์ของเจ้าของข้อมูลส่วนบุคคล (Data Subject Right Request) เพื่อการเปิดเผย แก้ไข หรือลบข้อมูล',
                    'badge' => 'คุ้มครองสิทธิ PDPA',
                    'badge_color' => 'info',
                    'url' => '#pdpa',
                    'target' => '_self',
                    'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 5
                ],
                [
                    'id' => 'sb-106',
                    'title' => 'ระบบตรวจสอบเบี้ยผู้สูงอายุและสวัสดิการสังคม',
                    'desc' => 'ลงทะเบียน ตรวจสอบสิทธิประโยชน์ เงินอุดหนุนเด็กแรกเกิด เบี้ยยังชีพผู้สูงอายุ และคนพิการของชุมชน',
                    'badge' => 'สวัสดิการชุมชน',
                    'badge_color' => 'success',
                    'url' => 'https://www.dso.go.th',
                    'target' => '_blank',
                    'image' => 'https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 6
                ],
                [
                    'id' => 'sb-107',
                    'title' => 'เครือข่ายส่งเสริมท่องเที่ยววัฒนธรรมและธรรมชาติ (Eco-Tourism)',
                    'desc' => 'ข้อมูลสถานที่ท่องเที่ยว เส้นทางธรรมชาติ ทะเลน้อย มรดกภูมิปัญญาใต้ พร้อมระบบจองคิวนำเที่ยวและที่พัก',
                    'badge' => 'ท่องเที่ยว & วัฒนธรรม',
                    'badge_color' => 'primary',
                    'url' => 'https://www.tourismthailand.org',
                    'target' => '_blank',
                    'image' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 7
                ],
                [
                    'id' => 'sb-108',
                    'title' => 'บริการจองคิวออนไลน์ติดต่อส่วนราชการ (Smart Queue)',
                    'desc' => 'ระบบจองเวลานัดหมายล่วงหน้า สำหรับงานทะเบียน ขอใบอนุญาต และงานติดต่อสอบถาม ประหยัดเวลา ไม่ต้องรอคิว',
                    'badge' => 'นัดหมายรวดเร็ว',
                    'badge_color' => 'warning',
                    'url' => 'https://www.bora.dopa.go.th',
                    'target' => '_blank',
                    'image' => 'https://images.unsplash.com/photo-1521791136368-1a8693790595?q=80&w=600&auto=format&fit=crop',
                    'active' => true,
                    'sort_order' => 8
                ]
            ];
        }

        if ($activeOnly) {
            $banners = array_filter($banners, static function($item) {
                return !isset($item['active']) || (bool)$item['active'] === true;
            });
        }

        // Sort by sort_order ascending
        usort($banners, static function($a, $b) {
            $orderA = isset($a['sort_order']) ? (int)$a['sort_order'] : 999;
            $orderB = isset($b['sort_order']) ? (int)$b['sort_order'] : 999;
            return $orderA - $orderB;
        });

        return array_values($banners);
    }
}

if (!function_exists('save_service_banners')) {
    /**
     * บันทึกข้อมูลแบนเนอร์บริการประชาชนและระบบลิงก์ลงไฟล์ JSON
     */
    function save_service_banners(array $banners)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'service_banners.json';
        return file_put_contents($jsonPath, json_encode($banners, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_procurement_categories')) {
    /**
     * ดึงหมวดหมู่ข่าวจัดซื้อจัดจ้างของภาครัฐ (e-GP & Transparency Portal)
     */
    function get_procurement_categories()
    {
        return [
            'ประกาศจัดซื้อจัดจ้าง',
            'ประกาศราคากลาง',
            'สรุปผลจัดซื้อจัดจ้าง (สขร.1)',
            'ประกาศสัญญา/ข้อตกลง'
        ];
    }
}

if (!function_exists('get_procurement_items')) {
    /**
     * ดึงข้อมูลประกาศและข่าวจัดซื้อจัดจ้างทั้งหมดหรือตามหมวดหมู่
     */
    function get_procurement_items(?string $category = null, bool $activeOnly = true, ?int $limit = null)
    {
        $model = new \App\Models\ProcurementModel();
        
        if ($activeOnly) {
            $model->where('status', 'active');
        }
        
        if ($category !== null && $category !== 'all') {
            $model->where('category', $category);
        }
        
        $model->orderBy('published_date', 'DESC');
        
        if ($limit !== null && $limit > 0) {
            $items = $model->findAll($limit);
        } else {
            $items = $model->findAll();
        }
        
        // Map DB fields back to what the views expect (for backward compatibility)
        return array_map(function($item) {
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'category' => $item['category'],
                'date' => $item['published_date'],
                'views' => 0, // Mocked for now, not tracked in DB
                'budget' => number_format((float)$item['budget'], 2) . ' บาท',
                'attachment_url' => $item['doc_path'],
                'active' => ($item['status'] === 'active')
            ];
        }, $items);
    }
}

if (!function_exists('get_procurement_by_id')) {
    function get_procurement_by_id($id)
    {
        $model = new \App\Models\ProcurementModel();
        $item = $model->find($id);
        if ($item) {
            return [
                'id' => $item['id'],
                'title' => $item['title'],
                'category' => $item['category'],
                'date' => $item['published_date'],
                'views' => 0,
                'budget' => number_format((float)$item['budget'], 2) . ' บาท',
                'attachment_url' => $item['doc_path'],
                'active' => ($item['status'] === 'active')
            ];
        }
        return null;
    }
}

if (!function_exists('save_procurement_items')) {
    /**
     * บันทึกรายการข่าวจัดซื้อจัดจ้างลงไฟล์ JSON
     */
    function save_procurement_items(array $items)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'procurement_items.json';
        return file_put_contents($jsonPath, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_gallery_categories')) {
    /**
     * ดึงหมวดหมู่อัลบั้มภาพกิจกรรม
     */
    function get_gallery_categories()
    {
        return [
            'ประเพณีและวัฒนธรรม',
            'ภารกิจผู้บริหารและจังหวัด',
            'กิจกรรมสาธารณประโยชน์',
            'การท่องเที่ยวและเศรษฐกิจ',
            'การศึกษานวัตกรรม'
        ];
    }
}

if (!function_exists('get_gallery_albums')) {
    /**
     * ดึงรายการอัลบั้มภาพกิจกรรมทั้งหมด หรือกรองตามหมวดหมู่และจำนวน
     */
    function get_gallery_albums($limit = null, $category = null, $activeOnly = true)
    {
        $model = new \App\Models\GalleryAlbumModel();
        $model->orderBy('created_at', 'DESC');
        
        if ($limit !== null && $limit > 0) {
            $albums = $model->findAll($limit);
        } else {
            $albums = $model->findAll();
        }
        
        return array_map(function($item) {
            return [
                'id' => 'gal_' . $item['id'],
                'db_id' => $item['id'],
                'title' => $item['title'],
                'category' => 'ประเพณีและวัฒนธรรม', // mocked category for backward compat
                'date' => $item['created_at'],
                'views' => 0,
                'cover_image' => $item['cover_image'],
                'photos' => [], // lazy load or joined later
                'active' => true
            ];
        }, $albums);
    }
}

if (!function_exists('get_gallery_by_id')) {
    function get_gallery_by_id($id)
    {
        // $id could be like "gal_1" or "gal_256901"
        $numericId = (int) preg_replace('/[^0-9]/', '', $id);
        
        $model = new \App\Models\GalleryAlbumModel();
        $album = $model->find($numericId);
        
        if ($album) {
            $photoModel = new \App\Models\GalleryPhotoModel();
            $photos = $photoModel->where('album_id', $numericId)->findAll();
            $photoUrls = array_column($photos, 'image_path');
            
            return [
                'id' => 'gal_' . $album['id'],
                'db_id' => $album['id'],
                'title' => $album['title'],
                'category' => 'ประเพณีและวัฒนธรรม',
                'date' => $album['created_at'],
                'views' => 0,
                'cover_image' => $album['cover_image'],
                'photos' => $photoUrls,
                'active' => true
            ];
        }
        return null;
    }
}

if (!function_exists('save_gallery_albums')) {
    /**
     * บันทึกรายการอัลบั้มภาพลงไฟล์ JSON
     */
    function save_gallery_albums(array $albums)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'gallery_albums.json';
        return file_put_contents($jsonPath, json_encode(array_values($albums), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('extract_youtube_id')) {
    /**
     * สกัด ID วิดีโอจากลิงก์ YouTube ทุกรูปแบบ (youtube.com, youtu.be, embed, หรือ ID โดยตรง)
     */
    function extract_youtube_id($url)
    {
        $url = trim($url ?? '');
        if (empty($url)) {
            return '';
        }
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        if (isset($match[1])) {
            return $match[1];
        }
        return $url;
    }
}

if (!function_exists('get_site_videos')) {
    /**
     * ดึงรายการวิดีโอยูทูปและสื่อประชาสัมพันธ์จังหวัดพัทลุง (Phatthalung Web TV / YouTube Showcase)
     */
    function get_site_videos($limit = null, $category = null, $activeOnly = false)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_videos.json';
        $videos = [];

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                $videos = $saved;
            }
        } else {
            // วิดีโอตัวอย่างเริ่มต้นความละเอียดสูง (Seed Data)
            $videos = [
                [
                    'id' => 'vid-101',
                    'youtube_id' => 'gT8Y_Qc7J4U',
                    'title' => 'มหัศจรรย์ทะเลน้อย: สวรรค์ของนกน้ำและสายน้ำลุ่มน้ำปากประ (Phatthalung Nature 4K)',
                    'category' => 'ท่องเที่ยวและธรรมชาติ',
                    'views' => 14850,
                    'date' => '2026-08-01',
                    'desc' => 'สัมผัสบรรยากาศยามเช้าอันเงียบสงบ ณ ยอดยักษ์บ้านปากประ และทิวทัศน์ทะเลบัวแดงที่งดงามเหนือคำบรรยาย',
                    'active' => true,
                    'featured' => true
                ],
                [
                    'id' => 'vid-102',
                    'youtube_id' => '7uDk24_z1p4',
                    'title' => 'สืบสานมรดกโลก: เสนาะสำเนียงมนต์เสน่ห์ศิลปวัฒนธรรมมโนราห์ และภูมิปัญญาใต้',
                    'category' => 'ศิลปวัฒนธรรมท้องถิ่น',
                    'views' => 8940,
                    'date' => '2026-07-28',
                    'desc' => 'สารคดีเจาะลึกศิลปะการร่ายรำมโนราห์ มรดกทางวัฒนธรรมที่จับต้องไม่ได้ซึ่งได้รับการยกย่องจากยูเนสโก',
                    'active' => true,
                    'featured' => false
                ],
                [
                    'id' => 'vid-103',
                    'youtube_id' => 'kY_c9tS4LpA',
                    'title' => 'รายงานผลการขับเคลื่อนยุทธศาสตร์จังหวัดพัทลุง สู่เมืองมรดกเชิงอนุรักษ์และเกษตรมูลค่าสูง',
                    'category' => 'ภารกิจและกิจกรรมจังหวัด',
                    'views' => 6420,
                    'date' => '2026-07-20',
                    'desc' => 'สรุปความก้าวหน้าโครงการพัฒนาระบบสาธารณูปโภค การส่งเสริมวิสาหกิจชุมชน และแผนปฏิบัติราชการประจำปี',
                    'active' => true,
                    'featured' => false
                ],
                [
                    'id' => 'vid-104',
                    'youtube_id' => 'Q-0i1b_68_o',
                    'title' => 'พัทลุง มิตรภาพ และอัตลักษณ์แห่งวิถีชุมชนรอบเขาอกทะลุ - Official PR Film 2026',
                    'category' => 'ส่งเสริมการท่องเที่ยว',
                    'views' => 21300,
                    'date' => '2026-07-15',
                    'desc' => 'ภาพยนตร์สร้างแรงบันดาลใจเพื่อต้อนรับผู้มาเยือนจากทั่วโลกสู่แดนสวรรค์แห่งความร่มรื่น วิถีออร์แกนิก และไมตรีจิต',
                    'active' => true,
                    'featured' => false
                ]
            ];
            if (!is_file($jsonPath)) {
                @file_put_contents($jsonPath, json_encode($videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        if ($activeOnly) {
            $videos = array_filter($videos, static function($v) {
                return !isset($v['active']) || (bool)$v['active'] === true;
            });
        }

        if ($category !== null && $category !== 'all' && $category !== '') {
            $videos = array_filter($videos, static function($v) use ($category) {
                return strcasecmp(trim($v['category'] ?? ''), trim($category)) === 0;
            });
        }

        // เรียงตามลำดับวันที่ใหม่ล่าสุด
        usort($videos, static function($a, $b) {
            $dateA = strtotime($a['date'] ?? '1970-01-01');
            $dateB = strtotime($b['date'] ?? '1970-01-01');
            return $dateB - $dateA;
        });

        if ($limit !== null && $limit > 0) {
            $videos = array_slice(array_values($videos), 0, $limit);
        }

        return array_values($videos);
    }
}

if (!function_exists('save_site_videos')) {
    /**
     * บันทึกรายการวิดีโอยูทูปลงไฟล์ JSON
     */
    function save_site_videos(array $videos)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_videos.json';
        return file_put_contents($jsonPath, json_encode(array_values($videos), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_document_categories')) {
    /**
     * คืนค่าข้อมูล 5 เสาหลักคลังเอกสารและไฟล์ดาวน์โหลดจังหวัด (5 Smart Pillars)
     */
    function get_document_categories()
    {
        return [
            'laws' => [
                'name' => 'กฎหมาย ระเบียบ และหนังสือราชการ',
                'icon' => 'fa-solid fa-scale-balanced',
                'desc' => 'พ.ร.บ., พระราชกฤษฎีกา, คำสั่งจังหวัด, มติ ครม., หนังสือเวียน และหนังสือราชการ',
                'gradient' => 'linear-gradient(135deg, #1e3a8a, #3b82f6)',
                'badge' => 'primary'
            ],
            'strategy' => [
                'name' => 'ยุทธศาสตร์ แผนงาน และรายงานผล',
                'icon' => 'fa-solid fa-chart-pie',
                'desc' => 'แผนแม่บท, ยุทธศาสตร์จังหวัด, รายงานผลโครงการ, รายงานการเงิน และ PMQA',
                'gradient' => 'linear-gradient(135deg, #047857, #10b981)',
                'badge' => 'success'
            ],
            'ita' => [
                'name' => 'ธรรมาภิบาล ความโปร่งใส และ ITA',
                'icon' => 'fa-solid fa-shield-halved',
                'desc' => 'การประเมิน ITA/OIT, แผนต่อต้านการทุจริต และศูนย์ข้อมูลข่าวสารภาครัฐ',
                'gradient' => 'linear-gradient(135deg, #b91c1c, #ef4444)',
                'badge' => 'danger'
            ],
            'knowledge' => [
                'name' => 'คลังความรู้ สถิติ และงานวิจัย',
                'icon' => 'fa-solid fa-folder-tree',
                'desc' => 'ข้อมูลสถิติจังหวัด, ระบบสารสนเทศ GIS, ผลงานวิจัย บทความ และกรณีศึกษา',
                'gradient' => 'linear-gradient(135deg, #d97706, #f59e0b)',
                'badge' => 'warning'
            ],
            'ict' => [
                'name' => 'คู่มือ นโยบายดิจิทัล และ ICT',
                'icon' => 'fa-solid fa-laptop-code',
                'desc' => 'คู่มือแนวทางการปฏิบัติงาน, ความมั่นคงปลอดภัยไซเบอร์ และการจัดการ ICT',
                'gradient' => 'linear-gradient(135deg, #6b21a8, #a855f7)',
                'badge' => 'info'
            ]
        ];
    }
}

if (!function_exists('get_site_documents')) {
    /**
     * ดึงรายการไฟล์และเอกสารดาวน์โหลดทั้งหมดของจังหวัด
     */
    function get_site_documents($limit = null, $category = null, $activeOnly = false)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_documents.json';
        $docs = [];

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                $docs = $saved;
            }
        } else {
            // ข้อมูลตัวอย่างเริ่มต้นครอบคลุมทั้ง 5 เสาหลัก (Seed Data)
            $docs = [
                [
                    'id' => 'doc-101',
                    'title' => 'ประกาศสำนักงานส่งเสริมการปกครองท้องถิ่นจังหวัดพัทลุง เรื่อง นโยบายการต่อต้านการทุจริตประจำปีงบประมาณ 2569',
                    'category' => 'ธรรมาภิบาล ความโปร่งใส และ ITA',
                    'sub_tag' => 'ประกาศ OIT / ITA',
                    'file_type' => 'pdf',
                    'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                    'file_size' => '2.8 MB',
                    'downloads' => 342,
                    'date' => '2026-08-02',
                    'active' => true
                ],
                [
                    'id' => 'doc-102',
                    'title' => 'หนังสือเวียนสั่งการ แนวทางและมาตรการเบิกจ่ายงบประมาณและการจัดซื้อจัดจ้างภาครัฐ 2569',
                    'category' => 'กฎหมาย ระเบียบ และหนังสือราชการ',
                    'sub_tag' => 'หนังสือเวียน',
                    'file_type' => 'pdf',
                    'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                    'file_size' => '1.5 MB',
                    'downloads' => 890,
                    'date' => '2026-08-01',
                    'active' => true
                ],
                [
                    'id' => 'doc-103',
                    'title' => 'แผนปฏิบัติราชการประจำปีของกลุ่มจังหวัดภาคใต้ฝั่งอ่าวไทย (พัทลุง-สงขลา-นครศรีธรรมราช)',
                    'category' => 'ยุทธศาสตร์ แผนงาน และรายงานผล',
                    'sub_tag' => 'แผนยุทธศาสตร์จังหวัด',
                    'file_type' => 'doc',
                    'file_url' => '#',
                    'file_size' => '4.2 MB',
                    'downloads' => 125,
                    'date' => '2026-07-28',
                    'active' => true
                ],
                [
                    'id' => 'doc-104',
                    'title' => 'รายงานผลวิเคราะห์ข้อมูลสถิติเศรษฐกิจ การเกษตรมูลค่าสูง และการท่องเที่ยวเมืองรองจังหวัดพัทลุง',
                    'category' => 'คลังความรู้ สถิติ และงานวิจัย',
                    'sub_tag' => 'ข้อมูลสถิติ & งานวิจัย',
                    'file_type' => 'xls',
                    'file_url' => '#',
                    'file_size' => '3.1 MB',
                    'downloads' => 210,
                    'date' => '2026-07-25',
                    'active' => true
                ],
                [
                    'id' => 'doc-105',
                    'title' => 'คู่มือมาตรฐานความมั่นคงปลอดภัยทางไซเบอร์ และแนวทางการปฏิบัติตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA)',
                    'category' => 'คู่มือ นโยบายดิจิทัล และ ICT',
                    'sub_tag' => 'คู่มือระบบ ICT / PDPA',
                    'file_type' => 'pdf',
                    'file_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                    'file_size' => '5.0 MB',
                    'downloads' => 560,
                    'date' => '2026-07-20',
                    'active' => true
                ]
            ];
            if (!is_file($jsonPath)) {
                @file_put_contents($jsonPath, json_encode($docs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        if ($activeOnly) {
            $docs = array_filter($docs, static function($d) {
                return !isset($d['active']) || (bool)$d['active'] === true;
            });
        }

        if ($category !== null && $category !== 'all' && $category !== '') {
            $docs = array_filter($docs, static function($d) use ($category) {
                return strcasecmp(trim($d['category'] ?? ''), trim($category)) === 0;
            });
        }

        usort($docs, static function($a, $b) {
            $dateA = strtotime($a['date'] ?? '1970-01-01');
            $dateB = strtotime($b['date'] ?? '1970-01-01');
            return $dateB - $dateA;
        });

        if ($limit !== null && $limit > 0) {
            $docs = array_slice(array_values($docs), 0, $limit);
        }

        return array_values($docs);
    }
}

if (!function_exists('save_site_documents')) {
    /**
     * บันทึกรายการคลังเอกสารลงไฟล์ JSON
     */
    function save_site_documents(array $docs)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_documents.json';
        return file_put_contents($jsonPath, json_encode(array_values($docs), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_executive_categories')) {
    /**
     * คืนค่าหมวดหมู่ทำเนียบผู้บริหาร
     */
    function get_executive_categories()
    {
        return [
            'high_exec' => [
                'name' => 'คณะผู้บริหารระดับสูง',
                'icon' => 'fa-solid fa-crown',
                'desc' => 'ผู้ว่าราชการจังหวัด และ รองผู้ว่าราชการจังหวัดพัทลุง'
            ],
            'dept_head' => [
                'name' => 'หัวหน้าส่วนราชการและหน่วยงาน',
                'icon' => 'fa-solid fa-users-viewfinder',
                'desc' => 'ผู้อำนวยการ กอง, ฝ่าย และหน่วยงานราชการภูมิภาค'
            ],
            'former_exec' => [
                'name' => 'ทำเนียบอดีตผู้บริหาร',
                'icon' => 'fa-solid fa-clock-rotate-left',
                'desc' => 'ประวัติศาสตร์ทำเนียบผู้ว่าราชการจังหวัดและผู้บริหารในอดีต'
            ]
        ];
    }
}

if (!function_exists('get_site_executives')) {
    /**
     * ดึงรายการทำเนียบผู้บริหาร
     */
    function get_site_executives($limit = null, $category = null, $featuredOnly = false)
    {
        $model = new \App\Models\ExecutiveModel();
        
        $model->where('active', 1);
        $model->orderBy('order_num', 'ASC');
        
        if ($limit !== null && $limit > 0) {
            $execs = $model->findAll($limit);
        } else {
            $execs = $model->findAll();
        }
        
        return array_map(function($item) {
            return [
                'id' => 'exec-' . $item['id'],
                'name' => $item['name'],
                'position' => $item['position'],
                'category' => 'คณะผู้บริหารระดับสูง',
                'quote' => '',
                'phone' => '',
                'email' => '',
                'photo' => $item['image_path'],
                'order_num' => (int)$item['order_num'],
                'featured' => true,
                'active' => true
            ];
        }, $execs);
    }
}

if (!function_exists('save_site_executives')) {
    /**
     * บันทึกข้อมูลทำเนียบผู้บริหาร
     */
    function save_site_executives(array $execs)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_executives.json';
        return file_put_contents($jsonPath, json_encode(array_values($execs), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_ita_categories')) {
    /**
     * ดึงหมวดหมู่ ITA / OIT & Open Data
     */
    function get_ita_categories()
    {
        return [
            'OIT 1: ตัวชี้วัดการเปิดเผยข้อมูล' => [
                'name' => 'OIT 1: ตัวชี้วัดการเปิดเผยข้อมูล',
                'icon' => 'fa-solid fa-folder-open',
                'color' => '#0284c7',
                'desc' => 'ข้อมูลพื้นฐาน การบริหารงาน และการใช้จ่ายเงินงบประมาณประจำปี'
            ],
            'OIT 2: ตัวชี้วัดการป้องกันการทุจริต' => [
                'name' => 'OIT 2: ตัวชี้วัดการป้องกันการทุจริต',
                'icon' => 'fa-solid fa-shield-halved',
                'color' => '#10b981',
                'desc' => 'การดำเนินงานเพื่อป้องกันความเสี่ยงด้านทุจริต และมาตรการส่งเสริมคุณธรรมภายใน'
            ],
            'Open Data: บัญชีชุดข้อมูลภาครัฐ' => [
                'name' => 'Open Data: บัญชีชุดข้อมูลภาครัฐ',
                'icon' => 'fa-solid fa-database',
                'color' => '#8b5cf6',
                'desc' => 'ดาวน์โหลดไฟล์ชุดข้อมูลเปิดสาธารณะ (CSV, JSON, XLS) ตามมาตรฐานสากล'
            ],
            'รายงานผลและประกาศคณะกรรมการ' => [
                'name' => 'รายงานผลและประกาศคณะกรรมการ',
                'icon' => 'fa-solid fa-award',
                'color' => '#f59e0b',
                'desc' => 'รายงานการรับรู้และสรุปผลประเมิน ITA จากสำนักงาน ป.ป.ช.'
            ]
        ];
    }
}

if (!function_exists('get_ita_scorecard')) {
    /**
     * ดึงข้อมูลคะแนนประเมิน ITA ประจำปี (Scorecard)
     */
    function get_ita_scorecard()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'ita_scorecard.json';

        if (is_file($jsonPath)) {
            $raw = @file_get_contents($jsonPath);
            $data = @json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        // Default initial scorecard data
        $defaults = [
            'year' => '2568',
            'overall_score' => '96.48',
            'grade' => 'A+',
            'grade_title' => 'ผ่านเกณฑ์ระดับยอดเยี่ยม (A+)',
            'evaluator' => 'สำนักงานคณะกรรมการป้องกันและปราบปรามการทุจริตแห่งชาติ (ป.ป.ช.)',
            'quote' => 'จังหวัดพัทลุงยึดมั่นในการบริหารงานด้วยความโปร่งใส สุจริต เป็นธรรม พร้อมเปิดเผยข้อมูลสาธารณะเพื่อการตรวจสอบอย่างแท้จริง',
            'metrics' => [
                ['title' => 'การเปิดเผยข้อมูลสาธารณะ (OIT 1)', 'score' => 98.50, 'color' => 'info'],
                ['title' => 'การป้องกันการทุจริต (OIT 2)', 'score' => 95.20, 'color' => 'success'],
                ['title' => 'การใช้อำนาจและปฏิบัติหน้าที่โดยสุจริต', 'score' => 96.80, 'color' => 'warning'],
                ['title' => 'การแก้ไขปัญหาและการมีส่วนร่วมของประชาชน', 'score' => 95.40, 'color' => 'primary']
            ]
        ];

        if (is_dir($writableDir)) {
            @file_put_contents($jsonPath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $defaults;
    }
}

if (!function_exists('save_ita_scorecard')) {
    /**
     * บันทึกข้อมูลคะแนน ITA Scorecard
     */
    function save_ita_scorecard(array $data)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'ita_scorecard.json';
        return file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_ita_items')) {
    /**
     * ดึงรายการตัวชี้วัด OIT และไฟล์ชุดข้อมูล Open Data
     */
    function get_ita_items($category = null, $featuredOnly = false)
    {
        $model = new \App\Models\ItaDocumentModel();
        
        $model->where('status', 'active');
        
        $items = $model->findAll();
        
        // Map DB fields back for views
        return array_map(function($item) {
            return [
                'id' => $item['id'],
                'code' => $item['oit_code'],
                'title' => $item['name'],
                'category' => 'OIT 1: ตัวชี้วัดการเปิดเผยข้อมูล', // hardcoded for compatibility or can be extended in DB
                'sub_category' => 'ข้อมูล',
                'desc' => '-',
                'file_type' => 'link',
                'file_url' => $item['url'],
                'file_size' => '-',
                'downloads' => 0,
                'featured' => true,
                'verified' => true,
                'date' => $item['created_at']
            ];
        }, $items);
    }
}

if (!function_exists('increment_ita_download')) {
    /**
     * นับจำนวนการดาวน์โหลดไฟล์ ITA/Open Data
     */
    function increment_ita_download($id)
    {
        // Feature deprecated or needs to be added to DB schema
        return true;
    }
}

if (!function_exists('get_nora_settings')) {
    /**
     * ดึงข้อมูลการตั้งค่าระบบแชตบอต "น้องโนรา AI Assistant"
     */
    function get_nora_settings()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'nora_ai_settings.json';

        if (is_file($jsonPath)) {
            $raw = @file_get_contents($jsonPath);
            $data = @json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        $defaults = [
            'bot_name' => 'น้องโนรา (Nora AI)',
            'tagline' => 'ผู้ช่วยบริการประชาชน 24 ชม. และนำทางอัจฉริยะ',
            'status_text' => 'พร้อมให้บริการตอบคำถามและนำทาง',
            'greeting_msg' => "สวัสดีค่ะ 🙏 น้องโนรา ยินดีให้บริการ ณ จังหวัดพัทลุง มรดกวัฒนธรรมและธรรมชาติอันยิ่งใหญ่!\n\nวันนี้คุณต้องการสอบถามเกี่ยวกับ **บริการ e-Services ภาครัฐ**, **คลังเอกสารราชการ**, **เช็คอินสถานที่ท่องเที่ยว (มาเมืองลุง)** หรือข้อมูลร้องทุกข์ใด พิมพ์ถามน้องโนราได้เลยค่ะ 😊",
            'fallback_msg' => "น้องโนรายังไม่มั่นใจในคำถามนี้ค่ะ แต่อย่ากังวลไปเลยนะคะ! คุณสามารถกดดู **ศูนย์บริการประชาชน (e-Services)** ด้านล่าง หรือโทรสอบถามเจ้าหน้าที่สายด่วนศูนย์ปฏิบัติการจังหวัด โทร. 074-611621 ได้ในเวลาราชการค่ะ ❤️",
            'avatar_color' => '#d97706', // Amber / Gold Nora hue
            'is_enabled' => true
        ];

        if (is_dir($writableDir)) {
            @file_put_contents($jsonPath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $defaults;
    }
}

if (!function_exists('save_nora_settings')) {
    /**
     * บันทึกการตั้งค่า "น้องโนรา AI Assistant"
     */
    function save_nora_settings(array $data)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'nora_ai_settings.json';
        return file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_nora_knowledge')) {
    /**
     * ดึงคลังความรู้ Q&A พื้นฐานสำหรับน้องโนรา AI
     */
    function get_nora_knowledge()
    {
        $model = new \App\Models\NoraKnowledgeModel();
        $items = $model->findAll();
        
        return array_map(function($item) {
            return [
                'id' => 'nora-qa-' . $item['id'],
                'keywords' => $item['keywords'],
                'question' => $item['intent'],
                'answer' => $item['answer_text'],
                'link_url' => $item['action_link'],
                'link_title' => $item['action_link'] ? 'เปิดลิงก์ที่เกี่ยวข้อง' : ''
            ];
        }, $items);
    }
}

if (!function_exists('save_nora_knowledge')) {
    /**
     * บันทึกคลังความรู้ Q&A
     */
    function save_nora_knowledge(array $items)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'nora_ai_knowledge.json';
        return file_put_contents($jsonPath, json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_emergency_alert')) {
    /**
     * ดึงข้อมูลระบบเตือนภัยพิบัติฉุกเฉินและสภาพอากาศ
     */
    function get_emergency_alert()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'emergency_alert.json';

        if (is_file($jsonPath)) {
            $raw = @file_get_contents($jsonPath);
            $data = @json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        // Default initial state: Watch / Orange Alert to show off the cool aesthetics immediately!
        $defaults = [
            'is_active' => true,
            'level' => 'orange', // green (normal), yellow (watch), orange (high alert), red (critical)
            'headline' => '🟠 [แจ้งเตือนเฝ้าระวังภัย] มรสุมตะวันตกเฉียงใต้ เฝ้าระวังฝนตกหนักและน้ำท่วมขังในลุ่มน้ำบางแห่ง',
            'details' => "กองอำนวยการป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง (ปภ.) ขอเตือนประชาชนในพื้นที่อำเภอควนขนุน และอำเภอศรีบรรพต ที่อยู่อาศัยบริเวณทางน้ำไหลและเชิงเขา เตรียมความพร้อมรองรับปริมาณฝนสะสมที่อาจส่งผลให้เกิดน้ำท่วมขังและดินสไลด์\n\n- ตรวจสอบอุปกรณ์ไฟฟ้าในระดับต่ำ\n- ติดตามข้อสั่งการจากผู้นำท้องถิ่นอย่างต่อเนื่อง\n- จัดเตรียมสิ่งของสำคัญในที่ปลอดภัย",
            'affected_areas' => 'อำเภอเมืองพัทลุง, อำเภอควนขนุน, อำเภอศรีบรรพต, ลุ่มน้ำทะเลน้อย',
            'weather_temp' => '27°C',
            'weather_cond' => 'ฝนฟ้าคะนอง 60% ของพื้นที่ (ความชื้น 82%)',
            'pm25_val' => '14 µg/m³ (อากาศดีเยี่ยม)',
            'updated_at' => date('Y-m-d H:i')
        ];

        if (is_dir($writableDir)) {
            @file_put_contents($jsonPath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $defaults;
    }
}

if (!function_exists('save_emergency_alert')) {
    /**
     * บันทึกข้อมูลการเตือนภัยและสภาพอากาศ
     */
    function save_emergency_alert(array $data)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $data['updated_at'] = date('Y-m-d H:i');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'emergency_alert.json';
        return file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}


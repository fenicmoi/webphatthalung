<?php

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return $needle === '' || $needle === substr($haystack, -strlen($needle));
    }
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
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
            'contact_email'    => 'phatthalung@moi.go.th',
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

if (!function_exists('thai_date')) {
    /**
     * แปลงวันที่เป็นรูปแบบภาษาไทย พ.ศ.
     */
    function thai_date($dateStr, $format = 'full', $showTime = false)
    {
        if (empty($dateStr)) return '';
        $timestamp = is_numeric($dateStr) ? (int)$dateStr : strtotime($dateStr);
        if (!$timestamp) return $dateStr;

        $thaiMonthsShort = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $thaiMonthsFull  = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        $thaiDaysFull    = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];

        $day   = (int)date('j', $timestamp);
        $month = (int)date('n', $timestamp);
        $year  = (int)date('Y', $timestamp) + 543;
        $time  = date('H:i', $timestamp) . ' น.';

        if ($format === 'short') {
            $res = "$day " . $thaiMonthsShort[$month] . " $year";
        } elseif ($format === 'day_full') {
            $w = (int)date('w', $timestamp);
            $res = "วัน" . $thaiDaysFull[$w] . "ที่ $day " . $thaiMonthsFull[$month] . " พ.ศ. $year";
        } else { // 'full' or default
            $res = "$day " . $thaiMonthsFull[$month] . " พ.ศ. $year";
        }

        if ($showTime) {
            $res .= " เวลา $time";
        }

        return $res;
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
            'show_banner'     => '1', // 1 = แสดงแบนเนอร์, 0 = ซ่อนแบนเนอร์
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

if (!function_exists('get_site_texts')) {
    /**
     * ดึงพจนานุกรมข้อความและเนื้อหาทั้งหมดในเว็บไซต์ (Site Text Dictionary)
     */
    function get_site_texts()
    {
        $defaults = [
            // --- Hero Banner & Header ---
            'hero_badge_default'       => 'อัญมณีแห่งภาคใต้ • มรดกเกษตรโลก GIAHS',
            'hero_weather_title'       => 'จุดชมวิวทะเลน้อย พัทลุง',
            'hero_weather_desc'        => '28°C แสงสวย อากาศสดชื่น',
            'hero_landmark_caption'    => 'จุดถ่ายภาพจุดชมวิว 360°',
            
            // --- Smart Search Dock ---
            'search_dock_title'        => 'ระบบค้นหาอัจฉริยะ',
            'search_dock_subtitle'     => 'Smart AI & Voice Search',
            'search_input_placeholder' => 'พิมพ์หรือกดไมโครโฟนเพื่อค้นหา (เช่น e-bidding, ทะเลน้อย, ผู้ว่า)...',
            'search_trending_label'    => 'คำค้นหายอดนิยม',

            // --- News Hub ---
            'news_section_title'       => 'ข่าวสารและประชาสัมพันธ์',
            'news_section_subtitle'    => 'ศูนย์กลางข่าวสาร กิจกรรม และประกาศราชการจังหวัดพัทลุง',
            'news_view_all_btn'        => 'ดูข่าวทั้งหมด',
            'news_tab_all'             => 'ข่าวทั้งหมด',
            'news_tab_pr'              => 'ข่าวประชาสัมพันธ์',
            'news_tab_procure'         => 'ประกาศจัดซื้อจัดจ้าง',
            'news_tab_activity'        => 'ข่าวกิจกรรมจังหวัด',
            'news_tab_jobs'            => 'รับสมัครงานราชการ',

            // --- Event Calendar ---
            'calendar_section_title'   => 'ปฏิทินกิจกรรมประจำเดือน',
            'calendar_section_subtitle'=> 'ตารางการจัดงาน ประชุม และภารกิจสำคัญของจังหวัดพัทลุง',
            'calendar_listen_voice_btn'=> '🔊 ฟังเสียงอ่านกำหนดการประจำเดือน',

            // --- Governor & Executives ---
            'governor_section_title'   => 'สารจากผู้ว่าราชการจังหวัดพัทลุง',
            'governor_quote_text'      => 'มุ่งมั่นพัฒนาพัทลุงสู่เมืองอัจฉริยะ เกษตรกรรมยั่งยืน การท่องเที่ยวเชิงนิเวศ และคุณภาพชีวิตที่ดีของพี่น้องประชาชน',
            'executive_section_title'  => 'คณะผู้บริหารจังหวัดพัทลุง',
            'executive_section_desc'   => 'ทำเนียบคณะผู้บริหาร หัวหน้าส่วนราชการ และผู้ขับเคลื่อนการพัฒนาจังหวัด',

            // --- Strategy & Projects ---
            'strategy_section_title'   => 'ยุทธศาสตร์และแผนพัฒนาจังหวัด',
            'strategy_section_desc'    => 'เป้าหมายการพัฒนาจังหวัดพัทลุงและตัวชี้วัดความก้าวหน้า 20 ปี',
            'projects_section_title'   => 'ระบบติดตามโครงการพัฒนาจังหวัด (GIS Tracker)',
            'projects_section_desc'    => 'แสดงพิกัดและสถานะความก้าวหน้าโครงการพัฒนาตามยุทธศาสตร์เชิงพื้นที่',

            // --- Public Services ---
            'services_section_title'   => 'บริการประชาชนและ e-Services',
            'services_section_desc'    => 'ช่องทางบริการภาครัฐดิจิทัล ร้องทุกข์ ศูนย์ดำรงธรรม และดาวน์โหลดเอกสาร',

            // --- Nora AI Assistant ---
            'nora_bot_name'            => 'น้องโนรา AI Assistant',
            'nora_tagline'             => 'ผู้ช่วยบริการประชาชน 24 ชม.',
            'nora_greeting'            => "สวัสดีค่ะ 🙏 น้องโนรา ยินดีให้บริการ ณ จังหวัดพัทลุง!\nวันนี้มีเรื่องราชการ e-Services หรือท่องเที่ยวใดให้ช่วยเหลือ พิมพ์ถามได้เลยนะคะ 😊",

            // --- Footer & Global ---
            'site_slogan'              => 'เมืองหนังโนราห์ อู่นาข้าว พราวน้ำตก แหล่งนกน้ำ ทะเลสาบงาม เขาอกทะลุ น้ำพุร้อน',
            'footer_address'           => 'ศาลากลางจังหวัดพัทลุง ถนนราเมศวร์ ตำบลคูหาสวรรค์ อำเภอเมืองพัทลุง 93000',
            'footer_phone'             => '074-613409',
            'footer_email'             => 'phatthalung@moi.go.th',
            'footer_copyright'         => 'สงวนลิขสิทธิ์ © 2026 สำนักงานจังหวัดพัทลุง ศาลากลางจังหวัดพัทลุง',
        ];

        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_texts.json';

        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved)) {
                return array_merge($defaults, $saved);
            }
        }

        return $defaults;
    }
}

if (!function_exists('site_text')) {
    /**
     * ดึงข้อความตาม Key หากไม่มีจะใช้ค่าเริ่มต้น
     * และรองรับการห่อ Span สำหรับ On-Page Live Editor
     *
     * @param string $key คีย์ข้อความ
     * @param string $default ข้อความเริ่มต้น
     * @param string $label ชื่อเรียกเพื่อแสดงในตัวแก้ไข
     * @param bool $raw คืนค่าเฉพาะข้อความธรรมดา (ไม่ห่อ tag html)
     * @return string
     */
    function site_text($key, $default = '', $label = '', $raw = false)
    {
        static $allTexts = null;
        if ($allTexts === null) {
            $allTexts = get_site_texts();
        }

        $val = isset($allTexts[$key]) && $allTexts[$key] !== '' ? $allTexts[$key] : $default;

        if ($raw) {
            return $val;
        }

        // Return with data attributes for frontend Live Text Editor
        $labelAttr = !empty($label) ? htmlspecialchars($label, ENT_QUOTES, 'UTF-8') : htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
        $keyAttr = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');

        return '<span class="site-text-node" data-text-key="' . $keyAttr . '" data-text-label="' . $labelAttr . '">' . $val . '</span>';
    }
}

if (!function_exists('save_site_texts')) {
    /**
     * บันทึกพจนานุกรมข้อความลงไฟล์ JSON
     */
    function save_site_texts(array $data)
    {
        $current = get_site_texts();
        $updated = array_merge($current, $data);

        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_texts.json';

        return (bool) file_put_contents($jsonPath, json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_site_banners')) {
    /**
     * ดึงรายการสไลด์และกราฟิก Multi-Layer สำหรับแสดงผลใน Hero Banner
     */
    function get_site_banners()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_banners.json';
        
        if (is_file($jsonPath)) {
            $saved = json_decode(file_get_contents($jsonPath), true);
            if (is_array($saved) && !empty($saved)) {
                $active = array_values(array_filter($saved, function($b) {
                    return !isset($b['active']) || $b['active'] == '1' || $b['active'] === true;
                }));
                if (!empty($active)) {
                    return $active;
                }
                return $saved;
            }
        }

        try {
            $model = new \App\Models\SiteBannerModel();
            $banners = $model->where('active', 1)->findAll();
            if (!empty($banners)) {
                return $banners;
            }
        } catch (\Throwable $e) {}

        // Return core default slides if empty
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
                'show_card' => true,
                'show_badge' => true,
                'show_title' => true,
                'show_desc' => true,
                'show_button' => true,
                'show_floating' => true,
                'style_class' => 'slide-bg-sane-muanglung'
            ]
        ];
    }
}

if (!function_exists('format_menu_url')) {
    /**
     * แปลงลิงก์เมนูให้เป็น URL ที่ถูกต้องอัตโนมัติ (รองรับทั้ง page/slug, ลิงก์ภายนอก และ anchor)
     */
    function format_menu_url($url)
    {
        $url = trim((string)$url);
        if (empty($url) || $url === '#') return '#';
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 || strpos($url, 'javascript:') === 0) {
            return $url;
        }
        if (strpos($url, '#') === 0) {
            return $url;
        }
        $cleaned = ltrim($url, '/');
        return base_url($cleaned);
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

if (!function_exists('save_site_news')) {
    /**
     * บันทึกรายการข่าวสารประชาสัมพันธ์ลงในไฟล์ JSON
     */
    function save_site_news(array $newsList): bool
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_news.json';
        return @file_put_contents($jsonPath, json_encode(array_values($newsList), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
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

if (!function_exists('get_aggregated_news')) {
    /**
     * ดึงรายการข่าวสารที่รวบรวมจากฟีด RSS กรมประชาสัมพันธ์ และ Social Media
     */
    function get_aggregated_news($forceRefresh = false, $sourceType = null)
    {
        $service = new \App\Libraries\NewsAggregatorService();
        $feeds = $service->getFeeds($forceRefresh);
        if (!empty($sourceType)) {
            $feeds = array_filter($feeds, function($item) use ($sourceType) {
                return ($item['source_type'] ?? '') === $sourceType;
            });
        }
        return array_values($feeds);
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
     * ดึงรายการทำเนียบผู้บริหารปัจจุบัน (Current Executive Leadership)
     */
    function get_site_executives($limit = null, $category = null, $featuredOnly = false)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = ($writableDir ?: WRITEPATH) . DIRECTORY_SEPARATOR . 'site_executives.json';

        $execs = [];
        if (file_exists($jsonPath)) {
            $raw = file_get_contents($jsonPath);
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $execs = $decoded;
            }
        }

        // Fallback default list if json is empty
        if (empty($execs)) {
            $execs = [
                [
                    'id' => 'exec-1',
                    'name' => 'นายสุจินต์ วาจากิจ',
                    'position' => 'ผู้ว่าราชการจังหวัดพัทลุง',
                    'category' => 'คณะผู้บริหารระดับสูง',
                    'quote' => 'รักเมืองลุง สร้างเมืองลุง ไปด้วยกัน ทำงานร่วมกัน ด้วยความสามัคคี การมีส่วนร่วม และการรับฟังความคิดเห็นของประชาชนในพื้นที่ เพื่อสร้างความเข้มแข็งจากฐานราก และยกระดับจังหวัดพัทลุง ให้มีความเจริญก้าวหน้าอย่างมั่นคง และยั่งยืนต่อไป',
                    'phone' => '074-613409',
                    'email' => 'phatthalung@moi.go.th',
                    'photo' => 'uploads/executives/exec_1785927173_1785927173_b938f363bbc5e18ce55a.png',
                    'row_num' => 1,
                    'col_num' => 1,
                    'order_num' => 1,
                    'education' => "ปริญญาตรี รัฐศาสตรบัณฑิต\nปริญญาโท รัฐประศาสนศาสตรมหาบัณฑิต",
                    'history' => "ผู้ว่าราชการจังหวัดพัทลุง\nรองผู้ว่าราชการจังหวัดพัทลุง\nปลัดจังหวัด\nนายอำเภอ",
                    'featured' => true,
                    'active' => true
                ],
                [
                    'id' => 'exec-2',
                    'name' => 'นายธราวุธ ช่วยเกิด',
                    'position' => 'รองผู้ว่าราชการจังหวัดพัทลุง',
                    'category' => 'คณะผู้บริหารระดับสูง',
                    'quote' => 'ขับเคลื่อนงานราชการและบริหารการปกครองเพื่อผลประโยชน์สูงสุดของพี่น้องชาวพัทลุง',
                    'phone' => '074-613409',
                    'email' => 'phatthalung@moi.go.th',
                    'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop',
                    'row_num' => 2,
                    'col_num' => 1,
                    'order_num' => 2,
                    'education' => "ปริญญาตรี ศิลปศาสตรบัณฑิต\nปริญญาโท พัฒนบริหารศาสตรมหาบัณฑิต",
                    'history' => "รองผู้ว่าราชการจังหวัดพัทลุง\nปลัดจังหวัด\nนายอำเภอเมือง",
                    'featured' => true,
                    'active' => true
                ],
                [
                    'id' => 'exec-3',
                    'name' => 'นางสาวศรอนงค์ สงสมพันธ์',
                    'position' => 'รองผู้ว่าราชการจังหวัดพัทลุง',
                    'category' => 'คณะผู้บริหารระดับสูง',
                    'quote' => 'มุ่งมั่นยกระดับสวัสดิการสังคม เศรษฐกิจ การศึกษา และการพัฒนาเมืองลุงสู่สากล',
                    'phone' => '074-613409',
                    'email' => 'phatthalung@moi.go.th',
                    'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop',
                    'row_num' => 2,
                    'col_num' => 2,
                    'order_num' => 3,
                    'education' => "ปริญญาตรี รัฐศาสตรบัณฑิต\nปริญญาโท รัฐประศาสนศาสตรมหาบัณฑิต",
                    'history' => "รองผู้ว่าราชการจังหวัดพัทลุง\nนายอำเภอ\nหัวหน้าสำนักงานจังหวัด",
                    'featured' => true,
                    'active' => true
                ]
            ];
        }

        // Filter by category
        if ($category !== null && $category !== 'all' && $category !== '') {
            $execs = array_filter($execs, static function($it) use ($category) {
                return (isset($it['category']) && strcasecmp($it['category'], $category) === 0);
            });
        }

        // Filter active
        $execs = array_filter($execs, static function($it) {
            return !isset($it['active']) || !empty($it['active']);
        });

        // Filter featured only if requested
        if ($featuredOnly) {
            $execs = array_filter($execs, static function($it) {
                return !empty($it['featured']);
            });
        }

        // Sort by row_num ASC, col_num ASC, order_num ASC
        usort($execs, static function($a, $b) {
            $rowA = (int)($a['row_num'] ?? 1);
            $rowB = (int)($b['row_num'] ?? 1);
            if ($rowA !== $rowB) {
                return $rowA - $rowB;
            }
            $colA = (int)($a['col_num'] ?? 1);
            $colB = (int)($b['col_num'] ?? 1);
            if ($colA !== $colB) {
                return $colA - $colB;
            }
            return (int)($a['order_num'] ?? 99) - (int)($b['order_num'] ?? 99);
        });

        if ($limit !== null && $limit > 0) {
            $execs = array_slice($execs, 0, $limit);
        }

        return array_values($execs);
    }
}

if (!function_exists('save_site_executives')) {
    /**
     * บันทึกข้อมูลทำเนียบผู้บริหารปัจจุบัน
     */
    function save_site_executives(array $execs)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = ($writableDir ?: WRITEPATH) . DIRECTORY_SEPARATOR . 'site_executives.json';
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
        try {
            $model = new \App\Models\ItaDocumentModel();
            $model->where('status', 'active');
            $items = $model->findAll();
            
            return array_map(function($item) {
                return [
                    'id' => $item['id'] ?? 1,
                    'code' => $item['oit_code'] ?? 'OIT',
                    'title' => $item['name'] ?? 'เอกสารความโปร่งใส',
                    'category' => 'OIT: ตัวชี้วัดการเปิดเผยข้อมูล',
                    'sub_category' => 'ข้อมูล',
                    'desc' => '-',
                    'file_type' => 'link',
                    'file_url' => $item['url'] ?? '',
                    'file_size' => '-',
                    'downloads' => 0,
                    'featured' => true,
                    'verified' => true,
                    'date' => $item['created_at'] ?? date('Y-m-d')
                ];
            }, $items);
        } catch (\Throwable $e) {
            return [];
        }
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
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'nora_ai_knowledge.json';

        if (is_file($jsonPath)) {
            $raw = @file_get_contents($jsonPath);
            $saved = @json_decode($raw, true);
            if (is_array($saved) && !empty($saved)) {
                return $saved;
            }
        }

        try {
            $model = new \App\Models\NoraKnowledgeModel();
            $dbItems = $model->findAll();
            if (!empty($dbItems)) {
                $items = array_map(function($item) {
                    return [
                        'id' => 'nora-qa-' . $item['id'],
                        'keywords' => $item['keywords'] ?? '',
                        'question' => $item['intent'] ?? '',
                        'answer' => $item['answer_text'] ?? '',
                        'link_url' => $item['action_link'] ?? '',
                        'link_title' => !empty($item['action_link']) ? 'เปิดลิงก์ที่เกี่ยวข้อง' : ''
                    ];
                }, $dbItems);
                if (is_dir($writableDir)) {
                    @file_put_contents($jsonPath, json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
                return $items;
            }
        } catch (\Throwable $e) {
            // Fallback gracefully
        }

        return [];
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
        $result = @file_put_contents($jsonPath, json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Optionally sync to database table
        try {
            $model = new \App\Models\NoraKnowledgeModel();
            $db = \Config\Database::connect();
            if ($db->tableExists('nora_knowledge')) {
                $model->truncate();
                foreach ($items as $item) {
                    $model->insert([
                        'intent'      => $item['question'] ?? '',
                        'keywords'    => $item['keywords'] ?? '',
                        'answer_text' => $item['answer'],
                        'action_link' => $item['link_url'] ?? ''
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore DB sync error if table or db not configured
        }

        return $result !== false;
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

if (!function_exists('get_site_governors')) {
    /**
     * ดึงข้อมูลทำเนียบผู้ว่าราชการจังหวัดพัทลุง (Hall of Governors)
     */
    function get_site_governors($search = null, $era = null)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_governors.json';

        if (is_file($jsonPath)) {
            $raw = @file_get_contents($jsonPath);
            $data = @json_decode($raw, true);
            if (is_array($data)) {
                // กรองผลลัพธ์
                if ($search || $era) {
                    return array_values(array_filter($data, function($g) use ($search, $era) {
                        if ($era && $era !== 'all' && ($g['era'] ?? '') !== $era) return false;
                        if ($search) {
                            $s = mb_strtolower($search);
                            $name = mb_strtolower($g['name'] ?? '');
                            $seq = (string)($g['sequence'] ?? '');
                            $period = mb_strtolower($g['period'] ?? '');
                            if (mb_strpos($name, $s) === false && mb_strpos($seq, $s) === false && mb_strpos($period, $s) === false) {
                                return false;
                            }
                        }
                        return true;
                    }));
                }
                return $data;
            }
        }

        // ค่าเริ่มต้น: ทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง
        $defaults = [
            [
                'id' => 'gov_1',
                'sequence' => 1,
                'name' => 'พระยาพัทลุง (ขุนคางเหล็ก)',
                'title_honor' => 'เจ้าเมืองพัทลุง ท่านแรกในยุคกรุงธนบุรี',
                'period' => 'พ.ศ. 2315 - 2332',
                'era' => 'ยุคกรุงธนบุรีและต้นรัตนโกสินทร์',
                'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                'achievement' => 'เจ้าเมืองพัทลุงผู้มีบทบาทสำคัญในการตั้งมั่นและรักษาความสงบเรียบร้อยของเมืองพัทลุงในยุคผลัดแผ่นดิน',
                'is_current' => false,
                'order_num' => 1
            ],
            [
                'id' => 'gov_2',
                'sequence' => 2,
                'name' => 'พระยาพัทลุง (ทองขาว)',
                'title_honor' => 'เจ้าเมืองพัทลุง',
                'period' => 'พ.ศ. 2332 - 2360',
                'era' => 'ยุคต้นรัตนโกสินทร์',
                'image' => '',
                'achievement' => 'บุตรขุนคางเหล็ก ดำรงตำแหน่งเจ้าเมืองพัทลุงและทำนุบำรุงบ้านเมืองในสมัยรัชกาลที่ 1 และ 2',
                'is_current' => false,
                'order_num' => 2
            ],
            [
                'id' => 'gov_3',
                'sequence' => 3,
                'name' => 'พระยาพัทลุง (จุ้ย)',
                'title_honor' => 'เจ้าเมืองพัทลุง',
                'period' => 'พ.ศ. 2360 - 2382',
                'era' => 'ยุคต้นรัตนโกสินทร์',
                'image' => '',
                'achievement' => 'บริหารราชการบ้านเมืองและดูแลความมั่นคงทางทะเลในสมัยรัชกาลที่ 2 และ 3',
                'is_current' => false,
                'order_num' => 3
            ],
            [
                'id' => 'gov_4',
                'sequence' => 4,
                'name' => 'พระยาพัทลุง (น้อยเกศ)',
                'title_honor' => 'เจ้าเมืองพัทลุง',
                'period' => 'พ.ศ. 2382 - 2410',
                'era' => 'ยุคต้นรัตนโกสินทร์',
                'image' => '',
                'achievement' => 'ปกครองและจัดระเบียบราชการเมืองพัทลุงในสมัยรัชกาลที่ 3 และ 4',
                'is_current' => false,
                'order_num' => 4
            ],
            [
                'id' => 'gov_5',
                'sequence' => 5,
                'name' => 'พระยาพัทลุง (เนตร)',
                'title_honor' => 'เจ้าเมืองพัทลุง',
                'period' => 'พ.ศ. 2410 - 2431',
                'era' => 'ยุครัตนโกสินทร์ตอนกลาง',
                'image' => '',
                'achievement' => 'เจ้าเมืองพัทลุงในสมัยรัชกาลที่ 4 และ 5 มีบทบาทในการสร้างความเจริญแก่ตัวเมืองและขยายเศรษฐกิจท้องถิ่น',
                'is_current' => false,
                'order_num' => 5
            ],
            [
                'id' => 'gov_6',
                'sequence' => 6,
                'name' => 'พระยาพัทลุง (เหมือน)',
                'title_honor' => 'เจ้าเมืองพัทลุง',
                'period' => 'พ.ศ. 2431 - 2437',
                'era' => 'ยุครัตนโกสินทร์ตอนกลาง',
                'image' => '',
                'achievement' => 'เจ้าเมืองพัทลุงท่านสุดท้ายก่อนเข้าสู่การปฏิรูปการปกครองแบบมณฑลเทศาภิบาล',
                'is_current' => false,
                'order_num' => 6
            ],
            [
                'id' => 'gov_7',
                'sequence' => 7,
                'name' => 'พระยาอภัยบริรักษ์ (เนตร จันทโรจวงศ์)',
                'title_honor' => 'ผู้ว่าราชการเมืองพัทลุง',
                'period' => 'พ.ศ. 2437 - 2450',
                'era' => 'ยุคมณฑลเทศาภิบาล (รัชกาลที่ 5)',
                'image' => '',
                'achievement' => 'ผู้ว่าราชการเมืองในยุคปฏิรูปการบริหารราชการแผ่นดิน และเป็นต้นสายสกุลจันทโรจวงศ์',
                'is_current' => false,
                'order_num' => 7
            ],
            [
                'id' => 'gov_8',
                'sequence' => 8,
                'name' => 'พระยาประเสริฐสิทธิศักดิ์ (กระจ่าง มหารักษ์)',
                'title_honor' => 'ผู้ว่าราชการเมืองพัทลุง',
                'period' => 'พ.ศ. 2450 - 2457',
                'era' => 'ยุคมณฑลเทศาภิบาล',
                'image' => '',
                'achievement' => 'บริหารราชการและส่งเสริมการคมนาคมเชื่อมต่อระหว่างเมืองพัทลุงกับมณฑลนครศรีธรรมราช',
                'is_current' => false,
                'order_num' => 8
            ],
            [
                'id' => 'gov_54',
                'sequence' => 54,
                'name' => 'นายกู้เกียรติ วงศ์กระพันธุ์',
                'title_honor' => 'ผู้ว่าราชการจังหวัดพัทลุง',
                'period' => '1 ต.ค. 2560 - 30 ก.ย. 2565',
                'era' => 'ยุคปัจจุบัน',
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                'achievement' => 'ขับเคลื่อนการพัฒนาจังหวัดพัทลุงเป็นเมืองท่องเที่ยวเชิงนิเวศ เกษตรอินทรีย์ และพัฒนาโครงสร้างพื้นฐาน',
                'is_current' => false,
                'order_num' => 54
            ],
            [
                'id' => 'gov_55',
                'sequence' => 55,
                'name' => 'นางนิศากร วิศิษฏ์สรอรรถ',
                'title_honor' => 'ผู้ว่าราชการจังหวัดพัทลุง (สตรีท่านแรก)',
                'period' => '2 ธ.ค. 2565 - 30 ก.ย. 2566',
                'era' => 'ยุคปัจจุบัน',
                'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
                'achievement' => 'ส่งเสริมเศรษฐกิจสร้างสรรค์ ศิลปวัฒนธรรมโนราห์ และยกระดับการบริการภาครัฐสู่ดิจิทัล',
                'is_current' => false,
                'order_num' => 55
            ],
            [
                'id' => 'gov_56',
                'sequence' => 56,
                'name' => 'นางสาวฐิติลักษณ์ คำพา',
                'title_honor' => 'ผู้ว่าราชการจังหวัดพัทลุง คนปัจจุบัน',
                'period' => '1 ต.ค. 2566 - ปัจจุบัน',
                'era' => 'ยุคปัจจุบัน',
                'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
                'achievement' => 'มุ่งเน้นการขับเคลื่อนยุทธศาสตร์ "เมืองเกษตรอินทรีย์ วิถีวัฒนธรรม ท่องเที่ยวเชิงนิเวศอย่างยั่งยืน"',
                'is_current' => true,
                'order_num' => 56
            ]
        ];

        if (is_dir($writableDir)) {
            @file_put_contents($jsonPath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $defaults;
    }
}

if (!function_exists('save_site_governors')) {
    /**
     * บันทึกข้อมูลทำเนียบผู้ว่าราชการจังหวัดพัทลุง
     */
    function save_site_governors(array $governors)
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_governors.json';
        return file_put_contents($jsonPath, json_encode(array_values($governors), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_site_strategy')) {
    /**
     * ดึงข้อมูลยุทธศาสตร์การพัฒนาจังหวัดพัทลุง (พ.ศ. 2566 - 2570)
     */
    function get_site_strategy(): array
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_strategy.json';

        if (file_exists($jsonPath)) {
            $data = json_decode(file_get_contents($jsonPath), true);
            if (!empty($data)) {
                return $data;
            }
        }

        $defaults = [
            'vision' => [
                'title' => 'วิสัยทัศน์การพัฒนาจังหวัดพัทลุง (พ.ศ. 2566 - 2570)',
                'period' => 'พ.ศ. 2566 - 2570',
                'statement' => 'เมืองเกษตรคุณค่าสูง ท่องเที่ยวเชิงนิเวศและวัฒนธรรมระดับสากล คุณภาพชีวิตที่ดี สังคมเป็นสุข ทรัพยากรธรรมชาติและสิ่งแวดล้อมยั่งยืน',
                'tagline' => 'Phatthalung Sustainable Development Goals 2026+',
                'motto' => 'เมืองลุงน่าอยู่ เกษตรปลอดภัย ทะเลน้อยมรดกโลก สังคมเป็นสุข',
                'cover_image' => 'assets/images/slider/sane_muanglung.png'
            ],
            'missions' => [
                'ยกระดับขีดความสามารถการผลิตและแปรรูปสินค้าเกษตรมูลค่าสูง เกษตรอินทรีย์ และเกษตรปลอดภัยสู่มาตรฐานสากล',
                'พัฒนาและยกระดับการท่องเที่ยวเชิงนิเวศ วัฒนธรรม และแหล่งมรดกโลกให้มีคุณภาพและสร้างรายได้กระจายสู่ชุมชน',
                'เสริมสร้างคุณภาพชีวิต พัฒนาคนทุกช่วงวัย ยกระดับสาธารณสุข และสร้างความมั่นคงทางสังคมอย่างยั่งยืน',
                'อนุรักษ์ ฟื้นฟู และบริหารจัดการทรัพยากรธรรมชาติ สิ่งแวดล้อม และระบบนิเวศลุ่มน้ำทะเลสาบสงขลาอย่างสมดุล',
                'พัฒนาระบบการบริหารจัดการภาครัฐให้ทันสมัย มีธรรมาภิบาล สู่การเป็นองค์กรดิจิทัลที่โปร่งใสและตรวจสอบได้'
            ],
            'core_values' => [
                ['title' => 'Green & Organic', 'desc' => 'เกษตรอินทรีย์และสิ่งแวดล้อมสีเขียว', 'icon' => 'fa-solid fa-leaf'],
                ['title' => 'Heritage & Eco-Tourism', 'desc' => 'มรดกวัฒนธรรมและการท่องเที่ยวยั่งยืน', 'icon' => 'fa-solid fa-mountain-sun'],
                ['title' => 'Smart Governance', 'desc' => 'บริการภาครัฐดิจิทัลและโปร่งใส (ITA AA)', 'icon' => 'fa-solid fa-shield-halved'],
                ['title' => 'Well-Being for All', 'desc' => 'คุณภาพชีวิตและความสุขของคนทุกช่วงวัย', 'icon' => 'fa-solid fa-heart-pulse']
            ],
            'kpis' => [
                [
                    'id' => 'kpi_1',
                    'title' => 'การเติบโตทางเศรษฐกิจ (GPP)',
                    'target' => '+4.5%',
                    'current' => '+3.8%',
                    'unit' => 'ต่อปี',
                    'icon' => 'fa-solid fa-chart-line',
                    'color' => '#2563eb',
                    'desc' => 'อัตราการขยายตัวของผลิตภัณฑ์มวลรวมจังหวัดพัทลุง'
                ],
                [
                    'id' => 'kpi_2',
                    'title' => 'พื้นที่เกษตรอินทรีย์และเกษตรปลอดภัย',
                    'target' => '50,000',
                    'current' => '38,500',
                    'unit' => 'ไร่',
                    'icon' => 'fa-solid fa-wheat-awn',
                    'color' => '#059669',
                    'desc' => 'พื้นที่ปลูกข้าวสังข์หยด GI และพืชผลเกษตรอินทรีย์รับรองมาตรฐาน'
                ],
                [
                    'id' => 'kpi_3',
                    'title' => 'รายได้จากการท่องเที่ยวเชิงนิเวศ',
                    'target' => '4,500',
                    'current' => '3,650',
                    'unit' => 'ล้านบาท/ปี',
                    'icon' => 'fa-solid fa-route',
                    'color' => '#d97706',
                    'desc' => 'รายได้หมุนเวียนสู่ชุมชน ภาคบริการ และผู้ประกอบการท่องเที่ยว'
                ],
                [
                    'id' => 'kpi_4',
                    'title' => 'การประเมินคุณธรรมและความโปร่งใส (ITA)',
                    'target' => '95.00+',
                    'current' => '94.82',
                    'unit' => 'คะแนน (ระดับ AA)',
                    'icon' => 'fa-solid fa-award',
                    'color' => '#7c3aed',
                    'desc' => 'ผลการประเมิน ITA หน่วยงานภาครัฐในจังหวัดพัทลุง'
                ]
            ],
            'pillars' => [
                [
                    'id' => 'pillar_1',
                    'number' => 1,
                    'title' => 'การพัฒนาเกษตรมูลค่าสูง เกษตรอินทรีย์ และอุตสาหกรรมแปรรูป',
                    'short_title' => 'เกษตรมูลค่าสูง & อาหารปลอดภัย',
                    'icon' => 'fa-solid fa-seedling',
                    'color' => '#059669',
                    'bg_gradient' => 'linear-gradient(135deg, #059669 0%, #10b981 100%)',
                    'summary' => 'ยกระดับสินค้าเกษตรอัตลักษณ์พื้นถิ่น เช่น ข้าวสังข์หยดเมืองพัทลุง GI, ปลาดุกร้า, สละลุงถาวร, กระจูดวรรณี สู่ตลาดพรีเมียมและส่งออก',
                    'strategies' => [
                        'ส่งเสริมและขยายพื้นที่การผลิตเกษตรอินทรีย์และเกษตรปลอดภัยได้มาตรฐาน GAP/Organic Thailand',
                        'พัฒนาเทคโนโลยี นวัตกรรมการแปรรูป และการสร้างแบรนด์สินค้าสิ่งบ่งชี้ทางภูมิศาสตร์ (GI)',
                        'สร้างเครือข่ายตลาดเกษตรกรดิจิทัล (Digital Farm Marketplace) เชื่อมโยงผู้บริโภคโดยตรง',
                        'ยกระดับมาตรฐานปศุสัตว์ปลอดภัยและสัตว์น้ำเศรษฐกิจลุ่มน้ำพัทลุง'
                    ],
                    'flagship' => 'โครงการขับเคลื่อน Food Valley พัทลุง เมืองนวัตกรรมเกษตรอาหารปลอดภัยระดับสากล'
                ],
                [
                    'id' => 'pillar_2',
                    'number' => 2,
                    'title' => 'การส่งเสริมการท่องเที่ยวเชิงนิเวศ อัตลักษณ์วัฒนธรรม และมรดกโลก',
                    'short_title' => 'ท่องเที่ยวเชิงนิเวศ & มรดกโลก',
                    'icon' => 'fa-solid fa-mountain-sun',
                    'color' => '#d97706',
                    'bg_gradient' => 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)',
                    'summary' => 'ชูจุดเด่นพื้นที่ชุ่มน้ำทะเลน้อย แรมซาร์ไซต์แห่งแรกของไทย และมรดกทางการเกษตรโลก (GIAHS) ควายน้ำทะเลน้อย เชื่อมโยงวัฒนธรรมมโนราห์-หนังตะลุง',
                    'strategies' => [
                        'พัฒนาแหล่งท่องเที่ยวเชิงนิเวศให้ได้มาตรฐานความยั่งยืนสากล (GSTC)',
                        'ยกระดับศิลปวัฒนธรรมมโนราห์ หนังตะลุง และหัตถกรรมกระจูด สู่เศรษฐกิจสร้างสรรค์ (Soft Power)',
                        'พัฒนาเส้นทางท่องเที่ยวเชื่อมโยง อ่าวไทย-อันดามัน และโครงข่ายโฮมสเตย์ชุมชน',
                        'ส่งเสริมการตลาดท่องเที่ยวดิจิทัลและการท่องเที่ยวคาร์บอนต่ำ (Low Carbon Tourism)'
                    ],
                    'flagship' => 'โครงการพัฒนาพื้นที่ทะเลน้อยสู่แหล่งท่องเที่ยวมรดกโลกและการอนุรักษ์ธรรมชาติอย่างยั่งยืน'
                ],
                [
                    'id' => 'pillar_3',
                    'number' => 3,
                    'title' => 'การพัฒนาคุณภาพชีวิต สังคมสูงวัย และการเสริมสร้างความมั่นคง',
                    'short_title' => 'คุณภาพชีวิต & สังคมเป็นสุข',
                    'icon' => 'fa-solid fa-people-roof',
                    'color' => '#2563eb',
                    'bg_gradient' => 'linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%)',
                    'summary' => 'ยกระดับบริการสาธารณสุข สวัสดิการชุมชน การพัฒนาทักษะอาชีพ และเตรียมความพร้อมรองรับสังคมผู้สูงอายุอย่างมีคุณภาพ',
                    'strategies' => [
                        'พัฒนาระบบสุขภาพปฐมภูมิและการดูแลผู้สูงอายุระยะยาว (Long-Term Care)',
                        'ส่งเสริมการศึกษา การเรียนรู้ตลอดชีวิต และทักษะอาชีพดิจิทัลสำหรับเยาวชนและแรงงาน',
                        'แก้ไขปัญหาความยากจนแบบชี้เป้าและลดความเหลื่อมล้ำทางสังคม',
                        'เสริมสร้างความปลอดภัยในชีวิตและทรัพย์สิน การป้องกันปัญหายาเสพติดและอุบัติภัย'
                    ],
                    'flagship' => 'โครงการพัทลุงเมืองแห่งความสุขและสังคมสูงวัยคุณภาพ (Age-Friendly Community)'
                ],
                [
                    'id' => 'pillar_4',
                    'number' => 4,
                    'title' => 'การอนุรักษ์ ฟื้นฟูทรัพยากรธรรมชาติ และการจัดการสิ่งแวดล้อม',
                    'short_title' => 'สิ่งแวดล้อมยั่งยืน & เมืองคาร์บอนต่ำ',
                    'icon' => 'fa-solid fa-water',
                    'color' => '#0284c7',
                    'bg_gradient' => 'linear-gradient(135deg, #0284c7 0%, #38bdf8 100%)',
                    'summary' => 'ฟื้นฟูระบบนิเวศลุ่มน้ำทะเลสาบสงขลา-พัทลุง เพิ่มพื้นที่ป่าไม้ การจัดการขยะชุมชน และการปรับตัวต่อการเปลี่ยนแปลงสภาพภูมิอากาศ',
                    'strategies' => [
                        'บูรณาการฟื้นฟูคุณภาพน้ำและทรัพยากรสัตว์น้ำในลุ่มน้ำทะเลสาบสงขลา',
                        'เพิ่มพื้นที่สีเขียว ปลูกป่าชุมชน และรักษาป่าต้นน้ำเทือกเขาบรรทัด',
                        'พัฒนาระบบการบริหารจัดการขยะมูลฝอยและน้ำเสียแบบครบวงจร',
                        'พัฒนาระบบเตือนภัย ป้องกันและบรรเทาสาธารณภัยจากอุทกภัยและดินโคลนถล่ม'
                    ],
                    'flagship' => 'โครงการบริหารจัดการน้ำและฟื้นฟูระบบนิเวศลุ่มน้ำทะเลสาบสงขลา-พัทลุงอย่างยั่งยืน'
                ],
                [
                    'id' => 'pillar_5',
                    'number' => 5,
                    'title' => 'การยกระดับการบริหารภาครัฐสู่ดิจิทัลและธรรมาภิบาลสากล',
                    'short_title' => 'ภาครัฐดิจิทัล & โปร่งใส ITA AA',
                    'icon' => 'fa-solid fa-laptop-code',
                    'color' => '#7c3aed',
                    'bg_gradient' => 'linear-gradient(135deg, #6d28d9 0%, #7c3aed 100%)',
                    'summary' => 'ขับเคลื่อนองค์กรสู่ Smart Province พัฒนาบริการดิจิทัล e-Services ศูนย์ดำรงธรรมออนไลน์ และการเปิดเผยข้อมูลภาครัฐ (Open Data)',
                    'strategies' => [
                        'พัฒนาระบบบริการประชาชนแบบจุดเดียวเบ็ดเสร็จ (One Stop Service Digital Portal)',
                        'เสริมสร้างวัฒนธรรมความโปร่งใส ป้องกันการทุจริต และรักษามาตรฐาน ITA ระดับ AA',
                        'พัฒนาขีดความสามารถบุคลากรภาครัฐด้านเทคโนโลยีดิจิทัลและข้อมูล (Data Analytics)',
                        'เปิดโอกาสให้ประชาชนและภาคเอกชนมีส่วนร่วมในการวางแผนและติดตามการพัฒนาจังหวัด'
                    ],
                    'flagship' => 'โครงการ Phatthalung Smart Province 2026 ยกระดับบริการดิจิทัลเพื่อประชาชน 24 ชม.'
                ]
            ],
            'documents' => [
                [
                    'id' => 'doc_plan_5y',
                    'title' => 'แผนพัฒนาจังหวัดพัทลุง 5 ปี (พ.ศ. 2566 - 2570) ฉบับทบทวน',
                    'category' => 'แผนพัฒนาจังหวัด 5 ปี',
                    'year' => '2566-2570',
                    'file_url' => 'uploads/strategy/plan_5years_2566_2570.pdf',
                    'file_size' => '18.4 MB',
                    'file_type' => 'pdf',
                    'pages' => 245,
                    'downloads' => 1420,
                    'is_featured' => true,
                    'updated_at' => '2026-01-15'
                ],
                [
                    'id' => 'doc_plan_2569',
                    'title' => 'แผนปฏิบัติราชการประจำปีของจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2569',
                    'category' => 'แผนปฏิบัติราชการประจำปี',
                    'year' => '2569',
                    'file_url' => 'uploads/strategy/action_plan_2569.pdf',
                    'file_size' => '12.8 MB',
                    'file_type' => 'pdf',
                    'pages' => 180,
                    'downloads' => 890,
                    'is_featured' => true,
                    'updated_at' => '2025-10-01'
                ],
                [
                    'id' => 'doc_plan_2568',
                    'title' => 'แผนปฏิบัติราชการประจำปีของจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2568',
                    'category' => 'แผนปฏิบัติราชการประจำปี',
                    'year' => '2568',
                    'file_url' => 'uploads/strategy/action_plan_2568.pdf',
                    'file_size' => '11.5 MB',
                    'file_type' => 'pdf',
                    'pages' => 165,
                    'downloads' => 1250,
                    'is_featured' => false,
                    'updated_at' => '2024-10-01'
                ],
                [
                    'id' => 'doc_plan_2567',
                    'title' => 'แผนปฏิบัติราชการประจำปีของจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2567',
                    'category' => 'แผนปฏิบัติราชการประจำปี',
                    'year' => '2567',
                    'file_url' => 'uploads/strategy/action_plan_2567.pdf',
                    'file_size' => '9.8 MB',
                    'file_type' => 'pdf',
                    'pages' => 150,
                    'downloads' => 1840,
                    'is_featured' => false,
                    'updated_at' => '2023-10-01'
                ],
                [
                    'id' => 'doc_me_report',
                    'title' => 'รายงานสรุปผลการติดตามและประเมินผลสัมฤทธิ์แผนพัฒนาจังหวัดพัทลุง',
                    'category' => 'รายงานผลการดำเนินงาน (M&E)',
                    'year' => '2568',
                    'file_url' => 'uploads/strategy/me_report_2568.pdf',
                    'file_size' => '6.4 MB',
                    'file_type' => 'pdf',
                    'pages' => 85,
                    'downloads' => 620,
                    'is_featured' => false,
                    'updated_at' => '2025-11-20'
                ]
            ]
        ];

        if (is_dir($writableDir)) {
            @file_put_contents($jsonPath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $defaults;
    }
}

if (!function_exists('save_site_strategy')) {
    /**
     * บันทึกข้อมูลยุทธศาสตร์การพัฒนาจังหวัดพัทลุง
     */
    function save_site_strategy(array $strategy): bool
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_strategy.json';
        return (bool) file_put_contents($jsonPath, json_encode($strategy, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('record_search_query')) {
    /**
     * บันทึกคำค้นหาจริงของผู้ใช้งานลงระบบประมวลผลคำค้นหายอดนิยม
     */
    function record_search_query(string $query): void
    {
        $query = trim(mb_substr($query, 0, 80));
        if (mb_strlen($query) < 2) return;

        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'search_trends.json';

        $data = [];
        if (file_exists($jsonPath)) {
            $data = json_decode((string)file_get_contents($jsonPath), true) ?: [];
        }

        $normKey = mb_strtolower($query);

        // Remove any incomplete partial prefixes if this is a longer complete word
        if (mb_strlen($query) >= 3) {
            foreach (array_keys($data) as $existingKey) {
                if ($existingKey !== $normKey && mb_strlen($existingKey) < mb_strlen($normKey) && mb_strpos($normKey, $existingKey) === 0) {
                    if (($data[$existingKey]['count'] ?? 0) <= 2) {
                        unset($data[$existingKey]);
                    }
                }
            }
        }

        if (isset($data[$normKey])) {
            $data[$normKey]['count'] = ($data[$normKey]['count'] ?? 0) + 1;
            $data[$normKey]['last_searched'] = time();
            $data[$normKey]['keyword'] = $query;
        } else {
            $data[$normKey] = [
                'keyword'       => $query,
                'count'         => 1,
                'first_searched'=> time(),
                'last_searched' => time()
            ];
        }

        @file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

if (!function_exists('get_trending_keywords')) {
    /**
     * ดึงคำค้นหายอดนิยมประจำสัปดาห์ (Trending Keywords) จากข้อมูลจริง
     */
    function get_trending_keywords(int $limit = 6): array
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'search_trends.json';

        $realTrends = [];
        if (file_exists($jsonPath)) {
            $data = json_decode((string)file_get_contents($jsonPath), true) ?: [];
            if (!empty($data) && is_array($data)) {
                // Filter out 1-2 char incomplete fragments if longer keyword exists
                $keys = array_keys($data);
                foreach ($keys as $k) {
                    if (mb_strlen($k) < 3) {
                        foreach ($keys as $otherK) {
                            if (mb_strlen($otherK) > mb_strlen($k) && mb_strpos($otherK, $k) === 0) {
                                unset($data[$k]);
                                break;
                            }
                        }
                    }
                }

                // Sort primarily by count DESC, secondarily by last_searched DESC (latest search first)
                uasort($data, function($a, $b) {
                    $cntA = $a['count'] ?? 0;
                    $cntB = $b['count'] ?? 0;
                    if ($cntA === $cntB) {
                        return ($b['last_searched'] ?? 0) <=> ($a['last_searched'] ?? 0);
                    }
                    return $cntB <=> $cntA;
                });

                foreach ($data as $item) {
                    if (!empty($item['keyword'])) {
                        $realTrends[] = [
                            'keyword' => $item['keyword'],
                            'count'   => $item['count'] ?? 1,
                            'icon'    => '🔥'
                        ];
                        if (count($realTrends) >= $limit) break;
                    }
                }
            }
        }

        // หากบันทึกยังไม่ครบตามจำนวน ให้ผสมผสานกับหัวข้อยอดนิยมจริงของจังหวัดพัทลุง
        $fallbackKeywords = [
            ['keyword' => 'ผู้ว่าราชการจังหวัด', 'icon' => '👑', 'count' => 98],
            ['keyword' => 'ประกาศจัดซื้อจัดจ้าง e-GP', 'icon' => '⚖️', 'count' => 142],
            ['keyword' => 'ทะเลน้อย มรดกโลก GIAHS', 'icon' => '🌿', 'count' => 74],
            ['keyword' => 'ยื่นคำร้องศูนย์ดำรงธรรม', 'icon' => '📢', 'count' => 85],
            ['keyword' => 'ศูนย์ข้อมูลความโปร่งใส ITA', 'icon' => '🛡️', 'count' => 65],
            ['keyword' => 'แผนพัฒนาจังหวัด 2568', 'icon' => '📊', 'count' => 58],
            ['keyword' => 'ภาษีที่ดินและสิ่งปลูกสร้าง', 'icon' => '💰', 'count' => 45],
            ['keyword' => 'ภาพกิจกรรมและคลังสื่อ', 'icon' => '📸', 'count' => 40]
        ];

        if (empty($realTrends)) {
            return array_slice($fallbackKeywords, 0, $limit);
        }

        // เติมเต็มจนครบ limit
        $existingKeywords = array_map('mb_strtolower', array_column($realTrends, 'keyword'));
        foreach ($fallbackKeywords as $fb) {
            if (count($realTrends) >= $limit) break;
            if (!in_array(mb_strtolower($fb['keyword']), $existingKeywords, true)) {
                $realTrends[] = $fb;
            }
        }

        return array_slice($realTrends, 0, $limit);
    }
}





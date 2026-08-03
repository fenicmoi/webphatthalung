<?php

if (!function_exists('get_site_settings')) {
    /**
     * ดึงค่าการตั้งค่าเว็บไซต์ (จากตาราง DB หรือ JSON file)
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


<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class BannerManager extends BaseController
{
    use ResponseTrait;

    private function getSettingsPath(): string
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        return $writableDir . DIRECTORY_SEPARATOR . 'banner_settings.json';
    }

    private function getBannersPath(): string
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        return $writableDir . DIRECTORY_SEPARATOR . 'site_banners.json';
    }

    public function getDefaultBanners(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'เสน่ห์เมืองลุง "เขา ป่า นา เล"',
                'subtitle' => 'อัญมณีแห่งภาคใต้ มรดกเกษตรโลก GIAHS',
                'badge_title' => 'LANDMARK',
                'badge_icon' => 'fa-solid fa-star',
                'bg_type' => 'image',
                'image_path' => 'assets/images/slider/sane_muanglung.png',
                'desc' => 'สัมผัสความอุดมสมบูรณ์ของระบบนิเวศและวัฒนธรรม นำเที่ยวทะเลน้อย ควายน้ำ ยอักษะ และวิถีเกษตรกรรม พร้อมระบบจองท่องเที่ยวชุมชนแบบอัจฉริยะ',
                'button_text' => 'เปิดโลกท่องเที่ยว',
                'button_url' => '#tourism',
                'button_icon' => 'fa-solid fa-compass',
                'active' => true,
                'show_card' => true,
                'show_badge' => true,
                'show_title' => true,
                'show_desc' => true,
                'show_button' => true,
                'show_floating' => true,
                'style_class' => 'slide-bg-sane-muanglung'
            ],
            [
                'id' => 2,
                'title' => 'SMART LIVING',
                'subtitle' => 'การสร้างชีวิตอัจฉริยะ เพื่อประชาชน',
                'badge_title' => 'SMART PHATTHALUNG 2026',
                'badge_icon' => 'fa-solid fa-globe',
                'bg_type' => 'kinetic_pole',
                'image_path' => '',
                'desc' => 'เชื่อมต่อระบบกล้อง AI อัจฉริยะ พร้อมเครือข่าย WiFi สาธารณะความเร็วสูง ครอบคลุมทุกพื้นที่ เพื่อสวัสดิภาพและความปลอดภัยสูงสุดตลอด 24 ชั่วโมง',
                'button_text' => 'เข้าใช้บริการ e-Service',
                'button_url' => '#services',
                'button_icon' => 'fa-solid fa-arrow-right',
                'active' => true,
                'show_card' => true,
                'show_badge' => true,
                'show_title' => true,
                'show_desc' => true,
                'show_button' => true,
                'show_floating' => true,
                'style_class' => 'slide-bg-living'
            ],
            [
                'id' => 3,
                'title' => 'SMART TOURISM',
                'subtitle' => 'สวรรค์ท่องเที่ยวธรรมชาติ ทะเลน้อย มรดกเกษตรโลก',
                'badge_title' => 'ECO & HERITAGE CITY',
                'badge_icon' => 'fa-solid fa-tree',
                'bg_type' => 'kinetic_nature',
                'image_path' => '',
                'desc' => 'สัมผัสประสบการณ์ท่องเที่ยวมิติดิจิทัล เช็คความปลอดภัย ลานจอดรถ และจองบริการท่องเที่ยวชุมชนผ่านแพลตฟอร์มไร้รอยต่อ',
                'button_text' => 'เปิดโลกท่องเที่ยว',
                'button_url' => '#tourism',
                'button_icon' => 'fa-solid fa-compass',
                'active' => true,
                'show_card' => true,
                'show_badge' => true,
                'show_title' => true,
                'show_desc' => true,
                'show_button' => true,
                'show_floating' => true,
                'style_class' => 'slide-bg-tourism'
            ],
            [
                'id' => 4,
                'title' => 'SMART GOVERNANCE',
                'subtitle' => 'ภาครัฐโปร่งใส รวดเร็ว ตรวจสอบได้ทุกขั้นตอน',
                'badge_title' => 'DIGITAL GOVERNANCE',
                'badge_icon' => 'fa-solid fa-landmark',
                'bg_type' => 'kinetic_gov',
                'image_path' => '',
                'desc' => 'ยื่นเรื่องร้องทุกข์ ติดตามผลการดำเนินงาน และดาวน์โหลดแบบฟอร์มหนังสือราชการผ่านเว็บพอร์ตัล ลดขั้นตอน สะดวกสบาย โดยไม่ต้องเดินทาง',
                'button_text' => 'ยื่นคำร้องออนไลน์',
                'button_url' => '#pdpa',
                'button_icon' => 'fa-solid fa-paper-plane',
                'active' => true,
                'show_card' => true,
                'show_badge' => true,
                'show_title' => true,
                'show_desc' => true,
                'show_button' => true,
                'show_floating' => true,
                'style_class' => 'slide-bg-governance'
            ]
        ];
    }

    public function index()
    {
        helper('settings');
        $data = [
            'title'      => 'จัดการแบนเนอร์และเลย์เอาต์เว็บ | Phatthalung Admin',
            'activeMenu' => 'banners',
            'bannerCfg'  => get_banner_settings(),
            'banners'    => get_site_banners()
        ];

        return view('admin/banner_manager', $data);
    }

    public function save()
    {
        // Save Settings
        $currentCfg = function_exists('get_banner_settings') ? get_banner_settings() : [];
        $newCfg = [
            'show_banner'     => $this->request->getPost('show_banner') ? '1' : '0',
            'layout_mode'     => $this->request->getPost('layout_mode') ?: 'hybrid_widescreen',
            'banner_height'   => $this->request->getPost('banner_height') ?: '540',
            'auto_play'       => $this->request->getPost('auto_play') ? '1' : '0',
            'interval_ms'     => $this->request->getPost('interval_ms') ?: '7500',
            'show_weather'    => $this->request->getPost('show_weather') ? '1' : '0',
            'show_giahs'      => $this->request->getPost('show_giahs') ? '1' : '0',
        ];
        file_put_contents($this->getSettingsPath(), json_encode($newCfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Save Slides Array from JSON POST
        $slidesJson = $this->request->getPost('slides_json');
        if (!empty($slidesJson)) {
            $decoded = json_decode($slidesJson, true);
            if (is_array($decoded)) {
                file_put_contents($this->getBannersPath(), json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        return $this->respond([
            'status'  => 'success',
            'message' => '🎉 บันทึกการตั้งค่าแบนเนอร์และรูปแบบเลย์เอาต์เว็บไซต์เรียบร้อยแล้ว! (มีผลบนหน้าเว็บจริงทันที)'
        ], 200);
    }

    public function upload()
    {
        $file = $this->request->getFile('slide_image');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->respond(['status' => 'error', 'message' => 'การอัปโหลดไฟล์ภาพล้มเหลว กรุณาตรวจสอบไฟล์'], 400);
        }

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'slider';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $newName = 'slide_' . time() . '_' . rand(100, 999) . '.' . $file->getExtension();
        $file->move($uploadDir, $newName);
        $relativePath = 'uploads/slider/' . $newName;

        return $this->respond([
            'status' => 'success',
            'path'   => $relativePath,
            'url'    => base_url($relativePath),
            'message' => 'อัปโหลดรูปภาพสไลด์สำเร็จ!'
        ], 200);
    }

    public function reset()
    {
        $settingsPath = $this->getSettingsPath();
        if (is_file($settingsPath)) {
            @unlink($settingsPath);
        }

        $bannersPath = $this->getBannersPath();
        if (is_file($bannersPath)) {
            @unlink($bannersPath);
        }

        return $this->respond([
            'status'  => 'success',
            'message' => '🔄 คืนค่าแบนเนอร์และเลย์เอาต์เป็นแบบเริ่มต้นของระบบสำเร็จ'
        ], 200);
    }
}

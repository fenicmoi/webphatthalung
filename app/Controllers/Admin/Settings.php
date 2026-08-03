<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Settings extends BaseController
{
    use ResponseTrait;

    private function getStoragePath(): string
    {
        // คำนวณตำแหน่งถอยกลับ 3 ระดับจาก App/Controllers/Admin ไปยัง Root/writable
        $writableDir = realpath(__DIR__ . '/../../../writable');
        if (!$writableDir) {
            $writableDir = __DIR__ . '/../../../writable';
            if (!is_dir($writableDir)) {
                @mkdir($writableDir, 0777, true);
            }
        }
        return rtrim($writableDir, '/\\') . DIRECTORY_SEPARATOR . 'site_settings.json';
    }

    /**
     * โหลดค่าติดตั้งปัจจุบันทั้งหมด (จาก JSON storage หรือค่าตั้งต้น)
     */
    private function getSettings(): array
    {
        $storagePath = $this->getStoragePath();
        $defaults = [
            'site_title_th'    => 'ศูนย์บริการดิจิทัลภาครัฐ จังหวัดพัทลุง',
            'site_title_en'    => 'Phatthalung Digital Government Portal',
            'slogan'           => 'บริการรวดเร็ว โปร่งใส ตรวจสอบได้ทุกขั้นตอนด้วยโครงสร้างนวัตกรรมร่วมสมัย',
            'contact_email'    => 'contact@phatthalung.go.th',
            'contact_phone'    => '074-611-234, 074-611-235',
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

        if (is_file($storagePath)) {
            $saved = json_decode(file_get_contents($storagePath), true);
            if (is_array($saved)) {
                return array_merge($defaults, $saved);
            }
        }

        return $defaults;
    }

    /**
     * แสดงหน้าต่างการตั้งค่าระบบ
     */
    public function index()
    {
        $data = [
            'title'    => 'ตั้งค่าระบบและปรับแต่งเว็บไซต์ | Phatthalung Admin',
            'settings' => $this->getSettings()
        ];

        return view('admin/settings', $data);
    }

    /**
     * บันทึกการตั้งค่าเว็บไซต์แบบ Async (No-Reload AJAX POST)
     */
    public function save()
    {
        $current = $this->getSettings();
        $storagePath = $this->getStoragePath();

        $keys = array_keys($current);
        $newSettings = [];

        foreach ($keys as $k) {
            if ($this->request->getPost($k) !== null) {
                $newSettings[$k] = trim($this->request->getPost($k));
            } else {
                // สำหรับ checkbox หรือ switch ที่ไม่ได้ติ๊ก
                if (in_array($k, ['maintenance_mode', 'public_register', 'csrf_protection'])) {
                    $newSettings[$k] = '0';
                } else {
                    $newSettings[$k] = $current[$k];
                }
            }
        }

        // จัดการอัปโหลดไฟล์โลโก้หน่วยงาน (Logo Upload Module)
        $logoFile = $this->request->getFile('site_logo_file');
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'logo';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $newFileName = 'logo_' . time() . '.' . $logoFile->getExtension();
            $logoFile->move($uploadDir, $newFileName);
            $newSettings['site_logo'] = 'uploads/logo/' . $newFileName;
        } elseif ($this->request->getPost('remove_logo') === '1') {
            $newSettings['site_logo'] = '';
        } elseif (!isset($newSettings['site_logo'])) {
            $newSettings['site_logo'] = $current['site_logo'] ?? '';
        }

        // บันทึกรูปลักษณ์และข้อมูลในไฟล์ Persistent storage
        file_put_contents($storagePath, json_encode($newSettings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $this->respond([
            'status'  => 'success',
            'message' => '🎉 บันทึกการตั้งค่าระบบและข้อความประชาสัมพันธ์เรียบร้อยแล้ว!'
        ], 200);
    }
}

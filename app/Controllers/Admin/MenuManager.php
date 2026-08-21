<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class MenuManager extends BaseController
{
    use ResponseTrait;

    /**
     * ค้นหาหรือสร้างไฟล์บันทึกโครงสร้างเมนูเว็บไซต์ในแฟ้ม writable
     */
    private function getStoragePath(): string
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        if (!$writableDir) {
            $writableDir = __DIR__ . '/../../../writable';
            if (!is_dir($writableDir)) {
                @mkdir($writableDir, 0777, true);
            }
        }
        return rtrim($writableDir, '/\\') . DIRECTORY_SEPARATOR . 'site_menus.json';
    }

    /**
     * โครงสร้างเมนูมาตรฐานสำหรับเว็บพอร์ทัลจังหวัด (ราชการสมัยใหม่)
     */
    public function getDefaultMenus(): array
    {
        return [
            [
                'id' => 'menu_' . uniqid(),
                'title' => 'หน้าแรก',
                'url' => base_url(),
                'icon' => 'fa-solid fa-house',
                'target' => '_self',
                'children' => []
            ],
            [
                'id' => 'menu_' . uniqid(),
                'title' => 'เกี่ยวกับจังหวัด',
                'url' => '#about',
                'icon' => 'fa-solid fa-landmark',
                'target' => '_self',
                'children' => [
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'ประวัติความเป็นมาและคำขวัญ',
                        'url' => '#history',
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'ทำเนียบผู้ว่าราชการจังหวัด',
                        'url' => base_url('governors'),
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'คณะผู้บริหารจังหวัดชุดปัจจุบัน',
                        'url' => base_url('executives'),
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'สัญลักษณ์และอัตลักษณ์ประจำเมือง',
                        'url' => '#symbols',
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'แผนยุทธศาสตร์การพัฒนาพัทลุง 2026',
                        'url' => '#strategy',
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'แผนที่สารสนเทศภูมิศาสตร์ (GIS Map)',
                        'url' => 'gis',
                        'target' => '_self'
                    ]
                ]
            ],
            [
                'id' => 'menu_' . uniqid(),
                'title' => 'บริการประชาชน e-Service',
                'url' => '#services',
                'icon' => 'fa-solid fa-laptop-file',
                'target' => '_self',
                'children' => [
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'ยื่นเรื่องร้องเรียน ศูนย์ดำรงธรรม',
                        'url' => '#damrongtham',
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'ระบบตรวจสอบรหัสสถานะคำร้อง',
                        'url' => '#tracking',
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'คลังดาวน์โหลดแบบฟอร์มประชาชน',
                        'url' => '#forms',
                        'target' => '_self'
                    ]
                ]
            ],
            [
                'id' => 'menu_' . uniqid(),
                'title' => 'ข่าวประชาสัมพันธ์',
                'url' => '#news',
                'icon' => 'fa-solid fa-bullhorn',
                'target' => '_self',
                'children' => [
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'ข่าวประกาศทั่วไปจากศาลากลาง',
                        'url' => '#news-general',
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'ประกาศจัดซื้อจัดจ้างภาครัฐ (e-Bidding)',
                        'url' => '#news-procurement',
                        'target' => '_self'
                    ],
                    [
                        'id' => 'sub_' . uniqid(),
                        'title' => 'ท่องเที่ยวและวัฒนธรรมเมืองหนังโนราห์',
                        'url' => '#news-tourism',
                        'target' => '_self'
                    ]
                ]
            ],
            [
                'id' => 'menu_' . uniqid(),
                'title' => 'ติดต่อศาลากลาง',
                'url' => '#contact',
                'icon' => 'fa-solid fa-phone-volume',
                'target' => '_self',
                'children' => []
            ]
        ];
    }

    /**
     * โหลดเมนูปัจจุบัน
     */
    private function getMenus(): array
    {
        $storagePath = $this->getStoragePath();
        if (is_file($storagePath)) {
            $data = json_decode(file_get_contents($storagePath), true);
            if (is_array($data)) {
                return $data;
            }
        }
        return $this->getDefaultMenus();
    }

    /**
     * หน้าต่างผู้จัดการเมนูในแผงควบคุมแอดมิน
     */
    public function index()
    {
        $data = [
            'title'       => 'จัดการเมนูและเมนูย่อย Dropdown | Phatthalung Admin',
            'activeMenu'  => 'menu_manager',
            'currentMenu' => $this->getMenus()
        ];

        return view('admin/menu_manager', $data);
    }

    /**
     * บันทึกโครงสร้างเมนูแบบ Async AJAX No-Reload
     */
    public function save()
    {
        $menuDataJson = $this->request->getPost('menu_data');
        if (empty($menuDataJson)) {
            // ป้องกันการรับค่าแบบ raw body JSON
            $body = $this->request->getBody();
            if (!empty($body)) {
                $decoded = json_decode($body, true);
                if (isset($decoded['menu_data'])) {
                    $menuDataJson = is_string($decoded['menu_data']) ? $decoded['menu_data'] : json_encode($decoded['menu_data']);
                }
            }
        }

        $menuArray = json_decode($menuDataJson, true);
        if (!is_array($menuArray)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'รูปแบบข้อมูลเมนูไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง'
            ], 400);
        }

        // บันทึกไฟล์ลง persistent JSON storage
        $storagePath = $this->getStoragePath();
        file_put_contents($storagePath, json_encode($menuArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $this->respond([
            'status'  => 'success',
            'message' => '🎉 บันทึกการจัดการเมนูหลักและเมนูย่อยเรียบร้อยแล้ว! ผลลัพธ์แสดงบนหน้าเว็บทันทีครับ'
        ], 200);
    }

    /**
     * รีเซ็ตเมนูกลับคืนสู่มาตรฐานราชการเริ่มต้น
     */
    public function reset()
    {
        $storagePath = $this->getStoragePath();
        $defaults = $this->getDefaultMenus();
        file_put_contents($storagePath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $this->respond([
            'status'  => 'success',
            'message' => '🔄 รีเซ็ตโครงสร้างเมนูบาร์สู่มาตรฐานเริ่มต้นเรียบร้อยแล้ว',
            'menu_data' => $defaults
        ], 200);
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class DigitalServiceManager extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        helper('settings');
    }

    public function index()
    {
        $data = [
            'title'      => 'จัดการระบบสารสนเทศและบริการดิจิทัล | Phatthalung Admin',
            'activeMenu' => 'digital_services',
            'services'   => get_digital_services(null, false)
        ];

        return view('admin/digital_services', $data);
    }

    public function saveInline()
    {
        $id = trim((string)$this->request->getPost('id'));
        if (empty($id)) {
            $id = 'srv_' . time() . '_' . rand(100, 999);
        }

        $title = trim((string)$this->request->getPost('title'));
        if (empty($title)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'กรุณาระบุชื่อระบบสารสนเทศ'
            ], 400);
        }

        $services = get_digital_services(null, false);
        $category = trim((string)$this->request->getPost('category')) ?: 'general';
        $catLabels = [
            'citizen' => 'บริการประชาชน',
            'officer' => 'สำหรับเจ้าหน้าที่',
            'general' => 'บริการทั่วไป / องค์กร'
        ];

        $item = [
            'id'             => $id,
            'title'          => $title,
            'subtitle'       => trim((string)$this->request->getPost('subtitle')),
            'category'       => $category,
            'category_label' => $catLabels[$category] ?? 'บริการทั่วไป',
            'url'            => trim((string)$this->request->getPost('url')) ?: '#',
            'target'         => trim((string)$this->request->getPost('target')) ?: '_blank',
            'icon'           => trim((string)$this->request->getPost('icon')) ?: 'fa-solid fa-laptop',
            'theme_color'    => trim((string)$this->request->getPost('theme_color')) ?: '#059669',
            'badge'          => trim((string)$this->request->getPost('badge')) ?: ($category === 'officer' ? 'สำหรับเจ้าหน้าที่' : 'บริการประชาชน'),
            'status'         => trim((string)$this->request->getPost('status')) ?: 'active',
            'sort_order'     => (int)($this->request->getPost('sort_order') ?: (count($services) + 1))
        ];

        $found = false;
        foreach ($services as $index => $exist) {
            if (strval($exist['id']) === strval($id)) {
                $services[$index] = $item;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $services[] = $item;
        }

        // Re-sort by sort_order
        usort($services, static function($a, $b) {
            return ($a['sort_order'] ?? 99) - ($b['sort_order'] ?? 99);
        });

        save_digital_services($services);

        return $this->respond([
            'status'  => 'success',
            'id'      => $id,
            'item'    => $item,
            'message' => '🎉 บันทึกข้อมูลระบบสารสนเทศ "' . $item['title'] . '" เรียบร้อยแล้ว!'
        ], 200);
    }

    public function toggleStatus($id)
    {
        $services = get_digital_services(null, false);
        $found = false;
        $newStatus = 'active';

        foreach ($services as $index => $exist) {
            if (strval($exist['id']) === strval($id)) {
                $currentStatus = $exist['status'] ?? 'active';
                $newStatus = ($currentStatus === 'active') ? 'development' : 'active';
                $services[$index]['status'] = $newStatus;
                $found = true;
                break;
            }
        }

        if ($found) {
            save_digital_services($services);
            $statusLabel = ($newStatus === 'active') ? '🟢 พร้อมใช้งาน' : '🟡 อยู่ระหว่างการพัฒนา';
            return $this->respond([
                'status'    => 'success',
                'newStatus' => $newStatus,
                'message'   => "เปลี่ยนสถานะเป็น {$statusLabel} เรียบร้อยแล้ว!"
            ], 200);
        }

        return $this->respond([
            'status'  => 'error',
            'message' => 'ไม่พบรหัสระบบที่ต้องการเปลี่ยนสถานะ'
        ], 404);
    }

    public function delete($id)
    {
        $services = get_digital_services(null, false);
        $filtered = [];
        $deletedTitle = '';

        foreach ($services as $exist) {
            if (strval($exist['id']) === strval($id)) {
                $deletedTitle = $exist['title'] ?? '';
                continue;
            }
            $filtered[] = $exist;
        }

        if (!empty($deletedTitle)) {
            save_digital_services($filtered);
            return $this->respond([
                'status'  => 'success',
                'message' => '🗑️ ลบระบบสารสนเทศ "' . $deletedTitle . '" เรียบร้อยแล้ว!'
            ], 200);
        }

        return $this->respond([
            'status'  => 'error',
            'message' => 'ไม่พบข้อมูลระบบที่ต้องการลบ'
        ], 404);
    }

    public function reset()
    {
        $defaults = [
            [
                'id'             => 'eoffice',
                'title'          => 'e-Office จังหวัดพัทลุง',
                'subtitle'       => 'ระบบงานสารบรรณและสำนักงานอิเล็กทรอนิกส์',
                'category'       => 'officer',
                'category_label' => 'สำหรับเจ้าหน้าที่',
                'url'            => 'https://eoffice.phatthalung.go.th/',
                'target'         => '_blank',
                'icon'           => 'fa-solid fa-file-signature',
                'theme_color'    => '#2563eb',
                'badge'          => 'งานสารบรรณ',
                'status'         => 'active',
                'sort_order'     => 1
            ],
            [
                'id'             => 'crimemap',
                'title'          => 'CRIME MAP 90 วัน',
                'subtitle'       => 'ระบบแผนที่อาชญากรรมและความปลอดภัยเมืองพัทลุง',
                'category'       => 'citizen',
                'category_label' => 'บริการประชาชน',
                'url'            => 'https://www.phatthalung.go.th/90day/',
                'target'         => '_blank',
                'icon'           => 'fa-solid fa-shield-halved',
                'theme_color'    => '#059669',
                'badge'          => 'เมืองปลอดภัย 24 ชม.',
                'status'         => 'active',
                'sort_order'     => 2
            ],
            [
                'id'             => 'phonedir',
                'title'          => 'ทำเนียบหัวหน้าส่วนราชการ',
                'subtitle'       => 'ระบบค้นหาหมายเลขโทรศัพท์และทำเนียบผู้บริหารหน่วยงาน',
                'category'       => 'general',
                'category_label' => 'บริการทั่วไป / องค์กร',
                'url'            => 'https://www.phatthalung.go.th/2026/directory',
                'target'         => '_self',
                'icon'           => 'fa-solid fa-address-book',
                'theme_color'    => '#d97706',
                'badge'          => 'สมุดโทรศัพท์ออนไลน์',
                'status'         => 'active',
                'sort_order'     => 3
            ],
            [
                'id'             => 'emenscr',
                'title'          => 'ระบบ eMENSCR',
                'subtitle'       => 'ระบบติดตามและประเมินผลแห่งชาติตามยุทธศาสตร์ชาติ (สศช.)',
                'category'       => 'officer',
                'category_label' => 'สำหรับเจ้าหน้าที่',
                'url'            => 'https://emenscr.nesdc.go.th/',
                'target'         => '_blank',
                'icon'           => 'fa-solid fa-chart-line',
                'theme_color'    => '#7c3aed',
                'badge'          => 'ยุทธศาสตร์ชาติ',
                'status'         => 'active',
                'sort_order'     => 4
            ],
            [
                'id'             => 'damrongtham',
                'title'          => 'ระบบรับเรื่องศูนย์ดำรงธรรม',
                'subtitle'       => 'ระบบยื่นและติดตามสถานะเรื่องร้องเรียนร้องทุกข์ออนไลน์',
                'category'       => 'citizen',
                'category_label' => 'บริการประชาชน',
                'url'            => '#',
                'target'         => '_self',
                'icon'           => 'fa-solid fa-scale-balanced',
                'theme_color'    => '#ea580c',
                'badge'          => 'กำลังพัฒนา',
                'status'         => 'development',
                'sort_order'     => 5
            ],
            [
                'id'             => 'computer_procurement',
                'title'          => 'ระบบการจัดหาคอมพิวเตอร์',
                'subtitle'       => 'ระบบยื่นคำขอและพิจารณาความเหมาะสมอุปกรณ์ดิจิทัลภาครัฐ',
                'category'       => 'officer',
                'category_label' => 'สำหรับเจ้าหน้าที่',
                'url'            => '#',
                'target'         => '_self',
                'icon'           => 'fa-solid fa-desktop',
                'theme_color'    => '#0891b2',
                'badge'          => 'กำลังพัฒนา',
                'status'         => 'development',
                'sort_order'     => 6
            ]
        ];

        save_digital_services($defaults);

        return $this->respond([
            'status'  => 'success',
            'message' => 'รีเซ็ตรายการระบบสารสนเทศกลับเป็นค่ามาตรฐานเริ่มต้นเรียบร้อยแล้ว!'
        ], 200);
    }
}

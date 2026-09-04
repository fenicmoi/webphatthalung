<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class GovernorPolicyManager extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url', 'form']);
    }

    private function checkOfficerAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
        }
        return null;
    }

    /**
     * หน้าจัดการนโยบายผู้ว่าราชการจังหวัด (Admin Governor Policy Studio)
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $executives = get_site_executives(null, null, false);
        $governor = null;
        if (!empty($executives)) {
            foreach ($executives as $ex) {
                if (!empty($ex['featured']) || strpos(($ex['position'] ?? ''), 'ผู้ว่าราชการ') !== false) {
                    $governor = $ex;
                    break;
                }
            }
            if (!$governor) $governor = $executives[0];
        }

        $siteTexts = function_exists('get_site_texts') ? get_site_texts() : [];
        $siteSettings = function_exists('get_site_settings') ? get_site_settings() : [];

        $data = [
            'title'        => 'ระบบจัดการนโยบายผู้ว่าราชการจังหวัด | Admin Portal',
            'activeMenu'   => 'governor_policy',
            'governor'     => $governor,
            'siteTexts'    => $siteTexts,
            'siteSettings' => $siteSettings,
            'isOfficer'    => true
        ];

        return view('admin/governor_policy_manager', $data);
    }

    /**
     * บันทึกข้อมูลนโยบายผู้ว่าฯ และอัปเดตไฟล์รูปภาพ
     */
    public function save(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $name = trim((string)$this->request->getPost('gov_name'));
        $position = trim((string)$this->request->getPost('gov_position'));
        $quote = trim((string)$this->request->getPost('gov_quote'));
        $policyDetails = trim((string)$this->request->getPost('gov_policy_details'));
        $visionTicker = trim((string)$this->request->getPost('provincial_vision_ticker'));
        $externalPhoto = trim((string)$this->request->getPost('gov_photo_url'));

        if (empty($name) || empty($position)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกชื่อและตำแหน่งของผู้ว่าราชการจังหวัด']);
        }

        // 1. จัดการรูปถ่ายผู้ว่าราชการจังหวัด (อัปโหลดใหม่ หรือใช้ URL เดิม)
        $finalPhoto = $externalPhoto;
        $photoFile = $this->request->getFile('gov_photo_file');
        
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/executives';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            $newName = 'gov_' . time() . '_' . $photoFile->getRandomName();
            if ($photoFile->move($uploadPath, $newName)) {
                $finalPhoto = 'uploads/executives/' . $newName;
            }
        }

        // 2. อัปเดตข้อมูลใน site_executives.json (สำหรับผู้ว่าราชการจังหวัด)
        $execs = get_site_executives(null, null, false);
        $govFound = false;
        
        foreach ($execs as &$item) {
            if (!empty($item['featured']) || strpos(($item['position'] ?? ''), 'ผู้ว่าราชการ') !== false || ($item['id'] ?? '') === 'exec-1') {
                $item['name'] = $name;
                $item['position'] = $position;
                if (!empty($quote)) {
                    $item['quote'] = $quote;
                }
                if (!empty($finalPhoto)) {
                    $item['photo'] = $finalPhoto;
                }
                if (!empty($policyDetails)) {
                    $item['history'] = $policyDetails;
                }
                $govFound = true;
                break;
            }
        }
        unset($item);

        if (!$govFound) {
            $execs[] = [
                'id'       => 'exec-1',
                'name'     => $name,
                'position' => $position,
                'category' => 'คณะผู้บริหารระดับสูง',
                'quote'    => $quote,
                'photo'    => $finalPhoto ?: 'uploads/executives/exec_1787543315_1787543315_5570c503c25f1ee9f002.jpg',
                'featured' => true,
                'active'   => true,
                'order_num'=> 1,
                'history'  => $policyDetails
            ];
        }

        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        @file_put_contents($writableDir . '/site_executives.json', json_encode($execs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // 3. อัปเดตข้อความแถบประกาศวิสัยทัศน์ใน site_texts.json
        helper('settings');
        if (function_exists('save_site_text')) {
            if (!empty($visionTicker)) {
                \save_site_text('provincial_vision_ticker', $visionTicker, 'วิสัยทัศน์บนแถบประกาศหน้าหลัก');
            }
            if (!empty($quote)) {
                \save_site_text('governor_policy_quote', $quote, 'นโยบายและวิสัยทัศน์ผู้ว่าราชการจังหวัด');
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'บันทึกนโยบายและข้อมูลผู้ว่าราชการจังหวัดเรียบร้อยแล้ว',
            'data'    => [
                'name'      => $name,
                'position'  => $position,
                'quote'     => $quote,
                'photo_url' => !empty($finalPhoto) ? (strpos($finalPhoto, 'http') === 0 ? $finalPhoto : base_url($finalPhoto)) : ''
            ]
        ]);
    }
}

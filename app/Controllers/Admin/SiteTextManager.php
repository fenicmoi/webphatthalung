<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class SiteTextManager extends BaseController
{
    use ResponseTrait;

    protected function getCategories()
    {
        return [
            'hero' => [
                'name' => 'แบนเนอร์และหัวเว็บ (Hero & Header)',
                'icon' => 'fa-solid fa-images text-primary',
                'keys' => [
                    'hero_badge_default'    => ['label' => 'ข้อความ Badge ด้านบนแบนเนอร์ (ค่าเริ่มต้น)', 'type' => 'text'],
                    'hero_weather_title'    => ['label' => 'หัวข้อจุดชมวิวรายงานอากาศ (Weather Node)', 'type' => 'text'],
                    'hero_weather_desc'     => ['label' => 'รายละเอียดสภาพอากาศ (Weather Desc)', 'type' => 'text'],
                    'hero_landmark_caption' => ['label' => 'คำบรรยายจุดถ่ายภาพ (Landmark Caption)', 'type' => 'text'],
                    'site_slogan'           => ['label' => 'คำขวัญประจำจังหวัด (Province Slogan)', 'type' => 'textarea'],
                ]
            ],
            'search' => [
                'name' => 'ระบบค้นหาอัจฉริยะ (Smart Search Dock)',
                'icon' => 'fa-solid fa-wand-magic-sparkles text-warning',
                'keys' => [
                    'search_dock_title'        => ['label' => 'หัวข้อแท่นค้นหา (Search Deck Title)', 'type' => 'text'],
                    'search_dock_subtitle'     => ['label' => 'คำโปรยแท่นค้นหา (Search Deck Subtitle)', 'type' => 'text'],
                    'search_input_placeholder' => ['label' => 'ข้อความในกล่องค้นหา (Search Placeholder)', 'type' => 'text'],
                    'search_trending_label'    => ['label' => 'ป้ายหัวข้อคำค้นหายอดนิยม (Trending Label)', 'type' => 'text'],
                ]
            ],
            'news' => [
                'name' => 'ข่าวสารและประชาสัมพันธ์ (News Hub)',
                'icon' => 'fa-solid fa-newspaper text-info',
                'keys' => [
                    'news_section_title'    => ['label' => 'หัวข้อส่วนข่าวสาร (News Section Title)', 'type' => 'text'],
                    'news_section_subtitle' => ['label' => 'คำโปรยส่วนข่าวสาร (News Section Subtitle)', 'type' => 'text'],
                    'news_view_all_btn'     => ['label' => 'ข้อความปุ่มดูข่าวทั้งหมด (View All News Button)', 'type' => 'text'],
                    'news_tab_all'          => ['label' => 'ชื่อแท็บ: ข่าวทั้งหมด', 'type' => 'text'],
                    'news_tab_pr'           => ['label' => 'ชื่อแท็บ: ข่าวประชาสัมพันธ์', 'type' => 'text'],
                    'news_tab_procure'      => ['label' => 'ชื่อแท็บ: ประกาศจัดซื้อจัดจ้าง', 'type' => 'text'],
                    'news_tab_activity'     => ['label' => 'ชื่อแท็บ: ข่าวกิจกรรมจังหวัด', 'type' => 'text'],
                    'news_tab_jobs'         => ['label' => 'ชื่อแท็บ: รับสมัครงานราชการ', 'type' => 'text'],
                ]
            ],
            'calendar' => [
                'name' => 'ปฏิทินกิจกรรม (Event Calendar)',
                'icon' => 'fa-solid fa-calendar-check text-success',
                'keys' => [
                    'calendar_section_title'    => ['label' => 'หัวข้อปฏิทินกิจกรรม (Calendar Title)', 'type' => 'text'],
                    'calendar_section_subtitle' => ['label' => 'คำโปรยปฏิทินกิจกรรม (Calendar Subtitle)', 'type' => 'text'],
                    'calendar_listen_voice_btn' => ['label' => 'ข้อความปุ่มฟังเสียงกำหนดการ (TTS Voice Button)', 'type' => 'text'],
                ]
            ],
            'executives' => [
                'name' => 'ทำเนียบผู้ว่าฯ และผู้บริหาร (Governors & Executives)',
                'icon' => 'fa-solid fa-user-tie text-warning',
                'keys' => [
                    'governor_section_title'   => ['label' => 'หัวข้อสารจากผู้ว่าราชการจังหวัด (Governor Section Title)', 'type' => 'text'],
                    'governor_quote_text'      => ['label' => 'ข้อความสาร/คำคมจากผู้ว่าฯ (Governor Speech/Quote)', 'type' => 'textarea'],
                    'executive_section_title'  => 'หัวข้อคณะผู้บริหารจังหวัด (Executive Section Title)',
                    'executive_section_desc'   => 'คำอธิบายคณะผู้บริหาร (Executive Section Desc)',
                ]
            ],
            'strategy_projects' => [
                'name' => 'ยุทธศาสตร์และโครงการ GIS (Strategy & GIS Projects)',
                'icon' => 'fa-solid fa-bullseye text-danger',
                'keys' => [
                    'strategy_section_title' => ['label' => 'หัวข้อยุทธศาสตร์และแผนพัฒนา (Strategy Section Title)', 'type' => 'text'],
                    'strategy_section_desc'  => ['label' => 'คำโปรยยุทธศาสตร์และแผนพัฒนา (Strategy Section Desc)', 'type' => 'textarea'],
                    'projects_section_title' => ['label' => 'หัวข้อระบบติดตามโครงการ GIS (Projects Section Title)', 'type' => 'text'],
                    'projects_section_desc'  => ['label' => 'คำโปรยระบบติดตามโครงการ GIS (Projects Section Desc)', 'type' => 'textarea'],
                ]
            ],
            'services' => [
                'name' => 'บริการประชาชนและ e-Services (Public Services)',
                'icon' => 'fa-solid fa-hand-pointer text-primary',
                'keys' => [
                    'services_section_title' => ['label' => 'หัวข้อบริการประชาชน (Services Section Title)', 'type' => 'text'],
                    'services_section_desc'  => ['label' => 'คำโปรยบริการประชาชน (Services Section Desc)', 'type' => 'textarea'],
                ]
            ],
            'nora' => [
                'name' => 'ผู้ช่วยอัจฉริยะ น้องโนรา AI (Nora AI Assistant)',
                'icon' => 'fa-solid fa-crown text-warning',
                'keys' => [
                    'nora_bot_name' => ['label' => 'ชื่อระบบผู้ช่วย AI (Bot Name)', 'type' => 'text'],
                    'nora_tagline'  => ['label' => 'สโลแกนประจำตัวบอท (Bot Tagline)', 'type' => 'text'],
                    'nora_greeting' => ['label' => 'ข้อความทักทายแรกเริ่ม (Greeting Message)', 'type' => 'textarea'],
                ]
            ],
            'footer' => [
                'name' => 'ส่วนท้ายเว็บและการติดต่อ (Footer & Contact)',
                'icon' => 'fa-solid fa-address-book text-secondary',
                'keys' => [
                    'footer_address'   => ['label' => 'ที่อยู่หน่วยงาน (Official Address)', 'type' => 'textarea'],
                    'footer_phone'     => ['label' => 'เบอร์โทรศัพท์ติดต่อ (Phone Number)', 'type' => 'text'],
                    'footer_email'     => ['label' => 'อีเมลติดต่อราชการ (Official Email)', 'type' => 'text'],
                    'footer_copyright' => ['label' => 'ข้อความลิขสิทธิ์ (Copyright Notice)', 'type' => 'text'],
                ]
            ]
        ];
    }

    public function index()
    {
        helper('settings');

        $allTexts = get_site_texts();
        $categories = $this->getCategories();

        $data = [
            'title'       => 'ระบบแก้ไขข้อความและเนื้อหาทั่วทั้งเว็บไซต์ | Phatthalung Admin',
            'activeMenu'  => 'site_texts',
            'allTexts'    => $allTexts,
            'categories'  => $categories
        ];

        return view('admin/site_text_manager', $data);
    }

    public function save()
    {
        helper('settings');

        $isJson = $this->request->getHeaderLine('Content-Type') === 'application/json' || $this->request->getPost('is_ajax_single');
        
        if ($this->request->getPost('is_ajax_single')) {
            $key = trim($this->request->getPost('text_key') ?? '');
            $val = trim($this->request->getPost('text_value') ?? '');

            if (empty($key)) {
                return $this->respond(['status' => 'error', 'message' => 'ไม่พบคีย์ข้อความ'], 400);
            }

            save_site_texts([$key => $val]);

            return $this->respond([
                'status'  => 'success',
                'message' => 'บันทึกข้อความ "' . esc($key) . '" เรียบร้อยแล้ว',
                'key'     => $key,
                'value'   => $val
            ], 200);
        }

        // Bulk form submit
        $postData = $this->request->getPost('texts');
        if (is_array($postData)) {
            save_site_texts($postData);
        }

        if ($this->request->isAJAX()) {
            return $this->respond([
                'status'  => 'success',
                'message' => '🎉 บันทึกการแก้ไขข้อความทั่วทั้งเว็บไซต์เรียบร้อยแล้ว!'
            ], 200);
        }

        return redirect()->to(base_url('admin/site-texts'))->with('success', 'บันทึกการแก้ไขข้อความเรียบร้อยแล้ว');
    }

    public function reset()
    {
        helper('settings');

        $key = $this->request->getPost('text_key');
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_texts.json';

        if (!empty($key)) {
            $current = get_site_texts();
            unset($current[$key]);
            file_put_contents($jsonPath, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return $this->respond(['status' => 'success', 'message' => 'คืนค่าข้อความ "' . esc($key) . '" สู่ค่าเริ่มต้นเรียบร้อยแล้ว']);
        }

        // Reset all
        if (is_file($jsonPath)) {
            @unlink($jsonPath);
        }

        return $this->respond(['status' => 'success', 'message' => 'คืนค่าข้อความทั้งหมดสู่ค่าเริ่มต้นเรียบร้อยแล้ว']);
    }
}

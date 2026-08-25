<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class NoraAi extends ResourceController
{
    public function __construct()
    {
        helper('settings');
    }

    /**
     * Endpoint: POST /api/nora-ai/chat
     */
    public function chat()
    {
        $message = trim((string)$this->request->getPost('message'));
        if (empty($message)) {
            $jsonBody = $this->request->getJSON();
            if ($jsonBody && !empty($jsonBody->message)) {
                $message = trim((string)$jsonBody->message);
            }
        }

        $settings = get_nora_settings();
        if (!$settings['is_enabled']) {
            return $this->respond([
                'status' => 'success',
                'reply'  => '🙏 ขออภัยค่ะ ขณะนี้ระบบน้องโนรา AI อยู่ระหว่างการบำรุงรักษาและอัปเกรดฐานข้อมูล กรุณาติดต่อสายด่วน 074-611621 หรือทบทวนบริการ e-Services บนหน้าหลักค่ะ',
                'cards'  => []
            ]);
        }

        if ($message === '' || mb_strlen($message) < 2) {
            return $this->respond([
                'status' => 'success',
                'reply'  => $settings['greeting_msg'],
                'cards'  => [
                    ['title' => '⚡ บริการออนไลน์ e-Services', 'url' => base_url('#services'), 'icon' => 'fa-bolt text-warning'],
                    ['title' => '📂 คลังเอกสารราชการดิจิทัล', 'url' => base_url('documents'), 'icon' => 'fa-folder-open text-info'],
                    ['title' => '🌿 ท่องเที่ยวพัทลุง (มาเมืองลุง)', 'url' => 'http://www.ma-muanglung.go.th', 'icon' => 'fa-compass text-success']
                ]
            ]);
        }

        $msgLower = mb_strtolower($message, 'UTF-8');
        $cards = [];
        $reply = "";

        // 1. Check greeting
        $greetings = ['สวัสดี', 'ฮัลโหล', 'hello', 'hi', 'ทักทาย', 'น้องโนรา', 'ดีค้าบ', 'สวัสดีครับ', 'สวัสดีค่ะ'];
        foreach ($greetings as $g) {
            if (mb_strpos($msgLower, $g) !== false && mb_strlen($msgLower) <= 25) {
                return $this->respond([
                    'status' => 'success',
                    'reply'  => "สวัสดีค่ะ! 🙏 น้องโนรายินดีต้อนรับสู่เว็บไซต์ทางการจังหวัดพัทลุงค่ะ วันนี้คุณมีคำถามหรืออยากให้โนราพาไปชมส่วนไหนของจังหวัด พิมพ์บอกหรือกดเลือกเมนูทางลัดด้านล่างได้เลยนะคะ 😊",
                    'cards'  => [
                        ['title' => '⚡ บริการออนไลน์ e-Services', 'url' => base_url('#services'), 'icon' => 'fa-bolt text-warning'],
                        ['title' => '🏆 ความโปร่งใส ITA & Open Data', 'url' => base_url('ita'), 'icon' => 'fa-award text-success'],
                        ['title' => '🏛️ รู้จักผู้บริหาร & ทำเนียบ', 'url' => base_url('executives'), 'icon' => 'fa-user-tie text-primary']
                    ]
                ]);
            }
        }

        // 2. Check custom Q&A knowledge base
        $knowledge = get_nora_knowledge();
        $bestMatch = null;
        $highestScore = 0;

        foreach ($knowledge as $item) {
            $keywords = explode(',', $item['keywords'] ?? '');
            $score = 0;
            foreach ($keywords as $kw) {
                $kw = trim(mb_strtolower($kw, 'UTF-8'));
                if ($kw !== '' && mb_strpos($msgLower, $kw) !== false) {
                    $score += mb_strlen($kw); // longer keywords carry higher weight
                }
            }
            if ($score > $highestScore) {
                $highestScore = $score;
                $bestMatch = $item;
            }
        }

        if ($bestMatch && $highestScore > 2) {
            if (!empty($bestMatch['link_url'])) {
                $url = $bestMatch['link_url'];
                $isAbsolute = preg_match('/^(http|https):\/\//i', $url) || str_starts_with($url, '#');
                $cards[] = [
                    'title' => !empty($bestMatch['link_title']) ? $bestMatch['link_title'] : '🔗 กดเพื่อเข้าสู่หน้ารายการนี้',
                    'url' => $isAbsolute ? $url : base_url($url),
                    'icon' => 'fa-external-link-alt text-primary'
                ];
            }
            return $this->respond([
                'status' => 'success',
                'reply'  => $bestMatch['answer'],
                'cards'  => $cards
            ]);
        }

        // 3. Executive / Governor checks
        if (preg_match('/(ผู้ว่า|รองผู้ว่า|ผู้บริหาร|ทำเนียบ|นายอำเภอ|ปลัด|นายก|หัวหน้าส่วน)/i', $msgLower)) {
            $execs = function_exists('get_site_executives') ? get_site_executives() : [];
            $govName = "นายอภิชาติ สาราบรรณ์ (ผู้ว่าราชการจังหวัดพัทลุง)";
            foreach ($execs as $e) {
                if (mb_strpos($e['position'] ?? '', 'ผู้ว่าราชการ') !== false && !empty($e['name'])) {
                    $govName = $e['name'] . " (" . $e['position'] . ")";
                    break;
                }
            }
            return $this->respond([
                'status' => 'success',
                'reply'  => "🏛️ **ข้อมูลคณะผู้บริหารและหัวหน้าส่วนราชการ**\nปัจจุบันผู้นำระดับสูงสุดของเราคือ **{$govName}** ซึ่งยึดหลักการบริหารงานอย่างมีคุณธรรม โปรงใส มุ่งเน้นความเจริญก้าวหน้าแก่พี่น้องเมืองลุงค่ะ\n\nสามารถตรวจสอบรายชื่อและประวัติคณะผู้บริหารทั้งหมดได้ในทำเนียบผู้บริหารนะคะ",
                'cards'  => [
                    ['title' => '👔 เปิดดูทำเนียบผู้บริหารและสายตรวจวิสัยทัศน์', 'url' => base_url('executives'), 'icon' => 'fa-users text-primary']
                ]
            ]);
        }

        // 4. ITA & Procurement & Transparency checks
        if (preg_match('/(ita|oit|ปปช|ความโปร่งใส|จัดซื้อจัดจ้าง|ราคากลาง|e-gp|open data|ข้อมูลสาธารณะ|ชุดข้อมูล|งบประมาณ)/i', $msgLower)) {
            $scorecard = function_exists('get_ita_scorecard') ? get_ita_scorecard() : ['overall_score' => '96.48', 'grade' => 'A+'];
            $sc = $scorecard['overall_score'] ?? '96.48';
            $gr = $scorecard['grade'] ?? 'A+';
            return $this->respond([
                'status' => 'success',
                'reply'  => "🏆 **ศูนย์ประเมินคุณธรรม ความโปร่งใส และการจัดซื้อจัดจ้าง**\nจังหวัดพัทลุงได้รับผลการประเมิน ITA ประจำปีงบประมาณด้วยคะแนน **{$sc} (ระดับ Grade {$gr})** จาก ป.ป.ช. โดยมีการเปิดเผยข้อมูลตามตัวชี้วัด OIT ครบถ้วน รวมถึงมีประกาศคณะจัดซื้อจัดจ้าง e-GP ให้ประมวลผลได้อย่างเปิดเผยค่ะ",
                'cards'  => [
                    ['title' => '🏆 ศูนย์ความโปร่งใส ITA & Open Data', 'url' => base_url('ita'), 'icon' => 'fa-award text-warning'],
                    ['title' => '📢 ประกาศจัดซื้อจัดจ้าง & ราคากลาง e-GP', 'url' => base_url('procurement'), 'icon' => 'fa-gavel text-danger']
                ]
            ]);
        }

        // 5. Tourism checks
        if (preg_match('/(เที่ยว|ท่องเที่ยว|มาเมืองลุง|ที่พัก|โรงแรม|คาเฟ่|ร้านอาหาร|มโนราห์|โนรา|ทะเลน้อย|เขาอกทะลุ|น้ำพุร้อน|พัทลุงมีอะไร)/i', $msgLower)) {
            return $this->respond([
                'status' => 'success',
                'reply'  => "🌿 **ยินดีต้อนรับสู่ \"มาเมืองลุง\" (ma-muanglung.go.th)**\nพัทลุงคือเป้าหมายท่องเที่ยวเชิงธรรมชาติและมรดกวัฒนธรรมอันดับต้นๆ! ไม่ว่าจะเป็น **ล่องเรือชมทะเลน้อยและนกน้ำ**, **พิชิตเขาอกทะลุ**, ชิมอาหารใต้ดั้งเดิมรสจัดจ้าน หรือชมงานแสดงศิลปะโนราอันเลื่องชื่อ\n\nคุณสามารถจองที่พัก เช็คพิกัดคาเฟ่ และสถานที่สวยๆ ได้ที่เว็บไซต์ท่องเที่ยวโดยตรงค่ะ!",
                'cards'  => [
                    ['title' => '🌿 เปิดเว็บไซต์ท่องเที่ยว "มาเมืองลุง" (www.ma-muanglung.go.th)', 'url' => 'http://www.ma-muanglung.go.th', 'icon' => 'fa-compass text-success']
                ]
            ]);
        }

        // 6. Complaints & Services
        if (preg_match('/(ร้องเรียน|ร้องทุกข์|ศูนย์ดำรงธรรม|ทุจริต|แจ้งเบาะแส|ความเสียหาย|เดือดร้อน)/i', $msgLower)) {
            return $this->respond([
                'status' => 'success',
                'reply'  => "📢 **ศูนย์ดำรงธรรมและรับเรื่องร้องเรียนร้องทุกข์ออนไลน์**\nหากพบความไม่ชอบธรรม เดือดร้อน หรือประสงค์แจ้งเบาะแสการทุจริต คุณสามารถส่งข้อมูลผ่านระบบรับเรื่องร้องเรียนออนไลน์ที่มีมาตรฐานความปลอดภัยและการปกปิดข้อมูลผู้ร้องถึงระดับสูงสุดค่ะ หรือโทรสายด่วน 1567",
                'cards'  => [
                    ['title' => '📢 เปิดระบบแจ้งเรื่องร้องเรียน/ร้องทุกข์ ออนไลน์', 'url' => base_url('citizen/complaints'), 'icon' => 'fa-shield-halved text-danger'],
                    ['title' => '⚡ ศูนย์บริการ e-Services ภาครัฐ', 'url' => base_url('#services'), 'icon' => 'fa-bolt text-warning']
                ]
            ]);
        }

        // 7. Google Gemini Live RAG Reasoning (If enabled with API Key)
        if (!empty($settings['use_gemini_live']) && !empty($settings['gemini_api_key'])) {
            $searchContext = \App\Libraries\SmartSearchService::search($message, 4);
            $geminiText = \App\Libraries\GeminiService::generateLiveReply($message, $searchContext);
            if (!empty($geminiText)) {
                $cards = [];
                foreach ($searchContext as $sc) {
                    $cards[] = [
                        'title' => "👉 [{$sc['badge']}] " . mb_substr($sc['title'], 0, 45) . (mb_strlen($sc['title']) > 45 ? '...' : ''),
                        'url'   => $sc['url'],
                        'icon'  => $sc['icon']
                    ];
                }
                return $this->respond([
                    'status' => 'success',
                    'reply'  => $geminiText,
                    'cards'  => $cards
                ]);
            }
        }

        // 8. Dynamic Smart Omni-Search Integration (Live Retrieval & Synthesis Engine)
        $smartReply = \App\Libraries\SmartSearchService::generateChatReply($message);
        if ($smartReply !== null) {
            return $this->respond($smartReply);
        }

        // 8. Fallback Default Reply
        return $this->respond([
            'status' => 'success',
            'reply'  => $settings['fallback_msg'] ?? "น้องโนรายังไม่มั่นใจในคำถามนี้ค่ะ คุณสามารถเลือกบริการหลักๆ จากเมนูทางลัดด้านล่าง หรือโทรสอบถามสำนักงานจังหวัดพัทลุง โทร. 074-611621 ได้ในวันและเวลาราชการนะคะ ❤️",
            'cards'  => [
                ['title' => '⚡ บริการออนไลน์ประชาชน (e-Services)', 'url' => base_url('#services'), 'icon' => 'fa-bolt text-warning'],
                ['title' => '📂 คลังเอกสารราชการทั้งหมด', 'url' => base_url('documents'), 'icon' => 'fa-folder-open text-info'],
                ['title' => '🌿 พัฒนาการท่องเที่ยว (มาเมืองลุง)', 'url' => 'http://www.ma-muanglung.go.th', 'icon' => 'fa-compass text-success'],
                ['title' => '🏆 ศูนย์ความโปร่งใส ITA & Open Data', 'url' => base_url('ita'), 'icon' => 'fa-award text-primary']
            ]
        ]);
    }

    /**
     * Endpoint: GET /api/nora-ai/settings
     */
    public function getSettings()
    {
        return $this->respond(get_nora_settings());
    }
}

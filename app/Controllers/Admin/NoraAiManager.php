<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class NoraAiManager extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $knowledge = get_nora_knowledge();
        $settings  = get_nora_settings();

        $data = [
            'title'       => 'จัดการระบบน้องโนรา AI & Smart Search Assistant | Phatthalung Admin',
            'activeMenu'  => 'nora_ai',
            'knowledge'   => $knowledge,
            'settings'    => $settings,
            'qaCount'     => count($knowledge),
            'recentNews'  => function_exists('get_site_news') ? count(get_site_news(100)) : 0,
            'recentDocs'  => function_exists('get_site_documents') ? count(get_site_documents()) : 0,
            'recentProcs' => function_exists('get_site_procurements') ? count(get_site_procurements()) : 0
        ];

        return view('admin/nora_ai_manager', $data);
    }

    private function checkAuth()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }
        return null;
    }

    public function getKnowledgeList()
    {
        if ($auth = $this->checkAuth()) return $auth;
        $items = get_nora_knowledge();
        $settings = get_nora_settings();
        return $this->response->setJSON(['status' => 'success', 'items' => $items, 'settings' => $settings]);
    }

    public function saveQaItem()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $id = trim((string)$this->request->getPost('id'));
        $keywords = trim((string)$this->request->getPost('keywords'));
        $question = trim((string)$this->request->getPost('question'));
        $answer = trim((string)$this->request->getPost('answer'));
        $link_url = trim((string)$this->request->getPost('link_url'));
        $link_title = trim((string)$this->request->getPost('link_title'));

        if (empty($keywords) || empty($question) || empty($answer)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุคำสำคัญ (Keywords) คำถาม และคำตอบให้ครบถ้วน']);
        }

        $items = get_nora_knowledge();

        if (empty($id)) {
            $newItem = [
                'id' => 'nora-qa-' . time() . '-' . mt_rand(10, 99),
                'keywords' => $keywords,
                'question' => $question,
                'answer' => $answer,
                'link_url' => $link_url,
                'link_title' => $link_title
            ];
            array_unshift($items, $newItem);
        } else {
            $found = false;
            foreach ($items as &$item) {
                if (((string)$item['id']) === ((string)$id)) {
                    $item['keywords'] = $keywords;
                    $item['question'] = $question;
                    $item['answer'] = $answer;
                    $item['link_url'] = $link_url;
                    $item['link_title'] = $link_title;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบ ID รายการ Q&A นี้']);
            }
        }

        if (save_nora_knowledge($items)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกข้อมูล Q&A ของน้องโนรา AI สำเร็จแล้ว']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกคลังความรู้ได้']);
    }

    public function deleteQaItem($id)
    {
        if ($auth = $this->checkAuth()) return $auth;

        $items = get_nora_knowledge();
        $filtered = [];
        $found = false;
        foreach ($items as $item) {
            if (((string)$item['id']) === ((string)$id)) {
                $found = true;
                continue;
            }
            $filtered[] = $item;
        }

        if (!$found) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล Q&A ที่ต้องการลบ']);
        }

        save_nora_knowledge($filtered);
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบข้อมูลคำถาม-คำตอบเรียบร้อยแล้ว']);
    }

    public function syncKnowledge()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $items = get_nora_knowledge();

        $defaultSeeds = [
            [
                'id' => 'seed-motto',
                'keywords' => 'คำขวัญ, คำขวัญจังหวัด, ประวัติ, พัทลุงคืออะไร, มรดกวัฒนธรรม, โนรา',
                'question' => 'คำขวัญประจำจังหวัดพัทลุงและประวัติความเป็นมาคืออะไร?',
                'answer' => '✨ **คำขวัญจังหวัดพัทลุง**: "เมืองหนังโนรา อู่นาข้าว พราวน้ำตก แหล่งนกน้ำ ถ้ำเย็นตา ภูเขาอกทะลุ น้ำพุร้อน"\n\nศิลปะการแสดง "โนรา" ได้รับการขึ้นทะเบียนจาก UNESCO ให้เป็นมรดกทางวัฒนธรรมที่จับต้องไม่ได้ของมนุษยชาติค่ะ 🎭👑',
                'link_url' => 'http://www.ma-muanglung.go.th',
                'link_title' => '🌿 เปิดเว็บไซต์ท่องเที่ยวและวัฒนธรรมพัทลุง'
            ],
            [
                'id' => 'seed-contact',
                'keywords' => 'เบอร์โทร, เบอร์ติดต่อ, ศาลากลาง, โทรศัพท์, ติดต่อจังหวัด, ศูนย์ดำรงธรรม, สายด่วน, ที่อยู่',
                'question' => 'ติดต่อศาลากลางจังหวัดพัทลุงและหน่วยงานราชการได้อย่างไร?',
                'answer' => "🏛️ **ศาลากลางจังหวัดพัทลุง**\n📍 ตั้งอยู่ที่ ถนนราเมศวร์ ตำบลคูหาสวรรค์ อำเภอเมืองพัทลุง 93000\n📞 **โทรศัพท์กลาง**: 074-611621\n☎️ **ศูนย์ดำรงธรรม**: สายด่วน 1567\n⏰ เปิดทำการวันจันทร์ - ศุกร์ เวลา 08:30 - 16:30 น.",
                'link_url' => 'citizen/complaints',
                'link_title' => '📢 ร้องเรียนศูนย์ดำรงธรรมออนไลน์'
            ],
            [
                'id' => 'seed-tax',
                'keywords' => 'ภาษี, ภาษีที่ดิน, ภาษีป้าย, ชำระภาษี, ค่าธรรมเนียม, e-services',
                'question' => 'ต้องการชำระหรือยื่นแบบภาษีท้องถิ่นและภาษีป้าย ทำอย่างไร?',
                'answer' => '💼 ประชาชนสามารถยื่นแบบภาษีป้าย ภาษีที่ดินและสิ่งปลูกสร้าง หรือดาวน์โหลดแบบฟอร์มเพื่อเตรียมเอกสารผ่านศูนย์บริการดิจิทัล (e-Services) ได้ตลอด 24 ชม. ค่ะ!',
                'link_url' => '#services',
                'link_title' => '⚡ เข้าสู่ระบบ e-Services'
            ],
            [
                'id' => 'seed-permission',
                'keywords' => 'ก่อสร้าง, ถมดิน, เลขที่บ้าน, e-permission, โครงสร้าง, ขออนุญาต',
                'question' => 'ขั้นตอนการยื่นขอกำหนดเลขที่บ้านและอนุญาตก่อสร้าง (e-Permission)',
                'answer' => '🏗️ ระบบ e-Permission ของจังหวัดพัทลุง อำนวยความสะดวกให้ประชาชนสามารถยื่นคำขออนุญาตก่อสร้างอาคาร ดัดแปลง หรือขอเลขที่บ้านใหม่ ผ่านอินเทอร์เน็ตได้ตลอด 24 ชั่วโมงค่ะ!',
                'link_url' => '#services',
                'link_title' => '🏠 ยื่นคำขอผ่านระบบ e-Permission'
            ],
            [
                'id' => 'seed-tourism',
                'keywords' => 'ท่องเที่ยว, ที่พัก, โรงแรม, คาเฟ่, ร้านอาหาร, ทะเลน้อย, ล่องแก่ง, เขาอกทะลุ, น้ำพุร้อน',
                'question' => 'แนะนำสถานที่ท่องเที่ยวสุดฮิตในจังหวัดพัทลุงให้หน่อย',
                'answer' => "🌿 แหล่งท่องเที่ยวไฮไลท์เมืองลุง:\n🌅 **ทะเลน้อย**: สวรรค์ของนกน้ำ ควายน้ำ และทุ่งบัวแดง\n🏔️ **เขาอกทะลุ**: สัญลักษณ์แห่งเมืองลุง\n🛶 **ล่องแก่งหนานมดแดง**: สนุกกับการผจญภัยสายน้ำใส\n♨️ **น้ำพุร้อนเขาชัยสน**: แช่น้ำแร่ธรรมชาติเพื่อสุขภาพ",
                'link_url' => 'http://www.ma-muanglung.go.th',
                'link_title' => '✨ ชมเว็บไซต์ท่องเที่ยวอย่างเป็นทางการ'
            ]
        ];

        $merged = $items;
        $added = 0;
        foreach ($defaultSeeds as $seed) {
            $exists = false;
            foreach ($merged as $cur) {
                if (mb_stripos($cur['question'] ?? '', mb_substr($seed['question'], 0, 15)) !== false) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $seed['id'] = 'nora-qa-' . time() . '-' . mt_rand(100, 999);
                $merged[] = $seed;
                $added++;
            }
        }

        save_nora_knowledge($merged);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => "ซิงค์และสร้างความรู้สำเร็จแล้ว (เพิ่มชุดความรู้ใหม่ {$added} รายการ)",
            'items'   => $merged
        ]);
    }

    public function saveSettings()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $current = get_nora_settings();
        $current['bot_name'] = trim((string)$this->request->getPost('bot_name')) ?: 'น้องโนรา (Nora AI)';
        $current['tagline'] = trim((string)$this->request->getPost('tagline')) ?: 'ผู้ช่วยบริการประชาชน 24 ชม.';
        $current['status_text'] = trim((string)$this->request->getPost('status_text')) ?: 'พร้อมให้บริการ 24 ชม.';
        $current['greeting_msg'] = trim((string)$this->request->getPost('greeting_msg')) ?: $current['greeting_msg'];
        $current['fallback_msg'] = trim((string)$this->request->getPost('fallback_msg')) ?: $current['fallback_msg'];
        $current['is_enabled'] = $this->request->getPost('is_enabled') !== null;
        $current['gemini_api_key'] = trim((string)$this->request->getPost('gemini_api_key'));
        $current['gemini_model'] = trim((string)$this->request->getPost('gemini_model')) ?: 'gemini-2.5-flash';
        $current['use_gemini_live'] = $this->request->getPost('use_gemini_live') !== null;

        if (save_nora_settings($current)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตการตั้งค่าระบบและ Gemini AI เรียบร้อยแล้ว']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตการตั้งค่าได้']);
    }

    public function testGeminiConnection()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $key = trim((string)$this->request->getPost('api_key'));
        if (empty($key)) {
            $key = \App\Libraries\GeminiService::getApiKey();
        }

        if (empty($key)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'กรุณากรอก API Key ก่อนทดสอบ'
            ]);
        }

        if (str_starts_with($key, 'gen-lang-client')) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'รหัสที่ระบุ (' . esc($key) . ') คือ Project ID ไม่ใช่ API Key ค่ะ (API Key ที่ถูกต้องจะขึ้นต้นด้วย "AIzaSy...")'
            ]);
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $key;
        $bodyData = [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => 'สวัสดี']]]
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($bodyData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 15,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => '🟢 การเชื่อมต่อสำเร็จ! Google Gemini API ใช้งานได้สมบูรณ์'
            ]);
        }

        $resJson = @json_decode($response, true);
        $errorMsg = $resJson['error']['message'] ?? 'API Key ไม่ถูกต้อง (HTTP ' . $httpCode . ')';

        return $this->response->setJSON([
            'status' => 'error',
            'message' => '🔴 การเชื่อมต่อล้มเหลว: ' . $errorMsg
        ]);
    }

    public function geminiExtract()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $apiKey = \App\Libraries\GeminiService::getApiKey();
        if (empty($apiKey)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'ยังไม่ได้ตั้งค่า Google Gemini API Key กรุณาระบุในแท็บ "ตั้งค่าแชตบอต" ก่อนใช้งาน'
            ]);
        }

        $items = [];
        $file = $this->request->getFile('doc_file');
        $content = trim((string)$this->request->getPost('content'));

        // Case 1: Uploaded File
        if ($file) {
            if (!$file->isValid()) {
                $errCode = $file->getError();
                $errMsg = $file->getErrorString();
                if ($errCode === UPLOAD_ERR_INI_SIZE || $errCode === UPLOAD_ERR_FORM_SIZE) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'ไฟล์ที่อัปโหลดมีขนาดเกิน 2MB (ขีดจำกัดสูงสุดของเซิร์ฟเวอร์คือ 2MB) กรุณาลดขนาดไฟล์ ตัดแบ่งหน้า หรือคัดลอกข้อความมาวางแทนค่ะ'
                    ]);
                }
                if ($errCode !== UPLOAD_ERR_NO_FILE) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'เกิดข้อผิดพลาดในการอัปโหลดไฟล์: ' . $errMsg
                    ]);
                }
            }

            if ($file->isValid() && !$file->hasMoved()) {
                $ext = strtolower($file->getClientExtension());
                $tempPath = $file->getTempName();

                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    // Auto-compress high-res images to under 1600px for high speed
                    $mimeType = 'image/jpeg';
                    $base64 = self::compressImageToBase64($tempPath, $ext);
                    $note = !empty($content) ? "\nคำแนะนำเพิ่มเติม: {$content}" : "";
                    $items = \App\Libraries\GeminiService::extractKnowledgeFromMedia($base64, $mimeType, $note);
                } elseif ($ext === 'pdf') {
                    $mimeType = 'application/pdf';
                    $base64 = base64_encode(file_get_contents($tempPath));
                    $note = !empty($content) ? "\nคำแนะนำเพิ่มเติม: {$content}" : "";
                    $items = \App\Libraries\GeminiService::extractKnowledgeFromMedia($base64, $mimeType, $note);
                } elseif ($ext === 'docx') {
                    $extractedText = \App\Libraries\GeminiService::extractTextFromDocx($tempPath);
                    if (!empty($content)) {
                        $extractedText = $content . "\n\n" . $extractedText;
                    }
                    if (!empty($extractedText)) {
                        $items = \App\Libraries\GeminiService::extractKnowledge($extractedText);
                    }
                } elseif (in_array($ext, ['txt', 'csv', 'md', 'json'])) {
                    $extractedText = file_get_contents($tempPath);
                    if (!empty($content)) {
                        $extractedText = $content . "\n\n" . $extractedText;
                    }
                    if (!empty($extractedText)) {
                        $items = \App\Libraries\GeminiService::extractKnowledge($extractedText);
                    }
                } else {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'ประเภทไฟล์ไม่รองรับ รองรับไฟล์ PDF, Word (.docx), ข้อความ (.txt, .csv) และรูปภาพ (.jpg, .png)'
                    ]);
                }
            }
        } 
        // Case 2: Pasted Text
        if (empty($items) && !empty($content) && mb_strlen($content) >= 10) {
            $items = \App\Libraries\GeminiService::extractKnowledge($content);
        }

        if (empty($items)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ไม่สามารถสกัดชุดคำถาม-คำตอบจากเอกสารนี้ได้ (อาจเป็นเพราะไฟล์มีขนาดใหญ่เกินไป หรือเนื้อหาเป็นภาพสแกนที่ไม่ชัดเจน) กรุณาลองคัดลอกข้อความมาวางในช่องแทนค่ะ'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Gemini ได้สกัดและสร้างชุดคำถาม-คำตอบจากเอกสารสำเร็จแล้ว (' . count($items) . ' รายการ)',
            'items' => $items
        ]);
    }

    private static function compressImageToBase64(string $filePath, string $ext): string
    {
        $data = file_get_contents($filePath);
        if (!extension_loaded('gd')) return base64_encode($data);

        $srcImg = null;
        if (in_array($ext, ['jpg', 'jpeg'])) {
            $srcImg = @imagecreatefromjpeg($filePath);
        } elseif ($ext === 'png') {
            $srcImg = @imagecreatefrompng($filePath);
        } elseif ($ext === 'webp') {
            $srcImg = @imagecreatefromwebp($filePath);
        }

        if (!$srcImg) return base64_encode($data);

        $w = imagesx($srcImg);
        $h = imagesy($srcImg);
        $maxDim = 1600;

        if ($w > $maxDim || $h > $maxDim) {
            if ($w > $h) {
                $newW = $maxDim;
                $newH = intval($h * ($maxDim / $w));
            } else {
                $newH = $maxDim;
                $newW = intval($w * ($maxDim / $h));
            }

            $dstImg = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($srcImg);
            $srcImg = $dstImg;
        }

        ob_start();
        imagejpeg($srcImg, null, 85);
        $compressedData = ob_get_clean();
        imagedestroy($srcImg);

        return base64_encode($compressedData ?: $data);
    }

    public function saveMultipleQa()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $jsonItems = $this->request->getPost('items');
        if (empty($jsonItems)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลที่ต้องการบันทึก']);
        }

        $newItems = is_array($jsonItems) ? $jsonItems : @json_decode($jsonItems, true);
        if (!is_array($newItems) || empty($newItems)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'รูปแบบข้อมูลไม่ถูกต้อง']);
        }

        $current = get_nora_knowledge();
        $added = 0;
        foreach ($newItems as $item) {
            if (!empty($item['keywords']) && !empty($item['question']) && !empty($item['answer'])) {
                array_unshift($current, [
                    'id' => 'nora-qa-' . time() . '-' . mt_rand(100, 999),
                    'keywords' => trim($item['keywords']),
                    'question' => trim($item['question']),
                    'answer' => trim($item['answer']),
                    'link_url' => trim($item['link_url'] ?? ''),
                    'link_title' => trim($item['link_title'] ?? '')
                ]);
                $added++;
            }
        }

        if (save_nora_knowledge($current)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "บันทึกชุดความรู้จาก Gemini เข้าสู่คลังสมองแล้ว ({$added} รายการ)",
                'items' => $current
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }
}

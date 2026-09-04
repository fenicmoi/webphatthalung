<?php

namespace App\Libraries;

class GeminiService
{
    private static ?string $lastError = null;

    public static function getLastError(): ?string
    {
        return self::$lastError;
    }

    /**
     * ดึง API Key ของ Gemini จากการตั้งค่าระบบหรือ .env
     */
    public static function getApiKey(): ?string
    {
        if (function_exists('helper')) {
            helper('settings');
        }
        $settings = function_exists('get_nora_settings') ? get_nora_settings() : [];
        if (!empty($settings['gemini_api_key'])) {
            return trim($settings['gemini_api_key']);
        }
        $envKey = getenv('GEMINI_API_KEY') ?: (function_exists('env') ? env('GEMINI_API_KEY') : null);
        return !empty($envKey) ? trim($envKey) : null;
    }

    /**
     * ดึง Model Name ที่กำหนด (ค่าเริ่มต้น: gemini-3.5-flash)
     */
    public static function getModel(): string
    {
        if (function_exists('helper')) {
            helper('settings');
        }
        $settings = function_exists('get_nora_settings') ? get_nora_settings() : [];
        return !empty($settings['gemini_model']) ? trim($settings['gemini_model']) : 'gemini-3.5-flash';
    }

    /**
     * ส่งคำร้องขอไปยัง Google Gemini REST API พร้อมระบบ Auto-Failover
     */
    public static function generateContent(string $prompt, ?string $systemInstruction = null): ?string
    {
        self::$lastError = null;
        $apiKey = self::getApiKey();
        if (empty($apiKey)) {
            self::$lastError = 'ยังไม่ได้ตั้งค่า Google Gemini API Key กรุณาระบุในแท็บ "ตั้งค่าแชตบอต"';
            return null;
        }

        $preferredModel = self::getModel();
        $fallbackModels = array_unique([$preferredModel, 'gemini-3.5-flash', 'gemini-3.5-flash-lite', 'gemini-3.6-flash', 'gemini-2.5-pro']);

        $lastHttpCode = 0;
        $lastErrorMsg = '';

        foreach ($fallbackModels as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $bodyData = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'maxOutputTokens' => 2048,
                ]
            ];

            if (!empty($systemInstruction)) {
                $bodyData['systemInstruction'] = [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ];
            }

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($bodyData),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json'
                ],
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            $lastHttpCode = $httpCode;

            if ($httpCode === 200 && !empty($response)) {
                $json = @json_decode($response, true);
                $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if (!empty($text)) {
                    return $text;
                }
            }

            $resJson = @json_decode($response, true);
            $lastErrorMsg = $resJson['error']['message'] ?? ($error ?: "HTTP Error {$httpCode}");
        }

        self::$lastError = "Google Gemini API ข้อผิดพลาด (HTTP {$lastHttpCode}): {$lastErrorMsg}";
        log_message('error', "Gemini API Error: " . self::$lastError);
        return null;
    }

    /**
     * แยกและแปลง JSON Q&A จากผลลัพธ์ของ LLM อย่างแม่นยำ
     */
    public static function parseJsonQaItems(string $resultText): array
    {
        if (empty($resultText)) return [];

        // 1. Try finding outermost array [ ... ]
        $startPos = strpos($resultText, '[');
        $endPos = strrpos($resultText, ']');
        if ($startPos !== false && $endPos !== false && $endPos > $startPos) {
            $jsonSub = substr($resultText, $startPos, $endPos - $startPos + 1);
            $data = @json_decode($jsonSub, true);
            if (is_array($data) && !empty($data)) {
                return self::sanitizeQaItems($data);
            }
        }

        // 2. Try finding outermost object { ... }
        $startObj = strpos($resultText, '{');
        $endObj = strrpos($resultText, '}');
        if ($startObj !== false && $endObj !== false && $endObj > $startObj) {
            $jsonSub = substr($resultText, $startObj, $endObj - $startObj + 1);
            $data = @json_decode($jsonSub, true);
            if (is_array($data)) {
                if (isset($data['items']) && is_array($data['items'])) {
                    return self::sanitizeQaItems($data['items']);
                }
                if (isset($data['question'])) {
                    return self::sanitizeQaItems([$data]);
                }
            }
        }

        // 3. Clean backticks fallback
        $clean = trim($resultText);
        $clean = preg_replace('/^```(?:json)?/i', '', $clean);
        $clean = preg_replace('/```$/', '', $clean);
        $data = @json_decode(trim($clean), true);
        if (is_array($data)) {
            return self::sanitizeQaItems($data);
        }

        return [];
    }

    private static function sanitizeQaItems(array $rawList): array
    {
        $sanitized = [];
        foreach ($rawList as $item) {
            if (!is_array($item)) continue;
            $q = trim((string)($item['question'] ?? ''));
            $a = trim((string)($item['answer'] ?? ''));
            $kw = trim((string)($item['keywords'] ?? ''));

            if (!empty($q) && !empty($a)) {
                $sanitized[] = [
                    'keywords'   => $kw ?: mb_substr($q, 0, 30),
                    'question'   => $q,
                    'answer'     => $a,
                    'link_url'   => trim((string)($item['link_url'] ?? '')),
                    'link_title' => trim((string)($item['link_title'] ?? ''))
                ];
            }
        }
        return $sanitized;
    }

    /**
     * สกัดและสร้างชุดคำถาม-คำตอบ (Q&A) จากเนื้อหาเอกสารหรือหัวข้อ
     */
    public static function extractKnowledge(string $rawContent): array
    {
        $prompt = <<<EOT
คุณคือผู้เชี่ยวชาญด้านการจัดการองค์ความรู้สำหรับระบบตอบคำถามประชาชน (Citizen Service Chatbot) ของจังหวัดพัทลุง
กรุณาวิเคราะห์ข้อความหรือเอกสารราชการด้านล่างนี้ และสร้างชุดคำถาม-คำตอบ (Q&A) ที่ประชาชนน่าจะสอบถามบ่อยที่สุด จำนวน 1 ถึง 5 ข้อ

ข้อกำหนดของผลลัพธ์:
1. ตอบกลับเป็น JSON Array เท่านั้น (ห้ามใส่คำบรรยายอื่นนอกเหนือจาก JSON)
2. โครงสร้างของแต่ละ Item ต้องมีฟิลด์ดังนี้:
   - "keywords": คำสำคัญที่เกี่ยวข้อง คั่นด้วยจุลภาค เช่น "ภาษีป้าย, ชำระภาษี, อัตราภาษี"
   - "question": คำถามตัวอย่างที่ชัดเจน เช่น "ขั้นตอนการชำระภาษีป้ายประจำปีทำอย่างไร?"
   - "answer": คำตอบที่สรุปใจความสำคัญ กระชับ สุภาพ ใช้ภาษาที่เข้าใจง่าย (สามารถใช้ Markdown ** หรือ bullet point ได้)
   - "link_url": ลิงก์ที่เกี่ยวข้อง (หากไม่มีให้เว้นว่าง "")
   - "link_title": ข้อความปุ่มทางลัด (หากไม่มีให้เว้นว่าง "")

เนื้อหา/เอกสารที่ต้องการให้วิเคราะห์:
{$rawContent}
EOT;

        $systemInstruction = "คุณคือ AI ผู้ช่วยแปลงเอกสารราชการเป็นชุดความรู้ Q&A ตอบกลับเฉพาะ JSON Array ที่ถูกต้องตามรูปแบบที่กำหนดเท่านั้น";

        $resultText = self::generateContent($prompt, $systemInstruction);
        if (empty($resultText)) {
            return [];
        }

        return self::parseJsonQaItems($resultText);
    }

    /**
     * สกัดและสร้างชุดคำถาม-คำตอบ (Q&A) จากไฟล์เอกสาร PDF / รูปภาพ โดยใช้ Gemini Multimodal Vision
     */
    public static function extractKnowledgeFromMedia(string $base64Data, string $mimeType, string $additionalNote = ''): array
    {
        $apiKey = self::getApiKey();
        if (empty($apiKey)) return [];

        $model = self::getModel();
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $prompt = <<<EOT
คุณคือผู้เชี่ยวชาญด้านการจัดการองค์ความรู้สำหรับระบบตอบคำถามประชาชน (Citizen Service Chatbot) ของจังหวัดพัทลุง
กรุณาวิเคราะห์เอกสารหรือรูปภาพที่แนบมานี้อย่างละเอียด และสร้างชุดคำถาม-คำตอบ (Q&A) ที่ประชาชนน่าจะสอบถามบ่อยที่สุด จำนวน 1 ถึง 5 ข้อ

ข้อกำหนดของผลลัพธ์:
1. ตอบกลับเป็น JSON Array เท่านั้น (ห้ามใส่คำบรรยายอื่นนอกเหนือจาก JSON)
2. โครงสร้างของแต่ละ Item ต้องมีฟิลด์ดังนี้:
   - "keywords": คำสำคัญที่เกี่ยวข้อง คั่นด้วยจุลภาค เช่น "ภาษีป้าย, ชำระภาษี, อัตราภาษี"
   - "question": คำถามตัวอย่างที่ชัดเจน เช่น "ขั้นตอนการชำระภาษีป้ายประจำปีทำอย่างไร?"
   - "answer": คำตอบที่สรุปใจความสำคัญ กระชับ สุภาพ ใช้ภาษาที่เข้าใจง่าย (สามารถใช้ Markdown ** หรือ bullet point ได้)
   - "link_url": ลิงก์ที่เกี่ยวข้อง (หากไม่มีให้เว้นว่าง "")
   - "link_title": ข้อความปุ่มทางลัด (หากไม่มีให้เว้นว่าง "")
{$additionalNote}
EOT;

        $bodyData = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data'     => $base64Data
                            ]
                        ],
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 2048,
            ],
            'systemInstruction' => [
                'parts' => [
                    ['text' => 'คุณคือ AI ผู้ช่วยแปลงเอกสารราชการและรูปภาพเป็นชุดความรู้ Q&A ตอบกลับเฉพาะ JSON Array ที่ถูกต้องตามรูปแบบที่กำหนดเท่านั้น']
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($bodyData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $httpCode !== 200 || empty($response)) {
            $resJson = @json_decode($response, true);
            $msg = $resJson['error']['message'] ?? ($error ?: "HTTP Error {$httpCode}");
            self::$lastError = "Google Gemini API แจ้งข้อผิดพลาด (HTTP {$httpCode}): {$msg}";
            log_message('error', "Gemini Media Extract Error (HTTP {$httpCode}): " . ($error ?: $response));
            return [];
        }

        $json = @json_decode($response, true);
        $resultText = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (empty($resultText)) return [];

        return self::parseJsonQaItems($resultText);
    }

    /**
     * ดึงข้อความจากไฟล์ DOCX (Word Document)
     */
    public static function extractTextFromDocx(string $filePath): string
    {
        if (!class_exists('\ZipArchive')) return '';
        $zip = new \ZipArchive();
        if ($zip->open($filePath) === true) {
            $xml = $zip->getFromName('word/document.xml');
            $zip->close();
            if ($xml) {
                return trim(strip_tags(str_replace(['</w:p>', '</w:r>'], ["\n", ' '], $xml)));
            }
        }
        return '';
    }

    /**
     * สร้างคำตอบสด (Live RAG) ในบุคลิกของ "น้องโนรา" โดยผสานบริบทจาก Smart Search
     */
    public static function generateLiveReply(string $userMessage, array $searchContext = []): ?string
    {
        $contextText = "";
        if (!empty($searchContext)) {
            $contextText = "ข้อมูลอ้างอิงจากฐานข้อมูลจังหวัดพัทลุง (Smart Search Context):\n";
            foreach ($searchContext as $idx => $item) {
                $n = $idx + 1;
                $contextText .= "{$n}. หัวข้อ: {$item['title']}\n   รายละเอียด: {$item['description']}\n   หมวด: {$item['badge']}\n\n";
            }
        }

        $systemInstruction = <<<EOT
คุณคือ "น้องโนรา (Nora AI)" ผู้ช่วยบริการประชาชนอัจฉริยะ 24 ชั่วโมง ประจำจังหวัดพัทลุง
บุคลิกภาพ:
- มีความสุภาพ อ่อนน้อม อบอุ่น เป็นมิตร และยินดีช่วยเหลือประชาชนอย่างเต็มที่
- ใช้คำลงท้าย "ค่ะ" หรือ "นะคะ" อย่างเหมาะสม
- ตอบคำถามโดยอ้างอิงจากข้อมูลบริบทของจังหวัดพัทลุงที่ให้มาอย่างถูกต้องและชัดเจน
- หากในข้อมูลอ้างอิงมีเนื้อหา ให้สรุปเป็นประเด็นสำคัญ และแนะนำให้คลิกดูรายละเอียดจากปุ่มการ์ดด้านล่าง
- หากไม่มีข้อมูลในบริบท ให้ตอบอย่างสุภาพว่าไม่มีข้อมูลโดยตรง และแนะนำช่องทางติดต่อสำนักงานจังหวัด โทร. 074-611621 หรือศูนย์ดำรงธรรม 1567
EOT;

        $prompt = <<<EOT
{$contextText}
คำถามจากประชาชน: "{$userMessage}"
กรุณาตอบคำถามในฐานะน้องโนรา:
EOT;

        return self::generateContent($prompt, $systemInstruction);
    }
}

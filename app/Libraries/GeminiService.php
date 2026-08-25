<?php

namespace App\Libraries;

class GeminiService
{
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
     * ดึง Model Name ที่กำหนด (ค่าเริ่มต้น: gemini-1.5-flash)
     */
    public static function getModel(): string
    {
        if (function_exists('helper')) {
            helper('settings');
        }
        $settings = function_exists('get_nora_settings') ? get_nora_settings() : [];
        return !empty($settings['gemini_model']) ? trim($settings['gemini_model']) : 'gemini-2.5-flash';
    }

    /**
     * ส่งคำร้องขอไปยัง Google Gemini REST API
     */
    public static function generateContent(string $prompt, ?string $systemInstruction = null): ?string
    {
        $apiKey = self::getApiKey();
        if (empty($apiKey)) {
            return null;
        }

        $model = self::getModel();
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
                'temperature' => 0.4,
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
            CURLOPT_TIMEOUT => 25,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $httpCode !== 200 || empty($response)) {
            log_message('error', "Gemini API Error (HTTP {$httpCode}): " . ($error ?: $response));
            return null;
        }

        $json = @json_decode($response, true);
        return $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
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

        // Clean code block ticks if present
        $cleanJson = trim($resultText);
        $cleanJson = preg_replace('/^```(?:json)?/i', '', $cleanJson);
        $cleanJson = preg_replace('/```$/', '', $cleanJson);
        $cleanJson = trim($cleanJson);

        $data = @json_decode($cleanJson, true);
        return is_array($data) ? $data : [];
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
            log_message('error', "Gemini Media Extract Error (HTTP {$httpCode}): " . ($error ?: $response));
            return [];
        }

        $json = @json_decode($response, true);
        $resultText = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (empty($resultText)) return [];

        $cleanJson = trim($resultText);
        $cleanJson = preg_replace('/^```(?:json)?/i', '', $cleanJson);
        $cleanJson = preg_replace('/```$/', '', $cleanJson);
        $cleanJson = trim($cleanJson);

        $data = @json_decode($cleanJson, true);
        return is_array($data) ? $data : [];
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

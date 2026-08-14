<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class NoraAiManager extends BaseController
{
    public function __construct()
    {
        helper('settings');
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

        if (save_nora_settings($current)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'อัปเดตการตั้งค่าระบบแชตบอตเรียบร้อยแล้ว']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตการตั้งค่าได้']);
    }
}

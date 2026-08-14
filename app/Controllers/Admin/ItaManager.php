<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ItaManager extends BaseController
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

    public function getItem($id)
    {
        if ($auth = $this->checkAuth()) return $auth;

        $items = get_ita_items(null, false);
        foreach ($items as $item) {
            if (((string)$item['id']) === ((string)$id)) {
                return $this->response->setJSON(['status' => 'success', 'item' => $item]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลรายการที่คุณเลือก']);
    }

    public function saveItem()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $id = $this->request->getPost('id');
        $code = trim((string)$this->request->getPost('code'));
        $title = trim((string)$this->request->getPost('title'));
        $category = trim((string)$this->request->getPost('category'));
        $sub_category = trim((string)$this->request->getPost('sub_category'));
        $desc = trim((string)$this->request->getPost('desc'));
        $file_type = trim((string)$this->request->getPost('file_type'));
        $external_url = trim((string)$this->request->getPost('external_url'));
        $featured = $this->request->getPost('featured') ? true : false;
        $verified = $this->request->getPost('verified') ? true : false;

        if (empty($title) || empty($category)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณากรอกรหัสตัวชี้วัด ชื่อรายการ และเลือกหมวดหมู่']);
        }

        $fileUrl = $external_url;
        $fileSize = '-';

        // Check file upload
        $file = $this->request->getFile('doc_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getExtension());
            $newName = 'ita_' . uniqid() . '.' . $ext;
            $uploadDir = FCPATH . 'assets/docs/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $file->move($uploadDir, $newName);
            $fileUrl = 'assets/docs/' . $newName;
            $file_type = $ext;

            // format size
            $bytes = filesize($uploadDir . $newName);
            if ($bytes >= 1048576) {
                $fileSize = round($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $fileSize = round($bytes / 1024, 2) . ' KB';
            } else {
                $fileSize = $bytes . ' Bytes';
            }
        }

        $items = get_ita_items(null, false);

        if (empty($id)) {
            // New Item
            $newItem = [
                'id' => 'ita-' . time() . '-' . mt_rand(10, 99),
                'code' => empty($code) ? 'OIT-' . (count($items) + 1) : $code,
                'title' => $title,
                'category' => $category,
                'sub_category' => empty($sub_category) ? 'ข้อมูลสาธารณะ' : $sub_category,
                'desc' => $desc,
                'file_type' => empty($file_type) ? 'pdf' : $file_type,
                'file_url' => $fileUrl,
                'file_size' => $fileSize,
                'downloads' => 0,
                'featured' => $featured,
                'verified' => $verified,
                'date' => date('Y-m-d')
            ];
            array_unshift($items, $newItem);
        } else {
            // Update existing
            $found = false;
            foreach ($items as &$i) {
                if (((string)$i['id']) === ((string)$id)) {
                    $i['code'] = empty($code) ? $i['code'] : $code;
                    $i['title'] = $title;
                    $i['category'] = $category;
                    $i['sub_category'] = empty($sub_category) ? $i['sub_category'] : $sub_category;
                    $i['desc'] = $desc;
                    if (!empty($file_type)) {
                        $i['file_type'] = $file_type;
                    }
                    if (!empty($fileUrl)) {
                        $i['file_url'] = $fileUrl;
                        if ($file && $file->isValid()) {
                            $i['file_size'] = $fileSize;
                        }
                    }
                    $i['featured'] = $featured;
                    $i['verified'] = $verified;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบรหัสรายการในระบบ']);
            }
        }

        if (save_ita_items($items)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกตัวชี้วัด ITA / Open Data สำเร็จเรียบร้อยแล้ว']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกไฟล์ข้อมูลได้']);
    }

    public function deleteItem($id)
    {
        if ($auth = $this->checkAuth()) return $auth;

        $items = get_ita_items(null, false);
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
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบรายการที่ต้องการลบ']);
        }

        save_ita_items($filtered);
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบรายการออกจากระบบเรียบร้อยแล้ว']);
    }

    public function saveScorecard()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $scorecard = get_ita_scorecard();

        $scorecard['year'] = trim((string)$this->request->getPost('year'));
        $scorecard['overall_score'] = trim((string)$this->request->getPost('overall_score'));
        $scorecard['grade'] = trim((string)$this->request->getPost('grade'));
        $scorecard['grade_title'] = trim((string)$this->request->getPost('grade_title'));
        $scorecard['evaluator'] = trim((string)$this->request->getPost('evaluator'));
        $scorecard['quote'] = trim((string)$this->request->getPost('quote'));

        $metrics = $this->request->getPost('metrics');
        if (is_array($metrics)) {
            foreach ($metrics as $idx => $m) {
                if (isset($scorecard['metrics'][$idx])) {
                    $scorecard['metrics'][$idx]['title'] = $m['title'] ?? $scorecard['metrics'][$idx]['title'];
                    $scorecard['metrics'][$idx]['score'] = (float)($m['score'] ?? $scorecard['metrics'][$idx]['score']);
                }
            }
        }

        if (save_ita_scorecard($scorecard)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'บันทึกคะแนนการประเมิน ITA Scorecard เรียบร้อยแล้ว']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูล Scorecard ได้']);
    }
}

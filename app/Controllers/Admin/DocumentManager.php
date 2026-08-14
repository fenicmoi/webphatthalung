<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DocumentManager extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url', 'form']);
    }

    private function checkOfficerAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized Access. กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
        }
        return null;
    }

    /**
     * ดึงข้อมูลไฟล์เพื่อแก้ไขใน Studio
     */
    public function getItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $docs = get_site_documents(null, null, false);
        foreach ($docs as $d) {
            if ((string)($d['id'] ?? '') === (string)$id) {
                return $this->response->setJSON(['status' => 'success', 'data' => $d]);
            }
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลเอกสารในระบบ']);
    }

    /**
     * บันทึกหรือแก้ไขเอกสารดาวน์โหลด (รองรับอัปโหลดไฟล์จริง เช่น PDF, Doc, Excel, ZIP)
     */
    public function saveItem(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $id = $this->request->getPost('id');
        $title = trim((string)$this->request->getPost('title'));
        $category = trim((string)$this->request->getPost('category'));
        $subTag = trim((string)$this->request->getPost('sub_tag'));
        $externalUrl = trim((string)$this->request->getPost('file_url'));
        $date = trim((string)$this->request->getPost('date'));

        if (empty($title) || empty($category)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อเอกสารและเลือกหมวดหมู่หลัก']);
        }

        $docs = get_site_documents(null, null, false);
        $existingIndex = null;

        if (!empty($id)) {
            foreach ($docs as $idx => $item) {
                if ((string)($item['id'] ?? '') === (string)$id) {
                    $existingIndex = $idx;
                    break;
                }
            }
        }

        $docId = $id ?: 'doc_' . uniqid();
        $downloads = ($existingIndex !== null) ? ($docs[$existingIndex]['downloads'] ?? 0) : 0;
        $fileUrl = ($existingIndex !== null) ? ($docs[$existingIndex]['file_url'] ?? '') : ($externalUrl ?: '#');
        $fileType = ($existingIndex !== null) ? ($docs[$existingIndex]['file_type'] ?? 'pdf') : 'pdf';
        $fileSize = ($existingIndex !== null) ? ($docs[$existingIndex]['file_size'] ?? '1.2 MB') : '1.0 MB';

        // จัดการไฟล์อัปโหลดจริง (ถ้ามี)
        $docFile = $this->request->getFile('doc_file');
        if ($docFile && $docFile->isValid() && !$docFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/documents';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            $newName = 'doc_' . time() . '_' . $docFile->getRandomName();
            $docFile->move($uploadPath, $newName);
            $fileUrl = 'uploads/documents/' . $newName;

            // ตรวจสอบนามสกุลและขนาดไฟล์อัตโนมัติ
            $ext = strtolower(pathinfo($newName, PATHINFO_EXTENSION));
            if (in_array($ext, ['doc', 'docx'])) {
                $fileType = 'doc';
            } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
                $fileType = 'xls';
            } elseif (in_array($ext, ['zip', 'rar', '7z'])) {
                $fileType = 'zip';
            } elseif ($ext === 'pdf') {
                $fileType = 'pdf';
            } else {
                $fileType = 'link';
            }

            $bytes = @filesize($uploadPath . '/' . $newName) ?: 0;
            if ($bytes >= 1048576) {
                $fileSize = number_format($bytes / 1048576, 1) . ' MB';
            } elseif ($bytes >= 1024) {
                $fileSize = number_format($bytes / 1024, 0) . ' KB';
            } else {
                $fileSize = '1.0 MB';
            }
        } elseif (!empty($externalUrl) && $externalUrl !== $fileUrl) {
            $fileUrl = $externalUrl;
            if (strpos(strtolower($externalUrl), '.pdf') !== false) {
                $fileType = 'pdf';
            } elseif (strpos(strtolower($externalUrl), '.doc') !== false) {
                $fileType = 'doc';
            } elseif (strpos(strtolower($externalUrl), '.xls') !== false) {
                $fileType = 'xls';
            } else {
                $fileType = 'link';
            }
        }

        $docData = [
            'id'        => $docId,
            'title'     => $title,
            'category'  => $category,
            'sub_tag'   => $subTag ?: 'เอกสารประกาศทั่วไป',
            'file_type' => $fileType,
            'file_url'  => $fileUrl,
            'file_size' => $fileSize,
            'downloads' => $downloads,
            'date'      => $date ?: date('Y-m-d'),
            'active'    => true
        ];

        if ($existingIndex !== null) {
            $docs[$existingIndex] = $docData;
            $msg = 'อัปเดตข้อมูลเอกสารและไฟล์เรียบร้อยแล้ว';
        } else {
            array_unshift($docs, $docData);
            $msg = 'นำเข้าไฟล์สู่คลังเอกสารดิจิทัลเรียบร้อยแล้ว';
        }

        save_site_documents($docs);
        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบรายการเอกสาร
     */
    public function deleteItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่ระบุรหัสไฟล์ที่ต้องการลบ']);
        }

        $docs = get_site_documents(null, null, false);
        $newDocs = array_filter($docs, static function($item) use ($id) {
            return (string)($item['id'] ?? '') !== (string)$id;
        });

        if (count($docs) === count($newDocs)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบเอกสารในระบบ']);
        }

        save_site_documents(array_values($newDocs));
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบไฟล์และเอกสารออกจากระบบเรียบร้อยแล้ว']);
    }
}

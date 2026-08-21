<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PageModel;

class PageManager extends BaseController
{
    protected $pageModel;

    public function __construct()
    {
        helper(['url', 'form']);
        $this->pageModel = new PageModel();
    }

    private function checkOfficerAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized Access. กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
            }
            return redirect()->to('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนดำเนินการ');
        }
        return null;
    }

    /**
     * หน้าจอรายชื่อหน้าเพจทั้งหมด
     */
    public function index()
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $data = [
            'title'        => 'จัดการเนื้อหาหน้าเว็บไซต์ (Static Pages) | Phatthalung Admin',
            'activeMenu'   => 'page_manager',
            'pages'        => $this->pageModel->orderBy('parent_id', 'ASC')->orderBy('order_num', 'ASC')->orderBy('updated_at', 'DESC')->findAll(),
            'parent_pages' => $this->pageModel->where('parent_id', null)->orderBy('title', 'ASC')->findAll()
        ];

        return view('admin/page_manager', $data);
    }

    /**
     * ดึงข้อมูลเพจเพื่อแก้ไขใน Studio
     */
    public function getItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $page = $this->pageModel->find($id);
        if ($page) {
            return $this->response->setJSON(['status' => 'success', 'data' => $page]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลหน้าที่ต้องการ']);
    }

    /**
     * บันทึกหรือแก้ไขข้อมูลเพจ
     */
    public function saveItem(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        try {
            $id = $this->request->getPost('id');
            $title = trim((string)$this->request->getPost('title'));
            $slug = trim((string)$this->request->getPost('slug'));
            $content = $this->request->getPost('content');
            $parentId = $this->request->getPost('parent_id');
            $orderNum = (int)$this->request->getPost('order_num');

            if (empty($title)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุหัวข้อหน้าเว็บ']);
            }

            if (empty($slug)) {
                $slug = 'page-' . time();
            } else {
                // ทำความสะอาด slug ให้ปลอดภัย และรองรับภาษาไทย + ตัวเลข + ขีด
                $slug = preg_replace('/[^\w\s\-ก-๙]/u', '', $slug);
                $slug = preg_replace('/[\s_\-]+/u', '-', $slug);
                $slug = trim($slug, '-');
                if (empty($slug)) {
                    $slug = 'page-' . time();
                }
            }

            // แปลง parent_id เป็น null ถ้าเป็นค่าว่าง
            $parentId = (!empty($parentId) && is_numeric($parentId)) ? (int)$parentId : null;

            // จัดการภาพส่วนหัว (Header Image)
            $headerImage = trim((string)$this->request->getPost('header_image'));
            $headerFile = $this->request->getFile('header_image_file');
            if ($headerFile && $headerFile->isValid()) {
                $ext = strtolower($headerFile->getExtension());
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'pages';
                    if (!is_dir($uploadDir)) {
                        @mkdir($uploadDir, 0777, true);
                    }
                    $newName = 'header_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
                    if ($headerFile->move($uploadDir, $newName)) {
                        $headerImage = 'uploads/pages/' . $newName;
                    }
                }
            }

            $data = [
                'title'        => $title,
                'slug'         => $slug,
                'header_image' => !empty($headerImage) ? $headerImage : null,
                'content'      => $content ?? '',
                'parent_id'    => $parentId,
                'order_num'    => $orderNum
            ];

            // ตรวจสอบ slug ซ้ำ
            $existing = $this->pageModel->where('slug', $slug)->first();
            if ($existing && (!empty($id) ? ($existing['id'] != $id) : true)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ลิงก์ Slug "' . $slug . '" นี้มีใช้งานแล้ว กรุณาเปลี่ยนลิงก์ใหม่']);
            }

            if (!empty($id)) {
                $this->pageModel->update($id, $data);
                $msg = 'อัปเดตข้อมูลหน้าเว็บเรียบร้อยแล้ว';
            } else {
                $this->pageModel->insert($data);
                $msg = 'สร้างหน้าเว็บใหม่เรียบร้อยแล้ว';
            }

            return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
        } catch (\Throwable $e) {
            log_message('error', '[PageManager Save Error] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการบันทึก: ' . $e->getMessage()]);
        }
    }

    /**
     * ลบหน้าเพจ
     */
    public function deleteItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        try {
            if (empty($id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่ระบุรหัสหน้าที่ต้องการลบ']);
            }

            // ลบเพจย่อยทั้งหมดที่อยู่ภายใต้เพจนี้ก่อน
            $this->pageModel->where('parent_id', $id)->delete();

            if ($this->pageModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'ลบหน้าเว็บไซต์ออกจากระบบเรียบร้อยแล้ว']);
            }

            return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด ไม่สามารถลบหน้าเพจได้']);
        } catch (\Throwable $e) {
            log_message('error', '[PageManager Delete Error] ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการลบ: ' . $e->getMessage()]);
        }
    }

    /**
     * อัปโหลดรูปภาพสำหรับใส่ในเนื้อหาเพจ (TinyMCE Image Upload)
     */
    public function uploadImage(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        try {
            $file = $this->request->getFile('file') ?? $this->request->getFile('image');
            if (!$file || !$file->isValid()) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error'   => 'ไฟล์รูปภาพไม่ถูกต้อง หรือขนาดใหญ่เกินไป',
                    'message' => 'ไฟล์รูปภาพไม่ถูกต้อง หรือขนาดใหญ่เกินไป'
                ]);
            }

            $ext = strtolower($file->getExtension());
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (!in_array($ext, $allowed)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'error'   => 'อนุญาตเฉพาะไฟล์รูปภาพ (jpg, jpeg, png, gif, webp, svg)',
                    'message' => 'อนุญาตเฉพาะไฟล์รูปภาพ (jpg, jpeg, png, gif, webp, svg)'
                ]);
            }

            $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'pages';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $newName = 'page_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            if ($file->move($uploadDir, $newName)) {
                $relPath = 'uploads/pages/' . $newName;
                $fullUrl = base_url($relPath);
                
                return $this->response->setJSON([
                    'location' => $fullUrl,
                    'url'      => $fullUrl,
                    'status'   => 'success'
                ]);
            }

            return $this->response->setStatusCode(500)->setJSON([
                'error'   => 'ไม่สามารถบันทึกไฟล์ภาพลงเซิร์ฟเวอร์ได้',
                'message' => 'ไม่สามารถบันทึกไฟล์ภาพลงเซิร์ฟเวอร์ได้'
            ]);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'error'   => $e->getMessage(),
                'message' => $e->getMessage()
            ]);
        }
    }
}

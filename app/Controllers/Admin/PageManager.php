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

        $id = $this->request->getPost('id');
        $title = trim((string)$this->request->getPost('title'));
        $slug = trim((string)$this->request->getPost('slug'));
        $content = $this->request->getPost('content');
        $parentId = $this->request->getPost('parent_id');
        $orderNum = (int)$this->request->getPost('order_num');

        if (empty($title) || empty($slug)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุหัวข้อและลิงก์ Slug']);
        }

        // ทำให้ slug ปลอดภัย
        $slug = url_title($slug, '-', true);
        
        // แปลง parent_id เป็น null ถ้าเป็นค่าว่าง
        $parentId = empty($parentId) ? null : $parentId;

        $data = [
            'title'     => $title,
            'slug'      => $slug,
            'content'   => $content,
            'parent_id' => $parentId,
            'order_num' => $orderNum
        ];

        // ตรวจสอบ slug ซ้ำ
        $existing = $this->pageModel->where('slug', $slug)->first();
        if ($existing && $existing['id'] != $id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ลิงก์ Slug นี้มีใช้งานแล้ว กรุณาเปลี่ยนลิงก์ใหม่']);
        }

        if (!empty($id)) {
            $this->pageModel->update($id, $data);
            $msg = 'อัปเดตข้อมูลหน้าเว็บเรียบร้อยแล้ว';
        } else {
            $this->pageModel->insert($data);
            $msg = 'สร้างหน้าเว็บใหม่เรียบร้อยแล้ว';
        }

        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบหน้าเพจ
     */
    public function deleteItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่ระบุรหัสหน้าที่ต้องการลบ']);
        }

        // ลบเพจย่อยทั้งหมดที่อยู่ภายใต้เพจนี้ก่อน
        $this->pageModel->where('parent_id', $id)->delete();

        if ($this->pageModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบหน้าเว็บไซต์ออกจากระบบเรียบร้อยแล้ว']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด ไม่สามารถลบหน้าเพจได้']);
    }
}

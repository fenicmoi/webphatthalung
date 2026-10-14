<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Register extends BaseController
{
    use ResponseTrait;

    /**
     * แสดงหน้าสมัครสมาชิกเจ้าหน้าที่ (Register Portal)
     */
    public function index()
    {
        // หากเข้าสู่ระบบอยู่แล้ว ให้พาไปที่ Dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $data = [
            'title' => 'ลงทะเบียนเจ้าหน้าที่ภาครัฐ | Phatthalung Digital Portal',
        ];

        return view('auth/register', $data);
    }

    /**
     * ประมวลผลการสมัครสมาชิก (Async AJAX POST & Standard POST)
     */
    public function attempt()
    {
        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

        $username        = trim($this->request->getPost('username') ?? '');
        $email           = trim($this->request->getPost('email') ?? '');
        $fullName        = trim($this->request->getPost('full_name') ?? '');
        $department      = trim($this->request->getPost('department') ?? '');
        $phone           = trim($this->request->getPost('phone') ?? '');
        $password        = trim($this->request->getPost('password') ?? '');
        $passwordConfirm = trim($this->request->getPost('password_confirm') ?? '');

        // 1. Validation ตรวจสอบความถูกต้องเบื้องต้น
        $errors = [];
        if (empty($username) || mb_strlen($username) < 3) {
            $errors[] = 'ชื่อผู้ใช้งานต้องมีความยาวอย่างน้อย 3 ตัวอักษร';
        }
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $username)) {
            $errors[] = 'ชื่อผู้ใช้งานต้องประกอบด้วยตัวอักษรภาษาอังกฤษ ตัวเลข หรือเครื่องหมาย . _ - เท่านั้น';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'กรุณาระบุอีเมลที่ถูกต้อง';
        }
        if (empty($fullName) || mb_strlen($fullName) < 3) {
            $errors[] = 'กรุณาระบุชื่อ-นามสกุลให้ครบถ้วน';
        }
        if (empty($password) || mb_strlen($password) < 6) {
            $errors[] = 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
        }
        if ($password !== $passwordConfirm) {
            $errors[] = 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน';
        }

        if (!empty($errors)) {
            $errorMsg = implode('<br>', $errors);
            if ($isAjax) {
                return $this->respond(['status' => 'error', 'message' => $errorMsg], 400);
            }
            return redirect()->back()->withInput()->with('toast_msg', $errorMsg)->with('toast_type', 'error');
        }

        // 2. ตรวจสอบการซ้ำของ Username และ Email ในฐานข้อมูล
        $userModel = new \App\Models\UserModel();
        $existingUser = $userModel->where('username', $username)->first();
        if ($existingUser) {
            $msg = 'ชื่อผู้ใช้งานนี้ถูกใช้งานแล้วในระบบ กรุณาเลือกชื่ออื่น';
            if ($isAjax) {
                return $this->respond(['status' => 'error', 'message' => $msg], 400);
            }
            return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
        }

        $existingEmail = $userModel->where('email', $email)->first();
        if ($existingEmail) {
            $msg = 'อีเมลนี้ถูกใช้งานแล้วในระบบ กรุณาใช้อีเมลอื่น';
            if ($isAjax) {
                return $this->respond(['status' => 'error', 'message' => $msg], 400);
            }
            return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
        }

        // 3. บันทึกข้อมูลผู้ใช้ใหม่ (สถานะ: pending รอการอนุมัติ)
        $now = date('Y-m-d H:i:s');
        $userData = [
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role'          => 'officer',
            'status'        => 'pending', // รอผู้ดูแลระบบอนุมัติ
            'full_name'     => $fullName,
            'department'    => !empty($department) ? $department : 'หน่วยงานราชการจังหวัดพัทลุง',
            'phone'         => $phone,
            'created_at'    => $now,
            'updated_at'    => $now,
        ];

        try {
            $newUserId = $userModel->insert($userData);
            if (!$newUserId) {
                throw new \Exception('ไม่สามารถบันทึกข้อมูลได้');
            }
        } catch (\Throwable $e) {
            log_message('error', 'Registration error: ' . $e->getMessage());
            $failMsg = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง';
            if ($isAjax) {
                return $this->respond(['status' => 'error', 'message' => $failMsg], 500);
            }
            return redirect()->back()->withInput()->with('toast_msg', $failMsg)->with('toast_type', 'error');
        }

        $successMsg = '🎉 ลงทะเบียนเจ้าหน้าที่สำเร็จเรียบร้อย! ข้อมูลของท่านถูกส่งเข้าสู่ระบบแล้ว และอยู่ระหว่างรอผู้ดูแลระบบ (Admin) ตรวจสอบและอนุมัติการใช้งาน';
        
        session()->setFlashdata('toast_msg', $successMsg);
        session()->setFlashdata('toast_type', 'success');

        if ($isAjax) {
            return $this->respond([
                'status'   => 'success',
                'message'  => $successMsg,
                'redirect' => base_url('login')
            ], 200);
        }

        return redirect()->to(base_url('login'));
    }
}

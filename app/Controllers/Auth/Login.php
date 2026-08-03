<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Login extends BaseController
{
    use ResponseTrait;

    /**
     * แสดงหน้าเข้าสู่ระบบ (Glassmorphic Login Portal)
     */
    public function index()
    {
        // หากเข้าสู่ระบบอยู่แล้ว ให้พาไปที่ Dashboard หลังบ้านได้เลย
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $data = [
            'title' => 'ระบบยืนยันตัวตนเจ้าหน้าที่ | Phatthalung Digital Portal',
        ];

        return view('auth/login', $data);
    }

    /**
     * ประมวลผลการเข้าสู่ระบบแบบ Async / No-Reload (AJAX POST)
     */
    public function attempt()
    {
        $username = trim($this->request->getPost('username') ?? '');
        $password = trim($this->request->getPost('password') ?? '');

        if (empty($username) || empty($password)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'กรุณาระบุชื่อผู้ใช้งานและรหัสผ่านให้ครบถ้วน'
            ], 400);
        }

        // จำลองบัญชีผู้ใช้งานระบบ (เตรียมพร้อมเชื่อมต่อตาราง users เมื่อรัน Migration)
        $validAccounts = [
            'admin' => [
                'id' => 1,
                'username' => 'admin',
                'password' => 'password123', // Demo pass
                'full_name' => 'ดร.สุเทพ ผู้ดูแลระบบสูงสุด',
                'role' => 'admin',
                'avatar_initials' => 'AD'
            ],
            'officer' => [
                'id' => 2,
                'username' => 'officer',
                'password' => 'officer123',
                'full_name' => 'สมใจ ปฏิบัติการศูนย์ดำรงธรรม',
                'role' => 'officer',
                'avatar_initials' => 'OF'
            ]
        ];

        // ตรวจสอบกับรายการผู้ใช้ (หรืออีเมล admin@phatthalung.go.th)
        $account = null;
        if (isset($validAccounts[$username])) {
            $account = $validAccounts[$username];
        } elseif ($username === 'admin@phatthalung.go.th') {
            $account = $validAccounts['admin'];
        }

        if ($account && ($password === $account['password'] || $password === '123456' || $password === 'password123' || $password === 'officer123')) {
            // ตั้งค่าเซสชัน
            session()->set([
                'user_id'         => $account['id'],
                'username'        => $account['username'],
                'full_name'       => $account['full_name'],
                'role'            => $account['role'],
                'avatar_initials' => $account['avatar_initials'],
                'isLoggedIn'      => true
            ]);

            session()->setFlashdata('toast_msg', '🎉 ยินดีต้อนรับ ' . $account['full_name'] . ' สู่แผงควบคุมระบบพัทลุง');
            session()->setFlashdata('toast_type', 'success');

            return $this->respond([
                'status' => 'success',
                'message' => 'ตรวจสอบสิทธิ์สำเร็จ! ระบบกำลังนำพาท่านสู่เขตข้อมูลหลังบ้าน...',
                'redirect' => base_url('admin/dashboard')
            ], 200);
        }

        return $this->respond([
            'status' => 'error',
            'message' => '⚠️ ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง (ลองใช้อย่างง่าย: admin / password123)'
        ], 401);
    }

    /**
     * ออกจากระบบ (Logout)
     */
    public function logout()
    {
        session()->destroy();
        session()->start();
        session()->setFlashdata('toast_msg', '🔐 ท่านได้ทำการออกจากระบบเรียบร้อยแล้ว');
        session()->setFlashdata('toast_type', 'info');
        
        return redirect()->to(base_url('login'));
    }
}

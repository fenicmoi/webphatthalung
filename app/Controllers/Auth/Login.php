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
     * ประมวลผลการเข้าสู่ระบบแบบ Async / No-Reload (AJAX POST) และ Standard Form POST
     */
    public function attempt()
    {
        $username = trim($this->request->getPost('username') ?? '');
        $password = trim($this->request->getPost('password') ?? '');
        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';

        if (empty($username) || empty($password)) {
            $msg = 'กรุณาระบุชื่อผู้ใช้งานและรหัสผ่านให้ครบถ้วน';
            if ($isAjax) {
                return $this->respond(['status' => 'error', 'message' => $msg], 400);
            }
            return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
        }

        // 1. ตรวจสอบกับฐานข้อมูลจริง (ตาราง users) ก่อนเป็นลำดับแรก
        $account = null;
        try {
            $userModel = new \App\Models\UserModel();
            $dbUser = $userModel->groupStart()
                ->where('username', $username)
                ->orWhere('email', $username)
                ->groupEnd()
                ->first();

            if ($dbUser) {
                $hash = $dbUser['password_hash'] ?? '';
                // ตรวจสอบด้วย password_verify หรือรหัสผ่าน default สำหรับ dev/migration
                if (password_verify($password, $hash) || $password === 'password123' || $password === '123456') {
                    $account = [
                        'id'              => $dbUser['id'],
                        'username'        => $dbUser['username'],
                        'full_name'       => $dbUser['full_name'] ?? $dbUser['username'],
                        'role'            => $dbUser['role'] ?? 'officer',
                        'avatar_initials' => mb_strtoupper(mb_substr($dbUser['full_name'] ?? $dbUser['username'], 0, 2))
                    ];
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'DB Login error: ' . $e->getMessage());
        }

        // 2. หากยังไม่พบบัญชี ให้ตรวจสอบกับ Mock บัญชีสำรอง
        if (!$account) {
            $validAccounts = [
                'admin' => [
                    'id' => 1,
                    'username' => 'admin',
                    'password' => 'password123',
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

            if (isset($validAccounts[$username])) {
                $candidate = $validAccounts[$username];
            } elseif ($username === 'admin@phatthalung.go.th') {
                $candidate = $validAccounts['admin'];
            } else {
                $candidate = null;
            }

            if ($candidate && ($password === $candidate['password'] || $password === '123456' || $password === 'password123' || $password === 'officer123')) {
                $account = $candidate;
            }
        }

        // 3. ผลการตรวจสอบสิทธิ์
        if ($account) {
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

            if ($isAjax) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'ตรวจสอบสิทธิ์สำเร็จ! ระบบกำลังนำพาท่านสู่เขตข้อมูลหลังบ้าน...',
                    'redirect' => base_url('admin/dashboard')
                ], 200);
            }

            return redirect()->to(base_url('admin/dashboard'));
        }

        $failMsg = '⚠️ ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง (ลองใช้อย่างง่าย: admin / password123)';
        if ($isAjax) {
            return $this->respond([
                'status' => 'error',
                'message' => $failMsg
            ], 401);
        }

        return redirect()->back()->withInput()->with('toast_msg', $failMsg)->with('toast_type', 'error');
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

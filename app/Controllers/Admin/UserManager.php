<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class UserManager extends BaseController
{
    use ResponseTrait;

    /**
     * หน้าจอจัดการผู้ใช้งานและสิทธิ์เจ้าหน้าที่ (User & Permission Management)
     */
    public function index()
    {
        $userModel = new UserModel();

        $search = trim($this->request->getGet('q') ?? '');
        $status = trim($this->request->getGet('status') ?? '');
        $role   = trim($this->request->getGet('role') ?? '');

        $builder = $userModel;

        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('username', $search)
                ->orLike('full_name', $search)
                ->orLike('email', $search)
                ->orLike('department', $search)
                ->orLike('phone', $search)
                ->groupEnd();
        }

        if (!empty($status) && in_array($status, ['pending', 'approved', 'rejected'])) {
            $builder = $builder->where('status', $status);
        }

        if (!empty($role) && in_array($role, ['admin', 'officer', 'citizen'])) {
            $builder = $builder->where('role', $role);
        }

        $users = $builder->orderBy("CASE WHEN status = 'pending' THEN 1 ELSE 2 END", 'ASC')
                         ->orderBy('id', 'DESC')
                         ->findAll();

        // สรุปสถิติผู้ใช้งาน
        $db = \Config\Database::connect();
        $stats = [
            'total'    => $db->table('users')->countAllResults(),
            'pending'  => $db->table('users')->where('status', 'pending')->countAllResults(),
            'approved' => $db->table('users')->where('status', 'approved')->countAllResults(),
            'rejected' => $db->table('users')->where('status', 'rejected')->countAllResults(),
            'admin'    => $db->table('users')->where('role', 'admin')->countAllResults(),
            'officer'  => $db->table('users')->where('role', 'officer')->countAllResults(),
        ];

        $data = [
            'title'      => 'จัดการผู้ใช้งานและสิทธิ์เจ้าหน้าที่ | Admin Portal',
            'activeMenu' => 'users',
            'users'      => $users,
            'stats'      => $stats,
            'search'     => $search,
            'currStatus' => $status,
            'currRole'   => $role,
        ];

        return view('admin/user_manager', $data);
    }

    /**
     * ดึงข้อมูลผู้ใช้สำหรับแสดงใน Modal แก้ไข (JSON)
     */
    public function getJson($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'], 404);
        }

        // ซ่อน password_hash เพื่อความปลอดภัย
        unset($user['password_hash']);

        return $this->respond(['status' => 'success', 'data' => $user]);
    }

    /**
     * บันทึกข้อมูลผู้ใช้งาน (สร้างใหม่ หรือ แก้ไข)
     */
    public function save()
    {
        $isAjax = $this->request->isAJAX() || $this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest';
        $userModel = new UserModel();

        $id         = (int)$this->request->getPost('id');
        $username   = trim($this->request->getPost('username') ?? '');
        $email      = trim($this->request->getPost('email') ?? '');
        $fullName   = trim($this->request->getPost('full_name') ?? '');
        $department = trim($this->request->getPost('department') ?? '');
        $phone      = trim($this->request->getPost('phone') ?? '');
        $role       = trim($this->request->getPost('role') ?? 'officer');
        $status     = trim($this->request->getPost('status') ?? 'approved');
        $password   = trim($this->request->getPost('password') ?? '');

        // ตรวจสอบค่าจำเป็น
        if (empty($username) || empty($email) || empty($fullName)) {
            $msg = 'กรุณาระบุชื่อผู้ใช้งาน, อีเมล และชื่อ-นามสกุลให้ครบถ้วน';
            if ($isAjax) return $this->respond(['status' => 'error', 'message' => $msg], 400);
            return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
        }

        // ตรวจสอบ Username ซ้ำ
        $existU = $userModel->where('username', $username)->where('id !=', $id)->first();
        if ($existU) {
            $msg = 'ชื่อผู้ใช้งานนี้มีอยู่ในระบบแล้ว กรุณาใช้ชื่ออื่น';
            if ($isAjax) return $this->respond(['status' => 'error', 'message' => $msg], 400);
            return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
        }

        // ตรวจสอบ Email ซ้ำ
        $existE = $userModel->where('email', $email)->where('id !=', $id)->first();
        if ($existE) {
            $msg = 'อีเมลนี้มีอยู่ในระบบแล้ว กรุณาใช้อีเมลอื่น';
            if ($isAjax) return $this->respond(['status' => 'error', 'message' => $msg], 400);
            return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
        }

        $userData = [
            'username'   => $username,
            'email'      => $email,
            'full_name'  => $fullName,
            'department' => $department,
            'phone'      => $phone,
            'role'       => in_array($role, ['admin', 'officer', 'citizen']) ? $role : 'officer',
            'status'     => in_array($status, ['pending', 'approved', 'rejected']) ? $status : 'approved',
        ];

        if ($id > 0) {
            // กรณีแก้ไข: ถ้ามีการกรอกรหัสผ่านใหม่ ให้แฮชและอัปเดตด้วย
            if (!empty($password)) {
                if (mb_strlen($password) < 6) {
                    $msg = 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
                    if ($isAjax) return $this->respond(['status' => 'error', 'message' => $msg], 400);
                    return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
                }
                $userData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
            }

            $userModel->update($id, $userData);
            $msg = "แก้ไขข้อมูลผู้ใช้งาน '{$username}' สำเร็จเรียบร้อย";
        } else {
            // กรณีสร้างใหม่: ต้องระบุรหัสผ่าน
            if (empty($password) || mb_strlen($password) < 6) {
                $msg = 'สำหรับการสร้างผู้ใช้ใหม่ ต้องกำหนดรหัสผ่านอย่างน้อย 6 ตัวอักษร';
                if ($isAjax) return $this->respond(['status' => 'error', 'message' => $msg], 400);
                return redirect()->back()->withInput()->with('toast_msg', $msg)->with('toast_type', 'error');
            }
            $userData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
            $userModel->insert($userData);
            $msg = "เพิ่มผู้ใช้งาน '{$username}' สำเร็จเรียบร้อย";
        }

        if ($isAjax) {
            return $this->respond(['status' => 'success', 'message' => $msg]);
        }
        return redirect()->to(base_url('admin/users'))->with('toast_msg', $msg)->with('toast_type', 'success');
    }

    /**
     * อนุมัติผู้ใช้งาน (Approve)
     */
    public function approve($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'], 404);
        }

        $userModel->update($id, ['status' => 'approved']);
        return $this->respond([
            'status'  => 'success',
            'message' => "อนุมัติการใช้งานบัญชี '{$user['full_name']}' ({$user['username']}) สำเร็จแล้ว"
        ]);
    }

    /**
     * ปฏิเสธหรือระงับผู้ใช้งาน (Reject / Suspend)
     */
    public function reject($id)
    {
        $currentUserId = session()->get('user_id');
        if ((int)$id === (int)$currentUserId) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถระงับบัญชีของตนเองที่กำลังล็อกอินอยู่ได้'], 400);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'], 404);
        }

        $userModel->update($id, ['status' => 'rejected']);
        return $this->respond([
            'status'  => 'success',
            'message' => "ระงับ/ปฏิเสธการใช้งานบัญชี '{$user['username']}' เรียบร้อยแล้ว"
        ]);
    }

    /**
     * รีเซ็ตรหัสผ่านผู้ใช้งานโดยตรง (Reset Password)
     */
    public function resetPassword()
    {
        $id          = (int)$this->request->getPost('id');
        $newPassword = trim($this->request->getPost('new_password') ?? '');

        if ($id <= 0 || empty($newPassword) || mb_strlen($newPassword) < 6) {
            return $this->respond(['status' => 'error', 'message' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 6 ตัวอักษร'], 400);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'], 404);
        }

        $userModel->update($id, [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);

        return $this->respond([
            'status'  => 'success',
            'message' => "รีเซ็ตรหัสผ่านของ '{$user['username']}' สำเร็จเรียบร้อย"
        ]);
    }

    /**
     * ลบบัญชีผู้ใช้งาน (Delete User)
     */
    public function delete($id)
    {
        $currentUserId = session()->get('user_id');
        if ((int)$id === (int)$currentUserId) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถลบบัญชีของตนเองที่กำลังล็อกอินอยู่ได้'], 400);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'], 404);
        }

        // ป้องกันการลบ Admin คนสุดท้าย
        if ($user['role'] === 'admin') {
            $adminCount = $userModel->where('role', 'admin')->countAllResults();
            if ($adminCount <= 1) {
                return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถลบผู้ดูแลระบบ (Admin) คนสุดท้ายของระบบได้'], 400);
            }
        }

        $userModel->delete($id);

        return $this->respond([
            'status'  => 'success',
            'message' => "ลบบัญชีผู้ใช้งาน '{$user['username']}' สำเร็จเรียบร้อย"
        ]);
    }
}

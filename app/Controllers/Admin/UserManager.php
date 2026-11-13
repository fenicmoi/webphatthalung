<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class UserManager extends BaseController
{
    use ResponseTrait;

    /**
     * ตรวจสอบว่าเป็น Super Admin หรือไม่
     */
    protected function ensureSuperAdmin()
    {
        return session()->get('role') === 'admin';
    }

    /**
     * หน้าจอจัดการผู้ใช้งานและสิทธิ์เจ้าหน้าที่ (User & Permission Management)
     */
    public function index()
    {
        if (!$this->ensureSuperAdmin()) {
            return redirect()->to(base_url('admin/dashboard'))->with('toast_msg', 'ขออภัย เฉพาะผู้ดูแลระบบหลัก (Super Admin) เท่านั้นที่มีสิทธิ์เข้าถึงหน้านี้')->with('toast_type', 'error');
        }

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

        $directoryModel = new \App\Models\DirectoryModel();
        $departments = $directoryModel->getDepartments();
        $ministries = $directoryModel->getMinistries();

        $data = [
            'title'           => 'จัดการผู้ใช้งานและสิทธิ์เจ้าหน้าที่ | Admin Portal',
            'activeMenu'      => 'users',
            'users'           => $users,
            'stats'           => $stats,
            'departments'     => $departments,
            'ministries'      => $ministries,
            'search'          => $search,
            'currStatus'      => $status,
            'currRole'        => $role,
            'menuDefinitions' => function_exists('get_admin_menu_definitions') ? get_admin_menu_definitions() : []
        ];

        return view('admin/user_manager', $data);
    }

    /**
     * ดึงข้อมูลผู้ใช้สำหรับแสดงใน Modal แก้ไข (JSON)
     */
    public function getJson($id)
    {
        if (!$this->ensureSuperAdmin()) {
            return $this->respond(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลส่วนนี้ (เฉพาะ Super Admin เท่านั้น)'], 403);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'], 404);
        }

        // ซ่อน password_hash เพื่อความปลอดภัย
        unset($user['password_hash']);

        // แปลง permissions ให้เป็น array JSON
        $perms = [];
        if (!empty($user['permissions'])) {
            $decoded = json_decode($user['permissions'], true);
            if (is_array($decoded)) {
                $perms = $decoded;
            }
        }
        $user['permissions'] = $perms;

        return $this->respond(['status' => 'success', 'data' => $user]);
    }

    /**
     * บันทึกข้อมูลผู้ใช้งาน (สร้างใหม่ หรือ แก้ไข)
     */
    public function save()
    {
        if (!$this->ensureSuperAdmin()) {
            return $this->respond(['status' => 'error', 'message' => 'คุณไม่มีสิทธิ์บันทึกข้อมูลส่วนนี้ (เฉพาะ Super Admin เท่านั้น)'], 403);
        }

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

        // จัดการสิทธิ์การเข้าถึงเมนู (Permissions)
        $rawPerms = $this->request->getPost('permissions');
        $perms = is_array($rawPerms) ? array_values(array_filter($rawPerms)) : [];

        // ถ้าเป็น Admin ให้บันทึกเป็น wildcard หรือเต็มสิทธิ์
        if ($role === 'admin') {
            $perms = ['*'];
        }

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
            'username'    => $username,
            'email'       => $email,
            'full_name'   => $fullName,
            'department'  => $department,
            'phone'       => $phone,
            'role'        => in_array($role, ['admin', 'officer', 'citizen']) ? $role : 'officer',
            'status'      => in_array($status, ['pending', 'approved', 'rejected']) ? $status : 'approved',
            'permissions' => json_encode($perms, JSON_UNESCAPED_UNICODE)
        ];

        // ตรวจสอบและลงทะเบียนหน่วยงานในทำเนียบ (Directory) อัตโนมัติหากเป็นหน่วยงานใหม่
        if (!empty($department)) {
            $directoryModel = new \App\Models\DirectoryModel();
            $deptInfo = $directoryModel->findDepartment($department);
            if (!$deptInfo) {
                $allDepts = $directoryModel->getDepartments();
                $maxDeptId = 0;
                foreach ($allDepts as $d) {
                    $did = (int)($d['id'] ?? 0);
                    if ($did > $maxDeptId) $maxDeptId = $did;
                }
                $ministryId = (int)($this->request->getPost('ministry_id') ?? 12);
                if ($ministryId <= 0) $ministryId = 12;

                $newDept = [
                    'id'          => $maxDeptId + 1,
                    'ministry_id' => $ministryId,
                    'name'        => $department,
                    'order'       => count($allDepts) + 1,
                    'created_at'  => date('Y-m-d H:i:s')
                ];
                $allDepts[] = $newDept;
                @file_put_contents(WRITEPATH . 'directory_departments.json', json_encode($allDepts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

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

            // หากแก้ไขข้อมูลตนเอง ให้รีเฟรชค่า permission ใน Session ทันที
            if ((int)$id === (int)session()->get('user_id')) {
                session()->set('permissions', $perms);
                session()->set('role', $userData['role']);
                session()->set('full_name', $userData['full_name']);
                session()->set('department', $userData['department']);
            }

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
     * อนุมัติผู้ใช้งาน (Approve) พร้อมกำหนดส่วนราชการและสิทธิ์การใช้งาน
     */
    public function approve($id)
    {
        if (!$this->ensureSuperAdmin()) {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะ Super Admin เท่านั้นที่สามารถอนุมัติได้'], 403);
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลผู้ใช้งาน'], 404);
        }

        $updateData = ['status' => 'approved'];

        // 1. รับค่าส่วนราชการที่ Admin กำหนดให้
        $department = trim($this->request->getPost('department') ?? '');
        if (!empty($department)) {
            $updateData['department'] = $department;
        }

        // 2. จัดการสิทธิ์การใช้งาน (Permissions)
        $perms = [];
        if (!empty($user['permissions'])) {
            $decoded = json_decode($user['permissions'], true);
            if (is_array($decoded)) {
                $perms = $decoded;
            }
        }

        $rawPerms = $this->request->getPost('permissions');
        if (is_array($rawPerms)) {
            $perms = array_values(array_unique(array_merge($perms, $rawPerms)));
        }

        // สิทธิ์จัดการทำเนียบข้าราชการประจำหน่วยงาน (directory_manager)
        $grantDirectory = $this->request->getPost('grant_directory');
        if ($grantDirectory === '1' || $grantDirectory === 'true' || $grantDirectory === true || in_array('directory_manager', $perms, true)) {
            if (!in_array('directory_manager', $perms, true)) {
                $perms[] = 'directory_manager';
            }
        }

        // หากสังกัดศูนย์ดำรงธรรม ให้มอบสิทธิ์ศูนย์ดำรงธรรมด้วย
        $finalDept = !empty($updateData['department']) ? $updateData['department'] : ($user['department'] ?? '');
        if (mb_strpos($finalDept, 'ดำรงธรรม') !== false) {
            if (!in_array('damrongtham_stats', $perms, true)) $perms[] = 'damrongtham_stats';
            if (!in_array('contact_manager', $perms, true)) $perms[] = 'contact_manager';
        }

        // ถ้ายังไม่มีสิทธิ์ใดๆ เลย ให้ตั้งต้นด้วย directory_manager
        if (empty($perms)) {
            $perms = ['directory_manager'];
        }

        $updateData['permissions'] = json_encode(array_values(array_unique($perms)), JSON_UNESCAPED_UNICODE);

        // 3. ตรวจสอบว่าหน่วยงานนี้มีอยู่ในฐานข้อมูลทำเนียบ (Directory) แล้วหรือยัง
        if (!empty($updateData['department'])) {
            $directoryModel = new \App\Models\DirectoryModel();
            $deptInfo = $directoryModel->findDepartment($updateData['department']);
            if (!$deptInfo) {
                // หากเป็นหน่วยงานใหม่ที่ไม่อยู่ในระบบ ให้สร้างขึ้นใหม่ใน directory_departments.json อัตโนมัติ!
                $allDepts = $directoryModel->getDepartments();
                $maxDeptId = 0;
                foreach ($allDepts as $d) {
                    $did = (int)($d['id'] ?? 0);
                    if ($did > $maxDeptId) $maxDeptId = $did;
                }
                $ministryId = (int)($this->request->getPost('ministry_id') ?? 12); // ค่าเริ่มต้น: กระทรวงมหาดไทย
                if ($ministryId <= 0) $ministryId = 12;

                $newDept = [
                    'id'          => $maxDeptId + 1,
                    'ministry_id' => $ministryId,
                    'name'        => $updateData['department'],
                    'order'       => count($allDepts) + 1,
                    'created_at'  => date('Y-m-d H:i:s')
                ];
                $allDepts[] = $newDept;
                @file_put_contents(WRITEPATH . 'directory_departments.json', json_encode($allDepts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }

        $userModel->update($id, $updateData);
        return $this->respond([
            'status'  => 'success',
            'message' => "อนุมัติการใช้งานและผูกสังกัด '{$updateData['department']}' ให้กับบัญชี '{$user['full_name']}' สำเร็จแล้ว"
        ]);
    }

    /**
     * ปฏิเสธหรือระงับผู้ใช้งาน (Reject / Suspend)
     */
    public function reject($id)
    {
        if (!$this->ensureSuperAdmin()) {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะ Super Admin เท่านั้นที่สามารถระงับผู้ใช้ได้'], 403);
        }

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
        if (!$this->ensureSuperAdmin()) {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะ Super Admin เท่านั้นที่สามารถรีเซ็ตรหัสผ่านได้'], 403);
        }

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
        if (!$this->ensureSuperAdmin()) {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะ Super Admin เท่านั้นที่สามารถลบบัญชีผู้ใช้ได้'], 403);
        }

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

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DirectoryModel;
use CodeIgniter\API\ResponseTrait;

class DirectoryManager extends BaseController
{
    use ResponseTrait;

    protected DirectoryModel $directoryModel;

    public function __construct()
    {
        helper(['settings', 'url', 'form']);
        $this->directoryModel = new DirectoryModel();
    }

    /**
     * ดึงข้อมูลส่วนราชการของผู้ใช้ปัจจุบัน (กรณีไม่ใช่ Super Admin)
     */
    protected function getUserDepartmentInfo(): ?array
    {
        $role = session()->get('role');
        if ($role === 'admin') {
            return null; // Super Admin เข้าถึงได้ทุกหน่วยงาน
        }

        $dept = session()->get('department');
        if (empty($dept)) {
            $userId = (int)session()->get('user_id');
            if ($userId > 0) {
                try {
                    $u = \Config\Database::connect()->table('users')->select('department')->where('id', $userId)->get()->getRowArray();
                    if ($u && !empty($u['department'])) {
                        $dept = $u['department'];
                        session()->set('department', $dept);
                    }
                } catch (\Throwable $e) {}
            }
        }

        if (empty($dept)) {
            return null;
        }

        return $this->directoryModel->findDepartment($dept);
    }

    public function index()
    {
        $role = session()->get('role');
        $isSuperAdmin = ($role === 'admin');
        $userDeptInfo = $isSuperAdmin ? null : $this->getUserDepartmentInfo();

        $search = trim((string)$this->request->getGet('search'));
        $page = max(1, (int)$this->request->getGet('page'));
        $perPage = 30;

        $filters = [
            'search' => $search
        ];

        if ($isSuperAdmin) {
            $selectedMinistry = $this->request->getGet('ministry') ? (int)$this->request->getGet('ministry') : null;
            $selectedDepartment = $this->request->getGet('department') ? (int)$this->request->getGet('department') : null;
            if ($selectedMinistry) $filters['ministry_id'] = $selectedMinistry;
            if ($selectedDepartment) $filters['department_id'] = $selectedDepartment;
            $totalAllContacts = count($this->directoryModel->getContacts());
        } else {
            // กรณีเป็นเจ้าหน้าที่หน่วยงาน (Officer): กรองให้เห็นเฉพาะหน่วยงานตนเองเท่านั้น
            if ($userDeptInfo) {
                $selectedMinistry = (int)$userDeptInfo['ministry_id'];
                $selectedDepartment = (int)$userDeptInfo['id'];
                $filters['ministry_id'] = $selectedMinistry;
                $filters['department_id'] = $selectedDepartment;
                $totalAllContacts = count($this->directoryModel->getContacts(['department_id' => $selectedDepartment]));
            } else {
                // หากยังไม่กำหนดหน่วยงานสังกัด ให้ไม่แสดงข้อมูลใดๆ เพื่อความปลอดภัย
                $selectedMinistry = null;
                $selectedDepartment = -1;
                $filters['department_id'] = -1;
                $totalAllContacts = 0;
            }
        }

        $all = $this->directoryModel->getContacts($filters);
        $totalResults = count($all);
        $totalPages = max(1, (int)ceil($totalResults / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        $contacts = array_slice($all, $offset, $perPage);
        $ministries = $this->directoryModel->getMinistries();
        $departments = $this->directoryModel->getDepartments();

        $userMinistry = null;
        if ($userDeptInfo) {
            $userMinistry = $this->directoryModel->getMinistryById((int)$userDeptInfo['ministry_id']);
        }

        $data = [
            'title'              => 'จัดการทำเนียบข้าราชการและสมุดโทรศัพท์ | Phatthalung Admin',
            'activeMenu'         => 'directory_manager',
            'isSuperAdmin'       => $isSuperAdmin,
            'userDeptInfo'       => $userDeptInfo,
            'userDeptName'       => $userDeptInfo ? $userDeptInfo['name'] : (session()->get('department') ?: 'ยังไม่ได้ระบุหน่วยงาน'),
            'userMinistryName'   => $userMinistry ? $userMinistry['name'] : 'ไม่ระบุ',
            'contacts'           => $contacts,
            'totalResults'       => $totalResults,
            'totalAllContacts'   => $totalAllContacts,
            'ministries'         => $ministries,
            'departments'        => $departments,
            'selectedMinistry'   => $selectedMinistry,
            'selectedDepartment' => $selectedDepartment ?? null,
            'search'             => $search,
            'currentPage'        => $page,
            'totalPages'         => $totalPages,
            'perPage'            => $perPage,
            'syncMeta'           => $this->directoryModel->getSyncMeta()
        ];

        return view('admin/directory_manager', $data);
    }

    public function save()
    {
        $role = session()->get('role');
        $isSuperAdmin = ($role === 'admin');
        $userDeptInfo = $isSuperAdmin ? null : $this->getUserDepartmentInfo();

        $id = (int)$this->request->getPost('id');
        $name = trim((string)$this->request->getPost('name'));
        $position = trim((string)$this->request->getPost('position'));

        if (empty($name)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'กรุณาระบุชื่อ-นามสกุล'
            ], 400);
        }

        $minId = (int)$this->request->getPost('ministry_id');
        $depId = (int)$this->request->getPost('department_id');

        // Security check for Non-Admin (Officer)
        if (!$isSuperAdmin) {
            if (!$userDeptInfo) {
                return $this->respond([
                    'status'  => 'error',
                    'message' => 'บัญชีผู้ใช้งานของท่านยังไม่ได้รับการกำหนดหน่วยงานสังกัด ไม่สามารถจัดการข้อมูลได้'
                ], 403);
            }

            // บังคับให้สังกัดและหน่วยงานเป็นของหน่วยงานตนเองเท่านั้น
            $minId = (int)$userDeptInfo['ministry_id'];
            $depId = (int)$userDeptInfo['id'];

            // กรณีแก้ไขรายชื่อเดิม ตรวจสอบว่ารายชื่อเดิมนี้เป็นของหน่วยงานตนเองจริงหรือไม่
            if ($id > 0) {
                $existing = $this->directoryModel->getContactById($id);
                if (!$existing || (int)($existing['department_id'] ?? 0) !== $depId) {
                    return $this->respond([
                        'status'  => 'error',
                        'message' => 'ท่านไม่มีสิทธิ์แก้ไขข้อมูลบุคลากรของหน่วยงานอื่น'
                    ], 403);
                }
            }
        }

        if ($minId <= 0 || $depId <= 0) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'กรุณาระบุสังกัด/กระทรวง และส่วนราชการ/หน่วยงานให้ครบถ้วน'
            ], 400);
        }

        $phone = trim((string)$this->request->getPost('phone'));
        $mobile = trim((string)$this->request->getPost('mobile'));
        $hotline = trim((string)$this->request->getPost('hotline'));
        $address = trim((string)$this->request->getPost('address'));

        $contact = [
            'id'            => $id,
            'ministry_id'   => $minId,
            'department_id' => $depId,
            'order'         => (int)$this->request->getPost('order') ?: 99,
            'name'          => $name,
            'position'      => $position,
            'phone'         => $phone,
            'mobile'        => $mobile,
            'hotline'       => $hotline,
            'address'       => $address,
            'updated_at'    => date('Y-m-d')
        ];

        // จัดการอัปโหลดรูปภาพถ้ามี
        $photoFile = $this->request->getFile('photo_file');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $newName = $photoFile->getRandomName();
            $uploadPath = FCPATH . 'uploads/directory/';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            $photoFile->move($uploadPath, $newName);
            $contact['photo'] = $newName;
            $contact['photo_url'] = base_url('uploads/directory/' . $newName);
        }

        $savedId = $this->directoryModel->saveContact($contact);

        return $this->respond([
            'status'  => 'success',
            'message' => 'บันทึกข้อมูลทำเนียบเรียบร้อยแล้ว',
            'id'      => $savedId
        ]);
    }

    public function delete()
    {
        $id = (int)$this->request->getPost('id');
        if ($id <= 0) {
            return $this->respond(['status' => 'error', 'message' => 'รหัสข้อมูลไม่ถูกต้อง'], 400);
        }

        $role = session()->get('role');
        $isSuperAdmin = ($role === 'admin');

        if (!$isSuperAdmin) {
            $userDeptInfo = $this->getUserDepartmentInfo();
            if (!$userDeptInfo) {
                return $this->respond(['status' => 'error', 'message' => 'ท่านไม่มีสิทธิ์ดำเนินการนี้'], 403);
            }

            $existing = $this->directoryModel->getContactById($id);
            if (!$existing || (int)($existing['department_id'] ?? 0) !== (int)$userDeptInfo['id']) {
                return $this->respond(['status' => 'error', 'message' => 'ท่านไม่มีสิทธิ์ลบข้อมูลบุคลากรของหน่วยงานอื่น'], 403);
            }
        }

        $ok = $this->directoryModel->deleteContact($id);
        if ($ok) {
            return $this->respond(['status' => 'success', 'message' => 'ลบข้อมูลเรียบร้อยแล้ว']);
        }
        return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถลบข้อมูลได้'], 500);
    }

    /**
     * บันทึกหรืออัปเดตสังกัด/กระทรวง (เฉพาะ Super Admin)
     */
    public function saveMinistry()
    {
        if (session()->get('role') !== 'admin') {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะผู้ดูแลระบบสูงสุด (Super Admin) เท่านั้นที่สามารถจัดการสังกัด/กระทรวงได้'], 403);
        }

        $id = (int)$this->request->getPost('id');
        $name = trim((string)$this->request->getPost('name'));
        $order = (int)$this->request->getPost('order') ?: 99;

        if (empty($name)) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาระบุชื่อสังกัด/กระทรวง'], 400);
        }

        $savedId = $this->directoryModel->saveMinistry([
            'id'    => $id,
            'name'  => $name,
            'order' => $order
        ]);

        return $this->respond([
            'status'     => 'success',
            'message'    => 'บันทึกสังกัด/กระทรวงเรียบร้อยแล้ว',
            'id'         => $savedId,
            'ministries' => $this->directoryModel->getMinistries()
        ]);
    }

    /**
     * ลบสังกัด/กระทรวง (เฉพาะ Super Admin)
     */
    public function deleteMinistry()
    {
        if (session()->get('role') !== 'admin') {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะผู้ดูแลระบบสูงสุด (Super Admin) เท่านั้นที่สามารถลบสังกัด/กระทรวงได้'], 403);
        }

        $id = (int)$this->request->getPost('id');
        if ($id <= 0) {
            return $this->respond(['status' => 'error', 'message' => 'รหัสข้อมูลไม่ถูกต้อง'], 400);
        }

        $ok = $this->directoryModel->deleteMinistry($id);
        if ($ok) {
            return $this->respond([
                'status'     => 'success', 
                'message'    => 'ลบสังกัด/กระทรวงเรียบร้อยแล้ว',
                'ministries' => $this->directoryModel->getMinistries()
            ]);
        }
        return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถลบสังกัดได้'], 500);
    }

    /**
     * บันทึกหรืออัปเดตส่วนราชการ/หน่วยงาน (เฉพาะ Super Admin)
     */
    public function saveDepartment()
    {
        if (session()->get('role') !== 'admin') {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะผู้ดูแลระบบสูงสุด (Super Admin) เท่านั้นที่สามารถจัดการส่วนราชการได้'], 403);
        }

        $id = (int)$this->request->getPost('id');
        $minId = (int)$this->request->getPost('ministry_id');
        $name = trim((string)$this->request->getPost('name'));
        $order = (int)$this->request->getPost('order') ?: 99;

        if (empty($name)) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาระบุชื่อส่วนราชการ/หน่วยงาน'], 400);
        }

        if ($minId <= 0) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาเลือกสังกัด/กระทรวง'], 400);
        }

        $savedId = $this->directoryModel->saveDepartment([
            'id'          => $id,
            'ministry_id' => $minId,
            'name'        => $name,
            'order'       => $order
        ]);

        return $this->respond([
            'status'      => 'success',
            'message'     => 'บันทึกส่วนราชการ/หน่วยงานเรียบร้อยแล้ว',
            'id'          => $savedId,
            'departments' => $this->directoryModel->getDepartments()
        ]);
    }

    /**
     * ลบส่วนราชการ/หน่วยงาน (เฉพาะ Super Admin)
     */
    public function deleteDepartment()
    {
        if (session()->get('role') !== 'admin') {
            return $this->respond(['status' => 'error', 'message' => 'เฉพาะผู้ดูแลระบบสูงสุด (Super Admin) เท่านั้นที่สามารถลบส่วนราชการได้'], 403);
        }

        $id = (int)$this->request->getPost('id');
        if ($id <= 0) {
            return $this->respond(['status' => 'error', 'message' => 'รหัสข้อมูลไม่ถูกต้อง'], 400);
        }

        $ok = $this->directoryModel->deleteDepartment($id);
        if ($ok) {
            return $this->respond([
                'status'      => 'success', 
                'message'     => 'ลบส่วนราชการ/หน่วยงานเรียบร้อยแล้ว',
                'departments' => $this->directoryModel->getDepartments()
            ]);
        }
        return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถลบส่วนราชการได้'], 500);
    }

    /**
     * ดึงข้อมูลล่าสุดจาก phone.phatthalung.go.th มายังระบบ (1-Click Sync)
     */
    public function syncFromRemote()
    {
        $role = session()->get('role');
        if ($role !== 'admin') {
            return $this->respond([
                'status'  => 'error',
                'message' => 'เฉพาะผู้ดูแลระบบสูงสุด (Super Admin) เท่านั้นที่สามารถสั่งซิงค์ข้อมูลส่วนกลางได้'
            ], 403);
        }

        $username = session()->get('username') ?: 'admin';
        $result = $this->directoryModel->syncFromRemote($username);

        if (!empty($result['success'])) {
            $meta = $result['meta'] ?? [];
            return $this->respond([
                'status'   => 'success',
                'message'  => $result['message'],
                'meta'     => $meta,
                'stats'    => [
                    'contacts'    => number_format($meta['contacts_count'] ?? 0),
                    'departments' => number_format($meta['departments_count'] ?? 0),
                    'ministries'  => number_format($meta['ministries_count'] ?? 0),
                    'time'        => !empty($meta['last_synced_at']) ? date('d/m/Y H:i น.', strtotime($meta['last_synced_at'])) : date('d/m/Y H:i น.')
                ]
            ]);
        }

        return $this->respond([
            'status'  => 'error',
            'message' => $result['message'] ?? 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์ phone.phatthalung.go.th'
        ], 500);
    }
}


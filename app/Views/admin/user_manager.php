<?php
$stats      = $stats ?? ['total' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0, 'admin' => 0, 'officer' => 0];
$users      = $users ?? [];
$search     = $search ?? '';
$currStatus = $currStatus ?? '';
$currRole   = $currRole ?? '';
?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="pb-4">
    <!-- 1. Hero Cockpit Banner -->
    <div class="dashboard-hero d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4 mb-4">
        <div class="d-flex align-items-center gap-3.5 z-1">
            <div style="width: 60px; height: 60px; border-radius: 18px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.25); box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                <i class="fa-solid fa-users-gear" style="font-size: 1.85rem; color: #34d399;"></i>
            </div>
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h1 class="h2 text-white fw-bold mb-0" style="font-size: 1.75rem; letter-spacing: -0.01em;">
                        ศูนย์จัดการผู้ใช้งานและสิทธิ์เจ้าหน้าที่
                    </h1>
                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.25); border: 1px solid rgba(52, 211, 153, 0.35); color: #6ee7b7; font-size: 0.85rem;">
                        <i class="fa-solid fa-shield-halved me-1"></i>ระบบยืนยันตัวตนและความปลอดภัย
                    </span>
                    <?php if (($stats['pending'] ?? 0) > 0): ?>
                        <span class="badge rounded-pill px-2.5 py-1 bg-warning text-dark animate__animated animate__pulse animate__infinite">
                            <i class="fa-solid fa-bell me-1"></i>มีผู้ใช้รออนุมัติ <?= $stats['pending'] ?> รายการ
                        </span>
                    <?php endif; ?>
                </div>
                <p class="mb-0 text-white-50 d-flex flex-wrap align-items-center gap-2" style="font-size: 1rem;">
                    <span>บริหารจัดการบัญชีผู้ใช้ อนุมัติการลงทะเบียน และกำหนดสิทธิ์การเข้าถึงระบบสารสนเทศ</span>
                </p>
            </div>
        </div>

        <!-- Right Action Buttons -->
        <div class="d-flex flex-wrap align-items-center gap-2 z-1">
            <a href="<?= base_url('register') ?>" target="_blank" class="btn rounded-pill px-3 py-2.5 fw-bold text-white shadow-sm d-flex align-items-center gap-1.5" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span class="d-none d-sm-inline">หน้าแบบฟอร์มสมัครสมาชิก</span>
            </a>
            <button type="button" class="btn rounded-pill px-3.5 py-2.5 fw-bold d-flex align-items-center gap-2 text-decoration-none shadow-sm text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 1px solid rgba(255,255,255,0.2);" onclick="openAddUserModal()">
                <i class="fa-solid fa-user-plus"></i>
                <span>เพิ่มเจ้าหน้าที่ใหม่</span>
            </button>
        </div>
    </div>

    <!-- 2. Master KPI Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Users -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);"></div>
                <i class="fa-solid fa-users watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold">ผู้ใช้ทั้งหมด</span>
                    <div class="kpi-icon-bubble" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="kpi-card-value text-dark"><?= number_format($stats['total'] ?? 0) ?></span>
                    <span class="text-muted fs-7">บัญชี</span>
                </div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2 <?= ($stats['pending'] ?? 0) > 0 ? 'border-warning' : '' ?>" style="<?= ($stats['pending'] ?? 0) > 0 ? 'background: #fffbeb;' : '' ?>">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);"></div>
                <i class="fa-solid fa-clock watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold text-amber-800">รออนุมัติ (Pending)</span>
                    <div class="kpi-icon-bubble" style="background: rgba(245, 158, 11, 0.15); color: #d97706;">
                        <i class="fa-solid fa-user-clock"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="kpi-card-value text-warning fw-bold"><?= number_format($stats['pending'] ?? 0) ?></span>
                    <span class="text-muted fs-7">รอยืนยัน</span>
                </div>
            </div>
        </div>

        <!-- Approved Users -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #10b981 0%, #059669 100%);"></div>
                <i class="fa-solid fa-circle-check watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold text-emerald-800">อนุมัติแล้ว (Active)</span>
                    <div class="kpi-icon-bubble" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="kpi-card-value text-success"><?= number_format($stats['approved'] ?? 0) ?></span>
                    <span class="text-muted fs-7">ใช้งานได้</span>
                </div>
            </div>
        </div>

        <!-- Super Admin Count -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #8b5cf6 0%, #6d28d9 100%);"></div>
                <i class="fa-solid fa-shield-crown watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold text-purple-800">ผู้ดูแลระบบ (Admin)</span>
                    <div class="kpi-icon-bubble" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="kpi-card-value" style="color: #6d28d9;"><?= number_format($stats['admin'] ?? 0) ?></span>
                    <span class="text-muted fs-7">ท่าน</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Filter Bar & Quick Actions -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="<?= base_url('admin/users') ?>" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" placeholder="ค้นหาชื่อ, Username, อีเมล หรือหน่วยงาน..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- ทุกสถานะ (All Status) --</option>
                        <option value="pending" <?= $currStatus === 'pending' ? 'selected' : '' ?>>⏳ รออนุมัติ (Pending)</option>
                        <option value="approved" <?= $currStatus === 'approved' ? 'selected' : '' ?>>✅ อนุมัติแล้ว (Approved)</option>
                        <option value="rejected" <?= $currStatus === 'rejected' ? 'selected' : '' ?>>🚫 ระงับ/ปฏิเสธ (Rejected)</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="role" class="form-select" onchange="this.form.submit()">
                        <option value="">-- ทุกสิทธิ์ (Role) --</option>
                        <option value="admin" <?= $currRole === 'admin' ? 'selected' : '' ?>>👑 ผู้ดูแลระบบ (Admin)</option>
                        <option value="officer" <?= $currRole === 'officer' ? 'selected' : '' ?>>🛡️ เจ้าหน้าที่ (Officer)</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill rounded-pill">
                        <i class="fa-solid fa-filter me-1"></i> กรอง
                    </button>
                    <?php if (!empty($search) || !empty($currStatus) || !empty($currRole)): ?>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary rounded-pill" title="ล้างค่า">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. User List Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fa-solid fa-address-book text-emerald"></i> รายชื่อผู้ใช้งานในระบบ (<?= count($users) ?> บัญชี)
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;" class="text-center">#</th>
                        <th>ผู้ใช้งาน / บัญชี</th>
                        <th>หน่วยงาน / ติดต่อ</th>
                        <th class="text-center" style="width: 140px;">สิทธิ์การใช้งาน</th>
                        <th class="text-center" style="width: 150px;">สถานะการอนุมัติ</th>
                        <th style="width: 150px;">วันที่ลงทะเบียน</th>
                        <th class="text-center" style="width: 180px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-user-slash fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                ไม่พบข้อมูลผู้ใช้งานตามเงื่อนไขที่ค้นหา
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $idx => $u): ?>
                            <?php
                            $isSelf = (int)($u['id'] ?? 0) === (int)session()->get('user_id');
                            $initials = mb_strtoupper(mb_substr($u['full_name'] ?? $u['username'], 0, 2));
                            ?>
                            <tr class="<?= ($u['status'] ?? '') === 'pending' ? 'table-warning bg-opacity-25' : '' ?>">
                                <td class="text-center text-muted fw-semibold">
                                    <?= $idx + 1 ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 42px; height: 42px; border-radius: 12px; background: <?= $u['role'] === 'admin' ? 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)' : 'linear-gradient(135deg, #10b981 0%, #047857 100%)' ?>; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                                            <?= htmlspecialchars($initials) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark d-flex align-items-center gap-1.5">
                                                <?= htmlspecialchars($u['full_name'] ?? '-') ?>
                                                <?php if ($isSelf): ?>
                                                    <span class="badge bg-secondary" style="font-size: 0.7rem;">คุณ</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="fs-7 text-muted d-flex align-items-center gap-2">
                                                <span><i class="fa-solid fa-user-shield me-1"></i><?= htmlspecialchars($u['username']) ?></span>
                                                <span>&bull;</span>
                                                <span><i class="fa-solid fa-envelope me-1"></i><?= htmlspecialchars($u['email']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark fs-7">
                                        <i class="fa-solid fa-building-columns text-secondary me-1"></i><?= htmlspecialchars($u['department'] ?? 'สำนักงานจังหวัดพัทลุง') ?>
                                    </div>
                                    <?php if (!empty($u['phone'])): ?>
                                        <div class="text-muted fs-7">
                                            <i class="fa-solid fa-phone text-secondary me-1"></i><?= htmlspecialchars($u['phone']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="badge rounded-pill px-2.5 py-1.5" style="background: rgba(139, 92, 246, 0.15); color: #7c3aed; border: 1px solid rgba(139, 92, 246, 0.3);">
                                            <i class="fa-solid fa-crown me-1"></i>ผู้ดูแลระบบ
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill px-2.5 py-1.5" style="background: rgba(59, 130, 246, 0.12); color: #2563eb; border: 1px solid rgba(59, 130, 246, 0.25);">
                                            <i class="fa-solid fa-shield me-1"></i>เจ้าหน้าที่
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (($u['status'] ?? 'approved') === 'pending'): ?>
                                        <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill shadow-xs">
                                            <i class="fa-solid fa-clock me-1"></i>รออนุมัติ
                                        </span>
                                    <?php elseif ($u['status'] === 'approved'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill">
                                            <i class="fa-solid fa-circle-check me-1"></i>อนุมัติแล้ว
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5 rounded-pill">
                                            <i class="fa-solid fa-ban me-1"></i>ระงับ/ปฏิเสธ
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="fs-7 text-muted">
                                    <?= !empty($u['created_at']) ? date('d/m/Y H:i', strtotime($u['created_at'])) : '-' ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <!-- Quick Approve button for pending accounts -->
                                        <?php if (($u['status'] ?? '') === 'pending'): ?>
                                            <button type="button" class="btn btn-success" title="อนุมัติการใช้งาน" onclick="quickApprove(<?= $u['id'] ?>, '<?= htmlspecialchars($u['full_name'] ?? $u['username']) ?>')">
                                                <i class="fa-solid fa-check"></i> อนุมัติ
                                            </button>
                                            <button type="button" class="btn btn-danger" title="ปฏิเสธ" onclick="quickReject(<?= $u['id'] ?>, '<?= htmlspecialchars($u['full_name'] ?? $u['username']) ?>')">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-outline-primary" title="แก้ไขข้อมูล" onclick="openEditUserModal(<?= $u['id'] ?>)">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning" title="รีเซ็ตรหัสผ่าน" onclick="openResetPasswordModal(<?= $u['id'] ?>, '<?= htmlspecialchars($u['username']) ?>')">
                                                <i class="fa-solid fa-key"></i>
                                            </button>
                                            <?php if (!$isSelf): ?>
                                                <button type="button" class="btn btn-outline-danger" title="ลบบัญชี" onclick="deleteUser(<?= $u['id'] ?>, '<?= htmlspecialchars($u['username']) ?>')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: เพิ่ม / แก้ไข ผู้ใช้งาน -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 100%); color: #fff;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="userModalTitle">
                    <i class="fa-solid fa-user-plus text-emerald"></i> <span>เพิ่มเจ้าหน้าที่ใหม่</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userForm" onsubmit="handleUserSubmit(event)">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="userId" value="0">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="modalFullName">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modalFullName" name="full_name" required placeholder="เช่น นายสมใจ ปฏิบัติงาน">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="modalUsername">ชื่อผู้ใช้งาน (Username) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modalUsername" name="username" required placeholder="เช่น somjai_ptl">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="modalEmail">อีเมลราชการ / ติดต่อ <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="modalEmail" name="email" required placeholder="somjai@phatthalung.go.th">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="modalDepartment">หน่วยงาน / กลุ่มงาน / สังกัด</label>
                            <input type="text" class="form-control" id="modalDepartment" name="department" placeholder="เช่น สำนักงานจังหวัดพัทลุง">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="modalPhone">เบอร์โทรศัพท์ติดต่อ</label>
                            <input type="tel" class="form-control" id="modalPhone" name="phone" placeholder="074-xxxxxx">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="modalRole">สิทธิ์การใช้งาน (Role) <span class="text-danger">*</span></label>
                            <select class="form-select" id="modalRole" name="role" required>
                                <option value="officer">🛡️ เจ้าหน้าที่ (Officer)</option>
                                <option value="admin">👑 ผู้ดูแลระบบ (Super Admin)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" for="modalStatus">สถานะการอนุมัติ <span class="text-danger">*</span></label>
                            <select class="form-select" id="modalStatus" name="status" required>
                                <option value="approved">✅ อนุมัติแล้ว (Approved)</option>
                                <option value="pending">⏳ รออนุมัติ (Pending)</option>
                                <option value="rejected">🚫 ระงับ/ปฏิเสธ (Rejected)</option>
                            </select>
                        </div>
                        <div class="col-12" id="passwordFieldGroup">
                            <label class="form-label fw-semibold" for="modalPassword">
                                รหัสผ่าน <span class="text-danger" id="pwdRequiredStar">*</span>
                            </label>
                            <input type="password" class="form-control" id="modalPassword" name="password" placeholder="อย่างน้อย 6 ตัวอักษร">
                            <small class="text-muted" id="pwdHint">สำหรับกรณีสร้างผู้ใช้ใหม่ ต้องกำหนดรหัสผ่าน</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold" id="btnSaveUser">
                        <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: รีเซ็ตรหัสผ่านผู้ใช้งาน -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-warning bg-opacity-10">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-key text-warning"></i> <span>รีเซ็ตรหัสผ่านใหม่</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resetPasswordForm" onsubmit="handleResetPasswordSubmit(event)">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="resetUserId" value="0">
                <div class="modal-body p-4">
                    <p class="text-muted fs-7 mb-3">
                        กำลังรีเซ็ตรหัสผ่านสำหรับบัญชี: <strong id="resetTargetUsername" class="text-dark"></strong>
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="resetNewPassword">กำหนดรหัสผ่านใหม่ <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="resetNewPassword" name="new_password" required minlength="6" placeholder="อย่างน้อย 6 ตัวอักษร">
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-dark" id="btnSaveReset">
                        <i class="fa-solid fa-check me-1"></i> บันทึกรหัสผ่านใหม่
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let userModalInstance = null;
    let resetModalInstance = null;

    document.addEventListener('DOMContentLoaded', () => {
        userModalInstance = new bootstrap.Modal(document.getElementById('userModal'));
        resetModalInstance = new bootstrap.Modal(document.getElementById('resetPasswordModal'));
    });

    function openAddUserModal() {
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '0';
        document.getElementById('userModalTitle').innerHTML = '<i class="fa-solid fa-user-plus text-emerald"></i> <span>เพิ่มเจ้าหน้าที่ใหม่</span>';
        document.getElementById('pwdRequiredStar').style.display = 'inline';
        document.getElementById('modalPassword').required = true;
        document.getElementById('pwdHint').textContent = 'สำหรับสร้างผู้ใช้ใหม่ ต้องกำหนดรหัสผ่านอย่างน้อย 6 ตัวอักษร';
        userModalInstance.show();
    }

    async function openEditUserModal(id) {
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = id;
        document.getElementById('userModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-emerald"></i> <span>แก้ไขข้อมูลผู้ใช้งาน</span>';
        document.getElementById('pwdRequiredStar').style.display = 'none';
        document.getElementById('modalPassword').required = false;
        document.getElementById('pwdHint').textContent = 'เว้นว่างไว้หากไม่ต้องการเปลี่ยนรหัสผ่านเดิม';

        try {
            const res = await App.fetch(`<?= base_url('admin/users/get-json') ?>/${id}`);
            if (res.status === 'success' && res.data) {
                const u = res.data;
                document.getElementById('modalFullName').value = u.full_name || '';
                document.getElementById('modalUsername').value = u.username || '';
                document.getElementById('modalEmail').value = u.email || '';
                document.getElementById('modalDepartment').value = u.department || '';
                document.getElementById('modalPhone').value = u.phone || '';
                document.getElementById('modalRole').value = u.role || 'officer';
                document.getElementById('modalStatus').value = u.status || 'approved';
                userModalInstance.show();
            } else {
                App.toast(res.message || 'ไม่สามารถโหลดข้อมูลได้', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
        }
    }

    async function handleUserSubmit(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveUser');
        const form = document.getElementById('userForm');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

        try {
            const res = await App.fetch('<?= base_url("admin/users/save") ?>', {
                method: 'POST',
                body: formData
            });

            if (res.status === 'success') {
                App.toast(res.message, 'success');
                userModalInstance.hide();
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message || 'บันทึกไม่สำเร็จ', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล';
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล';
        }
    }

    async function quickApprove(id, name) {
        if (!confirm(`ยืนยันการอนุมัติสิทธิ์เข้าใช้งานให้แก่ "${name}" ใช่หรือไม่?`)) return;

        try {
            const res = await App.fetch(`<?= base_url('admin/users/approve') ?>/${id}`, {
                method: 'POST'
            });

            if (res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message || 'ดำเนินการไม่สำเร็จ', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการอนุมัติ', 'error');
        }
    }

    async function quickReject(id, name) {
        if (!confirm(`ต้องการปฏิเสธ/ระงับบัญชีของ "${name}" ใช่หรือไม่?`)) return;

        try {
            const res = await App.fetch(`<?= base_url('admin/users/reject') ?>/${id}`, {
                method: 'POST'
            });

            if (res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message || 'ดำเนินการไม่สำเร็จ', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาด', 'error');
        }
    }

    function openResetPasswordModal(id, username) {
        document.getElementById('resetPasswordForm').reset();
        document.getElementById('resetUserId').value = id;
        document.getElementById('resetTargetUsername').textContent = username;
        resetModalInstance.show();
    }

    async function handleResetPasswordSubmit(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveReset');
        const form = document.getElementById('resetPasswordForm');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

        try {
            const res = await App.fetch('<?= base_url("admin/users/reset-password") ?>', {
                method: 'POST',
                body: formData
            });

            if (res.status === 'success') {
                App.toast(res.message, 'success');
                resetModalInstance.hide();
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message || 'รีเซ็ตรหัสผ่านไม่สำเร็จ', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> บันทึกรหัสผ่านใหม่';
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> บันทึกรหัสผ่านใหม่';
        }
    }

    async function deleteUser(id, username) {
        if (!confirm(`⚠️ ยืนยันการลบบัญชีผู้ใช้งาน "${username}" ออกจากระบบถาวร?\nการดำเนินการนี้ไม่สามารถย้อนคืนได้`)) return;

        try {
            const res = await App.fetch(`<?= base_url('admin/users/delete') ?>/${id}`, {
                method: 'POST'
            });

            if (res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message || 'ไม่สามารถลบผู้ใช้งานได้', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการลบผู้ใช้งาน', 'error');
        }
    }
</script>
<?= $this->endSection() ?>

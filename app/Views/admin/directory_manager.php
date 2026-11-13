<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
// Helper format phone number for easy scanning
if (!function_exists('format_thai_phone_display')) {
    function format_thai_phone_display($phone) {
        $clean = preg_replace('/[^0-9]/', '', (string)$phone);
        if (strlen($clean) === 9) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3, 3) . '-' . substr($clean, 6);
        } elseif (strlen($clean) === 10) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3, 3) . '-' . substr($clean, 6);
        }
        return $phone ?: '-';
    }
}
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 text-white d-inline-flex align-items-center justify-content-center shadow-xs" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); width: 38px; height: 38px; font-size: 1.15rem;">
                <i class="fa-solid fa-address-book"></i>
            </span>
            <span>จัดการทำเนียบข้าราชการและสมุดโทรศัพท์</span>
        </h4>
        <p class="text-secondary mb-0" style="font-size: 0.95rem;">
            <?= $isSuperAdmin ? 'ระบบบริหารจัดการรายชื่อข้าราชการ ตำแหน่ง สังกัดกระทรวง ส่วนราชการ และสมุดโทรศัพท์ติดต่อราชการ' : 'จัดการข้อมูลบุคลากรและเบอร์ติดต่อภายในส่วนราชการ: <strong class="text-dark">' . esc($userDeptName) . '</strong>' ?>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap align-items-center">
        <a href="<?= base_url('directory') ?>" target="_blank" class="btn btn-outline-secondary fw-semibold px-3 py-2 d-flex align-items-center gap-1.5 rounded-3 shadow-2xs" style="font-size: 0.92rem;">
            <i class="fa-solid fa-arrow-up-right-from-square text-success"></i> ดูหน้าเว็บจริง
        </a>
        <?php if ($isSuperAdmin): ?>
            <button type="button" class="btn btn-outline-success fw-bold px-3 py-2 d-flex align-items-center gap-1.5 rounded-3 shadow-2xs" id="btnSyncPhone" onclick="triggerPhoneSync()" style="font-size: 0.92rem; border-color: #10b981; color: #047857; background: #ecfdf5;">
                <i class="fa-solid fa-arrows-rotate text-success" id="syncIcon"></i>
                <span>ซิงค์ข้อมูลจาก phone</span>
                <span class="badge bg-success text-white rounded-pill px-1.5 py-0.5 ms-0.5" style="font-size: 0.68rem;">1-Click</span>
            </button>
            <button type="button" class="btn btn-outline-primary fw-semibold px-3 py-2 d-flex align-items-center gap-1.5 rounded-3 shadow-2xs" onclick="openMinistryModal()" style="font-size: 0.92rem;">
                <i class="fa-solid fa-landmark"></i> จัดการสังกัด/กระทรวง
            </button>
            <button type="button" class="btn btn-outline-info fw-semibold px-3 py-2 d-flex align-items-center gap-1.5 rounded-3 shadow-2xs" onclick="openDepartmentModal()" style="font-size: 0.92rem;">
                <i class="fa-solid fa-building"></i> จัดการส่วนราชการ
            </button>
        <?php else: ?>
            <div class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                <i class="fa-solid fa-building-circle-check text-success fs-5"></i>
                <div class="text-start">
                    <div style="font-size: 0.72rem; text-transform: uppercase; font-weight: 600;" class="text-muted">หน่วยงานที่ได้รับสิทธิ์</div>
                    <div class="fw-bold"><?= esc($userDeptName) ?></div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($isSuperAdmin || !empty($userDeptInfo)): ?>
            <button type="button" class="btn btn-success fw-bold px-3.5 py-2 shadow-sm d-flex align-items-center gap-2 rounded-3" onclick="openAddContactModal()" style="background: linear-gradient(135deg, #10b981, #059669); border: none; font-size: 0.95rem;">
                <i class="fa-solid fa-user-plus fs-5"></i> เพิ่มรายชื่อใหม่
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (!$isSuperAdmin && empty($userDeptInfo)): ?>
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 d-flex align-items-start gap-3 mb-4">
        <i class="fa-solid fa-triangle-exclamation fs-2 text-warning flex-shrink-0 mt-1"></i>
        <div>
            <h5 class="fw-bold mb-1 text-dark">ยังไม่ได้รับการผูกหน่วยงานสังกัด</h5>
            <p class="text-muted mb-2">
                บัญชีผู้ใช้งานของท่าน (<?= esc(session()->get('username')) ?>) ยังไม่ได้รับการผูกกับส่วนราชการในระบบทำเนียบข้าราชการ จึงยังไม่สามารถดูหรือแก้ไขข้อมูลได้ เพื่อรักษาความปลอดภัยของข้อมูลระหว่างหน่วยงาน
            </p>
            <div class="small fw-semibold text-dark">
                💡 คำแนะนำ: กรุณาติดต่อผู้ดูแลระบบกลาง (Super Admin) ให้ทำการระบุชื่อหน่วยงานของท่านในเมนู <strong>"จัดการผู้ใช้งาน"</strong>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Live Sync Status Banner with phone.phatthalung.go.th -->
<div class="card border-0 shadow-xs rounded-4 p-3 mb-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border: 1px solid #dcfce7 !important;">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-3 d-flex align-items-center justify-content-center text-success flex-shrink-0" style="width: 44px; height: 44px; background: #dcfce7; font-size: 1.25rem;">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="fw-bold text-dark" style="font-size: 0.98rem;">ระบบเชื่อมโยงข้อมูลอัตโนมัติ (Live Sync กับ phone.phatthalung.go.th)</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0.5 rounded-pill small">
                        <i class="fa-solid fa-circle-check me-1"></i>เชื่อมต่อ API สมบูรณ์
                    </span>
                </div>
                <div class="text-secondary small mt-0.5" id="syncMetaInfoText">
                    ซิงค์ข้อมูลล่าสุดเมื่อ: <strong class="text-dark"><?= !empty($syncMeta['last_synced_at']) ? date('d/m/Y H:i น.', strtotime($syncMeta['last_synced_at'])) : 'วันนี้' ?></strong> 
                    &bull; ข้อมูลในระบบปัจจุบัน: <span class="fw-semibold text-dark"><?= number_format($syncMeta['contacts_count'] ?? $totalAllContacts) ?> รายชื่อ</span> (<?= number_format($syncMeta['departments_count'] ?? count($departments)) ?> หน่วยงาน / <?= number_format($syncMeta['ministries_count'] ?? count($ministries)) ?> สังกัด)
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="https://www.phone.phatthalung.go.th" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5" style="font-size: 0.85rem;">
                <i class="fa-solid fa-up-right-from-square me-1"></i> ตรวจสอบเว็บ phone
            </a>
            <?php if ($isSuperAdmin): ?>
                <button type="button" class="btn btn-sm btn-success fw-bold rounded-pill px-3.5 py-1.5 d-inline-flex align-items-center gap-1.5 shadow-xs" onclick="triggerPhoneSync()" id="btnSyncBanner">
                    <i class="fa-solid fa-arrows-rotate" id="syncIconBanner"></i> ซิงค์ข้อมูลล่าสุดทันที
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- Quick Stats Bar (Modern Ergonomic Soft Tint Cards) -->
<div class="row g-3 mb-4">
    <?php if ($isSuperAdmin): ?>
        <div class="col-md-4">
            <div class="soft-stat-card">
                <div class="soft-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="soft-stat-label">ข้าราชการ / เจ้าหน้าที่ทั้งหมด</div>
                    <div class="soft-stat-value"><?= number_format($totalAllContacts) ?> <span style="font-size: 0.95rem; font-weight: 500; color: #64748b;">ท่าน</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-stat-card">
                <div class="soft-stat-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <div class="soft-stat-label">ส่วนราชการ / หน่วยงานย่อย</div>
                    <div class="soft-stat-value"><?= count($departments) ?> <span style="font-size: 0.95rem; font-weight: 500; color: #64748b;">หน่วยงาน</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-stat-card">
                <div class="soft-stat-icon" style="background: rgba(217, 119, 6, 0.12); color: #d97706;">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div>
                    <div class="soft-stat-label">กลุ่มสังกัด / กระทรวง</div>
                    <div class="soft-stat-value"><?= count($ministries) ?> <span style="font-size: 0.95rem; font-weight: 500; color: #64748b;">สังกัด</span></div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="col-md-4">
            <div class="soft-stat-card">
                <div class="soft-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div class="soft-stat-label">จำนวนบุคลากรในหน่วยงาน</div>
                    <div class="soft-stat-value"><?= number_format($totalAllContacts) ?> <span style="font-size: 0.95rem; font-weight: 500; color: #64748b;">ท่าน</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-stat-card">
                <div class="soft-stat-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="soft-stat-label">ส่วนราชการที่รับผิดชอบ</div>
                    <h6 class="fw-bold mb-0 text-dark text-truncate" title="<?= esc($userDeptName) ?>" style="font-size: 1.05rem;"><?= esc($userDeptName) ?></h6>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="soft-stat-card">
                <div class="soft-stat-icon" style="background: rgba(217, 119, 6, 0.12); color: #d97706;">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="soft-stat-label">สังกัด / กลุ่มกระทรวง</div>
                    <h6 class="fw-bold mb-0 text-dark text-truncate" title="<?= esc($userMinistryName) ?>" style="font-size: 1.05rem;"><?= esc($userMinistryName) ?></h6>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Filter & Search Controls (Modern Unified Bar) -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4" style="background: #ffffff;">
    <form method="get" action="<?= base_url('admin/directory') ?>">
        <div class="row g-2 align-items-center">
            <div class="<?= $isSuperAdmin ? 'col-md-5' : 'col-md-7' ?>">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="ค้นหาชื่อ, สกุล, ตำแหน่ง, หรือเบอร์โทรศัพท์..." value="<?= esc($search) ?>" style="height: 42px;">
                    <?php if ($search): ?>
                        <a href="<?= base_url('admin/directory') ?>" class="input-group-text bg-white text-muted text-decoration-none" title="ล้างคำค้นหา">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($isSuperAdmin): ?>
                <div class="col-md-4">
                    <select name="ministry" class="form-select" onchange="this.form.submit()" style="height: 42px;">
                        <option value="">-- กรองตามสังกัด/กระทรวง (ทั้งหมด) --</option>
                        <?php foreach ($ministries as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $selectedMinistry === (int)$m['id'] ? 'selected' : '' ?>>
                                <?= esc($m['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100 fw-semibold rounded-3 d-flex align-items-center justify-content-center gap-1.5" style="height: 42px; background: #059669; border-color: #059669;">
                        <i class="fa-solid fa-filter"></i> กรองผลลัพธ์
                    </button>
                    <?php if ($search || $selectedMinistry): ?>
                        <a href="<?= base_url('admin/directory') ?>" class="btn btn-outline-secondary rounded-3 d-flex align-items-center justify-content-center" style="height: 42px; width: 44px;" title="ล้างตัวกรอง">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="col-md-5 d-flex gap-2 align-items-center">
                    <div class="bg-light border rounded-3 px-3 w-100 text-truncate d-flex align-items-center gap-2" style="height: 42px;">
                        <i class="fa-solid fa-lock text-muted"></i>
                        <span class="text-muted small">ขอบเขต:</span>
                        <strong class="text-dark text-truncate small"><?= esc($userDeptName) ?></strong>
                    </div>
                    <button type="submit" class="btn btn-success fw-semibold rounded-3 px-4 flex-shrink-0 d-flex align-items-center gap-1.5" style="height: 42px; background: #059669; border-color: #059669;">
                        <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
                    </button>
                    <?php if ($search): ?>
                        <a href="<?= base_url('admin/directory') ?>" class="btn btn-outline-secondary rounded-3 d-flex align-items-center justify-content-center" style="height: 42px; width: 44px;" title="ล้างการค้นหา">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Contacts Table (Ergonomic High Legibility) -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 55px;" class="text-center">#</th>
                    <th style="width: 65px;" class="text-center">รูป</th>
                    <th>ชื่อ - สกุล</th>
                    <th>ตำแหน่ง</th>
                    <th>สังกัด / ส่วนราชการ</th>
                    <th style="min-width: 130px;">เบอร์โทรศัพท์โต๊ะ</th>
                    <th style="min-width: 130px;">เบอร์มือถือ</th>
                    <th class="text-end" style="width: 120px;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contacts)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-circle-question fs-2 d-block mb-2 opacity-40"></i>
                            <span class="fw-medium">ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contacts as $c): ?>
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-light text-secondary border px-2 py-1 rounded" style="font-size: 0.78rem; font-family: monospace;">
                                    <?= $c['id'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div style="width: 44px; height: 50px; border-radius: 8px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; margin: 0 auto; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    <img src="<?= $c['photo_url'] ?: base_url('assets/images/user-placeholder.png') ?>" 
                                         alt="<?= esc($c['name']) ?>" 
                                         class="w-100 h-100 object-fit-cover"
                                         loading="lazy"
                                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($c['name']) ?>&background=059669&color=fff';">
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.98rem;"><?= esc($c['name']) ?></span>
                            </td>
                            <td>
                                <span class="text-secondary" style="font-size: 0.92rem;"><?= esc($c['position'] ?: '-') ?></span>
                            </td>
                            <td>
                                <span class="badge px-2.5 py-1 text-truncate d-inline-block mb-1" style="max-width: 240px; background: #ecfdf5; color: #065f46; border: 1px solid #bbf7d0; font-size: 0.84rem; font-weight: 500;" title="<?= esc($c['department_name']) ?>">
                                    <?= esc($c['department_name']) ?>
                                </span>
                                <small class="text-muted d-block text-truncate" style="max-width: 240px; font-size: 0.8rem;"><?= esc($c['ministry_name']) ?></small>
                            </td>
                            <td>
                                <?php if (!empty($c['phone'])): ?>
                                    <a href="tel:<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1.5" style="font-family: 'Inter', monospace; font-size: 0.92rem; font-weight: 500;">
                                        <i class="fa-solid fa-phone text-muted" style="font-size: 0.78rem;"></i>
                                        <span><?= esc(format_thai_phone_display($c['phone'])) ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($c['mobile'])): ?>
                                    <a href="tel:<?= preg_replace('/[^0-9]/', '', $c['mobile']) ?>" class="text-decoration-none text-success d-inline-flex align-items-center gap-1.5" style="font-family: 'Inter', monospace; font-size: 0.92rem; font-weight: 600;">
                                        <i class="fa-solid fa-mobile-screen text-success" style="font-size: 0.82rem;"></i>
                                        <span><?= esc(format_thai_phone_display($c['mobile'])) ?></span>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn-action-pill btn-action-edit" 
                                        data-contact="<?= htmlspecialchars(json_encode($c, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
                                        onclick="openEditContactModal(this)"
                                        title="แก้ไขข้อมูลบุคลากร">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn-action-pill btn-action-delete ms-1" 
                                        onclick="deleteContact(<?= $c['id'] ?>, '<?= esc(addslashes($c['name'])) ?>')"
                                        title="ลบข้อมูล">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">
                แสดงผลหน้าที่ <?= $currentPage ?> จากทั้งหมด <?= $totalPages ?> หน้า (รวม <?= number_format($totalResults) ?> รายการ)
            </span>
            <ul class="pagination pagination-sm mb-0 gap-1">
                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link rounded-pill px-2.5" href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => max(1, $currentPage - 1)])) ?>">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
                <?php
                    $startP = max(1, $currentPage - 2);
                    $endP = min($totalPages, $currentPage + 2);
                ?>
                <?php for ($p = $startP; $p <= $endP; $p++): ?>
                    <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                        <a class="page-link rounded-circle text-center p-0 d-inline-flex align-items-center justify-content-center" 
                           style="width: 28px; height: 28px;"
                           href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => $p])) ?>">
                            <?= $p ?>
                        </a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link rounded-pill px-2.5" href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => min($totalPages, $currentPage + 1)])) ?>">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<!-- ===================== ADD / EDIT CONTACT MODAL ===================== -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form id="contactForm" onsubmit="saveContactForm(event)" enctype="multipart/form-data">
                <input type="hidden" name="id" id="contactId" value="">

                <div class="modal-header bg-success text-white border-0 py-3">
                    <h5 class="modal-title fw-bold" id="contactModalLabel">
                        <i class="fa-solid fa-user-pen me-2"></i> บันทึกข้อมูลข้าราชการ/ผู้บริหาร
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">ชื่อ - นามสกุล <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="contactName" class="form-control" required placeholder="เช่น นายสมชาย ใจดี">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">ตำแหน่ง <span class="text-danger">*</span></label>
                            <input type="text" name="position" id="contactPosition" class="form-control" required placeholder="เช่น ผู้ว่าราชการจังหวัดพัทลุง">
                        </div>

                        <?php if ($isSuperAdmin): ?>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold small mb-0">สังกัด / กลุ่มกระทรวง <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none small text-success fw-semibold" onclick="openMinistryModal()">
                                        <i class="fa-solid fa-plus-circle me-1"></i>เพิ่มสังกัด
                                    </button>
                                </div>
                                <select name="ministry_id" id="contactMinistryId" class="form-select" onchange="filterDeptOptions()" required>
                                    <option value="">-- เลือกสังกัด/กระทรวง --</option>
                                    <?php foreach ($ministries as $m): ?>
                                        <option value="<?= $m['id'] ?>"><?= esc($m['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label fw-semibold small mb-0">ส่วนราชการ / หน่วยงาน <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none small text-info fw-semibold" onclick="openDepartmentModal()">
                                        <i class="fa-solid fa-plus-circle me-1"></i>เพิ่มหน่วยงาน
                                    </button>
                                </div>
                                <select name="department_id" id="contactDeptId" class="form-select" required>
                                    <option value="">-- เลือกหน่วยงาน --</option>
                                    <?php foreach ($departments as $d): ?>
                                        <option value="<?= $d['id'] ?>" data-ministry="<?= $d['ministry_id'] ?>">
                                            <?= esc($d['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small mb-1">สังกัด / กลุ่มกระทรวง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light" value="<?= esc($userMinistryName) ?>" readonly>
                                <input type="hidden" name="ministry_id" id="contactMinistryId" value="<?= (int)($userDeptInfo['ministry_id'] ?? 0) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small mb-1">ส่วนราชการ / หน่วยงาน <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light fw-bold text-success" value="<?= esc($userDeptName) ?>" readonly>
                                <input type="hidden" name="department_id" id="contactDeptId" value="<?= (int)($userDeptInfo['id'] ?? 0) ?>">
                                <small class="text-muted" style="font-size: 0.72rem;"><i class="fa-solid fa-lock me-1"></i>ล็อกอัตโนมัติตามสิทธิ์หน่วยงานสังกัดของท่าน</small>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">เบอร์โทรศัพท์โต๊ะ</label>
                            <input type="text" name="phone" id="contactPhone" class="form-control" placeholder="เช่น 074-613012">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">เบอร์มือถือ</label>
                            <input type="text" name="mobile" id="contactMobile" class="form-control" placeholder="เช่น 089-2030467">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">เบอร์ภายใน (Hotline)</label>
                            <input type="text" name="hotline" id="contactHotline" class="form-control" placeholder="เช่น 71711">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">ที่ตั้งห้องปฏิบัติการ / สถานที่ติดต่อ</label>
                            <input type="text" name="address" id="contactAddress" class="form-control" placeholder="เช่น ศาลากลางจังหวัดพัทลุง (หลังใหม่) ชั้น 2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">ลำดับการแสดงผล</label>
                            <input type="number" name="order" id="contactOrder" class="form-control" value="1" min="1">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold small">อัปโหลดรูปถ่ายประจำตัว</label>
                            <input type="file" name="photo_file" id="contactPhotoFile" class="form-control" accept="image/*">
                            <small class="text-muted">แนะนำภาพแนวตั้ง สัดส่วน 3:4 พื้นหลังสุภาพ</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0 py-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" id="saveContactBtn" class="btn btn-success rounded-pill px-4 fw-semibold">
                        <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($isSuperAdmin): ?>
<!-- ============================================================== -->
<!-- MODAL: จัดการสังกัด / กระทรวง (Ministry Manager Modal) -->
<!-- ============================================================== -->
<div class="modal fade" id="ministryModal" tabindex="-1" aria-labelledby="ministryModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
                <h5 class="modal-title fw-bold" id="ministryModalLabel">
                    <i class="fa-solid fa-landmark me-2"></i> จัดการสังกัด / กลุ่มกระทรวง
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="hideMinistryModalSafe()"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Add / Edit Form Box -->
                <div class="card border border-primary-subtle bg-light-subtle rounded-3 p-3 mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary" id="ministryFormTitle">
                            <i class="fa-solid fa-plus-circle me-1"></i> เพิ่มสังกัด / กระทรวงใหม่
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" id="btnCancelEditMinistry" onclick="resetMinistryForm()" style="display: none;">
                            <i class="fa-solid fa-rotate-left me-1"></i> ยกเลิกการแก้ไข
                        </button>
                    </div>
                    <form id="ministryForm" onsubmit="saveMinistryForm(event)" class="row g-2 align-items-end">
                        <input type="hidden" name="id" id="ministryId" value="">
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold text-muted mb-1">ชื่อสังกัด / กระทรวง <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="ministryName" class="form-control" placeholder="เช่น กระทรวงมหาดไทย, กระทรวงการคลัง" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">ลำดับ</label>
                            <input type="number" name="order" id="ministryOrder" class="form-control" value="99" min="1">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" id="btnSaveMinistry" class="btn btn-primary w-100 fw-bold">
                                <i class="fa-solid fa-save me-1"></i> บันทึกสังกัด
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table Header & Real-time Search -->
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <h6 class="fw-bold mb-0 text-dark">
                        รายการสังกัดทั้งหมด (<span id="ministryCount">0</span>)
                    </h6>
                    <div style="width: 240px;">
                        <input type="text" id="searchMinistryInput" class="form-control form-control-sm rounded-pill px-3" placeholder="ค้นหาชื่อสังกัด..." oninput="renderMinistryTable()">
                    </div>
                </div>

                <!-- Table Container -->
                <div class="table-responsive border rounded-3" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small sticky-top">
                            <tr>
                                <th style="width: 70px;" class="text-center">ลำดับ</th>
                                <th>ชื่อสังกัด / กระทรวง</th>
                                <th style="width: 140px;" class="text-center">หน่วยงานในสังกัด</th>
                                <th style="width: 130px;" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="ministryTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="hideMinistryModalSafe()">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: จัดการส่วนราชการ / หน่วยงาน (Department Manager Modal) -->
<!-- ============================================================== -->
<div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0dcaf0, #0aa2c0);">
                <h5 class="modal-title fw-bold" id="departmentModalLabel">
                    <i class="fa-solid fa-building me-2"></i> จัดการส่วนราชการ / หน่วยงานย่อย
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="hideDepartmentModalSafe()"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Add / Edit Form Box -->
                <div class="card border border-info-subtle bg-light-subtle rounded-3 p-3 mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-info-emphasis" id="departmentFormTitle">
                            <i class="fa-solid fa-plus-circle me-1"></i> เพิ่มส่วนราชการ / หน่วยงานใหม่
                        </span>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" id="btnCancelEditDepartment" onclick="resetDepartmentForm()" style="display: none;">
                            <i class="fa-solid fa-rotate-left me-1"></i> ยกเลิกการแก้ไข
                        </button>
                    </div>
                    <form id="departmentForm" onsubmit="saveDepartmentForm(event)" class="row g-2 align-items-end">
                        <input type="hidden" name="id" id="deptId" value="">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted mb-1">สังกัด / กระทรวง <span class="text-danger">*</span></label>
                            <select name="ministry_id" id="deptFormMinistryId" class="form-select" required>
                                <option value="">-- เลือกสังกัด/กระทรวง --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted mb-1">ชื่อส่วนราชการ / หน่วยงาน <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="deptName" class="form-control" placeholder="เช่น สำนักงานจังหวัดพัทลุง" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">ลำดับ</label>
                            <input type="number" name="order" id="deptOrder" class="form-control" value="99" min="1">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" id="btnSaveDepartment" class="btn btn-info text-white w-100 fw-bold">
                                <i class="fa-solid fa-save me-1"></i> บันทึกหน่วยงาน
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table Filter & Search Controls -->
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-muted mb-1">กรองตามสังกัด/กระทรวง:</label>
                        <select id="deptFilterMinistry" class="form-select form-select-sm" onchange="renderDepartmentTable()">
                            <option value="">-- แสดงทุกสังกัด/กระทรวงทั้งหมด --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted mb-1">ค้นหาชื่อส่วนราชการ:</label>
                        <input type="text" id="searchDepartmentInput" class="form-control form-control-sm" placeholder="พิมพ์ชื่อหน่วยงาน..." oninput="renderDepartmentTable()">
                    </div>
                    <div class="col-md-3 text-end pt-3">
                        <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                            พบ <strong id="deptCount" class="text-info">0</strong> หน่วยงาน
                        </span>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="table-responsive border rounded-3" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small sticky-top">
                            <tr>
                                <th style="width: 70px;" class="text-center">ลำดับ</th>
                                <th style="width: 260px;">สังกัด/กระทรวง</th>
                                <th>ชื่อส่วนราชการ / หน่วยงาน</th>
                                <th style="width: 130px;" class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="departmentTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal" onclick="hideDepartmentModalSafe()">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    // In-memory data collections initialized from server
    let ministriesData = <?= json_encode($ministries, JSON_UNESCAPED_UNICODE) ?>;
    let departmentsData = <?= json_encode($departments, JSON_UNESCAPED_UNICODE) ?>;
    let allDeptOptions = [];

    // ==============================================================
    // Generic Safe Modal Helpers (Supports Bootstrap 5 and Fallback)
    // ==============================================================
    function showModalSafe(modalId) {
        const modalEl = document.getElementById(modalId);
        if (!modalEl) return;
        if (window.bootstrap && typeof bootstrap.Modal !== 'undefined') {
            const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
            instance.show();
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            let backdrop = document.querySelector('.' + modalId + '-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show ' + modalId + '-backdrop';
                backdrop.style.zIndex = (parseInt(window.getComputedStyle(modalEl).zIndex) || 1050) - 1;
                document.body.appendChild(backdrop);
            }
            document.body.classList.add('modal-open');
        }
    }

    function hideModalSafe(modalId) {
        const modalEl = document.getElementById(modalId);
        if (!modalEl) return;
        if (window.bootstrap && typeof bootstrap.Modal !== 'undefined') {
            const instance = bootstrap.Modal.getInstance(modalEl);
            if (instance) instance.hide();
        }
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        modalEl.setAttribute('aria-hidden', 'true');
        document.querySelectorAll('.' + modalId + '-backdrop').forEach(b => b.remove());
        if (!document.querySelector('.modal.show')) {
            document.body.classList.remove('modal-open');
        }
    }

    // Modal Specific Wrappers
    window.showContactModalSafe = () => showModalSafe('contactModal');
    window.hideContactModalSafe = () => hideModalSafe('contactModal');

    window.openMinistryModal = function() {
        resetMinistryForm();
        renderMinistryTable();
        showModalSafe('ministryModal');
    };
    window.hideMinistryModalSafe = () => hideModalSafe('ministryModal');

    window.openDepartmentModal = function(preselectedMinId = null) {
        resetDepartmentForm();
        if (preselectedMinId) {
            const minSelect = document.getElementById('deptFormMinistryId');
            if (minSelect) minSelect.value = preselectedMinId;
            const filterSelect = document.getElementById('deptFilterMinistry');
            if (filterSelect) filterSelect.value = preselectedMinId;
        } else {
            const contactMin = document.getElementById('contactMinistryId')?.value;
            if (contactMin) {
                const minSelect = document.getElementById('deptFormMinistryId');
                if (minSelect) minSelect.value = contactMin;
                const filterSelect = document.getElementById('deptFilterMinistry');
                if (filterSelect) filterSelect.value = contactMin;
            }
        }
        renderDepartmentTable();
        showModalSafe('departmentModal');
    };
    window.hideDepartmentModalSafe = () => hideModalSafe('departmentModal');

    // ==============================================================
    // Sync Select Dropdowns across all Modals
    // ==============================================================
    function refreshDropdowns() {
        // 1. Sync Ministry Options in Contact Modal
        const contactMinSelect = document.getElementById('contactMinistryId');
        if (contactMinSelect && contactMinSelect.tagName === 'SELECT') {
            const curContactMin = contactMinSelect.value;
            contactMinSelect.innerHTML = '<option value="">-- เลือกสังกัด/กระทรวง --</option>';
            ministriesData.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                if (String(m.id) === String(curContactMin)) opt.selected = true;
                contactMinSelect.appendChild(opt);
            });
        }

        // 2. Sync Ministry Options in Dept Form Modal
        const deptFormMinSelect = document.getElementById('deptFormMinistryId');
        if (deptFormMinSelect && deptFormMinSelect.tagName === 'SELECT') {
            const curDeptFormMin = deptFormMinSelect.value;
            deptFormMinSelect.innerHTML = '<option value="">-- เลือกสังกัด/กระทรวง --</option>';
            ministriesData.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                if (String(m.id) === String(curDeptFormMin)) opt.selected = true;
                deptFormMinSelect.appendChild(opt);
            });
        }

        // 3. Sync Ministry Options in Dept Filter Modal
        const deptFilterMinSelect = document.getElementById('deptFilterMinistry');
        if (deptFilterMinSelect && deptFilterMinSelect.tagName === 'SELECT') {
            const curDeptFilter = deptFilterMinSelect.value;
            deptFilterMinSelect.innerHTML = '<option value="">-- แสดงทุกสังกัด/กระทรวงทั้งหมด --</option>';
            ministriesData.forEach(m => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.name;
                if (String(m.id) === String(curDeptFilter)) opt.selected = true;
                deptFilterMinSelect.appendChild(opt);
            });
        }

        // 4. Rebuild allDeptOptions cache from departmentsData
        allDeptOptions = departmentsData.map(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.setAttribute('data-ministry', d.ministry_id);
            opt.textContent = d.name;
            return opt;
        });

        // 5. Update Dept Dropdown in Contact Modal
        filterDeptOptions();
    }

    // Filter department dropdown based on selected ministry in contact modal
    function filterDeptOptions() {
        const deptSelect = document.getElementById('contactDeptId');
        if (!deptSelect || deptSelect.tagName !== 'SELECT') return;

        const selectedMin = document.getElementById('contactMinistryId')?.value;
        const curVal = deptSelect.value;
        deptSelect.innerHTML = '<option value="">-- เลือกหน่วยงาน --</option>';

        departmentsData.forEach(d => {
            if (!selectedMin || String(d.ministry_id) === String(selectedMin)) {
                const opt = document.createElement('option');
                opt.value = d.id;
                opt.setAttribute('data-ministry', d.ministry_id);
                opt.textContent = d.name;
                if (String(d.id) === String(curVal)) opt.selected = true;
                deptSelect.appendChild(opt);
            }
        });
    }

    // ==============================================================
    // MINISTRY MANAGEMENT LOGIC
    // ==============================================================
    function renderMinistryTable() {
        const tbody = document.getElementById('ministryTableBody');
        const search = (document.getElementById('searchMinistryInput')?.value || '').toLowerCase().trim();
        if (!tbody) return;

        let filtered = ministriesData.filter(m => {
            return !search || (m.name && m.name.toLowerCase().includes(search));
        });

        document.getElementById('ministryCount').textContent = filtered.length;

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted">ไม่พบข้อมูลสังกัด/กระทรวงที่ค้นหา</td></tr>`;
            return;
        }

        let html = '';
        filtered.forEach(m => {
            const deptCount = departmentsData.filter(d => String(d.ministry_id) === String(m.id)).length;
            const safeName = (m.name || '').replace(/"/g, '&quot;');
            html += `
                <tr>
                    <td class="text-center font-monospace small text-muted">${m.order || 99}</td>
                    <td>
                        <span class="fw-semibold text-dark">${m.name}</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-2 py-0 text-info" onclick="hideMinistryModalSafe(); openDepartmentModal(${m.id});" title="คลิกเพื่อดูหน่วยงานในสังกัดนี้">
                            ${deptCount} หน่วยงาน <i class="fa-solid fa-arrow-right ms-1 small"></i>
                        </button>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-primary" onclick="editMinistry(${m.id})" title="แก้ไข">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="deleteMinistryItem(${m.id}, '${safeName}')" title="ลบ">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    window.editMinistry = function(id) {
        const m = ministriesData.find(item => item.id == id);
        if (!m) return;
        document.getElementById('ministryId').value = m.id;
        document.getElementById('ministryName').value = m.name || '';
        document.getElementById('ministryOrder').value = m.order || 99;
        document.getElementById('ministryFormTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-1"></i> แก้ไขสังกัด: ' + m.name;
        document.getElementById('btnSaveMinistry').innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกการแก้ไข';
        document.getElementById('btnCancelEditMinistry').style.display = 'inline-block';
        document.getElementById('ministryName').focus();
    };

    window.resetMinistryForm = function() {
        const form = document.getElementById('ministryForm');
        if (form) form.reset();
        document.getElementById('ministryId').value = '';
        document.getElementById('ministryOrder').value = 99;
        document.getElementById('ministryFormTitle').innerHTML = '<i class="fa-solid fa-plus-circle me-1"></i> เพิ่มสังกัด / กระทรวงใหม่';
        document.getElementById('btnSaveMinistry').innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกสังกัด';
        document.getElementById('btnCancelEditMinistry').style.display = 'none';
    };

    window.saveMinistryForm = async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveMinistry');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

        const formData = new FormData(document.getElementById('ministryForm'));

        try {
            const res = await fetch('<?= base_url('admin/directory/save-ministry') ?>', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.status === 'success') {
                if (data.ministries) {
                    ministriesData = data.ministries;
                }
                resetMinistryForm();
                refreshDropdowns();
                renderMinistryTable();
                alert(data.message || 'บันทึกสังกัด/กระทรวงเรียบร้อยแล้ว');
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการบันทึก');
            }
        } catch (err) {
            alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกสังกัด';
        }
    };

    window.deleteMinistryItem = async function(id, name) {
        const linkedDepts = departmentsData.filter(d => String(d.ministry_id) === String(id));
        let warn = `คุณต้องการลบสังกัด "${name}" ใช่หรือไม่?`;
        if (linkedDepts.length > 0) {
            warn += `\n\nคำเตือน: สังกัดนี้มีส่วนราชการ/หน่วยงานย่อยอยู่ ${linkedDepts.length} แห่ง!`;
        }

        if (!confirm(warn)) return;

        const formData = new FormData();
        formData.append('id', id);

        try {
            const res = await fetch('<?= base_url('admin/directory/delete-ministry') ?>', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.status === 'success') {
                if (data.ministries) {
                    ministriesData = data.ministries;
                }
                refreshDropdowns();
                renderMinistryTable();
                alert(data.message || 'ลบสังกัดเรียบร้อยแล้ว');
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการลบ');
            }
        } catch (err) {
            alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
        }
    };

    // ==============================================================
    // DEPARTMENT MANAGEMENT LOGIC
    // ==============================================================
    function renderDepartmentTable() {
        const tbody = document.getElementById('departmentTableBody');
        const filterMin = document.getElementById('deptFilterMinistry')?.value || '';
        const search = (document.getElementById('searchDepartmentInput')?.value || '').toLowerCase().trim();
        if (!tbody) return;

        let filtered = departmentsData.filter(d => {
            const matchMin = !filterMin || String(d.ministry_id) === String(filterMin);
            const matchSearch = !search || (d.name && d.name.toLowerCase().includes(search));
            return matchMin && matchSearch;
        });

        document.getElementById('deptCount').textContent = filtered.length;

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted">ไม่พบข้อมูลส่วนราชการตามเงื่อนไขที่เลือก</td></tr>`;
            return;
        }

        let html = '';
        filtered.forEach(d => {
            const minObj = ministriesData.find(m => String(m.id) === String(d.ministry_id));
            const minName = minObj ? minObj.name : '<span class="text-muted">(ไม่ระบุสังกัด)</span>';
            const safeName = (d.name || '').replace(/"/g, '&quot;');
            html += `
                <tr>
                    <td class="text-center font-monospace small text-muted">${d.order || 99}</td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-medium py-1 px-2">
                            ${minName}
                        </span>
                    </td>
                    <td>
                        <span class="fw-semibold text-dark">${d.name}</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-info" onclick="editDepartment(${d.id})" title="แก้ไข">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="deleteDepartmentItem(${d.id}, '${safeName}')" title="ลบ">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    window.editDepartment = function(id) {
        const d = departmentsData.find(item => item.id == id);
        if (!d) return;
        document.getElementById('deptId').value = d.id;
        document.getElementById('deptFormMinistryId').value = d.ministry_id || '';
        document.getElementById('deptName').value = d.name || '';
        document.getElementById('deptOrder').value = d.order || 99;
        document.getElementById('departmentFormTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-1"></i> แก้ไขส่วนราชการ: ' + d.name;
        document.getElementById('btnSaveDepartment').innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกการแก้ไข';
        document.getElementById('btnCancelEditDepartment').style.display = 'inline-block';
        document.getElementById('deptName').focus();
    };

    window.resetDepartmentForm = function() {
        const form = document.getElementById('departmentForm');
        if (form) form.reset();
        document.getElementById('deptId').value = '';
        document.getElementById('deptOrder').value = 99;
        document.getElementById('departmentFormTitle').innerHTML = '<i class="fa-solid fa-plus-circle me-1"></i> เพิ่มส่วนราชการ / หน่วยงานใหม่';
        document.getElementById('btnSaveDepartment').innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกหน่วยงาน';
        document.getElementById('btnCancelEditDepartment').style.display = 'none';
    };

    window.saveDepartmentForm = async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveDepartment');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

        const formData = new FormData(document.getElementById('departmentForm'));

        try {
            const res = await fetch('<?= base_url('admin/directory/save-department') ?>', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.status === 'success') {
                if (data.departments) {
                    departmentsData = data.departments;
                }
                const savedMinId = formData.get('ministry_id');
                resetDepartmentForm();
                // Retain ministry selection for easy multiple additions
                if (savedMinId) {
                    document.getElementById('deptFormMinistryId').value = savedMinId;
                }
                refreshDropdowns();
                renderDepartmentTable();
                alert(data.message || 'บันทึกส่วนราชการ/หน่วยงานเรียบร้อยแล้ว');
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการบันทึก');
            }
        } catch (err) {
            alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกหน่วยงาน';
        }
    };

    window.deleteDepartmentItem = async function(id, name) {
        if (!confirm(`คุณต้องการลบส่วนราชการ "${name}" ใช่หรือไม่?`)) return;

        const formData = new FormData();
        formData.append('id', id);

        try {
            const res = await fetch('<?= base_url('admin/directory/delete-department') ?>', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.status === 'success') {
                if (data.departments) {
                    departmentsData = data.departments;
                }
                refreshDropdowns();
                renderDepartmentTable();
                alert(data.message || 'ลบส่วนราชการเรียบร้อยแล้ว');
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการลบ');
            }
        } catch (err) {
            alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
        }
    };

    // ==============================================================
    // CONTACT MANAGEMENT LOGIC (ROSTER)
    // ==============================================================
    const isSuperAdminUser = <?= $isSuperAdmin ? 'true' : 'false' ?>;
    const userAssignedMinId = <?= (int)($userDeptInfo['ministry_id'] ?? 0) ?>;
    const userAssignedDeptId = <?= (int)($userDeptInfo['id'] ?? 0) ?>;

    window.openAddContactModal = function() {
        const form = document.getElementById('contactForm');
        if (form) form.reset();
        document.getElementById('contactId').value = '';
        document.getElementById('contactModalLabel').innerHTML = '<i class="fa-solid fa-user-plus me-2"></i> เพิ่มรายชื่อข้าราชการใหม่';
        if (isSuperAdminUser) {
            filterDeptOptions();
        } else {
            const minEl = document.getElementById('contactMinistryId');
            const deptEl = document.getElementById('contactDeptId');
            if (minEl) minEl.value = userAssignedMinId;
            if (deptEl) deptEl.value = userAssignedDeptId;
        }
        showContactModalSafe();
    };

    window.openEditContactModal = function(btnOrData) {
        let c = null;
        if (btnOrData && typeof btnOrData.getAttribute === 'function') {
            const raw = btnOrData.getAttribute('data-contact');
            try {
                c = JSON.parse(raw);
            } catch (err) {
                console.error('Failed to parse contact json:', err);
            }
        } else if (typeof btnOrData === 'object') {
            c = btnOrData;
        }

        if (!c) {
            alert('ไม่พบข้อมูลสำหรับแก้ไข');
            return;
        }

        document.getElementById('contactId').value = c.id || '';
        document.getElementById('contactName').value = c.name || '';
        document.getElementById('contactPosition').value = c.position || '';
        if (isSuperAdminUser) {
            document.getElementById('contactMinistryId').value = c.ministry_id || '';
            filterDeptOptions();
            document.getElementById('contactDeptId').value = c.department_id || '';
        } else {
            const minEl = document.getElementById('contactMinistryId');
            const deptEl = document.getElementById('contactDeptId');
            if (minEl) minEl.value = userAssignedMinId;
            if (deptEl) deptEl.value = userAssignedDeptId;
        }
        document.getElementById('contactPhone').value = c.phone || '';
        document.getElementById('contactMobile').value = c.mobile || '';
        document.getElementById('contactHotline').value = c.hotline || '';
        document.getElementById('contactAddress').value = c.address || '';
        document.getElementById('contactOrder').value = c.order || 1;
        document.getElementById('contactModalLabel').innerHTML = '<i class="fa-solid fa-user-pen me-2"></i> แก้ไขข้อมูล: ' + (c.name || '');
        showContactModalSafe();
    };

    // Legacy alias
    window.editContact = window.openEditContactModal;

    window.saveContactForm = async function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveContactBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

        const formData = new FormData(document.getElementById('contactForm'));

        try {
            const res = await fetch('<?= base_url('admin/directory/save') ?>', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการบันทึก');
            }
        } catch (err) {
            alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล';
        }
    };

    window.deleteContact = async function(id, name) {
        if (!confirm(`คุณต้องการลบข้อมูล "${name}" ใช่หรือไม่?`)) return;

        const formData = new FormData();
        formData.append('id', id);

        try {
            const res = await fetch('<?= base_url('admin/directory/delete') ?>', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการลบข้อมูล');
            }
        } catch (err) {
            alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
        }
    };

    // ==============================================================
    // 1-Click Sync Handler from phone.phatthalung.go.th
    // ==============================================================
    window.triggerPhoneSync = async function() {
        if (!confirm('ยืนยันการดึงข้อมูลล่าสุดจาก phone.phatthalung.go.th ใช่หรือไม่?\n\nระบบจะทำการอัปเดตรายชื่อข้าราชการ สังกัด และส่วนราชการทั้งหมดให้ทันสมัยตรงกับระบบเดิมทันที')) {
            return;
        }

        const btn1 = document.getElementById('btnSyncPhone');
        const btn2 = document.getElementById('btnSyncBanner');
        const icon1 = document.getElementById('syncIcon');
        const icon2 = document.getElementById('syncIconBanner');

        if (btn1) btn1.disabled = true;
        if (btn2) btn2.disabled = true;
        if (icon1) icon1.classList.add('fa-spin');
        if (icon2) icon2.classList.add('fa-spin');

        if (window.Swal) {
            Swal.fire({
                title: 'กำลังซิงค์ข้อมูล...',
                html: '<div class="py-2"><i class="fa-solid fa-cloud-arrow-down fa-bounce fs-1 text-success mb-3"></i><br>กำลังเชื่อมต่อและดึงข้อมูลล่าสุดจาก <b>phone.phatthalung.go.th</b><br><small class="text-muted">กรุณารอสักครู่ (ประมาณ 3-5 วินาที)</small></div>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        try {
            const res = await fetch('<?= base_url('admin/directory/sync') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await res.json();

            if (data.status === 'success') {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ซิงค์ข้อมูลสำเร็จเรียบร้อย!',
                        html: `
                            <div class="text-start p-3 bg-light rounded-3 mt-2 small">
                                <div class="mb-1.5"><i class="fa-solid fa-circle-check text-success me-1.5"></i><b>ข้าราชการ/ผู้บริหาร:</b> ${data.stats.contacts} ท่าน</div>
                                <div class="mb-1.5"><i class="fa-solid fa-circle-check text-success me-1.5"></i><b>ส่วนราชการ/หน่วยงาน:</b> ${data.stats.departments} แห่ง</div>
                                <div class="mb-1.5"><i class="fa-solid fa-circle-check text-success me-1.5"></i><b>สังกัด/กระทรวง:</b> ${data.stats.ministries} สังกัด</div>
                                <div class="text-muted mt-2 border-top pt-2"><i class="fa-regular fa-clock me-1"></i>เวลาที่ซิงค์: ${data.stats.time}</div>
                            </div>
                        `,
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#059669'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    alert(`ซิงค์ข้อมูลสำเร็จ!\n- บุคลากร: ${data.stats.contacts} ท่าน\n- หน่วยงาน: ${data.stats.departments} แห่ง\n- สังกัด: ${data.stats.ministries} สังกัด`);
                    location.reload();
                }
            } else {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: data.message || 'ไม่สามารถซิงค์ข้อมูลได้',
                        confirmButtonColor: '#dc2626'
                    });
                } else {
                    alert(data.message || 'ไม่สามารถซิงค์ข้อมูลได้');
                }
            }
        } catch (err) {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'การเชื่อมต่อล้มเหลว',
                    text: 'ไม่สามารถติดต่อเซิร์ฟเวอร์เพื่อซิงค์ข้อมูลได้',
                    confirmButtonColor: '#dc2626'
                });
            } else {
                alert('ไม่สามารถติดต่อเซิร์ฟเวอร์เพื่อซิงค์ข้อมูลได้');
            }
        } finally {
            if (btn1) btn1.disabled = false;
            if (btn2) btn2.disabled = false;
            if (icon1) icon1.classList.remove('fa-spin');
            if (icon2) icon2.classList.remove('fa-spin');
        }
    };

    // ==============================================================
    // DOM Ready & Listeners
    // ==============================================================

    document.addEventListener('DOMContentLoaded', function() {
        refreshDropdowns();

        // Dismiss handlers for modals
        ['contactModal', 'ministryModal', 'departmentModal'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;

            el.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
                btn.addEventListener('click', () => hideModalSafe(id));
            });

            el.addEventListener('click', function(e) {
                if (e.target === el) {
                    hideModalSafe(id);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>


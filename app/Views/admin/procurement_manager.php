<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
$items = $items ?? [];
$categories = $categories ?? [];
$totalItems = count($items);
$activeItems = count(array_filter($items, fn($i) => !empty($i['active'])));
?>

<!-- Hero Cockpit Header -->
<div class="glass-card mb-4 p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 28px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(59, 130, 246, 0.10) 50%, rgba(99, 102, 241, 0.08) 100%); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="position-absolute" style="top: -40px; right: -30px; font-size: 15rem; color: rgba(16, 185, 129, 0.04); pointer-events: none; z-index: 0;">
        <i class="fa-solid fa-file-invoice-dollar"></i>
    </div>
    
    <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #059669; font-size: 0.85rem; font-weight: 600;">
                <i class="fa-solid fa-scale-balanced"></i> e-GP & Government Transparency Center
            </div>
            <h2 class="fw-bold mb-2" style="color: var(--text-primary); letter-spacing: -0.5px;">
                จัดการข้อมูลข่าวจัดซื้อจัดจ้างภาครัฐ (e-GP)
            </h2>
            <p class="text-secondary mb-0" style="font-size: 1rem; max-width: 780px;">
                ศูนย์ควบคุมและเผยแพร่ประกาศจัดซื้อจัดจ้าง ราคากลาง สรุปผลการจัดซื้อจัดจ้างรายเดือน (สขร.1) และสัญญาโครงการของส่วนราชการในจังหวัดพัทลุง ตามมาตรฐาน พ.ร.บ. การจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('procurement') ?>" target="_blank" class="btn btn-outline-secondary px-3 py-2 rounded-4 fw-medium d-flex align-items-center gap-2 shadow-sm transition-all" style="border-width: 1.5px; background: var(--bg-surface);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> <span>ดูหน้า e-GP ประชาชน</span>
            </a>
            <button type="button" class="btn btn-success px-4 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow transition-all" onclick="openAddProcurementModal()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 1rem;">
                <i class="fa-solid fa-circle-plus"></i> <span>เพิ่มประกาศใหม่</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="position-relative z-1 row g-3 mt-3 pt-3 border-top" style="border-color: var(--glass-border) !important;">
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-folder-tree text-primary me-1"></i> ประกาศทั้งหมดในระบบ</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold" id="statTotalItems" style="color: var(--text-primary);"><?= number_format($totalItems) ?></span>
                    <span class="small text-secondary">รายการ</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> เผยแพร่บนเว็บไซต์</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-success" id="statActiveItems"><?= number_format($activeItems) ?></span>
                    <span class="small text-secondary">รายการ (Active)</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-layer-group text-warning me-1"></i> หมวดหมู่ประกาศ</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-warning"><?= count($categories) ?></span>
                    <span class="small text-secondary">หมวดหมู่หลัก</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-shield-halved text-info me-1"></i> ระบบเชื่อมโยง e-GP</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                        <i class="fa-solid fa-signal me-1"></i>กรมบัญชีกลาง Online
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="glass-card mb-4 p-3 rounded-4" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
    <div class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--glass-border); color: var(--text-secondary);">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" class="form-control custom-input border-start-0" id="searchProcInput" placeholder="ค้นหาชื่อประกาศ, โครงการ, วงเงินงบประมาณ..." oninput="filterProcurements()">
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select custom-input" id="filterCategorySelect" onchange="filterProcurements()">
                <option value="all">📁 ทุกหมวดหมู่ประกาศ (All Categories)</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select custom-input" id="filterStatusSelect" onchange="filterProcurements()">
                <option value="all">สถานะทั้งหมด</option>
                <option value="active">เฉพาะที่เผยแพร่ (Active)</option>
                <option value="inactive">เฉพาะที่ซ่อนไว้ (Inactive)</option>
            </select>
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-outline-secondary w-100 rounded-3 py-2 fw-medium" onclick="resetProcFilter()" title="ล้างตัวกรอง">
                <i class="fa-solid fa-rotate-left"></i>
            </button>
        </div>
    </div>
</div>

<!-- Procurement Table List -->
<div class="glass-card rounded-4 overflow-hidden shadow-sm" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="procurementTable">
            <thead class="table-light border-bottom" style="background: rgba(0, 0, 0, 0.02);">
                <tr class="text-secondary fs-7 text-uppercase" style="letter-spacing: 0.5px;">
                    <th scope="col" style="width: 130px;" class="ps-4">วันที่ประกาศ</th>
                    <th scope="col">ชื่อโครงการ / ประกาศจัดซื้อจัดจ้าง</th>
                    <th scope="col" style="width: 180px;">หมวดหมู่</th>
                    <th scope="col" style="width: 140px;">วงเงินงบประมาณ</th>
                    <th scope="col" style="width: 100px;" class="text-center">เอกสาร</th>
                    <th scope="col" style="width: 110px;" class="text-center">สถานะ</th>
                    <th scope="col" style="width: 120px;" class="text-center pe-4">จัดการ</th>
                </tr>
            </thead>
            <tbody id="procurementTableBody">
                <?php if (empty($items)): ?>
                    <tr id="emptyProcRow">
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-folder-open fs-1 text-muted opacity-50 mb-3 d-block"></i>
                            <div class="fw-semibold">ยังไม่มีข้อมูลประกาศจัดซื้อจัดจ้างในระบบ</div>
                            <small class="text-muted">คลิกปุ่ม "เพิ่มประกาศใหม่" ด้านบนเพื่อเริ่มต้นสร้างประกาศแรก</small>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        $isActive = !empty($item['active']);
                        $cat = $item['category'] ?? 'ประกาศจัดซื้อจัดจ้าง';
                        $catBadge = 'badge rounded-pill px-2.5 py-1 ';
                        if (str_contains($cat, 'ราคากลาง')) {
                            $catBadge .= 'bg-purple-subtle text-purple border border-purple-subtle';
                        } elseif (str_contains($cat, 'สรุปผล') || str_contains($cat, 'สขร')) {
                            $catBadge .= 'bg-success-subtle text-success border border-success-subtle';
                        } elseif (str_contains($cat, 'สัญญา')) {
                            $catBadge .= 'bg-warning-subtle text-warning border border-warning-subtle';
                        } else {
                            $catBadge .= 'bg-primary-subtle text-primary border border-primary-subtle';
                        }

                        $docUrl = $item['attachment_url'] ?? '';
                        if (!empty($docUrl) && !str_starts_with($docUrl, 'http')) {
                            $docUrl = base_url($docUrl);
                        }
                        ?>
                        <tr class="proc-row" data-id="<?= esc($item['id']) ?>" data-category="<?= esc($cat) ?>" data-active="<?= $isActive ? 'active' : 'inactive' ?>">
                            <td class="ps-4 text-nowrap">
                                <div class="fw-semibold text-dark fs-7">
                                    <i class="fa-regular fa-calendar text-secondary me-1"></i>
                                    <?= !empty($item['date']) ? date('d/m/Y', strtotime($item['date'])) : '-' ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-1 proc-title">
                                    <?= esc($item['title']) ?>
                                </div>
                                <div class="text-muted fs-8 d-flex align-items-center gap-3">
                                    <span><i class="fa-solid fa-hashtag text-secondary me-1"></i>รหัส: <?= esc($item['id']) ?></span>
                                    <?php if (!empty($docUrl)): ?>
                                        <a href="<?= esc($docUrl) ?>" target="_blank" class="text-decoration-none text-primary">
                                            <i class="fa-solid fa-file-pdf text-danger me-1"></i>เปิดดูเอกสารแนบ
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span class="<?= $catBadge ?>" style="font-size: 0.75rem;">
                                    <?= esc($cat) ?>
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <span class="fw-semibold text-dark fs-7">
                                    <?= esc($item['budget'] ?? '-') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($docUrl)): ?>
                                    <a href="<?= esc($docUrl) ?>" target="_blank" class="btn btn-sm btn-light border rounded-pill px-2.5 py-1 text-danger shadow-xs" title="ดูไฟล์แนบ PDF">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted fs-8">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($isActive): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-circle-check me-1"></i>เผยแพร่
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-eye-slash me-1"></i>ซ่อน
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" title="แก้ไขประกาศ" onclick="openEditProcurementModal(<?= (int)$item['id'] ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" title="ลบประกาศ" onclick="deleteProcurement(<?= (int)$item['id'] ?>, '<?= htmlspecialchars(addslashes($item['title'] ?? ''), ENT_QUOTES) ?>')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: เพิ่ม / แก้ไข ประกาศจัดซื้อจัดจ้าง -->
<div class="modal fade" id="procurementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom py-3 px-4" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%); color: #fff;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="procModalTitle">
                    <i class="fa-solid fa-file-circle-plus text-emerald"></i>
                    <span>เพิ่มประกาศจัดซื้อจัดจ้างใหม่</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="procurementForm" onsubmit="handleProcurementSubmit(event)" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="procId" value="0">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-dark" for="procTitle">
                                ชื่อประกาศ / รายการจัดซื้อจัดจ้าง <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="procTitle" name="title" rows="3" required placeholder="เช่น ประกวดราคาจ้างก่อสร้างปรับปรุงผิวจราจร... ด้วยวิธีประกวดราคาอิเล็กทรอนิกส์ (e-bidding)"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" for="procCategory">
                                หมวดหมู่ประกาศ <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="procCategory" name="category" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" for="procDate">
                                วันที่เผยแพร่ประกาศ <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="procDate" name="date" required value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" for="procBudget">
                                วงเงินงบประมาณ (บาท)
                            </label>
                            <input type="text" class="form-control" id="procBudget" name="budget" placeholder="เช่น 1,500,000 หรือ 0">
                            <small class="text-muted" style="font-size: 0.75rem;">ระบุเป็นตัวเลข หรือเว้นว่างหากไม่มี</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" for="procMethod">
                                วิธีการจัดหา
                            </label>
                            <select class="form-select" id="procMethod" name="method">
                                <option value="e-bidding">e-bidding (ประกวดราคาอิเล็กทรอนิกส์)</option>
                                <option value="เฉพาะเจาะจง" selected>เฉพาะเจาะจง</option>
                                <option value="คัดเลือก">คัดเลือก</option>
                                <option value="ทั่วไป">เชิญชวนทั่วไป</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark" for="procDocFile">
                                อัปโหลดไฟล์เอกสารแนบ (PDF / DOCX)
                            </label>
                            <input type="file" class="form-control" id="procDocFile" name="doc_file" accept=".pdf,.doc,.docx,.zip">
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                                รองรับไฟล์ PDF, Word ขนาดไม่เกิน 20 MB (หากมีลิงก์อยู่แล้วสามารถระบุด้านล่างได้)
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark" for="procAttachmentUrl">
                                ลิงก์เอกสารแนบ / URL ภายนอก (ทางเลือก)
                            </label>
                            <input type="text" class="form-control" id="procAttachmentUrl" name="attachment_url" placeholder="https://www.gprocurement.go.th/... หรือ uploads/docs/...">
                        </div>

                        <div class="col-12 pt-2">
                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="procActive" name="active" value="1" checked style="width: 2.2rem; height: 1.2rem;">
                                <label class="form-check-label fw-bold text-dark cursor-pointer" for="procActive">
                                    เปิดแสดงผลบนเว็บไซต์ทันที (Active)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold" id="btnSaveProc">
                        <i class="fa-solid fa-save me-1.5"></i> บันทึกข้อมูลประกาศ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let procurementModalInstance = null;

    document.addEventListener('DOMContentLoaded', () => {
        const modalEl = document.getElementById('procurementModal');
        if (modalEl) {
            procurementModalInstance = new bootstrap.Modal(modalEl);
        }
    });

    function openAddProcurementModal() {
        document.getElementById('procurementForm').reset();
        document.getElementById('procId').value = '0';
        document.getElementById('procDate').value = '<?= date("Y-m-d") ?>';
        document.getElementById('procActive').checked = true;
        document.getElementById('procModalTitle').innerHTML = '<i class="fa-solid fa-file-circle-plus text-emerald"></i> <span>เพิ่มประกาศจัดซื้อจัดจ้างใหม่</span>';
        procurementModalInstance.show();
    }

    async function openEditProcurementModal(id) {
        document.getElementById('procurementForm').reset();
        document.getElementById('procId').value = id;
        document.getElementById('procModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-emerald"></i> <span>แก้ไขประกาศจัดซื้อจัดจ้าง</span>';

        try {
            const res = await App.fetch(`<?= base_url('admin/procurement/get-inline') ?>/${id}`);
            if (res.status === 'success' && res.item) {
                const item = res.item;
                document.getElementById('procTitle').value = item.title || '';
                document.getElementById('procCategory').value = item.category || 'ประกาศจัดซื้อจัดจ้าง';
                document.getElementById('procDate').value = item.date || '<?= date("Y-m-d") ?>';
                document.getElementById('procBudget').value = item.budget || '';
                document.getElementById('procAttachmentUrl').value = item.attachment_url || '';
                document.getElementById('procActive').checked = !!item.active;
                procurementModalInstance.show();
            } else {
                App.toast(res.message || 'ไม่สามารถดึงข้อมูลประกาศได้', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
        }
    }

    async function handleProcurementSubmit(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSaveProc');
        const form = document.getElementById('procurementForm');
        const formData = new FormData(form);

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> กำลังบันทึก...';

        try {
            const res = await App.fetch('<?= base_url("admin/procurement/save-inline") ?>', {
                method: 'POST',
                body: formData
            });

            if (res.status === 'success') {
                App.toast(res.message || 'บันทึกข้อมูลเรียบร้อยแล้ว', 'success');
                procurementModalInstance.hide();
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message || 'บันทึกไม่สำเร็จ', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-save me-1.5"></i> บันทึกข้อมูลประกาศ';
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1.5"></i> บันทึกข้อมูลประกาศ';
        }
    }

    async function deleteProcurement(id, title) {
        if (!confirm(`⚠️ ยืนยันการลบประกาศจัดซื้อจัดจ้าง:\n"${title}"\nออกจากระบบใช่หรือไม่?`)) return;

        try {
            const res = await App.fetch(`<?= base_url('admin/procurement/delete-inline') ?>/${id}`, {
                method: 'POST'
            });

            if (res.status === 'success') {
                App.toast(res.message || 'ลบข้อมูลเรียบร้อยแล้ว', 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message || 'ไม่สามารถลบข้อมูลได้', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
        }
    }

    function filterProcurements() {
        const query = (document.getElementById('searchProcInput').value || '').toLowerCase().trim();
        const cat = document.getElementById('filterCategorySelect').value;
        const status = document.getElementById('filterStatusSelect').value;

        const rows = document.querySelectorAll('.proc-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowTitle = (row.querySelector('.proc-title')?.textContent || '').toLowerCase();
            const rowCat = row.dataset.category || '';
            const rowStatus = row.dataset.active || '';

            const matchQuery = !query || rowTitle.includes(query);
            const matchCat = (cat === 'all') || (rowCat === cat);
            const matchStatus = (status === 'all') || (rowStatus === status);

            if (matchQuery && matchCat && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    function resetProcFilter() {
        document.getElementById('searchProcInput').value = '';
        document.getElementById('filterCategorySelect').value = 'all';
        document.getElementById('filterStatusSelect').value = 'all';
        filterProcurements();
    }
</script>
<?= $this->endSection() ?>

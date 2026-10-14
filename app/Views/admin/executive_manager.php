<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
    $executives = $executives ?? [];
    $categories = $categories ?? [];
?>

<!-- Hero Cockpit Header -->
<div class="glass-card mb-4 p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 28px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.12) 0%, rgba(16, 185, 129, 0.10) 50%, rgba(245, 158, 11, 0.08) 100%); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="position-absolute" style="top: -40px; right: -30px; font-size: 15rem; color: rgba(59, 130, 246, 0.04); pointer-events: none; z-index: 0;">
        <i class="fa-solid fa-user-tie"></i>
    </div>
    
    <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #2563eb; font-size: 0.85rem; font-weight: 600;">
                <i class="fa-solid fa-sitemap"></i> Executive Leadership & Organizational Chart
            </div>
            <h2 class="fw-bold mb-2" style="color: var(--text-primary); letter-spacing: -0.5px;">
                ทำเนียบคณะผู้บริหารจังหวัดพัทลุง (Leadership Directory)
            </h2>
            <p class="text-secondary mb-0" style="font-size: 1rem; max-width: 760px;">
                บริหารจัดการข้อมูลคณะผู้บริหารระดับสูง ผู้ว่าราชการจังหวัด รองผู้ว่าราชการจังหวัด และหัวหน้าส่วนราชการ พร้อมกำหนดตำแหน่งผังโครงสร้างบนหน้าเว็บไซต์แบบเรียลไทม์
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('executives') ?>" target="_blank" class="btn btn-outline-secondary px-3 py-2 rounded-4 fw-medium d-flex align-items-center gap-2 shadow-sm transition-all" style="border-width: 1.5px; background: var(--bg-surface);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> <span>ดูหน้าผังทำเนียบ</span>
            </a>
            <button type="button" onclick="ExecutiveStudio.open()" class="btn btn-primary px-4 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow transition-all" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; font-size: 1rem;">
                <i class="fa-solid fa-user-plus"></i> <span>เพิ่มรายนามผู้บริหาร</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row inside Cockpit -->
    <div class="position-relative z-1 row g-3 mt-3 pt-3 border-top" style="border-color: var(--glass-border) !important;">
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-users text-primary me-1"></i> จำนวนผู้บริหารในระบบ</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold" style="color: var(--text-primary);"><?= count($executives) ?></span>
                    <span class="small text-secondary">ท่าน</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-crown text-warning me-1"></i> ผู้ว่าราชการจังหวัดปัจจุบัน</div>
                <div class="fs-6 fw-bold text-truncate" style="color: var(--text-primary);" title="<?= !empty($executives[0]['name']) ? esc($executives[0]['name']) : 'ยังไม่ได้ระบุ' ?>">
                    <?= !empty($executives[0]['name']) ? esc($executives[0]['name']) : 'ยังไม่ได้ระบุ' ?>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-layer-group text-info me-1"></i> ผังโครงสร้าง (Layout)</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">Grid แถว & คอลัมน์</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-eye text-success me-1"></i> แสดงบนหน้าแรก</div>
                <div class="d-flex align-items-baseline gap-2">
                    <?php $featuredCount = count(array_filter($executives, fn($e) => !empty($e['featured']))); ?>
                    <span class="fs-4 fw-bold text-success"><?= $featuredCount ?></span>
                    <span class="small text-secondary">ท่าน (Featured)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="glass-card rounded-4 overflow-hidden shadow-sm" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
    <div class="p-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--glass-border) !important;">
        <div>
            <h5 class="fw-bold mb-1" style="color: var(--text-primary);">
                <i class="fa-solid fa-id-badge text-primary me-2"></i>รายนามคณะผู้บริหารและลำดับผังโครงสร้าง
            </h5>
            <div class="text-secondary small">จัดเรียงตาม แถว (Row) และ คอลัมน์ (Column) จากซ้ายไปขวา (เช่น แถว 1 = ผู้ว่าฯ, แถว 2 = รองผู้ว่าฯ)</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1.5">
                รวมทั้งหมด <?= count($executives) ?> รายการ
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="small text-secondary" style="background: rgba(0,0,0,0.02);">
                <tr>
                    <th class="ps-4 table-nowrap" style="width: 80px;">รูปถ่าย</th>
                    <th style="min-width: 260px;">ชื่อ-นามสกุล & ตำแหน่ง</th>
                    <th class="text-center table-nowrap" style="width: 140px;">ตำแหน่งผัง (Grid)</th>
                    <th class="table-nowrap">หมวดหมู่</th>
                    <th style="min-width: 200px;">ข้อมูลติดต่อ</th>
                    <th class="text-center table-nowrap" style="width: 150px;">สถานะดำรงตำแหน่ง</th>
                    <th class="text-center table-nowrap" style="width: 90px;">หน้าแรก</th>
                    <th class="pe-4 text-end table-nowrap" style="width: 170px;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($executives)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-users-slash fs-1 d-block mb-3 opacity-50"></i>
                            <h6 class="fw-bold mb-1" style="color: var(--text-primary);">ยังไม่มีข้อมูลผู้บริหารในระบบ</h6>
                            <p class="small text-secondary mb-3">กดปุ่ม <strong>"+ เพิ่มรายนามผู้บริหาร"</strong> ด้านบนเพื่อเริ่มบันทึกข้อมูล</p>
                            <button type="button" onclick="ExecutiveStudio.open()" class="btn btn-sm btn-primary rounded-pill px-4 py-2 fw-bold">
                                <i class="fa-solid fa-plus me-1"></i> เพิ่มผู้บริหารท่านแรก
                            </button>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($executives as $ex): ?>
                        <?php $isActive = !isset($ex['active']) || !empty($ex['active']); ?>
                        <tr class="<?= !$isActive ? 'table-light opacity-75' : '' ?>">
                            <td class="ps-4">
                                <div class="position-relative" style="width: 52px; height: 52px;">
                                    <img src="<?= !empty($ex['photo']) ? (strpos($ex['photo'], 'http') === 0 ? esc($ex['photo']) : base_url($ex['photo'])) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop' ?>" 
                                         alt="<?= esc($ex['name']) ?>" 
                                         class="rounded-circle shadow-sm" 
                                         style="width: 52px; height: 52px; object-fit: cover; object-position: top center; border: 2.5px solid <?= $isActive ? 'var(--accent-gold, #f59e0b)' : '#94a3b8' ?>;">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold d-flex align-items-center gap-1.5" style="color: var(--text-primary); font-size: 1rem;">
                                    <?= esc($ex['name']) ?>
                                    <?php if (!$isActive): ?>
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">เกษียณ/ซ่อน</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-secondary small mt-0.5"><?= esc($ex['position']) ?></div>
                            </td>
                            <td class="text-center table-nowrap">
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill" style="font-size: 0.82rem;">
                                    แถว <?= (int)($ex['row_num'] ?? 1) ?> : คอลัมน์ <?= (int)($ex['col_num'] ?? 1) ?>
                                </span>
                                <small class="text-secondary d-block mt-0.5" style="font-size: 0.75rem;">(ลำดับ: <?= (int)($ex['order_num'] ?? 1) ?>)</small>
                            </td>
                            <td class="table-nowrap">
                                <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill small" style="border-color: var(--glass-border) !important;">
                                    <?= esc($ex['category'] ?? 'คณะผู้บริหารระดับสูง') ?>
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <?php if (!empty($ex['phone'])): ?>
                                        <div class="mb-0.5"><i class="fa-solid fa-phone text-success me-1"></i> <a href="tel:<?= esc($ex['phone']) ?>" class="text-decoration-none text-secondary"><?= esc($ex['phone']) ?></a></div>
                                    <?php endif; ?>
                                    <?php if (!empty($ex['email'])): ?>
                                        <div><i class="fa-solid fa-envelope text-danger me-1"></i> <a href="mailto:<?= esc($ex['email']) ?>" class="text-decoration-none text-secondary"><?= esc($ex['email']) ?></a></div>
                                    <?php endif; ?>
                                    <?php if (empty($ex['phone']) && empty($ex['email'])): ?>
                                        <span class="text-secondary opacity-50">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center table-nowrap">
                                <?php if ($isActive): ?>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-2.5 py-1 fw-bold shadow-xs d-inline-flex align-items-center gap-1" title="คลิกเพื่อยกเลิกการแสดงผล (เกษียณ/พ้นตำแหน่ง โดยไม่ต้องลบข้อมูล)" onclick="toggleExecutiveActive('<?= $ex['id'] ?>', '<?= esc($ex['name'], 'js') ?>')">
                                        <i class="fa-solid fa-circle-check"></i> ดำรงตำแหน่ง
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 fw-bold d-inline-flex align-items-center gap-1" title="คลิกเพื่อเปิดการแสดงผลอีกครั้ง" onclick="toggleExecutiveActive('<?= $ex['id'] ?>', '<?= esc($ex['name'], 'js') ?>')">
                                        <i class="fa-solid fa-ban text-warning"></i> เกษียณ/ซ่อน
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td class="text-center table-nowrap">
                                <?php if (!empty($ex['featured']) && $isActive): ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 fw-bold">
                                        <i class="fa-solid fa-check me-1"></i> แสดง
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1">ซ่อน</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-end table-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-1.5">
                                    <a href="<?= base_url('executives/detail/' . esc($ex['id'])) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1" title="ดูประวัติและพิมพ์ PDF">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button type="button" onclick="ExecutiveStudio.open('<?= $ex['id'] ?>')" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 fw-medium" title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" onclick="ExecutiveStudio.deleteItem('<?= $ex['id'] ?>', '<?= esc($ex['name'], 'js') ?>')" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" title="ลบ">
                                        <i class="fa-solid fa-trash-can"></i>
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

<script>
    async function toggleExecutiveActive(id, name) {
        if (!confirm(`ต้องการเปลี่ยนสถานะการแสดงผลของ "${name}" ใช่หรือไม่?\n\n(หากยกเลิกการแสดงผล ข้อมูลจะถูกซ่อนจากหน้าเว็บไซต์ทันที แต่ประวัติจะยังคงบันทึกไว้ในระบบอย่างปลอดภัย)`)) return;

        try {
            const res = await App.fetch(`<?= base_url('admin/executives/toggle-active') ?>/${id}`, {
                method: 'POST'
            });

            if (res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                App.toast(res.message || 'ดำเนินการไม่สำเร็จ', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        }
    }
</script>

<!-- Include Executive Studio Modal Component -->
<?= $this->include('components/executive_studio') ?>

<?= $this->endSection() ?>

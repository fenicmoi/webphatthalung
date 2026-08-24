<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
    $executives = $executives ?? [];
    $categories = $categories ?? [];
?>

<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill fw-bold">
                    <i class="fa-solid fa-user-tie me-1"></i> Current Executive Leadership
                </span>
                <span class="text-muted small">ระบบจัดการข้อมูลคณะผู้บริหารปัจจุบันและผังโครงสร้าง</span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">จัดการคณะผู้บริหารปัจจุบัน</h3>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('executives') ?>" target="_blank" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> ดูหน้าแสดงผลทำเนียบ
            </a>
            <button type="button" onclick="ExecutiveStudio.open()" class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm hover-scale">
                <i class="fa-solid fa-user-plus"></i> + เพิ่มรายนามผู้บริหาร
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">จำนวนผู้บริหารในระบบ</span>
                        <h3 class="fw-bold mb-0 text-dark"><?= count($executives) ?> <small class="fs-6 text-muted">ท่าน</small></h3>
                    </div>
                    <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-users fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">ผู้ว่าราชการจังหวัดปัจจุบัน</span>
                        <h5 class="fw-bold mb-0 text-primary" style="font-size: 1.1rem;">
                            <?= !empty($executives[0]['name']) ? esc($executives[0]['name']) : 'ยังไม่ได้ระบุ' ?>
                        </h5>
                    </div>
                    <div class="rounded-circle p-3 bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-crown fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">รูปแบบการแสดงผล</span>
                        <h6 class="fw-bold mb-0 text-dark">Grid แถว & คอลัมน์ (ขอบทอง)</h6>
                    </div>
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-table-cells-large fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white p-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold mb-0 text-dark">รายนามคณะผู้บริหารและตำแหน่งผังโครงสร้าง</h5>
                <small class="text-muted">จัดเรียงตาม แถว (Row) และ คอลัมน์ (Column) จากซ้ายไปขวา</small>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    <i class="fa-solid fa-info-circle text-primary me-1"></i> แถว 1 = ผู้ว่าฯ, แถว 2 = รองผู้ว่าฯ
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" style="width: 80px;">รูปถ่าย</th>
                        <th class="py-3">ชื่อ-นามสกุล & ตำแหน่ง</th>
                        <th class="py-3 text-center" style="width: 140px;">ตำแหน่งผัง (Grid)</th>
                        <th class="py-3">หมวดหมู่</th>
                        <th class="py-3">ข้อมูลติดต่อ</th>
                        <th class="py-3 text-center" style="width: 100px;">หน้าแรก</th>
                        <th class="pe-4 py-3 text-end" style="width: 180px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($executives)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fs-1 d-block mb-3 opacity-25"></i>
                                ยังไม่มีข้อมูลผู้บริหารในระบบ กรุณากดปุ่ม "+ เพิ่มรายนามผู้บริหาร" ด้านบน
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($executives as $ex): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="position-relative" style="width: 52px; height: 52px;">
                                        <img src="<?= !empty($ex['photo']) ? (strpos($ex['photo'], 'http') === 0 ? esc($ex['photo']) : base_url($ex['photo'])) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop' ?>" 
                                             alt="<?= esc($ex['name']) ?>" 
                                             class="rounded-circle border border-2 border-warning shadow-sm" 
                                             style="width: 52px; height: 52px; object-fit: cover; object-position: top center;">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-6"><?= esc($ex['name']) ?></div>
                                    <div class="text-muted small"><?= esc($ex['position']) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2 py-1 rounded-pill">
                                        แถว <?= (int)($ex['row_num'] ?? 1) ?> : คอลัมน์ <?= (int)($ex['col_num'] ?? 1) ?>
                                    </span>
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">(ลำดับ: <?= (int)($ex['order_num'] ?? 1) ?>)</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill small">
                                        <?= esc($ex['category'] ?? 'คณะผู้บริหารระดับสูง') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <?php if (!empty($ex['phone'])): ?>
                                            <div><i class="fa-solid fa-phone text-success me-1"></i> <?= esc($ex['phone']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($ex['email'])): ?>
                                            <div><i class="fa-solid fa-envelope text-danger me-1"></i> <?= esc($ex['email']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($ex['featured'])): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                            <i class="fa-solid fa-check me-1"></i> แสดง
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-muted rounded-pill px-2 py-1">ซ่อน</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group">
                                        <a href="<?= base_url('executives/detail/' . esc($ex['id'])) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-2 me-1" title="ดูประวัติและพิมพ์ PDF">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <button type="button" onclick="ExecutiveStudio.open('<?= $ex['id'] ?>')" class="btn btn-sm btn-outline-primary rounded-pill px-2 me-1" title="แก้ไข">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" onclick="ExecutiveStudio.deleteItem('<?= $ex['id'] ?>', '<?= esc($ex['name'], 'js') ?>')" class="btn btn-sm btn-outline-danger rounded-pill px-2" title="ลบ">
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
</div>

<!-- Include Executive Studio Modal Component -->
<?= $this->include('components/executive_studio') ?>

<?= $this->endSection() ?>

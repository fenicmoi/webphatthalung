<?php
$stats      = $stats ?? ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'resolved' => 0];
$categories = $categories ?? \App\Models\CitizenContactModel::getCategories();
$statuses   = $statuses ?? \App\Models\CitizenContactModel::getStatuses();
$districts  = $districts ?? \App\Models\CitizenContactModel::getDistricts();
$search     = $search ?? '';
$currStatus = $currStatus ?? '';
$currCat    = $currCat ?? '';
$items      = $items ?? [];
$pager      = $pager ?? null;
?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="pb-4">
    <!-- 1. Majestic Hero Cockpit Banner -->
    <div class="dashboard-hero d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4 mb-4">
        <div class="d-flex align-items-center gap-3.5 z-1">
            <div style="width: 60px; height: 60px; border-radius: 18px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.25); box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                <i class="fa-solid fa-comments" style="font-size: 1.85rem; color: #34d399;"></i>
            </div>
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h1 class="h2 text-white fw-bold mb-0" style="font-size: 1.75rem; letter-spacing: -0.01em;">
                        ศูนย์จัดการเรื่องติดต่อและข้อร้องเรียนประชาชน
                    </h1>
                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.25); border: 1px solid rgba(52, 211, 153, 0.35); color: #6ee7b7; font-size: 0.85rem;">
                        <i class="fa-solid fa-circle-check me-1"></i>ศูนย์ดำรงธรรมออนไลน์
                    </span>
                </div>
                <p class="mb-0 text-white-50 d-flex flex-wrap align-items-center gap-2" style="font-size: 1rem;">
                    <span>ระบบติดตามสถานะคำร้องและข้อเสนอแนะ ศาลากลางจังหวัดพัทลุง</span>
                    <span>&bull;</span>
                    <span style="color: #6ee7b7;">เปิดรับเรื่องตลอด 24 ชั่วโมง</span>
                </p>
            </div>
        </div>

        <!-- Right Action Buttons -->
        <div class="d-flex flex-wrap align-items-center gap-2 z-1">
            <a href="<?= base_url('contact') ?>" target="_blank" class="btn rounded-pill px-3.5 py-2.5 fw-bold d-flex align-items-center gap-2 text-decoration-none shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; border: 1px solid rgba(255,255,255,0.2);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>ดูแบบฟอร์มหน้าเว็บประชาชน</span>
            </a>
            <button type="button" class="btn rounded-pill px-3 py-2.5 fw-bold text-white shadow-sm d-flex align-items-center gap-1.5" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);" onclick="window.print()">
                <i class="fa-solid fa-print"></i>
                <span class="d-none d-sm-inline">พิมพ์รายงาน</span>
            </button>
        </div>
    </div>

    <!-- 2. Master KPI Summary Cards (4 Elevated Cards) -->
    <div class="row g-4 mb-4">
        <!-- Total -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);"></div>
                <i class="fa-solid fa-folder-open watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold">เรื่องทั้งหมด (Total)</span>
                    <div class="kpi-icon-box" style="background: rgba(59, 130, 246, 0.12); color: #2563eb;">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap my-1">
                    <span class="kpi-number" style="color: #1d4ed8;"><?= number_format($stats['total']) ?></span>
                    <span class="kpi-unit">เรื่อง</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                    <span class="badge rounded-pill bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-chart-simple me-1"></i>ยอดรวมทุกสถานะ
                    </span>
                    <a href="<?= base_url('admin/contacts') ?>" class="text-decoration-none text-muted small fw-semibold">ดูทั้งหมด <i class="fa-solid fa-chevron-right ms-0.5"></i></a>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);"></div>
                <i class="fa-solid fa-clock-rotate-left watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold">รอดำเนินการ (Pending)</span>
                    <div class="kpi-icon-box" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap my-1">
                    <span class="kpi-number" style="color: #d97706;"><?= number_format($stats['pending']) ?></span>
                    <span class="kpi-unit">เรื่อง</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-bold px-2 py-1" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-circle me-1 animate-pulse" style="font-size: 0.5rem;"></i>ต้องตรวจรับเรื่อง
                    </span>
                    <a href="<?= base_url('admin/contacts?status=pending') ?>" class="text-decoration-none text-warning small fw-semibold">ตรวจรับ <i class="fa-solid fa-chevron-right ms-0.5"></i></a>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #06b6d4 0%, #0284c7 100%);"></div>
                <i class="fa-solid fa-arrows-spin watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold">กำลังประสานงาน</span>
                    <div class="kpi-icon-box" style="background: rgba(6, 182, 212, 0.12); color: #0891b2;">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap my-1">
                    <span class="kpi-number" style="color: #0284c7;"><?= number_format($stats['in_progress']) ?></span>
                    <span class="kpi-unit">เรื่อง</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                    <span class="badge rounded-pill bg-info-subtle text-info-emphasis fw-bold px-2 py-1" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-spinner me-1"></i>ส่งต่อหน่วยงานแล้ว
                    </span>
                    <a href="<?= base_url('admin/contacts?status=in_progress') ?>" class="text-decoration-none text-info-emphasis small fw-semibold">ติดตาม <i class="fa-solid fa-chevron-right ms-0.5"></i></a>
                </div>
            </div>
        </div>

        <!-- Resolved -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-stat-card-v2">
                <div class="card-accent-bar" style="background: linear-gradient(90deg, #10b981 0%, #059669 100%);"></div>
                <i class="fa-solid fa-circle-check watermark-icon"></i>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="kpi-card-label fw-bold">ดำเนินการเรียบร้อย</span>
                    <div class="kpi-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div class="kpi-number-wrap my-1">
                    <span class="kpi-number" style="color: #047857;"><?= number_format($stats['resolved']) ?></span>
                    <span class="kpi-unit">เรื่อง</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                    <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-check-double me-1"></i>แจ้งผลประชาชนแล้ว
                    </span>
                    <a href="<?= base_url('admin/contacts?status=resolved') ?>" class="text-decoration-none text-success small fw-semibold">ดูผล <i class="fa-solid fa-chevron-right ms-0.5"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Smart Filter & Search Bar -->
    <div class="card border-0 rounded-4 shadow-xs p-3.5 mb-4 bg-white">
        <!-- Quick Status Pills -->
        <div class="d-flex flex-wrap gap-2 mb-3 pb-2.5 border-bottom align-items-center">
            <span class="text-muted fw-bold small me-1"><i class="fa-solid fa-filter text-primary me-1"></i>ตัวกรองด่วน:</span>
            
            <a href="<?= base_url('admin/contacts') ?>" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none <?= empty($currStatus) ? 'btn-primary shadow-xs' : 'btn-light border text-secondary' ?>" style="font-size: 0.92rem;">
                <span>ทั้งหมด (<?= number_format($stats['total']) ?>)</span>
            </a>

            <a href="<?= base_url('admin/contacts?status=pending') ?>" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none <?= $currStatus === 'pending' ? 'btn-warning text-dark shadow-xs' : 'btn-light border text-secondary' ?>" style="font-size: 0.92rem;">
                <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem; color: #f59e0b;"></i>
                <span>รอดำเนินการ (<?= number_format($stats['pending']) ?>)</span>
            </a>

            <a href="<?= base_url('admin/contacts?status=in_progress') ?>" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none <?= $currStatus === 'in_progress' ? 'btn-info text-white shadow-xs' : 'btn-light border text-secondary' ?>" style="font-size: 0.92rem;">
                <i class="fa-solid fa-spinner me-1" style="font-size: 0.75rem;"></i>
                <span>กำลังประสานงาน (<?= number_format($stats['in_progress']) ?>)</span>
            </a>

            <a href="<?= base_url('admin/contacts?status=resolved') ?>" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold text-decoration-none <?= $currStatus === 'resolved' ? 'btn-success text-white shadow-xs' : 'btn-light border text-secondary' ?>" style="font-size: 0.92rem;">
                <i class="fa-solid fa-check me-1"></i>
                <span>ดำเนินการเรียบร้อย (<?= number_format($stats['resolved']) ?>)</span>
            </a>
        </div>

        <!-- Extended Filter Form -->
        <form method="GET" action="<?= base_url('admin/contacts') ?>" class="row g-2.5 align-items-center">
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="<?= esc($search) ?>" class="form-control border-start-0" placeholder="ค้นหา รหัสติดตาม, ชื่อผู้ร้อง, เบอร์โทร, หรือหัวข้อ..." style="font-size: 0.95rem;">
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <select name="category" class="form-select" style="font-size: 0.95rem;">
                    <option value="">-- ทุกประเภทเรื่อง --</option>
                    <?php foreach ($categories as $k => $c): ?>
                        <option value="<?= esc($k) ?>" <?= $currCat === $k ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-6 col-lg-2">
                <select name="status" class="form-select" style="font-size: 0.95rem;">
                    <option value="">-- ทุกสถานะ --</option>
                    <?php foreach ($statuses as $sk => $s): ?>
                        <option value="<?= esc($sk) ?>" <?= $currStatus === $sk ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold shadow-xs" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-filter me-1"></i>กรองข้อมูล
                </button>
                <?php if (!empty($search) || !empty($currStatus) || !empty($currCat)): ?>
                    <a href="<?= base_url('admin/contacts') ?>" class="btn btn-outline-secondary rounded-pill px-3" title="ล้างตัวกรอง">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- 4. Records Table Container -->
    <div class="admin-card overflow-hidden">
        <div class="admin-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h2 class="h2-section-title mb-0" style="font-size: 1.2rem;">
                    <i class="fa-solid fa-list-check text-primary me-2"></i>รายการเรื่องติดต่อและคำร้องเรียนในระบบ
                </h2>
                <small class="text-muted">ข้อมูลการรับเรื่องจากประชาชนผ่านทางเว็บไซต์</small>
            </div>
            <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.9rem;">
                พบ <?= count($items) ?> รายการ
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-modern mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="table-nowrap ps-4" style="width: 13%;">รหัสติดตาม</th>
                        <th class="table-nowrap" style="width: 14%;">ประเภทเรื่อง</th>
                        <th style="min-width: 260px;">หัวข้อเรื่อง & เนื้อหา</th>
                        <th class="table-nowrap" style="width: 15%;">ผู้ยื่นคำร้อง</th>
                        <th class="table-nowrap" style="width: 10%;">พื้นที่</th>
                        <th class="table-nowrap text-center" style="width: 12%;">สถานะ</th>
                        <th class="table-nowrap text-end pe-4" style="width: 14%;">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div style="width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #94a3b8;">
                                    <i class="fa-solid fa-inbox" style="font-size: 1.8rem;"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">ไม่พบรายการเรื่องติดต่อหรือข้อร้องเรียน</h6>
                                <p class="small text-muted mb-0">ไม่มีข้อมูลที่ตรงกับเงื่อนไขการค้นหาในขณะนี้</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $row): 
                            $statusKey = $row['status'] ?? 'pending';
                            $st = $statuses[$statusKey] ?? ['name' => $statusKey, 'badge' => 'bg-secondary text-white'];
                            $catKey = $row['category'] ?? 'general';
                            $cat = $categories[$catKey] ?? ['name' => $catKey, 'color' => 'primary'];
                        ?>
                            <tr>
                                <!-- Tracking Code -->
                                <td class="ps-4 table-nowrap">
                                    <div class="d-flex align-items-center gap-1.5">
                                        <span class="badge bg-light text-primary border fw-bold font-monospace px-2.5 py-1.5" style="font-size: 0.92rem;">
                                            <?= esc($row['tracking_code']) ?>
                                        </span>
                                        <button type="button" class="btn btn-sm btn-link p-0 text-muted" onclick="navigator.clipboard.writeText('<?= esc($row['tracking_code']) ?>'); App.toast('คัดลอกรหัสแล้ว', 'info');" title="คัดลอกรหัส">
                                            <i class="fa-regular fa-copy"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="table-nowrap">
                                    <span class="badge bg-<?= esc($cat['color']) ?>-subtle text-<?= esc($cat['color']) ?> border border-<?= esc($cat['color']) ?>-subtle rounded-pill px-2.5 py-1" style="font-size: 0.88rem;">
                                        <?= esc($cat['name']) ?>
                                    </span>
                                </td>

                                <!-- Subject & Message snippet -->
                                <td>
                                    <div class="fw-bold text-dark text-truncate mb-0.5" style="max-width: 320px; font-size: 1.05rem;" title="<?= esc($row['subject']) ?>">
                                        <?= esc($row['subject']) ?>
                                    </div>
                                    <small class="text-muted text-truncate d-block" style="max-width: 320px; font-size: 0.9rem;">
                                        <?= esc(mb_substr($row['message'], 0, 75)) ?>
                                    </small>
                                </td>

                                <!-- Citizen Info -->
                                <td class="table-nowrap">
                                    <div class="fw-bold text-dark" style="font-size: 0.98rem;"><?= esc($row['full_name']) ?></div>
                                    <a href="tel:<?= esc($row['phone']) ?>" class="text-decoration-none small text-muted d-block">
                                        <i class="fa-solid fa-phone me-1 text-primary"></i><?= esc($row['phone']) ?>
                                    </a>
                                </td>

                                <!-- District -->
                                <td class="table-nowrap">
                                    <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1" style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-location-dot me-1 text-danger"></i>อ.<?= esc($row['district'] ?? 'พัทลุง') ?>
                                    </span>
                                </td>

                                <!-- Status Badge -->
                                <td class="table-nowrap text-center">
                                    <?php if ($statusKey === 'pending'): ?>
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2.5 py-1.5 fw-semibold" style="font-size: 0.9rem;">
                                            <i class="fa-solid fa-clock me-1.5 animate-pulse" style="font-size: 0.6rem;"></i>รอดำเนินการ
                                        </span>
                                    <?php elseif ($statusKey === 'in_progress'): ?>
                                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle px-2.5 py-1.5 fw-semibold" style="font-size: 0.9rem;">
                                            <i class="fa-solid fa-spinner me-1.5" style="font-size: 0.75rem;"></i>กำลังประสานงาน
                                        </span>
                                    <?php elseif ($statusKey === 'resolved'): ?>
                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-semibold" style="font-size: 0.9rem;">
                                            <i class="fa-solid fa-circle-check me-1.5"></i>เรียบร้อย
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border px-2.5 py-1.5" style="font-size: 0.9rem;">
                                            <?= esc($st['name']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Action Buttons -->
                                <td class="table-nowrap text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-xs" onclick="openDetailModal(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-folder-open"></i>
                                            <span>จัดการ</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light border text-danger rounded-circle p-2" onclick="deleteContact(<?= $row['id'] ?>)" title="ลบรายการคำร้องนี้">
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
        
        <?php if (!empty($pager)): ?>
            <div class="p-3 border-top d-flex justify-content-center bg-light">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: View Detail & Update Status (Citizen Dossier) -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-bottom px-4 py-3" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%); color: white;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; color: #fff;">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="detailModalLabel">รายละเอียดคำร้อง & จัดการสถานะ</h5>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-white text-dark rounded-pill px-2.5 py-0.5 fw-bold font-monospace" id="detailTrackingBadge"></span>
                            <small style="color: #a7f3d0;" id="detDistrictHeader"></small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="updateStatusForm" onsubmit="handleUpdateStatus(event)">
                <input type="hidden" id="modalContactId" name="contact_id">
                <div class="modal-body p-4 bg-light">
                    <!-- Loading Spinner -->
                    <div id="modalLoadingSpinner" class="text-center py-5">
                        <i class="fa-solid fa-spinner fa-spin fs-1 text-primary"></i>
                        <p class="text-muted mt-2 fw-semibold">กำลังโหลดข้อมูลคำร้อง...</p>
                    </div>

                    <div id="modalDetailContent" class="d-none">
                        <!-- Requester Dossier Info Card -->
                        <div class="card border-0 rounded-4 p-3.5 mb-3 bg-white shadow-2xs">
                            <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom fw-bold text-dark" style="font-size: 1.05rem;">
                                <i class="fa-solid fa-id-card text-primary"></i> ข้อมูลผู้ยื่นเรื่อง
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block mb-0.5">ชื่อ-นามสกุล ผู้ร้อง:</span>
                                    <strong id="detFullName" class="text-dark fs-6"></strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block mb-0.5">เบอร์โทรศัพท์ติดต่อ:</span>
                                    <strong id="detPhone" class="text-primary fs-6"></strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block mb-0.5">อีเมล:</span>
                                    <span id="detEmail" class="text-secondary fw-semibold"></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block mb-0.5">พื้นที่เกี่ยวข้อง:</span>
                                    <span id="detDistrict" class="text-secondary fw-semibold"></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block mb-0.5">ประเภทเรื่อง:</span>
                                    <span id="detCategory" class="badge bg-primary-subtle text-primary border rounded-pill px-2.5 py-1"></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted small d-block mb-0.5">วันเวลาที่ยื่นเรื่อง:</span>
                                    <span id="detCreatedAt" class="text-secondary fw-semibold"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Subject & Message -->
                        <div class="card border-0 rounded-4 p-3.5 mb-3 bg-white shadow-2xs">
                            <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom fw-bold text-dark" style="font-size: 1.05rem;">
                                <i class="fa-solid fa-message text-success"></i> รายละเอียดข้อความ / ข้อร้องเรียน
                            </div>
                            <h5 id="detSubject" class="fw-bold text-dark mb-2.5" style="line-height: 1.4;"></h5>
                            <div id="detMessage" class="p-3 rounded-3 bg-light border text-dark" style="white-space: pre-wrap; line-height: 1.7; font-size: 1rem;"></div>

                            <!-- Attachment Preview -->
                            <div id="detAttachmentBox" class="mt-3 pt-2 border-top d-none">
                                <span class="text-muted small fw-bold d-block mb-1.5"><i class="fa-solid fa-paperclip text-primary me-1"></i>เอกสาร / หลักฐานแนบ:</span>
                                <a id="detAttachmentLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                    <span>เปิดดูไฟล์หลักฐานแนบ</span>
                                </a>
                            </div>
                        </div>

                        <!-- Update Status & Officer Notes Form -->
                        <div class="card border-0 rounded-4 p-3.5 bg-white shadow-2xs">
                            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom fw-bold text-dark" style="font-size: 1.05rem;">
                                <i class="fa-solid fa-sliders text-warning"></i> การดำเนินการของเจ้าหน้าที่ (Officer Action)
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">อัปเดตสถานะคำร้อง <span class="text-danger">*</span></label>
                                    <select id="modalStatusSelect" name="status" class="form-select" required style="font-size: 0.98rem;">
                                        <?php foreach ($statuses as $sk => $s): ?>
                                            <option value="<?= esc($sk) ?>"><?= esc($s['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark">
                                        บันทึกความคืบหน้า / ผลการประสานงาน (ประชาชนจะเห็นข้อความนี้เมื่อนำรหัสไปตรวจผล)
                                    </label>
                                    <textarea id="modalOfficerNote" name="officer_note" class="form-control" rows="3" placeholder="ระบุผลการตรวจสอบ หรือความคืบหน้า เช่น ส่งต่อ อบจ.พัทลุง หรือ ดำเนินการซ่อมแซมเสร็จสิ้น..." style="font-size: 0.98rem;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top bg-white p-3 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                    <button type="submit" id="btnSaveModalStatus" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-flex align-items-center gap-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>บันทึกการเปลี่ยนแปลง</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentDetailModal = null;

async function openDetailModal(id) {
    document.getElementById('modalContactId').value = id;
    document.getElementById('modalLoadingSpinner').classList.remove('d-none');
    document.getElementById('modalDetailContent').classList.add('d-none');

    if (!currentDetailModal) {
        currentDetailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    }
    currentDetailModal.show();

    try {
        const res = await fetch(`<?= base_url('admin/contacts/detail') ?>/${id}`);
        const json = await res.json();

        if (json.status === 'success') {
            const d = json.data;
            document.getElementById('detailTrackingBadge').innerText = d.tracking_code;
            document.getElementById('detDistrictHeader').innerText = 'อ.' + (d.district || 'เมืองพัทลุง');
            document.getElementById('detFullName').innerText = d.full_name;
            document.getElementById('detPhone').innerText = d.phone;
            document.getElementById('detEmail').innerText = d.email || '-';
            document.getElementById('detDistrict').innerText = 'อ.' + (d.district || 'เมืองพัทลุง') + ' จ.พัทลุง';
            document.getElementById('detCategory').innerText = d.category_name;
            document.getElementById('detCreatedAt').innerText = d.created_at_fmt;
            document.getElementById('detSubject').innerText = d.subject;
            document.getElementById('detMessage').innerText = d.message;

            const attBox = document.getElementById('detAttachmentBox');
            if (d.attachment_url) {
                attBox.classList.remove('d-none');
                document.getElementById('detAttachmentLink').href = d.attachment_url;
            } else {
                attBox.classList.add('d-none');
            }

            document.getElementById('modalStatusSelect').value = d.status;
            document.getElementById('modalOfficerNote').value = d.officer_note || '';

            document.getElementById('modalLoadingSpinner').classList.add('d-none');
            document.getElementById('modalDetailContent').classList.remove('d-none');
        }
    } catch (e) {
        console.error(e);
        if (typeof App !== 'undefined' && App.toast) {
            App.toast('เกิดข้อผิดพลาดในการโหลดข้อมูลคำร้อง', 'error');
        }
    }
}

async function handleUpdateStatus(e) {
    e.preventDefault();
    const id = document.getElementById('modalContactId').value;
    const form = document.getElementById('updateStatusForm');
    const btn = document.getElementById('btnSaveModalStatus');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>กำลังบันทึก...';

    const formData = new FormData(form);

    try {
        const res = await fetch(`<?= base_url('admin/contacts/update-status') ?>/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const json = await res.json();

        if (json.status === 'success') {
            if (typeof App !== 'undefined' && App.toast) {
                App.toast(json.message || 'บันทึกสำเร็จ', 'success');
            }
            setTimeout(() => location.reload(), 700);
        } else {
            alert(json.message || 'บันทึกไม่สำเร็จ');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-check me-1"></i>บันทึกการเปลี่ยนแปลง';
        }
    } catch (err) {
        console.error(err);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-check me-1"></i>บันทึกการเปลี่ยนแปลง';
    }
}

async function deleteContact(id) {
    if (!confirm('คุณต้องการลบรายการคำร้องนี้หรือไม่? ข้อมูลและไฟล์แนบจะถูกลบถาวร')) {
        return;
    }

    try {
        const res = await fetch(`<?= base_url('admin/contacts/delete') ?>/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const json = await res.json();
        if (json.status === 'success') {
            if (typeof App !== 'undefined' && App.toast) {
                App.toast(json.message || 'ลบรายการสำเร็จ', 'success');
            }
            setTimeout(() => location.reload(), 600);
        }
    } catch (e) {
        console.error(e);
        alert('เกิดข้อผิดพลาดในการลบ');
    }
}
</script>
<?= $this->endSection() ?>

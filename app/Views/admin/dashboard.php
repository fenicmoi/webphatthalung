<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$stats = $stats ?? [
    'news' => '0 เรื่อง',
    'services_requests' => '0 รายการ',
    'users' => '0 ราย',
    'monthly_visitors' => '0 ครั้ง'
];

$fullName = session()->get('full_name') ?? 'ดร.สุเทพ ผู้ดูแลระบบสูง';
$roleName = (session()->get('role') === 'admin') ? 'Super Admin' : 'Officer';

// Time of day greeting in Thai
$hour = (int)date('H');
if ($hour < 12) {
    $greeting = 'สวัสดีตอนเช้า';
} elseif ($hour < 16) {
    $greeting = 'สวัสดีตอนบ่าย';
} else {
    $greeting = 'สวัสดีตอนเย็น';
}

$newsVal = preg_replace('/[^0-9,]/', '', $stats['news'] ?? '0');
$newsUnit = trim(str_replace($newsVal, '', $stats['news'] ?? 'เรื่อง')) ?: 'เรื่อง';

$reqVal = preg_replace('/[^0-9,]/', '', $stats['services_requests'] ?? '0');
$reqUnit = trim(str_replace($reqVal, '', $stats['services_requests'] ?? 'เรื่อง')) ?: 'เรื่อง';

$usersVal = preg_replace('/[^0-9,]/', '', $stats['users'] ?? '0');
$usersUnit = trim(str_replace($usersVal, '', $stats['users'] ?? 'ราย')) ?: 'ราย';

$viewsVal = preg_replace('/[^0-9,]/', '', $stats['monthly_visitors'] ?? '0');
$viewsUnit = trim(str_replace($viewsVal, '', $stats['monthly_visitors'] ?? 'ครั้ง')) ?: 'ครั้ง';
?>

<!-- 1. Majestic Hero Cockpit Banner -->
<div class="dashboard-hero d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
    <div class="d-flex align-items-center gap-3.5 z-1">
        <div style="width: 64px; height: 64px; border-radius: 18px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.25); box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
            <i class="fa-solid fa-gem" style="font-size: 2rem; color: #34d399;"></i>
        </div>
        <div>
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                <h1 class="h2 text-white fw-bold mb-0" style="font-size: 1.85rem; letter-spacing: -0.01em;">
                    <?= $greeting ?>, <?= esc($fullName) ?>
                </h1>
                <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(52, 211, 153, 0.35); color: #6ee7b7; font-size: 0.85rem;">
                    <i class="fa-solid fa-shield-halved me-1"></i><?= $roleName ?>
                </span>
            </div>
            <p class="mb-0 text-white-50 d-flex flex-wrap align-items-center gap-2" style="font-size: 1.05rem;">
                <span>ศูนย์กลางบริหารจัดการดิจิทัล จังหวัดพัทลุง</span>
                <span>&bull;</span>
                <span style="color: #6ee7b7;"><i class="fa-solid fa-circle me-1" style="font-size: 0.55rem;"></i>ระบบออนไลน์ 100% ปลอดภัยสูง</span>
            </p>
        </div>
    </div>

    <!-- Right Controls: Live Clock & Quick Hero Actions -->
    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 z-1">
        <div class="px-3.5 py-2 rounded-4 text-sm-end" style="background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.12); backdrop-filter: blur(10px);">
            <div class="text-white fw-bold d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                <i class="fa-regular fa-clock text-warning"></i>
                <span id="liveClockWidget"><?= date('H:i:s') ?> น.</span>
            </div>
            <small style="color: #cbd5e1; font-size: 0.88rem;">
                <?= function_exists('thai_date') ? thai_date(date('Y-m-d'), 'full', false) : date('d/m/Y') ?>
            </small>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('news') ?>" class="btn rounded-pill px-3.5 py-2.5 fw-bold d-flex align-items-center gap-2 text-decoration-none shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; border: 1px solid rgba(255,255,255,0.2);">
                <i class="fa-solid fa-circle-plus"></i>
                <span>เขียนข่าวใหม่</span>
            </a>
            <a href="<?= base_url('admin/nora-ai') ?>" class="btn rounded-pill px-3 py-2.5 fw-bold d-flex align-items-center gap-2 text-decoration-none text-white shadow-sm" style="background: rgba(168, 85, 247, 0.25); border: 1px solid rgba(192, 132, 252, 0.4); backdrop-filter: blur(8px);">
                <i class="fa-solid fa-wand-magic-sparkles" style="color: #f0abfc;"></i>
                <span>น้องโนรา AI</span>
            </a>
        </div>
    </div>
</div>

<!-- 2. Master KPI Highlights Grid (Elevated Cards with Micro-Trends) -->
<div class="row g-4 mb-4">
    <!-- Card 1: News & Publications -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card-v2">
            <div class="card-accent-bar" style="background: linear-gradient(90deg, #10b981 0%, #059669 100%);"></div>
            <i class="fa-solid fa-newspaper watermark-icon"></i>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="kpi-card-label fw-bold">ข่าวสาร & กิจกรรม</span>
                <div class="kpi-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </div>
            <div class="kpi-number-wrap my-1">
                <span class="kpi-number" style="color: #047857;"><?= $newsVal ?></span>
                <span class="kpi-unit"><?= $newsUnit ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                <span class="badge rounded-pill bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-trend-up me-1"></i>เผยแพร่ต่อเนื่อง
                </span>
                <a href="<?= base_url('news') ?>" class="text-decoration-none text-muted small fw-semibold">จัดการ <i class="fa-solid fa-chevron-right ms-0.5"></i></a>
            </div>
        </div>
    </div>

    <!-- Card 2: Citizen Contacts & MOI Mailbox -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card-v2">
            <div class="card-accent-bar" style="background: linear-gradient(90deg, #06b6d4 0%, #0284c7 100%);"></div>
            <i class="fa-solid fa-comments watermark-icon"></i>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="kpi-card-label fw-bold">เรื่องติดต่อ & ร้องเรียน</span>
                <div class="kpi-icon-box" style="background: rgba(6, 182, 212, 0.12); color: #0891b2;">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
            </div>
            <div class="kpi-number-wrap my-1">
                <span class="kpi-number" style="color: #0284c7;"><?= $reqVal ?></span>
                <span class="kpi-unit"><?= $reqUnit ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                <span class="badge rounded-pill bg-info-subtle text-info-emphasis fw-bold px-2 py-1" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-inbox me-1"></i>กล่องจดหมาย MOI
                </span>
                <a href="<?= base_url('admin/mailbox') ?>" class="text-decoration-none text-muted small fw-semibold">ดูรายการ <i class="fa-solid fa-chevron-right ms-0.5"></i></a>
            </div>
        </div>
    </div>

    <!-- Card 3: Officers & System Security -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card-v2">
            <div class="card-accent-bar" style="background: linear-gradient(90deg, #8b5cf6 0%, #6d28d9 100%);"></div>
            <i class="fa-solid fa-user-shield watermark-icon"></i>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="kpi-card-label fw-bold">เจ้าหน้าที่ในระบบ</span>
                <div class="kpi-icon-box" style="background: rgba(139, 92, 246, 0.12); color: #7c3aed;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <div class="kpi-number-wrap my-1">
                <span class="kpi-number" style="color: #6d28d9;"><?= $usersVal ?></span>
                <span class="kpi-unit"><?= $usersUnit ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                <span class="badge rounded-pill bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-lock me-1"></i>ความปลอดภัยระดับ 2
                </span>
                <span class="text-muted small fw-semibold">สิทธิ์แอดมิน</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Citizen Traffic & News Views -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card-v2">
            <div class="card-accent-bar" style="background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);"></div>
            <i class="fa-solid fa-chart-line watermark-icon"></i>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="kpi-card-label fw-bold">ยอดเข้าชมข่าวสาร</span>
                <div class="kpi-icon-box" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>
            <div class="kpi-number-wrap my-1">
                <span class="kpi-number" style="color: #d97706;"><?= $viewsVal ?></span>
                <span class="kpi-unit"><?= $viewsUnit ?></span>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top" style="border-color: #f1f5f9 !important;">
                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis fw-bold px-2 py-1" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-bolt me-1 text-warning"></i>สถิติเรียลไทม์
                </span>
                <span class="text-muted small fw-semibold">+18.4% วีคนี้</span>
            </div>
        </div>
    </div>
</div>

<!-- 3. Management Hub (Interactive App Cards) -->
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2 class="h2-section-title mb-1">
                <i class="fa-solid fa-shapes text-emerald me-2"></i>หมวดหมู่การทำงานหลัก (System Management Hub)
            </h2>
            <p class="text-muted mb-0" style="font-size: 0.98rem;">คลิกเพื่อเข้าสู่พื้นที่บริหารจัดการตามโครงสร้างระบบเว็บไซต์</p>
        </div>
        <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill d-none d-md-inline-block fw-semibold" style="font-size: 0.9rem;">
            <i class="fa-solid fa-cube text-primary me-1"></i>5 โมดูลหลัก
        </span>
    </div>

    <div class="row g-3">
        <!-- Hub 1: News & Media -->
        <div class="col-6 col-md-4 col-xl">
            <a href="<?= base_url('news') ?>" class="hub-card-v2">
                <div class="hub-arrow-v2"><i class="fa-solid fa-arrow-right"></i></div>
                <div class="hub-icon-v2" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(5, 150, 105, 0.25) 100%); color: #047857;">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <div class="hub-title">ข่าวสาร & มีเดีย</div>
                <div class="hub-desc">ประชาสัมพันธ์, Feeds, วีดิทัศน์ YouTube</div>
            </a>
        </div>

        <!-- Hub 2: Provincial Strategy -->
        <div class="col-6 col-md-4 col-xl">
            <a href="<?= base_url('admin/executives') ?>" class="hub-card-v2">
                <div class="hub-arrow-v2"><i class="fa-solid fa-arrow-right"></i></div>
                <div class="hub-icon-v2" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.25) 100%); color: #b45309;">
                    <i class="fa-solid fa-landmark"></i>
                </div>
                <div class="hub-title">ข้อมูลจังหวัด</div>
                <div class="hub-desc">คณะผู้บริหาร, ทำเนียบผู้ว่าฯ, ยุทธศาสตร์ GIS</div>
            </a>
        </div>

        <!-- Hub 3: Web CMS -->
        <div class="col-6 col-md-4 col-xl">
            <a href="<?= base_url('admin/pages') ?>" class="hub-card-v2">
                <div class="hub-arrow-v2"><i class="fa-solid fa-arrow-right"></i></div>
                <div class="hub-icon-v2" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(37, 99, 235, 0.25) 100%); color: #1d4ed8;">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="hub-title">จัดการหน้าเว็บ</div>
                <div class="hub-desc">Static Pages, เมนูบาร์, แบนเนอร์สไลด์</div>
            </a>
        </div>

        <!-- Hub 4: Citizen Services -->
        <div class="col-6 col-md-4 col-xl">
            <a href="<?= base_url('admin/mailbox') ?>" class="hub-card-v2">
                <div class="hub-arrow-v2"><i class="fa-solid fa-arrow-right"></i></div>
                <div class="hub-icon-v2" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(8, 145, 178, 0.25) 100%); color: #0e7490;">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div class="hub-title">บริการประชาชน</div>
                <div class="hub-desc">กล่องจดหมาย MOI, ติดต่อร้องเรียน</div>
            </a>
        </div>

        <!-- Hub 5: AI & System Settings -->
        <div class="col-12 col-md-4 col-xl">
            <a href="<?= base_url('admin/nora-ai') ?>" class="hub-card-v2" style="border-color: rgba(168, 85, 247, 0.35);">
                <div class="hub-arrow-v2" style="background: #a855f7; color: #fff;"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <div class="hub-icon-v2" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.18) 0%, rgba(124, 58, 237, 0.28) 100%); color: #7c3aed;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <div class="hub-title text-purple-700">ระบบ & น้องโนรา AI</div>
                <div class="hub-desc">AI คลังความรู้, จัดการ DB, ตั้งค่าระบบ</div>
            </a>
        </div>
    </div>
</div>

<!-- 4. Interactive Visual Analytics Section (Charts) -->
<div class="row g-4 mb-4">
    <!-- Chart 1: Citizen Traffic Trend (Area Chart) -->
    <div class="col-lg-8">
        <div class="admin-card h-100">
            <div class="admin-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h2 class="h2-section-title mb-0" style="font-size: 1.2rem;">
                        <i class="fa-solid fa-chart-area text-emerald me-2"></i>สถิติแนวโน้มผู้เข้าชมเว็บไซต์ (Weekly Traffic Trend)
                    </h2>
                    <small class="text-muted">ข้อมูลเปรียบเทียบสถิติการเปิดอ่านเนื้อหาในรอบ 7 วันล่าสุด</small>
                </div>
                <div class="d-flex align-items-center gap-1.5 bg-light p-1 rounded-pill border">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.85rem;">7 วันล่าสุด</button>
                    <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted px-2.5 py-1" style="font-size: 0.85rem;" onclick="App.toast('ข้อมูลย้อนหลัง 30 วันกำลังรวบรวม', 'info')">30 วัน</button>
                </div>
            </div>
            <div class="admin-card-body p-3">
                <div style="height: 240px; position: relative;">
                    <canvas id="trafficTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2: Device Analytics (Donut Chart) -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h2 class="h2-section-title mb-0" style="font-size: 1.2rem;">
                    <i class="fa-solid fa-mobile-screen-button text-primary me-2"></i>สัดส่วนอุปกรณ์ผู้เข้าชม (Devices)
                </h2>
                <small class="text-muted">ช่องทางที่ประชาชนเข้าถึงเว็บไซต์</small>
            </div>
            <div class="admin-card-body p-3 d-flex flex-column align-items-center justify-content-center">
                <div style="height: 180px; width: 100%; position: relative;">
                    <canvas id="deviceShareChart"></canvas>
                </div>
                <div class="w-100 mt-3 pt-2 border-top d-flex justify-content-around text-center">
                    <div>
                        <div class="fw-bold text-emerald" style="font-size: 1.1rem;">62%</div>
                        <small class="text-muted" style="font-size: 0.85rem;"><i class="fa-solid fa-mobile-screen me-1"></i>สมาร์ทโฟน</small>
                    </div>
                    <div>
                        <div class="fw-bold text-primary" style="font-size: 1.1rem;">33%</div>
                        <small class="text-muted" style="font-size: 0.85rem;"><i class="fa-solid fa-desktop me-1"></i>คอมพิวเตอร์</small>
                    </div>
                    <div>
                        <div class="fw-bold text-warning" style="font-size: 1.1rem;">5%</div>
                        <small class="text-muted" style="font-size: 0.85rem;"><i class="fa-solid fa-tablet-screen-button me-1"></i>แท็บเล็ต</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 5. Main Section: Recent Activity & Nora AI Cockpit -->
<div class="row g-4">
    <!-- Left Column: Dual Tab Table (News & System Audit Log) -->
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <!-- Nav Tabs for Table -->
                <ul class="nav nav-pills gap-2" id="dashboardTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-3 py-1.5 fw-bold" id="news-tab" data-bs-toggle="pill" data-bs-target="#news-panel" type="button" role="tab">
                            <i class="fa-solid fa-newspaper me-1.5 text-primary"></i>ข่าวสารล่าสุด
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-bold" id="activity-tab" data-bs-toggle="pill" data-bs-target="#activity-panel" type="button" role="tab">
                            <i class="fa-solid fa-clock-rotate-left me-1.5 text-warning"></i>บันทึกกิจกรรมระบบ
                        </button>
                    </li>
                </ul>

                <a href="<?= base_url('news') ?>" class="btn-modern-outline text-decoration-none">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> จัดการข่าวทั้งหมด
                </a>
            </div>

            <div class="tab-content" id="dashboardTabContent">
                <!-- Tab Panel 1: Recent News -->
                <div class="tab-pane fade show active" id="news-panel" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="min-width: 280px;">หัวเรื่องประกาศ</th>
                                    <th class="table-nowrap">หมวดหมู่</th>
                                    <th class="table-nowrap text-center">สถานะ</th>
                                    <th class="table-nowrap text-end">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentNews)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fa-regular fa-newspaper fa-2x mb-2 d-block text-secondary"></i>
                                            ยังไม่มีข่าวสารในระบบ 
                                            <a href="<?= base_url('news') ?>" class="btn btn-sm btn-primary ms-2 rounded-pill">เพิ่มข่าวสารแรก</a>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentNews as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #047857;">
                                                        <i class="fa-solid fa-file-lines"></i>
                                                    </div>
                                                    <div>
                                                        <a href="<?= base_url('news') ?>" class="text-dark fw-bold text-decoration-none d-block text-truncate" style="max-width: 360px; font-size: 1.05rem;" title="<?= htmlspecialchars($item['title'] ?? '') ?>">
                                                            <?= htmlspecialchars($item['title'] ?? '') ?>
                                                        </a>
                                                        <small class="text-muted" style="font-size: 0.88rem;">
                                                            <i class="fa-regular fa-clock me-1 text-primary"></i>
                                                            <?= !empty($item['created_at']) ? (function_exists('thai_date') ? thai_date($item['created_at'], 'short', true) : $item['created_at']) : '-' ?>
                                                            &bull; <i class="fa-solid fa-eye me-1 text-warning"></i><?= number_format($item['views_count'] ?? 0) ?> วิว
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-nowrap">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.9rem;">
                                                    <?= htmlspecialchars($item['category'] ?? 'ข่าวประชาสัมพันธ์') ?>
                                                </span>
                                            </td>
                                            <td class="table-nowrap text-center">
                                                <?php if (($item['status'] ?? 'published') === 'published'): ?>
                                                    <span class="badge-status-published">
                                                        <i class="fa-solid fa-circle me-1.5" style="font-size: 0.55rem;"></i>เผยแพร่อยู่
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge-status-draft">
                                                        <i class="fa-solid fa-clock me-1.5" style="font-size: 0.55rem;"></i>แบบร่าง
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="table-nowrap text-end">
                                                <a href="<?= base_url('news') ?>" class="btn btn-sm btn-light border px-2.5 py-1.5 rounded-3" title="ไปแก้ไขข่าว">
                                                    <i class="fa-solid fa-pen-to-square text-primary" style="font-size: 1rem;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Panel 2: Activity Logs -->
                <div class="tab-pane fade" id="activity-panel" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="table-nowrap">เวลา</th>
                                    <th>กิจกรรมของระบบ</th>
                                    <th class="table-nowrap">ผู้ดำเนินการ</th>
                                    <th class="table-nowrap text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($activityLogs)): ?>
                                    <?php foreach ($activityLogs as $log): ?>
                                        <tr>
                                            <td class="table-nowrap text-muted" style="font-size: 0.9rem;">
                                                <i class="fa-regular fa-clock me-1 text-primary"></i>
                                                <?= date('H:i d/m/Y', strtotime($log['timestamp'] ?? 'now')) ?>
                                            </td>
                                            <td class="fw-semibold text-dark" style="font-size: 0.98rem;">
                                                <?= htmlspecialchars($log['activity'] ?? '') ?>
                                            </td>
                                            <td class="table-nowrap text-secondary" style="font-size: 0.92rem;">
                                                <i class="fa-regular fa-user me-1 text-muted"></i><?= htmlspecialchars($log['user'] ?? 'System') ?>
                                            </td>
                                            <td class="table-nowrap text-center">
                                                <span class="badge bg-<?= ($log['badge'] ?? 'success') ?>-subtle text-<?= ($log['badge'] ?? 'success') ?> border border-<?= ($log['badge'] ?? 'success') ?>-subtle rounded-pill px-2.5 py-1">
                                                    <?= htmlspecialchars($log['status'] ?? 'Success') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Nora AI Assistant & Telemetry -->
    <div class="col-xl-4">
        <!-- Widget 1: Nora AI Interactive Cockpit -->
        <div class="nora-assistant-card mb-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2.5">
                    <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; color: #fdf4ff;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-white mb-0" style="font-size: 1.15rem;">น้องโนรา AI Assistant</h5>
                        <small style="color: #e9d5ff; font-size: 0.88rem;">ผู้ช่วยอัจฉริยะระบบบริหารราชการ</small>
                    </div>
                </div>
                <span class="badge rounded-pill" style="background: rgba(52, 211, 153, 0.25); border: 1px solid rgba(52, 211, 153, 0.4); color: #6ee7b7; font-size: 0.8rem;">
                    <i class="fa-solid fa-circle me-1 animate-pulse" style="font-size: 0.5rem;"></i>Active
                </span>
            </div>

            <p class="text-white-50 mb-3" style="font-size: 0.95rem; line-height: 1.5;">
                พิมพ์คำถามหรือเลือกคำสั่งด่วนให้น้องโนราช่วยวิเคราะห์ข้อมูล ร่างข่าวสาร หรือตรวจสอบเนื้อหาเว็บได้ทันที
            </p>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="<?= base_url('admin/nora-ai?prompt=' . urlencode('ช่วยร่างข่าวประชาสัมพันธ์โครงการพัฒนาการท่องเที่ยวพัทลุง')) ?>" class="nora-prompt-chip">
                    <i class="fa-solid fa-pen-nib text-warning"></i> ร่างข่าวประชาสัมพันธ์
                </a>
                <a href="<?= base_url('admin/nora-ai?prompt=' . urlencode('สรุปประเด็นยุทธศาสตร์การพัฒนาจังหวัดพัทลุง')) ?>" class="nora-prompt-chip">
                    <i class="fa-solid fa-chart-pie text-info"></i> สรุปยุทธศาสตร์
                </a>
                <a href="<?= base_url('admin/nora-ai?prompt=' . urlencode('ตรวจสอบความครบถ้วนของข้อมูลเว็บไซต์ตามเกณฑ์ ITA')) ?>" class="nora-prompt-chip">
                    <i class="fa-solid fa-shield-check text-success"></i> ตรวจสอบ ITA
                </a>
            </div>

            <a href="<?= base_url('admin/nora-ai') ?>" class="btn w-100 rounded-pill py-2 text-white fw-bold d-flex align-items-center justify-content-center gap-2 text-decoration-none shadow-sm" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%); border: 1px solid rgba(255,255,255,0.25);">
                <span>เปิดห้องทำงานน้องโนรา AI แบบเต็มจอ</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Widget 2: Quick System Shortcuts -->
        <div class="admin-card mb-4">
            <div class="admin-card-header d-flex align-items-center justify-content-between">
                <h2 class="h2-section-title mb-0" style="font-size: 1.15rem;">
                    <i class="fa-solid fa-bolt text-warning me-2"></i>เมนูลัดประจำวัน (Shortcuts)
                </h2>
            </div>
            <div class="admin-card-body p-3">
                <div class="d-grid gap-2.5">
                    <a href="<?= base_url('admin/pages') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2.5 rounded-3 text-decoration-none" style="font-size: 1rem;">
                        <span class="d-flex align-items-center"><i class="fa-solid fa-file-circle-plus text-primary me-2.5" style="font-size: 1.1rem;"></i>สร้างหน้าเว็บ Static ใหม่</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.85rem;"></i>
                    </a>
                    <a href="<?= base_url('admin/menu') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2.5 rounded-3 text-decoration-none" style="font-size: 1rem;">
                        <span class="d-flex align-items-center"><i class="fa-solid fa-compass text-success me-2.5" style="font-size: 1.1rem;"></i>จัดการเมนูบาร์เว็บ</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.85rem;"></i>
                    </a>
                    <a href="<?= base_url('admin/banners') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2.5 rounded-3 text-decoration-none" style="font-size: 1rem;">
                        <span class="d-flex align-items-center"><i class="fa-solid fa-images text-info me-2.5" style="font-size: 1.1rem;"></i>แบนเนอร์ & สไลด์หน้าแรก</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.85rem;"></i>
                    </a>
                    <a href="<?= base_url('admin/settings') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2.5 rounded-3 text-decoration-none" style="font-size: 1rem;">
                        <span class="d-flex align-items-center"><i class="fa-solid fa-sliders text-secondary me-2.5" style="font-size: 1.1rem;"></i>ตั้งค่าระบบและโลโก้</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.85rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Widget 3: System Health & Telemetry -->
        <div class="card border-0 rounded-4 p-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2.5">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #38bdf8;">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-white" style="font-size: 1.05rem;">สถานะเซิร์ฟเวอร์ & ความปลอดภัย</h6>
                </div>
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.8rem;">
                    Healthy
                </span>
            </div>

            <!-- Storage Meter -->
            <div class="mb-3">
                <div class="d-flex justify-content-between text-white-50 small mb-1">
                    <span>พื้นที่จัดเก็บ (Storage Disk)</span>
                    <span class="text-white fw-bold"><?= ($systemHealth['storageUsage'] ?? 38) ?>%</span>
                </div>
                <div class="progress" style="height: 6px; background: rgba(255,255,255,0.1);">
                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= ($systemHealth['storageUsage'] ?? 38) ?>%"></div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25 small text-white-50">
                <span>ฐานข้อมูล: <strong class="text-white">MySQL 8.x</strong></span>
                <span>PHP: <strong class="text-white"><?= PHP_VERSION ?></strong></span>
                <span>CI4: <strong class="text-emerald">v4.5+</strong></span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js for Interactive Dashboard Analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Live Digital Clock Widget
    const clockElement = document.getElementById('liveClockWidget');
    if (clockElement) {
        setInterval(function() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            clockElement.innerText = `${h}:${m}:${s} น.`;
        }, 1000);
    }

    // 2. Weekly Traffic Trend Chart (Chart.js Area Chart)
    const trafficCtx = document.getElementById('trafficTrendChart');
    if (trafficCtx) {
        const labels = <?= json_encode($chartData['days'] ?? ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์']) ?>;
        const dataValues = <?= json_encode($chartData['activeUsers'] ?? [920, 1050, 980, 1150, 1210, 1180, 1250]) ?>;

        const ctx = trafficCtx.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.01)');

        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'ยอดเข้าชมเว็บไซต์ (ครั้ง)',
                    data: dataValues,
                    borderColor: '#059669',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#059669',
                    pointBorderWidth: 2.5,
                    pointRadius: 4.5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Prompt', size: 13 },
                        bodyFont: { family: 'Prompt', size: 13 },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `ยอดเข้าชม: ${context.parsed.y.toLocaleString()} ครั้ง`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Prompt', size: 12 }, color: '#64748b' }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { family: 'Prompt', size: 12 }, color: '#64748b' }
                    }
                }
            }
        });
    }

    // 3. Device Share Donut Chart
    const deviceCtx = document.getElementById('deviceShareChart');
    if (deviceCtx) {
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: ['มือถือ (Mobile)', 'คอมพิวเตอร์ (Desktop)', 'แท็บเล็ต (Tablet)'],
                datasets: [{
                    data: [62, 33, 5],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Prompt', size: 12 },
                        bodyFont: { family: 'Prompt', size: 12 },
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ` สัดส่วน: ${context.parsed}%`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?= $this->endSection() ?>

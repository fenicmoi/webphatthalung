<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$stats = $stats ?? [
    'news' => '0 เรื่อง',
    'services_requests' => '0 รายการ',
    'users' => '0 ราย',
    'monthly_visitors' => '0 ครั้ง'
];
?>

<!-- Header Greeting -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #0f172a;">แผงควบคุมระบบ (Dashboard)</h4>
        <p class="text-muted mb-0" style="font-size: 0.92rem;">
            ภาพรวมสถิติและเครื่องมือบริหารจัดการเว็บไซต์จังหวัดพัทลุง
        </p>
    </div>
    <div>
        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem; font-weight: 500;">
            <i class="fa-regular fa-calendar-check text-primary me-2"></i><?= function_exists('thai_date') ? thai_date(date('Y-m-d'), 'full', false) : date('d/m/Y') ?>
        </span>
    </div>
</div>

<!-- KPI Cards Grid -->
<div class="row g-4 mb-4">
    <!-- Card 1: News -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-bold" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">ข่าวและกิจกรรม</span>
                <div class="kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1" style="color: #0f172a;"><?= $stats['news'] ?></h3>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.82rem; color: #10b981;">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <strong>+12%</strong> <span class="text-muted ms-1">จากเดือนที่ผ่านมา</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Service Requests -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-bold" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">บริการประชาชน</span>
                <div class="kpi-icon-box" style="background: #ecfdf5; color: #059669;">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1" style="color: #0f172a;"><?= $stats['services_requests'] ?></h3>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.82rem; color: #059669;">
                <i class="fa-solid fa-check-double"></i>
                <span>ดำเนินการเสร็จสิ้น 98%</span>
            </div>
        </div>
    </div>

    <!-- Card 3: System Users -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-bold" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">เจ้าหน้าที่ในระบบ</span>
                <div class="kpi-icon-box" style="background: #f5f3ff; color: #7c3aed;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1" style="color: #0f172a;"><?= $stats['users'] ?></h3>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.82rem; color: #64748b;">
                <i class="fa-solid fa-shield-halved text-primary"></i>
                <span>สิทธิ์ Admin / Officer</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Monthly Visitors -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-bold" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">การเข้าชมเว็บไซต์</span>
                <div class="kpi-icon-box" style="background: #fffbeb; color: #d97706;">
                    <i class="fa-solid fa-signal"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-1" style="color: #0f172a;"><?= $stats['monthly_visitors'] ?></h3>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.82rem; color: #10b981;">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <strong>+24.5%</strong> <span class="text-muted ms-1">อัตราเติบโต</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Section: Recent News & Quick Status -->
<div class="row g-4">
    <div class="col-xl-8">
        <div class="admin-card">
            <div class="admin-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-newspaper text-primary me-2"></i>รายการข่าวสารล่าสุด</h6>
                </div>
                <a href="<?= base_url('news') ?>" class="btn-modern-outline text-decoration-none" style="padding: 0.35rem 0.85rem; font-size: 0.82rem;">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> จัดการข่าวทั้งหมด
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50%;">หัวเรื่องประกาศ</th>
                            <th>หมวดหมู่</th>
                            <th>สถานะ</th>
                            <th class="text-end">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-dark">ประกาศโครงการพัฒนาคุณภาพชีวิตประจำปีงบประมาณ</strong>
                                <br><small class="text-muted"><i class="fa-regular fa-clock me-1"></i>เผยแพร่เมื่อ: 2 ชั่วโมงที่แล้ว</small>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">ข่าวประชาสัมพันธ์</span></td>
                            <td><span class="text-success fw-medium" style="font-size: 0.85rem;"><i class="fa-solid fa-circle me-1" style="font-size: 0.45rem;"></i>เผยแพร่อยู่</span></td>
                            <td class="text-end">
                                <a href="<?= base_url('news') ?>" class="btn btn-sm btn-light border" title="ไปจัดการ"><i class="fa-solid fa-pen-to-square text-primary"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-dark">รายงานสถานการณ์คุณภาพน้ำและการประปาภูมิภาคจังหวัดพัทลุง</strong>
                                <br><small class="text-muted"><i class="fa-regular fa-clock me-1"></i>เผยแพร่เมื่อ: เมื่อวานนี้</small>
                            </td>
                            <td><span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded-pill">ข่าวด่วน/สาธารณูปโภค</span></td>
                            <td><span class="text-success fw-medium" style="font-size: 0.85rem;"><i class="fa-solid fa-circle me-1" style="font-size: 0.45rem;"></i>เผยแพร่อยู่</span></td>
                            <td class="text-end">
                                <a href="<?= base_url('news') ?>" class="btn btn-sm btn-light border" title="ไปจัดการ"><i class="fa-solid fa-pen-to-square text-primary"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-dark">กำหนดการรับข้อเสนอโครงการส่งเสริมและสนับสนุนเกษตรกรท้องถิ่น</strong>
                                <br><small class="text-muted"><i class="fa-regular fa-clock me-1"></i>เผยแพร่เมื่อ: 3 วันที่แล้ว</small>
                            </td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">บริการประชาชน</span></td>
                            <td><span class="text-warning fw-medium" style="font-size: 0.85rem;"><i class="fa-solid fa-clock me-1" style="font-size: 0.45rem;"></i>รอตรวจทาน</span></td>
                            <td class="text-end">
                                <a href="<?= base_url('news') ?>" class="btn btn-sm btn-light border" title="ไปจัดการ"><i class="fa-solid fa-pen-to-square text-primary"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Quick System Links Card -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-bolt text-warning me-2"></i>เมนูลัด (Quick Shortcuts)</h6>
            </div>
            <div class="admin-card-body p-3">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('admin/pages') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2 rounded-3 text-decoration-none">
                        <span><i class="fa-solid fa-file-circle-plus text-primary me-2"></i>สร้างหน้าเว็บ Static ใหม่</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                    </a>
                    <a href="<?= base_url('admin/menu') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2 rounded-3 text-decoration-none">
                        <span><i class="fa-solid fa-compass text-success me-2"></i>จัดการเมนูบาร์เว็บ</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                    </a>
                    <a href="<?= base_url('admin/service-banners') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2 rounded-3 text-decoration-none">
                        <span><i class="fa-solid fa-hand-holding-heart text-info me-2"></i>จัดการแบนเนอร์บริการ</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                    </a>
                    <a href="<?= base_url('admin/settings') ?>" class="btn btn-light text-start border d-flex align-items-center justify-content-between p-2 rounded-3 text-decoration-none">
                        <span><i class="fa-solid fa-sliders text-secondary me-2"></i>ตั้งค่าระบบเว็บไซต์</span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Tip Box -->
        <div class="card border-0 rounded-4 p-4 text-white shadow-sm" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: #60a5fa;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h6 class="fw-bold mb-0 text-white">ระบบพร้อมใช้งาน</h6>
            </div>
            <p class="small text-white-50 mb-0">
                คุณสามารถจัดการโครงสร้างหน้าเว็บ เมนู และเนื้อหาได้อิสระจากแผงควบคุมนี้ ข้อมูลจะถูกซิงก์สู่หน้าเว็บไซต์หลักทันที
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

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
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h3 class="fw-bold mb-1">แผงควบคุมระบบ (Executive Dashboard)</h3>
        <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
            รายงานสถิติสดและบริหารจัดการข้อมูลหน้าเว็บไซต์หลักจังหวัดพัทลุง
        </p>
    </div>
    <div>
        <span class="glass-badge">
            <i class="fa-regular fa-clock"></i> ข้อมูลอัปเดตล่าสุด: <?= date('d/m/Y') ?>
        </span>
    </div>
</div>

<!-- KPI Cards Grid -->
<div class="row g-4 mb-5">
    <!-- Card 1: News -->
    <div class="col-sm-6 col-xl-3">
        <div class="glass-card hover-lift position-relative h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">ข่าวและกิจกรรม</span>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(59, 130, 246, 0.15); color: var(--accent-primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-2"><?= $stats['news'] ?></h2>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.85rem; color: #10b981;">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <strong>+12%</strong> <span style="color: var(--text-muted);">จากเดือนที่ผ่านมา</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Service Requests -->
    <div class="col-sm-6 col-xl-3">
        <div class="glass-card hover-lift position-relative h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">คำร้องบริการประชาชน</span>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.15); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-2"><?= $stats['services_requests'] ?></h2>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.85rem; color: #10b981;">
                <i class="fa-solid fa-check-double"></i>
                <span>ดำเนินการเสร็จสิ้น 98%</span>
            </div>
        </div>
    </div>

    <!-- Card 3: System Users -->
    <div class="col-sm-6 col-xl-3">
        <div class="glass-card hover-lift position-relative h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">เจ้าหน้าที่ในระบบ</span>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(99, 102, 241, 0.15); color: var(--accent-secondary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-2"><?= $stats['users'] ?></h2>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.85rem; color: var(--text-secondary);">
                <i class="fa-solid fa-shield-halved text-primary"></i>
                <span>สิทธิ์ Admin / Officer</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Monthly Visitors -->
    <div class="col-sm-6 col-xl-3">
        <div class="glass-card hover-lift position-relative h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span style="color: var(--text-muted); font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">การเข้าชมเว็บไซต์ (เดือนนี้)</span>
                <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(245, 158, 11, 0.15); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fa-solid fa-signal"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-2"><?= $stats['monthly_visitors'] ?></h2>
            <div class="d-flex align-items-center gap-1" style="font-size: 0.85rem; color: #10b981;">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <strong>+24.5%</strong> <span style="color: var(--text-muted);">อัตราการเติบโต</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Content & Interactive Actions Table Section -->
<div class="row g-4">
    <div class="col-xl-8">
        <div class="glass-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-bold mb-1"><i class="fa-solid fa-newspaper text-primary me-2"></i>รายการข่าวด้านหน้าที่เพิ่งอัปเดตล่าสุด</h5>
                    <small style="color: var(--text-muted);">จัดการและควบคุมการแสดงผลข่าวประชาสัมพันธ์</small>
                </div>
                <div>
                    <button class="btn-modern" style="padding: 0.5rem 1rem; font-size: 0.85rem;" onclick="App.toast('เปิดกล่องเพิ่มข้อความข่าวสารใหม่', 'success')">
                        <i class="fa-solid fa-plus"></i> เพิ่มข่าวใหม่
                    </button>
                </div>
            </div>

            <!-- Modern Data Table -->
            <div class="table-responsive">
                <table class="table-modern">
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
                                <strong>ประกาศโครงการพัฒนาคุณภาพชีวิตประจำปีงบประมาณ</strong>
                                <br><small style="color: var(--text-muted);">เผยแพร่เมื่อ: 2 ชั่วโมงที่แล้ว</small>
                            </td>
                            <td><span class="glass-badge" style="font-size: 0.75rem;">ข่าวประชาสัมพันธ์</span></td>
                            <td><span style="color: #10b981; font-weight: 500; font-size: 0.85rem;"><i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i>เผยแพร่อยู่</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light border-0" title="แก้ไข" onclick="App.toast('โหลดข้อมูลแก้ไขข่าว...', 'info')"><i class="fa-solid fa-pen-to-square text-primary"></i></button>
                                <button class="btn btn-sm btn-light border-0" title="ลบ" onclick="App.toast('ตรวจสอบการลบข้อมูล (Protected by CSRF)', 'error')"><i class="fa-solid fa-trash text-danger"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>รายงานสถานการณ์คุณภาพน้ำและการประปาภูมิภาคจังหวัดพัทลุง</strong>
                                <br><small style="color: var(--text-muted);">เผยแพร่เมื่อ: เมื่อวานนี้</small>
                            </td>
                            <td><span class="glass-badge" style="font-size: 0.75rem;">ข่าวด่วน/สาธารณูปโภค</span></td>
                            <td><span style="color: #10b981; font-weight: 500; font-size: 0.85rem;"><i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i>เผยแพร่อยู่</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light border-0" title="แก้ไข" onclick="App.toast('โหลดข้อมูลแก้ไขข่าว...', 'info')"><i class="fa-solid fa-pen-to-square text-primary"></i></button>
                                <button class="btn btn-sm btn-light border-0" title="ลบ" onclick="App.toast('ตรวจสอบการลบข้อมูล (Protected by CSRF)', 'error')"><i class="fa-solid fa-trash text-danger"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>กำหนดการรับข้อเสนอโครงการส่งเสริมและสนับสนุนเกษตรกรท้องถิ่น</strong>
                                <br><small style="color: var(--text-muted);">เผยแพร่เมื่อ: 3 วันที่แล้ว</small>
                            </td>
                            <td><span class="glass-badge" style="font-size: 0.75rem;">บริการประชาชน</span></td>
                            <td><span style="color: #f59e0b; font-weight: 500; font-size: 0.85rem;"><i class="fa-solid fa-clock me-1" style="font-size: 0.6rem;"></i>รอตรวจทาน</span></td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light border-0" title="แก้ไข" onclick="App.toast('โหลดข้อมูลแก้ไขข่าว...', 'info')"><i class="fa-solid fa-pen-to-square text-primary"></i></button>
                                <button class="btn btn-sm btn-light border-0" title="ลบ" onclick="App.toast('ตรวจสอบการลบข้อมูล (Protected by CSRF)', 'error')"><i class="fa-solid fa-trash text-danger"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Quick System Status / Database Verification Card -->
        <div class="glass-card mb-4" style="border-radius: 24px; border: 1px solid rgba(99, 102, 241, 0.3);">
            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-database text-warning"></i>
                <span>สถานะฐานข้อมูล (Database Phase 2)</span>
            </h5>
            <p style="color: var(--text-secondary); font-size: 0.9rem;">
                ตารางข้อมูลโครงสร้างพื้นฐานสำหรับระบบพัทลุงพอร์ทัล ได้รับการดีไซน์และพร้อมรันคำสั่ง Migration
            </p>

            <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                <li class="py-2 d-flex justify-content-between align-items-center border-bottom" style="border-color: var(--glass-border) !important;">
                    <span><i class="fa-solid fa-table me-2 text-primary"></i>ตาราง <code>users</code></span>
                    <span class="glass-badge" style="color: #10b981; font-size: 0.75rem;">Model Ready</span>
                </li>
                <li class="py-2 d-flex justify-content-between align-items-center border-bottom" style="border-color: var(--glass-border) !important;">
                    <span><i class="fa-solid fa-table me-2 text-primary"></i>ตาราง <code>news</code></span>
                    <span class="glass-badge" style="color: #10b981; font-size: 0.75rem;">Model Ready</span>
                </li>
                <li class="py-2 d-flex justify-content-between align-items-center">
                    <span><i class="fa-solid fa-table me-2 text-primary"></i>ตาราง <code>services</code></span>
                    <span class="glass-badge" style="color: #10b981; font-size: 0.75rem;">Model Ready</span>
                </li>
            </ul>
        </div>

        <div class="glass-card text-center" style="background: var(--gradient-hero); color: white !important;">
            <i class="fa-solid fa-lightbulb-on mb-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
            <h5 class="fw-bold text-white mb-2">เคล็ดลับระบบ No-Reload Admin</h5>
            <p style="font-size: 0.9rem; opacity: 0.95; margin-bottom: 1.2rem;">
                เมื่อคุณแก้ไขข่าวสาร ระบบจะใช้ Async Fetch API ในการบันทึกข้อมูลและแสดง Toast โดยหน้าเว็บจะไม่รันโหลดขาวกวนใจ
            </p>
            <button class="btn btn-light fw-bold px-4 rounded-pill" onclick="App.toast('พร้อมเริ่มลุยงานบริการประชาชนแล้วครับ!', 'success')">
                ตรวจสอบการทำงาน
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

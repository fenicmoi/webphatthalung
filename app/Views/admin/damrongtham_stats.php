<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-3 px-md-4 py-4">

    <!-- Header & Action Buttons -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">
                    <i class="fa-solid fa-database me-1"></i> ระบบสถิติฐานข้อมูลรายวัน (Database Auto-Accumulation)
                </span>
                <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 small fw-semibold">
                    <i class="fa-solid fa-server me-1"></i> บันทึกใน MySQL แล้ว <?= count($dailyRecords ?? []) ?> รายการ
                </span>
            </div>
            <h1 class="h3 fw-bold text-dark mb-0">บันทึกสถิติประจำวันและคำนวณยอดสะสมอัตโนมัติ</h1>
            <p class="text-muted small mb-0">เจ้าหน้าที่กรอกข้อมูลประจำวัน ระบบจะนำไปคำนวณบวกสะสมในฐานข้อมูล และอัปเดตแดชบอร์ดประชาชนทันที</p>
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <button type="button" class="btn btn-danger rounded-pill px-3 shadow-sm fw-bold" onclick="printDamrongthamReport()">
                <i class="fa-solid fa-file-pdf me-1.5"></i> สั่งพิมพ์ / PDF
            </button>
            <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm fw-bold" onclick="exportDamrongthamImage()">
                <i class="fa-solid fa-image me-1.5"></i> ส่งออกเป็นรูปภาพ (PNG)
            </button>
            <button type="button" class="btn text-white rounded-pill px-3.5 shadow-sm fw-bold" style="background-color: #06C755; border-color: #06C755;" onclick="shareDamrongthamToLine()">
                <i class="fa-brands fa-line me-1.5 fs-5 align-middle"></i> แชร์ภาพส่งใน LINE
            </button>
            <a href="<?= base_url('damrongtham#statistics-dashboard') ?>" target="_blank" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ดูแดชบอร์ดประชาชน
            </a>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#baselineModal">
                <i class="fa-solid fa-sliders me-1"></i> ตั้งค่ายอดสะสมตั้งต้น
            </button>
            <a href="<?= base_url('admin/damrongtham/stats/reset-zero') ?>" class="btn btn-outline-danger rounded-pill px-3" onclick="return confirm('คำเตือน: คุณต้องการรีเซ็ตข้อมูลสถิติตั้งต้นและประวัติรายวันทั้งหมดเป็น 0 ใช่หรือไม่?');" title="รีเซ็ตยอดสถิติทั้งหมดเป็น 0">
                <i class="fa-solid fa-rotate-left me-1"></i> รีเซ็ตเป็น 0
            </a>
            <a href="#dailyFormCard" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                <i class="fa-solid fa-plus me-1"></i> บันทึกข้อมูลประจำวัน
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-xs" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-xs" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- Summary KPI Preview Cards (Auto Calculated) -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100" style="border-top: 4px solid #e11d48 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">เรื่องร้องเรียนสะสมรวม</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2.5 py-1 small fw-semibold">Auto-Calculated</span>
                </div>
                <h2 class="fw-bold text-dark mb-0 font-monospace"><?= number_format($stats['cumulative']['total_received'] ?? 0) ?> <span class="fs-6 fw-normal text-muted">เรื่อง</span></h2>
                <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">
                    (ตั้งต้น <?= number_format($baseline['base_received'] ?? 0) ?> + รายวัน <?= number_format($stats['database_stats']['sum_daily_received'] ?? 0) ?>)
                </small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100" style="border-top: 4px solid #0284c7 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">ดำเนินการแล้วเสร็จ (ยุติ)</span>
                    <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 small fw-bold">
                        ร้อยละ <?= esc($stats['cumulative']['total_resolved_percent'] ?? 0) ?>%
                    </span>
                </div>
                <h2 class="fw-bold text-primary mb-0 font-monospace" style="color: #0284c7 !important;"><?= number_format($stats['cumulative']['total_resolved'] ?? 0) ?> <span class="fs-6 fw-normal text-muted">เรื่อง</span></h2>
                <small class="text-success fw-bold mt-1 d-block" style="font-size: 0.8rem;">
                    เป้าหมายสำเร็จตามเกณฑ์มาตรฐาน &ge; 95%
                </small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white h-100" style="border-top: 4px solid #10b981 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold">ระหว่างดำเนินการ</span>
                    <span class="badge bg-warning bg-opacity-20 text-dark rounded-pill px-2.5 py-1 small fw-bold">
                        ร้อยละ <?= esc($stats['cumulative']['total_pending_percent'] ?? 0) ?>%
                    </span>
                </div>
                <h2 class="fw-bold text-dark mb-0 font-monospace" style="color: #059669 !important;"><?= number_format($stats['cumulative']['total_pending'] ?? 0) ?> <span class="fs-6 fw-normal text-muted">เรื่อง</span></h2>
                <small class="text-muted mt-1 d-block" style="font-size: 0.8rem;">อยู่ระหว่างตรวจสอบข้อเท็จจริง/ประสานงาน</small>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="small fw-bold opacity-90"><i class="fa-solid fa-calendar-day me-1"></i> ล่าสุดประจำวัน</span>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2 py-0.5" style="font-size: 0.72rem;"><?= esc($stats['daily']['date_text'] ?? 'วันนี้') ?></span>
                </div>
                <div class="row g-2 text-center mt-1">
                    <div class="col-4 border-end border-white border-opacity-25">
                        <div class="fs-4 fw-bold font-monospace"><?= (int)($stats['daily']['received'] ?? 0) ?></div>
                        <small class="opacity-75" style="font-size:0.72rem;">รับเรื่อง</small>
                    </div>
                    <div class="col-4 border-end border-white border-opacity-25">
                        <div class="fs-4 fw-bold font-monospace"><?= (int)($stats['daily']['pending'] ?? 0) ?></div>
                        <small class="opacity-75" style="font-size:0.72rem;">กำลังทำ</small>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold font-monospace text-warning"><?= (int)($stats['daily']['general_inquiries'] ?? 0) ?></div>
                        <small class="opacity-75" style="font-size:0.72rem;">สอบถาม</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OFFICIAL INFOGRAPHIC & PRINTABLE REPORT DOCUMENT CARD -->
    <div id="damrongthamOfficialReportCard" class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white border-bottom border-light-subtle pt-3.5 px-4 pb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger text-white rounded-circle p-2" style="width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-file-invoice"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">ใบรายงานสรุปผลการดำเนินงานและสถิติสะสม (Official Report Card & Infographic)</h5>
                        <small class="text-muted">เอกสารรายงานทางการ ออกแบบสำหรับสั่งพิมพ์ PDF ขนาด A4 และส่งออกเป็นรูปภาพความละเอียดสูงเพื่อแชร์ใน LINE</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold shadow-xs" onclick="printDamrongthamReport()">
                        <i class="fa-solid fa-print me-1"></i> สั่งพิมพ์ / PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-xs" onclick="exportDamrongthamImage()">
                        <i class="fa-solid fa-file-image me-1"></i> ส่งออกรูป (PNG)
                    </button>
                    <button type="button" class="btn btn-sm text-white rounded-pill px-3 fw-bold shadow-xs" style="background-color: #06C755; border-color: #06C755;" onclick="shareDamrongthamToLine()">
                        <i class="fa-brands fa-line me-1"></i> แชร์ส่งใน LINE
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-2 p-md-4" style="background: #f1f5f9;">
            <!-- Outer preview scroll container for responsive screens -->
            <div class="table-responsive p-1 text-center" style="border-radius: 12px;">
                
                <!-- THE ACTUAL PRINTABLE / EXPORTABLE REPORT DOCUMENT -->
                <div id="damrongthamOfficialReport" class="official-report-sheet text-start mx-auto bg-white">
                    
                    <!-- Official Header -->
                    <div class="report-header pb-3 mb-3 border-bottom text-center">
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-2 flex-wrap flex-sm-nowrap">
                            <div class="d-flex align-items-center gap-3">
                                <img src="<?= base_url('uploads/logo/logo_1787048018.png') ?>" alt="ตราประจำจังหวัดพัทลุง" class="report-logo" width="68" height="68" style="object-fit: contain;">
                                <div class="text-start">
                                    <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.25rem; letter-spacing: -0.3px;">ศูนย์ดำรงธรรมจังหวัดพัทลุง</h4>
                                    <div class="text-muted small">ศาลากลางจังหวัดพัทลุง ถ.ราเมศวร์ ต.คูหาสวรรค์ อ.เมืองพัทลุง จ.พัทลุง 93000</div>
                                    <div class="fw-semibold text-primary" style="font-size: 0.82rem;">
                                        <i class="fa-solid fa-bullhorn me-1"></i> ข้อมูลตามประกาศ คสช. ที่ 96/2557 ลงวันที่ 18 กรกฎาคม 2557
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm-end ms-auto">
                                <span class="badge bg-danger rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.78rem;">
                                    รายงานสรุปสถิติทางการ
                                </span>
                                <div class="text-muted small mt-1" style="font-size: 0.75rem;">
                                    ข้อมูลสะสม ณ วันที่: <strong class="text-dark"><?= esc($stats['daily']['date_text'] ?? date('d/m/Y')) ?></strong>
                                </div>
                                <div class="text-muted small" style="font-size: 0.72rem;">
                                    พิมพ์เมื่อ: <?= date('d/m/Y H:i') ?> น.
                                </div>
                            </div>
                        </div>

                        <div class="p-2 rounded-3 text-center" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                            <h5 class="fw-bold mb-0" style="font-size: 1.05rem; letter-spacing: 0.2px;">
                                รายงานผลการดำเนินงานเรื่องร้องเรียน - ร้องทุกข์ และการยุติเรื่องสะสม (ศบส.)
                            </h5>
                        </div>
                    </div>

                    <!-- 4 Core Metric KPI Blocks -->
                    <div class="row g-2 mb-3">
                        <div class="col-3">
                            <div class="p-2.5 rounded-3 text-center border h-100" style="background: #fff1f2; border-color: #fecdd3 !important;">
                                <div class="text-danger fw-bold small text-truncate" style="font-size: 0.75rem;">เรื่องร้องเรียนสะสมรวม</div>
                                <div class="fw-bold text-dark font-monospace" style="font-size: 1.4rem; line-height: 1.2;">
                                    <?= number_format($stats['cumulative']['total_received'] ?? 0) ?>
                                </div>
                                <span class="text-muted" style="font-size: 0.7rem;">เรื่อง</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2.5 rounded-3 text-center border h-100" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                                <div class="text-success fw-bold small text-truncate" style="font-size: 0.75rem;">ยุติแล้วเสร็จ</div>
                                <div class="fw-bold text-success font-monospace" style="font-size: 1.4rem; line-height: 1.2;">
                                    <?= number_format($stats['cumulative']['total_resolved'] ?? 0) ?>
                                </div>
                                <span class="badge bg-success rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">
                                    <?= esc($stats['cumulative']['total_resolved_percent'] ?? 0) ?>%
                                </span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2.5 rounded-3 text-center border h-100" style="background: #fffbeb; border-color: #fde68a !important;">
                                <div class="text-warning-emphasis fw-bold small text-truncate" style="font-size: 0.75rem;">ระหว่างดำเนินการ</div>
                                <div class="fw-bold text-warning-emphasis font-monospace" style="font-size: 1.4rem; line-height: 1.2;">
                                    <?= number_format($stats['cumulative']['total_pending'] ?? 0) ?>
                                </div>
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">
                                    <?= esc($stats['cumulative']['total_pending_percent'] ?? 0) ?>%
                                </span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-2.5 rounded-3 text-center border h-100" style="background: #f0f9ff; border-color: #bae6fd !important;">
                                <div class="text-primary fw-bold small text-truncate" style="font-size: 0.75rem;">บริการให้คำปรึกษา</div>
                                <div class="fw-bold text-primary font-monospace" style="font-size: 1.4rem; line-height: 1.2;">
                                    <?= number_format($stats['cumulative']['total_inquiries'] ?? 0) ?>
                                </div>
                                <span class="text-muted" style="font-size: 0.7rem;">ราย</span>
                            </div>
                        </div>
                    </div>

                    <!-- 2 Comparison Columns: Channels vs Categories -->
                    <div class="row g-3 mb-3">
                        <!-- Column A: 4 Channels Breakdown -->
                        <div class="col-12 col-md-5">
                            <div class="border rounded-3 p-2.5 bg-white h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-arrows-split-up-and-left text-danger me-1"></i> แยกตาม 4 ช่องทางรับเรื่อง
                                    </h6>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">สะสม</span>
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <?php if (!empty($stats['channels'])): ?>
                                        <?php foreach ($stats['channels'] as $ch): ?>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-0.5" style="font-size: 0.78rem;">
                                                <span class="text-dark fw-medium">
                                                    <i class="fa-solid <?= esc($ch['icon'] ?? 'fa-circle') ?> me-1" style="color: <?= esc($ch['color'] ?? '#64748b') ?>; width: 14px;"></i>
                                                    <?= esc($ch['name']) ?>
                                                </span>
                                                <span class="font-monospace fw-bold text-dark">
                                                    <?= number_format($ch['count']) ?> <span class="text-muted fw-normal" style="font-size: 0.7rem;">(<?= esc($ch['percent']) ?>%)</span>
                                                </span>
                                            </div>
                                            <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 4px;">
                                                <div class="progress-bar rounded-pill" role="progressbar" style="width: <?= esc($ch['percent']) ?>%; background-color: <?= esc($ch['color'] ?? '#e11d48') ?>;"></div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Column B: 6 Categories Breakdown -->
                        <div class="col-12 col-md-7">
                            <div class="border rounded-3 p-2.5 bg-white h-100">
                                <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-folder-tree text-primary me-1"></i> แยกตาม 6 กลุ่มลักษณะเรื่องร้องเรียน
                                    </h6>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">สะสม</span>
                                </div>

                                <div class="row g-2">
                                    <?php if (!empty($stats['categories'])): ?>
                                        <?php foreach ($stats['categories'] as $cat): ?>
                                        <div class="col-6">
                                            <div class="p-1.5 rounded-2 border bg-light-subtle h-100">
                                                <div class="d-flex justify-content-between align-items-center" style="font-size: 0.75rem;">
                                                    <span class="text-dark fw-medium text-truncate" title="<?= esc($cat['name']) ?>">
                                                        <?= esc($cat['name']) ?>
                                                    </span>
                                                    <span class="font-monospace fw-bold ms-1" style="color: <?= esc($cat['color'] ?? '#0284c7') ?>;">
                                                        <?= number_format($cat['count']) ?>
                                                    </span>
                                                </div>
                                                <div class="progress mt-1" style="height: 4px; background-color: #e2e8f0; border-radius: 3px;">
                                                    <div class="progress-bar" style="width: <?= esc($cat['percent']) ?>%; background-color: <?= esc($cat['color'] ?? '#0284c7') ?>;"></div>
                                                </div>
                                                <div class="text-end text-muted mt-0.5" style="font-size: 0.65rem;">
                                                    <?= esc($cat['percent']) ?>%
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Highlight Strip -->
                    <div class="p-2.5 rounded-3 mb-3 border" style="background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%); border-color: #cbd5e1 !important;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-dark text-white rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-calendar-day me-1"></i> ล่าสุดประจำวัน: <?= esc($stats['daily']['date_text'] ?? 'วันนี้') ?>
                                </span>
                                <span class="small text-muted" style="font-size: 0.78rem;">
                                    รับเรื่อง: <strong class="text-primary">+<?= (int)($stats['daily']['received'] ?? 0) ?></strong> | 
                                    ยุติแล้ว: <strong class="text-success">+<?= (int)($stats['daily']['resolved'] ?? 0) ?></strong> | 
                                    กำลังทำ: <strong class="text-warning-emphasis"><?= (int)($stats['daily']['pending'] ?? 0) ?></strong> | 
                                    ให้คำปรึกษา: <strong class="text-info">+<?= (int)($stats['daily']['general_inquiries'] ?? 0) ?></strong>
                                </span>
                            </div>
                            <div class="small text-muted" style="font-size: 0.74rem;">
                                ผู้บันทึก: <strong class="text-dark"><?= esc($stats['daily']['officer_name'] ?? 'เจ้าหน้าที่ศูนย์ดำรงธรรม') ?></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Official Verification & Sign-off Block -->
                    <div class="pt-2 border-top">
                        <div class="row align-items-end g-2">
                            <div class="col-7">
                                <div class="text-muted small" style="font-size: 0.72rem; line-height: 1.4;">
                                    <div><strong>หน่วยงานรับผิดชอบ:</strong> กลุ่มงานศูนย์ดำรงธรรมจังหวัดพัทลุง ศาลากลางจังหวัดพัทลุง</div>
                                    <div><strong>ช่องทางติดต่อ:</strong> สายด่วน 1567 (24 ชม.) | โทรศัพท์ 074-614130 | https://www.phatthalung.go.th</div>
                                    <div class="text-success"><i class="fa-solid fa-circle-check me-1"></i> ข้อมูลเชื่อมต่อระบบฐานข้อมูลกลางอัตโนมัติ (Automated Real-time Data)</div>
                                </div>
                            </div>
                            <div class="col-5 text-center">
                                <div class="signature-box p-1" style="font-size: 0.75rem;">
                                    <div class="text-muted mb-4">(ลงชื่อ)..........................................................................</div>
                                    <div class="fw-bold text-dark">( <?= esc($stats['daily']['officer_name'] ?? 'เจ้าหน้าที่ศูนย์ดำรงธรรม') ?> )</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">เจ้าหน้าที่ศูนย์ดำรงธรรมจังหวัดพัทลุง / ผู้รายงาน</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /THE ACTUAL PRINTABLE / EXPORTABLE REPORT DOCUMENT -->

            </div>
        </div>
    </div>

    <!-- 1. DAILY RECORD ENTRY FORM CARD -->
    <div id="dailyFormCard" class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary text-white rounded-circle p-2" style="width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold text-dark mb-0" id="formTitle">แบบฟอร์มบันทึกข้อมูลสถิติประจำวัน (Daily Record Entry)</h5>
                        <small class="text-muted">กรอกข้อมูลประจำวัน หากบันทึกวันที่ซ้ำ ระบบจะทำการอัปเดตข้อมูลของวันนั้นให้โดยอัตโนมัติ</small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="resetDailyForm()">
                    <i class="fa-solid fa-rotate-left me-1"></i> ล้างฟอร์ม
                </button>
            </div>
        </div>
        <div class="card-body px-4 pb-4">
            <form id="dailyForm" action="<?= base_url('admin/damrongtham/daily/save') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold">วันที่ของข้อมูล (ค.ศ.) <span class="text-danger">*</span></label>
                        <input type="date" id="record_date" name="record_date" class="form-control rounded-3" value="<?= date('Y-m-d') ?>" required onchange="updateThaiDateText(this.value)">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold">ข้อความวันที่ภาษาไทย (แสดงผลบนเว็บ)</label>
                        <input type="text" id="date_text" name="date_text" class="form-control rounded-3" value="<?= \App\Models\DamrongthamDailyModel::formatThaiDate(date('Y-m-d')) ?>" placeholder="เช่น 2 ตุลาคม 2569" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-bold">เจ้าหน้าที่ผู้บันทึก</label>
                        <input type="text" id="officer_name" name="officer_name" class="form-control rounded-3" value="<?= esc(session()->get('full_name') ?? 'เจ้าหน้าที่ศูนย์ดำรงธรรม') ?>">
                    </div>
                </div>

                <!-- Core 4 Daily Figures -->
                <div class="p-3 rounded-4 mb-4" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-list-check text-primary me-2"></i>ตัวเลขสรุปประจำวัน (หลัก):</h6>
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold">1. รับเรื่องประจำวัน (เรื่อง) <span class="text-danger">*</span></label>
                            <input type="number" id="received_count" name="received_count" class="form-control rounded-3 text-center fw-bold fs-5 text-primary" value="0" min="0" required oninput="calcDailyPending()">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold text-success">2. ดำเนินการแล้วเสร็จ (ยุติ)</label>
                            <input type="number" id="resolved_count" name="resolved_count" class="form-control rounded-3 text-center fw-bold fs-5 text-success" value="0" min="0" oninput="calcDailyPending()">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold text-warning">3. ระหว่างดำเนินการ (เรื่อง)</label>
                            <input type="number" id="pending_count" name="pending_count" class="form-control rounded-3 text-center fw-bold fs-5 text-warning" value="0" min="0">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold text-info">4. สอบถามทั่วไป/ปรึกษา (ราย)</label>
                            <input type="number" id="general_inquiries" name="general_inquiries" class="form-control rounded-3 text-center fw-bold fs-5 text-info" value="0" min="0">
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <!-- 4 Channels Breakdown -->
                    <div class="col-12 col-lg-6">
                        <div class="p-3.5 rounded-4 border bg-white h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-arrows-split-up-and-left text-danger me-2"></i>แยกตาม 4 ช่องทางรับเรื่อง (ประจำวัน)</h6>
                                <span class="badge bg-light text-muted border small">ช่องทาง</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="small text-muted mb-1"><i class="fa-solid fa-person-walking me-1"></i> มาด้วยตนเอง</label>
                                    <input type="number" id="channel_walkin" name="channel_walkin" class="form-control form-control-sm rounded-3 text-center fw-bold" value="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1"><i class="fa-solid fa-phone-volume me-1"></i> โทรศัพท์ (สายด่วน 1567)</label>
                                    <input type="number" id="channel_phone" name="channel_phone" class="form-control form-control-sm rounded-3 text-center fw-bold" value="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1"><i class="fa-solid fa-globe me-1"></i> เว็บไซต์ / E-Service</label>
                                    <input type="number" id="channel_website" name="channel_website" class="form-control form-control-sm rounded-3 text-center fw-bold" value="0" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="small text-muted mb-1"><i class="fa-solid fa-envelope-open-text me-1"></i> จดหมาย / หนังสือ</label>
                                    <input type="number" id="channel_letter" name="channel_letter" class="form-control form-control-sm rounded-3 text-center fw-bold" value="0" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6 Categories Breakdown -->
                    <div class="col-12 col-lg-6">
                        <div class="p-3.5 rounded-4 border bg-white h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-folder-tree text-warning-emphasis me-2"></i>แยกตาม 6 กลุ่มเรื่อง (ประจำวัน)</h6>
                                <span class="badge bg-light text-muted border small">กลุ่มเรื่อง</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-6 col-sm-4">
                                    <label class="small text-muted mb-1 text-truncate d-block">เรื่องทั่วไป/ปรึกษา</label>
                                    <input type="number" id="cat_general" name="cat_general" class="form-control form-control-sm rounded-3 text-center fw-bold text-primary" value="0" min="0">
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="small text-muted mb-1 text-truncate d-block">ขอความช่วยเหลือ</label>
                                    <input type="number" id="cat_welfare" name="cat_welfare" class="form-control form-control-sm rounded-3 text-center fw-bold text-info" value="0" min="0">
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="small text-muted mb-1 text-truncate d-block">เดือดร้อนรำคาญ</label>
                                    <input type="number" id="cat_distress" name="cat_distress" class="form-control form-control-sm rounded-3 text-center fw-bold text-warning" value="0" min="0">
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="small text-muted mb-1 text-truncate d-block">กล่าวโทษเจ้าหน้าที่</label>
                                    <input type="number" id="cat_officer" name="cat_officer" class="form-control form-control-sm rounded-3 text-center fw-bold text-danger" value="0" min="0">
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="small text-muted mb-1 text-truncate d-block">แจ้งเบาะแส</label>
                                    <input type="number" id="cat_whistleblowing" name="cat_whistleblowing" class="form-control form-control-sm rounded-3 text-center fw-bold text-success" value="0" min="0">
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="small text-muted mb-1 text-truncate d-block">ปัญหาที่ดิน</label>
                                    <input type="number" id="cat_land" name="cat_land" class="form-control form-control-sm rounded-3 text-center fw-bold text-secondary" value="0" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">หมายเหตุ / รายละเอียดเพิ่มเติม (ถ้ามี)</label>
                    <textarea id="notes" name="notes" class="form-control rounded-3" rows="2" placeholder="ระบุข้อมูลเพิ่มเติมสำหรับการปฏิบัติงาน..."></textarea>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="text-muted small">
                        <i class="fa-solid fa-circle-info text-primary me-1"></i> เมื่อกดบันทึก ระบบจะนำตัวเลขของวันนี้ไปบวกคำนวณสะสม และอัปเดตหน้าเว็บศูนย์ดำรงธรรมทันที
                    </span>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกข้อมูลประจำวันเข้าฐานข้อมูล
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- 2. DAILY RECORDS HISTORY TABLE -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success text-white rounded-circle p-2" style="width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-table-list"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">ประวัติการบันทึกสถิติรายวันในฐานข้อมูล (MySQL Daily Records)</h5>
                        <small class="text-muted">รายการสถิติที่บันทึกแล้วทั้งหมด <?= count($dailyRecords ?? []) ?> วัน</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body px-4 pb-4">
            <?php if (!empty($dailyRecords)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th style="width: 130px;">วันที่บันทึก</th>
                            <th class="text-center" style="width: 100px;">รับเรื่อง</th>
                            <th class="text-center" style="width: 100px;">ยุติแล้ว</th>
                            <th class="text-center" style="width: 100px;">กำลังทำ</th>
                            <th class="text-center" style="width: 100px;">สอบถาม (ราย)</th>
                            <th>ช่องทางหลัก</th>
                            <th>กลุ่มเรื่อง</th>
                            <th>ผู้บันทึก</th>
                            <th class="text-end" style="width: 130px;">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dailyRecords as $rec): ?>
                        <tr>
                            <td>
                                <strong class="text-dark d-block"><?= esc($rec['date_text']) ?></strong>
                                <small class="text-muted font-monospace"><?= esc($rec['record_date']) ?></small>
                            </td>
                            <td class="text-center font-monospace fw-bold text-primary fs-6">
                                <?= (int)$rec['received_count'] ?>
                            </td>
                            <td class="text-center font-monospace fw-bold text-success fs-6">
                                <?= (int)$rec['resolved_count'] ?>
                            </td>
                            <td class="text-center font-monospace fw-bold text-warning fs-6">
                                <?= (int)$rec['pending_count'] ?>
                            </td>
                            <td class="text-center font-monospace fw-bold text-info fs-6">
                                <?= (int)$rec['general_inquiries'] ?>
                            </td>
                            <td class="small text-muted">
                                <div><i class="fa-solid fa-person-walking me-1"></i><?= (int)$rec['channel_walkin'] ?> | <i class="fa-solid fa-globe me-1"></i><?= (int)$rec['channel_website'] ?></div>
                                <div><i class="fa-solid fa-phone me-1"></i><?= (int)$rec['channel_phone'] ?> | <i class="fa-solid fa-envelope me-1"></i><?= (int)$rec['channel_letter'] ?></div>
                            </td>
                            <td class="small text-muted">
                                <div>ทั่วไป: <?= (int)$rec['cat_general'] ?> | ช่วยเหลือ: <?= (int)$rec['cat_welfare'] ?></div>
                                <div>เดือดร้อน: <?= (int)$rec['cat_distress'] ?> | จนท: <?= (int)$rec['cat_officer'] ?></div>
                            </td>
                            <td class="small text-muted">
                                <?= esc($rec['officer_name'] ?? '-') ?>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1" onclick='editDailyRecord(<?= json_encode($rec) ?>)' title="แก้ไข">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="<?= base_url('admin/damrongtham/daily/delete-record') ?>" method="post" onsubmit="return confirm('ยืนยันลบรายการสถิติประจำวันที่ <?= esc($rec['date_text']) ?>? ยอดสะสมจะถูกคำนวณใหม่โดยอัตโนมัติ');" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= esc($rec['id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" title="ลบ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5 bg-light rounded-4">
                <i class="fa-solid fa-inbox text-muted fa-3x mb-2"></i>
                <h6 class="fw-bold text-dark">ยังไม่มีรายการบันทึกรายวันในฐานข้อมูล</h6>
                <p class="text-muted small mb-0">ท่านสามารถใช้แบบฟอร์มด้านบนเพื่อเริ่มบันทึกข้อมูลประจำวันได้ทันที</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- BASELINE SETTINGS MODAL -->
<div class="modal fade" id="baselineModal" tabindex="-1" aria-labelledby="baselineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="baselineModalLabel">
                        <i class="fa-solid fa-sliders text-primary me-2"></i>ตั้งค่ายอดสะสมตั้งต้น (Historical Baseline)
                    </h5>
                    <small class="text-muted">กำหนดตัวเลขสะสมทางประวัติศาสตร์ก่อนเริ่มระบบบันทึกรายวัน (เช่น ข้อมูล ณ 18 ก.ค. 2557 - 1 ต.ค. 2569)</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="baselineForm" action="<?= base_url('admin/damrongtham/baseline/save') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">ข้อความอ้างอิงประกาศจัดตั้งศูนย์</label>
                        <input type="text" name="announcement_title" class="form-control rounded-3" value="<?= esc($baseline['announcement_title'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">ช่วงเวลาสะสมที่ระบุในประกาศ</label>
                        <input type="text" name="period_text" class="form-control rounded-3" value="<?= esc($baseline['period_text'] ?? '') ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-bold">ยอดรับเรื่องตั้งต้น</label>
                            <input type="number" name="base_received" class="form-control rounded-3 text-center fw-bold text-primary" value="<?= (int)($baseline['base_received'] ?? 0) ?>" min="0" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-success">ยุติแล้วเสร็จตั้งต้น</label>
                            <input type="number" name="base_resolved" class="form-control rounded-3 text-center fw-bold text-success" value="<?= (int)($baseline['base_resolved'] ?? 0) ?>" min="0" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold text-warning">ระหว่างดำเนินการตั้งต้น</label>
                            <input type="number" name="base_pending" class="form-control rounded-3 text-center fw-bold text-warning" value="<?= (int)($baseline['base_pending'] ?? 0) ?>" min="0" required>
                        </div>
                    </div>

                    <label class="form-label small fw-bold text-muted mb-2">ยอดตั้งต้นแยกตาม 4 ช่องทาง:</label>
                    <div class="row g-2 mb-3">
                        <div class="col-3">
                            <span class="small text-muted d-block">ด้วยตนเอง</span>
                            <input type="number" name="base_ch_walkin" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_ch_walkin'] ?? 0) ?>">
                        </div>
                        <div class="col-3">
                            <span class="small text-muted d-block">โทรศัพท์ 1567</span>
                            <input type="number" name="base_ch_phone" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_ch_phone'] ?? 0) ?>">
                        </div>
                        <div class="col-3">
                            <span class="small text-muted d-block">เว็บไซต์</span>
                            <input type="number" name="base_ch_website" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_ch_website'] ?? 0) ?>">
                        </div>
                        <div class="col-3">
                            <span class="small text-muted d-block">จดหมาย</span>
                            <input type="number" name="base_ch_letter" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_ch_letter'] ?? 0) ?>">
                        </div>
                    </div>

                    <label class="form-label small fw-bold text-muted mb-2">ยอดตั้งต้นแยกตาม 6 กลุ่มเรื่อง:</label>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <span class="small text-muted d-block text-truncate">เรื่องทั่วไป/อื่น ๆ</span>
                            <input type="number" name="base_cat_general" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_cat_general'] ?? 0) ?>">
                        </div>
                        <div class="col-4">
                            <span class="small text-muted d-block text-truncate">ขอความช่วยเหลือ</span>
                            <input type="number" name="base_cat_welfare" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_cat_welfare'] ?? 0) ?>">
                        </div>
                        <div class="col-4">
                            <span class="small text-muted d-block text-truncate">เดือดร้อนรำคาญ</span>
                            <input type="number" name="base_cat_distress" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_cat_distress'] ?? 0) ?>">
                        </div>
                        <div class="col-4">
                            <span class="small text-muted d-block text-truncate">กล่าวโทษเจ้าหน้าที่</span>
                            <input type="number" name="base_cat_officer" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_cat_officer'] ?? 0) ?>">
                        </div>
                        <div class="col-4">
                            <span class="small text-muted d-block text-truncate">แจ้งเบาะแส</span>
                            <input type="number" name="base_cat_whistleblowing" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_cat_whistleblowing'] ?? 0) ?>">
                        </div>
                        <div class="col-4">
                            <span class="small text-muted d-block text-truncate">ปัญหาที่ดิน</span>
                            <input type="number" name="base_cat_land" class="form-control form-control-sm text-center" value="<?= (int)($baseline['base_cat_land'] ?? 0) ?>">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <button type="button" class="btn btn-outline-danger rounded-pill px-3" onclick="resetBaselineFormInputsToZero()">
                            <i class="fa-solid fa-eraser me-1"></i> กรอกเป็น 0 ทั้งหมด
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">บันทึกค่ายอดตั้งต้น</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- LINE SHARE MODAL -->
<div class="modal fade" id="lineShareModal" tabindex="-1" aria-labelledby="lineShareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white rounded-circle p-2" style="width:38px;height:38px;background:#06C755;display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fa-brands fa-line fs-5"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="lineShareModalLabel">ส่งรายงานสถิติไปยัง LINE</h5>
                        <small class="text-muted">คัดลอกรูปภาพและข้อความสรุปพร้อมส่งต่อในกลุ่ม LINE ได้ทันที</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="clipboardNotice" class="alert alert-success d-flex align-items-center gap-2 rounded-3 py-2 px-3 mb-3" style="display:none !important;">
                    <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                    <div class="small">
                        <strong>คัดลอกรูปภาพลงคลิปบอร์ดแล้ว!</strong> ท่านสามารถเปิดหน้าต่างแชท LINE แล้วกด <kbd>Ctrl + V</kbd> (วาง) เพื่อส่งรูปภาพได้ทันที
                    </div>
                </div>

                <div class="row g-3 align-items-center">
                    <div class="col-12 col-md-6 text-center">
                        <div class="p-2 bg-light border rounded-3 shadow-xs">
                            <img id="lineSharePreviewImg" src="" alt="ตัวอย่างรูปภาพสถิติ" class="img-fluid rounded" style="max-height: 380px; object-fit: contain;">
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">ภาพความละเอียดสูง (2x High Definition)</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-message text-success me-1"></i> ข้อความสรุปสำหรับส่งใน LINE:</h6>
                            <textarea id="lineShareText" class="form-control form-control-sm text-secondary bg-white" rows="6" readonly style="font-size: 0.82rem; line-height: 1.5;"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn text-white fw-bold py-2.5 rounded-pill shadow-sm" style="background-color: #06C755; border-color: #06C755;" onclick="openLineShareAction()">
                                <i class="fa-brands fa-line me-1.5 fs-5 align-middle"></i> เปิด LINE ส่งข้อความสรุป & ลิงก์
                            </button>
                            <button type="button" class="btn btn-outline-dark fw-semibold py-2 rounded-pill" onclick="copyReportImageToClipboard()">
                                <i class="fa-solid fa-copy me-1.5 text-primary"></i> คัดลอกรูปภาพอีกครั้ง (Ctrl + V)
                            </button>
                            <a id="lineDownloadBtn" href="#" download="Damrongtham_Stats_Phatthalung.png" class="btn btn-outline-secondary btn-sm rounded-pill py-1.5">
                                <i class="fa-solid fa-download me-1"></i> ดาวน์โหลดไฟล์รูปภาพ (PNG)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top bg-light-subtle px-4 py-2.5">
                <small class="text-muted me-auto"><i class="fa-solid fa-lightbulb text-warning me-1"></i> บนมือถือ/แท็บเล็ต ระบบจะเปิดแอป LINE ให้เลือกห้องแชทอัตโนมัติ</small>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<style>
/* OFFICIAL REPORT SHEET STYLES */
.official-report-sheet {
    max-width: 820px;
    width: 100%;
    background-color: #ffffff;
    padding: 26px 30px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    font-family: 'Prompt', 'Kanit', 'Sarabun', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: #1e293b;
}

@media print {
    /* Hide everything except the report card and its content */
    html, body {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
        color: #000000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Hide admin navigation, sidebar, topbar, buttons, modals, and other cards */
    .admin-sidebar,
    .admin-header,
    .admin-topbar,
    .admin-footer,
    nav,
    aside,
    header,
    footer,
    .btn,
    .modal,
    .modal-backdrop,
    .alert,
    #dailyFormCard,
    .card:not(#damrongthamOfficialReportCard),
    .d-print-none,
    .no-print,
    #damrongthamOfficialReportCard .card-header {
        display: none !important;
    }

    #damrongthamOfficialReportCard {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    #damrongthamOfficialReportCard .card-body {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .table-responsive {
        overflow: visible !important;
        padding: 0 !important;
    }

    .official-report-sheet {
        max-width: 100% !important;
        width: 100% !important;
        border: none !important;
        box-shadow: none !important;
        padding: 4mm 6mm !important;
        margin: 0 !important;
        border-radius: 0 !important;
        page-break-inside: avoid !important;
    }

    @page {
        size: A4 portrait;
        margin: 8mm 10mm 8mm 10mm;
    }
}
</style>

<!-- Load Local HTML2Canvas -->
<script src="<?= base_url('assets/js/html2canvas.min.js') ?>"></script>

<script>
const thaiMonths = [
    '', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
    'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
    'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
];

function updateThaiDateText(dateVal) {
    if (!dateVal) return;
    const parts = dateVal.split('-');
    if (parts.length === 3) {
        const y = parseInt(parts[0], 10) + 543;
        const m = parseInt(parts[1], 10);
        const d = parseInt(parts[2], 10);
        document.getElementById('date_text').value = d + ' ' + (thaiMonths[m] || '') + ' ' + y;
    }
}

function calcDailyPending() {
    const rec = parseInt(document.getElementById('received_count').value) || 0;
    const res = parseInt(document.getElementById('resolved_count').value) || 0;
    const pen = Math.max(0, rec - res);
    document.getElementById('pending_count').value = pen;
}

function editDailyRecord(data) {
    document.getElementById('formTitle').innerText = 'แก้ไขข้อมูลสถิติประจำวันที่ ' + (data.date_text || data.record_date);
    document.getElementById('record_date').value = data.record_date || '';
    document.getElementById('date_text').value = data.date_text || '';
    document.getElementById('received_count').value = data.received_count || 0;
    document.getElementById('resolved_count').value = data.resolved_count || 0;
    document.getElementById('pending_count').value = data.pending_count || 0;
    document.getElementById('general_inquiries').value = data.general_inquiries || 0;
    
    // Channels
    document.getElementById('channel_walkin').value = data.channel_walkin || 0;
    document.getElementById('channel_phone').value = data.channel_phone || 0;
    document.getElementById('channel_website').value = data.channel_website || 0;
    document.getElementById('channel_letter').value = data.channel_letter || 0;

    // Categories
    document.getElementById('cat_general').value = data.cat_general || 0;
    document.getElementById('cat_welfare').value = data.cat_welfare || 0;
    document.getElementById('cat_distress').value = data.cat_distress || 0;
    document.getElementById('cat_officer').value = data.cat_officer || 0;
    document.getElementById('cat_whistleblowing').value = data.cat_whistleblowing || 0;
    document.getElementById('cat_land').value = data.cat_land || 0;

    document.getElementById('officer_name').value = data.officer_name || '';
    document.getElementById('notes').value = data.notes || '';

    // Scroll to form smoothly
    document.getElementById('dailyFormCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function resetDailyForm() {
    document.getElementById('dailyForm').reset();
    document.getElementById('formTitle').innerText = 'แบบฟอร์มบันทึกข้อมูลสถิติประจำวัน (Daily Record Entry)';
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('record_date').value = today;
    updateThaiDateText(today);
}

/* ========================================================
   PRINT, IMAGE EXPORT, AND LINE SHARING ENGINE
======================================================== */
let generatedReportBlob = null;
let generatedReportDataUrl = null;

// 1. PRINT REPORT AS PDF
function printDamrongthamReport() {
    window.print();
}

// Helper to generate Canvas
async function generateReportCanvas() {
    const reportElem = document.getElementById('damrongthamOfficialReport');
    if (!reportElem) return null;

    if (typeof html2canvas === 'undefined') {
        alert('กำลังโหลดไลบรารีสำหรับสร้างภาพ กรุณาลองใหม่อีกครั้ง');
        return null;
    }

    return await html2canvas(reportElem, {
        scale: 2, // High resolution (2x DPI)
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        logging: false,
        scrollX: 0,
        scrollY: 0,
        windowWidth: reportElem.scrollWidth
    });
}

// 2. EXPORT AS IMAGE (PNG)
async function exportDamrongthamImage() {
    const activeBtn = event ? (event.currentTarget || event.target) : null;
    let originalText = '';
    if (activeBtn) {
        originalText = activeBtn.innerHTML;
        activeBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังสร้างรูป...';
        activeBtn.disabled = true;
    }

    try {
        const canvas = await generateReportCanvas();
        if (!canvas) {
            if (activeBtn) { activeBtn.innerHTML = originalText; activeBtn.disabled = false; }
            return;
        }

        const dateStr = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        const filename = 'รายงานสถิติศูนย์ดำรงธรรม_พัทลุง_' + dateStr + '.png';

        const link = document.createElement('a');
        link.download = filename;
        link.href = canvas.toDataURL('image/png');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        if (activeBtn) {
            activeBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> ดาวน์โหลดแล้ว!';
            setTimeout(() => {
                activeBtn.innerHTML = originalText;
                activeBtn.disabled = false;
            }, 2000);
        }
    } catch (err) {
        console.error('Export image error:', err);
        alert('เกิดข้อผิดพลาดในการสร้างรูปภาพ: ' + err.message);
        if (activeBtn) { activeBtn.innerHTML = originalText; activeBtn.disabled = false; }
    }
}

// 3. SHARE TO LINE
async function shareDamrongthamToLine() {
    const activeBtn = event ? (event.currentTarget || event.target) : null;
    let originalText = '';
    if (activeBtn) {
        originalText = activeBtn.innerHTML;
        activeBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังเตรียมภาพ...';
        activeBtn.disabled = true;
    }

    try {
        const canvas = await generateReportCanvas();
        if (!canvas) {
            if (activeBtn) { activeBtn.innerHTML = originalText; activeBtn.disabled = false; }
            return;
        }

        generatedReportDataUrl = canvas.toDataURL('image/png');

        canvas.toBlob(async function(blob) {
            generatedReportBlob = blob;
            const dateStr = new Date().toISOString().slice(0, 10).replace(/-/g, '');
            const filename = 'รายงานสถิติศูนย์ดำรงธรรม_พัทลุง_' + dateStr + '.png';
            const imageFile = new File([blob], filename, { type: 'image/png' });

            const shareTitle = 'สรุปสถิติศูนย์ดำรงธรรมจังหวัดพัทลุง';
            const shareText = "📊 สรุปผลการดำเนินงานศูนย์ดำรงธรรมจังหวัดพัทลุง\n" +
                              "• เรื่องร้องเรียนสะสมรวม: <?= number_format($stats['cumulative']['total_received'] ?? 0) ?> เรื่อง\n" +
                              "• ดำเนินการแล้วเสร็จ (ยุติ): <?= number_format($stats['cumulative']['total_resolved'] ?? 0) ?> เรื่อง (<?= esc($stats['cumulative']['total_resolved_percent'] ?? 0) ?>%)\n" +
                              "• อยู่ระหว่างดำเนินการ: <?= number_format($stats['cumulative']['total_pending'] ?? 0) ?> เรื่อง (<?= esc($stats['cumulative']['total_pending_percent'] ?? 0) ?>%)\n" +
                              "• บริการให้คำปรึกษา: <?= number_format($stats['cumulative']['total_inquiries'] ?? 0) ?> ราย\n" +
                              "📅 ข้อมูลสะสม ณ วันที่: <?= esc($stats['daily']['date_text'] ?? date('d/m/Y')) ?>\n" +
                              "🔗 เว็บไซต์ทางการ: <?= base_url('damrongtham') ?>";

            // If Mobile Web Share with files is supported (e.g., Mobile Chrome / Safari)
            if (navigator.canShare && navigator.canShare({ files: [imageFile] })) {
                try {
                    await navigator.share({
                        title: shareTitle,
                        text: shareText,
                        files: [imageFile]
                    });
                    if (activeBtn) { activeBtn.innerHTML = originalText; activeBtn.disabled = false; }
                    return;
                } catch (shareErr) {
                    if (shareErr.name === 'AbortError') {
                        // User cancelled
                        if (activeBtn) { activeBtn.innerHTML = originalText; activeBtn.disabled = false; }
                        return;
                    }
                    console.log('Native share failed, fallback to modal:', shareErr);
                }
            }

            // Desktop / Web Fallback:
            // 1. Copy image to Clipboard
            let copied = false;
            if (navigator.clipboard && window.ClipboardItem) {
                try {
                    const item = new ClipboardItem({ 'image/png': blob });
                    await navigator.clipboard.write([item]);
                    copied = true;
                } catch (clipErr) {
                    console.warn('Clipboard write image failed:', clipErr);
                }
            }

            // 2. Prepare Modal Preview & Download
            const previewImg = document.getElementById('lineSharePreviewImg');
            if (previewImg) previewImg.src = generatedReportDataUrl;

            const textElem = document.getElementById('lineShareText');
            if (textElem) textElem.value = shareText;

            const dlBtn = document.getElementById('lineDownloadBtn');
            if (dlBtn) {
                dlBtn.href = generatedReportDataUrl;
                dlBtn.download = filename;
            }

            const noticeElem = document.getElementById('clipboardNotice');
            if (noticeElem) {
                noticeElem.style.setProperty('display', copied ? 'flex' : 'none', 'important');
            }

            // Show LINE Share Modal
            const modalElem = document.getElementById('lineShareModal');
            if (modalElem && window.bootstrap) {
                const modal = new bootstrap.Modal(modalElem);
                modal.show();
            }

            if (activeBtn) {
                activeBtn.innerHTML = originalText;
                activeBtn.disabled = false;
            }
        }, 'image/png');

    } catch (err) {
        console.error('Share to LINE error:', err);
        alert('เกิดข้อผิดพลาดในการแชร์ไปยัง LINE: ' + err.message);
        if (activeBtn) { activeBtn.innerHTML = originalText; activeBtn.disabled = false; }
    }
}

// Copy image to clipboard again
async function copyReportImageToClipboard() {
    if (generatedReportBlob && navigator.clipboard && window.ClipboardItem) {
        try {
            const item = new ClipboardItem({ 'image/png': generatedReportBlob });
            await navigator.clipboard.write([item]);
            const noticeElem = document.getElementById('clipboardNotice');
            if (noticeElem) noticeElem.style.setProperty('display', 'flex', 'important');
            alert('คัดลอกรูปภาพลงคลิปบอร์ดแล้ว! กด Ctrl + V ในห้องแชท LINE ได้ทันที');
        } catch (e) {
            alert('เบราว์เซอร์ไม่อนุญาตให้เขียนภาพลงคลิปบอร์ดอัตโนมัติ กรุณากดดาวน์โหลดไฟล์รูปภาพแล้วส่งใน LINE');
        }
    } else {
        alert('กรุณากดดาวน์โหลดไฟล์รูปภาพแล้วส่งใน LINE');
    }
}

// Open LINE Web / App Share intent
function openLineShareAction() {
    const textElem = document.getElementById('lineShareText');
    const msg = textElem ? textElem.value : 'สรุปสถิติศูนย์ดำรงธรรมจังหวัดพัทลุง';
    const lineUrl = 'https://line.me/R/msg/text/?' + encodeURIComponent(msg);
    window.open(lineUrl, '_blank');
}

// Reset all baseline inputs to 0 in modal
function resetBaselineFormInputsToZero() {
    const form = document.getElementById('baselineForm');
    if (!form) return;
    const inputs = form.querySelectorAll('input[type="number"]');
    inputs.forEach(input => input.value = 0);
}
</script>
<?= $this->endSection() ?>

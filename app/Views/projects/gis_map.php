<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
/* GIS Hub Custom Styles */
:root {
    --gis-navy: #0b1e48;
    --gis-blue: #1e40af;
    --gis-gold: #f59e0b;
}

.gis-hero-card {
    background: linear-gradient(135deg, #071530 0%, #0f2b6b 50%, #0369a1 100%);
    border-radius: 24px;
    position: relative;
    overflow: hidden;
    color: #ffffff;
    box-shadow: 0 15px 35px rgba(11, 30, 72, 0.15);
}

.gis-map-container {
    height: 650px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 2px solid #ffffff;
    position: relative;
    z-index: 1;
}

.project-sidebar-card {
    max-height: 650px;
    overflow-y: auto;
    border-radius: 20px;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
}

.project-item-card {
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 14px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.25s ease;
    cursor: pointer;
    background: #ffffff;
}

.project-item-card:hover {
    transform: translateY(-3px);
    border-color: #38bdf8;
    box-shadow: 0 8px 20px rgba(2, 132, 199, 0.12);
}

.project-item-card.active {
    border-color: #2563eb;
    background: rgba(37, 99, 235, 0.04);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.15);
}

.status-badge-completed { background: #10b981; color: #ffffff; }
.status-badge-in_progress { background: #2563eb; color: #ffffff; }
.status-badge-pending { background: #f59e0b; color: #ffffff; }
.status-badge-delayed { background: #ef4444; color: #ffffff; }

/* Custom Marker Pins */
.custom-gis-pin {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #ffffff;
    font-weight: 800;
    font-size: 13px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    border: 2.5px solid #ffffff;
    transition: transform 0.2s ease;
}
.custom-gis-pin:hover {
    transform: scale(1.2);
    z-index: 1000 !important;
}

.gallery-thumb {
    width: 90px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: opacity 0.2s ease;
}
.gallery-thumb:hover {
    opacity: 0.8;
}
</style>

<div class="container py-4">

    <!-- ======================================================== -->
    <!-- 1. HERO HEADER & SYSTEM NAVIGATION -->
    <!-- ======================================================== -->
    <div class="gis-hero-card p-4 p-lg-4.5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                        <i class="fa-solid fa-map-location-dot me-1"></i> ระบบ GIS โครงการพัฒนาจังหวัด
                    </span>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 small">
                        <i class="fa-solid fa-circle-nodes me-1"></i> เชื่อมโยง eMENSCR สภาพัฒน์
                    </span>
                </div>
                <h2 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">
                    แผนที่เชิงพื้นที่โครงการพัฒนาจังหวัดพัทลุง
                </h2>
                <p class="text-white text-opacity-80 mb-4 fs-6 fw-light">
                    ระบบภูมิสารสนเทศติดตามผลการดำเนินงานโครงการตามแผนปฏิบัติราชการประจำปี ตรวจสอบย้อนหลังรายปี และระบุพิกัด 11 อำเภอ
                </p>

                <!-- Navigation Switcher Buttons -->
                <div class="d-flex flex-wrap gap-2.5">
                    <a href="<?= base_url('projects/gis') ?>" class="btn btn-warning rounded-pill px-4 py-2.5 fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-map"></i> แผนที่โครงการ GIS
                    </a>
                    <a href="<?= base_url('projects/dashboard') ?>" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-chart-pie"></i> Executive Dashboard
                    </a>
                    <a href="<?= base_url('strategy') ?>" class="btn btn-outline-info rounded-pill px-3.5 py-2.5 fw-bold d-inline-flex align-items-center gap-2 text-white">
                        <i class="fa-solid fa-bullseye"></i> ยุทธศาสตร์จังหวัด
                    </a>
                    <?php if (session()->get('isLoggedIn')): ?>
                        <a href="<?= base_url('admin/projects') ?>" class="btn btn-light rounded-pill px-3.5 py-2.5 fw-bold text-dark d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-sliders text-primary"></i> จัดการข้อมูลโครงการ & eMENSCR
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4 text-center d-none d-lg-block">
                <div class="p-3.5 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-20 text-start shadow-sm" style="backdrop-filter: blur(10px);">
                    <div class="small text-warning fw-bold mb-2">
                        <i class="fa-solid fa-chart-simple me-1"></i> สรุปภาพรวมปีงบประมาณ <?= esc($filters['year'] ?: 'ล่าสุด') ?>
                    </div>
                    <div class="row g-2 text-white">
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-white bg-opacity-10">
                                <div class="text-white text-opacity-75 small">งบประมาณรวม</div>
                                <div class="fw-bold fs-6 text-warning"><?= number_format($summary['total_budget'] / 1000000, 1) ?> ลบ.</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-white bg-opacity-10">
                                <div class="text-white text-opacity-75 small">เบิกจ่ายแล้ว</div>
                                <div class="fw-bold fs-6 text-success"><?= $summary['disbursed_pct'] ?>%</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-white bg-opacity-10">
                                <div class="text-white text-opacity-75 small">จำนวนโครงการ</div>
                                <div class="fw-bold fs-6"><?= $summary['total_projects'] ?> โครงการ</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-white bg-opacity-10">
                                <div class="text-white text-opacity-75 small">แล้วเสร็จ</div>
                                <div class="fw-bold fs-6 text-info"><?= $summary['completed_pct'] ?>%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 2. MULTI-FILTER BAR (ตัวกรองอัจฉริยะย้อนหลังรายปี) -->
    <!-- ======================================================== -->
    <div class="card border-0 rounded-4 shadow-sm bg-white p-3.5 mb-4">
        <form method="GET" action="<?= base_url('projects/gis') ?>" id="gisFilterForm">
            <div class="row g-2.5 align-items-center">
                <!-- 1. Fiscal Year (ปีงบประมาณ ย้อนหลัง) -->
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-regular fa-calendar-check text-primary me-1"></i>ปีงบประมาณ</label>
                    <select class="form-select rounded-pill form-select-sm py-2 shadow-none fw-bold" name="year" onchange="document.getElementById('gisFilterForm').submit()">
                        <option value="">ทุกปีงบประมาณ</option>
                        <?php foreach ($yearsList as $yr): ?>
                            <option value="<?= $yr ?>" <?= ((string)($filters['year'] ?? '') === (string)$yr) ? 'selected' : '' ?>>
                                ปี <?= $yr ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 2. District (11 อำเภอ) -->
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-location-dot text-danger me-1"></i>อำเภอ</label>
                    <select class="form-select rounded-pill form-select-sm py-2 shadow-none" name="district" onchange="document.getElementById('gisFilterForm').submit()">
                        <option value="">ทุกอำเภอ (11 อำเภอ)</option>
                        <?php foreach ($districts as $d): ?>
                            <option value="<?= $d ?>" <?= (($filters['district'] ?? '') === $d) ? 'selected' : '' ?>>
                                อ.<?= $d ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 3. Strategic Pillar (ประเด็นการพัฒนา) -->
                <div class="col-6 col-md-3 col-lg-3">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-layer-group text-warning me-1"></i>ประเด็นการพัฒนา</label>
                    <select class="form-select rounded-pill form-select-sm py-2 shadow-none" name="pillar" onchange="document.getElementById('gisFilterForm').submit()">
                        <option value="">ทุกประเด็นการพัฒนา</option>
                        <?php foreach ($pillars as $pl): ?>
                            <option value="<?= $pl['number'] ?>" <?= ((string)($filters['pillar'] ?? '') === (string)$pl['number']) ? 'selected' : '' ?>>
                                ประเด็นที่ <?= $pl['number'] ?>: <?= esc($pl['short_title'] ?: $pl['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 4. Project Status -->
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-traffic-light text-success me-1"></i>สถานะโครงการ</label>
                    <select class="form-select rounded-pill form-select-sm py-2 shadow-none" name="status" onchange="document.getElementById('gisFilterForm').submit()">
                        <option value="">ทุกสถานะ</option>
                        <option value="in_progress" <?= (($filters['status'] ?? '') === 'in_progress') ? 'selected' : '' ?>>🔵 กำลังดำเนินการ</option>
                        <option value="completed" <?= (($filters['status'] ?? '') === 'completed') ? 'selected' : '' ?>>🟢 แล้วเสร็จ</option>
                        <option value="pending" <?= (($filters['status'] ?? '') === 'pending') ? 'selected' : '' ?>>🟡 รอดำเนินการ</option>
                        <option value="delayed" <?= (($filters['status'] ?? '') === 'delayed') ? 'selected' : '' ?>>🔴 ล่าช้า</option>
                    </select>
                </div>

                <!-- 5. Search Keyword & Clear Button -->
                <div class="col-12 col-lg-3">
                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-magnifying-glass text-secondary me-1"></i>ค้นหาชื่อโครงการ</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control rounded-start-pill py-2 shadow-none" name="q" value="<?= esc($filters['q'] ?? '') ?>" placeholder="ชื่อโครงการ, รหัส, สถานที่...">
                        <button type="submit" class="btn btn-primary px-3 fw-bold"><i class="fa-solid fa-search"></i></button>
                        <?php if (!empty($filters['year']) || !empty($filters['district']) || !empty($filters['status']) || !empty($filters['pillar']) || !empty($filters['q'])): ?>
                            <a href="<?= base_url('projects/gis') ?>" class="btn btn-outline-secondary rounded-end-pill px-2.5" title="ล้างตัวกรอง"><i class="fa-solid fa-rotate-left"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ======================================================== -->
    <!-- 3. INTERACTIVE GIS MAP & PROJECT SIDEBAR -->
    <!-- ======================================================== -->
    <div class="row g-4 mb-5">
        <!-- Center/Main GIS Map Container -->
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden p-2">
                <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 small fw-bold">
                        <span class="text-dark"><i class="fa-solid fa-map-pin text-danger me-1"></i> แผนที่พิกัดโครงการ (<?= count($projects) ?> จุด)</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 small">
                        <span><span class="badge status-badge-completed rounded-circle p-1 me-1"> </span>แล้วเสร็จ</span>
                        <span><span class="badge status-badge-in_progress rounded-circle p-1 me-1"> </span>กำลังดำเนินการ</span>
                        <span><span class="badge status-badge-pending rounded-circle p-1 me-1"> </span>รอดำเนินการ</span>
                    </div>
                </div>

                <!-- Leaflet Map Element -->
                <div id="phatthalungGisMap" class="gis-map-container"></div>
            </div>
        </div>

        <!-- Right/Sidebar Project List -->
        <div class="col-lg-4">
            <div class="project-sidebar-card p-3">
                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                    <h6 class="fw-bold text-dark m-0">
                        <i class="fa-solid fa-list-check text-primary me-1.5"></i> รายการโครงการ (<?= count($projects) ?>)
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1 small">
                        คลิกเพื่อดูพิกัด
                    </span>
                </div>

                <?php if (empty($projects)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-folder-open fs-2 mb-2 text-secondary d-block"></i>
                        ไม่พบข้อมูลโครงการตามเงื่อนไขที่เลือก
                    </div>
                <?php else: ?>
                    <div class="project-items-wrapper">
                        <?php foreach ($projects as $idx => $pj): ?>
                            <?php
                                $statusBadgeClass = 'status-badge-' . ($pj['status'] ?? 'in_progress');
                                $statusLabels = [
                                    'completed'   => 'แล้วเสร็จ',
                                    'in_progress' => 'กำลังดำเนินการ',
                                    'pending'     => 'รอดำเนินการ',
                                    'delayed'     => 'ล่าช้า',
                                ];
                                $statusText = $statusLabels[$pj['status']] ?? 'กำลังดำเนินการ';
                            ?>
                            <div class="project-item-card" id="pjCard_<?= $pj['id'] ?>" onclick="focusProjectOnMap(<?= $pj['id'] ?>, <?= $pj['latitude'] ?? 0 ?>, <?= $pj['longitude'] ?? 0 ?>)">
                                <div class="d-flex align-items-center justify-content-between mb-1.5">
                                    <span class="badge bg-light text-dark border px-2 py-0.5 small" style="font-size: 0.72rem;">
                                        ปี <?= $pj['fiscal_year'] ?> • อ.<?= esc($pj['district']) ?>
                                    </span>
                                    <span class="badge <?= $statusBadgeClass ?> rounded-pill px-2 py-0.5 small" style="font-size: 0.7rem;">
                                        <?= $statusText ?> (<?= $pj['progress_pct'] ?>%)
                                    </span>
                                </div>

                                <h6 class="fw-bold text-dark mb-1.5" style="font-size: 0.92rem; line-height: 1.35;">
                                    <?= esc($pj['project_name']) ?>
                                </h6>

                                <div class="text-muted small mb-2 text-truncate" style="font-size: 0.8rem;">
                                    <i class="fa-regular fa-building me-1"></i><?= esc($pj['agency']) ?>
                                </div>

                                <div class="d-flex align-items-center justify-content-between pt-2 border-top small">
                                    <span class="text-primary fw-bold">
                                        ฿<?= number_format($pj['budget']) ?>
                                    </span>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 0.75rem;" onclick="event.stopPropagation(); openProjectModal(<?= $pj['id'] ?>)">
                                        ดูข้อมูลเต็ม <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- ======================================================== -->
<!-- 4. PROJECT DETAIL SHOWCASE MODAL (ครบถ้วนทั้ง 8 มิติ) -->
<!-- ======================================================== -->
<div class="modal fade" id="projectDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-2xl overflow-hidden">
            <div class="modal-header bg-dark text-white px-4 py-3 border-0">
                <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden me-3">
                    <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill" id="modalPjYearBadge">ปี 2568</span>
                    <h6 class="modal-title fw-bold text-white text-truncate" id="modalPjTitle">รายละเอียดโครงการ</h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    <!-- Left Column: Core Info & KPIs -->
                    <div class="col-lg-7">
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-primary text-white px-2.5 py-1 rounded-pill" id="modalPjPillarBadge">ประเด็นการพัฒนา</span>
                                <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill" id="modalPjCode">รหัส eMENSCR</span>
                            </div>

                            <h5 class="fw-bold text-dark mb-3" id="modalPjFullName" style="line-height: 1.4;">-</h5>

                            <!-- Agency & Location -->
                            <div class="row g-3 mb-3 small">
                                <div class="col-sm-6">
                                    <div class="p-2.5 rounded-3 bg-light border">
                                        <div class="text-muted mb-0.5">หน่วยงานผู้รับผิดชอบ</div>
                                        <div class="fw-bold text-dark" id="modalPjAgency">-</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-2.5 rounded-3 bg-light border">
                                        <div class="text-muted mb-0.5">พื้นที่ดำเนินการ / อำเภอ</div>
                                        <div class="fw-bold text-dark" id="modalPjLocation">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Budget & Disbursement Bar -->
                            <div class="p-3.5 rounded-3 border bg-light mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1.5">
                                    <span class="small fw-bold text-muted">งบประมาณโครงการ</span>
                                    <span class="fs-5 fw-bold text-primary" id="modalPjBudget">฿0</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small text-secondary mb-1">
                                    <span>การเบิกจ่ายจริง: <strong id="modalPjDisbursed">฿0</strong></span>
                                    <span class="fw-bold text-success" id="modalPjDisbursedPct">0%</span>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 10px;">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" id="modalPjDisbursedBar" style="width: 0%;"></div>
                                </div>
                            </div>

                            <!-- Objectives -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-primary mb-1"><i class="fa-solid fa-bullseye me-1"></i> วัตถุประสงค์โครงการ</label>
                                <p class="text-secondary small mb-0 p-3 rounded-3 bg-light border" id="modalPjObjectives" style="line-height: 1.6;">-</p>
                            </div>

                            <!-- KPIs -->
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-success mb-1"><i class="fa-solid fa-chart-line me-1"></i> ตัวชี้วัดความสำเร็จ (KPIs)</label>
                                <div class="p-3 rounded-3 bg-light border small text-secondary" id="modalPjKpis" style="white-space: pre-line; line-height: 1.6;">-</div>
                            </div>

                            <!-- Progress & Status -->
                            <div>
                                <label class="form-label small fw-bold text-dark mb-1"><i class="fa-solid fa-clock-rotate-left me-1"></i> ผลการดำเนินงาน & ความก้าวหน้า</label>
                                <div class="p-3 rounded-3 bg-light border">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge px-3 py-1.5 rounded-pill fw-bold" id="modalPjStatusBadge">สถานะ</span>
                                        <span class="fw-bold fs-6 text-dark" id="modalPjProgressPct">ความก้าวหน้า 0%</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 8px; border-radius: 10px;">
                                        <div class="progress-bar bg-primary" id="modalPjProgressBar" style="width: 0%;"></div>
                                    </div>
                                    <p class="text-secondary small m-0" id="modalPjStatusDesc">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Photos Gallery, PDF Documents & Map Coordinates -->
                    <div class="col-lg-5">
                        <!-- Photo Gallery Card -->
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-images text-warning me-1.5"></i> ภาพถ่ายความก้าวหน้าโครงการ</h6>
                            
                            <!-- Main Large Photo -->
                            <div class="rounded-3 overflow-hidden mb-2 bg-light text-center border" style="height: 220px;">
                                <img src="" id="modalPjMainImg" class="w-100 h-100 object-fit-cover d-none" alt="ภาพโครงการ">
                                <div id="modalPjNoImg" class="h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="fa-solid fa-image fs-1 mb-1 text-secondary"></i>
                                    <span class="small">ไม่มีภาพประกอบ</span>
                                </div>
                            </div>

                            <!-- Thumbnails list -->
                            <div class="d-flex gap-2 overflow-x-auto pb-1" id="modalPjThumbs"></div>
                        </div>

                        <!-- Documents Card -->
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-file-pdf text-danger me-1.5"></i> เอกสารโครงการ & แผนงาน</h6>
                            <div id="modalPjDocsList">
                                <div class="text-muted small p-3 bg-light rounded-3 text-center">ไม่มีเอกสารแนบ</div>
                            </div>
                        </div>

                        <!-- Coordinates Card -->
                        <div class="bg-white p-3.5 rounded-4 shadow-sm border">
                            <div class="d-flex align-items-center justify-content-between small">
                                <span class="text-muted"><i class="fa-solid fa-location-crosshairs text-info me-1"></i> พิกัดภูมิศาสตร์:</span>
                                <span class="fw-bold text-dark" id="modalPjCoords">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let gisMap = null;
let markersLayer = null;
let projectData = <?= json_encode($projects, JSON_UNESCAPED_UNICODE) ?>;
let projectMarkers = {};

document.addEventListener('DOMContentLoaded', function() {
    initPhatthalungGisMap();
});

function initPhatthalungGisMap() {
    const mapEl = document.getElementById('phatthalungGisMap');
    if (!mapEl) return;

    // Center coordinates for Phatthalung Province: [7.6167, 100.0833], Zoom 10
    gisMap = L.map('phatthalungGisMap', {
        center: [7.6167, 100.0833],
        zoom: 10,
        zoomControl: true
    });

    // Base Street Layer (OpenStreetMap)
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(gisMap);

    // Satellite Imagery Layer (ESRI)
    const esriSat = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; ESRI World Imagery'
    });

    // Layer Switcher Control
    const baseMaps = {
        "🗺️ แผนที่มาตรฐาน (Street)": osmLayer,
        "🛰️ ภาพถ่ายดาวเทียม (Satellite)": esriSat
    };
    L.control.layers(baseMaps).addTo(gisMap);

    // Render Markers Layer
    markersLayer = L.layerGroup().addTo(gisMap);
    renderProjectPins(projectData);
}

function renderProjectPins(projects) {
    if (!markersLayer) return;
    markersLayer.clearLayers();
    projectMarkers = {};

    const bounds = L.latLngBounds();
    let hasCoords = false;

    projects.forEach(p => {
        if (!p.latitude || !p.longitude) return;

        const lat = parseFloat(p.latitude);
        const lng = parseFloat(p.longitude);
        bounds.extend([lat, lng]);
        hasCoords = true;

        // Determine pin color by status
        let pinColor = '#2563eb'; // In progress
        if (p.status === 'completed') pinColor = '#10b981';
        else if (p.status === 'pending') pinColor = '#f59e0b';
        else if (p.status === 'delayed') pinColor = '#ef4444';

        const customIcon = L.divIcon({
            className: 'custom-gis-pin-wrapper',
            html: `<div class="custom-gis-pin" style="background: ${pinColor}; width: 34px; height: 34px;">
                     <i class="fa-solid fa-map-pin"></i>
                   </div>`,
            iconSize: [34, 34],
            iconAnchor: [17, 34],
            popupAnchor: [0, -32]
        });

        const popupContent = `
            <div style="min-width: 220px; font-family: 'Sarabun', 'Inter', sans-serif;">
                <div style="font-size: 0.72rem; color: #64748b; font-weight: 700; margin-bottom: 2px;">
                    ปี ${p.fiscal_year} • อ.${p.district}
                </div>
                <div style="font-size: 0.92rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; line-height: 1.35;">
                    ${p.project_name}
                </div>
                <div style="font-size: 0.8rem; color: #475569; margin-bottom: 8px;">
                    งบประมาณ: <strong style="color: #2563eb;">฿${Number(p.budget).toLocaleString()}</strong>
                </div>
                <button type="button" class="btn btn-sm btn-primary rounded-pill w-100 py-1 fw-bold" style="font-size: 0.78rem;" onclick="openProjectModal(${p.id})">
                    <i class="fa-solid fa-circle-info me-1"></i> ดูรายละเอียดเต็ม
                </button>
            </div>
        `;

        const marker = L.marker([lat, lng], { icon: customIcon })
            .bindPopup(popupContent)
            .addTo(markersLayer);

        projectMarkers[p.id] = marker;
    });

    if (hasCoords && projects.length > 1) {
        gisMap.fitBounds(bounds, { padding: [40, 40] });
    }
}

function focusProjectOnMap(id, lat, lng) {
    // Highlight sidebar card
    document.querySelectorAll('.project-item-card').forEach(el => el.classList.remove('active'));
    const card = document.getElementById('pjCard_' + id);
    if (card) card.classList.add('active');

    if (lat && lng && gisMap) {
        gisMap.flyTo([lat, lng], 13, { duration: 1.2 });
        if (projectMarkers[id]) {
            setTimeout(() => {
                projectMarkers[id].openPopup();
            }, 600);
        }
    }
}

async function openProjectModal(id) {
    const modalEl = document.getElementById('projectDetailModal');
    const modal = new bootstrap.Modal(modalEl);

    try {
        const res = await fetch('<?= base_url("projects/detail") ?>/' + id).then(r => r.json());
        if (res.status !== 'success') {
            alert(res.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');
            return;
        }

        const p = res.data;
        document.getElementById('modalPjYearBadge').textContent = 'ปีงบประมาณ ' + p.fiscal_year;
        document.getElementById('modalPjTitle').textContent = p.project_name;
        document.getElementById('modalPjFullName').textContent = p.project_name;
        document.getElementById('modalPjCode').textContent = p.emenscr_code ? `eMENSCR: ${p.emenscr_code}` : 'รหัสโครงการ';
        document.getElementById('modalPjPillarBadge').textContent = p.pillar_title || `ประเด็นที่ ${p.pillar_number}`;
        document.getElementById('modalPjAgency').textContent = p.agency || '-';
        document.getElementById('modalPjLocation').textContent = `อ.${p.district}` + (p.location_name ? ` (${p.location_name})` : '');
        
        // Budget & Disbursed
        document.getElementById('modalPjBudget').textContent = '฿' + Number(p.budget).toLocaleString() + ' บาท';
        document.getElementById('modalPjDisbursed').textContent = '฿' + Number(p.disbursed_budget).toLocaleString() + ' บาท';
        document.getElementById('modalPjDisbursedPct').textContent = p.disbursed_pct + '%';
        document.getElementById('modalPjDisbursedBar').style.width = p.disbursed_pct + '%';

        document.getElementById('modalPjObjectives').textContent = p.objectives || 'ไม่ได้ระบุวัตถุประสงค์';
        document.getElementById('modalPjKpis').textContent = p.kpis || 'ไม่ได้ระบุตัวชี้วัด';
        
        // Status & Progress
        const statusMap = {
            'completed': { label: 'แล้วเสร็จ', class: 'status-badge-completed' },
            'in_progress': { label: 'กำลังดำเนินการ', class: 'status-badge-in_progress' },
            'pending': { label: 'รอดำเนินการ', class: 'status-badge-pending' },
            'delayed': { label: 'ล่าช้า', class: 'status-badge-delayed' }
        };
        const stInfo = statusMap[p.status] || { label: 'กำลังดำเนินการ', class: 'status-badge-in_progress' };
        const stBadge = document.getElementById('modalPjStatusBadge');
        stBadge.className = `badge px-3 py-1.5 rounded-pill fw-bold ${stInfo.class}`;
        stBadge.textContent = stInfo.label;
        document.getElementById('modalPjProgressPct').textContent = `ความก้าวหน้า ${p.progress_pct}%`;
        document.getElementById('modalPjProgressBar').style.width = p.progress_pct + '%';
        document.getElementById('modalPjStatusDesc').textContent = p.status_desc || 'การดำเนินงานเป็นไปตามแผนงาน';

        // Photos
        const mainImg = document.getElementById('modalPjMainImg');
        const noImg = document.getElementById('modalPjNoImg');
        const thumbs = document.getElementById('modalPjThumbs');
        thumbs.innerHTML = '';

        if (p.photos_array && p.photos_array.length > 0) {
            mainImg.src = p.photos_array[0];
            mainImg.classList.remove('d-none');
            noImg.classList.add('d-none');

            p.photos_array.forEach(imgUrl => {
                const imgThumb = document.createElement('img');
                imgThumb.src = imgUrl;
                imgThumb.className = 'gallery-thumb border';
                imgThumb.onclick = () => { mainImg.src = imgUrl; };
                thumbs.appendChild(imgThumb);
            });
        } else {
            mainImg.classList.add('d-none');
            noImg.classList.remove('d-none');
        }

        // Documents
        const docsContainer = document.getElementById('modalPjDocsList');
        docsContainer.innerHTML = '';
        if (p.documents_array && p.documents_array.length > 0) {
            p.documents_array.forEach(doc => {
                docsContainer.innerHTML += `
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-light border mb-2">
                        <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                            <i class="fa-solid fa-file-pdf text-danger fs-5 flex-shrink-0"></i>
                            <span class="small fw-bold text-dark text-truncate">${doc.title || 'เอกสารโครงการ.pdf'}</span>
                        </div>
                        <a href="<?= base_url() ?>/${doc.file_url}" download class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold flex-shrink-0">
                            <i class="fa-solid fa-download me-1"></i> โหลด PDF
                        </a>
                    </div>
                `;
            });
        } else {
            docsContainer.innerHTML = '<div class="text-muted small p-3 bg-light rounded-3 text-center">ไม่มีเอกสารแนบ</div>';
        }

        // Coordinates
        document.getElementById('modalPjCoords').textContent = (p.latitude && p.longitude) ? `${p.latitude}, ${p.longitude}` : 'ไม่ได้ระบุพิกัด';

        modal.show();
    } catch (e) {
        alert('เกิดข้อผิดพลาด: ' + e.message);
    }
}
</script>

<?= $this->endSection() ?>

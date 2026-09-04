<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$projects   = $projects ?? [];
$filters    = $filters ?? [];
$yearsList  = $yearsList ?? [];
$districts  = $districts ?? [];
$pillars    = $pillars ?? [];
$settings   = $settings ?? [];
$summary    = $summary ?? [];
$totalCount = $totalCount ?? count($projects);
?>

<!-- Leaflet CSS & JS for Admin Map Picker -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-map-location-dot me-2 text-primary"></i> จัดการโครงการเชิงพื้นที่ (GIS) & เชื่อมโยง eMENSCR</h4>
        <small class="text-muted">บันทึกข้อมูลโครงการ ปักหมุดแผนที่ GIS แนบรูปภาพผลการดำเนินงาน และเชื่อมต่อ eMENSCR API</small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('projects/gis') ?>" target="_blank" class="btn btn-outline-primary rounded-pill px-3 fw-bold">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ดูหน้าแผนที่ GIS ประชาชน
        </a>
        <a href="<?= base_url('projects/dashboard') ?>" target="_blank" class="btn btn-outline-info rounded-pill px-3 fw-bold">
            <i class="fa-solid fa-chart-pie me-1"></i> ดูหน้า Dashboard
        </a>
    </div>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm border" id="adminProjectTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 py-2 fw-bold" id="tab-list-btn" data-bs-toggle="pill" data-bs-target="#tab-list" type="button" role="tab">
            <i class="fa-solid fa-list-check me-1.5 text-primary"></i> รายการโครงการ (<?= $totalCount ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 py-2 fw-bold" id="tab-form-btn" data-bs-toggle="pill" data-bs-target="#tab-form" type="button" role="tab" onclick="resetProjectForm()">
            <i class="fa-solid fa-plus-circle me-1.5 text-success"></i> เพิ่มโครงการใหม่ (Add Project)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 py-2 fw-bold" id="tab-sync-btn" data-bs-toggle="pill" data-bs-target="#tab-sync" type="button" role="tab">
            <i class="fa-solid fa-cloud-arrow-down me-1.5 text-warning"></i> ซิงค์ eMENSCR API (สภาพัฒน์)
        </button>
    </li>
</ul>

<div class="tab-content" id="adminProjectTabsContent">

    <!-- ======================================================== -->
    <!-- TAB 1: ALL PROJECTS LIST -->
    <!-- ======================================================== -->
    <div class="tab-pane fade show active" id="tab-list" role="tabpanel">
        <div class="admin-card mb-4">
            <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-folder-open me-2 text-primary"></i> รายการโครงการทั้งหมด</h6>
                
                <!-- Quick Filter in Table Header -->
                <form method="GET" action="<?= base_url('admin/projects') ?>" class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm rounded-pill shadow-none" name="year" onchange="this.form.submit()">
                        <option value="">ทุกปีงบประมาณ</option>
                        <?php foreach ($yearsList as $yr): ?>
                            <option value="<?= $yr ?>" <?= ((string)($filters['year'] ?? '') === (string)$yr) ? 'selected' : '' ?>>ปี <?= $yr ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="form-select form-select-sm rounded-pill shadow-none" name="district" onchange="this.form.submit()">
                        <option value="">ทุกอำเภอ</option>
                        <?php foreach ($districts as $d): ?>
                            <option value="<?= $d ?>" <?= (($filters['district'] ?? '') === $d) ? 'selected' : '' ?>>อ.<?= $d ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control form-control-sm rounded-pill shadow-none" name="q" value="<?= esc($filters['q'] ?? '') ?>" placeholder="ค้นหาชื่อโครงการ...">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3"><i class="fa-solid fa-search"></i></button>
                </form>
            </div>

            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="4%" class="text-center">#</th>
                                <th width="10%">ปีงบฯ / รหัส</th>
                                <th width="32%">ชื่อโครงการ / หน่วยงาน</th>
                                <th width="12%">พื้นที่ / พิกัด</th>
                                <th width="12%" class="text-end">งบประมาณ</th>
                                <th width="12%" class="text-end">เบิกจ่าย</th>
                                <th width="10%" class="text-center">สถานะ</th>
                                <th width="8%" class="text-end pe-3">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($projects)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-folder-open fs-2 mb-2 text-secondary d-block"></i>
                                        ไม่พบข้อมูลโครงการตามเงื่อนไข
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($projects as $idx => $p): ?>
                                    <tr>
                                        <td class="text-center fw-bold text-muted"><?= $idx + 1 ?></td>
                                        <td>
                                            <div class="badge bg-dark text-warning mb-1">ปี <?= $p['fiscal_year'] ?></div>
                                            <div class="small text-muted font-monospace"><?= esc($p['emenscr_code']) ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-6"><?= esc($p['project_name']) ?></div>
                                            <small class="text-muted"><i class="fa-regular fa-building me-1"></i><?= esc($p['agency']) ?></small>
                                            <?php if (!empty($p['photos_array'])): ?>
                                                <span class="badge bg-light text-secondary border ms-1"><i class="fa-solid fa-image me-1"></i><?= count($p['photos_array']) ?> รูป</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">อ.<?= esc($p['district']) ?></div>
                                            <small class="text-muted text-truncate d-block" style="max-width: 140px;"><?= esc($p['location_name'] ?: '-') ?></small>
                                            <?php if (!empty($p['latitude']) && !empty($p['longitude'])): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 0.68rem;"><i class="fa-solid fa-map-pin me-1"></i>มีพิกัด GIS</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.68rem;">ไม่มีพิกัด</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            ฿<?= number_format($p['budget']) ?>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            ฿<?= number_format($p['disbursed_budget']) ?>
                                            <div class="text-muted small"><?= $p['disbursed_pct'] ?>%</div>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $stBadge = 'bg-primary';
                                                $stName = 'กำลังดำเนินการ';
                                                if ($p['status'] === 'completed') { $stBadge = 'bg-success'; $stName = 'แล้วเสร็จ'; }
                                                elseif ($p['status'] === 'pending') { $stBadge = 'bg-warning text-dark'; $stName = 'รอดำเนินการ'; }
                                                elseif ($p['status'] === 'delayed') { $stBadge = 'bg-danger'; $stName = 'ล่าช้า'; }
                                            ?>
                                            <span class="badge <?= $stBadge ?> rounded-pill px-2.5 py-1 small"><?= $stName ?></span>
                                            <div class="small text-muted mt-0.5"><?= $p['progress_pct'] ?>%</div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick='editProject(<?= json_encode($p) ?>)' title="แก้ไข">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteProject(<?= $p['id'] ?>, '<?= esc($p['project_name']) ?>')" title="ลบ">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
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

    <!-- ======================================================== -->
    <!-- TAB 2: ADD / EDIT PROJECT (WITH INTERACTIVE MAP PICKER) -->
    <!-- ======================================================== -->
    <div class="tab-pane fade" id="tab-form" role="tabpanel">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" id="formHeaderTitle"><i class="fa-solid fa-plus-circle me-2 text-success"></i> บันทึกข้อมูลโครงการใหม่</h6>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="switchToTab('tab-list-btn')">
                    <i class="fa-solid fa-arrow-left me-1"></i> กลับหน้ารายการ
                </button>
            </div>
            <div class="admin-card-body p-4">
                <form id="projectEditForm">
                    <input type="hidden" id="projectId" name="id">

                    <!-- Section 1: Basic Info -->
                    <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-circle-info me-1.5"></i> 1. ข้อมูลพื้นฐานโครงการ & ยุทธศาสตร์</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ปีงบประมาณ <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="formFiscalYear" name="fiscal_year" value="2568" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">รหัสโครงการ (eMENSCR Code)</label>
                            <input type="text" class="form-control" id="formEmenscrCode" name="emenscr_code" placeholder="เช่น 68-9300-0101">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">ประเด็นการพัฒนาจังหวัด</label>
                            <select class="form-select" id="formPillarNumber" name="pillar_number">
                                <?php foreach ($pillars as $pl): ?>
                                    <option value="<?= $pl['number'] ?>">ประเด็นที่ <?= $pl['number'] ?>: <?= esc($pl['short_title'] ?: $pl['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-bold">ชื่อโครงการ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="formProjectName" name="project_name" required placeholder="ระบุชื่อโครงการอย่างเป็นทางการ...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">หน่วยงานผู้รับผิดชอบ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="formAgency" name="agency" required placeholder="เช่น สำนักงานเกษตรจังหวัดพัทลุง">
                        </div>
                    </div>

                    <!-- Section 2: Budget & Progress -->
                    <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-sack-dollar me-1.5"></i> 2. งบประมาณ & ผลความก้าวหน้า</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">งบประมาณโครงการ (บาท) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="formBudget" name="budget" required value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">การเบิกจ่ายงบประมาณจริง (บาท)</label>
                            <input type="number" step="0.01" class="form-control" id="formDisbursedBudget" name="disbursed_budget" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">สถานะโครงการ</label>
                            <select class="form-select" id="formStatus" name="status">
                                <option value="in_progress">🔵 กำลังดำเนินการ</option>
                                <option value="completed">🟢 แล้วเสร็จ</option>
                                <option value="pending">🟡 รอดำเนินการ</option>
                                <option value="delayed">🔴 ล่าช้า</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">ความก้าวหน้า (%) (0-100)</label>
                            <input type="number" min="0" max="100" class="form-control" id="formProgressPct" name="progress_pct" value="0">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-bold">รายละเอียดผลการดำเนินงาน / สถานะล่าสุด</label>
                            <input type="text" class="form-control" id="formStatusDesc" name="status_desc" placeholder="เช่น อยู่ระหว่างส่งมอบงานงวดที่ 2">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">วัตถุประสงค์โครงการ</label>
                            <textarea class="form-control" id="formObjectives" name="objectives" rows="3" placeholder="ระบุวัตถุประสงค์โครงการ..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ตัวชี้วัดความสำเร็จ (KPIs) (1 บรรทัดต่อ 1 ข้อ)</label>
                            <textarea class="form-control" id="formKpis" name="kpis" rows="3" placeholder="1. ตัวชี้วัดที่ 1...&#10;2. ตัวชี้วัดที่ 2..."></textarea>
                        </div>
                    </div>

                    <!-- Section 3: GIS Location & Interactive Map Picker -->
                    <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-location-dot me-1.5"></i> 3. ตำแหน่งที่ตั้ง & พิกัดแผนที่ GIS (คลิกบนแผนที่เพื่อปักหมุด)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">อำเภอ <span class="text-danger">*</span></label>
                            <select class="form-select" id="formDistrict" name="district">
                                <?php foreach ($districts as $d): ?>
                                    <option value="<?= $d ?>">อ.<?= $d ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ตำบล / เทศบาล</label>
                            <input type="text" class="form-control" id="formSubdistrict" name="subdistrict" placeholder="เช่น ตำบลทะเลน้อย">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ชื่อสถานที่เฉพาะ / จุดสังเกต</label>
                            <input type="text" class="form-control" id="formLocationName" name="location_name" placeholder="เช่น สะพานเฉลิมพระเกียรติฯ 80 พรรษา">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Latitude (ละติจูด)</label>
                            <input type="text" class="form-control font-monospace" id="formLatitude" name="latitude" placeholder="7.6167000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Longitude (ลองจิจูด)</label>
                            <input type="text" class="form-control font-monospace" id="formLongitude" name="longitude" placeholder="100.0833000">
                        </div>

                        <!-- Map Picker Container -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-4 border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-bold text-dark"><i class="fa-solid fa-hand-pointer text-primary me-1"></i> คลิกบนแผนที่ด้านล่างเพื่อปักหมุดหาพิกัด Lat/Lng อัตโนมัติ:</span>
                                    <span class="badge bg-primary rounded-pill px-2.5 py-1 small" id="mapPickerStatus">พร้อมปักหมุด</span>
                                </div>
                                <div id="adminMapPicker" style="height: 320px; border-radius: 14px;" class="border"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Photo Gallery & PDF Documents -->
                    <h6 class="fw-bold text-warning mb-3"><i class="fa-solid fa-photo-film me-1.5"></i> 4. รูปภาพผลงาน & เอกสารโครงการ</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ลิงก์รูปภาพผลการดำเนินงาน (1 URL ต่อ 1 บรรทัด)</label>
                            <textarea class="form-control font-monospace small" id="formPhotos" name="photos" rows="3" placeholder="https://example.com/photo1.jpg&#10;https://example.com/photo2.jpg"></textarea>
                            <div class="mt-2">
                                <label class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa-solid fa-upload me-1"></i> อัปโหลดรูปภาพเข้าเซิร์ฟเวอร์
                                    <input type="file" class="d-none" id="photoUploadInput" accept="image/*" onchange="uploadProjectPhoto(this)">
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">เอกสารโครงการ (PDF)</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-file-pdf text-danger"></i></span>
                                <input type="text" class="form-control" id="formDocTitle" name="doc_title" placeholder="ชื่อเอกสาร เช่น รายละเอียดโครงการ.pdf">
                            </div>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control font-monospace small" id="formDocUrl" name="doc_file_url" placeholder="uploads/projects/docs/filename.pdf">
                                <label class="btn btn-outline-danger">
                                    <i class="fa-solid fa-file-arrow-up"></i> อัปโหลด PDF
                                    <input type="file" class="d-none" id="docUploadInput" accept=".pdf" onchange="uploadProjectDoc(this)">
                                </label>
                            </div>
                            <input type="hidden" id="formDocSize" name="doc_file_size" value="PDF">
                        </div>
                    </div>

                    <div class="text-end pt-3 border-top">
                        <button type="button" class="btn btn-secondary px-4 me-2 rounded-pill" onclick="switchToTab('tab-list-btn')">ยกเลิก</button>
                        <button type="button" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" id="btnSaveProject" onclick="saveProjectData()">
                            <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูลโครงการ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 3: eMENSCR API SYNC & SETTINGS -->
    <!-- ======================================================== -->
    <div class="tab-pane fade" id="tab-sync" role="tabpanel">
        <div class="row g-4">
            <!-- Sync Hub Card -->
            <div class="col-lg-5">
                <div class="admin-card h-100">
                    <div class="admin-card-header">
                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-arrows-rotate me-2 text-warning"></i> ระบบซิงค์ข้อมูล eMENSCR API (สภาพัฒน์)</h6>
                    </div>
                    <div class="admin-card-body p-4 text-center">
                        <div class="p-4 rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px;">
                            <i class="fa-solid fa-cloud-arrow-down fs-1"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">เชื่อมต่อฐานข้อมูลโครงการ สศช.</h5>
                        <p class="text-muted small mb-4">
                            ดึงข้อมูลโครงการตามยุทธศาสตร์ชาติ แผนแม่บท และแผนปฏิบัติราชการประจำปีของจังหวัดพัทลุงเข้าสู่ระบบ GIS และ Dashboard อัตโนมัติ
                        </p>

                        <div class="p-3.5 rounded-3 bg-light border text-start mb-4 small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">สถานะการซิงค์:</span>
                                <span class="badge bg-success" id="syncStatusBadge"><?= esc($settings['last_sync_status'] ?? 'ready') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">เวลาที่ซิงค์ล่าสุด:</span>
                                <span class="fw-bold text-dark" id="syncTimeText"><?= !empty($settings['last_sync_time']) ? date('d/m/Y H:i น.', strtotime($settings['last_sync_time'])) : '-' ?></span>
                            </div>
                            <div class="text-muted mt-2 border-top pt-2" id="syncMessageText">
                                <?= esc(($settings['last_sync_message'] ?? '') ?: 'พร้อมเชื่อมต่อ API') ?>
                            </div>
                        </div>

                        <button type="button" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold text-dark shadow w-100" id="btnSyncNow" onclick="triggerEmenscrSync()">
                            <i class="fa-solid fa-rotate me-1.5"></i> ซิงค์ข้อมูลโครงการเดี๋ยวนี้ (Sync Now)
                        </button>
                    </div>
                </div>
            </div>

            <!-- API Configuration Card -->
            <div class="col-lg-7">
                <div class="admin-card h-100">
                    <div class="admin-card-header">
                        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-gears me-2 text-primary"></i> การตั้งค่า eMENSCR API Token & Endpoint</h6>
                    </div>
                    <div class="admin-card-body p-4">
                        <form id="emenscrSettingsForm">
                            <div class="mb-3">
                                <label class="form-label fw-bold">API Endpoint (URL ระบบ eMENSCR สภาพัฒน์)</label>
                                <input type="url" class="form-control font-monospace" name="api_endpoint" value="<?= esc($settings['api_endpoint'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">API Token / Bearer Key</label>
                                <input type="password" class="form-control font-monospace" name="api_token" value="<?= esc($settings['api_token'] ?? '') ?>" placeholder="กรอก API Token ที่ได้รับจาก สศช.">
                                <small class="text-muted">รหัส Token การเข้าถึง API จากสำนักงานสภาพัฒนาการเศรษฐกิจและสังคมแห่งชาติ</small>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">รหัสจังหวัด (Province Code)</label>
                                    <input type="text" class="form-control" name="province_code" value="<?= esc($settings['province_code'] ?? '93') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">ชื่อจังหวัด</label>
                                    <input type="text" class="form-control" name="province_name" value="<?= esc($settings['province_name'] ?? 'พัทลุง') ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_sync" id="autoSyncCheck" <?= !empty($settings['auto_sync']) ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold" for="autoSyncCheck">เปิดใช้งาน Auto-Sync อัตโนมัติทุกวัน</label>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm" onclick="saveEmenscrSettings()">
                                    <i class="fa-solid fa-save me-1"></i> บันทึกการตั้งค่า API
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
let pickerMap = null;
let pickerMarker = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Leaflet Map Picker when tab-form is shown
    const formTabBtn = document.getElementById('tab-form-btn');
    if (formTabBtn) {
        formTabBtn.addEventListener('shown.bs.tab', function() {
            setTimeout(initAdminMapPicker, 200);
        });
    }
});

function initAdminMapPicker() {
    if (pickerMap) {
        pickerMap.invalidateSize();
        return;
    }

    const latInput = document.getElementById('formLatitude');
    const lngInput = document.getElementById('formLongitude');
    let initLat = parseFloat(latInput.value) || 7.6167;
    let initLng = parseFloat(lngInput.value) || 100.0833;

    pickerMap = L.map('adminMapPicker', {
        center: [initLat, initLng],
        zoom: 11
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(pickerMap);

    pickerMarker = L.marker([initLat, initLng], { draggable: true }).addTo(pickerMap);

    pickerMarker.on('dragend', function(e) {
        const coord = e.target.getLatLng();
        latInput.value = coord.lat.toFixed(7);
        lngInput.value = coord.lng.toFixed(7);
    });

    pickerMap.on('click', function(e) {
        pickerMarker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat.toFixed(7);
        lngInput.value = e.latlng.lng.toFixed(7);
        document.getElementById('mapPickerStatus').textContent = 'ปักหมุดแล้ว';
    });
}

function switchToTab(tabBtnId) {
    const tabEl = document.getElementById(tabBtnId);
    if (tabEl) {
        const tab = new bootstrap.Tab(tabEl);
        tab.show();
    }
}

function resetProjectForm() {
    document.getElementById('projectId').value = '';
    document.getElementById('projectEditForm').reset();
    document.getElementById('formHeaderTitle').innerHTML = '<i class="fa-solid fa-plus-circle me-2 text-success"></i> บันทึกข้อมูลโครงการใหม่';
    document.getElementById('formFiscalYear').value = '2568';
    document.getElementById('formBudget').value = '0';
    document.getElementById('formDisbursedBudget').value = '0';
    document.getElementById('formProgressPct').value = '0';
    document.getElementById('formLatitude').value = '7.6167000';
    document.getElementById('formLongitude').value = '100.0833000';

    if (pickerMarker && pickerMap) {
        pickerMarker.setLatLng([7.6167, 100.0833]);
        pickerMap.setView([7.6167, 100.0833], 11);
    }
}

function editProject(p) {
    document.getElementById('projectId').value = p.id || '';
    document.getElementById('formFiscalYear').value = p.fiscal_year || 2568;
    document.getElementById('formEmenscrCode').value = p.emenscr_code || '';
    document.getElementById('formPillarNumber').value = p.pillar_number || 1;
    document.getElementById('formProjectName').value = p.project_name || '';
    document.getElementById('formAgency').value = p.agency || '';
    document.getElementById('formBudget').value = p.budget || 0;
    document.getElementById('formDisbursedBudget').value = p.disbursed_budget || 0;
    document.getElementById('formStatus').value = p.status || 'in_progress';
    document.getElementById('formProgressPct').value = p.progress_pct || 0;
    document.getElementById('formStatusDesc').value = p.status_desc || '';
    document.getElementById('formObjectives').value = p.objectives || '';
    document.getElementById('formKpis').value = p.kpis || '';
    document.getElementById('formDistrict').value = p.district || 'เมืองพัทลุง';
    document.getElementById('formSubdistrict').value = p.subdistrict || '';
    document.getElementById('formLocationName').value = p.location_name || '';

    const lat = p.latitude || 7.6167;
    const lng = p.longitude || 100.0833;
    document.getElementById('formLatitude').value = lat;
    document.getElementById('formLongitude').value = lng;

    if (Array.isArray(p.photos_array)) {
        document.getElementById('formPhotos').value = p.photos_array.join("\n");
    } else {
        document.getElementById('formPhotos').value = '';
    }

    if (Array.isArray(p.documents_array) && p.documents_array.length > 0) {
        document.getElementById('formDocTitle').value = p.documents_array[0].title || '';
        document.getElementById('formDocUrl').value = p.documents_array[0].file_url || '';
        document.getElementById('formDocSize').value = p.documents_array[0].file_size || 'PDF';
    } else {
        document.getElementById('formDocTitle').value = '';
        document.getElementById('formDocUrl').value = '';
    }

    document.getElementById('formHeaderTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2 text-warning"></i> แก้ไขโครงการ: ' + p.project_name;
    
    switchToTab('tab-form-btn');

    setTimeout(() => {
        initAdminMapPicker();
        if (pickerMarker && pickerMap) {
            pickerMarker.setLatLng([parseFloat(lat), parseFloat(lng)]);
            pickerMap.setView([parseFloat(lat), parseFloat(lng)], 13);
        }
    }, 300);
}

async function saveProjectData() {
    const form = document.getElementById('projectEditForm');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSaveProject');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    try {
        const res = await App.fetch('<?= base_url("admin/projects/save") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast(res.message, 'success');
            setTimeout(() => location.reload(), 700);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกข้อมูลโครงการ';
    }
}

async function deleteProject(id, name) {
    if (!confirm(`คุณต้องการลบโครงการ "${name}" ใช่หรือไม่?`)) return;

    try {
        const res = await App.fetch('<?= base_url("admin/projects/delete") ?>/' + id, {
            method: 'POST'
        });
        if (res.status === 'success') {
            App.toast('ลบโครงการเรียบร้อยแล้ว', 'success');
            setTimeout(() => location.reload(), 600);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    }
}

async function triggerEmenscrSync() {
    const btn = document.getElementById('btnSyncNow');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> กำลังซิงค์ eMENSCR API...';

    try {
        const res = await App.fetch('<?= base_url("admin/projects/sync-emenscr") ?>', {
            method: 'POST'
        });
        if (res.status === 'success') {
            App.toast(res.message, 'success');
            document.getElementById('syncStatusBadge').textContent = 'success';
            document.getElementById('syncTimeText').textContent = res.last_sync;
            document.getElementById('syncMessageText').textContent = res.message;
            setTimeout(() => location.reload(), 1000);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-rotate me-1.5"></i> ซิงค์ข้อมูลโครงการเดี๋ยวนี้ (Sync Now)';
    }
}

async function saveEmenscrSettings() {
    const form = document.getElementById('emenscrSettingsForm');
    const formData = new FormData(form);

    try {
        const res = await App.fetch('<?= base_url("admin/projects/save-settings") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('บันทึกการตั้งค่า eMENSCR เรียบร้อยแล้ว', 'success');
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    }
}

async function uploadProjectPhoto(input) {
    if (!input.files || !input.files[0]) return;

    const formData = new FormData();
    formData.append('file', input.files[0]);

    try {
        App.toast('กำลังอัปโหลดรูปภาพ...', 'info');
        const res = await App.fetch('<?= base_url("admin/projects/upload-photo") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('อัปโหลดรูปภาพสำเร็จ', 'success');
            const photosArea = document.getElementById('formPhotos');
            if (photosArea.value.trim()) {
                photosArea.value += "\n" + res.url;
            } else {
                photosArea.value = res.url;
            }
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    }
}

async function uploadProjectDoc(input) {
    if (!input.files || !input.files[0]) return;

    const formData = new FormData();
    formData.append('file', input.files[0]);

    try {
        App.toast('กำลังอัปโหลดเอกสาร PDF...', 'info');
        const res = await App.fetch('<?= base_url("admin/projects/upload-doc") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('อัปโหลดเอกสารสำเร็จ', 'success');
            document.getElementById('formDocTitle').value = res.orig_name;
            document.getElementById('formDocUrl').value = res.file_url;
            document.getElementById('formDocSize').value = res.file_size;
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    }
}
</script>

<?= $this->endSection() ?>

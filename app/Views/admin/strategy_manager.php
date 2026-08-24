<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$vision = $vision ?? [];
$missions = $missions ?? [];
$kpis = $kpis ?? [];
$pillars = $pillars ?? [];
$documents = $documents ?? [];
?>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #0f172a;">จัดการยุทธศาสตร์และแผนพัฒนาจังหวัด</h4>
        <p class="text-muted mb-0" style="font-size: 0.92rem;">
            บริหารจัดการวิสัยทัศน์ 5 เสาหลักยุทธศาสตร์ ตัวชี้วัดเป้าหมาย และเอกสารแผนปฏิบัติราชการประจำปี
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('strategy') ?>" target="_blank" class="btn btn-outline-primary fw-bold rounded-pill px-3.5 d-inline-flex align-items-center gap-1.5 shadow-sm">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> ดูหน้าเว็บประชาชน
        </a>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-pills mb-4 gap-2" id="strategyAdminTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 py-2 fw-bold" id="tab-vision-btn" data-bs-toggle="pill" data-bs-target="#tab-vision" type="button" role="tab">
            <i class="fa-solid fa-bullseye me-1.5 text-warning"></i> วิสัยทัศน์ & พันธกิจ
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 py-2 fw-bold" id="tab-kpis-btn" data-bs-toggle="pill" data-bs-target="#tab-kpis" type="button" role="tab">
            <i class="fa-solid fa-chart-pie me-1.5 text-success"></i> ตัวชี้วัดเป้าหมาย (KPIs) (<?= count($kpis ?? []) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 py-2 fw-bold" id="tab-pillars-btn" data-bs-toggle="pill" data-bs-target="#tab-pillars" type="button" role="tab">
            <i class="fa-solid fa-layer-group me-1.5 text-primary"></i> ประเด็นการพัฒนาจังหวัด (<?= count($pillars ?? []) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 py-2 fw-bold" id="tab-docs-btn" data-bs-toggle="pill" data-bs-target="#tab-docs" type="button" role="tab">
            <i class="fa-solid fa-folder-open me-1.5 text-info"></i> แผนพัฒนาฯ & แผนประจำปี (<?= count($documents ?? []) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="strategyAdminTabsContent">

    <!-- ======================================================== -->
    <!-- TAB 1: VISION & MISSIONS -->
    <!-- ======================================================== -->
    <div class="tab-pane fade show active" id="tab-vision" role="tabpanel">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-bullseye me-2 text-warning"></i> กำหนดวิสัยทัศน์ พันธกิจ และค่านิยมจังหวัดพัทลุง</h6>
            </div>
            <div class="admin-card-body p-4">
                <form id="visionForm">
                    <div class="row g-4 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold">หัวข้อวิสัยทัศน์ (Vision Title)</label>
                            <input type="text" class="form-control" name="title" value="<?= esc($vision['title'] ?? 'วิสัยทัศน์การพัฒนาจังหวัดพัทลุง (พ.ศ. 2566 - 2570)') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">กรอบระยะเวลา (Period)</label>
                            <input type="text" class="form-control" name="period" value="<?= esc($vision['period'] ?? 'พ.ศ. 2566 - 2570') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">ข้อความวิสัยทัศน์ (Vision Statement) <span class="text-danger">*</span></label>
                        <textarea class="form-control fs-6 p-3" name="statement" rows="3" required><?= esc($vision['statement'] ?? '') ?></textarea>
                        <small class="text-muted">ข้อความวิสัยทัศน์หลัก 5 ปี จะแสดงผลอย่างเด่นชัดบนแบนเนอร์หน้าพอร์ทัล</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">คำขวัญ / อัตลักษณ์การพัฒนา (Development Motto)</label>
                        <input type="text" class="form-control" name="motto" value="<?= esc($vision['motto'] ?? '') ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">พันธกิจหลัก (Missions) <span class="text-muted small fw-normal">(พิมพ์ 1 บรรทัดต่อ 1 ข้อ)</span></label>
                        <textarea class="form-control" name="missions" rows="6"><?php
                            if (!empty($missions)) {
                                echo esc(implode("\n", $missions));
                            }
                        ?></textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" onclick="saveVision()">
                            <i class="fa-solid fa-save me-1.5"></i> บันทึกวิสัยทัศน์และพันธกิจ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 2: KPIS & TARGETS (DYNAMIC ADD / EDIT / DELETE) -->
    <!-- ======================================================== -->
    <div class="tab-pane fade" id="tab-kpis" role="tabpanel">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-chart-pie me-2 text-success"></i> ตัวชี้วัดและเป้าหมายความสำเร็จ (Key Target KPIs)</h6>
                    <small class="text-muted">คุณสามารถเพิ่ม แก้ไข ปรับเปลี่ยนสี ไอคอน หรือลบตัวชี้วัดได้อย่างอิสระ</small>
                </div>
                <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm" onclick="openKpiModal()">
                    <i class="fa-solid fa-plus me-1"></i> เพิ่มตัวชี้วัดใหม่ (Add KPI)
                </button>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="8%" class="text-center">ไอคอน</th>
                                <th width="35%">ชื่อตัวชี้วัด & คำอธิบาย</th>
                                <th width="18%" class="text-center">ค่าเป้าหมาย (Target)</th>
                                <th width="16%" class="text-center">ค่าปัจจุบัน</th>
                                <th width="18%" class="text-end pe-4">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($kpis)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-chart-pie fs-3 mb-2 text-secondary d-block"></i>
                                        ยังไม่มีตัวชี้วัดเป้าหมาย กรุณากดปุ่ม "เพิ่มตัวชี้วัดใหม่" ด้านบน
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($kpis as $idx => $k): ?>
                                    <tr>
                                        <td class="text-center fw-bold text-muted"><?= $idx + 1 ?></td>
                                        <td class="text-center">
                                            <div class="p-2 rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: rgba(0,0,0,0.04); color: <?= esc($k['color'] ?? '#2563eb') ?>;">
                                                <i class="<?= esc($k['icon'] ?? 'fa-solid fa-chart-line') ?> fs-5"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-6"><?= esc($k['title']) ?></div>
                                            <small class="text-muted"><?= esc($k['desc'] ?? '-') ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="fs-5 fw-bold" style="color: <?= esc($k['color'] ?? '#2563eb') ?>;">
                                                <?= esc($k['target']) ?>
                                            </span>
                                            <span class="badge bg-light text-secondary border ms-1 small"><?= esc($k['unit']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-secondary fw-semibold"><?= esc($k['current'] ?? '-') ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick='editKpi(<?= json_encode($k) ?>)' title="แก้ไข">
                                                <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteKpi('<?= $k['id'] ?>', '<?= esc($k['title']) ?>')" title="ลบ">
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
    <!-- TAB 3: PROVINCIAL DEVELOPMENT THEMES (DYNAMIC CRUD) -->
    <!-- ======================================================== -->
    <div class="tab-pane fade" id="tab-pillars" role="tabpanel">
        <div class="admin-card mb-4">
            <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-layer-group me-2 text-primary"></i> ประเด็นการพัฒนาจังหวัด (Strategic Development Themes)</h6>
                    <small class="text-muted">กำหนดประเด็นการพัฒนา สาระสำคัญ กลยุทธ์ขับเคลื่อน และโครงการสำคัญ</small>
                </div>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" onclick="openPillarModal()">
                    <i class="fa-solid fa-plus me-1"></i> เพิ่มประเด็นการพัฒนาใหม่ (Add Theme)
                </button>
            </div>
            <div class="admin-card-body p-4">
                <?php if (empty($pillars)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-layer-group fs-2 mb-2 text-secondary d-block"></i>
                        ยังไม่มีประเด็นการพัฒนา กรุณากดปุ่ม "เพิ่มประเด็นการพัฒนาใหม่" ด้านบน
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($pillars as $p): ?>
                            <div class="col-lg-6">
                                <div class="p-4 rounded-4 border bg-white shadow-sm h-100 d-flex flex-column position-relative transition-all hover-lift" style="border-radius: 20px;">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="p-2.5 rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" style="width: 46px; height: 46px; background: <?= esc($p['bg_gradient'] ?? 'linear-gradient(135deg, #059669, #10b981)') ?>;">
                                                <?= $p['number'] ?>
                                            </div>
                                            <div>
                                                <span class="badge px-2.5 py-1 rounded-pill mb-1" style="background: rgba(0,0,0,0.06); color: <?= esc($p['color'] ?? '#059669') ?>; font-size: 0.78rem;">
                                                    ประเด็นที่ <?= $p['number'] ?>
                                                </span>
                                                <h6 class="fw-bold text-dark m-0 fs-6"><?= esc($p['short_title']) ?></h6>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick='editPillar(<?= json_encode($p) ?>)' title="แก้ไข">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePillar('<?= $p['id'] ?>', '<?= esc($p['short_title']) ?>')" title="ลบ">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">ชื่อเต็มประเด็นการพัฒนา</label>
                                        <div class="fw-bold text-secondary" style="font-size: 0.92rem; line-height: 1.4;"><?= esc($p['title']) ?></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">สาระสังเขป</label>
                                        <p class="text-secondary small mb-0" style="line-height: 1.5;"><?= esc($p['summary']) ?></p>
                                    </div>

                                    <div class="mb-3 flex-grow-1">
                                        <label class="form-label small fw-bold text-muted mb-1">กลยุทธ์ขับเคลื่อน</label>
                                        <ul class="small text-secondary ps-3 mb-0" style="line-height: 1.5;">
                                            <?php foreach ($p['strategies'] as $st): ?>
                                                <li class="mb-1"><?= esc($st) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                    <div class="p-3 rounded-3 bg-light border border-start border-4 border-warning mt-auto">
                                        <span class="badge bg-warning text-dark fw-bold mb-1" style="font-size: 0.7rem;">
                                            <i class="fa-solid fa-star me-1"></i> โครงการสำคัญ / เรือธง
                                        </span>
                                        <div class="small fw-bold text-dark" style="line-height: 1.4;"><?= esc($p['flagship']) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- TAB 4: ACTION PLANS & DOCUMENTS MANAGEMENT -->
    <!-- ======================================================== -->
    <div class="tab-pane fade" id="tab-docs" role="tabpanel">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-folder-open me-2 text-info"></i> รายการเอกสารแผนพัฒนาและแผนปฏิบัติราชการประจำปี</h6>
                    <small class="text-muted">อัปโหลดไฟล์ PDF จัดการหมวดหมู่ และกำหนดแผนแม่บทหลัก</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="max-width: 260px;">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" class="form-control" id="adminDocSearch" placeholder="ค้นหาชื่อเอกสาร..." oninput="filterAdminDocs()">
                    </div>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold flex-shrink-0" onclick="openDocModal()">
                        <i class="fa-solid fa-plus me-1"></i> เพิ่มเอกสารแผนพัฒนาฯ
                    </button>
                </div>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="adminDocsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="38%">ชื่อเอกสารแผนพัฒนา / แผนปฏิบัติราชการ</th>
                                <th width="18%">หมวดหมู่</th>
                                <th width="10%" class="text-center">ปีงบประมาณ</th>
                                <th width="10%" class="text-center">ยอดดาวน์โหลด</th>
                                <th width="19%" class="text-end pe-4">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $idx => $doc): ?>
                                <tr class="admin-doc-row" data-title="<?= esc(mb_strtolower($doc['title'])) ?>" data-category="<?= esc($doc['category']) ?>" data-year="<?= esc($doc['year']) ?>">
                                    <td class="text-center fw-bold text-muted"><?= $idx + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="p-2 rounded bg-danger bg-opacity-10 text-danger flex-shrink-0">
                                                <i class="fa-solid fa-file-pdf fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-6"><?= esc($doc['title']) ?></div>
                                                <small class="text-muted">ขนาด: <?= esc($doc['file_size']) ?> | <?= esc($doc['pages'] ?? '-') ?> หน้า</small>
                                                <?php if (!empty($doc['is_featured'])): ?>
                                                    <span class="badge bg-danger rounded-pill ms-1" style="font-size: 0.68rem;">แผนแม่บท</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-primary border"><?= esc($doc['category']) ?></span></td>
                                    <td class="text-center"><span class="badge bg-dark text-warning"><?= esc($doc['year']) ?></span></td>
                                    <td class="text-center fw-bold"><?= number_format($doc['downloads'] ?? 0) ?></td>
                                    <td class="text-end pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick='editDoc(<?= json_encode($doc) ?>)' title="แก้ไข">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDoc('<?= $doc['id'] ?>', '<?= esc($doc['title']) ?>')" title="ลบ">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ======================================================== -->
<!-- MODAL: ADD / EDIT STRATEGY DOCUMENT -->
<!-- ======================================================== -->
<div class="modal fade" id="strategyDocModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark" id="docModalTitle">เพิ่ม/แก้ไขเอกสารแผนยุทธศาสตร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="docForm" enctype="multipart/form-data">
                    <input type="hidden" id="docId" name="id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อเอกสารแผนพัฒนา / แผนปฏิบัติราชการ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="docTitle" name="title" required placeholder="เช่น แผนปฏิบัติราชการประจำปีของจังหวัดพัทลุง พ.ศ. 2569">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">หมวดหมู่เอกสาร</label>
                            <select class="form-select" id="docCategory" name="category">
                                <option value="แผนพัฒนาจังหวัด 5 ปี">แผนพัฒนาจังหวัด 5 ปี</option>
                                <option value="แผนปฏิบัติราชการประจำปี" selected>แผนปฏิบัติราชการประจำปี</option>
                                <option value="รายงานผลการดำเนินงาน (M&E)">รายงานผลการดำเนินงาน (M&E)</option>
                                <option value="แผนพัฒนาอำเภอและชุมชน">แผนพัฒนาอำเภอและชุมชน</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ปีงบประมาณ</label>
                            <input type="text" class="form-control" id="docYear" name="year" placeholder="2569">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">จำนวนหน้า</label>
                            <input type="number" class="form-control" id="docPages" name="pages" value="100">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">อัปโหลดไฟล์เอกสาร (PDF)</label>
                            <input type="file" class="form-control" id="docFile" name="doc_file" accept=".pdf">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">หรือระบุเป็น URL ไฟล์</label>
                            <input type="text" class="form-control" id="docFileUrl" name="file_url" placeholder="uploads/strategy/... หรือ https://...">
                        </div>
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="docFeatured" name="is_featured" value="1">
                        <label class="form-check-label fw-bold text-dark" for="docFeatured">ตั้งเป็นแผนแม่บทหลัก (Featured Plan)</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" id="btnSaveDoc" onclick="saveDoc()">
                    <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: ADD / EDIT KPI ITEM -->
<!-- ======================================================== -->
<div class="modal fade" id="kpiModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark" id="kpiModalTitle">เพิ่มตัวชี้วัดเป้าหมายใหม่</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="kpiItemForm">
                    <input type="hidden" id="kpiId" name="id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อตัวชี้วัดเป้าหมาย (KPI Title) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kpiTitle" name="title" required placeholder="เช่น การเติบโตทางเศรษฐกิจ (GPP) หรือ พื้นที่เกษตรอินทรีย์">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ค่าเป้าหมาย (Target Value) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kpiTarget" name="target" required placeholder="เช่น +4.5% หรือ 50,000 หรือ 95.00+">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ค่าปัจจุบัน / สถานะล่าสุด (Current Status)</label>
                            <input type="text" class="form-control" id="kpiCurrent" name="current" placeholder="เช่น +3.8% หรือ 38,500">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">หน่วยนับ (Unit)</label>
                            <input type="text" class="form-control" id="kpiUnit" name="unit" placeholder="เช่น ต่อปี, ไร่, ล้านบาท/ปี, คะแนน">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">โทนสีแสดงผล (Theme Color)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" id="kpiColorPicker" value="#2563eb" onchange="document.getElementById('kpiColor').value = this.value">
                                <input type="text" class="form-control" id="kpiColor" name="color" value="#2563eb">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ไอคอน (Font Awesome Class)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white" id="kpiIconPreview"><i class="fa-solid fa-chart-line text-primary"></i></span>
                                <input type="text" class="form-control" id="kpiIcon" name="icon" value="fa-solid fa-chart-line" oninput="updateKpiIconPreview(this.value)">
                            </div>
                        </div>
                    </div>

                    <!-- Quick Icon Selector -->
                    <div class="mb-3 p-2.5 rounded-3 bg-light border">
                        <span class="small fw-bold text-secondary d-block mb-1.5"><i class="fa-solid fa-icons me-1"></i> เลือกไอคอนสำเร็จรูปด่วน:</span>
                        <div class="d-flex flex-wrap gap-1.5">
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-chart-line')"><i class="fa-solid fa-chart-line text-primary me-1"></i> กราฟเติบโต</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-wheat-awn')"><i class="fa-solid fa-wheat-awn text-success me-1"></i> เกษตร/รวงข้าว</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-route')"><i class="fa-solid fa-route text-warning me-1"></i> ท่องเที่ยว/เส้นทาง</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-award')"><i class="fa-solid fa-award text-purple me-1" style="color: #7c3aed;"></i> คุณธรรม ITA</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-leaf')"><i class="fa-solid fa-leaf text-success me-1"></i> สิ่งแวดล้อม</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-water')"><i class="fa-solid fa-water text-info me-1"></i> ลุ่มน้ำ</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-people-roof')"><i class="fa-solid fa-people-roof text-primary me-1"></i> คุณภาพชีวิต</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-shield-halved')"><i class="fa-solid fa-shield-halved text-danger me-1"></i> ความมั่นคง</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectKpiIcon('fa-solid fa-laptop-code')"><i class="fa-solid fa-laptop-code text-indigo me-1"></i> ดิจิทัล</button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">คำอธิบายรายละเอียด / นิยามตัวชี้วัด</label>
                        <textarea class="form-control" id="kpiDesc" name="desc" rows="2" placeholder="เช่น อัตราการขยายตัวของผลิตภัณฑ์มวลรวมจังหวัดพัทลุง"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" id="btnSaveKpi" onclick="saveKpi()">
                    <i class="fa-solid fa-save me-1"></i> บันทึกตัวชี้วัด
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: ADD / EDIT PROVINCIAL DEVELOPMENT THEME -->
<!-- ======================================================== -->
<div class="modal fade" id="pillarModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark" id="pillarModalTitle">เพิ่มประเด็นการพัฒนาจังหวัด</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="pillarItemForm">
                    <input type="hidden" id="pillarId" name="id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">ลำดับประเด็น <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="pillarNumber" name="number" required value="1" min="1">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-bold">ชื่อย่อประเด็นการพัฒนา (Short Title) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pillarShortTitle" name="short_title" required placeholder="เช่น เกษตรมูลค่าสูง & อาหารปลอดภัย">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อเต็มประเด็นการพัฒนา (Full Title) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pillarTitle" name="title" required placeholder="เช่น การพัฒนาเกษตรมูลค่าสูง เกษตรอินทรีย์ และอุตสาหกรรมแปรรูป">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">สาระสังเขป (Summary)</label>
                        <textarea class="form-control" id="pillarSummary" name="summary" rows="2" placeholder="อธิบายภาพรวมและเป้าหมายของประเด็นการพัฒนานี้..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">กลยุทธ์ขับเคลื่อน (Strategies) <span class="text-muted small fw-normal">(พิมพ์ 1 บรรทัดต่อ 1 ข้อ)</span></label>
                        <textarea class="form-control" id="pillarStrategies" name="strategies" rows="4" placeholder="กลยุทธ์ที่ 1...&#10;กลยุทธ์ที่ 2...&#10;กลยุทธ์ที่ 3..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">โครงการสำคัญ / เรือธง (Flagship Project)</label>
                        <input type="text" class="form-control" id="pillarFlagship" name="flagship" placeholder="เช่น โครงการขับเคลื่อน Food Valley พัทลุง เมืองนวัตกรรมเกษตรปลอดภัย">
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">โทนสีประจำประเด็น (Theme Color)</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" class="form-control form-control-color" id="pillarColorPicker" value="#059669" onchange="document.getElementById('pillarColor').value = this.value">
                                <input type="text" class="form-control" id="pillarColor" name="color" value="#059669">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ไอคอน (Font Awesome Class)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white" id="pillarIconPreview"><i class="fa-solid fa-seedling text-success"></i></span>
                                <input type="text" class="form-control" id="pillarIcon" name="icon" value="fa-solid fa-seedling" oninput="updatePillarIconPreview(this.value)">
                            </div>
                        </div>
                    </div>

                    <!-- Quick Icon Selector -->
                    <div class="p-2.5 rounded-3 bg-light border">
                        <span class="small fw-bold text-secondary d-block mb-1.5"><i class="fa-solid fa-icons me-1"></i> เลือกไอคอนสำเร็จรูปด่วน:</span>
                        <div class="d-flex flex-wrap gap-1.5">
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectPillarIcon('fa-solid fa-seedling', '#059669')"><i class="fa-solid fa-seedling text-success me-1"></i> เกษตร/พืชผล</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectPillarIcon('fa-solid fa-mountain-sun', '#d97706')"><i class="fa-solid fa-mountain-sun text-warning me-1"></i> ท่องเที่ยว/มรดกโลก</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectPillarIcon('fa-solid fa-people-roof', '#2563eb')"><i class="fa-solid fa-people-roof text-primary me-1"></i> คุณภาพชีวิต/สังคม</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectPillarIcon('fa-solid fa-water', '#0284c7')"><i class="fa-solid fa-water text-info me-1"></i> สิ่งแวดล้อม/ลุ่มน้ำ</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectPillarIcon('fa-solid fa-laptop-code', '#7c3aed')"><i class="fa-solid fa-laptop-code text-purple me-1" style="color: #7c3aed;"></i> ภาครัฐดิจิทัล</button>
                            <button type="button" class="btn btn-sm btn-white border rounded-pill px-2.5 py-1" onclick="selectPillarIcon('fa-solid fa-shield-halved', '#dc2626')"><i class="fa-solid fa-shield-halved text-danger me-1"></i> ความมั่นคง</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" id="btnSavePillar" onclick="savePillar()">
                    <i class="fa-solid fa-save me-1"></i> บันทึกประเด็นการพัฒนา
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let docModalInstance = null;
let kpiModalInstance = null;
let pillarModalInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    const docModalEl = document.getElementById('strategyDocModal');
    if (docModalEl && typeof bootstrap !== 'undefined') {
        docModalInstance = new bootstrap.Modal(docModalEl);
    }
    const kpiModalEl = document.getElementById('kpiModal');
    if (kpiModalEl && typeof bootstrap !== 'undefined') {
        kpiModalInstance = new bootstrap.Modal(kpiModalEl);
    }
    const pillarModalEl = document.getElementById('pillarModal');
    if (pillarModalEl && typeof bootstrap !== 'undefined') {
        pillarModalInstance = new bootstrap.Modal(pillarModalEl);
    }
});

function openPillarModal() {
    document.getElementById('pillarId').value = '';
    document.getElementById('pillarItemForm').reset();
    document.getElementById('pillarNumber').value = <?= count($pillars ?? []) + 1 ?>;
    document.getElementById('pillarColor').value = '#059669';
    document.getElementById('pillarColorPicker').value = '#059669';
    document.getElementById('pillarIcon').value = 'fa-solid fa-seedling';
    updatePillarIconPreview('fa-solid fa-seedling');
    document.getElementById('pillarModalTitle').innerHTML = '<i class="fa-solid fa-plus-circle me-1 text-primary"></i> เพิ่มประเด็นการพัฒนาจังหวัด';
    pillarModalInstance.show();
}

function editPillar(p) {
    document.getElementById('pillarId').value = p.id || '';
    document.getElementById('pillarNumber').value = p.number || 1;
    document.getElementById('pillarShortTitle').value = p.short_title || '';
    document.getElementById('pillarTitle').value = p.title || '';
    document.getElementById('pillarSummary').value = p.summary || '';
    document.getElementById('pillarFlagship').value = p.flagship || '';
    
    if (Array.isArray(p.strategies)) {
        document.getElementById('pillarStrategies').value = p.strategies.join("\n");
    } else {
        document.getElementById('pillarStrategies').value = p.strategies || '';
    }

    const color = p.color || '#059669';
    document.getElementById('pillarColor').value = color;
    document.getElementById('pillarColorPicker').value = color;
    const icon = p.icon || 'fa-solid fa-seedling';
    document.getElementById('pillarIcon').value = icon;
    updatePillarIconPreview(icon);

    document.getElementById('pillarModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-1 text-warning"></i> แก้ไขประเด็นการพัฒนาที่ ' + (p.number || '');
    pillarModalInstance.show();
}

function selectPillarIcon(iconClass, color) {
    document.getElementById('pillarIcon').value = iconClass;
    updatePillarIconPreview(iconClass);
    if (color) {
        document.getElementById('pillarColor').value = color;
        document.getElementById('pillarColorPicker').value = color;
    }
}

function updatePillarIconPreview(iconClass) {
    const preview = document.getElementById('pillarIconPreview');
    if (preview) {
        preview.innerHTML = `<i class="${iconClass}"></i>`;
    }
}

async function savePillar() {
    const form = document.getElementById('pillarItemForm');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSavePillar');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    try {
        const res = await App.fetch('<?= base_url("admin/strategy/save-pillar") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('บันทึกประเด็นการพัฒนาสำเร็จ', 'success');
            pillarModalInstance.hide();
            setTimeout(() => location.reload(), 700);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกประเด็นการพัฒนา';
    }
}

async function deletePillar(id, title) {
    if (!confirm(`คุณต้องการลบประเด็นการพัฒนา "${title}" ใช่หรือไม่?`)) return;

    const formData = new FormData();
    formData.append('id', id);

    try {
        const res = await App.fetch('<?= base_url("admin/strategy/delete-pillar") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('ลบประเด็นการพัฒนาเรียบร้อยแล้ว', 'success');
            setTimeout(() => location.reload(), 600);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    }
}

function openKpiModal() {
    document.getElementById('kpiId').value = '';
    document.getElementById('kpiItemForm').reset();
    document.getElementById('kpiColor').value = '#2563eb';
    document.getElementById('kpiColorPicker').value = '#2563eb';
    document.getElementById('kpiIcon').value = 'fa-solid fa-chart-line';
    updateKpiIconPreview('fa-solid fa-chart-line');
    document.getElementById('kpiModalTitle').innerHTML = '<i class="fa-solid fa-plus-circle me-1 text-success"></i> เพิ่มตัวชี้วัดเป้าหมายใหม่';
    kpiModalInstance.show();
}

function editKpi(kpi) {
    document.getElementById('kpiId').value = kpi.id || '';
    document.getElementById('kpiTitle').value = kpi.title || '';
    document.getElementById('kpiTarget').value = kpi.target || '';
    document.getElementById('kpiCurrent').value = kpi.current || '';
    document.getElementById('kpiUnit').value = kpi.unit || '';
    const color = kpi.color || '#2563eb';
    document.getElementById('kpiColor').value = color;
    document.getElementById('kpiColorPicker').value = color;
    const icon = kpi.icon || 'fa-solid fa-chart-line';
    document.getElementById('kpiIcon').value = icon;
    updateKpiIconPreview(icon);
    document.getElementById('kpiDesc').value = kpi.desc || '';

    document.getElementById('kpiModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-1 text-primary"></i> แก้ไขตัวชี้วัดเป้าหมาย';
    kpiModalInstance.show();
}

function selectKpiIcon(iconClass) {
    document.getElementById('kpiIcon').value = iconClass;
    updateKpiIconPreview(iconClass);
}

function updateKpiIconPreview(iconClass) {
    const preview = document.getElementById('kpiIconPreview');
    if (preview) {
        preview.innerHTML = `<i class="${iconClass}"></i>`;
    }
}

async function saveKpi() {
    const form = document.getElementById('kpiItemForm');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSaveKpi');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    try {
        const res = await App.fetch('<?= base_url("admin/strategy/save-kpi") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('บันทึกตัวชี้วัดเรียบร้อยแล้ว', 'success');
            kpiModalInstance.hide();
            setTimeout(() => location.reload(), 700);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกตัวชี้วัด';
    }
}

async function deleteKpi(id, title) {
    if (!confirm(`คุณต้องการลบตัวชี้วัด "${title}" ใช่หรือไม่?`)) return;

    const formData = new FormData();
    formData.append('id', id);

    try {
        const res = await App.fetch('<?= base_url("admin/strategy/delete-kpi") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('ลบตัวชี้วัดเรียบร้อยแล้ว', 'success');
            setTimeout(() => location.reload(), 600);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    }
}

function openDocModal() {
    document.getElementById('docId').value = '';
    document.getElementById('docForm').reset();
    document.getElementById('docModalTitle').innerHTML = '<i class="fa-solid fa-plus-circle me-1 text-primary"></i> เพิ่มเอกสารแผนพัฒนาฯ';
    docModalInstance.show();
}

function editDoc(doc) {
    document.getElementById('docId').value = doc.id || '';
    document.getElementById('docTitle').value = doc.title || '';
    document.getElementById('docCategory').value = doc.category || 'แผนปฏิบัติราชการประจำปี';
    document.getElementById('docYear').value = doc.year || '';
    document.getElementById('docPages').value = doc.pages || 100;
    document.getElementById('docFileUrl').value = doc.file_url || '';
    document.getElementById('docFeatured').checked = !!doc.is_featured;

    document.getElementById('docModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-1 text-warning"></i> แก้ไขเอกสารแผนพัฒนาฯ';
    docModalInstance.show();
}

async function saveDoc() {
    const form = document.getElementById('docForm');
    const formData = new FormData(form);
    const btn = document.getElementById('btnSaveDoc');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    try {
        const res = await App.fetch('<?= base_url("admin/strategy/save-document") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('บันทึกเอกสารแผนยุทธศาสตร์สำเร็จ', 'success');
            docModalInstance.hide();
            setTimeout(() => location.reload(), 800);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล';
    }
}

async function deleteDoc(id, title) {
    if (!confirm(`คุณต้องการลบเอกสาร "${title}" ใช่หรือไม่?`)) return;

    const formData = new FormData();
    formData.append('id', id);

    try {
        const res = await App.fetch('<?= base_url("admin/strategy/delete-document") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast('ลบเอกสารเรียบร้อยแล้ว', 'success');
            setTimeout(() => location.reload(), 600);
        }
    } catch (e) {
        App.toast('เกิดข้อผิดพลาด: ' + e.message, 'error');
    }
}

function filterAdminDocs() {
    const q = (document.getElementById('adminDocSearch')?.value || '').trim().toLowerCase();
    const rows = document.querySelectorAll('.admin-doc-row');
    rows.forEach(row => {
        const title = row.getAttribute('data-title') || '';
        const cat = row.getAttribute('data-category') || '';
        const yr = row.getAttribute('data-year') || '';
        if (!q || title.includes(q) || cat.toLowerCase().includes(q) || yr.includes(q)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?= $this->endSection() ?>

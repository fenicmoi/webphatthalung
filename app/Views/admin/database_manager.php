<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
/** @var array $dbInfo */
$dbInfo = $dbInfo ?? [];
$tables = $tables ?? [];
$totalTables = $totalTables ?? count($tables);
$totalRows = $totalRows ?? 0;
$totalSizeMb = $totalSizeMb ?? 0;
$migrationData = $migrationData ?? ['total' => 0, 'executed' => 0, 'pending' => 0, 'list' => []];
$schemaChecks = $schemaChecks ?? [];
$jsonCaches = $jsonCaches ?? [];
$defaultSqlFile = $defaultSqlFile ?? ['exists' => false, 'path' => 'db/webphatthalung.sql', 'size' => 0, 'size_formatted' => '0 KB', 'mtime' => '-'];
$secretToken = $secretToken ?? '';
$directUpdateUrl = $directUpdateUrl ?? '';
?>

<!-- Header Title & Action Controls -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <div class="p-2 rounded-3 text-white d-inline-flex align-items-center justify-content-center shadow-sm" style="background: linear-gradient(135deg, #0284c7, #2563eb); width: 38px; height: 38px;">
                <i class="fa-solid fa-database fs-5"></i>
            </div>
            <span>จัดการโครงสร้างตาราง & ฐานข้อมูลบน Hosting (Database & Table Manager)</span>
        </h4>
        <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
            เครื่องมือรัน Migration ตรวจสอบฟิลด์ ซ่อมแซมคอลัมน์ที่ขาดหาย และซิงค์ตารางข้อมูลสำหรับสภาพแวดล้อม Hosting / Production
        </p>
    </div>
    <div class="d-flex flex-wrap align-items-center gap-2">
        <button type="button" class="btn btn-outline-warning rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-xs" onclick="DbManager.repairColumns()">
            <i class="fa-solid fa-wrench"></i>
            <span>ซ่อมแซมคอลัมน์ (Auto-Repair)</span>
        </button>
        <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none;" onclick="DbManager.runMigrations()" id="btnRunMigration">
            <i class="fa-solid fa-bolt"></i>
            <span>รัน Migration อัปเดตตาราง</span>
        </button>
    </div>
</div>

<!-- Quick Metric Status Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 p-md-4 rounded-4 h-100 position-relative overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small text-secondary fw-semibold">ฐานข้อมูลปัจจุบัน</span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">Online</span>
            </div>
            <h5 class="fw-bold text-dark mb-1 text-truncate" title="<?= esc($dbInfo['database']) ?>"><?= esc($dbInfo['database']) ?></h5>
            <div class="small text-muted text-truncate" style="font-size: 0.78rem;">
                <i class="fa-solid fa-server me-1"></i><?= esc($dbInfo['hostname']) ?> (<?= esc($dbInfo['driver']) ?>)
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 p-md-4 rounded-4 h-100 position-relative overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small text-secondary fw-semibold">จำนวนตารางทั้งหมด</span>
                <i class="fa-solid fa-table-cells text-primary"></i>
            </div>
            <h4 class="fw-bold text-primary mb-1"><?= number_format($totalTables) ?> <small class="text-muted fs-6 fw-normal">ตาราง</small></h4>
            <div class="small text-muted" style="font-size: 0.78rem;">
                รวมทั้งหมด <?= number_format($totalRows) ?> แถวข้อมูล
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 p-md-4 rounded-4 h-100 position-relative overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small text-secondary fw-semibold">ขนาดฐานข้อมูล (DB Size)</span>
                <i class="fa-solid fa-hard-drive text-info"></i>
            </div>
            <h4 class="fw-bold text-info mb-1"><?= number_format($totalSizeMb, 2) ?> <small class="text-muted fs-6 fw-normal">MB</small></h4>
            <div class="small text-muted" style="font-size: 0.78rem;">
                Charset: <?= esc($dbInfo['charset']) ?>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="glass-card p-3 p-md-4 rounded-4 h-100 position-relative overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small text-secondary fw-semibold">สถานะ Migrations</span>
                <?php if ($migrationData['pending'] === 0): ?>
                    <span class="badge bg-success rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">สมบูรณ์ล่าสุด</span>
                <?php else: ?>
                    <span class="badge bg-danger rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">รออัปเดต <?= $migrationData['pending'] ?></span>
                <?php endif; ?>
            </div>
            <h4 class="fw-bold <?= $migrationData['pending'] > 0 ? 'text-danger' : 'text-success' ?> mb-1">
                <?= $migrationData['executed'] ?> / <?= $migrationData['total'] ?>
            </h4>
            <div class="small text-muted" style="font-size: 0.78rem;">
                <?= $migrationData['pending'] === 0 ? 'โครงสร้างตรงตามโค้ดล่าสุด' : 'มี ' . $migrationData['pending'] . ' ไฟล์รอรัน' ?>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation Tabs -->
<div class="glass-card p-4 rounded-4 shadow-sm mb-4">
    <ul class="nav nav-pills gap-2 border-bottom pb-3 mb-4" id="dbTabs" role="tablist" style="border-color: rgba(0,0,0,0.06) !important;">
        <li class="nav-item" role="presentation">
            <button class="tab-pill active" id="tab-tables-btn" data-bs-toggle="pill" data-bs-target="#tab-tables" type="button" role="tab">
                <i class="fa-solid fa-table me-1.5"></i> ตารางทั้งหมด (<?= $totalTables ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="tab-pill" id="tab-migrations-btn" data-bs-toggle="pill" data-bs-target="#tab-migrations" type="button" role="tab">
                <i class="fa-solid fa-clock-rotate-left me-1.5"></i> รายการ Migrations (<?= $migrationData['total'] ?>)
                <?php if ($migrationData['pending'] > 0): ?>
                    <span class="badge bg-danger ms-1"><?= $migrationData['pending'] ?></span>
                <?php endif; ?>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="tab-pill" id="tab-health-btn" data-bs-toggle="pill" data-bs-target="#tab-health" type="button" role="tab">
                <i class="fa-solid fa-shield-heart me-1.5"></i> ตรวจสอบคอลัมน์สำคัญ (Schema Check)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="tab-pill" id="tab-sql-btn" data-bs-toggle="pill" data-bs-target="#tab-sql" type="button" role="tab">
                <i class="fa-solid fa-terminal text-primary me-1.5"></i> Web SQL Console
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="tab-pill" id="tab-import-export-btn" data-bs-toggle="pill" data-bs-target="#tab-import-export" type="button" role="tab">
                <i class="fa-solid fa-file-import text-info me-1.5"></i> นำเข้า / สำรอง (.SQL)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="tab-pill" id="tab-hosting-btn" data-bs-toggle="pill" data-bs-target="#tab-hosting" type="button" role="tab">
                <i class="fa-solid fa-cloud-arrow-up text-warning me-1.5"></i> ลิงก์อัปเดต Hosting (Direct URL)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="tab-pill" id="tab-sync-btn" data-bs-toggle="pill" data-bs-target="#tab-sync" type="button" role="tab">
                <i class="fa-solid fa-arrows-rotate text-success me-1.5"></i> ซิงค์ไฟล์ JSON แคช
            </button>
        </li>
    </ul>

    <div class="tab-content" id="dbTabsContent">
        
        <!-- Tab 1: Database Tables -->
        <div class="tab-pane fade show active" id="tab-tables" role="tabpanel">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div class="input-group" style="max-width: 320px;">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="filterTableInput" class="form-control bg-light border-start-0" placeholder="พิมพ์ค้นหาชื่อตาราง...">
                </div>
                <div class="small text-muted">
                    คลิกที่ปุ่ม <strong>"ดูคอลัมน์"</strong> เพื่อตรวจสอบฟิลด์และโครงสร้างของแต่ละตาราง
                </div>
            </div>

            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0" id="tablesListTable">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>ชื่อตาราง (Table Name)</th>
                            <th>Engine</th>
                            <th class="text-end">จำนวนแถว (Rows)</th>
                            <th class="text-end">ขนาด (Size)</th>
                            <th>Collation</th>
                            <th class="text-center" style="width: 140px;">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tables as $idx => $t): ?>
                            <tr class="table-row-item">
                                <td class="text-center text-muted small"><?= $idx + 1 ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-table text-primary opacity-75"></i>
                                        <span class="fw-bold text-dark table-name-txt"><?= esc($t['name']) ?></span>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border px-2 py-0.5"><?= esc($t['engine']) ?></span></td>
                                <td class="text-end fw-bold text-secondary"><?= number_format($t['rows']) ?></td>
                                <td class="text-end text-muted small"><?= number_format($t['size_kb'], 1) ?> KB</td>
                                <td class="text-muted small"><?= esc($t['collation']) ?></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" onclick="DbManager.inspectTable('<?= esc($t['name']) ?>')">
                                        <i class="fa-solid fa-eye me-1"></i>ดูคอลัมน์
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 2: Migrations List -->
        <div class="tab-pane fade" id="tab-migrations" role="tabpanel">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div>
                    <h6 class="fw-bold m-0 text-dark">รายการไฟล์ Migration ทั้งหมดในโฟลเดอร์ App/Database/Migrations</h6>
                    <small class="text-muted">ระบบจะเปรียบเทียบกับตาราง <code>migrations</code> ในฐานข้อมูลจริงเพื่อตรวจสอบความพร้อม</small>
                </div>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-semibold" onclick="DbManager.runMigrations()">
                    <i class="fa-solid fa-rotate me-1"></i> รัน Migration เดี๋ยวนี้
                </button>
            </div>

            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th style="width: 50px;" class="text-center">#</th>
                            <th>เวอร์ชัน (Timestamp)</th>
                            <th>ชื่อคลาส Migration</th>
                            <th>ชื่อไฟล์</th>
                            <th class="text-center">Batch</th>
                            <th>วันที่รันสำเร็จ</th>
                            <th class="text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($migrationData['list'] as $idx => $m): ?>
                            <tr>
                                <td class="text-center text-muted small"><?= $idx + 1 ?></td>
                                <td><code class="text-primary fw-bold"><?= esc($m['version']) ?></code></td>
                                <td class="fw-semibold text-dark"><?= esc($m['class']) ?></td>
                                <td class="text-muted small"><?= esc($m['filename']) ?></td>
                                <td class="text-center">
                                    <?php if ($m['batch']): ?>
                                        <span class="badge bg-light text-dark border">Batch <?= $m['batch'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted small"><?= $m['run_at'] ?: '-' ?></td>
                                <td class="text-center">
                                    <?php if ($m['is_executed']): ?>
                                        <span class="badge bg-success rounded-pill px-2.5 py-1">
                                            <i class="fa-solid fa-check me-1"></i> รันแล้ว
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-2.5 py-1">
                                            <i class="fa-solid fa-hourglass-start me-1"></i> รอรัน
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Schema & Column Health Check -->
        <div class="tab-pane fade" id="tab-health" role="tabpanel">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div>
                    <h6 class="fw-bold m-0 text-dark">ตรวจสอบโครงสร้างตารางหลักและคอลัมน์สำคัญ</h6>
                    <small class="text-muted">ป้องกันปัญหาฟังก์ชันเว็บไซต์ error เนื่องจากคอลัมน์ใน MySQL บนโฮสติ้งไม่ตรงกับซอร์สโค้ด</small>
                </div>
                <button type="button" class="btn btn-sm btn-warning rounded-pill px-3 py-1.5 fw-semibold text-dark" onclick="DbManager.repairColumns()">
                    <i class="fa-solid fa-wrench me-1"></i> ซ่อมแซมคอลัมน์ที่ขาดหาย
                </button>
            </div>

            <div class="row g-3">
                <?php foreach ($schemaChecks as $tbl => $chk): 
                    $isHealthy = ($chk['status'] === 'healthy');
                ?>
                    <div class="col-md-6">
                        <div class="p-3.5 rounded-3 border <?= $isHealthy ? 'bg-light' : 'bg-warning bg-opacity-10 border-warning' ?>">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-bold m-0 d-flex align-items-center gap-2">
                                    <i class="<?= $isHealthy ? 'fa-solid fa-circle-check text-success' : 'fa-solid fa-triangle-exclamation text-warning' ?>"></i>
                                    <span><?= esc($chk['title']) ?></span>
                                </h6>
                                <span class="badge <?= $isHealthy ? 'bg-success' : 'bg-warning text-dark' ?> rounded-pill">
                                    <?= esc($chk['status_text']) ?>
                                </span>
                            </div>
                            <?php if (!$isHealthy && !empty($chk['missing'])): ?>
                                <div class="mt-2 p-2 bg-white rounded border border-warning">
                                    <small class="text-danger fw-bold d-block mb-1">คอลัมน์ที่ขาดหายในตารางนี้:</small>
                                    <div class="d-flex flex-wrap gap-1">
                                        <?php foreach ($chk['missing'] as $miss): ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25" style="font-size: 0.78rem;">
                                                + <?= esc($miss) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <small class="text-muted d-block mt-1">คอลัมน์ครบถ้วนตามสเปกมาตรฐาน (<?= $chk['fields_count'] ?? 0 ?> คอลัมน์)</small>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tab 4: Direct Hosting Update URL -->
        <div class="tab-pane fade" id="tab-hosting" role="tabpanel">
            <div class="p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, rgba(2, 132, 199, 0.08), rgba(37, 99, 235, 0.12)); border: 1px solid rgba(2, 132, 199, 0.25);">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="p-3 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #0284c7, #2563eb) !important;">
                        <i class="fa-solid fa-cloud-arrow-up fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0 text-dark">Direct Hosting Table Updater (URL สำหรับอัปเดตบน Hosting)</h5>
                        <small class="text-muted">บนโฮสติ้งจริงที่ไม่มี SSH Terminal คุณสามารถคัดลอกลิงก์ด้านล่างไปเปิดบนเบราว์เซอร์เพื่อสั่งรัน Migration และซ่อมแซมตารางได้ทันที</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">URL ลิงก์อัปเดตตรง (Direct Secret URL):</label>
                    <div class="input-group">
                        <input type="text" id="directUpdateUrlInput" class="form-control bg-white fw-bold font-monospace" value="<?= esc($directUpdateUrl) ?>" readonly>
                        <button type="button" class="btn btn-outline-primary" onclick="DbManager.copyUrl()" title="คัดลอกลิงก์">
                            <i class="fa-solid fa-copy me-1"></i> คัดลอก
                        </button>
                        <a href="<?= esc($directUpdateUrl) ?>" target="_blank" class="btn btn-primary" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none;">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ทดสอบเปิด
                        </a>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="fa-solid fa-lock me-1 text-warning"></i> ลิงก์นี้มีการเข้ารหัส Secret Token เฉพาะเว็บของคุณ ปลอดภัยต่อการใช้งาน
                    </small>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <div class="small text-muted">
                        Secret Token ปัจจุบัน: <code class="fw-bold text-dark" id="txtSecretToken"><?= esc($secretToken) ?></code>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1" onclick="DbManager.resetToken()">
                        <i class="fa-solid fa-arrows-rotate me-1"></i> สุ่มเปลี่ยน Secret Token ใหม่
                    </button>
                </div>
            </div>

            <div class="card rounded-3 border p-3.5 bg-light">
                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-circle-info text-primary me-2"></i>วิธีใช้งานบน Shared Hosting / Production:</h6>
                <ol class="small text-secondary mb-0 ps-3" style="line-height: 1.8;">
                    <li>เมื่อคุณอัปโหลดไฟล์ซอร์สโค้ดใหม่ขึ้นโฮสติ้งผ่าน FTP หรือ cPanel File Manager</li>
                    <li>เพียงเปิดลิงก์ <code>Direct Secret URL</code> ด้านบนในเบราว์เซอร์ของคุณ 1 ครั้ง</li>
                    <li>ระบบจะรัน <strong>Migration ล่าสุด</strong>, <strong>ตรวจเช็คและซ่อมแซมคอลัมน์</strong>, และ <strong>ซิงค์ข้อมูล</strong> ให้อัตโนมัติโดยสมบูรณ์</li>
                    <li>หน้าต่างยืนยันจะแสดงผลสรุปว่าทุกขั้นตอนสำเร็จเรียบร้อย พร้อมใช้งานทันที</li>
                </ol>
            </div>
        </div>

        <!-- Tab 5: JSON Cache Sync -->
        <div class="tab-pane fade" id="tab-sync" role="tabpanel">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div>
                    <h6 class="fw-bold m-0 text-dark">ซิงค์ข้อมูลจากไฟล์ JSON แคชเข้าสู่ตารางฐานข้อมูล MySQL</h6>
                    <small class="text-muted">ใช้เมื่อต้องการนำเข้าข้อมูลเดิมที่บันทึกในไฟล์ JSON (เช่น ข่าวสาร, ภาพแกลลอรี, เอกสารแนบ) เข้าสู่ตาราง MySQL</small>
                </div>
                <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-semibold" onclick="DbManager.syncData('all')">
                    <i class="fa-solid fa-arrows-rotate me-1"></i> ซิงค์ข้อมูลทั้งหมดเดี๋ยวนี้
                </button>
            </div>

            <div class="table-responsive rounded-3 border">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted text-uppercase">
                            <th>ไฟล์แคชสำรอง</th>
                            <th>คำอธิบาย</th>
                            <th class="text-end">จำนวนรายการ</th>
                            <th class="text-end">ขนาดไฟล์</th>
                            <th>แก้ไขล่าสุด</th>
                            <th class="text-center">สั่งซิงค์</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jsonCaches as $jc): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-regular fa-file-code text-primary fs-5"></i>
                                        <span class="fw-bold text-dark font-monospace"><?= esc($jc['file']) ?></span>
                                    </div>
                                </td>
                                <td class="text-muted small"><?= esc($jc['description']) ?></td>
                                <td class="text-end fw-bold text-secondary"><?= number_format($jc['items_count']) ?> รายการ</td>
                                <td class="text-end text-muted small"><?= number_format($jc['size_kb'], 1) ?> KB</td>
                                <td class="text-muted small"><?= esc($jc['updated_at']) ?></td>
                                <td class="text-center">
                                    <?php 
                                    $targetType = 'all';
                                    if ($jc['file'] === 'site_news.json') $targetType = 'news';
                                    if ($jc['file'] === 'site_settings.json') $targetType = 'settings';
                                    ?>
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" onclick="DbManager.syncData('<?= $targetType ?>')">
                                        <i class="fa-solid fa-upload me-1"></i> ซิงค์เข้า MySQL
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 6: Web SQL Console -->
        <div class="tab-pane fade" id="tab-sql" role="tabpanel">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <div>
                    <h6 class="fw-bold m-0 text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-terminal text-primary"></i>
                        <span>Web SQL Console (เครื่องมือรันคำสั่ง SQL บนเว็บโดยตรง)</span>
                    </h6>
                    <small class="text-muted">ใช้ทดแทน phpMyAdmin เมื่อ DirectAdmin ไม่สามารถเปิดใช้งานได้ สามารถรันคำสั่ง SELECT, ALTER, UPDATE, CREATE TABLE หรือ CHECK/OPTIMIZE ได้ทันที</small>
                </div>
            </div>

            <!-- Quick Template Chips -->
            <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
                <span class="small text-secondary fw-semibold"><i class="fa-solid fa-wand-magic-sparkles me-1 text-warning"></i>คำสั่งสำเร็จรูป:</span>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 font-monospace" style="font-size: 0.8rem;" onclick="DbManager.setSql('SHOW TABLES;')">SHOW TABLES;</button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 font-monospace" style="font-size: 0.8rem;" onclick="DbManager.setSql('DESCRIBE news;')">DESCRIBE news;</button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 font-monospace" style="font-size: 0.8rem;" onclick="DbManager.setSql('SELECT id, title, views, cover_fit, is_event, created_at FROM news ORDER BY id DESC LIMIT 10;')">SELECT 10 ข่าวล่าสุด</button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 font-monospace" style="font-size: 0.8rem;" onclick="DbManager.setSql('SELECT id, username, email, role, status FROM users LIMIT 10;')">SELECT users</button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 font-monospace" style="font-size: 0.8rem;" onclick="DbManager.setSql('CHECK TABLE news, pages, users, settings;')">CHECK TABLE</button>
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 font-monospace" style="font-size: 0.8rem;" onclick="DbManager.setSql('OPTIMIZE TABLE news, pages, users, settings;')">OPTIMIZE TABLE</button>
            </div>

            <div class="card rounded-3 border mb-3 overflow-hidden shadow-xs">
                <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between py-2 px-3">
                    <span class="small font-monospace"><i class="fa-solid fa-code me-2 text-info"></i>SQL Query Input</span>
                    <span class="badge bg-secondary" style="font-size: 0.72rem;">MySQL <?= esc($dbInfo['version']) ?></span>
                </div>
                <div class="card-body p-0">
                    <textarea id="sqlQueryTextarea" class="form-control border-0 font-monospace p-3 text-dark" rows="7" style="font-size: 0.92rem; background: #fafafa; resize: vertical; border-radius: 0;" placeholder="พิมพ์คำสั่ง SQL ที่นี่ (คั่นหลายคำสั่งด้วยเซมิโคลอน ;) เช่น:&#10;SELECT * FROM news ORDER BY id DESC LIMIT 5;&#10;SHOW COLUMNS FROM pages;"></textarea>
                </div>
                <div class="card-footer bg-light d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 py-2 px-3">
                    <div class="small text-muted">
                        <i class="fa-solid fa-keyboard me-1"></i> กดปุ่ม <strong>"รันคำสั่ง SQL"</strong> หรือกด <code>Ctrl + Enter</code>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="DbManager.clearSql()">
                            <i class="fa-solid fa-eraser me-1"></i> ล้าง
                        </button>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4 fw-semibold shadow-xs" id="btnExecuteSql" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none;" onclick="DbManager.executeSql()">
                            <i class="fa-solid fa-play me-1.5"></i> รันคำสั่ง SQL (Execute)
                        </button>
                    </div>
                </div>
            </div>

            <!-- SQL Results Display Container -->
            <div id="sqlResultsContainer" style="display: none;" class="mt-4"></div>
        </div>

        <!-- Tab 7: Import / Export SQL -->
        <div class="tab-pane fade" id="tab-import-export" role="tabpanel">
            <div class="row g-4">
                <!-- Card 1: Import default db/webphatthalung.sql -->
                <div class="col-lg-6">
                    <div class="card h-100 rounded-4 border p-4 shadow-xs" style="background: #ffffff;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 rounded-circle text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fa-solid fa-database fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold m-0 text-dark">นำเข้าจากไฟล์หลักของระบบ (db/webphatthalung.sql)</h6>
                                <small class="text-muted">ไฟล์สำรองมาตรฐานของระบบพัทลุงบนเซิร์ฟเวอร์</small>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light border mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-secondary fw-semibold">สถานะไฟล์บนเซิร์ฟเวอร์:</span>
                                <?php if ($defaultSqlFile['exists']): ?>
                                    <span class="badge bg-success rounded-pill px-2 py-0.5">พร้อมใช้งาน (Found)</span>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill px-2 py-0.5">ไม่พบไฟล์</span>
                                <?php endif; ?>
                            </div>
                            <div class="small text-muted font-monospace mb-1">
                                <i class="fa-regular fa-file me-1"></i><?= esc($defaultSqlFile['path']) ?>
                            </div>
                            <div class="d-flex justify-content-between small text-muted">
                                <span>ขนาด: <strong><?= esc($defaultSqlFile['size_formatted']) ?></strong></span>
                                <span>แก้ไขล่าสุด: <?= esc($defaultSqlFile['mtime']) ?></span>
                            </div>
                        </div>

                        <p class="small text-secondary mb-3" style="line-height: 1.6;">
                            หากคุณเพิ่งตั้งค่าฐานข้อมูลใหม่บน Hosting หรือตารางเดิมเสียหาย สามารถกดปุ่มด้านล่างเพื่อนำเข้าโครงสร้างและข้อมูลตั้งต้นทั้งหมดได้ทันที โดยไม่ต้องผ่าน phpMyAdmin
                        </p>

                        <div class="mt-auto">
                            <button type="button" class="btn btn-success w-100 rounded-pill py-2.5 fw-semibold shadow-xs" id="btnImportDefaultSql" <?= !$defaultSqlFile['exists'] ? 'disabled' : '' ?> onclick="DbManager.importDefaultSql()">
                                <i class="fa-solid fa-file-import me-1.5"></i> นำเข้าฐานข้อมูลจาก db/webphatthalung.sql
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Upload custom .sql file -->
                <div class="col-lg-6">
                    <div class="card h-100 rounded-4 border p-4 shadow-xs" style="background: #ffffff;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 rounded-circle text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1, #4f46e5);">
                                <i class="fa-solid fa-cloud-arrow-up fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold m-0 text-dark">อัปโหลดและนำเข้าไฟล์ .SQL ใหม่ (Upload .SQL)</h6>
                                <small class="text-muted">นำเข้าไฟล์ SQL สำรองจากเครื่องคอมพิวเตอร์ของคุณ</small>
                            </div>
                        </div>

                        <form id="uploadSqlForm" onsubmit="DbManager.importCustomSql(event)">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-secondary">เลือกไฟล์ .sql ที่ต้องการนำเข้า:</label>
                                <input type="file" class="form-control" id="customSqlFile" name="sql_file" accept=".sql" required>
                                <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;">
                                    <i class="fa-solid fa-circle-info me-1 text-primary"></i> รองรับไฟล์ .sql (ระบบจะปิด Foreign Key Checks ชั่วคราวขณะนำเข้า)
                                </small>
                            </div>

                            <p class="small text-secondary mb-3" style="line-height: 1.6;">
                                คำสั่งในไฟล์จะถูกแยกและรันทีละคำสั่งเพื่อป้องกันปัญหา Timeout บนโฮสติ้งรุ่นเก่า
                            </p>

                            <div class="mt-auto">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-semibold shadow-xs" id="btnImportCustomSql" style="background: linear-gradient(135deg, #6366f1, #4f46e5); border: none;">
                                    <i class="fa-solid fa-upload me-1.5"></i> อัปโหลดและรันคำสั่งในไฟล์ SQL
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card 3: Export Full Database Backup -->
                <div class="col-lg-6">
                    <div class="card h-100 rounded-4 border p-4 shadow-xs" style="background: #ffffff;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 rounded-circle text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fa-solid fa-download fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold m-0 text-dark">ดาวน์โหลดไฟล์สำรองฐานข้อมูล (.SQL Backup)</h6>
                                <small class="text-muted">ส่งออกโครงสร้างตารางและข้อมูลทั้งหมดเป็นไฟล์ .sql</small>
                            </div>
                        </div>

                        <p class="small text-secondary mb-3" style="line-height: 1.6;">
                            ระบบจะรวบรวมคำสั่ง <code>CREATE TABLE</code> และ <code>INSERT INTO</code> ของตารางทั้งหมดในฐานข้อมูล <strong><?= esc($dbInfo['database']) ?></strong> (ทั้งหมด <?= count($tables) ?> ตาราง) ดาวน์โหลดเก็บไว้ในเครื่องของคุณทันที
                        </p>

                        <div class="mt-auto">
                            <a href="<?= base_url('admin/database-manager/export-sql') ?>" class="btn btn-warning w-100 rounded-pill py-2.5 fw-semibold text-white shadow-xs" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none;">
                                <i class="fa-solid fa-download me-1.5"></i> ดาวน์โหลดไฟล์สำรองฐานข้อมูล (.sql)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Optimize & Check All Tables -->
                <div class="col-lg-6">
                    <div class="card h-100 rounded-4 border p-4 shadow-xs" style="background: #ffffff;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="p-3 rounded-circle text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                                <i class="fa-solid fa-gauge-high fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold m-0 text-dark">เพิ่มประสิทธิภาพ & ตรวจสอบตาราง (Check & Optimize)</h6>
                                <small class="text-muted">จัดเรียงพื้นที่ดิสก์และดัชนี (Index) ของตารางทั้งหมด</small>
                            </div>
                        </div>

                        <p class="small text-secondary mb-3" style="line-height: 1.6;">
                            บนโฮสติ้งรุ่นเก่าที่ไม่มีการทำ Auto-maintenance ฟังก์ชันนี้จะส่งคำสั่ง <code>CHECK TABLE</code> และ <code>OPTIMIZE TABLE</code> ไปยังทุกตาราง ช่วยลดพื้นที่ดิสก์และเพิ่มความเร็วในการ Query
                        </p>

                        <div class="mt-auto">
                            <button type="button" class="btn btn-info text-white w-100 rounded-pill py-2.5 fw-semibold shadow-xs" style="background: linear-gradient(135deg, #0ea5e9, #0284c7); border: none;" id="btnOptimizeTables" onclick="DbManager.optimizeTables()">
                                <i class="fa-solid fa-wrench me-1.5"></i> สั่ง Check & Optimize ทุกตารางทันที
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Inspect Table Columns -->
<div class="modal fade" id="inspectTableModal" tabindex="-1" aria-labelledby="inspectTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2 text-dark" id="inspectTableModalLabel">
                    <i class="fa-solid fa-table-columns text-primary"></i>
                    <span>โครงสร้างตาราง: <span id="inspectModalTableName" class="text-primary font-monospace"></span></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="inspectLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted small mt-2">กำลังดึงโครงสร้างฟิลด์...</p>
                </div>
                <div class="table-responsive rounded-3 border" id="inspectTableWrapper" style="display: none;">
                    <table class="table table-sm table-striped align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-dark">
                            <tr>
                                <th>ฟิลด์ (Field)</th>
                                <th>ประเภท (Type)</th>
                                <th>Null</th>
                                <th>Key</th>
                                <th>Default</th>
                                <th>Extra</th>
                            </tr>
                        </thead>
                        <tbody id="inspectColumnsBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top px-4 py-2.5">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<script>
var DbManager = {
    baseUrl: '<?= base_url() ?>',

    init: function() {
        // Filter table search
        const filterInput = document.getElementById('filterTableInput');
        if (filterInput) {
            filterInput.addEventListener('keyup', function() {
                const q = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.table-row-item');
                rows.forEach(r => {
                    const name = r.querySelector('.table-name-txt')?.textContent.toLowerCase() || '';
                    if (name.includes(q)) {
                        r.style.display = '';
                    } else {
                        r.style.display = 'none';
                    }
                });
            });
        }

        // Ctrl + Enter shortcut for SQL Console
        const sqlTextarea = document.getElementById('sqlQueryTextarea');
        if (sqlTextarea) {
            sqlTextarea.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    e.preventDefault();
                    DbManager.executeSql();
                }
            });
        }
    },

    runMigrations: function() {
        if (!confirm('คุณต้องการสั่งรัน Migration เพื่ออัปเดตโครงสร้างตารางทั้งหมดใช่หรือไม่?')) return;
        
        App.toast('กำลังตรวจสอบและรัน Migration...', 'info');
        const btn = document.getElementById('btnRunMigration');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> กำลังอัปเดตตาราง...';
        }

        fetch(this.baseUrl + '/admin/database-manager/migrate', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-bolt me-2"></i> รัน Migration อัปเดตตาราง';
            }
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาดในการรัน Migration', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-bolt me-2"></i> รัน Migration อัปเดตตาราง';
            }
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ผิดพลาด', 'error');
        });
    },

    repairColumns: function() {
        App.toast('กำลังตรวจสอบและซ่อมแซมคอลัมน์...', 'info');

        fetch(this.baseUrl + '/admin/database-manager/repair-columns', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' || data.status === 'warning') {
                App.toast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาดในการซ่อมแซม', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ผิดพลาด', 'error');
        });
    },

    syncData: function(target) {
        if (!confirm('ต้องการนำเข้าข้อมูลจากไฟล์ JSON แคชเข้าสู่ตารางฐานข้อมูล MySQL ใช่หรือไม่?')) return;
        App.toast('กำลังซิงค์ข้อมูล...', 'info');

        const formData = new FormData();
        formData.append('target', target);

        fetch(this.baseUrl + '/admin/database-manager/sync-data', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาดในการซิงค์', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ผิดพลาด', 'error');
        });
    },

    inspectTable: function(tableName) {
        const modalEl = document.getElementById('inspectTableModal');
        const modalTitle = document.getElementById('inspectModalTableName');
        const loading = document.getElementById('inspectLoading');
        const wrapper = document.getElementById('inspectTableWrapper');
        const tbody = document.getElementById('inspectColumnsBody');

        modalTitle.textContent = tableName;
        loading.style.display = 'block';
        wrapper.style.display = 'none';
        tbody.innerHTML = '';

        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        fetch(this.baseUrl + '/admin/database-manager/inspect-table/' + tableName)
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';
                if (data.status === 'success' && data.columns) {
                    wrapper.style.display = 'block';
                    data.columns.forEach(col => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td><strong class="text-primary font-monospace">${col.Field}</strong></td>
                            <td><span class="badge bg-light text-dark border font-monospace">${col.Type}</span></td>
                            <td>${col.Null === 'YES' ? '<span class="text-muted">YES</span>' : '<strong class="text-danger">NO</strong>'}</td>
                            <td>${col.Key ? '<span class="badge bg-warning text-dark">' + col.Key + '</span>' : '-'}</td>
                            <td><code>${col.Default !== null ? col.Default : 'NULL'}</code></td>
                            <td><small class="text-secondary">${col.Extra || '-'}</small></td>
                        `;
                        tbody.appendChild(tr);
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-3">${data.message || 'ไม่สามารถดึงข้อมูลได้'}</td></tr>`;
                    wrapper.style.display = 'block';
                }
            })
            .catch(err => {
                console.error(err);
                loading.style.display = 'none';
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-3">เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>`;
                wrapper.style.display = 'block';
            });
    },

    copyUrl: function() {
        const input = document.getElementById('directUpdateUrlInput');
        if (!input) return;
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value)
            .then(() => App.toast('คัดลอก URL เรียบร้อยแล้ว', 'success'))
            .catch(() => App.toast('ไม่สามารถคัดลอกได้ กรุณา Copy ด้วยตนเอง', 'info'));
    },

    resetToken: function() {
        if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการสุ่ม Secret Token ใหม่? (ลิงก์เดิมที่เคยตั้งไว้จะใช้งานไม่ได้)')) return;

        fetch(this.baseUrl + '/admin/database-manager/reset-token', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('directUpdateUrlInput').value = data.url;
                document.getElementById('txtSecretToken').textContent = data.token;
                App.toast('สร้าง Secret Token ใหม่เรียบร้อย', 'success');
            }
        })
        .catch(err => console.error(err));
    },

    setSql: function(query) {
        const textarea = document.getElementById('sqlQueryTextarea');
        if (textarea) {
            textarea.value = query;
            textarea.focus();
        }
    },

    clearSql: function() {
        const textarea = document.getElementById('sqlQueryTextarea');
        if (textarea) {
            textarea.value = '';
            textarea.focus();
        }
        const res = document.getElementById('sqlResultsContainer');
        if (res) res.style.display = 'none';
    },

    executeSql: function() {
        const textarea = document.getElementById('sqlQueryTextarea');
        const sql = textarea ? textarea.value.trim() : '';
        if (!sql) {
            App.toast('กรุณากรอกคำสั่ง SQL', 'warning');
            return;
        }

        const btn = document.getElementById('btnExecuteSql');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> กำลังรัน SQL...';
        }

        const resContainer = document.getElementById('sqlResultsContainer');
        if (resContainer) {
            resContainer.style.display = 'block';
            resContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="text-muted small mt-2">กำลังประมวลผลคำสั่ง SQL...</p></div>';
        }

        const formData = new FormData();
        formData.append('sql', sql);

        fetch(this.baseUrl + '/admin/database-manager/execute-sql', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-play me-1.5"></i> รันคำสั่ง SQL (Execute)';
            }
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                this.renderSqlResults(data.results);
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาด SQL', 'error');
                if (resContainer) {
                    resContainer.innerHTML = `
                        <div class="alert alert-danger rounded-3 shadow-xs mb-0">
                            <h6 class="fw-bold"><i class="fa-solid fa-circle-exclamation me-2"></i>เกิดข้อผิดพลาดในการรัน SQL</h6>
                            <pre class="mb-0 small text-danger font-monospace" style="white-space: pre-wrap;">${data.message || 'Error executing query'}</pre>
                        </div>
                    `;
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-play me-1.5"></i> รันคำสั่ง SQL (Execute)';
            }
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ผิดพลาด', 'error');
        });
    },

    renderSqlResults: function(results) {
        const container = document.getElementById('sqlResultsContainer');
        if (!container || !Array.isArray(results)) return;

        let html = '';
        results.forEach((item, idx) => {
            html += `<div class="card rounded-3 border mb-3 overflow-hidden shadow-xs">`;
            html += `
                <div class="card-header bg-light d-flex align-items-center justify-content-between py-2 px-3">
                    <span class="small font-monospace text-truncate" style="max-width: 70%;"><strong>#${idx + 1}</strong>: ${item.query}</span>
                    <span class="badge bg-secondary font-monospace" style="font-size: 0.75rem;">${item.duration}</span>
                </div>
            `;

            if (item.type === 'select') {
                html += `<div class="card-body p-0">`;
                if (item.columns && item.columns.length > 0 && item.rows && item.rows.length > 0) {
                    html += `<div class="table-responsive" style="max-height: 380px;">`;
                    html += `<table class="table table-sm table-striped table-hover align-middle mb-0 font-monospace" style="font-size: 0.84rem;">`;
                    html += `<thead class="table-dark sticky-top"><tr>`;
                    item.columns.forEach(col => {
                        html += `<th>${col}</th>`;
                    });
                    html += `</tr></thead><tbody>`;
                    item.rows.forEach(r => {
                        html += `<tr>`;
                        item.columns.forEach(col => {
                            const val = r[col];
                            if (val === null) {
                                html += `<td><em class="text-muted">NULL</em></td>`;
                            } else {
                                html += `<td class="text-truncate" style="max-width: 260px;" title="${String(val)}">${String(val)}</td>`;
                            }
                        });
                        html += `</tr>`;
                    });
                    html += `</tbody></table></div>`;
                } else {
                    html += `<div class="p-3 text-muted text-center small">ไม่มีข้อมูลแถวผลลัพธ์ (Empty set)</div>`;
                }
                html += `</div>`;
                html += `<div class="card-footer bg-white small text-muted py-1.5 px-3">พบข้อมูลทั้งหมด ${item.total} แถว (แสดงไม่เกิน ${item.rows.length} แถว)</div>`;
            } else {
                html += `
                    <div class="card-body py-3 px-3">
                        <div class="d-flex align-items-center gap-2 text-success">
                            <i class="fa-solid fa-circle-check fs-5"></i>
                            <span class="fw-semibold">ประมวลผลสำเร็จ มีแถวข้อมูลที่ได้รับผลกระทบ: <strong>${item.affected ?? 0}</strong> แถว</span>
                        </div>
                    </div>
                `;
            }
            html += `</div>`;
        });

        container.innerHTML = html;
    },

    importDefaultSql: function() {
        if (!confirm('ยืนยันนำเข้าฐานข้อมูลจาก db/webphatthalung.sql เข้าสู่ MySQL ใช่หรือไม่?\n(ระบบจะอัปเดตตารางและข้อมูลตั้งต้นทั้งหมด)')) return;

        const btn = document.getElementById('btnImportDefaultSql');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> กำลังนำเข้าฐานข้อมูล... (โปรดรอสักครู่)';
        }
        App.toast('กำลังประมวลผลนำเข้าไฟล์ฐานข้อมูล...', 'info');

        const formData = new FormData();
        formData.append('source', 'default_file');

        fetch(this.baseUrl + '/admin/database-manager/import-sql', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-file-import me-1.5"></i> นำเข้าฐานข้อมูลจาก db/webphatthalung.sql';
            }
            if (data.status === 'success' || data.status === 'warning') {
                App.toast(data.message, 'success');
                setTimeout(() => location.reload(), 1800);
            } else {
                App.toast(data.message || 'การนำเข้าล้มเหลว', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-file-import me-1.5"></i> นำเข้าฐานข้อมูลจาก db/webphatthalung.sql';
            }
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ผิดพลาด', 'error');
        });
    },

    importCustomSql: function(e) {
        e.preventDefault();
        const fileInput = document.getElementById('customSqlFile');
        if (!fileInput || !fileInput.files.length) {
            App.toast('กรุณาเลือกไฟล์ .sql', 'warning');
            return;
        }

        if (!confirm('ยืนยันอัปโหลดและรันคำสั่งจากไฟล์ SQL ที่เลือกใช่หรือไม่?')) return;

        const btn = document.getElementById('btnImportCustomSql');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> กำลังอัปโหลดและรันคำสั่ง SQL...';
        }
        App.toast('กำลังอัปโหลดและประมวลผลไฟล์ SQL...', 'info');

        const form = document.getElementById('uploadSqlForm');
        const formData = new FormData(form);
        formData.append('source', 'upload');

        fetch(this.baseUrl + '/admin/database-manager/import-sql', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-upload me-1.5"></i> อัปโหลดและรันคำสั่งในไฟล์ SQL';
            }
            if (data.status === 'success' || data.status === 'warning') {
                App.toast(data.message, 'success');
                setTimeout(() => location.reload(), 1800);
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาดในการนำเข้า', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-upload me-1.5"></i> อัปโหลดและรันคำสั่งในไฟล์ SQL';
            }
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ผิดพลาด', 'error');
        });
    },

    optimizeTables: function() {
        if (!confirm('ต้องการสั่ง CHECK TABLE และ OPTIMIZE TABLE ทุกตารางในฐานข้อมูลใช่หรือไม่?')) return;

        const btn = document.getElementById('btnOptimizeTables');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> กำลัง Optimize ทุกตาราง...';
        }
        App.toast('กำลังสั่ง Check และ Optimize ตาราง...', 'info');

        fetch(this.baseUrl + '/admin/database-manager/optimize-tables', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-wrench me-1.5"></i> สั่ง Check & Optimize ทุกตารางทันที';
            }
            if (data.status === 'success') {
                App.toast(data.message, 'success');
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาด', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-wrench me-1.5"></i> สั่ง Check & Optimize ทุกตารางทันที';
            }
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ผิดพลาด', 'error');
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    DbManager.init();
});
</script>

<style>
.tab-pill {
    background: transparent;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 9999px;
    padding: 8px 18px;
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--text-secondary, #64748b);
    transition: all 0.2s ease;
}
.tab-pill:hover {
    background: rgba(2, 132, 199, 0.06);
    color: #0284c7;
    border-color: rgba(2, 132, 199, 0.25);
}
.tab-pill.active {
    background: linear-gradient(135deg, #0284c7, #2563eb) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}
</style>

<?= $this->endSection() ?>

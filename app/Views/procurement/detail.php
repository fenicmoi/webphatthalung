<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container my-5 pt-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.95rem;">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-primary text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('news?category=' . urlencode('ประกาศจัดซื้อจัดจ้าง (e-GP)')) ?>" class="text-primary text-decoration-none">ประกาศจัดซื้อจัดจ้าง (e-GP)</a></li>
            <li class="breadcrumb-item active text-muted" aria-current="page"><?= esc($project['project_id'] ?? 'โครงการ') ?></li>
        </ol>
    </nav>

    <!-- Main Project Detail Sheet -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
        
        <!-- Header Banner -->
        <div class="p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-gavel me-1"></i> ระบบจัดซื้อจัดจ้างภาครัฐ (e-GP) กรมบัญชีกลาง
                </span>
                <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-location-dot me-1"></i> จังหวัดพัทลุง
                </span>
            </div>

            <h3 class="fw-bold text-white mb-3" style="line-height: 1.5;">
                <?= esc($project['project_name'] ?? $project['title'] ?? 'โครงการจัดซื้อจัดจ้าง') ?>
            </h3>

            <div class="d-flex flex-wrap align-items-center gap-4 text-light opacity-90 small">
                <span><i class="fa-solid fa-hashtag me-1 text-warning"></i> <strong>เลขที่โครงการ :</strong> <?= esc($project['project_id'] ?? '-') ?></span>
                <span><i class="fa-regular fa-calendar me-1 text-info"></i> <strong>วันที่ลงประกาศ :</strong> <?= esc($project['date'] ?? '-') ?></span>
                <span><i class="fa-solid fa-building-columns me-1 text-warning"></i> <strong>หน่วยงาน :</strong> <?= esc($project['dept_name'] ?? '-') ?></span>
            </div>
        </div>

        <!-- Body: Structured Official Data Table -->
        <div class="card-body p-4 p-md-5">
            <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom d-flex align-items-center gap-2">
                <i class="fa-solid fa-file-contract"></i>
                <span>รายละเอียดข้อมูลโครงการและงบประมาณ</span>
            </h5>

            <div class="table-responsive mb-5">
                <table class="table table-bordered align-middle m-0" style="font-size: 0.95rem;">
                    <tbody>
                        <tr>
                            <td class="bg-light fw-bold text-secondary" style="width: 220px;">เลขที่โครงการ e-GP</td>
                            <td class="fw-bold text-primary fs-6">
                                <code class="fs-6 fw-bold"><?= esc($project['project_id'] ?? '-') ?></code>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">ชื่อโครงการจัดซื้อจัดจ้าง</td>
                            <td class="fw-semibold text-dark">
                                <?= esc($project['project_name'] ?? $project['title'] ?? '-') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">หน่วยงานเจ้าของโครงการ</td>
                            <td class="text-dark">
                                <?= esc($project['dept_name'] ?? '-') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">หน่วยจัดซื้อ</td>
                            <td class="text-dark">
                                <?= esc($project['procure_unit'] ?? $project['dept_name'] ?? '-') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">วงเงินงบประมาณ (บาท)</td>
                            <td class="fw-bold text-success fs-5">
                                <?= number_format((float)($project['budget'] ?? 0), 2) ?> <small class="text-muted fw-normal fs-6">บาท</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">วิธีการจัดซื้อจัดจ้าง</td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.85rem;">
                                    <?= esc($project['method'] ?? 'เฉพาะเจาะจง') ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">สถานะโครงการ</td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.85rem;">
                                    <?= esc($project['status'] ?? 'ดำเนินการตามขั้นตอน') ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">วันที่ลงประกาศ / ดำเนินการ</td>
                            <td class="text-dark">
                                <?= esc($project['date'] ?? '-') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-light fw-bold text-secondary">แหล่งอ้างอิงข้อมูล</td>
                            <td class="text-muted">
                                ระบบการจัดซื้อจัดจ้างภาครัฐ (e-GP) กรมบัญชีกลาง กระทรวงการคลัง
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-4 border-top">
                <a href="<?= base_url('news?category=' . urlencode('ประกาศจัดซื้อจัดจ้าง (e-GP)')) ?>" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> ย้อนกลับไปรายการจัดซื้อจัดจ้าง
                </a>

                <div class="d-flex gap-2">
                    <button type="button" onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-print"></i> พิมพ์เอกสาร
                    </button>
                    <a href="https://www.gprocurement.go.th" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> เปิดระบบ e-GP กรมบัญชีกลาง
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

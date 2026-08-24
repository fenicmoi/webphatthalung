<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    $executive = $executive ?? [];
    $isOfficer = session()->get('isLoggedIn');

    $eduLines = !empty($executive['education']) ? preg_split('/\r\n|\r|\n/', trim($executive['education'])) : [];
    $trainingLines = !empty($executive['training']) ? preg_split('/\r\n|\r|\n/', trim($executive['training'])) : [];
    $historyLines = !empty($executive['history']) ? preg_split('/\r\n|\r|\n/', trim($executive['history'])) : [];
?>

<style>
/* ==========================================================================
   EXECUTIVE BIOGRAPHY & PRINT-TO-PDF STYLES
   ========================================================================== */
.exec-detail-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 60%, #0369a1 100%);
    padding: 50px 0 35px;
    color: #ffffff;
    border-bottom: 3px solid rgba(212, 175, 55, 0.5);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.exec-detail-card {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.exec-detail-frame {
    width: 220px;
    height: 220px;
    border-radius: 50%;
    padding: 7px;
    background: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
    box-shadow: 0 12px 30px rgba(180, 83, 9, 0.25);
    margin: 0 auto 20px;
}
.exec-detail-frame-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    background: #ffffff;
    border: 4px solid #ffffff;
}
.exec-detail-frame-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
}

.exec-quote-banner {
    background: linear-gradient(135deg, rgba(217, 119, 6, 0.06), rgba(2, 132, 199, 0.06));
    border-left: 5px solid #d97706;
    border-radius: 0 16px 16px 0;
    padding: 20px 24px;
    font-size: 1.1rem;
    line-height: 1.8;
    color: #1e293b;
}

.timeline-bio-item {
    position: relative;
    padding-left: 32px;
    padding-bottom: 20px;
}
.timeline-bio-item::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 6px;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}
.timeline-bio-item:last-child::before {
    display: none;
}
.timeline-bio-dot {
    position: absolute;
    left: 0;
    top: 4px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #ffffff;
    border: 4px solid #d97706;
}

/* Official Print Sheet Elements (Visible only on print) */
.print-only-header {
    display: none;
}

/* ==========================================================================
   PRINT MEDIA STYLES (FOR BROWSER PRINT & SAVE AS PDF)
   ========================================================================== */
@media print {
    /* Hide non-printable web UI elements */
    .exec-detail-header,
    .navbar,
    .gov-top-bar,
    .site-footer,
    .btn-action-bar,
    .breadcrumb,
    #backToTop,
    .nora-ai-fab,
    .nora-chat-window {
        display: none !important;
    }

    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 14pt !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .exec-detail-card {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    .print-only-header {
        display: block !important;
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #000;
    }

    .print-garuda {
        width: 70px;
        height: auto;
        margin-bottom: 10px;
    }

    .exec-detail-frame {
        width: 160px !important;
        height: 160px !important;
        box-shadow: none !important;
        border: 2px solid #333 !important;
        background: transparent !important;
        padding: 2px !important;
    }
    .exec-detail-frame-inner {
        border: none !important;
    }

    .exec-quote-banner {
        border-left: 3px solid #333 !important;
        background: #f8fafc !important;
    }

    .page-break-avoid {
        page-break-inside: avoid;
    }
}

/* Dark mode */
[data-theme="dark"] .exec-detail-card {
    background: #1e293b;
    border-color: rgba(255,255,255,0.1);
}
[data-theme="dark"] .exec-quote-banner {
    color: #e2e8f0;
    background: rgba(255,255,255,0.03);
}
</style>

<!-- HEADER BANNER -->
<header class="exec-detail-header">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 text-white-50">
                        <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white-50 text-decoration-none">หน้าหลัก</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('executives') ?>" class="text-white-50 text-decoration-none">คณะผู้บริหาร</a></li>
                        <li class="breadcrumb-item active text-warning" aria-current="page"><?= esc($executive['name']) ?></li>
                    </ol>
                </nav>
                <h1 class="h2 fw-bold text-white mb-1">ประวัติการรับราชการและวิสัยทัศน์</h1>
                <p class="mb-0 text-light opacity-75">ข้อมูลประวัติบุคคลทางการบริหาร ข้อมูลการติดต่อ และเส้นทางการทำงาน</p>
            </div>
            
            <!-- Action Toolbar -->
            <div class="btn-action-bar d-flex flex-wrap gap-2">
                <button type="button" onclick="window.print()" class="btn btn-warning fw-bold text-dark rounded-pill px-4 shadow-sm d-inline-flex align-items-center gap-2 hover-scale">
                    <i class="fa-solid fa-print"></i>
                    <span>พิมพ์ / บันทึกเป็น PDF</span>
                </button>
                <a href="<?= base_url('governors') ?>" class="btn btn-outline-warning rounded-pill px-3 d-inline-flex align-items-center gap-2" title="ไปยังทำเนียบเจ้าเมืองและอดีตผู้ว่าราชการจังหวัด">
                    <i class="fa-solid fa-landmark"></i>
                    <span>ทำเนียบอดีตผู้ว่าฯ</span>
                </a>
                <a href="<?= base_url('executives') ?>" class="btn btn-outline-light rounded-pill px-3 d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>กลับทำเนียบ</span>
                </a>
                <?php if ($isOfficer): ?>
                <button type="button" onclick="ExecutiveStudio.open('<?= $executive['id'] ?>')" class="btn btn-info text-dark fw-bold rounded-pill px-3 d-inline-flex align-items-center gap-1">
                    <i class="fa-solid fa-pen-to-square"></i> แก้ไขข้อมูล
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main class="container my-4 my-md-5">
    <!-- Printable Formal Header (Active only when printing / PDF saving) -->
    <div class="print-only-header">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Garuda_Emblem_of_Thailand.svg/200px-Garuda_Emblem_of_Thailand.svg.png" alt="ตราครุฑ" class="print-garuda">
        <h3 class="fw-bold mb-1" style="font-size: 18pt;">ประวัติและทำเนียบผู้บริหารจังหวัดพัทลุง</h3>
        <p class="mb-0 text-muted" style="font-size: 12pt;">ศูนย์ข้อมูลข่าวสาร ศาลากลางจังหวัดพัทลุง กระทรวงมหาดไทย</p>
    </div>

    <div class="exec-detail-card p-4 p-md-5">
        <div class="row g-4 g-lg-5">
            <!-- Left Column: Portrait & Contact Card -->
            <div class="col-lg-4 text-center">
                <?php 
                    $detailPhoto = !empty($executive['photo']) ? (strpos($executive['photo'], 'http') === 0 ? esc($executive['photo']) : base_url($executive['photo'])) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop';
                ?>
                <div class="exec-detail-frame">
                    <div class="exec-detail-frame-inner">
                        <img src="<?= $detailPhoto ?>" alt="<?= esc($executive['name']) ?>">
                    </div>
                </div>

                <div class="mb-3 d-print-none">
                    <a href="<?= $detailPhoto ?>" download="<?= esc($executive['name']) ?>.jpg" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-download"></i>
                        <span>ดาวน์โหลดรูปภาพทางการ</span>
                    </a>
                </div>

                <h3 class="fw-bold mb-1" style="color: #92400e; font-size: 1.45rem;"><?= esc($executive['name']) ?></h3>
                <div class="fw-semibold text-primary mb-3" style="font-size: 1.1rem;"><?= esc($executive['position']) ?></div>
                
                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2 rounded-pill mb-4">
                    <?= esc($executive['category'] ?? 'คณะผู้บริหารระดับสูง') ?>
                </span>

                <!-- Contact Box -->
                <div class="card border-0 rounded-4 p-3 text-start bg-light mb-4 page-break-avoid">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-address-book text-primary me-2"></i>ข้อมูลติดต่อราชการ</h6>
                    <div class="d-flex flex-column gap-2 small">
                        <?php if (!empty($executive['phone'])): ?>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-phone text-success fs-6" style="width: 20px;"></i>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">เบอร์โทรศัพท์สายตรง</span>
                                    <strong class="text-dark"><?= esc($executive['phone']) ?></strong>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($executive['email'])): ?>
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-envelope text-danger fs-6" style="width: 20px;"></i>
                                <div>
                                    <span class="text-muted d-block" style="font-size: 0.75rem;">อีเมลติดต่อ</span>
                                    <strong class="text-dark"><?= esc($executive['email']) ?></strong>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-building text-primary fs-6" style="width: 20px;"></i>
                            <div>
                                <span class="text-muted d-block" style="font-size: 0.75rem;">สถานที่ปฏิบัติงาน</span>
                                <span class="text-dark">ศาลากลางจังหวัดพัทลุง ต.คูหาสวรรค์ อ.เมือง จ.พัทลุง 93000</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Attachment Download Card -->
                <?php if (!empty($executive['document_file'])): 
                    $docHref = (strpos($executive['document_file'], 'http') === 0) ? esc($executive['document_file']) : base_url($executive['document_file']);
                    $docTitle = !empty($executive['document_name']) ? esc($executive['document_name']) : 'เอกสารประวัติฉบับทางการ (PDF)';
                ?>
                <div class="card border-0 rounded-4 p-3 text-start mb-4 shadow-sm d-print-none" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.06), rgba(245, 158, 11, 0.08)); border: 1px solid rgba(239, 68, 68, 0.2) !important;">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div style="width: 42px; height: 42px; border-radius: 12px; background: #ef4444; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.95rem;"><?= $docTitle ?></h6>
                            <span class="text-muted small">ไฟล์เอกสารแนบประจำตำแหน่ง</span>
                        </div>
                    </div>
                    <a href="<?= $docHref ?>" target="_blank" download class="btn btn-danger w-100 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 mt-2 hover-scale">
                        <i class="fa-solid fa-file-arrow-down"></i>
                        <span>ดาวน์โหลดเอกสารแนบ</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Vision, Education & Career History -->
            <div class="col-lg-8">
                <!-- Vision / Quote -->
                <?php if (!empty($executive['quote'])): ?>
                    <div class="mb-4 page-break-avoid">
                        <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-quote-left text-warning"></i>
                            <span>วิสัยทัศน์ & นโยบายในการปฏิบัติราชการ</span>
                        </h5>
                        <div class="exec-quote-banner shadow-sm">
                            <p class="fst-italic mb-2">“<?= esc($executive['quote']) ?>”</p>
                            <small class="text-muted d-block text-end">— <?= esc($executive['name']) ?> (<?= esc($executive['position']) ?>)</small>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Education History -->
                <div class="mb-4 pb-2 page-break-avoid">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-primary"></i>
                        <span>ประวัติการศึกษา (Education)</span>
                    </h5>
                    <?php if (!empty($eduLines)): ?>
                        <ul class="list-group list-group-flush rounded-3">
                            <?php foreach ($eduLines as $edu): ?>
                                <?php if (!empty(trim($edu))): ?>
                                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start gap-2">
                                        <i class="fa-solid fa-check text-success mt-1"></i>
                                        <span class="text-dark"><?= esc(ltrim($edu, "• \t\n\r\0\x0B-")) ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted small italic">ไม่มีข้อมูลประวัติการศึกษาที่บันทึกไว้</p>
                    <?php endif; ?>
                </div>

                <!-- Training & Executive Courses -->
                <div class="mb-4 pb-2 page-break-avoid">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-certificate text-warning"></i>
                        <span>ประวัติการฝึกอบรมและหลักสูตรสำคัญ (Training & Courses)</span>
                    </h5>
                    <?php if (!empty($trainingLines)): ?>
                        <ul class="list-group list-group-flush rounded-3">
                            <?php foreach ($trainingLines as $trn): ?>
                                <?php if (!empty(trim($trn))): ?>
                                    <li class="list-group-item px-0 py-2 border-0 d-flex align-items-start gap-2">
                                        <i class="fa-solid fa-award text-warning mt-1"></i>
                                        <span class="text-dark"><?= esc(ltrim($trn, "• \t\n\r\0\x0B-")) ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted small italic">ไม่มีข้อมูลประวัติการฝึกอบรมที่บันทึกไว้</p>
                    <?php endif; ?>
                </div>

                <!-- Civil Service Career History -->
                <div class="mb-4 page-break-avoid">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-briefcase text-primary"></i>
                        <span>ประวัติการรับราชการ / การดำรงตำแหน่งที่สำคัญ (Career History)</span>
                    </h5>
                    <?php if (!empty($historyLines)): ?>
                        <div class="ps-2 pt-2">
                            <?php foreach ($historyLines as $hItem): ?>
                                <?php if (!empty(trim($hItem))): ?>
                                    <div class="timeline-bio-item">
                                        <span class="timeline-bio-dot"></span>
                                        <div class="text-dark fw-semibold" style="line-height: 1.5;">
                                            <?= esc(ltrim($hItem, "• \t\n\r\0\x0B-")) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small italic">ไม่มีข้อมูลประวัติการรับราชการที่บันทึกไว้</p>
                    <?php endif; ?>
                </div>

                <!-- Print Footer for verification -->
                <div class="pt-4 border-top text-muted small d-flex flex-wrap justify-content-between gap-2">
                    <span>ข้อมูล ณ วันที่: <?= date('d/m/Y') ?></span>
                    <span>เว็บไซต์ทางการจังหวัดพัทลุง (phatthalung.go.th)</span>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- INCLUDE STUDIO MODAL FOR EDITING -->
<?= $this->include('components/executive_studio') ?>

<?= $this->endSection() ?>

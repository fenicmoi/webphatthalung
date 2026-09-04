<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$news = $news ?? [];
$isOfficer = session()->get('isLoggedIn');
$coverSrc = !empty($news['cover_image']) ? ((strpos((string)$news['cover_image'], 'http') === 0) ? $news['cover_image'] : base_url($news['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png');
?>

<!-- Article Header & Content Area -->
<div class="container my-4 my-md-5 pt-2">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            
            <!-- Breadcrumbs Navigation -->
            <nav aria-label="breadcrumb" class="mb-3 px-1">
                <ol class="breadcrumb m-0 d-flex align-items-center" style="font-size: 0.92rem;">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url() ?>" class="text-decoration-none fw-semibold text-success d-inline-flex align-items-center gap-1">
                            <i class="fa-solid fa-house" style="font-size: 0.85rem;"></i>
                            <span>หน้าหลัก</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('news') ?>" class="text-decoration-none fw-semibold text-success">
                            ข่าวสารและประกาศ
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-secondary text-truncate" style="max-width: 320px;" aria-current="page">
                        <?= esc($news['title'] ?? 'รายละเอียดข่าว') ?>
                    </li>
                </ol>
            </nav>

            <?php // ON-PAGE FRONTEND CMS BANNER FOR LOCAL NEWS (OFFICERS) ?>
            <?php if ($isOfficer && empty($news['is_prd'])): ?>
                <div class="p-3.5 rounded-4 mb-4 shadow-sm d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: linear-gradient(135deg, #1e293b, #0f172a); border: 2px solid #38bdf8;">
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="fa-solid fa-wand-magic-sparkles me-1"></i>On-Page CMS</span>
                        <div>
                            <h6 class="text-white fw-bold m-0">คุณกำลังรับชมในสิทธิ์เจ้าหน้าที่ผู้ดูแลระบบ</h6>
                            <small class="text-info">สามารถคลิกเพื่อแก้ไขเนื้อหา แนบไฟล์ หรือจัดการรูปภาพของบทความนี้ได้ทันทีโดยไม่ต้องเปลี่ยนหน้า</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" onclick="NewsStudio.open('<?= $news['id'] ?>')" class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-dark shadow-sm hover-scale" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                            <i class="fa-solid fa-pen-to-square me-2"></i>แก้ไขบทความนี้ (Edit Article)
                        </button>
                        <button type="button" onclick="NewsStudio.deleteNews('<?= $news['id'] ?>')" class="btn btn-outline-danger px-3 py-2 rounded-pill fw-bold">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php elseif (!empty($news['is_prd'])): 
                $sourceTitle = !empty($news['source']) ? $news['source'] : 'สำนักข่าวกรมประชาสัมพันธ์ (NNT)';
                $btnLabel = (mb_stripos($sourceTitle, 'สปชส') !== false) ? 'เปิดอ่านต้นฉบับ สปชส.พัทลุง' : 'เปิดอ่านต้นฉบับ NNT';
            ?>
                <!-- Official PRD Source Alert Banner -->
                <div class="p-3.5 px-4 rounded-4 mb-4 shadow-xs d-flex flex-wrap align-items-center justify-content-between gap-3 prd-source-banner">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2.5 rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 42px; height: 42px; background: #047857;">
                            <i class="fa-solid fa-bullhorn fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold m-0 prd-source-title">ข่าวสารเผยแพร่จาก <?= esc($sourceTitle) ?></h6>
                            <small class="prd-source-sub">ข้อมูลข่าวและภาพถ่ายกิจกรรมอย่างเป็นทางการของพื้นที่จังหวัดพัทลุง</small>
                        </div>
                    </div>
                    <?php if (!empty($news['source_url'])): ?>
                        <a href="<?= esc($news['source_url']) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-xs hover-scale" style="background: #047857; border: none; font-size: 0.9rem;">
                            <span><?= $btnLabel ?></span>
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Full Article Reading Card -->
            <article class="article-reading-card p-4 p-md-5 rounded-4 shadow-sm">
                
                <!-- Category & Meta Info Row -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-2 border-bottom" style="border-color: rgba(0,0,0,0.06) !important;">
                    <span class="badge px-3 py-2 rounded-pill fw-semibold shadow-xs article-cat-tag">
                        <i class="fa-solid fa-folder me-1.5"></i><?= esc($news['category'] ?? 'ข่าวประชาสัมพันธ์') ?>
                    </span>
                    <div class="article-meta-info small d-flex flex-wrap align-items-center gap-3">
                        <span><i class="fa-regular fa-calendar me-1 text-success"></i> วันที่ประกาศ: <?= date('d/m/Y H:i', strtotime($news['created_at'] ?? 'now')) ?> น.</span>
                        <span><i class="fa-regular fa-eye me-1 text-success"></i> ยอดเข้าชม <?= number_format($news['views'] ?? 1) ?> ครั้ง</span>
                    </div>
                </div>

                <!-- Article Title -->
                <h1 class="fw-bold article-title mb-3" style="font-size: 2rem; line-height: 1.45; letter-spacing: -0.01em;">
                    <?= esc($news['title']) ?>
                </h1>

                <!-- แถบเครื่องมือแชร์โซเชียล สั่งพิมพ์ และปรับขนาดตัวอักษร -->
                <div class="mb-4">
                    <?= $this->include('components/content_share_toolbar', ['shareTitle' => $news['title'] ?? '']) ?>
                </div>

                <!-- Main Cover Image -->
                <div class="rounded-4 overflow-hidden shadow-xs mb-4 text-center article-cover-wrapper">
                    <img src="<?= $coverSrc ?>" class="w-100 article-cover-img" style="max-height: 520px; object-fit: <?= esc($news['cover_fit'] ?? 'cover') ?>;" alt="<?= esc($news['title']) ?>">
                </div>

                <!-- Event Calendar & GPS Navigation Spotlight Banner (if applicable) -->
                <?php if (!empty($news['is_event']) && ($news['is_event'] == true || $news['is_event'] === '1' || $news['is_event'] === 'true')): 
                    $sDate = !empty($news['event_start_date']) ? date('d/m/Y', strtotime($news['event_start_date'])) : '-';
                    $eDate = !empty($news['event_end_date']) && $news['event_end_date'] !== $news['event_start_date'] ? ' ถึง ' . date('d/m/Y', strtotime($news['event_end_date'])) : '';
                    $location = !empty($news['event_location']) ? $news['event_location'] : 'ไม่ระบุสถานที่';
                    $coords = !empty($news['event_coordinates']) ? $news['event_coordinates'] : 'จังหวัดพัทลุง ' . $location;
                    $mapLink = strpos($coords, 'http') === 0 ? $coords : "https://maps.google.com/?q=" . urlencode($coords);
                ?>
                <div class="card p-4 rounded-4 mb-4 border shadow-sm event-spotlight-card">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-4 bg-success bg-opacity-20 text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px; border: 1px solid rgba(16, 185, 129, 0.3);">
                                <i class="fa-solid fa-calendar-days fs-2"></i>
                            </div>
                            <div>
                                <span class="badge bg-success rounded-pill px-3 py-1 text-white mb-1.5 d-inline-block" style="font-size: 0.78rem;">ปฏิทินกิจกรรมจังหวัดพัทลุง</span>
                                <h5 class="fw-bold m-0 d-flex align-items-center gap-2" style="font-size: 1.15rem;">
                                    <i class="fa-solid fa-clock text-warning"></i>
                                    <span>กำหนดการ: <?= $sDate . $eDate ?></span>
                                </h5>
                                <p class="m-0 text-muted small mt-1">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> สถานที่: <strong><?= esc($location) ?></strong>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <a href="<?= $mapLink ?>" target="_blank" class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow-xs d-inline-flex align-items-center gap-2 hover-scale" style="background: #047857; border: none;">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>นำทาง Google Maps</span>
                            </a>
                            <a href="<?= base_url('calendar') ?>" class="btn btn-outline-success rounded-pill px-3 py-2 small fw-bold" style="border-color: #047857; color: #047857;">
                                <i class="fa-solid fa-calendar me-1"></i> ปฏิทินทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Rich Text Content Body -->
                <div class="article-body pe-1 mb-5" style="font-size: 1.12rem; line-height: 1.95; color: #334155;">
                    <?= $news['content'] ?? '' ?>
                </div>

                <!-- DOWNLOAD ATTACHMENTS BOX -->
                <?php if (!empty($news['attachments']) && is_array($news['attachments'])): ?>
                    <div class="p-4 rounded-4 mb-5 shadow-xs article-attach-box">
                        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #047857; font-size: 1.15rem;">
                            <i class="fa-solid fa-folder-open text-warning fs-4"></i>
                            <span>เอกสารและไฟล์ดาวน์โหลดที่เกี่ยวข้อง (Attachments)</span>
                        </h5>
                        <div class="row g-3">
                            <?php foreach ($news['attachments'] as $doc): ?>
                                <div class="col-md-6">
                                    <a href="<?= esc($doc['url'] ?? '#') ?>" target="_blank" class="text-decoration-none d-flex align-items-center justify-content-between p-3 rounded-3 shadow-xs transition-all hover-lift article-attach-item">
                                        <div class="d-flex align-items-center gap-3 overflow-hidden me-2">
                                            <i class="<?= esc($doc['icon'] ?? 'fa-solid fa-file-pdf') ?> fs-2 text-danger"></i>
                                            <div class="text-truncate">
                                                <h6 class="fw-bold m-0 text-truncate article-attach-title" style="font-size: 0.95rem;"><?= esc($doc['name'] ?? 'ไฟล์ดาวน์โหลด') ?></h6>
                                                <small class="text-muted"><?= esc($doc['size'] ?? 'PDF/Doc') ?></small>
                                            </div>
                                        </div>
                                        <div class="btn btn-sm btn-success px-3 py-1.5 rounded-pill fw-semibold flex-shrink-0" style="background: #047857; border: none; font-size: 0.82rem;">
                                            <i class="fa-solid fa-download me-1"></i> โหลด
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- EXTRA IMAGE GALLERY EXHIBITION -->
                <?php if (!empty($news['images_gallery']) && is_array($news['images_gallery']) && count($news['images_gallery']) > 1): ?>
                    <div class="pt-4 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #047857; font-size: 1.15rem;">
                            <i class="fa-solid fa-camera-retro fs-4 text-success"></i>
                            <span>ภาพบรรยากาศและแกลลอรีกิจกรรมเพิ่มเติม (<?= count($news['images_gallery']) ?> ภาพ)</span>
                        </h5>
                        <div class="row g-3">
                            <?php foreach ($news['images_gallery'] as $idx => $imgPath): 
                                $gSrc = (strpos((string)$imgPath, 'http') === 0) ? $imgPath : base_url($imgPath);
                            ?>
                                <div class="col-6 col-md-4">
                                    <a href="<?= $gSrc ?>" class="d-block overflow-hidden rounded-3 shadow-xs hover-zoom position-relative group-gallery-item" style="height: 150px; border: 1px solid rgba(0,0,0,0.08);" title="คลิกเพื่อชมภาพขนาดใหญ่">
                                        <img src="<?= $gSrc ?>" class="w-100 h-100 object-fit-cover" alt="Gallery <?= $idx + 1 ?>" loading="lazy">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-2 text-center text-white small" style="background: linear-gradient(to top, rgba(0, 0, 0, 0.75), transparent);">
                                            <i class="fa-solid fa-magnifying-glass-plus me-1 text-warning"></i> <span>ขยายภาพ</span>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Share Footer & Return Button -->
                <div class="mt-5 pt-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-secondary small fw-semibold">แชร์บทความนี้:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://social-plugins.line.me/lineit/share?url=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;"><i class="fa-brands fa-line"></i></a>
                    </div>
                    <a href="<?= base_url('news') ?>" class="btn btn-outline-success px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2 shadow-xs" style="border-color: #047857; color: #047857; transition: all 0.2s ease;">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>กลับหน้ารวมข่าวสาร</span>
                    </a>
                </div>
            </article>
        </div>
    </div>
</div>

<style>
/* ==========================================================================
   News Detail Balanced & Harmonious UI System
   ========================================================================== */

/* PRD Source Alert Banner */
.prd-source-banner {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
}
.prd-source-title {
    color: #065f46;
}
.prd-source-sub {
    color: #047857;
    opacity: 0.9;
}

/* Category Pill Tag */
.article-cat-tag {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    font-size: 0.85rem;
}

/* Article Reading Card */
.article-reading-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
}

.article-title {
    color: #0f172a;
}

.article-meta-info {
    color: #64748b;
    font-weight: 500;
}

.article-cover-wrapper {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

/* Attachments Box */
.article-attach-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.article-attach-item {
    background: #ffffff;
    border: 1px solid #cbd5e1;
}

.article-attach-title {
    color: #0f172a;
}

/* Event Spotlight Card */
.event-spotlight-card {
    background: #f0fdf4;
    border-color: #bbf7d0 !important;
}

/* Custom prose styling for article readings */
.article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    margin: 20px auto;
    display: block;
    border: 1px solid #e2e8f0;
}
.article-body p {
    margin-bottom: 1.4rem;
}
.article-body h2, .article-body h3, .article-body h4 {
    color: #047857;
    margin-top: 1.8rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

/* Dark Mode Overrides */
[data-theme="dark"] .prd-source-banner {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.35);
    color: #ecfdf5;
}
[data-theme="dark"] .prd-source-title {
    color: #6ee7b7;
}
[data-theme="dark"] .prd-source-sub {
    color: #a7f3d0;
}
[data-theme="dark"] .article-cat-tag {
    background: rgba(16, 185, 129, 0.2);
    color: #34d399;
    border-color: rgba(16, 185, 129, 0.4);
}
[data-theme="dark"] .article-reading-card {
    background: rgba(30, 41, 59, 0.7);
    border-color: rgba(255, 255, 255, 0.12);
}
[data-theme="dark"] .article-title {
    color: #f8fafc;
}
[data-theme="dark"] .article-body {
    color: #cbd5e1 !important;
}
[data-theme="dark"] .article-meta-info {
    color: #94a3b8;
}
[data-theme="dark"] .article-cover-wrapper {
    background: #0f172a;
    border-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] .article-attach-box {
    background: rgba(15, 23, 42, 0.85);
    border-color: rgba(56, 189, 248, 0.3);
}
[data-theme="dark"] .article-attach-item {
    background: rgba(30, 41, 59, 0.8);
    border-color: rgba(255, 255, 255, 0.12);
}
[data-theme="dark"] .article-attach-title {
    color: #ffffff;
}
[data-theme="dark"] .event-spotlight-card {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.25));
    border-color: rgba(16, 185, 129, 0.5) !important;
}
[data-theme="dark"] .article-body img {
    border-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 10px 25px rgba(0,0,0,0.35);
}
</style>

<?= $this->endSection() ?>

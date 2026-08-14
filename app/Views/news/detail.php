<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isOfficer = session()->get('isLoggedIn');
$coverSrc = !empty($news['cover_image']) ? ((strpos((string)$news['cover_image'], 'http') === 0) ? $news['cover_image'] : base_url($news['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png');
?>

<!-- Article Header Area -->
<div class="container my-5 pt-3">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size: 0.95rem;">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('news') ?>" class="text-info text-decoration-none">ข่าวสารและประกาศ</a></li>
            <li class="breadcrumb-item active text-light text-truncate" style="max-width: 300px;" aria-current="page"><?= esc($news['title']) ?></li>
        </ol>
    </nav>

    <?php // ON-PAGE FRONTEND CMS BANNER FOR OFFICERS ?>
    <?php if ($isOfficer): ?>
        <div class="p-3 rounded-4 mb-4 shadow-lg d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: linear-gradient(135deg, #1e293b, #0f172a); border: 2px solid #38bdf8;">
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
    <?php endif; ?>

    <!-- Main Article Layout -->
    <div class="row g-5">
        <!-- Left: Full Article Reading Room -->
        <div class="col-lg-8">
            <article class="article-reading-card p-4 p-md-5 rounded-4 shadow-lg border-0">
                
                <!-- Category & Meta Info -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6) !important;">
                        <i class="fa-solid fa-folder me-2"></i><?= esc($news['category'] ?? 'ข่าวประกาศ') ?>
                    </span>
                    <div class="article-meta-info small d-flex gap-3">
                        <span><i class="fa-regular fa-calendar me-1"></i> วันที่ประกาศ: <?= date('d/m/Y H:i', strtotime($news['created_at'] ?? 'now')) ?> น.</span>
                        <span><i class="fa-regular fa-eye me-1"></i> ยอดเข้าชม <?= number_format($news['views'] ?? 1) ?> ครั้ง</span>
                    </div>
                </div>

                <!-- Article Title -->
                <h1 class="fw-bold article-title mb-4" style="font-size: 2.1rem; line-height: 1.4;">
                    <?= esc($news['title']) ?>
                </h1>

                <div class="rounded-4 overflow-hidden shadow-lg mb-5 text-center" style="max-height: 480px; background-color: #090d16;">
                    <img src="<?= $coverSrc ?>" class="w-100" style="max-height: 480px; object-fit: <?= esc($news['cover_fit'] ?? 'cover') ?>;" alt="<?= esc($news['title']) ?>">
                </div>

                <!-- Event Calendar & GPS Navigation Spotlight Banner -->
                <?php if (!empty($news['is_event']) && ($news['is_event'] == true || $news['is_event'] === '1' || $news['is_event'] === 'true')): 
                    $sDate = !empty($news['event_start_date']) ? date('d/m/Y', strtotime($news['event_start_date'])) : '-';
                    $eDate = !empty($news['event_end_date']) && $news['event_end_date'] !== $news['event_start_date'] ? ' ถึง ' . date('d/m/Y', strtotime($news['event_end_date'])) : '';
                    $location = !empty($news['event_location']) ? $news['event_location'] : 'ไม่ระบุสถานที่';
                    $coords = !empty($news['event_coordinates']) ? $news['event_coordinates'] : 'จังหวัดพัทลุง ' . $location;
                    $mapLink = strpos($coords, 'http') === 0 ? $coords : "https://maps.google.com/?q=" . urlencode($coords);
                ?>
                <div class="card p-4 rounded-4 mb-5 border shadow-lg" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.25)); border-color: rgba(16, 185, 129, 0.5) !important;">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-4 bg-success bg-opacity-25 text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 65px; height: 65px; border: 1px solid rgba(16, 185, 129, 0.4);">
                                <i class="fa-solid fa-calendar-days fs-2 text-warning"></i>
                            </div>
                            <div>
                                <span class="badge bg-success rounded-pill px-3 py-1 text-white mb-2 d-inline-block">ปฏิทินกิจกรรมจังหวัดพัทลุง</span>
                                <h5 class="fw-bold m-0 text-white d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-clock text-warning"></i>
                                    <span>กำหนดการ: <?= $sDate . $eDate ?></span>
                                </h5>
                                <p class="m-0 text-light opacity-85 small mt-1">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> สถานที่: <strong><?= esc($location) ?></strong>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <a href="<?= $mapLink ?>" target="_blank" class="btn btn-info text-dark fw-bold rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2 hover-scale" style="background: linear-gradient(135deg, #38bdf8, #0ea5e9); border: none;">
                                <i class="fa-solid fa-map-location-dot fs-5 text-warning"></i>
                                <span>นำทางด้วย Google Maps</span>
                            </a>
                            <a href="<?= base_url('calendar') ?>" class="btn btn-outline-success text-white rounded-pill px-3 py-2 small fw-bold">
                                <i class="fa-solid fa-calendar me-1"></i> ปฏิทินทั้งหมด
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Rich Text Content Body -->
                <div class="article-body pe-2 mb-5" style="font-size: 1.15rem; line-height: 1.9;">
                    <?= $news['content'] ?? '' ?>
                </div>

                <!-- DOWNLOAD ATTACHMENTS BOX -->
                <?php if (!empty($news['attachments']) && is_array($news['attachments'])): ?>
                    <div class="p-4 rounded-4 mb-5 shadow-sm" style="background: rgba(15, 23, 42, 0.85); border: 2px solid rgba(56, 189, 248, 0.4);">
                        <h5 class="fw-bold text-warning mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-folder-open text-warning fs-4"></i>
                            <span>เอกสารและไฟล์ดาวน์โหลดที่เกี่ยวข้อง (Attachments)</span>
                        </h5>
                        <div class="row g-3">
                            <?php foreach ($news['attachments'] as $doc): ?>
                                <div class="col-md-6">
                                    <a href="<?= esc($doc['url'] ?? '#') ?>" target="_blank" class="text-decoration-none d-flex align-items-center justify-content-between p-3 rounded-3 shadow-sm transition-all hover-lift" style="background: rgba(30, 41, 59, 0.8); border: 1px solid rgba(255,255,255,0.12);">
                                        <div class="d-flex align-items-center gap-3 overflow-hidden me-2">
                                            <i class="<?= esc($doc['icon'] ?? 'fa-solid fa-file') ?> fs-2"></i>
                                            <div class="text-truncate">
                                                <h6 class="fw-bold text-white m-0 text-truncate" style="font-size: 1rem;"><?= esc($doc['name'] ?? 'ไฟล์ดาวน์โหลด') ?></h6>
                                                <small class="text-info"><?= esc($doc['size'] ?? 'PDF/Doc') ?></small>
                                            </div>
                                        </div>
                                        <div class="btn btn-sm btn-primary px-3 py-2 rounded-pill fw-bold flex-shrink-0">
                                            <i class="fa-solid fa-download me-1"></i> โหลดไฟล์
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- EXTRA IMAGE GALLERY EXHIBITION -->
                <?php if (!empty($news['images_gallery']) && is_array($news['images_gallery']) && count($news['images_gallery']) > 1): ?>
                    <div class="pt-4 border-top" style="border-color: rgba(255,255,255,0.15) !important;">
                        <h5 class="fw-bold text-info mb-4 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-camera-retro fs-4"></i>
                            <span>ภาพบรรยากาศและแกลลอรีกิจกรรมเพิ่มเติม (<?= count($news['images_gallery']) ?> ภาพ)</span>
                        </h5>
                        <div class="row g-3">
                            <?php foreach ($news['images_gallery'] as $idx => $imgPath): 
                                $gSrc = (strpos((string)$imgPath, 'http') === 0) ? $imgPath : base_url($imgPath);
                            ?>
                                <div class="col-6 col-md-4">
                                    <a href="<?= $gSrc ?>" class="d-block overflow-hidden rounded-3 shadow-sm hover-zoom position-relative group-gallery-item" style="height: 140px; border: 2px solid rgba(255,255,255,0.2);" title="คลิกเพื่อชมภาพใน ShadowBox">
                                        <img src="<?= $gSrc ?>" class="w-100 h-100 object-fit-cover" alt="Gallery <?= $idx + 1 ?>">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-2 text-center text-white small" style="background: linear-gradient(to top, rgba(2, 132, 199, 0.85), transparent); backdrop-filter: blur(2px);">
                                            <i class="fa-solid fa-magnifying-glass-plus me-1 text-warning"></i> <span>ขยายชมภาพ</span>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Share Footer -->
                <div class="mt-5 pt-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--glass-border) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-secondary small fw-bold">แชร์บทความนี้:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://social-plugins.line.me/lineit/share?url=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="fa-brands fa-line"></i></a>
                    </div>
                    <a href="<?= base_url('news') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold">
                        <i class="fa-solid fa-arrow-left me-2"></i>กลับหน้าหลักข่าวสาร
                    </a>
                </div>
            </article>
        </div>

        <!-- Right: Recent & Trending News Sidebar -->
        <div class="col-lg-4">
            <div class="glass-card p-4 rounded-4 shadow-lg sticky-top" style="top: 100px; background: rgba(15, 23, 42, 0.75); border: 1px solid rgba(255,255,255,0.15);">
                <h5 class="fw-bold text-white mb-4 pb-2 border-bottom d-flex align-items-center gap-2" style="border-color: rgba(255,255,255,0.15) !important;">
                    <i class="fa-solid fa-newspaper text-warning"></i>
                    <span>ประกาศและข่าวกิจกรรมอื่นๆ</span>
                </h5>

                <div class="d-flex flex-column gap-3">
                    <?php if (!empty($recentNews) && is_array($recentNews)): ?>
                        <?php foreach ($recentNews as $rn): 
                            if (strval($rn['id']) === strval($news['id'])) continue;
                            $rnCover = !empty($rn['cover_image']) ? ((strpos((string)$rn['cover_image'], 'http') === 0) ? $rn['cover_image'] : base_url($rn['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png');
                        ?>
                            <a href="<?= base_url('news/detail/' . $rn['id']) ?>" class="text-decoration-none d-flex gap-3 p-2 rounded-3 transition-all hover-lift" style="background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255,255,255,0.08);">
                                <img src="<?= $rnCover ?>" class="rounded-3 object-fit-cover flex-shrink-0 shadow-sm" style="width: 90px; height: 75px;" alt="<?= esc($rn['title']) ?>">
                                <div class="overflow-hidden">
                                    <span class="badge bg-dark text-warning border mb-1" style="font-size: 0.7rem; border-color: rgba(245,158,11,0.4) !important;"><?= esc($rn['category'] ?? 'ข่าวประกาศ') ?></span>
                                    <h6 class="text-white fw-bold m-0 line-clamp-2" style="font-size: 0.95rem; line-height: 1.4;">
                                        <?= esc($rn['title']) ?>
                                    </h6>
                                    <small class="text-info" style="font-size: 0.75rem;"><i class="fa-regular fa-calendar me-1"></i><?= date('d/m/Y', strtotime($rn['created_at'] ?? 'now')) ?></small>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-secondary py-3">ไม่มีรายการข่าวอื่น</div>
                    <?php endif; ?>
                </div>

                <div class="mt-4 pt-3 text-center border-top" style="border-color: rgba(255,255,255,0.15) !important;">
                    <a href="<?= base_url('news') ?>" class="btn btn-outline-info w-100 rounded-pill fw-bold py-2">
                        <i class="fa-solid fa-table-list me-2"></i>ดูคลังข่าวสารทั้งหมด
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom prose styling for article readings */
.article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.35);
    margin: 20px auto;
    display: block;
    border: 1px solid rgba(255,255,255,0.15);
}
.article-body p {
    margin-bottom: 1.4rem;
}
.article-body h2, .article-body h3, .article-body h4 {
    color: #38bdf8;
    margin-top: 1.8rem;
    margin-bottom: 1rem;
    font-weight: bold;
}
</style>

<?= $this->endSection() ?>

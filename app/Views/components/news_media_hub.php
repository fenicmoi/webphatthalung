<?php
// =========================================================================
// ศาลาประชาสัมพันธ์และสื่อเมืองลุง (News, Events, Photo Gallery & Videos Hub)
// =========================================================================
$homeNews = function_exists('get_site_news') ? get_site_news(9, null, true) : [];
$newsCats = function_exists('get_news_categories') ? get_news_categories() : [];
$homeEvents = function_exists('get_site_events') ? get_site_events(true) : [];
$homeGalleryAlbums = function_exists('get_gallery_albums') ? get_gallery_albums(4, null, true) : [];
$totalAlbums = function_exists('get_gallery_albums') ? count(get_gallery_albums()) : 0;
$homeVideos = function_exists('get_site_videos') ? get_site_videos(3, null, true) : [];
$isOfficer = session()->get('isLoggedIn');
?>

<style>
/* CSS styles for the News & Media Hub */
/* 1. Main Tabs (ประเภทหลัก) - Underline Style */
.nav-underline-custom {
    gap: 1rem;
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    padding-bottom: 1px;
}
.nav-underline-custom::-webkit-scrollbar {
    height: 4px;
}
.nav-underline-custom::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.1);
    border-radius: 4px;
}
.news-media-tab-trigger {
    color: #64748b !important;
    background: transparent !important;
    border: none !important;
    padding: 12px 4px !important;
    margin-right: 1.5rem !important;
    font-weight: 600 !important;
    font-size: 1.05rem;
    transition: all 0.25s ease !important;
    border-radius: 0 !important;
    border-bottom: 3px solid transparent !important;
    white-space: nowrap;
}
.news-media-tab-trigger:hover {
    color: #1e3a8a !important;
    border-bottom-color: rgba(30, 58, 138, 0.3) !important;
}
.news-media-tab-trigger.active {
    color: #1e3a8a !important;
    border-bottom: 3px solid #1e3a8a !important;
    box-shadow: none !important;
    font-weight: 700 !important;
}

[data-theme="dark"] .news-media-tab-trigger {
    color: #94a3b8 !important;
}
[data-theme="dark"] .news-media-tab-trigger:hover {
    color: #60a5fa !important;
    border-bottom-color: rgba(96, 165, 250, 0.3) !important;
}
[data-theme="dark"] .news-media-tab-trigger.active {
    color: #60a5fa !important;
    border-bottom-color: #60a5fa !important;
}

/* 2. Category Sub-tabs (ประเภทย่อย) - Filter Chips */
.news-cat-btn {
    border: 1px solid #e2e8f0 !important;
    background: #ffffff;
    color: #475569 !important;
    transition: all 0.2s ease;
    font-weight: 500;
    padding: 6px 16px !important;
    font-size: 0.85rem !important;
    border-radius: 30px !important;
}
.news-cat-btn:hover {
    background: #f1f5f9 !important;
    color: #1e3a8a !important;
    border-color: #cbd5e1 !important;
}
.news-cat-btn.active {
    background: #eff6ff !important;
    color: #1e3a8a !important;
    font-weight: 700;
    border-color: #3b82f6 !important;
    box-shadow: none !important;
}

[data-theme="dark"] .news-cat-btn {
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    background: rgba(255, 255, 255, 0.05);
}
[data-theme="dark"] .news-cat-btn:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #60a5fa !important;
    border-color: rgba(96, 165, 250, 0.4) !important;
}
[data-theme="dark"] .news-cat-btn.active {
    background: rgba(37, 99, 235, 0.2) !important;
    color: #60a5fa !important;
    border-color: #60a5fa !important;
}
.news-read-more-link {
    color: #2563eb !important;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.2s ease;
}
.news-read-more-link:hover {
    transform: translateX(4px);
}
</style>

<section id="news-media-hub" class="my-5 py-4">
    <div class="glass-card p-4 p-md-5" style="border-radius: 28px; border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow); background: var(--card-bg, #ffffff);">
        
        <!-- Hub Header & Segmented Switcher -->
        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                        <i class="fa-solid fa-bullhorn text-warning"></i>
                        <span>ศาลาประชาสัมพันธ์และข่าวสารจังหวัด</span>
                        <?php if ($isOfficer): ?>
                            <span class="badge bg-success text-dark px-2 py-1 fs-6"><i class="fa-solid fa-user-shield me-1"></i>แอดมิน</span>
                        <?php endif; ?>
                    </h3>
                    <p style="color: var(--text-secondary); margin: 0; font-size: 0.92rem;">
                        จุดศูนย์รวมการประกาศข่าวสาร ปฏิทินตารางงาน คลังภาพ และรายการวิดีทัศน์ประชาสัมพันธ์จังหวัดพัทลุง
                    </p>
                </div>
            </div>

            <!-- Tab Navigation Pills -> Underline Style -->
            <ul class="nav nav-underline-custom border-bottom" id="newsMediaHubTabs" role="tablist" style="border-color: rgba(0,0,0,0.08) !important;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active news-media-tab-trigger" id="tab-pr-news-trigger" data-bs-toggle="pill" data-bs-target="#tab-pr-news" type="button" role="tab" aria-controls="tab-pr-news" aria-selected="true">
                        📰 ข่าวประชาสัมพันธ์
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link news-media-tab-trigger" id="tab-calendar-trigger" data-bs-toggle="pill" data-bs-target="#tab-calendar" type="button" role="tab" aria-controls="tab-calendar" aria-selected="false">
                        📅 ปฏิทินตารางงาน
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link news-media-tab-trigger" id="tab-gallery-trigger" data-bs-toggle="pill" data-bs-target="#tab-gallery" type="button" role="tab" aria-controls="tab-gallery" aria-selected="false">
                        📸 คลังภาพกิจกรรม
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link news-media-tab-trigger" id="tab-videos-trigger" data-bs-toggle="pill" data-bs-target="#tab-videos" type="button" role="tab" aria-controls="tab-videos" aria-selected="false">
                        📹 วิดีทัศน์ Web TV
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content Area -->
        <div class="tab-content" id="newsMediaHubTabContent">
            
            <!-- Tab 1: ข่าวประชาสัมพันธ์ -->
            <div class="tab-pane fade show active" id="tab-pr-news" role="tabpanel" aria-labelledby="tab-pr-news-trigger">
                <!-- Action buttons for admins -->
                <?php if ($isOfficer): ?>
                    <div class="d-flex flex-wrap gap-2 mb-3 justify-content-end">
                        <button type="button" onclick="NewsStudio.addCategory()" class="btn btn-xs btn-outline-info py-2 px-3 rounded-pill fw-bold">
                            <i class="fa-solid fa-tags"></i> เพิ่มหมวดหมู่ใหม่
                        </button>
                        <button type="button" onclick="NewsStudio.open()" class="btn btn-xs btn-warning py-2 px-4 rounded-pill fw-bold text-dark" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                            <i class="fa-solid fa-circle-plus"></i> + สร้างประกาศข่าวใหม่ (Studio)
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Category Filters inside Tab -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button class="btn btn-xs px-4 py-2 news-cat-btn rounded-pill active" data-filter="all" onclick="filterHomeNews('all', this)">
                        ทั้งหมด (<?= count($homeNews) ?>)
                    </button>
                    <?php foreach ($newsCats as $cat): 
                        $countInCat = 0;
                        foreach ($homeNews as $hn) {
                            if (strcasecmp(trim($hn['category'] ?? ''), trim($cat)) === 0) $countInCat++;
                        }
                        if ($countInCat === 0 && !$isOfficer) continue;
                    ?>
                        <button class="btn btn-xs px-4 py-2 news-cat-btn rounded-pill <?= ($countInCat === 0) ? 'opacity-50' : '' ?>" data-filter="<?= esc($cat) ?>" onclick="filterHomeNews('<?= esc($cat) ?>', this)">
                            <?= esc($cat) ?> (<?= $countInCat ?>)
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- News Grid List -->
                <div class="row g-4" id="newsGridContainer">
                    <?php if (empty($homeNews)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa-regular fa-folder-open fs-1 text-muted mb-3"></i>
                            <p class="text-muted m-0">ยังไม่มีข้อมูลข่าวสารในระบบขณะนี้</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($homeNews as $item): 
                            $coverImg = (!empty($item['cover_image']) && (strpos($item['cover_image'], 'http') === 0 || strpos($item['cover_image'], 'data:') === 0 || strpos($item['cover_image'], 'uploads/') === 0)) ? ((strpos($item['cover_image'], 'http') === 0) ? $item['cover_image'] : base_url($item['cover_image'])) : 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=80';
                            $attachCount = !empty($item['attachments']) ? count($item['attachments']) : 0;
                        ?>
                            <div class="col-md-6 col-lg-4 news-card-item" data-category="<?= esc($item['category'] ?? 'ทั่วไป') ?>">
                                <div class="glass-card h-100 d-flex flex-column justify-content-between hover-lift overflow-hidden" style="border: 1px solid var(--glass-border);">
                                    
                                    <!-- Image Header -->
                                    <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="d-block overflow-hidden position-relative group-hover-zoom flex-shrink-0" style="height: 180px; background: #0f172a;">
                                        <span class="badge position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill fw-bold" style="background: rgba(15,23,42,0.85); backdrop-filter: blur(8px); color: var(--accent-primary); z-index: 2; border: 1px solid rgba(56,189,248,0.25);">
                                            <i class="fa-solid fa-tag me-1"></i> <?= esc($item['category'] ?? 'ทั่วไป') ?>
                                        </span>
                                        <img src="<?= $coverImg ?>" alt="<?= esc($item['title']) ?>" class="w-100 h-100 object-fit-cover transition-all" loading="lazy">
                                    </a>

                                    <!-- Content Body -->
                                    <div class="card-body p-4 d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-2 news-card-meta small">
                                                <span><i class="fa-regular fa-calendar me-1 opacity-75"></i> <?= date('d/m/Y', strtotime($item['created_at'] ?? 'now')) ?></span>
                                                <span><i class="fa-regular fa-eye me-1 opacity-75"></i> <?= number_format($item['views'] ?? 1) ?> ครั้ง</span>
                                            </div>
                                            <h5 class="fw-bold news-card-title mb-1 line-clamp-2" style="font-size: 1.12rem; line-height: 1.4;">
                                                <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="text-decoration-none text-dark" style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    <?= esc($item['title']) ?>
                                                </a>
                                            </h5>
                                        </div>

                                        <div class="mt-4 pt-3 news-card-footer d-flex align-items-center justify-content-between border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                                            <div>
                                                <?php if ($attachCount > 0): ?>
                                                    <small class="text-secondary opacity-75" style="font-size: 0.8rem;"><i class="fa-solid fa-paperclip me-1"></i><?= $attachCount ?> ไฟล์แนบ</small>
                                                <?php endif; ?>
                                            </div>
                                            <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="news-read-more-link small">
                                                <span>รายละเอียด ➔</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab 2: ปฏิทินตารางงาน -->
            <div class="tab-pane fade" id="tab-calendar" role="tabpanel" aria-labelledby="tab-calendar-trigger">
                <?php if ($isOfficer): ?>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" onclick="window.location.href='<?= base_url('calendar') ?>'" class="btn btn-xs btn-warning rounded-pill px-4 py-2 fw-bold text-dark">
                            <i class="fa-solid fa-calendar-plus"></i> จัดการปฏิทินงาน (Studio)
                        </button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <?php if (empty($homeEvents)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa-solid fa-calendar-xmark fs-1 text-muted mb-3"></i>
                            <p class="text-muted m-0">ยังไม่มีตารางปฏิบัติงานและปฏิทินกิจกรรมในระบบขณะนี้</p>
                        </div>
                    <?php else: ?>
                        <?php 
                        $displayEvents = array_slice($homeEvents, 0, 3);
                        foreach ($displayEvents as $ev): 
                            $sDate = !empty($ev['event_start_date']) ? date('d/m/Y', strtotime($ev['event_start_date'])) : 'เร็วๆ นี้';
                            $loc = !empty($ev['event_location']) ? $ev['event_location'] : 'จังหวัดพัทลุง';
                        ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 d-flex flex-column justify-content-between transition-transform hover-scale" onclick="SmartEventViewer.open('<?= $ev['id'] ?>')" style="cursor: pointer; background: var(--card-bg, #ffffff); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
                                    <div>
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-bold">
                                                <i class="fa-solid fa-clock text-warning me-1"></i> <?= $sDate ?>
                                            </span>
                                            <span class="badge bg-secondary bg-opacity-10 text-muted small"><?= esc($ev['category'] ?? 'กิจกรรม') ?></span>
                                        </div>
                                        <h5 class="fw-bold mb-3 text-truncate-2 text-dark" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?= esc($ev['title']) ?>
                                        </h5>
                                        <p class="text-muted small line-clamp-2 mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                            <?= esc($ev['summary'] ?? strip_tags($ev['content'] ?? '')) ?>
                                        </p>
                                    </div>

                                    <div class="pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.06) !important;">
                                        <span class="text-primary small fw-bold text-truncate me-2" style="max-width: 65%;" title="<?= esc($loc) ?>">
                                            <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= esc($loc) ?>
                                        </span>
                                        <span class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold flex-shrink-0">
                                            <span>ดูพิกัด & รายละเอียด</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab 3: คลังภาพกิจกรรม -->
            <div class="tab-pane fade" id="tab-gallery" role="tabpanel" aria-labelledby="tab-gallery-trigger">
                <?php if ($isOfficer): ?>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" onclick="GalleryStudio.open()" class="btn btn-xs btn-warning rounded-pill px-4 py-2 fw-bold text-dark" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                            <i class="fa-solid fa-circle-plus"></i> เพิ่มอัลบั้มใหม่ (Studio)
                        </button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <?php if (empty($homeGalleryAlbums)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa-regular fa-image fs-1 text-muted mb-3"></i>
                            <p class="text-muted m-0">ยังไม่มีอัลบั้มรูปภาพในระบบขณะนี้</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($homeGalleryAlbums as $alb): 
                            $covUrl = (!empty($alb['cover_image']) && (strpos($alb['cover_image'], 'http') === 0 || strpos($alb['cover_image'], 'data:') === 0 || strpos($alb['cover_image'], 'uploads/') === 0)) ? ((strpos($alb['cover_image'], 'http') === 0) ? $alb['cover_image'] : base_url($alb['cover_image'])) : 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80';
                            $pCnt = !empty($alb['photos']) ? count($alb['photos']) : 0;
                        ?>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="<?= base_url('gallery/album/' . $alb['id']) ?>" class="text-decoration-none h-100 d-block">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-transform hover-scale" style="background: var(--card-bg, #ffffff); border: 1px solid rgba(15,23,42,0.08) !important;">
                                        <div class="position-relative" style="padding-top: 65%; overflow: hidden; background: #0f172a;">
                                            <img src="<?= $covUrl ?>" alt="<?= esc($alb['title']) ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover transition-all" loading="lazy" style="object-fit: cover;">
                                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, transparent 40%, rgba(0,0,0,0.7) 100%);"></div>
                                            <span class="badge position-absolute top-0 start-0 m-3 rounded-pill px-3 py-1 shadow" style="background: linear-gradient(135deg, #a855f7, #6b21a8); font-size: 0.75rem;">
                                                <?= esc($alb['category'] ?? 'กิจกรรมจังหวัด') ?>
                                            </span>
                                            <span class="badge position-absolute bottom-0 end-0 m-3 rounded-pill px-3 py-1 bg-dark text-info border border-secondary border-opacity-50 shadow" style="font-size: 0.78rem;">
                                                <i class="fa-solid fa-camera me-1"></i> <?= $pCnt ?> ภาพ
                                            </span>
                                        </div>
                                        <div class="card-body p-3 d-flex flex-column justify-content-between flex-grow-1">
                                            <h6 class="fw-bold mb-2 text-dark" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <?= esc($alb['title']) ?>
                                            </h6>
                                            <div class="d-flex align-items-center justify-content-between text-muted mt-3 pt-2 border-top small" style="font-size: 0.78rem; border-color: rgba(0,0,0,0.06) !important;">
                                                <span><i class="fa-regular fa-calendar me-1 text-primary"></i> <?= $alb['date'] ?? '' ?></span>
                                                <span><i class="fa-regular fa-eye me-1 text-secondary"></i> <?= number_format($alb['views'] ?? 1) ?> ครั้ง</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tab 4: วิดีทัศน์ Web TV -->
            <!-- Tab 4: วิดีทัศน์ Web TV -->
            <div class="tab-pane fade" id="tab-videos" role="tabpanel" aria-labelledby="tab-videos-trigger">
                <?php if ($isOfficer): ?>
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3.5 p-3 rounded-4 bg-light border">
                        <div class="small fw-bold text-dark d-flex align-items-center gap-2">
                            <span class="badge bg-danger rounded-pill px-2.5 py-1.5"><i class="fa-solid fa-film me-1"></i> โหมดจัดการวิดีโอ</span>
                            <span>คุณสามารถเพิ่ม แก้ไข หรือลบวิดีโอได้โดยตรง</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" onclick="openHomeVideoAddModal()" class="btn btn-sm btn-danger rounded-pill px-3.5 py-1.5 fw-bold shadow-sm d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> เพิ่มวิดีโอใหม่
                            </button>
                            <a href="<?= base_url('admin/videos') ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-sliders"></i> สตูดิโอหลังบ้าน
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <?php if (empty($homeVideos)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa-solid fa-video-slash fs-1 text-muted mb-3"></i>
                            <p class="text-muted m-0">ยังไม่มีวิดีโอประชาสัมพันธ์ในระบบขณะนี้</p>
                            <?php if ($isOfficer): ?>
                                <button type="button" onclick="openHomeVideoAddModal()" class="btn btn-danger btn-sm rounded-pill mt-3 px-4">
                                    <i class="fa-solid fa-plus me-1"></i> เพิ่มวิดีโอแรก
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($homeVideos as $vid): 
                            $yId = esc($vid['youtube_id'] ?? '');
                            $thumbUrl = "https://img.youtube.com/vi/{$yId}/hqdefault.jpg";
                            $vidTitle = esc($vid['title'] ?? 'วิดีโอจังหวัดพัทลุง');
                            $vidDesc = esc($vid['desc'] ?? '');
                            $vidCat = esc($vid['category'] ?? 'ทั่วไป');
                            $vidViews = number_format($vid['views'] ?? 1);
                            $vidDate = !empty($vid['date']) ? date('d/m/Y', strtotime($vid['date'])) : '-';
                            $vidId = esc($vid['id'] ?? '');
                        ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-transform hover-scale d-flex flex-column justify-content-between position-relative" style="cursor: pointer; background: var(--card-bg, #ffffff); border: 1px solid rgba(225, 29, 72, 0.2) !important;">
                                    
                                    <?php if ($isOfficer): ?>
                                        <!-- Quick Edit/Delete Overlay for Officer -->
                                        <div class="position-absolute top-0 end-0 m-2.5 d-flex gap-1.5" style="z-index: 10;">
                                            <button type="button" class="btn btn-sm btn-white bg-white shadow rounded-circle border p-0" style="width: 32px; height: 32px;" onclick="event.stopPropagation(); editHomeVideo('<?= $vidId ?>')" title="แก้ไขวิดีโอ">
                                                <i class="fa-solid fa-pen-to-square text-primary" style="font-size: 0.8rem;"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-white bg-white shadow rounded-circle border p-0" style="width: 32px; height: 32px;" onclick="event.stopPropagation(); deleteHomeVideo('<?= $vidId ?>', '<?= addslashes($vidTitle) ?>')" title="ลบวิดีโอ">
                                                <i class="fa-solid fa-trash-can text-danger" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <div onclick="SmartHomeCinema.play('<?= $yId ?>', '<?= addslashes($vid['title']) ?>', '<?= $vidId ?>')">
                                        <!-- Thumbnail with Play Overlay -->
                                        <div class="position-relative overflow-hidden bg-dark" style="padding-top: 56.25%;">
                                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-1 rounded-pill fw-bold" style="background: rgba(15,23,42,0.85); backdrop-filter: blur(8px); color: #fb7185; z-index: 2; border: 1px solid rgba(251,113,133,0.3);">
                                                <i class="fa-solid fa-film me-1"></i> <?= $vidCat ?>
                                            </span>
                                            <span class="badge position-absolute bottom-0 end-0 m-3 px-2 py-1 rounded" style="background: rgba(0,0,0,0.8); color: #fff; z-index: 2; font-size: 0.75rem;">
                                                <i class="fa-solid fa-eye text-warning me-1"></i> <?= $vidViews ?>
                                            </span>
                                            <img src="<?= $thumbUrl ?>" alt="<?= $vidTitle ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-90 transition-all hover-scale" onerror="this.src='https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80'">
                                            <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle text-white shadow-lg" style="width: 56px; height: 56px; background: rgba(225, 29, 72, 0.88); backdrop-filter: blur(4px); z-index: 2;">
                                                <i class="fa-solid fa-play ms-1 fs-4"></i>
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <h5 class="fw-bold mb-2 text-dark" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                <?= $vidTitle ?>
                                            </h5>
                                            <p class="text-muted small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                                <?= $vidDesc ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between text-muted small" style="border-color: rgba(0,0,0,0.06) !important;" onclick="SmartHomeCinema.play('<?= $yId ?>', '<?= addslashes($vid['title']) ?>', '<?= $vidId ?>')">
                                        <span><i class="fa-regular fa-calendar-days me-1 text-danger"></i> <?= $vidDate ?></span>
                                        <span class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold flex-shrink-0" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-play me-1"></i> คลิกเล่นวิดีโอ
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</section>

<?php if ($isOfficer): ?>
<!-- ======================================================== -->
<!-- MODAL: QUICK ADD/EDIT YOUTUBE VIDEO (HOME TAB) -->
<!-- ======================================================== -->
<div class="modal fade" id="homeVideoModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-danger text-white py-3 px-4" style="background: linear-gradient(135deg, #991b1b, #dc2626) !important;">
                <h5 class="modal-title fw-bold" id="homeVideoModalTitle">
                    <i class="fa-solid fa-film me-2"></i> จัดการวิดีโอ Web TV
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="homeVideoForm" onsubmit="event.preventDefault(); saveHomeVideo();">
                    <input type="hidden" id="homeVideoId" name="id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อวิดีโอ (Video Title) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="homeVideoTitle" name="title" required placeholder="เช่น มหัศจรรย์ทะเลน้อย: สวรรค์ของนกน้ำ">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ลิงก์วิดีโอ YouTube หรือ YouTube ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-danger"><i class="fa-brands fa-youtube fs-5"></i></span>
                            <input type="text" class="form-control" id="homeVideoYoutubeUrl" name="youtube_url" required placeholder="วางลิงก์ YouTube (เช่น https://www.youtube.com/watch?v=... หรือ ID)" oninput="previewHomeYoutubeInput(this.value)">
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="fa-solid fa-circle-info me-1"></i> รองรับทุกลิงก์ YouTube: youtube.com/watch?v=..., youtu.be/..., shorts/..., หรือพิมพ์เฉพาะ Video ID
                        </small>
                    </div>

                    <div id="homeYoutubePreviewWrap" class="mb-3 p-2 rounded-3 border bg-light d-none">
                        <span class="small fw-bold text-secondary d-block mb-1"><i class="fa-solid fa-circle-play text-danger me-1"></i> ตัวอย่างภาพหน้าปก</span>
                        <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm" style="max-height: 200px;">
                            <img id="homeYoutubeThumbImg" src="" alt="YouTube Preview" class="w-100 h-100 object-fit-cover">
                        </div>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">หมวดหมู่วิดีโอ</label>
                            <select class="form-select" id="homeVideoCategory" name="category">
                                <option value="ท่องเที่ยวและธรรมชาติ">ท่องเที่ยวและธรรมชาติ</option>
                                <option value="ศิลปวัฒนธรรมท้องถิ่น">ศิลปวัฒนธรรมท้องถิ่น</option>
                                <option value="ภารกิจและกิจกรรมจังหวัด">ภารกิจและกิจกรรมจังหวัด</option>
                                <option value="ส่งเสริมการท่องเที่ยว">ส่งเสริมการท่องเที่ยว</option>
                                <option value="ข่าวสารและสารคดีพิเศษ">ข่าวสารและสารคดีพิเศษ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">คำอธิบายสั้น</label>
                            <input type="text" class="form-control" id="homeVideoDesc" name="desc" placeholder="เช่น สารคดีเจาะลึกศิลปะการร่ายรำโนราห์">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger px-4 fw-bold" id="btnSaveHomeVideo" onclick="saveHomeVideo()">
                    <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let homeVideoModal;

function openHomeVideoAddModal() {
    if (!homeVideoModal) homeVideoModal = new bootstrap.Modal(document.getElementById('homeVideoModal'));
    document.getElementById('homeVideoForm').reset();
    document.getElementById('homeVideoId').value = '';
    document.getElementById('homeVideoModalTitle').innerHTML = '<i class="fa-solid fa-plus me-2"></i> เพิ่มวิดีโอ YouTube ใหม่';
    document.getElementById('homeYoutubePreviewWrap').classList.add('d-none');
    homeVideoModal.show();
}

function extractHomeYtId(url) {
    if (!url) return '';
    url = url.trim();
    if (/^[a-zA-Z0-9_-]{11}$/.test(url)) return url;
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|shorts\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : '';
}

function previewHomeYoutubeInput(val) {
    const yId = extractHomeYtId(val);
    const wrap = document.getElementById('homeYoutubePreviewWrap');
    const img = document.getElementById('homeYoutubeThumbImg');
    if (yId) {
        img.src = `https://img.youtube.com/vi/${yId}/hqdefault.jpg`;
        wrap.classList.remove('d-none');
    } else {
        wrap.classList.add('d-none');
    }
}

async function editHomeVideo(id) {
    if (!homeVideoModal) homeVideoModal = new bootstrap.Modal(document.getElementById('homeVideoModal'));
    try {
        const res = await App.fetch(`<?= base_url('admin/videos/get-item') ?>/${id}`);
        if (res && res.status === 'success') {
            const v = res.data;
            document.getElementById('homeVideoId').value = v.id;
            document.getElementById('homeVideoTitle').value = v.title;
            document.getElementById('homeVideoYoutubeUrl').value = v.youtube_id;
            document.getElementById('homeVideoCategory').value = v.category || 'ท่องเที่ยวและธรรมชาติ';
            document.getElementById('homeVideoDesc').value = v.desc || '';

            previewHomeYoutubeInput(v.youtube_id);
            document.getElementById('homeVideoModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2"></i> แก้ไขวิดีโอ: ' + v.title;
            homeVideoModal.show();
        } else {
            App.toast(res ? res.message : 'ไม่พบข้อมูลวิดีโอ', 'error');
        }
    } catch (err) {
        App.toast('ไม่สามารถโหลดข้อมูลวิดีโอได้', 'error');
    }
}

async function saveHomeVideo() {
    const form = document.getElementById('homeVideoForm');
    const title = document.getElementById('homeVideoTitle').value.trim();
    const ytInput = document.getElementById('homeVideoYoutubeUrl').value.trim();

    if (!title || !ytInput) {
        App.toast('กรุณากรอกชื่อวิดีโอและลิงก์ YouTube ให้ครบถ้วน', 'warning');
        return;
    }

    const yId = extractHomeYtId(ytInput);
    if (!yId) {
        App.toast('รูปแบบลิงก์ YouTube ไม่ถูกต้อง', 'warning');
        return;
    }

    const btn = document.getElementById('btnSaveHomeVideo');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    const formData = new FormData(form);

    try {
        const res = await App.fetch('<?= base_url("admin/videos/save-item") ?>', {
            method: 'POST',
            body: formData
        });

        if (res && res.status === 'success') {
            App.toast(res.message, 'success');
            homeVideoModal.hide();
            setTimeout(() => window.location.reload(), 800);
        } else {
            App.toast(res ? res.message : 'บันทึกข้อมูลไม่สำเร็จ', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = origText;
    }
}

async function deleteHomeVideo(id, title) {
    if (confirm(`คุณแน่ใจหรือไม่ที่จะลบวิดีโอ "${title}" ?`)) {
        try {
            const res = await App.fetch(`<?= base_url('admin/videos/delete-item') ?>/${id}`, {
                method: 'POST'
            });
            if (res && res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res ? res.message : 'ลบข้อมูลไม่สำเร็จ', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
        }
    }
}
</script>
<?php endif; ?>

<script>
// Filter News items inside Hub dynamically
function filterHomeNews(cat, btn) {
    document.querySelectorAll('.news-cat-btn').forEach(b => {
        b.classList.remove('active');
    });
    btn.classList.add('active');

    document.querySelectorAll('.news-card-item').forEach(item => {
        if (cat === 'all' || item.getAttribute('data-category') === cat) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

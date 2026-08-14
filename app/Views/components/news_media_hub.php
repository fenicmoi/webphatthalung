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
.news-media-tab-trigger {
    color: var(--text-secondary) !important;
    background: transparent !important;
    border: none !important;
    padding: 10px 22px !important;
    font-weight: 700 !important;
    font-size: 0.95rem;
    transition: all 0.25s ease !important;
    border-radius: 50px !important;
}
.news-media-tab-trigger.active {
    color: #ffffff !important;
    background: linear-gradient(135deg, #0284c7, #0369a1) !important;
    box-shadow: 0 4px 15px rgba(2, 132, 199, 0.35) !important;
}
[data-theme="dark"] .news-media-tab-trigger.active {
    color: #ffffff !important;
}
.news-cat-btn {
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-secondary) !important;
    transition: all 0.2s ease;
}
.news-cat-btn:hover, .news-cat-btn.active {
    background: var(--accent-primary) !important;
    color: #0f172a !important;
    font-weight: 700;
}
.news-read-more-link {
    color: var(--accent-primary) !important;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.2s ease;
}
.news-read-more-link:hover {
    transform: translateX(4px);
}
</style>

<section id="news-media-hub" class="my-5 py-4">
    <div class="glass-card p-4 p-md-5" style="border-radius: 28px; border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
        
        <!-- Hub Header & Segmented Switcher -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom" style="border-color: var(--glass-border) !important;">
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

            <!-- Tab Navigation Pills -->
            <ul class="nav nav-pills rounded-pill p-1 bg-light border d-inline-flex align-items-center gap-1" id="newsMediaHubTabs" role="tablist" style="background: rgba(255, 255, 255, 0.06) !important; border-color: var(--glass-border) !important;">
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
            <div class="tab-pane fade" id="tab-videos" role="tabpanel" aria-labelledby="tab-videos-trigger">
                <?php if ($isOfficer): ?>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" onclick="window.location.href='<?= base_url('videos') ?>'" class="btn btn-xs btn-warning rounded-pill px-4 py-2 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> จัดการวิดีโอ (Studio)
                        </button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <?php if (empty($homeVideos)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa-solid fa-video-slash fs-1 text-muted mb-3"></i>
                            <p class="text-muted m-0">ยังไม่มีวิดีโอประชาสัมพันธ์ในระบบขณะนี้</p>
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
                        ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-transform hover-scale d-flex flex-column justify-content-between" onclick="SmartHomeCinema.play('<?= $yId ?>', '<?= addslashes($vid['title']) ?>', '<?= $vid['id'] ?>')" style="cursor: pointer; background: var(--card-bg, #ffffff); border: 1px solid rgba(225, 29, 72, 0.2) !important;">
                                    <div>
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

                                    <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between text-muted small" style="border-color: rgba(0,0,0,0.06) !important;">
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

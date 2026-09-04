<?php
// =========================================================================
// ศาลาประชาสัมพันธ์และสื่อเมืองลุง (News, Events, Photo Gallery & Videos Hub)
// =========================================================================
$localNews = function_exists('get_site_news') ? get_site_news(12, null, true) : [];
$prdNews = [];
try {
    if (class_exists('\App\Libraries\PrdNewsService')) {
        $prdNews = \App\Libraries\PrdNewsService::getPhatthalungNews(12);
    }
} catch (\Throwable $e) {
    $prdNews = [];
}
$homeNews = array_merge($localNews, $prdNews);
usort($homeNews, function ($a, $b) {
    $tA = strtotime($a['created_at'] ?? '2026-01-01');
    $tB = strtotime($b['created_at'] ?? '2026-01-01');
    return $tB <=> $tA;
});
$homeNews = array_slice($homeNews, 0, 6);
$newsCats = function_exists('get_news_categories') ? get_news_categories() : [];
$prdCatName = 'ข่าวประชาสัมพันธ์ (สปชส.พัทลุง)';
if (!in_array($prdCatName, $newsCats, true)) {
    $newsCats[] = $prdCatName;
}
$homeEvents = function_exists('get_site_events') ? get_site_events(true) : [];
$homeGalleryAlbums = function_exists('get_gallery_albums') ? get_gallery_albums(4, null, true) : [];
$totalAlbums = function_exists('get_gallery_albums') ? count(get_gallery_albums()) : 0;
$homeVideos = function_exists('get_site_videos') ? get_site_videos(3, null, true) : [];
$isOfficer = false;
try {
    $isOfficer = (bool)session()->get('isLoggedIn');
} catch (\Throwable $e) {
    $isOfficer = false;
}

?>

<style>
/* ==========================================================================
   Clean Editorial News & Media Hub (Calm, Readable, Emerald-Themed)
   ========================================================================== */

/* 1. Main Navigation Tabs (Underline Minimalist) */
.news-media-tab-trigger {
    color: #64748b !important;
    background: transparent !important;
    border: none !important;
    padding: 10px 4px !important;
    margin-right: 2rem !important;
    font-weight: 500 !important;
    font-size: 1.02rem;
    transition: all 0.2s ease !important;
    border-radius: 0 !important;
    border-bottom: 2px solid transparent !important;
    white-space: nowrap;
}
.news-media-tab-trigger:hover {
    color: #047857 !important;
    border-bottom-color: rgba(4, 120, 87, 0.3) !important;
}
.news-media-tab-trigger.active {
    color: #047857 !important;
    border-bottom: 2px solid #047857 !important;
    font-weight: 700 !important;
}

[data-theme="dark"] .news-media-tab-trigger {
    color: #94a3b8 !important;
}
[data-theme="dark"] .news-media-tab-trigger.active {
    color: #34d399 !important;
    border-bottom-color: #34d399 !important;
}

/* 2. Category Sub-tabs - Soft Pill Chips */
.news-cat-btn {
    border: 1px solid #e2e8f0 !important;
    background: #f8fafc;
    color: #475569 !important;
    transition: all 0.2s ease;
    font-weight: 500;
    padding: 6px 16px !important;
    font-size: 0.85rem !important;
    border-radius: 50px !important;
}
.news-cat-btn:hover {
    background: #ecfdf5 !important;
    color: #047857 !important;
    border-color: #a7f3d0 !important;
}
.news-cat-btn.active {
    background: #047857 !important;
    color: #ffffff !important;
    font-weight: 600;
    border-color: #047857 !important;
    box-shadow: 0 4px 12px rgba(4, 120, 87, 0.2) !important;
}

[data-theme="dark"] .news-cat-btn {
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    background: rgba(255, 255, 255, 0.05);
    color: #cbd5e1 !important;
}
[data-theme="dark"] .news-cat-btn.active {
    background: #059669 !important;
    color: #ffffff !important;
    border-color: #059669 !important;
}

/* 3. News Card System (Calm, Flat & Non-Distracting) */
.gov-news-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #edf2f7;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.gov-news-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px -6px rgba(4, 120, 87, 0.1);
    border-color: rgba(16, 185, 129, 0.35);
}

.gov-news-img-wrap {
    height: 185px;
    background: #f1f5f9;
    overflow: hidden;
    position: relative;
}
.gov-news-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s ease;
}
.gov-news-card:hover .gov-news-img-wrap img {
    transform: scale(1.04);
}

.gov-news-title {
    color: #1e293b;
    font-weight: 600;
    font-size: 1.05rem;
    line-height: 1.5;
    transition: color 0.2s ease;
    margin-bottom: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3rem;
}
.gov-news-card:hover .gov-news-title {
    color: #047857;
}

.gov-news-meta {
    color: #64748b;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.gov-news-cat-tag {
    font-size: 0.75rem;
    font-weight: 600;
    color: #047857;
    background: #ecfdf5;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
}

[data-theme="dark"] .gov-news-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.08);
}
[data-theme="dark"] .gov-news-title {
    color: #f8fafc;
}
[data-theme="dark"] .gov-news-card:hover .gov-news-title {
    color: #34d399;
}
[data-theme="dark"] .gov-news-cat-tag {
    background: rgba(16, 185, 129, 0.2);
    color: #34d399;
}

/* 4. Hub Interactive Calendar Styling (High Dimension & Equal 7-Column Layout) */
.hub-calendar-wrapper {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #cbd5e1;
    overflow: hidden;
    width: 100%;
    box-shadow: 0 10px 30px -5px rgba(2, 44, 34, 0.08), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
}
.hub-cal-weekdays {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    background: linear-gradient(135deg, #022c22 0%, #064e3b 55%, #047857 100%);
    border-bottom: 2px solid #10b981;
    text-align: center;
    font-weight: 700;
    font-size: 0.9rem;
    color: #ffffff;
    padding: 12px 0;
    width: 100%;
    box-shadow: inset 0 -2px 6px rgba(0, 0, 0, 0.12);
}
.hub-cal-weekdays div {
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    letter-spacing: 0.2px;
}
.hub-cal-weekdays div:first-child {
    color: #fca5a5; /* Sunday */
}
.hub-cal-weekdays div:last-child {
    color: #86efac; /* Saturday */
}
.hub-cal-days-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    background: #e2e8f0;
    gap: 1px;
    width: 100%;
}
.hub-cal-cell {
    min-height: 110px;
    background: #ffffff;
    padding: 8px 10px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    gap: 5px;
    min-width: 0;
    overflow: hidden;
}
.hub-cal-cell:hover {
    background: #f0fdf4;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(4, 120, 87, 0.15);
    z-index: 5;
}
.hub-cal-cell.other-month {
    background: #fafafa;
    opacity: 0.35;
}
.hub-cal-cell.today {
    background: #ecfdf5;
    border: 2px solid #047857;
}
.hub-cal-cell.today .hub-cal-day-num {
    background: linear-gradient(135deg, #059669, #047857);
    color: #ffffff;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(4, 120, 87, 0.35);
}
.hub-cal-day-num {
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
    flex-shrink: 0;
}
.hub-cal-event-pill {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border: 1px solid rgba(16, 185, 129, 0.45);
    color: #065f46;
    font-size: 0.78rem;
    font-weight: 600;
    border-radius: 6px;
    padding: 4px 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    max-width: 100%;
    width: 100%;
    min-width: 0;
    line-height: 1.3;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(4, 120, 87, 0.08);
}
.hub-cal-event-pill:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(4, 120, 87, 0.25);
}

[data-theme="dark"] .hub-calendar-wrapper {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.08);
}
[data-theme="dark"] .hub-cal-weekdays {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-color: rgba(255, 255, 255, 0.08);
}
[data-theme="dark"] .hub-cal-days-grid {
    background: rgba(255, 255, 255, 0.08);
}
[data-theme="dark"] .hub-cal-cell {
    background: #1e293b;
}
[data-theme="dark"] .hub-cal-cell:hover {
    background: rgba(16, 185, 129, 0.15);
}
[data-theme="dark"] .hub-cal-cell.other-month {
    background: rgba(15, 23, 42, 0.5);
}
[data-theme="dark"] .hub-cal-day-num {
    color: #cbd5e1;
}

/* News Hub Container with Mathematical Seamless Woven Pattern */
.news-media-hub-card {
    position: relative;
    border-radius: 24px;
    border: 1.5px solid #d1e7dd !important;
    background-color: #f6faf7;
    background-image: 
        linear-gradient(180deg, rgba(255, 255, 255, 0.80) 0%, rgba(255, 255, 255, 0.90) 100%),
        url('<?= base_url('assets/images/banners/phatthalung_woven_pattern.svg?v=3') ?>');
    background-size: 80px 80px;
    background-repeat: repeat;
    background-position: top center;
    box-shadow: 0 10px 30px rgba(4, 120, 87, 0.06);
    overflow: hidden;
}

[data-theme="dark"] .news-media-hub-card {
    background-color: #0f172a;
    background-image: 
        linear-gradient(180deg, rgba(15, 23, 42, 0.86) 0%, rgba(15, 23, 42, 0.93) 100%),
        url('<?= base_url('assets/images/banners/phatthalung_woven_pattern.svg?v=3') ?>');
    background-size: 80px 80px;
    background-repeat: repeat;
    background-position: top center;
    border-color: rgba(255, 255, 255, 0.12) !important;
}
</style>

<section id="news-media-hub" class="my-5 py-2">
    <div class="card news-media-hub-card border-0 p-4 p-lg-5 shadow-sm">
        
        <!-- Hub Header & Main Tabs -->
        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <h3 class="fw-bold mb-0 d-flex align-items-center gap-2.5" style="color: var(--text-primary);">
                    <img src="<?= base_url('assets/images/phatthalung_fabric_emblem.svg') ?>" alt="ลายผ้าประจำจังหวัดพัทลุง" style="width: 30px; height: 36px; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));">
                    <span><?= site_text('news_section_title', 'ข่าวสารและประชาสัมพันธ์', 'หัวข้อส่วนข่าวสาร') ?></span>
                    <?php if ($isOfficer): ?>
                        <span class="badge bg-success bg-opacity-25 text-success px-2 py-1 fs-6"><i class="fa-solid fa-user-shield me-1"></i>แอดมิน</span>
                    <?php endif; ?>
                </h3>
            </div>

            <!-- Tab Navigation (Clean Underline Style) -->
            <ul class="nav nav-underline-custom border-bottom" id="newsMediaHubTabs" role="tablist" style="border-color: rgba(0,0,0,0.06) !important;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active news-media-tab-trigger" id="tab-pr-news-trigger" data-bs-toggle="pill" data-bs-target="#tab-pr-news" type="button" role="tab" aria-controls="tab-pr-news" aria-selected="true">
                        ข่าวประชาสัมพันธ์
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link news-media-tab-trigger" id="tab-calendar-trigger" data-bs-toggle="pill" data-bs-target="#tab-calendar" type="button" role="tab" aria-controls="tab-calendar" aria-selected="false">
                        ปฏิทินตารางงาน
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link news-media-tab-trigger" id="tab-gallery-trigger" data-bs-toggle="pill" data-bs-target="#tab-gallery" type="button" role="tab" aria-controls="tab-gallery" aria-selected="false">
                        คลังภาพกิจกรรม
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link news-media-tab-trigger" id="tab-videos-trigger" data-bs-toggle="pill" data-bs-target="#tab-videos" type="button" role="tab" aria-controls="tab-videos" aria-selected="false">
                        วิดีทัศน์ Web TV
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
                        <button type="button" onclick="NewsStudio.addCategory()" class="btn btn-xs btn-outline-success py-2 px-3 rounded-pill fw-bold">
                            <i class="fa-solid fa-tags"></i> เพิ่มหมวดหมู่ใหม่
                        </button>
                        <button type="button" onclick="NewsStudio.open()" class="btn btn-xs btn-success py-2 px-4 rounded-pill fw-bold text-white shadow-xs" style="background: #047857; border: none;">
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
                        $displayCatName = (strcasecmp(trim($cat), 'general') === 0) ? 'ข่าวทั่วไป' : $cat;
                        $isPrdCatChip = (mb_stripos($cat, 'สปชส') !== false || mb_stripos($cat, 'สำนักงานประชาสัมพันธ์') !== false);
                    ?>
                        <button class="btn btn-xs px-4 py-2 news-cat-btn rounded-pill <?= ($countInCat === 0) ? 'opacity-50' : '' ?>" data-filter="<?= esc($cat) ?>" onclick="filterHomeNews('<?= esc($cat) ?>', this)">
                            <?php if ($isPrdCatChip): ?><i class="fa-solid fa-bullhorn text-success me-1"></i><?php endif; ?>
                            <?= esc($displayCatName) ?> (<?= $countInCat ?>)
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- News Cards Grid -->
                <div class="row g-4" id="newsGridContainer">
                    <?php if (empty($homeNews)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa-regular fa-folder-open fs-1 text-muted mb-3"></i>
                            <p class="text-muted m-0">ยังไม่มีข้อมูลข่าวสารในระบบขณะนี้</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($homeNews as $item): 
                            $rawCat = trim($item['category'] ?? 'ข่าวทั่วไป');
                            $catLabel = (strcasecmp($rawCat, 'general') === 0 || empty($rawCat)) ? 'ข่าวทั่วไป' : $rawCat;
                            $isPrd = !empty($item['is_prd']);
                            
                            $coverImg = !empty($item['cover_image']) ? ((strpos($item['cover_image'], 'http') === 0 || strpos($item['cover_image'], 'data:') === 0 || strpos($item['cover_image'], 'uploads/') === 0) ? ((strpos($item['cover_image'], 'http') === 0) ? $item['cover_image'] : base_url($item['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png')) : base_url('assets/images/slider/sane_muanglung.png');
                            
                            $attachCount = !empty($item['attachments']) ? count($item['attachments']) : 0;
                            $newsDate = !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : (!empty($item['display_date']) ? $item['display_date'] : date('d/m/Y'));
                            $viewsCount = number_format($item['views'] ?? 0);
                            $sourceName = $item['source'] ?? '';
                        ?>
                            <div class="col-md-6 col-lg-4 news-card-item" data-category="<?= esc($rawCat) ?>">
                                <div class="gov-news-card">
                                    
                                    <!-- Image Header (Clean & Uncluttered) -->
                                    <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="d-block gov-news-img-wrap position-relative">
                                        <img src="<?= $coverImg ?>" alt="<?= esc($item['title']) ?>" loading="lazy">
                                    </a>

                                    <!-- Content Body -->
                                    <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <!-- Meta Row -->
                                            <div class="gov-news-meta mb-2.5">
                                                <span><i class="fa-regular fa-calendar me-1"></i><?= $newsDate ?></span>
                                                <span><i class="fa-regular fa-eye me-1"></i><?= $viewsCount ?></span>
                                            </div>

                                            <!-- News Title -->
                                            <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="text-decoration-none d-block">
                                                <h5 class="gov-news-title">
                                                    <?= esc($item['title']) ?>
                                                </h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- View More News Button -->
                <div class="text-center mt-5">
                    <a href="<?= base_url('news') ?>" class="btn btn-outline-success rounded-pill px-5 py-2.5 fw-bold shadow-xs d-inline-flex align-items-center gap-2 hover-scale" style="font-size: 0.95rem; border-width: 1.5px; color: #047857; border-color: #047857; transition: all 0.25s ease;">
                        <i class="fa-solid fa-newspaper text-success"></i>
                        <span>ดูข่าวประชาสัมพันธ์ทั้งหมด</span>
                        <i class="fa-solid fa-arrow-right-long ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- Tab 2: ปฏิทินตารางงาน (Interactive Calendar & Event Summary Inspector) -->
            <div class="tab-pane fade" id="tab-calendar" role="tabpanel" aria-labelledby="tab-calendar-trigger">
                
                <!-- Calendar Toolbar -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px; border-color: #047857; color: #047857;" onclick="HubCalendar.prevMonth()" title="เดือนก่อนหน้า">
                            <i class="fa-solid fa-chevron-left" style="font-size: 0.8rem;"></i>
                        </button>
                        <h5 class="fw-bold m-0 px-2 text-dark" id="hubCalMonthTitle" style="min-width: 170px; text-align: center; font-size: 1.15rem; color: #047857 !important;">
                            สิงหาคม 2569
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-success rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px; border-color: #047857; color: #047857;" onclick="HubCalendar.nextMonth()" title="เดือนถัดไป">
                            <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem;"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3 py-1 ms-1 fw-semibold text-success border border-success border-opacity-25" onclick="HubCalendar.goToToday()">
                            วันนี้
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <!-- Voice TTS Monthly Narration Button -->
                        <button type="button" id="btnHubCalSpeak" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2 shadow-xs hover-scale" onclick="HubCalendar.toggleSpeakMonthlySchedule()">
                            <i class="fa-solid fa-volume-high text-success" id="hubCalSpeakIcon"></i>
                            <span id="hubCalSpeakText">ฟังเสียงกำหนดการเดือนนี้</span>
                        </button>
                        <?php if ($isOfficer): ?>
                            <a href="<?= base_url('calendar') ?>" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-bold text-white shadow-xs" style="background: #047857; border: none;">
                                <i class="fa-solid fa-calendar-plus me-1"></i> จัดการปฏิทิน (Studio)
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Full-Width Responsive Monthly Calendar Grid -->
                <div class="hub-calendar-wrapper">
                    <div class="hub-cal-weekdays">
                        <div>อาทิตย์</div><div>จันทร์</div><div>อังคาร</div><div>พุธ</div><div>พฤหัสบดี</div><div>ศุกร์</div><div>เสาร์</div>
                    </div>
                    <div id="hubCalDaysGrid" class="hub-cal-days-grid">
                        <!-- Rendered dynamically by HubCalendar.renderGrid() -->
                    </div>
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

// Interactive Hub Calendar & Event Summary Engine
var ALL_HUB_EVENTS = <?= json_encode($homeEvents) ?>;

var HubCalendar = {
    currentDate: new Date(),
    selectedDateStr: '',
    events: [],
    isSpeaking: false,

    init: function() {
        this.events = Array.isArray(ALL_HUB_EVENTS) ? ALL_HUB_EVENTS : [];
        if (typeof SmartEventViewer !== 'undefined' && SmartEventViewer.registerEvents) {
            SmartEventViewer.registerEvents(this.events);
        }

        if (this.events.length > 0) {
            var firstEventDate = this.events[0].event_start_date;
            if (firstEventDate) {
                var d = new Date(firstEventDate);
                if (!isNaN(d.getTime())) {
                    this.currentDate = new Date(d.getFullYear(), d.getMonth(), 1);
                    this.selectedDateStr = firstEventDate.split(' ')[0].split('T')[0];
                }
            }
        }

        if (!this.selectedDateStr) {
            var today = new Date();
            this.selectedDateStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
        }

        this.render();
    },

    prevMonth: function() {
        this.stopSpeaking();
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
    },

    nextMonth: function() {
        this.stopSpeaking();
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
    },

    goToToday: function() {
        this.stopSpeaking();
        this.currentDate = new Date();
        var today = new Date();
        this.selectedDateStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
        this.render();
    },

    selectDate: function(dateStr) {
        this.selectedDateStr = dateStr;
        this.renderGrid();
        this.renderSelectedDay();
    },

    getEventsForDate: function(dateStr) {
        return this.events.filter(function(ev) {
            if (!ev.event_start_date) return false;
            var s = ev.event_start_date.split(' ')[0].split('T')[0];
            var e = ev.event_end_date ? ev.event_end_date.split(' ')[0].split('T')[0] : s;
            return dateStr >= s && dateStr <= e;
        });
    },

    formatThaiDate: function(dateStr) {
        if (!dateStr) return '';
        var parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        var thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        var d = parseInt(parts[2], 10);
        var m = parseInt(parts[1], 10) - 1;
        var y = parseInt(parts[0], 10) + 543;
        return d + ' ' + (thaiMonths[m] || '') + ' ' + y;
    },

    render: function() {
        var year = this.currentDate.getFullYear();
        var month = this.currentDate.getMonth();

        var thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];
        var titleEl = document.getElementById('hubCalMonthTitle');
        if (titleEl) {
            titleEl.textContent = (thaiMonths[month] || '') + ' ' + (year + 543);
        }

        this.renderGrid();
        this.renderSelectedDay();
    },

    renderGrid: function() {
        var gridEl = document.getElementById('hubCalDaysGrid');
        if (!gridEl) return;

        var year = this.currentDate.getFullYear();
        var month = this.currentDate.getMonth();

        var firstDayIndex = new Date(year, month, 1).getDay();
        var daysInMonth = new Date(year, month + 1, 0).getDate();
        var daysInPrevMonth = new Date(year, month, 0).getDate();

        var totalCells = Math.ceil((firstDayIndex + daysInMonth) / 7) * 7;
        var html = '';

        var realToday = new Date();
        var todayStr = realToday.getFullYear() + '-' + String(realToday.getMonth() + 1).padStart(2, '0') + '-' + String(realToday.getDate()).padStart(2, '0');

        for (var i = 0; i < totalCells; i++) {
            var cellDay = 0;
            var cellMonth = month;
            var cellYear = year;
            var isOtherMonth = false;

            if (i < firstDayIndex) {
                cellDay = daysInPrevMonth - (firstDayIndex - i) + 1;
                cellMonth = month - 1;
                if (cellMonth < 0) { cellMonth = 11; cellYear = year - 1; }
                isOtherMonth = true;
            } else if (i >= firstDayIndex + daysInMonth) {
                cellDay = i - (firstDayIndex + daysInMonth) + 1;
                cellMonth = month + 1;
                if (cellMonth > 11) { cellMonth = 0; cellYear = year + 1; }
                isOtherMonth = true;
            } else {
                cellDay = i - firstDayIndex + 1;
            }

            var cellDateStr = cellYear + '-' + String(cellMonth + 1).padStart(2, '0') + '-' + String(cellDay).padStart(2, '0');
            var isToday = (!isOtherMonth && cellDateStr === todayStr);

            var cellClass = 'hub-cal-cell' + (isOtherMonth ? ' other-month' : '') + (isToday ? ' today' : '');

            var dayEvents = this.getEventsForDate(cellDateStr);
            var clickAction = dayEvents.length > 0 ? `onclick="SmartEventViewer.open('${dayEvents[0].id}')"` : '';

            html += `<div class="${cellClass}" ${clickAction}>`;
            html += `<div class="d-flex align-items-center justify-content-between mb-1">`;
            html += `<span class="hub-cal-day-num">${cellDay}</span>`;
            if (dayEvents.length > 0) {
                html += `<span class="badge bg-success bg-opacity-20 text-success rounded-pill" style="font-size: 0.72rem; padding: 2px 7px;">${dayEvents.length} กิจกรรม</span>`;
            }
            html += `</div>`;

            if (dayEvents.length > 0) {
                dayEvents.forEach(function(ev) {
                    var safeTitle = (ev.title || 'กิจกรรม').replace(/"/g, '&quot;');
                    html += `<div class="hub-cal-event-pill" onclick="event.stopPropagation(); SmartEventViewer.open('${ev.id}')" title="${safeTitle}">`;
                    html += `<i class="fa-solid fa-calendar-check text-success me-1" style="font-size: 0.7rem;"></i> <span>${ev.title}</span>`;
                    html += `</div>`;
                });
            }

            html += `</div>`;
        }

        gridEl.innerHTML = html;
    },

    toggleSpeakMonthlySchedule: function() {
        if (this.isSpeaking) {
            this.stopSpeaking();
            return;
        }

        if (!('speechSynthesis' in window)) {
            alert('เบราว์เซอร์ของท่านไม่รองรับระบบอ่านออกเสียง (Speech Synthesis)');
            return;
        }

        window.speechSynthesis.cancel();

        var monthTitle = document.getElementById('hubCalMonthTitle') ? document.getElementById('hubCalMonthTitle').textContent.trim() : '';
        var year = this.currentDate.getFullYear();
        var month = this.currentDate.getMonth();

        // Get all events that fall in this month
        var monthEvents = this.events.filter(function(ev) {
            if (!ev.event_start_date) return false;
            var d = new Date(ev.event_start_date);
            return d.getFullYear() === year && d.getMonth() === month;
        });

        var speechText = 'ปฏิทินกิจกรรมจังหวัดพัทลุง ประจำเดือน ' + monthTitle + ' ';
        if (monthEvents.length === 0) {
            speechText += 'ไม่มีกำหนดการกิจกรรมในเดือนนี้';
        } else {
            speechText += 'มีทั้งหมด ' + monthEvents.length + ' กิจกรรม ได้แก่ ';
            monthEvents.forEach(function(ev, idx) {
                var sDate = ev.event_start_date ? HubCalendar.formatThaiDate(ev.event_start_date.split(' ')[0]) : '';
                var loc = ev.event_location ? (' ณ ' + ev.event_location) : '';
                speechText += 'ลำดับที่ ' + (idx + 1) + ' ' + ev.title + ' วันที่ ' + sDate + loc + ' ';
            });
        }

        var utter = new SpeechSynthesisUtterance(speechText);
        utter.lang = 'th-TH';
        utter.rate = 1.0;

        var voices = window.speechSynthesis.getVoices();
        var thVoice = voices.find(function(v) { return v.lang === 'th-TH' || v.lang === 'th_TH' || v.lang.startsWith('th'); });
        if (thVoice) utter.voice = thVoice;

        var self = this;
        utter.onstart = function() {
            self.isSpeaking = true;
            var btn = document.getElementById('btnHubCalSpeak');
            var icon = document.getElementById('hubCalSpeakIcon');
            var text = document.getElementById('hubCalSpeakText');
            if (btn) {
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-danger', 'text-white');
            }
            if (icon) icon.className = 'fa-solid fa-stop text-white';
            if (text) text.innerText = 'หยุดฟังเสียง';
        };

        utter.onend = utter.onerror = function() {
            self.stopSpeaking();
        };

        window.speechSynthesis.speak(utter);
    },

    stopSpeaking: function() {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
        this.isSpeaking = false;
        var btn = document.getElementById('btnHubCalSpeak');
        var icon = document.getElementById('hubCalSpeakIcon');
        var text = document.getElementById('hubCalSpeakText');
        if (btn) {
            btn.classList.remove('btn-danger', 'text-white');
            btn.classList.add('btn-outline-success');
        }
        if (icon) icon.className = 'fa-solid fa-volume-high text-success';
        if (text) text.innerText = 'ฟังเสียงกำหนดการเดือนนี้';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    HubCalendar.init();
    
    var calTabTrigger = document.getElementById('tab-calendar-trigger');
    if (calTabTrigger) {
        calTabTrigger.addEventListener('shown.bs.tab', function () {
            HubCalendar.render();
        });
    }
});
</script>

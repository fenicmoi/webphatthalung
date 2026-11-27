<?php
// =========================================================================
// ศาลาประชาสัมพันธ์และสื่อเมืองลุง (News, Events, Photo Gallery & Videos Hub)
// ดีไซน์ใหม่: แยก Section ออกมาแสดงผลอย่างชัดเจน เด่นสะดุดตาทุกหมวดหมู่ (No Hidden Tabs)
// =========================================================================
$allPublishedNews = function_exists('get_site_news') ? get_site_news(null, null, true) : [];
$allPublishedNews = is_array($allPublishedNews) ? $allPublishedNews : [];

// คัดกรองข่าวที่ถูกกำหนดเป็น "หัวข้อเด่น" (Featured News)
$featuredNews = array_values(array_filter($allPublishedNews, function($item) {
    return !empty($item['is_featured']);
}));

// หากมีหัวข้อเด่นที่กำหนดไว้ ให้นำเฉพาะหัวข้อเด่นมาแสดงที่หน้าหลัก
if (!empty($featuredNews)) {
    $homeNews = array_slice($featuredNews, 0, 6);
    $isFeaturedMode = true;
} else {
    $homeNews = array_slice($allPublishedNews, 0, 6);
    $isFeaturedMode = false;
}
$newsCats = function_exists('get_news_categories') ? get_news_categories() : [];
$newsCats = is_array($newsCats) ? $newsCats : [];
$homeEvents = function_exists('get_site_events') ? get_site_events(true) : [];
$homeEvents = is_array($homeEvents) ? $homeEvents : [];
$homeGalleryAlbums = function_exists('get_gallery_albums') ? get_gallery_albums(4, null, true) : [];
$homeGalleryAlbums = is_array($homeGalleryAlbums) ? $homeGalleryAlbums : [];
$totalAlbums = function_exists('get_gallery_albums') ? count(get_gallery_albums()) : 0;
$homeVideos = function_exists('get_site_videos') ? get_site_videos(3, null, true) : [];
$homeVideos = is_array($homeVideos) ? $homeVideos : [];
$isOfficer = false;
try {
    $isOfficer = (bool)session()->get('isLoggedIn');
} catch (\Throwable $e) {
    $isOfficer = false;
}

?>

<style>
/* ==========================================================================
   Clean Editorial News, Calendar & Multimedia Styling (Emerald GovTech Theme)
   ========================================================================== */

/* 1. Category Sub-tabs - Sleek Pill Chips */
.news-cat-btn {
    border: 1px solid rgba(16, 185, 129, 0.2) !important;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    color: #334155 !important;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    font-weight: 500;
    padding: 7px 18px !important;
    font-size: 0.86rem !important;
    border-radius: 50px !important;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
}
.news-cat-btn:hover {
    background: #ecfdf5 !important;
    color: #047857 !important;
    border-color: #059669 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(4, 120, 87, 0.12) !important;
}
.news-cat-btn.active {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    color: #ffffff !important;
    font-weight: 600;
    border-color: transparent !important;
    box-shadow: 0 6px 18px rgba(4, 120, 87, 0.28) !important;
    transform: translateY(-1px);
}

[data-theme="dark"] .news-cat-btn {
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    background: rgba(30, 41, 59, 0.8);
    color: #cbd5e1 !important;
}
[data-theme="dark"] .news-cat-btn:hover {
    background: rgba(16, 185, 129, 0.15) !important;
    color: #34d399 !important;
    border-color: #10b981 !important;
}
[data-theme="dark"] .news-cat-btn.active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: #ffffff !important;
    border-color: transparent !important;
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.3) !important;
}

/* 2. News Card System (Elevated GovTech Architecture) */
.gov-news-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid rgba(226, 232, 240, 0.85);
    box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
}
.gov-news-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 38px -10px rgba(4, 120, 87, 0.16), 0 8px 16px -4px rgba(0, 0, 0, 0.04);
    border-color: rgba(16, 185, 129, 0.45);
}

.gov-news-img-wrap {
    height: 195px;
    background: #f1f5f9;
    overflow: hidden;
    position: relative;
}
.gov-news-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
}
.gov-news-card:hover .gov-news-img-wrap img {
    transform: scale(1.06);
}

.gov-news-img-wrap::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 55%, rgba(0,0,0,0.3) 100%);
    pointer-events: none;
}

.gov-news-floating-cat {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 2;
    background: rgba(15, 23, 42, 0.72);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: #ffffff;
    font-size: 0.73rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 50px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    letter-spacing: 0.2px;
}

.gov-news-title {
    color: #1e293b;
    font-weight: 700;
    font-size: 1.05rem;
    line-height: 1.5;
    transition: color 0.2s ease;
    margin-bottom: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 3.1rem;
}
.gov-news-card:hover .gov-news-title {
    color: #047857;
}

.gov-news-meta {
    color: #64748b;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.85rem;
}
.gov-news-meta span {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    padding: 3px 8px;
    border-radius: 6px;
    font-weight: 500;
}

[data-theme="dark"] .gov-news-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.08);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}
[data-theme="dark"] .gov-news-title {
    color: #f8fafc;
}
[data-theme="dark"] .gov-news-card:hover .gov-news-title {
    color: #34d399;
}
[data-theme="dark"] .gov-news-meta {
    color: #94a3b8;
}
[data-theme="dark"] .gov-news-meta span {
    background: rgba(15, 23, 42, 0.6);
    color: #cbd5e1;
}

/* 3. Hub Interactive Calendar Styling */
.hub-calendar-wrapper {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
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

/* Visual Event Spotlight Cards Styling */
.gov-visual-event-card {
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
}
.gov-visual-event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 35px rgba(4, 120, 87, 0.16) !important;
    border-color: #10b981 !important;
}
.gov-visual-event-card .event-zoom-img {
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1) !important;
}
.gov-visual-event-card:hover .event-zoom-img {
    transform: scale(1.08);
}
.gov-visual-event-card .event-arrow-icon {
    transition: transform 0.25s ease;
}
.gov-visual-event-card:hover .event-arrow-icon {
    transform: translateX(4px);
}
.gov-visual-event-card:hover .event-title-hover {
    color: #047857 !important;
}
.gov-visual-event-card .event-glass-date {
    transition: transform 0.25s ease;
}
.gov-visual-event-card:hover .event-glass-date {
    transform: scale(1.06);
}

/* 4. Section Card Containers (Ambient Mesh & Glassmorphism) */
.gov-section-card {
    position: relative;
    border-radius: 28px;
    border: 1.5px solid rgba(16, 185, 129, 0.22) !important;
    background-color: #ffffff;
    background-image: 
        radial-gradient(1000px circle at 0% 0%, rgba(16, 185, 129, 0.08) 0%, transparent 60%),
        radial-gradient(900px circle at 100% 100%, rgba(5, 150, 105, 0.06) 0%, transparent 55%),
        radial-gradient(700px circle at 50% 25%, rgba(240, 253, 244, 0.75) 0%, transparent 70%),
        linear-gradient(180deg, #ffffff 0%, #fbfdfc 100%);
    box-shadow: 0 24px 60px -15px rgba(2, 44, 34, 0.09), 0 4px 16px -2px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    backdrop-filter: blur(16px);
}
.gov-section-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #059669 0%, #10b981 50%, #34d399 100%);
    border-radius: 28px 28px 0 0;
    z-index: 1;
}

[data-theme="dark"] .gov-section-card {
    background-color: #0f172a;
    background-image: 
        radial-gradient(1000px circle at 0% 0%, rgba(16, 185, 129, 0.14) 0%, transparent 60%),
        radial-gradient(900px circle at 100% 100%, rgba(5, 150, 105, 0.1) 0%, transparent 55%),
        linear-gradient(180deg, #0f172a 0%, #111e33 100%);
    border-color: rgba(16, 185, 129, 0.25) !important;
    box-shadow: 0 24px 60px -15px rgba(0, 0, 0, 0.6);
}

.section-icon-box {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
}
</style>

<!-- =========================================================================
     SECTION 1: ข่าวสารและประชาสัมพันธ์ (News & Announcements)
     ========================================================================= -->
<section id="news-media-hub" class="my-5 py-2">
    <div class="card gov-section-card border-0 p-4 p-lg-5 shadow-sm">
        
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2.5" style="color: var(--text-primary);">
                        <img src="<?= base_url('assets/images/phatthalung_fabric_emblem.svg') ?>" alt="ลายผ้าประจำจังหวัดพัทลุง" style="width: 32px; height: 38px; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));">
                        <span><?= site_text('news_section_title', 'ข่าวสารและประชาสัมพันธ์', 'หัวข้อส่วนข่าวสาร') ?></span>
                        <?php if ($isFeaturedMode): ?>
                            <span class="badge rounded-pill px-3 py-1.5 text-dark shadow-xs" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); font-size: 0.8rem;">
                                <i class="fa-solid fa-star me-1 text-danger"></i> หัวข้อเด่นหน้าหลัก (<?= count($homeNews) ?>)
                            </span>
                        <?php endif; ?>
                        <?php if ($isOfficer): ?>
                            <span class="badge bg-success bg-opacity-25 text-success px-2 py-1 fs-6"><i class="fa-solid fa-user-shield me-1"></i>แอดมิน</span>
                        <?php endif; ?>
                    </h3>
                    <p class="text-muted small mb-0 mt-1">อัปเดตข่าวสาร กิจกรรมราชการ และประกาศสำคัญของจังหวัดพัทลุง</p>
                </div>

                <?php if ($isOfficer): ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php if (function_exists('is_super_admin') && is_super_admin()): ?>
                            <button type="button" onclick="NewsStudio.addCategory()" class="btn btn-xs btn-outline-success py-2 px-3 rounded-pill fw-bold" title="เฉพาะ Super Admin เท่านั้น">
                                <i class="fa-solid fa-tags"></i> เพิ่มหมวดหมู่ใหม่
                            </button>
                        <?php endif; ?>
                        <button type="button" onclick="NewsStudio.open()" class="btn btn-xs btn-success py-2 px-4 rounded-pill fw-bold text-white shadow-xs" style="background: #047857; border: none;">
                            <i class="fa-solid fa-circle-plus"></i> + สร้างประกาศข่าวใหม่ (Studio)
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Category Filters: Dropdown Box System -->
            <div class="d-flex align-items-center gap-2 pt-2 border-top flex-wrap" style="border-color: rgba(0,0,0,0.06) !important;">
                <?php 
                $finTotalCount = 0;
                try {
                    $finDump = (defined('WRITABLE') ? rtrim(WRITABLE, '/\\') : dirname(__DIR__, 2) . '/writable') . '/dump_financial_reports.json';
                    if (file_exists($finDump)) {
                        $finData = json_decode(file_get_contents($finDump), true);
                        $finTotalCount = is_array($finData) ? count($finData) : 234;
                    }
                } catch (\Throwable $e) {}

                // รวบรวมสถิติจำนวนบทความในแต่ละหมวดหมู่
                $categoryStats = [];
                foreach ($newsCats as $cat) {
                    $countInCat = 0;
                    $isFinCatChip = (strcasecmp(trim($cat), 'รายงานทางการเงิน') === 0);
                    if ($isFinCatChip) {
                        $countInCat = $finTotalCount ?: 234;
                    } else {
                        $normCat = preg_replace('/\s*([\/\(\)])\s*/u', '$1', trim($cat));
                        foreach ($allPublishedNews as $hn) {
                            $normHnCat = preg_replace('/\s*([\/\(\)])\s*/u', '$1', trim($hn['category'] ?? ''));
                            if (strcasecmp($normHnCat, $normCat) === 0) {
                                $countInCat++;
                            }
                        }
                    }
                    if ($countInCat === 0 && !$isOfficer) continue;

                    $categoryStats[] = [
                        'cat'            => $cat,
                        'count'          => $countInCat,
                        'isFin'          => $isFinCatChip,
                        'isPrd'          => (mb_stripos($cat, 'สปชส') !== false || mb_stripos($cat, 'สำนักงานประชาสัมพันธ์') !== false),
                        'displayCatName' => (strcasecmp(trim($cat), 'general') === 0) ? 'ข่าวทั่วไป' : $cat,
                    ];
                }

                // เรียงลำดับจากจำนวนบทความมากไปหาน้อย
                usort($categoryStats, function ($a, $b) {
                    return $b['count'] <=> $a['count'];
                });
                ?>

                <!-- ปุ่ม ทั้งหมด -->
                <button class="btn btn-xs px-3.5 py-2 news-cat-btn rounded-pill active" id="btnNewsFilterAll" data-filter="all" onclick="resetNewsToAll(this)">
                    <i class="fa-solid fa-border-all me-1.5"></i>ทั้งหมด (<?= count($allPublishedNews) ?>)
                </button>

                <!-- Dropdown Box เลือกหมวดหมู่ -->
                <div class="dropdown d-inline-block">
                    <button class="btn btn-xs px-3.5 py-2 news-cat-btn rounded-pill dropdown-toggle d-inline-flex align-items-center gap-2 shadow-xs" type="button" id="newsCatDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-layer-group text-success"></i>
                        <span id="newsCatDropdownLabel" class="fw-semibold">เลือกหมวดหมู่ข่าวสาร (<?= count($categoryStats) ?> หมวด)</span>
                    </button>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-4 py-2 mt-1" style="min-width: 320px; font-size: 0.88rem; max-height: 400px; overflow-y: auto;">
                        <li class="px-3 py-1.5 text-muted small fw-bold text-uppercase border-bottom mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-arrow-down-wide-short me-1 text-primary"></i> เรียงตามจำนวนบทความ (มาก &rarr; น้อย)
                        </li>
                        <?php foreach ($categoryStats as $catItem): ?>
                            <?php if ($catItem['isFin']): ?>
                                <li>
                                    <a class="dropdown-item py-2 px-3 d-flex justify-content-between align-items-center" href="<?= base_url('news?category=' . urlencode('รายงานทางการเงิน')) ?>" title="คลิกเพื่อดูรายงานทางการเงินและงบทดลอง">
                                        <span><i class="fa-solid fa-file-invoice-dollar text-success me-2"></i><?= esc($catItem['displayCatName']) ?></span>
                                        <span class="badge bg-success bg-opacity-15 text-success rounded-pill font-monospace"><?= $catItem['count'] ?></span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <button class="dropdown-item py-2 px-3 d-flex justify-content-between align-items-center <?= ($catItem['count'] === 0) ? 'opacity-50' : '' ?>" type="button" onclick="selectNewsDropdownCat('<?= esc($catItem['cat']) ?>', '<?= esc($catItem['displayCatName']) ?>', <?= $catItem['count'] ?>)">
                                        <span>
                                            <?php if ($catItem['isPrd']): ?><i class="fa-solid fa-bullhorn text-success me-2"></i><?php else: ?><i class="fa-solid fa-tag text-muted me-2"></i><?php endif; ?>
                                            <?= esc($catItem['displayCatName']) ?>
                                        </span>
                                        <span class="badge bg-light text-muted border rounded-pill font-monospace"><?= $catItem['count'] ?></span>
                                    </button>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-1.5 px-3 text-primary small fw-semibold" href="<?= base_url('news') ?>">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ดูหน้าคลังข่าวสารทั้งหมด
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- News Cards Grid -->
        <div class="row g-4" id="newsGridContainer">
            <?php if (empty($allPublishedNews)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fa-regular fa-folder-open fs-1 text-muted mb-3"></i>
                    <p class="text-muted m-0">ยังไม่มีข้อมูลข่าวสารในระบบขณะนี้</p>
                </div>
            <?php else: ?>
                <div class="col-12 text-center py-5" id="newsGridEmpty" style="display: none;">
                    <i class="fa-regular fa-folder-open fs-1 text-muted mb-3"></i>
                    <p class="text-muted m-0">ยังไม่มีข้อมูลในหมวดหมู่นี้</p>
                </div>
                <?php 
                $initialIds = !empty($featuredNews) ? array_column(array_slice($featuredNews, 0, 6), 'id') : array_column(array_slice($allPublishedNews, 0, 6), 'id');
                ?>
                <?php foreach ($allPublishedNews as $item): 
                    $isInitShow = in_array($item['id'], $initialIds, false);
                    $rawCat = trim($item['category'] ?? 'ข่าวทั่วไป');
                    $catLabel = (strcasecmp($rawCat, 'general') === 0 || empty($rawCat)) ? 'ข่าวทั่วไป' : $rawCat;
                    $isPrd = !empty($item['is_prd']);
                    
                    $coverImg = !empty($item['cover_image']) ? ((strpos($item['cover_image'], 'http') === 0 || strpos($item['cover_image'], 'data:') === 0 || strpos($item['cover_image'], 'uploads/') === 0) ? ((strpos($item['cover_image'], 'http') === 0) ? $item['cover_image'] : base_url($item['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png')) : base_url('assets/images/slider/sane_muanglung.png');
                    
                    $attachCount = !empty($item['attachments']) ? count($item['attachments']) : 0;
                    $newsDate = !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : (!empty($item['display_date']) ? $item['display_date'] : date('d/m/Y'));
                    $viewsCount = number_format($item['views'] ?? 0);
                    $sourceName = $item['source'] ?? '';
                ?>
                    <div class="col-md-6 col-lg-4 news-card-item" data-category="<?= esc($rawCat) ?>" data-initial-show="<?= $isInitShow ? '1' : '0' ?>" style="<?= $isInitShow ? '' : 'display: none;' ?>">
                        <div class="gov-news-card position-relative">
                            
                            <?php if ($isOfficer && empty($item['is_prd'])): ?>
                                <!-- Quick Officer Controls -->
                                <div class="position-absolute top-0 end-0 m-2.5 d-flex gap-1.5" style="z-index: 10;">
                                    <button type="button" class="btn btn-sm <?= !empty($item['is_featured']) ? 'btn-warning text-dark' : 'btn-light text-muted' ?> shadow rounded-circle border p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="event.preventDefault(); event.stopPropagation(); NewsStudio.toggleFeatured('<?= $item['id'] ?>')" title="<?= !empty($item['is_featured']) ? 'หัวข้อเด่นหน้าหลัก (คลิกเพื่อยกเลิก)' : 'คลิกเพื่อตั้งเป็นหัวข้อเด่นหน้าหลัก' ?>">
                                        <i class="fa-<?= !empty($item['is_featured']) ? 'solid' : 'regular' ?> fa-star"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light bg-white shadow rounded-circle border p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="event.preventDefault(); event.stopPropagation(); NewsStudio.open('<?= $item['id'] ?>')" title="แก้ไขบทความนี้">
                                        <i class="fa-solid fa-pen-to-square text-primary" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                            <?php endif; ?>

                            <!-- Cover Image Wrap -->
                            <div class="gov-news-img-wrap">
                                <span class="gov-news-floating-cat">
                                    <?php if ($isPrd): ?><i class="fa-solid fa-bullhorn text-warning me-1"></i><?php endif; ?>
                                    <?= esc($catLabel) ?>
                                </span>
                                <?php if (!empty($item['is_featured'])): ?>
                                    <span class="badge position-absolute top-0 end-0 m-2.5 rounded-pill px-2.5 py-1 text-dark shadow" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); font-size: 0.72rem; z-index: 2;">
                                        <i class="fa-solid fa-star me-1 text-danger"></i> เด่น
                                    </span>
                                <?php endif; ?>
                                <a href="<?= base_url('news/detail/' . $item['id']) ?>">
                                    <img src="<?= $coverImg ?>" alt="<?= esc($item['title']) ?>" loading="lazy" onerror="this.src='<?= base_url('assets/images/slider/sane_muanglung.png') ?>'">
                                </a>
                            </div>

                            <!-- Body Content -->
                            <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <div class="gov-news-meta mb-2.5">
                                        <span><i class="fa-regular fa-calendar-days text-success me-1"></i> <?= $newsDate ?></span>
                                        <span><i class="fa-regular fa-eye text-primary me-1"></i> <?= $viewsCount ?></span>
                                        <?php if ($attachCount > 0): ?>
                                            <span><i class="fa-solid fa-paperclip text-secondary me-1"></i> <?= $attachCount ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="text-decoration-none">
                                        <h5 class="gov-news-title" title="<?= esc($item['title']) ?>">
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
</section>

<?php 
$todayStr = date('Y-m-d');
$filteredUpcomingEvents = [];
foreach ($homeEvents as $ev) {
    $multiDates = (!empty($ev['event_dates']) && is_array($ev['event_dates'])) ? array_values(array_filter($ev['event_dates'])) : [];
    if (!empty($multiDates)) {
        sort($multiDates);
        $nextDate = null;
        foreach ($multiDates as $md) {
            if ($md >= $todayStr) {
                $nextDate = $md;
                break;
            }
        }
        if ($nextDate !== null) {
            $ev['_display_date'] = $nextDate;
            $ev['_multi_count'] = count($multiDates);
            $filteredUpcomingEvents[] = $ev;
        }
        continue;
    }

    $endDate = !empty($ev['event_end_date']) ? substr($ev['event_end_date'], 0, 10) : '';
    $startDate = !empty($ev['event_start_date']) ? substr($ev['event_start_date'], 0, 10) : '';
    if ((!empty($endDate) && $endDate >= $todayStr) || (!empty($startDate) && $startDate >= $todayStr)) {
        $ev['_display_date'] = $startDate ?: $endDate;
        $filteredUpcomingEvents[] = $ev;
    }
}
usort($filteredUpcomingEvents, function($a, $b) {
    return strcmp((string)($a['_display_date'] ?? ''), (string)($b['_display_date'] ?? ''));
});
$upcomingEvents = array_slice($filteredUpcomingEvents, 0, 4);
?>

<?php $hasUpcomingEvents = !empty($upcomingEvents); ?>
<!-- =========================================================================
     SECTION 2: ศูนย์กิจกรรมจังหวัด คลังภาพ และวิดีทัศน์ Web TV (Unified Tabbed Hub)
     ========================================================================= -->
<section id="multimedia-gallery-section" class="my-5 py-2">
    <div id="provincial-calendar-section"></div>
    <div class="card gov-section-card border-0 p-4 p-lg-5 shadow-sm" style="border-radius: 28px; position: relative; overflow: hidden;">
        
        <!-- Ambient Decorative Glow -->
        <div class="position-absolute top-0 end-0 rounded-circle" style="width: 260px; height: 260px; background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%); pointer-events: none; z-index: 0;"></div>

        <!-- Unified Header + Modern Pill Tabs -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom position-relative" style="border-color: rgba(0,0,0,0.06) !important; z-index: 1;">
            <div class="d-flex align-items-center gap-3">
                <div class="section-icon-box text-white" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);">
                    <i class="fa-solid fa-photo-film fs-5"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark" style="color: var(--text-primary); font-size: 1.35rem;">กิจกรรมจังหวัด คลังภาพ และวิดีทัศน์ Web TV</h3>
                    <p class="text-muted small mb-0 mt-0.5">เลือกสลับชมกำหนดการกิจกรรม ประมวลภาพภารกิจ และวิดีทัศน์ประชาสัมพันธ์ได้ในจุดเดียว</p>
                </div>
            </div>

            <!-- Interactive Tab Switcher -->
            <ul class="nav nav-pills gap-2 p-1.5 rounded-pill bg-light border" id="homeMediaHubTabs" role="tablist">
                <?php if ($hasUpcomingEvents): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-3.5 py-1.5 fw-bold small d-inline-flex align-items-center gap-1.5" id="tab-events-btn" data-bs-toggle="pill" data-bs-target="#pane-home-events" type="button" role="tab">
                        <i class="fa-solid fa-calendar-check"></i> กิจกรรมจังหวัด
                        <span class="badge bg-warning text-dark rounded-pill"><?= count($upcomingEvents) ?></span>
                    </button>
                </li>
                <?php endif; ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= !$hasUpcomingEvents ? 'active' : '' ?> rounded-pill px-3.5 py-1.5 fw-bold small d-inline-flex align-items-center gap-1.5" id="tab-gallery-btn" data-bs-toggle="pill" data-bs-target="#pane-home-gallery" type="button" role="tab">
                        <i class="fa-solid fa-camera"></i> คลังภาพกิจกรรม
                        <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill"><?= $totalAlbums ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-3.5 py-1.5 fw-bold small d-inline-flex align-items-center gap-1.5" id="tab-videos-btn" data-bs-toggle="pill" data-bs-target="#pane-home-videos" type="button" role="tab">
                        <i class="fa-brands fa-youtube"></i> วิดีทัศน์ Web TV
                        <span class="badge bg-danger bg-opacity-15 text-danger rounded-pill"><?= count($homeVideos) ?></span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content position-relative" style="z-index: 1;">

            <?php if ($hasUpcomingEvents): ?>
            <!-- TAB 1: กำหนดการ & ไฮไลท์กิจกรรมเมืองลุง -->
            <div class="tab-pane fade show active" id="pane-home-events" role="tabpanel">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3.5">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill px-2.5 py-1 text-white shadow-xs" style="background: linear-gradient(135deg, #f59e0b, #d97706); font-size: 0.72rem;">
                            <i class="fa-solid fa-fire text-warning me-1"></i> เร็วๆ นี้
                        </span>
                        <span class="fw-bold text-dark small">ประมวลวาระงานสำคัญ ประเพณีท้องถิ่น และกิจกรรมขับเคลื่อนจังหวัดพัทลุง</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($isOfficer): ?>
                            <a href="<?= base_url('calendar') ?>" class="btn btn-xs btn-warning rounded-pill px-3 py-1.5 fw-bold text-dark shadow-xs" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none; font-size: 0.82rem;">
                                <i class="fa-solid fa-calendar-plus me-1"></i> จัดการปฏิทิน
                            </a>
                        <?php endif; ?>
                        <a href="<?= base_url('calendar') ?>" class="btn btn-sm btn-outline-success rounded-pill px-3.5 py-1.5 fw-bold d-inline-flex align-items-center gap-2 hover-scale shadow-xs" style="border-width: 1.5px; color: #047857; border-color: #047857; font-size: 0.84rem;">
                            <span>ดูปฏิทินฉบับเต็ม</span>
                            <i class="fa-solid fa-arrow-right-long" style="font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>

                <div class="row g-4">
            <?php foreach ($upcomingEvents as $ev): 
                $startDate = !empty($ev['_display_date']) ? $ev['_display_date'] : (!empty($ev['event_start_date']) ? $ev['event_start_date'] : ($ev['created_at'] ?? date('Y-m-d')));
                $time = strtotime($startDate);
                $thaiDay = date('j', $time);
                $thaiMonthNames = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                $thaiMonth = $thaiMonthNames[(int)date('n', $time)] ?? '';
                $thaiYear = (int)date('Y', $time) + 543;
                $shortYear = substr((string)$thaiYear, 2, 2);
                $fullDateLabel = $thaiDay . ' ' . $thaiMonth . ' ' . $thaiYear;

                // Image extraction & smart thematic fallback
                $evCover = !empty($ev['cover_image']) ? $ev['cover_image'] : '';
                if (empty($evCover) && !empty($ev['images']) && is_array($ev['images'])) {
                    $evCover = $ev['images'][0];
                }
                if (!empty($evCover)) {
                    if (strpos($evCover, 'http') === 0 || strpos($evCover, 'data:') === 0) {
                        // already full URL
                    } else {
                        $evCover = base_url($evCover);
                    }
                } else {
                    $titleLower = mb_strtolower($ev['title'] ?? '');
                    if (mb_strpos($titleLower, 'วิ่ง') !== false || mb_strpos($titleLower, 'run') !== false || mb_strpos($titleLower, 'กีฬา') !== false) {
                        $evCover = 'https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?auto=format&fit=crop&w=800&q=80';
                    } elseif (mb_strpos($titleLower, 'เดือนสิบ') !== false || mb_strpos($titleLower, 'เปรต') !== false || mb_strpos($titleLower, 'วัฒนธรรม') !== false || mb_strpos($titleLower, 'ประเพณี') !== false || mb_strpos($titleLower, 'ท่องเที่ยว') !== false || mb_strpos($titleLower, 'หลบบ้าน') !== false) {
                        $evCover = 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80';
                    } elseif (mb_strpos($titleLower, 'ประชุม') !== false || mb_strpos($titleLower, 'สัมมนา') !== false || mb_strpos($titleLower, 'เชิญร่วม') !== false) {
                        $evCover = 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80';
                    } else {
                        $evCover = 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80';
                    }
                }

                $loc = !empty($ev['event_location']) ? $ev['event_location'] : 'ศาลากลางจังหวัดพัทลุง';
                $catLabel = !empty($ev['category']) ? $ev['category'] : 'กิจกรรมจังหวัด';
            ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="gov-visual-event-card card h-100 border-0 shadow-sm rounded-4 overflow-hidden" onclick="SmartEventViewer.open('<?= esc($ev['id']) ?>')" style="cursor: pointer; background: var(--card-bg, #ffffff); border: 1px solid rgba(16, 185, 129, 0.22) !important;">
                        
                        <!-- 1. Event Poster / Image Header with Floating Badges -->
                        <div class="position-relative overflow-hidden" style="padding-top: 60%; background: #064e3b;">
                            <img src="<?= $evCover ?>" alt="<?= esc($ev['title']) ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover event-zoom-img" loading="lazy" style="object-fit: cover;">
                            
                            <!-- Contrast Vignette Overlay -->
                            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, transparent 40%, rgba(0,0,0,0.78) 100%);"></div>

                            <!-- 3D Glassmorphism Date Stamp (Top-Left) -->
                            <div class="position-absolute top-0 start-0 m-3 d-flex flex-column align-items-center text-center shadow-lg rounded-3 overflow-hidden event-glass-date" style="min-width: 50px; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); background: rgba(255, 255, 255, 0.95); border: 1px solid rgba(255, 255, 255, 0.85); z-index: 2;">
                                <div class="w-100 py-0.5 px-2 text-white fw-bold" style="background: linear-gradient(135deg, #059669, #047857); font-size: 0.68rem; letter-spacing: 0.3px;">
                                    <?= $thaiMonth ?> '<?= $shortYear ?>
                                </div>
                                <div class="py-1 px-2 fw-black" style="font-size: 1.25rem; line-height: 1; font-weight: 800; color: #064e3b;">
                                    <?= $thaiDay ?>
                                </div>
                            </div>

                            <!-- Floating Category Badge (Top-Right) -->
                            <span class="badge position-absolute top-0 end-0 m-3 rounded-pill px-2.5 py-1 text-white shadow-sm" style="background: rgba(15, 23, 42, 0.78); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); font-size: 0.72rem; border: 1px solid rgba(255, 255, 255, 0.25); z-index: 2;">
                                <?= esc($catLabel) ?>
                            </span>

                            <!-- Floating Location Pill (Bottom on image) -->
                            <div class="position-absolute bottom-0 start-0 m-2.5 d-flex align-items-center gap-1.5 text-white small" style="font-size: 0.75rem; text-shadow: 0 1px 3px rgba(0,0,0,0.8); z-index: 2;">
                                <span class="badge rounded-pill px-2.5 py-1 bg-dark bg-opacity-70 text-white border border-light border-opacity-25 text-truncate" style="max-width: 220px;">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= esc($loc) ?>
                                </span>
                            </div>
                        </div>

                        <!-- 2. Event Body -->
                        <div class="card-body p-3.5 d-flex flex-column justify-content-between flex-grow-1" style="background: #ffffff;">
                            <div>
                                <h6 class="fw-bold mb-2 text-dark event-title-hover" style="font-size: 0.92rem; line-height: 1.45; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.65rem;" title="<?= esc($ev['title']) ?>">
                                    <?= esc($ev['title']) ?>
                                </h6>
                                <div class="d-flex align-items-center gap-2 text-muted small mt-2" style="font-size: 0.78rem;">
                                    <i class="fa-regular fa-calendar-check text-success"></i>
                                    <span><?= $fullDateLabel ?></span>
                                </div>
                            </div>

                            <!-- 3. Event Action Footer -->
                            <div class="pt-3 mt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.06) !important;">
                                <span class="text-success fw-bold small d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                                    <span>ดูรายละเอียด & แผนที่</span>
                                    <i class="fa-solid fa-arrow-right-long event-arrow-icon" style="font-size: 0.78rem;"></i>
                                </span>
                                <span class="btn btn-xs rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center shadow-xs" style="width: 28px; height: 28px; border: 1px solid rgba(16, 185, 129, 0.2);">
                                    <i class="fa-solid fa-location-arrow" style="font-size: 0.75rem;"></i>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- TAB 2: PHOTO GALLERY BLOCK -->
            <div class="tab-pane fade <?= !$hasUpcomingEvents ? 'show active' : '' ?>" id="pane-home-gallery" role="tabpanel">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3.5">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <span class="badge rounded-circle p-1.5 bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-camera"></i>
                        </span>
                        <span>ภาพถ่ายภารกิจและกิจกรรมสำคัญ</span>
                    </h5>
                    
                    <div class="d-flex align-items-center gap-2">
                        <?php if (function_exists('can_manage_gallery') && can_manage_gallery()): ?>
                            <button type="button" onclick="GalleryStudio.open()" class="btn btn-xs btn-warning rounded-pill px-3 py-1.5 fw-bold text-dark" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                                <i class="fa-solid fa-circle-plus"></i> เพิ่มอัลบั้มใหม่
                            </button>
                        <?php endif; ?>
                        <a href="<?= base_url('gallery') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3.5 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5">
                            <span>ดูคลังภาพทั้งหมด (<?= $totalAlbums ?> อัลบั้ม)</span>
                            <i class="fa-solid fa-arrow-right-long" style="font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <?php if (empty($homeGalleryAlbums)): ?>
                        <div class="col-12 text-center py-4 bg-light rounded-4">
                            <i class="fa-regular fa-image fs-1 text-muted mb-2"></i>
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

            <!-- TAB 3: WEB TV & VIDEO CINEMA BLOCK -->
            <div class="tab-pane fade" id="pane-home-videos" role="tabpanel">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3.5">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <span class="badge rounded-circle p-1.5 bg-danger bg-opacity-10 text-danger">
                            <i class="fa-brands fa-youtube fs-5"></i>
                        </span>
                        <span>วิดีทัศน์นำเสนอและสารคดีเมืองลุง (Web TV)</span>
                    </h5>

                    <div class="d-flex align-items-center gap-2">
                        <?php if ($isOfficer): ?>
                            <button type="button" onclick="openHomeVideoAddModal()" class="btn btn-xs btn-danger rounded-pill px-3 py-1.5 fw-bold shadow-sm d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> เพิ่มวิดีโอใหม่
                            </button>
                            <a href="<?= base_url('admin/videos') ?>" class="btn btn-xs btn-outline-dark rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-sliders"></i> สตูดิโอหลังบ้าน
                            </a>
                        <?php endif; ?>
                        <a href="<?= base_url('video') ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3.5 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5">
                            <span>ดูวิดีโอทั้งหมด</span>
                            <i class="fa-solid fa-arrow-right-long" style="font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>

                <div class="row g-4">
                    <?php if (empty($homeVideos)): ?>
                        <div class="col-12 text-center py-4 bg-light rounded-4">
                            <i class="fa-solid fa-video-slash fs-1 text-muted mb-2"></i>
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
                            $vidId = esc($vid['id'] ?? '');
                        ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-transform hover-scale d-flex flex-column justify-content-between position-relative" style="cursor: pointer; background: var(--card-bg, #ffffff); border: 1px solid rgba(225, 29, 72, 0.2) !important;">
                                    
                                    <?php if ($isOfficer): ?>
                                        <!-- Quick Edit/Delete Overlay for Officer -->
                                        <div class="position-absolute top-0 end-0 m-2.5 d-flex gap-1.5" style="z-index: 10;">
                                            <button type="button" class="btn btn-sm btn-white bg-white shadow rounded-circle border p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="event.stopPropagation(); editHomeVideo('<?= $vidId ?>')" title="แก้ไขวิดีโอ">
                                                <i class="fa-solid fa-pen-to-square text-primary" style="font-size: 0.8rem;"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-white bg-white shadow rounded-circle border p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" onclick="event.stopPropagation(); deleteHomeVideo('<?= $vidId ?>', '<?= addslashes($vidTitle) ?>')" title="ลบวิดีโอ">
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
                                            <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle text-white shadow-lg" style="width: 58px; height: 58px; background: rgba(225, 29, 72, 0.92); backdrop-filter: blur(4px); z-index: 2; transition: transform 0.2s ease;">
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

<!-- =====================================================================
     SMART HOME CINEMA THEATER MODAL (IMAX-Style Dark Glassmorphism Viewer)
     ===================================================================== -->
<div class="modal fade" id="homeCinemaTheaterModal" tabindex="-1" aria-labelledby="homeCinemaTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg" style="background: rgba(15, 23, 42, 0.96); backdrop-filter: blur(25px); border: 1px solid rgba(255,255,255,0.15) !important; border-radius: 28px; overflow: hidden;">
            <div class="modal-header border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="border-color: rgba(255,255,255,0.1) !important;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 m-0 text-truncate" id="homeCinemaTitle">
                    <i class="fa-brands fa-youtube text-danger fs-3"></i>
                    <span id="homeCinemaTitleText">กำลังโหลดวิดีโอ...</span>
                </h5>
                <button type="button" class="btn btn-dark rounded-circle text-white p-2 d-flex align-items-center justify-content-center border" onclick="SmartHomeCinema.close()" style="width: 40px; height: 40px; border-color: rgba(255,255,255,0.2) !important;">
                    <i class="fa-solid fa-xmark fs-5"></i>
                </button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    <iframe id="homeCinemaPlayerIframe" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer border-top px-4 py-3 d-flex align-items-center justify-content-between text-muted small" style="border-color: rgba(255,255,255,0.1) !important;">
                <span class="text-light opacity-75"><i class="fa-solid fa-shield-halved text-success me-1"></i> สตรีมมิ่งความละเอียดสูงผ่าน YouTube CDN</span>
                <button type="button" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold small" onclick="SmartHomeCinema.close()">
                    <span>ปิดหน้าต่างรับชม</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if ($isOfficer): ?>
<!-- ======================================================== -->
<!-- MODAL: QUICK ADD/EDIT YOUTUBE VIDEO (OFFICER STUDIO) -->
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
    if (btn) {
        if (btn.classList.contains('news-cat-btn')) {
            btn.classList.add('active');
        } else if (btn.closest('.dropdown')) {
            var dropBtn = btn.closest('.dropdown').querySelector('.news-cat-btn');
            if (dropBtn) dropBtn.classList.add('active');
        }
    }

    var normCat = (cat || '').replace(/\s*\/\s*/g, '/').trim().toLowerCase();
    var visibleCount = 0;

    document.querySelectorAll('.news-card-item').forEach(item => {
        if (cat === 'all') {
            var isInit = item.getAttribute('data-initial-show') === '1';
            item.style.display = isInit ? '' : 'none';
            if (isInit) visibleCount++;
        } else {
            var itemCat = (item.getAttribute('data-category') || '').replace(/\s*([\/\(\)])\s*/g, '$1').trim().toLowerCase();
            var cleanNormCat = normCat.replace(/\s*([\/\(\)])\s*/g, '$1').trim().toLowerCase();
            if (itemCat === cleanNormCat) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        }
    });

    var emptyBox = document.getElementById('newsGridEmpty');
    if (emptyBox) {
        emptyBox.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
}

// Select news category from Dropdown Box
function selectNewsDropdownCat(cat, catName, count) {
    document.querySelectorAll('.news-cat-btn').forEach(b => b.classList.remove('active'));
    var dropBtn = document.getElementById('newsCatDropdownBtn');
    var dropLabel = document.getElementById('newsCatDropdownLabel');
    if (dropBtn) dropBtn.classList.add('active');
    if (dropLabel) dropLabel.innerHTML = 'หมวด: <strong>' + catName + '</strong> (' + count + ')';
    filterHomeNews(cat, dropBtn);
}

// Reset news filter to All
function resetNewsToAll(btn) {
    var dropLabel = document.getElementById('newsCatDropdownLabel');
    if (dropLabel) {
        dropLabel.innerHTML = 'เลือกหมวดหมู่ข่าวสาร';
    }
    filterHomeNews('all', btn);
}

// =========================================================================
// SMART HOME CINEMA THEATER ENGINE
// =========================================================================
const SmartHomeCinema = {
    modalInstance: null,
    play: function(youtubeId, title, dbId) {
        var el = document.getElementById('homeCinemaTheaterModal');
        if (!el) return;
        if (!this.modalInstance) {
            this.modalInstance = new bootstrap.Modal(el);
        }
        var titleEl = document.getElementById('homeCinemaTitleText');
        var iframe = document.getElementById('homeCinemaPlayerIframe');
        if (titleEl) titleEl.textContent = title || 'วิดีโอนำเสนอจังหวัดพัทลุง';
        if (iframe) iframe.src = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1&rel=0';
        this.modalInstance.show();

        if (dbId) {
            fetch('<?= base_url("videos/count-view") ?>/' + dbId, { method: 'POST' }).catch(function(){});
        }
    },
    close: function() {
        var el = document.getElementById('homeCinemaTheaterModal');
        var iframe = document.getElementById('homeCinemaPlayerIframe');
        if (iframe) iframe.src = '';
        if (this.modalInstance) {
            this.modalInstance.hide();
        } else if (el) {
            var m = bootstrap.Modal.getInstance(el);
            if (m) m.hide();
        }
    }
};

// =========================================================================
// INTERACTIVE HUB CALENDAR & EVENT SUMMARY ENGINE
// =========================================================================
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
            if (text) text.innerText = 'หยุดฟัง';
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
        if (text) text.innerText = 'ฟังเสียงกำหนดการ';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    HubCalendar.init();
});
</script>

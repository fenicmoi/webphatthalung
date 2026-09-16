<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
helper('settings');
$isOfficer = session()->get('isLoggedIn');
$categories = $categories ?? get_gallery_categories();
$selectedCat = $selectedCat ?? 'all';
$albums = $albums ?? [];

if (!function_exists('format_thai_date_medium')) {
    function format_thai_date_medium($dateStr) {
        $timestamp = strtotime($dateStr);
        if (!$timestamp) return $dateStr;
        $months = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $day = (int)date('j', $timestamp);
        $month = $months[(int)date('n', $timestamp)];
        $year = ((int)date('Y', $timestamp) + 543);
        return "$day $month $year";
    }
}
?>

<style>
/* --- Professional Government Gallery Portal Styling --- */
:root {
    --gov-primary: #006B54;
    --gov-dark: #004D40;
    --gov-deep: #003B30;
    --gov-accent: #00A878;
    --gov-light: #EAF7F2;
    --gov-bg: #F7F9F8;
    --gov-text: #17332D;
    --gov-muted: #71807B;
    --gov-border: #E4ECE8;
}

/* Page Title Box */
.gallery-title-box {
    border-left: 4px solid #00A878;
    background: linear-gradient(to right, #F0FBF7, #FFFFFF);
    border-radius: 18px;
    border: 1px solid #E4ECE8;
    box-shadow: 0 4px 18px rgba(0, 107, 84, 0.05);
    padding: 18px 24px;
}
.gallery-title-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 14px;
    background: rgba(0, 168, 120, 0.12);
    border: 1px solid rgba(0, 168, 120, 0.25);
    color: #006B54;
    font-size: 1.35rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.gallery-title-text {
    font-size: clamp(24px, 3vw, 28px);
    font-weight: 700;
    color: #17332D;
    line-height: 1.3;
}

/* Filter Pills */
.gallery-filter-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.gallery-pill-btn {
    height: 42px;
    padding: 0 18px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
    background: #FFFFFF;
    border: 1px solid #DDE5E1;
    color: #253A35;
    transition: all 0.25s ease;
}
.gallery-pill-btn:hover {
    border-color: #00A878;
    color: #006B54;
    background: #F0FBF7;
}
.gallery-pill-btn.active {
    background: #006B54 !important;
    color: #FFFFFF !important;
    border-color: #006B54 !important;
    box-shadow: 0 6px 18px rgba(0, 107, 84, 0.15);
    font-weight: 600;
}

/* Search Box */
.gallery-search-wrap {
    width: 100%;
    max-width: 320px;
}
.gallery-search-input-group {
    display: flex;
    align-items: center;
    background: #FFFFFF;
    border: 1px solid #CFE1DB;
    border-radius: 999px;
    height: 46px;
    padding: 3px 4px 3px 18px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    transition: border-color 0.2s, box-shadow 0.2s;
}
.gallery-search-input-group:focus-within {
    border-color: #00A878;
    box-shadow: 0 0 0 3px rgba(0, 168, 120, 0.10);
}
.gallery-search-input-group .form-control {
    border: none;
    background: transparent;
    padding: 0;
    font-size: 14px;
    color: #17332D;
    box-shadow: none !important;
}
.gallery-search-input-group .btn-search-circle {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 50%;
    background: #006B54;
    color: #FFFFFF;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.gallery-search-input-group .btn-search-circle:hover {
    background: #004D40;
    color: #FFFFFF;
}

/* Gallery Card */
.gallery-card-item {
    background: #FFFFFF;
    border: 1px solid #E4ECE8;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.gallery-card-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.10);
    border-color: rgba(0, 168, 120, 0.35);
}
.card-thumb-wrap {
    position: relative;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #003B30;
}
.card-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.gallery-card-item:hover .card-thumb-img {
    transform: scale(1.04);
}
.card-thumb-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.08) 0%, transparent 40%, rgba(0,0,0,0.65) 100%);
    pointer-events: none;
}

/* Badges */
.card-badge-cat {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 2;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 13px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
    color: #ffffff;
}
.card-badge-cat.badge-blue { background: #008FC7; }
.card-badge-cat.badge-green { background: #006B54; }
.card-badge-cat.badge-teal { background: #00A878; }
.card-badge-cat.badge-darkgreen { background: #004D40; }

.card-badge-date {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 2;
    background: rgba(0, 45, 38, 0.80);
    backdrop-filter: blur(8px);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 500;
    padding: 5px 12px;
}
.card-badge-count {
    position: absolute;
    bottom: 14px;
    right: 14px;
    z-index: 2;
    background: rgba(0, 45, 38, 0.80);
    backdrop-filter: blur(8px);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
}

/* Card Content */
.card-content-wrap {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}
.card-title-heading {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.55;
    color: #17332D;
    margin-bottom: 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s;
}
.card-title-heading a:hover {
    color: #006B54 !important;
}
.card-desc-paragraph {
    font-size: 14px;
    line-height: 1.7;
    color: #71807B;
    margin-bottom: 16px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-footer-strip {
    margin-top: auto;
    border-top: 1px solid #E4ECE8;
    padding-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-loc-text {
    font-size: 13px;
    color: #71807B;
    display: flex;
    align-items: center;
    gap: 6px;
}
.card-detail-btn {
    color: #006B54;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    transition: gap 0.2s ease, color 0.2s;
}
.card-detail-btn:hover {
    gap: 8px;
    color: #00A878;
}

/* Floating Buttons */
.floating-action-contact {
    bottom: 24px;
    left: 24px;
    z-index: 1040;
}
.btn-float-officer {
    height: 50px;
    border-radius: 999px;
    background: #006B54;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    padding: 0 20px;
    font-weight: 600;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.25s ease;
}
.btn-float-officer:hover {
    background: #004D40;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.22);
}

/* Gallery Pagination Styling */
.gallery-pagination .page-link {
    color: #006B54;
    border-color: rgba(0, 107, 84, 0.2);
    border-radius: 10px;
    margin: 0 4px;
    font-weight: 600;
    font-size: 0.92rem;
    padding: 8px 16px;
    transition: all 0.2s ease;
    background: #FFFFFF;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
.gallery-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #00A878, #006B54) !important;
    border-color: #006B54 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(0, 107, 84, 0.25);
}
.gallery-pagination .page-link:hover:not(.disabled) {
    background: rgba(0, 168, 120, 0.1);
    color: #006B54;
    border-color: #006B54;
}
.gallery-pagination .page-item.disabled .page-link {
    opacity: 0.45;
    pointer-events: none;
    background: transparent;
}

/* Dark Mode Support */
[data-theme="dark"] .gallery-title-box {
    background: linear-gradient(to right, #162923, #0f1c18);
    border-color: #223d34;
}
[data-theme="dark"] .gallery-title-text { color: #e6f4f0; }
[data-theme="dark"] .gallery-pill-btn {
    background: #162923;
    border-color: #223d34;
    color: #9bb0a9;
}
[data-theme="dark"] .gallery-pill-btn.active {
    background: #006B54 !important;
    color: #ffffff !important;
}
[data-theme="dark"] .gallery-search-input-group {
    background: #162923;
    border-color: #223d34;
}
[data-theme="dark"] .gallery-search-input-group .form-control {
    color: #e6f4f0;
}
[data-theme="dark"] .gallery-card-item {
    background: #162923;
    border-color: #223d34;
}
[data-theme="dark"] .card-title-heading a,
[data-theme="dark"] .card-title-heading { color: #e6f4f0; }
[data-theme="dark"] .card-desc-paragraph { color: #9bb0a9; }
[data-theme="dark"] .card-footer-strip { border-color: #223d34; }
[data-theme="dark"] .card-detail-btn { color: #34d399; }
[data-theme="dark"] .gallery-pagination .page-link {
    background: rgba(30, 41, 59, 0.7);
    border-color: rgba(255, 255, 255, 0.15);
    color: #94a3b8;
}
[data-theme="dark"] .gallery-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #00A878, #006B54) !important;
    color: #ffffff !important;
}
</style>

<div class="gallery-portal-page" style="background-color: var(--gov-bg, #F7F9F8); min-height: 80vh; padding-bottom: 60px;">
    <!-- Main Content Container with max-width: 1320px & reduced top whitespace (Section 2 & 3) -->
    <div class="container pt-3 pb-2" style="max-width: 1320px;">
        
        <!-- Breadcrumb (Section 4) -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb m-0 align-items-center" style="font-size: 15px;">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none fw-semibold" style="color: #006B54;"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #71807B;">คลังภาพกิจกรรมจังหวัดพัทลุง</li>
            </ol>
        </nav>

        <!-- Page Title Box (Section 5) -->
        <div class="gallery-title-box mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="gallery-title-icon">
                    <i class="fa-solid fa-camera-retro"></i>
                </div>
                <div>
                    <h1 class="gallery-title-text mb-0">คลังภาพกิจกรรมจังหวัดพัทลุง</h1>
                    <p class="gallery-title-sub mb-0 text-muted small">รวมภาพบรรยากาศกิจกรรมสำคัญ งานประเพณี และเรื่องราวของจังหวัดพัทลุง</p>
                </div>
            </div>

            <?php if ($isOfficer): ?>
                <div class="flex-shrink-0">
                    <button type="button" onclick="GalleryStudio.open()" class="btn btn-emerald rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #00A878, #006B54); color: #fff; border: none;">
                        <i class="fa-solid fa-circle-plus"></i>
                        <span>สร้างอัลบั้มใหม่</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Category Filter & Search Toolbar (Section 6 & 7) -->
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
            <!-- Filter Pills -->
            <div class="gallery-filter-scroll d-flex flex-wrap gap-2 align-items-center">
                <button type="button" class="btn gallery-pill-btn <?= ($selectedCat === 'all') ? 'active' : '' ?>" onclick="filterGalleryCat('all', this)">
                    <i class="fa-solid fa-border-all me-1.5"></i> ทั้งหมด (<?= count($albums) ?>)
                </button>
                <?php foreach ($categories as $cat): 
                    $count = 0;
                    foreach ($albums as $a) {
                        if (strcasecmp(trim($a['category'] ?? ''), trim($cat)) === 0) $count++;
                    }
                    $icon = 'fa-landmark-dome';
                    if (strpos($cat, 'ท่องเที่ยว') !== false || strpos($cat, 'อนุรักษ์') !== false) $icon = 'fa-leaf';
                    elseif (strpos($cat, 'สาธารณประโยชน์') !== false) $icon = 'fa-users';
                    elseif (strpos($cat, 'เศรษฐกิจ') !== false) $icon = 'fa-store';
                    elseif (strpos($cat, 'นวัตกรรม') !== false || strpos($cat, 'ศึกษา') !== false) $icon = 'fa-graduation-cap';
                ?>
                    <button type="button" class="btn gallery-pill-btn <?= ($selectedCat === $cat) ? 'active' : '' ?>" onclick="filterGalleryCat('<?= esc($cat, 'js') ?>', this)">
                        <i class="fa-solid <?= $icon ?> me-1.5"></i> <?= esc($cat) ?> (<?= $count ?>)
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Search Box -->
            <div class="gallery-search-wrap flex-shrink-0">
                <div class="gallery-search-input-group">
                    <input type="text" id="albumSearchInput" class="form-control" placeholder="ค้นหาชื่อกิจกรรม..." onkeyup="searchAlbums(this.value)">
                    <button class="btn btn-search-circle" type="button" aria-label="ค้นหา">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Albums Grid Container (Section 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18) -->
        <div class="row g-4" id="galleryGridContainer">
            <?php foreach ($albums as $item): 
                $coverUrl = (!empty($item['cover_image']) && (strpos($item['cover_image'], 'http') === 0 || strpos($item['cover_image'], 'data:') === 0 || strpos($item['cover_image'], 'uploads/') === 0)) ? ((strpos($item['cover_image'], 'http') === 0) ? $item['cover_image'] : base_url($item['cover_image'])) : 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80';
                $photoCount = !empty($item['photos']) ? count($item['photos']) : (!empty($item['photo_count']) ? (int)$item['photo_count'] : 1);
                $catName = esc($item['category'] ?? 'ประเพณีและวัฒนธรรม');
                $location = esc($item['location'] ?? 'วัดคูหาสวรรค์ อ.เมืองพัทลุง จ.พัทลุง');
                $desc = esc($item['description'] ?? 'กิจกรรมสำคัญของจังหวัดพัทลุง ที่สะท้อนเอกลักษณ์ทางวัฒนธรรมและความร่วมมือของประชาชนในท้องถิ่น');
                
                // Category badge styling & icon
                $catBadgeClass = 'badge-blue';
                $catIcon = 'fa-camera';
                if (strpos($catName, 'ท่องเที่ยว') !== false || strpos($catName, 'อนุรักษ์') !== false) {
                    $catBadgeClass = 'badge-green';
                    $catIcon = 'fa-leaf';
                } elseif (strpos($catName, 'สาธารณประโยชน์') !== false) {
                    $catBadgeClass = 'badge-teal';
                    $catIcon = 'fa-users';
                } elseif (strpos($catName, 'เศรษฐกิจ') !== false || strpos($catName, 'นวัตกรรม') !== false) {
                    $catBadgeClass = 'badge-darkgreen';
                    $catIcon = 'fa-store';
                }
            ?>
                <div class="col-12 col-md-6 col-lg-4 album-grid-item" data-category="<?= $catName ?>" data-title="<?= esc(strtolower($item['title'] ?? '')) ?>" data-desc="<?= esc(strtolower($desc)) ?>" data-loc="<?= esc(strtolower($location)) ?>">
                    <div class="gallery-card-item">
                        <!-- Cover Image Wrapper (16:9 Aspect Ratio) -->
                        <div class="card-thumb-wrap">
                            <img src="<?= $coverUrl ?>" alt="<?= esc($item['title']) ?>" class="card-thumb-img" loading="lazy">
                            <div class="card-thumb-overlay"></div>
                            
                            <!-- Badges -->
                            <span class="card-badge-cat <?= $catBadgeClass ?>">
                                <i class="fa-solid <?= $catIcon ?> me-1"></i><?= $catName ?>
                            </span>
                            <span class="card-badge-date">
                                <i class="fa-regular fa-calendar me-1"></i><?= format_thai_date_medium($item['date'] ?? 'now') ?>
                            </span>
                            <span class="card-badge-count">
                                <i class="fa-solid fa-camera me-1"></i><?= $photoCount ?> รูป
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="card-content-wrap">
                            <div>
                                <h3 class="card-title-heading">
                                    <a href="<?= base_url('gallery/album/' . $item['id']) ?>" class="text-decoration-none text-reset">
                                        <?= esc($item['title']) ?>
                                    </a>
                                </h3>
                                <p class="card-desc-paragraph">
                                    <?= $desc ?>
                                </p>
                            </div>

                            <!-- Card Footer -->
                            <div class="card-footer-strip">
                                <div class="card-loc-text">
                                    <i class="fa-solid fa-location-dot me-1" style="color: #008F6B;"></i>
                                    <span class="text-truncate" style="max-width: 170px;" title="<?= $location ?>"><?= $location ?></span>
                                </div>
                                <a href="<?= base_url('gallery/album/' . $item['id']) ?>" class="card-detail-btn">
                                    ดูรายละเอียด <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>

                            <?php if ($isOfficer): ?>
                                <div class="mt-2 pt-2 border-top d-flex justify-content-end gap-2">
                                    <button type="button" onclick="GalleryStudio.open('<?= $item['id'] ?>', '<?= esc($item['category'], 'js') ?>')" class="btn btn-sm btn-info text-dark rounded-pill px-3 fw-bold">
                                        <i class="fa-solid fa-pen me-1"></i> แก้ไข
                                    </button>
                                    <button type="button" onclick="GalleryStudio.deleteAlbum('<?= $item['id'] ?>', '<?= esc($item['title'], 'js') ?>')" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fa-solid fa-trash me-1"></i> ลบ
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Empty State Message -->
            <div id="noAlbumsMsg" class="col-12 text-center py-5 text-muted d-none">
                <i class="fa-solid fa-folder-open fs-1 text-secondary mb-3 d-block opacity-50"></i>
                <h5 class="fw-bold">ไม่พบกิจกรรมที่ค้นหา</h5>
                <p class="small text-muted">กรุณาลองค้นหาด้วยคำอื่น หรือเลือกหมวดหมู่อื่น</p>
                <button type="button" onclick="filterGalleryCat('all'); if(document.getElementById('albumSearchInput')) { document.getElementById('albumSearchInput').value=''; searchAlbums(''); }" class="btn btn-sm btn-outline-success rounded-pill px-4 mt-2">
                    <i class="fa-solid fa-rotate-left me-1"></i> ล้างการค้นหา
                </button>
            </div>
        </div>

        <!-- Pagination Navigation (Shows exactly 6 albums per page) -->
        <div id="galleryPaginationContainer" class="mt-5 d-flex justify-content-center"></div>
    </div>
</div>

<!-- Floating Action Button: ติดต่อเจ้าหน้าที่ (Bottom Left - Section 19) -->
<div class="floating-action-contact position-fixed">
    <a href="<?= base_url('contact') ?>" class="btn-float-officer">
        <i class="fa-solid fa-headphones-simple fs-5"></i>
        <span>ติดต่อเจ้าหน้าที่</span>
    </a>
</div>

<!-- Include Gallery Studio Component for officers -->
<?= $this->include('components/gallery_studio') ?>

<script>
var activeCat = "<?= esc($selectedCat, 'js') ?>";
var activeSearch = "";
var currentPage = 1;
var itemsPerPage = 6;

function filterGalleryCat(cat, btnEl) {
    activeCat = cat;
    currentPage = 1;
    if (btnEl) {
        document.querySelectorAll('.gallery-pill-btn').forEach(el => el.classList.remove('active'));
        btnEl.classList.add('active');
    }
    applyGalleryFilters();
}

function searchAlbums(query) {
    activeSearch = (query || "").toLowerCase().trim();
    currentPage = 1;
    applyGalleryFilters();
}

function goToPage(page) {
    currentPage = page;
    applyGalleryFilters();
    var target = document.getElementById('galleryGridContainer');
    if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function applyGalleryFilters() {
    var allItems = Array.from(document.querySelectorAll('#galleryGridContainer .album-grid-item'));
    var matchingItems = [];

    allItems.forEach(item => {
        var c = (item.getAttribute('data-category') || '').toLowerCase();
        var t = (item.getAttribute('data-title') || '').toLowerCase();
        var d = (item.getAttribute('data-desc') || '').toLowerCase();
        var l = (item.getAttribute('data-loc') || '').toLowerCase();
        
        var matchCat = (activeCat === 'all' || c === activeCat.toLowerCase());
        var matchSearch = (!activeSearch || t.indexOf(activeSearch) > -1 || c.indexOf(activeSearch) > -1 || d.indexOf(activeSearch) > -1 || l.indexOf(activeSearch) > -1);

        if (matchCat && matchSearch) {
            matchingItems.push(item);
        } else {
            item.style.display = 'none';
        }
    });

    var totalMatches = matchingItems.length;
    var totalPages = Math.ceil(totalMatches / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    if (currentPage < 1) currentPage = 1;

    var startIndex = (currentPage - 1) * itemsPerPage;
    var endIndex = startIndex + itemsPerPage;

    matchingItems.forEach((item, index) => {
        if (index >= startIndex && index < endIndex) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });

    var emptyEl = document.getElementById('noAlbumsMsg');
    if (emptyEl) {
        if (totalMatches === 0) emptyEl.classList.remove('d-none');
        else emptyEl.classList.add('d-none');
    }

    renderPagination(totalPages, totalMatches);
}

function renderPagination(totalPages, totalMatches) {
    var paginationContainer = document.getElementById('galleryPaginationContainer');
    if (!paginationContainer) return;

    if (totalMatches <= itemsPerPage) {
        paginationContainer.innerHTML = '';
        paginationContainer.classList.add('d-none');
        return;
    }

    paginationContainer.classList.remove('d-none');
    var html = '<ul class="pagination gallery-pagination justify-content-center m-0 flex-wrap gap-1">';
    
    // Previous Page Button
    html += '<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '">';
    html += '<a class="page-link" href="javascript:void(0)" onclick="goToPage(' + (currentPage - 1) + ')"><i class="fa-solid fa-chevron-left me-1"></i>ก่อนหน้า</a>';
    html += '</li>';

    // Page numbers
    for (var i = 1; i <= totalPages; i++) {
        html += '<li class="page-item ' + (currentPage === i ? 'active' : '') + '">';
        html += '<a class="page-link" href="javascript:void(0)" onclick="goToPage(' + i + ')">' + i + '</a>';
        html += '</li>';
    }

    // Next Page Button
    html += '<li class="page-item ' + (currentPage === totalPages ? 'disabled' : '') + '">';
    html += '<a class="page-link" href="javascript:void(0)" onclick="goToPage(' + (currentPage + 1) + ')">ถัดไป<i class="fa-solid fa-chevron-right ms-1"></i></a>';
    html += '</li>';

    html += '</ul>';
    paginationContainer.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', function() {
    applyGalleryFilters();
});
</script>

<?= $this->endSection() ?>

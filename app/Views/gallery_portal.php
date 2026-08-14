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
/* --- Premium Modern Photo Gallery Directory Styling --- */
.gallery-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0284c7 100%);
    border-radius: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(2, 132, 199, 0.25);
}
.gallery-hero::after {
    content: '';
    position: absolute;
    bottom: -50px;
    right: -50px;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.album-card {
    border: 1px solid var(--glass-border);
    border-radius: 1.25rem;
    overflow: hidden;
    background: var(--card-bg, #ffffff);
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.album-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: #38bdf8;
}
.album-cover-wrapper {
    position: relative;
    padding-top: 65%; /* 16:10 aspect ratio */
    overflow: hidden;
    background: #0f172a;
}
.album-cover-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.album-card:hover .album-cover-img {
    transform: scale(1.1);
}
.album-badge-count {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    color: #38bdf8;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
    border: 1px solid rgba(56, 189, 248, 0.4);
    z-index: 2;
}
.album-badge-cat {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #0284c7, #0369a1);
    color: #ffffff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    z-index: 2;
}
.album-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, transparent 40%, rgba(0,0,0,0.6) 100%);
    z-index: 1;
}
.album-title {
    color: var(--text-primary, #0f172a);
    font-size: 1.1rem;
    font-weight: 700;
    line-height: 1.4;
    transition: color 0.2s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.album-card:hover .album-title {
    color: #0284c7 !important;
}
.cat-pill-btn {
    border: 1px solid rgba(15, 23, 42, 0.15);
    background: var(--card-bg, #ffffff);
    color: var(--text-primary);
    padding: 8px 20px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.25s ease;
}
.cat-pill-btn:hover, .cat-pill-btn.active {
    background: linear-gradient(135deg, #0284c7, #0369a1);
    color: #ffffff !important;
    border-color: transparent;
    box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
}

/* Dark Mode Support */
[data-theme="dark"] .album-card {
    background: rgba(30, 41, 59, 0.65);
    border-color: rgba(255,255,255,0.1);
}
[data-theme="dark"] .album-title {
    color: #f8fafc;
}
[data-theme="dark"] .album-card:hover .album-title {
    color: #38bdf8 !important;
}
[data-theme="dark"] .cat-pill-btn {
    background: rgba(30, 41, 59, 0.7);
    border-color: rgba(255,255,255,0.15);
    color: #94a3b8;
}
[data-theme="dark"] .cat-pill-btn:hover, [data-theme="dark"] .cat-pill-btn.active {
    background: linear-gradient(135deg, #38bdf8, #0284c7);
    color: #0f172a !important;
    font-weight: 700;
}
</style>

<div class="container my-5 pt-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb m-0 small">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none fw-bold"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
            <li class="breadcrumb-item active text-secondary" aria-current="page">คลังภาพกิจกรรมและประเพณี (Photo Albums)</li>
        </ol>
    </nav>

    <!-- Hero Header Banner -->
    <div class="gallery-hero text-center py-5 px-4 mb-5 text-white d-flex flex-column align-items-center justify-content-center">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3 shadow-sm d-inline-flex align-items-center gap-1">
            <i class="fa-solid fa-camera-retro"></i>
            <span>Phatthalung Visual Archive</span>
        </span>
        <h1 class="fw-bold m-0 display-6 text-white d-flex align-items-center gap-2" style="text-shadow: 0 2px 10px rgba(0,0,0,0.4);">
            <span>คลังภาพกิจกรรมและประเพณี</span>
        </h1>
        <p class="text-white-50 mt-2 mb-0 small max-w-lg">ศูนย์บันทึกประวัติศาสตร์ งานบุญประเพณี ภารกิจผู้ว่าราชการจังหวัด และสถานที่ท่องเที่ยวของจังหวัดพัทลุง</p>
        
        <?php if ($isOfficer): ?>
            <div class="mt-4">
                <button type="button" onclick="GalleryStudio.open()" class="btn btn-warning rounded-pill px-4 py-2 fw-bold text-dark shadow-lg d-inline-flex align-items-center gap-2 hover-scale" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                    <i class="fa-solid fa-circle-plus fs-5"></i>
                    <span>+ สร้างอัลบั้มภาพกิจกรรมใหม่ (Studio)</span>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom" style="border-color: rgba(15,23,42,0.1) !important;">
        <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start">
            <button type="button" class="btn cat-pill-btn <?= ($selectedCat === 'all') ? 'active' : '' ?>" onclick="filterGalleryCat('all', this)">
                <i class="fa-solid fa-border-all me-1"></i> ทุกหมวดหมู่ (<?= count($albums) ?>)
            </button>
            <?php foreach ($categories as $cat): 
                $count = 0;
                foreach ($albums as $a) {
                    if (strcasecmp(trim($a['category'] ?? ''), trim($cat)) === 0) $count++;
                }
            ?>
                <button type="button" class="btn cat-pill-btn <?= ($selectedCat === $cat) ? 'active' : '' ?>" onclick="filterGalleryCat('<?= esc($cat, 'js') ?>', this)">
                    <?= esc($cat) ?> (<?= $count ?>)
                </button>
            <?php endforeach; ?>
        </div>

        <div class="input-group shadow-sm rounded-pill overflow-hidden border border-primary-subtle" style="max-width: 320px;">
            <input type="text" id="albumSearchInput" class="form-control border-0 py-2 px-4" placeholder="ค้นหาชื่ออัลบั้ม..." onkeyup="searchAlbums(this.value)">
            <span class="input-group-text bg-transparent border-0 text-primary pe-3">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
        </div>
    </div>

    <!-- Albums Grid Container -->
    <div class="row g-4" id="galleryGridContainer">
        <?php foreach ($albums as $item): 
            $coverUrl = (!empty($item['cover_image']) && (strpos($item['cover_image'], 'http') === 0 || strpos($item['cover_image'], 'data:') === 0 || strpos($item['cover_image'], 'uploads/') === 0)) ? ((strpos($item['cover_image'], 'http') === 0) ? $item['cover_image'] : base_url($item['cover_image'])) : 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80';
            $photoCount = !empty($item['photos']) ? count($item['photos']) : 0;
            $catName = esc($item['category'] ?? 'กิจกรรมสาธารณประโยชน์');
        ?>
            <div class="col-12 col-sm-6 col-lg-4 album-grid-item" data-category="<?= $catName ?>" data-title="<?= esc(strtolower($item['title'] ?? '')) ?>">
                <a href="<?= base_url('gallery/album/' . $item['id']) ?>" class="text-decoration-none h-100 d-block">
                    <div class="album-card">
                        <!-- Cover Image with badges -->
                        <div class="album-cover-wrapper">
                            <img src="<?= $coverUrl ?>" alt="<?= esc($item['title']) ?>" class="album-cover-img" loading="lazy">
                            <div class="album-overlay"></div>
                            <span class="album-badge-cat"><?= $catName ?></span>
                            <span class="album-badge-count"><i class="fa-solid fa-camera me-1"></i> <?= $photoCount ?> ภาพ</span>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <h3 class="album-title mb-2"><?= esc($item['title']) ?></h3>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top text-muted small" style="border-color: rgba(0,0,0,0.06) !important;">
                                <span>
                                    <i class="fa-regular fa-calendar me-1 text-primary"></i>
                                    <?= format_thai_date_medium($item['date'] ?? 'now') ?>
                                </span>
                                <span>
                                    <i class="fa-regular fa-eye me-1 text-secondary"></i>
                                    <?= number_format($item['views'] ?? 1) ?> ครั้ง
                                </span>
                            </div>

                            <?php if ($isOfficer): ?>
                                <div class="mt-3 pt-2 d-flex justify-content-end gap-2" onclick="event.stopPropagation(); event.preventDefault();">
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
                </a>
            </div>
        <?php endforeach; ?>

        <!-- Empty State Message -->
        <div id="noAlbumsMsg" class="col-12 text-center py-5 text-muted d-none">
            <i class="fa-solid fa-folder-open fs-1 text-secondary mb-3 d-block opacity-50"></i>
            <h5 class="fw-bold">ไม่พบอัลบั้มภาพกิจกรรมในหมวดหมู่นี้</h5>
            <p class="small text-muted">ลองเลือกหมวดหมู่ใหม่ หรือค้นหาด้วยคีย์เวิร์ดอื่น</p>
        </div>
    </div>
</div>

<!-- Include Gallery Studio Component for officers -->
<?= $this->include('components/gallery_studio') ?>

<script>
var activeCat = "<?= esc($selectedCat, 'js') ?>";
var activeSearch = "";

function filterGalleryCat(cat, btnEl) {
    activeCat = cat;
    if (btnEl) {
        document.querySelectorAll('.cat-pill-btn').forEach(el => el.classList.remove('active'));
        btnEl.classList.add('active');
    }
    applyGalleryFilters();
}

function searchAlbums(query) {
    activeSearch = (query || "").toLowerCase().trim();
    applyGalleryFilters();
}

function applyGalleryFilters() {
    var count = 0;
    document.querySelectorAll('#galleryGridContainer .album-grid-item').forEach(item => {
        var c = item.getAttribute('data-category');
        var t = item.getAttribute('data-title');
        
        var matchCat = (activeCat === 'all' || c === activeCat);
        var matchSearch = (!activeSearch || t.indexOf(activeSearch) > -1);

        if (matchCat && matchSearch) {
            item.style.display = 'block';
            count++;
        } else {
            item.style.display = 'none';
        }
    });

    var emptyEl = document.getElementById('noAlbumsMsg');
    if (emptyEl) {
        if (count === 0) emptyEl.classList.remove('d-none');
        else emptyEl.classList.add('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (activeCat && activeCat !== 'all') {
        applyGalleryFilters();
    }
});
</script>

<?= $this->endSection() ?>

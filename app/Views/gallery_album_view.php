<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
helper('settings');
$isOfficer = session()->get('isLoggedIn');
$album = $album ?? [];
$photos = $album['photos'] ?? [];
$title = esc($album['title'] ?? 'ไม่ระบุชื่ออัลบั้ม');
$category = esc($album['category'] ?? 'กิจกรรมสาธารณประโยชน์');

if (!function_exists('format_thai_date_long')) {
    function format_thai_date_long($dateStr) {
        $timestamp = strtotime($dateStr);
        if (!$timestamp) return $dateStr;
        $months = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        $day = (int)date('j', $timestamp);
        $month = $months[(int)date('n', $timestamp)];
        $year = ((int)date('Y', $timestamp) + 543);
        return "$day $month $year";
    }
}

// Convert all photo URLs to full absolute or usable URLs for JS
$jsPhotoUrls = [];
foreach ($photos as $p) {
    if (!empty($p)) {
        $full = (strpos($p, 'http') === 0 || strpos($p, 'data:') === 0 || strpos($p, 'uploads/') === 0) ? ((strpos($p, 'http') === 0) ? $p : base_url($p)) : base_url($p);
        $jsPhotoUrls[] = $full;
    }
}

// Ensure at least cover image if photos list is empty
if (empty($jsPhotoUrls) && !empty($album['cover_image'])) {
    $c = $album['cover_image'];
    $jsPhotoUrls[] = (strpos($c, 'http') === 0) ? $c : base_url($c);
}
?>

<style>
.album-header-banner {
    background: linear-gradient(135deg, #0f172a, #0369a1);
    border-radius: 1.5rem;
    padding: 3rem 2rem;
    color: #ffffff;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}
.album-header-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 60%);
    pointer-events: none;
}
.photo-thumb-card {
    position: relative;
    border-radius: 1rem;
    overflow: hidden;
    background: #0f172a;
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    aspect-ratio: 4 / 3;
    cursor: zoom-in;
    border: 1.5px solid rgba(255,255,255,0.1);
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.photo-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.photo-thumb-card:hover {
    transform: scale(1.04) translateY(-5px);
    box-shadow: 0 18px 35px rgba(2, 132, 199, 0.4);
    border-color: #00f0ff !important;
    z-index: 2;
}
.photo-thumb-card:hover .photo-thumb-img {
    transform: scale(1.12);
}
.photo-thumb-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 10px;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    opacity: 0;
    transition: opacity 0.25s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.8rem;
}
.photo-thumb-card:hover .photo-thumb-overlay {
    opacity: 1;
}
</style>

<div class="container my-5 pt-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb m-0 small">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none fw-bold"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('gallery') ?>" class="text-info text-decoration-none fw-bold"><i class="fa-solid fa-camera-retro me-1"></i>คลังภาพกิจกรรม</a></li>
            <li class="breadcrumb-item active text-secondary text-truncate" style="max-width: 300px;" aria-current="page"><?= $title ?></li>
        </ol>
    </nav>

    <!-- Album Banner -->
    <div class="album-header-banner mb-5 text-center text-md-start d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div class="position-relative z-1 max-w-2xl">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 mb-3">
                <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill shadow-sm">
                    <?= $category ?>
                </span>
                <span class="text-white-50 small">
                    <i class="fa-regular fa-calendar text-info me-1"></i>
                    <?= format_thai_date_long($album['date'] ?? 'now') ?>
                </span>
                <span class="text-white-50 small">
                    <i class="fa-regular fa-eye text-success me-1"></i>
                    เข้าชม <?= number_format($album['views'] ?? 1) ?> ครั้ง
                </span>
            </div>

            <h1 class="fw-bold m-0 display-6 text-white" style="line-height: 1.3;">
                <?= $title ?>
            </h1>
            <p class="text-white-50 mt-3 mb-0 small">
                <i class="fa-solid fa-circle-info text-info me-1"></i> คลิกที่รูปภาพใดก็ได้เพื่อเปิดหน้าต่างรับชมภาพความละเอียดสูงแบบ ShadowBox (รองรับการขยายและเลื่อนดูทั้งชุด)
            </p>
        </div>

        <div class="d-flex flex-column gap-2 flex-shrink-0">
            <?php if ($isOfficer): ?>
                <button type="button" onclick="GalleryStudio.open('<?= $album['id'] ?>', '<?= esc($album['category'], 'js') ?>')" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow hover-scale d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                    <i class="fa-solid fa-cloud-arrow-up fs-5"></i>
                    <span>+ เพิ่มรูป / แก้ไขอัลบั้มนี้</span>
                </button>
            <?php endif; ?>

            <button type="button" onclick="openAllInShadowbox(0)" class="btn btn-outline-light fw-bold rounded-pill px-4 py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-expand text-info"></i>
                <span>เปิดดูแบบเต็มจอ (Fullscreen)</span>
            </button>
        </div>
    </div>

    <!-- Thumbnail Grid -->
    <div class="row g-3 g-md-4">
        <?php foreach ($jsPhotoUrls as $idx => $photoUrl): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="photo-thumb-card" onclick="openAllInShadowbox(<?= $idx ?>)">
                    <img src="<?= $photoUrl ?>" alt="Photo <?= $idx+1 ?>" class="photo-thumb-img" loading="lazy">
                    <div class="photo-thumb-overlay">
                        <span class="fw-bold"><i class="fa-solid fa-magnifying-glass-plus text-warning me-1"></i> ดูภาพนี้</span>
                        <span class="badge bg-dark text-info rounded-pill px-2">#<?= $idx + 1 ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Back to gallery button -->
    <div class="text-center mt-5 pt-4">
        <a href="<?= base_url('gallery') ?>" class="btn btn-lg btn-outline-primary rounded-pill px-5 fw-bold shadow-sm hover-scale">
            <i class="fa-solid fa-arrow-left me-2"></i> กลับสู่หน้าหลักคลังภาพกิจกรรมทั้งหมด
        </a>
    </div>
</div>

<!-- Include Gallery Studio Component for officers -->
<?= $this->include('components/gallery_studio') ?>

<script>
var currentAlbumPhotos = <?= json_encode($jsPhotoUrls) ?>;
var albumTitle = "<?= esc($album['title'], 'js') ?>";

function openAllInShadowbox(idx) {
    if (typeof ShadowBox !== 'undefined' && currentAlbumPhotos && currentAlbumPhotos.length > 0) {
        ShadowBox.open(currentAlbumPhotos, idx, albumTitle);
    } else {
        alert('กำลังโหลดระบบแสดงภาพ กรุณาสักครู่...');
    }
}
</script>

<?= $this->endSection() ?>

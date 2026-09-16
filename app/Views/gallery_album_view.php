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

<!-- Google Fonts for Phatthalung Design System: IBM Plex Sans Thai & Noto Sans Thai -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@500;600;700&family=Noto+Sans+Thai:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* --- Design System จังหวัดพัทลุง: คลังภาพกิจกรรม --- */
.album-view-wrapper {
    font-family: 'Noto Sans Thai', 'Sarabun', sans-serif;
    color: #1e293b;
}

.album-view-wrapper h1, 
.album-view-wrapper h2, 
.album-view-wrapper h3, 
.album-view-wrapper .font-heading {
    font-family: 'IBM Plex Sans Thai', sans-serif;
}

/* 1. แบนเนอร์สีน้ำเงินเข้มหรูหรา ขนาดพอเหมาะไม่เทอะทะ */
.album-header-banner {
    background: linear-gradient(135deg, #0b1e36 0%, #0d2e59 55%, #075985 100%);
    border-radius: 20px;
    padding: 1.75rem 2.25rem;
    color: #ffffff;
    box-shadow: 0 10px 30px -5px rgba(11, 30, 54, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.08);
    position: relative;
    overflow: hidden;
}
.album-header-banner::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
    pointer-events: none;
}
.album-header-banner::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: 10%;
    width: 260px;
    height: 260px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, transparent 70%);
    pointer-events: none;
}

/* Headline หัวข้อข่าว */
.album-banner-title {
    font-size: clamp(1.35rem, 2.2vw, 1.75rem);
    font-weight: 600;
    line-height: 1.45;
    color: #ffffff;
    letter-spacing: -0.01em;
    margin: 0;
}

/* Meta Data Bar */
.album-meta-bar {
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.85rem;
    font-size: 0.88rem;
}
.album-category-badge {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 0.32rem 0.95rem;
    border-radius: 999px;
    box-shadow: 0 2px 8px rgba(217, 119, 6, 0.35);
    letter-spacing: 0.01em;
}
.album-meta-item {
    color: rgba(226, 232, 240, 0.85); /* Contrast ผ่าน WCAG AA */
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 400;
}
.album-meta-item i {
    color: #38bdf8;
    font-size: 0.9rem;
}

/* 2. ปุ่มเปิดดูแบบเต็มจอระดับพรีเมียม */
.btn-fullscreen-luxury {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.28);
    border-radius: 999px;
    padding: 0.65rem 1.45rem;
    font-size: 0.92rem;
    font-weight: 600;
    letter-spacing: 0.01em;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.btn-fullscreen-luxury:hover {
    background: #047857; /* เขียวมรกตพัทลุง */
    border-color: #10b981;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(4, 120, 87, 0.4);
}
.btn-edit-luxury {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #ffffff !important;
    border: none;
    border-radius: 999px;
    padding: 0.65rem 1.45rem;
    font-size: 0.92rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}
.btn-edit-luxury:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(217, 119, 6, 0.45);
    filter: brightness(1.05);
}

/* 3. คำแนะนำ (Instruction Box) โทนเขียวมรกต/ทอง ชัดเจน คอนทราสต์สูง */
.instruction-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.55rem;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46; /* เขียวเข้ม คอนทราสต์สูงผ่าน WCAG AA > 7:1 */
    padding: 0.55rem 1.15rem;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 500;
    box-shadow: 0 2px 6px rgba(5, 150, 105, 0.06);
}
.instruction-pill i {
    color: #059669;
    font-size: 1rem;
}

/* 4. Grid รูปภาพพร้อมระยะห่าง (Gutter) ที่สมดุล */
.photo-thumb-card {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.06);
    aspect-ratio: 4 / 3;
    cursor: zoom-in;
    border: 1px solid #e2e8f0;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s ease;
}
.photo-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.photo-thumb-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px -4px rgba(4, 120, 87, 0.22);
    border-color: #059669; /* เส้นขอบเขียวมรกต */
}
.photo-thumb-card:hover .photo-thumb-img {
    transform: scale(1.06);
}
.photo-thumb-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 10px 12px;
    background: linear-gradient(to top, rgba(11, 30, 54, 0.85) 0%, rgba(11, 30, 54, 0.4) 65%, transparent 100%);
    color: #ffffff;
    opacity: 0;
    transition: opacity 0.22s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.82rem;
}
.photo-thumb-card:hover .photo-thumb-overlay {
    opacity: 1;
}

/* Breadcrumb Styling */
.gallery-breadcrumb {
    margin-bottom: 2rem; /* เพิ่มระยะห่างจากแบนเนอร์ */
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    padding: 0.55rem 1.25rem;
    display: inline-flex;
    align-items: center;
}
.gallery-breadcrumb a {
    color: #047857;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: color 0.15s ease;
}
.gallery-breadcrumb a:hover {
    color: #065f46;
    text-decoration: underline;
}
.gallery-breadcrumb .active-item {
    color: #64748b;
    font-size: 0.9rem;
    font-weight: 400;
}
</style>

<div class="container my-4 album-view-wrapper">
    <!-- Breadcrumb: ย้ายและเพิ่มระยะห่างจากแบนเนอร์อย่างสมดุล -->
    <div class="d-flex align-items-center mb-3">
        <nav aria-label="breadcrumb" class="gallery-breadcrumb shadow-sm">
            <ol class="breadcrumb m-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="<?= base_url() ?>"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('gallery') ?>"><i class="fa-solid fa-camera-retro me-1"></i>คลังภาพกิจกรรม</a>
                </li>
                <li class="breadcrumb-item active text-truncate active-item" style="max-width: 280px;" aria-current="page" title="<?= $title ?>">
                    <?= $title ?>
                </li>
            </ol>
        </nav>
    </div>

    <!-- 1. Album Header Banner (หรูหรา น้ำเงินเข้ม สัดส่วนพอเหมาะกับเนื้อหา) -->
    <div class="album-header-banner mb-4">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 position-relative z-1">
            <div class="flex-grow-1">
                <!-- Meta Data: จัดเป็นแถวเดียวกัน สีจางลงกว่าหัวข้อ เพื่อ Hierarchy ชัดเจน -->
                <div class="album-meta-bar mb-2.5">
                    <span class="album-category-badge">
                        <i class="fa-solid fa-tag me-1"></i><?= $category ?>
                    </span>
                    <span class="album-meta-item">
                        <i class="fa-regular fa-calendar"></i>
                        <?= format_thai_date_long($album['date'] ?? 'now') ?>
                    </span>
                    <span class="album-meta-item">
                        <i class="fa-regular fa-eye"></i>
                        เข้าชม <?= number_format($album['views'] ?? 1) ?> ครั้ง
                    </span>
                    <span class="album-meta-item">
                        <i class="fa-regular fa-images"></i>
                        <?= count($jsPhotoUrls) ?> รูปภาพ
                    </span>
                </div>

                <!-- Headline หัวข้อข่าว: ขนาดเล็กลงแต่ชัดเจน font-weight 600 -->
                <h1 class="album-banner-title">
                    <?= $title ?>
                </h1>
            </div>

            <!-- Action Buttons: ขอบมน เพิ่ม Shadow บางๆ ดูพรีเมียม -->
            <div class="d-flex flex-wrap flex-sm-nowrap align-items-center gap-2 flex-shrink-0 mt-2 mt-lg-0">
                <?php if ($isOfficer): ?>
                    <button type="button" onclick="GalleryStudio.open('<?= $album['id'] ?>', '<?= esc($album['category'], 'js') ?>')" class="btn btn-edit-luxury">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>+ เพิ่มรูป / แก้ไขอัลบั้ม</span>
                    </button>
                <?php endif; ?>

                <button type="button" onclick="openAllInShadowbox(0)" class="btn btn-fullscreen-luxury">
                    <i class="fa-solid fa-expand text-warning"></i>
                    <span>เปิดดูแบบเต็มจอ (Fullscreen)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. UX Instruction Text: มีไอคอนกำกับชัดเจน สีเขียวมรกตคอนทราสต์เด่นชัด -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div class="instruction-pill">
            <i class="fa-solid fa-circle-info"></i>
            <span>คลิกที่รูปภาพเพื่อเปิดรับชมภาพความละเอียดสูงแบบ ShadowBox (รองรับการขยายภาพและสลับภาพด้วยปุ่มลูกศร)</span>
        </div>
        <div class="text-muted small fw-medium">
            <i class="fa-solid fa-layer-group text-primary me-1"></i> แสดงทั้งหมด <?= count($jsPhotoUrls) ?> ภาพ
        </div>
    </div>

    <!-- 4. Photo Grid: จัดระยะห่าง Gutter ที่สวยงาม (row g-3 g-md-4) -->
    <div class="row g-3 g-md-4">
        <?php foreach ($jsPhotoUrls as $idx => $photoUrl): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="photo-thumb-card" onclick="openAllInShadowbox(<?= $idx ?>)">
                    <img src="<?= $photoUrl ?>" alt="<?= $title ?> - รูปที่ <?= $idx+1 ?>" class="photo-thumb-img" loading="lazy">
                    <div class="photo-thumb-overlay">
                        <span class="fw-semibold"><i class="fa-solid fa-magnifying-glass-plus text-warning me-1"></i> ขยายภาพ</span>
                        <span class="badge bg-dark-subtle text-white rounded-pill px-2">#<?= $idx + 1 ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Back to gallery button -->
    <div class="text-center mt-5 pt-3 mb-4">
        <a href="<?= base_url('gallery') ?>" class="btn btn-outline-success rounded-pill px-4 py-2 fw-semibold shadow-sm" style="border-color: #059669; color: #047857;">
            <i class="fa-solid fa-arrow-left me-2"></i> กลับสู่หน้าหลักคลังภาพกิจกรรม
        </a>
    </div>
</div>

<!-- Include Gallery Studio Component for officers -->
<?= $this->include('components/gallery_studio') ?>

<!-- Include ShadowBox Component directly -->
<?= $this->include('components/shadow_box') ?>

<script>
var currentAlbumPhotos = <?= json_encode($jsPhotoUrls) ?>;
var albumTitle = "<?= esc($album['title'], 'js') ?>";

function openAllInShadowbox(idx) {
    if (!currentAlbumPhotos || currentAlbumPhotos.length === 0) return;

    if (typeof ShadowBox !== 'undefined' && window.ShadowBox) {
        window.ShadowBox.open(currentAlbumPhotos, idx, albumTitle);
        return;
    }

    // Immediate fallback viewer - zero delay, no alert!
    openQuickLightbox(idx);
}

function openQuickLightbox(idx) {
    var curIdx = idx || 0;
    var overlay = document.getElementById('quickAlbumLightbox');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'quickAlbumLightbox';
        overlay.style.cssText = 'position:fixed;inset:0;background:rgba(4,8,20,0.95);backdrop-filter:blur(15px);z-index:99999;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:15px;';
        overlay.innerHTML = `
            <div style="position:absolute;top:15px;right:20px;display:flex;gap:10px;z-index:100000;">
                <a id="qlbDownload" href="#" target="_blank" class="btn btn-dark rounded-circle p-2 d-flex align-items-center justify-content-center shadow-lg" style="width:44px;height:44px;border:1px solid rgba(255,255,255,0.3);background:rgba(30,41,59,0.85);" title="ดาวน์โหลดภาพ">
                    <i class="fa-solid fa-download text-info fs-5"></i>
                </a>
                <button type="button" id="qlbClose" class="btn btn-danger rounded-circle p-2 d-flex align-items-center justify-content-center shadow-lg" style="width:44px;height:44px;border:2px solid rgba(255,255,255,0.4);" title="ปิดหน้าต่าง [ESC]">
                    <i class="fa-solid fa-xmark fs-4 text-white fw-bold"></i>
                </button>
            </div>
            <div style="position:absolute;top:15px;left:20px;color:white;z-index:100000;">
                <h6 class="m-0 fw-bold text-truncate" style="max-width:55vw;" id="qlbTitle"></h6>
                <small class="text-info fw-bold" id="qlbCounter"></small>
            </div>
            <div style="position:relative;max-width:92vw;max-height:85vh;display:flex;align-items:center;justify-content:center;">
                <img id="qlbImg" src="" style="max-width:92vw;max-height:82vh;object-fit:contain;border-radius:12px;border:2px solid rgba(255,255,255,0.2);box-shadow:0 25px 50px rgba(0,0,0,0.85);cursor:pointer;" title="คลิกเพื่อสลับภาพถัดไป">
            </div>
            <button type="button" id="qlbPrev" class="btn btn-dark rounded-circle position-absolute top-50 start-0 translate-middle-y ms-2 ms-md-4 shadow-lg" style="width:52px;height:52px;border:1.5px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;z-index:100001;background:rgba(30,41,59,0.85);" title="ก่อนหน้า">
                <i class="fa-solid fa-chevron-left fs-4 text-white"></i>
            </button>
            <button type="button" id="qlbNext" class="btn btn-dark rounded-circle position-absolute top-50 end-0 translate-middle-y me-2 me-md-4 shadow-lg" style="width:52px;height:52px;border:1.5px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;z-index:100001;background:rgba(30,41,59,0.85);" title="ถัดไป">
                <i class="fa-solid fa-chevron-right fs-4 text-white"></i>
            </button>
        `;
        document.body.appendChild(overlay);

        document.getElementById('qlbClose').onclick = function() {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        };
        overlay.onclick = function(e) {
            if (e.target === overlay) {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        };
        document.getElementById('qlbImg').onclick = function() {
            if (currentAlbumPhotos.length > 1) {
                curIdx = (curIdx + 1) % currentAlbumPhotos.length;
                render();
            }
        };
        document.getElementById('qlbPrev').onclick = function(e) {
            e.stopPropagation();
            if (currentAlbumPhotos.length > 1) {
                curIdx = (curIdx - 1 + currentAlbumPhotos.length) % currentAlbumPhotos.length;
                render();
            }
        };
        document.getElementById('qlbNext').onclick = function(e) {
            e.stopPropagation();
            if (currentAlbumPhotos.length > 1) {
                curIdx = (curIdx + 1) % currentAlbumPhotos.length;
                render();
            }
        };
        document.addEventListener('keydown', function(e) {
            if (overlay.style.display === 'flex') {
                if (e.key === 'Escape') {
                    overlay.style.display = 'none';
                    document.body.style.overflow = '';
                } else if (e.key === 'ArrowLeft') {
                    document.getElementById('qlbPrev').click();
                } else if (e.key === 'ArrowRight') {
                    document.getElementById('qlbNext').click();
                }
            }
        });
    }

    function render() {
        var photoUrl = currentAlbumPhotos[curIdx] || '';
        document.getElementById('qlbImg').src = photoUrl;
        document.getElementById('qlbTitle').innerText = albumTitle || 'อัลบั้มภาพกิจกรรม';
        document.getElementById('qlbCounter').innerText = 'ภาพที่ ' + (curIdx + 1) + ' จาก ' + currentAlbumPhotos.length;
        document.getElementById('qlbDownload').href = photoUrl;
        document.getElementById('qlbPrev').style.display = currentAlbumPhotos.length > 1 ? 'flex' : 'none';
        document.getElementById('qlbNext').style.display = currentAlbumPhotos.length > 1 ? 'flex' : 'none';
    }

    render();
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
</script>

<?= $this->endSection() ?>

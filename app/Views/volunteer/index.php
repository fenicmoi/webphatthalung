<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
/* ==========================================================================
   ROYAL VOLUNTEER 904 PORTAL (ศูนย์อำนวยการจิตอาสาพระราชทาน จังหวัดพัทลุง)
   Theme: Royal Amber Gold & Dignified Sky Blue
   ========================================================================== */
.volunteer-hero-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0369a1 100%);
    border: 2px solid rgba(245, 158, 11, 0.4);
    border-radius: 1.75rem;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35), 0 0 30px rgba(245, 158, 11, 0.15);
    position: relative;
    overflow: hidden;
    color: #ffffff;
}
.volunteer-hero-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.18) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.volunteer-badge-glow {
    box-shadow: 0 0 25px rgba(245, 158, 11, 0.5);
    border: 3px solid #fbbf24;
}
.volunteer-motto {
    font-family: 'Sarabun', 'Prompt', sans-serif;
    color: #fef08a;
    letter-spacing: 0.5px;
    font-style: italic;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}
.pillar-card {
    border-radius: 1.25rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(0, 0, 0, 0.08);
}
.pillar-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}
[data-theme="dark"] .pillar-card {
    background: rgba(30, 41, 59, 0.9);
    border-color: rgba(255, 255, 255, 0.1);
    color: #f8fafc;
}
.news-volunteer-card {
    border-radius: 1.25rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(226, 232, 240, 0.8);
    background: #ffffff;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.news-volunteer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    border-color: #f59e0b;
}
[data-theme="dark"] .news-volunteer-card {
    background: #1e293b;
    border-color: #334155;
    color: #f8fafc;
}
.volunteer-btn-add {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #0f172a !important;
    font-weight: 700;
    border: 2px solid #fde68a;
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.35);
    transition: all 0.3s ease;
}
.volunteer-btn-add:hover {
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 12px 25px rgba(245, 158, 11, 0.5);
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}
</style>

<div class="container py-4">

    <!-- 1. BREADCRUMB NAVIGATION -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0 py-2 px-3 rounded-4 shadow-xs" style="background: rgba(0, 59, 48, 0.05);">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none text-success fw-medium"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
            <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">จิตอาสาพระราชทาน จังหวัดพัทลุง</li>
        </ol>
    </nav>

    <!-- 2. ROYAL VOLUNTEER HERO BANNER (Dignified Royal Amber Gold & Deep Sky Blue) -->
    <div class="volunteer-hero-card p-4 p-lg-5 mb-5 position-relative">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.5);">
                    <i class="fa-solid fa-crown text-warning fs-6"></i>
                    <span class="small fw-bold text-warning" style="letter-spacing: 0.5px;">ศูนย์อำนวยการจิตอาสาพระราชทาน จังหวัดพัทลุง (ศอ.จอส.พระราชทาน)</span>
                </div>
                <h1 class="display-6 fw-bold mb-2 text-white" style="line-height: 1.25;">
                    จิตอาสาพระราชทาน จังหวัดพัทลุง
                </h1>
                <div class="volunteer-motto fs-5 fw-bold mb-3">
                    “เราทำความ ดี ด้วยหัวใจ”
                </div>
                <p class="text-light opacity-90 mb-4" style="max-width: 680px; font-size: 0.98rem; line-height: 1.6;">
                    สืบสานพระราชปณิธานของพระบาทสมเด็จพระเจ้าอยู่หัว เพื่อเสริมสร้างความรัก ความสามัคคี และความผาสุกของประชาชนในพื้นที่จังหวัดพัทลุง ผ่านกิจกรรมจิตอาสาพัฒนา จิตอาสาภัยพิบัติ และจิตอาสาเฉพาะกิจ
                </p>

                <!-- Actions: Officer Post Button & Quick Navigation -->
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <?php if ($isOfficer): ?>
                    <button type="button" onclick="NewsStudio.open(null, 'จิตอาสาพระราชทาน')" class="btn volunteer-btn-add rounded-pill px-4 py-2.5 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-plus fs-5"></i>
                        <span>ลงข่าวกิจกรรมจิตอาสา (เฉพาะหน้านี้)</span>
                    </button>
                    <?php endif; ?>

                    <a href="#volunteerNewsSection" class="btn btn-outline-light rounded-pill px-4 py-2.5 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-newspaper text-warning"></i>
                        <span>ข่าวสารและภารกิจล่าสุด (<?= count($newsList) ?>)</span>
                    </a>

                    <a href="<?= base_url('gallery?category=' . urlencode('จิตอาสาพระราชทาน')) ?>" class="btn btn-outline-warning rounded-pill px-4 py-2.5 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-camera-retro"></i>
                        <span>ภาพกิจกรรมจิตอาสา</span>
                    </a>
                </div>
            </div>

            <!-- Emblem / Illustration Badge -->
            <div class="col-lg-4 text-center">
                <div class="d-inline-flex flex-column align-items-center p-4 rounded-4" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px);">
                    <div class="rounded-circle p-3 d-flex align-items-center justify-content-center volunteer-badge-glow mb-3" style="width: 110px; height: 110px; background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fa-solid fa-hands-holding-heart text-white" style="font-size: 3.2rem;"></i>
                    </div>
                    <span class="fw-bold text-white fs-5 mb-1">จิตอาสา 904</span>
                    <small class="text-warning fw-medium">จังหวัดพัทลุง</small>
                    <div class="mt-3 pt-2 border-top border-white border-opacity-20 text-white-50 small">
                        <i class="fa-solid fa-phone me-1 text-warning"></i>ศาลากลาง จ.พัทลุง โทร. 074-614130
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. THREE CORE MISSION PILLARS (จิตอาสาพัฒนา / ภัยพิบัติ / เฉพาะกิจ) -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h4 class="fw-bold m-0 text-dark" style="letter-spacing: -0.3px;">
                    <i class="fa-solid fa-shapes text-warning me-2"></i>3 ภารกิจหลักจิตอาสาพระราชทาน
                </h4>
                <small class="text-muted">โครงสร้างการดำเนินงานของศูนย์อำนวยการจิตอาสาพระราชทาน</small>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($pillars as $key => $p): ?>
            <div class="col-md-4">
                <div class="card pillar-card h-100 p-4 shadow-xs">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white shadow-xs" style="width: 48px; height: 48px; background: <?= $p['gradient'] ?>;">
                            <i class="fa-solid <?= $p['icon'] ?> fs-4"></i>
                        </div>
                        <span class="badge rounded-pill px-3 py-1.5 fw-bold" style="background: rgba(245, 158, 11, 0.15); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3);">
                            <?= $p['badge'] ?>
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1"><?= $p['title'] ?></h5>
                    <small class="text-muted mb-2 d-block"><?= $p['en'] ?></small>
                    <p class="small text-muted mb-0" style="line-height: 1.6;">
                        <?= $p['description'] ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 4. NEWS & ACTIVITY SECTION (ข่าวสารเฉพาะหน้าจิตอาสาพระราชทาน) -->
    <div class="mb-5" id="volunteerNewsSection">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 border-bottom pb-3">
            <div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-bold">หมวดหมู่เฉพาะ</span>
                    <h3 class="fw-bold m-0 text-dark">
                        <i class="fa-solid fa-bullhorn text-warning me-2"></i>ข่าวสารและกิจกรรมจิตอาสาพระราชทาน
                    </h3>
                </div>
                <small class="text-muted">รายงานภารกิจจิตอาสา ข่าวประชาสัมพันธ์ และกิจกรรมจิตอาสาพัฒนาในพื้นที่จังหวัดพัทลุง</small>
            </div>

            <!-- Instant Search Box & Officer Add Button -->
            <div class="d-flex align-items-center gap-2">
                <div class="input-group" style="max-width: 260px;">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="volunteerNewsSearch" class="form-control bg-light border-start-0" placeholder="ค้นหาข่าวในหน้านี้..." onkeyup="filterVolunteerNews(this.value)">
                </div>

                <?php if ($isOfficer): ?>
                <button type="button" onclick="NewsStudio.open(null, 'จิตอาสาพระราชทาน')" class="btn btn-warning fw-bold text-dark rounded-pill px-3 py-2 shadow-xs d-inline-flex align-items-center gap-1.5 flex-shrink-0" title="เพิ่มข่าวใหม่เฉพาะหมวดจิตอาสาพระราชทาน">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>ลงข่าวใหม่</span>
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- News Grid -->
        <?php if (!empty($newsList)): ?>
            <div class="row g-4" id="volunteerNewsGrid">
                <?php foreach ($newsList as $news): 
                    $coverImg = !empty($news['cover_image']) ? (str_starts_with($news['cover_image'], 'http') ? $news['cover_image'] : base_url($news['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png');
                    $dateText = !empty($news['created_at']) ? (function_exists('thai_date') ? thai_date(substr($news['created_at'], 0, 10), 'day_short') : substr($news['created_at'], 0, 10)) : date('d/m/Y');
                    $detailUrl = base_url('news/detail/' . ($news['id'] ?? ''));
                ?>
                <div class="col-md-6 col-lg-4 volunteer-news-item" data-title="<?= esc(strtolower($news['title'] ?? '')) ?>" data-content="<?= esc(strtolower($news['summary'] ?? '')) ?>">
                    <div class="news-volunteer-card shadow-xs">
                        <div class="position-relative overflow-hidden" style="height: 200px;">
                            <img src="<?= esc($coverImg) ?>" alt="<?= esc($news['title']) ?>" class="w-100 h-100" style="object-fit: <?= esc($news['cover_fit'] ?? 'cover') ?>; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            <span class="position-absolute top-0 start-0 m-3 badge rounded-pill px-3 py-1.5 text-white fw-bold shadow-xs" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(5px); border: 1px solid rgba(245, 158, 11, 0.6);">
                                <i class="fa-solid fa-calendar-day text-warning me-1"></i><?= $dateText ?>
                            </span>
                        </div>
                        <div class="p-3.5 d-flex flex-column flex-grow-1">
                            <h5 class="fw-bold mb-2 text-dark line-clamp-2" style="font-size: 1.05rem; line-height: 1.45;">
                                <a href="<?= $detailUrl ?>" class="text-decoration-none text-dark hover-warning">
                                    <?= esc($news['title']) ?>
                                </a>
                            </h5>
                            <p class="text-muted small mb-3 flex-grow-1 line-clamp-3" style="line-height: 1.6;">
                                <?= esc($news['summary']) ?>
                            </p>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <span class="small text-muted">
                                    <i class="fa-regular fa-eye me-1"></i><?= number_format($news['views'] ?? 0) ?> ครั้ง
                                </span>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($isOfficer): ?>
                                    <button type="button" onclick="NewsStudio.open(<?= (int)$news['id'] ?>)" class="btn btn-xs btn-outline-warning rounded-pill px-2.5 py-1 text-dark fw-bold" title="แก้ไขข่าวนี้">
                                        <i class="fa-solid fa-pen-to-square me-1"></i>แก้ไข
                                    </button>
                                    <?php endif; ?>
                                    <a href="<?= $detailUrl ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                        อ่านต่อ <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div id="noSearchNews" class="alert alert-light text-center border p-4 rounded-4 d-none">
                <i class="fa-solid fa-magnifying-glass text-muted fs-3 d-block mb-2"></i>
                <span class="text-muted">ไม่พบข่าวสารที่ตรงกับคำค้นหา</span>
            </div>
        <?php else: ?>
            <!-- Initial Empty State Guide for Officers & Citizens -->
            <div class="card border-0 rounded-4 p-5 text-center shadow-xs" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.04) 0%, rgba(3, 105, 161, 0.04) 100%); border: 2px dashed rgba(245, 158, 11, 0.4) !important;">
                <div class="mx-auto rounded-circle p-3 d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: rgba(245, 158, 11, 0.15); color: #d97706;">
                    <i class="fa-solid fa-newspaper fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">ยังไม่มีรายการข่าวสารจิตอาสาพระราชทานในขณะนี้</h5>
                <p class="text-muted small mx-auto mb-3" style="max-width: 520px;">
                    ศูนย์อำนวยการจิตอาสาพระราชทานจังหวัดพัทลุง กำลังเตรียมข้อมูลข่าวสารและกิจกรรมจิตอาสาเพื่อเผยแพร่ให้พี่น้องประชาชนได้รับทราบ
                </p>
                <?php if ($isOfficer): ?>
                <div>
                    <button type="button" onclick="NewsStudio.open(null, 'จิตอาสาพระราชทาน')" class="btn volunteer-btn-add rounded-pill px-4 py-2.5 d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-plus"></i>
                        <span>ลงข่าวจิตอาสาพระราชทานเรื่องแรก</span>
                    </button>
                </div>
                <?php else: ?>
                <div>
                    <a href="<?= base_url('news') ?>" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> ไปยังศูนย์รวมข่าวประชาสัมพันธ์จังหวัด
                    </a>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 5. PHOTO GALLERY PREVIEW (อัลบั้มภาพกิจกรรมจิตอาสา) -->
    <?php if (!empty($galleryAlbums)): ?>
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
                <h4 class="fw-bold m-0 text-dark">
                    <i class="fa-solid fa-images text-info me-2"></i>ภาพกิจกรรมจิตอาสาพระราชทาน
                </h4>
                <small class="text-muted">ประมวลภาพภารกิจและกิจกรรมจิตอาสาในพื้นที่จังหวัดพัทลุง</small>
            </div>
            <a href="<?= base_url('gallery?category=' . urlencode('จิตอาสาพระราชทาน')) ?>" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold">
                ดูคลังภาพทั้งหมด <i class="fa-solid fa-chevron-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            <?php foreach ($galleryAlbums as $gal): 
                $gCover = !empty($gal['cover_image']) ? (str_starts_with($gal['cover_image'], 'http') ? $gal['cover_image'] : base_url($gal['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png');
                $photoCount = count($gal['photos'] ?? []);
            ?>
            <div class="col-6 col-md-3">
                <a href="<?= base_url('gallery/album/' . ($gal['id'] ?? '')) ?>" class="text-decoration-none text-dark d-block">
                    <div class="card border-0 rounded-4 overflow-hidden shadow-xs hover-scale h-100">
                        <div class="position-relative" style="height: 150px;">
                            <img src="<?= esc($gCover) ?>" alt="<?= esc($gal['title']) ?>" class="w-100 h-100" style="object-fit: cover;">
                            <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 rounded-pill px-2 py-1 text-white small">
                                <i class="fa-solid fa-camera me-1"></i><?= $photoCount ?> ภาพ
                            </span>
                        </div>
                        <div class="p-3">
                            <h6 class="fw-bold mb-1 text-truncate" title="<?= esc($gal['title']) ?>"><?= esc($gal['title']) ?></h6>
                            <small class="text-muted"><?= esc($gal['date'] ?? '') ?></small>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- 6. VOLUNTEER GUIDELINES & CONTACT INFORMATION BOX -->
    <div class="card rounded-4 border-0 p-4 p-lg-5 shadow-xs" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); border-left: 5px solid #0284c7 !important;">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary text-white rounded-pill px-3 py-1 fw-bold">แนวทางปฏิบัติ</span>
                    <h5 class="fw-bold m-0 text-dark">การแต่งกายและข้อปฏิบัติของจิตอาสาพระราชทาน 904</h5>
                </div>
                <p class="text-muted small mb-3" style="line-height: 1.6;">
                    การแต่งกายในกิจกรรมจิตอาสาพระราชทานทั่วไป: สวมเสื้อสีตามที่กำหนด หรือเสื้อจิตอาสาพระราชทาน, สวมหมวกจิตอาสาพระราชทาน (สีฟ้า), ผูกผ้าพันคอพระราชทาน (สีเหลือง) พร้อมติดบัตรประจำตัวจิตอาสา และแต่งกายสุภาพรัดกุมพร้อมปฏิบัติภารกิจ
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                    <span><i class="fa-solid fa-building-flag text-primary me-1"></i>ศูนย์อำนวยการจิตอาสาพระราชทานจังหวัดพัทลุง</span>
                    <span>•</span>
                    <span><i class="fa-solid fa-location-dot text-danger me-1"></i>ศาลากลางจังหวัดพัทลุง ถ.ราเมศวร์ ต.คูหาสวรรค์ อ.เมืองพัทลุง</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="tel:074614130" class="btn btn-primary rounded-pill px-4 py-2.5 fw-bold shadow-xs d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-phone-volume"></i>
                    <span>โทร. 074-614130</span>
                </a>
            </div>
        </div>
    </div>

</div>

<script>
function filterVolunteerNews(keyword) {
    const q = (keyword || '').toLowerCase().trim();
    const items = document.querySelectorAll('.volunteer-news-item');
    const noResults = document.getElementById('noSearchNews');
    let visibleCount = 0;

    items.forEach(function(el) {
        const title = el.getAttribute('data-title') || '';
        const content = el.getAttribute('data-content') || '';
        if (!q || title.includes(q) || content.includes(q)) {
            el.classList.remove('d-none');
            visibleCount++;
        } else {
            el.classList.add('d-none');
        }
    });

    if (noResults) {
        if (visibleCount === 0 && items.length > 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    }
}
</script>

<?= $this->endSection() ?>

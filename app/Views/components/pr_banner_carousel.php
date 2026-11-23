<?php
// =========================================================================
// คอมโพเนนต์: แถบแบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์
// Horizontal 5-Banner Spotlight Showcase (จัดเรียงแนวนอน 5 การ์ดตามรูปสเก็ตช์)
// การ์ดตรงกลางใหญ่และเด่นที่สุด (Center Spotlight / Hatch Highlighted)
// ขนาบข้างซ้าย 2 การ์ด และขวา 2 การ์ด เรียงแถวแนวนอนราบเรียบ ไม่บิดเอียง 3D
// =========================================================================
$rawBanners = function_exists('get_service_banners') ? get_service_banners(true) : [];
$rawBanners = array_values($rawBanners);
$origBannerCount = count($rawBanners);

// แสดงเฉพาะข้อมูลแบนเนอร์จริงที่บันทึกไว้ในระบบเท่านั้น (ไม่มีการจำลอง Mockup หลอก)
$prBanners = [];
if ($origBannerCount > 0) {
    if ($origBannerCount < 5) {
        $repeatTimes = (int)ceil(5 / $origBannerCount);
        if ($repeatTimes < 2) $repeatTimes = 2;
        for ($r = 0; $r < $repeatTimes; $r++) {
            foreach ($rawBanners as $k => $b) {
                $b['__orig_index'] = $k;
                $prBanners[] = $b;
            }
        }
    } else {
        foreach ($rawBanners as $k => $b) {
            $b['__orig_index'] = $k;
            $prBanners[] = $b;
        }
    }
}
?>

<style>
/* =========================================================================
   HORIZONTAL 5-BANNER SPOTLIGHT SHOWCASE STYLING (จัดเรียงแนวนอน 5 การ์ด)
   - ตรงกลางใหญ่ที่สุดและเด่นที่สุด (Center Spotlight Highlight)
   - ด้านซ้าย 2 การ์ด และด้านขวา 2 การ์ด เรียงแนวระนาบแนวนอน ไม่บิดเอียง
   ========================================================================= */
.pr-banners-section {
    position: relative;
    margin: 3.5rem 0 4rem 0;
}

/* Horizontal Viewport Stage */
.pr-3d-stage {
    position: relative;
    width: 100%;
    height: 310px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 15px 0;
    user-select: none;
    touch-action: pan-y;
    cursor: grab;
    background: linear-gradient(180deg, rgba(248, 250, 252, 0) 0%, rgba(241, 245, 249, 0.45) 50%, rgba(248, 250, 252, 0) 100%);
    border-radius: 20px;
}

.pr-3d-stage:active {
    cursor: grabbing;
}

/* Edge Soft Fades */
.pr-3d-stage::before, 
.pr-3d-stage::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 60px;
    z-index: 18;
    pointer-events: none;
    opacity: 0.85;
}

.pr-3d-stage::before {
    left: 0;
    background: linear-gradient(to right, rgba(248, 250, 252, 1) 0%, rgba(248, 250, 252, 0) 100%);
}

.pr-3d-stage::after {
    right: 0;
    background: linear-gradient(to left, rgba(248, 250, 252, 1) 0%, rgba(248, 250, 252, 0) 100%);
}

/* Horizontal Track */
.pr-3d-track {
    position: relative;
    width: 100%;
    height: 100%;
}

/* Horizontal Card Base Styling (Flat, Clean, 2D Symmetrical) */
.pr-3d-card {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 380px;
    height: 220px;
    margin-top: -110px;
    margin-left: -190px;
    border-radius: 18px;
    background: #0f172a;
    border: 2px solid rgba(226, 232, 240, 0.8);
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.60s cubic-bezier(0.22, 1, 0.36, 1),
                opacity 0.45s ease,
                box-shadow 0.45s ease,
                border-color 0.45s ease,
                filter 0.45s ease;
    will-change: transform, opacity;
    box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
    text-decoration: none;
}

/* Active Center Card (Spotlight - เด่นสุด ขนาดใหญ่สุด และมีแสงเรือง) */
.pr-3d-card.is-active {
    border: 3.5px solid #0284c7 !important;
    box-shadow: 0 20px 45px -8px rgba(2, 132, 199, 0.50), 
                0 0 0 4px rgba(56, 189, 248, 0.35) !important;
    cursor: pointer;
}

.pr-3d-card.is-active:hover {
    box-shadow: 0 26px 55px -10px rgba(2, 132, 199, 0.65), 
                0 0 0 5px rgba(56, 189, 248, 0.50) !important;
    border-color: #38bdf8 !important;
}

/* Flanking Side Cards (Non-active) */
.pr-3d-card:not(.is-active) {
    box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.12);
}

.pr-3d-card:not(.is-active):hover {
    filter: brightness(1.02) !important;
    opacity: 0.98 !important;
    border-color: #cbd5e1;
}

/* Ambient blurred backdrop for contain mode */
.pr-3d-backdrop {
    position: absolute;
    inset: -15px;
    background-size: cover;
    background-position: center;
    filter: blur(18px) brightness(0.68) saturate(1.25);
    opacity: 0.92;
    transform: scale(1.15);
    pointer-events: none;
    z-index: 1;
}

/* Banner Image */
.pr-3d-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 16px;
    transition: transform 0.60s cubic-bezier(0.22, 1, 0.36, 1);
    position: relative;
    z-index: 2;
}

.pr-3d-img.fit-contain {
    object-fit: contain !important;
}

.pr-3d-img.fit-cover {
    object-fit: cover !important;
}

.pr-3d-img.fit-fill {
    object-fit: fill !important;
}

.pr-3d-card.is-active:hover .pr-3d-img {
    transform: scale(1.03);
}

/* Title & Badge Caption Overlay */
.pr-3d-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 6;
    padding: 2.2rem 1.15rem 0.9rem 1.15rem;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.72) 55%, rgba(15, 23, 42, 0) 100%);
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
    pointer-events: none;
    transition: all 0.35s ease;
}

.pr-3d-caption-inner {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.pr-3d-badge {
    align-self: flex-start;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    padding: 0.18rem 0.6rem;
    border-radius: 9999px;
    backdrop-filter: blur(8px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
}

.pr-3d-badge.badge-primary {
    background: rgba(2, 132, 199, 0.90);
    color: #ffffff;
    border: 1px solid rgba(56, 189, 248, 0.5);
}

.pr-3d-badge.badge-success {
    background: rgba(16, 185, 129, 0.90);
    color: #ffffff;
    border: 1px solid rgba(110, 231, 183, 0.5);
}

.pr-3d-badge.badge-danger {
    background: rgba(239, 68, 68, 0.90);
    color: #ffffff;
    border: 1px solid rgba(252, 165, 165, 0.5);
}

.pr-3d-badge.badge-warning {
    background: rgba(245, 158, 11, 0.90);
    color: #ffffff;
    border: 1px solid rgba(253, 230, 138, 0.5);
}

/* Spotlight Ribbon / Badge (ตรงกลางเด่นเป็นพิเศษ) */
.pr-spotlight-indicator {
    display: none;
    align-items: center;
    gap: 4px;
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 7;
    background: linear-gradient(135deg, #0284c7, #2563eb);
    color: #ffffff;
    font-size: 0.70rem;
    font-weight: 700;
    padding: 0.22rem 0.65rem;
    border-radius: 9999px;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.35);
}

.pr-3d-card.is-active .pr-spotlight-indicator {
    display: inline-flex;
}

.pr-3d-title {
    color: #ffffff;
    font-size: 0.90rem;
    font-weight: 700;
    line-height: 1.35;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.85);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: all 0.3s ease;
}

/* Active Center Card Title (ใหญ่และชัดเจน) */
.pr-3d-card.is-active .pr-3d-title {
    font-size: 1.12rem;
    color: #ffffff;
    text-shadow: 0 3px 12px rgba(0, 0, 0, 0.95);
}

.pr-3d-card.is-active .pr-3d-caption {
    padding: 2.6rem 1.30rem 1.05rem 1.30rem;
}

/* Side Non-Active Cards Title */
.pr-3d-card:not(.is-active) .pr-3d-title {
    font-size: 0.82rem;
    opacity: 0.90;
}

/* Floating External Link Icon */
.pr-3d-icon-link {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 7;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.82rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    transition: all 0.25s ease;
    opacity: 0.9;
}

.pr-3d-card.is-active:hover .pr-3d-icon-link {
    background: #0284c7;
    color: #ffffff;
    opacity: 1;
    transform: scale(1.15) rotate(12deg);
}

/* Navigation Buttons */
.pr-nav-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #1e293b;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.23, 1, 0.32, 1);
    font-size: 0.95rem;
}

.pr-nav-btn:hover {
    background: linear-gradient(135deg, #0284c7, #2563eb);
    color: #ffffff;
    border-color: transparent;
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 18px rgba(2, 132, 199, 0.35);
}

.pr-nav-btn.btn-auto-active {
    background: #ecfdf5;
    color: #059669;
    border-color: #a7f3d0;
}

.pr-nav-btn.btn-auto-active:hover {
    background: #059669;
    color: #ffffff;
}

/* Auto-Slide Progress Bar */
.pr-3d-progress-wrap {
    max-width: 480px;
    width: 100%;
    height: 4px;
    background: #e2e8f0;
    border-radius: 9999px;
    margin: 16px auto 0 auto;
    overflow: hidden;
    position: relative;
}

.pr-3d-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #0284c7, #2563eb, #38bdf8);
    border-radius: 9999px;
    transition: width 0.05s linear;
}

/* Indicator Dots */
.pr-3d-dots {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 12px;
}

.pr-3d-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #cbd5e1;
    border: none;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.23, 1, 0.32, 1);
    padding: 0;
}

.pr-3d-dot:hover {
    background: #94a3b8;
}

.pr-3d-dot.active {
    width: 34px;
    border-radius: 9999px;
    background: linear-gradient(135deg, #0284c7, #2563eb);
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4);
}

/* Responsive Media Queries */
@media (max-width: 1200px) {
    .pr-3d-stage {
        height: 290px;
    }
    .pr-3d-card {
        width: 350px;
        height: 200px;
        margin-top: -100px;
        margin-left: -175px;
    }
}

@media (max-width: 992px) {
    .pr-3d-stage {
        height: 265px;
    }
    .pr-3d-card {
        width: 310px;
        height: 180px;
        margin-top: -90px;
        margin-left: -155px;
    }
    .pr-3d-caption {
        padding: 1.8rem 0.95rem 0.75rem 0.95rem;
    }
}

@media (max-width: 768px) {
    .pr-3d-stage {
        height: 240px;
    }
    .pr-3d-card {
        width: 270px;
        height: 160px;
        margin-top: -80px;
        margin-left: -135px;
    }
    .pr-3d-caption {
        padding: 1.5rem 0.85rem 0.65rem 0.85rem;
    }
    .pr-3d-card.is-active .pr-3d-title {
        font-size: 0.98rem;
    }
    .pr-3d-card:not(.is-active) .pr-3d-title {
        font-size: 0.76rem;
    }
    .pr-3d-stage::before, 
    .pr-3d-stage::after {
        width: 35px;
    }
}

@media (max-width: 480px) {
    .pr-3d-stage {
        height: 215px;
    }
    .pr-3d-card {
        width: 230px;
        height: 135px;
        margin-top: -67px;
        margin-left: -115px;
        border-radius: 14px;
    }
    .pr-3d-img {
        border-radius: 14px;
    }
    .pr-3d-caption {
        padding: 1.2rem 0.70rem 0.50rem 0.70rem;
    }
    .pr-3d-card.is-active .pr-3d-title {
        font-size: 0.88rem;
    }
    .pr-3d-card:not(.is-active) .pr-3d-title {
        display: none;
    }
    .pr-3d-badge {
        font-size: 0.62rem;
        padding: 0.1rem 0.45rem;
    }
}
</style>

<?php if ($origBannerCount > 0): ?>
<!-- SECTION: แบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์ (5 การ์ดแนวนอน - Center Spotlight) -->
<section class="pr-banners-section" id="prBanners3DSection">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-layer-group me-1 text-primary"></i> PARTNER & GOVERNMENT LINKS
                </span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-grip-horizontal me-1"></i>จัดเรียง 5 แบนเนอร์แนวนอน (Spotlight Center)
                </span>
            </div>
            <h4 class="fw-bold mb-0 d-flex align-items-center gap-2" style="color: #0f172a;">
                <i class="fa-solid fa-bullhorn text-primary"></i>
                <span>แบนเนอร์ประชาสัมพันธ์ & หน่วยงานสัมพันธ์</span>
            </h4>
        </div>

        <div class="d-flex align-items-center gap-2">
            <?php 
            $canEditBanner = false;
            try {
                $canEditBanner = (bool)session()->get('isLoggedIn');
            } catch (\Throwable $e) {
                $canEditBanner = false;
            }
            if ($canEditBanner): 
            ?>
                <a href="<?= base_url('admin/service-banners') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 me-1 fw-semibold d-inline-flex align-items-center gap-1 shadow-xs" title="ไปเพิ่ม/แก้ไขแบนเนอร์หลังบ้าน">
                    <i class="fa-solid fa-plus"></i> จัดการแบนเนอร์
                </a>
            <?php endif; ?>

            <!-- Auto-Play Toggle Button -->
            <button type="button" class="pr-nav-btn btn-auto-active" id="pr3dAutoToggle" title="หยุดชั่วคราว / เล่นอัตโนมัติ" aria-label="Auto Slide Toggle">
                <i class="fa-solid fa-pause"></i>
            </button>

            <!-- Navigation Controls -->
            <button type="button" class="pr-nav-btn" id="pr3dPrev" aria-label="Previous Banner" title="แบนเนอร์ก่อนหน้า">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="pr-nav-btn" id="pr3dNext" aria-label="Next Banner" title="แบนเนอร์ถัดไป">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Horizontal Carousel Stage - 5 Banners View (Center Prominent) -->
    <div class="pr-3d-stage" id="pr3dStage" title="สไลด์แบนเนอร์แนวนอน 5 ภาพ (คลิกที่การ์ดเพื่อเลื่อนมาตรงกลาง หรือคลิกการ์ดกลางเพื่อเปิดลิงก์)">
        <div class="pr-3d-track" id="pr3dTrack" data-orig-count="<?= $origBannerCount ?>">
            <?php foreach ($prBanners as $idx => $item): 
                $imgUrl = (!empty($item['image']) && (strpos($item['image'], 'http') === 0 || strpos($item['image'], 'data:') === 0 || strpos($item['image'], 'uploads/') === 0 || strpos($item['image'], 'assets/') === 0)) 
                          ? ((strpos($item['image'], 'http') === 0) ? $item['image'] : base_url($item['image'])) 
                          : 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?q=80&w=800&auto=format&fit=crop';
                $targetUrl = !empty($item['url']) ? $item['url'] : '#';
                $targetAttr = !empty($item['target']) ? $item['target'] : '_blank';
                $itemTitle = $item['title'] ?? 'แบนเนอร์ประชาสัมพันธ์';
                $itemBadge = $item['badge'] ?? '';
                $itemBadgeColor = $item['badge_color'] ?? 'primary';
                $itemCoverFit = !empty($item['cover_fit']) ? $item['cover_fit'] : 'contain';
                $origIdx = $item['__orig_index'] ?? $idx;
            ?>
                <div class="pr-3d-card" data-index="<?= $idx ?>" data-original-index="<?= $origIdx ?>" data-url="<?= htmlspecialchars($targetUrl) ?>" data-target="<?= htmlspecialchars($targetAttr) ?>" title="<?= htmlspecialchars($itemTitle) ?>">
                    <!-- Top Spotlight Tag (แสดงเฉพาะใบกลาง) -->
                    <div class="pr-spotlight-indicator">
                        <i class="fa-solid fa-star text-warning"></i>
                        <span>แนะนำ</span>
                    </div>

                    <?php if ($itemCoverFit === 'contain'): ?>
                        <div class="pr-3d-backdrop" style="background-image: url('<?= htmlspecialchars($imgUrl) ?>');"></div>
                    <?php endif; ?>

                    <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($itemTitle) ?>" class="pr-3d-img fit-<?= htmlspecialchars($itemCoverFit) ?>" loading="lazy" draggable="false" style="object-fit: <?= htmlspecialchars($itemCoverFit) ?>;">
                    
                    <!-- Banner Title & Badge Label Overlay -->
                    <div class="pr-3d-caption">
                        <div class="pr-3d-caption-inner">
                            <?php if (!empty($itemBadge)): ?>
                                <span class="pr-3d-badge badge-<?= htmlspecialchars($itemBadgeColor) ?>">
                                    <i class="fa-solid fa-link me-1"></i><?= htmlspecialchars($itemBadge) ?>
                                </span>
                            <?php endif; ?>
                            <div class="pr-3d-title" title="<?= htmlspecialchars($itemTitle) ?>">
                                <?= htmlspecialchars($itemTitle) ?>
                            </div>
                        </div>
                    </div>

                    <div class="pr-3d-icon-link" title="เปิดเว็บไซต์: <?= htmlspecialchars($itemTitle) ?>">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Auto-Slide Progress Bar Indicator -->
    <div class="pr-3d-progress-wrap" title="เวลาเลื่อนอัตโนมัติ">
        <div class="pr-3d-progress-bar" id="pr3dProgressBar"></div>
    </div>

    <!-- Indicator Dots -->
    <div class="pr-3d-dots" id="pr3dDots"></div>
</section>

<script>
/**
 * Horizontal 5-Banner Spotlight Carousel Engine
 * Displays 5 cards symmetrically in a clean horizontal line:
 * [Far Left (diff -2)] [Near Left (diff -1)] [Center Spotlight (diff 0)] [Near Right (diff 1)] [Far Right (diff 2)]
 * Matches the user's hand-drawn sketch:
 * - Center card is noticeably larger, highlighted, and spotlighted.
 * - Symmetrically flanked by 2 cards on left and 2 cards on right.
 * - Flat 2D horizontal arrangement without 3D perspective distortion.
 */
(function() {
    function initHorizontalCarousel() {
        const stage = document.getElementById('pr3dStage');
        const track = document.getElementById('pr3dTrack');
        const prevBtn = document.getElementById('pr3dPrev');
        const nextBtn = document.getElementById('pr3dNext');
        const autoToggleBtn = document.getElementById('pr3dAutoToggle');
        const progressBar = document.getElementById('pr3dProgressBar');
        const dotsContainer = document.getElementById('pr3dDots');

        if (!stage || !track) return;

        const cards = Array.from(track.querySelectorAll('.pr-3d-card'));
        const totalCards = cards.length;
        if (totalCards === 0) return;

        const origCount = parseInt(track.getAttribute('data-orig-count') || totalCards, 10);

        let activeIndex = 0;
        let isAutoPlay = true;
        const autoPlayDuration = 3400; // 3.4 seconds per slide
        let progressInterval = null;
        let progressStartTime = Date.now();
        let isDragging = false;
        let startX = 0;
        let currentX = 0;
        const dragThreshold = 45;

        // 1. สร้างปุ่ม Dots ตามจำนวนแบนเนอร์ดั้งเดิม (Original Unique Count)
        if (dotsContainer) {
            dotsContainer.innerHTML = '';
            for (let idx = 0; idx < origCount; idx++) {
                const sampleCard = cards.find(c => parseInt(c.getAttribute('data-original-index') || 0, 10) === idx) || cards[idx];
                const dotTitle = sampleCard ? sampleCard.getAttribute('title') : ('แบนเนอร์ที่ ' + (idx + 1));
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'pr-3d-dot' + (idx === 0 ? ' active' : '');
                dot.setAttribute('aria-label', dotTitle || ('แบนเนอร์ที่ ' + (idx + 1)));
                dot.setAttribute('title', dotTitle || ('แบนเนอร์ที่ ' + (idx + 1)));
                dot.addEventListener('click', () => {
                    let targetSlide = idx;
                    if (totalCards > origCount) {
                        let bestDiff = 9999;
                        cards.forEach((c, cIdx) => {
                            const cOrig = parseInt(c.getAttribute('data-original-index') || cIdx, 10);
                            if (cOrig === idx) {
                                let d = Math.abs(cIdx - activeIndex);
                                if (d < bestDiff) {
                                    bestDiff = d;
                                    targetSlide = cIdx;
                                }
                            }
                        });
                    }
                    goToSlide(targetSlide);
                    resetProgressBar();
                });
                dotsContainer.appendChild(dot);
            }
        }

        // คำนวณค่าพารามิเตอร์การจัดเรียง 5 การ์ดแนวนอนตามความกว้างหน้าจอ (Responsive 5-Banner Parameters)
        function getHorizontalParams() {
            const w = stage.clientWidth || window.innerWidth;
            if (w < 576) {
                // Mobile
                return {
                    scaleCenter: 0.90,
                    scaleNear: 0.62,
                    scaleFar: 0.44,
                    step1: Math.min(w * 0.68, 240),
                    step2: Math.min(w * 1.15, 410),
                    hideFar: true
                };
            } else if (w < 768) {
                // Large Phone / Small Tablet
                return {
                    scaleCenter: 0.94,
                    scaleNear: 0.66,
                    scaleFar: 0.48,
                    step1: 260,
                    step2: 430,
                    hideFar: false
                };
            } else if (w < 992) {
                // Tablet
                return {
                    scaleCenter: 0.98,
                    scaleNear: 0.70,
                    scaleFar: 0.50,
                    step1: 280,
                    step2: 470,
                    hideFar: false
                };
            } else if (w < 1200) {
                // Small Desktop
                return {
                    scaleCenter: 1.02,
                    scaleNear: 0.73,
                    scaleFar: 0.54,
                    step1: 310,
                    step2: 530,
                    hideFar: false
                };
            } else {
                // Large Desktop (>= 1200px)
                return {
                    scaleCenter: 1.06,
                    scaleNear: 0.76,
                    scaleFar: 0.56,
                    step1: 340,
                    step2: 585,
                    hideFar: false
                };
            }
        }

        // 2. คำนวณตำแหน่งแนวนอน 5 การ์ด (Center ใหญ่สุด, ขนาบซ้าย 2 ใบ, ขวา 2 ใบ)
        function renderHorizontal() {
            const p = getHorizontalParams();

            cards.forEach((card, i) => {
                let diff = i - activeIndex;
                while (diff > totalCards / 2) diff -= totalCards;
                while (diff < -totalCards / 2) diff += totalCards;

                card.classList.remove('is-active');

                if (diff === 0) {
                    // 1. การ์ดตรงกลาง: ใหญ่ที่สุด เด่นที่สุด (Active Center Spotlight)
                    card.classList.add('is-active');
                    card.style.transform = `translate3d(0, 0, 0) scale(${p.scaleCenter})`;
                    card.style.zIndex = '25';
                    card.style.opacity = '1';
                    card.style.visibility = 'visible';
                    card.style.filter = 'brightness(1)';
                    card.style.pointerEvents = 'auto';
                } else if (diff === 1) {
                    // 2. การ์ดขวาใบที่ 1 (Near Right)
                    card.style.transform = `translate3d(${p.step1}px, 0, 0) scale(${p.scaleNear})`;
                    card.style.zIndex = '15';
                    card.style.opacity = '0.90';
                    card.style.visibility = 'visible';
                    card.style.filter = 'brightness(0.92)';
                    card.style.pointerEvents = 'auto';
                } else if (diff === -1) {
                    // 3. การ์ดซ้ายใบที่ 1 (Near Left)
                    card.style.transform = `translate3d(-${p.step1}px, 0, 0) scale(${p.scaleNear})`;
                    card.style.zIndex = '15';
                    card.style.opacity = '0.90';
                    card.style.visibility = 'visible';
                    card.style.filter = 'brightness(0.92)';
                    card.style.pointerEvents = 'auto';
                } else if (diff === 2) {
                    // 4. การ์ดขวาสุดใบที่ 2 (Far Right)
                    card.style.transform = `translate3d(${p.step2}px, 0, 0) scale(${p.scaleFar})`;
                    card.style.zIndex = '10';
                    card.style.opacity = p.hideFar ? '0' : '0.72';
                    card.style.visibility = p.hideFar ? 'hidden' : 'visible';
                    card.style.filter = 'brightness(0.80)';
                    card.style.pointerEvents = p.hideFar ? 'none' : 'auto';
                } else if (diff === -2) {
                    // 5. การ์ดซ้ายสุดใบที่ 2 (Far Left)
                    card.style.transform = `translate3d(-${p.step2}px, 0, 0) scale(${p.scaleFar})`;
                    card.style.zIndex = '10';
                    card.style.opacity = p.hideFar ? '0' : '0.72';
                    card.style.visibility = p.hideFar ? 'hidden' : 'visible';
                    card.style.filter = 'brightness(0.80)';
                    card.style.pointerEvents = p.hideFar ? 'none' : 'auto';
                } else {
                    // การ์ดอื่นๆ ทั้งหมด ซ่อนไว้นอกจออย่างนุ่มนวล
                    const side = diff > 0 ? 1 : -1;
                    card.style.transform = `translate3d(${side * (p.step2 + 250)}px, 0, 0) scale(0.35)`;
                    card.style.zIndex = '1';
                    card.style.opacity = '0';
                    card.style.visibility = 'hidden';
                    card.style.pointerEvents = 'none';
                }
            });

            // อัปเดตสถานะของจุด Indicator Dots
            if (dotsContainer) {
                const dots = dotsContainer.querySelectorAll('.pr-3d-dot');
                if (origCount > 0) {
                    const activeOriginalIdx = activeIndex % origCount;
                    dots.forEach((dot, idx) => {
                        dot.classList.toggle('active', idx === activeOriginalIdx);
                    });
                }
            }
        }

        function goToSlide(index) {
            activeIndex = (index + totalCards) % totalCards;
            renderHorizontal();
        }

        function nextSlide() {
            goToSlide(activeIndex + 1);
        }

        function prevSlide() {
            goToSlide(activeIndex - 1);
        }

        // 3. คลิกที่การ์ด: คลิกการ์ดข้างๆ เลื่อนมาตรงกลาง, คลิกการ์ดกลางเปิดลิงก์
        cards.forEach((card, idx) => {
            card.addEventListener('click', function(e) {
                if (idx !== activeIndex) {
                    e.preventDefault();
                    goToSlide(idx);
                    resetProgressBar();
                } else {
                    const url = card.getAttribute('data-url');
                    const target = card.getAttribute('data-target') || '_blank';
                    if (url && url !== '#') {
                        window.open(url, target);
                    }
                }
            });
        });

        // 4. Navigation Controls
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                nextSlide();
                resetProgressBar();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                prevSlide();
                resetProgressBar();
            });
        }

        // 5. Auto-Play & Smooth Progress Bar
        function startAutoProgress() {
            stopAutoProgress();
            progressStartTime = Date.now();
            
            progressInterval = setInterval(() => {
                if (!isAutoPlay || isDragging) return;

                const elapsed = Date.now() - progressStartTime;
                const percent = Math.min(100, (elapsed / autoPlayDuration) * 100);

                if (progressBar) {
                    progressBar.style.width = percent + '%';
                }

                if (elapsed >= autoPlayDuration) {
                    nextSlide();
                    progressStartTime = Date.now();
                    if (progressBar) progressBar.style.width = '0%';
                }
            }, 30);
        }

        function stopAutoProgress() {
            if (progressInterval) {
                clearInterval(progressInterval);
                progressInterval = null;
            }
        }

        function resetProgressBar() {
            progressStartTime = Date.now();
            if (progressBar) progressBar.style.width = '0%';
        }

        // Toggle Auto-Play Button
        if (autoToggleBtn) {
            autoToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                isAutoPlay = !isAutoPlay;
                if (isAutoPlay) {
                    autoToggleBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                    autoToggleBtn.classList.add('btn-auto-active');
                    resetProgressBar();
                    startAutoProgress();
                } else {
                    autoToggleBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                    autoToggleBtn.classList.remove('btn-auto-active');
                    if (progressBar) progressBar.style.width = '0%';
                }
            });
        }

        // 6. Touch & Mouse Drag Gestures
        function onDragStart(e) {
            isDragging = true;
            startX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
            currentX = startX;
        }

        function onDragMove(e) {
            if (!isDragging) return;
            currentX = e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
        }

        function onDragEnd() {
            if (!isDragging) return;
            isDragging = false;
            const deltaX = currentX - startX;
            if (Math.abs(deltaX) > dragThreshold) {
                if (deltaX < 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
                resetProgressBar();
            }
        }

        stage.addEventListener('mousedown', onDragStart);
        window.addEventListener('mousemove', onDragMove);
        window.addEventListener('mouseup', onDragEnd);

        stage.addEventListener('touchstart', onDragStart, { passive: true });
        window.addEventListener('touchmove', onDragMove, { passive: true });
        window.addEventListener('touchend', onDragEnd);

        window.addEventListener('resize', () => {
            renderHorizontal();
        });

        // Initialize First View and Start Continuous Auto-Slide
        renderHorizontal();
        startAutoProgress();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHorizontalCarousel);
    } else {
        initHorizontalCarousel();
    }
})();
</script>
<?php endif; ?>

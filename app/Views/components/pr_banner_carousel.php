<?php
// =========================================================================
// คอมโพเนนต์: แถบแบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์ (3D Carousel Slide)
// Phatthalung 3D Perspective Coverflow Banner Showcase (3-Banner Focus Showcase)
// แสดงผล 3 แบนเนอร์ (แบนเนอร์กลางใหญ่สุด พร้อมชื่อป้ายกำกับชัดเจน)
// =========================================================================
$prBanners = function_exists('get_service_banners') ? get_service_banners(true) : [];
if (empty($prBanners)) {
    // ถ้าไม่มีข้อมูล ให้ใช้ fallback ตัวอย่าง
    $prBanners = [
        [
            'id' => 'b1',
            'title' => 'ระบบชำระภาษีท้องถิ่นออนไลน์ (e-Tax Phatthalung)',
            'desc' => 'บริการชำระภาษีที่ดินและสิ่งปลูกสร้าง สะดวกรวดเร็ว',
            'badge' => 'บริการประชาชน 24 ชม.',
            'badge_color' => 'success',
            'url' => 'https://www.rd.go.th',
            'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?q=80&w=800&auto=format&fit=crop',
            'target' => '_blank'
        ],
        [
            'id' => 'b2',
            'title' => 'พอร์ตัลเชื่อมโยงบริการภาครัฐ Citizen e-Service',
            'desc' => 'ศูนย์กลางบริการภาครัฐแบบเบ็ดเสร็จสำหรับประชาชน',
            'badge' => 'Citizen Portal',
            'badge_color' => 'primary',
            'url' => 'https://www.dga.or.th',
            'image' => 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?q=80&w=800&auto=format&fit=crop',
            'target' => '_blank'
        ],
        [
            'id' => 'b3',
            'title' => 'ระบบแจ้งปัญหาและสายตรงผู้ว่าฯ (Traffy Fondue)',
            'desc' => 'แจ้งเรื่องร้องทุกข์ ติดตามผลการแก้ปัญหาได้ทันที',
            'badge' => 'สายตรงผู้ว่าฯ',
            'badge_color' => 'danger',
            'url' => 'https://www.traffy.in.th',
            'image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&w=800&auto=format&fit=crop',
            'target' => '_blank'
        ],
        [
            'id' => 'b4',
            'title' => 'ระบบติดตามโครงการและจัดซื้อจัดจ้างภาครัฐ (e-GP)',
            'desc' => 'ประกาศประกวดราคาและแผนการจัดซื้อจัดจ้างโปร่งใส',
            'badge' => 'จัดซื้อจัดจ้าง',
            'badge_color' => 'warning',
            'url' => 'http://www.gprocurement.go.th',
            'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?q=80&w=800&auto=format&fit=crop',
            'target' => '_blank'
        ]
    ];
}
?>

<style>
/* =========================================================================
   3D CAROUSEL SLIDER STYLING (3-Banner Showcase with Rich Title Overlays)
   ========================================================================= */
.pr-banners-section {
    position: relative;
    margin: 3.5rem 0 4rem 0;
}

/* 3D Viewport Stage - Extra Height for Large Cards */
.pr-3d-stage {
    perspective: 1400px;
    perspective-origin: 50% 50%;
    position: relative;
    width: 100%;
    height: 380px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 20px 0;
    user-select: none;
    touch-action: pan-y;
    cursor: grab;
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
    width: 70px;
    z-index: 25;
    pointer-events: none;
}

.pr-3d-stage::before {
    left: 0;
    background: linear-gradient(to right, rgba(248, 250, 252, 0.95) 0%, rgba(248, 250, 252, 0) 100%);
}

.pr-3d-stage::after {
    right: 0;
    background: linear-gradient(to left, rgba(248, 250, 252, 0.95) 0%, rgba(248, 250, 252, 0) 100%);
}

/* 3D Track */
.pr-3d-track {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
}

/* 3D Card - Large Premium Banner */
.pr-3d-card {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 540px;
    height: 270px;
    margin-top: -135px;
    margin-left: -270px;
    border-radius: 22px;
    background: #0f172a;
    border: 1.5px solid rgba(255, 255, 255, 0.15);
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.65s cubic-bezier(0.25, 1, 0.5, 1),
                opacity 0.5s ease,
                box-shadow 0.5s ease,
                filter 0.5s ease;
    will-change: transform, opacity;
    box-shadow: 0 15px 35px -8px rgba(0, 0, 0, 0.22);
    text-decoration: none;
}

/* Active Center Card: Prominent, Glowing, Largest */
.pr-3d-card.is-active {
    box-shadow: 0 25px 60px -10px rgba(2, 132, 199, 0.48), 0 0 0 3px #0284c7 !important;
    border-color: transparent !important;
}

.pr-3d-card.is-active:hover {
    transform: translateX(0px) translateZ(85px) rotateY(0deg) scale(1.18) !important;
    box-shadow: 0 35px 70px -12px rgba(2, 132, 199, 0.6), 0 0 0 3.5px #38bdf8 !important;
}

/* Banner Image - 100% full coverage */
.pr-3d-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 22px;
    transition: transform 0.65s cubic-bezier(0.25, 1, 0.5, 1);
}

.pr-3d-card.is-active:hover .pr-3d-img {
    transform: scale(1.05);
}

/* Title & Badge Caption Overlay */
.pr-3d-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 6;
    padding: 3rem 1.4rem 1.1rem 1.4rem;
    background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.72) 55%, rgba(15, 23, 42, 0) 100%);
    border-bottom-left-radius: 22px;
    border-bottom-right-radius: 22px;
    pointer-events: none;
    transition: all 0.4s ease;
}

.pr-3d-caption-inner {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.pr-3d-badge {
    align-self: flex-start;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    padding: 0.2rem 0.65rem;
    border-radius: 9999px;
    backdrop-filter: blur(8px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
}

.pr-3d-badge.badge-primary {
    background: rgba(2, 132, 199, 0.85);
    color: #ffffff;
    border: 1px solid rgba(56, 189, 248, 0.5);
}

.pr-3d-badge.badge-success {
    background: rgba(16, 185, 129, 0.85);
    color: #ffffff;
    border: 1px solid rgba(110, 231, 183, 0.5);
}

.pr-3d-badge.badge-danger {
    background: rgba(239, 68, 68, 0.85);
    color: #ffffff;
    border: 1px solid rgba(252, 165, 165, 0.5);
}

.pr-3d-badge.badge-warning {
    background: rgba(245, 158, 11, 0.85);
    color: #ffffff;
    border: 1px solid rgba(253, 230, 138, 0.5);
}

.pr-3d-title {
    color: #ffffff;
    font-size: 1.05rem;
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

/* Active Card Title Highlight */
.pr-3d-card.is-active .pr-3d-title {
    font-size: 1.25rem;
    color: #ffffff;
    text-shadow: 0 3px 12px rgba(0, 0, 0, 0.9);
}

.pr-3d-card.is-active .pr-3d-caption {
    padding: 3.5rem 1.6rem 1.25rem 1.6rem;
}

/* Flanking Non-Active Cards Title */
.pr-3d-card:not(.is-active) .pr-3d-title {
    font-size: 0.95rem;
    opacity: 0.85;
}

/* Floating External Link Icon */
.pr-3d-icon-link {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 7;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    transition: all 0.25s ease;
    opacity: 0.9;
}

.pr-3d-card.is-active:hover .pr-3d-icon-link {
    background: #0284c7;
    color: #ffffff;
    opacity: 1;
    transform: scale(1.15) rotate(15deg);
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

/* 3D Indicator Dots */
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

/* Responsive Breakpoints */
@media (max-width: 1200px) {
    .pr-3d-stage {
        height: 330px;
    }
    .pr-3d-card {
        width: 460px;
        height: 230px;
        margin-top: -115px;
        margin-left: -230px;
    }
}

@media (max-width: 768px) {
    .pr-3d-stage {
        height: 270px;
        perspective: 1000px;
    }
    .pr-3d-card {
        width: 350px;
        height: 175px;
        margin-top: -87px;
        margin-left: -175px;
        border-radius: 16px;
    }
    .pr-3d-img {
        border-radius: 16px;
    }
    .pr-3d-caption {
        padding: 2rem 1rem 0.8rem 1rem;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
    }
    .pr-3d-card.is-active .pr-3d-title {
        font-size: 1.05rem;
    }
    .pr-3d-stage::before, .pr-3d-stage::after {
        width: 35px;
    }
}

@media (max-width: 480px) {
    .pr-3d-stage {
        height: 220px;
        perspective: 800px;
    }
    .pr-3d-card {
        width: 280px;
        height: 145px;
        margin-top: -72px;
        margin-left: -140px;
        border-radius: 14px;
    }
    .pr-3d-img {
        border-radius: 14px;
    }
    .pr-3d-caption {
        padding: 1.5rem 0.75rem 0.6rem 0.75rem;
        border-bottom-left-radius: 14px;
        border-bottom-right-radius: 14px;
    }
    .pr-3d-card.is-active .pr-3d-title {
        font-size: 0.92rem;
    }
    .pr-3d-badge {
        font-size: 0.68rem;
        padding: 0.1rem 0.45rem;
    }
    .pr-3d-stage::before, .pr-3d-stage::after {
        display: none;
    }
}
</style>

<!-- SECTION: แบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์ (3D Carousel Slider - 3 Banners Focus) -->
<section class="pr-banners-section" id="prBanners3DSection">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-cube me-1 text-primary"></i> PARTNER & GOVERNMENT LINKS
                </span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-bolt-lightning me-1"></i>Auto 3D Slide
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

            <!-- 3D Navigation Controls -->
            <button type="button" class="pr-nav-btn" id="pr3dPrev" aria-label="Previous Banner">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="pr-nav-btn" id="pr3dNext" aria-label="Next Banner">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- 3D Carousel Stage - 3 Banners View (Center Prominent) -->
    <div class="pr-3d-stage" id="pr3dStage" title="สไลด์อัตโนมัติ (ลากซ้าย-ขวา หรือคลิกที่การ์ดเพื่อดูแบนเนอร์)">
        <div class="pr-3d-track" id="pr3dTrack">
            <?php foreach ($prBanners as $idx => $item): 
                $imgUrl = (!empty($item['image']) && (strpos($item['image'], 'http') === 0 || strpos($item['image'], 'data:') === 0 || strpos($item['image'], 'uploads/') === 0)) 
                          ? ((strpos($item['image'], 'http') === 0) ? $item['image'] : base_url($item['image'])) 
                          : 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?q=80&w=800&auto=format&fit=crop';
                $targetUrl = !empty($item['url']) ? $item['url'] : '#';
                $targetAttr = !empty($item['target']) ? $item['target'] : '_blank';
                $itemTitle = $item['title'] ?? 'แบนเนอร์ประชาสัมพันธ์';
                $itemBadge = $item['badge'] ?? '';
                $itemBadgeColor = $item['badge_color'] ?? 'primary';
            ?>
                <div class="pr-3d-card" data-index="<?= $idx ?>" data-url="<?= htmlspecialchars($targetUrl) ?>" data-target="<?= htmlspecialchars($targetAttr) ?>" title="<?= htmlspecialchars($itemTitle) ?>">
                    <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($itemTitle) ?>" class="pr-3d-img" loading="lazy" draggable="false">
                    
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

    <!-- 3D Carousel Indicator Dots -->
    <div class="pr-3d-dots" id="pr3dDots"></div>
</section>

<script>
/**
 * Phatthalung 3D Carousel Slider Engine (3-Banner Focus Showcase)
 * Displays strictly 3 banners at a time (Left, Active Center [Largest], Right).
 * Center card is most prominent with high-resolution scaling and title caption.
 */
(function() {
    function init3DCarousel() {
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

        let activeIndex = 0;
        let isAutoPlay = true;
        const autoPlayDuration = 3200; // 3.2 seconds per slide
        let progressInterval = null;
        let progressStartTime = Date.now();
        let isDragging = false;
        let startX = 0;
        let currentX = 0;
        const dragThreshold = 45;

        // 1. สร้างปุ่ม Dots ตามจำนวนแบนเนอร์
        if (dotsContainer) {
            dotsContainer.innerHTML = '';
            cards.forEach((card, idx) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'pr-3d-dot' + (idx === 0 ? ' active' : '');
                dot.setAttribute('aria-label', card.getAttribute('title') || ('แบนเนอร์ที่ ' + (idx + 1)));
                dot.setAttribute('title', card.getAttribute('title') || ('แบนเนอร์ที่ ' + (idx + 1)));
                dot.addEventListener('click', () => {
                    goToSlide(idx);
                    resetProgressBar();
                });
                dotsContainer.appendChild(dot);
            });
        }

        // คำนวณค่าพารามิเตอร์ 3 แบนเนอร์ตามความกว้างหน้าจอ
        function getResponsiveParams() {
            const w = window.innerWidth;
            if (w <= 480) {
                return { stepX: 135, rot: 22, centerScale: 1.08, flankScale: 0.72, zCenter: 40, zFlank: -40 };
            } else if (w <= 768) {
                return { stepX: 185, rot: 25, centerScale: 1.10, flankScale: 0.75, zCenter: 50, zFlank: -50 };
            } else if (w <= 1200) {
                return { stepX: 275, rot: 28, centerScale: 1.12, flankScale: 0.78, zCenter: 60, zFlank: -50 };
            } else {
                return { stepX: 375, rot: 30, centerScale: 1.16, flankScale: 0.80, zCenter: 75, zFlank: -50 };
            }
        }

        // 2. คำนวณตำแหน่ง 3D: แสดงผลเฉพาะ 3 แบนเนอร์ (กลางใหญ่สุด, ซ้าย, ขวา) การ์ดอื่นซ่อน 100%
        function render3D() {
            const p = getResponsiveParams();

            cards.forEach((card, i) => {
                let diff = i - activeIndex;
                if (diff > totalCards / 2) diff -= totalCards;
                if (diff < -totalCards / 2) diff += totalCards;

                card.classList.remove('is-active');

                if (diff === 0) {
                    // แบนเนอร์กลาง: เด่นและใหญ่ที่สุด
                    card.classList.add('is-active');
                    card.style.transform = `translateX(0px) translateZ(${p.zCenter}px) rotateY(0deg) scale(${p.centerScale})`;
                    card.style.zIndex = '30';
                    card.style.opacity = '1';
                    card.style.visibility = 'visible';
                    card.style.filter = 'brightness(1)';
                    card.style.pointerEvents = 'auto';
                } else if (diff === 1) {
                    // แบนเนอร์ขวา
                    card.style.transform = `translateX(${p.stepX}px) translateZ(${p.zFlank}px) rotateY(-${p.rot}deg) scale(${p.flankScale})`;
                    card.style.zIndex = '20';
                    card.style.opacity = '0.88';
                    card.style.visibility = 'visible';
                    card.style.filter = 'brightness(0.88)';
                    card.style.pointerEvents = 'auto';
                } else if (diff === -1) {
                    // แบนเนอร์ซ้าย
                    card.style.transform = `translateX(-${p.stepX}px) translateZ(${p.zFlank}px) rotateY(${p.rot}deg) scale(${p.flankScale})`;
                    card.style.zIndex = '20';
                    card.style.opacity = '0.88';
                    card.style.visibility = 'visible';
                    card.style.filter = 'brightness(0.88)';
                    card.style.pointerEvents = 'auto';
                } else {
                    // แบนเนอร์อื่นๆ ทั้งหมด ซ่อนสนิท (ไม่แสดง) เพื่อให้เห็นเฉพาะ 3 แบนเนอร์
                    const side = diff > 0 ? 1 : -1;
                    card.style.transform = `translateX(${side * p.stepX * 1.6}px) translateZ(-250px) scale(0.4)`;
                    card.style.zIndex = '0';
                    card.style.opacity = '0';
                    card.style.visibility = 'hidden';
                    card.style.pointerEvents = 'none';
                }
            });

            if (dotsContainer) {
                const dots = dotsContainer.querySelectorAll('.pr-3d-dot');
                dots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === activeIndex);
                });
            }
        }

        function goToSlide(index) {
            activeIndex = (index + totalCards) % totalCards;
            render3D();
        }

        function nextSlide() {
            goToSlide(activeIndex + 1);
        }

        function prevSlide() {
            goToSlide(activeIndex - 1);
        }

        // 3. คลิกที่การ์ด: คลิกการ์ดซ้ายหรือขวาจะเลื่อนมาตรงกลาง, คลิกการ์ดตรงกลางจะเปิดลิงก์
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

        // 5. Auto-Play & Smooth Progress Bar Animation
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
            render3D();
        });

        // Initialize First View and Start Continuous Auto-Slide
        render3D();
        startAutoProgress();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init3DCarousel);
    } else {
        init3DCarousel();
    }
})();
</script>

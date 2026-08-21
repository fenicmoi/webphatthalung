<?php
// =========================================================================
// คอมโพเนนต์: แถบแบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์ (PR & Partner Banners Slider)
// =========================================================================
$prBanners = function_exists('get_service_banners') ? get_service_banners(true) : [];
if (empty($prBanners)) {
    // ถ้าไม่มีข้อมูล ให้ใช้ fallback ตัวอย่าง
    $prBanners = [
        [
            'id' => 'b1',
            'title' => 'ศูนย์ดำรงธรรม 1567',
            'url' => 'https://damrongdhama.moi.go.th',
            'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?q=80&w=600&auto=format&fit=crop',
            'target' => '_blank'
        ],
        [
            'id' => 'b2',
            'title' => 'ระบบจัดซื้อจัดจ้างภาครัฐ e-GP',
            'url' => 'http://www.gprocurement.go.th',
            'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?q=80&w=600&auto=format&fit=crop',
            'target' => '_blank'
        ],
        [
            'id' => 'b3',
            'title' => 'สำนักงานขับเคลื่อนการปฏิรูปประเทศ DGA',
            'url' => 'https://www.dga.or.th',
            'image' => 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?q=80&w=600&auto=format&fit=crop',
            'target' => '_blank'
        ],
        [
            'id' => 'b4',
            'title' => 'Traffy Fondue แจ้งปัญหาเมือง',
            'url' => 'https://www.traffy.in.th',
            'image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?q=80&w=600&auto=format&fit=crop',
            'target' => '_blank'
        ]
    ];
}
?>

<style>
/* PR & Partner Banners Carousel Styling */
.pr-banners-section {
    position: relative;
    margin: 3.5rem 0;
}

.pr-banner-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.pr-banner-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.1);
    border-color: #93c5fd;
}

.pr-banner-img-wrap {
    height: 115px;
    width: 100%;
    overflow: hidden;
    position: relative;
    background: #f1f5f9;
}

.pr-banner-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.pr-banner-card:hover .pr-banner-img {
    transform: scale(1.06);
}

.pr-banner-title {
    font-size: 0.88rem;
    font-weight: 600;
    color: #1e293b;
    padding: 0.65rem 0.85rem;
    line-height: 1.35;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
}

.pr-slider-container {
    overflow: hidden;
    position: relative;
    padding: 6px 0;
}

.pr-slider-track {
    display: flex;
    gap: 1.25rem;
    transition: transform 0.5s ease-in-out;
}

.pr-slider-item {
    flex: 0 0 calc(25% - 1rem);
    max-width: calc(25% - 1rem);
}

@media (max-width: 992px) {
    .pr-slider-item {
        flex: 0 0 calc(33.333% - 0.85rem);
        max-width: calc(33.333% - 0.85rem);
    }
}

@media (max-width: 768px) {
    .pr-slider-item {
        flex: 0 0 calc(50% - 0.65rem);
        max-width: calc(50% - 0.65rem);
    }
}

@media (max-width: 480px) {
    .pr-slider-item {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

.pr-nav-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #1e293b;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.pr-nav-btn:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
</style>

<!-- SECTION: แบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์ (PR & Partner Banners Slider) -->
<section class="pr-banners-section">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.75rem; font-weight: 600;">
                    PARTNER & GOVERNMENT LINKS
                </span>
            </div>
            <h4 class="fw-bold mb-0" style="color: #0f172a;">
                <i class="fa-solid fa-bullhorn text-primary me-2"></i>แบนเนอร์ประชาสัมพันธ์ & หน่วยงานสัมพันธ์
            </h4>
        </div>

        <div class="d-flex align-items-center gap-2">
            <?php if (session()->get('isLoggedIn')): ?>
                <a href="<?= base_url('admin/service-banners') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 me-2" title="ไปเพิ่ม/แก้ไขแบนเนอร์หลังบ้าน">
                    <i class="fa-solid fa-plus me-1"></i> จัดการแบนเนอร์
                </a>
            <?php endif; ?>

            <!-- Navigation Controls -->
            <button type="button" class="pr-nav-btn" id="prSlidePrev" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="pr-nav-btn" id="prSlideNext" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- Carousel Container -->
    <div class="pr-slider-container" id="prSliderContainer">
        <div class="pr-slider-track" id="prSliderTrack">
            <?php foreach ($prBanners as $item): 
                $imgUrl = (!empty($item['image']) && (strpos($item['image'], 'http') === 0 || strpos($item['image'], 'data:') === 0 || strpos($item['image'], 'uploads/') === 0)) 
                          ? ((strpos($item['image'], 'http') === 0) ? $item['image'] : base_url($item['image'])) 
                          : 'https://images.unsplash.com/photo-1557200134-90327ee9fafa?q=80&w=600&auto=format&fit=crop';
                $targetUrl = !empty($item['url']) ? $item['url'] : '#';
                $targetAttr = !empty($item['target']) ? $item['target'] : '_blank';
            ?>
                <div class="pr-slider-item">
                    <a href="<?= htmlspecialchars($targetUrl) ?>" target="<?= htmlspecialchars($targetAttr) ?>" class="text-decoration-none d-block h-100" title="<?= htmlspecialchars($item['title'] ?? 'แบนเนอร์ประชาสัมพันธ์') ?>">
                        <div class="pr-banner-card">
                            <div class="pr-banner-img-wrap">
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($item['title'] ?? 'Banner') ?>" class="pr-banner-img" loading="lazy">
                            </div>
                            <div class="pr-banner-title">
                                <?= htmlspecialchars($item['title'] ?? 'หน่วยงานภาครัฐ') ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('prSliderTrack');
    const prevBtn = document.getElementById('prSlidePrev');
    const nextBtn = document.getElementById('prSlideNext');
    const container = document.getElementById('prSliderContainer');
    
    if (!track || !container) return;

    let currentIndex = 0;
    let autoSlideInterval = null;

    function getVisibleCount() {
        const width = window.innerWidth;
        if (width <= 480) return 1;
        if (width <= 768) return 2;
        if (width <= 992) return 3;
        return 4;
    }

    function updateSlider() {
        const totalItems = track.children.length;
        const visibleCount = getVisibleCount();
        const maxIndex = Math.max(0, totalItems - visibleCount);

        if (currentIndex > maxIndex) currentIndex = 0;
        if (currentIndex < 0) currentIndex = maxIndex;

        const itemWidth = track.children[0] ? track.children[0].offsetWidth : 0;
        const gap = 20; // 1.25rem
        const moveX = currentIndex * (itemWidth + gap);
        
        track.style.transform = `translateX(-${moveX}px)`;
    }

    function nextSlide() {
        const totalItems = track.children.length;
        const visibleCount = getVisibleCount();
        const maxIndex = Math.max(0, totalItems - visibleCount);
        
        if (currentIndex >= maxIndex) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }
        updateSlider();
    }

    function prevSlide() {
        const totalItems = track.children.length;
        const visibleCount = getVisibleCount();
        const maxIndex = Math.max(0, totalItems - visibleCount);
        
        if (currentIndex <= 0) {
            currentIndex = maxIndex;
        } else {
            currentIndex--;
        }
        updateSlider();
    }

    if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetAutoPlay(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetAutoPlay(); });

    function startAutoPlay() {
        if (!autoSlideInterval) {
            autoSlideInterval = setInterval(nextSlide, 4000);
        }
    }

    function stopAutoPlay() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
            autoSlideInterval = null;
        }
    }

    function resetAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }

    container.addEventListener('mouseenter', stopAutoPlay);
    container.addEventListener('mouseleave', startAutoPlay);
    window.addEventListener('resize', updateSlider);

    startAutoPlay();
});
</script>

<?php
$bannerCfg = function_exists('get_banner_settings') ? get_banner_settings() : ['show_banner' => '1', 'layout_mode' => 'hybrid_widescreen', 'banner_height' => '540', 'interval_ms' => '7500', 'show_weather' => '1', 'show_giahs' => '1'];
$banners = function_exists('get_site_banners') ? get_site_banners() : [];
$showBanner = (!isset($bannerCfg['show_banner']) || $bannerCfg['show_banner'] == '1');

if (!$showBanner || empty($banners)) {
    return; // Do not render if admin disabled banner or deleted all banners
}

$isWidescreen = (($bannerCfg['layout_mode'] ?? 'hybrid_widescreen') === 'hybrid_widescreen');
$bannerHeight = $bannerCfg['banner_height'] ?? '540';
$intervalMs = $bannerCfg['interval_ms'] ?? '7500';
$showWeather = (!isset($bannerCfg['show_weather']) || $bannerCfg['show_weather'] != '0');
$showGiahs = (!isset($bannerCfg['show_giahs']) || $bannerCfg['show_giahs'] != '0');
?>

<!-- WIDESCREEN HYBRID & KINETIC HERO BANNER COMPONENT -->
<div class="hero-banner-stage position-relative" style="z-index: 30; overflow: visible;">
<section id="smartCityCarousel" class="carousel slide smart-slider-box <?= $isWidescreen ? 'mode-widescreen' : 'mode-boxed' ?>" data-bs-ride="carousel" data-bs-interval="<?= $intervalMs ?>" 
         style="height: <?= $bannerHeight ?>px !important; <?= !$isWidescreen ? 'border-radius: var(--radius-lg) !important; width: 100% !important; border: 1px solid var(--glass-border) !important; margin-bottom: 2rem !important; box-shadow: 0 20px 45px rgba(0,0,0,0.25);' : 'width: 100% !important; border-radius: 0 !important; margin-bottom: 0 !important; border: none !important; border-bottom: 3px solid #6fd3c6 !important; box-shadow: 0 25px 55px rgba(0,0,0,0.35);' ?>">
    
    <!-- Carousel Indicators -->
    <div class="carousel-indicators" style="z-index: 20; margin-bottom: 1.2rem;">
        <?php foreach ($banners as $idx => $slide): ?>
            <button type="button" data-bs-target="#smartCityCarousel" data-bs-slide-to="<?= $idx ?>" class="<?= $idx === 0 ? 'active' : '' ?>" aria-current="<?= $idx === 0 ? 'true' : 'false' ?>" style="width: 38px; height: 6px; border-radius: 4px; background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.6);"></button>
        <?php endforeach; ?>
    </div>

    <!-- Carousel Inner -->
    <div class="carousel-inner h-100">
        <?php foreach ($banners as $idx => $slide): 
            $isActive = ($idx === 0) ? 'active' : '';
            $styleClass = $slide['style_class'] ?? 'slide-bg-sane-muanglung';
            $bgType = $slide['bg_type'] ?? 'image';
            $slideImgRaw = !empty($slide['image_path']) ? $slide['image_path'] : '';
            $bgSources = !empty($slideImgRaw) && function_exists('get_image_sources') ? get_image_sources($slideImgRaw) : null;
            $imgPath = $bgSources ? $bgSources['original_url'] : (!empty($slideImgRaw) ? base_url($slideImgRaw) : '');
            $badgeSubtitle = !empty($slide['subtitle']) ? $slide['subtitle'] : site_text('hero_badge_default', 'อัญมณีแห่งภาคใต้ • มรดกเกษตรโลก GIAHS', 'ข้อความ Badge แบนเนอร์');
        ?>
        <div class="carousel-item <?= $isActive ?> smart-slide-item <?= $styleClass ?> h-100" style="height: <?= $bannerHeight ?>px !important;">
            
            <!-- Background Image Layer (LCP Optimized) -->
            <?php if (!empty($imgPath)): ?>
                <div class="kenburns-bg">
                    <picture>
                        <?php if (!empty($bgSources['webp_url'])): ?>
                            <source srcset="<?= $bgSources['webp_url'] ?>" type="image/webp">
                        <?php endif; ?>
                        <img src="<?= $imgPath ?>" 
                             alt="<?= htmlspecialchars($slide['title'] ?? 'แบนเนอร์จังหวัดพัทลุง') ?>" 
                             class="kenburns-img"
                             <?= ($idx === 0) ? 'fetchpriority="high" loading="eager"' : 'loading="lazy"' ?>
                             decoding="async"
                             width="1920"
                             height="<?= $bannerHeight ?>">
                    </picture>
                </div>
                <div class="cinematic-vignette"></div>
            <?php else: ?>
                <div class="slide-geo-left"></div>
                <div class="slide-geo-right-orange" style="background: linear-gradient(135deg, <?= $idx === 2 ? '#00b09b, #96c93d' : ($idx === 3 ? '#3b82f6, #1d4ed8' : '#ff9600, #ff5e62') ?>);"></div>
            <?php endif; ?>

            <!-- Floating Custom Layer if uploaded by user -->
            <?php 
            $showFloating = (!isset($slide['show_floating']) || ($slide['show_floating'] !== false && $slide['show_floating'] !== '0' && $slide['show_floating'] !== 0));
            if ($showFloating && $bgType === 'custom_layer' && !empty($slide['floating_img_path'])): 
                $fPos = $slide['floating_pos'] ?? 'left_center';
                $posCss = "left: 8%; top: 50%; transform: translateY(-50%);";
                if ($fPos === 'right_center') $posCss = "right: 8%; top: 50%; transform: translateY(-50%);";
                elseif ($fPos === 'top_center') $posCss = "left: 50%; top: 18%; transform: translateX(-50%);";
                elseif ($fPos === 'bottom_left') $posCss = "left: 6%; bottom: 8%;";
                
                $floatSources = function_exists('get_image_sources') ? get_image_sources($slide['floating_img_path']) : null;
                $floatOrig = $floatSources ? $floatSources['original_url'] : base_url($slide['floating_img_path']);
            ?>
                <div class="position-absolute d-none d-md-block" style="<?= $posCss ?> z-index: 12; pointer-events: none; max-width: 420px;">
                    <picture>
                        <?php if (!empty($floatSources['webp_url'])): ?>
                            <source srcset="<?= $floatSources['webp_url'] ?>" type="image/webp">
                        <?php endif; ?>
                        <img src="<?= $floatOrig ?>" 
                             alt="Floating Graphic Layer" 
                             class="img-fluid" 
                             <?= ($idx === 0) ? 'fetchpriority="high" loading="eager"' : 'loading="lazy"' ?>
                             decoding="async"
                             style="max-height: 380px; filter: drop-shadow(0 20px 35px rgba(0, 0, 0, 0.45));">
                    </picture>
                </div>
            <?php endif; ?>

            <!-- Safe Content Container (Hybrid Widescreen & Boxed Safe Area) -->
            <div class="container h-100 position-relative py-4 py-lg-5" style="z-index: 15;">
                
                <!-- Floating Top Widgets -->
                <?php if ($showGiahs || $showWeather): ?>
                    <div class="d-flex justify-content-between align-items-start w-100 position-absolute top-0 start-0 pt-4 px-3 px-md-0" style="z-index: 30; pointer-events: none;">
                        <?php if ($showGiahs): ?>
                            <div class="anim-from-left delay-1" style="pointer-events: auto !important;">
                                <div class="badge-glass-glow shadow-sm" style="pointer-events: auto !important; cursor: pointer;">
                                    <i class="fa-solid fa-gem text-warning me-2 animate-pulse" style="font-size: 1.1rem;"></i>
                                    <span><?= site_text('hero_badge_default', !empty($slide['subtitle']) ? $slide['subtitle'] : 'อัญมณีแห่งภาคใต้ • มรดกเกษตรโลก GIAHS', 'ข้อความ Badge แบนเนอร์') ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>

                        <?php if ($showWeather): ?>
                            <div class="d-none d-md-flex anim-from-right delay-2 floating-node-2" style="pointer-events: auto !important;">
                                <div class="weather-glass-node shadow-sm" style="pointer-events: auto !important; cursor: pointer;">
                                    <i class="fa-solid fa-cloud-sun text-warning fs-1 me-3"></i>
                                    <div>
                                        <small class="text-info fw-bold d-block" style="font-size: 0.75rem;"><i class="fa-solid fa-location-dot me-1"></i><?= site_text('hero_weather_title', 'จุดชมวิวทะเลน้อย พัทลุง', 'จุดชมวิวสภาพอากาศ') ?></small>
                                        <h6 class="mb-0 fw-bold text-white mt-1" style="font-size: 0.95rem;"><?= site_text('hero_weather_desc', '28°C แสงสวย อากาศสดชื่น', 'ข้อความสภาพอากาศ') ?></h6>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php 
                $showCard   = (!isset($slide['show_card']) || ($slide['show_card'] !== false && $slide['show_card'] !== '0' && $slide['show_card'] !== 0));
                $showBadge  = (!isset($slide['show_badge']) || ($slide['show_badge'] !== false && $slide['show_badge'] !== '0' && $slide['show_badge'] !== 0));
                $showTitle  = (!isset($slide['show_title']) || ($slide['show_title'] !== false && $slide['show_title'] !== '0' && $slide['show_title'] !== 0));
                $showDesc   = (!isset($slide['show_desc']) || ($slide['show_desc'] !== false && $slide['show_desc'] !== '0' && $slide['show_desc'] !== 0));
                $showButton = (!isset($slide['show_button']) || ($slide['show_button'] !== false && $slide['show_button'] !== '0' && $slide['show_button'] !== 0));

                $hasVisibleCardContent = ($showCard && ($showBadge || $showTitle || $showDesc || $showButton));
                $placement = $slide['card_placement'] ?? (($bgType === 'image' || ($idx === 0 && empty($slide['card_placement']))) ? 'dock_bottom_right' : 'split_right');
                
                if ($hasVisibleCardContent):
                    if ($placement === 'dock_bottom_right'): 
                    ?>
                        <!-- Floating Interactive Gateway Dock (Bottom Right) -->
                        <div class="d-flex flex-column justify-content-end h-100 w-100 position-relative pb-2" style="z-index: 15; pointer-events: none;">
                            <div class="row w-100 m-0">
                                <div class="col-lg-6 offset-lg-6 p-0 ps-lg-4">
                                    <div class="slide-info-card anim-from-bottom delay-3 w-100 mb-0 pointer-events-auto" style="border-left: 6px solid #ffb703; background: rgba(11, 40, 52, 0.82) !important; color: #ffffff; box-shadow: 0 15px 40px rgba(0,0,0,0.5); backdrop-filter: blur(16px);">
                                        <?php if (($showBadge && !empty($slide['badge_title'])) || ($showTitle && !empty($slide['title']))): ?>
                                            <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                                                <?php if ($showBadge && !empty($slide['badge_title'])): ?>
                                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="border-radius: 6px;"><i class="<?= !empty($slide['badge_icon']) ? $slide['badge_icon'] : 'fa-solid fa-star' ?> me-1"></i> <?= $slide['badge_title'] ?></span>
                                                <?php endif; ?>
                                                <?php if ($showTitle && !empty($slide['title'])): ?>
                                                    <h6 class="fw-bold mb-0 text-white" style="font-size: 1.05rem;"><?= $slide['title'] ?></h6>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($showDesc && !empty($slide['desc'])): ?>
                                            <p class="mb-3 text-white-50" style="font-size: 0.92rem; line-height: 1.5;">
                                                <?= $slide['desc'] ?>
                                            </p>
                                        <?php endif; ?>

                                        <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid rgba(255,255,255,0.15);">
                                            <small class="text-warning fw-bold d-flex align-items-center"><i class="fa-solid fa-camera-retro me-1"></i> <?= site_text('hero_landmark_caption', 'จุดถ่ายภาพจุดชมวิว 360°', 'คำบรรยายจุดถ่ายภาพ') ?></small>
                                            <?php if ($showButton && (!empty($slide['button_text']) || !empty($slide['button_url']))): ?>
                                                <a href="<?= !empty($slide['button_url']) ? $slide['button_url'] : '#tourism' ?>" onclick="App.toast('เปิดระบบบริการอัจฉริยะ...', 'success')" class="btn btn-sm btn-warning fw-bold text-dark px-3 rounded-pill hover-lift">
                                                    <i class="<?= !empty($slide['button_icon']) ? $slide['button_icon'] : 'fa-solid fa-compass' ?> me-1"></i> <?= !empty($slide['button_text']) ? $slide['button_text'] : 'เข้าใช้งาน' ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: 
                        $colClass = ($placement === 'split_left') ? 'col-lg-7 text-center text-lg-start pe-lg-5' : 
                                   (($placement === 'center_overlay') ? 'col-lg-8 mx-auto text-center' : 'col-lg-7 offset-lg-5 text-center text-lg-start ps-lg-5');
                    ?>
                        <!-- Split-Screen or Centered Content -->
                        <div class="row h-100 align-items-center">
                            <div class="<?= $colClass ?>">
                                <?php if (($showBadge && !empty($slide['badge_title'])) || ($showTitle && !empty($slide['title']))): ?>
                                    <div class="anim-from-right delay-2">
                                        <?php if ($showBadge && !empty($slide['badge_title'])): ?>
                                            <span class="badge px-3 py-2 text-white fw-bold mb-3" style="background: rgba(255, 120, 0, 0.95); font-size: 0.95rem; border-radius: 30px; box-shadow: 0 5px 15px rgba(255, 120, 0, 0.4);">
                                                <i class="<?= !empty($slide['badge_icon']) ? $slide['badge_icon'] : 'fa-solid fa-globe' ?> me-1"></i> <?= $slide['badge_title'] ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($showTitle && !empty($slide['title'])): ?>
                                            <h2 class="slide-title-banner text-white mb-2">
                                                <?= $slide['title'] ?>
                                            </h2>
                                        <?php endif; ?>
                                        <?php if (!empty($slide['subtitle'])): ?>
                                            <h4 class="fw-bold text-white-50 mb-4"><?= $slide['subtitle'] ?></h4>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($showDesc || $showButton): ?>
                                    <div class="slide-info-card anim-from-bottom delay-3 <?= ($placement === 'center_overlay') ? 'mx-auto' : 'mx-auto mx-lg-0' ?>" style="border-left-color: <?= $idx === 2 ? '#10b981' : ($idx === 3 ? '#3b82f6' : '#ff9600') ?>;">
                                        <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-circle-check text-success me-2"></i>ระบบปฏิบัติการบริการดิจิทัลภาครัฐ</h6>
                                        <?php if ($showDesc && !empty($slide['desc'])): ?>
                                            <p class="mb-3 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                                                <?= $slide['desc'] ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($showButton): ?>
                                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                                <small class="text-muted"><i class="fa-solid fa-lock text-success me-1"></i> ปลอดภัย 24 ชั่วโมง</small>
                                                <a href="<?= !empty($slide['button_url']) ? $slide['button_url'] : '#services' ?>" class="btn btn-sm px-3 rounded-pill fw-bold text-white shadow-xs" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none;">
                                                    <?= !empty($slide['button_text']) ? $slide['button_text'] : 'เข้าใช้งาน' ?> <i class="fa-solid fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Navigation Arrows -->
    <button class="carousel-control-prev" type="button" data-bs-target="#smartCityCarousel" data-bs-slide="prev" style="width: 60px; z-index: 25;">
        <span class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0,0,0,0.5); border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); transition: transform 0.2s;">
            <i class="fa-solid fa-chevron-left text-white fs-5"></i>
        </span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#smartCityCarousel" data-bs-slide="next" style="width: 60px; z-index: 25;">
        <span class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0,0,0,0.5); border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); transition: transform 0.2s;">
            <i class="fa-solid fa-chevron-right text-white fs-5"></i>
        </span>
        <span class="visually-hidden">Next</span>
    </button>
</section>

<!-- FLOATING GLASS SMART SEARCH OVERLAY ON SLIDE (เหนือจุด Indicator 4 ขีด) -->
<div class="hero-search-overlay" style="position: absolute; left: 0; right: 0; bottom: 54px; z-index: 35; padding: 0 16px; pointer-events: none;">
    <div class="floating-search-shell position-relative" style="max-width: 780px; margin: 0 auto; pointer-events: auto;">
        <div class="floating-search-pill d-flex align-items-center gap-1.5" id="mainSearchWrapper"
             style="background: rgba(255, 255, 255, 0.93); backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px); border: 1.5px solid rgba(255, 255, 255, 0.85); border-radius: 999px; padding: 6px 7px 6px 9px; box-shadow: 0 18px 42px rgba(0, 0, 0, 0.32), 0 0 0 1px rgba(16, 185, 129, 0.25);">
            <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 40px; height: 40px; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); color: #059669;">
                <i class="fa-solid fa-magnifying-glass" style="font-size: 1rem;"></i>
            </div>

            <input type="text" id="globalSearchInput"
                   placeholder="<?= site_text('search_input_placeholder', 'ค้นหาข่าว, แผนพัฒนา, จัดซื้อจัดจ้าง, ทะเลน้อย, ผู้ว่า... หรือกดไมค์พูด', 'ข้อความกล่องค้นหา', true) ?>"
                   class="flex-grow-1 px-2.5"
                   style="border: none; background: transparent; color: #0f172a; outline: none; font-size: 1rem; font-weight: 500; min-width: 0;"
                   autocomplete="off"
                   onfocus="showTrendingPopover(true)"
                   oninput="if(this.value.trim().length > 0) showTrendingPopover(false);"
                   onkeydown="if(event.key === 'Enter') { showTrendingPopover(false); triggerSearchSubmit(); }">

            <div id="searchSpinner" class="spinner-border text-success spinner-border-sm mx-1 d-none" role="status" style="width: 1.15rem; height: 1.15rem;">
                <span class="visually-hidden">Loading...</span>
            </div>

            <button type="button" id="btnVoiceSearch"
                    class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 voice-search-btn"
                    title="ค้นหาด้วยเสียงพูด (Voice Search)"
                    onclick="toggleVoiceSearch()"
                    style="width: 38px; height: 38px; border: 1px solid rgba(16, 185, 129, 0.3); background: #ecfdf5; color: #059669; transition: all 0.25s ease;">
                <i class="fa-solid fa-microphone" id="voiceMicIcon" style="font-size: 0.95rem;"></i>
            </button>

            <button type="button"
                    class="btn rounded-pill px-4 py-2 fw-bold text-white shadow-sm flex-shrink-0"
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; white-space: nowrap; font-size: 0.94rem;"
                    onclick="triggerSearchSubmit()">
                <span>ค้นหา</span>
            </button>
        </div>

        <!-- Voice Listening Status Bar -->
        <div id="voiceListeningBar" class="d-none align-items-center justify-content-between px-3 py-2 mt-2 rounded-pill shadow-lg" style="background: rgba(6, 78, 59, 0.95); backdrop-filter: blur(10px); color: #ffffff; border: 1.5px solid #10b981; font-size: 0.86rem;">
            <div class="d-flex align-items-center gap-2">
                <span class="voice-wave-dot"></span>
                <span class="voice-wave-dot" style="animation-delay: 0.2s;"></span>
                <span class="voice-wave-dot" style="animation-delay: 0.4s;"></span>
                <span id="voiceListeningStatusText" class="ms-1 fw-semibold text-warning">กำลังฟังเสียงของคุณ (พูดคำที่ต้องการค้นหา)...</span>
            </div>
            <button type="button" class="btn btn-xs btn-outline-light rounded-pill px-2.5 py-0.5" style="font-size: 0.75rem;" onclick="stopVoiceSearch()">ยกเลิก</button>
        </div>

        <!-- Search Results Dropdown (เด้งลงมาพ้นขอบสไลด์ได้อย่างอิสระ) -->
        <div id="searchResultsDropdown" class="dropdown-menu w-100 mt-2 p-0 border-0" style="border-radius: 20px; max-height: 440px; overflow-y: auto; display: none; position: absolute; z-index: 1060; background: #ffffff; box-shadow: 0 24px 55px rgba(15, 23, 42, 0.28); border: 1.5px solid rgba(16, 185, 129, 0.35) !important;">
        </div>

        <!-- Trending Keywords Popover (แสดงเมื่อคลิกช่องค้นหา) -->
        <?php 
        $trendingKeywords = function_exists('get_trending_keywords') ? get_trending_keywords(6) : [];
        if (!empty($trendingKeywords)): 
        ?>
            <div id="trendingSearchPopover" class="floating-trending-popover">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-bold" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-fire text-warning me-1"></i> คำค้นหายอดนิยม
                    </span>
                    <button type="button" onclick="showTrendingPopover(false)" style="background:none;border:none;color:#94a3b8;font-size:0.9rem;cursor:pointer;line-height:1;">&times;</button>
                </div>
                <div class="d-flex flex-wrap gap-1.5">
                    <?php foreach ($trendingKeywords as $item): 
                        $kw = is_array($item) ? ($item['keyword'] ?? '') : $item;
                        $icon = is_array($item) ? ($item['icon'] ?? '🔥') : '🔥';
                        if (empty($kw)) continue;
                    ?>
                        <a href="javascript:void(0)" onclick="showTrendingPopover(false); quickSearchTag('<?= esc($kw) ?>')" class="badge rounded-pill px-2.5 py-1.5 text-decoration-none floating-trending-chip">
                            <span><?= $icon ?></span> <?= esc($kw) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>

<?php
$bannerCfg = function_exists('get_banner_settings') ? get_banner_settings() : ['layout_mode' => 'hybrid_widescreen', 'banner_height' => '540', 'interval_ms' => '7500', 'show_weather' => '1', 'show_giahs' => '1'];
$banners = function_exists('get_site_banners') ? get_site_banners() : [];
$isWidescreen = (($bannerCfg['layout_mode'] ?? 'hybrid_widescreen') === 'hybrid_widescreen');
$bannerHeight = $bannerCfg['banner_height'] ?? '540';
$intervalMs = $bannerCfg['interval_ms'] ?? '7500';
$showWeather = (!isset($bannerCfg['show_weather']) || $bannerCfg['show_weather'] != '0');
$showGiahs = (!isset($bannerCfg['show_giahs']) || $bannerCfg['show_giahs'] != '0');

if (empty($banners)) {
    return; // Do not render if admin deleted all banners
}
?>

<!-- WIDESCREEN HYBRID & KINETIC HERO BANNER COMPONENT -->
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
            $imgPath = !empty($slide['image_path']) ? base_url($slide['image_path']) : '';
        ?>
        <div class="carousel-item <?= $isActive ?> smart-slide-item <?= $styleClass ?> h-100" style="height: <?= $bannerHeight ?>px !important;">
            
            <!-- Background Image Layer -->
            <?php if (!empty($imgPath)): ?>
                <div class="kenburns-bg" style="background-image: url('<?= $imgPath ?>');"></div>
                <div class="cinematic-vignette"></div>
            <?php else: ?>
                <div class="slide-geo-left"></div>
                <div class="slide-geo-right-orange" style="background: linear-gradient(135deg, <?= $idx === 2 ? '#00b09b, #96c93d' : ($idx === 3 ? '#3b82f6, #1d4ed8' : '#ff9600, #ff5e62') ?>);"></div>
            <?php endif; ?>

            <!-- Floating Custom Layer if uploaded by user -->
            <?php if ($bgType === 'custom_layer' && !empty($slide['floating_img_path'])): 
                $fPos = $slide['floating_pos'] ?? 'left_center';
                $posCss = "left: 8%; top: 50%; transform: translateY(-50%);";
                if ($fPos === 'right_center') $posCss = "right: 8%; top: 50%; transform: translateY(-50%);";
                elseif ($fPos === 'top_center') $posCss = "left: 50%; top: 18%; transform: translateX(-50%);";
                elseif ($fPos === 'bottom_left') $posCss = "left: 6%; bottom: 8%;";
            ?>
                <div class="position-absolute d-none d-md-block" style="<?= $posCss ?> z-index: 12; pointer-events: none; max-width: 420px;">
                    <img src="<?= base_url($slide['floating_img_path']) ?>" alt="Floating Layer" class="img-fluid" style="max-height: 380px; filter: drop-shadow(0 20px 35px rgba(0, 0, 0, 0.45));">
                </div>
            <?php endif; ?>

            <!-- Safe Content Container (Hybrid Widescreen & Boxed Safe Area) -->
            <div class="container h-100 position-relative py-4 py-lg-5" style="z-index: 15;">
                
                <!-- Floating Top Widgets -->
                <?php if ($showGiahs || $showWeather): ?>
                    <div class="d-flex justify-content-between align-items-start w-100 position-absolute top-0 start-0 pt-4 px-3 px-md-0" style="z-index: 10; pointer-events: none;">
                        <?php if ($showGiahs): ?>
                            <div class="anim-from-left delay-1 pointer-events-auto">
                                <div class="badge-glass-glow">
                                    <i class="fa-solid fa-gem text-warning me-2 animate-pulse" style="font-size: 1.1rem;"></i>
                                    <span><?= !empty($slide['subtitle']) ? $slide['subtitle'] : 'อัญมณีแห่งภาคใต้ • มรดกเกษตรโลก GIAHS' ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div></div>
                        <?php endif; ?>

                        <?php if ($showWeather): ?>
                            <div class="d-none d-md-flex anim-from-right delay-2 floating-node-2 pointer-events-auto">
                                <div class="weather-glass-node">
                                    <i class="fa-solid fa-cloud-sun text-warning fs-1 me-3"></i>
                                    <div>
                                        <small class="text-info fw-bold" style="font-size: 0.75rem;"><i class="fa-solid fa-location-dot me-1"></i>จุดชมวิวทะเลน้อย พัทลุง</small>
                                        <h6 class="mb-0 fw-bold text-white mt-1" style="font-size: 0.95rem;">28°C แสงสวย อากาศสดชื่น</h6>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php 
                $placement = $slide['card_placement'] ?? (($bgType === 'image' || ($idx === 0 && empty($slide['card_placement']))) ? 'dock_bottom_right' : 'split_right');
                if ($placement === 'dock_bottom_right'): 
                ?>
                    <!-- Floating Interactive Gateway Dock (Bottom Right) -->
                    <div class="d-flex flex-column justify-content-end h-100 w-100 position-relative pb-2" style="z-index: 15; pointer-events: none;">
                        <div class="row w-100 m-0">
                            <div class="col-lg-6 offset-lg-6 p-0 ps-lg-4">
                                <div class="slide-info-card anim-from-bottom delay-3 w-100 mb-0 pointer-events-auto" style="border-left: 6px solid #ffb703; background: rgba(11, 40, 52, 0.82) !important; color: #ffffff; box-shadow: 0 15px 40px rgba(0,0,0,0.5); backdrop-filter: blur(16px);">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-warning text-dark fw-bold me-2 px-2 py-1" style="border-radius: 6px;"><i class="<?= !empty($slide['badge_icon']) ? $slide['badge_icon'] : 'fa-solid fa-star' ?> me-1"></i> <?= !empty($slide['badge_title']) ? $slide['badge_title'] : 'LANDMARK' ?></span>
                                        <h6 class="fw-bold mb-0 text-white" style="font-size: 1.05rem;"><?= $slide['title'] ?></h6>
                                    </div>
                                    <p class="mb-3 text-white-50" style="font-size: 0.92rem; line-height: 1.5;">
                                        <?= $slide['desc'] ?>
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid rgba(255,255,255,0.15);">
                                        <small class="text-warning fw-bold d-flex align-items-center"><i class="fa-solid fa-camera-retro me-1"></i> จุดถ่ายภาพจุดชมวิว 360°</small>
                                        <a href="<?= !empty($slide['button_url']) ? $slide['button_url'] : '#tourism' ?>" onclick="App.toast('เปิดระบบบริการอัจฉริยะ...', 'success')" class="btn btn-sm btn-warning fw-bold text-dark px-3 rounded-pill hover-lift">
                                            <i class="<?= !empty($slide['button_icon']) ? $slide['button_icon'] : 'fa-solid fa-compass' ?> me-1"></i> <?= !empty($slide['button_text']) ? $slide['button_text'] : 'เข้าใช้งาน' ?>
                                        </a>
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
                            <div class="anim-from-right delay-2">
                                <span class="badge px-3 py-2 text-white fw-bold mb-3" style="background: rgba(255, 120, 0, 0.95); font-size: 0.95rem; border-radius: 30px; box-shadow: 0 5px 15px rgba(255, 120, 0, 0.4);">
                                    <i class="<?= !empty($slide['badge_icon']) ? $slide['badge_icon'] : 'fa-solid fa-globe' ?> me-1"></i> <?= !empty($slide['badge_title']) ? $slide['badge_title'] : 'SMART CITY' ?>
                                </span>
                                <h2 class="slide-title-banner text-white mb-2">
                                    <?= $slide['title'] ?>
                                </h2>
                                <h4 class="fw-bold text-white-50 mb-4"><?= !empty($slide['subtitle']) ? $slide['subtitle'] : 'บริการประชาชนด้วยนวัตกรรมดิจิทัล' ?></h4>
                            </div>
                            
                            <div class="slide-info-card anim-from-bottom delay-3 <?= ($placement === 'center_overlay') ? 'mx-auto' : 'mx-auto mx-lg-0' ?>" style="border-left-color: <?= $idx === 2 ? '#10b981' : ($idx === 3 ? '#3b82f6' : '#ff9600') ?>;">
                                <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-circle-check text-success me-2"></i>ระบบปฏิบัติการบริการดิจิทัลภาครัฐ</h6>
                                <p class="mb-3 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                                    <?= $slide['desc'] ?>
                                </p>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <small class="text-muted"><i class="fa-solid fa-lock text-success me-1"></i> ปลอดภัย 24 ชั่วโมง</small>
                                    <a href="<?= !empty($slide['button_url']) ? $slide['button_url'] : '#services' ?>" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold">
                                        <?= !empty($slide['button_text']) ? $slide['button_text'] : 'เข้าใช้งาน' ?> <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
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

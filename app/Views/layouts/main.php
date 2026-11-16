<?php $siteConfig = function_exists('get_site_settings') ? get_site_settings() : []; ?>
<!DOCTYPE html>
<html lang="th" data-theme="<?= htmlspecialchars($siteConfig['default_theme'] ?? 'light') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteConfig['site_title_th'] ?? 'จังหวัดพัทลุง') ?> | <?= htmlspecialchars($siteConfig['site_title_en'] ?? 'Phatthalung Digital Portal') ?></title>
    <meta name="keywords" content="<?= htmlspecialchars($siteConfig['seo_keywords'] ?? '') ?>">
    <meta name="description" content="<?= htmlspecialchars($siteConfig['slogan'] ?? '') ?>">
    
    <!-- Base URL & CSRF Meta for Interactive Fetch API -->
    <meta name="base_url" content="<?= rtrim(base_url(), '/') ?>">
    <script>window.BASE_URL = "<?= rtrim(base_url(), '/') ?>";</script>
    <meta name="X-CSRF-HEADER" content="<?= csrf_header() ?>">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('uploads/logo/logo_1787048018.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/pwa/icon-192.png') ?>">
    
    <!-- PWA Web App Manifest & Mobile App Capabilities (Android / iOS) -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#047857">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="พัทลุงโฟน">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="พัทลุงโฟน">
    
    <!-- Google Fonts (Preconnect + Non-blocking with font-display: swap) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Prompt:wght@500;600;700&family=Sarabun:wght@400;500;600;700&subset=thai,latin&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Prompt:wght@500;600;700&family=Sarabun:wght@400;500;600;700&subset=thai,latin&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Prompt:wght@500;600;700&family=Sarabun:wght@400;500;600;700&subset=thai,latin&display=swap">
    </noscript>

    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 CDN (Non-blocking with Noscript Fallback) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </noscript>
    
    <!-- Custom Modern Stylesheet (Minified & Cache Busted) -->
    <link rel="stylesheet" href="<?= function_exists('asset_min_url') ? asset_min_url('assets/css/main.css') : base_url('assets/css/main.css?v=' . time()) ?>">
    
    <?php 
    ob_start();
    $this->renderSection('head_preload');
    $customPreload = ob_get_clean();

    if (!empty(trim((string)$customPreload))) {
        echo $customPreload . "\n";
    } elseif (trim((string)uri_string(), '/') === '') {
        // Only preload Hero slider on the homepage
        $firstHero = function_exists('get_first_hero_image') ? get_first_hero_image() : null;
        if ($firstHero) {
            $preloadUrl = !empty($firstHero['webp_url']) ? $firstHero['webp_url'] : $firstHero['original_url'];
            $preloadType = !empty($firstHero['webp_url']) ? 'image/webp' : 'image/jpeg';
            echo '    <!-- Preload Hero LCP Image -->' . "\n";
            echo '    <link rel="preload" as="image" href="' . esc($preloadUrl) . '" type="' . esc($preloadType) . '" fetchpriority="high">' . "\n";
        }
    }
    ?>
    
    <?php if (!empty($siteConfig['theme_accent'])): ?>
    <style>
        :root {
            --accent-primary: <?= htmlspecialchars($siteConfig['theme_accent']) ?> !important;
            --gradient-hero: linear-gradient(135deg, <?= htmlspecialchars($siteConfig['theme_accent']) ?> 0%, #1e3a8a 100%) !important;
        }
    </style>
    <?php endif; ?>
</head>
<body>
    <div class="content-wrapper">
        <!-- Emergency & Weather Top Alert Banner -->
        <?= $this->include('components/emergency_alert_banner') ?>

        <!-- 0. Official Provincial Top Utility Bar (Dignified & Accessible - Height ~32px) -->
        <div class="gov-top-utility-bar d-none d-lg-block" style="background: #003B30; color: #e2e8f0; font-size: 0.84rem; padding: 4px 0; min-height: 32px; border-bottom: 1px solid rgba(255,255,255,0.08); font-family: 'Prompt', sans-serif;">
            <div class="container d-flex justify-content-between align-items-center" style="min-height: 24px;">
                <div class="d-flex align-items-center gap-3">
                    <span><i class="fa-regular fa-calendar-check me-1.5 text-warning"></i><?= function_exists('thai_date') ? thai_date(date('Y-m-d'), 'day_full') : date('d/m/Y') ?></span>
                    <span class="opacity-40">•</span>
                    <span>
                        <a href="<?= base_url('damrongtham') ?>" class="text-white text-decoration-none hover-emerald d-inline-flex align-items-center" title="ศูนย์ดำรงธรรมจังหวัดพัทลุง">
                            <i class="fa-solid fa-phone me-1.5" style="color: #34d399 !important;"></i>สายด่วนศูนย์ดำรงธรรม 1567
                        </a>
                    </span>
                    <span class="opacity-40">•</span>
                    <span><i class="fa-solid fa-landmark me-1.5 text-warning"></i>ศาลากลางจังหวัดพัทลุง โทร. 074-614130</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Accessibility / Universal Access Button -->
                    <button type="button" class="btn btn-xs btn-outline-light rounded px-2 py-0 d-inline-flex align-items-center gap-1.5" onclick="if(window.W3CAccessibility) W3CAccessibility.openModal()" title="ช่วยเหลือการเข้าถึง (W3C WCAG AAA & เครื่องมือช่วยอ่านสำหรับประชาชนทุกคน)" style="font-size: 0.80rem; height: 22px; border-color: rgba(255,255,255,0.35); background: rgba(255,255,255,0.08);">
                        <i class="fa-solid fa-universal-access" style="color: #38bdf8;"></i>
                        <span>ช่วยเหลือการเข้าถึง</span>
                    </button>
                    <span class="opacity-40 mx-1">|</span>
                    <!-- Accessibility Font Size Adjuster -->
                    <span class="text-white-50 me-1" style="font-size: 0.82rem;">ขนาดตัวอักษร:</span>
                    <button type="button" class="btn btn-xs btn-outline-light rounded px-2 py-0 font-btn" onclick="adjustPortalFontSize(-1)" style="font-size: 0.80rem; height: 22px; min-width: 22px; display: inline-flex; align-items: center; justify-content: center;" title="ลดขนาดตัวอักษร">ก-</button>
                    <button type="button" class="btn btn-xs btn-outline-light rounded px-2 py-0 font-btn active-font" onclick="adjustPortalFontSize(0)" style="font-size: 0.82rem; height: 22px; min-width: 22px; display: inline-flex; align-items: center; justify-content: center;" title="ขนาดตัวอักษรปกติ">ก</button>
                    <button type="button" class="btn btn-xs btn-outline-light rounded px-2 py-0 font-btn" onclick="adjustPortalFontSize(1)" style="font-size: 0.84rem; height: 22px; min-width: 22px; display: inline-flex; align-items: center; justify-content: center;" title="เพิ่มขนาดตัวอักษร">ก+</button>
                    <span class="opacity-40 mx-1">|</span>
                    <!-- Theme Toggle -->
                    <button type="button" class="btn btn-xs btn-outline-light rounded px-2 py-0 d-inline-flex align-items-center justify-content-center" onclick="App.theme.toggle()" title="สลับโหมดมืด/สว่าง" style="font-size: 0.80rem; height: 22px; width: 26px;">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Municipal Government Header (~76px Height) -->
        <header class="gov-header-wrapper" style="min-height: 76px; display: flex; align-items: center;">
            <div class="gov-navbar" style="min-height: 76px; width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0 1rem; max-width: 1320px; margin: 0 auto;">
                <!-- 1. Left Brand Identity -->
                <div class="gov-brand-bar d-flex align-items-center justify-content-between">
                    <a href="<?= base_url() ?>" class="gov-brand-ribbon d-flex align-items-center gap-3">
                        <div class="gov-logo-circle" style="width: 50px; height: 50px; border-radius: 50%; padding: 2px; display: flex; align-items: center; justify-content: center; background: #ffffff; border: 2px solid #00A878; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                            <?php 
                            $siteLogo = function_exists('get_site_logo') ? get_site_logo() : ''; 
                            $rawLogoSetting = get_site_settings('site_logo');
                            $logoSources = !empty($rawLogoSetting) && function_exists('get_image_sources') ? get_image_sources($rawLogoSetting) : null;
                            ?>
                            <?php if (!empty($siteLogo)): ?>
                                <picture>
                                    <?php if (!empty($logoSources['webp_url'])): ?>
                                        <source srcset="<?= $logoSources['webp_url'] ?>" type="image/webp">
                                    <?php endif; ?>
                                    <img src="<?= htmlspecialchars($siteLogo) ?>" alt="ตราประจำจังหวัดพัทลุง" class="gov-logo-img" width="44" height="44" fetchpriority="high" decoding="async" style="object-fit: contain;">
                                </picture>
                            <?php else: ?>
                                <i class="fa-solid fa-building-columns text-success" style="font-size: 1.5rem;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="gov-title-stack d-flex flex-column justify-content-center">
                            <span class="gov-title-main" style="font-size: 1.28rem; font-weight: 700; color: #ffffff; line-height: 1.2;"><?= htmlspecialchars($siteConfig['site_title_th'] ?? 'จังหวัดพัทลุง') ?></span>
                            <span class="gov-title-sub" style="font-size: 0.78rem; font-weight: 500; color: rgba(255, 255, 255, 0.75); letter-spacing: 0.5px;">Phatthalung Province</span>
                        </div>
                    </a>

                    <button class="navbar-toggler d-xl-none border-0 p-2 me-2 text-white" type="button" onclick="toggleGovMobileNav()" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars-staggered fa-lg" style="color: #34d399;"></i>
                    </button>
                </div>

                <!-- 2. Center Navigation List -->
                <ul class="gov-nav-list" id="govNavList">
                    <?php 
                    $navMenus = function_exists('get_site_menus') ? get_site_menus() : [];
                    foreach ($navMenus as $nav): 
                        $hasSub = !empty($nav['children']) && is_array($nav['children']);
                    ?>
                        <?php if ($hasSub): ?>
                            <li class="gov-nav-item dropdown">
                                <a class="gov-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span><?= htmlspecialchars($nav['title'] ?? '') ?></span>
                                </a>
                                <ul class="dropdown-menu gov-dropdown-menu shadow border-0">
                                    <?php foreach($nav['children'] as $sub): 
                                        $subUrl = function_exists('format_menu_url') ? format_menu_url($sub['url'] ?? '#') : ($sub['url'] ?? '#');
                                    ?>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-decoration-none" href="<?= htmlspecialchars($subUrl) ?>" target="<?= htmlspecialchars($sub['target'] ?? '_self') ?>">
                                            <i class="fa-solid fa-chevron-right text-primary" style="font-size: 0.7rem;"></i>
                                            <span><?= htmlspecialchars($sub['title'] ?? '') ?></span>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: 
                            $mainUrl = function_exists('format_menu_url') ? format_menu_url($nav['url'] ?? '#') : ($nav['url'] ?? '#');
                        ?>
                            <li class="gov-nav-item">
                                <a class="gov-nav-link" href="<?= htmlspecialchars($mainUrl) ?>" target="<?= htmlspecialchars($nav['target'] ?? '_self') ?>">
                                    <span><?= htmlspecialchars($nav['title'] ?? '') ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php 
                    $isUserLoggedIn = false;
                    $userName = 'เจ้าหน้าที่';
                    try {
                        $isUserLoggedIn = (bool)session()->get('isLoggedIn');
                        if ($isUserLoggedIn) {
                            $userName = session()->get('full_name') ?: 'เจ้าหน้าที่';
                        }
                    } catch (\Throwable $e) {
                        $isUserLoggedIn = false;
                    }
                    ?>
                    <?php if ($isUserLoggedIn): ?>
                        <li class="gov-nav-item dropdown">
                            <a class="gov-nav-link dropdown-toggle text-warning" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-user me-1"></i> <?= mb_substr($userName, 0, 15) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end gov-dropdown-menu shadow border-0">
                                <li class="px-3 py-2 border-bottom"><small class="text-muted">สถานะ: เจ้าหน้าที่ระบบ</small></li>
                                <li><a class="dropdown-item py-2 text-primary fw-bold" href="<?= base_url('admin/dashboard') ?>"><i class="fa-solid fa-gauge me-2"></i>หลังบ้าน Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-bold" href="<?= base_url('logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i>ออกจากระบบ</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="gov-nav-item">
                            <a class="gov-nav-link text-white-50" href="<?= base_url('login') ?>" title="สำหรับเจ้าหน้าที่">
                                <i class="fa-solid fa-lock"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="gov-nav-item d-xl-none border-top mt-2 pt-2">
                        <a class="gov-nav-link text-info d-flex align-items-center gap-2" href="javascript:void(0);" onclick="if(window.W3CAccessibility) W3CAccessibility.openModal(); toggleGovMobileNav();">
                            <i class="fa-solid fa-universal-access"></i>
                            <span>ช่วยเหลือการเข้าถึง (Accessibility)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Widescreen Hero Banner Section -->
        <?= $this->renderSection('hero_banner') ?>

        <!-- Main Page Content Section -->
        <main class="container py-4 py-lg-5">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Official Elegant Provincial Footer with Guarantee Badges & Live Visitor Stats -->
        <?php 
        $visitorStats = function_exists('get_site_visitor_stats') ? get_site_visitor_stats() : [
            'start_date'   => '1 ตุลาคม 2566',
            'online_users' => 18,
            'today'        => '428',
            'month'        => '19,450',
            'total'        => '584,290'
        ];
        ?>
        <footer class="mt-5" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 60%, #047857 100%); color: #cbd5e1; border-top: 3px solid #10b981; font-family: 'Prompt', sans-serif;">
            <style>
            /* W3C HTML5 & AAA Official Guarantee Badges */
            .w3c-badge-pill {
                display: inline-flex;
                align-items: center;
                border-radius: 4px;
                overflow: hidden;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                font-weight: 700;
                line-height: 1;
                border: 1px solid rgba(255, 255, 255, 0.25);
                text-decoration: none !important;
                box-shadow: 0 2px 6px rgba(0,0,0,0.25);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .w3c-badge-pill:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 12px rgba(0,0,0,0.35);
            }
            .w3c-badge-pill .w3c-part {
                background: #ffffff;
                color: #005a9c;
                padding: 6px 7px;
                font-weight: 900;
                letter-spacing: -0.5px;
            }
            .w3c-badge-pill .html5-part {
                background: #e34f26;
                color: #ffffff;
                padding: 6px 8px;
                display: flex;
                align-items: center;
                gap: 4px;
            }
            .w3c-aaa-pill {
                display: inline-flex;
                align-items: center;
                border-radius: 4px;
                overflow: hidden;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                font-weight: 700;
                line-height: 1;
                border: 1px solid rgba(255, 255, 255, 0.25);
                text-decoration: none !important;
                box-shadow: 0 2px 6px rgba(0,0,0,0.25);
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .w3c-aaa-pill:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 12px rgba(0,0,0,0.35);
            }
            .w3c-aaa-pill .wai-part {
                background: #ffffff;
                color: #005a9c;
                padding: 6px 7px;
                font-weight: 900;
            }
            .w3c-aaa-pill .aaa-part {
                background: #005a9c;
                color: #ffffff;
                padding: 6px 8px;
            }
            .cert-badge-custom {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.22);
                border-radius: 6px;
                padding: 4px 9px;
                color: #ffffff;
                font-size: 0.76rem;
                font-weight: 600;
                text-decoration: none !important;
                transition: all 0.2s ease;
            }
            .cert-badge-custom:hover {
                background: rgba(255, 255, 255, 0.18);
                color: #ffffff;
                transform: translateY(-2px);
            }
            /* Social Icon Buttons */
            .footer-social-btn {
                width: 38px;
                height: 38px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #ffffff !important;
                text-decoration: none !important;
                font-size: 1.05rem;
                transition: all 0.22s ease;
                box-shadow: 0 3px 8px rgba(0,0,0,0.25);
            }
            .footer-social-btn:hover {
                transform: translateY(-3px) scale(1.08);
                box-shadow: 0 6px 16px rgba(0,0,0,0.4);
            }
            .social-fb { background: #1877f2; }
            .social-line { background: #06c755; }
            .social-yt { background: #ff0000; }
            /* Footer Policy Navigation Links */
            .footer-policy-link {
                color: rgba(255, 255, 255, 0.75);
                text-decoration: none;
                font-size: 0.84rem;
                transition: color 0.2s ease;
            }
            .footer-policy-link:hover {
                color: #34d399;
                text-decoration: underline;
            }
            /* Visitor Stat Counter Badges (Trang Style - Modernized) */
            .stat-pill-badge {
                background: rgba(2, 44, 34, 0.75);
                border: 1px solid rgba(16, 185, 129, 0.35);
                border-radius: 8px;
                padding: 4px 10px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 0.82rem;
                color: #e2e8f0;
            }
            .stat-pill-badge .stat-num {
                background: #0f172a;
                color: #34d399;
                font-weight: 700;
                padding: 2px 8px;
                border-radius: 5px;
                border: 1px solid rgba(16, 185, 129, 0.4);
                font-family: 'Courier New', Courier, monospace;
                letter-spacing: 0.5px;
            }
            .stat-pulse-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #10b981;
                display: inline-block;
                animation: statPulse 1.8s infinite;
            }
            @keyframes statPulse {
                0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.8); }
                70% { transform: scale(1.1); box-shadow: 0 0 0 7px rgba(16, 185, 129, 0); }
                100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
            }
            </style>

            <!-- Main Footer Body -->
            <div class="container py-5">
                <div class="row g-4">
                    
                    <!-- Col 1: Provincial Identity, Motto & Social Media -->
                    <div class="col-lg-5">
                        <div class="d-flex align-items-center gap-3 mb-2.5">
                            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px;" class="shadow-xs">
                                <img src="<?= base_url('assets/images/phatthalung_fabric_emblem.svg') ?>" alt="ลายผ้าอัตลักษณ์ประจำจังหวัดพัทลุง" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                            <div>
                                <h4 class="fw-bold text-white mb-0" style="font-size: 1.35rem; letter-spacing: -0.2px;">
                                    <?= htmlspecialchars($siteConfig['site_title_th'] ?? 'จังหวัดพัทลุง') ?>
                                </h4>
                                <small class="text-light opacity-75" style="font-size: 0.85rem;">
                                    <?= htmlspecialchars($siteConfig['site_title_en'] ?? 'Phatthalung Provincial Portal') ?>
                                </small>
                            </div>
                        </div>

                        <!-- Provincial Slogan Motto -->
                        <p class="small mb-3.5 fst-italic" style="line-height: 1.6; color: #a7f3d0; font-size: 0.88rem;">
                            "<?= htmlspecialchars($siteConfig['slogan'] ?? 'เมืองหนังโนราห์ อู่นาข้าว พราวน้ำตก แหล่งนกน้ำ ทะเลสาบงาม เขาอกทะลุ น้ำพุร้อน') ?>"
                        </p>

                        <!-- Social Media Links (Trang Province Style) -->
                        <div class="d-flex align-items-center gap-2.5 mb-3">
                            <span class="text-white small fw-semibold me-1">ติดตามเรา:</span>
                            <a href="<?= htmlspecialchars($siteConfig['fb_url'] ?? 'https://www.facebook.com/phatthalungPR') ?>" target="_blank" rel="noopener noreferrer" class="footer-social-btn social-fb" title="Facebook ประชาสัมพันธ์จังหวัดพัทลุง">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://line.me" target="_blank" rel="noopener noreferrer" class="footer-social-btn social-line" title="LINE Official จังหวัดพัทลุง">
                                <i class="fa-brands fa-line"></i>
                            </a>
                            <a href="https://www.youtube.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn social-yt" title="YouTube จังหวัดพัทลุง">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Col 2: Contact Information -->
                    <div class="col-sm-6 col-lg-3">
                        <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.98rem;">
                            <i class="fa-solid fa-address-card text-warning"></i> ข้อมูลการติดต่อ
                        </h6>
                        <div class="d-flex flex-column gap-2 small text-light opacity-90" style="font-size: 0.86rem; line-height: 1.6;">
                            <div>
                                <i class="fa-solid fa-location-dot text-success me-2"></i>
                                <?= htmlspecialchars($siteConfig['address'] ?? 'ศาลากลางจังหวัดพัทลุง ถนนราเมศวร์ ตำบลคูหาสวรรค์ อำเภอเมืองพัทลุง 93000') ?>
                            </div>
                            <div>
                                <i class="fa-solid fa-phone text-warning me-2"></i> โทรศัพท์: 
                                <b><?= htmlspecialchars($siteConfig['contact_phone'] ?? '074-613409') ?></b>
                            </div>
                            <div>
                                <i class="fa-solid fa-fax text-info me-2"></i> แฟกซ์: 
                                <b>074-613409</b>
                            </div>
                            <div>
                                <i class="fa-solid fa-envelope text-warning me-2"></i> อีเมล: 
                                <b><?= htmlspecialchars($siteConfig['contact_email'] ?? 'saraban_phatthalung@moi.go.th') ?></b>
                            </div>
                            <div>
                                <i class="fa-solid fa-headset text-danger me-2"></i> ศูนย์ดำรงธรรม: 
                                <a href="<?= base_url('damrongtham') ?>" class="badge bg-danger rounded-pill px-2.5 py-1 text-white text-decoration-none shadow-xs" title="ศูนย์ดำรงธรรมจังหวัดพัทลุง (1567)">
                                    โทร. 1567 (24 ชม.) / ร้องเรียนออนไลน์
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Col 3: Official Guarantee Badges (W3C HTML5, W3C AAA, IPv6, ITA) -->
                    <div class="col-sm-6 col-lg-4">
                        <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2" style="font-size: 0.98rem;">
                            <i class="fa-solid fa-award text-warning"></i> การันตีและมาตรฐานภาครัฐ
                        </h6>
                        <p class="small text-light opacity-75 mb-3" style="font-size: 0.84rem; line-height: 1.6;">
                            เว็บไซต์ได้รับการพัฒนาและตรวจสอบความถูกต้องตามมาตรฐานสากล W3C รองรับการเข้าถึงสำหรับประชาชนทุกคน (Universal Accessibility)
                        </p>

                        <!-- Guarantee Badges Showcase -->
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                            <!-- 1. W3C HTML5 Validated Badge -->
                            <a href="https://validator.w3.org/" target="_blank" rel="noopener noreferrer" class="w3c-badge-pill" title="W3C HTML5 Validated Standard">
                                <span class="w3c-part">W3C</span>
                                <span class="html5-part">
                                    <i class="fa-brands fa-html5"></i> HTML5
                                    <i class="fa-solid fa-check ms-0.5" style="color: #4ade80;"></i>
                                </span>
                            </a>

                            <!-- 2. W3C WCAG 2.1 Level AAA -->
                            <a href="https://www.w3.org/WAI/WCAG2AAA-Conformance" target="_blank" rel="noopener noreferrer" class="w3c-aaa-pill" title="W3C WAI-AAA WCAG 2.1 Conformance Standard">
                                <span class="wai-part">W3C</span>
                                <span class="aaa-part">WAI-AAA</span>
                            </a>

                            <!-- 3. IPv6 Ready -->
                            <span class="cert-badge-custom" title="รองรับโครงข่ายอินเทอร์เน็ตแห่งอนาคต IPv6">
                                <i class="fa-solid fa-network-wired text-info"></i>
                                <span>IPv6 Ready</span>
                            </span>

                            <!-- 4. ITA Transparency Grade A -->
                            <a href="<?= base_url('ita') ?>" class="cert-badge-custom" title="การประเมินคุณธรรมและความโปร่งใส (ITA) ผ่านเกณฑ์ระดับดีเยี่ยม">
                                <i class="fa-solid fa-shield-halved text-warning"></i>
                                <span>ITA Pass AA</span>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Horizontal Policy & Compliance Navigation Bar (Trang Province Pattern) -->
                <div class="mt-4 pt-3.5 border-top text-center" style="border-color: rgba(255,255,255,0.12) !important;">
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 gap-md-3">
                        <a href="<?= base_url() ?>" class="footer-policy-link">หน้าหลัก</a>
                        <span class="text-white-50 opacity-40">|</span>
                        <a href="<?= base_url('page/general') ?>" class="footer-policy-link">เกี่ยวกับจังหวัด</a>
                        <span class="text-white-50 opacity-40">|</span>
                        <a href="<?= base_url('executives') ?>" class="footer-policy-link">การบริหารงาน</a>
                        <span class="text-white-50 opacity-40">|</span>
                        <a href="<?= base_url('contact') ?>" class="footer-policy-link">ติดต่อเรา</a>
                        <span class="text-white-50 opacity-40">|</span>
                        <a href="<?= base_url('documents') ?>" class="footer-policy-link">แผนผังเว็บไซต์</a>
                        <span class="text-white-50 opacity-40">|</span>
                        <a href="<?= base_url('page/website-policy') ?>" class="footer-policy-link">นโยบายเว็บไซต์</a>
                        <span class="text-white-50 opacity-40">|</span>
                        <a href="<?= base_url('page/security-policy') ?>" class="footer-policy-link">นโยบายการรักษาความมั่นคงปลอดภัย</a>
                        <span class="text-white-50 opacity-40">|</span>
                        <a href="<?= base_url('page/privacy-policy') ?>" class="footer-policy-link">นโยบายการคุ้มครองข้อมูลส่วนบุคคล (PDPA)</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Counter Strip: Visitor Statistics & Quick Staff Access (Trang Province Bar) -->
            <div class="py-3" style="background: rgba(1, 28, 22, 0.95); border-top: 1px solid rgba(255,255,255,0.08); font-size: 0.83rem;">
                <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
                    
                    <!-- Left: Visitor Statistics Counter -->
                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-2">
                        <span class="text-light opacity-75 small">
                            <i class="fa-regular fa-calendar-check text-warning me-1"></i> สถิติผู้เข้าชมตั้งแต่ <?= esc($visitorStats['start_date']) ?>:
                        </span>
                        
                        <!-- 1. Real-time Online Users -->
                        <div class="stat-pill-badge" title="จำนวนผู้ใช้งานที่กำลังออนไลน์อยู่ขณะนี้">
                            <span class="stat-pulse-dot"></span>
                            <span>ออนไลน์:</span>
                            <span class="stat-num"><?= esc($visitorStats['online_users']) ?></span>
                        </div>

                        <!-- 2. Today Visits -->
                        <div class="stat-pill-badge" title="สถิติผู้เข้าชมวันนี้">
                            <i class="fa-solid fa-chart-simple text-info" style="font-size: 0.75rem;"></i>
                            <span>วันนี้:</span>
                            <span class="stat-num"><?= esc($visitorStats['today']) ?></span>
                        </div>

                        <!-- 3. This Month Visits -->
                        <div class="stat-pill-badge d-none d-sm-inline-flex" title="สถิติผู้เข้าชมเดือนนี้">
                            <i class="fa-solid fa-chart-line text-warning" style="font-size: 0.75rem;"></i>
                            <span>เดือนนี้:</span>
                            <span class="stat-num"><?= esc($visitorStats['month']) ?></span>
                        </div>

                        <!-- 4. Total Visits -->
                        <div class="stat-pill-badge" title="ยอดผู้เข้าชมสะสมทั้งหมด">
                            <i class="fa-solid fa-users text-success" style="font-size: 0.75rem;"></i>
                            <span>รวมทั้งหมด:</span>
                            <span class="stat-num"><?= esc($visitorStats['total']) ?></span>
                        </div>
                    </div>

                    <!-- Right: Quick Staff Login & Webmail Links -->
                    <div class="d-flex align-items-center gap-2">
                        <!-- Staff Login Button -->
                        <a href="<?= base_url('login') ?>" class="btn btn-sm btn-dark rounded-pill px-3 py-1 text-light d-inline-flex align-items-center gap-1.5 shadow-xs" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); font-size: 0.78rem;" title="ระบบเข้าสู่ระบบสำหรับเจ้าหน้าที่">
                            <i class="fa-solid fa-lock text-warning"></i>
                            <span>ระบบเจ้าหน้าที่</span>
                        </a>

                        <!-- Webmail MOI Link -->
                        <a href="https://mail.moi.go.th" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-dark rounded-pill px-3 py-1 text-light d-inline-flex align-items-center gap-1.5 shadow-xs" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); font-size: 0.78rem;" title="ระบบจดหมายอิเล็กทรอนิกส์กระทรวงมหาดไทย (Webmail MOI)">
                            <i class="fa-solid fa-envelope text-info"></i>
                            <span>ระบบอีเมล</span>
                        </a>
                    </div>

                </div>
            </div>
        </footer>
    </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle CDN (Deferred - Non-blocking) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <!-- Custom Application Interactive Script (Minified & Deferred) -->
    <script src="<?= function_exists('asset_min_url') ? asset_min_url('assets/js/app.js') : base_url('assets/js/app.js') ?>" defer></script>
    
    <!-- Gov Portal Accessibility & Navigation Helpers -->
    <script>
    function toggleGovMobileNav() {
        const nav = document.getElementById('govNavList');
        if (nav) nav.classList.toggle('show-mobile');
    }

    function adjustPortalFontSize(scale) {
        const root = document.documentElement;
        const btns = document.querySelectorAll('.font-btn');
        btns.forEach(b => b.classList.remove('active-font'));
        
        if (scale === -1) {
            root.style.fontSize = '14px';
            if(btns[0]) btns[0].classList.add('active-font');
            App.toast('ลดขนาดตัวอักษร (Small View)', 'info');
        } else if (scale === 1) {
            root.style.fontSize = '19px';
            if(btns[2]) btns[2].classList.add('active-font');
            App.toast('ขยายขนาดตัวอักษรเพื่อผู้สูงอายุและประชาชน (Large View)', 'info');
        } else {
            root.style.fontSize = '16px';
            if(btns[1]) btns[1].classList.add('active-font');
            App.toast('คืนสู่ขนาดตัวอักษรปกติ (Normal View)', 'info');
        }
    }

    function scrollToSectionOrHome(sectionId) {
        const el = document.getElementById(sectionId);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        } else {
            window.location.href = "<?= base_url() ?>#" + sectionId;
        }
    }

    function openOmniSearch() {
        const el = document.getElementById('omniSearchModal');
        if (el && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(el).show();
        } else {
            window.location.href = "<?= base_url('api/search') ?>";
        }
    }
    </script>

    <?php // Include Frontend On-Page News & PR Studio for logged in Officer/Admin ?>
    <?= $this->include('components/news_studio') ?>
    <?= $this->include('components/service_banner_studio') ?>
    <?= $this->include('components/procurement_studio') ?>
    <?php if (function_exists('can_manage_gallery') && can_manage_gallery()): ?>
    <?= $this->include('components/gallery_studio') ?>
    <?php endif; ?>
    <?= $this->include('components/smart_event_modal') ?>
    <?= $this->include('components/executive_studio') ?>
    <?= $this->include('components/ita_studio') ?>

    <?php // Include Executive ShadowBox & Lightbox Suite for Interactive Galleries ?>
    <?= $this->include('components/shadow_box') ?>




    <?php // Include 24/7 Citizen AI Assistant (น้องโนรา AI) & Brain Studio ?>
    <?= $this->include('components/nora_ai_assistant') ?>

    <?php // Include W3C WCAG AAA Universal Accessibility Suite & Thai AI TTS ?>
    <?= $this->include('components/universal_accessibility_aaa') ?>

    <?php // Include Universal On-Page Live Text & Section Editor for Officers ?>
    <?= $this->include('components/live_text_editor') ?>

    <!-- Universal Smart Omni-Search Modal (Phatthalung Universal Search Engine) -->
    <div class="modal fade" id="omniSearchModal" tabindex="-1" aria-labelledby="omniSearchModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.15) !important;">
                <!-- Modal Header with Large Instant Search Input (Clean Dignified Theme) -->
                <div class="modal-header p-3 border-bottom" style="background: rgba(15, 23, 42, 0.95); border-color: rgba(16, 185, 129, 0.3) !important; border-bottom-width: 2px !important;">
                    <div class="d-flex align-items-center w-100 gap-3 p-2 px-3 rounded-4 shadow-sm" style="background: #ffffff; border: 2px solid #059669; box-shadow: 0 4px 20px rgba(5, 150, 105, 0.15) !important;">
                        <div class="p-2 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="background: #ecfdf5; color: #047857; width: 44px; height: 44px; border: 1.5px solid #a7f3d0;">
                            <i class="fa-solid fa-magnifying-glass fs-5"></i>
                        </div>
                        <input type="text" id="omniSearchInput" class="form-control form-control-lg border-0 bg-transparent text-dark fs-5 px-1 shadow-none fw-semibold" placeholder="ค้นหาข้อมูลบริการ ข่าวสาร หรือเรื่องที่ต้องการ..." autocomplete="off" style="color: #0f172a !important;">
                        
                        <!-- Thai Voice Search Trigger (Web Speech API) -->
                        <button type="button" class="btn rounded-circle p-2 d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" id="btnVoiceSearch" onclick="OmniSearch.startVoiceSearch()" title="ค้นหาด้วยเสียงภาษาไทย (Voice Search)" style="width: 44px; height: 44px; background: #ecfdf5; color: #047857; border: 1.5px solid #a7f3d0;">
                            <i class="fa-solid fa-microphone fs-5"></i>
                        </button>

                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-1.5 me-1 text-light fw-medium shadow-xs" data-bs-dismiss="modal" style="border: 1px solid #334155; font-size: 0.82rem;">
                            ESC
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body: Trending Chips & Real-time Categorized Results -->
                <div class="modal-body p-4" id="omniSearchBody" style="min-height: 400px; max-height: 68vh;">
                    <!-- Trending Searches Section (Real Dynamic Data) -->
                    <div id="omniTrendingSection">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2 text-warning">
                                <i class="fa-solid fa-fire-flame-curved"></i>
                                <h6 class="m-0 fw-bold">คำค้นหายอดนิยมประจำสัปดาห์ (Trending Keywords)</h6>
                            </div>
                            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 rounded-pill px-2.5 py-1 small fw-medium">
                                <i class="fa-solid fa-arrow-trend-up me-1"></i> คำค้นยอดนิยม
                            </span>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2 mb-4" id="omniTrendingChips">
                            <?php 
                            $trendingKeywords = function_exists('get_trending_keywords') ? get_trending_keywords(6) : [];
                            foreach ($trendingKeywords as $kwItem): 
                                $kw = is_array($kwItem) ? ($kwItem['keyword'] ?? '') : $kwItem;
                                $icon = is_array($kwItem) ? ($kwItem['icon'] ?? '') : '';
                            ?>
                                <button type="button" onclick="OmniSearch.quickSearch('<?= esc($kw) ?>')" class="btn btn-sm rounded-pill px-3 py-1.5 fw-medium shadow-xs hover-scale" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4); color: #ffffff; transition: all 0.2s ease;">
                                    <span><?= $icon ?> <?= esc($kw) ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <h6 class="text-secondary small fw-bold text-uppercase mb-3"><i class="fa-solid fa-compass me-1"></i>บริการและรายการแนะนำ (Featured Services)</h6>
                    </div>

                    <!-- Live Search Results Container -->
                    <div id="omniResultsContainer">
                        <!-- Dynamically populated via JS -->
                    </div>
                </div>
                
                <!-- Modal Footer with Keyboard Shortcuts & Official Identity -->
                <div class="modal-footer px-4 py-2 border-top d-flex justify-content-between text-muted small" style="background: rgba(0, 0, 0, 0.35); border-color: rgba(255, 255, 255, 0.1) !important; font-size: 0.8rem;">
                    <div class="d-flex align-items-center gap-3">
                        <span><kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">↑</kbd> <kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">↓</kbd> เพื่อนำทาง</span>
                        <span><kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">ENTER</kbd> เพื่อเปิดลิงก์</span>
                        <span><kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">ESC</kbd> ปิดหน้าต่าง</span>
                    </div>
                    <div>
                        <span class="text-light opacity-75 small"><i class="fa-solid fa-landmark me-1 text-warning"></i>ระบบสืบค้นข้อมูลพอร์ทัลจังหวัดพัทลุง</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // =========================================================================
    // GLOBAL MODAL STACKING SHIELD (2026+ CORE UTILITY)
    // ย้าย Modal ทั้งหมดไปอยู่ใต้ <body> ล่วงหน้าเพื่อป้องกัน Stacking Context & Backdrop Freeze
    // =========================================================================
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal').forEach(function(modalEl) {
            if (modalEl.parentNode !== document.body) {
                document.body.appendChild(modalEl);
            }
        });
    });
    </script>

    <!-- PWA Mobile App Installation Suite -->
    <?= $this->include('components/pwa_installer') ?>
</body>
</html>


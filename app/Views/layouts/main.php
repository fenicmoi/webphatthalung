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
    
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 CDN (for Modern Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Modern Glassmorphism Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    
    <?php if (!empty($siteConfig['theme_accent'])): ?>
    <style>
        :root {
            --accent-primary: <?= htmlspecialchars($siteConfig['theme_accent']) ?> !important;
            --gradient-hero: linear-gradient(135deg, <?= htmlspecialchars($siteConfig['theme_accent']) ?> 0%, #3b82f6 50%, #06b6d4 100%) !important;
        }
    </style>
    <?php endif; ?>
</head>
<body>
    <!-- Ambient Background Lighting Glow -->
    <div class="ambient-glow"></div>
    <div class="ambient-glow-2"></div>

    <div class="content-wrapper">
        <!-- Emergency & Disaster Early Warning Marquee Banner -->
        <?= $this->include('components/emergency_alert_banner') ?>

        <!-- Municipal Government Ribbon Header (แถบเมนูหัวราชการมาตรฐานใหม่) -->
        <header class="gov-header-wrapper">
            <div class="gov-navbar">
                <!-- 1. Left Slanted Brand Ribbon & Mobile Toggler -->
                <div class="gov-brand-bar d-flex align-items-center justify-content-between">
                    <a href="<?= base_url() ?>" class="gov-brand-ribbon">
                        <div class="gov-logo-circle">
                            <?php $siteLogo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                            <?php if (!empty($siteLogo)): ?>
                                <img src="<?= htmlspecialchars($siteLogo) ?>" alt="Logo" class="gov-logo-img">
                            <?php else: ?>
                                <i class="fa-solid fa-building-columns text-success" style="font-size: 1.8rem;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="gov-title-stack">
                            <span class="gov-title-main"><?= htmlspecialchars($siteConfig['site_title_th'] ?? 'จังหวัดพัทลุง') ?></span>
                            <span class="gov-title-sub"><?= htmlspecialchars($siteConfig['site_title_en'] ?? $siteConfig['slogan'] ?? 'พัทลุงเมืองน่าอยู่ มุ่งสู่ดิจิทัลสากล') ?></span>
                        </div>
                    </a>

                    <button class="navbar-toggler d-xl-none border-0 p-3 me-2 text-white" type="button" onclick="toggleGovMobileNav()" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars-staggered fa-xl" style="color: #6fd3c6;"></i>
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
                                <ul class="dropdown-menu gov-dropdown-menu shadow-lg border-0">
                                    <?php foreach($nav['children'] as $sub): ?>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-decoration-none transition-all" href="<?= htmlspecialchars($sub['url'] ?? '#') ?>" target="<?= htmlspecialchars($sub['target'] ?? '_self') ?>" style="font-size: 0.95rem; font-weight: 500;">
                                            <i class="fa-solid fa-chevron-right text-primary" style="font-size: 0.75rem;"></i>
                                            <span><?= htmlspecialchars($sub['title'] ?? '') ?></span>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="gov-nav-item">
                                <a class="gov-nav-link" href="<?= htmlspecialchars($nav['url'] ?? '#') ?>" target="<?= htmlspecialchars($nav['target'] ?? '_self') ?>">
                                    <span><?= htmlspecialchars($nav['title'] ?? '') ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="gov-nav-item dropdown">
                            <a class="gov-nav-link dropdown-toggle text-warning" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-user me-1"></i> <?= mb_substr(session()->get('full_name') ?? 'เจ้าหน้าที่', 0, 15) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end gov-dropdown-menu shadow-lg border-0">
                                <li class="px-3 py-2 border-bottom"><small class="text-muted">สถานะ: เจ้าหน้าที่ระบบ</small></li>
                                <li><a class="dropdown-item py-2 text-primary fw-bold" href="<?= base_url('admin/dashboard') ?>"><i class="fa-solid fa-gauge me-2"></i>หลังบ้าน Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger fw-bold" href="<?= base_url('logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i>ออกจากระบบ</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="gov-nav-item">
                            <a class="gov-nav-link text-warning" href="<?= base_url('login') ?>" title="สำหรับเจ้าหน้าที่">
                                <i class="fa-solid fa-lock"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <!-- 3. Right Utility Station (Streamlined & Clean) -->
                <div class="gov-utility-station d-flex align-items-center pe-3">
                    <!-- Universal Smart Omni-Search Trigger -->
                    <button type="button" onclick="OmniSearch.open()" class="btn btn-sm btn-omni-trigger d-flex align-items-center gap-2 px-3 py-2 border rounded-pill shadow-sm m-0 text-decoration-none transition-transform hover-scale" style="background: rgba(14, 165, 233, 0.15); border: 1px solid rgba(14, 165, 233, 0.45) !important; color: var(--text-primary);">
                        <i class="fa-solid fa-wand-magic-sparkles text-warning fs-6"></i>
                        <span class="d-none d-md-inline fw-bold" style="font-size: 0.88rem;">ค้นหาอัจฉริยะ</span>
                        <span class="badge bg-primary text-white rounded-pill px-2 py-0 ms-1" style="font-size: 0.72rem;">Ctrl+K</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Widescreen Full-Width Edge-to-Edge Hero Banner Section (Hybrid Widescreen) -->
        <?= $this->renderSection('hero_banner') ?>

        <!-- Main Page Content Section -->
        <main class="container py-4 py-lg-5">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Minimalist Footer -->
        <footer class="py-5 mt-5" style="border-top: 1px solid var(--glass-border); background: var(--glass-navbar-bg);">
            <div class="container text-center text-md-start">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.25rem;">
                            จังหวัดพัทลุง | Phatthalung Province Modern Portal
                        </h6>
                        <small style="color: var(--text-muted);">
                            ขับเคลื่อนด้วย <strong>CodeIgniter <?= \CodeIgniter\CodeIgniter::CI_VERSION ?></strong> บนโครงสร้างเซิร์ฟเวอร์ร่วมสมัย (PHP 7.4+ Supported)
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="glass-badge">
                            <i class="fa-solid fa-circle-check text-success" style="color: #10b981;"></i> 
                            ระบบพร้อมให้บริการ 100%
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Application Interactive Script -->
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    
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
    </script>

    <?php // Include Frontend On-Page News & PR Studio for logged in Officer/Admin ?>
    <?= $this->include('components/news_studio') ?>
    <?= $this->include('components/service_banner_studio') ?>
    <?= $this->include('components/procurement_studio') ?>
    <?= $this->include('components/gallery_studio') ?>
    <?= $this->include('components/smart_event_modal') ?>
    <?= $this->include('components/executive_studio') ?>
    <?= $this->include('components/ita_studio') ?>

    <?php // Include Executive ShadowBox & Lightbox Suite for Interactive Galleries ?>
    <?= $this->include('components/shadow_box') ?>

    <?php // Include Smart Floating Capsule Dock & Accessibility Suite ?>
    <?= $this->include('components/smart_floating_dock') ?>

    <?php // Include 24/7 Citizen AI Assistant (น้องโนรา AI) & Brain Studio ?>
    <?= $this->include('components/nora_ai_assistant') ?>

    <?php // Include W3C WCAG AAA Universal Accessibility Suite & Thai AI TTS ?>
    <?= $this->include('components/universal_accessibility_aaa') ?>

    <!-- Universal Smart Omni-Search Modal (Phatthalung Universal Search Engine) -->
    <div class="modal fade" id="omniSearchModal" tabindex="-1" aria-labelledby="omniSearchModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.15) !important;">
                <!-- Modal Header with Large Instant Search Input & Voice AI (High-Contrast Luminous Spotlight Theme) -->
                <div class="modal-header p-3 border-bottom" style="background: rgba(15, 23, 42, 0.95); border-color: rgba(56, 189, 248, 0.4) !important; border-bottom-width: 2px !important;">
                    <div class="d-flex align-items-center w-100 gap-3 p-2 px-3 rounded-4 shadow-lg" style="background: #ffffff; border: 3px solid #00f0ff; box-shadow: 0 0 30px rgba(0, 240, 255, 0.45) !important;">
                        <div class="p-2 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="background: #e0f2fe; color: #0284c7; width: 45px; height: 45px; border: 2px solid #bae6fd;">
                            <i class="fa-solid fa-magnifying-glass fs-4 animate-bounce"></i>
                        </div>
                        <input type="text" id="omniSearchInput" class="form-control form-control-lg border-0 bg-transparent text-dark fs-4 px-1 shadow-none fw-bold" placeholder="✨ พิมพ์ชื่อบริการ, ร้องทุกข์ หรือคำค้นที่ต้องการ..." autocomplete="off" style="color: #0f172a !important;">
                        
                        <!-- Thai Voice Search Trigger (Web Speech API) -->
                        <button type="button" class="btn rounded-circle p-2 d-flex align-items-center justify-content-center shadow flex-shrink-0" id="btnVoiceSearch" onclick="OmniSearch.startVoiceSearch()" title="ค้นหาด้วยเสียงภาษาไทย (Voice Search)" style="width: 48px; height: 48px; background: #fff7ed; color: #c2410c; border: 2px solid #fdba74;">
                            <i class="fa-solid fa-microphone fs-5 text-danger animate-pulse"></i>
                        </button>

                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-2 me-1 text-info fw-bold shadow-sm" data-bs-dismiss="modal" style="border: 1px solid #334155;">
                            ESC
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body: Trending Chips & Real-time Categorized Results -->
                <div class="modal-body p-4" id="omniSearchBody" style="min-height: 400px; max-height: 68vh;">
                    <!-- Trending Searches Section -->
                    <div id="omniTrendingSection">
                        <div class="d-flex align-items-center gap-2 mb-3 text-warning">
                            <i class="fa-solid fa-fire-flame-curved"></i>
                            <h6 class="m-0 fw-bold">คำค้นหายอดนิยมประจำสัปดาห์ (Trending Keywords)</h6>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-4" id="omniTrendingChips">
                            <button type="button" onclick="OmniSearch.quickSearch('ยื่นคำร้อง PDPA')" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold">🛡️ ยื่นคำร้อง PDPA</button>
                            <button type="button" onclick="OmniSearch.quickSearch('ภาษีที่ดิน')" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">💰 ภาษีที่ดิน e-Tax</button>
                            <button type="button" onclick="OmniSearch.quickSearch('ทะเลน้อย')" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold">🌿 ทะเลน้อย มรดกโลก</button>
                            <button type="button" onclick="OmniSearch.quickSearch('กล้อง CCTV')" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">📹 กล้อง AI CCTV</button>
                            <button type="button" onclick="OmniSearch.quickSearch('ร้องทุกข์')" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">📢 ร้องทุกข์ออนไลน์ 24 ชม.</button>
                            <button type="button" onclick="OmniSearch.quickSearch('แบบฟอร์ม')" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-bold">📂 โหลดแบบฟอร์มคำร้อง PDF</button>
                        </div>

                        <h6 class="text-secondary small fw-bold text-uppercase mb-3"><i class="fa-solid fa-compass me-1"></i>บริการและรายการแนะนำ (Featured Services)</h6>
                    </div>

                    <!-- Live Search Results Container -->
                    <div id="omniResultsContainer">
                        <!-- Dynamically populated via JS -->
                    </div>
                </div>
                
                <!-- Modal Footer with Keyboard Shortcuts & AI Branding -->
                <div class="modal-footer px-4 py-2 border-top d-flex justify-content-between text-muted small" style="background: rgba(0, 0, 0, 0.35); border-color: rgba(255, 255, 255, 0.1) !important; font-size: 0.8rem;">
                    <div class="d-flex align-items-center gap-3">
                        <span><kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">↑</kbd> <kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">↓</kbd> เพื่อนำทาง</span>
                        <span><kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">ENTER</kbd> เพื่อเปิดลิงก์</span>
                        <span><kbd class="bg-dark border border-secondary text-light px-2 py-1 rounded">ESC</kbd> ปิดหน้าต่าง</span>
                    </div>
                    <div>
                        <span class="text-info fw-bold"><i class="fa-solid fa-wand-magic-sparkles me-1"></i>Powered by Phatthalung Omni-Search AI</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // =========================================================================
    // GLOBAL MODAL ANTI-FREEZE & BACKDROP SHIELD (2026+ CORE UTILITY)
    // ป้องกันปัญหาหน้าจอค้างหรือกดไม่ได้เมื่อเปิด Modal ใน Container ที่มี Stacking Context
    // =========================================================================
    document.addEventListener('show.bs.modal', function(event) {
        const modalEl = event.target;
        if (modalEl && modalEl.parentNode !== document.body) {
            document.body.appendChild(modalEl);
        }
    }, true);
    </script>
</body>
</html>


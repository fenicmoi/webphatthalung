<?php $siteConfig = function_exists('get_site_settings') ? get_site_settings() : []; ?>
<!DOCTYPE html>
<html lang="th" data-theme="<?= htmlspecialchars($siteConfig['default_theme'] ?? 'light') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteConfig['site_title_th'] ?? 'จังหวัดพัทลุง') ?> | <?= htmlspecialchars($siteConfig['site_title_en'] ?? 'Phatthalung Digital Portal') ?></title>
    <meta name="keywords" content="<?= htmlspecialchars($siteConfig['seo_keywords'] ?? '') ?>">
    <meta name="description" content="<?= htmlspecialchars($siteConfig['slogan'] ?? '') ?>">
    
    <!-- CSRF Meta for Interactive Fetch API -->
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
        <!-- Municipal Government Ribbon Header (แถบเมนูหัวราชการมาตรฐานใหม่) -->
        <header class="gov-header-wrapper">
            <div class="gov-navbar">
                <!-- 1. Left Slanted Brand Ribbon -->
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

                <button class="navbar-toggler d-xl-none border-0 px-4 my-2 text-white" type="button" onclick="toggleGovMobileNav()">
                    <i class="fa-solid fa-bars-staggered fa-xl"></i>
                </button>

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

                <!-- 3. Right Utility Station (อารยสถาปัตย์ & เครื่องมือประชาชน) -->
                <div class="gov-utility-station">
                    <div class="gov-util-content">
                        <!-- Upper Tier: Language, Contrast, Search -->
                        <div class="gov-util-row-top">
                            <span class="d-flex align-items-center gap-1 text-secondary" style="font-size: 0.8rem;">
                                <span>ภาษา</span>
                                <i class="fa-solid fa-globe text-primary"></i>
                                <strong class="text-primary text-uppercase">th</strong>
                            </span>
                            <span class="text-muted">|</span>
                            <!-- Color/Theme Accessibility switchers -->
                            <div class="d-flex gap-1" title="เปลี่ยนโหมดสีตัวอักษร / Dark Mode">
                                <button type="button" id="theme-toggle" class="btn btn-sm p-0 text-secondary border-0" style="width: 20px; height: 20px; background: #e2e8f0; border-radius: 3px;">
                                    <i class="fa-solid fa-circle-half-stroke" style="font-size: 0.75rem;"></i>
                                </button>
                            </div>
                            <span class="text-muted">|</span>
                            <!-- Quick Search Icon -->
                            <a href="#search" onclick="App.toast('เปิดระบบค้นหาข้อมูลทั่วเว็บไซต์...', 'info'); return false;" class="text-primary text-decoration-none">
                                <i class="fa-solid fa-magnifying-glass" style="font-size: 1rem;"></i>
                            </a>
                        </div>

                        <!-- Lower Tier: Font Size Resizers (ปรับขนาดตัวอักษร ก- ก ก+) -->
                        <div class="gov-util-row-bottom">
                            <span>ปรับขนาดตัวอักษร</span>
                            <div class="d-flex align-items-center gap-1">
                                <button type="button" class="font-btn font-small" onclick="adjustPortalFontSize(-1)" title="ลดขนาดตัวอักษร">ก</button>
                                <button type="button" class="font-btn font-medium active-font" id="btnFontNormal" onclick="adjustPortalFontSize(0)" title="ขนาดปกติ">ก</button>
                                <button type="button" class="font-btn font-large" onclick="adjustPortalFontSize(1)" title="ขยายขนาดตัวอักษร">ก</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Page Content Section -->
        <main class="container py-5">
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
</body>
</html>

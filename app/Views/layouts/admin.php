<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ระบบบริหารจัดการหลังบ้าน | Phatthalung Admin Portal' ?></title>
    
    <!-- CSRF Meta -->
    <meta name="X-CSRF-HEADER" content="<?= csrf_header() ?>">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('uploads/logo/logo_1787048018.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('uploads/logo/logo_1787048018.png') ?>">

    <!-- Google Fonts (Preconnect + Non-blocking with font-display: swap) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Prompt:wght@500;600;700&family=Sarabun:wght@400;500;600;700&subset=thai,latin&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Prompt:wght@500;600;700&family=Sarabun:wght@400;500;600;700&subset=thai,latin&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Prompt:wght@500;600;700&family=Sarabun:wght@400;500;600;700&subset=thai,latin&display=swap">
    </noscript>

    <!-- Bootstrap 5.3 & FontAwesome (Non-blocking) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </noscript>
    
    <!-- Custom Design System (Minified & Cache Busted) -->
    <link rel="stylesheet" href="<?= function_exists('asset_min_url') ? asset_min_url('assets/css/main.css') : base_url('assets/css/main.css?v=' . time()) ?>">
    
    <!-- Ergonomic UI Overrides for Admin Portal (High Contrast & Legibility) -->
    <style>
        /* 
         * 🚀 THE MAGIC FIX FOR FONT SIZE ON LARGE SCREENS 🚀
         * Bootstrap 5 relies on 'rem' for everything (fonts, margins, paddings, sizes).
         * By scaling up the root html font-size, we proportionally enlarge the ENTIRE dashboard
         * so the user doesn't have to zoom manually in the browser.
         */
        html {
            font-size: 115% !important; /* Base size scales from 16px to ~18.4px globally */
        }
        body.admin-body-classic {
            font-size: 1rem !important; /* Force body to use the new scaled rem size */
        }

        :root {
            --sidebar-w: 320px;
        }
        .admin-sidebar {
            width: var(--sidebar-w) !important;
        }
        .admin-main {
            margin-left: var(--sidebar-w) !important;
        }
        
        /* Typography Enhancements for 125% Scale / Farsightedness (สายตายาว 150) */
        .sidebar-menu-list {
            padding: 0.5rem 0.85rem !important;
        }
        .sidebar-menu-title {
            font-size: 15px !important;
            font-weight: 700 !important;
            color: #cbd5e1 !important; 
            padding: 1.25rem 0.5rem 0.5rem !important;
            letter-spacing: 0.05em !important;
        }
        .sidebar-group-btn {
            font-size: 17px !important; 
            font-weight: 600 !important;
            padding: 0.75rem 1rem !important;
            color: #f8fafc !important;
            margin-bottom: 0.35rem !important;
            border-radius: 12px !important;
        }
        .sidebar-group-btn.has-active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08) !important;
        }
        .sidebar-icon-box {
            width: 38px !important;
            height: 38px !important;
            font-size: 1.15rem !important;
            border-radius: 10px !important;
        }
        .sidebar-sublink {
            font-size: 16px !important;
            font-weight: 500 !important;
            padding: 0.65rem 1rem !important;
            color: #e2e8f0 !important;
            margin-bottom: 0.25rem !important;
            border-radius: 10px !important;
        }
        .sidebar-sublink:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        .sidebar-link-single {
            font-size: 17px !important;
            font-weight: 600 !important;
            padding: 0.75rem 1rem !important;
            color: #f8fafc !important;
            border-radius: 12px !important;
        }
        .sidebar-profile {
            padding: 1rem !important;
            margin: 1rem 1rem 0.5rem !important;
        }
        .avatar-badge {
            width: 44px !important;
            height: 44px !important;
            font-size: 1.15rem !important;
            border-radius: 12px !important;
        }
        .sidebar-profile h6 {
            font-size: 16px !important;
            color: #ffffff !important;
        }
        .sidebar-profile small {
            font-size: 14px !important;
            color: #cbd5e1 !important;
        }
        .sidebar-footer .btn {
            font-size: 16px !important;
            padding: 0.6rem 0.75rem !important;
        }
        @media (max-width: 992px) {
            .admin-main { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="admin-body-classic">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-header">
                <?php $adminLogo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                <?php if (!empty($adminLogo)): ?>
                    <img src="<?= htmlspecialchars($adminLogo) ?>" alt="Logo" style="height: 36px; width: auto; max-width: 40px; object-fit: contain;">
                <?php else: ?>
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #059669 0%, #0284c7 100%); display: flex; align-items: center; justify-content: center; color: #fff;">
                        <i class="fa-solid fa-layer-group" style="font-size: 1.1rem;"></i>
                    </div>
                <?php endif; ?>
                <div class="d-flex flex-column">
                    <span style="font-size: 1rem; font-weight: 700; color: #ffffff; line-height: 1.2;">PHATTHALUNG</span>
                    <span style="font-size: 12px; font-weight: 600; color: #34d399; letter-spacing: 0.05em;">ADMIN PORTAL</span>
                </div>
            </a>

            <!-- User Profile Snippet Card -->
            <div class="sidebar-profile">
                <div class="avatar-badge"><?= session()->get('avatar_initials') ?? 'AD' ?></div>
                <div style="overflow: hidden; flex: 1;">
                    <h6 class="mb-0 fw-bold" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden; font-size: 14px;">
                        <?= session()->get('full_name') ?? 'ผู้ดูแลระบบ' ?>
                    </h6>
                    <small style="color: #94a3b8; font-size: 13px; display: flex; align-items: center;">
                        <span class="status-dot"></span> <?= session()->get('role') === 'admin' ? 'Super Admin' : 'Officer' ?>
                    </small>
                </div>
            </div>

            <?php
            $currMenu = $activeMenu ?? '';
            $isNewsActive = in_array($currMenu, ['news', 'news_aggregator', 'videos']);
            $isProvinceActive = in_array($currMenu, ['executive_manager', 'governors', 'governor_policy', 'strategy_manager', 'project_manager']);
            $isCmsActive = in_array($currMenu, ['page_manager', 'menu_manager', 'banners', 'services', 'procurement', 'site_texts']);
            $isServicesActive = in_array($currMenu, ['mailbox_manager', 'contact_manager']);
            $isSystemActive = in_array($currMenu, ['nora_ai', 'settings', 'users', 'database_manager']);
            ?>

            <!-- Sidebar Navigation Menu Items -->
            <div class="sidebar-menu-list" id="sidebarMenuList">
                <!-- Overview: Single Direct Link -->
                <div class="sidebar-menu-title">
                    <span>ภาพรวมระบบ</span>
                    <button type="button" class="btn btn-link p-0" id="btnToggleAll" title="ย่อ/ขยายทุกกลุ่ม" style="font-size: 13px; text-decoration: none; color: #94a3b8 !important;">
                        <i class="fa-solid fa-arrows-up-down me-1"></i>ย่อ/ขยาย
                    </button>
                </div>
                
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link-single <?= $currMenu === 'dashboard' ? 'active' : '' ?>">
                    <div class="sidebar-icon-box" style="background: rgba(16, 185, 129, 0.18); color: #34d399;">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <span>แผงควบคุมหลัก (Dashboard)</span>
                </a>

                <!-- Group 1: News & Media -->
                <div class="sidebar-group <?= $isNewsActive ? 'open' : '' ?>" id="grp-news">
                    <button type="button" class="sidebar-group-btn <?= $isNewsActive ? 'has-active' : '' ?>" onclick="toggleSidebarGroup('grp-news')">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="sidebar-icon-box" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                                <i class="fa-solid fa-newspaper"></i>
                            </div>
                            <span>ข่าวสารและมีเดีย</span>
                        </div>
                        <i class="fa-solid fa-chevron-down sidebar-chevron"></i>
                    </button>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?= base_url('news') ?>" class="sidebar-sublink <?= $currMenu === 'news' ? 'active' : '' ?>">
                                <i class="fa-regular fa-newspaper"></i>
                                <span>ข่าวประชาสัมพันธ์</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/news-aggregator') ?>" class="sidebar-sublink <?= $currMenu === 'news_aggregator' ? 'active' : '' ?>">
                                <i class="fa-solid fa-satellite-dish"></i>
                                <span>ดึงข่าวอัตโนมัติ (Feeds)</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/videos') ?>" class="sidebar-sublink <?= $currMenu === 'videos' ? 'active' : '' ?>">
                                <i class="fa-solid fa-film"></i>
                                <span>วีดิทัศน์ & YouTube</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group 2: Provincial Info & Strategy -->
                <div class="sidebar-group <?= $isProvinceActive ? 'open' : '' ?>" id="grp-province">
                    <button type="button" class="sidebar-group-btn <?= $isProvinceActive ? 'has-active' : '' ?>" onclick="toggleSidebarGroup('grp-province')">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="sidebar-icon-box" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                                <i class="fa-solid fa-landmark"></i>
                            </div>
                            <span>ข้อมูลจังหวัด & ยุทธศาสตร์</span>
                        </div>
                        <i class="fa-solid fa-chevron-down sidebar-chevron"></i>
                    </button>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?= base_url('admin/executives') ?>" class="sidebar-sublink <?= $currMenu === 'executive_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-user-tie"></i>
                                <span>คณะผู้บริหารปัจจุบัน</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/governors') ?>" class="sidebar-sublink <?= $currMenu === 'governors' ? 'active' : '' ?>">
                                <i class="fa-solid fa-crown"></i>
                                <span>ทำเนียบผู้ว่าราชการ</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/governor-policy') ?>" class="sidebar-sublink <?= $currMenu === 'governor_policy' ? 'active' : '' ?>">
                                <i class="fa-solid fa-user-gear"></i>
                                <span>นโยบายผู้ว่าราชการ</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/strategy') ?>" class="sidebar-sublink <?= $currMenu === 'strategy_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-bullseye"></i>
                                <span>ยุทธศาสตร์ & แผนพัฒนา</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/projects') ?>" class="sidebar-sublink <?= $currMenu === 'project_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>แผนที่ GIS & eMENSCR</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group 3: Web CMS & Portal Management -->
                <div class="sidebar-group <?= $isCmsActive ? 'open' : '' ?>" id="grp-cms">
                    <button type="button" class="sidebar-group-btn <?= $isCmsActive ? 'has-active' : '' ?>" onclick="toggleSidebarGroup('grp-cms')">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="sidebar-icon-box" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <span>จัดการหน้าเว็บและเนื้อหา</span>
                        </div>
                        <i class="fa-solid fa-chevron-down sidebar-chevron"></i>
                    </button>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?= base_url('admin/pages') ?>" class="sidebar-sublink <?= $currMenu === 'page_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-file-lines"></i>
                                <span>หน้าเว็บไซต์ (Static Pages)</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/menu') ?>" class="sidebar-sublink <?= $currMenu === 'menu_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-compass"></i>
                                <span>จัดการเมนูบาร์เว็บไซต์</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/banners') ?>" class="sidebar-sublink <?= $currMenu === 'banners' ? 'active' : '' ?>">
                                <i class="fa-solid fa-images"></i>
                                <span>แบนเนอร์ & สไลด์หน้าแรก</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/service-banners') ?>" class="sidebar-sublink <?= $currMenu === 'services' ? 'active' : '' ?>">
                                <i class="fa-solid fa-bullhorn text-primary"></i>
                                <span>แบนเนอร์ประชาสัมพันธ์ & หน่วยงานสัมพันธ์</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/procurement') ?>" class="sidebar-sublink <?= $currMenu === 'procurement' ? 'active' : '' ?>">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <span>จัดซื้อจัดจ้าง e-GP</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/site-texts') ?>" class="sidebar-sublink <?= $currMenu === 'site_texts' ? 'active' : '' ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>แก้ไขข้อความทั่วเว็บไซต์</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group 4: Citizen Inquiries & Complaints -->
                <div class="sidebar-group <?= $isServicesActive ? 'open' : '' ?>" id="grp-services">
                    <button type="button" class="sidebar-group-btn <?= $isServicesActive ? 'has-active' : '' ?>" onclick="toggleSidebarGroup('grp-services')">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="sidebar-icon-box" style="background: rgba(6, 182, 212, 0.15); color: #06b6d4;">
                                <i class="fa-solid fa-comments"></i>
                            </div>
                            <span>บริการและรับเรื่องราว</span>
                        </div>
                        <i class="fa-solid fa-chevron-down sidebar-chevron"></i>
                    </button>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?= base_url('admin/mailbox') ?>" class="sidebar-sublink <?= $currMenu === 'mailbox_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-envelope"></i>
                                <span>กล่องจดหมายกลาง (MOI)</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/contacts') ?>" class="sidebar-sublink <?= $currMenu === 'contact_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-envelope-open-text"></i>
                                <span>เรื่องติดต่อ & ร้องเรียน</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Group 5: AI Tools & Settings -->
                <div class="sidebar-group <?= $isSystemActive ? 'open' : '' ?>" id="grp-system">
                    <button type="button" class="sidebar-group-btn <?= $isSystemActive ? 'has-active' : '' ?>" onclick="toggleSidebarGroup('grp-system')">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="sidebar-icon-box" style="background: rgba(168, 85, 247, 0.15); color: #a855f7;">
                                <i class="fa-solid fa-sliders"></i>
                            </div>
                            <span>ระบบและความปลอดภัย</span>
                        </div>
                        <i class="fa-solid fa-chevron-down sidebar-chevron"></i>
                    </button>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="<?= base_url('admin/nora-ai') ?>" class="sidebar-sublink <?= $currMenu === 'nora_ai' ? 'active' : '' ?>">
                                <i class="fa-solid fa-wand-magic-sparkles text-warning"></i>
                                <span>น้องโนรา AI & คลังความรู้</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/settings') ?>" class="sidebar-sublink <?= $currMenu === 'settings' ? 'active' : '' ?>">
                                <i class="fa-solid fa-gear"></i>
                                <span>ตั้งค่าระบบเว็บไซต์</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/database-manager') ?>" class="sidebar-sublink <?= $currMenu === 'database_manager' ? 'active' : '' ?>">
                                <i class="fa-solid fa-database text-info"></i>
                                <span>จัดการตาราง & ฐานข้อมูล</span>
                            </a>
                        </li>
                        <li>
                            <a href="#users" onclick="App.toast('ระบบจัดการสิทธิ์เจ้าหน้าที่อยู่ในแผนอัปเดตถัดไป', 'info')" class="sidebar-sublink">
                                <i class="fa-solid fa-users-gear"></i>
                                <span>เจ้าหน้าที่ระบบ</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="d-flex gap-2">
                    <a href="<?= base_url() ?>" target="_blank" class="btn btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 text-decoration-none" style="background: rgba(255, 255, 255, 0.08); color: #e2e8f0; font-size: 15px; border-radius: 8px; padding: 0.5rem 0.5rem;" title="เปิดหน้าเว็บไซต์ประชาชน">
                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.85rem;"></i> <span>หน้าเว็บประชาชน</span>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-sm d-flex align-items-center justify-content-center gap-1.5 text-decoration-none text-danger" style="background: rgba(239, 68, 68, 0.12); font-size: 15px; border-radius: 8px; padding: 0.5rem 0.85rem; font-weight: 600;" title="ออกจากระบบ">
                        <i class="fa-solid fa-power-off"></i> <span>ออก</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Clean Topbar with Real-Time Spotlight Menu Search -->
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-3 position-relative">
                    <button class="btn btn-sm p-1 d-lg-none" id="toggleSidebarBtn" style="color: #475569; font-size: 1.25rem; border: none; background: transparent;">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    
                    <!-- Spotlight Live Search -->
                    <div class="d-none d-md-flex align-items-center admin-search-box position-relative">
                        <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8; font-size: 0.85rem;"></i>
                        <input type="text" id="adminMenuSearch" placeholder="ค้นหาเมนู (เช่น ข่าว, ผู้ว่า, แผน, สถิติ)..." autocomplete="off">
                        <span id="searchClearBtn" class="d-none text-muted" style="cursor: pointer; font-size: 0.75rem;" title="ล้างการค้นหา"><i class="fa-solid fa-xmark"></i></span>
                        
                        <!-- Instant Dropdown Results -->
                        <div id="spotlightDropdown" class="admin-spotlight-dropdown shadow-lg"></div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?= base_url() ?>" target="_blank" class="btn-modern-outline d-none d-sm-inline-flex align-items-center gap-2 text-decoration-none" style="padding: 0.35rem 0.85rem; font-size: 0.85rem;">
                        <i class="fa-solid fa-globe text-primary"></i> <span>ไปยังหน้าเว็บไซต์</span>
                    </a>
                    
                    <!-- Notification Bell -->
                    <button class="btn btn-light rounded-circle position-relative border" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; color: #475569;" title="การแจ้งเตือน" onclick="App.toast('ระบบปกติ ไม่มีการแจ้งเตือนค้าง', 'info')">
                        <i class="fa-regular fa-bell" style="font-size: 1rem;"></i>
                    </button>
                </div>
            </header>

            <!-- Admin Workspace View Section -->
            <main class="admin-content">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5.3 & App JS (Deferred - Non-blocking) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="<?= function_exists('asset_min_url') ? asset_min_url('assets/js/app.js') : base_url('assets/js/app.js') ?>" defer></script>
    <script>
        // 1. Mobile Sidebar Toggle
        document.getElementById('toggleSidebarBtn')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('show');
        });

        // 2. Accordion Group Toggle
        function toggleSidebarGroup(groupId) {
            const group = document.getElementById(groupId);
            if (!group) return;
            group.classList.toggle('open');
        }

        // 3. Toggle All Groups
        let allExpanded = true;
        document.getElementById('btnToggleAll')?.addEventListener('click', function() {
            const groups = document.querySelectorAll('.sidebar-group');
            allExpanded = !allExpanded;
            groups.forEach(grp => {
                if (allExpanded) {
                    grp.classList.add('open');
                } else {
                    grp.classList.remove('open');
                }
            });
        });

        // 4. Instant Live Spotlight Search
        const searchInput = document.getElementById('adminMenuSearch');
        const clearBtn = document.getElementById('searchClearBtn');
        const dropdown = document.getElementById('spotlightDropdown');

        if (searchInput) {
            // Index all menu links
            const menuLinks = [];
            document.querySelectorAll('.sidebar-sublink, .sidebar-link-single').forEach(link => {
                const text = link.innerText.trim();
                const href = link.getAttribute('href');
                const icon = link.querySelector('i')?.className || 'fa-solid fa-link';
                const parentGroup = link.closest('.sidebar-group');
                const groupTitle = parentGroup ? parentGroup.querySelector('.sidebar-group-btn span')?.innerText.trim() : 'ภาพรวม';
                menuLinks.push({ text, href, icon, groupTitle, element: link, parentGroup });
            });

            searchInput.addEventListener('input', function() {
                const q = this.value.trim().toLowerCase();
                if (q === '') {
                    clearBtn.classList.add('d-none');
                    dropdown.classList.remove('show');
                    dropdown.innerHTML = '';
                    // Reset all groups to default visibility
                    menuLinks.forEach(m => {
                        m.element.style.display = '';
                    });
                    document.querySelectorAll('.sidebar-group').forEach(g => {
                        g.style.display = '';
                    });
                    return;
                }

                clearBtn.classList.remove('d-none');
                const matches = menuLinks.filter(m => 
                    m.text.toLowerCase().includes(q) || 
                    m.groupTitle.toLowerCase().includes(q) ||
                    m.href.toLowerCase().includes(q)
                );

                // Highlight & Filter in Sidebar
                menuLinks.forEach(m => {
                    const isMatch = m.text.toLowerCase().includes(q) || m.groupTitle.toLowerCase().includes(q);
                    m.element.style.display = isMatch ? '' : 'none';
                });

                document.querySelectorAll('.sidebar-group').forEach(grp => {
                    const hasVisibleLinks = grp.querySelectorAll('.sidebar-sublink:not([style*="display: none"])').length > 0;
                    grp.style.display = hasVisibleLinks ? '' : 'none';
                    if (hasVisibleLinks) grp.classList.add('open');
                });

                // Build Spotlight Dropdown
                if (matches.length > 0) {
                    let html = `<div class="px-2 py-1 text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase;">ผลการค้นหา (${matches.length} เมนู)</div>`;
                    matches.forEach(m => {
                        html += `
                            <a href="${m.href}" class="spotlight-item">
                                <div style="width: 28px; height: 28px; border-radius: 6px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">
                                    <i class="${m.icon}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 0.86rem; color: #0f172a;">${m.text}</div>
                                    <div style="font-size: 0.72rem; color: #64748b;">${m.groupTitle}</div>
                                </div>
                            </a>
                        `;
                    });
                    dropdown.innerHTML = html;
                    dropdown.classList.add('show');
                } else {
                    dropdown.innerHTML = `<div class="p-3 text-center text-muted" style="font-size: 0.85rem;"><i class="fa-solid fa-magnifying-glass me-1"></i> ไม่พบเมนูที่ตรงกับ "${q}"</div>`;
                    dropdown.classList.add('show');
                }
            });

            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                searchInput.focus();
            });

            // Close spotlight when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

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

    <!-- Google Fonts Preconnect (High-Speed Non-blocking) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Design System -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css?v=' . time()) ?>">
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
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #2563eb; display: flex; align-items: center; justify-content: center; color: #fff;">
                        <i class="fa-solid fa-layer-group" style="font-size: 1.1rem;"></i>
                    </div>
                <?php endif; ?>
                <div class="d-flex flex-column">
                    <span style="font-size: 1.05rem; font-weight: 700; color: #ffffff; line-height: 1.2;">PHATTHALUNG</span>
                    <span style="font-size: 0.72rem; font-weight: 600; color: #60a5fa; letter-spacing: 0.08em;">ADMIN PORTAL</span>
                </div>
            </a>

            <!-- User Profile Snippet Card -->
            <div class="sidebar-profile">
                <div class="avatar-badge"><?= session()->get('avatar_initials') ?? 'AD' ?></div>
                <div style="overflow: hidden; flex: 1;">
                    <h6 class="mb-0 fw-bold" style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                        <?= session()->get('full_name') ?? 'ผู้ดูแลระบบ' ?>
                    </h6>
                    <small style="color: #94a3b8; font-size: 0.75rem; display: flex; align-items: center;">
                        <span class="status-dot"></span> <?= session()->get('role') === 'admin' ? 'Super Admin' : 'Officer' ?>
                    </small>
                </div>
            </div>

            <!-- Sidebar Menu Items -->
            <ul class="sidebar-menu-list">
                <div class="sidebar-menu-title">ภาพรวมระบบ (OVERVIEW)</div>
                <li>
                    <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>แผงควบคุมหลัก</span>
                    </a>
                </li>

                <div class="sidebar-menu-title">บริหารจัดการข้อมูล (CONTENT)</div>
                <li>
                    <a href="<?= base_url('news') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'news' ? 'active' : '' ?>" title="ไปจัดการข่าวสารที่หน้าเว็บสาธารณะ">
                        <i class="fa-solid fa-newspaper text-warning"></i>
                        <span>ข่าวประชาสัมพันธ์</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/news-aggregator') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'news_aggregator' ? 'active' : '' ?>">
                        <i class="fa-solid fa-satellite-dish text-danger"></i>
                        <span>ระบบดึงข่าวอัตโนมัติ (Live Feeds)</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/pages') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'page_manager' ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-lines text-info"></i>
                        <span>หน้าเว็บไซต์ (Static Pages)</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/service-banners') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'services' ? 'active' : '' ?>">
                        <i class="fa-solid fa-bullhorn text-success"></i>
                        <span>แบนเนอร์ประชาสัมพันธ์ & ลิงก์ภายนอก</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/executives') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'executive_manager' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-tie text-warning"></i>
                        <span>คณะผู้บริหารปัจจุบัน</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/governors') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'governors' ? 'active' : '' ?>">
                        <i class="fa-solid fa-crown text-warning"></i>
                        <span>ทำเนียบผู้ว่าราชการจังหวัด</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/videos') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'videos' ? 'active' : '' ?>">
                        <i class="fa-solid fa-film text-danger" style="color: #f43f5e;"></i>
                        <span>วีดิทัศน์ Web TV & YouTube</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/strategy') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'strategy_manager' ? 'active' : '' ?>">
                        <i class="fa-solid fa-bullseye text-warning"></i>
                        <span>ยุทธศาสตร์ & แผนพัฒนาจังหวัด</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/projects') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'project_manager' ? 'active' : '' ?>">
                        <i class="fa-solid fa-map-location-dot" style="color: #06b6d4;"></i>
                        <span>แผนที่ GIS โครงการ & eMENSCR</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/banners') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'banners' ? 'active' : '' ?>">
                        <i class="fa-solid fa-images text-purple" style="color: #a855f7;"></i>
                        <span>แบนเนอร์ & เลย์เอาต์เว็บ</span>
                    </a>
                </li>

                <div class="sidebar-menu-title">การตั้งค่าระบบ (SETTINGS)</div>
                <li>
                    <a href="<?= base_url('admin/menu') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'menu_manager' ? 'active' : '' ?>">
                        <i class="fa-solid fa-compass text-teal" style="color: #14b8a6;"></i>
                        <span>จัดการเมนูบาร์เว็บ</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/settings') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'settings' ? 'active' : '' ?>">
                        <i class="fa-solid fa-sliders text-indigo" style="color: #818cf8;"></i>
                        <span>ตั้งค่าระบบเว็บไซต์</span>
                    </a>
                </li>
                <li>
                    <a href="#users" onclick="App.toast('ระบบจัดการสิทธิ์เจ้าหน้าที่อยู่ในแผนอัปเดตถัดไป', 'info')" class="sidebar-link">
                        <i class="fa-solid fa-users-gear text-slate" style="color: #94a3b8;"></i>
                        <span>เจ้าหน้าที่ระบบ</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="d-flex flex-column gap-2">
                    <a href="<?= base_url() ?>" target="_blank" class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="background: rgba(255, 255, 255, 0.08); color: #e2e8f0; font-size: 0.85rem; border-radius: 8px; padding: 0.45rem 1rem;">
                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.8rem;"></i> <span>ดูหน้าเว็บประชาชน</span>
                    </a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none text-danger" style="background: rgba(239, 68, 68, 0.12); font-size: 0.85rem; border-radius: 8px; padding: 0.45rem 1rem; font-weight: 600;">
                        <i class="fa-solid fa-power-off"></i> <span>ออกจากระบบ</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Clean Topbar -->
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm p-1 d-lg-none" id="toggleSidebarBtn" style="color: #475569; font-size: 1.25rem; border: none; background: transparent;">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    
                    <div class="d-none d-md-flex align-items-center admin-search-box">
                        <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8; font-size: 0.85rem;"></i>
                        <input type="text" placeholder="ค้นหาเมนูหรือฟังก์ชัน...">
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

    <!-- Bootstrap 5.3 & App JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script>
        // Mobile Sidebar Toggle Script
        document.getElementById('toggleSidebarBtn')?.addEventListener('click', function() {
            document.getElementById('adminSidebar').classList.toggle('show');
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ระบบบริหารจัดการหลังบ้าน | Phatthalung Admin Portal' ?></title>
    
    <!-- CSRF Meta -->
    <meta name="X-CSRF-HEADER" content="<?= csrf_header() ?>">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    
    <!-- Bootstrap 5.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Design System -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
</head>
<body>
    <div class="ambient-glow"></div>
    <div class="ambient-glow-2"></div>

    <div class="admin-wrapper">
        <!-- Ultra-Modern Glassmorphic Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-header">
                <?php $adminLogo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                <?php if (!empty($adminLogo)): ?>
                    <img src="<?= htmlspecialchars($adminLogo) ?>" alt="Logo" style="height: 38px; width: auto; max-width: 45px; object-fit: contain;">
                <?php else: ?>
                    <i class="fa-solid fa-square-poll-vertical" style="color: var(--accent-primary); font-size: 1.8rem;"></i>
                <?php endif; ?>
                <span>PHATTHALUNG <span class="gradient-text">ADMIN</span></span>
            </a>

            <!-- User Profile Snippet Card -->
            <div class="sidebar-profile">
                <div class="avatar-badge"><?= session()->get('avatar_initials') ?? 'AD' ?></div>
                <div style="overflow: hidden;">
                    <h6 class="mb-0 fw-bold" style="font-size: 0.95rem; color: var(--text-primary); text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                        <?= session()->get('full_name') ?? 'ผู้ดูแลระบบสูงสุด' ?>
                    </h6>
                    <small style="color: var(--accent-success); font-size: 0.75rem;">
                        <i class="fa-solid fa-circle me-1" style="font-size: 0.55rem;"></i> <?= session()->get('role') === 'admin' ? 'Admin Authority' : 'Officer Ready' ?>
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
                    <a href="#news" onclick="App.toast('เปิดระบบจัดการข่าวสารและกิจกรรม', 'info')" class="sidebar-link <?= ($activeMenu ?? '') === 'news' ? 'active' : '' ?>">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>ข่าวประชาสัมพันธ์</span>
                    </a>
                </li>
                <li>
                    <a href="#services" onclick="App.toast('เปิดระบบจัดการบริการออนไลน์ประชาชน', 'info')" class="sidebar-link <?= ($activeMenu ?? '') === 'services' ? 'active' : '' ?>">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                        <span>บริการประชาชน</span>
                    </a>
                </li>
                <li>
                    <a href="#banners" onclick="App.toast('เปิดระบบจัดการแบนเนอร์และภาพโฆษณา', 'info')" class="sidebar-link">
                        <i class="fa-solid fa-images"></i>
                        <span>แบนเนอร์ / หน้าเว็บ</span>
                    </a>
                </li>

                <div class="sidebar-menu-title">ความปลอดภัย & การตั้งค่า (ADMINISTRATIVE)</div>
                <li>
                    <a href="#users" onclick="App.toast('เปิดระบบจัดการเจ้าหน้าที่และผู้ดูแล', 'info')" class="sidebar-link">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>เจ้าหน้าที่ระบบ</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/settings') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'settings' ? 'active' : '' ?>">
                        <i class="fa-solid fa-sliders"></i>
                        <span>ตั้งค่าระบบเว็บไซต์</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/menu') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'menu_manager' ? 'active' : '' ?>">
                        <i class="fa-solid fa-compass"></i>
                        <span>จัดการเมนูบาร์เว็บ</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="d-flex flex-column gap-2">
                    <a href="<?= base_url('logout') ?>" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="font-size: 0.9rem; padding: 0.55rem 1rem; border-radius: var(--radius-sm); font-weight: 600;">
                        <i class="fa-solid fa-power-off"></i> <span>ออกจากระบบ (Logout)</span>
                    </a>
                    <a href="<?= base_url() ?>" class="btn-modern-outline w-100 d-flex align-items-center justify-content-center gap-2 text-decoration-none" style="font-size: 0.85rem; padding: 0.45rem 1rem;">
                        <i class="fa-solid fa-house"></i> <span>หน้าเว็บประชาชน</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Glass Topbar -->
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-link p-0 d-lg-none" id="toggleSidebarBtn" style="color: var(--text-primary); font-size: 1.5rem;">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                    
                    <div class="d-none d-md-flex align-items-center px-3 py-1" style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: var(--radius-full); width: 280px;">
                        <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted); font-size: 0.9rem;"></i>
                        <input type="text" placeholder="ค้นหาเมนูในระบบ..." style="border: none; background: transparent; color: var(--text-primary); outline: none; margin-left: 0.5rem; font-size: 0.9rem; width: 100%;">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Notification Bell with pulse effect -->
                    <button class="theme-toggle-btn position-relative" title="การแจ้งเตือน" onclick="App.toast('ยังไม่มีคำร้องใหม่จากระบบบริการประชาชน', 'info')">
                        <i class="fa-regular fa-bell" style="font-size: 1.15rem;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                    </button>

                    <!-- Theme Switcher Button -->
                    <button id="theme-toggle" class="theme-toggle-btn" title="สลับโหมดกลางวัน/กลางคืน">
                        <i class="fa-solid fa-moon text-indigo-500"></i>
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

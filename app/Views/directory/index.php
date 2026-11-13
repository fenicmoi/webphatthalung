<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .directory-hero {
        background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0f766e 100%);
        position: relative;
        overflow: hidden;
        border-radius: 0 0 1.75rem 1.75rem;
        padding: 1.5rem 0 2.25rem 0;
    }
    .directory-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 20%, rgba(52, 211, 153, 0.18) 0%, transparent 60%);
        pointer-events: none;
    }
    .dir-stat-badge {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #fff;
        padding: 0.25rem 0.75rem;
        border-radius: 50rem;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .filter-card {
        background: #ffffff;
        border: 1px solid rgba(16, 185, 129, 0.15);
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px -10px rgba(6, 78, 59, 0.08);
        margin-top: -1.75rem;
        position: relative;
        z-index: 10;
    }
    @media (max-width: 768px) {
        .directory-hero {
            border-radius: 0 0 1.25rem 1.25rem;
            padding: 1.1rem 0 1.85rem 0;
        }
        .dir-stat-badge {
            font-size: 0.72rem;
            padding: 0.2rem 0.55rem;
        }
        .filter-card {
            margin-top: -1.25rem;
            border-radius: 1rem;
            padding: 1rem !important;
        }
    }
    .contact-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        position: relative;
    }
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(6, 78, 59, 0.1), 0 8px 10px -6px rgba(6, 78, 59, 0.06);
        border-color: #10b981;
    }
    .contact-photo-wrapper {
        width: 100px;
        height: 120px;
        border-radius: 1rem;
        overflow: hidden;
        background: #f1f5f9;
        flex-shrink: 0;
        border: 2px solid #e2e8f0;
    }
    .contact-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .dept-badge {
        font-size: 0.75rem;
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        padding: 0.25rem 0.6rem;
        border-radius: 50rem;
        display: inline-block;
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .view-btn.active {
        background-color: #059669 !important;
        color: #ffffff !important;
        border-color: #059669 !important;
    }
    .contact-avatar-btn {
        cursor: pointer;
        transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.22s, border-color 0.22s;
        position: relative;
    }
    .contact-avatar-btn:hover {
        transform: scale(1.15);
        box-shadow: 0 6px 16px rgba(5, 150, 105, 0.35);
        border-color: #059669 !important;
        z-index: 5;
    }
    .contact-avatar-btn::after {
        content: '\f002';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        inset: 0;
        background: rgba(4, 120, 87, 0.55);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        opacity: 0;
        transition: opacity 0.2s ease;
        border-radius: inherit;
        pointer-events: none;
    }
    .contact-avatar-btn:hover::after {
        opacity: 1;
    }
    .text-hover-success {
        transition: color 0.15s ease;
    }
    .text-hover-success:hover {
        color: #059669 !important;
        text-decoration: underline;
    }
</style>

<!-- Hero Section (Minimal, Clean & Fast) -->
<section class="directory-hero text-white">
    <div class="container text-center position-relative">
        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 mb-2 fw-semibold" style="font-size: 0.78rem;">
            <i class="fa-solid fa-address-book text-warning me-1"></i> สมุดโทรศัพท์หน่วยงานภาครัฐ & ทำเนียบผู้บริหาร
        </span>
        <h1 class="fw-bold mb-2 tracking-tight text-white" style="font-size: clamp(1.35rem, 2.5vw, 1.85rem);">ทำเนียบข้าราชการและส่วนราชการ</h1>

        <!-- Quick Install Button (iOS & Android) -->
        <div class="d-flex justify-content-center align-items-center pt-1">
            <button type="button" class="btn btn-warning btn-sm text-dark fw-bold rounded-pill px-3.5 py-1.5 shadow-xs d-inline-flex align-items-center gap-1.5 btn-pwa-install" style="font-size: 0.78rem;" onclick="triggerPwaInstall()" title="ติดตั้งแอปพัทลุงโฟนลงมือถือ (iOS & Android)">
                <i class="fa-brands fa-apple text-dark"></i><i class="fa-brands fa-android text-success"></i> <span>ติดตั้งแอปพัทลุงโฟน</span>
            </button>
        </div>
    </div>
</section>

<!-- Filter & Search Bar -->
<div class="container mb-5">
    <div class="filter-card p-4">
        <form method="get" action="<?= base_url('directory') ?>" id="directoryFilterForm">
            <input type="hidden" name="view" id="viewModeInput" value="<?= esc($viewMode) ?>">

            <div class="row g-3 align-items-center">
                <!-- Search Box -->
                <div class="col-lg-4 col-md-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fa-solid fa-magnifying-glass text-success"></i>
                        </span>
                        <input type="text" name="search" id="directorySearchInput" class="form-control border-start-0 ps-0" 
                               placeholder="ค้นหาชื่อ, ตำแหน่ง, หรือหน่วยงาน..." 
                               value="<?= esc($search) ?>">
                        <?php if ($search): ?>
                            <a href="<?= base_url('directory?view=' . $viewMode) ?>" class="input-group-text bg-white text-muted text-decoration-none" title="ล้างการค้นหา">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ministry Filter -->
                <div class="col-lg-3 col-md-6">
                    <select name="ministry" class="form-select" onchange="document.getElementById('directoryFilterForm').submit();">
                        <option value="">-- ทุกสังกัด/กระทรวง (<?= count($ministries) ?>) --</option>
                        <?php foreach ($ministries as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $selectedMinistry === (int)$m['id'] ? 'selected' : '' ?>>
                                <?= esc($m['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Department Filter -->
                <div class="col-lg-3 col-md-6">
                    <select name="dept" class="form-select" onchange="document.getElementById('directoryFilterForm').submit();">
                        <option value="">-- ทุกหน่วยงานย่อย --</option>
                        <?php foreach ($departments as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $selectedDept === (int)$d['id'] ? 'selected' : '' ?>>
                                <?= esc($d['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="col-lg-2 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100 fw-semibold rounded-3">
                        <i class="fa-solid fa-filter me-1"></i> กรองข้อมูล
                    </button>
                </div>
            </div>

            <!-- Toolbar: Results Count, View Switcher & Export -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top">
                <div class="text-muted small">
                    พบผลการค้นหา: <strong class="text-dark"><?= number_format($totalResults) ?></strong> รายการ 
                    <?php if ($search || $selectedMinistry || $selectedDept): ?>
                        <span class="text-emerald">(จากทั้งหมด <?= number_format($totalAllContacts) ?> รายการ)</span>
                    <?php endif; ?>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
                    <!-- Export Actions -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-download me-1 text-primary"></i> ส่งออกข้อมูล
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li>
                                <a class="dropdown-item py-2" href="<?= base_url('directory/export-excel' . ($selectedMinistry ? '?ministry=' . $selectedMinistry : '')) ?>">
                                    <i class="fa-solid fa-file-excel text-success me-2"></i> ดาวน์โหลดไฟล์ Excel (CSV)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="<?= base_url('directory/export-vcf' . ($selectedMinistry ? '?ministry=' . $selectedMinistry : '')) ?>">
                                    <i class="fa-solid fa-address-card text-info me-2"></i> ส่งออก vCard (บันทึกลงมือถือ)
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- PWA Mobile App Quick Install Button -->
                    <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-bold rounded-pill px-3 d-inline-flex align-items-center gap-1.5 btn-pwa-install shadow-xs" onclick="triggerPwaInstall()" title="ติดตั้งแอปพัทลุงโฟนลงมือถือ (Android)">
                        <i class="fa-brands fa-android text-success"></i> ติดตั้งแอป
                    </button>

                    <!-- View Switcher (Table View is Primary) -->
                    <div class="btn-group" role="group">
                        <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['view' => 'table'])) ?>" 
                           class="btn btn-sm btn-outline-success view-btn <?= $viewMode === 'table' ? 'active' : '' ?>" title="โหมดตารางทำเนียบข้าราชการ (Table View)">
                            <i class="fa-solid fa-table-list me-1"></i> โหมดตารางทำเนียบ
                        </a>
                        <a href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['view' => 'cards'])) ?>" 
                           class="btn btn-sm btn-outline-success view-btn <?= $viewMode === 'cards' ? 'active' : '' ?>" title="โหมดนามบัตร (Card View)">
                            <i class="fa-solid fa-grip me-1"></i> โหมดนามบัตร
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Content Area -->
    <?php if (empty($contacts)): ?>
        <div class="text-center py-5 my-5 bg-white rounded-4 border p-4 shadow-sm">
            <div class="avatar-lg bg-emerald-subtle text-emerald rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3" style="width: 80px; height: 80px;">
                <i class="fa-solid fa-user-slash fs-1 text-muted"></i>
            </div>
            <h4 class="fw-bold text-dark">ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</h4>
            <p class="text-muted mb-4">ลองปรับเปลี่ยนคำค้นหา หรือเลือกสังกัด/กระทรวงอื่น</p>
            <a href="<?= base_url('directory') ?>" class="btn btn-emerald rounded-pill px-4">
                <i class="fa-solid fa-rotate-left me-1"></i> ล้างการค้นหาทั้งหมด
            </a>
        </div>
    <?php else: ?>

        <?php if ($viewMode === 'table'): ?>
            <!-- ===================== DESKTOP TABLE VIEW (>= 768px) ===================== -->
            <div class="d-none d-md-block bg-white rounded-4 border shadow-sm overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase" style="border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th style="width: 65px;" class="text-center">ลำดับ</th>
                                <th style="width: 70px;" class="text-center">รูปถ่าย</th>
                                <th style="min-width: 180px;">ยศ - ชื่อ - สกุล</th>
                                <th style="min-width: 200px;">ตำแหน่งหน้าที่</th>
                                <th style="min-width: 170px;">ส่วนราชการ / สังกัด</th>
                                <th style="min-width: 130px;">เบอร์โทรศัพท์โต๊ะ</th>
                                <th style="min-width: 120px;">เบอร์มือถือ</th>
                                <th style="width: 110px;" class="text-center">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $currentDeptId = null;
                                foreach ($contacts as $idx => $c): 
                                    $rowNum = ($offset ?? 0) + $idx + 1;
                            ?>
                                <?php if ($currentDeptId !== $c['department_id']): ?>
                                    <?php $currentDeptId = $c['department_id']; ?>
                                    <tr style="background: linear-gradient(90deg, #ecfdf5 0%, #f8fafc 100%); border-left: 4px solid #059669;">
                                        <td colspan="8" class="py-2.5 px-3">
                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-success rounded-pill px-3 py-1.5 fw-semibold shadow-xs" style="font-size: 0.85rem;">
                                                        <i class="fa-solid fa-building me-1.5"></i> <?= esc($c['department_name']) ?>
                                                    </span>
                                                    <span class="text-secondary small fw-medium">
                                                        <i class="fa-solid fa-landmark me-1 opacity-75"></i> สังกัด<?= esc($c['ministry_name']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <tr>
                                    <td class="text-center">
                                        <?php if ((int)($c['order'] ?? 99) === 1): ?>
                                            <span class="badge bg-warning text-dark border border-warning-subtle rounded-pill px-2" title="หัวหน้าส่วนราชการ (ลำดับชั้นยศที่ 1)">
                                                <?= $rowNum ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small fw-medium"><?= $rowNum ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="contact-avatar-btn" 
                                             role="button"
                                             tabindex="0"
                                             style="width: 48px; height: 58px; border-radius: 8px; overflow: hidden; background: #f1f5f9; border: 1.5px solid #cbd5e1; margin: 0 auto;"
                                             onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                             onkeydown="if(event.key==='Enter'||event.key===' ') showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                             title="คลิกที่รูปเพื่อดูรายละเอียดและเบอร์โทร: <?= esc($c['name']) ?>">
                                            <img src="<?= $c['photo_url'] ?: base_url('assets/images/user-placeholder.png') ?>" 
                                                 alt="<?= esc($c['name']) ?>" 
                                                 class="w-100 h-100 object-fit-cover"
                                                 onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($c['name']) ?>&background=047857&color=fff';">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block text-hover-success" 
                                              role="button"
                                              tabindex="0"
                                              style="cursor: pointer;"
                                              onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                              onkeydown="if(event.key==='Enter'||event.key===' ') showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                              title="คลิกเพื่อดูรายละเอียด: <?= esc($c['name']) ?>">
                                            <?= esc($c['name']) ?>
                                        </span>
                                        <?php if (!empty($c['address'])): ?>
                                            <small class="text-muted d-block" style="font-size: 0.78rem;">
                                                <i class="fa-solid fa-location-dot me-1 text-danger opacity-75"></i><?= esc($c['address']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-semibold small d-block"><?= esc($c['position']) ?></span>
                                        <?php if (!empty($c['hotline'])): ?>
                                            <span class="badge bg-light text-dark border small mt-1">
                                                ภายใน: <?= esc($c['hotline']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="dept-badge text-truncate d-block mb-1" title="<?= esc($c['department_name']) ?>">
                                            <?= esc($c['department_name']) ?>
                                        </span>
                                        <small class="text-muted d-block" style="font-size: 0.8rem;"><?= esc($c['ministry_name']) ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($c['phone'])): ?>
                                            <a href="tel:<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>" class="btn btn-sm btn-outline-success py-1 px-2.5 rounded-pill text-nowrap d-inline-flex align-items-center gap-1 shadow-xs" style="font-size: 0.82rem;">
                                                <i class="fa-solid fa-phone small"></i> <?= esc($c['phone']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($c['mobile'])): ?>
                                            <a href="tel:<?= preg_replace('/[^0-9]/', '', $c['mobile']) ?>" class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-pill text-nowrap d-inline-flex align-items-center gap-1 shadow-xs" style="font-size: 0.82rem;">
                                                <i class="fa-solid fa-mobile-screen small"></i> <?= esc($c['mobile']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5 py-1 text-nowrap" 
                                                onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                                title="ดูข้อมูลเพิ่มเติม & QR Code">
                                            <i class="fa-solid fa-id-card text-success me-1"></i>ดูข้อมูล
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===================== MOBILE CONTACT LIST (< 768px) ===================== -->
            <!-- ลดทอนรายละเอียด และสามารถกดโทรได้ทันทีโดยไม่ต้องเลื่อนจอไปทางซ้าย -->
            <div class="d-block d-md-none mb-4">
                <?php 
                    $currMobDeptId = null;
                    foreach ($contacts as $idx => $c): 
                        $phoneClean = !empty($c['phone']) ? preg_replace('/[^0-9]/', '', $c['phone']) : '';
                        $mobileClean = !empty($c['mobile']) ? preg_replace('/[^0-9]/', '', $c['mobile']) : '';
                        $hasPhone = !empty($phoneClean) || !empty($mobileClean);
                ?>
                    <?php if ($currentDeptId !== $c['department_id'] && $currMobDeptId !== $c['department_id']): ?>
                        <?php $currMobDeptId = $c['department_id']; ?>
                        <div class="px-3 py-2 mt-3 mb-2 rounded-3 bg-white border-start border-4 border-success shadow-xs d-flex align-items-center justify-content-between">
                            <span class="fw-bold text-dark small text-truncate" title="<?= esc($c['department_name']) ?>">
                                <i class="fa-solid fa-building text-success me-1"></i> <?= esc($c['department_name']) ?>
                            </span>
                            <span class="badge bg-light text-secondary border small text-nowrap" style="font-size: 0.68rem;">
                                <?= esc($c['ministry_name']) ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="card border-0 shadow-xs rounded-3 mb-2 p-2.5 bg-white position-relative" style="border: 1px solid rgba(15,23,42,0.08) !important;">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <!-- Left: Avatar & Info (แตะเพื่อเปิดดูข้อมูลฉบับเต็ม) -->
                            <div class="d-flex align-items-center gap-2.5 min-w-0 flex-grow-1" style="cursor: pointer;" onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)">
                                <div style="width: 44px; height: 52px; border-radius: 8px; overflow: hidden; background: #f1f5f9; border: 1.5px solid #e2e8f0; flex-shrink: 0;">
                                    <img src="<?= $c['photo_url'] ?: base_url('assets/images/user-placeholder.png') ?>" 
                                         alt="<?= esc($c['name']) ?>" 
                                         class="w-100 h-100 object-fit-cover"
                                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($c['name']) ?>&background=047857&color=fff';">
                                </div>
                                <div class="min-w-0 flex-grow-1">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.92rem; line-height: 1.3;">
                                        <?= esc($c['name']) ?>
                                    </div>
                                    <div class="text-success small fw-medium text-truncate mt-0.5" style="font-size: 0.78rem;">
                                        <?= esc($c['position']) ?>
                                    </div>
                                    <?php if (!empty($c['hotline'])): ?>
                                        <span class="badge bg-light text-muted border px-1.5 py-0.5 mt-1" style="font-size: 0.68rem;">
                                            ภายใน: <?= esc($c['hotline']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Right: Instant One-Tap Call Buttons (แตะโทรออกได้เลยทันที ไม่ต้องเลื่อนจอ) -->
                            <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                                <?php if (!empty($mobileClean)): ?>
                                    <a href="tel:<?= $mobileClean ?>" 
                                       class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-xs" 
                                       style="width: 38px; height: 38px; background: linear-gradient(135deg, #2563eb, #1d4ed8); border: none;" 
                                       title="โทรเบอร์มือถือ: <?= esc($c['mobile']) ?>">
                                        <i class="fa-solid fa-mobile-screen fs-6 text-white"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($phoneClean)): ?>
                                    <a href="tel:<?= $phoneClean ?>" 
                                       class="btn btn-success rounded-circle d-flex align-items-center justify-content-center shadow-xs" 
                                       style="width: 38px; height: 38px; background: linear-gradient(135deg, #059669, #047857); border: none;" 
                                       title="โทรเบอร์สำนักงาน/โต๊ะ: <?= esc($c['phone']) ?>">
                                        <i class="fa-solid fa-phone fs-6 text-white"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if (!$hasPhone): ?>
                                    <button type="button" class="btn btn-light border rounded-circle text-muted d-flex align-items-center justify-content-center" 
                                            style="width: 38px; height: 38px;"
                                            onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                            title="ดูข้อมูลเพิ่มเติม">
                                        <i class="fa-solid fa-id-card text-secondary"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- ===================== CARDS VIEW ===================== -->
            <div class="row g-4 mb-4">
                <?php foreach ($contacts as $c): ?>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="contact-card p-3">
                            <div class="d-flex gap-3 align-items-start mb-3">
                                <!-- Photo -->
                                <div class="contact-photo-wrapper shadow-xs contact-avatar-btn" 
                                     role="button"
                                     tabindex="0"
                                     onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                     onkeydown="if(event.key==='Enter'||event.key===' ') showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                     title="คลิกที่รูปเพื่อดูรายละเอียดและเบอร์โทร: <?= esc($c['name']) ?>">
                                    <img src="<?= $c['photo_url'] ?: base_url('assets/images/user-placeholder.png') ?>" 
                                         alt="<?= esc($c['name']) ?>" 
                                         class="contact-photo"
                                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($c['name']) ?>&background=047857&color=fff';">
                                </div>

                                <!-- Text Content -->
                                <div class="flex-grow-1 min-w-0">
                                    <span class="dept-badge mb-1 text-truncate d-inline-block w-100" title="<?= esc($c['department_name']) ?>">
                                        <?= esc($c['department_name']) ?>
                                    </span>
                                    <h5 class="fw-bold text-dark mb-1 text-truncate text-hover-success" 
                                        role="button"
                                        tabindex="0"
                                        style="cursor: pointer;"
                                        onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                        onkeydown="if(event.key==='Enter'||event.key===' ') showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                        title="<?= esc($c['name']) ?> (คลิกเพื่อดูรายละเอียด)">
                                        <?= esc($c['name']) ?>
                                    </h5>
                                    <p class="text-success small fw-medium mb-1 line-clamp-2" style="min-height: 2.4rem;" title="<?= esc($c['position']) ?>">
                                        <?= esc($c['position']) ?>
                                    </p>
                                    <span class="text-muted small d-block">
                                        <i class="fa-solid fa-landmark me-1 text-secondary opacity-75"></i><?= esc($c['ministry_name']) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Phone Buttons Strip -->
                            <div class="mt-auto pt-2 border-top d-flex gap-1.5 flex-wrap">
                                <?php if (!empty($c['phone'])): ?>
                                    <a href="tel:<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>" 
                                       class="btn btn-sm btn-outline-success flex-grow-1 rounded-pill d-inline-flex align-items-center justify-content-center gap-1.5"
                                       title="โทรเบอร์สำนักงาน: <?= esc($c['phone']) ?>">
                                        <i class="fa-solid fa-phone small"></i> โทรโต๊ะ
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($c['mobile'])): ?>
                                    <a href="tel:<?= preg_replace('/[^0-9]/', '', $c['mobile']) ?>" 
                                       class="btn btn-sm btn-outline-primary flex-grow-1 rounded-pill d-inline-flex align-items-center justify-content-center gap-1.5"
                                       title="โทรเบอร์มือถือ: <?= esc($c['mobile']) ?>">
                                        <i class="fa-solid fa-mobile-screen small"></i> มือถือ
                                    </a>
                                <?php endif; ?>

                                <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5" 
                                        onclick="showContactModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                        title="ดูรายละเอียด & QR Code">
                                    <i class="fa-solid fa-qrcode text-emerald"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="d-flex justify-content-center my-5">
                <nav aria-label="Directory Pagination">
                    <ul class="pagination pagination-sm gap-1">
                        <!-- First -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link rounded-pill px-3" href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => 1])) ?>">
                                <i class="fa-solid fa-angles-left"></i>
                            </a>
                        </li>

                        <!-- Previous -->
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link rounded-pill px-3" href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => max(1, $currentPage - 1)])) ?>">
                                <i class="fa-solid fa-angle-left"></i>
                            </a>
                        </li>

                        <!-- Page Numbers (Show 5 around current) -->
                        <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                        ?>
                        <?php for ($p = $startPage; $p <= $endPage; $p++): ?>
                            <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                <a class="page-link rounded-circle text-center p-0 d-inline-flex align-items-center justify-content-center <?= $p === $currentPage ? 'bg-success border-success text-white' : '' ?>" 
                                   style="width: 32px; height: 32px;"
                                   href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => $p])) ?>">
                                    <?= $p ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next -->
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link rounded-pill px-3" href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => min($totalPages, $currentPage + 1)])) ?>">
                                <i class="fa-solid fa-angle-right"></i>
                            </a>
                        </li>

                        <!-- Last -->
                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link rounded-pill px-3" href="<?= current_url() . '?' . http_build_query(array_merge($_GET, ['page' => $totalPages])) ?>">
                                <i class="fa-solid fa-angles-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<!-- ===================== DETAIL & QR CODE MODAL ===================== -->
<div class="modal fade" id="contactDetailModal" tabindex="-1" aria-labelledby="contactDetailModalLabel" aria-hidden="true" onclick="if(event.target === this) closeContactModal();">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="contactDetailModalLabel">
                    <i class="fa-solid fa-id-badge me-2"></i> ข้อมูลการติดต่อส่วนบุคคล
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeContactModal()" style="cursor: pointer;"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="d-inline-block position-relative mb-3">
                    <img id="modalContactPhoto" src="" alt="" 
                         class="rounded-4 border shadow-sm object-fit-cover" 
                         style="width: 140px; height: 175px; cursor: pointer; transition: transform 0.2s;"
                         onclick="if(this.src && !this.src.includes('ui-avatars')) window.open(this.src, '_blank');"
                         title="คลิกเพื่อเปิดดูรูปขนาดเต็ม">
                    <span class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white rounded-pill px-2 py-0.5 m-1 shadow-xs" style="font-size: 0.68rem; pointer-events: none;">
                        <i class="fa-solid fa-up-right-and-down-left-from-center me-0.5"></i> รูปขนาดเต็ม
                    </span>
                </div>
                <h4 id="modalContactName" class="fw-bold text-dark mb-1"></h4>
                <p id="modalContactPosition" class="text-success fw-semibold mb-2"></p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span id="modalContactDept" class="badge bg-light text-dark border"></span>
                    <span id="modalContactMinistry" class="badge bg-emerald-subtle text-emerald border border-success-subtle"></span>
                </div>

                <!-- Info Table -->
                <div class="bg-light rounded-3 p-3 text-start small mb-3">
                    <div class="row mb-2" id="modalPhoneRow">
                        <div class="col-4 text-muted">โทรศัพท์โต๊ะ:</div>
                        <div class="col-8 fw-semibold" id="modalContactPhone"></div>
                    </div>
                    <div class="row mb-2" id="modalMobileRow">
                        <div class="col-4 text-muted">โทรศัพท์มือถือ:</div>
                        <div class="col-8 fw-semibold text-primary" id="modalContactMobile"></div>
                    </div>
                    <div class="row mb-2" id="modalHotlineRow">
                        <div class="col-4 text-muted">เบอร์ภายใน:</div>
                        <div class="col-8 fw-bold text-danger" id="modalContactHotline"></div>
                    </div>
                    <div class="row mb-2" id="modalAddressRow">
                        <div class="col-4 text-muted">ที่ตั้ง/ห้อง:</div>
                        <div class="col-8" id="modalContactAddress"></div>
                    </div>
                    <div class="row" id="modalUpdatedRow">
                        <div class="col-4 text-muted">ปรับปรุงล่าสุด:</div>
                        <div class="col-8 text-muted" id="modalContactUpdated"></div>
                    </div>
                </div>

                <!-- QR Code for Saving Contact -->
                <div class="border rounded-3 p-3 bg-white d-inline-block shadow-xs mb-3">
                    <div id="modalQrCodeImgContainer">
                        <img id="modalQrCodeImg" src="" alt="QR Code" style="width: 140px; height: 140px;" class="d-block mx-auto mb-1">
                    </div>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-qrcode me-1"></i> สแกนด้วยกล้องมือถือเพื่อบันทึก Contact ทันที
                    </small>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-center">
                    <a id="modalCallPhoneBtn" href="" class="btn btn-outline-success rounded-pill px-3 py-1.5 small">
                        <i class="fa-solid fa-phone me-1"></i> โทรโต๊ะ
                    </a>
                    <a id="modalCallMobileBtn" href="" class="btn btn-outline-primary rounded-pill px-3 py-1.5 small">
                        <i class="fa-solid fa-mobile-screen me-1"></i> โทรมือถือ
                    </a>
                    <button type="button" class="btn btn-light border rounded-pill px-3 py-1.5 small" onclick="copyContactText()">
                        <i class="fa-solid fa-copy me-1"></i> คัดลอก
                    </button>
                </div>
            </div>

            <!-- Modal Footer with prominent close button -->
            <div class="modal-footer bg-light border-top d-flex justify-content-between py-2 px-3">
                <small class="text-muted"><i class="fa-solid fa-address-book text-success me-1"></i> ทำเนียบข้าราชการพัทลุง</small>
                <button type="button" class="btn btn-secondary rounded-pill px-4 py-1.5 btn-sm fw-medium shadow-xs" onclick="closeContactModal()" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-1.5"></i> ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentModalContact = null;

    function showContactModal(contact) {
        currentModalContact = contact;
        document.getElementById('modalContactName').innerText = contact.name || '-';
        document.getElementById('modalContactPosition').innerText = contact.position || '-';
        document.getElementById('modalContactDept').innerText = contact.department_name || '-';
        document.getElementById('modalContactMinistry').innerText = contact.ministry_name || '-';
        
        // Photo
        const photoEl = document.getElementById('modalContactPhoto');
        photoEl.src = contact.photo_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(contact.name) + '&background=047857&color=fff');
        photoEl.onerror = function() {
            this.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(contact.name) + '&background=047857&color=fff';
        };

        // Phone numbers
        const phoneEl = document.getElementById('modalContactPhone');
        const phoneRow = document.getElementById('modalPhoneRow');
        const callPhoneBtn = document.getElementById('modalCallPhoneBtn');
        if (contact.phone) {
            phoneEl.innerText = contact.phone;
            phoneRow.style.display = 'flex';
            callPhoneBtn.href = 'tel:' + contact.phone.replace(/[^0-9]/g, '');
            callPhoneBtn.style.display = 'inline-flex';
        } else {
            phoneRow.style.display = 'none';
            callPhoneBtn.style.display = 'none';
        }

        const mobileEl = document.getElementById('modalContactMobile');
        const mobileRow = document.getElementById('modalMobileRow');
        const callMobileBtn = document.getElementById('modalCallMobileBtn');
        if (contact.mobile) {
            mobileEl.innerText = contact.mobile;
            mobileRow.style.display = 'flex';
            callMobileBtn.href = 'tel:' + contact.mobile.replace(/[^0-9]/g, '');
            callMobileBtn.style.display = 'inline-flex';
        } else {
            mobileRow.style.display = 'none';
            callMobileBtn.style.display = 'none';
        }

        // Hotline
        const hotlineRow = document.getElementById('modalHotlineRow');
        if (contact.hotline) {
            document.getElementById('modalContactHotline').innerText = contact.hotline;
            hotlineRow.style.display = 'flex';
        } else {
            hotlineRow.style.display = 'none';
        }

        // Address
        const addressRow = document.getElementById('modalAddressRow');
        if (contact.address) {
            document.getElementById('modalContactAddress').innerText = contact.address;
            addressRow.style.display = 'flex';
        } else {
            addressRow.style.display = 'none';
        }

        // Updated
        document.getElementById('modalContactUpdated').innerText = contact.updated_at || '-';

        // Dynamic vCard QR Code
        const vcardData = `BEGIN:VCARD\nVERSION:3.0\nFN:${contact.name}\nTITLE:${contact.position}\nORG:${contact.department_name}\nTEL:${contact.mobile || contact.phone}\nEND:VCARD`;
        const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + encodeURIComponent(vcardData);
        document.getElementById('modalQrCodeImg').src = qrUrl;

        const modalEl = document.getElementById('contactDetailModal');
        if (window.bootstrap && typeof bootstrap.Modal !== 'undefined') {
            try {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal._isTransitioning = false;
                modal.show();
                return;
            } catch(e) {
                console.warn('Bootstrap modal show exception:', e);
            }
        }
        
        // Manual fallback if bootstrap modal failed or is missing
        if (modalEl) {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.setAttribute('aria-hidden', 'false');
            let backdrop = document.querySelector('.public-modal-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show public-modal-backdrop';
                backdrop.onclick = closeContactModal;
                document.body.appendChild(backdrop);
            }
            document.body.classList.add('modal-open');
        }
    }

    function closeContactModal() {
        const modalEl = document.getElementById('contactDetailModal');
        if (modalEl) {
            if (window.bootstrap && typeof bootstrap.Modal !== 'undefined') {
                try {
                    const inst = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl);
                    if (inst) {
                        inst._isTransitioning = false;
                        inst.hide();
                    }
                } catch(e) {
                    console.warn('Bootstrap hide error:', e);
                }
            }
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            modalEl.setAttribute('aria-hidden', 'true');
            modalEl.removeAttribute('aria-modal');
        }

        // Remove any lingering backdrops and unlock page scroll
        document.querySelectorAll('.modal-backdrop, .public-modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.key === 'Esc') {
            const modalEl = document.getElementById('contactDetailModal');
            if (modalEl && (modalEl.classList.contains('show') || modalEl.style.display === 'block')) {
                closeContactModal();
            }
        }
    });

    function copyContactText() {
        if (!currentModalContact) return;
        const text = `${currentModalContact.name}\nตำแหน่ง: ${currentModalContact.position}\nหน่วยงาน: ${currentModalContact.department_name}\nเบอร์โต๊ะ: ${currentModalContact.phone || '-'}\nเบอร์มือถือ: ${currentModalContact.mobile || '-'}`;
        navigator.clipboard.writeText(text).then(() => {
            alert('คัดลอกข้อมูลการติดต่อเรียบร้อยแล้ว');
        }).catch(err => {
            prompt('คัดลอกข้อมูลด้านล่างนี้:', text);
        });
    }
</script>

<?= $this->endSection() ?>

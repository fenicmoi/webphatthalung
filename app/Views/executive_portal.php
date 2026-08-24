<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    $categories = $categories ?? get_executive_categories();
    $selectedCat = $selectedCat ?? 'all';
    $executives = $executives ?? [];
    $groupedByRow = $groupedByRow ?? [];
    $isOfficer = session()->get('isLoggedIn');
?>

<style>
/* ==========================================================================
   CURRENT EXECUTIVE LEADERSHIP HIERARCHY & PORTAL STYLES (MODERN CIRCULAR FRAME)
   ========================================================================== */
.exec-portal-header {
    background: linear-gradient(135deg, #0b2545 0%, #134074 50%, #002855 100%);
    position: relative;
    overflow: hidden;
    padding: 60px 0 45px;
    border-bottom: 3px solid rgba(212, 175, 55, 0.4);
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}
.exec-portal-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,215,0,0.06) 10%, transparent 40%);
    pointer-events: none;
}

.exec-tab-btn {
    border: 1px solid rgba(0, 0, 0, 0.08);
    background: #ffffff;
    color: #334155;
    border-radius: 50rem;
    padding: 10px 24px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.exec-tab-btn:hover {
    transform: translateY(-2px);
    background: #f8fafc;
    color: #b45309;
    box-shadow: 0 6px 16px rgba(180, 83, 9, 0.12);
}
.exec-tab-btn.active {
    background: linear-gradient(135deg, #b45309, #d97706);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 6px 20px rgba(180, 83, 9, 0.3);
}

.exec-tab-link-gov {
    background: #fffbeb;
    color: #92400e;
    border-color: #fde68a;
}
.exec-tab-link-gov:hover {
    background: #fef3c7;
    color: #78350f;
}

/* EXECUTIVE CARD & CIRCULAR FRAME STYLING */
.exec-profile-card {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.35s ease;
    max-width: 380px;
    width: 100%;
    padding: 20px 15px;
    border-radius: 20px;
    background: transparent;
    position: relative;
}
.exec-profile-card:hover {
    transform: translateY(-6px);
}

/* Modern Multi-Ring Luxury Frame */
.exec-frame-outer {
    position: relative;
    width: 230px;
    height: 230px;
    border-radius: 50%;
    padding: 7px;
    background: linear-gradient(135deg, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
    box-shadow: 0 12px 30px rgba(180, 83, 9, 0.22), 0 4px 10px rgba(0,0,0,0.1);
    transition: all 0.4s ease;
    margin-bottom: 24px;
}
.exec-profile-card:hover .exec-frame-outer {
    box-shadow: 0 18px 40px rgba(180, 83, 9, 0.35), 0 0 20px rgba(253, 224, 71, 0.4);
    transform: scale(1.03);
}

/* Governor Apex Size Enhancement */
.exec-row-1 .exec-frame-outer {
    width: 260px;
    height: 260px;
    padding: 9px;
    background: linear-gradient(135deg, #d4af37, #fff275, #aa771c, #ffd700, #996515);
    box-shadow: 0 16px 40px rgba(212, 175, 55, 0.35);
}

.exec-frame-inner {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
    background: #ffffff;
    border: 4px solid #ffffff;
    position: relative;
    box-shadow: inset 0 2px 8px rgba(0,0,0,0.15);
}
.exec-portrait-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: transform 0.45s ease;
}
.exec-profile-card:hover .exec-portrait-img {
    transform: scale(1.06);
}

/* Quick Download Badge on the Frame */
.exec-frame-download-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0284c7, #1d4ed8);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
    border: 2px solid #ffffff;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-decoration: none;
    z-index: 5;
}
.exec-frame-download-badge:hover {
    transform: scale(1.15) rotate(-10deg);
    background: linear-gradient(135deg, #d97706, #b45309);
    color: #ffffff;
}

/* Typography & Decorative Lines (Matches Provided Image Style) */
.exec-name-text {
    font-size: 1.35rem;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 6px;
    letter-spacing: 0.2px;
}
.exec-row-1 .exec-name-text {
    font-size: 1.55rem;
    color: #78350f;
}

.exec-ornament-line {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 180px;
    margin: 4px auto 10px;
}
.exec-ornament-line::before,
.exec-ornament-line::after {
    content: '';
    height: 2px;
    flex-grow: 1;
    background: linear-gradient(90deg, transparent, #d97706, transparent);
}
.exec-ornament-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #d97706;
}

.exec-position-text {
    font-size: 1.05rem;
    color: #475569;
    font-weight: 600;
    margin-bottom: 8px;
    line-height: 1.4;
}

.exec-contact-info {
    font-size: 0.95rem;
    color: #64748b;
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 14px;
}
.exec-contact-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

/* Action Buttons */
.btn-exec-detail {
    background: #f8fafc;
    border: 1px solid rgba(217, 119, 6, 0.3);
    color: #92400e;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 6px 16px;
    border-radius: 50rem;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-exec-detail:hover {
    background: linear-gradient(135deg, #b45309, #d97706);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(180, 83, 9, 0.25);
    transform: translateY(-1px);
}

.btn-exec-download {
    background: #ffffff;
    border: 1px solid rgba(2, 132, 199, 0.3);
    color: #0284c7;
    font-weight: 600;
    font-size: 0.88rem;
    padding: 6px 16px;
    border-radius: 50rem;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-exec-download:hover {
    background: linear-gradient(135deg, #0284c7, #0369a1);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    transform: translateY(-1px);
}

/* Row Separator / Tier Design */
.exec-row-wrapper {
    position: relative;
    width: 100%;
}
.exec-row-wrapper:not(:last-child)::after {
    content: '';
    display: block;
    width: 80px;
    height: 1px;
    background: rgba(217, 119, 6, 0.2);
    margin: 40px auto;
}

/* Dark Mode Adaptation */
[data-theme="dark"] .exec-tab-btn {
    background: #1e293b;
    color: #e2e8f0;
    border-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] .exec-name-text {
    color: #fde68a;
}
[data-theme="dark"] .exec-position-text {
    color: #cbd5e1;
}
[data-theme="dark"] .exec-contact-info {
    color: #94a3b8;
}
[data-theme="dark"] .btn-exec-detail {
    background: #1e293b;
    color: #fde68a;
    border-color: rgba(253, 230, 138, 0.3);
}
[data-theme="dark"] .btn-exec-detail:hover {
    background: linear-gradient(135deg, #b45309, #d97706);
    color: #ffffff;
}
[data-theme="dark"] .btn-exec-download {
    background: #1e293b;
    color: #38bdf8;
    border-color: rgba(56, 189, 248, 0.3);
}
[data-theme="dark"] .btn-exec-download:hover {
    background: linear-gradient(135deg, #0284c7, #0369a1);
    color: #ffffff;
}
</style>

<!-- HEADER BANNER -->
<header class="exec-portal-header text-white mb-5">
    <div class="container position-relative z-1">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 mb-3 text-warning fw-semibold">
                    <i class="fa-solid fa-crown"></i>
                    <span>Official Current Executive Leadership</span>
                </div>
                <h1 class="display-5 fw-bold mb-2">คณะผู้บริหารจังหวัดพัทลุง</h1>
                <p class="lead mb-0 text-light opacity-90">ทำเนียบผู้บริหารปัจจุบัน ผู้นำการขับเคลื่อนและพัฒนาจังหวัดพัทลุง</p>
            </div>
            <div class="col-lg-5 text-lg-end mt-4 mt-lg-0 d-flex flex-wrap justify-content-lg-end gap-2">
                <a href="<?= base_url() ?>" class="btn btn-outline-light rounded-pill px-3 py-2">
                    <i class="fa-solid fa-arrow-left me-1"></i> หน้าหลัก
                </a>
                <!-- Link to Hall of Governors -->
                <a href="<?= base_url('governors') ?>" class="btn btn-warning fw-bold text-dark rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2 hover-scale" title="ไปยังทำเนียบเจ้าเมืองและอดีตผู้ว่าราชการจังหวัดพัทลุง">
                    <i class="fa-solid fa-landmark text-primary"></i>
                    <span>ทำเนียบอดีตผู้ว่าราชการจังหวัด</span>
                </a>
                <?php if ($isOfficer): ?>
                <button type="button" onclick="ExecutiveStudio.open()" class="btn btn-light fw-bold text-primary rounded-pill px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2 hover-scale">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>+ เพิ่มรายนาม</span>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<div class="container my-5">
    <!-- FILTER & CATEGORY NAV -->
    <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
        <a href="<?= base_url('executives') ?>" class="exec-tab-btn <?= $selectedCat === 'all' ? 'active' : '' ?>">
            <i class="fa-solid fa-border-all"></i>
            <span>แสดงทั้งหมด</span>
        </a>
        <?php foreach ($categories as $catKey => $catInfo): ?>
            <?php $isAct = strcasecmp($selectedCat, $catInfo['name']) === 0; ?>
            <a href="<?= base_url('executives/category/' . urlencode($catInfo['name'])) ?>" class="exec-tab-btn <?= $isAct ? 'active' : '' ?>">
                <i class="<?= esc($catInfo['icon'] ?? 'fa-solid fa-folder') ?>"></i>
                <span><?= esc($catInfo['name']) ?></span>
            </a>
        <?php endforeach; ?>

        <!-- Quick Link Tab to Past Governors Archive -->
        <a href="<?= base_url('governors') ?>" class="exec-tab-btn exec-tab-link-gov" title="ดูทำเนียบอดีตผู้ว่าราชการจังหวัดพัทลุง">
            <i class="fa-solid fa-landmark text-warning"></i>
            <span>ทำเนียบอดีตผู้ว่าราชการจังหวัด <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.75rem;"></i></span>
        </a>
    </div>

    <?php if (empty($groupedByRow)): ?>
        <!-- EMPTY STATE -->
        <div class="text-center py-5 my-4">
            <div class="p-4 rounded-circle d-inline-block bg-warning bg-opacity-10 text-warning mb-3">
                <i class="fa-solid fa-user-slash fs-1 text-primary"></i>
            </div>
            <h4 class="fw-bold text-dark">ยังไม่มีข้อมูลรายนามผู้บริหารในหมวดหมู่นี้</h4>
            <p class="text-muted">กรุณาเลือกหมวดหมู่อื่น หรือเข้าสู่ระบบเพื่อเพิ่มรายนามผู้บริหารใหม่</p>
        </div>
    <?php else: ?>

        <!-- HIERARCHICAL ROW/COLUMN GRID PRESENTATION (CIRCULAR GOLDEN FRAMES) -->
        <div class="py-2">
            <?php foreach ($groupedByRow as $rowNumber => $rowMembers): ?>
                <div class="exec-row-wrapper exec-row-<?= (int)$rowNumber ?>">
                    <div class="d-flex flex-wrap justify-content-center align-items-start gap-4 gap-lg-5">
                        <?php foreach ($rowMembers as $item): ?>
                            <?php 
                                $photoSrc = !empty($item['photo']) ? (strpos($item['photo'], 'http') === 0 ? esc($item['photo']) : base_url($item['photo'])) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop';
                            ?>
                            <div class="exec-profile-card">
                                <!-- Circular Luxury Frame -->
                                <div class="exec-frame-outer">
                                    <div class="exec-frame-inner">
                                        <img src="<?= $photoSrc ?>" 
                                             alt="<?= esc($item['name']) ?>" 
                                             class="exec-portrait-img"
                                             loading="lazy">
                                    </div>
                                    <!-- Direct Download Button Badge -->
                                    <a href="<?= $photoSrc ?>" 
                                       download="<?= esc($item['name']) ?>.jpg" 
                                       target="_blank" 
                                       class="exec-frame-download-badge" 
                                       title="ดาวน์โหลดรูปภาพประจำตำแหน่ง <?= esc($item['name']) ?>">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </div>

                                <!-- Name -->
                                <h3 class="exec-name-text"><?= esc($item['name']) ?></h3>

                                <!-- Decorative Gold Accent Line with Dots -->
                                <div class="exec-ornament-line">
                                    <span class="exec-ornament-dot"></span>
                                    <span class="exec-ornament-dot"></span>
                                </div>

                                <!-- Official Position -->
                                <div class="exec-position-text"><?= esc($item['position']) ?></div>

                                <!-- Contact Info -->
                                <div class="exec-contact-info">
                                    <?php if (!empty($item['phone'])): ?>
                                        <div class="exec-contact-item">
                                            <span>โทรศัพท์ : <?= esc($item['phone']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($item['email'])): ?>
                                        <div class="exec-contact-item">
                                            <span>อีเมล : <?= esc($item['email']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Action Buttons: Biography & Download Photo -->
                                <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-2">
                                    <a href="<?= base_url('executives/detail/' . esc($item['id'])) ?>" class="btn-exec-detail shadow-sm">
                                        <i class="fa-solid fa-address-card"></i>
                                        <span>ประวัติการรับราชการ</span>
                                    </a>
                                    <a href="<?= $photoSrc ?>" 
                                       download="<?= esc($item['name']) ?>.jpg" 
                                       target="_blank" 
                                       class="btn-exec-download shadow-sm"
                                       title="ดาวน์โหลดไฟล์รูปถ่ายความละเอียดสูง">
                                        <i class="fa-solid fa-download"></i>
                                        <span>ดาวน์โหลดรูป</span>
                                    </a>
                                </div>

                                <!-- Officer Quick Edit / Delete Controls -->
                                <?php if ($isOfficer): ?>
                                    <div class="d-flex align-items-center justify-content-center gap-2 mt-2 pt-2 border-top w-100" style="border-color: rgba(0,0,0,0.06) !important;">
                                        <small class="text-muted me-1">R<?= (int)($item['row_num'] ?? 1) ?>:C<?= (int)($item['col_num'] ?? 1) ?></small>
                                        <button type="button" onclick="ExecutiveStudio.open('<?= $item['id'] ?>')" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 small">
                                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                        </button>
                                        <button type="button" onclick="ExecutiveStudio.deleteItem('<?= $item['id'] ?>', '<?= esc($item['name'], 'js') ?>')" class="btn btn-xs btn-outline-danger rounded-pill px-2 py-1 small">
                                            <i class="fa-solid fa-trash-can"></i> ลบ
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<!-- INCLUDE STUDIO MODAL COMPONENT -->
<?= $this->include('components/executive_studio') ?>

<?= $this->endSection() ?>

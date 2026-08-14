<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    $categories = $categories ?? get_executive_categories();
    $selectedCat = $selectedCat ?? 'all';
    $executives = $executives ?? [];
    $isOfficer = session()->get('isLoggedIn');

    // แยกผู้ว่าราชการจังหวัด (ลำดับ 1 หรือมีคำว่า ผู้ว่าราชการจังหวัด) ออกมาเดี่ยวๆ ถ้าดูหมวดทั้งหมดหรือคณะผู้บริหารระดับสูง
    $governor = null;
    $deputiesAndOthers = [];

    foreach ($executives as $ex) {
        if ($governor === null && (strpos($ex['position'] ?? '', 'ผู้ว่าราชการจังหวัด') !== false && strpos($ex['position'] ?? '', 'รอง') === false)) {
            $governor = $ex;
        } else {
            $deputiesAndOthers[] = $ex;
        }
    }
    // ถ้ารายการแรกสุดเป็นเบอร์ 1 และยังหาผู้ว่าฯ ไม่เจอ ให้อยู่ด้านบนสุด
    if ($governor === null && !empty($executives)) {
        $governor = $executives[0];
        array_shift($deputiesAndOthers);
    }
?>

<style>
/* ==========================================================================
   EXECUTIVE LEADERSHIP & VISION PORTAL STYLES
   ========================================================================== */
.exec-portal-header {
    background: linear-gradient(135deg, #0b4f6c 0%, #1e3a8a 50%, #0369a1 100%);
    position: relative;
    overflow: hidden;
    padding: 60px 0 45px;
    border-bottom: 3px solid rgba(255,215,0,0.4);
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
}
.exec-portal-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.08) 10%, transparent 40%);
    pointer-events: none;
    animation: rotateSlow 45s linear infinite;
}

.exec-tab-btn {
    border: 1px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.85);
    color: #1e293b;
    border-radius: 50rem;
    padding: 10px 24px;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.exec-tab-btn:hover {
    transform: translateY(-2px);
    background: #ffffff;
    box-shadow: 0 6px 20px rgba(0,102,204,0.15);
    color: #0284c7;
}
.exec-tab-btn.active {
    background: linear-gradient(135deg, #0284c7, #1d4ed8);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 6px 22px rgba(2, 132, 199, 0.35);
}

/* Governor Apex Card */
.governor-apex-card {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(2, 132, 199, 0.15);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}
.governor-apex-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 6px;
    background: linear-gradient(90deg, #f59e0b, #3b82f6, #10b981);
}
.governor-apex-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
}
.governor-img-container {
    position: relative;
    overflow: hidden;
    height: 100%;
    min-height: 420px;
    background: radial-gradient(circle, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.governor-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: transform 0.5s ease;
}
.governor-apex-card:hover .governor-img {
    transform: scale(1.03);
}
.governor-quote-box {
    background: rgba(2, 132, 199, 0.04);
    border-left: 5px solid #0284c7;
    padding: 24px 30px;
    border-radius: 0 16px 16px 0;
    font-size: 1.2rem;
    line-height: 1.8;
    color: #1e293b;
    position: relative;
    font-weight: 500;
}

/* Deputy & Executive Cards */
.exec-grid-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.exec-grid-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.12);
    border-color: rgba(2, 132, 199, 0.3);
}
.exec-img-box {
    width: 100%;
    height: 330px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(180deg, #f1f5f9 0%, #e2e8f0 100%);
}
.exec-grid-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top center;
    transition: transform 0.4s ease;
}
.exec-grid-card:hover .exec-grid-img {
    transform: scale(1.05);
}

/* Dark Mode Adaptation */
[data-theme="dark"] .governor-apex-card,
[data-theme="dark"] .exec-grid-card {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.1);
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.4);
}
[data-theme="dark"] .governor-quote-box {
    background: rgba(255, 255, 255, 0.03);
    color: #e2e8f0;
}
[data-theme="dark"] .exec-tab-btn {
    background: #0f172a;
    color: #cbd5e1;
    border-color: rgba(255, 255, 255, 0.15);
}
[data-theme="dark"] .exec-img-box,
[data-theme="dark"] .governor-img-container {
    background: #0f172a;
}
</style>

<!-- HEADER BANNER -->
<header class="exec-portal-header text-white mb-5">
    <div class="container position-relative z-1">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 mb-3 text-warning fw-semibold">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Official Institutional Directory</span>
                </div>
                <h1 class="display-5 fw-bold mb-2">ทำเนียบผู้บริหาร & วิสัยทัศน์จังหวัดพัทลุง</h1>
                <p class="lead mb-0 text-light opacity-90">ศูนย์รวบรวมรายนามผู้นำ คณะผู้บริหาร และหัวหน้าส่วนราชการในการขับเคลื่อนเมืองลุงสู่ความเจริญก้าวหน้าและยั่งยืน</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0 d-flex flex-wrap justify-content-lg-end gap-2">
                <a href="<?= base_url() ?>" class="btn btn-outline-light rounded-pill px-4 py-2">
                    <i class="fa-solid fa-arrow-left me-1"></i> หน้าหลัก
                </a>
                <?php if ($isOfficer): ?>
                <button type="button" onclick="ExecutiveStudio.open()" class="btn btn-warning fw-bold text-dark rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2 hover-scale">
                    <i class="fa-solid fa-user-plus text-primary"></i>
                    <span>+ เพิ่มรายนามผู้บริหาร</span>
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
            <span>แสดงทำเนียบทั้งหมด</span>
        </a>
        <?php foreach ($categories as $catKey => $catInfo): ?>
            <?php $isAct = strcasecmp($selectedCat, $catInfo['name']) === 0; ?>
            <a href="<?= base_url('executives/category/' . urlencode($catInfo['name'])) ?>" class="exec-tab-btn <?= $isAct ? 'active' : '' ?>">
                <i class="<?= esc($catInfo['icon'] ?? 'fa-solid fa-folder') ?>"></i>
                <span><?= esc($catInfo['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($executives)): ?>
        <!-- EMPTY STATE -->
        <div class="text-center py-5 my-4">
            <div class="p-4 rounded-circle d-inline-block bg-primary bg-opacity-10 text-primary mb-3">
                <i class="fa-solid fa-users-slash fs-1"></i>
            </div>
            <h4 class="fw-bold text-dark">ยังไม่มีรายนามในหมวดหมู่นี้</h4>
            <p class="text-muted">กรุณาเลือกดูหมวดหมู่อื่น หรือให้เจ้าหน้าที่อัปโหลดรายนามเข้าสู่ระบบ</p>
        </div>
    <?php else: ?>

        <!-- SECTION 1: APEX GOVERNOR (ผู้ว่าราชการจังหวัดพัทลุง) -->
        <?php if ($governor): ?>
            <div class="mb-5 pb-2">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold text-uppercase d-flex align-items-center gap-2">
                        <i class="fa-solid fa-star text-warning"></i> ผู้นำองค์กรสูงสุด
                    </span>
                    <h3 class="fw-bold mb-0 text-dark">ผู้ว่าราชการจังหวัดพัทลุง</h3>
                </div>

                <div class="governor-apex-card p-0">
                    <div class="row g-0">
                        <div class="col-lg-5 col-xl-4">
                            <div class="governor-img-container">
                                <img src="<?= !empty($governor['photo']) ? (strpos($governor['photo'], 'http') === 0 ? esc($governor['photo']) : base_url($governor['photo'])) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop' ?>" alt="<?= esc($governor['name']) ?>" class="governor-img">
                                <div class="position-absolute bottom-0 start-0 w-100 p-3 text-center text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);">
                                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 mb-1">
                                        <i class="fa-solid fa-crown me-1"></i> ลำดับที่ <?= esc($governor['order_num'] ?? 1) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-xl-8 p-4 p-md-5 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <span class="text-primary fw-bold fs-5"><?= esc($governor['position'] ?? 'ผู้ว่าราชการจังหวัดพัทลุง') ?></span>
                                    <?php if ($isOfficer): ?>
                                    <div class="d-flex gap-2">
                                        <button type="button" onclick="ExecutiveStudio.open('<?= $governor['id'] ?? '' ?>')" class="btn btn-sm btn-info text-dark fw-bold rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <h2 class="display-6 fw-bold mb-4 text-dark"><?= esc($governor['name']) ?></h2>

                                <?php if (!empty($governor['quote'])): ?>
                                <div class="governor-quote-box shadow-sm my-3">
                                    <i class="fa-solid fa-quote-left fs-3 text-primary opacity-25 float-start me-3"></i>
                                    <?= esc($governor['quote']) ?>
                                    <div class="mt-2 text-end text-muted small fw-normal">— วิสัยทัศน์ผู้ว่าราชการจังหวัดพัทลุง</div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mt-4 pt-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3 text-muted">
                                <div class="d-flex flex-wrap gap-4">
                                    <?php if (!empty($governor['phone'])): ?>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-phone-volume text-success fs-5"></i>
                                        <strong class="text-dark">โทรศัพท์สายตรง:</strong> <?= esc($governor['phone']) ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php if (!empty($governor['email'])): ?>
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-envelope text-danger fs-5"></i>
                                        <strong class="text-dark">อีเมลราชการ:</strong> <?= esc($governor['email']) ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <a href="javascript:void(0);" onclick="alert('ดาวน์โหลดภาพประจำตำแหน่งผู้ว่าราชการจังหวัดสำหรับสื่อมวลชน เรียบร้อย!');" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa-solid fa-download me-1"></i> ภาพทางการสำหรับสื่อ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECTION 2: DEPUTY GOVERNORS & DEPARTMENT HEADS (รองผู้ว่าฯ และ คณะผู้บริหาร) -->
        <?php if (!empty($deputiesAndOthers)): ?>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary px-3 py-2 rounded-pill fw-bold text-uppercase d-flex align-items-center gap-2">
                        <i class="fa-solid fa-users text-light"></i> คณะผู้บริหาร & หัวหน้าส่วน
                    </span>
                    <h4 class="fw-bold mb-0 text-dark">รายนามรองผู้ว่าราชการจังหวัด และ คณะผู้บริหาร</h4>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <?php foreach ($deputiesAndOthers as $item): ?>
                    <div class="col-md-6 col-lg-4 col-xl-4">
                        <div class="exec-grid-card position-relative">
                            <div class="exec-img-box">
                                <img src="<?= !empty($item['photo']) ? (strpos($item['photo'], 'http') === 0 ? esc($item['photo']) : base_url($item['photo'])) : 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop' ?>" alt="<?= esc($item['name']) ?>" class="exec-grid-img">
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="badge bg-dark bg-opacity-75 text-warning backdrop-blur border border-white border-opacity-25 shadow-sm">
                                        ลำดับที่ <?= esc($item['order_num'] ?? '-') ?>
                                    </span>
                                </div>
                                <?php if (!empty($item['featured'])): ?>
                                <div class="position-absolute top-0 start-0 p-3">
                                    <span class="badge bg-primary text-white shadow-sm">
                                        <i class="fa-solid fa-star me-1"></i> หน้าแรก
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <div class="text-primary fw-bold mb-1 small text-uppercase">
                                        <i class="fa-solid fa-id-badge me-1"></i> <?= esc($item['position'] ?? 'คณะผู้บริหาร') ?>
                                    </div>
                                    <h4 class="fw-bold mb-3 text-dark"><?= esc($item['name']) ?></h4>
                                    <?php if (!empty($item['quote'])): ?>
                                        <p class="text-muted small fst-italic mb-3 line-clamp-3">
                                            “<?= esc($item['quote']) ?>”
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="pt-3 border-top d-flex flex-column gap-2 text-secondary small">
                                    <?php if (!empty($item['phone'])): ?>
                                        <div><i class="fa-solid fa-phone text-success me-2"></i><strong>โทร:</strong> <?= esc($item['phone']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($item['email'])): ?>
                                        <div><i class="fa-solid fa-envelope text-danger me-2"></i><strong>อีเมล:</strong> <?= esc($item['email']) ?></div>
                                    <?php endif; ?>

                                    <?php if ($isOfficer): ?>
                                    <div class="mt-2 pt-2 border-top d-flex justify-content-end gap-2">
                                        <button type="button" onclick="ExecutiveStudio.open('<?= $item['id'] ?>')" class="btn btn-xs btn-info text-dark rounded-pill px-2 py-1 small fw-bold">
                                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                        </button>
                                        <button type="button" onclick="ExecutiveStudio.deleteItem('<?= $item['id'] ?>', '<?= esc($item['name'], 'js') ?>')" class="btn btn-xs btn-outline-danger rounded-pill px-2 py-1 small">
                                            <i class="fa-solid fa-trash-can"></i> ลบ
                                        </button>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>
<?= $this->endSection() ?>

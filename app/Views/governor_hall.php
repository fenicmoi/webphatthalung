<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$governors    = $governors ?? [];
$allGovernors = $allGovernors ?? $governors;
$totalCount   = $totalCount ?? count($allGovernors);
$eras         = $eras ?? [];
$selectedEra  = $selectedEra ?? 'all';
$isOfficer    = $isOfficer ?? (bool)session()->get('isLoggedIn');
?>

<style>
/* ========================================================
   HALL OF GOVERNORS - PRESTIGIOUS ROYAL & HERITAGE THEME
   ======================================================== */
.gov-hero-banner {
    background: linear-gradient(135deg, #091e3a 0%, #1e3a8a 50%, #1e1b4b 100%);
    position: relative;
    overflow: hidden;
    color: #ffffff;
    border-radius: 0 0 2.5rem 2.5rem;
    box-shadow: 0 15px 35px -10px rgba(15, 23, 42, 0.4);
}
.gov-hero-banner::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: radial-gradient(circle at 80% 20%, rgba(217, 119, 6, 0.18) 0%, transparent 60%),
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.15) 0%, transparent 60%);
    pointer-events: none;
}
.gold-badge {
    background: linear-gradient(135deg, #fbbf24, #d97706);
    color: #1e1b4b;
    font-weight: 800;
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
}
.gold-border-glow {
    border: 2px solid rgba(245, 158, 11, 0.4) !important;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.gold-border-glow:hover {
    border-color: #f59e0b !important;
    transform: translateY(-5px);
    box-shadow: 0 20px 30px -10px rgba(217, 119, 6, 0.25) !important;
}
.gov-photo-wrap {
    width: 100%;
    padding-top: 125%; /* 4:5 portrait ratio */
    position: relative;
    overflow: hidden;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
}
.gov-photo-wrap img {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: top center; /* ล็อคโฟกัสที่ศีรษะและใบหน้าด้านบนเสมอ ไม่โดนตัดขอบ */
    transition: transform 0.5s ease;
}
.gold-border-glow:hover .gov-photo-wrap img {
    transform: scale(1.04);
}
.gov-photo-fallback {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: #94a3b8;
}
.era-pill-btn {
    padding: 0.55rem 1.25rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.88rem;
    border: 1px solid rgba(0,0,0,0.1);
    background: #ffffff;
    color: #475569;
    text-decoration: none;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.era-pill-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.era-pill-btn.active {
    background: linear-gradient(135deg, #1e3a8a, #0f172a);
    color: #ffffff;
    border-color: #1e3a8a;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
}
@media print {
    body * { visibility: hidden; }
    #printableHallArea, #printableHallArea * { visibility: visible; }
    #printableHallArea { position: absolute; left: 0; top: 0; width: 100%; }
    .no-print { display: none !important; }
}
</style>

<!-- ======================================================== -->
<!-- 1. HERO BANNER: HALL OF GOVERNORS -->
<!-- ======================================================== -->
<section class="gov-hero-banner py-5 px-3 px-md-5 mb-5">
    <div class="container py-3">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 mb-3">
                    <i class="fa-solid fa-crown text-warning"></i>
                    <span class="small fw-bold text-light tracking-wide text-uppercase">เกียรติประวัติและทำเนียบผู้บริหารสูงสุดของจังหวัด</span>
                </div>
                <h1 class="display-5 fw-extrabold text-white mb-2" style="letter-spacing: -0.5px;">
                    ทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง
                </h1>
                <p class="lead text-light text-opacity-90 mb-4" style="max-width: 700px; font-size: 1.1rem; line-height: 1.6;">
                    จารึกเกียรติยศและคุณูปการของอดีตเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง ตั้งแต่สมัยกรุงธนบุรี ต้นรัตนโกสินทร์ มณฑลเทศาภิบาล ตราบจนถึงปัจจุบัน
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <span class="badge gold-badge rounded-pill px-3 py-2 fs-6">
                        <i class="fa-solid fa-users me-1.5"></i> ทำเนียบรวม <?= $totalCount ?> ท่าน
                    </span>
                    <button type="button" onclick="window.print()" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-print text-warning"></i> พิมพ์เอกสารทำเนียบ
                    </button>
                    <?php if ($isOfficer): ?>
                        <button type="button" onclick="openGovStudioModal()" class="btn btn-warning text-dark rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow">
                            <i class="fa-solid fa-plus-circle"></i> + เพิ่มรายนามผู้ว่าฯ
                        </button>
                        <a href="<?= base_url('admin/governors') ?>" class="btn btn-light rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1.5 text-dark">
                            <i class="fa-solid fa-sliders"></i> สตูดิโอหลังบ้าน
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <div class="position-relative d-inline-block">
                    <div class="p-4 rounded-circle bg-white bg-opacity-10 border border-white border-opacity-20 shadow-lg" style="width: 220px; height: 220px; backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center;">
                        <img src="<?= base_url('uploads/logo/logo_1787048018.png') ?>" alt="ตราประจำจังหวัดพัทลุง" class="img-fluid" style="max-height: 160px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.4));">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================== -->
<!-- 2. FILTER & SEARCH CONTROLS -->
<!-- ======================================================== -->
<div class="container mb-4 no-print">
    <!-- Era Navigation Tabs -->
    <div class="d-flex align-items-center gap-2 overflow-x-auto pb-2 mb-4">
        <a href="<?= base_url('governors') ?>" class="era-pill-btn <?= ($selectedEra === 'all') ? 'active' : '' ?>">
            <i class="fa-solid fa-layer-group"></i> ทุกยุคสมัย (<?= $totalCount ?>)
        </a>
        <?php foreach ($eras as $eraName): 
            $countThisEra = count(array_filter($allGovernors, fn($g) => ($g['era'] ?? '') === $eraName));
        ?>
            <a href="<?= base_url('governors?era=' . urlencode($eraName)) ?>" class="era-pill-btn <?= ($selectedEra === $eraName) ? 'active' : '' ?>">
                <?= esc($eraName) ?> <span class="badge bg-secondary bg-opacity-25 rounded-pill ms-1"><?= $countThisEra ?></span>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Live Search & Layout Switcher -->
    <div class="row align-items-center g-3 bg-white p-3.5 rounded-4 shadow-sm border">
        <div class="col-12 col-md-7">
            <div class="position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y text-muted ms-3 fs-5"></i>
                <input type="text" id="liveGovSearch" class="form-control rounded-pill ps-5 py-2.5 fs-6 border-light-subtle" placeholder="ค้นหาตามชื่อ-นามสกุล, บรรดาศักดิ์, ลำดับที่ (เช่น คนที่ 1), หรือ พ.ศ. ดำรงตำแหน่ง..." oninput="filterGovernors(this.value)">
            </div>
        </div>
        <div class="col-12 col-md-5 d-flex align-items-center justify-content-md-end gap-2">
            <span class="text-muted small me-2" id="govCountBadge">แสดงผล <?= count($governors) ?> ท่าน</span>
            <div class="btn-group rounded-pill p-1 bg-light border" role="group">
                <button type="button" class="btn btn-sm btn-white active rounded-pill px-3 fw-bold" id="viewCardsBtn" onclick="switchGovView('cards')">
                    <i class="fa-solid fa-grip me-1 text-primary"></i> การ์ด
                </button>
                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 fw-bold" id="viewTableBtn" onclick="switchGovView('table')">
                    <i class="fa-solid fa-list me-1 text-secondary"></i> ตาราง
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- 3. GOVERNOR ROSTER: CARDS VIEW & TABLE VIEW -->
<!-- ======================================================== -->
<div class="container mb-5" id="printableHallArea">

    <!-- 3.1 CARDS GRID VIEW -->
    <div id="govCardsView" class="row g-4">
        <?php if (empty($governors)): ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-user-slash fs-1 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold text-muted">ไม่พบข้อมูลรายนามผู้ว่าราชการจังหวัดตามเงื่อนไข</h5>
                <p class="text-muted small">กรุณาปรับคำค้นหา หรือเลือกยุคสมัยใหม่อีกครั้ง</p>
            </div>
        <?php else: ?>
            <?php foreach ($governors as $gov): 
                $seq = (int)($gov['sequence'] ?? 1);
                $name = esc($gov['name'] ?? '');
                $period = esc($gov['period'] ?? '');
                $titleHonor = esc($gov['title_honor'] ?? 'ผู้ว่าราชการจังหวัดพัทลุง');
                $era = esc($gov['era'] ?? 'ยุคประวัติศาสตร์');
                $achievement = esc($gov['achievement'] ?? '');
                $isCurr = !empty($gov['is_current']);
                $img = !empty($gov['image']) ? (strpos($gov['image'], 'http') === 0 ? $gov['image'] : base_url($gov['image'])) : '';
                $govId = esc($gov['id'] ?? '');
            ?>
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3 gov-item-card" data-name="<?= mb_strtolower($name . ' ' . $period . ' ' . $seq . ' ' . $titleHonor . ' ' . $era) ?>">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden gold-border-glow position-relative bg-white d-flex flex-column justify-content-between">
                        
                        <!-- Top Badges & Officer Controls -->
                        <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1.5" style="z-index: 5;">
                            <span class="badge <?= $isCurr ? 'bg-success' : 'gold-badge' ?> rounded-pill px-3 py-1.5 fs-7">
                                <i class="fa-solid fa-award me-1"></i> ผู้ว่าราชการจังหวัดคนที่ <?= $seq ?>
                            </span>
                            <?php if ($isCurr): ?>
                                <span class="badge bg-danger rounded-pill px-2.5 py-1 small animate-pulse">
                                    <i class="fa-solid fa-circle-dot me-1"></i> ท่านปัจจุบัน
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($isOfficer): ?>
                            <div class="position-absolute top-0 end-0 m-3 d-flex gap-1.5" style="z-index: 5;">
                                <button type="button" class="btn btn-sm btn-white bg-white shadow-sm rounded-circle border p-0" style="width: 32px; height: 32px;" onclick="editGovStudio('<?= $govId ?>')" title="แก้ไขข้อมูล">
                                    <i class="fa-solid fa-pen-to-square text-primary" style="font-size: 0.8rem;"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-white bg-white shadow-sm rounded-circle border p-0" style="width: 32px; height: 32px;" onclick="deleteGovStudio('<?= $govId ?>', '<?= addslashes($name) ?>')" title="ลบข้อมูล">
                                    <i class="fa-solid fa-trash-can text-danger" style="font-size: 0.8rem;"></i>
                                </button>
                            </div>
                        <?php endif; ?>

                        <div>
                            <!-- Photo Wrap -->
                            <div class="gov-photo-wrap">
                                <?php if (!empty($img)): ?>
                                    <img src="<?= $img ?>" alt="<?= $name ?>" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="gov-photo-fallback" style="display: none;">
                                        <i class="fa-solid fa-user-tie fs-1 text-warning mb-2"></i>
                                        <span class="small text-white-50">ภาพทำเนียบประวัติศาสตร์</span>
                                    </div>
                                <?php else: ?>
                                    <div class="gov-photo-fallback">
                                        <i class="fa-solid fa-user-tie fs-1 text-warning mb-2"></i>
                                        <span class="small text-white-50">ภาพทำเนียบประวัติศาสตร์</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content Body -->
                            <div class="p-3.5 text-center">
                                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.12rem; line-height: 1.4;">
                                    <?= $name ?>
                                </h5>
                                <div class="text-primary fw-semibold small mb-2">
                                    <i class="fa-solid fa-shield-halved me-1 text-warning"></i> <?= $titleHonor ?>
                                </div>
                                <div class="badge bg-light text-dark border rounded-pill px-3 py-1.5 fw-bold small mb-3">
                                    <i class="fa-regular fa-clock text-danger me-1"></i> <?= $period ?>
                                </div>
                                <?php if (!empty($achievement)): ?>
                                    <p class="text-muted small mb-0 px-2" style="font-size: 0.82rem; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        <?= $achievement ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Footer Era Tag -->
                        <div class="px-3 py-2.5 bg-light border-top text-center text-muted small" style="font-size: 0.78rem;">
                            <i class="fa-solid fa-landmark me-1 text-secondary"></i> <?= $era ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- 3.2 TABLE VIEW (DEFAULT HIDDEN) -->
    <div id="govTableView" class="d-none bg-white rounded-4 shadow-sm border overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white" style="background: linear-gradient(135deg, #0f172a, #1e293b) !important;">
                    <tr>
                        <th class="py-3 px-4 text-center" style="width: 100px;">ลำดับที่</th>
                        <th class="py-3" style="width: 90px;">รูปถ่าย</th>
                        <th class="py-3">ชื่อ - บรรดาศักดิ์ / ตำแหน่ง</th>
                        <th class="py-3">ช่วงเวลาดำรงตำแหน่ง</th>
                        <th class="py-3">ยุคสมัย</th>
                        <th class="py-3">ผลงานและบทบาทสำคัญ</th>
                        <?php if ($isOfficer): ?>
                            <th class="py-3 text-center" style="width: 120px;">จัดการ</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="govTableBody">
                    <?php foreach ($governors as $gov): 
                        $seq = (int)($gov['sequence'] ?? 1);
                        $name = esc($gov['name'] ?? '');
                        $period = esc($gov['period'] ?? '');
                        $titleHonor = esc($gov['title_honor'] ?? 'ผู้ว่าราชการจังหวัดพัทลุง');
                        $era = esc($gov['era'] ?? 'ยุคประวัติศาสตร์');
                        $achievement = esc($gov['achievement'] ?? '-');
                        $isCurr = !empty($gov['is_current']);
                        $img = !empty($gov['image']) ? (strpos($gov['image'], 'http') === 0 ? $gov['image'] : base_url($gov['image'])) : '';
                        $govId = esc($gov['id'] ?? '');
                    ?>
                        <tr class="gov-item-row" data-name="<?= mb_strtolower($name . ' ' . $period . ' ' . $seq . ' ' . $titleHonor . ' ' . $era) ?>">
                            <td class="text-center py-3">
                                <span class="badge <?= $isCurr ? 'bg-success' : 'gold-badge' ?> rounded-pill px-3 py-1.5 fw-bold">
                                    คนที่ <?= $seq ?>
                                </span>
                            </td>
                            <td>
                                <div class="rounded-3 overflow-hidden bg-dark shadow-sm" style="width: 54px; height: 68px;">
                                    <?php if (!empty($img)): ?>
                                        <img src="<?= $img ?>" alt="<?= $name ?>" class="w-100 h-100" style="object-fit: cover; object-position: top center;">
                                    <?php else: ?>
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-warning bg-secondary bg-opacity-25">
                                            <i class="fa-solid fa-user-tie fs-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6 mb-0.5"><?= $name ?></div>
                                <div class="text-muted small"><i class="fa-solid fa-shield-halved text-warning me-1"></i> <?= $titleHonor ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-bold">
                                    <i class="fa-regular fa-clock text-danger me-1"></i> <?= $period ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2.5 py-1">
                                    <?= $era ?>
                                </span>
                            </td>
                            <td>
                                <div class="small text-muted" style="max-width: 320px; line-height: 1.4;">
                                    <?= $achievement ?>
                                </div>
                            </td>
                            <?php if ($isOfficer): ?>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" style="width: 32px; height: 32px; padding: 0;" onclick="editGovStudio('<?= $govId ?>')" title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0;" onclick="deleteGovStudio('<?= $govId ?>', '<?= addslashes($name) ?>')" title="ลบ">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php if ($isOfficer): ?>
<!-- ======================================================== -->
<!-- MODAL: GOVERNOR ON-PAGE STUDIO (ADD / EDIT) -->
<!-- ======================================================== -->
<div class="modal fade" id="govStudioModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #091e3a, #1e3a8a) !important;">
                <h5 class="modal-title fw-bold" id="govStudioModalTitle">
                    <i class="fa-solid fa-crown text-warning me-2"></i> จัดการทำเนียบผู้ว่าราชการจังหวัด
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="govStudioForm" onsubmit="event.preventDefault(); saveGovStudio();" enctype="multipart/form-data">
                    <input type="hidden" id="govId" name="id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ลำดับที่ (คนที่) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">คนที่</span>
                                <input type="number" class="form-control" id="govSequence" name="sequence" min="1" required placeholder="เช่น 1">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">ชื่อ - นามสกุล / บรรดาศักดิ์ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="govName" name="name" required placeholder="เช่น พระยาพัทลุง (ขุนคางเหล็ก)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ช่วงเวลาดำรงตำแหน่ง (พ.ศ.) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="govPeriod" name="period" required placeholder="เช่น พ.ศ. 2315 - 2332 หรือ 1 ต.ค. 2566 - ปัจจุบัน">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ตำแหน่ง / บรรดาศักดิ์กำกับ</label>
                            <input type="text" class="form-control" id="govTitleHonor" name="title_honor" placeholder="เช่น เจ้าเมืองพัทลุง หรือ ผู้ว่าราชการจังหวัดพัทลุง">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ยุคสมัยทางประวัติศาสตร์</label>
                            <select class="form-select" id="govEra" name="era">
                                <option value="ยุคกรุงธนบุรีและต้นรัตนโกสินทร์">ยุคกรุงธนบุรีและต้นรัตนโกสินทร์</option>
                                <option value="ยุคต้นรัตนโกสินทร์">ยุคต้นรัตนโกสินทร์</option>
                                <option value="ยุครัตนโกสินทร์ตอนกลาง">ยุครัตนโกสินทร์ตอนกลาง</option>
                                <option value="ยุคมณฑลเทศาภิบาล (รัชกาลที่ 5)">ยุคมณฑลเทศาภิบาล (รัชกาลที่ 5)</option>
                                <option value="ยุคหลังเปลี่ยนแปลงการปกครอง พ.ศ. 2475">ยุคหลังเปลี่ยนแปลงการปกครอง พ.ศ. 2475</option>
                                <option value="ยุคปัจจุบัน">ยุคปัจจุบัน</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-4">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input" type="checkbox" id="govIsCurrent" name="is_current" value="1">
                                <label class="form-check-label fw-bold text-dark" for="govIsCurrent">ผู้ว่าราชการจังหวัดคนปัจจุบัน</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">อัปโหลดรูปภาพประจำตัว (Portrait Photo)</label>
                            <input type="file" class="form-control" id="govImageFile" name="image_file" accept="image/*" onchange="previewGovImg(this)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">หรือระบุเป็น URL รูปภาพ</label>
                            <input type="text" class="form-control" id="govImageUrl" name="image_url" placeholder="https://..." oninput="document.getElementById('govImgPreview').src = this.value">
                        </div>
                    </div>

                    <div id="govPreviewBox" class="mb-3 p-3 rounded-3 border bg-light d-flex align-items-center gap-3">
                        <div class="rounded-3 overflow-hidden bg-dark shadow-sm flex-shrink-0" style="width: 70px; height: 85px;">
                            <img id="govImgPreview" src="" alt="Preview" class="w-100 h-100" style="object-fit: cover; object-position: top center;" onerror="this.src='https://via.placeholder.com/150x180?text=No+Photo'">
                        </div>
                        <div>
                            <span class="small fw-bold text-secondary d-block mb-1">ตัวอย่างภาพที่จะแสดงบนทำเนียบ</span>
                            <span class="text-muted small">ระบบรองรับไฟล์ JPG, PNG, WebP และคำนวณอัตราส่วนภาพบุคคลให้สวยงาม</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">ประวัติย่อ / บทบาทและผลงานสำคัญ</label>
                        <textarea class="form-control" id="govAchievement" name="achievement" rows="3" placeholder="ระบุประวัติย่อหรือบทบาทสำคัญในการพัฒนาจังหวัดพัทลุง..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary px-4 fw-bold" id="btnSaveGovStudio" onclick="saveGovStudio()">
                    <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let govStudioModal;

function openGovStudioModal() {
    if (!govStudioModal) govStudioModal = new bootstrap.Modal(document.getElementById('govStudioModal'));
    document.getElementById('govStudioForm').reset();
    document.getElementById('govId').value = '';
    document.getElementById('govImgPreview').src = '';
    document.getElementById('govStudioModalTitle').innerHTML = '<i class="fa-solid fa-plus-circle text-warning me-2"></i> เพิ่มรายนามผู้ว่าราชการจังหวัด';
    govStudioModal.show();
}

function previewGovImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('govImgPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

async function editGovStudio(id) {
    if (!govStudioModal) govStudioModal = new bootstrap.Modal(document.getElementById('govStudioModal'));
    try {
        const res = await App.fetch(`<?= base_url('admin/governors/get-item') ?>/${id}`);
        if (res && res.status === 'success') {
            const g = res.data;
            document.getElementById('govId').value = g.id || '';
            document.getElementById('govSequence').value = g.sequence || 1;
            document.getElementById('govName').value = g.name || '';
            document.getElementById('govPeriod').value = g.period || '';
            document.getElementById('govTitleHonor').value = g.title_honor || '';
            document.getElementById('govEra').value = g.era || 'ยุคปัจจุบัน';
            document.getElementById('govImageUrl').value = g.image || '';
            document.getElementById('govAchievement').value = g.achievement || '';
            document.getElementById('govIsCurrent').checked = !!g.is_current;

            if (g.image) {
                const imgUrl = (g.image.startsWith('http')) ? g.image : '<?= base_url() ?>/' + g.image;
                document.getElementById('govImgPreview').src = imgUrl;
            } else {
                document.getElementById('govImgPreview').src = '';
            }

            document.getElementById('govStudioModalTitle').innerHTML = `<i class="fa-solid fa-pen-to-square text-warning me-2"></i> แก้ไข: คนที่ ${g.sequence} ${g.name}`;
            govStudioModal.show();
        } else {
            App.toast(res ? res.message : 'ไม่พบข้อมูลผู้ว่าราชการจังหวัด', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
    }
}

async function saveGovStudio() {
    const form = document.getElementById('govStudioForm');
    const name = document.getElementById('govName').value.trim();
    const period = document.getElementById('govPeriod').value.trim();
    const seq = document.getElementById('govSequence').value.trim();

    if (!name || !period || !seq) {
        App.toast('กรุณากรอกลำดับที่ ชื่อ และช่วงเวลาดำรงตำแหน่งให้ครบถ้วน', 'warning');
        return;
    }

    const btn = document.getElementById('btnSaveGovStudio');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    const formData = new FormData(form);

    try {
        const res = await App.fetch('<?= base_url("admin/governors/save-item") ?>', {
            method: 'POST',
            body: formData
        });

        if (res && res.status === 'success') {
            App.toast(res.message, 'success');
            govStudioModal.hide();
            setTimeout(() => window.location.reload(), 800);
        } else {
            App.toast(res ? res.message : 'บันทึกข้อมูลไม่สำเร็จ', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = origText;
    }
}

async function deleteGovStudio(id, name) {
    if (confirm(`คุณแน่ใจหรือไม่ที่จะลบรายนาม "${name}" ออกจากทำเนียบผู้ว่าราชการจังหวัด?`)) {
        try {
            const res = await App.fetch(`<?= base_url('admin/governors/delete-item') ?>/${id}`, {
                method: 'POST'
            });
            if (res && res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res ? res.message : 'ลบข้อมูลไม่สำเร็จ', 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
        }
    }
}
</script>
<?php endif; ?>

<script>
// Live Search for Governors
function filterGovernors(val) {
    const s = val.toLowerCase().trim();
    let visibleCount = 0;

    // Filter Cards
    document.querySelectorAll('.gov-item-card').forEach(card => {
        const text = card.getAttribute('data-name') || '';
        if (text.includes(s) || s === '') {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Filter Table Rows
    document.querySelectorAll('.gov-item-row').forEach(row => {
        const text = row.getAttribute('data-name') || '';
        if (text.includes(s) || s === '') {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    const badge = document.getElementById('govCountBadge');
    if (badge) badge.innerText = `แสดงผล ${visibleCount} ท่าน`;
}

// Switch between Cards and Table View
function switchGovView(view) {
    const cards = document.getElementById('govCardsView');
    const table = document.getElementById('govTableView');
    const cardBtn = document.getElementById('viewCardsBtn');
    const tableBtn = document.getElementById('viewTableBtn');

    if (view === 'cards') {
        cards.classList.remove('d-none');
        table.classList.add('d-none');
        cardBtn.classList.add('active', 'btn-white');
        cardBtn.classList.remove('btn-light');
        tableBtn.classList.remove('active', 'btn-white');
        tableBtn.classList.add('btn-light');
    } else {
        cards.classList.add('d-none');
        table.classList.remove('d-none');
        tableBtn.classList.add('active', 'btn-white');
        tableBtn.classList.remove('btn-light');
        cardBtn.classList.remove('active', 'btn-white');
        cardBtn.classList.add('btn-light');
    }
}
</script>

<?= $this->endSection() ?>

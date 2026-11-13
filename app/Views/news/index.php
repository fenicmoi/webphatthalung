<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isOfficer        = session()->get('isLoggedIn');
$isProcurementCat = !empty($isProcurementCat);
$isFinancialCat   = !empty($isFinancialCat);
$newsList         = $newsList ?? [];
$categories       = $categories ?? [];
$currentCat       = $currentCat ?? null;
$pageTitle        = $pageTitle ?? 'ข่าวสารและประกาศ';
?>

<style>
/* Clean & Eye-Soothing e-GP Table Styles */
.egp-clean-table {
    border-collapse: separate !important;
    border-spacing: 0;
    width: 100% !important;
    border: none !important;
}
.egp-clean-table thead th {
    background-color: #dbeafe !important;
    color: #1e3a8a !important;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 14px 16px !important;
    border-bottom: 2px solid #bfdbfe !important;
    vertical-align: middle;
}
.egp-clean-table tbody tr {
    transition: background-color 0.15s ease-in-out;
}
.egp-clean-table tbody tr:hover {
    background-color: #f8fafc !important;
}
.egp-clean-table tbody td {
    padding: 14px 16px !important;
    vertical-align: middle;
    color: #334155;
    border-bottom: 1px solid #f1f5f9 !important;
    font-size: 0.93rem;
}
.egp-doc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background-color: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    color: #0f172a;
    transition: all 0.2s ease;
    text-decoration: none;
}
.egp-doc-btn:hover {
    background-color: #dbeafe;
    border-color: #93c5fd;
    color: #1d4ed8;
    transform: scale(1.05);
}

/* DataTables Controls & Pagination Aesthetics */
.dataTables_wrapper .dataTables_length select {
    border-radius: 8px;
    padding: 6px 32px 6px 12px;
    border: 1px solid #cbd5e1;
    font-size: 0.88rem;
    background-color: #ffffff;
}
.dataTables_wrapper .dataTables_filter input {
    border-radius: 8px;
    padding: 6px 14px;
    border: 1px solid #cbd5e1;
    font-size: 0.88rem;
    min-width: 260px;
}
.dataTables_wrapper .dataTables_info {
    color: #64748b;
    font-size: 0.88rem;
    padding-top: 14px;
}
.dataTables_wrapper .dataTables_paginate {
    padding-top: 10px;
}
.dataTables_paginate .pagination .page-item .page-link {
    border-radius: 6px;
    margin: 0 2px;
    color: #334155;
    border: 1px solid #e2e8f0;
    font-size: 0.88rem;
    padding: 6px 12px;
}
.dataTables_paginate .pagination .page-item.active .page-link {
    background-color: #1e3a8a !important;
    border-color: #1e3a8a !important;
    color: #ffffff !important;
    font-weight: bold;
}
</style>

<div class="container my-5 pt-4">
    <!-- Breadcrumb & Header Banner -->
    <div class="glass-card p-4 p-md-5 rounded-4 shadow-sm mb-4" style="background: rgba(30, 41, 59, 0.85); border: 1px solid rgba(255,255,255,0.15) !important;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb m-0" style="font-size: 0.95rem;">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
                <li class="breadcrumb-item active text-light" aria-current="page"><?= !empty($currentCat) ? esc($currentCat) : 'ข่าวประชาสัมพันธ์ทั้งหมด' ?></li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
            <div>
                <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                    <?php if ($isFinancialCat): ?>
                        <i class="fa-solid fa-file-invoice-dollar text-warning fs-1"></i>
                        <span>รายงานทางการเงินและงบทดลอง - จังหวัดพัทลุง</span>
                    <?php elseif ($isProcurementCat): ?>
                        <i class="fa-solid fa-gavel text-warning fs-1"></i>
                        <span>ประกาศจัดซื้อจัดจ้างภาครัฐ (e-GP) - จังหวัดพัทลุง</span>
                    <?php else: ?>
                        <i class="fa-solid fa-bullhorn text-warning fs-1"></i>
                        <span>คลังข่าวสารและประกาศราชการ</span>
                    <?php endif; ?>
                    <?php if ($isOfficer): ?>
                        <span class="badge bg-success text-dark fs-6 px-3 py-1"><i class="fa-solid fa-user-shield me-1"></i>On-Page CMS Ready</span>
                    <?php endif; ?>
                </h2>
                <p class="text-secondary m-0">
                    <?php if ($isFinancialCat): ?>
                        รายงานทางการเงิน งบทดลอง และเอกสารการเงินของจังหวัดพัทลุง (รายการที่เผยแพร่บนเว็บไซต์ใหม่)
                    <?php elseif ($isProcurementCat): ?>
                        ประกาศจัดซื้อจัดจ้าง แผนการจัดซื้อจัดจ้าง และราคากลางของจังหวัดพัทลุง (รายการที่เผยแพร่บนเว็บไซต์ใหม่)
                    <?php else: ?>
                        ติดตามข่าวสาร กิจกรรมจังหวัด ประกาศประกวดราคา และคู่มือประชาชนทั้งหมด
                    <?php endif; ?>
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2 align-items-center">
                <?php if ($isFinancialCat): ?>
                    <a href="https://www.phatthalung.go.th/2022-stop/news_money" target="_blank" rel="noopener noreferrer" class="btn btn-warning text-dark px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;" title="เปิดดูระบบรายงานทางการเงินเว็บไซต์เดิม (2022-stop)">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> ไปยังระบบเดิม (2022-stop)
                    </a>
                    <a href="<?= base_url('financial-reports') ?>" class="btn btn-outline-light px-3.5 py-2 rounded-pill fw-semibold d-inline-flex align-items-center gap-2 text-white" style="font-size: 0.9rem;" title="ดูข้อมูลรายงานทางการเงินและงบทดลองย้อนหลังในคลังเว็บไซต์ใหม่">
                        <i class="fa-solid fa-clock-rotate-left text-warning"></i> คลังข้อมูลเดิม (230 รายการ)
                    </a>
                <?php elseif ($isProcurementCat): ?>
                    <a href="https://www.phatthalung.go.th/2022-stop/news?cid=3" target="_blank" rel="noopener noreferrer" class="btn btn-warning text-dark px-3.5 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;" title="เปิดดูประกาศจัดซื้อจัดจ้างในระบบเดิม (2022-stop)">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> ไปยังระบบเดิม (2022-stop)
                    </a>
                    <a href="<?= base_url('procurement') ?>" class="btn btn-outline-light px-3.5 py-2 rounded-pill fw-semibold d-inline-flex align-items-center gap-2 text-white" style="font-size: 0.9rem;" title="เปิดหน้าระบบสืบค้นจัดซื้อจัดจ้างภาครัฐ e-GP ฉบับเต็ม">
                        <i class="fa-solid fa-gavel text-warning"></i> คลังจัดซื้อจัดจ้าง e-GP ฉบับเต็ม
                    </a>
                <?php endif; ?>
                <?php if ($isOfficer): ?>
                    <?php if (function_exists('is_super_admin') && is_super_admin()): ?>
                        <button type="button" onclick="NewsStudio.addCategory()" class="btn btn-outline-info px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2" title="เฉพาะ Super Admin เท่านั้น">
                            <i class="fa-solid fa-tags"></i> เพิ่มหมวดหมู่
                        </button>
                    <?php endif; ?>
                    <button type="button" onclick="NewsStudio.open()" class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-dark d-flex align-items-center gap-2 shadow-lg hover-scale" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                        <i class="fa-solid fa-circle-plus fs-5"></i> + สร้างข่าวสารใหม่ทันที (Studio)
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Category Pills Filter -->
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-start align-items-center">
        <span class="text-muted fw-bold small me-2"><i class="fa-solid fa-filter text-primary me-1"></i>หมวดหมู่:</span>
        <a href="<?= base_url('news') ?>" class="btn btn-sm px-3.5 py-1.5 rounded-pill news-cat-btn <?= (empty($currentCat) && empty($isFeaturedFilter)) ? 'active' : '' ?>">
            ทั้งหมด
        </a>
        <a href="<?= base_url('news?category=featured') ?>" class="btn btn-sm px-3.5 py-1.5 rounded-pill news-cat-btn <?= (!empty($isFeaturedFilter)) ? 'active' : '' ?>" style="<?= (!empty($isFeaturedFilter)) ? 'background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; border: none; font-weight: bold;' : '' ?>">
            <i class="fa-solid fa-star text-warning me-1"></i> หัวข้อเด่น
        </a>
        <?php foreach ($categories as $cat): 
            $normCur = preg_replace('/\s*([\/\(\)])\s*/u', '$1', trim($currentCat ?? ''));
            $normCat = preg_replace('/\s*([\/\(\)])\s*/u', '$1', trim($cat));
            $isActive = (!empty($normCur) && strcasecmp($normCur, $normCat) === 0);
            $isEgpCat = (mb_stripos($cat, 'จัดซื้อจัดจ้าง') !== false || mb_stripos($cat, 'e-gp') !== false);
            $isFinCatItem = (mb_stripos($cat, 'การเงิน') !== false || mb_stripos($cat, 'งบทดลอง') !== false);
            $isPrdCatItem = (mb_stripos($cat, 'สปชส') !== false || mb_stripos($cat, 'สำนักงานประชาสัมพันธ์') !== false || mb_stripos($cat, 'กรมประชาสัมพันธ์') !== false || mb_stripos($cat, 'nnt') !== false || mb_stripos($cat, 'prd') !== false);
        ?>
            <a href="<?= base_url('news?category=' . urlencode($cat)) ?>" class="btn btn-sm px-3.5 py-1.5 rounded-pill news-cat-btn <?= $isActive ? 'active' : '' ?>">
                <?php if ($isEgpCat): ?><i class="fa-solid fa-gavel text-warning me-1"></i><?php endif; ?>
                <?php if ($isFinCatItem): ?><i class="fa-solid fa-file-invoice-dollar text-primary me-1"></i><?php endif; ?>
                <?php if ($isPrdCatItem): ?><i class="fa-solid fa-bullhorn text-success me-1"></i><?php endif; ?>
                <?= esc($cat) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Standard News List View -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5" style="background: var(--card-bg, #ffffff); border: 1px solid rgba(15,23,42,0.08) !important;">
        <?php if ($isProcurementCat): ?>
            <div class="card-header bg-light py-3 px-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h6 class="fw-bold m-0 text-dark">
                    <i class="fa-solid fa-gavel text-warning me-1"></i> ประกาศจัดซื้อจัดจ้าง (e-GP) (รายการที่เผยแพร่ใหม่บนเว็บไซต์)
                </h6>
                <div class="d-flex gap-2 align-items-center">
                    <a href="https://www.phatthalung.go.th/2022-stop/news?cid=3" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-warning text-dark rounded-pill px-2.5 py-1 fw-bold text-decoration-none">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1 text-warning"></i> ระบบเดิม (2022-stop)
                    </a>
                    <a href="<?= base_url('procurement') ?>" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 fw-semibold text-decoration-none">
                        <i class="fa-solid fa-table me-1 text-primary"></i> คลังจัดซื้อจัดจ้าง e-GP ฉบับเต็ม &raquo;
                    </a>
                </div>
            </div>
        <?php elseif ($isFinancialCat): ?>
            <div class="card-header bg-light py-3 px-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <h6 class="fw-bold m-0 text-dark">
                    <i class="fa-solid fa-file-invoice-dollar text-success me-1"></i> รายงานทางการเงินและงบทดลอง (รายการที่เผยแพร่ใหม่บนเว็บไซต์)
                </h6>
                <div class="d-flex gap-2 align-items-center">
                    <a href="https://www.phatthalung.go.th/2022-stop/news_money" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-outline-warning text-dark rounded-pill px-2.5 py-1 fw-bold text-decoration-none">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1 text-warning"></i> ระบบเดิม (2022-stop)
                    </a>
                    <a href="<?= base_url('financial-reports') ?>" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1 fw-semibold text-decoration-none">
                        <i class="fa-solid fa-clock-rotate-left text-primary me-1"></i> คลังย้อนหลัง (230 รายการ) &raquo;
                    </a>
                </div>
            </div>
        <?php elseif (!empty($isPrdCat)): ?>
            <div class="card-header bg-success bg-opacity-10 py-3 px-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="fw-bold m-0 text-success">
                        <i class="fa-solid fa-bullhorn me-2"></i> ข่าวสารและกิจกรรมจาก สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (สปชส.พัทลุง)
                    </h6>
                    <span class="badge bg-success rounded-pill px-2.5 py-0.5" style="font-size: 0.75rem;">Live Feed</span>
                </div>
                <a href="https://phatthalung.prd.go.th/th/content/category/index/id/3394" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ดูหมวดข่าวบน สปชส.พัทลุง
                </a>
            </div>
        <?php endif; ?>

        <?php if (empty($newsList)): ?>
            <div class="text-center py-5">
                <i class="fa-regular fa-folder-open text-muted fs-1 mb-3 opacity-50"></i>
                <h5 class="fw-bold text-dark">ไม่พบข้อมูลข่าวสารในหมวดหมู่นี้</h5>
                <p class="text-muted small m-0">กรุณาลองเปลี่ยนหมวดหมู่ค้นหา หรือคลิกเพิ่มข่าวสารใหม่จากโหมดเจ้าหน้าที่</p>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($newsList as $idx => $item): 
                    $nDate = !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : (!empty($item['display_date']) ? $item['display_date'] : date('d/m/Y'));
                    $attachCount = !empty($item['attachments']) ? count($item['attachments']) : 0;
                    $views = number_format($item['views'] ?? 1);
                    $isPrd = !empty($item['is_prd']);
                    $coverImg = !empty($item['cover_image']) ? $item['cover_image'] : null;
                ?>
                    <div class="list-group-item list-group-item-action p-3.5 p-md-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 border-bottom transition-all hover-bg-light" style="border-color: rgba(0,0,0,0.05) !important;">
                        
                        <!-- Left: Image (if PRD), Date & Category & Title -->
                        <div class="d-flex align-items-start gap-3 flex-grow-1 overflow-hidden">
                            <?php if ($isPrd && !empty($coverImg)): ?>
                                <img src="<?= esc($coverImg) ?>" alt="<?= esc($item['title']) ?>" class="rounded-3 flex-shrink-0 d-none d-sm-block shadow-xs" style="width: 100px; height: 68px; object-fit: cover;">
                            <?php else: ?>
                                <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-3 fw-bold flex-shrink-0 text-center" style="min-width: 95px; font-size: 0.82rem;">
                                    <i class="fa-regular fa-calendar me-1"></i> <?= $nDate ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="overflow-hidden">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <?php if ($isPrd && !empty($coverImg)): ?>
                                        <span class="text-muted small" style="font-size: 0.78rem;">
                                            <i class="fa-regular fa-calendar text-primary me-1"></i><?= $nDate ?>
                                        </span>
                                    <?php elseif (!$isPrd): ?>
                                        <span class="badge bg-light text-dark border px-2.5 py-0.5 rounded-pill small" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-tag me-1 text-primary"></i> <?= esc($item['category'] ?? 'ข่าวประกาศ') ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($item['is_featured'])): ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-star me-1 text-danger"></i> หัวข้อเด่น
                                        </span>
                                    <?php elseif (!empty($item['is_pinned'])): ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.68rem;">
                                            <i class="fa-solid fa-thumbtack me-1"></i> ปักหมุด
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <h6 class="fw-bold mb-1 text-dark text-truncate-2" style="line-height: 1.45; font-size: 1.02rem;">
                                    <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="text-dark text-decoration-none hover-primary">
                                        <?= esc($item['title']) ?>
                                    </a>
                                </h6>
                                <?php if (!empty($item['summary'])): ?>
                                    <p class="text-muted small mb-0 text-truncate" style="max-width: 700px; font-size: 0.85rem;">
                                        <?= esc($item['summary']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Right: Meta, Attachment & Action -->
                        <div class="d-flex align-items-center gap-3 text-muted small flex-shrink-0 align-self-end align-self-md-center">
                            <?php if ($attachCount > 0): ?>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2.5 py-1 rounded-pill" title="<?= $attachCount ?> ไฟล์แนบ">
                                    <i class="fa-solid fa-paperclip me-1"></i> <?= $attachCount ?> ไฟล์
                                </span>
                            <?php endif; ?>
                            
                            <span class="text-secondary" style="font-size: 0.78rem;">
                                <i class="fa-regular fa-eye me-1"></i> <?= $views ?>
                            </span>

                            <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" style="font-size: 0.82rem;">
                                รายละเอียด <i class="fa-solid fa-chevron-right ms-1"></i>
                            </a>

                            <?php if ($isOfficer && empty($item['is_prd'])): ?>
                                <div class="d-flex gap-1 ms-1">
                                    <button type="button" onclick="NewsStudio.toggleFeatured('<?= $item['id'] ?>')" class="btn btn-sm <?= !empty($item['is_featured']) ? 'btn-warning text-dark' : 'btn-outline-secondary' ?> rounded-circle p-0 d-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;" title="<?= !empty($item['is_featured']) ? 'หัวข้อเด่นหน้าหลัก (คลิกเพื่อยกเลิก)' : 'คลิกเพื่อตั้งเป็นหัวข้อเด่นหน้าหลัก' ?>">
                                        <i class="fa-<?= !empty($item['is_featured']) ? 'solid' : 'regular' ?> fa-star"></i>
                                    </button>
                                    <button type="button" onclick="NewsStudio.open('<?= $item['id'] ?>')" class="btn btn-sm btn-outline-info rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="แก้ไข">
                                        <i class="fa-solid fa-pen text-dark" style="font-size: 0.75rem;"></i>
                                    </button>
                                    <button type="button" onclick="NewsStudio.deleteNews('<?= $item['id'] ?>')" class="btn btn-sm btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="ลบ">
                                        <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

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

        <!-- Server-Side DataTables Search Toolbar & Table -->
        <div class="p-3.5 p-md-4">
            <div class="row g-2.5 align-items-center mb-3">
                <div class="col-md-7 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-success">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" id="newsServerSearchInput" class="form-control border-start-0 rounded-end-pill bg-light shadow-none py-2" placeholder="พิมพ์ค้นหาหัวข้อข่าว, หมวดหมู่, หรือคำสำคัญในเนื้อหา...">
                    </div>
                </div>
                <div class="col-md-5 col-lg-6 d-flex align-items-center justify-content-md-end gap-2">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 small fw-bold" id="newsTotalBadge">
                        <i class="fa-solid fa-database me-1"></i> กำลังโหลดข้อมูล...
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table id="newsServerTable" class="table egp-clean-table align-middle mb-0" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="width: 120px;">วันที่ประกาศ</th>
                            <th>หัวข้อข่าว / รายละเอียด</th>
                            <th style="width: 110px;" class="text-center">เข้าชม</th>
                            <th style="width: <?= $isOfficer ? '210px' : '125px' ?>;" class="text-end">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables Bootstrap 5 CDN (Server-Side Processing) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script>
(function() {
    const IS_OFFICER = <?= $isOfficer ? 'true' : 'false' ?>;
    const CURRENT_CAT = <?= json_encode(!empty($isFeaturedFilter) ? 'featured' : (string)($currentCat ?? ''), JSON_UNESCAPED_UNICODE) ?>;
    const DATATABLES_URL = <?= json_encode(base_url('news/datatables')) ?>;

    function escHtml(s) {
        return String(s || '').replace(/[&<>"']/g, m => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]));
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery === 'undefined' || !jQuery.fn.DataTable) return;

        const dt = jQuery('#newsServerTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            pageLength: 15,
            lengthMenu: [[10, 15, 25, 50], [10, 15, 25, 50]],
            order: [[0, 'desc']],
            dom: '<"d-flex flex-wrap justify-content-between align-items-center mb-2"l>rt<"d-flex flex-wrap justify-content-between align-items-center mt-3"ip>',
            ajax: {
                url: DATATABLES_URL,
                type: 'GET',
                data: function(d) {
                    d.category = CURRENT_CAT;
                },
                dataSrc: function(json) {
                    const badge = document.getElementById('newsTotalBadge');
                    if (badge && json) {
                        const filtered = Number(json.recordsFiltered || 0).toLocaleString();
                        const total = Number(json.recordsTotal || 0).toLocaleString();
                        badge.innerHTML = `<i class="fa-solid fa-newspaper me-1"></i> พบ ${filtered} รายการ (ทั้งหมด ${total})`;
                    }
                    return json.data || [];
                }
            },
            columns: [
                {
                    data: 'date_label',
                    render: function(data) {
                        return `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-2 rounded-3 fw-bold" style="font-size:0.8rem; min-width:92px;"><i class="fa-regular fa-calendar me-1"></i>${escHtml(data)}</span>`;
                    }
                },
                {
                    data: 'title',
                    render: function(data, type, row) {
                        const thumb = row.cover_image
                            ? `<img src="${escHtml(row.cover_image)}" alt="" class="rounded-3 flex-shrink-0 d-none d-sm-block shadow-xs" style="width:76px;height:54px;object-fit:cover;border:1px solid rgba(0,0,0,0.06);" loading="lazy">`
                            : '';
                        const featBadge = row.is_featured
                            ? `<span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 fw-bold" style="font-size:0.7rem;"><i class="fa-solid fa-star text-danger me-1"></i>หัวข้อเด่น</span>`
                            : '';
                        const attachBadge = row.attach_count > 0
                            ? `<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-2 py-0.5" style="font-size:0.7rem;"><i class="fa-solid fa-paperclip me-1"></i>${row.attach_count} ไฟล์</span>`
                            : '';
                        const catBadge = `<span class="badge bg-light text-dark border rounded-pill px-2 py-0.5" style="font-size:0.7rem;"><i class="fa-solid fa-tag text-success me-1"></i>${escHtml(row.category)}</span>`;
                        const summary = row.summary
                            ? `<div class="text-muted small mt-1" style="font-size:0.8rem;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">${escHtml(row.summary)}</div>`
                            : '';
                        return `
                            <div class="d-flex align-items-start gap-3">
                                ${thumb}
                                <div class="min-w-0">
                                    <div class="d-flex flex-wrap align-items-center gap-1.5 mb-1">${catBadge}${featBadge}${attachBadge}</div>
                                    <a href="${escHtml(row.detail_url)}" class="fw-bold text-dark text-decoration-none d-block" style="font-size:0.96rem;line-height:1.4;">${escHtml(data)}</a>
                                    ${summary}
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'views',
                    className: 'text-center',
                    render: function(data) {
                        return `<span class="text-secondary small fw-semibold"><i class="fa-regular fa-eye me-1 text-success"></i>${Number(data || 0).toLocaleString()}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    className: 'text-end',
                    render: function(data, type, row) {
                        let html = `<div class="d-inline-flex align-items-center gap-1.5">`;
                        html += `<a href="${escHtml(row.detail_url)}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold" style="font-size:0.8rem;">อ่านข่าว</a>`;
                        if (IS_OFFICER && !row.is_prd) {
                            const starCls = row.is_featured ? 'btn-warning text-dark' : 'btn-outline-secondary';
                            const starIco = row.is_featured ? 'fa-solid' : 'fa-regular';
                            html += `<button type="button" onclick="NewsStudio.toggleFeatured('${escHtml(row.id)}')" class="btn btn-sm ${starCls} rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width:30px;height:30px;" title="ตั้ง/ยกเลิกหัวข้อเด่น"><i class="${starIco} fa-star" style="font-size:0.75rem;"></i></button>`;
                            html += `<button type="button" onclick="NewsStudio.open('${escHtml(row.id)}')" class="btn btn-sm btn-outline-info rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width:30px;height:30px;" title="แก้ไข"><i class="fa-solid fa-pen text-dark" style="font-size:0.72rem;"></i></button>`;
                            html += `<button type="button" onclick="NewsStudio.deleteNews('${escHtml(row.id)}')" class="btn btn-sm btn-outline-danger rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width:30px;height:30px;" title="ลบ"><i class="fa-solid fa-trash" style="font-size:0.72rem;"></i></button>`;
                        }
                        html += `</div>`;
                        return html;
                    }
                }
            ],
            language: {
                processing: '<div class="py-2 px-3 rounded-pill bg-white shadow-sm border text-success fw-bold small"><i class="fa-solid fa-spinner fa-spin me-1"></i>กำลังโหลดข้อมูล...</div>',
                lengthMenu: 'แสดง _MENU_ รายการ/หน้า',
                zeroRecords: 'ไม่พบข่าวสารที่ตรงกับคำค้นหา',
                info: 'แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ',
                infoEmpty: 'ไม่มีรายการข่าวสาร',
                infoFiltered: '(กรองจากทั้งหมด _MAX_ รายการ)',
                paginate: { first: 'หน้าแรก', last: 'หน้าสุดท้าย', next: 'ถัดไป ›', previous: '‹ ก่อนหน้า' }
            }
        });

        // เชื่อมช่องค้นหาแบบ Debounce 300ms เพื่อลดการยิง server ซ้ำซ้อน
        let searchTimer = null;
        const searchInput = document.getElementById('newsServerSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                const val = this.value;
                searchTimer = setTimeout(() => {
                    dt.search(val).draw();
                }, 300);
            });
        }
    });
})();
</script>

<?= $this->endSection() ?>

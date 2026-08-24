<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isOfficer = session()->get('isLoggedIn');
$isProcurementCat = !empty($isProcurementCat);
?>

<!-- DataTables CSS for Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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
                    <?php if ($isProcurementCat): ?>
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
                    <?php if ($isProcurementCat): ?>
                        ข้อมูลโครงการจัดซื้อจัดจ้างภาครัฐ แผนการจัดซื้อจัดจ้าง และราคากลาง จากระบบ e-GP กรมบัญชีกลาง (เฉพาะหน่วยงานในจังหวัดพัทลุง)
                    <?php else: ?>
                        ติดตามข่าวสาร กิจกรรมจังหวัด ประกาศประกวดราคา และคู่มือประชาชนทั้งหมด
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($isOfficer): ?>
                <div class="d-flex gap-2">
                    <button type="button" onclick="NewsStudio.addCategory()" class="btn btn-outline-info px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                        <i class="fa-solid fa-tags"></i> เพิ่มหมวดหมู่
                    </button>
                    <button type="button" onclick="NewsStudio.open()" class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-dark d-flex align-items-center gap-2 shadow-lg hover-scale" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                        <i class="fa-solid fa-circle-plus fs-5"></i> + สร้างข่าวสารใหม่ทันที (Studio)
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Category Pills Filter -->
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-start align-items-center">
        <span class="text-muted fw-bold small me-2"><i class="fa-solid fa-filter text-primary me-1"></i>หมวดหมู่:</span>
        <a href="<?= base_url('news') ?>" class="btn btn-sm px-3.5 py-1.5 rounded-pill news-cat-btn <?= empty($currentCat) ? 'active' : '' ?>">
            ทั้งหมด
        </a>
        <?php foreach ($categories as $cat): 
            $isActive = (strcasecmp(trim($currentCat ?? ''), trim($cat)) === 0);
            $isEgpCat = (mb_stripos($cat, 'จัดซื้อจัดจ้าง') !== false || mb_stripos($cat, 'e-gp') !== false);
        ?>
            <a href="<?= base_url('news?category=' . urlencode($cat)) ?>" class="btn btn-sm px-3.5 py-1.5 rounded-pill news-cat-btn <?= $isActive ? 'active' : '' ?>">
                <?php if ($isEgpCat): ?><i class="fa-solid fa-gavel text-warning me-1"></i><?php endif; ?>
                <?= esc($cat) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if ($isProcurementCat): ?>
        <!-- ============================================================= -->
        <!-- CLEAN, SIMPLE & EYE-SOOTHING e-GP DATATABLES SERVERSIDE UI -->
        <!-- ============================================================= -->
        <div class="card border-0 rounded-4 shadow-sm mb-5 overflow-hidden" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
            
            <!-- Top Controls Header -->
            <div class="p-3.5 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom" style="background-color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-circle-check text-success me-1"></i> e-GP DataTables (Server-side)
                    </span>
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-location-dot me-1"></i> พื้นที่จังหวัดพัทลุง
                    </span>
                </div>
            </div>

            <!-- Clean Table with DataTables -->
            <div class="p-3 p-md-4">
                <div class="table-responsive">
                    <table class="table egp-clean-table m-0" id="egpTable">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 70px;">ลำดับ</th>
                                <th style="width: 170px;">หน่วยงาน</th>
                                <th style="width: 170px;">หน่วยจัดซื้อ</th>
                                <th>ชื่อโครงการ</th>
                                <th class="text-center" style="width: 160px;">วงเงินงบประมาณ<br><span style="font-size: 0.8rem; font-weight: 500;">(บาท)</span></th>
                                <th class="text-center" style="width: 190px;">สถานะโครงการ</th>
                                <th class="text-center" style="width: 90px;">ดูข้อมูล</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated via DataTables Server-side AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-3 bg-light border-top text-center text-muted small">
                <i class="fa-solid fa-circle-info text-primary me-1"></i> เชื่อมโยงข้อมูลจัดซื้อจัดจ้างภาครัฐ กรมบัญชีกลาง (e-GP) ของหน่วยงานในจังหวัดพัทลุง
            </div>
        </div>

    <?php endif; ?>

    <!-- Standard News List View -->
    <div class="card border-0 rounded-4 shadow-sm overflow-hidden mb-5" style="background: var(--card-bg, #ffffff); border: 1px solid rgba(15,23,42,0.08) !important;">
        <?php if ($isProcurementCat): ?>
            <div class="card-header bg-light py-3 px-4 border-bottom">
                <h6 class="fw-bold m-0 text-dark">
                    <i class="fa-solid fa-file-invoice text-primary me-1"></i> ประกาศจัดซื้อจัดจ้างและสรุปผล (สขร.1) เพิ่มเติมจากสำนักงาน
                </h6>
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
                    $nDate = !empty($item['created_at']) ? date('d/m/Y', strtotime($item['created_at'])) : date('d/m/Y');
                    $attachCount = !empty($item['attachments']) ? count($item['attachments']) : 0;
                    $views = number_format($item['views'] ?? 1);
                ?>
                    <div class="list-group-item list-group-item-action p-3.5 p-md-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 border-bottom transition-all hover-bg-light" style="border-color: rgba(0,0,0,0.05) !important;">
                        
                        <!-- Left: Date & Category & Title -->
                        <div class="d-flex align-items-start gap-3 flex-grow-1 overflow-hidden">
                            <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-3 fw-bold flex-shrink-0 text-center" style="min-width: 95px; font-size: 0.82rem;">
                                <i class="fa-regular fa-calendar me-1"></i> <?= $nDate ?>
                            </div>
                            
                            <div class="overflow-hidden">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-light text-dark border px-2.5 py-0.5 rounded-pill small" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-tag me-1 text-primary"></i> <?= esc($item['category'] ?? 'ข่าวประกาศ') ?>
                                    </span>
                                    <?php if (!empty($item['is_pinned'])): ?>
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

                            <?php if ($isOfficer): ?>
                                <div class="d-flex gap-1 ms-1">
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

<!-- DataTables Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    if ($('#egpTable').length) {
        $('#egpTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('api/egp/datatable') ?>',
                type: 'POST'
            },
            columns: [
                { data: 'no', className: 'text-center', width: '65px', orderable: true },
                { data: 'dept_name', width: '160px' },
                { data: 'procure_unit', width: '160px' },
                { data: 'project_name' },
                { data: 'budget', className: 'text-end pe-4 fw-bold text-dark', width: '150px' },
                { data: 'status', className: 'text-center', width: '180px' },
                { data: 'action', className: 'text-center', width: '80px', orderable: false }
            ],
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: {
                processing: '<div class="py-3 text-primary"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><div class="mt-2 small fw-bold">กำลังโหลดข้อมูล e-GP...</div></div>',
                search: '<i class="fa-solid fa-search text-primary me-1"></i> ค้นหาโครงการ:',
                searchPlaceholder: 'พิมพ์ชื่อโครงการ, รหัส หรือหน่วยงาน...',
                lengthMenu: 'แสดง _MENU_ แถวต่อหน้า',
                info: 'แสดงโครงการที่ _START_ ถึง _END_ จากทั้งหมด _TOTAL_ โครงการ',
                infoEmpty: 'ไม่พบรายการข้อมูล',
                infoFiltered: '(กรองจากทั้งหมด _MAX_ รายการ)',
                zeroRecords: '<div class="py-4 text-muted"><i class="fa-solid fa-folder-open fs-2 d-block mb-2 opacity-50"></i>ไม่พบข้อมูลโครงการที่ค้นหา</div>',
                paginate: {
                    first: '<i class="fa-solid fa-angles-left"></i>',
                    last: '<i class="fa-solid fa-angles-right"></i>',
                    next: '<i class="fa-solid fa-chevron-right"></i>',
                    previous: '<i class="fa-solid fa-chevron-left"></i>'
                }
            }
        });
    }
});
</script>

<?= $this->endSection() ?>

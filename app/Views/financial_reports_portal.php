<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$years = $years ?? [];
$selectedYear = $selectedYear ?? '';
$months = $months ?? [];
$selectedMonth = $selectedMonth ?? '';
$departments = $departments ?? [];
$selectedDept = $selectedDept ?? '';
$keyword = $keyword ?? '';
$items = $items ?? [];
?>

<!-- Include DataTables Bootstrap 5 & jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
.financial-header-banner {
    background: linear-gradient(135deg, #09324d 0%, #0d5c75 50%, #178385 100%);
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(13, 92, 117, 0.25);
}
.financial-header-banner::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
    border-radius: 50%;
}
#financialTable {
    border-collapse: separate !important;
    border-spacing: 0;
    width: 100% !important;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 0.5rem;
    overflow: hidden;
}
#financialTable thead th {
    background: #0d5c75 !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 0.9rem 1.25rem;
    border: none !important;
    font-size: 1rem;
}
#financialTable tbody tr:nth-of-type(odd) td {
    background-color: rgba(248, 249, 250, 0.85) !important;
}
#financialTable tbody tr:nth-of-type(even) td {
    background-color: #ffffff !important;
}
#financialTable tbody td {
    padding: 1rem 1.25rem;
    vertical-align: middle;
    border-bottom: 1px solid #eaf0f6;
    font-size: 0.96rem;
    color: #1e293b;
    transition: background-color 0.15s ease;
}
#financialTable tbody tr:hover td {
    background-color: #e0f2fe !important;
}
.fin-row-title {
    color: #1e293b !important;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}
.fin-row-title:hover {
    color: #0284c7 !important;
    text-decoration: underline !important;
}
.btn-download-pdf {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff !important;
    border: none;
    border-radius: 50px;
    padding: 0.45rem 1.1rem;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
    box-shadow: 0 2px 6px rgba(239, 68, 68, 0.25);
}
.btn-download-pdf:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
    color: #ffffff !important;
}
/* DataTables Pagination & Controls */
.dataTables_wrapper .dataTables_info {
    color: #64748b !important;
    font-weight: 500;
    padding-top: 0.5rem !important;
}
.dataTables_wrapper .dataTables_length select {
    border-radius: 0.375rem;
    padding: 0.35rem 2.25rem 0.35rem 0.75rem;
    border-color: #cbd5e1;
    background-color: #ffffff;
}
.dataTables_paginate .pagination .page-item .page-link {
    border-radius: 0.35rem;
    margin: 0 0.15rem;
    color: #0d5c75;
    font-weight: 600;
    border-color: #e2e8f0;
}
.dataTables_paginate .pagination .page-item.active .page-link {
    background: #0d5c75 !important;
    border-color: #0d5c75 !important;
    color: #ffffff !important;
}
</style>

<div class="container my-5 pt-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb m-0 small">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none fw-bold"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('procurement') ?>" class="text-secondary text-decoration-none">ศูนย์ข้อมูลความโปร่งใส</a></li>
            <li class="breadcrumb-item active text-secondary" aria-current="page">รายงานทางการเงินและงบทดลอง</li>
        </ol>
    </nav>

    <!-- Top Hero Banner -->
    <div class="financial-header-banner text-center py-4 px-4 text-white mb-4 d-flex flex-column align-items-center justify-content-center">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.85rem;">
                <i class="fa-solid fa-scale-balanced me-1"></i> ความโปร่งใสและการเปิดเผยข้อมูลงบประมาณ (ITA O18-O20)
            </span>
        </div>
        <h1 class="fw-bold m-0 text-white d-flex align-items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-warning"></i>
            <span>รายงานทางการเงินและงบทดลอง</span>
        </h1>
        <p class="text-white-50 mt-2 mb-3 small">รายงานงบทดลอง งบกรมจังหวัดพัทลุง และงบสำนักงานจังหวัดพัทลุง ประจำเดือนและประจำปีงบประมาณ</p>

        <!-- Quick Switch Navigation -->
        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="<?= base_url('procurement') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 opacity-90 hover-opacity-100">
                <i class="fa-solid fa-gavel text-warning"></i> ประกาศจัดซื้อจัดจ้าง (e-GP)
            </a>
            <a href="<?= base_url('news?category=' . urlencode('รายงานทางการเงิน')) ?>" class="btn btn-sm btn-light text-dark rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-chart-line text-primary"></i> รายงานงบทดลองทั้งหมด
            </a>
        </div>
    </div>

    <!-- Filter Toolbar Box -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 p-4" style="background: var(--card-bg, #ffffff); border: 1px solid var(--glass-border) !important;">
        <div class="row g-3 align-items-end">
            <!-- Year Filter -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-secondary mb-1">ปีงบประมาณ (พ.ศ.) :</label>
                <select id="filterYear" class="form-select rounded-3 shadow-sm py-2 border-primary-subtle" style="font-size: 0.95rem;">
                    <option value="">ทั้งหมดทุกปี (All Years)</option>
                    <?php foreach ($years as $y): ?>
                        <option value="<?= esc($y) ?>" <?= ($selectedYear === $y) ? 'selected' : '' ?>>ปี พ.ศ. <?= esc($y) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Month Filter -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-secondary mb-1">ประจำเดือน :</label>
                <select id="filterMonth" class="form-select rounded-3 shadow-sm py-2 border-primary-subtle" style="font-size: 0.95rem;">
                    <option value="">ทั้งหมดทุกเดือน (All Months)</option>
                    <?php foreach ($months as $m): ?>
                        <option value="<?= esc($m) ?>" <?= ($selectedMonth === $m) ? 'selected' : '' ?>>เดือน<?= esc($m) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Department Filter -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-secondary mb-1">หน่วยงาน/ประเภทงบ :</label>
                <select id="filterDept" class="form-select rounded-3 shadow-sm py-2 border-primary-subtle" style="font-size: 0.95rem;">
                    <option value="">ทุกหน่วยงาน (All)</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= esc($d) ?>" <?= ($selectedDept === $d) ? 'selected' : '' ?>><?= esc($d) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Search box -->
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-secondary mb-1">ค้นหาคำในชื่อรายงาน :</label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden border border-primary-subtle">
                    <input type="text" id="customSearchInput" class="form-control border-0 py-2 px-3" placeholder="พิมพ์คำค้นหา..." value="<?= esc($keyword) ?>" autocomplete="off" style="font-size: 0.95rem;">
                    <button class="btn btn-primary px-3 fw-bold" id="btnCustomSearch" style="background: linear-gradient(135deg, #0d5c75, #178385); border: none;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Total Count Info Bar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: rgba(15,23,42,0.08) !important;">
            <div class="fw-bold fs-6 d-flex align-items-center gap-2" style="color: #0d5c75;">
                <i class="fa-solid fa-file-invoice text-warning"></i>
                <span>จำนวนรายงานทางการเงินทั้งหมด <span id="displayTotalCount" class="badge bg-danger rounded-pill px-2 fs-7"><?= count($items) ?></span> รายการ</span>
            </div>
            <div class="text-muted small">
                <i class="fa-solid fa-circle-check text-success me-1"></i> เปิดเผยตามมาตรฐานระบบบริหารการเงินการคลังภาครัฐแบบอิเล็กทรอนิกส์ (GFMIS)
            </div>
        </div>
    </div>

    <!-- DataTables Table Container -->
    <div class="card border-0 rounded-4 shadow-sm p-4" style="background: var(--card-bg, #ffffff); border: 1px solid var(--glass-border) !important;">
        <div class="table-responsive">
            <table id="financialTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="min-width: 500px;">ชื่อรายงาน / รายการเอกสาร</th>
                        <th style="min-width: 180px;">หน่วยงาน</th>
                        <th style="min-width: 120px;" class="text-center">ปี พ.ศ.</th>
                        <th style="display: none;">เดือน (Hidden)</th>
                        <th style="min-width: 140px;" class="text-center">ดาวน์โหลด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): 
                        $docUrl = !empty($item['doc_path']) ? $item['doc_path'] : '#';
                        $yearVal = $item['fiscal_year'] ?? '';
                        $monthVal = $item['report_month'] ?? '';
                        $fileSize = !empty($item['file_size']) ? $item['file_size'] : '';
                        $downloads = (int)($item['download_count'] ?? 0);
                    ?>
                        <tr>
                            <!-- Column 1: Title + badges -->
                            <td>
                                <div class="d-flex align-items-baseline gap-2">
                                    <i class="fa-solid fa-file-pdf text-danger" style="font-size: 1.1rem;"></i>
                                    <div>
                                        <a href="<?= esc($docUrl) ?>" target="_blank" class="fin-row-title d-block mb-1">
                                            <?= esc($item['title']) ?>
                                        </a>
                                        <div class="d-flex flex-wrap align-items-center gap-2 small text-muted">
                                            <?php if (!empty($monthVal)): ?>
                                                <span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill">เดือน<?= esc($monthVal) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($fileSize)): ?>
                                                <span><i class="fa-solid fa-paperclip me-1"></i>ขนาด: <?= esc($fileSize) ?></span>
                                            <?php endif; ?>
                                            <?php if ($downloads > 0): ?>
                                                <span><i class="fa-solid fa-download me-1"></i>ดาวน์โหลด: <?= number_format($downloads) ?> ครั้ง</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Column 2: Department -->
                            <td>
                                <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill fw-medium" style="font-size: 0.85rem;">
                                    <?= esc($item['department'] ?? 'สำนักงานจังหวัดพัทลุง') ?>
                                </span>
                            </td>

                            <!-- Column 3: Fiscal Year -->
                            <td class="text-center fw-bold text-secondary">
                                <?= esc($yearVal ?: '-') ?>
                            </td>

                            <!-- Column 4: Hidden Month for filtering -->
                            <td style="display: none;"><?= esc($monthVal) ?></td>

                            <!-- Column 5: Direct Download Action -->
                            <td class="text-center">
                                <a href="<?= esc($docUrl) ?>" target="_blank" class="btn-download-pdf">
                                    <i class="fa-solid fa-download"></i> ดาวน์โหลด PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#financialTable').DataTable({
        "language": {
            "sEmptyTable":     "ไม่พบข้อมูลรายงานทางการเงินในระบบ",
            "sInfo":           "หน้า _PAGE_ จากทั้งหมด _PAGES_ หน้า (แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ)",
            "sInfoEmpty":      "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
            "sInfoFiltered":   "(กรองจากทั้งหมด _MAX_ รายการ)",
            "sLengthMenu":     "_MENU_ รายการ/หน้า",
            "sLoadingRecords": "กำลังโหลดข้อมูล...",
            "sProcessing":     "กำลังดำเนินการ...",
            "sSearch":         "ค้นหา:",
            "sZeroRecords":    "ไม่พบคำค้นหาที่ตรงกันในตาราง",
            "oPaginate": {
                "sFirst":    "«",
                "sLast":     "»",
                "sNext":     "›",
                "sPrevious": "‹"
            }
        },
        "pageLength": 12,
        "lengthMenu": [ [10, 12, 20, 50, -1], [10, 12, 20, 50, "ทั้งหมด"] ],
        "dom": "<'row d-none'<'col-sm-12'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row mt-4 pt-3 border-top align-items-center'<'col-sm-12 col-md-5 text-muted'i><'col-sm-12 col-md-7 d-flex justify-content-md-end align-items-center gap-3'lp>>",
        "order": [[ 2, "desc" ]], // Sort by year descending
        "ordering": true,
        "columnDefs": [
            { "orderable": false, "targets": [0, 4] }
        ],
        "drawCallback": function(settings) {
            var count = settings.aiDisplay.length;
            $('#displayTotalCount').text(count.toLocaleString());
        }
    });

    // Custom filtering by Year
    $('#filterYear').on('change', function() {
        var val = $(this).val();
        table.column(2).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '', true, false).draw();
    });

    // Custom filtering by Month (Column 3)
    $('#filterMonth').on('change', function() {
        var val = $(this).val();
        table.column(3).search(val ? val : '', true, false).draw();
    });

    // Custom filtering by Department (Column 1)
    $('#filterDept').on('change', function() {
        var val = $(this).val();
        table.column(1).search(val ? val : '', true, false).draw();
    });

    // Custom Search Input
    $('#customSearchInput').on('keyup', function(e) {
        table.search($(this).val()).draw();
    });
    $('#btnCustomSearch').on('click', function() {
        table.search($('#customSearchInput').val()).draw();
    });
});
</script>

<?= $this->endSection() ?>

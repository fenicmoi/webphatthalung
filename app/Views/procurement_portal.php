<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
helper('settings');
$isOfficer = session()->get('isLoggedIn');
$categories = $categories ?? get_procurement_categories();
$selectedCat = $selectedCat ?? 'all';
$items = $items ?? [];

// Helper สำหรับแปลงวันที่เป็นภาษาไทยแบบย่อ (เช่น 4 ส.ค. 69)
if (!function_exists('format_thai_date_short')) {
    function format_thai_date_short($dateStr) {
        $timestamp = strtotime($dateStr);
        if (!$timestamp) return $dateStr;
        $months = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $day = (int)date('j', $timestamp);
        $month = $months[(int)date('n', $timestamp)];
        $year = ((int)date('Y', $timestamp) + 543) % 100;
        return "$day $month $year";
    }
}
?>

<!-- Include DataTables Bootstrap 5 & jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
/* --- Custom DataTables styling inspired by e-GP Portal Reference --- */
.procurement-header-banner {
    background: linear-gradient(135deg, #072a44 0%, #0b5e7a 50%, #118196 100%);
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(11, 94, 122, 0.25);
}
.procurement-header-banner::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}
#procurementTable {
    border-collapse: separate !important;
    border-spacing: 0;
    width: 100% !important;
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 0.5rem;
    overflow: hidden;
}
#procurementTable thead th {
    background: #0b5e7a !important;
    color: #ffffff !important;
    font-weight: 600;
    padding: 0.9rem 1.25rem;
    border: none !important;
    font-size: 1.02rem;
}
#procurementTable tbody tr:nth-of-type(odd) td {
    background-color: rgba(248, 249, 250, 0.85) !important;
}
#procurementTable tbody tr:nth-of-type(even) td {
    background-color: #ffffff !important;
}
#procurementTable tbody td {
    padding: 1.05rem 1.25rem;
    vertical-align: middle;
    border-bottom: 1px solid #eaf0f6;
    font-size: 0.96rem;
    color: #1e293b;
    transition: background-color 0.15s ease;
}
#procurementTable tbody tr:hover td {
    background-color: #e0f2fe !important;
    cursor: pointer;
}
.proc-row-title {
    color: #1e293b !important;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s ease;
}
.proc-row-title:hover {
    color: #0284c7 !important;
    text-decoration: underline !important;
}
.badge-new {
    background-color: #ef4444 !important;
    color: #ffffff !important;
    font-size: 0.72rem;
    padding: 0.2rem 0.45rem;
    border-radius: 0.25rem;
    font-weight: 700;
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
    color: #0b5e7a;
    font-weight: 600;
    border-color: #e2e8f0;
}
.dataTables_paginate .pagination .page-item.active .page-link {
    background: #0b5e7a !important;
    border-color: #0b5e7a !important;
    color: #ffffff !important;
    box-shadow: 0 2px 8px rgba(11, 94, 122, 0.3);
}

/* Dark Mode Overrides */
[data-theme="dark"] .procurement-header-banner {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
}
[data-theme="dark"] #procurementTable {
    border-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] #procurementTable tbody tr:nth-of-type(odd) td {
    background-color: rgba(15, 23, 42, 0.65) !important;
}
[data-theme="dark"] #procurementTable tbody tr:nth-of-type(even) td {
    background-color: rgba(30, 41, 59, 0.65) !important;
}
[data-theme="dark"] #procurementTable tbody td {
    color: #f8fafc !important;
    border-bottom-color: rgba(255, 255, 255, 0.08);
}
[data-theme="dark"] #procurementTable tbody tr:hover td {
    background-color: rgba(56, 189, 248, 0.15) !important;
}
[data-theme="dark"] .proc-row-title {
    color: #f8fafc !important;
}
[data-theme="dark"] .proc-row-title:hover {
    color: #38bdf8 !important;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_info,
[data-theme="dark"] .dataTables_wrapper .dataTables_length label {
    color: #94a3b8 !important;
}
[data-theme="dark"] .dataTables_wrapper .dataTables_length select {
    background-color: #1e293b !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
}
[data-theme="dark"] .dataTables_paginate .pagination .page-item .page-link {
    background-color: #1e293b;
    border-color: rgba(255,255,255,0.15);
    color: #38bdf8;
}
</style>

<div class="container my-5 pt-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb m-0 small">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none fw-bold"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
            <li class="breadcrumb-item active text-secondary" aria-current="page">ศูนย์ข้อมูลข่าวจัดซื้อจัดจ้าง (e-GP)</li>
        </ol>
    </nav>

    <!-- Top Hero Title Bar (Inspired by Reference Image) -->
    <div class="procurement-header-banner text-center py-4 px-4 text-white mb-4 d-flex flex-column align-items-center justify-content-center">
        <h1 class="fw-bold m-0 text-white d-flex align-items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-warning"></i>
            <span>ข่าวจัดซื้อจัดจ้าง</span>
            <?php if ($isOfficer): ?>
                <span class="badge bg-success text-dark fs-7 ms-2"><i class="fa-solid fa-user-shield me-1"></i>On-Page CMS</span>
            <?php endif; ?>
        </h1>
        <p class="text-white-50 mt-2 mb-0 small">ศูนย์ข้อมูลประกวดราคา เปิดเผยราคากลาง และสรุปผล สขร.1 จังหวัดพัทลุง</p>
    </div>

    <!-- Filter Toolbar Box -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 p-4" style="background: var(--card-bg, #ffffff); border: 1px solid var(--glass-border) !important;">
        <div class="row g-3 align-items-center justify-content-between">
            <!-- Category Dropdown -->
            <div class="col-md-5 col-lg-4">
                <label class="form-label small fw-bold text-secondary mb-1">หมวดหมู่ข่าวจัดซื้อจัดจ้าง :</label>
                <select id="filterCategory" class="form-select rounded-3 shadow-sm py-2 border-primary-subtle" style="font-size: 0.95rem;">
                    <option value="">ทั้งหมด (All Categories)</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat) ?>" <?= ($selectedCat === $cat) ? 'selected' : '' ?>><?= esc($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Search box & Submit Button -->
            <div class="col-md-7 col-lg-5">
                <label class="form-label small fw-bold text-secondary mb-1">ค้นหาประกาศ :</label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden border border-primary-subtle">
                    <input type="text" id="customSearchInput" class="form-control border-0 py-2 px-3" placeholder="คำค้นหา..." autocomplete="off" style="font-size: 0.95rem;">
                    <button class="btn btn-primary px-4 fw-bold" id="btnCustomSearch" style="background: linear-gradient(135deg, #0b5e7a, #118196); border: none;">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> ค้นหา
                    </button>
                </div>
            </div>

            <!-- Officer On-Page Action -->
            <?php if ($isOfficer): ?>
            <div class="col-12 col-lg-3 text-lg-end mt-3 mt-lg-0">
                <label class="form-label small fw-bold text-transparent d-none d-lg-block mb-1">&nbsp;</label>
                <button type="button" onclick="ProcurementStudio.open(null, $('#filterCategory').val())" class="btn btn-warning w-100 rounded-3 py-2 fw-bold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2 hover-scale" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                    <i class="fa-solid fa-circle-plus fs-5"></i> + เพิ่มประกาศใหม่ (Studio)
                </button>
            </div>
            <?php endif; ?>
        </div>

        <!-- Total Count Info Bar -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: rgba(15,23,42,0.08) !important;">
            <div class="fw-bold fs-6 d-flex align-items-center gap-2" style="color: #0b5e7a;">
                <i class="fa-solid fa-table-list text-warning"></i>
                <span>จำนวนข่าวจัดซื้อจัดจ้างทั้งหมด <span id="displayTotalCount" class="badge bg-danger rounded-pill px-2 fs-7"><?= count($items) ?></span> รายการ</span>
            </div>
            <div class="text-muted small">
                <i class="fa-solid fa-circle-check text-success me-1"></i> เปิดเผยข้อมูลตามมาตรฐานความโปร่งใส
            </div>
        </div>
    </div>

    <!-- DataTables Table Container -->
    <div class="card border-0 rounded-4 shadow-sm p-4" style="background: var(--card-bg, #ffffff); border: 1px solid var(--glass-border) !important;">
        <div class="table-responsive">
            <table id="procurementTable" class="table table-hover align-middle mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="min-width: 520px;">หัวข้อข่าว</th>
                        <th style="display: none;">หมวดหมู่ (Hidden for filter)</th>
                        <th style="min-width: 130px;" class="text-end">วันที่</th>
                        <?php if ($isOfficer): ?>
                            <th style="min-width: 100px; width: 100px;" class="text-center">จัดการ</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): 
                        $attUrl = (!empty($item['attachment_url']) && (strpos($item['attachment_url'], 'http') === 0 || strpos($item['attachment_url'], 'uploads/') === 0 || strpos($item['attachment_url'], 'assets/') === 0)) ? ((strpos($item['attachment_url'], 'http') === 0) ? $item['attachment_url'] : base_url($item['attachment_url'])) : '#';
                        $timestamp = strtotime($item['date'] ?? 'now');
                        $isRecent = (time() - $timestamp) <= (14 * 86400); // within 14 days
                    ?>
                        <tr>
                            <!-- Column 1: Title + Views + Badge -->
                            <td onclick="window.open('<?= $attUrl ?>', '_blank')" style="cursor: pointer;">
                                <div class="d-flex align-items-baseline gap-2">
                                    <span class="text-secondary fw-bold pe-1" style="font-size: 1.1rem;">&gt;</span>
                                    <a href="<?= $attUrl ?>" target="_blank" onclick="event.stopPropagation()" class="proc-row-title d-inline">
                                        <?= esc($item['title']) ?>
                                    </a>
                                    <span class="text-secondary small text-nowrap">(ดู : <?= number_format($item['views'] ?? 1) ?>)</span>
                                    <?php if ($isRecent): ?>
                                        <span class="badge badge-new text-nowrap ms-1">NEW</span>
                                    <?php endif; ?>
                                    <?php if (!empty($item['budget']) && $item['budget'] !== '-'): ?>
                                        <span class="badge bg-info-subtle text-info border text-nowrap ms-1 small">งบประมาณ: <?= esc($item['budget']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Column 2: Hidden Category Name for DataTables filtering -->
                            <td style="display: none;"><?= esc($item['category'] ?? '') ?></td>

                            <!-- Column 3: Thai Short Date -->
                            <td class="text-end text-secondary fw-semibold text-nowrap" data-order="<?= $timestamp ?>">
                                <?= format_thai_date_short($item['date'] ?? 'now') ?>
                            </td>

                            <!-- Column 4: Officer Management Buttons -->
                            <?php if ($isOfficer): ?>
                                <td class="text-center" onclick="event.stopPropagation()">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" onclick="ProcurementStudio.open('<?= $item['id'] ?>')" class="btn btn-sm btn-info text-dark rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="แก้ไข">
                                            <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                        </button>
                                        <button type="button" onclick="ProcurementStudio.deleteItem('<?= $item['id'] ?>', '<?= esc($item['title'], 'js') ?>')" class="btn btn-sm btn-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="ลบ">
                                            <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var table = $('#procurementTable').DataTable({
        "language": {
            "sEmptyTable":     "ไม่มีข้อมูลข่าวจัดซื้อจัดจ้างในระบบ",
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
        "order": [[ <?= $isOfficer ? '2' : '2' ?>, "desc" ]], // Sort by date descending
        "ordering": true,
        "columnDefs": [
            { "orderable": false, "targets": [0<?= $isOfficer ? ', 3' : '' ?>] }
        ],
        "drawCallback": function(settings) {
            var count = settings.aiDisplay.length;
            $('#displayTotalCount').text(count.toLocaleString());
        }
    });

    // Custom filtering by Category Dropdown
    $('#filterCategory').on('change', function() {
        var val = $(this).val();
        if (val === '') {
            table.column(1).search('', true, false).draw();
        } else {
            // Exact search match on column 1
            table.column(1).search('^' + $.fn.dataTable.util.escapeRegex(val) + '$', true, false).draw();
        }
    });

    // Custom search box input
    $('#customSearchInput').on('keyup change clear', function() {
        table.search(this.value).draw();
    });
    
    $('#btnCustomSearch').on('click', function(e) {
        e.preventDefault();
        table.search($('#customSearchInput').val()).draw();
    });

    // If URL opened with a specific category selected (not 'all'), trigger initial filter
    var initialCat = $('#filterCategory').val();
    if (initialCat && initialCat !== '' && initialCat !== 'all') {
        table.column(1).search('^' + $.fn.dataTable.util.escapeRegex(initialCat) + '$', true, false).draw();
    }
});
</script>

<?= $this->endSection() ?>

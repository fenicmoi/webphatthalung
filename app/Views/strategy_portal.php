<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$vision = $vision ?? [];
$missions = $missions ?? [];
$core_values = $core_values ?? [];
$kpis = $kpis ?? [];
$pillars = $pillars ?? [];
$documents = $documents ?? [];
$isOfficer = $isOfficer ?? false;
?>

<style>
/* ==========================================================================
   STRATEGY PORTAL DESIGN SYSTEM (ROYAL NAVY, AMBER GOLD & EMERALD ECO)
   ========================================================================== */
.strategy-hero {
    background: linear-gradient(135deg, #0b1e48 0%, #1e3a8a 50%, #0369a1 100%);
    position: relative;
    overflow: hidden;
    color: #ffffff;
    border-radius: 28px;
    padding: 3.5rem 2.5rem;
    box-shadow: 0 20px 45px rgba(11, 30, 72, 0.25);
    margin-bottom: 2.5rem;
}
.strategy-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 650px;
    height: 650px;
    background: radial-gradient(circle, rgba(217, 119, 6, 0.18) 0%, rgba(2, 132, 199, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.strategy-vision-box {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(16px);
    border: 2px solid rgba(251, 191, 36, 0.35);
    border-radius: 20px;
    padding: 1.8rem 2.2rem;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}
.strategy-vision-box::after {
    content: '“';
    position: absolute;
    top: -15px;
    left: 20px;
    font-size: 5rem;
    line-height: 1;
    font-family: serif;
    color: rgba(251, 191, 36, 0.4);
}
.pillar-card {
    background: var(--card-bg, #ffffff);
    border-radius: 22px;
    border: 1px solid rgba(0, 0, 0, 0.07);
    padding: 2rem;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}
.pillar-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}
.pillar-badge-num {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    font-weight: 800;
    color: #ffffff;
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
.kpi-metric-card {
    background: var(--card-bg, #ffffff);
    border-radius: 20px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    padding: 1.75rem 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(0,0,0,0.03);
}
.kpi-metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}
.doc-download-card {
    background: var(--card-bg, #ffffff);
    border-radius: 20px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    padding: 1.75rem;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.doc-download-card:hover {
    border-color: #38bdf8;
    box-shadow: 0 12px 30px rgba(2, 132, 199, 0.12);
    transform: translateY(-4px);
}
.nav-pills-strategy .nav-link {
    border-radius: 50px;
    padding: 0.65rem 1.4rem;
    font-weight: 700;
    font-size: 0.95rem;
    color: #475569;
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.08);
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    transition: all 0.25s ease;
}
.nav-pills-strategy .nav-link.active {
    background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(2, 132, 199, 0.35);
    border-color: transparent;
}
.flagship-banner {
    background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #047857 100%);
    color: #ffffff;
    border-radius: 22px;
    padding: 2.2rem;
    position: relative;
    overflow: hidden;
}
[data-theme="dark"] .pillar-card,
[data-theme="dark"] .kpi-metric-card,
[data-theme="dark"] .doc-download-card {
    background: #1e293b !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f8fafc !important;
}
[data-theme="dark"] .nav-pills-strategy .nav-link {
    background: #1e293b;
    color: #cbd5e1;
    border-color: rgba(255,255,255,0.1);
}
</style>

<div class="container py-4">

    <!-- ======================================================== -->
    <!-- COMPACT HEADER BANNER: ศูนย์เอกสารแผนพัฒนาและแผนปฏิบัติราชการประจำปี -->
    <!-- ======================================================== -->
    <section class="strategy-hero mb-4" style="padding: 1.75rem 2rem; border-radius: 22px;">
        <div class="position-relative z-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white text-opacity-75 text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าแรก</a></li>
                    <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">ศูนย์เอกสารแผนพัฒนาและแผนปฏิบัติราชการประจำปี</li>
                </ol>
            </nav>

            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning bg-opacity-20 border border-warning border-opacity-30 text-warning fw-bold small mb-2">
                        <i class="fa-solid fa-book-open-reader"></i> Official Plans &amp; E-Book Center
                    </div>
                    <h1 class="h3 fw-bold mb-1.5 text-white" style="letter-spacing: -0.01em;">
                        ศูนย์เอกสารแผนพัฒนาและแผนปฏิบัติราชการประจำปี
                    </h1>
                    <p class="text-white text-opacity-80 mb-0 small">
                        เอกสารฉบับทางการรับรองโดย สำนักงานจังหวัดพัทลุง • รองรับการเปิดอ่านแบบหนังสือ E-Book พลิกหน้า 3D และดาวน์โหลดไฟล์ PDF
                    </p>
                </div>

                <div class="col-lg-4 d-flex flex-wrap justify-content-lg-end align-items-center gap-2">
                    <?php if ($isOfficer): ?>
                        <button type="button" class="btn btn-warning btn-sm rounded-pill px-3.5 py-2 fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-1.5" onclick="StrategyDocStudio.createNew()">
                            <i class="fa-solid fa-circle-plus"></i> เพิ่มเอกสาร
                        </button>
                        <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1.5" onclick="CategoryStudio.open()">
                            <i class="fa-solid fa-folder-tree"></i> จัดการหมวดหมู่
                        </button>
                        <a href="<?= base_url('admin/strategy') ?>" class="btn btn-light btn-sm rounded-pill px-3 py-2 fw-bold text-dark d-inline-flex align-items-center gap-1.5" title="จัดการข้อมูลยุทธศาสตร์หลังบ้าน">
                            <i class="fa-solid fa-sliders text-primary"></i> หลังบ้าน
                        </a>
                    <?php else: ?>
                        <div class="px-3.5 py-2 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-20 text-white text-end">
                            <div class="small text-white text-opacity-75">เอกสารทั้งหมดในระบบ</div>
                            <div class="h4 fw-bold text-warning mb-0 lh-1"><?= number_format(count($documents)) ?> <span class="fs-6 fw-normal text-white">เล่ม</span></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- DOCUMENT DOWNLOAD & E-BOOK CENTER (ศูนย์ดาวน์โหลดแผนพัฒนาฯ) -->
    <!-- ======================================================== -->
    <section id="documentsSection" class="mb-5">

        <?php
            // แสดงการ์ดแนะนำด้านบนเฉพาะเอกสารที่ตั้งค่า "แผนแม่บทหลัก (is_featured)"
            // แต่ยังคงแสดงเอกสารทั้งหมดทุกรายการในตารางค้นหา/กรองหมวดหมู่ด้านล่างเพื่อไม่ให้เอกสารหายจากหมวดหมู่
            $featuredDoc = null;
            foreach ($documents as $d) {
                if (!empty($d['is_featured'])) {
                    $featuredDoc = $d;
                    break;
                }
            }
            $otherDocs = $documents;
        ?>

        <!-- 🌟 PRIMARY FOCUS: HERO FEATURED MASTER PLAN CARD -->
        <?php if ($featuredDoc): ?>
            <div class="card border-0 rounded-4 shadow-md overflow-hidden mb-4" style="background: linear-gradient(135deg, #0b1e48 0%, #1e3a8a 60%, #0369a1 100%); color: #ffffff;">
                <div class="card-body p-4 p-lg-4.5 position-relative">
                    <div class="row align-items-center g-4">
                        <!-- Left Icon Badge -->
                        <div class="col-auto">
                            <div class="p-3.5 rounded-4 bg-white bg-opacity-15 border border-white border-opacity-20 d-flex flex-column align-items-center justify-content-center text-center shadow-sm" style="width: 88px; height: 100px;">
                                <i class="fa-solid fa-file-pdf text-warning fs-1 mb-1"></i>
                                <span class="badge bg-warning text-dark fw-bold px-1.5 py-0.5" style="font-size: 0.68rem;">แนะนำ</span>
                            </div>
                        </div>

                        <!-- Center Info -->
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 small fw-bold">
                                    <i class="fa-solid fa-star me-1"></i> <?= esc($featuredDoc['category'] ?? 'เอกสารแผนพัฒนาจังหวัด') ?>
                                </span>
                                <span class="text-white text-opacity-75 small">
                                    <i class="fa-regular fa-calendar-check me-1"></i>พ.ศ. <?= esc($featuredDoc['year']) ?>
                                </span>
                            </div>
                            <h4 class="fw-bold text-white mb-2" style="line-height: 1.4;">
                                <?= esc($featuredDoc['title']) ?>
                            </h4>
                            <div class="d-flex flex-wrap align-items-center gap-3 text-white text-opacity-80 small">
                                <span><i class="fa-solid fa-hard-drive me-1 text-warning"></i>ขนาดไฟล์: <strong><?= esc($featuredDoc['file_size']) ?></strong></span>
                                <span><i class="fa-solid fa-book-open me-1 text-warning"></i>ความยาว: <strong><?= esc($featuredDoc['pages'] ?? '-') ?> หน้า</strong></span>
                                <span><i class="fa-solid fa-download me-1 text-warning"></i>ดาวน์โหลดแล้ว: <strong><?= number_format($featuredDoc['downloads'] ?? 0) ?> ครั้ง</strong></span>
                            </div>
                        </div>

                        <!-- Right Action Buttons -->
                        <div class="col-12 col-lg-auto d-flex flex-sm-row flex-lg-column gap-2.5">
                            <a href="<?= base_url('ebook/view?doc=' . urlencode($featuredDoc['id'])) ?>" target="_blank" class="btn btn-warning rounded-pill px-4 py-2.5 fw-bold text-dark shadow d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-book-open-reader"></i> เปิดอ่าน E-Book
                            </a>
                            <a href="<?= preg_match('#^https?://#i', $featuredDoc['file_url']) ? esc($featuredDoc['file_url']) : base_url($featuredDoc['file_url']) ?>" download class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center justify-content-center gap-2" onclick="trackStrategyDownload('<?= $featuredDoc['id'] ?>')">
                                <i class="fa-solid fa-download"></i> ดาวน์โหลด PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 📋 SECONDARY FOCUS: CLEAN LIST OF ANNUAL ACTION PLANS & REPORTS WITH REAL-TIME SEARCH -->
        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
            <div class="card-header bg-light bg-opacity-50 border-bottom px-4 py-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list-check text-primary"></i> แผนปฏิบัติราชการประจำปี & รายงานติดตามประเมินผล (M&E)
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 small fw-bold" id="docCountBadge">
                        แสดง <?= count($otherDocs) ?> รายการ
                    </span>
                </div>

                <!-- 🔍 REAL-TIME SEARCH & FILTER TOOLBAR -->
                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted ps-3">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 rounded-end-pill py-2 shadow-none" id="docSearchInput" placeholder="พิมพ์ค้นหาชื่อแผน, คำสำคัญ, หรือปีงบประมาณ..." oninput="filterStrategyDocs()">
                        </div>
                    </div>

                    <!-- Year Dropdown Filter -->
                    <div class="col-sm-6 col-md-3">
                        <select class="form-select rounded-pill py-2 shadow-none" id="docYearSelect" onchange="filterStrategyDocs()">
                            <option value="">ทุกปีงบประมาณ</option>
                            <?php
                                $years = array_unique(array_filter(array_column($documents, 'year')));
                                rsort($years);
                                foreach ($years as $yr):
                            ?>
                                <option value="<?= esc($yr) ?>">ปีงบประมาณ <?= esc($yr) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Category Pills / Reset -->
                    <div class="col-sm-6 col-md-3 text-sm-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 w-100 fw-bold d-inline-flex align-items-center justify-content-center gap-1.5" onclick="clearDocFilter()">
                            <i class="fa-solid fa-rotate-left"></i> ล้างการค้นหา
                        </button>
                    </div>
                </div>

                <!-- Category Filter Pills -->
                <div class="d-flex flex-wrap gap-1.5 pt-3" id="docCategoryPills">
                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fw-bold category-filter-btn active" data-category="" onclick="setDocCategoryFilter('', this)">
                        ทั้งหมด (<span id="totalDocCount"><?= count($otherDocs) ?></span>)
                    </button>
                    <?php 
                    $categories = $categories ?? (function_exists('get_strategy_categories') ? get_strategy_categories() : []);
                    foreach ($categories as $cat): 
                        $cName = is_array($cat) ? ($cat['name'] ?? '') : $cat;
                        $cIcon = is_array($cat) ? ($cat['icon'] ?? 'fa-solid fa-folder') : 'fa-solid fa-folder';
                        $cColor = is_array($cat) ? ($cat['badge_color'] ?? 'primary') : 'primary';
                        if (empty($cName)) continue;
                        $cCount = count(array_filter($otherDocs, fn($d) => ($d['category'] ?? '') === $cName));
                    ?>
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-bold category-filter-btn" data-category="<?= esc($cName) ?>" onclick="setDocCategoryFilter('<?= esc($cName) ?>', this)">
                            <i class="<?= esc($cIcon) ?> me-1 text-<?= esc($cColor) ?>"></i><?= esc($cName) ?>
                            <span class="badge bg-secondary bg-opacity-25 text-dark ms-1" style="font-size: 0.72rem;"><?= $cCount ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="list-group list-group-flush" id="strategyDocsList">
                <?php if (empty($otherDocs)): ?>
                    <div class="p-4 text-center text-muted" id="docEmptyMsg">ไม่มีเอกสารเพิ่มเติม</div>
                <?php else: ?>
                    <?php foreach ($otherDocs as $doc): ?>
                        <div class="list-group-item px-4 py-3.5 border-bottom transition-all hover-bg-light doc-list-row" data-title="<?= esc(mb_strtolower($doc['title'])) ?>" data-category="<?= esc($doc['category']) ?>" data-subcategory="<?= esc(mb_strtolower($doc['sub_category'] ?? '')) ?>" data-year="<?= esc($doc['year']) ?>">
                            <div class="row align-items-center g-3">
                                <!-- Year Badge -->
                                <div class="col-auto">
                                    <div class="p-2 rounded-3 text-center d-flex flex-column align-items-center justify-content-center" style="width: 60px; background: rgba(2, 132, 199, 0.08); color: #0284c7;">
                                        <span style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase;">ปีงบฯ</span>
                                        <span class="fw-bold fs-6 lh-1"><?= esc($doc['year']) ?></span>
                                    </div>
                                </div>

                                <!-- Title & Details -->
                                <div class="col">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <span class="badge bg-light text-primary border rounded-pill small px-2 py-0.5" style="font-size: 0.72rem;">
                                            <?= esc($doc['category']) ?>
                                        </span>
                                        <?php if (!empty($doc['sub_category'])): ?>
                                            <span class="badge bg-white text-secondary border rounded-pill small px-2 py-0.5" style="font-size: 0.72rem;">
                                                <i class="fa-solid fa-tag me-1 text-muted"></i><?= esc($doc['sub_category']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 doc-item-title" style="font-size: 0.96rem; line-height: 1.4;">
                                        <?= esc($doc['title']) ?>
                                    </h6>
                                    <div class="text-muted small d-flex flex-wrap gap-3" style="font-size: 0.8rem;">
                                        <span><i class="fa-solid fa-hard-drive me-1 text-secondary"></i><?= esc($doc['file_size']) ?></span>
                                        <span><i class="fa-solid fa-file-lines me-1 text-secondary"></i><?= esc($doc['pages'] ?? '-') ?> หน้า</span>
                                        <span><i class="fa-solid fa-download me-1 text-secondary"></i><?= number_format($doc['downloads'] ?? 0) ?> ครั้ง</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12 col-md-auto d-flex flex-wrap align-items-center gap-2">
                                    <a href="<?= base_url('ebook/view?doc=' . urlencode($doc['id'])) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5">
                                        <i class="fa-solid fa-book-open-reader"></i> อ่าน E-Book
                                    </a>
                                    <a href="<?= preg_match('#^https?://#i', $doc['file_url']) ? esc($doc['file_url']) : base_url($doc['file_url']) ?>" download class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" onclick="trackStrategyDownload('<?= $doc['id'] ?>')">
                                        <i class="fa-solid fa-download"></i> ดาวน์โหลด PDF
                                    </a>
                                    <?php if ($isOfficer): ?>
                                        <button type="button" class="btn btn-outline-warning btn-sm rounded-pill px-2.5 py-1.5" onclick="StrategyDocStudio.edit(<?= htmlspecialchars(json_encode($doc), ENT_QUOTES, 'UTF-8') ?>)" title="แก้ไขเอกสาร">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-2.5 py-1.5" onclick="StrategyDocStudio.delete('<?= esc($doc['id']) ?>', '<?= esc($doc['title']) ?>')" title="ลบเอกสาร">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="p-5 text-center text-muted d-none" id="docNoMatchMsg">
                        <i class="fa-solid fa-folder-magnifying-glass fs-1 text-secondary mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark mb-1">ไม่พบเอกสารที่ตรงกับเงื่อนไขการค้นหา</h6>
                        <p class="small text-muted mb-3">ลองเปลี่ยนคำค้นหา หรือกดปุ่มล้างการค้นหาเพื่อดูเอกสารทั้งหมด</p>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3.5 py-1.5 fw-bold" onclick="clearDocFilter()">
                            <i class="fa-solid fa-rotate-left me-1"></i> ล้างการค้นหา
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<!-- ======================================================== -->
<!-- MODAL: PDF / E-BOOK PREVIEW VIEWER -->
<!-- ======================================================== -->
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="height: 92vh;">
        <div class="modal-content border-0 rounded-4 shadow-2xl h-100 overflow-hidden" style="background: #0f172a;">
            <div class="modal-header border-bottom border-secondary border-opacity-25 px-4 py-3 text-white">
                <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden me-3">
                    <i class="fa-solid fa-file-pdf text-danger fs-4"></i>
                    <h6 class="modal-title fw-bold text-truncate text-white" id="docPreviewTitle">เปิดอ่านเอกสาร</h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="docPreviewDownloadBtn" download class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด PDF
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 h-100 bg-dark position-relative">
                <iframe id="docPreviewIframe" src="" class="w-100 h-100 border-0" style="min-height: 550px;"></iframe>
            </div>
        </div>
    </div>
</div>

<?php if ($isOfficer): ?>
<!-- ======================================================== -->
<!-- MODAL: STRATEGY DOCUMENT CATEGORY STUDIO (ระบบจัดการหมวดหมู่เอกสาร) -->
<!-- ======================================================== -->
<div class="modal fade" id="categoryStudioModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-2xl overflow-hidden" style="background: #ffffff;">
            <!-- Header -->
            <div class="modal-header px-4 py-3 border-bottom text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-white bg-opacity-15 border border-white border-opacity-20 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-folder-tree text-warning fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">ระบบจัดการหมวดหมู่เอกสารยุทธศาสตร์</h5>
                        <small class="text-white text-opacity-75">เพิ่ม แก้ไข ลบ และจัดเรียงหมวดหมู่เอกสารแผนพัฒนาของจังหวัดพัทลุง</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body p-4 p-md-4.5 bg-light bg-opacity-50">
                <div class="row g-4">
                    <!-- Left Column: Category List -->
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100 bg-white">
                            <div class="card-header bg-white border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-list-ol text-primary"></i> หมวดหมู่เอกสารปัจจุบัน
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold" onclick="CategoryStudio.resetForm()">
                                    <i class="fa-solid fa-plus me-1"></i> เพิ่มหมวดใหม่
                                </button>
                            </div>
                            <div class="card-body p-3 overflow-y-auto" style="max-height: 480px;" id="catStudioListBox">
                                <!-- Rendered via JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Add / Edit Category Form -->
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-4 shadow-sm bg-white">
                            <div class="card-header bg-white border-bottom px-4 py-3">
                                <h6 class="fw-bold text-dark m-0" id="catFormTitle">
                                    <i class="fa-solid fa-plus-circle text-success me-1"></i> เพิ่มหมวดหมู่เอกสารใหม่
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form id="catStudioForm" onsubmit="event.preventDefault(); CategoryStudio.submit();">
                                    <input type="hidden" id="cs_id" name="id" value="">
                                    <input type="hidden" id="cs_sort_order" name="sort_order" value="">

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small mb-1">
                                            <i class="fa-solid fa-heading text-primary me-1"></i> ชื่อหมวดหมู่เอกสาร <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control rounded-3 py-2 fw-semibold" id="cs_name" name="name" required placeholder="เช่น แผนพัฒนากลุ่มจังหวัด, แผนปฏิบัติราชการประจำปี">
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa-solid fa-icons text-info me-1"></i> ไอคอนหมวดหมู่
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light" id="cs_icon_preview"><i class="fa-solid fa-folder"></i></span>
                                                <input type="text" class="form-control" id="cs_icon" name="icon" value="fa-solid fa-folder" oninput="CategoryStudio.updateIconPreview(this.value)">
                                            </div>
                                            <div class="d-flex flex-wrap gap-1 mt-2">
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 small" onclick="CategoryStudio.setIcon('fa-solid fa-flag')"><i class="fa-solid fa-flag"></i></button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 small" onclick="CategoryStudio.setIcon('fa-solid fa-file-contract')"><i class="fa-solid fa-file-contract"></i></button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 small" onclick="CategoryStudio.setIcon('fa-solid fa-chart-line')"><i class="fa-solid fa-chart-line"></i></button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 small" onclick="CategoryStudio.setIcon('fa-solid fa-layer-group')"><i class="fa-solid fa-layer-group"></i></button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 small" onclick="CategoryStudio.setIcon('fa-solid fa-book-bookmark')"><i class="fa-solid fa-book-bookmark"></i></button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 small" onclick="CategoryStudio.setIcon('fa-solid fa-scale-balanced')"><i class="fa-solid fa-scale-balanced"></i></button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 small" onclick="CategoryStudio.setIcon('fa-solid fa-star')"><i class="fa-solid fa-star"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa-solid fa-palette text-warning me-1"></i> สีของป้ายกำกับ
                                            </label>
                                            <select class="form-select rounded-3 py-2 fw-semibold" id="cs_badge_color" name="badge_color">
                                                <option value="primary">🔵 สีน้ำเงิน (Primary)</option>
                                                <option value="success">🟢 สีเขียว (Success)</option>
                                                <option value="info">🔷 สีฟ้า/คราม (Info)</option>
                                                <option value="warning">🟡 สีเหลือง/ทอง (Warning)</option>
                                                <option value="danger">🔴 สีแดง (Danger)</option>
                                                <option value="dark">⚫ สีดำ/เทาเข้ม (Dark)</option>
                                                <option value="secondary">⚪ สีเทา (Secondary)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small mb-1">
                                            <i class="fa-solid fa-tags text-success me-1"></i> หมวดหมู่ย่อย (Sub-categories)
                                        </label>
                                        <input type="text" class="form-control rounded-3 py-2" id="cs_sub_categories" name="sub_categories" placeholder="คั่นด้วยเครื่องหมายจุลภาค เช่น แผนพัฒนาจังหวัด, ฉบับทบทวน, แผนพัฒนากลุ่มจังหวัด">
                                        <div class="form-text small text-muted"><i class="fa-solid fa-circle-info me-1"></i> ระบุหมวดหมู่ย่อยสำหรับให้เจ้าหน้าที่เลือกเมื่อเพิ่ม/แก้ไขเอกสาร (คั่นแต่ละรายการด้วยเครื่องหมาย <code>,</code>)</div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark small mb-1">
                                            <i class="fa-solid fa-align-left text-secondary me-1"></i> คำอธิบายย่อ (Description)
                                        </label>
                                        <textarea class="form-control rounded-3" id="cs_desc" name="desc" rows="2" placeholder="คำอธิบายรายละเอียดประเภทเอกสารในหมวดหมู่นี้..."></textarea>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                                        <button type="button" class="btn btn-light rounded-pill px-3 py-2 fw-bold text-muted" onclick="CategoryStudio.resetForm()">
                                            <i class="fa-solid fa-rotate-left me-1"></i> ล้างข้อมูล
                                        </button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-1.5" id="btnSaveCategory">
                                            <i class="fa-solid fa-floppy-disk"></i> บันทึกหมวดหมู่
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer px-4 py-2.5 bg-white border-top d-flex justify-content-between">
                <small class="text-muted"><i class="fa-solid fa-circle-info me-1 text-primary"></i> หมวดหมู่ที่บันทึกจะมีผลกับการกรองเอกสารบนหน้า /strategy และในระบบแอดมินทันที</small>
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 fw-bold" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: ON-PAGE STRATEGY DOCUMENT ADD/EDIT STUDIO -->
<!-- ======================================================== -->
<div class="modal fade" id="strategyDocModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-2xl overflow-hidden">
            <div class="modal-header px-4 py-3 bg-dark text-white border-0">
                <h6 class="modal-title fw-bold text-white mb-0" id="strategyDocModalTitle">
                    <i class="fa-solid fa-file-pdf text-warning me-2"></i>จัดการเอกสารยุทธศาสตร์
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light bg-opacity-50">
                <form id="strategyDocForm" onsubmit="event.preventDefault(); StrategyDocStudio.submit();">
                    <input type="hidden" id="sd_id" name="id" value="">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small mb-1">ชื่อเอกสาร / แผนพัฒนา <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3 py-2 fw-semibold" id="sd_title" name="title" required placeholder="เช่น แผนปฏิบัติราชการประจำปีของจังหวัดพัทลุง พ.ศ. 2570">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">หมวดหมู่หลัก <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3 py-2 fw-semibold" id="sd_category" name="category" required onchange="StrategyDocStudio.onCategoryChange()">
                                <!-- Populated dynamically via JS -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">หมวดหมู่ย่อย (Sub-category)</label>
                            <input type="text" class="form-control rounded-3 py-2" id="sd_sub_category" name="sub_category" list="sd_sub_category_list" placeholder="เช่น แผนพัฒนาจังหวัด, ฉบับทบทวน">
                            <datalist id="sd_sub_category_list">
                                <!-- Populated dynamically via JS -->
                            </datalist>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">ปีงบประมาณ</label>
                            <input type="text" class="form-control rounded-3 py-2 text-center fw-bold" id="sd_year" name="year" placeholder="เช่น 2570 หรือ 2566-2570">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">จำนวนหน้า</label>
                            <input type="number" class="form-control rounded-3 py-2 text-center" id="sd_pages" name="pages" value="100">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small mb-1">อัปโหลดไฟล์ PDF (เลือกไฟล์จากเครื่อง หรือระบุลิงก์ URL)</label>
                        <input type="file" class="form-control rounded-3 mb-2" id="sd_file" name="doc_file" accept=".pdf">
                        <div class="input-group">
                            <span class="input-group-text bg-white small text-muted">URL ไฟล์:</span>
                            <input type="text" class="form-control small" id="sd_file_url" name="file_url" placeholder="uploads/strategy/... หรือ https://...">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sd_is_featured" name="is_featured" value="1">
                            <label class="form-check-label fw-bold text-dark small" for="sd_is_featured">
                                ⭐ กำหนดเป็น "แผนแม่บทเด่นประจำหน้า" (Hero Featured Master Plan)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow-sm" id="btnSaveDoc">
                            <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกเอกสาร
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
let currentDocCategory = '';

// =========================================================================
// CATEGORY STUDIO CONTROLLER
// =========================================================================
window.CategoryStudio = {
    modalInstance: null,
    categories: <?= json_encode($categories ?? [], JSON_UNESCAPED_UNICODE) ?> || [],

    init: function() {
        const modalEl = document.getElementById('categoryStudioModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            this.modalInstance = new bootstrap.Modal(modalEl);
        }
    },

    open: function() {
        if (!this.modalInstance) this.init();
        this.renderList();
        this.resetForm();
        if (this.modalInstance) this.modalInstance.show();
    },

    loadAndRefresh: function() {
        fetch('<?= base_url("strategy/categories") ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success' && data.categories) {
                this.categories = data.categories;
                this.renderList();
                this.updatePillsOnPage();
            }
        })
        .catch(e => console.error(e));
    },

    renderList: function() {
        const box = document.getElementById('catStudioListBox');
        if (!box) return;

        if (!this.categories || this.categories.length === 0) {
            box.innerHTML = '<div class="p-4 text-center text-muted">ยังไม่มีรายการหมวดหมู่</div>';
            return;
        }

        let html = '<div class="list-group list-group-flush">';
        this.categories.forEach((cat, idx) => {
            const count = document.querySelectorAll(`.doc-list-row[data-category="${cat.name}"]`).length;
            let subChips = '';
            if (cat.sub_categories && Array.isArray(cat.sub_categories) && cat.sub_categories.length > 0) {
                subChips = '<div class="d-flex flex-wrap gap-1 mt-1.5">' + 
                    cat.sub_categories.map(s => `<span class="badge bg-light text-secondary border px-1.5 py-0.5" style="font-size: 0.68rem;"><i class="fa-solid fa-tag me-1 text-muted"></i>${s}</span>`).join('') + 
                    '</div>';
            }
            html += `
            <div class="list-group-item p-3 border-bottom d-flex align-items-center justify-content-between gap-3 bg-white hover-bg-light transition-all rounded-3 mb-1">
                <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                    <span class="badge bg-${cat.badge_color || 'primary'} rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="${cat.icon || 'fa-solid fa-folder'} fs-6"></i>
                    </span>
                    <div class="overflow-hidden">
                        <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.95rem;">${cat.name}</h6>
                        <small class="text-muted d-block text-truncate">${cat.desc || 'เอกสารในหมวดนี้'}</small>
                        ${subChips}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                    <span class="badge bg-light text-secondary border px-2 py-1 me-1" title="จำนวนเอกสาร">${count} ไฟล์</span>
                    <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1 rounded-pill" onclick="CategoryStudio.editCategory(${idx})" title="แก้ไขหมวดนี้">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger px-2 py-1 rounded-pill" onclick="CategoryStudio.deleteCategory('${cat.id}', '${cat.name.replace(/'/g, "\\'")}')" title="ลบหมวดนี้">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>
            `;
        });
        html += '</div>';
        box.innerHTML = html;
    },

    resetForm: function() {
        const titleEl = document.getElementById('catFormTitle');
        if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-plus-circle text-success me-1"></i> เพิ่มหมวดหมู่เอกสารใหม่';
        const idEl = document.getElementById('cs_id');
        if (idEl) idEl.value = '';
        const sortEl = document.getElementById('cs_sort_order');
        if (sortEl) sortEl.value = this.categories.length + 1;
        const nameEl = document.getElementById('cs_name');
        if (nameEl) nameEl.value = '';
        const iconEl = document.getElementById('cs_icon');
        if (iconEl) iconEl.value = 'fa-solid fa-folder';
        const badgeEl = document.getElementById('cs_badge_color');
        if (badgeEl) badgeEl.value = 'primary';
        const subEl = document.getElementById('cs_sub_categories');
        if (subEl) subEl.value = '';
        const descEl = document.getElementById('cs_desc');
        if (descEl) descEl.value = '';
        this.updateIconPreview('fa-solid fa-folder');
        const btnSave = document.getElementById('btnSaveCategory');
        if (btnSave) btnSave.innerHTML = '<i class="fa-solid fa-plus me-1"></i> เพิ่มหมวดหมู่';
    },

    editCategory: function(idx) {
        const cat = this.categories[idx];
        if (!cat) return;
        const titleEl = document.getElementById('catFormTitle');
        if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-pen-to-square text-warning me-1"></i> แก้ไขหมวดหมู่: ' + cat.name;
        document.getElementById('cs_id').value = cat.id || '';
        document.getElementById('cs_sort_order').value = cat.sort_order || (idx + 1);
        document.getElementById('cs_name').value = cat.name || '';
        document.getElementById('cs_icon').value = cat.icon || 'fa-solid fa-folder';
        document.getElementById('cs_badge_color').value = cat.badge_color || 'primary';
        const subEl = document.getElementById('cs_sub_categories');
        if (subEl) subEl.value = Array.isArray(cat.sub_categories) ? cat.sub_categories.join(', ') : '';
        document.getElementById('cs_desc').value = cat.desc || '';
        this.updateIconPreview(cat.icon || 'fa-solid fa-folder');
        const btnSave = document.getElementById('btnSaveCategory');
        if (btnSave) btnSave.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> บันทึกการแก้ไข';
        document.getElementById('cs_name').focus();
    },

    setIcon: function(iconClass) {
        const iconEl = document.getElementById('cs_icon');
        if (iconEl) iconEl.value = iconClass;
        this.updateIconPreview(iconClass);
    },

    updateIconPreview: function(iconClass) {
        const preview = document.getElementById('cs_icon_preview');
        if (preview) {
            preview.innerHTML = `<i class="${iconClass || 'fa-solid fa-folder'}"></i>`;
        }
    },

    submit: function() {
        const nameInput = document.getElementById('cs_name');
        const name = nameInput ? nameInput.value.trim() : '';
        if (!name) {
            alert('กรุณากรอกชื่อหมวดหมู่');
            return;
        }

        const form = document.getElementById('catStudioForm');
        const formData = new FormData(form);

        const btn = document.getElementById('btnSaveCategory');
        const origBtnText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
        }

        fetch('<?= base_url("strategy/categories/save") ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origBtnText;
            }
            if (data.status === 'success') {
                if (typeof App !== 'undefined' && App.toast) {
                    App.toast(data.message, 'success');
                } else {
                    alert(data.message);
                }
                this.loadAndRefresh();
                this.resetForm();
            } else {
                alert(data.message || 'บันทึกล้มเหลว');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origBtnText;
            }
            console.error(err);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });
    },

    deleteCategory: function(id, name) {
        if (!confirm(`คุณต้องการลบหมวดหมู่ "${name}" ใช่หรือไม่?\n(หากมีเอกสารในหมวดนี้ ระบบจะย้ายเอกสารไปไว้ในหมวดแรกให้โดยอัตโนมัติ)`)) {
            return;
        }

        fetch('<?= base_url("strategy/categories/delete") ?>/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined' && App.toast) {
                    App.toast(data.message, 'success');
                } else {
                    alert(data.message);
                }
                this.loadAndRefresh();
                setTimeout(() => location.reload(), 800);
            } else {
                alert(data.message || 'ลบล้มเหลว');
            }
        })
        .catch(err => {
            console.error(err);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });
    },

    updatePillsOnPage: function() {
        const container = document.getElementById('docCategoryPills');
        if (!container) return;

        const totalDocs = document.querySelectorAll('.doc-list-row').length;
        let html = `
        <button type="button" class="btn btn-sm ${currentDocCategory === '' ? 'btn-dark active' : 'btn-light border'} rounded-pill px-3 py-1 fw-bold category-filter-btn" data-category="" onclick="setDocCategoryFilter('', this)">
            ทั้งหมด (<span id="totalDocCount">${totalDocs}</span>)
        </button>
        `;

        this.categories.forEach(cat => {
            const count = document.querySelectorAll(`.doc-list-row[data-category="${cat.name}"]`).length;
            const isActive = currentDocCategory === cat.name;
            html += `
            <button type="button" class="btn btn-sm ${isActive ? 'btn-dark active' : 'btn-light border'} rounded-pill px-3 py-1 fw-bold category-filter-btn" data-category="${cat.name}" onclick="setDocCategoryFilter('${cat.name}', this)">
                <i class="${cat.icon || 'fa-solid fa-folder'} me-1 text-${cat.badge_color || 'primary'}"></i>${cat.name}
                <span class="badge bg-secondary bg-opacity-25 text-dark ms-1" style="font-size: 0.72rem;">${count}</span>
            </button>
            `;
        });

        container.innerHTML = html;
    }
};

// =========================================================================
// STRATEGY DOCUMENT STUDIO CONTROLLER
// =========================================================================
window.StrategyDocStudio = {
    modalInstance: null,

    init: function() {
        const modalEl = document.getElementById('strategyDocModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            this.modalInstance = new bootstrap.Modal(modalEl);
        }
    },

    populateCategoryDropdown: function(selectedCategory = '', selectedSubCategory = '') {
        const sel = document.getElementById('sd_category');
        if (!sel) return;
        const cats = CategoryStudio.categories || [];
        sel.innerHTML = '';
        cats.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.name;
            opt.textContent = c.name;
            if (c.name === selectedCategory) opt.selected = true;
            sel.appendChild(opt);
        });
        const activeCat = selectedCategory || (cats[0] ? cats[0].name : '');
        this.updateSubCategoryDatalist(activeCat);
        const subInput = document.getElementById('sd_sub_category');
        if (subInput && selectedSubCategory !== undefined) {
            subInput.value = selectedSubCategory;
        }
    },

    onCategoryChange: function() {
        const sel = document.getElementById('sd_category');
        if (!sel) return;
        this.updateSubCategoryDatalist(sel.value);
    },

    updateSubCategoryDatalist: function(categoryName = '') {
        const datalist = document.getElementById('sd_sub_category_list');
        if (!datalist) return;
        datalist.innerHTML = '';
        const cats = CategoryStudio.categories || [];
        const found = cats.find(c => (c.name || c) === categoryName);
        if (found && Array.isArray(found.sub_categories)) {
            found.sub_categories.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub;
                datalist.appendChild(opt);
            });
        }
    },

    createNew: function() {
        if (!this.modalInstance) this.init();
        document.getElementById('strategyDocModalTitle').innerHTML = '<i class="fa-solid fa-file-circle-plus text-success me-2"></i>เพิ่มเอกสารแผนพัฒนา / ยุทธศาสตร์ใหม่';
        document.getElementById('sd_id').value = '';
        document.getElementById('sd_title').value = '';
        document.getElementById('sd_sub_category').value = '';
        document.getElementById('sd_year').value = new Date().getFullYear() + 543;
        document.getElementById('sd_pages').value = '100';
        document.getElementById('sd_file').value = '';
        document.getElementById('sd_file_url').value = '';
        document.getElementById('sd_is_featured').checked = false;
        this.populateCategoryDropdown('', '');
        if (this.modalInstance) this.modalInstance.show();
    },

    edit: function(doc) {
        if (!this.modalInstance) this.init();
        document.getElementById('strategyDocModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square text-warning me-2"></i>แก้ไขเอกสาร: ' + (doc.title || '');
        document.getElementById('sd_id').value = doc.id || '';
        document.getElementById('sd_title').value = doc.title || '';
        document.getElementById('sd_year').value = doc.year || (new Date().getFullYear() + 543);
        document.getElementById('sd_pages').value = doc.pages || '100';
        document.getElementById('sd_file').value = '';
        document.getElementById('sd_file_url').value = doc.file_url || '';
        document.getElementById('sd_is_featured').checked = !!doc.is_featured;
        this.populateCategoryDropdown(doc.category || '', doc.sub_category || doc.sub_tag || '');
        if (this.modalInstance) this.modalInstance.show();
    },

    submit: function() {
        const form = document.getElementById('strategyDocForm');
        const formData = new FormData(form);

        const btn = document.getElementById('btnSaveDoc');
        const origText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังบันทึก...';
        }

        fetch('<?= base_url("strategy/document/save-inline") ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origText;
            }
            if (data.status === 'success') {
                if (typeof App !== 'undefined' && App.toast) {
                    App.toast(data.message, 'success');
                } else {
                    alert(data.message);
                }
                setTimeout(() => location.reload(), 700);
            } else {
                alert(data.message || 'บันทึกล้มเหลว');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origText;
            }
            console.error(err);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });
    },

    delete: function(id, title) {
        if (!confirm(`คุณต้องการลบเอกสาร "${title}" ใช่หรือไม่?`)) return;

        fetch('<?= base_url("strategy/document/delete-inline") ?>/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined' && App.toast) {
                    App.toast(data.message, 'success');
                } else {
                    alert(data.message);
                }
                setTimeout(() => location.reload(), 700);
            } else {
                alert(data.message || 'ลบล้มเหลว');
            }
        })
        .catch(err => {
            console.error(err);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });
    }
};

function previewStrategyDoc(title, fileUrl) {
    document.getElementById('docPreviewTitle').textContent = title;
    document.getElementById('docPreviewDownloadBtn').href = fileUrl;
    document.getElementById('docPreviewIframe').src = fileUrl;
    
    const modalEl = document.getElementById('docPreviewModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

async function trackStrategyDownload(docId) {
    try {
        await fetch('<?= base_url("strategy/download") ?>/' + docId, { method: 'GET' });
    } catch (e) {
        console.log('Track download error:', e);
    }
}

function setDocCategoryFilter(category, btn) {
    currentDocCategory = category;
    document.querySelectorAll('.category-filter-btn').forEach(el => {
        el.classList.remove('btn-dark', 'active');
        el.classList.add('btn-light', 'border');
    });
    if (btn) {
        btn.classList.remove('btn-light', 'border');
        btn.classList.add('btn-dark', 'active');
    }
    filterStrategyDocs();
}

function clearDocFilter() {
    document.getElementById('docSearchInput').value = '';
    document.getElementById('docYearSelect').value = '';
    currentDocCategory = '';
    
    document.querySelectorAll('.category-filter-btn').forEach((el, idx) => {
        if (idx === 0) {
            el.classList.remove('btn-light', 'border');
            el.classList.add('btn-dark', 'active');
        } else {
            el.classList.remove('btn-dark', 'active');
            el.classList.add('btn-light', 'border');
        }
    });
    
    filterStrategyDocs();
}

function filterStrategyDocs() {
    const searchVal = (document.getElementById('docSearchInput')?.value || '').trim().toLowerCase();
    const yearVal = (document.getElementById('docYearSelect')?.value || '').trim();
    const rows = document.querySelectorAll('.doc-list-row');
    const noMatchEl = document.getElementById('docNoMatchMsg');
    const countBadge = document.getElementById('docCountBadge');

    let visibleCount = 0;

    rows.forEach(row => {
        const title = row.getAttribute('data-title') || '';
        const cat = row.getAttribute('data-category') || '';
        const subcat = row.getAttribute('data-subcategory') || '';
        const year = row.getAttribute('data-year') || '';

        const matchSearch = !searchVal || title.includes(searchVal) || cat.toLowerCase().includes(searchVal) || subcat.includes(searchVal) || year.includes(searchVal);
        const matchYear = !yearVal || year === yearVal;
        const matchCat = !currentDocCategory || cat === currentDocCategory;

        if (matchSearch && matchYear && matchCat) {
            row.classList.remove('d-none');
            visibleCount++;
        } else {
            row.classList.add('d-none');
        }
    });

    if (countBadge) {
        countBadge.textContent = `แสดง ${visibleCount} รายการ`;
    }

    if (noMatchEl) {
        if (visibleCount === 0 && rows.length > 0) {
            noMatchEl.classList.remove('d-none');
        } else {
            noMatchEl.classList.add('d-none');
        }
    }
}
</script>

<?= $this->endSection() ?>

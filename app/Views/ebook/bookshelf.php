<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$books      = $books ?? [];
$categories = $categories ?? [];
$isOfficer  = (bool) session()->get('isLoggedIn');
$siteLogo   = base_url('uploads/logo/logo_1787048018.png');
?>

<style>
/* ==================== DIGITAL BOOKSHELF STUDIO ==================== */
.bookshelf-hero {
    background: linear-gradient(135deg, #0b2922 0%, #114b3c 55%, #0f3b66 100%);
    color: #ffffff;
    border-radius: 26px;
    padding: 2rem 2.25rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 18px 40px rgba(11, 41, 34, 0.22);
}
.bookshelf-hero::after {
    content: '';
    position: absolute;
    top: -35%;
    right: -6%;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(251, 191, 36, 0.2) 0%, transparent 70%);
    pointer-events: none;
}

/* Filter Pill Bar */
.shelf-cat-pill {
    border-radius: 999px;
    padding: 0.45rem 1.1rem;
    font-size: 0.86rem;
    font-weight: 700;
    border: 1px solid #d1e7dd;
    background: #ffffff;
    color: #1f332a;
    transition: all 0.2s ease;
    cursor: pointer;
}
.shelf-cat-pill:hover {
    border-color: #10b981;
    color: #047857;
    transform: translateY(-1px);
}
.shelf-cat-pill.active {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.28);
}

/* Warm Wood Shelf Unit */
.wood-shelf-unit {
    background: linear-gradient(180deg, rgba(255, 252, 245, 0.9) 0%, rgba(247, 241, 229, 0.85) 100%);
    border: 1px solid rgba(180, 134, 78, 0.22);
    border-radius: 28px;
    padding: 2rem 1.75rem 1.25rem;
    box-shadow: 0 14px 36px rgba(120, 82, 36, 0.08);
}

.wood-shelf-row {
    position: relative;
    padding-bottom: 16px;
    margin-bottom: 2.25rem;
}
.wood-shelf-row:last-child {
    margin-bottom: 0.5rem;
}

/* 3D Wooden Plank */
.wood-plank {
    height: 18px;
    border-radius: 6px;
    background: linear-gradient(180deg, #d7a86e 0%, #b57b3c 55%, #8c5a26 100%);
    box-shadow:
        0 10px 22px rgba(74, 44, 15, 0.28),
        inset 0 2px 2px rgba(255, 236, 204, 0.65),
        inset 0 -3px 4px rgba(59, 34, 10, 0.45);
    position: relative;
    margin-top: -4px;
    z-index: 1;
}
.wood-plank::after {
    content: '';
    position: absolute;
    left: 2%;
    right: 2%;
    bottom: -10px;
    height: 10px;
    background: radial-gradient(ellipse at center, rgba(60, 35, 12, 0.28) 0%, transparent 75%);
    pointer-events: none;
}

/* 3D Book Card */
.shelf-book-slot {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
}

.book-3d-item {
    width: 100%;
    max-width: 215px;
    aspect-ratio: 3 / 4.15;
    border-radius: 6px 14px 14px 6px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 16px 14px 14px 20px;
    color: #ffffff;
    text-decoration: none;
    box-shadow:
        -6px 12px 24px rgba(15, 23, 42, 0.28),
        inset 8px 0 12px rgba(0, 0, 0, 0.25),
        inset -2px 0 3px rgba(255, 255, 255, 0.25);
    transition: transform 0.32s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.32s ease;
    overflow: hidden;
    cursor: pointer;
}
.book-3d-item:hover {
    transform: translateY(-12px) rotateY(-8deg) scale(1.03);
    box-shadow:
        -12px 24px 36px rgba(15, 23, 42, 0.35),
        inset 10px 0 14px rgba(0, 0, 0, 0.28);
    color: #ffffff;
}

/* Spine Groove */
.book-3d-item::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 11px;
    width: 2px;
    background: rgba(255, 255, 255, 0.22);
    box-shadow: -2px 0 4px rgba(0, 0, 0, 0.3);
    z-index: 3;
    pointer-events: none;
}

/* Page Edge Strip on Right */
.book-3d-item::after {
    content: '';
    position: absolute;
    top: 5px;
    bottom: 5px;
    right: 0;
    width: 5px;
    background: repeating-linear-gradient(90deg, #f8fafc 0px, #e2e8f0 1px, #ffffff 2px);
    opacity: 0.85;
    border-radius: 0 3px 3px 0;
    z-index: 3;
    pointer-events: none;
}

/* Custom Cover Image Book */
.book-3d-item.book-has-cover {
    padding: 0;
    background: #0f172a;
}
.book-custom-cover-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
}
.book-custom-cover-overlay {
    position: relative;
    z-index: 2;
    margin-top: auto;
    width: 100%;
    padding: 28px 12px 10px 18px;
    background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.78) 65%, rgba(15, 23, 42, 0.92) 100%);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.7rem;
    color: #ffffff;
}

/* Theme Swatch Selector inside Studio Modal */
.ebook-theme-swatch {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 3px solid transparent;
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    position: relative;
}
.ebook-theme-swatch:hover { transform: scale(1.1); }
.ebook-theme-swatch.active {
    border-color: #0f172a;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #10b981;
    transform: scale(1.08);
}

/* Preview Box inside Modal */
.ebook-preview-stage {
    background: linear-gradient(180deg, #fffcf5 0%, #f3ebd9 100%);
    border: 1px solid rgba(180, 134, 78, 0.25);
    border-radius: 18px;
    padding: 1.25rem 1rem 0.9rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.ebook-preview-stage .book-3d-item {
    max-width: 175px;
    cursor: default;
}
.ebook-preview-stage .book-3d-item:hover {
    transform: none;
}
.ebook-preview-stage .wood-plank {
    width: 92%;
    height: 12px;
    margin-top: 2px;
}

/* Book Cover Themes */
.cover-emerald { background: linear-gradient(145deg, #065f46 0%, #047857 52%, #022c22 100%); }
.cover-royal   { background: linear-gradient(145deg, #1d4ed8 0%, #1e3a8a 58%, #0f172a 100%); }
.cover-amber   { background: linear-gradient(145deg, #d97706 0%, #b45309 55%, #78350f 100%); }
.cover-teal    { background: linear-gradient(145deg, #0d9488 0%, #0f766e 55%, #134e4a 100%); }
.cover-indigo  { background: linear-gradient(145deg, #4f46e5 0%, #3730a3 58%, #1e1b4b 100%); }
.cover-rose    { background: linear-gradient(145deg, #be123c 0%, #9f1239 58%, #4c0519 100%); }

.book-cover-emblem {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.92);
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
}
.book-cover-emblem img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.book-cover-cat {
    font-size: 0.66rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    background: rgba(255, 255, 255, 0.16);
    border: 1px solid rgba(255, 255, 255, 0.24);
    padding: 2px 8px;
    border-radius: 999px;
    display: inline-block;
    margin-bottom: 6px;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.book-cover-title {
    font-size: 0.88rem;
    font-weight: 700;
    line-height: 1.38;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.35);
}

.book-cover-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.7rem;
    padding-top: 8px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.9);
}

.book-read-pill {
    background: rgba(255, 255, 255, 0.92);
    color: #0f172a;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 999px;
    font-size: 0.68rem;
}

.shelf-book-caption {
    margin-top: 10px;
    text-align: center;
    max-width: 215px;
    width: 100%;
}
.shelf-book-caption-title {
    font-size: 0.84rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 4px;
}

[data-theme="dark"] .wood-shelf-unit {
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    border-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] .shelf-book-caption-title {
    color: #f1f5f9;
}
</style>

<div class="container py-4">

    <!-- 1. HERO HEADER -->
    <section class="bookshelf-hero mb-4">
        <div class="position-relative z-2">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url() ?>" class="text-white text-opacity-75 text-decoration-none">
                            <i class="fa-solid fa-house me-1"></i>หน้าแรก
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">ชั้นหนังสือ E-Book จังหวัดพัทลุง</li>
                </ol>
            </nav>

            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning bg-opacity-20 border border-warning border-opacity-30 text-warning fw-bold small mb-2">
                        <i class="fa-solid fa-book-open-reader"></i> Digital Library &amp; 3D Flipbook
                    </div>
                    <h1 class="h2 fw-bold mb-1.5 text-white">
                        ชั้นหนังสืออิเล็กทรอนิกส์ (E-Book) จังหวัดพัทลุง
                    </h1>
                    <p class="text-white text-opacity-85 mb-0 small">
                        คลิกที่หน้าปกหนังสือเพื่อเปิดอ่านแบบพลิกหน้า 3D เหมือนหนังสือจริง รองรับเสียงเปิดกระดาษ หมุนหน้าตาราง 90° และดาวน์โหลด PDF
                    </p>
                </div>
                <div class="col-lg-4 d-flex flex-wrap justify-content-lg-end gap-2">
                    <a href="<?= base_url('strategy') ?>" class="btn btn-warning btn-sm rounded-pill px-3.5 py-2 fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-1.5">
                        <i class="fa-solid fa-folder-open"></i> ศูนย์เอกสารแผนพัฒนาฯ
                    </a>
                    <?php if ($isOfficer): ?>
                        <button type="button" onclick="EbookStudio.openNew()" class="btn btn-light btn-sm rounded-pill px-3.5 py-2 fw-bold text-success shadow-sm d-inline-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-circle-plus"></i> เพิ่มหนังสือ E-Book
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. SEARCH & CATEGORY FILTER BAR -->
    <div class="card border-0 rounded-4 shadow-sm p-3.5 mb-4 bg-white">
        <div class="row g-2.5 align-items-center">
            <div class="col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-success">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" id="ebookSearchInput" class="form-control border-start-0 rounded-end-pill bg-light shadow-none" placeholder="ค้นหาชื่อหนังสือ หรือปีงบประมาณ..." oninput="filterBookshelf()">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="d-flex flex-wrap gap-1.5 justify-content-lg-end" id="ebookCatFilters">
                    <button type="button" class="shelf-cat-pill active" data-cat="" onclick="selectShelfCat('', this)">
                        ทั้งหมด (<span id="shelfTotalCount"><?= count($books) ?></span>)
                    </button>
                    <?php foreach ($categories as $cat): ?>
                        <button type="button" class="shelf-cat-pill" data-cat="<?= esc($cat) ?>" onclick="selectShelfCat('<?= esc($cat) ?>', this)">
                            <?= esc($cat) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. 3D WOODEN BOOKSHELF ROWS -->
    <div class="wood-shelf-unit" id="bookshelfGridWrap">
        <?php if (empty($books)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-book-open fs-1 mb-2 d-block opacity-50"></i>
                <h5 class="fw-bold">ยังไม่มีหนังสือ E-Book บนชั้น</h5>
            </div>
        <?php else: ?>
            <div id="bookshelfRowsContainer"></div>
            <div id="bookshelfEmptyState" class="text-center py-5 text-muted d-none">
                <i class="fa-solid fa-magnifying-glass fs-1 mb-2 d-block opacity-50"></i>
                <h5 class="fw-bold text-dark">ไม่พบหนังสือที่ตรงกับคำค้นหา</h5>
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-4 mt-2 fw-bold" onclick="resetBookshelfFilter()">แสดงหนังสือทั้งหมด</button>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php if ($isOfficer): ?>
<!-- ==================== ON-PAGE E-BOOK STUDIO MODAL ==================== -->
<div class="modal fade" id="ebookStudioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 py-3 px-4" style="background: linear-gradient(135deg, #0b2922 0%, #114b3c 100%); color: #fff;">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning text-dark" style="width:34px;height:34px;">
                        <i class="fa-solid fa-book-medical"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 fs-6" id="ebookModalTitle">เพิ่มหนังสือ E-Book ใหม่</h5>
                        <div class="small text-white-50" style="font-size:0.75rem;">อัปโหลดไฟล์ PDF และภาพหน้าปก (หากไม่ใส่ปก ระบบจะสร้างปก 3D ให้อัตโนมัติ)</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ebookStudioForm" onsubmit="EbookStudio.save(event)" enctype="multipart/form-data">
                <input type="hidden" name="id" id="ebId">
                <input type="hidden" name="theme" id="ebTheme" value="emerald">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- ฟอร์มกรอกข้อมูล -->
                        <div class="col-lg-7">
                            <div class="mb-3">
                                <label class="form-label fw-bold small mb-1">ชื่อหนังสือ E-Book <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="ebTitle" class="form-control rounded-3" required placeholder="เช่น คู่มือการท่องเที่ยววิถีชุมชนจังหวัดพัทลุง" oninput="EbookStudio.updatePreview()">
                            </div>

                            <div class="row g-2.5 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small mb-1">หมวดหมู่หนังสือ</label>
                                    <input type="text" name="category" id="ebCategory" list="ebookCatSuggestions" class="form-control rounded-3" placeholder="เช่น หนังสืออิเล็กทรอนิกส์" oninput="EbookStudio.updatePreview()">
                                    <datalist id="ebookCatSuggestions">
                                        <option value="หนังสืออิเล็กทรอนิกส์">
                                        <option value="แผนพัฒนาจังหวัด">
                                        <option value="คู่มือประชาชน">
                                        <option value="รายงานประจำปี">
                                        <option value="ท่องเที่ยวและวัฒนธรรม">
                                        <option value="งบประมาณจังหวัด">
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= esc($cat) ?>">
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label fw-bold small mb-1">ปี พ.ศ.</label>
                                    <input type="text" name="year" id="ebYear" class="form-control rounded-3" placeholder="2570" oninput="EbookStudio.updatePreview()">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label fw-bold small mb-1">จำนวนหน้า</label>
                                    <input type="text" name="pages" id="ebPages" class="form-control rounded-3" placeholder="เช่น 64">
                                </div>
                            </div>

                            <!-- ไฟล์ PDF -->
                            <div class="p-3 rounded-3 bg-light border mb-3">
                                <label class="form-label fw-bold small mb-1 text-success">
                                    <i class="fa-solid fa-file-pdf me-1"></i>ไฟล์หนังสือ PDF <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="pdf_file" id="ebPdfFile" class="form-control form-control-sm rounded-3 mb-2" accept=".pdf,application/pdf">
                                <div class="d-flex align-items-center gap-2 my-1">
                                    <hr class="flex-grow-1 my-0 opacity-25"><span class="text-muted" style="font-size:0.72rem;">หรือระบุ URL ไฟล์ PDF ที่มีอยู่แล้ว</span><hr class="flex-grow-1 my-0 opacity-25">
                                </div>
                                <input type="text" name="file_url" id="ebFileUrl" class="form-control form-control-sm rounded-3" placeholder="https://... หรือ uploads/ebooks/...">
                                <div id="ebCurrentPdfHint" class="form-text text-success small d-none mt-1"></div>
                            </div>

                            <!-- ภาพหน้าปก (ไม่บังคับ) -->
                            <div class="p-3 rounded-3 bg-light border mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label fw-bold small mb-0 text-primary">
                                        <i class="fa-solid fa-image me-1"></i>ภาพหน้าปกหนังสือ (ไม่บังคับ)
                                    </label>
                                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size:0.68rem;">ไม่มีปก = สร้างปก 3D อัตโนมัติ</span>
                                </div>
                                <input type="file" name="cover_file" id="ebCoverFile" class="form-control form-control-sm rounded-3" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" onchange="EbookStudio.onCoverFileChange(this)">
                                <div class="form-text" style="font-size:0.74rem;">แนะนำสัดส่วนแนวตั้ง 3:4 (เช่น 600×830 px) รองรับ .jpg, .png, .webp</div>
                                <div class="form-check mt-2 d-none" id="ebRemoveCoverWrap">
                                    <input class="form-check-input" type="checkbox" name="remove_cover" value="1" id="ebRemoveCover" onchange="EbookStudio.updatePreview()">
                                    <label class="form-check-label small text-danger fw-bold" for="ebRemoveCover">
                                        ลบภาพปกที่อัปโหลดไว้ (สลับกลับไปใช้ปก 3D อัตโนมัติ)
                                    </label>
                                </div>
                            </div>

                            <!-- เลือกสีปกอัตโนมัติ -->
                            <div>
                                <label class="form-label fw-bold small mb-2">โทนสีปกอัตโนมัติ (ใช้เมื่อไม่ได้อัปโหลดภาพปก)</label>
                                <div class="d-flex flex-wrap gap-2.5" id="ebThemeSwatches">
                                    <button type="button" class="ebook-theme-swatch cover-emerald active" data-theme="emerald" title="เขียวมรกต" onclick="EbookStudio.selectTheme('emerald')"></button>
                                    <button type="button" class="ebook-theme-swatch cover-royal" data-theme="royal" title="น้ำเงินราชการ" onclick="EbookStudio.selectTheme('royal')"></button>
                                    <button type="button" class="ebook-theme-swatch cover-amber" data-theme="amber" title="ทองอำพัน" onclick="EbookStudio.selectTheme('amber')"></button>
                                    <button type="button" class="ebook-theme-swatch cover-teal" data-theme="teal" title="เขียวน้ำทะเล" onclick="EbookStudio.selectTheme('teal')"></button>
                                    <button type="button" class="ebook-theme-swatch cover-indigo" data-theme="indigo" title="ม่วงคราม" onclick="EbookStudio.selectTheme('indigo')"></button>
                                    <button type="button" class="ebook-theme-swatch cover-rose" data-theme="rose" title="แดงกุหลาบ" onclick="EbookStudio.selectTheme('rose')"></button>
                                </div>
                            </div>
                        </div>

                        <!-- ตัวอย่างปก 3D สดๆ -->
                        <div class="col-lg-5 d-flex flex-column justify-content-center">
                            <div class="ebook-preview-stage">
                                <div class="small fw-bold text-muted mb-2.5">
                                    <i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i>ตัวอย่างหน้าปกบนชั้นหนังสือ
                                </div>
                                <div id="ebLivePreviewBook" class="book-3d-item cover-emerald"></div>
                                <div class="wood-plank"></div>
                                <div id="ebPreviewModeBadge" class="mt-2.5 badge bg-success-subtle text-success rounded-pill px-3 py-1" style="font-size:0.72rem;">
                                    โหมดสร้างปก 3D อัตโนมัติ
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold btn-sm" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" id="ebSaveBtn" class="btn btn-success rounded-pill px-4 fw-bold btn-sm shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-1.5"></i>บันทึกขึ้นชั้นหนังสือ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const ALL_EBOOKS = <?= json_encode(array_values($books), JSON_UNESCAPED_UNICODE) ?>;
const SITE_LOGO_URL = <?= json_encode($siteLogo) ?>;
const IS_OFFICER = <?= $isOfficer ? 'true' : 'false' ?>;
const EBOOK_SAVE_URL = <?= json_encode(base_url('ebooks/save')) ?>;
const EBOOK_DELETE_BASE = <?= json_encode(base_url('ebooks/delete')) ?>;
const EBOOK_CSRF_HEADER = <?= json_encode(csrf_header()) ?>;
let EBOOK_CSRF_TOKEN = <?= json_encode(csrf_hash()) ?>;
let currentShelfCat = '';

function escapeHtmlShelf(str) {
    return String(str || '').replace(/[&<>"']/g, s => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[s]));
}

function buildBookCoverInnerHtml(book) {
    const yrText = book.year ? ('พ.ศ. ' + String(book.year).replace(/^พ\.ศ\.\s*/u, '')) : 'ฉบับทางการ';
    if (book.cover_image) {
        return `
            <img src="${escapeHtmlShelf(book.cover_image)}" alt="${escapeHtmlShelf(book.title)}" class="book-custom-cover-img" loading="lazy">
            <div class="book-custom-cover-overlay">
                <span class="fw-semibold">${escapeHtmlShelf(yrText)}</span>
                <span class="book-read-pill"><i class="fa-solid fa-book-open me-1"></i>อ่าน</span>
            </div>
        `;
    }
    return `
        <div class="text-center">
            <div class="book-cover-emblem">
                <img src="${SITE_LOGO_URL}" alt="ตราจังหวัดพัทลุง">
            </div>
            <span class="book-cover-cat">${escapeHtmlShelf(book.category || 'หนังสืออิเล็กทรอนิกส์')}</span>
        </div>
        <div class="my-auto text-center px-1">
            <div class="book-cover-title">${escapeHtmlShelf(book.title || 'ชื่อหนังสือ E-Book')}</div>
        </div>
        <div class="book-cover-footer">
            <span>${escapeHtmlShelf(yrText)}</span>
            <span class="book-read-pill"><i class="fa-solid fa-book-open me-1"></i>อ่าน</span>
        </div>
    `;
}

function renderBookshelf(list) {
    const container = document.getElementById('bookshelfRowsContainer');
    const emptyEl = document.getElementById('bookshelfEmptyState');
    if (!container) return;

    if (!list || list.length === 0) {
        container.innerHTML = '';
        if (emptyEl) emptyEl.classList.remove('d-none');
        return;
    }
    if (emptyEl) emptyEl.classList.add('d-none');

    // แบ่งเป็นชั้นๆ ละ 4 เล่มบนจอใหญ่
    const perShelf = 4;
    let html = '';
    for (let i = 0; i < list.length; i += perShelf) {
        const chunk = list.slice(i, i + perShelf);
        html += `<div class="wood-shelf-row">`;
        html += `<div class="row g-4 justify-content-start align-items-end px-2">`;
        chunk.forEach(book => {
            const hasCover = Boolean(book.cover_image);
            const themeCls = hasCover ? 'book-has-cover' : ('cover-' + (book.theme || 'emerald'));
            const metaText = [book.pages ? (book.pages + ' หน้า') : '', book.file_size || ''].filter(Boolean).join(' • ');
            const adminBtns = (IS_OFFICER && book.is_custom)
                ? `<button type="button" class="btn btn-link p-0 text-warning text-decoration-none" style="font-size:0.78rem;" title="แก้ไขหนังสือ" onclick="EbookStudio.openEdit('${escapeHtmlShelf(book.id)}')"><i class="fa-solid fa-pen-to-square"></i></button>
                   <button type="button" class="btn btn-link p-0 text-danger text-decoration-none" style="font-size:0.78rem;" title="ลบหนังสือ" onclick="EbookStudio.remove('${escapeHtmlShelf(book.id)}', '${escapeHtmlShelf(book.title)}')"><i class="fa-solid fa-trash-can"></i></button>`
                : '';
            html += `
                <div class="col-6 col-md-3 shelf-book-slot">
                    <a href="${escapeHtmlShelf(book.ebook_url)}" target="_blank" class="book-3d-item ${themeCls}" title="คลิกเพื่อเปิดอ่าน E-Book: ${escapeHtmlShelf(book.title)}">
                        ${buildBookCoverInnerHtml(book)}
                    </a>
                    <div class="shelf-book-caption">
                        <div class="shelf-book-caption-title" title="${escapeHtmlShelf(book.title)}">${escapeHtmlShelf(book.title)}</div>
                        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 small">
                            ${metaText ? `<span class="text-muted" style="font-size:0.74rem;">${escapeHtmlShelf(metaText)}</span>` : ''}
                            <a href="${escapeHtmlShelf(book.pdf_url)}" target="_blank" class="text-primary text-decoration-none fw-bold" style="font-size:0.74rem;" title="ดาวน์โหลด PDF ต้นฉบับ">
                                <i class="fa-solid fa-file-pdf me-0.5"></i>PDF
                            </a>
                            ${adminBtns}
                        </div>
                    </div>
                </div>
            `;
        });
        html += `</div>`;
        html += `<div class="wood-plank"></div>`;
        html += `</div>`;
    }
    container.innerHTML = html;
}

function selectShelfCat(cat, btnEl) {
    currentShelfCat = cat;
    document.querySelectorAll('#ebookCatFilters .shelf-cat-pill').forEach(b => b.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');
    filterBookshelf();
}

function filterBookshelf() {
    const q = (document.getElementById('ebookSearchInput')?.value || '').trim().toLowerCase();
    const filtered = ALL_EBOOKS.filter(b => {
        const matchCat = !currentShelfCat || (b.category === currentShelfCat);
        const matchQ = !q ||
            (b.title || '').toLowerCase().includes(q) ||
            (b.category || '').toLowerCase().includes(q) ||
            String(b.year || '').toLowerCase().includes(q);
        return matchCat && matchQ;
    });
    renderBookshelf(filtered);
}

function resetBookshelfFilter() {
    const inp = document.getElementById('ebookSearchInput');
    if (inp) inp.value = '';
    const firstBtn = document.querySelector('#ebookCatFilters .shelf-cat-pill[data-cat=""]');
    selectShelfCat('', firstBtn);
}

const EbookStudio = {
    modal: null,
    existingCoverUrl: '',
    uploadedPreviewUrl: '',

    getModal() {
        if (!this.modal) {
            const el = document.getElementById('ebookStudioModal');
            if (el) this.modal = bootstrap.Modal.getOrCreateInstance(el);
        }
        return this.modal;
    },

    getCsrfHeaders() {
        const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const metaHeader = document.querySelector('meta[name="csrf-header"]')?.getAttribute('content');
        return {
            'X-Requested-With': 'XMLHttpRequest',
            [metaHeader || EBOOK_CSRF_HEADER]: metaToken || EBOOK_CSRF_TOKEN
        };
    },

    toast(msg, type = 'success') {
        if (window.App && typeof App.toast === 'function') {
            App.toast(msg, type);
        } else {
            alert(msg);
        }
    },

    openNew() {
        const form = document.getElementById('ebookStudioForm');
        if (form) form.reset();
        document.getElementById('ebId').value = '';
        document.getElementById('ebYear').value = String(new Date().getFullYear() + 543);
        document.getElementById('ebookModalTitle').textContent = 'เพิ่มหนังสือ E-Book ใหม่';
        document.getElementById('ebCurrentPdfHint').classList.add('d-none');
        document.getElementById('ebRemoveCoverWrap').classList.add('d-none');
        document.getElementById('ebRemoveCover').checked = false;
        this.existingCoverUrl = '';
        this.uploadedPreviewUrl = '';
        this.selectTheme('emerald');
        this.updatePreview();
        this.getModal()?.show();
    },

    openEdit(id) {
        const book = ALL_EBOOKS.find(b => b.id === id);
        if (!book) return;
        const form = document.getElementById('ebookStudioForm');
        if (form) form.reset();
        document.getElementById('ebId').value = book.id || '';
        document.getElementById('ebTitle').value = book.title || '';
        document.getElementById('ebCategory').value = book.category || '';
        document.getElementById('ebYear').value = book.year || '';
        document.getElementById('ebPages').value = book.pages || '';
        document.getElementById('ebFileUrl').value = book.file_url || '';
        document.getElementById('ebookModalTitle').textContent = 'แก้ไขหนังสือ E-Book';

        const pdfHint = document.getElementById('ebCurrentPdfHint');
        if (book.file_url) {
            pdfHint.textContent = 'ไฟล์ปัจจุบัน: ' + book.file_url + ' (เลือกไฟล์ใหม่หากต้องการเปลี่ยน)';
            pdfHint.classList.remove('d-none');
        } else {
            pdfHint.classList.add('d-none');
        }

        this.existingCoverUrl = book.cover_image || '';
        this.uploadedPreviewUrl = '';
        const remWrap = document.getElementById('ebRemoveCoverWrap');
        const remChk = document.getElementById('ebRemoveCover');
        remChk.checked = false;
        if (this.existingCoverUrl) {
            remWrap.classList.remove('d-none');
        } else {
            remWrap.classList.add('d-none');
        }

        this.selectTheme(book.theme || 'emerald');
        this.updatePreview();
        this.getModal()?.show();
    },

    selectTheme(theme) {
        document.getElementById('ebTheme').value = theme;
        document.querySelectorAll('#ebThemeSwatches .ebook-theme-swatch').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.theme === theme);
        });
        this.updatePreview();
    },

    onCoverFileChange(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.uploadedPreviewUrl = e.target.result;
                document.getElementById('ebRemoveCover').checked = false;
                this.updatePreview();
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            this.uploadedPreviewUrl = '';
            this.updatePreview();
        }
    },

    updatePreview() {
        const previewEl = document.getElementById('ebLivePreviewBook');
        const badgeEl = document.getElementById('ebPreviewModeBadge');
        if (!previewEl) return;

        const title = document.getElementById('ebTitle')?.value.trim() || 'ชื่อหนังสือ E-Book';
        const category = document.getElementById('ebCategory')?.value.trim() || 'หนังสืออิเล็กทรอนิกส์';
        const year = document.getElementById('ebYear')?.value.trim() || '';
        const theme = document.getElementById('ebTheme')?.value || 'emerald';
        const removeCover = document.getElementById('ebRemoveCover')?.checked;

        const coverUrl = this.uploadedPreviewUrl || (!removeCover ? this.existingCoverUrl : '');
        previewEl.className = 'book-3d-item ' + (coverUrl ? 'book-has-cover' : ('cover-' + theme));
        previewEl.innerHTML = buildBookCoverInnerHtml({
            title, category, year, theme, cover_image: coverUrl
        });

        if (badgeEl) {
            if (coverUrl) {
                badgeEl.className = 'mt-2.5 badge bg-primary-subtle text-primary rounded-pill px-3 py-1';
                badgeEl.textContent = 'ใช้ภาพหน้าปกที่อัปโหลด';
            } else {
                badgeEl.className = 'mt-2.5 badge bg-success-subtle text-success rounded-pill px-3 py-1';
                badgeEl.textContent = 'โหมดสร้างปก 3D อัตโนมัติ';
            }
        }
    },

    async save(e) {
        e.preventDefault();
        const form = document.getElementById('ebookStudioForm');
        const btn = document.getElementById('ebSaveBtn');
        const origHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1.5"></span>กำลังบันทึก...';

        try {
            const fd = new FormData(form);
            const res = await fetch(EBOOK_SAVE_URL, {
                method: 'POST',
                headers: this.getCsrfHeaders(),
                body: fd
            });
            const data = await res.json();
            if (data.status === 'success') {
                this.toast(data.message || 'บันทึกหนังสือ E-Book เรียบร้อยแล้ว', 'success');
                this.getModal()?.hide();
                setTimeout(() => window.location.reload(), 650);
            } else {
                this.toast(data.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        } catch (err) {
            this.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาลองใหม่อีกครั้ง', 'error');
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    },

    async remove(id, title) {
        if (!confirm(`ยืนยันการลบหนังสือ "${title}" ออกจากชั้นหนังสือ E-Book?`)) return;
        try {
            const res = await fetch(EBOOK_DELETE_BASE + '/' + encodeURIComponent(id), {
                method: 'POST',
                headers: this.getCsrfHeaders()
            });
            const data = await res.json();
            if (data.status === 'success') {
                this.toast(data.message || 'ลบหนังสือเรียบร้อยแล้ว', 'success');
                setTimeout(() => window.location.reload(), 600);
            } else {
                this.toast(data.message || 'ไม่สามารถลบหนังสือได้', 'error');
            }
        } catch (err) {
            this.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => renderBookshelf(ALL_EBOOKS));
</script>

<?= $this->endSection() ?>

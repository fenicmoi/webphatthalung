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
                        <a href="<?= base_url('admin/strategy') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-plus"></i> เพิ่มหนังสือ E-Book
                        </a>
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

<script>
const ALL_EBOOKS = <?= json_encode(array_values($books), JSON_UNESCAPED_UNICODE) ?>;
const SITE_LOGO_URL = <?= json_encode($siteLogo) ?>;
let currentShelfCat = '';

function escapeHtmlShelf(str) {
    return String(str || '').replace(/[&<>"']/g, s => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[s]));
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
            const themeCls = 'cover-' + (book.theme || 'emerald');
            const yrText = book.year ? ('พ.ศ. ' + String(book.year).replace(/^พ\.ศ\.\s*/u, '')) : 'ฉบับทางการ';
            const metaText = [book.pages ? (book.pages + ' หน้า') : '', book.file_size || ''].filter(Boolean).join(' • ');
            html += `
                <div class="col-6 col-md-3 shelf-book-slot">
                    <a href="${escapeHtmlShelf(book.ebook_url)}" target="_blank" class="book-3d-item ${themeCls}" title="คลิกเพื่อเปิดอ่าน E-Book: ${escapeHtmlShelf(book.title)}">
                        <div class="text-center">
                            <div class="book-cover-emblem">
                                <img src="${SITE_LOGO_URL}" alt="ตราจังหวัดพัทลุง">
                            </div>
                            <span class="book-cover-cat">${escapeHtmlShelf(book.category)}</span>
                        </div>
                        <div class="my-auto text-center px-1">
                            <div class="book-cover-title">${escapeHtmlShelf(book.title)}</div>
                        </div>
                        <div class="book-cover-footer">
                            <span>${escapeHtmlShelf(yrText)}</span>
                            <span class="book-read-pill"><i class="fa-solid fa-book-open me-1"></i>อ่าน</span>
                        </div>
                    </a>
                    <div class="shelf-book-caption">
                        <div class="shelf-book-caption-title" title="${escapeHtmlShelf(book.title)}">${escapeHtmlShelf(book.title)}</div>
                        <div class="d-flex align-items-center justify-content-center gap-2 small">
                            ${metaText ? `<span class="text-muted" style="font-size:0.74rem;">${escapeHtmlShelf(metaText)}</span>` : ''}
                            <a href="${escapeHtmlShelf(book.pdf_url)}" target="_blank" class="text-primary text-decoration-none fw-bold" style="font-size:0.74rem;" title="ดาวน์โหลด PDF ต้นฉบับ">
                                <i class="fa-solid fa-file-pdf me-0.5"></i>PDF
                            </a>
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

document.addEventListener('DOMContentLoaded', () => renderBookshelf(ALL_EBOOKS));
</script>

<?= $this->endSection() ?>

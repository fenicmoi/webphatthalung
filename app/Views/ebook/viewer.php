<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">
    <title><?= esc($title) ?> | E-Book จังหวัดพัทลุง</title>
    <link rel="icon" type="image/png" href="<?= base_url('uploads/logo/logo_1787048018.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&family=Sarabun:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg-studio-1: #0b1320;
            --bg-studio-2: #162235;
            --toolbar-bg: rgba(15, 23, 42, 0.88);
            --toolbar-border: rgba(255, 255, 255, 0.1);
            --accent: #10b981;
            --accent-hover: #059669;
            --gold: #f59e0b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Sarabun', 'Prompt', sans-serif;
            background: radial-gradient(circle at 50% 45%, #1e324a 0%, var(--bg-studio-2) 42%, var(--bg-studio-1) 100%);
            color: var(--text-main);
            user-select: none;
            -webkit-user-select: none;
        }

        .ebook-app {
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            position: relative;
        }

        /* ==================== TOP TOOLBAR ==================== */
        .ebook-topbar {
            height: 58px;
            padding: 0 16px;
            background: var(--toolbar-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--toolbar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            z-index: 40;
            flex-shrink: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            flex: 1;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            text-decoration: none;
            font-family: 'Prompt', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        .book-meta {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }
        .book-title {
            font-family: 'Prompt', sans-serif;
            font-size: 0.96rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .book-sub {
            font-size: 0.74rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .book-badge {
            background: rgba(16, 185, 129, 0.18);
            color: #6ee7b7;
            padding: 1px 8px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .tool-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 36px;
            padding: 0 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
            font-family: 'Prompt', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s ease;
        }
        .tool-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }
        .tool-btn.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .tool-btn-primary {
            background: linear-gradient(135deg, #10b981, #059669);
            border-color: transparent;
            color: #fff;
        }
        .tool-btn-primary:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
        }

        /* ==================== STAGE AREA ==================== */
        .ebook-stage-wrap {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ebook-zoom-layer {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease-out;
            transform-origin: center center;
            padding: 18px 64px;
        }

        .ebook-zoom-layer.is-zoomed {
            cursor: grab;
        }
        .ebook-zoom-layer.is-zoomed.is-dragging {
            cursor: grabbing;
            transition: none;
        }

        #flipbookContainer {
            position: relative;
            filter: drop-shadow(0 25px 45px rgba(0, 0, 0, 0.65));
        }

        /* หน้ากระดาษแต่ละหน้า */
        .ebook-page {
            background: #ffffff;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 0 24px rgba(0, 0, 0, 0.06);
        }

        .ebook-page canvas {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
        }

        .page-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.85rem;
        }

        .page-number-stamp {
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            color: rgba(15, 23, 42, 0.45);
            pointer-events: none;
        }

        /* ปุ่มลูกศรซ้าย-ขวาข้างหนังสือ */
        .side-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 46px;
            height: 76px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: #fff;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 25;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            backdrop-filter: blur(6px);
        }
        .side-nav-btn:hover {
            background: rgba(16, 185, 129, 0.85);
            border-color: transparent;
            transform: translateY(-50%) scale(1.06);
        }
        .side-nav-btn:disabled {
            opacity: 0.22;
            cursor: not-allowed;
            pointer-events: none;
        }
        .side-nav-prev { left: 14px; }
        .side-nav-next { right: 14px; }

        /* ==================== THUMBNAIL DRAWER ==================== */
        .thumb-drawer {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 240px;
            background: rgba(15, 23, 42, 0.96);
            backdrop-filter: blur(12px);
            border-right: 1px solid var(--toolbar-border);
            z-index: 35;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            display: flex;
            flex-direction: column;
        }
        .thumb-drawer.open {
            transform: translateX(0);
        }
        .thumb-drawer-header {
            padding: 12px 14px;
            border-bottom: 1px solid var(--toolbar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'Prompt', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .thumb-list {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            align-content: start;
        }
        .thumb-item {
            background: rgba(255, 255, 255, 0.06);
            border: 2px solid transparent;
            border-radius: 8px;
            padding: 6px;
            cursor: pointer;
            text-align: center;
            transition: all 0.18s ease;
        }
        .thumb-item:hover {
            background: rgba(255, 255, 255, 0.12);
        }
        .thumb-item.active {
            border-color: var(--accent);
            background: rgba(16, 185, 129, 0.16);
        }
        .thumb-canvas-wrap {
            width: 100%;
            aspect-ratio: 1 / 1.414;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
            color: #64748b;
            font-size: 0.72rem;
        }
        .thumb-canvas-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .thumb-label {
            font-size: 0.72rem;
            color: #cbd5e1;
        }

        /* ==================== BOTTOM CONTROL BAR ==================== */
        .ebook-bottombar {
            height: 58px;
            padding: 0 16px;
            background: var(--toolbar-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid var(--toolbar-border);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            z-index: 40;
            flex-shrink: 0;
        }

        .ctrl-group {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.05);
            padding: 4px 8px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .ctrl-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: transparent;
            border: none;
            color: #e2e8f0;
            font-size: 0.92rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }
        .ctrl-btn:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }
        .ctrl-btn.active {
            background: var(--accent);
            color: #fff;
        }

        .page-jump-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.84rem;
            color: #cbd5e1;
            padding: 0 6px;
        }
        .page-input {
            width: 52px;
            height: 30px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.35);
            color: #fff;
            text-align: center;
            font-family: 'Prompt', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .page-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .zoom-label {
            min-width: 44px;
            text-align: center;
            font-size: 0.78rem;
            color: #cbd5e1;
            font-family: 'Prompt', sans-serif;
        }

        /* ==================== LOADING & ERROR OVERLAY ==================== */
        .overlay-state {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 24px;
            z-index: 30;
            background: radial-gradient(circle at center, rgba(30, 50, 74, 0.88), rgba(11, 19, 32, 0.96));
        }
        .overlay-state.hidden { display: none; }

        .loader-book {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            background: linear-gradient(135deg, #10b981, #0284c7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #fff;
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.35);
            margin-bottom: 18px;
            animation: pulseBook 1.5s infinite ease-in-out;
        }
        @keyframes pulseBook {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.07); }
        }

        .progress-track {
            width: 260px;
            max-width: 80vw;
            height: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            overflow: hidden;
            margin-top: 14px;
        }
        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #10b981, #38bdf8);
            border-radius: 999px;
            transition: width 0.25s ease;
        }

        .error-card {
            max-width: 460px;
            background: rgba(30, 41, 59, 0.92);
            border: 1px solid rgba(248, 113, 113, 0.3);
            border-radius: 20px;
            padding: 28px 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
        }

        /* Toast notification */
        .ebook-toast {
            position: fixed;
            top: 72px;
            left: 50%;
            transform: translateX(-50%) translateY(-12px);
            background: rgba(16, 185, 129, 0.95);
            color: #fff;
            padding: 8px 18px;
            border-radius: 999px;
            font-family: 'Prompt', sans-serif;
            font-size: 0.84rem;
            font-weight: 500;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
            opacity: 0;
            pointer-events: none;
            transition: all 0.25s ease;
            z-index: 60;
        }
        .ebook-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .ebook-topbar { padding: 0 10px; height: 54px; }
            .btn-back span, .tool-btn span { display: none; }
            .tool-btn { padding: 0 10px; }
            .ebook-zoom-layer { padding: 10px 14px; }
            .side-nav-btn { width: 34px; height: 54px; font-size: 1rem; }
            .side-nav-prev { left: 6px; }
            .side-nav-next { right: 6px; }
            .zoom-group { display: none; }
            .ebook-bottombar { padding: 0 8px; gap: 6px; }
        }
    </style>
</head>
<body>

<div class="ebook-app" id="ebookApp">
    <!-- TOP BAR -->
    <header class="ebook-topbar">
        <div class="topbar-left">
            <a href="<?= esc($backUrl) ?>" class="btn-back" title="กลับหน้าเดิม">
                <i class="fa-solid fa-arrow-left"></i>
                <span>ย้อนกลับ</span>
            </a>
            <div class="book-meta">
                <div class="book-title" title="<?= esc($bookTitle) ?>"><?= esc($bookTitle) ?></div>
                <div class="book-sub">
                    <span class="book-badge"><i class="fa-solid fa-book-open me-1"></i> <?= esc($category) ?></span>
                    <?php if (!empty($period)): ?>
                        <span><?= esc($period) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="topbar-actions">
            <button type="button" class="tool-btn" id="btnShareLink" onclick="shareEbookLink()" title="คัดลอกลิงก์ E-Book">
                <i class="fa-solid fa-share-nodes"></i>
                <span>แชร์ลิงก์</span>
            </button>
            <?php if (!empty($downloadUrl)): ?>
                <a href="<?= esc($downloadUrl) ?>" target="_blank" class="tool-btn" title="เปิดไฟล์ PDF ต้นฉบับ">
                    <i class="fa-solid fa-up-right-from-square"></i>
                    <span>เปิด PDF</span>
                </a>
                <a href="<?= esc($downloadUrl) ?>" download class="tool-btn tool-btn-primary" onclick="trackEbookDownload()" title="ดาวน์โหลดไฟล์ PDF">
                    <i class="fa-solid fa-download"></i>
                    <span>ดาวน์โหลด</span>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- MAIN STAGE -->
    <main class="ebook-stage-wrap" id="stageWrap">
        <!-- Thumbnail Drawer -->
        <aside class="thumb-drawer" id="thumbDrawer">
            <div class="thumb-drawer-header">
                <span><i class="fa-solid fa-grid-2 me-1"></i> สารบัญหน้าหนังสือ</span>
                <button type="button" class="ctrl-btn" onclick="toggleThumbDrawer()" title="ปิด">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="thumb-list" id="thumbList"></div>
        </aside>

        <!-- Side Navigation Arrows -->
        <button type="button" class="side-nav-btn side-nav-prev" id="btnSidePrev" onclick="flipPrev()" title="หน้าก่อนหน้า (ลูกศรซ้าย)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button type="button" class="side-nav-btn side-nav-next" id="btnSideNext" onclick="flipNext()" title="หน้าถัดไป (ลูกศรขวา)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <!-- Zoomable Flipbook Layer -->
        <div class="ebook-zoom-layer" id="zoomLayer">
            <div id="flipbookContainer"></div>
        </div>

        <!-- Loading Overlay -->
        <div class="overlay-state" id="loadingOverlay">
            <div class="loader-book"><i class="fa-solid fa-book-open-reader"></i></div>
            <h3 style="font-family:'Prompt',sans-serif; font-size:1.15rem; font-weight:600; margin-bottom:6px;">กำลังเตรียมหนังสือ E-Book...</h3>
            <p id="loadingStatus" style="color:#94a3b8; font-size:0.9rem;">กำลังโหลดเอกสาร PDF โปรดรอสักครู่</p>
            <div class="progress-track">
                <div class="progress-fill" id="loadingProgress"></div>
            </div>
        </div>

        <!-- Error Overlay -->
        <div class="overlay-state hidden" id="errorOverlay">
            <div class="error-card">
                <div style="font-size:2.4rem; color:#f87171; margin-bottom:12px;">
                    <i class="fa-solid fa-book-skull"></i>
                </div>
                <h3 style="font-family:'Prompt',sans-serif; font-size:1.15rem; margin-bottom:8px;">ไม่สามารถเปิดอ่านไฟล์ E-Book ได้</h3>
                <p id="errorMessage" style="color:#cbd5e1; font-size:0.9rem; line-height:1.6; margin-bottom:18px;">
                    ไฟล์เอกสารอาจยังไม่ได้อัปโหลดขึ้นเซิร์ฟเวอร์ หรือลิงก์ไฟล์ไม่ถูกต้อง
                </p>
                <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                    <?php if (!empty($downloadUrl)): ?>
                        <a href="<?= esc($downloadUrl) ?>" target="_blank" class="tool-btn tool-btn-primary">
                            <i class="fa-solid fa-file-pdf"></i> ลองเปิดไฟล์ PDF โดยตรง
                        </a>
                    <?php endif; ?>
                    <a href="<?= esc($backUrl) ?>" class="tool-btn">
                        <i class="fa-solid fa-arrow-left"></i> กลับหน้าเดิม
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- BOTTOM CONTROLS -->
    <footer class="ebook-bottombar">
        <!-- Thumbnails & Page Mode Toggle -->
        <div class="ctrl-group">
            <button type="button" class="ctrl-btn" id="btnThumbToggle" onclick="toggleThumbDrawer()" title="แสดงสารบัญภาพย่อทุกหน้า">
                <i class="fa-solid fa-table-cells-large"></i>
            </button>
            <button type="button" class="ctrl-btn" id="btnPageMode" onclick="toggleSinglePageMode()" title="สลับโหมด 1 หน้าเดี่ยว / 2 หน้าคู่">
                <i class="fa-solid fa-book-open"></i>
            </button>
        </div>

        <!-- Page Navigation -->
        <div class="ctrl-group">
            <button type="button" class="ctrl-btn" onclick="flipFirst()" title="หน้าแรก (Home)">
                <i class="fa-solid fa-backward-step"></i>
            </button>
            <button type="button" class="ctrl-btn" onclick="flipPrev()" title="หน้าก่อนหน้า (←)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="page-jump-wrap">
                <span>หน้า</span>
                <input type="number" id="pageInput" class="page-input" value="1" min="1" onchange="jumpToPage(this.value)">
                <span>/ <strong id="totalPagesLabel">-</strong></span>
            </div>
            <button type="button" class="ctrl-btn" onclick="flipNext()" title="หน้าถัดไป (→)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            <button type="button" class="ctrl-btn" onclick="flipLast()" title="หน้าสุดท้าย (End)">
                <i class="fa-solid fa-forward-step"></i>
            </button>
        </div>

        <!-- Rotate Controls (สำหรับหน้าตารางแนวนอน/ตะแคง) -->
        <div class="ctrl-group">
            <button type="button" class="ctrl-btn" onclick="rotateBook(-90)" title="หมุนซ้าย 90° (Shift+R)">
                <i class="fa-solid fa-rotate-left"></i>
            </button>
            <button type="button" class="ctrl-btn" id="btnRotateRight" onclick="rotateBook(90)" title="หมุนขวา 90° สำหรับหน้าตารางแนวนอน (R)">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>

        <!-- Zoom Controls -->
        <div class="ctrl-group zoom-group">
            <button type="button" class="ctrl-btn" onclick="changeZoom(-0.2)" title="ซูมออก (-)">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
            </button>
            <span class="zoom-label" id="zoomLabel">100%</span>
            <button type="button" class="ctrl-btn" onclick="changeZoom(0.2)" title="ซูมเข้า (+)">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
            </button>
            <button type="button" class="ctrl-btn" onclick="resetZoom()" title="รีเซ็ตขนาดและการหมุน (0)">
                <i class="fa-solid fa-arrows-rotate"></i>
            </button>
        </div>

        <!-- Sound & Fullscreen -->
        <div class="ctrl-group">
            <button type="button" class="ctrl-btn active" id="btnSoundToggle" onclick="toggleFlipSound()" title="เปิด/ปิดเสียงพลิกกระดาษ">
                <i class="fa-solid fa-volume-high"></i>
            </button>
            <button type="button" class="ctrl-btn" id="btnFullscreen" onclick="toggleFullscreen()" title="โหมดเต็มจอ (F)">
                <i class="fa-solid fa-expand"></i>
            </button>
        </div>
    </footer>
</div>

<div class="ebook-toast" id="ebookToast"></div>

<!-- PDF.js & StPageFlip CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.min.js"></script>

<script>
const EBOOK_CONFIG = {
    streamUrl: <?= json_encode($streamUrl) ?>,
    downloadUrl: <?= json_encode($downloadUrl) ?>,
    isValidPdf: <?= $isValidPdf ? 'true' : 'false' ?>,
    docId: <?= json_encode($docId) ?>,
    trackDownloadUrl: <?= json_encode(base_url('strategy/download')) ?>
};

if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

let pdfDoc = null;
let pageFlip = null;
let totalPages = 0;
let renderedPages = new Set();
let renderingPages = new Set();
let soundEnabled = true;
let audioCtx = null;

// Zoom, Rotate & View Mode state
let currentZoom = 1;
let currentRotation = 0;
let panX = 0, panY = 0;
let isDragging = false, dragStartX = 0, dragStartY = 0;
let singlePageMode = window.innerWidth <= 768;
let bookAspectRatio = 0.707;

function showToast(msg) {
    const el = document.getElementById('ebookToast');
    el.textContent = msg;
    el.classList.add('show');
    clearTimeout(el._timer);
    el._timer = setTimeout(() => el.classList.remove('show'), 2200);
}

function showError(msg) {
    document.getElementById('loadingOverlay').classList.add('hidden');
    document.getElementById('errorMessage').textContent = msg;
    document.getElementById('errorOverlay').classList.remove('hidden');
}

// สังเคราะห์เสียงพลิกกระดาษนุ่มๆ ด้วย Web Audio API (ไม่ต้องโหลดไฟล์ mp3)
function playFlipSound() {
    if (!soundEnabled) return;
    try {
        if (!audioCtx) {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (!AudioContextClass) return;
            audioCtx = new AudioContextClass();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        const duration = 0.16;
        const bufferSize = Math.floor(audioCtx.sampleRate * duration);
        const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
        const data = buffer.getChannelData(0);
        for (let i = 0; i < bufferSize; i++) {
            const env = Math.sin((i / bufferSize) * Math.PI);
            data[i] = (Math.random() * 2 - 1) * env * 0.16;
        }
        const noise = audioCtx.createBufferSource();
        noise.buffer = buffer;

        const filter = audioCtx.createBiquadFilter();
        filter.type = 'bandpass';
        filter.frequency.setValueAtTime(1400, audioCtx.currentTime);
        filter.frequency.exponentialRampToValueAtTime(550, audioCtx.currentTime + duration);
        filter.Q.value = 1.1;

        const gain = audioCtx.createGain();
        gain.gain.setValueAtTime(0.35, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);

        noise.connect(filter);
        filter.connect(gain);
        gain.connect(audioCtx.destination);
        noise.start();
    } catch (e) {}
}

function toggleFlipSound() {
    soundEnabled = !soundEnabled;
    const btn = document.getElementById('btnSoundToggle');
    btn.classList.toggle('active', soundEnabled);
    btn.innerHTML = soundEnabled
        ? '<i class="fa-solid fa-volume-high"></i>'
        : '<i class="fa-solid fa-volume-xmark"></i>';
    showToast(soundEnabled ? 'เปิดเสียงพลิกกระดาษแล้ว' : 'ปิดเสียงพลิกกระดาษแล้ว');
}

async function renderPdfPage(pageNum, scale = 1.65) {
    if (!pdfDoc || pageNum < 1 || pageNum > totalPages) return;
    if (renderedPages.has(pageNum) || renderingPages.has(pageNum)) return;

    renderingPages.add(pageNum);
    try {
        const page = await pdfDoc.getPage(pageNum);
        const viewport = page.getViewport({ scale });
        const pageEl = document.getElementById('ebook-page-' + pageNum);
        if (!pageEl) return;

        const canvas = pageEl.querySelector('canvas');
        const ctx = canvas.getContext('2d', { alpha: false });
        canvas.width = Math.floor(viewport.width);
        canvas.height = Math.floor(viewport.height);

        await page.render({ canvasContext: ctx, viewport }).promise;

        const placeholder = pageEl.querySelector('.page-placeholder');
        if (placeholder) placeholder.style.display = 'none';

        renderedPages.add(pageNum);

        // อัปเดตภาพย่อใน Thumbnail Drawer ด้วย (ถ้ายังไม่มี)
        const thumbWrap = document.getElementById('thumb-wrap-' + pageNum);
        if (thumbWrap && !thumbWrap.querySelector('img')) {
            const img = document.createElement('img');
            img.src = canvas.toDataURL('image/jpeg', 0.55);
            img.alt = 'หน้า ' + pageNum;
            thumbWrap.innerHTML = '';
            thumbWrap.appendChild(img);
        }
    } catch (err) {
        console.warn('Render page ' + pageNum + ' error:', err);
    } finally {
        renderingPages.delete(pageNum);
    }
}

// Lazy render หน้าปัจจุบันและหน้าใกล้เคียงเพื่อให้พลิกลื่นมาก
function renderVisibleWindow(currentIndex) {
    const currentPage = currentIndex + 1;
    const targets = [
        currentPage,
        currentPage + 1,
        currentPage + 2,
        currentPage - 1,
        currentPage + 3,
        currentPage - 2
    ];
    targets.forEach(p => {
        if (p >= 1 && p <= totalPages) {
            renderPdfPage(p);
        }
    });
}

function updatePageUI(pageIndex) {
    const currentPage = pageIndex + 1;
    const input = document.getElementById('pageInput');
    if (input) input.value = currentPage;

    document.getElementById('btnSidePrev').disabled = (pageIndex <= 0);
    document.getElementById('btnSideNext').disabled = (pageIndex >= totalPages - 1);

    document.querySelectorAll('.thumb-item').forEach(el => {
        el.classList.toggle('active', parseInt(el.dataset.page, 10) === currentPage);
    });
}

function computeBookDimensions(aspectRatio) {
    const stage = document.getElementById('stageWrap');
    const isMobile = window.innerWidth <= 768;
    const isPortraitMode = singlePageMode || isMobile;
    const availW = stage.clientWidth - (isMobile ? 28 : 140);
    const availH = stage.clientHeight - 36;

    let pageH = Math.min(availH, 860);
    let pageW = Math.floor(pageH * aspectRatio);

    if (!isPortraitMode) {
        if (pageW * 2 > availW) {
            pageW = Math.floor(availW / 2);
            pageH = Math.floor(pageW / aspectRatio);
        }
    } else {
        if (pageW > availW) {
            pageW = Math.floor(availW);
            pageH = Math.floor(pageW / aspectRatio);
        }
    }

    return {
        width: Math.max(240, pageW),
        height: Math.max(320, pageH),
        isPortrait: isPortraitMode
    };
}

async function preparePdfWorker() {
    if (typeof pdfjsLib === 'undefined') return false;
    try {
        const workerUrl = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        const res = await fetch(workerUrl);
        if (res.ok) {
            const code = await res.text();
            const blob = new Blob([code], { type: 'application/javascript' });
            pdfjsLib.GlobalWorkerOptions.workerSrc = URL.createObjectURL(blob);
            return true;
        }
    } catch (e) {
        console.warn('Blob worker fallback:', e);
    }
    return false;
}

async function fetchPdfBytesWithProgress(url) {
    const res = await fetch(url, { credentials: 'same-origin' });
    if (!res.ok) {
        throw new Error(`HTTP ${res.status} - ไม่พบไฟล์เอกสารบนเซิร์ฟเวอร์`);
    }
    const contentLength = parseInt(res.headers.get('content-length') || '0', 10);
    if (!res.body || !res.body.getReader) {
        const buf = await res.arrayBuffer();
        document.getElementById('loadingProgress').style.width = '90%';
        return new Uint8Array(buf);
    }

    const reader = res.body.getReader();
    const chunks = [];
    let received = 0;

    while (true) {
        const { done, value } = await reader.read();
        if (done) break;
        chunks.push(value);
        received += value.length;
        if (contentLength > 0) {
            const pct = Math.min(90, Math.round((received / contentLength) * 90));
            document.getElementById('loadingProgress').style.width = pct + '%';
            document.getElementById('loadingStatus').textContent = `กำลังดาวน์โหลดเอกสาร PDF... (${pct}%)`;
        } else {
            const mb = (received / (1024 * 1024)).toFixed(1);
            document.getElementById('loadingProgress').style.width = '55%';
            document.getElementById('loadingStatus').textContent = `กำลังดาวน์โหลดเอกสาร PDF... (${mb} MB)`;
        }
    }

    const combined = new Uint8Array(received);
    let offset = 0;
    for (const chunk of chunks) {
        combined.set(chunk, offset);
        offset += chunk.length;
    }
    return combined;
}

async function initEbookViewer() {
    if (!EBOOK_CONFIG.isValidPdf || !EBOOK_CONFIG.streamUrl) {
        showError('ไม่พบลิงก์ไฟล์เอกสาร PDF สำหรับเปิดอ่าน E-Book');
        return;
    }

    if (typeof pdfjsLib === 'undefined' || typeof St === 'undefined' || !St.PageFlip) {
        showError('ไม่สามารถโหลดไลบรารีตัวอ่าน E-Book ได้ กรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ตแล้วลองใหม่');
        return;
    }

    try {
        document.getElementById('loadingStatus').textContent = 'กำลังเชื่อมต่อไฟล์เอกสาร PDF...';
        document.getElementById('loadingProgress').style.width = '10%';

        // โหลด PDF Worker เป็น Same-Origin Blob URL + ดาวน์โหลดไฟล์ PDF พร้อมกัน
        const [workerReady, pdfBytes] = await Promise.all([
            preparePdfWorker(),
            fetchPdfBytesWithProgress(EBOOK_CONFIG.streamUrl)
        ]);

        document.getElementById('loadingStatus').textContent = 'กำลังประมวลผลหน้าหนังสือ...';
        document.getElementById('loadingProgress').style.width = '94%';

        const loadingTask = pdfjsLib.getDocument({
            data: pdfBytes,
            cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
            cMapPacked: true,
            disableWorker: !workerReady
        });

        pdfDoc = await loadingTask.promise;
        totalPages = pdfDoc.numPages;

        document.getElementById('totalPagesLabel').textContent = totalPages;
        document.getElementById('pageInput').max = totalPages;
        document.getElementById('loadingStatus').textContent = `พบทั้งหมด ${totalPages} หน้า กำลังจัดรูปเล่มหนังสือ...`;
        document.getElementById('loadingProgress').style.width = '98%';

        // อ่านสัดส่วนหน้าแรกเพื่อกำหนดขนาดรูปเล่มให้ตรงกับ PDF จริง
        const firstPage = await pdfDoc.getPage(1);
        const firstVp = firstPage.getViewport({ scale: 1 });
        bookAspectRatio = firstVp.width / firstVp.height;

        // สร้างรายการ Thumbnails
        const thumbList = document.getElementById('thumbList');
        thumbList.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const thumbItem = document.createElement('div');
            thumbItem.className = 'thumb-item' + (i === 1 ? ' active' : '');
            thumbItem.dataset.page = i;
            thumbItem.onclick = () => {
                jumpToPage(i);
                if (window.innerWidth <= 768) toggleThumbDrawer(false);
            };
            thumbItem.innerHTML = `
                <div class="thumb-canvas-wrap" id="thumb-wrap-${i}">หน้า ${i}</div>
                <div class="thumb-label">หน้า ${i}</div>
            `;
            thumbList.appendChild(thumbItem);
        }

        await buildFlipbook(0);
        document.getElementById('loadingProgress').style.width = '100%';
        document.getElementById('loadingOverlay').classList.add('hidden');
        updatePageModeButton();

    } catch (err) {
        console.error('E-Book init error:', err);
        showError('ไม่สามารถโหลดไฟล์ PDF นี้ได้ (' + (err.message || 'ไม่พบไฟล์บนเซิร์ฟเวอร์') + ') คุณสามารถลองกดเปิดไฟล์ PDF โดยตรงด้านล่างครับ');
    }
}

async function buildFlipbook(startPageIndex = 0) {
    if (pageFlip) {
        try { pageFlip.destroy(); } catch (e) {}
        pageFlip = null;
    }

    renderedPages.clear();
    renderingPages.clear();

    const zoomLayer = document.getElementById('zoomLayer');
    let container = document.getElementById('flipbookContainer');
    if (container) container.remove();
    container = document.createElement('div');
    container.id = 'flipbookContainer';
    zoomLayer.appendChild(container);

    const dims = computeBookDimensions(bookAspectRatio);

    for (let i = 1; i <= totalPages; i++) {
        const pageDiv = document.createElement('div');
        pageDiv.className = 'ebook-page';
        pageDiv.id = 'ebook-page-' + i;
        if (i === 1 || i === totalPages) {
            pageDiv.setAttribute('data-density', 'hard');
        }
        pageDiv.innerHTML = `
            <canvas></canvas>
            <div class="page-placeholder">
                <i class="fa-solid fa-spinner fa-spin fa-lg"></i>
                <span>กำลังโหลดหน้า ${i}...</span>
            </div>
            <div class="page-number-stamp">${i}</div>
        `;
        container.appendChild(pageDiv);
    }

    pageFlip = new St.PageFlip(container, {
        width: dims.width,
        height: dims.height,
        size: 'fixed',
        minWidth: 240,
        maxWidth: 960,
        minHeight: 320,
        maxHeight: 1100,
        maxShadowOpacity: 0.55,
        showCover: !dims.isPortrait,
        mobileScrollSupport: true,
        usePortrait: dims.isPortrait,
        drawShadow: true,
        flippingTime: 650,
        startPage: startPageIndex,
        startZIndex: 2
    });

    pageFlip.loadFromHTML(document.querySelectorAll('.ebook-page'));

    pageFlip.on('flip', (e) => {
        playFlipSound();
        updatePageUI(e.data);
        renderVisibleWindow(e.data);
    });

    await renderPdfPage(startPageIndex + 1);
    updatePageUI(startPageIndex);
    renderVisibleWindow(startPageIndex);
}

function updatePageModeButton() {
    const btn = document.getElementById('btnPageMode');
    if (!btn) return;
    btn.classList.toggle('active', singlePageMode);
    btn.innerHTML = singlePageMode
        ? '<i class="fa-regular fa-file-lines"></i>'
        : '<i class="fa-solid fa-book-open"></i>';
    btn.title = singlePageMode
        ? 'กำลังแสดงแบบ 1 หน้าเดี่ยว (คลิกเพื่อสลับเป็น 2 หน้าคู่)'
        : 'กำลังแสดงแบบ 2 หน้าคู่ (คลิกเพื่อสลับเป็น 1 หน้าเดี่ยว)';
}

async function toggleSinglePageMode() {
    if (!pdfDoc || !pageFlip) return;
    const curIndex = pageFlip.getCurrentPageIndex();
    singlePageMode = !singlePageMode;
    updatePageModeButton();
    await buildFlipbook(curIndex);
    showToast(singlePageMode ? 'สลับเป็นโหมดแสดง 1 หน้าเต็มจอแล้ว' : 'สลับเป็นโหมดหนังสือ 2 หน้าคู่แล้ว');
}

function flipPrev() { if (pageFlip) pageFlip.flipPrev(); }
function flipNext() { if (pageFlip) pageFlip.flipNext(); }
function flipFirst() { if (pageFlip) pageFlip.turnToPage(0); }
function flipLast() { if (pageFlip && totalPages > 0) pageFlip.turnToPage(totalPages - 1); }

function jumpToPage(val) {
    if (!pageFlip) return;
    let p = parseInt(val, 10);
    if (isNaN(p)) return;
    p = Math.max(1, Math.min(totalPages, p));
    pageFlip.turnToPage(p - 1);
    renderVisibleWindow(p - 1);
}

// Thumbnail Drawer
function toggleThumbDrawer(forceState) {
    const drawer = document.getElementById('thumbDrawer');
    const btn = document.getElementById('btnThumbToggle');
    const willOpen = typeof forceState === 'boolean' ? forceState : !drawer.classList.contains('open');
    drawer.classList.toggle('open', willOpen);
    btn.classList.toggle('active', willOpen);

    // เมื่อเปิดสารบัญ ค่อยๆ เรนเดอร์ภาพย่อหน้าที่อยู่ใกล้เคียง
    if (willOpen && pageFlip) {
        const cur = pageFlip.getCurrentPageIndex() + 1;
        for (let p = Math.max(1, cur - 2); p <= Math.min(totalPages, cur + 8); p++) {
            renderPdfPage(p);
        }
    }
}

// Zoom, Rotate & Pan
function applyZoomTransform() {
    const layer = document.getElementById('zoomLayer');
    layer.style.transform = `translate(${panX}px, ${panY}px) scale(${currentZoom}) rotate(${currentRotation}deg)`;
    layer.classList.toggle('is-zoomed', currentZoom > 1 || currentRotation !== 0);
    document.getElementById('zoomLabel').textContent = Math.round(currentZoom * 100) + '%';
    const rotBtn = document.getElementById('btnRotateRight');
    if (rotBtn) rotBtn.classList.toggle('active', currentRotation % 360 !== 0);
}

function rotateBook(delta) {
    currentRotation = (currentRotation + delta + 360) % 360;
    panX = 0;
    panY = 0;
    applyZoomTransform();
    showToast(currentRotation === 0 ? 'รีเซ็ตมุมกระดาษเป็นปกติ (0°)' : `หมุนหน้ากระดาษเป็น ${currentRotation}°`);
}

function changeZoom(delta) {
    currentZoom = Math.max(0.8, Math.min(2.4, +(currentZoom + delta).toFixed(2)));
    if (currentZoom <= 1) { panX = 0; panY = 0; }
    applyZoomTransform();
}

function resetZoom() {
    currentZoom = 1;
    currentRotation = 0;
    panX = 0;
    panY = 0;
    applyZoomTransform();
    showToast('รีเซ็ตขนาดและมุมกระดาษแล้ว');
}

// Drag to pan when zoomed
const zoomLayerEl = document.getElementById('zoomLayer');
zoomLayerEl.addEventListener('mousedown', (e) => {
    if (currentZoom <= 1) return;
    isDragging = true;
    dragStartX = e.clientX - panX;
    dragStartY = e.clientY - panY;
    zoomLayerEl.classList.add('is-dragging');
});
window.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    panX = e.clientX - dragStartX;
    panY = e.clientY - dragStartY;
    applyZoomTransform();
});
window.addEventListener('mouseup', () => {
    if (!isDragging) return;
    isDragging = false;
    zoomLayerEl.classList.remove('is-dragging');
});

// Fullscreen
function toggleFullscreen() {
    const app = document.getElementById('ebookApp');
    if (!document.fullscreenElement) {
        if (app.requestFullscreen) app.requestFullscreen();
    } else {
        if (document.exitFullscreen) document.exitFullscreen();
    }
}
document.addEventListener('fullscreenchange', () => {
    const btn = document.getElementById('btnFullscreen');
    const isFs = !!document.fullscreenElement;
    btn.classList.toggle('active', isFs);
    btn.innerHTML = isFs
        ? '<i class="fa-solid fa-compress"></i>'
        : '<i class="fa-solid fa-expand"></i>';
});

// Share Link
function shareEbookLink() {
    const url = window.location.href;
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(() => showToast('คัดลอกลิงก์ E-Book แล้ว!'));
    } else {
        prompt('คัดลอกลิงก์ E-Book:', url);
    }
}

// Track Strategy Download
function trackEbookDownload() {
    if (EBOOK_CONFIG.docId) {
        fetch(EBOOK_CONFIG.trackDownloadUrl + '/' + encodeURIComponent(EBOOK_CONFIG.docId)).catch(() => {});
    }
}

// Keyboard Shortcuts
document.addEventListener('keydown', (e) => {
    if (document.activeElement && document.activeElement.tagName === 'INPUT') return;
    if (e.key === 'ArrowRight' || e.key === 'PageDown') { e.preventDefault(); flipNext(); }
    else if (e.key === 'ArrowLeft' || e.key === 'PageUp') { e.preventDefault(); flipPrev(); }
    else if (e.key === 'Home') { e.preventDefault(); flipFirst(); }
    else if (e.key === 'End') { e.preventDefault(); flipLast(); }
    else if (e.key === '+' || e.key === '=') { changeZoom(0.2); }
    else if (e.key === '-') { changeZoom(-0.2); }
    else if (e.key === '0') { resetZoom(); }
    else if (e.key.toLowerCase() === 'r') { rotateBook(e.shiftKey ? -90 : 90); }
    else if (e.key.toLowerCase() === 'f') { toggleFullscreen(); }
});

document.addEventListener('DOMContentLoaded', initEbookViewer);
</script>
</body>
</html>

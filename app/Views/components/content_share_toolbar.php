<?php
// =========================================================================
// คอมโพเนนต์: แถบเครื่องมือแชร์โซเชียล สั่งพิมพ์ และปรับขนาดตัวอักษร (Content Share & Print Toolbar)
// =========================================================================
$shareTitle = !empty($shareTitle) ? $shareTitle : (!empty($page['title']) ? $page['title'] : (!empty($news['title']) ? $news['title'] : 'จังหวัดพัทลุง'));
$shareUrl = current_url();
?>

<div class="content-toolbar-box d-flex align-items-center flex-wrap gap-2">
    
    <!-- 1. Left: Social Sharing Buttons -->
    <div class="d-flex align-items-center flex-wrap gap-1">
        <span class="small fw-semibold text-secondary me-1 d-none d-sm-inline" style="font-size: 0.8rem;">
            <i class="fa-solid fa-share-nodes text-primary me-1"></i> แชร์:
        </span>
        
        <!-- Facebook Share -->
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl) ?>" 
           target="_blank" rel="noopener noreferrer"
           class="btn btn-sm btn-social-share btn-facebook" 
           title="แชร์ไปยัง Facebook">
            <i class="fa-brands fa-facebook-f"></i>
        </a>

        <!-- Line Share -->
        <a href="https://social-plugins.line.me/lineit/share?url=<?= urlencode($shareUrl) ?>" 
           target="_blank" rel="noopener noreferrer"
           class="btn btn-sm btn-social-share btn-line" 
           title="แชร์ไปยัง LINE">
            <i class="fa-brands fa-line"></i>
        </a>

        <!-- X (Twitter) Share -->
        <a href="https://twitter.com/intent/tweet?text=<?= urlencode($shareTitle) ?>&url=<?= urlencode($shareUrl) ?>" 
           target="_blank" rel="noopener noreferrer"
           class="btn btn-sm btn-social-share btn-x" 
           title="แชร์ไปยัง X (Twitter)">
            <i class="fa-brands fa-x-twitter"></i>
        </a>

        <!-- Copy Link Button -->
        <button type="button" 
                class="btn btn-sm btn-social-share btn-copy" 
                onclick="copyPageLink('<?= $shareUrl ?>')" 
                title="คัดลอกลิงก์">
            <i class="fa-regular fa-copy"></i>
        </button>
    </div>

    <!-- 2. Right: Action Buttons (Print & Font Size) -->
    <div class="d-flex align-items-center gap-1.5 ms-auto">
        <!-- Font Size Scale -->
        <div class="btn-group btn-group-sm" role="group" aria-label="Font Size">
            <button type="button" class="btn btn-outline-secondary font-scale-btn" onclick="adjustContentFontSize(-1)" title="ลดขนาดตัวอักษร">A-</button>
            <button type="button" class="btn btn-outline-secondary font-scale-btn" onclick="adjustContentFontSize(0)" title="ขนาดปกติ">A</button>
            <button type="button" class="btn btn-outline-secondary font-scale-btn" onclick="adjustContentFontSize(1)" title="เพิ่มขนาดตัวอักษร">A+</button>
        </div>

        <!-- Print / PDF Button -->
        <button type="button" class="btn btn-sm btn-primary rounded-pill px-2.5 py-1 fw-bold shadow-sm d-flex align-items-center gap-1" 
                onclick="window.print()" 
                style="background: #1e40af; border: none; font-size: 0.8rem;"
                title="สั่งพิมพ์หน้านี้ หรือบันทึกเป็นไฟล์ PDF">
            <i class="fa-solid fa-print"></i>
            <span class="d-none d-sm-inline">สั่งพิมพ์/PDF</span>
        </button>
    </div>
</div>

<style>
/* Social Share Buttons Styling */
.btn-social-share {
    border-radius: 30px !important;
    padding: 0.35rem 0.85rem !important;
    font-size: 0.84rem !important;
    font-weight: 600 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none !important;
    transition: all 0.2s ease !important;
    color: #ffffff !important;
    text-decoration: none !important;
}

.btn-social-share:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    opacity: 0.92;
}

.btn-facebook {
    background: #1877f2 !important;
}

.btn-line {
    background: #06c755 !important;
}

.btn-x {
    background: #0f172a !important;
}

.btn-copy {
    background: #ffffff !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
}

.btn-copy:hover {
    background: #f1f5f9 !important;
    color: #1e293b !important;
}

.font-scale-btn {
    padding: 0.25rem 0.6rem !important;
    font-weight: bold;
    font-size: 0.82rem !important;
    border-color: #cbd5e1 !important;
    color: #475569 !important;
}

.font-scale-btn:hover {
    background-color: #e2e8f0 !important;
    color: #1e293b !important;
}

/* Print CSS Optimizations (รองรับการพิมพ์เอกสารราชการที่สวยงามและประหยัดหมึก) */
@media print {
    /* ซ่อนแถบเมนู, footer, แถบแชร์, studio bar และ floating dock เมื่อสั่งพิมพ์ */
    .gov-header-wrapper,
    footer,
    .content-toolbar-box,
    .btn,
    .sidebar,
    .admin-sidebar,
    .admin-topbar,
    .breadcrumb,
    .floating-capsule-dock,
    .ambient-glow,
    .nav-pills {
        display: none !important;
    }

    body {
        background: #ffffff !important;
        color: #000000 !important;
        font-size: 14pt !important;
        line-height: 1.6 !important;
    }

    .container, .card, .card-body {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    .dynamic-content, .article-reading-card {
        font-size: 13pt !important;
        color: #000000 !important;
    }

    img {
        max-width: 90% !important;
        page-break-inside: avoid;
    }

    h1, h2, h3, h4 {
        color: #000000 !important;
        page-break-after: avoid;
    }
}
</style>

<script>
function copyPageLink(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            if (typeof App !== 'undefined' && App.toast) {
                App.toast('คัดลอกลิงก์สำเร็จแล้ว! สามารถนำไปแชร์ต่อได้ทันที', 'success');
            } else {
                alert('คัดลอกลิงก์เรียบร้อยแล้ว');
            }
        }).catch(function() {
            prompt('คัดลอกลิงก์ด้านล่าง:', url);
        });
    } else {
        prompt('คัดลอกลิงก์ด้านล่าง:', url);
    }
}

function adjustContentFontSize(action) {
    const containers = document.querySelectorAll('.dynamic-content, .article-content, .page-content-container');
    containers.forEach(el => {
        let currentSize = parseFloat(window.getComputedStyle(el).fontSize);
        if (action === -1) {
            el.style.fontSize = Math.max(14, currentSize - 2) + 'px';
        } else if (action === 1) {
            el.style.fontSize = Math.min(26, currentSize + 2) + 'px';
        } else {
            el.style.fontSize = '1.05rem';
        }
    });
}
</script>

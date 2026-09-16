<?php
// =========================================================================
// คอมโพเนนต์: แถบเครื่องมือแชร์โซเชียล สั่งพิมพ์ และปรับขนาดตัวอักษร (Content Share & Print Toolbar)
// =========================================================================
$shareTitle = !empty($shareTitle) ? $shareTitle : (!empty($page['title']) ? $page['title'] : (!empty($news['title']) ? $news['title'] : 'จังหวัดพัทลุง'));
$shareUrl = function_exists('current_url') ? current_url() : (isset($_SERVER['REQUEST_URI']) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $_SERVER['REQUEST_URI'] : base_url());
?>

<div class="content-toolbar-box d-flex align-items-center flex-wrap gap-2">
    
    <!-- 1. Left: Social Sharing Buttons (0 Third-Party Blocking / Inline SVG) -->
    <div class="d-flex align-items-center flex-wrap gap-1.5">
        <span class="fw-medium text-secondary me-1 d-none d-sm-inline" style="font-family: 'Prompt', sans-serif; font-size: 0.86rem;">
            <i class="fa-solid fa-share-nodes text-success me-1"></i> แชร์:
        </span>
        
        <!-- Native Smart Share (Visible on mobile/devices supporting Web Share API) -->
        <button type="button" 
                class="btn btn-sm btn-social-share btn-smart-share d-none" 
                id="btnNativeShare"
                onclick="triggerNativeShare('<?= esc($shareTitle, 'js') ?>', '<?= esc($shareUrl, 'js') ?>')"
                title="แชร์ด่วนผ่านแอปพลิเคชัน">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
            <span style="font-family: 'Prompt', sans-serif; font-size: 0.82rem;">แชร์</span>
        </button>

        <!-- Facebook Share (Inline SVG - No FontAwesome Brand Font Download) -->
        <button type="button" 
                onclick="triggerDynamicShare('facebook', '<?= esc($shareUrl, 'js') ?>', '<?= esc($shareTitle, 'js') ?>')" 
                class="btn btn-sm btn-social-share btn-facebook" 
                title="แชร์ไปยัง Facebook"
                aria-label="แชร์ไปยัง Facebook">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </button>

        <!-- Line Share (Inline SVG) -->
        <button type="button" 
                onclick="triggerDynamicShare('line', '<?= esc($shareUrl, 'js') ?>', '<?= esc($shareTitle, 'js') ?>')" 
                class="btn btn-sm btn-social-share btn-line" 
                title="แชร์ไปยัง LINE"
                aria-label="แชร์ไปยัง LINE">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M24 10.304c0-5.369-5.383-9.738-12-9.738-6.616 0-12 4.369-12 9.738 0 4.814 4.269 8.846 10.036 9.608.391.084.922.258 1.057.592.121.303.079.777.039 1.085l-.171 1.027c-.053.303-.242 1.186 1.039.645 1.281-.54 6.911-4.069 9.428-6.967 1.739-1.907 2.572-3.843 2.572-5.99z"/></svg>
        </button>

        <!-- X (Twitter) Share (Inline SVG) -->
        <button type="button" 
                onclick="triggerDynamicShare('x', '<?= esc($shareUrl, 'js') ?>', '<?= esc($shareTitle, 'js') ?>')" 
                class="btn btn-sm btn-social-share btn-x" 
                title="แชร์ไปยัง X (Twitter)"
                aria-label="แชร์ไปยัง X (Twitter)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </button>

        <!-- Copy Link Button (Inline SVG) -->
        <button type="button" 
                class="btn btn-sm btn-social-share btn-copy" 
                onclick="copyPageLink('<?= $shareUrl ?>')" 
                title="คัดลอกลิงก์"
                aria-label="คัดลอกลิงก์">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
        </button>
    </div>

    <!-- 2. Right: Action Buttons (Print & Font Size) -->
    <div class="d-flex align-items-center gap-2 ms-auto">
        <!-- Font Size Scale -->
        <div class="btn-group btn-group-sm" role="group" aria-label="Font Size">
            <button type="button" class="btn btn-outline-secondary font-scale-btn" onclick="adjustContentFontSize(-1)" title="ลดขนาดตัวอักษร">A-</button>
            <button type="button" class="btn btn-outline-secondary font-scale-btn" onclick="adjustContentFontSize(0)" title="ขนาดปกติ">A</button>
            <button type="button" class="btn btn-outline-secondary font-scale-btn" onclick="adjustContentFontSize(1)" title="เพิ่มขนาดตัวอักษร">A+</button>
        </div>

        <!-- Print / PDF Button -->
        <button type="button" class="btn btn-sm rounded-pill px-3 py-1 fw-semibold shadow-sm d-flex align-items-center gap-1.5 text-white" 
                onclick="window.print()" 
                style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%); border: none; font-family: 'Prompt', sans-serif; font-size: 0.84rem; height: 32px;"
                title="สั่งพิมพ์หน้านี้ หรือบันทึกเป็นไฟล์ PDF">
            <i class="fa-solid fa-print"></i>
            <span class="d-none d-sm-inline">สั่งพิมพ์ / PDF</span>
        </button>
    </div>
</div>

<style>
/* Social Share Buttons Styling */
.btn-social-share {
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    font-size: 0.84rem !important;
    font-weight: 600 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    color: #ffffff !important;
    text-decoration: none !important;
}

.btn-social-share:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
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
    color: #0f172a !important;
}

.font-scale-btn {
    font-family: 'Prompt', sans-serif !important;
    height: 32px !important;
    padding: 0 0.65rem !important;
    font-weight: 600 !important;
    font-size: 0.82rem !important;
    border-color: #cbd5e1 !important;
    color: #475569 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.font-scale-btn:hover {
    background-color: #f1f5f9 !important;
    color: #0f172a !important;
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
// ตรวจสอบความพร้อมของ Web Share API บนอุปกรณ์ผู้ใช้
document.addEventListener('DOMContentLoaded', function() {
    if (navigator.share) {
        const btnNative = document.getElementById('btnNativeShare');
        if (btnNative) {
            btnNative.classList.remove('d-none');
        }
    }
});

// Dynamic On-Demand Share (ไม่โหลดสคริปต์ภายนอกล่วงหน้า)
function triggerDynamicShare(platform, url, title) {
    let targetUrl = '';
    const encodedUrl = encodeURIComponent(url);
    const encodedTitle = encodeURIComponent(title || document.title);

    switch(platform) {
        case 'facebook':
            targetUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodedUrl;
            break;
        case 'line':
            targetUrl = 'https://social-plugins.line.me/lineit/share?url=' + encodedUrl;
            break;
        case 'x':
            targetUrl = 'https://twitter.com/intent/tweet?text=' + encodedTitle + '&url=' + encodedUrl;
            break;
        default:
            return;
    }

    // เปิดหน้าต่างแชร์แบบ Popup ไม่ดึงสคริปต์และไม่โหลด SDK ภายนอกมาบล็อกหน้าเว็บ
    const w = 620, h = 560;
    const left = (screen.width / 2) - (w / 2);
    const top = (screen.height / 2) - (h / 2);
    window.open(targetUrl, 'shareWindow', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
}

// Native Web Share API (สำหรับมือถือ เปิดเมนูแชร์ของระบบปฏิบัติการโดยตรง)
function triggerNativeShare(title, url) {
    if (navigator.share) {
        navigator.share({
            title: title || document.title,
            text: title || document.title,
            url: url || window.location.href
        }).catch(err => {
            if (err.name !== 'AbortError') {
                copyPageLink(url);
            }
        });
    } else {
        copyPageLink(url);
    }
}

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
            el.style.fontSize = '1.15rem';
        }
    });
}
</script>

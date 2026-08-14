<?php
/**
 * ============================================================================
 * Phatthalung Executive ShadowBox & Lightbox Gallery Suite
 * ============================================================================
 * ระบบแสดงภาพแบบ Shadowbox พร้อมเอฟเฟกต์ Glassmorphism, ระบบขยาย (Zoom),
 * และนำทางภาพแบบอัลบั้ม (Gallery Navigation) รองรับ Keyboard Shortcuts & Touch Swiping
 */
?>

<!-- ShadowBox Modal Overlay -->
<div id="phatthalungShadowBox" class="shadowbox-overlay" style="display: none;">
    <!-- Backdrop & Click-outside catcher -->
    <div class="shadowbox-backdrop" onclick="ShadowBox.close()"></div>

    <!-- Top Floating Control Bar -->
    <div class="shadowbox-topbar d-flex align-items-center justify-content-between p-3 position-absolute top-0 start-0 end-0 z-3">
        <div class="d-flex align-items-center gap-3 text-white pe-pointer-none">
            <div class="p-2 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background: linear-gradient(135deg, #0284c7, #2563eb) !important;">
                <i class="fa-solid fa-images fs-5"></i>
            </div>
            <div>
                <h6 class="m-0 fw-bold shadowbox-title text-truncate" id="sbTitle" style="max-width: 50vw; font-size: 1.1rem; text-shadow: 0 2px 8px rgba(0,0,0,0.8);">
                    อัลบั้มภาพกิจกรรมและข่าวสาร
                </h6>
                <small class="text-info fw-bold" id="sbCounter">ภาพที่ 1 จาก 1</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Zoom In/Out Button -->
            <button type="button" class="btn btn-dark rounded-circle border p-2 d-flex align-items-center justify-content-center shadow-lg hover-scale" onclick="ShadowBox.toggleZoom()" title="ขยาย/ย่อภาพ (Zoom)" id="btnSbZoom" style="width: 44px; height: 44px; border-color: rgba(255,255,255,0.25) !important; background: rgba(30, 41, 59, 0.85);">
                <i class="fa-solid fa-magnifying-glass-plus text-warning fs-5" id="iconSbZoom"></i>
            </button>
            <!-- Download Button -->
            <a href="#" target="_blank" id="btnSbDownload" class="btn btn-dark rounded-circle border p-2 d-flex align-items-center justify-content-center shadow-lg hover-scale" title="ดาวน์โหลดไฟล์ภาพฉบับเต็ม" style="width: 44px; height: 44px; border-color: rgba(255,255,255,0.25) !important; background: rgba(30, 41, 59, 0.85);">
                <i class="fa-solid fa-download text-info fs-5"></i>
            </a>
            <!-- Close Button -->
            <button type="button" class="btn btn-danger rounded-circle p-2 d-flex align-items-center justify-content-center shadow-lg ms-2 hover-scale" onclick="ShadowBox.close()" title="ปิดหน้าต่าง [ESC]" style="width: 46px; height: 46px; border: 2px solid rgba(255,255,255,0.4) !important;">
                <i class="fa-solid fa-xmark fs-4 text-white fw-bold"></i>
            </button>
        </div>
    </div>

    <!-- Main Central Stage for Image Display -->
    <div class="shadowbox-stage d-flex align-items-center justify-content-center p-2 p-md-5 w-100 h-100 pe-none">
        <div class="shadowbox-content pe-auto position-relative text-center d-inline-block transition-transform duration-300" id="sbContainer">
            <!-- Loading Spinner -->
            <div id="sbLoading" class="position-absolute top-50 start-50 translate-middle text-center text-info" style="display: none; z-index: 1;">
                <div class="spinner-border text-info" style="width: 3.5rem; height: 3.5rem; border-width: 0.35rem;" role="status"></div>
                <div class="mt-2 fw-bold small text-white">กำลังโหลดภาพความละเอียดสูง...</div>
            </div>

            <!-- Image Tag -->
            <img src="" id="sbImage" class="shadowbox-main-img shadow-2xl rounded-4 border" alt="ShadowBox Display" style="max-height: 85vh; max-width: 92vw; object-fit: contain; border: 3px solid rgba(255,255,255,0.2) !important; background-color: #040814; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.85);">

            <!-- Caption strip below image -->
            <div id="sbCaptionBox" class="p-3 mt-2 rounded-3 text-white text-center shadow-sm w-100" style="background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.15); display: none;">
                <span id="sbCaptionText" class="fw-bold" style="font-size: 1.05rem;"></span>
            </div>
        </div>
    </div>

    <!-- Left / Right Navigation Arrows (Visible only if Gallery has > 1 image) -->
    <button type="button" id="sbPrevBtn" class="shadowbox-nav-btn sb-nav-left rounded-circle shadow-lg d-flex align-items-center justify-content-center transition-all hover-scale" onclick="ShadowBox.prev()" title="ภาพก่อนหน้า [← Left Arrow]">
        <i class="fa-solid fa-chevron-left fs-3 text-white"></i>
    </button>

    <button type="button" id="sbNextBtn" class="shadowbox-nav-btn sb-nav-right rounded-circle shadow-lg d-flex align-items-center justify-content-center transition-all hover-scale" onclick="ShadowBox.next()" title="ภาพถัดไป [→ Right Arrow]">
        <i class="fa-solid fa-chevron-right fs-3 text-white"></i>
    </button>

    <!-- Bottom Keyboard Shortcut Reminder -->
    <div class="position-absolute bottom-0 start-0 end-0 p-2 text-center text-white-50 small d-none d-md-block" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); pointer-events: none;">
        <span><kbd class="bg-dark text-warning border px-2 py-1 rounded">←</kbd> <kbd class="bg-dark text-warning border px-2 py-1 rounded">→</kbd> สลับภาพ &nbsp;&bull;&nbsp; <kbd class="bg-dark text-warning border px-2 py-1 rounded">+ / Z</kbd> ขยายภาพ &nbsp;&bull;&nbsp; <kbd class="bg-dark text-warning border px-2 py-1 rounded">ESC / คลิกพื้นหลัง</kbd> เพื่อปิด</span>
    </div>
</div>

<!-- Dedicated Styles for Phatthalung ShadowBox -->
<style>
.shadowbox-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 10500;
    animation: sbFadeIn 0.3s ease-out;
}

.shadowbox-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(4, 8, 20, 0.92);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    cursor: pointer;
}

@keyframes sbFadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}

.shadowbox-topbar {
    background: linear-gradient(to bottom, rgba(0,0,0,0.85), transparent);
}

.shadowbox-main-img {
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: zoom-in;
}

.shadowbox-main-img.zoomed {
    transform: scale(1.65);
    cursor: zoom-out;
    max-height: 96vh !important;
    max-width: 98vw !important;
    z-index: 10;
}

.shadowbox-nav-btn {
    position: absolute;
    top: 50%;
    translate: 0 -50%;
    width: 58px;
    height: 58px;
    background: rgba(30, 41, 59, 0.75);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255,255,255,0.25) !important;
    color: white;
    cursor: pointer;
    z-index: 10510;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}

.shadowbox-nav-btn:hover {
    background: linear-gradient(135deg, #0284c7, #3b82f6);
    border-color: #ffffff !important;
    transform: translate(0, -50%) scale(1.1);
}

.sb-nav-left {
    left: 25px;
}

.sb-nav-right {
    right: 25px;
}

@media (max-width: 768px) {
    .shadowbox-nav-btn {
        width: 44px;
        height: 44px;
    }
    .sb-nav-left { left: 10px; }
    .sb-nav-right { right: 10px; }
}

/* --- Auto Hover Magnifier Indicator on Interactive Images --- */
.shadowbox-trigger, 
.article-reading-card img:not(.no-shadowbox),
.gallery-grid-img {
    cursor: pointer;
    transition: all 0.35s ease;
    position: relative;
}

.shadowbox-trigger:hover, 
.article-reading-card img:not(.no-shadowbox):hover {
    filter: brightness(1.06) contrast(1.02);
    box-shadow: 0 12px 30px -5px rgba(2, 132, 199, 0.45) !important;
    outline: 3px solid rgba(56, 189, 248, 0.85);
    outline-offset: -3px;
}
</style>

<!-- ShadowBox Core JS Engine -->
<script>
const ShadowBox = (function() {
    let _gallery = [];
    let _currentIndex = 0;
    let _isZoomed = false;

    function _renderCurrent() {
        if (_gallery.length === 0) return;

        const item = _gallery[_currentIndex];
        const img = document.getElementById('sbImage');
        const titleEl = document.getElementById('sbTitle');
        const counterEl = document.getElementById('sbCounter');
        const downloadEl = document.getElementById('btnSbDownload');
        const prevBtn = document.getElementById('sbPrevBtn');
        const nextBtn = document.getElementById('sbNextBtn');
        const captionBox = document.getElementById('sbCaptionBox');
        const captionText = document.getElementById('sbCaptionText');

        // Reset Zoom
        if (_isZoomed) toggleZoom(false);

        // Update Details
        titleEl.textContent = item.title || 'อัลบั้มภาพกิจกรรมและข่าวสาร';
        counterEl.textContent = `ภาพที่ ${_currentIndex + 1} จาก ${_gallery.length}`;
        downloadEl.setAttribute('href', item.src);
        downloadEl.setAttribute('download', item.title || 'phatthalung_image');

        if (item.caption && item.caption.trim() !== '') {
            captionText.textContent = item.caption;
            captionBox.style.display = 'block';
        } else {
            captionBox.style.display = 'none';
        }

        // Show/Hide Nav Buttons
        if (_gallery.length > 1) {
            prevBtn.style.display = 'flex';
            nextBtn.style.display = 'flex';
        } else {
            prevBtn.style.display = 'none';
            nextBtn.style.display = 'none';
        }

        // Smooth transition switch
        img.style.opacity = '0.3';
        const tempImg = new Image();
        tempImg.onload = function() {
            img.src = item.src;
            img.style.opacity = '1';
        };
        tempImg.src = item.src;
    }

    function open(images, initialIndex = 0, defaultTitle = 'อัลบั้มภาพกิจกรรมและข่าวสาร') {
        _gallery = [];
        _currentIndex = initialIndex;

        // Support passing either a single image URL string or array of image objects/strings
        if (typeof images === 'string') {
            _gallery.push({ src: images, title: defaultTitle, caption: '' });
        } else if (Array.isArray(images)) {
            images.forEach(im => {
                if (typeof im === 'string') {
                    _gallery.push({ src: im, title: defaultTitle, caption: '' });
                } else if (typeof im === 'object') {
                    _gallery.push({ src: im.src || im.url, title: im.title || defaultTitle, caption: im.caption || '' });
                }
            });
        }

        if (_gallery.length === 0) return;

        // Display overlay and prevent body scroll
        const box = document.getElementById('phatthalungShadowBox');
        box.style.display = 'block';
        document.body.style.overflow = 'hidden';

        _renderCurrent();
    }

    function close() {
        const box = document.getElementById('phatthalungShadowBox');
        box.style.display = 'none';
        document.body.style.overflow = '';
        if (_isZoomed) toggleZoom(false);
    }

    function next() {
        if (_gallery.length <= 1) return;
        _currentIndex = (_currentIndex + 1) % _gallery.length;
        _renderCurrent();
    }

    function prev() {
        if (_gallery.length <= 1) return;
        _currentIndex = (_currentIndex - 1 + _gallery.length) % _gallery.length;
        _renderCurrent();
    }

    function toggleZoom(forceState = null) {
        const img = document.getElementById('sbImage');
        const zoomIcon = document.getElementById('iconSbZoom');
        
        _isZoomed = forceState !== null ? forceState : !_isZoomed;

        if (_isZoomed) {
            img.classList.add('zoomed');
            if (zoomIcon) {
                zoomIcon.classList.remove('fa-magnifying-glass-plus');
                zoomIcon.classList.add('fa-magnifying-glass-minus');
            }
            if (typeof App !== 'undefined' && App.toast) App.toast('ขยายภาพ 165%', 'info');
        } else {
            img.classList.remove('zoomed');
            if (zoomIcon) {
                zoomIcon.classList.remove('fa-magnifying-glass-minus');
                zoomIcon.classList.add('fa-magnifying-glass-plus');
            }
        }
    }

    // Keyboard navigation listener
    document.addEventListener('keydown', function(e) {
        const box = document.getElementById('phatthalungShadowBox');
        if (!box || box.style.display === 'none') return;

        if (e.key === 'Escape') close();
        if (e.key === 'ArrowRight' || e.key === 'd' || e.key === 'D') next();
        if (e.key === 'ArrowLeft' || e.key === 'a' || e.key === 'A') prev();
        if (e.key === '+' || e.key === 'z' || e.key === 'Z') toggleZoom();
    });

    // Automatically bind all article pictures and galleries on load
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Bind clicking on sbImage itself to toggle zoom
        const sbImg = document.getElementById('sbImage');
        if (sbImg) {
            sbImg.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleZoom();
            });
        }

        // 2. Scan and bind all gallery links and article body images in News Detail page
        setTimeout(function() {
            const articleCard = document.querySelector('.article-reading-card');
            if (!articleCard) return;

            const titleEl = articleCard.querySelector('.article-title');
            const pageTitle = titleEl ? titleEl.textContent.trim() : 'ภาพกิจกรรมพัทลุง';

            // Collect all viewable images in the reading card (banner + inline images + extra gallery)
            const allImgs = Array.from(articleCard.querySelectorAll('img:not(.no-shadowbox)'));
            const galleryData = allImgs.map((imgEl, i) => {
                imgEl.classList.add('shadowbox-trigger');
                imgEl.setAttribute('title', '🔍 คลิกเพื่อชมภาพขนาดใหญ่ใน ShadowBox');
                imgEl.setAttribute('data-sb-index', i);

                // If image is inside an anchor with target=_blank (like extra gallery), intercept click
                const parentAnchor = imgEl.closest('a');
                if (parentAnchor) {
                    parentAnchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        ShadowBox.open(galleryData, i, pageTitle);
                    });
                } else {
                    imgEl.addEventListener('click', function(e) {
                        e.stopPropagation();
                        ShadowBox.open(galleryData, i, pageTitle);
                    });
                }

                return {
                    src: imgEl.src || parentAnchor?.href,
                    title: pageTitle,
                    caption: imgEl.getAttribute('alt') || `ภาพประกอบบทความ : ${pageTitle}`
                };
            });
        }, 350);
    });

    return {
        open: open,
        close: close,
        next: next,
        prev: prev,
        toggleZoom: toggleZoom
    };
})();
</script>

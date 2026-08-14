<!-- =========================================================================
     SMART FLOATING CAPSULE DOCK & ACCESSIBILITY STUDIO (2026+ MODERN UI)
     แทนที่แถบเมนูทางลัดแนวตั้งโบราณ ด้วยแท่นบาร์กระจกใสสไตล์ macOS/iOS พร้อมเอฟเฟกต์โฮเวอร์
     ========================================================================= -->

<style>
/* --- 1. Smart Floating Capsule Dock --- */
.floating-capsule-dock {
    position: fixed;
    bottom: 25px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1040;
    background: rgba(15, 23, 42, 0.78);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 1.5px solid rgba(255, 255, 255, 0.18);
    border-radius: 60px;
    padding: 6px 14px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 0 20px rgba(56, 189, 248, 0.15);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
    display: flex;
    align-items: center;
    gap: 6px;
    max-width: 95vw;
    overflow-x: auto;
    scrollbar-width: none;
}
.floating-capsule-dock.dock-scroll-hidden {
    transform: translateX(-50%) translateY(110%);
    opacity: 0;
    pointer-events: none;
}
.floating-capsule-dock:hover {
    opacity: 1 !important;
    transform: translateX(-50%) translateY(0) !important;
    pointer-events: auto !important;
}
.floating-capsule-dock::-webkit-scrollbar {
    display: none;
}
.floating-capsule-dock.dock-minimized {
    transform: translateX(-50%) translateY(120%);
    opacity: 0;
    pointer-events: none;
}
.dock-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none !important;
    color: #f8fafc !important;
    padding: 6px 12px;
    border-radius: 30px;
    transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    white-space: nowrap;
    border: 1px solid transparent;
}
.dock-item-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    background: rgba(255, 255, 255, 0.08);
    transition: all 0.25s ease;
    margin-bottom: 2px;
}
.dock-item-label {
    font-size: 0.75rem;
    font-weight: 600;
    opacity: 0.85;
    transition: opacity 0.2s ease;
}
.dock-item:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.25);
    transform: translateY(-6px) scale(1.08);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}
.dock-item:hover .dock-item-icon {
    background: #00f0ff !important;
    color: #0f172a !important;
    box-shadow: 0 0 15px #00f0ff;
}
.dock-item:hover .dock-item-label {
    opacity: 1;
    color: #00f0ff !important;
}
.dock-divider {
    width: 1px;
    height: 36px;
    background: rgba(255, 255, 255, 0.15);
    margin: 0 4px;
}
.dock-minimize-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.dock-minimize-btn:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
}

/* Floating Restore Button (when minimized) */
.dock-restore-btn {
    position: fixed;
    bottom: 25px;
    left: 50%;
    transform: translateX(-50%) translateY(120%);
    z-index: 1039;
    background: linear-gradient(135deg, #0b5e7a, #118196);
    color: #ffffff !important;
    border: 2px solid #38bdf8;
    border-radius: 50px;
    padding: 8px 22px;
    font-weight: 700;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    opacity: 0;
    pointer-events: none;
}
.dock-restore-btn.show-restore {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
    pointer-events: auto;
}
.dock-restore-btn:hover {
    transform: translateX(-50%) translateY(-4px) scale(1.05);
    background: linear-gradient(135deg, #118196, #00f0ff);
    color: #0f172a !important;
}

/* --- 2. Smart Accessibility & Utility Studio Button (Bottom Left) --- */
.accessibility-trigger {
    position: fixed;
    bottom: 25px;
    left: 25px;
    z-index: 1045;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: #ffffff;
    border: 2px solid #38bdf8;
    border-radius: 50px;
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 0.88rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.45), 0 0 15px rgba(56, 189, 248, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
}
.accessibility-trigger:hover {
    transform: scale(1.05) translateY(-2px);
    background: #38bdf8;
    color: #0f172a !important;
    box-shadow: 0 12px 30px rgba(56, 189, 248, 0.6);
}
.accessibility-trigger:hover i {
    color: #0f172a !important;
}
.accessibility-trigger i {
    font-size: 1.25rem;
    color: #38bdf8;
    transition: color 0.2s ease;
}

/* Accessibility Modal Panel */
.access-panel-card {
    background: rgba(15, 23, 42, 0.92);
    backdrop-filter: blur(25px);
    border: 2px solid rgba(56, 189, 248, 0.4);
    border-radius: 1.5rem;
    color: #f8fafc;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
}
.font-size-option {
    border: 2px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    border-radius: 1rem;
    padding: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.font-size-option:hover, .font-size-option.active {
    background: #38bdf8;
    color: #0f172a !important;
    border-color: #38bdf8;
    font-weight: 700;
}
.access-theme-btn {
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    border-radius: 0.75rem;
    padding: 10px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}
.access-theme-btn:hover, .access-theme-btn.active {
    background: #f59e0b;
    color: #0f172a !important;
    border-color: #f59e0b;
    font-weight: 700;
}
</style>

<!-- 1. FLOATING CAPSULE DOCK (Section Shortcuts) -->
<div class="floating-capsule-dock" id="floatingCapsuleDock">
    <a href="<?= base_url() ?>" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #0284c7, #0369a1);"><i class="fa-solid fa-house text-white"></i></div>
        <span class="dock-item-label">หน้าหลัก</span>
    </a>

    <a href="<?= base_url('news') ?>" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #0284c7, #0369a1);"><i class="fa-solid fa-newspaper text-white"></i></div>
        <span class="dock-item-label">ข่าวสาร</span>
    </a>

    <a href="<?= base_url('calendar') ?>" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #0284c7, #0369a1);"><i class="fa-solid fa-calendar-check text-white"></i></div>
        <span class="dock-item-label">ปฏิทินกิจกรรม</span>
    </a>

    <a href="<?= base_url('procurement') ?>" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #0284c7, #0369a1);"><i class="fa-solid fa-file-invoice-dollar text-white"></i></div>
        <span class="dock-item-label">จัดซื้อจัดจ้าง</span>
    </a>

    <a href="<?= base_url('gallery') ?>" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #475569, #334155);"><i class="fa-solid fa-camera-retro text-white"></i></div>
        <span class="dock-item-label">คลังภาพ</span>
    </a>

    <a href="<?= base_url('videos') ?>" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #475569, #334155);"><i class="fa-solid fa-play text-white"></i></div>
        <span class="dock-item-label">วิดีทัศน์</span>
    </a>

    <a href="<?= base_url('documents') ?>" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #475569, #334155);"><i class="fa-solid fa-cloud-arrow-down text-white"></i></div>
        <span class="dock-item-label">คลังเอกสาร</span>
    </a>

    <a href="<?= base_url('ita') ?>" class="dock-item" title="ศูนย์การประเมินความโปร่งใส ITA/OIT และระบบข้อมูลเปิดสาธารณะ">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #059669, #047857);"><i class="fa-solid fa-award text-warning"></i></div>
        <span class="dock-item-label">ITA & ข้อมูลเปิด</span>
    </a>

    <a href="http://www.ma-muanglung.go.th" target="_blank" rel="noopener noreferrer" class="dock-item" title="เปิดเว็บไซต์ท่องเที่ยวพัทลุง (www.ma-muanglung.go.th)">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #059669, #047857);"><i class="fa-solid fa-compass text-white"></i></div>
        <span class="dock-item-label">มาเมืองลุง (เที่ยว)</span>
    </a>

    <div class="dock-divider"></div>

    <a href="javascript:void(0);" onclick="if(typeof NoraAI!=='undefined'){NoraAI.toggle();}" class="dock-item" title="ผู้ช่วยตอบคำถามประชาชน 24 ชม. (น้องโนรา AI)">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #d97706, #b45309);"><i class="fa-solid fa-crown text-white animate-bounce-slow"></i></div>
        <span class="dock-item-label">น้องโนรา AI</span>
    </a>

    <a href="#services" onclick="event.preventDefault(); scrollToSectionOrHome('services');" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #d97706, #b45309);"><i class="fa-solid fa-hand-pointer text-white"></i></div>
        <span class="dock-item-label">บริการประชาชน</span>
    </a>

    <a href="javascript:void(0);" onclick="openOmniSearch()" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #d97706, #b45309);"><i class="fa-solid fa-magnifying-glass text-white"></i></div>
        <span class="dock-item-label">ค้นหาสะดวก</span>
    </a>

    <a href="javascript:void(0);" onclick="openCitizenRequest()" class="dock-item">
        <div class="dock-item-icon" style="background: linear-gradient(135deg, #d97706, #b45309);"><i class="fa-solid fa-paper-plane text-white"></i></div>
        <span class="dock-item-label">ร้องทุกข์</span>
    </a>

    <div class="dock-divider"></div>

    <!-- Minimize Toggle Button -->
    <button type="button" class="dock-minimize-btn" onclick="toggleFloatingDock(false)" title="ย่อแถบทางลัด">
        <i class="fa-solid fa-chevron-down"></i>
    </button>
</div>

<!-- Restore Button when Dock is Minimized -->
<button type="button" class="dock-restore-btn d-flex align-items-center gap-2" id="dockRestoreBtn" onclick="toggleFloatingDock(true)">
    <i class="fa-solid fa-wand-magic-sparkles text-warning animate-pulse"></i>
    <span>เมนูทางลัด (Shortcuts)</span>
</button>

<!-- W3C AAA Accessibility suite is separately included in main.php layout -->

<script>
function toggleFloatingDock(show) {
    const dock = document.getElementById('floatingCapsuleDock');
    const restoreBtn = document.getElementById('dockRestoreBtn');
    if (show) {
        if (dock) dock.classList.remove('dock-minimized');
        if (restoreBtn) restoreBtn.classList.remove('show-restore');
        localStorage.setItem('dock_state', 'open');
    } else {
        if (dock) dock.classList.add('dock-minimized');
        if (restoreBtn) restoreBtn.classList.add('show-restore');
        localStorage.setItem('dock_state', 'minimized');
    }
}

function scrollToSectionOrHome(sectionId) {
    const el = document.getElementById(sectionId);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
    } else {
        window.location.href = "<?= base_url() ?>#" + sectionId;
    }
}

function openOmniSearch() {
    const el = document.getElementById('omniSearchModal');
    if (el && typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(el).show();
    } else {
        window.location.href = "<?= base_url('api/search') ?>";
    }
}

function openCitizenRequest() {
    const el = document.getElementById('citizenRequestModal');
    if (el && typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(el).show();
    } else {
        window.location.href = "<?= base_url() ?>#citizenRequestModal";
    }
}

function applyPortalFont(scale, btnEl) {
    document.querySelectorAll('.font-size-option').forEach(el => el.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');
    
    if (typeof adjustPortalFontSize === 'function') {
        adjustPortalFontSize(scale);
    } else {
        const root = document.documentElement;
        if (scale === -1) root.style.fontSize = '14px';
        else if (scale === 1) root.style.fontSize = '19px';
        else root.style.fontSize = '16px';
    }
    localStorage.setItem('portal_font_scale', scale);
}

function applyPortalTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    document.querySelectorAll('.access-theme-btn').forEach(b => b.classList.remove('active'));
    if (theme === 'dark') {
        const dBtn = document.getElementById('btnThemeDark');
        if (dBtn) dBtn.classList.add('active');
        if (typeof App !== 'undefined' && App.toast) App.toast('เปลี่ยนเป็นโหมดถนอมสายตา (Dark Mode)', 'info');
    } else {
        const lBtn = document.getElementById('btnThemeLight');
        if (lBtn) lBtn.classList.add('active');
        if (typeof App !== 'undefined' && App.toast) App.toast('เปลี่ยนเป็นโหมดสว่าง (Light Mode)', 'info');
    }
    localStorage.setItem('theme', theme);
}

function switchPortalLang(lang) {
    if (typeof App !== 'undefined' && App.toast) {
        App.toast(lang === 'th' ? 'เปลี่ยนเป็นภาษาไทยแล้ว' : 'Switched to English Interface', 'success');
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: lang === 'th' ? 'ภาษาไทย (TH)' : 'English (EN)',
            text: lang === 'th' ? 'แสดงผลด้วยภาษาไทยเป็นหลัก' : 'Interface language set to English.',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    }
}

function resetPortalAccessibility() {
    applyPortalFont(0, document.getElementById('btnFontMd'));
    applyPortalTheme('light');
    if (typeof App !== 'undefined' && App.toast) App.toast('คืนค่ามาตรฐานเรียบร้อยแล้ว', 'info');
}

// Check saved preferences on load
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('dock_state') === 'minimized') {
        toggleFloatingDock(false);
    }
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    if (currentTheme === 'dark') {
        const dBtn = document.getElementById('btnThemeDark');
        if (dBtn) dBtn.classList.add('active');
    } else {
        const lBtn = document.getElementById('btnThemeLight');
        if (lBtn) lBtn.classList.add('active');
    }

    // ===== AUTO-HIDE DOCK ON SCROLL DOWN, SHOW ON SCROLL UP =====
    let lastScrollY = window.scrollY;
    let scrollTimer = null;
    const dock = document.getElementById('floatingCapsuleDock');
    if (dock && localStorage.getItem('dock_state') !== 'minimized') {
        window.addEventListener('scroll', function() {
            const currentY = window.scrollY;
            if (currentY > lastScrollY && currentY > 300) {
                dock.classList.add('dock-scroll-hidden');
            } else {
                dock.classList.remove('dock-scroll-hidden');
            }
            lastScrollY = currentY;
            // Show dock again if user stops scrolling for 3 seconds
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                dock.classList.remove('dock-scroll-hidden');
            }, 3000);
        }, { passive: true });
    }
});
</script>

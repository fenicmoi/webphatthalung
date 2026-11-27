<?= $this->extend('layouts/main') ?>

<?php 
$services = $services ?? [];
$cfg = function_exists('get_site_settings') ? get_site_settings() : [];
$layoutMode = $cfg['layout_mode'] ?? 'hybrid_widescreen';
?>

<?php if ($layoutMode === 'hybrid_widescreen'): ?>
    <?php $this->section('hero_banner') ?>
        <?= $this->include('components/hero_banner') ?>
    <?php $this->endSection() ?>
<?php endif; ?>

<?= $this->section('content') ?>

<?php if ($layoutMode !== 'hybrid_widescreen'): ?>
    <?= $this->include('components/hero_banner') ?>
<?php endif; ?>

<style>
/* Futuristic Voice Search Styling & Animations */
.voice-search-btn:hover {
    background: #047857 !important;
    color: #ffffff !important;
    box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
}
.voice-search-btn.recording {
    background: #ef4444 !important;
    color: #ffffff !important;
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    animation: micPulse 1.2s infinite;
}
@keyframes micPulse {
    0% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
        transform: scale(1);
    }
    70% {
        box-shadow: 0 0 0 12px rgba(239, 68, 68, 0);
        transform: scale(1.08);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        transform: scale(1);
    }
}
.voice-wave-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    display: inline-block;
    animation: waveBounce 0.8s infinite alternate ease-in-out;
}
@keyframes waveBounce {
    0% { transform: scale(0.6); opacity: 0.5; }
    100% { transform: scale(1.2); opacity: 1; background: #34d399; }
}

/* Clean Floating Soft Search Bar */
.floating-search-section {
    margin-top: 4px;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 25;
}
.floating-search-shell {
    max-width: 860px;
    margin: 0 auto;
}
.floating-search-pill {
    background: #ffffff;
    border: 1.5px solid rgba(16, 185, 129, 0.25);
    border-radius: 999px;
    padding: 6px 7px 6px 9px;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.09), 0 2px 8px rgba(15, 23, 42, 0.04);
    transition: all 0.25s ease;
}
.floating-search-pill:focus-within {
    border-color: #10b981;
    box-shadow: 0 14px 36px rgba(16, 185, 129, 0.16), 0 0 0 4px rgba(16, 185, 129, 0.1);
}
.floating-trending-popover {
    display: none;
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 8px);
    background: #ffffff;
    border: 1px solid rgba(16, 185, 129, 0.22);
    border-radius: 18px;
    padding: 12px 16px;
    box-shadow: 0 16px 38px rgba(15, 23, 42, 0.12);
    z-index: 1040;
}
.floating-trending-popover.show {
    display: block;
}
.floating-trending-chip {
    background: #f4faf6;
    color: #065f46;
    border: 1px solid rgba(16, 185, 129, 0.22);
    font-size: 0.78rem;
    font-weight: 600;
    transition: all 0.2s ease;
}
.floating-trending-chip:hover {
    background: #10b981;
    color: #ffffff;
    border-color: #10b981;
    transform: translateY(-1px);
}
@media (max-width: 768px) {
    .floating-search-section {
        margin-top: 2px;
        margin-bottom: 1.15rem;
    }
    .floating-search-pill {
        padding: 5px 6px 5px 8px;
    }
}
</style>

<!-- 1.1 QUICK JUMP NAVIGATION BAR (แถบทางลัด 1 แถวบางๆ ใต้สไลด์ — แถบค้นหาย้ายขึ้นไปบนสไลด์แล้ว) -->
<section class="mb-4 position-relative" style="z-index: 15;">
    <script>
    function showTrendingPopover(show) {
        var pop = document.getElementById('trendingSearchPopover');
        var inp = document.getElementById('globalSearchInput');
        if (!pop) return;
        if (show && (!inp || inp.value.trim() === '')) {
            pop.classList.add('show');
        } else {
            pop.classList.remove('show');
        }
    }
    document.addEventListener('click', function(e) {
        var shell = document.querySelector('.floating-search-shell');
        if (shell && !shell.contains(e.target)) {
            showTrendingPopover(false);
        }
    });
    </script>

    <!-- Quick Jump Bar (ทางลัดข้ามหมวดสำคัญใน 1 คลิก — แถวเดียวโล่งสบายตา) -->
    <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mt-1">
        <a href="#news-media-hub" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: #047857; border: 1px solid rgba(4, 120, 87, 0.22);">
            <i class="fa-solid fa-newspaper text-success"></i> ข่าวสารล่าสุด
        </a>
        <a href="#multimedia-gallery-section" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: #6d28d9; border: 1px solid rgba(109, 40, 217, 0.2);">
            <i class="fa-solid fa-photo-film text-purple" style="color: #7c3aed;"></i> กิจกรรม / ภาพ / วิดีโอ
        </a>
        <a href="#provincial-policy-wrap" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: #0369a1; border: 1px solid rgba(3, 105, 161, 0.2);">
            <i class="fa-solid fa-user-tie text-primary"></i> ผู้บริหาร &amp; แผนพัฒนาฯ
        </a>
        <a href="#digital-services-wrap" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: #b45309; border: 1px solid rgba(180, 83, 9, 0.22);">
            <i class="fa-solid fa-laptop-code text-warning"></i> ระบบสารสนเทศ e-Service
        </a>
        <a href="<?= base_url('strategy') ?>" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: #0f766e; border: 1px solid rgba(15, 118, 110, 0.22);">
            <i class="fa-solid fa-book-open text-success"></i> แผนพัฒนาจังหวัด
        </a>
        <a href="#pr-banner-wrap" class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5" style="background: #ffffff; color: #334155; border: 1px solid rgba(51, 65, 85, 0.2);">
            <i class="fa-solid fa-bullhorn text-danger"></i> แบนเนอร์หน่วยงาน
        </a>
    </div>
</section>

<!-- 2. PROVINCIAL POLICY, ROYAL INITIATIVES & STRATEGIC HUB (คำขวัญ แบนเนอร์พระราชดำริ ปกสมุดยุทธศาสตร์ และนโยบายผู้ว่าฯ) -->
<div id="provincial-policy-wrap">
<?php 
try {
    echo $this->include('components/provincial_policy_hub');
} catch (\Throwable $e) {
    echo '<!-- provincial_policy_hub error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . ' in ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ':' . $e->getLine() . ' -->';
}
?>
</div>

<!-- 3. NEWS & MEDIA HUB (ข่าวสารล่าสุด + รวมกิจกรรม/คลังภาพ/วิดีโอแบบ Tab) -->
<?php 
try {
    echo $this->include('components/news_media_hub');
} catch (\Throwable $e) {
    echo '<!-- news_media_hub error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . ' in ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ':' . $e->getLine() . ' -->';
}
?>

<!-- 4. DIGITAL SERVICES & e-GOVERNMENT HUB (ศูนย์รวมระบบสารสนเทศและการบริการดิจิทัล) -->
<div id="digital-services-wrap">
<?php 
try {
    echo $this->include('components/digital_services_hub');
} catch (\Throwable $e) {
    echo '<!-- digital_services_hub error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . ' in ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ':' . $e->getLine() . ' -->';
}
?>
</div>

<!-- 5. PR & PARTNER BANNERS 3D SLIDER (แถบแบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์) -->
<div id="pr-banner-wrap">
<?= $this->include('components/pr_banner_carousel') ?>
</div>

<!-- 4. GOVERNANCE & TRANSPARENCY HUB (ศูนย์ข้อมูลความโปร่งใสและจัดซื้อจัดจ้าง) -->
<!-- ย้ายไปเข้าผ่านเมนูด้านบนแทนการแสดงผลหน้าหลัก -->
<?php // echo $this->include('components/governance_hub'); ?>

<!-- 4. GLASSMORPHIC CITIZEN REQUEST MODAL -->
<div class="modal fade" id="citizenRequestModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-modal" style="background: var(--glass-navbar-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 24px; box-shadow: var(--glass-shadow); color: var(--text-primary);">
            <div class="modal-header border-bottom" style="border-color: var(--glass-border) !important; padding: 1.5rem;">
                <h5 class="modal-title fw-bold" id="modalTitle">
                    <i class="fa-solid fa-paper-plane text-primary me-2"></i>ยื่นเรื่องและส่งข้อความถึงเจ้าหน้าที่ (Online Request)
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(var(--bs-icon-invert));"></button>
            </div>
            
            <form id="citizenRequestForm" onsubmit="handleAsyncSubmit(event)">
                <div class="modal-body p-4">
                    <div class="alert" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); color: var(--text-primary); border-radius: var(--radius-sm);">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>
                        <strong>ระบบตอบรับอัตโนมัติ:</strong> ข้อมูลของคุณจะถูกส่งเข้าระบบเจ้าหน้าที่หลังบ้าน (Phase 2 Portal) และสร้างรหัสติดตามให้ท่านทันทีโดยไม่ต้องเปลี่ยนหน้าจอ
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ชื่อ-นามสกุล ผู้ติดต่อ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control custom-input" name="full_name" required placeholder="เช่น สมชาย ใจมั่นคง">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">เบอร์โทรศัพท์หรืออีเมลสำหรับติดต่อกลับ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control custom-input" name="contact_info" required placeholder="08X-XXX-XXXX หรือ email@domain.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">ประเภทบริการ / หัวข้อเรื่อง</label>
                            <input type="text" id="modalServiceType" name="service_type" class="form-control custom-input" readonly style="background: rgba(255,255,255,0.05);">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">รายละเอียดคำร้องหรือประเด็นที่ต้องการให้ช่วยเหลือ <span class="text-danger">*</span></label>
                            <textarea class="form-control custom-input" name="description" rows="4" required placeholder="อธิบายข้อความคำร้อง สถานที่ หรือรายละเอียดประเด็นของท่านให้ชัดเจน..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pdpaConsent" required>
                                <label class="form-check-label" for="pdpaConsent" style="font-size: 0.85rem; color: var(--text-secondary);">
                                    ข้าพเจ้าตกลงยินยอมให้ประมวลผลข้อมูลส่วนบุคคลตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล (PDPA) เพื่อประโยชน์ในการประสานงานบริการภาครัฐ
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top p-4 d-flex justify-content-between" style="border-color: var(--glass-border) !important;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn-modern" id="submitBtn">
                        <i class="fa-solid fa-cloud-arrow-up"></i> บันทึกและส่งคำร้องออนไลน์
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- INLINE INTERACTIVE SCRIPT (NO-RELOAD HANDLING) -->
<script>
let modalInstance = null;

// 1. ฟังก์ชันโหลดประกาศตามแท็บและปฏิทินย้ายไปอยู่ใน components/news_media_hub.php แล้ว

// 3. ฟังก์ชันเปิดหน้าต่างรับคำร้องบริการประชาชน
function openRequestModal(serviceTitle) {
    document.getElementById('modalServiceType').value = serviceTitle;
    const el = document.getElementById('citizenRequestModal');
    modalInstance = bootstrap.Modal.getOrCreateInstance(el);
    modalInstance.show();
}

// 4. ฟังก์ชันส่งข้อมูลคำร้องแบบ Async (No-Reload SPA Submit)
async function handleAsyncSubmit(event) {
    event.preventDefault();
    const form = document.getElementById('citizenRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    const formData = new FormData(form);

    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังส่งและเข้ารหัสระบบ...';

    try {
        const res = await App.fetch('<?= base_url("api/submit-request") ?>', {
            method: 'POST',
            body: formData
        });

        if (res.status === 'success') {
            App.toast(`🎉 ${res.message}`, 'success');
            form.reset();
            modalInstance?.hide();
            
            // นำรหัสติดตามไปแสดงที่ช่องค้นหาด้านบนทันทีเพื่อความประทับใจ
            const tracker = document.getElementById('trackingInput');
            if (tracker) tracker.value = res.tracking_code;
        } else {
            App.toast(`ข้อผิดพลาด: ${res.message}`, 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดระหว่างส่งข้อมูล: ' + err.message, 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// 5. Global Smart Search (Live Search & Voice Dictation)
let searchTimeout = null;
const searchInput = document.getElementById('globalSearchInput');
const searchDropdown = document.getElementById('searchResultsDropdown');
const searchSpinner = document.getElementById('searchSpinner');

// Voice Recognition Instance
let voiceRecognition = null;
let isVoiceListening = false;

function initVoiceSearch() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        return null;
    }
    const recognition = new SpeechRecognition();
    recognition.lang = 'th-TH';
    recognition.continuous = false;
    recognition.interimResults = true;

    recognition.onstart = function() {
        isVoiceListening = true;
        const micBtn = document.getElementById('btnVoiceSearch');
        const micIcon = document.getElementById('voiceMicIcon');
        const listeningBar = document.getElementById('voiceListeningBar');
        const statusText = document.getElementById('voiceListeningStatusText');
        
        if (micBtn) micBtn.classList.add('recording');
        if (micIcon) micIcon.className = 'fa-solid fa-microphone-lines';
        if (listeningBar) {
            listeningBar.classList.remove('d-none');
            listeningBar.classList.add('d-flex');
        }
        if (statusText) statusText.innerText = 'กำลังฟังเสียงของคุณ (พูดคำที่ต้องการค้นหา)...';
    };

    recognition.onresult = function(event) {
        let transcript = '';
        for (let i = event.resultIndex; i < event.results.length; ++i) {
            transcript += event.results[i][0].transcript;
        }
        const sInput = document.getElementById('globalSearchInput');
        if (sInput) {
            sInput.value = transcript;
            sInput.dispatchEvent(new Event('input'));
        }
    };

    recognition.onerror = function(event) {
        console.warn('Speech recognition error:', event.error);
        stopVoiceSearch();
        if (event.error === 'not-allowed') {
            if (typeof App !== 'undefined' && App.toast) {
                App.toast('กรุณาอนุญาตการเข้าถึงไมโครโฟนในเบราว์เซอร์', 'warning');
            } else {
                alert('กรุณาอนุญาตการเข้าถึงไมโครโฟนในเบราว์เซอร์');
            }
        }
    };

    recognition.onend = function() {
        stopVoiceSearch();
        const sInput = document.getElementById('globalSearchInput');
        if (sInput && sInput.value.trim().length > 0) {
            sInput.dispatchEvent(new Event('input'));
        }
    };

    return recognition;
}

function toggleVoiceSearch() {
    if (isVoiceListening) {
        stopVoiceSearch();
        return;
    }

    if (!voiceRecognition) {
        voiceRecognition = initVoiceSearch();
    }

    if (!voiceRecognition) {
        if (typeof App !== 'undefined' && App.toast) {
            App.toast('เบราว์เซอร์นี้ไม่รองรับระบบสั่งงานด้วยเสียง (Speech Recognition)', 'warning');
        } else {
            alert('เบราว์เซอร์นี้ไม่รองรับระบบสั่งงานด้วยเสียง');
        }
        return;
    }

    try {
        voiceRecognition.start();
    } catch (err) {
        console.warn('Voice recognition start error:', err);
    }
}

function stopVoiceSearch() {
    if (voiceRecognition && isVoiceListening) {
        try { voiceRecognition.stop(); } catch(e) {}
    }
    isVoiceListening = false;
    const micBtn = document.getElementById('btnVoiceSearch');
    const micIcon = document.getElementById('voiceMicIcon');
    const listeningBar = document.getElementById('voiceListeningBar');

    if (micBtn) micBtn.classList.remove('recording');
    if (micIcon) micIcon.className = 'fa-solid fa-microphone';
    if (listeningBar) {
        listeningBar.classList.remove('d-flex');
        listeningBar.classList.add('d-none');
    }
}

function triggerSearchSubmit() {
    const sInput = document.getElementById('globalSearchInput');
    if (sInput && sInput.value.trim().length > 0) {
        const query = sInput.value.trim();
        if (typeof OmniSearch !== 'undefined' && typeof OmniSearch.open === 'function') {
            OmniSearch.open(query);
            const drop = document.getElementById('searchResultsDropdown');
            if (drop) drop.style.display = 'none';
        } else {
            window.location.href = `<?= base_url('search') ?>?q=${encodeURIComponent(query)}`;
        }
    } else if (sInput) {
        sInput.focus();
    }
}

function quickSearchTag(kw) {
    const sInput = document.getElementById('globalSearchInput');
    if (sInput) {
        sInput.value = kw;
        sInput.focus();
        sInput.dispatchEvent(new Event('input'));
    }
}

// Auto open OmniSearch if URL has ?q= parameter
document.addEventListener('DOMContentLoaded', function() {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const searchQ = urlParams.get('q');
        if (searchQ && typeof OmniSearch !== 'undefined' && typeof OmniSearch.open === 'function') {
            setTimeout(() => OmniSearch.open(searchQ), 300);
        }
    } catch(e) {}
});

if (searchInput) {
    // ซ่อน Dropdown เมื่อคลิกที่อื่น
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.style.display = 'none';
        }
    });
    
    // โชว์ Dropdown ถ้ามีค่าเมื่อคลิก input
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0 && searchDropdown.innerHTML.trim() !== '') {
            searchDropdown.style.display = 'block';
        }
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length === 0) {
            searchDropdown.style.display = 'none';
            searchSpinner.classList.add('d-none');
            return;
        }
        
        searchSpinner.classList.remove('d-none');
        
        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`<?= base_url("search") ?>?q=${encodeURIComponent(query)}`);
                const json = await res.json();
                
                searchSpinner.classList.add('d-none');
                
                if (json.success && json.data) {
                    renderSearchResults(json.data, query);
                }
            } catch (err) {
                console.error("Search Error:", err);
                searchSpinner.classList.add('d-none');
            }
        }, 400); // delay 400ms
    });
}

function renderSearchResults(results, query) {
    if (results.length === 0) {
        searchDropdown.innerHTML = `<div class="p-4 text-center text-muted"><i class="fa-solid fa-magnifying-glass fs-3 d-block mb-2 opacity-50"></i>ไม่พบข้อมูลที่ตรงกับ "${query}"</div>`;
    } else {
        let html = '<div class="list-group list-group-flush" style="border-radius: 12px; overflow: hidden;">';
        results.forEach(item => {
            let link = item.url && item.url !== '#' ? item.url : 'javascript:void(0)';
            if (typeof link === 'string' && (link.includes('phatthalung.go.th/2022/files/') || link.includes('phatthalung.go.th/2022-stop/files/'))) {
                link = link.replace(/phatthalung\.go\.th\/2022(-stop)?\/files\//g, 'phatthalung.go.th/files/');
            }
            const isExternalOrFile = link.startsWith('http') || link.endsWith('.pdf') || link.endsWith('.doc') || link.endsWith('.docx') || link.endsWith('.xls') || link.endsWith('.xlsx') || link.endsWith('.zip');
            const targetAttr = isExternalOrFile ? 'target="_blank" rel="noopener noreferrer"' : '';
            
            const avatarHtml = item.image_url ? `
                <div class="position-relative" style="width: 44px; height: 44px; flex-shrink: 0;">
                    <img src="${item.image_url}" alt="" class="rounded-circle border border-2 border-warning shadow-sm" style="width: 44px; height: 44px; object-fit: cover; object-position: top center;">
                </div>
            ` : `
                <div style="flex-shrink: 0;">
                    <span class="badge ${item.ui_badge_color} rounded-circle p-2 shadow-sm" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                        <i class="${item.ui_icon}" style="font-size: 1.15rem;"></i>
                    </span>
                </div>
            `;

            html += `
            <a href="${link}" ${targetAttr} class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: transparent; border-bottom: 1px solid var(--glass-border, #f1f5f9); transition: all 0.2s;">
                ${avatarHtml}
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 fw-bold text-truncate" style="color: var(--text-primary, #0f172a); max-width: 75%; font-size: 0.98rem;">${item.title}</h6>
                        <span class="badge ${item.ui_badge_color} bg-opacity-75" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 50rem;">${item.ui_badge_text}</span>
                    </div>
                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.85rem;">${item.description || '-'}</p>
                </div>
                <div class="text-muted ms-1" style="font-size: 0.8rem; opacity: 0.6;">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            `;
        });
        html += '</div>';
        
        // Add footer for all results link
        html += `
        <div class="p-2.5 d-flex align-items-center justify-content-between px-3 border-top" style="border-color: var(--glass-border, #f1f5f9) !important; background: rgba(0,0,0,0.02);">
            <small class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i> พบผลลัพธ์ ${results.length} รายการ</small>
            <a href="javascript:void(0)" onclick="if(typeof OmniSearch !== 'undefined') { OmniSearch.open(document.getElementById('globalSearchInput').value.trim()); document.getElementById('searchResultsDropdown').style.display='none'; }" class="text-decoration-none small text-success fw-bold">
                <i class="fa-solid fa-wand-magic-sparkles me-1 text-warning"></i> ดูผลลัพธ์แบบละเอียด &raquo;
            </a>
        </div>
        `;
        
        searchDropdown.innerHTML = html;
    }
    searchDropdown.style.display = 'block';
}
</script>



<?= $this->endSection() ?>

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
</style>

<!-- 1.1 GLOBAL SMART SEARCH DOCK (High-Visibility Prestigious Emerald Command Deck) -->
<section class="mb-5 position-relative z-3">
    <div class="card border-0 p-4 p-lg-4 text-white shadow-xl" 
         style="border-radius: 26px; background: linear-gradient(135deg, #022c22 0%, #064e3b 55%, #047857 100%); border: 1px solid rgba(16, 185, 129, 0.4) !important; border-top: 3px solid #10b981 !important; box-shadow: 0 20px 45px -10px rgba(2, 44, 34, 0.45), 0 0 0 1px rgba(16, 185, 129, 0.25) !important;">
        <div class="row align-items-center g-3">
            
            <!-- Search Title & Subtitle Badge -->
            <div class="col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center gap-2.5">
                    <span class="p-2.5 rounded-3 text-white d-inline-flex align-items-center justify-content-center shadow-md" style="background: rgba(255,255,255,0.15); width: 44px; height: 44px; border: 1px solid rgba(255,255,255,0.25);">
                        <i class="fa-solid fa-wand-magic-sparkles text-warning fs-5"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-white" style="font-size: 1.15rem; letter-spacing: -0.2px;">ระบบค้นหาอัจฉริยะ</h5>
                        <small class="text-light opacity-75" style="font-size: 0.78rem;">Smart AI & Voice Search</small>
                    </div>
                </div>
            </div>

            <!-- Search Input with Voice Dictation -->
            <div class="col-lg-9 position-relative">
                <div class="search-container d-flex align-items-center p-1.5 transition-all shadow-lg" 
                     style="background: #ffffff; border: 2px solid #10b981; border-radius: 50px; transition: all 0.3s ease;"
                     id="mainSearchWrapper">
                    
                    <div class="search-icon-wrapper d-flex align-items-center justify-content-center text-success rounded-circle ms-2" style="width: 38px; height: 38px; flex-shrink: 0; background: #ecfdf5;">
                        <i class="fa-solid fa-search" style="color: #047857; font-size: 1.05rem;"></i>
                    </div>
                    
                    <input type="text" id="globalSearchInput" placeholder="พิมพ์หรือกดไมโครโฟนเพื่อค้นหา (เช่น e-bidding, ทะเลน้อย, ผู้ว่า)..." 
                           class="flex-grow-1 px-3"
                           style="border: none; background: transparent; color: #0f172a; outline: none; font-size: 1.02rem; font-weight: 500;" autocomplete="off"
                           onkeydown="if(event.key === 'Enter') triggerSearchSubmit();">
                    
                    <!-- Search Spinner -->
                    <div id="searchSpinner" class="spinner-border text-success spinner-border-sm mx-2 d-none" role="status" style="width: 1.3rem; height: 1.3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <!-- Voice Search Button (Futuristic Voice AI) -->
                    <button type="button" id="btnVoiceSearch" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center me-1 voice-search-btn shadow-xs" 
                            title="พิมพ์ค้นหาด้วยเสียงพูด (Voice Search)" 
                            onclick="toggleVoiceSearch()" 
                            style="width: 42px; height: 42px; border: 1.5px solid rgba(16, 185, 129, 0.4); background: #ecfdf5; color: #047857; transition: all 0.25s ease;">
                        <i class="fa-solid fa-microphone" id="voiceMicIcon" style="font-size: 1.05rem;"></i>
                    </button>
                    
                    <!-- Submit / Search Button -->
                    <button type="button" class="btn rounded-pill px-4 py-2 me-1 fw-bold text-white shadow-sm transition-all hover-scale" 
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none; white-space: nowrap; font-size: 0.98rem;" 
                            onclick="triggerSearchSubmit()">
                        <span>ค้นหา</span>
                    </button>
                </div>

                <!-- Voice Listening Status Bar (Pop-down feedback) -->
                <div id="voiceListeningBar" class="d-none align-items-center justify-content-between px-3 py-2 mt-2 rounded-pill shadow-lg" style="background: rgba(0,0,0,0.85); backdrop-filter: blur(10px); color: #ffffff; border: 1.5px solid #10b981; font-size: 0.88rem;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="voice-wave-dot"></span>
                        <span class="voice-wave-dot" style="animation-delay: 0.2s;"></span>
                        <span class="voice-wave-dot" style="animation-delay: 0.4s;"></span>
                        <span id="voiceListeningStatusText" class="ms-1 fw-semibold text-warning">กำลังฟังเสียงของคุณ (พูดคำที่ต้องการค้นหา)...</span>
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-light rounded-pill px-2.5 py-0.5" style="font-size: 0.75rem;" onclick="stopVoiceSearch()">ยกเลิก</button>
                </div>

                <!-- Quick Trending Search Pills -->
                <?php 
                $trendingKeywords = function_exists('get_trending_keywords') ? get_trending_keywords(6) : [];
                if (!empty($trendingKeywords)): 
                ?>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2.5 pt-2 border-top" style="border-color: rgba(255,255,255,0.12) !important;">
                        <span class="text-light opacity-75 small fw-semibold" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-fire text-warning me-1"></i> คำค้นหายอดนิยม:
                        </span>
                        <?php foreach ($trendingKeywords as $item): 
                            $kw = is_array($item) ? ($item['keyword'] ?? '') : $item;
                            $icon = is_array($item) ? ($item['icon'] ?? '🔥') : '🔥';
                            if (empty($kw)) continue;
                        ?>
                            <a href="javascript:void(0)" onclick="quickSearchTag('<?= esc($kw) ?>')" class="badge rounded-pill px-2.5 py-1 text-decoration-none shadow-xs hover-scale" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.25); font-size: 0.78rem; transition: all 0.2s;">
                                <span><?= $icon ?></span> <?= esc($kw) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Search Results Dropdown -->
                <div id="searchResultsDropdown" class="dropdown-menu w-100 mt-2 p-0 border-0" style="border-radius: 18px; max-height: 450px; overflow-y: auto; display: none; position: absolute; z-index: 1050; background: #ffffff; box-shadow: 0 20px 45px rgba(0,0,0,0.25); border: 2px solid rgba(16, 185, 129, 0.4) !important;">
                    <!-- Results will be injected here via JS -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. NEWS & MEDIA HUB (ศูนย์รวมข่าวสารและสื่อมัลติมีเดีย) -->
<?= $this->include('components/news_media_hub') ?>

<!-- 2. PUBLIC e-SERVICES GRID (ซ่อนไว้ชั่วคราวตามคำขอ) -->
<?php /*
<section id="services" class="my-5 py-2">
    <div class="d-flex flex-wrap align-items-end justify-content-between mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-concierge-bell text-primary me-2"></i>ศูนย์บริการประชาชน (Online e-Services)</h3>
            <p style="color: var(--text-secondary); margin: 0;">เมนูเข้าถึงรวดเร็วสำหรับยื่นเรื่องและตรวจสอบเอกสารสาธารณะ</p>
        </div>
        <small style="color: var(--text-muted);"><i class="fa-solid fa-lock text-success me-1"></i>ข้อมูลเข้ารหัสความปลอดภัยระดับ SSL</small>
    </div>

    <div class="row g-4">
        <?php foreach ($services as $srv): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 h-100 hover-lift d-flex flex-column justify-content-between p-4" 
                 style="cursor: pointer; border-radius: 20px; background: var(--card-bg, #ffffff); box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: all 0.3s ease;" 
                 onclick="<?= $srv['action'] ?>"
                 onmouseover="this.style.boxShadow='0 10px 25px rgba(0,0,0,0.08)';"
                 onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.03)';">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="width: 54px; height: 54px; border-radius: 16px; background: <?= $srv['color'] ?>15; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: <?= $srv['color'] ?>;">
                            <i class="<?= $srv['icon'] ?>"></i>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.8rem; background: #f8fafc; padding: 4px 10px; border-radius: 30px;"><i class="fa-solid fa-arrow-right-to-bracket"></i> กดเข้าถึง</span>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark"><?= $srv['title'] ?></h5>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5; margin-bottom: 0;">
                        <?= $srv['desc'] ?>
                    </p>
                </div>
                <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.05) !important;">
                    <span style="font-size: 0.8rem; color: <?= $srv['color'] ?>; font-weight: 600;">
                        <i class="fa-regular fa-circle-check me-1"></i> บริการออนไลน์
                    </span>
                    <span class="text-primary fw-bold" style="font-size: 0.8rem;">อ่านคู่มือ <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
*/ ?>

<!-- 3.1 PR & PARTNER BANNERS SLIDER (แถบแบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์) -->
<?= $this->include('components/pr_banner_carousel') ?>

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
        window.location.href = `<?= base_url('search') ?>?q=${encodeURIComponent(sInput.value.trim())}`;
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
            const link = item.url && item.url !== '#' ? item.url : 'javascript:void(0)';
            
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
            <a href="${link}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: transparent; border-bottom: 1px solid var(--glass-border, #f1f5f9); transition: all 0.2s;">
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
        <div class="p-2.5 text-center border-top" style="border-color: var(--glass-border, #f1f5f9) !important; background: rgba(0,0,0,0.02);">
            <small class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i> พบผลลัพธ์ทั้งหมด ${results.length} รายการ (คลิกเพื่อเข้าสู่หน้าเนื้อหา)</small>
        </div>
        `;
        
        searchDropdown.innerHTML = html;
    }
    searchDropdown.style.display = 'block';
}
</script>

<!-- 5. DYNAMIC FOOTER & AGENCY CREDENTIALS -->
<footer class="mt-5 pt-4 border-top" style="border-color: var(--glass-border) !important;">
    <div class="glass-card p-4 rounded-4 mb-4" style="background: var(--glass-bg);">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <h5 class="fw-bold mb-2 text-primary d-flex align-items-center gap-2">
                    <?php $footerLogo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                    <?php if (!empty($footerLogo)): ?>
                        <img src="<?= htmlspecialchars($footerLogo) ?>" alt="Logo" style="height: 35px; width: auto; max-width: 45px; object-fit: contain;">
                    <?php else: ?>
                        <i class="fa-solid fa-building-flag me-1"></i>
                    <?php endif; ?>
                    <span><?= htmlspecialchars($cfg['site_title_th'] ?? 'จังหวัดพัทลุง') ?></span>
                </h5>
                <p class="text-secondary mb-2" style="font-size: 0.9rem;">
                    <i class="fa-solid fa-location-dot me-2 text-danger"></i><?= htmlspecialchars($cfg['address'] ?? '') ?>
                </p>
                <div class="d-flex flex-wrap gap-3 mt-2" style="font-size: 0.9rem; color: var(--text-secondary);">
                    <span><i class="fa-solid fa-phone text-success me-1"></i> สายตรง: <strong class="text-primary"><?= htmlspecialchars($cfg['contact_phone'] ?? '-') ?></strong></span>
                    <span><i class="fa-solid fa-envelope text-warning me-1"></i> อีเมล: <strong class="text-primary"><?= htmlspecialchars($cfg['contact_email'] ?? '-') ?></strong></span>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="d-flex justify-content-lg-end gap-3 align-items-center">
                    <?php if(!empty($cfg['fb_url'])): ?>
                    <a href="<?= htmlspecialchars($cfg['fb_url']) ?>" target="_blank" class="btn-modern-outline text-decoration-none" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                        <i class="fa-brands fa-facebook me-2 text-primary"></i> Facebook ประชาสัมพันธ์
                    </a>
                    <?php endif; ?>
                    <?php if(!empty($cfg['line_id'])): ?>
                    <span class="glass-badge" style="font-size: 0.9rem; padding: 0.6rem 1rem;">
                        <i class="fa-brands fa-line text-success me-1" style="font-size: 1.2rem;"></i> LINE Official: <strong class="ms-1"><?= htmlspecialchars($cfg['line_id']) ?></strong>
                    </span>
                    <?php endif; ?>
                </div>
                <small class="d-block text-muted mt-3" style="font-size: 0.8rem;">
                    © 2026 <?= htmlspecialchars($cfg['site_title_en'] ?? 'Phatthalung Portal') ?>. Developed with CodeIgniter 4 Interactive SPA Architecture.
                </small>
            </div>
        </div>
    </div>
</footer>

<?= $this->endSection() ?>

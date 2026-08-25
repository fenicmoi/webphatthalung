<?php
// W3C WCAG AAA Universal Accessibility & Thai TTS Engine (2026+ Standard)
?>

<style>
/* ==========================================================================
   W3C WCAG AAA UNIVERSAL ACCESSIBILITY SYSTEM (CSS CUSTOM TOKENS & OVERRIDES)
   ========================================================================== */
.w3c-aaa-trigger {
    position: fixed;
    bottom: 22px;
    left: 22px;
    z-index: 1045;
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff !important;
    border: 2px solid #38bdf8;
    border-radius: 50px;
    padding: 6px 14px 6px 6px;
    box-shadow: 0 8px 24px rgba(2, 132, 199, 0.4), 0 0 15px rgba(56, 189, 248, 0.2);
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.84rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-decoration: none;
}
.w3c-aaa-trigger:hover {
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 12px 30px rgba(2, 132, 199, 0.6);
    color: #fff;
    border-color: #00f0ff;
}
.w3c-icon-badge {
    width: 36px;
    height: 36px;
    background: #fff;
    color: #0284c7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    flex-shrink: 0;
}
@media (max-width: 1199px) {
    .w3c-aaa-trigger {
        padding: 0;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        justify-content: center;
    }
    .w3c-icon-badge {
        width: 42px;
        height: 42px;
        box-shadow: none;
    }
}
}

/* Modal styling */
.w3c-modal-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0369a1 100%);
    border-bottom: 3px solid #38bdf8;
    color: #ffffff;
}
.w3c-card-option {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    transition: all 0.2s;
}
.w3c-card-option.active-mode {
    border-color: #0284c7;
    background: #f0f9ff;
    box-shadow: 0 4px 15px rgba(2, 132, 199, 0.15);
}

/* --- AAA OVERRIDE CLASSES (APPLIED TO HTML/BODY) --- */

/* 1. High Contrast AAA (Black / Bright Yellow) */
html.w3c-high-contrast, body.w3c-high-contrast {
    background-color: #000000 !important;
    color: #ffff00 !important;
}
html.w3c-high-contrast div, html.w3c-high-contrast section, html.w3c-high-contrast card, html.w3c-high-contrast .card, html.w3c-high-contrast table, html.w3c-high-contrast tr, html.w3c-high-contrast td {
    background: #000000 !important;
    color: #ffff00 !important;
    border-color: #ffff00 !important;
}
html.w3c-high-contrast a, html.w3c-high-contrast button {
    color: #00ffff !important;
    border: 2px solid #00ffff !important;
    font-weight: 900 !important;
    text-decoration: underline !important;
}
html.w3c-high-contrast .badge {
    background: #ffff00 !important;
    color: #000000 !important;
}

/* 2. Colorblind Friendly (Deuteranopia & Protanopia High Saturation Filter) */
html.w3c-colorblind {
    filter: hue-rotate(20deg) contrast(1.2) saturate(1.3);
}

/* 3. Monochrome Grayscale */
html.w3c-grayscale {
    filter: grayscale(100%) contrast(1.1);
}

/* 4. Dyslexic & Readability Spacing */
body.w3c-dyslexic * {
    font-family: 'Sarabun', 'Arial', sans-serif !important;
    letter-spacing: 1.5px !important;
    word-spacing: 4px !important;
    line-height: 1.8 !important;
}

/* 5. Highlight Interactive Links & Buttons */
body.w3c-highlight-links a, body.w3c-highlight-links button {
    outline: 3px solid #f59e0b !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 10px #f59e0b !important;
    background-color: rgba(245, 158, 11, 0.15) !important;
}

/* 6. Giant Cursor */
body.w3c-big-cursor * {
    cursor: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='48' height='48' viewBox='0 0 24 24'%3E%3Cpath fill='%23ffff00' stroke='%23000000' stroke-width='2' d='M5.5 3.21V20.8c0 .45.54.67.85.35l4.86-4.86a.5.5 0 0 1 .35-.15h6.87a.5.5 0 0 0 .35-.85L6.35 2.85a.5.5 0 0 0-.85.35Z'/%3E%3C/svg%3E"), auto !important;
}

/* 7. Reading Line Ruler Tracker */
#w3cReadingRuler {
    position: fixed;
    left: 0;
    width: 100vw;
    height: 44px;
    background: rgba(254, 240, 138, 0.35);
    border-top: 3px solid #ef4444;
    border-bottom: 3px solid #ef4444;
    pointer-events: none;
    z-index: 1040;
    display: none;
    transition: transform 0.05s ease-out;
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.5);
}
#w3cReadingRuler.active {
    display: block;
}
</style>

<!-- READING RULER TRACKER ELEMENT -->
<div id="w3cReadingRuler" aria-hidden="true"></div>

<!-- FLOATING W3C AAA ACCESSIBILITY TRIGGER (BOTTOM LEFT) -->
<button type="button" class="w3c-aaa-trigger" id="w3cTriggerBtn" onclick="W3CAccessibility.openModal()" title="เครื่องมือช่วยอ่านและปรับการแสดงผล (สำหรับผู้สูงอายุและผู้มีปัญหาการมองเห็น)">
    <div class="w3c-icon-badge"><i class="fa-solid fa-universal-access animate-pulse"></i></div>
    <span class="d-none d-xl-inline-block text-nowrap">ช่วยเหลือการเข้าถึง</span>
</button>

<!-- W3C WCAG AAA UNIVERSAL ACCESSIBILITY MODAL -->
<div class="modal fade" id="w3cAaaAccessibilityModal" tabindex="-1" aria-labelledby="w3cAaaModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header w3c-modal-header px-4 py-3 rounded-top-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-circle bg-white text-info d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-universal-access fs-3 text-info"></i>
                    </div>
                    <div>
                        <span class="badge bg-info text-dark fw-bold px-3 py-1 rounded-pill mb-1">บริการผู้สูงอายุ & ประชาชนทุกคน (W3C AAA)</span>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="w3cAaaModalLabel">ศูนย์เครื่องมืออำนวยความสะดวก (ปรับตัวอักษร & ฟังเสียงอ่าน)</h5>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 text-dark">
                <div class="alert alert-info border-info border-opacity-50 rounded-3 p-3 mb-4 d-flex align-items-center gap-3">
                    <i class="fa-solid fa-circle-info fs-3 text-info flex-shrink-0"></i>
                    <div>
                        <strong>เพื่อประชาชนทุกคนโดยไร้ข้อจำกัด:</strong> ระบบถูกออกแบบมารองรับผู้สูงอายุ ผู้พิการทางสายตา ผู้มีภาวะตาบอดสี และผู้บกพร่องทางการอ่าน การตั้งค่าทั้งหมดจะถูกบันทึกจำไว้ตลอดการท่องเว็บไซต์
                    </div>
                </div>

                <!-- SECTION 1: THAI TEXT-TO-SPEECH (TTS) & AUDIO GUIDE -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-volume-high text-danger me-2"></i>1. ระบบเสียงอ่านข้อความภาษาไทย (Thai AI Text-to-Speech & Audio Guide)</h6>
                <div class="w3c-card-option mb-4" id="card-tts">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                        <div>
                            <span class="fw-bold text-dark fs-6 d-block mb-1">🔊 โหมดอ่านข้อความเมื่อนำเมาส์ไปชี้ (Hover Text-to-Speech)</span>
                            <span class="text-muted small">ระบบจะอ่านออกเสียงหัวข้อ ปุ่ม และข้อความภาษาไทย เมื่อคุณเลื่อนเมาส์ผ่าน</span>
                        </div>
                        <div class="form-check form-switch fs-4 ms-0">
                            <input class="form-check-input ms-0" type="checkbox" role="switch" id="toggleTTS" onchange="W3CAccessibility.toggleTTS(this.checked)">
                        </div>
                    </div>
                    <div class="row align-items-center g-3 pt-2 border-top">
                        <div class="col-sm-6 d-flex align-items-center gap-2">
                            <label class="form-label small mb-0 text-muted fw-bold">ระดับความเร็วเสียงอ่าน:</label>
                            <select class="form-select form-select-sm w-auto fw-bold text-primary" id="ttsSpeed" onchange="W3CAccessibility.setSpeechSpeed(this.value)">
                                <option value="0.75">0.75x (อ่านช้าชัดเจน)</option>
                                <option value="1.0" selected>1.0x (ความเร็วมาตรฐาน)</option>
                                <option value="1.25">1.25x (อ่านเร็วและกระชับ)</option>
                            </select>
                        </div>
                        <div class="col-sm-6 text-end">
                            <button type="button" onclick="W3CAccessibility.testTTS()" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3 shadow-sm">
                                <i class="fa-solid fa-bullhorn me-1"></i> ทดสอบเสียงอ่าน AI
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: CONTRAST & VISION PROFILES (WCAG AAA) -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-eye text-primary me-2"></i>2. การมองเห็นและการตัดกันของสี (Vision & Color Blindness Profiles)</h6>
                <div class="row g-2 mb-4">
                    <div class="col-6 col-md-4">
                        <button type="button" class="btn btn-outline-secondary w-100 p-3 rounded-3 fw-bold text-start text-dark d-flex flex-column gap-1 h-100" id="btnModeNormal" onclick="W3CAccessibility.setColorProfile('normal')">
                            <span class="fs-5">☀️ สีมาตรฐาน</span>
                            <span class="small text-muted" style="font-size:0.75rem;">โหมดการแสดงผลสบายตาปกติ</span>
                        </button>
                    </div>
                    <div class="col-6 col-md-4">
                        <button type="button" class="btn btn-outline-dark w-100 p-3 rounded-3 fw-bold text-start bg-dark text-white d-flex flex-column gap-1 h-100" id="btnModeDark" onclick="W3CAccessibility.setColorProfile('dark')">
                            <span class="fs-5 text-info">🌙 ถนอมสายตา</span>
                            <span class="small text-light opacity-75" style="font-size:0.75rem;">โหมดมืด (Dark Night Mode)</span>
                        </button>
                    </div>
                    <div class="col-12 col-md-4">
                        <button type="button" class="btn btn-outline-warning w-100 p-3 rounded-3 fw-bold text-start d-flex flex-column gap-1 h-100" style="background:#000000; color:#ffff00; border: 2px solid #ffff00;" id="btnModeHighContrast" onclick="W3CAccessibility.setColorProfile('high-contrast')">
                            <span class="fs-5">🟡 ความสว่าง AAA</span>
                            <span class="small" style="font-size:0.75rem; color:#00ffff;">ตัดกันสูงสุด (High Contrast)</span>
                        </button>
                    </div>
                    <div class="col-6 col-md-6">
                        <button type="button" class="btn btn-outline-info w-100 p-3 rounded-3 fw-bold text-start text-dark d-flex align-items-center gap-2" id="btnModeColorblind" onclick="W3CAccessibility.setColorProfile('colorblind')">
                            <i class="fa-solid fa-palette fs-3 text-primary"></i>
                            <div>
                                <span class="d-block">🔵 โหมดผู้มีภาวะตาบอดสี</span>
                                <span class="small text-muted" style="font-size:0.75rem;">เพิ่มมิติสีแดง/เขียว (Colorblind Filter)</span>
                            </div>
                        </button>
                    </div>
                    <div class="col-6 col-md-6">
                        <button type="button" class="btn btn-outline-secondary w-100 p-3 rounded-3 fw-bold text-start text-dark d-flex align-items-center gap-2" id="btnModeGrayscale" onclick="W3CAccessibility.setColorProfile('grayscale')">
                            <i class="fa-solid fa-circle-half-stroke fs-3 text-secondary"></i>
                            <div>
                                <span class="d-block">⚪ โหมดขาว-ดำ (Monochrome)</span>
                                <span class="small text-muted" style="font-size:0.75rem;">ลดความเมื่อยล้าและสิ่งเร้าสายตา</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- SECTION 3: TYPOGRAPHY & READABILITY AAA -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-text-height text-success me-2"></i>3. ขนาดตัวอักษรและระยะการอ่าน (AAA Typography & Spacing)</h6>
                <div class="w3c-card-option mb-4">
                    <label class="form-label fw-bold text-dark small d-block mb-2">ปรับขนาดอักษรบนจอ (Font Size Scale):</label>
                    <div class="btn-group w-100 mb-3" role="group" id="w3cFontGroup">
                        <button type="button" class="btn btn-outline-primary fw-bold active" onclick="W3CAccessibility.setFontScale(100, this)">100% (ปกติ)</button>
                        <button type="button" class="btn btn-outline-primary fw-bold" onclick="W3CAccessibility.setFontScale(115, this)">115% (กลาง)</button>
                        <button type="button" class="btn btn-outline-primary fw-bold" onclick="W3CAccessibility.setFontScale(130, this)">130% (ผู้สูงอายุ)</button>
                        <button type="button" class="btn btn-outline-primary fw-bold" onclick="W3CAccessibility.setFontScale(150, this)">150% (ขยายใหญ่)</button>
                    </div>

                    <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between border">
                        <div>
                            <label class="form-check-label fw-bold text-dark d-block" for="toggleDyslexic">🅰️ โหมดอักษรอ่านง่ายพิเศษ (Dyslexia & Elderly Spacing)</label>
                            <span class="small text-muted">ขยายระยะห่างระหว่างตัวอักษรและบรรทัด เพื่อลดอาการตาลาย</span>
                        </div>
                        <input class="form-check-input fs-4 ms-0" type="checkbox" id="toggleDyslexic" onchange="W3CAccessibility.toggleDyslexic(this.checked)">
                    </div>
                </div>

                <!-- SECTION 4: CURSOR & READING TRACKING HELPERS -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-computer-mouse text-warning me-2"></i>4. ตัวช่วยเมาส์และการนำทาง (Cursor & Tracking Helpers)</h6>
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <div class="form-check form-switch p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between">
                            <label class="form-check-label fw-bold text-dark mb-2" for="toggleRuler"><i class="fa-solid fa-ruler-horizontal text-danger me-1"></i> 🎯 ไม้บรรทัดนำทางสายตา</label>
                            <div class="d-flex justify-content-end">
                                <input class="form-check-input fs-4 ms-0" type="checkbox" id="toggleRuler" onchange="W3CAccessibility.toggleRuler(this.checked)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between">
                            <label class="form-check-label fw-bold text-dark mb-2" for="toggleCursor"><i class="fa-solid fa-arrow-pointer text-primary me-1"></i> 🖱️ เคอร์เซอร์ใหญ่พิเศษ</label>
                            <div class="d-flex justify-content-end">
                                <input class="form-check-input fs-4 ms-0" type="checkbox" id="toggleCursor" onchange="W3CAccessibility.toggleCursor(this.checked)">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch p-3 bg-light rounded-3 border h-100 d-flex flex-column justify-content-between">
                            <label class="form-check-label fw-bold text-dark mb-2" for="toggleHighlight"><i class="fa-solid fa-highlighter text-warning me-1"></i> ✨ ไฮไลท์กรอบลิงก์ & ปุ่ม</label>
                            <div class="d-flex justify-content-end">
                                <input class="form-check-input fs-4 ms-0" type="checkbox" id="toggleHighlight" onchange="W3CAccessibility.toggleHighlight(this.checked)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4 justify-content-between">
                <button type="button" onclick="W3CAccessibility.resetAll()" class="btn btn-outline-danger fw-bold rounded-pill px-4">
                    <i class="fa-solid fa-rotate-left me-1"></i> คืนค่าปกติทั้งหมด
                </button>
                <button type="button" class="btn btn-primary fw-bold rounded-pill px-5 shadow" data-bs-dismiss="modal">
                    <i class="fa-solid fa-check me-1"></i> บันทึก & ปิดหน้าต่าง
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================================================
// W3C WCAG AAA ACCESSIBILITY & THAI TTS ENGINE JAVASCRIPT
// ============================================================================
const W3CAccessibility = {
    modalInstance: null,
    ttsEnabled: false,
    speechRate: 1.0,
    synth: window.speechSynthesis || null,
    activeVoice: null,

    init: function() {
        // Restore preferences from localStorage
        const profile = localStorage.getItem('w3c_profile') || 'normal';
        const tts = localStorage.getItem('w3c_tts') === 'true';
        const dyslexic = localStorage.getItem('w3c_dyslexic') === 'true';
        const ruler = localStorage.getItem('w3c_ruler') === 'true';
        const cursor = localStorage.getItem('w3c_cursor') === 'true';
        const highlight = localStorage.getItem('w3c_highlight') === 'true';
        const fontScale = localStorage.getItem('w3c_font_scale') || '100';
        this.speechRate = parseFloat(localStorage.getItem('w3c_tts_rate')) || 1.0;

        this.setColorProfile(profile, false);
        this.setFontScale(parseInt(fontScale), null, false);
        if (dyslexic) { document.getElementById('toggleDyslexic').checked = true; this.toggleDyslexic(true, false); }
        if (ruler) { document.getElementById('toggleRuler').checked = true; this.toggleRuler(true, false); }
        if (cursor) { document.getElementById('toggleCursor').checked = true; this.toggleCursor(true, false); }
        if (highlight) { document.getElementById('toggleHighlight').checked = true; this.toggleHighlight(true, false); }
        if (tts) { document.getElementById('toggleTTS').checked = true; this.toggleTTS(true, false); }
        if (document.getElementById('ttsSpeed')) document.getElementById('ttsSpeed').value = this.speechRate;

        // Init Thai Voice
        if (this.synth) {
            const getVoices = () => {
                const voices = this.synth.getVoices();
                this.activeVoice = voices.find(v => v.lang.includes('th')) || voices[0];
            };
            getVoices();
            if (speechSynthesis.onvoiceschanged !== undefined) {
                speechSynthesis.onvoiceschanged = getVoices;
            }
        }

        // Bind Ruler mousemove tracking
        document.addEventListener('mousemove', (e) => {
            const rulerEl = document.getElementById('w3cReadingRuler');
            if (rulerEl && rulerEl.classList.contains('active')) {
                rulerEl.style.top = (e.clientY - 22) + 'px';
            }
        });

        // Bind Hover TTS Events to important elements
        this.bindHoverTTS();
    },

    openModal: function() {
        const el = document.getElementById('w3cAaaAccessibilityModal');
        if (el && typeof bootstrap !== 'undefined') {
            if (el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
            if (!this.modalInstance) this.modalInstance = new bootstrap.Modal(el);
            this.modalInstance.show();
        }
    },

    toggleTTS: function(enabled, notify = true) {
        this.ttsEnabled = enabled;
        localStorage.setItem('w3c_tts', enabled);
        const card = document.getElementById('card-tts');
        if (card) {
            if (enabled) card.classList.add('active-mode');
            else card.classList.remove('active-mode');
        }
        if (notify && typeof App !== 'undefined') {
            App.toast(enabled ? '🔊 เปิดใช้งานระบบเสียงอ่านภาษาไทย (Hover TTS)' : '🔇 ปิดระบบเสียงอ่าน', 'info');
        }
        if (enabled && notify) {
            this.speak("ระบบเสียงอ่านข้อความภาษาไทย เปิดใช้งานแล้วครับ");
        } else if (this.synth) {
            this.synth.cancel();
        }
    },

    setSpeechSpeed: function(val) {
        this.speechRate = parseFloat(val);
        localStorage.setItem('w3c_tts_rate', val);
        if (this.ttsEnabled) {
            this.speak("ปรับความเร็วเสียงอ่านเป็นระดับ " + val + " แล้วครับ");
        }
    },

    testTTS: function() {
        this.speak("สวัสดีครับ! ยินดีต้อนรับสู่เว็บไซต์ทางการจังหวัดพัทลุง ศูนย์เครื่องมืออำนวยความสะดวก ปรับขนาดอักษรและเสียงอ่าน พร้อมให้บริการประชาชนทุกคนครับ");
    },

    speak: function(text) {
        if (!this.synth || !text) return;
        this.synth.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = this.speechRate;
        utterance.pitch = 1.0;
        if (this.activeVoice) utterance.voice = this.activeVoice;
        else utterance.lang = 'th-TH';
        this.synth.speak(utterance);
    },

    bindHoverTTS: function() {
        const targets = 'h1, h2, h3, h4, h5, h6, .card-title, button, .dock-item-label, .marquee-text, .badge, .btn';
        document.querySelectorAll(targets).forEach(el => {
            el.addEventListener('mouseenter', () => {
                if (!this.ttsEnabled || !el.textContent.trim()) return;
                this.speak(el.textContent.trim());
            });
            el.addEventListener('mouseleave', () => {
                if (!this.ttsEnabled || !this.synth) return;
                // Optional: keep finishing or stop immediately
            });
        });
    },

    setColorProfile: function(profile, notify = true) {
        document.documentElement.classList.remove('w3c-high-contrast', 'w3c-colorblind', 'w3c-grayscale');
        document.body.classList.remove('w3c-high-contrast', 'w3c-colorblind', 'w3c-grayscale');
        
        // Disable old dark mode attribute if conflicting
        if (profile === 'normal') {
            document.documentElement.setAttribute('data-theme', 'light');
            document.body.setAttribute('data-theme', 'light');
        } else if (profile === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.body.setAttribute('data-theme', 'dark');
        } else if (profile === 'high-contrast') {
            document.documentElement.classList.add('w3c-high-contrast');
            document.body.classList.add('w3c-high-contrast');
        } else if (profile === 'colorblind') {
            document.documentElement.classList.add('w3c-colorblind');
        } else if (profile === 'grayscale') {
            document.documentElement.classList.add('w3c-grayscale');
        }

        localStorage.setItem('w3c_profile', profile);
        if (notify && typeof App !== 'undefined') {
            App.toast('เปลี่ยนโหมดการมองเห็นสีเป็น: ' + profile.toUpperCase(), 'info');
        }
    },

    setFontScale: function(scale, btnEl, notify = true) {
        document.documentElement.style.fontSize = (16 * (scale / 100)) + 'px';
        localStorage.setItem('w3c_font_scale', scale);

        if (btnEl) {
            document.querySelectorAll('#w3cFontGroup .btn').forEach(b => b.classList.remove('active', 'btn-primary'));
            btnEl.classList.add('active');
        }
        if (notify && typeof App !== 'undefined') {
            App.toast('ปรับขนาดตัวอักษรเป็น: ' + scale + '%', 'info');
        }
    },

    toggleDyslexic: function(enabled, notify = true) {
        localStorage.setItem('w3c_dyslexic', enabled);
        if (enabled) document.body.classList.add('w3c-dyslexic');
        else document.body.classList.remove('w3c-dyslexic');
        if (notify && typeof App !== 'undefined') App.toast(enabled ? '🅰️ เปิดโหมดอักษรอ่านง่าย (Dyslexic Friendly)' : 'ปิดโหมดอักษรพิเศษ', 'info');
    },

    toggleRuler: function(enabled, notify = true) {
        localStorage.setItem('w3c_ruler', enabled);
        const ruler = document.getElementById('w3cReadingRuler');
        if (ruler) {
            if (enabled) ruler.classList.add('active');
            else ruler.classList.remove('active');
        }
        if (notify && typeof App !== 'undefined') App.toast(enabled ? '🎯 เปิดไม้บรรทัดนำทางสายตา' : 'ปิดไม้บรรทัดนำทางสายตา', 'info');
    },

    toggleCursor: function(enabled, notify = true) {
        localStorage.setItem('w3c_cursor', enabled);
        if (enabled) document.body.classList.add('w3c-big-cursor');
        else document.body.classList.remove('w3c-big-cursor');
        if (notify && typeof App !== 'undefined') App.toast(enabled ? '🖱️ ขยายเคอร์เซอร์เมาส์ขนาดใหญ่' : 'คืนค่าเคอร์เซอร์ปกติ', 'info');
    },

    toggleHighlight: function(enabled, notify = true) {
        localStorage.setItem('w3c_highlight', enabled);
        if (enabled) document.body.classList.add('w3c-highlight-links');
        else document.body.classList.remove('w3c-highlight-links');
        if (notify && typeof App !== 'undefined') App.toast(enabled ? '✨ เปิดกรอบไฮไลท์ลิงก์และปุ่มทั้งหมด' : 'ปิดไฮไลท์ลิงก์', 'info');
    },

    resetAll: function() {
        localStorage.removeItem('w3c_profile');
        localStorage.removeItem('w3c_tts');
        localStorage.removeItem('w3c_dyslexic');
        localStorage.removeItem('w3c_ruler');
        localStorage.removeItem('w3c_cursor');
        localStorage.removeItem('w3c_highlight');
        localStorage.removeItem('w3c_font_scale');
        localStorage.removeItem('w3c_tts_rate');

        this.setColorProfile('normal', false);
        this.setFontScale(100, null, false);
        this.toggleTTS(false, false);
        this.toggleDyslexic(false, false);
        this.toggleRuler(false, false);
        this.toggleCursor(false, false);
        this.toggleHighlight(false, false);

        document.querySelectorAll('#w3cAaaAccessibilityModal input[type="checkbox"]').forEach(c => c.checked = false);
        if (typeof App !== 'undefined') App.toast('🔄 คืนค่าปกติของการแสดงผลและเสียงอ่านเรียบร้อยแล้ว', 'success');
    }
};

// Initialize on DOM Ready
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        W3CAccessibility.init();
    }, 500);
});
</script>

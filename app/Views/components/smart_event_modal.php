<?php
/**
 * Smart Event Viewer Modal
 * หน้าต่างแสดงรายละเอียดกิจกรรมและตารางงาน (เชื่อมโยงกับระบบข่าวและพิกัด Google Maps)
 */
?>
<div class="modal fade" id="smartEventViewerModal" tabindex="-1" aria-labelledby="sevTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-2xl overflow-hidden" style="background: #ffffff; color: #1e293b; border: 2px solid rgba(16, 185, 129, 0.35) !important;">
            
            <!-- Cover Header (if image available) -->
            <div id="sevCoverContainer" class="position-relative w-100" style="height: 240px; background: #022c22; overflow: hidden; display: none;">
                <img id="sevCoverImg" src="" alt="Event Cover" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(2, 44, 34, 0.1) 0%, rgba(2, 44, 34, 0.9) 100%);"></div>
                
                <span class="badge position-absolute top-0 start-0 m-3 rounded-pill px-3 py-2 text-white shadow-md d-inline-flex align-items-center gap-1.5" style="background: linear-gradient(135deg, #059669, #047857); font-size: 0.85rem; z-index: 2;">
                    <i class="fa-solid fa-calendar-check text-warning"></i>
                    <span>ปฏิทินกิจกรรมจังหวัดพัทลุง</span>
                </span>
                
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 p-2 bg-dark bg-opacity-50 rounded-circle shadow-sm" data-bs-dismiss="modal" aria-label="Close" style="z-index: 3;"></button>
            </div>

            <!-- Standard Header when no cover image -->
            <div id="sevStandardHeader" class="modal-header py-3 px-4 d-flex align-items-center justify-content-between text-white" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 100%); border-bottom: 2px solid #10b981;">
                <div class="d-flex align-items-center gap-3">
                    <span class="p-2 rounded-3 text-white d-flex align-items-center justify-content-center shadow-xs" style="background: rgba(255,255,255,0.15); width: 42px; height: 42px; border: 1px solid rgba(255,255,255,0.25);">
                        <i class="fa-solid fa-calendar-day fs-5 text-warning"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold m-0 text-white" style="font-size: 1.1rem;">รายละเอียดกิจกรรมจังหวัดพัทลุง</h6>
                        <small class="text-light opacity-75">Provincial Event Calendar Spotlight</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 p-md-5 bg-white">
                <!-- Event Dates & Voice TTS Header Strip -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 px-3 py-2 rounded-pill fs-6 fw-bold d-inline-flex align-items-center gap-2 shadow-xs" style="color: #047857 !important;">
                            <i class="fa-regular fa-clock text-warning"></i>
                            <span id="sevDateRangeText">วันที่จัดกิจกรรม</span>
                        </span>
                        <span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-40 px-3 py-2 rounded-pill small fw-semibold" id="sevCatText">ข่าวกิจกรรมจังหวัด</span>
                    </div>

                    <!-- Voice TTS Audio Narration Button -->
                    <button type="button" id="sevSpeakBtn" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2 shadow-xs hover-scale" onclick="SmartEventViewer.toggleSpeakCurrentEvent()">
                        <i class="fa-solid fa-volume-high text-success" id="sevSpeakIcon"></i>
                        <span id="sevSpeakText">ฟังเสียงบรรยาย</span>
                    </button>
                </div>

                <!-- Event Title -->
                <h4 id="sevTitleText" class="fw-bold mb-4" style="color: #0f172a; line-height: 1.45; font-size: 1.25rem;">
                    ชื่อกิจกรรม
                </h4>

                <!-- Location & Navigation Box -->
                <div id="sevLocationBox" class="p-3.5 rounded-4 mb-4 border d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 shadow-xs" style="background: #f0fdf4; border-color: rgba(16, 185, 129, 0.3) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2.5 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="background: #ffffff; width: 46px; height: 46px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid rgba(16, 185, 129, 0.25);">
                            <i class="fa-solid fa-location-dot fs-5 text-danger"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold d-block" style="font-size: 0.8rem;">สถานที่ / จุดนัดหมาย:</small>
                            <span id="sevLocationText" class="fw-bold" style="color: #064e3b; font-size: 0.98rem;">ไม่ระบุสถานที่</span>
                        </div>
                    </div>
                    <a id="sevMapBtn" href="#" target="_blank" class="btn btn-sm text-white rounded-pill px-4 py-2 fw-bold flex-shrink-0 shadow-xs hover-scale d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none;">
                        <i class="fa-solid fa-map-location-dot fs-6 text-warning"></i>
                        <span>นำทางด้วย Google Maps</span>
                    </a>
                </div>

                <!-- Summary / Content Excerpt -->
                <div id="sevSummaryBox" class="p-4 rounded-4 mb-4 border" style="background: #f8fafc; border-color: #e2e8f0 !important; font-size: 0.96rem; line-height: 1.7; color: #334155;">
                </div>

                <!-- Footer CTA Buttons -->
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <?php if (session()->get('isLoggedIn')): ?>
                            <button id="sevEditBtn" type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-xs d-flex align-items-center gap-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>แก้ไขกิจกรรมนี้ (Studio)</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-end">
                        <button type="button" class="btn btn-light border text-secondary rounded-pill px-4 py-2" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                        <a id="sevReadMoreBtn" href="#" class="btn fw-bold rounded-pill px-4 py-2 text-white shadow-xs hover-scale d-flex align-items-center gap-2" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none;">
                            <span>อ่านข่าวสารฉบับเต็ม</span>
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
var SmartEventViewer = {
    modal: null,
    eventsMap: {},
    currentEvent: null,
    isSpeaking: false,

    init: function() {
        var el = document.getElementById('smartEventViewerModal');
        if (el && typeof bootstrap !== 'undefined') {
            this.modal = new bootstrap.Modal(el);
            el.addEventListener('hidden.bs.modal', function() {
                SmartEventViewer.stopSpeaking();
            });
        }
    },

    registerEvents: function(eventList) {
        if (!Array.isArray(eventList)) return;
        eventList.forEach(function(ev) {
            SmartEventViewer.eventsMap[ev.id] = ev;
        });
    },

    open: function(eventId) {
        if (!this.modal) this.init();
        var ev = this.eventsMap[eventId];
        if (!ev) {
            if (typeof App !== 'undefined' && App.toast) {
                App.toast('ไม่พบข้อมูลรายละเอียดของกิจกรรมดังกล่าว', 'error');
            }
            return;
        }

        this.currentEvent = ev;
        this.stopSpeaking();

        // Title and Category
        document.getElementById('sevTitleText').innerText = ev.title || 'ไม่ระบุชื่อกิจกรรม';
        document.getElementById('sevCatText').innerText = ev.category || 'ข่าวกิจกรรมจังหวัด';

        // Dates formatting
        var sDate = ev.event_start_date || '';
        var eDate = ev.event_end_date || '';
        var dateText = 'กำหนดการ: ' + (sDate ? SmartEventViewer.formatThaiDate(sDate) : 'เร็วๆ นี้');
        if (eDate && eDate !== sDate) {
            dateText += ' ถึง ' + SmartEventViewer.formatThaiDate(eDate);
        }
        document.getElementById('sevDateRangeText').innerText = dateText;

        // Cover image
        var cov = ev.cover_image;
        if (cov && cov !== 'assets/images/slider/sane_muanglung.png' && cov.trim() !== '') {
            var fullCov = (cov.startsWith('http') || cov.startsWith('data:') || cov.startsWith('uploads/')) ? ((cov.startsWith('http')) ? cov : "<?= base_url() ?>/" + cov.replace(/^\//, '')) : "<?= base_url() ?>/" + cov;
            document.getElementById('sevCoverImg').src = fullCov;
            document.getElementById('sevCoverContainer').style.display = 'block';
            document.getElementById('sevStandardHeader').style.display = 'none';
        } else {
            document.getElementById('sevCoverContainer').style.display = 'none';
            document.getElementById('sevStandardHeader').style.display = 'flex';
        }

        // Location and GPS Map
        var loc = ev.event_location || 'บริเวณสถานที่ตั้งตามเนื้อหาประกาศ';
        document.getElementById('sevLocationText').innerText = loc;
        
        var coords = ev.event_coordinates;
        var mapBtn = document.getElementById('sevMapBtn');
        if (coords && coords.trim() !== '') {
            var mapUrl = (coords.startsWith('http')) ? coords : ("https://maps.google.com/?q=" + encodeURIComponent(coords.trim()));
            mapBtn.setAttribute('href', mapUrl);
            mapBtn.style.display = 'inline-flex';
        } else {
            var defaultMapUrl = "https://maps.google.com/?q=" + encodeURIComponent("จังหวัดพัทลุง " + loc);
            mapBtn.setAttribute('href', defaultMapUrl);
            mapBtn.style.display = 'inline-flex';
        }

        // Summary
        var sum = ev.summary || (ev.content ? ev.content.replace(/<[^>]*>?/gm, '').substring(0, 220) + '...' : 'ไม่มีคำอธิบายเพิ่มเติม');
        document.getElementById('sevSummaryBox').innerHTML = sum;

        // Links and Buttons
        var readBtn = document.getElementById('sevReadMoreBtn');
        if (readBtn) readBtn.setAttribute('href', "<?= base_url('news/detail') ?>/" + ev.id);

        var editBtn = document.getElementById('sevEditBtn');
        if (editBtn) {
            editBtn.onclick = function() {
                if (SmartEventViewer.modal) SmartEventViewer.modal.hide();
                setTimeout(function() {
                    if (typeof NewsStudio !== 'undefined') NewsStudio.open(ev.id);
                }, 400);
            };
        }

        if (this.modal) this.modal.show();
    },

    formatThaiDate: function(dateStr) {
        if (!dateStr) return '';
        var parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        var y = parseInt(parts[0], 10) + 543;
        var m = parseInt(parts[1], 10);
        var d = parseInt(parts[2], 10);
        var months = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        return d + ' ' + (months[m] || '') + ' ' + y;
    },

    toggleSpeakCurrentEvent: function() {
        if (this.isSpeaking) {
            this.stopSpeaking();
            return;
        }
        if (!this.currentEvent) return;

        if (!('speechSynthesis' in window)) {
            alert('เบราว์เซอร์ของท่านไม่รองรับระบบอ่านออกเสียง (Speech Synthesis)');
            return;
        }

        window.speechSynthesis.cancel();

        var ev = this.currentEvent;
        var dateInfo = document.getElementById('sevDateRangeText').innerText;
        var locInfo = ev.event_location ? ('สถานที่ ' + ev.event_location) : 'ไม่ระบุสถานที่';
        var sumInfo = ev.summary ? ev.summary.replace(/<[^>]*>?/gm, '') : '';

        var fullSpeech = 'กิจกรรม ' + ev.title + ' ' + dateInfo + ' ' + locInfo + ' รายละเอียด ' + sumInfo;

        var utter = new SpeechSynthesisUtterance(fullSpeech);
        utter.lang = 'th-TH';
        utter.rate = 1.0;

        var voices = window.speechSynthesis.getVoices();
        var thVoice = voices.find(function(v) { return v.lang === 'th-TH' || v.lang === 'th_TH' || v.lang.startsWith('th'); });
        if (thVoice) utter.voice = thVoice;

        var self = this;
        utter.onstart = function() {
            self.isSpeaking = true;
            var btn = document.getElementById('sevSpeakBtn');
            var icon = document.getElementById('sevSpeakIcon');
            var text = document.getElementById('sevSpeakText');
            if (btn) {
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-danger', 'text-white');
            }
            if (icon) icon.className = 'fa-solid fa-stop text-white';
            if (text) text.innerText = 'หยุดฟังเสียง';
        };

        utter.onend = utter.onerror = function() {
            self.stopSpeaking();
        };

        window.speechSynthesis.speak(utter);
    },

    stopSpeaking: function() {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
        }
        this.isSpeaking = false;
        var btn = document.getElementById('sevSpeakBtn');
        var icon = document.getElementById('sevSpeakIcon');
        var text = document.getElementById('sevSpeakText');
        if (btn) {
            btn.classList.remove('btn-danger', 'text-white');
            btn.classList.add('btn-outline-success');
        }
        if (icon) icon.className = 'fa-solid fa-volume-high text-success';
        if (text) text.innerText = 'ฟังเสียงบรรยาย';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    SmartEventViewer.init();
});
</script>

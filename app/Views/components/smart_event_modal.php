<?php
/**
 * Smart Event Viewer Modal
 * หน้าต่างแสดงรายละเอียดกิจกรรมและตารางงาน (เชื่อมโยงกับระบบข่าวและพิกัด Google Maps)
 */
?>
<div class="modal fade" id="smartEventViewerModal" tabindex="-1" aria-labelledby="sevTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-2xl overflow-hidden" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(16, 185, 129, 0.4) !important; color: #f8fafc;">
            
            <!-- Cover Header -->
            <div id="sevCoverContainer" class="position-relative w-100" style="height: 260px; background: #0f172a; overflow: hidden; display: none;">
                <img id="sevCoverImg" src="" alt="Event Cover" class="w-100 h-100 object-fit-cover" style="object-fit: cover;">
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(15, 23, 42, 0.2) 0%, rgba(15, 23, 42, 0.95) 100%);"></div>
                
                <span class="badge position-absolute top-0 start-0 m-3 rounded-pill px-3 py-2 text-white shadow-lg d-inline-flex align-items-center gap-1" style="background: linear-gradient(135deg, #10b981, #059669); font-size: 0.85rem; z-index: 2;">
                    <i class="fa-solid fa-calendar-check"></i>
                    <span>ปฏิทินกิจกรรมจังหวัด</span>
                </span>
                
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 p-2 bg-dark bg-opacity-75 rounded-circle shadow" data-bs-dismiss="modal" aria-label="Close" style="z-index: 3;"></button>
            </div>

            <!-- Standard Header when no cover image -->
            <div id="sevStandardHeader" class="modal-header border-bottom border-secondary border-opacity-25 py-3 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 rounded-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #10b981, #059669); width: 42px; height: 42px;">
                        <i class="fa-solid fa-calendar-day fs-5"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold m-0 text-white">รายละเอียดกิจกรรมจังหวัดพัทลุง</h6>
                        <small class="text-success">Provincial Event Calendar Spotlight</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 p-md-5">
                <!-- Event Dates Badge Strip -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="badge bg-dark border border-success text-success px-3 py-2 rounded-pill fs-6 fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-clock text-warning animate-pulse"></i>
                        <span id="sevDateRangeText">วันที่จัดกิจกรรม</span>
                    </span>
                    <span class="badge bg-secondary bg-opacity-25 text-light px-3 py-2 rounded-pill small" id="sevCatText">ข่าวกิจกรรมจังหวัด</span>
                </div>

                <!-- Event Title -->
                <h3 id="sevTitleText" class="fw-bold text-white mb-4" style="line-height: 1.4;">
                    ชื่อกิจกรรม
                </h3>

                <!-- Location & Navigation Box -->
                <div id="sevLocationBox" class="p-3 rounded-4 mb-4 border d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 shadow-sm" style="background: rgba(255,255,255,0.04); border-color: rgba(56, 189, 248, 0.25) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                            <i class="fa-solid fa-location-dot fs-3 text-warning"></i>
                        </div>
                        <div>
                            <small class="text-white-50 fw-bold d-block">สถานที่ / จุดนัดหมาย:</small>
                            <span id="sevLocationText" class="text-white fs-6 fw-bold">ไม่ระบุสถานที่</span>
                        </div>
                    </div>
                    <a id="sevMapBtn" href="#" target="_blank" class="btn btn-sm btn-info text-dark rounded-pill px-4 py-2 fw-bold flex-shrink-0 shadow hover-scale d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #38bdf8, #0ea5e9); border: none;">
                        <i class="fa-solid fa-map-location-dot fs-6"></i>
                        <span>นำทางด้วย Google Maps</span>
                    </a>
                </div>

                <!-- Summary / Content Excerpt -->
                <div id="sevSummaryBox" class="p-4 rounded-4 mb-4 border text-light" style="background: rgba(0,0,0,0.25); border-color: rgba(255,255,255,0.08) !important; font-size: 1.02rem; line-height: 1.7;">
                </div>

                <!-- Footer CTA Buttons -->
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 pt-3 border-top border-secondary border-opacity-25">
                    <div class="d-flex align-items-center gap-2">
                        <?php if (session()->get('isLoggedIn')): ?>
                            <button id="sevEditBtn" type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>แก้ไขกิจกรรมนี้ (ผ่านระบบข่าว)</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-end">
                        <button type="button" class="btn btn-outline-secondary text-light rounded-pill px-4 py-2" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                        <a id="sevReadMoreBtn" href="#" class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow hover-scale d-flex align-items-center gap-2" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
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

    init: function() {
        var el = document.getElementById('smartEventViewerModal');
        if (el && typeof bootstrap !== 'undefined') {
            this.modal = new bootstrap.Modal(el);
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
    }
};

document.addEventListener('DOMContentLoaded', function() {
    SmartEventViewer.init();
});
</script>

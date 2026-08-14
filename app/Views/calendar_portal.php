<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$isLoggedIn = session()->get('isLoggedIn');
$allEvents = $events ?? [];
?>

<style>
/* Custom Interactive Calendar Styling */
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

.calendar-header-day {
    background: linear-gradient(180deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.95));
    color: #38bdf8;
    font-weight: 700;
    text-align: center;
    padding: 14px 8px;
    font-size: 1.05rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.calendar-cell {
    min-height: 135px;
    background: rgba(15, 23, 42, 0.6);
    padding: 8px;
    position: relative;
    transition: background 0.2s ease, transform 0.1s ease;
    border-right: 1px solid rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.calendar-cell:hover {
    background: rgba(30, 41, 59, 0.85);
}

.calendar-cell.other-month {
    background: rgba(9, 13, 22, 0.45);
    opacity: 0.5;
}

.calendar-cell.today {
    background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.25), rgba(15, 23, 42, 0.75));
    border: 1px solid #10b981 !important;
}

.day-number {
    font-size: 1.15rem;
    font-weight: 700;
    color: #f8fafc;
    display: inline-block;
    margin-bottom: 6px;
}

.today .day-number {
    background: #10b981;
    color: #0f172a;
    padding: 2px 10px;
    border-radius: 99px;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.6);
}

.event-badge-pill {
    display: block;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.35));
    border: 1px solid rgba(16, 185, 129, 0.5);
    color: #a7f3d0;
    padding: 6px 8px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    margin-bottom: 5px;
    cursor: pointer;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 6px rgba(0,0,0,0.25);
}

.event-badge-pill:hover {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(16, 185, 129, 0.45);
}

.agenda-card {
    background: rgba(30, 41, 59, 0.65);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(16px);
}

.agenda-card:hover {
    transform: translateY(-4px);
    border-color: rgba(56, 189, 248, 0.6);
    box-shadow: 0 15px 35px rgba(14, 165, 233, 0.2);
}
</style>

<div class="container-fluid py-5 px-lg-5" style="min-height: 85vh; background: radial-gradient(circle at top center, #1e293b 0%, #090d16 100%);">
    
    <!-- Hero Banner & Navigation header -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าแรก</a></li>
                    <li class="breadcrumb-item active text-light opacity-75" aria-current="page">ปฏิทินกิจกรรมและตารางงานจังหวัดพัทลุง</li>
                </ol>
            </nav>
            <h1 class="display-5 fw-bold text-white mb-2 d-flex align-items-center gap-3">
                <span class="p-3 rounded-4 bg-success bg-opacity-10 text-success border border-success border-opacity-25 shadow">
                    <i class="fa-solid fa-calendar-days text-warning animate-bounce"></i>
                </span>
                <span>ปฏิทินกิจกรรมจังหวัดพัทลุง</span>
            </h1>
            <p class="lead text-light opacity-75 m-0">
                รวมลานกิจกรรม งานประเพณี วาระสำคัญของจังหวัด และตารางปฏิบัติงานเชิงรุก พร้อมระบบนำทางพิกัด GPS อัจฉริยะ
            </p>
        </div>
        
        <!-- Officer On-Page Create Action -->
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
            <?php if ($isLoggedIn): ?>
                <button onclick="openNewEventStudio()" class="btn btn-warning text-dark fw-bold btn-lg rounded-pill px-4 py-3 shadow-lg hover-scale d-inline-flex align-items-center gap-2 border-2">
                    <i class="fa-solid fa-circle-plus fs-4 text-danger"></i>
                    <span>+ เพิ่มกิจกรรม / งานสำคัญใหม่</span>
                </button>
            <?php else: ?>
                <a href="<?= base_url('news') ?>" class="btn btn-outline-info rounded-pill px-4 py-2 fw-bold">
                    <i class="fa-solid fa-newspaper me-1"></i> ไปที่หน้าศูนย์ข่าวสารทั้งหมด
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Interactive Controls Toolbar -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4 p-3 rounded-4 shadow-lg border" style="background: rgba(30, 41, 59, 0.85); border-color: rgba(255,255,255,0.15) !important;">
        <!-- Month / Year Selector Buttons -->
        <div class="d-flex align-items-center gap-2">
            <button onclick="CalendarPortal.prevMonth()" class="btn btn-outline-info rounded-circle p-0 d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px;" title="เดือนก่อนหน้า">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="px-3 text-center" style="min-width: 240px;">
                <h4 id="calendarMonthTitle" class="fw-bold text-white m-0 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-calendar-alt text-warning"></i>
                    <span>กำลังโหลด...</span>
                </h4>
            </div>
            <button onclick="CalendarPortal.nextMonth()" class="btn btn-outline-info rounded-circle p-0 d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 44px; height: 44px;" title="เดือนถัดไป">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
            <button onclick="CalendarPortal.goToToday()" class="btn btn-success rounded-pill px-3 py-2 fw-bold ms-2 shadow-sm d-none d-sm-inline-block">
                <i class="fa-solid fa-location-crosshairs me-1"></i> เดือนปัจจุบัน
            </button>
        </div>

        <!-- View Mode Switch (Grid vs Agenda) -->
        <div class="btn-group p-1 bg-dark rounded-pill border border-secondary border-opacity-50" role="group">
            <button id="btnViewGrid" type="button" onclick="CalendarPortal.switchView('grid')" class="btn btn-primary rounded-pill px-4 py-2 fw-bold active">
                <i class="fa-solid fa-table-cells me-2"></i>มุมมองตารางปฏิทิน
            </button>
            <button id="btnViewAgenda" type="button" onclick="CalendarPortal.switchView('agenda')" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold border-0">
                <i class="fa-solid fa-list-ul me-2"></i>รายการเรียงตามวันที่
            </button>
        </div>
    </div>

    <!-- 1. GRID CALENDAR VIEW -->
    <div id="viewGridContainer" class="mb-5">
        <div class="calendar-grid">
            <!-- Day of Week Headers -->
            <div class="calendar-header-day text-danger">อาทิตย์</div>
            <div class="calendar-header-day">จันทร์</div>
            <div class="calendar-header-day">อังคาร</div>
            <div class="calendar-header-day">พุธ</div>
            <div class="calendar-header-day">พฤหัสบดี</div>
            <div class="calendar-header-day">ศุกร์</div>
            <div class="calendar-header-day text-info">เสาร์</div>

            <!-- Calendar Dynamic Days injection target -->
            <div id="calendarDaysGrid" class="col-12" style="display: contents;"></div>
        </div>
    </div>

    <!-- 2. AGENDA LIST VIEW -->
    <div id="viewAgendaContainer" class="mb-5 d-none">
        <div id="agendaListContent" class="row g-4">
            <!-- Dynamically populated by JavaScript -->
        </div>
    </div>

    <!-- Guidance footer card -->
    <div class="p-4 rounded-4 text-white shadow-lg border" style="background: rgba(15, 23, 42, 0.7); border-color: rgba(16, 185, 129, 0.3) !important;">
        <div class="row align-items-center">
            <div class="col-md-8 d-flex align-items-center gap-3">
                <div class="p-3 rounded-circle bg-success bg-opacity-20 text-success fs-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-circle-question text-warning"></i>
                </div>
                <div>
                    <h6 class="fw-bold m-0 fs-5 text-success">คำแนะนำสำหรับประชาชนและหน่วยงาน</h6>
                    <p class="text-light opacity-75 m-0 small">ท่านสามารถคลิกที่ป้ายชื่อกิจกรรมบนตารางปฏิทินเพื่อดูรายละเอียด สถานที่จัดงาน พร้อมเปิดใช้ฟีเจอร์ "นำทางด้วยแผนที่ (Google Maps Navigation)" ได้ทันที</p>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-dark border border-secondary text-info px-3 py-2 rounded-pill small">
                    <i class="fa-solid fa-rotate me-1"></i> เชื่อมโยงฐานข้อมูลจากระบบข่าวสาธารณะ
                </span>
            </div>
        </div>
    </div>

</div>

<script>
// Pass Php Events straight to JavaScript client-side memory
var PROVINCE_EVENTS = <?= json_encode($allEvents) ?>;

var CalendarPortal = {
    currentDate: new Date(), // Defaults to current client browser month or year 2026 if set
    viewMode: 'grid',
    events: [],

    init: function() {
        // Parse and register events
        this.events = Array.isArray(PROVINCE_EVENTS) ? PROVINCE_EVENTS : [];
        if (typeof SmartEventViewer !== 'undefined') {
            SmartEventViewer.registerEvents(this.events);
        }

        // If today is before 2026 and we want August 2026 by default to showcase sample events gracefully:
        var sampleMonthYear = new Date(2026, 7, 1); // Month 7 is August (0-indexed)
        if (this.currentDate.getFullYear() < 2026) {
            this.currentDate = sampleMonthYear;
        }

        this.render();
    },

    prevMonth: function() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
    },

    nextMonth: function() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
    },

    goToToday: function() {
        this.currentDate = new Date();
        if (this.currentDate.getFullYear() < 2026) {
            this.currentDate = new Date(2026, 7, 5); // Aug 5, 2026 sample today
        }
        this.render();
    },

    switchView: function(mode) {
        this.viewMode = mode;
        var gridEl = document.getElementById('viewGridContainer');
        var agendaEl = document.getElementById('viewAgendaContainer');
        var btnGrid = document.getElementById('btnViewGrid');
        var btnAgenda = document.getElementById('btnViewAgenda');

        if (mode === 'grid') {
            gridEl.classList.remove('d-none');
            agendaEl.classList.add('d-none');
            btnGrid.className = 'btn btn-primary rounded-pill px-4 py-2 fw-bold active';
            btnAgenda.className = 'btn btn-outline-light rounded-pill px-4 py-2 fw-bold border-0';
        } else {
            gridEl.classList.add('d-none');
            agendaEl.classList.remove('d-none');
            btnAgenda.className = 'btn btn-primary rounded-pill px-4 py-2 fw-bold active';
            btnGrid.className = 'btn btn-outline-light rounded-pill px-4 py-2 fw-bold border-0';
        }
        this.render();
    },

    render: function() {
        var year = this.currentDate.getFullYear();
        var month = this.currentDate.getMonth(); // 0 - 11

        // Update title
        var thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];
        var titleEl = document.getElementById('calendarMonthTitle');
        if (titleEl) {
            titleEl.innerHTML = `<i class="fa-solid fa-calendar-alt text-warning me-2"></i> ${thaiMonths[month]} ${year + 543}`;
        }

        if (this.viewMode === 'grid') {
            this.renderGrid(year, month);
        } else {
            this.renderAgenda(year, month);
        }
    },

    renderGrid: function(year, month) {
        var gridEl = document.getElementById('calendarDaysGrid');
        if (!gridEl) return;

        var firstDayIndex = new Date(year, month, 1).getDay(); // 0 is Sun
        var daysInMonth = new Date(year, month + 1, 0).getDate();
        var daysInPrevMonth = new Date(year, month, 0).getDate();

        var totalCells = Math.ceil((firstDayIndex + daysInMonth) / 7) * 7;
        var html = '';

        var realToday = new Date();
        // If testing year 2026, let's treat 2026-08-05 as Today for visual highlight if matching
        var todayStr = realToday.toISOString().split('T')[0];
        if (realToday.getFullYear() < 2026 && year === 2026 && month === 7) {
            todayStr = '2026-08-05';
        }

        for (var i = 0; i < totalCells; i++) {
            var cellDay = 0;
            var cellMonth = month;
            var cellYear = year;
            var isOtherMonth = false;

            if (i < firstDayIndex) {
                cellDay = daysInPrevMonth - (firstDayIndex - i) + 1;
                cellMonth = month - 1;
                if (cellMonth < 0) { cellMonth = 11; cellYear = year - 1; }
                isOtherMonth = true;
            } else if (i >= firstDayIndex + daysInMonth) {
                cellDay = i - (firstDayIndex + daysInMonth) + 1;
                cellMonth = month + 1;
                if (cellMonth > 11) { cellMonth = 0; cellYear = year + 1; }
                isOtherMonth = true;
            } else {
                cellDay = i - firstDayIndex + 1;
            }

            var cellDateStr = cellYear + '-' + String(cellMonth + 1).padStart(2, '0') + '-' + String(cellDay).padStart(2, '0');
            var isToday = (!isOtherMonth && cellDateStr === todayStr);

            var cellClass = 'calendar-cell' + (isOtherMonth ? ' other-month' : '') + (isToday ? ' today' : '');

            // Find events for this date
            var dayEvents = this.getEventsForDate(cellDateStr);

            html += `<div class="${cellClass}">`;
            html += `<span class="day-number">${cellDay}</span>`;
            
            // Events list inside cell
            if (dayEvents.length > 0) {
                dayEvents.forEach(function(ev) {
                    var ico = 'fa-solid fa-flag-checkered text-warning';
                    if (ev.category && ev.category.indexOf('ท่องเที่ยว') !== -1) ico = 'fa-solid fa-camera-retro text-info';
                    if (ev.category && ev.category.indexOf('ราชการ') !== -1) ico = 'fa-solid fa-bullhorn text-danger';

                    html += `<div class="event-badge-pill" onclick="SmartEventViewer.open('${ev.id}')" title="${ev.title}">`;
                    html += `<i class="${ico} me-1"></i> ${ev.title}`;
                    html += `</div>`;
                });
            }

            html += `</div>`;
        }

        gridEl.innerHTML = html;
    },

    renderAgenda: function(year, month) {
        var agendaEl = document.getElementById('agendaListContent');
        if (!agendaEl) return;

        // Filter events for current month (or show all upcoming if none in month)
        var monthStrPrefix = year + '-' + String(month + 1).padStart(2, '0');
        var matchingEvents = this.events.filter(function(ev) {
            var s = ev.event_start_date || '';
            var e = ev.event_end_date || '';
            return s.startsWith(monthStrPrefix) || e.startsWith(monthStrPrefix) || (s <= monthStrPrefix && e >= monthStrPrefix);
        });

        if (matchingEvents.length === 0) {
            agendaEl.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="p-5 rounded-4 border border-secondary border-opacity-25" style="background: rgba(15, 23, 42, 0.5);">
                        <i class="fa-regular fa-calendar-xmark text-white-50 display-3 mb-3"></i>
                        <h4 class="text-white fw-bold">ไม่พบตารางกิจกรรมในเดือนนี้</h4>
                        <p class="text-white-50 m-0">กรุณาลองเปลี่ยนปฏิทินไปยังเดือนอื่น หรือเลือกมุมมองตารางปฏิทิน</p>
                    </div>
                </div>
            `;
            return;
        }

        var html = '';
        matchingEvents.forEach(function(ev) {
            var sDate = ev.event_start_date ? SmartEventViewer.formatThaiDate(ev.event_start_date) : 'ระบุตามประกาศ';
            var loc = ev.event_location || 'ไม่ระบุสถานที่';
            var cov = ev.cover_image && ev.cover_image !== 'assets/images/slider/sane_muanglung.png' ? (ev.cover_image.startsWith('http') ? ev.cover_image : "<?= base_url() ?>/" + ev.cover_image.replace(/^\//, '')) : null;

            html += `
                <div class="col-lg-6 col-xl-4">
                    <div class="agenda-card h-100 p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-50 px-3 py-2 rounded-pill fw-bold">
                                    <i class="fa-solid fa-clock me-1 text-warning"></i> ${sDate}
                                </span>
                                <span class="badge bg-secondary bg-opacity-25 text-light small">${ev.category || 'ข่าวกิจกรรม'}</span>
                            </div>
                            
                            <?php // Optional thumbnail in agenda view ?>
                            ${cov ? `<img src="${cov}" class="w-100 rounded-3 mb-3 shadow-sm object-fit-cover" style="height: 160px; object-fit: cover;">` : ''}

                            <h4 class="fw-bold text-white mb-3" style="line-height: 1.4; cursor: pointer;" onclick="SmartEventViewer.open('${ev.id}')">
                                ${ev.title}
                            </h4>
                            <p class="text-light opacity-75 small mb-4 line-clamp-3">
                                ${ev.summary || (ev.content ? ev.content.replace(/<[^>]*>?/gm, '').substring(0, 140) + '...' : '')}
                            </p>
                        </div>

                        <div class="pt-3 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                            <span class="text-info small fw-bold text-truncate me-2" style="max-width: 60%;" title="${loc}">
                                <i class="fa-solid fa-location-dot text-warning me-1"></i> ${loc}
                            </span>
                            <button onclick="SmartEventViewer.open('${ev.id}')" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 py-1 flex-shrink-0">
                                <span>ดูรายละเอียด</span> <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        agendaEl.innerHTML = html;
    },

    getEventsForDate: function(dateStr) {
        return this.events.filter(function(ev) {
            var start = ev.event_start_date || '';
            var end = ev.event_end_date || start;
            if (!start) return false;
            return (dateStr >= start && dateStr <= end);
        });
    }
};

// Helper function for logged-in officers to open NewsStudio and preset as Event!
function openNewEventStudio() {
    if (typeof NewsStudio !== 'undefined') {
        NewsStudio.open();
        setTimeout(function() {
            var chk = document.getElementById('studioIsEvent');
            if (chk && !chk.checked) {
                chk.checked = true;
                NewsStudio.toggleEventPanel(true);
            }
        }, 300);
    } else {
        alert('กรุณาดำเนินการผ่านหน้าแรกหรือระบบข่าวสาร');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    CalendarPortal.init();
});
</script>

<?= $this->endSection() ?>

<?php
    $alertData = function_exists('get_emergency_alert') ? get_emergency_alert() : [];
    if (empty($alertData)) {
        $alertData = [
            'is_active' => true,
            'level' => 'orange',
            'headline' => '🌧️ [แจ้งเตือนเฝ้าระวัง] มรสุมกำลังแรง พัทลุงฝนตกหนักถึงหนักมาก เฝ้าระวังน้ำท่วมฉับพลันและน้ำป่าไหลหลากในพื้นที่เสี่ยงภัย',
            'details' => "กองอำนวยการป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง (กอปภ.จ.พัทลุง) ประกาศแจ้งเตือนประชาชนในพื้นที่เสี่ยงภัย ให้ระมัดระวังอันตรายจากฝนตกหนักถึงหนักมากและปริมาณฝนสะสม ซึ่งอาจทำให้เกิดน้ำท่วมฉับพลัน น้ำป่าไหลหลาก และดินโคลนถล่ม",
            'affected_areas' => 'อำเภอเมืองพัทลุง, อำเภอควนขนุน, อำเภอศรีบรรพต, อำเภอกงหรา, อำเภอศรีนครินทร์',
            'weather_temp' => '25.8°C',
            'weather_cond' => 'ฝนตกหนัก 80% ของพื้นที่',
            'pm25_val' => '5.5 µg/m³ (อากาศดีมาก)'
        ];
    }
    $isOfficer = $isOfficer ?? session()->get('isLoggedIn');

    // Severity color configuration - Refined Civic Palette (มากแต่น้อย นุ่มนวล ไม่แสบตา)
    $level = $alertData['level'] ?? 'orange';
    $bgStyle = 'linear-gradient(90deg, #065f46 0%, #047857 50%, #059669 100%)';
    $badgeText = 'สถานการณ์ปกติ';
    $badgeBg = 'rgba(6, 95, 70, 0.85)';
    $icon = 'fa-sun text-warning';
    $pulseClass = '';

    if ($level === 'red') {
        $bgStyle = 'linear-gradient(90deg, #7f1d1d 0%, #991b1b 45%, #b91c1c 100%)';
        $badgeText = 'วิกฤตฉุกเฉิน';
        $badgeBg = 'rgba(127, 29, 29, 0.9)';
        $icon = 'fa-triangle-exclamation text-warning';
        $pulseClass = 'alert-pulse-red';
    } elseif ($level === 'orange') {
        $bgStyle = 'linear-gradient(90deg, #9a3412 0%, #c2410c 45%, #ea580c 100%)';
        $badgeText = 'เฝ้าระวังฝนตกหนัก';
        $badgeBg = 'rgba(124, 45, 18, 0.9)';
        $icon = 'fa-cloud-showers-heavy text-warning';
        $pulseClass = 'alert-pulse-orange';
    } elseif ($level === 'yellow') {
        $bgStyle = 'linear-gradient(90deg, #854d0e 0%, #a16207 50%, #ca8a04 100%)';
        $badgeText = 'แจ้งเตือนมรสุม';
        $badgeBg = 'rgba(113, 63, 18, 0.9)';
        $icon = 'fa-cloud-sun-rain text-white';
    }
?>

<style>
/* ==========================================================================
   MINIMALIST HIGH-IMPACT EMERGENCY ALERT BAR (มากแต่น้อย)
   ========================================================================== */
.emergency-banner-bar {
    color: #ffffff;
    padding: 6px 0;
    font-size: 0.86rem;
    position: relative;
    z-index: 1030;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.18);
    font-family: 'Prompt', -apple-system, BlinkMacSystemFont, sans-serif;
}
.alert-pulse-red {
    animation: bgFlashRed 3s infinite alternate;
}
@keyframes bgFlashRed {
    0% { filter: brightness(0.98); }
    100% { filter: brightness(1.12); }
}
.alert-pulse-orange {
    animation: bgPulseOrange 3.5s infinite alternate;
}
@keyframes bgPulseOrange {
    0% { filter: brightness(0.97); }
    100% { filter: brightness(1.08); }
}

.em-status-pill {
    padding: 3px 10px;
    border-radius: 9999px;
    font-size: 0.78rem;
    font-weight: 700;
    border: 1px solid rgba(255, 255, 255, 0.35);
    white-space: nowrap;
    letter-spacing: 0.2px;
    transition: all 0.2s ease;
}
.em-status-pill:hover {
    background: rgba(0,0,0,0.3) !important;
    border-color: rgba(255,255,255,0.6);
}

.em-pulse-dot {
    width: 7px;
    height: 7px;
    background-color: #facc15;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 rgba(250, 204, 21, 0.7);
    animation: emPulse 2s infinite;
}
@keyframes emPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(255, 255, 255, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
}

.marquee-container {
    display: flex;
    align-items: center;
    overflow: hidden;
    white-space: nowrap;
    width: 100%;
}
.marquee-text {
    display: inline-block;
    padding-left: 100%;
    animation: scrollMarquee 42s linear infinite;
    font-weight: 500;
    font-size: 0.88rem;
    letter-spacing: 0.2px;
}
.marquee-text:hover {
    animation-play-state: paused;
}
@keyframes scrollMarquee {
    0% { transform: translate3d(0, 0, 0); }
    100% { transform: translate3d(-100%, 0, 0); }
}

.em-btn-glass {
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.35);
    color: #ffffff !important;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 3px 11px;
    border-radius: 9999px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.em-btn-glass:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.6);
    transform: translateY(-1px);
    color: #ffffff !important;
}

.em-btn-white {
    background: #ffffff;
    color: #0f172a !important;
    border: none;
    font-weight: 700;
    font-size: 0.78rem;
    padding: 3px 12px;
    border-radius: 9999px;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(0,0,0,0.12);
}
.em-btn-white:hover {
    background: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.18);
    color: #0284c7 !important;
}

.em-btn-icon {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.74rem;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0;
    text-decoration: none;
}
.em-btn-icon:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #ffffff;
    transform: scale(1.08);
}

.emergency-call-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    transition: all 0.25s;
    text-decoration: none;
    color: #1e293b;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}
.emergency-call-card:hover {
    transform: translateY(-4px);
    border-color: #ef4444;
    box-shadow: 0 10px 25px rgba(239, 68, 68, 0.15);
}

[data-theme="dark"] .emergency-call-card {
    background: #0f172a;
    border-color: #334155;
    color: #f8fafc;
}
</style>

<!-- EMERGENCY & WEATHER SLENDER ALERT BAR (1 บรรทัด สวยงาม สบายตา) -->
<?php if (!empty($alertData['is_active'])): ?>
<div class="emergency-banner-bar <?= $pulseClass ?>" id="emergencyTopBar" style="background: <?= $bgStyle ?>;">
    <div class="container-fluid px-3 px-lg-4">
        <div class="d-flex align-items-center justify-content-between gap-2 gap-lg-3 flex-nowrap" style="min-height: 32px;">
            
            <!-- 1. Left Pill: Compact Severity + Live Temp (คลิกเพื่อดูรายละเอียด) -->
            <div class="d-flex align-items-center gap-2 flex-shrink-0 cursor-pointer" onclick="EmergencySystem.openBroadcast()" title="คลิกดูรายงานสถานการณ์และสายด่วนฉุกเฉิน">
                <span class="em-status-pill shadow-xs d-inline-flex align-items-center gap-1.5" style="background: <?= $badgeBg ?>;">
                    <span class="em-pulse-dot"></span>
                    <i class="fa-solid <?= $icon ?>"></i>
                    <span class="d-none d-sm-inline"><?= $badgeText ?></span>
                    <span class="opacity-40 d-none d-sm-inline">|</span>
                    <span class="fw-bold text-warning"><?= esc($alertData['weather_temp'] ?? '25.8°C') ?></span>
                </span>
            </div>

            <!-- 2. Middle: Marquee Ticker (วิ่งนุ่มนวล สบายตา) -->
            <div class="marquee-container flex-grow-1 overflow-hidden cursor-pointer" onclick="EmergencySystem.openBroadcast()" title="คลิกดูรายละเอียดทั้งหมด">
                <span class="marquee-text">
                    📢 <?= esc($alertData['headline'] ?? 'มรสุมพาดผ่าน เฝ้าระวังฝนตกหนัก') ?> 
                    <?php if (!empty($alertData['affected_areas'])): ?>
                        <span class="ms-3 opacity-90 fw-normal"> | 📍 พื้นที่เฝ้าระวัง: <?= esc($alertData['affected_areas']) ?></span>
                    <?php endif; ?>
                </span>
            </div>

            <!-- 3. Right: Sleek Unified Action Group -->
            <div class="d-flex align-items-center gap-1.5 gap-md-2 flex-shrink-0">
                <!-- Direct River CCTV & Radar Link (Glassmorphism Pill) -->
                <a href="https://ddpmpt.rescuephatthalung.com/#forecast" target="_blank" rel="noopener noreferrer" class="em-btn-glass text-decoration-none d-inline-flex align-items-center gap-1.5" title="เปิดดูภาพสดกล้องริมคลอง & เรดาร์ฝน ปภ.พัทลุง">
                    <i class="fa-solid fa-video text-warning"></i>
                    <span class="d-none d-md-inline">กล้องคลอง & เรดาร์ฝน</span>
                    <i class="fa-solid fa-arrow-up-right-from-square small opacity-75"></i>
                </a>

                <!-- Details & Hotlines Modal Trigger (Clean White Pill) -->
                <button type="button" onclick="EmergencySystem.openBroadcast()" class="em-btn-white d-inline-flex align-items-center gap-1.5" title="ดูรายละเอียดประกาศ คำแนะนำประชาชน และสายด่วน">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                    <span>รายละเอียด & สายด่วน</span>
                </button>

                <!-- Admin discrete gear icon (only for logged-in officers) -->
                <?php if ($isOfficer): ?>
                <a href="<?= base_url('admin/emergency') ?>" class="em-btn-icon" title="จัดการระบบเตือนภัยหลังบ้าน">
                    <i class="fa-solid fa-gear"></i>
                </a>
                <?php endif; ?>

                <!-- Dismiss X Button -->
                <button type="button" class="em-btn-icon" onclick="dismissEmergencyBar()" title="ปิดแถบแจ้งเตือนชั่วคราว" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>

<!-- MODAL: EMERGENCY BROADCAST & HOTLINES DIRECTORY CENTER (มากในรายละเอียดเมื่อคลิก) -->
<div class="modal fade" id="emergencyBroadcastModal" tabindex="-1" aria-labelledby="emergencyBroadcastModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0 overflow-hidden">
            <div class="modal-header px-4 py-3 text-white" style="background: <?= $bgStyle ?>;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-circle bg-white bg-opacity-20 text-white d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid <?= $icon ?> fs-4"></i>
                    </div>
                    <div>
                        <span class="badge bg-dark bg-opacity-30 text-white rounded-pill px-2.5 py-0.5 mb-1" style="font-size: 0.72rem;">
                            กอปภ.จ.พัทลุง • ประกาศเตือนภัยฉุกเฉิน
                        </span>
                        <h5 class="modal-title fw-bold mb-0" id="emergencyBroadcastModalLabel">
                            <?= esc($alertData['headline'] ?? 'แจ้งเตือนสถานการณ์สภาพอากาศและภัยพิบัติ') ?>
                        </h5>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" style="background: #f8fafc;">
                <!-- Weather & Telemetry Live Matrix -->
                <div class="p-3.5 mb-4 rounded-4 bg-white border shadow-sm">
                    <div class="row g-3 text-center text-md-start">
                        <div class="col-6 col-md-3 border-end">
                            <span class="text-muted small d-block mb-1"><i class="fa-solid fa-temperature-half text-warning me-1"></i> อุณหภูมิสด:</span>
                            <span class="fs-5 fw-bold text-dark"><?= esc($alertData['weather_temp'] ?? '25.8°C') ?></span>
                        </div>
                        <div class="col-6 col-md-3 border-end">
                            <span class="text-muted small d-block mb-1"><i class="fa-solid fa-cloud-showers-heavy text-primary me-1"></i> สภาพฝน:</span>
                            <span class="fs-6 fw-bold text-dark"><?= esc($alertData['weather_cond'] ?? 'ฝนตกหนัก') ?></span>
                        </div>
                        <div class="col-6 col-md-3 border-end">
                            <span class="text-muted small d-block mb-1"><i class="fa-solid fa-wind text-info me-1"></i> ดัชนีฝุ่น PM 2.5:</span>
                            <span class="fs-5 fw-bold text-success"><?= esc($alertData['pm25_val'] ?? '5.5 µg/m³') ?></span>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <span class="text-muted small d-block mb-1">ระดับสถานการณ์:</span>
                            <span class="badge py-1.5 px-3 fw-bold rounded-pill text-white shadow-xs" style="background: <?= $bgStyle ?>;">
                                <i class="fa-solid fa-shield-halved me-1"></i> <?= $badgeText ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Live DDPM Cameras & Radar Featured Card -->
                <div class="p-3.5 mb-4 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-circle bg-danger bg-opacity-25 text-danger d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; flex-shrink: 0;">
                                <i class="fa-solid fa-video fs-4 animate-pulse"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-danger text-white rounded-pill px-2.5 py-0.5 fw-bold" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-circle-dot me-1"></i> REAL-TIME CCTV
                                    </span>
                                    <span class="badge bg-light text-dark rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.72rem;">
                                        กอปภ.จ.พัทลุง
                                    </span>
                                </div>
                                <h6 class="fw-bold mb-0 text-white">กล้องสดริมคลอง & เรดาร์พยากรณ์ฝนพัทลุง</h6>
                                <small class="text-white-50">สำนักงาน ปภ. จังหวัดพัทลุง ร่วมกับประชาชน จิตอาสา และกู้ภัยพัทลุง</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a href="<?= base_url('weather-radar') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-tv"></i> ดูผ่านเว็บจังหวัด
                            </a>
                            <a href="https://ddpmpt.rescuephatthalung.com/#forecast" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-danger fw-bold rounded-pill px-3.5 py-2 shadow-sm d-inline-flex align-items-center gap-1.5 hover-scale" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none;">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                <span>เปิดดูภาพสด & เรดาร์ฝน</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Announcement Details -->
                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-bullhorn text-danger me-2"></i>ข้อความสั่งการและคำแนะนำสำหรับประชาชน:</h6>
                <div class="p-4 rounded-4 bg-white border shadow-sm mb-4 text-dark" style="line-height: 1.7; font-size: 0.95rem;">
                    <?= nl2br(esc($alertData['details'] ?? "สถานการณ์โดยรวมปกติ ขอความร่วมมือประชาชนรักษาสุขภาพ และอัปเดตข่าวสารจากทางราชการอย่างต่อเนื่อง")) ?>
                    
                    <?php if (!empty($alertData['affected_areas'])): ?>
                    <hr class="my-3 text-muted">
                    <div class="d-flex align-items-center gap-2 flex-wrap text-muted small">
                        <strong><i class="fa-solid fa-location-dot text-danger me-1"></i> พื้นที่หรืออำเภอที่ต้องเฝ้าระวังเป็นพิเศษ:</strong>
                        <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-3 py-1 rounded-pill"><?= esc($alertData['affected_areas']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Emergency Hotlines Direct Call Matrix -->
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-phone-volume text-primary me-2"></i>สายด่วนแจ้งเหตุฉุกเฉินและกู้ชีพ (คลิกเพื่อโทรออกทันที):</h6>
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:1784" class="emergency-call-card">
                            <div>
                                <span class="badge bg-danger text-white rounded-pill mb-2">สายด่วน 1784</span>
                                <h6 class="fw-bold mb-1">ปภ. จังหวัดพัทลุง</h6>
                                <span class="text-muted small">แจ้งภัยพิบัติ น้ำท่วม ดินถล่ม</span>
                            </div>
                            <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger fs-4"><i class="fa-solid fa-phone"></i></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:1669" class="emergency-call-card">
                            <div>
                                <span class="badge bg-success text-white rounded-pill mb-2">สายด่วน 1669</span>
                                <h6 class="fw-bold mb-1">กู้ชีพฉุกเฉิน / นพรัตน์</h6>
                                <span class="text-muted small">รถพยาบาล อุบัติเหตุร้ายแรง</span>
                            </div>
                            <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success fs-4"><i class="fa-solid fa-truck-medical"></i></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:199" class="emergency-call-card">
                            <div>
                                <span class="badge bg-warning text-dark rounded-pill mb-2">สายด่วน 199</span>
                                <h6 class="fw-bold mb-1">สถานีดับเพลิงเทศบาล</h6>
                                <span class="text-muted small">อัคคีภัย สัตว์มีพิษเข้าบ้าน</span>
                            </div>
                            <div class="p-3 rounded-circle bg-warning bg-opacity-25 text-warning fs-4"><i class="fa-solid fa-fire-extinguisher"></i></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:191" class="emergency-call-card">
                            <div>
                                <span class="badge bg-info text-dark rounded-pill mb-2">สายด่วน 191</span>
                                <h6 class="fw-bold mb-1">ตำรวจภูธรจังหวัด</h6>
                                <span class="text-muted small">เหตุด่วน ร้ายแรง ความปลอดภัย</span>
                            </div>
                            <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info fs-4"><i class="fa-solid fa-shield-halved"></i></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:1129" class="emergency-call-card">
                            <div>
                                <span class="badge bg-secondary text-white rounded-pill mb-2">สายด่วน 1129</span>
                                <h6 class="fw-bold mb-1">การไฟฟ้า กฟภ. พัทลุง</h6>
                                <span class="text-muted small">ไฟฟ้าดับ เสาไฟฟ้าโค่นล้ม</span>
                            </div>
                            <div class="p-3 rounded-circle bg-secondary bg-opacity-10 text-secondary fs-4"><i class="fa-solid fa-bolt"></i></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:1567" class="emergency-call-card">
                            <div>
                                <span class="badge bg-primary text-white rounded-pill mb-2">สายด่วน 1567</span>
                                <h6 class="fw-bold mb-1">ศูนย์ดำรงธรรมจังหวัด</h6>
                                <span class="text-muted small">รับเรื่องร้องทุกข์ ขอความช่วยเหลือ</span>
                            </div>
                            <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary fs-4"><i class="fa-solid fa-headset"></i></div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 justify-content-between">
                <div class="small text-muted"><i class="fa-solid fa-circle-info me-1"></i> กองอำนวยการป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง (กอปภ.จ.พัทลุง)</div>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<script>
const EmergencySystem = {
    broadcastModal: null,

    openBroadcast: function() {
        const el = document.getElementById('emergencyBroadcastModal');
        if (el && typeof bootstrap !== 'undefined') {
            if (el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
            if (!this.broadcastModal) this.broadcastModal = new bootstrap.Modal(el);
            this.broadcastModal.show();
        }
    }
};

function dismissEmergencyBar() {
    const el = document.getElementById('emergencyTopBar');
    if (el) {
        el.style.transition = 'all 0.3s ease';
        el.style.transform = 'translateY(-100%)';
        el.style.opacity = '0';
        setTimeout(() => { el.style.display = 'none'; }, 300);
        try {
            sessionStorage.setItem('dismiss_em_alert', 'true');
        } catch(e) {}
    }
}

document.addEventListener('DOMContentLoaded', () => {
    try {
        if (sessionStorage.getItem('dismiss_em_alert') === 'true') {
            const el = document.getElementById('emergencyTopBar');
            if (el) el.style.display = 'none';
        }
    } catch(e) {}
});
</script>

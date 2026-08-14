<?php
    $alertData = function_exists('get_emergency_alert') ? get_emergency_alert() : [
        'is_active' => true,
        'level' => 'orange',
        'headline' => '🟠 [แจ้งเตือนเฝ้าระวังภัย] มรสุมตะวันตกเฉียงใต้ เฝ้าระวังฝนตกหนักและน้ำท่วมขังในลุ่มน้ำบางแห่ง',
        'details' => "กองอำนวยการป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง (ปภ.) ขอเตือนประชาชนในพื้นที่เสี่ยง เตรียมความพร้อมรองรับปริมาณฝนสะสม",
        'affected_areas' => 'อำเภอเมืองพัทลุง, อำเภอควนขนุน, อำเภอศรีบรรพต',
        'weather_temp' => '27°C',
        'weather_cond' => 'ฝนฟ้าคะนอง 60% (ความชื้น 82%)',
        'pm25_val' => '14 µg/m³ (อากาศดีเยี่ยม)'
    ];
    $isOfficer = $isOfficer ?? session()->get('isLoggedIn');

    // Severity color configuration
    $level = $alertData['level'] ?? 'green';
    $bgStyle = 'linear-gradient(135deg, #047857 0%, #10b981 100%)'; // green default
    $badgeText = '🟢 สถานการณ์ปกติ / อากาศสด';
    $badgeBg = '#065f46';
    $icon = 'fa-cloud-sun text-warning';
    $pulseClass = '';

    if ($level === 'red') {
        $bgStyle = 'linear-gradient(135deg, #991b1b 0%, #dc2626 50%, #7f1d1d 100%)';
        $badgeText = '🔴 ภัยวิกฤตฉุกเฉินสูงสุด';
        $badgeBg = '#450a0a';
        $icon = 'fa-triangle-exclamation text-warning animate-bounce';
        $pulseClass = 'alert-pulse-red';
    } elseif ($level === 'orange') {
        $bgStyle = 'linear-gradient(135deg, #c2410c 0%, #ea580c 50%, #9a3412 100%)';
        $badgeText = '🟠 เตือนภัยระดับสูง (เฝ้าระวัง)';
        $badgeBg = '#7c2d12';
        $icon = 'fa-bolt text-warning animate-pulse';
        $pulseClass = 'alert-pulse-orange';
    } elseif ($level === 'yellow') {
        $bgStyle = 'linear-gradient(135deg, #a16207 0%, #ca8a04 100%)';
        $badgeText = '🟡 แจ้งเตือนและสภาพอากาศ';
        $badgeBg = '#713f12';
        $icon = 'fa-cloud-showers-heavy text-white';
    }
?>

<style>
/* ==========================================================================
   EMERGENCY & DISASTER EARLY WARNING MARQUEE BANNER
   ========================================================================== */
.emergency-banner-bar {
    color: #ffffff;
    padding: 8px 0;
    font-size: 0.92rem;
    position: relative;
    z-index: 1030;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}
.alert-pulse-red {
    animation: bgFlashRed 2s infinite alternate;
}
@keyframes bgFlashRed {
    0% { filter: brightness(1); }
    100% { filter: brightness(1.25); box-shadow: 0 0 25px rgba(220, 38, 38, 0.8); }
}
.alert-pulse-orange {
    animation: bgPulseOrange 3s infinite alternate;
}
@keyframes bgPulseOrange {
    0% { filter: brightness(0.95); }
    100% { filter: brightness(1.15); }
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
    animation: scrollMarquee 40s linear infinite;
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}
.marquee-text:hover {
    animation-play-state: paused;
}
@keyframes scrollMarquee {
    0% { transform: translate3d(0, 0, 0); }
    100% { transform: translate3d(-100%, 0, 0); }
}

.telemetry-badge {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 3px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
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

<!-- EMERGENCY & WEATHER TOP MARQUEE BANNER -->
<?php if (!empty($alertData['is_active'])): ?>
<div class="emergency-banner-bar <?= $pulseClass ?>" style="background: <?= $bgStyle ?>;">
    <div class="container-fluid px-3 px-md-4">
        <div class="row align-items-center g-2">
            <!-- Severity Level Badge -->
            <div class="col-12 col-md-3 col-xl-2 d-flex align-items-center justify-content-between justify-content-md-start gap-2">
                <span class="badge px-3 py-2 text-white shadow-sm fw-bold d-inline-flex align-items-center gap-2 rounded-pill" style="background: <?= $badgeBg ?>; border: 1px solid rgba(255,255,255,0.3); font-size: 0.82rem;">
                    <i class="fa-solid <?= $icon ?>"></i>
                    <span><?= $badgeText ?></span>
                </span>
                <?php if ($isOfficer): ?>
                <button type="button" onclick="EmergencySystem.openStudio()" class="btn btn-xs btn-warning text-dark fw-bold rounded-circle p-1 d-md-none" style="width:28px; height:28px;" title="ตั้งค่าเตือนภัย">
                    <i class="fa-solid fa-sliders"></i>
                </button>
                <?php endif; ?>
            </div>

            <!-- Ticker Headline & Weather Telemetry -->
            <div class="col-12 col-md-6 col-xl-7 d-flex align-items-center overflow-hidden">
                <div class="marquee-container flex-grow-1 me-3">
                    <span class="marquee-text cursor-pointer" onclick="EmergencySystem.openBroadcast()">
                        📢 <?= esc($alertData['headline'] ?? 'มรสุมพาดผ่าน เฝ้าระวังฝนตกหนัก') ?> 
                        <span class="ms-4 opacity-75"> | พื้นที่เฝ้าระวัง: <?= esc($alertData['affected_areas'] ?? 'ทุกอำเภอในจังหวัดพัทลุง') ?></span>
                    </span>
                </div>
                <!-- Weather Live Pills -->
                <div class="d-none d-xl-flex align-items-center gap-2 flex-shrink-0">
                    <span class="telemetry-badge" title="อุณหภูมิและสภาพอากาศสด"><i class="fa-solid fa-temperature-half text-warning"></i> <?= esc($alertData['weather_temp'] ?? '27°C') ?> • <?= esc($alertData['weather_cond'] ?? 'ฟ้าโปร่ง') ?></span>
                    <span class="telemetry-badge" title="ดัชนีคุณภาพอากาศ PM2.5"><i class="fa-solid fa-wind text-info"></i> PM2.5: <?= esc($alertData['pm25_val'] ?? '14 µg/m³') ?></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 col-md-3 col-xl-3 text-end d-flex align-items-center justify-content-end gap-2">
                <button type="button" onclick="EmergencySystem.openBroadcast()" class="btn btn-sm btn-light fw-bold text-dark rounded-pill px-3 shadow-sm flex-shrink-0 hover-scale">
                    <i class="fa-solid fa-bullhorn text-danger me-1"></i> รายละเอียด & สายด่วน
                </button>

                <?php if ($isOfficer): ?>
                <button type="button" onclick="EmergencySystem.openStudio()" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 shadow d-none d-md-inline-flex align-items-center gap-1 hover-scale" title="ปรับแต่งคำประกาศเตือนภัย (On-Page CMS Studio)">
                    <i class="fa-solid fa-gear text-danger"></i>
                    <span>Studio เตือนภัย</span>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- MODAL 1: EMERGENCY BROADCAST & HOTLINES DIRECTORY CENTER -->
<div class="modal fade" id="emergencyBroadcastModal" tabindex="-1" aria-labelledby="emergencyBroadcastModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header px-4 py-3 text-white rounded-top-4" style="background: <?= $bgStyle ?>;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-circle bg-white text-dark d-flex align-items-center justify-content-center flex-shrink-0 shadow-lg" style="width: 52px; height: 52px;">
                        <i class="fa-solid <?= $level === 'red' || $level === 'orange' ? 'fa-triangle-exclamation text-danger fs-3 animate-bounce' : 'fa-cloud-sun text-success fs-3' ?>"></i>
                    </div>
                    <div>
                        <span class="badge bg-dark bg-opacity-75 text-warning mb-1 px-3 py-1 rounded-pill">ศูนย์บัญชาการเตือนภัยและสายด่วนจังหวัดพัทลุง</span>
                        <h5 class="modal-title fw-bold mb-0" id="emergencyBroadcastModalLabel"><?= esc($alertData['headline'] ?? 'รายงานสภาพอากาศและเฝ้าระวังภัย') ?></h5>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Weather & PM2.5 Live Card -->
                <div class="card bg-light border-0 rounded-4 p-3 mb-4 shadow-sm">
                    <div class="row align-items-center text-center text-md-start g-3">
                        <div class="col-md-4 border-end-md">
                            <span class="text-muted small d-block mb-1"><i class="fa-solid fa-temperature-full text-warning me-1"></i> อุณหภูมิ / สภาพอากาศสด:</span>
                            <span class="fs-4 fw-bold text-dark"><?= esc($alertData['weather_temp'] ?? '27°C') ?></span>
                            <span class="small text-secondary d-block"><?= esc($alertData['weather_cond'] ?? '-') ?></span>
                        </div>
                        <div class="col-md-4 border-end-md">
                            <span class="text-muted small d-block mb-1"><i class="fa-solid fa-seedling text-success me-1"></i> ดัชนีฝุ่น PM 2.5:</span>
                            <span class="fs-5 fw-bold text-success"><?= esc($alertData['pm25_val'] ?? '14 µg/m³ (อากาศดี)') ?></span>
                            <span class="small text-muted d-block">อัปเดต: <?= esc($alertData['updated_at'] ?? 'ล่าสุด') ?></span>
                        </div>
                        <div class="col-md-4 text-md-center">
                            <span class="text-muted small d-block mb-1">ระดับความไม่ประมาท:</span>
                            <span class="badge py-2 px-3 fw-bold rounded-pill text-white shadow-sm" style="background: <?= $bgStyle ?>;">
                                <i class="fa-solid fa-shield-halved me-1"></i> <?= $badgeText ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Announcement Details -->
                <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-bullhorn text-danger me-2"></i>ข้อความสั่งการและคำแนะนำจากกองอำนวยการ ปภ. พัทลุง:</h6>
                <div class="p-4 rounded-4 bg-white border shadow-sm mb-4 text-dark" style="line-height: 1.7; font-size: 0.98rem;">
                    <?= nl2br(esc($alertData['details'] ?? "สถานการณ์โดยรวมปกติ ขอความร่วมมือประชาชนรักษาสุขภาพ และอัปเดตข่าวสารจากทางราชการอย่างต่อเนื่อง")) ?>
                    <hr class="my-3 text-muted">
                    <div class="d-flex align-items-center gap-2 flex-wrap text-muted small">
                        <strong><i class="fa-solid fa-location-dot text-danger me-1"></i> พื้นที่หรืออำเภอที่ต้องเฝ้าระวังเป็นพิเศษ:</strong>
                        <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-3 py-1 rounded-pill"><?= esc($alertData['affected_areas'] ?? 'ทุกพื้นที่อำเภอ') ?></span>
                    </div>
                </div>

                <!-- Emergency Hotlines Direct Call Matrix -->
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-phone-volume text-primary me-2"></i>สายด่วนแจ้งเหตุฉุกเฉินและกู้ชีพ (คลิกเพื่อโทรออกทันที):</h6>
                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:1784" class="emergency-call-card">
                            <div>
                                <span class="badge bg-danger text-white rounded-pill mb-2">สายด่วน ปภ.</span>
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
                                <span class="text-muted small">อัคคีภัย สัตว์มีพิษเข้าพักอาศัย</span>
                            </div>
                            <div class="p-3 rounded-circle bg-warning bg-opacity-25 text-warning fs-4"><i class="fa-solid fa-fire-extinguisher"></i></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:191" class="emergency-call-card">
                            <div>
                                <span class="badge bg-info text-dark rounded-pill mb-2">สายด่วน 191</span>
                                <h6 class="fw-bold mb-1">ตำรวจภูธรจังหวัด</h6>
                                <span class="text-muted small">แจ้งเหตุด่วน ร้ายแรง ความปลอดภัย</span>
                            </div>
                            <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info fs-4"><i class="fa-solid fa-shield-halved"></i></div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <a href="tel:1129" class="emergency-call-card">
                            <div>
                                <span class="badge bg-secondary text-white rounded-pill mb-2">สายด่วน 1129</span>
                                <h6 class="fw-bold mb-1">การไฟฟ้า กฟภ. พัทลุง</h6>
                                <span class="text-muted small">แจ้งเหตุไฟฟ้าดับ เสาโค่นล้ม</span>
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
            <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4 justify-content-between">
                <div class="small text-muted"><i class="fa-solid fa-circle-info me-1"></i> เชื่อมต่อข้อมูลโดยศูนย์ปฏิบัติการภาวะฉุกเฉิน (EOC) จังหวัดพัทลุง</div>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 2: EMERGENCY SYSTEM STUDIO FOR ADMIN / OFFICERS -->
<?php if ($isOfficer): ?>
<div class="modal fade" id="emergencyStudioModal" tabindex="-1" aria-labelledby="emergencyStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-warning text-dark px-4 py-3 rounded-top-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-sliders fs-4 text-danger"></i>
                    <h5 class="modal-title fw-bold mb-0" id="emergencyStudioModalLabel">ระบบจัดการประกาศเตือนภัยฉุกเฉิน & สภาพอากาศ Studio</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="emergencyStudioForm">
                    <!-- Toggle switch -->
                    <div class="form-check form-switch p-3 bg-light rounded-3 mb-4 d-flex align-items-center justify-content-between">
                        <label class="form-check-label fw-bold text-dark fs-6" for="em_is_active">🟢 เปิดแสดงแถบประกาศเตือนภัยและสภาพอากาศ (Top Alert Bar) บนทุกหน้าเว็บ</label>
                        <input class="form-check-input fs-5 ms-0" type="checkbox" id="em_is_active" name="is_active" <?= !empty($alertData['is_active']) ? 'checked' : '' ?>>
                    </div>

                    <!-- Severity Level Selector -->
                    <h6 class="fw-bold small text-dark mb-2">1. ระดับความรุนแรงของภัยพิบัติ / สถานการณ์ (Severity Level) <span class="text-danger">*</span></h6>
                    <div class="row g-2 mb-4">
                        <div class="col-6 col-md-3">
                            <input type="radio" class="btn-check" name="level" id="level_green" value="green" <?= $level==='green'?'checked':'' ?>>
                            <label class="btn btn-outline-success w-100 p-3 rounded-3 fw-bold small text-center d-block" for="level_green">
                                <i class="fa-solid fa-cloud-sun fs-4 d-block mb-1 text-success"></i>
                                🟢 สถานการณ์ปกติ<br><span class="text-muted" style="font-size:0.75rem;">สภาพอากาศประจำวัน</span>
                            </label>
                        </div>
                        <div class="col-6 col-md-3">
                            <input type="radio" class="btn-check" name="level" id="level_yellow" value="yellow" <?= $level==='yellow'?'checked':'' ?>>
                            <label class="btn btn-outline-warning w-100 p-3 rounded-3 fw-bold small text-center d-block text-dark" for="level_yellow">
                                <i class="fa-solid fa-cloud-showers-heavy fs-4 d-block mb-1 text-warning"></i>
                                🟡 แจ้งเตือนมรสุม<br><span class="text-muted" style="font-size:0.75rem;">ติดตามสถานการณ์</span>
                            </label>
                        </div>
                        <div class="col-6 col-md-3">
                            <input type="radio" class="btn-check" name="level" id="level_orange" value="orange" <?= $level==='orange'?'checked':'' ?>>
                            <label class="btn btn-outline-danger w-100 p-3 rounded-3 fw-bold small text-center d-block" for="level_orange" style="border-color: #ea580c; color: #ea580c;">
                                <i class="fa-solid fa-bolt fs-4 d-block mb-1" style="color: #ea580c;"></i>
                                🟠 เฝ้าระวังภัยสูง<br><span class="text-muted" style="font-size:0.75rem;">ความเสี่ยงน้ำท่วม/ดินถล่ม</span>
                            </label>
                        </div>
                        <div class="col-6 col-md-3">
                            <input type="radio" class="btn-check" name="level" id="level_red" value="red" <?= $level==='red'?'checked':'' ?>>
                            <label class="btn btn-outline-danger w-100 p-3 rounded-3 fw-bold small text-center d-block bg-danger bg-opacity-10 text-danger" for="level_red">
                                <i class="fa-solid fa-triangle-exclamation fs-4 d-block mb-1 text-danger animate-bounce"></i>
                                🔴 ภัยวิกฤตฉุกเฉิน<br><span class="text-muted" style="font-size:0.75rem;">อพยพด่วน / น้ำป่าเข้า</span>
                            </label>
                        </div>
                    </div>

                    <!-- Texts -->
                    <h6 class="fw-bold small text-dark mb-2">2. ข้อความประกาศและรายละเอียด (Broadcast Content)</h6>
                    <div class="mb-3">
                        <label class="form-label small text-muted">หัวข้อประกาศที่วิ่งบนแถบบาร์ (Headline Ticker)</label>
                        <input type="text" class="form-control fw-bold text-dark" name="headline" id="em_headline" value="<?= esc($alertData['headline'] ?? '') ?>" placeholder="ระบุข้อความสั้นกระชับได้ใจความ..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">อำเภอหรือพื้นที่ที่ได้รับผลกระทบ (Affected Areas)</label>
                        <input type="text" class="form-control" name="affected_areas" id="em_affected_areas" value="<?= esc($alertData['affected_areas'] ?? 'ทุกอำเภอในจังหวัดพัทลุง') ?>">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-muted">ข้อความสั่งการหรือข้อปฏิบัติตามคำเตือนฉบับเต็ม (Full Instructions)</label>
                        <textarea class="form-control" name="details" id="em_details" rows="4" placeholder="ระบุข้อปฏิบัติสำหรับประชาชน..."><?= esc($alertData['details'] ?? '') ?></textarea>
                    </div>

                    <!-- Weather Telemetry -->
                    <h6 class="fw-bold small text-dark mb-2">3. อัปเดตตัวเลขสภาพอากาศและ PM 2.5 (Live Telemetry)</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">อุณหภูมิ (°C)</label>
                            <input type="text" class="form-control text-center fw-bold text-warning" name="weather_temp" id="em_weather_temp" value="<?= esc($alertData['weather_temp'] ?? '27°C') ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small text-muted">สภาพอากาศ / ปริมาณฝน</label>
                            <input type="text" class="form-control" name="weather_cond" id="em_weather_cond" value="<?= esc($alertData['weather_cond'] ?? 'ฝนฟ้าคะนอง 60%') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">ดัชนีฝุ่น PM 2.5</label>
                            <input type="text" class="form-control text-center fw-bold text-success" name="pm25_val" id="em_pm25_val" value="<?= esc($alertData['pm25_val'] ?? '14 µg/m³ (อากาศดี)') ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" onclick="EmergencySystem.save()" class="btn btn-danger fw-bold rounded-pill px-5 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>เผยแพร่ประกาศทันที</span>
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const EmergencySystem = {
    broadcastModal: null,
    studioModal: null,

    openBroadcast: function() {
        const el = document.getElementById('emergencyBroadcastModal');
        if (el && typeof bootstrap !== 'undefined') {
            if (el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
            if (!this.broadcastModal) this.broadcastModal = new bootstrap.Modal(el);
            this.broadcastModal.show();
        }
    },

    <?php if ($isOfficer): ?>
    openStudio: function() {
        const el = document.getElementById('emergencyStudioModal');
        if (el && typeof bootstrap !== 'undefined') {
            if (el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
            if (!this.studioModal) this.studioModal = new bootstrap.Modal(el);
            this.studioModal.show();
        }
    },

    save: function() {
        const form = document.getElementById('emergencyStudioForm');
        if (!form) return;
        const formData = new FormData(form);
        if (typeof App !== 'undefined') App.toast('กำลังเผยแพร่ประกาศเตือนภัย...', 'info');

        fetch('<?= base_url('admin/emergency/save-alert') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined') App.toast(data.message, 'success');
                if (this.studioModal) this.studioModal.hide();
                setTimeout(() => window.location.reload(), 800);
            } else {
                if (typeof App !== 'undefined') App.toast(data.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof App !== 'undefined') App.toast('เกิดข้อผิดพลาดทางเครือข่าย', 'error');
        });
    }
    <?php endif; ?>
};
</script>

<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$alert = $alertData ?? [];
$isActive = !empty($alert['is_active']);
$level = $alert['level'] ?? 'orange';
?>

<style>
/* Emergency Manager Custom Styles */
.emergency-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 20px;
    padding: 2rem;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
}
.emergency-hero::after {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(239, 68, 68, 0.25) 0%, transparent 70%);
    pointer-events: none;
}

.level-card {
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 14px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.23, 1, 0.32, 1);
    text-align: center;
    background: #ffffff;
    height: 100%;
}
.level-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
.level-card.selected {
    border-color: #0284c7;
    background: rgba(2, 132, 199, 0.05);
    box-shadow: 0 8px 25px rgba(2, 132, 199, 0.25);
}

.level-card.level-red.selected {
    border-color: #dc2626;
    background: rgba(220, 38, 38, 0.08);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
}
.level-card.level-orange.selected {
    border-color: #ea580c;
    background: rgba(234, 88, 12, 0.08);
    box-shadow: 0 8px 25px rgba(234, 88, 12, 0.3);
}
.level-card.level-yellow.selected {
    border-color: #ca8a04;
    background: rgba(202, 138, 4, 0.08);
    box-shadow: 0 8px 25px rgba(202, 138, 4, 0.3);
}
.level-card.level-green.selected {
    border-color: #16a34a;
    background: rgba(22, 163, 74, 0.08);
    box-shadow: 0 8px 25px rgba(22, 163, 74, 0.3);
}

/* Live Preview Bar */
.preview-alert-bar {
    padding: 10px 18px;
    border-radius: 14px;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.92rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    transition: all 0.35s ease;
}

.preset-chip {
    padding: 6px 14px;
    border-radius: 9999px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #334155;
}
.preset-chip:hover {
    background: #e2e8f0;
    border-color: #94a3b8;
    transform: translateY(-2px);
    color: #0f172a;
}
</style>

<!-- Top Hero Header -->
<div class="emergency-hero mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-3 py-1 rounded-pill fw-bold" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-cloud-bolt me-1"></i> EMERGENCY & DISASTER MANAGEMENT
                </span>
                <?php if ($isActive): ?>
                    <span class="badge bg-success text-white px-2.5 py-1 rounded-pill" id="heroStatusBadge">
                        <i class="fa-solid fa-circle-dot me-1"></i> กำลังเปิดแสดงบนหน้าเว็บ (Active)
                    </span>
                <?php else: ?>
                    <span class="badge bg-secondary text-white px-2.5 py-1 rounded-pill" id="heroStatusBadge">
                        <i class="fa-solid fa-circle-pause me-1"></i> ปิดการแสดงผลอยู่ (Inactive)
                    </span>
                <?php endif; ?>
            </div>
            <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-warning"></i>
                <span>ระบบจัดการแจ้งเตือนภัย & สภาพอากาศฉุกเฉิน</span>
            </h3>
            <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                ควบคุมแถบแจ้งเตือนด่วนบนส่วนบนสุดของเว็บไซต์จังหวัดพัทลุง เตือนภัยฝนตกหนัก น้ำท่วม สภาพอากาศ และสายด่วนกู้ชีพ ปภ.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-light rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> ดูหน้าแรกของเว็บ
            </a>
            <button type="button" class="btn <?= $isActive ? 'btn-danger' : 'btn-success' ?> rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2" id="quickToggleBtn" onclick="toggleEmergencyAlert()">
                <i class="fa-solid <?= $isActive ? 'fa-power-off' : 'fa-play' ?>"></i>
                <span id="quickToggleText"><?= $isActive ? 'ปิดการแจ้งเตือน' : 'เปิดการแจ้งเตือนทันที' ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Main Form Card -->
<div class="card border-0 rounded-4 shadow-sm p-4 mb-4" style="background: #ffffff;">
    <form id="emergencyAlertForm" onsubmit="event.preventDefault(); saveEmergencySettings();">
        
        <!-- Section 1: Activation Switch & Severity Levels -->
        <div class="row align-items-center mb-4 pb-3 border-bottom">
            <div class="col-md-7">
                <h5 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-tower-broadcast text-primary"></i>
                    <span>สถานะการเปิดใช้งานแถบแจ้งเตือน (Top Alert Bar)</span>
                </h5>
                <p class="text-muted small mb-0">เมื่อเปิดใช้งาน แถบแจ้งเตือนจะแสดงผลที่ส่วนบนสุดของหน้าแรกเว็บไซต์ทันที</p>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <div class="form-check form-switch d-inline-block p-0">
                    <label class="form-check-label fw-bold me-2 fs-6" for="is_active" id="labelActiveStatus">
                        <?= $isActive ? '🟢 เปิดแสดงแถบแจ้งเตือน' : '⚪ ปิดการแสดงผล' ?>
                    </label>
                    <input class="form-check-input fs-4 cursor-pointer m-0 align-middle" type="checkbox" id="is_active" name="is_active" value="1" <?= $isActive ? 'checked' : '' ?> onchange="onSwitchChange(this.checked)">
                </div>
            </div>
        </div>

        <!-- Automated Weather & PM2.5 Sync Card -->
        <div class="mb-4 p-3.5 rounded-4 border-2" style="background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%); border: 2px dashed #0284c7;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="p-3 rounded-circle text-white d-flex align-items-center justify-content-center shadow-sm" style="background: linear-gradient(135deg, #0284c7, #2563eb); width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="fa-solid fa-satellite-dish fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-bolt me-1 text-warning"></i> LIVE AUTO-SYNC
                            </span>
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-0.5" style="font-size: 0.75rem;">
                                🌐 Open-Meteo & Air4Thai
                            </span>
                        </div>
                        <h6 class="fw-bold mb-1 text-dark">
                            ดึงข้อมูลสภาพอากาศ & คุณภาพอากาศ PM2.5 จังหวัดพัทลุง สดอัตโนมัติ
                        </h6>
                        <p class="text-muted small mb-0">
                            ตรวจจับปริมาณฝนสะสม ความน่าจะเป็นฝน อุณหภูมิ และ PM2.5 พร้อมประเมินระดับเตือนภัยอัตโนมัติ
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" id="btnSyncOnly" onclick="fetchLiveWeather(false)">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>ดึงข้อมูลใส่ฟอร์ม</span>
                    </button>
                    <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" id="btnSyncAndPublish" onclick="fetchLiveWeather(true)" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                        <i class="fa-solid fa-bolt"></i>
                        <span>ดึงข้อมูล & เผยแพร่ทันที (1-Click)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Severity Level Selection -->
        <div class="mb-4">
            <label class="form-label fw-bold text-dark mb-2" style="font-size: 1rem;">
                <i class="fa-solid fa-layer-group text-info me-1"></i> เลือกระดับความรุนแรงของสถานการณ์ (Severity Level) <span class="text-danger">*</span>
            </label>
            <div class="row g-3">
                <!-- Red -->
                <div class="col-6 col-lg-3">
                    <input type="radio" class="d-none" name="level" id="levelRed" value="red" <?= $level === 'red' ? 'checked' : '' ?> onchange="updateLevelSelection('red')">
                    <label class="level-card level-red <?= $level === 'red' ? 'selected' : '' ?>" for="levelRed">
                        <div class="p-2.5 rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex mb-2">
                            <i class="fa-solid fa-triangle-exclamation fs-3 animate-bounce"></i>
                        </div>
                        <h6 class="fw-bold text-danger mb-1">🔴 วิกฤตฉุกเฉินสูงสุด</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">น้ำท่วมฉับพลัน / อพยพด่วน / ภัยพิบัติใหญ่</small>
                    </label>
                </div>

                <!-- Orange -->
                <div class="col-6 col-lg-3">
                    <input type="radio" class="d-none" name="level" id="levelOrange" value="orange" <?= $level === 'orange' ? 'checked' : '' ?> onchange="updateLevelSelection('orange')">
                    <label class="level-card level-orange <?= $level === 'orange' ? 'selected' : '' ?>" for="levelOrange">
                        <div class="p-2.5 rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex mb-2" style="color: #ea580c !important;">
                            <i class="fa-solid fa-cloud-showers-heavy fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: #ea580c;">🟠 เฝ้าระวังภัยระดับสูง</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">ฝนตกหนักมาก / เสี่ยงน้ำป่าไหลหลาก (แนะนำช่วงนี้)</small>
                    </label>
                </div>

                <!-- Yellow -->
                <div class="col-6 col-lg-3">
                    <input type="radio" class="d-none" name="level" id="levelYellow" value="yellow" <?= $level === 'yellow' ? 'checked' : '' ?> onchange="updateLevelSelection('yellow')">
                    <label class="level-card level-yellow <?= $level === 'yellow' ? 'selected' : '' ?>" for="levelYellow">
                        <div class="p-2.5 rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex mb-2">
                            <i class="fa-solid fa-cloud-sun-rain fs-3 text-warning"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">🟡 แจ้งเตือนมรสุม</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">ฝนตกปานกลาง / ลมกระโชก / คลื่นลมแรง</small>
                    </label>
                </div>

                <!-- Green -->
                <div class="col-6 col-lg-3">
                    <input type="radio" class="d-none" name="level" id="levelGreen" value="green" <?= $level === 'green' ? 'checked' : '' ?> onchange="updateLevelSelection('green')">
                    <label class="level-card level-green <?= $level === 'green' ? 'selected' : '' ?>" for="levelGreen">
                        <div class="p-2.5 rounded-circle bg-success bg-opacity-10 text-success d-inline-flex mb-2">
                            <i class="fa-solid fa-sun fs-3 text-success"></i>
                        </div>
                        <h6 class="fw-bold text-success mb-1">🟢 สถานการณ์ปกติ</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">สภาพอากาศประจำวัน / อากาศแจ่มใส</small>
                    </label>
                </div>
            </div>
        </div>

        <!-- Quick Template Presets -->
        <div class="mb-4 p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
            <label class="form-label fw-bold text-secondary mb-2 small d-flex align-items-center gap-1">
                <i class="fa-solid fa-wand-magic-sparkles text-primary"></i> เลือกข้อความแม่แบบด่วน 1-คลิก (Quick Presets):
            </label>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="preset-chip" onclick="applyPreset('heavy_rain')">
                    🌧️ ฝนตกหนักมาก เฝ้าระวังน้ำท่วมฉับพลัน
                </button>
                <button type="button" class="preset-chip" onclick="applyPreset('water_level')">
                    🌊 ระดับน้ำในลุ่มน้ำเพิ่มสูงขึ้น
                </button>
                <button type="button" class="preset-chip" onclick="applyPreset('strong_wind')">
                    🌪️ ลมกระโชกแรงและคลื่นลมในทะเลสาบ
                </button>
                <button type="button" class="preset-chip" onclick="applyPreset('normal_weather')">
                    ☀️ สถานการณ์คลี่คลาย สภาพอากาศปกติ
                </button>
            </div>
        </div>

        <!-- Section 2: Headline & Content -->
        <div class="row g-3 mb-4">
            <div class="col-12">
                <label class="form-label fw-bold text-dark mb-1" style="font-size: 1rem;">
                    <i class="fa-solid fa-heading text-danger me-1"></i> หัวข้อประกาศที่วิ่งบนแถบบาร์ (Headline Ticker) <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-lg border-2" id="inputHeadline" name="headline" value="<?= esc($alert['headline'] ?? '') ?>" placeholder="เช่น 🌧️ [แจ้งเตือนเฝ้าระวัง] มรสุมกำลังแรง พัทลุงฝนตกหนักถึงหนักมาก..." required oninput="updateLivePreview()">
                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i> ข้อความนี้จะปรากฏวิ่งในแถบแจ้งเตือนส่วนบนสุดของหน้าเว็บ</small>
            </div>

            <div class="col-md-12">
                <label class="form-label fw-bold text-dark mb-1">
                    <i class="fa-solid fa-location-dot text-primary me-1"></i> อำเภอหรือพื้นที่เสี่ยงภัยที่ต้องเฝ้าระวัง (Affected Areas)
                </label>
                <input type="text" class="form-control border-2" id="inputAreas" name="affected_areas" value="<?= esc($alert['affected_areas'] ?? '') ?>" placeholder="เช่น อำเภอเมืองพัทลุง, อำเภอควนขนุน, อำเภอศรีบรรพต, อำเภอกงหรา..." oninput="updateLivePreview()">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold text-dark mb-1">
                    <i class="fa-solid fa-align-left text-success me-1"></i> รายละเอียดประกาศและแนวทางปฏิบัติสำหรับประชาชน (Full Details & Instructions)
                </label>
                <textarea class="form-control border-2" id="inputDetails" name="details" rows="5" placeholder="ระบุข้อความคำสั่งการ หรือคำแนะนำ 1. ตรวจสอบเครื่องใช้ไฟฟ้า 2. ติดตามข่าวสาร..."><?= esc($alert['details'] ?? '') ?></textarea>
                <small class="text-muted">ข้อความนี้จะแสดงในป๊อปอัปเมื่อประชาชนกดคลิกปุ่ม "รายละเอียด & สายด่วน"</small>
            </div>
        </div>

        <!-- Section 3: Weather Telemetry Live -->
        <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
            <i class="fa-solid fa-cloud-sun text-warning"></i>
            <span>ข้อมูลสภาพอากาศสดและ PM 2.5 (Live Telemetry)</span>
        </h5>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">อุณหภูมิ (°C)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-temperature-half text-warning"></i></span>
                    <input type="text" class="form-control text-center fw-bold border-2" id="inputTemp" name="weather_temp" value="<?= esc($alert['weather_temp'] ?? '26°C') ?>" oninput="updateLivePreview()">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">สภาพอากาศ / ปริมาณฝน</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-cloud-showers-heavy text-primary"></i></span>
                    <input type="text" class="form-control fw-bold border-2" id="inputCond" name="weather_cond" value="<?= esc($alert['weather_cond'] ?? 'ฝนตกหนัก 80% ของพื้นที่') ?>" oninput="updateLivePreview()">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">ดัชนีคุณภาพอากาศ PM 2.5</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-wind text-info"></i></span>
                    <input type="text" class="form-control text-center fw-bold border-2" id="inputPm" name="pm25_val" value="<?= esc($alert['pm25_val'] ?? '11 µg/m³ (อากาศดีมาก)') ?>" oninput="updateLivePreview()">
                </div>
            </div>
        </div>

        <!-- Section 4: Live Preview -->
        <div class="mb-4">
            <label class="form-label fw-bold text-dark mb-2">
                <i class="fa-solid fa-eye text-primary me-1"></i> ตัวอย่างแสดงผลจริงบนหน้าเว็บ (Live Preview):
            </label>
            <div class="p-3 rounded-4 bg-light border">
                <div id="livePreviewBar" class="preview-alert-bar d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: linear-gradient(135deg, #c2410c 0%, #ea580c 50%, #9a3412 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-dark bg-opacity-40 text-white rounded-pill px-2.5 py-1" id="previewBadgeText">
                            <i class="fa-solid fa-bolt me-1 text-warning"></i> 🟠 เตือนภัยระดับสูง
                        </span>
                        <span id="previewHeadline" class="text-white fw-bold">
                            <?= esc($alert['headline'] ?? 'มรสุมกำลังแรง พัทลุงฝนตกหนักถึงหนักมาก') ?>
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-dark bg-opacity-30 text-white rounded-pill px-2 py-1" id="previewTelemetry">
                            <i class="fa-solid fa-temperature-half text-warning me-1"></i><span id="previewTempText">26°C</span> • <span id="previewCondText">ฝนตกหนัก</span>
                        </span>
                        <span class="btn btn-xs btn-light text-dark fw-bold rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-phone text-danger me-1"></i> สายด่วนฉุกเฉิน
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center justify-content-between pt-3 border-top gap-3 flex-wrap">
            <div class="text-muted small">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> อัปเดตล่าสุด: <span class="fw-bold text-dark"><?= esc($alert['updated_at'] ?? 'เพิ่งแก้ไข') ?></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-pill fw-bold shadow-lg d-inline-flex align-items-center gap-2" id="saveSubmitBtn" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none;">
                    <i class="fa-solid fa-cloud-arrow-up fs-5"></i>
                    <span>บันทึกและเผยแพร่สู่หน้าเว็บทันที</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let currentLevel = '<?= $level ?>';

function updateLevelSelection(level) {
    currentLevel = level;
    document.querySelectorAll('.level-card').forEach(el => el.classList.remove('selected'));
    const input = document.querySelector(`input[name="level"][value="${level}"]`);
    if (input) {
        input.checked = true;
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label) label.classList.add('selected');
    }
    updateLivePreview();
}

function onSwitchChange(checked) {
    const label = document.getElementById('labelActiveStatus');
    const heroBadge = document.getElementById('heroStatusBadge');
    const toggleBtn = document.getElementById('quickToggleBtn');
    const toggleText = document.getElementById('quickToggleText');

    if (checked) {
        if (label) label.textContent = '🟢 เปิดแสดงแถบแจ้งเตือน';
        if (heroBadge) {
            heroBadge.className = 'badge bg-success text-white px-2.5 py-1 rounded-pill';
            heroBadge.innerHTML = '<i class="fa-solid fa-circle-dot me-1"></i> กำลังเปิดแสดงบนหน้าเว็บ (Active)';
        }
        if (toggleBtn) {
            toggleBtn.className = 'btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2';
        }
        if (toggleText) toggleText.textContent = 'ปิดการแจ้งเตือน';
    } else {
        if (label) label.textContent = '⚪ ปิดการแสดงผล';
        if (heroBadge) {
            heroBadge.className = 'badge bg-secondary text-white px-2.5 py-1 rounded-pill';
            heroBadge.innerHTML = '<i class="fa-solid fa-circle-pause me-1"></i> ปิดการแสดงผลอยู่ (Inactive)';
        }
        if (toggleBtn) {
            toggleBtn.className = 'btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2';
        }
        if (toggleText) toggleText.textContent = 'เปิดการแจ้งเตือนทันที';
    }
}

function toggleEmergencyAlert() {
    const checkbox = document.getElementById('is_active');
    if (!checkbox) return;
    const newState = !checkbox.checked;
    checkbox.checked = newState;
    onSwitchChange(newState);

    const formData = new FormData();
    formData.append('is_active', newState ? '1' : '0');

    if (typeof App !== 'undefined') App.toast('กำลังปรับปรุงสถานะแจ้งเตือน...', 'info');

    fetch('<?= base_url('admin/emergency/toggle') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (typeof App !== 'undefined') App.toast(data.message, 'success');
        } else {
            if (typeof App !== 'undefined') App.toast(data.message || 'เกิดข้อผิดพลาด', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        if (typeof App !== 'undefined') App.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
    });
}

function updateLivePreview() {
    const previewBar = document.getElementById('livePreviewBar');
    const badgeText = document.getElementById('previewBadgeText');
    const headline = document.getElementById('previewHeadline');
    const tempText = document.getElementById('previewTempText');
    const condText = document.getElementById('previewCondText');

    const inputHeadline = document.getElementById('inputHeadline').value;
    const inputTemp = document.getElementById('inputTemp').value;
    const inputCond = document.getElementById('inputCond').value;

    if (headline) headline.textContent = inputHeadline || 'ข้อความแจ้งเตือนด่วน...';
    if (tempText) tempText.textContent = inputTemp || '26°C';
    if (condText) condText.textContent = inputCond || 'ฝนตกหนัก';

    if (previewBar && badgeText) {
        if (currentLevel === 'red') {
            previewBar.style.background = 'linear-gradient(135deg, #991b1b 0%, #dc2626 50%, #7f1d1d 100%)';
            badgeText.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1 text-warning animate-bounce"></i> 🔴 ภัยวิกฤตฉุกเฉิน';
        } else if (currentLevel === 'orange') {
            previewBar.style.background = 'linear-gradient(135deg, #c2410c 0%, #ea580c 50%, #9a3412 100%)';
            badgeText.innerHTML = '<i class="fa-solid fa-cloud-showers-heavy me-1 text-warning"></i> 🟠 เตือนภัยฝนตกหนัก';
        } else if (currentLevel === 'yellow') {
            previewBar.style.background = 'linear-gradient(135deg, #a16207 0%, #ca8a04 100%)';
            badgeText.innerHTML = '<i class="fa-solid fa-cloud-sun-rain me-1 text-white"></i> 🟡 แจ้งเตือนมรสุม';
        } else {
            previewBar.style.background = 'linear-gradient(135deg, #047857 0%, #10b981 100%)';
            badgeText.innerHTML = '<i class="fa-solid fa-sun me-1 text-warning"></i> 🟢 สถานการณ์ปกติ';
        }
    }
}

function fetchLiveWeather(autoPublish = false) {
    const btn = autoPublish ? document.getElementById("btnSyncAndPublish") : document.getElementById("btnSyncOnly");
    const origHtml = btn ? btn.innerHTML : "";
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังเชื่อมต่อ API...';
    }

    const endpoint = autoPublish 
        ? '<?= base_url("admin/emergency/auto-apply-weather") ?>' 
        : '<?= base_url("admin/emergency/sync-weather") ?>';
    const method = autoPublish ? "POST" : "GET";

    fetch(endpoint, {
        method: method,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
        if (data.status === "success" && data.data) {
            const w = data.data;
            if (w.level) updateLevelSelection(w.level);
            if (w.headline) document.getElementById("inputHeadline").value = w.headline;
            if (w.affected_areas) document.getElementById("inputAreas").value = w.affected_areas;
            if (w.details) document.getElementById("inputDetails").value = w.details;
            if (w.weather_temp) document.getElementById("inputTemp").value = w.weather_temp;
            if (w.weather_cond) document.getElementById("inputCond").value = w.weather_cond;
            if (w.pm25_val) document.getElementById("inputPm").value = w.pm25_val;
            
            // Switch to active if inactive
            const checkbox = document.getElementById("is_active");
            if (checkbox && !checkbox.checked) {
                checkbox.checked = true;
                onSwitchChange(true);
            }

            updateLivePreview();
            
            if (typeof App !== "undefined") {
                App.toast(data.message, "success");
            }
            if (autoPublish) {
                setTimeout(() => window.location.reload(), 1200);
            }
        } else {
            if (typeof App !== "undefined") App.toast(data.message || "ไม่สามารถดึงข้อมูลได้", "error");
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
        console.error(err);
        if (typeof App !== "undefined") App.toast("เกิดข้อผิดพลาดในการเชื่อมต่อเครือข่าย", "error");
    });
}

function applyPreset(type) {
    if (type === 'heavy_rain') {
        updateLevelSelection('orange');
        document.getElementById('inputHeadline').value = '🌧️ [แจ้งเตือนเฝ้าระวัง] มรสุมกำลังแรง พัทลุงฝนตกหนักถึงหนักมาก เฝ้าระวังน้ำท่วมฉับพลันและน้ำป่าไหลหลากในพื้นที่เสี่ยงภัย';
        document.getElementById('inputAreas').value = 'อำเภอเมืองพัทลุง, อำเภอควนขนุน, อำเภอศรีบรรพต, อำเภอกงหรา, อำเภอศรีนครินทร์';
        document.getElementById('inputDetails').value = "กองอำนวยการป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง (กอปภ.จ.พัทลุง) ประกาศแจ้งเตือนประชาชนในพื้นที่เสี่ยงภัย โดยเฉพาะพื้นที่ลาดเชิงเขา ทางน้ำไหลผ่าน และที่ลุ่มริมแม่น้ำ ให้ระมัดระวังอันตรายจากฝนตกหนักถึงหนักมากและปริมาณฝนสะสม ซึ่งอาจทำให้เกิดน้ำท่วมฉับพลัน น้ำป่าไหลหลาก และดินโคลนถล่ม\n\nแนวทางปฏิบัติสำหรับประชาชน:\n1. ตรวจสอบและยกสิ่งของ เครื่องใช้ไฟฟ้าขึ้นที่สูง\n2. หลีกเลี่ยงการสัญจรผ่านเส้นทางที่มีกระแสน้ำท่วมขัง\n3. ตรวจสอบคันกั้นน้ำ ประตูน้ำ และระบบระบายน้ำรอบที่อยู่อาศัย\n4. ติดตามข่าวสารการพยากรณ์อากาศและประกาศเตือนภัยจากทางราชการอย่างใกล้ชิด\n5. หากต้องการความช่วยเหลือเร่งด่วน ติดต่อสายด่วน ปภ. 1784 หรือสายด่วนศูนย์ดำรงธรรม 1567 ตลอด 24 ชั่วโมง";
        document.getElementById('inputTemp').value = '26°C';
        document.getElementById('inputCond').value = 'ฝนตกหนัก 80% ของพื้นที่';
        document.getElementById('inputPm').value = '11 µg/m³ (อากาศดีมาก)';
    } else if (type === 'water_level') {
        updateLevelSelection('red');
        document.getElementById('inputHeadline').value = '🌊 [เตือนภัยวิกฤต] ระดับน้ำในลุ่มน้ำเพิ่มสูงขึ้นใกล้ล้นตลิ่ง ขอให้ประชาชนขนย้ายสิ่งของขึ้นที่สูงและเตรียมพร้อมอพยพ';
        document.getElementById('inputAreas').value = 'อำเภอควนขนุน (ลุ่มน้ำคลองท่าแนะ), อำเภอเมืองพัทลุง (ลุ่มน้ำคลองนาท่อม), ลุ่มน้ำทะเลน้อย';
        document.getElementById('inputDetails').value = "แจ้งเตือนระดับน้ำล้นตลิ่ง ขอให้ประชาชนในลุ่มน้ำคลองท่าแนะ คลองนาท่อม และพื้นที่ริมทะเลสาบสงขลา-พัทลุง ยกของขึ้นที่สูงทันที ตัดสะพานไฟชั้นล่าง และเตรียมพร้อมอพยพกลุ่มเปราะบาง เด็ก และคนชราไปยังศูนย์พักพิงชั่วคราว ติดต่อศูนย์บัญชาการ ปภ. 1784";
        document.getElementById('inputTemp').value = '25°C';
        document.getElementById('inputCond').value = 'ฝนตกหนักต่อเนื่อง ปริมาณน้ำสะสมสูง';
        document.getElementById('inputPm').value = '10 µg/m³ (อากาศดีเยี่ยม)';
    } else if (type === 'strong_wind') {
        updateLevelSelection('yellow');
        document.getElementById('inputHeadline').value = '🌪️ [เฝ้าระวังคลื่นลมแรง] ทะเลสาบสงขลา-พัทลุงมีคลื่นลมแรงและลมกระโชกแรง ขอให้ชาวเรือและผู้ประกอบการท่องเที่ยวเดินเรือด้วยความระมัดระวัง';
        document.getElementById('inputAreas').value = 'อำเภอปากพะยูน, อำเภอบางแก้ว, อำเภอเขาชัยสน, อำเภอควนขนุน (ทะเลน้อย)';
        document.getElementById('inputDetails').value = "คลื่นลมบริเวณทะเลสาบสงขลา-พัทลุงมีกำลังแรง คลื่นสูง 1-2 เมตร บริเวณที่มีฝนฟ้าคะนองคลื่นสูงมากกว่า 2 เมตร ขอให้เรือประมงพื้นบ้านและเรือนำเที่ยวเดินเรือด้วยความระมัดระวัง และหลีกเลี่ยงการเดินเรือในบริเวณที่มีฝนฟ้าคะนอง";
        document.getElementById('inputTemp').value = '28°C';
        document.getElementById('inputCond').value = 'ลมกระโชกแรง มีฝนฟ้าคะนอง 60%';
        document.getElementById('inputPm').value = '14 µg/m³';
    } else if (type === 'normal_weather') {
        updateLevelSelection('green');
        document.getElementById('inputHeadline').value = '☀️ [สภาพอากาศประจำวัน] สภาพอากาศโดยรวมปลอดโปร่ง มีฝนฟ้าคะนองบางแห่ง ท้องฟ้าแจ่มใส สถานการณ์ปกติ';
        document.getElementById('inputAreas').value = 'ทุกอำเภอในจังหวัดพัทลุง';
        document.getElementById('inputDetails').value = "สถานการณ์สภาพอากาศโดยรวมในพื้นที่จังหวัดพัทลุงอยู่ในเกณฑ์ปกติ ไม่มีรายงานภัยพิบัติ ประชาชนสามารถดำเนินชีวิตและประกอบกิจการได้ตามปกติ ติดตามข้อมูลพยากรณ์อากาศประจำวันได้ตลอด 24 ชั่วโมง";
        document.getElementById('inputTemp').value = '31°C';
        document.getElementById('inputCond').value = 'ฟ้าโปร่ง มีเมฆบางส่วน';
        document.getElementById('inputPm').value = '12 µg/m³ (อากาศดีมาก)';
    }
    updateLivePreview();
    if (typeof App !== 'undefined') App.toast('กรอกข้อความตามแม่แบบเรียบร้อยแล้ว กด "บันทึก" เพื่อเผยแพร่', 'info');
}

function saveEmergencySettings() {
    const form = document.getElementById('emergencyAlertForm');
    if (!form) return;

    const btn = document.getElementById('saveSubmitBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> กำลังบันทึกและเผยแพร่...';

    const formData = new FormData(form);

    fetch('<?= base_url('admin/emergency/save-alert') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        if (data.status === 'success') {
            if (typeof App !== 'undefined') App.toast(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            if (typeof App !== 'undefined') App.toast(data.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        console.error(err);
        if (typeof App !== 'undefined') App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
    });
}

// Initial setup
document.addEventListener('DOMContentLoaded', () => {
    updateLivePreview();
});
</script>

<?= $this->endSection() ?>


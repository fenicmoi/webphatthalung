<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$alert = $alertData ?? [];
$level = $alert['level'] ?? 'orange';
$extUrl = $externalUrl ?? 'https://ddpmpt.rescuephatthalung.com/#forecast';
?>

<style>
.radar-hero {
    background: linear-gradient(135deg, #091e3a 0%, #102a45 50%, #1e3a8a 100%);
    border-radius: 24px;
    padding: 2.5rem 2rem;
    color: #ffffff;
    box-shadow: 0 15px 35px rgba(2, 6, 23, 0.25);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.12);
}
.radar-hero::after {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 280px;
    height: 280px;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, transparent 70%);
    pointer-events: none;
}

.radar-frame-box {
    border-radius: 20px;
    background: #0f172a;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.2);
    border: 1px solid #334155;
    overflow: hidden;
    position: relative;
}

.radar-frame-header {
    background: #1e293b;
    padding: 12px 20px;
    border-bottom: 1px solid #334155;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.iframe-container {
    position: relative;
    width: 100%;
    height: 820px;
    background: #0b1120;
}

.iframe-container iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
}

.station-badge-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px;
    transition: all 0.25s ease;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}
.station-badge-card:hover {
    transform: translateY(-4px);
    border-color: #3b82f6;
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.12);
}

[data-theme="dark"] .station-badge-card {
    background: #1e293b;
    border-color: #334155;
    color: #f8fafc;
}

.launch-overlay-card {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 18px;
    padding: 24px;
    text-align: center;
    color: #ffffff;
    max-width: 600px;
    margin: 0 auto;
}
</style>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0 py-2 px-3 rounded-pill bg-light border small">
            <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-decoration-none text-muted"><i class="fa-solid fa-house me-1"></i>หน้าแรก</a></li>
            <li class="breadcrumb-item text-muted">ระบบเตือนภัย & สภาพอากาศ</li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">กล้องสดริมคลอง & เรดาร์ฝน ปภ.พัทลุง</li>
        </ol>
    </nav>

    <!-- Hero Header -->
    <div class="radar-hero mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold shadow-xs" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-dot animate-pulse me-1"></i> REAL-TIME CCTV & RADAR
                    </span>
                    <span class="badge bg-light text-dark rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.78rem;">
                        กอปภ.จ.พัทลุง
                    </span>
                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50 rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                        OpenRadar & Camera Stream
                    </span>
                </div>
                <h2 class="fw-bold mb-2 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-video text-warning"></i>
                    <span>ระบบติดตามสถานการณ์น้ำ กล้องสดริมคลอง และเรดาร์พยากรณ์ฝน</span>
                </h2>
                <p class="text-white-50 mb-0" style="font-size: 1.02rem; max-width: 820px; line-height: 1.6;">
                    โดย <strong>สำนักงานป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง (กอปภ.จ.พัทลุง)</strong> ร่วมกับ <strong>ประชาชน จิตอาสา และกู้ภัยพัทลุง</strong> ตรวจสอบระดับน้ำในคลองสายหลักและทิศทางกลุ่มฝนตลอด 24 ชั่วโมง
                </p>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?= esc($extUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-warning text-dark px-4 py-2.5 rounded-pill fw-bold shadow-lg d-inline-flex align-items-center gap-2 hover-scale" style="background: linear-gradient(135deg, #fbbf24, #f59e0b); border: none;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>เปิดดูระบบสดเต็มจอ (แยกหน้าต่าง)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Live Telemetry Status Bar -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 p-3" style="background: #ffffff;">
        <div class="row g-3 align-items-center">
            <div class="col-md-3 border-end-md">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary fs-4">
                        <i class="fa-solid fa-cloud-showers-heavy"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">สภาพอากาศสดพัทลุง</small>
                        <span class="fw-bold text-dark fs-6"><?= esc($alert['weather_cond'] ?? 'มีฝนฟ้าคะนองในพื้นที่') ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 border-end-md">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning fs-4">
                        <i class="fa-solid fa-temperature-half"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">อุณหภูมิปัจจุบัน</small>
                        <span class="fw-bold text-dark fs-5"><?= esc($alert['weather_temp'] ?? '26°C') ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 border-end-md">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success fs-4">
                        <i class="fa-solid fa-wind"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">ดัชนีคุณภาพอากาศ</small>
                        <span class="fw-bold text-dark fs-6"><?= esc($alert['pm25_val'] ?? '5.5 µg/m³ (อากาศดีมาก)') ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-md-center">
                <small class="text-muted d-block mb-1">ระดับสถานการณ์ปัจจุบัน</small>
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold" style="background: #ea580c !important; color: #ffffff !important;">
                    <i class="fa-solid fa-shield-halved me-1"></i> เฝ้าระวังภัยระดับสูง
                </span>
            </div>
        </div>
    </div>

    <!-- Main Live Screen Frame -->
    <div class="radar-frame-box mb-4">
        <div class="radar-frame-header text-white">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger rounded-circle p-1" style="width: 10px; height: 10px; display: inline-block;"></span>
                <span class="fw-bold" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-satellite-dish text-info me-1.5"></i> ถ่ายทอดสดกล้องริมคลอง & เรดาร์กลุ่มฝน (ddpmpt.rescuephatthalung.com)
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-xs btn-outline-light rounded-pill px-3 py-1" onclick="reloadRadarFrame()" title="รีเฟรชหน้าจอ">
                    <i class="fa-solid fa-rotate-right me-1"></i> รีเฟรช
                </button>
                <a href="<?= esc($extUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-xs btn-primary rounded-pill px-3 py-1 fw-bold text-white shadow-xs">
                    <i class="fa-solid fa-expand me-1"></i> เต็มจอ (Full Screen)
                </a>
            </div>
        </div>

        <div class="iframe-container" id="radarContainer">
            <!-- Graceful Fallback / Launcher Banner -->
            <div class="d-flex align-items-center justify-content-center h-100 p-4">
                <div class="launch-overlay-card shadow-lg">
                    <div class="p-3 rounded-circle bg-danger bg-opacity-20 text-danger d-inline-flex mb-3">
                        <i class="fa-solid fa-video fs-1 animate-pulse"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-2">กล้องตรวจระดับน้ำริมคลอง & เรดาร์ฝน ปภ.พัทลุง</h4>
                    <p class="text-white-50 mb-4" style="line-height: 1.6;">
                        ระบบถ่ายทอดสดความละเอียดสูงและเรดาร์พยากรณ์น้ำท่วม เปิดให้บริการโดย สำนักงาน ปภ. จังหวัดพัทลุง ร่วมกับประชาชน จิตอาสา และกู้ภัยพัทลุง
                    </p>
                    <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 mb-3">
                        <a href="<?= esc($extUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-danger btn-lg px-4 py-3 rounded-pill fw-bold shadow-lg d-inline-flex align-items-center gap-2 hover-scale" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none;">
                            <i class="fa-solid fa-arrow-up-right-from-square fs-5"></i>
                            <span>คลิกเปิดดูระบบสดเต็มจอทันที</span>
                        </a>
                    </div>
                    <small class="text-white-50 d-block">
                        <i class="fa-solid fa-shield-halved text-success me-1"></i> ระบบภายนอกความปลอดภัยสูง รองรับทั้งมือถือ แท็บเล็ต และคอมพิวเตอร์
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stations & Information Grid -->
    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
        <i class="fa-solid fa-map-location-dot text-primary"></i>
        <span>จุดตรวจระดับน้ำและกล้องสำคัญในจังหวัดพัทลุง</span>
    </h5>
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="station-badge-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold rounded-pill px-2.5 py-1">ลุ่มน้ำคลองท่าแนะ</span>
                    <span class="badge bg-success rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">กล้องสดออนไลน์</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">อำเภอควนขนุน (คลองท่าแนะ)</h6>
                <p class="text-muted small mb-3">เฝ้าระวังระดับน้ำหลากจากเทือกเขาบรรทัด ทางน้ำไหลผ่านสู่ทะเลน้อย</p>
                <a href="<?= esc($extUrl) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold">
                    <i class="fa-solid fa-video me-1"></i> ดูภาพสดจุดนี้
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="station-badge-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-warning bg-opacity-20 text-warning text-dark fw-bold rounded-pill px-2.5 py-1">ลุ่มน้ำคลองนาท่อม</span>
                    <span class="badge bg-success rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">กล้องสดออนไลน์</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">อำเภอเมืองพัทลุง (คลองนาท่อม)</h6>
                <p class="text-muted small mb-3">จุดเฝ้าระวังน้ำท่วมขังเขตเทศบาลเมืองพัทลุง และสะพานลำปำ</p>
                <a href="<?= esc($extUrl) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold">
                    <i class="fa-solid fa-video me-1"></i> ดูภาพสดจุดนี้
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="station-badge-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold rounded-pill px-2.5 py-1">ลุ่มน้ำคลองป่าบอน</span>
                    <span class="badge bg-success rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">กล้องสดออนไลน์</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">อำเภอป่าบอน - เขาชัยสน</h6>
                <p class="text-muted small mb-3">ติดตามสถานการณ์น้ำไหลลงสู่ทะเลสาบสงขลาตอนกลาง</p>
                <a href="<?= esc($extUrl) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold">
                    <i class="fa-solid fa-video me-1"></i> ดูภาพสดจุดนี้
                </a>
            </div>
        </div>
    </div>

    <!-- Emergency Hotlines Direct Matrix -->
    <div class="card border-0 rounded-4 shadow-sm p-4 text-white mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <span class="badge bg-danger rounded-pill px-3 py-1 mb-2 fw-bold">24-HOUR EMERGENCY HOTLINES</span>
                <h4 class="fw-bold text-white mb-1">สายด่วนช่วยเหลือผู้ประสบภัยน้ำท่วม 24 ชั่วโมง</h4>
                <p class="text-white-50 mb-0 small">หากเกิดเหตุน้ำท่วมฉับพลัน ดินโคลนถล่ม หรือต้องการความช่วยเหลืออพยพเร่งด่วน</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
                    <a href="tel:1784" class="btn btn-danger btn-lg rounded-pill px-4 py-2.5 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-phone-volume"></i> สายด่วน ปภ. 1784
                    </a>
                    <a href="tel:1567" class="btn btn-outline-light btn-lg rounded-pill px-4 py-2.5 fw-bold shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-phone"></i> ศูนย์ดำรงธรรม 1567
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function reloadRadarFrame() {
    const container = document.getElementById('radarContainer');
    if (container) {
        App.toast('กำลังโหลดข้อมูลระบบสด...', 'info');
        setTimeout(() => {
            window.open('<?= esc($extUrl) ?>', '_blank');
        }, 300);
    }
}
</script>

<?= $this->endSection() ?>


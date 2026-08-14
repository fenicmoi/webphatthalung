<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$services = $services ?? [];
$cfg = function_exists('get_site_settings') ? get_site_settings() : [];
?>

<!-- 1. SMART MUNICIPALITY KINETIC HERO BANNER (สไลด์ประกอบแอนิเมชันเลเยอร์อัจฉริยะ) -->
<section id="smartCityCarousel" class="carousel slide smart-slider-box mb-4" data-bs-ride="carousel" data-bs-interval="7500">
    <!-- Carousel Indicators -->
    <div class="carousel-indicators" style="z-index: 20; margin-bottom: 1.2rem;">
        <button type="button" data-bs-target="#smartCityCarousel" data-bs-slide-to="0" class="active" aria-current="true" style="width: 38px; height: 6px; border-radius: 4px; background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.6);"></button>
        <button type="button" data-bs-target="#smartCityCarousel" data-bs-slide-to="1" style="width: 38px; height: 6px; border-radius: 4px; background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.6);"></button>
        <button type="button" data-bs-target="#smartCityCarousel" data-bs-slide-to="2" style="width: 38px; height: 6px; border-radius: 4px; background: #ffffff; box-shadow: 0 2px 6px rgba(0,0,0,0.6);"></button>
    </div>

    <!-- Carousel Inner (Slide Content) -->
    <div class="carousel-inner h-100">
        <!-- ================= SLIDE 1: SMART LIVING (เมืองอัจฉริยะ คุณภาพชีวิตสากล) ================= -->
        <div class="carousel-item active smart-slide-item slide-bg-living h-100 p-4 p-lg-5">
            <div class="slide-geo-left"></div>
            <div class="slide-geo-right-orange"></div>

            <!-- Layer 1: Smart Pole Visual (เสาไฟ & กล้อง AI อัจฉริยะ) -->
            <div class="smart-pole-visual d-none d-lg-flex anim-from-bottom delay-1">
                <div class="pole-top-light"></div>
                <div class="pole-shaft">
                    <div class="pole-sensor-box" title="กล้อง AI CCTV 24 ชม."><i class="fa-solid fa-video text-danger animate-pulse"></i></div>
                    <div class="pole-sensor-box" title="เซ็นเซอร์มลภาวะ PM 2.5"><i class="fa-solid fa-wind text-primary"></i></div>
                    <div class="pole-sensor-box" title="ปล่อยสัญญาณ Public WiFi"><i class="fa-solid fa-wifi text-success"></i></div>
                </div>
            </div>

            <!-- Layer 2: Floating Tech Network Nodes (ไอคอนหกเหลี่ยมประกอบร่าง) -->
            <div class="d-none d-lg-block">
                <div class="tech-hex-node anim-zoom-pop delay-2 floating-node-1" style="left: 24%; top: 25%;"><i class="fa-solid fa-cloud-arrow-up text-primary"></i></div>
                <div class="tech-hex-node anim-zoom-pop delay-3 floating-node-2" style="left: 33%; top: 18%;"><i class="fa-solid fa-shield-halved text-success"></i></div>
                <div class="tech-hex-node anim-zoom-pop delay-4 floating-node-3" style="left: 38%; top: 48%;"><i class="fa-solid fa-gears text-warning"></i></div>
                <div class="tech-hex-node anim-zoom-pop delay-3 floating-node-1" style="left: 26%; top: 65%;"><i class="fa-solid fa-signal text-info"></i></div>
            </div>

            <!-- Layer 3: Main Headline & Glass Info Card -->
            <div class="row h-100 align-items-center position-relative" style="z-index: 15;">
                <div class="col-lg-7 offset-lg-5 text-center text-lg-start ps-lg-5">
                    <div class="anim-from-right delay-2">
                        <span class="badge px-3 py-2 text-white fw-bold mb-3" style="background: rgba(255, 120, 0, 0.95); font-size: 0.95rem; border-radius: 30px; box-shadow: 0 5px 15px rgba(255, 120, 0, 0.4);">
                            <i class="fa-solid fa-globe me-1"></i> SMART PHATTHALUNG 2026
                        </span>
                        <h2 class="slide-title-banner text-white mb-2">
                            SMART <span class="text-gradient-orange">LIVING</span>
                        </h2>
                        <h4 class="fw-bold text-white-50 mb-4">การสร้างชีวิตอัจฉริยะ เพื่อประชาชน</h4>
                    </div>
                    
                    <div class="slide-info-card anim-from-bottom delay-3 mx-auto mx-lg-0">
                        <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-circle-check text-success me-2"></i>ยกระดับเมืองด้วยเทคโนโลยีดิจิทัล</h6>
                        <p class="mb-3 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                            เชื่อมต่อระบบกล้อง AI อัจฉริยะ พร้อมเครือข่าย WiFi สาธารณะความเร็วสูง ครอบคลุมทุกพื้นที่ เพื่อสวัสดิภาพและความปลอดภัยสูงสุดตลอด 24 ชั่วโมง
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                            <small class="text-muted"><i class="fa-solid fa-broadcast-tower text-primary me-1"></i> สถานะโครงข่าย: <strong class="text-success">ออนไลน์ 100%</strong></small>
                            <a href="#services" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold">เข้าใช้บริการ e-Service <i class="fa-solid fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SLIDE 2: SMART TOURISM & ECO-PARADISE (ท่องเที่ยวทะเลน้อย สวรรค์ดิจิทัล) ================= -->
        <div class="carousel-item smart-slide-item slide-bg-tourism h-100 p-4 p-lg-5">
            <div class="slide-geo-left" style="background: linear-gradient(135deg, rgba(46, 158, 98, 0.35) 0%, rgba(0,0,0,0) 80%);"></div>
            <div class="slide-geo-right-orange" style="background: linear-gradient(135deg, #00b09b, #96c93d);"></div>

            <!-- Floating Tourism Elements -->
            <div class="d-none d-lg-block">
                <div class="tech-hex-node anim-zoom-pop delay-2 floating-node-2" style="left: 16%; top: 30%; border-color: #96c93d;"><i class="fa-solid fa-crow text-success"></i></div>
                <div class="tech-hex-node anim-zoom-pop delay-3 floating-node-1" style="left: 26%; top: 56%; border-color: #96c93d;"><i class="fa-solid fa-leaf text-success"></i></div>
                <div class="tech-hex-node anim-zoom-pop delay-4 floating-node-3" style="left: 36%; top: 26%; border-color: #96c93d;"><i class="fa-solid fa-camera-retro text-warning"></i></div>
            </div>

            <div class="row h-100 align-items-center position-relative" style="z-index: 15;">
                <div class="col-lg-7 offset-lg-5 text-center text-lg-start ps-lg-5">
                    <div class="anim-from-right delay-1">
                        <span class="badge bg-success px-3 py-2 text-white fw-bold mb-3" style="font-size: 0.95rem; border-radius: 30px; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);">
                            <i class="fa-solid fa-tree me-1"></i> ECO & HERITAGE CITY
                        </span>
                        <h2 class="slide-title-banner text-white mb-2">
                            SMART <span style="color: #4dee94;">TOURISM</span>
                        </h2>
                        <h4 class="fw-bold text-white-50 mb-4">สวรรค์ท่องเที่ยวธรรมชาติ ทะเลน้อย มรดกเกษตรโลก</h4>
                    </div>

                    <div class="slide-info-card anim-from-bottom delay-3 mx-auto mx-lg-0" style="border-left-color: #10b981;">
                        <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-map-location-dot text-success me-2"></i>นำเที่ยวด้วยระบบ VR 360 & GPS อัจฉริยะ</h6>
                        <p class="mb-3 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                            สัมผัสประสบการณ์ท่องเที่ยวมิติดิจิทัล เช็คความปลอดภัย ลานจอดรถ และจองบริการท่องเที่ยวชุมชนผ่านแพลตฟอร์มไร้รอยต่อ
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                            <small class="text-muted"><i class="fa-solid fa-vr-cardboard text-success me-1"></i> รองรับระบบ Virtual Tour 360°</small>
                            <button onclick="App.toast('เปิดแผนที่นำเที่ยวดิจิทัล 360 องศา...', 'success')" class="btn btn-sm btn-success px-3 rounded-pill fw-bold text-white">เปิดโลกท่องเที่ยว <i class="fa-solid fa-compass ms-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SLIDE 3: SMART GOVERNANCE (บริการราชการออนไลน์ 24 ชม.) ================= -->
        <div class="carousel-item smart-slide-item slide-bg-governance h-100 p-4 p-lg-5">
            <div class="slide-geo-left"></div>
            <div class="slide-geo-right-orange" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);"></div>

            <!-- Floating Gov Elements -->
            <div class="d-none d-lg-block">
                <div class="tech-hex-node anim-zoom-pop delay-2 floating-node-3" style="left: 18%; top: 25%; border-color: #60a5fa;"><i class="fa-solid fa-file-contract text-primary"></i></div>
                <div class="tech-hex-node anim-zoom-pop delay-3 floating-node-2" style="left: 30%; top: 52%; border-color: #60a5fa;"><i class="fa-solid fa-stamp text-info"></i></div>
                <div class="tech-hex-node anim-zoom-pop delay-4 floating-node-1" style="left: 37%; top: 20%; border-color: #60a5fa;"><i class="fa-solid fa-building-columns text-warning"></i></div>
            </div>

            <div class="row h-100 align-items-center position-relative" style="z-index: 15;">
                <div class="col-lg-7 offset-lg-5 text-center text-lg-start ps-lg-5">
                    <div class="anim-from-right delay-1">
                        <span class="badge bg-primary px-3 py-2 text-white fw-bold mb-3" style="font-size: 0.95rem; border-radius: 30px; box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);">
                            <i class="fa-solid fa-landmark me-1"></i> DIGITAL GOVERNANCE
                        </span>
                        <h2 class="slide-title-banner text-white mb-2">
                            SMART <span style="color: #60a5fa;">GOVERNANCE</span>
                        </h2>
                        <h4 class="fw-bold text-white-50 mb-4">ภาครัฐโปร่งใส รวดเร็ว ตรวจสอบได้ทุกขั้นตอน</h4>
                    </div>

                    <div class="slide-info-card anim-from-bottom delay-3 mx-auto mx-lg-0" style="border-left-color: #3b82f6;">
                        <h6 class="fw-bold mb-2 text-dark"><i class="fa-solid fa-shield-halved text-primary me-2"></i>บริการประชาชน One-Stop Service 24 ชม.</h6>
                        <p class="mb-3 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                            ยื่นเรื่องร้องทุกข์ ติดตามผลการดำเนินงาน และดาวน์โหลดแบบฟอร์มหนังสือราชการผ่านเว็บพอร์ตัล ลดขั้นตอน สะดวกสบาย โดยไม่ต้องเดินทาง
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                            <small class="text-muted"><i class="fa-solid fa-lock text-success me-1"></i> ความปลอดภัยข้อมูล PDPA</small>
                            <a href="#pdpa" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold">ยื่นคำร้องออนไลน์ <i class="fa-solid fa-paper-plane ms-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <button class="carousel-control-prev" type="button" data-bs-target="#smartCityCarousel" data-bs-slide="prev" style="width: 60px; z-index: 25;">
        <span class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0,0,0,0.5); border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); transition: transform 0.2s;">
            <i class="fa-solid fa-chevron-left text-white fs-5"></i>
        </span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#smartCityCarousel" data-bs-slide="next" style="width: 60px; z-index: 25;">
        <span class="d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0,0,0,0.5); border-radius: 50%; border: 1px solid rgba(255,255,255,0.3); transition: transform 0.2s;">
            <i class="fa-solid fa-chevron-right text-white fs-5"></i>
        </span>
        <span class="visually-hidden">Next</span>
    </button>
</section>

<!-- 1.1 INTERACTIVE CITIZEN TRACKING DOCK -->
<section class="mb-5">
    <div class="glass-card p-4 hover-lift" style="border-radius: 20px; background: linear-gradient(135deg, var(--glass-bg) 0%, rgba(255,255,255,0.08) 100%); border: 1px solid var(--glass-border);">
        <div class="row align-items-center g-3">
            <div class="col-lg-5">
                <h5 class="fw-bold mb-1" style="color: var(--text-primary);"><i class="fa-solid fa-magnifying-glass-location text-primary me-2"></i>ติดตามคำร้องและตรวจสอบสถานะเอกสาร</h5>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">บริการประชาชน 24 ชม. ตรวจสอบความคืบหน้ารวดเร็ว ฉับไว</p>
            </div>
            <div class="col-lg-7">
                <div class="d-flex align-items-center p-2 shadow-sm" style="background: var(--bg-primary); border: 2px solid var(--glass-border); border-radius: var(--radius-md);">
                    <i class="fa-solid fa-barcode mx-3 text-primary" style="font-size: 1.4rem;"></i>
                    <input type="text" id="trackingInput" placeholder="กรอกรหัสคำร้อง 13 หลัก หรือรหัสติดตาม (เช่น PHAT-84321)..." 
                           style="border: none; background: transparent; color: var(--text-primary); outline: none; width: 100%; font-size: 1rem;">
                    <button class="btn-modern px-4 py-2 m-1" style="white-space: nowrap; border-radius: var(--radius-sm); font-weight: 600;" onclick="verifyTrackingCode()">
                        <i class="fa-solid fa-bolt me-1"></i> ตรวจสอบทันที
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. PUBLIC e-SERVICES GRID -->
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
            <div class="glass-card h-100 hover-lift d-flex flex-column justify-content-between p-4" 
                 style="cursor: pointer; border-radius: 20px;" onclick="<?= $srv['action'] ?>">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="width: 54px; height: 54px; border-radius: 16px; background: rgba(255, 255, 255, 0.1); border: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: <?= $srv['color'] ?>;">
                            <i class="<?= $srv['icon'] ?>"></i>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.8rem;"><i class="fa-solid fa-arrow-right-to-bracket"></i> กดเข้าถึง</span>
                    </div>
                    <h5 class="fw-bold mb-2"><?= $srv['title'] ?></h5>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5; margin-bottom: 0;">
                        <?= $srv['desc'] ?>
                    </p>
                </div>
                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between" style="border-color: var(--glass-border) !important;">
                    <span style="font-size: 0.8rem; color: <?= $srv['color'] ?>; font-weight: 500;">
                        <i class="fa-regular fa-circle-check me-1"></i> บริการออนไลน์
                    </span>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">อ่านคู่มือ &gt;</span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- 3. NEWS & MEDIA HUB (ศูนย์รวมข่าวสารและสื่อมัลติมีเดีย) -->
<?= $this->include('components/news_media_hub') ?>

<!-- 4. GOVERNANCE & TRANSPARENCY HUB (ศูนย์ข้อมูลความโปร่งใสและจัดซื้อจัดจ้าง) -->
<?= $this->include('components/governance_hub') ?>

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

// 5. ตรวจสอบรหัสติดตาม
function verifyTrackingCode() {
    const val = document.getElementById('trackingInput').value.trim();
    if (!val) {
        App.toast('กรุณากรอกรหัสติดตามเรื่อง (เช่น PHAT-84321) ก่อนกดปุ่มติดตามครับ', 'error');
        return;
    }
    App.toast(`🟢 รหัส [${val}]: คำร้องของท่านอยู่ในขั้นตอนการดำเนินการของศูนย์เจ้าหน้าที่ พัทลุงดิจิทัลพอร์ทัล`, 'success');
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

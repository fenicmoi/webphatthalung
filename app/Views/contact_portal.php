<?php
$categories = $categories ?? \App\Models\CitizenContactModel::getCategories();
$districts  = $districts ?? \App\Models\CitizenContactModel::getDistricts();
$siteConfig = $siteConfig ?? (function_exists('get_site_settings') ? get_site_settings() : []);
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="contact-hub-wrapper py-2">

    <!-- 1. REGAL HERO BANNER WITH GRADIENT & MESH AESTHETICS -->
    <div class="contact-hero-banner position-relative overflow-hidden rounded-4 mb-4 shadow-sm text-white">
        <div class="hero-mesh-overlay"></div>
        <div class="hero-cultural-watermark"></div>
        
        <div class="position-relative z-2 p-4 p-md-5">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-emerald-200 text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าแรก</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">ติดต่อราชการ & บริการประชาชน</li>
                </ol>
            </nav>

            <div class="row align-items-center justify-content-between g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3 hero-chip">
                        <i class="fa-solid fa-gem text-warning"></i>
                        <span class="fw-semibold">One-Stop Citizen Service • ศาลากลางจังหวัดพัทลุง</span>
                    </div>
                    <h1 class="display-6 fw-bold mb-2 hero-title">
                        ศูนย์บริการประชาชนและติดต่อราชการ
                    </h1>
                    <p class="hero-subtitle mb-0 opacity-90">
                        ยื่นเรื่องราวร้องทุกข์ ติดต่อประสานงาน และสอบถามข้อมูลภาครัฐ พร้อมระบบแจ้งเตือนเจ้าหน้าที่ผ่าน LINE แบบ Real-time
                    </p>
                </div>

                <div class="col-lg-5 text-lg-end">
                    <div class="hero-track-box p-3.5 rounded-4 shadow-lg text-start d-inline-block w-100" style="max-width: 420px;">
                        <span class="d-block text-white fw-bold mb-1" style="font-size: 0.95rem;">
                            <i class="fa-solid fa-magnifying-glass text-warning me-1.5"></i> ตรวจสอบสถานะคำร้อง (Tracking)
                        </span>
                        <small class="text-emerald-100 d-block mb-2" style="font-size: 0.8rem;">กรอกรหัสคำร้องเพื่อดูผลการดำเนินงานทันที</small>
                        <div class="input-group shadow-xs rounded-pill overflow-hidden">
                            <input type="text" id="heroQuickTrackCode" class="form-control form-control-sm border-0 text-uppercase fw-bold px-3" placeholder="เช่น PTL-260831-XXXX">
                            <button class="btn btn-warning btn-sm px-3.5 fw-bold text-dark" type="button" onclick="heroPerformTrack()">
                                ตรวจสอบ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STREAMLINED 4-CARD QUICK CONTACT CARDS -->
    <div class="row g-3 mb-4">
        <!-- 1. Location -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="contact-info-tile h-100 p-3.5 rounded-4 shadow-xs hover-lift">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="tile-icon-circle grad-emerald text-white">
                        <i class="fa-solid fa-landmark"></i>
                    </div>
                    <div>
                        <span class="tile-label">ที่ตั้งศาลากลาง</span>
                        <h6 class="tile-title mb-0">ศาลากลางจังหวัดพัทลุง</h6>
                    </div>
                </div>
                <p class="tile-desc mb-0">ถ.ราเมศวร์ ต.คูหาสวรรค์ อ.เมืองพัทลุง จ.พัทลุง 93000</p>
            </div>
        </div>

        <!-- 2. Phone -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="contact-info-tile h-100 p-3.5 rounded-4 shadow-xs hover-lift">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="tile-icon-circle grad-blue text-white">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <span class="tile-label">โทรศัพท์กลาง</span>
                        <h6 class="tile-title mb-0"><a href="tel:074613409" class="text-decoration-none text-dark hover-emerald">074-613409</a></h6>
                    </div>
                </div>
                <p class="tile-desc mb-0">วันและเวลาราชการ (จันทร์ - ศุกร์ 08:30 - 16:30 น.)</p>
            </div>
        </div>

        <!-- 3. Email -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="contact-info-tile h-100 p-3.5 rounded-4 shadow-xs hover-lift">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="tile-icon-circle grad-purple text-white">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <span class="tile-label">อีเมลทางการ</span>
                        <h6 class="tile-title mb-0"><a href="mailto:phatthalung@moi.go.th" class="text-decoration-none text-dark hover-emerald">phatthalung@moi.go.th</a></h6>
                    </div>
                </div>
                <p class="tile-desc mb-0">รับ-ส่งหนังสือราชการและติดต่อประสานงาน</p>
            </div>
        </div>

        <!-- 4. Damrongtham 1567 Hotline -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="contact-info-tile tile-highlight h-100 p-3.5 rounded-4 shadow-xs hover-lift">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="tile-icon-circle grad-gold text-white">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div>
                        <span class="tile-label">ศูนย์ดำรงธรรมพัทลุง</span>
                        <h6 class="tile-title mb-0 text-emerald-900">สายด่วนร้องทุกข์ 24 ชม.</h6>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-2 pt-1 border-top border-warning border-opacity-25">
                    <small class="text-muted fw-semibold">โทรฟรีตลอด 24 ชม.</small>
                    <a href="tel:1567" class="btn btn-sm btn-gold-gradient px-3 py-1 rounded-pill fw-bold text-decoration-none shadow-xs">
                        <i class="fa-solid fa-phone me-1"></i> โทร 1567
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MAIN WORKSPACE: SERVICE SELECTOR & EXPANDABLE SLIDE-DOWN FORM -->
    <div class="row g-4">
        
        <!-- Left Column: Citizen Service Hub & Slide-down Form (7 Cols) -->
        <div class="col-12 col-lg-7">
            
            <!-- STEP 1: SERVICE CATEGORY SELECTION CARDS (5 การ์ดบริการสีสันสดใส) -->
            <div class="service-hub-card p-4 rounded-4 shadow-sm bg-white border mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div>
                        <h4 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 1.15rem;">
                            <span class="service-step-badge">1</span>
                            <span>เลือกประเภทบริการที่คุณต้องการยื่นเรื่อง</span>
                        </h4>
                        <small class="text-muted">คลิกเลือกหมวดหมู่ที่ตรงกับความต้องการเพื่อเปิดฟอร์มยื่นเรื่อง</small>
                    </div>
                </div>

                <div class="service-cards-grid">
                    <?php 
                    $catMeta = [
                        'complaint' => [
                            'grad' => 'grad-rose',
                            'badge' => 'ศูนย์ดำรงธรรม',
                            'desc'  => 'รับเรื่องร้องเรียน ความเดือดร้อน และไกล่เกลี่ยข้อพิพาท'
                        ],
                        'assistance' => [
                            'grad' => 'grad-amber',
                            'badge' => 'ปภ. & บรรเทาทุกข์',
                            'desc'  => 'สาธารณภัย น้ำท่วม วาตภัย และขอรับความช่วยเหลือเร่งด่วน'
                        ],
                        'service' => [
                            'grad' => 'grad-emerald',
                            'badge' => 'e-Service ภาครัฐ',
                            'desc'  => 'ขอรับบริการออนไลน์ หนังสือรับรอง และประสานงานหน่วยงาน'
                        ],
                        'general' => [
                            'grad' => 'grad-blue',
                            'badge' => 'สอบถามข้อมูล',
                            'desc'  => 'ข้อมูลข่าวสารราชการ การท่องเที่ยว และการติดต่อทั่วไป'
                        ],
                        'whistleblow' => [
                            'grad' => 'grad-purple',
                            'badge' => 'แจ้งเบาะแสลับ',
                            'desc'  => 'แจ้งเบาะแสการกระทำผิด และรักษาความลับผู้แจ้ง 100%'
                        ],
                    ];

                    $catIndex = 0;
                    foreach ($categories as $catKey => $cat): 
                        $catIndex++;
                        $meta = $catMeta[$catKey] ?? [
                            'grad'  => 'grad-emerald',
                            'badge' => 'บริการทั่วไป',
                            'desc'  => 'ติดต่อประสานงานราชการจังหวัดพัทลุง'
                        ];
                        $isDefaultActive = ($catIndex === 1);
                    ?>
                        <div class="service-action-card <?= $isDefaultActive ? 'active' : '' ?>" 
                             data-cat-key="<?= esc($catKey) ?>" 
                             data-cat-name="<?= esc($cat['name']) ?>"
                             data-cat-grad="<?= esc($meta['grad']) ?>"
                             onclick="selectServiceCategory('<?= esc($catKey) ?>', '<?= esc($cat['name']) ?>', '<?= esc($meta['grad']) ?>')">
                            
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="service-card-icon <?= esc($meta['grad']) ?> text-white">
                                    <i class="fa-solid <?= esc($cat['icon']) ?>"></i>
                                </div>
                                <span class="badge badge-service-tag"><?= esc($meta['badge']) ?></span>
                            </div>

                            <h6 class="service-card-title fw-bold text-dark mb-1"><?= esc($cat['name']) ?></h6>
                            <p class="service-card-desc text-muted small mb-2"><?= esc($meta['desc']) ?></p>

                            <div class="service-card-cta d-flex align-items-center justify-content-between pt-2 border-top">
                                <span class="cta-text fw-semibold small">
                                    <span class="active-indicator"><i class="fa-solid fa-circle-check text-success me-1"></i>กำลังเลือกหมวดนี้</span>
                                    <span class="default-indicator">คลิกยื่นเรื่องนี้ <i class="fa-solid fa-arrow-right ms-1"></i></span>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- STEP 2: EXPANDABLE SLIDE-DOWN FORM (ฟอร์มคลี่เปิดลงมาอย่างนุ่มนวล) -->
            <div id="slideDownFormSection" class="vibrant-form-wrapper rounded-4 shadow-sm overflow-hidden mb-4">
                
                <!-- Dynamic Form Header Banner matching selected Category -->
                <div id="dynamicFormHeader" class="form-gradient-header grad-rose p-4 text-white position-relative">
                    <div class="header-sparkle-bg"></div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 position-relative z-2">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="form-header-badge-icon">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </span>
                            <div>
                                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-0.5 small mb-1">ขั้นตอนที่ 2 : กรอกข้อมูล</span>
                                <h4 class="fw-bold mb-0 text-white" id="displaySelectedCategoryTitle" style="font-size: 1.25rem;">
                                    ยื่นเรื่อง: เรื่องร้องเรียน / ร้องทุกข์ทั่วไป
                                </h4>
                            </div>
                        </div>
                        <span class="badge badge-line-vibrant rounded-pill px-3 py-1.5 d-inline-flex align-items-center gap-1.5">
                            <i class="fa-brands fa-line fs-6"></i>
                            <span>LINE แจ้งเตือนเจ้าหน้าที่ทันที</span>
                        </span>
                    </div>
                </div>

                <div class="p-4 p-md-4.5 bg-white">
                    <!-- Main Form -->
                    <form id="publicContactForm" onsubmit="handleContactSubmit(event)" enctype="multipart/form-data">
                        
                        <!-- Hidden category input synced with top selection -->
                        <input type="hidden" name="category" id="hiddenCategoryInput" value="complaint">

                        <!-- SECTION A: ข้อมูลผู้ติดต่อ (Soft Emerald Tint Container) -->
                        <div class="form-section-card bg-soft-emerald p-3.5 rounded-3 mb-3.5 border">
                            <div class="section-title-sm text-emerald mb-2.5 d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-user-shield"></i>
                                <span>ข้อมูลผู้ยื่นเรื่อง & พื้นที่เกี่ยวข้อง</span>
                            </div>
                            
                            <div class="row g-2.5">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small mb-1">
                                        ชื่อ-นามสกุล <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-user field-icon text-emerald"></i>
                                        <input type="text" name="full_name" class="form-control form-control-modern ps-5" required placeholder="เช่น นายสมชาย ใจมั่นคง" maxlength="255">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small mb-1">
                                        หมายเลขโทรศัพท์ <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-phone field-icon text-emerald"></i>
                                        <input type="tel" name="phone" class="form-control form-control-modern ps-5" required placeholder="เช่น 081-234-5678" maxlength="50">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small mb-1">
                                        พื้นที่ / อำเภอที่เกิดเหตุ <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-map-pin field-icon text-emerald"></i>
                                        <select name="district" class="form-select form-control-modern ps-5" required>
                                            <?php foreach ($districts as $d): ?>
                                                <option value="<?= esc($d) ?>"><?= esc($d) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark small mb-1">
                                        อีเมล <small class="text-muted fw-normal">(สำหรับรับสำเนารหัสคำร้อง - ไม่ระบุก็ได้)</small>
                                    </label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-envelope field-icon text-emerald"></i>
                                        <input type="email" name="email" class="form-control form-control-modern ps-5" placeholder="citizen@example.com">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION B: รายละเอียดเรื่องที่ติดต่อ (Soft Warm Tint Container) -->
                        <div class="form-section-card bg-soft-warm p-3.5 rounded-3 mb-3.5 border">
                            <div class="section-title-sm text-amber-900 mb-2.5 d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-file-lines text-warning"></i>
                                <span>หัวข้อเรื่องและรายละเอียดข้อเท็จจริง</span>
                            </div>

                            <div class="row g-2.5">
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark small mb-1">
                                        หัวข้อเรื่อง / ประเด็นติดต่อ <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-with-icon">
                                        <i class="fa-solid fa-heading field-icon text-warning"></i>
                                        <input type="text" name="subject" class="form-control form-control-modern ps-5" required placeholder="ระบุหัวข้อเรื่องอย่างกระชับ เช่น ขอซ่อมแซมถนนสายบ้านควน, ขอความช่วยเหลือ..." maxlength="255">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark small mb-1">
                                        รายละเอียดข้อเท็จจริง / ข้อมูลประกอบ <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="message" class="form-control form-control-modern p-3" rows="4" required placeholder="ระบุข้อมูล วัน เวลา สถานที่เกิดเหตุ หรือสิ่งที่ต้องการให้หน่วยงานดำเนินการช่วยเหลืออย่างละเอียด..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION C: แนบหลักฐาน & PDPA -->
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa-solid fa-paperclip text-emerald me-1"></i> แนบภาพถ่ายหรือเอกสารประกอบ <small class="text-muted fw-normal">(JPG, PNG, PDF สูงสุด 10MB)</small>
                                </label>
                                <div class="file-upload-vibrant p-3 rounded-3 text-center position-relative">
                                    <i class="fa-solid fa-cloud-arrow-up text-emerald fs-2 mb-1"></i>
                                    <div class="small fw-bold text-dark">คลิกเพื่อเลือกไฟล์ หรือลากไฟล์มาวางที่นี่</div>
                                    <input type="file" name="attachment" class="form-control mt-2" accept="image/jpeg,image/png,image/webp,application/pdf">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="pdpa-notice-vibrant p-3 rounded-3 d-flex align-items-start gap-2.5">
                                    <input class="form-check-input mt-1 flex-shrink-0" type="checkbox" id="pdpaAgreement" required>
                                    <label class="form-check-label text-muted small lh-base" for="pdpaAgreement">
                                        ข้าพเจ้ายินยอมให้จังหวัดพัทลุงจัดเก็บและประมวลผลข้อมูลส่วนบุคคลตาม <strong>พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)</strong> เพื่อวัตถุประสงค์ในการประสานงาน แก้ไขปัญหา และแจ้งผลการดำเนินงานเท่านั้น
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" id="btnSubmitContact" class="btn btn-vibrant-submit w-100 py-3 rounded-pill fw-bold shadow-md">
                            <i class="fa-solid fa-paper-plane me-2"></i>ส่งเรื่องติดต่อเจ้าหน้าที่ (LINE Real-time)
                        </button>

                    </form>

                    <!-- Success Confirmation Box (Hidden by default) -->
                    <div id="submissionSuccessCard" class="d-none text-center py-5">
                        <div class="success-icon-badge mb-3 mx-auto">
                            <i class="fa-solid fa-circle-check text-success"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-2">ส่งเรื่องติดต่อเรียบร้อยแล้ว!</h3>
                        <p class="text-muted small mb-4">ระบบได้ส่งข้อความแจ้งเตือนไปยัง LINE เจ้าหน้าที่และบันทึกข้อมูลเข้าสู่ระบบเรียบร้อยแล้ว</p>
                        
                        <div class="tracking-ticket-box p-4 rounded-4 text-center mb-4 d-inline-block shadow-sm">
                            <span class="text-muted small text-uppercase fw-semibold">รหัสติดตามสถานะของคุณ</span>
                            <div class="display-6 fw-bold text-emerald my-1 font-monospace" id="displayTrackingCode">PTL-XXXXXX</div>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 mt-1" onclick="copyTrackingCode()">
                                <i class="fa-solid fa-copy me-1"></i>คัดลอกรหัส
                            </button>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-emerald-gradient text-white rounded-pill px-4 py-2 fw-bold" onclick="checkStatusFromCode()">
                                <i class="fa-solid fa-clock-rotate-left me-1"></i>ตรวจสถานะทันที
                            </button>
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2" onclick="resetContactForm()">
                                <i class="fa-solid fa-rotate me-1"></i>ส่งเรื่องอื่นเพิ่มเติม
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Right Column: Map & Useful Contacts (5 Cols) -->
        <div class="col-12 col-lg-5 d-flex flex-column gap-3.5">
            
            <!-- Map Card with Vibrant Accent -->
            <div class="portal-card p-3.5 p-md-4 rounded-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                        <span class="sidebar-badge-icon grad-emerald text-white"><i class="fa-solid fa-map-location-dot"></i></span>
                        <span>แผนที่ศาลากลางจังหวัดพัทลุง</span>
                    </h5>
                    <a href="https://maps.app.goo.gl/y3iHcrVp3hZ3kMsm9" target="_blank" class="small text-decoration-none text-emerald fw-bold hover-underline">
                        Google Maps <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size: 0.75rem;"></i>
                    </a>
                </div>

                <div class="ratio ratio-16x9 rounded-3 overflow-hidden border mb-2.5 shadow-xs">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.8824147770857!2d100.0722359758778!3d7.61661609239893!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x304d2e858db142d7%3A0xe21f5791775a7206!2z4Lio4Liy4Lil4Liy4LiB4Lil4Liy4LiH4LiI4Lix4LiH4Lir4Lin4Lix4LiU4Lie4Lix4LiX4LiX4Lil4Li44LiH!5e0!3m2!1sth!2sth!4v1700000000000!5m2!1sth!2sth" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="p-2.5 rounded-3 bg-light d-flex align-items-center gap-2 text-muted small">
                    <i class="fa-solid fa-clock text-emerald fs-6"></i>
                    <span><strong>วันและเวลาทำการ:</strong> จันทร์ - ศุกร์ 08:30 - 16:30 น. (เว้นวันหยุดราชการ)</span>
                </div>
            </div>

            <!-- Emergency & Hotlines Card with Glowing Number Badges -->
            <div class="portal-card p-3.5 p-md-4 rounded-4 shadow-sm">
                <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                    <span class="sidebar-badge-icon grad-rose text-white"><i class="fa-solid fa-shield-halved"></i></span>
                    <span>สายด่วนฉุกเฉินและสาธารณภัย</span>
                </h5>

                <div class="d-flex flex-column gap-2.5">
                    <!-- 1567 -->
                    <div class="hotline-card-row d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="hotline-badge-num grad-gold text-white">1567</div>
                            <div>
                                <span class="fw-bold text-dark small d-block">ศูนย์ดำรงธรรมจังหวัดพัทลุง</span>
                                <small class="text-muted" style="font-size: 0.75rem;">รับเรื่องร้องเรียน / ร้องทุกข์ไกล่เกลี่ย 24 ชม.</small>
                            </div>
                        </div>
                        <a href="tel:1567" class="btn btn-sm btn-call-round rounded-pill px-3 py-1 fw-bold text-decoration-none">
                            <i class="fa-solid fa-phone fs-7 me-1"></i>โทร
                        </a>
                    </div>

                    <!-- 1784 -->
                    <div class="hotline-card-row d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="hotline-badge-num grad-amber text-white">1784</div>
                            <div>
                                <span class="fw-bold text-dark small d-block">สนง. ปภ. จังหวัดพัทลุง</span>
                                <small class="text-muted" style="font-size: 0.75rem;">สาธารณภัย / น้ำท่วม / วาตภัย</small>
                            </div>
                        </div>
                        <a href="tel:1784" class="btn btn-sm btn-call-round rounded-pill px-3 py-1 fw-bold text-decoration-none">
                            <i class="fa-solid fa-phone fs-7 me-1"></i>โทร
                        </a>
                    </div>

                    <!-- 1669 -->
                    <div class="hotline-card-row d-flex align-items-center justify-content-between p-2.5 rounded-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="hotline-badge-num grad-emerald text-white">1669</div>
                            <div>
                                <span class="fw-bold text-dark small d-block">กู้ชีพ-การแพทย์ฉุกเฉิน</span>
                                <small class="text-muted" style="font-size: 0.75rem;">อุบัติเหตุ & เจ็บป่วยฉุกเฉิน 24 ชม.</small>
                            </div>
                        </div>
                        <a href="tel:1669" class="btn btn-sm btn-call-round rounded-pill px-3 py-1 fw-bold text-decoration-none">
                            <i class="fa-solid fa-phone fs-7 me-1"></i>โทร
                        </a>
                    </div>
                </div>
            </div>

            <!-- Social & AI Assistant -->
            <div class="portal-card p-3 rounded-4 shadow-sm">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="https://www.facebook.com/phatthalungPR" target="_blank" class="channel-widget-btn d-flex align-items-center gap-2.5 p-2.5 rounded-3 text-decoration-none h-100 hover-lift">
                            <div class="widget-icon-wrap grad-blue text-white">
                                <i class="fa-brands fa-facebook-f"></i>
                            </div>
                            <div class="overflow-hidden">
                                <span class="fw-bold small d-block text-truncate text-dark">Facebook สปชส.</span>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">ข่าวสารราชการสด</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="javascript:void(0)" onclick="NoraAssistant.openChat()" class="channel-widget-btn d-flex align-items-center gap-2.5 p-2.5 rounded-3 text-decoration-none h-100 hover-lift">
                            <div class="widget-icon-wrap bg-white border border-success border-opacity-25 text-white overflow-hidden p-0.5 shadow-xs">
                                <img src="<?= base_url('assets/images/nora_avatar.png') ?>" alt="น้องโนรา AI" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                            <div class="overflow-hidden">
                                <span class="fw-bold small d-block text-truncate text-dark">ถามน้องโนรา AI</span>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">แชตตอบ 24 ชม.</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Modal: Real-time Status Tracking -->
<div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-xl overflow-hidden">
            <div class="modal-header modal-header-regal text-white p-4">
                <div>
                    <h5 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2" id="trackingModalLabel">
                        <i class="fa-solid fa-magnifying-glass text-warning"></i>
                        <span>ตรวจสอบสถานะคำร้องและข้อร้องเรียน</span>
                    </h5>
                    <small class="text-emerald-100" style="font-size: 0.85rem;">ระบบติดตามความคืบหน้าการดำเนินงานของเจ้าหน้าที่ (Tracking ID)</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-4.5" style="background: #fafcfb;">
                <div class="input-group input-group-lg mb-3.5 shadow-sm rounded-pill overflow-hidden border">
                    <input type="text" id="trackInputCode" class="form-control border-0 fw-bold fs-6 text-uppercase px-4" placeholder="ระบุรหัสติดตาม เช่น PTL-260831-XXXX" style="letter-spacing: 0.5px;">
                    <button class="btn btn-vibrant-submit px-4 fw-bold" type="button" onclick="performTracking()">
                        <i class="fa-solid fa-search me-1.5"></i>ค้นหาคำร้อง
                    </button>
                </div>

                <!-- Live Tracking Result Container -->
                <div id="trackResultBox" class="d-none">
                    <!-- Dynamic Content populated via JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VIBRANT & GRADIENT ENRICHED STYLES -->
<style>
:root {
    --ptl-emerald: #047857;
    --ptl-emerald-dark: #064e3b;
    --ptl-emerald-light: #10b981;
    --ptl-gold: #d97706;
    --ptl-border: #e2ede6;
}

.contact-hub-wrapper {
    max-width: 1280px;
    margin: 0 auto;
}

.text-emerald {
    color: var(--ptl-emerald) !important;
}

.text-emerald-200 {
    color: #a7f3d0 !important;
}

.text-emerald-100 {
    color: #d1fae5 !important;
}

.text-emerald-900 {
    color: #064e3b !important;
}

.text-amber-900 {
    color: #78350f !important;
}

.hover-emerald:hover {
    color: var(--ptl-emerald) !important;
}

/* Gradients */
.grad-emerald {
    background: linear-gradient(135deg, #059669 0%, #047857 50%, #064e3b 100%) !important;
}

.grad-blue {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%) !important;
}

.grad-purple {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 50%, #6d28d9 100%) !important;
}

.grad-gold {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%) !important;
}

.grad-amber {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%) !important;
}

.grad-rose {
    background: linear-gradient(135deg, #f43f5e 0%, #e11d48 50%, #be123c 100%) !important;
}

/* 1. Hero Banner */
.contact-hero-banner {
    background: linear-gradient(135deg, #022c22 0%, #064e3b 40%, #047857 80%, #0d9488 100%);
    min-height: 220px;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.hero-mesh-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 85% 25%, rgba(251, 191, 36, 0.25) 0%, transparent 55%),
                radial-gradient(circle at 15% 85%, rgba(52, 211, 153, 0.3) 0%, transparent 50%);
    pointer-events: none;
}

.hero-cultural-watermark {
    position: absolute;
    right: -20px;
    bottom: -30px;
    width: 320px;
    height: 200px;
    background: url('<?= base_url('assets/images/banners/phatthalung_identity_bg.jpg') ?>') center right / contain no-repeat;
    opacity: 0.14;
    pointer-events: none;
    filter: invert(1);
}

.hero-chip {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
    color: #ffffff;
    font-size: 0.82rem;
}

.hero-track-box {
    background: rgba(2, 44, 34, 0.7);
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(12px);
}

/* 2. Quick Contact Tiles */
.contact-info-tile {
    background: #ffffff;
    border: 1px solid var(--ptl-border);
    transition: all 0.25s ease;
}

.contact-info-tile:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(4, 120, 87, 0.1);
    border-color: #a7f3d0;
}

.contact-info-tile.tile-highlight {
    background: linear-gradient(180deg, #ffffff 0%, #fefce8 100%);
    border-color: #fde68a;
}

.tile-icon-circle {
    width: 46px;
    height: 46px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.tile-label {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 600;
}

.tile-title {
    font-size: 0.95rem;
    font-weight: 700;
}

.tile-desc {
    font-size: 0.82rem;
    color: #64748b;
    line-height: 1.45;
}

.btn-gold-gradient {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #ffffff !important;
    font-size: 0.8rem;
    border: none;
    transition: all 0.2s ease;
}

.btn-gold-gradient:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: scale(1.05);
}

/* 3. SERVICE HUB CARDS (STEP 1) */
.service-hub-card {
    border: 1px solid var(--ptl-border);
}

.service-step-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #047857;
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 700;
}

.service-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 0.85rem;
}

.service-action-card {
    background: #fbfdfc;
    border: 1.5px solid #e2ede6;
    border-radius: 14px;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.service-action-card:hover {
    background: #ffffff;
    border-color: #86efac;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(4, 120, 87, 0.1);
}

.service-action-card.active {
    background: #ffffff;
    border-color: #059669;
    box-shadow: 0 8px 24px rgba(5, 150, 105, 0.18), 0 0 0 2px #10b981;
}

.service-card-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

.badge-service-tag {
    background: #f1f5f9;
    color: #475569;
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.3rem 0.55rem;
    border-radius: 6px;
}

.service-card-title {
    font-size: 0.92rem;
    line-height: 1.35;
}

.service-card-desc {
    font-size: 0.78rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.service-action-card .active-indicator {
    display: none;
    color: #047857;
}

.service-action-card .default-indicator {
    display: inline-block;
    color: #64748b;
}

.service-action-card.active .active-indicator {
    display: inline-block;
}

.service-action-card.active .default-indicator {
    display: none;
}

/* 4. EXPANDABLE SLIDE-DOWN FORM */
.vibrant-form-wrapper {
    background: #ffffff;
    border: 1px solid var(--ptl-border);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.form-gradient-header {
    transition: background 0.4s ease;
}

.header-sparkle-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 90% 20%, rgba(251, 191, 36, 0.25) 0%, transparent 40%);
    pointer-events: none;
}

.form-header-badge-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: #ffffff;
}

.badge-line-vibrant {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.35);
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Section Containers with Soft Gradients */
.form-section-card.bg-soft-emerald {
    background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 100%);
    border-color: #d1fae5 !important;
}

.form-section-card.bg-soft-warm {
    background: linear-gradient(135deg, #fffbeb 0%, #f8fafc 100%);
    border-color: #fef3c7 !important;
}

.section-title-sm {
    font-size: 0.88rem;
    font-weight: 700;
}

/* Modern Input Fields */
.input-with-icon {
    position: relative;
}

.input-with-icon .field-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.95rem;
    pointer-events: none;
}

.form-control-modern {
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    font-size: 0.95rem;
    padding: 0.6rem 0.9rem;
    color: #1e293b;
    transition: all 0.2s ease;
}

.form-control-modern:focus {
    border-color: #059669;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
}

/* File Upload Dropzone */
.file-upload-vibrant {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfeff 100%);
    border: 2px dashed #6ee7b7;
    transition: all 0.2s ease;
}

.file-upload-vibrant:hover {
    background: #ecfdf5;
    border-color: #059669;
}

/* PDPA Notice Box */
.pdpa-notice-vibrant {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

/* Vibrant Submit Button */
.btn-vibrant-submit {
    background: linear-gradient(135deg, #059669 0%, #047857 40%, #0d9488 100%);
    color: #ffffff !important;
    border: none;
    font-size: 1.05rem;
    letter-spacing: 0.3px;
    position: relative;
    overflow: hidden;
    transition: all 0.25s ease;
}

.btn-vibrant-submit:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 40%, #047857 100%);
    box-shadow: 0 8px 25px rgba(5, 150, 105, 0.35);
    transform: translateY(-2px);
}

/* Sidebar Widgets */
.portal-card {
    background: #ffffff;
    border: 1px solid var(--ptl-border);
}

.sidebar-badge-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.hotline-card-row {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.hotline-card-row:hover {
    background: #ffffff;
    border-color: #86efac;
    box-shadow: 0 4px 12px rgba(4, 120, 87, 0.08);
    transform: translateX(2px);
}

.hotline-badge-num {
    width: 46px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.88rem;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.btn-call-round {
    background: #ecfdf5;
    color: #047857 !important;
    border: 1px solid #a7f3d0;
    font-size: 0.78rem;
    transition: all 0.2s ease;
}

.btn-call-round:hover {
    background: #047857;
    color: #ffffff !important;
}

.channel-widget-btn {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.channel-widget-btn:hover {
    background: #ffffff;
    border-color: #86efac;
    box-shadow: 0 4px 14px rgba(4, 120, 87, 0.1);
}

.widget-icon-wrap {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.modal-header-regal {
    background: linear-gradient(135deg, #022c22 0%, #064e3b 60%, #047857 100%);
}

.success-icon-badge {
    font-size: 4rem;
}

.tracking-ticket-box {
    background: #f0fdf4;
    border: 2px dashed #86efac;
    min-width: 320px;
}

.btn-emerald-gradient {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

/* Dark Mode Overrides */
[data-theme="dark"] .contact-info-tile,
[data-theme="dark"] .service-hub-card,
[data-theme="dark"] .service-action-card,
[data-theme="dark"] .vibrant-form-wrapper,
[data-theme="dark"] .vibrant-form-wrapper > div.bg-white,
[data-theme="dark"] .portal-card,
[data-theme="dark"] .hotline-card-row,
[data-theme="dark"] .channel-widget-btn {
    background: #1e293b !important;
    border-color: rgba(255, 255, 255, 0.1) !important;
    color: #f1f5f9;
}

[data-theme="dark"] .form-control-modern {
    background: #0f172a;
    border-color: rgba(255, 255, 255, 0.15);
    color: #f1f5f9;
}

[data-theme="dark"] .form-section-card.bg-soft-emerald,
[data-theme="dark"] .form-section-card.bg-soft-warm,
[data-theme="dark"] .file-upload-vibrant,
[data-theme="dark"] .pdpa-notice-vibrant {
    background: rgba(15, 23, 42, 0.6) !important;
    border-color: rgba(255, 255, 255, 0.1) !important;
}

[data-theme="dark"] .service-card-title {
    color: #f8fafc !important;
}
</style>

<!-- JAVASCRIPT LOGIC -->
<script>
let lastCreatedTrackingCode = '';

// Category Selection & Slide-down Form Dynamic Header
function selectServiceCategory(catKey, catName, catGrad) {
    // 1. Update UI Active State on Cards
    document.querySelectorAll('.service-action-card').forEach(card => card.classList.remove('active'));
    const selectedCard = document.querySelector(`.service-action-card[data-cat-key="${catKey}"]`);
    if (selectedCard) {
        selectedCard.classList.add('active');
    }

    // 2. Update Hidden Form Value
    document.getElementById('hiddenCategoryInput').value = catKey;

    // 3. Update Dynamic Form Header Title & Gradient
    const header = document.getElementById('dynamicFormHeader');
    header.className = 'form-gradient-header p-4 text-white position-relative ' + catGrad;
    document.getElementById('displaySelectedCategoryTitle').innerText = 'ยื่นเรื่อง: ' + catName;

    // 4. Smooth Scroll to the Form
    const formSection = document.getElementById('slideDownFormSection');
    formSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function heroPerformTrack() {
    const code = document.getElementById('heroQuickTrackCode').value.trim();
    if (!code) {
        App.toast('กรุณาระบุรหัสคำร้อง เช่น PTL-260831-XXXX', 'info');
        return;
    }
    document.getElementById('trackInputCode').value = code;
    const modal = new bootstrap.Modal(document.getElementById('trackingModal'));
    modal.show();
    performTracking();
}

async function handleContactSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('publicContactForm');
    const btn = document.getElementById('btnSubmitContact');
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังบันทึกและส่งแจ้งเตือน...';

    const formData = new FormData(form);

    try {
        const res = await fetch('<?= base_url('api/contact/submit') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const json = await res.json();

        if (json.status === 'success') {
            lastCreatedTrackingCode = json.tracking_code;
            document.getElementById('displayTrackingCode').innerText = json.tracking_code;
            
            form.classList.add('d-none');
            document.getElementById('submissionSuccessCard').classList.remove('d-none');
            App.toast('ส่งเรื่องและแจ้งเตือนเข้า LINE เจ้าหน้าที่เรียบร้อยแล้ว', 'success');
        } else {
            App.toast(json.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>ส่งเรื่องติดต่อเจ้าหน้าที่ (LINE Real-time)';
        }
    } catch (err) {
        console.error(err);
        App.toast('การเชื่อมต่อขัดข้อง กรุณาลองใหม่อีกครั้ง', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>ส่งเรื่องติดต่อเจ้าหน้าที่ (LINE Real-time)';
    }
}

function copyTrackingCode() {
    if (!lastCreatedTrackingCode) return;
    navigator.clipboard.writeText(lastCreatedTrackingCode).then(() => {
        App.toast('คัดลอกรหัสติดตามแล้ว: ' + lastCreatedTrackingCode, 'info');
    });
}

function resetContactForm() {
    document.getElementById('publicContactForm').reset();
    document.getElementById('publicContactForm').classList.remove('d-none');
    document.getElementById('submissionSuccessCard').classList.add('d-none');
    const btn = document.getElementById('btnSubmitContact');
    btn.disabled = false;
    btn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>ส่งเรื่องติดต่อเจ้าหน้าที่ (LINE Real-time)';
}

function checkStatusFromCode() {
    if (lastCreatedTrackingCode) {
        document.getElementById('trackInputCode').value = lastCreatedTrackingCode;
        const modal = new bootstrap.Modal(document.getElementById('trackingModal'));
        modal.show();
        performTracking();
    }
}

async function performTracking() {
    const code = document.getElementById('trackInputCode').value.trim();
    if (!code) {
        App.toast('กรุณาระบุรหัสติดตาม', 'info');
        return;
    }

    const box = document.getElementById('trackResultBox');
    box.classList.remove('d-none');
    box.innerHTML = '<div class="text-center py-4"><i class="fa-solid fa-spinner fa-spin fs-3 text-success"></i><p class="text-muted small mt-2">กำลังค้นหาข้อมูล...</p></div>';

    try {
        const res = await fetch(`<?= base_url('api/contact/track') ?>?code=${encodeURIComponent(code)}`);
        const json = await res.json();

        if (json.status === 'success') {
            const badgeClass = json.status_info.badge || 'bg-secondary text-white';
            const iconClass = json.status_info.icon || 'fa-info-circle';

            box.innerHTML = `
                <div class="card border-0 rounded-4 p-4 shadow-sm" style="background: #ffffff; border: 1.5px solid #d8e8dd !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2.5">
                        <div>
                            <span class="badge ${badgeClass} fs-6 px-3 py-1.5 rounded-pill mb-1">
                                <i class="fa-solid ${iconClass} me-1"></i> ${json.status_info.name}
                            </span>
                            <div class="text-muted small">รหัสคำร้อง: <strong class="text-emerald fs-6">${json.tracking_code}</strong></div>
                        </div>
                        <div class="text-end text-muted small" style="font-size: 0.78rem;">
                            <div>ยื่นเรื่องเมื่อ: <strong>${json.created_at}</strong></div>
                            <div>อัปเดต: <strong>${json.updated_at}</strong></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">หัวข้อเรื่อง:</div>
                        <div class="fw-bold text-dark fs-5">${json.subject}</div>
                    </div>

                    <div class="row g-2 small text-muted mb-3">
                        <div class="col-sm-6">
                            <span>ประเภท: <strong class="text-dark">${json.category_name}</strong></span>
                        </div>
                        <div class="col-sm-6">
                            <span>พื้นที่: <strong class="text-dark">อ.${json.district} จ.พัทลุง</strong></span>
                        </div>
                    </div>

                    <div class="p-3 rounded-3" style="background: #f4f9f5; border: 1px solid #dbeae0;">
                        <div class="fw-bold text-emerald small mb-1"><i class="fa-solid fa-reply me-1"></i>บันทึก/ผลการดำเนินงานของเจ้าหน้าที่:</div>
                        <p class="mb-0 small text-dark lh-base">${json.officer_note || 'อยู่ระหว่างการตรวจสอบและประสานงานหน่วยงานที่เกี่ยวข้อง'}</p>
                    </div>
                </div>
            `;
        } else {
            box.innerHTML = `
                <div class="alert alert-warning text-center rounded-4 py-3.5 mb-0">
                    <i class="fa-solid fa-triangle-exclamation fs-3 text-warning mb-1.5"></i>
                    <h6 class="fw-bold mb-1">${json.message}</h6>
                    <small class="text-muted">โปรดตรวจสอบรหัสติดตามให้ถูกต้อง เช่น PTL-260831-XXXX</small>
                </div>
            `;
        }
    } catch (e) {
        console.error(e);
        box.innerHTML = '<div class="alert alert-danger text-center">เกิดข้อผิดพลาดในการดึงข้อมูล</div>';
    }
}
</script>
<?= $this->endSection() ?>

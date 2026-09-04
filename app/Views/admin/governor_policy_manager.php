<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
$govName = $governor['name'] ?? 'นายสุจินต์ วาจากิจ';
$govPosition = $governor['position'] ?? 'ผู้ว่าราชการจังหวัดพัทลุง';
$govQuote = !empty($governor['quote']) ? $governor['quote'] : 'รักเมืองลุง สร้างเมืองลุง ไปด้วยกัน ทำงานร่วมกัน ด้วยความสามัคคี การมีส่วนร่วม และการรับฟังความคิดเห็นของประชาชนในพื้นที่ เพื่อสร้างความเข้มแข็งจากฐานราก และยกระดับจังหวัดพัทลุง ให้มีความเจริญก้าวหน้าอย่างมั่นคง และยั่งยืนต่อไป';
$govPhoto = !empty($governor['photo']) ? (strpos((string)$governor['photo'], 'http') === 0 ? $governor['photo'] : base_url($governor['photo'])) : base_url('uploads/executives/exec_1787543315_1787543315_5570c503c25f1ee9f002.jpg');
$govHistory = $governor['history'] ?? '';
$visionTicker = site_text('provincial_vision_ticker', 'เมืองแห่งความยั่งยืนทางเศรษฐกิจ สังคม ความมั่นคง ทรัพยากรธรรมชาติและสิ่งแวดล้อม (Sustainability Phatthalung)', 'วิสัยทัศน์บนแถบประกาศ');
?>

<div class="container-fluid p-0">
    
    <!-- Top Action Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>" class="text-decoration-none">แผงควบคุม</a></li>
                    <li class="breadcrumb-item active" aria-current="page">จัดการนโยบายผู้ว่าราชการจังหวัด</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <span class="p-2 rounded-3 text-white d-inline-flex align-items-center justify-content-center shadow-xs" style="background: linear-gradient(135deg, #047857 0%, #065f46 100%); width: 40px; height: 40px;">
                    <i class="fa-solid fa-user-gear fs-5"></i>
                </span>
                <span>ระบบจัดการนโยบายผู้ว่าราชการจังหวัด</span>
            </h4>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-medium d-inline-flex align-items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>ดูหน้าแรกของเว็บ</span>
            </a>
            <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm hover-lift" onclick="submitGovernorPolicy()">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span id="btnSaveText">บันทึกการเปลี่ยนแปลง</span>
            </button>
        </div>
    </div>

    <!-- Alert Banner / Hint -->
    <div class="alert bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-3 mb-4 d-flex align-items-center gap-3">
        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <div class="small text-dark">
            <strong>ระบบอัปเดตแบบเรียลไทม์:</strong> เมื่อท่านแก้ไขและบันทึกข้อมูลในหน้านี้ ข้อมูลรูปภาพ คำแถลงนโยบาย และแถบประกาศวิสัยทัศน์จะแสดงผลที่ <strong>Section นโยบายผู้ว่าราชการจังหวัด</strong> บนหน้าหลักทันที
        </div>
    </div>

    <form id="governorPolicyForm" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="row g-4">
            
            <!-- Left Column: Form Controls (2/3 width on desktop) -->
            <div class="col-12 col-xl-7">
                
                <!-- 1. Governor Profile & Photo Card -->
                <div class="card border-0 rounded-4 shadow-xs mb-4 overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center gap-2">
                        <i class="fa-solid fa-user-tie text-success fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">1. ข้อมูลประจำตำแหน่งและรูปถ่ายผู้ว่าราชการจังหวัด</h6>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-md-7">
                                <label class="form-label fw-bold small text-dark">ชื่อ-นามสกุล ผู้ว่าราชการจังหวัด <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" id="govNameInput" name="gov_name" value="<?= esc($govName) ?>" required placeholder="เช่น นายสุจินต์ วาจากิจ" oninput="updateLivePreview()">
                            </div>

                            <!-- Position -->
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-dark">ตำแหน่งทางการ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" id="govPositionInput" name="gov_position" value="<?= esc($govPosition) ?>" required placeholder="ผู้ว่าราชการจังหวัดพัทลุง" oninput="updateLivePreview()">
                            </div>

                            <!-- Photo Upload Area -->
                            <div class="col-12 mt-3">
                                <label class="form-label fw-bold small text-dark">รูปถ่ายผู้ว่าราชการจังหวัด (แนะนำภาพแนวตั้ง ชุดปกติขาวหรือสูทสากล)</label>
                                
                                <div class="d-flex flex-column flex-sm-row align-items-center gap-3 p-3 rounded-3 bg-light border">
                                    <!-- Current / Selected Preview Thumbnail -->
                                    <div class="position-relative rounded-2 overflow-hidden shadow-xs flex-shrink-0" style="width: 100px; height: 120px; background: #cbd5e1; border: 2px solid #ffffff;">
                                        <img id="formPhotoPreview" src="<?= $govPhoto ?>" alt="Preview" class="w-100 h-100 object-fit-cover">
                                    </div>

                                    <!-- Upload Input & Button -->
                                    <div class="flex-grow-1 w-100">
                                        <div class="input-group mb-2">
                                            <input type="file" class="form-control rounded-3" id="govPhotoFile" name="gov_photo_file" accept="image/*" onchange="previewSelectedImage(this)">
                                        </div>
                                        <input type="hidden" id="govPhotoUrl" name="gov_photo_url" value="<?= esc($governor['photo'] ?? '') ?>">
                                        <small class="text-muted d-block">
                                            <i class="fa-solid fa-circle-info text-primary me-1"></i> รองรับไฟล์ .jpg, .png, .webp (ระบบจะปรับขนาดและจัดกึ่งกลางให้อัตโนมัติ)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- 2. Policy Statement & Vision Quote Card -->
                <div class="card border-0 rounded-4 shadow-xs mb-4 overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center gap-2">
                        <i class="fa-solid fa-quote-left text-success fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">2. ข้อความนโยบายและวิสัยทัศน์การบริหารงาน (Governor Quote)</h6>
                    </div>
                    <div class="card-body p-4">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-dark">
                                คำแถลงนโยบายหลัก / วาทะการบริหาร (Policy Statement) <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control rounded-3" id="govQuoteInput" name="gov_quote" rows="4" required placeholder="พิมพ์ข้อความนโยบายของผู้ว่าราชการจังหวัด..." oninput="updateLivePreview()"><?= esc($govQuote) ?></textarea>
                            <small class="text-muted mt-1 d-block">
                                ข้อความนี้จะแสดงในเครื่องหมายคำพูด “...” ข้างรูปถ่ายของผู้ว่าราชการจังหวัดบนหน้าแรก
                            </small>
                        </div>

                        <div>
                            <label class="form-label fw-bold small text-dark">
                                รายละเอียดนโยบายและแนวทางการขับเคลื่อนเพิ่มเติม (บันทึกไว้ในระบบ)
                            </label>
                            <textarea class="form-control rounded-3" id="govPolicyDetails" name="gov_policy_details" rows="3" placeholder="ระบุกรอบนโยบายสำคัญหรือประวัติการทำงานเพิ่มเติม (ถ้ามี)..."><?= esc($govHistory) ?></textarea>
                        </div>

                    </div>
                </div>

                <!-- 3. Top Announcement / Vision Bar Card -->
                <div class="card border-0 rounded-4 shadow-xs mb-4 overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center gap-2">
                        <i class="fa-solid fa-bell text-warning fs-5"></i>
                        <h6 class="fw-bold mb-0 text-dark">3. แถบประกาศวิสัยทัศน์จังหวัดด้านบน (Vision Ticker Bar)</h6>
                    </div>
                    <div class="card-body p-4">
                        <div>
                            <label class="form-label fw-bold small text-dark">ข้อความวิสัยทัศน์บนแถบประกาศ</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-success">
                                    <i class="fa-solid fa-bullhorn"></i>
                                </span>
                                <input type="text" class="form-control rounded-end-3" id="visionTickerInput" name="provincial_vision_ticker" value="<?= esc($visionTicker) ?>" placeholder="เมืองแห่งความยั่งยืนทางเศรษฐกิจ สังคม ความมั่นคง..." oninput="updateLivePreview()">
                            </div>
                            <small class="text-muted mt-1 d-block">
                                แสดงบนแถบประกาศสีขาว-เขียว ด้านบนสุดของกล่องนโยบาย
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Real-time Live Visual Preview (1/3 width on desktop) -->
            <div class="col-12 col-xl-5">
                <div class="sticky-top" style="top: 24px; z-index: 10;">
                    
                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden" style="border: 1px solid #cbd5e1 !important; background: #ffffff;">
                        <div class="card-header bg-dark text-white py-3 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                                <span class="fw-bold small">ตัวอย่างแสดงผลเสมือนจริง (Live Preview)</span>
                            </div>
                            <span class="badge bg-secondary rounded-pill px-2.5 py-0.5" style="font-size: 0.7rem;">หน้าแรก (Home)</span>
                        </div>

                        <div class="p-3 bg-light">
                            
                            <!-- 1. Announcement Preview -->
                            <div class="mb-2 p-2 rounded-2 bg-white border d-flex align-items-center gap-2 shadow-xs" style="font-size: 0.8rem;">
                                <span class="badge bg-success px-2 py-1"><i class="fa-solid fa-bell me-1"></i>ประกาศ</span>
                                <span class="text-truncate fw-medium text-dark flex-grow-1" id="previewVisionTicker">
                                    <?= esc($visionTicker) ?>
                                </span>
                            </div>

                            <!-- 2. Governor Card Preview -->
                            <div class="p-3 rounded-3 shadow-xs position-relative overflow-hidden" style="background: #ffffff; border: 1.5px solid #d1e7d1;">
                                <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                    <h6 class="fw-bold text-success m-0" style="font-size: 0.92rem;">
                                        <i class="fa-solid fa-user-tie me-1"></i> ผู้ว่าราชการจังหวัดพัทลุง
                                    </h6>
                                </div>

                                <div class="d-flex align-items-center gap-2.5 my-2">
                                    <div class="flex-grow-1">
                                        <blockquote class="mb-0 text-muted fst-italic" style="font-size: 0.78rem; line-height: 1.45;" id="previewGovQuote">
                                            “<?= esc($govQuote) ?>”
                                        </blockquote>
                                    </div>

                                    <div class="flex-shrink-0">
                                        <div class="rounded-2 overflow-hidden shadow-xs border" style="width: 75px; height: 90px; background: #e2e8f0;">
                                            <img id="previewGovPhoto" src="<?= $govPhoto ?>" alt="Preview Photo" class="w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between pt-1.5 mt-2 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                                    <small class="text-success fw-semibold" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-crown me-1"></i> ทำเนียบผู้ว่าฯ
                                    </small>
                                    <div class="text-end">
                                        <span class="fw-bold text-dark d-block" style="font-size: 0.85rem;" id="previewGovName"><?= esc($govName) ?></span>
                                        <small class="text-muted d-block" style="font-size: 0.72rem;" id="previewGovPosition"><?= esc($govPosition) ?></small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer bg-white py-3 px-4 text-center border-top">
                            <button type="button" class="btn btn-success w-100 rounded-pill py-2.5 fw-bold shadow-xs hover-lift d-flex align-items-center justify-content-center gap-2" onclick="submitGovernorPolicy()">
                                <i class="fa-solid fa-check-circle"></i>
                                <span>บันทึกข้อมูลทันที</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </form>

</div>

<script>
// Real-time Visual Preview updater
function updateLivePreview() {
    const name = document.getElementById('govNameInput').value.trim() || 'นายสุจินต์ วาจากิจ';
    const position = document.getElementById('govPositionInput').value.trim() || 'ผู้ว่าราชการจังหวัดพัทลุง';
    const quote = document.getElementById('govQuoteInput').value.trim() || 'รักเมืองลุง สร้างเมืองลุง ไปด้วยกัน...';
    const vision = document.getElementById('visionTickerInput').value.trim() || 'เมืองแห่งความยั่งยืนทางเศรษฐกิจ สังคม...';

    document.getElementById('previewGovName').textContent = name;
    document.getElementById('previewGovPosition').textContent = position;
    document.getElementById('previewGovQuote').textContent = '“' + quote + '”';
    document.getElementById('previewVisionTicker').textContent = vision;
}

// Preview uploaded image
function previewSelectedImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('formPhotoPreview').src = e.target.result;
            document.getElementById('previewGovPhoto').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// AJAX Submit Governor Policy Form
function submitGovernorPolicy() {
    const form = document.getElementById('governorPolicyForm');
    const formData = new FormData(form);
    const saveBtn = document.getElementById('btnSaveText');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'กำลังบันทึก...';

    fetch('<?= base_url('admin/governor-policy/save') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        saveBtn.textContent = originalText;
        if (data.status === 'success') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: data.message || 'บันทึกนโยบายผู้ว่าราชการจังหวัดเรียบร้อยแล้ว',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                alert(data.message || 'บันทึกสำเร็จ!');
            }
            if (data.data && data.data.photo_url) {
                document.getElementById('formPhotoPreview').src = data.data.photo_url;
                document.getElementById('previewGovPhoto').src = data.data.photo_url;
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: data.message || 'ไม่สามารถบันทึกข้อมูลได้'
                });
            } else {
                alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้'));
            }
        }
    })
    .catch(err => {
        saveBtn.textContent = originalText;
        console.error(err);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อเครือข่าย กรุณาลองใหม่อีกครั้ง');
    });
}
</script>

<?= $this->endSection() ?>

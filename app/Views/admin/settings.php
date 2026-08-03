<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $s = $settings ?? []; ?>

<!-- Header Title -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-sliders text-primary me-2"></i>ตั้งค่าระบบและปรับแต่งเว็บไซต์ (Portal Configuration)</h4>
        <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
            จัดการข้อมูลทั่วไป ข้อความแจ้งเตือน และธีมการแสดงผลของเว็บไซต์จังหวัดพัทลุง <span class="badge bg-success ms-2">No-Reload Async Sync</span>
        </p>
    </div>
    <button form="settingsForm" type="submit" class="btn-modern" id="saveBtn">
        <i class="fa-solid fa-floppy-disk me-2"></i> บันทึกการเปลี่ยนแปลงทั้งหมด
    </button>
</div>

<div class="row g-4">
    <!-- Main Configuration Panel (Left Column) -->
    <div class="col-xl-8">
        <div class="glass-card p-4" style="border-radius: 24px;">
            
            <!-- Navigation Pills Tabs -->
            <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="settingsTab" role="tablist" style="border-color: var(--glass-border) !important;">
                <li class="nav-item" role="presentation">
                    <button class="tab-pill active" id="gen-tab" data-bs-toggle="pill" data-bs-target="#tab-general" type="button" role="tab">
                        <i class="fa-solid fa-building me-1"></i> ข้อมูลทั่วไป
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="tab-pill" id="sec-tab" data-bs-toggle="pill" data-bs-target="#tab-security" type="button" role="tab">
                        <i class="fa-solid fa-shield-halved me-1"></i> ความปลอดภัย & สิทธิ์
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="tab-pill" id="ui-tab" data-bs-toggle="pill" data-bs-target="#tab-ui" type="button" role="tab">
                        <i class="fa-solid fa-palette me-1"></i> ดีไซน์ & ธีมสี
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="tab-pill" id="seo-tab" data-bs-toggle="pill" data-bs-target="#tab-seo" type="button" role="tab">
                        <i class="fa-solid fa-globe me-1"></i> โซเชียล & SEO
                    </button>
                </li>
            </ul>

            <!-- Settings Form -->
            <form id="settingsForm" onsubmit="handleSaveSettings(event)">
                <div class="tab-content" id="settingsTabContent">
                    
                    <!-- TAB 1: GENERAL INFORMATION -->
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                        <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-circle-info me-2"></i>ข้อมูลชื่อหน่วยงานและสถานที่ติดต่อ</h6>
                        <?php $currentLogo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                        <div class="row g-3">
                            <div class="col-12 mb-2">
                                <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px dashed var(--glass-border);">
                                    <label class="form-label fw-bold d-block text-primary mb-2">
                                        <i class="fa-solid fa-building-flag me-2"></i>ตราสัญลักษณ์ประจำหน่วยงาน หรือ โลโก้เว็บไซต์ (Official Logo)
                                    </label>
                                    <div class="d-flex flex-wrap align-items-center gap-4">
                                        <div class="text-center" style="width: 85px; height: 85px; border-radius: 20px; background: var(--bg-secondary); border: 2px solid var(--glass-border); display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                            <img id="logoPreviewThumb" src="<?= !empty($currentLogo) ? htmlspecialchars($currentLogo) : '' ?>" 
                                                 alt="Logo" style="max-height: 70px; max-width: 70px; <?= empty($currentLogo) ? 'display: none;' : '' ?> object-fit: contain;">
                                            <i id="logoFallbackIcon" class="fa-solid fa-building-flag text-secondary" style="font-size: 2.2rem; <?= !empty($currentLogo) ? 'display: none;' : '' ?>"></i>
                                        </div>
                                        <div style="flex: 1; min-width: 250px;">
                                            <input type="file" name="site_logo_file" id="siteLogoInput" class="form-control custom-input mb-2" accept="image/png, image/jpeg, image/webp, image/svg+xml" onchange="previewLogo(event)">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <small class="text-muted" style="font-size: 0.8rem;">รองรับไฟล์ PNG, JPG, WEBP, SVG (แนะนำพื้นหลังโปร่งใส)</small>
                                                <?php if(!empty($currentLogo)): ?>
                                                <div class="form-check form-switch m-0" style="font-size: 0.85rem;">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="remove_logo" id="removeLogoSwitch" value="1" onchange="toggleRemoveLogo(this)">
                                                    <label class="form-check-label text-danger fw-bold" for="removeLogoSwitch">ถอดลบโลโก้</label>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ชื่อเว็บไซต์ (ภาษาไทย) <span class="text-danger">*</span></label>
                                <input type="text" name="site_title_th" class="form-control custom-input" value="<?= htmlspecialchars($s['site_title_th'] ?? '') ?>" required oninput="updatePreview()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ชื่อเว็บไซต์ (ภาษาอังกฤษ)</label>
                                <input type="text" name="site_title_en" class="form-control custom-input" value="<?= htmlspecialchars($s['site_title_en'] ?? '') ?>" oninput="updatePreview()">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">คำขวัญหรือข้อความต้อนรับบนหน้าแรก (Welcome Slogan)</label>
                                <textarea name="slogan" rows="2" class="form-control custom-input" oninput="updatePreview()"><?= htmlspecialchars($s['slogan'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fa-solid fa-envelope me-1"></i> อีเมลกลางราชการ</label>
                                <input type="email" name="contact_email" class="form-control custom-input" value="<?= htmlspecialchars($s['contact_email'] ?? '') ?>" oninput="updatePreview()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fa-solid fa-phone me-1"></i> เบอร์โทรศัพท์ติดต่อศูนย์ดำรงธรรม</label>
                                <input type="text" name="contact_phone" class="form-control custom-input" value="<?= htmlspecialchars($s['contact_phone'] ?? '') ?>" oninput="updatePreview()">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold"><i class="fa-solid fa-map-location-dot me-1"></i> ที่ตั้งศาลากลางจังหวัดและศูนย์ราชการ</label>
                                <input type="text" name="address" class="form-control custom-input" value="<?= htmlspecialchars($s['address'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: SECURITY & ACCESS -->
                    <div class="tab-pane fade" id="tab-security" role="tabpanel">
                        <h6 class="fw-bold mb-4 text-primary"><i class="fa-solid fa-lock me-2"></i>ระบบปกป้องการทำงานและเปิด/ปิดระบบ</h6>
                        
                        <div class="p-3 mb-3 d-flex align-items-center justify-content-between" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: var(--radius-md);">
                            <div>
                                <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-screwdriver-wrench me-2"></i>โหมดปิดปรับปรุงชั่วคราว (Maintenance Mode)</h6>
                                <p class="mb-0 text-secondary" style="font-size: 0.85rem;">เมื่อเปิดใช้งาน ผู้ใช้ทั่วไปจะเห็นหน้าแจ้งเตือนปรับปรุงระบบ (ยกเว้นผู้ดูแลระบบที่สามารถเข้าสู่ระบบผ่านหลังบ้านได้ตามปกติ)</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="maintenance_mode" value="1" <?= ($s['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' ?> style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
                            </div>
                        </div>

                        <div class="p-3 mb-3 d-flex align-items-center justify-content-between" style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: var(--radius-md);">
                            <div>
                                <h6 class="fw-bold mb-1">เปิดรับสิทธิ์ลงทะเบียนประชาชนทั่วไป</h6>
                                <p class="mb-0 text-secondary" style="font-size: 0.85rem;">อนุญาตให้ประชาชนกดสมัครบัญชีผ่านระบบ e-Service เพื่อดูประวัติคำร้องย้อนหลังได้</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="public_register" value="1" <?= ($s['public_register'] ?? '1') == '1' ? 'checked' : '' ?> style="width: 3rem; height: 1.5rem; cursor: pointer;">
                            </div>
                        </div>

                        <div class="p-3 d-flex align-items-center justify-content-between" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: var(--radius-md);">
                            <div>
                                <h6 class="fw-bold text-success mb-1"><i class="fa-solid fa-user-shield me-2"></i>ระบบป้องกันการโจมตีข้ามเว็บไซต์ (Strict CSRF Token Validation)</h6>
                                <p class="mb-0 text-secondary" style="font-size: 0.85rem;">บังคับตรวจสอบ Token ความปลอดภัยในทุกแบบฟอร์มคำร้อง (แนะนำให้เปิดไว้ตลอดเวลา)</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="csrf_protection" value="1" <?= ($s['csrf_protection'] ?? '1') == '1' ? 'checked' : '' ?> style="width: 3rem; height: 1.5rem; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: BRANDING & THEME -->
                    <div class="tab-pane fade" id="tab-ui" role="tabpanel">
                        <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-paintbrush me-2"></i>ชุดสีและรูปลักษณ์อินเตอร์เฟซร่วมสมัย</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ธีมแสงหน้าจอเริ่มต้นสำหรับผู้มาเยือน</label>
                                <select name="default_theme" class="form-select custom-input">
                                    <option value="light" <?= ($s['default_theme'] ?? 'light') == 'light' ? 'selected' : '' ?>>☀️ โหมดกลางวัน (Light Mode)</option>
                                    <option value="dark" <?= ($s['default_theme'] ?? 'light') == 'dark' ? 'selected' : '' ?>>🌙 โหมดกลางคืน (Dark Mode - ถนอมสายตา)</option>
                                    <option value="auto">⚡ ปรับตามเบราว์เซอร์อัตโนมัติ (System Preference)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">สีสันหลัก (Theme Accent Color)</label>
                                <div class="d-flex gap-3 align-items-center mt-1">
                                    <input type="color" name="theme_accent" id="themeAccentPicker" class="form-control form-control-color" value="<?= htmlspecialchars($s['theme_accent'] ?? '#6366f1') ?>" title="Choose your color" style="width: 60px; height: 45px; border-radius: var(--radius-sm);" oninput="updatePreview()">
                                    <span class="font-monospace text-secondary" id="colorTextValue"><?= htmlspecialchars($s['theme_accent'] ?? '#6366f1') ?></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="p-3 rounded text-secondary" style="background: rgba(99, 102, 241, 0.08); border-left: 4px solid var(--accent-primary);">
                                    <i class="fa-solid fa-lightbulb text-warning me-2"></i> <strong>คำแนะนำดีไซเนอร์:</strong> ชุดสีที่คุณปรับที่นี่จะถูกส่งค่าไปปรับเปลี่ยนโทนสี Gradient และ Glassmorphism ในหน้าหลักของศูนย์บริการประชาชนโดยทันที
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: SOCIAL & SEO -->
                    <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                        <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-share-nodes me-2"></i>การเชื่อมต่อโซเชียลมีเดียและคำค้นหา (SEO Meta Tags)</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fa-brands fa-facebook text-primary me-1"></i> Facebook Official Page</label>
                                <input type="url" name="fb_url" class="form-control custom-input" placeholder="https://facebook.com/..." value="<?= htmlspecialchars($s['fb_url'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><i class="fa-brands fa-line text-success me-1"></i> LINE Official Account ID</label>
                                <input type="text" name="line_id" class="form-control custom-input" placeholder="@phatthalung_connect" value="<?= htmlspecialchars($s['line_id'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold"><i class="fa-solid fa-magnifying-glass-chart text-warning me-1"></i> คำค้นหา Google Search (SEO Keywords)</label>
                                <input type="text" name="seo_keywords" class="form-control custom-input" value="<?= htmlspecialchars($s['seo_keywords'] ?? '') ?>" placeholder="คั่นคำด้วยเครื่องหมายจุลภาค (,)">
                                <small class="text-muted">เช่น: จังหวัดพัทลุง, บริการประชาชน, ทะเลน้อย, ศูนย์ดำรงธรรม, ประกวดราคา, ข่าวราชการ</small>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Live Preview Card (Right Column) -->
    <div class="col-xl-4">
        <div class="glass-card p-4 sticky-top" style="top: 100px; border-radius: 24px; border: 1px solid var(--glass-border); box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3" style="border-color: var(--glass-border) !important;">
                <h6 class="fw-bold mb-0 text-secondary"><i class="fa-solid fa-tv me-2"></i>จำลองการแสดงผลสด (Live Preview)</h6>
                <span class="badge bg-primary">Instant View</span>
            </div>

            <!-- Simulation Box -->
            <div class="p-4 rounded-4 mb-3 text-center transition-all" id="previewBox" style="background: var(--bg-secondary); border: 2px dashed var(--glass-border);">
                <div class="mb-2 d-flex justify-content-center align-items-center gap-2">
                    <img id="previewBoxLogo" src="<?= !empty($currentLogo) ? htmlspecialchars($currentLogo) : '' ?>" style="height: 38px; width: auto; max-width: 50px; object-fit: contain; <?= empty($currentLogo) ? 'display: none;' : '' ?>">
                    <span class="badge" id="previewAccentBadge" style="background: <?= htmlspecialchars($s['theme_accent'] ?? '#6366f1') ?>; color: white; padding: 0.4rem 0.8rem; border-radius: var(--radius-full);">
                        <i class="fa-solid fa-flag me-1" id="previewBadgeIcon" style="<?= !empty($currentLogo) ? 'display: none;' : '' ?>"></i> พัทลุงพอร์ทัล
                    </span>
                </div>
                <h5 class="fw-bold my-2 text-primary" id="previewTitleTh"><?= htmlspecialchars($s['site_title_th'] ?? '') ?></h5>
                <h6 class="text-secondary mb-3" style="font-size: 0.85rem;" id="previewTitleEn"><?= htmlspecialchars($s['site_title_en'] ?? '') ?></h6>
                
                <div class="p-3 rounded-3 mb-3 text-start" style="background: var(--glass-bg); border: 1px solid var(--glass-border); font-size: 0.85rem;">
                    <i class="fa-solid fa-quote-left text-primary me-1"></i>
                    <span id="previewSlogan"><?= htmlspecialchars($s['slogan'] ?? '') ?></span>
                </div>

                <div class="d-flex flex-column gap-1 text-start border-top pt-3" style="border-color: var(--glass-border) !important; font-size: 0.85rem; color: var(--text-secondary);">
                    <div><i class="fa-solid fa-envelope text-primary me-2"></i><span id="previewEmail"><?= htmlspecialchars($s['contact_email'] ?? '') ?></span></div>
                    <div><i class="fa-solid fa-phone text-success me-2"></i><span id="previewPhone"><?= htmlspecialchars($s['contact_phone'] ?? '') ?></span></div>
                </div>
            </div>

            <p class="text-center mb-0 text-muted" style="font-size: 0.8rem;">
                <i class="fa-solid fa-circle-check text-success me-1"></i> ระบบบันทึกข้อมูลแบบ Hybrid (DB Ready + persistent JSON)
            </p>
        </div>
    </div>
</div>

<script>
// 1. ฟังก์ชันบันทึกการตั้งค่าแบบ No-Reload SPA
async function handleSaveSettings(event) {
    event.preventDefault();
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveBtn');
    const formData = new FormData(form);

    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังบันทึกข้อมูลลงฐานระบบ...';

    try {
        const res = await App.fetch('<?= base_url("admin/settings/save") ?>', {
            method: 'POST',
            body: formData
        });

        if (res.status === 'success') {
            App.toast(res.message, 'success');
        } else {
            App.toast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการติดต่อเซิร์ฟเวอร์: ' + err.message, 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    }
}

// 2. ฟังก์ชันอัปเดต Live Preview ทันทีที่ผู้ใช้พิมพ์ข้อความ
function updatePreview() {
    const titleTh = document.querySelector('input[name="site_title_th"]').value;
    const titleEn = document.querySelector('input[name="site_title_en"]').value;
    const slogan = document.querySelector('textarea[name="slogan"]').value;
    const email = document.querySelector('input[name="contact_email"]').value;
    const phone = document.querySelector('input[name="contact_phone"]').value;
    const accent = document.getElementById('themeAccentPicker').value;

    document.getElementById('previewTitleTh').textContent = titleTh || 'ชื่อเว็บไซต์';
    document.getElementById('previewTitleEn').textContent = titleEn || '';
    document.getElementById('previewSlogan').textContent = slogan || 'คำขวัญเว็บไซต์';
    document.getElementById('previewEmail').textContent = email || '-';
    document.getElementById('previewPhone').textContent = phone || '-';
    document.getElementById('previewAccentBadge').style.backgroundColor = accent;
    document.getElementById('colorTextValue').textContent = accent;
}

// 3. ฟังก์ชันพรีวิวโลโก้สดด้วยเทคโนโลยี FileReader
function previewLogo(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const thumb = document.getElementById('logoPreviewThumb');
            const icon = document.getElementById('logoFallbackIcon');
            const boxLogo = document.getElementById('previewBoxLogo');
            const badgeIcon = document.getElementById('previewBadgeIcon');

            if (thumb) { thumb.src = e.target.result; thumb.style.display = 'block'; }
            if (icon) icon.style.display = 'none';
            if (boxLogo) { boxLogo.src = e.target.result; boxLogo.style.display = 'inline-block'; }
            if (badgeIcon) badgeIcon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

function toggleRemoveLogo(checkbox) {
    const thumb = document.getElementById('logoPreviewThumb');
    const icon = document.getElementById('logoFallbackIcon');
    const boxLogo = document.getElementById('previewBoxLogo');
    const badgeIcon = document.getElementById('previewBadgeIcon');
    if (checkbox.checked) {
        if (thumb) thumb.style.display = 'none';
        if (icon) icon.style.display = 'inline-block';
        if (boxLogo) boxLogo.style.display = 'none';
        if (badgeIcon) badgeIcon.style.display = 'inline-block';
    } else {
        if (thumb) thumb.style.display = 'block';
        if (icon) icon.style.display = 'none';
        if (boxLogo) boxLogo.style.display = 'inline-block';
        if (badgeIcon) badgeIcon.style.display = 'none';
    }
}
</script>

<?= $this->endSection() ?>

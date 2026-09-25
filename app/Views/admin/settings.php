<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $s = $settings ?? []; ?>

<!-- Hero Cockpit Header -->
<div class="glass-card mb-4 p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 28px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(59, 130, 246, 0.10) 50%, rgba(16, 185, 129, 0.08) 100%); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="position-absolute" style="top: -40px; right: -30px; font-size: 15rem; color: rgba(99, 102, 241, 0.04); pointer-events: none; z-index: 0;">
        <i class="fa-solid fa-sliders"></i>
    </div>
    
    <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3); color: #4f46e5; font-size: 0.85rem; font-weight: 600;">
                <i class="fa-solid fa-gear"></i> Master Portal Engine & Core Configuration
            </div>
            <h2 class="fw-bold mb-2" style="color: var(--text-primary); letter-spacing: -0.5px;">
                ตั้งค่าระบบและปรับแต่งเว็บไซต์ (Portal Configuration)
            </h2>
            <p class="text-secondary mb-0" style="font-size: 1rem; max-width: 760px;">
                จัดการอัตลักษณ์หน่วยงาน ตราสัญลักษณ์ทางการ ช่องทางติดต่อ โหมดความปลอดภัย และการเชื่อมต่อ LINE Flex Notification แบบ No-Reload Instant Sync
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <button form="settingsForm" type="submit" class="btn btn-primary px-4 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow transition-all" id="saveBtn" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none; font-size: 1rem;">
                <i class="fa-solid fa-cloud-arrow-up"></i> <span>บันทึกการเปลี่ยนแปลงทั้งหมด</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row inside Cockpit -->
    <div class="position-relative z-1 row g-3 mt-3 pt-3 border-top" style="border-color: var(--glass-border) !important;">
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-database text-primary me-1"></i> สถาปัตยกรรมข้อมูล</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">Hybrid (DB + JSON)</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-shield-halved text-success me-1"></i> ความปลอดภัยระบบ</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">CSRF Strict Protection</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-brands fa-line text-success me-1"></i> LINE Notification</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <?php if(!empty($s['line_channel_access_token'])): ?>
                        <span class="badge bg-success text-white rounded-pill px-3 py-1">พร้อมส่งแจ้งเตือน</span>
                    <?php else: ?>
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1">ยังไม่เชื่อมต่อ</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> สถานะเปิดบริการ</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <?php if(($s['maintenance_mode'] ?? '0') == '1'): ?>
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1">ปิดปรับปรุงชั่วคราว</span>
                    <?php else: ?>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">ออนไลน์ปกติ (Active)</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Configuration Panel (Left Column) -->
    <div class="col-xl-8">
        <div class="glass-card p-4 rounded-4 shadow-sm" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
            
            <!-- Navigation Pills Tabs -->
            <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="settingsTab" role="tablist" style="border-color: var(--glass-border) !important;">
                <li class="nav-item" role="presentation">
                    <button class="tab-pill active rounded-3 px-3 py-2 fw-medium" id="gen-tab" data-bs-toggle="pill" data-bs-target="#tab-general" type="button" role="tab">
                        <i class="fa-solid fa-building me-1.5 text-primary"></i> ข้อมูลทั่วไป & อัตลักษณ์
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="tab-pill rounded-3 px-3 py-2 fw-medium" id="sec-tab" data-bs-toggle="pill" data-bs-target="#tab-security" type="button" role="tab">
                        <i class="fa-solid fa-shield-halved me-1.5 text-success"></i> ความปลอดภัย & สิทธิ์
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="tab-pill rounded-3 px-3 py-2 fw-medium" id="ui-tab" data-bs-toggle="pill" data-bs-target="#tab-ui" type="button" role="tab">
                        <i class="fa-solid fa-palette me-1.5 text-info"></i> ดีไซน์ & ธีมสี
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="tab-pill rounded-3 px-3 py-2 fw-medium" id="seo-tab" data-bs-toggle="pill" data-bs-target="#tab-seo" type="button" role="tab">
                        <i class="fa-solid fa-globe me-1.5 text-warning"></i> โซเชียล & SEO
                    </button>
                </li>
            </ul>

            <!-- Settings Form -->
            <form id="settingsForm" onsubmit="handleSaveSettings(event)">
                <div class="tab-content" id="settingsTabContent">
                    
                    <!-- TAB 1: GENERAL INFORMATION -->
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                        <h6 class="fw-bold mb-3" style="color: var(--text-primary);"><i class="fa-solid fa-circle-info text-primary me-2"></i>ข้อมูลชื่อหน่วยงานและสถานที่ติดต่อ</h6>
                        <?php $currentLogo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                        <div class="row g-3">
                            <div class="col-12 mb-2">
                                <div class="p-3.5 rounded-4" style="background: var(--bg-secondary); border: 1px dashed var(--glass-border);">
                                    <label class="form-label fw-bold d-block mb-2" style="color: var(--text-primary);">
                                        <i class="fa-solid fa-building-flag text-primary me-2"></i>ตราสัญลักษณ์ประจำหน่วยงาน หรือ โลโก้เว็บไซต์ (Official Logo)
                                    </label>
                                    <div class="d-flex flex-wrap align-items-center gap-4">
                                        <div class="text-center rounded-4 shadow-sm" style="width: 88px; height: 88px; background: var(--bg-surface); border: 2px solid var(--glass-border); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            <img id="logoPreviewThumb" src="<?= !empty($currentLogo) ? htmlspecialchars($currentLogo) : '' ?>" 
                                                 alt="Logo" style="max-height: 72px; max-width: 72px; <?= empty($currentLogo) ? 'display: none;' : '' ?> object-fit: contain;">
                                            <i id="logoFallbackIcon" class="fa-solid fa-building-flag text-secondary" style="font-size: 2.4rem; <?= !empty($currentLogo) ? 'display: none;' : '' ?>"></i>
                                        </div>
                                        <div style="flex: 1; min-width: 250px;">
                                            <input type="file" name="site_logo_file" id="siteLogoInput" class="form-control custom-input mb-2" accept="image/png, image/jpeg, image/webp, image/svg+xml" onchange="previewLogo(event)">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <small class="text-secondary" style="font-size: 0.8rem;">รองรับไฟล์ PNG, JPG, WEBP, SVG (แนะนำไฟล์พื้นหลังโปร่งใส Transparent)</small>
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
                                <label class="form-label fw-bold" style="color: var(--text-primary);">ชื่อเว็บไซต์ (ภาษาไทย) <span class="text-danger">*</span></label>
                                <input type="text" name="site_title_th" class="form-control custom-input" value="<?= htmlspecialchars($s['site_title_th'] ?? '') ?>" required oninput="updatePreview()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--text-primary);">ชื่อเว็บไซต์ (ภาษาอังกฤษ)</label>
                                <input type="text" name="site_title_en" class="form-control custom-input" value="<?= htmlspecialchars($s['site_title_en'] ?? '') ?>" oninput="updatePreview()">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold" style="color: var(--text-primary);">คำขวัญหรือข้อความต้อนรับบนหน้าแรก (Welcome Slogan)</label>
                                <textarea name="slogan" rows="2" class="form-control custom-input" oninput="updatePreview()"><?= htmlspecialchars($s['slogan'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--text-primary);"><i class="fa-solid fa-envelope text-primary me-1"></i> อีเมลกลางราชการ</label>
                                <input type="email" name="contact_email" class="form-control custom-input" value="<?= htmlspecialchars($s['contact_email'] ?? '') ?>" oninput="updatePreview()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--text-primary);"><i class="fa-solid fa-phone text-success me-1"></i> เบอร์โทรศัพท์ติดต่อศูนย์ดำรงธรรม</label>
                                <input type="text" name="contact_phone" class="form-control custom-input" value="<?= htmlspecialchars($s['contact_phone'] ?? '') ?>" oninput="updatePreview()">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold" style="color: var(--text-primary);"><i class="fa-solid fa-map-location-dot text-danger me-1"></i> ที่ตั้งศาลากลางจังหวัดและศูนย์ราชการ</label>
                                <input type="text" name="address" class="form-control custom-input" value="<?= htmlspecialchars($s['address'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: SECURITY & ACCESS -->
                    <div class="tab-pane fade" id="tab-security" role="tabpanel">
                        <h6 class="fw-bold mb-4" style="color: var(--text-primary);"><i class="fa-solid fa-lock text-success me-2"></i>ระบบปกป้องการทำงานและเปิด/ปิดระบบ</h6>
                        
                        <div class="p-3.5 mb-3 d-flex align-items-center justify-content-between rounded-4" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2);">
                            <div>
                                <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-screwdriver-wrench me-2"></i>โหมดปิดปรับปรุงชั่วคราว (Maintenance Mode)</h6>
                                <p class="mb-0 text-secondary" style="font-size: 0.88rem;">เมื่อเปิดใช้งาน ผู้ใช้ทั่วไปจะเห็นหน้าแจ้งเตือนปรับปรุงระบบ (ผู้ดูแลระบบยังคงล็อกอินหลังบ้านได้ตามปกติ)</p>
                            </div>
                            <div class="form-check form-switch m-0" style="transform: scale(1.3); transform-origin: right center;">
                                <input class="form-check-input" type="checkbox" role="switch" name="maintenance_mode" value="1" <?= ($s['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' ?> style="cursor: pointer;">
                            </div>
                        </div>

                        <div class="p-3.5 mb-3 d-flex align-items-center justify-content-between rounded-4" style="background: var(--bg-secondary); border: 1px solid var(--glass-border);">
                            <div>
                                <h6 class="fw-bold mb-1" style="color: var(--text-primary);"><i class="fa-solid fa-user-plus text-primary me-2"></i>เปิดรับสิทธิ์ลงทะเบียนประชาชนทั่วไป</h6>
                                <p class="mb-0 text-secondary" style="font-size: 0.88rem;">อนุญาตให้ประชาชนสมัครบัญชีผ่านระบบ e-Service เพื่อดูประวัติคำร้องย้อนหลังได้</p>
                            </div>
                            <div class="form-check form-switch m-0" style="transform: scale(1.3); transform-origin: right center;">
                                <input class="form-check-input" type="checkbox" role="switch" name="public_register" value="1" <?= ($s['public_register'] ?? '1') == '1' ? 'checked' : '' ?> style="cursor: pointer;">
                            </div>
                        </div>

                        <div class="p-3.5 d-flex align-items-center justify-content-between rounded-4" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2);">
                            <div>
                                <h6 class="fw-bold text-success mb-1"><i class="fa-solid fa-shield-halved me-2"></i>ระบบป้องกันการโจมตีข้ามเว็บไซต์ (Strict CSRF Token Validation)</h6>
                                <p class="mb-0 text-secondary" style="font-size: 0.88rem;">บังคับตรวจสอบ Token ความปลอดภัยในทุกแบบฟอร์มคำร้อง (แนะนำให้เปิดไว้ตลอดเวลา)</p>
                            </div>
                            <div class="form-check form-switch m-0" style="transform: scale(1.3); transform-origin: right center;">
                                <input class="form-check-input" type="checkbox" role="switch" name="csrf_protection" value="1" <?= ($s['csrf_protection'] ?? '1') == '1' ? 'checked' : '' ?> style="cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: BRANDING & THEME -->
                    <div class="tab-pane fade" id="tab-ui" role="tabpanel">
                        <h6 class="fw-bold mb-3" style="color: var(--text-primary);"><i class="fa-solid fa-palette text-info me-2"></i>ชุดสีและรูปลักษณ์อินเตอร์เฟซร่วมสมัย</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--text-primary);">ธีมแสงหน้าจอเริ่มต้นสำหรับผู้มาเยือน</label>
                                <select name="default_theme" class="form-select custom-input">
                                    <option value="light" <?= ($s['default_theme'] ?? 'light') == 'light' ? 'selected' : '' ?>>☀️ โหมดกลางวัน (Light Mode)</option>
                                    <option value="dark" <?= ($s['default_theme'] ?? 'light') == 'dark' ? 'selected' : '' ?>>🌙 โหมดกลางคืน (Dark Mode - ถนอมสายตา)</option>
                                    <option value="auto">⚡ ปรับตามเบราว์เซอร์อัตโนมัติ (System Preference)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--text-primary);">สีสันหลักของเว็บไซต์ (Theme Accent Color)</label>
                                <div class="d-flex gap-3 align-items-center mt-1">
                                    <input type="color" name="theme_accent" id="themeAccentPicker" class="form-control form-control-color shadow-sm" value="<?= htmlspecialchars($s['theme_accent'] ?? '#6366f1') ?>" title="เลือกสีประจำธีม" style="width: 60px; height: 42px; border-radius: 12px;" oninput="updatePreview()">
                                    <span class="font-monospace fw-bold" id="colorTextValue" style="color: var(--text-primary); font-size: 1.05rem;"><?= htmlspecialchars($s['theme_accent'] ?? '#6366f1') ?></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="p-3.5 rounded-4 d-flex align-items-center gap-3" style="background: rgba(99, 102, 241, 0.08); border-left: 4px solid var(--accent-primary);">
                                    <i class="fa-solid fa-lightbulb text-warning fs-4"></i>
                                    <div class="small text-secondary" style="font-size: 0.9rem;">
                                        <strong>คำแนะนำการออกแบบ:</strong> สี Accent Color ที่คุณเลือกจะถูกส่งค่าไปปรับเปลี่ยนโทนสี Gradient และองค์ประกอบสำคัญในหน้าหลักของศูนย์บริการประชาชนโดยทันที
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: SOCIAL & SEO -->
                    <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                        <h6 class="fw-bold mb-3" style="color: var(--text-primary);"><i class="fa-solid fa-share-nodes text-warning me-2"></i>การเชื่อมต่อโซเชียลมีเดีย, LINE แจ้งเตือน และ SEO</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--text-primary);"><i class="fa-brands fa-facebook text-primary me-1"></i> Facebook Official Page</label>
                                <input type="url" name="fb_url" class="form-control custom-input" placeholder="https://facebook.com/..." value="<?= htmlspecialchars($s['fb_url'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold" style="color: var(--text-primary);"><i class="fa-brands fa-line text-success me-1"></i> LINE Official Account ID</label>
                                <input type="text" name="line_id" class="form-control custom-input" placeholder="@phatthalung_connect" value="<?= htmlspecialchars($s['line_id'] ?? '') ?>">
                            </div>
                            
                            <!-- LINE Messaging API Alerts for Officers -->
                            <div class="col-12 mt-3">
                                <div class="p-3.5 rounded-4" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25);">
                                    <div class="d-flex align-items-center gap-2 mb-2 text-success fw-bold">
                                        <i class="fa-brands fa-line fs-5"></i>
                                        <span>ระบบแจ้งเตือนเข้า LINE เจ้าหน้าที่ (LINE Messaging API / Flex Message)</span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-8">
                                            <label class="form-label small fw-bold" style="color: var(--text-primary);">LINE Channel Access Token (Long-lived)</label>
                                            <input type="text" name="line_channel_access_token" class="form-control form-control-sm font-monospace custom-input" value="<?= htmlspecialchars($s['line_channel_access_token'] ?? '') ?>" placeholder="ใส่ Channel Access Token จาก LINE Developers Console">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small fw-bold" style="color: var(--text-primary);">Group ID / User ID <small class="text-secondary fw-normal">(ว่างไว้เพื่อ Broadcast)</small></label>
                                            <input type="text" name="line_admin_group_id" class="form-control form-control-sm font-monospace custom-input" value="<?= htmlspecialchars($s['line_admin_group_id'] ?? '') ?>" placeholder="เช่น Cxxxxxxxx หรือ Uxxxxxxxx">
                                        </div>
                                    </div>
                                    <small class="text-secondary mt-2 d-block" style="font-size: 0.82rem;">ระบบจะส่ง Flex Message การ์ดแจ้งเตือนพร้อมปุ่มเปิดรับเรื่องเข้า LINE เจ้าหน้าที่ทันทีที่มีประชาชนส่งเรื่องเข้ามา</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold" style="color: var(--text-primary);"><i class="fa-solid fa-magnifying-glass-chart text-warning me-1"></i> คำค้นหา Google Search (SEO Keywords)</label>
                                <input type="text" name="seo_keywords" class="form-control custom-input" value="<?= htmlspecialchars($s['seo_keywords'] ?? '') ?>" placeholder="คั่นคำด้วยเครื่องหมายจุลภาค (,)">
                                <small class="text-secondary" style="font-size: 0.82rem;">เช่น: จังหวัดพัทลุง, บริการประชาชน, ทะเลน้อย, ศูนย์ดำรงธรรม, ประกวดราคา, ข่าวราชการ</small>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Live Preview Card (Right Column) -->
    <div class="col-xl-4">
        <div class="sticky-top" style="top: 90px; z-index: 10;">
            <div class="glass-card rounded-4 overflow-hidden shadow-lg" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
                <!-- Mini Safari Window Header -->
                <div class="px-3 py-2.5 d-flex align-items-center justify-content-between border-bottom" style="background: rgba(0,0,0,0.04); border-color: var(--glass-border) !important;">
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background: #ff5f56;"></span>
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background: #ffbd2e;"></span>
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background: #27c93f;"></span>
                    </div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1" style="font-size: 0.72rem; font-weight: 600;">
                        <i class="fa-solid fa-tv me-1"></i>Live Preview
                    </span>
                </div>

                <!-- Simulation Box -->
                <div class="p-4" style="background: var(--bg-secondary);">
                    <div class="p-4 rounded-4 text-center transition-all shadow-sm" id="previewBox" style="background: var(--bg-surface); border: 1px solid var(--glass-border);">
                        <div class="mb-2 d-flex justify-content-center align-items-center gap-2">
                            <img id="previewBoxLogo" src="<?= !empty($currentLogo) ? htmlspecialchars($currentLogo) : '' ?>" style="height: 38px; width: auto; max-width: 50px; object-fit: contain; <?= empty($currentLogo) ? 'display: none;' : '' ?>">
                            <span class="badge shadow-sm" id="previewAccentBadge" style="background: <?= htmlspecialchars($s['theme_accent'] ?? '#6366f1') ?>; color: white; padding: 0.45rem 0.9rem; border-radius: 9999px;">
                                <i class="fa-solid fa-flag me-1" id="previewBadgeIcon" style="<?= !empty($currentLogo) ? 'display: none;' : '' ?>"></i> พัทลุงพอร์ทัล
                            </span>
                        </div>
                        <h5 class="fw-bold my-2" id="previewTitleTh" style="color: var(--text-primary);"><?= htmlspecialchars($s['site_title_th'] ?? 'จังหวัดพัทลุง') ?></h5>
                        <h6 class="text-secondary mb-3" style="font-size: 0.85rem;" id="previewTitleEn"><?= htmlspecialchars($s['site_title_en'] ?? '') ?></h6>
                        
                        <div class="p-3 rounded-3 mb-3 text-start shadow-sm" style="background: var(--bg-secondary); border: 1px solid var(--glass-border); font-size: 0.88rem;">
                            <i class="fa-solid fa-quote-left text-primary me-1"></i>
                            <span id="previewSlogan" class="text-secondary"><?= htmlspecialchars($s['slogan'] ?? '') ?></span>
                        </div>

                        <div class="d-flex flex-column gap-1.5 text-start border-top pt-3" style="border-color: var(--glass-border) !important; font-size: 0.85rem; color: var(--text-secondary);">
                            <div class="d-flex align-items-center gap-2"><i class="fa-solid fa-envelope text-primary"></i> <span id="previewEmail" class="text-truncate"><?= htmlspecialchars($s['contact_email'] ?? '') ?></span></div>
                            <div class="d-flex align-items-center gap-2"><i class="fa-solid fa-phone text-success"></i> <span id="previewPhone"><?= htmlspecialchars($s['contact_phone'] ?? '') ?></span></div>
                        </div>
                    </div>

                    <p class="text-center mb-0 mt-3 text-secondary small" style="font-size: 0.8rem;">
                        <i class="fa-solid fa-shield-check text-success me-1"></i> บันทึกข้อมูลแบบ Hybrid (DB Ready + persistent JSON)
                    </p>
                </div>
            </div>
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
    saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังบันทึกข้อมูล...';

    try {
        const res = await App.fetch('<?= base_url("admin/settings/save") ?>', {
            method: 'POST',
            body: formData
        });

        if (res.status === 'success') {
            App.toast(res.message || 'บันทึกการตั้งค่าระบบเรียบร้อยแล้ว', 'success');
        } else {
            App.toast('เกิดข้อผิดพลาด: ' + (res.message || ''), 'error');
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

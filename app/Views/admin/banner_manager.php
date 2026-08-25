<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $cfg = $bannerCfg ?? []; ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-images text-primary me-2"></i>จัดการแบนเนอร์และรูปแบบเลย์เอาต์เว็บ (Portal Banner & Layout Manager)</h4>
        <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
            ปรับแต่งโครงสร้างการแสดงผล (Hybrid Widescreen vs Modern Boxed) และจัดการกราฟิก Multi-Layer ของป้ายโฆษณา <span class="badge bg-success ms-2">Real-Time Frontend Sync</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-warning fw-bold px-3 py-2" onclick="resetBannersToDefault()" style="border-radius: 12px;">
            <i class="fa-solid fa-rotate-left me-1"></i> คืนค่าเริ่มต้น
        </button>
        <button type="button" class="btn-modern px-4 py-2" onclick="saveBannerSettings()">
            <i class="fa-solid fa-floppy-disk me-2"></i> บันทึกและแสดงผลทันที
        </button>
    </div>
</div>

<ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="bannerTab" role="tablist" style="border-color: var(--glass-border) !important;">
    <li class="nav-item" role="presentation">
        <button class="tab-pill active" id="mode-tab" data-bs-toggle="pill" data-bs-target="#tab-mode" type="button" role="tab">
            <i class="fa-solid fa-desktop me-2"></i> 1. เลือกโหมดเลย์เอาต์ (Layout Mode)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="tab-pill" id="slides-tab" data-bs-toggle="pill" data-bs-target="#tab-slides" type="button" role="tab">
            <i class="fa-solid fa-layer-group me-2"></i> 2. จัดการสไลด์และกราฟิก Multi-Layer
        </button>
    </li>
</ul>

<form id="bannerConfigForm">
    <input type="hidden" name="slides_json" id="slides_json" value="">
    
    <div class="tab-content" id="bannerTabContent">
        <!-- ================= TAB 1: LAYOUT MODE SELECTION ================= -->
        <div class="tab-pane fade show active" id="tab-mode" role="tabpanel">
            
            <!-- MASTER BANNER VISIBILITY TOGGLE -->
            <div class="card border-0 mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, rgba(2, 44, 34, 0.95), rgba(6, 78, 59, 0.9)); border: 2px solid #10b981 !important;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-circle d-flex align-items-center justify-content-center text-white shadow" style="background: #10b981; width: 50px; height: 50px; flex-shrink: 0;">
                            <i class="fa-solid fa-power-off fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-eye me-2"></i>เปิด / ปิด การแสดงผลแบนเนอร์หน้าแรก (Hero Banner Toggle)</h5>
                            <p class="text-light opacity-85 mb-0" style="font-size: 0.92rem;">
                                กำหนดว่าจะให้แสดงแถบสไลด์ภาพและแอนิเมชันขนาดใหญ่บนหน้าหลักหรือไม่ (หากปิด ระบบจะเลื่อนเนื้อหาหลักขึ้นมาติดแถบเมนูด้านบนทันที)
                            </p>
                        </div>
                    </div>
                    <div class="form-check form-switch m-0" style="transform: scale(1.4); transform-origin: right center;">
                        <input class="form-check-input" type="checkbox" name="show_banner" id="show_banner" value="1" <?= (!isset($cfg['show_banner']) || $cfg['show_banner'] == '1') ? 'checked' : '' ?>>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-expand me-2"></i>เลือกโครงสร้างหน้าจอแบนเนอร์หลักบนพอร์ตัลประชาชน</h6>
            
            <div class="row g-4 mb-5">
                <!-- Option 1: Hybrid Widescreen -->
                <div class="col-lg-6">
                    <label class="w-100 h-100 m-0" style="cursor: pointer;">
                        <input type="radio" name="layout_mode" value="hybrid_widescreen" class="d-none peer-radio" <?= (($cfg['layout_mode'] ?? 'hybrid_widescreen') === 'hybrid_widescreen') ? 'checked' : '' ?>>
                        <div class="layout-card p-4 h-100 rounded-4 transition-all" style="background: var(--glass-bg); border: 2px solid var(--glass-border); box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-warning text-dark fw-bold px-3 py-1"><i class="fa-solid fa-star me-1"></i> แนะนำสำหรับเว็บเทศบาลและระดับจังหวัด</span>
                                <div class="radio-check-indicator"><i class="fa-solid fa-circle-check fs-4 text-muted"></i></div>
                            </div>
                            <div class="layout-preview-box mb-3 rounded-3 overflow-hidden position-relative" style="height: 160px; background: #081d26; border: 1px solid rgba(255,255,255,0.15);">
                                <!-- Widescreen mock -->
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center position-relative" style="background: linear-gradient(90deg, #09303d 0%, #154c60 50%, #09303d 100%);">
                                    <div class="position-absolute start-0 end-0 top-0 bottom-0 d-flex align-items-center justify-content-center" style="border-left: 2px dashed #6fd3c6; border-right: 2px dashed #6fd3c6; max-width: 65%; margin: auto;">
                                        <span class="text-white-50 fw-bold" style="font-size: 0.75rem;"><i class="fa-solid fa-shield-halved text-success me-1"></i> Inner Safe-Area Content Zone</span>
                                    </div>
                                    <span class="position-absolute start-0 top-0 bg-info text-dark fw-bold px-2 py-1" style="font-size: 0.65rem;">100% Widescreen Edge-to-Edge</span>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-primary">Hybrid Widescreen (สุดขอบหน้าจอ 100% + Safe Content)</h5>
                            <p class="text-secondary mb-0" style="font-size: 0.92rem; line-height: 1.5;">
                                ปลดปล่อยภาพทิวทัศน์และแอนิเมชันให้แผ่กว้างเต็มเบราว์เซอร์ 100% สร้างอิมแพ็คและความสง่างามสูงสุด (เช่นเดียวกับเว็บเทศบาลนครยะลา) โดยมีเข็มขัดกันกระจัดกระจาย ยึดข้อความและปุ่มกดให้อยู่กลางจออ่านสบายในทุกหน้าจอ คอมพิวเตอร์หรือทีวี 4K
                            </p>
                        </div>
                    </label>
                </div>

                <!-- Option 2: Modern Boxed Card -->
                <div class="col-lg-6">
                    <label class="w-100 h-100 m-0" style="cursor: pointer;">
                        <input type="radio" name="layout_mode" value="modern_boxed" class="d-none peer-radio" <?= (($cfg['layout_mode'] ?? '') === 'modern_boxed') ? 'checked' : '' ?>>
                        <div class="layout-card p-4 h-100 rounded-4 transition-all" style="background: var(--glass-bg); border: 2px solid var(--glass-border); box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-secondary fw-bold px-3 py-1">สไตล์โมเดิร์นคลาสสิก (Classic Elegant)</span>
                                <div class="radio-check-indicator"><i class="fa-solid fa-circle-check fs-4 text-muted"></i></div>
                            </div>
                            <div class="layout-preview-box mb-3 rounded-3 overflow-hidden d-flex align-items-center justify-content-center p-3" style="height: 160px; background: #0b1a20; border: 1px solid rgba(255,255,255,0.15);">
                                <!-- Boxed mock -->
                                <div class="w-100 h-100 rounded-3 d-flex align-items-center justify-content-center position-relative shadow" style="background: linear-gradient(135deg, #104253 0%, #1e7087 100%); border: 2px solid #6fd3c6; max-width: 85%;">
                                    <span class="text-white fw-bold" style="font-size: 0.8rem;"><i class="fa-solid fa-box me-1"></i> Boxed Card Container (Border Radius 24px)</span>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2 text-primary">Modern Boxed Card (กรอบมนลอยตัวในพื้นที่ 1,400px)</h5>
                            <p class="text-secondary mb-0" style="font-size: 0.92rem; line-height: 1.5;">
                                จัดแถบแบนเนอร์ให้อยู่ภายในกรอบกลางของเว็บไซต์ โดดเด่นด้วยมุมโค้งมน (Rounded Corners 24px) และเงาลึก 3 มิติ สุภาพเรียบร้อย เหมาะสำหรับการจัดสรรพื้นที่หน้าเว็บให้เป็นหมวดหมู่กะทัดรัด สบายตาในรูปแบบพรีเมียมการ์ด
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-sliders me-2"></i>ตัวตั้งค่าเพิ่มเติมของแบนเนอร์และเลเยอร์แอนิเมชัน</h6>
            <div class="glass-card p-4 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-arrows-up-down me-1"></i> ความสูงแบนเนอร์ (Banner Height)</label>
                        <select name="banner_height" class="form-select modern-input">
                            <option value="480" <?= (($cfg['banner_height'] ?? '') == '480') ? 'selected' : '' ?>>480 พิกเซล (กะทัดรัด - โหลดเร็ว)</option>
                            <option value="540" <?= (($cfg['banner_height'] ?? '540') == '540') ? 'selected' : '' ?>>540 พิกเซล (สมดุลมาตรฐาน - แนะนำ)</option>
                            <option value="600" <?= (($cfg['banner_height'] ?? '') == '600') ? 'selected' : '' ?>>600 พิกเซล (จอกว้างโรงหนัง - โอ่อ่าอลังการ)</option>
                        </select>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <label class="form-label fw-bold text-primary"><i class="fa-solid fa-stopwatch me-1"></i> ความเร็วสลับสไลด์ (Carousel Interval)</label>
                        <select name="interval_ms" class="form-select modern-input">
                            <option value="5000" <?= (($cfg['interval_ms'] ?? '') == '5000') ? 'selected' : '' ?>>5 วินาที (รวดเร็ว)</option>
                            <option value="7500" <?= (($cfg['interval_ms'] ?? '7500') == '7500') ? 'selected' : '' ?>>7.5 วินาที (มาตรฐาน - อ่านง่าย)</option>
                            <option value="10000" <?= (($cfg['interval_ms'] ?? '') == '10000') ? 'selected' : '' ?>>10 วินาที (ค่อยเป็นค่อยไป)</option>
                        </select>
                    </div>

                    <div class="col-md-12 col-lg-4 d-flex flex-column justify-content-center">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="show_weather" id="show_weather" <?= (!empty($cfg['show_weather']) && $cfg['show_weather'] != '0') ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold ms-2" for="show_weather">
                                <i class="fa-solid fa-cloud-sun text-warning me-1"></i> แสดงรายงานอากาศ & จุดชมวิว (Weather Node)
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="show_giahs" id="show_giahs" <?= (!empty($cfg['show_giahs']) && $cfg['show_giahs'] != '0') ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold ms-2" for="show_giahs">
                                <i class="fa-solid fa-gem text-info me-1"></i> แสดงตรามรดกเกษตรโลก (GIAHS Badge)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= TAB 2: SLIDE MANAGER & KINETIC LAYERS ================= -->
        <div class="tab-pane fade" id="tab-slides" role="tabpanel">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-primary mb-0"><i class="fa-solid fa-layer-group me-2"></i>รายการสไลด์แอนิเมชันที่เปิดแสดงผลบนพอร์ตัลหลัก</h6>
                <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-2 rounded-pill" onclick="addNewSlide()">
                    <i class="fa-solid fa-plus me-1"></i> + เพิ่มสไลด์ใหม่ (Add Slide)
                </button>
            </div>

            <div id="slidesContainer" class="d-flex flex-column gap-4">
                <!-- Slides will be injected here via JavaScript -->
            </div>
        </div>
    </div>
</form>

<!-- CSS for Radio Card Highlight -->
<style>
.peer-radio:checked + .layout-card {
    border-color: #ff9600 !important;
    background: rgba(255, 150, 0, 0.08) !important;
    box-shadow: 0 15px 35px rgba(255, 150, 0, 0.25) !important;
}
.peer-radio:checked + .layout-card .radio-check-indicator i {
    color: #ff9600 !important;
}
.layout-card:hover {
    transform: translateY(-4px);
    border-color: var(--theme-accent);
}
</style>

<script>
// Pass server banners JSON to client script
let BANNERS_DATA = <?= json_encode($banners ?? []) ?>;

document.addEventListener("DOMContentLoaded", function() {
    renderSlidesList();
});

function renderSlidesList() {
    const container = document.getElementById('slidesContainer');
    container.innerHTML = '';

    if (!BANNERS_DATA || BANNERS_DATA.length === 0) {
        container.innerHTML = `<div class="alert alert-warning text-center p-4 rounded-4"><i class="fa-solid fa-triangle-exclamation me-2"></i>ยังไม่มีสไลด์ที่กำหนดไว้ กรุณาคลิก "เพิ่มสไลด์ใหม่"</div>`;
        return;
    }

    BANNERS_DATA.forEach((slide, idx) => {
        let imgDisplay = slide.image_path ? `<?= base_url() ?>/${slide.image_path}` : 'https://placehold.co/600x250/113f4f/ffffff?text=No+Custom+Image+(Using+Kinetic+Vectors)';
        let floatingImgDisplay = slide.floating_img_path ? `<?= base_url() ?>/${slide.floating_img_path}` : 'https://placehold.co/200x100/0c1628/60a5fa?text=ยังไม่ได้เลือกภาพกราฟิกลอยตัว+(PNG)';

        const card = document.createElement('div');
        card.className = "glass-card p-4 rounded-4 position-relative";
        card.style.background = "var(--glass-bg)";
        card.style.border = "1px solid var(--glass-border)";

        card.innerHTML = `
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--glass-border) !important;">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary text-white fs-6 px-3 py-2 rounded-pill">สไลด์ลำดับที่ #${idx + 1}</span>
                    <span class="badge ${slide.active !== false ? 'bg-success' : 'bg-secondary'} px-2 py-1">${slide.active !== false ? 'เปิดแสดงผล' : 'ปิดชั่วคราว'}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" onclick="deleteSlide(${idx})" title="ลบสไลด์">
                        <i class="fa-solid fa-trash-can me-1"></i> ลบรายการ
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="p-3 rounded-4 bg-dark border border-secondary text-center position-relative mb-2">
                        <img src="${imgDisplay}" id="preview_img_${idx}" class="img-fluid rounded-3" style="max-height: 180px; object-fit: cover; width: 100%;">
                        <div class="mt-2">
                            <label class="btn btn-sm btn-info fw-bold rounded-pill px-3 m-0" style="cursor: pointer;">
                                <i class="fa-solid fa-cloud-arrow-up me-1"></i> อัปโหลดรูปภาพใหม่...
                                <input type="file" class="d-none" accept="image/*" onchange="uploadSlideImage(event, ${idx})">
                            </label>
                        </div>
                    </div>
                    <small class="text-muted d-block text-center">💡 แนะนำไฟล์ PNG/JPG ขนาดกว้าง 1920x600 px ขึ้นไป (ระบบจะทำ Kinetic Ken-Burns ให้อัตโนมัติ)</small>
                </div>

                <div class="col-lg-7">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-warning"><i class="fa-solid fa-wand-magic-sparkles me-1"></i> ธีมกราฟิกลอยตัว (Multi-Layer Effect)</label>
                            <select class="form-select modern-input" onchange="updateSlideProp(${idx}, 'bg_type', this.value); renderSlidesList();">
                                <option value="custom_layer" ${slide.bg_type === 'custom_layer' ? 'selected' : ''}>🌟 อัปโหลดกราฟิกชั้นลอยเอง (Custom Floating PNG/WebP)</option>
                                <option value="image" ${slide.bg_type === 'image' || !slide.bg_type ? 'selected' : ''}>✨ โหมดภาพสวยสะอาดตา (Clean Image & Ken-Burns Zoom)</option>
                                <option value="kinetic_pole" ${slide.bg_type === 'kinetic_pole' ? 'selected' : ''}>📡 เสา AI & โครงข่าย WiFi อัจฉริยะ (Smart Living Nodes)</option>
                                <option value="kinetic_nature" ${slide.bg_type === 'kinetic_nature' ? 'selected' : ''}>🌿 ไอคอนธรรมชาติ & ท่องเที่ยว (Eco-Tourism VR 360)</option>
                                <option value="kinetic_gov" ${slide.bg_type === 'kinetic_gov' ? 'selected' : ''}>🏛️ บริการราชการ 24 ชม. & โล่ PDPA (Digital Governance)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-info"><i class="fa-solid fa-layer-group me-1"></i> รูปแบบการวางกล่องข้อความ (Card Layout)</label>
                            <select class="form-select modern-input" onchange="updateSlideProp(${idx}, 'card_placement', this.value)">
                                <option value="dock_bottom_right" ${slide.card_placement === 'dock_bottom_right' || (!slide.card_placement && idx === 0) ? 'selected' : ''}>🗂️ แท่นลอยมุมล่างขวา (ไม่บังวิวกึ่งกลางภาพ)</option>
                                <option value="split_right" ${slide.card_placement === 'split_right' || (!slide.card_placement && idx !== 0) ? 'selected' : ''}>➡️ กล่องข้อความทางขวา (Split-Screen Right)</option>
                                <option value="split_left" ${slide.card_placement === 'split_left' ? 'selected' : ''}>⬅️ กล่องข้อความทางซ้าย (Split-Screen Left)</option>
                                <option value="center_overlay" ${slide.card_placement === 'center_overlay' ? 'selected' : ''}>🎯 ข้อความใหญ่กึ่งกลางจอ (Center Cinematic)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded-4 border border-warning" style="background: rgba(255, 193, 7, 0.06); box-shadow: 0 4px 15px rgba(255, 193, 7, 0.12);">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-warning m-0"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>อัปโหลดภาพชั้นลอยตัวของคุณเอง (Custom Floating Graphic Layer)</h6>
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold">Multi-Layer DIY</span>
                                </div>
                                <div class="row align-items-center g-3">
                                    <div class="col-sm-4 text-center">
                                        <div class="p-2 bg-dark rounded-3 border border-secondary mb-2 position-relative">
                                            <img src="${floatingImgDisplay}" id="preview_floating_img_${idx}" class="img-fluid" style="max-height: 95px; object-fit: contain;">
                                        </div>
                                        <label class="btn btn-sm btn-warning fw-bold rounded-pill px-3 m-0 text-dark w-100 shadow-sm" style="cursor: pointer;">
                                            <i class="fa-solid fa-cloud-arrow-up me-1"></i> เลือกไฟล์ชั้นลอย (PNG/WebP)...
                                            <input type="file" class="d-none" accept="image/*" onchange="uploadFloatingLayer(event, ${idx})">
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-warning small fw-bold mb-1"><i class="fa-solid fa-location-dot me-1"></i>ตำแหน่งจัดวางบนจอ (Position)</label>
                                                <select class="form-select modern-input form-select-sm" onchange="updateSlideProp(${idx}, 'floating_pos', this.value)">
                                                    <option value="left_center" ${slide.floating_pos === 'left_center' || !slide.floating_pos ? 'selected' : ''}>↖️ ฝั่งซ้ายกึ่งกลาง (Left Center)</option>
                                                    <option value="right_center" ${slide.floating_pos === 'right_center' ? 'selected' : ''}>↗️ ฝั่งขวากึ่งกลาง (Right Center)</option>
                                                    <option value="top_center" ${slide.floating_pos === 'top_center' ? 'selected' : ''}>⬆️ ลอยตรงกลางด้านบน (Top Center)</option>
                                                    <option value="bottom_left" ${slide.floating_pos === 'bottom_left' ? 'selected' : ''}>↙️ ลอยมุมล่างซ้าย (Bottom Left)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-warning small fw-bold mb-1"><i class="fa-solid fa-running me-1"></i>แอนิเมชันเคลื่อนไหว (Animation)</label>
                                                <select class="form-select modern-input form-select-sm" onchange="updateSlideProp(${idx}, 'floating_anim', this.value)">
                                                    <option value="float_bounce" ${slide.floating_anim === 'float_bounce' || !slide.floating_anim ? 'selected' : ''}>🎈 ลอยตัวขึ้นลงนุ่มนวล (Gentle Breathing Float)</option>
                                                    <option value="pulse_glow" ${slide.floating_anim === 'pulse_glow' ? 'selected' : ''}>🌟 เรืองแสงมีมิติ (Pulse Glow)</option>
                                                    <option value="slide_left" ${slide.floating_anim === 'slide_left' ? 'selected' : ''}>⚡ เลื่อนพุ่งเข้าจอ (Cinematic Entry)</option>
                                                </select>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <small class="text-white-50 d-block" style="font-size: 0.8rem;">💡 คำแนะนำ: เมื่ออัปโหลดไฟล์เรียบร้อย ระบบจะปรับแถบ <b>ธีมกราฟิก</b> ด้านบนให้อยู่ในโหมด <b>'🌟 อัปโหลดกราฟิกชั้นลอยเอง'</b> โดยอัตโนมัติ!</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary"><i class="fa-solid fa-heading me-1"></i> หัวข้อ/คำขวัญสไลด์ (Title)</label>
                            <input type="text" class="form-control modern-input" value="${slide.title || ''}" onchange="updateSlideProp(${idx}, 'title', this.value)" placeholder="เช่น เสน่ห์เมืองลุง เขา ป่า นา เล...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary"><i class="fa-solid fa-tag me-1"></i> ข้อความป้ายตราสัญลักษณ์ (Badge Title)</label>
                            <input type="text" class="form-control modern-input" value="${slide.badge_title || ''}" onchange="updateSlideProp(${idx}, 'badge_title', this.value)" placeholder="เช่น LANDMARK / ECO & HERITAGE">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-primary"><i class="fa-solid fa-align-left me-1"></i> คำอธิบายรายละเอียด (Description)</label>
                            <textarea class="form-control modern-input" rows="2" onchange="updateSlideProp(${idx}, 'desc', this.value)" placeholder="รายละเอียดเชิญชวนประชาชนหรือนักท่องเที่ยว...">${slide.desc || ''}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary"><i class="fa-solid fa-link me-1"></i> ข้อความบนปุ่มกด (Button Text)</label>
                            <input type="text" class="form-control modern-input" value="${slide.button_text || ''}" onchange="updateSlideProp(${idx}, 'button_text', this.value)" placeholder="เช่น เปิดโลกท่องเที่ยว">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-primary"><i class="fa-solid fa-share me-1"></i> ลิงก์ปลายทางเมื่อคลิกปุ่ม (URL / Anchor)</label>
                            <input type="text" class="form-control modern-input" value="${slide.button_url || ''}" onchange="updateSlideProp(${idx}, 'button_url', this.value)" placeholder="เช่น #tourism หรือ https://...">
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(card);
    });
}

function updateSlideProp(idx, prop, val) {
    BANNERS_DATA[idx][prop] = val;
}

function addNewSlide() {
    const newSlide = {
        id: Date.now(),
        title: 'โครงการเมืองอัจฉริยะ พัทลุงยั่งยืน',
        badge_title: 'NEWS & EVENT',
        badge_icon: 'fa-solid fa-bullhorn',
        bg_type: 'image',
        card_placement: 'dock_bottom_right',
        image_path: 'assets/images/slider/sane_muanglung.png',
        desc: 'ประชาสัมพันธ์ความก้าวหน้าโครงการพัฒนาโครงสร้างพื้นฐานด้านดิจิทัลและส่งเสริมการท่องเที่ยวทะเลน้อย 360 องศา',
        button_text: 'อ่านเพิ่มเติม',
        button_url: '#news',
        active: true
    };
    BANNERS_DATA.push(newSlide);
    renderSlidesList();
    App.toast('เพิ่มรายการสไลด์ใหม่แล้ว อย่าลืมกดบันทึกการเปลี่ยนแปลงทั้งหมด', 'success');
}

function deleteSlide(idx) {
    if (confirm('ยืนยันการลบสไลด์ลำดับที่ #' + (idx + 1) + ' นี้หรือไม่?')) {
        BANNERS_DATA.splice(idx, 1);
        renderSlidesList();
        App.toast('ลบรายการเรียบร้อย', 'info');
    }
}

function uploadSlideImage(event, idx) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('slide_image', file);

    App.toast('กำลังอัปโหลดไฟล์รูปภาพสไลด์...', 'info');

    fetch('<?= base_url("admin/banners/upload") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            BANNERS_DATA[idx].image_path = data.path;
            const previewImg = document.getElementById('preview_img_' + idx);
            if (previewImg) previewImg.src = data.url;
            App.toast('🎉 ' + data.message, 'success');
        } else {
            App.toast('❌ ' + (data.message || 'การอัปโหลดล้มเหลว'), 'danger');
        }
    })
    .catch(err => {
        console.error('Error uploading slide image:', err);
        App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'danger');
    });
}

function uploadFloatingLayer(event, idx) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('slide_image', file);

    App.toast('กำลังอัปโหลดไฟล์กราฟิกเลเยอร์ลอยตัว...', 'info');

    fetch('<?= base_url("admin/banners/upload") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            BANNERS_DATA[idx].floating_img_path = data.path;
            BANNERS_DATA[idx].bg_type = 'custom_layer';
            renderSlidesList();
            App.toast('🎉 อัปโหลดเลเยอร์ลอยตัวและเปิดใช้งานโหมด Custom เรียบร้อยแล้ว!', 'success');
        } else {
            App.toast('❌ ' + (data.message || 'การอัปโหลดล้มเหลว'), 'danger');
        }
    })
    .catch(err => {
        console.error('Error uploading floating layer:', err);
        App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'danger');
    });
}

function saveBannerSettings() {
    // Sync JSON before submit
    document.getElementById('slides_json').value = JSON.stringify(BANNERS_DATA);

    const form = document.getElementById('bannerConfigForm');
    const formData = new FormData(form);

    App.toast('กำลังจัดทำข้อมูลและซิงค์เลย์เอาต์เว็บจริง...', 'info');

    fetch('<?= base_url("admin/banners/save") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            App.toast('⚡ ' + data.message, 'success');
        } else {
            App.toast('❌ เกิดข้อผิดพลาดในการบันทึก', 'danger');
        }
    })
    .catch(err => {
        console.error('Error saving banner configuration:', err);
        App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'danger');
    });
}

function resetBannersToDefault() {
    if (!confirm('ยืนยันการล้างค่าที่ปรับแต่งทั้งหมด และคืนกลับเป็นระบบเริ่มต้นหรือไม่?')) return;

    fetch('<?= base_url("admin/banners/reset") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            App.toast('🔄 ' + data.message, 'success');
            setTimeout(() => window.location.reload(), 800);
        } else {
            App.toast('❌ ไม่สามารถคืนค่าเริ่มต้นได้', 'danger');
        }
    })
    .catch(err => {
        console.error('Error resetting banners:', err);
    });
}
</script>
<?= $this->endSection() ?>

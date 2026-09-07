<?= $this->extend('layouts/main') ?>

<?php 
$services = $services ?? [];
$cfg = function_exists('get_site_settings') ? get_site_settings() : [];
$layoutMode = $cfg['layout_mode'] ?? 'hybrid_widescreen';
?>

<?php if ($layoutMode === 'hybrid_widescreen'): ?>
    <?php $this->section('hero_banner') ?>
        <?= $this->include('components/hero_banner') ?>
    <?php $this->endSection() ?>
<?php endif; ?>

<?= $this->section('content') ?>

<?php if ($layoutMode !== 'hybrid_widescreen'): ?>
    <?= $this->include('components/hero_banner') ?>
<?php endif; ?>

<style>
/* ==========================================================================
   Provincial Citizen Search Dock (Warm, Natural, Accessible & Prestigious)
   ========================================================================== */
.provincial-search-card {
    background: #ffffff;
    border-radius: 22px;
    border: 1px solid #e2e8f0 !important;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
    transition: all 0.3s ease;
}
.provincial-search-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #047857 0%, #10b981 35%, #d97706 70%, #047857 100%);
}
[data-theme="dark"] .provincial-search-card {
    background: #1e293b;
    border-color: #334155 !important;
    box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.4);
}
[data-theme="dark"] .provincial-search-card .text-dark {
    color: #f1f5f9 !important;
}
[data-theme="dark"] .provincial-search-card .text-secondary {
    color: #94a3b8 !important;
}
[data-theme="dark"] .provincial-search-card input.text-dark {
    color: #f8fafc !important;
}
.search-icon-badge {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
[data-theme="dark"] .search-icon-badge {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border-color: rgba(16, 185, 129, 0.3);
}
.search-dock-input-wrap {
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    border-radius: 50px;
    padding: 6px 8px;
    transition: all 0.25s ease;
}
.search-dock-input-wrap:focus-within {
    background: #ffffff;
    border-color: #059669;
    box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
}
[data-theme="dark"] .search-dock-input-wrap {
    background: #0f172a;
    border-color: #334155;
}
[data-theme="dark"] .search-dock-input-wrap:focus-within {
    border-color: #34d399;
    box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.15);
}
.voice-search-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    transition: all 0.2s ease;
}
.voice-search-btn:hover {
    background: #ecfdf5;
    color: #047857;
    border-color: #a7f3d0;
}
.voice-search-btn.recording {
    background: #fee2e2 !important;
    color: #dc2626 !important;
    border-color: #f87171 !important;
    animation: micGentlePulse 1.2s infinite;
}
@keyframes micGentlePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.08); }
}
.voice-listening-toast {
    background: #0f172a;
    color: #ffffff;
    border-radius: 30px;
    padding: 8px 18px;
    border: 1px solid #334155;
    font-size: 0.88rem;
}
.trending-tag-pill {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    border-radius: 50px;
    padding: 4px 12px;
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.trending-tag-pill:hover {
    background: #ecfdf5;
    color: #047857;
    border-color: #a7f3d0;
    transform: translateY(-1px);
}
[data-theme="dark"] .trending-tag-pill {
    background: #334155;
    color: #cbd5e1;
    border-color: #475569;
}
[data-theme="dark"] .trending-tag-pill:hover {
    background: rgba(16, 185, 129, 0.2);
    color: #34d399;
    border-color: #34d399;
}
[data-theme="dark"] #searchResultsDropdown {
    background: #1e293b !important;
    border-color: #334155 !important;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4) !important;
}
/* Quick Citizen Services Strip */
.quick-service-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    position: relative;
    overflow: hidden;
}
.quick-service-pill:hover {
    border-color: #059669;
    box-shadow: 0 10px 20px -4px rgba(5, 150, 105, 0.12);
    transform: translateY(-2px);
}
.quick-service-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.25s ease;
}
.quick-service-pill:hover .quick-service-icon-wrap {
    transform: scale(1.08);
}
.quick-service-title {
    font-size: 0.95rem;
    line-height: 1.3;
}
.quick-service-sub {
    font-size: 0.76rem;
    line-height: 1.2;
}
[data-theme="dark"] .quick-service-pill {
    background: #1e293b;
    border-color: #334155;
    box-shadow: 0 4px 14px rgba(0,0,0,0.3);
}
[data-theme="dark"] .quick-service-pill:hover {
    border-color: #34d399;
}
[data-theme="dark"] .quick-service-pill .text-dark {
    color: #f1f5f9 !important;
}
[data-theme="dark"] .quick-service-pill .text-secondary {
    color: #94a3b8 !important;
}
</style>

<!-- 1.1 PROVINCIAL CITIZEN SEARCH DOCK (Warm, Accessible & Dignified) -->
<section class="mb-4 position-relative z-3">
    <div class="card border-0 p-3 p-lg-4 provincial-search-card">
        <div class="row align-items-center g-3">
            
            <!-- Search Title & Subtitle Badge -->
            <div class="col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center gap-3">
                    <span class="search-icon-badge shadow-xs">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.12rem; letter-spacing: -0.2px;">
                            <?= site_text('search_dock_title', 'สืบค้นข้อมูลและบริการประชาชน', 'หัวข้อระบบค้นหา') ?>
                        </h5>
                        <small class="text-secondary" style="font-size: 0.8rem;">
                            <?= site_text('search_dock_subtitle', 'ศูนย์บริการข้อมูลข่าวสารและบริการภาครัฐ', 'คำโปรยระบบค้นหา') ?>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Search Input with Voice Dictation -->
            <div class="col-lg-9 position-relative">
                <div class="search-dock-input-wrap d-flex align-items-center shadow-xs" id="mainSearchWrapper">
                    
                    <div class="d-flex align-items-center justify-content-center text-muted ps-2 pe-1" style="font-size: 1.05rem;">
                        <i class="fa-solid fa-search" style="color: #059669;"></i>
                    </div>
                    
                    <input type="text" id="globalSearchInput" 
                           placeholder="<?= site_text('search_input_placeholder', 'ค้นหาข้อมูล เช่น ศูนย์ดำรงธรรม, ทะเลน้อย, แผนพัฒนาจังหวัด, e-Bidding...', 'ข้อความกล่องค้นหา', true) ?>" 
                           class="form-control border-0 bg-transparent px-2 shadow-none text-dark"
                           style="font-size: 0.98rem; font-weight: 500;" autocomplete="off"
                           onkeydown="if(event.key === 'Enter') triggerSearchSubmit();">
                    
                    <!-- Search Spinner -->
                    <div id="searchSpinner" class="spinner-border text-success spinner-border-sm mx-2 d-none" role="status" style="width: 1.2rem; height: 1.2rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <!-- Voice Search Button -->
                    <button type="button" id="btnVoiceSearch" class="btn btn-sm d-flex align-items-center justify-content-center me-1 voice-search-btn" 
                            title="ค้นหาด้วยเสียงพูด (Voice Search)" 
                            onclick="toggleVoiceSearch()">
                        <i class="fa-solid fa-microphone" id="voiceMicIcon" style="font-size: 0.95rem;"></i>
                    </button>
                    
                    <!-- Submit / Search Button -->
                    <button type="button" class="btn rounded-pill px-4 py-2 me-1 fw-bold text-white shadow-xs transition-all hover-scale" 
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none; white-space: nowrap; font-size: 0.95rem;" 
                            onclick="triggerSearchSubmit()">
                        <i class="fa-solid fa-magnifying-glass me-1 opacity-75"></i>
                        <span>ค้นหา</span>
                    </button>
                </div>

                <!-- Voice Listening Status Bar (Pop-down feedback) -->
                <div id="voiceListeningBar" class="d-none align-items-center justify-content-between px-3 py-2 mt-2 voice-listening-toast shadow-md">
                    <div class="d-flex align-items-center gap-2">
                        <span class="spinner-grow spinner-grow-sm text-warning" role="status" aria-hidden="true"></span>
                        <span id="voiceListeningStatusText" class="ms-1 fw-medium text-warning">กำลังรับฟังเสียงของคุณ (พูดคำที่ต้องการค้นหา)...</span>
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-light rounded-pill px-2.5 py-0.5" style="font-size: 0.75rem;" onclick="stopVoiceSearch()">ยกเลิก</button>
                </div>

                <!-- Quick Trending Search Pills -->
                <?php 
                $trendingKeywords = function_exists('get_trending_keywords') ? get_trending_keywords(6) : [];
                if (!empty($trendingKeywords)): 
                ?>
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2 pt-2">
                        <span class="text-secondary small fw-medium" style="font-size: 0.8rem;">
                            <i class="fa-solid fa-fire text-amber-500 me-1" style="color: #d97706;"></i> บริการยอดนิยม:
                        </span>
                        <?php foreach ($trendingKeywords as $item): 
                            $kw = is_array($item) ? ($item['keyword'] ?? '') : $item;
                            $icon = is_array($item) ? ($item['icon'] ?? '') : '';
                            if (empty($kw)) continue;
                        ?>
                            <a href="javascript:void(0)" onclick="quickSearchTag('<?= esc($kw) ?>')" class="trending-tag-pill shadow-xs">
                                <?php if (!empty($icon)): ?><span><?= $icon ?></span><?php endif; ?>
                                <span><?= esc($kw) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Search Results Dropdown -->
                <div id="searchResultsDropdown" class="dropdown-menu w-100 mt-2 p-0 border-0" style="border-radius: 16px; max-height: 450px; overflow-y: auto; display: none; position: absolute; z-index: 1050; background: #ffffff; box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12); border: 1px solid #e2e8f0 !important;">
                    <!-- Results will be injected here via JS -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 1.1.5 QUICK CITIZEN ACCESS SERVICES (4 ทางลัดบริการด่วนประชาชน) -->
<section class="mb-4">
    <div class="row g-2 g-md-3">
        <!-- 1. ยื่นเรื่องศูนย์ดำรงธรรม -->
        <div class="col-6 col-lg-3">
            <a href="<?= base_url('contact') ?>" class="quick-service-pill text-decoration-none d-flex align-items-center p-2.5 p-lg-3">
                <div class="quick-service-icon-wrap me-2.5" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                    <i class="fa-solid fa-scale-balanced fs-5"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="d-block fw-bold text-dark quick-service-title">ศูนย์ดำรงธรรม</span>
                    <small class="text-secondary d-none d-sm-block text-truncate quick-service-sub">ยื่นเรื่องร้องทุกข์ 24 ชม.</small>
                </div>
            </a>
        </div>

        <!-- 2. ตรวจสอบสถานะคำร้อง -->
        <div class="col-6 col-lg-3">
            <a href="<?= base_url('contact#tracking') ?>" class="quick-service-pill text-decoration-none d-flex align-items-center p-2.5 p-lg-3">
                <div class="quick-service-icon-wrap me-2.5" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;">
                    <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="d-block fw-bold text-dark quick-service-title">ติดตามคำร้อง</span>
                    <small class="text-secondary d-none d-sm-block text-truncate quick-service-sub">ตรวจสอบรหัสติดตาม</small>
                </div>
            </a>
        </div>

        <!-- 3. คลังดาวน์โหลดแบบฟอร์ม -->
        <div class="col-6 col-lg-3">
            <a href="<?= base_url('documents') ?>" class="quick-service-pill text-decoration-none d-flex align-items-center p-2.5 p-lg-3">
                <div class="quick-service-icon-wrap me-2.5" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a;">
                    <i class="fa-solid fa-file-arrow-down fs-5"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="d-block fw-bold text-dark quick-service-title">แบบฟอร์มราชการ</span>
                    <small class="text-secondary d-none d-sm-block text-truncate quick-service-sub">คลังเอกสารประชาชน</small>
                </div>
            </a>
        </div>

        <!-- 4. ประกาศจัดซื้อจัดจ้างภาครัฐ (e-Bidding) -->
        <div class="col-6 col-lg-3">
            <a href="<?= base_url('procurement') ?>" class="quick-service-pill text-decoration-none d-flex align-items-center p-2.5 p-lg-3">
                <div class="quick-service-icon-wrap me-2.5" style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;">
                    <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
                </div>
                <div class="overflow-hidden">
                    <span class="d-block fw-bold text-dark quick-service-title">จัดซื้อจัดจ้าง</span>
                    <small class="text-secondary d-none d-sm-block text-truncate quick-service-sub">ประกาศ e-GP ภาครัฐ</small>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- 1.2 PROVINCIAL POLICY, ROYAL INITIATIVES & STRATEGIC HUB (แถบวิสัยทัศน์ แบนเนอร์พระราชดำริ ปกสมุดยุทธศาสตร์ และนโยบายผู้ว่าฯ) -->
<?php 
try {
    echo $this->include('components/provincial_policy_hub');
} catch (\Throwable $e) {
    echo '<!-- provincial_policy_hub error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . ' in ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ':' . $e->getLine() . ' -->';
}
?>

<!-- 3. NEWS & MEDIA HUB (ศูนย์รวมข่าวสารและสื่อมัลติมีเดีย) -->
<?php 
try {
    echo $this->include('components/news_media_hub');
} catch (\Throwable $e) {
    echo '<!-- news_media_hub error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . ' in ' . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ':' . $e->getLine() . ' -->';
}
?>

<!-- 2. PUBLIC e-SERVICES GRID (ซ่อนไว้ชั่วคราวตามคำขอ) -->
<?php /*
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
            <div class="card border-0 h-100 hover-lift d-flex flex-column justify-content-between p-4" 
                 style="cursor: pointer; border-radius: 20px; background: var(--card-bg, #ffffff); box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: all 0.3s ease;" 
                 onclick="<?= $srv['action'] ?>"
                 onmouseover="this.style.boxShadow='0 10px 25px rgba(0,0,0,0.08)';"
                 onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.03)';">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div style="width: 54px; height: 54px; border-radius: 16px; background: <?= $srv['color'] ?>15; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; color: <?= $srv['color'] ?>;">
                            <i class="<?= $srv['icon'] ?>"></i>
                        </div>
                        <span style="color: var(--text-muted); font-size: 0.8rem; background: #f8fafc; padding: 4px 10px; border-radius: 30px;"><i class="fa-solid fa-arrow-right-to-bracket"></i> กดเข้าถึง</span>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark"><?= $srv['title'] ?></h5>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5; margin-bottom: 0;">
                        <?= $srv['desc'] ?>
                    </p>
                </div>
                <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.05) !important;">
                    <span style="font-size: 0.8rem; color: <?= $srv['color'] ?>; font-weight: 600;">
                        <i class="fa-regular fa-circle-check me-1"></i> บริการออนไลน์
                    </span>
                    <span class="text-primary fw-bold" style="font-size: 0.8rem;">อ่านคู่มือ <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
*/ ?>

<!-- 3.1 PR & PARTNER BANNERS SLIDER (แถบแบนเนอร์ประชาสัมพันธ์และหน่วยงานสัมพันธ์) -->
<?= $this->include('components/pr_banner_carousel') ?>

<!-- 4. GOVERNANCE & TRANSPARENCY HUB (ศูนย์ข้อมูลความโปร่งใสและจัดซื้อจัดจ้าง) -->
<!-- ย้ายไปเข้าผ่านเมนูด้านบนแทนการแสดงผลหน้าหลัก -->
<?php // echo $this->include('components/governance_hub'); ?>

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

// 5. Global Smart Search (Live Search & Voice Dictation)
let searchTimeout = null;
const searchInput = document.getElementById('globalSearchInput');
const searchDropdown = document.getElementById('searchResultsDropdown');
const searchSpinner = document.getElementById('searchSpinner');

// Voice Recognition Instance
let voiceRecognition = null;
let isVoiceListening = false;

function initVoiceSearch() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        return null;
    }
    const recognition = new SpeechRecognition();
    recognition.lang = 'th-TH';
    recognition.continuous = false;
    recognition.interimResults = true;

    recognition.onstart = function() {
        isVoiceListening = true;
        const micBtn = document.getElementById('btnVoiceSearch');
        const micIcon = document.getElementById('voiceMicIcon');
        const listeningBar = document.getElementById('voiceListeningBar');
        const statusText = document.getElementById('voiceListeningStatusText');
        
        if (micBtn) micBtn.classList.add('recording');
        if (micIcon) micIcon.className = 'fa-solid fa-microphone-lines';
        if (listeningBar) {
            listeningBar.classList.remove('d-none');
            listeningBar.classList.add('d-flex');
        }
        if (statusText) statusText.innerText = 'กำลังฟังเสียงของคุณ (พูดคำที่ต้องการค้นหา)...';
    };

    recognition.onresult = function(event) {
        let transcript = '';
        for (let i = event.resultIndex; i < event.results.length; ++i) {
            transcript += event.results[i][0].transcript;
        }
        const sInput = document.getElementById('globalSearchInput');
        if (sInput) {
            sInput.value = transcript;
            sInput.dispatchEvent(new Event('input'));
        }
    };

    recognition.onerror = function(event) {
        console.warn('Speech recognition error:', event.error);
        stopVoiceSearch();
        if (event.error === 'not-allowed') {
            if (typeof App !== 'undefined' && App.toast) {
                App.toast('กรุณาอนุญาตการเข้าถึงไมโครโฟนในเบราว์เซอร์', 'warning');
            } else {
                alert('กรุณาอนุญาตการเข้าถึงไมโครโฟนในเบราว์เซอร์');
            }
        }
    };

    recognition.onend = function() {
        stopVoiceSearch();
        const sInput = document.getElementById('globalSearchInput');
        if (sInput && sInput.value.trim().length > 0) {
            sInput.dispatchEvent(new Event('input'));
        }
    };

    return recognition;
}

function toggleVoiceSearch() {
    if (isVoiceListening) {
        stopVoiceSearch();
        return;
    }

    if (!voiceRecognition) {
        voiceRecognition = initVoiceSearch();
    }

    if (!voiceRecognition) {
        if (typeof App !== 'undefined' && App.toast) {
            App.toast('เบราว์เซอร์นี้ไม่รองรับระบบสั่งงานด้วยเสียง (Speech Recognition)', 'warning');
        } else {
            alert('เบราว์เซอร์นี้ไม่รองรับระบบสั่งงานด้วยเสียง');
        }
        return;
    }

    try {
        voiceRecognition.start();
    } catch (err) {
        console.warn('Voice recognition start error:', err);
    }
}

function stopVoiceSearch() {
    if (voiceRecognition && isVoiceListening) {
        try { voiceRecognition.stop(); } catch(e) {}
    }
    isVoiceListening = false;
    const micBtn = document.getElementById('btnVoiceSearch');
    const micIcon = document.getElementById('voiceMicIcon');
    const listeningBar = document.getElementById('voiceListeningBar');

    if (micBtn) micBtn.classList.remove('recording');
    if (micIcon) micIcon.className = 'fa-solid fa-microphone';
    if (listeningBar) {
        listeningBar.classList.remove('d-flex');
        listeningBar.classList.add('d-none');
    }
}

function triggerSearchSubmit() {
    const sInput = document.getElementById('globalSearchInput');
    if (sInput && sInput.value.trim().length > 0) {
        window.location.href = `<?= base_url('search') ?>?q=${encodeURIComponent(sInput.value.trim())}`;
    } else if (sInput) {
        sInput.focus();
    }
}

function quickSearchTag(kw) {
    const sInput = document.getElementById('globalSearchInput');
    if (sInput) {
        sInput.value = kw;
        sInput.focus();
        sInput.dispatchEvent(new Event('input'));
    }
}

if (searchInput) {
    // ซ่อน Dropdown เมื่อคลิกที่อื่น
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.style.display = 'none';
        }
    });
    
    // โชว์ Dropdown ถ้ามีค่าเมื่อคลิก input
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0 && searchDropdown.innerHTML.trim() !== '') {
            searchDropdown.style.display = 'block';
        }
    });

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length === 0) {
            searchDropdown.style.display = 'none';
            searchSpinner.classList.add('d-none');
            return;
        }
        
        searchSpinner.classList.remove('d-none');
        
        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`<?= base_url("search") ?>?q=${encodeURIComponent(query)}`);
                const json = await res.json();
                
                searchSpinner.classList.add('d-none');
                
                if (json.success && json.data) {
                    renderSearchResults(json.data, query);
                }
            } catch (err) {
                console.error("Search Error:", err);
                searchSpinner.classList.add('d-none');
            }
        }, 400); // delay 400ms
    });
}

function renderSearchResults(results, query) {
    if (results.length === 0) {
        searchDropdown.innerHTML = `<div class="p-4 text-center text-muted"><i class="fa-solid fa-magnifying-glass fs-3 d-block mb-2 opacity-50"></i>ไม่พบข้อมูลที่ตรงกับ "${query}"</div>`;
    } else {
        let html = '<div class="list-group list-group-flush" style="border-radius: 12px; overflow: hidden;">';
        results.forEach(item => {
            const link = item.url && item.url !== '#' ? item.url : 'javascript:void(0)';
            
            const avatarHtml = item.image_url ? `
                <div class="position-relative" style="width: 44px; height: 44px; flex-shrink: 0;">
                    <img src="${item.image_url}" alt="" class="rounded-circle border border-2 border-warning shadow-sm" style="width: 44px; height: 44px; object-fit: cover; object-position: top center;">
                </div>
            ` : `
                <div style="flex-shrink: 0;">
                    <span class="badge ${item.ui_badge_color} rounded-circle p-2 shadow-sm" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                        <i class="${item.ui_icon}" style="font-size: 1.15rem;"></i>
                    </span>
                </div>
            `;

            html += `
            <a href="${link}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 p-3 text-decoration-none" style="background: transparent; border-bottom: 1px solid var(--glass-border, #f1f5f9); transition: all 0.2s;">
                ${avatarHtml}
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 fw-bold text-truncate" style="color: var(--text-primary, #0f172a); max-width: 75%; font-size: 0.98rem;">${item.title}</h6>
                        <span class="badge ${item.ui_badge_color} bg-opacity-75" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 50rem;">${item.ui_badge_text}</span>
                    </div>
                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.85rem;">${item.description || '-'}</p>
                </div>
                <div class="text-muted ms-1" style="font-size: 0.8rem; opacity: 0.6;">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            `;
        });
        html += '</div>';
        
        // Add footer for all results link
        html += `
        <div class="p-2.5 text-center border-top" style="border-color: var(--glass-border, #f1f5f9) !important; background: rgba(0,0,0,0.02);">
            <small class="text-muted"><i class="fa-solid fa-circle-check text-success me-1"></i> พบผลลัพธ์ทั้งหมด ${results.length} รายการ (คลิกเพื่อเข้าสู่หน้าเนื้อหา)</small>
        </div>
        `;
        
        searchDropdown.innerHTML = html;
    }
    searchDropdown.style.display = 'block';
}
</script>



<?= $this->endSection() ?>

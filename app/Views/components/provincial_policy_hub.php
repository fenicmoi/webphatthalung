<?php
// =========================================================================
// ส่วนแสดงผล: แบนเนอร์พระราชดำริ, แผนพัฒนาจังหวัด (ปกสมุด 3D), และนโยบายผู้ว่าราชการจังหวัด
// =========================================================================
$cfg = function_exists('get_site_settings') ? get_site_settings() : [];
$siteLogo = function_exists('get_site_logo') ? get_site_logo() : base_url('assets/images/slider/sane_muanglung.png');
$executives = function_exists('get_site_executives') ? get_site_executives() : [];
$governor = null;
if (!empty($executives)) {
    foreach ($executives as $ex) {
        if (!empty($ex['featured']) || strpos(($ex['position'] ?? ''), 'ผู้ว่าราชการ') !== false) {
            $governor = $ex;
            break;
        }
    }
    if (!$governor) $governor = $executives[0];
}

$govName = $governor['name'] ?? 'นายสุจินต์ วาจากิจ';
$govPosition = $governor['position'] ?? 'ผู้ว่าราชการจังหวัดพัทลุง';
$defaultQuote = 'รักเมืองลุง สร้างเมืองลุง ไปด้วยกัน ทำงานร่วมกัน ด้วยความสามัคคี การมีส่วนร่วม และการรับฟังความคิดเห็นของประชาชนในพื้นที่ เพื่อสร้างความเข้มแข็งจากฐานราก และยกระดับจังหวัดพัทลุง ให้มีความเจริญก้าวหน้าอย่างมั่นคง และยั่งยืนต่อไป';
$govQuote = function_exists('site_text') ? site_text('governor_policy_quote', (!empty($governor['quote']) ? $governor['quote'] : $defaultQuote), 'นโยบายและวิสัยทัศน์ผู้ว่าราชการจังหวัด', true) : (!empty($governor['quote']) ? $governor['quote'] : $defaultQuote);
$govQuote = strip_tags((string)$govQuote);
$govPhoto = !empty($governor['photo']) ? (strpos((string)$governor['photo'], 'http') === 0 ? $governor['photo'] : base_url($governor['photo'])) : base_url('uploads/executives/exec_1787543315_1787543315_5570c503c25f1ee9f002.jpg');
?>

<section class="provincial-policy-section mb-5">
    
    <!-- 0. PROVINCIAL MOTTO & CULTURAL IDENTITY BANNER (ส่วนแสดงคำขวัญและอัตลักษณ์ประจำจังหวัดพัทลุง) -->
    <div class="provincial-motto-banner mb-3 rounded-4 overflow-hidden position-relative shadow-xs">
        <div class="motto-background-layer">
            <img src="<?= base_url('assets/images/banners/phatthalung_identity_bg.jpg') ?>" alt="อัตลักษณ์จังหวัดพัทลุง เขาอกทะลุ โนราห์ หนังตะลุง ทะเลน้อย" class="w-100 h-100 object-fit-cover">
            <div class="motto-gradient-overlay"></div>
        </div>
        
        <div class="motto-content-wrap position-relative z-2 text-center py-4 px-3 w-100">
            <div class="motto-ornament mb-2">
                <img src="<?= base_url('assets/images/phatthalung_fabric_emblem.svg') ?>" alt="ลายผ้าอัตลักษณ์ประจำจังหวัดพัทลุง" style="width: 44px; height: 52px; object-fit: contain; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.35)); transition: transform 0.3s ease;" class="hover-lift">
            </div>
            
            <h5 class="motto-heading fw-bold mb-1.5">
                <i class="fa-solid fa-feather text-success me-1 opacity-75"></i>
                <span>คำขวัญประจำจังหวัดพัทลุง</span>
            </h5>
            
            <p class="motto-text fw-bold mb-0">
                “<?= site_text('site_slogan', ($cfg['slogan'] ?? 'เมืองหนังโนราห์ อู่นาข้าว พราวน้ำตก แหล่งนกน้ำ ทะเลสาบงาม เขาอกทะลุ น้ำพุร้อน'), 'คำขวัญประจำจังหวัด', true) ?>”
            </p>
        </div>
        
        <div class="motto-bottom-border"></div>
    </div>

    <!-- 1. Top Announcement / Vision Bar (แถบประกาศวิสัยทัศน์เมืองพัทลุง) -->
    <div class="gov-vision-announcement-bar mb-3 d-flex flex-wrap align-items-center shadow-xs overflow-hidden">
        <div class="gov-announce-badge d-flex align-items-center gap-2 px-3.5 py-2">
            <span class="announce-bell-icon d-inline-flex align-items-center justify-content-center">
                <i class="fa-solid fa-bell"></i>
            </span>
            <span class="announce-label fw-bold">ประกาศ</span>
        </div>
        <div class="gov-announce-ticker flex-grow-1 px-3 py-2">
            <div class="d-flex align-items-center gap-2">
                <span class="ticker-text fw-medium">
                    <?= site_text('provincial_vision_ticker', 'เมืองแห่งความยั่งยืนทางเศรษฐกิจ สังคม ความมั่นคง ทรัพยากรธรรมชาติและสิ่งแวดล้อม (Sustainability Phatthalung)', 'วิสัยทัศน์บนแถบประกาศ') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Main 3-Column Policy & Strategic Hub Grid -->
    <div class="provincial-hub-container p-3 p-lg-4 rounded-4 shadow-sm">
        <div class="row g-3 align-items-stretch">
            
            <!-- Column 1: 2x2 Royal & Provincial Initiative Banner Links (ส่วนที่ 1: แบนเนอร์ลิงก์ 2 คอลัมน์ 2 แถว) -->
            <div class="col-12 col-md-6 col-xl-4 d-flex flex-column">
                <div class="royal-banner-grid h-100 p-2 rounded-3">
                    <div class="row g-2 h-100">
                        
                        <!-- Banner 1: โครงการอันเนื่องมาจากพระราชดำริ -->
                        <div class="col-6">
                            <a href="https://www.rdpb.go.th" target="_blank" class="royal-banner-card d-flex flex-column h-100 rounded-3 overflow-hidden shadow-xs hover-lift" title="โครงการอันเนื่องมาจากพระราชดำริ">
                                <div class="royal-banner-img-wrap position-relative h-100 w-100 flex-grow-1">
                                    <img src="<?= base_url('assets/images/banners/banner_royal_project.jpg') ?>" alt="โครงการอันเนื่องมาจากพระราชดำริ" loading="lazy">
                                    <div class="royal-banner-overlay d-flex align-items-end p-2">
                                        <span class="royal-banner-caption fw-bold">โครงการอันเนื่องมาจากพระราชดำริ</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Banner 2: โครงการอนุรักษ์พันธุกรรมพืช (อพ.สธ.) -->
                        <div class="col-6">
                            <a href="http://www.rspg.or.th" target="_blank" class="royal-banner-card d-flex flex-column h-100 rounded-3 overflow-hidden shadow-xs hover-lift" title="โครงการอนุรักษ์พันธุกรรมพืชอันเนื่องมาจากพระราชดำริ (อพ.สธ.)">
                                <div class="royal-banner-img-wrap position-relative h-100 w-100 flex-grow-1">
                                    <img src="<?= base_url('assets/images/banners/banner_rspg.jpg') ?>" alt="โครงการอนุรักษ์พันธุกรรมพืช (อพ.สธ.)" loading="lazy">
                                    <div class="royal-banner-overlay d-flex align-items-end p-2">
                                        <span class="royal-banner-caption fw-bold">โครงการอนุรักษ์พันธุกรรมพืช (อพ.สธ.)</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Banner 3: จิตอาสาพระราชทาน -->
                        <div class="col-6">
                            <a href="https://www.royaloffice.th" target="_blank" class="royal-banner-card d-flex flex-column h-100 rounded-3 overflow-hidden shadow-xs hover-lift" title="จิตอาสาพระราชทาน">
                                <div class="royal-banner-img-wrap position-relative h-100 w-100 flex-grow-1">
                                    <img src="<?= base_url('assets/images/banners/banner_jitarsa.jpg') ?>" alt="จิตอาสาพระราชทาน" loading="lazy">
                                    <div class="royal-banner-overlay d-flex align-items-end p-2">
                                        <span class="royal-banner-caption fw-bold">จิตอาสาพระราชทาน</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Banner 4: ศูนย์ดำรงธรรมจังหวัดพัทลุง -->
                        <div class="col-6">
                            <a href="<?= base_url('contact') ?>" class="royal-banner-card d-flex flex-column h-100 rounded-3 overflow-hidden shadow-xs hover-lift" title="ศูนย์ดำรงธรรมจังหวัดพัทลุง 1567">
                                <div class="royal-banner-img-wrap position-relative h-100 w-100 flex-grow-1">
                                    <img src="<?= base_url('assets/images/banners/banner_damrongdhama.jpg') ?>" alt="ศูนย์ดำรงธรรมจังหวัดพัทลุง 1567" loading="lazy">
                                    <div class="royal-banner-overlay d-flex align-items-end p-2">
                                        <span class="royal-banner-caption fw-bold">ศูนย์ดำรงธรรม 1567</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Column 2: 3D Provincial Strategic Plan Book Cover (ส่วนที่ 2: แบนเนอร์รูปปกสมุดแผนพัฒนาจังหวัด) -->
            <div class="col-12 col-md-6 col-xl-3 d-flex flex-column justify-content-center align-items-center">
                <a href="<?= base_url('strategy') ?>" class="provincial-book-link text-decoration-none w-100 h-100 d-flex flex-column align-items-center justify-content-center" title="คลิกเพื่ออ่านแผนพัฒนาจังหวัดพัทลุง พ.ศ. 2566 - 2570">
                    
                    <!-- 3D Book Cover Card -->
                    <div class="book-3d-wrapper">
                        <div class="book-3d-cover d-flex flex-column justify-content-between p-3.5 text-center text-white">
                            
                            <!-- Book Top Header -->
                            <div class="book-header">
                                <h6 class="book-main-title fw-bold mb-1">แผนพัฒนาจังหวัดพัทลุง</h6>
                                <span class="book-period badge rounded-pill px-2.5 py-0.5">พ.ศ. 2566 - 2570</span>
                            </div>

                            <!-- Book Center Emblem -->
                            <div class="book-emblem-wrap my-auto py-2">
                                <div class="book-emblem-circle shadow-sm mx-auto">
                                    <img src="<?= $siteLogo ?: base_url('assets/images/slider/sane_muanglung.png') ?>" alt="ตราสัญลักษณ์จังหวัดพัทลุง" class="img-fluid">
                                </div>
                            </div>

                            <!-- Book Footer Slogan & Action Tag -->
                            <div class="book-footer">
                                <p class="book-vision-text mb-2">
                                    "เมืองแห่งความยั่งยืน ด้านเศรษฐกิจ สังคม และความมั่นคง ทรัพยากรธรรมชาติและสิ่งแวดล้อม<br>(Sustainability Phatthalung)"
                                </p>
                                <div class="book-cta-chip d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill fw-bold">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span>เปิดอ่านแผนพัฒนา</span>
                                </div>
                            </div>

                            <!-- 3D Spine Lighting & Ribbon Accent -->
                            <div class="book-spine-effect"></div>
                            <div class="book-edge-shadow"></div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Column 3: Governor's Leadership Vision (ส่วนที่ 3: นโยบายการบริหารงานของผู้ว่าฯ) -->
            <div class="col-12 col-xl-5 d-flex">
                <div class="governor-vision-card p-3.5 p-lg-4 rounded-3 w-100 d-flex flex-column justify-content-between position-relative overflow-hidden shadow-xs">
                    
                    <!-- Card Header -->
                    <div class="gov-card-header mb-2 d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-success d-flex align-items-center gap-2" style="font-size: 1.15rem; color: #047857 !important;">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>ผู้ว่าราชการจังหวัดพัทลุง</span>
                        </h5>
                    </div>

                    <!-- Card Body: Quote & Portrait Side by Side -->
                    <div class="d-flex align-items-center gap-3 my-auto">
                        <!-- Quote -->
                        <div class="gov-quote-wrap flex-grow-1">
                            <blockquote class="gov-quote-text mb-0">
                                “<?= esc($govQuote) ?>”
                            </blockquote>
                        </div>

                        <!-- Portrait Image -->
                        <div class="gov-portrait-wrap flex-shrink-0 text-center">
                            <div class="gov-portrait-frame shadow-sm rounded-3 overflow-hidden">
                                <img src="<?= $govPhoto ?>" alt="<?= esc($govName) ?>" class="img-fluid">
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer: Name & Office -->
                    <div class="gov-card-footer mt-2 pt-2 border-top d-flex align-items-center justify-content-between" style="border-color: rgba(0,0,0,0.06) !important;">
                        <a href="<?= base_url('governor-hall') ?>" class="text-decoration-none small fw-semibold text-success hover-underline">
                            <i class="fa-solid fa-crown me-1"></i> ทำเนียบผู้ว่าราชการจังหวัด
                        </a>
                        <div class="text-end">
                            <span class="fw-bold text-dark d-block" style="font-size: 0.98rem;"><?= esc($govName) ?></span>
                            <small class="text-muted d-block" style="font-size: 0.8rem;"><?= esc($govPosition) ?></small>
                        </div>
                    </div>

                    <!-- Decorative Watermark Seal -->
                    <div class="gov-watermark-seal">
                        <i class="fa-solid fa-landmark-dome"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

<style>
/* ==========================================================================
   Provincial Policy, Royal Initiatives & Strategic Hub Styles
   ========================================================================== */

/* 0. Provincial Motto & Cultural Identity Banner */
.provincial-motto-banner {
    background: #ffffff;
    border: 1px solid #cce3cc;
    min-height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.motto-background-layer {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    opacity: 0.75;
    pointer-events: none;
    overflow: hidden;
}

.motto-background-layer img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center;
    filter: saturate(1.1);
}

.motto-gradient-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.88) 0%, rgba(255, 255, 255, 0.42) 60%, rgba(240, 253, 244, 0.6) 100%);
}

.motto-diamond-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    background: #fef08a;
    color: #b45309;
    border: 1px solid #facc15;
    border-radius: 50%;
    font-size: 0.68rem;
    box-shadow: 0 2px 6px rgba(250, 204, 21, 0.35);
}

.motto-heading {
    color: #065f46;
    font-size: 1.15rem;
    letter-spacing: 0.3px;
    text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
}

.motto-text {
    color: #0f172a;
    font-size: 1.25rem;
    letter-spacing: 0.2px;
    text-shadow: 0 1px 3px rgba(255, 255, 255, 0.9);
    line-height: 1.55;
}

.motto-bottom-border {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #047857 0%, #10b981 50%, #ffd700 100%);
}

/* Main Container */
.provincial-hub-container {
    background: #eaf3ea;
    background-image: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.4) 0%, transparent 80%), repeating-linear-gradient(45deg, rgba(4, 120, 87, 0.02) 0px, rgba(4, 120, 87, 0.02) 2px, transparent 2px, transparent 12px);
    border: 1px solid #cce3cc;
    transition: all 0.3s ease;
}

/* 1. Top Announcement Bar */
.gov-vision-announcement-bar {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    border-left: 5px solid #047857;
}

.gov-announce-badge {
    background: linear-gradient(135deg, #047857 0%, #065f46 100%);
    color: #ffffff;
    border-radius: 6px;
    margin: 4px;
}

.announce-bell-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    color: #fef08a;
    font-size: 0.85rem;
}

.announce-label {
    font-size: 0.92rem;
    letter-spacing: 0.3px;
}

.ticker-text {
    color: #1e293b;
    font-size: 0.95rem;
}

/* 2. Royal Banner 2x2 Grid */
.royal-banner-grid {
    background: #477b47;
    border: 2px solid #5a945a;
}

.royal-banner-card {
    background: #1b5e20;
    border: 1.5px solid #ffffff;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.royal-banner-img-wrap {
    height: 100%;
    min-height: 110px;
    width: 100%;
    overflow: hidden;
    background: #f1f5f9;
}

.royal-banner-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease;
}

.royal-banner-card:hover .royal-banner-img-wrap img {
    transform: scale(1.06);
}

.royal-banner-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.2) 60%, transparent 100%);
}

.royal-banner-caption {
    color: #ffffff;
    font-size: 0.72rem;
    line-height: 1.25;
    text-shadow: 0 1px 3px rgba(0,0,0,0.8);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* 3. 3D Strategic Plan Book Cover */
.book-3d-wrapper {
    perspective: 800px;
    width: 100%;
    max-width: 240px;
    height: 100%;
    min-height: 220px;
    display: flex;
}

.book-3d-cover {
    width: 100%;
    background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 60%, #1e4620 100%);
    border: 2px solid #ffd700;
    border-radius: 4px 12px 12px 4px;
    box-shadow: -4px 6px 16px rgba(0, 0, 0, 0.25), 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.provincial-book-link:hover .book-3d-cover {
    transform: translateY(-4px) rotateY(-6deg);
    box-shadow: -8px 12px 24px rgba(4, 120, 87, 0.3), 0 0 15px rgba(255, 215, 0, 0.3);
}

.book-spine-effect {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 14px;
    background: linear-gradient(to right, rgba(255,255,255,0.3) 0%, rgba(0,0,0,0.3) 40%, rgba(255,255,255,0.15) 70%, transparent 100%);
    border-right: 1px solid rgba(0,0,0,0.2);
}

.book-main-title {
    font-size: 0.95rem;
    color: #ffffff;
    text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    letter-spacing: 0.2px;
}

.book-period {
    background: #fef08a;
    color: #14532d;
    font-size: 0.72rem;
    font-weight: 700;
}

.book-emblem-circle {
    width: 58px;
    height: 58px;
    border-radius: 50%;
    background: #ffffff;
    padding: 4px;
    border: 2px solid #ffd700;
    display: flex;
    align-items: center;
    justify-content: center;
}

.book-emblem-circle img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

.book-vision-text {
    font-size: 0.68rem;
    color: #e2e8f0;
    line-height: 1.35;
    opacity: 0.95;
}

.book-cta-chip {
    background: rgba(255, 255, 255, 0.95);
    color: #14532d;
    font-size: 0.72rem;
    border: 1px solid #ffd700;
    transition: all 0.2s ease;
}

.provincial-book-link:hover .book-cta-chip {
    background: #ffd700;
    color: #052e16;
}

/* 4. Governor Vision Card */
.governor-vision-card {
    background: #fefefe;
    background-image: linear-gradient(135deg, #ffffff 0%, #f7faf7 100%);
    border: 1.5px solid #d1e7d1;
}

.gov-quote-text {
    font-size: 0.82rem;
    color: #334155;
    line-height: 1.55;
    font-style: italic;
    position: relative;
    z-index: 2;
}

.gov-portrait-frame {
    width: 175px;
    height: 215px;
    background: #f1f5f9;
    border: 3px solid #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.14), 0 3px 8px rgba(0, 0, 0, 0.08);
}

@media (min-width: 1400px) {
    .gov-portrait-frame {
        width: 195px;
        height: 235px;
    }
}

.gov-portrait-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gov-watermark-seal {
    position: absolute;
    right: -10px;
    bottom: -15px;
    font-size: 5.5rem;
    color: rgba(4, 120, 87, 0.04);
    pointer-events: none;
    z-index: 1;
}

/* 5. Quick Navigation Buttons */
.gov-quick-nav-btn {
    background: #1b5e20;
    color: #ffffff !important;
    border: 1px solid #2e7d32;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.gov-quick-nav-btn:hover {
    background: #2e7d32;
    color: #fef08a !important;
    transform: translateX(3px);
    box-shadow: 0 4px 10px rgba(27, 94, 32, 0.25);
}

.gov-quick-nav-btn i {
    font-size: 0.72rem;
    opacity: 0.8;
    transition: transform 0.2s ease;
}

.gov-quick-nav-btn:hover i {
    transform: translateX(2px);
    opacity: 1;
}

/* Dark Mode Support */
[data-theme="dark"] .provincial-hub-container {
    background: #102a18;
    border-color: rgba(16, 185, 129, 0.2);
}
[data-theme="dark"] .gov-vision-announcement-bar {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] .ticker-text {
    color: #f8fafc;
}
[data-theme="dark"] .governor-vision-card {
    background: #163820;
    border-color: rgba(16, 185, 129, 0.3);
}
[data-theme="dark"] .gov-quote-text {
    color: #e2e8f0;
}
[data-theme="dark"] .gov-card-footer span {
    color: #ffffff !important;
}
[data-theme="dark"] .gov-portrait-frame {
    border-color: rgba(255, 255, 255, 0.2);
}
</style>

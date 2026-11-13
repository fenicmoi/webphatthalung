<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* DAMRONGTHAM PORTAL STYLES */
    :root {
        --drt-navy-dark: #0a192f;
        --drt-navy: #0f2744;
        --drt-navy-light: #1e3a8a;
        --drt-gold: #d4af37;
        --drt-gold-light: #fbbf24;
        --drt-gold-dark: #b45309;
        --drt-emerald: #059669;
    }

    .drt-hero-section {
        background: radial-gradient(circle at 50% 0%, #1e3a8a 0%, #0f2744 60%, #0a192f 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
        border-radius: 0 0 2rem 2rem;
        padding: 2rem 0 3.5rem 0;
        box-shadow: 0 10px 30px rgba(10, 25, 47, 0.25);
    }
    .drt-hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(212, 175, 55, 0.1) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
        opacity: 0.6;
    }
    .drt-banner-container {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
        border: 2px solid rgba(212, 175, 55, 0.4);
        background: #0b1a30;
        transition: transform 0.3s ease;
    }
    .drt-banner-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 40px rgba(212, 175, 55, 0.25);
    }
    .drt-official-banner-img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }
    .drt-gold-badge {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.2) 0%, rgba(251, 191, 36, 0.1) 100%);
        border: 1px solid rgba(212, 175, 55, 0.5);
        color: #fef08a;
        padding: 0.35rem 1rem;
        border-radius: 50rem;
        font-size: 0.88rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .drt-stat-card {
        background: rgba(255, 255, 255, 0.07);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        transition: all 0.3s ease;
        text-align: center;
    }
    .drt-stat-card:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(212, 175, 55, 0.5);
        transform: translateY(-3px);
    }
    .drt-stat-num {
        font-size: 1.75rem;
        font-weight: 800;
        background: linear-gradient(135deg, #ffffff 0%, #fef08a 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.2;
    }

    /* ACTION BAR */
    .drt-quick-nav {
        margin-top: -1.75rem;
        position: relative;
        z-index: 20;
    }
    .drt-nav-btn {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #1e293b;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }
    .drt-nav-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(15, 39, 68, 0.15);
        color: #0f2744;
        border-color: rgba(212, 175, 55, 0.6);
    }
    .drt-nav-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    /* TRACKING SECTION */
    .drt-track-box {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }
    .drt-search-input-group {
        position: relative;
    }
    .drt-search-input {
        border-radius: 50rem;
        padding: 0.9rem 1.5rem 0.9rem 3.25rem;
        border: 2px solid #cbd5e1;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .drt-search-input:focus {
        border-color: #0f2744;
        box-shadow: 0 0 0 4px rgba(15, 39, 68, 0.12);
    }
    .drt-search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 1.15rem;
    }

    /* STEPPER TIMELINE */
    .drt-stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 2rem 0 1rem 0;
    }
    .drt-stepper::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 40px;
        right: 40px;
        height: 4px;
        background: #e2e8f0;
        z-index: 1;
    }
    .drt-step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .drt-step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.5rem auto;
        font-size: 1.1rem;
        font-weight: 700;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    .drt-step-item.active .drt-step-circle {
        background: #0f2744;
        color: #fef08a;
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.3);
    }
    .drt-step-item.completed .drt-step-circle {
        background: #059669;
        color: #fff;
    }
    .drt-step-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    .drt-step-item.active .drt-step-label {
        color: #0f2744;
        font-weight: 700;
    }

    /* COMPLAINT FORM */
    .drt-form-card {
        background: #ffffff;
        border-radius: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    .drt-form-header {
        background: linear-gradient(135deg, #0f2744 0%, #1e3a8a 100%);
        color: #ffffff;
        padding: 1.75rem 2rem;
    }
    .drt-cat-radio-card {
        cursor: pointer;
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 1.15rem;
        padding: 1.25rem;
        transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        min-height: 145px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        z-index: 1;
    }
    .drt-cat-radio-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--card-gradient, linear-gradient(135deg, rgba(15, 23, 42, 0.90) 0%, rgba(15, 23, 42, 0.75) 100%));
        z-index: -1;
        transition: all 0.3s ease;
    }
    .drt-cat-radio-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25);
        border-color: rgba(251, 191, 36, 0.9);
    }
    .drt-cat-radio-card:hover::before {
        filter: brightness(1.08);
    }
    .drt-cat-radio input[type="radio"]:checked + .drt-cat-radio-card {
        border-color: #fbbf24;
        box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.45), 0 16px 32px rgba(0, 0, 0, 0.3);
        transform: translateY(-4px);
    }
    .drt-cat-check-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #fbbf24;
        color: #0f2744;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    .drt-cat-radio input[type="radio"]:checked + .drt-cat-radio-card .drt-cat-check-badge {
        display: flex;
    }
    .drt-privacy-alert {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border: 1px solid #bbf7d0;
        border-radius: 1rem;
        padding: 1.25rem;
    }

    /* NEWS CARDS */
    .drt-news-card {
        transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0 !important;
    }
    .drt-news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(15, 39, 68, 0.12) !important;
        border-color: rgba(212, 175, 55, 0.6) !important;
    }
    .drt-news-card:hover .drt-news-img-wrap img {
        transform: scale(1.05);
    }
    .drt-news-img-wrap img {
        transition: transform 0.4s ease;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .backdrop-blur {
        backdrop-filter: blur(8px);
    }
    .hover-primary-solid:hover {
        background: #0f2744 !important;
        color: #ffffff !important;
        border-color: #0f2744 !important;
    }

    /* DISTRICT CARDS */
    .drt-district-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .drt-district-card:hover {
        border-color: rgba(212, 175, 55, 0.8);
        box-shadow: 0 8px 24px rgba(15, 39, 68, 0.08);
        transform: translateY(-3px);
    }
    .drt-district-card.is-main {
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border: 2px solid #0f2744;
    }

    /* ACCORDION */
    .drt-faq-accordion .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem !important;
        margin-bottom: 0.75rem;
        overflow: hidden;
    }
    .drt-faq-accordion .accordion-button {
        font-weight: 600;
        font-size: 1rem;
        color: #0f2744;
        background: #ffffff;
        padding: 1.15rem 1.25rem;
    }
    .drt-faq-accordion .accordion-button:not(.collapsed) {
        background: #eff6ff;
        color: #1e3a8a;
        box-shadow: none;
    }

    /* MOBILE RESPONSIVE TWEAKS */
    @media (max-width: 767.98px) {
        .drt-hero-section {
            padding: 1.25rem 0 2.5rem 0;
            border-radius: 0 0 1.25rem 1.25rem;
        }
        .drt-stat-card {
            padding: 0.75rem;
        }
        .drt-stat-num {
            font-size: 1.4rem;
        }
        .drt-nav-btn {
            padding: 0.85rem 1rem;
        }
        .drt-nav-icon {
            width: 40px;
            height: 40px;
            font-size: 1.15rem;
        }
        .drt-stepper {
            flex-direction: column;
            gap: 1rem;
            margin: 1.5rem 0;
        }
        .drt-stepper::before {
            display: none;
        }
        .drt-step-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }
        .drt-step-circle {
            margin: 0;
            flex-shrink: 0;
        }
        .drt-form-header {
            padding: 1.25rem 1.25rem;
        }
        .drt-track-box {
            padding: 1.25rem;
        }
    }

    /* Red Under Development Ribbons & Strips */
    .drt-dev-top-strip {
        background: linear-gradient(90deg, #991b1b 0%, #dc2626 50%, #991b1b 100%);
        color: #ffffff;
        padding: 0.65rem 1rem;
        border-bottom: 2px solid rgba(254, 202, 202, 0.4);
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.25);
        position: relative;
        z-index: 100;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
    }
    .drt-screen-ribbon-wrapper {
        position: fixed;
        top: 0;
        right: 0;
        width: 170px;
        height: 170px;
        overflow: hidden;
        z-index: 999999;
        pointer-events: none;
    }
    .drt-screen-ribbon {
        position: absolute;
        top: 36px;
        right: -45px;
        width: 210px;
        background: linear-gradient(135deg, #991b1b 0%, #dc2626 50%, #b91c1c 100%);
        color: #ffffff;
        text-align: center;
        line-height: 34px;
        font-weight: 700;
        font-size: 0.78rem;
        letter-spacing: 0.5px;
        transform: rotate(45deg);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.45);
        border-top: 1.5px dashed rgba(255, 255, 255, 0.6);
        border-bottom: 1.5px dashed rgba(255, 255, 255, 0.6);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
    }
    .drt-banner-ribbon {
        position: absolute;
        top: 32px;
        right: -55px;
        width: 230px;
        background: linear-gradient(135deg, #991b1b 0%, #dc2626 50%, #b91c1c 100%);
        color: #ffffff;
        text-align: center;
        line-height: 36px;
        font-weight: 800;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        transform: rotate(45deg);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.55);
        z-index: 5;
        border-top: 2px dashed rgba(255, 255, 255, 0.6);
        border-bottom: 2px dashed rgba(255, 255, 255, 0.6);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
        pointer-events: none;
    }
</style>

<!-- 1. HERO SECTION & OFFICIAL IDENTITY -->
<section class="drt-hero-section">
    <div class="container">
        
        <!-- Top Nav Breadcrumbs -->
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white-50 text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
                    <li class="breadcrumb-item text-white-50 active" aria-current="page">ศูนย์ดำรงธรรมจังหวัดพัทลุง</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?= base_url('damrongtham/presentation') ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 shadow-xs fw-semibold">
                    <i class="fa-solid fa-chalkboard-user me-1 text-warning"></i> เปิดโหมด Presentation
                </a>
                <span class="drt-gold-badge">
                    <i class="fa-solid fa-scale-balanced text-warning"></i> ศาลากลางจังหวัดพัทลุง
                </span>
                <a href="tel:1567" class="btn btn-sm btn-danger rounded-pill px-3 py-1 fw-bold text-decoration-none shadow-sm">
                    <i class="fa-solid fa-phone me-1"></i> โทร 1567 (24 ชม.)
                </a>
            </div>
        </div>

        <!-- Official Damrongtham Banner Container -->
        <div class="drt-banner-container mb-4">
            
            <img src="<?= base_url('assets/images/banners/banner_damrongdhama_official.png') ?>" 
                 alt="ศูนย์ดำรงธรรมจังหวัดพัทลุง Damrongtham Center of Phatthalung" 
                 class="drt-official-banner-img">
        </div>

        <!-- Motto & Intro Text -->
        <div class="row align-items-center justify-content-between g-4">
            <div class="col-12 col-lg-7">
                <h1 class="h3 fw-bold text-white mb-2" style="letter-spacing: -0.5px;">
                    ศูนย์ดำรงธรรมจังหวัดพัทลุง (ศาลากลางจังหวัดพัทลุง)
                </h1>
                <p class="text-light opacity-90 mb-3" style="font-size: 1.05rem; line-height: 1.6;">
                    <i class="fa-solid fa-quote-left text-warning opacity-50 me-2"></i>
                    <strong>"บำบัดทุกข์ บำรุงสุข"</strong> รับเรื่องร้องเรียน ร้องทุกข์ ไกล่เกลี่ยประนอมข้อพิพาท ให้บริการข้อมูลข่าวสาร และให้คำปรึกษาทางกฎหมาย เพื่ออำนวยความเป็นธรรมแก่พี่น้องประชาชนชาวพัทลุงทุกคน
                </p>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="badge bg-success bg-opacity-25 text-emerald border border-success border-opacity-25 px-3 py-2 rounded-pill small">
                        <i class="fa-solid fa-shield-halved me-1 text-success"></i> คุ้มครองความลับผู้ร้องเรียน 100%
                    </span>
                    <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill small">
                        <i class="fa-solid fa-bolt me-1"></i> ระบบรับเรื่อง E-Service ทันสมัย
                    </span>
                </div>
            </div>

            <!-- Cumulative Statistics (ITA) -->
            <div class="col-12 col-lg-5">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="drt-stat-card">
                            <div class="drt-stat-num"><?= $stats['total'] ?></div>
                            <small class="text-white-50">เรื่องร้องทุกข์สะสม</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="drt-stat-card">
                            <div class="drt-stat-num text-success"><?= $stats['rate'] ?>%</div>
                            <small class="text-white-50">อัตราการแก้ไขสำเร็จ</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="drt-stat-card">
                            <div class="drt-stat-num"><?= $stats['resolved'] ?></div>
                            <small class="text-white-50">เรื่องที่ยุติ/แจ้งผลแล้ว</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="drt-stat-card">
                            <div class="drt-stat-num text-warning">11 + 1</div>
                            <small class="text-white-50">ศูนย์เครือข่ายอำเภอ</small>
                        </div>
                    </div>
                </div>
                <div class="mt-2.5 text-center" id="statistics-dashboard">
                    <a href="<?= base_url('damrongtham/presentation') ?>" target="_blank" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm me-2">
                        <i class="fa-solid fa-chalkboard-user me-1"></i> โหมดนำเสนอ Presentation
                    </a>
                    <a href="<?= base_url('admin/damrongtham/stats') ?>" target="_blank" class="btn btn-sm btn-outline-light rounded-pill px-3 shadow-xs" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.25);">
                        <i class="fa-solid fa-file-invoice me-1 text-warning"></i> ดูรายงานสถิติทางการ / พิมพ์ PDF / แชร์ LINE
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- 2. QUICK ACTION NAVIGATION BAR -->
<div class="container drt-quick-nav mb-5">
    <div class="row g-2 g-md-3">
        <div class="col-6 col-md-4 col-lg">
            <a href="#complaint-form" class="drt-nav-btn">
                <div class="drt-nav-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">ยื่นเรื่องร้องเรียน</h6>
                    <small class="text-muted">บริการออนไลน์ 24 ชม.</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="#tracking-section" class="drt-nav-btn">
                <div class="drt-nav-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-magnifying-glass-location"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">ตรวจสอบสถานะ</h6>
                    <small class="text-muted">ค้นหาด้วยรหัสคำร้อง</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="#statistics-dashboard" class="drt-nav-btn">
                <div class="drt-nav-icon bg-warning bg-opacity-10 text-warning-emphasis">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">สถิติและผลงาน</h6>
                    <small class="text-muted">แดชบอร์ดสรุปผลงาน</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="#news-activities-section" class="drt-nav-btn">
                <div class="drt-nav-icon bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">ข่าวกิจกรรม</h6>
                    <small class="text-muted">ภารกิจและการลงพื้นที่</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="tel:1567" class="drt-nav-btn">
                <div class="drt-nav-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">สายด่วน 1567</h6>
                    <small class="text-muted">โทรฟรีตลอด 24 ชม.</small>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg">
            <a href="#district-network" class="drt-nav-btn">
                <div class="drt-nav-icon bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">จุดบริการ 11 อำเภอ</h6>
                    <small class="text-muted">เบอร์โทรและที่ตั้งอำเภอ</small>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="container pb-5">

    <!-- 3. REAL-TIME TRACKING SECTION -->
    <div id="tracking-section" class="mb-5 pt-2">
        <div class="drt-track-box">
            <div class="row align-items-center mb-3">
                <div class="col-12 col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-primary rounded-pill px-3 py-1">Real-time Tracking</span>
                        <h4 class="fw-bold text-dark mb-0">ตรวจสอบสถานะเรื่องร้องเรียน / ร้องทุกข์</h4>
                    </div>
                    <p class="text-muted mb-0 small">
                        กรอกรหัสติดตามเรื่องที่ได้รับจากการยื่นเรื่อง (เช่น <code>DRT-261019-XXXX</code> หรือ <code>PTL-XXXXXX</code>) เพื่อตรวจสอบขั้นตอนการดำเนินงานของเจ้าหน้าที่
                    </p>
                </div>
                <div class="col-12 col-lg-4 text-lg-end mt-2 mt-lg-0">
                    <span class="text-muted small"><i class="fa-solid fa-clock-rotate-left me-1"></i> อัปเดตข้อมูลแบบทันที</span>
                </div>
            </div>

            <!-- Search Form -->
            <form id="drt-track-form" class="row g-2 mb-3">
                <div class="col-12 col-md-9">
                    <div class="drt-search-input-group">
                        <i class="fa-solid fa-ticket drt-search-icon"></i>
                        <input type="text" id="track-code-input" class="form-control drt-search-input" 
                               placeholder="กรอกรหัสติดตามเรื่อง เช่น DRT-261019-A1B2 หรือ PTL-260831-C7D8" 
                               required autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" id="track-submit-btn" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold" style="background: #0f2744; border-color: #0f2744;">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> ตรวจสอบสถานะ
                    </button>
                </div>
            </form>

            <!-- Loading Spinner Container -->
            <div id="track-loading" class="text-center py-4 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">กำลังค้นหา...</span>
                </div>
                <p class="text-muted small mt-2 mb-0">กำลังค้นหาข้อมูลจากฐานข้อมูลศูนย์ดำรงธรรม...</p>
            </div>

            <!-- Dynamic Result Card -->
            <div id="track-result" class="d-none mt-4 p-3.5 bg-white border rounded-4 shadow-xs">
                <!-- Result content injected via JS -->
            </div>
        </div>
    </div>

    <!-- 3.5. INTERACTIVE STATISTICS & PERFORMANCE DASHBOARD (ศูนย์ข้อมูลสถิติและผลการดำเนินงาน) -->
    <?php 
    $ds = $drtStats ?? \App\Models\DamrongthamStatsModel::getStats(); 
    ?>
    <div id="statistics-dashboard" class="mb-5 pt-3">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-warning bg-opacity-20 text-warning-emphasis px-3 py-1 rounded-pill mb-1 fw-bold">
                    <i class="fa-solid fa-chart-pie me-1"></i> รายงานสถิติและผลการดำเนินงานทางการ
                </span>
                <h3 class="fw-bold text-dark mb-0">ศูนย์ข้อมูลสถิติศูนย์ดำรงธรรมจังหวัดพัทลุง</h3>
                <p class="text-muted mb-0 small">สรุปสถิติการรับเรื่องร้องเรียน ร้องทุกข์ และผลการแก้ไขปัญหาแก่ประชาชนอย่างโปร่งใส</p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?= base_url('damrongtham/presentation') ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 shadow-xs fw-semibold">
                    <i class="fa-solid fa-chalkboard-user me-1 text-warning"></i> เปิดโหมด Presentation
                </a>
                <span class="badge bg-white border text-muted shadow-xs px-3 py-2 rounded-pill small">
                    <i class="fa-solid fa-clock-rotate-left me-1 text-primary"></i> ปรับปรุง: <?= esc($ds['updated_at'] ?? date('d/m/Y')) ?>
                </span>
            </div>
        </div>

        <!-- Official Announcement Header Box (จากภาพที่ 1) -->
        <div class="p-3.5 mb-4 rounded-4 shadow-sm border" style="background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%); border-color: #fde047 !important;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-2 bg-warning bg-opacity-35 text-dark" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 1.02rem;">
                            <?= esc($ds['cumulative']['title'] ?? 'เรื่องร้องเรียนสะสมตั้งแต่เปิดศูนย์ตามประกาศ คสช. ฉบับที่ 96/2557 ลงวันที่ 18 กรกฎาคม 2557') ?>
                        </h6>
                        <small class="text-dark text-opacity-75">
                            <i class="fa-regular fa-calendar-check me-1"></i> ช่วงเวลาสะสม: <strong><?= esc($ds['cumulative']['period_text'] ?? '18 กรกฎาคม 2557 – ปัจจุบัน') ?></strong>
                        </small>
                    </div>
                </div>
                <div>
                    <span class="badge bg-white text-dark shadow-xs px-3 py-2 rounded-pill fw-semibold">
                        <i class="fa-solid fa-bullhorn text-danger me-1"></i> ศาลากลางจังหวัดพัทลุง
                    </span>
                </div>
            </div>
        </div>

        <!-- 4 KPI Counter Cards (ภาพที่ 1 & 2) -->
        <div class="row g-3 mb-4">
            <!-- รับเรื่องสะสม -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 bg-white" style="border-top: 4px solid #f43f5e !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold">รับเรื่องสะสมทั้งหมด</span>
                        <span class="badge rounded-pill px-2.5 py-1 small fw-semibold" style="background:#ffe4e6; color:#e11d48;">สะสมรวม</span>
                    </div>
                    <div class="h2 fw-bold text-dark mb-0 font-monospace"><?= number_format($ds['cumulative']['total_received'] ?? 12053) ?></div>
                    <small class="text-muted">เรื่องร้องเรียน / ร้องทุกข์</small>
                </div>
            </div>

            <!-- ดำเนินการแล้วเสร็จ (ยุติ) -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 bg-white" style="border-top: 4px solid #0284c7 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold">ดำเนินการแล้วเสร็จ (ยุติ)</span>
                        <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 small fw-bold">
                            <i class="fa-solid fa-check me-1"></i> <?= esc($ds['cumulative']['total_resolved_percent'] ?? 99.74) ?>%
                        </span>
                    </div>
                    <div class="h2 fw-bold text-primary mb-0 font-monospace" style="color: #0284c7 !important;"><?= number_format($ds['cumulative']['total_resolved'] ?? 12022) ?></div>
                    <small class="text-muted">เรื่องที่แก้ไขและแจ้งผลแล้ว</small>
                </div>
            </div>

            <!-- อยู่ระหว่างดำเนินการ -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3.5 h-100 bg-white" style="border-top: 4px solid #10b981 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-muted small fw-bold">อยู่ระหว่างดำเนินการ</span>
                        <span class="badge bg-warning bg-opacity-20 text-dark rounded-pill px-2.5 py-1 small fw-bold">
                            ร้อยละ <?= esc($ds['cumulative']['total_pending_percent'] ?? 0.26) ?>%
                        </span>
                    </div>
                    <div class="h2 fw-bold text-dark mb-0 font-monospace" style="color: #059669 !important;"><?= number_format($ds['cumulative']['total_pending'] ?? 31) ?></div>
                    <small class="text-muted">เรื่องที่อยู่ระหว่างดำเนินการ</small>
                </div>
            </div>

            <!-- ประจำวัน (Daily Stats) -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 p-3 h-100 text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="small fw-bold opacity-90"><i class="fa-solid fa-calendar-day me-1"></i> สถิติประจำวัน</span>
                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2 py-0.5" style="font-size: 0.72rem;"><?= esc($ds['daily']['date_text'] ?? 'วันนี้') ?></span>
                    </div>
                    <div class="row g-2 text-center mt-1">
                        <div class="col-4 border-end border-white border-opacity-25">
                            <div class="fs-4 fw-bold font-monospace"><?= (int)($ds['daily']['received'] ?? 0) ?></div>
                            <small class="opacity-75" style="font-size:0.72rem;">รับเรื่อง</small>
                        </div>
                        <div class="col-4 border-end border-white border-opacity-25">
                            <div class="fs-4 fw-bold font-monospace"><?= (int)($ds['daily']['pending'] ?? 0) ?></div>
                            <small class="opacity-75" style="font-size:0.72rem;">กำลังทำ</small>
                        </div>
                        <div class="col-4">
                            <div class="fs-4 fw-bold font-monospace text-warning"><?= (int)($ds['daily']['general_inquiries'] ?? 0) ?></div>
                            <small class="opacity-75" style="font-size:0.72rem;">สอบถาม</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resolution Rate Meter (KPI Progress) -->
        <div class="card border-0 shadow-sm rounded-4 p-3.5 bg-white mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?= base_url('damrongtham/presentation') ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 shadow-xs fw-semibold">
                    <i class="fa-solid fa-chalkboard-user me-1 text-warning"></i> เปิดโหมด Presentation
                </a>
                    <i class="fa-solid fa-gauge-high text-success fs-5"></i>
                    <strong class="text-dark small">อัตราการยุติเรื่องร้องเรียนสะสม (Cumulative Resolution Rate)</strong>
                </div>
                <div class="text-end">
                    <span class="fs-5 fw-bold text-success font-monospace"><?= esc($ds['cumulative']['total_resolved_percent'] ?? 99.74) ?>%</span>
                    <small class="text-muted ms-1">(เป้าหมาย &ge; 95%)</small>
                </div>
            </div>
            <div class="progress rounded-pill" style="height: 12px; background: #e2e8f0;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success rounded-pill" role="progressbar" style="width: <?= esc($ds['cumulative']['total_resolved_percent'] ?? 99.74) ?>%;" aria-valuenow="<?= esc($ds['cumulative']['total_resolved_percent'] ?? 99.74) ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        <!-- Tabs Header: สถิติสะสม vs ประจำเดือน -->
        <ul class="nav nav-pills nav-fill bg-white p-1.5 rounded-4 shadow-sm border mb-4" id="drtStatsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill fw-bold py-2.5" id="tab-cum-btn" data-bs-toggle="pill" data-bs-target="#tab-cum-pane" type="button" role="tab">
                    <i class="fa-solid fa-chart-pie me-2"></i> สถิติสะสมภาพรวมทั้งหมด (Cumulative)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold py-2.5" id="tab-month-btn" data-bs-toggle="pill" data-bs-target="#tab-month-pane" type="button" role="tab">
                    <i class="fa-solid fa-calendar-check me-2"></i> สรุปผลประจำเดือน <?= esc($ds['monthly']['month_name'] ?? 'สิงหาคม 2569') ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold py-2.5" id="tab-year-btn" data-bs-toggle="pill" data-bs-target="#tab-year-pane" type="button" role="tab">
                    <i class="fa-solid fa-chart-column me-2"></i> สถิติเปรียบเทียบรายปีงบประมาณ (พ.ศ. 2557 - 2570)
                </button>
            </li>
        </ul>
        <div class="tab-content" id="drtStatsTabsContent">
            <!-- TAB 1: CUMULATIVE CHARTS -->
            <div class="tab-pane fade show active" id="tab-cum-pane" role="tabpanel">
                <div class="row g-4">
                    <!-- Doughnut Chart: Categories Breakdown -->
                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">กลุ่มเรื่องร้องเรียนสะสม</h5>
                                    <small class="text-muted">สัดส่วน 6 กลุ่มเรื่องร้องเรียนหลัก</small>
                                </div>
                                <span class="badge bg-light text-muted border">6 กลุ่มเรื่อง</span>
                            </div>
                            <div style="height: 270px; position: relative;">
                                <canvas id="drtCategoriesChart"></canvas>
                            </div>
                            <!-- Legend Items -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="row g-2">
                                    <?php foreach (($ds['categories_breakdown'] ?? []) as $catItem): ?>
                                    <div class="col-12 col-sm-6">
                                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: #f8fafc;">
                                            <div class="d-flex align-items-center gap-2 overflow-hidden me-1">
                                                <span class="rounded-circle" style="width: 10px; height: 10px; background: <?= esc($catItem['color']) ?>; flex-shrink: 0;"></span>
                                                <span class="small text-truncate" title="<?= esc($catItem['name']) ?>"><?= esc($catItem['name']) ?></span>
                                            </div>
                                            <strong class="small font-monospace text-dark"><?= esc($catItem['percent']) ?>%</strong>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bar Chart: Channels Breakdown -->
                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">ช่องทางการรับเรื่องสะสม</h5>
                                    <small class="text-muted">4 ช่องทางที่ประชาชนยื่นเรื่อง</small>
                                </div>
                                <span class="badge bg-light text-muted border">4 ช่องทาง</span>
                            </div>
                            <div style="height: 270px; position: relative;">
                                <canvas id="drtChannelsChart"></canvas>
                            </div>
                            <!-- Channel Stats Grid -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="row g-2 text-center">
                                    <?php foreach (($ds['channels_breakdown'] ?? []) as $chItem): ?>
                                    <div class="col-6 col-sm-3">
                                        <div class="p-2.5 rounded-3 border bg-light h-100">
                                            <i class="fa-solid <?= esc($chItem['icon'] ?? 'fa-circle') ?> mb-1" style="color: <?= esc($chItem['color']) ?>; font-size: 1.1rem;"></i>
                                            <div class="small fw-semibold text-truncate"><?= esc($chItem['name']) ?></div>
                                            <div class="fw-bold text-dark font-monospace"><?= number_format($chItem['count']) ?></div>
                                            <small class="text-muted" style="font-size: 0.75rem;"><?= esc($chItem['percent']) ?>%</small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: MONTHLY SUMMARY -->
            <div class="tab-pane fade" id="tab-month-pane" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 border-bottom pb-3">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill mb-1 fw-bold">
                                สรุปผลการดำเนินงานประจำเดือน
                            </span>
                            <h4 class="fw-bold text-dark mb-0">ประจำเดือน <?= esc($ds['monthly']['month_name'] ?? 'สิงหาคม 2569') ?></h4>
                            <small class="text-muted"><?= esc($ds['monthly']['period_text'] ?? 'ระหว่างวันที่ 1 - 31 สิงหาคม 2569') ?></small>
                        </div>
                    </div>

                    <!-- 4 Monthly KPI Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-4 bg-light text-center border">
                                <small class="text-muted d-block mb-1">รับเรื่องประจำเดือน</small>
                                <span class="h3 fw-bold text-dark font-monospace"><?= (int)($ds['monthly']['received'] ?? 0) ?></span>
                                <span class="small text-muted ms-1">เรื่อง</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-4 bg-success bg-opacity-10 text-center border border-success border-opacity-25">
                                <small class="text-success d-block mb-1">ยุติเรื่องแล้วเสร็จ</small>
                                <span class="h3 fw-bold text-success font-monospace"><?= (int)($ds['monthly']['resolved'] ?? 0) ?></span>
                                <span class="small text-success ms-1">เรื่อง</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-4 bg-warning bg-opacity-10 text-center border border-warning border-opacity-25">
                                <small class="text-warning-emphasis d-block mb-1">ระหว่างดำเนินการ</small>
                                <span class="h3 fw-bold text-warning-emphasis font-monospace"><?= (int)($ds['monthly']['pending'] ?? 0) ?></span>
                                <span class="small text-warning-emphasis ms-1">เรื่อง</span>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-center border border-primary border-opacity-25">
                                <small class="text-primary d-block mb-1">เรื่องทั่วไป/ให้คำปรึกษาฯ</small>
                                <span class="h3 fw-bold text-primary font-monospace"><?= (int)($ds['monthly']['consultation_general'] ?? 0) ?></span>
                                <span class="small text-primary ms-1">เรื่อง</span>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Detail Sections: Channels & Categories -->
                    <div class="row g-4">
                        <div class="col-12 col-md-5">
                            <div class="p-3.5 rounded-4 border bg-light h-100">
                                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-arrows-split-up-and-left text-primary me-2"></i>ช่องทางรับเรื่องประจำเดือน</h6>
                                <ul class="list-group list-group-flush rounded-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-2.5">
                                        <span><i class="fa-solid fa-person-walking text-muted me-2"></i> ด้วยตนเอง</span>
                                        <span class="badge bg-primary rounded-pill font-monospace fs-6 px-3"><?= (int)($ds['monthly']['channels']['walkin'] ?? 0) ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-2.5">
                                        <span><i class="fa-solid fa-globe text-muted me-2"></i> เว็บไซต์ / E-Service</span>
                                        <span class="badge bg-info rounded-pill font-monospace fs-6 px-3 text-dark"><?= (int)($ds['monthly']['channels']['website'] ?? 0) ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-2.5">
                                        <span><i class="fa-solid fa-phone-volume text-muted me-2"></i> โทรศัพท์ (สายด่วน 1567)</span>
                                        <span class="badge bg-success rounded-pill font-monospace fs-6 px-3"><?= (int)($ds['monthly']['channels']['phone'] ?? 0) ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-2.5">
                                        <span><i class="fa-solid fa-envelope-open-text text-muted me-2"></i> จดหมาย / หนังสือ</span>
                                        <span class="badge bg-warning text-dark rounded-pill font-monospace fs-6 px-3"><?= (int)($ds['monthly']['channels']['letter'] ?? 0) ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-12 col-md-7">
                            <div class="p-3.5 rounded-4 border bg-light h-100">
                                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-layer-group text-primary me-2"></i>กลุ่มเรื่องร้องเรียนประจำเดือน</h6>
                                <div class="row g-2">
                                    <div class="col-6 col-sm-4">
                                        <div class="p-3 bg-white rounded-3 border text-center">
                                            <small class="text-muted d-block text-truncate">ปัญหาความเดือดร้อน</small>
                                            <span class="fs-4 fw-bold text-danger font-monospace"><?= (int)($ds['monthly']['categories']['distress'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="p-3 bg-white rounded-3 border text-center">
                                            <small class="text-muted d-block text-truncate">เรื่องทั่วไป/อื่น ๆ</small>
                                            <span class="fs-4 fw-bold text-primary font-monospace"><?= (int)($ds['monthly']['categories']['general'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="p-3 bg-white rounded-3 border text-center">
                                            <small class="text-muted d-block text-truncate">แจ้งเบาะแส</small>
                                            <span class="fs-4 fw-bold text-success font-monospace"><?= (int)($ds['monthly']['categories']['whistleblowing'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="p-3 bg-white rounded-3 border text-center">
                                            <small class="text-muted d-block text-truncate">กล่าวโทษเจ้าหน้าที่</small>
                                            <span class="fs-4 fw-bold text-dark font-monospace"><?= (int)($ds['monthly']['categories']['officer'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="p-3 bg-white rounded-3 border text-center">
                                            <small class="text-muted d-block text-truncate">ขอความช่วยเหลือ</small>
                                            <span class="fs-4 fw-bold text-info font-monospace"><?= (int)($ds['monthly']['categories']['welfare'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="p-3 bg-white rounded-3 border text-center">
                                            <small class="text-muted d-block text-truncate">ปัญหาที่ดิน</small>
                                            <span class="fs-4 fw-bold text-warning font-monospace"><?= (int)($ds['monthly']['categories']['land'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        
            <!-- TAB 3: FISCAL YEAR COMPARISON (2557 - 2570) -->
            <div class="tab-pane fade" id="tab-year-pane" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 border-bottom pb-3">
                        <div>
                            <span class="badge px-3 py-1 rounded-pill mb-1 fw-bold" style="background: rgba(124, 58, 237, 0.1); color: #7c3aed;">
                                <i class="fa-solid fa-chart-column me-1"></i> รายงานสถิติเปรียบเทียบระยะยาว
                            </span>
                            <h4 class="fw-bold text-dark mb-0">ข้อมูลสถิติเปรียบเทียบการรับเรื่องร้องเรียนช่วงปีงบประมาณ พ.ศ. 2557 - 2570</h4>
                            <small class="text-muted">ข้อมูล ณ วันที่ 4 พฤศจิกายน 2569 | ยอดรับเรื่องสะสมรวมทั้งสิ้น <strong>12,092</strong> เรื่อง</small>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                <a href="<?= base_url('damrongtham/presentation') ?>" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3 shadow-xs fw-semibold">
                    <i class="fa-solid fa-chalkboard-user me-1 text-warning"></i> เปิดโหมด Presentation
                </a>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill">
                                <i class="fa-solid fa-fire me-1"></i> สูงสุดปี 2563 (4,936 เรื่อง)
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1.5 rounded-pill">
                                <i class="fa-solid fa-clock-rotate-left me-1"></i> ครอบคลุม 14 ปีงบประมาณ
                            </span>
                        </div>
                    </div>

                    <!-- Interactive Fiscal Year Bar Chart -->
                    <div style="height: 380px; position: relative;" class="mb-4">
                        <canvas id="drtFiscalChart"></canvas>
                    </div>

                    <!-- Fiscal Year Data Table Breakdown -->
                    <div class="table-responsive rounded-3 border">
                        <table class="table table-sm table-hover align-middle mb-0 text-center" style="font-size: 0.86rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-start ps-3">ปีงบประมาณ</th>
                                    <th>ช่วงเวลา</th>
                                    <th>จำนวนเรื่อง (เรื่อง)</th>
                                    <th>สัดส่วน (%)</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $fyList = $fiscalYears ?? \App\Models\DamrongthamDailyModel::getFiscalYearStats();
                                $fyTotal = array_sum(array_column($fyList, 'count'));
                                foreach ($fyList as $fy): 
                                    $fyPct = $fyTotal > 0 ? round(($fy['count'] / $fyTotal) * 100, 2) : 0;
                                    $isPeak = !empty($fy['highlight']);
                                    $isCurr = !empty($fy['current']);
                                ?>
                                <tr class="<?= $isPeak ? 'table-warning' : ($isCurr ? 'table-info' : '') ?>">
                                    <td class="text-start ps-3 fw-bold">
                                        <?= esc($fy['year']) ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?= ($fy['short'] === '2557') ? '18 ก.ค. 57 - 30 ก.ย. 57' : (($fy['short'] === '2570') ? '1 ต.ค. 69 - ปัจจุบัน' : '1 ต.ค. - 30 ก.ย.') ?>
                                    </td>
                                    <td>
                                        <span class="fw-bold font-monospace <?= $isPeak ? 'text-danger fs-6' : ($isCurr ? 'text-primary fs-6' : 'text-dark') ?>">
                                            <?= number_format($fy['count']) ?>
                                        </span>
                                    </td>
                                    <td class="font-monospace text-muted">
                                        <?= $fyPct ?>%
                                    </td>
                                    <td>
                                        <?php if ($isPeak): ?>
                                            <span class="badge bg-danger rounded-pill px-2">สูงสุด (มาตรการโควิด-19)</span>
                                        <?php elseif ($isCurr): ?>
                                            <span class="badge bg-primary rounded-pill px-2">ปีปัจจุบัน</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-25 text-dark rounded-pill px-2">เสร็จสิ้น</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="2" class="text-start ps-3">รวมเรื่องร้องเรียนสะสมทั้งหมด (18 ก.ค. 2557 - ปัจจุบัน)</td>
                                    <td class="text-primary font-monospace fs-6"><?= number_format($fyTotal) ?></td>
                                    <td class="font-monospace">100.00%</td>
                                    <td><span class="badge bg-success rounded-pill px-2.5">ยอดทางการ</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 4. E-COMPLAINT SUBMISSION FORM -->
    <div id="complaint-form" class="mb-5 pt-3">
        <div class="drt-form-card">
            
            <!-- Header -->
            <div class="drt-form-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">
                            <i class="fa-solid fa-shield-heart me-1"></i> E-Service ศูนย์ดำรงธรรม
                        </span>
                        
                        <h3 class="fw-bold text-white mb-1">แบบฟอร์มยื่นเรื่องร้องเรียน / ร้องทุกข์ออนไลน์</h3>
                        <p class="text-white-50 mb-0 small">
                            กรอกข้อมูลรายละเอียดของท่านให้ครบถ้วน เพื่อให้เจ้าหน้าที่ตรวจสอบข้อเท็จจริงและเร่งประสานการแก้ไขปัญหา
                        </p>
                    </div>
                    <div>
                        <span class="badge bg-white bg-opacity-20 text-white px-3 py-2 rounded-3 small">
                            <i class="fa-solid fa-lock me-1 text-warning"></i> เข้ารหัสความปลอดภัย SSL
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-4 p-md-5">
                <form id="drt-complaint-form" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- STEP 1: CATEGORY SELECTION -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-primary rounded-circle" style="width:26px; height:26px; display:inline-flex; align-items:center; justify-content:center;">1</span>
                            <span>เลือกประเภทเรื่องที่ท่านต้องการร้องเรียน / ขอความช่วยเหลือ <span class="text-danger">*</span></span>
                        </label>
                        <div class="row g-3">
                            <?php $catIdx = 0; foreach ($categories as $catKey => $cat): $catIdx++; ?>
                            <div class="col-12 col-md-6 col-lg-4">
                                <label class="drt-cat-radio w-100 h-100">
                                    <input type="radio" name="category" value="<?= $catKey ?>" class="d-none" <?= $catIdx === 1 ? 'checked' : '' ?>>
                                    <div class="drt-cat-radio-card" style="background-image: url('<?= base_url($cat['image'] ?? '') ?>'); --card-gradient: <?= $cat['gradient'] ?? 'linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.75) 100%)' ?>;">
                                        <span class="drt-cat-check-badge"><i class="fa-solid fa-check"></i></span>
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <div class="rounded-3 p-2 text-white shadow-sm" style="background: <?= $cat['color'] ?>; width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.3);">
                                                    <i class="fa-solid <?= $cat['icon'] ?>"></i>
                                                </div>
                                                <span class="badge bg-dark bg-opacity-60 text-white border border-white border-opacity-25 px-2.5 py-1 small fw-semibold" style="backdrop-filter: blur(8px); font-size: 0.75rem;">
                                                    <?= $cat['badge'] ?>
                                                </span>
                                            </div>
                                            <h6 class="fw-bold text-white mb-1" style="font-size: 0.98rem; text-shadow: 0 1px 3px rgba(0,0,0,0.7);"><?= $cat['name'] ?></h6>
                                        </div>
                                        <p class="text-white text-opacity-85 small mb-0 mt-2" style="font-size: 0.82rem; line-height: 1.45; text-shadow: 0 1px 2px rgba(0,0,0,0.8);"><?= $cat['desc'] ?></p>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <!-- STEP 2: CITIZEN INFORMATION & PRIVACY TOGGLE -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-primary rounded-circle" style="width:26px; height:26px; display:inline-flex; align-items:center; justify-content:center;">2</span>
                            <span>ข้อมูลผู้ร้องเรียน / ผู้ขอความช่วยเหลือ</span>
                        </label>

                        <!-- Confidential Privacy Switch -->
                        <div class="drt-privacy-alert mb-3">
                            <div class="form-check form-switch d-flex align-items-center gap-3 ps-0">
                                <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" id="is_confidential" name="is_confidential" value="1" checked style="width: 2.8rem; height: 1.4rem; cursor: pointer;">
                                <label class="form-check-label fw-bold text-success mb-0" for="is_confidential" style="cursor: pointer;">
                                    <i class="fa-solid fa-user-shield me-1"></i> ประสงค์ขอปกปิดข้อมูลผู้ร้องเรียนเป็นความลับ
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2" style="font-size: 0.84rem;">
                                ระบบจะคุ้มครองข้อมูลส่วนบุคคลตาม พ.ร.บ. ข้อมูลข่าวสารของราชการ พ.ศ. 2540 โดยข้อมูลชื่อ เบอร์โทรศัพท์ และเอกสารของท่าน จะเปิดเผยเฉพาะเจ้าหน้าที่ผู้รับผิดชอบโดยตรงเท่านั้น
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold" for="drt_full_name">ชื่อ - นามสกุล ผู้ร้องเรียน <span class="text-danger">*</span></label>
                                <input type="text" id="drt_full_name" name="full_name" class="form-control rounded-3" placeholder="ระบุชื่อและนามสกุลจริง" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">เบอร์โทรศัพท์ติดต่อสะดวก <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control rounded-3" placeholder="เช่น 081-234-5678" required>
                                <small class="text-muted" style="font-size: 0.78rem;">เจ้าหน้าที่จะใช้ติดต่อสอบถามรายละเอียดเพิ่มเติม</small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">อีเมล (ถ้ามี)</label>
                                <input type="email" name="email" class="form-control rounded-3" placeholder="example@email.com">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-semibold">พื้นที่เกิดเหตุ (อำเภอในจังหวัดพัทลุง) <span class="text-danger">*</span></label>
                                <select name="district" class="form-select rounded-3" required>
                                    <option value="" disabled selected>-- เลือกอำเภอที่เกิดเหตุ --</option>
                                    <?php foreach ($districtNames as $dName): ?>
                                    <option value="<?= $dName ?>"><?= $dName ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">สถานที่เกิดเหตุ / ตำบล / หมู่บ้าน หรือจุดสังเกต</label>
                                <input type="text" name="location_detail" class="form-control rounded-3" placeholder="เช่น หมู่ที่ 3 ตำบลคูหาสวรรค์ ใกล้วัดคูหาสวรรค์ หรือพิกัดโดยประมาณ">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <!-- STEP 3: COMPLAINT DETAILS & ATTACHMENTS -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-primary rounded-circle" style="width:26px; height:26px; display:inline-flex; align-items:center; justify-content:center;">3</span>
                            <span>รายละเอียดเรื่องร้องเรียน / ร้องทุกข์ <span class="text-danger">*</span></span>
                        </label>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-semibold">หัวข้อเรื่องร้องเรียน <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control rounded-3" placeholder="สรุปประเด็นสั้นๆ เช่น ร้องเรียนกลิ่นเหม็นจากโรงงาน, ขอความช่วยเหลือเรื่องที่ดินทำกิน" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">รายละเอียดข้อเท็จจริงและพฤติการณ์ความเดือดร้อน <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control rounded-3" placeholder="กรุณาระบุรายละเอียดข้อเท็จจริง เช่น ใคร ทำอะไร ที่ไหน เมื่อใด และต้องการให้ศูนย์ดำรงธรรมช่วยเหลือหรือแก้ไขอย่างไร..." required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">แนบไฟล์ภาพถ่าย / เอกสารหลักฐานประกอบ (ถ้ามี)</label>
                                <input type="file" name="attachment" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.webp,.pdf">
                                <small class="text-muted" style="font-size: 0.78rem;">
                                    รองรับไฟล์ภาพ JPG, PNG, WEBP หรือเอกสาร PDF (ขนาดไฟล์ไม่เกิน 10MB)
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Disclaimer -->
                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms-agree" required>
                            <label class="form-check-label small text-muted" for="terms-agree">
                                ข้าพเจ้าขอรับรองว่าข้อความข้างต้นเป็นความจริงทุกประการ มิได้เจตนากลั่นแกล้งผู้ใด และยินยอมให้ศูนย์ดำรงธรรมจังหวัดพัทลุงใช้ข้อมูลดังกล่าวเพื่อประโยชน์ในการประสานงานแก้ไขปัญหาตามระเบียบของทางราชการ
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" id="submit-complaint-btn" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold shadow-sm" style="background: #0f2744; border-color: #0f2744;">
                            <i class="fa-solid fa-paper-plane me-2"></i> ส่งเรื่องร้องเรียนต่อศูนย์ดำรงธรรม
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <!-- 4.5. PR NEWS & ACTIVITIES SECTION (ประชาสัมพันธ์และข่าวกิจกรรม) -->
    <div id="news-activities-section" class="mb-5 pt-3">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill mb-1 fw-bold">
                    <i class="fa-solid fa-bullhorn me-1"></i> ข่าวสารและกิจกรรม
                </span>
                <h3 class="fw-bold text-dark mb-0">ประชาสัมพันธ์และกิจกรรมศูนย์ดำรงธรรม</h3>
                <p class="text-muted mb-0 small">ติดตามภารกิจการลงพื้นที่บำบัดทุกข์ บำรุงสุข การไกล่เกลี่ยข้อพิพาท และข่าวสารเพื่อประชาชนชาวพัทลุง</p>
            </div>
            <div>
                <a href="<?= base_url('news') ?>" class="btn btn-outline-primary rounded-pill fw-bold btn-sm px-3 shadow-2xs">
                    ดูข่าวสารทั้งหมด <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (!empty($newsList)): ?>
                <?php foreach ($newsList as $news): ?>
                    <?php
                        $cover = !empty($news['cover_image']) ? (strpos($news['cover_image'], 'http') === 0 ? $news['cover_image'] : base_url($news['cover_image'])) : base_url('assets/images/banners/banner_damrongdhama_official.png');
                        $dateStr = !empty($news['created_at']) ? date('d/m/Y', strtotime($news['created_at'])) : date('d/m/Y');
                        $views = (int)($news['views'] ?? 0);
                        $category = $news['category'] ?? 'ข่าวประชาสัมพันธ์';
                        $detailUrl = !empty($news['is_prd']) && !empty($news['source_url']) ? $news['source_url'] : base_url('news/detail/' . ($news['id'] ?? ''));
                        $target = !empty($news['is_prd']) ? 'target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="drt-news-card card border-0 h-100 rounded-4 overflow-hidden shadow-xs hover-lift bg-white">
                            <div class="drt-news-img-wrap position-relative overflow-hidden" style="height: 190px;">
                                <img src="<?= $cover ?>" alt="<?= htmlspecialchars($news['title'] ?? '') ?>" class="w-100 h-100 object-fit-cover transition-transform" loading="lazy" onerror="this.src='<?= base_url('assets/images/banners/banner_damrongdhama_official.png') ?>'">
                                <div class="position-absolute top-0 start-0 m-2.5">
                                    <span class="badge bg-dark bg-opacity-75 backdrop-blur rounded-pill px-2.5 py-1 text-white small" style="font-size: 0.75rem;">
                                        <?= htmlspecialchars($category) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-3.5 d-flex flex-column">
                                <div class="d-flex align-items-center gap-3 text-muted small mb-2" style="font-size: 0.78rem;">
                                    <span><i class="fa-regular fa-calendar me-1"></i> <?= $dateStr ?></span>
                                    <span><i class="fa-regular fa-eye me-1"></i> <?= number_format($views) ?> ครั้ง</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2 line-clamp-2" style="font-size: 0.95rem; line-height: 1.45;" title="<?= htmlspecialchars($news['title'] ?? '') ?>">
                                    <?= htmlspecialchars($news['title'] ?? '') ?>
                                </h6>
                                <p class="text-muted small line-clamp-2 mb-3 flex-grow-1" style="font-size: 0.83rem; line-height: 1.5;">
                                    <?= htmlspecialchars(strip_tags($news['summary'] ?? $news['content'] ?? '')) ?>
                                </p>
                                <a href="<?= $detailUrl ?>" <?= $target ?> class="btn btn-sm btn-light border rounded-pill fw-bold text-primary w-100 py-1.5 mt-auto hover-primary-solid">
                                    อ่านรายละเอียด <i class="fa-solid fa-chevron-right ms-1 small"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5 bg-white rounded-4 border">
                        <i class="fa-regular fa-newspaper text-muted fa-3x mb-2"></i>
                        <h6 class="fw-bold text-dark">ยังไม่มีข้อมูลข่าวสารในขณะนี้</h6>
                        <p class="text-muted small mb-0">ระบบจะอัปเดตข่าวสารและกิจกรรมของศูนย์ดำรงธรรมอย่างต่อเนื่อง</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- 5. DISTRICT DAMRONGTHAM NETWORK (11 DISTRICTS) -->
    <div id="district-network" class="mb-5 pt-3">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-success text-white px-3 py-1 rounded-pill mb-1">เครือข่ายบริการ 11 อำเภอ</span>
                <h3 class="fw-bold text-dark mb-0">ศูนย์ดำรงธรรมประจำอำเภอ จังหวัดพัทลุง</h3>
                <p class="text-muted mb-0 small">ท่านสามารถเดินทางไปติดต่อหรือโทรศัพท์ประสานงานกับศูนย์ดำรงธรรม ณ ที่ว่าการอำเภอในพื้นที่ได้โดยตรง</p>
            </div>
            <div>
                <a href="tel:1567" class="btn btn-outline-danger rounded-pill fw-bold btn-sm">
                    <i class="fa-solid fa-headset me-1"></i> โทรสายด่วนกลาง 1567
                </a>
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($districts as $d): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="drt-district-card <?= $d['district'] === 'เมืองพัทลุง' && strpos($d['name'], 'ศาลากลาง') !== false ? 'is-main' : '' ?>">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge <?= strpos($d['name'], 'ศาลากลาง') !== false ? 'bg-primary' : 'bg-secondary bg-opacity-25 text-dark' ?> rounded-pill small">
                                อ.<?= $d['district'] ?>
                            </span>
                            <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> เวลาราชการ</small>
                        </div>
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.98rem;"><?= $d['name'] ?></h6>
                        <p class="text-muted small mb-2" style="font-size: 0.83rem; line-height: 1.4;">
                            <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= $d['location'] ?>
                        </p>
                    </div>
                    <div class="pt-2 border-top mt-2 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small d-block" style="font-size: 0.76rem;">เบอร์โทรศัพท์</span>
                            <strong class="text-dark small"><?= $d['phone'] ?></strong>
                        </div>
                        <a href="tel:<?= preg_replace('/[^0-9]/', '', $d['phone']) ?>" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold">
                            <i class="fa-solid fa-phone me-1"></i> โทรออก
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 6. CITIZEN GUIDELINES & FAQ ACCORDION -->
    <div id="faq-section" class="mb-5 pt-3">
        <div class="text-center max-w-700 mx-auto mb-4">
            <span class="badge bg-warning bg-opacity-25 text-dark fw-bold px-3 py-1 rounded-pill mb-2">คำถามที่พบบ่อย</span>
            <h3 class="fw-bold text-dark">ข้อควรรู้และแนวทางการใช้บริการศูนย์ดำรงธรรม</h3>
            <p class="text-muted small">คำแนะนำสำคัญสำหรับประชาชนก่อนการยื่นเรื่องร้องเรียนร้องทุกข์</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="accordion drt-faq-accordion" id="drtFaqAccordion">
                    <?php foreach ($faqs as $fIdx => $faq): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFaq<?= $fIdx ?>">
                            <button class="accordion-button <?= $fIdx !== 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq<?= $fIdx ?>" aria-expanded="<?= $fIdx === 0 ? 'true' : 'false' ?>" aria-controls="collapseFaq<?= $fIdx ?>">
                                <i class="fa-solid fa-circle-question text-warning me-2"></i> <?= $faq['q'] ?>
                            </button>
                        </h2>
                        <div id="collapseFaq<?= $fIdx ?>" class="accordion-collapse collapse <?= $fIdx === 0 ? 'show' : '' ?>" aria-labelledby="headingFaq<?= $fIdx ?>" data-bs-parent="#drtFaqAccordion">
                            <div class="accordion-body text-muted small" style="line-height: 1.7; font-size: 0.92rem;">
                                <?= $faq['a'] ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. 24/7 HOTLINE EMERGENCY CALLOUT BANNER -->
    <div class="card border-0 rounded-4 overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f2744 100%);">
        <div class="card-body p-4 p-md-5 text-white">
            <div class="row align-items-center g-4">
                <div class="col-12 col-md-8">
                    <span class="badge bg-warning text-dark fw-bold rounded-pill px-3 py-1 mb-2">บริการตลอด 24 ชั่วโมง</span>
                    <h3 class="fw-bold text-white mb-2">ต้องการความช่วยเหลือเร่งด่วน หรือปรึกษาเจ้าหน้าที่?</h3>
                    <p class="text-white-50 mb-0">
                        โทรสายด่วนศูนย์ดำรงธรรม 1567 หรือติดต่อกลุ่มงานศูนย์ดำรงธรรมจังหวัดพัทลุง ศาลากลางจังหวัดพัทลุง ได้ทุกวันไม่เว้นวันหยุดราชการ
                    </p>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="tel:1567" class="btn btn-warning btn-lg rounded-pill px-4 py-2.5 fw-bold text-dark shadow">
                        <i class="fa-solid fa-phone me-1"></i> โทรสายด่วน 1567
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- SUCCESS MODAL (TRACKING CODE POPUP) -->
<div class="modal fade" id="drtSuccessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto mb-3 rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-circle-check fa-3x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">ยื่นเรื่องร้องเรียนเรียบร้อยแล้ว</h4>
                <p class="text-muted small mb-3">ศูนย์ดำรงธรรมจังหวัดพัทลุงได้รับเรื่องของท่านแล้ว กรุณาจดจำหรือคัดลอกรหัสติดตามเรื่องนี้ไว้</p>
                
                <div class="bg-light p-3 rounded-3 border mb-3">
                    <small class="text-muted d-block mb-1">รหัสติดตามเรื่องของท่าน (Tracking Code)</small>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span id="modal-tracking-code" class="h4 fw-bold text-primary mb-0 font-monospace">DRT-XXXXXX</span>
                        <button type="button" id="btn-copy-code" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="คัดลอกรหัส">
                            <i class="fa-regular fa-copy me-1"></i> คัดลอก
                        </button>
                    </div>
                </div>

                <div class="alert alert-info text-start small mb-4 py-2">
                    <i class="fa-solid fa-info-circle me-1"></i> เจ้าหน้าที่จะดำเนินการตรวจสอบข้อเท็จจริง และประสานงานหน่วยงานที่เกี่ยวข้องต่อไป ท่านสามารถใช้รหัสนี้ตรวจสอบความคืบหน้าได้ตลอด 24 ชั่วโมง
                </div>

                <button type="button" class="btn btn-primary rounded-pill w-100 py-2 fw-bold" data-bs-dismiss="modal">
                    ตกลง / เข้าใจแล้ว
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for Interactive Dashboard Analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // INITIALIZE INTERACTIVE CHARTS
    const catCanvas = document.getElementById('drtCategoriesChart');
    if (catCanvas && typeof Chart !== 'undefined') {
        const catData = <?= json_encode($ds['categories_breakdown'] ?? []) ?>;
        new Chart(catCanvas, {
            type: 'doughnut',
            data: {
                labels: catData.map(c => c.name),
                datasets: [{
                    data: catData.map(c => c.count),
                    backgroundColor: catData.map(c => c.color),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const val = ctx.parsed;
                                const pct = ((val / total) * 100).toFixed(2);
                                return ' ' + ctx.label + ': ' + val.toLocaleString() + ' เรื่อง (' + pct + '%)';
                            }
                        }
                    }
                },
                cutout: '62%'
            }
        });
    }

    const chCanvas = document.getElementById('drtChannelsChart');
    if (chCanvas && typeof Chart !== 'undefined') {
        const chData = <?= json_encode($ds['channels_breakdown'] ?? []) ?>;
        new Chart(chCanvas, {
            type: 'bar',
            data: {
                labels: chData.map(ch => ch.name),
                datasets: [{
                    label: 'จำนวนเรื่องสะสม',
                    data: chData.map(ch => ch.count),
                    backgroundColor: chData.map(ch => ch.color),
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' จำนวน: ' + ctx.parsed.y.toLocaleString() + ' เรื่อง';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.06)' },
                        ticks: {
                            callback: function(val) { return val.toLocaleString(); }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }
    


    // Fiscal Year Comparison Chart (2557 - 2570)
    const fyCanvas = document.getElementById('drtFiscalChart');
    let fiscalChartInstance = null;
    if (fyCanvas && typeof Chart !== 'undefined') {
        const fyData = <?= json_encode($fiscalYears ?? \App\Models\DamrongthamDailyModel::getFiscalYearStats()) ?>;
        fiscalChartInstance = new Chart(fyCanvas, {
            type: 'bar',
            data: {
                labels: fyData.map(f => f.short),
                datasets: [{
                    label: 'จำนวนเรื่องรับ (เรื่อง)',
                    data: fyData.map(f => f.count),
                    backgroundColor: fyData.map(f => f.highlight ? '#7c3aed' : (f.current ? '#0284c7' : '#6366f1')),
                    borderRadius: 6,
                    barPercentage: 0.65
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: function(items) {
                                const idx = items[0].dataIndex;
                                return fyData[idx].year;
                            },
                            label: function (ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const val = ctx.parsed.y;
                                const pct = ((val / total) * 100).toFixed(2);
                                return ' จำนวน: ' + val.toLocaleString() + ' เรื่อง (' + pct + '%)';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.06)' },
                        ticks: {
                            callback: function(val) { return val.toLocaleString(); }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { weight: 'bold' }
                        }
                    }
                }
            }
        });

        const yearTabBtn = document.getElementById('tab-year-btn');
        if (yearTabBtn) {
            yearTabBtn.addEventListener('shown.bs.tab', function () {
                if (fiscalChartInstance) {
                    fiscalChartInstance.resize();
                }
            });
        }
    }

    // AUTO FOCUS FULL NAME ON CATEGORY SELECTION
    const fullNameInput = document.getElementById('drt_full_name');
    let drtFocusTimer = null;

    function focusOnFullName() {
        if (!fullNameInput) return;
        if (drtFocusTimer) clearTimeout(drtFocusTimer);
        
        // Smoothly scroll to full name field
        fullNameInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        drtFocusTimer = setTimeout(() => {
            fullNameInput.focus();
            // Subtle highlight pulse to guide the user's eye
            fullNameInput.classList.add('border-primary', 'shadow');
            setTimeout(() => {
                fullNameInput.classList.remove('border-primary', 'shadow');
            }, 1200);
        }, 350);
    }

    document.querySelectorAll('.drt-cat-radio').forEach(box => {
        box.addEventListener('click', function () {
            focusOnFullName();
        });
    });

    document.querySelectorAll('input[name="category"]').forEach(radio => {
        radio.addEventListener('change', function () {
            focusOnFullName();
        });
    });

    // TRACKING FORM SUBMIT
    const trackForm = document.getElementById('drt-track-form');
    const trackInput = document.getElementById('track-code-input');
    const trackLoading = document.getElementById('track-loading');
    const trackResult = document.getElementById('track-result');
    const trackSubmitBtn = document.getElementById('track-submit-btn');

    if (trackForm) {
        trackForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const code = trackInput.value.trim();
            if (!code) return;

            trackLoading.classList.remove('d-none');
            trackResult.classList.add('d-none');
            trackSubmitBtn.disabled = true;

            fetch('<?= base_url('api/damrongtham/track') ?>?code=' + encodeURIComponent(code))
                .then(res => res.json())
                .then(data => {
                    trackLoading.classList.add('d-none');
                    trackSubmitBtn.disabled = false;
                    trackResult.classList.remove('d-none');

                    if (data.status === 'success') {
                        renderTrackResult(data);
                    } else {
                        trackResult.innerHTML = `
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-exclamation text-danger fa-2x mb-2"></i>
                                <h6 class="fw-bold text-dark">ไม่พบข้อมูลคำร้อง</h6>
                                <p class="text-muted small mb-0">${data.message || 'กรุณาตรวจสอบรหัสติดตามเรื่องอีกครั้ง หรือติดต่อสายด่วน 1567'}</p>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    trackLoading.classList.add('d-none');
                    trackSubmitBtn.disabled = false;
                    trackResult.classList.remove('d-none');
                    trackResult.innerHTML = `
                        <div class="text-center py-3 text-danger">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง
                        </div>
                    `;
                });
        });
    }

    function renderTrackResult(data) {
        const step = data.current_step || 1;
        const s1Active = step >= 1 ? 'completed' : '';
        const s2Active = step >= 2 ? (step > 2 ? 'completed' : 'active') : '';
        const s3Active = step >= 3 ? (step > 3 ? 'completed' : 'active') : '';
        const s4Active = step >= 4 ? 'completed' : '';

        trackResult.innerHTML = `
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 border-bottom mb-3">
                <div>
                    <span class="badge ${data.status_info.badge || 'bg-secondary'} rounded-pill px-3 py-1 mb-1">
                        ${data.status_info.name || 'รับเรื่องแล้ว'}
                    </span>
                    <h5 class="fw-bold text-dark mb-0">${data.subject}</h5>
                    <small class="text-muted">ผู้ยื่นคำร้อง: ${data.full_name} | พื้นที่: อ.${data.district}</small>
                </div>
                <div class="text-end">
                    <span class="text-muted small d-block">รหัสคำร้อง</span>
                    <strong class="text-primary font-monospace fs-6">${data.tracking_code}</strong>
                </div>
            </div>

            <!-- Stepper -->
            <div class="drt-stepper">
                <div class="drt-step-item ${s1Active}">
                    <div class="drt-step-circle"><i class="fa-solid fa-inbox"></i></div>
                    <div class="drt-step-label">1. รับเรื่องในระบบ</div>
                </div>
                <div class="drt-step-item ${s2Active}">
                    <div class="drt-step-circle"><i class="fa-solid fa-clipboard-check"></i></div>
                    <div class="drt-step-label">2. ตรวจสอบข้อเท็จจริง</div>
                </div>
                <div class="drt-step-item ${s3Active}">
                    <div class="drt-step-circle"><i class="fa-solid fa-arrows-split-up-and-left"></i></div>
                    <div class="drt-step-label">3. ประสานหน่วยงาน</div>
                </div>
                <div class="drt-step-item ${s4Active}">
                    <div class="drt-step-circle"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="drt-step-label">4. ดำเนินการ/แจ้งผล</div>
                </div>
            </div>

            <!-- Officer Note -->
            <div class="bg-light p-3 rounded-3 mt-3 border">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-user-tie text-primary"></i>
                    <strong class="text-dark small">ความคืบหน้าและการประสานงานของเจ้าหน้าที่:</strong>
                </div>
                <p class="text-muted small mb-0 ps-3 border-start border-primary border-2">
                    ${data.officer_note || 'อยู่ระหว่างตรวจสอบข้อเท็จจริงและมอบหมายส่วนงานที่รับผิดชอบ'}
                </p>
                <div class="text-end mt-2">
                    <small class="text-muted" style="font-size: 0.78rem;">วันที่รับเรื่อง: ${data.created_at} | ปรับปรุงล่าสุด: ${data.updated_at}</small>
                </div>
            </div>
        `;
    }

    // COMPLAINT FORM SUBMIT
    const complaintForm = document.getElementById('drt-complaint-form');
    const submitBtn = document.getElementById('submit-complaint-btn');
    const successModal = new bootstrap.Modal(document.getElementById('drtSuccessModal'));

    if (complaintForm) {
        complaintForm.addEventListener('submit', function (e) {
            e.preventDefault();

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> กำลังบันทึกข้อมูลคำร้อง...';

            const formData = new FormData(complaintForm);

            fetch('<?= base_url('api/damrongtham/submit') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i> ส่งเรื่องร้องเรียนต่อศูนย์ดำรงธรรม';

                if (data.status === 'success') {
                    complaintForm.reset();
                    document.getElementById('modal-tracking-code').innerText = data.tracking_code;
                    
                    // Auto copy handler
                    document.getElementById('btn-copy-code').onclick = function () {
                        navigator.clipboard.writeText(data.tracking_code).then(() => {
                            this.innerHTML = '<i class="fa-solid fa-check me-1"></i> คัดลอกแล้ว';
                            setTimeout(() => {
                                this.innerHTML = '<i class="fa-regular fa-copy me-1"></i> คัดลอก';
                            }, 2000);
                        });
                    };

                    successModal.show();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้ กรุณาตรวจสอบข้อมูล'));
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i> ส่งเรื่องร้องเรียนต่อศูนย์ดำรงธรรม';
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง');
            });
        });
    }

});
</script>
<?= $this->endSection() ?>

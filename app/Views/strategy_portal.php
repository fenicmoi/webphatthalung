<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$vision = $vision ?? [];
$missions = $missions ?? [];
$core_values = $core_values ?? [];
$kpis = $kpis ?? [];
$pillars = $pillars ?? [];
$documents = $documents ?? [];
$isOfficer = $isOfficer ?? false;
?>

<style>
/* ==========================================================================
   STRATEGY PORTAL DESIGN SYSTEM (ROYAL NAVY, AMBER GOLD & EMERALD ECO)
   ========================================================================== */
.strategy-hero {
    background: linear-gradient(135deg, #0b1e48 0%, #1e3a8a 50%, #0369a1 100%);
    position: relative;
    overflow: hidden;
    color: #ffffff;
    border-radius: 28px;
    padding: 3.5rem 2.5rem;
    box-shadow: 0 20px 45px rgba(11, 30, 72, 0.25);
    margin-bottom: 2.5rem;
}
.strategy-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 650px;
    height: 650px;
    background: radial-gradient(circle, rgba(217, 119, 6, 0.18) 0%, rgba(2, 132, 199, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.strategy-vision-box {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(16px);
    border: 2px solid rgba(251, 191, 36, 0.35);
    border-radius: 20px;
    padding: 1.8rem 2.2rem;
    position: relative;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}
.strategy-vision-box::after {
    content: '“';
    position: absolute;
    top: -15px;
    left: 20px;
    font-size: 5rem;
    line-height: 1;
    font-family: serif;
    color: rgba(251, 191, 36, 0.4);
}
.pillar-card {
    background: var(--card-bg, #ffffff);
    border-radius: 22px;
    border: 1px solid rgba(0, 0, 0, 0.07);
    padding: 2rem;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}
.pillar-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}
.pillar-badge-num {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    font-weight: 800;
    color: #ffffff;
    flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
.kpi-metric-card {
    background: var(--card-bg, #ffffff);
    border-radius: 20px;
    border: 1px solid rgba(0, 0, 0, 0.06);
    padding: 1.75rem 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(0,0,0,0.03);
}
.kpi-metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
}
.doc-download-card {
    background: var(--card-bg, #ffffff);
    border-radius: 20px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    padding: 1.75rem;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.doc-download-card:hover {
    border-color: #38bdf8;
    box-shadow: 0 12px 30px rgba(2, 132, 199, 0.12);
    transform: translateY(-4px);
}
.nav-pills-strategy .nav-link {
    border-radius: 50px;
    padding: 0.65rem 1.4rem;
    font-weight: 700;
    font-size: 0.95rem;
    color: #475569;
    background: #ffffff;
    border: 1px solid rgba(0,0,0,0.08);
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    transition: all 0.25s ease;
}
.nav-pills-strategy .nav-link.active {
    background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(2, 132, 199, 0.35);
    border-color: transparent;
}
.flagship-banner {
    background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #047857 100%);
    color: #ffffff;
    border-radius: 22px;
    padding: 2.2rem;
    position: relative;
    overflow: hidden;
}
[data-theme="dark"] .pillar-card,
[data-theme="dark"] .kpi-metric-card,
[data-theme="dark"] .doc-download-card {
    background: #1e293b !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: #f8fafc !important;
}
[data-theme="dark"] .nav-pills-strategy .nav-link {
    background: #1e293b;
    color: #cbd5e1;
    border-color: rgba(255,255,255,0.1);
}
</style>

<div class="container py-4">

    <!-- ======================================================== -->
    <!-- 1. REGAL HERO BANNER & PROVINCIAL VISION 2566-2570 -->
    <!-- ======================================================== -->
    <section class="strategy-hero">
        <div class="position-relative z-2">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0" style="font-size: 0.88rem;">
                    <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white text-opacity-75 text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าแรก</a></li>
                    <li class="breadcrumb-item text-white text-opacity-75">เกี่ยวกับจังหวัด</li>
                    <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">ยุทธศาสตร์การพัฒนาจังหวัดพัทลุง</li>
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-warning bg-opacity-20 border border-warning border-opacity-30 text-warning fw-bold small mb-3">
                        <i class="fa-solid fa-compass"></i> แผนพัฒนาจังหวัด 5 ปี (พ.ศ. 2566 – 2570) ฉบับทบทวน
                    </div>
                    
                    <h1 class="display-6 fw-bold mb-3 text-white" style="letter-spacing: -0.02em;">
                        ยุทธศาสตร์การพัฒนาจังหวัดพัทลุง
                    </h1>
                    <p class="text-white text-opacity-80 fs-5 mb-4 fw-light">
                        กรอบทิศทางการขับเคลื่อนเศรษฐกิจ สังคม ท่องเที่ยว และสิ่งแวดล้อม สู่เมืองอัจฉริยะที่ยั่งยืน
                    </p>

                    <!-- Vision Box -->
                    <div class="strategy-vision-box mb-4">
                        <div class="small text-warning text-uppercase fw-bold mb-1 letter-spacing-1">
                            <i class="fa-solid fa-bullseye me-1"></i> วิสัยทัศน์จังหวัดพัทลุง (Provincial Vision)
                        </div>
                        <h4 class="fw-bold text-white mb-2" style="line-height: 1.5;">
                            "<?= esc($vision['statement'] ?? 'เมืองเกษตรคุณค่าสูง ท่องเที่ยวเชิงนิเวศและวัฒนธรรมระดับสากล คุณภาพชีวิตที่ดี สังคมเป็นสุข ทรัพยากรธรรมชาติและสิ่งแวดล้อมยั่งยืน') ?>"
                        </h4>
                        <div class="d-flex align-items-center gap-2 text-white text-opacity-75 small">
                            <i class="fa-solid fa-circle-check text-success"></i>
                            <span><?= esc($vision['motto'] ?? 'เมืองลุงน่าอยู่ เกษตรปลอดภัย ทะเลน้อยมรดกโลก สังคมเป็นสุข') ?></span>
                        </div>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="d-flex flex-wrap gap-2.5">
                        <a href="#pillarsSection" class="btn btn-warning rounded-pill px-4 py-2.5 fw-bold shadow-sm text-dark d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-layer-group"></i> ประเด็นการพัฒนาจังหวัด
                        </a>
                        <a href="#documentsSection" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-pdf"></i> ศูนย์ดาวน์โหลดแผนพัฒนาฯ
                        </a>
                        <a href="#kpiSection" class="btn btn-outline-info rounded-pill px-3 py-2.5 fw-bold d-inline-flex align-items-center gap-2 text-white">
                            <i class="fa-solid fa-chart-pie"></i> ตัวชี้วัดเป้าหมาย
                        </a>
                        <?php if ($isOfficer): ?>
                            <a href="<?= base_url('admin/strategy') ?>" class="btn btn-light rounded-pill px-3.5 py-2.5 fw-bold text-dark d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-sliders text-primary"></i> จัดการข้อมูลยุทธศาสตร์
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4 text-center d-none d-lg-block">
                    <div class="p-4 rounded-circle bg-white bg-opacity-10 border border-white border-opacity-20 shadow-lg d-inline-flex align-items-center justify-content-center" style="width: 240px; height: 240px; backdrop-filter: blur(10px);">
                        <img src="<?= base_url('uploads/logo/logo_1787048018.png') ?>" alt="ตราประจำจังหวัดพัทลุง" class="img-fluid" style="max-height: 180px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5));">
                    </div>
                    <div class="mt-3 text-white text-opacity-90 fw-bold fs-6">
                        จังหวัดพัทลุง • Phatthalung Province
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 2. CORE VALUES & MISSIONS (พันธกิจและค่านิยมหลัก) -->
    <!-- ======================================================== -->
    <section class="mb-5">
        <div class="row g-4 mb-4">
            <?php foreach ($core_values as $cv): ?>
                <div class="col-6 col-md-3">
                    <div class="p-3.5 rounded-4 border bg-white shadow-sm h-100 d-flex align-items-center gap-3 transition-all hover-lift" style="border-radius: 18px;">
                        <div class="p-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: rgba(2, 132, 199, 0.1); color: #0284c7;">
                            <i class="<?= esc($cv['icon']) ?> fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6"><?= esc($cv['title']) ?></div>
                            <small class="text-muted" style="font-size: 0.8rem;"><?= esc($cv['desc']) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Missions Card -->
        <div class="card border-0 rounded-4 shadow-sm p-4 p-lg-4.5 bg-white mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-flag text-primary fs-5"></i>
                <h5 class="fw-bold text-dark m-0">พันธกิจการพัฒนาจังหวัดพัทลุง (Provincial Missions)</h5>
            </div>
            <div class="row g-3">
                <?php foreach ($missions as $mIdx => $missionText): ?>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-light h-100">
                            <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 0.85rem;">
                                <?= $mIdx + 1 ?>
                            </span>
                            <span class="text-secondary fw-medium" style="font-size: 0.95rem; line-height: 1.55;">
                                <?= esc($missionText) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 3. PROVINCIAL DEVELOPMENT THEMES (ประเด็นการพัฒนาจังหวัด) -->
    <!-- ======================================================== -->
    <section id="pillarsSection" class="mb-5 pt-3">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1.5 rounded-pill mb-2">Development Themes</span>
                <h3 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-layer-group text-warning me-2"></i>ประเด็นการพัฒนาจังหวัด (<?= count($pillars) ?> ประเด็น)
                </h3>
            </div>
            <p class="text-muted m-0" style="max-width: 500px; font-size: 0.92rem;">
                จำแนกตามประเด็นยุทธศาสตร์หลัก ครอบคลุมการพัฒนาเศรษฐกิจ สังคม สิ่งแวดล้อม และการบริหารภาครัฐ
            </p>
        </div>

        <div class="row g-4">
            <?php 
                $pillarColClass = 'col-lg-6 col-xl-4';
                if (count($pillars) === 2) $pillarColClass = 'col-lg-6';
                elseif (count($pillars) === 3) $pillarColClass = 'col-lg-4';
                elseif (count($pillars) === 4) $pillarColClass = 'col-md-6 col-xl-3';
            ?>
            <?php foreach ($pillars as $pillar): ?>
                <div class="<?= $pillarColClass ?>">
                    <div class="pillar-card">
                        <!-- Pillar Header -->
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="pillar-badge-num" style="background: <?= esc($pillar['bg_gradient'] ?? 'linear-gradient(135deg, #059669, #10b981)') ?>;">
                                <?= $pillar['number'] ?>
                            </div>
                            <div>
                                <span class="badge px-2.5 py-1 rounded-pill mb-1" style="background: rgba(2, 132, 199, 0.1); color: <?= esc($pillar['color'] ?? '#059669') ?>; font-size: 0.78rem;">
                                    ประเด็นที่ <?= $pillar['number'] ?>
                                </span>
                                <h5 class="fw-bold text-dark m-0" style="font-size: 1.15rem; line-height: 1.35;">
                                    <?= esc($pillar['short_title']) ?>
                                </h5>
                            </div>
                        </div>

                        <!-- Summary -->
                        <p class="text-secondary small mb-3" style="line-height: 1.6; min-height: 48px;">
                            <?= esc($pillar['summary']) ?>
                        </p>

                        <!-- Key Strategies Bullet List -->
                        <div class="mb-3 flex-grow-1">
                            <h6 class="fw-bold text-dark small mb-2 d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-list-check text-primary"></i> กลยุทธ์การดำเนินงาน:
                            </h6>
                            <ul class="list-unstyled mb-0 small text-secondary ps-1">
                                <?php foreach ($pillar['strategies'] as $st): ?>
                                    <li class="mb-2 d-flex align-items-start gap-2">
                                        <i class="fa-solid fa-check text-success mt-1" style="font-size: 0.75rem;"></i>
                                        <span><?= esc($st) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Flagship Project Box -->
                        <div class="p-3 rounded-3 border-start border-4 border-warning bg-light mt-auto">
                            <span class="badge bg-warning text-dark fw-bold px-2 py-0.5 mb-1" style="font-size: 0.7rem;">
                                <i class="fa-solid fa-star me-1"></i> โครงการเรือธง (Flagship)
                            </span>
                            <div class="small fw-bold text-dark" style="line-height: 1.4;">
                                <?= esc($pillar['flagship']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 4. KEY TARGET INDICATORS (ตัวชี้วัดเป้าหมายสำคัญ) -->
    <!-- ======================================================== -->
    <section id="kpiSection" class="mb-5 pt-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div>
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1.5 rounded-pill mb-2">Target & KPIs</span>
                <h3 class="fw-bold text-dark m-0">
                    <i class="fa-solid fa-chart-pie text-success me-2"></i>เป้าหมายและตัวชี้วัดความสำเร็จที่สำคัญ
                </h3>
            </div>
            <span class="text-muted small">เป้าประสงค์รวมระยะ 5 ปี (พ.ศ. 2566 – 2570)</span>
        </div>

        <div class="row g-4">
            <?php 
                $kpiColClass = (count($kpis) === 3) ? 'col-md-4' : 'col-sm-6 col-lg-3';
            ?>
            <?php foreach ($kpis as $kpi): ?>
                <div class="<?= $kpiColClass ?>">
                    <div class="kpi-metric-card h-100 d-flex flex-column">
                        <div class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm mx-auto" style="width: 56px; height: 56px; background: rgba(0,0,0,0.04); color: <?= esc($kpi['color'] ?? '#2563eb') ?>;">
                            <i class="<?= esc($kpi['icon'] ?? 'fa-solid fa-chart-line') ?> fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-secondary mb-2" style="font-size: 0.95rem; min-height: 42px;"><?= esc($kpi['title']) ?></h6>
                        <div class="display-6 fw-bold mb-1" style="color: <?= esc($kpi['color'] ?? '#2563eb') ?>;">
                            <?= esc($kpi['target']) ?>
                        </div>
                        <div class="text-muted small mb-2"><?= esc($kpi['unit']) ?></div>
                        
                        <?php if (!empty($kpi['current'])): ?>
                            <div class="mb-2">
                                <span class="badge bg-light text-secondary border px-2.5 py-1 small">
                                    สถานะปัจจุบัน: <strong><?= esc($kpi['current']) ?></strong>
                                </span>
                            </div>
                        <?php endif; ?>

                        <p class="text-secondary small mt-auto mb-0" style="font-size: 0.82rem; line-height: 1.45;">
                            <?= esc($kpi['desc'] ?? '') ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 5. FLAGSHIP MEGA PROJECTS HIGHLIGHT -->
    <!-- ======================================================== -->
    <section class="mb-5">
        <div class="flagship-banner shadow-lg">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white fw-bold small mb-2">
                        <i class="fa-solid fa-gem text-warning"></i> โครงการขับเคลื่อนการพัฒนาเชิงพื้นที่ระดับสากล
                    </div>
                    <h3 class="fw-bold text-white mb-2">
                        มรดกทางการเกษตรโลก (GIAHS) & พื้นที่ชุ่มน้ำทะเลน้อย แรมซาร์ไซต์
                    </h3>
                    <p class="text-white text-opacity-90 mb-4" style="line-height: 1.7;">
                        จังหวัดพัทลุงมุ่งมั่นขับเคลื่อน <strong>"ระบบการเลี้ยงควายปลักในพื้นที่ชุ่มน้ำทะเลน้อย"</strong> ซึ่งได้รับการขึ้นทะเบียนเป็นระบบมรดกทางการเกษตรโลก (GIAHS) โดย FAO สหประชาชาติ ควบคู่กับการอนุรักษ์พื้นที่ชุ่มน้ำโลกและยกระดับเศรษฐกิจชุมชนอย่างยั่งยืน
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold">🌿 เกษตรอินทรีย์ GI</span>
                        <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold">🐃 ควายน้ำทะเลน้อย GIAHS</span>
                        <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold">🌊 ลุ่มน้ำทะเลสาบสงขลา</span>
                        <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold">🛡️ Smart & Green City</span>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <a href="<?= base_url('page/thale-noi-giahs') ?>" class="btn btn-warning rounded-pill px-4 py-3 fw-bold text-dark shadow hover-scale d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> ศึกษาข้อมูลมรดกโลก
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================== -->
    <!-- 6. DOCUMENT DOWNLOAD & E-BOOK CENTER (ศูนย์ดาวน์โหลดแผนพัฒนาฯ) -->
    <!-- ======================================================== -->
    <section id="documentsSection" class="mb-5 pt-3">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-1.5 rounded-pill mb-2">
                    <i class="fa-solid fa-folder-open me-1"></i> Official Plans & Documents
                </span>
                <h3 class="fw-bold text-dark m-0">
                    ศูนย์เอกสารแผนพัฒนาและแผนปฏิบัติราชการประจำปี
                </h3>
            </div>
            <div class="text-muted small">
                เอกสารฉบับทางการรับรองโดย สำนักงานจังหวัดพัทลุง
            </div>
        </div>

        <?php
            // Separate Featured 5-Year Master Plan from annual plans
            $featuredDoc = null;
            $otherDocs = [];
            foreach ($documents as $d) {
                if (!empty($d['is_featured']) && $featuredDoc === null) {
                    $featuredDoc = $d;
                } else {
                    $otherDocs[] = $d;
                }
            }
            if (!$featuredDoc && !empty($documents)) {
                $featuredDoc = $documents[0];
                $otherDocs = array_slice($documents, 1);
            }
        ?>

        <!-- 🌟 PRIMARY FOCUS: HERO FEATURED MASTER PLAN CARD -->
        <?php if ($featuredDoc): ?>
            <div class="card border-0 rounded-4 shadow-md overflow-hidden mb-4" style="background: linear-gradient(135deg, #0b1e48 0%, #1e3a8a 60%, #0369a1 100%); color: #ffffff;">
                <div class="card-body p-4 p-lg-4.5 position-relative">
                    <div class="row align-items-center g-4">
                        <!-- Left Icon Badge -->
                        <div class="col-auto">
                            <div class="p-3.5 rounded-4 bg-white bg-opacity-15 border border-white border-opacity-20 d-flex flex-column align-items-center justify-content-center text-center shadow-sm" style="width: 88px; height: 100px;">
                                <i class="fa-solid fa-file-pdf text-warning fs-1 mb-1"></i>
                                <span class="badge bg-warning text-dark fw-bold px-1.5 py-0.5" style="font-size: 0.68rem;">แผนแม่บท</span>
                            </div>
                        </div>

                        <!-- Center Info -->
                        <div class="col">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1.5">
                                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 small fw-bold">
                                    <i class="fa-solid fa-star me-1"></i> แผนแม่บท 5 ปี ฉบับทางการ
                                </span>
                                <span class="text-white text-opacity-75 small">
                                    <i class="fa-regular fa-calendar-check me-1"></i>พ.ศ. <?= esc($featuredDoc['year']) ?>
                                </span>
                            </div>
                            <h4 class="fw-bold text-white mb-2" style="line-height: 1.4;">
                                <?= esc($featuredDoc['title']) ?>
                            </h4>
                            <div class="d-flex flex-wrap align-items-center gap-3 text-white text-opacity-80 small">
                                <span><i class="fa-solid fa-hard-drive me-1 text-warning"></i>ขนาดไฟล์: <strong><?= esc($featuredDoc['file_size']) ?></strong></span>
                                <span><i class="fa-solid fa-book-open me-1 text-warning"></i>ความยาว: <strong><?= esc($featuredDoc['pages'] ?? '-') ?> หน้า</strong></span>
                                <span><i class="fa-solid fa-download me-1 text-warning"></i>ดาวน์โหลดแล้ว: <strong><?= number_format($featuredDoc['downloads'] ?? 0) ?> ครั้ง</strong></span>
                            </div>
                        </div>

                        <!-- Right Action Buttons -->
                        <div class="col-12 col-lg-auto d-flex flex-sm-row flex-lg-column gap-2.5">
                            <button type="button" class="btn btn-warning rounded-pill px-4 py-2.5 fw-bold text-dark shadow d-inline-flex align-items-center justify-content-center gap-2" onclick="previewStrategyDoc('<?= esc($featuredDoc['title']) ?>', '<?= base_url($featuredDoc['file_url']) ?>')">
                                <i class="fa-solid fa-book-open-reader"></i> เปิดอ่าน E-Book
                            </button>
                            <a href="<?= base_url($featuredDoc['file_url']) ?>" download class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-bold d-inline-flex align-items-center justify-content-center gap-2" onclick="trackStrategyDownload('<?= $featuredDoc['id'] ?>')">
                                <i class="fa-solid fa-download"></i> ดาวน์โหลด PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 📋 SECONDARY FOCUS: CLEAN LIST OF ANNUAL ACTION PLANS & REPORTS WITH REAL-TIME SEARCH -->
        <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
            <div class="card-header bg-light bg-opacity-50 border-bottom px-4 py-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list-check text-primary"></i> แผนปฏิบัติราชการประจำปี & รายงานติดตามประเมินผล (M&E)
                    </h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 small fw-bold" id="docCountBadge">
                        แสดง <?= count($otherDocs) ?> รายการ
                    </span>
                </div>

                <!-- 🔍 REAL-TIME SEARCH & FILTER TOOLBAR -->
                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted ps-3">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 rounded-end-pill py-2 shadow-none" id="docSearchInput" placeholder="พิมพ์ค้นหาชื่อแผน, คำสำคัญ, หรือปีงบประมาณ..." oninput="filterStrategyDocs()">
                        </div>
                    </div>

                    <!-- Year Dropdown Filter -->
                    <div class="col-sm-6 col-md-3">
                        <select class="form-select rounded-pill py-2 shadow-none" id="docYearSelect" onchange="filterStrategyDocs()">
                            <option value="">ทุกปีงบประมาณ</option>
                            <?php
                                $years = array_unique(array_filter(array_column($documents, 'year')));
                                rsort($years);
                                foreach ($years as $yr):
                            ?>
                                <option value="<?= esc($yr) ?>">ปีงบประมาณ <?= esc($yr) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Category Pills / Reset -->
                    <div class="col-sm-6 col-md-3 text-sm-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 w-100 fw-bold d-inline-flex align-items-center justify-content-center gap-1.5" onclick="clearDocFilter()">
                            <i class="fa-solid fa-rotate-left"></i> ล้างการค้นหา
                        </button>
                    </div>
                </div>

                <!-- Category Filter Pills -->
                <div class="d-flex flex-wrap gap-1.5 pt-3">
                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-1 fw-bold category-filter-btn active" data-category="" onclick="setDocCategoryFilter('', this)">
                        ทั้งหมด
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-bold category-filter-btn" data-category="แผนปฏิบัติราชการประจำปี" onclick="setDocCategoryFilter('แผนปฏิบัติราชการประจำปี', this)">
                        แผนปฏิบัติราชการประจำปี
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-bold category-filter-btn" data-category="รายงานผลการดำเนินงาน (M&E)" onclick="setDocCategoryFilter('รายงานผลการดำเนินงาน (M&E)', this)">
                        รายงาน M&E
                    </button>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-bold category-filter-btn" data-category="แผนพัฒนาจังหวัด 5 ปี" onclick="setDocCategoryFilter('แผนพัฒนาจังหวัด 5 ปี', this)">
                        แผนพัฒนา 5 ปี
                    </button>
                </div>
            </div>

            <div class="list-group list-group-flush" id="strategyDocsList">
                <?php if (empty($otherDocs)): ?>
                    <div class="p-4 text-center text-muted" id="docEmptyMsg">ไม่มีเอกสารเพิ่มเติม</div>
                <?php else: ?>
                    <?php foreach ($otherDocs as $doc): ?>
                        <div class="list-group-item px-4 py-3.5 border-bottom transition-all hover-bg-light doc-list-row" data-title="<?= esc(mb_strtolower($doc['title'])) ?>" data-category="<?= esc($doc['category']) ?>" data-year="<?= esc($doc['year']) ?>">
                            <div class="row align-items-center g-3">
                                <!-- Year Badge -->
                                <div class="col-auto">
                                    <div class="p-2 rounded-3 text-center d-flex flex-column align-items-center justify-content-center" style="width: 60px; background: rgba(2, 132, 199, 0.08); color: #0284c7;">
                                        <span style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase;">ปีงบฯ</span>
                                        <span class="fw-bold fs-6 lh-1"><?= esc($doc['year']) ?></span>
                                    </div>
                                </div>

                                <!-- Title & Details -->
                                <div class="col">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-light text-primary border rounded-pill small px-2 py-0.5" style="font-size: 0.72rem;">
                                            <?= esc($doc['category']) ?>
                                        </span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 doc-item-title" style="font-size: 0.96rem; line-height: 1.4;">
                                        <?= esc($doc['title']) ?>
                                    </h6>
                                    <div class="text-muted small d-flex flex-wrap gap-3" style="font-size: 0.8rem;">
                                        <span><i class="fa-solid fa-hard-drive me-1 text-secondary"></i><?= esc($doc['file_size']) ?></span>
                                        <span><i class="fa-solid fa-file-lines me-1 text-secondary"></i><?= esc($doc['pages'] ?? '-') ?> หน้า</span>
                                        <span><i class="fa-solid fa-download me-1 text-secondary"></i><?= number_format($doc['downloads'] ?? 0) ?> ครั้ง</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12 col-md-auto d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5" onclick="previewStrategyDoc('<?= esc($doc['title']) ?>', '<?= base_url($doc['file_url']) ?>')">
                                        <i class="fa-solid fa-eye"></i> เปิดอ่าน
                                    </button>
                                    <a href="<?= base_url($doc['file_url']) ?>" download class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" onclick="trackStrategyDownload('<?= $doc['id'] ?>')">
                                        <i class="fa-solid fa-download"></i> ดาวน์โหลด PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="p-5 text-center text-muted d-none" id="docNoMatchMsg">
                        <i class="fa-solid fa-folder-magnifying-glass fs-1 text-secondary mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark mb-1">ไม่พบเอกสารที่ตรงกับเงื่อนไขการค้นหา</h6>
                        <p class="small text-muted mb-3">ลองเปลี่ยนคำค้นหา หรือกดปุ่มล้างการค้นหาเพื่อดูเอกสารทั้งหมด</p>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3.5 py-1.5 fw-bold" onclick="clearDocFilter()">
                            <i class="fa-solid fa-rotate-left me-1"></i> ล้างการค้นหา
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<!-- ======================================================== -->
<!-- MODAL: PDF / E-BOOK PREVIEW VIEWER -->
<!-- ======================================================== -->
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="height: 92vh;">
        <div class="modal-content border-0 rounded-4 shadow-2xl h-100 overflow-hidden" style="background: #0f172a;">
            <div class="modal-header border-bottom border-secondary border-opacity-25 px-4 py-3 text-white">
                <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden me-3">
                    <i class="fa-solid fa-file-pdf text-danger fs-4"></i>
                    <h6 class="modal-title fw-bold text-truncate text-white" id="docPreviewTitle">เปิดอ่านเอกสาร</h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="docPreviewDownloadBtn" download class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-download me-1"></i> ดาวน์โหลด PDF
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 h-100 bg-dark position-relative">
                <iframe id="docPreviewIframe" src="" class="w-100 h-100 border-0" style="min-height: 550px;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
let currentDocCategory = '';

function previewStrategyDoc(title, fileUrl) {
    document.getElementById('docPreviewTitle').textContent = title;
    document.getElementById('docPreviewDownloadBtn').href = fileUrl;
    document.getElementById('docPreviewIframe').src = fileUrl;
    
    const modalEl = document.getElementById('docPreviewModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

async function trackStrategyDownload(docId) {
    try {
        await fetch('<?= base_url("strategy/download") ?>/' + docId, { method: 'GET' });
    } catch (e) {
        console.log('Track download error:', e);
    }
}

function setDocCategoryFilter(category, btn) {
    currentDocCategory = category;
    document.querySelectorAll('.category-filter-btn').forEach(el => {
        el.classList.remove('btn-dark', 'active');
        el.classList.add('btn-light', 'border');
    });
    if (btn) {
        btn.classList.remove('btn-light', 'border');
        btn.classList.add('btn-dark', 'active');
    }
    filterStrategyDocs();
}

function clearDocFilter() {
    document.getElementById('docSearchInput').value = '';
    document.getElementById('docYearSelect').value = '';
    currentDocCategory = '';
    
    document.querySelectorAll('.category-filter-btn').forEach((el, idx) => {
        if (idx === 0) {
            el.classList.remove('btn-light', 'border');
            el.classList.add('btn-dark', 'active');
        } else {
            el.classList.remove('btn-dark', 'active');
            el.classList.add('btn-light', 'border');
        }
    });
    
    filterStrategyDocs();
}

function filterStrategyDocs() {
    const searchVal = (document.getElementById('docSearchInput')?.value || '').trim().toLowerCase();
    const yearVal = (document.getElementById('docYearSelect')?.value || '').trim();
    const rows = document.querySelectorAll('.doc-list-row');
    const noMatchEl = document.getElementById('docNoMatchMsg');
    const countBadge = document.getElementById('docCountBadge');

    let visibleCount = 0;

    rows.forEach(row => {
        const title = row.getAttribute('data-title') || '';
        const cat = row.getAttribute('data-category') || '';
        const year = row.getAttribute('data-year') || '';

        const matchSearch = !searchVal || title.includes(searchVal) || cat.toLowerCase().includes(searchVal) || year.includes(searchVal);
        const matchYear = !yearVal || year === yearVal;
        const matchCat = !currentDocCategory || cat === currentDocCategory;

        if (matchSearch && matchYear && matchCat) {
            row.classList.remove('d-none');
            visibleCount++;
        } else {
            row.classList.add('d-none');
        }
    });

    if (countBadge) {
        countBadge.textContent = `แสดง ${visibleCount} รายการ`;
    }

    if (noMatchEl) {
        if (visibleCount === 0 && rows.length > 0) {
            noMatchEl.classList.remove('d-none');
        } else {
            noMatchEl.classList.add('d-none');
        }
    }
}
</script>

<?= $this->endSection() ?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
.budget-hero {
    background: linear-gradient(135deg, #0b1e3b 0%, #103b66 52%, #065f46 100%);
    color: #ffffff;
    border-radius: 24px;
    padding: 2rem 2.25rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 18px 40px rgba(11, 30, 59, 0.18);
}
.budget-hero::after {
    content: '';
    position: absolute;
    top: -40%;
    right: -8%;
    width: 340px;
    height: 340px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(251, 191, 36, 0.18) 0%, transparent 70%);
    pointer-events: none;
}

.budget-year-tab {
    border-radius: 999px;
    padding: 0.55rem 1.25rem;
    font-weight: 700;
    font-size: 0.92rem;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    transition: all 0.2s ease;
    cursor: pointer;
}
.budget-year-tab:hover {
    border-color: #0284c7;
    color: #0284c7;
    transform: translateY(-1px);
}
.budget-year-tab.active {
    background: linear-gradient(135deg, #0f3b6c 0%, #0284c7 100%);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 6px 18px rgba(2, 132, 199, 0.28);
}

.budget-report-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 1.5rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.22s ease;
    position: relative;
    overflow: hidden;
}
.budget-report-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px rgba(15, 23, 42, 0.09);
    border-color: #93c5fd;
}
.budget-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
.btn-budget-ebook {
    background: #f0fdf4;
    color: #047857;
    border: 1px solid #bbf7d0;
    font-weight: 700;
    transition: all 0.18s ease;
}
.btn-budget-ebook:hover {
    background: #059669;
    color: #ffffff;
    border-color: #059669;
}
.btn-budget-pdf {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    border: none;
    font-weight: 700;
    transition: all 0.18s ease;
}
.btn-budget-pdf:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    color: #ffffff;
}

.budget-link-tile {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.1rem 1.25rem;
    text-decoration: none;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease;
    height: 100%;
}
.budget-link-tile:hover {
    border-color: #38bdf8;
    background: #f8fbff;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(2, 132, 199, 0.1);
    color: #0369a1;
}

[data-theme="dark"] .budget-report-card,
[data-theme="dark"] .budget-link-tile,
[data-theme="dark"] .budget-year-tab:not(.active) {
    background: #1e293b;
    border-color: rgba(255, 255, 255, 0.1);
    color: #f8fafc;
}
</style>

<div class="container py-4">

    <!-- 1. HERO HEADER -->
    <section class="budget-hero mb-4">
        <div class="position-relative z-2">
            <nav aria-label="breadcrumb" class="mb-2">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url() ?>" class="text-white text-opacity-75 text-decoration-none">
                            <i class="fa-solid fa-house me-1"></i>หน้าแรก
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-warning fw-bold" aria-current="page">สรุปงบประมาณจังหวัดพัทลุง</li>
                </ol>
            </nav>

            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning bg-opacity-20 border border-warning border-opacity-30 text-warning fw-bold small mb-2">
                        <i class="fa-solid fa-landmark"></i> ข้อมูลเปิดภาครัฐ • สำนักงบประมาณ (เขตที่ 4)
                    </div>
                    <h1 class="h2 fw-bold mb-2 text-white">
                        สรุปงบประมาณจังหวัดพัทลุง (พ.ศ. 2568 – 2570)
                    </h1>
                    <p class="text-white text-opacity-85 mb-0 small" style="line-height: 1.65;">
                        ศูนย์เปิดเผยเอกสารการจัดสรรงบประมาณลงพื้นที่จังหวัดพัทลุง (รหัสพื้นที่ <strong><?= esc($provinceCode) ?></strong>) เชื่อมโยงตรงจาก <strong>สำนักงบประมาณ</strong> เพื่อให้ประชาชนและหน่วยงานตรวจสอบได้อย่างโปร่งใส รองรับทั้งการเปิดอ่านแบบ E-Book พลิกหน้า 3D และดาวน์โหลด PDF
                    </p>
                </div>
                <div class="col-lg-4 d-flex flex-wrap justify-content-lg-end gap-2">
                    <a href="https://govspending.data.go.th" target="_blank" rel="noopener" class="btn btn-warning rounded-pill px-3.5 py-2 fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-1.5">
                        <i class="fa-solid fa-magnifying-glass-chart"></i> ดูการเบิกจ่าย (ภาษีไปไหน?)
                    </a>
                    <a href="<?= base_url('strategy') ?>" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-bold d-inline-flex align-items-center gap-1.5">
                        <i class="fa-solid fa-book-open"></i> แผนพัฒนาจังหวัด
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. YEAR FILTER TABS -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2" id="budgetYearTabs">
            <?php foreach ($budgetYears as $idx => $yrGroup): ?>
                <button type="button"
                        class="budget-year-tab <?= $idx === 0 ? 'active' : '' ?>"
                        data-year="<?= esc($yrGroup['year']) ?>"
                        onclick="filterBudgetYear('<?= esc($yrGroup['year']) ?>', this)">
                    <i class="fa-regular fa-calendar-check me-1"></i>
                    ปีงบประมาณ <?= esc($yrGroup['year']) ?>
                    <?php if (!empty($yrGroup['is_latest'])): ?>
                        <span class="badge bg-warning text-dark ms-1 rounded-pill" style="font-size: 0.68rem;">ล่าสุด</span>
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
            <button type="button" class="budget-year-tab" data-year="all" onclick="filterBudgetYear('all', this)">
                <i class="fa-solid fa-layer-group me-1"></i> แสดงทุกปี (2568 – 2570)
            </button>
        </div>

        <div class="text-muted small">
            <i class="fa-solid fa-shield-check text-success me-1"></i>
            แหล่งข้อมูล: <a href="https://www.bb.go.th" target="_blank" rel="noopener" class="text-decoration-none fw-bold">สำนักงบประมาณ (bb.go.th)</a>
        </div>
    </div>

    <!-- 3. BUDGET REPORTS BY YEAR -->
    <div id="budgetYearsContainer">
        <?php foreach ($budgetYears as $idx => $yrGroup): ?>
            <section class="budget-year-section mb-4 <?= $idx === 0 ? '' : 'd-none' ?>" data-year="<?= esc($yrGroup['year']) ?>">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h4 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <span class="badge <?= esc($yrGroup['badge_cls']) ?> rounded-pill px-3 py-2">
                            พ.ศ. <?= esc($yrGroup['year']) ?>
                        </span>
                        <span>รายงานการจัดสรรงบประมาณจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. <?= esc($yrGroup['year']) ?></span>
                    </h4>
                    <span class="text-muted small">ครบ 3 ชุดรายงานทางการจากสำนักงบประมาณ</span>
                </div>

                <div class="row g-3.5">
                    <?php foreach ($yrGroup['reports'] as $rep): ?>
                        <?php
                            $ebookUrl = base_url('ebook/view') . '?' . http_build_query([
                                'file'  => $rep['url'],
                                'title' => $rep['title'],
                                'back'  => base_url('budget'),
                            ]);
                        ?>
                        <div class="col-12 col-md-4">
                            <div class="budget-report-card">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div class="budget-icon-box" style="background: <?= esc($rep['accent_bg']) ?>; color: <?= esc($rep['accent']) ?>;">
                                        <i class="<?= esc($rep['icon']) ?>"></i>
                                    </div>
                                    <div>
                                        <span class="small fw-bold" style="color: <?= esc($rep['accent']) ?>;">
                                            ปีงบประมาณ พ.ศ. <?= esc($yrGroup['year']) ?>
                                        </span>
                                        <h5 class="fw-bold mb-0 mt-0.5" style="font-size: 1.05rem; line-height: 1.4;">
                                            <?= esc($rep['short_title']) ?>
                                        </h5>
                                    </div>
                                </div>

                                <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">
                                    <?= esc($rep['desc']) ?>
                                </p>

                                <div class="d-flex flex-wrap gap-2 mt-auto pt-3 border-top">
                                    <a href="<?= esc($ebookUrl) ?>" target="_blank" class="btn btn-budget-ebook btn-sm rounded-pill px-3 py-2 flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1.5">
                                        <i class="fa-solid fa-book-open-reader"></i> อ่าน E-Book
                                    </a>
                                    <a href="<?= esc($rep['url']) ?>" target="_blank" rel="noopener" class="btn btn-budget-pdf btn-sm rounded-pill px-3 py-2 flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1.5">
                                        <i class="fa-solid fa-file-pdf"></i> เปิด PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <!-- 4. RELATED TRANSPARENCY & SPENDING LINKS -->
    <section class="mt-5 pt-3 border-top">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-compass text-primary"></i>
            ช่องทางตรวจสอบงบประมาณและการเบิกจ่ายที่เกี่ยวข้อง
        </h5>
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="https://govspending.data.go.th" target="_blank" rel="noopener" class="budget-link-tile">
                    <div class="budget-icon-box" style="background: #fef3c7; color: #b45309;">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div>
                        <div class="fw-bold small">ภาษีไปไหน? (DGA)</div>
                        <div class="text-muted" style="font-size: 0.78rem;">ดูยอดเบิกจ่ายและโครงการรายจังหวัด</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="<?= base_url('financial-reports') ?>" class="budget-link-tile">
                    <div class="budget-icon-box" style="background: #dcfce7; color: #15803d;">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div class="fw-bold small">รายงานทางการเงินจังหวัด</div>
                        <div class="text-muted" style="font-size: 0.78rem;">งบทดลองและรายงานการเงินประจำเดือน</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="<?= base_url('procurement') ?>" class="budget-link-tile">
                    <div class="budget-icon-box" style="background: #dbeafe; color: #1d4ed8;">
                        <i class="fa-solid fa-gavel"></i>
                    </div>
                    <div>
                        <div class="fw-bold small">จัดซื้อจัดจ้าง (e-GP)</div>
                        <div class="text-muted" style="font-size: 0.78rem;">ประกาศจัดซื้อจัดจ้างปีงบประมาณ 2569</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <a href="<?= base_url('strategy') ?>" class="budget-link-tile">
                    <div class="budget-icon-box" style="background: #f3e8ff; color: #7e22ce;">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </div>
                    <div>
                        <div class="fw-bold small">ศูนย์เอกสารแผนพัฒนาฯ</div>
                        <div class="text-muted" style="font-size: 0.78rem;">แผน 5 ปี และแผนปฏิบัติราชการประจำปี</div>
                    </div>
                </a>
            </div>
        </div>
    </section>

</div>

<script>
function filterBudgetYear(year, btnEl) {
    document.querySelectorAll('#budgetYearTabs .budget-year-tab').forEach(b => b.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');

    document.querySelectorAll('.budget-year-section').forEach(sec => {
        if (year === 'all' || sec.getAttribute('data-year') === year) {
            sec.classList.remove('d-none');
        } else {
            sec.classList.add('d-none');
        }
    });
}
</script>

<?= $this->endSection() ?>

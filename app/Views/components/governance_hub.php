<?php
// =========================================================================
// ศูนย์บริการความโปร่งใสและจัดซื้อจัดจ้าง (Procurement & ITA Transparency Hub)
// =========================================================================
$procCategories = function_exists('get_procurement_categories') ? get_procurement_categories() : [];
$procItems = function_exists('get_procurement_items') ? get_procurement_items(null, true) : [];
$homeScorecard = function_exists('get_ita_scorecard') ? get_ita_scorecard() : [];
$isOfficer = session()->get('isLoggedIn');
?>

<style>
.gov-tab-trigger {
    color: var(--text-secondary) !important;
    background: transparent !important;
    border: none !important;
    padding: 10px 22px !important;
    font-weight: 700 !important;
    font-size: 0.95rem;
    transition: all 0.25s ease !important;
    border-radius: 50px !important;
}
.gov-tab-trigger.active {
    color: #ffffff !important;
    background: linear-gradient(135deg, #059669, #047857) !important;
    box-shadow: 0 4px 15px rgba(5, 150, 105, 0.35) !important;
}
[data-theme="dark"] .gov-tab-trigger.active {
    color: #ffffff !important;
}
.procurement-nav-item {
    cursor: pointer;
    transition: all 0.2s ease;
}
.procurement-nav-item:hover, .procurement-nav-item.active {
    background: rgba(56, 189, 248, 0.1);
    color: var(--accent-primary) !important;
    font-weight: 700;
}
</style>

<section id="governance-hub" class="my-5 py-4">
    <div class="glass-card p-4 p-md-5" style="border-radius: 28px; border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
        
        <!-- Hub Header & Selector -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom" style="border-color: var(--glass-border) !important;">
            <div>
                <h3 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: var(--text-primary);">
                    <i class="fa-solid fa-shield-halved text-success"></i>
                    <span>ศูนย์ข้อมูลความโปร่งใสและจัดซื้อจัดจ้าง</span>
                </h3>
                <p style="color: var(--text-secondary); margin: 0; font-size: 0.92rem;">
                    การเปิดเผยข้อมูลเปิดภาครัฐตามเกณฑ์ ITA และประกาศจัดซื้อจัดจ้างตามมาตรฐาน e-GP
                </p>
            </div>

            <!-- Tab Navigation Pills -->
            <ul class="nav nav-pills rounded-pill p-1 bg-light border d-inline-flex align-items-center gap-1" id="govHubTabs" role="tablist" style="background: rgba(255, 255, 255, 0.06) !important; border-color: var(--glass-border) !important;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active gov-tab-trigger" id="tab-procurement-trigger" data-bs-toggle="pill" data-bs-target="#tab-procurement" type="button" role="tab" aria-controls="tab-procurement" aria-selected="true">
                        💼 ประกาศจัดซื้อจัดจ้าง (e-GP)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link gov-tab-trigger" id="tab-ita-trigger" data-bs-toggle="pill" data-bs-target="#tab-ita" type="button" role="tab" aria-controls="tab-ita" aria-selected="false">
                        🟢 ผลการประเมิน ITA & ข้อมูลเปิด
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content Area -->
        <div class="tab-content" id="govHubTabContent">
            
            <!-- Tab 1: ประกาศจัดซื้อจัดจ้าง -->
            <div class="tab-pane fade show active" id="tab-procurement" role="tabpanel" aria-labelledby="tab-procurement-trigger">
                <?php if ($isOfficer): ?>
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" onclick="ProcurementStudio.open()" class="btn btn-xs btn-warning rounded-pill px-4 py-2 fw-bold text-dark">
                            <i class="fa-solid fa-file-circle-plus"></i> จัดการจัดซื้อจัดจ้าง (Studio)
                        </button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Left category list -->
                    <div class="col-lg-3">
                        <div class="list-group rounded-3 border-0 bg-transparent">
                            <button class="list-group-item list-group-item-action procurement-nav-item py-3 px-4 border-0 mb-1 rounded active fw-bold text-dark" 
                                    style="background: transparent;"
                                    onclick="filterProcurement('all', this)">
                                📋 ประกาศทั้งหมด (<?= count($procItems) ?>)
                            </button>
                            <?php foreach ($procCategories as $pCat): 
                                $catItemCount = 0;
                                foreach ($procItems as $pi) {
                                    if (strcasecmp(trim($pi['category'] ?? ''), trim($pCat)) === 0) $catItemCount++;
                                }
                            ?>
                                <button class="list-group-item list-group-item-action procurement-nav-item py-3 px-4 border-0 mb-1 rounded text-dark" 
                                        style="background: transparent;"
                                        onclick="filterProcurement('<?= esc($pCat) ?>', this)">
                                    📁 <?= esc($pCat) ?> (<?= $catItemCount ?>)
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Right announcement items list -->
                    <div class="col-lg-9">
                        <div class="table-responsive border rounded-3 p-2 bg-white bg-opacity-5">
                            <table class="table align-middle table-hover text-dark m-0" id="procurementTable">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th style="width: 15%;">วันที่ประกาศ</th>
                                        <th>หัวข้อประกาศจัดซื้อจัดจ้าง</th>
                                        <th style="width: 15%;" class="text-end">รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($procItems)): ?>
                                        <tr id="procEmptyMsg">
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-file-shield fs-2 mb-2 d-block"></i>
                                                ไม่พบข้อมูลการประกาศจัดซื้อจัดจ้างขณะนี้
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($procItems as $pi): 
                                            $piViews = number_format($pi['views'] ?? 0);
                                        ?>
                                            <tr class="proc-table-row" data-category="<?= esc($pi['category'] ?? '') ?>">
                                                <td class="text-muted small">
                                                    <i class="fa-regular fa-calendar-check text-danger me-1"></i>
                                                    <?= date('d/m/Y', strtotime($pi['date'] ?? 'now')) ?>
                                                </td>
                                                <td>
                                                    <span class="d-block fw-bold text-dark mb-1"><?= esc($pi['title']) ?></span>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary me-2 rounded-pill px-2 py-0.5" style="font-size:0.7rem;"><?= esc($pi['category']) ?></span>
                                                    <small class="text-muted"><i class="fa-regular fa-eye me-1"></i> เข้าชม <?= $piViews ?> ครั้ง</small>
                                                </td>
                                                <td class="text-end">
                                                    <a href="<?= base_url('procurement/detail/' . $pi['id']) ?>" 
                                                       class="btn btn-sm btn-outline-info rounded-pill px-3 py-1 fw-bold">
                                                        เปิดอ่าน ➔
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr id="procEmptyMsg" class="d-none">
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <i class="fa-solid fa-file-shield fs-2 mb-2 d-block"></i>
                                                ไม่พบข้อมูลในหมวดหมู่ที่เลือก
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: ผลคะแนน ITA & ข้อมูลเปิด -->
            <div class="tab-pane fade" id="tab-ita" role="tabpanel" aria-labelledby="tab-ita-trigger">
                <div class="card bg-light border-0 rounded-4 p-4 shadow-sm text-dark">
                    <div class="row align-items-center g-4">
                        <!-- Score badge -->
                        <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center border-end-md">
                            <div class="d-inline-flex flex-column align-items-center justify-content-center p-4 rounded-circle shadow-lg mb-2 text-white" style="width: 130px; height: 130px; background: linear-gradient(135deg, #047857, #10b981); border: 4px solid #d1fae5;">
                                <span class="fs-2 fw-bold mb-0 lh-1"><?= esc($homeScorecard['overall_score'] ?? '96.48') ?></span>
                                <span class="badge bg-warning text-dark mt-1 px-2 py-1 fw-bold" style="font-size: 0.75rem;">Grade <?= esc($homeScorecard['grade'] ?? 'A+') ?></span>
                            </div>
                            <div class="fw-bold text-success small"><?= esc($homeScorecard['grade_title'] ?? 'ผ่านเกณฑ์ยอดเยี่ยม') ?></div>
                            <div class="text-muted small">ประจำปี พ.ศ. <?= esc($homeScorecard['year'] ?? '2568') ?></div>
                        </div>

                        <!-- Score Details -->
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-award text-warning me-2"></i>ผลประเมินคุณธรรมและความโปร่งใส (ITA)</h5>
                            <p style="color: var(--text-secondary); line-height: 1.6; font-size: 0.95rem;" class="mb-3">
                                ศูนย์บัญชาการข้อมูลการประเมินคุณธรรมและความโปร่งใสในการดำเนินงานของหน่วยงานภาครัฐ (OIT) จังหวัดพัทลุง ได้รับการจัดอันดับระดับพึงพอใจยอดเยี่ยมสูงสุดในกลุ่มจังหวัดภาคใต้ตอนล่าง
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?= base_url('ita') ?>#oit1" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold">
                                    <i class="fa-solid fa-folder-open me-1"></i> OIT 1: เปิดเผยข้อมูล
                                </a>
                                <a href="<?= base_url('ita') ?>#oit2" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold">
                                    <i class="fa-solid fa-shield-halved me-1"></i> OIT 2: ป้องกันการทุจริต
                                </a>
                                <a href="<?= base_url('ita') ?>#opendata" class="btn btn-sm btn-outline-warning rounded-pill px-3 py-1 fw-bold text-dark">
                                    <i class="fa-solid fa-database me-1"></i> Open Data ชุดข้อมูลเปิด
                                </a>
                            </div>
                        </div>

                        <!-- Right CTA Button -->
                        <div class="col-md-3 text-center text-md-end">
                            <a href="<?= base_url('ita') ?>" class="btn btn-md rounded-pill px-4 py-3 fw-bold text-dark shadow-lg d-inline-flex flex-column align-items-center justify-content-center w-100 hover-scale" style="background: linear-gradient(135deg, #34d399, #10b981); border: 2px solid rgba(255,255,255,0.4);">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-award fs-5 text-warning"></i>
                                    <span>เข้าสู่ศูนย์ ITA & Data</span>
                                </div>
                                <span class="small text-dark opacity-75 mt-1" style="font-size: 0.75rem;">ดูตัวชี้วัด & รายชื่อไฟล์ ➔</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<script>
// Filter Procurement items inside tab dynamically
function filterProcurement(pCat, btn) {
    document.querySelectorAll('.procurement-nav-item').forEach(b => {
        b.classList.remove('active');
        b.classList.add('text-dark');
    });
    btn.classList.add('active');
    btn.classList.remove('text-dark');

    let count = 0;
    document.querySelectorAll('.proc-table-row').forEach(row => {
        const itemCat = row.getAttribute('data-category');
        if (pCat === 'all' || itemCat === pCat) {
            row.style.display = '';
            count++;
        } else {
            row.style.display = 'none';
        }
    });

    const emptyMsg = document.getElementById('procEmptyMsg');
    if (emptyMsg) {
        if (count === 0) {
            emptyMsg.classList.remove('d-none');
        } else {
            emptyMsg.classList.add('d-none');
        }
    }
}
</script>

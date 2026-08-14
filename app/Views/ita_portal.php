<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    $items = $items ?? [];
    $scorecard = $scorecard ?? [];
    $categories = $categories ?? [];
    $selectedCat = $selectedCat ?? 'all';
    $isOfficer = $isOfficer ?? session()->get('isLoggedIn');
?>

<style>
/* ==========================================================================
   ITA & OPEN DATA HUB STYLES
   ========================================================================== */
.ita-hero-header {
    background: linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #047857 100%);
    padding: 60px 0 50px;
    border-bottom: 5px solid #10b981;
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    color: #fff;
    position: relative;
    overflow: hidden;
}
.ita-hero-header::after {
    content: '';
    position: absolute;
    bottom: -50px; right: -50px;
    width: 350px; height: 350px;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.scorecard-box {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(16, 185, 129, 0.2);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    position: relative;
    margin-top: -35px;
    z-index: 10;
}
.score-grade-pill {
    width: 130px; height: 130px;
    border-radius: 50%;
    background: linear-gradient(135deg, #047857, #10b981);
    color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.35);
    border: 4px solid #d1fae5;
}

.ita-category-tab {
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #475569;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.ita-category-tab:hover {
    background: #e2e8f0;
    color: #047857;
    transform: translateY(-2px);
}
.ita-category-tab.active {
    background: linear-gradient(135deg, #047857, #10b981);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.3);
}

.oit-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 22px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    transition: all 0.3s;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.oit-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(16, 185, 129, 0.12);
    border-color: #34d399;
}

.code-badge {
    padding: 6px 14px;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}

/* Dark Mode */
[data-theme="dark"] .scorecard-box,
[data-theme="dark"] .oit-card {
    background: #1e293b;
    border-color: rgba(255,255,255,0.1);
    color: #f8fafc;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}
[data-theme="dark"] .ita-category-tab {
    background: #0f172a;
    color: #cbd5e1;
    border-color: #334155;
}
</style>

<!-- HERO HEADER -->
<header class="ita-hero-header mb-5">
    <div class="container position-relative z-1">
        <div class="row align-items-center justify-content-between g-4">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-20 mb-3 text-warning fw-semibold">
                    <i class="fa-solid fa-check-to-slot"></i>
                    <span>NACC ITA & Open Government Data Standard</span>
                </div>
                <h1 class="display-5 fw-bold mb-2">ศูนย์การประเมินความโปร่งใส ITA & ชุดข้อมูลสาธารณะ (Open Data)</h1>
                <p class="lead mb-0 text-light opacity-90">ยกระดับคุณธรรมและการตรวจสอบภาครัฐ เปิดเผยตัวชี้วัด OIT และบริการชุดข้อมูลสาธารณะแบบเปี่ยมประสิทธิภาพ</p>
            </div>
            <div class="col-lg-4 text-lg-end d-flex flex-wrap justify-content-lg-end gap-2">
                <a href="<?= base_url() ?>" class="btn btn-outline-light rounded-pill px-4 py-2">
                    <i class="fa-solid fa-arrow-left me-1"></i> หน้าหลัก
                </a>
                <?php if ($isOfficer): ?>
                <button type="button" onclick="ItaStudio.open()" class="btn btn-warning fw-bold text-dark rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2 hover-scale">
                    <i class="fa-solid fa-folder-plus text-primary"></i>
                    <span>+ เพิ่มตัวชี้วัด / Open Data</span>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<div class="container mb-5">

    <!-- ITA SCORECARD BOX -->
    <?php if ($scorecard): ?>
    <div class="scorecard-box p-4 p-lg-5 mb-5">
        <div class="row align-items-center g-4">
            <!-- Left Score Badge -->
            <div class="col-md-3 text-center d-flex flex-column align-items-center justify-content-center border-end-md">
                <div class="score-grade-pill mb-3">
                    <span class="fs-1 fw-bold mb-0 lh-1"><?= esc($scorecard['overall_score'] ?? '96.48') ?></span>
                    <span class="badge bg-warning text-dark mt-1 fs-6 px-3">Grade <?= esc($scorecard['grade'] ?? 'A+') ?></span>
                </div>
                <h6 class="fw-bold text-success mb-1"><?= esc($scorecard['grade_title'] ?? 'ผ่านเกณฑ์ระดับยอดเยี่ยม') ?></h6>
                <span class="small text-muted">ประจำปีงบประมาณ พ.ศ. <?= esc($scorecard['year'] ?? '2568') ?></span>
                
                <?php if ($isOfficer): ?>
                <button type="button" onclick="ItaStudio.openScorecard()" class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1 mt-3 small">
                    <i class="fa-solid fa-sliders me-1"></i> ปรับแก้ไขคะแนน (Scorecard)
                </button>
                <?php endif; ?>
            </div>

            <!-- Right Metrics -->
            <div class="col-md-9 px-lg-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-award text-warning me-2"></i>ผลประเมินคุณธรรมและความโปร่งใส (ITA Scorecard)
                    </h5>
                    <span class="badge bg-info bg-opacity-10 text-info fw-bold px-3 py-1 rounded-pill">
                        ประเมินโดย: <?= esc($scorecard['evaluator'] ?? 'สำนักงาน ป.ป.ช.') ?>
                    </span>
                </div>
                <p class="text-secondary small fst-italic mb-4">
                    "<?= esc($scorecard['quote'] ?? 'จังหวัดพัทลุงยึดมั่นในการบริหารงานด้วยความโปร่งใส สุจริต เป็นธรรม พร้อมเปิดเผยข้อมูลสาธารณะเพื่อการตรวจสอบอย่างแท้จริง') ?>"
                </p>

                <div class="row g-3">
                    <?php 
                    $metrics = $scorecard['metrics'] ?? [];
                    foreach ($metrics as $m): 
                        $pct = floatval($m['score'] ?? 95);
                        $color = $m['color'] ?? 'success';
                    ?>
                    <div class="col-sm-6">
                        <div class="d-flex justify-content-between align-items-center small fw-bold mb-1">
                            <span class="text-dark"><?= esc($m['title']) ?></span>
                            <span class="text-<?= $color ?>"><?= number_format($pct, 2) ?>%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 20px;">
                            <div class="progress-bar bg-<?= $color ?> progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $pct ?>%;" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- FILTER & SEARCH TOOLBAR -->
    <div class="row g-3 align-items-center justify-content-between mb-4">
        <div class="col-lg-8">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= base_url('ita') ?>" class="ita-category-tab <?= $selectedCat === 'all' ? 'active' : '' ?>">
                    <i class="fa-solid fa-list-check"></i> ตัวชี้วัดและข้อมูลทั้งหมด
                </a>
                <?php foreach ($categories as $catKey => $cat): ?>
                <a href="<?= base_url('ita/category/' . urlencode($catKey)) ?>" class="ita-category-tab <?= strcasecmp($selectedCat, $catKey) === 0 ? 'active' : '' ?>">
                    <i class="<?= esc($cat['icon']) ?>"></i> <?= esc($cat['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                <span class="input-group-text bg-white border-0 text-muted px-3"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="oitSearchInput" class="form-control border-0 py-2" placeholder="ค้นหา รหัส O1, O18, ชื่อชุดข้อมูล...">
            </div>
        </div>
    </div>

    <!-- ITEMS GRID -->
    <?php if (empty($items)): ?>
        <div class="text-center py-5 bg-light rounded-4 my-5 border">
            <i class="fa-solid fa-folder-open fs-1 text-muted mb-3 d-block"></i>
            <h5 class="fw-bold text-dark">ไม่พบข้อมูลตัวชี้วัดหรือชุดข้อมูลในหมวดนี้</h5>
            <p class="text-muted">กรุณาเลือกหมวดหมู่อื่น หรือกลับไปยังหน้าทั้งหมด</p>
            <a href="<?= base_url('ita') ?>" class="btn btn-outline-success rounded-pill px-4 py-2">ดูรายการทั้งหมด</a>
        </div>
    <?php else: ?>
        <div class="row g-4" id="oitItemsContainer">
            <?php foreach ($items as $item): 
                $fileType = strtolower($item['file_type'] ?? 'pdf');
                $typeBadge = 'bg-danger text-white';
                $typeIcon = 'fa-file-pdf';
                if ($fileType === 'csv') { $typeBadge = 'bg-success text-white'; $typeIcon = 'fa-file-csv'; }
                elseif ($fileType === 'json') { $typeBadge = 'bg-warning text-dark'; $typeIcon = 'fa-file-code'; }
                elseif ($fileType === 'xls' || $fileType === 'xlsx') { $typeBadge = 'bg-success text-white'; $typeIcon = 'fa-file-excel'; }
                elseif ($fileType === 'link') { $typeBadge = 'bg-primary text-white'; $typeIcon = 'fa-link'; }
                
                $fileUrl = $item['file_url'] ?? '#';
                $isAbsolute = preg_match('/^(http|https):\/\//i', $fileUrl);
                $downloadUrl = $isAbsolute ? $fileUrl : base_url($fileUrl);
                $code = $item['code'] ?? 'O1';
                $codeBg = str_starts_with($code, 'DAT') ? 'bg-purple text-white' : (str_starts_with($code, 'O') && (int)substr($code, 1) > 30 ? 'bg-success text-white' : 'bg-info text-white');
            ?>
            <div class="col-md-6 col-lg-4 oit-item-box" data-title="<?= esc(strtolower($item['title'] . ' ' . $item['code'] . ' ' . $item['desc'] . ' ' . $item['category'])) ?>">
                <div class="oit-card">
                    <div>
                        <!-- Top header -->
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="code-badge <?= str_starts_with($code, 'DAT') ? 'bg-dark text-warning' : 'bg-success bg-opacity-10 text-success' ?>">
                                <i class="fa-solid <?= str_starts_with($code, 'DAT') ? 'fa-database' : 'fa-check-circle' ?> me-1"></i> <?= esc($code) ?>
                            </span>
                            <span class="badge <?= $typeBadge ?> rounded-pill px-3 py-1">
                                <i class="fa-solid <?= $typeIcon ?> me-1"></i> <?= strtoupper(esc($fileType)) ?>
                            </span>
                        </div>

                        <!-- Title & Category -->
                        <div class="small text-muted fw-semibold mb-1">
                            <?= esc($item['category'] ?? 'OIT 1: การเปิดเผยข้อมูล') ?> 
                            <?= !empty($item['sub_category']) ? ' • ' . esc($item['sub_category']) : '' ?>
                        </div>
                        <h5 class="fw-bold text-dark mb-2 line-clamp-2" style="min-height: 48px;">
                            <?= esc($item['title']) ?>
                        </h5>
                        <p class="text-secondary small mb-4 line-clamp-2 opacity-85">
                            <?= esc($item['desc'] ?? 'เอกสารและรายละเอียดประกอบการประเมินคุณธรรมและความโปร่งใสภาครัฐ') ?>
                        </p>
                    </div>

                    <div>
                        <!-- Footer metadata and action -->
                        <div class="pt-3 border-top d-flex align-items-center justify-content-between text-muted small mb-3">
                            <div><i class="fa-solid fa-database me-1 text-secondary"></i> ขนาด: <?= esc($item['file_size'] ?? '-') ?></div>
                            <div><i class="fa-solid fa-cloud-arrow-down me-1 text-primary"></i> <?= number_format((int)($item['downloads'] ?? 0)) ?> ครั้ง</div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="<?= esc($downloadUrl) ?>" target="_blank" onclick="trackItaDownload('<?= $item['id'] ?>')" class="btn btn-success fw-bold rounded-pill w-100 d-flex align-items-center justify-content-center gap-2 shadow-sm py-2">
                                <i class="fa-solid <?= $fileType === 'link' ? 'fa-external-link' : 'fa-file-arrow-down' ?>"></i>
                                <span><?= $fileType === 'link' ? 'เปิดอ่านบนเว็บ' : 'ดาวน์โหลดไฟล์' ?></span>
                            </a>
                            
                            <?php if ($isOfficer): ?>
                            <button type="button" onclick="ItaStudio.open('<?= $item['id'] ?>')" class="btn btn-outline-warning text-dark fw-bold rounded-circle flex-shrink-0" style="width: 42px; height: 42px;" title="แก้ไขรายการ">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" onclick="ItaStudio.deleteItem('<?= $item['id'] ?>', '<?= esc(addslashes($item['title'])) ?>')" class="btn btn-outline-danger fw-bold rounded-circle flex-shrink-0" style="width: 42px; height: 42px;" title="ลบ">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
// Search Input Filtering
document.getElementById('oitSearchInput')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    const boxes = document.querySelectorAll('.oit-item-box');
    let visibleCount = 0;
    boxes.forEach(box => {
        const title = box.getAttribute('data-title') || '';
        if (title.includes(term)) {
            box.style.display = 'block';
            visibleCount++;
        } else {
            box.style.display = 'none';
        }
    });
});

// Download Counter Tracker
function trackItaDownload(id) {
    fetch('<?= base_url('ita/count-download/') ?>' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
}
</script>

<?= $this->endSection() ?>

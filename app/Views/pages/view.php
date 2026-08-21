<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$page = $page ?? []; 
$children = $children ?? []; 

$headerImg = !empty($page['header_image']) ? $page['header_image'] : '';
if (!empty($headerImg) && strpos($headerImg, 'http') !== 0 && strpos($headerImg, 'data:') !== 0) {
    $headerImg = base_url($headerImg);
}
?>

<!-- PAGE HEADER (รองรับภาพพื้นหลังกำหนดเอง - ขนาดใหญ่สวยงามเต็มตา) -->
<div class="text-white py-5 position-relative overflow-hidden" 
     style="min-height: 220px; display: flex; align-items: center; <?= !empty($headerImg) ? "background: url('" . esc($headerImg) . "') center center / cover no-repeat;" : 'background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0369a1 100%);' ?> box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
    
    <!-- Dark Vignette Overlay for High Contrast Text Readability -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: <?= !empty($headerImg) ? 'linear-gradient(90deg, rgba(15,23,42,0.88) 0%, rgba(15,23,42,0.65) 50%, rgba(15,23,42,0.4) 100%)' : 'rgba(0,0,0,0.15)' ?>; z-index: 1;"></div>
    
    <div class="container position-relative py-3" style="z-index: 2;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.92rem;">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white text-decoration-none opacity-75 hover-opacity-100"><i class="fa-solid fa-house"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($page['title'] ?? '') ?></li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-0 text-white" style="font-size: 2.2rem; text-shadow: 0 2px 8px rgba(0,0,0,0.6);"><?= esc($page['title'] ?? '') ?></h1>
    </div>
</div>

<!-- PAGE CONTENT (ระยะห่างกระชับ สบายตา) -->
<div class="container my-3 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: var(--card-bg, #ffffff); border: 1px solid rgba(0,0,0,0.06) !important;">
                <div class="card-body p-3.5 p-md-4 page-content-container">
                    
                    <!-- 1. แท็บเมนูหน้าย่อย (ถ้ามี) -->
                    <?php if (!empty($children)): ?>
                        <div class="mb-3">
                            <ul class="nav nav-pills custom-pills gap-2 flex-wrap" id="pageTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill px-3.5 py-2 fw-bold" id="main-tab" data-bs-toggle="tab" data-bs-target="#tab-main" type="button" role="tab" aria-controls="tab-main" aria-selected="true" style="font-size: 0.94rem;">
                                        <i class="fa-solid fa-file-lines me-1.5 opacity-75"></i><?= esc($page['title'] ?? '') ?>
                                    </button>
                                </li>
                                <?php foreach($children as $idx => $child): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill px-3.5 py-2 fw-bold" id="child-tab-<?= $child['id'] ?>" data-bs-toggle="tab" data-bs-target="#tab-child-<?= $child['id'] ?>" type="button" role="tab" aria-controls="tab-child-<?= $child['id'] ?>" aria-selected="false" style="font-size: 0.94rem;">
                                        <?= esc($child['title'] ?? '') ?>
                                    </button>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- 2. แถบสถานะและเครื่องมือ (จัดวางสมดุล ซ้าย: วันที่/ยอดวิว, ขวา: แชร์/พิมพ์/ซูม) -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(0,0,0,0.08) !important;">
                        <div class="d-flex align-items-center gap-3 text-muted small" style="font-size: 0.85rem;">
                            <span>
                                <i class="fa-regular fa-clock me-1 text-primary"></i> อัปเดต: <?= function_exists('thai_date') ? thai_date($page['updated_at'] ?? $page['created_at'], 'compact') : date('d/m/Y', strtotime($page['updated_at'] ?? $page['created_at'])) ?>
                            </span>
                            <span class="d-none d-sm-inline opacity-50">|</span>
                            <span>
                                <i class="fa-regular fa-eye me-1 text-primary"></i> <?= number_format($page['views'] ?? 0) ?> ครั้ง
                            </span>
                        </div>

                        <!-- แถบเครื่องมือแชร์โซเชียล สั่งพิมพ์ และปรับขนาดตัวอักษร -->
                        <div>
                            <?= $this->include('components/content_share_toolbar') ?>
                        </div>
                    </div>

                    <!-- 3. เนื้อหาเพจ -->
                    <?php if (!empty($children)): ?>
                        <div class="tab-content" id="pageTabContent">
                            <!-- เนื้อหาหลัก -->
                            <div class="tab-pane fade show active dynamic-content" id="tab-main" role="tabpanel" aria-labelledby="main-tab">
                                <?= $page['content'] ?? '' ?>
                            </div>
                            <!-- เนื้อหาย่อย (Children) -->
                            <?php foreach($children as $idx => $child): ?>
                            <div class="tab-pane fade dynamic-content" id="tab-child-<?= $child['id'] ?>" role="tabpanel" aria-labelledby="child-tab-<?= $child['id'] ?>">
                                <?= $child['content'] ?? '' ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- แสดงผลเนื้อหาเดี่ยวๆ -->
                        <div class="dynamic-content">
                            <?= $page['content'] ?? '' ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between text-muted small" style="border-color: rgba(0,0,0,0.06) !important; font-size: 0.85rem;">
                        <div>
                            <i class="fa-regular fa-clock me-1 text-primary"></i> อัปเดตล่าสุด: <?= function_exists('thai_date') ? thai_date($page['updated_at'] ?? $page['created_at'], 'full', true) : date('d/m/Y H:i', strtotime($page['updated_at'] ?? $page['created_at'])) ?>
                        </div>
                        <div>
                            <i class="fa-regular fa-eye me-1 text-primary"></i> จำนวนผู้เข้าชม: <span class="fw-bold text-dark"><?= number_format($page['views'] ?? 0) ?></span> ครั้ง
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* แท็บควบคุมการสลับหน้าย่อย */
.custom-pills .nav-link {
    color: #475569;
    background-color: #f1f5f9;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
}
.custom-pills .nav-link:hover {
    background-color: #e2e8f0;
    color: #1e293b;
}
.custom-pills .nav-link.active {
    background-color: #1e3a8a !important;
    color: #ffffff !important;
    border-color: #1e3a8a !important;
    box-shadow: 0 2px 8px rgba(30, 58, 138, 0.25);
}

/* จัดรูปแบบเนื้อหาที่สร้างจาก WYSIWYG Editor ให้กระชับ */
.dynamic-content {
    line-height: 1.75;
    color: var(--text-primary, #1e293b);
    font-size: 1.02rem;
}
.dynamic-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 1rem 0;
}
.dynamic-content p {
    margin-bottom: 0.95rem;
}
.dynamic-content h2, .dynamic-content h3, .dynamic-content h4 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-weight: 700;
    color: #1e3a8a;
}
.dynamic-content a {
    color: #1e40af;
    text-decoration: underline;
}
.dynamic-content table {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
}
.dynamic-content table th, .dynamic-content table td {
    padding: 0.6rem 0.85rem;
    border: 1px solid #e2e8f0;
}
</style>

<?= $this->endSection() ?>

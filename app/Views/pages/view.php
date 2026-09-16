<?= $this->extend('layouts/main') ?>

<?php 
$page = $page ?? []; 
$children = $children ?? []; 

$headerRaw = !empty($page['header_image']) ? $page['header_image'] : '';
$headerSources = !empty($headerRaw) && function_exists('get_image_sources') ? get_image_sources($headerRaw) : null;
$headerUrl = $headerSources ? (!empty($headerSources['webp_url']) ? $headerSources['webp_url'] : $headerSources['original_url']) : (!empty($headerRaw) ? base_url(ltrim($headerRaw, '/')) : '');

$renderContent = function($rawHtml) use ($headerUrl) {
    if (empty($rawHtml)) return '';
    $html = str_replace('../uploads/', base_url('uploads/'), $rawHtml);
    $html = str_replace('./uploads/', base_url('uploads/'), $html);

    // Auto-convert images to modern WebP & apply responsive lazy loading
    $imgCount = 0;
    $html = preg_replace_callback('/<img\s+([^>]*?)src=["\']([^"\']+)["\']([^>]*)>/i', function($m) use (&$imgCount, $headerUrl) {
        $prefix = $m[1];
        $src = $m[2];
        $suffix = $m[3];

        $webpSrc = $src;
        if (strpos($src, 'uploads/') !== false) {
            $parsed = parse_url($src, PHP_URL_PATH);
            $pos = strpos($parsed, 'uploads/');
            if ($pos !== false) {
                $rel = substr($parsed, $pos);
                if (function_exists('convert_to_webp_if_missing')) {
                    $w = convert_to_webp_if_missing($rel);
                    if ($w) {
                        $webpSrc = base_url($w);
                    }
                }
            }
        }

        // The first image in content is LCP if no header image exists
        $isLcp = ($imgCount === 0 && empty($headerUrl));
        $loadingAttr = $isLcp ? 'fetchpriority="high" loading="eager"' : 'loading="lazy"';
        $imgCount++;

        $rest = preg_replace('/\s+(loading|decoding|fetchpriority)=["\'][^"\']*["\']/i', '', $prefix . ' ' . $suffix);
        return '<img src="' . htmlspecialchars($webpSrc) . '" ' . trim($rest) . ' ' . $loadingAttr . ' decoding="async">';
    }, $html);

    return $html;
};
?>

<?php if (!empty($headerUrl)): ?>
<?= $this->section('head_preload') ?>
    <link rel="preload" as="image" href="<?= $headerUrl ?>" type="image/webp" fetchpriority="high">
<?= $this->endSection() ?>
<?php endif; ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER (รองรับภาพพื้นหลังกำหนดเอง - ขนาดใหญ่สวยงามเต็มตา / WebP Optimized) -->
<div class="text-white py-4 py-md-5 position-relative overflow-hidden" 
     style="min-height: 200px; display: flex; align-items: center; <?= !empty($headerUrl) ? "background: url('" . esc($headerUrl) . "') center center / cover no-repeat;" : 'background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #047857 100%);' ?> box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
    
    <!-- Dark Vignette Overlay for High Contrast Text Readability -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: <?= !empty($headerUrl) ? 'linear-gradient(90deg, rgba(2,44,34,0.92) 0%, rgba(2,44,34,0.72) 50%, rgba(2,44,34,0.45) 100%)' : 'rgba(0,0,0,0.2)' ?>; z-index: 1;"></div>
    
    <div class="container position-relative py-2" style="z-index: 2;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.88rem; font-family: 'Prompt', sans-serif;">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white text-decoration-none opacity-75 hover-opacity-100"><i class="fa-solid fa-house me-1"></i>หน้าแรก</a></li>
                <li class="breadcrumb-item active text-white fw-medium" aria-current="page"><?= esc($page['title'] ?? '') ?></li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-0 text-white page-main-title"><?= esc($page['title'] ?? '') ?></h1>
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
                        <?php
                        $getChildIcon = function($title) {
                            if (stripos($title, 'ประวัติ') !== false) return 'fa-landmark';
                            if (stripos($title, 'สัญลักษณ์') !== false || stripos($title, 'โลโก้') !== false) return 'fa-award';
                            if (stripos($title, 'ยุทธศาสตร์') !== false || stripos($title, 'พัฒนา') !== false) return 'fa-bullseye';
                            if (stripos($title, 'แผนที่') !== false) return 'fa-map-location-dot';
                            return 'fa-layer-group';
                        };
                        ?>
                        <div class="mb-4">
                            <ul class="nav nav-pills custom-pills gap-2 flex-wrap" id="pageTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active rounded-pill fw-semibold" id="main-tab" data-bs-toggle="tab" data-bs-target="#tab-main" type="button" role="tab" aria-controls="tab-main" aria-selected="true">
                                        <i class="fa-solid fa-circle-info me-1.5 opacity-85"></i><?= esc($page['title'] ?? '') ?>
                                    </button>
                                </li>
                                <?php foreach($children as $idx => $child): ?>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link rounded-pill fw-semibold" id="child-tab-<?= $child['id'] ?>" data-bs-toggle="tab" data-bs-target="#tab-child-<?= $child['id'] ?>" type="button" role="tab" aria-controls="tab-child-<?= $child['id'] ?>" aria-selected="false">
                                        <i class="fa-solid <?= $getChildIcon($child['title'] ?? '') ?> me-1.5 opacity-85"></i><?= esc($child['title'] ?? '') ?>
                                    </button>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- 2. แถบสถานะและเครื่องมือ (จัดวางสมดุล ซ้าย: วันที่/ยอดวิว, ขวา: แชร์/พิมพ์/ซูม) -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(0,0,0,0.08) !important;">
                        <div class="d-flex align-items-center gap-2.5 text-secondary" style="font-family: 'Prompt', sans-serif; font-size: 0.88rem;">
                            <span class="d-inline-flex align-items-center">
                                <i class="fa-regular fa-clock me-1.5 text-success"></i> อัปเดต: <?= function_exists('thai_date') ? thai_date($page['updated_at'] ?? $page['created_at'], 'compact') : date('d/m/Y', strtotime($page['updated_at'] ?? $page['created_at'])) ?>
                            </span>
                            <span class="opacity-40">•</span>
                            <span class="d-inline-flex align-items-center">
                                <i class="fa-regular fa-eye me-1.5 text-success"></i> <?= number_format($page['views'] ?? 0) ?> ครั้ง
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
                                <?= $renderContent($page['content'] ?? '') ?>
                            </div>
                            <!-- เนื้อหาย่อย (Children) -->
                            <?php foreach($children as $idx => $child): ?>
                            <div class="tab-pane fade dynamic-content" id="tab-child-<?= $child['id'] ?>" role="tabpanel" aria-labelledby="child-tab-<?= $child['id'] ?>">
                                <?= $renderContent($child['content'] ?? '') ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- แสดงผลเนื้อหาเดี่ยวๆ -->
                        <div class="dynamic-content">
                            <?= $renderContent($page['content'] ?? '') ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between text-muted" style="border-color: rgba(0,0,0,0.06) !important; font-family: 'Prompt', sans-serif; font-size: 0.86rem;">
                        <div>
                            <i class="fa-regular fa-clock me-1 text-success"></i> อัปเดตล่าสุด: <?= function_exists('thai_date') ? thai_date($page['updated_at'] ?? $page['created_at'], 'full', true) : date('d/m/Y H:i', strtotime($page['updated_at'] ?? $page['created_at'])) ?>
                        </div>
                        <div>
                            <i class="fa-regular fa-eye me-1 text-success"></i> จำนวนผู้เข้าชม: <span class="fw-bold text-dark"><?= number_format($page['views'] ?? 0) ?></span> ครั้ง
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* หัวข้อใหญ่ประจำหน้า */
.page-main-title {
    font-family: 'Prompt', sans-serif;
    font-size: 1.85rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 10px rgba(0,0,0,0.6);
}

@media (max-width: 767px) {
    .page-main-title {
        font-size: 1.45rem;
    }
}

/* แท็บควบคุมการสลับหน้าย่อย (Modern Segment Control) */
.custom-pills .nav-link {
    font-family: 'Prompt', sans-serif;
    font-size: 0.95rem;
    padding: 0.55rem 1.25rem;
    color: #475569;
    background-color: #f1f5f9;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e2e8f0;
    display: inline-flex;
    align-items: center;
}
.custom-pills .nav-link:hover {
    background-color: #e2e8f0;
    color: #0f172a;
    transform: translateY(-1px);
}
.custom-pills .nav-link.active {
    background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #047857 100%) !important;
    color: #ffffff !important;
    border-color: #047857 !important;
    box-shadow: 0 4px 14px rgba(6, 78, 59, 0.35);
}

/* จัดรูปแบบเนื้อหาการอ่าน (Article Reading Typography) */
.dynamic-content {
    font-family: 'Sarabun', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 1.15rem; /* ขนาดมาตรฐานอ่านสบายตาสำหรับภาษาไทย */
    line-height: 1.85;
    color: #1e293b;
    letter-spacing: 0.005em;
}
.dynamic-content p {
    margin-bottom: 1.25rem;
}
.dynamic-content h2 {
    font-family: 'Prompt', sans-serif;
    font-size: 1.55rem;
    font-weight: 700;
    color: #064e3b;
    margin-top: 2rem;
    margin-bottom: 0.9rem;
    padding-bottom: 0.4rem;
    border-bottom: 2px solid #e2e8f0;
}
.dynamic-content h3 {
    font-family: 'Prompt', sans-serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: #047857;
    margin-top: 1.75rem;
    margin-bottom: 0.75rem;
}
.dynamic-content h4 {
    font-family: 'Prompt', sans-serif;
    font-size: 1.15rem;
    font-weight: 600;
    color: #0f172a;
    margin-top: 1.25rem;
    margin-bottom: 0.5rem;
}
.dynamic-content ul, .dynamic-content ol {
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
}
.dynamic-content li {
    margin-bottom: 0.55rem;
    line-height: 1.8;
}
.dynamic-content strong {
    font-weight: 600;
    color: #0f172a;
}
.dynamic-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 1.25rem 0;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}
.dynamic-content a {
    color: #047857;
    text-decoration: underline;
    font-weight: 500;
}
.dynamic-content a:hover {
    color: #064e3b;
}
.dynamic-content table {
    width: 100%;
    margin-bottom: 1.5rem;
    border-collapse: collapse;
    font-size: 1.02rem;
    line-height: 1.6;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
}
.dynamic-content table th, .dynamic-content table td {
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    vertical-align: top;
}
.dynamic-content table th {
    background-color: #f8fafc;
    color: #0f172a;
    font-family: 'Prompt', sans-serif;
    font-weight: 600;
    border-bottom: 2px solid #cbd5e1;
}
</style>

<?= $this->endSection() ?>

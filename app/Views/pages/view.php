<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="bg-primary text-white py-5 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); mix-blend-mode: overlay;"></div>
    <div class="container position-relative z-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-white text-decoration-none opacity-75 hover-opacity-100"><i class="fa-solid fa-house"></i> หน้าแรก</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page"><?= esc($page['title']) ?></li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-0 text-shadow"><?= esc($page['title']) ?></h1>
    </div>
</div>

<!-- PAGE CONTENT -->
<div class="container my-5 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: var(--card-bg, #ffffff);">
                <div class="card-body p-4 p-md-5 page-content-container">
                    
                    <!-- เนื้อหาหลัก หรือ Tabs (ถ้าระบบมีหน้าย่อย) -->
                    <?php if (!empty($children)): ?>
                        <ul class="nav nav-pills custom-pills mb-4" id="pageTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill px-4 fw-bold" id="main-tab" data-bs-toggle="tab" data-bs-target="#tab-main" type="button" role="tab" aria-controls="tab-main" aria-selected="true">
                                    <i class="fa-solid fa-file-lines me-2"></i><?= esc($page['title']) ?>
                                </button>
                            </li>
                            <?php foreach($children as $idx => $child): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill px-4 fw-bold" id="child-tab-<?= $child['id'] ?>" data-bs-toggle="tab" data-bs-target="#tab-child-<?= $child['id'] ?>" type="button" role="tab" aria-controls="tab-child-<?= $child['id'] ?>" aria-selected="false">
                                    <?= esc($child['title']) ?>
                                </button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="tab-content" id="pageTabContent">
                            <!-- เนื้อหาหลัก -->
                            <div class="tab-pane fade show active dynamic-content" id="tab-main" role="tabpanel" aria-labelledby="main-tab">
                                <?= $page['content'] ?>
                            </div>
                            <!-- เนื้อหาย่อย (Children) -->
                            <?php foreach($children as $idx => $child): ?>
                            <div class="tab-pane fade dynamic-content" id="tab-child-<?= $child['id'] ?>" role="tabpanel" aria-labelledby="child-tab-<?= $child['id'] ?>">
                                <?= $child['content'] ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- แสดงผลเนื้อหาเดี่ยวๆ (ไม่มีหน้าย่อย) -->
                        <div class="dynamic-content">
                            <?= $page['content'] ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-5 pt-4 border-top d-flex flex-wrap align-items-center justify-content-between text-muted small" style="border-color: rgba(0,0,0,0.06) !important;">
                        <div>
                            <i class="fa-regular fa-clock me-1 text-primary"></i> อัปเดตล่าสุด: <?= function_exists('thai_date') ? thai_date($page['updated_at'] ?? $page['created_at'], 'full', true) : date('d/m/Y H:i', strtotime($page['updated_at'] ?? $page['created_at'])) ?>
                        </div>
                        <div>
                            <i class="fa-regular fa-eye me-1 text-primary"></i> จำนวนผู้เข้าชม: <span class="fw-bold text-dark"><?= number_format($page['views']) ?></span> ครั้ง
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* จัดรูปแบบเนื้อหาที่สร้างจาก WYSIWYG Editor */
.dynamic-content {
    line-height: 1.8;
    color: var(--text-primary);
    font-size: 1.05rem;
}
.dynamic-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 1.5rem 0;
}
.dynamic-content p {
    margin-bottom: 1.2rem;
}
.dynamic-content h2, .dynamic-content h3, .dynamic-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: var(--primary-color);
}
.dynamic-content a {
    color: var(--primary-color);
    text-decoration: underline;
}
.dynamic-content table {
    width: 100% !important;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
}
.dynamic-content table th, .dynamic-content table td {
    border: 1px solid rgba(0,0,0,0.1);
    padding: 10px;
}
/* สไตล์สำหรับ Tabs (Nav-Pills) */
.custom-pills .nav-link {
    color: var(--text-secondary);
    background-color: transparent;
    transition: all 0.3s ease;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}
.custom-pills .nav-link:hover {
    background-color: rgba(0,0,0,0.05);
}
.custom-pills .nav-link.active {
    color: white;
    background-color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>

<?= $this->endSection() ?>

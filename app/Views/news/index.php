<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isOfficer = session()->get('isLoggedIn');
?>

<div class="container my-5 pt-4">
    <!-- Breadcrumb & Header Banner -->
    <div class="glass-card p-4 p-md-5 rounded-4 shadow-lg mb-5" style="background: rgba(30, 41, 59, 0.65); border: 1px solid rgba(255,255,255,0.15) !important;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb m-0" style="font-size: 0.95rem;">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>" class="text-info text-decoration-none"><i class="fa-solid fa-house me-1"></i>หน้าหลัก</a></li>
                <li class="breadcrumb-item active text-light" aria-current="page">ข่าวสารและประกาศจากสำนักงาน</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
            <div>
                <h2 class="fw-bold mb-1 text-white d-flex align-items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-warning fs-1"></i>
                    <span>คลังข่าวสารและประกาศราชการ</span>
                    <?php if ($isOfficer): ?>
                        <span class="badge bg-success text-dark fs-6 px-3 py-1"><i class="fa-solid fa-user-shield me-1"></i>On-Page CMS Ready</span>
                    <?php endif; ?>
                </h2>
                <p class="text-secondary m-0">ติดตามข่าวสาร กิจกรรมจังหวัด ประกาศประกวดราคา และคู่มือประชาชนล่าสุด</p>
            </div>

            <?php if ($isOfficer): ?>
                <div class="d-flex gap-2">
                    <button type="button" onclick="NewsStudio.addCategory()" class="btn btn-outline-info px-4 py-2 rounded-pill fw-bold d-flex align-items-center gap-2">
                        <i class="fa-solid fa-tags"></i> เพิ่มหมวดหมู่
                    </button>
                    <button type="button" onclick="NewsStudio.open()" class="btn btn-warning px-4 py-2 rounded-pill fw-bold text-dark d-flex align-items-center gap-2 shadow-lg hover-scale" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                        <i class="fa-solid fa-circle-plus fs-5"></i> + สร้างข่าวสารใหม่ทันที (Studio)
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Category Pills Filter -->
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
        <a href="<?= base_url('news') ?>" class="btn px-4 py-2 news-cat-btn <?= empty($currentCat) ? 'active' : '' ?>">
            🌟 ทั้งหมด (All)
        </a>
        <?php foreach ($categories as $cat): 
            $isActive = (strcasecmp(trim($currentCat ?? ''), trim($cat)) === 0);
        ?>
            <a href="<?= base_url('news?category=' . urlencode($cat)) ?>" class="btn px-4 py-2 news-cat-btn <?= $isActive ? 'active' : '' ?>">
                📁 <?= esc($cat) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- News Grid -->
    <div class="row g-4">
        <?php if (empty($newsList)): ?>
            <div class="col-12 text-center py-5">
                <div class="glass-card p-5 rounded-4 d-inline-block shadow-sm" style="background: rgba(30, 41, 59, 0.4);">
                    <i class="fa-regular fa-folder-open text-info fs-1 mb-3"></i>
                    <h5 class="text-white fw-bold">ไม่พบข้อมูลข่าวสารในหมวดหมู่นี้</h5>
                    <p class="text-secondary small m-0">กรุณาลองเปลี่ยนหมวดหมู่ค้นหา หรือคลิกเพิ่มข่าวสารใหม่จากโหมดเจ้าหน้าที่</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($newsList as $item): 
                $coverSrc = !empty($item['cover_image']) ? ((strpos((string)$item['cover_image'], 'http') === 0) ? $item['cover_image'] : base_url($item['cover_image'])) : base_url('assets/images/slider/sane_muanglung.png');
                $attachCount = !empty($item['attachments']) ? count($item['attachments']) : 0;
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card news-showcase-card h-100 border-0">
                        
                        <?php if ($isOfficer): ?>
                            <div class="position-absolute top-0 end-0 m-2 z-3 d-flex gap-1">
                                <button type="button" onclick="event.stopPropagation(); event.preventDefault(); NewsStudio.open('<?= $item['id'] ?>')" class="btn btn-sm btn-info rounded-circle shadow-lg p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" title="แก้ไขข่าวนี้บนหน้าเว็บ">
                                    <i class="fa-solid fa-pen text-dark fw-bold"></i>
                                </button>
                                <button type="button" onclick="event.stopPropagation(); event.preventDefault(); NewsStudio.deleteNews('<?= $item['id'] ?>')" class="btn btn-sm btn-danger rounded-circle shadow-lg p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" title="ลบข่าวนี้">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>

                        <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="text-decoration-none position-relative d-block overflow-hidden" style="height: 220px; background-color: #090d16;">
                            <img src="<?= $coverSrc ?>" class="w-100 h-100 transition-transform duration-500 hover-zoom" style="object-fit: <?= esc($item['cover_fit'] ?? 'cover') ?>;" alt="<?= esc($item['title']) ?>">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge rounded-pill bg-dark text-warning border px-3 py-2 shadow-sm" style="border-color: rgba(245, 158, 11, 0.5) !important; backdrop-filter: blur(6px);">
                                    <i class="fa-solid fa-folder me-1"></i> <?= esc($item['category'] ?? 'ข่าวประกาศ') ?>
                                </span>
                            </div>
                        </a>

                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2 news-card-meta small">
                                    <span><i class="fa-regular fa-calendar me-1"></i> <?= date('d/m/Y', strtotime($item['created_at'] ?? 'now')) ?></span>
                                    <span><i class="fa-regular fa-eye me-1"></i> <?= number_format($item['views'] ?? 1) ?> ครั้ง</span>
                                </div>
                                <h5 class="fw-bold news-card-title mb-1 line-clamp-2">
                                    <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="text-decoration-none">
                                        <?= esc($item['title']) ?>
                                    </a>
                                </h5>
                            </div>

                            <div class="mt-4 pt-3 news-card-footer d-flex align-items-center justify-content-between">
                                <?php if ($attachCount > 0): ?>
                                    <span class="badge bg-info text-dark fw-bold"><i class="fa-solid fa-paperclip me-1"></i> <?= $attachCount ?> ไฟล์แนบ</span>
                                <?php else: ?>
                                    <span class="text-muted small"><i class="fa-solid fa-book-open me-1"></i> รายละเอียด</span>
                                <?php endif; ?>
                                <a href="<?= base_url('news/detail/' . $item['id']) ?>" class="btn btn-sm news-card-btn rounded-pill px-3 fw-bold">
                                    อ่านรายละเอียด <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

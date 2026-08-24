<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
    $feeds = $feeds ?? [];
    $total = $total ?? count($feeds);
    $lastSync = $lastSync ?? '-';
?>

<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">
                    <i class="fa-solid fa-satellite-dish me-1"></i> Live News & Social Aggregator
                </span>
                <span class="text-muted small">ระบบดึงฟีดข่าวอัตโนมัติ 24 ชม. (สปชส.พัทลุง, NNT, Facebook, เตือนภัย)</span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">ระบบรวบรวมข่าวสารอัตโนมัติ</h3>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('news') ?>" target="_blank" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> ดูหน้าคลังข่าวสาร
            </a>
            <button type="button" id="btnSyncFeeds" onclick="syncAggregatedFeeds()" class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm hover-scale">
                <i class="fa-solid fa-arrows-rotate" id="syncIcon"></i>
                <span id="syncText">🔄 ซิงค์ข่าวสารทันที (Sync Now)</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">จำนวนข่าวที่ดึงได้</span>
                        <h3 class="fw-bold mb-0 text-dark" id="statTotal"><?= $total ?> <small class="fs-6 text-muted">ข่าว</small></h3>
                    </div>
                    <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-rss fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">อัปเดตล่าสุด</span>
                        <h5 class="fw-bold mb-0 text-success" id="statLastSync" style="font-size: 1.05rem;"><?= $lastSync ?></h5>
                    </div>
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-clock-rotate-left fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">แหล่งข่าวภาครัฐ (RSS/API)</span>
                        <h5 class="fw-bold mb-0 text-primary" style="font-size: 1.05rem;">สปชส. / NNT / มท.</h5>
                    </div>
                    <div class="rounded-circle p-3 bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-bullhorn fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">กระแสโซเชียลทางการ</span>
                        <h5 class="fw-bold mb-0 text-primary" style="font-size: 1.05rem;">Official Facebook Feed</h5>
                    </div>
                    <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary">
                        <i class="fa-brands fa-facebook fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feeds Table Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-list-check text-primary me-2"></i>รายการข่าวและโพสต์ที่รวบรวมได้ (Aggregated Items)
                </h5>
                <small class="text-muted">ระบบจะอัปเดตแคชอัตโนมัติทุก 30 นาที และสามารถคลิก "นำเข้าเป็นข่าวเว็บ" เพื่อบันทึกเป็นข่าวถาวรได้</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="aggregatedTable">
                <thead class="bg-light text-muted small">
                    <tr>
                        <th class="ps-4" style="width: 50px;">ลำดับ</th>
                        <th style="min-width: 320px;">หัวข้อข่าว / โพสต์</th>
                        <th>แหล่งข่าวต้นทาง</th>
                        <th>หมวดหมู่</th>
                        <th>เวลาเผยแพร่</th>
                        <th class="text-end pe-4" style="width: 220px;">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($feeds)): ?>
                        <?php foreach ($feeds as $idx => $item): ?>
                            <tr>
                                <td class="ps-4 text-muted fw-bold"><?= $idx + 1 ?></td>
                                <td>
                                    <a href="<?= esc($item['link']) ?>" target="_blank" class="fw-bold text-dark text-decoration-none d-block mb-1 text-truncate" style="max-width: 480px;">
                                        <?= esc($item['title']) ?>
                                    </a>
                                    <p class="text-muted small mb-0 text-truncate" style="max-width: 480px;">
                                        <?= esc($item['summary']) ?>
                                    </p>
                                </td>
                                <td>
                                    <span class="badge <?= $item['badge_color'] ?? 'bg-secondary' ?> rounded-pill px-2.5 py-1">
                                        <i class="<?= $item['source_icon'] ?? 'fa-solid fa-rss' ?> me-1"></i>
                                        <?= esc($item['source']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1">
                                        <?= esc($item['category'] ?? 'ข่าวทั่วไป') ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($item['published_at'] ?? 'now')) ?>
                                    </small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="<?= esc($item['link']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5" title="เปิดดูต้นฉบับ">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                        <?php if (!empty($item['is_imported'])): ?>
                                            <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3 fw-bold disabled" disabled style="opacity: 0.85; cursor: not-allowed;">
                                                <i class="fa-solid fa-check me-1 text-success"></i> นำเข้าแล้ว
                                            </button>
                                        <?php else: ?>
                                            <button type="button" id="btnImport_<?= esc($item['id']) ?>" onclick="importFeedItem('<?= esc($item['id']) ?>', this)" class="btn btn-sm btn-success rounded-pill px-3 fw-bold d-flex align-items-center gap-1 shadow-sm hover-scale">
                                                <i class="fa-solid fa-download"></i> นำเข้าข่าว
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-rss fs-1 d-block mb-2 opacity-50"></i>
                                ยังไม่มีข้อมูลข่าวที่ดึงเข้ามา กรุณากดปุ่ม <strong>"ซิงค์ข่าวสารทันที"</strong> ด้านบน
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function syncAggregatedFeeds() {
    const btn = document.getElementById('btnSyncFeeds');
    const icon = document.getElementById('syncIcon');
    const txt = document.getElementById('syncText');

    icon.classList.add('fa-spin');
    txt.innerText = 'กำลังซิงค์ข่าวจากทุกแหล่ง...';
    btn.disabled = true;

    fetch('<?= base_url('admin/news-aggregator/sync') ?>')
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถซิงค์ข่าวได้'));
                icon.classList.remove('fa-spin');
                txt.innerText = '🔄 ซิงค์ข่าวสารทันที (Sync Now)';
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
            icon.classList.remove('fa-spin');
            txt.innerText = '🔄 ซิงค์ข่าวสารทันที (Sync Now)';
            btn.disabled = false;
        });
}

function importFeedItem(feedId, btn) {
    if (!confirm('ต้องการนำเข้าข่าวนี้สู่ระบบคลังข่าวถาวรของเว็บไซต์ใช่หรือไม่?')) {
        return;
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังนำเข้า...';
    }

    const formData = new FormData();
    formData.append('feed_id', feedId);

    fetch('<?= base_url('admin/news-aggregator/import') ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            if (btn) {
                btn.className = 'btn btn-sm btn-secondary rounded-pill px-3 fw-bold disabled';
                btn.style.opacity = '0.85';
                btn.style.cursor = 'not-allowed';
                btn.innerHTML = '<i class="fa-solid fa-check me-1 text-success"></i> นำเข้าแล้ว';
                btn.onclick = null;
            }
            alert(data.message);
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-download"></i> นำเข้าข่าว';
            }
            alert('ข้อผิดพลาด: ' + (data.message || 'ไม่สามารถนำเข้าข่าวได้'));
        }
    })
    .catch(err => {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-download"></i> นำเข้าข่าว';
        }
        alert('เกิดข้อผิดพลาดในการนำเข้าข่าว');
    });
}
</script>

<?= $this->endSection() ?>

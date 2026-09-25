<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
    $feeds = $feeds ?? [];
    $total = $total ?? count($feeds);
    $lastSync = $lastSync ?? '-';
?>

<!-- Hero Cockpit Header -->
<div class="glass-card mb-4 p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 28px; background: linear-gradient(135deg, rgba(239, 68, 68, 0.12) 0%, rgba(249, 115, 22, 0.10) 50%, rgba(139, 92, 246, 0.08) 100%); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="position-absolute" style="top: -40px; right: -30px; font-size: 15rem; color: rgba(239, 68, 68, 0.04); pointer-events: none; z-index: 0;">
        <i class="fa-solid fa-satellite-dish"></i>
    </div>
    
    <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #dc2626; font-size: 0.85rem; font-weight: 600;">
                <span class="spinner-grow spinner-grow-sm text-danger" style="width: 8px; height: 8px;"></span>
                <span>Live News & Social Media Aggregator</span>
            </div>
            <h2 class="fw-bold mb-2" style="color: var(--text-primary); letter-spacing: -0.5px;">
                ระบบรวบรวมข่าวสารอัตโนมัติ (Live Intelligence Radar)
            </h2>
            <p class="text-secondary mb-0" style="font-size: 1rem; max-width: 760px;">
                ระบบเฝ้าฟังและดึงฟีดข่าวประชาสัมพันธ์อัตโนมัติตลอด 24 ชม. (สปชส.พัทลุง, NNT สำนักข่าวแห่งชาติ, กระทรวงมหาดไทย, และ Official Facebook Feed) พร้อมนำเข้าเป็นข่าวทางการของจังหวัดในคลิกเดียว
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('news') ?>" target="_blank" class="btn btn-outline-secondary px-3 py-2 rounded-4 fw-medium d-flex align-items-center gap-2 shadow-sm transition-all" style="border-width: 1.5px; background: var(--bg-surface);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> <span>ดูคลังข่าวประชาชน</span>
            </a>
            <button type="button" id="btnSyncFeeds" onclick="syncAggregatedFeeds()" class="btn btn-danger px-4 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow transition-all" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; font-size: 1rem;">
                <i class="fa-solid fa-arrows-rotate" id="syncIcon"></i>
                <span id="syncText">ซิงค์ข่าวสารทันที (Sync Now)</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row inside Cockpit -->
    <div class="position-relative z-1 row g-3 mt-3 pt-3 border-top" style="border-color: var(--glass-border) !important;">
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-rss text-danger me-1"></i> จำนวนข่าวในเรดาร์</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold" id="statTotal" style="color: var(--text-primary);"><?= number_format($total) ?></span>
                    <span class="small text-secondary">ข่าวที่ตรวจพบ</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-regular fa-clock text-warning me-1"></i> อัปเดตล่าสุด</div>
                <div class="fs-6 fw-bold text-truncate" id="statLastSync" style="color: var(--text-primary);" title="<?= esc($lastSync) ?>"><?= esc($lastSync) ?></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-shield-halved text-primary me-1"></i> แหล่งข่าวภาครัฐ (RSS/API)</div>
                <div class="d-flex align-items-center gap-1.5 mt-1">
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">สปชส.พัทลุง</span>
                    <span class="badge bg-info-subtle text-info rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">NNT</span>
                    <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">มท.</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-brands fa-facebook text-info me-1"></i> โซเชียลทางการ</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-primary rounded-pill px-3 py-1 d-flex align-items-center gap-1 text-white" style="font-size: 0.78rem; background: #1877f2 !important;">
                        <i class="fa-brands fa-facebook"></i> Official Page Feed
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Source Filter Bar -->
<div class="glass-card mb-4 p-3 rounded-4" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
    <div class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--glass-border); color: var(--text-secondary);">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" class="form-control custom-input border-start-0" id="filterAggSearch" placeholder="ค้นหาหัวข้อข่าว, คำสำคัญ, หรือแหล่งที่มา..." oninput="filterAggregatedFeeds()">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select custom-input" id="filterAggSource" onchange="filterAggregatedFeeds()">
                <option value="all">🌐 แหล่งข่าวทั้งหมด (All Sources)</option>
                <option value="สปชส">สปชส.พัทลุง</option>
                <option value="NNT">NNT สำนักข่าวแห่งชาติ</option>
                <option value="มท">กระทรวงมหาดไทย</option>
                <option value="facebook">Official Facebook</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-select custom-input" id="filterAggStatus" onchange="filterAggregatedFeeds()">
                <option value="all">📋 สถานะทั้งหมด</option>
                <option value="pending">ยังไม่ได้นำเข้า</option>
                <option value="imported">นำเข้าแล้ว</option>
            </select>
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-outline-secondary w-100 rounded-3 py-2 fw-medium" onclick="resetAggFilters()">
                <i class="fa-solid fa-rotate-left me-1"></i> ล้างตัวกรอง
            </button>
        </div>
    </div>
</div>

<!-- Feeds Table Card -->
<div class="glass-card rounded-4 overflow-hidden shadow-sm" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
    <div class="p-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--glass-border) !important;">
        <div>
            <h5 class="fw-bold mb-1" style="color: var(--text-primary);">
                <i class="fa-solid fa-list-check text-danger me-2"></i>รายการข่าวและโพสต์ที่ตรวจพบ (Feed Monitor)
            </h5>
            <div class="text-secondary small">
                ระบบแคชอัตโนมัติรอบละ 30 นาที สามารถกด <strong>"นำเข้าข่าว"</strong> เพื่อเปลี่ยนข่าวฟีดให้กลายเป็นข่าวประชาสัมพันธ์ทางการของระบบ
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1.5" id="filteredItemCount">
                แสดงผล <?= count($feeds) ?> รายการ
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="aggregatedTable">
            <thead class="small text-secondary" style="background: rgba(0,0,0,0.02);">
                <tr>
                    <th class="ps-4 table-nowrap" style="width: 60px;">#</th>
                    <th style="min-width: 340px;">หัวข้อข่าว / สาระสำคัญ</th>
                    <th class="table-nowrap">แหล่งข่าวต้นทาง</th>
                    <th class="table-nowrap">หมวดหมู่</th>
                    <th class="table-nowrap">เวลาเผยแพร่</th>
                    <th class="text-end pe-4 table-nowrap" style="width: 220px;">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($feeds)): ?>
                    <?php foreach ($feeds as $idx => $item): 
                        $isImported = !empty($item['is_imported']);
                        $feedSource = $item['source'] ?? 'ข่าวประชาสัมพันธ์';
                        $feedTitle = $item['title'] ?? '';
                        $feedSummary = $item['summary'] ?? '';
                    ?>
                        <tr class="feed-row" data-title="<?= strtolower(esc($feedTitle)) ?>" data-summary="<?= strtolower(esc($feedSummary)) ?>" data-source="<?= strtolower(esc($feedSource)) ?>" data-status="<?= $isImported ? 'imported' : 'pending' ?>">
                            <td class="ps-4 text-secondary fw-bold"><?= $idx + 1 ?></td>
                            <td>
                                <a href="<?= esc($item['link']) ?>" target="_blank" class="fw-bold text-decoration-none d-block mb-1" style="color: var(--text-primary); font-size: 0.98rem; line-height: 1.45;">
                                    <?= esc($feedTitle) ?>
                                    <i class="fa-solid fa-arrow-up-right-from-square small text-secondary ms-1" style="font-size: 0.75rem;"></i>
                                </a>
                                <?php if (!empty($feedSummary)): ?>
                                    <p class="text-secondary small mb-0 text-truncate" style="max-width: 540px; font-size: 0.88rem;">
                                        <?= esc($feedSummary) ?>
                                    </p>
                                <?php endif; ?>
                            </td>
                            <td class="table-nowrap">
                                <span class="badge <?= $item['badge_color'] ?? 'bg-secondary' ?> rounded-pill px-3 py-1.5 fw-medium">
                                    <i class="<?= $item['source_icon'] ?? 'fa-solid fa-rss' ?> me-1"></i>
                                    <?= esc($feedSource) ?>
                                </span>
                            </td>
                            <td class="table-nowrap">
                                <span class="badge bg-light text-secondary border rounded-pill px-2.5 py-1" style="border-color: var(--glass-border) !important;">
                                    <?= esc($item['category'] ?? 'ข่าวทั่วไป') ?>
                                </span>
                            </td>
                            <td class="table-nowrap">
                                <span class="text-secondary small">
                                    <i class="fa-regular fa-clock me-1 text-muted"></i>
                                    <?= date('d/m/Y H:i', strtotime($item['published_at'] ?? 'now')) ?>
                                </span>
                            </td>
                            <td class="text-end pe-4 table-nowrap">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <a href="<?= esc($item['link']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1.5" title="เปิดดูหน้าเว็บต้นฉบับ">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <?php if ($isImported): ?>
                                        <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3 py-1.5 fw-bold disabled" disabled style="opacity: 0.85; cursor: not-allowed;">
                                            <i class="fa-solid fa-check me-1 text-success"></i> นำเข้าแล้ว
                                        </button>
                                    <?php else: ?>
                                        <button type="button" id="btnImport_<?= esc($item['id']) ?>" onclick="importFeedItem('<?= esc($item['id']) ?>', this)" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                            <i class="fa-solid fa-cloud-arrow-down"></i> <span>นำเข้าข่าว</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr id="emptyFeedRow">
                        <td colspan="6" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-satellite-dish fs-1 d-block mb-3 opacity-50 text-danger"></i>
                            <h6 class="fw-bold mb-1" style="color: var(--text-primary);">ยังไม่มีข้อมูลข่าวที่ดึงเข้ามาในระบบ</h6>
                            <p class="small text-secondary mb-3">คลิกปุ่ม <strong>"ซิงค์ข่าวสารทันที (Sync Now)"</strong> เพื่อดึงข่าวจาก สปชส. และสื่อมวลชนรอบล่าสุด</p>
                            <button type="button" onclick="syncAggregatedFeeds()" class="btn btn-sm btn-danger px-4 py-2 rounded-pill fw-bold">
                                <i class="fa-solid fa-rotate me-1"></i> เริ่มซิงค์เดี๋ยวนี้
                            </button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
                if (typeof App !== 'undefined' && App.toast) {
                    App.toast(data.message || 'ซิงค์ข่าวสารล่าสุดเรียบร้อยแล้ว', 'success');
                }
                setTimeout(() => location.reload(), 800);
            } else {
                if (typeof App !== 'undefined' && App.toast) {
                    App.toast('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถซิงค์ข่าวได้'), 'error');
                } else {
                    alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถซิงค์ข่าวได้'));
                }
                icon.classList.remove('fa-spin');
                txt.innerText = 'ซิงค์ข่าวสารทันที (Sync Now)';
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof App !== 'undefined' && App.toast) {
                App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
            } else {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
            }
            icon.classList.remove('fa-spin');
            txt.innerText = 'ซิงค์ข่าวสารทันที (Sync Now)';
            btn.disabled = false;
        });
}

function importFeedItem(feedId, btn) {
    if (!confirm('ต้องการนำเข้าข่าวนี้สู่ระบบคลังข่าวถาวรของเว็บไซต์ใช่หรือไม่? ข้อมูลจะถูกบันทึกลงในระบบข่าวประชาสัมพันธ์ของจังหวัด')) {
        return;
    }

    const origHtml = btn ? btn.innerHTML : '';
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
                btn.className = 'btn btn-sm btn-secondary rounded-pill px-3 py-1.5 fw-bold disabled';
                btn.style.opacity = '0.85';
                btn.style.cursor = 'not-allowed';
                btn.innerHTML = '<i class="fa-solid fa-check me-1 text-success"></i> นำเข้าแล้ว';
                btn.onclick = null;
                const row = btn.closest('.feed-row');
                if (row) row.dataset.status = 'imported';
            }
            if (typeof App !== 'undefined' && App.toast) {
                App.toast(data.message || 'นำเข้าข่าวสู่ระบบถาวรเรียบร้อยแล้ว', 'success');
            }
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
            if (typeof App !== 'undefined' && App.toast) {
                App.toast('ข้อผิดพลาด: ' + (data.message || 'ไม่สามารถนำเข้าข่าวได้'), 'error');
            } else {
                alert('ข้อผิดพลาด: ' + (data.message || 'ไม่สามารถนำเข้าข่าวได้'));
            }
        }
    })
    .catch(err => {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
        if (typeof App !== 'undefined' && App.toast) {
            App.toast('เกิดข้อผิดพลาดในการนำเข้าข่าว', 'error');
        } else {
            alert('เกิดข้อผิดพลาดในการนำเข้าข่าว');
        }
    });
}

function filterAggregatedFeeds() {
    const q = (document.getElementById('filterAggSearch')?.value || '').toLowerCase().trim();
    const sourceFilter = document.getElementById('filterAggSource')?.value || 'all';
    const statusFilter = document.getElementById('filterAggStatus')?.value || 'all';
    const rows = document.querySelectorAll('.feed-row');
    let visibleCount = 0;

    rows.forEach(r => {
        const title = r.dataset.title || '';
        const summary = r.dataset.summary || '';
        const source = r.dataset.source || '';
        const status = r.dataset.status || '';

        const matchQ = !q || title.includes(q) || summary.includes(q) || source.includes(q);
        const matchSource = (sourceFilter === 'all') || source.includes(sourceFilter.toLowerCase());
        const matchStatus = (statusFilter === 'all') || (status === statusFilter);

        if (matchQ && matchSource && matchStatus) {
            r.style.display = '';
            visibleCount++;
        } else {
            r.style.display = 'none';
        }
    });

    const counter = document.getElementById('filteredItemCount');
    if (counter) {
        counter.textContent = `แสดงผล ${visibleCount} รายการ`;
    }
}

function resetAggFilters() {
    const s = document.getElementById('filterAggSearch');
    const src = document.getElementById('filterAggSource');
    const st = document.getElementById('filterAggStatus');
    if (s) s.value = '';
    if (src) src.value = 'all';
    if (st) st.value = 'all';
    filterAggregatedFeeds();
}
</script>

<?= $this->endSection() ?>

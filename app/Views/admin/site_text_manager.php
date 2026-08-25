<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>ระบบแก้ไขข้อความและเนื้อหาทั่วทั้งเว็บไซต์ (Universal Site Text & Section CMS)</h4>
        <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
            แก้ไขข้อความ หัวข้อ สโลแกน ป้าย Badge และคำบรรยายทุกจุดในเว็บไซต์ได้ในที่เดียว <span class="badge bg-success ms-2"><i class="fa-solid fa-bolt me-1"></i>Live Sync Frontend</span>
        </p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-warning fw-bold px-3 py-2" onclick="resetAllTextsToDefault()" style="border-radius: 12px;">
            <i class="fa-solid fa-rotate-left me-1"></i> คืนค่าเริ่มต้นทั้งหมด
        </button>
        <button type="button" class="btn-modern px-4 py-2" onclick="submitSiteTextsForm()">
            <i class="fa-solid fa-floppy-disk me-2"></i> บันทึกข้อความทั้งหมด
        </button>
    </div>
</div>

<!-- Quick Live Search Filter -->
<div class="card border-0 mb-4 p-3 rounded-4 shadow-sm" style="background: var(--glass-bg); border: 1px solid var(--glass-border) !important;">
    <div class="d-flex align-items-center gap-3">
        <div class="p-2 rounded-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <input type="text" id="filterTextSearch" class="form-control modern-input border-0 flex-grow-1" 
               placeholder="พิมพ์ค้นหาข้อความที่ต้องการแก้ไข (เช่น อัญมณี, ผู้ว่า, ค้นหา, ข่าวสาร, สโลแกน)..." 
               oninput="filterTextCards(this.value)" style="font-size: 1rem;">
        <span class="badge bg-secondary px-3 py-2" id="textCountBadge" style="border-radius: 10px; font-size: 0.85rem;">
            <?= count($allTexts) ?> รายการข้อความ
        </span>
    </div>
</div>

<!-- Section Tabs -->
<ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3 overflow-auto flex-nowrap" id="textTabs" role="tablist" style="border-color: var(--glass-border) !important;">
    <li class="nav-item" role="presentation">
        <button class="tab-pill active" id="tab-all-btn" data-bs-toggle="pill" data-bs-target="#tab-cat-all" type="button" role="tab">
            <i class="fa-solid fa-list-check me-2"></i> แสดงทั้งหมด
        </button>
    </li>
    <?php foreach ($categories as $catKey => $cat): ?>
    <li class="nav-item" role="presentation">
        <button class="tab-pill text-nowrap" id="tab-<?= $catKey ?>-btn" data-bs-toggle="pill" data-bs-target="#tab-cat-<?= $catKey ?>" type="button" role="tab">
            <i class="<?= $cat['icon'] ?? 'fa-solid fa-folder' ?> me-2"></i> <?= $cat['name'] ?>
        </button>
    </li>
    <?php endforeach; ?>
</ul>

<!-- Form Container -->
<form id="siteTextsMainForm" action="<?= base_url('admin/site-texts/save') ?>" method="POST">
    <?= csrf_field() ?>

    <div class="tab-content" id="textTabsContent">
        <!-- ALL TAB -->
        <div class="tab-pane fade show active" id="tab-cat-all" role="tabpanel">
            <?php foreach ($categories as $catKey => $cat): ?>
                <div class="text-category-block mb-4" data-category="<?= $catKey ?>">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom" style="border-color: var(--glass-border) !important;">
                        <i class="<?= $cat['icon'] ?> fs-5"></i>
                        <h5 class="fw-bold mb-0 text-primary"><?= $cat['name'] ?></h5>
                    </div>

                    <div class="row g-3">
                        <?php foreach ($cat['keys'] as $keyName => $keyInfo): 
                            $label = is_array($keyInfo) ? ($keyInfo['label'] ?? $keyName) : $keyInfo;
                            $type = is_array($keyInfo) ? ($keyInfo['type'] ?? 'text') : 'text';
                            $currentVal = $allTexts[$keyName] ?? '';
                        ?>
                        <div class="col-lg-6 text-card-item" data-text-key="<?= esc($keyName) ?>" data-text-label="<?= esc($label) ?>" data-text-val="<?= esc($currentVal) ?>">
                            <div class="glass-card p-3 rounded-3 h-100 position-relative" style="background: var(--glass-bg); border: 1px solid var(--glass-border); transition: all 0.2s;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label fw-bold mb-0 text-primary" style="font-size: 0.92rem;">
                                        <i class="fa-solid fa-tag me-1 text-muted"></i> <?= esc($label) ?>
                                    </label>
                                    <span class="badge bg-dark text-info fw-mono px-2 py-0.5" style="font-size: 0.72rem; border: 1px solid rgba(255,255,255,0.1);">
                                        <?= esc($keyName) ?>
                                    </span>
                                </div>

                                <?php if ($type === 'textarea'): ?>
                                    <textarea name="texts[<?= esc($keyName) ?>]" rows="3" class="form-control modern-input" style="font-size: 0.95rem; resize: vertical;"><?= esc($currentVal) ?></textarea>
                                <?php else: ?>
                                    <input type="text" name="texts[<?= esc($keyName) ?>]" value="<?= esc($currentVal) ?>" class="form-control modern-input" style="font-size: 0.95rem;">
                                <?php endif; ?>

                                <div class="d-flex align-items-center justify-content-between mt-2 pt-1">
                                    <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-code me-1"></i> <?= 'site_text(\'' . $keyName . '\')' ?></small>
                                    <button type="button" class="btn btn-xs btn-outline-secondary" onclick="resetSingleText('<?= esc($keyName) ?>')" title="คืนค่าเริ่มต้นเฉพาะข้อความนี้">
                                        <i class="fa-solid fa-rotate-left"></i> รีเซ็ต
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- INDIVIDUAL CATEGORY TABS -->
        <?php foreach ($categories as $catKey => $cat): ?>
        <div class="tab-pane fade" id="tab-cat-<?= $catKey ?>" role="tabpanel">
            <div class="row g-3">
                <?php foreach ($cat['keys'] as $keyName => $keyInfo): 
                    $label = is_array($keyInfo) ? ($keyInfo['label'] ?? $keyName) : $keyInfo;
                    $type = is_array($keyInfo) ? ($keyInfo['type'] ?? 'text') : 'text';
                    $currentVal = $allTexts[$keyName] ?? '';
                ?>
                <div class="col-lg-6">
                    <div class="glass-card p-3 rounded-3 h-100" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-bold mb-0 text-primary" style="font-size: 0.92rem;">
                                <i class="fa-solid fa-tag me-1 text-muted"></i> <?= esc($label) ?>
                            </label>
                            <span class="badge bg-dark text-info fw-mono px-2 py-0.5" style="font-size: 0.72rem;">
                                <?= esc($keyName) ?>
                            </span>
                        </div>

                        <?php if ($type === 'textarea'): ?>
                            <textarea name="texts[<?= esc($keyName) ?>]" rows="3" class="form-control modern-input" style="font-size: 0.95rem;"><?= esc($currentVal) ?></textarea>
                        <?php else: ?>
                            <input type="text" name="texts[<?= esc($keyName) ?>]" value="<?= esc($currentVal) ?>" class="form-control modern-input" style="font-size: 0.95rem;">
                        <?php endif; ?>

                        <div class="d-flex align-items-center justify-content-between mt-2 pt-1">
                            <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-code me-1"></i> <?= 'site_text(\'' . $keyName . '\')' ?></small>
                            <button type="button" class="btn btn-xs btn-outline-secondary" onclick="resetSingleText('<?= esc($keyName) ?>')">
                                <i class="fa-solid fa-rotate-left"></i> รีเซ็ต
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</form>

<script>
function filterTextCards(query) {
    query = (query || '').toLowerCase().trim();
    const items = document.querySelectorAll('.text-card-item');
    let visibleCount = 0;

    items.forEach(el => {
        const key = el.getAttribute('data-text-key').toLowerCase();
        const label = el.getAttribute('data-text-label').toLowerCase();
        const val = (el.querySelector('input, textarea')?.value || '').toLowerCase();

        if (!query || key.includes(query) || label.includes(query) || val.includes(query)) {
            el.style.display = '';
            visibleCount++;
        } else {
            el.style.display = 'none';
        }
    });

    const badge = document.getElementById('textCountBadge');
    if (badge) {
        badge.innerText = `${visibleCount} รายการที่ตรงกับคำค้น`;
    }
}

function submitSiteTextsForm() {
    const form = document.getElementById('siteTextsMainForm');
    const formData = new FormData(form);

    if (typeof App !== 'undefined' && App.toast) App.toast('กำลังบันทึกข้อมูลข้อความ...', 'info');

    fetch("<?= base_url('admin/site-texts/save') ?>", {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: data.message || 'บันทึกข้อความทั้งหมดเรียบร้อยแล้ว',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else if (typeof App !== 'undefined' && App.toast) {
                App.toast(data.message, 'success');
            }
        } else {
            alert(data.message || 'เกิดข้อผิดพลาดในการบันทึก');
        }
    })
    .catch(err => {
        console.error(err);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
    });
}

function resetSingleText(key) {
    if (!confirm(`คุณต้องการคืนค่าเริ่มต้นสำหรับข้อความ "${key}" ใช่หรือไม่?`)) return;

    const fd = new FormData();
    fd.append('text_key', key);
    fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch("<?= base_url('admin/site-texts/reset') ?>", {
        method: "POST",
        body: fd,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        }
    });
}

function resetAllTextsToDefault() {
    if (!confirm('คำเตือน: คุณต้องการคืนค่าข้อความทั้งหมดบนเว็บไซต์สู่ค่าเริ่มต้นจากโรงงานใช่หรือไม่?')) return;

    const fd = new FormData();
    fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch("<?= base_url('admin/site-texts/reset') ?>", {
        method: "POST",
        body: fd,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        }
    });
}
</script>

<?= $this->endSection() ?>

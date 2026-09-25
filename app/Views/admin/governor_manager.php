<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$governors = $governors ?? [];
?>

<!-- Hero Cockpit Header -->
<div class="glass-card mb-4 p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 28px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(217, 119, 6, 0.12) 50%, rgba(5, 150, 105, 0.08) 100%); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="position-absolute" style="top: -40px; right: -30px; font-size: 15rem; color: rgba(217, 119, 6, 0.04); pointer-events: none; z-index: 0;">
        <i class="fa-solid fa-crown"></i>
    </div>
    
    <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(217, 119, 6, 0.15); border: 1px solid rgba(217, 119, 6, 0.3); color: #d97706; font-size: 0.85rem; font-weight: 600;">
                <i class="fa-solid fa-crown"></i> Hall of Governors & Historical Hall of Fame
            </div>
            <h2 class="fw-bold mb-2" style="color: var(--text-primary); letter-spacing: -0.5px;">
                ทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง (Hall of Governors)
            </h2>
            <p class="text-secondary mb-0" style="font-size: 1rem; max-width: 760px;">
                บันทึกประวัติศาสตร์ผู้นำเมืองพัทลุง ตั้งแต่ยุคกรุงธนบุรี พระยาพัทลุง (ขุนคางเหล็ก) เจ้าเมืองพัทลุงท่านแรก สืบต่อถึงผู้ว่าราชการจังหวัดคนปัจจุบัน พร้อมระบบบันทึกประวัติและผลงานสำคัญ
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('governors') ?>" target="_blank" class="btn btn-outline-secondary px-3 py-2 rounded-4 fw-medium d-flex align-items-center gap-2 shadow-sm transition-all" style="border-width: 1.5px; background: var(--bg-surface);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> <span>ดูหน้าหอเกียรติยศ</span>
            </a>
            <button type="button" onclick="openGovModal()" class="btn btn-success px-4 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow transition-all" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none; font-size: 1rem;">
                <i class="fa-solid fa-plus-circle"></i> <span>เพิ่มรายนามผู้ว่าฯ ใหม่</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row inside Cockpit -->
    <div class="position-relative z-1 row g-3 mt-3 pt-3 border-top" style="border-color: var(--glass-border) !important;">
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-users text-emerald me-1"></i> จำนวนทำเนียบทั้งหมด</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold" style="color: var(--text-primary);"><?= count($governors) ?></span>
                    <span class="small text-secondary">ท่าน</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-landmark text-warning me-1"></i> เจ้าเมืองพัทลุงคนแรก</div>
                <div class="fs-6 fw-bold text-truncate" style="color: var(--text-primary);" title="พระยาพัทลุง (ขุนคางเหล็ก)">
                    พระยาพัทลุง (ขุนคางเหล็ก)
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-user-check text-success me-1"></i> ผู้ว่าฯ คนปัจจุบัน</div>
                <?php 
                    $currGov = current(array_filter($governors, fn($g) => !empty($g['is_current']))) ?: end($governors);
                ?>
                <div class="fs-6 fw-bold text-truncate text-success" title="<?= esc($currGov['name'] ?? '-') ?>">
                    <?= esc($currGov['name'] ?? '-') ?>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-clock-rotate-left text-info me-1"></i> ยุคสมัยทางประวัติศาสตร์</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-info"><?= count(array_unique(array_column($governors, 'era'))) ?></span>
                    <span class="small text-secondary">ยุคสมัย</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="glass-card rounded-4 overflow-hidden shadow-sm" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
    <div class="p-4 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--glass-border) !important;">
        <div class="position-relative flex-grow-1" style="max-width: 420px;">
            <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y text-secondary ms-3"></i>
            <input type="text" class="form-control custom-input rounded-pill ps-5" placeholder="ค้นหารายชื่อ, คนที่, พ.ศ., หรือยุคสมัย..." oninput="filterAdminGovs(this.value)">
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1.5">
                รายการทั้งหมด <strong id="adminGovCount" class="text-emerald"><?= count($governors) ?></strong> ท่าน
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="small text-secondary" style="background: rgba(0,0,0,0.02);">
                <tr>
                    <th class="py-3 px-4 text-center table-nowrap" style="width: 100px;">ลำดับที่</th>
                    <th class="py-3 table-nowrap" style="width: 80px;">รูปถ่าย</th>
                    <th class="py-3" style="min-width: 240px;">ชื่อ - บรรดาศักดิ์</th>
                    <th class="py-3 table-nowrap">ช่วงเวลาดำรงตำแหน่ง</th>
                    <th class="py-3 table-nowrap">ยุคสมัย</th>
                    <th class="py-3 table-nowrap">สถานะ</th>
                    <th class="py-3 text-center pe-4 table-nowrap" style="width: 140px;">การจัดการ</th>
                </tr>
            </thead>
            <tbody id="adminGovTableBody">
                <?php if (empty($governors)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="fa-solid fa-crown fs-1 d-block mb-3 opacity-50 text-warning"></i>
                            <h6 class="fw-bold mb-1" style="color: var(--text-primary);">ยังไม่มีข้อมูลรายนามในทำเนียบผู้ว่าราชการจังหวัด</h6>
                            <p class="small text-secondary mb-3">กดปุ่ม <strong>"+ เพิ่มรายนามผู้ว่าฯ ใหม่"</strong> ด้านบนเพื่อเริ่มบันทึกทำเนียบ</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($governors as $g): 
                        $seq = (int)($g['sequence'] ?? 1);
                        $name = esc($g['name'] ?? '');
                        $period = esc($g['period'] ?? '');
                        $era = esc($g['era'] ?? 'ยุคปัจจุบัน');
                        $isCurr = !empty($g['is_current']);
                        $img = !empty($g['image']) ? (strpos($g['image'], 'http') === 0 ? $g['image'] : base_url($g['image'])) : '';
                        $govId = esc($g['id'] ?? '');
                    ?>
                        <tr class="admin-gov-row" data-search="<?= mb_strtolower($name . ' ' . $period . ' ' . $seq . ' ' . $era) ?>">
                            <td class="text-center py-3 px-4 table-nowrap">
                                <span class="badge <?= $isCurr ? 'bg-warning text-dark' : 'bg-emerald-subtle text-emerald border border-emerald-subtle' ?> rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.85rem;">
                                    คนที่ <?= $seq ?>
                                </span>
                            </td>
                            <td>
                                <div class="rounded-3 overflow-hidden shadow-sm flex-shrink-0" style="width: 52px; height: 64px; background: #064e3b; border: 1.5px solid var(--glass-border);">
                                    <?php if (!empty($img)): ?>
                                        <img src="<?= $img ?>" alt="<?= $name ?>" class="w-100 h-100 object-fit-cover" style="object-position: top center;" onerror="this.src='https://via.placeholder.com/100x120?text=No+Img'">
                                    <?php else: ?>
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-warning" style="background: linear-gradient(135deg, #022c22, #064e3b);">
                                            <i class="fa-solid fa-user-tie fs-5"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold" style="color: var(--text-primary); font-size: 1rem;"><?= $name ?></div>
                                <div class="small fw-semibold mt-0.5" style="color: #059669;"><?= esc($g['title_honor'] ?? 'ผู้ว่าราชการจังหวัดพัทลุง') ?></div>
                            </td>
                            <td class="table-nowrap">
                                <span class="badge bg-light text-secondary border px-2.5 py-1.5 fw-medium" style="border-color: var(--glass-border) !important;">
                                    <i class="fa-regular fa-clock text-emerald me-1"></i> <?= $period ?>
                                </span>
                            </td>
                            <td class="table-nowrap">
                                <span class="badge bg-emerald-subtle text-emerald rounded-pill px-3 py-1 fw-medium" style="font-size: 0.8rem;">
                                    <?= $era ?>
                                </span>
                            </td>
                            <td class="table-nowrap">
                                <?php if ($isCurr): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold shadow-sm">
                                        <i class="fa-solid fa-circle-check me-1"></i> ท่านปัจจุบัน
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1">อดีตผู้ว่าฯ</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4 table-nowrap">
                                <div class="d-flex justify-content-center align-items-center gap-1.5">
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1" onclick="editGovModal('<?= $govId ?>')" title="แก้ไขรายละเอียด">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> แก้ไข
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" onclick="deleteGovModal('<?= $govId ?>', '<?= addslashes($name) ?>')" title="ลบรายนามนี้">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: ADD / EDIT GOVERNOR -->
<!-- ======================================================== -->
<div class="modal fade" id="adminGovModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden" style="background: var(--bg-surface); border: 1px solid var(--glass-border) !important;">
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #022c22, #064e3b) !important;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-warning" style="width: 38px; height: 38px; background: rgba(255,255,255,0.15);">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="adminGovModalTitle">จัดการทำเนียบผู้ว่าราชการจังหวัด</h5>
                        <small style="color: #a7f3d0; font-size: 0.85rem;">บันทึกข้อมูลประวัติและรูปถ่ายเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: var(--bg-surface);">
                <form id="adminGovForm" onsubmit="event.preventDefault(); saveAdminGov();" enctype="multipart/form-data">
                    <input type="hidden" id="adminGovId" name="id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">ลำดับที่ (คนที่) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent" style="border-color: var(--glass-border); color: var(--text-secondary);">คนที่</span>
                                <input type="number" class="form-control custom-input" id="adminGovSequence" name="sequence" min="1" required placeholder="เช่น 1">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">ชื่อ - นามสกุล / บรรดาศักดิ์ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control custom-input" id="adminGovName" name="name" required placeholder="เช่น พระยาพัทลุง (ขุนคางเหล็ก)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">ช่วงเวลาดำรงตำแหน่ง (พ.ศ.) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control custom-input" id="adminGovPeriod" name="period" required placeholder="เช่น พ.ศ. 2315 - 2332 หรือ 1 ต.ค. 2566 - ปัจจุบัน">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">ตำแหน่ง / บรรดาศักดิ์กำกับ</label>
                            <input type="text" class="form-control custom-input" id="adminGovTitleHonor" name="title_honor" placeholder="เช่น เจ้าเมืองพัทลุง หรือ ผู้ว่าราชการจังหวัดพัทลุง">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">ยุคสมัยทางประวัติศาสตร์</label>
                            <select class="form-select custom-input" id="adminGovEra" name="era">
                                <option value="ยุคกรุงธนบุรีและต้นรัตนโกสินทร์">ยุคกรุงธนบุรีและต้นรัตนโกสินทร์</option>
                                <option value="ยุคต้นรัตนโกสินทร์">ยุคต้นรัตนโกสินทร์</option>
                                <option value="ยุครัตนโกสินทร์ตอนกลาง">ยุครัตนโกสินทร์ตอนกลาง</option>
                                <option value="ยุคมณฑลเทศาภิบาล (รัชกาลที่ 5)">ยุคมณฑลเทศาภิบาล (รัชกาลที่ 5)</option>
                                <option value="ยุคหลังเปลี่ยนแปลงการปกครอง พ.ศ. 2475">ยุคหลังเปลี่ยนแปลงการปกครอง พ.ศ. 2475</option>
                                <option value="ยุคปัจจุบัน">ยุคปัจจุบัน</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-3">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input" type="checkbox" id="adminGovIsCurrent" name="is_current" value="1">
                                <label class="form-check-label fw-bold" for="adminGovIsCurrent" style="color: var(--text-primary);">ผู้ว่าราชการจังหวัดคนปัจจุบัน</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">อัปโหลดรูปภาพประจำตัว (Portrait Photo)</label>
                            <input type="file" class="form-control custom-input" id="adminGovImageFile" name="image_file" accept="image/*" onchange="previewAdminGovImg(this)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">หรือระบุเป็น URL รูปภาพ</label>
                            <input type="text" class="form-control custom-input" id="adminGovImageUrl" name="image_url" placeholder="https://..." oninput="document.getElementById('adminGovImgPreview').src = this.value">
                        </div>
                    </div>

                    <div id="adminGovPreviewBox" class="mb-3 p-3 rounded-4 d-flex align-items-center gap-3" style="background: var(--bg-secondary); border: 1px solid var(--glass-border);">
                        <div class="rounded-3 overflow-hidden shadow-sm flex-shrink-0" style="width: 70px; height: 88px; background: #064e3b; border: 1.5px solid var(--glass-border);">
                            <img id="adminGovImgPreview" src="" alt="Preview" class="w-100 h-100 object-fit-cover" style="object-position: top center;" onerror="this.src='https://via.placeholder.com/150x180?text=No+Photo'">
                        </div>
                        <div>
                            <span class="small fw-bold d-block mb-1" style="color: var(--text-primary);"><i class="fa-solid fa-image text-emerald me-1"></i> ตัวอย่างภาพบุคคล</span>
                            <span class="text-secondary small">ระบบรองรับไฟล์ JPG, PNG, WebP โดยจะแสดงผลแบบรักษาอัตราส่วนที่เหมาะสม</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold" style="color: var(--text-primary);">ประวัติย่อ / บทบาทและผลงานสำคัญ</label>
                        <textarea class="form-control custom-input" id="adminGovAchievement" name="achievement" rows="3" placeholder="ระบุประวัติย่อหรือบทบาทสำคัญในการพัฒนาจังหวัดพัทลุง..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top px-4 py-3" style="border-color: var(--glass-border) !important; background: var(--bg-secondary);">
                <button type="button" class="btn btn-outline-secondary px-3.5 rounded-3 fw-medium" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-success px-4 rounded-3 fw-bold shadow-sm" id="btnSaveAdminGov" onclick="saveAdminGov()" style="background: linear-gradient(135deg, #059669, #047857); border: none;">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let adminGovModal;

function openGovModal() {
    if (!adminGovModal) adminGovModal = new bootstrap.Modal(document.getElementById('adminGovModal'));
    document.getElementById('adminGovForm').reset();
    document.getElementById('adminGovId').value = '';
    document.getElementById('adminGovImgPreview').src = '';
    document.getElementById('adminGovModalTitle').innerHTML = 'เพิ่มรายนามผู้ว่าราชการจังหวัด';
    adminGovModal.show();
}

function previewAdminGovImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('adminGovImgPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

async function editGovModal(id) {
    if (!adminGovModal) adminGovModal = new bootstrap.Modal(document.getElementById('adminGovModal'));
    try {
        const res = await App.fetch(`<?= base_url('admin/governors/get-item') ?>/${id}`);
        if (res && res.status === 'success') {
            const g = res.data;
            document.getElementById('adminGovId').value = g.id || '';
            document.getElementById('adminGovSequence').value = g.sequence || 1;
            document.getElementById('adminGovName').value = g.name || '';
            document.getElementById('adminGovPeriod').value = g.period || '';
            document.getElementById('adminGovTitleHonor').value = g.title_honor || '';
            document.getElementById('adminGovEra').value = g.era || 'ยุคปัจจุบัน';
            document.getElementById('adminGovImageUrl').value = g.image || '';
            document.getElementById('adminGovAchievement').value = g.achievement || '';
            document.getElementById('adminGovIsCurrent').checked = !!g.is_current;

            if (g.image) {
                const imgUrl = (g.image.startsWith('http')) ? g.image : '<?= base_url() ?>/' + g.image;
                document.getElementById('adminGovImgPreview').src = imgUrl;
            } else {
                document.getElementById('adminGovImgPreview').src = '';
            }

            document.getElementById('adminGovModalTitle').innerHTML = `แก้ไข: คนที่ ${g.sequence} ${g.name}`;
            adminGovModal.show();
        } else {
            App.toast(res ? res.message : 'ไม่พบข้อมูลผู้ว่าราชการจังหวัด', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
    }
}

async function saveAdminGov() {
    const form = document.getElementById('adminGovForm');
    const name = document.getElementById('adminGovName').value.trim();
    const period = document.getElementById('adminGovPeriod').value.trim();
    const seq = document.getElementById('adminGovSequence').value.trim();

    if (!name || !period || !seq) {
        App.toast('กรุณากรอกลำดับที่ ชื่อ และช่วงเวลาดำรงตำแหน่งให้ครบถ้วน', 'warning');
        return;
    }

    const formData = new FormData(form);
    const btn = document.getElementById('btnSaveAdminGov');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    try {
        const res = await App.fetch('<?= base_url("admin/governors/save-item") ?>', {
            method: 'POST',
            body: formData
        });

        if (res && res.status === 'success') {
            App.toast('บันทึกข้อมูลทำเนียบเรียบร้อยแล้ว', 'success');
            adminGovModal.hide();
            setTimeout(() => location.reload(), 700);
        } else {
            App.toast(res ? res.message : 'เกิดข้อผิดพลาดในการบันทึก', 'error');
        }
    } catch (err) {
        App.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-1"></i> บันทึกข้อมูล';
    }
}

async function deleteGovModal(id, name) {
    if (!confirm(`คุณแน่ใจหรือไม่ที่จะลบรายนาม "${name}" ออกจากทำเนียบ?`)) return;

    try {
        const res = await App.fetch(`<?= base_url('admin/governors/delete-item') ?>/${id}`, {
            method: 'POST'
        });
        if (res && res.status === 'success') {
            App.toast('ลบข้อมูลเรียบร้อยแล้ว', 'success');
            setTimeout(() => location.reload(), 700);
        } else {
            App.toast(res ? res.message : 'เกิดข้อผิดพลาดในการลบ', 'error');
        }
    } catch (err) {
        App.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
    }
}

function filterAdminGovs(val) {
    const q = (val || '').toLowerCase().trim();
    const rows = document.querySelectorAll('.admin-gov-row');
    let matched = 0;

    rows.forEach(r => {
        const s = r.getAttribute('data-search') || '';
        if (!q || s.includes(q)) {
            r.style.display = '';
            matched++;
        } else {
            r.style.display = 'none';
        }
    });

    const cnt = document.getElementById('adminGovCount');
    if (cnt) cnt.innerText = matched;
}
</script>
<?= $this->endSection() ?>

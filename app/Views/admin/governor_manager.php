<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$governors = $governors ?? [];
?>
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill fw-bold">
                    <i class="fa-solid fa-crown me-1 text-warning"></i> Roster of Governors
                </span>
                <span class="text-muted small">ระบบบริหารจัดการทำเนียบผู้ว่าราชการจังหวัดพัทลุง</span>
            </div>
            <h3 class="fw-bold mb-0 text-dark">ทำเนียบเจ้าเมืองและผู้ว่าราชการจังหวัดพัทลุง</h3>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('governors') ?>" target="_blank" class="btn btn-outline-success rounded-pill px-3 py-2 fw-bold d-flex align-items-center gap-2">
                <i class="fa-solid fa-external-link-alt"></i> ดูหน้าสาธารณะ
            </a>
            <button type="button" onclick="openGovModal()" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #059669, #047857); border: none;">
                <i class="fa-solid fa-plus-circle"></i> + เพิ่มรายนามผู้ว่าฯ ใหม่
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">จำนวนทำเนียบทั้งหมด</span>
                        <h3 class="fw-bold mb-0 text-dark"><?= count($governors) ?> <small class="fs-6 text-muted">ท่าน</small></h3>
                    </div>
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-users fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">ผู้ว่าราชการจังหวัดคนแรก</span>
                        <h6 class="fw-bold mb-0 text-dark">พระยาพัทลุง (ขุนคางเหล็ก)</h6>
                    </div>
                    <div class="rounded-circle p-3 bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-crown fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">ผู้ว่าฯ คนปัจจุบัน</span>
                        <h6 class="fw-bold mb-0 text-success">
                            <?php 
                                $currGov = current(array_filter($governors, fn($g) => !empty($g['is_current']))) ?: end($governors);
                                echo esc($currGov['name'] ?? '-');
                            ?>
                        </h6>
                    </div>
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-user-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block">ยุคสมัยทางประวัติศาสตร์</span>
                        <h3 class="fw-bold mb-0 text-dark"><?= count(array_unique(array_column($governors, 'era'))) ?> <small class="fs-6 text-muted">ยุค</small></h3>
                    </div>
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-landmark fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
        <div class="card-header bg-white border-bottom p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="position-relative flex-grow-1" style="max-width: 400px;">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y text-muted ms-3"></i>
                <input type="text" class="form-control rounded-pill ps-5 border-light-subtle" placeholder="ค้นหารายชื่อ, ลำดับที่, พ.ศ. ..." oninput="filterAdminGovs(this.value)">
            </div>
            <div class="text-muted small">
                รายการทั้งหมด <strong id="adminGovCount" class="text-success"><?= count($governors) ?></strong> ท่าน
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: linear-gradient(135deg, #022c22, #064e3b) !important; color: #ffffff;">
                    <tr>
                        <th class="py-3 px-4 text-center" style="width: 90px;">ลำดับที่</th>
                        <th class="py-3" style="width: 80px;">รูปถ่าย</th>
                        <th class="py-3">ชื่อ - บรรดาศักดิ์</th>
                        <th class="py-3">ช่วงเวลาดำรงตำแหน่ง</th>
                        <th class="py-3">ยุคสมัย</th>
                        <th class="py-3">สถานะ</th>
                        <th class="py-3 text-center" style="width: 140px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="adminGovTableBody">
                    <?php if (empty($governors)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                ยังไม่มีข้อมูลรายนามในทำเนียบผู้ว่าราชการจังหวัด
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
                                <td class="text-center py-3">
                                    <span class="badge <?= $isCurr ? 'bg-warning text-dark' : 'bg-success' ?> rounded-pill px-3 py-1.5 fw-bold">
                                        คนที่ <?= $seq ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="rounded-3 overflow-hidden bg-dark shadow-sm" style="width: 50px; height: 60px;">
                                        <?php if (!empty($img)): ?>
                                            <img src="<?= $img ?>" alt="<?= $name ?>" class="w-100 h-100" style="object-fit: cover; object-position: top center;" onerror="this.src='https://via.placeholder.com/100x120?text=No+Img'">
                                        <?php else: ?>
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-warning" style="background: #064e3b;">
                                                <i class="fa-solid fa-user-tie"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-6"><?= $name ?></div>
                                    <div class="small fw-semibold" style="color: #047857;"><?= esc($g['title_honor'] ?? 'ผู้ว่าราชการจังหวัดพัทลุง') ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-dark border border-success border-opacity-25 px-2.5 py-1.5 fw-bold">
                                        <i class="fa-regular fa-clock text-success me-1"></i> <?= $period ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1">
                                        <?= $era ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($isCurr): ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fw-bold">ท่านปัจจุบัน</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1">อดีตผู้ว่าฯ</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-circle me-1" style="width: 32px; height: 32px; padding: 0;" onclick="editGovModal('<?= $govId ?>')" title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 32px; height: 32px; padding: 0;" onclick="deleteGovModal('<?= $govId ?>', '<?= addslashes($name) ?>')" title="ลบ">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: ADD / EDIT GOVERNOR -->
<!-- ======================================================== -->
<div class="modal fade" id="adminGovModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header py-3 px-4 text-white" style="background: linear-gradient(135deg, #022c22, #064e3b) !important;">
                <h5 class="modal-title fw-bold" id="adminGovModalTitle">
                    <i class="fa-solid fa-crown text-warning me-2"></i> จัดการทำเนียบผู้ว่าราชการจังหวัด
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="adminGovForm" onsubmit="event.preventDefault(); saveAdminGov();" enctype="multipart/form-data">
                    <input type="hidden" id="adminGovId" name="id">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ลำดับที่ (คนที่) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">คนที่</span>
                                <input type="number" class="form-control" id="adminGovSequence" name="sequence" min="1" required placeholder="เช่น 1">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">ชื่อ - นามสกุล / บรรดาศักดิ์ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="adminGovName" name="name" required placeholder="เช่น พระยาพัทลุง (ขุนคางเหล็ก)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ช่วงเวลาดำรงตำแหน่ง (พ.ศ.) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="adminGovPeriod" name="period" required placeholder="เช่น พ.ศ. 2315 - 2332 หรือ 1 ต.ค. 2566 - ปัจจุบัน">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ตำแหน่ง / บรรดาศักดิ์กำกับ</label>
                            <input type="text" class="form-control" id="adminGovTitleHonor" name="title_honor" placeholder="เช่น เจ้าเมืองพัทลุง หรือ ผู้ว่าราชการจังหวัดพัทลุง">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ยุคสมัยทางประวัติศาสตร์</label>
                            <select class="form-select" id="adminGovEra" name="era">
                                <option value="ยุคกรุงธนบุรีและต้นรัตนโกสินทร์">ยุคกรุงธนบุรีและต้นรัตนโกสินทร์</option>
                                <option value="ยุคต้นรัตนโกสินทร์">ยุคต้นรัตนโกสินทร์</option>
                                <option value="ยุครัตนโกสินทร์ตอนกลาง">ยุครัตนโกสินทร์ตอนกลาง</option>
                                <option value="ยุคมณฑลเทศาภิบาล (รัชกาลที่ 5)">ยุคมณฑลเทศาภิบาล (รัชกาลที่ 5)</option>
                                <option value="ยุคหลังเปลี่ยนแปลงการปกครอง พ.ศ. 2475">ยุคหลังเปลี่ยนแปลงการปกครอง พ.ศ. 2475</option>
                                <option value="ยุคปัจจุบัน">ยุคปัจจุบัน</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-4">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input" type="checkbox" id="adminGovIsCurrent" name="is_current" value="1">
                                <label class="form-check-label fw-bold text-dark" for="adminGovIsCurrent">ผู้ว่าราชการจังหวัดคนปัจจุบัน</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">อัปโหลดรูปภาพประจำตัว (Portrait Photo)</label>
                            <input type="file" class="form-control" id="adminGovImageFile" name="image_file" accept="image/*" onchange="previewAdminGovImg(this)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">หรือระบุเป็น URL รูปภาพ</label>
                            <input type="text" class="form-control" id="adminGovImageUrl" name="image_url" placeholder="https://..." oninput="document.getElementById('adminGovImgPreview').src = this.value">
                        </div>
                    </div>

                    <div id="adminGovPreviewBox" class="mb-3 p-3 rounded-3 border bg-light d-flex align-items-center gap-3">
                        <div class="rounded-3 overflow-hidden shadow-sm flex-shrink-0" style="width: 70px; height: 85px; background: #064e3b;">
                            <img id="adminGovImgPreview" src="" alt="Preview" class="w-100 h-100" style="object-fit: cover; object-position: top center;" onerror="this.src='https://via.placeholder.com/150x180?text=No+Photo'">
                        </div>
                        <div>
                            <span class="small fw-bold text-secondary d-block mb-1">ตัวอย่างภาพที่จะแสดงบนทำเนียบ</span>
                            <span class="text-muted small">ระบบรองรับไฟล์ JPG, PNG, WebP และคำนวณอัตราส่วนภาพบุคคลให้สวยงาม</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">ประวัติย่อ / บทบาทและผลงานสำคัญ</label>
                        <textarea class="form-control" id="adminGovAchievement" name="achievement" rows="3" placeholder="ระบุประวัติย่อหรือบทบาทสำคัญในการพัฒนาจังหวัดพัทลุง..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-success px-4 fw-bold" id="btnSaveAdminGov" onclick="saveAdminGov()" style="background: linear-gradient(135deg, #059669, #047857); border: none;">
                    <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
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
    document.getElementById('adminGovModalTitle').innerHTML = '<i class="fa-solid fa-plus-circle text-warning me-2"></i> เพิ่มรายนามผู้ว่าราชการจังหวัด';
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

            document.getElementById('adminGovModalTitle').innerHTML = `<i class="fa-solid fa-pen-to-square text-warning me-2"></i> แก้ไข: คนที่ ${g.sequence} ${g.name}`;
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
            setTimeout(() => location.reload(), 800);
        } else {
            App.toast(res ? res.message : 'เกิดข้อผิดพลาดในการบันทึก', 'error');
        }
    } catch (err) {
        App.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล';
    }
}

async function deleteGovModal(id, name) {
    const confirmed = await App.confirm(`ต้องการลบรายนาม "${name}" ออกจากทำเนียบหรือไม่?`);
    if (!confirmed) return;

    try {
        const res = await App.fetch(`<?= base_url('admin/governors/delete-item') ?>/${id}`, {
            method: 'POST'
        });
        if (res && res.status === 'success') {
            App.toast('ลบข้อมูลเรียบร้อยแล้ว', 'success');
            setTimeout(() => location.reload(), 800);
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

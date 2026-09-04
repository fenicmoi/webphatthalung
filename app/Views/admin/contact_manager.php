<?php
$stats      = $stats ?? ['total' => 0, 'pending' => 0, 'in_progress' => 0, 'resolved' => 0];
$categories = $categories ?? \App\Models\CitizenContactModel::getCategories();
$statuses   = $statuses ?? \App\Models\CitizenContactModel::getStatuses();
$districts  = $districts ?? \App\Models\CitizenContactModel::getDistricts();
$search     = $search ?? '';
$currStatus = $currStatus ?? '';
$currCat    = $currCat ?? '';
$items      = $items ?? [];
$pager      = $pager ?? null;
?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- 1. Header & Quick Statistics Cards -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-envelope-open-text text-primary me-2"></i>จัดการเรื่องติดต่อและข้อร้องเรียนประชาชน</h4>
            <p class="text-muted small mb-0">ศูนย์บริการประชาชนและรับเรื่องร้องทุกข์ออนไลน์ ศาลากลางจังหวัดพัทลุง</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('contact') ?>" target="_blank" class="btn btn-outline-primary rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>ดูหน้าเว็บประชาชน
            </a>
        </div>
    </div>

    <!-- Stat Counter Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold">เรื่องทั้งหมด</small>
                        <h3 class="fw-bold text-dark mb-0 mt-1"><?= number_format($stats['total']) ?></h3>
                    </div>
                    <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-folder-open fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold">รอดำเนินการ (Pending)</small>
                        <h3 class="fw-bold text-warning mb-0 mt-1"><?= number_format($stats['pending']) ?></h3>
                    </div>
                    <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-clock fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold">กำลังประสานงาน</small>
                        <h3 class="fw-bold text-info mb-0 mt-1"><?= number_format($stats['in_progress']) ?></h3>
                    </div>
                    <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-spinner fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold">ดำเนินการเรียบร้อย</small>
                        <h3 class="fw-bold text-success mb-0 mt-1"><?= number_format($stats['resolved']) ?></h3>
                    </div>
                    <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Filters & Search Bar -->
    <div class="card border-0 rounded-4 shadow-xs p-3 mb-4 bg-white">
        <form method="GET" action="<?= base_url('admin/contacts') ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="q" value="<?= esc($search) ?>" class="form-control border-start-0 custom-input" placeholder="ค้นหา รหัสติดตาม, ชื่อผู้ร้อง, เบอร์โทร, หัวข้อ...">
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <select name="category" class="form-select custom-input">
                    <option value="">-- ทุกประเภทเรื่อง --</option>
                    <?php foreach ($categories as $k => $c): ?>
                        <option value="<?= esc($k) ?>" <?= $currCat === $k ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <select name="status" class="form-select custom-input">
                    <option value="">-- ทุกสถานะ --</option>
                    <?php foreach ($statuses as $sk => $s): ?>
                        <option value="<?= esc($sk) ?>" <?= $currStatus === $sk ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">กรองข้อมูล</button>
                <?php if (!empty($search) || !empty($currStatus) || !empty($currCat)): ?>
                    <a href="<?= base_url('admin/contacts') ?>" class="btn btn-outline-secondary rounded-pill px-3" title="ล้างตัวกรอง"><i class="fa-solid fa-rotate-left"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- 3. Records Table -->
    <div class="card border-0 rounded-4 shadow-xs bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted text-uppercase">
                        <th class="ps-4">รหัสติดตาม</th>
                        <th>ประเภทเรื่อง</th>
                        <th>หัวข้อเรื่อง / รายละเอียด</th>
                        <th>ผู้ติดต่อ</th>
                        <th>พื้นที่</th>
                        <th>สถานะ</th>
                        <th>วันที่ยื่น</th>
                        <th class="text-end pe-4">การจัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-1 mb-2 d-block opacity-25"></i>
                                ไม่พบรายการเรื่องติดต่อหรือข้อร้องเรียน
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $row): 
                            $st = $statuses[$row['status']] ?? ['name' => $row['status'], 'badge' => 'bg-secondary text-white'];
                            $cat = $categories[$row['category']] ?? ['name' => $row['category'], 'color' => 'primary'];
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-primary border fw-bold font-monospace px-2 py-1">
                                        <?= esc($row['tracking_code']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= esc($cat['color']) ?> bg-opacity-10 text-<?= esc($cat['color']) ?> border border-<?= esc($cat['color']) ?> border-opacity-25 rounded-pill px-2.5 py-1 small">
                                        <?= esc($cat['name']) ?>
                                    </span>
                                </td>
                                <td style="max-width: 280px;">
                                    <div class="fw-bold text-dark text-truncate"><?= esc($row['subject']) ?></div>
                                    <small class="text-muted text-truncate d-block"><?= esc(mb_substr($row['message'], 0, 70)) ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($row['full_name']) ?></div>
                                    <small class="text-muted"><i class="fa-solid fa-phone me-1"></i><?= esc($row['phone']) ?></small>
                                </td>
                                <td>
                                    <span class="small text-muted">อ.<?= esc($row['district'] ?? 'พัทลุง') ?></span>
                                </td>
                                <td>
                                    <span class="badge <?= esc($st['badge']) ?> rounded-pill px-2.5 py-1 small">
                                        <?= esc($st['name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary rounded-pill px-3" onclick="openDetailModal(<?= $row['id'] ?>)">
                                            <i class="fa-solid fa-eye me-1"></i>ดูรายละเอียด
                                        </button>
                                        <button type="button" class="btn btn-outline-danger ms-1 rounded-circle" onclick="deleteContact(<?= $row['id'] ?>)" title="ลบรายการ">
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
        
        <?php if (!empty($pager)): ?>
            <div class="p-3 border-top d-flex justify-content-end">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal: View Detail & Update Status -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white p-3 px-4">
                <div>
                    <h5 class="modal-title fw-bold" id="detailModalLabel">รายละเอียดคำร้อง & จัดการสถานะ</h5>
                    <small class="text-white-50" id="detailTrackingBadge"></small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="updateStatusForm" onsubmit="handleUpdateStatus(event)">
                <input type="hidden" id="modalContactId" name="contact_id">
                <div class="modal-body p-4">
                    <div id="modalLoadingSpinner" class="text-center py-5">
                        <i class="fa-solid fa-spinner fa-spin fs-2 text-primary"></i>
                        <p class="text-muted mt-2">กำลังโหลดข้อมูล...</p>
                    </div>

                    <div id="modalDetailContent" class="d-none">
                        <!-- Requester Info Box -->
                        <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                            <div class="row g-2 small">
                                <div class="col-sm-6">
                                    <span class="text-muted">ผู้ติดต่อ/ผู้ร้อง:</span>
                                    <strong id="detFullName" class="text-dark d-block"></strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted">เบอร์โทรศัพท์:</span>
                                    <strong id="detPhone" class="text-primary d-block"></strong>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted">อีเมล:</span>
                                    <span id="detEmail" class="text-dark d-block"></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted">พื้นที่เกี่ยวข้อง:</span>
                                    <span id="detDistrict" class="text-dark d-block"></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted">ประเภทเรื่อง:</span>
                                    <span id="detCategory" class="badge bg-primary rounded-pill"></span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted">วันเวลาที่ยื่นเรื่อง:</span>
                                    <span id="detCreatedAt" class="text-muted d-block"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Subject & Message -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small mb-1">หัวข้อเรื่อง</label>
                            <h5 id="detSubject" class="fw-bold text-dark"></h5>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small mb-1">รายละเอียดข้อความ / ข้อร้องเรียน</label>
                            <div id="detMessage" class="p-3 rounded-3 bg-light border text-dark small" style="white-space: pre-wrap; line-height: 1.6;"></div>
                        </div>

                        <!-- Attachment Preview -->
                        <div id="detAttachmentBox" class="mb-4 d-none">
                            <label class="form-label fw-bold text-muted small mb-1">เอกสาร/รูปภาพแนบ</label>
                            <div>
                                <a id="detAttachmentLink" href="#" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fa-solid fa-paperclip me-1"></i>เปิดดูไฟล์แนบ
                                </a>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Update Status & Officer Notes -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">อัปเดตสถานะคำร้อง <span class="text-danger">*</span></label>
                                <select id="modalStatusSelect" name="status" class="form-select custom-input" required>
                                    <?php foreach ($statuses as $sk => $s): ?>
                                        <option value="<?= esc($sk) ?>"><?= esc($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">บันทึกข้อความตอบกลับ / การประสานงานของเจ้าหน้าที่ (ประชาชนจะมองเห็นเมื่อตรวจสอบรหัส)</label>
                                <textarea id="modalOfficerNote" name="officer_note" class="form-control custom-input" rows="3" placeholder="ระบุผลการตรวจสอบ หรือความคืบหน้า เช่น ส่งต่อ อบจ.พัทลุง หรือ ดำเนินการซ่อมแซมเสร็จสิ้น..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top p-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
                    <button type="submit" id="btnSaveModalStatus" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-save me-1"></i>บันทึกการเปลี่ยนแปลง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentDetailModal = null;

async function openDetailModal(id) {
    document.getElementById('modalContactId').value = id;
    document.getElementById('modalLoadingSpinner').classList.remove('d-none');
    document.getElementById('modalDetailContent').classList.add('d-none');

    if (!currentDetailModal) {
        currentDetailModal = new bootstrap.Modal(document.getElementById('detailModal'));
    }
    currentDetailModal.show();

    try {
        const res = await fetch(`<?= base_url('admin/contacts/detail') ?>/${id}`);
        const json = await res.json();

        if (json.status === 'success') {
            const d = json.data;
            document.getElementById('detailTrackingBadge').innerText = 'รหัสติดตาม: ' + d.tracking_code;
            document.getElementById('detFullName').innerText = d.full_name;
            document.getElementById('detPhone').innerText = d.phone;
            document.getElementById('detEmail').innerText = d.email || '-';
            document.getElementById('detDistrict').innerText = 'อ.' + (d.district || 'เมืองพัทลุง') + ' จ.พัทลุง';
            document.getElementById('detCategory').innerText = d.category_name;
            document.getElementById('detCreatedAt').innerText = d.created_at_fmt;
            document.getElementById('detSubject').innerText = d.subject;
            document.getElementById('detMessage').innerText = d.message;

            const attBox = document.getElementById('detAttachmentBox');
            if (d.attachment_url) {
                attBox.classList.remove('d-none');
                document.getElementById('detAttachmentLink').href = d.attachment_url;
            } else {
                attBox.classList.add('d-none');
            }

            document.getElementById('modalStatusSelect').value = d.status;
            document.getElementById('modalOfficerNote').value = d.officer_note || '';

            document.getElementById('modalLoadingSpinner').classList.add('d-none');
            document.getElementById('modalDetailContent').classList.remove('d-none');
        }
    } catch (e) {
        console.error(e);
        alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
    }
}

async function handleUpdateStatus(e) {
    e.preventDefault();
    const id = document.getElementById('modalContactId').value;
    const form = document.getElementById('updateStatusForm');
    const btn = document.getElementById('btnSaveModalStatus');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>กำลังบันทึก...';

    const formData = new FormData(form);

    try {
        const res = await fetch(`<?= base_url('admin/contacts/update-status') ?>/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const json = await res.json();

        if (json.status === 'success') {
            alert(json.message);
            location.reload();
        } else {
            alert(json.message || 'บันทึกไม่สำเร็จ');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-save me-1"></i>บันทึกการเปลี่ยนแปลง';
        }
    } catch (err) {
        console.error(err);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-save me-1"></i>บันทึกการเปลี่ยนแปลง';
    }
}

async function deleteContact(id) {
    if (!confirm('คุณต้องการลบรายการคำร้องนี้หรือไม่? ข้อมูลและไฟล์แนบจะถูกลบถาวร')) {
        return;
    }

    try {
        const res = await fetch(`<?= base_url('admin/contacts/delete') ?>/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const json = await res.json();
        if (json.status === 'success') {
            alert(json.message);
            location.reload();
        }
    } catch (e) {
        console.error(e);
        alert('เกิดข้อผิดพลาดในการลบ');
    }
}
</script>
<?= $this->endSection() ?>

<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #0f172a;">จัดการหน้าเว็บไซต์ (Static Pages)</h4>
        <p class="text-muted mb-0" style="font-size: 0.92rem;">
            สร้างและแก้ไขหน้าเนื้อหาคงที่ พร้อมระบบหน้าย่อย (Tabs) อัตโนมัติ
        </p>
    </div>
    <div>
        <button class="btn btn-modern" onclick="openPageModal()">
            <i class="fa-solid fa-plus me-1"></i> สร้างหน้าใหม่
        </button>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list-check me-2 text-primary"></i> รายชื่อหน้าเว็บไซต์ทั้งหมด</h6>
        <span class="badge bg-light text-muted border"><?= count($pages ?? []) ?> รายการ</span>
    </div>
    
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0" id="pagesTable">
                <thead>
                    <tr>
                        <th width="6%">ID</th>
                        <th width="38%">หัวข้อหน้าเพจ (Title)</th>
                        <th width="24%">ลิงก์ (Slug)</th>
                        <th width="14%" class="text-center">ยอดเข้าชม</th>
                        <th width="18%" class="text-end pe-4">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pages)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-folder-open mb-2 d-block" style="font-size: 2rem; opacity: 0.5;"></i>
                                ยังไม่มีหน้าเว็บไซต์ในระบบ กดปุ่ม "สร้างหน้าใหม่" ด้านบนเพื่อเริ่มสร้าง
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pages as $p): ?>
                            <tr>
                                <td class="text-muted"><?= $p['id'] ?></td>
                                <td>
                                    <?php if (!empty($p['parent_id'])): ?>
                                        <div class="d-flex align-items-center ms-3">
                                            <span class="text-muted me-2"><i class="fa-solid fa-turn-up fa-rotate-90"></i></span>
                                            <span class="fw-medium text-secondary"><?= esc($p['title']) ?></span>
                                            <span class="badge bg-light text-muted border ms-2" style="font-size: 0.7rem;">แท็บย่อย</span>
                                        </div>
                                    <?php else: ?>
                                        <span class="fw-bold text-dark"><?= esc($p['title']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('page/' . esc($p['slug'])) ?>" target="_blank" class="text-decoration-none badge bg-light text-primary border px-2 py-1">
                                        /page/<?= esc($p['slug']) ?> <i class="fa-solid fa-arrow-up-right-from-square ms-1" style="font-size:0.65rem;"></i>
                                    </a>
                                </td>
                                <td class="text-center font-monospace" style="font-size: 0.9rem;"><?= number_format($p['views']) ?></td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editPage(<?= $p['id'] ?>)" title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> แก้ไข
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePage(<?= $p['id'] ?>, '<?= esc($p['title']) ?>')" title="ลบ">
                                        <i class="fa-solid fa-trash"></i>
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

<!-- Modal สำหรับสร้าง/แก้ไขเพจ -->
<div class="modal fade" id="pageModal" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom">
                <h5 class="modal-title fw-bold" id="pageModalTitle"><i class="fa-solid fa-pen me-2"></i> สร้าง/แก้ไขหน้าเว็บไซต์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="pageForm">
                    <input type="hidden" id="pageId" name="id">
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">หัวข้อหน้าเพจ (Title) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="pageTitle" name="title" required placeholder="เช่น ประวัติจังหวัด">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ลิงก์ URL (Slug) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light text-muted" style="font-size: 0.95rem;">/page/</span>
                                <input type="text" class="form-control border-start-0" id="pageSlug" name="slug" required placeholder="เช่น history">
                            </div>
                            <small class="mt-2 d-block text-muted" style="font-size: 0.85rem;"><i class="fa-solid fa-circle-info me-1"></i>อักษรภาษาอังกฤษ ตัวเลข และขีดกลางเท่านั้น</small>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label fw-bold">กำหนดให้เป็นหน้าย่อยของ (Parent Page)</label>
                            <select class="form-select" id="pageParent" name="parent_id">
                                <option value="" class="text-dark">-- ไม่มี (ตั้งเป็นหน้าหลัก) --</option>
                                <?php foreach ($parent_pages as $parent): ?>
                                    <option value="<?= $parent['id'] ?>" class="text-dark"><?= esc($parent['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="mt-2 d-block text-muted" style="font-size: 0.85rem;">ถ้าระบุ เพจนี้จะกลายเป็นแท็บซ้อนอยู่ข้างในหน้าหลักที่เลือก</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ลำดับการแสดงผลแท็บ</label>
                            <input type="number" class="form-control" id="pageOrder" name="order_num" value="0">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">เนื้อหา (Content) <span class="text-danger">*</span></label>
                        <!-- พื้นที่สำหรับ TinyMCE -->
                        <div class="border rounded-1">
                            <textarea id="pageContent" name="content"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="btnSavePage" onclick="savePage()">
                    <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>

<!-- โหลด TinyMCE ผ่าน CDN ที่เสถียร (ไม่มีปัญหา API Key ล็อกการพิมพ์) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

<style>
/* บังคับให้กรอบ TinyMCE สามารถคลิกและพิมพ์ได้แน่นอน 100% */
.tox-tinymce {
    border-radius: 8px !important;
    border: 1px solid #cbd5e1 !important;
}
.tox-tinymce-aux {
    z-index: 1065 !important;
}
</style>

<script>
let pageModal;

// ป้องกัน Bootstrap 5 Modal ขัดขวาง Focus จาก TinyMCE
document.addEventListener('focusin', (e) => {
    if (e.target.closest && e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
        e.stopImmediatePropagation();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('pageModal');
    
    // กำหนดค่า Modal โดยปิด focus trap เพื่อไม่ให้บล็อกการพิมพ์ใน iframe
    pageModal = new bootstrap.Modal(modalEl, {
        focus: false,
        keyboard: true
    });
    
    // ตั้งค่า TinyMCE
    tinymce.init({
        selector: '#pageContent',
        height: 480,
        menubar: 'file edit view insert format tools table help',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image table | code help',
        content_style: 'body { font-family: "Sarabun", "Prompt", "Inter", sans-serif; font-size: 16px; line-height: 1.6; color: #1e293b; padding: 12px; }',
        promotion: false,
        branding: false,
        setup: function (editor) {
            editor.on('init', function () {
                editor.getBody().style.fontSize = '16px';
            });
        }
    });

    // เมื่อ Modal เปิดขึ้นมา ให้โฟกัส Editor
    modalEl.addEventListener('shown.bs.modal', function () {
        if (tinymce.get('pageContent')) {
            tinymce.get('pageContent').focus();
        }
    });

    // สร้าง Slug อัตโนมัติจาก Title
    document.getElementById('pageTitle').addEventListener('keyup', function() {
        if (!document.getElementById('pageId').value) {
            let slug = this.value.toLowerCase().trim()
                .replace(/[^\w\s-ก-๙]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            document.getElementById('pageSlug').value = slug;
        }
    });
});

function openPageModal() {
    document.getElementById('pageForm').reset();
    document.getElementById('pageId').value = '';
    document.getElementById('pageParent').value = '';
    document.getElementById('pageOrder').value = '0';
    if (tinymce.get('pageContent')) {
        tinymce.get('pageContent').setContent('');
    }
    document.getElementById('pageModalTitle').innerHTML = '<i class="fa-solid fa-plus me-2"></i> สร้างหน้าเว็บไซต์ใหม่';
    pageModal.show();
    setTimeout(() => {
        if (tinymce.get('pageContent')) {
            tinymce.get('pageContent').focus();
        }
    }, 150);
}

async function editPage(id) {
    try {
        const res = await App.fetch(`<?= base_url('admin/pages/get-item') ?>/${id}`);
        if (res.status === 'success') {
            document.getElementById('pageId').value = res.data.id;
            document.getElementById('pageTitle').value = res.data.title;
            document.getElementById('pageSlug').value = res.data.slug;
            document.getElementById('pageParent').value = res.data.parent_id || '';
            document.getElementById('pageOrder').value = res.data.order_num || '0';
            if (tinymce.get('pageContent')) {
                tinymce.get('pageContent').setContent(res.data.content || '');
            }
            
            document.getElementById('pageModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2"></i> แก้ไขหน้า: ' + res.data.title;
            pageModal.show();
            setTimeout(() => {
                if (tinymce.get('pageContent')) {
                    tinymce.get('pageContent').focus();
                }
            }, 150);
        } else {
            App.toast(res.message, 'error');
        }
    } catch (err) {
        App.toast('ไม่สามารถดึงข้อมูลเพจได้', 'error');
    }
}

async function savePage() {
    const form = document.getElementById('pageForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const btn = document.getElementById('btnSavePage');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    // อัปเดตข้อมูลจาก Editor กลับไปที่ Textarea ก่อนส่งค่า
    tinymce.triggerSave();

    const formData = new FormData(form);

    try {
        const res = await App.fetch('<?= base_url("admin/pages/save-item") ?>', {
            method: 'POST',
            body: formData
        });

        if (res.status === 'success') {
            App.toast(res.message, 'success');
            pageModal.hide();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            App.toast(res.message, 'error');
        }
    } catch (err) {
        App.toast('ข้อผิดพลาดเซิร์ฟเวอร์', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = origText;
    }
}

async function deletePage(id, title) {
    if (confirm(`คุณแน่ใจหรือไม่ที่จะลบหน้าเว็บไซต์ "${title}" ?\n\n** คำเตือน: การกระทำนี้ไม่สามารถย้อนกลับได้`)) {
        try {
            const res = await App.fetch(`<?= base_url('admin/pages/delete-item') ?>/${id}`, {
                method: 'POST'
            });
            if (res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                App.toast(res.message, 'error');
            }
        } catch (err) {
            App.toast('ลบข้อมูลไม่สำเร็จ', 'error');
        }
    }
}
</script>
<?= $this->endSection() ?>

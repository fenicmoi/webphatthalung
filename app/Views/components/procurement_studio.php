<?php if (session()->get('isLoggedIn')): 
    helper('settings');
    $procCategories = get_procurement_categories();
?>
<!-- ON-PAGE PROCUREMENT & e-GP STUDIO MODAL -->
<div class="modal fade" id="procurementStudioModal" tabindex="-1" aria-labelledby="procStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-5 shadow-lg overflow-hidden" style="background: var(--glass-bg); backdrop-filter: blur(25px); border: 2px solid var(--glass-border) !important;">
            
            <!-- Modal Header -->
            <div class="modal-header px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a, #1e293b); border-color: rgba(255,255,255,0.15) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #0284c7, #0369a1); color: #fff;">
                        <i class="fa-solid fa-file-invoice-dollar fs-4 animate-pulse"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="procStudioModalLabel">
                            ระบบบริหารข่าวจัดซื้อจัดจ้างภาครัฐ (e-GP Studio) <span class="badge bg-warning text-dark fs-7 ms-2">On-Page CMS</span>
                        </h5>
                        <small class="text-info">เผยแพร่ประกาศ مناقصة ราคากลาง และ สขร.1 ตามมาตรฐานความโปร่งใสภาครัฐ</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 p-md-5">
                <form id="procStudioForm" onsubmit="event.preventDefault(); ProcurementStudio.save();" enctype="multipart/form-data">
                    <input type="hidden" id="proc_id" name="id" value="">
                    
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-secondary">หมวดหมู่ประกาศ <span class="text-danger">*</span></label>
                            <select class="form-select rounded-pill px-3 py-2 border-primary-subtle shadow-sm" id="proc_category" name="category" required>
                                <?php foreach ($procCategories as $cat): ?>
                                    <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-secondary">วันที่ประกาศ <span class="text-danger">*</span></label>
                            <input type="date" class="form-control rounded-pill px-3 py-2 border-primary-subtle shadow-sm" id="proc_date" name="date" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-secondary">หัวข้อประกาศ / ชื่อโครงการ <span class="text-danger">*</span></label>
                            <textarea class="form-control rounded-4 p-3 border-primary-subtle shadow-sm" id="proc_title" name="title" rows="3" placeholder="ระบุชื่อโครงการ ประกาศ หรือรายงานผลการดำเนินงาน สขร.1..." required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">วงเงินงบประมาณ / ราคากลาง</label>
                            <div class="input-group shadow-sm rounded-pill overflow-hidden border border-primary-subtle">
                                <span class="input-group-text bg-body-tertiary border-0 px-3"><i class="fa-solid fa-coins text-warning"></i></span>
                                <input type="text" class="form-control border-0 px-2 py-2" id="proc_budget" name="budget" placeholder="เช่น 1,500,000 บาท หรือ -">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-secondary">อัปโหลดไฟล์เอกสาร (PDF / Word)</label>
                            <input type="file" class="form-control rounded-pill px-3 py-1 border-primary-subtle shadow-sm" id="proc_doc_file" name="doc_file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-secondary">หรือระบุ URL เชื่อมโยงเอกสาร (ถ้ามี)</label>
                            <div class="input-group shadow-sm rounded-pill overflow-hidden border border-primary-subtle">
                                <span class="input-group-text bg-body-tertiary border-0 px-3"><i class="fa-solid fa-link text-info"></i></span>
                                <input type="text" class="form-control border-0 px-2 py-2" id="proc_attachment_url" name="attachment_url" placeholder="เช่น assets/docs/sample.pdf หรือ https://www.gprocurement.go.th/...">
                            </div>
                            <small class="text-muted ms-2">หากไม่ได้อัปโหลดไฟล์ ระบบจะดึงลิ้งก์จากช่องนี้ไปแสดง</small>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="proc_active" name="active" checked style="cursor: pointer;">
                                <label class="form-check-label fw-bold text-success" for="proc_active" style="cursor: pointer;">
                                    🟢 เปิดเผยแพร่บนศูนย์ข้อมูลจัดซื้อจัดจ้าง
                                </label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center gap-2" id="procSaveBtn" style="background: linear-gradient(135deg, #0284c7, #0369a1); border: none;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>บันทึกประกาศ</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
window.ProcurementStudio = {
    modal: null,
    
    init: function() {
        if (!this.modal) {
            const el = document.getElementById('procurementStudioModal');
            if (el) {
                this.modal = new bootstrap.Modal(el);
            }
        }
    },

    open: function(id = null, category = null) {
        this.init();
        const form = document.getElementById('procStudioForm');
        form.reset();
        document.getElementById('proc_id').value = '';
        document.getElementById('proc_active').checked = true;
        
        if (category) {
            const catSelect = document.getElementById('proc_category');
            if (catSelect) catSelect.value = category;
        }

        if (id) {
            fetch(`<?= base_url('admin/procurement/get-inline') ?>/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.item) {
                        const item = data.item;
                        document.getElementById('proc_id').value = item.id;
                        document.getElementById('proc_title').value = item.title;
                        document.getElementById('proc_category').value = item.category || 'ประกาศจัดซื้อจัดจ้าง';
                        document.getElementById('proc_date').value = item.date || '<?= date('Y-m-d') ?>';
                        document.getElementById('proc_budget').value = item.budget || '-';
                        document.getElementById('proc_attachment_url').value = item.attachment_url || '';
                        document.getElementById('proc_active').checked = (item.active === true || item.active === 'true' || item.active === 1 || item.active === '1');
                        this.modal.show();
                    } else {
                        Swal.fire('ข้อผิดพลาด', data.message || 'ไม่พบข้อมูล', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('ข้อผิดพลาด', 'ไม่สามารถโหลดข้อมูลได้', 'error');
                });
        } else {
            this.modal.show();
        }
    },

    save: function() {
        const form = document.getElementById('procStudioForm');
        const formData = new FormData(form);

        const saveBtn = document.getElementById('procSaveBtn');
        const origHtml = saveBtn.innerHTML;
        saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> กำลังบันทึก...';
        saveBtn.disabled = true;

        fetch('<?= base_url('admin/procurement/save-inline') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            saveBtn.innerHTML = origHtml;
            saveBtn.disabled = false;

            if (data.status === 'success') {
                this.modal.hide();
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: data.message || 'บันทึกข้อมูลจัดซื้อจัดจ้างเรียบร้อยแล้ว',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#10b981',
                    timer: 1800
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire('ข้อผิดพลาด', data.message || 'เกิดข้อผิดพลาดบางประการ', 'error');
            }
        })
        .catch(err => {
            saveBtn.innerHTML = origHtml;
            saveBtn.disabled = false;
            console.error(err);
            Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
        });
    },

    deleteItem: function(id, title) {
        Swal.fire({
            title: 'ยืนยันการลบประกาศ?',
            text: `คุณต้องการลบ "${title}" ใช่หรือไม่? (ไม่สามารถกู้คืนได้)`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ลบประกาศนี้',
            cancelButtonText: 'ยกเลิก'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`<?= base_url('admin/procurement/delete-inline') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('ลบแล้ว!', 'ลบรายการจัดซื้อจัดจ้างเรียบร้อยแล้ว', 'success')
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่สามารถลบรายการได้', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                });
            }
        });
    }
};
</script>
<?php endif; ?>

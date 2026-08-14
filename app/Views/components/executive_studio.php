<?php
    helper('settings');
    $execCategories = function_exists('get_executive_categories') ? get_executive_categories() : [];
?>
<!-- ON-PAGE EXECUTIVE LEADERSHIP STUDIO MODAL -->
<div class="modal fade" id="executiveStudioModal" tabindex="-1" aria-labelledby="execStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #1e3a8a, #0369a1);">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-circle bg-warning text-dark shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-crown fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white" id="execStudioModalLabel">Executive Studio — จัดการทำเนียบและวิสัยทัศน์ผู้บริหาร</h5>
                        <small class="text-info">ระบบปรับแต่งข้อมูลผู้นำ อัปโหลดภาพประจำตำแหน่ง และคำคมวิสัยทัศน์แบบเรียลไทม์</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 p-md-5 bg-light">
                <form id="execStudioForm" onsubmit="event.preventDefault(); ExecutiveStudio.save();" enctype="multipart/form-data">
                    <input type="hidden" id="exec_id" name="id" value="">

                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label for="exec_name" class="form-label fw-bold text-dark">ชื่อ-นามสกุล ผู้บริหาร <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm" id="exec_name" name="name" placeholder="เช่น นายสุจินต์ วาจสกิจ" required>
                        </div>
                        <div class="col-md-5">
                            <label for="exec_position" class="form-label fw-bold text-dark">ตำแหน่งราชการ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm" id="exec_position" name="position" placeholder="เช่น ผู้ว่าราชการจังหวัดพัทลุง" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="exec_category" class="form-label fw-bold text-dark">หมวดหมู่ทำเนียบ</label>
                            <select class="form-select rounded-3 shadow-sm" id="exec_category" name="category">
                                <?php foreach ($execCategories as $cKey => $cVal): ?>
                                    <option value="<?= esc($cVal['name'] ?? '') ?>"><?= esc($cVal['name'] ?? '') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="exec_order_num" class="form-label fw-bold text-dark">ลำดับศักดิ์ (1=ผู้ว่าฯ)</label>
                            <input type="number" class="form-control rounded-3 shadow-sm text-center fw-bold" id="exec_order_num" name="order_num" value="1" min="1" max="999">
                        </div>
                        <div class="col-md-3 d-flex align-items-end pb-1">
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input" type="checkbox" id="exec_featured" name="featured" value="1" checked>
                                <label class="form-check-label fw-semibold text-primary" for="exec_featured">แสดงบนหน้าแรก</label>
                            </div>
                        </div>
                    </div>

                    <!-- PHOTO UPLOAD & URL -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-image text-primary me-2"></i>รูปภาพถ่ายประจำตำแหน่ง</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="exec_photo_file" class="form-label text-muted small">1) อัปโหลดจากคอมพิวเตอร์ (ไฟล์ JPG/PNG)</label>
                                <input class="form-control rounded-3" type="file" id="exec_photo_file" name="photo_file" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label for="exec_photo_url" class="form-label text-muted small">2) หรือระบุ ลิงก์รูปภาพ (URL)</label>
                                <input type="text" class="form-control rounded-3" id="exec_photo_url" name="photo_url" placeholder="https://... หรือ assets/img/...">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="exec_quote" class="form-label fw-bold text-dark">คำคมหรือวิสัยทัศน์ของผู้บริหาร (Vision Quote)</label>
                        <textarea class="form-control rounded-3 shadow-sm" id="exec_quote" name="quote" rows="3" placeholder="เช่น รักเมืองลุง สร้างเมืองลุง ไปด้วยกัน ทำงานร่วมกัน ด้วยความสามัคคี..."></textarea>
                        <small class="text-muted">คำคมนี้จะถูกแสดงอย่างโดดเด่นทั้งในโซนหน้าเว็บหลัก และบัตรเชิดชูวิสัยทัศน์ในหน้าทำเนียบ</small>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="exec_phone" class="form-label fw-bold text-dark">เบอร์โทรศัพท์สายตรง</label>
                            <input type="text" class="form-control rounded-3 shadow-sm" id="exec_phone" name="phone" placeholder="เช่น 074-613409">
                        </div>
                        <div class="col-md-6">
                            <label for="exec_email" class="form-label fw-bold text-dark">อีเมลติดต่อ</label>
                            <input type="email" class="form-control rounded-3 shadow-sm" id="exec_email" name="email" placeholder="เช่น phatthalung@moi.go.th">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2 hover-scale">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>บันทึกรายนามและขึ้นระบบทันที</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
window.ExecutiveStudio = {
    modalInstance: null,

    open: function(id = null) {
        if (!this.modalInstance) {
            const el = document.getElementById('executiveStudioModal');
            if (!el) return;
            this.modalInstance = new bootstrap.Modal(el);
        }

        const form = document.getElementById('execStudioForm');
        if (form) form.reset();
        const idEl = document.getElementById('exec_id');
        if (idEl) idEl.value = '';

        if (id && id !== 'null' && id !== '') {
            document.getElementById('execStudioModalLabel').innerText = 'Executive Studio — แก้ไขรายนามและวิสัยทัศน์ผู้บริหาร';
            fetch(`<?= base_url('admin/executives/get-item') ?>/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.data) {
                        const it = data.data;
                        document.getElementById('exec_id').value = it.id;
                        document.getElementById('exec_name').value = it.name || '';
                        document.getElementById('exec_position').value = it.position || '';
                        document.getElementById('exec_category').value = it.category || 'คณะผู้บริหารระดับสูง';
                        document.getElementById('exec_quote').value = it.quote || '';
                        document.getElementById('exec_phone').value = it.phone || '';
                        document.getElementById('exec_email').value = it.email || '';
                        document.getElementById('exec_order_num').value = it.order_num || 1;
                        document.getElementById('exec_featured').checked = !(!it.featured);
                        document.getElementById('exec_photo_url').value = it.photo || '';
                        this.modalInstance.show();
                    } else {
                        alert(data.message || 'ไม่พบข้อมูลผู้บริหาร');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('เกิดข้อผิดพลาดในการดึงข้อมูลผู้บริหาร');
                });
        } else {
            document.getElementById('execStudioModalLabel').innerText = 'Executive Studio — + เพิ่มรายนามผู้บริหารท่านใหม่';
            this.modalInstance.show();
        }
    },

    save: function() {
        const form = document.getElementById('execStudioForm');
        const formData = new FormData(form);

        fetch('<?= base_url('admin/executives/save-item') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert('ข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('เกิดข้อผิดพลาดในการติดต่อเซิร์ฟเวอร์');
        });
    },

    deleteItem: function(id, name) {
        if (confirm(`คุณต้องการลบรายนาม "${name}" ออกจากทำเนียบผู้บริหารหรือไม่?`)) {
            fetch(`<?= base_url('admin/executives/delete-item') ?>/${id}`, {
                method: 'POST'
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('ข้อผิดพลาด: ' + (data.message || 'ไม่สามารถลบรายการได้'));
                }
            })
            .catch(err => {
                console.error(err);
                alert('เกิดข้อผิดพลาดในการติดต่อเซิร์ฟเวอร์');
            });
        }
    }
};
</script>

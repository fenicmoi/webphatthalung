<?php
    helper('settings');
    $execCategories = function_exists('get_executive_categories') ? get_executive_categories() : [];
?>
<!-- ON-PAGE EXECUTIVE LEADERSHIP STUDIO MODAL -->
<div class="modal fade" id="executiveStudioModal" tabindex="-1" aria-labelledby="execStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #0f172a, #1e3a8a, #0369a1);">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-circle bg-warning text-dark shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-tie fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white" id="execStudioModalLabel">Executive Studio — จัดการข้อมูลคณะผู้บริหารปัจจุบัน</h5>
                        <small class="text-light opacity-75">จัดการตำแหน่งการแสดงผล (แถว-คอลัมน์) รูปถ่าย ประวัติการรับราชการ และข้อมูลติดต่อ</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 p-md-5 bg-light">
                <form id="execStudioForm" onsubmit="event.preventDefault(); ExecutiveStudio.save();" enctype="multipart/form-data">
                    <input type="hidden" id="exec_id" name="id" value="">

                    <!-- 1. General Info -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-id-card text-primary me-2"></i>1. ข้อมูลพื้นฐานผู้บริหาร</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label for="exec_name" class="form-label fw-bold text-dark">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 shadow-sm" id="exec_name" name="name" placeholder="เช่น นายสุจินต์ วาจากิจ" required>
                            </div>
                            <div class="col-md-5">
                                <label for="exec_position" class="form-label fw-bold text-dark">ตำแหน่งทางการบริหาร <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 shadow-sm" id="exec_position" name="position" placeholder="เช่น ผู้ว่าราชการจังหวัดพัทลุง" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="exec_category" class="form-label fw-bold text-dark">หมวดหมู่</label>
                                <select class="form-select rounded-3 shadow-sm" id="exec_category" name="category">
                                    <?php foreach ($execCategories as $cKey => $cVal): ?>
                                        <option value="<?= esc($cVal['name'] ?? '') ?>"><?= esc($cVal['name'] ?? '') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end pb-1">
                                <div class="form-check form-switch fs-6">
                                    <input class="form-check-input" type="checkbox" id="exec_featured" name="featured" value="1" checked>
                                    <label class="form-check-label fw-semibold text-primary" for="exec_featured">แสดงบนหน้าแรกของเว็บไซต์</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Grid Positioning (Row & Column) -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white" style="border-left: 5px solid #0284c7 !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-table-cells-large text-primary me-2"></i>2. การจัดวางตำแหน่งผังโครงสร้าง (แถวและคอลัมน์)</h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary">Grid Layout Positioning</span>
                        </div>
                        <p class="text-muted small mb-3">ระบบจะจัดเรียงผู้บริหารในแต่ละแถวให้อยู่กึ่งกลางโดยอัตโนมัติ และใส่กรอบทรงกลมแบบโมเดิร์นให้อย่างสวยงาม</p>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="exec_row_num" class="form-label fw-bold text-dark">
                                    <i class="fa-solid fa-arrows-up-down text-primary me-1"></i> แถวที่ (Row)
                                </label>
                                <input type="number" class="form-control rounded-3 shadow-sm text-center fw-bold fs-5" id="exec_row_num" name="row_num" value="1" min="1" max="50">
                                <small class="text-muted d-block mt-1">1 = แถวบนสุด (ผู้ว่าฯ), 2 = แถวที่สอง (รองผู้ว่าฯ)</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exec_col_num" class="form-label fw-bold text-dark">
                                    <i class="fa-solid fa-arrows-left-right text-primary me-1"></i> คอลัมน์ที่ (Column)
                                </label>
                                <input type="number" class="form-control rounded-3 shadow-sm text-center fw-bold fs-5" id="exec_col_num" name="col_num" value="1" min="1" max="50">
                                <small class="text-muted d-block mt-1">ลำดับซ้ายไปขวาในแถวนั้นๆ (1, 2, 3...)</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exec_order_num" class="form-label fw-bold text-dark">
                                    <i class="fa-solid fa-arrow-down-1-9 text-muted me-1"></i> ลำดับศักดิ์ (Order)
                                </label>
                                <input type="number" class="form-control rounded-3 shadow-sm text-center" id="exec_order_num" name="order_num" value="1" min="1" max="999">
                                <small class="text-muted d-block mt-1">ลำดับอาวุโส / การเรียงสำรอง</small>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Photo Upload & Auto Framing -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-camera-retro text-primary me-2"></i>3. รูปถ่ายประจำตำแหน่ง (ระบบใส่กรอบวงกลมให้อัตโนมัติ)</h6>
                        <small class="text-muted d-block mb-3">สามารถอัปโหลดรูปภาพแนวตั้งหรือสี่เหลี่ยมจัตุรัส ระบบจะจัดการครอปทรงกลมและใส่กรอบขอบทองโมเดิร์นให้ทันที</small>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="exec_photo_file" class="form-label text-muted small">อัปโหลดไฟล์ภาพใหม่ (JPG/PNG)</label>
                                <input class="form-control rounded-3" type="file" id="exec_photo_file" name="photo_file" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label for="exec_photo_url" class="form-label text-muted small">หรือระบุ ที่อยู่รูปภาพ (URL/Path)</label>
                                <input type="text" class="form-control rounded-3" id="exec_photo_url" name="photo_url" placeholder="uploads/executives/... หรือ https://...">
                            </div>
                        </div>
                    </div>

                    <!-- 3.1 Document / Attachment File Upload (PDF/Word) -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-file-pdf text-danger me-2"></i>3.1 แนบไฟล์เอกสารประวัติ / ไฟล์เอกสารทางการ (PDF / Word)</h6>
                        <small class="text-muted d-block mb-3">สามารถอัปโหลดไฟล์ PDF ประวัติฉบับเต็ม หรือเอกสารที่เกี่ยวข้อง เพื่อให้ประชาชนและเจ้าหน้าที่สามารถดาวน์โหลดได้</small>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="exec_document_file" class="form-label text-muted small">อัปโหลดไฟล์เอกสาร (PDF, DOC, DOCX)</label>
                                <input class="form-control rounded-3" type="file" id="exec_document_file" name="document_file" accept=".pdf,.doc,.docx">
                            </div>
                            <div class="col-md-6">
                                <label for="exec_document_name" class="form-label text-muted small">ชื่อเอกสารที่แสดง (Document Title)</label>
                                <input type="text" class="form-control rounded-3" id="exec_document_name" name="document_name" placeholder="เช่น ประวัติและผลงานฉบับสมบูรณ์ (PDF)">
                            </div>
                            <div class="col-12">
                                <label for="exec_document_url" class="form-label text-muted small">หรือระบุ ลิงก์ไฟล์เอกสารภายนอก (URL)</label>
                                <input type="text" class="form-control rounded-3" id="exec_document_url" name="document_url" placeholder="uploads/executives/docs/... หรือ https://...">
                            </div>
                            <div class="col-12 d-none" id="exec_current_doc_box">
                                <div class="alert alert-light border d-flex align-items-center justify-content-between p-2 mb-0 rounded-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-paperclip text-primary"></i>
                                        <span class="small fw-semibold" id="exec_current_doc_text">มีไฟล์เอกสารแนบเดิม</span>
                                    </div>
                                    <a href="#" target="_blank" id="exec_current_doc_link" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                        <i class="fa-solid fa-download me-1"></i> เปิดดูไฟล์
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Contact Details -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-address-book text-primary me-2"></i>4. ข้อมูลการติดต่อ</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="exec_phone" class="form-label fw-bold text-dark"><i class="fa-solid fa-phone text-success me-1"></i> เบอร์โทรศัพท์สายตรง</label>
                                <input type="text" class="form-control rounded-3 shadow-sm" id="exec_phone" name="phone" placeholder="เช่น 074-613409">
                            </div>
                            <div class="col-md-6">
                                <label for="exec_email" class="form-label fw-bold text-dark"><i class="fa-solid fa-envelope text-danger me-1"></i> อีเมลติดต่อราชการ</label>
                                <input type="email" class="form-control rounded-3 shadow-sm" id="exec_email" name="email" placeholder="เช่น phatthalung@moi.go.th">
                            </div>
                        </div>
                    </div>

                    <!-- 5. Vision, Education & Career History (For Detail & PDF Page) -->
                    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-graduation-cap text-primary me-2"></i>5. ประวัติการศึกษาและการรับราชการ (สำหรับหน้าประวัติเต็มและสั่งพิมพ์ PDF)</h6>
                        
                        <div class="mb-3">
                            <label for="exec_quote" class="form-label fw-bold text-dark">วิสัยทัศน์ / คำขวัญในการทำงาน (Vision & Motto)</label>
                            <textarea class="form-control rounded-3 shadow-sm" id="exec_quote" name="quote" rows="2" placeholder="เช่น รักเมืองลุง สร้างเมืองลุง ไปด้วยกัน ทำงานร่วมกัน ด้วยความสามัคคี..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="exec_education" class="form-label fw-bold text-dark">ประวัติการศึกษา (Education History)</label>
                            <textarea class="form-control rounded-3 shadow-sm" id="exec_education" name="education" rows="3" placeholder="• ปริญญาตรี รัฐศาสตรบัณฑิต (ร.บ.)&#10;• ปริญญาโท รัฐประศาสนศาสตรมหาบัณฑิต (รป.ม.)"></textarea>
                            <small class="text-muted">ใส่เป็นข้อๆ แต่ละบรรทัด ระบบจะจัดเรียงเป็นรายการให้อัตโนมัติ</small>
                        </div>

                        <div class="mb-3">
                            <label for="exec_training" class="form-label fw-bold text-dark">ประวัติการฝึกอบรมและหลักสูตรสำคัญ (Training & Executive Courses)</label>
                            <textarea class="form-control rounded-3 shadow-sm" id="exec_training" name="training" rows="3" placeholder="• หลักสูตรนักปกครองระดับสูง (นปส.) รุ่นที่ ...&#10;• หลักสูตรนักบริหารระดับสูง (นบส. 1) รุ่นที่ ...&#10;• หลักสูตรวิทยาลัยการปกครอง / วปอ."></textarea>
                            <small class="text-muted">ใส่เป็นข้อๆ แต่ละบรรทัด เช่น หลักสูตร นปส., นบส., วปอ., บยส. ฯลฯ</small>
                        </div>

                        <div class="mb-0">
                            <label for="exec_history" class="form-label fw-bold text-dark">ประวัติการรับราชการ / การดำรงตำแหน่งที่สำคัญ (Civil Service Career)</label>
                            <textarea class="form-control rounded-3 shadow-sm" id="exec_history" name="history" rows="4" placeholder="• พ.ศ. 2567 - ปัจจุบัน: ผู้ว่าราชการจังหวัดพัทลุง&#10;• พ.ศ. 2565 - 2567: รองผู้ว่าราชการจังหวัด...&#10;• ปลัดจังหวัด..."></textarea>
                            <small class="text-muted">ข้อมูลนี้จะถูกนำไปแสดงในหน้า "ประวัติผู้บริหารฉบับสมบูรณ์" และสามารถสั่งพิมพ์เป็น PDF ได้</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm d-flex align-items-center gap-2 hover-scale">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>บันทึกข้อมูลผู้บริหารทันที</span>
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

        const currentDocBox = document.getElementById('exec_current_doc_box');
        if (currentDocBox) currentDocBox.classList.add('d-none');
        const docNameEl = document.getElementById('exec_document_name');
        if (docNameEl) docNameEl.value = '';
        const docUrlEl = document.getElementById('exec_document_url');
        if (docUrlEl) docUrlEl.value = '';

        if (id && id !== 'null' && id !== '') {
            document.getElementById('execStudioModalLabel').innerText = 'Executive Studio — แก้ไขข้อมูลผู้บริหาร';
            fetch(`<?= base_url('admin/executives/get-item') ?>/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.data) {
                        const it = data.data;
                        document.getElementById('exec_id').value = it.id || '';
                        document.getElementById('exec_name').value = it.name || '';
                        document.getElementById('exec_position').value = it.position || '';
                        document.getElementById('exec_category').value = it.category || 'คณะผู้บริหารระดับสูง';
                        document.getElementById('exec_row_num').value = it.row_num || 1;
                        document.getElementById('exec_col_num').value = it.col_num || 1;
                        document.getElementById('exec_order_num').value = it.order_num || 1;
                        document.getElementById('exec_featured').checked = !(!it.featured);
                        document.getElementById('exec_photo_url').value = it.photo || '';
                        document.getElementById('exec_document_name').value = it.document_name || '';
                        document.getElementById('exec_document_url').value = it.document_file || '';
                        document.getElementById('exec_phone').value = it.phone || '';
                        document.getElementById('exec_email').value = it.email || '';
                        document.getElementById('exec_quote').value = it.quote || '';
                        document.getElementById('exec_education').value = it.education || '';
                        document.getElementById('exec_training').value = it.training || '';
                        document.getElementById('exec_history').value = it.history || '';

                        if (it.document_file) {
                            const fullDocUrl = (it.document_file.indexOf('http') === 0) ? it.document_file : ('<?= base_url() ?>/' + it.document_file);
                            const docBox = document.getElementById('exec_current_doc_box');
                            const docLink = document.getElementById('exec_current_doc_link');
                            const docText = document.getElementById('exec_current_doc_text');
                            if (docBox && docLink && docText) {
                                docLink.href = fullDocUrl;
                                docText.innerText = it.document_name ? ('ไฟล์ปัจจุบัน: ' + it.document_name) : 'มีไฟล์เอกสารแนบในระบบ';
                                docBox.classList.remove('d-none');
                            }
                        }

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
            document.getElementById('exec_row_num').value = 1;
            document.getElementById('exec_col_num').value = 1;
            document.getElementById('exec_order_num').value = 1;
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

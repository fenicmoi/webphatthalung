<?php
    $categories = function_exists('get_ita_categories') ? get_ita_categories() : [];
    $scorecard = function_exists('get_ita_scorecard') ? get_ita_scorecard() : [];
?>

<!-- MODAL 1: ITA/OIT & OPEN DATA ITEM STUDIO -->
<div class="modal fade" id="itaStudioModal" tabindex="-1" aria-labelledby="itaStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-success text-white px-4 py-3 rounded-top-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-folder-open fs-4"></i>
                    <h5 class="modal-title fw-bold mb-0" id="itaStudioModalLabel">ระบบจัดการตัวชี้วัดความโปร่งใส ITA & Open Data Studio</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="itaStudioForm" enctype="multipart/form-data">
                    <input type="hidden" id="ita_id" name="id" value="">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-dark"><i class="fa-solid fa-tag me-1 text-success"></i> รหัสตัวชี้วัด (Code) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ita_code" name="code" placeholder="เช่น O1, O18, DAT-01" required>
                            <div class="form-text small">รหัส OIT ตาม ป.ป.ช. หรือรหัสชุดข้อมูล</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-dark"><i class="fa-solid fa-heading me-1 text-primary"></i> ชื่อตัวชี้วัด / ชุดข้อมูล <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ita_title" name="title" placeholder="ระบุชื่อเอกสารหรือหัวข้อ..." required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">หมวดหมู่หลัก (Category) <span class="text-danger">*</span></label>
                            <select class="form-select" id="ita_category" name="category" required>
                                <?php foreach ($categories as $key => $cat): ?>
                                    <option value="<?= esc($key) ?>"><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">หมวดหมู่ย่อย (Sub-Category)</label>
                            <input type="text" class="form-control" id="ita_sub_category" name="sub_category" placeholder="เช่น ข้อมูลพื้นฐาน, งบประมาณ, ชุดข้อมูลเปิด">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark"><i class="fa-solid fa-align-left me-1 text-secondary"></i> คำอธิบายย่อ (Description)</label>
                        <textarea class="form-control" id="ita_desc" name="desc" rows="2" placeholder="อธิบายสรุปใจความสำคัญ หรือความถูกต้องตามเกณฑ์..."></textarea>
                    </div>

                    <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                        <h6 class="fw-bold small text-dark mb-2"><i class="fa-solid fa-cloud-arrow-up text-danger me-1"></i> แนบไฟล์ หรือ ระบุลิงก์ url เว็บภายใน</h6>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold">ประเภทไฟล์ (Type)</label>
                                <select class="form-select form-select-sm" id="ita_file_type" name="file_type">
                                    <option value="pdf">PDF Document (.pdf)</option>
                                    <option value="csv">CSV Open Data (.csv)</option>
                                    <option value="json">JSON Dataset (.json)</option>
                                    <option value="xls">Excel Spreadsheet (.xls/xlsx)</option>
                                    <option value="link">Web Link / Internal Route</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small fw-semibold">เลือกไฟล์เพื่ออัปโหลด</label>
                                <input class="form-control form-control-sm" type="file" id="ita_doc_file" name="doc_file" accept=".pdf,.csv,.json,.xls,.xlsx,.doc,.docx">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label small fw-semibold">หรือระบุ URL/Route ลิงก์ปลายทาง (กรณีไม่ได้อัปโหลดไฟล์)</label>
                            <input type="text" class="form-control form-control-sm" id="ita_external_url" name="external_url" placeholder="เช่น executives, citizen/complaints หรือ https://...">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ita_verified" name="verified" checked>
                            <label class="form-check-label fw-bold small text-dark" for="ita_verified">✅ ผ่านการตรวจสอบความถูกต้องแล้ว</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ita_featured" name="featured" checked>
                            <label class="form-check-label fw-bold small text-dark" for="ita_featured">⭐ แสดงป้ายแนะนำ (Featured)</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" onclick="ItaStudio.save()" class="btn btn-success fw-bold rounded-pill px-5 shadow-sm">
                    <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกรายการ
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 2: ITA SCORECARD STUDIO -->
<div class="modal fade" id="itaScorecardModal" tabindex="-1" aria-labelledby="itaScorecardModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-warning text-dark px-4 py-3 rounded-top-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-award fs-4 text-primary"></i>
                    <h5 class="modal-title fw-bold mb-0" id="itaScorecardModalLabel">ปรับปรุงผลคะแนนการประเมินความโปร่งใส (ITA Scorecard)</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="itaScorecardForm">
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-dark">ปีงบประมาณ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center fw-bold" name="year" value="<?= esc($scorecard['year'] ?? '2568') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-dark">คะแนนรวม (เต็ม 100) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center fw-bold text-success fs-5" name="overall_score" value="<?= esc($scorecard['overall_score'] ?? '96.48') ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small text-dark">เกรด (Grade) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-center fw-bold bg-warning bg-opacity-25" name="grade" value="<?= esc($scorecard['grade'] ?? 'A+') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-dark">คำอธิบายผลระดับเกรด <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="grade_title" value="<?= esc($scorecard['grade_title'] ?? 'ผ่านเกณฑ์ระดับยอดเยี่ยม (A+)') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">หน่วยงานประเมินผล (Evaluator)</label>
                        <input type="text" class="form-control" name="evaluator" value="<?= esc($scorecard['evaluator'] ?? 'สำนักงาน ป.ป.ช.') ?>">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-dark">ข้อความประกาศวิสัยทัศน์ความโปร่งใส (Quote)</label>
                        <textarea class="form-control" name="quote" rows="2"><?= esc($scorecard['quote'] ?? 'จังหวัดพัทลุงยึดมั่นในการบริหารงานด้วยความโปร่งใส สุจริต เป็นธรรม พร้อมเปิดเผยข้อมูลสาธารณะเพื่อการตรวจสอบอย่างแท้จริง') ?></textarea>
                    </div>

                    <h6 class="fw-bold small text-dark border-bottom pb-2 mb-3">💯 คะแนนย่อยตัวชี้วัด (4 ด้านหลัก)</h6>
                    <div class="row g-3">
                        <?php 
                        $metrics = $scorecard['metrics'] ?? [];
                        for ($i = 0; $i < 4; $i++): 
                            $title = $metrics[$i]['title'] ?? "ตัวชี้วัดที่ " . ($i+1);
                            $sc = $metrics[$i]['score'] ?? 95.00;
                        ?>
                        <div class="col-md-8">
                            <label class="form-label small text-muted mb-1">ชื่อด้านที่ <?= $i+1 ?></label>
                            <input type="text" class="form-control form-control-sm" name="metrics[<?= $i ?>][title]" value="<?= esc($title) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">คะแนน (%)</label>
                            <input type="number" step="0.01" max="100" class="form-control form-control-sm text-end fw-bold text-primary" name="metrics[<?= $i ?>][score]" value="<?= esc($sc) ?>">
                        </div>
                        <?php endfor; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" onclick="ItaStudio.saveScorecard()" class="btn btn-warning fw-bold text-dark rounded-pill px-5 shadow-sm">
                    <i class="fa-solid fa-check-double me-1"></i> บันทึกผลประเมิน ITA
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const ItaStudio = {
    modal: null,
    scoreModal: null,

    init: function() {
        const el = document.getElementById('itaStudioModal');
        if (el && typeof bootstrap !== 'undefined') {
            this.modal = new bootstrap.Modal(el);
        }
        const scEl = document.getElementById('itaScorecardModal');
        if (scEl && typeof bootstrap !== 'undefined') {
            this.scoreModal = new bootstrap.Modal(scEl);
        }
    },

    open: function(id = null) {
        if (!this.modal) this.init();
        const form = document.getElementById('itaStudioForm');
        form.reset();
        document.getElementById('ita_id').value = '';

        if (!id) {
            document.getElementById('itaStudioModalLabel').innerText = 'เพิ่มตัวชี้วัด ITA / Open Data ';
            this.modal.show();
            return;
        }

        document.getElementById('itaStudioModalLabel').innerText = 'กำลังโหลดข้อมูล...';
        this.modal.show();

        fetch('<?= base_url('admin/ita/get-item/') ?>' + id, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const item = data.item;
                document.getElementById('itaStudioModalLabel').innerText = 'แก้ไขรายการรหัส: ' + item.code;
                document.getElementById('ita_id').value = item.id;
                document.getElementById('ita_code').value = item.code || '';
                document.getElementById('ita_title').value = item.title || '';
                document.getElementById('ita_category').value = item.category || 'OIT 1: ตัวชี้วัดการเปิดเผยข้อมูล';
                document.getElementById('ita_sub_category').value = item.sub_category || '';
                document.getElementById('ita_desc').value = item.desc || '';
                document.getElementById('ita_file_type').value = item.file_type || 'pdf';
                document.getElementById('ita_external_url').value = item.file_url || '';
                document.getElementById('ita_verified').checked = !!item.verified;
                document.getElementById('ita_featured').checked = !!item.featured;
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
                this.modal.hide();
            }
        })
        .catch(err => {
            console.error(err);
            App.toast('ไม่สามารถดึงข้อมูลรายการได้', 'error');
            this.modal.hide();
        });
    },

    save: function() {
        const form = document.getElementById('itaStudioForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        App.toast('กำลังบันทึกรายการ...', 'info');

        fetch('<?= base_url('admin/ita/save-item') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                this.modal.hide();
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            App.toast('เกิดข้อผิดพลาดทางเครือข่าย', 'error');
        });
    },

    deleteItem: function(id, title) {
        if (!confirm('คุณยืนยันที่จะลบรายการ "' + title + '" หรือไม่?')) return;

        App.toast('กำลังดำเนินการลบ...', 'info');
        fetch('<?= base_url('admin/ita/delete-item/') ?>' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(data.message || 'ไม่สามารถลบรายการได้', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    },

    openScorecard: function() {
        if (!this.scoreModal) this.init();
        this.scoreModal.show();
    },

    saveScorecard: function() {
        const form = document.getElementById('itaScorecardForm');
        const formData = new FormData(form);
        App.toast('กำลังบันทึกคะแนน ITA Scorecard...', 'info');

        fetch('<?= base_url('admin/ita/save-scorecard') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                this.scoreModal.hide();
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(data.message || 'ไม่สามารถบันทึกข้อมูล Scorecard ได้', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    }
};

document.addEventListener('DOMContentLoaded', () => ItaStudio.init());
</script>

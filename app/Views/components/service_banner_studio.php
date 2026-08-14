<?php if (session()->get('isLoggedIn')): ?>
<!-- ON-PAGE SERVICE BANNER & LINK STUDIO MODAL -->
<div class="modal fade" id="serviceBannerStudioModal" tabindex="-1" aria-labelledby="sbStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-5 shadow-lg overflow-hidden" style="background: var(--glass-bg); backdrop-filter: blur(25px); border: 2px solid var(--glass-border) !important;">
            
            <!-- Modal Header -->
            <div class="modal-header px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a, #1e293b); border-color: rgba(255,255,255,0.15) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #10b981, #059669); color: #fff;">
                        <i class="fa-solid fa-wand-magic-sparkles fs-4 animate-pulse"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="sbStudioModalLabel">
                            ระบบจัดการแบนเนอร์บริการประชาชนและลิงก์ภายนอก <span class="badge bg-warning text-dark fs-7 ms-2">On-Page CMS Studio</span>
                        </h5>
                        <small class="text-info">แก้ไขข้อมูลป้ายโฆษณาบริการและใส่จุดเชื่อมโยง (URLs) บนหน้าเว็บจริงได้ทันที</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= base_url('admin/service-banners') ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-external-link-alt me-1"></i> เปิดในออฟฟิศแอดมิน (Full Studio)
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4 p-md-5">
                <div class="row g-4">
                    <!-- Left: List of Banners -->
                    <div class="col-lg-5 border-end-lg pe-lg-4" style="border-color: var(--glass-border) !important;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-primary m-0"><i class="fa-solid fa-list-check me-2"></i>รายการแบนเนอร์ปัจจุบัน</h6>
                            <button type="button" onclick="ServiceBannerStudio.createNew()" class="btn btn-sm btn-success fw-bold rounded-pill px-3 shadow-sm d-flex align-items-center gap-1" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                                <i class="fa-solid fa-plus"></i> เพิ่มรายการใหม่
                            </button>
                        </div>

                        <div id="sbStudioList" class="d-flex flex-column gap-2 overflow-y-auto pe-2" style="max-height: 480px;">
                            <!-- Rendered via JS -->
                        </div>
                    </div>

                    <!-- Right: Editor Form -->
                    <div class="col-lg-7">
                        <form id="sbStudioForm" onsubmit="event.preventDefault(); ServiceBannerStudio.save();">
                            <input type="hidden" id="sb_id" name="id" value="">
                            <input type="hidden" id="sb_sort_order" name="sort_order" value="">
                            <input type="hidden" id="sb_image_path" name="image" value="">

                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--glass-border) !important;">
                                <h6 class="fw-bold m-0" id="sbFormHeading" style="color: var(--text-primary);"><i class="fa-solid fa-pen-to-square text-warning me-2"></i>เพิ่ม / แก้ไขรายละเอียดแบนเนอร์บริการ</h6>
                                <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="sb_active" name="active" checked style="cursor: pointer;">
                                    <label class="form-check-label small fw-bold text-success" for="sb_active" id="sb_active_label" style="cursor: pointer;">
                                        🟢 แสดงผลบนหน้าเว็บ
                                    </label>
                                </div>
                            </div>

                            <!-- Image Cover preview Box -->
                            <div class="mb-4 text-center">
                                <div class="position-relative rounded-4 overflow-hidden mb-2 border shadow-sm mx-auto" style="max-height: 190px; background: #0f172a; border-color: rgba(255,255,255,0.2) !important;">
                                    <img src="<?= base_url('assets/images/banners/eservice_citizen.png') ?>" id="sb_preview_img" class="img-fluid w-100" style="max-height: 190px; object-fit: cover;" alt="Preview Banner">
                                    <div class="position-absolute bottom-0 start-0 end-0 p-2 d-flex align-items-center justify-content-center" style="background: rgba(15, 23, 42, 0.75);">
                                        <label class="btn btn-warning btn-sm fw-bold rounded-pill px-4 m-0 shadow text-dark" style="cursor: pointer;">
                                            <i class="fa-solid fa-camera me-1"></i> เปลี่ยนรูปภาพแบนเนอร์ (Upload Image)...
                                            <input type="file" class="d-none" id="sb_image_input" accept="image/*" onchange="ServiceBannerStudio.handleUpload(this)">
                                        </label>
                                    </div>
                                </div>
                                <small class="text-muted"><i class="fa-solid fa-circle-info me-1"></i>แนะนำขนาดภาพ 1280x600 px หรืออัตราส่วน 16:9 เพื่อความคมชัดทุกหน้าจอ</small>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold text-primary mb-1"><i class="fa-solid fa-heading me-1"></i> ชื่อบริการ / ป้ายโฆษณา (Title) <span class="text-danger">*</span></label>
                                    <input type="text" id="sb_title" class="form-control modern-input fw-bold" required placeholder="เช่น ระบบชำระภาษีท้องถิ่นออนไลน์ e-Tax">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold text-info mb-1"><i class="fa-solid fa-tag me-1"></i> ป้ายหมวดหมู่</label>
                                    <input type="text" id="sb_badge" class="form-control modern-input" placeholder="เช่น บริการ 24 ชม.">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-secondary mb-1">สีป้าย</label>
                                    <select id="sb_badge_color" class="form-select modern-input text-center fw-bold">
                                        <option value="success">🟢 เขียว</option>
                                        <option value="primary">🔵 น้ำเงิน</option>
                                        <option value="warning">🟡 เหลือง</option>
                                        <option value="danger">🔴 แดง</option>
                                        <option value="info">ฟ้า</option>
                                        <option value="dark">⚫ ดำ</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-bold text-secondary mb-1"><i class="fa-solid fa-align-left me-1"></i> รายละเอียดคำอธิบายย่อ (Description)</label>
                                    <input type="text" id="sb_desc" class="form-control modern-input" placeholder="อธิบายวัตถุประสงค์และการให้บริการประชาชนแบบย่อ">
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label small fw-bold text-warning mb-1"><i class="fa-solid fa-globe me-1"></i> ใส่จุดเชื่อมโยง (Destination URL / Link) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark text-warning border-secondary"><i class="fa-solid fa-link"></i></span>
                                        <input type="text" id="sb_url" class="form-control modern-input text-info fw-bold" required placeholder="https://www.egov.go.th หรือ #pdpa">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-window-restore me-1"></i> เปิดในหน้าต่าง</label>
                                    <select id="sb_target" class="form-select modern-input">
                                        <option value="_blank">🌐 แท็บใหม่ (_blank)</option>
                                        <option value="_self">📱 หน้าเดิม (_self)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Form Action Footer -->
                            <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-end gap-3" style="border-color: var(--glass-border) !important;">
                                <button type="button" onclick="ServiceBannerStudio.deleteCurrent()" id="sbDeleteBtn" class="btn btn-outline-danger px-4 py-2 rounded-pill fw-bold d-none">
                                    <i class="fa-solid fa-trash me-1"></i> ลบรายการนี้
                                </button>
                                <button type="submit" class="btn-modern px-5 py-2 shadow-lg">
                                    <i class="fa-solid fa-floppy-disk me-2"></i> บันทึกและเผยแพร่ออนไลน์ทันที
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.ServiceBannerStudio = {
    modalInstance: null,
    listData: [],
    currentItem: null,

    init: function() {
        const modalEl = document.getElementById('serviceBannerStudioModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            this.modalInstance = new bootstrap.Modal(modalEl);
        }
        
        // Setup active switch toggle label updates
        const sw = document.getElementById('sb_active');
        if (sw) {
            sw.addEventListener('change', function() {
                const lbl = document.getElementById('sb_active_label');
                if (this.checked) {
                    lbl.innerHTML = '🟢 แสดงผลบนหน้าเว็บ';
                    lbl.className = 'form-check-label small fw-bold text-success';
                } else {
                    lbl.innerHTML = '🔴 ซ่อนป้ายนี้';
                    lbl.className = 'form-check-label small fw-bold text-danger';
                }
            });
        }
    },

    open: function(id = null) {
        if (!this.modalInstance) this.init();
        if (!this.modalInstance) {
            App.toast('ไม่สามารถโหลดระบบ Studio ได้ กรุณาลองรีเฟรชหน้าต่าง', 'error');
            return;
        }

        this.loadList(() => {
            if (id) {
                const target = this.listData.find(x => String(x.id) === String(id));
                if (target) {
                    this.selectItem(target);
                } else {
                    this.createNew();
                }
            } else if (this.listData.length > 0) {
                this.selectItem(this.listData[0]);
            } else {
                this.createNew();
            }
            this.modalInstance.show();
        });
    },

    loadList: function(callback) {
        fetch('<?= base_url("service-banners/get-all-json") ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.listData = data.banners || [];
                this.renderList();
                if (typeof callback === 'function') callback();
            }
        })
        .catch(err => console.error(err));
    },

    renderList: function() {
        const box = document.getElementById('sbStudioList');
        if (!box) return;

        if (this.listData.length === 0) {
            box.innerHTML = '<div class="p-4 text-center text-muted">ยังไม่มีรายการแบนเนอร์บริการ</div>';
            return;
        }

        let html = '';
        this.listData.forEach((item, idx) => {
            const imgUrl = (item.image && (item.image.startsWith('http') || item.image.startsWith('data:')))
                ? item.image
                : '<?= base_url() ?>' + (item.image || 'assets/images/banners/eservice_citizen.png');
            
            const isSelected = this.currentItem && String(this.currentItem.id) === String(item.id);
            
            html += `
            <div onclick="ServiceBannerStudio.selectItemById('${item.id}')" class="p-3 rounded-4 transition-all mb-2 shadow-sm d-flex align-items-center gap-3" style="cursor: pointer; background: ${isSelected ? 'rgba(16, 185, 129, 0.15)' : 'var(--glass-bg)'}; border: 2px solid ${isSelected ? '#10b981' : 'var(--glass-border)'};">
                <img src="${imgUrl}" class="rounded-3 flex-shrink-0" style="width: 75px; height: 50px; object-fit: cover; border: 1px solid rgba(255,255,255,0.2);">
                <div class="flex-grow-1 text-truncate">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge bg-${item.badge_color || 'primary'}">${item.badge || 'บริการ'}</span>
                        <small class="${item.active ? 'text-success fw-bold' : 'text-danger fw-bold'}" style="font-size: 0.75rem;">${item.active ? '● ออนไลน์' : '● ซ่อน'}</small>
                    </div>
                    <h6 class="fw-bold mb-0 text-truncate" style="color: var(--text-primary); font-size: 0.95rem;">${item.title || 'ไม่มีชื่อบริการ'}</h6>
                    <small class="text-info text-truncate d-block"><i class="fa-solid fa-link me-1"></i>${item.url || '#'}</small>
                </div>
                <i class="fa-solid fa-chevron-right text-muted"></i>
            </div>
            `;
        });
        box.innerHTML = html;
    },

    selectItemById: function(id) {
        const target = this.listData.find(x => String(x.id) === String(id));
        if (target) this.selectItem(target);
    },

    selectItem: function(item) {
        this.currentItem = item;
        this.renderList(); // highlight

        document.getElementById('sb_id').value = item.id || '';
        document.getElementById('sb_sort_order').value = item.sort_order || '';
        document.getElementById('sb_title').value = item.title || '';
        document.getElementById('sb_badge').value = item.badge || '';
        document.getElementById('sb_badge_color').value = item.badge_color || 'success';
        document.getElementById('sb_desc').value = item.desc || '';
        document.getElementById('sb_url').value = item.url || '';
        document.getElementById('sb_target').value = item.target || '_blank';
        document.getElementById('sb_image_path').value = item.image || '';

        const imgUrl = (item.image && (item.image.startsWith('http') || item.image.startsWith('data:')))
            ? item.image
            : '<?= base_url() ?>' + (item.image || 'assets/images/banners/eservice_citizen.png');
        document.getElementById('sb_preview_img').src = imgUrl;

        const sw = document.getElementById('sb_active');
        sw.checked = (item.active !== false && item.active !== '0' && item.active !== 0);
        sw.dispatchEvent(new Event('change'));

        document.getElementById('sbFormHeading').innerHTML = `<i class="fa-solid fa-pen-to-square text-warning me-2"></i>แก้ไขป้ายบริการ [<code>${item.id}</code>]`;
        document.getElementById('sbDeleteBtn').classList.remove('d-none');
    },

    createNew: function() {
        this.currentItem = null;
        this.renderList();

        document.getElementById('sb_id').value = '';
        document.getElementById('sb_sort_order').value = this.listData.length + 1;
        document.getElementById('sb_title').value = '';
        document.getElementById('sb_badge').value = 'บริการออนไลน์';
        document.getElementById('sb_badge_color').value = 'success';
        document.getElementById('sb_desc').value = '';
        document.getElementById('sb_url').value = 'https://www.egov.go.th';
        document.getElementById('sb_target').value = '_blank';
        document.getElementById('sb_image_path').value = 'assets/images/banners/eservice_citizen.png';
        document.getElementById('sb_preview_img').src = '<?= base_url("assets/images/banners/eservice_citizen.png") ?>';

        const sw = document.getElementById('sb_active');
        sw.checked = true;
        sw.dispatchEvent(new Event('change'));

        document.getElementById('sbFormHeading').innerHTML = `<i class="fa-solid fa-plus-circle text-success me-2"></i>เพิ่มรายการแบนเนอร์บริการและลิงก์ใหม่`;
        document.getElementById('sbDeleteBtn').classList.add('d-none');
        document.getElementById('sb_title').focus();
    },

    handleUpload: function(input) {
        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        const loader = App.showLoader ? App.showLoader('กำลังอัปโหลดรูปภาพ...') : null;
        fetch('<?= base_url("service-banners/upload-image") ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (loader && App.hideLoader) App.hideLoader(loader);
            if (data.status === 'success') {
                document.getElementById('sb_image_path').value = data.path;
                document.getElementById('sb_preview_img').src = data.url;
                App.toast('อัปโหลดรูปภาพแบนเนอร์สำเร็จ', 'success');
            } else {
                App.toast(data.message || 'อัปโหลดภาพล้มเหลว', 'error');
            }
        })
        .catch(err => {
            if (loader && App.hideLoader) App.hideLoader(loader);
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    },

    save: function() {
        const form = document.getElementById('sbStudioForm');
        const formData = new FormData(form);
        // Ensure active state boolean is passed correctly
        formData.set('active', document.getElementById('sb_active').checked ? '1' : '0');

        const loader = App.showLoader ? App.showLoader('กำลังบันทึกและอัปเดตหน้าเว็บ...') : null;
        fetch('<?= base_url("service-banners/save-inline") ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (loader && App.hideLoader) App.hideLoader(loader);
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                setTimeout(() => location.reload(), 700);
            } else {
                App.toast(data.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
            }
        })
        .catch(err => {
            if (loader && App.hideLoader) App.hideLoader(loader);
            App.toast('เชื่อมต่อเซิร์ฟเวอร์ล้มเหลว', 'error');
        });
    },

    deleteCurrent: function() {
        const id = document.getElementById('sb_id').value;
        if (!id) return;

        if (confirm('คุณแน่ใจหรือไม่ที่จะลบป้ายแบนเนอร์และลิงก์บริการนี้ถาวร?')) {
            const loader = App.showLoader ? App.showLoader('กำลังลบรายการ...') : null;
            fetch('<?= base_url("service-banners/delete/") ?>' + id, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (loader && App.hideLoader) App.hideLoader(loader);
                if (data.status === 'success') {
                    App.toast(data.message, 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    App.toast(data.message || 'ลบล้มเหลว', 'error');
                }
            })
            .catch(err => {
                if (loader && App.hideLoader) App.hideLoader(loader);
            });
        }
    }
};
</script>
<?php endif; ?>

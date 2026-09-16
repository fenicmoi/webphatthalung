<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-bullhorn text-primary me-2"></i>จัดการแบนเนอร์ประชาสัมพันธ์ & หน่วยงานสัมพันธ์ (3D Carousel Banners)</h4>
        <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
            เพิ่ม ลบ และอัปโหลดภาพแบนเนอร์ 3D Coverflow พร้อมตั้งค่าลิงก์เว็บไซต์ปลายทาง (URL) ที่แสดงบนหน้าแรกเว็บไซต์ <span class="badge bg-success ms-2">Real-Time Sync</span>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-outline-warning fw-bold px-3 py-2" onclick="resetServiceBanners()" style="border-radius: 12px;">
            <i class="fa-solid fa-rotate-left me-1"></i> คืนค่าเริ่มต้น
        </button>
        <button type="button" class="btn btn-success fw-bold px-4 py-2 shadow-sm d-flex align-items-center gap-2" onclick="addNewBanner()" style="border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); border: none;">
            <i class="fa-solid fa-plus-circle fs-5"></i> เพิ่มแบนเนอร์ใหม่
        </button>
        <button type="button" class="btn-modern px-4 py-2 shadow-lg" onclick="saveAllServiceBanners()">
            <i class="fa-solid fa-floppy-disk me-2"></i> บันทึกและแสดงผลทันที
        </button>
    </div>
</div>

<!-- Quick Instruction Box -->
<div class="p-4 rounded-4 mb-4 shadow-sm" style="background: var(--glass-bg); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="d-flex align-items-center gap-3">
        <div class="p-3 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6; width: 52px; height: 52px; font-size: 1.5rem;">
            <i class="fa-solid fa-link"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1" style="color: var(--text-primary);">คำแนะนำในการตั้งค่าลิงก์เชื่อมโยง (URLs & Links Setup)</h6>
            <p class="small text-secondary m-0">
                • <b>ลิงก์เว็บไซต์ภายนอก หรือระบบเฉพาะทาง:</b> ระบุ URL เต็ม เช่น <code>https://www.egov.go.th</code> หรือ <code>https://tax.rd.go.th</code><br>
                • <b>ลิงก์ภายในพอร์ตัลหรือแท็บในหน้า:</b> ระบุรหัสสมอเรือ เช่น <code>#pdpa</code> (ยื่นเรื่อง PDPA), <code>#services</code> (บริการ e-Service), หรือ <code><?= base_url('news') ?></code>
            </p>
        </div>
    </div>
</div>

<!-- Banner Cards Management Grid -->
<div id="serviceBannersList" class="row g-4">
    <!-- Rendered by Javascript -->
</div>

<!-- Empty State -->
<div id="emptyBannersState" class="text-center py-5 d-none">
    <div class="p-5 rounded-4" style="background: var(--glass-bg); border: 2px dashed var(--glass-border);">
        <i class="fa-solid fa-folder-open fs-1 text-muted mb-3 d-block"></i>
        <h5 class="fw-bold text-secondary">ยังไม่มีป้ายแบนเนอร์บริการในขณะนี้</h5>
        <p class="text-muted mb-4">คลิกปุ่มด้านล่างเพื่อเพิ่มป้ายแบนเนอร์และลิงก์สำหรับให้บริการประชาชน</p>
        <button type="button" class="btn btn-primary px-4 py-2 rounded-pill fw-bold" onclick="addNewBanner()">
            <i class="fa-solid fa-plus me-2"></i> เพิ่มแบนเนอร์บริการใหม่
        </button>
    </div>
</div>

<!-- Modal: เพิ่มแบนเนอร์ประชาสัมพันธ์ใหม่ -->
<div class="modal fade" id="modalAddBanner" tabindex="-1" aria-labelledby="modalAddBannerLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden" style="background: #ffffff;">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-plus-circle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalAddBannerLabel">เพิ่มแบนเนอร์ประชาสัมพันธ์ใหม่</h5>
                        <small style="color: #a7f3d0; font-size: 0.85rem;">สำหรับแสดงผลใน 3D Carousel Slide บนหน้าแรกของเว็บไซต์</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background-color: #f8fafc;">
                <form id="formAddBanner" onsubmit="event.preventDefault(); submitNewBannerModal();">
                    <!-- Image Preview & Upload Box -->
                    <div class="card p-3 mb-4 rounded-4 border shadow-sm" style="background: #0f172a; color: #fff;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold" style="font-size: 1rem;"><i class="fa-solid fa-image text-success me-2"></i>รูปภาพแบนเนอร์ <span class="text-danger">*</span></span>
                            <span class="badge bg-secondary text-white px-2 py-1">ขนาดแนะนำ ~1200 x 500 px (อัตราส่วน 2.4:1)</span>
                        </div>
                        
                        <div class="rounded-3 overflow-hidden position-relative text-center d-flex align-items-center justify-content-center mb-3" style="background: #1e293b; min-height: 190px; max-height: 240px; border: 2px dashed rgba(255,255,255,0.2);">
                            <img id="newBannerPreview" src="<?= base_url('assets/images/banners/eservice_citizen.png') ?>" alt="Preview" style="max-height: 220px; max-width: 100%; object-fit: contain; border-radius: 8px;">
                            <div id="uploadSpinner" class="position-absolute top-50 start-50 translate-middle d-none text-center bg-dark p-3 rounded-3 shadow" style="--bs-bg-opacity: .85;">
                                <div class="spinner-border text-success mb-2" role="status"></div>
                                <div class="text-white small fw-bold">กำลังอัปโหลดรูปภาพ...</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <input type="file" id="newBannerFileInput" class="form-control form-control-sm bg-dark text-white border-secondary" accept="image/*" onchange="previewAndUploadNewBanner(event)">
                            <input type="hidden" id="newBannerImagePath" value="assets/images/banners/eservice_citizen.png">
                        </div>
                        <small class="text-secondary mt-1 d-block"><i class="fa-solid fa-circle-info me-1"></i> สามารถเลือกไฟล์จากคอมพิวเตอร์ (.jpg, .png, .webp) ระบบจะอัปโหลดอัตโนมัติทันที</small>
                    </div>

                    <!-- Fields Row -->
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-dark mb-1" style="font-size: 1rem;"><i class="fa-solid fa-heading text-primary me-1"></i> ชื่อแบนเนอร์ / ข้อความกำกับ (Title) <span class="text-danger">*</span></label>
                            <input type="text" id="newBannerTitle" class="form-control form-control-lg border-2" placeholder="เช่น ระบบบริการภาษีท้องถิ่นออนไลน์ หรือ ศูนย์รับเรื่องร้องเรียน" required style="font-size: 1rem;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-1" style="font-size: 1rem;"><i class="fa-solid fa-tag text-info me-1"></i> ป้ายกำกับ (Badge)</label>
                            <div class="input-group">
                                <input type="text" id="newBannerBadge" class="form-control border-2" placeholder="เช่น บริการใหม่" value="บริการออนไลน์">
                                <select id="newBannerBadgeColor" class="form-select border-2 text-center fw-bold" style="max-width: 90px;" title="เลือกสีป้าย">
                                    <option value="primary">🔵 ฟ้า</option>
                                    <option value="success" selected>🟢 เขียว</option>
                                    <option value="warning">🟡 เหลือง</option>
                                    <option value="danger">🔴 แดง</option>
                                    <option value="info">🔷 คราม</option>
                                    <option value="dark">⚫ ดำ</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark mb-1" style="font-size: 1rem;"><i class="fa-solid fa-globe text-warning me-1"></i> ลิงก์เว็บไซต์ปลายทาง (Destination URL) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-2 text-warning"><i class="fa-solid fa-link"></i></span>
                                <input type="text" id="newBannerUrl" class="form-control border-2 text-primary fw-bold" placeholder="https://www.egov.go.th หรือ #pdpa หรือ /news" required value="https://" style="font-size: 1rem;">
                            </div>
                            <div class="form-text text-muted">ใส่ URL เว็บไซต์ภายนอก เช่น <code>https://...</code> หรือลิงก์ภายในหน้า เช่น <code>#pdpa</code>, <code>#services</code></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark mb-1"><i class="fa-solid fa-window-restore text-secondary me-1"></i> รูปแบบการเปิดลิงก์ (Target)</label>
                            <select id="newBannerTarget" class="form-select border-2">
                                <option value="_blank" selected>🌐 เปิดในแท็บใหม่ (_blank) [แนะนำสำหรับเว็บภายนอก]</option>
                                <option value="_self">📱 เปิดในหน้าเดิม (_self) [สำหรับหน้าภายใน]</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i> สถานะการแสดงผล</label>
                            <select id="newBannerActive" class="form-select border-2">
                                <option value="1" selected>🟢 เปิดใช้งานทันที (แสดงบน 3D Carousel หน้าแรก)</option>
                                <option value="0">🔴 ซ่อนไว้ชั่วคราว (ยังไม่แสดงบนหน้าแรก)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-dark mb-1"><i class="fa-solid fa-align-left text-muted me-1"></i> คำอธิบายย่อ (Description / รายละเอียดบริการ)</label>
                            <input type="text" id="newBannerDesc" class="form-control border-2" placeholder="รายละเอียดหรือข้อมูลเสริมของบริการนี้ (ถ้ามี)">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-3 px-4 d-flex justify-content-between" style="background: #f1f5f9; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-outline-secondary px-4 py-2.5 rounded-pill fw-bold" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark me-1"></i> ยกเลิก
                </button>
                <button type="button" class="btn btn-success px-4 py-2.5 rounded-pill fw-bold shadow-sm d-flex align-items-center gap-2" onclick="submitNewBannerModal()" style="background: linear-gradient(135deg, #10b981, #059669); border: none; font-size: 1.05rem;">
                    <i class="fa-solid fa-check-circle fs-5"></i> บันทึกและเพิ่มแบนเนอร์ทันที
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let serviceBanners = <?= json_encode($banners ?? [], JSON_UNESCAPED_UNICODE) ?>;

function renderBannersList() {
    const container = document.getElementById('serviceBannersList');
    const emptyState = document.getElementById('emptyBannersState');
    
    if (!serviceBanners || serviceBanners.length === 0) {
        container.innerHTML = '';
        emptyState.classList.remove('d-none');
        return;
    }

    emptyState.classList.add('d-none');
    
    let html = '';
    serviceBanners.forEach((item, idx) => {
        const imgUrl = (item.image && (item.image.startsWith('http') || item.image.startsWith('data:'))) 
            ? item.image 
            : '<?= base_url() ?>' + (item.image || 'assets/images/banners/eservice_citizen.png');
            
        html += `
        <div class="col-lg-6">
            <div class="glass-card p-4 rounded-4 h-100 shadow-sm d-flex flex-column justify-content-between position-relative transition-all" style="border: 2px solid ${item.active ? 'var(--glass-border)' : 'rgba(239, 68, 68, 0.4)'}; background: ${item.active ? 'var(--glass-bg)' : 'rgba(239, 68, 68, 0.04)'};">
                <div>
                    <!-- Header Action Row -->
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3" style="border-color: var(--glass-border) !important;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-pill px-3 py-1 fw-bold">ลำดับ #${idx + 1}</span>
                            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="active_switch_${idx}" ${item.active ? 'checked' : ''} onchange="toggleActive(${idx}, this.checked)" style="cursor: pointer;">
                                <label class="form-check-label small fw-bold ${item.active ? 'text-success' : 'text-danger'}" for="active_switch_${idx}" style="cursor: pointer;">
                                    ${item.active ? '🟢 แสดงผลบนหน้าเว็บ' : '🔴 ซ่อนป้ายนี้'}
                                </label>
                            </div>
                        </div>
                        <div class="d-flex gap-1">
                            ${idx > 0 ? `<button type="button" onclick="moveOrder(${idx}, -1)" class="btn btn-sm btn-outline-secondary px-2 py-1" title="ย้ายขึ้น"><i class="fa-solid fa-arrow-up"></i></button>` : ''}
                            ${idx < serviceBanners.length - 1 ? `<button type="button" onclick="moveOrder(${idx}, 1)" class="btn btn-sm btn-outline-secondary px-2 py-1" title="ย้ายลง"><i class="fa-solid fa-arrow-down"></i></button>` : ''}
                            <button type="button" onclick="deleteBanner(${idx})" class="btn btn-sm btn-outline-danger px-2 py-1 ms-1" title="ลบแบนเนอร์นี้"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    </div>

                    <!-- Banner Image Preview & Upload Box -->
                    <div class="position-relative mb-3 rounded-4 overflow-hidden shadow-sm" style="height: 200px; background: #0f172a; border: 1px solid rgba(255,255,255,0.15);">
                        <img src="${imgUrl}" id="preview_img_${idx}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;" alt="${item.title || 'Service Banner'}">
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 d-flex align-items-end justify-content-between" style="background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, transparent 100%);">
                            <div class="text-truncate pe-2">
                                <span class="badge bg-${item.badge_color || 'primary'} mb-1">${item.badge || 'บริการออนไลน์'}</span>
                                <h6 class="text-white fw-bold m-0 text-truncate">${item.title || 'ไม่ระบุชื่อบริการ'}</h6>
                            </div>
                            <label class="btn btn-warning btn-sm fw-bold rounded-pill px-3 m-0 shadow text-dark flex-shrink-0" style="cursor: pointer;">
                                <i class="fa-solid fa-camera me-1"></i> เปลี่ยนรูปภาพ...
                                <input type="file" class="d-none" accept="image/*" onchange="uploadBannerImage(event, ${idx})">
                            </label>
                        </div>
                    </div>

                    <!-- Input Fields Form -->
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-primary mb-1"><i class="fa-solid fa-heading me-1"></i> ชื่อป้ายแบนเนอร์ / บริการ (Title)</label>
                            <input type="text" class="form-control modern-input" value="${item.title || ''}" onchange="updateProp(${idx}, 'title', this.value); renderBannersList();" placeholder="เช่น ระบบชำระภาษีออนไลน์ e-Tax">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-info mb-1"><i class="fa-solid fa-tag me-1"></i> ป้ายสถานะ (Badge)</label>
                            <div class="input-group">
                                <input type="text" class="form-control modern-input" value="${item.badge || ''}" onchange="updateProp(${idx}, 'badge', this.value); renderBannersList();" placeholder="เช่น บริการ 24 ชม.">
                                <select class="form-select modern-input text-center fw-bold" style="max-width: 85px;" onchange="updateProp(${idx}, 'badge_color', this.value); renderBannersList();" title="เลือกสีของป้ายสถานะ">
                                    <option value="primary" ${item.badge_color === 'primary' ? 'selected' : ''}>🔵</option>
                                    <option value="success" ${item.badge_color === 'success' || !item.badge_color ? 'selected' : ''}>🟢</option>
                                    <option value="warning" ${item.badge_color === 'warning' ? 'selected' : ''}>🟡</option>
                                    <option value="danger" ${item.badge_color === 'danger' ? 'selected' : ''}>🔴</option>
                                    <option value="info" ${item.badge_color === 'info' ? 'selected' : ''}>cyan</option>
                                    <option value="dark" ${item.badge_color === 'dark' ? 'selected' : ''}>⚫</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-secondary mb-1"><i class="fa-solid fa-align-left me-1"></i> คำอธิบายย่อ (Description / Subtitle)</label>
                            <input type="text" class="form-control modern-input" value="${item.desc || ''}" onchange="updateProp(${idx}, 'desc', this.value)" placeholder="รายละเอียดสั้นๆ ของบริการและวัตถุประสงค์">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-warning mb-1"><i class="fa-solid fa-globe me-1"></i> ลิงก์ปลายทาง (Destination URL / Link)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-warning border-secondary"><i class="fa-solid fa-link"></i></span>
                                <input type="text" class="form-control modern-input text-info fw-bold" value="${item.url || ''}" onchange="updateProp(${idx}, 'url', this.value)" placeholder="https://... หรือ #pdpa หรือรหัสสมอเรือ">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted mb-1"><i class="fa-solid fa-window-restore me-1"></i> วิธีการเปิดลิงก์ (Target)</label>
                            <select class="form-select modern-input" onchange="updateProp(${idx}, 'target', this.value)">
                                <option value="_blank" ${item.target === '_blank' || !item.target ? 'selected' : ''}>🌐 แท็บใหม่ (_blank)</option>
                                <option value="_self" ${item.target === '_self' ? 'selected' : ''}>📱 หน้าต่างเดิม (_self)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between" style="border-color: var(--glass-border) !important;">
                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>ไอดีแบนเนอร์: <code>${item.id}</code></small>
                    <a href="${item.url || '#'}" target="${item.target || '_blank'}" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold">
                        <i class="fa-solid fa-up-right-from-square me-1"></i> ทดลองกดเปิดลิงก์
                    </a>
                </div>
            </div>
        </div>
        `;
    });

    container.innerHTML = html;
}

function updateProp(idx, key, value) {
    if (serviceBanners[idx]) {
        serviceBanners[idx][key] = value;
    }
}

function toggleActive(idx, isChecked) {
    if (serviceBanners[idx]) {
        serviceBanners[idx].active = isChecked;
        renderBannersList();
    }
}

function moveOrder(idx, dir) {
    const newIdx = idx + dir;
    if (newIdx < 0 || newIdx >= serviceBanners.length) return;
    const temp = serviceBanners[idx];
    serviceBanners[idx] = serviceBanners[newIdx];
    serviceBanners[newIdx] = temp;
    // re-assign sort_order
    serviceBanners.forEach((b, i) => { b.sort_order = i + 1; });
    renderBannersList();
    App.toast('↔️ ยับเปลี่ยนลำดับแบนเนอร์แล้ว กรุณากดปุ่มบันทึกเพื่อให้อนุญาตผลจริง', 'info');
}

function addNewBanner() {
    // Reset form fields
    document.getElementById('newBannerTitle').value = '';
    document.getElementById('newBannerBadge').value = 'บริการออนไลน์';
    document.getElementById('newBannerBadgeColor').value = 'success';
    document.getElementById('newBannerUrl').value = 'https://';
    document.getElementById('newBannerTarget').value = '_blank';
    document.getElementById('newBannerActive').value = '1';
    document.getElementById('newBannerDesc').value = '';
    document.getElementById('newBannerImagePath').value = 'assets/images/banners/eservice_citizen.png';
    document.getElementById('newBannerPreview').src = '<?= base_url("assets/images/banners/eservice_citizen.png") ?>';
    document.getElementById('newBannerFileInput').value = '';
    
    // Show Modal
    const modalEl = document.getElementById('modalAddBanner');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
}

function previewAndUploadNewBanner(e) {
    const file = e.target.files[0];
    if (!file) return;

    // Show local preview immediately
    const preview = document.getElementById('newBannerPreview');
    preview.src = URL.createObjectURL(file);
    
    const spinner = document.getElementById('uploadSpinner');
    spinner.classList.remove('d-none');
    
    const formData = new FormData();
    formData.append('image', file);
    
    fetch('<?= base_url("admin/service-banners/upload") ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        spinner.classList.add('d-none');
        if (data.status === 'success') {
            document.getElementById('newBannerImagePath').value = data.path;
            App.toast('อัปโหลดรูปภาพสำเร็จ', 'success');
        } else {
            App.toast(data.message || 'เกิดข้อผิดพลาดในการอัปโหลด', 'error');
        }
    })
    .catch(err => {
        spinner.classList.add('d-none');
        console.error(err);
        App.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์อัปโหลดได้', 'error');
    });
}

function submitNewBannerModal() {
    const title = document.getElementById('newBannerTitle').value.trim();
    const url = document.getElementById('newBannerUrl').value.trim();
    
    if (!title) {
        App.toast('กรุณากรอกชื่อแบนเนอร์', 'error');
        document.getElementById('newBannerTitle').focus();
        return;
    }
    if (!url || url === 'https://') {
        App.toast('กรุณาระบุลิงก์ปลายทาง (URL)', 'error');
        document.getElementById('newBannerUrl').focus();
        return;
    }
    
    const newId = 'sb-' + Date.now().toString().slice(-5);
    const newBanner = {
        id: newId,
        title: title,
        desc: document.getElementById('newBannerDesc').value.trim(),
        badge: document.getElementById('newBannerBadge').value.trim() || 'บริการออนไลน์',
        badge_color: document.getElementById('newBannerBadgeColor').value,
        url: url,
        target: document.getElementById('newBannerTarget').value,
        image: document.getElementById('newBannerImagePath').value || 'assets/images/banners/eservice_citizen.png',
        active: document.getElementById('newBannerActive').value === '1',
        sort_order: 1 // We'll put it at the top
    };
    
    // Add to top of list
    serviceBanners.unshift(newBanner);
    
    // Re-index sort order
    serviceBanners.forEach((b, i) => { b.sort_order = i + 1; });
    
    renderBannersList();
    
    // Close modal
    const modalEl = document.getElementById('modalAddBanner');
    const modal = bootstrap.Modal.getInstance(modalEl);
    if (modal) modal.hide();
    
    // Auto save
    saveAllServiceBanners();
    App.toast('🎉 เพิ่มแบนเนอร์เรียบร้อยและแสดงผลบนหน้าเว็บทันที!', 'success');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function deleteBanner(idx) {
    if (confirm(`คุณต้องการลบแบนเนอร์ "${serviceBanners[idx].title}" ใช่หรือไม่?`)) {
        serviceBanners.splice(idx, 1);
        serviceBanners.forEach((b, i) => { b.sort_order = i + 1; });
        renderBannersList();
        App.toast('🗑️ ลบออกจากรายการชั่วคราวแล้ว กรุณากดปุ่มบันทึกเพื่อยืนยัน', 'warning');
    }
}

function uploadBannerImage(e, idx) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('image', file);

    const loader = App.showLoader ? App.showLoader('กำลังอัปโหลดรูปภาพ...') : null;
    
    fetch('<?= base_url("admin/service-banners/upload") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (loader && App.hideLoader) App.hideLoader(loader);
        if (data.status === 'success') {
            serviceBanners[idx].image = data.path;
            renderBannersList();
            App.toast(data.message, 'success');
        } else {
            App.toast(data.message || 'เกิดข้อผิดพลาดในการอัปโหลด', 'error');
        }
    })
    .catch(err => {
        if (loader && App.hideLoader) App.hideLoader(loader);
        console.error(err);
        App.toast('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์อัปโหลดได้', 'error');
    });
}

function saveAllServiceBanners() {
    // Recheck sort order
    serviceBanners.forEach((b, i) => { b.sort_order = i + 1; });

    const formData = new FormData();
    formData.append('banners_json', JSON.stringify(serviceBanners));

    const loader = App.showLoader ? App.showLoader('กำลังบันทึกข้อมูลและซิงค์ขึ้นสู่เว็บจริง...') : null;
    
    fetch('<?= base_url("admin/service-banners/save") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (loader && App.hideLoader) App.hideLoader(loader);
        if (data.status === 'success') {
            App.toast(data.message, 'success');
        } else {
            App.toast(data.message || 'บันทึกข้อมูลล้มเหลว', 'error');
        }
    })
    .catch(err => {
        if (loader && App.hideLoader) App.hideLoader(loader);
        console.error(err);
        App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อเครือข่าย', 'error');
    });
}

function resetServiceBanners() {
    if (confirm('คำเตือน: คุณแน่ใจหรือไม่ที่จะคืนค่าแบนเนอร์และลิงก์บริการทั้งหมดกลับสู่ค่าเริ่มต้นของระบบ?')) {
        const loader = App.showLoader ? App.showLoader('กำลังคืนค่าเริ่มต้น...') : null;
        fetch('<?= base_url("admin/service-banners/reset") ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (loader && App.hideLoader) App.hideLoader(loader);
            if (data.status === 'success') {
                App.toast(data.message, 'success');
                setTimeout(() => location.reload(), 800);
            }
        })
        .catch(err => {
            if (loader && App.hideLoader) App.hideLoader(loader);
            console.error(err);
        });
    }
}

// Initial Render on Load
document.addEventListener('DOMContentLoaded', () => {
    renderBannersList();
});
</script>
<?= $this->endSection() ?>

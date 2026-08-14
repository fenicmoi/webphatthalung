<?php
// Only render this component if user is logged in as Officer or Admin
if (!session()->get('isLoggedIn')) {
    return;
}
?>

<!-- Include Quill.js Rich Text Editor Resources -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<!-- Frontend On-Page News & PR Studio Modal -->
<div class="modal fade" id="newsStudioModal" tabindex="-1" aria-labelledby="newsStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content border-0" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 100%); color: #f8fafc;">
            
            <!-- Studio Header -->
            <div class="modal-header px-4 py-3 border-bottom d-flex align-items-center justify-content-between" style="background: rgba(15, 23, 42, 0.95); border-color: rgba(56, 189, 248, 0.3) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0ea5e9, #3b82f6); width: 48px; height: 48px;">
                        <i class="fa-solid fa-newspaper fs-3 text-white"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold m-0 text-white d-flex align-items-center gap-2">
                            <span>Phatthalung On-Page News Studio</span>
                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.75rem;"><i class="fa-solid fa-wand-magic-sparkles me-1"></i>Frontend CMS</span>
                        </h4>
                        <p class="text-info m-0 small" style="opacity: 0.9;">สตูดิโอบริหารจัดการข่าวและประชาสัมพันธ์บนหน้าเว็บจริง (Officer & Admin Exclusive)</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <input type="hidden" id="studioNewsId" value="">
                    <input type="hidden" id="studioCoverImage" value="">
                    
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold text-light" data-bs-dismiss="modal" style="border-color: rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-xmark me-1"></i> ปิดหน้านี้
                    </button>
                    <button type="button" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-lg" id="btnSaveNews" onclick="NewsStudio.save()" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%); border: none;">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> บันทึกและเผยแพร่ทันที (Publish)
                    </button>
                </div>
            </div>

            <!-- Studio Body -->
            <div class="modal-body p-4 p-md-5 overflow-auto">
                <div class="row g-4">
                    
                    <!-- Left Column: Article Meta & Rich Text Editor -->
                    <div class="col-xl-7 col-lg-7">
                        <div class="glass-card p-4 rounded-4 shadow-lg mb-4" style="background: rgba(30, 41, 59, 0.65); border: 1px solid rgba(255,255,255,0.12);">
                            
                            <!-- Title & Category Row -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-warning fs-6"><i class="fa-solid fa-heading me-1"></i> หัวข้อข่าว / ประกาศ <span class="text-danger">*</span></label>
                                <input type="text" id="studioNewsTitle" class="form-control form-control-lg rounded-3 text-white fw-bold px-3 py-2 shadow-sm" placeholder="พิมพ์หัวข้อข่าวสารหรือประกาศราชการที่นี่..." style="background: #090d16; border: 2px solid #38bdf8; font-size: 1.35rem;">
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold text-info"><i class="fa-solid fa-folder-tree me-1"></i> หมวดหมู่ข่าว</label>
                                    <select id="studioNewsCategory" class="form-select form-select-lg rounded-3 text-white fw-bold shadow-sm" style="background: #0f172a; border: 1px solid rgba(56, 189, 248, 0.5);">
                                        <?php 
                                        $cats = function_exists('get_news_categories') ? get_news_categories() : ['ประกาศราชการ / แจ้งเตือน', 'ข่าวกิจกรรมจังหวัด', 'ประกาศจัดซื้อจัดจ้าง (e-GP)', 'ส่งเสริมการท่องเที่ยว'];
                                        foreach ($cats as $c): ?>
                                            <option value="<?= esc($c) ?>"><?= esc($c) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-5 d-flex align-items-end">
                                    <button type="button" onclick="NewsStudio.addCategory()" class="btn btn-outline-warning w-100 py-2 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                        <i class="fa-solid fa-circle-plus"></i>
                                        <span>+ เพิ่มหมวดหมู่ใหม่</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Event Calendar Integration Switch & Panel -->
                            <div class="card p-3 rounded-4 shadow-sm mb-4 border" style="background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.4) !important;">
                                <div class="form-check form-switch d-flex align-items-center gap-3 m-0 p-0">
                                    <input class="form-check-input ms-0 mt-0 flex-shrink-0 cursor-pointer" type="checkbox" role="switch" id="studioIsEvent" onchange="NewsStudio.toggleEventPanel(this.checked)" style="width: 50px; height: 26px;">
                                    <label class="form-check-label fw-bold text-white cursor-pointer" for="studioIsEvent">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-calendar-days text-success fs-5"></i>
                                            <span class="fs-6">ตั้งค่าเป็นรายการ "ปฏิทินกิจกรรม / งานสำคัญ"</span>
                                            <span class="badge bg-success rounded-pill px-2 py-0 small">Event Calendar</span>
                                        </span>
                                        <small class="d-block text-light opacity-75 fw-normal mt-1" style="font-size: 0.82rem;">เปิดใช้เพื่อนำข่าวนี้ไปแสดงผลบนตารางปฏิทินกิจกรรมของจังหวัด พร้อมระบุวันที่จัดงานและพิกัดแผนที่</small>
                                    </label>
                                </div>

                                <!-- Event Details Collapsible Panel -->
                                <div id="studioEventPanel" class="mt-4 pt-3 border-top d-none" style="border-color: rgba(255,255,255,0.15) !important;">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-success small"><i class="fa-solid fa-clock me-1"></i> วันที่เริ่มต้น (Start Date) <span class="text-danger">*</span></label>
                                            <input type="date" id="studioEventStartDate" class="form-control rounded-3 text-white fw-bold" style="background: #0f172a; border: 1px solid rgba(16, 185, 129, 0.5);">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-success small"><i class="fa-solid fa-flag-checkered me-1"></i> วันที่สิ้นสุด (End Date)</label>
                                            <input type="date" id="studioEventEndDate" class="form-control rounded-3 text-white fw-bold" style="background: #0f172a; border: 1px solid rgba(16, 185, 129, 0.5);">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-warning small"><i class="fa-solid fa-location-dot me-1"></i> สถานที่จัดกิจกรรม / อาคาร / ชุมชน</label>
                                        <input type="text" id="studioEventLocation" class="form-control rounded-3 text-white" placeholder="เช่น บริเวณหน้าศาลากลางจังหวัดพัทลุง หรือ หาดแสนสุขลำปำ..." style="background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.2);">
                                    </div>
                                    <div>
                                        <label class="form-label fw-bold text-info small"><i class="fa-solid fa-map-location-dot me-1"></i> พิกัดแผนที่ GPS (Latitude, Longitude) หรือ ลิงก์ Google Maps</label>
                                        <input type="text" id="studioEventCoordinates" class="form-control rounded-3 text-white" placeholder="เช่น 7.6167, 100.0833 หรือ https://maps.app.goo.gl/..." style="background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.2);">
                                        <small class="text-white-50 mt-1 d-block"><i class="fa-solid fa-circle-info text-info me-1"></i> ข้อมูลนี้ช่วยให้ประชาชนคลิกปุ่ม "นำทางด้วยแผนที่" เพื่อเปิด Google Maps จากตารางปฏิทินได้ทันที</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Excerpt -->
                            <div class="mb-4">
                                <label class="form-label text-light small"><i class="fa-solid fa-align-left me-1"></i> ข้อความสรุปสั้น (แสดงบนหน้าบัตรข่าว)</label>
                                <textarea id="studioNewsSummary" class="form-control rounded-3 text-white shadow-sm" rows="2" placeholder="สรุปเนื้อหาสั้นกระชับไม่เกิน 2-3 บรรทัด (ปล่อยว่างไว้เพื่อดึงจากเนื้อหาอัตโนมัติ)..." style="background: rgba(0,0,0,0.35); border: 1px solid rgba(255,255,255,0.2);"></textarea>
                            </div>

                            <!-- Quill Rich Text Editor -->
                            <div>
                                <label class="form-label fw-bold text-warning fs-6 d-flex align-items-center justify-content-between">
                                    <span><i class="fa-solid fa-pen-to-square me-2"></i>เนื้อหาบทความฉบับเต็ม <span class="text-danger">*</span></span>
                                    <small class="text-info fw-normal"><i class="fa-regular fa-lightbulb me-1"></i>คลิกปุ่ม 'แทรกลงในเนื้อหา' จากคลังภาพด้านขวาเพื่อส่งรูปเข้ามาตรงเคอร์เซอร์</small>
                                </label>
                                <div class="rounded-4 overflow-hidden shadow-lg" style="border: 2px solid rgba(56, 189, 248, 0.4); background: #ffffff;">
                                    <!-- Editor Container (White Background for crisp document formatting) -->
                                    <div id="quillEditorContainer" style="height: 450px; color: #0f172a; font-size: 1.1rem; line-height: 1.8;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Image Gallery Hub & Document Attachments -->
                    <div class="col-xl-5 col-lg-5">
                        
                        <!-- 1. IMAGE GALLERY & COVER SELECTOR HUB -->
                        <div class="glass-card p-4 rounded-4 shadow-lg mb-4" style="background: rgba(30, 41, 59, 0.65); border: 1px solid rgba(255,255,255,0.12);">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.15) !important;">
                                <h5 class="fw-bold m-0 text-white d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-images text-warning"></i>
                                    <span>1. คลังภาพบทความ & ภาพหน้าปก</span>
                                </h5>
                                <span class="badge bg-primary rounded-pill" id="imgCountBadge">0 ภาพ</span>
                            </div>

                            <!-- Current Cover Preview Box -->
                            <div class="mb-4 p-3 rounded-3 shadow-sm text-center" style="background: rgba(0,0,0,0.4); border: 2px dashed rgba(255,255,255,0.25);">
                                <small class="text-info fw-bold d-block mb-2"><i class="fa-solid fa-image me-1"></i>ภาพหน้าปกตัวอย่างปัจจุบัน (Cover Preview)</small>
                                <img id="coverPreviewImg" src="<?= base_url('assets/images/slider/sane_muanglung.png') ?>" class="img-fluid rounded-3 shadow-sm mb-2" style="max-height: 160px; object-fit: cover; width: 100%;">
                                <div id="coverStatusMsg" class="small text-warning">⭐ ใช้ภาพเริ่มต้น (คลิกปุ่ม 'เลือกเป็นภาพหน้าปก' ที่ภาพด้านล่างเพื่อเปลี่ยน)</div>
                                
                                <!-- Cover Image Resizing & Fit Toolbar -->
                                <div class="mt-3 pt-3 border-top text-start" style="border-color: rgba(255,255,255,0.15) !important;">
                                    <label class="d-block text-white fw-bold small mb-2"><i class="fa-solid fa-crop-simple text-warning me-1"></i>ปรับสัดส่วนภาพหน้าปก (Image Aspect Ratio Resizing):</label>
                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                        <button type="button" onclick="NewsStudio.resizeCover('16_9')" class="btn btn-sm btn-outline-info flex-fill fw-bold py-1 text-center" title="ปรับภาพให้เป็น 16:9 พอดีการ์ดข่าว (1280x720)"><i class="fa-solid fa-tv me-1"></i> 16:9 Widescreen</button>
                                        <button type="button" onclick="NewsStudio.resizeCover('4_3')" class="btn btn-sm btn-outline-info flex-fill fw-bold py-1 text-center" title="ปรับภาพให้เป็น 4:3 โปสเตอร์ (1024x768)"><i class="fa-solid fa-image me-1"></i> 4:3 Standard</button>
                                        <button type="button" onclick="NewsStudio.resizeCover('optimize')" class="btn btn-sm btn-outline-warning flex-fill fw-bold py-1 text-center" title="บีบอัดความคมชัดให้อยู่ในระดับเว็บและโหลดเร็วขึ้น"><i class="fa-solid fa-bolt me-1"></i> บีบอัด & Optimize</button>
                                    </div>

                                    <div class="row g-2 align-items-center">
                                        <div class="col-5">
                                            <label class="text-light small fw-bold mb-0"><i class="fa-solid fa-maximize me-1"></i>การจัดวางในกรอบ (Fit):</label>
                                        </div>
                                        <div class="col-7">
                                            <select id="studioCoverFit" class="form-select form-select-sm text-white fw-bold shadow-sm" style="background: #0f172a; border: 1px solid #38bdf8;" onchange="NewsStudio.changeCoverFit(this.value)">
                                                <option value="cover">📐 Fill (เต็มกรอบ พอดีช่อง)</option>
                                                <option value="contain">🔍 Contain (เต็มใบ ไร้การตัดขอบ)</option>
                                                <option value="fill">↔️ Stretch (ยืดให้เต็มช่อง)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Button & File Input -->
                            <div class="mb-3">
                                <label for="studioImageInput" class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 m-0 text-white cursor-pointer hover-lift" style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                                    <i class="fa-solid fa-cloud-arrow-up fs-5"></i>
                                    <span>📤 อัปโหลดรูปภาพเข้าคลัง (เลือกได้หลายภาพ)</span>
                                </label>
                                <input type="file" id="studioImageInput" multiple accept="image/*" class="d-none" onchange="NewsStudio.handleImageUpload(this)">
                            </div>

                            <!-- Uploaded Gallery Cards Grid -->
                            <div class="row g-2 overflow-auto pe-1" id="studioImageGalleryGrid" style="max-height: 280px;">
                                <div class="col-12 text-center py-4 text-white-50 small" id="noImagesPlaceholder">
                                    <i class="fa-regular fa-image fs-2 mb-2 d-block opacity-50"></i>
                                    ยังไม่มีรูปภาพในคลัง กรุณากดอัปโหลดภาพ
                                </div>
                            </div>
                        </div>

                        <!-- 2. DOCUMENT & FILE ATTACHMENTS HUB -->
                        <div class="glass-card p-4 rounded-4 shadow-lg" style="background: rgba(30, 41, 59, 0.65); border: 1px solid rgba(255,255,255,0.12);">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.15) !important;">
                                <h5 class="fw-bold m-0 text-white d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-folder-open text-info"></i>
                                    <span>2. คลังไฟล์เอกสารดาวน์โหลด (Attachments)</span>
                                </h5>
                                <span class="badge bg-secondary rounded-pill" id="docCountBadge">0 ไฟล์</span>
                            </div>

                            <!-- Document Upload Button -->
                            <div class="mb-3">
                                <label for="studioDocInput" class="btn btn-outline-info w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 m-0 cursor-pointer hover-lift">
                                    <i class="fa-solid fa-file-arrow-up fs-5"></i>
                                    <span>📁 อัปโหลดไฟล์เอกสาร (PDF, Doc, Excel, Zip)</span>
                                </label>
                                <input type="file" id="studioDocInput" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" class="d-none" onchange="NewsStudio.handleDocUpload(this)">
                            </div>

                            <!-- Uploaded Documents List -->
                            <div class="d-flex flex-column gap-2 overflow-auto pe-1" id="studioDocList" style="max-height: 240px;">
                                <div class="text-center py-4 text-white-50 small" id="noDocsPlaceholder">
                                    <i class="fa-regular fa-file-lines fs-2 mb-2 d-block opacity-50"></i>
                                    ยังไม่มีไฟล์เอกสารแนบในบทความนี้
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- News Studio JS Control Engine -->
<script>
window.NewsStudio = {
    quill: null,
    imagesGallery: [],
    attachments: [],
    currentCover: '',

    init: function() {
        if (!this.quill && document.getElementById('quillEditorContainer')) {
            this.quill = new Quill('#quillEditorContainer', {
                theme: 'snow',
                placeholder: 'พิมพ์หรือนำเข้าเนื้อหาบทความข่าวประชาสัมพันธ์ตรงนี้...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link', 'blockquote', 'clean']
                    ]
                }
            });
        }
    },

    toggleEventPanel: function(show) {
        const panel = document.getElementById('studioEventPanel');
        if (!panel) return;
        if (show) {
            panel.classList.remove('d-none');
        } else {
            panel.classList.add('d-none');
        }
    },

    open: function(newsId = null) {
        this.init();
        this.imagesGallery = [];
        this.attachments = [];
        this.currentCover = '';
        
        const modalEl = document.getElementById('newsStudioModal');
        if (!modalEl) return;

        // Clear form
        document.getElementById('studioNewsId').value = '';
        document.getElementById('studioNewsTitle').value = '';
        document.getElementById('studioNewsSummary').value = '';
        if (this.quill) {
            this.quill.root.innerHTML = '';
        }

        const chkEvent = document.getElementById('studioIsEvent');
        if (chkEvent) chkEvent.checked = false;
        document.getElementById('studioEventStartDate').value = new Date().toISOString().split('T')[0];
        document.getElementById('studioEventEndDate').value = '';
        document.getElementById('studioEventLocation').value = '';
        document.getElementById('studioEventCoordinates').value = '';
        this.toggleEventPanel(false);

        this.renderImages();
        this.renderDocs();

        if (newsId) {
            App.toast('กำลังโหลดข้อมูลข่าว...', 'info');
            const baseUrl = window.BASE_URL || '';
            fetch(baseUrl + '/news/get-json/' + newsId)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.data) {
                        const n = data.data;
                        document.getElementById('studioNewsId').value = n.id || '';
                        document.getElementById('studioNewsTitle').value = n.title || '';
                        document.getElementById('studioNewsCategory').value = n.category || 'ประกาศราชการ / แจ้งเตือน';
                        document.getElementById('studioNewsSummary').value = n.summary || '';
                        if (this.quill) {
                            this.quill.root.innerHTML = n.content || '';
                        }
                        this.currentCover = n.cover_image || '';
                        this.imagesGallery = n.images_gallery || (this.currentCover ? [this.currentCover] : []);
                        this.attachments = n.attachments || [];
                        const coverFit = n.cover_fit || 'cover';
                        const fitSel = document.getElementById('studioCoverFit');
                        if (fitSel) fitSel.value = coverFit;
                        this.changeCoverFit(coverFit);

                        const isEvent = n.is_event == true || n.is_event === '1' || n.is_event === 'true';
                        if (chkEvent) chkEvent.checked = isEvent;
                        document.getElementById('studioEventStartDate').value = n.event_start_date || '';
                        document.getElementById('studioEventEndDate').value = n.event_end_date || '';
                        document.getElementById('studioEventLocation').value = n.event_location || '';
                        document.getElementById('studioEventCoordinates').value = n.event_coordinates || '';
                        this.toggleEventPanel(isEvent);

                        this.updateCoverPreview(this.currentCover);
                        this.renderImages();
                        this.renderDocs();
                    }
                })
                .catch(err => console.error(err));
        } else {
            const fitSel = document.getElementById('studioCoverFit');
            if (fitSel) fitSel.value = 'cover';
            this.changeCoverFit('cover');
            this.updateCoverPreview('assets/images/slider/sane_muanglung.png');
        }

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    },

    addCategory: async function() {
        const catName = prompt('กรุณาพิมพ์ชื่อหมวดหมู่ข่าวใหม่ที่ต้องการเพิ่ม (เช่น ข่าวประกวดราคา 2569):');
        if (!catName || !catName.trim()) return;

        const baseUrl = window.BASE_URL || '';
        const formData = new FormData();
        formData.append('category_name', catName.trim());

        try {
            const res = await fetch(baseUrl + '/news/save-category', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.status === 'success') {
                App.toast('เพิ่มหมวดหมู่เรียบร้อย', 'success');
                const sel = document.getElementById('studioNewsCategory');
                const newOpt = document.createElement('option');
                newOpt.value = catName.trim();
                newOpt.textContent = catName.trim();
                sel.appendChild(newOpt);
                sel.value = catName.trim();
            } else {
                App.toast(data.message || 'ไม่สามารถบันทึกหมวดหมู่ได้', 'error');
            }
        } catch(e) {
            console.error(e);
            App.toast('เกิดข้อผิดพลาดในการสร้างหมวดหมู่', 'error');
        }
    },

    handleImageUpload: async function(input) {
        if (!input.files || input.files.length === 0) return;
        const baseUrl = window.BASE_URL || '';
        App.toast('กำลังอัปโหลดรูปภาพเข้าสู่คลัง...', 'info');

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch(baseUrl + '/news/upload-image', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.status === 'success') {
                    this.imagesGallery.push(data.path);
                    if (!this.currentCover) {
                        this.currentCover = data.path;
                        this.updateCoverPreview(this.currentCover);
                    }
                } else {
                    App.toast(data.message, 'error');
                }
            } catch (err) {
                console.error(err);
                App.toast('อัปโหลดผิดพลาด: ' + file.name, 'error');
            }
        }
        input.value = '';
        this.renderImages();
        App.toast('อัปโหลดรูปภาพเสร็จสิ้น!', 'success');
    },

    handleDocUpload: async function(input) {
        if (!input.files || input.files.length === 0) return;
        const baseUrl = window.BASE_URL || '';
        App.toast('กำลังอัปโหลดไฟล์เอกสาร...', 'info');

        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const formData = new FormData();
            formData.append('document', file);

            try {
                const res = await fetch(baseUrl + '/news/upload-doc', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.status === 'success') {
                    this.attachments.push({
                        name: data.file_name,
                        path: data.path,
                        url: data.url,
                        size: data.size,
                        icon: data.icon_class
                    });
                } else {
                    App.toast(data.message, 'error');
                }
            } catch (err) {
                console.error(err);
                App.toast('อัปโหลดเอกสารผิดพลาด: ' + file.name, 'error');
            }
        }
        input.value = '';
        this.renderDocs();
        App.toast('อัปโหลดไฟล์เอกสารเสร็จสิ้น!', 'success');
    },

    setCover: function(imgPath) {
        this.currentCover = imgPath;
        this.updateCoverPreview(imgPath);
        this.renderImages();
        App.toast('⭐ เปลี่ยนภาพหน้าปกสำเร็จ!', 'success');
    },

    updateCoverPreview: function(imgPath) {
        const imgEl = document.getElementById('coverPreviewImg');
        const msgEl = document.getElementById('coverStatusMsg');
        if (imgEl) {
            const baseUrl = window.BASE_URL || '';
            imgEl.src = imgPath.startsWith('http') ? imgPath : (baseUrl + '/' + imgPath.replace(/^\//, ''));
        }
        if (msgEl) {
            msgEl.innerHTML = `⭐ ตั้งค่าเป็นภาพหน้าปกประจำบทความเรียบร้อย`;
        }
        document.getElementById('studioCoverImage').value = imgPath;
    },

    changeCoverFit: function(fitMode) {
        const imgEl = document.getElementById('coverPreviewImg');
        if (imgEl) {
            imgEl.style.objectFit = fitMode;
            if (fitMode === 'contain') {
                imgEl.style.backgroundColor = '#090d16';
            } else {
                imgEl.style.backgroundColor = 'transparent';
            }
        }
    },

    resizeCover: async function(mode) {
        const currentPath = this.currentCover || document.getElementById('studioCoverImage').value;
        if (!currentPath || currentPath.indexOf('sane_muanglung.png') !== -1) {
            App.toast('กรุณาเลือกหรืออัปโหลดภาพหน้าปกของตัวเองก่อนปรับขนาดครับ', 'error');
            return;
        }

        App.toast('⏳ กำลังประมวลผล Resizing กราฟิกภาพหน้าปก...', 'info');
        const baseUrl = window.BASE_URL || '';
        const formData = new FormData();
        formData.append('image_path', currentPath);
        formData.append('mode', mode);

        try {
            const res = await fetch(baseUrl + '/news/resize-cover', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.status === 'success' && data.path) {
                App.toast('🎉 ' + data.message, 'success');
                // replace old cover in gallery with resized version
                const idx = this.imagesGallery.indexOf(this.currentCover);
                if (idx !== -1) {
                    this.imagesGallery[idx] = data.path;
                } else {
                    this.imagesGallery.unshift(data.path);
                }
                this.currentCover = data.path;
                this.updateCoverPreview(data.path);
                this.renderImages();
            } else {
                App.toast(data.message || 'ไม่สามารถปรับขนาดภาพได้', 'error');
            }
        } catch (err) {
            console.error(err);
            App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อ API Resizing', 'error');
        }
    },

    insertImageToEditor: function(imgPath) {
        if (!this.quill) return;
        const baseUrl = window.BASE_URL || '';
        const fullUrl = imgPath.startsWith('http') ? imgPath : (baseUrl + '/' + imgPath.replace(/^\//, ''));
        const range = this.quill.getSelection(true);
        this.quill.insertEmbed(range.index, 'image', fullUrl);
        this.quill.setSelection(range.index + 1);
        App.toast('📥 แทรกรูปภาพเข้าสู่บทความแล้ว', 'info');
    },

    insertDocToEditor: function(idx) {
        if (!this.quill || !this.attachments[idx]) return;
        const doc = this.attachments[idx];
        const range = this.quill.getSelection(true);
        
        // Insert a cleanly styled HTML download link box into editor
        const downloadHtml = `<br><p><a href="${doc.url}" target="_blank" style="display: inline-block; padding: 10px 18px; background: #0ea5e9; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 10px 0;">📎 ดาวน์โหลดเอกสาร: ${doc.name} (${doc.size})</a></p><br>`;
        this.quill.clipboard.dangerouslyPasteHTML(range.index, downloadHtml);
        App.toast('📎 แทรกกล่องดาวน์โหลดเข้าสู่บทความแล้ว', 'info');
    },

    removeDoc: function(idx) {
        if (confirm('ยืนยันลบไฟล์เอกสารนี้ออกจากบทความ?')) {
            this.attachments.splice(idx, 1);
            this.renderDocs();
        }
    },

    removeImg: function(idx) {
        if (confirm('ยืนยันลบรูปภาพนี้จากคลังบทความ?')) {
            const removed = this.imagesGallery[idx];
            this.imagesGallery.splice(idx, 1);
            if (this.currentCover === removed) {
                this.currentCover = this.imagesGallery[0] || 'assets/images/slider/sane_muanglung.png';
                this.updateCoverPreview(this.currentCover);
            }
            this.renderImages();
        }
    },

    renderImages: function() {
        const grid = document.getElementById('studioImageGalleryGrid');
        const badge = document.getElementById('imgCountBadge');
        if (!grid) return;

        if (badge) badge.textContent = this.imagesGallery.length + ' ภาพ';

        if (this.imagesGallery.length === 0) {
            grid.innerHTML = `<div class="col-12 text-center py-4 text-white-50 small" id="noImagesPlaceholder"><i class="fa-regular fa-image fs-2 mb-2 d-block opacity-50"></i>ยังไม่มีรูปภาพในคลัง กรุณากดอัปโหลดภาพ</div>`;
            return;
        }

        const baseUrl = window.BASE_URL || '';
        let html = '';
        this.imagesGallery.forEach((path, idx) => {
            const isCover = (path === this.currentCover);
            const src = path.startsWith('http') ? path : (baseUrl + '/' + path.replace(/^\//, ''));
            
            html += `
                <div class="col-6 col-md-4 col-xl-6">
                    <div class="p-2 rounded-3 shadow-sm position-relative h-100 d-flex flex-column justify-content-between transition-all" style="background: rgba(15, 23, 42, 0.85); border: 2px solid ${isCover ? '#10b981' : 'rgba(255,255,255,0.18)'}; box-shadow: ${isCover ? '0 0 15px rgba(16,185,129,0.4)' : 'none'};">
                        <?php // Cover Badge ?>
                        ${isCover ? '<span class="badge bg-success position-absolute top-0 start-0 m-2 shadow-lg fw-bold px-2 py-1 z-2" style="font-size:0.75rem;"><i class="fa-solid fa-star me-1"></i>ภาพหน้าปกปัจจุบัน</span>' : ''}
                        
                        <button type="button" onclick="NewsStudio.removeImg(${idx})" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-1 p-1 z-2 shadow" style="width:26px;height:26px;line-height:1;" title="ลบรูป">
                            <i class="fa-solid fa-xmark small"></i>
                        </button>

                        <div class="text-center mb-2 overflow-hidden rounded-2" style="height: 120px;">
                            <img src="${src}" class="w-100 h-100 object-fit-cover hover-scale">
                        </div>

                        <div class="d-flex flex-column gap-2 mt-auto">
                            ${!isCover ? `<button type="button" onclick="NewsStudio.setCover('${path}')" class="btn btn-sm btn-warning py-2 fw-bold w-100 text-dark shadow-sm d-flex align-items-center justify-content-center gap-1" style="font-size:0.8rem; background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;" title="คลิกเพื่อให้รูปนี้ไปโชว์เป็นตัวอย่างบนการ์ดข่าว"><i class="fa-solid fa-star text-dark"></i> เลือกเป็นภาพหน้าปก</button>` : ''}
                            <button type="button" onclick="NewsStudio.insertImageToEditor('${path}')" class="btn btn-sm btn-primary py-2 fw-bold w-100 shadow-sm d-flex align-items-center justify-content-center gap-1" style="font-size:0.8rem; background: linear-gradient(135deg, #0284c7, #3b82f6); border: none;" title="ส่งรูปนี้เข้าไปในบทความ"><i class="fa-solid fa-circle-arrow-down me-1"></i> แทรกลงเนื้อหา</button>
                        </div>
                    </div>
                </div>
            `;
        });
        grid.innerHTML = html;
    },

    renderDocs: function() {
        const list = document.getElementById('studioDocList');
        const badge = document.getElementById('docCountBadge');
        if (!list) return;

        if (badge) badge.textContent = this.attachments.length + ' ไฟล์';

        if (this.attachments.length === 0) {
            list.innerHTML = `<div class="text-center py-4 text-white-50 small" id="noDocsPlaceholder"><i class="fa-regular fa-file-lines fs-2 mb-2 d-block opacity-50"></i>ยังไม่มีไฟล์เอกสารแนบในบทความนี้</div>`;
            return;
        }

        let html = '';
        this.attachments.forEach((doc, idx) => {
            html += `
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 shadow-sm" style="background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.12);">
                    <div class="d-flex align-items-center gap-3 overflow-hidden me-2">
                        <i class="${doc.icon || 'fa-solid fa-file'} fs-3"></i>
                        <div class="text-truncate">
                            <h6 class="fw-bold m-0 text-white text-truncate" style="font-size: 0.95rem;">${doc.name}</h6>
                            <small class="text-info">${doc.size}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-shrink-0">
                        <button type="button" onclick="NewsStudio.insertDocToEditor(${idx})" class="btn btn-sm btn-outline-warning fw-bold px-2 py-1" title="แทรกปุ่มโหลดในตัวเขียน">
                            <i class="fa-solid fa-link me-1"></i> แทรกลงเนื้อหา
                        </button>
                        <button type="button" onclick="NewsStudio.removeDoc(${idx})" class="btn btn-sm btn-outline-danger px-2 py-1" title="ลบไฟล์">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        list.innerHTML = html;
    },

    save: async function() {
        const title = document.getElementById('studioNewsTitle').value.trim();
        const content = this.quill ? this.quill.root.innerHTML : '';

        if (!title) {
            App.toast('กรุณาตั้งชื่อหัวข้อข่าวและประกาศ', 'error');
            document.getElementById('studioNewsTitle').focus();
            return;
        }
        if (!content || content === '<p><br></p>') {
            App.toast('กรุณาพิมพ์เนื้อหาของบทความ', 'error');
            return;
        }

        const btn = document.getElementById('btnSaveNews');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> กำลังบันทึกข้อมูล...';

        const formData = new FormData();
        formData.append('id', document.getElementById('studioNewsId').value);
        formData.append('title', title);
        formData.append('category', document.getElementById('studioNewsCategory').value);
        formData.append('summary', document.getElementById('studioNewsSummary').value);
        formData.append('content', content);
        formData.append('cover_image', this.currentCover || document.getElementById('studioCoverImage').value);
        formData.append('cover_fit', document.getElementById('studioCoverFit') ? document.getElementById('studioCoverFit').value : 'cover');
        
        const isEvent = document.getElementById('studioIsEvent')?.checked ? 'true' : 'false';
        formData.append('is_event', isEvent);
        formData.append('event_start_date', document.getElementById('studioEventStartDate')?.value || '');
        formData.append('event_end_date', document.getElementById('studioEventEndDate')?.value || '');
        formData.append('event_location', document.getElementById('studioEventLocation')?.value || '');
        formData.append('event_coordinates', document.getElementById('studioEventCoordinates')?.value || '');

        formData.append('images_gallery', JSON.stringify(this.imagesGallery));
        formData.append('attachments', JSON.stringify(this.attachments));

        const baseUrl = window.BASE_URL || '';
        try {
            const res = await fetch(baseUrl + '/news/save', { method: 'POST', body: formData });
            const data = await res.json();
            btn.disabled = false;
            btn.innerHTML = origText;

            if (data.status === 'success') {
                App.toast('🎉 ' + data.message, 'success');
                const modal = bootstrap.Modal.getInstance(document.getElementById('newsStudioModal'));
                if (modal) modal.hide();

                // Reload page automatically in 800ms to show the fresh updated news card/page!
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            } else {
                App.toast(data.message || 'บันทึกผิดพลาด', 'error');
            }
        } catch(e) {
            console.error(e);
            btn.disabled = false;
            btn.innerHTML = origText;
            App.toast('เกิดข้อผิดพลาดในการสื่อสารกับเซิร์ฟเวอร์', 'error');
        }
    },

    deleteNews: async function(id) {
        if (!confirm('⚠️ คุณต้องการลบข่าว/ประกาศ รายการนี้ออกจากเว็บไซต์ถาวรหรือไม่?')) return;
        const baseUrl = window.BASE_URL || '';
        const formData = new FormData();
        formData.append('id', id);

        try {
            const res = await fetch(baseUrl + '/news/delete', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.status === 'success') {
                App.toast('🗑️ ' + data.message, 'success');
                setTimeout(() => window.location.reload(), 600);
            } else {
                App.toast(data.message, 'error');
            }
        } catch(e) {
            console.error(e);
            App.toast('ลบข้อมูลผิดพลาด', 'error');
        }
    }
};
</script>

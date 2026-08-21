<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php 
$parent_pages = $parent_pages ?? []; 
$pages = $pages ?? []; 
?>
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

                        <!-- ปรับภาพส่วนหัวของเพจ (Header Image) -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="fa-regular fa-image text-primary me-1"></i> ภาพพื้นหลังส่วนหัวเพจ (Header Banner Image)
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="pageHeaderImage" name="header_image" placeholder="ระบุ URL รูปภาพ หรือกดปุ่มอัปโหลดด้านขวา (เช่น assets/images/slider/sane_muanglung.png)" oninput="updateHeaderPreviewFromInput(this.value)">
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('pageHeaderImageFile').click()">
                                    <i class="fa-solid fa-upload me-1"></i> อัปโหลดรูปภาพใหม่
                                </button>
                            </div>
                            <input type="file" id="pageHeaderImageFile" name="header_image_file" class="d-none" accept="image/*" onchange="previewHeaderImage(this)">
                            
                            <div id="headerImagePreviewWrap" class="mt-2 d-none position-relative" style="max-height: 140px; overflow: hidden; border-radius: 12px;">
                                <img id="headerImagePreview" src="" alt="Header Preview" class="w-100 object-fit-cover rounded-3 border" style="max-height: 140px;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle" style="width: 32px; height: 32px; padding: 0;" onclick="clearHeaderImage()" title="ลบภาพพื้นหลัง">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="fa-solid fa-circle-info me-1"></i> แนะนำภาพแนวนอนอัตราส่วน 16:9 (หากไม่ระบุ ระบบจะใช้สีพื้นหลังน้ำเงินมาตรฐานของจังหวัด)
                            </small>
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

<!-- ========================================== -->
<!-- MODAL: FONT AWESOME 6 ICON & SYMBOL PICKER -->
<!-- ========================================== -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalTitle" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3 px-4" style="background: linear-gradient(135deg, #1e3a8a, #0369a1) !important;">
                <h5 class="modal-title fw-bold" id="iconPickerModalTitle">
                    <i class="fa-solid fa-icons me-2 text-warning"></i> คลังไอคอนและสัญลักษณ์พิเศษ (Font Awesome 6)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <!-- ช่องค้นหาและตัวกรองหมวดหมู่ -->
                <div class="row g-3 mb-3">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" class="form-control" id="iconSearchInput" placeholder="ค้นหาไอคอน (เช่น house, file, check, star, phone, user, map, car)..." oninput="filterIcons()">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select class="form-select" id="iconCategorySelect" onchange="filterIcons()">
                            <option value="all">📁 ทุกหมวดหมู่ (All Categories)</option>
                            <option value="gov">🏛️ งานราชการ & หน่วยงาน</option>
                            <option value="doc">📄 เอกสาร & กฎหมาย</option>
                            <option value="contact">📞 ติดต่อ & พิกัดสถานที่</option>
                            <option value="people">👥 บุคลากร & ผู้บริหาร</option>
                            <option value="chart">📊 สถิติ & ยุทธศาสตร์</option>
                            <option value="nature">🌾 เกษตร & ท่องเที่ยว</option>
                            <option value="symbol">🔣 สัญลักษณ์ & เครื่องหมาย</option>
                        </select>
                    </div>
                </div>

                <!-- ตารางเลือกไอคอน (Scrollable Grid) -->
                <div class="p-2 border rounded-3 mb-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                    <div class="row g-2" id="iconGridContainer">
                        <!-- Javascript จะ render ไอคอนทั้งหมดที่นี่ -->
                    </div>
                </div>

                <!-- ตัวเลือกการปรับแต่ง: สี ขนาด และการแสดงผลสด -->
                <div class="p-3 rounded-3 border" style="background: #f8fafc;">
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-secondary mb-1">สีของไอคอน (Color)</label>
                            <select class="form-select form-select-sm" id="iconColorSelect" onchange="updateSelectedIconPreview()">
                                <option value="#1e3a8a">🔵 น้ำเงินเข้ม (Navy / ราชการ)</option>
                                <option value="#0284c7">🔷 ฟ้าสว่าง (Cyan Blue)</option>
                                <option value="#16a34a">🟢 เขียว (Success Green)</option>
                                <option value="#ea580c">🟠 ส้ม / ทอง (Gold Warning)</option>
                                <option value="#dc2626">🔴 แดง (Danger Red)</option>
                                <option value="#0f172a">⚫ เทาดำเข้ม (Dark Slate)</option>
                                <option value="inherit">🔘 สีเดียวกับข้อความ (Default)</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-secondary mb-1">ขนาดของไอคอน (Size)</label>
                            <select class="form-select form-select-sm" id="iconSizeSelect" onchange="updateSelectedIconPreview()">
                                <option value="1em">1.0x ขนาดปกติ (1em)</option>
                                <option value="1.25em" selected>1.25x ปานกลาง (1.25em)</option>
                                <option value="1.5em">1.5x ขนาดใหญ่ (1.5em)</option>
                                <option value="2em">2.0x ขนาดใหญ่พิเศษ (2.0em)</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-secondary mb-1">ตัวอย่างแสดงผลสด</label>
                            <div class="d-flex align-items-center justify-content-center p-2 rounded border bg-white" style="min-height: 40px;" id="selectedIconPreviewBox">
                                <i class="fa-solid fa-landmark" style="color: #1e3a8a; font-size: 1.25em;"></i>
                                <span class="ms-2 small text-muted text-truncate" id="selectedIconNameText">fa-landmark</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary px-4 fw-bold" onclick="insertSelectedIconToEditor()">
                    <i class="fa-solid fa-plus me-1"></i> แทรกลงในเนื้อหา
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
.icon-select-btn {
    border: 1px solid #e2e8f0;
    background: #ffffff;
    border-radius: 8px;
    padding: 10px 6px;
    text-align: center;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #475569;
}
.icon-select-btn i {
    font-size: 1.35rem;
    color: #1e3a8a;
    transition: transform 0.15s ease;
}
.icon-select-btn:hover {
    background: #e0f2fe;
    border-color: #38bdf8;
    color: #0369a1;
}
.icon-select-btn:hover i {
    transform: scale(1.15);
}
.icon-select-btn.active {
    background: #1e3a8a;
    border-color: #1e3a8a;
    color: #ffffff;
}
.icon-select-btn.active i {
    color: #ffffff;
}
</style>

<script>
let pageModal;
let iconPickerModal;
let selectedIconClass = 'fa-solid fa-landmark';

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

    const iconModalEl = document.getElementById('iconPickerModal');
    iconPickerModal = new bootstrap.Modal(iconModalEl, {
        focus: false,
        keyboard: true
    });
    
    // ตั้งค่า TinyMCE ให้รองรับการแทรกภาพ และ Font Awesome Icons
    tinymce.init({
        selector: '#pageContent',
        height: 520,
        menubar: 'file edit view insert format tools table help',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media table | fa_picker charmap | code fullscreen help',
        content_css: [
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'
        ],
        content_style: 'body { font-family: "Sarabun", "Prompt", "Inter", sans-serif; font-size: 16px; line-height: 1.6; color: #1e293b; padding: 12px; } img { max-width: 100%; height: auto; border-radius: 8px; margin: 8px 0; }',
        promotion: false,
        branding: false,
        image_advtab: true,
        image_title: true,
        image_caption: true,
        automatic_uploads: true,
        paste_data_images: true,
        file_picker_types: 'image',
        // ระบบอัปโหลดรูปภาพผ่าน AJAX ไปยังเซิร์ฟเวอร์
        images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '<?= base_url("admin/pages/upload-image") ?>');
            
            const csrfMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            const csrfHeader = document.querySelector('meta[name="X-CSRF-HEADER"]');
            if (csrfMeta && csrfHeader) {
                xhr.setRequestHeader(csrfHeader.content, csrfMeta.content);
            }
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    progress(e.loaded / e.total * 100);
                }
            };

            xhr.onload = () => {
                if (xhr.status === 403 || xhr.status === 401) {
                    reject({ message: 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่', remove: true });
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    reject('เกิดข้อผิดพลาดในการอัปโหลด: HTTP ' + xhr.status);
                    return;
                }

                try {
                    const json = JSON.parse(xhr.responseText);
                    if (!json || (!json.location && !json.url)) {
                        reject('ผลลัพธ์จากเซิร์ฟเวอร์ไม่ถูกต้อง: ' + xhr.responseText);
                        return;
                    }
                    resolve(json.location || json.url);
                } catch (e) {
                    reject('ไม่สามารถแปลงข้อมูล JSON ได้');
                }
            };

            xhr.onerror = () => {
                reject('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์เพื่ออัปโหลดภาพได้');
            };

            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }),
        // ตัวเลือกเปิด File Picker ให้เลือกภาพจากคอมพิวเตอร์ได้โดยตรง
        file_picker_callback: function (cb, value, meta) {
            if (meta.filetype === 'image') {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function () {
                    const file = this.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('file', file);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '<?= base_url("admin/pages/upload-image") ?>');
                    
                    const csrfMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
                    const csrfHeader = document.querySelector('meta[name="X-CSRF-HEADER"]');
                    if (csrfMeta && csrfHeader) {
                        xhr.setRequestHeader(csrfHeader.content, csrfMeta.content);
                    }
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            try {
                                const json = JSON.parse(xhr.responseText);
                                cb(json.location || json.url, { title: file.name, alt: file.name });
                            } catch (e) {
                                alert('อัปโหลดภาพไม่สำเร็จ');
                            }
                        } else {
                            alert('เกิดข้อผิดพลาดในการอัปโหลดภาพ HTTP: ' + xhr.status);
                        }
                    };

                    xhr.send(formData);
                };

                input.click();
            }
        },
        setup: function (editor) {
            editor.on('init', function () {
                editor.getBody().style.fontSize = '16px';
            });

            // ลงทะเบียนปุ่มแทรกไอคอน Font Awesome 6
            editor.ui.registry.addButton('fa_picker', {
                text: 'ไอคอน & สัญลักษณ์',
                icon: 'star',
                tooltip: 'เปิดคลังแทรกไอคอน Font Awesome 6 และสัญลักษณ์พิเศษ',
                onAction: function () {
                    openIconPickerModal();
                }
            });
        }
    });

    // เริ่มต้นแสดงผลไอคอนในคลัง
    renderIconGrid(ICON_LIBRARY);

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
    document.getElementById('pageHeaderImage').value = '';
    clearHeaderImage();
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

function previewHeaderImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('headerImagePreview').src = e.target.result;
            document.getElementById('headerImagePreviewWrap').classList.remove('d-none');
            document.getElementById('pageHeaderImage').value = input.files[0].name;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateHeaderPreviewFromInput(val) {
    val = val.trim();
    if (val) {
        let fullUrl = val;
        if (!val.startsWith('http://') && !val.startsWith('https://') && !val.startsWith('data:')) {
            fullUrl = '<?= base_url() ?>/' + val.replace(/^\/+/, '');
        }
        document.getElementById('headerImagePreview').src = fullUrl;
        document.getElementById('headerImagePreviewWrap').classList.remove('d-none');
    } else {
        clearHeaderImage();
    }
}

function clearHeaderImage() {
    document.getElementById('pageHeaderImage').value = '';
    document.getElementById('pageHeaderImageFile').value = '';
    document.getElementById('headerImagePreview').src = '';
    document.getElementById('headerImagePreviewWrap').classList.add('d-none');
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
            
            // จัดการ Header Image
            if (res.data.header_image) {
                document.getElementById('pageHeaderImage').value = res.data.header_image;
                let fullUrl = res.data.header_image;
                if (!fullUrl.startsWith('http://') && !fullUrl.startsWith('https://')) {
                    fullUrl = '<?= base_url() ?>/' + fullUrl.replace(/^\/+/, '');
                }
                document.getElementById('headerImagePreview').src = fullUrl;
                document.getElementById('headerImagePreviewWrap').classList.remove('d-none');
            } else {
                clearHeaderImage();
            }

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

        if (res && res.status === 'success') {
            App.toast(res.message, 'success');
            pageModal.hide();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            App.toast(res ? res.message : 'บันทึกข้อมูลไม่สำเร็จ', 'error');
        }
    } catch (err) {
        console.error('Save page error:', err);
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

// ==========================================
// คลังไอคอน FONT AWESOME 6 (ICON LIBRARY)
// ==========================================
const ICON_LIBRARY = [
    // 1. ราชการ & องค์กร (Gov)
    { class: 'fa-solid fa-landmark', name: 'อาคารราชการ', cat: 'gov', tags: 'government building landmark palace' },
    { class: 'fa-solid fa-building-columns', name: 'เสาหลัก/สถาบัน', cat: 'gov', tags: 'institution bank court columns' },
    { class: 'fa-solid fa-university', name: 'สถาบัน/มหาวิทยาลัย', cat: 'gov', tags: 'university school academy' },
    { class: 'fa-solid fa-scale-balanced', name: 'ตราชู/ความยุติธรรม', cat: 'gov', tags: 'justice law balance court' },
    { class: 'fa-solid fa-shield-halved', name: 'โล่/ความปลอดภัย', cat: 'gov', tags: 'shield security defense protection' },
    { class: 'fa-solid fa-gavel', name: 'ค้อนศาล/ระเบียบ', cat: 'gov', tags: 'gavel law rule judge' },
    { class: 'fa-solid fa-flag', name: 'ธง/นโยบาย', cat: 'gov', tags: 'flag nation policy' },
    { class: 'fa-solid fa-crown', name: 'มงกุฎ/เกียรติยศ', cat: 'gov', tags: 'crown royal honor' },

    // 2. เอกสาร & กฎหมาย (Doc)
    { class: 'fa-solid fa-file-lines', name: 'เอกสาร/เนื้อหา', cat: 'doc', tags: 'file text document paper' },
    { class: 'fa-solid fa-file-pdf', name: 'ไฟล์ PDF', cat: 'doc', tags: 'pdf download document' },
    { class: 'fa-solid fa-file-contract', name: 'สัญญา/ข้อตกลง', cat: 'doc', tags: 'contract agreement legal' },
    { class: 'fa-solid fa-folder-open', name: 'แฟ้มเอกสาร', cat: 'doc', tags: 'folder directory category' },
    { class: 'fa-solid fa-newspaper', name: 'หนังสือพิมพ์/ข่าว', cat: 'doc', tags: 'news article press' },
    { class: 'fa-solid fa-bullhorn', name: 'ประกาศ/แจ้งเตือน', cat: 'doc', tags: 'announcement broadcast speaker' },
    { class: 'fa-solid fa-book', name: 'หนังสือ/คู่มือ', cat: 'doc', tags: 'book manual guide' },
    { class: 'fa-solid fa-clipboard-check', name: 'รายการตรวจสอบ', cat: 'doc', tags: 'checklist audit inspect' },
    { class: 'fa-solid fa-signature', name: 'ลายเซ็น/อนุมัติ', cat: 'doc', tags: 'signature approve sign' },

    // 3. ติดต่อ & พิกัดสถานที่ (Contact)
    { class: 'fa-solid fa-phone', name: 'โทรศัพท์', cat: 'contact', tags: 'phone call hotline contact' },
    { class: 'fa-solid fa-envelope', name: 'อีเมล/จดหมาย', cat: 'contact', tags: 'email mail message letter' },
    { class: 'fa-solid fa-location-dot', name: 'พิกัด/ที่ตั้ง', cat: 'contact', tags: 'map pin location address gps' },
    { class: 'fa-solid fa-map-location-dot', name: 'แผนที่นำทาง', cat: 'contact', tags: 'navigation map directions' },
    { class: 'fa-solid fa-globe', name: 'เว็บไซต์/สากล', cat: 'contact', tags: 'website internet world web' },
    { class: 'fa-solid fa-clock', name: 'เวลาทำการ', cat: 'contact', tags: 'clock time hour schedule' },
    { class: 'fa-solid fa-calendar-days', name: 'ปฏิทิน/วันที่', cat: 'contact', tags: 'calendar date event schedule' },
    { class: 'fa-solid fa-comments', name: 'การสนทนา/ติดต่อ', cat: 'contact', tags: 'chat conversation support' },

    // 4. บุคลากร & ผู้บริหาร (People)
    { class: 'fa-solid fa-user-tie', name: 'ผู้บริหาร/ข้าราชการ', cat: 'people', tags: 'executive officer leader boss' },
    { class: 'fa-solid fa-users', name: 'ประชาชน/กลุ่มคน', cat: 'people', tags: 'people team community public' },
    { class: 'fa-solid fa-user-gear', name: 'เจ้าหน้าที่ระบบ', cat: 'people', tags: 'admin officer staff technical' },
    { class: 'fa-solid fa-user-graduate', name: 'นักวิชาการ/การศึกษา', cat: 'people', tags: 'graduate student education' },
    { class: 'fa-solid fa-id-card', name: 'บัตรประจำตัว', cat: 'people', tags: 'id card identity citizen' },
    { class: 'fa-solid fa-handshake', name: 'ความร่วมมือ', cat: 'people', tags: 'partnership cooperate agreement' },

    // 5. สถิติ & ยุทธศาสตร์ (Chart)
    { class: 'fa-solid fa-chart-line', name: 'กราฟแนวโน้ม', cat: 'chart', tags: 'chart trend growth progress' },
    { class: 'fa-solid fa-chart-pie', name: 'แผนภูมิวงกลม', cat: 'chart', tags: 'pie chart share percentage' },
    { class: 'fa-solid fa-chart-bar', name: 'แผนภูมิแท่ง', cat: 'chart', tags: 'bar chart statistics data' },
    { class: 'fa-solid fa-bullseye', name: 'เป้าหมาย/วิสัยทัศน์', cat: 'chart', tags: 'target goal vision mission' },
    { class: 'fa-solid fa-trophy', name: 'รางวัล/เกียรติบัตร', cat: 'chart', tags: 'trophy award success winner' },
    { class: 'fa-solid fa-medal', name: 'เหรียญรางวัล', cat: 'chart', tags: 'medal honor prize' },
    { class: 'fa-solid fa-star', name: 'ดาว/ความโดดเด่น', cat: 'chart', tags: 'star favorite highlight rate' },
    { class: 'fa-solid fa-ranking-star', name: 'อันดับ/ผลประเมิน', cat: 'chart', tags: 'ranking score evaluation' },

    // 6. เกษตร & ท่องเที่ยว (Nature)
    { class: 'fa-solid fa-tree', name: 'ต้นไม้/ป่าไม้', cat: 'nature', tags: 'tree forest green nature' },
    { class: 'fa-solid fa-leaf', name: 'ใบไม้/สิ่งแวดล้อม', cat: 'nature', tags: 'leaf eco bio plant' },
    { class: 'fa-solid fa-mountain-sun', name: 'ภูเขา/ธรรมชาติ', cat: 'nature', tags: 'mountain hill landscape travel' },
    { class: 'fa-solid fa-water', name: 'แม่น้ำ/ทะเล', cat: 'nature', tags: 'water river sea ocean lake' },
    { class: 'fa-solid fa-wheat-awn', name: 'รวงข้าว/เกษตรกรรม', cat: 'nature', tags: 'agriculture rice farm crop' },
    { class: 'fa-solid fa-seedling', name: 'กล้าไม้/การเพาะปลูก', cat: 'nature', tags: 'sprout seed farming growth' },
    { class: 'fa-solid fa-compass', name: 'เข็มทิศ/ท่องเที่ยว', cat: 'nature', tags: 'compass direction tourism travel' },
    { class: 'fa-solid fa-camera', name: 'ถ่ายภาพ/จุดเช็คอิน', cat: 'nature', tags: 'camera photo tourism landmark' },
    { class: 'fa-solid fa-car', name: 'ยานพาหนะ/การเดินทาง', cat: 'nature', tags: 'car vehicle transport travel' },

    // 7. สัญลักษณ์ & เครื่องหมาย (Symbol)
    { class: 'fa-solid fa-check', name: 'เครื่องหมายถูก', cat: 'symbol', tags: 'check tick yes pass' },
    { class: 'fa-solid fa-check-double', name: 'ตรวจสอบเรียบร้อย', cat: 'symbol', tags: 'double check complete done' },
    { class: 'fa-solid fa-circle-check', name: 'วงกลมถูก', cat: 'symbol', tags: 'circle check success valid' },
    { class: 'fa-solid fa-circle-info', name: 'ข้อมูลเพิ่มเติม', cat: 'symbol', tags: 'info detail about note' },
    { class: 'fa-solid fa-circle-exclamation', name: 'ข้อควรระวัง', cat: 'symbol', tags: 'warning alert exclamation notice' },
    { class: 'fa-solid fa-circle-question', name: 'คำถาม/ช่วยเหลือ', cat: 'symbol', tags: 'question help faq ask' },
    { class: 'fa-solid fa-arrow-right', name: 'ลูกศรชี้ขวา', cat: 'symbol', tags: 'arrow next forward go' },
    { class: 'fa-solid fa-circle-arrow-right', name: 'ปุ่มลูกศรขวา', cat: 'symbol', tags: 'circle arrow forward next' },
    { class: 'fa-solid fa-thumbs-up', name: 'ถูกใจ/เห็นชอบ', cat: 'symbol', tags: 'thumbs up like approve good' },
    { class: 'fa-solid fa-heart', name: 'หัวใจ/ความห่วงใย', cat: 'symbol', tags: 'heart care health love' },
    { class: 'fa-solid fa-lightbulb', name: 'ความคิดสร้างสรรค์', cat: 'symbol', tags: 'lightbulb idea innovation think' },
    { class: 'fa-solid fa-magnifying-glass', name: 'ค้นหา', cat: 'symbol', tags: 'search zoom find look' }
];

function openIconPickerModal() {
    document.getElementById('iconSearchInput').value = '';
    document.getElementById('iconCategorySelect').value = 'all';
    renderIconGrid(ICON_LIBRARY);
    updateSelectedIconPreview();
    iconPickerModal.show();
}

function renderIconGrid(list) {
    const container = document.getElementById('iconGridContainer');
    if (!container) return;

    if (list.length === 0) {
        container.innerHTML = `<div class="col-12 text-center py-4 text-muted"><i class="fa-regular fa-face-frown fs-3 mb-2 d-block"></i>ไม่พบไอคอนที่ตรงกับคำค้นหา</div>`;
        return;
    }

    container.innerHTML = list.map(item => {
        const isActive = item.class === selectedIconClass ? 'active' : '';
        return `
            <div class="col-4 col-sm-3 col-md-2">
                <button type="button" class="w-100 icon-select-btn ${isActive}" onclick="selectIcon('${item.class}', '${item.name}')" title="${item.name}">
                    <i class="${item.class}"></i>
                    <span class="text-truncate w-100">${item.name}</span>
                </button>
            </div>
        `;
    }).join('');
}

function filterIcons() {
    const q = document.getElementById('iconSearchInput').value.toLowerCase().trim();
    const cat = document.getElementById('iconCategorySelect').value;

    const filtered = ICON_LIBRARY.filter(item => {
        const matchCat = (cat === 'all' || item.cat === cat);
        const matchQuery = !q || item.name.toLowerCase().includes(q) || item.class.toLowerCase().includes(q) || item.tags.toLowerCase().includes(q);
        return matchCat && matchQuery;
    });

    renderIconGrid(filtered);
}

function selectIcon(iconClass, iconName) {
    selectedIconClass = iconClass;
    document.getElementById('selectedIconNameText').textContent = iconName + ' (' + iconClass.replace('fa-solid ', '') + ')';

    // อัปเดต class active ในตาราง
    document.querySelectorAll('.icon-select-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.querySelector(`i.${iconClass.replace(' ', '.')}`)) {
            btn.classList.add('active');
        }
    });

    updateSelectedIconPreview();
}

function updateSelectedIconPreview() {
    const color = document.getElementById('iconColorSelect').value;
    const size = document.getElementById('iconSizeSelect').value;
    const box = document.getElementById('selectedIconPreviewBox');
    if (!box) return;

    const styleStr = `color: ${color === 'inherit' ? '#1e293b' : color}; font-size: ${size};`;
    box.querySelector('i').className = selectedIconClass;
    box.querySelector('i').style.cssText = styleStr;
}

function insertSelectedIconToEditor() {
    const color = document.getElementById('iconColorSelect').value;
    const size = document.getElementById('iconSizeSelect').value;

    let styleRules = [];
    if (color !== 'inherit') styleRules.push(`color: ${color}`);
    if (size !== '1em') styleRules.push(`font-size: ${size}`);
    
    const styleAttr = styleRules.length > 0 ? ` style="${styleRules.join('; ')};"` : '';
    const iconHtml = `<i class="${selectedIconClass} me-2"${styleAttr}></i>&nbsp;`;

    if (tinymce.get('pageContent')) {
        tinymce.get('pageContent').insertContent(iconHtml);
    }

    iconPickerModal.hide();
    App.toast('แทรกไอคอนลงในเนื้อหาเรียบร้อยแล้ว', 'success');
}
</script>
<?= $this->endSection() ?>

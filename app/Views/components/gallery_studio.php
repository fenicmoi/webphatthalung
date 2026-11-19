<?php
helper('settings');
$galCategories = get_gallery_categories();
?>

<!-- ON-PAGE GALLERY STUDIO (จัดการคลังภาพกิจกรรมพร้อมอัปโหลดหลายภาพ) -->
<style>
#galleryStudioModal .modal-body::-webkit-scrollbar {
    width: 6px;
}
#galleryStudioModal .modal-body::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.4);
    border-radius: 4px;
}
#galleryStudioModal .modal-body::-webkit-scrollbar-thumb {
    background: rgba(56, 189, 248, 0.4);
    border-radius: 4px;
}
#galleryStudioModal .modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(56, 189, 248, 0.7);
}
</style>

<div class="modal fade" id="galleryStudioModal" tabindex="-1" aria-labelledby="galleryStudioTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content glass-modal border-0 rounded-4 shadow-lg" id="galleryStudioForm" onsubmit="GalleryStudio.save(event)" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(56, 189, 248, 0.35) !important; color: #f8fafc; max-height: calc(100vh - 3.5rem); display: flex; flex-direction: column;">
            <input type="hidden" name="id" id="galId">

            <div class="modal-header border-bottom border-secondary border-opacity-25 py-3 px-4 flex-shrink-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3 text-dark fw-bold d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); width: 48px; height: 48px;">
                        <i class="fa-solid fa-camera-retro fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0 text-white" id="galleryStudioTitle">สร้างอัลบั้มภาพกิจกรรมจังหวัด</h5>
                        <small class="text-info" id="galleryStudioSubTitle">ระบบจัดการคลังภาพกิจกรรมและประเพณี (Gallery On-Page Studio)</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-3" style="overflow-y: auto; flex: 1 1 auto;">
                <!-- 1. ชื่ออัลบั้ม -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-warning small">
                        <i class="fa-solid fa-heading me-1"></i> ชื่ออัลบั้มกิจกรรม / งานประเพณี <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2 rounded-3" name="title" id="galTitle" placeholder="เช่น งานประเพณีแข่งโพนและลากพระ ประจำปี 2569..." required>
                </div>

                <div class="row g-3 mb-3">
                    <!-- 2. หมวดหมู่ -->
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fw-bold text-warning small m-0">
                                <i class="fa-solid fa-folder me-1"></i> หมวดหมู่กิจกรรม
                            </label>
                            <button type="button" class="btn btn-link btn-sm text-info p-0 text-decoration-none" onclick="GalleryStudio.toggleCustomCategory()" id="btnToggleCatMode" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-circle-plus me-1"></i>เพิ่มหมวดใหม่
                            </button>
                        </div>
                        <select class="form-select bg-dark text-white border-secondary border-opacity-50 py-2 rounded-3" id="galCategorySelect" onchange="GalleryStudio.onCategorySelectChange(this.value)">
                            <?php foreach ($galCategories as $gCat): ?>
                                <option value="<?= esc($gCat) ?>"><?= esc($gCat) ?></option>
                            <?php endforeach; ?>
                            <option value="__NEW__" style="color: #38bdf8; font-weight: bold;">➕ พิมพ์หมวดหมู่ใหม่...</option>
                        </select>
                        <div id="customCategoryWrapper" class="mt-2 d-none">
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary border-opacity-50 text-info" style="font-size: 0.85rem;"><i class="fa-solid fa-pen-to-square"></i></span>
                                <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2 rounded-end-3" id="galCustomCategoryInput" placeholder="พิมพ์ชื่อหมวดหมู่ใหม่ที่ต้องการ...">
                            </div>
                            <small class="text-white-50 mt-1 d-block" style="font-size: 0.75rem;">* หมวดหมู่ใหม่จะถูกบันทึกและแสดงในแท็บตัวกรองหน้าคลังภาพอัตโนมัติ</small>
                        </div>
                        <input type="hidden" name="category" id="galCategory" value="<?= esc($galCategories[0] ?? 'ประเพณีและวัฒนธรรม') ?>">
                    </div>

                    <!-- 3. วันที่จัดกิจกรรม -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-warning small">
                            <i class="fa-solid fa-calendar-day me-1"></i> วันที่จัดกิจกรรม
                        </label>
                        <input type="date" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2 rounded-3" name="date" id="galDate" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <!-- 4. ภาพปก (Cover Image) -->
                <div class="card border-secondary border-opacity-25 rounded-4 p-3 mb-3" style="background: rgba(255,255,255,0.03);">
                    <label class="form-label fw-bold text-info small d-block mb-2">
                        <i class="fa-solid fa-image me-1"></i> ภาพปกอัลบั้ม (Cover Image / Thumbnail)
                    </label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small text-white-50 d-block mb-1">อัปโหลดไฟล์รูปปก (JPG / PNG):</label>
                            <input type="file" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-50" name="cover_file" id="galCoverFile" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-white-50 d-block mb-1">หรือระบุลิงก์รูปภาพปก (URL):</label>
                            <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-50" name="cover_url" id="galCoverUrl" placeholder="https://...">
                        </div>
                    </div>
                    <div id="coverPreviewContainer" class="mt-2 d-none">
                        <img id="coverImgPreview" src="" alt="Cover Preview" class="rounded-3 shadow-sm border border-secondary" style="max-height: 120px; object-fit: cover;">
                    </div>
                </div>

                <!-- 5. อัปโหลดรูปภาพกิจกรรมในชุด (Multiple Photos Upload) -->
                <div class="card border-secondary border-opacity-25 rounded-4 p-3 mb-3" style="background: rgba(14, 165, 233, 0.05); border-color: rgba(56, 189, 248, 0.3) !important;">
                    <label class="form-label fw-bold text-warning small d-block mb-2">
                        <i class="fa-solid fa-images me-1"></i> อัปโหลดรูปภาพในอัลบั้ม (เลือกได้หลายภาพในครั้งเดียว)
                    </label>
                    <div class="p-3 border border-dashed border-info border-opacity-50 rounded-3 text-center transition-all hover-bg-light" style="background: rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-cloud-arrow-up fs-2 text-info mb-2 d-block"></i>
                        <span class="d-block text-white fw-bold mb-1">คลิกหรือเลือกหลายไฟล์เพื่อนำเข้าคลังภาพ</span>
                        <small class="text-white-50 d-block mb-3">รองรับ JPG, PNG, WEBP หรือ HEIC (เลือกทีละหลายไฟล์ได้)</small>
                        <input type="file" class="form-control bg-dark text-white border-info" name="gallery_photos[]" id="galMultiPhotos" accept="image/*" multiple>
                    </div>

                    <div class="mt-3">
                        <label class="small text-white-50 d-block mb-1">เพิ่มด้วยลิงก์ URL (พิมพ์ URL คนละบรรทัด หรือคั่นด้วยลูกน้ำ):</label>
                        <textarea class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-50" name="external_urls" id="galExternalUrls" rows="2" placeholder="https://domain.com/photo1.jpg&#10;https://domain.com/photo2.jpg"></textarea>
                    </div>
                </div>

                <!-- 6. รายการรูปภาพเดิมในอัลบั้ม (สำหรับการแก้ไข) -->
                <div id="existingPhotosCard" class="card border-secondary border-opacity-25 rounded-4 p-3 d-none" style="background: rgba(255,255,255,0.02);">
                    <label class="form-label fw-bold text-success small d-block mb-2">
                        <i class="fa-solid fa-photo-film me-1"></i> รูปภาพปัจจุบันในอัลบั้ม (<span id="existingCount">0</span> ภาพ) - คลิกไอคอนถังขยะเพื่อลบเฉพาะรูป
                    </label>
                    <div class="row g-2" id="existingPhotosGrid">
                        <!-- Populated dynamically via JS -->
                    </div>
                </div>
            </div>

            <div class="modal-footer border-top border-secondary border-opacity-25 px-4 py-3 d-flex justify-content-between flex-shrink-0" style="background: rgba(15, 23, 42, 0.7);">
                <button type="button" class="btn btn-outline-secondary text-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-warning fw-bold text-dark rounded-pill px-5 shadow-sm" id="galBtnSave">
                    <i class="fa-solid fa-floppy-disk me-2"></i><span id="galBtnSaveText">บันทึกอัลบั้มกิจกรรม</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
var GalleryStudio = {
    modal: null,
    currentAlbumId: null,

    init: function() {
        var el = document.getElementById('galleryStudioModal');
        if (el && typeof bootstrap !== 'undefined') {
            this.modal = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
        }
        var catInput = document.getElementById('galCustomCategoryInput');
        if (catInput && !catInput._hasBind) {
            catInput._hasBind = true;
            catInput.addEventListener('input', function() {
                var hiddenCat = document.getElementById('galCategory');
                if (hiddenCat) hiddenCat.value = this.value.trim();
            });
        }
    },

    onCategorySelectChange: function(val) {
        var wrapper = document.getElementById('customCategoryWrapper');
        var input = document.getElementById('galCustomCategoryInput');
        var hiddenCat = document.getElementById('galCategory');
        if (val === '__NEW__') {
            if (wrapper) wrapper.classList.remove('d-none');
            if (input) {
                input.focus();
                if (hiddenCat) hiddenCat.value = input.value.trim();
            }
        } else {
            if (wrapper) wrapper.classList.add('d-none');
            if (hiddenCat) hiddenCat.value = val;
        }
    },

    toggleCustomCategory: function() {
        var select = document.getElementById('galCategorySelect');
        if (select) {
            select.value = '__NEW__';
            this.onCategorySelectChange('__NEW__');
        }
    },

    setCategoryValue: function(cat) {
        cat = (cat || '').trim();
        var select = document.getElementById('galCategorySelect');
        var wrapper = document.getElementById('customCategoryWrapper');
        var input = document.getElementById('galCustomCategoryInput');
        var hiddenCat = document.getElementById('galCategory');
        if (!select) return;

        var found = false;
        for (var i = 0; i < select.options.length; i++) {
            if (select.options[i].value === cat) {
                select.selectedIndex = i;
                found = true;
                break;
            }
        }

        if (found) {
            if (wrapper) wrapper.classList.add('d-none');
            if (hiddenCat) hiddenCat.value = cat;
        } else if (cat) {
            select.value = '__NEW__';
            if (wrapper) wrapper.classList.remove('d-none');
            if (input) input.value = cat;
            if (hiddenCat) hiddenCat.value = cat;
        } else {
            select.selectedIndex = 0;
            if (wrapper) wrapper.classList.add('d-none');
            if (hiddenCat) hiddenCat.value = select.value;
        }
    },

    open: function(id, defaultCategory, directData) {
        if (!this.modal) this.init();
        var form = document.getElementById('galleryStudioForm');
        if (form) form.reset();

        document.getElementById('coverPreviewContainer').classList.add('d-none');
        document.getElementById('existingPhotosCard').classList.add('d-none');
        document.getElementById('existingPhotosGrid').innerHTML = '';
        this.currentAlbumId = id || '';
        document.getElementById('galId').value = this.currentAlbumId;

        var titleEl = document.getElementById('galleryStudioTitle');
        var subTitleEl = document.getElementById('galleryStudioSubTitle');
        var btnTextEl = document.getElementById('galBtnSaveText');

        if (defaultCategory) {
            this.setCategoryValue(defaultCategory);
        } else {
            this.setCategoryValue('');
        }

        // Helper เติมข้อมูลลงฟอร์มและแสดงตัวอย่างภาพ
        var populateAlbumData = function(alb) {
            if (!alb) return;
            if (alb.id) {
                document.getElementById('galId').value = alb.id;
                GalleryStudio.currentAlbumId = alb.id;
            }
            if (alb.title) document.getElementById('galTitle').value = alb.title;
            if (alb.category) GalleryStudio.setCategoryValue(alb.category);
            if (alb.date) document.getElementById('galDate').value = alb.date;
            if (alb.cover_image) {
                document.getElementById('galCoverUrl').value = alb.cover_image;
                var cPrev = document.getElementById('coverImgPreview');
                var cUrl = (alb.cover_image.startsWith('http') || alb.cover_image.startsWith('data:')) ? alb.cover_image : "<?= base_url() ?>/" + alb.cover_image;
                cPrev.src = cUrl;
                document.getElementById('coverPreviewContainer').classList.remove('d-none');
            }

            if (alb.photos && alb.photos.length > 0) {
                document.getElementById('existingPhotosCard').classList.remove('d-none');
                document.getElementById('existingCount').innerText = alb.photos.length;
                var grid = document.getElementById('existingPhotosGrid');
                grid.innerHTML = '';
                alb.photos.forEach(function(pUrl) {
                    var fullUrl = (pUrl.startsWith('http') || pUrl.startsWith('data:')) ? pUrl : "<?= base_url() ?>/" + pUrl;
                    var col = document.createElement('div');
                    col.className = 'col-6 col-sm-4 col-md-3 position-relative';
                    col.innerHTML = `
                        <div class="card bg-dark border border-secondary border-opacity-50 rounded-3 overflow-hidden shadow-sm h-100 position-relative">
                            <img src="${fullUrl}" class="w-100 h-100" style="height: 110px !important; object-fit: cover;" alt="photo">
                            <button type="button" onclick="GalleryStudio.deletePhoto('${alb.id}', '${pUrl}', this.closest('.col-6'))" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 m-1 p-1 d-flex align-items-center justify-content-center" style="width: 26px; height: 26px;" title="ลบภาพนี้">
                                <i class="fa-solid fa-xmark fs-8"></i>
                            </button>
                        </div>
                    `;
                    grid.appendChild(col);
                });
            }
        };

        // 1. กรณีส่งข้อมูล Object มาโดยตรง (เปิดจากหน้าดูอัลบั้มเดี่ยว โหลดทันทีไม่ต้องรอเน็ตเวิร์ก)
        if (directData && typeof directData === 'object') {
            if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-pen-to-square me-2 text-warning"></i>แก้ไขข้อมูลอัลบั้มภาพกิจกรรม';
            if (subTitleEl) subTitleEl.innerText = 'แก้ไขชื่อ หมวดหมู่ วันที่ ภาพปก และรูปภาพในอัลบั้มนี้';
            if (btnTextEl) btnTextEl.innerText = 'บันทึกการแก้ไขอัลบั้ม';
            populateAlbumData(directData);
            if (this.modal) this.modal.show();
            return;
        }

        // 2. กรณีส่ง ID มา (โหลดผ่าน AJAX)
        if (id) {
            if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2 text-info"></i>กำลังโหลดข้อมูลอัลบั้ม...';
            if (subTitleEl) subTitleEl.innerText = 'กำลังดึงข้อมูลล่าสุดจากระบบ...';
            if (btnTextEl) btnTextEl.innerText = 'บันทึกการแก้ไขอัลบั้ม';
            if (this.modal) this.modal.show();

            fetch("<?= base_url('admin/gallery/get-item') ?>/" + encodeURIComponent(id), {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.data) {
                    if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-pen-to-square me-2 text-warning"></i>แก้ไขข้อมูลอัลบั้มภาพกิจกรรม';
                    if (subTitleEl) subTitleEl.innerText = 'แก้ไขชื่อ หมวดหมู่ วันที่ ภาพปก และรูปภาพในอัลบั้มนี้';
                    populateAlbumData(data.data);
                } else {
                    if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>ไม่พบข้อมูลอัลบั้ม';
                    if (typeof App !== 'undefined' && App.toast) App.toast(data.message || 'ไม่พบข้อมูลอัลบั้ม', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>เกิดข้อผิดพลาดในการโหลด';
            });
        } else {
            // 3. กรณีสร้างอัลบั้มใหม่
            if (titleEl) titleEl.innerHTML = '<i class="fa-solid fa-circle-plus me-2 text-warning"></i>สร้างอัลบั้มภาพกิจกรรมจังหวัดใหม่';
            if (subTitleEl) subTitleEl.innerText = 'ระบบจัดการคลังภาพกิจกรรมและประเพณี (Gallery On-Page Studio)';
            if (btnTextEl) btnTextEl.innerText = 'บันทึกและสร้างอัลบั้มใหม่';
            if (this.modal) this.modal.show();
        }
    },

    save: function(e) {
        e.preventDefault();
        var catSelect = document.getElementById('galCategorySelect');
        var customInput = document.getElementById('galCustomCategoryInput');
        var hiddenCat = document.getElementById('galCategory');
        if (catSelect && catSelect.value === '__NEW__') {
            var catVal = customInput ? customInput.value.trim() : '';
            if (!catVal) {
                if (typeof App !== 'undefined' && App.toast) App.toast('กรุณาระบุชื่อหมวดหมู่ใหม่', 'warning');
                else alert('กรุณาระบุชื่อหมวดหมู่ใหม่');
                if (customInput) customInput.focus();
                return;
            }
            if (hiddenCat) hiddenCat.value = catVal;
        }
        var form = document.getElementById('galleryStudioForm');
        var btn = document.getElementById('galBtnSave');
        var origText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังอัปโหลดรูปภาพ...';
        btn.disabled = true;

        var formData = new FormData(form);

        fetch("<?= base_url('admin/gallery/save-item') ?>", {
            method: 'POST',
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = origText;
            btn.disabled = false;
            if (data.status === 'success') {
                if (typeof App !== 'undefined' && App.toast) App.toast(data.message || 'บันทึกอัลบั้มเรียบร้อยแล้ว', 'success');
                if (this.modal) this.modal.hide();
                setTimeout(function() { window.location.reload(); }, 1000);
            } else {
                if (typeof App !== 'undefined' && App.toast) App.toast(data.message || 'เกิดข้อผิดพลาด', 'error');
                else alert(data.message || 'เกิดข้อผิดพลาด');
            }
        })
        .catch(err => {
            btn.innerHTML = origText;
            btn.disabled = false;
            console.error(err);
            alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
        });
    },

    deleteAlbum: function(id, title) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'ยืนยันการลบอัลบั้ม?',
                text: 'คุณแน่ใจหรือไม่ว่าต้องการลบ "' + title + '" ออกจากคลังภาพกิจกรรม',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ใช่, ลบอัลบั้มนี้',
                cancelButtonText: 'ยกเลิก'
            }).then(result => {
                if (result.isConfirmed) this.doDeleteAlbum(id);
            });
        } else if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบอัลบั้ม "' + title + '" ?')) {
            this.doDeleteAlbum(id);
        }
    },

    doDeleteAlbum: function(id) {
        var formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        fetch("<?= base_url('admin/gallery/delete-item') ?>/" + id, {
            method: 'POST',
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined' && App.toast) App.toast(data.message, 'success');
                setTimeout(function() { window.location.reload(); }, 800);
            } else {
                if (typeof App !== 'undefined' && App.toast) App.toast(data.message || 'ลบไม่สำเร็จ', 'error');
            }
        })
        .catch(err => console.error(err));
    },

    deletePhoto: function(albumId, photoUrl, cardEl) {
        if (!confirm('ต้องการลบรูปภาพนี้ออกจากอัลบั้มใช่หรือไม่?')) return;

        var formData = new FormData();
        formData.append('album_id', albumId);
        formData.append('photo_url', photoUrl);

        fetch("<?= base_url('admin/gallery/delete-photo') ?>", {
            method: 'POST',
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined' && App.toast) App.toast('ลบภาพเรียบร้อยแล้ว', 'success');
                if (cardEl) cardEl.remove();
                var cEl = document.getElementById('existingCount');
                if (cEl) cEl.innerText = Math.max(0, parseInt(cEl.innerText || '1') - 1);
            } else {
                if (typeof App !== 'undefined' && App.toast) App.toast(data.message || 'ลบภาพไม่สำเร็จ', 'error');
            }
        })
        .catch(err => console.error(err));
    }
};

document.addEventListener('DOMContentLoaded', function() {
    GalleryStudio.init();
});
</script>

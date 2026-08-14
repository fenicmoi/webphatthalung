<?php
helper('settings');
$galCategories = get_gallery_categories();
?>

<!-- ON-PAGE GALLERY STUDIO (จัดการคลังภาพกิจกรรมพร้อมอัปโหลดหลายภาพ) -->
<div class="modal fade" id="galleryStudioModal" tabindex="-1" aria-labelledby="galleryStudioTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content glass-modal border-0 rounded-4 shadow-lg" style="background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(25px); border: 1px solid rgba(56, 189, 248, 0.35) !important; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary border-opacity-25 py-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 rounded-3 text-dark fw-bold d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); width: 48px; height: 48px;">
                        <i class="fa-solid fa-camera-retro fs-3"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold m-0 text-white" id="galleryStudioTitle">สร้างอัลบั้มภาพกิจกรรมจังหวัด</h5>
                        <small class="text-info">ระบบจัดการคลังภาพกิจกรรมและประเพณี (Gallery On-Page Studio)</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="galleryStudioForm" onsubmit="GalleryStudio.save(event)">
                <input type="hidden" name="id" id="galId">

                <div class="modal-body px-4 py-4">
                    <!-- 1. ชื่ออัลบั้ม -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-warning small">
                            <i class="fa-solid fa-heading me-1"></i> ชื่ออัลบั้มกิจกรรม / งานประเพณี <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2 rounded-3" name="title" id="galTitle" placeholder="เช่น งานประเพณีแข่งโพนและลากพระ ประจำปี 2569..." required>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- 2. หมวดหมู่ -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-warning small">
                                <i class="fa-solid fa-folder me-1"></i> หมวดหมู่กิจกรรม
                            </label>
                            <select class="form-select bg-dark text-white border-secondary border-opacity-50 py-2 rounded-3" name="category" id="galCategory">
                                <?php foreach ($galCategories as $gCat): ?>
                                    <option value="<?= esc($gCat) ?>"><?= esc($gCat) ?></option>
                                <?php endforeach; ?>
                            </select>
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
                    <div class="card border-secondary border-opacity-25 rounded-4 p-3 mb-4" style="background: rgba(255,255,255,0.03);">
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
                    <div class="card border-secondary border-opacity-25 rounded-4 p-3 mb-4" style="background: rgba(14, 165, 233, 0.05); border-color: rgba(56, 189, 248, 0.3) !important;">
                        <label class="form-label fw-bold text-warning small d-block mb-2">
                            <i class="fa-solid fa-images me-1"></i> อัปโหลดรูปภาพในอัลบั้ม (เลือกได้หลายภาพในครั้งเดียว)
                        </label>
                        <div class="p-4 border border-dashed border-info border-opacity-50 rounded-3 text-center transition-all hover-bg-light" style="background: rgba(0,0,0,0.2);">
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

                <div class="modal-footer border-top border-secondary border-opacity-25 px-4 py-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary text-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark rounded-pill px-5 shadow-sm" id="galBtnSave">
                        <i class="fa-solid fa-floppy-disk me-2"></i>บันทึกอัลบั้มกิจกรรม
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var GalleryStudio = {
    modal: null,
    currentAlbumId: null,

    init: function() {
        var el = document.getElementById('galleryStudioModal');
        if (el && typeof bootstrap !== 'undefined') {
            this.modal = new bootstrap.Modal(el);
        }
    },

    open: function(id, defaultCategory) {
        if (!this.modal) this.init();
        var form = document.getElementById('galleryStudioForm');
        if (form) form.reset();

        document.getElementById('coverPreviewContainer').classList.add('d-none');
        document.getElementById('existingPhotosCard').classList.add('d-none');
        document.getElementById('existingPhotosGrid').innerHTML = '';
        this.currentAlbumId = id || '';
        document.getElementById('galId').value = this.currentAlbumId;

        if (defaultCategory && document.getElementById('galCategory')) {
            document.getElementById('galCategory').value = defaultCategory;
        }

        if (id) {
            document.getElementById('galleryStudioTitle').innerText = 'แก้ไขข้อมูลอัลบั้มภาพกิจกรรม';
            // Load existing album data via AJAX
            fetch("<?= base_url('admin/gallery/get-item') ?>/" + id, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.data) {
                    var alb = data.data;
                    document.getElementById('galTitle').value = alb.title || '';
                    document.getElementById('galCategory').value = alb.category || 'ประเพณีและวัฒนธรรม';
                    document.getElementById('galDate').value = alb.date || '';
                    document.getElementById('galCoverUrl').value = alb.cover_image || '';
                    
                    if (alb.cover_image) {
                        var cPrev = document.getElementById('coverImgPreview');
                        cPrev.src = (alb.cover_image.startsWith('http') || alb.cover_image.startsWith('data:')) ? alb.cover_image : "<?= base_url() ?>/" + alb.cover_image;
                        document.getElementById('coverPreviewContainer').classList.remove('d-none');
                    }

                    if (alb.photos && alb.photos.length > 0) {
                        document.getElementById('existingPhotosCard').classList.remove('d-none');
                        document.getElementById('existingCount').innerText = alb.photos.length;
                        var grid = document.getElementById('existingPhotosGrid');
                        grid.innerHTML = '';
                        alb.photos.forEach(function(pUrl, idx) {
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
                } else {
                    if (typeof App !== 'undefined' && App.toast) App.toast('ไม่พบข้อมูลอัลบั้ม', 'error');
                }
            })
            .catch(err => console.error(err));
        } else {
            document.getElementById('galleryStudioTitle').innerText = 'สร้างอัลบั้มภาพกิจกรรมจังหวัดใหม่';
        }

        if (this.modal) this.modal.show();
    },

    save: function(e) {
        e.preventDefault();
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

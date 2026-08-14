<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
helper('settings');
$isOfficer = session()->get('isLoggedIn');
$selectedCat = $selectedCat ?? 'all';
$videos = $videos ?? [];
?>

<style>
/* --- Premium Modern Phatthalung Web TV & Video Cinema Styling --- */
.webtv-hero {
    background: linear-gradient(135deg, #0f172a 0%, #4c0519 50%, #9f1239 100%);
    border-radius: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(159, 18, 57, 0.3);
}
.webtv-hero::after {
    content: '';
    position: absolute;
    bottom: -50px;
    right: -50px;
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, rgba(251, 113, 133, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.video-card {
    border: 1px solid var(--glass-border);
    border-radius: 1.25rem;
    overflow: hidden;
    background: var(--card-bg, #ffffff);
    box-shadow: 0 4px 15px rgba(0,0,0,0.06);
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    cursor: pointer;
}
.video-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(225, 29, 72, 0.2);
    border-color: #f43f5e;
}
.video-thumb-wrapper {
    position: relative;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
    overflow: hidden;
    background: #000;
}
.video-thumb-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
    opacity: 0.9;
}
.video-card:hover .video-thumb-img {
    transform: scale(1.08);
    opacity: 1;
}
.video-play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(1);
    width: 60px;
    height: 60px;
    background: rgba(225, 29, 72, 0.85);
    backdrop-filter: blur(8px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.5rem;
    box-shadow: 0 0 25px rgba(225, 29, 72, 0.6);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 2;
}
.video-card:hover .video-play-overlay {
    transform: translate(-50%, -50%) scale(1.2);
    background: rgba(225, 29, 72, 1);
    box-shadow: 0 0 35px rgba(225, 29, 72, 0.9);
}
.video-badge-cat {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(8px);
    color: #fb7185;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    border: 1px solid rgba(251, 113, 133, 0.4);
    z-index: 3;
}
.video-badge-views {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(0, 0, 0, 0.75);
    color: #ffffff;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    z-index: 3;
}
.cat-btn-pill {
    transition: all 0.3s ease;
    font-weight: 600;
    border-radius: 50rem !important;
    border: 1px solid var(--glass-border);
    color: var(--text-secondary);
    background: var(--card-bg, #ffffff);
}
.cat-btn-pill.active, .cat-btn-pill:hover {
    background: linear-gradient(135deg, #e11d48, #9f1239) !important;
    color: #ffffff !important;
    border-color: transparent;
    box-shadow: 0 4px 15px rgba(225, 29, 72, 0.35);
}
</style>

<div class="container py-4">
    <!-- Hero Header Banner -->
    <div class="webtv-hero p-4 p-md-5 mb-5 text-white">
        <div class="row align-items-center relative z-1">
            <div class="col-lg-8">
                <span class="badge rounded-pill bg-danger bg-opacity-75 px-3 py-2 fw-bold text-white mb-3 text-uppercase" style="letter-spacing: 1px;">
                    <i class="fa-solid fa-signal text-warning animate-pulse me-1"></i> Phatthalung Digital Web TV
                </span>
                <h1 class="display-6 fw-bolder mb-2 d-flex align-items-center gap-3">
                    <i class="fa-brands fa-youtube text-danger fs-1"></i>
                    <span>ศูนย์รวมสื่อวิดีทัศน์และวีดีโอส่งเสริมการท่องเที่ยว</span>
                </h1>
                <p class="text-white text-opacity-75 lead fs-6 mb-0 max-w-2xl">
                    รับชมภาพยนตร์ส่งเสริมการท่องเที่ยว สารคดีศิลปวัฒนธรรมมโนราห์ และรายงานความก้าวหน้าโครงการสำคัญของจังหวัดพัทลุงในรูปแบบความละเอียดสูง (4K Streaming)
                </p>
            </div>
            <div class="col-lg-4 text-start text-lg-end mt-4 mt-lg-0">
                <?php if ($isOfficer): ?>
                    <button type="button" onclick="VideoStudio.open()" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold text-dark shadow-lg hover-scale d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-plus fs-5 text-danger"></i>
                        <span>เพิ่มวิดีโอใหม่จาก YouTube</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Category Filter Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(0,0,0,0.08) !important;">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="text-muted fw-bold me-2 small"><i class="fa-solid fa-filter text-danger me-1"></i>หมวดหมู่:</span>
            <?php foreach ($categories as $catLabel => $catValue): ?>
                <a href="<?= base_url('videos/category/' . urlencode((string)$catValue)) ?>" class="btn btn-sm px-4 py-2 cat-btn-pill <?= ($selectedCat === $catValue) ? 'active' : '' ?>">
                    <?= esc($catLabel) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div>
            <span class="text-muted small">แสดงทั้งหมด <strong><?= count($videos) ?></strong> รายการ</span>
        </div>
    </div>

    <!-- Video Cards Grid -->
    <?php if (empty($videos)): ?>
        <div class="text-center py-5 my-5 glass-card rounded-5 border">
            <div class="py-4">
                <i class="fa-brands fa-youtube fs-1 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold text-muted">ไม่พบวิดีโอในหมวดหมู่นี้</h5>
                <p class="text-muted small mb-0">ระบบจะทำการจัดเก็บและเผยแพร่สื่อวิดีทัศน์ในเร็วๆ นี้</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4 mb-5">
            <?php foreach ($videos as $vid): 
                $yId = esc($vid['youtube_id'] ?? '');
                $thumbUrl = "https://img.youtube.com/vi/{$yId}/hqdefault.jpg";
                $vidTitle = esc($vid['title'] ?? 'วิดีโอจังหวัดพัทลุง');
                $vidDesc = esc($vid['desc'] ?? '');
                $vidCat = esc($vid['category'] ?? 'ทั่วไป');
                $vidViews = number_format($vid['views'] ?? 1);
                $vidDate = !empty($vid['date']) ? date('d/m/Y', strtotime($vid['date'])) : '-';
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="video-card h-100" onclick="SmartCinema.play('<?= $yId ?>', '<?= addslashes($vid['title']) ?>', '<?= $vid['id'] ?>')">
                        <!-- Video Thumbnail & Overlay -->
                        <div class="video-thumb-wrapper">
                            <span class="video-badge-cat"><i class="fa-solid fa-film me-1"></i><?= $vidCat ?></span>
                            <span class="video-badge-views"><i class="fa-solid fa-eye me-1 text-warning"></i><?= $vidViews ?> ครั้ง</span>
                            <img src="<?= $thumbUrl ?>" alt="<?= $vidTitle ?>" class="video-thumb-img" onerror="this.src='https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80'">
                            <div class="video-play-overlay">
                                <i class="fa-solid fa-play ms-1"></i>
                            </div>
                        </div>

                        <!-- Content Body -->
                        <div class="p-4 d-flex flex-column justify-content-between flex-grow-1">
                            <div>
                                <h5 class="fw-bold mb-2 text-truncate-2" style="color: var(--text-primary); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= $vidTitle ?>">
                                    <?= $vidTitle ?>
                                </h5>
                                <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.5;">
                                    <?= $vidDesc ?>
                                </p>
                            </div>

                            <div class="pt-3 border-top d-flex align-items-center justify-content-between text-muted small" style="border-color: rgba(0,0,0,0.06) !important;">
                                <span><i class="fa-regular fa-calendar-days me-1 text-danger"></i> เผยแพร่: <?= $vidDate ?></span>
                                <?php if ($isOfficer): ?>
                                    <div class="d-flex gap-1" onclick="event.stopPropagation();">
                                        <button type="button" onclick="VideoStudio.edit('<?= $vid['id'] ?>')" class="btn btn-sm btn-light text-primary py-0 px-2 rounded" title="แก้ไข">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button type="button" onclick="VideoStudio.delete('<?= $vid['id'] ?>', '<?= addslashes($vid['title']) ?>')" class="btn btn-sm btn-light text-danger py-0 px-2 rounded" title="ลบ">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1 fw-bold">
                                        <i class="fa-brands fa-youtube me-1"></i> HD
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- =====================================================================
     SMART CINEMA THEATER MODAL (IMAX-Style Dark Glassmorphism Viewer)
     ===================================================================== -->
<div class="modal fade" id="cinemaTheaterModal" tabindex="-1" aria-labelledby="cinemaTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg" style="background: rgba(15, 23, 42, 0.96); backdrop-filter: blur(25px); border: 1px solid rgba(255,255,255,0.15) !important; border-radius: 28px; overflow: hidden;">
            <div class="modal-header border-bottom py-3 px-4 d-flex align-items-center justify-content-between" style="border-color: rgba(255,255,255,0.1) !important;">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2 m-0 text-truncate" id="cinemaTitle">
                    <i class="fa-brands fa-youtube text-danger fs-3"></i>
                    <span id="cinemaTitleText">กำลังโหลดวิดีโอ...</span>
                </h5>
                <button type="button" class="btn btn-dark rounded-circle text-white p-2 d-flex align-items-center justify-content-center border" onclick="SmartCinema.close()" style="width: 40px; height: 40px; border-color: rgba(255,255,255,0.2) !important;">
                    <i class="fa-solid fa-xmark fs-5"></i>
                </button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    <iframe id="cinemaPlayerIframe" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
            <div class="modal-footer border-top px-4 py-3 d-flex align-items-center justify-content-between text-muted small" style="border-color: rgba(255,255,255,0.1) !important;">
                <span><i class="fa-solid fa-shield-halved text-success me-1"></i> สตรีมมิ่งความละเอียดสูงผ่านเซิร์ฟเวอร์ความปลอดภัยของ YouTube (Google CDN)</span>
                <button type="button" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold small" onclick="SmartCinema.close()">
                    <span>ปิดหน้าต่างรับชม</span>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if ($isOfficer): ?>
<!-- =====================================================================
     OFFICER ON-PAGE YOUTUBE STUDIO MODAL
     ===================================================================== -->
<div class="modal fade" id="videoStudioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 rounded-4 shadow-lg p-3" style="background: var(--card-bg, #ffffff);">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="fa-brands fa-youtube text-danger fs-3 animate-pulse"></i>
                    <span id="studioTitle">เพิ่มวิดีโอใหม่จาก YouTube</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="videoStudioForm" onsubmit="VideoStudio.save(event)">
                <div class="modal-body py-4">
                    <input type="hidden" id="v_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">ลิงก์วิดีโอ YouTube หรือ Video ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-danger"><i class="fa-brands fa-youtube"></i></span>
                            <input type="text" class="form-control" id="v_youtube_url" name="youtube_url" placeholder="เช่น https://www.youtube.com/watch?v=XXXXX หรือวางโค้ด ID" required>
                        </div>
                        <div class="form-text small">ระบบจะทำการดึงรูปหน้าปกความละเอียดสูงและแปลงรหัสวิดีโอให้อัตโนมัติ</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">ชื่อวิดีโอยูทูป / หัวข้อข่าว <span class="text-danger">*</span></label>
                        <input type="text" class="form-control fw-bold" id="v_title" name="title" placeholder="ระบุชื่อรายการ หรือคำอธิบายที่ชวนติดตาม" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">หมวดหมู่เนื้อหา <span class="text-danger">*</span></label>
                        <select class="form-select" id="v_category" name="category" required>
                            <option value="ส่งเสริมการท่องเที่ยว">ส่งเสริมการท่องเที่ยว</option>
                            <option value="ท่องเที่ยวและธรรมชาติ">ท่องเที่ยวและธรรมชาติ</option>
                            <option value="ศิลปวัฒนธรรมท้องถิ่น">ศิลปวัฒนธรรมท้องถิ่น</option>
                            <option value="ภารกิจและกิจกรรมจังหวัด">ภารกิจและกิจกรรมจังหวัด</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">คำอธิบายย่อ</label>
                        <textarea class="form-control" id="v_desc" name="desc" rows="3" placeholder="ข้อความแนะนำ หรือสรุปเนื้อหาสำคัญในคลิป"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top pt-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold text-white shadow-sm d-flex align-items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>บันทึกและเผยแพร่วิดีโอ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const VideoStudio = {
    open: function() {
        document.getElementById('videoStudioForm').reset();
        document.getElementById('v_id').value = '';
        document.getElementById('studioTitle').textContent = 'เพิ่มวิดีโอใหม่จาก YouTube';
        var modal = new bootstrap.Modal(document.getElementById('videoStudioModal'));
        modal.show();
    },
    edit: function(id) {
        fetch('<?= base_url("admin/videos/get-item") ?>/' + id)
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                var d = res.data;
                document.getElementById('v_id').value = d.id || '';
                document.getElementById('v_youtube_url').value = 'https://www.youtube.com/watch?v=' + (d.youtube_id || '');
                document.getElementById('v_title').value = d.title || '';
                document.getElementById('v_category').value = d.category || 'ส่งเสริมการท่องเที่ยว';
                document.getElementById('v_desc').value = d.desc || '';
                document.getElementById('studioTitle').textContent = 'แก้ไขข้อมูลวิดีโอ';
                var modal = new bootstrap.Modal(document.getElementById('videoStudioModal'));
                modal.show();
            } else {
                Swal.fire('Error', res.message || 'ไม่สามารถโหลดข้อมูลได้', 'error');
            }
        });
    },
    save: function(e) {
        e.preventDefault();
        var form = document.getElementById('videoStudioForm');
        var fd = new FormData(form);
        
        Swal.fire({
            title: 'กำลังเชื่อมโยงวิดีโอ...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('<?= base_url("admin/videos/save-item") ?>', {
            method: 'POST',
            body: fd
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'บันทึกสำเร็จ',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('ข้อผิดพลาด', res.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
            }
        })
        .catch(err => {
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์', 'error');
        });
    },
    delete: function(id, title) {
        Swal.fire({
            title: 'ยืนยันการลบวิดีโอ?',
            text: `ต้องการลบเรื่อง "${title}" ออกจากศูนย์รวมสื่อวิดีทัศน์หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ลบวิดีโอนี้',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url("admin/videos/delete-item") ?>/' + id, { method: 'POST' })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ลบสำเร็จ',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.message || 'ไม่สามารถลบรายการได้', 'error');
                    }
                });
            }
        });
    }
};
</script>
<?php endif; ?>

<script>
const SmartCinema = {
    modalInstance: null,
    play: function(youtubeId, title, dbId) {
        var el = document.getElementById('cinemaTheaterModal');
        if (!this.modalInstance) {
            this.modalInstance = new bootstrap.Modal(el);
        }
        document.getElementById('cinemaTitleText').textContent = title || 'วิดีโอนำเสนอจังหวัดพัทลุง';
        document.getElementById('cinemaPlayerIframe').src = 'https://www.youtube.com/embed/' + youtubeId + '?autoplay=1&rel=0';
        this.modalInstance.show();

        // Count view asynchronously
        if (dbId) {
            fetch('<?= base_url("videos/count-view") ?>/' + dbId, { method: 'POST' });
        }
    },
    close: function() {
        var el = document.getElementById('cinemaTheaterModal');
        var iframe = document.getElementById('cinemaPlayerIframe');
        iframe.src = ''; // Clear source to immediately stop playing audio
        if (this.modalInstance) {
            this.modalInstance.hide();
        } else {
            var m = bootstrap.Modal.getInstance(el);
            if (m) m.hide();
        }
    }
};
</script>

<?= $this->endSection() ?>

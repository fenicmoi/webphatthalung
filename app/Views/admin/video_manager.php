<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
$videos = $videos ?? [];
$categories = $categories ?? [];
?>

<div class="admin-content-container">
    
    <!-- Top Action Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-film text-danger me-2"></i>จัดการสื่อวีดิทัศน์ Web TV & YouTube
            </h4>
            <p class="text-muted small mb-0">เพิ่ม แก้ไข และจัดหมวดหมู่วิดีทัศน์ประชาสัมพันธ์จังหวัดพัทลุงสำหรับแสดงผลบนหน้าแรกและหน้า Web TV</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('videos') ?>" target="_blank" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ดูหน้า Web TV ประชาชน
            </a>
            <button type="button" class="btn btn-danger btn-sm px-3 rounded-pill fw-bold shadow-sm" onclick="openAddVideoModal()">
                <i class="fa-solid fa-plus me-1"></i> เพิ่มวิดีโอใหม่
            </button>
        </div>
    </div>

    <!-- Stats & Overview Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block mb-1">วิดีโอทั้งหมด</span>
                <h3 class="fw-bold text-dark mb-0" id="statTotalVideos"><?= count($videos) ?></h3>
                <small class="text-muted" style="font-size: 0.75rem;">คลิปในระบบ Web TV</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block mb-1">ยอดผู้รับชมรวม</span>
                <?php 
                $totalViews = array_sum(array_column($videos, 'views'));
                ?>
                <h3 class="fw-bold text-danger mb-0"><?= number_format($totalViews) ?></h3>
                <small class="text-muted" style="font-size: 0.75rem;">ครั้ง (All Views)</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block mb-1">หมวดหมู่วิดีโอ</span>
                <h3 class="fw-bold text-primary mb-0"><?= count($categories) ?></h3>
                <small class="text-muted" style="font-size: 0.75rem;">หมวดหมู่หลัก</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <span class="text-muted small d-block mb-1">การแสดงผลหน้าแรก</span>
                <h3 class="fw-bold text-success mb-0">6</h3>
                <small class="text-muted" style="font-size: 0.75rem;">คลิปล่าสุดบนหน้าแรก</small>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control border-start-0" id="searchVideoInput" placeholder="ค้นหาชื่อวิดีโอ, หมวดหมู่, หรือ YouTube ID..." oninput="filterVideos()">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" id="filterCategorySelect" onchange="filterVideos()">
                    <option value="all">📁 ทุกหมวดหมู่ (All Categories)</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="resetVideoFilter()">
                    <i class="fa-solid fa-rotate-left me-1"></i> ล้างตัวกรอง
                </button>
            </div>
        </div>
    </div>

    <!-- Video Grid -->
    <div class="row g-3" id="videoGridContainer">
        <?php if (empty($videos)): ?>
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-video-slash fs-1 text-muted mb-3 d-block"></i>
                <h6 class="text-muted">ยังไม่มีรายการวิดีโอในระบบ</h6>
                <button type="button" class="btn btn-danger btn-sm rounded-pill mt-2" onclick="openAddVideoModal()">
                    <i class="fa-solid fa-plus me-1"></i> เพิ่มวิดีโอแรกของคุณ
                </button>
            </div>
        <?php else: ?>
            <?php foreach ($videos as $vid): 
                $yId = esc($vid['youtube_id'] ?? '');
                $thumbUrl = "https://img.youtube.com/vi/{$yId}/hqdefault.jpg";
                $vidTitle = esc($vid['title'] ?? '');
                $vidDesc = esc($vid['desc'] ?? '');
                $vidCat = esc($vid['category'] ?? 'ทั่วไป');
                $vidDate = !empty($vid['date']) ? date('d/m/Y', strtotime($vid['date'])) : '-';
                $vidViews = number_format($vid['views'] ?? 0);
            ?>
                <div class="col-12 col-md-6 col-lg-4 video-item-card" data-title="<?= strtolower($vidTitle) ?>" data-category="<?= esc($vidCat) ?>" data-youtube="<?= strtolower($yId) ?>">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-column justify-content-between" style="border: 1px solid rgba(0,0,0,0.06) !important;">
                        <div>
                            <!-- Thumbnail Area -->
                            <div class="position-relative bg-dark overflow-hidden" style="padding-top: 56.25%;">
                                <img src="<?= $thumbUrl ?>" alt="<?= $vidTitle ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" onerror="this.src='https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80'">
                                <span class="badge position-absolute top-0 start-0 m-2.5 px-2.5 py-1 rounded-pill bg-dark bg-opacity-75 text-warning small">
                                    <i class="fa-solid fa-folder me-1"></i> <?= $vidCat ?>
                                </span>
                                <span class="badge position-absolute bottom-0 end-0 m-2 px-2 py-1 rounded bg-black bg-opacity-75 text-white" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-eye text-warning me-1"></i> <?= $vidViews ?>
                                </span>
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-50 start-50 translate-middle rounded-circle shadow" style="width: 44px; height: 44px; padding: 0;" onclick="previewVideoPlayer('<?= $yId ?>', '<?= addslashes($vidTitle) ?>')">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                            </div>

                            <div class="p-3.5">
                                <h6 class="fw-bold mb-1 text-dark" style="line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= $vidTitle ?>
                                </h6>
                                <p class="text-muted small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= $vidDesc ?>
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer with Actions -->
                        <div class="px-3.5 py-2.5 bg-light border-top d-flex align-items-center justify-content-between small">
                            <span class="text-muted"><i class="fa-regular fa-calendar me-1"></i> <?= $vidDate ?></span>
                            <div class="d-flex align-items-center gap-1.5">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1" onclick="editVideo('<?= $vid['id'] ?>')" title="แก้ไข">
                                    <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" onclick="deleteVideo('<?= $vid['id'] ?>', '<?= addslashes($vidTitle) ?>')" title="ลบ">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<!-- ======================================================== -->
<!-- MODAL: ADD / EDIT YOUTUBE VIDEO -->
<!-- ======================================================== -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-danger text-white py-3 px-4" style="background: linear-gradient(135deg, #991b1b, #dc2626) !important;">
                <h5 class="modal-title fw-bold" id="videoModalTitle">
                    <i class="fa-solid fa-film me-2"></i> เพิ่มวิดีโอ YouTube ใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="videoForm" onsubmit="event.preventDefault(); saveVideo();">
                    <input type="hidden" id="videoId" name="id">

                    <!-- ชื่อวิดีโอ -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อวิดีโอ (Video Title) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="videoTitle" name="title" required placeholder="เช่น มหัศจรรย์ทะเลน้อย: สวรรค์ของนกน้ำและสายน้ำ">
                    </div>

                    <!-- ลิงก์ YouTube / ID -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">ลิงก์วิดีโอ YouTube หรือ YouTube ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-danger"><i class="fa-brands fa-youtube fs-5"></i></span>
                            <input type="text" class="form-control" id="videoYoutubeUrl" name="youtube_url" required placeholder="วางลิงก์ YouTube (เช่น https://www.youtube.com/watch?v=dQw4w9WgXcQ หรือ dQw4w9WgXcQ)" oninput="previewYoutubeInput(this.value)">
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="fa-solid fa-circle-info me-1"></i> รองรับทุกลิงก์ YouTube: youtube.com/watch?v=..., youtu.be/..., shorts/..., หรือพิมพ์เฉพาะ Video ID 11 หลัก
                        </small>
                    </div>

                    <!-- Live Video Preview Box -->
                    <div id="youtubePreviewContainer" class="mb-3 p-2 rounded-3 border bg-light d-none">
                        <span class="small fw-bold text-secondary d-block mb-1"><i class="fa-solid fa-circle-play text-danger me-1"></i> ตัวอย่างภาพหน้าปก / วิดีโอ</span>
                        <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm" style="max-height: 240px;">
                            <img id="youtubeThumbPreview" src="" alt="YouTube Preview" class="w-100 h-100 object-fit-cover">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- หมวดหมู่ -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">หมวดหมู่ (Category)</label>
                            <select class="form-select" id="videoCategory" name="category">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- วันที่ -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">คำอธิบายสั้น (Short Description)</label>
                            <input type="text" class="form-control" id="videoDesc" name="desc" placeholder="เช่น สารคดีเจาะลึกศิลปะการร่ายรำโนราห์">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger px-4 fw-bold" id="btnSaveVideo" onclick="saveVideo()">
                    <i class="fa-solid fa-save me-1"></i> บันทึกข้อมูล
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: VIDEO PLAYER PREVIEW -->
<!-- ======================================================== -->
<div class="modal fade" id="videoPlayerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg bg-dark text-white overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-white text-truncate" id="playerModalTitle"></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closePlayer()"></button>
            </div>
            <div class="modal-body p-3">
                <div class="ratio ratio-16x9 rounded overflow-hidden">
                    <iframe id="playerIframe" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let videoModal;
let playerModal;

document.addEventListener('DOMContentLoaded', function() {
    videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
    playerModal = new bootstrap.Modal(document.getElementById('videoPlayerModal'));

    document.getElementById('videoPlayerModal').addEventListener('hidden.bs.modal', function () {
        closePlayer();
    });
});

function openAddVideoModal() {
    document.getElementById('videoForm').reset();
    document.getElementById('videoId').value = '';
    document.getElementById('videoModalTitle').innerHTML = '<i class="fa-solid fa-plus me-2"></i> เพิ่มวิดีโอ YouTube ใหม่';
    document.getElementById('youtubePreviewContainer').classList.add('d-none');
    videoModal.show();
}

function extractYoutubeId(url) {
    if (!url) return '';
    url = url.trim();
    if (/^[a-zA-Z0-9_-]{11}$/.test(url)) return url;
    
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|shorts\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : '';
}

function previewYoutubeInput(val) {
    const yId = extractYoutubeId(val);
    const container = document.getElementById('youtubePreviewContainer');
    const img = document.getElementById('youtubeThumbPreview');

    if (yId) {
        img.src = `https://img.youtube.com/vi/${yId}/hqdefault.jpg`;
        container.classList.remove('d-none');
    } else {
        container.classList.add('d-none');
    }
}

async function editVideo(id) {
    try {
        const res = await App.fetch(`<?= base_url('admin/videos/get-item') ?>/${id}`);
        if (res.status === 'success') {
            const v = res.data;
            document.getElementById('videoId').value = v.id;
            document.getElementById('videoTitle').value = v.title;
            document.getElementById('videoYoutubeUrl').value = v.youtube_id;
            document.getElementById('videoCategory').value = v.category || 'ท่องเที่ยวและธรรมชาติ';
            document.getElementById('videoDesc').value = v.desc || '';

            previewYoutubeInput(v.youtube_id);

            document.getElementById('videoModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2"></i> แก้ไขวิดีโอ: ' + v.title;
            videoModal.show();
        } else {
            App.toast(res.message, 'error');
        }
    } catch (err) {
        App.toast('ไม่สามารถโหลดข้อมูลวิดีโอได้', 'error');
    }
}

async function saveVideo() {
    const form = document.getElementById('videoForm');
    const title = document.getElementById('videoTitle').value.trim();
    const ytInput = document.getElementById('videoYoutubeUrl').value.trim();

    if (!title || !ytInput) {
        App.toast('กรุณากรอกชื่อวิดีโอและลิงก์ YouTube ให้ครบถ้วน', 'warning');
        return;
    }

    const yId = extractYoutubeId(ytInput);
    if (!yId) {
        App.toast('รูปแบบลิงก์ YouTube ไม่ถูกต้อง', 'warning');
        return;
    }

    const btn = document.getElementById('btnSaveVideo');
    const origText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังบันทึก...';

    const formData = new FormData(form);

    try {
        const res = await App.fetch('<?= base_url("admin/videos/save-item") ?>', {
            method: 'POST',
            body: formData
        });

        if (res && res.status === 'success') {
            App.toast(res.message, 'success');
            videoModal.hide();
            setTimeout(() => window.location.reload(), 800);
        } else {
            App.toast(res ? res.message : 'บันทึกข้อมูลไม่สำเร็จ', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = origText;
    }
}

async function deleteVideo(id, title) {
    if (confirm(`คุณแน่ใจหรือไม่ที่จะลบวิดีโอ "${title}" ?`)) {
        try {
            const res = await App.fetch(`<?= base_url('admin/videos/delete-item') ?>/${id}`, {
                method: 'POST'
            });
            if (res.status === 'success') {
                App.toast(res.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                App.toast(res.message, 'error');
            }
        } catch (err) {
            App.toast('เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
        }
    }
}

function filterVideos() {
    const q = document.getElementById('searchVideoInput').value.toLowerCase().trim();
    const cat = document.getElementById('filterCategorySelect').value;

    document.querySelectorAll('.video-item-card').forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const yId = card.getAttribute('data-youtube') || '';
        const itemCat = card.getAttribute('data-category') || '';

        const matchQ = !q || title.includes(q) || yId.includes(q) || itemCat.toLowerCase().includes(q);
        const matchCat = (cat === 'all' || itemCat === cat);

        if (matchQ && matchCat) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function resetVideoFilter() {
    document.getElementById('searchVideoInput').value = '';
    document.getElementById('filterCategorySelect').value = 'all';
    filterVideos();
}

function previewVideoPlayer(yId, title) {
    document.getElementById('playerModalTitle').textContent = title;
    document.getElementById('playerIframe').src = `https://www.youtube-nocookie.com/embed/${yId}?autoplay=1&rel=0`;
    playerModal.show();
}

function closePlayer() {
    document.getElementById('playerIframe').src = '';
}
</script>

<?= $this->endSection() ?>

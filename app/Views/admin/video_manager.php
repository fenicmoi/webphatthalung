<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php
$videos = $videos ?? [];
$categories = $categories ?? [];
$totalViews = array_sum(array_column($videos, 'views'));
?>

<!-- Hero Cockpit Header -->
<div class="glass-card mb-4 p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 28px; background: linear-gradient(135deg, rgba(220, 38, 38, 0.12) 0%, rgba(147, 51, 234, 0.10) 50%, rgba(59, 130, 246, 0.08) 100%); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="position-absolute" style="top: -40px; right: -30px; font-size: 15rem; color: rgba(220, 38, 38, 0.04); pointer-events: none; z-index: 0;">
        <i class="fa-solid fa-film"></i>
    </div>
    
    <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(220, 38, 38, 0.15); border: 1px solid rgba(220, 38, 38, 0.3); color: #dc2626; font-size: 0.85rem; font-weight: 600;">
                <i class="fa-brands fa-youtube"></i> Web TV & Provincial Media Studio
            </div>
            <h2 class="fw-bold mb-2" style="color: var(--text-primary); letter-spacing: -0.5px;">
                สตูดิโอสื่อวีดิทัศน์ Web TV & YouTube
            </h2>
            <p class="text-secondary mb-0" style="font-size: 1rem; max-width: 760px;">
                จัดการและเผยแพร่วีดิทัศน์ประชาสัมพันธ์ ท่องเที่ยว วัฒนธรรมมโนราห์ และกิจกรรมสำคัญของจังหวัดพัทลุง แสดงผลทั้งบนหน้าหลักและห้องสมุด Web TV ประชาชน
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="<?= base_url('videos') ?>" target="_blank" class="btn btn-outline-secondary px-3 py-2 rounded-4 fw-medium d-flex align-items-center gap-2 shadow-sm transition-all" style="border-width: 1.5px; background: var(--bg-surface);">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> <span>ดูหน้า Web TV</span>
            </a>
            <button type="button" class="btn btn-danger px-4 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow transition-all" onclick="openAddVideoModal()" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); border: none; font-size: 1rem;">
                <i class="fa-solid fa-circle-plus"></i> <span>เพิ่มวิดีโอใหม่</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Row inside Cockpit -->
    <div class="position-relative z-1 row g-3 mt-3 pt-3 border-top" style="border-color: var(--glass-border) !important;">
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-video text-danger me-1"></i> วิดีโอทั้งหมดในคลัง</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold" id="statTotalVideos" style="color: var(--text-primary);"><?= number_format(count($videos)) ?></span>
                    <span class="small text-secondary">คลิปในระบบ</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-fire-flame-curved text-warning me-1"></i> ยอดผู้รับชมรวม</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-danger"><?= number_format($totalViews) ?></span>
                    <span class="small text-secondary">ครั้ง (All Views)</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-regular fa-folder-open text-primary me-1"></i> หมวดหมู่สื่อสารคดี</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-primary"><?= count($categories) ?></span>
                    <span class="small text-secondary">หมวดหมู่หลัก</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-tv text-success me-1"></i> จุดแสดงผลหน้าหลัก</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Featured 6 คลิปเด่น</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="glass-card mb-4 p-3 rounded-4" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
    <div class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--glass-border); color: var(--text-secondary);">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" class="form-control custom-input border-start-0" id="searchVideoInput" placeholder="ค้นหาชื่อคลิปวิดีโอ, คำอธิบาย, หรือรหัส YouTube ID..." oninput="filterVideos()">
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-select custom-input" id="filterCategorySelect" onchange="filterVideos()">
                <option value="all">📁 ทุกหมวดหมู่ (All Categories)</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-outline-secondary w-100 rounded-3 py-2 fw-medium" onclick="resetVideoFilter()">
                <i class="fa-solid fa-rotate-left me-1"></i> ล้างตัวกรอง
            </button>
        </div>
    </div>
</div>

<!-- Video Grid -->
<div class="row g-4" id="videoGridContainer">
    <?php if (empty($videos)): ?>
        <div class="col-12 text-center py-5">
            <div class="glass-card p-5 rounded-4 text-center" style="border: 2px dashed var(--glass-border); background: var(--bg-surface);">
                <i class="fa-solid fa-video-slash fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                <h5 class="fw-bold mb-1" style="color: var(--text-primary);">ยังไม่มีคลิปวิดีโอในคลัง</h5>
                <p class="text-secondary small mb-3">กดปุ่มด้านล่างเพื่อเพิ่มลิงก์ YouTube แรกของคุณ</p>
                <button type="button" class="btn btn-danger rounded-pill px-4 py-2 fw-bold" onclick="openAddVideoModal()">
                    <i class="fa-solid fa-plus me-1"></i> เพิ่มวิดีโอแรกของคุณ
                </button>
            </div>
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
            <div class="col-12 col-md-6 col-xl-4 video-item-card" data-title="<?= strtolower($vidTitle) ?>" data-category="<?= esc($vidCat) ?>" data-youtube="<?= strtolower($yId) ?>">
                <div class="glass-card h-100 rounded-4 overflow-hidden d-flex flex-column justify-content-between transition-all" style="border: 1px solid var(--glass-border); background: var(--bg-surface); box-shadow: var(--glass-shadow);">
                    <div>
                        <!-- Thumbnail Area with 16:9 ratio -->
                        <div class="position-relative overflow-hidden group-hover-zoom" style="padding-top: 56.25%; background: #000;">
                            <img src="<?= $thumbUrl ?>" alt="<?= $vidTitle ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" onerror="this.src='https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80'" style="transition: transform 0.4s ease;">
                            
                            <!-- Badges -->
                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-1.5 rounded-pill shadow-sm" style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.15); color: #fde047; font-size: 0.78rem;">
                                <i class="fa-solid fa-folder me-1"></i> <?= $vidCat ?>
                            </span>
                            <span class="badge position-absolute bottom-0 end-0 m-3 px-2.5 py-1 rounded-pill shadow-sm" style="background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px); color: #fff; font-size: 0.75rem;">
                                <i class="fa-solid fa-eye text-warning me-1"></i> <?= $vidViews ?>
                            </span>

                            <!-- Play Button Hover Trigger -->
                            <button type="button" class="btn btn-danger position-absolute top-50 start-50 translate-middle rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: linear-gradient(135deg, #ef4444, #dc2626); border: 2px solid rgba(255,255,255,0.8);" onclick="previewVideoPlayer('<?= $yId ?>', '<?= addslashes($vidTitle) ?>')" title="เล่นตัวอย่างวิดีโอ">
                                <i class="fa-solid fa-play fs-5 ms-0.5 text-white"></i>
                            </button>
                        </div>

                        <!-- Card Content -->
                        <div class="p-3.5">
                            <h6 class="fw-bold mb-1" style="color: var(--text-primary); line-height: 1.45; font-size: 1rem; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?= $vidTitle ?>">
                                <?= $vidTitle ?>
                            </h6>
                            <p class="text-secondary small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.88rem; line-height: 1.5;">
                                <?= !empty($vidDesc) ? $vidDesc : 'ไม่มีคำอธิบายเพิ่มเติม' ?>
                            </p>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="px-3.5 py-2.5 border-top d-flex align-items-center justify-content-between" style="border-color: var(--glass-border) !important; background: rgba(0,0,0,0.02);">
                        <span class="text-secondary small" style="font-size: 0.82rem;">
                            <i class="fa-regular fa-calendar me-1"></i> <?= $vidDate ?>
                        </span>
                        <div class="d-flex align-items-center gap-1.5">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-medium" onclick="editVideo('<?= $vid['id'] ?>')" title="แก้ไขรายละเอียด">
                                <i class="fa-solid fa-pen-to-square me-1"></i> แก้ไข
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" onclick="deleteVideo('<?= $vid['id'] ?>', '<?= addslashes($vidTitle) ?>')" title="ลบคลิปนี้">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ======================================================== -->
<!-- MODAL: ADD / EDIT YOUTUBE VIDEO -->
<!-- ======================================================== -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden" style="background: var(--bg-surface); border: 1px solid var(--glass-border) !important;">
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 100%); color: #fff;">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 38px; height: 38px; background: rgba(255,255,255,0.2);">
                        <i class="fa-solid fa-film"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="videoModalTitle">เพิ่มวิดีโอ YouTube ใหม่</h5>
                        <small style="color: rgba(255,255,255,0.85); font-size: 0.85rem;">เชื่อมโยงคลิปจาก YouTube สู่ระบบ Web TV พัทลุง</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: var(--bg-surface);">
                <form id="videoForm" onsubmit="event.preventDefault(); saveVideo();">
                    <input type="hidden" id="videoId" name="id">

                    <!-- ชื่อวิดีโอ -->
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--text-primary);">ชื่อวิดีโอ (Video Title) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control custom-input" id="videoTitle" name="title" required placeholder="เช่น มหัศจรรย์ทะเลน้อย: สวรรค์ของนกน้ำและสายน้ำ">
                    </div>

                    <!-- ลิงก์ YouTube / ID -->
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--text-primary);">ลิงก์วิดีโอ YouTube หรือ Video ID <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-danger" style="border-color: var(--glass-border);"><i class="fa-brands fa-youtube fs-5"></i></span>
                            <input type="text" class="form-control custom-input border-start-0" id="videoYoutubeUrl" name="youtube_url" required placeholder="วางลิงก์ เช่น https://www.youtube.com/watch?v=... หรือ youtu.be/..." oninput="previewYoutubeInput(this.value)">
                        </div>
                        <small class="text-secondary mt-1 d-block" style="font-size: 0.82rem;">
                            <i class="fa-solid fa-circle-info me-1"></i> รองรับทั้งลิงก์แบบเต็ม, ลิงก์สั้น youtu.be, YouTube Shorts, หรือ Video ID 11 หลัก
                        </small>
                    </div>

                    <!-- Live Video Preview Box -->
                    <div id="youtubePreviewContainer" class="mb-3 p-3 rounded-4 d-none" style="background: var(--bg-secondary); border: 1px solid var(--glass-border);">
                        <span class="small fw-bold text-secondary d-block mb-2"><i class="fa-solid fa-circle-play text-danger me-1"></i> ตัวอย่างภาพหน้าปกอัตโนมัติจาก YouTube</span>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm" style="max-height: 240px; border: 1px solid var(--glass-border);">
                            <img id="youtubeThumbPreview" src="" alt="YouTube Preview" class="w-100 h-100 object-fit-cover">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- หมวดหมู่ -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">หมวดหมู่สื่อ (Category)</label>
                            <select class="form-select custom-input" id="videoCategory" name="category">
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- คำอธิบายสั้น -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--text-primary);">คำอธิบายสั้น (Short Description)</label>
                            <input type="text" class="form-control custom-input" id="videoDesc" name="desc" placeholder="เช่น สารคดีเจาะลึกศิลปวัฒนธรรมเมืองลุง">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top px-4 py-3" style="border-color: var(--glass-border) !important; background: var(--bg-secondary);">
                <button type="button" class="btn btn-outline-secondary px-3.5 rounded-3 fw-medium" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger px-4 rounded-3 fw-bold shadow-sm" id="btnSaveVideo" onclick="saveVideo()" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none;">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> บันทึกข้อมูล
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
        <div class="modal-content rounded-4 border-0 shadow-lg bg-dark text-white overflow-hidden" style="box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7) !important;">
            <div class="modal-header border-0 pb-0 px-4 pt-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 overflow-hidden pe-3">
                    <i class="fa-brands fa-youtube text-danger fs-5"></i>
                    <h6 class="modal-title fw-bold text-white text-truncate mb-0" id="playerModalTitle" style="font-size: 0.95rem;"></h6>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closePlayer()"></button>
            </div>
            <div class="modal-body p-4">
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow" style="border: 1px solid rgba(255,255,255,0.1);">
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
    document.getElementById('videoModalTitle').innerHTML = 'เพิ่มวิดีโอ YouTube ใหม่';
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

            document.getElementById('videoModalTitle').innerHTML = 'แก้ไขวิดีโอ: ' + v.title;
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
        App.toast('รูปแบบลิงก์ YouTube ไม่ถูกต้อง กรุณาตรวจสอบ', 'warning');
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
            App.toast(res.message || 'บันทึกข้อมูลวิดีโอเรียบร้อย', 'success');
            videoModal.hide();
            setTimeout(() => window.location.reload(), 700);
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
                App.toast(res.message || 'ลบวิดีโอเรียบร้อยแล้ว', 'success');
                setTimeout(() => window.location.reload(), 700);
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

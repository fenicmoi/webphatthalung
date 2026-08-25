<?php
$isOfficer = session()->get('isLoggedIn');
if (!$isOfficer) {
    return; // Only rendered for logged in officers/admins
}
?>

<style>
/* ==========================================================================
   ON-PAGE LIVE TEXT & CONTENT EDITOR STUDIO (2026+ VISUAL CMS)
   ========================================================================== */

/* Floating Live Text Edit Mode Trigger Button (Top Left / Bottom Left Float) */
.live-edit-mode-pill {
    position: fixed;
    top: 90px;
    right: 20px;
    z-index: 1060;
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
    color: #ffffff !important;
    border: 2px solid #818cf8;
    border-radius: 50px;
    padding: 8px 16px;
    box-shadow: 0 10px 25px rgba(67, 56, 202, 0.45), 0 0 20px rgba(129, 140, 248, 0.3);
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.85rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-decoration: none;
}
.live-edit-mode-pill:hover {
    transform: translateY(-2px) scale(1.04);
    box-shadow: 0 12px 30px rgba(67, 56, 202, 0.6);
    color: #ffffff !important;
    border-color: #a5b4fc;
}
.live-edit-mode-pill.active {
    background: linear-gradient(135deg, #047857 0%, #059669 50%, #10b981 100%);
    border-color: #34d399;
    box-shadow: 0 0 25px rgba(16, 185, 129, 0.6);
}

/* Editable Node Highlights when Live Edit Mode is Active */
body.live-text-edit-active .site-text-node {
    outline: 2px dashed #6366f1 !important;
    outline-offset: 3px;
    background: rgba(99, 102, 241, 0.12) !important;
    cursor: pointer !important;
    position: relative;
    border-radius: 4px;
    transition: all 0.2s ease;
    display: inline-block;
}
body.live-text-edit-active .site-text-node:hover {
    outline: 2px solid #10b981 !important;
    background: rgba(16, 185, 129, 0.25) !important;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.5);
}
body.live-text-edit-active .site-text-node::after {
    content: "✏️";
    font-size: 0.7rem;
    position: absolute;
    top: -10px;
    right: -10px;
    background: #4338ca;
    color: #fff;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

/* Modal styling */
.live-editor-modal-content {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 2px solid rgba(129, 140, 248, 0.4);
    border-radius: 1.5rem;
    color: #ffffff;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
}
</style>

<!-- Floating Live Edit Mode Switch Button -->
<button type="button" class="live-edit-mode-pill" id="btnLiveEditToggle" onclick="LiveTextEditor.toggle()" title="เปิด/ปิดโหมดแก้ไขข้อความสดบนหน้าเว็บ (Visual Text CMS)">
    <i class="fa-solid fa-pen-to-square"></i>
    <span id="liveEditTextLabel">โหมดแก้ไขข้อความ</span>
</button>

<!-- Live Text Edit Modal -->
<div class="modal fade" id="liveTextEditModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content live-editor-modal-content">
            <div class="modal-header border-bottom border-secondary px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fa-solid fa-pen-nib"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-white mb-0" id="liveEditModalTitle">แก้ไขข้อความ</h6>
                        <small class="text-info fw-mono" id="liveEditKeyBadge" style="font-size: 0.75rem;"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="liveEditTargetKey">
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-light small mb-1">
                        <i class="fa-solid fa-keyboard me-1 text-primary"></i> เนื้อหาข้อความ (Text Content)
                    </label>
                    <textarea id="liveEditTextArea" class="form-control" rows="4" 
                              style="background: rgba(30, 41, 59, 0.9); color: #ffffff; border: 1.5px solid rgba(255, 255, 255, 0.2); border-radius: 12px; font-size: 1rem;"></textarea>
                </div>

                <div class="p-3 rounded-3 mb-2" style="background: rgba(255,255,255,0.06); border: 1px dashed rgba(255,255,255,0.2);">
                    <small class="text-warning fw-bold d-block mb-1"><i class="fa-solid fa-eye me-1"></i> ตัวอย่างแสดงผลสด (Live Preview):</small>
                    <div id="liveEditPreviewBox" class="text-white" style="font-size: 0.95rem;"></div>
                </div>
            </div>
            <div class="modal-footer border-top border-secondary px-4 py-3 d-flex justify-content-between">
                <a href="<?= base_url('admin/site-texts') ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill" title="เปิดหน้าจัดการข้อความทั้งหมดในระบบหลังบ้าน">
                    <i class="fa-solid fa-sliders me-1"></i> จัดการทั้งหมดใน Admin
                </a>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-success fw-bold rounded-pill px-4" onclick="LiveTextEditor.saveCurrent()">
                        <i class="fa-solid fa-check me-1"></i> บันทึกข้อความ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.LiveTextEditor = (function() {
    let isActive = false;
    let currentTargetNode = null;

    function init() {
        const textNodes = document.querySelectorAll('.site-text-node');
        textNodes.forEach(node => {
            node.addEventListener('click', function(e) {
                if (!isActive) return;
                e.preventDefault();
                e.stopPropagation();
                openModalForNode(this);
            });
        });

        // Live typing preview
        const area = document.getElementById('liveEditTextArea');
        const prev = document.getElementById('liveEditPreviewBox');
        if (area && prev) {
            area.addEventListener('input', function() {
                prev.innerText = this.value;
            });
        }
    }

    function toggle() {
        isActive = !isActive;
        const btn = document.getElementById('btnLiveEditToggle');
        const label = document.getElementById('liveEditTextLabel');

        if (isActive) {
            document.body.classList.add('live-text-edit-active');
            if (btn) btn.classList.add('active');
            if (label) label.innerText = 'ปิดโหมดแก้ไขข้อความ (กำลังเปิด)';
            if (typeof App !== 'undefined' && App.toast) App.toast('เปิดโหมดแก้ไขข้อความแล้ว คลิกที่ข้อความที่มีกรอบเพื่อแก้ไขได้ทันที', 'info');
        } else {
            document.body.classList.remove('live-text-edit-active');
            if (btn) btn.classList.remove('active');
            if (label) label.innerText = 'โหมดแก้ไขข้อความ';
            if (typeof App !== 'undefined' && App.toast) App.toast('ปิดโหมดแก้ไขข้อความแล้ว', 'info');
        }
    }

    function openModalForNode(node) {
        currentTargetNode = node;
        const key = node.getAttribute('data-text-key') || '';
        const label = node.getAttribute('data-text-label') || key;
        const currentText = node.innerText;

        document.getElementById('liveEditTargetKey').value = key;
        document.getElementById('liveEditModalTitle').innerText = 'แก้ไข: ' + label;
        document.getElementById('liveEditKeyBadge').innerText = 'key: ' + key;
        
        const area = document.getElementById('liveEditTextArea');
        const prev = document.getElementById('liveEditPreviewBox');
        if (area) area.value = currentText;
        if (prev) prev.innerText = currentText;

        const modalEl = document.getElementById('liveTextEditModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    }

    function saveCurrent() {
        const key = document.getElementById('liveEditTargetKey').value;
        const val = document.getElementById('liveEditTextArea').value;

        if (!key) return;

        const fd = new FormData();
        fd.append('is_ajax_single', '1');
        fd.append('text_key', key);
        fd.append('text_value', val);
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch("<?= base_url('admin/site-texts/save') ?>", {
            method: "POST",
            body: fd,
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                // Update all nodes on the page that share this key
                document.querySelectorAll(`.site-text-node[data-text-key="${key}"]`).forEach(n => {
                    n.innerText = val;
                });

                const modalEl = document.getElementById('liveTextEditModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                if (typeof App !== 'undefined' && App.toast) {
                    App.toast('บันทึกข้อความเรียบร้อยแล้ว!', 'success');
                }
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการบันทึก');
            }
        })
        .catch(err => {
            console.error(err);
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
        });
    }

    document.addEventListener('DOMContentLoaded', init);

    return {
        toggle,
        saveCurrent
    };
})();
</script>

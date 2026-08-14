<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
helper('settings');
$isOfficer = session()->get('isLoggedIn');
$categories = $categories ?? get_document_categories();
$selectedCat = $selectedCat ?? 'all';
$documents = $documents ?? [];
?>

<style>
/* --- Premium Modern Smart Document Archive Styling --- */
.doc-hero {
    background: linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #047857 100%);
    border-radius: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(4, 120, 87, 0.25);
}
.doc-hero::after {
    content: '';
    position: absolute;
    top: -60px;
    right: -40px;
    width: 360px;
    height: 360px;
    background: radial-gradient(circle, rgba(52, 211, 153, 0.22) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.pillar-card {
    border: 1px solid var(--glass-border);
    border-radius: 1.25rem;
    background: var(--card-bg, #ffffff);
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    height: 100%;
    cursor: pointer;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.pillar-card:hover, .pillar-card.active-pillar {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(16, 185, 129, 0.2);
    border-color: #10b981 !important;
}
.pillar-card.active-pillar {
    background: linear-gradient(135deg, #064e3b, #047857);
    color: #ffffff !important;
}
.pillar-card.active-pillar .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}
.pillar-card.active-pillar .pillar-title {
    color: #ffffff !important;
}
.pillar-icon-box {
    width: 54px;
    height: 54px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #ffffff;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}
.doc-row-card {
    border: 1px solid var(--glass-border);
    border-radius: 1rem;
    background: var(--card-bg, #ffffff);
    transition: all 0.25s ease;
    padding: 1.25rem;
}
.doc-row-card:hover {
    transform: translateX(6px);
    border-color: #10b981;
    box-shadow: 0 6px 20px rgba(0,0,0,0.07);
}
.file-badge-icon {
    width: 52px;
    height: 52px;
    border-radius: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
}
.file-pdf { background: #fee2e2; color: #dc2626; }
.file-doc, .file-docx { background: #dbeafe; color: #2563eb; }
.file-xls, .file-xlsx, .file-csv { background: #dcfce7; color: #16a34a; }
.file-zip, .file-rar { background: #fef3c7; color: #d97706; }
.file-link { background: #f3e8ff; color: #9333ea; }
.search-input-box {
    border-radius: 50rem !important;
    padding-left: 3rem;
    border: 2px solid rgba(16, 185, 129, 0.3);
    background: var(--card-bg, #ffffff);
    color: var(--text-primary);
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    height: 54px;
}
.search-input-box:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
}
</style>

<div class="container py-4">
    <!-- Hero Header Banner -->
    <div class="doc-hero p-4 p-md-5 mb-5 text-white">
        <div class="row align-items-center relative z-1">
            <div class="col-lg-8">
                <span class="badge rounded-pill bg-success bg-opacity-75 px-3 py-2 fw-bold text-white mb-3 text-uppercase" style="letter-spacing: 1px;">
                    <i class="fa-solid fa-folder-open text-warning me-1"></i> Smart Digital Document Hub
                </span>
                <h1 class="display-6 fw-bolder mb-2 d-flex align-items-center gap-3">
                    <i class="fa-solid fa-cloud-arrow-down text-success fs-1"></i>
                    <span>ศูนย์รวมไฟล์ดาวน์โหลดและคลังเอกสารดิจิทัล</span>
                </h1>
                <p class="text-white text-opacity-85 lead fs-6 mb-0 max-w-2xl">
                    บริการคลังเอกสารสาธารณะ หนังสือราชการ แผนปฏิบัติงาน และข้อมูลการประเมิน ITA จัดกลุ่มโครงสร้าง 5 เสาหลัก เพื่อการสืบค้นที่สะดวกและรวดเร็วที่สุด
                </p>
            </div>
            <div class="col-lg-4 text-start text-lg-end mt-4 mt-lg-0">
                <?php if ($isOfficer): ?>
                    <button type="button" onclick="DocumentStudio.open()" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold text-dark shadow-lg hover-scale d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-file-circle-plus fs-5 text-success"></i>
                        <span>นำเข้าไฟล์สู่คลังเอกสาร (Studio)</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 5 Smart Pillars Folders (Mega-Category Navigation) -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold m-0" style="color: var(--text-primary);">
                <i class="fa-solid fa-layer-group text-success me-2"></i>เลือกหมวดหมู่เอกสาร 5 เสาหลัก (5 Smart Pillars):
            </h5>
            <?php if ($selectedCat !== 'all'): ?>
                <a href="<?= base_url('documents') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold">
                    <i class="fa-solid fa-rotate-left me-1"></i> แสดงเอกสารทุกหมวดหมู่
                </a>
            <?php endif; ?>
        </div>
        <div class="row g-3">
            <?php foreach ($categories as $catKey => $catInfo): 
                $isActive = ($selectedCat === $catInfo['name']);
                // นับจำนวนไฟล์ในกลุ่มนี้
                $countDocs = 0;
                foreach ($documents as $dItem) {
                    if (strcasecmp(trim($dItem['category'] ?? ''), trim($catInfo['name'])) === 0) $countDocs++;
                }
            ?>
                <div class="col-12 col-md-6 col-xl">
                    <a href="<?= base_url('documents/category/' . urlencode((string)$catInfo['name'])) ?>" class="text-decoration-none">
                        <div class="pillar-card p-4 <?= $isActive ? 'active-pillar' : '' ?>">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="pillar-icon-box" style="background: <?= $catInfo['gradient'] ?>;">
                                    <i class="<?= $catInfo['icon'] ?>"></i>
                                </div>
                                <span class="badge rounded-pill bg-dark bg-opacity-50 text-white px-3 py-1 fw-bold">
                                    <?= $countDocs ?> ไฟล์
                                </span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-2 pillar-title" style="color: var(--text-primary); font-size: 0.95rem; line-height: 1.4;">
                                    <?= esc($catInfo['name']) ?>
                                </h6>
                                <p class="text-muted small mb-0" style="font-size: 0.76rem; line-height: 1.4;">
                                    <?= esc($catInfo['desc']) ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Live Search & Filter Bar -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-8">
            <div class="position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y text-success fs-5 ms-3"></i>
                <input type="text" id="liveDocSearch" class="form-control search-input-box fs-6" placeholder="พิมพ์ชื่อเอกสาร, เลขคำสั่ง, ปีงบประมาณ หรือคำค้นสำคัญ (ค้นหาแบบ Instant Search)..." onkeyup="filterDocuments()">
            </div>
        </div>
        <div class="col-12 col-md-4 text-start text-md-end">
            <span class="text-muted small">แสดงผลรายการเอกสาร: <strong id="visibleCount"><?= count($documents) ?></strong> / <strong><?= count($documents) ?></strong> รายการ</span>
        </div>
    </div>

    <!-- Document Items Table / Cards Grid -->
    <?php if (empty($documents)): ?>
        <div class="text-center py-5 my-5 glass-card rounded-5 border">
            <div class="py-4">
                <i class="fa-solid fa-file-circle-exclamation fs-1 text-muted mb-3 d-block"></i>
                <h5 class="fw-bold text-muted">ไม่พบไฟล์เอกสารในหมวดหมู่นี้</h5>
                <p class="text-muted small mb-0">เจ้าหน้าที่กำลังดำเนินการอัปโหลดไฟล์และปรับปรุงข้อมูลในฐานระบบ</p>
            </div>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3 mb-5" id="docContainer">
            <?php foreach ($documents as $doc): 
                $fType = strtolower($doc['file_type'] ?? 'pdf');
                $fIcon = 'fa-solid fa-file-pdf';
                if ($fType === 'doc' || $fType === 'docx') $fIcon = 'fa-solid fa-file-word';
                elseif ($fType === 'xls' || $fType === 'xlsx') $fIcon = 'fa-solid fa-file-excel';
                elseif ($fType === 'zip' || $fType === 'rar') $fIcon = 'fa-solid fa-file-ziper';
                elseif ($fType === 'link') $fIcon = 'fa-solid fa-link';

                $docDate = !empty($doc['date']) ? date('d/m/Y', strtotime($doc['date'])) : '-';
                $docUrl = !empty($doc['file_url']) && $doc['file_url'] !== '#' ? (strpos($doc['file_url'], 'http') === 0 ? $doc['file_url'] : base_url($doc['file_url'])) : '#';
            ?>
                <div class="doc-row-card shadow-sm d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 doc-item-row" data-title="<?= strtolower(esc($doc['title'] . ' ' . ($doc['sub_tag'] ?? '') . ' ' . $doc['category'])) ?>">
                    <div class="d-flex align-items-start align-items-md-center gap-3">
                        <div class="file-badge-icon file-<?= $fType ?>">
                            <i class="<?= $fIcon ?>"></i>
                        </div>
                        <div>
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-tag me-1"></i> <?= esc($doc['sub_tag'] ?? 'เอกสารราชการ') ?>
                                </span>
                                <span class="text-muted small"><i class="fa-regular fa-calendar me-1"></i> อัปเดต: <?= $docDate ?></span>
                                <span class="text-muted small"><i class="fa-solid fa-database me-1"></i> ขนาด: <?= esc($doc['file_size'] ?? '1.2 MB') ?></span>
                                <span class="text-muted small"><i class="fa-solid fa-cloud-arrow-down text-primary me-1"></i> โหลดแล้ว: <strong id="dl_count_<?= $doc['id'] ?>"><?= number_format($doc['downloads'] ?? 0) ?></strong> ครั้ง</span>
                            </div>
                            <h6 class="fw-bold m-0 text-dark" style="line-height: 1.4;">
                                <?= esc($doc['title']) ?>
                            </h6>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-shrink-0 align-self-end align-self-md-center mt-2 mt-md-0">
                        <?php if ($isOfficer): ?>
                            <div class="d-flex gap-1 me-2">
                                <button type="button" onclick="DocumentStudio.edit('<?= $doc['id'] ?>')" class="btn btn-sm btn-light text-primary rounded px-2" title="แก้ไข">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" onclick="DocumentStudio.delete('<?= $doc['id'] ?>', '<?= addslashes($doc['title']) ?>')" class="btn btn-sm btn-light text-danger rounded px-2" title="ลบ">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <a href="<?= $docUrl ?>" target="_blank" onclick="trackDownload('<?= $doc['id'] ?>')" class="btn btn-success rounded-pill px-4 py-2 fw-bold text-white shadow-sm hover-scale d-flex align-items-center gap-2">
                            <i class="fa-solid fa-download"></i>
                            <span>ดาวน์โหลดไฟล์</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($isOfficer): ?>
<!-- =====================================================================
     OFFICER ON-PAGE DOCUMENT STUDIO MODAL
     ===================================================================== -->
<div class="modal fade" id="docStudioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content glass-card border-0 rounded-4 shadow-lg p-3" style="background: var(--card-bg, #ffffff);">
            <div class="modal-header border-bottom pb-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-circle-plus text-success fs-3 animate-pulse"></i>
                    <span id="docStudioTitle">นำเข้าไฟล์และเอกสารใหม่</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="docStudioForm" onsubmit="DocumentStudio.save(event)" enctype="multipart/form-data">
                <div class="modal-body py-4">
                    <input type="hidden" id="d_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">ชื่อเอกสาร / ประกาศคำสั่ง / รายงาน <span class="text-danger">*</span></label>
                        <input type="text" class="form-control fw-bold fs-6" id="d_title" name="title" placeholder="ระบุชื่อเอกสารที่ถูกต้องตามกฎหมายหรือคำสั่ง" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">หมวดหมู่หลัก 5 เสาหลัก <span class="text-danger">*</span></label>
                            <select class="form-select" id="d_category" name="category" required>
                                <option value="กฎหมาย ระเบียบ และหนังสือราชการ">1. กฎหมาย ระเบียบ และหนังสือราชการ</option>
                                <option value="ยุทธศาสตร์ แผนงาน และรายงานผล">2. ยุทธศาสตร์ แผนงาน และรายงานผล</option>
                                <option value="ธรรมาภิบาล ความโปร่งใส และ ITA">3. ธรรมาภิบาล ความโปร่งใส และ ITA (OIT)</option>
                                <option value="คลังความรู้ สถิติ และงานวิจัย">4. คลังความรู้ สถิติ และงานวิจัย (GIS)</option>
                                <option value="คู่มือ นโยบายดิจิทัล และ ICT">5. คู่มือ นโยบายดิจิทัล และ ICT</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">ป้ายกำกับย่อย (Tag) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="d_sub_tag" name="sub_tag" placeholder="เช่น หนังสือเวียน, ประกาศ OIT, รายงานการเงิน">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label class="form-label fw-bold text-dark">อัปโหลดไฟล์จริงจากตัวเครื่อง (PDF/Word/Excel/ZIP)</label>
                            <input type="file" class="form-control" id="d_doc_file" name="doc_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar">
                            <div class="form-text small text-muted">ระบบจะคำนวณนามสกุลและขนาดไฟล์ให้โดยอัตโนมัติ</div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-dark">หรือระบุเป็นลิงก์ดาวน์โหลด (URL / Google Drive)</label>
                            <input type="text" class="form-control" id="d_file_url" name="file_url" placeholder="https://...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">วันที่ออกประกาศ / วันที่อัปเดต</label>
                        <input type="date" class="form-control w-50" id="d_date" name="date" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="modal-footer border-top pt-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold text-white shadow-sm d-flex align-items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>บันทึกและเผยแพร่สู่สาธารณะ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const DocumentStudio = {
    open: function() {
        document.getElementById('docStudioForm').reset();
        document.getElementById('d_id').value = '';
        document.getElementById('d_date').value = '<?= date('Y-m-d') ?>';
        document.getElementById('docStudioTitle').textContent = 'นำเข้าไฟล์และเอกสารใหม่';
        var modal = new bootstrap.Modal(document.getElementById('docStudioModal'));
        modal.show();
    },
    edit: function(id) {
        fetch('<?= base_url("admin/documents/get-item") ?>/' + id)
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                var d = res.data;
                document.getElementById('d_id').value = d.id || '';
                document.getElementById('d_title').value = d.title || '';
                document.getElementById('d_category').value = d.category || 'กฎหมาย ระเบียบ และหนังสือราชการ';
                document.getElementById('d_sub_tag').value = d.sub_tag || '';
                document.getElementById('d_file_url').value = d.file_url || '';
                if (d.date) document.getElementById('d_date').value = d.date;
                document.getElementById('docStudioTitle').textContent = 'แก้ไขข้อมูลไฟล์เอกสาร';
                var modal = new bootstrap.Modal(document.getElementById('docStudioModal'));
                modal.show();
            } else {
                Swal.fire('Error', res.message || 'ไม่สามารถโหลดข้อมูลได้', 'error');
            }
        });
    },
    save: function(e) {
        e.preventDefault();
        var form = document.getElementById('docStudioForm');
        var fd = new FormData(form);
        
        Swal.fire({
            title: 'กำลังบันทึกและจัดการไฟล์...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        fetch('<?= base_url("admin/documents/save-item") ?>', {
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
            title: 'ยืนยันการลบไฟล์และเอกสาร?',
            text: `ต้องการลบรายการ "${title}" ออกจากฐานคลังเอกสารหรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ลบไฟล์นี้',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url("admin/documents/delete-item") ?>/' + id, { method: 'POST' })
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
// Instant Live Search for Documents without Page Reloads!
function filterDocuments() {
    var input = document.getElementById("liveDocSearch").value.toLowerCase().trim();
    var rows = document.querySelectorAll(".doc-item-row");
    var visible = 0;
    
    rows.forEach(function(row) {
        var title = row.getAttribute("data-title") || "";
        if (title.indexOf(input) > -1 || input === "") {
            row.classList.remove("d-none");
            row.classList.add("d-flex");
            visible++;
        } else {
            row.classList.remove("d-flex");
            row.classList.add("d-none");
        }
    });
    
    var countLabel = document.getElementById("visibleCount");
    if (countLabel) countLabel.textContent = visible;
}

// Ajax Download Counter
function trackDownload(id) {
    fetch('<?= base_url("documents/count-download") ?>/' + id, { method: 'POST' })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success' && res.downloads) {
            var el = document.getElementById("dl_count_" + id);
            if (el) el.textContent = new Intl.NumberFormat().format(res.downloads);
        }
    });
}
</script>

<?= $this->endSection() ?>

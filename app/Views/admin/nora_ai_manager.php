<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php
$knowledge   = $knowledge ?? [];
$settings    = $settings ?? [];
$qaCount     = $qaCount ?? count($knowledge);
$recentNews  = $recentNews ?? 0;
$recentDocs  = $recentDocs ?? 0;
$recentProcs = $recentProcs ?? 0;
?>

<style>
/* Modern Executive Nora AI Studio Styles */
:root {
    --nora-primary: #4f46e5;
    --nora-primary-dark: #3730a3;
    --nora-accent: #f59e0b;
    --nora-bg-light: #f8fafc;
    --nora-card-border: rgba(226, 232, 240, 0.8);
}

.nora-hero {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    color: #ffffff;
    box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.25);
}
.nora-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.2) 0%, rgba(245, 158, 11, 0) 70%);
    pointer-events: none;
}

.nora-nav-pills .nav-link {
    color: #475569;
    font-weight: 700;
    padding: 12px 22px;
    border-radius: 12px;
    transition: all 0.2s ease-in-out;
    border: 1px solid transparent;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
}
.nora-nav-pills .nav-link:hover {
    color: var(--nora-primary);
    background: #f1f5f9;
}
.nora-nav-pills .nav-link.active {
    background: #4f46e5 !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
}

.qa-card-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.qa-card-item:hover {
    border-color: #cbd5e1;
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.08);
}

.dropzone-box {
    border: 2px dashed #cbd5e1;
    background: #f8fafc;
    border-radius: 16px;
    transition: all 0.2s ease;
    cursor: pointer;
}
.dropzone-box:hover {
    border-color: #6366f1;
    background: #eef2ff;
}

.badge-tag {
    background: #f1f5f9;
    color: #334155;
    font-weight: 600;
    font-size: 0.78rem;
    padding: 4px 10px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}
</style>

<div class="container-fluid px-0">

    <!-- 1. HERO STUDIO BANNER -->
    <div class="nora-hero p-4 p-md-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 mb-3 backdrop-blur">
                    <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px; box-shadow: 0 0 8px #22c55e;"></span>
                    <span class="small fw-bold text-white">Nora AI Brain Studio</span>
                    <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.7rem;">Gemini 3.5 Flash</span>
                </div>
                <h2 class="fw-bold mb-2 text-white">ศูนย์บริหารจัดการสมอง AI น้องโนรา</h2>
                <p class="text-white text-opacity-80 mb-4" style="font-size: 0.95rem; max-width: 600px;">
                    จัดการองค์ความรู้ Q&A ตอบคำถามประชาชน 24 ชม. พร้อมระบบ <strong>AI Co-Pilot</strong> สกัดข้อมูลจากไฟล์เอกสารและภาพถ่ายราชการเข้าสู่คลังสมองอัตโนมัติ
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" onclick="AdminNora.switchTab('ai-extract-tab')" class="btn btn-warning rounded-pill px-4 py-2.5 fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>สกัดความรู้ด้วย AI (AI Co-Pilot)</span>
                    </button>
                    <button type="button" onclick="AdminNora.openNewQaModal()" class="btn btn-light rounded-pill px-4 py-2.5 fw-bold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-primary"></i>
                        <span>+ เพิ่ม Q&A ด้วยตนเอง</span>
                    </button>
                    <button type="button" onclick="AdminNora.syncKnowledge()" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-semibold d-inline-flex align-items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>ซิงค์ความรู้พื้นฐาน</span>
                    </button>
                </div>
            </div>

            <!-- Quick Stats in Hero -->
            <div class="col-lg-5">
                <div class="row g-2.5">
                    <div class="col-6">
                        <div class="p-3.5 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-15 backdrop-blur h-100">
                            <span class="text-white text-opacity-70 small fw-bold d-block mb-1">คลังคำถาม-คำตอบ (Q&A)</span>
                            <h3 class="fw-bold text-warning mb-0" id="statQaCount"><?= count($knowledge) ?></h3>
                            <small class="text-white text-opacity-70" style="font-size: 0.75rem;">ชุดความรู้พร้อมตอบ</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3.5 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-15 backdrop-blur h-100">
                            <span class="text-white text-opacity-70 small fw-bold d-block mb-1">สถานะผู้ช่วย AI</span>
                            <h4 class="fw-bold mb-0 <?= !empty($settings['is_enabled']) ? 'text-success' : 'text-danger' ?>">
                                <?= !empty($settings['is_enabled']) ? '🟢 เปิดใช้งาน' : '🔴 ปิดชั่วคราว' ?>
                            </h4>
                            <small class="text-white text-opacity-70" style="font-size: 0.75rem;">บนหน้าเว็บสาธารณะ</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-15 backdrop-blur d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                <div class="p-2 rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                                    <i class="fa-solid fa-brain"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-white small text-truncate"><?= esc($settings['bot_name'] ?? 'น้องโนรา AI') ?></div>
                                    <div class="text-white text-opacity-70 text-truncate" style="font-size: 0.75rem;"><?= esc($settings['tagline'] ?? 'ผู้ช่วยบริการประชาชน 24 ชม.') ?></div>
                                </div>
                            </div>
                            <button type="button" onclick="AdminNora.switchTab('settings-tab')" class="btn btn-xs btn-outline-light rounded-pill px-3 py-1 flex-shrink-0">
                                ตั้งค่า
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. STUDIO NAVIGATION TABS -->
    <div class="d-flex flex-wrap gap-2 mb-4 nora-nav-pills" id="noraStudioTabs" role="tablist">
        <button class="nav-link active shadow-sm" id="knowledge-tab" data-bs-toggle="pill" data-bs-target="#pane-knowledge" type="button" role="tab">
            <i class="fa-solid fa-book-bookmark text-warning"></i>
            <span>คลังความรู้ Q&A ทั้งหมด (<span id="tabQaCount"><?= count($knowledge) ?></span>)</span>
        </button>
        <button class="nav-link shadow-sm" id="ai-extract-tab" data-bs-toggle="pill" data-bs-target="#pane-ai-extract" type="button" role="tab">
            <i class="fa-solid fa-wand-magic-sparkles text-primary"></i>
            <span>AI Co-Pilot สกัดเอกสาร & ข้อความ</span>
        </button>
        <button class="nav-link shadow-sm" id="simulator-tab" data-bs-toggle="pill" data-bs-target="#pane-simulator" type="button" role="tab">
            <i class="fa-solid fa-comments text-success"></i>
            <span>ห้องทดสอบสนทนา (Live Playground)</span>
        </button>
        <button class="nav-link shadow-sm" id="settings-tab" data-bs-toggle="pill" data-bs-target="#pane-settings" type="button" role="tab">
            <i class="fa-solid fa-sliders text-info"></i>
            <span>ตั้งค่าระบบ & Gemini Engine</span>
        </button>
    </div>

    <!-- 3. TAB CONTENT PANES -->
    <div class="tab-content" id="noraStudioTabContent">

        <!-- ======================================================== -->
        <!-- TAB 1: ALL Q&A KNOWLEDGE BASE (SPACIOUS & CLEAR) -->
        <!-- ======================================================== -->
        <div class="tab-pane fade show active" id="pane-knowledge" role="tabpanel">
            
            <!-- Controls Bar -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    
                    <!-- Search Input -->
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="qaSearchInput" onkeyup="AdminNora.filterCards()" class="form-control bg-light border-start-0 shadow-none" placeholder="ค้นหาคำถาม, คำสำคัญ (Keywords), หรือคำตอบ...">
                    </div>

                    <!-- Right Controls -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small fw-semibold">แสดงผล: <strong class="text-dark" id="filterCount"><?= count($knowledge) ?></strong> รายการ</span>
                        <button type="button" onclick="AdminNora.openNewQaModal()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-xs">
                            <i class="fa-solid fa-plus me-1"></i> เพิ่มคำถาม-คำตอบใหม่
                        </button>
                    </div>
                </div>
            </div>

            <!-- Q&A Cards Grid (Spacious & Easy to scan) -->
            <div id="qaCardsContainer" class="row g-3">
                <?php if (empty($knowledge)): ?>
                    <div class="col-12 text-center py-5 bg-white rounded-4 border">
                        <i class="fa-solid fa-brain fs-1 text-muted opacity-50 mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark">ยังไม่มีข้อมูลในคลังความรู้</h5>
                        <p class="text-muted small mb-3">คุณสามารถใช้ AI Co-Pilot สกัดข้อมูลจากเอกสาร หรือกดปุ่มเพิ่มคำถามด้วยตนเองได้ทันที</p>
                        <button type="button" onclick="AdminNora.switchTab('ai-extract-tab')" class="btn btn-warning fw-bold text-dark rounded-pill px-4">
                            <i class="fa-solid fa-wand-magic-sparkles me-1"></i> เริ่มต้นสกัดความรู้ด้วย AI
                        </button>
                    </div>
                <?php else: ?>
                    <?php foreach ($knowledge as $idx => $item): 
                        $kws = array_filter(array_map('trim', explode(',', $item['keywords'] ?? '')));
                    ?>
                        <div class="col-12 col-lg-6 qa-item-wrapper" data-keywords="<?= esc(mb_strtolower($item['keywords'] ?? '')) ?>" data-question="<?= esc(mb_strtolower($item['question'] ?? '')) ?>" data-answer="<?= esc(mb_strtolower($item['answer'] ?? '')) ?>">
                            <div class="qa-card-item p-4 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Header: Index & Actions -->
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2.5 py-1 rounded-pill small fw-bold">
                                            #<?= $idx + 1 ?>
                                        </span>
                                        <div class="d-flex align-items-center gap-1">
                                            <button type="button" onclick="AdminNora.editQa('<?= esc($item['id']) ?>')" class="btn btn-sm btn-outline-primary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="แก้ไข">
                                                <i class="fa-solid fa-pen" style="font-size: 0.75rem;"></i>
                                            </button>
                                            <button type="button" onclick="AdminNora.deleteQa('<?= esc($item['id']) ?>', '<?= esc(addslashes($item['question'] ?? '')) ?>')" class="btn btn-sm btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="ลบ">
                                                <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Question -->
                                    <h6 class="fw-bold text-dark mb-2" style="line-height: 1.45; font-size: 1.05rem;">
                                        ❓ <?= esc($item['question'] ?? '-') ?>
                                    </h6>

                                    <!-- Answer Box -->
                                    <div class="p-3 rounded-3 bg-light text-secondary small mb-3" style="line-height: 1.6; border: 1px solid #f1f5f9;">
                                        <?= nl2br(esc($item['answer'] ?? '-')) ?>
                                    </div>
                                </div>

                                <!-- Footer: Keywords & Link -->
                                <div>
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        <span class="small text-muted fw-bold me-1 align-self-center"><i class="fa-solid fa-tag me-1 text-warning"></i>คำค้น:</span>
                                        <?php foreach ($kws as $kw): ?>
                                            <span class="badge-tag"><?= esc($kw) ?></span>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php if (!empty($item['link_url'])): ?>
                                        <div class="pt-2 border-top">
                                            <a href="<?= esc($item['link_url']) ?>" target="_blank" class="text-primary small text-decoration-none fw-bold">
                                                <i class="fa-solid fa-link me-1"></i> <?= esc($item['link_title'] ?: $item['link_url']) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 2: AI CO-PILOT EXTRACTOR (HERO WORKSPACE) -->
        <!-- ======================================================== -->
        <div class="tab-pane fade" id="pane-ai-extract" role="tabpanel">
            <div class="row g-4">
                
                <!-- Left Input Studio -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-wand-magic-sparkles text-warning fs-4"></i>
                                <span>ป้อนเอกสารหรือข้อความให้ AI สกัด</span>
                            </h5>
                        </div>
                        <p class="text-muted small mb-4">
                            คุณสามารถ <strong>อัปโหลดไฟล์เอกสาร (PDF, Word, Text, รูปภาพ)</strong> หรือ <strong>คัดลอกข้อความระเบียบ/ข่าวสารมาวาง</strong> แล้วให้ Google Gemini AI สกัดเป็นชุดคำถาม-คำตอบ (Q&A) พร้อมคีย์เวิร์ดเข้าสู่ระบบให้อัตโนมัติ
                        </p>

                        <!-- File Dropzone -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">1. อัปโหลดไฟล์เอกสาร (PDF, Word, Text, รูปภาพ)</label>
                            <div class="dropzone-box p-4 text-center" id="dropZoneDoc" onclick="document.getElementById('geminiDocFile').click()">
                                <input type="file" id="geminiDocFile" onchange="AdminNora.handleFileSelect(this)" class="d-none" accept=".pdf,.docx,.doc,.txt,.csv,.jpg,.jpeg,.png,.webp">
                                
                                <div id="fileUploadPrompt">
                                    <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;">
                                        <i class="fa-solid fa-cloud-arrow-up fs-3"></i>
                                    </div>
                                    <div class="fw-bold text-dark">คลิกเพื่อเลือกไฟล์ หรือลากไฟล์มาวางที่นี่</div>
                                    <div class="text-muted small mt-1">รองรับ PDF, Word (.docx), ข้อความ (.txt, .csv), รูปภาพ (.jpg, .png) สูงสุด 20MB</div>
                                </div>

                                <div id="fileSelectedBadge" style="display: none;" class="py-2">
                                    <div class="badge bg-primary fs-6 p-3 rounded-4 d-inline-flex align-items-center gap-3 shadow-sm">
                                        <i class="fa-solid fa-file-lines fs-4"></i>
                                        <div class="text-start">
                                            <div id="selectedFileName" class="fw-bold">filename.pdf</div>
                                            <div class="text-white text-opacity-75 small" style="font-size: 0.72rem;">พร้อมส่งให้ AI วิเคราะห์</div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" onclick="event.stopPropagation(); AdminNora.clearSelectedFile();" title="ลบไฟล์">
                                            <i class="fa-solid fa-xmark text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Textarea input -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small">2. หรือวางข้อความ / คำสั่งเพิ่มเติมให้ AI</label>
                            <textarea class="form-control rounded-3 p-3 bg-light shadow-none" id="geminiRawContent" rows="6" placeholder="เช่น วางข้อความระเบียบราชการ, ข้อมูลสถานที่ท่องเที่ยว, หรือพิมพ์คำสั่งเพิ่มเติม เช่น 'เน้นสรุปขั้นตอนการขอรับบริการและเอกสารที่ประชาชนต้องเตรียม'"></textarea>
                        </div>

                        <button type="button" onclick="AdminNora.runGeminiExtract()" id="btnRunGemini" class="btn btn-warning btn-lg rounded-pill fw-bold text-dark py-3 shadow w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles fs-5"></i>
                            <span>เริ่มต้นสกัดความรู้ด้วย Gemini AI</span>
                        </button>
                    </div>
                </div>

                <!-- Right Output Studio -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-list-check text-success fs-4"></i>
                                    <span>ผลลัพธ์ชุดความรู้ที่ AI สกัดได้</span>
                                </h5>
                                <div id="geminiActionBtns" style="display: none;">
                                    <button type="button" onclick="AdminNora.importGeminiQa()" class="btn btn-success rounded-pill px-3.5 py-1.5 fw-bold shadow-xs d-inline-flex align-items-center gap-1.5 small">
                                        <i class="fa-solid fa-plus-circle"></i>
                                        <span>นำเข้าคลังความรู้ทันที</span>
                                    </button>
                                </div>
                            </div>

                            <div id="geminiResultPlaceholder" class="text-center py-5 text-muted">
                                <div class="p-4 rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-robot fs-2 text-secondary opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">ยังไม่มีผลการสกัด</h6>
                                <p class="small text-muted mb-0">เลือกไฟล์หรือวางข้อความทางซ้ายมือ แล้วกดปุ่ม "เริ่มต้นสกัดความรู้" ผลลัพธ์จะปรากฏที่นี่ครับ</p>
                            </div>

                            <!-- List of extracted items -->
                            <div id="geminiCardsList" class="d-flex flex-column gap-3" style="display: none !important;">
                                <!-- Dynamically populated by JS -->
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded-3 text-muted small border">
                            <i class="fa-solid fa-lightbulb text-warning me-1"></i> <strong>คำแนะนำ:</strong> AI จะวิเคราะห์เนื้อหาและสร้าง 1-5 คำถาม-คำตอบที่มีคุณภาพสูงสุด พร้อมคัดแยกคำค้น (Keywords) ให้อัตโนมัติ
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 3: LIVE AI PLAYGROUND (SIMULATOR) -->
        <!-- ======================================================== -->
        <div class="tab-pane fade" id="pane-simulator" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="height: 600px; display: flex; flex-direction: column; background: #ffffff;">
                        
                        <!-- Chat Header -->
                        <div class="p-3 px-4 bg-dark text-white d-flex align-items-center justify-content-between border-bottom border-warning border-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                    <i class="fa-solid fa-chess-queen fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-white"><?= esc($settings['bot_name'] ?? 'น้องโนรา (Nora AI)') ?> <span class="badge bg-success rounded-pill px-2 py-0.5 ms-1 small">Live Test</span></h6>
                                    <small class="text-white text-opacity-70"><?= esc($settings['status_text'] ?? 'พร้อมให้บริการตอบคำถามประชาชน 24 ชม.') ?></small>
                                </div>
                            </div>
                            <button type="button" onclick="AdminNora.clearSimulatorChat()" class="btn btn-sm btn-outline-light rounded-pill px-3">
                                <i class="fa-solid fa-rotate-left me-1"></i> รีเซ็ตแชต
                            </button>
                        </div>

                        <!-- Chat Messages Area -->
                        <div class="flex-grow-1 p-4 overflow-auto d-flex flex-column gap-3 bg-light" id="simulatorMessagesArea">
                            <div class="p-3.5 rounded-4 bg-white shadow-xs border align-self-start" style="max-width: 85%;">
                                <div class="small fw-bold text-warning mb-1"><i class="fa-solid fa-chess-queen me-1"></i> น้องโนรา (Nora AI)</div>
                                <div style="font-size: 0.92rem; line-height: 1.6;">
                                    <?= nl2br(esc($settings['greeting_msg'] ?? 'สวัสดีค่ะ 🙏 น้องโนรา ยินดีให้บริการค่ะ มีเรื่องใดให้ช่วยสอบถามพิมพ์มาได้เลยนะคะ')) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Input Box -->
                        <div class="p-3 bg-white border-top">
                            <form id="simulatorForm" onsubmit="event.preventDefault(); AdminNora.sendSimulatorMsg();" class="d-flex gap-2">
                                <input type="text" id="simulatorInput" class="form-control rounded-pill px-4 py-2.5 shadow-none bg-light" placeholder="พิมพ์ข้อความทดสอบถามน้องโนรา เช่น 'เที่ยวทะเลน้อยช่วงไหนดี', 'ภาษีป้าย'...">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold flex-shrink-0">
                                    <i class="fa-solid fa-paper-plane me-1"></i> ส่ง
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- TAB 4: SETTINGS & GEMINI ENGINE (CLEAR & STRUCTURED) -->
        <!-- ======================================================== -->
        <div class="tab-pane fade" id="pane-settings" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <form id="adminNoraSettingsForm" onsubmit="event.preventDefault(); AdminNora.saveSettings();">
                        
                        <!-- Section 1: AI Engine Configuration -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                            <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                <h5 class="fw-bold text-primary m-0 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-wand-magic-sparkles text-warning fs-4"></i>
                                    <span>การเชื่อมต่อ Google Gemini AI API (LLM Engine)</span>
                                </h5>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">Google AI Studio</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small">
                                    Google Gemini API Key
                                    <a href="https://aistudio.google.com/app/apikey" target="_blank" class="small text-decoration-none ms-2"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>รับ API Key ฟรีที่ Google AI Studio</a>
                                </label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light"><i class="fa-solid fa-key text-muted"></i></span>
                                    <input type="password" class="form-control font-monospace" name="gemini_api_key" id="set_gemini_api_key" value="<?= esc($settings['gemini_api_key'] ?? '') ?>" placeholder="AQ. หรือ AIzaSy...">
                                    <button type="button" class="btn btn-outline-secondary" onclick="AdminNora.toggleKeyVisibility()" title="แสดง/ซ่อนคีย์"><i class="fa-solid fa-eye" id="eyeIcon"></i></button>
                                    <button type="button" class="btn btn-outline-primary fw-bold px-3" onclick="AdminNora.testGeminiConnection()" id="btnTestKey"><i class="fa-solid fa-plug me-1"></i> ทดสอบเชื่อมต่อ</button>
                                </div>
                                <div id="geminiTestStatus" style="display:none;" class="mb-2"></div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">โมเดล AI (Gemini Model)</label>
                                    <select class="form-select rounded-3" name="gemini_model" id="set_gemini_model">
                                        <option value="gemini-3.5-flash" <?= ($settings['gemini_model'] ?? 'gemini-3.5-flash') === 'gemini-3.5-flash' ? 'selected' : '' ?>>Gemini 3.5 Flash (ความเร็วสูง แนะนำสำหรับภาษาไทย)</option>
                                        <option value="gemini-3.5-flash-lite" <?= ($settings['gemini_model'] ?? '') === 'gemini-3.5-flash-lite' ? 'selected' : '' ?>>Gemini 3.5 Flash-Lite (เร็วพิเศษ)</option>
                                        <option value="gemini-3.6-flash" <?= ($settings['gemini_model'] ?? '') === 'gemini-3.6-flash' ? 'selected' : '' ?>>Gemini 3.6 Flash (วิเคราะห์เชิงลึก)</option>
                                        <option value="gemini-2.5-pro" <?= ($settings['gemini_model'] ?? '') === 'gemini-2.5-pro' ? 'selected' : '' ?>>Gemini 2.5 Pro (วิเคราะห์เอกสารซับซ้อน)</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch p-3 bg-light rounded-3 w-100 mb-0 border">
                                        <input class="form-check-input ms-0 me-3" type="checkbox" name="use_gemini_live" id="set_use_gemini_live" <?= !empty($settings['use_gemini_live']) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold text-dark small" for="set_use_gemini_live">เปิดใช้ Gemini Live RAG (ตอบสดเมื่อไม่พบใน Q&A)</label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Section 2: Bot Persona & Public Status -->
                        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                            <div class="border-bottom pb-3 mb-3">
                                <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-robot text-primary fs-4"></i>
                                    <span>บุคลิกภาพและการแสดงผลหน้าเว็บไซต์ (Bot Persona)</span>
                                </h5>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">ชื่อผู้ช่วย AI</label>
                                    <input type="text" class="form-control" name="bot_name" value="<?= esc($settings['bot_name'] ?? 'น้องโนรา (Nora AI)') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small">คำบรรยายบทบาท (Tagline)</label>
                                    <input type="text" class="form-control" name="tagline" value="<?= esc($settings['tagline'] ?? 'ผู้ช่วยบริการประชาชน 24 ชม. และนำทางอัจฉริยะ') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small">ข้อความสถานะ (Status Text)</label>
                                    <input type="text" class="form-control" name="status_text" value="<?= esc($settings['status_text'] ?? 'พร้อมให้บริการ 24 ชม.') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small">ข้อความทักทายแรกเมื่อประชาชนเปิดแชต (Greeting Message)</label>
                                    <textarea class="form-control" name="greeting_msg" rows="3"><?= esc($settings['greeting_msg'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-dark small">ข้อความตอบกลับเมื่อ AI ไม่ทราบคำตอบ (Fallback Message)</label>
                                    <textarea class="form-control" name="fallback_msg" rows="3"><?= esc($settings['fallback_msg'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <div class="form-check form-switch p-3 bg-light rounded-3 border">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_enabled" id="set_is_enabled" <?= !empty($settings['is_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold text-dark" for="set_is_enabled">🟢 เปิดใช้งานระบบน้องโนรา AI บนหน้าเว็บไซต์สาธารณะ 24 ชั่วโมง</label>
                            </div>
                        </div>

                        <div class="text-end mb-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                                <i class="fa-solid fa-save me-1"></i> บันทึกการตั้งค่าทั้งหมด
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- ============================================================= -->
<!-- MODAL: ADD / EDIT SINGLE Q&A (CLEAN & SPACIOUS) -->
<!-- ============================================================= -->
<div class="modal fade" id="qaModal" tabindex="-1" aria-labelledby="qaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white border-bottom border-warning border-3 px-4 py-3">
                <h5 class="modal-title fw-bold m-0 text-white" id="qaModalLabel">
                    <i class="fa-solid fa-circle-plus text-warning me-2"></i>บันทึกคำถาม-คำตอบใหม่
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="qaForm" onsubmit="event.preventDefault(); AdminNora.saveQa();">
                <input type="hidden" id="modal_qa_id" name="id" value="">
                
                <div class="modal-body p-4 bg-light">
                    <div class="card border-0 rounded-4 p-4 bg-white shadow-xs">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">
                                คำสำคัญที่เกี่ยวข้อง (Keywords) <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3" id="modal_qa_keywords" name="keywords" placeholder="คั่นด้วยจุลภาค เช่น: ทะเลน้อย, ล่องเรือ, ดูนก, ค่าเข้า" required>
                            <small class="text-muted">เมื่อประชาชนพิมพ์คำเหล่านี้ AI จะค้นหาและตอบคำตอบนี้ทันที</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">
                                คำถามตัวอย่าง (Question) <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3" id="modal_qa_question" name="question" placeholder="เช่น: การล่องเรือชมนกน้ำและทุ่งบัวแดงทะเลน้อยมีค่าบริการเท่าไหร่?" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small">
                                คำตอบของน้องโนรา AI (Answer) <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control rounded-3" id="modal_qa_answer" name="answer" rows="5" placeholder="ระบุข้อความคำตอบที่กระชับ สุภาพ เข้าใจง่าย..." required></textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fw-bold text-dark small">ลิงก์ทางลัด (URL - ถ้ามี)</label>
                                <input type="text" class="form-control" id="modal_qa_link_url" name="link_url" placeholder="เช่น #services, tourism, หรือ https://...">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold text-dark small">ข้อความบนปุ่ม</label>
                                <input type="text" class="form-control" id="modal_qa_link_title" name="link_title" placeholder="เช่น 🚤 ดูรายละเอียดการท่องเที่ยว">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-white px-4 py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-xs">
                        <i class="fa-solid fa-save me-1"></i> บันทึกเข้าคลังความรู้
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const AdminNora = {
    qaList: <?= json_encode($knowledge ?? []) ?>,
    extractedItems: [],
    qaModalInstance: null,
    selectedFile: null,

    notify: function(msg, type = 'info') {
        if (typeof App !== 'undefined' && typeof App.toast === 'function') {
            App.toast(msg, type);
            return;
        }
        alert(msg);
    },

    switchTab: function(tabId) {
        const tabEl = document.getElementById(tabId);
        if (tabEl) {
            const tab = new bootstrap.Tab(tabEl);
            tab.show();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    openNewQaModal: function() {
        document.getElementById('qaForm').reset();
        document.getElementById('modal_qa_id').value = '';
        document.getElementById('qaModalLabel').innerHTML = '<i class="fa-solid fa-circle-plus text-warning me-2"></i>เพิ่มคำถาม-คำตอบใหม่';
        
        const el = document.getElementById('qaModal');
        if (!this.qaModalInstance) this.qaModalInstance = new bootstrap.Modal(el);
        this.qaModalInstance.show();
    },

    editQa: function(id) {
        const item = this.qaList.find(x => String(x.id) === String(id));
        if (!item) return;

        document.getElementById('modal_qa_id').value = item.id || '';
        document.getElementById('modal_qa_keywords').value = item.keywords || '';
        document.getElementById('modal_qa_question').value = item.question || '';
        document.getElementById('modal_qa_answer').value = item.answer || '';
        document.getElementById('modal_qa_link_url').value = item.link_url || '';
        document.getElementById('modal_qa_link_title').value = item.link_title || '';

        document.getElementById('qaModalLabel').innerHTML = '<i class="fa-solid fa-pen-to-square text-warning me-2"></i>แก้ไขชุดคำถาม-คำตอบ';
        
        const el = document.getElementById('qaModal');
        if (!this.qaModalInstance) this.qaModalInstance = new bootstrap.Modal(el);
        this.qaModalInstance.show();
    },

    saveQa: function() {
        const form = document.getElementById('qaForm');
        const formData = new FormData(form);

        fetch('<?= base_url('admin/nora-ai/save-qa') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                if (this.qaModalInstance) this.qaModalInstance.hide();
                setTimeout(() => location.reload(), 600);
            } else {
                this.notify(data.message, 'error');
            }
        })
        .catch(err => {
            this.notify('เกิดข้อผิดพลาด: ' + err.message, 'error');
        });
    },

    deleteQa: function(id, title) {
        if (!confirm(`คุณต้องการลบรายการ Q&A: "${title}" ใช่หรือไม่?`)) return;

        fetch('<?= base_url('admin/nora-ai/delete-qa') ?>/' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                setTimeout(() => location.reload(), 500);
            } else {
                this.notify(data.message, 'error');
            }
        })
        .catch(err => this.notify('เกิดข้อผิดพลาด: ' + err.message, 'error'));
    },

    filterCards: function() {
        const query = (document.getElementById('qaSearchInput')?.value || '').toLowerCase().trim();
        const items = document.querySelectorAll('.qa-item-wrapper');
        let count = 0;

        items.forEach(el => {
            const kw = el.getAttribute('data-keywords') || '';
            const q = el.getAttribute('data-question') || '';
            const a = el.getAttribute('data-answer') || '';

            if (!query || kw.includes(query) || q.includes(query) || a.includes(query)) {
                el.style.display = 'block';
                count++;
            } else {
                el.style.display = 'none';
            }
        });

        const filterCountEl = document.getElementById('filterCount');
        if (filterCountEl) filterCountEl.textContent = count;
    },

    // AI File Handling
    handleFileSelect: function(input) {
        if (input.files && input.files[0]) {
            this.selectedFile = input.files[0];
            document.getElementById('selectedFileName').textContent = this.selectedFile.name + ' (' + (this.selectedFile.size / 1024).toFixed(1) + ' KB)';
            document.getElementById('fileSelectedBadge').style.display = 'block';
            document.getElementById('fileUploadPrompt').style.display = 'none';
        }
    },

    clearSelectedFile: function() {
        this.selectedFile = null;
        document.getElementById('geminiDocFile').value = '';
        document.getElementById('fileSelectedBadge').style.display = 'none';
        document.getElementById('fileUploadPrompt').style.display = 'block';
    },

    runGeminiExtract: function() {
        const content = (document.getElementById('geminiRawContent')?.value || '').trim();
        const file = this.selectedFile;

        if (!file && (!content || content.length < 10)) {
            this.notify('กรุณาเลือกไฟล์เอกสาร หรือพิมพ์/วางข้อความอย่างน้อย 10 ตัวอักษร', 'warning');
            return;
        }

        const btn = document.getElementById('btnRunGemini');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Gemini กำลังอ่านและสกัดความรู้...';

        const formData = new FormData();
        if (file) formData.append('doc_file', file);
        if (content) formData.append('content', content);

        fetch('<?= base_url('admin/nora-ai/gemini-extract') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles fs-5"></i> <span>เริ่มต้นสกัดความรู้ด้วย Gemini AI</span>';

            if (data.status === 'success') {
                this.extractedItems = data.items || [];
                this.renderExtractedResults(this.extractedItems);
                this.notify(data.message, 'success');
            } else {
                this.notify(data.message || 'ไม่สามารถสกัดความรู้ได้', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles fs-5"></i> <span>เริ่มต้นสกัดความรู้ด้วย Gemini AI</span>';
            this.notify('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์: ' + err.message, 'error');
        });
    },

    renderExtractedResults: function(items) {
        const placeholder = document.getElementById('geminiResultPlaceholder');
        const list = document.getElementById('geminiCardsList');
        const actions = document.getElementById('geminiActionBtns');

        if (!items || items.length === 0) {
            placeholder.style.display = 'block';
            list.style.display = 'none';
            actions.style.display = 'none';
            return;
        }

        placeholder.style.display = 'none';
        list.style.setProperty('display', 'flex', 'important');
        actions.style.display = 'block';

        let html = '';
        items.forEach((item, idx) => {
            const num = idx + 1;
            html += `
                <div class="card border rounded-3 p-3.5 bg-white shadow-xs">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-primary rounded-pill px-2.5 py-1">ข้อที่ ${num}</span>
                        <span class="badge bg-warning bg-opacity-25 text-dark border border-warning border-opacity-30">คำค้น: ${item.keywords || '-'}</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">❓ ${item.question || '-'}</h6>
                    <div class="text-secondary small bg-light p-3 rounded-3" style="line-height: 1.6;">
                        ${(item.answer || '').replace(/\\n/g, '<br>')}
                    </div>
                </div>
            `;
        });

        list.innerHTML = html;
    },

    importGeminiQa: function() {
        if (!this.extractedItems || this.extractedItems.length === 0) {
            this.notify('ไม่พบรายการที่ต้องการนำเข้า', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('items', JSON.stringify(this.extractedItems));

        fetch('<?= base_url('admin/nora-ai/save-multiple-qa') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                setTimeout(() => location.reload(), 700);
            } else {
                this.notify(data.message, 'error');
            }
        })
        .catch(err => this.notify('เกิดข้อผิดพลาด: ' + err.message, 'error'));
    },

    syncKnowledge: function() {
        if (!confirm('คุณต้องการซิงค์ชุดคำถาม-คำตอบพื้นฐานของจังหวัดพัทลุงหรือไม่?')) return;

        fetch('<?= base_url('admin/nora-ai/sync') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                setTimeout(() => location.reload(), 600);
            }
        })
        .catch(err => this.notify('เกิดข้อผิดพลาด: ' + err.message, 'error'));
    },

    // Simulator Handlers
    sendSimulatorMsg: function() {
        const input = document.getElementById('simulatorInput');
        const text = (input?.value || '').trim();
        if (!text) return;

        const area = document.getElementById('simulatorMessagesArea');

        // User bubble
        const userDiv = document.createElement('div');
        userDiv.className = 'p-3 px-3.5 rounded-4 bg-primary text-white shadow-xs align-self-end small';
        userDiv.style.maxWidth = '80%';
        userDiv.textContent = text;
        area.appendChild(userDiv);
        input.value = '';
        area.scrollTop = area.scrollHeight;

        // Fetch AI reply
        const formData = new FormData();
        formData.append('message', text);

        fetch('<?= base_url('api/nora-ai/chat') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            const botDiv = document.createElement('div');
            botDiv.className = 'p-3.5 rounded-4 bg-white shadow-xs border align-self-start';
            botDiv.style.maxWidth = '85%';
            
            let formatted = (data.reply || '')
                .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
            botDiv.innerHTML = `<div class="small fw-bold text-warning mb-1"><i class="fa-solid fa-chess-queen me-1"></i> น้องโนรา (Nora AI)</div><div style="font-size: 0.92rem; line-height: 1.6;">${formatted}</div>`;

            if (data.cards && data.cards.length > 0) {
                const cardBox = document.createElement('div');
                cardBox.className = 'mt-2 d-flex flex-column gap-1';
                data.cards.forEach(c => {
                    const btn = document.createElement('a');
                    btn.href = c.url || '#';
                    btn.target = '_blank';
                    btn.className = 'btn btn-xs btn-outline-primary text-start text-truncate rounded-pill px-3 py-1';
                    btn.innerHTML = `<i class="${c.icon || 'fa-solid fa-link'} me-1"></i> ${c.title || 'เปิดดู'}`;
                    cardBox.appendChild(btn);
                });
                botDiv.appendChild(cardBox);
            }

            area.appendChild(botDiv);
            area.scrollTop = area.scrollHeight;
        })
        .catch(err => {
            const errDiv = document.createElement('div');
            errDiv.className = 'p-3 rounded-4 bg-danger bg-opacity-10 text-danger align-self-start small';
            errDiv.textContent = 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์';
            area.appendChild(errDiv);
            area.scrollTop = area.scrollHeight;
        });
    },

    clearSimulatorChat: function() {
        const area = document.getElementById('simulatorMessagesArea');
        if (area) {
            area.innerHTML = `
                <div class="p-3.5 rounded-4 bg-white shadow-xs border align-self-start" style="max-width: 85%;">
                    <div class="small fw-bold text-warning mb-1"><i class="fa-solid fa-chess-queen me-1"></i> น้องโนรา (Nora AI)</div>
                    <div style="font-size: 0.92rem; line-height: 1.6;">
                        <?= nl2br(esc($settings['greeting_msg'] ?? 'สวัสดีค่ะ 🙏 น้องโนรา ยินดีให้บริการค่ะ มีเรื่องใดให้ช่วยสอบถามพิมพ์มาได้เลยนะคะ')) ?>
                    </div>
                </div>
            `;
        }
    },

    // Settings
    toggleKeyVisibility: function() {
        const input = document.getElementById('set_gemini_api_key');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-solid fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa-solid fa-eye';
        }
    },

    testGeminiConnection: function() {
        const key = (document.getElementById('set_gemini_api_key')?.value || '').trim();
        const statusBox = document.getElementById('geminiTestStatus');
        const btn = document.getElementById('btnTestKey');

        if (!key) {
            this.notify('กรุณากรอก API Key ก่อนกดทดสอบ', 'warning');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> ทดสอบ...';

        const formData = new FormData();
        formData.append('api_key', key);

        fetch('<?= base_url('admin/nora-ai/test-gemini') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-plug me-1"></i> ทดสอบเชื่อมต่อ';

            statusBox.style.display = 'block';
            if (data.status === 'success') {
                statusBox.innerHTML = '<div class="alert alert-success py-2 px-3 small mb-0"><i class="fa-solid fa-circle-check me-1"></i> ' + data.message + '</div>';
                this.notify(data.message, 'success');
            } else {
                statusBox.innerHTML = '<div class="alert alert-danger py-2 px-3 small mb-0"><i class="fa-solid fa-triangle-exclamation me-1"></i> ' + data.message + '</div>';
                this.notify(data.message, 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-plug me-1"></i> ทดสอบเชื่อมต่อ';
            this.notify('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
        });
    },

    saveSettings: function() {
        const form = document.getElementById('adminNoraSettingsForm');
        const formData = new FormData(form);

        fetch('<?= base_url('admin/nora-ai/save-settings') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                setTimeout(() => location.reload(), 700);
            } else {
                this.notify(data.message, 'error');
            }
        })
        .catch(err => this.notify('เกิดข้อผิดพลาด: ' + err.message, 'error'));
    }
};
</script>

<?= $this->endSection() ?>

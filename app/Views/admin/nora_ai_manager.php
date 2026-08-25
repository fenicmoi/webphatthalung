<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="p-2 rounded-3 text-warning bg-dark d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; border: 1px solid #f59e0b;">
                    <i class="fa-solid fa-wand-magic-sparkles fs-5 animate-pulse"></i>
                </div>
                <h4 class="fw-bold mb-0 text-dark">ระบบบริหารจัดการน้องโนรา AI & Smart Search</h4>
            </div>
            <p class="text-muted small mb-0">ศูนย์ควบคุมสมองปัญญาประดิษฐ์ (Nora AI Brain) ตอบคำถามบริการประชาชน 24 ชม. และเชื่อมต่อคลังข้อมูลจังหวัด</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" onclick="AdminNora.syncKnowledge()" class="btn btn-primary rounded-pill px-3 shadow-sm fw-bold d-flex align-items-center gap-2">
                <i class="fa-solid fa-arrows-rotate"></i>
                <span>ซิงค์ความรู้จากเว็บ (Auto-Sync)</span>
            </button>
            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm fw-semibold">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> ดูหน้าเว็บจริง
            </a>
        </div>
    </div>

    <!-- Stat Bento Cards Grid -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block mb-1">คลังคำถาม-คำตอบ (Q&A)</span>
                        <h3 class="fw-bold mb-0 text-dark" id="statQaCount"><?= count($knowledge) ?></h3>
                    </div>
                    <div class="rounded-circle p-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-brain fs-4"></i>
                    </div>
                </div>
                <small class="text-success mt-2 d-block"><i class="fa-solid fa-check me-1"></i>พร้อมตอบกลับทันที</small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block mb-1">เชื่อมต่อ Smart Omni-Search</span>
                        <h3 class="fw-bold mb-0 text-info">Live Sync</h3>
                    </div>
                    <div class="rounded-circle p-3 bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-satellite-dish fs-4"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">เอกสาร <?= $recentDocs ?> รายการ | ข่าว <?= $recentNews ?> รายการ</small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block mb-1">สถานะบริการ AI 24 ชม.</span>
                        <h3 class="fw-bold mb-0 text-success"><?= !empty($settings['is_enabled']) ? 'เปิดให้บริการ' : 'ปิดชั่วคราว' ?></h3>
                    </div>
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                </div>
                <small class="text-success mt-2 d-block"><i class="fa-solid fa-bolt me-1"></i>ระบบพร้อมรับประชาชน</small>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 bg-white border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold d-block mb-1">ชื่อผู้ช่วย AI ประจำจังหวัด</span>
                        <h5 class="fw-bold mb-0 text-primary text-truncate"><?= esc($settings['bot_name'] ?? 'น้องโนรา AI') ?></h5>
                    </div>
                    <div class="rounded-circle p-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-chess-queen fs-4"></i>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block text-truncate"><?= esc($settings['tagline'] ?? 'ผู้ช่วยบริการประชาชน') ?></small>
            </div>
        </div>
    </div>

    <!-- Main Tabs Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom px-4 pt-3 pb-0">
            <ul class="nav nav-tabs card-header-tabs fw-bold" id="noraAdminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-dark" id="knowledge-tab" data-bs-toggle="tab" data-bs-target="#knowledge-pane" type="button" role="tab">
                        <i class="fa-solid fa-brain text-warning me-2"></i>คลังคำถาม-คำตอบ (Q&A Base)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-dark" id="simulator-tab" data-bs-toggle="tab" data-bs-target="#simulator-pane" type="button" role="tab">
                        <i class="fa-solid fa-comments text-primary me-2"></i>ทดสอบสนทนาจำลอง (AI Simulator)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-dark" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-pane" type="button" role="tab">
                        <i class="fa-solid fa-sliders text-success me-2"></i>ตั้งค่าแชตบอต & บุคลิก AI
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="noraAdminTabContent">
                
                <!-- TAB 1: Q&A KNOWLEDGE BASE -->
                <div class="tab-pane fade show active" id="knowledge-pane" role="tabpanel">
                    <div class="row g-4">
                        <!-- Left/Top: Form -->
                        <div class="col-lg-5">
                            <div class="card border border-light-subtle rounded-4 p-4 bg-light shadow-xs h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-dark m-0" id="qaFormTitle">
                                        <i class="fa-solid fa-circle-plus text-success me-2"></i>เพิ่มคำถาม-คำตอบใหม่
                                    </h6>
                                    <button type="button" onclick="AdminNora.resetForm()" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5">
                                        <i class="fa-solid fa-rotate-left me-1"></i>ล้างฟอร์ม
                                    </button>
                                </div>

                                <form id="adminNoraQaForm" onsubmit="event.preventDefault(); AdminNora.saveQa();">
                                    <input type="hidden" id="admin_qa_id" name="id" value="">

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark">
                                            คำสำคัญที่เกี่ยวข้อง (Keywords) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control rounded-3" id="admin_qa_keywords" name="keywords" placeholder="คั่นด้วยจุลภาค เช่น: น้ำพุร้อน, เขาอกทะลุ, ภาษี, ขยะ" required>
                                        <div class="form-text small text-muted">เมื่อประชาชนพิมพ์คำที่ตรงกับ Keywords เหล่านี้ AI จะตอบคำตอบนี้ทันที</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark">
                                            คำถามตัวอย่าง (Question) <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control rounded-3" id="admin_qa_question" name="question" placeholder="เช่น: ติดต่อเรื่องขอใบอนุญาตก่อสร้างได้อย่างไร?" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-dark">
                                            คำตอบของน้องโนรา AI (Answer) <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control rounded-3" id="admin_qa_answer" name="answer" rows="4" placeholder="ระบุข้อความคำตอบที่ต้องการให้น้องโนราตอบประชาชน..." required></textarea>
                                    </div>

                                    <div class="row g-2 mb-4">
                                        <div class="col-md-7">
                                            <label class="form-label small fw-semibold text-muted">ลิงก์ทางลัด (URL - ถ้ามี)</label>
                                            <input type="text" class="form-control form-control-sm rounded-3" id="admin_qa_link_url" name="link_url" placeholder="เช่น #services, documents, หรือ https://...">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label small fw-semibold text-muted">ข้อความบนปุ่ม</label>
                                            <input type="text" class="form-control form-control-sm rounded-3" id="admin_qa_link_title" name="link_title" placeholder="เช่น ⚡ กดเข้าสู่บริการ">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2 shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกเข้าคลังสมอง AI
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Right: Data Table -->
                        <div class="col-lg-7">
                            <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
                                <h6 class="fw-bold text-dark m-0">
                                    <i class="fa-solid fa-list-check me-2 text-primary"></i>รายการในคลังความรู้ (<span id="tableQaCount"><?= count($knowledge) ?></span> รายการ)
                                </h6>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" onclick="AdminNora.openGeminiModal()" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-xs d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-wand-magic-sparkles text-warning"></i>
                                        <span>ให้ Gemini ช่วยสร้าง Q&A</span>
                                    </button>
                                    <div class="input-group input-group-sm" style="max-width: 200px;">
                                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                        <input type="text" id="qaSearchInput" onkeyup="AdminNora.filterTable()" class="form-control border-start-0" placeholder="ค้นหา...">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive border rounded-4 bg-white shadow-xs overflow-hidden" style="max-height: 520px;">
                                <table class="table table-hover align-middle mb-0 small" id="adminQaTable">
                                    <thead class="table-dark sticky-top">
                                        <tr>
                                            <th style="width: 28%;">Keywords</th>
                                            <th style="width: 32%;">คำถาม (Question)</th>
                                            <th style="width: 28%;">คำตอบย่อ</th>
                                            <th style="width: 12%; text-align: center;">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="adminQaTableBody">
                                        <?php if (empty($knowledge)): ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted">ยังไม่มีข้อมูลในคลังความรู้</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($knowledge as $item): ?>
                                                <tr>
                                                    <td>
                                                        <?php 
                                                            $kws = explode(',', $item['keywords'] ?? '');
                                                            foreach ($kws as $kw): 
                                                        ?>
                                                            <span class="badge bg-warning bg-opacity-25 text-dark fw-semibold me-1 mb-1"><?= esc(trim($kw)) ?></span>
                                                        <?php endforeach; ?>
                                                    </td>
                                                    <td class="fw-bold text-dark"><?= esc($item['question'] ?? '-') ?></td>
                                                    <td class="text-muted"><div class="line-clamp-2"><?= esc($item['answer'] ?? '-') ?></div></td>
                                                    <td class="text-center text-nowrap">
                                                        <button type="button" onclick="AdminNora.editQa('<?= esc($item['id']) ?>')" class="btn btn-xs btn-outline-warning text-dark me-1" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                                                        <button type="button" onclick="AdminNora.deleteQa('<?= esc($item['id']) ?>', '<?= esc(addslashes($item['question'] ?? '')) ?>')" class="btn btn-xs btn-outline-danger" title="ลบ"><i class="fa-solid fa-trash-can"></i></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: AI SIMULATOR / PLAYGROUND -->
                <div class="tab-pane fade" id="simulator-pane" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card border rounded-4 shadow-sm overflow-hidden" style="height: 550px; display: flex; flex-direction: column;">
                                <div class="p-3 bg-dark text-white d-flex align-items-center justify-content-between border-bottom border-warning border-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-white text-warning d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                            <i class="fa-solid fa-chess-queen fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-white"><?= esc($settings['bot_name'] ?? 'น้องโนรา (Nora AI)') ?> <span class="badge bg-success fs-8 ms-1">Simulator</span></h6>
                                            <small class="text-light opacity-75">ทดสอบคำถามและดูผลลัพธ์ตอบสนองของระบบแบบ Real-time</small>
                                        </div>
                                    </div>
                                    <button type="button" onclick="AdminNora.clearSimChat()" class="btn btn-xs btn-outline-light rounded-pill px-3">
                                        <i class="fa-solid fa-rotate-right me-1"></i> ล้างหน้าต่างทดสอบ
                                    </button>
                                </div>

                                <div class="p-4 flex-grow-1 overflow-y-auto bg-light d-flex flex-column gap-3" id="simChatArea">
                                    <div class="p-3 rounded-4 bg-white shadow-xs border align-self-start" style="max-width: 80%;">
                                        <?= nl2br(esc($settings['greeting_msg'] ?? 'สวัสดีค่ะ! 🙏 น้องโนราพร้อมตอบคำถามแล้วค่ะ')) ?>
                                    </div>
                                </div>

                                <div class="p-3 bg-white border-top">
                                    <form onsubmit="event.preventDefault(); AdminNora.sendSimMessage();" class="d-flex align-items-center gap-2">
                                        <input type="text" id="simInput" class="form-control rounded-pill px-3 py-2" placeholder="พิมพ์คำถามเพื่อทดสอบการตอบของ AI เช่น 'จัดซื้อจัดจ้าง', 'เบอร์ติดต่อ', 'ที่เที่ยว'..." autocomplete="off">
                                        <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                                            <i class="fa-solid fa-paper-plane me-1"></i> ส่ง
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: BOT SETTINGS & GEMINI CONFIGURATION -->
                <div class="tab-pane fade" id="settings-pane" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-9">
                            <form id="adminNoraSettingsForm" onsubmit="event.preventDefault(); AdminNora.saveSettings();">
                                
                                <!-- Card 1: Bot Personality & Text -->
                                <div class="card border rounded-4 p-4 shadow-xs bg-white mb-4">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                        <i class="fa-solid fa-sliders text-warning me-2"></i>ข้อมูลพื้นฐานและบุคลิกภาพของแชตบอต
                                    </h6>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">ชื่อผู้ช่วย AI (Bot Name)</label>
                                            <input type="text" class="form-control rounded-3" name="bot_name" id="set_bot_name" value="<?= esc($settings['bot_name'] ?? 'น้องโนรา (Nora AI)') ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">ข้อความสถานะ (Tagline)</label>
                                            <input type="text" class="form-control rounded-3" name="tagline" id="set_tagline" value="<?= esc($settings['tagline'] ?? 'ผู้ช่วยบริการประชาชน 24 ชม.') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-dark">ข้อความทักทายแรกเข้า (Greeting Message)</label>
                                        <textarea class="form-control rounded-3" name="greeting_msg" id="set_greeting_msg" rows="3"><?= esc($settings['greeting_msg'] ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-dark">ข้อความกรณีค้นหาไม่พบ (Fallback Reply)</label>
                                        <textarea class="form-control rounded-3" name="fallback_msg" id="set_fallback_msg" rows="3"><?= esc($settings['fallback_msg'] ?? '') ?></textarea>
                                    </div>

                                    <div class="form-check form-switch p-3 bg-light rounded-3 mb-0">
                                        <input class="form-check-input ms-0 me-3" type="checkbox" name="is_enabled" id="set_is_enabled" <?= !empty($settings['is_enabled']) ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold text-dark" for="set_is_enabled">🟢 เปิดใช้งานระบบน้องโนรา AI บนหน้าเว็บไซต์สาธารณะ 24 ชั่วโมง</label>
                                    </div>
                                </div>

                                <!-- Card 2: Google Gemini AI API Configuration -->
                                <div class="card border border-primary-subtle rounded-4 p-4 shadow-xs bg-white mb-4">
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                                        <h6 class="fw-bold text-primary m-0 d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-wand-magic-sparkles text-warning"></i>
                                            <span>การเชื่อมต่อ Google Gemini AI API (LLM Engine)</span>
                                        </h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">Google DeepMind</span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-dark">
                                            Google Gemini API Key
                                            <a href="https://aistudio.google.com/" target="_blank" class="small text-decoration-none ms-2"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>รับ API Key ฟรีที่ Google AI Studio</a>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fa-solid fa-key text-muted"></i></span>
                                            <input type="password" class="form-control" name="gemini_api_key" id="set_gemini_api_key" value="<?= esc($settings['gemini_api_key'] ?? '') ?>" placeholder="AIzaSy...">
                                            <button type="button" class="btn btn-outline-secondary" onclick="AdminNora.toggleKeyVisibility()" title="แสดง/ซ่อนคีย์"><i class="fa-solid fa-eye" id="eyeIcon"></i></button>
                                            <button type="button" class="btn btn-outline-primary fw-bold" onclick="AdminNora.testGeminiConnection()" id="btnTestKey"><i class="fa-solid fa-plug me-1"></i> ทดสอบเชื่อมต่อ</button>
                                        </div>
                                        <div class="form-text small text-muted">ต้องเป็น API Key ที่ขึ้นต้นด้วย <code>AIzaSy...</code> (ไม่ใช่ Project ID หรือ Client ID)</div>
                                        <div id="geminiTestStatus" class="mt-2" style="display:none;"></div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">Gemini Model</label>
                                            <select class="form-select rounded-3" name="gemini_model" id="set_gemini_model">
                                                <option value="gemini-2.5-flash" <?= ($settings['gemini_model'] ?? 'gemini-2.5-flash') === 'gemini-2.5-flash' ? 'selected' : '' ?>>Gemini 2.5 Flash (เร็ว & ฉลาด แนะนำ)</option>
                                                <option value="gemini-2.5-pro" <?= ($settings['gemini_model'] ?? '') === 'gemini-2.5-pro' ? 'selected' : '' ?>>Gemini 2.5 Pro (วิเคราะห์ระดับสูง)</option>
                                                <option value="gemini-flash-latest" <?= ($settings['gemini_model'] ?? '') === 'gemini-flash-latest' ? 'selected' : '' ?>>Gemini Flash Latest (อัปเดตล่าสุด)</option>
                                                <option value="gemini-2.5-flash-lite" <?= ($settings['gemini_model'] ?? '') === 'gemini-2.5-flash-lite' ? 'selected' : '' ?>>Gemini 2.5 Flash-Lite (เร็วพิเศษ)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 d-flex align-items-end">
                                            <div class="form-check form-switch p-3 bg-light rounded-3 w-100 mb-0">
                                                <input class="form-check-input ms-0 me-3" type="checkbox" name="use_gemini_live" id="set_use_gemini_live" <?= !empty($settings['use_gemini_live']) ? 'checked' : '' ?>>
                                                <label class="form-check-label fw-bold small text-dark" for="set_use_gemini_live">⚡ ใช้ Gemini ตอบสดร่วมกับ Smart Search (Live RAG)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-warning fw-bold text-dark rounded-pill px-5 shadow-sm">
                                        <i class="fa-solid fa-check me-1"></i> บันทึกการตั้งค่าทั้งหมด
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- MODAL: GEMINI AI KNOWLEDGE EXTRACTOR -->
<div class="modal fade" id="geminiKnowledgeModal" tabindex="-1" aria-labelledby="geminiKnowledgeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-bottom border-warning border-2 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-warning fs-4 animate-pulse"></i>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="geminiKnowledgeModalLabel">Gemini AI Knowledge Co-Pilot</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">คุณสามารถ <strong>อัปโหลดไฟล์เอกสารราชการ (PDF, DOCX, TXT, รูปภาพ)</strong> หรือวางข้อความ แล้วให้ <strong>Google Gemini AI</strong> อ่านและสกัดเป็นชุดคำถาม-คำตอบ (Q&A) เข้าสู่ระบบให้อัตโนมัติ</p>
                
                <!-- File Upload Box -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark small">📎 อัปโหลดไฟล์เอกสาร (PDF, Word, Text, รูปภาพ)</label>
                    <div class="border border-2 border-dashed rounded-4 p-3 text-center bg-light position-relative" id="dropZoneDoc" style="cursor: pointer;" onclick="document.getElementById('geminiDocFile').click()">
                        <input type="file" id="geminiDocFile" onchange="AdminNora.handleFileSelect(this)" class="d-none" accept=".pdf,.docx,.doc,.txt,.csv,.jpg,.jpeg,.png,.webp">
                        <div id="fileUploadPrompt">
                            <i class="fa-solid fa-cloud-arrow-up text-primary fs-2 mb-2"></i>
                            <div class="fw-bold text-dark small">คลิกเพื่อเลือกไฟล์ หรือลากไฟล์มาวางที่นี่</div>
                            <div class="text-muted" style="font-size: 0.75rem;">รองรับไฟล์ PDF, Word (.docx), ข้อความ (.txt, .csv), รูปภาพเอกสาร (.jpg, .png) สูงสุด 20MB</div>
                        </div>
                        <div id="fileSelectedBadge" style="display: none;" class="mt-1">
                            <span class="badge bg-primary fs-7 p-2 rounded-pill d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-file-lines"></i>
                                <span id="selectedFileName">filename.pdf</span>
                                <i class="fa-solid fa-xmark text-white ms-1" style="cursor: pointer;" onclick="event.stopPropagation(); AdminNora.clearSelectedFile();"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Textarea (Optional if file uploaded, or Main input if pasting) -->
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark small">✍️ หรือวางข้อความ / คำสั่งเพิ่มเติมให้ AI (ถ้ามี)</label>
                    <textarea class="form-control rounded-3" id="geminiRawContent" rows="4" placeholder="เช่น วางข้อความระเบียบ, ข่าวสาร, หรือใส่คำสั่งเพิ่มเติม เช่น 'เน้นสรุปขั้นตอนการขออนุญาตและเอกสารที่ต้องเตรียม'"></textarea>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <small class="text-muted"><i class="fa-solid fa-bolt text-warning me-1"></i>Gemini จะอ่านไฟล์และสร้าง 1-5 คำถาม-คำตอบที่ตรงประเด็นที่สุด</small>
                    <button type="button" onclick="AdminNora.runGeminiExtract()" id="btnRunGemini" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i> สกัดความรู้ด้วย Gemini
                    </button>
                </div>

                <!-- Extracted Preview Container -->
                <div id="geminiResultContainer" style="display: none;">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 d-flex align-items-center justify-content-between">
                        <span><i class="fa-solid fa-list-check text-success me-1"></i> ผลลัพธ์ชุดความรู้ที่ Gemini สกัดจากเอกสาร:</span>
                        <button type="button" onclick="AdminNora.importGeminiQa()" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm">
                            <i class="fa-solid fa-plus-circle me-1"></i> นำเข้าคลังความรู้ทันที
                        </button>
                    </h6>
                    <div id="geminiCardsList" class="d-flex flex-column gap-3">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<script>
const AdminNora = {
    qaList: <?= json_encode($knowledge ?? []) ?>,

    notify: function(msg, type = 'info') {
        if (typeof App !== 'undefined' && typeof App.toast === 'function') {
            App.toast(msg, type);
            return;
        }
        let toastEl = document.getElementById('adminNoraToast');
        if (!toastEl) {
            toastEl = document.createElement('div');
            toastEl.id = 'adminNoraToast';
            toastEl.style.cssText = 'position:fixed;top:20px;right:20px;z-index:999999;padding:12px 20px;border-radius:12px;color:#fff;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,0.18);transition:all 0.3s ease;';
            document.body.appendChild(toastEl);
        }
        const colors = { success: '#10b981', error: '#ef4444', warning: '#f59e0b', info: '#2563eb' };
        toastEl.style.backgroundColor = colors[type] || '#2563eb';
        toastEl.textContent = msg;
        toastEl.style.display = 'block';
        toastEl.style.opacity = '1';
        setTimeout(() => {
            toastEl.style.opacity = '0';
            setTimeout(() => toastEl.style.display = 'none', 300);
        }, 3000);
    },

    resetForm: function() {
        const form = document.getElementById('adminNoraQaForm');
        if (form) form.reset();
        document.getElementById('admin_qa_id').value = '';
        document.getElementById('qaFormTitle').innerHTML = '<i class="fa-solid fa-circle-plus text-success me-2"></i>เพิ่มคำถาม-คำตอบใหม่';
    },

    editQa: function(id) {
        const item = this.qaList.find(i => String(i.id) === String(id));
        if (!item) return;
        document.getElementById('admin_qa_id').value = item.id || '';
        document.getElementById('admin_qa_keywords').value = item.keywords || '';
        document.getElementById('admin_qa_question').value = item.question || '';
        document.getElementById('admin_qa_answer').value = item.answer || '';
        document.getElementById('admin_qa_link_url').value = item.link_url || '';
        document.getElementById('admin_qa_link_title').value = item.link_title || '';
        document.getElementById('qaFormTitle').innerHTML = '<i class="fa-solid fa-pen text-warning me-2"></i>แก้ไขคำถาม: ' + (item.question || '');
        this.notify('โหลดข้อมูลเข้าฟอร์มเรียบร้อยแล้ว', 'info');
    },

    saveQa: function() {
        const form = document.getElementById('adminNoraQaForm');
        const formData = new FormData(form);
        this.notify('กำลังบันทึก...', 'info');

        fetch('<?= base_url('admin/nora-ai/save-qa') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                this.resetForm();
                this.refreshList();
            } else {
                this.notify(data.message || 'บันทึกล้มเหลว', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            this.notify('ข้อผิดพลาดการเชื่อมต่อ', 'error');
        });
    },

    deleteQa: function(id, title) {
        if (!confirm('คุณแน่ใจหรือไม่ที่จะลบคำถาม "' + (title || id) + '"?')) return;
        this.notify('กำลังลบข้อมูล...', 'info');

        fetch('<?= base_url('admin/nora-ai/delete-qa/') ?>' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                this.refreshList();
            } else {
                this.notify(data.message || 'ลบล้มเหลว', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            this.notify('ข้อผิดพลาดการเชื่อมต่อ', 'error');
        });
    },

    syncKnowledge: function() {
        this.notify('กำลังซิงค์และสร้างความรู้จากข้อมูลทั้งเว็บไซต์...', 'info');

        fetch('<?= base_url('admin/nora-ai/sync-knowledge') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                this.refreshList();
            } else {
                this.notify(data.message || 'ซิงค์ล้มเหลว', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            this.notify('ข้อผิดพลาดการเชื่อมต่อ', 'error');
        });
    },

    refreshList: function() {
        fetch('<?= base_url('admin/nora-ai/list') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.qaList = data.items || [];
                const countBadge = document.getElementById('statQaCount');
                const tableBadge = document.getElementById('tableQaCount');
                if (countBadge) countBadge.innerText = this.qaList.length;
                if (tableBadge) tableBadge.innerText = this.qaList.length;
                this.renderTable(this.qaList);
            }
        })
        .catch(err => console.error(err));
    },

    renderTable: function(items) {
        const tbody = document.getElementById('adminQaTableBody');
        if (!tbody) return;

        if (!items || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">ยังไม่มีข้อมูลในคลังความรู้</td></tr>';
            return;
        }

        let html = '';
        items.forEach(item => {
            const kwArr = (item.keywords || '').split(',');
            const kwBadges = kwArr.map(k => `<span class="badge bg-warning bg-opacity-25 text-dark fw-semibold me-1 mb-1">${k.trim()}</span>`).join('');
            const safeTitle = (item.question || '').replace(/'/g, "\\'");
            html += `
                <tr>
                    <td>${kwBadges}</td>
                    <td class="fw-bold text-dark">${item.question || '-'}</td>
                    <td class="text-muted"><div class="line-clamp-2">${item.answer || '-'}</div></td>
                    <td class="text-center text-nowrap">
                        <button type="button" onclick="AdminNora.editQa('${item.id}')" class="btn btn-xs btn-outline-warning text-dark me-1" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                        <button type="button" onclick="AdminNora.deleteQa('${item.id}', '${safeTitle}')" class="btn btn-xs btn-outline-danger" title="ลบ"><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    },

    filterTable: function() {
        const query = (document.getElementById('qaSearchInput')?.value || '').toLowerCase();
        const filtered = this.qaList.filter(i => {
            return (i.keywords && i.keywords.toLowerCase().includes(query)) ||
                   (i.question && i.question.toLowerCase().includes(query)) ||
                   (i.answer && i.answer.toLowerCase().includes(query));
        });
        this.renderTable(filtered);
    },

    saveSettings: function() {
        const form = document.getElementById('adminNoraSettingsForm');
        const formData = new FormData(form);
        this.notify('กำลังบันทึกการตั้งค่า...', 'info');

        fetch('<?= base_url('admin/nora-ai/save-settings') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.notify(data.message || 'บันทึกล้มเหลว', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            this.notify('ข้อผิดพลาดการเชื่อมต่อ', 'error');
        });
    },

    // AI Live Simulator
    clearSimChat: function() {
        const area = document.getElementById('simChatArea');
        if (area) {
            area.innerHTML = '<div class="p-3 rounded-4 bg-white shadow-xs border align-self-start" style="max-width: 80%;">สวัสดีค่ะ! 🙏 น้องโนราพร้อมตอบคำถามทดสอบแล้วค่ะ</div>';
        }
    },

    sendSimMessage: function() {
        const input = document.getElementById('simInput');
        const area = document.getElementById('simChatArea');
        if (!input || !area) return;

        const text = input.value.trim();
        if (!text) return;

        // User bubble
        const userDiv = document.createElement('div');
        userDiv.className = 'p-3 rounded-4 bg-primary text-white shadow-xs align-self-end';
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
            botDiv.className = 'p-3 rounded-4 bg-white shadow-xs border align-self-start';
            botDiv.style.maxWidth = '85%';
            
            let formatted = (data.reply || '')
                .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
            botDiv.innerHTML = formatted;

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
            console.error(err);
            const errDiv = document.createElement('div');
            errDiv.className = 'p-3 rounded-4 bg-danger bg-opacity-10 text-danger align-self-start';
            errDiv.textContent = 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์';
            area.appendChild(errDiv);
            area.scrollTop = area.scrollHeight;
        });
    },

    // Gemini AI Knowledge Co-Pilot Handlers
    geminiModal: null,
    geminiExtractedItems: [],

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

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> กำลังทดสอบ...';
        }

        const formData = new FormData();
        formData.append('api_key', key);

        fetch('<?= base_url('admin/nora-ai/test-gemini') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-plug me-1"></i> ทดสอบเชื่อมต่อ';
            }

            if (statusBox) {
                statusBox.style.display = 'block';
                if (data.status === 'success') {
                    statusBox.innerHTML = '<div class="alert alert-success py-2 px-3 small mb-0"><i class="fa-solid fa-circle-check me-1"></i> ' + data.message + '</div>';
                    this.notify(data.message, 'success');
                } else {
                    statusBox.innerHTML = '<div class="alert alert-danger py-2 px-3 small mb-0"><i class="fa-solid fa-triangle-exclamation me-1"></i> ' + data.message + '</div>';
                    this.notify(data.message, 'error');
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-plug me-1"></i> ทดสอบเชื่อมต่อ';
            }
            this.notify('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
        });
    },

    selectedDocFile: null,

    handleFileSelect: function(input) {
        if (input.files && input.files[0]) {
            this.selectedDocFile = input.files[0];
            const nameEl = document.getElementById('selectedFileName');
            const badgeEl = document.getElementById('fileSelectedBadge');
            const promptEl = document.getElementById('fileUploadPrompt');
            if (nameEl) nameEl.textContent = this.selectedDocFile.name + ' (' + (this.selectedDocFile.size / 1024).toFixed(1) + ' KB)';
            if (badgeEl) badgeEl.style.display = 'block';
            if (promptEl) promptEl.style.display = 'none';
        }
    },

    clearSelectedFile: function() {
        this.selectedDocFile = null;
        const fileInput = document.getElementById('geminiDocFile');
        if (fileInput) fileInput.value = '';
        const badgeEl = document.getElementById('fileSelectedBadge');
        const promptEl = document.getElementById('fileUploadPrompt');
        if (badgeEl) badgeEl.style.display = 'none';
        if (promptEl) promptEl.style.display = 'block';
    },

    openGeminiModal: function() {
        const el = document.getElementById('geminiKnowledgeModal');
        if (el && typeof bootstrap !== 'undefined') {
            if (!this.geminiModal) this.geminiModal = new bootstrap.Modal(el);
            document.getElementById('geminiRawContent').value = '';
            this.clearSelectedFile();
            document.getElementById('geminiResultContainer').style.display = 'none';
            this.geminiExtractedItems = [];
            this.geminiModal.show();
        }
    },

    runGeminiExtract: function() {
        const content = (document.getElementById('geminiRawContent')?.value || '').trim();
        const file = this.selectedDocFile;

        if (!file && (!content || content.length < 10)) {
            this.notify('กรุณาเลือกไฟล์เอกสาร (PDF, DOCX, รูปภาพ) หรือพิมพ์ข้อความอย่างน้อย 10 ตัวอักษร', 'warning');
            return;
        }

        const btn = document.getElementById('btnRunGemini');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Gemini กำลังอ่านเอกสารและสกัดความรู้...';
        }

        const formData = new FormData();
        if (file) {
            formData.append('doc_file', file);
        }
        if (content) {
            formData.append('content', content);
        }

        fetch('<?= base_url('admin/nora-ai/gemini-extract') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles me-1"></i> สกัดความรู้ด้วย Gemini';
            }

            if (data.status === 'success') {
                this.geminiExtractedItems = data.items || [];
                this.renderGeminiResults(this.geminiExtractedItems);
                this.notify(data.message, 'success');
            } else {
                this.notify(data.message || 'เกิดข้อผิดพลาดในการสกัดความรู้', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles me-1"></i> สกัดความรู้ด้วย Gemini';
            }
            this.notify('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    },

    renderGeminiResults: function(items) {
        const container = document.getElementById('geminiResultContainer');
        const list = document.getElementById('geminiCardsList');
        if (!container || !list) return;

        if (!items || items.length === 0) {
            list.innerHTML = '<div class="alert alert-warning small">ไม่พบผลลัพธ์ที่สามารถสกัดได้</div>';
            container.style.display = 'block';
            return;
        }

        let html = '';
        items.forEach((item, idx) => {
            const num = idx + 1;
            html += `
                <div class="card border rounded-3 p-3 bg-white shadow-xs">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-primary">ข้อที่ ${num}</span>
                        <span class="badge bg-warning bg-opacity-25 text-dark">Keywords: ${item.keywords || '-'}</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">❓ ${item.question || '-'}</h6>
                    <p class="text-muted small mb-0 bg-light p-2 rounded-2">💡 ${item.answer || '-'}</p>
                </div>
            `;
        });

        list.innerHTML = html;
        container.style.display = 'block';
    },

    importGeminiQa: function() {
        if (!this.geminiExtractedItems || this.geminiExtractedItems.length === 0) {
            this.notify('ไม่พบรายการที่ต้องการนำเข้า', 'warning');
            return;
        }

        this.notify('กำลังนำเข้าชุดความรู้...', 'info');
        const formData = new FormData();
        formData.append('items', JSON.stringify(this.geminiExtractedItems));

        fetch('<?= base_url('admin/nora-ai/save-multiple-qa') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.notify(data.message, 'success');
                if (this.geminiModal) this.geminiModal.hide();
                this.refreshList();
            } else {
                this.notify(data.message || 'นำเข้าล้มเหลว', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            this.notify('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    }
};
</script>
<?= $this->endSection() ?>

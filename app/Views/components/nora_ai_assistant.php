<?php
    $noraSettings = function_exists('get_nora_settings') ? get_nora_settings() : [
        'bot_name' => 'น้องโนรา (Nora AI)',
        'tagline' => 'ผู้ช่วยบริการประชาชน 24 ชม.',
        'greeting_msg' => "สวัสดีค่ะ 🙏 น้องโนรา ยินดีให้บริการ ณ จังหวัดพัทลุง!\nวันนี้มีเรื่องราชการ e-Services หรือท่องเที่ยวใดให้ช่วยเหลือ พิมพ์ถามได้เลยนะคะ 😊",
        'is_enabled' => true
    ];
    $isOfficer = $isOfficer ?? session()->get('isLoggedIn');
?>

<style>
/* ==========================================================================
   NONG NORA AI ASSISTANT (24/7 CITIZEN SERVICE CHATBOT UI)
   ========================================================================== */
.nora-floating-launcher {
    position: fixed;
    bottom: 25px;
    right: 25px;
    z-index: 1045;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
    color: #fff !important;
    border: 3px solid #fef3c7;
    border-radius: 50px;
    padding: 12px 22px;
    box-shadow: 0 10px 25px rgba(217, 119, 6, 0.45), 0 0 15px rgba(245, 158, 11, 0.3);
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-weight: 700;
    font-size: 1.05rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-decoration: none;
}
.nora-floating-launcher:hover {
    transform: translateY(-5px) scale(1.04);
    box-shadow: 0 15px 35px rgba(217, 119, 6, 0.6);
    color: #fff;
}
.nora-avatar-icon {
    width: 34px;
    height: 34px;
    background: #fff;
    color: #d97706;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Chat Drawer Window */
.nora-chat-window {
    position: fixed;
    bottom: 95px;
    right: 25px;
    width: 400px;
    max-width: calc(100vw - 40px);
    height: 560px;
    max-height: calc(100vh - 120px);
    background: #ffffff;
    border-radius: 28px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(217, 119, 6, 0.2);
    display: flex;
    flex-direction: column;
    z-index: 1055;
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    transform-origin: bottom right;
    opacity: 0;
    pointer-events: none;
    transform: scale(0.8) translateY(20px);
}
.nora-chat-window.active {
    opacity: 1;
    pointer-events: all;
    transform: scale(1) translateY(0);
}

.nora-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #78350f 100%);
    color: #ffffff;
    padding: 16px 20px;
    border-bottom: 3px solid #f59e0b;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.nora-status-dot {
    width: 10px; height: 10px;
    background: #10b981;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 8px #10b981;
    animation: pulse 2s infinite;
}

.nora-messages-area {
    flex: 1;
    overflow-y: auto;
    padding: 18px;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 14px;
    scroll-behavior: smooth;
}

.nora-bubble {
    max-width: 82%;
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 0.92rem;
    line-height: 1.5;
    word-break: break-word;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}
.nora-bubble-bot {
    background: #ffffff;
    color: #1e293b;
    border-bottom-left-radius: 4px;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #f59e0b;
    align-self: flex-start;
}
.nora-bubble-user {
    background: linear-gradient(135deg, #059669, #10b981);
    color: #ffffff;
    border-bottom-right-radius: 4px;
    align-self: flex-end;
}

.nora-chips-container {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 6px;
}
.nora-chip {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fde68a;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.nora-chip:hover {
    background: #f59e0b;
    color: #ffffff;
    border-color: #f59e0b;
    transform: translateY(-2px);
}

.nora-input-area {
    padding: 12px 16px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.nora-input-area input {
    flex: 1;
    border: 1px solid #cbd5e1;
    border-radius: 24px;
    padding: 10px 18px;
    font-size: 0.95rem;
    outline: none;
    transition: border-color 0.2s;
}
.nora-input-area input:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
}

.nora-send-btn {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}
.nora-send-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
}

.nora-action-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: #fef3c7;
    border: 1px solid #fde68a;
    border-radius: 12px;
    margin-top: 6px;
    color: #78350f;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.88rem;
    transition: all 0.2s;
}
.nora-action-card:hover {
    background: #fde68a;
    color: #000;
    transform: translateX(3px);
}

/* Typing indicator */
.typing-dots {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 0;
}
.typing-dots span {
    width: 7px; height: 7px;
    background: #d97706;
    border-radius: 50%;
    animation: typingBounce 1.4s infinite ease-in-out both;
}
.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); opacity: 0.5; }
    40% { transform: scale(1); opacity: 1; }
}

/* Dark theme overrides */
[data-theme="dark"] .nora-chat-window {
    background: #1e293b;
    border-color: #334155;
}
[data-theme="dark"] .nora-messages-area {
    background: #0f172a;
}
[data-theme="dark"] .nora-bubble-bot {
    background: #1e293b;
    color: #e2e8f0;
    border-color: #334155;
}
[data-theme="dark"] .nora-input-area {
    background: #1e293b;
    border-color: #334155;
}
[data-theme="dark"] .nora-input-area input {
    background: #0f172a;
    color: #fff;
    border-color: #475569;
}
</style>

<!-- FLOATING LAUNCHER BUTTON -->
<?php if ($noraSettings['is_enabled']): ?>
<button type="button" class="nora-floating-launcher" id="noraLauncherBtn" onclick="NoraAI.toggle()" title="เปิดผู้ช่วยตอบคำถามประชาชน น้องโนรา AI">
    <div class="nora-avatar-icon"><i class="fa-solid fa-crown"></i></div>
    <div class="d-flex flex-column text-start lh-1">
        <span class="small fw-normal opacity-90" style="font-size: 0.72rem;">ผู้ช่วยอัจฉริยะ 24 ชม.</span>
        <span>💬 น้องโนรา AI</span>
    </div>
</button>
<?php endif; ?>

<!-- CHAT DRAWER WINDOW -->
<div class="nora-chat-window" id="noraChatWindow" role="dialog" aria-labelledby="noraChatTitle">
    <!-- Header -->
    <div class="nora-header">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-circle bg-white text-warning d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; border: 2px solid #fef3c7;">
                <i class="fa-solid fa-chess-queen fs-4 text-warning"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-white d-flex align-items-center gap-2" id="noraChatTitle">
                    <span><?= esc($noraSettings['bot_name'] ?? 'น้องโนรา AI Assistant') ?></span>
                    <span class="nora-status-dot" title="ระบบพร้อมให้บริการ"></span>
                </h6>
                <span class="small text-light opacity-85 d-block" style="font-size: 0.76rem;">
                    <?= esc($noraSettings['tagline'] ?? 'ผู้ช่วยบริการประชาชน 24 ชม.') ?>
                </span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-1">
            <?php if ($isOfficer): ?>
            <button type="button" onclick="NoraAI.openStudio()" class="btn btn-xs btn-outline-warning text-white rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="จัดการฐานความรู้ (On-Page CMS Studio)">
                <i class="fa-solid fa-gear"></i>
            </button>
            <?php endif; ?>
            <button type="button" onclick="NoraAI.clearChat()" class="btn btn-xs btn-outline-light text-white rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border: none;" title="ล้างหน้าจอและเริ่มต้นสนทนาใหม่">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
            <button type="button" onclick="NoraAI.toggle()" class="btn btn-xs btn-outline-light text-white rounded-circle p-0 d-flex align-items-center justify-content-center ms-1" style="width: 32px; height: 32px; border: none;" title="ย่อหน้าต่างแชต">
                <i class="fa-solid fa-xmark fs-5"></i>
            </button>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="nora-messages-area" id="noraMessages">
        <!-- Initial Bot Greeting -->
        <div class="nora-bubble nora-bubble-bot shadow-sm">
            <?= nl2br(esc($noraSettings['greeting_msg'] ?? "สวัสดีค่ะ! 🙏 น้องโนรา ยินดีต้อนรับค่ะ\nวันนี้มีเรื่องราชการ e-Services หรือท่องเที่ยวใดให้ช่วยเหลือ พิมพ์ถามหรือเลือกหัวข้อด้านล่างได้เลยค่ะ 😊")) ?>

            <!-- Starter Topic Chips -->
            <div class="mt-3">
                <span class="small text-muted fw-bold d-block mb-1" style="font-size: 0.75rem;">💡 คำถามยอดฮิตที่ประชาชนสนใจ:</span>
                <div class="nora-chips-container">
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('ติดต่อศาลากลางและเบอร์โทรศัพท์')">📞 ติดต่อจังหวัด & ศาลากลาง</button>
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('แนะนำสถานที่ท่องเที่ยวพัทลุง')">🌿 แนะนำที่เที่ยวเมืองลุง</button>
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('ต้องการยื่นหรือชำระภาษีป้ายท้องถิ่น')">💼 ชำระ/ยื่นภาษีท้องถิ่น</button>
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('คำขวัญจังหวัดพัทลุงคืออะไร')">🎭 มรดกวัฒนธรรมหนังโนรา</button>
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('เช็คผลคะแนนความโปร่งใส ITA')">🏆 เช็คคะแนนโปร่งใส ITA</button>
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('ขั้นตอนการขอก่อสร้างและบ้านเลขที่ e-Permission')">🏠 ขอก่อสร้าง e-Permission</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Input Area -->
    <div class="nora-input-area">
        <input type="text" id="noraInput" placeholder="พิมพ์คำถามหรือค้นหาบริการราชการ..." autocomplete="off" onkeypress="if(event.key==='Enter') NoraAI.sendMessage()">
        <button type="button" class="nora-send-btn" onclick="NoraAI.sendMessage()" aria-label="ส่งข้อความ">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

<!-- MODAL: NORA AI KNOWLEDGE & SETTINGS STUDIO FOR ADMIN/OFFICERS -->
<?php if ($isOfficer): ?>
<div class="modal fade" id="noraAiStudioModal" tabindex="-1" aria-labelledby="noraStudioModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-dark text-warning px-4 py-3 rounded-top-4 border-bottom border-warning border-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkling fs-4 text-warning animate-spin-slow"></i>
                    <h5 class="modal-title fw-bold mb-0 text-white" id="noraStudioModalLabel">Nora AI Brain & Q&A Knowledge Studio</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <ul class="nav nav-tabs fw-bold mb-4" id="noraTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active text-dark" id="qa-tab" data-bs-toggle="tab" data-bs-target="#qa-pane" type="button" role="tab"><i class="fa-solid fa-brain me-1 text-primary"></i> คลังคำถาม-คำตอบ (Q&A)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-dark" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-pane" type="button" role="tab"><i class="fa-solid fa-sliders me-1 text-warning"></i> ตั้งค่าการต้อนรับ & ระบบแชต</button>
                    </li>
                </ul>

                <div class="tab-content" id="noraTabContent">
                    <!-- TAB 1: Q&A KNOWLEDGE BASE -->
                    <div class="tab-pane fade show active" id="qa-pane" role="tabpanel">
                        <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-plus text-success me-1"></i> เพิ่ม / แก้ไข ความรู้ใหม่ให้น้องโนรา AI</h6>
                            <form id="noraQaForm">
                                <input type="hidden" id="qa_id" name="id" value="">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">คำสำคัญที่เกี่ยวข้อง (Keywords) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="qa_keywords" name="keywords" placeholder="คั่นด้วยเครื่องหมายจุลภาค เช่น: น้ำพุร้อน, เขาอกทะลุ, ค่าธรรมเนียม, ขยะ" required>
                                    <div class="form-text small">เมื่อประชาชนพิมพ์คำที่ตรงกับ Keywords เหล่านี้ AI จะนำเสนอคำตอบนี้ทันที</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">คำถามตัวอย่าง (Question) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="qa_question" name="question" placeholder="เช่น: ติดต่อเรื่องการขอใบอนุญาตก่อสร้างได้อย่างไร?" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark">คำตอบของน้องโนรา AI (Answer) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="qa_answer" name="answer" rows="3" placeholder="ระบุคำตอบรายละเอียดที่ต้องการให้ระบบตอบ..." required></textarea>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-md-7">
                                        <label class="form-label small fw-semibold">ปุ่มทางลัด/ลิงก์อ้างอิง (URL Link - ถ้ามี)</label>
                                        <input type="text" class="form-control form-control-sm" id="qa_link_url" name="link_url" placeholder="เช่น #services, ita, หรือ https://...">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold">ข้อความบนปุ่มลิงก์</label>
                                        <input type="text" class="form-control form-control-sm" id="qa_link_title" name="link_title" placeholder="เช่น ⚡ กดเข้าสู่หน้ารับบริการ">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" onclick="NoraAI.resetQaForm()" class="btn btn-sm btn-outline-secondary rounded-pill px-3">ล้างฟอร์ม</button>
                                    <button type="button" onclick="NoraAI.saveQaItem()" class="btn btn-sm btn-success fw-bold rounded-pill px-4 shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> บันทึกเข้าคลังสมอง AI
                                    </button>
                                </div>
                            </form>
                        </div>

                        <h6 class="fw-bold small text-muted border-bottom pb-2 mb-3">📚 รายการความรู้ Q&A ที่จดจำในระบบขณะนี้ (<span id="noraQaCount">0</span> รายการ)</h6>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm align-middle small">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 25%;">Keywords</th>
                                        <th style="width: 30%;">คำถาม (Question)</th>
                                        <th style="width: 35%;">คำตอบย่อ (Answer)</th>
                                        <th style="width: 10%; text-align: center;">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="noraQaTableBody">
                                    <tr><td colspan="4" class="text-center text-muted">กำลังโหลดข้อมูล...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: BOT SETTINGS -->
                    <div class="tab-pane fade" id="settings-pane" role="tabpanel">
                        <form id="noraSettingsForm">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">ชื่อผู้ช่วย AI (Bot Name)</label>
                                    <input type="text" class="form-control" name="bot_name" id="set_bot_name" value="<?= esc($noraSettings['bot_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">ข้อความสถานะ (Tagline)</label>
                                    <input type="text" class="form-control" name="tagline" id="set_tagline" value="<?= esc($noraSettings['tagline'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark">ข้อความทักทายแรกเข้า (Greeting Message)</label>
                                <textarea class="form-control" name="greeting_msg" id="set_greeting_msg" rows="3"><?= esc($noraSettings['greeting_msg'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-dark">ข้อความกรณีค้นหาไม่พบ (Fallback Reply)</label>
                                <textarea class="form-control" name="fallback_msg" id="set_fallback_msg" rows="2"><?= esc($noraSettings['fallback_msg'] ?? "น้องโนรายังไม่มั่นใจในคำถามนี้ค่ะ คุณสามารถโทรสอบถามสำนักงานจังหวัด โทร. 074-611621 ได้ในเวลาราชการนะคะ ❤️") ?></textarea>
                            </div>

                            <div class="form-check form-switch p-3 bg-light rounded-3 mb-3">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_enabled" id="set_is_enabled" <?= !empty($noraSettings['is_enabled']) ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold text-dark" for="set_is_enabled">🟢 เปิดใช้งานระบบแชตบอต "น้องโนรา AI" บนหน้าเว็บ 24 ชั่วโมง</label>
                            </div>

                            <div class="text-end">
                                <button type="button" onclick="NoraAI.saveSettings()" class="btn btn-warning fw-bold text-dark rounded-pill px-5 shadow">
                                    <i class="fa-solid fa-check me-1"></i> บันทึกการตั้งค่า
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3 rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const NoraAI = {
    isOpen: false,
    studioModal: null,
    qaList: [],

    toggle: function() {
        const win = document.getElementById('noraChatWindow');
        const launcher = document.getElementById('noraLauncherBtn');
        if (!win) return;

        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            win.classList.add('active');
            if (launcher) launcher.style.opacity = '0.9';
            setTimeout(() => {
                const input = document.getElementById('noraInput');
                if (input) input.focus();
            }, 300);
        } else {
            win.classList.remove('active');
            if (launcher) launcher.style.opacity = '1';
        }
    },

    clearChat: function() {
        const area = document.getElementById('noraMessages');
        if (!area) return;
        area.innerHTML = `
            <div class="nora-bubble nora-bubble-bot shadow-sm">
                สวัสดีอีกครั้งค่ะ! 🙏 น้องโนราพร้อมตอบข้อซักถามแล้วค่ะ
                <div class="mt-2">
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('ติดต่อศาลากลางและเบอร์โทรศัพท์')">📞 ติดต่อศาลากลาง</button>
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('แนะนำสถานที่ท่องเที่ยวพัทลุง')">🌿 ที่เที่ยวเมืองลุง</button>
                    <button type="button" class="nora-chip" onclick="NoraAI.sendChip('เช็คผลคะแนนความโปร่งใส ITA')">🏆 ผลประเมิน ITA</button>
                </div>
            </div>
        `;
    },

    sendChip: function(text) {
        const input = document.getElementById('noraInput');
        if (input) {
            input.value = text;
            this.sendMessage();
        }
    },

    sendMessage: function() {
        const input = document.getElementById('noraInput');
        const area = document.getElementById('noraMessages');
        if (!input || !area) return;

        const text = input.value.trim();
        if (!text) return;

        // Append user message
        const userDiv = document.createElement('div');
        userDiv.className = 'nora-bubble nora-bubble-user shadow-sm';
        userDiv.textContent = text;
        area.appendChild(userDiv);
        input.value = '';
        this.scrollToBottom();

        // Append typing indicator
        const typingDiv = document.createElement('div');
        typingDiv.className = 'nora-bubble nora-bubble-bot shadow-sm typing-indicator-box';
        typingDiv.innerHTML = `
            <span class="small text-muted d-block mb-1" style="font-size:0.75rem;">น้องโนรา กำลังวิเคราะห์ข้อมูล...</span>
            <div class="typing-dots"><span></span><span></span><span></span></div>
        `;
        area.appendChild(typingDiv);
        this.scrollToBottom();

        // Fetch reply from backend
        const formData = new FormData();
        formData.append('message', text);

        fetch('<?= base_url('api/nora-ai/chat') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Remove typing indicator
            if (typingDiv.parentNode) typingDiv.parentNode.removeChild(typingDiv);

            const botDiv = document.createElement('div');
            botDiv.className = 'nora-bubble nora-bubble-bot shadow-sm';
            
            // Format line breaks and basic bold text
            let replyHTML = (data.reply || "ขออภัยค่ะ ไม่สามารถเชื่อมต่อระบบตอบสนองได้")
                .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
            botDiv.innerHTML = replyHTML;

            // Render interactive action cards if present
            if (data.cards && data.cards.length > 0) {
                const cardsContainer = document.createElement('div');
                cardsContainer.className = 'mt-2';
                data.cards.forEach(c => {
                    const cardLink = document.createElement('a');
                    cardLink.className = 'nora-action-card shadow-sm';
                    cardLink.href = c.url || '#';
                    cardLink.target = c.url && (c.url.startsWith('http') || c.url.includes('.pdf')) ? '_blank' : '_self';
                    cardLink.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <i class="${c.icon || 'fa-solid fa-link'}"></i>
                            <span class="line-clamp-1">${c.title || 'ดูรายละเอียด'}</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    `;
                    if (c.url && c.url.startsWith('#')) {
                        cardLink.onclick = (e) => {
                            e.preventDefault();
                            NoraAI.toggle();
                            if (typeof scrollToSectionOrHome === 'function') {
                                scrollToSectionOrHome(c.url.substring(1));
                            } else {
                                window.location.href = c.url;
                            }
                        };
                    }
                    cardsContainer.appendChild(cardLink);
                });
                botDiv.appendChild(cardsContainer);
            }

            area.appendChild(botDiv);
            this.scrollToBottom();
        })
        .catch(err => {
            console.error(err);
            if (typingDiv.parentNode) typingDiv.parentNode.removeChild(typingDiv);
            const errDiv = document.createElement('div');
            errDiv.className = 'nora-bubble nora-bubble-bot text-danger';
            errDiv.innerText = 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง';
            area.appendChild(errDiv);
            this.scrollToBottom();
        });
    },

    scrollToBottom: function() {
        const area = document.getElementById('noraMessages');
        if (area) {
            setTimeout(() => { area.scrollTop = area.scrollHeight; }, 50);
        }
    },

    <?php if ($isOfficer): ?>
    openStudio: function() {
        const el = document.getElementById('noraAiStudioModal');
        if (el && typeof bootstrap !== 'undefined') {
            if (el.parentNode !== document.body) {
                document.body.appendChild(el);
            }
            if (!this.studioModal) this.studioModal = new bootstrap.Modal(el);
            this.loadKnowledgeList();
            this.studioModal.show();
        }
    },

    loadKnowledgeList: function() {
        const tbody = document.getElementById('noraQaTableBody');
        const badge = document.getElementById('noraQaCount');
        if (!tbody) return;

        fetch('<?= base_url('admin/nora-ai/list') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                this.qaList = data.items || [];
                if (badge) badge.innerText = this.qaList.length;
                if (this.qaList.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">ยังไม่มีข้อมูลในคลังความรู้</td></tr>';
                    return;
                }
                let html = '';
                this.qaList.forEach(item => {
                    html += `
                        <tr>
                            <td><span class="badge bg-secondary bg-opacity-25 text-dark text-wrap text-start">${item.keywords}</span></td>
                            <td class="fw-bold text-primary">${item.question}</td>
                            <td class="text-muted"><div class="line-clamp-2">${item.answer}</div></td>
                            <td class="text-center">
                                <button type="button" onclick="NoraAI.editQa('${item.id}')" class="btn btn-xs btn-outline-warning text-dark me-1" title="แก้ไข"><i class="fa-solid fa-pen"></i></button>
                                <button type="button" onclick="NoraAI.deleteQa('${item.id}', '${item.question}')" class="btn btn-xs btn-outline-danger" title="ลบ"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            }
        })
        .catch(err => console.error(err));
    },

    resetQaForm: function() {
        const form = document.getElementById('noraQaForm');
        if (form) form.reset();
        document.getElementById('qa_id').value = '';
    },

    editQa: function(id) {
        const item = this.qaList.find(i => i.id === id);
        if (!item) return;
        document.getElementById('qa_id').value = item.id || '';
        document.getElementById('qa_keywords').value = item.keywords || '';
        document.getElementById('qa_question').value = item.question || '';
        document.getElementById('qa_answer').value = item.answer || '';
        document.getElementById('qa_link_url').value = item.link_url || '';
        document.getElementById('qa_link_title').value = item.link_title || '';
        if (typeof App !== 'undefined') App.toast('โหลดข้อมูลเตรียมแก้ไขแล้ว', 'info');
    },

    saveQaItem: function() {
        const form = document.getElementById('noraQaForm');
        if (!form || !form.checkValidity()) {
            if (form) form.reportValidity();
            return;
        }
        const formData = new FormData(form);
        if (typeof App !== 'undefined') App.toast('กำลังบันทึก...', 'info');

        fetch('<?= base_url('admin/nora-ai/save-qa') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined') App.toast(data.message, 'success');
                this.resetQaForm();
                this.loadKnowledgeList();
            } else {
                if (typeof App !== 'undefined') App.toast(data.message || 'เกิดข้อผิดพลาด', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof App !== 'undefined') App.toast('ข้อผิดพลาดทางเครือข่าย', 'error');
        });
    },

    deleteQa: function(id, title) {
        if (!confirm('คุณต้องการลบคำถาม "' + title + '" ออกจากคลังความรู้หรือไม่?')) return;

        fetch('<?= base_url('admin/nora-ai/delete-qa/') ?>' + id, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined') App.toast(data.message, 'success');
                this.loadKnowledgeList();
            } else {
                if (typeof App !== 'undefined') App.toast(data.message || 'ไม่สามารถลบรายการได้', 'error');
            }
        })
        .catch(err => console.error(err));
    },

    saveSettings: function() {
        const form = document.getElementById('noraSettingsForm');
        const formData = new FormData(form);
        if (typeof App !== 'undefined') App.toast('กำลังบันทึกการตั้งค่า...', 'info');

        fetch('<?= base_url('admin/nora-ai/save-settings') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (typeof App !== 'undefined') App.toast(data.message, 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                if (typeof App !== 'undefined') App.toast(data.message || 'ไม่สามารถบันทึกได้', 'error');
            }
        })
        .catch(err => console.error(err));
    }
    <?php endif; ?>
};
</script>

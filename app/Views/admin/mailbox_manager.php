<?php
$emails        = $emails ?? [];
$pager         = $pager ?? null;
$folder        = $folder ?? 'inbox';
$search        = $search ?? '';
$account       = $account ?? 'all';
$accounts      = $accounts ?? [];
$accountCounts = $accountCounts ?? [];
$counts        = $counts ?? ['inbox' => 0, 'unread' => 0, 'official' => 0, 'citizen' => 0, 'starred' => 0, 'trash' => 0];
?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <!-- 1. Top Header & Account Switcher -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h4 class="fw-bold mb-0"><i class="fa-solid fa-inbox text-primary me-2"></i>กล่องจดหมายกลางภาครัฐ</h4>
                <span class="badge bg-dark rounded-pill px-3 py-1 fw-bold">MOI Mailbox Hub</span>
            </div>
            <p class="text-muted small mb-0 mt-1">ศูนย์กลางตรวจดูและจัดการอีเมลราชการของจังหวัดพัทลุงในระบบหลังบ้าน (2 บัญชีทางการ)</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success rounded-pill px-3 fw-bold shadow-xs" onclick="syncMailboxNow()" id="btnSyncMail">
                <i class="fa-solid fa-rotate me-1"></i>ซิงค์อีเมลใหม่
            </button>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#mailboxConfigModal">
                <i class="fa-solid fa-gear me-1"></i>ตั้งค่าเซิร์ฟเวอร์
            </button>
            <a href="https://mail.moi.go.th" target="_blank" class="btn btn-outline-primary rounded-pill px-3">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Webmail MOI
            </a>
        </div>
    </div>

    <!-- Account Switcher Pill Bar -->
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="<?= base_url('admin/mailbox?account=all&folder=' . esc($folder)) ?>" 
           class="btn btn-sm rounded-pill px-3 fw-bold <?= $account === 'all' ? 'btn-primary' : 'btn-outline-secondary bg-white' ?>">
            <i class="fa-solid fa-envelopes-bulk me-1"></i>ทุกกล่องจดหมาย (All Mailboxes)
        </a>

        <?php foreach ($accounts as $emailKey => $acc): 
            $accUnread = $accountCounts[$emailKey]['unread'] ?? 0;
            $isActive = $account === $emailKey;
        ?>
            <a href="<?= base_url('admin/mailbox?account=' . esc($emailKey) . '&folder=' . esc($folder)) ?>" 
               class="btn btn-sm rounded-pill px-3 fw-bold <?= $isActive ? 'btn-' . ($acc['badge'] ?? 'primary') : 'btn-outline-secondary bg-white' ?>">
                <i class="fa-solid <?= $acc['key'] === 'saraban' ? 'fa-scroll text-success' : 'fa-building text-primary' ?> me-1"></i>
                <?= esc($acc['label']) ?> (<?= esc($emailKey) ?>)
                <?php if ($accUnread > 0): ?>
                    <span class="badge bg-danger rounded-pill ms-1"><?= $accUnread ?></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- 2. Webmail Split-Pane Layout -->
    <div class="row g-3">
        <!-- A. Left Sidebar Folders -->
        <div class="col-lg-3 col-xl-2">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white h-100">
                <div class="d-flex flex-column gap-1">
                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=inbox') ?>" class="mailbox-folder-link <?= $folder === 'inbox' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-inbox text-primary"></i>
                            <span>กล่องขาเข้า</span>
                        </div>
                        <?php if (!empty($counts['unread'])): ?>
                            <span class="badge bg-danger rounded-pill"><?= $counts['unread'] ?></span>
                        <?php else: ?>
                            <small class="text-muted"><?= $counts['inbox'] ?></small>
                        <?php endif; ?>
                    </a>

                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=official') ?>" class="mailbox-folder-link <?= $folder === 'official' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-building-columns text-success"></i>
                            <span>หนังสือราชการ</span>
                        </div>
                        <small class="text-muted"><?= $counts['official'] ?></small>
                    </a>

                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=citizen') ?>" class="mailbox-folder-link <?= $folder === 'citizen' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-tag text-info"></i>
                            <span>จากประชาชน</span>
                        </div>
                        <small class="text-muted"><?= $counts['citizen'] ?></small>
                    </a>

                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=starred') ?>" class="mailbox-folder-link <?= $folder === 'starred' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-star text-warning"></i>
                            <span>เรื่องสำคัญ</span>
                        </div>
                        <small class="text-muted"><?= $counts['starred'] ?></small>
                    </a>

                    <hr class="my-2 opacity-10">

                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=trash') ?>" class="mailbox-folder-link text-muted <?= $folder === 'trash' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>ถังขยะ</span>
                        </div>
                        <small><?= $counts['trash'] ?></small>
                    </a>
                </div>

                <div class="mt-auto pt-4">
                    <div class="p-2.5 rounded-3 bg-light border small text-muted">
                        <div class="d-flex align-items-center gap-1.5 fw-bold text-dark mb-1">
                            <i class="fa-solid fa-shield-halved text-success"></i> ระบบเชื่อมต่อสด
                        </div>
                        <div class="text-truncate">Server: mail.moi.go.th</div>
                        <div class="small opacity-75">SSL Port 993 (IMAP)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- B. Middle Email List -->
        <div class="col-lg-4 col-xl-4">
            <div class="card border-0 rounded-4 shadow-xs bg-white h-100 overflow-hidden d-flex flex-column">
                <!-- Search header -->
                <div class="p-3 border-bottom bg-light">
                    <form method="GET" action="<?= base_url('admin/mailbox') ?>">
                        <input type="hidden" name="folder" value="<?= esc($folder) ?>">
                        <input type="hidden" name="account" value="<?= esc($account) ?>">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="q" value="<?= esc($search) ?>" class="form-control border-start-0 custom-input" placeholder="ค้นหาอีเมล, ผู้ส่ง, หรือหัวข้อ...">
                            <?php if (!empty($search)): ?>
                                <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=' . esc($folder)) ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark"></i></a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Email List Scroll Area -->
                <div class="email-list-pane flex-grow-1 overflow-auto" style="max-height: 70vh;">
                    <?php if (empty($emails)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-envelope-open fs-1 opacity-25 d-block mb-2"></i>
                            ไม่มีอีเมลในหมวดนี้
                        </div>
                    <?php else: ?>
                        <?php foreach ($emails as $em): 
                            $isUnread = empty($em['is_read']);
                            $isSaraban = str_contains($em['recipient_email'] ?? '', 'saraban');
                        ?>
                            <div class="email-list-item <?= $isUnread ? 'unread' : '' ?>" id="email-item-<?= $em['id'] ?>" onclick="viewEmailDetail(<?= $em['id'] ?>)">
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <button type="button" class="btn-star <?= !empty($em['is_starred']) ? 'starred' : '' ?>" onclick="toggleStarEmail(event, <?= $em['id'] ?>)">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                        <span class="email-sender text-truncate fw-bold <?= $isUnread ? 'text-dark' : 'text-secondary' ?>">
                                            <?= esc($em['sender_name'] ?: $em['sender_email']) ?>
                                        </span>
                                    </div>
                                    <small class="email-time text-muted flex-shrink-0">
                                        <?= date('d M', strtotime($em['received_at'])) ?>
                                    </small>
                                </div>
                                <div class="email-subject text-truncate <?= $isUnread ? 'fw-bold text-dark' : 'text-secondary' ?>">
                                    <?= esc($em['subject'] ?: '(ไม่มีหัวข้อ)') ?>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <div class="d-flex align-items-center gap-1.5 overflow-hidden">
                                        <span class="badge rounded-pill <?= $isSaraban ? 'bg-success bg-opacity-15 text-success border border-success border-opacity-25' : 'bg-primary bg-opacity-15 text-primary border border-primary border-opacity-25' ?>" style="font-size: 0.68rem;">
                                            <?= $isSaraban ? 'สารบรรณ' : 'อีเมลกลาง' ?>
                                        </span>
                                        <small class="email-snippet text-muted text-truncate">
                                            <?= esc(mb_substr($em['body_plain'] ?: strip_tags($em['body_html']), 0, 40)) ?>
                                        </small>
                                    </div>
                                    <?php if (!empty($em['has_attachment'])): ?>
                                        <i class="fa-solid fa-paperclip text-muted small flex-shrink-0" title="มีไฟล์แนบ"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($pager)): ?>
                    <div class="p-2 border-top d-flex justify-content-center small">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- C. Right Reading Pane -->
        <div class="col-lg-5 col-xl-6">
            <div class="card border-0 rounded-4 shadow-xs bg-white h-100 p-4 d-flex flex-column" id="readingPaneContainer">
                <div id="readingPanePlaceholder" class="text-center py-5 my-auto text-muted">
                    <i class="fa-regular fa-envelope-open fs-1 opacity-25 d-block mb-3"></i>
                    <h5>เลือกอีเมลจากรายการด้านซ้ายเพื่อเปิดอ่าน</h5>
                    <p class="small text-muted mb-0">ระบบจะแสดงผลเนื้อหาฉบับเต็มและไฟล์แนบที่นี่</p>
                </div>

                <div id="readingPaneContent" class="d-none flex-grow-1 d-flex flex-column">
                    <!-- Email Action Toolbar -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill fw-bold" onclick="convertCurrentEmailToRequest()" id="btnConvertRequest">
                                <i class="fa-solid fa-file-circle-plus me-1"></i>แปลงเป็นเรื่องร้องเรียน/คำร้อง
                            </button>
                            <a id="btnReplyMail" href="#" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="fa-solid fa-reply me-1"></i>ตอบกลับ
                            </a>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="deleteCurrentEmail()" title="ย้ายไปถังขยะ">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email Header Info -->
                    <div class="mb-3">
                        <h4 class="fw-bold text-dark mb-2" id="readSubject"></h4>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light border">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle-sm bg-primary text-white fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;" id="readAvatar">
                                    MOI
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" id="readSenderName"></div>
                                    <small class="text-muted" id="readSenderEmail"></small>
                                </div>
                            </div>
                            <div class="text-end small text-muted">
                                <div>กล่องปลายทาง: <span class="fw-bold text-dark" id="readRecipient"></span></div>
                                <div id="readReceivedAt"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Box (If any) -->
                    <div id="readAttachmentsSection" class="mb-3 d-none">
                        <div class="small fw-bold text-muted mb-1"><i class="fa-solid fa-paperclip me-1"></i>ไฟล์แนบ:</div>
                        <div class="d-flex flex-wrap gap-2" id="readAttachmentsList"></div>
                    </div>

                    <!-- Email Body Viewer -->
                    <div class="email-body-content flex-grow-1 p-3 rounded-3 border bg-white overflow-auto" id="readBodyHtml" style="min-height: 250px; max-height: 48vh; line-height: 1.7;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Mail Server Settings -->
<div class="modal fade" id="mailboxConfigModal" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-dark text-white p-3 px-4">
                <h5 class="modal-title fw-bold" id="configModalLabel"><i class="fa-solid fa-server me-2 text-warning"></i>การตั้งค่า Mail Server (MOI IMAP)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 mb-3">
                    <div class="fw-bold text-success mb-1"><i class="fa-solid fa-circle-check me-1"></i> เชื่อมต่อสด 2 บัญชีทางการพร้อมกัน:</div>
                    <ul class="mb-0 small ps-3">
                        <li><strong>อีเมลกลาง:</strong> phatthalung@moi.go.th (Port 993 SSL)</li>
                        <li><strong>งานสารบรรณ:</strong> saraban_phatthalung@moi.go.th (Port 993 SSL)</li>
                    </ul>
                </div>
                <p class="text-muted small mb-0">รหัสผ่านและค่าการเชื่อมต่อถูกบันทึกไว้อย่างปลอดภัยในไฟล์ระบบ <code>.env</code> เรียบร้อยแล้ว</p>
            </div>
            <div class="modal-footer border-top p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<style>
.mailbox-folder-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.65rem 0.9rem;
    border-radius: 12px;
    color: #334155;
    text-decoration: none !important;
    font-weight: 500;
    font-size: 0.92rem;
    transition: all 0.15s ease;
}

.mailbox-folder-link:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.mailbox-folder-link.active {
    background: #ecfdf5;
    color: #047857;
    font-weight: 600;
}

.email-list-item {
    padding: 0.85rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    transition: background 0.15s ease;
}

.email-list-item:hover {
    background: #f8fafc;
}

.email-list-item.selected {
    background: #e0f2fe !important;
    border-left: 4px solid #0284c7;
}

.email-list-item.unread {
    background: #f0fdf4;
    border-left: 3px solid #10b981;
}

.btn-star {
    background: transparent;
    border: none;
    padding: 0;
    color: #cbd5e1;
    cursor: pointer;
    font-size: 0.9rem;
    transition: color 0.15s ease;
}

.btn-star.starred, .btn-star:hover {
    color: #f59e0b;
}

[data-theme="dark"] .mailbox-folder-link {
    color: #cbd5e1;
}

[data-theme="dark"] .mailbox-folder-link:hover {
    background: rgba(255,255,255,0.05);
}

[data-theme="dark"] .mailbox-folder-link.active {
    background: rgba(16, 185, 129, 0.2);
    color: #6ee7b7;
}

[data-theme="dark"] .email-list-item {
    border-color: rgba(255,255,255,0.05);
}

[data-theme="dark"] .email-list-item:hover {
    background: rgba(255,255,255,0.04);
}

[data-theme="dark"] .email-list-item.unread {
    background: rgba(16, 185, 129, 0.1);
}
</style>

<script>
let currentEmailId = null;
let currentEmailData = null;
const activeAccount = '<?= esc($account) ?>';

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function viewEmailDetail(id) {
    currentEmailId = id;
    document.querySelectorAll('.email-list-item').forEach(i => i.classList.remove('selected'));
    const itemEl = document.getElementById(`email-item-${id}`);
    if (itemEl) {
        itemEl.classList.add('selected');
        itemEl.classList.remove('unread');
    }

    document.getElementById('readingPanePlaceholder').classList.add('d-none');
    const content = document.getElementById('readingPaneContent');
    content.classList.remove('d-none');
    content.style.opacity = '0.5';

    try {
        const res = await fetch(`<?= base_url('admin/mailbox/detail') ?>/${id}`);
        const json = await res.json();

        if (json.status === 'success') {
            const d = json.data;
            currentEmailData = d;

            document.getElementById('readSubject').innerText = d.subject || '(ไม่มีหัวข้อ)';
            document.getElementById('readSenderName').innerText = d.sender_name || d.sender_email;
            document.getElementById('readSenderEmail').innerText = `<${d.sender_email}>`;
            document.getElementById('readRecipient').innerText = d.recipient_email;
            document.getElementById('readReceivedAt').innerText = d.received_at_fmt;
            document.getElementById('readAvatar').innerText = (d.sender_name || d.sender_email).substring(0, 2).toUpperCase();

            // Reply link
            document.getElementById('btnReplyMail').href = `mailto:${d.sender_email}?subject=Re: ${encodeURIComponent(d.subject || '')}`;

            // Body
            if (d.body_html) {
                document.getElementById('readBodyHtml').innerHTML = d.body_html;
            } else if (d.body_plain) {
                document.getElementById('readBodyHtml').innerHTML = escapeHtml(d.body_plain).replace(/\n/g, '<br>');
            } else {
                document.getElementById('readBodyHtml').innerHTML = '<p class="text-muted">(ไม่มีเนื้อหาข้อความ)</p>';
            }

            // Attachments
            const attSection = document.getElementById('readAttachmentsSection');
            const attList = document.getElementById('readAttachmentsList');
            if (d.attachments && d.attachments.length > 0) {
                attSection.classList.remove('d-none');
                attList.innerHTML = d.attachments.map(a => `
                    <div class="p-2 px-3 rounded-pill bg-light border d-flex align-items-center gap-2 small">
                        <i class="fa-solid fa-file-arrow-down text-primary"></i>
                        <span class="fw-bold">${escapeHtml(a.name)}</span>
                        <span class="text-muted">(${(a.size / 1024).toFixed(1)} KB)</span>
                    </div>
                `).join('');
            } else {
                attSection.classList.add('d-none');
            }

            content.style.opacity = '1';
        }
    } catch (e) {
        console.error(e);
        alert('เกิดข้อผิดพลาดในการโหลดอีเมล: ' + e.message);
        content.style.opacity = '1';
    }
}

async function syncMailboxNow() {
    const btn = document.getElementById('btnSyncMail');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>กำลังดึงอีเมล...';

    const formData = new FormData();
    if (activeAccount !== 'all') {
        formData.append('account', activeAccount);
    }

    try {
        const res = await fetch(`<?= base_url('admin/mailbox/sync') ?>`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        alert(json.message);
        location.reload();
    } catch (e) {
        console.error(e);
        alert('เกิดข้อผิดพลาดในการซิงค์ข้อมูล');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-rotate me-1"></i>ซิงค์อีเมลใหม่';
    }
}

async function toggleStarEmail(e, id) {
    e.stopPropagation();
    try {
        const res = await fetch(`<?= base_url('admin/mailbox/toggle-star') ?>/${id}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (json.status === 'success') {
            const btn = e.target.closest('.btn-star');
            if (json.is_starred) {
                btn.classList.add('starred');
            } else {
                btn.classList.remove('starred');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

async function deleteCurrentEmail() {
    if (!currentEmailId) return;
    if (!confirm('คุณต้องการย้ายอีเมลฉบับนี้ไปถังขยะหรือไม่?')) return;

    try {
        const res = await fetch(`<?= base_url('admin/mailbox/delete') ?>/${currentEmailId}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (json.status === 'success') {
            alert(json.message);
            location.reload();
        }
    } catch (e) {
        console.error(e);
        alert('เกิดข้อผิดพลาด');
    }
}

async function convertCurrentEmailToRequest() {
    if (!currentEmailId) return;
    const btn = document.getElementById('btnConvertRequest');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>กำลังแปลง...';

    try {
        const res = await fetch(`<?= base_url('admin/mailbox/convert') ?>/${currentEmailId}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (json.status === 'success') {
            alert(json.message);
        } else {
            alert(json.message || 'แปลงคำร้องไม่สำเร็จ');
        }
    } catch (e) {
        console.error(e);
        alert('เกิดข้อผิดพลาดในการแปลงข้อมูล');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-file-circle-plus me-1"></i>แปลงเป็นเรื่องร้องเรียน/คำร้อง';
    }
}
</script>
<?= $this->endSection() ?>

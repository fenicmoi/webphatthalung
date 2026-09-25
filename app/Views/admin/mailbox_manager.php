<?php
$emails        = $emails ?? [];
$pager         = $pager ?? null;
$folder        = $folder ?? 'inbox';
$search        = $search ?? '';
$account       = $account ?? 'all';
$accounts      = $accounts ?? [];
$accountCounts = $accountCounts ?? [];
$counts        = $counts ?? ['inbox' => 0, 'unread' => 0, 'official' => 0, 'citizen' => 0, 'starred' => 0, 'trash' => 0];

$activeAccountName = 'ทุกกล่องจดหมาย (All Accounts)';
if ($account !== 'all' && isset($accounts[$account])) {
    $activeAccountName = $accounts[$account]['label'] . ' (' . $account . ')';
}
?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="pb-4">
    <!-- 1. Majestic Hero Cockpit Banner -->
    <div class="dashboard-hero d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4 mb-4">
        <div class="d-flex align-items-center gap-3.5 z-1">
            <div style="width: 60px; height: 60px; border-radius: 18px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.25); box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                <i class="fa-solid fa-inbox" style="font-size: 1.85rem; color: #34d399;"></i>
            </div>
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h1 class="h2 text-white fw-bold mb-0" style="font-size: 1.75rem; letter-spacing: -0.01em;">
                        กล่องจดหมายกลางภาครัฐ (MOI Mailbox Hub)
                    </h1>
                    <span class="badge rounded-pill px-2.5 py-1" style="background: rgba(16, 185, 129, 0.25); border: 1px solid rgba(52, 211, 153, 0.35); color: #6ee7b7; font-size: 0.85rem;">
                        <i class="fa-solid fa-signal me-1"></i>SSL IMAP 993 Active
                    </span>
                </div>
                <p class="mb-0 text-white-50 d-flex flex-wrap align-items-center gap-2" style="font-size: 1rem;">
                    <span>ศูนย์รวมรับ-ส่งอีเมลทางการของจังหวัดพัทลุง</span>
                    <span>&bull;</span>
                    <span style="color: #6ee7b7;">เชื่อมต่อสด 2 บัญชีหลัก (อีเมลกลาง & งานสารบรรณ)</span>
                </p>
            </div>
        </div>

        <!-- Right Quick Actions -->
        <div class="d-flex flex-wrap align-items-center gap-2 z-1">
            <button type="button" class="btn rounded-pill px-3.5 py-2 fw-bold d-flex align-items-center gap-2 text-decoration-none shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; border: 1px solid rgba(255,255,255,0.2);" onclick="syncMailboxNow()" id="btnSyncMail">
                <i class="fa-solid fa-rotate"></i>
                <span>ซิงค์ดึงอีเมลใหม่</span>
            </button>
            <button type="button" class="btn rounded-pill px-3 py-2 fw-bold text-white shadow-sm d-flex align-items-center gap-1.5" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);" data-bs-toggle="modal" data-bs-target="#mailboxConfigModal">
                <i class="fa-solid fa-gear"></i>
                <span>ตั้งค่าเซิร์ฟเวอร์</span>
            </button>
            <a href="https://mail.moi.go.th" target="_blank" class="btn rounded-pill px-3 py-2 fw-bold text-white shadow-sm d-flex align-items-center gap-1.5" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);" title="เปิด Webmail กระทรวงมหาดไทย">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span class="d-none d-sm-inline">Webmail MOI</span>
            </a>
        </div>
    </div>

    <!-- 2. Account Switcher Bar -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 bg-white p-2.5 rounded-4 border shadow-xs">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted fw-bold small me-1 ps-2"><i class="fa-solid fa-filter text-primary me-1"></i>เลือกบัญชี:</span>
            
            <a href="<?= base_url('admin/mailbox?account=all&folder=' . esc($folder)) ?>" 
               class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 text-decoration-none <?= $account === 'all' ? 'btn-primary shadow-xs' : 'btn-light border text-secondary' ?>" style="font-size: 0.92rem;">
                <i class="fa-solid fa-envelopes-bulk"></i>
                <span>ทุกกล่องจดหมาย (All Accounts)</span>
            </a>

            <?php foreach ($accounts as $emailKey => $acc): 
                $accUnread = $accountCounts[$emailKey]['unread'] ?? 0;
                $isActive = $account === $emailKey;
                $isSaraban = ($acc['key'] ?? '') === 'saraban';
            ?>
                <a href="<?= base_url('admin/mailbox?account=' . esc($emailKey) . '&folder=' . esc($folder)) ?>" 
                   class="btn btn-sm rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 text-decoration-none <?= $isActive ? ($isSaraban ? 'btn-success text-white shadow-xs' : 'btn-primary text-white shadow-xs') : 'btn-light border text-secondary' ?>" style="font-size: 0.92rem;">
                    <i class="fa-solid <?= $isSaraban ? 'fa-scroll text-success' : 'fa-building text-primary' ?> <?= $isActive ? 'text-white' : '' ?>"></i>
                    <span><?= esc($acc['label']) ?></span>
                    <small class="opacity-75">(<?= esc($emailKey) ?>)</small>
                    <?php if ($accUnread > 0): ?>
                        <span class="badge bg-danger rounded-pill px-1.5 ms-0.5"><?= $accUnread ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="text-muted small pe-2 d-none d-lg-block">
            <span>แสดงผล: <strong class="text-dark"><?= esc($activeAccountName) ?></strong></span>
        </div>
    </div>

    <!-- 3. Webmail 3-Pane Ergonomic Layout -->
    <div class="row g-3">
        <!-- A. Left Folders Navigation -->
        <div class="col-lg-3 col-xl-2">
            <div class="card border-0 rounded-4 shadow-xs p-3 bg-white h-100 d-flex flex-column">
                <div class="text-uppercase text-muted fw-bold px-2 mb-2" style="font-size: 0.78rem; letter-spacing: 0.05em;">
                    โฟลเดอร์จดหมาย
                </div>

                <div class="d-flex flex-column gap-1.5">
                    <!-- Inbox -->
                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=inbox') ?>" class="mailbox-folder-link <?= $folder === 'inbox' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="folder-icon-box" style="background: rgba(37, 99, 235, 0.12); color: #2563eb;">
                                <i class="fa-solid fa-inbox"></i>
                            </div>
                            <span class="fw-semibold">กล่องขาเข้า</span>
                        </div>
                        <?php if (!empty($counts['unread'])): ?>
                            <span class="badge bg-danger rounded-pill px-2"><?= $counts['unread'] ?></span>
                        <?php else: ?>
                            <small class="text-muted fw-bold"><?= $counts['inbox'] ?></small>
                        <?php endif; ?>
                    </a>

                    <!-- Official Letters -->
                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=official') ?>" class="mailbox-folder-link <?= $folder === 'official' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="folder-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <span class="fw-semibold">หนังสือราชการ</span>
                        </div>
                        <small class="text-muted fw-bold"><?= $counts['official'] ?></small>
                    </a>

                    <!-- Citizen Mail -->
                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=citizen') ?>" class="mailbox-folder-link <?= $folder === 'citizen' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="folder-icon-box" style="background: rgba(6, 182, 212, 0.12); color: #0891b2;">
                                <i class="fa-solid fa-user-tag"></i>
                            </div>
                            <span class="fw-semibold">จากประชาชน</span>
                        </div>
                        <small class="text-muted fw-bold"><?= $counts['citizen'] ?></small>
                    </a>

                    <!-- Starred -->
                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=starred') ?>" class="mailbox-folder-link <?= $folder === 'starred' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="folder-icon-box" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <span class="fw-semibold">เรื่องสำคัญ</span>
                        </div>
                        <small class="text-muted fw-bold"><?= $counts['starred'] ?></small>
                    </a>

                    <hr class="my-2 opacity-10">

                    <!-- Trash -->
                    <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=trash') ?>" class="mailbox-folder-link text-muted <?= $folder === 'trash' ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="folder-icon-box" style="background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                                <i class="fa-solid fa-trash-can"></i>
                            </div>
                            <span class="fw-semibold">ถังขยะ</span>
                        </div>
                        <small class="text-muted fw-bold"><?= $counts['trash'] ?></small>
                    </a>
                </div>

                <!-- Server Info Card at Bottom -->
                <div class="mt-auto pt-4">
                    <div class="p-3 rounded-4 bg-light border">
                        <div class="d-flex align-items-center gap-2 fw-bold text-dark mb-1.5" style="font-size: 0.92rem;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                            <span>IMAP Mail Server</span>
                        </div>
                        <div class="text-truncate fw-semibold text-secondary small">mail.moi.go.th</div>
                        <div class="small text-muted mt-1">SSL Port 993 &bull; มีการเข้ารหัส</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- B. Middle Email List Pane -->
        <div class="col-lg-4 col-xl-4">
            <div class="card border-0 rounded-4 shadow-xs bg-white h-100 overflow-hidden d-flex flex-column">
                <!-- Search & Filter Header -->
                <div class="p-3 border-bottom bg-light">
                    <form method="GET" action="<?= base_url('admin/mailbox') ?>">
                        <input type="hidden" name="folder" value="<?= esc($folder) ?>">
                        <input type="hidden" name="account" value="<?= esc($account) ?>">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="q" value="<?= esc($search) ?>" class="form-control border-start-0" placeholder="ค้นหาอีเมล, ผู้ส่ง, หรือหัวข้อ..." style="font-size: 0.95rem;">
                            <?php if (!empty($search)): ?>
                                <a href="<?= base_url('admin/mailbox?account=' . esc($account) . '&folder=' . esc($folder)) ?>" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark"></i></a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- Email List Scrollable Container -->
                <div class="email-list-pane flex-grow-1 overflow-auto" style="max-height: 72vh;">
                    <?php if (empty($emails)): ?>
                        <div class="text-center py-5 my-auto text-muted px-3">
                            <div style="width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: #94a3b8;">
                                <i class="fa-regular fa-envelope-open" style="font-size: 1.8rem;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">ไม่มีจดหมายในโฟลเดอร์นี้</h6>
                            <p class="small text-muted mb-0">หากต้องการตรวจสอบอีเมลใหม่ล่าสุด ให้กดปุ่ม "ซิงค์ดึงอีเมลใหม่" ด้านบน</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($emails as $em): 
                            $isUnread = empty($em['is_read']);
                            $isSaraban = str_contains($em['recipient_email'] ?? '', 'saraban');
                            $senderInitial = mb_substr($em['sender_name'] ?: $em['sender_email'], 0, 1, 'UTF-8');
                        ?>
                            <div class="email-list-item <?= $isUnread ? 'unread' : '' ?>" id="email-item-<?= $em['id'] ?>" onclick="viewEmailDetail(<?= $em['id'] ?>)">
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-1.5">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <button type="button" class="btn-star <?= !empty($em['is_starred']) ? 'starred' : '' ?>" onclick="toggleStarEmail(event, <?= $em['id'] ?>)" title="ทำเครื่องหมายสำคัญ">
                                            <i class="fa-solid fa-star"></i>
                                        </button>
                                        <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 28px; height: 28px; font-size: 0.85rem; background: <?= $isSaraban ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #3b82f6, #1d4ed8)' ?>;">
                                            <?= esc($senderInitial) ?>
                                        </div>
                                        <span class="email-sender text-truncate fw-bold <?= $isUnread ? 'text-dark' : 'text-secondary' ?>" style="font-size: 0.98rem;">
                                            <?= esc($em['sender_name'] ?: $em['sender_email']) ?>
                                        </span>
                                    </div>
                                    <small class="email-time text-muted flex-shrink-0" style="font-size: 0.85rem;">
                                        <?= date('d M', strtotime($em['received_at'])) ?>
                                    </small>
                                </div>

                                <div class="email-subject text-truncate mb-1 <?= $isUnread ? 'fw-bold text-dark' : 'text-secondary' ?>" style="font-size: 1.02rem;">
                                    <?= esc($em['subject'] ?: '(ไม่มีหัวข้อ)') ?>
                                </div>

                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <span class="badge rounded-pill <?= $isSaraban ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' ?>" style="font-size: 0.78rem;">
                                            <?= $isSaraban ? 'สารบรรณ' : 'อีเมลกลาง' ?>
                                        </span>
                                        <span class="email-snippet text-muted text-truncate" style="font-size: 0.88rem;">
                                            <?= esc(mb_substr($em['body_plain'] ?: strip_tags($em['body_html']), 0, 45)) ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($em['has_attachment'])): ?>
                                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5" title="มีไฟล์แนบ">
                                            <i class="fa-solid fa-paperclip me-1"></i>ไฟล์แนบ
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!empty($pager)): ?>
                    <div class="p-2.5 border-top d-flex justify-content-center bg-light">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- C. Right Reading Pane -->
        <div class="col-lg-5 col-xl-6">
            <div class="card border-0 rounded-4 shadow-xs bg-white h-100 p-4 d-flex flex-column" id="readingPaneContainer">
                <!-- Empty Placeholder -->
                <div id="readingPanePlaceholder" class="text-center py-5 my-auto text-muted">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: #f0fdf4; color: #047857; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                        <i class="fa-regular fa-envelope-open" style="font-size: 2.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">เลือกอีเมลจากรายการด้านซ้ายเพื่อเปิดอ่าน</h5>
                    <p class="text-muted mb-0" style="font-size: 0.95rem;">ระบบจะแสดงเนื้อหาฉบับเต็ม ไฟล์แนบ และตัวเลือกในการส่งต่อ/ตอบกลับ</p>
                </div>

                <!-- Active Email Reader Content -->
                <div id="readingPaneContent" class="d-none flex-grow-1 d-flex flex-column">
                    <!-- Action Toolbar -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-3">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5 shadow-xs" onclick="convertCurrentEmailToRequest()" id="btnConvertRequest">
                                <i class="fa-solid fa-file-circle-plus"></i>
                                <span>แปลงเป็นเรื่องร้องเรียน/คำร้อง</span>
                            </button>
                            <a id="btnReplyMail" href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-bold d-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-reply"></i>
                                <span>ตอบกลับ</span>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1.5" onclick="window.print()" title="พิมพ์อีเมล">
                                <i class="fa-solid fa-print"></i>
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="deleteCurrentEmail()" title="ย้ายไปถังขยะ">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email Header Info Card -->
                    <div class="mb-3">
                        <h3 class="fw-bold text-dark mb-3" id="readSubject" style="font-size: 1.35rem; line-height: 1.4;"></h3>
                        <div class="p-3 rounded-4 bg-light border">
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle text-white fw-bold d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px; background: linear-gradient(135deg, #059669, #0284c7); font-size: 1.1rem;" id="readAvatar">
                                        MOI
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 1.05rem;" id="readSenderName"></div>
                                        <div class="text-muted small" id="readSenderEmail"></div>
                                    </div>
                                </div>
                                <div class="text-sm-end small text-muted">
                                    <div>กล่องปลายทาง: <span class="badge bg-secondary-subtle text-secondary-emphasis border rounded-pill px-2" id="readRecipient"></span></div>
                                    <div class="mt-1" id="readReceivedAt"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Box (If any) -->
                    <div id="readAttachmentsSection" class="mb-3 d-none">
                        <div class="small fw-bold text-dark mb-2"><i class="fa-solid fa-paperclip text-primary me-1"></i>เอกสาร / ไฟล์แนบที่มากับอีเมล:</div>
                        <div class="d-flex flex-wrap gap-2" id="readAttachmentsList"></div>
                    </div>

                    <!-- Email Body Viewer -->
                    <div class="email-body-content flex-grow-1 p-3.5 rounded-4 border bg-white overflow-auto shadow-2xs" id="readBodyHtml" style="min-height: 260px; max-height: 50vh; line-height: 1.75; font-size: 1.02rem;">
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
            <div class="modal-header border-bottom px-4 py-3" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%); color: white;">
                <div class="d-flex align-items-center gap-2.5">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                        <i class="fa-solid fa-server"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="configModalLabel">การตั้งค่า Mail Server (MOI IMAP)</h5>
                        <small style="color: #a7f3d0;">ระบบเชื่อมต่อเซิร์ฟเวอร์กระทรวงมหาดไทย</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-25 mb-3">
                    <div class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-1.5"></i> บัญชีทางการที่เชื่อมต่อสดในระบบ:</div>
                    <ul class="mb-0 small ps-3 text-secondary" style="line-height: 1.6;">
                        <li><strong>อีเมลกลางจังหวัด:</strong> phatthalung@moi.go.th (SSL Port 993)</li>
                        <li><strong>งานสารบรรณจังหวัด:</strong> saraban_phatthalung@moi.go.th (SSL Port 993)</li>
                    </ul>
                </div>
                <div class="p-3 rounded-4 bg-white border">
                    <div class="d-flex align-items-center gap-2 text-dark fw-bold mb-1">
                        <i class="fa-solid fa-shield-halved text-primary"></i> การรักษาความปลอดภัย
                    </div>
                    <p class="text-muted small mb-0">รหัสผ่านและค่าการเชื่อมต่อถูกเข้ารหัสและบันทึกไว้อย่างปลอดภัยในระบบเบื้องหลังเรียบร้อยแล้ว ไม่จำเป็นต้องแก้ไข</p>
                </div>
            </div>
            <div class="modal-footer border-top bg-white p-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<style>
.mailbox-folder-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.65rem 0.95rem;
    border-radius: 14px;
    color: #334155;
    text-decoration: none !important;
    font-size: 0.98rem;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.mailbox-folder-link:hover {
    background: #f1f5f9;
    color: #0f172a;
    transform: translateX(3px);
}

.mailbox-folder-link.active {
    background: #ecfdf5;
    color: #047857;
    font-weight: 700;
    box-shadow: 0 2px 6px rgba(4, 120, 87, 0.08);
}

.folder-icon-box {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
}

.email-list-item {
    padding: 1rem 1.15rem;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.email-list-item:hover {
    background: #f8fafc;
    border-left-color: #cbd5e1;
}

.email-list-item.selected {
    background: #eff6ff !important;
    border-left-color: #2563eb !important;
}

.email-list-item.unread {
    background: #f0fdf4;
    border-left-color: #10b981;
}

.btn-star {
    background: transparent;
    border: none;
    padding: 0;
    color: #cbd5e1;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.btn-star.starred, .btn-star:hover {
    color: #f59e0b;
    transform: scale(1.15);
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
        if (typeof App !== 'undefined' && App.toast) {
            App.toast('เกิดข้อผิดพลาดในการโหลดอีเมล', 'error');
        }
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
        if (typeof App !== 'undefined' && App.toast) {
            App.toast(json.message || 'ซิงค์ข้อมูลสำเร็จ', 'success');
        }
        setTimeout(() => location.reload(), 800);
    } catch (e) {
        console.error(e);
        if (typeof App !== 'undefined' && App.toast) {
            App.toast('เกิดข้อผิดพลาดในการซิงค์ข้อมูล', 'error');
        }
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-rotate me-1"></i>ซิงค์ดึงอีเมลใหม่';
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
            if (typeof App !== 'undefined' && App.toast) {
                App.toast(json.message, 'success');
            }
            setTimeout(() => location.reload(), 600);
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
            if (typeof App !== 'undefined' && App.toast) {
                App.toast(json.message || 'แปลงเป็นเรื่องร้องเรียนเรียบร้อยแล้ว', 'success');
            } else {
                alert(json.message);
            }
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

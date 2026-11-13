<?php
// =========================================================================
// ศูนย์รวมระบบสารสนเทศและบริการดิจิทัลภาครัฐ (Digital Services & e-Government Hub)
// จังหวัดพัทลุง - Clean & Premium Minimalist Design (สะอาดตา โปร่งโล่ง หรูหรา)
// =========================================================================
$digitalServices = function_exists('get_digital_services') ? get_digital_services() : [];

// กำหนดโทนสีพาสเทลสุภาพแบบ Soft-tint เพื่อให้ภาพรวมคลีน สบายตา
$pastelTones = [
    '#2563eb' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'dark_text' => '#93c5fd'],
    '#059669' => ['bg' => '#ecfdf5', 'text' => '#047857', 'dark_text' => '#6ee7b7'],
    '#d97706' => ['bg' => '#fffbeb', 'text' => '#b45309', 'dark_text' => '#fcd34d'],
    '#7c3aed' => ['bg' => '#f5f3ff', 'text' => '#6d28d9', 'dark_text' => '#c4b5fd'],
    '#ea580c' => ['bg' => '#fff7ed', 'text' => '#c2410c', 'dark_text' => '#fdba74'],
    '#0891b2' => ['bg' => '#ecfeff', 'text' => '#0e7490', 'dark_text' => '#67e8f9'],
];
?>

<style>
/* ==========================================================================
   Clean & Premium Minimalist Digital Services Hub
   ========================================================================== */
.digital-services-section {
    position: relative;
    margin-bottom: 2.75rem;
}

/* 1. Seamless Frosted Canvas (ไร้เส้นขอบเขียวหนา ให้ความรู้สึกโปร่งโล่งสบายตา) */
.digital-services-box {
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 26px;
    border: 1px solid rgba(226, 232, 240, 0.7);
    padding: 1.5rem 1.85rem;
    position: relative;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);
}

[data-theme="dark"] .digital-services-box {
    background: linear-gradient(180deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.75) 100%);
    border-color: rgba(255, 255, 255, 0.07);
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.3);
}

/* 2. Minimalist White Card (การ์ดขาวคลีน ไร้แถบสีหนา ขอบมนบางเบา) */
.service-card-compact {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid rgba(226, 232, 240, 0.85);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 13px;
    text-decoration: none !important;
    transition: all 0.24s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
    cursor: pointer;
    height: 100%;
    min-height: 72px;
}

.service-card-compact:hover {
    transform: translateY(-3px);
    border-color: rgba(16, 185, 129, 0.5);
    box-shadow: 0 10px 24px -4px rgba(4, 120, 87, 0.12), 0 0 0 1px rgba(16, 185, 129, 0.25);
    background: #ffffff;
}

[data-theme="dark"] .service-card-compact {
    background: rgba(30, 41, 59, 0.85);
    border-color: rgba(255, 255, 255, 0.08);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

[data-theme="dark"] .service-card-compact:hover {
    background: rgba(30, 41, 59, 1);
    border-color: #34d399;
    box-shadow: 0 10px 24px -4px rgba(0, 0, 0, 0.45);
}

/* 3. Soft-Tint Pastel Squircle Icon (ไอคอนโทนพาสเทลสุภาพ ไม่ฉูดฉาด) */
.service-icon-compact {
    width: 42px;
    height: 42px;
    border-radius: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
    transition: transform 0.22s ease;
}

.service-card-compact:hover .service-icon-compact {
    transform: scale(1.08);
}

[data-theme="dark"] .service-icon-compact {
    background: rgba(255, 255, 255, 0.07) !important;
}

/* 4. Minimalist Action Arrow */
.service-arrow-indicator {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 0.8rem;
    flex-shrink: 0;
    background: rgba(241, 245, 249, 0.7);
    transition: all 0.22s ease;
}

.service-card-compact:hover .service-arrow-indicator {
    background: #ecfdf5;
    color: #047857 !important;
    transform: translateX(3px);
}

[data-theme="dark"] .service-arrow-indicator {
    background: rgba(255, 255, 255, 0.06);
    color: #64748b;
}

[data-theme="dark"] .service-card-compact:hover .service-arrow-indicator {
    background: rgba(16, 185, 129, 0.18);
    color: #34d399 !important;
}

/* 5. Subtle "กำลังพัฒนา" Badge (แสดงเฉพาะระบบที่กำลังพัฒนา เพื่อลดความรก) */
.badge-dev-subtle {
    font-size: 0.68rem;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 50px;
    background: #fffbeb;
    color: #b45309;
    border: 1px solid rgba(245, 158, 11, 0.25);
    display: inline-flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
}

[data-theme="dark"] .badge-dev-subtle {
    background: rgba(245, 158, 11, 0.15);
    color: #fbbf24;
    border-color: rgba(245, 158, 11, 0.3);
}

.pulse-dot-amber {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #f59e0b;
    display: inline-block;
}

/* 6. Filter Micro Pills (Clean Ghost Style) */
.service-cat-tab-sm {
    border-radius: 50px;
    padding: 5px 14px;
    font-size: 0.80rem;
    font-weight: 600;
    border: 1px solid rgba(203, 213, 225, 0.75);
    background: #ffffff;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.service-cat-tab-sm:hover {
    border-color: #10b981;
    color: #047857;
    background: #ecfdf5;
}

.service-cat-tab-sm.active {
    background: linear-gradient(135deg, #047857 0%, #065f46 100%);
    color: #ffffff !important;
    border-color: #047857 !important;
    box-shadow: 0 2px 8px rgba(4, 120, 87, 0.25);
}

[data-theme="dark"] .service-cat-tab-sm {
    background: rgba(30, 41, 59, 0.85);
    border-color: rgba(255, 255, 255, 0.1);
    color: #cbd5e1;
}

[data-theme="dark"] .service-cat-tab-sm:hover {
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
}

[data-theme="dark"] .service-cat-tab-sm.active {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-color: #10b981 !important;
}
</style>

<section class="digital-services-section" id="digitalServicesHub">
    <div class="digital-services-box">
        
        <!-- Header Row (Clean & Dignified) -->
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2.5 mb-3.5">
            <div class="d-flex align-items-center gap-2.5">
                <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 shadow-xs" 
                     style="width: 36px; height: 36px; background: linear-gradient(135deg, #059669 0%, #047857 100%); font-size: 1.05rem;">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="fw-bold mb-0" style="font-size: 1.18rem; color: var(--text-primary); letter-spacing: -0.2px;">
                            ระบบสารสนเทศและการบริการ
                        </h3>
                        <span class="badge rounded-pill text-success d-none d-sm-inline-block" 
                              style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.25); font-size: 0.70rem; font-weight: 600;">
                            Digital Services
                        </span>
                    </div>
                    <small class="text-muted" style="font-size: 0.78rem;">
                        ศูนย์กลางระบบสารสนเทศและบริการดิจิทัลภาครัฐ จังหวัดพัทลุง
                    </small>
                </div>
            </div>

            <!-- Filter Micro Pills -->
            <div class="d-flex flex-wrap align-items-center gap-1.5" id="serviceCatFilterGroup">
                <button type="button" class="service-cat-tab-sm active" onclick="filterServiceCards('all', this)">
                    <i class="fa-solid fa-grid-2"></i> ทั้งหมด (<?= count($digitalServices) ?>)
                </button>
                <button type="button" class="service-cat-tab-sm" onclick="filterServiceCards('citizen', this)">
                    <i class="fa-solid fa-users text-success"></i> บริการประชาชน
                </button>
                <button type="button" class="service-cat-tab-sm" onclick="filterServiceCards('officer', this)">
                    <i class="fa-solid fa-building-user text-primary"></i> สำหรับเจ้าหน้าที่
                </button>
            </div>
        </div>

        <!-- Cards Grid (3 Columns x 2 Rows with comfortable breathing room) -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2.5" id="serviceCardsContainer">
            <?php foreach ($digitalServices as $srv): 
                $themeColor = !empty($srv['theme_color']) ? $srv['theme_color'] : '#059669';
                $pastel = $pastelTones[$themeColor] ?? ['bg' => '#ecfdf5', 'text' => '#047857', 'dark_text' => '#6ee7b7'];
                $isActive = ($srv['status'] ?? 'active') === 'active';
                $cat = $srv['category'] ?? 'general';
            ?>
                <div class="col service-card-col" data-category="<?= esc($cat) ?>">
                    <?php if ($isActive): ?>
                        <a href="<?= esc($srv['url']) ?>" 
                           target="<?= esc($srv['target'] ?? '_blank') ?>" 
                           rel="noopener noreferrer" 
                           class="service-card-compact" 
                           title="<?= esc($srv['title']) ?> (คลิกเพื่อเข้าสู่ระบบ)">
                    <?php else: ?>
                        <div class="service-card-compact" 
                             onclick="showDevServiceNotice('<?= esc(addslashes($srv['title'])) ?>')"
                             title="<?= esc($srv['title']) ?> (อยู่ระหว่างพัฒนา)">
                    <?php endif; ?>

                        <!-- Left: Soft Pastel Squircle Icon -->
                        <div class="service-icon-compact" 
                             style="background: <?= esc($pastel['bg']) ?>; color: <?= esc($pastel['text']) ?>;">
                            <i class="<?= esc($srv['icon'] ?? 'fa-solid fa-laptop') ?>"></i>
                        </div>

                        <!-- Middle: Title & Subtitle (Clean & Legible) -->
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center justify-content-between gap-1 mb-0.5">
                                <h6 class="fw-bold mb-0 text-truncate" style="font-size: 0.92rem; color: var(--text-primary); line-height: 1.25;">
                                    <?= esc($srv['title']) ?>
                                </h6>
                                <?php if (!$isActive): ?>
                                    <span class="badge-dev-subtle">
                                        <span class="pulse-dot-amber"></span> กำลังพัฒนา
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted mb-0 text-truncate" style="font-size: 0.75rem; line-height: 1.25;">
                                <?= esc($srv['subtitle']) ?>
                            </p>
                        </div>

                        <!-- Right: Minimalist Action Arrow Indicator -->
                        <div class="service-arrow-indicator">
                            <?php if ($isActive): ?>
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            <?php endif; ?>
                        </div>

                    <?php if ($isActive): ?>
                        </a>
                    <?php else: ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Modal แจ้งเตือนระบบอยู่ระหว่างพัฒนา -->
<div class="modal fade" id="devServiceNoticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-xl" style="border-radius: 24px; overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" 
                     style="width: 58px; height: 58px; background: #fffbeb; border: 2px solid #fde68a;">
                    <i class="fa-solid fa-screwdriver-wrench text-warning fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2" id="devModalTitle">ระบบอยู่ระหว่างการพัฒนา</h5>
                <p class="text-muted small mb-4" id="devModalDesc" style="font-size: 0.84rem; line-height: 1.5;">
                    ระบบนี้อยู่ระหว่างการจัดเตรียมโครงสร้างพื้นฐานและเชื่อมโยงฐานข้อมูลภาครัฐ จะเปิดให้บริการเร็วๆ นี้ครับ
                </p>
                <button type="button" class="btn btn-emerald-primary w-100 rounded-pill py-2 fw-semibold" data-bs-dismiss="modal">
                    รับทราบ
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function filterServiceCards(cat, btn) {
    document.querySelectorAll('.service-cat-tab-sm').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');

    const cards = document.querySelectorAll('.service-card-col');
    cards.forEach(card => {
        const itemCat = card.getAttribute('data-category');
        if (cat === 'all' || itemCat === cat || (cat === 'officer' && itemCat === 'general')) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function showDevServiceNotice(serviceName) {
    const titleEl = document.getElementById('devModalTitle');
    const descEl = document.getElementById('devModalDesc');
    if (titleEl) titleEl.innerText = serviceName;
    if (descEl) descEl.innerText = 'ระบบ "' + serviceName + '" อยู่ระหว่างการจัดเตรียมโครงสร้างพื้นฐานและเชื่อมต่อฐานข้อมูลภาครัฐ จะเปิดให้บริการแก่ประชาชนและส่วนราชการในเร็วๆ นี้ครับ';
    
    const modalEl = document.getElementById('devServiceNoticeModal');
    if (modalEl && typeof bootstrap !== 'undefined') {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    } else {
        alert('ระบบ ' + serviceName + ' อยู่ระหว่างการพัฒนาและจะเปิดให้บริการเร็วๆ นี้ครับ');
    }
}
</script>

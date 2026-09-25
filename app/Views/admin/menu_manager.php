<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $s = function_exists('get_site_settings') ? get_site_settings() : []; ?>

<!-- Hero Cockpit Header -->
<div class="glass-card mb-4 p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 28px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(59, 130, 246, 0.10) 50%, rgba(139, 92, 246, 0.08) 100%); border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
    <div class="position-absolute" style="top: -40px; right: -30px; font-size: 15rem; color: rgba(16, 185, 129, 0.04); pointer-events: none; z-index: 0;">
        <i class="fa-solid fa-compass"></i>
    </div>
    
    <div class="position-relative z-1 d-flex flex-wrap justify-content-between align-items-center gap-4">
        <div>
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #059669; font-size: 0.85rem; font-weight: 600;">
                <i class="fa-solid fa-sitemap"></i> Navigation Architecture & Mega-Menu
            </div>
            <h2 class="fw-bold mb-2" style="color: var(--text-primary); letter-spacing: -0.5px;">
                จัดการโครงสร้างเมนูเว็บไซต์ (Navigation & Dropdowns)
            </h2>
            <p class="text-secondary mb-0" style="font-size: 1rem; max-width: 720px;">
                ปรับแต่งลำดับเมนูหลักและเมนูย่อย (Submenu) แบบเรียลไทม์ พร้อมหน้าต่างจำลองการแสดงผลสดเสมือนหน้าเว็บจริง (Live Simulator)
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <button type="button" class="btn btn-outline-danger px-3 py-2 rounded-4 fw-medium d-flex align-items-center gap-2 shadow-sm transition-all" onclick="resetMenuToDefault()" style="border-width: 1.5px;">
                <i class="fa-solid fa-rotate-left"></i> <span>รีเซ็ตค่าเริ่มต้น</span>
            </button>
            <button type="button" class="btn btn-emerald px-4 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow transition-all" id="btnSaveMenu" onclick="saveMenuTree()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border: none; font-size: 1rem;">
                <i class="fa-solid fa-cloud-arrow-up"></i> <span>บันทึกโครงสร้างเมนู</span>
            </button>
        </div>
    </div>

    <!-- Live Counter Stat Chips -->
    <div class="position-relative z-1 row g-3 mt-3 pt-3 border-top" style="border-color: var(--glass-border) !important;">
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-bars text-primary me-1"></i> เมนูหลักทั้งหมด</div>
                <div class="fs-4 fw-bold" id="statMainCount" style="color: var(--text-primary);">0</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-layer-group text-info me-1"></i> เมนูย่อย (Dropdown)</div>
                <div class="fs-4 fw-bold text-info" id="statSubCount">0</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-link text-emerald me-1"></i> ลิงก์ทั้งหมดในระบบ</div>
                <div class="fs-4 fw-bold text-success" id="statTotalLinks">0</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 rounded-4" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                <div class="text-secondary small fw-medium mb-1"><i class="fa-solid fa-circle-check text-warning me-1"></i> สถานะการแสดงผล</div>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">พร้อมใช้งาน</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT COLUMN: INTERACTIVE MENU BUILDER CANVAS -->
    <div class="col-xl-7 col-lg-6">
        <div class="glass-card p-4 mb-4" style="border-radius: 24px; border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow); background: var(--bg-surface);">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom" style="border-color: var(--glass-border) !important;">
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--text-primary);">
                        <i class="fa-solid fa-diagram-project text-emerald me-2"></i>ผังรายการเมนูเว็บไซต์ (Hierarchy Canvas)
                    </h5>
                    <div class="text-secondary small">จัดลำดับโดยการกดปุ่มขึ้น-ลง และกด "+ เมนูย่อย" เพื่อทำดรอปดาวน์</div>
                </div>
                <button type="button" class="btn btn-primary px-3 py-2 rounded-4 fw-bold d-flex align-items-center gap-2 shadow-sm" onclick="addMainMenu()" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-plus-circle"></i> เพิ่มเมนูหลัก
                </button>
            </div>

            <!-- Dynamic Menu Tree Container -->
            <div id="menuTreeContainer" class="d-flex flex-column gap-3">
                <!-- Javascript will reactively inject menu cards here -->
            </div>

            <div class="mt-4 pt-2">
                <button type="button" class="btn btn-outline-primary w-100 py-3 rounded-4 d-flex align-items-center justify-content-center gap-2 border-2 transition-all" style="border-style: dashed !important; background: rgba(59, 130, 246, 0.04); font-size: 1rem; font-weight: 600;" onclick="addMainMenu()">
                    <i class="fa-solid fa-circle-plus fs-5"></i> <span>เพิ่มรายการเมนูหลักด้านบนสุด (Add Main Nav Item)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: LIVE BROWSER SIMULATOR -->
    <div class="col-xl-5 col-lg-6">
        <div class="sticky-top" style="top: 90px; z-index: 10;">
            <!-- Simulated Browser Window -->
            <div class="glass-card rounded-4 overflow-hidden mb-4 shadow-lg" style="border: 1px solid var(--glass-border); background: var(--bg-surface);">
                <!-- Browser Window Top Header -->
                <div class="px-3 py-2.5 d-flex align-items-center justify-content-between border-bottom" style="background: rgba(0,0,0,0.04); border-color: var(--glass-border) !important;">
                    <div class="d-flex align-items-center gap-1.5">
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background: #ff5f56;"></span>
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background: #ffbd2e;"></span>
                        <span class="rounded-circle d-inline-block" style="width: 11px; height: 11px; background: #27c93f;"></span>
                    </div>
                    <div class="px-3 py-1 rounded-pill small text-secondary d-flex align-items-center gap-2" style="background: var(--bg-secondary); border: 1px solid var(--glass-border); font-size: 0.78rem; max-width: 260px;">
                        <i class="fa-solid fa-lock text-success" style="font-size: 0.7rem;"></i>
                        <span class="text-truncate">https://www.phatthalung.go.th</span>
                    </div>
                    <span class="badge bg-emerald-subtle text-emerald rounded-pill px-2.5 py-1" style="font-size: 0.72rem; font-weight: 600;">
                        <i class="fa-solid fa-bolt me-1"></i>Live Simulator
                    </span>
                </div>

                <!-- Simulation Navbar Content -->
                <div class="p-3" style="background: var(--bg-secondary);">
                    <!-- Brand Banner inside Simulator -->
                    <div class="p-3 rounded-4 mb-3 d-flex align-items-center justify-content-between" style="background: var(--glass-bg); border: 1px solid var(--glass-border);">
                        <div class="d-flex align-items-center gap-2 fw-bold" style="color: var(--text-primary); font-size: 0.95rem;">
                            <?php $logo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                            <?php if(!empty($logo)): ?>
                                <img src="<?= htmlspecialchars($logo) ?>" style="height: 30px; width: auto;" alt="Logo">
                            <?php else: ?>
                                <i class="fa-solid fa-building-flag text-danger fs-5"></i>
                            <?php endif; ?>
                            <span class="text-truncate" style="max-width: 200px;"><?= htmlspecialchars($s['site_title_th'] ?? 'จังหวัดพัทลุง') ?></span>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">Navbar Demo</span>
                    </div>

                    <!-- Interactive Simulated Menu Pills -->
                    <div class="p-2.5 rounded-4 mb-3" style="background: var(--glass-bg); border: 1px solid var(--glass-border); min-height: 90px;">
                        <div class="small text-secondary mb-2 px-1" style="font-size: 0.78rem;">
                            <i class="fa-regular fa-eye me-1"></i> เมนูที่ปรากฏบนหน้าบ้าน (คลิกเพื่อทดสอบ Dropdown):
                        </div>
                        <div id="simulatedNavbar" class="d-flex flex-wrap align-items-center gap-2">
                            <!-- Javascript will inject simulated pills & dropdowns -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pro Tips Card -->
            <div class="glass-card p-4 rounded-4" style="border: 1px solid var(--glass-border); background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%);">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle p-2.5 d-flex align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); width: 40px; height: 40px; flex-shrink: 0;">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--text-primary); font-size: 0.95rem;">แนวทางจัดเมนูเว็บไซต์ราชการที่ดี:</h6>
                        <ul class="mb-0 ps-3 mt-2 small text-secondary" style="line-height: 1.6;">
                            <li><strong>ชื่อเมนู:</strong> ควรกระชับ ชัดเจน ไม่เกิน 2-4 คำ เพื่อให้แสดงผลใน 1 บรรทัดบนหน้าจอมือถือและแท็บเล็ต</li>
                            <li><strong>เมนูย่อย (Submenu):</strong> จัดกลุ่มบริการประชาชนหรือข้อมูลเชิงลึกไว้ใต้เมนูหลักที่เกี่ยวข้อง</li>
                            <li><strong>การใส่ลิงก์:</strong> สามารถใส่ Anchor `#services`, `#news` เพื่อเลื่อนหน้า หรือใส่ URL เต็ม เช่น `https://...` ได้ครับ</li>
                            <li><strong>ไอคอน FontAwesome:</strong> กรอกชื่อคลาส เช่น <code>fa-solid fa-house</code>, <code>fa-solid fa-newspaper</code></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ข้อมูลเมนูตั้งต้นจาก PHP
let menuTree = <?= json_encode($currentMenu ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

// 1. ฟังก์ชันคำนวณและอัปเดตสถิติ (Stats Update)
function updateMenuStats() {
    const mainCount = menuTree ? menuTree.length : 0;
    let subCount = 0;
    if (menuTree && menuTree.length > 0) {
        menuTree.forEach(item => {
            if (item.children && Array.isArray(item.children)) {
                subCount += item.children.length;
            }
        });
    }
    const statMain = document.getElementById('statMainCount');
    const statSub = document.getElementById('statSubCount');
    const statTotal = document.getElementById('statTotalLinks');
    if (statMain) statMain.textContent = mainCount;
    if (statSub) statSub.textContent = subCount;
    if (statTotal) statTotal.textContent = mainCount + subCount;
}

// 2. ฟังก์ชันวาดเมนู (Reactive Render)
function renderMenuBuilder() {
    const container = document.getElementById('menuTreeContainer');
    const simNav = document.getElementById('simulatedNavbar');
    if (!container || !simNav) return;

    container.innerHTML = '';
    simNav.innerHTML = '';

    updateMenuStats();

    if (!menuTree || menuTree.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5 text-secondary">
                <i class="fa-regular fa-folder-open fs-1 mb-2 opacity-50"></i>
                <p class="mb-0">ยังไม่มีข้อมูลเมนู คลิกปุ่ม "+ เพิ่มเมนูหลัก" เพื่อเริ่มต้นได้เลยครับ</p>
            </div>
        `;
        simNav.innerHTML = `<span class="text-secondary small">ไม่มีเมนูแสดงผล</span>`;
        return;
    }

    // วาด Interactive Tree Builder
    menuTree.forEach((item, pIndex) => {
        const itemCard = document.createElement('div');
        itemCard.className = 'glass-card p-3.5 rounded-4 transition-all position-relative';
        itemCard.style.background = 'var(--glass-bg)';
        itemCard.style.border = '1px solid var(--glass-border)';
        itemCard.style.boxShadow = '0 4px 15px rgba(0,0,0,0.03)';

        // วาดรายการเมนูย่อย
        let childrenHtml = '';
        if (item.children && item.children.length > 0) {
            item.children.forEach((sub, sIndex) => {
                childrenHtml += `
                    <div class="d-flex flex-wrap align-items-center gap-2 p-2.5 mt-2 rounded-3" style="background: rgba(16, 185, 129, 0.04); border-left: 3px solid #10b981; border: 1px solid var(--glass-border); border-left-width: 3px;">
                        <span class="text-emerald ms-1 d-flex align-items-center" style="font-size: 0.85rem;" title="เมนูย่อย">
                            <i class="fa-solid fa-turn-up fa-rotate-90"></i>
                        </span>
                        <input type="text" class="form-control form-control-sm custom-input" placeholder="ชื่อเมนูย่อย..." value="${escapeHtml(sub.title || '')}" onchange="updateSubItem(${pIndex}, ${sIndex}, 'title', this.value)" style="flex: 2; min-width: 140px; font-weight: 500;">
                        <input type="text" class="form-control form-control-sm custom-input" placeholder="ลิงก์ปลายทาง (เช่น #news หรือ https://...)" value="${escapeHtml(sub.url || '')}" onchange="updateSubItem(${pIndex}, ${sIndex}, 'url', this.value)" style="flex: 2; min-width: 140px; font-size: 0.85rem;">
                        
                        <div class="d-flex align-items-center gap-1 ms-auto">
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-2" style="width: 28px; height: 28px;" title="เลื่อนขึ้น" onclick="moveSubItem(${pIndex}, ${sIndex}, -1)" ${sIndex === 0 ? 'disabled' : ''}>
                                <i class="fa-solid fa-arrow-up" style="font-size: 0.75rem;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-2" style="width: 28px; height: 28px;" title="เลื่อนลง" onclick="moveSubItem(${pIndex}, ${sIndex}, 1)" ${sIndex === item.children.length - 1 ? 'disabled' : ''}>
                                <i class="fa-solid fa-arrow-down" style="font-size: 0.75rem;"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger p-1 rounded-2" style="width: 28px; height: 28px;" title="ลบเมนูย่อย" onclick="deleteSubItem(${pIndex}, ${sIndex})">
                                <i class="fa-solid fa-trash" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        itemCard.innerHTML = `
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-2" style="border-color: var(--glass-border) !important;">
                <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
                    <span class="badge rounded-pill fw-bold text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); font-size: 0.88rem;">
                        ${pIndex + 1}
                    </span>
                    <div class="input-group input-group-sm" style="width: 135px;">
                        <span class="input-group-text bg-transparent border-end-0" style="border-color: var(--glass-border);"><i class="${escapeHtml(item.icon || 'fa-solid fa-link')} text-primary" style="font-size: 0.85rem;"></i></span>
                        <input type="text" class="form-control custom-input border-start-0 ps-1" placeholder="fa-..." value="${escapeHtml(item.icon || '')}" onchange="updateParentItem(${pIndex}, 'icon', this.value)" style="font-size: 0.82rem;">
                    </div>
                    <input type="text" class="form-control custom-input fw-bold" placeholder="ชื่อเมนูหลัก..." value="${escapeHtml(item.title || '')}" onchange="updateParentItem(${pIndex}, 'title', this.value)" style="flex: 2; min-width: 150px; font-size: 0.98rem; color: var(--text-primary);">
                    <input type="text" class="form-control custom-input" placeholder="ลิงก์ URL..." value="${escapeHtml(item.url || '')}" onchange="updateParentItem(${pIndex}, 'url', this.value)" style="flex: 2; min-width: 140px; font-size: 0.88rem;">
                </div>
                
                <div class="d-flex align-items-center gap-1 ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-success px-2.5 py-1 rounded-3 d-flex align-items-center gap-1.5 fw-medium" style="font-size: 0.82rem;" onclick="addSubmenuItem(${pIndex})">
                        <i class="fa-solid fa-plus text-success"></i> <span>เมนูย่อย</span>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-3" style="width: 30px; height: 30px;" title="เลื่อนขึ้น" onclick="moveParentItem(${pIndex}, -1)" ${pIndex === 0 ? 'disabled' : ''}>
                        <i class="fa-solid fa-chevron-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary p-1 rounded-3" style="width: 30px; height: 30px;" title="เลื่อนลง" onclick="moveParentItem(${pIndex}, 1)" ${pIndex === menuTree.length - 1 ? 'disabled' : ''}>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger p-1 rounded-3" style="width: 30px; height: 30px;" title="ลบเมนูหลัก" onclick="deleteParentItem(${pIndex})">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <!-- Submenu Items section -->
            <div class="ps-2 pe-1">
                ${childrenHtml}
                ${item.children && item.children.length > 0 ? '' : '<div class="text-secondary opacity-75 small mt-2 ps-1" style="font-size: 0.8rem;"><i class="fa-regular fa-folder-open me-1"></i>ไม่มีเมนูย่อย (กดปุ่ม "+ เมนูย่อย" หากต้องการทำ Dropdown)</div>'}
            </div>
        `;

        container.appendChild(itemCard);

        // วาด Simulator ในหน้าต่างจำลองด้านขวา
        const hasChildren = item.children && item.children.length > 0;
        const pill = document.createElement('div');
        pill.className = 'btn-group';

        let dropItemsHtml = '';
        if (hasChildren) {
            item.children.forEach(c => {
                dropItemsHtml += `
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3 rounded-3" href="javascript:void(0)" onclick="App.toast('จำลองคลิก: ${escapeJs(c.title)}', 'info')" style="font-size: 0.88rem;">
                            <i class="fa-solid fa-angle-right text-emerald" style="font-size: 0.75rem;"></i> 
                            <span>${escapeHtml(c.title)}</span>
                        </a>
                    </li>
                `;
            });
        }

        if (hasChildren) {
            pill.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1.5 rounded-pill px-3 py-1.5" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: var(--glass-border); font-size: 0.85rem; color: var(--text-primary); font-weight: 500; background: var(--bg-surface);">
                    <i class="${escapeHtml(item.icon || 'fa-solid fa-circle')} text-emerald" style="font-size: 0.8rem;"></i>
                    <span>${escapeHtml(item.title)}</span>
                </button>
                <ul class="dropdown-menu shadow-lg p-2 rounded-4 border-0 mt-1" style="background: var(--bg-surface); border: 1px solid var(--glass-border) !important; min-width: 180px;">
                    ${dropItemsHtml}
                </ul>
            `;
        } else {
            pill.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1.5 rounded-pill px-3 py-1.5" type="button" onclick="App.toast('จำลองคลิกเมนูหลัก: ${escapeJs(item.title)}', 'info')" style="border-color: var(--glass-border); font-size: 0.85rem; color: var(--text-primary); font-weight: 500; background: var(--bg-surface);">
                    <i class="${escapeHtml(item.icon || 'fa-solid fa-circle')} text-primary" style="font-size: 0.8rem;"></i>
                    <span>${escapeHtml(item.title)}</span>
                </button>
            `;
        }

        simNav.appendChild(pill);
    });
}

// Helper escape functions
function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function escapeJs(text) {
    if (!text) return '';
    return text.toString().replace(/'/g, "\\'").replace(/"/g, '\\"');
}

// 3. ฟังก์ชันเพิ่มแก้ไขรายการ (Tree Manipulation Methods)
function addMainMenu() {
    menuTree.push({
        id: 'menu_' + Date.now(),
        title: 'เมนูใหม่',
        url: '#section-' + (menuTree.length + 1),
        icon: 'fa-solid fa-link',
        target: '_self',
        children: []
    });
    renderMenuBuilder();
    App.toast('เพิ่มเมนูหลักแล้ว กรุณากรอกชื่อและลิงก์ได้เลยครับ', 'info');
}

function addSubmenuItem(parentIndex) {
    if (!menuTree[parentIndex].children) {
        menuTree[parentIndex].children = [];
    }
    menuTree[parentIndex].children.push({
        id: 'sub_' + Date.now(),
        title: 'เมนูย่อยใหม่',
        url: '#sub-section',
        target: '_self'
    });
    renderMenuBuilder();
    App.toast(`เพิ่มเมนูย่อยใต้ "${menuTree[parentIndex].title}" เรียบร้อย`, 'info');
}

function updateParentItem(index, field, val) {
    menuTree[index][field] = val;
    renderMenuBuilder();
}

function updateSubItem(pIndex, sIndex, field, val) {
    menuTree[pIndex].children[sIndex][field] = val;
    renderMenuBuilder();
}

function moveParentItem(index, direction) {
    const targetIndex = index + direction;
    if (targetIndex < 0 || targetIndex >= menuTree.length) return;
    const temp = menuTree[index];
    menuTree[index] = menuTree[targetIndex];
    menuTree[targetIndex] = temp;
    renderMenuBuilder();
}

function moveSubItem(pIndex, sIndex, direction) {
    const arr = menuTree[pIndex].children;
    const targetIndex = sIndex + direction;
    if (targetIndex < 0 || targetIndex >= arr.length) return;
    const temp = arr[sIndex];
    arr[sIndex] = arr[targetIndex];
    arr[targetIndex] = temp;
    renderMenuBuilder();
}

function deleteParentItem(index) {
    if (confirm(`คุณต้องการลบเมนูหลัก "${menuTree[index].title}" พร้อมเมนูย่อยทั้งหมดจริงหรือไม่?`)) {
        menuTree.splice(index, 1);
        renderMenuBuilder();
        App.toast('ลบเมนูเรียบร้อย', 'info');
    }
}

function deleteSubItem(pIndex, sIndex) {
    menuTree[pIndex].children.splice(sIndex, 1);
    renderMenuBuilder();
    App.toast('ลบเมนูย่อยเรียบร้อย', 'info');
}

// 4. ฟังก์ชันบันทึกและรีเซ็ต (Ajax Save API)
async function saveMenuTree() {
    const btn = document.getElementById('btnSaveMenu');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังบันทึก...';

    const formData = new FormData();
    formData.append('menu_data', JSON.stringify(menuTree));

    try {
        const res = await App.fetch('<?= base_url("admin/menu/save") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast(res.message || 'บันทึกโครงสร้างเมนูเรียบร้อยแล้ว', 'success');
        } else {
            App.toast('เกิดข้อผิดพลาดในการบันทึก: ' + (res.message || ''), 'error');
        }
    } catch (err) {
        App.toast('ข้อผิดพลาดเซิร์ฟเวอร์: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = orig;
    }
}

async function resetMenuToDefault() {
    if (!confirm('คุณยืนยันที่จะรีเซ็ตโครงสร้างเมนูกลับคืนสู่ค่าเริ่มต้นของทางราชการหรือไม่? ข้อมูลที่แก้ไขจะถูกแทนที่ด้วยชุดค่ามาตรฐาน')) {
        return;
    }

    try {
        const res = await App.fetch('<?= base_url("admin/menu/reset") ?>', {
            method: 'POST'
        });
        if (res.status === 'success') {
            menuTree = res.menu_data;
            renderMenuBuilder();
            App.toast(res.message || 'รีเซ็ตโครงสร้างเมนูกลับสู่ค่าเริ่มต้นสำเร็จ', 'success');
        } else {
            App.toast('เกิดข้อผิดพลาดในการรีเซ็ต: ' + (res.message || ''), 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
    }
}

// รันแสดงผลทันทีที่โหลดเสร็จสิ้น
document.addEventListener('DOMContentLoaded', function() {
    renderMenuBuilder();
});
</script>
<?= $this->endSection() ?>

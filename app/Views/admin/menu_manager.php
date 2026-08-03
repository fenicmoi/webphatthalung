<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $s = function_exists('get_site_settings') ? get_site_settings() : []; ?>
<div class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--text-primary);">
            <i class="fa-solid fa-compass text-primary me-2"></i>จัดการโครงสร้างเมนูบาร์ด้านบน (Navigation & Dropdowns)
        </h3>
        <p class="text-secondary mb-0">ปรับแต่งรายการเมนูหลักและเมนูย่อย (Submenu) แบบ Interactive SPA พร้อมระบบจำลองการแสดงผลสด</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn-modern-outline text-danger" onclick="resetMenuToDefault()">
            <i class="fa-solid fa-rotate-left me-1"></i> รีเซ็ตค่าเริ่มต้น
        </button>
        <button type="button" class="btn-modern" id="btnSaveMenu" onclick="saveMenuTree()">
            <i class="fa-solid fa-cloud-arrow-up me-2"></i> บันทึกโครงสร้างเมนู
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT COLUMN: INTERACTIVE MENU BUILDER -->
    <div class="col-xl-7 col-lg-6">
        <div class="glass-card p-4 mb-4" style="border-radius: 24px; border: 1px solid var(--glass-border); box-shadow: var(--glass-shadow);">
            <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3" style="border-color: var(--glass-border) !important;">
                <div>
                    <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-sitemap me-2"></i>ลำดับเมนูเว็บไซต์ (Menu Hierarchy)</h5>
                    <small class="text-muted">คลิกที่ปุ่มเพิ่มเมนูย่อยเพื่อสร้างรายการแบบหยด (Dropdown Submenu)</small>
                </div>
                <button type="button" class="btn-modern py-2 px-3" style="font-size: 0.9rem; border-radius: 12px;" onclick="addMainMenu()">
                    <i class="fa-solid fa-circle-plus me-1"></i> เพิ่มเมนูหลัก
                </button>
            </div>

            <div id="menuTreeContainer" class="d-flex flex-column gap-3">
                <!-- Javascript will reactively inject menu components here -->
            </div>

            <div class="mt-4 text-center">
                <button type="button" class="btn-modern-outline w-100 py-3 d-flex align-items-center justify-content-center gap-2" style="border-radius: 16px; border-style: dashed;" onclick="addMainMenu()">
                    <i class="fa-solid fa-plus text-primary"></i> <span class="fw-bold">เพิ่มรายการเมนูหลักด้านบนสุด (Add New Main Nav)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: LIVE SIMULATOR -->
    <div class="col-xl-5 col-lg-6">
        <div class="glass-card p-4 sticky-top" style="top: 100px; border-radius: 24px; border: 1px solid var(--glass-border); background: var(--bg-secondary);">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3" style="border-color: var(--glass-border) !important;">
                <h6 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-tv me-2"></i>จำลองแถบ Navbar จริง (Live Preview)</h6>
                <span class="badge bg-success">Interactive Demo</span>
            </div>

            <!-- Simulation Navbar Display -->
            <div class="p-3 rounded-4 mb-3" style="background: var(--glass-bg); border: 2px solid var(--glass-border); box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: rgba(0,0,0,0.05) !important;">
                    <div class="d-flex align-items-center gap-2 fw-bold text-primary" style="font-size: 0.95rem;">
                        <?php $logo = function_exists('get_site_logo') ? get_site_logo() : ''; ?>
                        <?php if(!empty($logo)): ?>
                            <img src="<?= htmlspecialchars($logo) ?>" style="height: 28px; width: auto;">
                        <?php else: ?>
                            <i class="fa-solid fa-building-flag text-danger"></i>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($s['site_title_th'] ?? 'จังหวัดพัทลุง') ?></span>
                    </div>
                    <span class="badge bg-primary" style="font-size: 0.7rem;">Top Bar</span>
                </div>

                <!-- Interactive Simulated Menu Pills -->
                <div id="simulatedNavbar" class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Javascript will inject simulated pills & dropdowns -->
                </div>
            </div>

            <div class="alert alert-info border-0 rounded-4 d-flex align-items-start gap-3" style="background: rgba(99, 102, 241, 0.1); color: var(--text-primary); font-size: 0.88rem;">
                <i class="fa-solid fa-lightbulb text-primary mt-1" style="font-size: 1.2rem;"></i>
                <div>
                    <strong>เคล็ดลับการออกแบบเมนูราชการ:</strong>
                    <ul class="mb-0 ps-3 mt-1" style="color: var(--text-secondary);">
                        <li>เมนูที่มี "เมนูย่อย" จะมีลูกศรชี้ลง ▾ และปรากฏกรอบลอยเมื่อเอาเมาส์คลิกหรือวางเหนือรายการ</li>
                        <li>ลิงก์สามารถใส่เป็น `#hash` สำหรับหน้าหลัก (เช่น `#services`, `#news`) หรือ URL เว็บอื่นได้ครับ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ข้อมูลเมนูตั้งต้นจาก PHP
let menuTree = <?= json_encode($currentMenu ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

// 1. ฟังก์ชันวาดเมนู (Reactive Render)
function renderMenuBuilder() {
    const container = document.getElementById('menuTreeContainer');
    const simNav = document.getElementById('simulatedNavbar');
    container.innerHTML = '';
    simNav.innerHTML = '';

    if (!menuTree || menuTree.length === 0) {
        container.innerHTML = `<div class="text-center py-5 text-muted">ยังไม่มีข้อมูลเมนู คลิกปุ่มเพิ่มเมนูหลักได้เลยครับ</div>`;
        return;
    }

    // วาด Interactive Tree Builder
    menuTree.forEach((item, pIndex) => {
        const itemCard = document.createElement('div');
        itemCard.className = 'glass-card p-3 rounded-4 transition-all';
        itemCard.style.background = 'var(--glass-bg)';
        itemCard.style.border = '1px solid var(--glass-border)';

        // ลายเซ็นเมนูหลัก
        let childrenHtml = '';
        if (item.children && item.children.length > 0) {
            item.children.forEach((sub, sIndex) => {
                childrenHtml += `
                    <div class="d-flex align-items-center gap-2 p-2 mt-2 rounded-3" style="background: rgba(0,0,0,0.03); border-left: 3px solid var(--accent-primary);">
                        <i class="fa-solid fa-turn-up fa-rotate-90 text-primary ms-2" style="font-size: 0.8rem;"></i>
                        <input type="text" class="form-control form-control-sm custom-input" placeholder="ชื่อเมนูย่อย..." value="${sub.title || ''}" onchange="updateSubItem(${pIndex}, ${sIndex}, 'title', this.value)" style="flex: 2;">
                        <input type="text" class="form-control form-control-sm custom-input" placeholder="ลิงก์ปลายทาง (เช่น #news หรือ https://...)" value="${sub.url || ''}" onchange="updateSubItem(${pIndex}, ${sIndex}, 'url', this.value)" style="flex: 2;">
                        
                        <button type="button" class="btn btn-sm text-secondary p-1" title="เลื่อนขึ้น" onclick="moveSubItem(${pIndex}, ${sIndex}, -1)"><i class="fa-solid fa-arrow-up"></i></button>
                        <button type="button" class="btn btn-sm text-secondary p-1" title="เลื่อนลง" onclick="moveSubItem(${pIndex}, ${sIndex}, 1)"><i class="fa-solid fa-arrow-down"></i></button>
                        <button type="button" class="btn btn-sm text-danger p-1" title="ลบเมนูย่อย" onclick="deleteSubItem(${pIndex}, ${sIndex})"><i class="fa-solid fa-trash"></i></button>
                    </div>
                `;
            });
        }

        itemCard.innerHTML = `
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-2" style="border-color: rgba(0,0,0,0.05) !important;">
                <div class="d-flex align-items-center gap-2 flex-grow-1">
                    <span class="badge bg-primary rounded-circle p-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">${pIndex + 1}</span>
                    <div class="input-group input-group-sm" style="max-width: 130px;">
                        <span class="input-group-text bg-transparent"><i class="${item.icon || 'fa-solid fa-link'}"></i></span>
                        <input type="text" class="form-control custom-input" placeholder="ไอคอน (fa-...)" value="${item.icon || ''}" onchange="updateParentItem(${pIndex}, 'icon', this.value)">
                    </div>
                    <input type="text" class="form-control custom-input fw-bold" placeholder="ชื่อเมนูหลัก..." value="${item.title || ''}" onchange="updateParentItem(${pIndex}, 'title', this.value)" style="flex: 1; min-width: 150px; font-size: 1rem; color: var(--text-primary);">
                    <input type="text" class="form-control custom-input" placeholder="ลิงก์ URL..." value="${item.url || ''}" onchange="updateParentItem(${pIndex}, 'url', this.value)" style="flex: 1; min-width: 150px; font-size: 0.9rem;">
                </div>
                
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn-modern-outline py-1 px-2 text-primary" style="font-size: 0.8rem; border-radius: 8px;" onclick="addSubmenuItem(${pIndex})">
                        <i class="fa-solid fa-plus me-1"></i> เพิ่มเมนูย่อย
                    </button>
                    <button type="button" class="btn btn-sm text-secondary" title="เลื่อนขึ้น" onclick="moveParentItem(${pIndex}, -1)"><i class="fa-solid fa-chevron-up"></i></button>
                    <button type="button" class="btn btn-sm text-secondary" title="เลื่อนลง" onclick="moveParentItem(${pIndex}, 1)"><i class="fa-solid fa-chevron-down"></i></button>
                    <button type="button" class="btn btn-sm text-danger" title="ลบเมนูหลัก" onclick="deleteParentItem(${pIndex})"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            </div>

            <!-- Submenu Items section -->
            <div class="ps-3 pe-2">
                ${childrenHtml}
                ${item.children && item.children.length > 0 ? '' : '<small class="text-muted d-block mt-2" style="font-size: 0.8rem;"><i class="fa-regular fa-folder me-1"></i>ไม่มีเมนูย่อย (คลิก "เพิ่มเมนูย่อย" เพื่อเปลี่ยนเป็นเมนูแบบ Dropdown)</small>'}
            </div>
        `;

        container.appendChild(itemCard);

        // วาด Simulator ด้านบนขวา
        const hasChildren = item.children && item.children.length > 0;
        const pill = document.createElement('div');
        pill.className = 'btn-group';

        let dropItemsHtml = '';
        if (hasChildren) {
            item.children.forEach(c => {
                dropItemsHtml += `<li><a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0)" onclick="App.toast('จำลองคลิก: ${c.title}', 'info')" style="font-size: 0.85rem;"><i class="fa-solid fa-chevron-right text-primary" style="font-size: 0.7rem;"></i> ${c.title}</a></li>`;
            });
        }

        if (hasChildren) {
            pill.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-color: var(--glass-border); font-size: 0.85rem; color: var(--text-primary); font-weight: 500;">
                    <i class="${item.icon || 'fa-solid fa-circle'} text-primary"></i> ${item.title}
                </button>
                <ul class="dropdown-menu glass-card shadow-lg p-2 rounded-4 border-0" style="background: var(--bg-secondary);">
                    ${dropItemsHtml}
                </ul>
            `;
        } else {
            pill.innerHTML = `
                <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 rounded-pill px-3 py-1" type="button" onclick="App.toast('จำลองคลิกเมนูหลัก: ${item.title}', 'info')" style="border-color: var(--glass-border); font-size: 0.85rem; color: var(--text-primary); font-weight: 500;">
                    <i class="${item.icon || 'fa-solid fa-circle'} text-primary"></i> ${item.title}
                </button>
            `;
        }

        simNav.appendChild(pill);
    });
}

// 2. ฟังก์ชันเพิ่มแก้ไขรายการ (Tree Manipulation Methods)
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
}

// 3. ฟังก์ชันบันทึกและรีเซ็ต (Ajax Save API)
async function saveMenuTree() {
    const btn = document.getElementById('btnSaveMenu');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังบันทึกข้อมูล...';

    const formData = new FormData();
    formData.append('menu_data', JSON.stringify(menuTree));

    try {
        const res = await App.fetch('<?= base_url("admin/menu/save") ?>', {
            method: 'POST',
            body: formData
        });
        if (res.status === 'success') {
            App.toast(res.message, 'success');
        } else {
            App.toast('เกิดข้อผิดพลาดในการบันทึก: ' + res.message, 'error');
        }
    } catch (err) {
        App.toast('ข้อผิดพลาดเซิร์ฟเวอร์: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = orig;
    }
}

async function resetMenuToDefault() {
    if (!confirm('คุณยืนยันที่จะรีเซ็ตโครงสร้างเมนูกลับคืนสู่ค่าเริ่มต้นของทางราชการหรือไม่?')) {
        return;
    }

    try {
        const res = await App.fetch('<?= base_url("admin/menu/reset") ?>', {
            method: 'POST'
        });
        if (res.status === 'success') {
            menuTree = res.menu_data;
            renderMenuBuilder();
            App.toast(res.message, 'success');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาดในการรีเซ็ตเมนู', 'error');
    }
}

// รันแสดงผลทันทีที่โหลดเสร็จสิ้น
document.addEventListener('DOMContentLoaded', function() {
    renderMenuBuilder();
});
</script>
<?= $this->endSection() ?>

<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<?php 
$services = $services ?? (function_exists('get_digital_services') ? get_digital_services(null, false) : []);
?>

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2">
            <span class="p-2 rounded-3 text-white d-inline-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); width: 38px; height: 38px; font-size: 1.15rem;">
                <i class="fa-solid fa-laptop-code"></i>
            </span>
            <span>จัดการระบบสารสนเทศ & e-Services (Digital Services)</span>
        </h4>
        <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">
            เพิ่ม ลบ แก้ไข ลิงก์ระบบสารสนเทศ กำหนดไอคอน สี และสถานะการพัฒนาที่จะแสดงในกล่องบริการบนหน้าแรกเว็บไซต์
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-outline-warning fw-bold px-3 py-2 d-flex align-items-center gap-1.5" onclick="resetDigitalServices()" style="border-radius: 12px;">
            <i class="fa-solid fa-rotate-left"></i> คืนค่าเริ่มต้น
        </button>
        <button type="button" class="btn btn-success fw-bold px-4 py-2 shadow-sm d-flex align-items-center gap-2" onclick="openAddServiceModal()" style="border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); border: none;">
            <i class="fa-solid fa-plus-circle fs-5"></i> เพิ่มระบบสารสนเทศใหม่
        </button>
    </div>
</div>

<!-- Info Alert Banner -->
<div class="p-3.5 rounded-4 mb-4 shadow-xs" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25);">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2.5">
            <i class="fa-solid fa-circle-info text-success fs-5"></i>
            <span style="font-size: 0.92rem; color: var(--text-primary);">
                ระบบสารสนเทศที่บันทึกที่นี่ จะถูกนำไปแสดงในแถบ <b>"ศูนย์รวมระบบสารสนเทศและการบริการ"</b> บนหน้าแรกของเว็บไซต์ทันที
            </span>
        </div>
        <a href="<?= base_url() ?>#digitalServicesHub" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.82rem;">
            <i class="fa-solid fa-eye me-1"></i> ดูการแสดงผลหน้าแรก
        </a>
    </div>
</div>

<!-- Digital Services List Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="background: var(--card-bg, #ffffff);">
    <div class="card-header bg-transparent py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
        <span class="fw-bold d-flex align-items-center gap-2" style="font-size: 1.05rem; color: var(--text-primary);">
            <i class="fa-solid fa-list-check text-success"></i>
            <span>รายการระบบสารสนเทศทั้งหมด</span>
            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5" id="servicesCountBadge">
                <?= count($services) ?> ระบบ
            </span>
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="servicesTable">
            <thead class="table-light text-muted" style="font-size: 0.84rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <tr>
                    <th class="ps-4" style="width: 70px;">ลำดับ</th>
                    <th style="width: 65px;">ไอคอน</th>
                    <th>ชื่อระบบ & คำอธิบาย</th>
                    <th style="width: 170px;">กลุ่มผู้ใช้งาน</th>
                    <th>ลิงก์ปลายทาง (URL)</th>
                    <th class="text-center" style="width: 170px;">สถานะการใช้งาน</th>
                    <th class="text-end pe-4" style="width: 140px;">จัดการ</th>
                </tr>
            </thead>
            <tbody id="servicesTableBody">
                <?php if (empty($services)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-laptop-code fs-1 d-block mb-3 opacity-40"></i>
                            ยังไม่มีข้อมูลระบบสารสนเทศในระบบ คลิกปุ่ม <b>"เพิ่มระบบสารสนเทศใหม่"</b> เพื่อเริ่มต้น
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $pastelTones = [
                        '#2563eb' => ['bg' => '#eff6ff', 'text' => '#1d4ed8'],
                        '#059669' => ['bg' => '#ecfdf5', 'text' => '#047857'],
                        '#d97706' => ['bg' => '#fffbeb', 'text' => '#b45309'],
                        '#7c3aed' => ['bg' => '#f5f3ff', 'text' => '#6d28d9'],
                        '#ea580c' => ['bg' => '#fff7ed', 'text' => '#c2410c'],
                        '#0891b2' => ['bg' => '#ecfeff', 'text' => '#0e7490'],
                    ];
                    foreach ($services as $idx => $srv): 
                        $themeColor = !empty($srv['theme_color']) ? $srv['theme_color'] : '#059669';
                        $pastel = $pastelTones[$themeColor] ?? ['bg' => '#ecfdf5', 'text' => '#047857'];
                        $isActive = ($srv['status'] ?? 'active') === 'active';
                        $category = $srv['category'] ?? 'general';
                    ?>
                        <tr id="service-row-<?= esc($srv['id']) ?>" class="service-row-item">
                            <!-- Sort Order -->
                            <td class="ps-4">
                                <span class="badge rounded-pill bg-light text-secondary border fw-bold px-2 py-1" style="font-size: 0.82rem;">
                                    <?= esc($srv['sort_order'] ?? ($idx + 1)) ?>
                                </span>
                            </td>

                            <!-- Icon Preview -->
                            <td>
                                <div style="width: 42px; height: 42px; border-radius: 12px; background: <?= esc($pastel['bg']) ?>; color: <?= esc($pastel['text']) ?>; display: inline-flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                                    <i class="<?= esc($srv['icon'] ?? 'fa-solid fa-laptop') ?>"></i>
                                </div>
                            </td>

                            <!-- Title & Subtitle -->
                            <td>
                                <div class="fw-bold" style="font-size: 0.96rem; color: var(--text-primary);">
                                    <?= esc($srv['title']) ?>
                                </div>
                                <small class="text-muted d-block text-truncate" style="max-width: 320px; font-size: 0.80rem;">
                                    <?= esc($srv['subtitle']) ?>
                                </small>
                            </td>

                            <!-- Category -->
                            <td>
                                <?php if ($category === 'officer'): ?>
                                    <span class="badge rounded-pill px-2.5 py-1.5" style="background: #eff6ff; color: #1d4ed8; border: 1px solid rgba(29, 78, 216, 0.2); font-size: 0.76rem; font-weight: 600;">
                                        <i class="fa-solid fa-building-user me-1"></i> สำหรับเจ้าหน้าที่
                                    </span>
                                <?php elseif ($category === 'citizen'): ?>
                                    <span class="badge rounded-pill px-2.5 py-1.5" style="background: #ecfdf5; color: #047857; border: 1px solid rgba(4, 120, 87, 0.2); font-size: 0.76rem; font-weight: 600;">
                                        <i class="fa-solid fa-users me-1"></i> บริการประชาชน
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill px-2.5 py-1.5" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; font-size: 0.76rem; font-weight: 600;">
                                        <i class="fa-solid fa-globe me-1"></i> ทั่วไป / องค์กร
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Link URL -->
                            <td>
                                <?php if (!empty($srv['url']) && $srv['url'] !== '#'): ?>
                                    <a href="<?= esc($srv['url']) ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none d-inline-flex align-items-center gap-1.5 text-primary text-truncate" style="max-width: 250px; font-size: 0.86rem;">
                                        <span class="text-truncate"><?= esc($srv['url']) ?></span>
                                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small fst-italic">ไม่ได้ระบุลิงก์</span>
                                <?php endif; ?>
                            </td>

                            <!-- 1-Click Status Toggle -->
                            <td class="text-center">
                                <button type="button" 
                                        class="btn btn-sm rounded-pill px-3 py-1 fw-bold transition-all" 
                                        id="status-btn-<?= esc($srv['id']) ?>"
                                        onclick="toggleServiceStatus('<?= esc($srv['id']) ?>')"
                                        style="<?= $isActive ? 'background: #ecfdf5; color: #047857; border: 1.5px solid rgba(16, 185, 129, 0.4);' : 'background: #fffbeb; color: #b45309; border: 1.5px solid rgba(245, 158, 11, 0.4);' ?> font-size: 0.78rem;">
                                    <?php if ($isActive): ?>
                                        <i class="fa-solid fa-circle-check text-success me-1"></i> พร้อมใช้งาน
                                    <?php else: ?>
                                        <i class="fa-solid fa-screwdriver-wrench text-warning me-1"></i> อยู่ระหว่างพัฒนา
                                    <?php endif; ?>
                                </button>
                            </td>

                            <!-- Action Buttons -->
                            <td class="text-end pe-4">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                        style="width: 32px; height: 32px; padding: 0;"
                                        title="แก้ไขข้อมูล" 
                                        onclick="openEditServiceModal(<?= htmlspecialchars(json_encode($srv), ENT_QUOTES, 'UTF-8') ?>)">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger rounded-circle" 
                                        style="width: 32px; height: 32px; padding: 0;"
                                        title="ลบระบบ" 
                                        onclick="deleteService('<?= esc($srv['id']) ?>', '<?= esc(addslashes($srv['title'])) ?>')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: เพิ่ม / แก้ไขระบบสารสนเทศ -->
<div class="modal fade" id="modalServiceForm" tabindex="-1" aria-labelledby="modalServiceFormLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-xl overflow-hidden" style="background: #ffffff;">
            
            <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); color: #ffffff;">
                <div class="d-flex align-items-center gap-2.5">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fa-solid fa-laptop-code" id="modalHeaderIcon"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalServiceFormLabel">เพิ่มระบบสารสนเทศใหม่</h5>
                        <small style="color: #a7f3d0; font-size: 0.82rem;">กรอกข้อมูลระบบสารสนเทศเพื่อแสดงผลในศูนย์บริการดิจิทัล</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="serviceForm" onsubmit="handleServiceSubmit(event)">
                <div class="modal-body p-4" style="background-color: #f8fafc;">
                    <input type="hidden" name="id" id="service_id" value="">

                    <div class="row g-3">
                        <!-- ชื่อระบบ -->
                        <div class="col-md-7">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                ชื่อระบบสารสนเทศ <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control rounded-3" name="title" id="service_title" required placeholder="เช่น e-Office จังหวัดพัทลุง, CRIME MAP 90 วัน">
                        </div>

                        <!-- กลุ่มผู้ใช้งาน / หมวดหมู่ -->
                        <div class="col-md-5">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                กลุ่มผู้ใช้งาน <span class="text-danger">*</span>
                            </label>
                            <select class="form-select rounded-3" name="category" id="service_category" required>
                                <option value="citizen">👥 บริการประชาชน</option>
                                <option value="officer">🏢 สำหรับเจ้าหน้าที่ / ส่วนราชการ</option>
                                <option value="general">🌐 บริการทั่วไป / องค์กร</option>
                            </select>
                        </div>

                        <!-- คำอธิบายสั้นๆ -->
                        <div class="col-12">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                คำอธิบายระบบสั้นๆ (แสดงใต้ชื่อระบบ)
                            </label>
                            <input type="text" class="form-control rounded-3" name="subtitle" id="service_subtitle" placeholder="เช่น ระบบงานสารบรรณและสำนักงานอิเล็กทรอนิกส์">
                        </div>

                        <!-- ลิงก์ URL ปลายทาง -->
                        <div class="col-md-8">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                ลิงก์เว็บไซต์ปลายทาง (URL) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-link text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0 rounded-end-3" name="url" id="service_url" required placeholder="https://eoffice.phatthalung.go.th/ (หรือใส่ # หากยังพัฒนาไม่เสร็จ)">
                            </div>
                        </div>

                        <!-- การเปิดแท็บหน้าจอ -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">การเปิดหน้าต่าง</label>
                            <select class="form-select rounded-3" name="target" id="service_target">
                                <option value="_blank">เปิดแท็บใหม่ (_blank)</option>
                                <option value="_self">เปิดหน้าต่างเดิม (_self)</option>
                            </select>
                        </div>

                        <!-- ไอคอน FontAwesome -->
                        <div class="col-md-7">
                            <label class="form-label fw-bold d-flex align-items-center justify-content-between" style="font-size: 0.9rem;">
                                <span>ไอคอนระบบ (FontAwesome Class)</span>
                                <span id="iconPreviewBadge" class="badge rounded-pill bg-light text-secondary border px-2 py-0.5" style="font-size: 0.75rem;">
                                    <i class="fa-solid fa-laptop me-1"></i> ตัวอย่าง
                                </span>
                            </label>
                            <input type="text" class="form-control rounded-3 font-monospace mb-2" name="icon" id="service_icon" value="fa-solid fa-laptop" oninput="updateIconPreview(this.value)">
                            
                            <!-- Quick Pick Icons -->
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                <small class="text-muted me-1" style="font-size: 0.74rem;">ไอคอนยอดนิยม:</small>
                                <?php 
                                $sampleIcons = [
                                    'fa-solid fa-file-signature' => 'สารบรรณ',
                                    'fa-solid fa-shield-halved'   => 'ความปลอดภัย',
                                    'fa-solid fa-address-book'    => 'สมุดโทรศัพท์',
                                    'fa-solid fa-chart-line'      => 'แผนงาน',
                                    'fa-solid fa-scale-balanced'  => 'ยุติธรรม/ร้องเรียน',
                                    'fa-solid fa-desktop'         => 'คอมพิวเตอร์',
                                    'fa-solid fa-hospital'        => 'สาธารณสุข',
                                    'fa-solid fa-school'          => 'การศึกษา',
                                    'fa-solid fa-tree'            => 'สิ่งแวดล้อม',
                                    'fa-solid fa-bus'             => 'คมนาคม'
                                ];
                                foreach ($sampleIcons as $ico => $lbl): ?>
                                    <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-0.5" style="font-size: 0.72rem;" onclick="pickIcon('<?= $ico ?>')">
                                        <i class="<?= $ico ?> me-1"></i> <?= $lbl ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- ธีมสี (Theme Color) -->
                        <div class="col-md-5">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">สีประจำระบบ</label>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input type="color" class="form-control form-control-color rounded-3 p-1" id="service_theme_color" name="theme_color" value="#059669" style="width: 48px; height: 38px;">
                                <input type="text" class="form-control rounded-3 font-monospace" id="service_theme_color_text" value="#059669" oninput="document.getElementById('service_theme_color').value = this.value">
                            </div>

                            <!-- Quick Color Swatches -->
                            <div class="d-flex gap-1.5 align-items-center">
                                <?php 
                                $swatches = ['#059669', '#2563eb', '#d97706', '#7c3aed', '#ea580c', '#0891b2', '#dc2626'];
                                foreach ($swatches as $c): ?>
                                    <button type="button" class="btn rounded-circle p-0" style="width: 22px; height: 22px; background: <?= $c ?>; border: 1.5px solid #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" onclick="pickColor('<?= $c ?>')"></button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- สถานะการเปิดให้บริการ -->
                        <div class="col-md-7">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">
                                สถานะการให้บริการ <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-3 pt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_active" value="active" checked>
                                    <label class="form-check-label fw-semibold text-success" for="status_active">
                                        <i class="fa-solid fa-circle-check me-1"></i> 🟢 พร้อมใช้งาน
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_development" value="development">
                                    <label class="form-check-label fw-semibold text-warning" for="status_development">
                                        <i class="fa-solid fa-screwdriver-wrench me-1"></i> 🟡 อยู่ระหว่างการพัฒนา
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- ลำดับการแสดงผล -->
                        <div class="col-md-5">
                            <label class="form-label fw-bold" style="font-size: 0.9rem;">ลำดับการแสดงผล (ตัวเลข)</label>
                            <input type="number" class="form-control rounded-3" name="sort_order" id="service_sort_order" value="1" min="1">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top py-3 px-4 d-flex justify-content-between" style="background: #ffffff;">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        ยกเลิก
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" id="btnSubmitForm" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); border: none;">
                        <i class="fa-solid fa-floppy-disk me-1.5"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let serviceModalInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('modalServiceForm');
    if (el) {
        serviceModalInstance = bootstrap.Modal.getOrCreateInstance(el);
    }

    const colorPicker = document.getElementById('service_theme_color');
    const colorText = document.getElementById('service_theme_color_text');
    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
        });
    }
});

function updateIconPreview(iconClass) {
    const badge = document.getElementById('iconPreviewBadge');
    if (badge) {
        badge.innerHTML = `<i class="${iconClass || 'fa-solid fa-laptop'} me-1"></i> ตัวอย่าง`;
    }
}

function pickIcon(iconClass) {
    const input = document.getElementById('service_icon');
    if (input) {
        input.value = iconClass;
        updateIconPreview(iconClass);
    }
}

function pickColor(hexColor) {
    const picker = document.getElementById('service_theme_color');
    const text = document.getElementById('service_theme_color_text');
    if (picker) picker.value = hexColor;
    if (text) text.value = hexColor;
}

function openAddServiceModal() {
    document.getElementById('serviceForm').reset();
    document.getElementById('service_id').value = '';
    document.getElementById('modalServiceFormLabel').innerText = 'เพิ่มระบบสารสนเทศใหม่';
    document.getElementById('modalHeaderIcon').className = 'fa-solid fa-plus-circle';
    pickColor('#059669');
    updateIconPreview('fa-solid fa-laptop');

    // Auto calculate next sort order
    const rows = document.querySelectorAll('.service-row-item');
    document.getElementById('service_sort_order').value = rows.length + 1;
    document.getElementById('status_active').checked = true;

    serviceModalInstance?.show();
}

function openEditServiceModal(item) {
    document.getElementById('serviceForm').reset();
    document.getElementById('service_id').value = item.id || '';
    document.getElementById('service_title').value = item.title || '';
    document.getElementById('service_subtitle').value = item.subtitle || '';
    document.getElementById('service_category').value = item.category || 'general';
    document.getElementById('service_url').value = item.url || '';
    document.getElementById('service_target').value = item.target || '_blank';
    document.getElementById('service_icon').value = item.icon || 'fa-solid fa-laptop';
    document.getElementById('service_sort_order').value = item.sort_order || 1;

    const color = item.theme_color || '#059669';
    pickColor(color);
    updateIconPreview(item.icon);

    if (item.status === 'development') {
        document.getElementById('status_development').checked = true;
    } else {
        document.getElementById('status_active').checked = true;
    }

    document.getElementById('modalServiceFormLabel').innerText = 'แก้ไขระบบสารสนเทศ: ' + item.title;
    document.getElementById('modalHeaderIcon').className = 'fa-solid fa-pencil';

    serviceModalInstance?.show();
}

async function handleServiceSubmit(event) {
    event.preventDefault();
    const form = document.getElementById('serviceForm');
    const btn = document.getElementById('btnSubmitForm');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1.5"></i> กำลังบันทึก...';

    const formData = new FormData(form);

    try {
        const res = await App.fetch('<?= base_url("admin/digital-services/save-inline") ?>', {
            method: 'POST',
            body: formData
        });

        if (res.status === 'success') {
            App.toast(res.message || 'บันทึกข้อมูลเรียบร้อยแล้ว', 'success');
            serviceModalInstance?.hide();
            setTimeout(() => {
                window.location.reload();
            }, 600);
        } else {
            App.toast(res.message || 'เกิดข้อผิดพลาดในการบันทึก', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาด: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

async function toggleServiceStatus(id) {
    const btn = document.getElementById('status-btn-' + id);
    if (!btn) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

    try {
        const res = await App.fetch('<?= base_url("admin/digital-services/toggle-status") ?>/' + encodeURIComponent(id), {
            method: 'POST'
        });

        if (res.status === 'success') {
            App.toast(res.message, 'success');
            if (res.newStatus === 'active') {
                btn.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i> พร้อมใช้งาน';
                btn.style = 'background: #ecfdf5; color: #047857; border: 1.5px solid rgba(16, 185, 129, 0.4); font-size: 0.78rem;';
            } else {
                btn.innerHTML = '<i class="fa-solid fa-screwdriver-wrench text-warning me-1"></i> อยู่ระหว่างพัฒนา';
                btn.style = 'background: #fffbeb; color: #b45309; border: 1.5px solid rgba(245, 158, 11, 0.4); font-size: 0.78rem;';
            }
        } else {
            App.toast(res.message || 'ไม่สามารถเปลี่ยนสถานะได้', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาด: ' + err.message, 'error');
    } finally {
        btn.disabled = false;
    }
}

async function deleteService(id, title) {
    if (typeof Swal !== 'undefined') {
        const confirmResult = await Swal.fire({
            title: 'ยืนยันการลบระบบสารสนเทศ?',
            text: `คุณต้องการลบ "${title}" ออกจากศูนย์บริการดิจิทัลหรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก',
            borderRadius: '16px'
        });
        if (!confirmResult.isConfirmed) return;
    } else {
        if (!confirm(`คุณต้องการลบ "${title}" หรือไม่?`)) return;
    }

    try {
        const res = await App.fetch('<?= base_url("admin/digital-services/delete") ?>/' + encodeURIComponent(id), {
            method: 'POST'
        });

        if (res.status === 'success') {
            App.toast(res.message, 'success');
            const row = document.getElementById('service-row-' + id);
            if (row) {
                row.remove();
            }
        } else {
            App.toast(res.message || 'ไม่สามารถลบข้อมูลได้', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาด: ' + err.message, 'error');
    }
}

async function resetDigitalServices() {
    if (typeof Swal !== 'undefined') {
        const confirmResult = await Swal.fire({
            title: 'คืนค่ารายการระบบสารสนเทศเริ่มต้น?',
            text: 'ระบบจะรีเซ็ตรายการกลับเป็น 6 ระบบมาตรฐานตามเดิม',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'ใช่, คืนค่าเริ่มต้น',
            cancelButtonText: 'ยกเลิก',
            borderRadius: '16px'
        });
        if (!confirmResult.isConfirmed) return;
    } else {
        if (!confirm('ยืนยันการคืนค่ารายการระบบเริ่มต้น?')) return;
    }

    try {
        const res = await App.fetch('<?= base_url("admin/digital-services/reset") ?>', {
            method: 'POST'
        });

        if (res.status === 'success') {
            App.toast(res.message, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 600);
        } else {
            App.toast(res.message || 'ไม่สามารถรีเซ็ตได้', 'error');
        }
    } catch (err) {
        App.toast('เกิดข้อผิดพลาด: ' + err.message, 'error');
    }
}
</script>

<?= $this->endSection() ?>

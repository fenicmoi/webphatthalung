<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ระบบยืนยันตัวตนเจ้าหน้าที่ | Phatthalung Portal Login' ?></title>
    
    <!-- CSRF Meta -->
    <meta name="X-CSRF-HEADER" content="<?= csrf_header() ?>">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    
    <!-- Bootstrap 5.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Design System -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    
    <style>
        .login-viewport {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }
        .login-card {
            width: 100%;
            max-width: 480px;
            border-radius: 28px !important;
            padding: 2.5rem !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25) !important;
            border: 1px solid var(--glass-border) !important;
        }
        .demo-btn {
            font-size: 0.82rem;
            padding: 0.45rem 0.85rem;
            border-radius: var(--radius-full);
            background: rgba(99, 102, 241, 0.12);
            color: var(--accent-primary);
            border: 1px solid rgba(99, 102, 241, 0.3);
            cursor: pointer;
            transition: var(--transition-smooth);
        }
        .demo-btn:hover {
            background: var(--gradient-hero);
            color: white !important;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>
    <div class="ambient-glow-2"></div>

    <!-- Theme Switcher Top-Right Bar -->
    <div class="position-fixed top-0 end-0 p-4" style="z-index: 10;">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url() ?>" class="btn-modern-outline text-decoration-none" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                <i class="fa-solid fa-arrow-left me-1"></i> กลับหน้าเว็บประชาชน
            </a>
            <button id="theme-toggle" class="theme-toggle-btn" title="สลับโหมดกลางวัน/กลางคืน">
                <i class="fa-solid fa-moon text-indigo-500"></i>
            </button>
        </div>
    </div>

    <!-- Login Content Viewport -->
    <div class="login-viewport">
        <div class="glass-card login-card hover-lift">
            <div class="text-center mb-4">
                <div class="mb-3 d-flex justify-content-center">
                    <div style="width: 68px; height: 68px; border-radius: 22px; background: var(--gradient-hero); color: white; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);">
                        <i class="fa-solid fa-user-lock"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1">ระบบยืนยันตัวตนเจ้าหน้าที่</h4>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;">
                    ศูนย์บริหารจัดการข้อมูลภาครัฐ <strong>Phatthalung Portal</strong>
                </p>
            </div>

            <!-- Flashdata Alert Toast check -->
            <?php if (session()->getFlashdata('toast_msg')): ?>
                <div class="alert alert-warning d-flex align-items-center mb-4" role="alert" style="border-radius: var(--radius-sm); font-size: 0.9rem;">
                    <i class="fa-solid fa-triangle-exclamation me-2 text-warning" style="font-size: 1.2rem;"></i>
                    <div><?= session()->getFlashdata('toast_msg') ?></div>
                </div>
            <?php endif; ?>

            <!-- Demo Fill Section for Seamless Development Showcase -->
            <div class="mb-4 p-3 text-center" style="background: rgba(255,255,255,0.04); border-radius: var(--radius-sm); border: 1px dashed var(--glass-border);">
                <small style="color: var(--text-muted); display: block; margin-bottom: 0.5rem;"><i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i>คลิกเพื่อกรอกรหัสทดสอบอัตโนมัติ (Demo Access):</small>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" class="demo-btn" onclick="fillDemo('admin', 'password123')">
                        👑 สิทธิ์ผู้ดูแลระบบ (Admin)
                    </button>
                    <button type="button" class="demo-btn" onclick="fillDemo('officer', 'officer123')">
                        🛡️ สิทธิ์เจ้าหน้าที่ (Officer)
                    </button>
                </div>
            </div>

            <!-- Interactive Login Form -->
            <form id="loginForm" onsubmit="handleLoginSubmit(event)">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size: 0.9rem;">ชื่อผู้ใช้งาน หรือ อีเมลราชการ <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <i class="fa-solid fa-id-badge position-absolute" style="left: 14px; top: 12px; color: var(--text-muted); font-size: 1.1rem;"></i>
                        <input type="text" id="username" name="username" class="form-control custom-input" required placeholder="เช่น admin หรือ officer" style="padding-left: 2.75rem !important;">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-bold mb-0" style="font-size: 0.9rem;">รหัสผ่าน <span class="text-danger">*</span></label>
                        <a href="#" onclick="App.toast('กรุณาติดต่อศูนย์คอมพิวเตอร์และสื่อสารเพื่อทำการรีเซ็ตพาสเวิร์ดครับ', 'info'); return false;" style="font-size: 0.8rem; color: var(--accent-primary); text-decoration: none;">ลืมรหัสผ่าน?</a>
                    </div>
                    <div class="position-relative">
                        <i class="fa-solid fa-key position-absolute" style="left: 14px; top: 12px; color: var(--text-muted); font-size: 1.1rem;"></i>
                        <input type="password" id="password" name="password" class="form-control custom-input" required placeholder="••••••••••••" style="padding-left: 2.75rem !important; padding-right: 2.75rem !important;">
                        <button type="button" onclick="togglePassword()" class="btn btn-link position-absolute p-0 border-0" style="right: 14px; top: 10px; color: var(--text-muted); text-decoration: none;">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                        <label class="form-check-label" for="rememberMe" style="font-size: 0.85rem; color: var(--text-secondary);">
                            จดจำเซสชันในเบราว์เซอร์นี้
                        </label>
                    </div>
                </div>

                <button type="submit" id="btnLogin" class="btn-modern w-100 justify-content-center" style="padding: 0.8rem; font-size: 1.05rem;">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> เข้าสู่ระบบ
                </button>
            </form>

            <div class="text-center mt-4 pt-3 border-top" style="border-color: var(--glass-border) !important;">
                <small style="color: var(--text-muted);">
                    การเข้าถึงโดยไม่ได้รับอนุญาตอาจมีโทษทางกฎหมาย พ.ร.บ. คอมพิวเตอร์
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script>
        // 1. ตรวจสอบข้อความแจ้งเตือน Toast ที่ติดมากับ Flashdata
        <?php if (session()->getFlashdata('toast_msg')): ?>
            document.addEventListener('DOMContentLoaded', () => {
                App.toast('<?= session()->getFlashdata("toast_msg") ?>', '<?= session()->getFlashdata("toast_type") ?? "info" ?>');
            });
        <?php endif; ?>

        // 2. ฟังก์ชันกรอกรหัสทดสอบอัตโนมัติ (Demo Credentials)
        function fillDemo(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            App.toast(`กรอกข้อมูลของบัญชี ${username} เรียบร้อย! กดปุ่มเข้าสู่ระบบได้เลย`, 'info');
        }

        // 3. ฟังก์ชันสลับดูรหัสผ่าน
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // 4. ประมวลผลเข้าสู่ระบบแบบ Async (No-Reload)
        async function handleLoginSubmit(event) {
            event.preventDefault();
            const btn = document.getElementById('btnLogin');
            const form = document.getElementById('loginForm');
            const formData = new FormData(form);

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังตรวจสอบสิทธิ์กับเซิร์ฟเวอร์...';

            try {
                const res = await App.fetch('<?= base_url("login/attempt") ?>', {
                    method: 'POST',
                    body: formData
                });

                if (res.status === 'success') {
                    App.toast(`✨ ${res.message}`, 'success');
                    btn.classList.remove('btn-modern');
                    btn.style.background = '#10b981';
                    btn.innerHTML = '<i class="fa-solid fa-check me-2"></i>ยืนยันตัวตนสำเร็จ กำลังเปลี่ยนหน้า...';

                    setTimeout(() => {
                        window.location.href = res.redirect;
                    }, 800);
                } else {
                    App.toast(res.message || 'รหัสผ่านไม่ถูกต้อง', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            } catch (err) {
                // error alert handled in App.fetch
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }
    </script>
</body>
</html>

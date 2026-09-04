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
        :root {
            --login-primary: #047857;
            --login-primary-dark: #064e3b;
            --login-accent: #d97706;
        }

        body {
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .login-viewport {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            position: relative;
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 540px;
            border-radius: 32px !important;
            padding: 3rem 2.5rem !important;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.28), 0 0 0 1px rgba(4, 120, 87, 0.15) !important;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
        }

        .login-logo-box {
            width: 84px;
            height: 84px;
            border-radius: 26px;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #047857 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px rgba(4, 120, 87, 0.35);
            border: 2px solid rgba(255, 255, 255, 0.25);
            padding: 10px;
        }

        .login-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #064e3b;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 1.05rem;
            color: #4b5563;
        }

        .form-label-lg {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.45rem;
        }

        .login-input {
            font-size: 1.1rem !important;
            padding: 0.85rem 1.2rem 0.85rem 3.4rem !important;
            border-radius: 16px !important;
            border: 2px solid #d1ded5 !important;
            background: #fbfdfc !important;
            transition: all 0.25s ease !important;
        }

        .login-input:focus {
            border-color: #047857 !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(4, 120, 87, 0.15) !important;
        }

        .login-input-icon {
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #047857;
            font-size: 1.25rem;
            pointer-events: none;
        }

        .login-toggle-eye {
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1.2rem;
            padding: 6px;
            text-decoration: none;
        }

        .login-toggle-eye:hover {
            color: #047857;
        }

        .demo-box {
            background: #f0fdf4;
            border: 1.5px dashed #86efac;
            border-radius: 20px;
            padding: 1.2rem;
        }

        .demo-btn {
            font-size: 0.95rem;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            background: #ffffff;
            color: #065f46;
            border: 1.5px solid #a7f3d0;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(4, 120, 87, 0.08);
        }

        .demo-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white !important;
            border-color: #047857;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(4, 120, 87, 0.25);
        }

        .btn-login-submit {
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #064e3b 100%);
            color: #ffffff !important;
            font-size: 1.25rem !important;
            font-weight: 800 !important;
            padding: 0.95rem 1.5rem !important;
            border-radius: 50px !important;
            border: none !important;
            box-shadow: 0 8px 25px rgba(4, 120, 87, 0.35) !important;
            transition: all 0.25s ease !important;
        }

        .btn-login-submit:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(4, 120, 87, 0.45) !important;
        }

        .btn-back-home {
            font-size: 1.05rem;
            font-weight: 700;
            padding: 0.65rem 1.35rem;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.9);
            border: 1.5px solid #d1e7dd;
            color: #047857;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }

        .btn-back-home:hover {
            background: #047857;
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        [data-theme="dark"] .login-card {
            background: rgba(15, 23, 42, 0.92) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #f1f5f9;
        }

        [data-theme="dark"] .login-title {
            color: #34d399 !important;
        }

        [data-theme="dark"] .login-subtitle {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .form-label-lg {
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .login-input {
            background: #0b1329 !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }

        [data-theme="dark"] .demo-box {
            background: rgba(4, 120, 87, 0.15) !important;
            border-color: rgba(52, 211, 153, 0.3) !important;
        }

        [data-theme="dark"] .demo-btn {
            background: #1e293b;
            color: #34d399;
            border-color: #047857;
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>
    <div class="ambient-glow-2"></div>

    <!-- Theme Switcher Top-Right Bar -->
    <div class="position-fixed top-0 end-0 p-4" style="z-index: 10;">
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url() ?>" class="btn-back-home text-decoration-none d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-house"></i>
                <span>กลับหน้าเว็บประชาชน</span>
            </a>
            <button id="theme-toggle" class="theme-toggle-btn p-2.5 fs-5 rounded-circle shadow-sm" title="สลับโหมดกลางวัน/กลางคืน">
                <i class="fa-solid fa-moon text-emerald"></i>
            </button>
        </div>
    </div>

    <!-- Login Content Viewport -->
    <div class="login-viewport">
        <div class="glass-card login-card hover-lift">
            
            <div class="text-center mb-4">
                <div class="mb-3 d-flex justify-content-center">
                    <div class="login-logo-box">
                        <img src="<?= base_url('assets/images/phatthalung_fabric_emblem.svg') ?>" alt="ตราลายผ้าอัตลักษณ์ประจำจังหวัดพัทลุง">
                    </div>
                </div>
                <h2 class="login-title mb-1.5">ระบบยืนยันตัวตนเจ้าหน้าที่</h2>
                <p class="login-subtitle mb-0">
                    ศูนย์บริหารจัดการข้อมูลภาครัฐ <strong>จังหวัดพัทลุง</strong>
                </p>
            </div>

            <!-- Flashdata Alert Toast check -->
            <?php if (session()->getFlashdata('toast_msg')): ?>
                <div class="alert alert-warning d-flex align-items-center mb-4 p-3 rounded-3 fs-6" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2.5 text-warning fs-5"></i>
                    <div><?= session()->getFlashdata('toast_msg') ?></div>
                </div>
            <?php endif; ?>

            <!-- Demo Fill Section for Seamless Development Showcase -->
            <div class="demo-box mb-4 text-center">
                <div class="fw-bold text-emerald mb-2" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-wand-magic-sparkles text-warning me-1.5"></i>คลิกเพื่อกรอกรหัสทดสอบอัตโนมัติ (Demo Access):
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-2.5">
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
                
                <!-- Username -->
                <div class="mb-3.5">
                    <label class="form-label-lg" for="username">
                        ชื่อผู้ใช้งาน หรือ อีเมลราชการ <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                        <i class="fa-solid fa-user-shield position-absolute login-input-icon"></i>
                        <input type="text" id="username" name="username" class="form-control login-input" required placeholder="เช่น admin หรือ officer" autocomplete="username">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3.5">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label-lg mb-0" for="password">
                            รหัสผ่าน <span class="text-danger">*</span>
                        </label>
                        <a href="#" onclick="App.toast('กรุณาติดต่อศูนย์สารสนเทศและการสื่อสารเพื่อทำการรีเซ็ตรหัสผ่านครับ', 'info'); return false;" class="text-emerald fw-bold text-decoration-none" style="font-size: 0.95rem;">
                            ลืมรหัสผ่าน?
                        </a>
                    </div>
                    <div class="position-relative">
                        <i class="fa-solid fa-lock position-absolute login-input-icon"></i>
                        <input type="password" id="password" name="password" class="form-control login-input" required placeholder="••••••••••••" autocomplete="current-password">
                        <button type="button" onclick="togglePassword()" class="btn btn-link position-absolute login-toggle-eye" aria-label="สลับดูรหัสผ่าน">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check d-flex align-items-center gap-2">
                        <input class="form-check-input" type="checkbox" id="rememberMe" checked style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                        <label class="form-check-label text-muted" for="rememberMe" style="font-size: 0.95rem; cursor: pointer;">
                            จดจำเซสชันในเบราว์เซอร์นี้
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="btnLogin" class="btn btn-login-submit w-100 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-right-to-bracket me-2.5"></i> เข้าสู่ระบบ
                </button>
            </form>

            <div class="text-center mt-4 pt-3.5 border-top" style="border-color: rgba(0,0,0,0.08) !important;">
                <p class="text-muted mb-0" style="font-size: 0.92rem;">
                    <i class="fa-solid fa-shield-halved text-warning me-1"></i> ระบบสำหรับเจ้าหน้าที่และผู้ได้รับมอบหมายเท่านั้น
                </p>
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
                    btn.classList.remove('btn-login-submit');
                    btn.style.background = '#10b981';
                    btn.style.color = '#ffffff';
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

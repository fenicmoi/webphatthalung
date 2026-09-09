<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ระบบยืนยันตัวตนเจ้าหน้าที่ | Phatthalung Portal Login' ?></title>
    
    <!-- CSRF Meta -->
    <meta name="X-CSRF-HEADER" content="<?= csrf_header() ?>">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    
    <!-- Google Fonts: Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            font-family: 'Prompt', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            font-size: 0.95rem;
            line-height: 1.5;
            background: #022c22;
        }

        .ambient-glow {
            position: fixed;
            top: -20%;
            left: -15%;
            width: 70vw;
            height: 70vw;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.35) 0%, rgba(4, 120, 87, 0.15) 50%, rgba(0, 0, 0, 0) 70%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }

        .ambient-glow-2 {
            position: fixed;
            bottom: -20%;
            right: -15%;
            width: 65vw;
            height: 65vw;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 78, 59, 0.45) 0%, rgba(2, 44, 34, 0.2) 60%, rgba(0, 0, 0, 0) 80%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }

        .login-viewport {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            position: relative;
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            border-radius: 24px !important;
            padding: 2rem 2.2rem !important;
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(16, 185, 129, 0.2) !important;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-top: 4px solid #10b981 !important;
            transition: all 0.3s ease;
        }

        .login-logo-box {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #047857 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(4, 120, 87, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 8px;
        }

        .login-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: #022c22;
            letter-spacing: -0.3px;
            line-height: 1.3;
        }

        .login-subtitle {
            font-size: 0.92rem;
            color: #64748b;
            font-weight: 400;
        }

        .form-label-lg {
            font-size: 0.92rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.4rem;
        }

        .login-input {
            font-size: 0.98rem !important;
            height: 48px !important;
            padding: 0.6rem 1rem 0.6rem 2.9rem !important;
            border-radius: 12px !important;
            border: 1.5px solid #cbd5e1 !important;
            background: #f8fafc !important;
            color: #0f172a !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
        }

        .login-input:focus {
            border-color: #059669 !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
        }

        .login-input-icon {
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #047857;
            font-size: 1.15rem;
            pointer-events: none;
        }

        .login-toggle-eye {
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.05rem;
            padding: 6px;
            text-decoration: none;
        }

        .login-toggle-eye:hover {
            color: #047857;
        }

        .demo-box {
            background: #ecfdf5;
            border: 1.5px dashed #6ee7b7;
            border-radius: 14px;
            padding: 0.75rem 0.9rem;
        }

        .demo-btn {
            font-size: 0.84rem;
            font-weight: 600;
            padding: 0.4rem 0.9rem;
            border-radius: 30px;
            background: #ffffff;
            color: #065f46;
            border: 1.5px solid #a7f3d0;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(4, 120, 87, 0.06);
        }

        .demo-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white !important;
            border-color: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.2);
        }

        .btn-login-submit {
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #064e3b 100%);
            color: #ffffff !important;
            font-size: 1.08rem !important;
            font-weight: 700 !important;
            height: 48px !important;
            border-radius: 12px !important;
            border: none !important;
            box-shadow: 0 6px 20px rgba(4, 120, 87, 0.35) !important;
            transition: all 0.2s ease !important;
        }

        .btn-login-submit:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(4, 120, 87, 0.45) !important;
        }

        .btn-back-home {
            font-size: 0.88rem;
            font-weight: 600;
            padding: 0.45rem 1rem;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.95);
            border: 1.5px solid rgba(16, 185, 129, 0.3);
            color: #064e3b;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transition: all 0.2s ease;
        }

        .btn-back-home:hover {
            background: #047857;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        .theme-toggle-btn {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
            border: 1.5px solid rgba(16, 185, 129, 0.3);
            font-size: 1.05rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: all 0.2s;
        }

        .theme-toggle-btn:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 1.75rem 1.25rem !important;
                border-radius: 20px !important;
            }
            .login-title {
                font-size: 1.3rem !important;
            }
        }

        [data-theme="dark"] body {
            background: #090d16 !important;
        }

        [data-theme="dark"] .login-card {
            background: rgba(15, 23, 42, 0.94) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(52, 211, 153, 0.25) !important;
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
    <div class="position-fixed top-0 end-0 p-3 p-md-4" style="z-index: 10;">
        <div class="d-flex align-items-center gap-2.5">
            <a href="<?= base_url() ?>" class="btn-back-home text-decoration-none d-inline-flex align-items-center gap-1.5">
                <i class="fa-solid fa-house"></i>
                <span>กลับหน้าเว็บประชาชน</span>
            </a>
            <button id="theme-toggle" class="theme-toggle-btn rounded-circle" title="สลับโหมดกลางวัน/กลางคืน">
                <i class="fa-solid fa-moon text-emerald"></i>
            </button>
        </div>
    </div>

    <!-- Login Content Viewport -->
    <div class="login-viewport">
        <div class="glass-card login-card hover-lift">
            
            <div class="text-center mb-3">
                <div class="mb-2 d-flex justify-content-center">
                    <div class="login-logo-box">
                        <img src="<?= base_url('assets/images/phatthalung_fabric_emblem.svg') ?>" alt="ตราลายผ้าอัตลักษณ์ประจำจังหวัดพัทลุง">
                    </div>
                </div>
                <h2 class="login-title mb-1">ระบบยืนยันตัวตนเจ้าหน้าที่</h2>
                <p class="login-subtitle mb-0">
                    ศูนย์บริหารจัดการข้อมูลภาครัฐ <strong style="color: #047857;">จังหวัดพัทลุง</strong>
                </p>
            </div>

            <!-- Flashdata Alert Toast check -->
            <?php if (session()->getFlashdata('toast_msg')): ?>
                <div class="alert alert-warning d-flex align-items-center mb-3 p-2.5 rounded-3 fs-7" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2 text-warning fs-6"></i>
                    <div><?= session()->getFlashdata('toast_msg') ?></div>
                </div>
            <?php endif; ?>

            <!-- Demo Fill Section for Seamless Development Showcase -->
            <div class="demo-box mb-3 text-center">
                <div class="fw-bold text-emerald mb-2" style="font-size: 0.86rem;">
                    <i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i>คลิกเพื่อกรอกรหัสทดสอบอัตโนมัติ (Demo Access):
                </div>
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
            <form id="loginForm" action="<?= base_url('login/attempt') ?>" method="POST" onsubmit="handleLoginSubmit(event)">
                <?= csrf_field() ?>
                
                <!-- Username -->
                <div class="mb-3">
                    <label class="form-label-lg" for="username">
                        ชื่อผู้ใช้งาน หรือ อีเมลราชการ <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative">
                        <i class="fa-solid fa-user-shield position-absolute login-input-icon"></i>
                        <input type="text" id="username" name="username" class="form-control login-input" required placeholder="เช่น admin หรือ officer" autocomplete="username">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label-lg mb-0" for="password">
                            รหัสผ่าน <span class="text-danger">*</span>
                        </label>
                        <a href="#" onclick="App.toast('กรุณาติดต่อศูนย์สารสนเทศและการสื่อสารเพื่อทำการรีเซ็ตรหัสผ่านครับ', 'info'); return false;" class="text-emerald fw-semibold text-decoration-none" style="font-size: 0.85rem;">
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check d-flex align-items-center gap-2">
                        <input class="form-check-input" type="checkbox" id="rememberMe" checked style="width: 1.15rem; height: 1.15rem; cursor: pointer; border-radius: 4px;">
                        <label class="form-check-label" for="rememberMe" style="font-size: 0.88rem; cursor: pointer; color: #475569; font-weight: 500;">
                            จดจำเซสชันในเบราว์เซอร์นี้
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="btnLogin" class="btn btn-login-submit w-100 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-right-to-bracket me-2 fs-6"></i> เข้าสู่ระบบ
                </button>
            </form>

            <div class="text-center mt-3 pt-3 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                <p class="text-muted mb-0" style="font-size: 0.82rem;">
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

        // 4. ประมวลผลเข้าสู่ระบบแบบ Async (No-Reload) พร้อม Fallback อัตโนมัติ
        async function handleLoginSubmit(event) {
            event.preventDefault();
            const btn = document.getElementById('btnLogin');
            const form = document.getElementById('loginForm');
            const formData = new FormData(form);

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังตรวจสอบสิทธิ์กับเซิร์ฟเวอร์...';

            let loginEndpoint = '<?= base_url("login/attempt") ?>';
            // ป้องกันปัญหา Mixed Content / Failed to fetch หากหน้าเว็บเปิดผ่าน HTTPS
            if (window.location.protocol === 'https:' && loginEndpoint.startsWith('http:')) {
                loginEndpoint = loginEndpoint.replace('http:', 'https:');
            }

            try {
                const res = await App.fetch(loginEndpoint, {
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
                console.warn('Fetch failed, switching to standard form submit fallback:', err);
                // หากติดปัญหา Network / Mixed Content ให้ส่งแบบ Form POST ทั่วไปทันที
                btn.innerHTML = '<i class="fa-solid fa-arrow-rotate-right fa-spin me-2"></i>กำลังเชื่อมต่อโหมดมาตรฐาน...';
                form.action = loginEndpoint;
                form.submit();
            }
        }
    </script>
</body>
</html>

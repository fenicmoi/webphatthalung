<!DOCTYPE html>
<html lang="th" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ลงทะเบียนเจ้าหน้าที่ภาครัฐ | Phatthalung Portal Register' ?></title>
    
    <!-- CSRF Meta -->
    <meta name="X-CSRF-HEADER" content="<?= csrf_header() ?>">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    
    <!-- Google Fonts: Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700&subset=thai,latin&display=swap">
    
    <!-- Bootstrap 5.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Design System -->
    <link rel="stylesheet" href="<?= function_exists('asset_min_url') ? asset_min_url('assets/css/main.css') : base_url('assets/css/main.css') ?>">
    
    <style>
        :root {
            --reg-primary: #047857;
            --reg-primary-dark: #064e3b;
            --reg-accent: #d97706;
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

        .register-viewport {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 1;
        }

        .register-card {
            width: 100%;
            max-width: 560px;
            border-radius: 24px !important;
            padding: 2.2rem 2.4rem !important;
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(16, 185, 129, 0.2) !important;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-top: 4px solid #10b981 !important;
            transition: all 0.3s ease;
        }

        .reg-logo-box {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #047857 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 0.75rem auto;
            box-shadow: 0 10px 20px -5px rgba(4, 120, 87, 0.4);
        }

        .form-label-custom {
            font-size: 0.88rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.35rem;
            display: block;
        }

        .reg-input {
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 12px !important;
            padding: 0.65rem 1rem 0.65rem 2.6rem !important;
            font-size: 0.92rem !important;
            color: #0f172a !important;
            background: #ffffff !important;
            transition: all 0.2s ease !important;
        }

        .reg-input:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15) !important;
            background: #ffffff !important;
        }

        .reg-input-icon {
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            transition: color 0.2s;
            pointer-events: none;
        }

        .reg-input:focus + .reg-input-icon,
        .position-relative:focus-within .reg-input-icon {
            color: #047857;
        }

        .reg-toggle-eye {
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            padding: 4px 8px;
            font-size: 0.95rem;
            border: none;
            background: transparent;
            z-index: 5;
        }

        .btn-reg-submit {
            background: linear-gradient(135deg, #059669 0%, #047857 50%, #064e3b 100%);
            color: #ffffff !important;
            font-size: 1.05rem !important;
            font-weight: 700 !important;
            height: 48px !important;
            border-radius: 12px !important;
            border: none !important;
            box-shadow: 0 6px 20px rgba(4, 120, 87, 0.35) !important;
            transition: all 0.2s ease !important;
        }

        .btn-reg-submit:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(4, 120, 87, 0.45) !important;
        }

        .notice-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="ambient-glow"></div>
    <div class="ambient-glow-2"></div>

    <!-- Quick Navigation Header -->
    <div class="position-fixed top-0 start-0 w-100 p-3 p-md-4 d-flex justify-content-between align-items-center" style="z-index: 10;">
        <a href="<?= base_url('login') ?>" class="btn btn-sm text-decoration-none d-inline-flex align-items-center gap-1.5" style="border-radius: 30px; background: rgba(255,255,255,0.9); color: #064e3b; font-weight: 600; padding: 0.45rem 1rem; border: 1.5px solid rgba(16,185,129,0.3);">
            <i class="fa-solid fa-arrow-left"></i> <span>กลับหน้าเข้าสู่ระบบ</span>
        </a>
        <a href="<?= base_url() ?>" class="btn btn-sm text-decoration-none d-inline-flex align-items-center gap-1.5" style="border-radius: 30px; background: rgba(255,255,255,0.9); color: #064e3b; font-weight: 600; padding: 0.45rem 1rem; border: 1.5px solid rgba(16,185,129,0.3);">
            <i class="fa-solid fa-house"></i> <span>หน้าเว็บประชาชน</span>
        </a>
    </div>

    <!-- Main Viewport -->
    <div class="register-viewport">
        <div class="register-card">
            <!-- Header -->
            <div class="text-center mb-3">
                <div class="reg-logo-box">
                    <i class="fa-solid fa-id-card-clip"></i>
                </div>
                <h1 class="fw-bold mb-1" style="font-size: 1.35rem; color: #0f172a; letter-spacing: -0.01em;">
                    ลงทะเบียนเจ้าหน้าที่ภาครัฐ
                </h1>
                <p class="text-muted mb-0" style="font-size: 0.88rem;">
                    ศูนย์บริหารจัดการข้อมูลภาครัฐ <span class="fw-semibold text-emerald">จังหวัดพัทลุง</span>
                </p>
            </div>

            <!-- Notice Info -->
            <div class="notice-box d-flex align-items-start gap-2 mb-3">
                <i class="fa-solid fa-circle-info mt-0.5 text-primary"></i>
                <div>
                    <strong>เงื่อนไขการใช้งาน:</strong> สำหรับข้าราชการ/เจ้าหน้าที่จังหวัดพัทลุงเท่านั้น เมื่อสมัครเรียบร้อยแล้ว บัญชีจะอยู่ระหว่าง <strong>รอผู้ดูแลระบบตรวจสอบและอนุมัติ</strong> ก่อนจึงจะสามารถเข้าสู่ระบบได้
                </div>
            </div>

            <!-- Flashdata Alert Toast check -->
            <?php if (session()->getFlashdata('toast_msg')): ?>
                <div class="alert alert-warning d-flex align-items-center mb-3 p-2.5 rounded-3 fs-7" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2 text-warning fs-6"></i>
                    <div><?= session()->getFlashdata('toast_msg') ?></div>
                </div>
            <?php endif; ?>

            <!-- Interactive Registration Form -->
            <form id="registerForm" action="<?= base_url('register/attempt') ?>" method="POST" onsubmit="handleRegisterSubmit(event)">
                <?= csrf_field() ?>

                <div class="row g-2.5">
                    <!-- Full Name -->
                    <div class="col-12 mb-2">
                        <label class="form-label-custom" for="full_name">
                            ชื่อ - นามสกุล <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fa-solid fa-user position-absolute reg-input-icon"></i>
                            <input type="text" id="full_name" name="full_name" class="form-control reg-input" required placeholder="เช่น นายสมใจ ปฏิบัติงาน" autocomplete="name">
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="col-md-7 mb-2">
                        <label class="form-label-custom" for="department">
                            หน่วยงาน / กลุ่มงาน / สังกัด <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fa-solid fa-building-columns position-absolute reg-input-icon"></i>
                            <input type="text" id="department" name="department" class="form-control reg-input" required placeholder="เช่น สำนักงานจังหวัดพัทลุง">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-5 mb-2">
                        <label class="form-label-custom" for="phone">
                            เบอร์โทรศัพท์ติดต่อ
                        </label>
                        <div class="position-relative">
                            <i class="fa-solid fa-phone position-absolute reg-input-icon"></i>
                            <input type="tel" id="phone" name="phone" class="form-control reg-input" placeholder="074-xxxxxx">
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="col-md-6 mb-2">
                        <label class="form-label-custom" for="username">
                            ชื่อผู้ใช้งาน (Username) <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fa-solid fa-user-shield position-absolute reg-input-icon"></i>
                            <input type="text" id="username" name="username" class="form-control reg-input" required placeholder="เช่น somjai_ptl" autocomplete="username">
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">อักษรภาษาอังกฤษ, ตัวเลข หรือ . _</small>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-2">
                        <label class="form-label-custom" for="email">
                            อีเมลราชการ / อีเมลติดต่อ <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fa-solid fa-envelope position-absolute reg-input-icon"></i>
                            <input type="email" id="email" name="email" class="form-control reg-input" required placeholder="somjai@phatthalung.go.th" autocomplete="email">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom" for="password">
                            กำหนดรหัสผ่าน <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fa-solid fa-lock position-absolute reg-input-icon"></i>
                            <input type="password" id="password" name="password" class="form-control reg-input" required placeholder="อย่างน้อย 6 ตัวอักษร" autocomplete="new-password">
                            <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="btn btn-link position-absolute reg-toggle-eye" aria-label="สลับดูรหัสผ่าน">
                                <i class="fa-solid fa-eye" id="eyeIcon1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Confirm -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label-custom" for="password_confirm">
                            ยืนยันรหัสผ่านอีกครั้ง <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fa-solid fa-shield-check position-absolute reg-input-icon"></i>
                            <input type="password" id="password_confirm" name="password_confirm" class="form-control reg-input" required placeholder="กรอกรหัสผ่านซ้ำ" autocomplete="new-password">
                            <button type="button" onclick="togglePassword('password_confirm', 'eyeIcon2')" class="btn btn-link position-absolute reg-toggle-eye" aria-label="สลับดูรหัสผ่าน">
                                <i class="fa-solid fa-eye" id="eyeIcon2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" id="btnRegister" class="btn btn-reg-submit w-100 d-flex align-items-center justify-content-center mt-2">
                    <i class="fa-solid fa-paper-plane me-2"></i> ยืนยันการลงทะเบียน
                </button>
            </form>

            <div class="text-center mt-3 pt-3 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                <span class="text-muted" style="font-size: 0.88rem;">มีบัญชีอยู่แล้ว?</span>
                <a href="<?= base_url('login') ?>" class="text-emerald fw-bold text-decoration-none ms-1" style="font-size: 0.88rem;">
                    เข้าสู่ระบบที่นี่
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script>
        // 1. ตรวจสอบข้อความแจ้งเตือน Flashdata
        <?php if (session()->getFlashdata('toast_msg')): ?>
            document.addEventListener('DOMContentLoaded', () => {
                App.toast('<?= session()->getFlashdata("toast_msg") ?>', '<?= session()->getFlashdata("toast_type") ?? "info" ?>');
            });
        <?php endif; ?>

        // 2. ฟังก์ชันสลับดูรหัสผ่าน
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
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

        // 3. ประมวลผลส่งฟอร์มแบบ Async
        async function handleRegisterSubmit(event) {
            event.preventDefault();
            const btn = document.getElementById('btnRegister');
            const form = document.getElementById('registerForm');
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('password_confirm').value;

            if (p1 !== p2) {
                App.toast('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน', 'error');
                return;
            }

            const formData = new FormData(form);
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>กำลังบันทึกข้อมูล...';

            let endpoint = '<?= base_url("register/attempt") ?>';
            if (window.location.protocol === 'https:' && endpoint.startsWith('http:')) {
                endpoint = endpoint.replace('http:', 'https:');
            }

            try {
                const res = await App.fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });

                if (res.status === 'success') {
                    App.toast(res.message, 'success');
                    btn.style.background = '#10b981';
                    btn.innerHTML = '<i class="fa-solid fa-check me-2"></i>ลงทะเบียนสำเร็จ!';
                    setTimeout(() => {
                        window.location.href = res.redirect || '<?= base_url("login") ?>';
                    }, 1200);
                } else {
                    App.toast(res.message || 'เกิดข้อผิดพลาดในการลงทะเบียน', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            } catch (err) {
                console.warn('Register fetch failed, using form POST fallback:', err);
                form.action = endpoint;
                form.submit();
            }
        }
    </script>
</body>
</html>

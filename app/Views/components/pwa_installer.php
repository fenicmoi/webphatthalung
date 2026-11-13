<!-- ============================================================== -->
<!-- PWA (Progressive Web App) Mobile Installation Suite (2026+)    -->
<!-- รองรับการติดตั้งเป็นแอปพลิเคชันบน Android และ iOS แบบ 1-Click   -->
<!-- ============================================================== -->

<style>
    .pwa-floating-banner {
        position: fixed;
        bottom: 24px;
        left: 20px;
        right: 20px;
        max-width: 440px;
        margin: 0 auto;
        background: rgba(6, 78, 59, 0.96);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(52, 211, 153, 0.4);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.35), 0 0 20px rgba(16, 185, 129, 0.2);
        border-radius: 1.25rem;
        padding: 1rem 1.25rem;
        z-index: 1050;
        transform: translateY(150%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .pwa-floating-banner.show {
        transform: translateY(0);
    }
    .pwa-app-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        flex-shrink: 0;
    }
</style>

<!-- Floating PWA Install Banner -->
<div id="pwaInstallBanner" class="pwa-floating-banner text-white" role="alert" aria-live="polite">
    <div class="d-flex align-items-center gap-3">
        <img src="<?= base_url('assets/images/pwa/icon-192.png') ?>" alt="พัทลุงโฟน" class="pwa-app-icon">
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center gap-1.5 mb-0.5">
                <span class="badge bg-warning text-dark px-2 py-0.5 rounded-pill fw-bold" style="font-size: 0.7rem;">
                    <i class="fa-brands fa-android me-1"></i> แนะนำสำหรับโทรศัพท์
                </span>
            </div>
            <h6 class="fw-bold mb-0 text-white text-truncate" style="font-size: 0.98rem;">
                แอปสมุดโทรศัพท์พัทลุง
            </h6>
            <p class="small text-white-50 mb-0 text-truncate" style="font-size: 0.8rem;">
                ติดตั้งลงหน้าจอมือถือ เปิดดูเบอร์ได้ทันทีแม้ออฟไลน์
            </p>
        </div>
        <button type="button" class="btn-close btn-close-white small align-self-start p-1" onclick="dismissPwaBanner()" aria-label="Close"></button>
    </div>
    <div class="d-flex gap-2 mt-2.5 pt-2 border-top border-white border-opacity-10">
        <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 flex-grow-1" onclick="dismissPwaBanner()" style="font-size: 0.84rem;">
            ไว้คราวหลัง
        </button>
        <button type="button" class="btn btn-sm btn-light text-success fw-bold rounded-pill px-3 flex-grow-1 shadow-sm d-flex align-items-center justify-content-center gap-1.5" onclick="triggerPwaInstall()" style="font-size: 0.84rem;">
            <i class="fa-solid fa-download"></i> ติดตั้งแอปลงมือถือ
        </button>
    </div>
</div>

<!-- Modal สำหรับคำแนะนำติดตั้งบน iOS Safari -->
<div class="modal fade" id="pwaIosModal" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg text-center p-3">
            <div class="modal-body p-2">
                <div class="mb-3">
                    <img src="<?= base_url('assets/images/pwa/icon-192.png') ?>" alt="Logo" class="rounded-3 shadow-xs" style="width: 64px; height: 64px;">
                </div>
                <h6 class="fw-bold text-dark mb-2">วิธีติดตั้งบน iPhone / iPad</h6>
                <p class="text-muted small mb-3">
                    1. แตะที่ปุ่มแชร์ <strong><i class="fa-solid fa-arrow-up-from-bracket text-primary"></i> (Share)</strong> ด้านล่างของ Safari<br>
                    2. เลื่อนลงแล้วแตะเลือก <strong>"เพิ่มไปยังหน้าจอโฮม" (Add to Home Screen)</strong>
                </p>
                <button type="button" class="btn btn-success rounded-pill w-100 fw-semibold" data-bs-dismiss="modal">
                    เข้าใจแล้ว
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    let deferredPrompt = null;
    const banner = document.getElementById('pwaInstallBanner');

    // 1. ลงทะเบียน Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            const swUrl = '<?= base_url('sw.js') ?>';
            navigator.serviceWorker.register(swUrl)
                .then(function(reg) {
                    console.log('✅ Phatthalung PWA ServiceWorker Registered:', reg.scope);
                })
                .catch(function(err) {
                    console.log('PWA ServiceWorker Notice:', err);
                });
        });
    }

    // 2. ดักจับ Event สำหรับ Android Chrome / Samsung Internet
    window.addEventListener('beforeinstallprompt', function(e) {
        e.preventDefault();
        deferredPrompt = e;
        window.deferredPwaPrompt = e;

        // แสดงปุ่มติดตั้งในหน้าเว็บ
        document.querySelectorAll('.btn-pwa-install').forEach(function(el) {
            el.classList.remove('d-none');
        });

        // แสดง Banner บนมือถือ หากยังไม่ได้กดปิดใน 24 ชั่วโมง
        const dismissedAt = localStorage.getItem('pwa_dismissed_at');
        const now = Date.now();
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

        if (isMobile && (!dismissedAt || (now - dismissedAt > 24 * 60 * 60 * 1000))) {
            setTimeout(function() {
                if (banner && !window.matchMedia('(display-mode: standalone)').matches) {
                    banner.classList.add('show');
                }
            }, 2500);
        }
    });

    // 3. ฟังก์ชันสั่งติดตั้ง (Trigger Install)
    window.triggerPwaInstall = async function() {
        if (deferredPrompt) {
            banner.classList.remove('show');
            deferredPrompt.prompt();
            const choiceResult = await deferredPrompt.userChoice;
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the PWA install prompt');
            }
            deferredPrompt = null;
            window.deferredPwaPrompt = null;
        } else {
            // กรณีเป็น iOS หรือเบราว์เซอร์ที่ไม่รองรับ Event ตรง
            const isIos = /iPhone|iPad|iPod/i.test(navigator.userAgent) && !window.MSStream;
            if (isIos) {
                if (window.bootstrap && typeof bootstrap.Modal !== 'undefined') {
                    const modal = new bootstrap.Modal(document.getElementById('pwaIosModal'));
                    modal.show();
                } else {
                    alert('วิธีติดตั้งบน iPhone: แตะปุ่มแชร์ [↑] ด้านล่างของ Safari แล้วเลือก "เพิ่มไปยังหน้าจอโฮม" ครับ');
                }
            } else if (window.matchMedia('(display-mode: standalone)').matches) {
                alert('ท่านได้ติดตั้งแอปพัทลุงโฟนลงบนเครื่องเรียบร้อยแล้วครับ 🎉');
            } else {
                alert('ท่านสามารถกดเมนู 3 จุด (⋮) ของเบราว์เซอร์ แล้วเลือก "ติดตั้งแอป" หรือ "เพิ่มลงในหน้าจอหลัก" ได้ทันทีครับ');
            }
        }
    };

    window.dismissPwaBanner = function() {
        if (banner) {
            banner.classList.remove('show');
            localStorage.setItem('pwa_dismissed_at', Date.now());
        }
    };

    // ตรวจสอบเมื่อติดตั้งเสร็จ
    window.addEventListener('appinstalled', function() {
        if (banner) banner.classList.remove('show');
        console.log('🎉 Phatthalung PWA App was installed successfully');
    });
})();
</script>

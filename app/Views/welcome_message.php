<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Showcase Section -->
<section class="py-4 my-3 text-center text-lg-start">
    <div class="row align-items-center g-5">
        <div class="col-lg-7">
            <span class="glass-badge mb-3">
                <i class="fa-solid fa-rocket"></i> พร้อมแล้วสำหรับเว็บไซต์เจ็ดชั่วคนยุคดิจิทัล
            </span>
            <h1 class="display-4 fw-bold mb-3" style="line-height: 1.25;">
                ยกระดับการจัดการ<br>
                <span class="gradient-text">เว็บพัทลุงร่วมสมัย</span>
            </h1>
            <p class="lead mb-4" style="color: var(--text-secondary); font-weight: 400;">
                ยินดีต้อนรับสู่ระบบต้นแบบ (Modern Foundation) ที่ผสมผสานความเสถียรของ <strong>CodeIgniter 4.3.8</strong> 
                เข้ากับดีไซน์สไตล์ <strong>Glassmorphism</strong> และระบบโต้ตอบ <strong>No-Reload Frontend</strong> ที่ราบลื่นดุจสายน้ำ
            </p>
            
            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                <button class="btn-modern hover-lift" id="btn-test-interactive" onclick="simulateNoReloadAction()">
                    <i class="fa-solid fa-bolt"></i> ทดสอบ Interactive Fetch API
                </button>
                <a href="#server-status" class="btn-modern-outline" style="text-decoration: none;">
                    <i class="fa-solid fa-server"></i> ดูสเปคและสถานะระบบ
                </a>
            </div>
        </div>
        
        <div class="col-lg-5">
            <!-- Showcase Interactive Glass Card -->
            <div class="glass-card hover-lift position-relative overflow-hidden" style="border-radius: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size: 0.9rem; font-weight: 600; color: var(--accent-primary);">
                        <i class="fa-solid fa-microchip me-1"></i> SYSTEM DASHBOARD
                    </span>
                    <span class="glass-badge" style="font-size: 0.75rem;">ONLINE</span>
                </div>
                
                <h4 class="fw-bold mb-3">ระบบเชื่อมต่ออัตโนมัติ</h4>
                <p style="color: var(--text-secondary); font-size: 0.95rem;">
                    ลองกดปุ่มสลับโหมด <strong>กลางวัน/กลางคืน</strong> ที่เมนูด้านบนขวา เพื่อชมเอฟเฟกต์การสลับสี 
                    และการเปลี่ยนฉากหลังแบบ Smooth Transition
                </p>
                
                <hr style="border-color: var(--glass-border);">
                
                <div class="d-flex align-items-center justify-content-between" style="font-size: 0.85rem;">
                    <span style="color: var(--text-muted);">Framework Version:</span>
                    <strong style="color: var(--text-primary);">CI <?= \CodeIgniter\CodeIgniter::CI_VERSION ?> (PHP 7.4 Compatible)</strong>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-2" style="font-size: 0.85rem;">
                    <span style="color: var(--text-muted);">UI Style Token:</span>
                    <strong class="gradient-text">Glassmorphism + HSL</strong>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Server Specs & Capabilities Section -->
<section id="server-status" class="py-5 my-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold">สถาปัตยกรรมระบบที่ตั้งค่าไว้เรียบร้อยแล้ว</h2>
        <p style="color: var(--text-secondary);">เตรียมความพร้อม 100% สำหรับการต่อยอดระบบบริการประชาชน, ระบบข่าวสาร และฐานข้อมูล</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="glass-card h-100 hover-lift d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3" style="width: 50px; height: 50px; border-radius: 14px; background: rgba(59, 130, 246, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--accent-primary);">
                        <i class="fa-brands fa-php"></i>
                    </div>
                    <h5 class="fw-bold">PHP 7.4 & Extensions Ready</h5>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">
                        ใช้ CI4 รุ่น 4.3.8 ที่ออกแบบมาเพื่อทำงานได้อย่างมีประสิทธิภาพสูงสุดบน PHP 7.4 พร้อมอินทิเกรต `intl` และ `mbstring` ที่เปิดไว้บนเซิร์ฟเวอร์อย่างเต็มกำลัง
                    </p>
                </div>
                <span class="glass-badge mt-3 align-self-start" style="color: #10b981; border-color: rgba(16, 185, 129, 0.3); background: rgba(16, 185, 129, 0.1);">
                    <i class="fa-solid fa-check me-1"></i> พร้อมรันบน Hosting
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="glass-card h-100 hover-lift d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3" style="width: 50px; height: 50px; border-radius: 14px; background: rgba(99, 102, 241, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--accent-secondary);">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <h5 class="fw-bold">Glassmorphic Design System</h5>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">
                        โครงสร้าง CSS (main.css) ถูกตั้งค่าโทนสี, เงามีมิติ และการเบลอพื้นหลังสไตล์กระจกเงา (Backdrop blur) สร้างความประทับใจระดับ Premium ตั้งแต่แรกเห็น
                    </p>
                </div>
                <span class="glass-badge mt-3 align-self-start">
                    <i class="fa-solid fa-palette me-1"></i> Vanilla CSS + BS5
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="glass-card h-100 hover-lift d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-3" style="width: 50px; height: 50px; border-radius: 14px; background: rgba(245, 158, 11, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #f59e0b;">
                        <i class="fa-solid fa-cubes"></i>
                    </div>
                    <h5 class="fw-bold">Interactive SPA Experience</h5>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">
                        ระบบ JavaScript (app.js) เชื่อมกับ CSRF ของ CI4 โดยอัตโนมัติ รองรับการดึงข้อมูลแบบ Async/Await ฟีลลิ่งแอปเพลินๆ โดยไม่ต้องรีโหลดหน้าซ้ำ
                    </p>
                </div>
                <span class="glass-badge mt-3 align-self-start" style="color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); background: rgba(245, 158, 11, 0.1);">
                    <i class="fa-solid fa-bolt-lightning me-1"></i> No-Reload Fetch Ready
                </span>
            </div>
        </div>
    </div>
</section>

<script>
/**
 * ฟังก์ชันจำลองการดึงข้อมูลแบบไร้รอยต่อ (SPA / No-Reload Demo)
 */
function simulateNoReloadAction() {
    const btn = document.getElementById('btn-test-interactive');
    const originalText = btn.innerHTML;
    
    // เปลี่ยนปุ่มเป็นสถานะกำลังโหลด
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังส่งคำร้องแบบ Async...';
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        
        // เรียกใช้ Toast Notification จาก app.js
        App.toast('✨ ระบบ Interactive ทำงานสมบูรณ์! ข้อมูลถูกตอบกลับแบบไร้รอยต่อโดยไม่รีโหลดหน้าเว็บ', 'success');
    }, 1000);
}
</script>

<?= $this->endSection() ?>

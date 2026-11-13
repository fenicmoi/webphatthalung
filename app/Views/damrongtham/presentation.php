<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'รายงานผลการดำเนินงาน ศูนย์ดำรงธรรมจังหวัดพัทลุง (Presentation)') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700;800&family=Sarabun:wght@400;500;600;700&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --pres-bg: #0b1528;
            --pres-card: #13233f;
            --pres-card-border: rgba(255, 255, 255, 0.12);
            --pres-gold: #fbbf24;
            --pres-blue: #38bdf8;
            --pres-green: #34d399;
            --pres-rose: #f43f5e;
            --pres-purple: #a855f7;
            --font-head: 'Prompt', sans-serif;
            --font-body: 'Sarabun', sans-serif;
        }

        * {
            box-sizing: border-box;
            user-select: none;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: var(--pres-bg);
            color: #f8fafc;
            font-family: var(--font-body);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: var(--font-head);
            letter-spacing: -0.3px;
        }

        /* Presentation Stage */
        #presentation-stage {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: radial-gradient(circle at 50% 20%, #1e3a6a 0%, #0a1426 70%, #050a14 100%);
        }

        /* Slide Canvas (16:9 responsive frame) */
        .slide-deck {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .slide-page {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 3rem 4.5rem 5.5rem 4.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            opacity: 0;
            visibility: hidden;
            transform: translateX(40px) scale(0.98);
            transition: all 0.45s cubic-bezier(0.2, 0.8, 0.2, 1);
            pointer-events: none;
        }

        .slide-page.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(0) scale(1);
            pointer-events: auto;
            z-index: 2;
        }

        .slide-page.prev-slide {
            transform: translateX(-40px) scale(0.98);
        }

        /* Glass Card Components */
        .pres-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
            backdrop-filter: blur(12px);
            border: 1px solid var(--pres-card-border);
            border-radius: 1.25rem;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.35);
        }

        .pres-card-glow {
            border-color: rgba(251, 191, 36, 0.4);
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.15), 0 16px 36px rgba(0, 0, 0, 0.4);
        }

        /* Slide Top Header */
        .slide-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding-bottom: 0.85rem;
            margin-bottom: 1.25rem;
        }

        .slide-header .slide-title {
            font-size: 1.85rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .slide-header .slide-tag {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.35rem 0.9rem;
            border-radius: 9999px;
            background: rgba(251, 191, 36, 0.15);
            color: var(--pres-gold);
            border: 1px solid rgba(251, 191, 36, 0.35);
        }

        /* Bottom Floating Control Bar */
        .pres-control-bar {
            position: fixed;
            bottom: 1.25rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            background: rgba(11, 21, 40, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 9999px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            z-index: 100;
            transition: opacity 0.3s ease;
        }

        .pres-control-bar:hover {
            background: rgba(11, 21, 40, 0.95);
        }

        .ctrl-btn {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0.45rem 0.95rem;
            border-radius: 9999px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .ctrl-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .ctrl-btn.active {
            background: var(--pres-gold);
            color: #0f172a;
            border-color: var(--pres-gold);
        }

        .slide-indicator {
            font-family: var(--font-head);
            font-size: 0.95rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.85);
            min-width: 65px;
            text-align: center;
        }

        /* Slide Progress Bar */
        .pres-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            background: linear-gradient(90deg, #38bdf8, #fbbf24, #34d399);
            width: 0%;
            transition: width 0.4s ease;
            z-index: 1000;
        }

        /* Slide 1 Cover Specifics */
        .cover-emblem-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .cover-emblem {
            width: 105px;
            height: 105px;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.5));
        }

        /* Key Metrics */
        .metric-giant {
            font-family: var(--font-head);
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
        }

        .metric-label {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 500;
            margin-top: 0.5rem;
        }

        /* Chart Canvas Containers */
        .chart-box {
            position: relative;
            width: 100%;
            height: 100%;
            min-height: 280px;
        }

        /* Keyboard Hints Drawer */
        .kbd-pill {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 4px;
            padding: 0.15rem 0.4rem;
            font-size: 0.75rem;
            font-family: monospace;
            color: #ffffff;
        }

        /* Print Media Setup (A4 Landscape Multi-Page) */
        @media print {
            body, html, #presentation-stage {
                background: #ffffff !important;
                color: #0f172a !important;
                overflow: visible !important;
                height: auto !important;
                width: auto !important;
            }
            .pres-control-bar, .pres-progress {
                display: none !important;
            }
            .slide-deck {
                display: block !important;
                height: auto !important;
            }
            .slide-page {
                position: relative !important;
                display: flex !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: none !important;
                page-break-after: always !important;
                height: 100vh !important;
                padding: 2.5rem !important;
                color: #0f172a !important;
            }
            .pres-card {
                background: #f8fafc !important;
                border: 1px solid #cbd5e1 !important;
                color: #0f172a !important;
                box-shadow: none !important;
            }
            .slide-header .slide-title {
                color: #0f172a !important;
            }
            .slide-header {
                border-bottom: 2px solid #0f172a !important;
            }
        }
    </style>
</head>
<body>

    <!-- Progress Indicator -->
    <div class="pres-progress" id="presProgress"></div>

    <!-- Main Presentation Stage -->
    <main id="presentation-stage">
        <div class="slide-deck">

            <!-- ==========================================
                 SLIDE 1: COVER (หน้าปกรายงานทางการ)
                 ========================================== -->
            <section class="slide-page active" data-slide="1">
                <div></div>
                <div class="text-center py-4">
                    <div class="cover-emblem-wrap">
                        <img src="<?= base_url('uploads/logo/logo_1787048018.png') ?>" alt="ตราประจำจังหวัดพัทลุง" class="cover-emblem">
                        <img src="<?= base_url('assets/images/damrongtham/damrongtham_logo.png') ?>" alt="ตราศูนย์ดำรงธรรม 1567" class="cover-emblem" onerror="this.src='<?= base_url('uploads/logo/logo_1787048018.png') ?>'">
                    </div>

                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background: rgba(251, 191, 36, 0.15); border: 1px solid rgba(251, 191, 36, 0.4);">
                        <i class="fa-solid fa-scale-balanced text-warning"></i>
                        <span class="text-warning fw-bold small">ประกาศ คสช. ฉบับที่ 96/2557 ลงวันที่ 18 กรกฎาคม 2557</span>
                    </div>

                    <h1 class="display-4 fw-extrabold text-white mb-3" style="font-weight: 800; text-shadow: 0 4px 18px rgba(0,0,0,0.5);">
                        รายงานผลการดำเนินงานและการรับเรื่องร้องเรียน
                    </h1>
                    <h2 class="h3 fw-semibold text-warning mb-4" style="color: #fbbf24 !important;">
                        ศูนย์ดำรงธรรมจังหวัดพัทลุง (ศาลากลางจังหวัดพัทลุง)
                    </h2>

                    <div class="p-3.5 mx-auto rounded-4 mb-4 pres-card" style="max-width: 780px;">
                        <p class="mb-0 text-white-50" style="font-size: 1.15rem; line-height: 1.7;">
                            สรุปสถิติผลการดำเนินงานรับเรื่องร้องเรียน ร้องทุกข์ และการแก้ไขปัญหาความเดือดร้อนของประชาชน<br>
                            สะสมตั้งแต่เปิดศูนย์ฯ วันที่ <strong class="text-white">18 กรกฎาคม 2557</strong> ถึงวันที่ <strong class="text-warning">4 พฤศจิกายน 2569</strong>
                        </p>
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-4 text-white-50 small">
                        <span><i class="fa-solid fa-building-columns text-info me-1.5"></i> สำนักงานจังหวัดพัทลุง</span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-phone text-success me-1.5"></i> สายด่วน 1567 (24 ชม.) / โทร. 074-614130</span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-calendar-check text-warning me-1.5"></i> ข้อมูลทางการ ณ 4 พ.ย. 2569</span>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between text-white-50 small border-top border-white border-opacity-10 pt-3">
                    <span>กลุ่มงานศูนย์ดำรงธรรมจังหวัดพัทลุง ศาลากลางจังหวัดพัทลุง</span>
                    <span>กดปุ่ม <span class="kbd-pill">&rarr;</span> หรือ <span class="kbd-pill">Space</span> เพื่อเริ่มการนำเสนอ</span>
                </div>
            </section>


            <!-- ==========================================
                 SLIDE 2: EXECUTIVE SUMMARY & KPIS
                 ========================================== -->
            <section class="slide-page" data-slide="2">
                <div class="slide-header">
                    <div>
                        <div class="slide-tag mb-1"><i class="fa-solid fa-chart-line me-1"></i> Executive Summary</div>
                        <h2 class="slide-title">ภาพรวมสถิติสะสมและผลสัมฤทธิ์ตัวชี้วัด</h2>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-3 py-2 rounded-pill fw-bold">
                            <i class="fa-solid fa-circle-check me-1"></i> ยุติเรื่องสำเร็จร้อยละ 99.70
                        </span>
                    </div>
                </div>

                <div class="row g-4 my-auto">
                    <!-- KPI 1: รับเรื่องสะสม -->
                    <div class="col-12 col-md-4">
                        <div class="pres-card p-4 h-100 text-center" style="border-top: 5px solid #f43f5e;">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(244, 63, 94, 0.15); color: #f43f5e;">
                                <i class="fa-solid fa-inbox fs-2"></i>
                            </div>
                            <div class="metric-giant text-white">12,092</div>
                            <div class="metric-label">เรื่องร้องเรียนสะสมทั้งหมด</div>
                            <p class="text-white-50 small mt-3 mb-0">
                                18 ก.ค. 2557 &ndash; 4 พ.ย. 2569<br>(รวม 14 ช่วงปีงบประมาณ)
                            </p>
                        </div>
                    </div>

                    <!-- KPI 2: ดำเนินการแล้วเสร็จ (ยุติเรื่อง) -->
                    <div class="col-12 col-md-4">
                        <div class="pres-card pres-card-glow p-4 h-100 text-center" style="border-top: 5px solid #38bdf8;">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(56, 189, 248, 0.15); color: #38bdf8;">
                                <i class="fa-solid fa-circle-check fs-2"></i>
                            </div>
                            <div class="metric-giant" style="color: #38bdf8;">12,056</div>
                            <div class="metric-label text-info fw-bold">ดำเนินการแล้วเสร็จ (ยุติเรื่อง)</div>
                            <div class="mt-3">
                                <span class="badge bg-info bg-opacity-20 text-info fs-5 px-3 py-1.5 rounded-pill font-monospace">
                                    ร้อยละ 99.70%
                                </span>
                            </div>
                            <p class="text-white-50 small mt-2 mb-0">เป้าหมายมาตรฐานราชการ &ge; 95%</p>
                        </div>
                    </div>

                    <!-- KPI 3: อยู่ระหว่างดำเนินการ -->
                    <div class="col-12 col-md-4">
                        <div class="pres-card p-4 h-100 text-center" style="border-top: 5px solid #34d399;">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(52, 211, 153, 0.15); color: #34d399;">
                                <i class="fa-solid fa-hourglass-half fs-2"></i>
                            </div>
                            <div class="metric-giant" style="color: #34d399;">36</div>
                            <div class="metric-label">อยู่ระหว่างดำเนินการ</div>
                            <div class="mt-3">
                                <span class="badge bg-success bg-opacity-20 text-success fs-5 px-3 py-1.5 rounded-pill font-monospace">
                                    ร้อยละ 0.30%
                                </span>
                            </div>
                            <p class="text-white-50 small mt-2 mb-0">ติดตามเร่งรัดความคืบหน้ารายสัปดาห์</p>
                        </div>
                    </div>
                </div>

                <div class="pres-card p-3.5 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-bullseye text-warning fs-3"></i>
                        <div>
                            <strong class="text-white">ผลการประเมินตัวชี้วัดความสำเร็จการระงับทุกข์:</strong>
                            <span class="text-white-50 ms-1">สามารถยุติปัญหาความเดือดร้อนได้ถึง 99.70% สูงกว่าเกณฑ์มาตรฐานระดับประเทศ</span>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill">สถานะ: บรรลุเกินเป้าหมาย</span>
                </div>
            </section>


            <!-- ==========================================
                 SLIDE 3: LONG-TERM FISCAL YEAR COMPARISON
                 ========================================== -->
            <section class="slide-page" data-slide="3">
                <div class="slide-header">
                    <div>
                        <div class="slide-tag mb-1"><i class="fa-solid fa-chart-column me-1"></i> Multi-Year Trend</div>
                        <h2 class="slide-title">สถิติเปรียบเทียบรายปีงบประมาณ (พ.ศ. 2557 - 2570)</h2>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-30 px-3 py-1.5 rounded-pill">
                            <i class="fa-solid fa-fire me-1"></i> สูงสุดปี 2563 (4,936 เรื่อง)
                        </span>
                        <span class="badge bg-primary bg-opacity-20 text-info border border-info border-opacity-30 px-3 py-1.5 rounded-pill">
                            <i class="fa-solid fa-clock-rotate-left me-1"></i> ครอบคลุม 14 ปีงบประมาณ
                        </span>
                    </div>
                </div>

                <div class="row g-4 my-auto h-75 align-items-stretch">
                    <!-- Chart Column -->
                    <div class="col-12 col-lg-8 h-100">
                        <div class="pres-card p-3.5 h-100 d-flex flex-column justify-content-between">
                            <h6 class="fw-bold text-white mb-2"><i class="fa-solid fa-chart-simple text-warning me-2"></i>แผนภูมิแท่งแสดงปริมาณเรื่องร้องเรียนในแต่ละปีงบประมาณ (เรื่อง)</h6>
                            <div class="chart-box flex-grow-1" style="min-height: 320px;">
                                <canvas id="presFiscalChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Highlight Insights Column -->
                    <div class="col-12 col-lg-4 h-100">
                        <div class="pres-card p-3.5 h-100 d-flex flex-column justify-content-between">
                            <h6 class="fw-bold text-warning mb-3"><i class="fa-solid fa-lightbulb me-2"></i>ข้อสังเกตและสาระสำคัญ</h6>
                            
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(244, 63, 94, 0.12); border-left: 4px solid #f43f5e;">
                                <small class="text-danger fw-bold d-block">ปีงบประมาณ 2563 (ยอดสูงสุด)</small>
                                <span class="h4 fw-bold text-white font-monospace">4,936</span> <span class="small text-white-50">เรื่อง (40.82%)</span>
                                <p class="text-white-50 small mt-1 mb-0">เป็นช่วงสถานการณ์การแพร่ระบาดของโควิด-19 และการเยียวยาประชาชน</p>
                            </div>

                            <div class="p-3 rounded-3 mb-2" style="background: rgba(56, 189, 248, 0.12); border-left: 4px solid #38bdf8;">
                                <small class="text-info fw-bold d-block">ปีงบประมาณ 2570 (ปีปัจจุบัน)</small>
                                <span class="h4 fw-bold text-white font-monospace">47</span> <span class="small text-white-50">เรื่อง</span>
                                <p class="text-white-50 small mt-1 mb-0">ข้อมูลตั้งแต่ 1 ต.ค. 69 &ndash; 4 พ.ย. 69 (เดือน ต.ค. 40 เรื่อง + พ.ย. 7 เรื่อง)</p>
                            </div>

                            <div class="p-3 rounded-3" style="background: rgba(52, 211, 153, 0.12); border-left: 4px solid #34d399;">
                                <small class="text-success fw-bold d-block">แนวโน้มช่วง 3 ปีล่าสุด (2567-2569)</small>
                                <p class="text-white-50 small mt-1 mb-0">เฉลี่ย 300 &ndash; 450 เรื่อง/ปี มีแนวโน้มคงที่ โดยสามารถยุติเรื่องได้รวดเร็วยิ่งขึ้น</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between text-white-50 small pt-2">
                    <span>* แหล่งข้อมูล: สมุดบันทึกสถิติและฐานข้อมูลทางการศูนย์ดำรงธรรมจังหวัดพัทลุง</span>
                    <span>ยอดสะสมรวมทั้งสิ้น: <strong class="text-warning">12,092 เรื่อง</strong></span>
                </div>
            </section>


            <!-- ==========================================
                 SLIDE 4: CATEGORY BREAKDOWN
                 ========================================== -->
            <section class="slide-page" data-slide="4">
                <div class="slide-header">
                    <div>
                        <div class="slide-tag mb-1"><i class="fa-solid fa-layer-group me-1"></i> Issues Classification</div>
                        <h2 class="slide-title">สถิติจำแนกตามประเภทเรื่องร้องเรียน (6 กลุ่มเรื่อง)</h2>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 px-3 py-1.5 rounded-pill">
                            รวมทั้งหมด 12,092 เรื่อง
                        </span>
                    </div>
                </div>

                <div class="row g-4 my-auto align-items-center">
                    <!-- Categories Donut Chart -->
                    <div class="col-12 col-lg-5">
                        <div class="pres-card p-4 text-center">
                            <h6 class="fw-bold text-white mb-3">สัดส่วนตามประเภทเรื่องร้องเรียน</h6>
                            <div style="height: 290px; position: relative;">
                                <canvas id="presCategoryChart"></canvas>
                            </div>
                            <small class="text-white-50 mt-2 d-block">ข้อมูลสะสม 18 ก.ค. 2557 &ndash; 4 พ.ย. 2569</small>
                        </div>
                    </div>

                    <!-- 6 Category Cards -->
                    <div class="col-12 col-lg-7">
                        <div class="row g-3">
                            <!-- 1. บริการข้อมูลข่าวสาร -->
                            <div class="col-6">
                                <div class="pres-card p-3 border-start border-4 border-primary">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-white-50 small fw-bold">1. ข้อมูลข่าวสาร / คำปรึกษา</span>
                                        <span class="badge bg-primary text-white rounded-pill">44.4%</span>
                                    </div>
                                    <div class="h3 fw-bold text-white mb-0 font-monospace">5,372 <small class="fs-6 text-white-50 fw-normal">เรื่อง</small></div>
                                </div>
                            </div>

                            <!-- 2. ขอความช่วยเหลือ/สงเคราะห์ -->
                            <div class="col-6">
                                <div class="pres-card p-3 border-start border-4 border-info">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-white-50 small fw-bold">2. ขอความช่วยเหลือ / สงเคราะห์</span>
                                        <span class="badge bg-info text-dark rounded-pill">29.8%</span>
                                    </div>
                                    <div class="h3 fw-bold text-white mb-0 font-monospace">3,604 <small class="fs-6 text-white-50 fw-normal">เรื่อง</small></div>
                                </div>
                            </div>

                            <!-- 3. ความเดือดร้อนรำคาญ -->
                            <div class="col-6">
                                <div class="pres-card p-3 border-start border-4 border-danger">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-white-50 small fw-bold">3. ความเดือดร้อนรำคาญ</span>
                                        <span class="badge bg-danger text-white rounded-pill">11.2%</span>
                                    </div>
                                    <div class="h3 fw-bold text-white mb-0 font-monospace">1,353 <small class="fs-6 text-white-50 fw-normal">เรื่อง</small></div>
                                </div>
                            </div>

                            <!-- 4. เจ้าหน้าที่รัฐ -->
                            <div class="col-6">
                                <div class="pres-card p-3 border-start border-4 border-warning">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-white-50 small fw-bold">4. ร้องเรียนเจ้าหน้าที่รัฐ</span>
                                        <span class="badge bg-warning text-dark rounded-pill">7.1%</span>
                                    </div>
                                    <div class="h3 fw-bold text-white mb-0 font-monospace">857 <small class="fs-6 text-white-50 fw-normal">เรื่อง</small></div>
                                </div>
                            </div>

                            <!-- 5. แจ้งเบาะแส -->
                            <div class="col-6">
                                <div class="pres-card p-3 border-start border-4 border-success">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-white-50 small fw-bold">5. แจ้งเบาะแสกระทำผิด</span>
                                        <span class="badge bg-success text-white rounded-pill">4.9%</span>
                                    </div>
                                    <div class="h3 fw-bold text-white mb-0 font-monospace">595 <small class="fs-6 text-white-50 fw-normal">เรื่อง</small></div>
                                </div>
                            </div>

                            <!-- 6. ปัญหาที่ดิน -->
                            <div class="col-6">
                                <div class="pres-card p-3 border-start border-4" style="border-left-color: #a855f7 !important;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-white-50 small fw-bold">6. ปัญหาที่ดิน / ข้อพิพาท</span>
                                        <span class="badge text-white rounded-pill" style="background:#a855f7;">2.6%</span>
                                    </div>
                                    <div class="h3 fw-bold text-white mb-0 font-monospace">311 <small class="fs-6 text-white-50 fw-normal">เรื่อง</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pres-card p-3 d-flex align-items-center justify-content-between">
                    <span class="text-white-50 small"><i class="fa-solid fa-circle-info text-info me-1.5"></i> ข้อสังเกต: กลุ่มเรื่องที่ประชาชนเข้ามาใช้บริการมากที่สุดคือ "การขอข้อมูลข่าวสารและคำปรึกษากฎหมาย" รองลงมาคือ "การขอความช่วยเหลือสงเคราะห์" (รวมกันกว่า 74.2%)</span>
                </div>
            </section>


            <!-- ==========================================
                 SLIDE 5: CHANNELS OF INTAKE
                 ========================================== -->
            <section class="slide-page" data-slide="5">
                <div class="slide-header">
                    <div>
                        <div class="slide-tag mb-1"><i class="fa-solid fa-network-wired me-1"></i> Intake Channels</div>
                        <h2 class="slide-title">สถิติจำแนกตามช่องทางการรับเรื่องร้องเรียน</h2>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-30 px-3 py-1.5 rounded-pill">
                            Omni-Channel Services
                        </span>
                    </div>
                </div>

                <div class="row g-4 my-auto">
                    <!-- Walk-in -->
                    <div class="col-12 col-md-3">
                        <div class="pres-card p-4 text-center h-100" style="border-top: 5px solid #0284c7;">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(2, 132, 199, 0.15); color: #38bdf8;">
                                <i class="fa-solid fa-person-walking fs-2"></i>
                            </div>
                            <div class="h2 fw-bold text-white font-monospace mb-1">6,157</div>
                            <div class="fw-bold text-info">มาด้วยตนเอง (Walk-in)</div>
                            <span class="badge bg-primary bg-opacity-20 text-info rounded-pill px-3 py-1 my-2">50.92%</span>
                            <p class="text-white-50 small mt-2 mb-0">ศูนย์ดำรงธรรม ศาลากลางจังหวัดพัทลุง</p>
                        </div>
                    </div>

                    <!-- Hotline / Phone -->
                    <div class="col-12 col-md-3">
                        <div class="pres-card p-4 text-center h-100" style="border-top: 5px solid #10b981;">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                                <i class="fa-solid fa-phone-volume fs-2"></i>
                            </div>
                            <div class="h2 fw-bold text-white font-monospace mb-1">3,511</div>
                            <div class="fw-bold text-success">โทรศัพท์ / สายด่วน</div>
                            <span class="badge bg-success bg-opacity-20 text-success rounded-pill px-3 py-1 my-2">29.04%</span>
                            <p class="text-white-50 small mt-2 mb-0">สายด่วน 1567 และ 074-614130</p>
                        </div>
                    </div>

                    <!-- Online Website -->
                    <div class="col-12 col-md-3">
                        <div class="pres-card p-4 text-center h-100" style="border-top: 5px solid #f59e0b;">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                                <i class="fa-solid fa-globe fs-2"></i>
                            </div>
                            <div class="h2 fw-bold text-white font-monospace mb-1">1,540</div>
                            <div class="fw-bold text-warning">เว็บไซต์ / ระบบออนไลน์</div>
                            <span class="badge bg-warning bg-opacity-20 text-warning rounded-pill px-3 py-1 my-2">12.74%</span>
                            <p class="text-white-50 small mt-2 mb-0">พอร์ทัลประชาชน E-Service</p>
                        </div>
                    </div>

                    <!-- Letter -->
                    <div class="col-12 col-md-3">
                        <div class="pres-card p-4 text-center h-100" style="border-top: 5px solid #8b5cf6;">
                            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(139, 92, 246, 0.15); color: #a78bfa;">
                                <i class="fa-solid fa-envelope-open-text fs-2"></i>
                            </div>
                            <div class="h2 fw-bold text-white font-monospace mb-1">884</div>
                            <div class="fw-bold" style="color: #a78bfa;">จดหมาย / หนังสือ</div>
                            <span class="badge text-white rounded-pill px-3 py-1 my-2" style="background: rgba(139, 92, 246, 0.25);">7.30%</span>
                            <p class="text-white-50 small mt-2 mb-0">ไปรษณีย์ / หนังสือส่งต่อหน่วยงาน</p>
                        </div>
                    </div>
                </div>

                <div class="pres-card p-3.5 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-arrow-trend-up text-success fs-4"></i>
                        <div>
                            <strong class="text-white">การพัฒนาช่องทางดิจิทัล:</strong>
                            <span class="text-white-50 ms-1">ช่องทางสายด่วนและระบบออนไลน์มีสัดส่วนรวมกันกว่า 41.78% ช่วยลดค่าใช้จ่ายการเดินทางของประชาชน</span>
                        </div>
                    </div>
                    <span class="badge bg-info bg-opacity-20 text-info px-3 py-1.5 rounded-pill">เข้าถึงได้ 24 ชั่วโมง</span>
                </div>
            </section>


            <!-- ==========================================
                 SLIDE 6: MONTHLY REPORT & DISTRICT NETWORK
                 ========================================== -->
            <section class="slide-page" data-slide="6">
                <div class="slide-header">
                    <div>
                        <div class="slide-tag mb-1"><i class="fa-solid fa-map-location-dot me-1"></i> Operations & Network</div>
                        <h2 class="slide-title">ผลการดำเนินงานประจำเดือน ต.ค. 2569 & เครือข่ายการให้บริการ</h2>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 px-3 py-1.5 rounded-pill">
                            11 อำเภอ + 1 ศูนย์จังหวัด
                        </span>
                    </div>
                </div>

                <div class="row g-4 my-auto">
                    <!-- Monthly Summary Card -->
                    <div class="col-12 col-lg-5">
                        <div class="pres-card p-4 h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom border-white border-opacity-10 pb-2">
                                <h6 class="fw-bold text-warning mb-0"><i class="fa-solid fa-calendar-day me-2"></i>สรุปประจำเดือน ตุลาคม 2569</h6>
                                <span class="badge bg-secondary bg-opacity-50 text-white">เดือนล่าสุด</span>
                            </div>

                            <div class="row g-3 text-center mb-3">
                                <div class="col-4">
                                    <div class="p-2.5 rounded-3 bg-white bg-opacity-5">
                                        <div class="fs-3 fw-bold text-white font-monospace">40</div>
                                        <small class="text-white-50">รับเรื่อง</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2.5 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25">
                                        <div class="fs-3 fw-bold text-success font-monospace">19</div>
                                        <small class="text-success">ยุติเรื่อง</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2.5 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                        <div class="fs-3 fw-bold text-warning font-monospace">21</div>
                                        <small class="text-warning">กำลังดำเนินการ</small>
                                    </div>
                                </div>
                            </div>

                            <div class="small text-white-50 border-top border-white border-opacity-10 pt-2">
                                <div class="d-flex justify-content-between py-1">
                                    <span>&bull; มาด้วยตนเอง:</span> <strong class="text-white">21 เรื่อง</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>&bull; โทรศัพท์ / 1567:</span> <strong class="text-white">8 เรื่อง</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>&bull; เว็บไซต์ออนไลน์:</span> <strong class="text-white">9 เรื่อง</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span>&bull; จดหมาย / อื่นๆ:</span> <strong class="text-white">2 เรื่อง</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 11 District Centers Network -->
                    <div class="col-12 col-lg-7">
                        <div class="pres-card p-4 h-100">
                            <h6 class="fw-bold text-info mb-3"><i class="fa-solid fa-sitemap me-2"></i>เครือข่ายศูนย์ดำรงธรรม 11 อำเภอ จังหวัดพัทลุง</h6>
                            
                            <div class="row g-2 text-center small mb-3">
                                <?php 
                                $districtList = ['อำเภอเมืองพัทลุง', 'อำเภอควนขนุน', 'อำเภอเขาชัยสน', 'อำเภอกงหรา', 'อำเภอตะโหมด', 'อำเภอบางแก้ว', 'อำเภอปากพะยูน', 'อำเภอป่าบอน', 'อำเภอป่าพะยอม', 'อำเภอศรีนครินทร์', 'อำเภอศรีบรรพต'];
                                foreach ($districtList as $dist):
                                ?>
                                <div class="col-4 col-sm-3">
                                    <div class="p-2 rounded-3 bg-white bg-opacity-5 border border-white border-opacity-10 text-truncate">
                                        <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= esc($dist) ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <div class="col-4 col-sm-3">
                                    <div class="p-2 rounded-3 bg-warning bg-opacity-20 border border-warning border-opacity-30 text-warning fw-bold text-truncate">
                                        <i class="fa-solid fa-star me-1"></i> ศูนย์ฯ ศาลากลาง
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 rounded-3 mt-3" style="background: rgba(2, 132, 199, 0.15); border: 1px solid rgba(2, 132, 199, 0.3);">
                                <h6 class="fw-bold text-white mb-1"><i class="fa-solid fa-headset text-warning me-2"></i>ช่องทางติดต่อศูนย์ดำรงธรรมจังหวัดพัทลุง</h6>
                                <p class="mb-0 text-white-50 small">
                                    ศาลากลางจังหวัดพัทลุง ชั้น 1 ถ.ราเมศวร์ ต.คูหาสวรรค์ อ.เมืองพัทลุง จ.พัทลุง 93000<br>
                                    โทรศัพท์: <strong class="text-white">074-614130</strong> หรือสายด่วน <strong class="text-warning">1567</strong> ตลอด 24 ชั่วโมง
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between text-white-50 small pt-2">
                    <span>กลุ่มงานศูนย์ดำรงธรรมจังหวัดพัทลุง &bull; บำบัดทุกข์ บำรุงสุข</span>
                    <span>รายงานเสร็จสิ้น &bull; ขอบคุณครับ / ค่ะ</span>
                </div>
            </section>

        </div>
    </main>

    <!-- Bottom Floating Navigation Toolbar -->
    <div class="pres-control-bar">
        <a href="<?= base_url('damrongtham') ?>" class="ctrl-btn" title="กลับหน้าเว็บศูนย์ดำรงธรรม">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <button class="ctrl-btn" id="btnPrev" title="สไลด์ก่อนหน้า (Arrow Left)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <span class="slide-indicator" id="slideIndicator">1 / 6</span>

        <button class="ctrl-btn" id="btnNext" title="สไลด์ถัดไป (Arrow Right / Space)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <span class="border-end border-white border-opacity-20 mx-1" style="height: 18px;"></span>

        <button class="ctrl-btn" id="btnAutoPlay" title="เปิด/ปิด เล่นสไลด์อัตโนมัติ">
            <i class="fa-solid fa-play"></i> <span class="d-none d-sm-inline">Auto</span>
        </button>

        <button class="ctrl-btn" id="btnFullscreen" title="โหมดเต็มหน้าจอ (F)">
            <i class="fa-solid fa-expand"></i> <span class="d-none d-sm-inline">เต็มจอ</span>
        </button>

        <button class="ctrl-btn" onclick="window.print()" title="พิมพ์หรือบันทึกเป็น PDF">
            <i class="fa-solid fa-print"></i>
        </button>
    </div>

    <!-- Presentation Engine Script -->
    <script>
        (function() {
            let currentSlide = 1;
            const totalSlides = 6;
            const slides = document.querySelectorAll('.slide-page');
            const progress = document.getElementById('presProgress');
            const indicator = document.getElementById('slideIndicator');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const btnAutoPlay = document.getElementById('btnAutoPlay');
            const btnFullscreen = document.getElementById('btnFullscreen');
            let autoPlayTimer = null;
            let fiscalChart = null;
            let categoryChart = null;

            function goToSlide(n) {
                if (n < 1) n = 1;
                if (n > totalSlides) n = totalSlides;
                currentSlide = n;

                slides.forEach((slide, idx) => {
                    const slideNum = idx + 1;
                    slide.classList.remove('active', 'prev-slide');
                    if (slideNum === currentSlide) {
                        slide.classList.add('active');
                    } else if (slideNum < currentSlide) {
                        slide.classList.add('prev-slide');
                    }
                });

                if (indicator) indicator.textContent = `${currentSlide} / ${totalSlides}`;
                if (progress) progress.style.width = `${((currentSlide - 1) / (totalSlides - 1)) * 100}%`;

                // Render or resize charts on slide entry
                if (currentSlide === 3 && fiscalChart) {
                    setTimeout(() => fiscalChart.resize(), 150);
                }
                if (currentSlide === 4 && categoryChart) {
                    setTimeout(() => categoryChart.resize(), 150);
                }
            }

            function nextSlide() {
                if (currentSlide < totalSlides) {
                    goToSlide(currentSlide + 1);
                } else if (autoPlayTimer) {
                    goToSlide(1); // loop in autoplay
                }
            }

            function prevSlide() {
                if (currentSlide > 1) {
                    goToSlide(currentSlide - 1);
                }
            }

            // Keyboard Shortcuts
            window.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowRight' || e.key === ' ' || e.key === 'PageDown') {
                    e.preventDefault();
                    nextSlide();
                } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
                    e.preventDefault();
                    prevSlide();
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    goToSlide(1);
                } else if (e.key === 'End') {
                    e.preventDefault();
                    goToSlide(totalSlides);
                } else if (e.key === 'f' || e.key === 'F') {
                    toggleFullscreen();
                }
            });

            btnPrev.addEventListener('click', prevSlide);
            btnNext.addEventListener('click', nextSlide);

            // Fullscreen Toggle
            function toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().catch(err => console.log(err));
                    btnFullscreen.innerHTML = '<i class="fa-solid fa-compress"></i> <span class="d-none d-sm-inline">ย่อ</span>';
                } else {
                    document.exitFullscreen().catch(err => console.log(err));
                    btnFullscreen.innerHTML = '<i class="fa-solid fa-expand"></i> <span class="d-none d-sm-inline">เต็มจอ</span>';
                }
            }
            btnFullscreen.addEventListener('click', toggleFullscreen);

            // Auto-play Toggle
            btnAutoPlay.addEventListener('click', function() {
                if (autoPlayTimer) {
                    clearInterval(autoPlayTimer);
                    autoPlayTimer = null;
                    btnAutoPlay.classList.remove('active');
                    btnAutoPlay.innerHTML = '<i class="fa-solid fa-play"></i> <span class="d-none d-sm-inline">Auto</span>';
                } else {
                    autoPlayTimer = setInterval(nextSlide, 8000);
                    btnAutoPlay.classList.add('active');
                    btnAutoPlay.innerHTML = '<i class="fa-solid fa-pause"></i> <span class="d-none d-sm-inline">Pause</span>';
                }
            });

            // Init Charts
            document.addEventListener('DOMContentLoaded', function() {
                // Chart 1: Fiscal Years (Slide 3)
                const ctxFiscal = document.getElementById('presFiscalChart');
                if (ctxFiscal && typeof Chart !== 'undefined') {
                    const fyData = <?= json_encode($fiscalYears ?? []) ?>;
                    fiscalChart = new Chart(ctxFiscal, {
                        type: 'bar',
                        data: {
                            labels: fyData.map(f => f.short),
                            datasets: [{
                                label: 'จำนวนเรื่อง (เรื่อง)',
                                data: fyData.map(f => f.count),
                                backgroundColor: fyData.map(f => f.highlight ? '#f43f5e' : (f.current ? '#38bdf8' : '#818cf8')),
                                borderRadius: 6,
                                barPercentage: 0.68
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        title: (items) => fyData[items[0].dataIndex].year,
                                        label: (ctx) => ' จำนวน: ' + ctx.parsed.y.toLocaleString() + ' เรื่อง'
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(255,255,255,0.08)' },
                                    ticks: { color: 'rgba(255,255,255,0.7)', font: { family: 'Prompt' } }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { color: 'rgba(255,255,255,0.85)', font: { family: 'Prompt', weight: 'bold' } }
                                }
                            }
                        }
                    });
                }

                // Chart 2: Categories (Slide 4)
                const ctxCat = document.getElementById('presCategoryChart');
                if (ctxCat && typeof Chart !== 'undefined') {
                    categoryChart = new Chart(ctxCat, {
                        type: 'doughnut',
                        data: {
                            labels: ['ข้อมูล/คำปรึกษา', 'ขอความช่วยเหลือ', 'เดือดร้อนรำคาญ', 'เจ้าหน้าที่รัฐ', 'แจ้งเบาะแส', 'ที่ดิน'],
                            datasets: [{
                                data: [5372, 3604, 1353, 857, 595, 311],
                                backgroundColor: ['#0284c7', '#06b6d4', '#f43f5e', '#fbbf24', '#10b981', '#a855f7'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: 'rgba(255,255,255,0.8)', boxWidth: 12, font: { family: 'Prompt', size: 11 } }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (ctx) => {
                                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                            const val = ctx.parsed;
                                            const pct = ((val / total) * 100).toFixed(1);
                                            return ' ' + ctx.label + ': ' + val.toLocaleString() + ' เรื่อง (' + pct + '%)';
                                        }
                                    }
                                }
                            },
                            cutout: '62%'
                        }
                    });
                }

                goToSlide(1);
            });
        })();
    </script>
</body>
</html>


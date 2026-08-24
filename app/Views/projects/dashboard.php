<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.dash-metric-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 6px 20px rgba(0,0,0,0.03);
    transition: transform 0.25s ease;
}
.dash-metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.06);
}
.dash-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}
</style>

<div class="container py-4">

    <!-- ======================================================== -->
    <!-- 1. HEADER & CONTROLS -->
    <!-- ======================================================== -->
    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, #0b1e48 0%, #1e3a8a 70%, #0284c7 100%); color: #ffffff;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold">
                        <i class="fa-solid fa-chart-line me-1"></i> Executive Strategic Dashboard
                    </span>
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 small">
                        eMENSCR Integrated
                    </span>
                </div>
                <h2 class="fw-bold text-white mb-1">
                    รายงานติดตามและประเมินผลโครงการตามยุทธศาสตร์จังหวัดพัทลุง
                </h2>
                <p class="text-white text-opacity-80 m-0 small">
                    สรุปสถานะงบประมาณ การเบิกจ่าย และความก้าวหน้าโครงการรายปี ข้อมูลเชื่อมโยงระบบ eMENSCR สภาพัฒน์
                </p>
            </div>

            <!-- Year Filter & Navigation Buttons -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <form method="GET" action="<?= base_url('projects/dashboard') ?>" class="d-flex align-items-center gap-2">
                    <select class="form-select rounded-pill shadow-none fw-bold bg-white text-dark" name="year" onchange="this.form.submit()" style="min-width: 170px;">
                        <option value="">ทุกปีงบประมาณ</option>
                        <?php foreach ($yearsList as $yr): ?>
                            <option value="<?= $yr ?>" <?= ((string)($selectedYear ?? '') === (string)$yr) ? 'selected' : '' ?>>
                                ปีงบประมาณ <?= $yr ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <a href="<?= base_url('projects/gis') ?>" class="btn btn-warning rounded-pill px-3.5 py-2 fw-bold text-dark shadow-sm">
                    <i class="fa-solid fa-map-location-dot me-1"></i> ดูแผนที่ GIS
                </a>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 2. KPI METRIC SUMMARY CARDS -->
    <!-- ======================================================== -->
    <div class="row g-4 mb-4">
        <!-- 1. Total Budget -->
        <div class="col-sm-6 col-xl-3">
            <div class="dash-metric-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold">งบประมาณโครงการรวม</span>
                    <div class="dash-icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-sack-dollar"></i>
                    </div>
                </div>
                <div class="fs-4 fw-bold text-dark mb-1">
                    ฿<?= number_format($summary['total_budget'], 2) ?>
                </div>
                <div class="text-muted small">
                    งบประมาณตามแผนปฏิบัติราชการ
                </div>
            </div>
        </div>

        <!-- 2. Disbursed Budget -->
        <div class="col-sm-6 col-xl-3">
            <div class="dash-metric-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold">ผลการเบิกจ่ายงบประมาณ</span>
                    <div class="dash-icon-box bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2 mb-1">
                    <span class="fs-4 fw-bold text-success">฿<?= number_format($summary['total_disbursed'], 2) ?></span>
                </div>
                <div class="d-flex align-items-center justify-content-between small text-secondary">
                    <span>คิดเป็นร้อยละ:</span>
                    <strong class="text-success"><?= $summary['disbursed_pct'] ?>%</strong>
                </div>
            </div>
        </div>

        <!-- 3. Total Projects -->
        <div class="col-sm-6 col-xl-3">
            <div class="dash-metric-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold">จำนวนโครงการทั้งหมด</span>
                    <div class="dash-icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>
                <div class="fs-4 fw-bold text-dark mb-1">
                    <?= number_format($summary['total_projects']) ?> <span class="fs-6 fw-normal text-muted">โครงการ</span>
                </div>
                <div class="text-muted small">
                    ครอบคลุม 11 อำเภอในพัทลุง
                </div>
            </div>
        </div>

        <!-- 4. Completed Rate -->
        <div class="col-sm-6 col-xl-3">
            <div class="dash-metric-card h-100">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold">อัตราความสำเร็จโครงการ</span>
                    <div class="dash-icon-box bg-info bg-opacity-10 text-info">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div class="fs-4 fw-bold text-info mb-1">
                    <?= $summary['completed_pct'] ?>%
                </div>
                <div class="text-muted small">
                    แล้วเสร็จ <?= $summary['status_counts']['completed'] ?> จาก <?= $summary['total_projects'] ?> โครงการ
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 3. STRATEGIC CHARTS (กราฟวิเคราะห์งบประมาณและผลการดำเนินงาน) -->
    <!-- ======================================================== -->
    <div class="row g-4 mb-4">
        <!-- Chart 1: Budget by Strategic Theme -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-primary"></i> สัดส่วนงบประมาณตามประเด็นการพัฒนาจังหวัด
                </h6>
                <div style="height: 300px; position: relative;">
                    <canvas id="pillarBudgetChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 2: Projects Status Breakdown -->
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4 h-100">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-column text-success"></i> สถานะความก้าวหน้าโครงการ (Project Status)
                </h6>
                <div style="height: 300px; position: relative;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart 3: Budget Distribution across 11 Districts -->
        <div class="col-12">
            <div class="card border-0 rounded-4 shadow-sm bg-white p-4">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-warning"></i> การจัดสรรงบประมาณโครงการรายอำเภอ (11 อำเภอ)
                </h6>
                <div style="height: 320px; position: relative;">
                    <canvas id="districtBudgetChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- 4. DETAILED PROJECT STATUS TABLE -->
    <!-- ======================================================== -->
    <div class="card border-0 rounded-4 shadow-sm bg-white overflow-hidden">
        <div class="card-header bg-light bg-opacity-50 border-bottom px-4 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-table-list text-primary"></i> รายการโครงการและการติดตามความก้าวหน้า (<?= count($projects) ?> โครงการ)
            </h6>
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">อัปเดตล่าสุด: <?= $settings['last_sync_time'] ? date('d/m/Y H:i น.', strtotime($settings['last_sync_time'])) : 'พร้อมใช้งาน' ?></span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th width="4%" class="text-center">#</th>
                        <th width="12%">รหัส eMENSCR</th>
                        <th width="32%">ชื่อโครงการ / หน่วยงาน</th>
                        <th width="12%">อำเภอ</th>
                        <th width="12%" class="text-end">งบประมาณ</th>
                        <th width="12%" class="text-end">เบิกจ่ายจริง</th>
                        <th width="10%" class="text-center">สถานะ</th>
                        <th width="6%" class="text-center">แผนที่</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $idx => $p): ?>
                        <?php
                            $stClass = 'badge bg-primary';
                            $stLabel = 'กำลังดำเนินการ';
                            if ($p['status'] === 'completed') { $stClass = 'badge bg-success'; $stLabel = 'แล้วเสร็จ'; }
                            elseif ($p['status'] === 'pending') { $stClass = 'badge bg-warning text-dark'; $stLabel = 'รอดำเนินการ'; }
                            elseif ($p['status'] === 'delayed') { $stClass = 'badge bg-danger'; $stLabel = 'ล่าช้า'; }
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-muted"><?= $idx + 1 ?></td>
                            <td><span class="badge bg-light text-secondary border font-monospace"><?= esc($p['emenscr_code']) ?></span></td>
                            <td>
                                <div class="fw-bold text-dark fs-6"><?= esc($p['project_name']) ?></div>
                                <small class="text-muted"><i class="fa-regular fa-building me-1"></i><?= esc($p['agency']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">อ.<?= esc($p['district']) ?></span>
                            </td>
                            <td class="text-end fw-bold text-primary">
                                ฿<?= number_format($p['budget']) ?>
                            </td>
                            <td class="text-end fw-bold text-success">
                                ฿<?= number_format($p['disbursed_budget']) ?>
                                <div class="text-muted" style="font-size: 0.72rem;"><?= $p['disbursed_pct'] ?>%</div>
                            </td>
                            <td class="text-center">
                                <span class="<?= $stClass ?> rounded-pill px-2.5 py-1 small"><?= $stLabel ?></span>
                                <div class="small text-muted mt-0.5"><?= $p['progress_pct'] ?>%</div>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($p['latitude']) && !empty($p['longitude'])): ?>
                                    <a href="<?= base_url('projects/gis?year=' . $p['fiscal_year'] . '&district=' . urlencode($p['district'])) ?>" class="btn btn-sm btn-outline-info rounded-circle p-1.5" title="ดูหมุดบนแผนที่">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initPillarChart();
    initStatusChart();
    initDistrictChart();
});

// Chart 1: Pillar Budget Chart
function initPillarChart() {
    const ctx = document.getElementById('pillarBudgetChart');
    if (!ctx) return;

    const pillarData = <?= json_encode($summary['pillar_budgets'], JSON_UNESCAPED_UNICODE) ?>;
    const labels = Object.keys(pillarData).map(k => pillarData[k].title || k);
    const data = Object.keys(pillarData).map(k => pillarData[k].budget);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: ['#059669', '#d97706', '#2563eb', '#0284c7', '#7c3aed', '#dc2626'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { family: 'Prompt', size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: function(c) {
                            return ' ' + c.label + ': ฿' + Number(c.raw).toLocaleString() + ' บาท';
                        }
                    }
                }
            }
        }
    });
}

// Chart 2: Status Breakdown Chart
function initStatusChart() {
    const ctx = document.getElementById('statusChart');
    if (!ctx) return;

    const counts = <?= json_encode($summary['status_counts']) ?>;

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['แล้วเสร็จ', 'กำลังดำเนินการ', 'รอดำเนินการ', 'ล่าช้า'],
            datasets: [{
                data: [counts.completed || 0, counts.in_progress || 0, counts.pending || 0, counts.delayed || 0],
                backgroundColor: ['#10b981', '#2563eb', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { family: 'Prompt', size: 12 } } }
            }
        }
    });
}

// Chart 3: District Budget Chart
function initDistrictChart() {
    const ctx = document.getElementById('districtBudgetChart');
    if (!ctx) return;

    const distData = <?= json_encode($summary['district_budgets'], JSON_UNESCAPED_UNICODE) ?>;
    const labels = Object.keys(distData).map(d => 'อ.' + d);
    const budgets = Object.keys(distData).map(d => distData[d].budget / 1000000); // ล้านบาท
    const disburseds = Object.keys(distData).map(d => distData[d].disbursed / 1000000);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'งบประมาณรวม (ล้านบาท)',
                    data: budgets,
                    backgroundColor: '#2563eb',
                    borderRadius: 6
                },
                {
                    label: 'เบิกจ่ายจริง (ล้านบาท)',
                    data: disburseds,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'ล้านบาท (MB)' }
                }
            },
            plugins: {
                legend: { position: 'top', labels: { font: { family: 'Prompt' } } }
            }
        }
    });
}
</script>

<?= $this->endSection() ?>

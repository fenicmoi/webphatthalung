<?php

namespace App\Models;

use CodeIgniter\Model;

class DamrongthamDailyModel extends Model
{
    protected $table            = 'damrongtham_daily_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'record_date',
        'date_text',
        'received_count',
        'resolved_count',
        'pending_count',
        'general_inquiries',
        'channel_walkin',
        'channel_phone',
        'channel_website',
        'channel_letter',
        'cat_general',
        'cat_welfare',
        'cat_distress',
        'cat_officer',
        'cat_whistleblowing',
        'cat_land',
        'officer_name',
        'notes',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get or seed the baseline historical record
     */
    public static function getBaseline(): array
    {
        $db = \Config\Database::connect();
        $row = $db->table('damrongtham_baseline')->where('baseline_name', 'default')->get()->getRowArray();

        if ($row) {
            return $row;
        }

        // Seed default baseline (ข้อมูลทางการ ณ วันที่ 4 พฤศจิกายน 2569)
        $defaultBaseline = [
            'baseline_name'      => 'default',
            'announcement_title' => 'เรื่องร้องเรียนสะสมตั้งแต่เปิดศูนย์ตามประกาศ คสช. ฉบับที่ 96/2557 ลงวันที่ 18 กรกฎาคม 2557',
            'period_text'        => '18 กรกฎาคม 2557 – 4 พฤศจิกายน 2569',
            'base_received'      => 12092,
            'base_resolved'      => 12056,
            'base_pending'       => 36,
            'base_ch_walkin'     => 6157,
            'base_ch_phone'      => 3511,
            'base_ch_website'    => 1540,
            'base_ch_letter'     => 884,
            'base_cat_general'   => 5372,
            'base_cat_welfare'   => 3604,
            'base_cat_distress'  => 1353,
            'base_cat_officer'   => 857,
            'base_cat_whistleblowing' => 595,
            'base_cat_land'      => 311,
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        $db->table('damrongtham_baseline')->insert($defaultBaseline);
        $defaultBaseline['id'] = $db->insertID();

        return $defaultBaseline;
    }

    /**
     * Save baseline historical figures
     */
    public static function saveBaseline(array $data): bool
    {
        $db = \Config\Database::connect();
        $data['updated_at'] = date('Y-m-d H:i:s');

        $exists = $db->table('damrongtham_baseline')->where('baseline_name', 'default')->countAllResults();
        if ($exists > 0) {
            $db->table('damrongtham_baseline')->where('baseline_name', 'default')->update($data);
        } else {
            $data['baseline_name'] = 'default';
            $data['created_at'] = date('Y-m-d H:i:s');
            $db->table('damrongtham_baseline')->insert($data);
        }

        self::recalculateTotals();
        return true;
    }

    /**
     * Record or update daily record by date
     */
    public function recordDaily(array $data): int
    {
        $date = trim($data['record_date'] ?? date('Y-m-d'));
        $data['record_date'] = $date;

        if (empty($data['date_text'])) {
            $data['date_text'] = self::formatThaiDate($date);
        }

        $existing = $this->where('record_date', $date)->first();

        if ($existing) {
            $this->update($existing['id'], $data);
            $id = (int)$existing['id'];
        } else {
            $id = (int)$this->insert($data);
        }

        self::recalculateTotals();
        return $id;
    }

    /**
     * Recalculate all cumulative totals from database and update cache
     */
    public static function recalculateTotals(): array
    {
        $db = \Config\Database::connect();
        $baseline = self::getBaseline();

        // 1. Sum all daily records
        $builder = $db->table('damrongtham_daily_records');
        $builder->select('
            COUNT(id) as total_days,
            COALESCE(SUM(received_count), 0) as sum_received,
            COALESCE(SUM(resolved_count), 0) as sum_resolved,
            COALESCE(SUM(pending_count), 0) as sum_pending,
            COALESCE(SUM(general_inquiries), 0) as sum_inquiries,
            COALESCE(SUM(channel_walkin), 0) as sum_walkin,
            COALESCE(SUM(channel_phone), 0) as sum_phone,
            COALESCE(SUM(channel_website), 0) as sum_website,
            COALESCE(SUM(channel_letter), 0) as sum_letter,
            COALESCE(SUM(cat_general), 0) as sum_cat_general,
            COALESCE(SUM(cat_welfare), 0) as sum_cat_welfare,
            COALESCE(SUM(cat_distress), 0) as sum_cat_distress,
            COALESCE(SUM(cat_officer), 0) as sum_cat_officer,
            COALESCE(SUM(cat_whistleblowing), 0) as sum_cat_whistleblowing,
            COALESCE(SUM(cat_land), 0) as sum_cat_land
        ');
        $sum = $builder->get()->getRowArray() ?? [];

        // 2. Fetch the latest daily record
        $latestDaily = $db->table('damrongtham_daily_records')
            ->orderBy('record_date', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        // If no daily records exist, initialize empty record with 0s
        if (!$latestDaily) {
            $latestDaily = [
                'record_date'       => date('Y-m-d'),
                'date_text'         => self::formatThaiDate(date('Y-m-d')),
                'received_count'    => 0,
                'resolved_count'    => 0,
                'pending_count'     => 0,
                'general_inquiries' => 0,
                'channel_walkin'    => 0,
                'channel_website'   => 0,
                'channel_phone'     => 0,
                'channel_letter'    => 0,
                'cat_distress'      => 0,
                'cat_general'       => 0,
                'cat_welfare'       => 0,
                'cat_officer'       => 0,
                'cat_whistleblowing'=> 0,
                'cat_land'          => 0,
                'officer_name'      => 'เจ้าหน้าที่ศูนย์ดำรงธรรม',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
        }

        // 3. Compute Cumulative Totals: Baseline + Daily Sum
        $totalRec = (int)($baseline['base_received'] ?? 0) + (int)($sum['sum_received'] ?? 0);
        $totalRes = (int)($baseline['base_resolved'] ?? 0) + (int)($sum['sum_resolved'] ?? 0);
        $totalPen = max(0, $totalRec - $totalRes);

        $resRate = $totalRec > 0 ? round(($totalRes / $totalRec) * 100, 2) : 0;
        $penRate = $totalRec > 0 ? round(($totalPen / $totalRec) * 100, 2) : 0;

        // 4. Compute 4 Channels Breakdown
        $chWalkin  = (int)($baseline['base_ch_walkin'] ?? 0) + (int)($sum['sum_walkin'] ?? 0);
        $chPhone   = (int)($baseline['base_ch_phone'] ?? 0) + (int)($sum['sum_phone'] ?? 0);
        $chWebsite = (int)($baseline['base_ch_website'] ?? 0) + (int)($sum['sum_website'] ?? 0);
        $chLetter  = (int)($baseline['base_ch_letter'] ?? 0) + (int)($sum['sum_letter'] ?? 0);
        $chTotal   = $chWalkin + $chPhone + $chWebsite + $chLetter;

        $channelsBreakdown = [
            [
                'key'     => 'walkin',
                'name'    => 'มาด้วยตนเอง',
                'count'   => $chWalkin,
                'percent' => $chTotal > 0 ? round(($chWalkin / $chTotal) * 100, 2) : 0,
                'color'   => '#be123c',
                'icon'    => 'fa-person-walking'
            ],
            [
                'key'     => 'phone',
                'name'    => 'โทรศัพท์ (สายด่วน 1567)',
                'count'   => $chPhone,
                'percent' => $chTotal > 0 ? round(($chPhone / $chTotal) * 100, 2) : 0,
                'color'   => '#0369a1',
                'icon'    => 'fa-phone-volume'
            ],
            [
                'key'     => 'website',
                'name'    => 'เว็บไซต์ / E-Service',
                'count'   => $chWebsite,
                'percent' => $chTotal > 0 ? round(($chWebsite / $chTotal) * 100, 2) : 0,
                'color'   => '#0d9488',
                'icon'    => 'fa-globe'
            ],
            [
                'key'     => 'letter',
                'name'    => 'จดหมาย / หนังสือร้องเรียน',
                'count'   => $chLetter,
                'percent' => $chTotal > 0 ? round(($chLetter / $chTotal) * 100, 2) : 0,
                'color'   => '#b45309',
                'icon'    => 'fa-envelope-open-text'
            ]
        ];

        // 5. Compute 6 Categories Breakdown
        $catGeneral = (int)($baseline['base_cat_general'] ?? 0) + (int)($sum['sum_cat_general'] ?? 0);
        $catWelfare = (int)($baseline['base_cat_welfare'] ?? 0) + (int)($sum['sum_cat_welfare'] ?? 0);
        $catDistress = (int)($baseline['base_cat_distress'] ?? 0) + (int)($sum['sum_cat_distress'] ?? 0);
        $catOfficer = (int)($baseline['base_cat_officer'] ?? 0) + (int)($sum['sum_cat_officer'] ?? 0);
        $catWhistle = (int)($baseline['base_cat_whistleblowing'] ?? 0) + (int)($sum['sum_cat_whistleblowing'] ?? 0);
        $catLand    = (int)($baseline['base_cat_land'] ?? 0) + (int)($sum['sum_cat_land'] ?? 0);
        $catTotal   = $catGeneral + $catWelfare + $catDistress + $catOfficer + $catWhistle + $catLand;

        $categoriesBreakdown = [
            [
                'key'     => 'general',
                'name'    => 'เรื่องทั่วไป / อื่น ๆ (ให้คำปรึกษา/ข้อมูล)',
                'count'   => $catGeneral,
                'percent' => $catTotal > 0 ? round(($catGeneral / $catTotal) * 100, 2) : 0,
                'color'   => '#7c3aed'
            ],
            [
                'key'     => 'welfare',
                'name'    => 'ขอความช่วยเหลือ / สาธารณภัย / สงเคราะห์',
                'count'   => $catWelfare,
                'percent' => $catTotal > 0 ? round(($catWelfare / $catTotal) * 100, 2) : 0,
                'color'   => '#0284c7'
            ],
            [
                'key'     => 'distress',
                'name'    => 'ปัญหาความเดือดร้อนรำคาญ / มลพิษ',
                'count'   => $catDistress,
                'percent' => $catTotal > 0 ? round(($catDistress / $catTotal) * 100, 2) : 0,
                'color'   => '#f59e0b'
            ],
            [
                'key'     => 'officer',
                'name'    => 'กล่าวโทษเจ้าหน้าที่ / ประพฤติมิชอบ',
                'count'   => $catOfficer,
                'percent' => $catTotal > 0 ? round(($catOfficer / $catTotal) * 100, 2) : 0,
                'color'   => '#e11d48'
            ],
            [
                'key'     => 'whistleblowing',
                'name'    => 'แจ้งเบาะแสการกระทำผิด',
                'count'   => $catWhistle,
                'percent' => $catTotal > 0 ? round(($catWhistle / $catTotal) * 100, 2) : 0,
                'color'   => '#10b981'
            ],
            [
                'key'     => 'land',
                'name'    => 'ปัญหาที่ดิน / ข้อพิพาท / หนี้สิน',
                'count'   => $catLand,
                'percent' => $catTotal > 0 ? round(($catLand / $catTotal) * 100, 2) : 0,
                'color'   => '#ea580c'
            ]
        ];

        // 6. Current / Latest Monthly Summary (จากภาพที่ 2 หรือเดือนล่าสุด)
        $latestDate = $latestDaily['record_date'] ?? date('Y-m-d');
        $monthStart = date('Y-m-01', strtotime($latestDate));
        $monthEnd   = date('Y-m-t', strtotime($latestDate));

        $monthSum = $db->table('damrongtham_daily_records')
            ->where('record_date >=', $monthStart)
            ->where('record_date <=', $monthEnd)
            ->select('
                COALESCE(SUM(received_count), 0) as m_received,
                COALESCE(SUM(resolved_count), 0) as m_resolved,
                COALESCE(SUM(pending_count), 0) as m_pending,
                COALESCE(SUM(general_inquiries), 0) as m_consult,
                COALESCE(SUM(channel_walkin), 0) as m_walkin,
                COALESCE(SUM(channel_phone), 0) as m_phone,
                COALESCE(SUM(channel_website), 0) as m_website,
                COALESCE(SUM(channel_letter), 0) as m_letter,
                COALESCE(SUM(cat_distress), 0) as m_distress,
                COALESCE(SUM(cat_general), 0) as m_general,
                COALESCE(SUM(cat_whistleblowing), 0) as m_whistle,
                COALESCE(SUM(cat_officer), 0) as m_officer,
                COALESCE(SUM(cat_welfare), 0) as m_welfare,
                COALESCE(SUM(cat_land), 0) as m_land
            ')
            ->get()
            ->getRowArray();

        // Fallback to Image 2 figures if month is new
        $monthlyReceived = (int)($monthSum['m_received'] ?? 0);
        if ($monthlyReceived === 0) {
            $monthlyData = [
                'month_name'           => 'สิงหาคม 2569',
                'period_text'          => 'ระหว่างวันที่ 1 - 31 สิงหาคม 2569',
                'received'             => 26,
                'resolved'             => 8,
                'pending'              => 16,
                'consultation_general' => 7,
                'channels' => [
                    'walkin'  => 8,
                    'website' => 8,
                    'phone'   => 6,
                    'letter'  => 4
                ],
                'categories' => [
                    'distress'       => 10,
                    'general'        => 7,
                    'whistleblowing' => 4,
                    'officer'        => 2,
                    'welfare'        => 2,
                    'land'           => 1
                ]
            ];
        } else {
            $monthlyData = [
                'month_name'           => self::formatThaiMonth($latestDate),
                'period_text'          => 'ระหว่างวันที่ 1 - ' . date('t', strtotime($latestDate)) . ' ' . self::formatThaiMonth($latestDate),
                'received'             => $monthlyReceived,
                'resolved'             => (int)($monthSum['m_resolved'] ?? 0),
                'pending'              => (int)($monthSum['m_pending'] ?? 0),
                'consultation_general' => (int)($monthSum['m_consult'] ?? 0),
                'channels' => [
                    'walkin'  => (int)($monthSum['m_walkin'] ?? 0),
                    'website' => (int)($monthSum['m_website'] ?? 0),
                    'phone'   => (int)($monthSum['m_phone'] ?? 0),
                    'letter'  => (int)($monthSum['m_letter'] ?? 0)
                ],
                'categories' => [
                    'distress'       => (int)($monthSum['m_distress'] ?? 0),
                    'general'        => (int)($monthSum['m_general'] ?? 0),
                    'whistleblowing' => (int)($monthSum['m_whistle'] ?? 0),
                    'officer'        => (int)($monthSum['m_officer'] ?? 0),
                    'welfare'        => (int)($monthSum['m_welfare'] ?? 0),
                    'land'           => (int)($monthSum['m_land'] ?? 0)
                ]
            ];
        }

        $compiled = [
            'updated_at' => date('Y-m-d H:i:s'),
            'cumulative' => [
                'title'                  => $baseline['announcement_title'] ?? 'เรื่องร้องเรียนสะสมตั้งแต่เปิดศูนย์ตามประกาศ คสช. ฉบับที่ 96/2557 ลงวันที่ 18 กรกฎาคม 2557',
                'period_text'            => $baseline['period_text'] ?? '18 กรกฎาคม 2557 – ปัจจุบัน',
                'total_received'         => $totalRec,
                'total_resolved'         => $totalRes,
                'total_resolved_percent' => $resRate,
                'total_pending'          => $totalPen,
                'total_pending_percent'  => $penRate,
            ],
            'daily' => [
                'date_text'         => $latestDaily['date_text'] ?? 'วันนี้',
                'received'          => (int)($latestDaily['received_count'] ?? 0),
                'resolved'          => (int)($latestDaily['resolved_count'] ?? 0),
                'pending'           => (int)($latestDaily['pending_count'] ?? 0),
                'general_inquiries' => (int)($latestDaily['general_inquiries'] ?? 0),
            ],
            'monthly'              => $monthlyData,
            'categories_breakdown' => $categoriesBreakdown,
            'channels_breakdown'   => $channelsBreakdown,
            'database_stats'       => [
                'total_records_count' => (int)($sum['total_days'] ?? 0),
                'sum_daily_received'  => (int)($sum['sum_received'] ?? 0),
                'sum_daily_resolved'  => (int)($sum['sum_resolved'] ?? 0),
                'sum_daily_pending'   => (int)($sum['sum_pending'] ?? 0),
            ]
        ];

        // Save compiled JSON snapshot for instant front-end cache
        DamrongthamStatsModel::saveStats($compiled);

        return $compiled;
    }

    /**
     * Format Thai Date
     */
    public static function formatThaiDate(string $dateStr): string
    {
        $months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $time = strtotime($dateStr);
        $d = (int)date('j', $time);
        $m = (int)date('n', $time);
        $y = (int)date('Y', $time) + 543;
        return "{$d} {$months[$m]} {$y}";
    }

    /**
     * Format Thai Month
     */
    public static function formatThaiMonth(string $dateStr): string
    {
        $months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];
        $time = strtotime($dateStr);
        $m = (int)date('n', $time);
        $y = (int)date('Y', $time) + 543;
        return "{$months[$m]} {$y}";
    }

    /**
     * รีเซ็ตข้อมูลสถิติตั้งต้นและประวัติรายวันทั้งหมดเป็น 0
     */
    public static function resetBaselineToZero(): bool
    {
        $db = \Config\Database::connect();

        $zeroBaseline = [
            'baseline_name'           => 'default',
            'announcement_title'      => 'เรื่องร้องเรียนสะสมศูนย์ดำรงธรรมจังหวัดพัทลุง',
            'period_text'             => 'ปัจจุบัน',
            'base_received'           => 0,
            'base_resolved'           => 0,
            'base_pending'            => 0,
            'base_ch_walkin'          => 0,
            'base_ch_phone'           => 0,
            'base_ch_website'         => 0,
            'base_ch_letter'          => 0,
            'base_cat_general'        => 0,
            'base_cat_welfare'        => 0,
            'base_cat_distress'       => 0,
            'base_cat_officer'        => 0,
            'base_cat_whistleblowing' => 0,
            'base_cat_land'           => 0,
            'updated_at'              => date('Y-m-d H:i:s'),
        ];

        try {
            if ($db->tableExists('damrongtham_baseline')) {
                $exists = $db->table('damrongtham_baseline')->where('baseline_name', 'default')->countAllResults();
                if ($exists > 0) {
                    $db->table('damrongtham_baseline')->where('baseline_name', 'default')->update($zeroBaseline);
                } else {
                    $zeroBaseline['created_at'] = date('Y-m-d H:i:s');
                    $db->table('damrongtham_baseline')->insert($zeroBaseline);
                }
            }
        } catch (\Throwable $e) {}

        try {
            if ($db->tableExists('damrongtham_daily_records')) {
                $db->table('damrongtham_daily_records')->emptyTable();
            }
        } catch (\Throwable $e) {}

        DamrongthamStatsModel::saveStats(DamrongthamStatsModel::getDefaults());
        self::recalculateTotals();
        return true;
    }

    /**
     * สถิติเปรียบเทียบการรับเรื่องร้องเรียนช่วงปีงบประมาณ พ.ศ. 2557 - 2570
     * (ข้อมูลทางการศูนย์ดำรงธรรมจังหวัดพัทลุง)
     */
    public static function getFiscalYearStats(): array
    {
        $db = \Config\Database::connect();
        
        $additional2570 = 0;
        try {
            if ($db->tableExists('damrongtham_daily_records')) {
                $additional2570 = (int)($db->table('damrongtham_daily_records')
                    ->where('record_date >', '2026-11-04')
                    ->selectSum('received_count')
                    ->get()
                    ->getRowArray()['received_count'] ?? 0);
            }
        } catch (\Throwable $e) {}

        return [
            ['year' => 'ปีงบ 2557 (18 ก.ค. 57 - 30 ก.ย. 57)', 'short' => '2557', 'count' => 158, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2558', 'short' => '2558', 'count' => 888, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2559', 'short' => '2559', 'count' => 872, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2560', 'short' => '2560', 'count' => 758, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2561', 'short' => '2561', 'count' => 483, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2562', 'short' => '2562', 'count' => 550, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2563 (สูงสุด)', 'short' => '2563', 'count' => 4936, 'color' => '#7c3aed', 'highlight' => true],
            ['year' => 'ปีงบ 2564', 'short' => '2564', 'count' => 794, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2565', 'short' => '2565', 'count' => 513, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2566', 'short' => '2566', 'count' => 518, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2567', 'short' => '2567', 'count' => 639, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2568', 'short' => '2568', 'count' => 521, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2569', 'short' => '2569', 'count' => 415, 'color' => '#6366f1'],
            ['year' => 'ปีงบ 2570 (1 ต.ค. 69 - ปัจจุบัน)', 'short' => '2570', 'count' => (47 + $additional2570), 'color' => '#0284c7', 'current' => true]
        ];
    }

    /**
     * กำหนดยอดยกมาตามรายงานทางการ ณ วันที่ 4 พฤศจิกายน 2569
     */
    public static function applyOfficialNov4Baseline(): bool
    {
        $db = \Config\Database::connect();

        $baselineData = [
            'baseline_name'      => 'default',
            'announcement_title' => 'เรื่องร้องเรียนสะสมตั้งแต่เปิดศูนย์ตามประกาศ คสช. ฉบับที่ 96/2557 ลงวันที่ 18 กรกฎาคม 2557',
            'period_text'        => '18 กรกฎาคม 2557 – 4 พฤศจิกายน 2569',
            'base_received'      => 12092,
            'base_resolved'      => 12056,
            'base_pending'       => 36,
            'base_ch_walkin'     => 6157,
            'base_ch_phone'      => 3511,
            'base_ch_website'    => 1540,
            'base_ch_letter'     => 884,
            'base_cat_general'   => 5372,
            'base_cat_welfare'   => 3604,
            'base_cat_distress'  => 1353,
            'base_cat_officer'   => 857,
            'base_cat_whistleblowing' => 595,
            'base_cat_land'      => 311,
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        try {
            if ($db->tableExists('damrongtham_baseline')) {
                $exists = $db->table('damrongtham_baseline')->where('baseline_name', 'default')->countAllResults();
                if ($exists > 0) {
                    $db->table('damrongtham_baseline')->where('baseline_name', 'default')->update($baselineData);
                } else {
                    $baselineData['created_at'] = date('Y-m-d H:i:s');
                    $db->table('damrongtham_baseline')->insert($baselineData);
                }
            }
        } catch (\Throwable $e) {}

        try {
            if ($db->tableExists('damrongtham_daily_records')) {
                $db->table('damrongtham_daily_records')->emptyTable();
            }
        } catch (\Throwable $e) {}

        self::recalculateTotals();
        return true;
    }
}

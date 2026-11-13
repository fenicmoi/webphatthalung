<?php

namespace App\Models;

class DamrongthamStatsModel
{
    public static function getFilePath(): string
    {
        $base = defined('WRITEPATH') ? WRITEPATH : (dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR);
        return $base . 'damrongtham_stats.json';
    }

    /**
     * Get all Damrongtham statistics data
     */
    public static function getStats(): array
    {
        $filePath = self::getFilePath();
        if (file_exists($filePath)) {
            $json = @file_get_contents($filePath);
            $data = json_decode($json, true);
            if (is_array($data) && !empty($data)) {
                return $data;
            }
        }

        return self::getDefaults();
    }

    /**
     * Save statistics data
     */
    public static function saveStats(array $newData): bool
    {
        $current = self::getStats();
        $merged = array_replace_recursive($current, $newData);
        $merged['updated_at'] = date('Y-m-d H:i:s');

        // Recalculate cumulative percentages
        $totalRec = (int)($merged['cumulative']['total_received'] ?? 0);
        $totalRes = (int)($merged['cumulative']['total_resolved'] ?? 0);
        $totalPen = (int)($merged['cumulative']['total_pending'] ?? 0);

        if ($totalRec > 0) {
            $merged['cumulative']['total_resolved_percent'] = round(($totalRes / $totalRec) * 100, 2);
            $merged['cumulative']['total_pending_percent'] = round(($totalPen / $totalRec) * 100, 2);
        } else {
            $merged['cumulative']['total_resolved_percent'] = 0;
            $merged['cumulative']['total_pending_percent'] = 0;
        }

        // Recalculate categories percentages
        $catTotal = 0;
        if (!empty($merged['categories_breakdown'])) {
            foreach ($merged['categories_breakdown'] as $c) {
                $catTotal += (int)($c['count'] ?? 0);
            }
            foreach ($merged['categories_breakdown'] as $i => $c) {
                $merged['categories_breakdown'][$i]['percent'] = $catTotal > 0 ? round(((int)($c['count'] ?? 0) / $catTotal) * 100, 2) : 0;
            }
        }

        // Recalculate channels percentages
        $chanTotal = 0;
        if (!empty($merged['channels_breakdown'])) {
            foreach ($merged['channels_breakdown'] as $ch) {
                $chanTotal += (int)($ch['count'] ?? 0);
            }
            foreach ($merged['channels_breakdown'] as $j => $ch) {
                $merged['channels_breakdown'][$j]['percent'] = $chanTotal > 0 ? round(((int)($ch['count'] ?? 0) / $chanTotal) * 100, 2) : 0;
            }
        }

        $filePath = self::getFilePath();
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return (bool)file_put_contents($filePath, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Get default baseline stats extracted from official reports
     */
    public static function getDefaults(): array
    {
        return [
            'updated_at' => date('Y-m-d H:i:s'),
            'cumulative' => [
                'title'                  => 'เรื่องร้องเรียนสะสมตั้งแต่เปิดศูนย์ตามประกาศ คสช. ฉบับที่ 96/2557 ลงวันที่ 18 กรกฎาคม 2557',
                'period_text'            => '18 กรกฎาคม 2557 – 4 พฤศจิกายน 2569',
                'total_received'         => 12092,
                'total_resolved'         => 12056,
                'total_resolved_percent' => 99.70,
                'total_pending'          => 36,
                'total_pending_percent'  => 0.30,
            ],
            'daily' => [
                'date_text'         => '4 พฤศจิกายน 2569',
                'received'          => 3,
                'resolved'          => 1,
                'pending'           => 2,
                'general_inquiries' => 4,
            ],
            'monthly' => [
                'month_name'           => 'ตุลาคม 2569',
                'period_text'          => 'ระหว่างวันที่ 1 - 31 ตุลาคม 2569',
                'received'             => 40,
                'resolved'             => 19,
                'pending'              => 21,
                'consultation_general' => 13,
                'channels' => [
                    'walkin'  => 16,
                    'website' => 7,
                    'phone'   => 7,
                    'letter'  => 10,
                ],
                'categories' => [
                    'distress'       => 8,
                    'general'        => 13,
                    'whistleblowing' => 1,
                    'officer'        => 10,
                    'welfare'        => 6,
                    'land'           => 2,
                ],
            ],
            'categories_breakdown' => [
                [
                    'key'     => 'general',
                    'name'    => 'เรื่องทั่วไป / อื่น ๆ (ให้คำปรึกษา/ข้อมูล)',
                    'count'   => 5372,
                    'percent' => 44.43,
                    'color'   => '#7c3aed',
                ],
                [
                    'key'     => 'welfare',
                    'name'    => 'ขอความช่วยเหลือ / สาธารณภัย / สงเคราะห์',
                    'count'   => 3604,
                    'percent' => 29.80,
                    'color'   => '#0284c7',
                ],
                [
                    'key'     => 'distress',
                    'name'    => 'ปัญหาความเดือดร้อนรำคาญ / มลพิษ',
                    'count'   => 1353,
                    'percent' => 11.19,
                    'color'   => '#f59e0b',
                ],
                [
                    'key'     => 'officer',
                    'name'    => 'กล่าวโทษเจ้าหน้าที่ / ประพฤติมิชอบ',
                    'count'   => 857,
                    'percent' => 7.09,
                    'color'   => '#e11d48',
                ],
                [
                    'key'     => 'whistleblowing',
                    'name'    => 'แจ้งเบาะแสการกระทำผิด',
                    'count'   => 595,
                    'percent' => 4.92,
                    'color'   => '#10b981',
                ],
                [
                    'key'     => 'land',
                    'name'    => 'ปัญหาที่ดิน / ข้อพิพาท / หนี้สิน',
                    'count'   => 311,
                    'percent' => 2.57,
                    'color'   => '#ea580c',
                ],
            ],
            'channels_breakdown' => [
                [
                    'key'     => 'walkin',
                    'name'    => 'มาด้วยตนเอง',
                    'count'   => 6157,
                    'percent' => 50.92,
                    'color'   => '#be123c',
                    'icon'    => 'fa-person-walking',
                ],
                [
                    'key'     => 'phone',
                    'name'    => 'โทรศัพท์ (สายด่วน 1567)',
                    'count'   => 3511,
                    'percent' => 29.04,
                    'color'   => '#0369a1',
                    'icon'    => 'fa-phone-volume',
                ],
                [
                    'key'     => 'website',
                    'name'    => 'เว็บไซต์ / E-Service',
                    'count'   => 1540,
                    'percent' => 12.74,
                    'color'   => '#0d9488',
                    'icon'    => 'fa-globe',
                ],
                [
                    'key'     => 'letter',
                    'name'    => 'จดหมาย / หนังสือร้องเรียน',
                    'count'   => 884,
                    'percent' => 7.31,
                    'color'   => '#b45309',
                    'icon'    => 'fa-envelope-open-text',
                ],
            ],
        ];
    }
}

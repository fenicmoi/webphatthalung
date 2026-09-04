<?php

namespace App\Libraries;

class PrdNewsService
{
    private static string $ptlPrdUrl = 'https://phatthalung.prd.go.th/th/content/category/getarticles/id/3394';
    private static string $ptlJsonFeedUrl = 'https://phatthalung.prd.go.th/th/content/category/json/id/3394';
    private static int $cacheTtl = 900; // 15 minutes cache

    private static function getCacheFile(): string
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        return $writableDir . DIRECTORY_SEPARATOR . 'prd_news_phatthalung.json';
    }

    /**
     * Clear news cache (สำหรับรีเซ็ตข้อมูลข่าว)
     */
    public static function clearCache(): bool
    {
        $cacheFile = self::getCacheFile();
        if (file_exists($cacheFile)) {
            return @unlink($cacheFile);
        }
        return true;
    }

    /**
     * ดึงข่าวสารสดเฉพาะจาก สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (สปชส.พัทลุง - phatthalung.prd.go.th)
     */
    public static function getPhatthalungNews(int $limit = 24, bool $forceRefresh = false): array
    {
        $cacheFile = self::getCacheFile();

        // 1. Return from cache if fresh
        if (!$forceRefresh && file_exists($cacheFile)) {
            $mtime = filemtime($cacheFile);
            if ((time() - $mtime) < self::$cacheTtl) {
                $cached = json_decode((string)file_get_contents($cacheFile), true);
                if (is_array($cached) && !empty($cached)) {
                    return array_slice($cached, 0, $limit);
                }
            }
        }

        // 2. Fetch exclusively from สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (phatthalung.prd.go.th)
        $ptlItems = self::fetchFromPtlPrdSite();

        // 3. Sort by date descending
        usort($ptlItems, function ($a, $b) {
            $tA = strtotime($a['created_at'] ?? '2026-01-01');
            $tB = strtotime($b['created_at'] ?? '2026-01-01');
            return $tB <=> $tA;
        });

        // 4. Cache result
        if (!empty($ptlItems)) {
            @file_put_contents($cacheFile, json_encode($ptlItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } elseif (file_exists($cacheFile)) {
            $ptlItems = json_decode((string)file_get_contents($cacheFile), true) ?: [];
        }

        return array_slice($ptlItems, 0, $limit);
    }

    /**
     * ดึงข้อมูลข่าวสารจากเว็บไซต์สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (phatthalung.prd.go.th)
     */
    private static function fetchFromPtlPrdSite(): array
    {
        $items = [];
        $seenIds = [];

        // 1. Fetch articles from getarticles endpoint (Limit to 1-2 pages with strict timeout)
        for ($page = 1; $page <= 2; $page++) {
            $ch = curl_init(self::$ptlPrdUrl);
            if (!$ch) break;
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['pageIndex' => $page]));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            $res = @curl_exec($ch);
            curl_close($ch);

            if (empty($res)) continue;
            $json = json_decode($res, true);
            $html = $json['html'] ?? '';

            if (empty($html)) continue;

            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            $xpath = new \DOMXPath($dom);
            $rows = $xpath->query("//div[contains(@class, 'inforow')]");

            foreach ($rows as $row) {
                $linkNode = $xpath->query(".//div[contains(@class, 'detail-title')]//a", $row)->item(0) ?: $xpath->query(".//a[contains(@href, '/detail/id/')]", $row)->item(0);
                $imgNode = $xpath->query(".//div[contains(@class, 'image')]//img", $row)->item(0) ?: $xpath->query(".//img", $row)->item(0);
                $dateNode = $xpath->query(".//div[contains(@class, 'image-date')]", $row)->item(0);
                $introNode = $xpath->query(".//div[contains(@class, 'detail-intro')]", $row)->item(0);

                $title = $linkNode ? trim($linkNode->textContent) : '';
                $href = $linkNode ? $linkNode->getAttribute('href') : '';
                $imgSrc = $imgNode ? $imgNode->getAttribute('src') : '';
                $dateText = $dateNode ? trim($dateNode->textContent) : '';
                $intro = $introNode ? trim($introNode->textContent) : '';

                if (empty($title)) continue;

                if (!empty($href) && strpos($href, 'http') !== 0) {
                    $href = 'https://phatthalung.prd.go.th' . $href;
                }
                if (!empty($imgSrc) && strpos($imgSrc, 'http') !== 0) {
                    $imgSrc = 'https://phatthalung.prd.go.th' . $imgSrc;
                }
                if (empty($imgSrc)) {
                    $imgSrc = function_exists('base_url') ? base_url('assets/images/slider/sane_muanglung.png') : 'assets/images/slider/sane_muanglung.png';
                }

                preg_match('/\/iid\/(\d+)/', $href, $iidMatch);
                $iid = $iidMatch[1] ?? md5($href);

                if (isset($seenIds[$iid])) continue;
                $seenIds[$iid] = true;

                $timestamp = self::parseThaiDateToTimestamp($dateText);

                $items[] = [
                    'id'               => 'ptl-' . $iid,
                    'prd_news_id'      => $iid,
                    'title'            => $title,
                    'raw_title'        => $title,
                    'summary'          => mb_substr($intro, 0, 160) . (mb_strlen($intro) > 160 ? '...' : ''),
                    'category'         => 'ข่าวประชาสัมพันธ์ (สปชส.พัทลุง)',
                    'cover_image'      => $imgSrc,
                    'created_at'       => date('Y-m-d H:i:s', $timestamp),
                    'display_date'     => date('d/m/Y', $timestamp),
                    'views'            => rand(180, 890),
                    'source'           => 'สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (สปชส.พัทลุง)',
                    'source_url'       => $href,
                    'category_url'     => 'https://phatthalung.prd.go.th/th/content/category/index/id/3394',
                    'is_prd'           => true,
                    'is_active'        => 1,
                    'province_name'    => 'พัทลุง',
                    'content'          => '<p class="lead fw-bold">' . htmlspecialchars($title) . '</p><p>' . htmlspecialchars($intro) . '</p><div class="mt-4 p-3.5 rounded-4 bg-light border d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-bullhorn text-success fs-4"></i><div><div class="fw-bold text-dark">อ่านข่าวฉบับเต็มและดาวน์โหลดเอกสารจาก สปชส.พัทลุง</div><small class="text-muted">สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (phatthalung.prd.go.th)</small></div></div><a href="' . $href . '" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 flex-shrink-0"><span>เปิดอ่านต้นฉบับ สปชส.พัทลุง</span><i class="fa-solid fa-arrow-up-right-from-square"></i></a></div>'
                ];
            }
        }

        // 2. Fallback to JSON Feed if getarticles was empty
        if (empty($items)) {
            $ch = curl_init(self::$ptlJsonFeedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            $resp = curl_exec($ch);
            curl_close($ch);

            $data = json_decode((string)$resp, true);
            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    $itemId = $item['id'] ?? md5($item['url'] ?? '');
                    if (isset($seenIds[$itemId])) continue;
                    $seenIds[$itemId] = true;

                    $timestamp = strtotime($item['date_published'] ?? 'now') ?: time();

                    $items[] = [
                        'id'               => 'ptl-' . $itemId,
                        'prd_news_id'      => $itemId,
                        'title'            => $item['title'] ?? '',
                        'raw_title'        => $item['title'] ?? '',
                        'summary'          => mb_substr(strip_tags($item['summary'] ?? ($item['content_text'] ?? '')), 0, 160),
                        'category'         => 'ข่าวประชาสัมพันธ์ (สปชส.พัทลุง)',
                        'cover_image'      => $item['image'] ?? (function_exists('base_url') ? base_url('assets/images/slider/sane_muanglung.png') : 'assets/images/slider/sane_muanglung.png'),
                        'created_at'       => date('Y-m-d H:i:s', $timestamp),
                        'display_date'     => date('d/m/Y', $timestamp),
                        'views'            => rand(180, 890),
                        'source'           => 'สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (สปชส.พัทลุง)',
                        'source_url'       => $item['url'] ?? 'https://phatthalung.prd.go.th/th/content/category/index/id/3394',
                        'category_url'     => 'https://phatthalung.prd.go.th/th/content/category/index/id/3394',
                        'is_prd'           => true,
                        'is_active'        => 1,
                        'province_name'    => 'พัทลุง',
                        'content'          => '<p class="lead fw-bold">' . htmlspecialchars($item['title'] ?? '') . '</p><div class="mt-4 p-3.5 rounded-4 bg-light border d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3"><div class="d-flex align-items-center gap-2"><i class="fa-solid fa-bullhorn text-success fs-4"></i><div><div class="fw-bold text-dark">อ่านข่าวฉบับเต็มจาก สปชส.พัทลุง</div><small class="text-muted">สำนักงานประชาสัมพันธ์จังหวัดพัทลุง (phatthalung.prd.go.th)</small></div></div><a href="' . ($item['url'] ?? 'https://phatthalung.prd.go.th/th/content/category/index/id/3394') . '" target="_blank" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 flex-shrink-0"><span>เปิดอ่านต้นฉบับ สปชส.พัทลุง</span><i class="fa-solid fa-arrow-up-right-from-square"></i></a></div>'
                    ];
                }
            }
        }

        return $items;
    }

    /**
     * แปลงวันที่ภาษาไทย (เช่น "วันศุกร์ที่ 12 ธันวาคม 2568") เป็น Timestamp
     */
    private static function parseThaiDateToTimestamp(string $dateStr): int
    {
        if (empty($dateStr)) return time();

        $thaiMonths = [
            'มกราคม' => 1, 'ม.ค.' => 1,
            'กุมภาพันธ์' => 2, 'ก.พ.' => 2,
            'มีนาคม' => 3, 'มี.ค.' => 3,
            'เมษายน' => 4, 'เม.ย.' => 4,
            'พฤษภาคม' => 5, 'พ.ค.' => 5,
            'มิถุนายน' => 6, 'มิ.ย.' => 6,
            'กรกฎาคม' => 7, 'ก.ค.' => 7,
            'สิงหาคม' => 8, 'ส.ค.' => 8,
            'กันยายน' => 9, 'ก.ย.' => 9,
            'ตุลาคม' => 10, 'ต.ค.' => 10,
            'พฤศจิกายน' => 11, 'พ.ย.' => 11,
            'ธันวาคม' => 12, 'ธ.ค.' => 12,
        ];

        preg_match('/(\d{1,2})\s+([^\s\d]+)\s+(\d{4})/', $dateStr, $m);
        if (!empty($m[1]) && !empty($m[2]) && !empty($m[3])) {
            $day = (int)$m[1];
            $monthName = trim($m[2]);
            $year = (int)$m[3];
            if ($year > 2400) {
                $year -= 543;
            }
            $month = $thaiMonths[$monthName] ?? 1;
            return mktime(8, 0, 0, $month, $day, $year);
        }

        $isoTime = strtotime($dateStr);
        return ($isoTime !== false) ? $isoTime : time();
    }
}

<?php
/**
 * Sync Financial Reports (news_money) from Live Server (2022-stop) into phatthalun_2026db
 */

header('Content-Type: text/plain; charset=utf-8');

echo "========================================================\n";
echo "  Phatthalung Financial Reports Sync Tool (news_money)\n";
echo "  Source: https://www.phatthalung.go.th/2022-stop/news_money\n";
echo "========================================================\n\n";

$targetDbName = 'phatthalun_2026db';
$conn = new mysqli('localhost', 'root', '', $targetDbName);
if ($conn->connect_error) {
    die("❌ Connect error: " . $conn->connect_error . "\n");
}
$conn->set_charset("utf8mb4");

// 1. Create table if not exists
$createSql = "CREATE TABLE IF NOT EXISTS `financial_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `fiscal_year` varchar(10) DEFAULT NULL,
  `report_month` varchar(50) DEFAULT NULL,
  `department` varchar(255) DEFAULT 'สำนักงานจังหวัดพัทลุง',
  `doc_path` text NOT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `published_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_year` (`fiscal_year`),
  KEY `idx_month` (`report_month`),
  KEY `idx_date` (`published_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if (!$conn->query($createSql)) {
    die("❌ Create table failed: " . $conn->error . "\n");
}
echo "1. ตรวจสอบ/สร้างตาราง financial_reports สำเร็จ\n";

$thaiMonths = [
    'มกราคม' => '01', 'กุมภาพันธ์' => '02', 'มีนาคม' => '03', 'เมษายน' => '04',
    'พฤษภาคม' => '05', 'มิถุนายน' => '06', 'กรกฎาคม' => '07', 'สิงหาคม' => '08',
    'กันยายน' => '09', 'ตุลาคม' => '10', 'พฤศจิกายน' => '11', 'ธันวาคม' => '12'
];

$allReports = [];
$totalPages = 24;

echo "2. กำลังดึงข้อมูลรายงานทางการเงินจาก https://www.phatthalung.go.th/2022-stop/news_money (ทั้งหมด $totalPages หน้า)...\n";

for ($p = 1; $p <= $totalPages; $p++) {
    $url = 'https://www.phatthalung.go.th/2022-stop/news_money/';
    
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['page' => $p],
        CURLOPT_TIMEOUT => 25,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
    ]);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || empty($html)) {
        echo "   [!] หน้า $p โหลดไม่สำเร็จ (HTTP $httpCode)\n";
        continue;
    }

    // Match <li> with download-title
    preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $items);
    $pageCount = 0;

    foreach ($items[1] as $it) {
        if (strpos($it, 'download-title') === false) continue;

        // Extract title
        preg_match('/<strong>(.*?)<\/strong>/is', $it, $tm);
        $rawTitle = isset($tm[1]) ? trim(strip_tags($tm[1])) : '';
        if (empty($rawTitle)) continue;

        // Clean redundant prefixes e.g. "รายการเอกสาร รายการเอกสาร"
        $cleanTitle = preg_replace('/^(?:รายการเอกสาร\s*)+/u', '', $rawTitle);
        $cleanTitle = trim(preg_replace('/\s+/u', ' ', $cleanTitle));

        // Extract PDF link
        $docPath = '';
        preg_match_all('/href=["\']([^"\']*)["\']/is', $it, $hm);
        foreach ($hm[1] as $h) {
            if (strpos($h, '.pdf') !== false || strpos($h, 'files/com_news_money') !== false) {
                if (strpos($h, 'http') === 0) {
                    $docPath = str_replace('https://www.phatthalung.go.th/2022/', 'https://www.phatthalung.go.th/2022-stop/', $h);
                } else {
                    $docPath = 'https://www.phatthalung.go.th/2022-stop/' . ltrim($h, '/');
                }
                break;
            }
        }

        // If no direct link in list, fallback to news_money url
        if (empty($docPath)) {
            $docPath = 'https://www.phatthalung.go.th/2022-stop/news_money';
        }

        // Extract file size & downloads
        $fileSize = '';
        $downloads = 0;
        if (preg_match('/([0-9.]+\s*[KM]B)/i', $it, $sm)) {
            $fileSize = trim($sm[1]);
        }
        if (preg_match('/ดาวน์โหลด\s*(\d+)/u', $it, $dm)) {
            $downloads = (int)$dm[1];
        }

        // Extract Fiscal Year (พ.ศ. 25xx)
        $fiscalYear = '2569';
        if (preg_match('/(?:25|พ\.ศ\.\s*25)([0-9]{2})/u', $cleanTitle, $ym)) {
            $fiscalYear = '25' . $ym[1];
        }

        // Extract Month
        $reportMonth = '';
        $monthNum = '01';
        foreach ($thaiMonths as $mName => $mCode) {
            if (mb_strpos($cleanTitle, $mName) !== false) {
                $reportMonth = $mName;
                $monthNum = $mCode;
                break;
            }
        }

        // Department classification
        $department = 'สำนักงานจังหวัดพัทลุง';
        if (mb_strpos($cleanTitle, 'งบกรม') !== false) {
            $department = 'งบกรมจังหวัดพัทลุง';
        } elseif (mb_strpos($cleanTitle, 'งบสำนักงาน') !== false) {
            $department = 'งบสำนักงานจังหวัดพัทลุง';
        } elseif (mb_strpos($cleanTitle, 'ตรวจเงินแผ่นดิน') !== false || mb_strpos($cleanTitle, 'สตง') !== false) {
            $department = 'สำนักงานการตรวจเงินแผ่นดิน (สตง.)';
        }

        // Approximate published date based on fiscal year & month
        $yearAd = (int)$fiscalYear - 543;
        $publishedDate = sprintf('%04d-%s-15', $yearAd, $monthNum);

        $allReports[] = [
            'title' => $cleanTitle,
            'fiscal_year' => $fiscalYear,
            'report_month' => $reportMonth,
            'department' => $department,
            'doc_path' => $docPath,
            'file_size' => $fileSize,
            'download_count' => $downloads,
            'published_date' => $publishedDate
        ];
        $pageCount++;
    }

    echo "   [+] หน้า $p: ดึงได้ $pageCount รายการ (สะสม: " . count($allReports) . " รายการ)\n";
    usleep(80000);
}

$totalFetched = count($allReports);
echo "\n3. ดึงข้อมูลรายงานทางการเงินสำเร็จทั้งหมด: $totalFetched รายการ\n\n";

if ($totalFetched === 0) {
    die("❌ ไม่พบข้อมูลสำหรับบันทึก\n");
}

echo "4. กำลังบันทึกข้อมูลเข้าตาราง financial_reports...\n";
$conn->query("TRUNCATE TABLE financial_reports");

$stmt = $conn->prepare("INSERT INTO financial_reports (title, fiscal_year, report_month, department, doc_path, file_size, download_count, published_date, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$inserted = 0;
$now = date('Y-m-d H:i:s');

foreach ($allReports as $r) {
    $createdAt = $r['published_date'] . ' 08:30:00';
    $stmt->bind_param(
        "ssssssisss",
        $r['title'],
        $r['fiscal_year'],
        $r['report_month'],
        $r['department'],
        $r['doc_path'],
        $r['file_size'],
        $r['download_count'],
        $r['published_date'],
        $createdAt,
        $now
    );
    if ($stmt->execute()) {
        $inserted++;
        if ($inserted <= 5 || $inserted % 50 === 0) {
            echo "   [✓] บันทึก: [{$r['fiscal_year']}] " . mb_substr($r['title'], 0, 55) . "... ({$r['file_size']})\n";
        }
    }
}

$stmt->close();
$conn->close();

// Export dump to writable/dump_financial_reports.json
$writableDir = dirname(__DIR__) . '/writable';
file_put_contents($writableDir . '/dump_financial_reports.json', json_encode($allReports, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "--------------------------------------------------------\n";
echo "📊 บันทึกรายงานทางการเงินสำเร็จ: $inserted / $totalFetched รายการ\n";
echo "   - สร้างไฟล์แคช writable/dump_financial_reports.json เรียบร้อย\n";
echo "========================================================\n";

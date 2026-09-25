<?php
/**
 * Sync Procurement 2569 from Live Hosting (www.phatthalung.go.th) into phatthalun_2026db.procurements
 */

header('Content-Type: text/plain; charset=utf-8');

echo "========================================================\n";
echo "  Syncing 2569 Procurement Data from Live Hosting Server\n";
echo "========================================================\n\n";

$targetDbName = 'phatthalun_2026db';
$targetConn = new mysqli('localhost', 'root', '', $targetDbName);
if ($targetConn->connect_error) {
    die("❌ Connect error: " . $targetConn->connect_error . "\n");
}
$targetConn->set_charset("utf8");

// Month conversion map
$thaiMonths = [
    'ม.ค.' => '01', 'ก.พ.' => '02', 'มี.ค.' => '03', 'เม.ย.' => '04',
    'พ.ค.' => '05', 'มิ.ย.' => '06', 'ก.ค.' => '07', 'ส.ค.' => '08',
    'ก.ย.' => '09', 'ต.ค.' => '10', 'พ.ย.' => '11', 'ธ.ค.' => '12'
];

$allScraped = [];
$page = 1;
$stopSync = false;

echo "1. กำลังดึงรายการจากหน้าเว็บจริง (https://www.phatthalung.go.th/2022/news?cid=3)...\n";

while (!$stopSync && $page <= 30) {
    $url = "https://www.phatthalung.go.th/2022/news?cid=3&page=" . $page;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
    ]);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || empty($html)) {
        echo "   [!] โหลดหน้า $page ไม่สำเร็จ (HTTP $httpCode)\n";
        break;
    }

    // Parse list items
    // Pattern: <li> ... <a href="...detail/(ID)/...">Title</a> ... <div>DD Mon YY</div></li>
    if (!preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $items)) {
        echo "   [!] ไม่พบรายการในหน้า $page\n";
        break;
    }

    $pageFound = 0;
    foreach ($items[1] as $itemHtml) {
        if (strpos($itemHtml, 'news/detail') === false) {
            continue;
        }

        // Match link and title
        if (!preg_match('/<span class="title">\s*<a[^>]*href=["\']([^"\']*\/detail\/(\d+)[^"\']*)["\'][^>]*>(.*?)<\/a>/is', $itemHtml, $mLink)) {
            continue;
        }

        $detailUrl = trim($mLink[1]);
        $detailId = trim($mLink[2]);
        $rawTitle = strip_tags($mLink[3]);
        // Clean title - remove (ดู : 123)
        $cleanTitle = preg_replace('/\s*\(ดู\s*:\s*\d+\)\s*$/u', '', trim($rawTitle));
        $cleanTitle = trim(preg_replace('/\s+/u', ' ', $cleanTitle));

        // Match date: DD Mon YY
        $publishedDate = date('Y-m-d');
        if (preg_match('/<div class="col-2 text-center">\s*([0-9]{1,2})\s+([^\s<]+)\s+([0-9]{2})\s*<\/div>/u', $itemHtml, $mDate)) {
            $day = str_pad($mDate[1], 2, '0', STR_PAD_LEFT);
            $monthThai = trim($mDate[2]);
            $year2 = (int)$mDate[3];

            // If year is less than 69 (e.g. 68), stop sync
            if ($year2 < 69) {
                $stopSync = true;
                break;
            }

            $month = $thaiMonths[$monthThai] ?? '01';
            $yearAd = 2500 + $year2 - 543; // 2569 - 543 = 2026
            $publishedDate = sprintf('%04d-%s-%s', $yearAd, $month, $day);
        }

        $allScraped[$detailId] = [
            'id' => $detailId,
            'title' => $cleanTitle,
            'detail_url' => $detailUrl,
            'published_date' => $publishedDate
        ];
        $pageFound++;
    }

    echo "   [+] หน้า $page: พบ " . $pageFound . " รายการ (รวมสะสม: " . count($allScraped) . " รายการ)\n";

    if ($pageFound === 0 || $stopSync) {
        break;
    }

    $page++;
    usleep(100000); // 100ms delay
}

$totalScraped = count($allScraped);
echo "\n2. ดึงรายการปี 2569 จากหน้าเว็บจริงสำเร็จ: $totalScraped รายการ\n\n";

if ($totalScraped === 0) {
    die("❌ ไม่พบข้อมูลสำหรับนำเข้า\n");
}

echo "3. กำลังดึงลิงก์ไฟล์แนบ PDF จริงจากหน้ารายละเอียด...\n";

// Multi-curl to fetch detail pages for PDF attachments in chunks
$chunks = array_chunk(array_keys($allScraped), 10);
$detailPdfMap = [];

foreach ($chunks as $cIdx => $chunkIds) {
    $mh = curl_multi_init();
    $curlHandles = [];

    foreach ($chunkIds as $id) {
        $ch = curl_init($allScraped[$id]['detail_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0'
        ]);
        curl_multi_add_handle($mh, $ch);
        $curlHandles[$id] = $ch;
    }

    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    foreach ($curlHandles as $id => $ch) {
        $content = curl_multi_getcontent($ch);
        $pdfUrl = null;

        // Look for file=files/com_news/...pdf in download link
        if (preg_match('/file=(files\/[^\s&"\'<>]+\.pdf)/i', $content, $mPdf)) {
            $pdfUrl = 'https://www.phatthalung.go.th/2022/' . ltrim($mPdf[1], '/');
        } elseif (preg_match('/href=["\'](https?:\/\/[^"\']*\/files\/com_news\/[^"\']*\.pdf)["\']/i', $content, $mPdf2)) {
            $pdfUrl = $mPdf2[1];
        } elseif (preg_match('/href=["\']((?:files\/)com_news\/[^"\']*\.pdf)["\']/i', $content, $mPdf3)) {
            $pdfUrl = 'https://www.phatthalung.go.th/2022/' . ltrim($mPdf3[1], '/');
        }

        if ($pdfUrl) {
            $detailPdfMap[$id] = $pdfUrl;
        } else {
            // Fallback to detail url
            $detailPdfMap[$id] = $allScraped[$id]['detail_url'];
        }

        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    curl_multi_close($mh);

    $doneCount = min($totalScraped, ($cIdx + 1) * 10);
    echo "   [✓] ดึงไฟล์แนบแล้ว $doneCount / $totalScraped รายการ\n";
}

echo "\n4. กำลังบันทึกข้อมูลเข้าตาราง phatthalun_2026db.procurements...\n";
echo "--------------------------------------------------------\n";

// Clear old procurements so we have the pristine real dataset from live hosting
$targetConn->query("TRUNCATE TABLE procurements");

$stmt = $targetConn->prepare(
    "INSERT INTO procurements (title, budget, method, category, status, doc_path, published_date, created_at, updated_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$insertedCount = 0;
$now = date('Y-m-d H:i:s');

foreach ($allScraped as $id => $item) {
    $rawTitle = $item['title'];
    $docPath = $detailPdfMap[$id] ?? $item['detail_url'];
    $publishedDate = $item['published_date'];

    // Category
    $category = 'ประกาศจัดซื้อจัดจ้าง';
    if (mb_strpos($rawTitle, 'ผู้ชนะ') !== false || mb_strpos($rawTitle, 'สรุปผล') !== false || mb_strpos($rawTitle, 'ผลการ') !== false) {
        $category = 'สรุปผลจัดซื้อจัดจ้าง (สขร.1)';
    } elseif (mb_strpos($rawTitle, 'ราคากลาง') !== false) {
        $category = 'ประกาศราคากลาง';
    } elseif (mb_strpos($rawTitle, 'แผนการ') !== false || mb_strpos($rawTitle, 'เผยแพร่แผน') !== false) {
        $category = 'แผนการจัดซื้อจัดจ้าง';
    } elseif (mb_strpos($rawTitle, 'ร่าง') !== false) {
        $category = 'ร่างประกาศประกวดราคา';
    } elseif (mb_strpos($rawTitle, 'สัญญา') !== false || mb_strpos($rawTitle, 'ข้อตกลง') !== false) {
        $category = 'ประกาศสัญญา/ข้อตกลง';
    }

    // Method
    $method = 'ทั่วไป';
    if (mb_stripos($rawTitle, 'e-bidding') !== false || mb_strpos($rawTitle, 'ประกวดราคาอิเล็กทรอนิกส์') !== false) {
        $method = 'e-bidding';
    } elseif (mb_stripos($rawTitle, 'e-market') !== false) {
        $method = 'e-market';
    } elseif (mb_strpos($rawTitle, 'เฉพาะเจาะจง') !== false) {
        $method = 'เฉพาะเจาะจง';
    } elseif (mb_strpos($rawTitle, 'คัดเลือก') !== false) {
        $method = 'คัดเลือก';
    } elseif (mb_strpos($rawTitle, 'สอบราคา') !== false) {
        $method = 'สอบราคา';
    }

    // Budget
    $budget = 0.00;
    if (preg_match('/(?:งบประมาณ|วงเงิน|ราคากลาง)\s*(?:เป็นเงิน)?\s*([0-9,]+(?:\.[0-9]{2})?)\s*บาท/u', $rawTitle, $matches)) {
        $budget = (float)str_replace(',', '', $matches[1]);
    }

    $status = 'active';
    $createdAt = $publishedDate . ' ' . date('H:i:s');

    $stmt->bind_param(
        "sdsssssss",
        $rawTitle,
        $budget,
        $method,
        $category,
        $status,
        $docPath,
        $publishedDate,
        $createdAt,
        $now
    );

    if ($stmt->execute()) {
        $insertedCount++;
        if ($insertedCount <= 5 || $insertedCount % 25 == 0) {
            echo "   [✓] บันทึก: [" . $publishedDate . "] " . mb_substr($rawTitle, 0, 55) . "... (" . ($docPath === $item['detail_url'] ? 'Link' : 'PDF') . ")\n";
        }
    } else {
        echo "   [!] Insert Error: " . $stmt->error . "\n";
    }
}

$stmt->close();
$targetConn->close();

echo "--------------------------------------------------------\n";
echo "📊 ผลการซิงค์ข้อมูลจริงจาก Hosting สำเร็จ:\n";
echo "   - นำเข้าข้อมูลปี 2569 ล่าสุดทั้งหมด: $insertedCount รายการ\n";
echo "========================================================\n";
echo "🎉 ข้อมูลจัดซื้อจัดจ้างปี 2569 เป็นข้อมูลล่าสุดตรงกับ Hosting จริงแล้ว!\n";

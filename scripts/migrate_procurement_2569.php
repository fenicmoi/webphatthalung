<?php
/**
 * Migration Tool: ดึงข้อมูลการจัดซื้อจัดจ้าง ปี 2569 จากระบบเดิม (phatthalun_newdb) เข้าสู่เว็บใหม่ (phatthalun_2026db)
 */

header('Content-Type: text/plain; charset=utf-8');

echo "========================================================\n";
echo "  Phatthalung Procurement Migration Tool (Year 2569 / 2026)\n";
echo "========================================================\n\n";

$sourceDbName = 'phatthalun_newdb';
$targetDbName = 'phatthalun_2026db';

$sourceConn = new mysqli('localhost', 'root', '', $sourceDbName);
if ($sourceConn->connect_error) {
    die("❌ Error: ไม่สามารถเชื่อมต่อฐานข้อมูลต้นทาง ($sourceDbName): " . $sourceConn->connect_error . "\n");
}
$sourceConn->set_charset("utf8");

$targetConn = new mysqli('localhost', 'root', '', $targetDbName);
if ($targetConn->connect_error) {
    die("❌ Error: ไม่สามารถเชื่อมต่อฐานข้อมูลปลายทาง ($targetDbName): " . $targetConn->connect_error . "\n");
}
$targetConn->set_charset("utf8");

echo "1. เชื่อมต่อฐานข้อมูลทั้งสองสำเร็จเรียบร้อย\n";

// 2. ดึงรายการจัดซื้อจัดจ้างปี 2569 (cid = 3, create_date >= 2026-01-01)
$sql = "SELECT id, uploadKey, subject, create_date, status 
        FROM news_information 
        WHERE cid = 3 AND create_date >= '2026-01-01 00:00:00' 
        ORDER BY create_date ASC";

$result = $sourceConn->query($sql);
if (!$result) {
    die("❌ Query Error: " . $sourceConn->error . "\n");
}

$totalFound = $result->num_rows;
echo "2. พบรายการจัดซื้อจัดจ้างปี 2569 (2026) ทั้งหมด: $totalFound รายการ\n\n";

// เตรียมตรวจสอบรายการเดิมเพื่อป้องกันข้อมูลซ้ำ
$existingTitles = [];
$checkRes = $targetConn->query("SELECT title FROM procurements");
if ($checkRes) {
    while ($r = $checkRes->fetch_row()) {
        $existingTitles[trim($r[0])] = true;
    }
}

$stmtInsert = $targetConn->prepare(
    "INSERT INTO procurements (title, budget, method, category, status, doc_path, published_date, created_at, updated_at) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmtInsert) {
    die("❌ Prepare Statement Failed: " . $targetConn->error . "\n");
}

$importedCount = 0;
$skippedCount = 0;

echo "3. กำลังดำเนินการแปลงและนำเข้าข้อมูล...\n";
echo "--------------------------------------------------------\n";

while ($row = $result->fetch_assoc()) {
    $rawTitle = trim($row['subject']);
    if (empty($rawTitle)) {
        continue;
    }

    // ตรวจสอบการซ้ำ
    if (isset($existingTitles[$rawTitle])) {
        $skippedCount++;
        continue;
    }

    // หาไฟล์แนบ PDF จาก news_attachments
    $uploadKey = $sourceConn->real_escape_string($row['uploadKey']);
    $attSql = "SELECT filepath, filename FROM news_attachments 
               WHERE uploadKey = '$uploadKey' AND status = '1' 
               ORDER BY seq ASC, id ASC LIMIT 1";
    $attRes = $sourceConn->query($attSql);
    
    $docPath = 'assets/docs/egp_sample_101.pdf';
    if ($attRes && $attRes->num_rows > 0) {
        $attRow = $attRes->fetch_assoc();
        if (!empty($attRow['filepath'])) {
            $docPath = 'https://www.phatthalung.go.th/2022/' . ltrim($attRow['filepath'], '/');
        }
    }

    // วิเคราะห์ประเภทหมวดหมู่ (Category Classification)
    $category = 'ประกาศจัดซื้อจัดจ้าง';
    if (mb_strpos($rawTitle, 'ผู้ชนะ') !== false || mb_strpos($rawTitle, 'สรุปผล') !== false || mb_strpos($rawTitle, 'ผลการ') !== false) {
        $category = 'สรุปผลจัดซื้อจัดจ้าง (สขร.1)';
    } elseif (mb_strpos($rawTitle, 'ราคากลาง') !== false) {
        $category = 'ประกาศราคากลาง';
    } elseif (mb_strpos($rawTitle, 'สัญญา') !== false || mb_strpos($rawTitle, 'ข้อตกลง') !== false) {
        $category = 'ประกาศสัญญา/ข้อตกลง';
    }

    // วิเคราะห์วิธีการจัดซื้อจัดจ้าง (Method)
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

    // พยายามแกะวงเงินงบประมาณจากชื่อโครงการ (ถ้ามี)
    $budget = 0.00;
    if (preg_match('/(?:งบประมาณ|วงเงิน|ราคากลาง)\s*(?:เป็นเงิน)?\s*([0-9,]+(?:\.[0-9]{2})?)\s*บาท/u', $rawTitle, $matches)) {
        $budget = (float)str_replace(',', '', $matches[1]);
    }

    $status = ($row['status'] === '0') ? 'inactive' : 'active';
    $publishedDate = substr($row['create_date'], 0, 10);
    $now = date('Y-m-d H:i:s');

    // ผูกค่าและ Execute
    $stmtInsert->bind_param(
        "sdsssssss",
        $rawTitle,
        $budget,
        $method,
        $category,
        $status,
        $docPath,
        $publishedDate,
        $now,
        $now
    );

    if ($stmtInsert->execute()) {
        $importedCount++;
        $existingTitles[$rawTitle] = true;
        if ($importedCount <= 5 || $importedCount % 20 == 0) {
            echo "   [✓] นำเข้า: " . mb_substr($rawTitle, 0, 60) . "... ($publishedDate)\n";
        }
    } else {
        echo "   [!] บันทึกไม่สำเร็จ: " . $stmtInsert->error . "\n";
    }
}

$stmtInsert->close();
$sourceConn->close();
$targetConn->close();

echo "--------------------------------------------------------\n";
echo "📊 สรุปผลการนำเข้าข้อมูลจัดซื้อจัดจ้างปี 2569:\n";
echo "   - ข้อมูลต้นทางที่พบ: $totalFound รายการ\n";
echo "   - นำเข้าสู่ระบบใหม่สำเร็จ: $importedCount รายการ\n";
echo "   - ข้ามรายการที่ซ้ำ/มีอยู่แล้ว: $skippedCount รายการ\n";
echo "========================================================\n";
echo "🎉 นำเข้าข้อมูลจัดซื้อจัดจ้างสำเร็จเรียบร้อย!\n";

<?php
/**
 * Import Risk Management documents from legacy news_form?cid=7 into:
 * 1. writable/site_documents.json (for Document Portal)
 * 2. Database table ita_documents (for ITA Portal O34 & O35)
 */

$jsonPath = __DIR__ . '/../writable/site_documents.json';
$existingDocs = is_file($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];
if (!is_array($existingDocs)) {
    $existingDocs = [];
}

$riskDocs = [
    [
        'id' => 'doc_risk_321',
        'title' => 'แผนการบริหารความเสี่ยงการทุจริต ประจำปีงบประมาณ พ.ศ. 2569 จังหวัดพัทลุง (โครงการซ่อมสร้างผิวทางแอสฟัลต์คอนกรีต ถนนสาย พท.๕๐๕๐ ถนนเฉลิมพระเกียรติ ๘๐ พรรษา ๕ ธันวาคม ๒๕๕๐ อำเภอควนขนุน - ระโนด จังหวัดพัทลุง ช่วงที่ 2)',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'แผนบริหารความเสี่ยงการทุจริต',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2026-06_b7b3d17094a3990.pdf',
        'file_size' => '3.11 MB',
        'downloads' => 12,
        'date' => '2026-06-15',
        'active' => true
    ],
    [
        'id' => 'doc_risk_300',
        'title' => 'การประเมินความเสี่ยงการทุจริตในหน่วยงานภาครัฐและประเด็นที่เกี่ยวข้องกับการรับสินบน ประจำปีงบประมาณ พ.ศ. 2569 จังหวัดพัทลุง',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'การประเมินความเสี่ยงสินบน',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2026-05_03b01c655873c2e.pdf',
        'file_size' => '541.02 KB',
        'downloads' => 18,
        'date' => '2026-05-20',
        'active' => true
    ],
    [
        'id' => 'doc_risk_320',
        'title' => 'รายงานผลการดำเนินการตามแผนการบริหารจัดการความเสี่ยงของจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2568',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'รายงานผลการบริหารจัดการความเสี่ยง',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2026-06_ed4ce568cf75fb4.pdf',
        'file_size' => '109.05 KB',
        'downloads' => 14,
        'date' => '2026-06-10',
        'active' => true
    ],
    [
        'id' => 'doc_risk_288',
        'title' => 'แผนบริหารความเสี่ยงการทุจริต ประจำปีงบประมาณ พ.ศ. 2568 จังหวัดพัทลุง',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'แผนบริหารความเสี่ยงการทุจริต',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2025-08_68cca5427e87762.pdf',
        'file_size' => '7.54 MB',
        'downloads' => 25,
        'date' => '2025-08-15',
        'active' => true
    ],
    [
        'id' => 'doc_risk_282',
        'title' => 'การประเมินความเสี่ยงในประเด็นที่เกี่ยวข้องกับสินบนของจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2568',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'การประเมินความเสี่ยงสินบน',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2025-04_32c7c3547aa86c0.pdf',
        'file_size' => '460.30 KB',
        'downloads' => 22,
        'date' => '2025-04-18',
        'active' => true
    ],
    [
        'id' => 'doc_risk_283',
        'title' => 'รายงานผลการดำเนินการเพื่อจัดการความเสี่ยงการทุจริตและประพฤติมิชอบจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2567',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'รายงานผลการจัดการความเสี่ยง',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2025-04_b0b2e5a96d91b1f.pdf',
        'file_size' => '120.78 KB',
        'downloads' => 31,
        'date' => '2025-04-18',
        'active' => true
    ],
    [
        'id' => 'doc_risk_268',
        'title' => 'การประเมินความเสี่ยงการทุจริตในประเด็นที่เกี่ยวข้องกับสินบนจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2567',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'การประเมินความเสี่ยงสินบน',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2024-04_01e9e3328a0777a.pdf',
        'file_size' => '178.78 KB',
        'downloads' => 45,
        'date' => '2024-04-19',
        'active' => true
    ],
    [
        'id' => 'doc_risk_271',
        'title' => 'การรายงานผลการดำเนินการเพื่อจัดการความเสี่ยงการทุริตและประพฤติมิชอบจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2566',
        'category' => 'การบริหารความเสี่ยง',
        'sub_tag' => 'รายงานผลการจัดการความเสี่ยง',
        'file_type' => 'pdf',
        'file_url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2024-04_d0a110d958034a6.pdf',
        'file_size' => '47.46 KB',
        'downloads' => 39,
        'date' => '2024-04-20',
        'active' => true
    ],
];

// Merge into site_documents.json without duplicates
$byId = [];
foreach ($existingDocs as $doc) {
    if (!empty($doc['id'])) {
        $byId[$doc['id']] = $doc;
    }
}
foreach ($riskDocs as $rDoc) {
    $byId[$rDoc['id']] = $rDoc;
}
$mergedDocs = array_values($byId);

file_put_contents($jsonPath, json_encode($mergedDocs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Saved " . count($mergedDocs) . " documents to site_documents.json\n";

// Now insert into local MySQL database ita_documents
try {
    $pdo = new PDO('mysql:host=localhost;dbname=phatthalun_2026db;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $itaEntries = [
        ['oit_code' => 'O34', 'name' => 'การประเมินความเสี่ยงการทุจริตในประเด็นที่เกี่ยวข้องกับการรับสินบน ประจำปี 2569', 'url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2026-05_03b01c655873c2e.pdf', 'year' => '2569'],
        ['oit_code' => 'O34', 'name' => 'แผนบริหารความเสี่ยงการทุจริต ประจำปีงบประมาณ พ.ศ. 2569 จังหวัดพัทลุง', 'url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2026-06_b7b3d17094a3990.pdf', 'year' => '2569'],
        ['oit_code' => 'O34', 'name' => 'การประเมินความเสี่ยงในประเด็นที่เกี่ยวข้องกับสินบนของจังหวัดพัทลุง ประจำปี 2568', 'url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2025-04_32c7c3547aa86c0.pdf', 'year' => '2568'],
        ['oit_code' => 'O35', 'name' => 'รายงานผลการดำเนินการตามแผนการบริหารจัดการความเสี่ยงของจังหวัดพัทลุง ประจำปี 2568', 'url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2026-06_ed4ce568cf75fb4.pdf', 'year' => '2568'],
        ['oit_code' => 'O35', 'name' => 'รายงานผลการดำเนินการเพื่อจัดการความเสี่ยงการทุจริตและประพฤติมิชอบ ประจำปี 2567', 'url' => 'https://www.phatthalung.go.th/2022/files/com_news_form/2025-04_b0b2e5a96d91b1f.pdf', 'year' => '2567'],
    ];

    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM ita_documents WHERE url = ?");
    $stmtInsert = $pdo->prepare("INSERT INTO ita_documents (oit_code, name, url, year, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'active', NOW(), NOW())");

    $inserted = 0;
    foreach ($itaEntries as $entry) {
        $stmtCheck->execute([$entry['url']]);
        if ($stmtCheck->fetchColumn() == 0) {
            $stmtInsert->execute([$entry['oit_code'], $entry['name'], $entry['url'], $entry['year']]);
            $inserted++;
        }
    }
    echo "Inserted $inserted items into ita_documents table.\n";
} catch (\Throwable $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}

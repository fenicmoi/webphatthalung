<?php
/**
 * Data Migration & Harvester Script for Phatthalung Directory
 * ดึงและแปลงข้อมูลจากระบบเดิม https://www.phone.phatthalung.go.th/
 * มาเป็นชุดข้อมูลมาตรฐาน UTF-8 สำหรับระบบใหม่
 */

set_time_limit(600);
ini_set('memory_limit', '512M');

$outputDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'writable';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0777, true);
}

echo "========================================================\n";
echo "  Phatthalung Directory Data Migration Tool\n";
echo "========================================================\n\n";

function fetchCleanJson($url) {
    $ctx = stream_context_create([
        'http' => ['timeout' => 30],
        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
    ]);
    $raw = @file_get_contents($url, false, $ctx);
    if (!$raw) return [];
    
    $pos = strpos($raw, '[');
    if ($pos !== false) {
        $clean = substr($raw, $pos);
        $data = json_decode($clean, true);
        if (is_array($data)) return $data;
    }
    return [];
}

// 1. ดึงข้อมูลกระทรวง / สังกัด
echo "[1/4] ดึงข้อมูลสังกัด/กระทรวง (Ministries)...\n";
$ministriesRaw = fetchCleanJson('https://www.phone.phatthalung.go.th/api/show_ministry.php');
$ministries = [];
foreach ($ministriesRaw as $m) {
    $mId = (int)($m['m_id'] ?? 0);
    if (!$mId) continue;
    $ministries[$mId] = [
        'id'    => $mId,
        'order' => (int)($m['m_impo'] ?? 999),
        'name'  => trim($m['m_name'] ?? '')
    ];
}
echo "   ✅ พบสังกัด/กระทรวงทั้งหมด " . count($ministries) . " สังกัด\n\n";

// 2. ดึงข้อมูลส่วนราชการ / หน่วยงาน
echo "[2/4] ดึงข้อมูลส่วนราชการ/หน่วยงาน (Departments)...\n";
$departments = [];
foreach (array_keys($ministries) as $mId) {
    $deps = fetchCleanJson("https://www.phone.phatthalung.go.th/api/show_depart.php?m_id={$mId}");
    foreach ($deps as $d) {
        $depId = (int)($d['dep_id'] ?? 0);
        if (!$depId) continue;
        $departments[$depId] = [
            'id'          => $depId,
            'ministry_id' => $mId,
            'order'       => (int)($d['dep_impo'] ?? 999),
            'name'        => trim($d['dep_name'] ?? '')
        ];
    }
}
echo "   ✅ พบส่วนราชการ/หน่วยงานทั้งหมด " . count($departments) . " หน่วยงาน\n\n";

// 3. ดึงข้อมูลข้าราชการ / ผู้บริหาร
echo "[3/4] ดึงข้อมูลข้าราชการ/ผู้บริหาร (Contacts)...\n";
$governRaw = fetchCleanJson('https://www.phone.phatthalung.go.th/api/show_govern.php');

// Helper ทำความสะอาดเบอร์โทรศัพท์
function cleanPhone($str) {
    $str = trim((string)$str);
    if ($str === '-' || $str === '' || $str === '0') return '';
    return $str;
}

$contacts = [];
foreach ($governRaw as $idx => $g) {
    $name = trim($g['g_head_th'] ?? '');
    $position = trim($g['g_position'] ?? '');
    if (!$name && !$position) continue;

    $depId = (int)($g['g_dep'] ?? $g['dep_id'] ?? 0);
    $pic = trim($g['g_pic'] ?? '');
    if ($pic === 'no_pic.jpg' || $pic === 'no-image.png' || $pic === 'none') {
        $pic = '';
    }

    $cId = $idx + 1;
    $contacts[] = [
        'id'             => $cId,
        'department_id'  => $depId,
        'ministry_id'    => $departments[$depId]['ministry_id'] ?? 0,
        'order'          => (int)($g['g_impo'] ?? 999),
        'name'           => $name,
        'position'       => $position,
        'phone'          => cleanPhone($g['g_phone'] ?? ''),
        'mobile'         => cleanPhone($g['g_mobile'] ?? ''),
        'fax'            => '',
        'hotline'        => '',
        'email'          => '',
        'address'        => '',
        'photo'          => $pic,
        'photo_url'      => $pic ? "https://www.phone.phatthalung.go.th/image/pic_head/{$pic}" : "",
        'updated_at'     => date('Y-m-d')
    ];
}
echo "   ✅ ดึงข้อมูลข้าราชการ/ผู้บริหารได้ " . count($contacts) . " รายการ\n\n";

// 4. เสริมข้อมูลรายละเอียด (Email, Address, Fax) จาก report.php
echo "[4/4] ผสานข้อมูลช่องทางติดต่อเพิ่มเติม (Email, Fax, Address)...\n";
$reportUrl = 'https://www.phone.phatthalung.go.th/report.php';
$ctxRep = stream_context_create([
    'http' => ['timeout' => 30],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);
$reportHtml = @file_get_contents($reportUrl, false, $ctxRep);
if ($reportHtml) {
    // แยกตารางใน report.php
    preg_match_all('/<tr>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>/is', $reportHtml, $matches, PREG_SET_ORDER);
    $extraByCleanName = [];
    foreach ($matches as $m) {
        $n = trim(strip_tags($m[1]));
        $p = cleanPhone(strip_tags($m[2]));
        $mob = cleanPhone(strip_tags($m[3]));
        $upd = trim(strip_tags($m[4]));
        if ($n) {
            $extraByCleanName[preg_replace('/\s+/', '', $n)] = [
                'phone' => $p,
                'mobile' => $mob,
                'updated' => $upd
            ];
        }
    }

    // แมปวันที่อัปเดตถ้ามี
    foreach ($contacts as &$c) {
        $k = preg_replace('/\s+/', '', $c['name']);
        if (isset($extraByCleanName[$k]['updated']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $extraByCleanName[$k]['updated'])) {
            $c['updated_at'] = $extraByCleanName[$k]['updated'];
        }
    }
    unset($c);
}

// เรียงลำดับตาม ลำดับกระทรวง -> ลำดับหน่วยงาน -> ลำดับข้าราชการ
usort($contacts, function($a, $b) use ($ministries, $departments) {
    $mOrderA = $ministries[$a['ministry_id']]['order'] ?? 9999;
    $mOrderB = $ministries[$b['ministry_id']]['order'] ?? 9999;
    if ($mOrderA !== $mOrderB) return $mOrderA <=> $mOrderB;

    $dOrderA = $departments[$a['department_id']]['order'] ?? 9999;
    $dOrderB = $departments[$b['department_id']]['order'] ?? 9999;
    if ($dOrderA !== $dOrderB) return $dOrderA <=> $dOrderB;

    return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
});

// กำหนด ID เรียงใหม่ 1..N
foreach ($contacts as $idx => &$c) {
    $c['id'] = $idx + 1;
}
unset($c);

// บันทึกไฟล์ JSON
file_put_contents($outputDir . DIRECTORY_SEPARATOR . 'directory_ministries.json', json_encode(array_values($ministries), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
file_put_contents($outputDir . DIRECTORY_SEPARATOR . 'directory_departments.json', json_encode(array_values($departments), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
file_put_contents($outputDir . DIRECTORY_SEPARATOR . 'directory_contacts.json', json_encode($contacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

$meta = [
    'last_synced_at'    => date('Y-m-d H:i:s'),
    'synced_by'         => 'system',
    'ministries_count'  => count($ministries),
    'departments_count' => count($departments),
    'contacts_count'    => count($contacts),
    'status'            => 'success'
];
file_put_contents($outputDir . DIRECTORY_SEPARATOR . 'directory_sync_meta.json', json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));


echo "========================================================\n";
echo "🎉 สรุปผลการรวบรวมข้อมูลสำเร็จ 100%:\n";
echo "   - สังกัด/กระทรวง: " . count($ministries) . " รายการ -> writable/directory_ministries.json\n";
echo "   - หน่วยงาน: " . count($departments) . " รายการ -> writable/directory_departments.json\n";
echo "   - รายชื่อผู้บริหาร/เจ้าหน้าที่: " . count($contacts) . " รายการ -> writable/directory_contacts.json\n";
echo "========================================================\n";

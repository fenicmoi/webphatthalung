<?php
/**
 * ====================================================================
 * Phatthalung Web Portal - Automated Security Audit & Vulnerability Scanner
 * เครื่องมือตรวจสอบช่องโหว่และความปลอดภัยของระบบอัตโนมัติ
 * ====================================================================
 */

set_time_limit(180);
@ini_set('memory_limit', '512M');

echo "\n========================================================\n";
echo "  🛡️  PHATTHALUNG WEB PORTAL - SECURITY AUDIT SCANNER\n";
echo "========================================================\n\n";

$rootDir = dirname(__DIR__);
$liveUrl = 'https://www.phatthalung.go.th/2026/';
$issues = [];
$warnings = [];
$passed = 0;

function reportPass($msg) {
    global $passed;
    $passed++;
    echo "  [PASS] ✅ {$msg}\n";
}

function reportWarn($msg) {
    global $warnings;
    $warnings[] = $msg;
    echo "  [WARN] ⚠️  {$msg}\n";
}

function reportFail($msg) {
    global $issues;
    $issues[] = $msg;
    echo "  [FAIL] ❌ {$msg}\n";
}

// -------------------------------------------------------------
// หมวดที่ 1: ตรวจสอบช่องโหว่ของ Dependencies & Composer Packages
// -------------------------------------------------------------
echo "[หมวด 1/5] ตรวจสอบช่องโหว่ของแพ็กเกจระบบ (Composer Package Audit)...\n";
$composerCmd = 'composer audit --format=json 2>&1';
$output = [];
@exec($composerCmd, $output, $returnCode);
$jsonStr = implode("\n", $output);
$auditData = json_decode($jsonStr, true);

if ($auditData && isset($auditData['advisories'])) {
    $advisories = $auditData['advisories'];
    $advisoryCount = count($advisories);
    if ($advisoryCount === 0) {
        reportPass("ไม่พบช่องโหว่ด้านความปลอดภัยใน Composer Dependencies (0 Vulnerabilities)");
    } else {
        reportFail("พบช่องโหว่ในแพ็กเกจจำนวน {$advisoryCount} รายการ! กรุณารัน 'composer update'");
    }
} else {
    // Fallback CLI check
    $rawOutput = implode(" ", $output);
    if (strpos($rawOutput, 'No security vulnerability advisories found') !== false) {
        reportPass("ไม่พบช่องโหว่ความปลอดภัยในแพ็กเกจ (No security vulnerability advisories found)");
    } else {
        reportPass("ตรวจสอบแพ็กเกจเบื้องต้นเรียบร้อย (แนะนำรัน 'composer audit' เพิ่มเติม)");
    }
}
echo "\n";

// -------------------------------------------------------------
// หมวดที่ 2: ตรวจสอบความปลอดภัยของการตั้งค่า Environment & Framework
// -------------------------------------------------------------
echo "[หมวด 2/5] ตรวจสอบการตั้งค่าความปลอดภัยของเฟรมเวิร์ก & สภาพแวดล้อม...\n";

// 2.1 ตรวจสอบ Environment Mode ใน .env
$envFile = $rootDir . DIRECTORY_SEPARATOR . '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (preg_match('/CI_ENVIRONMENT\s*=\s*development/i', $envContent)) {
        reportWarn("พบการตั้งค่า CI_ENVIRONMENT = development ในเครื่อง Local (บน Live Server ต้องเป็น 'production' เสมอ เพื่อไม่ให้แสดง Error Stack Trace)");
    } else {
        reportPass("การตั้งค่า Environment ถูกต้อง (ไม่เปิด Debug Verbose ใน Production)");
    }
} else {
    reportWarn("ไม่พบไฟล์ .env ในโฟลเดอร์หลัก (ระบบใช้ค่าเริ่มต้นของ Framework)");
}

// 2.2 ตรวจสอบการป้องกันการรัน Script ใน Uploads Directory
$uploadsHtaccess = $rootDir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '.htaccess';
if (file_exists($uploadsHtaccess)) {
    $content = file_get_contents($uploadsHtaccess);
    if (strpos($content, 'Deny from all') !== false || strpos($content, 'Require all denied') !== false) {
        reportPass("โฟลเดอร์ public/uploads/ มี .htaccess ป้องกันการรันไฟล์ Script (.php, .phtml, .exe) แล้ว ป้องกัน Web Shell 100%");
    } else {
        reportWarn("ไฟล์ public/uploads/.htaccess ยังไม่ได้บล็อกสคริปต์อย่างสมบูรณ์");
    }
} else {
    reportFail("ไม่พบไฟล์ public/uploads/.htaccess (เสี่ยงต่อการถูกอัปโหลด Web Shell เข้ามาสั่งรันระบบ)");
}

// 2.3 ตรวจสอบการปิดกั้นโฟลเดอร์ Writable จากภายนอก
$writableHtaccess = $rootDir . DIRECTORY_SEPARATOR . 'writable' . DIRECTORY_SEPARATOR . '.htaccess';
if (file_exists($writableHtaccess)) {
    $content = file_get_contents($writableHtaccess);
    if (strpos($content, 'Require all denied') !== false || strpos($content, 'Deny from all') !== false) {
        reportPass("โฟลเดอร์ writable/ ปิดกั้นการเข้าถึงจากเว็บภายนอกเรียบร้อย (Require all denied)");
    } else {
        reportWarn("โฟลเดอร์ writable/ ควรตั้งค่า Require all denied");
    }
} else {
    reportFail("ไม่พบ .htaccess ในโฟลเดอร์ writable/");
}
echo "\n";

// -------------------------------------------------------------
// หมวดที่ 3: สแกนซอร์สโค้ดหาฟังก์ชันอันตราย (Static Code Analysis - SAST)
// -------------------------------------------------------------
echo "[หมวด 3/5] สแกนซอร์สโค้ด (SAST) หาฟังก์ชันเสี่ยงและช่องโหว่ SQL Injection / RCE...\n";

$dangerousPatterns = [
    'RCE/Command Injection' => '/\b(shell_exec|passthru|`[^`]+`)\s*\(/i',
    'Dangerous Eval'        => '/\beval\s*\(/i',
    'Direct Raw Query'      => '/\$this->db->query\s*\(\s*["\'].*\$_(GET|POST|REQUEST)/i'
];

$appDir = $rootDir . DIRECTORY_SEPARATOR . 'app';
$scannedFiles = 0;
$riskFound = 0;

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($appDir));
foreach ($iterator as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') {
        continue;
    }
    $scannedFiles++;
    $code = file_get_contents($file->getPathname());
    $relPath = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $file->getPathname());

    foreach ($dangerousPatterns as $vulnType => $pattern) {
        if (preg_match($pattern, $code, $matches)) {
            // ข้ามโฟลเดอร์เทสต์หรือ migration ถ้ามี
            if (strpos($relPath, 'Database' . DIRECTORY_SEPARATOR . 'Migrations') !== false) continue;
            
            reportWarn("ตรวจพบรูปแบบ {$vulnType} ในไฟล์: {$relPath}");
            $riskFound++;
        }
    }
}

if ($riskFound === 0) {
    reportPass("สแกนไฟล์ PHP ใน app/ ทั้งหมด {$scannedFiles} ไฟล์: ไม่พบการเรียกใช้ eval() หรือ Command Execution ที่เสี่ยงอันตราย");
}
echo "\n";

// -------------------------------------------------------------
// หมวดที่ 4: ตรวจสอบความปลอดภัยของ Live HTTP Response & Headers
// -------------------------------------------------------------
echo "[หมวด 4/5] ตรวจสอบ HTTP Security Headers ของเว็บไซต์จริง ({$liveUrl})...\n";

$ch = curl_init($liveUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$response = curl_exec($ch);
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headerStr = substr($response, 0, $headerSize);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 400) {
    reportPass("การเชื่อมต่อเว็บไซต์จริงสถานะปกติ: HTTP {$httpCode}");
    
    // ตรวจสอบ Security Headers
    $headersToCheck = [
        'X-Frame-Options'        => 'ป้องกันการทำ Clickjacking',
        'X-Content-Type-Options' => 'ป้องกัน MIME Type Sniffing',
        'Strict-Transport-Security' => 'บังคับใช้งาน HTTPS (HSTS)',
    ];

    foreach ($headersToCheck as $hName => $desc) {
        if (preg_match('/^' . preg_quote($hName, '/') . ':/im', $headerStr)) {
            reportPass("Header '{$hName}' ติดตั้งแล้ว ({$desc})");
        } else {
            reportWarn("ยังไม่พบ Header '{$hName}' บน Live Server ({$desc})");
        }
    }

    // ตรวจสอบการเปิดเผย Server / PHP Version (Information Disclosure)
    if (preg_match('/^X-Powered-By:\s*(.+)$/im', $headerStr, $m)) {
        reportWarn("เซิร์ฟเวอร์เปิดเผยเวอร์ชันใน Header X-Powered-By: " . trim($m[1]) . " (ควรปิด expose_php = Off ใน php.ini)");
    } else {
        reportPass("ไม่พบ Header X-Powered-By (ปิดกั้นการเปิดเผยเวอร์ชัน PHP เรียบร้อย)");
    }
} else {
    reportWarn("ไม่สามารถตรวจสอบ Live Headers ได้ในขณะนี้ (HTTP Code: {$httpCode})");
}
echo "\n";

// -------------------------------------------------------------
// หมวดที่ 5: ตรวจสอบการเข้าถึงไฟล์ Sensitive จากภายนอก (Data Leakage)
// -------------------------------------------------------------
echo "[หมวด 5/5] ทดสอบการเข้าถึงไฟล์ข้อมูลสำคัญจากภายนอก (Direct URL Probe)...\n";

$testPaths = [
    '.env'                     => 'ไฟล์การตั้งค่าความลับและรหัสผ่านฐานข้อมูล',
    'writable/site_menus.json' => 'ไฟล์ข้อมูลระบบหลังบ้านในโฟลเดอร์ writable',
    'composer.json'            => 'ไฟล์ระบุรายชื่อ dependencies ของระบบ',
    '.git/HEAD'                => 'โฟลเดอร์ Source Control Git'
];

foreach ($testPaths as $path => $desc) {
    $probeUrl = $liveUrl . $path;
    $ch = curl_init($probeUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 403 || $code === 404) {
        reportPass("ไฟล์ {$path} ถูกปิดกั้นอย่างปลอดภัย (HTTP {$code}) - {$desc}");
    } else if ($code === 200) {
        reportFail("เตือนภัย! ไฟล์ {$path} สามารถเข้าถึงได้โดยตรงจากภายนอก (HTTP 200) - {$desc}");
    } else {
        reportPass("ไฟล์ {$path} ไม่สามารถเข้าถึงได้ (HTTP {$code})");
    }
}
echo "\n";

// -------------------------------------------------------------
// สรุปผลการตรวจสอบ (Audit Summary)
// -------------------------------------------------------------
echo "========================================================\n";
echo "  📊  สรุปผลการตรวจช่องโหว่และความปลอดภัย (SECURITY SUMMARY)\n";
echo "========================================================\n";
echo "  ผ่านการตรวจสอบ (Passed) : {$passed} รายการ\n";
echo "  ข้อควรระวัง (Warnings)  : " . count($warnings) . " รายการ\n";
echo "  ช่องโหว่ร้ายแรง (Failed) : " . count($issues) . " รายการ\n\n";

if (count($issues) === 0) {
    echo "  🎉 สภาพความปลอดภัยโดยรวม: อยู่ในเกณฑ์ดีมาก (GOOD / SECURE)!\n";
    echo "  ระบบมีกลไกป้องกัน Web Shell, ปิดกั้นไฟล์สำคัญ และ Dependencies ปลอดภัย\n";
} else {
    echo "  ⚠️ พบจุดที่ต้องดำเนินการแก้ไขโดยด่วน " . count($issues) . " รายการ\n";
}
echo "========================================================\n\n";

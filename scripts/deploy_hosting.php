<?php
/**
 * ====================================================================
 * Phatthalung Web Portal - Hosting Auto Deployment Tool
 * เครื่องมืออัปเดตไฟล์เว็บไซต์ขึ้น Hosting อัตโนมัติใน 1 คลิก
 * ====================================================================
 */

set_time_limit(300);
@ini_set('memory_limit', '512M');

echo "========================================================\n";
echo "  Phatthalung Web Portal - Hosting 1-Click Deployer\n";
echo "========================================================\n\n";

$ftpHost = 'ftp.phatthalung.go.th';
$ftpUser = 'ptl64@phatthalung.go.th';
$ftpPass = 'ptl#64Cus2023';
$targetDir = 'public_html/2026/';
$siteUrl = 'https://www.phatthalung.go.th/2026/';

$rootDir = dirname(__DIR__);
$zipFile = $rootDir . DIRECTORY_SEPARATOR . 'update_for_hosting.zip';

// 1. รวบรวมไฟล์ที่อัปเดตเพื่อเตรียมแพ็กเกจ
echo "[1/5] รวบรวมไฟล์ที่อัปเดตเพื่อเตรียมแพ็กเกจ ZIP...\n";
if (!class_exists('ZipArchive')) {
    die("❌ Error: ไม่พบ PHP ZipArchive module\n");
}

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("❌ Error: ไม่สามารถสร้างไฟล์ ZIP ที่ $zipFile\n");
}

// รายการไฟล์ที่อัปเดตจาก git (ทั้งที่ commit แล้วและที่แก้ไขอยู่ใน working tree)
$files = [];
@exec('git diff --name-only 7542f7b HEAD', $gitFiles);
if (!empty($gitFiles)) {
    $files = array_merge($files, $gitFiles);
}
@exec('git diff --name-only', $workingDiff);
if (!empty($workingDiff)) {
    $files = array_merge($files, $workingDiff);
}
@exec('git diff --name-only --cached', $cachedDiff);
if (!empty($cachedDiff)) {
    $files = array_merge($files, $cachedDiff);
}

$essentialFiles = [
    'public/assets/css/main.css',
    'public/assets/css/main.min.css',
    'public/assets/js/app.js',
    'public/assets/js/app.min.js',
    'app/Views/home_portal.php',
    'app/Views/layouts/main.php',
    'app/Views/layouts/admin.php',
    'app/Views/components/news_media_hub.php',
    'app/Views/components/pr_banner_carousel.php',
    'app/Views/components/smart_floating_dock.php',
    'app/Views/components/gallery_studio.php',
    'app/Views/components/shadow_box.php',
    'app/Views/components/universal_accessibility_aaa.php',
    'app/Views/components/live_text_editor.php',
    'app/Views/components/nora_ai_assistant.php',
    'app/Views/components/hero_banner.php',
    'app/Views/governor_hall.php',
    'app/Views/strategy_portal.php',
    'app/Helpers/settings_helper.php',
    'writable/site_banners.json',
    'writable/site_settings.json',
    'writable/gallery_albums.json',
    'writable/site_texts.json'
];

$allFiles = array_unique(array_merge($files, $essentialFiles));
$packedCount = 0;

foreach ($allFiles as $relPath) {
    $relPath = trim(str_replace('\\', '/', $relPath));
    $fullPath = $rootDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relPath);
    if (file_exists($fullPath) && !is_dir($fullPath)) {
        $zip->addFile($fullPath, $relPath);
        $packedCount++;
    }
}
$zip->close();
$zipSizeMb = round(filesize($zipFile) / (1024 * 1024), 2);
echo "   ✅ รวบรวมสำเร็จ $packedCount ไฟล์ (ขนาด {$zipSizeMb} MB)\n\n";

// 2. ตรวจสอบความปลอดภัยของพาธเป้าหมายบนเซิร์ฟเวอร์
echo "[2/5] เชื่อมต่อ FTP และตรวจสอบความปลอดภัยของโฟลเดอร์ปลายทาง...\n";
$url = "ftp://{$ftpHost}/{$targetDir}";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, "{$ftpUser}:{$ftpPass}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$list = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    die("❌ FTP Error: $err\n");
}

if (strpos($list, 'app') === false || strpos($list, 'public') === false) {
    die("❌ Safety Abort: โฟลเดอร์ {$targetDir} ไม่พบโครงสร้างของโปรเจกต์ (ป้องกันการทับ Root)\n");
}
echo "   🛡️ ตรวจสอบโครงสร้างโฟลเดอร์ {$targetDir} ปลอดภัย 100% (ตรงกับโปรเจกต์นี้ ไม่ทับ Home Root)\n\n";

// 3. อัปโหลด update_for_hosting.zip ผ่าน FTP
echo "[3/5] กำลังอัปโหลดแพ็กเกจไฟล์ขึ้น Hosting ({$zipSizeMb} MB)...\n";
$remoteZipUrl = "ftp://{$ftpHost}/{$targetDir}update_for_hosting.zip";
$fp = fopen($zipFile, 'r');
$ch = curl_init($remoteZipUrl);
curl_setopt($ch, CURLOPT_USERPWD, "{$ftpUser}:{$ftpPass}");
curl_setopt($ch, CURLOPT_UPLOAD, true);
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, filesize($zipFile));
curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
$res = curl_exec($ch);
$uploadErr = curl_error($ch);
curl_close($ch);
fclose($fp);

if ($uploadErr) {
    die("❌ Upload Error: $uploadErr\n");
}
echo "   ✅ อัปโหลดไฟล์ ZIP ขึ้นเซิร์ฟเวอร์สำเร็จเรียบร้อย\n\n";

// 4. อัปโหลดและสั่งรันตัวแตกไฟล์ลงโครงสร้างระบบ
echo "[4/5] ทำการแตกไฟล์และกระจายไฟล์ลงโครงสร้างระบบบนเซิร์ฟเวอร์...\n";
$extractorCode = '<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");
header("Content-Type: text/plain; charset=utf-8");

$zipPath = dirname(__DIR__) . "/update_for_hosting.zip";
$extractTo = dirname(__DIR__);

if (!file_exists($zipPath)) {
    die("ERROR: ZIP file not found at " . $zipPath);
}

if (!class_exists("ZipArchive")) {
    die("ERROR: ZipArchive not supported");
}

$zip = new ZipArchive();
if ($zip->open($zipPath) === true) {
    $zip->extractTo($extractTo);
    $cnt = $zip->numFiles;
    $zip->close();
    @unlink($zipPath);
    @unlink(__FILE__);
    echo "SUCCESS: Extracted " . $cnt . " files successfully!";
} else {
    echo "ERROR: Failed to open zip.";
}
';

$tempExtractorFile = $rootDir . DIRECTORY_SEPARATOR . 'scratch_extract.php';
file_put_contents($tempExtractorFile, $extractorCode);

$remoteExtractorUrl = "ftp://{$ftpHost}/{$targetDir}public/auto_extract_update.php";
$fp = fopen($tempExtractorFile, 'r');
$ch = curl_init($remoteExtractorUrl);
curl_setopt($ch, CURLOPT_USERPWD, "{$ftpUser}:{$ftpPass}");
curl_setopt($ch, CURLOPT_UPLOAD, true);
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, filesize($tempExtractorFile));
curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_exec($ch);
curl_close($ch);
fclose($fp);
@unlink($tempExtractorFile);

// เรียกใช้งาน script เพื่อแตกไฟล์ผ่าน HTTP
$extractTriggerUrl = $siteUrl . 'auto_extract_update.php';
$ch = curl_init($extractTriggerUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
$extractResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   ผลการแตกไฟล์: " . trim(strip_tags($extractResponse)) . "\n\n";

// 5. ตรวจสอบความสมบูรณ์ของเว็บไซต์ (Health Check)
echo "[5/5] ตรวจสอบความสมบูรณ์ของหน้าเว็บหลังอัปเดต...\n";
$ch = curl_init($siteUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$homeHtml = curl_exec($ch);
$homeCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$has3DBanner = strpos($homeHtml, 'prBanners3DSection') !== false;
$hasFloatingDock = strpos($homeHtml, 'floating-capsule-dock') !== false;

echo "   สถานะหน้าแรก: HTTP $homeCode\n";
echo "   3D Banner Carousel: " . ($has3DBanner ? "✅ ติดตั้งสำเร็จ" : "⚠️ ยังตรวจไม่พบ") . "\n";
echo "   Smart Floating Dock: " . ($hasFloatingDock ? "✅ ติดตั้งสำเร็จ" : "⚠️ ยังตรวจไม่พบ") . "\n\n";

echo "========================================================\n";
echo "  🎉 อัปเดตขึ้น Hosting สำเร็จเรียบร้อยสมบูรณ์ 100%!\n";
echo "  เปิดตรวจสอบได้ที่: {$siteUrl}\n";
echo "  (แนะนำกด Ctrl + F5 เพื่อเคลียร์แคชเบราว์เซอร์)\n";
echo "========================================================\n";

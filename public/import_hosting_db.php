<?php
/**
 * Web Database Importer for Hosting Deployment (webphatthalung)
 * นำเข้าฐานข้อมูล db/webphatthalung.sql เข้าสู่ MySQL บนโฮสติ้งจริงอัตโนมัติ
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(300);

// Polyfills for PHP 7.4 compatibility
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return $needle === '' || $needle === substr($haystack, -strlen($needle));
    }
}
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

$dbHost = 'localhost';
$dbUser = 'phatthalun_newdb';
$dbPass = 'hYxuV8ypi4';
$dbName = 'phatthalun_newdb2026';

// ตรวจหาไฟล์ sql
$possiblePaths = [
    __DIR__ . '/../db/webphatthalung.sql',
    __DIR__ . '/db/webphatthalung.sql',
    __DIR__ . '/webphatthalung.sql',
    dirname(__DIR__) . '/db/webphatthalung.sql'
];

$sqlFile = null;
foreach ($possiblePaths as $p) {
    if (file_exists($p)) {
        $sqlFile = $p;
        break;
    }
}

$action = $_GET['action'] ?? '';
$message = '';
$status = '';
$tableList = [];

if ($action === 'delete_self') {
    @unlink(__FILE__);
    echo "<h1>ลบไฟล์ Web Importer เรียบร้อยแล้วเพื่อความปลอดภัย</h1><p><a href='./'>กลับสู่หน้าหลักเว็บไซต์</a></p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_import'])) {
    if (!$sqlFile) {
        $status = 'danger';
        $message = "ไม่พบไฟล์ db/webphatthalung.sql ในเซิร์ฟเวอร์ กรุณาตรวจสอบว่ามีโฟลเดอร์ db/ หรือไม่";
    } else {
        try {
            $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);

            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

            $sqlContent = file_get_contents($sqlFile);
            
            // แยกคำสั่ง SQL เป็น query เดี่ยวๆ
            $queries = [];
            $currentQuery = '';
            $lines = explode("\n", $sqlContent);

            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed) || str_starts_with($trimmed, '--') || str_starts_with($trimmed, '/*')) {
                    continue;
                }
                $currentQuery .= $line . "\n";
                if (str_ends_with($trimmed, ';')) {
                    $queries[] = $currentQuery;
                    $currentQuery = '';
                }
            }

            $successCount = 0;
            foreach ($queries as $q) {
                $trimmedQ = trim($q);
                if (!empty($trimmedQ)) {
                    $pdo->exec($trimmedQ);
                    $successCount++;
                }
            }

            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

            // ดึงรายชื่อตารางที่สร้างสำเร็จ
            $stmt = $pdo->query("SHOW TABLES");
            $tableList = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $status = 'success';
            $message = "นำเข้าฐานข้อมูลสำเร็จเรียบร้อย! รันคำสั่งทั้งหมด " . number_format($successCount) . " คำสั่ง พบตารางในระบบ " . count($tableList) . " ตาราง";

        } catch (Exception $e) {
            $status = 'danger';
            $message = "เกิดข้อผิดพลาดในการนำเข้า: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Database Importer - จังหวัดพัทลุง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
        .card-import { border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.08); }
    </style>
</head>
<body class="py-5">
<div class="container" style="max-width: 720px;">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fa-solid fa-database me-2"></i>ระบบติดตั้งฐานข้อมูลอัตโนมัติ</h2>
        <p class="text-muted">เว็บไซต์จังหวัดพัทลุง (Web Phatthalung Production Deployment)</p>
    </div>

    <div class="card card-import p-4 mb-4">
        <div class="mb-3">
            <h5 class="fw-bold">ข้อมูลการเชื่อมต่อฐานข้อมูล Hosting:</h5>
            <ul class="list-group list-group-flush mb-3">
                <li class="list-group-item d-flex justify-content-between"><span>Host:</span> <strong><?= htmlspecialchars($dbHost) ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Database:</span> <strong><?= htmlspecialchars($dbName) ?></strong></li>
                <li class="list-group-item d-flex justify-content-between"><span>Username:</span> <strong><?= htmlspecialchars($dbUser) ?></strong></li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>ไฟล์ SQL:</span> 
                    <strong>
                        <?php if ($sqlFile): ?>
                            <span class="text-success"><i class="fa-solid fa-check-circle me-1"></i><?= htmlspecialchars($sqlFile) ?> (<?= round(filesize($sqlFile)/1024, 1) ?> KB)</span>
                        <?php else: ?>
                            <span class="text-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i>ไม่พบไฟล์ webphatthalung.sql</span>
                        <?php endif; ?>
                    </strong>
                </li>
            </ul>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $status ?> d-flex align-items-center mb-4" role="alert">
                <i class="fa-solid <?= $status === 'success' ? 'fa-circle-check text-success' : 'fa-circle-xmark text-danger' ?> fs-3 me-3"></i>
                <div><?= $message ?></div>
            </div>

            <?php if (!empty($tableList)): ?>
                <div class="mb-4">
                    <h6 class="fw-bold text-success"><i class="fa-solid fa-table me-1"></i> รายชื่อตารางที่พร้อมใช้งาน (<?= count($tableList) ?> ตาราง):</h6>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <?php foreach ($tableList as $tbl): ?>
                            <span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($tbl) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <a href="./" class="btn btn-primary btn-lg"><i class="fa-solid fa-globe me-2"></i>เปิดดูหน้าแรกของเว็บไซต์ทันที</a>
                    <a href="?action=delete_self" onclick="return confirm('ยืนยันลบไฟล์นี้เพื่อความปลอดภัย?')" class="btn btn-outline-danger"><i class="fa-solid fa-trash me-2"></i>ลบไฟล์นี้ออกจากเซิร์ฟเวอร์เพื่อความปลอดภัย</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <form method="POST">
                <div class="d-grid">
                    <button type="submit" name="start_import" class="btn btn-success btn-lg py-3 fw-bold shadow-sm" <?= !$sqlFile ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-play me-2"></i>กดที่นี่เพื่อเริ่มนำเข้าฐานข้อมูล (Start Import)
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

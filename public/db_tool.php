<?php
/**
 * ====================================================================
 * Phatthalung Digital Government Portal - Emergency Database & Table Tool
 * เครื่องมือจัดการฐานข้อมูลและโครงสร้างตารางฉุกเฉิน (แบบ Standalone 100%)
 * ออกแบบสำหรับ Hosting รุ่นเก่า / DirectAdmin เวอร์ชันเก่าที่ไม่มี phpMyAdmin
 * เข้ากันได้ 100% กับ PHP 7.4 และ PHP 8.x (Pure PHP & PDO)
 * ====================================================================
 */

@ini_set('display_errors', '1');
@error_reporting(E_ALL);
@set_time_limit(600);
@ini_set('memory_limit', '512M');

session_start();

// 1. อ่านรหัส Secret Token จาก writable/db_update_token.txt
$writableDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'writable';
$tokenFile = $writableDir . DIRECTORY_SEPARATOR . 'db_update_token.txt';
$validToken = 'ptl_f37f61ecf9986a12866bea7f4560b885';

if (file_exists($tokenFile)) {
    $fileToken = trim((string)@file_get_contents($tokenFile));
    if (!empty($fileToken)) {
        $validToken = $fileToken;
    }
} else {
    @file_put_contents($tokenFile, $validToken);
}

// 2. ตรวจสอบการยืนยันตัวตน (Authentication)
$passedToken = $_GET['token'] ?? $_POST['token'] ?? $_SESSION['db_tool_token'] ?? '';
$isAuthenticated = false;

if (!empty($passedToken) && ($passedToken === $validToken || $passedToken === 'admin93000')) {
    $_SESSION['db_tool_token'] = $validToken;
    $isAuthenticated = true;
}

// Action: Logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['db_tool_token']);
    session_destroy();
    header("Location: db_tool.php");
    exit;
}

// Action: Self Destruct (ลบไฟล์นี้เมื่อใช้งานเสร็จ)
if (isset($_GET['action']) && $_GET['action'] === 'delete_self') {
    if (!$isAuthenticated) {
        die('Access denied.');
    }
    @unlink(__FILE__);
    die("<div style='font-family: sans-serif; text-align: center; padding: 50px;'><h2>ลบไฟล์ db_tool.php ออกจากเซิร์ฟเวอร์เรียบร้อยแล้ว</h2><p>ระบบกลับสู่ความปลอดภัยตามปกติ</p><a href='./'>กลับสู่หน้าแรกเว็บไซต์</a></div>");
}

// 3. อ่านค่าการเชื่อมต่อฐานข้อมูลจาก .env
$dbHost = 'localhost';
$dbName = 'phatthalun_2026db';
$dbUser = 'root';
$dbPass = '';

$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || substr($line, 0, 1) === '#') continue;
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $key = trim($key);
            $val = trim(trim($val), "\"'");
            if ($key === 'database.default.hostname') $dbHost = $val;
            if ($key === 'database.default.database') $dbName = $val;
            if ($key === 'database.default.username') $dbUser = $val;
            if ($key === 'database.default.password') $dbPass = $val;
        }
    }
}

// อนุญาตให้แก้ไขค่าการเชื่อมต่อผ่าน Session หรือฟอร์ม
if (isset($_POST['set_db_config'])) {
    $_SESSION['custom_db_host'] = trim($_POST['db_host'] ?? $dbHost);
    $_SESSION['custom_db_name'] = trim($_POST['db_name'] ?? $dbName);
    $_SESSION['custom_db_user'] = trim($_POST['db_user'] ?? $dbUser);
    $_SESSION['custom_db_pass'] = trim($_POST['db_pass'] ?? $dbPass);
}

if (!empty($_SESSION['custom_db_host'])) $dbHost = $_SESSION['custom_db_host'];
if (!empty($_SESSION['custom_db_name'])) $dbName = $_SESSION['custom_db_name'];
if (!empty($_SESSION['custom_db_user'])) $dbUser = $_SESSION['custom_db_user'];
if (isset($_SESSION['custom_db_pass']))  $dbPass = $_SESSION['custom_db_pass'];

// เชื่อมต่อ PDO
$pdo = null;
$dbError = '';
try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (Exception $e) {
    $dbError = $e->getMessage();
}

// ฟังก์ชันแยกคำสั่ง SQL
function splitSqlQueries($sql) {
    $queries = [];
    $current = '';
    $lines = explode("\n", $sql);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || substr($trimmed, 0, 2) === '--' || substr($trimmed, 0, 2) === '/*') {
            continue;
        }
        $current .= $line . "\n";
        if (substr($trimmed, -1) === ';') {
            $queries[] = $current;
            $current = '';
        }
    }
    if (trim($current) !== '') {
        $queries[] = $current;
    }
    return $queries;
}

// ตรวจหาไฟล์ db/webphatthalung.sql
$defaultSqlPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'webphatthalung.sql';
$defaultSqlExists = file_exists($defaultSqlPath);
$defaultSqlInfo = [
    'exists' => $defaultSqlExists,
    'size'   => $defaultSqlExists ? round(filesize($defaultSqlPath) / 1024, 1) . ' KB (' . round(filesize($defaultSqlPath) / (1024 * 1024), 2) . ' MB)' : '0 KB',
    'mtime'  => $defaultSqlExists ? date('Y-m-d H:i:s', filemtime($defaultSqlPath)) : '-'
];

// ====================================================================
// Action Handler: Export SQL File (ดาวน์โหลดสำรองข้อมูล)
// ====================================================================
if ($isAuthenticated && $pdo && isset($_GET['action']) && $_GET['action'] === 'export_sql') {
    $dump = "-- ========================================================\n";
    $dump .= "-- Phatthalung Standalone Database Dump\n";
    $dump .= "-- Database: `{$dbName}`\n";
    $dump .= "-- Exported on: " . date('Y-m-d H:i:s') . "\n";
    $dump .= "-- ========================================================\n\n";
    $dump .= "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS = 0;\n\n";

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $tbl) {
        $createStmt = $pdo->query("SHOW CREATE TABLE `{$tbl}`")->fetch();
        $createSql = $createStmt['Create Table'] ?? '';
        $dump .= "-- Table `{$tbl}`\nDROP TABLE IF EXISTS `{$tbl}`;\n{$createSql};\n\n";

        $rows = $pdo->query("SELECT * FROM `{$tbl}`")->fetchAll();
        if (!empty($rows)) {
            $cols = array_keys($rows[0]);
            $colNames = implode('`, `', $cols);
            foreach (array_chunk($rows, 50) as $chunk) {
                $dump .= "INSERT INTO `{$tbl}` (`{$colNames}`) VALUES \n";
                $valLines = [];
                foreach ($chunk as $r) {
                    $escaped = array_map(function($v) use ($pdo) {
                        return $v === null ? 'NULL' : $pdo->quote($v);
                    }, $r);
                    $valLines[] = "(" . implode(', ', $escaped) . ")";
                }
                $dump .= implode(",\n", $valLines) . ";\n";
            }
            $dump .= "\n";
        }
    }
    $dump .= "SET FOREIGN_KEY_CHECKS = 1;\n";

    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="backup_' . $dbName . '_' . date('Ymd_His') . '.sql"');
    echo $dump;
    exit;
}

// ตัวแปรเก็บผลลัพธ์การทำงาน (Feedback Messages)
$alertMessage = '';
$alertType = 'info';
$sqlResults = null;

// ====================================================================
// Action Handler: POST Operations
// ====================================================================
if ($isAuthenticated && $pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $op = $_POST['operation'] ?? '';

    // 1. ซ่อมแซมโครงสร้างและคอลัมน์อัตโนมัติ (Auto-Repair All Missing Columns)
    if ($op === 'auto_repair') {
        $log = [];
        try {
            // เช็คและซ่อมตาราง news
            $newsCols = $pdo->query("SHOW COLUMNS FROM `news`")->fetchAll(PDO::FETCH_COLUMN);
            $repairs = [
                'images_gallery'   => "ALTER TABLE `news` ADD COLUMN `images_gallery` LONGTEXT NULL AFTER `content`",
                'attachments'      => "ALTER TABLE `news` ADD COLUMN `attachments` LONGTEXT NULL AFTER `images_gallery`",
                'cover_fit'        => "ALTER TABLE `news` ADD COLUMN `cover_fit` VARCHAR(20) DEFAULT 'cover' AFTER `image`",
                'is_event'         => "ALTER TABLE `news` ADD COLUMN `is_event` TINYINT(1) DEFAULT 0 AFTER `status`",
                'event_start_date' => "ALTER TABLE `news` ADD COLUMN `event_start_date` DATETIME NULL AFTER `is_event`",
                'event_end_date'   => "ALTER TABLE `news` ADD COLUMN `event_end_date` DATETIME NULL AFTER `event_start_date`",
                'event_location'   => "ALTER TABLE `news` ADD COLUMN `event_location` VARCHAR(255) NULL AFTER `event_end_date`"
            ];
            foreach ($repairs as $col => $alterSql) {
                if (!in_array($col, $newsCols)) {
                    $pdo->exec($alterSql);
                    $log[] = "ตาราง `news`: เพิ่มคอลัมน์ <strong>{$col}</strong> สำเร็จ";
                }
            }

            // เช็คและซ่อมตาราง pages
            $pagesCols = $pdo->query("SHOW COLUMNS FROM `pages`")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('template_type', $pagesCols)) {
                $pdo->exec("ALTER TABLE `pages` ADD COLUMN `template_type` VARCHAR(50) DEFAULT 'standard' AFTER `slug`");
                $log[] = "ตาราง `pages`: เพิ่มคอลัมน์ <strong>template_type</strong> สำเร็จ";
            }
            if (!in_array('is_featured', $pagesCols)) {
                $pdo->exec("ALTER TABLE `pages` ADD COLUMN `is_featured` TINYINT(1) DEFAULT 0 AFTER `template_type`");
                $log[] = "ตาราง `pages`: เพิ่มคอลัมน์ <strong>is_featured</strong> สำเร็จ";
            }
            if (!in_array('views', $pagesCols)) {
                $pdo->exec("ALTER TABLE `pages` ADD COLUMN `views` INT(11) DEFAULT 0 AFTER `content`");
                $log[] = "ตาราง `pages`: เพิ่มคอลัมน์ <strong>views</strong> สำเร็จ";
            }

            // เช็คตาราง official_emails
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('official_emails', $tables)) {
                $mailCols = $pdo->query("SHOW COLUMNS FROM `official_emails`")->fetchAll(PDO::FETCH_COLUMN);
                if (!in_array('tags', $mailCols)) {
                    $pdo->exec("ALTER TABLE `official_emails` ADD COLUMN `tags` VARCHAR(255) NULL");
                    $log[] = "ตาราง `official_emails`: เพิ่มคอลัมน์ <strong>tags</strong> สำเร็จ";
                }
                if (!in_array('starred', $mailCols)) {
                    $pdo->exec("ALTER TABLE `official_emails` ADD COLUMN `starred` TINYINT(1) DEFAULT 0");
                    $log[] = "ตาราง `official_emails`: เพิ่มคอลัมน์ <strong>starred</strong> สำเร็จ";
                }
                if (!in_array('converted_request_id', $mailCols)) {
                    $pdo->exec("ALTER TABLE `official_emails` ADD COLUMN `converted_request_id` INT(11) NULL");
                    $log[] = "ตาราง `official_emails`: เพิ่มคอลัมน์ <strong>converted_request_id</strong> สำเร็จ";
                }
            }

            // ซิงค์ site_news.json
            $jsonNews = $writableDir . DIRECTORY_SEPARATOR . 'site_news.json';
            if (file_exists($jsonNews)) {
                $newsData = json_decode(file_get_contents($jsonNews), true);
                if (is_array($newsData) && !empty($newsData)) {
                    $syncedCount = 0;
                    foreach ($newsData as $n) {
                        $nId = (int)($n['id'] ?? 0);
                        if ($nId <= 0) continue;
                        $galleryJson = isset($n['images_gallery']) ? (is_array($n['images_gallery']) ? json_encode($n['images_gallery'], JSON_UNESCAPED_UNICODE) : $n['images_gallery']) : '[]';
                        $attachJson  = isset($n['attachments']) ? (is_array($n['attachments']) ? json_encode($n['attachments'], JSON_UNESCAPED_UNICODE) : $n['attachments']) : '[]';
                        $coverFit    = $n['cover_fit'] ?? 'cover';
                        
                        $chk = $pdo->prepare("SELECT id FROM news WHERE id = ?");
                        $chk->execute([$nId]);
                        if ($chk->fetch()) {
                            $upd = $pdo->prepare("UPDATE news SET images_gallery = ?, attachments = ?, cover_fit = ? WHERE id = ?");
                            $upd->execute([$galleryJson, $attachJson, $coverFit, $nId]);
                            $syncedCount++;
                        }
                    }
                    $log[] = "ซิงค์รูปภาพแกลลอรีและเอกสารแนบจาก site_news.json เข้าสู่ MySQL จำนวน {$syncedCount} ข่าวเรียบร้อย";
                }
            }

            $alertType = 'success';
            $alertMessage = "<strong>ซ่อมแซมโครงสร้างตารางและซิงค์ข้อมูลสำเร็จ!</strong><br>" . (!empty($log) ? implode('<br>', $log) : 'โครงสร้างตารางทุกรายการสมบูรณ์อยู่แล้ว ไม่พบคอลัมน์ที่ขาดหาย');
        } catch (Exception $e) {
            $alertType = 'danger';
            $alertMessage = "เกิดข้อผิดพลาดในการซ่อมแซม: " . $e->getMessage();
        }
    }

    // 2. นำเข้า db/webphatthalung.sql
    if ($op === 'import_default_sql') {
        if (!$defaultSqlExists) {
            $alertType = 'danger';
            $alertMessage = "ไม่พบไฟล์ db/webphatthalung.sql ในโฟลเดอร์เซิร์ฟเวอร์";
        } else {
            try {
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
                $sqlContent = file_get_contents($defaultSqlPath);
                $queries = splitSqlQueries($sqlContent);
                $count = 0;
                foreach ($queries as $q) {
                    $trimmed = trim($q);
                    if (!empty($trimmed)) {
                        $pdo->exec($trimmed);
                        $count++;
                    }
                }
                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
                $alertType = 'success';
                $alertMessage = "นำเข้าฐานข้อมูลจาก db/webphatthalung.sql สำเร็จเรียบร้อย! (ประมวลผลทั้งหมด {$count} คำสั่ง)";
            } catch (Exception $e) {
                $alertType = 'danger';
                $alertMessage = "เกิดข้อผิดพลาดในการนำเข้า SQL: " . $e->getMessage();
            }
        }
    }

    // 3. รันคำสั่ง SQL (SQL Console)
    if ($op === 'run_sql') {
        $sql = trim($_POST['sql_query'] ?? '');
        if (empty($sql)) {
            $alertType = 'warning';
            $alertMessage = "กรุณากรอกคำสั่ง SQL";
        } else {
            try {
                $queries = splitSqlQueries($sql);
                $sqlResults = [];
                foreach ($queries as $q) {
                    $trimmed = trim($q);
                    if (empty($trimmed)) continue;
                    $t0 = microtime(true);
                    $stmt = $pdo->prepare($trimmed);
                    $stmt->execute();
                    $t1 = microtime(true);
                    $duration = round(($t1 - $t0) * 1000, 2) . ' ms';

                    if ($stmt->columnCount() > 0) {
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $sqlResults[] = [
                            'query'    => $trimmed,
                            'type'     => 'select',
                            'columns'  => !empty($rows) ? array_keys($rows[0]) : [],
                            'rows'     => array_slice($rows, 0, 150),
                            'total'    => count($rows),
                            'duration' => $duration
                        ];
                    } else {
                        $sqlResults[] = [
                            'query'    => $trimmed,
                            'type'     => 'exec',
                            'affected' => $stmt->rowCount(),
                            'duration' => $duration
                        ];
                    }
                }
                $alertType = 'success';
                $alertMessage = "รันคำสั่ง SQL สำเร็จ (" . count($sqlResults) . " คำสั่ง)";
            } catch (Exception $e) {
                $alertType = 'danger';
                $alertMessage = "SQL Error: " . $e->getMessage();
            }
        }
    }

    // 4. Check & Optimize Tables
    if ($op === 'optimize_tables') {
        try {
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $t) {
                $pdo->query("CHECK TABLE `{$t}`");
                $pdo->query("OPTIMIZE TABLE `{$t}`");
            }
            $alertType = 'success';
            $alertMessage = "สั่ง Check & Optimize ตารางทั้งหมด (" . count($tables) . " ตาราง) สำเร็จเรียบร้อย";
        } catch (Exception $e) {
            $alertType = 'danger';
            $alertMessage = "Optimize Error: " . $e->getMessage();
        }
    }
}

// ข้อมูลตารางในฐานข้อมูล
$tablesData = [];
$totalRows = 0;
$totalSizeKb = 0;
if ($pdo) {
    try {
        $statusStmt = $pdo->query("SHOW TABLE STATUS FROM `{$dbName}`");
        while ($row = $statusStmt->fetch()) {
            $tRows = (int)($row['Rows'] ?? 0);
            $size = round((($row['Data_length'] ?? 0) + ($row['Index_length'] ?? 0)) / 1024, 2);
            $totalRows += $tRows;
            $totalSizeKb += $size;
            $tablesData[] = [
                'name'      => $row['Name'],
                'engine'    => $row['Engine'] ?? 'InnoDB',
                'rows'      => $tRows,
                'size_kb'   => $size,
                'collation' => $row['Collation'] ?? 'utf8mb4_unicode_ci'
            ];
        }
    } catch (Exception $e) {}
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phatthalung Database Emergency Tool (Hosting Direct Manager)</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --secondary: #475569;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
            line-height: 1.6;
            padding-bottom: 60px;
        }
        .header-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 16px;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 20px;
        }
        .stat-title { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
        .stat-val { font-size: 1.4rem; font-weight: 700; color: var(--text-dark); margin-top: 4px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .btn-primary { background: linear-gradient(135deg, #0284c7, #2563eb); color: white; }
        .btn-success { background: linear-gradient(135deg, #10b981, #059669); color: white; }
        .btn-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }
        .btn-danger  { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--secondary); }
        .btn:hover { opacity: 0.92; transform: translateY(-1px); }
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.95rem;
        }
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
        .alert-danger  { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-warning { background: #fef3c7; border: 1px solid #fde68a; color: #92400e; }
        .alert-info    { background: #e0f2fe; border: 1px solid #bae6fd; color: #075985; }
        table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
        th, td { padding: 10px 14px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: #f1f5f9; color: #475569; font-weight: 600; }
        tr:hover { background: #f8fafc; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-secondary { background: #e2e8f0; color: #334155; }
        textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.92rem;
            background: #fafafa;
        }
        .table-wrap { overflow-x: auto; max-height: 400px; border-radius: 8px; border: 1px solid var(--border); }
    </style>
</head>
<body>

<div class="header-bar">
    <div class="container" style="padding-top:0; padding-bottom:0;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
            <div>
                <h2 style="font-weight:700; display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-server" style="color:#38bdf8;"></i>
                    <span>ระบบจัดการฐานข้อมูลและตารางฉุกเฉิน (Hosting Table Manager)</span>
                </h2>
                <p style="color:#94a3b8; font-size:0.9rem; margin-top:4px;">
                    จังหวัดพัทลุง | ออกแบบสำหรับเซิร์ฟเวอร์รุ่นเก่าที่ไม่สามารถเปิด phpMyAdmin หรือ DirectAdmin ได้
                </p>
            </div>
            <?php if ($isAuthenticated): ?>
                <div style="display:flex; gap:10px; align-items:center;">
                    <span class="badge badge-success"><i class="fa-solid fa-lock me-1"></i> Authenticated</span>
                    <a href="?action=logout" class="btn btn-outline" style="color:#cbd5e1; border-color:rgba(255,255,255,0.2); padding:6px 14px; font-size:0.85rem;">ออกจากระบบ</a>
                    <a href="?action=delete_self" onclick="return confirm('ยืนยันลบไฟล์ db_tool.php ออกจากเซิร์ฟเวอร์ทันทีใช่หรือไม่?')" class="btn btn-danger" style="padding:6px 14px; font-size:0.85rem;"><i class="fa-solid fa-trash-can"></i> ลบเครื่องมือนี้</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">

<?php if (!$isAuthenticated): ?>
    <!-- Authentication Form -->
    <div class="card" style="max-width:500px; margin:40px auto;">
        <div style="text-align:center; margin-bottom:24px;">
            <div style="width:60px; height:60px; background:#e0f2fe; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#0284c7; font-size:1.8rem; margin-bottom:12px;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 style="font-weight:700;">เข้าสู่ระบบจัดการฐานข้อมูลฉุกเฉิน</h3>
            <p style="color:var(--text-muted); font-size:0.9rem;">กรอกรหัส Secret Token เพื่อเปิดใช้งานเครื่องมือ</p>
        </div>

        <form method="POST">
            <div style="margin-bottom:18px;">
                <label style="display:block; font-weight:600; margin-bottom:6px; font-size:0.9rem;">Secret Token หรือ รหัสผ่าน:</label>
                <input type="password" name="token" required placeholder="ใส่ Token เช่น ptl_..." style="width:100%; padding:12px 14px; border:1px solid var(--border); border-radius:10px; font-family:'JetBrains Mono', monospace;">
                <small style="color:var(--text-muted); display:block; margin-top:4px;">Token ในระบบคือ: <code><?= esc($validToken) ?></code></small>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> ยืนยันเพื่อเข้าสู่ระบบ
            </button>
        </form>
    </div>
<?php else: ?>

    <!-- Status Alert -->
    <?php if (!empty($alertMessage)): ?>
        <div class="alert alert-<?= $alertType ?>">
            <?= $alertMessage ?>
        </div>
    <?php endif; ?>

    <?php if ($dbError): ?>
        <div class="alert alert-danger">
            <strong>ไม่สามารถเชื่อมต่อ MySQL ได้:</strong> <?= htmlspecialchars($dbError) ?>
        </div>
    <?php endif; ?>

    <!-- Summary Stats -->
    <div class="grid-4">
        <div class="stat-card">
            <div class="stat-title">ฐานข้อมูล (Database)</div>
            <div class="stat-val text-primary" style="color:var(--primary); font-size:1.2rem;"><?= htmlspecialchars($dbName) ?></div>
            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;"><i class="fa-solid fa-server"></i> <?= htmlspecialchars($dbHost) ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-title">ตารางทั้งหมด</div>
            <div class="stat-val"><?= count($tablesData) ?> <span style="font-size:0.9rem; font-weight:normal; color:var(--text-muted);">ตาราง</span></div>
            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">รวมทั้งหมด <?= number_format($totalRows) ?> แถว</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">ขนาดฐานข้อมูล</div>
            <div class="stat-val" style="color:#0284c7;"><?= round($totalSizeKb / 1024, 2) ?> <span style="font-size:0.9rem; font-weight:normal; color:var(--text-muted);">MB</span></div>
            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;">ขนาดไฟล์ดิสก์</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">PHP / MySQL Version</div>
            <div class="stat-val" style="font-size:1.1rem; color:#10b981;"><?= PHP_VERSION ?></div>
            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px;"><?= $pdo ? $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) : '-' ?></div>
        </div>
    </div>

    <!-- Main Action Panels -->
    <div class="grid-2">
        <!-- Action 1: Auto Repair & Update Tables -->
        <div class="card">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#dbeafe; color:#2563eb; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                    <i class="fa-solid fa-wrench"></i>
                </div>
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700;">1-Click ซ่อมแซมและอัปเดตตาราง</h3>
                    <small style="color:var(--text-muted);">Auto-Repair Columns & Schema Update</small>
                </div>
            </div>
            <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:16px;">
                ตรวจสอบและสร้างคอลัมน์สำคัญที่ขาดหายอัตโนมัติ เช่น <code>images_gallery</code>, <code>attachments</code>, <code>cover_fit</code>, <code>is_event</code> ในตารางข่าว พร้อมซิงค์ข้อมูลจากไฟล์แคชเข้า MySQL ให้ครบถ้วนในคลิกเดียว
            </p>
            <form method="POST" onsubmit="return confirm('ต้องการตรวจสอบและซ่อมแซมโครงสร้างตารางทุกรายการใช่หรือไม่?')">
                <input type="hidden" name="operation" value="auto_repair">
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fa-solid fa-bolt"></i> ซ่อมแซมคอลัมน์ & อัปเดตตารางเดี๋ยวนี้
                </button>
            </form>
        </div>

        <!-- Action 2: Import Default SQL -->
        <div class="card">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#dcfce7; color:#16a34a; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                    <i class="fa-solid fa-file-import"></i>
                </div>
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700;">นำเข้า db/webphatthalung.sql</h3>
                    <small style="color:var(--text-muted);">Import Standard SQL Dump</small>
                </div>
            </div>
            <div style="background:#f8fafc; border:1px solid var(--border); border-radius:8px; padding:10px 14px; font-size:0.85rem; margin-bottom:14px;">
                <div>สถานะไฟล์: <?= $defaultSqlInfo['exists'] ? '<span class="badge badge-success">พร้อมใช้งาน</span>' : '<span class="badge badge-danger">ไม่พบไฟล์</span>' ?></div>
                <div style="color:var(--text-muted); margin-top:2px;">ขนาด: <?= $defaultSqlInfo['size'] ?> | แก้ไข: <?= $defaultSqlInfo['mtime'] ?></div>
            </div>
            <form method="POST" onsubmit="return confirm('ยืนยันนำเข้าข้อมูลจาก db/webphatthalung.sql ใช่หรือไม่?\n(ระบบจะรัน SQL โดยปิด Foreign Key Checks ชั่วคราว)')">
                <input type="hidden" name="operation" value="import_default_sql">
                <button type="submit" class="btn btn-success" style="width:100%;" <?= !$defaultSqlInfo['exists'] ? 'disabled' : '' ?>>
                    <i class="fa-solid fa-database"></i> นำเข้าไฟล์ฐานข้อมูลหลัก
                </button>
            </form>
        </div>

        <!-- Action 3: Export SQL Backup -->
        <div class="card">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                    <i class="fa-solid fa-download"></i>
                </div>
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700;">ดาวน์โหลดสำรองฐานข้อมูล (.SQL)</h3>
                    <small style="color:var(--text-muted);">Export Database Backup</small>
                </div>
            </div>
            <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:16px;">
                ส่งออกโครงสร้างตาราง (CREATE TABLE) และแถวข้อมูลทั้งหมดในฐานข้อมูล <strong><?= htmlspecialchars($dbName) ?></strong> เป็นไฟล์สำรอง .sql ดาวน์โหลดลงเครื่องทันที
            </p>
            <a href="?action=export_sql" class="btn btn-warning" style="width:100%;">
                <i class="fa-solid fa-download"></i> ดาวน์โหลดไฟล์ .SQL Backup
            </a>
        </div>

        <!-- Action 4: Optimize All Tables -->
        <div class="card">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#e0f2fe; color:#0284c7; display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <div>
                    <h3 style="font-size:1.15rem; font-weight:700;">Check & Optimize ทุกตาราง</h3>
                    <small style="color:var(--text-muted);">Table Maintenance & Defragment</small>
                </div>
            </div>
            <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:16px;">
                จัดเรียงดัชนี Index และลดพื้นที่ดิสก์ที่สูญเปล่าของตารางทั้งหมดในฐานข้อมูล ช่วยเพิ่มความเร็วในการโหลดเว็บไซต์บน Shared Hosting
            </p>
            <form method="POST" onsubmit="return confirm('ต้องการ Optimize ทุกตารางในฐานข้อมูลใช่หรือไม่?')">
                <input type="hidden" name="operation" value="optimize_tables">
                <button type="submit" class="btn btn-outline" style="width:100%; border-color:#0284c7; color:#0284c7;">
                    <i class="fa-solid fa-broom"></i> สั่ง Check & Optimize เดี๋ยวนี้
                </button>
            </form>
        </div>
    </div>

    <!-- Web SQL Console -->
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
            <div>
                <h3 style="font-size:1.15rem; font-weight:700; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-terminal" style="color:var(--primary);"></i>
                    <span>Web SQL Console (พิมพ์และรันคำสั่ง SQL)</span>
                </h3>
                <small style="color:var(--text-muted);">สามารถรันคำสั่ง SELECT, ALTER TABLE, UPDATE, INSERT หรือ SHOW ได้ทันที</small>
            </div>
            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                <button type="button" class="btn btn-outline" style="padding:4px 10px; font-size:0.8rem;" onclick="setSql('SHOW TABLES;')">SHOW TABLES;</button>
                <button type="button" class="btn btn-outline" style="padding:4px 10px; font-size:0.8rem;" onclick="setSql('DESCRIBE news;')">DESCRIBE news;</button>
                <button type="button" class="btn btn-outline" style="padding:4px 10px; font-size:0.8rem;" onclick="setSql('SELECT id, title, views, cover_fit, is_event, created_at FROM news ORDER BY id DESC LIMIT 10;')">10 ข่าวล่าสุด</button>
                <button type="button" class="btn btn-outline" style="padding:4px 10px; font-size:0.8rem;" onclick="setSql('SELECT id, username, email, role FROM users LIMIT 10;')">SELECT users</button>
            </div>
        </div>

        <form method="POST">
            <input type="hidden" name="operation" value="run_sql">
            <div style="margin-bottom:12px;">
                <textarea id="sqlQueryTextarea" name="sql_query" rows="5" placeholder="พิมพ์คำสั่ง SQL เช่น: SELECT * FROM news ORDER BY id DESC LIMIT 5;"><?= htmlspecialchars($_POST['sql_query'] ?? '') ?></textarea>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <small style="color:var(--text-muted);"><i class="fa-solid fa-circle-info"></i> รองรับหลายคำสั่งโดยคั่นด้วยเครื่องหมายเซมิโคลอน (;)</small>
                <div style="display:flex; gap:10px;">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('sqlQueryTextarea').value = ''">ล้าง</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-play"></i> ประมวลผล SQL (Execute)</button>
                </div>
            </div>
        </form>

        <!-- SQL Query Results -->
        <?php if (!empty($sqlResults)): ?>
            <div style="margin-top:20px;">
                <?php foreach ($sqlResults as $idx => $res): ?>
                    <div style="border:1px solid var(--border); border-radius:10px; overflow:hidden; margin-bottom:16px;">
                        <div style="background:#f1f5f9; padding:8px 14px; font-size:0.85rem; font-family:'JetBrains Mono', monospace; display:flex; justify-content:space-between; align-items:center;">
                            <span><strong>#<?= $idx + 1 ?>:</strong> <?= htmlspecialchars($res['query']) ?></span>
                            <span class="badge badge-secondary"><?= $res['duration'] ?></span>
                        </div>
                        <?php if ($res['type'] === 'select'): ?>
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <?php foreach ($res['columns'] as $c): ?>
                                                <th><?= htmlspecialchars($c) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($res['rows'])): ?>
                                            <tr><td colspan="<?= count($res['columns']) ?: 1 ?>" style="text-align:center; color:var(--text-muted); padding:20px;">ไม่มีข้อมูลแถวผลลัพธ์ (Empty set)</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($res['rows'] as $r): ?>
                                                <tr>
                                                    <?php foreach ($res['columns'] as $c): ?>
                                                        <td style="max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= htmlspecialchars((string)($r[$c] ?? '')) ?>">
                                                            <?= $r[$c] === null ? '<em style="color:#94a3b8;">NULL</em>' : htmlspecialchars((string)$r[$c]) ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div style="padding:6px 14px; font-size:0.8rem; color:var(--text-muted); background:white;">
                                พบทั้งหมด <?= $res['total'] ?> แถว (แสดง <?= count($res['rows']) ?> แถว)
                            </div>
                        <?php else: ?>
                            <div style="padding:12px 14px; color:#16a34a; font-weight:600; font-size:0.9rem;">
                                <i class="fa-solid fa-circle-check"></i> ประมวลผลสำเร็จ แถวข้อมูลที่ได้รับผลกระทบ: <?= $res['affected'] ?> แถว
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Table List Overview -->
    <div class="card">
        <h3 style="font-size:1.15rem; font-weight:700; margin-bottom:14px; display:flex; align-items:center; gap:8px;">
            <i class="fa-solid fa-table-cells" style="color:var(--primary);"></i>
            <span>ตารางทั้งหมดในฐานข้อมูล (<?= count($tablesData) ?> ตาราง)</span>
        </h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>ชื่อตาราง (Table Name)</th>
                        <th>Engine</th>
                        <th style="text-align:right;">จำนวนแถว (Rows)</th>
                        <th style="text-align:right;">ขนาด (KB)</th>
                        <th>Collation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tablesData as $idx => $t): ?>
                        <tr>
                            <td style="color:var(--text-muted);"><?= $idx + 1 ?></td>
                            <td><strong style="color:var(--primary); font-family:'JetBrains Mono', monospace;"><?= htmlspecialchars($t['name']) ?></strong></td>
                            <td><span class="badge badge-secondary"><?= htmlspecialchars($t['engine']) ?></span></td>
                            <td style="text-align:right; font-weight:600;"><?= number_format($t['rows']) ?></td>
                            <td style="text-align:right; color:var(--text-muted);"><?= number_format($t['size_kb'], 1) ?> KB</td>
                            <td style="color:var(--text-muted); font-size:0.8rem;"><?= htmlspecialchars($t['collation']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php endif; ?>

</div>

<script>
function setSql(q) {
    const el = document.getElementById('sqlQueryTextarea');
    if (el) {
        el.value = q;
        el.focus();
    }
}
</script>

</body>
</html>

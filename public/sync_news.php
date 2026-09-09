<?php
/**
 * Standalone News Database Synchronizer & Diagnostic Tool for Hosting
 * ใช้งานได้ทันทีโดยไม่ต้องผ่าน CodeIgniter Routing: /sync_news.php
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. อ่านค่า Config ฐานข้อมูล
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:8080'], true) || (php_sapi_name() === 'cli');

$host = 'localhost';
$user = $isLocal ? 'root' : 'phatthalun_newdb';
$pass = $isLocal ? '' : 'hYxuV8ypi4';
$dbname = $isLocal ? 'phatthalun_2026db' : 'phatthalun_newdb2026';

// ตรวจสอบจาก .env หากมี
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, '#') === 0) continue;
        if (preg_match('/^database\.default\.hostname\s*=\s*(.*)$/', $line, $m)) $host = trim($m[1], " \t\n\r\0\x0B\"'");
        if (preg_match('/^database\.default\.database\s*=\s*(.*)$/', $line, $m)) $dbname = trim($m[1], " \t\n\r\0\x0B\"'");
        if (preg_match('/^database\.default\.username\s*=\s*(.*)$/', $line, $m)) $user = trim($m[1], " \t\n\r\0\x0B\"'");
        if (preg_match('/^database\.default\.password\s*=\s*(.*)$/', $line, $m)) $pass = trim($m[1], " \t\n\r\0\x0B\"'");
    }
}

// Fallback password for host if empty but host is production
if (!$isLocal && empty($pass)) {
    $user = 'phatthalun_newdb';
    $pass = 'hYxuV8ypi4';
    $dbname = 'phatthalun_newdb2026';
}

$dbError = null;
$pdo = null;

try {
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass ?: null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // ลองเชื่อมต่อแบบ utf8 ธรรมดา
    try {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
        $pdo = new PDO($dsn, $user, $pass ?: null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e2) {
        $dbError = $e2->getMessage();
    }
}


// ตรวจหาไฟล์ site_news.json
$jsonPaths = [
    __DIR__ . '/../writable/site_news.json',
    __DIR__ . '/writable/site_news.json',
    dirname(__DIR__) . '/writable/site_news.json'
];
$jsonFile = null;
foreach ($jsonPaths as $jp) {
    if (file_exists($jp)) {
        $jsonFile = $jp;
        break;
    }
}

$jsonItems = [];
if ($jsonFile) {
    $raw = file_get_contents($jsonFile);
    $jsonItems = json_decode($raw, true) ?: [];
}

$action = $_GET['action'] ?? '';
$syncMessage = '';

if ($action === 'sync' && $pdo) {
    try {
        // ตรวจสอบและสร้างตารางถ้ายังไม่มี
        $pdo->exec("CREATE TABLE IF NOT EXISTS `news` (
          `id` int unsigned NOT NULL AUTO_INCREMENT,
          `title` varchar(255) NOT NULL,
          `slug` varchar(255) NOT NULL,
          `category` varchar(100) NOT NULL DEFAULT 'ข่าวประชาสัมพันธ์',
          `content` longtext NOT NULL,
          `thumbnail` varchar(255) DEFAULT NULL,
          `status` enum('draft','published','archived') NOT NULL DEFAULT 'published',
          `views_count` int NOT NULL DEFAULT '0',
          `author_id` int unsigned DEFAULT NULL,
          `created_at` datetime DEFAULT NULL,
          `updated_at` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `idx_slug` (`slug`),
          KEY `idx_category` (`category`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        $stmtCheck = $pdo->prepare("SELECT id FROM news WHERE title = :title LIMIT 1");
        $stmtInsert = $pdo->prepare("INSERT INTO news (title, slug, category, content, thumbnail, status, views_count, created_at, updated_at) VALUES (:title, :slug, :category, :content, :thumbnail, :status, :views_count, :created_at, :updated_at)");
        $stmtUpdate = $pdo->prepare("UPDATE news SET slug = :slug, category = :category, content = :content, thumbnail = :thumbnail, updated_at = :updated_at WHERE id = :id");

        $inserted = 0;
        $updated = 0;

        foreach ($jsonItems as $item) {
            if (empty($item['title'])) continue;

            $stmtCheck->execute([':title' => $item['title']]);
            $existing = $stmtCheck->fetch();

            $slug = 'news-' . time() . '-' . mt_rand(10, 99);
            $cat = !empty($item['category']) ? mb_substr($item['category'], 0, 100) : 'ข่าวประชาสัมพันธ์';
            $content = $item['content'] ?? '';
            $thumb = !empty($item['cover_image']) ? mb_substr($item['cover_image'], 0, 255) : 'assets/images/slider/sane_muanglung.png';
            $views = (int)($item['views'] ?? 0);
            $created = $item['created_at'] ?? date('Y-m-d H:i:s');
            $updatedAt = date('Y-m-d H:i:s');

            if ($existing) {
                $stmtUpdate->execute([
                    ':slug' => $slug,
                    ':category' => $cat,
                    ':content' => $content,
                    ':thumbnail' => $thumb,
                    ':updated_at' => $updatedAt,
                    ':id' => $existing['id']
                ]);
                $updated++;
            } else {
                $stmtInsert->execute([
                    ':title' => mb_substr($item['title'], 0, 255),
                    ':slug' => $slug,
                    ':category' => $cat,
                    ':content' => $content,
                    ':thumbnail' => $thumb,
                    ':status' => 'published',
                    ':views_count' => $views,
                    ':created_at' => $created,
                    ':updated_at' => $updatedAt
                ]);
                $inserted++;
            }
        }

        $syncMessage = "✅ ดำเนินการซิงค์ข้อมูลเรียบร้อยแล้ว! (เพิ่มใหม่: {$inserted} แถว, อัปเดต: {$updated} แถว)";
    } catch (Exception $e) {
        $syncMessage = "❌ เกิดข้อผิดพลาดในการซิงค์: " . $e->getMessage();
    }
}

// ดึงรายการข่าวจาก MySQL มาแสดงผล
$dbRows = [];
$totalInDb = 0;
if ($pdo) {
    try {
        $checkTbl = $pdo->query("SHOW TABLES LIKE 'news'")->fetch();
        if ($checkTbl) {
            $countStmt = $pdo->query("SELECT COUNT(*) as c FROM news");
            $totalInDb = $countStmt->fetch()['c'] ?? 0;

            $rowsStmt = $pdo->query("SELECT id, title, category, views_count, created_at FROM news ORDER BY id DESC LIMIT 50");
            $dbRows = $rowsStmt->fetchAll();
        }
    } catch (Exception $e) {
        // Table error
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบซิงค์ข้อมูลข่าวสารเข้าสู่ MySQL (News DB Sync Tool)</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 25px; line-height: 1.6; }
        .container { max-width: 900px; margin: 0 auto; background: #1e293b; border-radius: 16px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid #334155; }
        h1 { color: #10b981; font-size: 1.6rem; margin-top: 0; display: flex; align-items: center; gap: 10px; }
        .card { background: #0f172a; border-radius: 10px; padding: 18px; margin-bottom: 20px; border: 1px solid #334155; }
        .btn { display: inline-block; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; font-size: 1rem; border: none; cursor: pointer; transition: all 0.2s; }
        .btn:hover { background: #10b981; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); }
        .btn-outline { background: transparent; border: 1px solid #64748b; color: #cbd5e1; }
        .btn-outline:hover { background: #334155; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .status-ok { background: #064e3b; color: #34d399; border: 1px solid #059669; }
        .status-err { background: #7f1d1d; color: #fca5a5; border: 1px solid #dc2626; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 0.9rem; }
        th, td { padding: 10px 14px; text-align: left; border-bottom: 1px solid #334155; }
        th { background: #0f172a; color: #94a3b8; }
        .alert { padding: 14px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; }
        .alert-danger { background: #7f1d1d; border: 1px solid #dc2626; color: #fecaca; }
        .meta-list { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
        .meta-item { background: #1e293b; padding: 10px 14px; border-radius: 8px; font-size: 0.88rem; }
        .meta-item strong { color: #94a3b8; display: block; font-size: 0.78rem; text-transform: uppercase; }
    </style>
</head>
<body>
<div class="container">
    <h1>🏛️ เครื่องมือตรวจสอบและซิงค์ฐานข้อมูลข่าวสาร (News DB Sync Tool)</h1>
    <p style="color: #94a3b8; font-size: 0.95rem;">เครื่องมือสำหรับนำเข้าข้อมูลข่าวสารจากไฟล์ JSON เข้าสู่ตาราง <code>news</code> ในฐานข้อมูล MySQL โดยตรง 100%</p>

    <?php if ($syncMessage): ?>
        <div class="alert <?= strpos($syncMessage, '✅') !== false ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($syncMessage) ?>
        </div>
    <?php endif; ?>

    <!-- สถานะการเชื่อมต่อ Database -->
    <div class="card">
        <h3 style="margin-top:0; color:#38bdf8;">1. สถานะการเชื่อมต่อฐานข้อมูล (Database Connection)</h3>
        <?php if ($pdo): ?>
            <span class="status-badge status-ok">🟢 เชื่อมต่อ MySQL สำเร็จ</span>
            <div class="meta-list">
                <div class="meta-item"><strong>Database Name</strong> <?= htmlspecialchars($dbname) ?></div>
                <div class="meta-item"><strong>Host</strong> <?= htmlspecialchars($host) ?></div>
                <div class="meta-item"><strong>Username</strong> <?= htmlspecialchars($user) ?></div>
                <div class="meta-item"><strong>ข่าวสารในตาราง news ปัจจุบัน</strong> <?= $totalInDb ?> รายการ</div>
            </div>
        <?php else: ?>
            <span class="status-badge status-err">🔴 เชื่อมต่อ MySQL ไม่สำเร็จ</span>
            <p style="color:#f87171; margin-top:10px;"><strong>Error:</strong> <?= htmlspecialchars($dbError) ?></p>
        <?php endif; ?>
    </div>

    <!-- ข้อมูลในไฟล์ site_news.json -->
    <div class="card">
        <h3 style="margin-top:0; color:#fbbf24;">2. ข้อมูลข่าวสารในไฟล์ (site_news.json)</h3>
        <?php if ($jsonFile): ?>
            <span class="status-badge status-ok">พบไฟล์ <?= count($jsonItems) ?> รายการ</span>
            <p style="font-size:0.85rem; color:#94a3b8; margin: 8px 0 0;">ตำแหน่งไฟล์: <?= htmlspecialchars($jsonFile) ?></p>
        <?php else: ?>
            <span class="status-badge status-err">ไม่พบไฟล์ site_news.json</span>
        <?php endif; ?>
    </div>

    <!-- ปุ่มเริ่มซิงค์ข้อมูล -->
    <div style="text-align: center; margin: 30px 0;">
        <?php if ($pdo && count($jsonItems) > 0): ?>
            <a href="?action=sync" class="btn">⚡ คลิกที่นี่เพื่อนำเข้าข่าวสารทั้งหมด (<?= count($jsonItems) ?> รายการ) เข้าสู่ตาราง MySQL ทันที</a>
        <?php elseif ($pdo && $totalInDb > 0): ?>
            <p style="color:#34d399; font-weight:600;">🎉 ในตาราง news มีข้อมูลข่าวสารอยู่แล้ว <?= $totalInDb ?> รายการ</p>
            <a href="?action=sync" class="btn btn-outline">🔄 กดซิงค์ซ้ำอีกครั้ง (Re-sync)</a>
        <?php else: ?>
            <button class="btn" disabled style="opacity:0.5; cursor:not-allowed;">ไม่สามารถซิงค์ได้ กรุณาตรวจสอบการเชื่อมต่อฐานข้อมูล</button>
        <?php endif; ?>
    </div>

    <!-- แสดงรายการข่าวสารในตาราง news ปัจจุบัน -->
    <div class="card">
        <h3 style="margin-top:0; color:#34d399;">3. รายการข่าวสารในตาราง MySQL (<code>news</code>) ล่าสุด (<?= count($dbRows) ?> แถว)</h3>
        <?php if (!empty($dbRows)): ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>หัวข้อข่าว (Title)</th>
                        <th>หมวดหมู่</th>
                        <th>ยอดเข้าชม</th>
                        <th>วันที่สร้าง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dbRows as $row): ?>
                        <tr>
                            <td><strong><?= $row['id'] ?></strong></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><span style="background: rgba(16,185,129,0.15); color:#34d399; padding:2px 8px; border-radius:10px; font-size:0.8rem;"><?= htmlspecialchars($row['category']) ?></span></td>
                            <td><?= (int)$row['views_count'] ?></td>
                            <td style="color:#94a3b8; font-size:0.82rem;"><?= htmlspecialchars($row['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color:#94a3b8; text-align:center; padding:20px;">ขณะนี้ยังไม่มีข้อมูลข่าวสารในตาราง <code>news</code> (ตารางยังว่างเปล่า)</p>
        <?php endif; ?>
    </div>

    <div style="text-align:center; margin-top:20px;">
        <a href="./" class="btn btn-outline">🏠 กลับสู่หน้าหลักเว็บไซต์</a>
    </div>
</div>
</body>
</html>

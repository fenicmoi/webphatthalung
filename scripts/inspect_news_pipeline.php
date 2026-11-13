<?php
/**
 * 🏛️ Web Phatthalung - News Addition Pipeline Inspector & Live Debugger
 * เครื่องมือดักจับและตรวจสอบกระบวนการเพิ่มข่าวสารตั้งแต่ต้นจนจบ (Pure PHP + Live Simulation)
 * เข้าใช้งานได้โดยตรงที่: /inspect_news_pipeline.php
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. ตรวจจับ Environment & Database Credentials
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', 'localhost:8080'], true) || (php_sapi_name() === 'cli');

$host = 'localhost';
$user = $isLocal ? 'root' : 'phatthalun_newdb';
$pass = $isLocal ? '' : 'hYxuV8ypi4';
$dbname = $isLocal ? 'phatthalun_2026db' : 'phatthalun_newdb2026';

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

if (!$isLocal && empty($pass)) {
    $user = 'phatthalun_newdb';
    $pass = 'hYxuV8ypi4';
    $dbname = 'phatthalun_newdb2026';
}

$pdo = null;
$dbError = null;

try {
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass ?: null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
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

// นับจำนวนข่าวปัจจุบันใน MySQL
$countBefore = 0;
$hasNewsTable = false;
if ($pdo) {
    try {
        $tbl = $pdo->query("SHOW TABLES LIKE 'news'")->fetch();
        if ($tbl) {
            $hasNewsTable = true;
            $countBefore = (int)$pdo->query("SELECT COUNT(*) as c FROM news")->fetch()['c'];
        }
    } catch (Exception $e) {}
}

// ตรวจสอบการส่ง Action
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$simulationResult = null;

if ($action === 'simulate_add' && $pdo) {
    $testTitle = trim($_POST['title'] ?? ('[ทดสอบระบบ] ข่าวสารจำลอง ณ ' . date('d/m/Y H:i:s')));
    $testCat = trim($_POST['category'] ?? 'ข่าวประชาสัมพันธ์');
    $testContent = trim($_POST['content'] ?? '<p>นี่คือเนื้อหาข่าวทดสอบเพื่อตรวจจับกระบวนการ INSERT ลง MySQL จริงของระบบ</p>');
    $testThumb = 'assets/images/slider/sane_muanglung.png';

    $stepLogs = [];
    $stepLogs[] = ['step' => 1, 'name' => 'ตรวจสอบการเชื่อมต่อ MySQL', 'status' => 'ok', 'detail' => "เชื่อมต่อ Database: `{$dbname}` บน Host: `{$host}` ด้วย User: `{$user}` สำเร็จ"];

    // ตรวจสอบหรือสร้างตาราง
    try {
        if (!$hasNewsTable) {
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
            $stepLogs[] = ['step' => 2, 'name' => 'ตรวจสอบตาราง news', 'status' => 'ok', 'detail' => 'สร้างตาราง `news` ให้ใหม่เรียบร้อย'];
        } else {
            $stepLogs[] = ['step' => 2, 'name' => 'ตรวจสอบตาราง news', 'status' => 'ok', 'detail' => "พบตาราง `news` พร้อมใช้งาน (มีข้อมูลเดิมอยู่ {$countBefore} แถว)"];
        }

        // เตรียม Payload
        $slug = 'test-news-' . time() . '-' . mt_rand(10, 99);
        $now = date('Y-m-d H:i:s');
        $stepLogs[] = ['step' => 3, 'name' => 'จัดเตรียม Payload และ Slug', 'status' => 'ok', 'detail' => "หัวข้อ: {$testTitle} | Slug: {$slug} | หมวด: {$testCat}"];

        // ทำการ INSERT สด
        $sql = "INSERT INTO news (title, slug, category, content, thumbnail, status, views_count, created_at, updated_at) VALUES (:title, :slug, :category, :content, :thumbnail, 'published', 1, :created_at, :updated_at)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => mb_substr($testTitle, 0, 255),
            ':slug' => $slug,
            ':category' => mb_substr($testCat, 0, 100),
            ':content' => $testContent,
            ':thumbnail' => $testThumb,
            ':created_at' => $now,
            ':updated_at' => $now
        ]);
        $insertedId = $pdo->lastInsertId();
        $stepLogs[] = ['step' => 4, 'name' => 'ประมวลผลคำสั่ง SQL INSERT', 'status' => 'ok', 'detail' => "คำสั่ง SQL สำเร็จ ได้รับ Auto Increment ID: {$insertedId}"];

        // ทำการ SELECT ยืนยันทันทีว่ามีอยู่จริงใน MySQL
        $verifyStmt = $pdo->prepare("SELECT id, title, category, created_at FROM news WHERE id = :id LIMIT 1");
        $verifyStmt->execute([':id' => $insertedId]);
        $verifiedRow = $verifyStmt->fetch();

        if ($verifiedRow && (int)$verifiedRow['id'] === (int)$insertedId) {
            $countAfter = (int)$pdo->query("SELECT COUNT(*) as c FROM news")->fetch()['c'];
            $stepLogs[] = ['step' => 5, 'name' => 'ตรวจเช็คความมีอยู่จริงในฐานข้อมูล (Verification)', 'status' => 'ok', 'detail' => "ดึงข้อมูลจากตาราง `news` พบแถว ID: {$insertedId} ตรงกันจริง! (ยอดรวมในตารางเพิ่มจาก {$countBefore} เป็น {$countAfter} แถว)"];
            $simulationResult = [
                'success' => true,
                'inserted_id' => $insertedId,
                'row' => $verifiedRow,
                'logs' => $stepLogs
            ];
        } else {
            $stepLogs[] = ['step' => 5, 'name' => 'ตรวจเช็คความมีอยู่จริงในฐานข้อมูล (Verification)', 'status' => 'error', 'detail' => 'คำสั่งผ่านแต่ไม่พบแถวในตาราง!'];
            $simulationResult = ['success' => false, 'error' => 'Verification failed', 'logs' => $stepLogs];
        }

    } catch (Exception $e) {
        $stepLogs[] = ['step' => 4, 'name' => 'ประมวลผลคำสั่ง SQL INSERT', 'status' => 'error', 'detail' => 'SQL Error: ' . $e->getMessage()];
        $simulationResult = ['success' => false, 'error' => $e->getMessage(), 'logs' => $stepLogs];
    }
}

if ($action === 'delete_item' && $pdo && !empty($_GET['id'])) {
    $delId = (int)$_GET['id'];
    $pdo->prepare("DELETE FROM news WHERE id = :id")->execute([':id' => $delId]);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?msg=deleted');
    exit;
}

// ดึง 10 ข่าวล่าสุดมาแสดง
$recentRows = [];
if ($pdo && $hasNewsTable) {
    try {
        $recentRows = $pdo->query("SELECT id, title, category, views_count, created_at FROM news ORDER BY id DESC LIMIT 10")->fetchAll();
    } catch (Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🕵️ เครื่องมือดักจับกระบวนการเพิ่มข่าว (News Addition Pipeline Inspector)</title>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Prompt', sans-serif; background: #090d16; color: #f1f5f9; margin: 0; padding: 25px; line-height: 1.6; }
        .container { max-width: 960px; margin: 0 auto; background: #131d2e; border-radius: 20px; padding: 32px; box-shadow: 0 20px 50px rgba(0,0,0,0.6); border: 1px solid #23354e; }
        h1 { color: #10b981; font-size: 1.65rem; margin-top: 0; display: flex; align-items: center; gap: 12px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .card { background: #0a111e; border-radius: 14px; padding: 20px; border: 1px solid #1e293b; }
        .card h3 { margin-top: 0; font-size: 1.1rem; color: #38bdf8; display: flex; align-items: center; gap: 8px; }
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .badge-success { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.4); }
        .timeline { list-style: none; padding: 0; margin: 20px 0; position: relative; }
        .timeline-item { display: flex; gap: 15px; margin-bottom: 18px; }
        .timeline-num { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0; }
        .timeline-ok .timeline-num { background: #065f46; color: #34d399; border: 2px solid #10b981; }
        .timeline-err .timeline-num { background: #7f1d1d; color: #fca5a5; border: 2px solid #ef4444; }
        .timeline-content { background: #0f172a; padding: 12px 18px; border-radius: 12px; border: 1px solid #1e293b; flex-grow: 1; }
        .timeline-title { font-weight: 600; margin-bottom: 4px; color: #e2e8f0; }
        .timeline-desc { font-size: 0.88rem; color: #94a3b8; word-break: break-all; }
        .btn { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 1rem; border: none; cursor: pointer; transition: all 0.2s; }
        .btn:hover { background: #10b981; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35); }
        .btn-danger { background: #dc2626; }
        .btn-danger:hover { background: #ef4444; }
        .btn-outline { background: transparent; border: 1px solid #475569; color: #cbd5e1; }
        .btn-outline:hover { background: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 0.88rem; }
        th, td { padding: 10px 14px; text-align: left; border-bottom: 1px solid #1e293b; }
        th { background: #0a111e; color: #94a3b8; }
        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 22px; display: flex; align-items: center; gap: 12px; }
        .alert-success { background: rgba(16, 185, 129, 0.12); border: 1px solid #059669; color: #a7f3d0; }
        .alert-danger { background: rgba(239, 68, 68, 0.12); border: 1px solid #dc2626; color: #fecaca; }
        input[type="text"], textarea, select { width: 100%; box-sizing: border-box; background: #0f172a; border: 1.5px solid #334155; color: #fff; padding: 10px 14px; border-radius: 8px; font-family: inherit; font-size: 0.95rem; margin-top: 6px; }
        input[type="text"]:focus, textarea:focus, select:focus { outline: none; border-color: #10b981; }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fa-solid fa-microscope text-success"></i> ตัวตรวจจับกระบวนการเพิ่มข่าว (News Addition Pipeline Inspector)</h1>
    <p style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 25px;">
        เครื่องมือจำลองและดักจับการบันทึกข่าวสารตั้งแต่ต้นจนจบ ตรวจเช็คสถานะการเขียนลงในตาราง MySQL จริงแบบสดๆ (Live Verification)
    </p>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> ลบรายการทดสอบเรียบร้อยแล้ว</div>
    <?php endif; ?>

    <!-- สถานะฐานข้อมูล -->
    <div class="grid-2">
        <div class="card">
            <h3><i class="fa-solid fa-database"></i> 1. สถานะ MySQL บน Host</h3>
            <?php if ($pdo): ?>
                <span class="badge badge-success"><i class="fa-solid fa-check"></i> เชื่อมต่อฐานข้อมูลสำเร็จ</span>
                <div style="margin-top: 10px; font-size: 0.88rem; color: #cbd5e1;">
                    <div>• ฐานข้อมูล: <strong style="color:#38bdf8;"><?= htmlspecialchars($dbname) ?></strong></div>
                    <div>• โฮสต์: <?= htmlspecialchars($host) ?> (User: <?= htmlspecialchars($user) ?>)</div>
                    <div>• ตาราง <code>news</code>: <?= $hasNewsTable ? '<span style="color:#34d399;">พบตารางเรียบร้อย</span>' : '<span style="color:#f87171;">ไม่พบตาราง</span>' ?></div>
                </div>
            <?php else: ?>
                <span class="badge badge-danger"><i class="fa-solid fa-triangle-exclamation"></i> เชื่อมต่อไม่สำเร็จ</span>
                <p style="color: #f87171; font-size: 0.85rem; margin-top: 8px;"><strong>Error:</strong> <?= htmlspecialchars($dbError) ?></p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3><i class="fa-solid fa-chart-pie"></i> 2. ข้อมูลข่าวในตารางปัจจุบัน</h3>
            <div style="font-size: 2.2rem; font-weight: 700; color: #10b981; margin: 4px 0;">
                <?= $countBefore ?> <span style="font-size: 1rem; color: #94a3b8; font-weight: normal;">รายการข่าว</span>
            </div>
            <p style="font-size: 0.82rem; color: #94a3b8; margin: 0;">
                ข้อมูลดึงสดจาก <code>SELECT COUNT(*) FROM news</code>
            </p>
        </div>
    </div>

    <!-- ผลการทดสอบ Pipeline -->
    <?php if ($simulationResult): ?>
        <div class="card" style="border: 2px solid <?= $simulationResult['success'] ? '#10b981' : '#ef4444' ?>; margin-bottom: 25px;">
            <h3>
                <i class="fa-solid <?= $simulationResult['success'] ? 'fa-circle-check text-success' : 'fa-circle-xmark text-danger' ?>"></i> 
                ผลการดักจับ Pipeline การเพิ่มข่าว (Inspector Trace)
            </h3>

            <div class="timeline">
                <?php foreach ($simulationResult['logs'] as $log): ?>
                    <div class="timeline-item <?= $log['status'] === 'ok' ? 'timeline-ok' : 'timeline-err' ?>">
                        <div class="timeline-num"><?= $log['step'] ?></div>
                        <div class="timeline-content">
                            <div class="timeline-title"><?= htmlspecialchars($log['name']) ?></div>
                            <div class="timeline-desc"><?= htmlspecialchars($log['detail']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($simulationResult['success']): ?>
                <div class="alert alert-success" style="margin-bottom: 0;">
                    <i class="fa-solid fa-award fs-3"></i>
                    <div>
                        <strong>สรุปผลการทดสอบ: ผ่าน 100% (Confirmed)</strong><br>
                        ข้อมูลข่าวถูกบันทึกลงตาราง <code>news</code> จริง และตรวจสอบพบในฐานข้อมูลเรียบร้อย (ID: <?= $simulationResult['inserted_id'] ?>)
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger" style="margin-bottom: 0;">
                    <i class="fa-solid fa-triangle-exclamation fs-3"></i>
                    <div>
                        <strong>สรุปผลการทดสอบ: ล้มเหลว!</strong><br>
                        <?= htmlspecialchars($simulationResult['error'] ?? 'ไม่สามารถ Insert ข้อมูลได้') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- ฟอร์มทดสอบยิงข่าวจำลอง -->
    <div class="card" style="margin-bottom: 25px;">
        <h3><i class="fa-solid fa-paper-plane"></i> 3. ทดสอบจำลองการเพิ่มข่าว (Simulate Add News)</h3>
        <p style="color: #94a3b8; font-size: 0.88rem;">
            กรอกข้อมูลหรือใช้ค่าเริ่มต้นด้านล่าง แล้วกดปุ่มทดสอบ เพื่อให้ระบบทำการยิงคำสั่งเพิ่มข่าวและดักจับผลลัพธ์ทีละสเต็ป
        </p>

        <form method="POST">
            <input type="hidden" name="action" value="simulate_add">
            <div style="margin-bottom: 14px;">
                <label style="font-size: 0.88rem; font-weight: 600; color: #cbd5e1;">หัวข้อข่าว (Title):</label>
                <input type="text" name="title" value="[ทดสอบระบบ] ข่าวสารจำลองทดสอบการบันทึกลง MySQL ณ <?= date('d/m/Y H:i:s') ?>" required>
            </div>
            <div style="margin-bottom: 14px;">
                <label style="font-size: 0.88rem; font-weight: 600; color: #cbd5e1;">หมวดหมู่ (Category):</label>
                <select name="category">
                    <option value="ข่าวประชาสัมพันธ์">ข่าวประชาสัมพันธ์</option>
                    <option value="ประกาศจัดซื้อจัดจ้าง (e-GP)">ประกาศจัดซื้อจัดจ้าง (e-GP)</option>
                    <option value="ข่าวกิจกรรมจังหวัด">ข่าวกิจกรรมจังหวัด</option>
                </select>
            </div>
            <div style="margin-bottom: 18px;">
                <label style="font-size: 0.88rem; font-weight: 600; color: #cbd5e1;">เนื้อหาข่าว (Content):</label>
                <textarea name="content" rows="3">&lt;p&gt;นี่คือเนื้อหาข่าวสำหรับทดสอบกระบวนการเขียนลง MySQL ตาราง news เพื่อตรวจเช็คความสมบูรณ์ของระบบ&lt;/p&gt;</textarea>
            </div>

            <button type="submit" class="btn" <?= !$pdo ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' ?>>
                <i class="fa-solid fa-bolt"></i> กดทดสอบเพิ่มข่าวและดักจับผลลัพธ์ทันที
            </button>
        </form>
    </div>

    <!-- รายการข่าวในตาราง news ล่าสุด -->
    <div class="card">
        <h3><i class="fa-solid fa-list-check"></i> 4. รายการข่าวในตาราง <code>news</code> ล่าสุด (<?= count($recentRows) ?> รายการ)</h3>
        <?php if (!empty($recentRows)): ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>หัวข้อข่าว</th>
                        <th>หมวดหมู่</th>
                        <th>วันที่สร้าง</th>
                        <th style="text-align: right;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentRows as $r): ?>
                        <tr>
                            <td><strong style="color: #38bdf8;">#<?= $r['id'] ?></strong></td>
                            <td><?= htmlspecialchars($r['title']) ?></td>
                            <td><span class="badge" style="background: rgba(16,185,129,0.15); color:#34d399;"><?= htmlspecialchars($r['category']) ?></span></td>
                            <td style="color: #94a3b8; font-size: 0.8rem;"><?= htmlspecialchars($r['created_at']) ?></td>
                            <td style="text-align: right;">
                                <?php if (strpos($r['title'], '[ทดสอบ') !== false): ?>
                                    <a href="?action=delete_item&id=<?= $r['id'] ?>" class="badge badge-danger" onclick="return confirm('ยืนยันลบข่าวทดสอบนี้?')" style="text-decoration:none;">
                                        <i class="fa-solid fa-trash"></i> ลบ
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: #94a3b8; text-align: center; padding: 20px;">ยังไม่มีข้อมูลในตาราง <code>news</code></p>
        <?php endif; ?>
    </div>

    <div style="text-align: center; margin-top: 25px;">
        <a href="./" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> กลับสู่หน้าหลักเว็บไซต์</a>
    </div>
</div>
</body>
</html>

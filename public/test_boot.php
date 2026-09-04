<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "<h1>Diagnostic Report for Web Phatthalung</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";

// 1. Test Writable directory permissions
echo "<h3>1. Writable Directory Check</h3>";
$writableDirs = [
    __DIR__ . '/../writable',
    __DIR__ . '/../writable/cache',
    __DIR__ . '/../writable/session',
    __DIR__ . '/../writable/logs'
];
foreach ($writableDirs as $dir) {
    if (is_dir($dir)) {
        $w = is_writable($dir) ? "<span style='color:green'>Writable (OK)</span>" : "<span style='color:red'>NOT Writable (Please chmod 777)</span>";
        echo "<p>{$dir}: {$w}</p>";
    } else {
        echo "<p style='color:red'>Directory not found: {$dir}</p>";
    }
}

// 2. Test DotEnv & CI Boot
echo "<h3>2. CodeIgniter 4 Boot Test</h3>";
try {
    require_once __DIR__ . '/../app/Config/Paths.php';
    $paths = new Config\Paths();
    require_once rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
    require_once SYSTEMPATH . 'Config/DotEnv.php';
    (new CodeIgniter\Config\DotEnv(ROOTPATH))->load();
    echo "<p style='color:green'>DotEnv loaded successfully!</p>";

    $app = Config\Services::codeigniter();
    $app->initialize();
    echo "<p style='color:green'>CodeIgniter initialized successfully!</p>";

    $db = Config\Database::connect();
    $db->connect();
    echo "<p style='color:green'>Database connected successfully! Connected DB: <b>" . htmlspecialchars($db->getDatabase()) . "</b></p>";
    
    $tables = $db->listTables();
    echo "<p style='color:green'>Tables count: " . count($tables) . "</p>";

    echo "<h3>4. View Render Test (home_portal)</h3>";
    try {
        helper(['settings', 'url']);
        $html = view('home_portal');
        echo "<p style='color:green'>view('home_portal') rendered successfully! Length: " . strlen($html) . "</p>";
    } catch (Throwable $e) {
        echo "<div style='background:#fee2e2;color:#991b1b;padding:15px;border-radius:8px;'>";
        echo "<h4>View Render Error:</h4>";
        echo "<pre>" . htmlspecialchars($e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine()) . "</pre>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</div>";
    }
} catch (Throwable $e) {
    echo "<div style='background:#fee2e2;color:#991b1b;padding:15px;border-radius:8px;'>";
    echo "<h4>Error Encountered:</h4>";
    echo "<pre>" . htmlspecialchars($e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}

// 3. Latest Error Log
echo "<h3>3. Latest CodeIgniter Error Log</h3>";
$logFiles = glob(__DIR__ . '/../writable/logs/log-*.log');
if (!empty($logFiles)) {
    rsort($logFiles);
    echo "<b>File: " . basename($logFiles[0]) . "</b>";
    echo "<pre style='background:#1e1e1e;color:#10b981;padding:15px;border-radius:8px;max-height:400px;overflow:auto;'>" . htmlspecialchars(file_get_contents($logFiles[0])) . "</pre>";
} else {
    echo "<p>No log files in writable/logs/</p>";
}

<?php
/**
 * Database Importer for webphatthalung
 * Imports db/webphatthalung.sql directly via CodeIgniter 4 / PDO
 */

define('FCPATH', __DIR__ . '/../public/');
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();

$app = Config\Services::codeigniter();
$app->initialize();
$db = Config\Database::connect();

$sqlFile = ROOTPATH . 'db/webphatthalung.sql';

echo "===============================================\n";
echo " Importing webphatthalung Database \n";
echo "===============================================\n\n";

if (!file_exists($sqlFile)) {
    echo "ERROR: SQL file not found at {$sqlFile}\n";
    exit(1);
}

echo "Target Database: " . $db->getDatabase() . "\n";
echo "Source File: " . $sqlFile . " (" . round(filesize($sqlFile)/1024, 2) . " KB)\n\n";

$sql = file_get_contents($sqlFile);

// Remove comments and split into individual queries
$queries = [];
$currentQuery = '';
$lines = explode("\n", $sql);

foreach ($lines as $line) {
    $trimmed = trim($line);
    if ($trimmed === '' || strpos($trimmed, '--') === 0 || strpos($trimmed, '/*') === 0) {
        continue;
    }
    
    $currentQuery .= $line . "\n";
    if (substr($trimmed, -1) === ';') {
        $queries[] = $currentQuery;
        $currentQuery = '';
    }
}

echo "Executing " . count($queries) . " SQL queries...\n";

$db->disableForeignKeyChecks();
$count = 0;
foreach ($queries as $q) {
    $trimmedQ = trim($q);
    if (!empty($trimmedQ)) {
        try {
            $db->query($trimmedQ);
            $count++;
        } catch (\Throwable $e) {
            echo "Warning on query: " . $e->getMessage() . "\n";
        }
    }
}
$db->enableForeignKeyChecks();

echo "\n=======================================================\n";
echo " Database import completed! ({$count} queries executed)\n";
echo "=======================================================\n";

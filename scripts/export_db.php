<?php
/**
 * Database and Data Exporter for webphatthalung
 * Exports database structure and data to db/webphatthalung.sql (UTF-8)
 * and updates writable/dump_*.json seed files.
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

echo "===============================================\n";
echo " Exporting webphatthalung Database & Seed Data \n";
echo "===============================================\n\n";

$dbName = $db->getDatabase();
$tables = $db->listTables();

// 1. Export JSON Seed files to writable/
echo "[1/2] Updating JSON Seed Datasets in writable/...\n";
$seedTables = ['pages', 'provincial_projects', 'emenscr_settings', 'executives', 'gallery_albums', 'gallery_photos', 'ita_documents', 'nora_knowledge', 'procurements', 'site_banners'];

foreach ($seedTables as $t) {
    if ($db->tableExists($t)) {
        $rows = $db->table($t)->get()->getResultArray();
        $filePath = WRITEPATH . "dump_{$t}.json";
        file_put_contents($filePath, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo "  - {$t}: " . count($rows) . " rows -> dump_{$t}.json\n";
    }
}

// 2. Generate SQL Dump file db/webphatthalung.sql (UTF-8)
echo "\n[2/2] Generating SQL Dump: db/webphatthalung.sql (UTF-8)...\n";
$sqlFile = ROOTPATH . 'db/webphatthalung.sql';
$sqlHandle = fopen($sqlFile, 'w');

if (!$sqlHandle) {
    echo "ERROR: Cannot write to {$sqlFile}\n";
    exit(1);
}

$header = "-- ========================================================\n"
        . "-- webphatthalung Database Backup\n"
        . "-- Database: {$dbName}\n"
        . "-- Generated on: " . date('Y-m-d H:i:s') . "\n"
        . "-- Character Set: UTF-8\n"
        . "-- ========================================================\n\n"
        . "SET NAMES utf8mb4;\n"
        . "SET FOREIGN_KEY_CHECKS = 0;\n\n";
fwrite($sqlHandle, $header);

foreach ($tables as $table) {
    echo "  - Exporting table: {$table}\n";
    
    // Drop table statement
    fwrite($sqlHandle, "\n-- --------------------------------------------------------\n");
    fwrite($sqlHandle, "-- Table structure for `{$table}`\n");
    fwrite($sqlHandle, "-- --------------------------------------------------------\n");
    fwrite($sqlHandle, "DROP TABLE IF EXISTS `{$table}`;\n");
    
    // Create table statement
    $createResult = $db->query("SHOW CREATE TABLE `{$table}`")->getRowArray();
    if (isset($createResult['Create Table'])) {
        fwrite($sqlHandle, $createResult['Create Table'] . ";\n\n");
    }
    
    // Dump data
    $rows = $db->table($table)->get()->getResultArray();
    if (!empty($rows)) {
        fwrite($sqlHandle, "-- Dumping data for `{$table}`\n");
        $columns = array_keys($rows[0]);
        $escapedCols = array_map(function($c) { return "`{$c}`"; }, $columns);
        $colList = implode(', ', $escapedCols);
        
        $batchSize = 50;
        $batchRows = [];
        foreach ($rows as $row) {
            $escapedVals = [];
            foreach ($row as $val) {
                if ($val === null) {
                    $escapedVals[] = 'NULL';
                } else {
                    $escapedVals[] = $db->escape($val);
                }
            }
            $batchRows[] = "(" . implode(', ', $escapedVals) . ")";
            
            if (count($batchRows) >= $batchSize) {
                fwrite($sqlHandle, "INSERT INTO `{$table}` ({$colList}) VALUES\n" . implode(",\n", $batchRows) . ";\n");
                $batchRows = [];
            }
        }
        
        if (!empty($batchRows)) {
            fwrite($sqlHandle, "INSERT INTO `{$table}` ({$colList}) VALUES\n" . implode(",\n", $batchRows) . ";\n");
        }
        fwrite($sqlHandle, "\n");
    }
}

fwrite($sqlHandle, "SET FOREIGN_KEY_CHECKS = 1;\n");
fclose($sqlHandle);

echo "\n=======================================================\n";
echo " Database export completed successfully! \n";
echo " File: db/webphatthalung.sql (" . round(filesize($sqlFile) / 1024, 2) . " KB)\n";
echo "=======================================================\n";

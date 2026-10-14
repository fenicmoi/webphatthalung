<?php
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

$fields = $db->getFieldNames('users');
echo "Current users fields: " . implode(', ', $fields) . "\n";

if (!in_array('status', $fields)) {
    $db->query("ALTER TABLE `users` ADD COLUMN `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'approved' AFTER `role`");
    echo "✅ Added column: status\n";
} else {
    echo "ℹ️ Column status already exists\n";
}

if (!in_array('department', $fields)) {
    $db->query("ALTER TABLE `users` ADD COLUMN `department` VARCHAR(255) NULL AFTER `full_name`");
    echo "✅ Added column: department\n";
} else {
    echo "ℹ️ Column department already exists\n";
}

if (!in_array('phone', $fields)) {
    $db->query("ALTER TABLE `users` ADD COLUMN `phone` VARCHAR(50) NULL AFTER `department`");
    echo "✅ Added column: phone\n";
} else {
    echo "ℹ️ Column phone already exists\n";
}

$updatedFields = $db->getFieldNames('users');
echo "Updated users fields: " . implode(', ', $updatedFields) . "\n";

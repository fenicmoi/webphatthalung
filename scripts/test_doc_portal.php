<?php
// Test controller Document
require __DIR__ . '/../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$routes = Config\Services::routes();
require APPPATH . 'Config/Routes.php';

echo "Testing get_document_categories:\n";
helper('settings');
$cats = get_document_categories();
foreach ($cats as $k => $c) {
    echo "- [$k] {$c['name']}\n";
}

echo "\nTesting get_site_documents for 'การบริหารความเสี่ยง':\n";
$docs = get_site_documents(null, 'การบริหารความเสี่ยง');
echo "Found " . count($docs) . " items in 'การบริหารความเสี่ยง':\n";
foreach ($docs as $d) {
    echo "  * [{$d['date']}] {$d['title']} ({$d['file_size']})\n    URL: {$d['file_url']}\n";
}

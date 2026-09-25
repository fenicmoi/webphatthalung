<?php
$pdo = new PDO('mysql:host=localhost;dbname=phatthalun_2026db;charset=utf8', 'root', '');
$docs = $pdo->query('SELECT * FROM ita_documents')->fetchAll(PDO::FETCH_ASSOC);
file_put_contents(__DIR__ . '/../writable/dump_ita_documents.json', json_encode($docs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Dumped " . count($docs) . " ita_documents to dump_ita_documents.json\n";

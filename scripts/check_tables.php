<?php
$pdo = new PDO('mysql:host=localhost;dbname=phatthalun_2026db;charset=utf8', 'root', '');
$docs = $pdo->query('SELECT * FROM ita_documents')->fetchAll(PDO::FETCH_ASSOC);
echo "Total ita_documents: " . count($docs) . "\n";
print_r(array_slice($docs, 0, 10));

<?php
$urls = [
    'https://www.phatthalung.go.th/2022/files/com_news_form/2026-06_b7b3d17094a3990.pdf',
    'https://www.phatthalung.go.th/2022/files/com_news_form/2026-05_03b01c655873c2e.pdf',
    'https://www.phatthalung.go.th/2022/files/com_news_form/2026-06_ed4ce568cf75fb4.pdf',
    'https://www.phatthalung.go.th/2022/files/com_news_form/2025-04_b0b2e5a96d91b1f.pdf',
    'https://www.phatthalung.go.th/2022/files/com_news_form/2025-04_32c7c3547aa86c0.pdf',
    'https://www.phatthalung.go.th/2022/files/com_news_form/2025-08_68cca5427e87762.pdf',
    'https://www.phatthalung.go.th/2022/files/com_news_form/2024-04_d0a110d958034a6.pdf',
    'https://www.phatthalung.go.th/2022/files/com_news_form/2024-04_01e9e3328a0777a.pdf'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    echo "$code ($type) - " . basename($url) . "\n";
}

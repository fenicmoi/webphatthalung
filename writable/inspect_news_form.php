<?php
$html = file_get_contents('https://www.phatthalung.go.th/2022/news_form/showList?cid=7');

if (preg_match('/จำนวน.*?(\d[0-9,]*)\s*รายการ/u', $html, $m)) {
    echo "Total items: " . $m[0] . "\n";
}

// Find items in download or table
preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $items);
echo "List items count: " . count($items[1]) . "\n";
foreach ($items[1] as $idx => $it) {
    if (strpos($it, 'download-title') === false && strpos($it, 'title') === false) continue;
    preg_match('/<strong>(.*?)<\/strong>/is', $it, $tm);
    if (empty($tm)) {
        preg_match('/<a[^>]*>(.*?)<\/a>/is', $it, $tm);
    }
    $title = isset($tm[1]) ? trim(strip_tags($tm[1])) : 'No title';
    preg_match_all('/href=["\']([^"\']*)["\']/is', $it, $hm);
    $pdf = '';
    foreach ($hm[1] as $h) {
        if (strpos($h, '.pdf') !== false || strpos($h, 'files/') !== false) {
            $pdf = $h;
            break;
        }
    }
    preg_match('/([0-9.]+\s*[KM]B)/i', $it, $sm);
    $size = $sm[1] ?? '';
    echo "#" . ($idx + 1) . ": $title | $pdf | $size\n";
}

// Also check what other cid exist in news_form
preg_match_all('/<a[^>]*href=["\']([^"\']*news_form[^"\']*)["\'][^>]*>(.*?)<\/a>/is', $html, $navs);
echo "\nOther news_form categories found:\n";
foreach ($navs[1] as $nIdx => $nUrl) {
    echo "  - " . trim(strip_tags($navs[2][$nIdx])) . " => " . $nUrl . "\n";
}

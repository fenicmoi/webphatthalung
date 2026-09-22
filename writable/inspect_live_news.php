<?php
$html = file_get_contents('https://www.phatthalung.go.th/2022/news?cid=3');
if (preg_match('/จำนวนข่าวประกาศ.*?(\d[0-9,]*)\s*รายการ/u', $html, $m)) {
    echo "Total on live hosting: " . $m[1] . " items\n";
}

// Find items in HTML
// Let's find news items
preg_match_all('/<a[^>]*href=["\']([^"\']*news_view[^"\']*id=(\d+)[^"\']*)["\'][^>]*>(.*?)<\/a>/is', $html, $matches, PREG_SET_ORDER);
echo "Found " . count($matches) . " news links on page 1\n";
foreach (array_slice($matches, 0, 10) as $item) {
    $title = strip_tags(trim($item[3]));
    echo "#" . $item[2] . " => " . $title . " (" . $item[1] . ")\n";
}

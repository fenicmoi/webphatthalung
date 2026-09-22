<?php
$html = file_get_contents('https://www.phatthalung.go.th/2022/news/detail/11905/data.html');
preg_match_all('/<a[^>]*href=["\']([^"\']*)["\'][^>]*>(.*?)<\/a>/is', $html, $m);
foreach ($m[0] as $idx => $link) {
    if (stripos($link, 'download') !== false || stripos($link, 'files/') !== false || stripos($link, '.pdf') !== false || stripos($link, 'file') !== false) {
        echo "Link: " . $m[1][$idx] . " | Text: " . strip_tags($m[2][$idx]) . "\n";
    }
}

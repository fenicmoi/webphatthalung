<?php
$html = file_get_contents('https://www.phatthalung.go.th/2022/news/detail/11905/data.html');
preg_match_all('/<a[^>]*href=["\']([^"\']*\.pdf[^"\']*)["\'][^>]*>(.*?)<\/a>/i', $html, $m);
echo "PDF links in detail 11905:\n";
print_r($m[1]);

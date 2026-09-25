<?php
$ch = curl_init('https://www.phatthalung.go.th/2026/news_form/showList?cid=7');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$res = curl_exec($ch);
$url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "Effective URL: $url (HTTP $code)\n";
echo "Found 'การบริหารความเสี่ยง': " . (strpos($res, 'การบริหารความเสี่ยง') !== false ? 'YES' : 'NO') . "\n";
echo "Found 'แผนการบริหารความเสี่ยงการทุจริต': " . (strpos($res, 'แผนการบริหารความเสี่ยงการทุจริต') !== false ? 'YES' : 'NO') . "\n";

<?php
$html = file_get_contents('https://www.phatthalung.go.th/2022/news?cid=3');
$pos = mb_strpos($html, 'จำนวนข่าวประกาศ');
if ($pos !== false) {
    echo mb_substr($html, $pos, 2500);
} else {
    echo "Not found";
}

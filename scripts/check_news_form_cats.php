<?php
$html = file_get_contents('C:/Users/Lenovo/.gemini/antigravity-ide/brain/b9361caf-f59a-4a5c-ac49-a0924374d55e/.system_generated/steps/809/content.md');

preg_match_all('/<a[^>]+href=[\'"](news_form\/showList\?cid=\d+)[\'"][^>]*>(.*?)<\/a>/i', $html, $m);
foreach ($m[1] as $idx => $link) {
    echo $link . " => " . trim(strip_tags($m[2][$idx])) . "\n";
}

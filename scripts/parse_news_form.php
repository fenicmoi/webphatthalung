<?php
$html = file_get_contents('C:/Users/Lenovo/.gemini/antigravity-ide/brain/b9361caf-f59a-4a5c-ac49-a0924374d55e/.system_generated/steps/809/content.md');

preg_match('/<div class="download-block">(.*?)<\/div>\s*<\/div>\s*<\/div>/is', $html, $m);
echo $m[0] . "\n";

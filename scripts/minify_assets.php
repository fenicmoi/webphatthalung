<?php
/**
 * Asset Minifier & Optimizer Utility
 * Run via: php scripts/minify_assets.php
 */

function minifyCSS($css) {
    // 1. Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    // 2. Remove space around delimiters
    $css = preg_replace('/\s*([\{\}\;\:\,\>\+\~])\s*/', '$1', $css);
    // 3. Remove unnecessary spaces
    $css = preg_replace('/\s+/', ' ', $css);
    // 4. Remove ; before }
    $css = str_replace(';}', '}', $css);
    return trim($css);
}

function minifyJS($js) {
    // 1. Remove multi-line comments
    $js = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $js);
    // 2. Remove single-line comments (preserving URLs)
    $lines = explode("\n", $js);
    $cleanLines = [];
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if (strpos($trimmed, '//') === 0) {
            continue;
        }
        if (preg_match('#^(.*?)(?<!:)\/\/(.*)$#', $line, $matches)) {
            $before = $matches[1];
            $quoteCount = substr_count($before, "'") + substr_count($before, '"') + substr_count($before, '`');
            if ($quoteCount % 2 === 0) {
                $line = $before;
            }
        }
        $cleanLines[] = rtrim($line);
    }
    $js = implode("\n", $cleanLines);
    $js = preg_replace('/(\r?\n){2,}/', "\n", $js);
    return trim($js);
}

$baseDir = dirname(__DIR__);
$cssFile = $baseDir . '/public/assets/css/main.css';
$minCssFile = $baseDir . '/public/assets/css/main.min.css';
if (file_exists($cssFile)) {
    $css = file_get_contents($cssFile);
    $minCss = minifyCSS($css);
    file_put_contents($minCssFile, $minCss);
    echo "CSS: " . round(filesize($cssFile)/1024, 1) . " KB -> " . round(filesize($minCssFile)/1024, 1) . " KB (-" . round((1 - filesize($minCssFile)/filesize($cssFile))*100, 1) . "%)\n";
}

$jsFile = $baseDir . '/public/assets/js/app.js';
$minJsFile = $baseDir . '/public/assets/js/app.min.js';
if (file_exists($jsFile)) {
    $js = file_get_contents($jsFile);
    $minJs = minifyJS($js);
    file_put_contents($minJsFile, $minJs);
    echo "JS:  " . round(filesize($jsFile)/1024, 1) . " KB -> " . round(filesize($minJsFile)/1024, 1) . " KB (-" . round((1 - filesize($minJsFile)/filesize($jsFile))*100, 1) . "%)\n";
}

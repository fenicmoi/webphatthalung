<?php
// Render exact mathematical seamless PNG tile of Nora beadwork pattern
$size = 120;
$img = imagecreatetruecolor($size, $size);

// Base Sage Green Background
$bgColor = imagecolorallocate($img, 163, 194, 153); // #a3c299
$darkLine = imagecolorallocate($img, 139, 169, 129); // #8ba981
$lightLine = imagecolorallocate($img, 211, 232, 202); // #d3e8ca
$brightLine = imagecolorallocate($img, 234, 245, 228); // #eaf5e4

imagefilledrectangle($img, 0, 0, $size, $size, $bgColor);
imagesetthickness($img, 2);

function drawDiamond($img, $cx, $cy, $radius, $color) {
    $points = [
        $cx, $cy - $radius,
        $cx + $radius, $cy,
        $cx, $cy + $radius,
        $cx - $radius, $cy
    ];
    // PHP 8+ takes 3 args, PHP 7 takes 4 args
    if (PHP_VERSION_ID >= 80000) {
        imagepolygon($img, $points, $color);
    } else {
        imagepolygon($img, $points, 4, $color);
    }
}

// Draw Concentric Diamonds at Center (60, 60)
$radii = [50, 42, 34, 26, 18];
$colors = [$darkLine, $lightLine, $darkLine, $lightLine, $brightLine];

for ($i = 0; $i < count($radii); $i++) {
    drawDiamond($img, 60, 60, $radii[$i], $colors[$i]);
}

// Center Cross
imagesetthickness($img, 3);
imageline($img, 48, 60, 72, 60, $brightLine);
imageline($img, 60, 48, 60, 72, $brightLine);

// 4 Corners: (0,0), (120,0), (0,120), (120,120)
imagesetthickness($img, 2);
$corners = [[0, 0], [$size, 0], [0, $size], [$size, $size]];
foreach ($corners as $c) {
    for ($i = 0; $i < count($radii); $i++) {
        drawDiamond($img, $c[0], $c[1], $radii[$i], $colors[$i]);
    }
    // Corner cross
    imagesetthickness($img, 3);
    imageline($img, $c[0] - 12, $c[1], $c[0] + 12, $c[1], $brightLine);
    imageline($img, $c[0], $c[1] - 12, $c[0], $c[1] + 12, $brightLine);
    imagesetthickness($img, 2);
}

imagepng($img, 'c:/wamp64/www/webphatthalung/public/assets/images/banners/phatthalung_woven_pattern.png', 9);
imagedestroy($img);
echo "Mathematical seamless PNG tile created: 120x120\n";

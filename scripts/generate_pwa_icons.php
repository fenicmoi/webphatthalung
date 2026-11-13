<?php
/**
 * PWA Icon Generator for Phatthalung Digital Portal
 * สร้างไอคอน PWA คุณภาพสูงขนาด 192x192 และ 512x512 สำหรับ Android
 */

$logoFile = dirname(__DIR__) . '/public/uploads/logo/logo_1787048018.png';
if (!file_exists($logoFile)) {
    $logoFile = dirname(__DIR__) . '/public/favicon.png';
}

$destDir = dirname(__DIR__) . '/public/assets/images/pwa';
if (!is_dir($destDir)) {
    mkdir($destDir, 0777, true);
}

$srcImg = imagecreatefrompng($logoFile);
imagealphablending($srcImg, true);
imagesavealpha($srcImg, true);

$srcW = imagesx($srcImg);
$srcH = imagesy($srcImg);

function createPwaIcon($srcImg, $srcW, $srcH, $targetSize, $destPath, $isMaskable = false) {
    $canvas = imagecreatetruecolor($targetSize, $targetSize);
    imagealphablending($canvas, false);
    imagesavealpha($canvas, true);

    // Deep Emerald Green Background for Android Theme
    $bg = imagecolorallocate($canvas, 4, 120, 87); // #047857
    imagefilledrectangle($canvas, 0, 0, $targetSize, $targetSize, $bg);

    // Subtle radial light gradient or soft circle
    imagealphablending($canvas, true);
    $innerGlow = imagecolorallocatealpha($canvas, 16, 185, 129, 90); // Emerald 500 with alpha
    imagefilledellipse($canvas, $targetSize / 2, $targetSize / 2, $targetSize * 0.9, $targetSize * 0.9, $innerGlow);

    // White circle backdrop for the emblem
    $whiteCircle = imagecolorallocate($canvas, 255, 255, 255);
    $circleSize = $targetSize * ($isMaskable ? 0.68 : 0.78);
    imagefilledellipse($canvas, $targetSize / 2, $targetSize / 2, $circleSize, $circleSize, $whiteCircle);

    // Scale and place logo inside
    $emblemSize = $circleSize * 0.88;
    $dstX = ($targetSize - $emblemSize) / 2;
    $dstY = ($targetSize - $emblemSize) / 2;

    imagecopyresampled(
        $canvas, $srcImg,
        (int)$dstX, (int)$dstY,
        0, 0,
        (int)$emblemSize, (int)$emblemSize,
        $srcW, $srcH
    );

    imagepng($canvas, $destPath, 9);
    imagedestroy($canvas);
    echo "Generated: " . basename($destPath) . " ({$targetSize}x{$targetSize})\n";
}

createPwaIcon($srcImg, $srcW, $srcH, 192, $destDir . '/icon-192.png', false);
createPwaIcon($srcImg, $srcW, $srcH, 512, $destDir . '/icon-512.png', false);
createPwaIcon($srcImg, $srcW, $srcH, 512, $destDir . '/icon-maskable.png', true);

imagedestroy($srcImg);
echo "All PWA Icons Generated Successfully!\n";

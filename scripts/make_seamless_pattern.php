<?php
$srcPath = 'C:/Users/Lenovo/.gemini/antigravity-ide/brain/9a47a13a-ba32-4b96-89dd-731e4945299b/.user_uploaded/media_1788330823204.png';
$destPath = 'c:/wamp64/www/webphatthalung/public/assets/images/banners/phatthalung_woven_pattern.png';

$src = imagecreatefrompng($srcPath);
if (!$src) {
    die("Failed to load source image\n");
}

$w = imagesx($src);
$h = imagesy($src);
echo "Original size: {$w}x{$h}\n";

// Create 4-way mirrored canvas (2w x 2h)
$tile = imagecreatetruecolor($w * 2, $h * 2);
imagealphablending($tile, false);
imagesavealpha($tile, true);

// 1. Top-Left: Original
imagecopy($tile, $src, 0, 0, 0, 0, $w, $h);

// 2. Top-Right: Horizontal Flip
for ($x = 0; $x < $w; $x++) {
    for ($y = 0; $y < $h; $y++) {
        $color = imagecolorat($src, $x, $y);
        imagesetpixel($tile, ($w * 2 - 1) - $x, $y, $color);
    }
}

// 3. Bottom-Left: Vertical Flip
for ($x = 0; $x < $w; $x++) {
    for ($y = 0; $y < $h; $y++) {
        $color = imagecolorat($src, $x, $y);
        imagesetpixel($tile, $x, ($h * 2 - 1) - $y, $color);
    }
}

// 4. Bottom-Right: Both Flips (Horizontal & Vertical)
for ($x = 0; $x < $w; $x++) {
    for ($y = 0; $y < $h; $y++) {
        $color = imagecolorat($src, $x, $y);
        imagesetpixel($tile, ($w * 2 - 1) - $x, ($h * 2 - 1) - $y, $color);
    }
}

// Save to destination
imagepng($tile, $destPath, 9);
imagedestroy($src);
imagedestroy($tile);

echo "4-way Mirrored Seamless tile generated successfully: " . ($w * 2) . "x" . ($h * 2) . "\n";

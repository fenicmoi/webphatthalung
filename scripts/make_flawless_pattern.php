<?php
// Generate a 100% flawless seamless repeating texture from the original pattern
$srcPath = 'C:/Users/Lenovo/.gemini/antigravity-ide/brain/9a47a13a-ba32-4b96-89dd-731e4945299b/.user_uploaded/media_1788330823204.png';
$destPath = 'c:/wamp64/www/webphatthalung/public/assets/images/banners/phatthalung_woven_pattern.png';

$src = imagecreatefrompng($srcPath);
$sw = imagesx($src);
$sh = imagesy($src);

// To guarantee zero visible seam lines:
// 1. Create a 4-way mirrored block (2*sw x 2*sh)
$mw = $sw * 2;
$mh = $sh * 2;
$mirrored = imagecreatetruecolor($mw, $mh);
imagealphablending($mirrored, false);
imagesavealpha($mirrored, true);

// Top-Left
imagecopy($mirrored, $src, 0, 0, 0, 0, $sw, $sh);

// Top-Right (H-Flip)
for ($x = 0; $x < $sw; $x++) {
    for ($y = 0; $y < $sh; $y++) {
        imagesetpixel($mirrored, $mw - 1 - $x, $y, imagecolorat($src, $x, $y));
    }
}

// Bottom-Left (V-Flip)
for ($x = 0; $x < $sw; $x++) {
    for ($y = 0; $y < $sh; $y++) {
        imagesetpixel($mirrored, $x, $mh - 1 - $y, imagecolorat($src, $x, $y));
    }
}

// Bottom-Right (HV-Flip)
for ($x = 0; $x < $sw; $x++) {
    for ($y = 0; $y < $sh; $y++) {
        imagesetpixel($mirrored, $mw - 1 - $x, $mh - 1 - $y, imagecolorat($src, $x, $y));
    }
}

// 2. Tile it into a larger 3x3 block (432 x 420) so the repeating frequency in CSS is smooth and natural
$targetW = $mw * 2; // 288
$targetH = $mh * 2; // 280
$finalTile = imagecreatetruecolor($targetW, $targetH);
imagealphablending($finalTile, false);
imagesavealpha($finalTile, true);

for ($i = 0; $i < 2; $i++) {
    for ($j = 0; $j < 2; $j++) {
        imagecopy($finalTile, $mirrored, $i * $mw, $j * $mh, 0, 0, $mw, $mh);
    }
}

// Apply gentle gaussian blur or edge softening if needed, or save directly
imagepng($finalTile, $destPath, 9);
imagedestroy($src);
imagedestroy($mirrored);
imagedestroy($finalTile);

echo "Flawless Seamless Tile generated: {$targetW}x{$targetH}\n";

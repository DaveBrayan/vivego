<?php

$sourcePath = __DIR__ . '/../public/images/libro_de_reclamaciones.jpeg';
$targetPath = __DIR__ . '/../public/images/libro_de_reclamaciones.png';

$img = imagecreatefromjpeg($sourcePath);
$width = imagesx($img);
$height = imagesy($img);

$transparentImg = imagecreatetruecolor($width, $height);
imagesavealpha($transparentImg, true);
$transColour = imagecolorallocatealpha($transparentImg, 0, 0, 0, 127);
imagefill($transparentImg, 0, 0, $transColour);

for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        $avg = ($r + $g + $b) / 3;
        
        if ($r >= 248 && $g >= 248 && $b >= 248) {
            $alpha = 127;
        } elseif ($avg >= 238) {
            $alpha = min(127, max(0, (int)round(($avg - 238) / (248 - 238) * 127)));
        } else {
            $alpha = 0;
        }
        
        $color = imagecolorallocatealpha($transparentImg, $r, $g, $b, $alpha);
        imagesetpixel($transparentImg, $x, $y, $color);
    }
}

imagepng($transparentImg, $targetPath, 9);
imagedestroy($img);
imagedestroy($transparentImg);

echo "Updated transparent PNG successfully.\n";

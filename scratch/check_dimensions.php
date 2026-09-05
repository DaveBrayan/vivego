<?php
$file = "public/storage/media/media_1787210295_8sAMvBrx.png";
$im = imagecreatefrompng($file);
$size = getimagesize($file);
for ($y = 0; $y < 5; $y++) {
    $c = imagecolorat($im, 50, $y);
    $rgba = imagecolorsforindex($im, $c);
    echo "x=50, y=$y: R={$rgba['red']} G={$rgba['green']} B={$rgba['blue']} Alpha={$rgba['alpha']}\n";
}

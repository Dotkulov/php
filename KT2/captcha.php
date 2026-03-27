<?php
session_start();

$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

if (isset($_GET['refresh'])) {
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    $_SESSION['captcha_code'] = $code;
} elseif (!isset($_SESSION['captcha_code'])) {
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    $_SESSION['captcha_code'] = $code;
}

$code = $_SESSION['captcha_code'];

$imgPath = 'noise.jpg';
if (!file_exists($imgPath)) {
    $img = imagecreatetruecolor(300, 100);
    $bg = imagecolorallocate($img, 240, 240, 240);
    imagefill($img, 0, 0, $bg);
} else {
    $img = imagecreatefromjpeg($imgPath);
    $img = imagescale($img, 350, 100);
}

$fontFile = 'C:/Windows/Fonts/arial.ttf';
if (!file_exists($fontFile)) {
    $fontFile = __DIR__ . '/arial.ttf';
}

$fontSize = rand(18, 30);
$startX = 100;
$y = 65;
$spacing = 40;
$redIndex = random_int(0, strlen($code) - 1);

for ($i = 0; $i < strlen($code); $i++) {
    $char = $code[$i];
    $x = $startX + $i * $spacing;
    
    $yOffset = rand(-20, 20);
    
    if ($i == $redIndex) {
        $textColor = imagecolorallocate($img, 220, 40, 40);
    } else {
        $colorR = rand(20, 200);
        $colorG = rand(20, 200);
        $colorB = rand(20, 200);

        while (($colorR + $colorG + $colorB) < 100 || ($colorR + $colorG + $colorB) > 600) {
            $colorR = rand(20, 200);
            $colorG = rand(20, 200);
            $colorB = rand(20, 200);
        }
        
        $textColor = imagecolorallocate($img, $colorR, $colorG, $colorB);
    }
    
    if (function_exists('imagettftext') && file_exists($fontFile)) {
        $angle = rand(-15, 15);
        imagettftext($img, $fontSize, $angle, $x, $y + $yOffset, $textColor, $fontFile, $char);
    } else {
        imagestring($img, 5, $x, $y + $yOffset - 10, $char, $textColor);
    }
}

for ($i = 0; $i < 10; $i++) {
    $lineColor = imagecolorallocate($img, rand(100, 200), rand(100, 200), rand(100, 200));
    $x1 = rand(0, imagesx($img));
    $y1 = rand(0, imagesy($img));
    $x2 = rand(0, imagesx($img));
    $y2 = rand(0, imagesy($img));
    imageline($img, $x1, $y1, $x2, $y2, $lineColor);
}

$noiseCount = rand(200, 400);
for ($i = 0; $i < $noiseCount; $i++) {
    $noiseColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
    $x = rand(0, imagesx($img));
    $y = rand(0, imagesy($img));
    imagesetpixel($img, $x, $y, $noiseColor);
}

header('Content-Type: image/jpeg');
imagejpeg($img, null, 85);
imagedestroy($img);
?>
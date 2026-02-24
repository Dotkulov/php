<?php
session_start();

function generateRandomString($length = 5) {
    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$captcha_code = generateRandomString(5);
$_SESSION['captcha'] = $captcha_code;

$width = 500;
$height = 200;

if (file_exists('noise.jpg')) {
    $image = imagecreatefromjpeg('noise.jpg');
    $image = imagescale($image, $width, $height);
} else {
    $image = imagecreatetruecolor($width, $height);
    $bg_color = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $bg_color);

    for ($i = 0; $i < 1000; $i++) {
        $noise_color = imagecolorallocate($image, rand(200, 255), rand(200, 255), rand(200, 255));
        imagesetpixel($image, rand(0, $width-1), rand(0, $height-1), $noise_color);
    }
}

$font_paths = [
    '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
    '/usr/share/fonts/truetype/arial.ttf',
    'C:\\Windows\\Fonts\\Arial.ttf',
    '/System/Library/Fonts/Helvetica.ttc',
    __DIR__ . '/fonts/arial.ttf', 
];

$font_found = false;
foreach ($font_paths as $path) {
    if (file_exists($path)) {
        $font_file = $path;
        $font_found = true;
        break;
    }
}

if ($font_found) {
    $colors = [
        imagecolorallocate($image, 0, 0, 0),
        imagecolorallocate($image, 0, 0, 200),
        imagecolorallocate($image, 200, 0, 0),
        imagecolorallocate($image, 0, 150, 0),
    ];

    $x = 50;
    for ($i = 0; $i < strlen($captcha_code); $i++) {
        $color = $colors[rand(0, count($colors)-1)];
        $font_size = rand(30, 40);
        $angle = rand(-10, 10);
        $y = rand(130, 160);

        imagettftext($image, $font_size, $angle, $x + ($i * 70), $y, $color, $font_file, $captcha_code[$i]);
    }
} else {
    $colors = [
        imagecolorallocate($image, 0, 0, 0),
        imagecolorallocate($image, 0, 0, 200),
        imagecolorallocate($image, 200, 0, 0),
    ];

    $x = 60;
    for ($i = 0; $i < strlen($captcha_code); $i++) {
        $char = $captcha_code[$i];
        $color = $colors[rand(0, count($colors)-1)];
        $y = rand(100, 140);
        $font = 5;

        for ($offset_x = -1; $offset_x <= 1; $offset_x++) {
            for ($offset_y = -1; $offset_y <= 1; $offset_y++) {
                imagestring($image, $font, $x + ($i * 70) + $offset_x, $y + $offset_y, $char, $color);
            }
        }
    }
}

for ($i = 0; $i < 5; $i++) {
    $line_color = imagecolorallocate($image, rand(100, 150), rand(100, 150), rand(100, 150));
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
}

header('Content-Type: image/png');
header('Cache-Control: no-cache, must-revalidate');
imagepng($image);
imagedestroy($image);
?>

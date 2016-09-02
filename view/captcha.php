<?php
session_start();
Header("Content-type: image/jpeg");

if ($_GET["item"] == null) {
	die();
} else {
	$code = $_SESSION[$_GET["item"]."_captcha"];
}

$width = 200;
$height = 40;
$x = 10;
$y = 30;
$font_size = 16;
$angle = 4;

$dst_img = ImageCreate($width, $height);
$bgc = ImageColorAllocate ($dst_img, 150, 150, 150);
$color = ImageColorAllocate ($dst_img, 68, 91, 54);
ImageFilledRectangle ($dst_img, 0, 0, $width, $height, $bgc); 
$string = "";
for ($i = 0; $i < strlen($code); $i++) {
	$string .= $code[$i];
	$string .= " ";
}
ImageTTFText($dst_img, $font_size, $angle, $x, $y, $color, "css/castelar.ttf", strtolower($string));
ImageJPEG($dst_img, null, 100);
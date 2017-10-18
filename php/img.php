<?php
$arr=[60,60,'999999','f2f2f2','image'];
$get=[];
foreach ($_GET as $key => $value) {
    $get[]=!$value?$key:$value;
}
if (isset($get[0])) {
    $arr[4]=isset($get[1])?$get[1]:'image';
    preg_match('/^(\d+x\d+)*(\/)*([0-9a-fA-F]+)*(\/)*([0-9a-fA-F]+)*$/', $get[0], $match);
    if (isset($match[1])) {
        list($arr[0],$arr[1])=explode('x', $match[1]);
    }
    if (isset($match[3])) {
        $arr[2]=$match[3];
    }
    if (isset($match[5])) {
        $arr[3]=$match[5];
    }
}
$arr[2]=str_split($arr[2], 2);
$arr[3]=str_split($arr[3], 2);
$x=($arr[0]-9*strlen($arr[4]))/2;
$y=($arr[1]-16)/2;
$im = imagecreate($arr[0], $arr[1]);
$back_color = imagecolorallocate($im, hexdec($arr[3][0]), hexdec($arr[3][1]), hexdec($arr[3][2]));
$text_color = imagecolorallocate($im, hexdec($arr[2][0]), hexdec($arr[2][1]), hexdec($arr[2][2]));
imagestring($im, 5, $x, $y, $arr[4], $text_color);
header("Content-Type: image/png");
imagepng($im);
imagedestroy($im);

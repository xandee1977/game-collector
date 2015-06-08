<?php
header('Content-Type: image/png');

$filename = $_REQUEST["filename"];
$filename = sprintf("images/games/1/%s", $filename);

if(file_exists($filename)) {
    // Create a blank image and add some text
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if($ext == "png") {
        $im = imagecreatefrompng($filename);
    } else {
        $im = imagecreatefromjpeg($filename);
    }
} else {
    $filename = 'images/no-picture.png';
    $im = imagecreatefrompng($filename);	
}

imagepng($im);
imagedestroy($im);
?>
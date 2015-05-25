<?php
header('Content-Type: image/png');

$game_id = 0;
if(isset($_REQUEST["game_id"])) {
	$game_id = (int) $_REQUEST["game_id"];
}
if(file_exists(sprintf("images/%s.jpg", $game_id))) {
	// Create a blank image and add some text
	$ini_filename = sprintf("images/%s.jpg", $game_id);
	$im = imagecreatefromjpeg($ini_filename);
} else {
	// Create a blank image and add some text
	$ini_filename = 'images/no-picture.png';
	$im = imagecreatefrompng($ini_filename );	
}



$ini_x_size = getimagesize($ini_filename )[0];
$ini_y_size = getimagesize($ini_filename )[1];

//the minimum of xlength and ylength to crop.
$crop_measure = min($ini_x_size, $ini_y_size);

// Set the content type header - in this case image/jpeg
//header('Content-Type: image/jpeg');

/*
$to_crop_array = array('x' =>0 , 'y' => 0, 'width' => $crop_measure	, 'height'=> $crop_measure);
$thumb_im = imagecrop($im, $to_crop_array);
*/

imagepng($im);
imagedestroy($im);
?>
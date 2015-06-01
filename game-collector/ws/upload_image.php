<?php
ini_set('max_execution_time', 0);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);


	/*
	$data = $_POST['image'];	
	$data = base64_decode($data);
	$file = 'pictures/'. uniqid() . '.png';
	$success = file_put_contents($file, $data);
	*/
echo 1;

	// requires php5
    $json_data = $HTTP_RAW_POST_DATA;
    $array_data = json_decode($json_data, true);	


    var_dump($array_data);

    return;
	define('UPLOAD_DIR', 'pictures/');
	$img = $array_data["image"];
	$img = str_replace('data:image/jpeg;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	if($data === FALSE) {
		echo "Erro ao decodar";
		echo $img;
	}
	$file = UPLOAD_DIR . uniqid() . '.jpg';
	$success = file_put_contents($file, $data);
	if($success === FALSE) {
		echo "Erro ao upar";
	}

	/*
	$data = base64_decode($data); // base64 decoded image data
	$source_img = imagecreatefromstring($data);
	$rotated_img = imagerotate($source_img, 90, 0); // rotate with angle 90 here
	$file = 'images/'. uniqid() . '.png';
	$imageSave = imagejpeg($rotated_img, $file, 10);
	imagedestroy($source_img);
	*/
?>
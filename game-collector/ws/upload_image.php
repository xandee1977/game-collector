<?php
	include_once 'configs/WS_CONF.php';
	include_once 'class/db.class.php';	
	include_once 'class/user.class.php';

	$data = $_POST["image64"];	
	$data = str_replace('data:image/jpeg;base64,', '', $data);
	$data = base64_decode($data);
	//
	$filename = sprintf("profile_%s.jpg", $_POST["profile_id"]);
	$file = 'pictures/'. $filename;
	$success = file_put_contents($file, $data);

    $user = new User();
    $array_data = array(
    	"profile_id" => $_POST["profile_id"],
    	"picture_url" => $filename
    );
    $result = $user->saveProfile($array_data);
    if(!$result) {
        echo "Erro ao salvar perfil";
    }	
?>
<?php
    include_once 'configs/WS_CONF.php';
    include_once 'class/db.class.php';

    $data = $_POST["image64"];  
    $data = str_replace('data:image/jpeg;base64,', '', $data);
    $data = base64_decode($data);
    $file = sprintf("pictures/%s/%s", $_POST["filetype"], $_POST["filename"]);
    $success = file_put_contents($file, $data);    

    // Se for uma imagem de profile
    if($_POST["filetype"] == 'profile') {
        include_once 'class/user.class.php';

        $user = new User();
        $array_data = array(
            "profile_id" => $_POST["profile_id"],
            "picture_url" => $_POST["filename"]
        );
        $result = $user->saveProfile($array_data);
        if(!$result) {
            echo "Erro ao salvar perfil";
        }
    }
?>
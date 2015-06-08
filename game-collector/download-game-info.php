<?php
include_once 'configs/WS_CONF.php';
include_once 'class/game.class.php';

$game = new Game();

set_time_limit(0); // limite de execucao infinito
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);



/*
$filtered = [];

$url = "http://thegamesdb.net/browse/6/?sortBy=&limit=1500&searchview=listing&page=1";
$html_list = file("http://thegamesdb.net/browse/6/?sortBy=&limit=1500&searchview=listing&page=1");

$id = 18;
$last_id = 314;
foreach($html_list as $line) {
    if(strpos ($line, "border: 1px solid #666;\"/>") && !strpos ($line, "boxart_blank.png")) {
        if($id > $last_id) {
            $p1 = explode("src=\"", $line);
            $p1 = explode("\" alt", $p1[1]);
            $game_picture = sprintf("http://thegamesdb.net/%s",  str_replace ( "_favcache/_tile-view" , "_gameviewcache" , $p1[0]));


            $p2 = explode("alt=\"", $line);
            $p2 = explode("\" style", $p2[1]);
            $game_title = str_replace(" Boxart", "", $p2[0]);
            
            $filename = save_image($game_picture);

            //$filtered[] = $image_url;
            $array_data = array(
                "game_title" => $game_title,
                "image" => $filename,
                "game_desc" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus pulvinar eros in posuere consectetur. Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                "game_type_id" => 1,
                "system_id" => 1
            );

            $result = $game->save($array_data);
            if($result) {
                sprintf("%s - SAVED!", $game_title);
            }
        }
        $id = $id + 1;
    }
}


function save_image($inPath){ 
    $p_filename = explode("/", $inPath);
    $filename = $p_filename[count($p_filename)-1];
    $commands = [
        "cd /home/conrado/Documentos/Projetos/apache/game-collector/ws/images/games/",
        sprintf('wget %s', $inPath)
    ];
    exec(implode(" && ", $commands));
    return $filename;
}
*/

?>
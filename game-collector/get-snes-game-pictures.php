<?php
include_once 'configs/WS_CONF.php';
include_once 'class/game.class.php';


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);

function save_image($inPath,$outPath){ 
    //echo sprintf("FROM: %s TO ===> %s <br />", $inPath, $outPath);
    $p_filename = explode("/", $inPath);
    $filename = $p_filename[count($p_filename)-1];
    $commands = [
        "cd /home/conrado/Documentos/Projetos/apache/game-collector/ws/images/",
        sprintf('wget %s', $inPath)
    ];
    exec(implode(" && ", $commands));
    
    
    printf('mv /home/conrado/Documentos/Projetos/apache/game-collector/ws/images/%s /home/conrado/Documentos/Projetos/apache/game-collector/ws/images/%s', $filename, $outPath);
    print "<br />";
    
    
    //exec(sprintf('mv /home/conrado/Documentos/Projetos/apache/game-collector/ws/images/%s /home/conrado/Documentos/Projetos/apache/game-collector/ws/images/%s', $filename, $outPath));

    /*
    $ch = curl_init($inPath);
    $fp = fopen($outPath, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    */
}

// Le o arquivo txt e busta o ID na nossa base
$img_urls = file("url_game_pictures.txt");
$game = new Game();
$game_list = $game->game_list("0,2000");
foreach($game_list as $game_item) {
    $game_name = str_replace("--", "-", str_replace(".","-", str_replace(":", "", str_replace(" ", "-", strtolower($game_item["game_title"])))));
    //echo count($img_urls);
    foreach($img_urls as $key => $img_url) {
        if (strpos($img_url, $game_name) !== false) {            
            $ext = pathinfo($img_url, PATHINFO_EXTENSION);
            save_image($img_url,sprintf("%s.%s", $game_item["game_id"], $ext));
            unset($img_urls[$key]); // Remove o elemento encontrado da lista
            break;
        }        
    }
}

/*
$img_urls = file("url_game_pictures.txt");
$game_ids = [];
foreach($img_urls as $img_url) {
    $parts = explode("/", $img_url);

    //echo str_replace("-", " ", $parts[count($parts)-1]);
    $game_file_name = str_replace("-", " ", $parts[count($parts)-1]);
    $game_file_name = trim(substr($game_file_name, 0, strrpos($game_file_name, "video")));
    echo $game_file_name;

    if (!in_array($game_id, $ids)) { 
        $file_current_content .= sprintf("%s\n", $url);
        $url_game_pictures[] = $url;
    }

    echo "<br />";
}
*/


/*
//PEGA AS URLS E SALVA NUM ARQUIVO TXT
$file_list = "url_game_pictures.txt";
$file_current_content = file_get_contents($file_list);

$url_game_pictures = [];
$n_pages = 20;
for($i=0; $i<$n_pages; $i++) {
    $html = file_get_contents(sprintf("http://www.ranker.com/list/all-super-nintendo-games-list-of-snes-console-games/video-games-by-console?page=%s", $i) );
    preg_match_all('#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si', $html, $result);
    if(is_array($result) && count($result)>0) {
        foreach($result[0] as $url) {
            if (strpos($url,"-video-games-photo-") !== false) {
                if (!in_array($url, $url_game_pictures)) { 
                    $file_current_content .= sprintf("%s\n", $url);
                    $url_game_pictures[] = $url;
                }
            }
        }
    }
    
}
// Grava a lista de urls no arquivo
file_put_contents($file_list, $file_current_content);
*/

?>
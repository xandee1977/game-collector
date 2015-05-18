<?php
include_once 'configs/WS_CONF.php';
include_once 'class/game.class.php';


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);


/*
$line = '<a href="http://thegamesdb.net/game/26319/" style="color: #000;">96 Zenkoku Koukou Soccer Senshuken</a>';

preg_match_all("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $line, $match);
//$name = $match[2][0];
var_dump($match);
*/

function save_image($inPath,$outPath)
{ //Download images from remote server    
    echo sprintf("FROM: %s TO ===> %s <br />", $inPath, $outPath);
    
    /*
    $in=fopen($inPath, "rb");
    $out=fopen($outPath, "wb");
    while ($chunk = fread($in,8192))
    {
        $res = fwrite($out, $chunk, 8192);
        var_dump($res);        
    }
    fclose($in);
    fclose($out);
    */

    $ch = curl_init($inPath);
    $fp = fopen($outPath, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);    
}

$file_lines = file("http://thegamesdb.net/browse/6/?limit=1185&page=1");
//$count = 0;
foreach($file_lines as $line) {
    /*
    if($count == 3) {
        break;
    }
    */
    
    if (strpos($line,'src="/banners/_favcache/_tile-view') !== false) {
        //echo $line;
        //<img src="/banners/_favcache/_tile-view/boxart/original/front/26319-1.jpg" alt="'96 Zenkoku Koukou Soccer Senshuken Boxart" style="border: 1px solid #666;">
        $parts = explode("\"", $line);
        $url = sprintf("http://thegamesdb.net%s", $parts[1]);
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $name = str_replace(" Boxart", "", $parts[3]);
        $filename = strtolower(str_replace("-Boxart", "", str_replace("'", "", str_replace(" ", "-", $parts[3]))));        

        $specials = ["?",":","!","&"];
        foreach($specials as $sp) {
            $filename = str_replace($sp, "", $filename);
        }

        //save_image($url,sprintf("/home/conrado/Documentos/Projetos/apache/game-collector/ws/pitcures/%s.%s", $filename, $ext));
        $game = new Game();
        $game_data = array("game_title" => $name, "system_id" => 1, "pic" => sprintf("%s.%s", $filename, $ext));
        $game_id = $game->save($game_data);
    }

}
?>
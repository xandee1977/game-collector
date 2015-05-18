<?php
ini_set('max_execution_time', 0); // Removing execution_time_limit

$start = 1;
if($_REQUEST["start"]) {
    $start = (int) $_REQUEST["start"];
}
$end = 133;
if($_REQUEST["end"]) {
    $end = (int) $_REQUEST["end"];
}


for($i=$start; $i<=$end; $i++) {
	/* SuperGamePower
    $path = sprintf("http://files.datassette.org/revistas/sgp_%s.pdf", $i);
	$save_path = sprintf("/home/conrado/tmp/revistas/sgp_%s.pdf", $i);
    */
    /* GamePower
    $path = sprintf("http://files.datassette.org/revistas/gamepower_%s.pdf", $i);
    $save_path = sprintf("/home/conrado/tmp/revistas/gamepower_%s.pdf", $i);
    */
    /* SuperGame
    $path = sprintf("http://files.datassette.org/revistas/supergame_%s.pdf", $i);
    $save_path = sprintf("/home/conrado/tmp/revistas/SuperGame/supergame_%s.pdf", $i);
    */
    /*
    $path = sprintf("http://files.datassette.org/revistas/videogame_%s.pdf", $i);
    $save_path = sprintf("/home/conrado/tmp/revistas/VideoGame/videogame_%s.pdf", $i);
    */

    $path = sprintf("http://files.datassette.org/revistas/gamers_%s.pdf", $i);
    $save_path = sprintf("/home/conrado/tmp/revistas/Gamers/gamers_%s.pdf", $i);    

    $ch = curl_init($path);
    $fp = fopen($save_path, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);	
}
?>
<?php
set_time_limit(0); // limite de execucao infinito
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(1);

$list_words = ['L'];

$links_list = [];
$control = [];
$file = fopen("tmp/wiki_links.txt","w");
foreach($list_words as $word) {
	//$list_urls[] = sprintf("http://pt.wikipedia.org/wiki/Lista_de_jogos_para_Super_Nintendo_(%s)", $word);	
	$content = file(sprintf("http://pt.wikipedia.org/wiki/Lista_de_jogos_para_Super_Nintendo_(%s)", $word));	
	foreach($content as $line_content) {
		if(strpos($line_content, "title=")) {
			$p = explode("href=\"", $line_content);
			$p = explode("\" title=\"", $p[1]);
			$link = sprintf("http://pt.wikipedia.org%s", $p[0]);
			$p = explode("title=\"", $line_content);
			$p = explode("\" class=\"", $p[1]);
			$title = $p[0];

			$line = sprintf("link: [ %s ] - title: %s\r\n", $link, $title);
			if(!in_array($line, $control)) {
				fwrite($file, $line);
				$control[] = $line;	
			}
			
		}
	}	
}
fclose($file);
?>
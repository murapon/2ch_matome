<?php
//$raw_contents = file_get_contents("sample.htm");

$raw_contents='<b>‘S“Še”F<span style="color:#FF8C00">31Œ</span> •½‹Ï“Še”F<span style="color:#FF8C00">103.3Œ/ŽžŠÔ</span></b> (2013/12/25 20:24Žž
<img src="http://img.2ch.net/ico/folder1_03.gif">';


//preg_match_all('/(http:\/\/|https:\/\/)\/.+(jpg|jpeg|gif|png)/', $raw_contents , $matches);
preg_match('/(http:\/\/|https:\/\/)[^\s]*?\.(jpg|jpeg|gif|png)/', $raw_contents , $matches);
var_dump($matches[0]);

preg_match('/.jpg$/', $matches[0] , $image_type);
var_dump($image_type);
if(count($image_type) > 0){
    echo("jpg\n");
}
preg_match('/.png$/', $matches[0] , $image_type);
var_dump($image_type);
if(count($image_type) > 0){
    echo("jpg\n");
}



?>

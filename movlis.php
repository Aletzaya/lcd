<?php
$tabla="est";
include("lib/kaplib.php");
$link=conectarse();
if ($Guarda_x > 0) {    
   $lUp=mysql_query("update $tabla SET lt1='$Lt1',lt2='$Lt2',lt3='$Lt3',lt4='$Lt4',lt5='$Lt5',lt6='$Lt6',lt7='$Lt7',lt8='$Lt8',lt9='$Lt9',lt10='$Lt10' where estudio='$cKey' limit 1",$link);
   header("Location: lista.php?busca=$cKey");
}			
mysql_close($link);
?>

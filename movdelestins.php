<?php
$tabla="estins";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("delete from $tabla where instituto='$cKey' and estudio='$Estudio' limit 1",$link);
header("Location: instd.php?cKey=$cKey");
mysql_close($link);
?>

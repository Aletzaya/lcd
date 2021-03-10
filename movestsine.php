<?php
$tabla="ests";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("delete from $tabla where estudio='$cKey' and descripcion='$Sinonimo' limit 1",$link);
header("Location: estudiose.php?cKey=$cKey");
mysql_close($link);
?>

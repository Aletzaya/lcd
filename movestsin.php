<?php
$tabla="ests";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("insert into $tabla (estudio,descripcion) VALUES ('$cKey','$Sinonimo')",$link);
header("Location: estudiose.php?cKey=$cKey");
mysql_close($link);
?>

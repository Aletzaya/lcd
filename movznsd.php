<?php
$tabla="znsd";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("insert into $tabla (zona,localidad,observacion) VALUES ('$cKey','$Localidad','$Observaciones')",$link);
header("Location: zonasd.php?cKey=$cKey");
//$lUp=mysql_query("update $tabla set descripcion = 'ZONA CANCELADA' where zona='$cKey' limit 1",$link);
//header("Location: zonasd.php?cKey=$cKey");
mysql_close($link);
?>

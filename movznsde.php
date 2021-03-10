<?php
$tabla="znsd";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("delete from $tabla  where localidad='$Localidad' and zona='$cKey' limit 1",$link);
header("Location: zonasd.php?cKey=$cKey");
//$lUp=mysql_query("update $tabla set descripcion = 'ZONA CANCELADA' where zona='$cKey' limit 1",$link);
//header("Location: zonasd.php?cKey=$cKey");
mysql_close($link);
?>

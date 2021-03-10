<?php
$tabla="depd";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("insert into $tabla (departamento,subdepto,nombre) VALUES ('$cKey','$Subdepto','$Nombre')",$link);
header("Location: deptod.php?cKey=$cKey");
//$lUp=mysql_query("update $tabla set descripcion = 'ZONA CANCELADA' where zona='$cKey' limit 1",$link);
//header("Location: zonasd.php?cKey=$cKey");
mysql_close($link);
?>

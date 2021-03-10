<?php
$tabla="depd";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("select estudio from est where subdepto='$Subdepto' limit 1",$link);
if (!$row=mysql_fetch_array($lUp)){
   $lUp=mysql_query("delete from $tabla  where departamento='$cKey' and subdepto='$Subdepto' limit 1",$link);   
   header("Location: deptod.php?cKey=$cKey");
}else{
   $Msj="Por cuestiones de Integridad referencial, este sub-departamento no se puede eliminar!";
   header("Location: msj.php?url=deptod.php?cKey=$cKey&Msj=$Msj");
}
   
//$lUp=mysql_query("update $tabla set descripcion = 'ZONA CANCELADA' where zona='$cKey' limit 1",$link);
//header("Location: zonasd.php?cKey=$cKey");
mysql_close($link);
?>

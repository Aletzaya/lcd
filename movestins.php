<?php
$tabla="estins";
include("lib/kaplib.php");
$link=conectarse();
$lUp=mysql_query("select estudio from est where estudio='$Estudio' limit 1",$link);
$lEx=mysql_fetch_array($lUp);
if($lEx){
    $lUp=mysql_query("insert into $tabla (instituto,estudio) VALUES ('$cKey','$Estudio')",$link);
   header("Location: instd.php?cKey=$cKey");
}else{
   $Msj="La Clave del estudio No existe($Estudio), favor de verificar";
    header("Location: msj.php?url=instd.php?cKey=$cKey&Msj=$Msj&busca=$busca");
}	  

mysql_close($link);
?>

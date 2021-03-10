<?php
include("lib/kaplib.php");
$link=conectarse();
if ($Guarda_x > 0) {
    $tabla="reportes";
	if ($cKey=='NUEVO'){
        $lUp=mysql_query("insert into $tabla (nombre,descripcion,instruccion) VALUES ('$Nombre','$Descripcion','$cSql')",$link);
		$cKey=mysql_insert_id();
	     header("Location: reportesd.php?cKey=$cKey");
  	}else{
        $lUp=mysql_query("update $tabla SET descripcion='$Descripcion',nombre='$Nombre',instruccion='$cSql' where id='$cKey' limit 1",$link);
	     header("Location: reportesd.php?cKey=$cKey");			
	}			
}elseif($Guarda2_x > 0){
    $tabla="reportesd";
	$lUp=mysql_query("insert into $tabla (id,pregunta,variable,tipo,orden,longitud) VALUES ('$cKey','$Pregunta','$Variable','$Tipo','$Orden','$Longitud')",$link);
	header("Location: reportesd.php?cKey=$cKey");
}else{
 	$lUp=mysql_query("delete from reportesd where id='$cKey' and pregunta='$Pregunta' limit 1",$link);
	header("Location: reportesd.php?cKey=$cKey");
}
mysql_close($link);
?>

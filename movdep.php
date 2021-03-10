<?php
$tabla="dep";
include("lib/kaplib.php");
$link=conectarse();
if ($Guarda_x > 0) {    
	if ($cKey=='NUEVO'){
       $lUp=mysql_query("insert into $tabla (departamento,nombre) VALUES ('','$Nombre')",$link);
	   $Idcli=mysql_insert_id();
       header("Location: depto.php?busca=$Idcli");
	}else{
        $lUp=mysql_query("update $tabla SET nombre='$Nombre' where departamento='$cKey' limit 1",$link);
		 header("Location: depto.php");
	}			
}elseif ($Elimina_x >0){
    $Msj="Por cuestiones de Integridad, proceso deshabilitado!";
    header("Location: msj.php?url=depto.php?busca=$cKey&Msj=$Msj");
    //$lUp=mysql_query("update $tabla set descripcion = 'ZONA CANCELADA' where zona='$cKey' limit 1",$link);
	//header("Location: zonas.php?busca=$cKey");
}else{
	header("Location: depto.php?busca=$cKey");
}
mysql_close($link);
?>

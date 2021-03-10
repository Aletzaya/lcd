<?php
$tabla="zns";
include("lib/kaplib.php");
$link=conectarse();
if ($Guarda_x > 0) {    
	if ($cKey=='NUEVO'){
       $lUp=mysql_query("insert into $tabla (zona,descripcion,poblacion) VALUES ('','$Descripcion','$Poblacion')",$link);
	   $Idcli=mysql_insert_id();
       header("Location: zonas.php?busca=$Idcli");
	}else{
        $lUp=mysql_query("update $tabla SET descripcion='$Descripcion',poblacion='$Poblacion' where zona='$cKey' limit 1",$link);
		 header("Location: zonas.php?busca=$cKey");
	}			
}elseif ($Elimina_x >0){
    $Msj="Por cuestiones de Integridad, proceso deshabilitado!";
    header("Location: msj.php?url=zonas.php?busca=$cKey&Msj=$Msj");
    //$lUp=mysql_query("update $tabla set descripcion = 'ZONA CANCELADA' where zona='$cKey' limit 1",$link);
	//header("Location: zonas.php?busca=$cKey");
}else{
	header("Location: zonas.php?busca=$cKey");
}
mysql_close($link);
?>

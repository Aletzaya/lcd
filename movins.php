<?php
$tabla="inst";
include("lib/kaplib.php");
$link=conectarse();
if ($Guarda_x > 0) {    
	if ($cKey=='NUEVO'){
       $lUp=mysql_query("insert into $tabla (institucion,nombre,alias,direccion,localidad,municipio,referencia,codigo,rfc,fax,telefono,director,subdirector,lista,mail) VALUES ('','$Nombre','$Alias','$Direccion','$Localidad','$Municipio','$Referencia','$Codigo','$Rfc','$fax','$Telefono','$Director','$Subdirector','$Lista','$Mail')",$link);
	   $Idcli=mysql_insert_id();
       header("Location: institu.php?busca=-1");
	}else{
       $lUp=mysql_query("update $tabla SET nombre='$Nombre',alias='$Alias',direccion='$Direccion',localidad='$Localidad',municipio='$Municipio',referencia='$Referencia',codigo='$Codigo',rfc='$Rfc',fax='$Fax',telefono='$Telefono',director='$Director',subdirector='$Subdirector',lista='$Lista',mail='$Mail' where institucion='$cKey' limit 1",$link);
	   header("Location: institu.php?busca=$cKey");
	}			
}elseif ($Elimina_x >0){
    $lUp=mysql_query("update $tabla set descripcion = 'INSTITUCION CANCELADA' where institucion='$cKey' limit 1",$link);
	header("Location: institu.php?busca=$cKey");
}else{
	header("Location: institu.php?busca=$cKey");
}
mysql_close($link);
?>

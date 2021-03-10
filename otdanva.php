<?php
include("lib/kaplib.php");
$link=conectarse();
if ($Guarda_x > 0){
    $tabla="cli";
	$Sp=" ";
    $Prueba=trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
    if($cKey=='NUEVO'){
	    $lUp=mysql_query("select cliente,nombrec from $tabla where apellidop='$Apellidop' and apellidom='$Apellidom' and nombre='$Nombre'",$link);	
		$cCpo=mysql_fetch_array($lUp);
		if($cCpo[1]<>""){
		   $Msj="El Cliente $cCpo[0] $cCpo[1] ya existe, favor de verificar";
	       header("Location: msj.php?url=clientes.php?Msj=$Msj&busca=$cCpo[0]");
		}   
	}else{
        $lUp=mysql_query("update $tabla SET apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',direccion='$Direccion',localidad='$Localidad',municipio='$Municipio',telefono='$Telefono',credencial='$Credencial',codigo='$Codigo',sexo='$Sexo',titular='$Titular',mail='$Mail',padecimiento='$Padecimiento',alta='$Alta',fechan='$Fechan',nombrec='$Prueba',afiliacion='$Afiliacion',observaciones='$Observaciones',zona='$Zona',institucion='$Institucion',refubicacion='$Refubicacion',expiracion='$Expiracion',expira='$Expira' where cliente='$cKey' limit 1",$link);
		header("Location: clientes.php?busca=$busca");		
	}	
}else{
      $lUp=mysql_query("select estudio,descripcion from est  where estudio='$Estudio' ",$link);
	  if($cCpo=mysql_fetch_array($lUp)){
        $lUp=mysql_query("insert into otdnvas (recepcionista,estudio,descripcion,descuento,precio) VALUES ('$Usr','$Estudio','$cCpo[1]',0,0)",$link);
	   header("Location: ordenesnvas.php?busca=$busca");
	}else{
		$Msj="El Estudio $Estudio no existe, favor de verificar";
	    header("Location: msj.php?url=ordenesnvas.php?Msj=$Msj");
	}   
}
mysql_close($link);
?>
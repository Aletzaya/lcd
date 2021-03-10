<?php
if ($Guarda_x > 0) {
    $tabla="cli";
    include("lib/kaplib.php");
    $link=conectarse();
	$Sp=" ";
    $Prueba=trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
    if($cKey=='NUEVO'){
	    $lUp=mysql_query("select cliente,nombrec,apellidop from $tabla where apellidop='$Apellidop' and apellidom='$Apellidom' and nombre='$Nombre'",$link);	
		$cCpo=mysql_fetch_array($lUp);
		if($cCpo[apellidop]==$Apellidop){
		   $Msj="El Cliente $cCpo[nombrec] ya existe con la cuenta $cCpo[cliente], favor de verificar";
	       header("Location: msj.php?url=clientes.php?busca=$cCpo[0]&Msj=$Msj");
		}else{  
           $Fecha=date("Y-m-d");
		   $Mes=substr($Fechan,5,2);
		   $Dia=substr($Fechan,8,2);
		   $Ano=substr($Fechan,0,4);
		   if(!checkdate($Mes,$Dia,$Ano)){
             $Fecha=date("Y-m-d");
		     $Fechan = substr($Fecha,0,4) - $Anos ."-".substr($Fecha,5,2)."-".substr($Fecha,8,2);
		   }
   	       $lUp=mysql_query("insert into $tabla (apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,credencial,codigo,sexo,titular,mail,padecimiento,alta,fechan,nombrec,afiliacion,observaciones,zona,institucion,expiracion,expira,refubicacion,fecha) VALUES ('$Apellidop','$Apellidom','$Nombre','$Direccion','$Localidad','$Municipio','$Telefono','$Credencial','$Codigo','$Sexo','$Titular','$Mail','$Padecimiento','$Fecha','$Fechan','$Prueba','$Afiliacion','$Observaciones','$Zona','$Institucion','$Expiracion','$Expira','$Refubicacion','$Fecha')",$link);
		   $Cliente=mysql_insert_id();
   	       header("Location: clientes.php");		
		}   
	}else{
        $Fecha=date("Y-m-d");
		$Mes=substr($Fechan,5,2);
		$Dia=substr($Fechan,8,2);
		$Ano=substr($Fechan,0,4);
		if(!checkdate($Mes,$Dia,$Ano)){
		    $Fechan = substr($Fecha,0,4) - $Anos ."-".substr($Fecha,5,2)."-".substr($Fecha,8,2);
		}	
        $lUp=mysql_query("update $tabla SET apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',direccion='$Direccion',localidad='$Localidad',municipio='$Municipio',telefono='$Telefono',credencial='$Credencial',codigo='$Codigo',sexo='$Sexo',titular='$Titular',mail='$Mail',padecimiento='$Padecimiento',alta='$Alta',fechan='$Fechan',nombrec='$Prueba',afiliacion='$Afiliacion',observaciones='$Observaciones',zona='$Zona',institucion='$Institucion',refubicacion='$Refubicacion',expiracion='$Expiracion',expira='$Expira',fecha='$Fecha' where cliente='$cKey' limit 1",$link);
		header("Location: clientes.php?busca=$busca");		
	}	
}elseif ($Elimina_x >0){
   $tabla="cli";
   include("lib/kaplib.php");
   $link=conectarse();
   $lUp=mysql_query("delete from $tabla where cliente='$cKey'",$link);
   header("Location: clientes.php?busca=$busca");
}else{
   header("Location: clientese.php?busca=$busca");
}
mysql_close($link);
?>
<?php
$tabla="est";
include("lib/kaplib.php");
$link=conectarse();
if ($Guarda_x > 0) {    
	if ($cKey=='NUEVO'){
	   $lUp=mysql_query("select estudio from  $tabla where estudio='$Estudio' limit 1",$link);
	   $lEx=mysql_fetch_array($lUp);
	   if(!$lEx){
          $lUp=mysql_query("insert into $tabla (estudio,descripcion,objetivo,condiciones,tubocantidad,tiempoest,entord,entexp,enthos,enturg,equipo,tecnica,muestras,estpropio,subdepto,contenido,comision) VALUES ('$Estudio','$Descripcion',ltrim('$Objetivo'),ltrim('$Condiciones'),'$Tubocantidad','$Tiempoest','$Entord','$Entexp','$Enthos','$Enturg','$Equipo','$Tecnica','$Muestras','$Estpropio','$Subdepto','$Contenido','$Comision')",$link);
          header("Location: estudios.php?busca=$Estudio");
	   }else{
	      $Msj="La Clave del estudio ya existe($Estudio), favor de verificar";
	      header("Location: msj.php?url=estudiose.php?cKey=NUEVO&Msj=$Msj&busca=$busca");
	   }
	}else{
       $lUp=mysql_query("update $tabla SET descripcion='$Descripcion',objetivo='$Objetivo',condiciones='$Condiciones',tubocantidad='$Tubocantidad',tiempoest='$Tiempoest',entord='$Entord',entexp='$Entexp',enthos='$Enthos',enturg='$Enturg',equipo='$Equipo',tecnica='$Tecnica',muestras='$Muestras',estpropio='$Estpropio',subdepto='$Subdepto',contenido='$Contenido',comision='$Comision' where estudio='$cKey' limit 1",$link);
	   header("Location: estudios.php?busca=$cKey");
	}
}elseif ($Elimina_x >0){
    $lUp=mysql_query("delete from $tabla where estudio='$cKey' limit 1",$link);
	header("Location: estudios.php");
}else{
	header("Location: estudios.php?busca=$cKey");
}
mysql_close($link);
?>
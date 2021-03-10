<?php
if ($Guarda_x > 0) {
    $tabla="med";
    include("lib/kaplib.php");
    $link=conectarse();
	$Sp=" ";
    $NomCom=trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
    if($cKey=='NUEVO'){
        //$lUp=mysql_query("insert into $tabla (apellidop,apellidom,nombre,rfc,cedula,codigo,especialidad,subespecialidad,dirparticular,locparticular,telparticular,dirconsultorio,locconsultorio,telconsultorio,telcelular,mail,diasconsulta,hravisita,hraconsulta,institucion,dependencia,status) VALUES ('$Apellidom','$Apellidop','$Nombre','$Rfc','$Cedula','$Codigo','$Especialidad','$Subespecialidad','$Dirparticular','$Locparticular','$Telparticular','$Dirconsultorio','$Locconsultorio','$Telconsultorio','$Telcelular','$Mail','$Diasconsulta','$Hraconsulta','$Institucion','$Dependencia','$Status')",$link);
	    //$lUp=mysql_query("insert into $tabla (apellidop,apellidom,nombre,rfc,cedula,codigo,nombrec,especialidad,subespecialidad,dirparticular,locparticular,telparticular,dirconsultorio,locconsultorio,telconsultorio,telcelular,mail,diasconsulta,hravisita,hraconsulta,zona,institucion) VALUES ('$Apellidop','$Apellidom','$Nombre','$Rfc','$Cedula','$Codigo','$Nombrec','$Especialidad','$Subespecialidad','$Dirparticular','$Locparticular','$Telparticular','$Dirconsultorio','$Locconsultorio','$Telconsultorio','$Telcelular','$Mail,'$Diasconsulta','$Hravisita','$Hraconsulta','$Zona','$Institucion')",$link);
		$Medico=strtoupper($Medico);
	    $lUp=mysql_query("insert into $tabla (medico,apellidop,apellidom,nombre,rfc,cedula,codigo,nombrec,especialidad,subespecialidad,dirparticular,locparticular,telparticular,dirconsultorio,locconsultorio,telconsultorio,telcelular,mail,diasconsulta,hravisita,hraconsulta,zona,institucion,comisiones,telinstitucion,fecha,fechanac,comision,status,fecharev,refubicacion) VALUES ('$Medico','$Apellidop','$Apellidom','$Nombre','$Rfc','$Cedula','$Codigo','$NomCom','$Especialidad','$Subespecialidad','$Dirparticular','$Locparticular','$Telparticular','$Dirconsultorio','$Locconsultorio','$Telconsultorio','$Telcelular','$Mail','$Diasconsulta','$Hravisita','$Hraconsulta','$Zona','$Institucion','$Comisiones','$Telinstitucion','$Fecha','$Fechanac','$Comision','Status','$Fecharev','$Refubicacion')",$link);
	}else{
        $lUp=mysql_query("update $tabla SET apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',rfc='$Rfc',cedula='$Cedula',codigo='$Codigo',nombrec='$NomCom',especialidad='$Especialidad',subespecialidad='$Subespecialidad',dirparticular='$Dirparticular',locparticular='$Locparticular',telparticular='$Telparticular',zona='$Zona',hraconsulta='$Hraconsulta',hravisita='$Hravisita',comisiones='$Comisiones',diasconsulta='$Diasconsulta',mail='$Mail',telcelular='$Telcelular',telconsultorio='$Telconsultorio',locconsultorio='$Locconsultorio',telinstitucion='$Telinstitucion',fecha='$Fecha',fechanac='$Fechanac',comision='$Comision',status='$Status',fecharev='$Fecharev',refubicacion='$Refubicacion' where medico='$cKey' limit 1",$link);
	}
	header("Location: medicos.php?busca=$busca");
}elseif ($Elimina_x >0){
   $tabla="med";
   include("lib/kaplib.php");
   $link=conectarse();
   $lUp=mysql_query("delete from $tabla where medico='$cKey'",$link);
   header("Location: medicos.php?busca=$busca");	
}else{
   header("Location: medicos.php?busca=$busca");
}
mysql_close($link);
?>
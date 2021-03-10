<?php

  session_start();
  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");
  date_default_timezone_set("America/Mexico_City");
  $link=conectarse();

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $Institucion=$_REQUEST[Institucion];

  $Departamento=$_REQUEST[Departamento];

  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

  $Arecepcion=$_REQUEST[Arecepcion];
  
  $Externo=$_REQUEST[Externo];
	
  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

	$Sucursal     =   $_REQUEST[Sucursal];
	//$Sucursal     =   $Sucursal[0];
	$sucursalt = $_REQUEST[sucursalt];
	$sucursal0 = $_REQUEST[sucursal0];
	$sucursal1 = $_REQUEST[sucursal1];
	$sucursal2 = $_REQUEST[sucursal2];
	$sucursal3 = $_REQUEST[sucursal3];
	$sucursal4 = $_REQUEST[sucursal4];

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php

  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="*";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}
	}

if($Sucursal <> '*'){
	if($Arecepcion=="S"){
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
	
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' AND ($Sucursal)
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' AND ($Sucursal)
					order by ot.orden";
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00' 
					and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00' AND ($Sucursal)
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00' AND ($Sucursal)
					order by ot.orden";
				}
			}
		}
	
	}else{
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento AND ($Sucursal)
					order by ot.orden";
				}
					
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento AND ($Sucursal)
					order by ot.orden";	
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' AND ($Sucursal)
					order by ot.orden";
				}
				
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and est.estpropio='N' AND ($Sucursal)
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' AND ($Sucursal)
					order by ot.orden";
				}
			}
		}
	}

}else{
	if($Arecepcion=="S"){
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N'
					order by ot.orden";
	
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00'
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N'
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and otd.cinco='0000-00-00 00:00:00'
					order by ot.orden";
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00' 
					and est.estpropio='N'
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and otd.cinco='0000-00-00 00:00:00'
					order by ot.orden";
				}
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00' and est.estpropio='N'
					order by ot.orden";
					
				}else{	
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and otd.cinco='0000-00-00 00:00:00'
					order by ot.orden";
				}
			}
		}
	
	}else{
		if(strlen($Departamento)>0){
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N'
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and dep.departamento=$Departamento 
					and est.depto=$Departamento
					order by ot.orden";
				}
					
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento and est.estpropio='N'
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, est.depto, dep.departamento, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones,
					otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est, dep
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and dep.departamento=$Departamento 
					and est.depto=$Departamento
					order by ot.orden";	
				}
			}
		}else{
			if(strlen($Institucion)>0){
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion' and est.estpropio='N'
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and ot.institucion='$Institucion'
					order by ot.orden";
				}
				
			}else{
				if($Externo=="S"){
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, est.estpropio, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada' and est.estpropio='N'
					order by ot.orden";
					
				}else{
					$cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
					ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
					ot.status, ot.horae, otd.statustom, otd.usrest, otd.fechaest, ot.observaciones, otd.etiquetas,otd.obsest,ot.suc
					FROM ot, cli, otd, est
					WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
					ot.fecha <='$Fechaf' and ot.status<>'Entregada'
					order by ot.orden";
				}
			}
		}
	}
}

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" alt="" width="187" height="61"> 
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio 
        Clinico Duran</strong><br><font size="1">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "Relacion de Ordenes de trabajo pendientes x entregar del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='12'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Suc/Inst/Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente / Estudios</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Captura</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Entrega</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Estimado</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Restante</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>servicio</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Etiq.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>R. Est.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Cap.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Imp.</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Rece.</font></th>";
    echo "<tr><td colspan='12'><hr noshade></td></tr>";
    $Orden=0;
    $Ordenes=1;
	$Estudios=0;
    while($rg=mysql_fetch_array($UpA)) {
    	if($Orden<>$rg[orden]){
    		if($Orden<>0){
    			echo "<th colspan='5' align='center'><font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>$Obs</font></th>";
    			echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th>
				<th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
				$Ordenes++;
				$Urge2=0;
    		}
	    	if($rg[servicio]=="Urgente"){
				$Urgencia="* * *  U R G  * * * ";
				$Gfont4="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
			}else{
				$Urgencia=$rg[servicio];
				$Gfont4="<font size='1' face='Arial, Helvetica, sans-serif'>";
			}
			$Rec=$rg[recepcionista];
			$Obs=$rg[observaciones];
			
			$horafin= $rg[horae];
			$horaini=$rg[hora];
			$fechaInicial = $rg[fecha];
			$fechaInicial2 = date('Y-m-d');
			$fechaActual = $rg[fechae];
			$horaInicial = abs(strtotime($rg[hora]));
			$horaActual = abs(strtotime($rg[horae]));
			$horaInicial2 = abs(strtotime(date('H:i:s')));
			$Hora3=date("H:i:s");

			$diff = abs(strtotime($fechaActual) - strtotime($fechaInicial));
			$diff2 = abs(strtotime($fechaInicial2) - strtotime($fechaActual));
			
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*23));
			
			$years2 = floor($diff2 / (365*60*60*24));
			$months2 = floor(($diff2 - $years2 * 365*60*60*24) / (30*60*60*24));
			$days2 = floor(($diff2 - $years2 * 365*60*60*24 - $months2*30*60*60*24)/ (60*60*23));

			if($diff>0){
				if($horaInicial>$horaActual){
					$days=$days;
					$mensaje=' ';
				}else{
					$days=$days;
					$mensaje=' ';
				}
			}else{
				if($horaInicial>$horaActual){
					$days=$days;
					$mensaje='Invalido';
				}else{
					$days=$days;
					$mensaje=' ';
				}
			}
			
			if($fechaInicial2>$fechaActual){
					$days2=' ';
					$mensaje2=' ';
					$reshora=0; 
			}else{
				if($fechaInicial2==$fechaActual and $horaInicial2>$horaActual){
					$days2=' ';
					$mensaje2=' ';
					$reshora=0; 
				}else{
					$days2=$days2;
					$mensaje2=' ';
					if($horaInicial2>=$horaActual){
						$reshora= RestarHoras($Hora3,$rg[horae]);
						$days2=$days2-1;
					}else{
						$reshora= RestarHoras($Hora3,$rg[horae]);
					}
				}
			}
						
			if($days==0){
				$dias=' ';
				$dias2=' ';
			}else{
				$dias= $days;
				$dias2='dias';
			}
			
			if($days2<=0){
				$dias3=' ';
				$dias4=' ';
			}else{
				$dias3= $days2;
				$dias4='dias';
			}

    		echo "<tr bgcolor ='#CCCC99'>";
    		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[suc]&nbsp;-&nbsp;$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
    		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[nombrec]</font></th>";
    		echo "<th align='center'><font size='1' color='#009933'>$rg[fecha] &nbsp;&nbsp; $rg[hora] &nbsp; </font></th>";
			echo "<th align='center'><font size='1' color='#0000CC'>$rg[fechae] &nbsp;&nbsp; $rg[horae] &nbsp; </font></th>";
			echo "<th align='center'><font size='1' color='#990066'>$dias $dias2 ".RestarHoras($horaini,$horafin)." 
			<font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'>$mensaje</font></th>";

			echo "<th align='center'><font size='2' color='#990033'>$dias3 $dias4 $reshora</font></th>";

			echo "<th align='center'>$Gfont4 $Urgencia &nbsp;&nbsp;&nbsp;&nbsp; $Rec </font></th>";
			echo "<th colspan='5'>&nbsp;</font></th>";
    		echo "</tr>";
    	    $Orden=$rg[orden];
    	}

		if($rg[etiquetas]>=1){
			$imagen1="OKShield.png";
		}else{	
			$imagen1="ErrorCircle.png";
		}
			$unoa=$rg[uno];
			$uno=substr($unoa,10);
			$esp="_";
			$uno.=$esp;
			$uno.=$unoa;

		if($rg[dos]<>'0000-00-00 00:00:00'){
			$imagen2="OKShield.png";
		}else{	
			$imagen2="ErrorCircle.png";
		}
			$dosa=$rg[dos];
			$dos=substr($dosa,10);
			$esp="_";
			$dos.=$esp;
			$dos.=$dosa;

		if($rg[tres]<>'0000-00-00 00:00:00'){
			$imagen3="OKShield.png";
		}else{	
			$imagen3="ErrorCircle.png";
		}
			$tresa=$rg[tres];
			$tres=substr($tresa,10);
			$esp="_";
			$tres.=$esp;
			$tres.=$tresa;

		if($rg[cuatro]<>'0000-00-00 00:00:00'){
			$imagen4="OKShield.png";
		}else{	
			$imagen4="ErrorCircle.png";
		}
			$cuatroa=$rg[cuatro];
			$cuatro=substr($cuatroa,10);
			$esp="_";
			$cuatro.=$esp;
			$cuatro.=$cuatroa;

		if($rg[cinco]<>'0000-00-00 00:00:00'){
			$imagen5="OKShield.png";
			$pendiente=" ";
			$Gfont3="<font size='1' face='Arial, Helvetica, sans-serif'>";
			$mensacinco='Entr a Rec';
			$mensacincob='Tiempo Real';
			$cincob=$rg[cinco];
		}else{	
			$imagen5="ErrorCircle.png";
			$pendiente="Pendiente";
			$Gfont3="<font color='#FF6600' size='1' face='Arial, Helvetica, sans-serif'>";
			$mensacinco='&nbsp;';
			$mensacincob='&nbsp;';
			$cincob=' ';
		}
			$cincoa=$rg[cinco];
			$cinco=substr($cincoa,11);
			$esp="_";
			$cinco.=$esp;
			$cinco.=$cincoa;

		if($rg[estudio]=="URG"){
			$Urge=1;
		}else{
			$Urge=0;
		}

    	if($Urge<>0){
			$Urgencia2="* * *  U R G   * * * ";
			$Gfont5="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			$Urgencia2=" ";
			$Gfont5="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}
    	if($rg[statustom]=='PENDIENTE'){
			$Gfont6="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			$Gfont6="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}
		
		if($rg[fechaest]=='0000-00-00 00:00:00'){
			$Gfont7="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			$Gfont7="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}
		$fechatn=substr($rg[fechaest],0,10);		
		$horatn=substr($rg[fechaest],11);
		//$horatn2=$horatn-$rg[hora];
		
		$horafin=$horatn;
		$horaini=$rg[hora];
		if($horafin=='00:00:00'){
			$Hora2=date('H:i:s');
			$horafin=$Hora2;
		}
		//
		echo "<tr>";
		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]&nbsp;-&nbsp;</font></th>";
		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> $rg[descripcion] &nbsp;&nbsp;&nbsp; $Gfont3 $pendiente &nbsp;&nbsp;&nbsp; $Gfont5 $Urgencia2</font></th>";
		echo "<th align='left' colspan='2'><font size='1' face='Arial, Helvetica, sans-serif'>$Gfont7 $rg[statustom] &nbsp;&nbsp;&nbsp; $fechatn &nbsp; $horatn &nbsp;&nbsp; $rg[usrest]</font>";
		echo "<font size='1' face='Arial, Helvetica, sans-serif'>$Gfont7 ".RestarHoras($horaini,$horatn); "</font></th>";
		echo "<th align='left'><font size='1' color='#0000CC'>&nbsp;&nbsp;&nbsp; $mensacinco $cincob </font></th>";
		if($rg[cinco]<>'0000-00-00 00:00:00'){
			echo "<th align='left'><font size='1' color='#990033'>&nbsp;&nbsp;&nbsp; $mensacincob ".RestarHoras($horatn,$cinco)."</font></th>";
		}else{
			echo "<th align='left'><font size='2' color='#009900'>&nbsp;&nbsp;&nbsp;</font></th>";
		}
		if($rg[cinco]=='0000-00-00 00:00:00' and $reshora=='0'){
			$texcedido=date('H:i:s');
			echo "<th align='left'><font size='2' color='#FF0000'>&nbsp;&nbsp;&nbsp; Excedido ".RestarHoras($rg[horae],$texcedido)."</font></th>";
		}else{
			echo "<th align='left'><font size='2' color='#FF0000'>&nbsp;&nbsp;&nbsp; </font></th>";
		}
		
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen1 alt=$uno></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen2 alt=$dos></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen3 alt=$tres></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen4 alt=$cuatro></font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen5 alt=$cinco></font></th>";
   		echo "</tr>"; 
		if($rg[obsest]<>''){
		echo "<tr>";
    	echo "<th colspan='2' align='left'></th>";
    	echo "<th colspan='2' align='left'><font color='#0066FF' size='1' face='Arial, Helvetica, sans-serif'>$rg[obsest]</font></th>";
    	echo "</tr>";
		}
		$Estudios++;
			$Urge=0;
     }
     echo "<th colspan='5' align='center'><font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>$Obs</font></th>";
	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th>
	 <th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
     echo "<tr><td colspan='12'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "</tr>"; 

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];

              
     echo "</table>";
	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=22&fechas=1&FecI=$FecI&FecF=$FecF'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
?>
</font></font> 
<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=22&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
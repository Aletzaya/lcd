<?php

  session_start();

 if(!isset($_REQUEST[Depto])){$Depto=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[Depto];$Depto=$_REQUEST[Depto];}

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=10;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Estudio=$_REQUEST[Estudio];

  $Estudio2=$_REQUEST[Estudio2];
  
  $Subdepto=$_REQUEST[Subdepto];

  $sucorigen = $_REQUEST[sucorigen]; 

  $Suc=$_REQUEST[Suc];
  
  $Stat=$_REQUEST[Stat];
	    
  $Ord=$_REQUEST[Ord];

  $Orden2=$_REQUEST[Orden2];
		    
  if (!isset($Stat)){
	    $Stat=='*';
  }else{
	    $Stat=$_REQUEST[Stat];
  }

  $Op=$_REQUEST[Op];

  $Folio=$_REQUEST[folio];

  $Msj="";

  $Fecha=date("Y-m-d");
  $Fechas=date("Y-m-d H:i:s");

  $Fech2	=	$_REQUEST[Fech2];
  if (!isset($Fech2)){
      $Fech2 = date("Y-m-d");
  }

  $Fech3	=	$_REQUEST[Fech3];

  if (!isset($Fech3)){
      $Fech3 = date("Y-m-d");
  }

  if ($Fech2>$Fech3){
	  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }
  
  if ($Stat=='*'){
	  $status=" ";
  }elseif($Stat=='TERMINADA'){
  	  $status="and otd.status='TERMINADA'";
  }elseif($Stat=='CANCELADA'){
  	  $status="and otd.status='CANCELADA'";
  }elseif($Stat=='PENDIENTE S/R'){
  	  $status="and otd.statustom=' '";
  }else{
	  $status="and otd.status<>'TERMINADA' and otd.status<>'CANCELADA'";
  }

  if ($Suc=='*'){
	  $Suc1=' ';
  }else{
	  $Suc1=" and ot.suc='$Suc'";
  }

  if ($Subdepto=='*'){
	  $Subdepto1=' ';
  }else{
	  $Subdepto1=" and est.subdepto='$Subdepto'";
  }

  $Hora = date("H:i:s");

  $SqlC="SELECT *
		FROM maqdet
		WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2'";

	$resC=mysql_query($SqlC,$link);

	$registro3=mysql_fetch_array($resC);

  if($Op=='1'){
  	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
		VALUES
		('$Orden2','$Estudio2','1','$Fecha','$Hora','$Usr')");
	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',mint='1',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}
  	//$busca=$_REQUEST[Orden2];
  }elseif($Op=='2'){
	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
		VALUES
		('$Orden2','$Estudio2','2','$Fecha','$Hora','$Usr')");
	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',mint='2',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}
	//$busca=$_REQUEST[Orden2];
  }elseif($Op=='3'){

  	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
		VALUES
		('$Orden2','$Estudio2','3','$Fecha','$Hora','$Usr')");
	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',mint='3',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}
	//$busca=$_REQUEST[Orden2];
  }elseif($Op=='4'){
  	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
		VALUES
		('$Orden2','$Estudio2','4','$Fecha','$Hora','$Usr')");
	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',mint='4',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}
	//$busca=$_REQUEST[Orden2];
  }elseif($Op=='Ext'){
  			//**************************** inventario
 	
		$SqlC="SELECT *
		FROM invldet
		WHERE invldet.orden='$Orden2' AND invldet.estudio='$Estudio2'";

		$resC=mysql_query($SqlC,$link);

		$registro4=mysql_fetch_array($resC);

  		if (empty($registro4)) {

				$cSql4="SELECT team FROM authuser WHERE authuser.uname='$Usr'";
				$result4=mysql_query($cSql4);
				$row4=mysql_fetch_array($result4);
				$row4s=$row4[team];

				if($row4s==1 or $row4s==0){

					$Almacen='invl.invmatriz';

				}elseif($row4s==2){

					$Almacen='invl.invhf';

				}elseif($row4s==3){

					$Almacen='invl.invtepex';

				}elseif($row4s==4){

					$Almacen='invl.invreyes';

				}

				//$cSql2="SELECT estd.estudio,estd.producto,estd.cantidad,invl.clave FROM estd,invl WHERE estd.estudio='$Estudio2' and estd.producto=invl.clave";
				$cSql2="SELECT estd.estudio,estd.producto,estd.idproducto,estd.cantidad,invl.clave FROM estd,invl WHERE estd.estudio='$Estudio2' and estd.idproducto=invl.id and estd.suc='$row4s'";

			    $result2=mysql_query($cSql2);

			    while ($row2=mysql_fetch_array($result2)){

			    	$id=$row2[idproducto];

			      $lUp = mysql_query("UPDATE invl SET $Almacen = $Almacen - $row2[cantidad], invl.existencia=invl.existencia - $row2[cantidad] WHERE invl.id='$row2[idproducto]' limit 1");

					  $Fecha2  = date("Y-m-d H:i");

				  $lUp2 = mysql_query("INSERT INTO invldet (fecha,idproducto,producto,estudio,cantidad,usr,suc,orden,sucorigen) 
					   VALUES
					   ('$Fecha2','$id','$row2[producto]','$Estudio2','$row2[cantidad]','$Usr','$row4s','$Orden2','$sucorigen')");
			    }
			    			//**************************** inventario
		}

  	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mext,fenvext,henvext,usrenvext)
		VALUES
		('$Orden2','$Estudio2','$_REQUEST[alias]','$Fecha','$Hora','$Usr')");

   	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',mext='$_REQUEST[alias]',fenvext='$Fecha',henvext='$Hora',usrenvext='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}

	//$busca=$_REQUEST[Orden2];
  }elseif($Op=='Recint'){
  	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,frecint,hrecint,usrrecint)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$Hora','$Usr')");
	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frecint='$Fecha',hrecint='$Hora',usrrecint='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}
	//$busca=$_REQUEST[Orden2];

  }elseif($Op=='Recext'){
  	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,frecext,hrecext,usrrecext)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$Hora','$Usr')");

	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frecext='$Fecha',hrecext='$Hora',usrrecext='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}
	//$busca=$_REQUEST[Orden2];

  }elseif($Op=='Rec'){
  	if (empty($registro3)) {
  	    //$lUp    = mysql_query("delete FROM maqdet WHERE orden='$Orden2' and estudio='$Estudio2'",$link);
		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,frec,hrec,usrrec)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$Hora','$Usr')");
	}else{
		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frec='$Fecha',hrec='$Hora',usrrec='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");
	}
	//$busca=$_REQUEST[Orden2];
  }

//$busca=$Orden2;

  $Titulo="Proceso de Estudios";

  if (!isset($Ord)){
  	$OrdenDef="ot.orden";            //Orden de la tabla por default
  }elseif($Ord=='ord'){
  	$OrdenDef="ot.orden";            //Orden de la tabla por default
  }elseif($Ord=='est'){
  	$OrdenDef="otd.estudio";            //Orden de la tabla por default
  }elseif($Ord=='pac'){
  	$OrdenDef="cli.nombrec";            //Orden de la tabla por default
  }

  $DepA=mysql_query("SELECT * FROM dep",$link);

  $SubA=mysql_query("SELECT departamento,subdepto FROM depd where departamento=$Depto",$link);

if($Folio<>''){
	$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
	otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
	otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno,otd.fechaest,otd.fr,otd.usrvalida,est.subdepto
	FROM ot,est,otd,cli
	WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Folio' and otd.statustom<>'' and otd.statustom<>'PENDIENTE'";
}else{
	if($busca<>''){
		  $Palabras  = str_word_count($busca);  //Dame el numero de palabras
		  if($Palabras > 1){
			 $P=str_word_count($busca,1);          //Metelas en un arreglo
			 for ($i = 0; $i < $Palabras; $i++) {
					if(!isset($BusInt)){$BusInt=" cli.nombrec like '%$P[$i]%' ";}else{$BusInt=$BusInt." and cli.nombrec like '%$P[$i]%' ";}
			 }
		  }else{
			 $BusInt=" cli.nombrec like '%$busca%' ";  
		  }

		  if( $busca < 'a'){
		
			$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
			otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
			otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,est.subdepto
			FROM ot,est,otd,cli
			WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' 
			$Suc1 $Subdepto1";
		  }else{
			$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
			otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
			otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,est.subdepto
			FROM ot,est,otd,cli
			WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca' and otd.statustom<>'' and otd.statustom<>'PENDIENTE'
			$Suc1 $Subdepto1 and $BusInt ";
		  }
	}else{
	  if($Depto<>"*"){
			if($Subdepto<>"*"){
					 if($Suc<>"*"){
						 $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
						otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
						otd.recibeencaja,est.subdepto,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,
						otd.usrvalida
						FROM ot,est,otd,cli
						WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
						AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and est.subdepto='$Subdepto' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' and 
						ot.suc='$Suc' $status";
	
					 }else{
						$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
						otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
						otd.recibeencaja,est.subdepto,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,
						otd.usrvalida
						FROM ot,est,otd,cli
						WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
						AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' and est.subdepto='$Subdepto' $status";
					 }
			}else{
				if($Suc<>"*"){
					$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
					otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
					otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,est.subdepto
					FROM ot,est,otd,cli
					WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
					AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' and ot.suc='$Suc' $status";
				}else{
					$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
					otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
					otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,est.subdepto
					FROM ot,est,otd,cli
					WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
					AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' $status";
				}
			}
	  }else{
		 if($Suc<>"*"){
				$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
				otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
				otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,est.subdepto
				FROM ot,est,otd,cli
				WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio 
				AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and ot.suc='$Suc' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' $status";
		 }else{
				$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
				otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.statustom,
				otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,est.subdepto
				FROM ot,est,otd,cli
				WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca'
				and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and otd.statustom<>'' and otd.statustom<>'PENDIENTE' $status";
		}
	  }
	}
}

  if($Depto=="*"){
	  $marcat='#F000000';
	  $marca1=' ';
	  $marca2=' ';
	  $marca3=' ';
	  $marca4=' ';
	  $marca6=' ';
  }else{
 	if($Depto=="1"){
	  $marca1='#F000000';
	  $marca2=' ';
	  $marca3=' ';
	  $marca4=' ';
	  $marca6=' ';
  	}else{
 	 	if($Depto=="2"){
	  $marca1=' ';
	  $marca2='#F000000';
	  $marca3=' ';
	  $marca4=' ';
	  $marca6=' ';
  		}else{
 	 		if($Depto=="3"){
	  $marca1=' ';
	  $marca2=' ';
	  $marca3='#F000000';
	  $marca4=' ';
	  $marca6=' ';
  			}else{
 	 			if($Depto=="4"){
	  $marca1=' ';
	  $marca2=' ';
	  $marca3=' ';
	  $marca4='#F000000';
	  $marca6=' ';
  				}else{
 	 				if($Depto=="6"){
	  $marca1=' ';
	  $marca2=' ';
	  $marca3=' ';
	  $marca4=' ';
	  $marca6='#F000000';
  					}else{
						$marca=' ';
					}
				}
			}
		}
	}
  }
	
  $Edit = array("Edit"," &nbsp; &nbsp; &nbsp; ","Inst","Orden","Paciente","Est","Descripcion","Fecha","Hora","Status","-","-","-","-","-","-","-","-","-");

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
<meta http-equiv='content-type' content='text/html; charset=utf-8'/>

<link href='lib/textos.css' rel='stylesheet' type='text/css'>
</head>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<script language="JavaScript1.2">
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=800,height=350,left=100,top=150")
}
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
    alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Win(url){
   window.open(url,"WinVen","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=650,height=400,left=50,top=150")
}
function Win2(url){
   window.open(url,"WinVen","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=350,height=400,left=50,top=150")
}

function cFocus(){
  document.form1.busca.focus();
}

</script>

<style type="text/css">
<!--
.Estilo10
{
font-family: "Arial", Times, serif;
font-size: 11px;
}
-->
</style>
<body bgcolor="#FFFFFF" onload='cFocus()'>

<?php headymenu($Titulo,1);

	echo "<hr noshade style='color:000099;height:1px'>";

	echo "<form name='form1' method='post' action='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";
	echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marcat><font size='1'>Todos los Deptos</b></a></td>";
	echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=1&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca1><font size='1'>Laboratorio</b></a></td>";
	echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=2&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca2><font size='1'>Rayos X y USG</b></a></td>";
	echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=3&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca3><font size='1'>Especiales</b></a></td>";
	echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=4&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca4><font size='1'>Servicios</b></a></td>";
	echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=6&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca6><font size='1'>Externos</b></a></td>";

	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

	echo "<form name='form' method='post' action='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'>";

	echo "&nbsp;<b> DE: $Fech2 </b><input type='hidden' readonly='readonly' name='Fech2' size='10' value ='$Fech2' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech2,'yyyy-mm-dd',this)>";
		
	echo "&nbsp;<b> A: $Fech3 </b><input type='hidden' readonly='readonly' name='Fech3' size='10' value ='$Fech3' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech3,'yyyy-mm-dd',this)>";

	echo "</form>";

	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

	echo " &nbsp; &nbsp;<b> Busca: </b>";
	echo "<input type='text' name='busca' size='30' maxlength='30'>";
	echo " &nbsp; <input type='submit' name='Submit' value='Ok'>";

	echo "$Gfont  &nbsp; &nbsp; <b><a class='pg' href=javascript:winuni2('pidedatosventana.php?cRep=27')><b>Envio Int</b></font></a>";

	echo "$Gfont  &nbsp; &nbsp; <b><a class='pg' href=javascript:winuni2('pidedatosventana.php?cRep=28')><b>Envio Ext</b></font></a>";

	echo "$Gfont  &nbsp; &nbsp; <b><a class='pg' href=javascript:winuni2('pidedatosventana.php?cRep=29')><b>Produc.</b></font></a>";

	echo "<hr noshade style='color:000099;height:1px'>";

	echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'><font color=$marcat><font size='1'>Todos Subdeptos</b></a></td>";

	while ($Subdepto=mysql_fetch_array($SubA)){
	  if ($Subdepto[subdepto]==$_REQUEST[Subdepto]){
	   		echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=$Subdepto[departamento]&Subdepto=$Subdepto[subdepto]&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font size='1' color='#F000000'>$Subdepto[subdepto]</font></b></a></td>";
	  }else{
			echo "<td>$Gfont <b> <a class='pg' href='proceso.php?Depto=$Subdepto[departamento]&Subdepto=$Subdepto[subdepto]&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font size='1'>$Subdepto[subdepto]</font></b></a></td>";
	  }
	}
	$Subdepto=$_REQUEST[Subdepto];	
	echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
	echo " &nbsp; &nbsp;$Gfont<b> Folio: </b>";
	echo "<input type='text' name='folio' size='6' maxlength='6'>";
	echo " &nbsp; <input type='submit' name='Submit' value='Enviar'>";
	echo "<td> <b> <a class='pg' href='proceso.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fecha&Fech3=$Fecha&Stat=*&Ord=ord'><font color='#4a87ae'><font size='1'>Limpia</b></a></font></td>";
	echo "<input type='hidden' name='pagina' value='1'>"; 

	echo "<hr noshade style='color:000099;height:1px'>";

	echo "</form></font>";
	echo "</div>";

   	if(!$res=mysql_query($SqlA." ORDER BY ".$OrdenDef,$link)){
        cMensaje("No se encontraron resultados � hay un error en el filtro");    #Manda mensaje de datos no existentes
 	}else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

		if($limitInf<0){$limitInf=0;}

        $sql=$SqlA.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

        $res=mysql_query($sql,$link);

		echo "<table align='center' width='99%' border='0' cellspacing='0' cellpadding='0'>";

		echo "<tr bgcolor='#b5d3e9'>";
		echo "<td align='center'>$Gfont<font size='1'><b>Suc</b></font></td>";
		echo "<td> </td>";
		echo "<td> </td>";
		echo "<td> </td>";
		echo "<td> </td>";
		echo "<td align='center' colspan='4'>$Gfont<font size='1'><b>Maquila Interna</b></font></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Maq. Externa</b></font></td>";
		echo "<td align='center' colspan='2'>$Gfont<font size='1'><b>Observ.</b></font></td>";
		echo "<td align='center' colspan='2'>$Gfont<font size='1'><b>Recibe</b></font></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Proc</b></font></td>";
		echo "</tr>";
		echo "<tr bgcolor='#b5d3e9'>";
		echo "<td align='center'>";
		$Suc=$_REQUEST[Suc];
		echo "<form name='form' method='post' action='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";

		$SucA=mysql_query("select id from cia",$link); 		  
		echo "<select size='1' name='Suc' class='Estilo10' onchange=this.form.submit()>";
		echo "<option value='*'>*</option>";
		while ($Suc=mysql_fetch_array($SucA)){
		    echo "<option value='$Suc[id]'>$Suc[id]</option>";
		}
		$Suc=$_REQUEST[Suc];
		echo "<option selected value='*'>$_REQUEST[Suc]</option>";

		echo "</select>";
		echo "</form>";
		echo"</td>";

		echo "<td align='center'>$Gfont <b><a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'>Inst-Ord</b></a></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Fech/Hra</b></td>";
		echo "<td align='center'>$Gfont <b><a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=pac'>Paciente</b></a></td>";
		echo "<td align='center'>$Gfont <b><a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=est'>Cve-Estudio</b></a></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>MTRZ</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>OHF</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>TPX</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>RYS</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Proveedor</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Env</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Rec</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Int</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Ext</b></td>";
		echo "<td align='center'>$Gfont<font size='1'><b>Est</b></td>";
		echo "</tr>";              
		  
		  
//        PonEncabezado();         #---------------------Encabezado del browse----------------------

	while($registro=mysql_fetch_array($res)){
	    $clnk=strtolower($registro[estudio]);
	  	if($registro[cuatro]<>'0000-00-00 00:00:00'){
			$imagen4="OKShield.png";
		}else{	
			$imagen4="ErrorCircle.png";
		}

		if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
		echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		$hora2=substr($registro[hora],0,5);
		echo "<td align='center'>$Gfont <font size='1' color='#FF0000'><b>$registro[suc]</b></font></td>";
		echo "<td align='right'>$Gfont<font size='1'> $registro[institucion]-<font size='1'><b>$registro[orden]</b></td>";
		echo "<td>$Gfont <font size='1'> $registro[fecha] <font size='1'><b> $hora2</b></td>";		  		  
		
        echo "<td>$Gfont <font size='1'> ".substr($registro[nombrec],0,33)."$Gfon</td>";
	  	if($registro[fr]==0){
			$colorfr='#6D6D6D';
		}else{
			$colorfr='#FF0000';
		}
		  
        echo "<td>$Gfont<font size='1' color='$colorfr'><b>$registro[estudio]</b></font>$Gfont<font size='1'>-".substr($registro[descripcion],0,27)." $Gfon</td>";

		$SqlB="SELECT *
		FROM maqdet
		WHERE maqdet.orden='$registro[orden]' AND maqdet.estudio='$registro[estudio]'";

		$resB=mysql_query($SqlB,$link);

		$registro2=mysql_fetch_array($resB);

		if($registro2[mint]=='1'){				
		   echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
		}else{
	   		echo "<td align='center'>$Gfont<a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=1&Ord=ord&pagina=$pagina&busca=$registro[orden]'><img src='lib/iconfalse.gif'></b></a></td>";
		}

 		if($registro2[mint]=='2'){				
		   echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
		}else{
	   		echo "<td align='center'>$Gfont<a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=2&Ord=ord&pagina=$pagina&busca=$registro[orden]'><img src='lib/iconfalse.gif'></b></a></td>";
		}

		if($registro2[mint]=='3'){				
		   echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
		}else{
	   		echo "<td align='center'>$Gfont<a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=3&Ord=ord&pagina=$pagina&busca=$registro[orden]'><img src='lib/iconfalse.gif'></b></a></td>";
		}

		if($registro2[mint]=='4'){				
		   echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
		}else{
	   		echo "<td align='center'>$Gfont<a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=4&Ord=ord&pagina=$pagina&busca=$registro[orden]'><img src='lib/iconfalse.gif'></b></a></td>";
		}

		echo "<td align='center'>";
		echo "<form name='form' method='post' action='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=Ext&Mext=$Mql[alias]&Ord=ord&pagina=$pagina&sucorigen=$registro[suc]&busca=$registro[orden]'>";
		$MqlA=mysql_query("select * from mql",$link); 		  
		echo "<select size='1' name='alias' class='Estilo10' onchange=this.form.submit()>";
		echo "<option value=' '>Quitar Registro</option>";
		while ($Mql=mysql_fetch_array($MqlA)){
		    echo "<option value='$Mql[id]'>$Mql[alias]</option>";
		}
		$MqlA2=mysql_query("select * from mql where mql.id=$registro2[mext]",$link);
		$Mql2=mysql_fetch_array($MqlA2);
		echo "<option selected value='$registro2[mext]'>$Mql2[alias]</option>";
		echo "</select>";
		echo "</form>";
		echo"</td>";
		if($registro2[obsenv]<>''){
   			echo "<td align='center'><a class='pg' href=javascript:winuni2('obsmqlenv.php?orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/messageon.png' border='0'></a></td>";
   		}else{
   			echo "<td align='center'><a class='pg' href=javascript:winuni2('obsmqlenv.php?orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/messageoff.png' border='0'></a></td>";   		}

   		if($registro2[obsrec]<>''){
   			echo "<td align='center'><a class='pg' href=javascript:winuni2('obsmqlrec.php?orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/messageon.png' border='0'></a></td>";
   		}else{
   			echo "<td align='center'><a class='pg' href=javascript:winuni2('obsmqlrec.php?orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/messageoff.png' border='0'></a></td>";
   		}


		if($registro2[usrrecint]<>''){				
		   	echo "<td align='center'>$Gfont $registro2[usrrecint]</td>";
		}elseif($registro2[mint]<>$registro[suc]){
	   		echo "<td align='center'><a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=Recint&Ord=ord&pagina=$pagina&busca=$registro[orden]'><img src='lib/iconfalse.gif'></b></a></td>";
		}else{
		   	echo "<td align='center'>$Gfont - </td>";
		} 


		if($registro2[usrrecext]<>''){				
		   	echo "<td align='center'>$Gfont $registro2[usrrecext]</td>";
		}elseif($registro2[usrenvext]<>''){
	   		echo "<td align='center'><a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=Recext&Ord=ord&pagina=$pagina&busca=$registro[orden]'><img src='lib/iconfalse.gif'></b></a></td>";
		}else{
		   	echo "<td align='center'>$Gfont - </td>";
		}     

		if($registro2[usrrec]<>''){				
		   	echo "<td align='center'>$Gfont $registro2[usrrec]</td>";
		}else{
	   		echo "<td align='center'><a class='pg' href='proceso.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&Op=Rec&Ord=ord&pagina=$pagina&busca=$registro[orden]'><img src='lib/iconfalse.gif'></b></a></td>";
		}        
   

		$nRng++;
	}//fin while
	echo "</table>";
	PonPaginacion5(false);      #-------------------pon los No.de paginas-------------------
}

?>
</body>
</html>
<?
mysql_close();
?>
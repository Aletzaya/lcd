<?php

  session_start();

 if(!isset($_REQUEST[Depto])){$Depto=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[Depto];$Depto=$_REQUEST[Depto];}

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Estudio=$_REQUEST[Estudio];

  $Estudio2=$_REQUEST[Estudio2];
  
  $Subdepto=$_REQUEST[Subdepto];

  $Suc=$_REQUEST[Suc];
  
  $Stat=$_REQUEST[Stat];
	    
  $Ord=$_REQUEST[Ord];

  $Orden2=$_REQUEST[Orden2];

  $statustom    = $_REQUEST[statustom];
		    
  if (!isset($Stat)){
	    $Stat=='*';
  }else{
	    $Stat=$_REQUEST[Stat];
  }

  $op=$_REQUEST[op];

  $Folio=$_REQUEST[folio];

  $Msj="";

  $Fecha=date("Y-m-d");
  $Fechas=date("Y-m-d H:i:s");
  $Fechaest  = date("Y-m-d H:i:s");

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

  $Hora = date("h:i:s");

  $Titulo="Estudios por departamento";

  if (!isset($Ord)){
  	$OrdenDef="ot.orden";            //Orden de la tabla por default
  }elseif($Ord=='ord'){
  	$OrdenDef="ot.orden";            //Orden de la tabla por default
  }elseif($Ord=='est'){
  	$OrdenDef="otd.estudio";            //Orden de la tabla por default
  }elseif($Ord=='pac'){
  	$OrdenDef="cli.nombrec";            //Orden de la tabla por default
  }


  if($op=='Rec'){
  	
  	$SqlC="SELECT *
		FROM maqdet
		WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2'";

	$resC=mysql_query($SqlC,$link);

	$registro3=mysql_fetch_array($resC);


  	if (empty($registro3)) {

		$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,frec,hrec,usrrec)
		VALUES
		('$Orden2','$Estudio2','$Fecha','$Hora','$Usr')");

	}else{

		$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Estudio2',frec='$Fecha',hrec='$Hora',usrrec='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Estudio2' limit 1");

	}
  }elseif($op=='1'){
	  if($_REQUEST[regis]=='1'){
			$Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$statustom', status='$statustom'
					  WHERE orden='$Orden2' AND estudio='$Estudio'");

			 $OtdA  = mysql_query("SELECT dos,lugar,estudio FROM otd WHERE orden='$Orden2' AND estudio='$Estudio'");
			 
			  while ($Otd  = mysql_fetch_array($OtdA)){	   
				 $Est  = $Otd[estudio];  
				  if(substr($Otd[dos],0,4)=='0000'){     
						if($Otd[lugar] <= '3'){	         
					  		$lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
							 WHERE orden='$Orden2' and estudio='$Estudio' limit 1");                     
					   }else{           
						  $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
							 WHERE orden='$Orden2' AND estudio='$Estudio' limit 1");           
						}
				 	}

				 	$SqlC="SELECT *
	 				FROM maqdet
					WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Est'";

					$resC=mysql_query($SqlC,$link);

					$registro4=mysql_fetch_array($resC);

					if (empty($registro4)) {

						$lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
						VALUES
						('$Orden2','$Est','$_REQUEST[Suc]','$Fecha','$Hora','$Usr')");
					}else{
						$lUp    = mysql_query("UPDATE maqdet SET orden='$Orden2',estudio='$Est',mint='$_REQUEST[Suc]',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden2' AND maqdet.estudio='$Est' limit 1");
					}  	
			 	}

  	  }else{
       		$Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$statustom', status='$statustom'
	          WHERE orden='$Orden2' AND estudio='$Estudio'");
	  }
	  
	   $NumA1  = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$Orden2' AND otd.statustom='PENDIENTE'");
	   
	   $NumA2  = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$Orden2' AND otd.statustom=' '");

	 	if(mysql_num_rows($NumA1)>=1){
			  $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$Orden2'");
		}else{ 
			 if(mysql_num_rows($NumA2)==0){
				$lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$Orden2'");
			 }else{ 
			  	$lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$Orden2'");
 			 } 
		 } 

  }

  $DepA=mysql_query("SELECT * FROM dep",$link);

  $SubA=mysql_query("SELECT departamento,subdepto FROM depd where departamento=$Depto",$link);

//  if(strlen($Depto)==0){$Depto='999';}
if($Folio<>''){
	$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno,otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
	FROM ot,est,otd,cli
	WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Folio'";
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
		
			$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
			FROM ot,est,otd,cli
			WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca' 
			$Suc1 $Subdepto1";
		  }else{
			$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
			FROM ot,est,otd,cli
			WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca' 
			$Suc1 $Subdepto1 and $BusInt ";
		  }
	}else{
	  if($Depto<>"*"){
			if($Subdepto<>"*"){
					 if($Suc<>"*"){
						 $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.subdepto,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
						FROM ot,est,otd,cli
						WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
						AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and est.subdepto='$Subdepto' and 
						ot.suc='$Suc' $status";
	
					 }else{
						$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.subdepto,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
						FROM ot,est,otd,cli
						WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
						AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and est.subdepto='$Subdepto' $status";
					 }
			}else{
				if($Suc<>"*"){
					$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
					FROM ot,est,otd,cli
					WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
					AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and ot.suc='$Suc' $status";
				}else{
					$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
					FROM ot,est,otd,cli
					WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
					AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' $status";
				}
			}
	  }else{
		 if($Suc<>"*"){
				$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
				FROM ot,est,otd,cli
				WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio 
				AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and ot.suc='$Suc' $status";
		 }else{
				$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf,otd.statustom,otd.usrest
				FROM ot,est,otd,cli
				WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca'
				and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' $status";
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

   echo "<form name='form1' method='post' action='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marcat><font size='1'>Todos los Deptos</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=1&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca1><font size='1'>Laboratorio</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=2&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca2><font size='1'>Rayos X y USG</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=3&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca3><font size='1'>Especiales</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=4&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca4><font size='1'>Servicios</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=6&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca6><font size='1'>Externos</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=9&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca6><font size='1'>Laboratorio Biologia Molecular</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=10&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca6><font size='1'>Estudios De Gabinete</b></a></td>";

   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

	echo "<form name='form' method='post' action='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'>";

	echo "&nbsp;<b> DE: $Fech2 </b><input type='hidden' readonly='readonly' name='Fech2' size='10' value ='$Fech2' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech2,'yyyy-mm-dd',this)>";
		
	echo "&nbsp;<b> A: $Fech3 </b><input type='hidden' readonly='readonly' name='Fech3' size='10' value ='$Fech3' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech3,'yyyy-mm-dd',this)>";

	echo "</form>";

   echo "<hr noshade style='color:000099;height:1px'>";
   
   echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'><font color=$marcat><font size='1'>Todos Subdeptos</b></a></td>";
      
	  while ($Subdepto=mysql_fetch_array($SubA)){
		  if ($Subdepto[subdepto]==$_REQUEST[Subdepto]){
  	   		echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=$Subdepto[departamento]&Subdepto=$Subdepto[subdepto]&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font size='1' color='#F000000'>$Subdepto[subdepto]</font></b></a></td>";
		  }else{
	   		echo "<td>$Gfont <b> <a class='pg' href='estdepto.php?Depto=$Subdepto[departamento]&Subdepto=$Subdepto[subdepto]&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font size='1'>$Subdepto[subdepto]</font></b></a></td>";
		  }
	  }
			$Subdepto=$_REQUEST[Subdepto];
			

   echo "<hr noshade style='color:000099;height:1px'>";

   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

   echo " &nbsp; &nbsp;<b> Busca: </b>";
   echo "<input type='text' name='busca' size='30' maxlength='30' placeholder='Folio o Nombre'>";
   echo " &nbsp; <input type='submit' name='Submit' value='Ok'>";

   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
   echo " &nbsp; &nbsp;$Gfont<b> Folio: </b>";
   echo "<input type='text' name='folio' size='6' maxlength='6'>";
   echo " &nbsp; <input type='submit' name='Submit' value='Enviar'>";
   echo "<td> <b> <a class='pg' href='estdepto.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fecha&Fech3=$Fecha&Stat=*&Ord=ord'><font color='#4a87ae'><font size='1'>Limpia</b></a></font></td>";
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
	//echo $sql;
        $res=mysql_query($sql,$link);

          echo "<table align='center' width='100%' border='0' cellspacing='0' cellpadding='0'>";

          echo "<tr height='10' bgcolor='#b5d3e9'>";
		  echo "<td align='center'>$Gfont<font size='1'>";
		  $Suc=$_REQUEST[Suc];
    	  echo "<form name='form' method='post' action='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";
          
		  $SucA=mysql_query("select id from cia",$link); 		  
          echo "<select size='1' name='Suc' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>*</option>";
          while ($Suc=mysql_fetch_array($SucA)){
                echo "<option value='$Suc[id]'>$Suc[id]</option>";
          }
          $Suc=$_REQUEST[Suc];
		  echo "<option selected value='*'>$_REQUEST[Suc]</option></p>";
		  
          echo "</select></p>";
		  

		  echo"</b></td>";
		  echo "</form>";
          //echo "<td align='center'>$Gfont<font size='1'><b>Suc</b></td>";
          //echo "<td align='center'>$Gfont<font size='1'><b>Inst-Ord</b></td>";

          echo "<td align='center'>$Gfont <b><a class='pg' href='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'>Inst-Ord</b></a></td>";

          echo "<td align='center'>$Gfont<font size='1'><b>Fech/Hra</b></td>";
		  echo "<td align='center'>$Gfont<font size='1'><b>Tom/Real</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Cap</b></td>";
          //echo "<td align='center'>$Gfont<font size='1'>-</td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Img</b></td>";
        //  echo "<td align='center'>$Gfont<font size='1'><b>DCM</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>MQL</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Imp</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Proc</b></td>";
          echo "<td align='center'>$Gfont <b><a class='pg' href='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=pac'>Paciente</b></a></td>";

          //echo "<td align='center'>$Gfont<font size='1'><b>Cve-Estudio</b></td>";
		  
          echo "<td align='center'>$Gfont <b><a class='pg' href='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=est'>Cve-Estudio</b></a></td>";

          echo "<td align='center'>$Gfont<font size='1'><b>Email</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Etiq</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>OGral</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>OEst</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Ent.Proc</b></td>";
		  //echo "<td align='center'>$Gfont<font size='1'><b>Env/Ent</b></td>";
		  
		  echo "<td align='center'>$Gfont<font size='1'><b>";
    	  echo "<form name='form' method='post' action='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";
		  echo "<select size='1' name='Stat' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>* Todos</option>";
          echo "<option value='TERMINADA'>Terminada</option>";
          echo "<option value='PENDIENTE'>Pendiente</option>";
          echo "<option value='PENDIENTE S/R'>Pendiente S/R</option>";
          echo "<option value='CANCELADA'>Cancelada</option>";
          $Stat=$_REQUEST[Stat];
		  echo "<option selected value='*'>$_REQUEST[Stat]</option></p>";
		  
          echo "</select></p>";
		  echo"</b></td>";
		  echo "</form>";
		  
		  echo "<td align='center'>$Gfont<font size='1'><b>Ent.Recep</b></td>";
		//  echo "<td align='center'>$Gfont<font size='1'><b>Cancel</b></td>";

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
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo'; height='20'>";
			$hora2=substr($registro[hora],0,5);
		  echo "<td align='center'>$Gfont <font size='1' color='#FF0000'><b>$registro[suc]</b></font></td>";
          echo "<td align='right'>$Gfont<font size='1'> $registro[institucion]-<font size='1'><b>$registro[orden]</b></td>";
		  echo "<td>$Gfont <font size='1'> $registro[fecha] <font size='1'><b> $hora2</b></td>";

//----------  T O M A   /   R E A L I Z A C I O N     ---------------------

			if($registro[statustom]=='TOMA/REALIZ'){
				if($registro[usrest]<>''){				
               		echo "<td align='center'>$Gfont <font size='1'>$registro[usrest]</font></td>";
               	}else{
               		echo "<td align='center'>$Gfont<a class='pg' href='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio=$registro[estudio]&regis=1&statustom=TOMA/REALIZ&Suc=$registro[suc]&op=1&pagina=$pagina&Ord=ord'><img src='lib/iconfalse.gif'></b></a></td>";
               	}
            }else{
				if($registro[statustom]==''){
               		echo "<td align='center'>$Gfont<a class='pg' href='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio=$registro[estudio]&regis=1&statustom=TOMA/REALIZ&Suc=$registro[suc]&op=1&pagina=$pagina&Ord=ord'><img src='lib/iconfalse.gif'></b></a></td>";

				}elseif($registro[statustom]=='PENDIENTE'){
               		echo "<td align='center'>$Gfont<a class='pg' href='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio=$registro[estudio]&regis=1&statustom=TOMA/REALIZ&Suc=$registro[suc]&op=1&pagina=$pagina&Ord=ord'>$registro[statustom]</b></a></td>";
				}else{
               		echo "<td align='center'>$Gfont <font size='1'>R->$registro[usrest]</font></td>";
				}
            }   

//----------  C A P T U R A   ---------------------

		  if($registro[depto] <> 2 ){

		  	echo "<td align='center'><a href=javascript:Ventana('capturaresdiagvalidapdf.php?busca=$registro[orden]&estudio=$registro[estudio]&alterno=$registro[alterno]')><img src='images/editapdf2.png' alt='Edita reg' border='0'></a></td>";

          }else{
		 	 echo "<td align='center'><a href=javascript:wingral('capturaresword.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/edit.png' alt='Edita reg' border='0'></a></td>";
		  }

//----------  S U B I R   A R C H I V O    ---------------------

		  $ImgA = mysql_query("SELECT archivo FROM estudiospdf WHERE id='$registro[orden]' and usrelim=''");

		  $Img=mysql_fetch_array($ImgA);

		  if($Img[archivo]<>''){
			  echo "<td align='center'><a href=javascript:wingral('displayestudioslcdimg.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/desplegar.png'  width='18'></a></td>";

		  }else{
			  echo "<td align='center'><a href=javascript:wingral('displayestudioslcdimg.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/subir.jpg' alt='Up_Img' border='0' width='15'></a></td>";

		  }

//----------  S U B I R   A R C H I V O   D I C O M  ---------------------
/*
		  $ImgD = mysql_query("SELECT archivo FROM estudiosdicom WHERE id='$registro[orden]' and usrelim=''");

		  $Imgdicom = mysql_fetch_array($ImgD);

		  if($Imgdicom[archivo]<>''){

			  echo "<td align='center'><a href=javascript:wingral('displayestudiosdicom.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/dicom.jpg'  width='18'></a></td>";

		  }else{
			  echo "<td align='center'><a href=javascript:wingral('displayestudiosdicom.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/dicom.jpg' alt='Up_Img' border='0' width='15'></a></td>";

		  }
*/

//----------  M A Q U I L A    E X T E R N A   ---------------------

		$SqlB="SELECT *
		FROM maqdet
		WHERE maqdet.orden='$registro[orden]' AND maqdet.estudio='$registro[estudio]'";

		$resB=mysql_query($SqlB,$link);

		$registro2=mysql_fetch_array($resB);

		if($registro2[mext]<>''){
				$SqlB2="SELECT *
				FROM mql
				WHERE mql.id='$registro2[mext]'";

				$resB2=mysql_query($SqlB2,$link);

				$registro2A=mysql_fetch_array($resB2);	

		   	echo "<td align='center'><font size='1'><small><a class='pg' href=javascript:winuni2('ordenesdxestmql.php?Orden=$registro[orden]&Estudio=$registro[estudio]')> $registro2A[alias]</a></small></font></td>";
		}else{
	   		echo "<td align='center'> </td>";
		}       		

//----------  I M P R E S I O N   ---------------------

		if($registro[capturo]<>'' and $registro[fr]=='0'){
          
		  if($registro[depto] <> 2 ){
		  			if($registro[creapdf]=='pdf'){

						echo "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$registro[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im&alterno=$registro[alterno]')><font size='1'>PDF</font></a></td>";
			 		}else{

						echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$registro[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$registro[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
					}
		  }else{		//Radiologia
                 //echo "<td align='center'><a class='pg' href='pdfradiologia.php?busca=$He[orden]&Estudio=$registro[estudio]'><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
		  		 echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a></td> ";
		  }
		}else{
			if($registro[capturo]<>'' and $registro[usrvalida]<>''){
			  if($registro[depto] <> 2 ){
				    if($registro[creapdf]=='pdf'){

						echo "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$registro[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im&alterno=$registro[alterno]')><font size='1'>PDF</font></a></td>";
			 		}else{

						echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$registro[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$registro[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
					}
			  }else{		//Radiologia
					 //echo "<td align='center'><a class='pg' href='pdfradiologia.php?busca=$He[orden]&Estudio=$registro[estudio]'><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
					 echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a></td> ";
			  }
			}else{
				echo "<td align='center'>-</td>";
			}
		}


// --------------- P R O C E S O  ----------

		if($registro2[usrrec]<>''){				
		   	echo "<td align='center'>$Gfont $registro2[usrrec]</td>";
		}else{
	   		echo "<td align='center'><a class='pg' href='estdepto.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Orden2=$registro[orden]&Estudio2=$registro[estudio]&op=Rec&pagina=$pagina&Ord=ord'><img src='lib/iconfalse.gif'></b></a></td>";
		}       		

// --------------- N O M B R E     D E  L     P A  C I E N T E ----------
		
          echo "<td>$Gfont <font size='1'> ".substr($registro[nombrec],0,33)."$Gfon</td>";
	  	if($registro[fr]==0){
			$colorfr='#6D6D6D';
		}else{
			$colorfr='#FF0000';
		}
		  
          echo "<td>$Gfont<font size='1' color='$colorfr'><b>$registro[estudio]</b></font>$Gfont<font size='1'>-".substr($registro[descripcion],0,27)." $Gfon</td>";
		 
		if($registro[entemailpac]=='1' or $registro[entemailmed]=='1' or $registro[entemailinst]=='1'){ 
			echo "<td align='center'>&nbsp;</a></td>";
			//echo "<td align='center'><a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$registro[orden]')><img src='lib/email.png' border='0'></a></td>";   
		}else{
			echo "<td align='center'>&nbsp;</a></td>";
		}

		 
          echo "<td align='center'><a href=javascript:Ventana('impeti.php?op=1&busca=$registro[1]&Est=$registro[estudio]')><img src='lib/print.png' alt='Edita reg' border='0'></a></td>";
          if($registro[observaciones]<>''){
			echo "<td align='center'><a href=javascript:Ventana('ordenesdxest.php?busca=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/edit.png' alt='Edita reg' border='0'>";          				
          }else{
			echo "<td align='center'>&nbsp;</td>";          				
          } 
		    
          if($registro[obsest]<>''){
			echo "<td align='center'><a class='pg' href=javascript:winuni2('obsest.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/messageon.png' border='0'></a></td>";          				
          }else{
			echo "<td align='center'>&nbsp;</td>";          				
          }   

		if($registro[fechaest]<>'0000-00-00 00:00:00'){

			$fechatn=substr($registro[fechaest],0,10);		
			$horatn=substr($registro[fechaest],11);
			//$horatn2=$horatn-$rg[hora];
			
			$horafin=$horatn;
			$horaini=$registro[hora];
			echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>$Gfont7 ".RestarHoras($horaini,$horatn); "</font></td>";
		}else{
			$fechatn=substr($Fechas,0,10);		
			$horatn=substr($Fechas,11);
			
			$horafin=$horatn;
			$horaini=$registro[hora];

			echo "<td><font size='1' face='Arial, Helvetica, sans-serif' color='#e03030'><b>S/R</b></font><font size='1' face='Arial, Helvetica, sans-serif' color='#e03030'> $Gfont7 ".RestarHoras($horaini,$horatn); "</font></td>";
		}

		  //echo "<td>$Gfont <font size='1'>".substr($registro[3],0,5)."</td>";
		  if($registro[status]<>'TERMINADA' and $registro[status]<>'CANCELADA'){ 	
	  		$Gfont5="<font color='#e20909' size='1' face='Arial, Helvetica, sans-serif'>";
		  }else{
	  		$Gfont5="<font color='#17202a' size='1' face='Arial, Helvetica, sans-serif'>";
		  }
		  echo "<td>$Gfont5 <a class='pg' href=javascript:winuni2('termina.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><font size='1'><b>$registro[status]</b></font></td>";
          echo "<td>$Gfont <font size='1'>$registro[recibeencaja]</td>";
	/*	  if($registro[status] <> 'CANCELADA'){
		 	 echo "<td align='center'><a class='pg' href=javascript:winuni2('cancest.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/slc.png' alt='Edita reg' border='0'> </td>";
		 	 //echo "<td align='center'><a href=javascript:Ventana('capturaresdiag.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
          }else{
		 	 echo "<td align='center'><a class='pg' href=javascript:winuni2('cancest.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><img src='images/ErrorCircle.png' alt='Edita reg' border='0'> </td>";
		  } */
		  
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

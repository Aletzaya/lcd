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
  
  $Subdepto=$_REQUEST[Subdepto];

  $Suc=$_REQUEST[Suc];
  
  $Stat=$_REQUEST[Stat];
	    
  $Ord=$_REQUEST[Ord];
		    
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

  $DepA=mysql_query("SELECT * FROM dep",$link);

  $SubA=mysql_query("SELECT departamento,subdepto FROM depd where departamento=$Depto",$link);

//  if(strlen($Depto)==0){$Depto='999';}
if($Folio<>''){
	$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
	otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
	otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida
	FROM ot,est,otd,cli
	WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Folio' and otd.fr='1'";
}else{
	if($busca<>''){
		$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
		otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
		otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida
		FROM ot,est,otd,cli
		WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca' and otd.fr='1'
		$Suc1 $Subdepto1";
	}else{
	  if($Depto<>"*"){
			if($Subdepto<>"*"){
					 if($Suc<>"*"){
						 $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
						otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
						otd.recibeencaja,est.subdepto,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,
						otd.usrvalida
						FROM ot,est,otd,cli
						WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
						AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and est.subdepto='$Subdepto' and 
						ot.suc='$Suc' and otd.fr='1' $status";
	
					 }else{
						$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
						otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
						otd.recibeencaja,est.subdepto,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,
						otd.usrvalida
						FROM ot,est,otd,cli
						WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
						AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and est.subdepto='$Subdepto' and otd.fr='1' $status";
					 }
			}else{
				if($Suc<>"*"){
					$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
					otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
					otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida
					FROM ot,est,otd,cli
					WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
					AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and ot.suc='$Suc' and otd.fr='1' $status";
				}else{
					$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
					otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
					otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida
					FROM ot,est,otd,cli
					WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND est.depto='$Depto' 
					AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and otd.fr='1' $status";
				}
			}
	  }else{
		 if($Suc<>"*"){
				$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
				otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
				otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida
				FROM ot,est,otd,cli
				WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio 
				AND otd.orden>='$busca' and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and ot.suc='$Suc' and otd.fr='1' $status";
		 }else{
				$SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
				otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
				otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida
				FROM ot,est,otd,cli
				WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden>='$busca'
				and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' and otd.fr='1' $status";
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

   echo "<form name='form1' method='post' action='estdeptovalida.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marcat><font size='1'>Todos los Deptos</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=1&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca1><font size='1'>Laboratorio</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=2&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca2><font size='1'>Rayos X y USG</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=3&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca3><font size='1'>Especiales</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=4&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca4><font size='1'>Servicios</b></a></td>";
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=6&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font color=$marca6><font size='1'>Externos</b></a></td>";

   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

	echo "<form name='form' method='post' action='estdeptovalida.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'>";

	echo "&nbsp;<b> DE: $Fech2 </b><input type='hidden' readonly='readonly' name='Fech2' size='10' value ='$Fech2' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech2,'yyyy-mm-dd',this)>";
		
	echo "&nbsp;<b> A: $Fech3 </b><input type='hidden' readonly='readonly' name='Fech3' size='10' value ='$Fech3' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech3,'yyyy-mm-dd',this)>";

	echo "</form>";
	
   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

   echo " &nbsp; &nbsp;<b> Busca: </b>";
   echo "<input type='text' name='busca' size='6' maxlength='6'>";
   echo " &nbsp; <input type='submit' name='Submit' value='Ok'>";

   echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
   echo " &nbsp; &nbsp;<b> Folio: </b>";
   echo "<input type='text' name='folio' size='6' maxlength='6'>";
   echo " &nbsp; <input type='submit' name='Submit' value='Enviar'>";
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fecha&Fech3=$Fecha&Stat=*&Ord=ord'><font color='#4a87ae'><font size='1'>Limpia</b></a></font></td>";
   echo "<input type='hidden' name='pagina' value='1'>"; 

   echo "<hr noshade style='color:000099;height:1px'>";
   
   echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=*&Subdepto=*&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'><font color=$marcat><font size='1'>Todos Subdeptos</b></a></td>";
      
	  while ($Subdepto=mysql_fetch_array($SubA)){
		  if ($Subdepto[subdepto]==$_REQUEST[Subdepto]){
  	   		echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=$Subdepto[departamento]&Subdepto=$Subdepto[subdepto]&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font size='1' color='#F000000'>$Subdepto[subdepto]</font></b></a></td>";
		  }else{
	   		echo "<td>$Gfont <b> <a class='pg' href='estdeptovalida.php?Depto=$Subdepto[departamento]&Subdepto=$Subdepto[subdepto]&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'><font size='1'>$Subdepto[subdepto]</font></b></a></td>";
		  }
	  }
			$Subdepto=$_REQUEST[Subdepto];
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

          echo "<tr height='10' bgcolor='#b5d3e9'>";
		  echo "<td align='center'>$Gfont<font size='1'>";
		  $Suc=$_REQUEST[Suc];
    	  echo "<form name='form' method='post' action='estdeptovalida.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";
          
		  $SucA=mysql_query("select id from cia",$link); 		  
          echo "<select size='1' name='Suc' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Suc*</option>";
          while ($Suc=mysql_fetch_array($SucA)){
                echo "<option value='$Suc[id]'>Suc $Suc[id]</option>";
          }
          $Suc=$_REQUEST[Suc];
		  echo "<option selected value='*'>Suc $_REQUEST[Suc]</option></p>";
		  
          echo "</select></p>";
		  

		  echo"</b></td>";
		  echo "</form>";
          //echo "<td align='center'>$Gfont<font size='1'><b>Suc</b></td>";
          //echo "<td align='center'>$Gfont<font size='1'><b>Inst-Ord</b></td>";

          echo "<td align='center'>$Gfont <b><a class='pg' href='estdeptovalida.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=ord'>Inst-Ord</b></a></td>";

          echo "<td align='center'>$Gfont<font size='1'><b>Fech/Hra</b></td>";
		  echo "<td align='center'>$Gfont<font size='1'><b>Capt</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Imp</b></td>";
          //echo "<td align='center'>$Gfont<font size='1'><b>Paciente</b></td>";
          echo "<td align='center'>$Gfont <b><a class='pg' href='estdeptovalida.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=pac'>Paciente</b></a></td>";

          //echo "<td align='center'>$Gfont<font size='1'><b>Cve-Estudio</b></td>";
		  
          echo "<td align='center'>$Gfont <b><a class='pg' href='estdeptovalida.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=est'>Cve-Estudio</b></a></td>";

          echo "<td align='center'>$Gfont<font size='1'><b>Etiq</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>T/R/M</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Ent.Proc</b></td>";
		  //echo "<td align='center'>$Gfont<font size='1'><b>Env/Ent</b></td>";
		  
		  echo "<td align='center'>$Gfont<font size='1'><b>";
    	  echo "<form name='form' method='post' action='estdeptovalida.php?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&Fech2=$Fech2&Fech3=$Fech3&Stat=$Stat&Ord=$Ord'>";
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
		  echo "<td align='center'>$Gfont<font size='1'><b>Cancel</b></td>";

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
          echo "<td align='right'>$Gfont<font size='1'> $registro[institucion]-<font size='2'><b>$registro[orden]</b></td>";
		  echo "<td>$Gfont <font size='1'> $registro[fecha] <font size='1'><b> $hora2</b></td>";		  		  
		  if($registro[depto] <> 2 ){
		 	 echo "<td align='center'><a href=javascript:Ventana('capturaresdiagvalida.php?busca=$registro[orden]&estudio=$registro[estudio]&alterno=$registro[alterno]')><img src='images/validacion.png' alt='Edita reg' border='0'> </td>";
		 	 //echo "<td align='center'><a href=javascript:Ventana('capturaresdiag.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
          }else{
		 	 echo "<td align='center'><a href=javascript:wingral('capturaresword.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
		  }

	  	if($registro[usrvalida]<>''){
          
		  if($registro[depto] <> 2 ){
			  if($registro[alterno] == 1){
				echo "<td align='center'><a href=javascript:Ventana('estdeptoimpalt.php?clnk=$clnk&Orden=$registro[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
			  }else{
				if($registro[alterno] == 2){
					echo "<td align='center'><a href=javascript:Ventana('estdeptoimpalt2.php?clnk=$clnk&Orden=$registro[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
				}else{
					echo "<td align='center'><a href=javascript:Ventana('estdeptoimp.php?clnk=$clnk&Orden=$registro[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
				}
			  }
		  }else{		//Radiologia
                 //echo "<td align='center'><a class='pg' href='pdfradiologia.php?busca=$He[orden]&Estudio=$registro[estudio]'><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
		  		 echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a></td> ";
		  }
		}else{
			echo "<td align='center'>-</td>";
		}
          echo "<td>$Gfont <font size='2'> ".substr($registro[nombrec],0,33)."$Gfon</td>";
	  	if($registro[fr]==0){
			$colorfr='#6D6D6D';
		}else{
			$colorfr='#FF0000';
		}
		  
          echo "<td>$Gfont<font size='1' color='$colorfr'><b>$registro[estudio]</b></font>$Gfont<font size='1'>-".substr($registro[descripcion],0,27)." $Gfon</td>";
          echo "<td align='center'><a href=javascript:Ventana('impeti.php?op=1&busca=$registro[1]&Est=$registro[estudio]')><img src='lib/print.png' alt='Edita reg' border='0'></a></td>";
          if($registro[observaciones]<>''){
			echo "<td align='center'><a href=javascript:Ventana('ordenesdxest.php?busca=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/edit.png' alt='Edita reg' border='0'>&nbsp;";          				
          }else{
			echo "<td align='center'><a href=javascript:Ventana('ordenesdxest.php?busca=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/editoff.png' alt='Edita reg' border='0'>&nbsp;";          				
          } 
		    
          if($registro[obsest]<>''){
			echo "<a class='pg' href=javascript:winuni2('obsest.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/messageon.png' border='0'></a></td>";          				
          }else{
			echo "<a class='pg' href=javascript:winuni2('obsest.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/messageoff.png' border='0'></a></td>";          				
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
		  echo "<td>$Gfont5 <font size='1'><a class='pg' href=javascript:winuni2('termina.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><b>$registro[status]</b></font></td>";
          echo "<td>$Gfont <font size='1'>$registro[recibeencaja]</td>";
		  if($registro[status] <> 'CANCELADA'){
		 	 echo "<td align='center'><a class='pg' href=javascript:winuni2('cancest.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><img src='lib/slc.png' alt='Edita reg' border='0'> </td>";
		 	 //echo "<td align='center'><a href=javascript:Ventana('capturaresdiag.php?busca=$registro[orden]&estudio=$registro[estudio]')><img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
          }else{
		 	 echo "<td align='center'><a class='pg' href=javascript:winuni2('cancest.php?Orden=$registro[orden]&Estudio=$registro[estudio]')><img src='images/ErrorCircle.png' alt='Edita reg' border='0'> </td>";
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
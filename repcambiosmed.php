<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $FechaI	=	$_REQUEST[FechaI];

  if (!isset($FechaI)){
      $FechaI = date("Y-m-d");
  }

  $FechaF	=	$_REQUEST[FechaF];

  if (!isset($FechaF)){
      $FechaF = date("Y-m-d");
  }

  if ($FechaI>$FechaF){
	  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }
  

  if (!isset($_REQUEST[filtro3])){
      $filtro3 = '*';
  }else{
	  $filtro3    = $_REQUEST[filtro3];       
  }
  
  if (!isset($_REQUEST[filtro7])){
      $filtro7 = '';
  }else{
      $filtro7    = $_REQUEST[filtro7];       
  }

 if($filtro3<>'*'){
 	$filtro4="and logcambios.suc='$filtro3'";
 }else{
	$filtro4=" ";
 }

 
 if($filtro7<>''){
    $filtro8="and (logcambios.orden like '%$filtro7%' or logcambios.usr like '%$filtro7%')";
 }else{
    $filtro8="";
 }

 if($filtro3=='*'){
	 $Sucursal='sucursalt';
 }elseif($filtro3==0){
 	 $Sucursal='sucursal0';
 }elseif($filtro3==1){
 	 $Sucursal='sucursal1';
 }elseif($filtro3==2){
 	 $Sucursal='sucursal2';
 }elseif($filtro3==3){
 	 $Sucursal='sucursal3';
 }elseif($filtro3==4){
 	 $Sucursal='sucursal4';
 }

   $Titulo = "Reporte de Cambios de Clave - Medico del $FechaI al $FechaF";

	$cOtA="(SELECT * from logcambios where date_format(fecha,'%Y-%m-%d') Between '$FechaI' And '$FechaF' $filtro4 $filtro8
	order by logcambios.id)";	

  $OtA  = mysql_query($cOtA,$link);

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>


<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

    <?php
    headymenu($Titulo, 0);

echo "<table align='center' width='100%' border='0'>";

	echo "<form name='form' method='post' action='repcambiosmed.php?busca=$busca&filtro3=$filtro3&filtro7=$filtro7&FechaI=$FechaI&FechaF=$FechaF'>";
	
	echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
		
	echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
	

	echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
	echo "<select size='1' name='filtro3' class='Estilo10'>";
	echo "<option value='*'>Todos*</option>";
	$SucA=mysql_query("SELECT id,alias FROM cia order by id");
	while($Suc=mysql_fetch_array($SucA)){
		echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
		if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
	}
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";		  
	echo "</select>";
	echo"</b></td>";

	echo "<td align='left'>$Gfont<b><font size='1'>Buscar: </b></font>";
	echo "<input type='text' name='filtro7' size='30' maxlength='30' placeholder='Orden o Usuario' value='$filtro7'>";
	echo"</td>";

	echo "<td align='left'>";
	echo "&nbsp; <input type='SUBMIT' value='Ok'>";
	echo"</b></font></td>";
	echo "</form>";
    echo "<td>";

	
    echo "<form name='form2' method='get' action='menu.php'>";
    echo "&nbsp; <input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
    echo "</td></tr></table>";

    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

    echo "<table width='100%' align='center' border='0'>";

    echo "<tr bgcolor='#a2b2de'><td align='center'>$Gfon Id </td><td align='center'>$Gfon Fecha</td><td align='center'>$Gfon Suc</td><td align='center'>$Gfon Orden</td><td align='center'>$Gfon Concepto</td><td align='center'>$Gfon Usuario</td></tr>";

    while ($reg = mysql_fetch_array($OtA)) {

		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td align='center'>$Gfon $reg[id] </td><td align='center'>$Gfon $reg[fecha]</td><td align='center'>$Gfon $reg[suc]</td><td align='center'>$Gfon $reg[orden]</td><td align='left'>$Gfon $reg[concepto]</td><td align='center'>$Gfon $reg[usr]</td></tr>";
			$nRng++;

    }

    echo "</table>";
    mysql_close();
?>

</body>

</html>
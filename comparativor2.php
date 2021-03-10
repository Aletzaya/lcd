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
	  echo '<script language="javascript">alert("Fechas 1 incorrectas... Verifique");</script>'; 
  }

  $FechaI2	=	$_REQUEST[FechaI2];

  if (!isset($FechaI2)){
      $FechaI2 = date("Y-m-d",strtotime($FechaI2."- 1 year"));
  }

  $FechaF2  = $_REQUEST[FechaF2];

  if (!isset($FechaF2)){
      $FechaF2 = date("Y-m-d",strtotime($FechaF2."- 1 year"));
  }

  if ($FechaI2>$FechaF2){
	  echo '<script language="javascript">alert("Fechas 2 incorrectas... Verifique");</script>'; 
  }  
  
  if (!isset($_REQUEST[filtro])){
      $filtro = '*';
  }else{
	  $filtro    = $_REQUEST[filtro];       
  }

  if (!isset($_REQUEST[filtro3])){
      $filtro3 = '*';
  }else{
	  $filtro3    = $_REQUEST[filtro3];       
  }

  if (!isset($_REQUEST[filtro5])){
      $filtro5 = '*';
  }else{
	  $filtro5    = $_REQUEST[filtro5];       
  }

  if (!isset($_REQUEST[filtro7])){
      $filtro7 = '*';
  }else{
	  $filtro7    = $_REQUEST[filtro7];       
  }

  if (!isset($_REQUEST[filtro9])){
      $filtro9 = '*';
  }else{
	  $filtro9    = $_REQUEST[filtro9];       
  }

 if($filtro<>'*'){
 	$filtro2="and med.clasificacion='$filtro'";
 }else{
	$filtro2=" ";
 }

 if($filtro3<>'*'){
 	$filtro4="and ot.suc='$filtro3'";
 }else{
	$filtro4=" ";
 }

 if($filtro5<>'*'){
 	$filtro6="and ot.institucion='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and est.depto='$filtro7'";
 }else{
	$filtro8=" ";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and inst.condiciones='$filtro9'";
 }else{
	$filtro10=" ";
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

  $Titulo = "Demanda Comparativa por Estudio";

	$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(ot.orden), est.depto,est.subdepto 
	FROM otd, est, ot
	WHERE otd.estudio = est.estudio and ot.fecha>='$FechaI' and ot.fecha<='$FechaF' and ot.orden=otd.orden $filtro4 $filtro6 $filtro8
	GROUP BY est.subdepto Order BY est.subdepto";

	$OtA  = mysql_query($cSql,$link);

	$cSql2="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden) as cant, sum(otd.precio) as precio,sum(otd.precio * (otd.descuento/100)) as descuento, count(ot.orden), est.depto,est.subdepto 
	FROM otd, est, ot
	WHERE otd.estudio = est.estudio and ot.fecha>='$FechaI2' and ot.fecha<='$FechaF2' and ot.orden=otd.orden $filtro4 $filtro6 $filtro8
	GROUP BY est.subdepto Order BY est.subdepto";

	$OtA2 = mysql_query($cSql2,$link);

	$est2="SELECT est.estudio,est.descripcion,est.subdepto FROM est where est.activo='Si' ORDER BY est.subdepto ASC";

  	$est3  = mysql_query($est2,$link);

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

	echo "<form name='form' method='post' action='comparativo.php'>";

	echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
		
	echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";


	echo "$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI2' size='10' value ='$FechaI2'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI2,'yyyy-mm-dd',this)>";
		
	echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF2' size='10' value ='$FechaF2'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF2,'yyyy-mm-dd',this)>";
	

	echo "$Gfont<b><font size='1'>Suc </b></font>";
	echo "<select size='1' name='filtro3' class='Estilo10'>";

	$SucA=mysql_query("SELECT id,alias FROM cia order by id");
  echo "<option value='*'>Todos*</option>";
	while($Suc=mysql_fetch_array($SucA)){
		echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
		if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
    if($filtro3=='*'){$DesSuc=' Todos';}
	}

	
  echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";		  
	echo "</select>";
	echo"</b>";

  echo "$Gfont<b><font size='1'>Inst </b></font>";

  echo "<select size='1' name='filtro5' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
  $InsA = mysql_query("SELECT institucion,alias FROM inst order by institucion");
  while($Ins=mysql_fetch_array($InsA)){
        echo "<option value=$Ins[institucion]>$Ins[institucion]&nbsp;$Ins[alias]</option>";
        if($Ins[institucion]==$filtro5){$DesInst=$Ins[alias];}
        if($filtro5=='*'){$DesInst=' Todos';}
  }   
  echo "<option selected value='$filtro5'>$Gfont <font size='-1'>$filtro5 $DesInst</option>";  
  echo "</select>";
  echo"</b>";
  
  echo "$Gfont<b><font size='1'>Depto</b></font>";

  echo "<select size='1' name='filtro7' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
   $Depto=mysql_query("select departamento,nombre from dep",$link);
  while ($Depto1=mysql_fetch_array($Depto)){
        echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        if($Depto1[departamento]==$filtro7){$Desdepto=$Depto1[nombre];}
        if($filtro7=='*'){$Desdepto=' Todos';}
  }   
  echo "<option selected value='$filtro7'>$Gfont <font size='-1'>$filtro7 $Desdepto</option>";  
  echo "</select>";
  echo"</b>";


//  echo "&nbsp; <input type='hidden' name='filtro3' value='$filtro3'>";

  echo "&nbsp; <input type='SUBMIT' value='Ok'>";

  echo"</b></td>";
  echo "</form>";

  echo"<tr><td colspan='9'>&nbsp</td></tr>";

    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

    $Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon </td><td align='center' colspan='2'>$Gfon </td>
  <td align='center' colspan='2'>$Gfon <b>De $FechaI al $FechaF</b></td><td align='center' colspan='2'>$Gfon <b>De $FechaI2 al $FechaF2</b></td>
  <td align='center' colspan='2'>$Gfon <b>Diferencia</b></td></tr><tr bgcolor='#a2b2de'><td align='center'>$Gfon <b>Estudio</b></td><td align='center' colspan='2'>$Gfon <b>Descripcion</b></td>
	<td align='center'>$Gfon <b>Cant</b></td><td align='center'>$Gfon <b>Importe</b></td><td align='center'>$Gfon <b>Cant</b></td><td align='center'>$Gfon <b>Importe</b></td>
	<td align='center'>$Gfon <b>Cantidad</b></td><td align='center'>$Gfon <b>Importe</b></td></tr>";

    echo $Tit;

	$canti1=0;

	$importotal1=0;

	$estudio1='';


	$canti2=0;

	$importotal2=0;

	$estudio2='';


    //echo "<table width='100%' align='center' border='0'>";

  	while ($reg1 = mysql_fetch_array($est3)) {

  		while ($reg2 = mysql_fetch_array($OtA)) {

  			$estudio = str_replace(' ', '', $reg2[estudio]);

  			if($reg1[estudio]==$estudio){

  				$estudio1=$reg2[estudio];

  				$canti1=$reg2[cant];

  				$cantit1=$cantit1+$reg2[cant];

  				$importotal1=$reg2[precio]-$reg2[descuento];

  				$importotalt1=$importotalt1+$importotal1;

  			}
  		}

  		while ($reg3 = mysql_fetch_array($OtA2)) {

  			$estudios = str_replace(' ', '', $reg3[estudio]);

  			if($reg1[estudio]==$estudios){

  				$estudio2=$reg3[estudio];

  				$canti2=$reg3[cant];

  				$cantit2=$cantit2+$reg3[cant];

  				$importotal2=$reg3[precio]-$reg3[descuento];

  				$importotalt2=$importotalt2+$importotal2;

  			}
  		}


  		if($canti1<>0 or $canti2<>0){

  			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

  			$difcant=$canti1-$canti2;
  			$difimport=$importotal1-$importotal2;

			echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
			<td align='center'>$Gfont $reg1[estudio]</td><td colspan='2'>$Gfont $reg1[descripcion] - $reg1[subdepto] </font></td><td align='center'>$Gfont $canti1<font size='1'></font></td>
			<td align='right'>$Gfont ".number_format($importotal1,'2')."</font></td><td align='center'>$Gfont $canti2</font></td><td align='right'>$Gfont ".number_format($importotal2,'2')."</font></td><td  align='center'>$Gfont $difcant</font></td><td align='right'>$Gfont ".number_format($difimport,'2')."</font></td></tr>";

			$nRng++;
		}

		$estudio1='';
		$canti1=0;
		$importotal1=0;

		$estudio2='';
		$canti2=0;
		$importotal2=0;

		mysql_data_seek($OtA, 0);
		mysql_data_seek($OtA2, 0);
  	}

  	$cantit3=$cantit1-$cantit2;
  	$importotalt3=$importotalt1-$importotalt2;

    $Tot = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon </td><td align='right' colspan='2'>$Gfon <b>Totales --------> </b></td>
	<td align='center'>$Gfon <b>$cantit1</b></td><td align='right'>$Gfon <b>$ ".number_format($importotalt1,'2')."</b></td><td align='center'>$Gfon <b>$cantit2</b></td><td align='right'>$Gfon <b>$ ".number_format($importotalt2,'2')."</b></td>
	<td align='center'>$Gfon <b>$cantit3</b></td><td align='right'>$Gfon <b>$ ".number_format($importotalt3,'2')."</b></td></tr>";

    echo $Tot;

    echo "</table>";
    mysql_close();
?>

</body>

</html>
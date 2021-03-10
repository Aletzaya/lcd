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
 	$filtro6="and inst.institucion='$filtro5'";
 }else{
	$filtro6=" ";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and inst.status='$filtro7'";
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


   $Titulo = "Corte general por institucion del $FechaI al $FechaF";

	$cOtA="select inst.institucion,inst.nombre,date_format(ot.fecha,'%Y-%m') as fecha,count(*),inst.condiciones,inst.promotorasig,
	inst.status,inst.condiciones,ot.suc,sum(ot.importe) as importe from ot,inst
	WHERE ot.institucion=inst.institucion $filtro4 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF'
	GROUP BY ot.institucion order by ot.institucion";	

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

	echo "<form name='form' method='post' action='impcortegral.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	
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
	
	echo "<td align='left'>$Gfont<b><font size='1'>Institucion</b></font>";
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
	echo "&nbsp; <input type='SUBMIT' value='Ok'>";

	echo"</b></font></td></tr></table>";
	echo "</form>";

    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

	echo "<table align='center' width='100%' border='0'>";

    echo "<tr bgcolor='#a2b2de'><td align='center'>$Gfon Inst </td><td align='center'>$Gfon Nombre</td>
	<td align='center'>$Gfon Ventas</td><td align='center'>$Gfon Adeudos</td><td align='center'>$Gfon IngEfec</td><td align='center'>$Gfon IngTarj</td><td align='center'>$Gfon IngChe</td><td align='center'>$Gfon IngTransf</td><td align='center'>$Gfon RecupEfec</td><td align='center'>$Gfon RecupTarj</td><td align='center'>$Gfon RecupChe</td><td align='center'>$Gfon RecupTrans</td><td align='center'>$Gfon IngTotal</td></tr>";

    $Inst = 'XXX';
    while ($reg = mysql_fetch_array($OtA)) {
        if ($reg[institucion] <> $Inst) {

			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            if ($Inst <> 'XXX') {

                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td align='center'>$Gfont $Inst</td><td width='22%'>$Gfont $Nombre </font></td><td align='right' width='15%'>$Gfont ".number_format($Ventas,'2')."</font></td><td align='right'>$Gfont ".number_format($Adeudos,'2')."</font></td><td align='right'>$Gfont ".number_format($IngEfec,'2')."</td><td align='right'>$Gfont ".number_format($IngTarj,'2')."</td><td align='right'>$Gfont ".number_format($IngChe,'2')." </td><td align='right'>$Gfont ".number_format($IngTransf,'2')." </td><td align='right'>$Gfont ".number_format($RecupEfec,'2')."</font></td><td align='right'>$Gfont ".number_format($RecupTarj,'2')."</font></td><td align='right'>$Gfont ".number_format($RecupChe,'2')."</font></td></tr>";

				$VentasT+=$Ventas;				
				$Ventas = 0;

            }
        }

            $Inst = $reg[institucion];
            $Nombre = $reg[nombre];	
            $Ventas = $reg[importe];	
			$nRng++;
    }

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td align='center'>$Gfont $Inst</td><td width='22%'>$Gfont $Nombre </font></td><td align='right' width='15%'>$Gfont ".number_format($Ventas,'2')."</font></td><td align='right'>$Gfont ".number_format($Adeudos,'2')."</font></td><td align='right'>$Gfont ".number_format($IngEfec,'2')."</td><td align='right'>$Gfont ".number_format($IngTarj,'2')."</td><td align='right'>$Gfont ".number_format($IngChe,'2')." </td><td align='right'>$Gfont ".number_format($IngTransf,'2')." </td><td align='right'>$Gfont ".number_format($RecupEfec,'2')."</font></td><td align='right'>$Gfont ".number_format($RecupTarj,'2')."</font></td><td align='right'>$Gfont ".number_format($RecupChe,'2')."</font></td></tr>";


    echo "</table>";
    mysql_close();
?>

</body>

</html>
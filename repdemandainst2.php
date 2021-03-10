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
 	$filtro6="and inst.promotorasig='$filtro5'";
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

  $Titulo = "Demanda de afluencia de pacientes por institucion del $FechaI al $FechaF";

	$cOtA="select inst.institucion,inst.nombre,date_format(ot.fecha,'%Y%m') as fecha2,count(*),inst.condiciones,inst.promotorasig,
	inst.status,inst.condiciones,ot.suc,sum(ot.importe) as importe from ot,inst
	WHERE ot.institucion=inst.institucion $filtro4 $filtro6 $filtro8 $filtro10 and ot.fecha Between '$FechaI' And '$FechaF'
	GROUP BY ot.institucion,date_format(ot.fecha,'%Y-%m') order by ot.institucion, date_format(ot.fecha,'%Y-%m')";

  $OtA  = mysql_query($cOtA,$link);
  $OtB  = mysql_query($cOtA,$link);

  $Mes  = array("","Ene","Feb","Mzo","Abr","May","Jun","Jul","Agos","Sept","Oct","Nov","Dic");

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

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

	echo "<form name='form' method='post' action='repdemandainst.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	
	echo "<td>&nbsp; $Gfont <font size='1'><b> DE: $FechaI </b></font><input type='hidden' readonly='readonly' name='FechaI' size='10' value ='$FechaI' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)></td>";
		
	echo "<td>&nbsp; $Gfont <font size='1'><b> A: $FechaF </b></font><input type='hidden' readonly='readonly' name='FechaF' size='10' value ='$FechaF' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></td>";
	
	echo "</form>";

	echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
	echo "<form name='form' method='post' action='repdemandainst.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro3' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	$SucA=mysql_query("SELECT id,alias FROM cia order by id");
	while($Suc=mysql_fetch_array($SucA)){
		echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
		if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
	}
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";
	
	echo "<td align='left'>$Gfont<b><font size='1'>Promotor</b></font>";
	echo "<form name='form' method='post' action='repdemandainst.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro5' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	echo "<option value='Promotor_A'>Promotor_A</option>";
	echo "<option value='Promotor_B'>Promotor_B</option>";
	echo "<option value='Promotor_C'>Promotor_C</option>";
	echo "<option value='Promotor_D'>Promotor_D</option>";
	echo "<option value='Promotor_E'>Promotor_E</option>";
	echo "<option value='Promotor_F'>Promotor_F</option>";
	echo "<option value='Base'>Base</option>";
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";
	
	echo "<td align='left'>$Gfont<b><font size='1'>Condiciones</b></font>";
	echo "<form name='form' method='post' action='repdemandainst.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro9' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	echo "<option value='CONTADO'>Contado</option>";
	echo "<option value='CREDITO'>Credito</option>";
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro9</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";
	
	echo "<td align='left'>$Gfont<b><font size='1'>Status</b></font>";
	echo "<form name='form' method='post' action='repdemandainst.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";
	echo "<select size='1' name='filtro7' class='Estilo10' onchange=this.form.submit()>";
	echo "<option value='*'>Todos*</option>";
	echo "<option value='ACTIVO'>Activo</option>";
	echo "<option value='INACTIVO'>Inactivo</option>";
	echo "<option selected value='*'>$Gfont <font size='-1'>$filtro7</option></p>";		  
	echo "</select>";
	echo"</b></td>";
	echo "</form>";

    echo "</font></td><td>";

    $Sql = str_replace("'", "!", $cOtA);  //Remplazo la comita p'k mande todo el string
	
    echo "<form name='form2' method='get' action='menu.php'>";
    echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
    echo "</td></tr></table>";

    $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
    $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
    $Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon Inst</td><td align='center' colspan='2'>$Gfon Nombre</td>
	<td align='center'>$Gfon Condiciones</td><td align='center'>$Gfon Promotor</td>";

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $Tit = $Tit . "<td align='center'>$Gfon $Mes[$x]</td>";
		$Cmes+=1;
    }

    $Tit = $Tit . "<td align='center'>$Gfon # Ord</td><td align='center'>$Gfon Venta</td>
	<td align='center'>$Gfon Prom</td>
	<td align='center'>$Gfon Vst</td></tr>";

    echo "<table width='100%' align='center' border='0'>";

    echo $Tit;
    $Inst = 'XXX';
    while ($reg = mysql_fetch_array($OtA)) {
		if ($Inst <> $reg[institucion]) {
			$sTit ='';
			$nRng++;
			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;		

			if ($Inst == 'XXX') {
			//if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
				echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
				<td align='center'><a href=javascript:winuni('comisionesmed.php?FecI=$FechaI&FecF=$FechaF&Institucion=*&$Sucursal=1&Promotor=$filtro5&Medico=$Inst')>$Gfont $reg[institucion]</a>
				</td><td width='22%'>$Gfont $reg[nombre] </font></td><td aling='right' width='5%'>$Gfont <font size='1'>$reg[status]</font></td>
				<td align='center' width='15%'>$Gfont $reg[condiciones]</font></td><td>$Gfont $reg[promotorasig]</font></td>";
			
			}else{
				$cOtB= "select inst,count(*),date_format(vinst.fecha,'%Y%m') from vinst
				WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m') Between '$Ini' And '$Fin'";
				
				$OtB  = mysql_fetch_array(mysql_query($cOtB,$link));

				echo "<td align='center'>$Gfont ".number_format($contart,'0')." </td>
				<td align='right'>$Gfont ".number_format($importemt,'2')."</font>
				<td align='right'>$Gfont ".number_format($importemt/$contart,'2')."</font>
				<td align='center'><a href=javascript:winuni('visitasinst.php?FechaI=$Ini&FechaF=$Fin&Inst=$Inst')>$Gfont $OtB[1]</a></font></td></tr>";				
				
				if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
				
				echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
				<td align='center'><a href=javascript:winuni('comisionesmed.php?FecI=$FechaI&FecF=$FechaF&Institucion=*&$Sucursal=1&Promotor=$filtro5&Medico=$Inst')>$Gfont $reg[institucion]</a>
				</td><td width='22%'>$Gfont $reg[nombre] </font></td><td aling='right' width='5%'>$Gfont <font size='1'>$reg[status]</font></td>
				<td align='center' width='15%'>$Gfont $reg[condiciones]</font></td><td>$Gfont $reg[promotorasig]</font></td>";

			}
			$importem=$reg[importe];
			$contar=$reg[3];				
			echo "<td align='right' bgcolor='$vist' 
				onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
				onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($importem,'2')."</td>";

		}else{
			$importem=$reg[importe];
			$contar=$reg[3];				
			echo "<td align='right' bgcolor='$vist' 
				onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
				onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($importem,'2')."</td>";
		}
		$Inst=$reg[institucion];
		$GraT+=$reg[importe];
		$VentaT+=$reg[importe];
		$Tord+=$reg[3];
		$importemt=0;
		$contart=0;
		$importem=0;
		$contar=0;

	}
			
	$PromedioG= $GraT/$Cmes;
	$VentaT+=$Venta;
    echo "<tr bgcolor='#a2b2de' aling='center'><td>$Gfon2 <b> &nbsp; Totales: </b></td><td colspan='2'><b>$Gfon2 &nbsp; Instituciones --> $nRng</b></td>
	<td>&nbsp;</td><td align='right'>$Gfon2 &nbsp;</font></td>";
    echo $cTit. "<td align='right'>$Gfon2 <b>".number_format($GraT,'2')."</b></td>
	<td align='center'>$Gfon2 <b>".number_format($Tord,'0')."</b>
	</td><td align='right'>$Gfon2 <b>".number_format($VentaT,'2')."</b></td>
	</td><td align='right'>$Gfon2 &nbsp;</b></td>
	<td align='center'>$Gfon2 <b>&nbsp;</b></td></tr>";

    echo "</table>";
    mysql_close();
?>

</body>

</html>
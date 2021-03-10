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
      $filtro9 = 'Atencion';
  }else{
	  $filtro9   = $_REQUEST[filtro9];       
  }

/*if($filtro<>'*'){
 	$filtro2="and med.clasificacion='$filtro'";
 }else{
	$filtro2=" ";
 }*/

 if($filtro3<>'*'){
 	$filtro4="and maqdet.mint='$filtro3'";
 }else{
	$filtro4=" ";
 }
/*
 if($filtro5<>'*'){
  $filtro6="and ot.institucion='$filtro5'";
 }else{
  $filtro6=" ";
 }
*/
if($filtro7<>'*'){
 	$filtro8="and est.depto='$filtro7'";
 }else{
	$filtro8=" ";
 }
 
/* 
 if($filtro9=='Atencion'){
 	$filtro10="otd.usrest";
 }elseif($filtro9=='Captura'){
	$filtro10="otd.capturo";
 }elseif($filtro9=='Impresion'){
  $filtro10="otd.impest";
 }elseif($filtro9=='Proceso'){
  $filtro10="maqdet.usrrec";
 }

*/
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

  $Titulo = "Reporte de Productividad del $FechaI al $FechaF";

    $cOtA="select maqdet.usrrec as usuario,date_format(maqdet.fenv,'%Y-%m') as fecha,count(*),maqdet.orden,maqdet.mint,maqdet.estudio,est.depto
    from maqdet,est
    WHERE maqdet.fenv Between '$FechaI' And '$FechaF'  and maqdet.estudio=est.estudio $filtro4 $filtro8
    GROUP BY maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m') order by maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m')";
/*
  $cOtA="select maqdet.usrrec as usuario,date_format(maqdet.fenv,'%Y-%m') as fecha,count(*),maqdet.orden,maqdet.mint,maqdet.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
    from otd,maqdet,est
    WHERE maqdet.orden=otd.orden and maqdet.fenv Between '$FechaI' And '$FechaF' and maqdet.estudio=est.estudio and maqdet.orden=otd.orden
    GROUP BY maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m') order by maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m')";
*/

  $OtA  = mysql_query($cOtA,$link);

  $Mes  = array("","Ene","Feb","Mzo","Abr","May","Jun","Jul","Agos","Sept","Oct","Nov","Dic");

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
  $tCntot = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

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

  echo "<form name='form' method='post' action='repoperativo4.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";

  echo "<td align='center'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

  echo "$Gfont <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></td>";
  
  echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
  echo "<select size='1' name='filtro3' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
  $SucA=mysql_query("SELECT id,alias FROM cia order by id");
  while($Suc=mysql_fetch_array($SucA)){
    echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
    if($Suc[id]==$filtro3){$DesSuc=$Suc[alias];}
  }
  echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$filtro3 $DesSuc</option></p>";     
  echo "</select>";
  echo"</b></td>";

  echo "<td align='left'>$Gfont<b><font size='1'>Inst.</b></font>";
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
  echo"</b></td>";

  echo "<td align='left'>$Gfont<b><font size='1'>Depto</b></font>";
  echo "<select size='1' name='filtro7' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
	$Depto=mysql_query("select departamento,nombre from dep",$link);
	while ($Depto1=mysql_fetch_array($Depto)){
	       echo "<option value='$Depto1[0]'>$Gfont <font size='-2'>$Depto1[0] - $Depto1[nombre]</font></option>";
	       if($Depto1[departamento]==$filtro7){$Desdepto=$Depto1[nombre];} 
	}
  echo "<option selected value='$filtro7'>$Gfont <font size='-1'>$filtro7 $Desdepto</option>";  
  echo "</select>";
  echo "&nbsp; <input type='SUBMIT' value='Ok'>";

  echo"</b></td>";
/*
  echo "<td align='left'>$Gfont<b><font size='1'>Etapa</b></font>";
  echo "<select size='1' name='filtro9' class='Estilo10'>";
  echo "<option value='Atencion'>Atencion</option>";
  echo "<option value='Proceso'>Proceso</option>";
  echo "<option value='Captura'>Captura</option>";
  echo "<option value='Impresion'>Impresion</option>";
  echo "<option selected value='Atencion'>$Gfont <font size='-1'>$filtro9</option>";  
  echo "</select>";

  echo"</b></font></td>";*/
  echo "</form>";

  echo "</font></td><td>";

    $Sql = str_replace("'", "!", $cOtA);  //Remplazo la comita p'k mande todo el string
	
    echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
    echo "</td></tr></table>";

    $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
    $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
    $Gfon = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
    $Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon <b>Captura</b></td>";

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $Tit = $Tit . "<td align='center' colspan='2'>$Gfon <b>$Mes[$x]</b></td>";
		$Cmes+=1;
    }

    $Tit = $Tit . "<td align='center' colspan='2'>$Gfon <b>Total</b></td><td align='center'>$Gfon <b>Prom</b></td></tr>";

    echo "<table width='100%' align='center' border='0'>";

    echo $Tit;
    $capt = 'XXX';
    while ($reg = mysql_fetch_array($OtA)) {
      if ($reg[usuario] <> $capt) {

			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            if ($capt <> 'XXX') {
                $cTit = '';
                $SubT = 0;
                $SubTot = 0;
				
                for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                    if (substr($i, 4, 2) == '13') {
                        $i = $i + 88;
					           }
										
                    $x = substr($i, 4, 2) * 1;
//                    $cTit = $cTit . "<td align='center'>$Gfon ".number_format($aCnt[$x],'0')."</font></td>";
                    $cTit = $cTit . "<td align='center' bgcolor='$vist' 
                    onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
                    onMouseOut=this.style.backgroundColor='$vist';>$Gfon ".number_format($aCnt[$x],'0')."</font></td><td align='right' bgcolor='$vist' 
                    onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
                    onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntot[$x],'2')."</font></td>";
                    $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                    $taCnt[$x] = $taCnt[$x] + $aCntot[$x];
                    $SubT += $aCnt[$x];
                    $SubTot += $aCntot[$x];
                    $GraT += $aCnt[$x];
                    $GraTot += $aCntot[$x];
                }
				
				      $Promedio= $SubT/$Cmes;

              if($capt==''){
                $capt='SIN_REGISTRO';
              }else{
                $capt=$capt;
              }
				

              echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
              <td align='center'><a href=javascript:winuni('repoperativod4.php?FecI=$FechaI&FecF=$FechaF&Institucion=$filtro5&Suc=$filtro3&capt=$capt&depto=$filtro7&etapa=$filtro9')>$Gfont <b>$capt</b></a>
              </td></td>";
              echo $cTit . "<td align='center'>$Gfon <b>".number_format($SubT,'0')."</b></td><td align='right'>$Gfon ".number_format($SubTot,'2')."</td><td align='center'>$Gfon ".number_format($Promedio,'2')." </td></tr>";
              $VentaT+=$Venta;				
              $Venta = 0;
              $Tvisit+=$OtB[1];

            }
            $capt = $reg[usuario];
            $Nombre = $reg[nomedic];
            $aCnt = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");	
            $aCntot = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0"); 

			$nRng++;
		
        }
        $Fec = $reg[fecha];
        $Pos = 0 + substr($Fec, 5, 2);
        $aCnt[$Pos] = $reg[2];
        $aCntot[$Pos] = $aCntot[$Pos]+($reg[precios]-$reg[descuentos]);

    }
    $cTit = '';
    $SubT = 0;
    $SubTot = 0;
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
		
		$x = substr($i, 4, 2) * 1;
 
		$cTit = $cTit . "<td align='center' bgcolor='$vist' 
		onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
					onMouseOut=this.style.backgroundColor='$vist';>$Gfon ".number_format($aCnt[$x],'0')."</font></td><td align='right' bgcolor='$vist' 
          onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
          onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntot[$x],'2')."</font></td>";
        $SubT+=$aCnt[$x];
        $GraT+=$aCnt[$x];
        $SubTot += $aCntot[$x];
    $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
    $taCnt[$x] = $taCnt[$x] + $aCntot[$x];
    }
	$Promedio= $SubT/$Cmes;


	if( ($nRng % 2) > 2 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
    <td align='center'><a href=javascript:winuni('repoperativod4.php?FecI=$FechaI&FecF=$FechaF&Institucion=$filtro5&Suc=$filtro3&capt=$capt&depto=$filtro7&etapa=$filtro9')>$Gfont <b>$capt</b></a>
    </td></td>";
	echo $cTit . "<td align='center'>$Gfon <b>".number_format($SubT,'0')."</b></td><td align='right'>$Gfon ".number_format($SubTot,'2')."</td><td align='center'>$Gfon ".number_format($Promedio,'2')." </td></tr>";
	$Tvisit+=$OtB[1];
    $cTit = '';
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
		
        $cTit = $cTit . "<td align='center'>$Gfon2 <b>".number_format($tCnt[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($taCnt[$x],'2')."</b></font></td>";
    }
	$PromedioG= $GraT/$Cmes;
	$VentaT+=$Venta;
    echo "<tr bgcolor='#a2b2de' aling='center'><td>$Gfon2 <b> &nbsp; Totales: Personal --> $nRng</b></td>";
    echo $cTit. "<td align='center'>$Gfon2 <b>".number_format($GraT,'0')."</b></td>
	<td align='center'>$Gfon2 </td></tr>";

    echo "</table>";
    mysql_close();
?>

</body>

</html>
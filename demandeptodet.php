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


  $HoraI=$_REQUEST[HoraI];

  if (!isset($HoraI)){
      $HoraI = '07:00';
  }

  $HoraF=$_REQUEST[HoraF];

  if (!isset($HoraF)){
      $HoraF = date('G:i');
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
 	$filtro2="and otd.status='$filtro'";
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
 	$filtro10="and est.subdepto='$filtro9'";
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

  $Titulo = "Demanda de Lista de Trabajo";

  $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
  otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
  otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno, otd.fechaest,otd.fr,otd.usrvalida,otd.creapdf, est.subdepto, otd.tres
  FROM ot,est,otd,cli
  WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio and ot.fecha>='$FechaI' and ot.fecha<='$FechaF' and ot.orden=otd.orden AND ot.hora >='$HoraI' AND ot.hora <='$HoraF' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 Order BY est.depto, est.subdepto, otd.estudio";

	$OtA  = mysql_query($SqlA,$link);

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

	echo "<form name='form' method='post' action='demandeptodet.php'>";

	echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De: </b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$FechaI'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

    echo "&nbsp;<b><font size='1'> Hra.I.: </b></font>";
  echo "<input type='text' value='$HoraI' name='HoraI' size='6' >&nbsp;";
		
	echo "$Gfont&nbsp;<font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$FechaF'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";


  echo "&nbsp;&nbsp;<b><font size='1'>Hra.F.: </b></font>";
  echo "<input type='text' value='$HoraF'  name='HoraF' size='6' >&nbsp;&nbsp;";
	

	echo "$Gfont <b><font size='1'>Suc </b></font>";
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

  echo "$Gfont <b><font size='1'>Inst </b></font>";

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
  
  echo "<br>";

  echo "<br>";

  echo "$Gfont <b><font size='1'>Depto</b></font>";

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

  echo "$Gfont <b><font size='1'>Subdepto</b></font>";

  echo "<select size='1' name='filtro9' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
   $subdepto=mysql_query("select departamento,subdepto from depd",$link);
  while ($subdepto1=mysql_fetch_array($subdepto)){
        echo "<option value='$subdepto1[1]'>$subdepto1[1]</option>";
        if($subdepto1[departamento]==$filtro9){$Desudepto=$subdepto1[nombre];}
        if($filtro9=='*'){$Desudepto=' Todos';}
  }   
  echo "<option selected value='$filtro9'>$Gfont <font size='-1'>$filtro9 $Desudepto</option>";  
  echo "</select>";
  echo"</b>";

    echo "$Gfont <b><font size='1'>Status</b></font>";

  echo "<select size='1' name='filtro' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";  
  echo "<option value='DEPTO'>DEPTO</option>";  
  echo "<option value='RESUL'>RESUL</option>";  
  echo "<option value='TOMA/REALIZ'>TOMA/REALIZ</option>";  
  echo "<option value='PENDIENTE'>PENDIENTE</option>";  
  echo "<option value='TERMINADA'>TERMINADA</option>";  
  echo "<option selected value='$filtro'>$Gfont <font size='-1'>$filtro</option>";  
  echo "</select>";
  echo"</b>";

//  echo "&nbsp; <input type='hidden' name='filtro3' value='$filtro3'>";

  echo "&nbsp;<input type='SUBMIT' value='Ok'>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;";

      echo "&nbsp; &nbsp; <input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";


  echo"</b></td>";
  echo "</form>";

  echo"<tr><td colspan='9'>&nbsp</td></tr>";

    $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
    $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";

    $Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon <b>Suc</b></td><td align='center'>$Gfon <b>Inst - Orden</b></td>
	<td align='center'>$Gfon <b>Fecha/Hora de Captura</b></td><td align='center'>$Gfon <b>Nombre del paciente</b></td><td align='center'>$Gfon <b>Status</b></td><td align='center'>$Gfon <b>Fech. Capt.</b></td></tr>";

    echo $Tit;

	$canti1=0;

	$importotal1=0;

	$estudio1='';


	$canti2=0;

	$importotal2=0;

	$estudio2='';

  $contestudio2=1;

    //echo "<table width='100%' align='center' border='0'>";
  $subdepto2='';

  	while ($reg1 = mysql_fetch_array($OtA)) {

  			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

  			$difcant=$canti1-$canti2;
  			$difimport=$importotal1-$importotal2;

        $est2="SELECT * FROM dep where departamento=$reg1[depto]";

        $est3  = mysql_query($est2,$link);

        $reg2 = mysql_fetch_array($est3);

        if($reg1[depto]==1){

          $laboratorio=$laboratorio+1;

        }elseif($reg1[depto]==2){

          $rx=$rx+1;

        }elseif($reg1[depto]==3){

          $Especiales =$Especiales +1;

        }elseif($reg1[depto]==4){

          $Servicios  =$Servicios  +1;

        }elseif($reg1[depto]==6){

          $Externos   =$Externos   +1;

        }elseif($reg1[depto]==7){

          $Mixto    =$Mixto    +1;


        }elseif($reg1[depto]==8){

          $Administrativo     =$Administrativo     +1;

        }


        if($reg1[subdepto]<>$subdepto2){

          if( $estudio2<>''){

            $contestudio3=$contestudio3+$contestudio2;

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
            <td align='center' colspan='6'>$Gfon <b>Total Estudios ----> $contestudio2</b></td></tr>";

            $contestudio2=1;

            echo "<tr><td align='right' colspan='4'></td>
            <td align='left' colspan='2' bgcolor='#f9e79f'>$Gfon <b>Total Estudios del Subdepartamento ----> $contestudio3</b></td></tr>";

            echo "<tr><td align='center' colspan='6'>$Gfon <b><hr></b></td></tr>";

            $contestudio3=0;

          }

          echo "<tr bgcolor='#f9e79f' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#f9e79f';>
          <td align='center' colspan='6'>$Gfon <b>$reg2[nombre] - $reg1[subdepto]</b></td></tr>";

          $subdepto2=$reg1[subdepto];

          $estudio2='';

          $contestudio2=1;

        }else{

          $subdepto2=$reg1[subdepto];

        }

        if($reg1[estudio]<>$estudio2){

          if( $estudio2<>''){
            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
            <td align='center' colspan='6'>$Gfon <b>Total Estudios ----> $contestudio2</b></td></tr>";

            $contestudio3=$contestudio3+$contestudio2;

            $contestudio2=1;


          }

          echo "<tr bgcolor='#bfc9ca' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#bfc9ca';>
          <td align='left' colspan='6'>$Gfon <b>$reg1[estudio] - $reg1[descripcion]</b></td></tr>";

          $estudio2=$reg1[estudio];

        }else{

          $estudio2=$reg1[estudio];

          $contestudio2++;

        }

        if($reg1[status]=='TERMINADA'){

          $color='#000000';

        }else{

          $color='#F00000';

        }

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
      <td align='center'>$Gfont $reg1[suc]</td><td align='center'>$Gfont $reg1[institucion] - $reg1[orden]</td><td align='center'>$Gfont $reg1[fecha] - $reg1[hora]</td><td>$Gfont $reg1[nombrec] </font></td><td align='center'>$Gfont <font size='1' color=$color>$reg1[status]</font></td><td align='center'>$Gfont $reg1[tres]</font></td></tr><tr><td align='center' colspan='6'>$Gfont <b>$reg1[observaciones]</b></font></td></tr>";

			$nRng++;
		}


    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
    <td align='center' colspan='6'>$Gfon <b>Total Estudios ----> $contestudio2</b></td></tr>";

            $contestudio3=$contestudio3+$contestudio2;

    echo "<tr><td align='right' colspan='4'></td>
    <td align='left' colspan='2' bgcolor='#f9e79f'>$Gfon <b>Total Estudios del Subdepartamento ----> $contestudio3</b></td></tr>";

    echo "<tr><td align='center' colspan='6'>$Gfon <b><hr></b></td></tr>";


    echo "</table>";

    echo "<table align='center' width='50%' border='0'>";

    $totalest=$laboratorio+$Administrativo+$rx+$Especiales+$Servicios+$Externos+$Mixto;

    echo "<tr bgcolor='#bfc9ca' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#bfc9ca';><td align='center'>$Gfon <b>Departamento</td><td align='center'>$Gfon <b>Estudios</td></tr>";
    echo "<tr><td align='center'>$Gfon Laboratorio</td><td align='center'>$Gfon $laboratorio</td></tr>";
    echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor=$Gfdogrid;><td align='center'>$Gfon Rayos x e Imagen</td><td align='center'>$Gfon $rx</td></tr>";
    echo "<tr><td align='center'>$Gfon Especiales</td><td align='center'>$Gfon $Especiales</td></tr>";
    echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor=$Gfdogrid;><td align='center'>$Gfon Servicios </td><td align='center'>$Gfon $Servicios</td></tr>";
    echo "<tr><td align='center'>$Gfon Externos</td><td align='center'>$Gfon $Externos</td></tr>";
    echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor=$Gfdogrid;><td align='center'>$Gfon Mixto</td><td align='center'>$Gfon $Mixto</td></tr>";
    echo "<tr><td align='center'>$Gfon Administrativo</td><td align='center'>$Gfon $Administrativo</td></tr>";
    echo "<tr bgcolor='#bfc9ca' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#bfc9ca';><td align='center'>$Gfon <b>Total</td><td align='center'>$Gfon <b>$totalest</td></tr>";

    echo "</table>";



    mysql_close();
?>

</body>

</html>
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
  $filtro4="and ot.suc='$filtro3'";
  $filtro4p="and maqdet.mint='$filtro3'";
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

      $AtencionA="select otd.usrest as usuarioA,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
      from otd,ot,est
      WHERE ot.orden=otd.orden and ot.fecha Between '$FechaI' And '$FechaF' and otd.estudio=est.estudio $filtro4 $filtro6 $filtro8
      GROUP BY otd.usrest,date_format(ot.fecha,'%Y-%m') order by otd.usrest,date_format(ot.fecha,'%Y-%m')";

      $CapturaA="select otd.capturo as usuarioC,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
      from otd,ot,est
      WHERE ot.orden=otd.orden and ot.fecha Between '$FechaI' And '$FechaF' and otd.estudio=est.estudio $filtro4 $filtro6 $filtro8
      GROUP BY otd.capturo,date_format(ot.fecha,'%Y-%m') order by otd.capturo,date_format(ot.fecha,'%Y-%m')";

    /*  $ProcesoA="select maqdet.usrrec as usuarioP,date_format(maqdet.fenv,'%Y-%m') as fecha,count(*),maqdet.orden,maqdet.mint,maqdet.estudio,est.depto
      from maqdet,est
      WHERE maqdet.fenv Between '$FechaI' And '$FechaF' and maqdet.estudio=est.estudio $filtro4p $filtro8
      GROUP BY maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m') order by maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m')";
*/
      $ProcesoA="select maqdet.usrrec as usuarioP,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos,otd.statustom
        FROM ot,est,otd,maqdet
        WHERE ot.orden=otd.orden AND otd.estudio=est.estudio and maqdet.orden=ot.orden AND otd.estudio=est.estudio AND maqdet.estudio=otd.estudio and ot.fecha Between '$FechaI' And '$FechaF' $filtro4 $filtro6 $filtro8 GROUP BY maqdet.usrrec,date_format(ot.fecha,'%Y-%m') order by maqdet.usrrec,date_format(ot.fecha,'%Y-%m')";

      /*
      $ProcesoA="select maqdet.usrrec as usuarioP,date_format(maqdet.fenv,'%Y-%m') as fecha,count(*),maqdet.orden,maqdet.mint,maqdet.estudio,est.depto
      from maqdet,est
      WHERE maqdet.fenv Between '$FechaI' And '$FechaF' and maqdet.estudio=est.estudio $filtro4p $filtro8
      GROUP BY maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m') order by maqdet.usrrec,date_format(maqdet.fenv,'%Y-%m')";
      */

/*
      $ImpresionA="select otd.impest as usuarioI,date_format(ot.fecha,'%Y-%m') as fecha,count(*),ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos
      from otd,ot,est
      WHERE ot.orden=otd.orden and ot.fecha Between '$FechaI' And '$FechaF' and otd.estudio=est.estudio $filtro4 $filtro6 $filtro8
      GROUP BY otd.impest,date_format(ot.fecha,'%Y-%m') order by otd.impest,date_format(ot.fecha,'%Y-%m')";

*/

  //$OtA  = mysql_query($cOtA,$link);
  $Atencion  = mysql_query($AtencionA,$link);
  $Captura  = mysql_query($CapturaA,$link);
  $Proceso  = mysql_query($ProcesoA,$link);
//  $Impresion  = mysql_query($ImpresionA,$link);

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

  echo "<form name='form' method='post' action='repoperativo5.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&FechaI=$FechaI&FechaF=$FechaF'>";

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
    $Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon <b>Usuario</b></td>";
    $Tit2 = "<tr bgcolor='#a2b2de'><td align='center'></td>";

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $Tit = $Tit . "<td align='center' colspan='6'>$Gfon <b>$Mes[$x]</b></td>";
        $Tit2 = $Tit2 . "<td align='center' colspan='2'>$Gfon Atn</b></td><td align='center' colspan='2'>$Gfon Proc</b></td><td align='center' colspan='2'>$Gfon Capt</b></td>";
		$Cmes+=1;
    }

    $Tit = $Tit;
    $Tit2 = $Tit2;

    echo "<table width='100%' align='center' border='0'>";

    echo $Tit.$Tit2;
    $capt = 'XXX';




    $usrB=mysql_query("SELECT * FROM authuser order by uname",$link);
      while($regusr=mysql_fetch_array($usrB)){
          $Usrb=strtoupper($regusr[uname]);       
  // *************** A t e n c i o n **************

          $contador=0;
          $cTit = '';
          $cTit2 = '';
          $cTit3 = '';
          $SubT = 0;
          $SubTot = 0;

   //      $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $taCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCntot = array("0","0","0","0","0","0","0","0","0","0","0","0","0");


          while($rega=mysql_fetch_array($Atencion)){

              $usuA=strtoupper($rega[usuarioA]);

              if($Usrb==$usuA){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;

                      $Fec = $rega[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCnt[$Pos] = $rega[2];
                      $aCntot[$Pos] = $aCntot[$Pos]+($rega[precios]-$rega[descuentos]);
                      $contador=1;

                      $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                      $taCnt[$x] = $taCnt[$x] + $aCntot[$x];
                      $taCntt[$x] = $taCntt[$x] + $aCntot[$x];
                      $SubT += $aCnt[$x];
                      $SubTot += $aCntot[$x];
                      $GraT += $aCnt[$x];
                      $GraTot += $aCntot[$x];   

                  }
              }

              if($aCnt[$x]==0){
                $resulaten='';
              }else{
                $resulaten=number_format($aCnt[$x],'0');
              }

          }

              $cTit = "<td align='center' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfon <a href=javascript:winuni('repoperativod3.php?FecI=$FechaI&FecF=$FechaF&Institucion=$filtro5&Suc=$filtro3&capt=$Usrb&depto=$filtro7&etapa=$filtro9')>$Gfont <b>".$resulaten."</b></a></font></td><td align='right' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($taCnt[$x],'2')."</font></td>";
  // *************** P r o c e s o  **************
/*-
          $cSql="SELECT maqdet.usrrec as usuario,date_format(maqdet.fenv,'%Y-%m') as fecha,maqdet.orden,maqdet.mint,maqdet.estudio,est.depto,est.subdepto,count(*) as contar
          from maqdet,est
          WHERE maqdet.fenv Between '$FechaI' And '$FechaF' and maqdet.usrrec='$Usrb' and maqdet.estudio=est.estudio GROUP BY maqdet.estudio";

          $cSql="select maqdet.usrrec as usuario,date_format(ot.fecha,'%Y-%m') as fecha,ot.orden,ot.institucion,maqdet.estudio,est.depto,est.subdepto,count(*) as contar
        FROM ot,est,otd,maqdet
        WHERE ot.orden=otd.orden AND otd.estudio=est.estudio and maqdet.orden=ot.orden AND otd.estudio=est.estudio AND maqdet.estudio=otd.estudio and ot.fecha Between '$FechaI' And '$FechaF' and maqdet.usrrec='$Usrb'";

          $UpA=mysql_query($ProcesoA,$link);

          while($registro=mysql_fetch_array($UpA)) {
            $departamento1=$registro[depto];
            $subdepartamento1=$registro[subdepto];
            $estudio1=$registro[estudio];

            $cSqla="SELECT orden,estudio,sum(precio) as precios,descuento
            from otd
            WHERE otd.orden=$registro[orden]";

            $UpAa=mysql_query($cSqla,$link);

            $registro2=mysql_fetch_array($UpAa);

                $precio1=$registro[contar]*$registro2[precios];

                $descuentos1=($registro2[descuento]*$registro2[precios])/100;

                $importe1=$precio1-$descuentos1;

                $contarT=$contarT+$registro[contar];
                $precio1T=$precio1T+$precio1;
                $descuentos1T=$descuentos1T+$descuentos1;
                $importe1T=$importe1T+$importe1;
               
        }//fin while
     

          $cTit3 = '';
          $SubT = 0;
          $SubTot = 0;

         // $tCntp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $taCntp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCntotp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCntp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

          while($regp=mysql_fetch_array($Proceso)){

              $usuB=strtoupper($regp[usuarioP]);

              if($Usrb==$usuB){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;

                      $Fec = $regp[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCntp[$Pos] = $regp[2];
                      $aCntotp[$Pos] = $aCntotp[$Pos]+$importe1T;
                      $contador=1;

                      $tCntp[$x] = $tCntp[$x] + $aCntp[$x];
                      $taCntp[$x] = $taCntp[$x] + $aCntotp[$x];
                      $SubT += $aCntp[$x];
                      $SubTot += $aCntotp[$x];
                      $GraT += $aCntp[$x];
                      $GraTot += $aCntotp[$x];       

                  }

              }

              if($aCntp[$x]==0){
                $resulproceso='';
              }else{
                $resulproceso=number_format($aCntp[$x],'0');
              }

          }
         
              $cTit3 = "<td align='center' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';><a href=javascript:winuni('repoperativod4.php?FecI=$FechaI&FecF=$FechaF&Institucion=$filtro5&Suc=$filtro3&capt=$Usrb&depto=$filtro7&etapa=$filtro9')>$Gfont <b>".$resulproceso."</b></a></font></td><td align='right' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($importe1T,'2')."</font></td>";
              
          $importe1T2=$importe1T2+$importe1T;
          $importe1T=0;

          */

          $aCntp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $taCntp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCntotp = array("0","0","0","0","0","0","0","0","0","0","0","0","0");


          while($regb=mysql_fetch_array($Proceso)){

              $usuP=strtoupper($regb[usuarioP]);

              if($Usrb==$usuP){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;

                      $Fec = $regb[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCntp[$Pos] = $regb[2];
                      $aCntotp[$Pos] = $aCntotp[$Pos]+($regb[precios]-$regb[descuentos]);
                      $contador=1;

                      $tCntp[$x] = $tCntp[$x] + $aCntp[$x];
                      $taCntp[$x] = $taCntp[$x] + $aCntotp[$x];
                      $taCnttp[$x] = $taCnttp[$x] + $aCntotp[$x];
                      $SubT += $aCntp[$x];
                      $SubTot += $aCntotp[$x];
                      $GraT += $aCntp[$x];
                      $GraTot += $aCntotp[$x];   

                  }
              }

              if($aCntp[$x]==0){
                $resulproceso='';
              }else{
                $resulproceso=number_format($aCntp[$x],'0');
              }

          }

              $cTit3 = "<td align='center' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfon <a href=javascript:winuni('repoperativod4.php?FecI=$FechaI&FecF=$FechaF&Institucion=$filtro5&Suc=$filtro3&capt=$Usrb&depto=$filtro7&etapa=$filtro9')>$Gfont <b>".$resulproceso."</b></a></font></td><td align='right' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($taCntp[$x],'2')."</font></td>";
  // *************** C a p t u r a  **************

          $cTit2 = '';
         // $SubT = 0;
         // $SubTot = 0;


              $aCntc = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
              $taCntc = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
              $aCntotc = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

          while($regc=mysql_fetch_array($Captura)){

              $usuC=strtoupper($regc[usuarioC]);

              if($Usrb==$usuC){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;

                      $Fec = $regc[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCntc[$Pos] = $regc[2];
                      $aCntotc[$Pos] = $aCntotc[$Pos]+($regc[precios]-$regc[descuentos]);
                      $contador=1;

                      $tCntc[$x] = $tCntc[$x] + $aCntc[$x];
                      $taCntc[$x] = $taCntc[$x] + $aCntotc[$x];
                      $taCntct[$x] = $taCntct[$x] + $aCntotc[$x];
                      $SubT += $aCntc[$x];
                      $SubTot += $aCntotc[$x];
                      $GraT += $aCntc[$x];
                      $GraTot += $aCntotc[$x];   
                  }

              }

              if($aCntc[$x]==0){
                $resulcaptura='';
              }else{
                $resulcaptura=number_format($aCntc[$x],'0');
              }

          }

              $cTit2 = "<td align='center' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfon <a href=javascript:winuni('repoperativod.php?FecI=$FechaI&FecF=$FechaF&Institucion=$filtro5&Suc=$filtro3&capt=$Usrb&depto=$filtro7&etapa=$filtro9')>$Gfont <b>".$resulcaptura."</a></font></td><td align='right' bgcolor='$vist' 
              onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
              onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntotc[$x],'2')."</font></td>";
  // *************** I m p r e s i o n  **************
/*
          $cTit4 = '';
         // $SubT = 0;
         // $SubTot = 0;

          $tCnti = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $taCnti = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCntoti = array("0","0","0","0","0","0","0","0","0","0","0","0","0");
          $aCnti = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

          while($regi=mysql_fetch_array($Impresion)){
              if($Usrb==$regp[usuarioI]){ 

                  for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                      if (substr($i, 4, 2) == '13') {
                          $i = $i + 88;
                       }
                      
                      $x = substr($i, 4, 2) * 1;

                      $Fec = $regi[fecha];
                      $Pos = 0 + substr($Fec, 5, 2);
                      $aCnti[$Pos] = $regi[2];
                      $aCntoti[$Pos] = $aCntoti[$Pos]+($regi[precios]-$regi[descuentos]);
                      $contador=1;

                       $tCnti[$x] = $tCnti[$x] + $aCnti[$x];
                      $taCnti[$x] = $taCnti[$x] + $aCntoti[$x];
                      $SubT += $aCnti[$x];
                      $SubTot += $aCntoti[$x];
                      $GraT += $aCnti[$x];
                      $GraTot += $aCntoti[$x];       
                                    
                      $cTit4 = $cTit4 . "<td align='center' bgcolor='$vist' 
                      onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
                      onMouseOut=this.style.backgroundColor='$vist';>$Gfon ".number_format($aCnti[$x],'0')."</font></td><td align='right' bgcolor='$vist' 
                      onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
                      onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntoti[$x],'2')."</font></td>";

                  }

              }else{
                      $cTit4 = "<td align='center' bgcolor='$vist' 
                      onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
                      onMouseOut=this.style.backgroundColor='$vist';>$Gfon ".number_format($aCnti[$x],'0')."</font></td><td align='right' bgcolor='$vist' 
                      onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'
                      onMouseOut=this.style.backgroundColor='$vist';>$Gfont ".number_format($aCntoti[$x],'2')."</font></td>";
              }

          }

      */
              if($contador==1){

                if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

                if($Usrb==''){
                  $Usrb='SIN_REGISTRO';
                }else{
                  $Usrb=$Usrb;
                }
        
                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>
                <td align='center'>$Gfont <b>$Usrb</b>
                </td></td>";
                echo $cTit . $cTit3 . $cTit2;

                $nRng++;

              }

          $aCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

          mysql_data_seek($Atencion, 1);
          mysql_data_seek($Captura, 1);
          mysql_data_seek($Proceso, 1);
          //mysql_data_seek($Impresion, 1);

      }

    $cTit = '';
    $cTit3 = '';
    $cTit2 = '';
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
    
        $cTit = $cTit . "<td align='center'>$Gfon2 <b>".number_format($tCnt[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($taCntt[$x],'2')."</b></font></td>";

        $cTit3 = $cTit3 . "<td align='center'>$Gfon2 <b>".number_format($tCntp[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($taCnttp[$x],'2')."</b></font></td>";

        $cTit2 = $cTit2 . "<td align='center'>$Gfon2 <b>".number_format($tCntc[$x],'0')."</b></font></td><td align='right'>$Gfon2 <b>".number_format($taCntct[$x],'2')."</b></font></td>";
    }

    $PromedioG= $GraT/$Cmes;
    $VentaT+=$Venta;

    echo "<tr bgcolor='#a2b2de' aling='center'><td>$Gfon2 <b> &nbsp; Totales: Personal --> $nRng</b></td>";
    echo $cTit.$cTit3.$cTit2. "</tr>";

    echo "</table>";


    mysql_close();
?>

</body>

</html>
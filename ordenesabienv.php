<?php

session_start();

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require("lib/kaplib.php");

$link=conectarse();

$Usr=$check['uname'];

$busca=$_REQUEST[busca];

$Sucursal     =   $_REQUEST[Sucursal];
$Institucion  =   $_REQUEST[Institucion];
$Departamento=$_REQUEST[Departamento];

$FecI       =   $_REQUEST[FecI];
$FecF       =   $_REQUEST[FecF];

$Fechai     =   $FecI;
$Fechaf     =   $FecF;

$Titulo     =   $_REQUEST[Titulo];

$Fecha=date("Y-m-d");

$Hora=date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php
$InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
$NomI    = mysql_fetch_array($InstA);
if ($Sucursal <> '*') {
    $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";
    if($Institucion=='*'){ 
 		if($Departamento==''){           
			$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					   ot.horae,est.clavealt,ot.suc
					   FROM ot, cli, otd, est, med
					   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					   ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal'
					   order by ot.orden";
		}else{
			$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					   ot.horae,est.clavealt,ot.suc
					   FROM ot, cli, otd, est, med, dep
					   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					   ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' and dep.departamento=$Departamento and est.depto=$Departamento
					   order by ot.orden";
		}
    }else{
  	$Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0] Institucion: $Institucion - $NomI[nombre]";
		if($Departamento==''){           

			$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					   ot.horae,est.clavealt,ot.suc                           
					   FROM ot, cli, otd, est, med
					   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					   ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.institucion='$Institucion'
					   order by ot.orden";
		}else{
			
			$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					   ot.horae,est.clavealt,ot.suc                           
					   FROM ot, cli, otd, est, med, dep
					   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					   ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.institucion='$Institucion' and dep.departamento=$Departamento and est.depto=$Departamento
					   order by ot.orden";
		}
    }    
} else {

  $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0] Institucion: $Institucion - $NomI[nombre]";
            
  if($Institucion=='*'){
	  
	  if($Departamento==''){

		  $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					 otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					 ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					 ot.horae,est.clavealt,ot.suc 
					 FROM ot, cli, otd, est, med
					 WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					 ot.fecha <='$Fechaf' AND ot.medico=med.medico
					 order by ot.orden";
	  }else{
		  $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					 otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					 ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					 ot.horae,est.clavealt,ot.suc 
					 FROM ot, cli, otd, est, med, dep
					 WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					 ot.fecha <='$Fechaf' AND ot.medico=med.medico and dep.departamento=$Departamento and est.depto=$Departamento
					 order by ot.orden";
	  }

  }else{
	  
 	  if($Departamento==''){

		  $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					 otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					 ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					 ot.horae,est.clavealt,ot.suc
					 FROM ot, cli, otd, est, med
					 WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					 ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.institucion='$Institucion'
					 ORDER BY ot.orden";    
	  }else{
		  
		  $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
					 otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
					 ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
					 ot.horae,est.clavealt,ot.suc
					 FROM ot, cli, otd, est, med, dep
					 WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
					 ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.institucion='$Institucion' and dep.departamento=$Departamento and est.depto=$Departamento
					 ORDER BY ot.orden";    
	  }
  }     
}

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" width="187" height="61"> 
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio Clinico Duran</strong><br>
        <font size="1">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "$Titulo"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='5'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Suc - Inst - Ord - Fecha - Hora</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Medico</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</font></th>";		
    echo "<tr><td colspan='5'><hr noshade></td></tr>";
    $Orden=0;
    $Importe=0;
    $Descuento=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
    $Estudios=0;
    while ($rg = mysql_fetch_array($UpA)) {
            if ($Orden <> $rg[orden]) {
                if ($Orden <> 0) {
                    echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
                    $ImporteT+=$Importe;
                    $DescuentoT+=$Descuento;
                    $Importe = 0;
                    $Descuento = 0;
                    $Ordenes++;
                    $Med1 = "A";
                    $Rec = "B";
                    $Urge2 = 0;
                }
                $Rec = $rg[recepcionista];
                echo "<tr>";
                echo "<th align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif' color='#FF0000'>$rg[suc]</font>
				<font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;-&nbsp;$rg[institucion]&nbsp;-&nbsp;$rg[orden] &nbsp;&nbsp;&nbsp;$rg[fecha]&nbsp;&nbsp;&nbsp; $rg[hora]</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2] </font></th>";
				$Med = $rg[medico];
            	if ($Med1 <> $Med) {
					$Med1 = $Med;
					$Med2 = $rg[nombrec];
					$Med3 = $rg[medicon];
					if ($Med1 == "MD") {
						$Med2 = $Med3;
					}
				}
		    	 echo "<th align='center'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;$Med2</font></th>";
                echo "</tr>";
                $Orden = $rg[orden];
            }
						
            echo "<tr>";
            echo "<th>";
            echo "<th>";
            echo "<th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]&nbsp; </font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]</font></th>";
            	$Estudios++;
            $Med = $rg[medico];
            if ($Med1 <> $Med) {
                $Med1 = $Med;
                $Med2 = $rg[nombrec];
                $Med3 = $rg[medicon];
                if ($Med1 == "MD") {
                    $Med2 = $Med3;
                }
            }
    }

	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
   	 $Ordenes++;		
 	 $ImporteT+=$Importe;
 	 $DescuentoT+=$Descuento;	
    	
     echo "<tr><td colspan='6'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "</tr>"; 

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];

              
     echo "</table>";
	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=23&fechas=1&FecI=$FecI&FecF=$FecF'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
?>
</font></font> 
<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=23&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
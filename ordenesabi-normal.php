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
//$Sucursal     =   $Sucursal[0];
$sucursalt = $_REQUEST[sucursalt];
$sucursal0 = $_REQUEST[sucursal0];
$sucursal1 = $_REQUEST[sucursal1];
$sucursal2 = $_REQUEST[sucursal2];
$sucursal3 = $_REQUEST[sucursal3];
$sucursal4 = $_REQUEST[sucursal4];

$Institucion  =   $_REQUEST[Institucion];
$Depto        =   $_REQUEST[Depto];
$Recepcionista   =   $_REQUEST[Recepcionista];

$FecI       =   $_REQUEST[FecI];
$FecF       =   $_REQUEST[FecF];

$Fechai     =   $FecI;
$Fechaf     =   $FecF;

$Titulo     =   $_REQUEST[Titulo];
$Urgentes   =   $_REQUEST[Urgentes];

$Servicio   = $_REQUEST[Servicio];	//1.todos 2.Urgentes 3.Express

$DesctoS    = $_REQUEST[Descto];

$Fecha=date("Y-m-d");

$Hora=date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php
//if($Depto=="*"){
$InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
$NomI    = mysql_fetch_array($InstA);

if($Recepcionista=="*"){
	$Recep=" ";
}else{
	$Recep=" AND ot.recepcionista='$Recepcionista'";
}

if($Institucion=='LCD'){  
	$LCD=" AND ot.institucion<='20' AND ot.institucion<>'19' AND ot.institucion<>'18' AND ot.institucion<>'17' AND ot.institucion<>'16' 
	AND ot.institucion<>'15' AND ot.institucion<>'14' AND ot.institucion<>'13' AND ot.institucion<>'12' AND ot.institucion<>'11' 
	AND ot.institucion<>'9' AND ot.institucion<>'8' AND ot.institucion<>'7' AND ot.institucion<>'6' AND ot.institucion<>'5'
	 AND ot.institucion<>'4' AND ot.institucion<>'2'";
}else{
	if($Institucion=='SLCD'){
		$SLCD=" AND ot.institucion<>'20' AND ot.institucion<>'1' AND ot.institucion<>'3' AND ot.institucion<>'10'";
	}
		  
}

$Sucursal= "";

if($sucursalt=="1"){  

	$Sucursal="*";
	
}else{

	if($sucursal0=="1"){  
		$Sucursal= " AND ot.suc=0";
		$Sucursal2= "Administracion - ";
	}
	
	if($sucursal1=="1"){  
//		if($Sucursal==""){
			$Sucursal= $Sucursal . " AND ot.suc=1";
			$Sucursal2= $Sucursal2 . "Laboratorio - ";
//		}else{
//			$Sucursal= $Sucursal . " OR ot.suc=1";
//		}
	}
	
	if($sucursal2=="1"){
//		if($Sucursal==""){
			$Sucursal= $Sucursal . " AND ot.suc=2";
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
//		}else{
//			$Sucursal= $Sucursal . " OR ot.suc=2";
//		}
	}
	
	if($sucursal3=="1"){
//		if($Sucursal==""){
			$Sucursal= $Sucursal . " AND ot.suc=3";
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
//		}else{
//			$Sucursal= $Sucursal . " OR ot.suc=3";
//		}
	}
	
	if($sucursal4=="1"){
//		if($Sucursal==""){
			$Sucursal= $Sucursal . " AND ot.suc=4";
			$Sucursal2= $Sucursal2 . "Los Reyes - ";

//		}else{
//			$Sucursal= $Sucursal . " OR ot.suc=4";
//		}
	}
}

if ($Servicio == "2" OR $Servicio == "3") {  //Urgentes	
    if ($Servicio == "3") {
        $Serv = "Express";
    } else {
        $Serv = "Urgente";
    }

    $CiaA   = mysql_query("SELECT nombre FROM cia WHERE id='$Sucursal'");
    $Cia    = mysql_fetch_array($CiaA);

    if ($DesctoS == "S") {

        if ($Sucursal <> '*') {

            
            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: $Sucursal2";

            $cSql = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                     otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, 
                     ot.institucion,ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, 
                     ot.descuento as descto,est.estpropio,est.muestras,est.entord,ot.horae,est.clavealt,ot.suc
                     FROM ot, cli, otd, est, med
                     WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                     ot.fecha <='$Fechaf' AND ot.medico=med.medico $Sucursal AND ot.servicio='$Serv' $Recep
                     AND ot.descuento <> ' ' 
                     order by ot.orden";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                       est.entord,ot.horae,est.clavealt,ot.suc
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' 
                       AND ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.servicio='$Serv' AND ot.descuento <> ' ' $Recep
                       order by ot.orden";
        }
        
    } else {

        if ($Sucursal <> '*' ) {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: $Sucursal2";

            $cSql = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                      otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                      ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                      est.entord,ot.horae,est.clavealt,ot.suc
                      FROM ot, cli, otd, est, med
                      WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' 
                      AND ot.fecha <='$Fechaf' AND ot.medico=med.medico $Sucursal AND ot.servicio='$Serv' $Recep
                      order by ot.orden";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: * todas, Institucion: $Institucion - $NomI[nombre]";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                       est.entord,ot.horae,est.clavealt,ot.suc
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.servicio='$Serv' $Recep
					   order by ot.orden";
        }
    }
    
} elseif ($Servicio == 1) {  //Todos urgentes y de todo
  
    if ($DesctoS == "S") {

        if ($Sucursal <> '*') {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal2";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,ot.suc
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico $Sucursal AND ot.descuento <> ' ' $Recep
                       order by ot.orden";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf ";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,ot.suc
                       FROM ot, cli, otd, est, med
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.descuento <> ' ' $Recep
                       order by ot.orden";
        }
        
    } else {

        if ($Sucursal <> '*') {
            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal2";
            if($Institucion=='*'){            
                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,ot.suc
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico $Sucursal $Recep
                           order by ot.orden";
            }else{
				if($Institucion=='LCD'){  
					$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
							   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
							   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
							   ot.horae,est.clavealt,ot.suc                           
							   FROM ot, cli, otd, est, med
							   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
							   ot.fecha <='$Fechaf' AND ot.medico=med.medico $Sucursal $Recep
							   $LCD
							   order by ot.orden";                
				}else{
					if($Institucion=='SLCD'){  
						$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
								   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
								   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
								   ot.horae,est.clavealt,ot.suc                           
								   FROM ot, cli, otd, est, med
								   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
								   ot.fecha <='$Fechaf' AND ot.medico=med.medico $Sucursal $Recep
								   $SLCD
								   order by ot.orden";                
					}else{
			            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]";
						$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
								   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
								   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
								   ot.horae,est.clavealt,ot.suc                           
								   FROM ot, cli, otd, est, med
								   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
								   ot.fecha <='$Fechaf' AND ot.medico=med.medico $Sucursal AND ot.institucion='$Institucion' $Recep
								   order by ot.orden";    
					}
            	} 
			}
        } else {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]";
            
            if($Institucion=='*'){

                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,ot.suc 
                           FROM ot, cli, otd, est, med
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico $Recep					   
                           order by ot.orden";
            }else{
				if($Institucion=='LCD'){
	
					$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
							   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
							   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
							   ot.horae,est.clavealt,ot.suc 
							   FROM ot, cli, otd, est, med
							   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
							   ot.fecha <='$Fechaf' AND ot.medico=med.medico $Recep	
							   $LCD
							   order by ot.orden";
				}else{
					if($Institucion=='SLCD'){  
						$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
								   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
								   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
								   ot.horae,est.clavealt,ot.suc 
								   FROM ot, cli, otd, est, med
								   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
								   ot.fecha <='$Fechaf' AND ot.medico=med.medico $Recep	
								   $SLCD
								   order by ot.orden";
					}else{
			            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]";
						$cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
								   otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
								   ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
								   ot.horae,est.clavealt,ot.suc
								   FROM ot, cli, otd, est, med
								   WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
								   ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.institucion='$Institucion' $Recep
								   ORDER BY ot.orden";
					}
				}
            }     
        }
    }
}

//echo $cSql;

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
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Suc-Inst-Ord</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</font></th>";
	echo "<th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Precio</font></th>";		
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Desc. %</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</font></th>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
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
                    echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;</font></th>";
                    echo "<th align='left'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med2</font></th>";
                    echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'><strong><u>$Urgencia</u></strong></font></th>";
                    echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total OT: $</font></th>";
                    echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Importe, '2') . "</font></th>";
                    echo "<th align='center'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Descuento) . "</font></th>";
                    echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;" . number_format($Importe - $Descuento, '2') . "&nbsp; </font></th>";
                    echo "</tr>";

                    echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
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
                echo "<th align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif' color='#FF0000'>$rg[suc]</font><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;-&nbsp;$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2] &nbsp; $rg[numveces] vecs</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha Cap.: $rg[fecha]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fec.ent: $rg[fechae] ".substr($rg[horae],0,5)."</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora Cap.: $rg[hora]</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif' color='#CCCCCC'>$Rec</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descto]</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[servicio]</font></th>";
                echo "</tr>";
                $Orden = $rg[orden];
            }
			
			if($Institucion==94){
				$clavealterna=$rg[clavealt];
			}else{
				$clavealterna=' ';
			}
			
            echo "<tr>";
            echo "<th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]&nbsp; $clavealterna &nbsp;</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]&nbsp;&nbsp;$rg[estpropio]/$rg[muestras]/$rg[entord]</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'></font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[precio], '2') . "</font></th>";
            echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[descuento]) . "</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[8], '2') . "</font></th>";
            echo "</tr>";
            $Estudios++;
            $Importe+=$rg[precio];
            $Descuento+=($rg[precio] * ($rg[descuento] / 100));
            $Med = $rg[medico];
            if ($rg[estudio] == "URG") {
                $Urge = 1;
            } else {
                $Urge = 0;
            }
            $Urge2 = $Urge2 + $Urge;
            if ($Med1 <> $Med) {
                $Med1 = $Med;
                $Med2 = $rg[nombrec];
                $Med3 = $rg[medicon];
                if ($Med1 == "MD") {
                    $Med2 = $Med3;
                }
            }
            if ($rg[servicio] == "Urgente" or $Urge2 <> 0) {
                $Urgencia = "* * *  U R G E N C I A  * * * ";
            } else {
                $Urgencia = " ";
            }
    }

    echo "<tr>";
     echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;</font></th>";
  	 echo "<th align='left'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med2</font></th>";
     echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'><strong><u>$Urgencia</u></strong></font></th>";
	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total OT: $</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></th>";
   	 echo "<th align='center'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento)."</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($Importe-$Descuento,'2')."</font></th>";
     echo "</tr>"; 
    		
	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
   	 $Ordenes++;		
 	 $ImporteT+=$Importe;
 	 $DescuentoT+=$Descuento;	
    	
     echo "<tr><td colspan='8'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>GRAN TOTAL --> $</font></th>";
	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ImporteT,'2')."</font></th>";
   	 echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($DescuentoT)."</font></th>";
   	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($ImporteT-$DescuentoT,'2')."</font></th>";
     echo "</tr>"; 

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];

              
     echo "</table>";
	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=3&fechas=1&FecI=$FecI&FecF=$FecF'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
?>
</font></font> 
<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=3&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
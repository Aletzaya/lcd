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
$Depto        =   $_REQUEST[Depto];


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

            
            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";

            $cSql = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                     otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, 
                     ot.institucion,ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, 
                     ot.descuento as descto,est.estpropio,est.muestras,est.entord,ot.horae,est.clavealt,est.depto,
					 dep.departamento,dep.nombre, ot.receta
                     FROM ot, cli, otd, est, med, dep
                     WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                     ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.servicio='$Serv' 
                     AND ot.descuento <> ' ' and est.depto=dep.departamento
                     order by ot.orden";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                       est.entord,ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta
                       FROM ot, cli, otd, est, med, dep
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' 
                       AND ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.servicio='$Serv' AND ot.descuento <> ' ' and est.depto=dep.departamento
                       order by ot.orden";
        }
        
    } else {

        if ($Sucursal <> '*' ) {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";

            $cSql = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                      otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                      ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                      est.entord,ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta
                      FROM ot, cli, otd, est, med, dep
                      WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' 
                      AND ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.servicio='$Serv' and est.depto=dep.departamento
                      order by ot.orden";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Sucursal: * todas, Institucion: $Institucion";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, otd.status, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,
                       est.entord,ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta
                       FROM ot, cli, otd, est, med, dep
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.servicio='$Serv' and est.depto=dep.departamento
                       order by ot.orden";
        }
    }
    
} elseif ($Servicio == 1) {  //Todos urgentes y de todo
    
    if ($DesctoS == "S") {

        if ($Sucursal <> '*') {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta
                       FROM ot, cli, otd, est, med, dep
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.descuento <> ' ' and est.depto=dep.departamento
                       order by ot.orden";
        } else {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf";
            $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                       otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                       ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto,est.estpropio,est.muestras,est.entord,
                       ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta
                       FROM ot, cli, otd, est, med, dep
                       WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                       ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.descuento <> ' ' and est.depto=dep.departamento
                       order by ot.orden";
        }
        
    } else {

        if ($Sucursal <> '*') {
            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0]";
            if($Institucion=='*'){            
                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta
                           FROM ot, cli, otd, est, med, dep
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' and est.depto=dep.departamento
                           order by ot.orden";
            }else{
                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta                           
                           FROM ot, cli, otd, est, med, dep
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.suc='$Sucursal' AND ot.institucion='$Institucion' and est.depto=dep.departamento
                           order by ot.orden";                
            }    
        } else {

            $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal $Cia[0] Institucion: $Institucion";
            
            if($Institucion=='*'){

                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta 
                           FROM ot, cli, otd, est, med, dep
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico and est.depto=dep.departamento
                           order by ot.orden";

            }else{

                $cSql   = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
                           otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, 
                           ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto, cli.numveces,est.estpropio,est.muestras,est.entord,
                           ot.horae,est.clavealt,est.depto,dep.departamento,dep.nombre, ot.receta
                           FROM ot, cli, otd, est, med, dep
                           WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio AND ot.fecha>='$Fechai' and
                           ot.fecha <='$Fechaf' AND ot.medico=med.medico AND ot.institucion='$Institucion' and est.depto=dep.departamento
                           ORDER BY ot.orden";                                
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
    echo "<tr><td colspan='13'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No.</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No. Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Receta</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Nombre del Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>No. de Ficha</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Servicio</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Clave</font></th>";		
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Descripcion</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Unid. Med.</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Cantidad</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>P. Unit.</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Subtotal</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>IVA</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Total</font></th>";
    echo "<tr><td colspan='13'><hr noshade></td></tr>";
    $Orden=0;
    $Importe=0;
    $Descuento=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
    $Ordenes2=1;
    $Estudios=0;
	$Ordenes4=1;
    while ($rg = mysql_fetch_array($UpA)) {
            if ($Orden <> $rg[orden]) {
                if ($Orden <> 0) {
/*                    echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;</font></th>";
                    echo "<th align='left'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med2</font></th>";
                    echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'><strong><u>$Urgencia</u></strong></font></th>";
                    echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total OT: $</font></th>";
                    echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Importe, '2') . "</font></th>";
                    echo "<th align='center'><hr><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Descuento) . "</font></th>";
                    echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;" . number_format($Importe - $Descuento, '2') . "&nbsp; </font></th>";
                    echo "</tr>";

                    echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
*/                    $ImporteT+=$Importe;
                    $DescuentoT+=$Descuento;
                    $Importe = 0;
                    $Descuento = 0;
                    $Ordenes2++;
                    $Ordenes4++;
                    $Med1 = "A";
                    $Rec = "B";
                    $Urge2 = 0;
                }
               $Rec = $rg[recepcionista];
/*                echo "<tr>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$Ordenes2</font></th>";
                echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2] </font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp; $rg[3]</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
                echo "</tr>";
*/                $Orden = $rg[orden];
            }
			$Unitario=$rg[8]/1.16;
			$IVA=$Unitario*0.16;
			if($Ordenes2<>$Ordenes3){
				$Consecutivo=$Ordenes4;
				$Institucion2=$Institucion;
				$Orden3=$rg[orden];
				$Paciente=$rg[2];
				$Depto2=$rg[3];
				$Guion='-';
			}else{
				$Consecutivo=' ';
				$Institucion2=' ';
				$Orden3=' ';
				$Paciente=' ';
				$Depto2=' ';
				$Guion=' ';
			}				
            echo "<tr>";
            echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$Consecutivo</font></th>";
            echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$Institucion2&nbsp;$Guion&nbsp;$Orden3</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[receta]</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $Paciente</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp; $Depto2</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[nombre] &nbsp;</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[clavealt] &nbsp;</font></th>";
            echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]</font></th>";
            echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Servicio</font></th>";
            echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>1</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Unitario, '2') . "</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($Unitario, '2') . "</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($IVA, '2') . "</font></th>";
            echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>" . number_format($rg[8], '2') . "</font></th>";
            echo "</tr>";
			$Ordenes2=$Ordenes3;
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

/*    echo "<tr>";
     echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;</font></th>";
  	 echo "<th align='left'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med2</font></th>";
     echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'><strong><u>$Urgencia</u></strong></font></th>";
	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total OT: $</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></th>";
   	 echo "<th align='center'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento)."</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($Importe-$Descuento,'2')."</font></th>";
     echo "</tr>"; 
    		
	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
*/   	 $Ordenes++;		
 	 $ImporteT+=$Importe;
 	 $DescuentoT+=$Descuento;	
    	
    echo "<tr><td colspan='14'><hr noshade></td></tr>";
     
   	 echo "<tr>";
     echo "<th>";
     echo "<th>";
     echo "<th>";
     echo "<th>";
     echo "<th>";
     echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes2</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>GRAN TOTAL --> $</font></th>";
     echo "<th>";
     echo "<th>";
     echo "<th>";
	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
   	 echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
   	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($ImporteT-$DescuentoT,'2')."</font></th>";
     echo "</tr>"; 

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];

              
     echo "</table>";
	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=21&fechas=1&FecI=$FecI&FecF=$FecF'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
?>
</font></font> 
<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=21&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
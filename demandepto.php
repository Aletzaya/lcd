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
	$sucursal5 = $_REQUEST[sucursal5];
	$sucursal6 = $_REQUEST[sucursal6];


  $Institucion=$_REQUEST[Institucion];

  $FecI=$_REQUEST[FecI];

  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

  $Departamento=$_REQUEST[Departamento];

  $Servicio=$_REQUEST[Servicio];

  if($Servicio=="*"){
  	  $Servicio=" ";
  }else{
  	$Servicio=" and ot.servicio='$Servicio'";
  }

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
?>
<html>
<head>
<title>Sistema de Laboratoriio clinico</title>
</head>
<body>

<?php
  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="*";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}

		if($sucursal5=="1"){
			$Sucursal2= $Sucursal2 . "Camarones - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=5";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=5";
			}
		}
	}

if(strlen($Departamento)>0){
	if(strlen($Institucion)>0){
        if ($Sucursal <> '*') {
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Depto. Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, dep.departamento
			FROM otd, est, ot, dep
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and ot.institucion=$Institucion and dep.departamento=$Departamento and est.depto=$Departamento $Servicio AND ($Sucursal)
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(distinct ot.orden) from ot,dep,est,otd where otd.estudio=est.estudio and ot.orden=otd.orden and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.institucion=$Institucion and est.depto=$Departamento and dep.departamento=$Departamento AND ($Sucursal)");
			$registro2=mysql_fetch_array($registro3);
		}else{
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Depto. Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, dep.departamento
			FROM otd, est, ot, dep
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and ot.institucion=$Institucion and dep.departamento=$Departamento and est.depto=$Departamento $Servicio
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(distinct ot.orden) from ot,dep,est,otd where otd.estudio=est.estudio and ot.orden=otd.orden and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.institucion=$Institucion and est.depto=$Departamento and dep.departamento=$Departamento");
			$registro2=mysql_fetch_array($registro3);
		}
	}else{
        if ($Sucursal <> '*') {
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, dep.departamento
			FROM otd, est, ot, dep
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento $Servicio AND ($Sucursal)
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(distinct ot.orden) from ot,dep,est,otd where otd.estudio=est.estudio and ot.orden=otd.orden and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and est.depto=$Departamento and dep.departamento=$Departamento AND ($Sucursal)");
			$registro2=mysql_fetch_array($registro3);
		}else{
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, dep.departamento
			FROM otd, est, ot, dep
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento $Servicio
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(distinct ot.orden) from ot,dep,est,otd where otd.estudio=est.estudio and ot.orden=otd.orden and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and est.depto=$Departamento and dep.departamento=$Departamento");
			$registro2=mysql_fetch_array($registro3);
		}
	}
}else{
	if(strlen($Institucion)>0){
        if ($Sucursal <> '*') {
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Depto. Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden
			FROM otd, est, ot
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and ot.institucion=$Institucion $Servicio AND ($Sucursal)
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(ot.orden) from ot where ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.institucion=$Institucion AND ($Sucursal)");
			$registro2=mysql_fetch_array($registro3);
		}else{
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Depto. Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden
			FROM otd, est, ot
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and ot.institucion=$Institucion $Servicio
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(ot.orden) from ot where ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.institucion=$Institucion");
			$registro2=mysql_fetch_array($registro3);
		}
	}else{
        if ($Sucursal <> '*') {
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden
			FROM otd, est, ot
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden $Servicio AND ($Sucursal)
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(ot.orden) from ot where ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' AND ($Sucursal)");
			$registro2=mysql_fetch_array($registro3);
		}else{
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(distinct ot.orden), est.depto, est.subdepto, otd.orden
			FROM otd, est, ot
			WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden $Servicio
			GROUP BY est.depto, est.subdepto, otd.estudio";
	
			$registro3=mysql_query("select count(ot.orden) from ot where ot.fecha>='$Fechai' and ot.fecha<='$Fechaf'");
			$registro2=mysql_fetch_array($registro3);
		}
	}
}


/** }else{
    $Titulo="Demanda de estudios del Depto: $Depto del $Fechai al $Fechaf";
	$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden) as numero , ( otd.precio * ( 1 - ( otd.descuento /100  ) ) )
	FROM otd, est, ot
	WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and est.depto='$Depto'
	and ot.orden=otd.orden
	GROUP BY otd.estudio";
    $OtNum="select count(orden) from ot where fecha='$Fecha' and recepcionista='$Recepcionista'";
}
**/
$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr>
    <td><div align='center'>
        <font size="4" face="Arial, Helvetica, sans-serif"><strong>Laboratorio Clinico Duran</strong></font><br>
        <font size="2"><?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Demanda de estudios del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]</p>";?>
        <font size="2"><?php // echo "$Titulo"; ?>
        </div>
    </td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
        $FechaAux=strtotime($Fecha);
        $nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
        $FechaAnt=date("Y-m-d",$nDias);
        echo "<table align='center' width='90%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Depto</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Subdepto</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Estudio</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Descripcion</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Precio</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>#Estudios</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Sub-total</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Descuentos</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>I m p o r t e</font></th>";

        $Subtotal=0;
		$Total=0;
		$Descuentos=0;
		$Noveces=0;
		$subdep=" ";
		$orden=0;
		$nordenes=0;

        while($registro=mysql_fetch_array($UpA)) {
    		if($subdep==$registro[8]){
				$departamento=" ";
				$subdepartamento=" ";
			}else{
				$departamento=$registro[7];
				$subdepartamento=$registro[8];
		        echo "<tr><td></td><td></td><td></td><td></td><td colspan='5'><hr></td></tr>";
	    		if($Noveces<>0){
			        echo "<tr>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>T o t a l</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
					echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces2)."</strong></font></td>";
			        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Subtotal2,'2')."</strong></font></td>";
			        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuentos2,'2')."</strong></font></td>";
			        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Total2,'2')."</strong></font></td>";
			        echo "</tr>";
			        echo "<tr><td colspan='9'><hr noshade></td></tr>";
        		     $Noveces3=$Noveces3+$Noveces2;
				     $Nordenes3=$Nordenes3+$Nordenes2;
		             $Descuentos3=$Descuentos3+$Descuentos2;
		             $Subtotal3=$Subtotal3+$Subtotal2;
		             $Total3=$Total3+$Total2;
		             $Noveces2=0;
		             $Nordenes2=0;
        		     $Descuentos2=0;
		             $Subtotal2=0;
        		     $Total2=0;

				}
			}

             ?>
</font></font>

<tr>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><strong><?php echo $departamento; ?></strong></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><strong><?php echo $subdepartamento; ?></strong></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $registro[0]; ?></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $registro[1]; ?></font></td>
  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[2],"2"); ?></font></td>
  <td align='center'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[3]); ?></font></td>
  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[4],"2"); ?></font></td>
  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[5],"2"); ?></font></td>
  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[4]-$registro[5],"2"); ?></font></td>
</tr>

<font size="1" face="Arial, Helvetica, sans-serif">
<?php
             $Noveces2=$Noveces2+$registro[3];
		     $Nordenes2=$Nordenes2+$registro[6];
             $Descuentos2=$Descuentos2+$registro[5];
             $Subtotal2=$Subtotal2+$registro[4];
             $Total2=$Total2+($registro[4]-$registro[5]);

             $Noveces=$Noveces2+$registro[3];
		     $Nordenes=$Nordenes2+$registro[6];
             $Descuentos=$Descuentos2+$registro[5];
             $Subtotal=$Subtotal2+$registro[4];
             $Total=$Total2+($registro[4]-$registro[5]);
			 $Cuenta=$Cuenta+$registro[6];
			 $subdep=$registro[8];
		

        }//fin while
   		     $Noveces3=$Noveces3+$Noveces2;
		     $Nordenes3=$Nordenes3+$Nordenes2;
             $Descuentos3=$Descuentos3+$Descuentos2;
             $Subtotal3=$Subtotal3+$Subtotal2;
             $Total3=$Total3+$Total2;

        echo "<tr><td></td><td></td><td></td><td></td><td colspan='5'><hr></td></tr>";
        echo "<tr>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>T o t a l </font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
		echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces2)."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Subtotal2,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuentos2,'2')."</strong></font></td>";
        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Total2,'2')."</strong></font></td>";
        echo "</tr>";

        echo "<tr></tr>";
        echo "<tr></tr>";
        echo "<tr></tr>";
        echo "<tr></tr>";

        echo "<tr>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'> No. Ordenes : ".number_format($registro2[0])."</font></td>";
        echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>T o t a l &nbsp;&nbsp;  G r a l .</font></td>";
        echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'> - </font></td>";
        echo "<td align='center' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Noveces3)."</strong></font></td>";
        echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Subtotal3,'2')."</strong></font></td>";
        echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Descuentos3,'2')."</strong></font></td>";
        echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Total3,'2')."</strong></font></td>";
        echo "</tr>";

        echo "</table>";
        echo "<br>";
		echo "<br>";
		echo "<br>";
        echo "<table align='center' width='75%' border='0' cellspacing='1' cellpadding='0'>";

       	echo "<tr>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Depto</font></th>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Nombre</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>No. Estudios</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> - </font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Sub-total</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> &nbsp; Desctos </font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>I m p o r t e</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> - </font></th>";
       	echo "</tr>";
        echo "<tr><td colspan='8'><hr noshade></td></tr>";

        $DeptoA=mysql_query("select departamento,nombre from dep order by departamento",$link);
        while($rg=mysql_fetch_array($DeptoA)) {
			if(strlen($Departamento)>0){
				if(strlen($Institucion)>0){
					if ($Sucursal <> '*') {
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot, dep
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento AND ($Sucursal)
						and ot.institucion=$Institucion and est.depto='$rg[0]' group by est.depto";
					}else{
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot, dep
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento
						and ot.institucion=$Institucion and est.depto='$rg[0]' group by est.depto";
					}
				}else{
					if ($Sucursal <> '*') {
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot, dep
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento AND ($Sucursal)
						and est.depto='$rg[0]' group by est.depto";
					}else{
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot, dep
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento
						and est.depto='$rg[0]' group by est.depto";
					}
				}
			}else{
				if(strlen($Institucion)>0){
					if ($Sucursal <> '*') {
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden AND ($Sucursal)
						and ot.institucion=$Institucion and est.depto='$rg[0]' group by est.depto";
					}else{
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
						and ot.institucion=$Institucion and est.depto='$rg[0]' group by est.depto";
					}
				}else{
					if ($Sucursal <> '*') {						
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden AND ($Sucursal)
						and est.depto='$rg[0]' group by est.depto";
					}else{
						$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
						FROM otd, est, ot
						WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
						and est.depto='$rg[0]' group by est.depto";
					}
				}
			}

            $dmA=mysql_query($cSql,$link);
            if($dm=mysql_fetch_array($dmA)){
  	      		echo "<tr>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[0]."</font></th>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[1]."</font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[0],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format(($dm[0]/$Noveces3)*100,'0')." % </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1]-$dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format((($dm[1]-$dm[2])/$Total3)*100,'0')." % </font></th>";
        		echo "</tr>";
        	}
        }
        echo "<tr><td colspan='8'><hr noshade></td></tr>";
        echo "</table>";
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=9&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
?>
</font>
<div align="left">
<form name="form1" method="post" action="pidedatos.php?cRep=9&fechas=1&FecI=$FecI&FecF=$FecF">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
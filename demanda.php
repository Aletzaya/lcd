s<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $Institucion=$_REQUEST[Institucion];

  $Fechai=$_REQUEST[Fechai];

  $Fechaf=$_REQUEST[Fechaf];

  $Titulo=$_REQUEST[Titulo];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

  $reporte=$_REQUEST[reporte];
?>
<html>
<head>
<title>Sistema de Laboratoriio clinico</title>
</head>
<body>

<?php

	if(strlen($Institucion)>0){
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
    	$Titulo="Demanda de estudios del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
		$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(ot.orden), est.clavealt
		FROM otd, est, ot
		WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and ot.institucion=$Institucion
		GROUP BY otd.estudio";
	}else{
    	$Titulo="Demanda de estudios del $Fechai al $Fechaf";
		$cSql="SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), count(ot.orden), est.clavealt
		FROM otd, est, ot
		WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
		GROUP BY otd.estudio";
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
    	    <font size="2"><?php echo "$Titulo"; ?>
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
        	echo "<tr><td colspan='8'><hr noshade></td></tr>";
	        echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Estudio</font></th>";
    	    echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Descripcion</font></th>";
    	    echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Clave</font></th>";
        	echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Precio</font></th>";
	        echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>#Estudios</font></th>";
    	    echo "<th><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Sub-total</font></th>";
        	echo "<th><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Descuentos</font></th>";
	        echo "<th><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>I m p o r t e</font></th>";
    	    echo "<tr><td colspan='8'><hr noshade></td></tr>";
	        $Subtotal=0;
			$Total=0;
			$Descuentos=0;
			$Noveces=0;
    	    while($registro=mysql_fetch_array($UpA)) {
       ?>
	</font></font>
	<tr>
	  <td><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $registro[0]; ?></font></td>
	  <td><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $registro[1]; ?></font></td>
	  <td align='center'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo $registro[clavealt]; ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[2],"2"); ?></font></td>
	  <td align='center'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[3]); ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[4],"2"); ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[5],"2"); ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><?php echo number_format($registro[4]-$registro[5],"2"); ?></font></td>
	</tr>
	<font size="1" face="Arial, Helvetica, sans-serif">
	<?php
             $Noveces=$Noveces+$registro[3];
             $Descuentos=$Descuentos+$registro[5];
             $Subtotal=$Subtotal+$registro[4];
             $Total=$Total+($registro[4]-$registro[5]);
			 $Cuenta=$registro[6];
        }//fin while
        echo "<tr>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>".$Cuenta."</font></td>";
        echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>T o t a l e s </font></td>";
        echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
        echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
		echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces)."</strong></font></td>";
        echo "<td align='right'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Subtotal,'2')."</strong></font></td>";
        echo "<td align='right'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuentos,'2')."</strong></font></td>";
        echo "<td align='right'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Total,'2')."</strong></font></td>";
        echo "</tr>";

        echo "</table>";
        echo "<br>";
		echo "<br>";
		echo "<br>";
        echo "<table align='center' width='75%' border='0' cellspacing='1' cellpadding='0'>";

       	echo "<tr>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Depto</font></th>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Nombre</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>No.Estudios</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> - </font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Sub-total</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> &nbsp; Desctos </font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>I m p o r t e</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> - </font></th>";
       	echo "</tr>";
        echo "<tr><td colspan='9'><hr noshade></td></tr>";

        $DeptoA=mysql_query("select departamento,nombre from dep order by departamento",$link);
        while($rg=mysql_fetch_array($DeptoA)) {
			if(strlen($Institucion)>0){
	        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100))
		    	FROM otd, est, ot
		    	WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
		    	and ot.institucion=$Institucion and est.depto='$rg[0]' group by est.depto";
			}else{
	        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100))
		    	FROM otd, est, ot
		    	WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
		    	and est.depto='$rg[0]' group by est.depto";
			}
            $dmA=mysql_query($cSql,$link);
            if($dm=mysql_fetch_array($dmA)){
  	      		echo "<tr>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[0]."</font></th>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[1]."</font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[0],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format(($dm[0]/$Noveces)*100,'0')." % </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1]-$dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format((($dm[1]-$dm[2])/$Total)*100,'0')." % </font></th>";
        		echo "</tr>";
        	}
        }
        echo "<tr><td colspan='8'><hr noshade></td></tr>";
        echo "</table>";
		echo "<div align='center'>";
		echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=2'>";
		echo "Regresar</a></font>";
		echo "</div>";
	?>
</font>
<div align="left">
<form name="form1" method="post" action="menu.php">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
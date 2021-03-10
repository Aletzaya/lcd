<?php

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
<title>Sistema de Laboratorio clinico</title>
</head>
<body>

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

	$UpA=mysql_query($cSql,$link);

  $FechaAux=strtotime($Fecha);
	$nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
  $FechaAnt=date("Y-m-d",$nDias);
  echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
	echo "<tr><td colspan='8'><hr noshade></td></tr>";
  echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Estudio</font></th>";
  echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Descripcion</font></th>";
  echo "<tr><td colspan='8'><hr noshade></td></tr>";
  $Subtotal=0;
  $Total=0;
  $Descuentos=0;
  $Noveces=0;
  while($registro=mysql_fetch_array($UpA)) {
  	echo "<tr>";
    echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>$registro[estudio]</font></td>";
    echo "<td><font size='1' face='Arial, Helvetica, sans-serif'>$registro[descripcion]</font></td>";

    $cSqlEst="SELECT *
    FROM conest
    WHERE conest.estudio = '$registro[estudio]' ";

    $UpAEst=mysql_query($cSqlEst,$link);

    while($regest=mysql_fetch_array($UpAEst)) {

      echo "<td bgcolor='#336699'><font size='1' face='Arial, Helvetica, sans-serif'>$regest[conest]</font></td>";

    }

    echo "</tr>";

    mysql_data_seek($UpAEst, 0);

    $Noveces=$Noveces+$registro[3];
    $Descuentos=$Descuentos+$registro[5];
    $Subtotal=$Subtotal+$registro[4];
    $Total=$Total+($registro[4]-$registro[5]);
    $Cuenta=$registro[6];

  }//fin while

  echo "<tr>";
  echo "<td><font size='1' face='Arial, Helvetica, sans-serif'> </font></td>";
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
<div align="left">
<form name="form1" method="post" action="menu.php">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
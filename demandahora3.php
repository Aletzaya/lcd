<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();
  
  $Sucursal = $_REQUEST[Sucursal];

  $Institucion=$_REQUEST[Institucion];

  $FecI=$_REQUEST[FecI];

  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

  $Departamento=$_REQUEST[Departamento];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>

<?php
        
$Titulo="Demanda de estudios del $Fechai al $Fechaf";
$cSql="SELECT otd.estudio, est.descripcion, est.depto, est.subdepto, otd.orden, ot.hora, count(distinct otd.estudio) as cant, date_format(ot.hora, '%H') as hora
FROM otd, est, ot
WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden Group by hora order by hora, otd.estudio";


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
echo "<table align='center' width='90%' border='0' cellspacing='1' cellpadding='0'>";
echo "<tr><td colspan='5'><hr></td></tr>";	
echo "<tr>";
echo "<td align='center'><strong><font size='2' face='Arial, Helvetica, sans-serif'>Horario</font></td>";
echo "<td align='center'><strong><font size='2' face='Arial, Helvetica, sans-serif'>Descripcion</font></td>";
echo "<td align='center'><strong><font size='2' face='Arial, Helvetica, sans-serif'>Cantidad</font></td>";
echo "<td align='center'><strong><font size='2' face='Arial, Helvetica, sans-serif'>Importe</font></td>";
echo "<td align='center'><strong><font size='2' face='Arial, Helvetica, sans-serif'>Total</font></td>";
echo "</tr>";
echo "<tr><td colspan='5'><hr></td></tr>";	

$Hora4='';
$contador=1;
$contadorxh=0;
$est='';
$total='ini';

while($registro=mysql_fetch_array($UpA)) {
	$Hora3=substr($registro[hora],0,2);
	if($Hora3==$Hora4){
		$Hora3='';
		$total='';
	}else{
		$Hora3=$Hora3.' Hrs.';
		if($total=='ini'){
			$total='';	
		}else{
			$total="<tr bgcolor='#6d9ca4'><td colspan=2 align=right><font size='2' face='Arial, Helvetica, sans-serif'><b>Totales:</b></font></td><td align=center><font size='2' face='Arial, Helvetica, sans-serif'><b>$contadorxh</b></font></td><td></td><td></td></tr>";	
			$contadorxh=0;
		}
	}


	if($registro[estudio]==$est){
		$contador=$contador+1;
	}else{

		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='CDCDFA';}    //El resto de la division;

		echo "$total";	
		echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='6d9ca4';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
	    echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$Hora3</font></td>";
	    echo "<td align='left' width='30%'><font size='1' face='Arial, Helvetica, sans-serif'> $registro[estudio] - $registro[descripcion]</font></td>";
		echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$contador</font></td>";
		echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'></font></td>";
	    echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
		echo "</tr>";
		$contadorxh=$contadorxh+$contador;
		$contador=1;
		$nRng++;	
	}
	$est=$registro[estudio];
	$Hora4=substr($registro[hora],0,2);
}

echo "<tr bgcolor='#6d9ca4'><td colspan=2 align=right><font size='2' face='Arial, Helvetica, sans-serif'><b>Totales:</b></font></td><td align=center><font size='2' face='Arial, Helvetica, sans-serif'><b>$contadorxh</b></font></td><td></td><td></td></tr>";
echo "</table>";

       
        echo "<tr><td colspan='8'><hr noshade></td></tr>";
        echo "</table>";
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=8&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
?>
</font>
<div align="left">
<form name="form1" method="post" action="pidedatos.php?cRep=8&fechas=1&FecI=$FecI&FecF=$FecF">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
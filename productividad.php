<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");
  
    date_default_timezone_set("America/Mexico_City");

  $link=conectarse();

  $Usr=$check['uname'];

  $FecI=$_REQUEST[FecI];

  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $HoraI=$_REQUEST[HoraI];

  $HoraF=$_REQUEST[HoraF];

  $Titulo=$_REQUEST[Titulo];
	
  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
  
  $SucursalD   = $_REQUEST[SucursalDe];

  if($SucursalD=="*"){
  	$SucursalDe="";
  }else{
  	$SucursalDe="and ot.suc=$SucursalD";
  }
  
  $Personal = $_REQUEST[personal];

  if($Personal=="*"){
  	$Personal2="";
  }else{
  	$Personal2="and maqdet.usrrec='$Personal'";
  }

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php

$cSql="SELECT maqdet.orden,maqdet.estudio,maqdet.mint,maqdet.fenv,maqdet.henv,maqdet.usrrec,maqdet.obsrec,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion
FROM maqdet, ot, cli, est
WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.frec>='$FecI' and
maqdet.frec <='$FecF' $SucursalDe $Personal2
order by maqdet.orden";


$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" alt="" width="187" height="61"> 
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio 
        Clinico Duran</strong><br><font size="2">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="2"><?php echo "Relacion de Productividad del $Fechai al $Fechaf Sucursal de: $SucursalD  De: $Personal"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="2">
<?php

echo "<br><table align='center' width='98%' border='1' cellspacing='0' cellpadding='0'>";
echo "<tr>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Suc</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Orden</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Paciente</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Estudios</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Descripcion</font></td>";	
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Observaciones</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Personal</font></td>";
echo "</tr>";
while($rg=mysql_fetch_array($UpA)) {
	echo "<tr>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[suc]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion] - $rg[orden]</font></td>";
	echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[nombrec]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]</font></td>";	
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[obsrec]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[usrrec]</font></td>";
	echo "</tr>";
	$Estudios++;
}

echo "<tr><td align='center' colspan='7'><font size='1' face='Arial, Helvetica, sans-serif'>No. de estudios: $Estudios</font></td></tr>";
echo "</table>";

echo "<br><br><table align='center' width='98%' border='0' cellspacing='0' cellpadding='0'>";
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatosventana.php?cRep=29&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
echo "<div align='left'>";
$FecI=$_REQUEST[FecI];
$FecF=$_REQUEST[FecF];

echo "<form name='form1' method='post' action='pidedatosventana.php?cRep=29&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";
echo "</div>";
?>
</body>
</html>
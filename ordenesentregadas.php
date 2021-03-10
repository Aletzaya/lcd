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

  $Departamento=$_REQUEST[Departamento];

  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php
if(strlen($Institucion)>0){
	$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
	$Nombre=mysql_fetch_array($NomA);
	$Titulo="Relacion de Ordenes de trabajo Entregadas del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
    $cSql="SELECT ot.institucion, ot.orden, cli.nombrec, ot.fecha, ot.hora, ot.recepcionista, ot.entfec, ot.enthra, ot.entusr, ot.recibio
	FROM ot, cli
	WHERE ot.cliente = cli.cliente AND ot.fecha>='$Fechai' and ot.fecha <='$Fechaf' and ot.status='Entregada' 
	and ot.institucion='$Institucion'
	order by ot.orden";
}else{
	$Titulo="Relacion de Ordenes de trabajo Entregadas del $Fechai al $Fechaf";
    $cSql="SELECT ot.institucion, ot.orden, cli.nombrec, ot.fecha, ot.hora, ot.recepcionista, ot.entfec, ot.enthra, ot.entusr, ot.recibio
	FROM ot, cli
	WHERE ot.cliente = cli.cliente AND ot.fecha>='$Fechai' and ot.fecha <='$Fechaf' and ot.status='Entregada' 
	order by ot.orden";
}

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" alt="" width="187" height="61"> 
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio 
        Clinico Duran</strong><br><font size="1">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "$Titulo"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<?php
  echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
  echo "<tr><td colspan='5'><hr noshade></td></tr>";
  echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</font></th>";
  echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
  echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Captura</font></th>";
  echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Entrega</font></th>";		
  echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recibio</font></th>";		
  echo "<tr><td colspan='5'><hr noshade></td></tr>";

  $Ordenes=0;
  while($rg=mysql_fetch_array($UpA)) {
  	echo "<tr>";
  	echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
  	echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[nombrec]</font></th>";
  	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[recepcionista] <br> Fecha: $rg[fecha] &nbsp;&nbsp;&nbsp; $rg[hora] </font></th>";
  	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[entusr] <br> Fecha: $rg[entfec] &nbsp;&nbsp;&nbsp; $rg[enthra] </font></th>";
  	echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[recibio]</font></th>";
  	echo "</tr>";
	echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
	$Ordenes++;
  }
    
echo "<tr>";
echo "<th>";
echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
echo "</tr>"; 

$FecI=$_REQUEST[FecI];
$FecF=$_REQUEST[FecF];

              
echo "</table>";
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=13&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
?>

<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=13&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
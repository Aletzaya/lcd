<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  require ("config.php");
  
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
  
  $SucursalP = $_REQUEST[Proveedor];

  if($SucursalP=="*"){
  	$SucursalPara="";
  }else{
  	$SucursalPara="and maqdet.mext='$SucursalP'";
  }

  $personal = $_REQUEST[personal];

  if($personal=="*"){
    $personale="";
  }else{
    $personale="and maqdet.usrenvext='$personal'";
  }
  
?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<script language="JavaScript1.2">
  function Ventana(url){
     window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=800,height=350,left=100,top=150")
  }
</script>
<body>
<?php

$cSql="SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,maqdet.usrenvext,est.costo
FROM maqdet, ot, cli, est
WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.fenvext>='$FecI' and
maqdet.fenvext <='$FecF' AND maqdet.henvext >='$HoraI' AND maqdet.henvext <='$HoraF' $SucursalDe $SucursalPara $personale
order by maqdet.orden";


$UpA=mysql_query($cSql,$link);

?>
<table width="95%" border="0" align="center">
  <tr> 
    <td width="25%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" alt="" width="187" height="61"> 
      </div></td>
    <td width="75%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio 
        Clinico Duran</strong><br><font size="2">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="2"><?php echo "Relacion de Envio de muestras del $Fechai al $Fechaf Sucursal de: $SucursalD Para Sucursal: $SucursalP"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="2">
<?php

echo "<br><table align='center' width='95%' border='1' cellspacing='1' cellpadding='0'>";
echo "<tr bgcolor='#a2b2de' height='20'>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Suc</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Orden</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Paciente</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Estudios</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Descripcion</b></font></td>";	
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Observaciones</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Para</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Usr</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Costo</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Precio</b></font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'><b>Dif</b></font></td>";
echo "</tr>";
while($rg=mysql_fetch_array($UpA)) {

    $MqlA2=mysql_query("select * from mql where mql.id=$rg[mext]",$link);
    $Mql2=mysql_fetch_array($MqlA2);

    if($rg[costo]==0){
      $costo='';
    }else{
      $costo=$rg[costo];
    }

    $Ord2=mysql_query("select precio,descuento from otd where otd.orden=$rg[orden] and otd.estudio='$rg[estudio]'",$link);
    $Ord=mysql_fetch_array($Ord2);
    $precio=$Ord[precio]-(($Ord[precio]*$Ord[descuento])/100);

  if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

  if(($precio-$rg[costo])<=0){
    $color='red';
  }else{
    $color='black';
  }

	echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo'; height='20'>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[suc]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion] - $rg[orden]</font></td>";
	echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[nombrec]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]</font></td>";	
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[obsenv]</font></td>";
  echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$Mql2[alias]</font></td>";
  echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[usrenvext]</font></td>";
  echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($costo,'2')."</font></td>";
  echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($precio,'2')."</font></td>";
  echo "<td align='right'><font size='1' face='Arial, Helvetica, sans-serif' color='$color'>".number_format($precio-$rg[costo],'2')."</font></td>";
	echo "</tr>";
  $costoT += $rg[costo];
  $precioT += $precio;
  $difT += $precio-$rg[costo];
	$Estudios++;
  $nRng++;
}
echo "<tr bgcolor='#a2b2de' height='20'><td align='center' colspan='8'><font size='1' face='Arial, Helvetica, sans-serif'><b>No. de estudios: $Estudios</b></font></td><td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>".number_format($costoT,'2')."</b></font></td><td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>".number_format($precioT,'2')."</b></font></td><td align='right'><font size='1' face='Arial, Helvetica, sans-serif'><b>".number_format($difT,'2')."</b></font></td></tr>";
echo "</table>";

echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=28&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
echo "<div align='left'>";
$FecI=$_REQUEST[FecI];
$FecF=$_REQUEST[FecF];

echo "<form name='form1' method='post' action='pidedatos.php?cRep=28&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";
echo "</div>";
?>
</body>
</html>
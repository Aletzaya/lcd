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

  $Generapago=$_REQUEST[generapago];
	
  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

  $Fechai=date("Y-m-d H:i");
  
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

  if($Generapago=='Si'){

      $Inst='FecI='.$FecI.'&FecF='.$FecF.'&HoraI='.$HoraI.'&HoraF='.$HoraF.'&SucursalDe='.$SucursalD.'&Proveedor='.$SucursalP.'&personal='.$personal;

      $lUp    = mysql_query("INSERT INTO generapago (instruccion,fecha,usr,cancel,proveedor)
      VALUES
      ('$Inst','$Fechai','$Usr','','$SucursalP')");

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

$cSql="SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,cli.cliente,cli.nombrec,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,maqdet.usrenvext
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

echo "<br><table align='center' width='95%' border='1' cellspacing='0' cellpadding='0'>";
echo "<tr bgcolor='#a2b2de' height='20'>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Suc</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Orden</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Paciente</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Estudios</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Descripcion</font></td>";	
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Observaciones</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Para</font></td>";
echo "<td align='CENTER'><font size='2' face='Arial, Helvetica, sans-serif'>Usr</font></td>";
echo "</tr>";
while($rg=mysql_fetch_array($UpA)) {

    $MqlA2=mysql_query("select * from mql where mql.id=$rg[mext]",$link);
    $Mql2=mysql_fetch_array($MqlA2);

  if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo'; height='20'>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[suc]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion] - $rg[orden]</font></td>";
	echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[nombrec]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]</font></td>";	
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]</font></td>";
	echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[obsenv]</font></td>";
  echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$Mql2[alias]</font></td>";
  echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[usrenvext]</font></td>";
	echo "</tr>";
	$Estudios++;
    $nRng++;

}
echo "<tr bgcolor='#a2b2de' height='20'><td align='center' colspan='8'><font size='1' face='Arial, Helvetica, sans-serif'>No. de estudios: $Estudios</font></td></tr>";
echo "</table>";

echo "<br><br><table align='center' width='98%' border='0' cellspacing='0' cellpadding='0'>";
echo "<tr>";
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Elaborado por:</font></td>";
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>_________________________________</font></td>";
echo "<td align='left'><font size='1' face='Arial, Helvetica, sans-serif'> </font></td>";
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Mensajero:</font></td>";	
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>_________________________________</font></td>";
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'> </font></td>";
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Recibido por:</font></td>";
echo "<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>_________________________________</font></td>";
echo "</tr>";
echo "</table>";

echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatosventana.php?cRep=28&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
echo "<div align='left'>";
$FecI=$_REQUEST[FecI];
$FecF=$_REQUEST[FecF];

echo "<form name='form1' method='post' action='pidedatosventana.php?cRep=28&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'> &nbsp; - &nbsp; <a href=javascript:Ventana('repenvioextpdf.php?FecI=$FecI&FecF=$FecF&HoraI=$HoraI&HoraF=$HoraF&SucursalDe=$SucursalD&Proveedor=$SucursalP&personal=$personal')><img src='lib/logoorthin.jpg' alt='pdf' width='80' border='0'></a> &nbsp; - &nbsp; <a href=javascript:Ventana('repenvioextpdfeli.php?FecI=$FecI&FecF=$FecF&HoraI=$HoraI&HoraF=$HoraF&SucursalDe=$SucursalD&Proveedor=$SucursalP&personal=$personal')><img src='lib/logoeli.jpg' alt='pdf' width='180' border='0'></a> &nbsp; &nbsp; &nbsp; &nbsp; - &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <a href='repenvioext.php?FecI=$FecI&FecF=$FecF&HoraI=$HoraI&HoraF=$HoraF&SucursalDe=$SucursalD&Proveedor=$SucursalP&personal=$personal&generapago=Si'> ( Genera Envio para Pago )</a>  &nbsp; - &nbsp; <a href=javascript:Ventana('repenvioextpdf2.php?FecI=$FecI&FecF=$FecF&HoraI=$HoraI&HoraF=$HoraF&SucursalDe=$SucursalD&Proveedor=$SucursalP&personal=$personal')><img src='lib/logoorthin.jpg' alt='pdf' width='80' border='0'></a>  &nbsp; &nbsp; - &nbsp;  &nbsp; <a href=javascript:Ventana('repenvioextpdfcilab.php?FecI=$FecI&FecF=$FecF&HoraI=$HoraI&HoraF=$HoraF&SucursalDe=$SucursalD&Proveedor=$SucursalP&personal=$personal')><img src='lib/cilab.jpg' alt='pdf' width='80' border='0'></a>";
echo "</form>";
echo "</div>";
?>
</body>
</html>
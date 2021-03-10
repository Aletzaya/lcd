<?php
  session_start();

  require("lib/importeletras.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $FcA=mysql_query("select fc.id,fc.fecha,fc.cliente,clif.nombre,clif.direccion,clif.localidad,clif.telefono,clif.rfc,clif.codigo from fc,clif where fc.cliente=clif.id and fc.id='$busca'",$link);
  $Fc=mysql_fetch_array($FcA);

  $cSql="select otd.estudio,est.descripcion,otd.precio,otd.descuento from otd,est,ot where ot.factura='$busca' and ot.orden=otd.orden and otd.estudio=est.estudio";
  $FcdA=mysql_query($cSql,$link);
  //echo $cSql;

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Impresion de Facturas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<form action="facturas.php" method="get" name="manda">
  <div align="right">
    <input type="submit" name="Original" value="Factura" onClick="print()">
    <input type="hidden" name="op" value="br">
  </div>
</form>

<?php


  $Gfon="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color=$Gletra><b> &nbsp;";

  echo "<table width='90%' border='0' align='center'>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";  
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<td align='left' width='80%'>$Gfon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $Fc[nombre] </b></font></td>";
  echo "<td align='right' width='20%'>$Gfon &nbsp; </td>";
  echo "</tr>";
  echo "<tr><td align='left' width='80%'>$Gfon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $Fc[direccion] </b></font></td>";
  echo "<td align='center' width='20%'>$Gfon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Fol.:$busca </b></font></td>";
  echo "<tr>";
  echo "<td align='left' width='80%'>$Gfon  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; COL. $Fc[localidad] &nbsp; C.P. $Fc[codigo] </b> </font></td>";
  echo "<td align='center' width='20%'>$Gfon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $Fc[fecha] </b></font></td>";  echo "</tr>";
  echo "</tr>";
  echo "<tr>";
  echo "<td align='left' width='80%'>$Gfon  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TEL.&nbsp;$Fc[telefono] &nbsp; &nbsp; R.F.C.: $Fc[rfc]</b></font></td>";
  echo "<td align='right' width='20%'>$Gfon &nbsp; </td>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "<tr>";
  echo "</tr>";
  echo "<tr>";
  echo "</table>";
//  echo "<p>&nbsp;</p>";

  echo "<table width='85%' align='center'>";
  while($registro=mysql_fetch_array($FcdA)){
       echo "<tr>";
       echo "<td width='7%'>$Gfon <b> 1 </b> </font></td>";
       echo "<td width='80%'>$Gfon <b> $registro[1]</b></font></td>";
       if($registro[descuento]==0){$Precio=$registro[2]/1.16;}else{$Precio=($registro[2]*(1-($registro[descuento]/100)))/1.16;}
       echo "<td width='13%' align='right'>$Gfon <b>".number_format($Precio,"2")."</b></font></td>";
       echo "</tr>";
       $nImporte+=$Precio;
       $Rng++;
  }
  while($Rng<10){
   echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $Rng++;
  }
  echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td width='20%' align='right'>$Gfon <b>".number_format($nImporte,"2")."</b></font></td></tr>";
//  echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td></tr>";
//  echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td></tr>";
  echo "<tr><td>&nbsp;</td><td width='20%'>$Gfon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  $Letra=impletras($nImporte*1.16,"pesos ");
  echo "$Gfon <b> $Letra </b></font>";
  echo "</td><td width='20%' align='right'>$Gfon <b>".number_format(($nImporte*.16),"2")."</b></font></td></tr>";
  echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td width='20%' align='right'>$Gfon <b>".number_format($nImporte*1.16,"2")."</b></font></td></tr>";
  echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
  echo "</table>";
  mysql_close();

  ?>

</body>

</html>
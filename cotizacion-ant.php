<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $Titulo="Cotizacion";

  $link=conectarse();

  $tamPag=10;

  //$Vta=$_REQUEST[Vta];

  if(!isset($_REQUEST[Vta])){$Vta=$_SESSION['Venta_ot'];}else{

     $Vta=$_REQUEST[Vta];

     $_SESSION['Venta_ot']=$_REQUEST[Vta];


  } #En caso k venga del cat.de clientes(cliventas) y desde ahi manda la clave


  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30


  $cSql="select estudio,descripcion,precio,descuento from otdnvas where usr='$Usr' and venta='$Vta' ";


?>

<html>
<head>
<title>Cotizacion</title>
<style type="text/css">
<!--
.Estilo4 {font-family: Arial, Helvetica, sans-serif}
.Estilo5 {
	font-size: xx-small;
	font-weight: bold;
}
.Estilo6 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: xx-small;
	font-weight: bold;
}
.Estilo11 {font-family: Arial, Helvetica, sans-serif; font-size: xx-small; }
.Estilo12 {font-size: xx-small}
-->
</style>
</head>

<body>


<?php


echo "<table width='100%' border='0'>";

echo "<tr>";

echo "<td width='15%'><img  src='images/Logotipo%20Duran4.jpg' border='0'></td>";

echo "<td width='70%' align='center'><br><img src='lib/labclidur.jpg'></td>";

echo "<td width='15%'>&nbsp;</td>";

echo "</tr>";

echo "</table>$Gfont";

echo "<br><div align='center'>Av. Fray Pedro de Gante No. 108 Col. centro Texcoco Edo. de Mexico Tels. 95-41140 y 9546292</div>";

$OtA=mysql_query("select * from otnvas where usr='$Usr' and venta='$Vta'",$link);
$Ot=mysql_fetch_array($OtA);

$lUpA=mysql_query("select otdnvas.estudio,est.descripcion,precio,descuento,est.condiciones,est.entord from otdnvas,est where otdnvas.usr='$Usr' and otdnvas.venta='$Vta' and otdnvas.estudio=est.estudio",$link);    #Checo k bno halla estudios capturados
$lBd=false;

echo "<div align='center'> Fecha : $Fecha &nbsp; &nbsp; &nbsp; &nbsp; Hora : $hora  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Atendio: $Usr </div></font></br>";

echo "<table width='100%' border='0'>";
echo "<tr><td colspan='6'><hr noshade style='color:059444;height:2px'></td></tr>";
echo "<tr><td width='5%'><strong>Est.</td><td width='15%'><strong>Descripcion</td><td align='center' width='10%'><strong>Entrega</td><td align='center' width='30%'><strong>Condiciones</td><td align='center' width='5%'><strong>Precio</td><td align='center' width='5%'><strong>Importe</td></strong></tr>";
echo "<tr><td colspan='6'><hr noshade style='color:059444;height:2px'></td></tr>";
while($reg=mysql_fetch_array($lUpA)){
	if($reg[entord]==1){
		$DIA="Mismo";
	}else{
		$DIA=$reg[entord];
	}
	
	if($reg[descuento]==0){
		$Marca=" ";
	}else{
		$Marca="*";
	}

	 echo "<tr>";
     echo "<td>$Gfont $reg[estudio]</font></td>";
     echo "<td>$Gfont $reg[descripcion]</font></td>";
     echo "<td align='right'>$Gfont $DIA Dia(s) &nbsp; &nbsp; </font></td>";
     echo "<td>$Gfont $reg[condiciones]</font></td>";
     echo "<td align='right'>$Gfont ".number_format($reg[precio],"2")." &nbsp; </font></td>";
//     echo "<td align='right'>$Gfont ".number_format($reg[precio]*($reg[descuento]/100),"2")." &nbsp; </font></td>";
     echo "<td align='right'>$Gfont ".number_format($reg[precio]-($reg[precio]*($reg[descuento]/100)),"2")."$Marca &nbsp; </font></td>";
     echo "</tr>";
     echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
	 $nDesc+=$reg[precio]*($reg[descuento]/100);
     $nImp+=$reg[precio];
}

echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>$Gfont <b> Total &nbsp; $ </b> </td><td align='right'>$Gfont <strong>".number_format($nImp-$nDesc,"2")." </strong></font></td></tr>";

echo "</table>";

?>

<table width="80%" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#FFFFFF">
  <tr bordercolor="#FFFFFF">
    <td height="37" colspan="2"><div align="center" class="Estilo4 Estilo5">Estimado cliente le sugerimos seguir las indicaciones arriba citadas para obtener resultados mas precisos.</div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td colspan="2" bordercolor="#CCCCCC"><div align="center" class="Estilo6">Entrega de resultados </div></td>
  </tr>
  <tr bordercolor="#CCCCCC">
    <td width="406" height="51"><span class="Estilo11">- Muestras recibidas antes de las 12:00 hrs. se entregar&aacute;n los resultados a partir de las 3:00 P.M.</span></td>
    <td width="406"><span class="Estilo11">- Muestras recibidas despues de las 7:00 P.M. se entregar&aacute;n los resultados el d&iacute;a siguiente.</span></td>
  </tr>
  <tr bordercolor="#CCCCCC">
    <td><span class="Estilo11">- En caso de <strong>urgencia</strong> el personal de recepci&oacute;n le indicar&aacute; el tiempo de entrega. </span></td>
    <td><span class="Estilo11">- Considerar en el tiempo de entrega unicamente d&iacute;as h&aacute;biles. </span></td>
  </tr>
</table>
<p align="center"><span class="Estilo5">* </span><span class="Estilo12">Estudios que incluyen descuento sobre su precio normal </span></p>
<form action="ordenesnvas.php" method="get" name="manda">
  <table width='100%' height="25" border='0' align="center" cellpadding="0" cellspacing="0">
    <tr><td align='right'>
        <div align="right">
          <input type="submit" name="Original" value="Cotizacion" onClick="print()">    
          <input type="hidden" name="op" value="br">
        </div></td></tr></table>
</form>
</body>
</html>

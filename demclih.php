<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $orden=$_REQUEST[orden];

  $Msj="";

  $Fecha=date("Y-m-d");

  $Hora = date("h:i:s");

  $Titulo="Estudios del cliente [$busca]";

  require("lib/kaplib.php");

  $link=conectarse();

  $OrdenDef="oth.fecha";            //Orden de la tabla por default

  $Tabla="otdh";

  session_register("Tabla"); //Tabla a usar en los filtros

  $cSqlH="select nombrec,direccion,localidad,sexo,fechan,telefono from cli where cliente='$busca'";

  $cSqlD="select otdh.estudio,otdh.status,est.descripcion,otdh.precio,otdh.descuento,est.muestras,otdh.etiquetas,oth.fecha,oth.orden from otdh,est,oth where otdh.estudio=est.estudio and otdh.orden=oth.orden and oth.cliente='$busca' ";

  $HeA=mysql_query($cSqlH,$link);

  $He=mysql_fetch_array($HeA);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style type="text/css">
<!--
a.ord:link {
    color: #66CC66;
    text-decoration: none;
}
a.ord:visited {
    color: #66CC66;
    text-decoration: none;
}
a.ord:hover {
    color: #000099;
    text-decoration: underline;
}
-->
</style>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF">
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><div align="left"><a href="ordenes.php"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></div></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633FF">
    <td width="82%" bgcolor="#6633ff"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/dot.gif" alt="Sistema Clinico(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
    </td>
    <td width="18%" height="23"><div align="right">
     <script language="JavaScript1.2">generate_mainitems()</script>
</div></td></tr>
</table>
<hr noshade style="color:000099;height:2px">
<?
  if($op=='sm'){
     $cSumA="select sum($SumaCampo) from $Tabla,cli where $Tabla.cliente=cli.cliente ".$cWhe;
     $cSum=mysql_query($cSumA,$link);
     $Suma=mysql_fetch_array($cSum);
     $Funcion=" / $SumaCampo: ".number_format($Suma[0],"2");
  }
   echo "<table align='center' width='90%' background='lib/fondo.gif' >";
   echo "<div align='center'><font color='#000099'><strong>DETALLE POR ODEN DE ESTUDIOS</strong></font></div>";
   echo "<td><font color='#0066FF' size='3' >";
   echo "<p align='center'> <font color='#660066'>Paciente: $busca </font> &nbsp;&nbsp;&nbsp; $He[nombrec] <font color='#660066'>Direccion:</font>$He[direccion]</p>";
   echo "<p align='center'><font color='#660066'>Localidad: $He[localidad]</font> <font color='#660066'>Sexo: $He[sexo] </font>  <font color='#660066'> Telefono: </font> $He[telefono] <font color='#660066'>Fecha Nac:</font>$He[fechan]</p>";
   echo "</font></td></table>";

   if(!$res=mysql_query($cSqlD." ORDER BY ".$OrdenDef,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{
      echo "<br>";
      $numeroRegistros=mysql_num_rows($res);
		echo "<div align='center'>";
		echo "<img src='images/corner-bottom-left.gif' width='15' height='12'>";
		echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Estudios</b></strong></font>";
		echo "<img src='images/corner-bottom-right.gif' width='15' height='12'>";
		echo "</div>";
		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td colspan='10'><hr noshade></td></tr>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Orden</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Fecha</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Estudio</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Descripcion</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Precio</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>%Dto</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Importe</font></th>";
		while($registro=mysql_fetch_array($res))		{  ?>
        <tr bgcolor="#CEDADD" onMouseOver="this.style.backgroundColor='#E3DBFD';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CEDADD'"o"];">
        <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[8]; ?></b></font></td>
        <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[7]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[estudio]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[descripcion]; ?></b></font></td>
        <td align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[precio],"2"); ?></b></font></td>
        <td align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[descuento],"2"); ?></b></font></td>
        <td align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[precio]*(1-$registro[descuento]/100),"2"); ?></b></font></td>
        </tr>
        <?
		}//fin while
		echo "</table>";
	}//fin if
    ?>

<p>&nbsp;</p>

<hr noshade style="color:66CC66;height:5px">
</body>
</html>
<?
mysql_close();
?>
<?
$Titulo="Filtros guardados para la tabla de $Fil";
require("lib/kaplib.php");
$link=conectarse();
$Columna="";
$cSql="select producto,descripcion,existencia from inv where producto>='$busca'";
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
$Usr=$HTTP_SESSION_VARS['usuario_login'];
$url = explode("?",$HTTP_REFERER);
$Url=$url[0];   //pagina de donde viene
session_start();
$Tabla="inv";
session_register("Tabla"); //Tabla a usar en los filtros
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Filtros.... </title>
</head>
<body bgcolor="#FFFFFF">
<table width="98%" border="0">
  <tr> 
    <td width="11%" height="59"><img src="lib/logo2.jpg" width="100" height="80"></td>
    <td width="78%"> 
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="3%">&nbsp;</td>
    <td width="8%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" bgcolor="#6633FF">
  <tr> 
    <td width="48%"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="Imagenes/dot.gif" alt="LCD" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong> 
    </td>
    <td width="52%" height="23"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
      </font></td>
  </tr>
</table>
<hr noshade style="color:3366FF;height:2px">
<?php
    $sql="select * from ft where fil='$Fil'";
	$res=mysql_query($sql,$link);
	$numeroRegistros=mysql_num_rows($res);
	$res=mysql_query($sql,$link);
	echo "<div align='center'>";
	echo "<img src='Imagenes/corner-bottom-left.gif' width='15' height='12'>";
	echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Registros, ordenados por ".$orden.$Columna."</b></strong></font>";
	echo "<img src='Imagenes/corner-bottom-right.gif' width='15' height='12'>";
	echo "</div>";
	echo "<table align='center' width='40%' border='0' cellspacing='1' cellpadding='0'>";  //Tamaño tabla
	echo "<tr><td colspan='3'><hr noshade></td></tr>";
	echo "<th bgcolor='#6633FF'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Identificador</font></th>";
	echo "<th bgcolor='#6633FF'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Nombre del filtro</font></th>";
	while($registro=mysql_fetch_array($res)){
       ?>
       <tr bgcolor="#CCCCCC" onMouseOver="this.style.backgroundColor='#99CCFF';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CCCCCC'"o"];">
       <td><a href=<?php echo $Url."?op=!".$registro[id]; ?>><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><?php echo $registro[id]; ?></b></font></a></td>
       <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[nombre]; ?></b></font></td>
       </tr>
       <!-- fin tabla resultados -->
       <?
	}//fin while
	echo "</table>";
    ?>
	<br>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td align="center" valign="top">
	</td></tr>
	</table>
    
<p align="center"><font size="2"><a href="<? echo $Url.'?pagina='.$pagina.'&orden='.$orden.'&busca='.$busca;?>">Regresa</a></font></p>
<p align="center">&nbsp;</p>
<hr noshade style="color:3366FF;height:3px">

</body>
</html>
<?
mysql_close();
?>
<?
require("lib/kaplib.php");
$link=conectarse();
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
session_start();
$Usr=$HTTP_SESSION_VARS['usuario_login'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Sistema Administrativo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<font color="#003399">
<p><div align="center"><strong>Guarda Filtros</strong></div></p>
<?php
  if($Nombre<>""){
    $Tabla=$HTTP_SESSION_VARS['Tabla'];
    $lUp=mysql_query("insert into ft (fil,nombre) VALUES ('$Tabla','$Nombre')",$link);
    $Id=mysql_insert_id();
    $lUp=mysql_query("update ftd SET id=$Id where fil='$Tabla' and id='99999' and usr='$Usr'",$link);
    $filtro_nvo=$Id;
    session_register("filtro_nvo");
    echo "<div align='center'>Tu filtro a sido guardado con el #$Id<br><br>";
    echo "<hr noshade style='color:3366FF;height:4px'>";?>
   <p><A HREF="javascript:window.close()">Favor de cerrar esta ventana</a> </p>
   <p><img src="lib/martillo.gif" width="91" height="75"></p>
   <p>
   <?php
    echo "<p>Ahora puedes abrir este filtro - <br>";
    echo "cuando lo requieras <br>";
    echo "unicamente debes de dar click <br> en abrir filtros <br></p>";
    echo "</div><font></strong>";
   }else{
    echo "<form name='form1' method='post' action='".$_SERVER["PHP_SELF"]."'>
    <p>Nombre: <input name='Nombre' type='text' id='Nombre'></p>
    <p>&nbsp;</p>
    <p><div align='center'><input type='IMAGE' name='Guarda' src='lib/caja.gif' alt='Guarda los Datos'></div></p>
    </form>";
   }
?>
</p>
</body>
</html>
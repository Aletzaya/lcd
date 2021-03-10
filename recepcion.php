<?
require("aut_verifica.inc.php");
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body>

<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:777px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="80" border="0">
    <tr>
      <td width="16%" height="76"> <div align="center"><a href="menu.php"><img src="lib/logo2.jpg" width="100" height="80" border="1"></a></div></td>
      <td width="70%">
<p align="left"><img src="lib/labclidur.jpg" width="350" height="50" border="1"></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>  
  <table width="100%" height="414" border="0">
    <tr>
      <td width="16%" height="410"> 
        <p><a href="ordenes.php"><img src="lib/ordstrab.jpg" width="150" height="25" border="1"></a></p>
        <p><a href="medicos.php?op=recepcion.php"><img src="lib/medicos.jpg" width="150" height="25" border="1"></a></p>
        <p><a href="estudios.php?op=recepcion.php"><img src="lib/estudios.jpg" width="150" height="25" border="1"></a></p>
        <p><a href="clientes.php?op=recepcion.php"><img src="lib/pacientes.jpg" width="150" height="25" border="0"></a></p>
        <p><a href="ingresos.php"><img src="lib/ingresos.jpg" width="150" height="25" border="0"></a></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p align="center"><a href="menu.php"><img src="lib/SmallExit.BMP" border="1"></a></p>
        </td>
      <td width="70%" background="lib/fondo2.jpg"><div align="center">
          <p><img src="lib/logo1.jpg" width="400" height="200" border="1"></p>
        </div></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <div align="right"></div>
  <div align="center"></div>
  <p>&nbsp;</p>
</div>
</body>
</html>
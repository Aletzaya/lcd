<?php
/*
# File: admin/index.php
# Script Name: vAuthenticate 3.0.1
# Author: Vincent Ryan Ong
# Email: support@beanbug.net
#
# Description:
# vAuthenticate is a revolutionary authentication script which uses
# PHP and MySQL for lightning fast processing. vAuthenticate comes
# with an admin interface where webmasters and administrators can
# create new user accounts, new user groups, activate/inactivate
# groups or individual accounts, set user level, etc. This may be
# used to protect files for member-only areas. vAuthenticate
# uses a custom class to handle the bulk of insertion, updates, and
# deletion of data. This class can also be used for other applications
# which needs user authentication.
#
# This script is a freeware but if you want to give donations,
# please send your checks (coz cash will probably be stolen in the
# post office) to:
#
# Vincent Ryan Ong
# Rm. 440 Wellington Bldg.
# 655 Condesa St. Binondo, Manila
# Philippines, 1006
*/
?>
<?php
    require_once ('../auth.php');
    require_once ('../authconfig.php');
    require_once ('../check.php');

	if ($check["level"] != 1 and $check["level"] != 9)
	{
		// Feel free to change the error message below. Just make sure you put a "\" before
		// any double quote.
		print "<font face=\"Arial, Helvetica, sans-serif\" size=\"5\" color=\"#FF0000\">";
		print "<b>Illegal Access</b>";
		print "</font><br>";
  		print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"#000000\">";
		print "<b>You do not have permission to view this page.</b></font>";
		
		exit; // End program execution. This will disable continuation of processing the rest of the page.
	}	
?>

<html>
<head>
<title>vAuthenticate Administrative Interface</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p align='center'><font face="Arial, Helvetica, sans-serif" size="5"><b>Administracion</b></font></p>
<table width="75%" border="1" align='center' cellspacing="0" cellpadding="0" bordercolor="#000000">
  <tr bgcolor='#CCCCCC'> 
    <td width="20%" height="16"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Administrador</font></b></td>

    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authuser.php">Usuarios</a></font></div>
    </td>
    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authgroup.php">Grupos</a></font></div>
    </td>

    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="<?php echo $logout; ?>">Salir</a></font></div>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bienvenido 
        al sistema de Administracion del sitio: </font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Puede Realizar 
        las fuciones administrativas que se listan abajo:</font> </p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <b>Settings</b> 
        - Control del sitio: entrada y salida (login y logout).</font></p>

      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <b>Users</b> 
        - Adiciona, Modifica, Activa/desactiva y Elimina Usaurios.</font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <b>Groups</b> 
        - Crea, Modifica, Activa/desactiva y Elimina grupos.</font></p>
      
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <b>Emailer</b> 
        - personaliza perfiles para notificar via mail.</font></p>

      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <b>Logout</b> 
        - finaliza la sesion.</font></p>
</body>
</html>

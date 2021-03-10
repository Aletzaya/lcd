<?php

/*
# File: chgpwd.php
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

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
 	
$Gfont="<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#6D6D6D'>";

echo "<head><title>Cambio de password</title></head>";

echo "<body bgcolor='#FFFFFF'>";

echo "<p>&nbsp;</p>";

echo "<div align='center'>$Gfont <b>Cambio de password</b></font></div>";

echo "<p>&nbsp;</p>";

echo "<div align='center'>";

echo "<center>";

echo "<form method='POST' action='chgpwd.php'>";



  echo "<TABLE WIDTH=312 BORDER=0 CELLPADDING=0 CELLSPACING=0>";

  echo "<TR>";
  echo "<TD>";
  echo "<img SRC='lib/tablita_01.gif' width=6 height=6>";
  echo "</TD>";

  echo "<TD background='lib/tablita_02.gif' width=150>";
  echo "<img SRC='espacio.gif' width=1 height=6>";
  echo "</TD>";
  
  echo "<TD background='lib/tablita_02.gif' width=150>";
  echo "<img SRC='espacio.gif' width=1 height=6>";
  echo "</TD>";

  echo "<TD>";
  echo "<img SRC='lib/tablita_03.gif' width=6 height=6>";
  echo "</TD>";
  echo "</TR>";

  //--------------------

  echo "<TR>";
  echo "<TD background='lib/tablita_04.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";

  echo "<TD background='lib/tablita_05.gif' align='right'>";
  echo "&nbsp;";
  echo "</TD>";
  
  echo "<TD background='lib/tablita_05.gif'>";
  echo "&nbsp;";
  echo "</TD>";

  echo "<TD background='lib/tablita_06.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";
  echo "</TR>";

  /* 
  //----------------------
  
  echo "<TR>";
  echo "<TD background='lib/tablita_04.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";

  echo "<TD background='lib/tablita_05.gif' align='right'>";
  echo "$Gfont Password anterior : &nbsp; </font>";
  echo "</TD>";
  
  echo "<TD background='lib/tablita_05.gif'>";
  echo "<input type='password' name='oldpasswd' size='10'></td>";
  echo "</TD>";

  echo "<TD background='lib/tablita_06.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";
  echo "</TR>";
  
  //--------------------
  */
  
  echo "<TR>";
  echo "<TD background='lib/tablita_04.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";

  echo "<TD background='lib/tablita_05.gif' align='right'>";
  echo "$Gfont &nbsp;Password nuevo : &nbsp; </font>";
  echo "</TD>";
  
  echo "<TD background='lib/tablita_05.gif'>";
  echo "<input type='password' name='newpasswd' size='10'></td>";
  echo "</TD>";

  echo "<TD background='lib/tablita_06.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";
  echo "</TR>";

  //--------------------

  echo "<TR>";
  echo "<TD background='lib/tablita_04.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";

  echo "<TD background='lib/tablita_05.gif' align='right'>";
  echo "$Gfont &nbsp;Confirmar : &nbsp; </font>";
  echo "</TD>";
  
  echo "<TD background='lib/tablita_05.gif'>";
  echo "<input type='password' name='confirmpasswd' size='10'></td>";
  echo "</TD>";

  echo "<TD background='lib/tablita_06.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";
  echo "</TR>";

  //--------------------Solo para crear un renglon en blanco;

  echo "<TR>";
  echo "<TD background='lib/tablita_04.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";

  echo "<TD background='lib/tablita_05.gif' align='right'>";
  echo "&nbsp;";
  echo "</TD>";
  
  echo "<TD background='lib/tablita_05.gif'>";
  echo "&nbsp;";
  echo "</TD>";

  echo "<TD background='lib/tablita_06.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";
  echo "</TR>";
  
  //--------------------

  echo "<TR>";
  echo "<TD background='lib/tablita_04.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";

  echo "<TD background='lib/tablita_05.gif' align='right'>";
  echo "<input type='submit' value='Salvar Cambios' name='submit'> &nbsp; ";
  echo "</TD>";
  
  echo "<TD background='lib/tablita_05.gif'>";
  echo "<input type='reset' value='Limpiar Campos' name='reset'>";
  echo "</TD>";

  echo "<TD background='lib/tablita_06.gif'>";
  echo "<img SRC='lib/espacio.gif' width=6 height=1>";
  echo "</TD>";
  echo "</TR>";

  //-------------------- Utimo!! el que cierra el cuadro
  
  echo "<TR>";
  echo "<TD>";
  echo "<IMG SRC='lib/tablita_07.gif' width=6 height=6>";
  echo "</TD>";
  
  echo "<TD align=center background='lib/tablita_08.gif'>";
  echo "<IMG SRC='espacio.gif' width=1 height=6>";
  echo "</TD>";

  echo "<TD align=center background='lib/tablita_08.gif'>";
  echo "<IMG SRC='espacio.gif' width=1 height=6>";
  echo "</TD>";
  
  echo "<TD>";
  echo "<IMG SRC='lib/tablita_09.gif' width=6 height=6>";
  echo "</TD>";
  echo "</TR>";  

  echo "</TABLE>";  

  echo "</form>";

  echo "</center>";

  echo "</div>";

  echo "<div align='center'><br><table width='40%' border='1' cellpadding='4' cellspacing='0'><tr><td>";

  echo "$Gfont Por cuestiones de seguridad es necesario cambiar constantemente ";
  echo "las claves de acceso, por lo cual le pedimos encarecidamente que recuerde ";
  echo "su nueva clave</font>";

  echo "</td></tr></table></div>";

	// Get global variable values if there are any
	if (isset($_POST['submit'])){
	   
		$USERNAME = $_COOKIE['USERNAME'];
				
		$PASSWORD = $_COOKIE['PASSWORD'];		   

		$submit = $_POST['submit'];
		$oldpasswd = $_POST['oldpasswd'];
		$newpasswd = $_POST['newpasswd'];
		$confirmpasswd = $_POST['confirmpasswd'];
    
   }else{
		$submit = "";
	}
   	
	$user = new auth();
	$connection = mysql_connect($dbhost, $dbusername, $dbpass);
	
	// REVISED CODE
	$SelectedDB = mysql_select_db($dbname);
	$userdata = mysql_query("SELECT * FROM authuser WHERE uname='$USERNAME' and passwd='$PASSWORD'");
	
	if ($submit){
		 
		// Check if Old password is the correct
		if ($PASSWORD == trim($newpasswd)){
			print "<p align=\"center\">";
			print "	<font face=\"Arial\" color=\"#FF0000\">";
			print "		<b>El password nuevo debe ser diferente al anterior!</b>";
			print "	</font>";
			print "</p>";
			exit;
		}

		
		// Check if New password if blank
		if (trim($newpasswd) == ""){
			print "<p align=\"center\">";
			print "	<font face=\"Arial\" color=\"#FF0000\">";
			print "		<b>El nuevo password no puede estar en blanco!</b>";
			print "	</font>";
			print "</p>";
			exit;
		}
				
		// Check if New password is confirmed
		if ($newpasswd != $confirmpasswd){
			print "<p align=\"center\">";
			print "	<font face=\"Arial\" color=\"#FF0000\">";
			print "		<b>El nuevo password no ha sido confirmado!</b>";
			print "	</font>";
			print "</p>";
			exit;
		}

		
		// If everything is ok, use auth class to modify the record
		$update = $user->modify_user($USERNAME, $newpasswd, $check["team"], $check["level"], $check["status"], $check["msj"]);
		if ($update) {
			print "<p align=\"center\">";
			print "	<font face=\"Arial\" color=\"#FF0000\">";
			print "		<b>El password ha sido cambiado con exito!</b><br>";
			print "		Sera redireccionado y debera de dar su nuevo password. <BR>";
			print "		De click <a href=\"$login\">aqui</a> para reiniciar.";
			print "	</font>";
			print "</p>";
		}
		
	}	// end - new password field is not empty
?>

</body>

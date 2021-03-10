<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link    = conectarse();

    $archivo   = $_REQUEST[archivo];
    $id   = $_REQUEST[id];
    echo "<font face='verdana' color='#0066FF' size='2' >Archivo Eliminado $id - $archivo </font>";
	unlink("estudios/$archivo");
	$Usrelim    = $_COOKIE['USERNAME'];
    $lUp = mysql_query("UPDATE estudiospdf set usrelim='$Usrelim' where archivo='$archivo' and id='$id'");
    echo "<font face='verdana' color='#0066FF' size='2' >$Usrelim</font>";   
?>
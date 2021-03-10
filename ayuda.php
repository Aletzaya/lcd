<?php

  #Librerias
  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");
  $link=conectarse();
  
  $Id=$_REQUEST[Id];
  
  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA=mysql_query("select * from qrys where id=$Id",$link);
  $Qry=mysql_fetch_array($QryA);

  $Gfont="<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#668ec1'> &nbsp;";
  

 // require ("config.php");							//Parametros de colores;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title>A y u d a </title>

</head>

<body bgcolor="#FFFFFF">

<?php

  echo "<p>$Gfont <b> $Qry[nombre] </b> </font></p>";

  echo "<hr>";
    
  echo "<p>$Gfont $Qry[ayuda] </p>";  
  echo "</body>";
  
  echo "</html>";
  
  
  mysql_close();
  
?>
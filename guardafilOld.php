<?
require("lib/kaplib.php");
$link=conectarse();
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
$Usr=$HTTP_SESSION_VARS['usuario_login'];
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Sistema Administrativo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<p><font color="#003399"><strong>Guarda Filtros</strong></font></p>
<?php
  if($Nombre<>""){
    $Tabla=$HTTP_SESSION_VARS['Tabla'];
    $lUp=mysql_query("insert into ft (fil,nombre) VALUES ('$Tabla','$Nombre')",$link);
    $Id=mysql_insert_id();   
    $lUp=mysql_query("update ftd SET id=$Id where fil='$Tabla' and id='99999'",$link);
    $filtro_nvo=$Id;
    session_register("filtro_nvo");  
    echo "<strong><font color='#003399'> Filtro guardado No. $Id<br><br>";
    echo "Favor de cerrar esta ventana</font></strong>";
  }else{
    echo "<form name='form1' method='post' action='".$_SERVER["PHP_SELF"]."'>
    <p>Nombre: <input name='Nombre' type='text' id='Nombre'></p>
    <p><input type='IMAGE' name='Guarda' src='Imagenes/Guarda.gif' alt='Guarda los Datos'></p>
    </form>";
  }
?>
</body>
</html>
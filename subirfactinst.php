<?php
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

require ("config.php");							   //Parametros de colores;

$link     = conectarse();

$route = "factinst/";

$route = $route . basename($_FILES['file']['name']);
$archivos = basename($_FILES['file']['name']);

if(move_uploaded_file($_FILES['file']['tmp_name'], $route)){
	$busca   = $_COOKIE['NOOT'];
	$Usr2    = $_COOKIE['USERNAME'];
    $Fechasub  = date("Y-m-d H:i:s");
    $cNombreFile=$archivos;
	$lUp = mysql_query("INSERT INTO factinst (id,archivo,usr,fechasub) VALUES ('$busca','$cNombreFile','$Usr2','$Fechasub')");
}

?>
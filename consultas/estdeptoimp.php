<?php

  session_start();
/*
  if(!isset($_REQUEST[Depto])){$Depto=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[Depto];$Depto=$_REQUEST[Depto];}

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link    = conectarse();
 */

// Opens a connection to a MySQL server
$connection = mysql_connect("localhost", "root", "texcocolcd");

if (!$connection) {
    die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db("lcd", $connection);
if (!$db_selected) {
    die('Can\'t use db : ' . mysql_error());
}

require("../lib/lib.php");



$Fecha = date("Y-m-d");
$Hora = date("H:i");

$Ord = $_SESSION['cVar'];


//Graba quien genera la impresion y manda direcciona la impresion;		  
$cLnk = $_REQUEST[clnk] . '.php';
$Est = $_REQUEST[Estudio];

$OtdA = mysql_query("SELECT cuatro,lugar FROM otd WHERE orden='$Ord' AND estudio='$Est' LIMIT 1");
$Otd = mysql_fetch_array($OtdA);

/*
if (substr($Otd[cuatro], 0, 4) == '0000') { //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
    $Fecha = date("Y-m-d");

    $Hora = date("H:i");

    if ($Otd[lugar] <= '5') {

        $Up = mysql_query("UPDATE otd set cuatro = '$Fecha $Hora', lugar='5' 
	                WHERE orden='$Ord' AND estudio='$Est'");
    } else {

        $Up = mysql_query("UPDATE otd set cuatro = '$Fecha $Hora' 
	               WHERE orden='$busca' AND estudio='$Est'");
    }
}


//Btc($Titulo.$Est,$Ord);		
*/
header("Location: ../informes/$cLnk?Orden=$Ord&Estudio=$Est");
?>

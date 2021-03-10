<?php
session_start();

require("lib/lib.php");

$link = conectarse();

#Variables que cambian;
$Tabla     = "cia";
$Titulo     = "Parametros generales del sistema";
$Return   = "menu.php";

if ($_REQUEST[Boton] == Aceptar) {        //Para agregar uno nuevo
    if (strlen($_REQUEST[Password]) > 0) {

        $Password = MD5($_REQUEST[Password]);

        $Sql = "UPDATE cia SET password = '$Password' WHERE id='1'
                 ";
        $lUp = mysql_query($Sql);
    }

    if (strlen($_REQUEST[Passwordadmin]) > 0) {

        $Password = MD5($_REQUEST[Passwordadmin]);


        $lUp = mysql_query("UPDATE cia SET passwordadmin = '$Password' WHERE id='1'");
    }



    header("Location: menu.php?Msj=Parametros del sistema actualizado");
} elseif ($_REQUEST[Boton] == Cancelar) {

    header("Location: $Return");
}

$CpoA = mysql_query("SELECT * FROM cia WHERE id='1' ");
$Cpo = mysql_fetch_array($CpoA);

$Fecha = date("Y-m-d");

require ("config.php");
?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onload="cFocus()">

<?php headymenu($Titulo,1); ?>

<script language="JavaScript1.2">

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}

function cFocus(){
  document.form1.Nombre.focus();
}

</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='5%' align='center'>$Gfont";

		  echo "regresar<br>";
        echo "<a href='$Return'><img src='lib/regresa.jpg' border='0'></a>";
        echo "<p>&nbsp;</p>";

    echo "</td><td align='center'>";

       echo "<form name='form1' method='post' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'><br>";

             cTable('80%','0');

             cInput("Nombre de la compa&ntilde;ia: ","Text","50","Cia","right",$Cpo[nombre],"99",false,false,'');
             cInput("Direccion: ","Text","40","Direccion","right",$Cpo[direccion],"50",false,false,'');
             cInput("Ciudad: ","Text","40","Ciudad","right",$Cpo[municipio],"50",false,false,'');
             cInput("Telefono: ","Text","20","Telefono","right",$Cpo[telefono],"20",false,false,'');
             cInput("Rfc: ","Text","20","Rfc","right",$Cpo[rfc],"20",false,false,'');

             cInput("% de Iva: ","Text","10","Iva","right",$Cpo[iva],"10",false,false,'');

             cInput("Password: ","Password","20","Password","right","","20",false,false,'para cancelacion de movtos');

             cInput("Password administrador: ","Passwordadmin","20","Passwordadmin","right","","20",false,false,'unicamente para el administrativo');

             cTableCie();

             echo Botones();

             mysql_close();

            echo "</font>";

      echo "</form>";

      echo "</td>";

  echo "</tr>";

echo "</table>";

CierraWin();

echo "</body>";

echo "</html>";

?>
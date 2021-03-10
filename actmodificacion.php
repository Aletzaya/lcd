<?php
session_start();

require("lib/lib.php");

$link = conectarse();

$FechaI   =   $_REQUEST[FechaI];

$FechaF   =   $_REQUEST[FechaF];

if ($FechaI>$FechaF){
  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>';  
}  

#Variables que cambian;
$Tabla     = "actmod";
$Titulo     = "Activacion Temporal de Modificacion de Orden de Trabajo";
$Return   = "menu.php";

if ($_REQUEST[Boton] == Aceptar) {        //Para agregar uno nuevo

    $Rg = mysql_query("UPDATE cambios SET fechai='$_REQUEST[FechaI]',horai='$_REQUEST[HoraI]',fechaf='$_REQUEST[FechaF]',horaf='$_REQUEST[HoraF]',activo='$_REQUEST[Activo]' WHERE id='1'");

    echo '<script language="javascript">alert(" Registro Modificado ");</script>';  

} elseif ($_REQUEST[Boton] == Cancelar) {

    header("Location: $Return");
}

$CpoA = mysql_query("SELECT * FROM cambios WHERE id='1'");
$Cpo = mysql_fetch_array($CpoA);

require ("config.php");
?>

<html>

<head>

 <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
 
<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<title><?php echo $Titulo;?></title>

</head>

<body onload="cFocus()">

<?php headymenu($Titulo,1); ?>


<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='5%' align='center'>$Gfont";

		  echo "regresar<br>";
        echo "<a href='$Return'><img src='lib/regresa.jpg' border='0'></a>";
        echo "<p>&nbsp;</p>";

    echo "</td><td align='center'>";

       echo "<form name='form1' method='post' action=".$_SERVER['PHP_SELF']."><br>";

             cTable('80%','0');

            echo "<tr bgcolor='#d6eaf8'>"; 

            echo "<td align='center' colspan='9'>$Gfont <font size='1'><b>De:</b></font><input type='text' readonly='readonly' name='FechaI' size='10' value ='$Cpo[fechai]'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

            $HoraI=substr($Cpo[horai],0,5);

            echo "&nbsp; &nbsp; <b><font size='1'> Hra.I.: </b></font>";
            echo "<input type='text' value='$HoraI' name='HoraI' size='5' >&nbsp;";
                
            echo "$Gfont &nbsp; &nbsp; <font size='1'><b>A: </b></font><input type='text' readonly='readonly' name='FechaF' size='10' value ='$Cpo[fechaf]'> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";

            $HoraF=substr($Cpo[horaf],0,5);

            echo "&nbsp; &nbsp; <b><font size='1'>Hra.F.: </b></font>";
            echo "<input type='text' value='$HoraF'  name='HoraF' size='6' >&nbsp;&nbsp;";

            echo "</tr>"; 

            echo "<tr align='center'>"; 

             echo "<td>&nbsp;"; 
             echo "</td>"; 

            echo "</tr>"; 

             echo "<tr bgcolor='#d6eaf8' align='center'>"; 
           
            if($Cpo[activo]==1){

                echo "<td>"; 
                echo "$Gfont <b><font size='1'> Activo : <input type='checkbox' name='Activo' value='0' checked></b></font>";
                echo "</td>"; 

            }else{

                echo "<td>"; 
                echo "$Gfont <b><font size='1'> Activo : <input type='checkbox' name='Activo' value='1'></b></font>";
                echo "</td>"; 

            }

            echo "</tr>"; 

             mysql_close();

            echo "</font>";

      echo "</form>";

      echo "</td>";

  echo "</tr>";

echo "</table>";
             
echo Botones();

CierraWin();

echo "</body>";

echo "</html>";

?>
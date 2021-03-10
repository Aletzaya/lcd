<?php
setcookie("USERNAME", "");
setcookie("PASSWORD", "");

session_start();

$_SESSION['Venta_ot'] = 1;
$_SESSION['cVarVal'] = 'Ini';

$_SESSION['usr'] = 'Admon';

require("lib/lib.php");

$link = conectarse();
$Titulo = 'Area restringida';

require ('config.php');
?>

<html>

    <head>

        <script language='JavaScript' type='text/JavaScript'>
            function Ventana(url){
            window.open(url,'venini','scrollbars=yes,location=no,dependent=yes,resizable=yes')
            }
        </script>

        <title>Sistema de Control Administrativo de laboratorio</title>

        <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>

    </head>
    <body bgcolor='#eaf2f8' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onload='cFocus()'>
        <p> &nbsp </p>
        <table width='800' height='500' border='0' cellpadding='0' cellspacing='0' align='center' class='texto_bienvenida_usuario' style='border-collapse: collapse; border: 1px solid #066;'>
            <tr bgcolor='#ffffff'>
                <td height='50' width='1000' valign='center'>
                    <div align='center'><img src='lib/logo1.jpg' width='300'></div>
                </td>
            </tr>
            <tr bgcolor='#ffffff'>
                <td height='300' valign='top'>
                    <table width='600' cellpadding='3' cellspacing='2' border='0' align='center'>
                        <tr><td align='center' colspan='2'> &nbsp </td></tr>
                        <tr bgcolor='#f1f1f1'><td align='center' colspan='2'><?= $Gfont ?><strong><font size='+1'>Favor de seleccionar la sucursal </font></strong></td></tr>
                        <tr bgcolor='#003399'><td align='center'><font  color='#ffffff'> Sucursal </font></td><td align='center'><font  color='#ffffff'> Nombre </td></tr>
                        <?php
                        $sql = "SELECT id,alias FROM cia ORDER BY id";
                        $CiaA = $link->query($sql);

                        if (!$CiaA) {
                            echo "ERROR";
                        }
                        while ($rg = $CiaA->fetch_assoc()) {
                            
                            if ($rg[0] <> 6) {
                                if (($nRng % 2) > 0) {$BgColor = 'FFFFFF';} else {$BgColor = "e1e1e1";} 
                               
                                echo "<tr height='30' bgcolor=$BgColor>";

                                echo "<td align='center'>$Gfont <font size='+1' color='#003399'>$rg[id]</font></font></td>";

                                echo "<td align='center'>$Gfont <a class='pg' href='menu.php?busca=$rg[id]'><b> &nbsp; $rg[alias] </a></td>";

                                echo "</tr>";
                            } 
                            $nRng++;
                        }

                        echo "</table>";

                        if ($_REQUEST[Cia] <> '') {

                            echo "<form name='Login' method='post' action='$resultpage'>";

                            echo "<br><div align='center'>$Gfont Usuario: &nbsp ";

                            //echo "<input type='text' style='background-color:#e1e1e1; color:#ffffff;font-weight:bold;' name='username' size='15' maxlength='15' onKeyUp='this.value = this.value.toUpperCase();' >";
                            echo "<input type='text' style='background-color:#003399; color:#ffffff;font-weight:bold;' name='username' size='15' maxlength='15'>";

                            echo " &nbsp &nbsp &nbsp &nbsp Clave:";

                            echo "<input type='password' style='background-color:#003399;color:#ffffff;font-weight:bold;' name='password' size='15' maxlength='15'>";

                            echo "<input type='submit' style='background:#003399; color:#ffffff;font-weight:bold;' name='Login' value='Continuar'></div>";

                            echo "</form>";
                        }



//echo "<p align='center'><a href=javascript:(opener=this).close();Ventana('menu.php')><img src='lib/acceso.gif' border=0></a></p><br><br>";
                        echo "<br>$Gfont ";

                        if ($_REQUEST[op] == 99) {

                            echo "<div align='left'><b> &nbsp &nbsp &nbsp Lo siento, no tienes acceso a la sucursal seleccionada</b></div><br>";
                        } elseif ($_REQUEST[op] == 98) {

                            echo "<div align='left'><b> &nbsp &nbsp &nbsp Clave incorrecta</b></div><br>";
                        } elseif ($_REQUEST[Msj]) {

                            echo "<div align='center'><b> &nbsp; &nbsp; &nbsp; La Session ha sido cerrada con exito!!!  &nbsp; &nbsp; &nbsp; PUEDES CERRAR EL NAVEGADOR &nbsp; &nbsp; &nbsp; </b></div><br>";
                        }
                        ?>
                </td>
            </tr>
        </table>
    </body>
</html>

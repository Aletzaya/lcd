<?php

session_start();

date_default_timezone_set("America/Mexico_City");

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/kaplib.php");

$link   = conectarse();
  
$Fec    = date('Y-m-d');
$Usr    = $_COOKIE['USERNAME'];
$Suc    = $_COOKIE['TEAM'];        //Sucursal 
$Vta    = $_SESSION['Venta_ot'];
    
if($_REQUEST[Boton] == "Genera orden"){               // Genera la Orden de trabajo                    

    $Fecha = date("Y-m-d");
    $Hora1 = date("H:i");
    //$Hora2 = strtotime("-60 min",strtotime($Hora1));
    //$hora  = date("H:i",$Hora2);

    $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30
    $OtdA = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otdnvas WHERE usr='$Usr' and venta='$Vta'");
    $Otd  = mysql_fetch_array($OtdA);
    
    $OtA  = mysql_query("SELECT * FROM otnvas WHERE usr='$Usr' AND venta='$Vta'", $link);
    $Ot   = mysql_fetch_array($OtA);

    $lUp  = mysql_query("INSERT INTO os
            (cliente,fecha,institucion,importe,lugar,responsable)
            VALUES
            ('$Ot[cliente]','$Fecha','$Ot[inst]',$Otd[0],'$_REQUEST[Lugar]','$_REQUEST[Responsable]')");
    
    $Id   = mysql_insert_id();

    $lUpA = mysql_query("SELECT otdnvas.estudio,otdnvas.precio,otdnvas.descuento,est.depto
            FROM otdnvas,est
            WHERE usr='$Usr' and venta='$Vta' and otdnvas.estudio=est.estudio");    #Checo k bno halla estudios capturados

    $lBd = false;

    while ($lUp = mysql_fetch_array($lUpA)) {

        $lOtd = mysql_query("INSERT INTO osd (id,estudio,precio,descuento)
                 VALUES
                 ($Id,'$lUp[estudio]','$lUp[precio]','$lUp[descuento]')");

    }

    header("Location: impos.php?busca=$Id&Vta=$Vta");
    
}



  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

   echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

   //headymenu($Titulo,0);

   echo "<table width='100%' border='0'>";

   echo "<tr>";

   echo "<td width='15%' align='left'><a href='menu.php'><img  src='lib/logo2.jpg' border='0'></a></td>";

   echo "<td>&nbsp;</td>";

	echo "<td width=91><img  src='lib/logo40.jpg' border='0'></td>";

   echo "</tr></table>";

 ?>

<script language="JavaScript1.2">
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
        alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function cFocus(){
  document.form1.Lugar.focus();
}

function vacio(q) {
        for ( i = 0; i < q.length; i++ ) {
                if ( q.charAt(i) != " " ) {
                        return true
                }
        }
        return false
}

function Completo() {

        if( vacio(document.form1.Lugar.value)  == false ) {
            alert("Es necesario poner el lugar")
            return false
        } else {
            if( vacio(document.form1.Responsable.value)  == false ) {
                alert("Favor de poner la persona responsable")
                return false
            }
        }

}


</script>

<?php

$OtA  = mysql_query("SELECT otnvas.cliente,cli.nombrec,otnvas.inst
        FROM otnvas LEFT JOIN cli ON otnvas.cliente=cli.cliente
        WHERE otnvas.venta='$Vta' and otnvas.usr='$Usr'");
$Ot   = mysql_fetch_array($OtA);


$InsA = mysql_query("SELECT nombre FROM inst WHERE institucion='$Ot[inst]'");
$Ins  = mysql_fetch_array($InsA);

echo "<table width='100%' cellpadding=0 cellspacing=0 border='0'>";

echo "<tr height='36'><td background='menu/left_cap.gif' width='5'>&nbsp;</td>";

echo "<td background='menu/center_tile.gif'> &nbsp; </td>";

echo "<td background='menu/center_tile.gif' align='right'>$Gfont ";

echo "<img src='lib/msjn.png' border='0'><font color='#ffffff'> $Usr ";
echo " | Sin leer <a class='vt' href=javascript:winuni('msjrec.php')> $nMsj <font color='#69b747'>  mensaje(s) </font></a>";
echo " | <a class='vt' href=javascript:winuni('msjenve.php?busca=NUEVO')> Nvo.mensaje</a> | ";
echo " <a class='vt' href='logout.php'> Salir</a><img src='lib/exit.png' border='0'> &nbsp; ";
echo "</td>";
echo "<td background='menu/right_cap.gif' width='5'>&nbsp;</td>";
echo "</tr></table>";

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";

echo "<tr><td height='370' align='center'>$Gfont ";

    echo "<div align='center'><font size='+1'><b>Orden de sevicio a domicilio</b></font></div>";
      
    echo "<table width='80%' border='0' cellpadding='0' cellspacing='3' style='width: 80%; border-color: #000000;  border-style: solid; border-width: 1px;'>>";

    echo "<tr bgcolor='#e1e1e1'><td align='center'>$Gfont Cliente</td><td align='center'>$Gfont Nombre</td><td align='center'>$Gfont Institucion</td></tr>";

    echo "<tr><td align='center'>$Gfont $Ot[cliente]</td><td>$Gfont $Ot[nombrec]</td><td>$Gfont $Ins[nombre]</td></tr>";
    echo "</table>";
    
    
      echo "<p>&nbsp;</p>";
      
        echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return Completo();'>";

         echo "<p>Lugar de la visita: ";
         echo "<input class='textos' class='textos' name='Lugar' type='text' value='' size='40'> </p>";
         
         echo "<p>Responsable: ";
         echo "<input class='textos' class='textos' name='Responsable' type='text' value='' size='40'> </p>";

         echo "<div align='center'><input type='submit' name='Boton' value='Genera orden'></div>";
      echo "</form>";


echo "</td></tr>";      
echo "<tr background='lib/prueba.jpg' height='80'><td>$Gfont ";

        echo "<a href='ordenesnvas.php'><img src='lib/regresa.jpg'></a> &nbsp; &nbsp; ";
        //echo "<a class='vt' href='oservicio.php'>O.servicio</a> </font>";


echo "</td></tr></table>";
      
mysql_close();

echo "</body>";

echo "</html>";

?>
<style type='text/css'>

a.vt:link {

 color: #003c72;

    font-size: 14px;

    text-decoration: none;

}

a.vt:visited {

    color: #003c72;

    font-size: 14px;

    text-decoration: none;

}

a.vt:hover {

    color: #111111;

    font-size: 14px;

}

</style>


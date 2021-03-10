<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $FechaI=$_REQUEST[FechaI];

  $FechaF=$_REQUEST[FechaF];

  if (!isset($FechaI)){

      $FechaF=date("Y-m-d");

      $FechaI=date("Y-m-")."01";

  }

  $Titulo = "Demanda de afluencia de pacientes por medico del $FechaI al $FechaF";

  if ($_REQUEST[Zona]=='*'){

     $cOtA="select med.medico,med.nombrec,date_format(ot.fecha,'%Y-%m') as fecha,count(*),med.especialidad from ot,med
     WHERE ot.medico=med.medico and ot.fecha Between '$FechaI' And '$FechaF'
     GROUP BY ot.medico,date_format(ot.fecha,'%Y-%m') order by ot.medico, date_format(ot.fecha,'%Y-%m')";

  }else{

     $cOtA="select med.medico,med.nombrec,date_format(ot.fecha,'%Y-%m') as fecha,count(*),med.especialidad from ot,med
     WHERE ot.medico=med.medico and med.zona='$_REQUEST[Zona]' and ot.fecha Between '$FechaI' And '$FechaF'
     GROUP BY ot.medico,date_format(ot.fecha,'%Y-%m') order by ot.medico, date_format(ot.fecha,'%Y-%m')";

  }

  $OtA  = mysql_query($cOtA,$link);

  $Mes  = array("","Enero","Feb","Mzo","Abr","Mayo","Jun","Jul","Agost","Sept","Oct","Nov","Dic");

  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#ffffff'> &nbsp; ";

  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0");

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

    <?php
    headymenu($Titulo, 0);

    $Ini = 0 + substr($FechaI, 0, 4) . substr($FechaI, 5, 2);
    $Fin = 0 + substr($FechaF, 0, 4) . substr($FechaF, 5, 2);
    //$Fin=0+substr($FechaF,5,2);

    $Tit = "<tr bgcolor='$GfdoTitulo'><td align='center'>$Gfont <font color='#ffffff'> Medico </td><td center='center'>$Gfont  <font color='#ffffff'>Nombre</td><td center='center'>$Gfont  <font color='#ffffff'>Especialidad</td>";

    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $Tit = $Tit . "<td align='center'>$Gfont <font color='#ffffff'> $Mes[$x]</td>";
    }

    $Tit = $Tit . "<td align='center'>$Gfont <font color='#ffffff'> Total</td></tr>";

    echo "<p>$Gfont ";
    if ($_REQUEST[Zona] == '*') {
        echo "Zona: * todas </p>";
    } else {
        $ZnA = mysql_query("select descripcion from zns where zona='$_REQUEST[Zona]'", $link);
        $Zn = mysql_fetch_array($ZnA);
        echo "Zona: $_REQUEST[Zona] $Zn[0] </p>";
    }

    echo "<table width='90%' align='center' border='0'>";

    echo $Tit;
    $Med = 'XXX';
    while ($reg = mysql_fetch_array($OtA)) {
        if ($reg[medico] <> $Med) {
            if ($Med <> 'XXX') {
                $cTit = '';
                $SubT = 0;
                for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
                    if (substr($i, 4, 2) == '13') {
                        $i = $i + 88;
                    }
                    $x = substr($i, 4, 2) * 1;
                    $cTit = $cTit . "<td align='center'>$Gfont $aCnt[$x]</font></td>";
                    $tCnt[$x] = $tCnt[$x] + $aCnt[$x];
                    $SubT += $aCnt[$x];
                    $GraT += $aCnt[$x];
                }
                echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';><td>$Gfont $Med</td><td>$Gfont $Nombre</font></td><td>$Gfont $Esp</font></td>";
                echo $cTit . "<td align='right'>$Gfont $SubT</td></tr>";
            }
            $Med = $reg[medico];
            $Esp = $reg[especialidad]; 
            $Nombre = $reg[nombrec];
            $aCnt = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
        }
        $Fec = $reg[fecha];
        $Pos = 0 + substr($Fec, 5, 2);
        $aCnt[$Pos] = $reg[3];
    }
    $cTit = '';
    $SubT = 0;
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $cTit = $cTit . "<td align='center'>$Gfont $aCnt[$x]</font></td>";
        $SubT+=$aCnt[$x];
        $GraT+=$aCnt[$x];
    }
    echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';><td>$Gfont $Med</td><td>$Gfont $Nombre</font></td>";
    echo $cTit . "<td align='right'>$Gfont $SubT</td></tr>";

    $cTit = '';
    for ($i = $Ini; $i <= $Fin; $i = $i + 1) {
        if (substr($i, 4, 2) == '13') {
            $i = $i + 88;
        }
        $x = substr($i, 4, 2) * 1;
        $cTit = $cTit . "<td align='center'>$Gfont $tCnt[$x]</font></td>";
    }
    echo "<tr><td>&nbsp;</td><td align='right'>$Gfont Total</font></td>";
    echo $cTit . "<td align='right'>$Gfont $GraT</td></tr>";

    echo "</table>";

    echo "<table align='center' width='90%' border='0'>";

    echo "<tr><td width='5%'><a href='menu.php'><img src='lib/regresa.jpg' border='0'></a>";

    echo "</td><td>$Gfont ";

    echo "<form name='form1' method='post' action='repdemandamed.php'><br><br>";

    echo "<a class='pg' href='menu.php'>Regresar</a> &nbsp; ";

    echo " Fecha Inicial: <input type='text' name='FechaI' value='$FechaI' size='10' maxlength='10'>";

    echo " Fecha Final: <input type='text' name='FechaF' value='$FechaF' size='10' maxlength='10'> ";

    echo " &nbsp; &nbsp; Zona : ";
    $InsA = mysql_query("select zona,descripcion from zns", $link);
    echo "<select name='Zona'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[0]'>$Ins[1]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select> &nbsp; &nbsp; ";

    echo " <INPUT TYPE='SUBMIT'  name='Boton' Value='Enviar'>";

    $Sql = str_replace("'", "!", $cOtA);  //Remplazo la comita p'k mande todo el string

    echo " &nbsp; <a class='pg' href='bajarep.php?cSql=$Sql'> Exporta </a> </form>";

    echo "</font></td></tr></table>";

    echo "<form name='form2' method='post' action='menu.php'>";
    echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
    echo "</form>";

    mysql_close();
    ?>

</body>

</html>
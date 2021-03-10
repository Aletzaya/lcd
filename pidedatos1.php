<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $level       = $check['level'];

  $Titulo = "Captura de datos";

  $Fecha    = date("Y-m-d");
  $FechaIni    = date("Y-m-d");
  //$FechaIni = strtotime("-1 day", strtotime($Fecha));
  //$FechaIni = date ("Y-m-d", $FechaIni);

  
  $Titulo="Reporte de Pagos";

  require ("config.php");							//Parametros de colores;

?>

<html>

<head>
<title><?php echo $Titulo;?></title>

</head>


<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<script language="JavaScript1.2">

function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}

</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

headymenu($Titulo,1);


 $Fecha   = date("Y-m-d");

 echo "<table width='100%' align='center' border='0' cellpadding=0 cellspacing=0>";

 //echo "<tr><td height='300' align='center'>$Gfont ";

     echo "<form name='form1' method='get' action='detpagos.php'>";

    echo "<p align='center'>Sucursal : ";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Matriz</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr></p>";

     echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Pagos: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT id,referencia FROM cptpagod ORDER BY referencia");
    echo "<select class='InputMayusculas' name='Pagos'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[referencia]'> &nbsp; $Ins[referencia] </option>";
    }
    echo "<option selected value=''> &nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Forma de pago: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT * FROM cpagos ORDER BY id");
    echo "<select class='InputMayusculas' name='Fpago'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[id]'> &nbsp;$Ins[id].- $Ins[concepto] </option>";
    }   
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";

    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Usuario: </td><td>&nbsp; ";
    $InsA   = mysql_query("SELECT uname FROM authuser ORDER BY uname");
    echo "<select class='InputMayusculas' name='Usr'>";           
    while($Ins = mysql_fetch_array($InsA))
    {echo "<option value='$Ins[uname]'> &nbsp; $Ins[uname] </option>";}
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";
          
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Pagos cancelados: </td><td>&nbsp; ";
    echo "<select class='InputMayusculas' name='Cancelado'>";
    echo "<option value='1'>&nbsp; Si </option>";
    echo "<option value='0'>&nbsp; No</option>";
    echo "<option selected value=''>&nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    
    echo "<tr height='30'>";
    echo "<td align='right' width='33%' >";
    echo "$Gfont Fecha inicial: ";
    echo "</td><td valign='center'> &nbsp ";
    echo "<input type='text' name='FechaI' value='$FechaIni' size='10'  class='content_txt'> ";
    echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr>";
    
    echo "<tr><td align='right'> ";
    echo "$Gfont Fecha final: ";
    echo "</td><td> &nbsp ";
    echo "<input type='text' name='FechaF' value='$Fecha' size='10'  class='content_txt'> ";      
    echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></td></tr><tr><td>";
    echo "<br><br></td><td><input class='boton' type='submit' name='Boton' value='enviar'>";
    echo "</td></tr>";   
    
    echo "</form>";

  echo "<tr><td colspan='2'><a href='menu.php'><img src='lib/regresa.jpg' border='0'></a></td></tr>";

  echo "<tr background='lib/prueba.jpg'>";

  echo "<td colspan='2'><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></td>";

  echo "</tr></table>";
    
echo "</body>";

echo "</html>";

?>

<?php

  session_start();

  require("lib/lib.php");
  date_default_timezone_set("America/Mexico_City");
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");


  $Usr       = $check['uname'];
  $busca     = $_REQUEST[busca];
  $pagina    = $_REQUEST[pagina];
  $orden     = $_REQUEST[orden];
  $Estudio   = $_REQUEST[Estudio];
  $status    = $_REQUEST[status];

  $Msj       = "";
  $Fecha     = date("Y-m-d");
  $Fechaest  = date("Y-m-d H:i:s");
  $Hora      = date("h:i:s");
  $Titulo    = "Observaciones de recepcion de muestra [$Estudio]";

  $link      = conectarse();

  $Tabla     = "maqdet";
  
  if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo
        $lUp2   = mysql_query("UPDATE maqdet set obsrec='$_REQUEST[Observaciones]' WHERE orden='$orden' and estudio='$Estudio'");
        echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
  }

  $cSqlH     = "SELECT ot.orden,ot.fecha,ot.hora,ot.fechae,ot.cliente,cli.nombrec,ot.institucion
                FROM ot,cli
                WHERE ot.cliente=cli.cliente and ot.orden='$orden'";

  $cSqlD     = "SELECT maqdet.orden,maqdet.estudio,maqdet.mint,maqdet.mext,maqdet.fenv,maqdet.henv,maqdet.usrenv,maqdet.frec,
  				maqdet.hrec,maqdet.usrrec,maqdet.obsenv,maqdet.obsrec,est.descripcion
                FROM maqdet,est
                WHERE maqdet.estudio=est.estudio AND maqdet.orden='$orden' and maqdet.estudio='$Estudio'";

  $HeA       = mysql_query($cSqlH);
  $He        = mysql_fetch_array($HeA);

  $res 		 = mysql_query($cSqlD);
  $registro  = mysql_fetch_array($res);

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';

require ("config.php");

echo "<html>";

echo "<title>$Titulo</title>";

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";

echo "<tr bgcolor ='#618fa9'>";
echo "<td colspan='2'>$Gfont <font size='2'><font color='#ffffff'>Orden: $He[institucion] - $He[orden] &nbsp; &nbsp; Cliente: $He[cliente] - $He[nombrec] </font></td>";
echo "</tr>";
echo "</table>";

echo "<table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'><tr><td>$Gfont";

echo "<br>";

echo "<table align='center' width='100%' border='0' cellspacing='0' cellpadding='0'>";
echo "<tr align='center' bgcolor ='#618fa9'>";
echo "<td>&nbsp;</td>";
echo "<td>&nbsp;</font></td>";
echo "<td colspan='3'>$Gfont <font size='1' color='#ffffff'><b>Envio</b></font></td>";
echo "<td colspan='3'>$Gfont <font size='1' color='#ffffff'><b>Recibe</b></font></td>";
echo "</tr>";
echo "<tr align='center' bgcolor ='#618fa9'>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Estud</b></font></td>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Descripcion</b></font></td>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Fecha</b></font></td>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Hora</b></font></td>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Usuario</b></font></td>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Fecha</b></font></td>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Hora</b></font></td>";
echo "<td>$Gfont <font size='1' color='#ffffff'><b>Usuario</b></font></td>";
echo "</tr>";

echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
echo "<td align='center'>$Gfont <font size='1'><b>$registro[estudio]</b></font></td>";
echo "<td>$Gfont <font size='1'><b>$registro[descripcion]</b></font></td>";
echo "<td align='center'>$Gfont <font size='1'><b>$registro[fenv]</b></font></td>";
echo "<td align='center'>$Gfont <font size='1'><b>$registro[henv]</b></font></td>";
echo "<td align='center'>$Gfont <font size='1'><b>$registro[usrenv]</b></font></td>";
echo "<td align='center'>$Gfont <font size='1'><b>$registro[frec]</b></font></td>";
echo "<td align='center'>$Gfont <font size='1'><b>$registro[hrec]</b></font></td>";
echo "<td align='center'>$Gfont <font size='1'><b>$registro[usrrec]</b></font></td>";
echo "</tr>";

echo "</table> <br>";

echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
echo "<tr><td align='center'><form name='form1' method='post' action='obsmqlrec.php?orden=$orden&Estudio=$Estudio'>";
echo "$Gfont Observaciones:&nbsp;";
//echo "$Gfont <strong>Observaciones:&nbsp;</strong>";
echo "<TEXTAREA NAME='Observaciones' cols='70' rows='3'>$registro[obsrec]</TEXTAREA>";
echo Botones2();
echo "</td></tr>"; 
echo "</table> <br>";
echo "</form>";

echo "</body>";

echo "</html>";

mysql_close();
?>
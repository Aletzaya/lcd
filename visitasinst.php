<?php

  session_start();
    
  require("lib/lib.php");

  $link     = conectarse();  

  #Variables comunes;
  $Titulo = "Registro de visitas";
  $Inst  = $_REQUEST[Inst];	
  $FechaI	=	$_REQUEST[FechaI];
  $FechaF	=	$_REQUEST[FechaF];
  
  require ("config.php");							//Parametros de colores;
  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
  $Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
  $Gfon2 = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

echo "<body bgcolor='#FFFFFF'>";

$registro   = mysql_fetch_array(mysql_query("SELECT institucion,nombre as nominstitucion FROM inst WHERE institucion='$Inst'")); 

echo "<table width='100%' height='80' border='0'>";    //Encabezado
echo "<tr><td width='26%' height='76'>";
echo "<p align=='left'><img src='images/Logotipo%20Duran4.jpg' width='187' height='61'></p>";
echo "</td>";
echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de Visitas</p>";
echo "</td></tr></table>";
echo "<p><strong><font size='1' face='Arial, Helvetica, sans-serif'>Institucion: $Inst - $registro[nominstitucion] </strong></p>";

echo "<hr>";
echo "<table width='100%' border='0'>";    //Encabezado
echo "<tr>";
echo "<th align='center' height='26'>$Gfont Movto</th>";
echo "<th align='center' height='26'>$Gfont Periodo</th>";
echo "<th align='center' height='26'>$Gfont Fec/vis</th>";
echo "<th height='26'>$Gfont Comentario</th>";
echo "</tr>";

      $PgsA   = mysql_query("SELECT periodo,fecha,nota,movto FROM vinst WHERE vinst.inst='$Inst' and date_format(vinst.fecha,'%Y%m') Between '$FechaI' And '$FechaF'"); 

      while($rg=mysql_fetch_array($PgsA)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'>$Gfont $rg[movto]</td>";
           echo "<td align='center'>$Gfont $rg[periodo]</td>";
           echo "<td align='center'>$Gfont $rg[fecha]</td>";
           echo "<td align='left' width='500px'>$Gfont $rg[nota]</td>";
           echo "</tr>";

           $nRng++;

      }

	
	
   echo "</table>";     

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
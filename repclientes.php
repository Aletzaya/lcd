<?php


session_start();

require("lib/lib.php");

$link       = conectarse();

      
#Variables comunes;
$FechaI     = $_REQUEST[FechaI];
$FechaF     = $_REQUEST[FechaF];

$Detallado  = $_REQUEST[Detallado];

if (!isset($FechaI)){
     $FechaF=date("Y-m-d");
     $FechaI=date("Y-m-")."01";
}

$Titulo="Morbilidad de clientes del $FechaI al $FechaF";

if($Detallado == 'No'){
  $CliA   =  mysql_query("SELECT fecha, count( * ) as cantidad
             FROM `cli`
             WHERE fecha >= '$FechaI' AND fecha <= '$FechaF'
             GROUP BY fecha");
}else{
  $CliA   =  mysql_query("SELECT cli.fecha, cli.cliente, cli.nombrec,cli.colonia, cli.municipio,cli.usr,cli.institucion,inst.alias
             FROM `cli`LEFT JOIN inst ON cli.institucion=inst.institucion
             WHERE fecha >= '$FechaI' AND fecha <= '$FechaF'
             ORDER BY fecha, cliente");
    
}
   
  require ("config.php");							//Parametros de colores;
  
echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>";
echo "<html>";
echo "<head>";

echo "<title>::: Sistema de labortorio clinico</title>";

//echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";

echo "<meta http-equiv='content-type' content='text/html; charset=utf-8'/>";

echo '<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></LINK>';

echo '<SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>';

//echo "<link href='lib/textos.css' rel='stylesheet' type='text/css'>";

echo "</head>";

 echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

  headymenu("Menu principal (inicio)",1);
  
  echo "<table border='0' width='100%' align='center' border=0 cellpadding=0 cellspacing=0>";

  echo "<tr><td height='280' align='center'>$Gfont ";


                    echo "<table width='90%' align='center' border='0' class='textosItalicos'>";

                    if($Detallado == 'No'){
                        echo "<tr bgcolor=$Gbgtitulo>";
                        echo "<th>$Gfont Fecha </b>&nbsp; &nbsp; </th>";
                        echo "<th>$Gfont Cantidad<b></th>";
                        echo "</tr>";
                    }else{
                        echo "<tr bgcolor=$Gbgtitulo>";
                        echo "<th> &nbsp; </th>";
                        echo "<th>$Gfont Fecha </b>&nbsp; &nbsp; </th>";
                        echo "<th>$Gfont Institucion </b> &nbsp; </th>";
                        echo "<th>$Gfont Cuenta </b>&nbsp; &nbsp; </th>";
                        echo "<th>$Gfont Nombre </b>&nbsp; &nbsp; </th>";
                        echo "<th>$Gfont Municipio<b></th>";
                        echo "<th>$Gfont Colonia<b></th>";
                        echo "<th>$Gfont Usuario<b></th>";
                        echo "</tr>";                                                
                    }    

                    while($reg=mysql_fetch_array($CliA)){

                        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF'; }else{$Fdo=$Gfdogrid;}
           		echo "<tr bgcolor=$Fdo onMouseOver=this.style.backgroundColor='#a7c3f2';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        if($Detallado == 'No'){
                            echo "<td align='right'>$Gfont  $reg[fecha] &nbsp;</font></td>";
                            echo "<td align='right'>$Gfont  $reg[cantidad] &nbsp;</font></td>";
                            $nCnt  += $reg[cantidad];
                        }else{
                            echo "<td align='right'><a class='Seleccionar' href=javascript:winuni('repclientese.php?busca=$reg[cliente]')>seleccionar</a></td>";
                            echo "<td align='right'>$Gfont  $reg[fecha] &nbsp;</font></td>";
                            echo "<td align='right'>$Gfont  $reg[alias] &nbsp;</font></td>";
                            echo "<td align='right'>$Gfont  $reg[cliente] &nbsp;</font></td>";
                            echo "<td align='left'>$Gfont  ".$reg[nombrec]." </td>";
                            echo "<td align='left'>$Gfont  ".$reg[municipio]." </td>";
                            echo "<td align='left'>$Gfont  ".$reg[colonia]." </td>";                            
                            echo "<td align='left'>$Gfont  ".$reg[usr]." </td>";                            
                            $nCnt  ++;
                        }        
                        echo "</tr>";
                    }
                    if($Detallado == 'No'){
                       echo "<tr><td align='right'>$Gfont Total ---> </td><td align='right'>$Gfont ".number_format($nCnt,"0")."</td></tr>";
                    }else{
                       echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td align='right'>$Gfont Total ---> </td><td align='right'>$Gfont ".number_format($nCnt,"0")."</td></tr>";                        
                    }    
                    echo "</table>";

                    echo "<form name='form2' method='get' action=" . $_SERVER['PHP_SELF'] . ">$Gfont";

			echo "<div align='left'>&nbsp; Fecha Inicial: <input type='text' name='FechaI' value='$FechaI' size='10' maxlength='10'>";
                        echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

			echo "&nbsp; Fecha Final: </font><input type='text' name='FechaF' value='$FechaF' size='10' maxlength='10'> ";
                        echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
            
                        echo " &nbsp; Detallado:";
                        
                        echo "<select name='Detallado'>";
                        echo "<option value='Si'>Si</option>";
                        echo "<option value='No'>No</option>";
                        echo "<option selected value='$_REQUEST[Detallado]'>$_REQUEST[Detallado]</option>";
                        echo "</select>  &nbsp; &nbsp; &nbsp; <input type='submit' name='Boton' value='Enviar'> ";

			echo "<input type='submit' name='Boton' value='Enviar'></div>";

                    echo "</form>";

    
   echo "</td></tr></table>";    

echo "</body>";

echo "</html>";
/*
   echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='textosItalicos'>";
	echo "<tr height='45' class='footer' align='right'><td align='center'>";
	echo " © TECNOLOGIAS ENLACE Texcoco Edo. de M&eacute;xico 2011. All rights reserved Ver. 2.0<br>";
	//echo "Campo Florido #76 col. Tulantongo tel 01(595) 9250401 nxtl: 015546164781";	
	echo "</td></tr>";
	echo "</table>";   
*/   

?>
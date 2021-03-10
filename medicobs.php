<?php
  session_start();

  require("lib/kaplib.php");

  $link = conectarse();

  $busca  = $_REQUEST[orden];

  $Titulo = "Informacion Importante del Medico";

  $CpoA   = mysql_query("SELECT * FROM med WHERE medico='$busca'");
  
  $Cpo    = mysql_fetch_array($CpoA);
  
  require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body>

<?php 
    
    //headymenu($Titulo,0);
    

    echo "<br><p align='center'>$Gfont <b><font size='+1'>$Titulo</a></b></font></p>";    
    
    echo "<table width='100%' border='1'>";

    echo "<tr>";
  
      echo "<td>$Gfont"; 
	
            cTable('100%','0');

            cInput("<b>Observaciones: </b>","Text","40","Observaciones","left",$Cpo[observaciones],"40",false,true,'');
      		echo "<td>$Gfont</td>"; 
            cInput("<b>Servicio: </b>","Text","40","Servicio","left",$Cpo[servicio],"40",false,true,'');
            cTableCie();
            
           echo "<p align='center'><b><a class='pg' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></b></p>";
            
      
      echo "</td>";
    echo "</tr>";
    echo "</table>";
    
echo "</body>";

echo "</html>";
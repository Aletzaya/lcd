<?php

  session_start();
    
  require("lib/lib.php");

  $link     = conectarse();  

  #Variables comunes;
  $Titulo = "Registro de visitas";
  $busca  = $_REQUEST[busca];	

  require ("config.php");							//Parametros de colores;

echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

echo "<body bgcolor='#FFFFFF'>";

     	echo "<table width='100%' border='0'>";    //Encabezado
   	echo "<tr>";
   	echo "<th align='center' height='26'>$Gfont Period</th>";
   	echo "<th align='center' height='26'>$Gfont Fec/vis</th>";
   	echo "<th height='26'>$Gfont Comentario</th>";
   	echo "<th height='26'>$Gfont Comision</th>";
		echo "</tr>";

      $PgsA   = mysql_query("SELECT periodo,fecha,nota,importe FROM pgs WHERE medico='$busca'"); 

      while($rg=mysql_fetch_array($PgsA)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'>$Gfont $rg[periodo]</td>";
           echo "<td align='center'>$Gfont $rg[fecha]</td>";
           echo "<td align='left'>$Gfont $rg[nota]</td>";
           echo "<td align='right'>$Gfont".number_format($rg[importe],"2")."</td>";
           echo "</tr>";

           $nRng++;

      }

	
	
   echo "</table>";     

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
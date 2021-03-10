<?php

  session_start();
    
  require("lib/lib.php");

  $link     = conectarse();  

  $busca  = $_REQUEST[busca]; 

  $MediA  = mysql_query("SELECT * FROM med WHERE medico='$busca'"); 
  $Medi   = mysql_fetch_array($MediA);

  #Variables comunes;
  $Titulo = "Registro de modificaciones de Medico - $busca $Medi[nombrec]";

  require ("config.php");							//Parametros de colores;

echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

echo "<body bgcolor='#FFFFFF'>";

     	echo "<table width='100%' border='0'>";    //Encabezado
   	echo "<tr>";
   	echo "<th align='center' height='26'>$Gfont Fecha</th>";
   	echo "<th align='center' height='26'>$Gfont Usuario</th>";
   	echo "<th height='26'>$Gfont Concepto</th>";
		echo "</tr>";

      $PgsA   = mysql_query("SELECT * FROM logmed WHERE medico='$busca' order by fecha desc"); 

      while($rg=mysql_fetch_array($PgsA)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'>$Gfont $rg[fecha]</td>";
           echo "<td align='center'>$Gfont $rg[usr]</td>";
           echo "<td align='left'>$Gfont $rg[concepto]</td>";
           echo "</tr>";

           $nRng++;

      }

  echo "<div align='center'>$Gfont <b> $nRng Modificaciones al registro de Medico $busca - $Medi[nombrec]<b></div><br>";
	
   echo "</table>";     

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
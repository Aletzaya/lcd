<?php 

  include_once ("authconfig.php"); 

  require ("config.php");
  
  echo "<htm>";
  
  echo "<html>";
  
  echo "<head>";
  
  echo "<title>Entrada al sistema</title>";
  
  echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
  
  echo "</head>";
  
//  echo "<body bgcolor='#FFFFFF' text='#000000'>";
  
 ?>
  
  <body bgcolor="#FFFFFF" onload="cFocus()">

  <script language="JavaScript1.2">

  function cFocus(){

    document.Sample.username.focus();

  }

  </script>
  
  <?php

  
  echo "<p>&nbsp;</p><p>&nbsp;</p>";
  

  echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' align='center' bgcolor='#FFFFFF'>";  echo "<tr bgcolor='e3e3e3' height='32'>";
  echo "<td align='center'><font size='+2' color='#065091'><b>$Gcia</b></font></td>";
  echo "</tr>";
  echo "</table>";

  echo "<p>&nbsp;</p>";
  
  echo "<table width='60%' border='0' cellpadding='0' cellspacing='0' align='center' bgcolor='#FFFFFF'>";  
  echo "<tr>";
  
  echo "<td>&nbsp;</td>";
  
  echo "<td valign='top' align='center' height='244' width='442' background='lib/box2.jpg'>$Gfont ";


   echo "<table width='98%' border='0' cellpadding='0' cellspacing='0' align='center'>";  
      echo "<tr><td valign='top'><img src='lib/candado.jpg' border='0'></td>";
   
      echo "<td>$Gfont <font size='+1'>";
   
      echo "<form name='Sample' method='post' action='$resultpage'>";

		echo "<div align='center'><b>Datos de acceso &nbsp; </b></div></font><br><br>";
			
		echo "<div align='left'>Usuario:</div>";
			
      echo "<input type='text' style='background-color:#638AD9; color:#ffffff;font-weight:bold;' name='username' size='15' maxlength='15' onKeyUp='this.value = this.value.toUpperCase();' >";

		echo "<div align='left'>Clave:</div>";
  
      echo "<input type='password' style='background-color:#638AD9;color:#ffffff;font-weight:bold;' name='password' size='15' maxlength='15'><br><br>";

      echo "<div><input type='submit' style='background:#51649E; color:#ffffff;font-weight:bold;' name='Login' value='Continuar'></div>";

      //echo "<input type='reset' name='Clear' value='Limpiar'></div>";

      echo "</form>";

      echo "</td></tr>";
   
   echo "</table>";  
  
  echo "</td>";
  
  echo "<td>&nbsp;</td>";

    
  echo "</tr>";
  
  echo "</table>";

echo "</body>";

echo "</html>";

?>

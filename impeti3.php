<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link  = conectarse();

  $Fecha = "01/08/2014";

?>


<html>

<head>

<title>Impresion de etiquetas</title>

</head>

<body bgcolor='#FFFFFF' >

<?php

$busca = $_REQUEST[busca];
 
$Est   = $_REQUEST[Est];  
  
$OtA   = mysql_query("SELECT ot.fecha,ot.cliente,cli.nombrec,ot.institucion
         FROM ot,cli,inst 
         WHERE ot.orden='$busca' and ot.cliente=cli.cliente and inst.institucion=ot.institucion");

$Ot   = mysql_fetch_array($OtA);
	 
     echo "<table width='100%' border='0' align='center'>";

     echo "<tr>";

     echo "<td width='100%' height='50' align='center'>";
	 
	   	  echo "<font size='2' face='Calibri, Courier, mono'><strong>H. G. GUADALUPE VICTORIA</strong></font>";

  		  echo "<br>";
		  
	   	  echo "<font size='2' face='Calibri, Courier, mono'><strong>ISEM TEXCOCO</strong></font>";
		  
		  echo "<br>";
		  
		  echo "<br>";
		  
  		  echo "<font size='2' face='Calibri, Courier, mono'>$Ot[nombrec]</font>";

  		  echo "<br>";
		  		
  		  echo "<font size='2' face='Calibri, Courier, mono'><strong>Fecha: $Fecha</strong>";

		  echo "<br>";

		  echo "<div align='center'><font size='1' face='Calibri, Courier, mono'>";

  		  echo "<br>";
		  
		  echo "<input name='Imp' face='Calibri, Courier, mono' type='submit' onClick='print()' value='No.Orden: $Ot[institucion] - $busca'>";

     echo "</td>";

     echo "</tr>";

     echo "</table>";
   
   echo "<br>";		//Espacios entre etiqueta y etiqueta;
   
echo "</body>";

echo "</html>";

?>
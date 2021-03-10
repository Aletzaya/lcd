<?php
  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  require("lib/lib.php");

  $op		=	$_REQUEST[op];
  $busca	=	$_REQUEST[busca];
  $Estudio	=	$_REQUEST[Estudio];

  $Usr		=	$check['uname'];

  if($op == ac){

	 $Fecha = date("Y-m-d H:m");

	 $PrmA  = mysql_query("SELECT passwordadmin FROM cia WHERE id='1'");
	 $Prm   = mysql_fetch_array($PrmA);

	 $Clave = md5($_REQUEST[Password]);

	 if($Prm[0] == $Clave ){

  		$lUp   = mysql_query("UPDATE otd SET descuento = '$_REQUEST[Descuento]',razon = '$_REQUEST[Razon]' WHERE orden='$busca' AND estudio='$Estudio'");
		$TotA  = mysql_query("SELECT sum( precio * ( 1 - descuento /100 ) ) FROM otd
				 WHERE orden ='$busca'");
		$Tot   = mysql_fetch_array($TotA);

		$lUp   = mysql_query("UPDATE ot SET importe = '$Tot[0]' WHERE orden='$busca'");


	 }

   header("Location: ordenesd.php?busca=$busca");

  }


 $CpoA  = mysql_query("SELECT * FROM  otd WHERE orden='$busca' AND estudio='$Estudio'");
 $Cpo   = mysql_fetch_array($CpoA);


  $link		=	conectarse();

  require ("config.php");

echo "<html>";
echo "<head>";
echo "<title>$Titulo</title>";
echo "</head>";


 echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

 headymenu($Titulo,0);

echo '<table width="87%" height="149" border="0">';
echo '<tr>';
    echo "<td height='145' align='center'>$Gfont ";
    echo "<p><strong>";
    echo "Descuento por estudio";
	echo "</strong></p>";
    echo "<form name='form1' method='post' action='descuentotd.php'>";
    echo "<p>$Gfont Razon :";
    echo "<input name='Razon' type='text' size='30' value=$Cpo[razon]''>";
    echo "</p>";
    echo "<p>% de Descuento :";
    echo "<input name='Descuento' type='text' value='$Cpo[descuento]' size='5'>";
    echo "</p>";
    echo "<div align='center'>Password: ";
    echo "<input type='password' style='background-color:#bacbc2;color:#ffffff;font-weight:bold;' name='Password' size='15' maxlength='15'>";
    echo "<input type='submit' name='Boton' value='Envair'>";
    echo "</div>";
    echo "<input type='hidden' name='Estudio' value='$Estudio'>";
    echo "<input type='hidden' name='busca' value='$busca'>";
    echo "<input type='hidden' name='op' value='ac'>";
    echo "</form>";
    echo "</td>";
echo "</tr>";
echo "</table>";

echo "<a class='pg' href='ordenesd.php?busca=$busca'><img src='lib/regresa.jpg' border='0'></a>";

echo '<hr noshade style="color:66CC66;height:3px">';
echo "</body>";
echo "</html>";

?>

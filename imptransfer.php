<?php

  session_start();
  require("lib/lib.php");
  $link   = conectarse();

  $busca  = $_REQUEST[busca];
  
  $Titulo = "Transferencia";

  $HeA = mysql_query("SELECT id,fecha,hora,cant,costo,usrdest,usrorin,origen,destino
         FROM trans
         WHERE id = '$busca'");

  $He=mysql_fetch_array($HeA);

  require ("config.php");

  require_once("importeletras.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

  //headymenu($Titulo);

  echo "<body>";

  echo "<div align='left'>$Gfont &nbsp; &nbsp; &nbsp;  <font size='+1'>$Gcia</font></font></div><br>";

  echo "<table align='center' width='95%' border='0' >";

  echo "<tr>";
  echo "<td>$Gfont <b> No.Transferencia: $He[id]</b></td>";
  echo "<td align='right'>$Gfont Fecha: $He[fecha]</td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont Entrega: $He[origen] - $He[usrorin]</td>";
  echo "<td align='right'>$Gfont Cantidad: ".number_format($He[cant],"2")."</td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont Recibe: $He[destino] - $He[usrdest] &nbsp; &nbsp; </td>";
  echo "<td align='right'>$Gfont  </td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont  </td>";
  echo "<td align='right'>$Gfont Costo: $ ".number_format($He[costo],"2")." </td>";
  echo "</tr>";
  
  echo "</table>";
    
  //echo "<hr>";

  $Sql = "SELECT transd.clave,transd.cantidad,transd.costo,invl.descripcion
         FROM transd, invl
         WHERE transd.id = '$busca' AND transd.clave = invl.clave
         ORDER BY transd.clave";

  $Rolls = $Peso = $Defectos = 0;
  $res   = mysql_query($Sql);

  echo "<table align='center' width='98%' border='0'>";
  echo "<tr>";
  echo "<th>$Gfont Producto</th>";
  echo "<th>$Gfont  Descripcion</th>";
  echo "<th>$Gfont  Cantidad</th>";
  echo "<th>$Gfont  Costo</th>";
  echo "<th>$Gfont  &nbsp; Importe</th>";
  echo "</tr>";
  
  
  while( $rg = mysql_fetch_array($res) ){
	   echo "<tr>";
  		echo "<td>$Gfont $rg[clave] </td>";
  		echo "<td>$Gfont $rg[descripcion] </td>";
  		echo "<td align='right'>$Gfont $rg[cantidad]</td>";
  		echo "<td align='right'>$Gfont ".number_format($rg[costo],"2")."</td>";
  		echo "<td align='right'>$Gfont ".number_format($rg[costo]*$rg[cantidad],"2")."</td>";
  		echo "</tr>";
  		$Costo += $rg[costo]*$rg[cantidad];                  				
  }                

  echo "</table>";
  

  echo "<p>&nbsp;</p>";
    
  $Imp = impletras($He[costo],'pesos');
  
  echo "<div align='left'>$Gfont &nbsp; &nbsp;  $Imp</div>";
   
     echo "<div>&nbsp;</div>";
     echo "<div>&nbsp;</div>";
     echo "<div>&nbsp;</div>";
     echo "<div>&nbsp;</div>";
     echo "<div>&nbsp;</div>";
  
  echo "<div align='center'>________________________________</div>";
  echo "<div align='center'>Firma de recibido</div>";
  echo "<div align='center'>$He[recibio]</div>";
    
  echo "<table align='center' width='95%' border='0' >";

  echo "<tr>";
  echo "<td width='65%'>$Gfont </td>";
  echo "<td align='right'>$Gfont Costo: $</td>";
  echo "<td align='right'>$Gfont ".number_format($Costo,"2")." &nbsp; </td>";
  echo "</tr>";
  
  echo "</table>";
    
  echo "<br>";

  echo "<form name='form1' method='post' action='imptransfer.php?busca=$busca'>";

     echo "<input type='submit' name='Imprimir' value='Imprime' onClick='print()'>";

  echo "</form>";
    
echo "</body>";

echo "</html>";

mysql_close();


?>
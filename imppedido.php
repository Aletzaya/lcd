<?php

  session_start();
  require("lib/lib.php");
  $link   = conectarse();

  $busca  = $_REQUEST[busca];
  
  $Titulo = "Pedido";

  //$cSqlD="select sd.id,sd.fecha,sd.cliente,sd.kilos,sd.bolsas,sd.ubi,sd.status,cli.alias from sd left join cli on sd.proveedor=cli.id where sd.id = '$busca'";
  $HeA = mysql_query("SELECT pedido.id,pedido.fecha,pedido.hora,pedido.concepto,pedido.cantidad,pedido.importe,pedido.recibio
         FROM pedido
         WHERE pedido.id = '$busca'");

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
  echo "<td>$Gfont <b> No.Pedido: $He[id]</b></td>";
  echo "<td align='right'>$Gfont Fecha: $He[fecha]</td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont Concepto: $He[concepto]</td>";
  echo "<td align='right'>$Gfont Cantidad: ".number_format($He[cantidad],"2")."</td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont Solicito: $He[recibio] &nbsp; &nbsp; </td>";
  echo "<td align='right'>$Gfont  </td>";
  echo "</tr>";

  echo "<tr>";
  echo "<td>$Gfont  </td>";
  echo "<td align='right'>$Gfont Costo: $ ".number_format($He[importe],"2")." </td>";
  echo "</tr>";
  
  echo "</table>";
    
  //echo "<hr>";

  $Sql = "SELECT pedidod.clave, pedidod.cantidad, pedidod.costo, invl.descripcion, pedidod.existencia
         FROM pedidod, invl
         WHERE pedidod.id = '$busca' AND pedidod.clave = invl.clave
         ORDER BY pedidod.clave";

  $Rolls = $Peso = $Defectos = 0;
  $res   = mysql_query($Sql);

  echo "<table align='center' width='98%' border='0'>";
  echo "<tr>";
  echo "<th>$Gfont Producto</th>";
  echo "<th>$Gfont  Descripcion</th>";
  echo "<th>$Gfont  Cnt</th>";
  echo "<th>$Gfont  Costo</th>";
  echo "<th>$Gfont  Importe</th>";
  echo "<th>$Gfont  Exist.</th>";
  echo "<th>$Gfont  Status</th>";
  echo "</tr>";
  
  
  while( $rg = mysql_fetch_array($res) ){
	  
	     $cantidad2 = $rg[existencia]-$rg[cantidad];

		   if($rg[existencia]<='0'){
			   $staped='Faltante';
			   $colorletra='#FF0000';
		   }else{
			   if($cantidad2 >= '1'){
					$staped='Disponible';
				    $colorletra='#666600';			   
		   		}else{
					if($cantidad2 == '0'){
						$staped='Ultima Pza';
  					    $colorletra='#FF3300';
		   		   }else{
					    $staped='Incompleto';	
  					    $colorletra='#FF0000';		   
				   }
				}
		   }

	    echo "<tr>";
  		echo "<td>$Gfont <font size='1'> $rg[clave] </td>";
  		echo "<td>$Gfont <font size='1'> $rg[descripcion] </td>";
  		echo "<td align='right'>$Gfont <font size='1'> $rg[cantidad]</td>";
  		echo "<td align='right'>$Gfont <font size='1'>".number_format($rg[costo],"2")."</td>";
  		echo "<td align='right'>$Gfont <font size='1'>".number_format($rg[costo]*$rg[cantidad],"2")."</td>";
  		echo "<td align='right'>$Gfont <font size='1'>$rg[existencia]</td>";
        echo "<td align='center'><font color=$colorletra size='1'>$staped</td>";
  		echo "</tr>";
  		$Costo += $rg[costo]*$rg[cantidad];                  				
  }                

  echo "</table>";
  

  echo "<p>&nbsp;</p>";
    
  $Imp = impletras($He[importe],'pesos');
  
  echo "<div align='left'>$Gfont &nbsp; &nbsp;  $Imp</div>";

  while($nRng < 30){
   
     echo "<div>&nbsp;</div>";
	  $nRng++;	  
  
  }

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
    
  //echo "<p align='center'>$Gfont <b>Total --------> No.rollos: $He[rollos] &nbsp; &nbsp; &nbsp; &nbsp; Peso: &nbsp; ";

  //echo number_format($He[cantidad],"2")."</b></p>";

   
  echo "<br>";

  echo "<form name='form1' method='post' action='imppedido.php?busca=$busca'>";

     echo "<input type='submit' name='Imprimir' value='Imprime' onClick='print()'>";

  echo "</form>";
    
  //echo "<input type='submit' name='Imprimir' value='Imprime' onCLick='print()'>";

echo "</body>";

echo "</html>";

mysql_close();


?>
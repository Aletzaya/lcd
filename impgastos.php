<?php
  session_start();

  require("lib/kaplib.php");

  $link = conectarse();

  $busca  = $_REQUEST[busca];
  
  $cSql = "SELECT dpag_ref.id,dpag_ref.concept,dpag_ref.autoriza,dpag_ref.hospi,cptpagod.referencia,cptpagod.id idref,"
        . "cpagos.id idpagos,cpagos.clave,cpagos.concepto,dpag_ref.fecha,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.orden_h,"
        . "dpag_ref.tipopago,dpag_ref.cerrada,dpag_ref.fechapago,dpag_ref.recibe,dpag_ref.id_ref FROM dpag_ref "
        . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
        . "WHERE dpag_ref.id='$busca' ORDER BY id";

  $CpoA   = mysql_query($cSql);
  
  $Cpo    = mysql_fetch_array($CpoA);
  
  $Titulo = "Pago de $Cpo[autoriza] &nbsp; &nbsp; &nbsp; No. $busca";
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
            echo "<br> &nbsp; <b>Referencia del pago :</b> $Cpo[referencia]<br><br>";
            echo "&nbsp; <b>Fecha de pago :</b> $Cpo[fecha]<br><br>";
            if($Cpo[hospi]=='Si'){
                echo "&nbsp; <b>Referencia a laboratorio :</b> $Cpo[hospi]  &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; <b>No.-</b> $Cpo[orden_h]<br><br>";
            }
            echo "&nbsp; <b>Concepto de laboratorio :</b> $Cpo[concept]<br><br>";
            echo "&nbsp; <b>Recibe :</b> $Cpo[recibe]<br><br>";
            echo "&nbsp; <b>Autoriza :</b> $Cpo[autoriza]<br><br>";
            echo "&nbsp; <b>Pago :</b> $Cpo[concepto]<br><br>";
            echo "&nbsp; <b>Importe :</b> $".number_format($Cpo[monto],2)."<br><br>";
            echo "&nbsp; <b>Observaciones :</b> $Cpo[observaciones]<br><br><br><br><br><br>";
            echo "".cTable('100%','0')."<td align='center'>$Gfont";
            echo "&nbsp; ____________________________ </td><td align='center'>$Gfont ___________________________<br></td></tr><tr><td align='center'>";
            echo "$Gfont <b>Autoriza: $Cpo[autoriza]</td><td align='center'>";
            echo "$Gfont <b>Recibe: $Cpo[recibe]</td></tr></table>";
            echo "<br> &nbsp; <img src='lib/print.png' alt='Imprimir' border='0' onClick='window.print()'> &nbsp; ";

            
           echo "<p align='center'><b><a class='pg' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></b></p>";
            
      
      echo "</td>";
    echo "</tr>";
    echo "</table>";
    
echo "</body>";

echo "</html>";
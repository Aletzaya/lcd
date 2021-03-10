<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link   = conectarse();

  $Usr    = $check['uname'];
  $busca  = $_REQUEST[busca];
  $Ingreso= $_REQUEST[Ingreso];
  $Tpago  = $_REQUEST[Tpago];
  $op     = $_REQUEST[op];
  $Titulo = "I N G R E S O S / C A J A";
  $Fecha  = date("Y-m-d");
  $Tabla  = "cja";

  if($op =="Ab"){        //Para agregar uno nuevo
      if($Ingreso>0){
        $Fecha=date("Y-m-d");
        $Hora = date("H:i");
//	 	$Hora1 = date("H:i");         
//    	 	$Hora2 = strtotime("-60 min",strtotime($Hora1));
//		$Hora  = date("H:i",$Hora2);

        $OtA     = mysql_query("select importe,ubicacion from ot where orden='$busca'",$link);
        $Ot      = mysql_fetch_array($OtA);
        $cSqlA   = mysql_query("select sum(importe) from cja where orden='$busca'",$link);
        $Abonado = mysql_fetch_array($cSqlA);
        
        if(($Abonado[0] + $Ingreso + .5) >= $Ot[0] ){
           $lUp=mysql_query("update ot Set pagada='Si',fecpago='$Fecha' where orden='$busca'",$link);
        }

        $lUp=mysql_query("insert into $Tabla (orden,importe,fecha,hora,usuario,tpago) VALUES ('$busca','$Ingreso','$Fecha','$Hora','$Usr','$Tpago')",$link);

     }

     header("Location: ordenesd.php?busca=$busca&pagina=$pagina");
  }

 require ("config.php");							//Parametros de colores;


echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

?>

<script language="JavaScript1.2">

function Valido() {   
        if(document.form1.Ingreso.value > <?php echo $Abonos; ?>){
			alert("Revise la Cantidad a Abonar")   
            return false 
		} else {
			    if(document.form1.Ingreso.value < 0){
					alert("Revise la Cantidad a Abonar")   
            		return false 
				} else {
	        		return true   
				}
		}
}

function cFocus(){
  document.form1.Ingreso.focus();
}


</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

  headymenu($Titulo,0);

  $cSqlA  = mysql_query("select sum(importe) from cja where orden='$busca'");
  $SqlS   = mysql_fetch_array($cSqlA);
  
  $cSqlH  = "select ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,ot.medico,med.nombrec from ot,cli,med where ot.cliente=cli.cliente and ot.medico=med.medico and ot.orden='$busca'";

  $HeA    = mysql_query($cSqlH);
  $He     = mysql_fetch_array($HeA);
  
  $Abonos = $He[importe] - $Sqls[0];

  echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";

  echo "<tr bgcolor ='#618fa9'>";
  echo "<td>$Gfont <font color='#ffffff'>No.Orden:  $busca</td>";
  echo "<td>$Gfont <font color='#ffffff'> Cliente:  $He[cliente] $He[4]</td>";
  echo "<td>$Gfont <font color='#ffffff'> &nbsp; Fecha:  $He[fecha]  &nbsp; Fecha/entrega:  $He[fechae]</td>";
  echo "</tr><tr bgcolor ='#E1E1E1'>";
  echo "<td>$Gfont Medico: $He[medico] $He[9]</td>";
  echo "<td>$Gfont Importe: $  $He[importe] &nbsp; &nbsp; Abonado: $ ".number_format($SqlS[0],'2')."</td>";
  echo "<td>$Gfont Saldo: ".number_format($He[importe]-$SqlS[0],"2")."</td></tr>";
  echo "<tr bgcolor ='#618fa9'>";
  echo "<td>$Gfont </td>";
  echo "<td>$Gfont </td>";
  echo "<td>$Gfont </td>";
  echo "</tr><tr>";
  echo "</table>$Gfont ";

  echo "<p>&nbsp;</p>";
  
  echo "<p align='center'>Registro de pago de clientes</p>";

  echo "<div align='center'>";

      echo "<form name='form1' method='post' action='ingreso.php?busca=$busca&pagina=$pagina&op=Ab'>";

      $Saldo = $He[importe]-$SqlS[0];
      
      echo "<div align='center'>Tipo de pago :";
      echo "<select name='Tpago'>";
      echo "<option value='Efectivo'>Efectivo</option>";
      echo "<option value='Tarjeta'>Tarjeta</option>";
      echo "<option value='Cheque'>Cheque</option>";
      echo "<option value='Transferencia'>Transferencia</option>";
	  echo "<option value='Credito'>Credito</option>";
	  echo "<option value='Nomina'>Nomina</option>";
      echo "<option selected value ='Efectivo'>Efectivo</option>";
      echo "</select>";
      echo "</div>";
      echo "<p>&nbsp;</p>";
      echo "<div align='center'>Su pago por $";
      echo "<input name='Ingreso' value='$Saldo' type='text' size='5'>";
      echo "<input type='submit' name='Submit' value='Enter'> &nbsp; &nbsp; ";
      echo "</div>";

      echo "<p>&nbsp;</p>";
      
      echo "<a href='ordenesd.php?busca=$busca&pagina=$pagina'><img src='lib/regresa.jpg' border='0'></a>";
      echo "</form>";

echo "</body>";

echo "</html>";

?>
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
  $Usuario  = $_REQUEST[Usuario];
  $Importe  = $_REQUEST[Importe];
  $opc     = $_REQUEST[op];
  $Titulo = "I N G R E S O S / C A J A";
  $Fecha  = date("Y-m-d");
  $Fechaest  = date("Y-m-d H:i:s");
  $Tabla  = "cja";
  if(!isset($_REQUEST[Cambia])){$Cambia='NO';}
  $id2	= $_REQUEST[id];
  
require ("config.php");							//Parametros de colores;

  if($_REQUEST[opc] == 'NO'){        //Para agregar uno nuevo
		$lUp    = mysql_query("UPDATE cja set tpago = '$Tpago', usuario = '$Usuario', importe = '$Importe' WHERE orden='$busca' and id='$id2'");
//		$Cambia='No';
 	    $lUp2  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
		('$Fechaest','$Usr','Modifica Ot: $busca id: $id2')");
		
		$OtC     = mysql_query("select importe from ot where orden='$busca'",$link);
		$Otd      = mysql_fetch_array($OtC);
		$cSqlB   = mysql_query("select sum(importe) from cja where orden='$busca'",$link);
		$Abonado = mysql_fetch_array($cSqlB);
		
		if(($Abonado[0] + .5) >= $Otd[0] ){
		   $lUp=mysql_query("update ot Set pagada='Si',fecpago='$Fecha' where orden='$busca'",$link);
		}else{
		   $lUp=mysql_query("update ot Set pagada='No' where orden='$busca'",$link);
		}
  }


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

//  headymenu($Titulo,0);

  $cSqlA  = mysql_query("select sum(importe) from cja where orden='$busca'");
  $SqlS   = mysql_fetch_array($cSqlA);
  
  $cSqlH  = "select ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,ot.medico,med.nombrec from ot,cli,med where ot.cliente=cli.cliente and ot.medico=med.medico and ot.orden='$busca'";
  $HeA    = mysql_query($cSqlH);
  $He     = mysql_fetch_array($HeA);
  
  $Abonos = $He[importe] - $Sqls[0];

  echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";
  
    echo "<p align='center'><font size='+2' face='Arial, Helvetica, sans-serif'><b>Registro de pago de clientes</b></font></p>";

  echo "</tr><tr>";
  echo "</tr><tr>";

  echo "<tr>";
  echo "<td><font size='2' face='Arial, Helvetica, sans-serif'> <b>No.Orden:  $He[institucion] - $busca  &nbsp; Cliente: $He[4]</font></b></td>";
  echo "</tr><tr bgcolor ='#618fa9'>";
  echo "<td>$Gfont <font color='#ffffff'><b> Importe: $ ".number_format($He[importe],'2')."</b></td>";
  echo "<td>$Gfont <font color='#ffffff'><b> Abonado: $ ".number_format($SqlS[0],'2')."</b></td>";
  echo "<td>$Gfont <font color='#ffffff'><b> Saldo: $ ".number_format($He[importe]-$SqlS[0],'2')."</b></td></tr>";
  echo "<tr>";
  echo "</table>$Gfont ";

  $cSql  = "SELECT id,fecha,hora,orden,importe,usuario,tpago,suc FROM cja WHERE cja.orden='$busca'";
  $res  = mysql_query($sql);

 if(!$res=mysql_query($cSql)){
 
        echo "$Gfont <p align='center'>No se encontraron resultados y/o hay un error en el filtro</p>";    #Manda mensaje de datos no existentes
        echo "<p align='center'>$Gfont Favor de presiona refresca...</p></font>";    #Manda mensaje de datos no existentes
		  $nRng=7;

  }else{
	echo "<table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";
	echo "<tr>";
	echo "<td align='center' bgcolor='$Gbarra'><font size='2' face='Arial, Helvetica, sans-serif'> <b>Id</td>";
	echo "<td align='center' bgcolor='$Gbarra'><font size='2' face='Arial, Helvetica, sans-serif'> <b>Fecha</td>";
	echo "<td align='center' bgcolor='$Gbarra'><font size='2' face='Arial, Helvetica, sans-serif'> <b>Hora</td>";
	echo "<td align='center' bgcolor='$Gbarra'><font size='2' face='Arial, Helvetica, sans-serif'> <b>Importe</td>";
	echo "<td align='center' bgcolor='$Gbarra'><font size='2' face='Arial, Helvetica, sans-serif'> <b>Usuario</td>";
	echo "<td align='center' bgcolor='$Gbarra'><font size='2' face='Arial, Helvetica, sans-serif'> <b>Tipo pago</td>";
	echo "<td align='center' bgcolor='$Gbarra'><font size='2' face='Arial, Helvetica, sans-serif'> <b>&nbsp;</td>";
	echo "</tr>";
    while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[id]</td>";
           echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[fecha]</td>";
           echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[hora]</td>";
  		   if($id2==$rg[id] and $_REQUEST[Cambia]=='SI'){
			    echo "<form name='form1' method='post' action='ingreso3.php?Cambia=NO&busca=$busca&id=$rg[id]'>";
				echo "<td align='right'><input name='Importe' value='$rg[importe]' type='text' size='6'></td>";
				echo "<td align='center'><input name='Usuario' value='$rg[usuario]' type='text' size='20'></td>";
				echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Tipo de pago :";
				echo "<select name='Tpago'>";
				echo "<option value='Efectivo'>Efectivo</option>";
				echo "<option value='Tarjeta'>Tarjeta</option>";
				echo "<option value='Cheque'>Cheque</option>";
        echo "<option value='Transferencia'>Transferencia</option>";
				echo "<option value='Credito'>Credito</option>";
				echo "<option value='Nomina'>Nomina</option>";
				echo "<option selected value ='$rg[tpago]'>$rg[tpago]</option>";
				echo "</select>";
				echo "</td>";
				echo "<input type='hidden' name='opc' value='NO'>";
				echo "<td align='center'><INPUT TYPE='SUBMIT' name='Cambia' value='Cambia'></td>";
		   }else{
				echo "<form name='form1' method='post' action='ingreso3.php?Cambia=SI&busca=$busca&id=$rg[id]'>";			   
				echo "<td align='right'><font size='2' face='Arial, Helvetica, sans-serif'>$ ".number_format($rg[importe],'2')."</td>";
				echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[usuario]</td>";
				echo "<td align='center'><font size='2' face='Arial, Helvetica, sans-serif'>$rg[tpago]</td>";
				echo "<input type='hidden' name='opc' value='SI'>";
				echo "<td align='center'><INPUT TYPE='SUBMIT' name='Edita' value='Edita'></td>"; 
			}
		   echo "</form>";
           echo "</tr>";
           $nRng++;
     }
     echo "</table>";
  }
echo "<div align='center'>";
echo "<a href='ordenesd.php?busca=$busca&pagina=$pagina'><img src='lib/regresa.jpg' border='0'></a>";
echo "</div>";
echo "</body>";

echo "</html>";

?>
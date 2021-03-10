<?php

  session_start();

  require("lib/lib.php");
  
  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $link  = conectarse();

  $Usr=$check['uname'];

  $Fecha=date("Y-m-d H:i");

  $busca    = $_REQUEST[busca];

  $proveedor    = $_REQUEST[proveedor];

  $cId      = $_REQUEST[cId];

  $op      = $_REQUEST[op];

//$Tabla = "elpagos";

  $Msj   = "";

  $Titulo= "Detalle de Pagos de compras";

  $Usr    = $check['uname'];

  if($op=='El'){

     $Rg=mysql_query("DELETE from elpagos where id='$cId' limit 1");

    $cSql = "SELECT sum(importe) as suma FROM elpagos WHERE idcompra='$busca'";
    $PagComA = mysql_query($cSql);
    $PagCom = mysql_fetch_array($PagComA);

    $HeB    = mysql_query("SELECT importe,iva from el where id='$busca'");

    $Cpob     = mysql_fetch_array($HeB);

    $dif  = $Cpob[importe]-$PagCom[suma];

    if($dif==0){

      $steconomico='Pagada';

    }else{

      $steconomico='Pendiente';

    }

    $cSql3 = mysql_query("UPDATE el SET importepag='$PagCom[suma]',statuse='$steconomico' WHERE id='$busca'");
   }

  if($_REQUEST[Boton]=='Agregar'){

     $Rg=mysql_query("INSERT into elpagos (fecha,idcompra,importe,proveedor,usr,tpago,doctopago,fechacap,observaciones) VALUES ('$_REQUEST[fecha]','$busca','$_REQUEST[importe]','$proveedor','$Usr','$_REQUEST[tpago]','$_REQUEST[doctopago]','$Fecha','$_REQUEST[observaciones]')");

    $cSql = "SELECT sum(importe) as suma FROM elpagos WHERE idcompra='$busca'";
    $PagComA = mysql_query($cSql);
    $PagCom = mysql_fetch_array($PagComA);

    $HeB    = mysql_query("SELECT importe,iva from el where id='$busca'");

    $Cpob     = mysql_fetch_array($HeB);

    $dif  = $Cpob[importe]-$PagCom[suma];

    if($dif==0){

      $steconomico='Pagada';

    }else{

      $steconomico='Pendiente';

    }

    $cSql3 = mysql_query("UPDATE el SET importepag='$PagCom[suma]',statuse='$steconomico' WHERE id='$busca'");

  }elseif($_REQUEST[Boton]=='Actualizar'){

     $Rg = mysql_query("UPDATE elpagos SET fecha='$_REQUEST[fecha]',idcompra='$busca',importe='$_REQUEST[importe]',proveedor='$proveedor',usr='$Usr',tpago='$_REQUEST[tpago]',doctopago='$_REQUEST[doctopago]',fechacap='$Fecha',observaciones='$_REQUEST[observaciones]' WHERE id='$cId'");


    $cSql = "SELECT sum(importe) as suma FROM elpagos WHERE idcompra='$busca'";
    $PagComA = mysql_query($cSql);
    $PagCom = mysql_fetch_array($PagComA);


    $HeB    = mysql_query("SELECT importe,iva from el where id='$busca'");

    $Cpob     = mysql_fetch_array($HeB);

    $dif  = $Cpob[importe]-$PagCom[suma];

    if($dif==0){

      $steconomico='Pagada';

    }else{

      $steconomico='Pendiente';

    }

    $cSql3 = mysql_query("UPDATE el SET importepag='$PagCom[suma]',statuse='$steconomico' WHERE id='$busca'");


  }

  $HeA    = mysql_query("SELECT * from el where id='$busca'");

  $Cpo     = mysql_fetch_array($HeA);

  $Hi    = mysql_query("SELECT * from prv where id=$Cpo[proveedor]");

  $Hil     = mysql_fetch_array($Hi);

  $cSql   = mysql_query("SELECT * FROM elpagos WHERE idcompra='$busca' order by id Desc");

  require ("config.php");

?>

<html>

<head>

 <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
 
<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>


<title><?php echo $Titulo;?></title>

</head>

<?php

echo "<table width='100%' border='0'>";

echo "<tr>";
echo "<td width='25%' align='right' bgcolor='#a9dfbf'>$Gfont Id de Compra: </td><td align='left' width='25%' bgcolor='#a9dfbf'>$Gfont <b> ".$Cpo[id]." </b></td>";
echo "<td width='25%' align='right' bgcolor='#a9dfbf'>$Gfont Fecha de compra: </td><td align='left' width='35%' bgcolor='#a9dfbf'>$Gfont  ".$Cpo[fecha]." - ".$Cpo[hora]." </td>";
echo "</tr>";

echo "<tr>";
echo "<td width='25%' align='right'>$Gfont Proveedor: </td><td align='left' colspan='3'>$Gfont <b> ".$Cpo[proveedor]." - ".$Hil[nombre]." </b></td>";
echo "</tr>";

echo "</tr>";

if($Cpo[almacen]=='invgral'){

  $Almacen='General';

}elseif($Cpo[almacen]=='invmatriz'){

  $Almacen='Matriz';

}elseif($Cpo[almacen]=='invtepex'){

    $Almacen='Tepexpan';

}elseif($Cpo[almacen]=='invhf'){

    $Almacen='HF';

}elseif($Cpo[almacen]=='invgralreyes'){

      $Almacen='Gral.Reyes';

}elseif($Cpo[almacen]=='invreyes'){

        $Almacen='Reyes';

}

echo "<tr>";
echo "<td width='25%' align='right' bgcolor='#a9dfbf'>$Gfont Concepto / Documento: </td><td align='left' colspan='3' bgcolor='#a9dfbf'>$Gfont  ".$Cpo[concepto]." - ".$Cpo[documento]." </td>";

echo "<tr>";
echo "<td width='25%' align='right'>$Gfont Cantidad / Importe: </td><td align='left'>$Gfont $ ".number_format($Cpo[importe],"2")." - > ".$Cpo[cantidad]." </td>";
echo "<td width='25%' align='right'>$Gfont Status Economico: </td><td align='left'>$Gfont <b>".$Cpo[statuse]." </b></td>";
echo "</tr>";

echo "<tr>";
echo "<td width='25%' align='right' bgcolor='#a9dfbf'>$Gfont Importe Pagado: </td><td align='left' bgcolor='#a9dfbf'>$Gfont  $ ".number_format($Cpo[importepag],"2")." - > Resta: $ ".number_format(($Cpo[importe])-$Cpo[importepag],"2")." </td>";
echo "<td width='25%' align='right' bgcolor='#a9dfbf'>$Gfont Almacen: </td><td align='left' bgcolor='#a9dfbf'>$Gfont  ".$Almacen." </td>";
echo "</tr>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<hr>";

//******************* Pagos Registrados

echo "<table width='100%' border='0'>";

echo "<tr>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> Ed </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> Id </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> fecha </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> Importe </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> Tipo de Pago </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> Documento </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> Usuario </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> Fecha de Captura </b></td>";
echo "<td align='center' bgcolor='#C0C0C0'>$Gfont <b> - </b></td>";
echo "</tr>";

while($rg=mysql_fetch_array($cSql)){

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

    if($cId==$rg[id]){
      $Fdo='#e6b0aa';
    }else{
      $Fdo=$Fdo;
    }

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";        

    echo "<td align='center'><a href='pagosel.php?busca=$busca&op=ed&cId=$rg[id]'><img src='lib/edit.png' alt='Edita reg' border='0'></td>";

    echo "<td>$Gfont $rg[id]</font></td>";
    echo "<td>$Gfont $rg[fecha]</font></td>";
    echo "<td align='right'>$Gfont ".number_format ($rg[importe],'2')."</font></td>";
    echo "<td>$Gfont $rg[tpago]</font></td>";
    echo "<td>$Gfont $rg[doctopago]</font></td>";
    echo "<td>$Gfont $rg[usr]</font></td>";
    echo "<td>$Gfont $rg[fechacap]</font></td>";

    if($Usr=='nazario'){

      echo "<td align='center'><a href='pagosel.php?busca=$busca&cId=$rg[id]&op=El'><img src='lib/deleoff.png' alt='Elimina reg' border='0'></td>";    

    }

    echo "</tr>";
    $nRng++;

}//fin while

echo "</table>";


echo "<hr>";
//******************* Agregar pago

  if($op=='ed'){

    $CpoA  = mysql_query("SELECT * FROM elpagos WHERE id='$cId'");
    $rg   = mysql_fetch_array($CpoA);    

  }

echo "<form name='form1' method='get' action='pagosel.php' onSubmit='return Valido();'>";

echo "<table align='center' width='100%' border='0'>";

echo "<tr bgcolor='#d6eaf8'><td>"; 
echo "$Gfont <b><font size='1'> Fecha de pago: </b></font>";
echo "</td>"; 

echo "<td>"; 
echo "$Gfont <b><font size='1'> Importe : </b></font>";
echo "</td>";  

echo "<td>"; 
echo "$Gfont <b><font size='1'> Tipo de Pago : </b></font>";
echo "</td>";

echo "<td>"; 
echo "$Gfont <b><font size='1'> Documento de pago : </b></font>";
echo "</td>";

echo "<tr bgcolor='#d6eaf8'><td>"; 

echo "<input type='text' name='fecha' size='10' value ='$rg[fecha]' required> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].fecha,'yyyy-mm-dd',this)></b></font>";
echo "</td>"; 


if($op=='ed'){

  $importes=$rg[importe];
  $importesa=$rg[importe];

}else{

  $importes=($Cpo[importe])-$Cpo[importepag];

}


echo "<td>"; 
echo "$Gfont <b><font size='1'><input type='number' step='any' name='importe' value='$importes' size='15' required></b></font>";
echo "</td>"; 

echo "<td align='left'>$Gfont<b><font size='1'>";
echo "<select size='1' name='tpago' class='Estilo10'>";
echo "<option value='Efectivo'> Efectivo </option>";
echo "<option value='Tarjeta'> Tarjeta </option>";
echo "<option value='Cheque'> Cheque </option>";
echo "<option value='Transferencia'> Transferencia </option>";
echo "<option selected value='$rg[tpago]'>$Gfont <font size='-1'>$rg[tpago]</option>";     
echo "</select>";
echo"</b></font></td>";

echo "<td>"; 
echo "$Gfont <b><font size='1'><input type='text' name='doctopago' value='$rg[doctopago]' size='30'></b></font>";
echo "</td>"; 

echo "</tr>"; 

echo "<tr bgcolor='#d6eaf8'><td>"; 
echo "$Gfont <b><font size='1'> Observaciones : </b></font>";
echo "</td>"; 
echo "<td colspan='4'>"; 
echo "$Gfont <b><font size='1'><input type='text' name='observaciones' value='$rg[observaciones]' size='80' rows='3'></b></font>";
echo "</td>"; 

echo "<input type='hidden' name='proveedor' value='$Cpo[proveedor]'>";
echo "<input type='hidden' name='busca' value='$busca'>";
echo "<input type='hidden' name='cId' value='$rg[id]'>";

if($op=='ed'){

  echo "<tr><td></td><td align='center'>"; 
  echo "$Gfont <b><font size='1'> Cancelar <br>";
  echo "<a href='pagosel.php?busca=$busca'><img src='lib/regresa.jpg' border='0'></a></b></font>";
  echo "</td>"; 

  echo "<td colspan='3' align='center'>"; 

  echo "<input type='submit' name='Boton' value='Actualizar'>";     

}else{

   echo "</tr>"; 

  echo "<tr><td></td><td align='center'>"; 
  echo "$Gfont <b><font size='1'> regresar <br>";
  echo "<a href='#' onclick='cerrarVentana();'><img src='lib/regresa.jpg' border='0'></a></b></font>";
  echo "</td>"; 

  echo "<td colspan='3' align='center'>"; 

  echo "<input type='submit' name='Boton' value='Agregar'>"; 

}  

echo "</td>"; 

echo "</tr>"; 
echo "</table>"; 

echo "</form>";

?>
<script>
function cerrarVentana(){
  window.close();
}

function Valido() {  
  var $importes;
  var $op;
  var $importesa;

  if($op='ed'){

    if(document.form1.importe.value > <?php echo ($Cpo[importe]+$Cpo[iva])-($Cpo[importepag]-$importesa); ?>){
        alert("Revise la Cantidad a Abonar")   
        return false 
    } else {
        if(document.form1.importe.value <= 0){
            alert("Revise la Cantidad a Abonar")   
            return false 
        } else {
            return true   
        }
    }

  }else{

    if(document.form1.importe.value > <?php echo ($Cpo[importe]+$Cpo[iva])-$Cpo[importepag]; ?>){
        alert("Revise la Cantidad a Abonar")   
        return false 
    } else {
        if(document.form1.importe.value <= 0){
            alert("Revise la Cantidad a Abonar")   
            return false 
        } else {
            return true   
        }
    }

  }



}

</script> 

<?php
echo "</body>";

echo "</html>";

?>
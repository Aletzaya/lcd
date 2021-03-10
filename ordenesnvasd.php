<?php
/*  
echo "Nivel ".$check['level'];
echo "grupo o sucursal".$check['team'];
echo "Status ".$check['status'];
echo "Nombre: ".$check['username'];
*/

session_start();

require("lib/lib.php");

$link     = conectarse();  

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

$Usr=$check['uname'];

if(!isset($_REQUEST[$Vta])){$Vta=$_SESSION['Venta_ot'];}

$Fecharec   =   date("Y-m-d");
$cSqlA      =   mysql_query("select medico from otnvas where usr='$Usr' and venta='$Vta'",$link);
$cSql       =   mysql_fetch_array($cSqlA);
$Abono      =   $_REQUEST[Abono];
$op         =   $_REQUEST[op];
$Tpago      =   $_REQUEST[Tpago];
$Titulo     =   "A cuenta y otros datos";
   
require ("config.php");							//Parametros de colores;


?>

<html>
<head>

<title><?php echo $Titulo;?></title>

</head>

<script language="JavaScript1.2">
function cFocus(){
    document.form1.Abono.focus();
}

function Valido() {   
    if(document.form1.Abono.value > <?php echo $Abono; ?>){
        alert("Revise la Cantidad a Abonar")   
        return false 
    } else {
        if(document.form1.Abono.value < 0){
            alert("Revise la Cantidad a Abonar")   
            return false 
        } else {
            return true   
        }
    }
}


</script>


<?php 

  echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

  headymenu("Genera la orden",0);
  
  echo "<table border='0' width='100%' align='center' border=0 cellpadding=0 cellspacing=0>";

  echo "<tr><td height='280' align='center'>$Gfont ";

      echo "<div align='left'> &nbsp &nbsp &nbsp <a href='ordenesnvas.php?Vta=$Vta'><img src='lib/regresa.jpg' border='0'></a></div>";
 
       echo "<form name='form1' method='post' action='ordenesnvas.php?Vta= $Vta&op=Fn' onSubmit='return Valido();'>";
       
          echo "<p>No.de receta o folio alterno: ";
          echo "<input class='textos' name='Receta' type='text' size='10'>";
		  
          echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dato complementario: ";
          echo "<input class='textos' name='Datoc' type='text' size='35'>";
          
          echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fec.Receta :";
          echo "<input class='textos' name='Fechar' type='text' size='10' value='$Fecharec'> aaaa-mm-dd</p>";
          
          echo "<p><b>Importe: $ ".number_format($Abono,"2")."</b> &nbsp &nbsp &nbsp ";
          
          echo 'Tipo de pago:';          
          echo "<select class='textos' name='Tpago'>";
          echo "<option value='Efectivo'>Efectivo</option>";
          echo "<option value='Tarjeta'>Tarjeta</option>";
          echo "<option value='Cheque'>Cheque</option>";
		  echo "<option value='Credito'>Credito</option>";
		  echo "<option value='Nomina'>Nomina</option>";
          echo "<option selected value ='Efectivo'>Efectivo</option>";
          echo "</select> &nbsp &nbsp &nbsp &nbsp ";

          echo "A cuenta(abonar): $ ";
          echo "<input name='Abono' type='text' size='6'>";
          echo "</p>";
          echo "<p align='center'>";         
          
          if($cSql[0]=='MD'){
                echo "Nombre del Medico:";
                echo "<input name='Medicon' type='text' size='30' value='A QUIEN CORRESPONDA'>";
          }else{
                echo "<input type='hidden' name='Medicon' value='A QUIEN CORRESPONDA'>";
          }
          
          echo "</p>";
          echo '<div>Diagnostico Medico</div>';
          echo '<div align="center"><TEXTAREA NAME="Diagmedico" cols="60" rows="3" ></TEXTAREA></div>';
          echo '<div align="center">Observaciones</div>';

          echo '<div align="center"><TEXTAREA NAME="Observaciones" cols="60" rows="3" ></TEXTAREA></div>';
              
          echo '<input type="submit" name="Genera" value="Imprime">';
          
       echo "</form>";
                    
  echo "</td></tr>";
     
  echo "<tr background='lib/prueba.jpg' height='80'>";

  echo "<td>&nbsp;</td>";

  echo "</tr></table>";

?>

<p>&nbsp;</p>


</body>

</html>
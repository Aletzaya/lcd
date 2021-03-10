<?php

session_start();

require("lib/lib.php");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

require_once 'nusoap.php';

$link=conectarse();


$busca    =  $_REQUEST[busca];
$op       =  $_REQUEST[op];


if ($_REQUEST[Boton] == 'CANCELAR FACTURA') {

    $HeA     =  mysql_query("SELECT uuid FROM fc WHERE fc.id='$busca'");
    $He      = mysql_fetch_array($HeA);
    
    if ($He[uuid] == '') {
        
        $lUp = mysql_query("UPDATE fc SET cantidad=0,importe=0,iva=0,total=0,status='Cancelada'
                        WHERE id='$busca'");

        $lUp = mysql_query("DELETE FROM fcd WHERE id='$busca'");

        $Msj = "Tu factura No. $busca ha sido cancelada con exito(aun no se habia timbrado)";

        header("Location: facturas.php?Msj=$Msj");
        
    } else {

        $PrmA = mysql_query("SELECT password FROM cia WHERE id='1'");
        $Prm  = mysql_fetch_array($PrmA);

        $Clave = md5($_REQUEST[Password]);
        
        //echo "Calve dada es $Clave Pass es $Prm[0]";
        
        if ($Prm[0] == $Clave) {
            
            $HeA = mysql_query("SELECT uuid FROM fc WHERE fc.id='$busca'");
            $He = mysql_fetch_array($HeA);

            $cUid = $He[uuid];
           
            $pfx = filetoStringB64('fae/pfx.pfx');

            $wsdl = 'http://54.243.190.121/cfdi.asmx?wsdl';

            $client = new nusoap_client($wsdl, true);

            $client->soap_defencoding = 'UTF-8';

            $client->namespaces = array("SOAP-ENV" => "http://schemas.xmlsoap.org/soap/envelope/");


            // se preparan los parámetros de entrada
            $params = array(
                "user"          => 'WSAAZF0029',
                "password"      => 'ff7521e0d4',
                "rfc"           => 'LCD960909TW5',
                "uuid"          => $cUid,
                "pfx"           => $pfx,
                "pfxPassword"   => 'lcdtw596'
            );

            

            //llamamos al metodo
            try {

                
                //Real
                $result = $client->call("cancelaCFDiLayout", $params, false, '', '');

                                                // revisamos si hay error
                $err = $client->getError();
                if ($err) {
                    // Creamos una nueva excepción con el error para desplegarlo
                    //throw new Exception("Error: ".$err);
                    //print_r($err);

                    header("Location: menu.php?op=102&Msj=$err");

                } else {
                   
                    $Msj1 = $result["cancelaCFDiLayoutResult"]["string"][4]; //Fact cancelada coreectamente
                    $Msj2 = $result["cancelaCFDiLayoutResult"]["string"][3]; //Codigo 201
                    $Msj3 = $result["cancelaCFDiLayoutResult"]["string"][2]; //UUid
                    $Msj4 = $result["cancelaCFDiLayoutResult"]["string"][1];   //Rfc

                    $lUp = mysql_query("UPDATE fc SET status='Cancelada' WHERE id='$busca'");

                    header("Location: facturas.php?Msj=$Msj1" . ", " . $Msj2 . ", " . $Msj3 . ", " . $Msj4);
                }                                            
            
            } catch (Exception $e) {
                
                print_r($e->getMessage());
            }
            
            
        } else {

            $Msj = "Lo siento!!! su clave no coincide";
        }
        
        break;
    } //endif
}



$CpoA     =  mysql_query("SELECT fc.fecha,fc.cliente,fc.cantidad,fc.iva,fc.ieps,fc.importe,
  	     clif.rfc,clif.nombre,fc.uuid,clif.direccion,clif.colonia,clif.municipio,clif.codigo
  	     FROM clif,fc WHERE fc.id='$busca' AND fc.cliente=clif.id");

$Cpo      = mysql_fetch_array($CpoA);
    
$Titulo   = "Edita factura [$busca]"; 
  
require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

headymenu($Titulo,0); 

?>

<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}
function SiElimina(){
  if(confirm("ATENCION! Desea dar de Baja este registro?")){
     return(true);
   }else{
     document.form1.busca.value="NUEVO";
      return(false);
   }
}


function Completo(){
var lRt;
lRt=true;
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}
</script>

<?php

echo '<table width="100%" border="0">';
  echo '<tr>';
  echo "<td  width='10%' rowspan='2'>";      
         echo "$Gfont regresar &nbsp; &nbsp; <br>";
         echo "<a class='pg' href='facturas.php'><img src='lib/regresa.jpg' border='0'></a>";
      
   echo "</td>";
   echo "<td align='center'>";
   
   echo "<p>&nbsp</p>";
   
	    	echo "<form name='form1' method='post' action=" . $_SERVER['PHP_SELF'] . ">";

                        echo "<table width='95%' border='1' align='center' cellpadding='0' cellspacing='0' class='textos'>";
                        echo "<tr>";
                        echo "<td align='right' width='30%'>Cliente: $Cpo[cliente] &nbsp;</td><td width='50%'>&nbsp;";
                        echo "<input class='textos' type='text' name='Nombre' value='$Cpo[nombre]' class='texto_tablas' size='60' onBLur=Mayusculas('Nombre')>";
                        echo "</td></tr>";
                        echo "<tr><td align='right'>Direccion: &nbsp;</td><td>&nbsp;";
                        echo "<input class='textos' type='text' name='Direccion' value='$Cpo[direccion]' class='texto_tablas' size='50' onBLur=Mayusculas('Direccion')>";
                        echo "</td></tr>";
                        echo "<tr><td align='right'>Colonia: &nbsp;</td><td>&nbsp;";
                        echo "<input class='textos' type='text' name='Colonia' value='$Cpo[colonia]' class='texto_tablas' size='30' onBLur=Mayusculas('Colonia')>";
                        echo "</td></tr>";
                        echo "<tr><td align='right'>Municipio: &nbsp;</td><td>&nbsp;";
                        echo "<input class='textos' type='text' name='Municipio' value='$Cpo[municipio]' class='texto_tablas' size='30' onBLur=Mayusculas('Municipio')> &nbsp; ";

                        echo " Cod.postal: ";
                        echo "<input class='textos' type='text' name='Codigo' value='$Cpo[codigo]' class='texto_tablas' size='5' onBLur=Mayusculas('Codigo')>";

                        echo "</td></tr>";
                        echo "<tr><td align='right'>Rfc: &nbsp;</td><td>&nbsp;";
                        echo "<input class='textos' type='text' name='Rfc' value='$Cpo[rfc]' class='texto_tablas' size='20' onBLur=Mayusculas('Rfc')>";
                        echo "</td></tr>";
                        echo "</table>";

                        echo "<div align='center' class='textosBoldAvanRegre'>PRODUCTOS </div>";

                        echo "<table width='90%' border='1' align='center' cellpadding='3' cellspacing='0' class='textos'>";
                        echo "<tr bgcolor=$Gfdogrid>";
                        echo "<th>Producto</th>";
                        echo "<th>Descripcion</th>";
                        echo "<th>Cantidad</th>";
                        echo "<th>Precio</th>";
                        echo "<th>Importe</th>";
                        echo "</tr>";

                        $CpoA = mysql_query("SELECT fcd.estudio,est.descripcion,fcd.cantidad,fcd.precio,fcd.iva,
                                fcd.importe
                                FROM fcd LEFT JOIN est ON fcd.estudio=est.estudio
                                WHERE fcd.id='$busca'");

                        //	$Detalle = "|CONCEPTO";

                        while ($rg = mysql_fetch_array($CpoA)) {

                            //if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

                            echo "<tr class='textos' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                            echo "<td align='center'> $rg[estudio] </td>";
                            echo "<td align='left'> $rg[descripcion] </td>";
                            echo "<td align='right'> " . number_format($rg[cantidad], "2") . " </td>";
                            echo "<td align='right'> " . number_format($rg[precio], "2") . " </td>";
                            //echo "<td align='right'> ".number_format($rg[cantidad]*$rg[precio],"2")." </td>";
                            echo "<td align='right'> " . number_format($rg[importe], "2") . " </td>";
                            echo "</tr>";

                            $nCnt += $rg[cantidad];
                            $nImp += $rg[importe];

                            $nRng++;
                        }

                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        echo "<td align='center'>&nbsp;</td>";
                        echo "<td align='right'> Total ---> </td>";
                        echo "<td align='right'> " . number_format($nCnt, "2") . " </td>";
                        echo "<td align='right'> &nbsp; </td>";
                        echo "<td align='right'> " . number_format($nImp, "2") . " </td>";
                        echo "</tr>";
                        echo "</table><br>";

                        echo "<p align='left' class='textos'>";

                        echo "Para poder cancerlar &eacute;sta factura es necesario proporcionar el password: ";
                        echo "<input  class='nombre_cliente' type='password' name='Password' value='' size='10'> &nbsp ";
                        
                        echo "<input type='hidden' name='busca' value='$busca'>";
                        
                        echo "<input type='submit' style='background:#618fa9; color:#ffffff;font-weight:bold;' name='Boton' value='CANCELAR FACTURA'>";

                        echo "</p>";

                echo "</form>";

                echo "<div align='left'>$Msj</div>";
                
  echo "</td>";
    
  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

mysql_close();

function filetoStringB64($filePath){

        $fd 	= fopen($filePath, 'rb');
    	$size 	= filesize($filePath);
    	$cont 	= fread($fd, $size);
    	fclose($fd);
    	return  base64_encode($cont);

}

?>

<?php

set_time_limit(720);

session_start();

require ("config.php");          //Parametros de colores;
require ("lib/lib.php");


require ("CFDIComboBoxes.php");
require_once 'class.phpmailer.php';

include_once ('data/CiaDAO.php');
include_once ('data/ClientesDAO.php');
include_once ('data/FcDAO.php');
include_once ('data/ProveedorPACDAO.php');
require_once ('data/FacturaDetisa.php');

require_once ('cfdi33/SelloCFDI.php');
require_once ('cfdi33/pac/SifeiPACWrapper.php');
require_once ('cfdi33/pac/PAC.php');

require_once ('cfdi33/pac/SifeiService.php');

$queryParameters = array();
foreach ($_REQUEST as $key=>$value) {
    $queryParameters[$key] = $value;
}

if (!empty($queryParameters['busca'])) {
    //error_log("Requested fc " . $queryParameters['busca']);
    $_SESSION['cVarVal'] = $queryParameters['busca'];
}
//error_log("Processing fc " . $_SESSION['cVarVal']);

if (!empty($queryParameters['Metododepago'])) {
    //error_log("Requested fc " . $queryParameters['Metododepago']);
    $_SESSION['cMDP'] = $queryParameters['Metododepago'];
}

$ciaDAO         = new CiaDAO();
$clientesDAO    = new ClientesDAO();
$fcDAO          = new FcDAO();
$pacDAO         = new ProveedorPACDAO();

$ppac   = $pacDAO->getActive();

$pac = new cfdi33\SifeiPAC($ppac->getUrl_webservice(), $ppac->getUsuario(), $ppac->getPassword());
$pac->setIdEquipo($ppac->getClave_aux());
$pac->setSerie("");

$busca  = $_SESSION['cVarVal']; // ID de FC

$cia = $ciaDAO->retrieveFields("facclavesat, clavesat, facturacion");
$fcVO = $fcDAO->retrieve($busca);
$clienteVO = $clientesDAO->retrieve($fcVO->getCliente());

$link = conectarse();
$Titulo = "Favor de confirmar sus datos";

$lBd = false;
$Msj = $queryParameters['Msj'];

if ($queryParameters['Boton'] == 'Genera factura formato carta') {
    $_SESSION['cVar'] = 0;
    $lBd = true;
} elseif ($queryParameters['Boton'] == 'Genera factura formato ticket') {
    $_SESSION['cVar'] = 1;
    $lBd = true;
}

if ($queryParameters['Boton'] == 'Guardar estos cambios') {

    $cSql = "UPDATE fc SET usocfdi = '$_REQUEST[Usocfdi]',formadepago='$_REQUEST[Formadepago]',
             metododepago = '$_REQUEST[Metododepago]',observaciones = '$_REQUEST[Observaciones]'
             WHERE fc.id='$busca'";

    if (!mysql_query($cSql)) {
        $Archivo = 'FC';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
    
    $Msj = " Cambio guardado";

} elseif ($queryParameters['Boton'] == 'Genera') {

/************************************************************************************************************************************************************/
    $facturaDetisa = new com\detisa\omicrom\FacturaDetisa($busca);
    if (count($facturaDetisa->getComprobante()->getConceptos()->getConcepto())==0) {
        die('<div align="center"><p>&nbsp;</p><font color="#99000">Error critico</font><b></b><br>El comprobante no tiene conceptos, no es posible timbrar un comprobante sin conceptos. Favor de verificar.<br><b>' . mysql_error() . '</b><br> favor de dar click en la flecha &nbsp <a class=nombre_cliente href=' . $_SERVER["PHP_SELF"] . '><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

    $sifei = new \cfdi33\SifeiService($pac);
    $sello = new \cfdi33\SelloCFDI();
    

    $sello->sellaComprobante($facturaDetisa->getComprobante());


    $DOMFactura = $facturaDetisa->getComprobante()->asXML();
    $xmlFactura = $DOMFactura->saveXML();
    //echo htmlspecialchars($xmlFactura) . '<br/><br/>';
    try {
        $xmlResponse = $sifei->timbraComprobante($xmlFactura);

        if ($xmlResponse) {
            trigger_error("Comprobante Timbrado " . $xmlResponse);
            $cfdiTimbrado = \cfdi33\Comprobante::parse($xmlResponse);
            trigger_error("Comprobante Timbrado " . print_r($cfdiTimbrado, TRUE));
            $facturaDetisa->setComprobanteTimbrado($cfdiTimbrado);
            $facturaDetisa->update();
            $facturaDetisa->save("SIFEI");
        } else {
            $Msj = $sifei->getError();
        }

        header("Location: facturas.php?pagina=0&Sort=Asc&orden=fc.id&busca=&Msj=$Msj");
 
    } catch (Exception $e) {
        print_r($e->getMessage());
        $Msj = "Error : " . $e->getMessage();
    }
    
     header("Location: facturas.php?pagina=0&Sort=Asc&orden=fc.id&busca=&Msj=$Msj");
/************************************************************************************************************************************************************/
}

$HeA   = mysql_query("SELECT fc.id,fc.cliente,clif.nombre,clif.direccion,clif.rfc,clif.codigo,
         clif.correo,fc.fecha,clif.enviarcorreo,fc.usocfdi,
         fc.formadepago,fc.importe,fc.observaciones,fc.metododepago
         FROM fc LEFT JOIN clif ON fc.cliente=clif.id
         WHERE fc.id='$busca'");

$He    = mysql_fetch_array($HeA);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

 echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

 headymenu($Titulo,1);

 //echo $cSql;

 
   	    	echo "<table width='100%' border='0' align='center' cellpadding='2' cellspacing='0' class='textos'>";
  	    	echo "<tr>";
  	    	echo "<td width='70' align='center'>regresar<br><a href='facturas.php?orden=fc.id'><img src='lib/regresa.jpg' border=0></a>";
			echo "</td><td align='center'>";

	    	echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

                $Nom = utf8_encode($He[nombre]);
                
  	    	echo "<table width='95%' border='1' align='center' cellpadding='0' cellspacing='0' class='textos'>";
                
			echo "<tr>";
			echo "<td align='right' width='30%'>* Nombre: $He[cliente] &nbsp;</td><td width='50%'>&nbsp;";
			echo "<input type='text' name='Nombre' value='$Nom' class='textos' size='80' onBLur=Mayusculas('Nombre')>";
			echo "</td></tr>";
			echo "<tr><td align='right'>* Rfc: &nbsp;</td><td>&nbsp;";
			echo "<input type='text' name='Rfc' value='$He[rfc]' class='textos' size='20' maxlength='13' onBlur='ValidaRfc(this.value)'>";
			echo "</td></tr>";
                        
			echo "<tr><td align='right'>* Municipio: &nbsp;</td><td>&nbsp;";
			echo "<input type='text' name='Municipio' value='$He[municipio]' class='textos' size='30' onBLur=Mayusculas('Municipio')> &nbsp; ";

			echo "* Cod.postal: ";
			echo "<input type='text' name='Codigo' value='$He[codigo]' class='textos' size='5' onBLur=Mayusculas('Codigo')>";

                        ?>
                        <tr>
                        <td align="right" bgcolor="#e1e1e1">Uso de CFDI: &nbsp;</td><td>&nbsp;
                        <?php    
                        echo "<select name='Usocfdi'>";
                        $UsoA = mysql_query("SELECT clave,descripcion FROM cfdi33_c_uso ORDER BY clave");
                        while($rg = mysql_fetch_array($UsoA)){                            
                            echo "<option value='$rg[clave]'>".$rg[clave]." | ".$rg[descripcion]."</option>";
                            if($He[usocfdi] == $rg[clave]){
                                $Display = $rg[descripcion];
                            }                                                          
                        }
                        echo "<option value='$He[usocfdi]' selected>$Display</option>";
                        echo "</select> &nbsp ";
                        ?>                        
                        </td>
                        </tr>                        
                        <tr>
                        <td align="right" bgcolor="#e1e1e1">CFDI relacionado: &nbsp;</td>
                        <td align="left">&nbsp;<input type="text" name="Relacioncfdi" id="Relacioncfdi" class="texto_tablas" size="10"/>
                                        &nbsp;<a href="javascript:openRelationshipWindow()"><img src='libnvo/acceso.png'/></a><small>en caso de ser necesario</small>
                                        &nbsp; <?php ComboboxTipoRelacion::generate('tiporelacion'); ?>
                        </td>
                        </tr>
                        <tr>
                            <td align="right" bgcolor="#e1e1e1">M&eacute;todo de pago: &nbsp;</td>
                            <td align="left">&nbsp;
                            <?php
                            echo "<select name='Metododepago'>";
                            $MpagoA = mysql_query("SELECT clave,descripcion FROM cfdi33_c_mpago ORDER BY clave");
                            while($rg = mysql_fetch_array($MpagoA)){                            
                                echo "<option value='$rg[clave]'>".$rg[clave]." | ".$rg[descripcion]."</option>";
                                if($He[metododepago] == $rg[clave]){
                                    $Display = $rg[descripcion];
                                }                                                          
                            }
                            echo "<option value='$He[metododepago]' selected>$Display</option>";
                            echo "</select> &nbsp ";
                            ?>
                            </td>
                        </tr>
                        
                        <?php
                        
			echo "<tr><td align='right'>Forma de pago: &nbsp;</td><td>&nbsp;";
                        echo "<select name='Formadepago'>";
                        $Pagos = mysql_query("SELECT * FROM cpagos ORDER BY clave");
                        while($rg = mysql_fetch_array($Pagos)){                            
                            echo "<option value='$rg[clave]'>".$rg[clave]." | ".$rg[concepto]."</option>";
                            if($He[formadepago] == $rg[clave]){
                                $Display = $rg[concepto];
                            }                                                          
                        }
                        echo "<option value='$He[formadepago]' selected>$Display</option>";
                        echo "</select> &nbsp ";
                        
                        echo "Observaciones: ";
			echo "<input type='text' name='Observaciones' value='$He[observaciones]' class='textos' size='20'>";                        
			echo "</td></tr>";

			echo "<tr><td align='right'>Correo electronico: &nbsp;</td><td>&nbsp;";
			echo "<input type='text' name='Correo' value='$He[correo]' class='textos' size='50'> &nbsp; enviar correo";

			if($He[enviarcorreo] == 'Si') {
				echo "<input type='checkbox' name='Enviarcorreo' value='Si' checked>";
			}else {
				echo "<input type='checkbox' name='Enviarcorreo' value='Si'>";
			}
			echo "</td></tr>";

			echo "<tr><td align='left'><font color='#990000'> &nbsp;$Msj </td><td align='right'>&nbsp;";
			echo "<input type='submit' style='background:#618fa9; color:#ffffff;font-weight:bold;' name='Boton' value='Guardar estos cambios'>";

			echo "</td></tr>";
	   	    echo "</table>";

			echo "<input type='hidden' name='op' value='ag'>";
			echo "<input type='hidden' name='Cliente' value='$He[cliente]'>";

	   		echo "</form>";


	   		echo "<div align='center' class='textosBoldAvanRegre'>PRODUCTOS A FACTURAR</div>";

  	   		echo "<table width='90%' border='1' align='center' cellpadding='3' cellspacing='0' class='textos'>";
			echo "<tr bgcolor=$Gfdogrid>";
			echo "<th>Producto</th>";
			echo "<th>Descripcion</th>";
			echo "<th>Cantidad</th>";
			echo "<th>Precio</th>";
			echo "<th>Descuento</th>";
			echo "<th>Importe</th>";
			echo "</tr>";

			$CpoA  = mysql_query("SELECT fcd.estudio,est.descripcion,fcd.precio,fcd.iva,
                                 fcd.importe,fcd.descuento,fcd.cantidad
                                 FROM fcd LEFT JOIN est ON fcd.estudio=est.estudio
                                 WHERE fcd.id='$busca' ORDER BY fcd.idnvo");

			//	$Detalle = "|CONCEPTO";

                    while($rg=mysql_fetch_array($CpoA)){

        		//if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           		echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           		echo "<td align='left'> $rg[estudio] </td>";
           		echo "<td align='left'> $rg[descripcion] </td>";
           		echo "<td align='right'> $rg[cantidad] </td>";
           		echo "<td align='right'> ".number_format($rg[precio],"2")." </td>";
		        echo "<td align='right'> ".number_format($rg[descuento],"2")." </td>";
           		echo "<td align='right'> ".number_format($rg[cantidad]*($rg[precio]*(1-($rg[descuento]/100))),"2")." </td>";
           		echo "</tr>";

           		$nRng++;                        

                    }
                    
                    echo "</table><br>";

                    echo "<table width='90%' border='1' align='center' cellpadding='2' cellspacing='0' class='textos'>";
                    echo "<tr><td>";
                        echo "<table width='99%' border='0' align='center' cellpadding='1' cellspacing='2' class='textos'>";
                        echo "<tr bgcolor='#c1c1c1'><th align='right'>Sub-total $ ".number_format($He[importe],"2")."</th>";
                        echo "<th align='right'>Iva $ ".number_format($He[iva],"2")."</th>";
                        //echo "<th align='right'>Ieps $ ".number_format($He[ieps],"2")."</th>";
                        echo "<th align='right'><font color='#990000'>Total $ ".number_format($He[total],"2")."&nbsp;</th></tr>";
                        echo "</table>";
                    echo "</td></tr></table><br>";

      		   echo "<form name='form2' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

			echo "<input type='hidden' name='Correo' value='$He[correo]'>";
			echo "<input type='hidden' name='Enviarcorreo' value='$He[enviarcorreo]'>";

			echo "<p align='center'>";

			echo "<input type='submit' style='background:#618fa9; color:#ffffff;font-weight:bold;' name='Boton' value='Genera'>";

			echo "</p>";


        	echo "</form>";

                //Cierra tabla 4
		echo "</td></tr></table>";


 
 
 
  CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

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

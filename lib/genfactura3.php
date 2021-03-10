<?php

session_start();

require("lib/lib.php");

$link     = conectarse();

require_once 'nusoap.php';
require_once 'class.phpmailer.php';
echo $_REQUEST[Ord];

if(isset($_REQUEST[busca])){$_SESSION['cVarVal']=$_REQUEST[busca];}


$link      	= conectarse();
$Error     	= $_REQUEST[errores];
$Titulo    	= "Favor de confirmar sus datos";
$busca     	= $_SESSION['cVarVal'];        //Dato a busca;
$Accion    	= $_REQUEST[accion];
$idFactura 	= $busca;	//Cuando viene con un valor quiere decir que regresa de la factura
//$idFactura = $_REQUEST[idFactura];	//Cuando viene con un valor quiere decir que regresa de la factura
/*
$CiaA  = mysql_query("SELECT nombre, rfc, direccion, numeroext, numeroint, colonia, ciudad, estado, telefono, 
        numeroext, numeroint, codigo, iva, facclavesat,facturacion
        FROM cia
        WHERE id =1");

$Cia   = mysql_fetch_array($CiaA);
*/
if($_REQUEST[op] == 'ag' AND $_REQUEST[Cliente] <> '')  {

        $lUp  = mysql_query("UPDATE clif SET nombre='$_REQUEST[Nombre]',
                rfc='$_REQUEST[Rfc]',codigo='$_REQUEST[Codigo]',cuentaban='$_REQUEST[Cuentaban]',
                formadepago='$_REQUEST[Formadepago]',correo='$_REQUEST[Correo]',
                enviarcorreo='$_REQUEST[Enviarcorreo]'
                WHERE id='$_REQUEST[Cliente]'");
        
	if($_REQUEST[Observaciones] <> ''){
           $lUp = mysql_query("UPDATE fc SET observaciones = '$_REQUEST[Observaciones]' WHERE id='$busca'");
        }   
        
       $Msj  = " Cambio guardado";

}elseif($_REQUEST[Boton] == 'Genera factura'){
    
    $cSQLy = "SELECT orden FROM fcd WHERE id = $busca";
    $CpoAy = mysql_query($cSQLy);
    $rgaa = mysql_fetch_array($CpoAy);
    
    
    $lUp = mysql_query("UPDATE ot SET factura = $busca WHERE orden = $rgaa[orden]");

    $cSql = "SELECT fc.id, fc.cliente, clif.nombre, clif.rfc, clif.codigo,clif.correo, 
             clif.enviarcorreo, clif.cuentaban, clif.formadepago, fc.importe, fc.iva, 
             fc.total, fc.fecha, fc.observaciones  
             FROM fc LEFT JOIN clif ON fc.cliente=clif.id 
             WHERE fc.id='$busca'";

    $HeA = mysql_query($cSql);
    $He  = mysql_fetch_array($HeA);

    $CiaA = mysql_query("SELECT nombre, rfc, direccion, numeroext, numeroint, colonia, ciudad, 
            estado, codigo, telefono,facturacion, facclavesat, iva                                 
            FROM cia");

    $Cia = mysql_fetch_array($CiaA);

    $cSQL = "SELECT
            fcd.estudio,
            est.descripcion,
            fcd.iva/100 as iva,
            est.inv_cunidad as umedida,
            est.inv_cproducto as clave_producto,
            fcd.cantidad,
            fcd.precio,
            fcd.descuento,fcd.orden
        FROM fcd JOIN est ON fcd.estudio=est.estudio
        WHERE fcd.id = $busca
        ";
    
    
    
    error_log("*********************************************************************************************************************");
    error_log($cSQL);
    error_log("*********************************************************************************************************************");

    $CpoA = mysql_query($cSQL);
    if (!$CpoA) {
        die('<div align="center"><p>&nbsp;</p><font color="#99000">Error critico</font><b></b><br>el proceso <b>NO finaliz&oacute; correctamente!</b> favor de informar al departamento de sistemas<br><b>' . mysql_error() . '</b><br> favor de dar click en la flecha &nbsp <a class=nombre_cliente href=' . $_SERVER["PHP_SELF"] . '><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
    // Inicializa los totalizadores
    $TSubtotal = 0;
    $TTotal = 0;
    $TIeps = 0;
    $TIva = 0;

    $ItemIndex = 0;

    while ($rg = mysql_fetch_array($CpoA)) {

        $cDescripcion = ucwords(strtolower($rg['descripcion']));
        $nClv = $rg['producto'];
        $Umedida = $rg['umedida'];

        if ($nClv < 10) {

            $ComA = mysql_query("SELECT clave, clavei FROM com WHERE descripcion LIKE '%$cDescripcion%'");
            $Com = mysql_fetch_array($ComA);

            if ($Com['clave'] <> '') {
                $nClv = $Com['clave'];
            }
            /*
              if ($Com[clavei] == 'D') {
              $Umedida = '01';
              }
             */
        }// If combustible

        if ($rg[ticket] > 0) {
            $cDescripcion = ucwords(strtolower($rg['descripcion'])) . " Ticket no: " . $rg['ticket'];
        }

        $TSubtotal += round(round($rg['cantidad'], 3) * round($rg['precio'], 6), 2);
        $TTotal += round($rg['total'], 4);

        $TIva += round($rg['impiva'], 4);
        $TIeps += round($rg['impieps'], 4);

        $Concepto = "|" . ( ++$ItemIndex) .
                "|" . round($rg['cantidad'], 3) .
                "|" . $Umedida .
                "|" . $nClv .
                "|" . $cDescripcion .
                "|" . round($rg['precio'], 6) .
                "|" . round($Descuento, 2) .
                "|" . round(round($rg['cantidad'], 3) * round($rg['precio'], 6), 2) .
                "|" . $Pedimento .
                "|" . $FecPedimento .
                "|" . $Aduana .
                "|" . $NumPredial .
                "|TRASLADADOS|IVA|" . round($rg['factoriva'], 0) .
                "|" . round($rg['impiva'], 4) .
                "|IEPS|" . round($rg['factorieps'], 4) .
                "|" . round($rg['impieps'], 4);
        #echo "<br/><br/>Concepto:<br/>" . $Concepto;
        $Detalle = $Detalle . $Concepto;
    }//foreach concepto

    $Partidas = cZeros($ItemIndex, 2);
    $Detalle = "|" . $Partidas . "|" . $Detalle;
    #echo "<br/><br/>Cadena Productos:<br/>" . $Detalle;

    $cSQLTotal = "
        SELECT 
           round( sum( cantidad ), 3) cantidad,
           round( sum( total ), 6) total,
           round( ( sum( total ) - sum( ieps ) ) / (1 + factoriva), 6) importe,
           round( ( sum( total ) - sum( ieps ) ) / (1 + factoriva) * factoriva, 6) iva,
           round( sum( ieps ) , 6) ieps
        FROM (
           SELECT 
              iva factoriva,
              CASE
                 WHEN tipoc = 'I' THEN
                    ( importe / preciob )
                 ELSE
                    ( cantidad )
              END AS cantidad,
              CASE
                 WHEN tipoc = 'I' THEN
                    ( importe )
                 ELSE
                    ( cantidad * preciob )
              END AS total,
              CASE
                 WHEN tipoc = 'I' THEN
                    ( ( importe/preciob ) * ieps )
                 ELSE
                    ( cantidad * ieps )
              END AS ieps
           FROM fcd WHERE id = $busca) as SUB
    ";
    #echo "<br/><br/>" . $cSQLTotal;
    $cTotA = mysql_query($cSQLTotal);
    if (!$cTotA) {
        die('<div align="center"><p>&nbsp;</p><font color="#99000">Error critico</font><b></b><br>el proceso <b>NO finaliz&oacute; correctamente!</b> favor de informar al departamento de sistemas<br><b>' . mysql_error() . '</b><br> favor de dar click en la flecha &nbsp <a class=nombre_cliente href=' . $_SERVER["PHP_SELF"] . '><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
    $cTotales = mysql_fetch_array($cTotA);

    #echo "<br/><br/>Totalizador Total " . $TTotal . " vs " . round($cTotales[total], 4);
    #echo "<br/><br/>Totalizador Subtotal " . $TSubtotal . " vs " . round($cTotales[importe], 4);
    #echo "<br/><br/>Totalizador IVA " . $TIva . " vs " . round($cTotales[iva], 4);
    #echo "<br/><br/>Totalizador IEPS " . $TIeps . " vs " . round($cTotales[ieps], 4);

    if ($Cia['facturacion'] == 'Si') {
        $Observaciones = $He['observaciones'];
    } else {
        $Observaciones = "  FACTURA DE DEMOSTRACION ";
    }

    if ($He['formadepago'] == '01' OR $He['formadepago'] == '') {
        $CuentaBan = '';
        $Formadepago = "01";
    } else {
        $CuentaBan = $He['cuentaban'];
        $Formadepago = $He['formadepago'];
    }

    $Enviar = $He['enviarcorreo'] == 'Si' ? "true" : "false";

    $RazonSocial = strtoupper($He['nombre']);

    $TotRetencion = 0;      //Osea --> $He[retencioniva] + $He[isr];
    $cPagos = $_REQUEST[Pagos] == 2 ? 'PARCIALIDADES' : 'PAGO EN UNA SOLA EXHIBICION';
    $TipoComprobante = 'ingreso';    //Ingreso para factura, No ingreso para NC
    $Moneda = "MXN";
    $TipoCambio = 1;
    $Referencia = "TEL. " . $Cia['telefono'];
    $DspRetenido = "";          //  RETENIDOS";
    $CptIva = "IVA";
    $TsaIva = round($Cia['iva'], 2);
    $ImpRetenido = 0;
    $CptIsr = "";
    $TsaIsr = "";
    $ImpIsr = "";
    $cRegimen = $Cia['regimen'];
    $cPublicidad = "";

    $Datos = "01" . //1.Encabezado;
            "|FA" . //2.Factura,Nota de Credito...
            "|" . "3.2" . //3.Version del archivo;
            "|" . $He['seriefac'] . //4.Serie(nada);
            "|" . $He['id'] . //5.Folio interno;
            "|" . $cPagos . //6.Condiciones de pago 1 de 5...
            "|" . $NumCertificado . //7.Numero de certificado aqui no mando nada
            "|" . $He['cndpago'] . //8.Condiciones de pago NO MANDO NADA
            "|" . round($cTotales['importe'], 4) . //9.Sub total
            "|" . $Descuento . //10.Descuento
            "|" . $DescDescuento . //11.DMotivo del descuento
            "|" . round($cTotales['total'], 4) . //12.Total despues del impuesto
            "|" . ($He['formadepago'] == '98' ? 'NA' : $He['formadepago']) . //13.Forma de pago efectivo, cheque...
            "|" . $TipoComprobante . //14.Ingreso, Egreso o traslado
            "|" . $Moneda . //15.Moneda MXN;
            "|" . $TipoCambio . //16Tipo de cambio 1;
            "|EMISOR" . //17
            "|" . $Cia['rfc'] . //18.Rfc
            "|" . $Cia['cia'] . //19.RAzon Social
            "|DOMICILIO FISCAL" . //20.
            "|" . $Cia['direccion'] . //21.Direccion fiscal
            "|" . $Cia['numeroext'] . //Numero exterior
            "|" . $Cia['numeroint'] . //Numero interior
            "|" . $Cia['colonia'] . //Colonia
            "|" . $Cia['ciudad'] . //Localidad, Delegacion,Municipio
            "|" . $Referencia . //Referencia de la localidad o/y tel
            "|" . $Cia['ciudad'] . //Ciudad
            "|" . $Cia['estado'] . //Estado
            "|MEXICO" . //Pais
            "|" . $Cia['codigo'] . //Codigo postal
            "|EXPEDIDO" . //ESTO LO MANDO CON MINUSCULAS
            "|" . $Cia['direccionexp'] . //Datos en caso de que se expida en otra direccion que no es el dom.fiscal
            "|" . $Cia['numeroextexp'] . //Numero exterior
            "|" . $Cia['numerointexp'] . //Numero interior
            "|" . $Cia['coloniaexp'] . //colonia
            "|" . $Cia['nada'] . //localidad y ref
            "|" . $Cia['ciudadexp'] . //municipio
            "|" . $Cia['estadoexp'] . //extado
            "|Mexico" . //Pais
            "|" . $Cia['codigoexp'] . //Codigo
            "|RECEPTOR" .
            "|" . strtoupper($He['rfc']) . //Rfc
            "|" . $RazonSocial . //Razon social
            "|DOMICILIO_FISCAL" .
            "|" . strtoupper($He['direccion']) . //Direccion
            "|" . $He['numeroext'] . //Numero exterior
            "|" . $He['numeroint'] . //
            "|" . $He['colonia'] . //colonia
            "|" . $He['localidad'] . //No lo uso no lo tengo
            "|" . $He['referencia'] .
            "|" . // no lo uso no lo tengo
            "|" . $He['municipio'] .
            "|" . strtoupper($He['estado']) .
            "|MEXICO" . //Pais $He[pais].
            "|" . $He['codigo'] .
            "|" . $He['correo'] .
            "|" . $TotRetencion . //Total de impuesto retenido
            "|" . round($TIva + ($He['desgloseIEPS'] == 'S' ? $TIeps : 0), 4) . //Total impuesto trasladado
            "|" . $DspRetenido . //Texto en caso de que haya retencion solo se pone RETENIDOS
            "|" . $CptIva . //Concepto palabra IVA
            "|" . $TsaIva . //Tasa iva
            "|" . $ImpRetenido . //Importe del iva retenido
            "|" . $CptIsr . //SI es que hay isr pone la palabra Isr
            "|" . $TsaIsr . //Tasa impuesto isr
            "|" . $ImpIsr . //Importe del Isr
            "|" . $Cia['ciudad'] . " " . $Cia['estado'] . //Lugar donde se expide el comprobante?????????
            "|" . $cRegimen . //Regimen
            "|" . $He['cuentaban'] . //Cuenta bancaria;
            "|" . ($He['desgloseIEPS'] == 'S' ? 'A' : '') . //Se indica si se va a desglosar el Ieps
            "|" . $Observaciones . //Observaciones
            "|" . $cPublicidad . //Observaciones
            "|" . $He['fecha'] . //Observaciones
            "|INI_PRODUCTOS";                                //Indico que empiezas el detalle de productos
    //echo "<br/><br/>Request:<br/>" . $Datos;
    //die();

    $Datos = $Datos . $Detalle;

    #break;

    $cCer = "fae/cer.cer";
    $cKey = "fae/key.key";
    $cLgo = "fae/logo.png";

    //===============Find datos========
    $logo = filetoStringB64('fae/logo.png');
    $certificado = filetoStringB64('fae/cer.cer');
    $llave = filetoStringB64('fae/key.key');
    $wsdl = 'http://localhost:9190/GeneradorCFDIsWEB/Facturador?wsdl';
    $client = new nusoap_client($wsdl, true);
    $client->timeout = 180;
    $client->soap_defencoding = 'UTF-8';
    $client->namespaces = array("SOAP-ENV" => "http://schemas.xmlsoap.org/soap/envelope/");

    $Fmt = $_SESSION['cVar'];        //Tipo de formato;
    // se preparan los parámetros de entrada
    $params = array(
        "user" => 'D3Ty0FeX',
        "password" => 'PaszTeX',
        "layout" => $Datos,
        "certificado" => $certificado,
        "llave" => $llave,
        "passPrivado" => $Cia[facclavesat],
        "logo" => $logo,
        "formato" => $Fmt,
        "idfc" => "$busca"
    );
    
    //llamamos al metodo
    try {

        if ($Cia[facturacion] == 'Si') {
            //Real
            $result = $client->call("obtenerCfdis", $params, false, '', '');
        } else {
            //llamamos al web service
            //Cuando hay error de conexion raro es aquiiiiiiiiiiiii
            $result = $client->call("obtenerCfdisTest", $params, false, '', '');
        }

        if (!$client->fault) {
            $Msj = $result["response"]["error"];
            header("Location: facturas.php?pagina=0&Sort=Asc&orden=fc.id&busca=&Msj=$Msj");
        } else {

            $facValida = $result["response"]["valid"];

            // revisamos si hay error
            $err = $client->getError();
            if ($err Or $facValida == 'false') {

                $cError = $result["response"]["error"];
                $Msj = $cError;

                header("Location: facturas.php?pagina=0&Sort=Asc&orden=fc.id&busca=&Msj=$Msj");
            }
            
            
        }
    } catch (Exception $e) {
        print_r($e->getMessage());
    }

    
}


$HeA   = mysql_query("SELECT fc.id,fc.cliente,clif.nombre,clif.rfc,clif.codigo,
         clif.correo,fc.fecha,fc.iva,clif.enviarcorreo,
         clif.cuentaban,clif.formadepago,fc.importe,fc.iva,fc.total,
         fc.observaciones
         FROM fc LEFT JOIN clif ON fc.cliente=clif.id
         WHERE fc.id='$busca'");

$He    = mysql_fetch_array($HeA);

require ("config.php");							   //Parametros de colores;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>

</head>

<?php

 echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

 headymenu($Titulo,1);

 //echo $cSql;

 
   	    	echo "<table width='100%' border='0' align='center' cellpadding='2' cellspacing='0' class='textos'>";
  	    	echo "<tr>";
  	    	echo "<td width='70' align='center'>regresar<br><a href='facturas3.php?orden=fc.id'><img src='lib/regresa.jpg' border=0></a>";
                echo "</td><td align='center'>";

	    	echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

                $Nom = utf8_encode($He[nombre]);
                
  	    	echo "<table width='95%' border='1' align='center' cellpadding='0' cellspacing='0' class='textos'>";
			echo "<tr>";
			echo "<td align='right' width='30%'>* Nombre: $He[cliente] &nbsp;</td><td width='50%'>&nbsp;";
			echo "<input type='text' name='Nombre' value='$Nom' class='textos' size='80' onBLur=Mayusculas('Nombre')>";
			echo "</td></tr>";
			echo "<tr><td align='right'>* Rfc: &nbsp;</td><td>&nbsp;";
			echo "<input type='text' name='Rfc' value='$He[rfc]' class='textos' size='20' maxlength='13' onBlur='ValidaRfc(this.value)'> &nbsp; &nbsp; ";                        
			echo "* Codigo postal: &nbsp; &nbsp;";
			echo "<input type='text' name='Codigo' value='$He[codigo]' class='textos' size='5' onBLur=Mayusculas('Codigo')>";
			echo "</td></tr>";
                        echo "<tr><td align='right'>Metodo de pago: &nbsp;</td><td>&nbsp;";
                        
                        echo "&nbsp;<SELECT class='texto_tablas' name='Formadepago'>";
                        $Pagos = mysql_query("SELECT * FROM cpagos ORDER BY clave");
                        while ($rg = mysql_fetch_array($Pagos)) {
                            echo "<option value='$rg[clave]'>" . $rg[clave] . " | " . $rg[concepto] . "</option>";
                            if ($He[formadepago] == $rg[clave]) {
                                $display = $rg[clave] . " | " . strtoupper($rg[concepto]);
                            }
                        }
                        echo "<option value='$Cpo[clave]'>selected>$display</option>";
                        echo "</select>";                                                
                        echo "</td></tr>";
                        
                        echo "<tr height='25'><td align='right' bgcolor='#e1e1e1'>Forma de pago: &nbsp;</td><td>&nbsp;";
                        echo "<select name='Pagos' class='texto_tablas'>";
                        echo "<option value = '1'>Pago en una sola exhibición</option>";
                        echo "<option value = '2'>Parcialidades</option>";
                        if(isset($_REQUEST[Pagos])){
                            if ($_REQUEST[Pagos] == 2) {
                                echo "<option value='2' selected>Parcialidades</option>";
                            } else {
                                echo "<option value='1' selected>Pago en una sola exhibición</option>";
                            }
                        }
                        echo "</select> ";
                        echo "</td></tr>";
                        
                        echo "<tr><td align='right'>Observaciones: ";
                        echo "</td><td>";
			echo "<input type='text' name='Observaciones' value='$He[observaciones]' class='textos' size='20'>";                        
			echo "</td></tr>";
                        
			echo "<tr><td align='right'>Correo electronico: &nbsp;</td><td>&nbsp;";
			echo "<input type='text' name='Correo' value='$He[correo]' class='textos' size='50'> &nbsp; enviar correo";

			if($He[enviarcorreo] == 'Si') {
				echo "<input type='checkbox' name='Enviarcorreo' value='Si' checked>";
			}else{
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
                        
                        $PrecioU   = round($rg[precio]*(1-($rg[descuento]/100)),2);
                        //Error al precio le esta quitando nuevamente el iva
                        //$nIvaPro   = round($rg[cantidad] * ($PrecioU - ($PrecioU/(1+($rg[iva]/100)))),2); //Lo saco asi para evitar diferencias y hacer que cuadre
                        $nIvaPro   = $rg[cantidad] * ($PrecioU*($rg[iva]/100)); //Lo saco asi para evitar diferencias y hacer que cuadre
                                                        
                        $Imp       = $rg[cantidad] * $PrecioU;  
                        $nIva      = $rg[iva];  //Factor
                        $nCnt      = $rg[cantidad];

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

                       echo "<p align='center'>";
                        echo "<input type='hidden' name='ord' value='$_REQUEST[ord]'>";
			echo "<input type='submit' style='background:#618fa9; color:#ffffff;font-weight:bold;' name='Boton' value='Genera factura'>";

			echo "</p>";

        	echo "</form>";

                //Cierra tabla 4
		echo "</td></tr></table>";
 
  CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";

  echo "</html>";

  mysql_close();

  
?>

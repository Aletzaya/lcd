<?php

/*
 * ComprobanteDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

namespace com\detisa\omicrom {

    require_once ('mysqlUtils.php');
    require_once ('cfdi33/Comprobante.php');
    require_once ('cfdi33/addenda/Observaciones.php');
    require_once ('pdf/PDFTransformer.php');

    class FacturaDAO {

        private $folio;
        /* @var $comprobante \cfdi33\Comprobante */
        private $comprobante;
        /* @var $mysqlConnection \mysqli */
        private $mysqlConnection;

        function __construct($folio) {

            error_log("Cargando CFDI con folio " . $folio);

            $this->folio = $folio;
            $this->comprobante= new \cfdi33\Comprobante();
            $this->mysqlConnection = getConnection();
            
            $this->comprobante();
            $this->emisor();
            $this->receptor();
            $this->cfdiRelacionados();
            $this->conceptos();
            $this->impuestos();
            $this->observaciones();
        }//constructor

        public function __destruct() {
            $this->mysqlConnection->close();
        }

        function getFolio() {
            return $this->folio;
        }

        function getComprobante() {
            return $this->comprobante;
        }

        function setFolio($folio) {
            $this->folio = $folio;
        }

        function setComprobante($comprobante) {
            $this->comprobante = $comprobante;
        }

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function comprobante() {

            $sql = "SELECT 
                    fc.id, 
                    DATE_FORMAT(fc.fecha, '%Y-%m-%dT%H:%h:%i') fecha, 
                    fc.serie, 
                    fc.formadepago, 
                    fc.metododepago, 
                    fc.total,
                    cia.codigo
                    FROM fc JOIN cia ON cia.id = 1
                    WHERE fc.id = " . $this->folio;

            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {
                $this->comprobante->setFolio($rs['id']);
                $this->comprobante->setSerie($rs['serie']);
                $this->comprobante->setFecha($rs['fecha']);
                $this->comprobante->setTipoDeComprobante("I");
                $this->comprobante->setVersion("3.3");
                $this->comprobante->setFormaPago($rs['formadepago']);
                $this->comprobante->setMetodoPago($rs['metododepago']);
                $this->comprobante->setMoneda("MXN");
                $this->comprobante->setTipoCambio(1);
                $this->comprobante->setTotal(number_format($rs['total'], 2, '.', ''));
                $this->comprobante->setLugarExpedicion($rs['codigo']);
            }else{
                error_log($this->mysqlConnection->error);
            }
        }//comprobante

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function emisor() {

            /* @var $emisor \cfdi33\Comprobante\Emisor */
            $emisor = new \cfdi33\Comprobante\Emisor();
            $sql = "SELECT nombre, rfc, clave_regimen FROM cia WHERE cia.id = 1";

            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {
                $emisor->setNombre($rs['nombre']);
                $emisor->setRfc($rs['rfc']);
                $emisor->setRegimenFiscal($rs['clave_regimen']);
            }else{
                error_log($this->mysqlConnection->error);
            }
            $this->comprobante->setEmisor($emisor);
        }//retrieve

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function receptor() {

            /* @var $emisor \cfdi33\Comprobante\Receptor */
            $receptor = new \cfdi33\Comprobante\Receptor();
            $sql = "SELECT clif.nombre, clif.rfc, fc.usocfdi FROM fc JOIN clif ON fc.cliente = clif.id WHERE fc.id = " . $this->folio;

            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {
                $receptor->setNombre($rs['nombre']);
                $receptor->setRfc($rs['rfc']);
                $receptor->setUsoCFDI($rs['usocfdi']);
            }else{
                error_log($this->mysqlConnection->error);
            }
            $this->comprobante->setReceptor($receptor);
        }//receptor

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function cfdiRelacionados() {

            $cfdiRelacionados = new \cfdi33\Comprobante\CfdiRelacionados();
            $cfdiRelacionado = new \cfdi33\Comprobante\CfdiRelacionados\CfdiRelacionado();

            $sql = "
                SELECT F.id, IFNULL(F.tiporelacion,  '') tiporelacion, IFNULL(R.id,  '') rfolio, IFNULL(R.uuid,  '') ruuid
                FROM fc F
                LEFT JOIN fc R ON R.id = F.relacioncfdi
                WHERE F.id = " . $this->folio;

            if (($query = $this->mysqlConnection->query($sql)) && ($rs = $query->fetch_assoc())) {
                if (!empty($rs['ruuid'])) {
                    $cfdiRelacionado->setUUID($rs['ruuid']);
                    $cfdiRelacionados->addCfdiRelacionado($cfdiRelacionado);
                    $cfdiRelacionados->setTipoRelacion($rs['tiporelacion']);
                    $this->comprobante->setCfdiRelacionados($cfdiRelacionados);
                }
            }else{
                error_log($this->mysqlConnection->error);
            }
        }//cfdiRelacionados

        /**
         * 
         * @throws \com\detisa\omicrom\Exception 
         */
        private function conceptos() {

            $conceptos = new \cfdi33\Comprobante\Conceptos();
            $subTotal = 0.00;
            $sql = "SELECT fcd.estudio NoIdentificacion, 
                   est.descripcion Descripcion, 
                   est.inv_cunidad ClaveUnidad, 
                   est.inv_cproducto ClaveProdServ, 
                   fcd.cantidad Cantidad, 
                   round(fcd.importe,2) Importe, 
                   round(fcd.precio,2) ValorUnitario,
                   fcd.iva/100 base_iva,
                   CAST( fcd.iva/100 AS DECIMAL( 10, 6 ) ) factoriva,
                   fcd.descuento/100 base_descuento                    
                   FROM fcd, est
                   WHERE fcd.id = " . $this->folio . " AND fcd.estudio = est.estudio";

            $total = 0.00;

            if (($query = $this->mysqlConnection->query($sql))) {
                
                while (($rs = $query->fetch_assoc())) {
                    
                    $concepto = new \cfdi33\Comprobante\Conceptos\Concepto();
                    
                    $concepto->setClaveProdServ($rs['ClaveProdServ']);
                    $concepto->setClaveUnidad($rs['ClaveUnidad']);
                    $concepto->setDescripcion($rs['Descripcion']);
                    $concepto->setImporte(number_format($rs['ValorUnitario'], 2, '.', ''));
                    $concepto->setCantidad(number_format($rs['Cantidad'], 4, '.', ''));
                    $concepto->setNoIdentificacion($rs['NoIdentificacion']);
                    $concepto->setValorUnitario(number_format($rs['ValorUnitario'], 4, '.', ''));

                    $subTotal += $rs['ValorUnitario'];

                    $traslados = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados();

                    $iva = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados\Traslado();
                    $iva->setBase(number_format($rs['ValorUnitario'], 2, '.', ''));
                    $iva->setImpuesto('002');
                    $iva->setTasaOCuota($rs['factoriva']);
                    $iva->setTipoFactor('Tasa');
                    //Iva del producto
                    $ImporteIva = round($rs['Importe']-$rs['ValorUnitario'],2);
                    $iva->setImporte(number_format($ImporteIva, 2, '.', ''));

                    $traslados->addTraslado($iva);
                    /*
                    if ($rs['tax_ieps']>0) {
                        $ieps = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados\Traslado();
                        $ieps->setBase(number_format($rs['base_ieps'], 2, '.', ''));
                        $ieps->setImpuesto('003');
                        $ieps->setTasaOCuota($rs['factorieps']);
                        $ieps->setTipoFactor('Cuota');
                        $ieps->setImporte(number_format($rs['tax_ieps'], 2, '.', ''));

                        $traslados->addTraslado($ieps);
                    }
                    */
                    //$total += $rs['ValorUnitario'];

                    $impuestos = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos();
                    $impuestos->setTraslados($traslados);
                    $concepto->setImpuestos($impuestos);
                    $conceptos->addConcepto($concepto);
                }//while
                /*
                if ($total !== $this->comprobante->getTotal()) {
                    $difference = round( $this->comprobante->getTotal()-$total, 2 );
                    error_log("There is a difference " . $difference );

                    $subTotal += $difference;
                    $arreglo = $conceptos->getConcepto();
                    $importe = $arreglo[0]->getImporte() + $difference;
                    $cantidad = round( $importe / $arreglo[0]->getValorUnitario(), 4 );
                    $arreglo[0]->setImporte($importe);
                    $arreglo[0]->setCantidad($cantidad);
                }
                */
                $this->comprobante->setSubTotal(number_format($subTotal, 2, '.', ''));
                $this->comprobante->setConceptos($conceptos);
                 
               
            }else{
                error_log($this->mysqlConnection->error);
            }
        }//conceptos

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        //Agrupa la suma de los impuestos por tasa
        private function impuestos() {

            $impuestos = new \cfdi33\Comprobante\Impuestos();
            $traslados = new \cfdi33\Comprobante\Impuestos\Traslados();

            $sql = "
                SELECT 
                    SUM(round(importe-precio,2)) ValorIva, 
                    CAST( fcd.iva /100 AS DECIMAL( 10, 6 ) ) factoriva
                    FROM fcd, est
                    WHERE fcd.id = " . $this->folio . "
                    AND fcd.estudio = est.estudio
                    GROUP BY factoriva";
//echo $sql;
            $total_traslado = 0.00;

            if (($query = $this->mysqlConnection->query($sql))) {
                while (($rs = $query->fetch_assoc())) {
                    $total_traslado += $rs['ValorIva'];
                    $iva = new \cfdi33\Comprobante\Impuestos\Traslados\Traslado();
                    $iva->setImporte(number_format($rs['ValorIva'], 2, '.', ''));
                    $iva->setImpuesto('002');
                    $iva->setTasaOCuota($rs['factoriva']);
                    $iva->setTipoFactor('Tasa');
                    $traslados->addTraslado($iva);                   
                }


                $impuestos->setTraslados($traslados);
                //$impuestos->setTotalImpuestosRetenidos('0.00');
                $impuestos->setTotalImpuestosTrasladados($total_traslado);

                $this->comprobante->setImpuestos($impuestos);
            }else{
                error_log($this->mysqlConnection->error);
            }
        }//getImpuestosFactura

        /**
         * 
         * @throws \com\detisa\omicrom\Exception
         */
        private function observaciones() {
            
            $observaciones = new \Observaciones();
            $sql = "
                SELECT fc.observaciones, fcd.ticket 
                FROM fc JOIN fcd ON fcd.id = fc.id
                WHERE fc.id = " . $this->folio . " AND producto <= 10
            ";

            $observacion = '';
            $tickets = '';
            $i = 0;
            if (($query = $this->mysqlConnection->query($sql))) {
                while (($rs = $query->fetch_assoc())) {
                    $observacion = $rs['observaciones'];
                    $tickets .= ($i++>0 ? ', ' : '') . $rs['ticket'];
                }
                if ($observacion !== '') {
                    $observaciones->addObservaciones(new \Observacion($observacion));
                }
                if ($tickets !== '') {
                    $observaciones->addObservaciones(new \Observacion('* Correspondiente a los Tickets : ' . $tickets));
                }

                $this->comprobante->addAddenda($observaciones);
            }else{
                error_log($this->mysqlConnection->error);
            }
        }
        
        public function updateFC($id, $uuid) {
            
            $sql = "UPDATE fc SET uuid = '" . $uuid . "', status = 'Timbrada' WHERE fc.id = " . $id;
            trigger_error($sql);
            return $this->mysqlConnection->query($sql);
        }
        
        public function updateRM($id, $uuid) {

            $sql = "UPDATE rm SET uuid = '" . $uuid . "' "
                    . "WHERE id IN (SELECT ticket FROM fcd JOIN inv ON inv.id = fcd.producto AND rubro = 'Combustible' WHERE fcd.id = " . $id . " AND ticket <> 0)";
            trigger_error($sql);
            return $this->mysqlConnection->query($sql);
        }
        
        public function updateVTA($id, $uuid) {
            
            $sql = "UPDATE vtaditivos SET uuid = '" . $uuid . "' "
                    . "WHERE id IN (SELECT ticket FROM fcd JOIN inv ON inv.id = fcd.producto AND rubro = 'Aceites' WHERE fcd.id = " . $id . " AND ticket <> 0)";
            trigger_error($sql);
            return $this->mysqlConnection->query($sql);
        }
        
        
        /**
         * 
         * @param \cfdi33\Comprobante $Comprobante
         */
        public function insertFactura($Comprobante, $clavePAC) {
            
            $sql = "INSERT INTO facturas (cfdi_xml, pdf_format, fecha_emision, fecha_timbrado, clave_pac, id_fc_fk, emisor, receptor, uuid)"
                    . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            trigger_error($sql);

            $pdf = \PDFTransformer::getPDF($Comprobante, 'S');
            $DOM = $Comprobante->asXML();
            $xml = $DOM->saveXML();
           
            $stmt = $this->mysqlConnection->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sssssssss", 
                        $xml,
                        $pdf,
                        $Comprobante->getFecha(),
                        $Comprobante->getTimbreFiscalDigital()->getFechaTimbrado(),
                        $clavePAC,
                        $Comprobante->getFolio(),
                        $Comprobante->getEmisor()->getRfc(),
                        $Comprobante->getReceptor()->getRfc(),
                        $Comprobante->getTimbreFiscalDigital()->getUUID());
                if (!$stmt->execute()) {
                    trigger_error($stmt->error);
                }
            } else {
                trigger_error("Error insertando factura " . $this->mysqlConnection->error);
            }
            
        }
    }//FacturaDAO
}//com\detisa\omicrom

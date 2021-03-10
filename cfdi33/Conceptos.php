<?php

/*
 * Conceptos
 * cfdi33®
 * ® 2017, Softcoatl 
 * http://www.softcoatl.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

namespace cfdi33\Comprobante {

    require_once ('CFDIElement.php');

    class Conceptos implements \cfdi33\CFDIElement {

        private $Concepto = array();

        function getConcepto() {
            return $this->Concepto;
        }

        function addConcepto($Concepto) {
            array_push($this->Concepto, $Concepto);
        }

        public function asJsonArray() {

            $conceptos = array();
            $idx = 0;

            /* @var $concepto Concepto */
            foreach ($this->Concepto as $concepto) {
                $conceptos[$idx++] = $concepto->asJsonArray();
            }

            return array("Concepto" => $conceptos);
        }//Conceptos::asJsonArray

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {

            /* @var $Conceptos \DOMElement */
            $Conceptos = $root->ownerDocument->createElement("cfdi:Conceptos");
            /* @var $concepto Conceptos\Concepto */
            foreach ($this->Concepto as $concepto) {
                $Conceptos->appendChild($concepto->asXML($root));
            }
            return $Conceptos;
        }

        /**
         * 
         * @param \DOMElement $DOMConceptos
         * @return \cfdi33\Comprobante\Conceptos
         */
        public static function parse($DOMConceptos) {
            
            $Conceptos = new Conceptos();
            /* @var $DOMConcepto DOMElement */
            for ($i=0; $i<$DOMConceptos->childNodes->length; $i++) {
                /* @var $DOMConcepto \DOMElement */
                $DOMConcepto = $DOMConceptos->childNodes->item($i);
                if (strpos($DOMConcepto->nodeName, ':Concepto')) {
                    $Concepto = new \cfdi33\Comprobante\Conceptos\Concepto();
                    \com\softcoatl\Reflection::setAttributes($Concepto, $DOMConcepto);
                    if ($DOMConcepto->hasChildNodes()) {
                        $Impuestos = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos();
                        for ($j=0; $j<$DOMConcepto->childNodes->length; $j++) {
                            if (strpos($DOMConcepto->childNodes->item($j)->nodeName, ':Impuestos')) {
                                /* @var $DOMImpuestos \DOMElement */
                                    $DOMImpuestos = $DOMConcepto->childNodes->item($j);
                                for ($k=0; $k<$DOMImpuestos->childNodes->length; $k++) {
                                    /* @var $DOMImpuesto DOMElement */
                                    $DOMImpuesto = $DOMImpuestos->childNodes->item($k);
                                    if (strpos($DOMImpuesto->nodeName, ':Traslados')) {
                                        $Traslados = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados();
                                        for ($l=0; $l<$DOMImpuesto->childNodes->length; $l++) {
                                            $DOMTraslado = $DOMImpuesto->childNodes->item($l);
                                            if (strpos($DOMTraslado->nodeName, ':Traslado')) {
                                                $Traslado = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados\Traslado();
                                                \com\softcoatl\Reflection::setAttributes($Traslado, $DOMTraslado);
                                                $Traslados->addTraslado($Traslado);
                                            }
                                        }
                                        $Impuestos->setTraslados($Traslados);
                                    } else if (strpos($DOMImpuesto->nodeName, ':Retenciones')) {
                                        $Retenciones = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Retenciones();
                                        for ($l=0; $l<$DOMImpuesto->childNodes->length; $l++) {
                                            $DOMRetencion = $DOMImpuesto->childNodes->item($l);
                                            if (strpos($DOMRetencion->nodeName, ':Retencion')) {
                                                $Retencion = new \cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Retenciones\Retencion();
                                                \com\softcoatl\Reflection::setAttributes($Retencion, $DOMRetencion);
                                                $Retenciones->addRetencion($Retencion);
                                            }
                                        }
                                        $Impuestos->setRetenciones($Retenciones);
                                    }
                                }
                            }
                        }
                    }
                    $Conceptos->addConcepto($Concepto);
                }
            }
            return $Conceptos;
        }
    }//Conceptos

    
}//cfdi33\Comprobante

namespace cfdi33\Comprobante\Conceptos {

    class Concepto implements \cfdi33\CFDIElement {

        /* @var $impuestos Concepto\Impuestos */ 
        private $Impuestos;
        private $InformacionAduanera = array();
        private $CuentaPredial;
        private $ComplementoConcepto;
        private $Parte = array();
        private $ClaveProdServ;
        private $NoIdentificacion;
        private $Cantidad;
        private $ClaveUnidad;
        private $Unidad;
        private $Descripcion;
        private $ValorUnitario;
        private $Importe;
        private $Descuento;

        function getImpuestos() {
            return $this->Impuestos;
        }

        function getInformacionAduanera() {
            return $this->InformacionAduanera;
        }

        function getCuentaPredial() {
            return $this->CuentaPredial;
        }

        function getComplementoConcepto() {
            return $this->ComplementoConcepto;
        }

        function getParte() {
            return $this->Parte;
        }

        function getClaveProdServ() {
            return $this->ClaveProdServ;
        }

        function getNoIdentificacion() {
            return $this->NoIdentificacion;
        }

        function getCantidad() {
            return $this->Cantidad;
        }

        function getClaveUnidad() {
            return $this->ClaveUnidad;
        }

        function getUnidad() {
            return $this->Unidad;
        }

        function getDescripcion() {
            return $this->Descripcion;
        }

        function getValorUnitario() {
            return $this->ValorUnitario;
        }

        function getImporte() {
            return $this->Importe;
        }

        function getDescuento() {
            return $this->Descuento;
        }

        function setImpuestos($Impuestos) {
            $this->Impuestos = $Impuestos;
        }

        function addInformacionAduanera($InformacionAduanera) {
            array_push($this->InformacionAduanera, $InformacionAduanera);
        }

        function setCuentaPredial($CuentaPredial) {
            $this->CuentaPredial = $CuentaPredial;
        }

        function setComplementoConcepto($ComplementoConcepto) {
            $this->ComplementoConcepto = $ComplementoConcepto;
        }

        function setParte($Parte) {
            $this->Parte = $Parte;
        }

        function setClaveProdServ($ClaveProdServ) {
            $this->ClaveProdServ = $ClaveProdServ;
        }

        function setNoIdentificacion($NoIdentificacion) {
            $this->NoIdentificacion = $NoIdentificacion;
        }

        function setCantidad($Cantidad) {
            $this->Cantidad = $Cantidad;
        }

        function setClaveUnidad($ClaveUnidad) {
            $this->ClaveUnidad = $ClaveUnidad;
        }

        function setUnidad($Unidad) {
            $this->Unidad = $Unidad;
        }

        function setDescripcion($Descripcion) {
            $this->Descripcion = $Descripcion;
        }

        function setValorUnitario($ValorUnitario) {
            $this->ValorUnitario = $ValorUnitario;
        }

        function setImporte($Importe) {
            $this->Importe = $Importe;
        }

        function setDescuento($Descuento) {
            $this->Descuento = $Descuento;
        }


        private function getVarArray() {
            return array_filter(get_object_vars($this), 
                            function ($val) { 
                                return !is_array($val) 
                                    && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val))
                                    && !($val instanceof Concepto\Impuestos);                    
            }); 
        }
            
        public function asJsonArray() {

            $ov = $this->getVarArray();

            if ($this->Impuestos !== NULL) {
                $ov["Impuestos"] = $this->Impuestos->asJsonArray();
            }

            if (!empty($this->InformacionAduanera)) {
                $informacionAduanera = array();
                $idx = 0;

                /* @var $parte InformacionAduanera */
                foreach ($this->InformacionAduanera as $parte) {
                    $informacionAduanera[$idx++] = $parte->asJsonArray();
                }

                $ov["InformacionAduanera"] = $informacionAduanera;
            }//if there is Informaci�n Aduanera

            if (!empty($this->Parte)) {
                $partes = array();
                $idx = 0;

                /* @var $parte Parte */
                foreach ($this->Parte as $parte) {
                    $partes[$idx++] = $parte->asJsonArray();
                }

                $ov["Parte"] = $partes;
            }

            return $ov;
        }//Concepto::asJsonString

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {

            $Concepto = $root->ownerDocument->createElement("cfdi:Concepto");
            $ov = $this->getVarArray();
            foreach ($ov as $attr=>$value) {
                $Concepto->setAttribute($attr, $value);
            }
            
            if ($this->Impuestos !== NULL) {
                $Concepto->appendChild($this->Impuestos->asXML($root));
            }

            if (!empty($this->InformacionAduanera)) {

                $informacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
                /* @var $parte InformacionAduanera */
                foreach ($this->InformacionAduanera as $parte) {
                    $informacionAduanera->appendChild($parte->asXML($root));
                }

                $Concepto->appendChild($informacionAduanera);
            }//if there is Informaci�n Aduanera

            if (!empty($this->Parte)) {

                $partes = $root->ownerDocument->createElement("cfdi:Parte");
                /* @var $parte Parte */
                foreach ($this->Parte as $parte) {
                    $partes->appendChild($parte->asXML($root));
                }
                $Concepto->appendChild($partes);
            }

            return $Concepto;
        }

    }//cfdi33\Comprobante\Concepto
   
}//cfdi33\Comprobante\Conceptos

namespace cfdi33\Comprobante\Conceptos\Concepto {

    class InformacionAduanera implements \cfdi33\CFDIElement {

        private $NumeroPedimento;

        function getNumeroPedimento() {
            return $this->NumeroPedimento;
        }

        function setNumeroPedimento($NumeroPedimento) {
            $this->NumeroPedimento = $NumeroPedimento;
        }

        public function asJsonArray() {
            return array_filter(get_object_vars($this));
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {

            $InformacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
            $InformacionAduanera->setAttribute("NumeroPedimento", $this->NumeroPedimento);
            return $InformacionAduanera;
        }

//InformacionAduanera::asJsonArray

    }//cfdi33\Comprobante\Conceptos\Concepto\InformacionAduanera

    class Parte implements \cfdi33\CFDIElement {

        private $InformacionAduanera = array();
        private $ClaveProdServ;
        private $NoIdentificacion;
        private $Cantidad;
        private $Unidad;
        private $Descripcion;
        private $ValorUnitario;
        private $Importe;

        function getInformacionAduanera() {
            return $this->InformacionAduanera;
        }

        function getClaveProdServ() {
            return $this->ClaveProdServ;
        }

        function getNoIdentificacion() {
            return $this->NoIdentificacion;
        }

        function getCantidad() {
            return $this->Cantidad;
        }

        function getUnidad() {
            return $this->Unidad;
        }

        function getDescripcion() {
            return $this->Descripcion;
        }

        function getValorUnitario() {
            return $this->ValorUnitario;
        }

        function getImporte() {
            return $this->Importe;
        }

        function addInformacionAduanera($InformacionAduanera) {
            array_push($this->InformacionAduanera, $InformacionAduanera);
        }

        function setClaveProdServ($ClaveProdServ) {
            $this->ClaveProdServ = $ClaveProdServ;
        }

        function setNoIdentificacion($NoIdentificacion) {
            $this->NoIdentificacion = $NoIdentificacion;
        }

        function setCantidad($Cantidad) {
            $this->Cantidad = $Cantidad;
        }

        function setUnidad($Unidad) {
            $this->Unidad = $Unidad;
        }

        function setDescripcion($Descripcion) {
            $this->Descripcion = $Descripcion;
        }

        function setValorUnitario($ValorUnitario) {
            $this->ValorUnitario = $ValorUnitario;
        }

        function setImporte($Importe) {
            $this->Importe = $Importe;
        }

        private function getVarArray() {
            return array_filter(get_object_vars($this), 
                            function ($val) { 
                                return !is_array($val) 
                                    && ($val === '0' || $val === 0 || $val === 0.0 ||  !empty($val));
                            });
        }

        public function asJsonArray() {

            $ov = $this->getVarArray();
            if (!empty($this->InformacionAduanera)) {
                $informacionAduanera = array();
                $idx = 0;

                /* @var $parte InformacionAduanera */
                foreach ($this->InformacionAduanera as $parte) {
                    $informacionAduanera[$idx++] = $parte->asJsonArray();
                }

                $ov["InformacionAduanera"] = $informacionAduanera;
            }

            return $ov;        

        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {

            $Parte = $root->ownerDocument->createElement("cfdi:Parte");
            $ov = $this->getVarArray();
            
            foreach ($ov as $attr=>$value) {
                $Parte->setAttribute($attr, $value);
            }
            
            if (!empty($this->InformacionAduanera)) {

                $informacionAduanera = $root->ownerDocument->createElement("cfdi:InformacionAduanera");
                /* @var $parte InformacionAduanera */
                foreach ($this->InformacionAduanera as $parte) {
                    $informacionAduanera->appendChild($parte->asXML($root));
                }
                $Parte->appendChild($informacionAduanera);
            }

            return $Parte;
        }

//Parte::asJsonArray

    }//cfdi33\Comprobante\Conceptos\Concepto\Parte

    class Impuestos implements \cfdi33\CFDIElement {

        /* @var $Traslados Impuestos\Traslados */
        private $Traslados;
        /* @var $Retenciones Impuestos\Retenciones */
        private $Retenciones;
        
        function getTraslados() {
            return $this->Traslados;
        }

        function getRetenciones() {
            return $this->Retenciones;
        }

        function setTraslados($Traslados) {
            $this->Traslados = $Traslados;
        }

        function setRetenciones($Retenciones) {
            $this->Retenciones = $Retenciones;
        }

        public function asJsonArray() {
            $impuestos = array();

            if ($this->Traslados != NULL) {
                $traslado = $this->Traslados->getTraslado();
                if (!empty($traslado)) {
                    $impuestos["Traslados"] = $this->Traslados->asJsonArray(); 
                }
            }
            
            if ($this->Retenciones != NULL) {
                $retencion =  $this->Retenciones->getRetencion();
                if (!empty($retencion)) {
                    $impuestos["Retenciones"] = $this->Retenciones->asJsonArray(); 
                }
            }

            return $impuestos;
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {

            $Impuestos = $root->ownerDocument->createElement("cfdi:Impuestos");
            if ($this->Traslados != NULL) {
                $Impuestos->appendChild($this->Traslados->asXML($root));
            }            
            if ($this->Retenciones != NULL) {
                $Impuestos->appendChild($this->Retenciones->asXML($root));
            }
            return $Impuestos;
        }

    }//cfdi33\Comprobante\Conceptos\Concepto\Impuestos

    
}//cfdi33\Comprobante\Conceptos\Concepto

namespace cfdi33\Comprobante\Conceptos\Concepto\Impuestos {

    class Traslados implements \cfdi33\CFDIElement {

        private $Traslado = array();

        function getTraslado() {
            return $this->Traslado;
        }

        /* @var $Traslado Traslados\Traslado */
        function addTraslado($Traslado) {
            array_push($this->Traslado, $Traslado);
        }

        public function asJsonArray() {
            $traslados = array();
            $idx = 0;

            /* @var $traslado Traslados\Traslado */
            foreach ($this->Traslado as $traslado) {
                $traslados[$idx++] = $traslado->asJsonArray();
            }

            return array("Traslado" => $traslados);
        }//Traslados::asJsonArray

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Traslado = $root->ownerDocument->createElement("cfdi:Traslados");
            /* @var $traslado Traslados\Traslado */
            foreach ($this->Traslado as $tax) {
                $Traslado->appendChild($tax->asXML($root));
            }
            return $Traslado;
        }

    }//cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados

    class Retenciones implements \cfdi33\CFDIElement {

        private $Retencion = array();

        function getRetencion() {
            return $this->Retencion;
        }

        /* @var $Retencion Retenciones\Retencion */
        function addRetencion($Retencion) {
            array_push($this->Retencion, $Retencion);
        }

        public function asJsonArray() {
            $retenciones = array();
            $idx = 0;

            /* @var $retencion Traslados\Traslado */
            foreach ($this->Retencion as $retencion) {
                $retenciones[$idx++] = $retencion->asJsonArray();
            }

            return array("Retencion" => $retenciones);
        }//Retenciones::asJsonArray

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Retencion = $root->ownerDocument->createElement("cfdi:Retenciones");
            /* @var $traslado Retenciones\Retencion */
            foreach ($this->Retencion as $tax) {
                $Retencion->appendChild($tax->asXML($root));
            }
            return $Retencion;
        }

    }//cfdi33\Comprobante\Conceptos\Concepto\Retenciones

}//cfdi33\Comprobante\Conceptos\Impuestos

namespace cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados {

    class Traslado implements \cfdi33\CFDIElement {

        private $Base;
        private $Impuesto;
        private $TipoFactor;
        private $TasaOCuota;
        private $Importe;
        
        function getBase() {
            return $this->Base;
        }

        function getImpuesto() {
            return $this->Impuesto;
        }

        function getTipoFactor() {
            return $this->TipoFactor;
        }

        function getTasaOCuota() {
            return $this->TasaOCuota;
        }

        function getImporte() {
            return $this->Importe;
        }

        function setBase($Base) {
            $this->Base = $Base;
        }

        function setImpuesto($Impuesto) {
            $this->Impuesto = $Impuesto;
        }

        function setTipoFactor($TipoFactor) {
            $this->TipoFactor = $TipoFactor;
        }

        function setTasaOCuota($TasaOCuota) {
            $this->TasaOCuota = $TasaOCuota;
        }

        function setImporte($Importe) {
            $this->Importe = $Importe;
        }

        public function asJsonArray() {
            return array_filter(get_object_vars($this));
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Traslado = $root->ownerDocument->createElement("cfdi:Traslado");
            $ov = array_filter(get_object_vars($this));
            
            foreach ($ov as $attr=>$value) {
                $Traslado->setAttribute($attr, $value);
            }
            
            return $Traslado;
        }

    }//cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados\Traslado

}//cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Traslados

namespace cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Retenciones {

    class Retencion implements \cfdi33\CFDIElement {

        private $Base;
        private $Impuesto;
        private $TipoFactor;
        private $TasaOCuota;
        private $Importe;
        
        function getBase() {
            return $this->Base;
        }

        function getImpuesto() {
            return $this->Impuesto;
        }

        function getTipoFactor() {
            return $this->TipoFactor;
        }

        function getTasaOCuota() {
            return $this->TasaOCuota;
        }

        function getImporte() {
            return $this->Importe;
        }

        function setBase($Base) {
            $this->Base = $Base;
        }

        function setImpuesto($Impuesto) {
            $this->Impuesto = $Impuesto;
        }

        function setTipoFactor($TipoFactor) {
            $this->TipoFactor = $TipoFactor;
        }

        function setTasaOCuota($TasaOCuota) {
            $this->TasaOCuota = $TasaOCuota;
        }

        function setImporte($Importe) {
            $this->Importe = $Importe;
        }

        public function asJsonArray() {
            return array_filter(get_object_vars($this));        
        }

        /**
         * 
         * @param \DOMElement $root
         * @return \DOMNode
         */
        public function asXML($root) {
            
            $Retencion = $root->ownerDocument->createElement("cfdi:Retencion");
            $ov = array_filter(get_object_vars($this));
            
            foreach ($ov as $attr=>$value) {
                $Retencion->setAttribute($attr, $value);
            }
            
            return $Retencion;
        }

    }//cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Retenciones\Retencion

}//cfdi33\Comprobante\Conceptos\Concepto\Impuestos\Retenciones

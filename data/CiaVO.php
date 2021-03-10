<?php

/*
 * CiaVO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

class CiaVO {

    private $cia;
    private $direccion;
    private $numeroext;
    private $numeroint;
    private $colonia;
    private $ciudad;
    private $estado;
    private $telefono;
    private $desgloce;
    private $iva;
    private $rfc;
    private $regimen;
    private $codigo;
    private $pasw;
    private $estacion;
    private $factor;
    private $numestacion;
    private $clavepemex;
    private $segundos;
    private $lastpein;
    private $folenvios;
    private $clavegpg;
    private $folioenvios;
    private $serie;
    private $facturacion;
    private $facclavesat;
    private $zonahoraria;
    private $master;
    private $rfc_proveedor_sw;
    private $clave_envios_xml;
    private $activa_envio_xml;
    private $direccionexp;
    private $numeroextexp;
    private $numerointexp;
    private $coloniaexp;
    private $ciudadexp;
    private $estadoexp;
    private $codigoexp;
    private $ventastarxticket;
    private $diaslimiteticket;
    private $sesion;
    private $md5;
    private $firmwaremd5;
    private $clavesat;
    private $rfc_proveedor_ws;
    private $pesosporpunto;
    private $vigencia;
    private $claveterminal;
    private $clave_regimen;
    private $version_cfdi;
    
    function getCia() {
        return $this->cia;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getNumeroext() {
        return $this->numeroext;
    }

    function getNumeroint() {
        return $this->numeroint;
    }

    function getColonia() {
        return $this->colonia;
    }

    function getCiudad() {
        return $this->ciudad;
    }

    function getEstado() {
        return $this->estado;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getDesgloce() {
        return $this->desgloce;
    }

    function getIva() {
        return $this->iva;
    }

    function getRfc() {
        return $this->rfc;
    }

    function getRegimen() {
        return $this->regimen;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getPasw() {
        return $this->pasw;
    }

    function getEstacion() {
        return $this->estacion;
    }

    function getFactor() {
        return $this->factor;
    }

    function getNumestacion() {
        return $this->numestacion;
    }

    function getClavepemex() {
        return $this->clavepemex;
    }

    function getSegundos() {
        return $this->segundos;
    }

    function getLastpein() {
        return $this->lastpein;
    }

    function getFolenvios() {
        return $this->folenvios;
    }

    function getClavegpg() {
        return $this->clavegpg;
    }

    function getFolioenvios() {
        return $this->folioenvios;
    }

    function getSerie() {
        return $this->serie;
    }

    function getFacturacion() {
        return $this->facturacion;
    }

    function getFacclavesat() {
        return $this->facclavesat;
    }

    function getZonahoraria() {
        return $this->zonahoraria;
    }

    function getMaster() {
        return $this->master;
    }

    function getRfc_proveedor_sw() {
        return $this->rfc_proveedor_sw;
    }

    function getClave_envios_xml() {
        return $this->clave_envios_xml;
    }

    function getActiva_envio_xml() {
        return $this->activa_envio_xml;
    }

    function getDireccionexp() {
        return $this->direccionexp;
    }

    function getNumeroextexp() {
        return $this->numeroextexp;
    }

    function getNumerointexp() {
        return $this->numerointexp;
    }

    function getColoniaexp() {
        return $this->coloniaexp;
    }

    function getCiudadexp() {
        return $this->ciudadexp;
    }

    function getEstadoexp() {
        return $this->estadoexp;
    }

    function getCodigoexp() {
        return $this->codigoexp;
    }

    function getVentastarxticket() {
        return $this->ventastarxticket;
    }

    function getDiaslimiteticket() {
        return $this->diaslimiteticket;
    }

    function getSesion() {
        return $this->sesion;
    }

    function getMd5() {
        return $this->md5;
    }

    function getFirmwaremd5() {
        return $this->firmwaremd5;
    }

    function getClavesat() {
        return $this->clavesat;
    }

    function getRfc_proveedor_ws() {
        return $this->rfc_proveedor_ws;
    }

    function getPesosporpunto() {
        return $this->pesosporpunto;
    }

    function getVigencia() {
        return $this->vigencia;
    }

    function getClaveterminal() {
        return $this->claveterminal;
    }

    function getClave_regimen() {
        return $this->clave_regimen;
    }

    function getVersion_cfdi() {
        return $this->version_cfdi;
    }

    function setCia($cia) {
        $this->cia = $cia;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setNumeroext($numeroext) {
        $this->numeroext = $numeroext;
    }

    function setNumeroint($numeroint) {
        $this->numeroint = $numeroint;
    }

    function setColonia($colonia) {
        $this->colonia = $colonia;
    }

    function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setDesgloce($desgloce) {
        $this->desgloce = $desgloce;
    }

    function setIva($iva) {
        $this->iva = $iva;
    }

    function setRfc($rfc) {
        $this->rfc = $rfc;
    }

    function setRegimen($regimen) {
        $this->regimen = $regimen;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setPasw($pasw) {
        $this->pasw = $pasw;
    }

    function setEstacion($estacion) {
        $this->estacion = $estacion;
    }

    function setFactor($factor) {
        $this->factor = $factor;
    }

    function setNumestacion($numestacion) {
        $this->numestacion = $numestacion;
    }

    function setClavepemex($clavepemex) {
        $this->clavepemex = $clavepemex;
    }

    function setSegundos($segundos) {
        $this->segundos = $segundos;
    }

    function setLastpein($lastpein) {
        $this->lastpein = $lastpein;
    }

    function setFolenvios($folenvios) {
        $this->folenvios = $folenvios;
    }

    function setClavegpg($clavegpg) {
        $this->clavegpg = $clavegpg;
    }

    function setFolioenvios($folioenvios) {
        $this->folioenvios = $folioenvios;
    }

    function setSerie($serie) {
        $this->serie = $serie;
    }

    function setFacturacion($facturacion) {
        $this->facturacion = $facturacion;
    }

    function setFacclavesat($facclavesat) {
        $this->facclavesat = $facclavesat;
    }

    function setZonahoraria($zonahoraria) {
        $this->zonahoraria = $zonahoraria;
    }

    function setMaster($master) {
        $this->master = $master;
    }

    function setRfc_proveedor_sw($rfc_proveedor_sw) {
        $this->rfc_proveedor_sw = $rfc_proveedor_sw;
    }

    function setClave_envios_xml($clave_envios_xml) {
        $this->clave_envios_xml = $clave_envios_xml;
    }

    function setActiva_envio_xml($activa_envio_xml) {
        $this->activa_envio_xml = $activa_envio_xml;
    }

    function setDireccionexp($direccionexp) {
        $this->direccionexp = $direccionexp;
    }

    function setNumeroextexp($numeroextexp) {
        $this->numeroextexp = $numeroextexp;
    }

    function setNumerointexp($numerointexp) {
        $this->numerointexp = $numerointexp;
    }

    function setColoniaexp($coloniaexp) {
        $this->coloniaexp = $coloniaexp;
    }

    function setCiudadexp($ciudadexp) {
        $this->ciudadexp = $ciudadexp;
    }

    function setEstadoexp($estadoexp) {
        $this->estadoexp = $estadoexp;
    }

    function setCodigoexp($codigoexp) {
        $this->codigoexp = $codigoexp;
    }

    function setVentastarxticket($ventastarxticket) {
        $this->ventastarxticket = $ventastarxticket;
    }

    function setDiaslimiteticket($diaslimiteticket) {
        $this->diaslimiteticket = $diaslimiteticket;
    }

    function setSesion($sesion) {
        $this->sesion = $sesion;
    }

    function setMd5($md5) {
        $this->md5 = $md5;
    }

    function setFirmwaremd5($firmwaremd5) {
        $this->firmwaremd5 = $firmwaremd5;
    }

    function setClavesat($clavesat) {
        $this->clavesat = $clavesat;
    }

    function setRfc_proveedor_ws($rfc_proveedor_ws) {
        $this->rfc_proveedor_ws = $rfc_proveedor_ws;
    }

    function setPesosporpunto($pesosporpunto) {
        $this->pesosporpunto = $pesosporpunto;
    }

    function setVigencia($vigencia) {
        $this->vigencia = $vigencia;
    }

    function setClaveterminal($claveterminal) {
        $this->claveterminal = $claveterminal;
    }

    function setClave_regimen($clave_regimen) {
        $this->clave_regimen = $clave_regimen;
    }

    function setVersion_cfdi($version_cfdi) {
        $this->version_cfdi = $version_cfdi;
    }
    
    public function __toString() {
        return "CiaVO={cia=".$this->cia
                    . ", direccion=".$this->direccion
                    . ", numeroext=".$this->numeroext
                    . ", numeroint=".$this->numeroint
                    . ", colonia=".$this->colonia
                    . ", ciudad=".$this->ciudad
                    . ", estado=".$this->estado
                    . ", telefono=".$this->telefono
                    . ", desgloce=".$this->desgloce
                    . ", iva=".$this->iva
                    . ", rfc=".$this->rfc
                    . ", regimen=".$this->regimen
                    . ", codigo=".$this->codigo
                    . ", pasw=".$this->pasw
                    . ", estacion=".$this->estacion
                    . ", factor=".$this->factor
                    . ", numestacion=".$this->numestacion
                    . ", clavepemex=".$this->clavepemex
                    . ", segundos=".$this->segundos
                    . ", lastpein=".$this->lastpein
                    . ", folioenvios=".$this->folenvios
                    . ", clavegpg=".$this->clavegpg
                    . ", folioenvios=".$this->folioenvios
                    . ", serie=".$this->serie
                    . ", facturacion=".$this->facturacion
                    . ", facclavesat=".$this->facclavesat
                    . ", zonahoraria=".$this->zonahoraria
                    . ", master=".$this->master
                    . ", rfc_proveedor_sw=".$this->rfc_proveedor_sw
                    . ", clave_envios_xml=".$this->clave_envios_xml
                    . ", activa_envio_xml=".$this->activa_envio_xml
                    . ", direccionexp=".$this->direccionexp
                    . ", numeroextexp=".$this->numeroextexp
                    . ", numerointexp=".$this->numerointexp
                    . ", coloniaexp=".$this->coloniaexp
                    . ", ciudadexp=".$this->ciudadexp
                    . ", estadoexp=".$this->estadoexp
                    . ", codigoexp=".$this->codigoexp
                    . ", ventastarvticket=".$this->ventastarxticket
                    . ", diaslimiteticket=".$this->diaslimiteticket
                    . ", sesion=".$this->sesion
                    . ", md5=".$this->md5
                    . ", firmwaremd5=".$this->firmwaremd5
                    . ", clavesat=".$this->clavesat
                    . ", rfc_proveedor_ws=".$this->rfc_proveedor_ws
                    . ", pesosoporpunto=".$this->pesosporpunto
                    . ", vigencia=".$this->vigencia
                    . ", claveterminal=".$this->claveterminal
                    . ", clave_regimen=".$this->clave_regimen
                    . ", version_cfdi=".$this->version_cfdi."}";
    }
}

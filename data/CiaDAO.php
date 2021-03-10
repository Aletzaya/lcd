<?php

/*
 * CiaDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

include_once ('mysqlUtils.php');
include_once ('CiaVO.php');

class CiaDAO {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function __destruct() {
        $this->conn->close();
    }

    /*
     * @return CiaVO
     */
    private function parseRS($rs) {
        $cia = new CiaVO();
        $cia->setCia($rs['nombre']);
        $cia->setDireccion($rs['direccion']);
        $cia->setNumeroext($rs['numeroext']);
        $cia->setNumeroint($rs['numeroint']);
        $cia->setColonia($rs['colonia']);
        $cia->setCiudad($rs['ciudad']);
        $cia->setEstado($rs['estado']);
        $cia->setTelefono($rs['telefono']);
        //$cia->setDesgloce($rs['desgloce']);
        $cia->setIva($rs['iva']);
        $cia->setRfc(trim($rs['rfc']));
        //$cia->setRegimen($rs['regimen']);
        $cia->setCodigo($rs['codigo']);
        //$cia->setPasw($rs['pasw']);
        //$cia->setEstacion($rs['estacion']);
        //$cia->setFactor($rs['factor']);
        //$cia->setNumestacion($rs['numestacion']);
        //$cia->setClavepemex($rs['clavepemex']);
        //$cia->setSegundos($rs['segundos']);
        //$cia->setLastpein($rs['lastpein']);
        //$cia->setFolenvios($rs['folenvios']);
        //$cia->setClavegpg($rs['clavegpg']);
        //$cia->setFolioenvios($rs['folioenvios']);
        //$cia->setSerie($rs['serie']);
        //$cia->setFacturacion($rs['facturacion']);
        //$cia->setFacclavesat($rs['facclavesat']);
        //$cia->setZonahoraria($rs['zonahoraria']);
        //$cia->setMaster($rs['master']);
        //$cia->setRfc_proveedor_sw($rs['rfc_proveedor_sw']);
        //$cia->setClave_envios_xml($rs['clave_envios_xml']);
        //$cia->setActiva_envio_xml($rs['activa_envio_xml']);
        //$cia->setDireccionexp($rs['direccionexp']);
        //$cia->setNumeroextexp($rs['numeroextexp']);
        //$cia->setNumerointexp($rs['numerointexp']);
        //$cia->setColoniaexp($rs['coloniaexp']);
        //$cia->setCiudadexp($rs['ciudadexp']);
        //$cia->setEstadoexp($rs['estadoexp']);
        //$cia->setCodigoexp($rs['codigoexp']);
        //$cia->setVentastarxticket($rs['ventastarxticket']);
        //$cia->setDiaslimiteticket($rs['diaslimiteticket']);
        //$cia->setSesion($rs['sesion']);
        //$cia->setMd5($rs['md5']);
        //$cia->setFirmwaremd5($rs['firmwaremd5']);
        //$cia->setClavesat($rs['clavesat']);
        //$cia->setRfc_proveedor_ws($rs['rfc_proveedor_ws']);
        //$cia->setPesosporpunto($rs['pesosporpunto']);
        //$cia->setVigencia($rs['vigencia']);
        //$cia->setClaveterminal($rs['claveterminal']);
        //$cia->setClave_regimen($rs['clave_regimen']);
        //$cia->setVersion_cfdi($rs['version_cfdi']);
        return $cia;
    }
    
    /*
     * @return CiaVO
     */
    public function retrieveFields($fields) {
        $cia = new CiaVO();
        $sql = "SELECT ".$fields." FROM cia";
        
        if (($query = $this->conn->query($sql)) && ($rs = $query->fetch_assoc())) {
            $cia = $this->parseRS($rs);
        }
        return $cia;
    }
}

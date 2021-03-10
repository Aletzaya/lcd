<?php

/*
 * FcDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

include_once ('mysqlUtils.php');
include_once ('FcVO.php');

class FcDAO {
    private $conn;
    
    public function __construct() {
        $this->conn=getConnection();
    }

    
    public function __destruct() {
        $this->conn->close();
    }

    /*
     * @return FcVO
     */
    public function retrieve($id) {
        $fc = new FcVO();
        $sql = "SELECT * FROM fc WHERE id = " . $id;
        error_log($sql);
        if (($query = $this->conn->query($sql)) && ($rs = $query->fetch_assoc())) {
            $fc->setId($rs['id']);
            $fc->setSerie($rs['serie']);
            $fc->setFecha($rs['fecha']);
            $fc->setCliente($rs['cliente']);
            $fc->setCantidad($rs['cantidad']);
            $fc->setImporte($rs['importe']);
            $fc->setIva($rs['iva']);
            //$fc->setIeps($rs['ieps']);
            $fc->setStatus($rs['status']);
            $fc->setTotal($rs['total']);
            $fc->setUuid($rs['uuid']);
            //$fc->setTicket($rs['ticket']);
            $fc->setObservaciones($rs['observaciones']);
            $fc->setUsr($rs['usr']);
            $fc->setOrigen($rs['origen']);
            $fc->setStCancelacion($rs['stCancelacion']);
            $fc->setRelacioncfdi($rs['relacioncfdi']);
            $fc->setTiporelacion($rs['tiporelacion']);
            $fc->setUsocfdi($rs['usocfdi']);
        }
        return $fc;
    }
    
    /*
     * @return FcVO
     */
    public function create($cliente, $origen) {
        $sql = "INSERT INTO fc (serie, fecha, cliente, origen) SELECT serie, now(), ?, ? FROM cia";
        if (($ps=$this->conn->prepare($sql))) {
            $ps->bind_param("ss", 
                    $cliente,
                    $origen);
             $id = $ps->execute() ? $ps->insert_id : -1;
            $ps->close();
        }
        return $id;
    }

    public function setObservaciones($id, $observaciones) {
        $sql = "UPDATE fc SET observaciones = '".$observaciones."' WHERE id=".$id;
        if ($this->conn->query($sql)) {
            return true;
        }

        if (mysqli_errno($this->conn)) {
            throw new Exception(mysqli_error($this->conn));
        }
        return false;
    }
    public function setUsocfdi($id, $usocfdi) {
        $sql = "UPDATE fc SET usocfdi = '".$usocfdi."' WHERE id=".$id;
        if ($this->conn->query($sql)) {
            return true;
        }

        if (mysqli_errno($this->conn)) {
            throw new Exception(mysqli_error($this->conn));
        }
        return false;
    }

    public function setRelacion($id, $relacion, $tiporelacion) {
        $sql = "UPDATE fc SET relacioncfdi = '".$relacion."', tiporelacion = '".$tiporelacion."' WHERE id=".$id;

        if ($this->conn->query($sql)) {
            return true;
        }

        if (mysqli_errno($this->conn)) {
            throw new Exception(mysqli_error($this->conn));
        }
        return false;
    }

}

<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr    = $check['uname'];
  $link   = conectarse();
  $Suc    = $_COOKIE['TEAM'];        //Sucursal 

  $busca  = $_REQUEST[busca];
  $hospi  = $_REQUEST[Hospi];
  $ref    = $_REQUEST[Concept];
  $modifica    = $_REQUEST[modifica];
  $Tabla  = "dpag_ref";
  $Titulo = "Detalle de pagos";
  $Fecha  = date('Y-m-d H:m:s'); 
  $error="Sin error";
  
  if ($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Actualizar) {        //Para agregar uno nuevo
      
    if ($_REQUEST[Monto] <> "") {
        
        if ($hospi == 'Si' AND $_REQUEST[Orden_h] != '') {

            if ($busca == 'NUEVO') {

                $cSql = "INSERT INTO $Tabla (id_ref,orden_h,fecha,observaciones,monto,tipopago,fechapago,recibe,concept,hospi,autoriza,usr,suc) "
                        . "VALUES ('$_REQUEST[Referencia]','$_REQUEST[Orden_h]','$Fecha','$_REQUEST[Observaciones]','$_REQUEST[Monto]','$_REQUEST[Tipopago]',"
                        . "'$_REQUEST[Fechapago]','$_REQUEST[Recibe]','$_REQUEST[Concept]','$_REQUEST[Hospi]','$_REQUEST[Autoriza]','$Usr','$Suc')";

                if (!mysql_query($cSql)) {
                    $Archivo = 'CLI';
                    die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
                }

                $busca = mysql_insert_id();
                
            } else {
                 $modifica    =  $modifica + 1 ;

                $cSql = "UPDATE $Tabla SET id_ref = '$_REQUEST[Referencia]',orden_h='$_REQUEST[Orden_h]',fecha = '$Fecha',observaciones = '$_REQUEST[Observaciones]',"
                        . "monto = '$_REQUEST[Monto]', tipopago='$_REQUEST[Tipopago]',fechapago='$_REQUEST[Fechapago]',recibe='$_REQUEST[Recibe]',"
                        . "concept = '$_REQUEST[Concept]',hospi='$_REQUEST[Hospi]',autoriza='$_REQUEST[Autoriza]',usr='$Usr',suc='$Suc',modifica='$modifica' WHERE id='$busca' limit 1";

                if (!mysql_query($cSql)) {
                    $Archivo = 'CLI';
                    die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
                }
                
            }
            
            header("Location: ctpagos.php?busca=ini");
        } elseif ($hospi == 'No') {

            if ($busca == 'NUEVO') {

                $cSql = "INSERT INTO $Tabla (id_ref,orden_h,fecha,observaciones,monto,tipopago,fechapago,recibe,concept,hospi,autoriza,usr,suc) "
                        . "VALUES ('$_REQUEST[Referencia]','$_REQUEST[Orden_h]','$Fecha','$_REQUEST[Observaciones]','$_REQUEST[Monto]','$_REQUEST[Tipopago]',"
                        . "'$_REQUEST[Fechapago]','$_REQUEST[Recibe]','$_REQUEST[Concept]','$_REQUEST[Hospi]','$_REQUEST[Autoriza]','$Usr','$Suc')";

                if (!mysql_query($cSql)) {
                    $Archivo = 'CLI';
                    die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
                }

                $busca = mysql_insert_id();

            } else {

                 $modifica    =  $modifica + 1 ;

                $cSql = "UPDATE $Tabla SET id_ref = '$_REQUEST[Referencia]',orden_h='$_REQUEST[Orden_h]',fecha = '$Fecha',observaciones = '$_REQUEST[Observaciones]',"
                        . "monto = '$_REQUEST[Monto]', tipopago='$_REQUEST[Tipopago]',fechapago='$_REQUEST[Fechapago]',recibe='$_REQUEST[Recibe]',"
                        . "concept = '$_REQUEST[Concept]',hospi='$_REQUEST[Hospi]',autoriza='$_REQUEST[Autoriza]',usr='$Usr',suc='$Suc',modifica='$modifica' WHERE id='$busca' limit 1";
                //echo $cSql;
                if (!mysql_query($cSql)) {
                    $Archivo = 'CLI';
                    die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
                }
            }
            header("Location: ctpagos.php?busca=ini");
        } else {
            $error = "error";
        }
    } else {
        $error = "error";
    }
    
} elseif ($_REQUEST[Boton] == Cancelar) {

    header("Location: ctpagos.php?busca=ini");
}

if ($_REQUEST[Boton] == "Cerrar orden") {
    if ($_REQUEST[Referencia] <> "" AND $_REQUEST[Recibe] <> "" AND $_REQUEST[Autoriza] <> "" AND $_REQUEST[Tipopago] <> "" AND $_REQUEST[Monto] <> "") {
        $cSql = "UPDATE $Tabla SET cerrada='1' WHERE id='$busca' limit 1";
        
        if (!mysql_query($cSql)) {
            $Archivo = 'CLI';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
    header("Location: ctpagos.php");   
        
    }else{
        $error="error";
        //echo "entroooooooooooooo";
    }
    //echo "pasa xaqui";
    
}

require ("config.php");

 $sql = "SELECT dpag_ref.id,dpag_ref.concept,dpag_ref.autoriza,dpag_ref.hospi,cptpagod.referencia,cptpagod.id idref,dpag_ref.modifica, "
        . "cpagos.id idpagos,cpagos.clave,cpagos.concepto,dpag_ref.fecha,dpag_ref.observaciones,dpag_ref.monto,dpag_ref.orden_h,"
        . "dpag_ref.tipopago,dpag_ref.cerrada,dpag_ref.fechapago,dpag_ref.recibe,dpag_ref.id_ref FROM dpag_ref "
        . "LEFT JOIN cptpagod ON dpag_ref.id_ref=cptpagod.id LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id "
        . "WHERE dpag_ref.id='$busca' ORDER BY id";

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onLoad="cFocus1()">

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php headymenu($Titulo,0); 
//echo $sql;
$Cpoa=  mysql_query($sql);
$Cpo = mysql_fetch_array($Cpoa);


?>

<table width="100%" border="0">
    <?php
    echo "<tr>";
    echo "<td><div align='center'>";
    echo "<form name='form1' method='get' onSubmit='return Completo();'>";
  
        cInput("Id: ", "Text", "10", "busca", "right", $busca, "5", 1, 1, '');

        if ($Cpo[fechapago] <> "") {
            cInput("Fecha del pago: ", "Text", "10", "Fechapago", "right", $Cpo[fechapago], "10", 1, 0, '');
        } elseif ($error == "error") {
            cInput("Fecha del pago: ", "Text", "10", "Fechapago", "right", $_REQUEST[Fechapago], "10", 1, 0, '');
        } else {
            cInput("Fecha del pago: ", "Text", "10", "Fechapago", "right", date('Y-m-d'), "10", 1, 0, '');
        }

        echo " <tr height='25' class='content_txt'><td align='right' >$Gfont Tipo de pago : &nbsp; </font></td><td><select name='Referencia' class='content_txt'>";
        $CliA = mysql_query("SELECT id,referencia,cuenta FROM cptpagod ORDER BY id");
        while ($Cli = mysql_fetch_array($CliA)) {
            echo "<option value='$Cli[id]'>$Cli[referencia]| " . $Cli[cuenta] . "</option>";
        }
        if ($Cpo[referencia] != '') {
            echo "<option selected value='$Cpo[id_ref]'>$Cpo[referencia]</option>";
        }elseif($error=="error"){
            $cSql = "SELECT * FROM cptpagod WHERE id='$_REQUEST[Referencia]'";
            $CliAe = mysql_query($cSql);
            $Clie = mysql_fetch_array($CliAe);
            echo "<option selected value='$Clie[id]'>$Clie[referencia]| " . $Clie[cuenta] . "</option>";
        } else {
            echo "<option selected value=''> Seleccionar cuenta de pago </option>";
        }
        echo "</select></td></tr>";

        echo " <tr height='25' class='content_txt'><td align='right' >$Gfont Referencia a laboratorio: &nbsp; </font></td><td><select name='Hospi' class='content_txt'>";
        echo "<option value='Si'> Si </option>";
        echo "<option value='No'> No </option>";
        if ($Cpo[hospi] <> '') {
            echo "<option selected value='$Cpo[hospi]'>$Cpo[hospi]</option>";
        } elseif ($error == "error") {
            echo "<option selected value='$_REQUEST[Hospi]'> $_REQUEST[Hospi] </option>";
        } else {
            echo "<option selected value='Nada'> Si/No </option>";
        }
        echo "</select></td></tr>";

        if ($Cpo[orden_h] <> "") {
            cInput("No. de orden del paciente: ", "Text", "5", "Orden_h", "right", $Cpo[orden_h], "7", 1, 0, '');
        } elseif($error == "error") {
            cInput("No. de orden del paciente: ", "Text", "5", "Orden_h", "right", $_REQUEST[Orden_h], "7", 1, 0, '');
            
        }else{
            cInput("No. de orden del paciente: ", "Text", "5", "Orden_h", "right","", "7", 1, 0, '');
        }

        if ($Cpo[concept] <> "") {
            cInput("Concepto de laboratorio : ", "Text", "40", "Concept", "right", $Cpo[concept], "60", 1, 0, '');
        } elseif($error == "error") {
            cInput("Concepto de laboratorio : ", "Text", "40", "Concept", "right", $_REQUEST[Concept], "60", 1, 0, '');
        }else{
            cInput("Concepto de laboratorio : ", "Text", "40", "Concept", "right", "", "60", 1, 0, '');
        }
        
        if ($error != "error") {
            cInput("Quien recibe: ", "Text", "35", "Recibe", "right", $Cpo[recibe], "60", 1, 0, '');
        } else {
            cInput("Quien recibe: ", "Text", "35", "Recibe", "right", $_REQUEST[Recibe], "60", 1, 0, '');
        }

        if ($Cpo[autoriza] <> "") {
            cInput("Quien autoriza : ", "Text", "35", "Autoriza", "right", $Cpo[autoriza], "60", 1, 0, '');
        }elseif($error == "error") {
            cInput("Quien autoriza : ", "Text", "40", "Autoriza", "right", $_REQUEST[Autoriza], "60", 1, 0, '');
        }else{
            cInput("Quien autoriza : ", "Text", "35", "Autoriza", "right","", "60", 1, 0, '');
        }
        
        echo " <tr height='25' class='content_txt'><td align='right' >$Gfont Forma de pago: &nbsp; </font></td><td><select name='Tipopago' class='content_txt'>";
        $CliA = mysql_query("SELECT * FROM cpagos ORDER BY id");
        while ($Cli = mysql_fetch_array($CliA)) {
            echo "<option value='$Cli[id]'>$Cli[concepto]| " . $Cli[clave] . "</option>";
        }
        if ($Cpo[tipopago] <> 0) {
            echo "<option selected value='$Cpo[idpagos]'>$Cpo[concepto]| $Cpo[clave]</option>";
        } elseif ($error == "error") {
            $cSql = "SELECT * FROM cpagos WHERE id='$_REQUEST[Tipopago]'";
            $CliAe = mysql_query($cSql);
            $Clie = mysql_fetch_array($CliAe);
            echo "<option selected value='$Clie[id]'>$Clie[concepto]| $Clie[clave]</option>";
        } else {
            echo "<option selected value='$Referencia'> Seleccionar forma de pago </option>";
        }
        echo "</select></td></tr>";
        //echo $cSql;
        if ($Cpo[monto] <> "") {
            cInput("Importe : $", "Text", "4", "Monto", "right", $Cpo[monto], "40", 1, 0, '');
        } elseif($error == "error") {
            cInput("Importe : $", "Text", "4", "Monto", "right", $_REQUEST[Monto], "40", 1, 0, '');
        }else{
            cInput("Importe : $", "Text", "4", "Monto", "right", "", "40", 1, 0, '');
        }
        
        echo "<tr class='content_txt'><td align='right'>$Gfont Observaciones:&nbsp; </font></td><td>";
        echo "<TEXTAREA NAME='Observaciones' style='background-color:$InputCol;color:#444444;' cols='50' rows='4' >$Cpo[observaciones]</TEXTAREA>";
        if ($error == "error") {
            echo"<tr><td align='center' colspan='2'><p style='color:#FF0000';>FAVOR DE AGREGAR TODOS LOS CAMPOS FALTANTES</p></td></tr>";
        }
        
        if ($Cpo[cerrada] == "0") {
            echo"<tr><td align='center' colspan='2'><p style='color:#FF0000';>*** ORDEN SIN CERRAR, NO SE RECIBIRA ***</p></td></tr>";
        }
        
    
    echo "</div></td></tr>";
    cTableCie();
    if($busca=='NUEVO'){
        echo Botones();
    }else if($Cpo[cerrada] == "1"){
        
    }else{
        $modifica = $Cpo[modifica];
        echo "<div align='center'><input type='hidden' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='busca' value='$busca'><input type='hidden' name='modifica' value='$modifica'> &nbsp;  &nbsp;  &nbsp; ";
        echo "<input type='submit' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='Boton' value='Cerrar orden'> &nbsp;  &nbsp;  &nbsp; ";
        echo "<input type='submit' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='Boton' value='Actualizar'><div>";
    }
    
    echo "<table width='100%'><tr><td>";
    echo "<br><div align='light'><a href='ctpagos.php?busca=ini'><img src='lib/volver.png' border='0'></a><div>";
    echo "</td></tr></table>";
    mysql_close();
    ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
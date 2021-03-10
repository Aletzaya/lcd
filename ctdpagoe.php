<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr        = $check['uname'];
  $link       = conectarse();
  $busca      = $_REQUEST[busca];
 
  $Tabla      = "cptpagod";

  $Titulo     = "Detalle de pagos";
    
if ($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar) {        //Para agregar uno nuevo
    if ($_REQUEST[Pago] <> 0) {
        if ($busca == 'NUEVO') {
            if ($_REQUEST[Cuenta] != "") {

                $cSql = "INSERT INTO $Tabla (referencia,cuenta,id_nvo) VALUES ('$_REQUEST[Referencia]','$_REQUEST[Cuenta]',$_REQUEST[Pago])";

                if (!mysql_query($cSql)) {
                    $Archivo = 'CLI';
                    die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
                }

                $busca = mysql_insert_id();

                $cSql = '';
            }
        } else {

            $cSql = "UPDATE $Tabla SET referencia = '$_REQUEST[Referencia]',cuenta = '$_REQUEST[Cuenta]',id_nvo = '$_REQUEST[Pago]' WHERE id='$busca' limit 1";

            if (!mysql_query($cSql)) {
                $Archivo = 'CLI';
                die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
            }
        }
        //echo $cSql;
        header("Location: ctdpago.php?busca=ini");
    }
} elseif ($_REQUEST[Boton] == Cancelar) {

    header("Location: ctdpago.php?busca=ini");
}

 require ("config.php");

 $sql = "SELECT * FROM cptpagod WHERE id='$busca'"
 

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onLoad="cFocus1()">

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php headymenu($Titulo,0); 
$Cpoa=  mysql_query($sql);
$Cpo = mysql_fetch_array($Cpoa);
?>

<table width="100%" border="0">
    <?php
    echo "<tr>";
    echo "<td align='left'>";
    echo "<form name='form1' method='get' onSubmit='return Completo();'>";
    
        cInput("Id: ","Text","10","Curp","right",$busca,"40",false,true,'');

        cInput("Referencia: ","Text","35","Referencia","right",$Cpo[referencia],"40",false,false,'');
        
	cInput("No. Cuenta: ","Text","35","Cuenta","right",$Cpo[cuenta],"40",false,false,'');	
        
        echo " <tr height='25' class='content_txt'><td align='right' ><font color='#7a7a9e' size='3'>Cuenta: &nbsp; <font></td><td><select name='Pago' class='pg'>";
            $CliA = mysql_query("SELECT id,pago FROM cptpago ORDER BY id");
        while ($Cli = mysql_fetch_array($CliA)) {
            echo "<option value='$Cli[id]'> &nbsp; $Cli[pago] </option>";                
            }
        if($Cpo[pago] <>''){
            echo "<option selected value='$Cpo[idnvo]'>$Cpo[pago]</option>";                
        }   else{
            echo "<option selected value='$Referencia'> &nbsp; Seleccionar tipo de pago </option>";
        }
        echo "</select></td></tr>";
        cTableCie();
        echo Botones();
        echo "<a href='ctdpago.php?busca=ini'><img src='lib/regresa.jpg' border='0'></a>";
        mysql_close();
    ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
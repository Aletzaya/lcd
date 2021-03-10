<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr        = $check['uname'];
  $link       = conectarse();
  $busca      = $_REQUEST[busca];
 
  $Tabla      = "cptpago";

  $Titulo     = "Detalle de pagos";
    
if ($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar) {        //Para agregar uno nuevo

    if ($busca == 'NUEVO') {

        $cSql = "INSERT INTO $Tabla (pago) VALUES ('$_REQUEST[Pago]')";

        if (!mysql_query($cSql)) {
            $Archivo = 'CLI';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }

        $busca = mysql_insert_id();

        $cSql = '';
    } else {

        $cSql = "UPDATE $Tabla SET pago='$_REQUEST[Pago]' WHERE id='$busca' limit 1";
        echo $cSql;
        if (!mysql_query($cSql)) {
            $Archivo = 'CLI';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
    }
    header("Location: ctpago.php?busca=ini");
} elseif ($_REQUEST[Boton] == Cancelar) {

    header("Location: ctpago.php?busca=ini");
}

 require ("config.php");

 $sql = "SELECT pago FROM cptpago WHERE id='$busca'"
 

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

             cInput("Pago: ","Text","35","Pago","right",$Cpo[pago],"40",false,false,'');
			  
             cTableCie();

                echo Botones();
            echo "<a href='ctpago.php?busca=ini'><img src='lib/regresa.jpg' border='0'></a>";

                mysql_close();
              ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
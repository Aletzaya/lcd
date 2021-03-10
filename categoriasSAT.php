<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
include_once ("CFDIComboBoxes.php");

require("lib/lib.php");

$link = conectarse();

$Usr      = $_COOKIE[USERNAME];
$Nivel    = $_COOKIE[LEVEL];  
$Medico   = $_COOKIE[GRUPO];
//echo "Cia $Cia Nivel $Nivel Usr $Usr Medico $Medico";

$queryParameters = array();
foreach ($_REQUEST as $key => $value) {
    $queryParameters[$key] = $value;
}

$tipo           = $queryParameters['tipo'];
$division       = $queryParameters['division'];
$grupo          = $queryParameters['grupo'];
$clase          = $queryParameters['clase'];
$cbConcepto     = $queryParameters['quicksearch']!='' ? trim(substr($queryParameters['quicksearch'], 0, strpos($queryParameters['quicksearch'], "|"))) : $queryParameters['claveps'];

$Msj    = $_REQUEST[Msj];
$op     = $_REQUEST[op];
$busca  = $_REQUEST[busca];
$Return = "listae.php?busca=$busca";
$Titulo = "Catálogo de claves de Producto/Servicio SAT CFDI 3.3";

$Usr = $_COOKIE[USERNAME];

$ct_ps_q = mysql_query("
    SELECT 
            CT.clave ccategoria,
            CT.clave_PADRE,
            C.nombre, 
            C.clave,
            CT.descripcion, 
            CT.tipo,
            CASE
                WHEN CT.clave = CONCAT(SUBSTR(C.clave, 1, 2), '000000') THEN 'division'
                WHEN CT.clave = CONCAT(SUBSTR(C.clave, 1, 4), '0000') THEN 'grupo'
                WHEN CT.clave = CONCAT(SUBSTR(C.clave, 1, 6), '00') THEN 'clase'
                WHEN CT.clave = C.clave  THEN 'concepto'
            END categoria
        FROM cfdi33_c_conceptos C
        JOIN cfdi33_c_categorias CT 
            ON CT.clave = CONCAT(SUBSTR(C.clave, 1, 2), '000000') 
            OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 4), '0000')
            OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 6), '00')
        WHERE C.clave = '" . $cbConcepto . "'
        ORDER BY CT.clave
");

$ctipo = "";

$cdivision = "";
$cgrupo = "";
$cclase = "";

while (($ct_rs_rs = mysql_fetch_array($ct_ps_q))) {
    if ($ct_rs_rs['categoria']=='division') {
        $cdivision = $ct_rs_rs['ccategoria'];
    }
    if ($ct_rs_rs['categoria']=='grupo') {
        $cgrupo = $ct_rs_rs['ccategoria'];
    }
    if ($ct_rs_rs['categoria']=='clase') {
        $cclase = $ct_rs_rs['ccategoria'];
    }

    $ctipo = $ct_rs_rs['tipo'];
}

if (substr($_REQUEST[Boton], 0, 7) == "Agregar") {        //Para agregar uno nuevo
    
    $cSql = "UPDATE cfdi33_c_conceptos SET status = 1 WHERE clave = '" . $_REQUEST[NuevaClave] . "'";

    //$cSql = "UPDATE cfdi33_c_conceptos SET status = 1 WHERE clave = '" . $cbConcepto . "'";
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'INV ';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . mysqli_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

    $Msj = "Registro dado de alta";

    header("Location: $Return?Msj=$Msj&busca=$busca&common_claveps=".$_REQUEST[NuevaClave]);
}


#Variables comunes;
$Titulo    = ":: Categorias SAT, modulo de categorias";

require ("config.php");							//Parametros de colores;

//<p align="center"><img src="images/logo1.jpg" width="400" height="200"></p>

?>

<html>
<head>

<title><?php echo $Titulo;?></title>

<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>

</head>

<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="js/jquery.mockjax.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="lib/predictive_search.js"></script>

<link rel="stylesheet" href="lib/predictive_styles.css">

<script>

    $(document).ready(function () {
        $('#tipo').val('<?= $ctipo != '' ? $ctipo : $tipo ?>');
        $('#division').val('<?= $cdivision != '' ? $cdivision : $division ?>');
        $('#grupo').val('<?= $cgrupo != '' ? $cgrupo : $grupo ?>');
        $('#clase').val('<?= $cclase != '' ? $cclase : $clase ?>');
        $('#claveps').val('<?= $cbConcepto != '' ? $cbConcepto : $claveps ?>');

        $('#tipo').change(function () {
            $('#division').val('');
            $('#grupo').val('');
            $('#clase').val('');
            $('#claveps').val('');
            document.form1.submit();
        });
        $('#division').change(function () {
            $('#grupo').val('');
            $('#clase').val('');
            $('#claveps').val('');
            document.form1.submit();
        });
        $('#grupo').change(function () {
            $('#clase').val('');
            $('#claveps').val('');
            document.form1.submit();
        });
        $('#clase').change(function () {
            $('#claveps').val('');
            document.form1.submit();
        });
        $('#claveps').change(function () {
            $('#Boton').val('Agregar Clave ' + $('#claveps').val());
        });

        $('#autocomplete').val('<?= $SCliente ?>').addClass('texto_tablas')
                .attr('size', '145')
                .attr('placeholder', '                                             <-- ------ Favor de seleccionar el concepto ------ -->')
                .click(function () {
                    this.select();
                })
                .activeComboBox(
                        $('[name=\'form1\']'),
                        'SELECT clave as data, CONCAT(clave, \' | \', nombre) value FROM cfdi33_c_conceptos WHERE 1=1',
                        'nombre');

    });
    function load() {
        document.form10.busca.focus();
    }
    
</script>

<?php 

  echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

  headymenu("Menu principal (inicio)",1);
  
  echo "<table border='0' width='100%' align='center' border=0 cellpadding=0 cellspacing=0>";

  echo "<tr><td height='280' align='center'>$Gfont ";

        // echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValCampos2();'>";

  ?>
                <form name='form1' method='get' action="">

                    <input type="hidden" name="busca" id="busca" value="<?=$busca?>"/> 
                    <table style="width: 90%; text-align: center; border: 0px; margin: 5px;">                                            
                        <caption class="content_txt">Herramienta de búsqueda por Categorías de Clave de Producto/Servicio definidas por el SAT para el CFDI versión 3.3</caption>
                        <tr class="content_txt" >
                            <td class="content_txt"> 
                                <b>Búsqueda rápida.</b> <br/>
                                <small>Escriba el texto a buscar y seleccione el más apropiado.</small>
                            </td>
                            <td class="content_txt"  style="text-align: left;"> 
                                <div style="position: relative;">
                                    <input style="font-size: 12px;" type="text" name="quicksearch" id="autocomplete"/>
                                    <?= "<br/>" . $ccategoria?>
                                </div>
                                <div id="autocomplete-suggestions"></div>
                            </td>
                        </tr>
                        <tr class="content_txt" >
                            <td class="content_txt">
                                <b>Producto/Servicio</b>
                            </td>
                            <td class="content_txt"  style="text-align: left;"> 
                                <select style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; font-weight: bold; color: #3E5A8F;" name="tipo" id="tipo">
                                    <option value="">SELECCIONE TIPO</option>
                                    <option value="Producto">Producto</option>
                                    <option value="Servicio">Servicio</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="content_txt" >
                            <td class="content_txt">
                                <b>División</b>
                            </td>
                            <td class="content_txt"  style="text-align: left;"> 
                                <?= ComboboxDivison::generate("division", $ctipo!='' ? $ctipo : $tipo);?>
                            </td>
                        </tr>
                        <tr class="content_txt" >
                            <td class="content_txt">
                                <b>Grupo</b>
                            </td>
                            <td class="content_txt"  style="text-align: left;"> 
                                <?= ComboboxGrupo::generate("grupo", $cdivision!='' ? $cdivision : $division);?>
                            </td>
                        </tr>
                        <tr class="content_txt" >
                            <td class="content_txt">
                                <b>Clase</b>
                            </td>
                            <td class="content_txt"  style="text-align: left;"> 
                                <?= ComboboxClase::generate("clase", $cgrupo!='' ? $cgrupo : $grupo);?>
                            </td>
                        </tr>
                        <tr class="content_txt">
                            <td class="content_txt">
                                <b>Clave de Producto/Servicio</b> <br/>
                                <small>Clave de producto requerida por el SAT.</small>
                            </td>
                            <td class="content_txt"  style="text-align: left;"> 
                                <?= ComboboxProductoServicio::generate("claveps", $cclase!='' ? $cclase : $clase);?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php 
                                
                                    //regresar($Return);
                                    //Lo agrego para hacerlo funcionar
                                    echo "<input type='hidden' name='NuevaClave' value='$cbConcepto'>";
                                
                                ?>
                            </td>
                            <td><input class="numeros_pagina" type="submit" id="Boton" name="Boton" value="Agregar Clave <?=$cbConcepto?>"></td>
                        </tr>
                    </table>

  
  <?php
  
               // echo "</form>";
  
  echo "</td></tr>";
     
  echo "<tr background='lib/prueba.jpg' height='80'>";

  echo "<td>&nbsp;</td>";

  echo "</tr></table>";

?>

<p>&nbsp;</p>


</body>

</html>
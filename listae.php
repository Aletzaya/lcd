<?php

session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
include_once ("CFDIComboBoxes.php");

require("lib/lib.php");

$queryParameters = array();
foreach ($_REQUEST as $key=>$value) {
    $queryParameters[$key] = $value;
}


$Usr        = $check['uname'];

$fechmod    = date("Y-m-d H:i:s");

$link       = conectarse();

$tamPag     = 15;

$pagina     = $_REQUEST[pagina];
$busca      = $_REQUEST[busca];
$op         = $_REQUEST[op];
$Tabla      = "est";

$cumedida       = $_REQUEST['cumedida'];
$common_claveps = $_REQUEST['common_claveps'];

$Titulo     = "Detalle del estudio ($busca)";
  
  
  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo
      
     $cSql = "UPDATE $Tabla SET lt1=$_REQUEST[Lt1],lt2='$_REQUEST[Lt2]',lt3='$_REQUEST[Lt3]',lt4='$_REQUEST[Lt4]',
     lt5='$_REQUEST[Lt5]',lt6='$_REQUEST[Lt6]',lt7='$_REQUEST[Lt7]',lt8='$_REQUEST[Lt8]',lt9='$_REQUEST[Lt9]',
     lt10='$_REQUEST[Lt10]',lt11='$_REQUEST[Lt11]',lt12='$_REQUEST[Lt12]',lt13='$_REQUEST[Lt13]',lt14='$_REQUEST[Lt14]',
     lt15='$_REQUEST[Lt15]',lt16='$_REQUEST[Lt16]',lt17='$_REQUEST[Lt17]',lt18='$_REQUEST[Lt18]',lt19='$_REQUEST[Lt19]'
     ,lt20='$_REQUEST[Lt20]',lt21='$_REQUEST[Lt21]',lt22='$_REQUEST[Lt22]',lt23='$_REQUEST[Lt23]',modify='$Usr',fechmod='$fechmod'
     WHERE estudio='$busca' limit 1";
   
     
    if (!mysql_query($cSql)) {
         die(mysql_error());
     }
   
     $Prc = number_format($_REQUEST[Lt1],"2").", ".number_format($_REQUEST[Lt2],"2").", ".number_format($_REQUEST[Lt3],"2").", ".number_format($_REQUEST[Lt4],"2");

     Btc("Actualizacion de precios ".$Prc,$busca);
     

  }elseif ($_REQUEST[Boton] == "Actualiza/clasificacion") {

    $cSql  = "UPDATE est SET inv_cunidad='$_REQUEST[cumedida]',inv_cproducto='$_REQUEST[common_claveps]' WHERE estudio='$busca' limit 1";
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'INV';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
   // logs('Act(inv)','$Usr','$cProceso');
    
    header("Location: lista.php?Msj=Registro actualizado");
    
}


if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo

     header("Location: lista.php?pagina=$pagina");

}elseif($_REQUEST[Boton] == Cancelar){

     header("Location: lista.php?pagina=$pagina");

}

$cSql = "SELECT estudio,descripcion,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10,lt10,lt11,lt12,lt13,lt14,lt15,lt16,lt17,lt18,lt19,lt20,lt21,lt22,lt23,formato,
         modify,fechmod,inv_cunidad,inv_cproducto 
         FROM $Tabla 
         WHERE estudio='$busca'";


  //echo "$cSql";
  
$CpoA  = mysql_query($cSql);
$Cpo   = mysql_fetch_array($CpoA);
  
$ct_ps_q = mysql_query("SELECT C.nombre, CT.descripcion , CT.tipo
FROM cfdi33_c_conceptos C
JOIN cfdi33_c_categorias CT 
      ON (CT.clave_padre = '0' AND CT.clave = CONCAT(SUBSTR(C.clave, 1, 2), '000000')) 
      OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 4), '0000') 
      OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 6), '00')
WHERE C.clave = '" . $Cpo['inv_cproducto'] . "'
ORDER BY CT.clave
");
 

$lAg   = $busca<>$Cpo[estudio];

echo "<html>";
echo "<head>";
echo "<title>$Titulo</title>";

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>Advanced example</title>
<script language="javascript" type="text/javascript" src="/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen",
		//theme_advanced_buttons1_add_before : "save,newdocument,separator",
		//theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		//theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		content_css : "example_word.css",
	    plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		media_external_list_url : "example_media_list.js",
		file_browser_callback : "fileBrowserCallBack",
		paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : false,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false		
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		alert("Filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);

		// Insert new URL, this would normaly be done in a popup
		win.document.forms[0].elements[field_name].value = "someurl.htm";
	}
</script>
</head>


<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>

<script>

    $(document).ready(function () {
        $('#cumedida').val('<?= $cumedida != '' ? $cumedida : $Cpo['inv_cunidad'] ?>');
        $('#common_claveps').val('<?= $common_claveps != '' ? $common_claveps : $Cpo['inv_cproducto'] ?>');
    });

</script>
    
    
<?php

echo "<body bgcolor='#FFFFFF'>";

headymenu($Titulo,0);

?>
    
<hr noshade style="color:3366FF;height:2px">

<?php

echo "<table width=100% border=0>";
  echo "<tr>";
   echo "<td align='center' width='10%'>";
        echo "<a class='pg' href='lista.php?pagina=$pagina'><img src='lib/regresar.gif' border='0'></a>";
   echo "</td>";
   echo "<td align='center'>";
   
    echo "<form name='form1' method='get' action='listae.php'>";
        
    $rubro      = $queryParameters['Rubro']!='' ? $queryParameters['Rubro'] : ($Cpo['rubro']!='' ? $Cpo['rubro'] : "Aceites");
    $umedida    = $queryParameters['Umedida']!='' ? $queryParameters['Umedida'] : ($Cpo['umedida']!='' ? $Cpo['umedida'] : "Pzas");
    $activo     = $queryParameters['Activo']!='' ? $queryParameters['Activo'] : ($Cpo['activo']!='' ? $Cpo['activo'] : "Si");

    ?>
       
       <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">Estudio: <?php echo $Cpo[estudio]; ?> &nbsp; &nbsp;
       &nbsp; &nbsp; Descripcion: <?php if(!$lAg){echo $Cpo[descripcion];} ?></p>
       <div>Precio 1........:<input type="text" name="Lt1" value ='<?php echo $Cpo[lt1]; ?>' size="5"></div>
       <div>Precio 2........:<input type="text" name="Lt2" value ='<?php echo $Cpo[lt2]; ?>' size="5"></div>
       <div>Precio 3........:<input type="text" name="Lt3" value ='<?php echo $Cpo[lt3]; ?>' size="5"></div>
       <div>Precio 4........:<input type="text" name="Lt4" value ='<?php echo $Cpo[lt4]; ?>' size="5"></div>
       <div>Precio 5........:<input type="text" name="Lt5" value ='<?php echo $Cpo[lt5]; ?>' size="5"></div>
       <div>Precio 6........:<input type="text" name="Lt6" value ='<?php echo $Cpo[lt6]; ?>' size="5"></div>
       <div>Precio 7........:<input type="text" name="Lt7" value ='<?php echo $Cpo[lt7]; ?>' size="5"></div>
       <div>Precio 8........:<input type="text" name="Lt8" value ='<?php echo $Cpo[lt8]; ?>' size="5"></div>
       <div>Precio 9........:<input type="text" name="Lt9" value ='<?php echo $Cpo[lt9]; ?>' size="5"></div>
       <div>Precio 10.......:<input type="text" name="Lt10" value ='<?php echo $Cpo[lt10]; ?>' size="5"></div>
       <div>Precio 11.......:<input type="text" name="Lt11" value ='<?php echo $Cpo[lt11]; ?>' size="5"></div>       
       <div>Precio 12.......:<input type="text" name="Lt12" value ='<?php echo $Cpo[lt12]; ?>' size="5"></div>
       <div>Precio 13.......:<input type="text" name="Lt13" value ='<?php echo $Cpo[lt13]; ?>' size="5"></div>
       <div>Precio 14.......:<input type="text" name="Lt14" value ='<?php echo $Cpo[lt14]; ?>' size="5"></div>
       <div>Precio 15.......:<input type="text" name="Lt15" value ='<?php echo $Cpo[lt15]; ?>' size="5"></div>
       <div>Precio 16.......:<input type="text" name="Lt16" value ='<?php echo $Cpo[lt16]; ?>' size="5"></div>
       <div>Precio 17.......:<input type="text" name="Lt17" value ='<?php echo $Cpo[lt17]; ?>' size="5"></div>
       <div>Precio 18.......:<input type="text" name="Lt18" value ='<?php echo $Cpo[lt18]; ?>' size="5"></div>
       <div>Precio 19.......:<input type="text" name="Lt19" value ='<?php echo $Cpo[lt19]; ?>' size="5"></div>
       <div>Precio 20.......:<input type="text" name="Lt20" value ='<?php echo $Cpo[lt20]; ?>' size="5"></div>
       <div>Precio 21.......:<input type="text" name="Lt21" value ='<?php echo $Cpo[lt21]; ?>' size="5"></div>
       <div>Precio 22.......:<input type="text" name="Lt22" value ='<?php echo $Cpo[lt22]; ?>' size="5"></div>
       <div>Precio 23.......:<input type="text" name="Lt23" value ='<?php echo $Cpo[lt23]; ?>' size="5"></div>

       <?php
       
       echo Botones();
    
       echo "</form>";
       
            echo "<form name='frmclasif' method='post' action='listae.php'>";

            echo "<br><table width='80%' border='1' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

            echo "<tr><td colspan='2' align='left'  class='content_txt'> &nbsp; *** Clasificacion necesario para poder facturar &eacute;ste producto</td></tr>";
            //echo "<tr><td colspan='2' align='center'  bgcolor='$Gbgsubtitulo' class='titulo'>D e t a l l e</td></tr>";     

            echo '<tr class="content_txt">';
            echo '<td class="content_txt" align="right" bgcolor="#E1E1E1">';
            echo 'Unidad de medida: ';
            echo '</td><td class="content_txt">';
            ComboboxUnidades::generate('cumedida');
            echo "</td></tr>";

            echo '<tr class="content_txt">';
            echo '<td class="content_txt" align="right" bgcolor="#E1E1E1">';
            echo 'Clave de Producto/Servicio: ';
            echo '</td><td class="content_txt">';

            ComboboxCommonProductoServicio::generate("common_claveps"); 

            echo "&nbsp; <a href='categoriasSAT.php?busca=$busca'>";
            echo '<img src="lib/desplegar.png" title="en caso de no aparecer la clave del nuevo producto dar click aqui">';                                
            echo "</a></td></tr>";

            echo "<tr><td></td><td align='right'>";
            echo "<input class='InputBoton' type='submit' name='Boton' value='Actualiza/clasificacion'>";
            echo " &nbsp; </td></tr></table>";
            echo "<input type='hidden' name='busca' value='$busca'>";                                
            echo "</form>";

            /*
            echo "<p align='left'><b>Formato de resultados para estudio de tipo texto</strong></font></b></p>";
            //echo "<textarea name='Observaciones' cols='90' rows='4'>$Cpo[observaciones]</textarea>";
            echo "<textarea name='Formato' rows='20' cols='80' style='width: 100%'>";
            echo "$Cpo[formato]";
            echo "</textarea>";
            if($Usr=='nazario' or $Usr=='emilio' or $Usr=='Dolores' or $Usr=='carmen' or $Usr=='fabiola' or $Usr=='Javier'){
               echo Botones();
            }
            echo "$Gfont Usr.ult.mod:</b> $Cpo[modify] &nbsp;&nbsp; <b>Fecha Modif.:</b> $Cpo[fechmod] </p>";
              
            */
          mysql_close();

       ?>
     
  </td>
  </tr>
</table>
<hr noshade style="color:FF0000;height:3px">
</body>
</html>

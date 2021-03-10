<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();

//$RetSelec = $_SESSION[OnToy][4];                                     //Pagina a la que regresa con parametros        
//$Retornar = "<a href=".$_SESSION[OnToy][4]."><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  
 
#Saco los valores de las sessiones los cuales no cambian;
$Gusr      = $_SESSION[Usr][0];
$Gcia      = $_SESSION[Usr][1];
$Gnomcia   = $_SESSION[Usr][2];
$Gnivel    = $_SESSION[Usr][3];        
$Gteam     = $_SESSION[Usr][4];
$Gmenu     = $_SESSION[Usr][5];

#Variables comunes;
$Titulo    = "Edita cotizacion";
$busca     = $_REQUEST[busca];
$op        = $_REQUEST[op];
$Msj       = $_REQUEST[Msj];
$Retornar  = "cotizaciones.php";
//$Retornar = "<a href='cotizaciones.php'><img src='lib/regresa.jpg' height='22'></a>";      //Regresar abort  

$aMes      = array("-","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

#Intruccion a realizar si es que mandan algun proceso
if($_REQUEST[Boton] == Cancelar){

    header("Location: ordenescon.php");
    
}elseif ($_REQUEST[Boton] == 'Actualizar') {
            
    $cSql =  "UPDATE ot SET medico='$_REQUEST[Medico]',receta='$_REQUEST[Receta]',diagmedico='$_REQUEST[Diagmedico]',observaciones='$_REQUEST[Observaciones]',
              fechae='$_REQUEST[Fechae]',horae=''$_REQUEST[Horae]'',servicio='$_REQUEST[Servicio]',descuento='$_REQUEST[Descuento]'
              WHERE orden ='$busca'";
           
    $cProceso = "Actualizo ot orden ".$busca;
    
    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'OT';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }
  
    logs('Act(ot)','$Usr','$cProceso');
    
    
    header("Location: ordenescon.php");
    
}elseif($_REQUEST[Boton] == 'Enviar correo'){

    //Librerias necesarias en en la carpeta de OMICROM
    require_once 'nusoap.php';
    require_once 'class.phpmailer.php';
    
    
    require_once('tcpdf/config/lang/eng.php');
    require_once('tcpdf/tcpdf.php');
    //require_once("importeletras.php");

    $doc_title    = "Formato";
    $doc_subject  = "recibos unicode";
    $doc_keywords = "keywords para la busqueda en el PDF";


    //create new PDF document (document units are set by default to millimeters)
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 

    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(PDF_AUTHOR);
    $pdf->SetTitle($doc_title);
    $pdf->SetSubject($doc_subject);
    $pdf->SetKeywords($doc_keywords);

    // **** Formato del tipo de hoja a imprimir
    define ("PDF_PAGE_FORMAT", "A4");


    define ("PDF_MARGIN_TOP", 5);      //Donde empieza el texto dentro del cuadro

    define ("PDF_MARGIN_BOTTOM", 0);


    //Paramentro como LogotipoImg,,Nombre de la Empresa,Sub titulo
    //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->SetHeaderData('logoDuran245.png','','','');


    // Tamaño de la letra pero de header(titulos);
    define ("PDF_FONT_SIZE_MAIN", 8);

    //Titulo que va en el encabezado del archivo pdf;
    define ("PDF_HEADER_TITLE", "Impresion de formatos");

    //Tamaño de la letra del body('time','BI',8) BI la hace renegrida;
    $pdf->SetFont('helvetica', '', 8);

    //set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->setLanguageArray($l); //set language items

    //initialize document
    $pdf->AliasNbPages();

    $pdf->AddPage();

    // set barcode
    $pdf->SetBarcode(date("Y-m-d H:i:s", time()));
    //$pdf->SetBarcode("Fabrica de Texcoco");

    $cFecha  = $Cia[ciudad]." a ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);

    $pdf->writeHTML('<table border="0"><tr><td align="right"><h1>Laboratorio Clinico Duran &nbsp;</h1></td></tr>
                     <tr><td> &nbsp; </td></tr>
                     <tr><td align="right">Sucursal:'.$Gcia.' &nbsp;'.$Cia[alias].' &nbsp;  </td></tr>
                     <tr><td> &nbsp; </td></tr>
                     <tr><td align="right">'.$Cia[municipio].' '.$cFecha.' &nbsp;  </td></tr>
                     <tr><td> &nbsp; </td></tr>
                     <tr><td align="right"><h2>Cotizacion No: '.$busca.'&nbsp;</h2></td></tr>
                     </table>', true, 0, true, 0);            


    $Head = '<br><br><table border="1" align="center">
          <tr bgcolor="#e1e1e1">
          <td align="center" width="80"  height="40">Estudio</td>
          <td align="center" width="450">Descripcion</td>
          <td align="center" width="450">Procedimiento</td>
          <td align="center" width="110">Precio</td></tr>';

    $CtdA  =   mysql_query("SELECT ctd.estudio,est.descripcion,ctd.precio,ctd.descuento,est.condiciones,est.estudio as clvestudio 
               FROM ctd,est 
               WHERE ctd.estudio=est.id and ctd.id='$busca'");

    $Datos = '<br><br><br><br><table align="center">';

    while ($Ctd = mysql_fetch_array($CtdA)) {
         $Datos = $Datos . '<tr bgcolor="#c1c1c1"><td width="950" height="30"> &nbsp; <b>Estudio: </b>'.$Ctd[clvestudio].' &nbsp; ('.$Ctd[estudio].')</td><td width="110">&nbsp;</td></tr>'.                 
                  '<tr><td width="950" height="30"><b>Descripcion: </b>'.$Ctd[descripcion].'</td><td width="110" align="right"> $ '.number_format($Ctd[precio]*(1-$Ctd[descuento]/100),"2").'</td></tr>'.                    
                  '<tr><td width="950" height="30"><b>Condiciones: </b>'.$Ctd[condiciones].'</td><td width="110">&nbsp;</td></tr>'.                    
                  '<tr><td width="950" height="40"> &nbsp; </td><td width="110"> &nbsp; </td></tr>';                    
         $nImporte += $Ctd[precio]*(1-$Ctd[descuento]/100);         
    }

    $Datos = $Datos . '<tr><td width="950" height="30" align="right"> &nbsp; <b>Total ---> &nbsp; </b></td><td width="110" align="right"> $ '.number_format($nImporte,"2").'</td></tr>';                 

    $pdf->writeHTML($Datos.'</table>', true, 0, true, 0);

    //Close and output PDF document
    $FileOut = 'cotizaciones/CotizacionDuran.pdf';
    $pdf->Output($FileOut,'F');   //Con esto lo guarado con el nombre prueba en la raiz
    //============================================================+
    // END OF FILE                                                 
    //============================================================+

    $SmtpA = mysql_query("SELECT * FROM smtp WHERE smtpvalido = 1 ORDER BY id");

    $Smtp = mysql_fetch_array($SmtpA);


    //if(!file_exists($directorioV14)){
    //    $Pdf = "fae/archivos/" . $Cpo[uuid] . ".pdf";
    //    $Xml = "fae/archivos/" . $Cpo[uuid] . ".xml";
    //}else{
        $Pdf = $FileOut;
    //}

    try {
    //*******************Envio de correo;
    //		Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    //Tell PHPMailer to use SMTP
    $mail->IsSMTP();
    //Enable SMTP debugging
    //0 = off (for production use)
    //1 = client messages
    //2 = client and server messages
    $mail->SMTPDebug = 0;

    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'error_log';

    //Set the hostname of the mail server
    $mail->Host = $Smtp[smtpname];

    //Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = $Smtp[smtpport];    

    //Whether to use SMTP authentication
    //$mail->SMTPSecure = 'ssl';
    
    $mail->SMTPAuth = true;

    error_log("********************************************\n" . $Smtp[smtpuser] . "::" . $Smtp[smtploginpass] ."\n");
    //Username to use for SMTP authentication
    $mail->Username = $Smtp[smtpuser];

    //Password to use for SMTP authentication
    $mail->Password = $Smtp[smtploginpass];
    
    //Set the subject line
    $mail->Subject = "Factura electronica";

  
    $mail->Body = "Estimado cliente, le estamos enviando por este medio la factura electronica y al mismo tiempo nos reiteramos a sus ordenes para cualquier aclaracion al respecto, gracias por su preferencia.<br> ";

    //Replace the plain text body with one created manually
    $mail->AltBody = 'Envio de cfdi';
    
     //Set who the message is to be sent from
    $mail->SetFrom('facturacion@detisa.com.mx', 'detisa.com.mx');

    //Set who the message is to be sent to
    $mail->AddAddress($_REQUEST[Correo], 'Servicios');

    //Attach an image file
    $mail->AddAttachment($Pdf);

    //Send the message, check for errors
    if (!$mail->Send()) {

        $Msj =  "Mailer Error: " . $mail->ErrorInfo;
    } else {
        $Msj = "Su archivos Pdf han sido enviados con exito";
    }
    } catch (phpmailerException $e) {
        error_log($e->errorMessage());
        $Msj = $e->errorMessage();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $Msj = $e->getMessage();
    }
    
    header("Location: cotizaciones.php?Msj=$Msj");
            
}


$CpoA      = mysql_query("SELECT ct.cliente,ct.fecha,ct.hora,ct.receta,ct.fecharec,ct.diagmedico,ct.medico,
             ct.observaciones,ct.servicio, ct.fechae,ct.importe,ct.recepcionista,ct.suc,ct.recibio,ct.horae,
             ct.descuento,cli.nombrec,cli.mail 
             FROM ct LEFT JOIN cli ON  ct.cliente=cli.cliente 
             WHERE ct.id='$busca'");
$Cpo       = mysql_fetch_array($CpoA);

$MedA       =   mysql_query("SELECT nombrec FROM med WHERE id='$Cpo[medico]'");
$Med        =   mysql_fetch_array($MedA);



require ("config.php");							   //Parametros de colores;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<?php    

echo '<body topmargin="1">';

encabezados();

PonTitulo($Titulo);

menu($Gmenu);


//submenu();

//Tabla contenedor de brighs

echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValidaCampos();'>";
        
//Tabla Principal    
    echo "<table border='0' width='100%' align='center' cellpadding='1' cellspacing='4'>";    
    echo "<tr>";
        echo "<td bgcolor='$Gbgsubtitulo'  width='50%' class='letratitulo' align='center'>";
        echo "Datos principales";
    echo "</td><td bgcolor='$Gbgsubtitulo'  width='50%' class='letratitulo' align='center'>";
        echo "Otros datos...";
    echo "</td></tr>";
    
    //Renglo para crear un espacio...
    echo "<tr height='2'><td></td><td></td></tr>";

    echo "<tr><td valign='top' align='center' height='440'>";


        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
        //cTable('90%', '0',$Titulo);
           
            //echo "<tr><td colspan='2' align='left'  bgcolor='$Gbgsubtitulo' class='titulo'><div class='letrasubt' align='center'> Datos adicionales</div></td></tr>";            
            cInput("No.de orden :", "Text", "13", "Orden", "right", $Cpo[orden], "10", 1, 1, "");
            cInput("Fecha :", "Text", "13", "Fecha", "right", $Cpo[fecha], "20", 1, 1, "Hra: ".$Cpo[hora]);
            cInput("Descuento:", "Text", "20", "Descuento", "right", $Cpo[descuento], "20", 1, 0, " razon");
            cInput("Paciente :", "Text", "30", "Cliente", "right", $Cpo[nombrec], "35", 1, 1, '');            
            cInput("No. Receta :", "Text", "20", "Receta", "right", $Cpo[receta], "40", 1, 0, '');
            cInput("Fecha receta :", "Text", "20", "Fecharec", "right", $Cpo[fecharec], "40", 1, 0, '');
            echo "<tr height='35' class='letrap'><td align='right' valign='bottom'>Diagnostico:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Diagmedico' cols='65' class='letrap' rows='4'>$Cpo[diagmedico]</TEXTAREA>";
            echo "</td></tr>";  
            echo "<tr height='35' class='letrap'><td align='right' valign='bottom'>Observaciones:&nbsp;</td><td>";
            echo "<TEXTAREA NAME='Observaciones' cols='65' class='letrap' rows='4'>$Cpo[observaciones]</TEXTAREA>";
            echo "</td></tr>";  

            echo "<tr height='30' class='letrap'><td align='right'>Institucion:&nbsp;</td><td>";
            echo '<select name="Institucion" class="content5" id="Unidades" disabled>';            
            $InsA = mysql_query("SELECT institucion as id,alias,lista,condiciones FROM inst WHERE status='ACTIVO' ORDER BY institucion");      
            while ($Ins=mysql_fetch_array($InsA)){      
                echo '<option value='.$Ins[id].'>'.$Ins[alias].'</option>';
                if($Cpo[institucion] == $Ins[id]){
                    echo '<option selected="'.$Cpo[institucion].'">'.$Ins[alias].'</option>';  
                }
            }          
           echo '</select> ';
           echo "</td></tr>";
           
            cInput("Medico :", "Text", "8", "Medico", "right", $Cpo[medico], "40", 1, 1, $Med[nombrec]);            

            echo "</td></tr>";   
            
            echo "<tr height='10' bgcolor='#b1b1b1'><td></td><td></td></tr>";             
            
            echo "<tr class='letrap'><td align='right' class='formas_txt'>Correo: &nbsp;</td><td bgcolor='#ffffff'>&nbsp;";
                    echo "<input type='text' size='40' name='Correo' value='$Cpo[mail]'>";
                    echo "<input type='submit' name='Boton' value='Enviar correo' class='texto_tablas'>";
            echo "</td></tr>";
            
            echo "<tr height='10' bgcolor='#b1b1b1'><td></td><td></td></tr>";                         
            
        echo "</table>";
            botones();
            
    //Cuadro derecho del cuadro principal     
    echo "</td><td valign='top'>";

        echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='0' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

            //echo "<tr><td colspan='2' align='center'  bgcolor='$Gbgsubtitulo'><div class='letrasubt'>Datos personales</td></tr>";

            echo "<tr><td align='right'  class='letrap'>Servicio: &nbsp; </td><td>";
            echo '<select name="Servicio" class="content5" id="Unidades">';
            echo '<option value="Ordinario">Ordinario</option>';
            echo '<option value="Urgente">Urgente</option>';
            echo '<option value="Express">Express</option>';
            echo '<option value="Hospitalizado">Hospitlizado</option>';
            echo '<option value="Nocturno">Nocturno</option>';
            echo '<option selected="'.$Cpo[servicio].'">'.$Cpo[servicio].'</option>';  
            echo '</select> &nbsp; ';        
            echo "</td></tr>";
            cInput("Fecha de entrega :", "Text", "25", "Fechae", "right", $Cpo[fechae], "30", 1, 0, '');
            cInput("Hora :", "Text", "10", "Horae", "right", $Cpo[horae], "10", 1, 0, '');
            cInput("Importe :", "Text", "40", "Importe", "right", number_format($Cpo[importe],2), "50", 1, 1, '');
 
            //echo "<tr height='20' bgcolor='#e1e1e1'><td></td><td></td></tr>";             
            echo "<tr><td height='30' colspan='2' align='center' ><div class='letrasubt'><b>Estudios de la cotizacion</b></td></tr>";
            echo "<tr bgcolor='#b1b1b1'><td height='10' colspan='2' align='center'><hr></td></tr>";
            echo "<tr><td height='10' colspan='2' align='center'></td></tr>";
 
            //Detalle de estudios
            echo "<table width='98%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";           
            echo "<tr bgcolor='$Gbgsubtitulo'><th class='letrap'>Estudio</th><th class='letrap'>Descripcion</th><th class='letrap'>Descto</th><th class='letrap'>Importe</th><td>";                 
            
            $CtdA   = mysql_query("SELECT est.estudio,est.descripcion,ctd.precio,ctd.descuento
                      FROM est,ctd
                      WHERE ctd.id='$busca' AND ctd.estudio=est.id");
            $nRng   = 0;
            while($registro=mysql_fetch_array($CtdA)){
                
                if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
                echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                echo "<td class='content2'>".ucwords(strtolower($registro[estudio]))."</td>";
                echo '<td class="content2">'.ucwords(strtolower($registro[descripcion])).'</span></td>';
                echo '<td align="right"><span class="content2">'.number_format($registro[descuento],"2").'</span></td>';
                echo '<td align="right"><span class="content2">'.number_format($registro[precio],"2").'</span></td>';
                echo '</tr>';
                $nImporte  += $nImp+($registro[precio]*(1-($registro[descuento]/100)));
                $nRng++;
            }
            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td class='content2'>&nbsp;</td>";
            echo '<td align="right"><span class="content2"><b>Importe: </span></td>';
            echo '<td align="right"><span class="content2"> </span></td>';
            echo '<td align="right"><span class="content2"><b>'.number_format($nImporte,"2").'</span></td>';
            echo '</tr>';   
            echo "</table>";
            
            
            echo "<tr class='letrap'><td align='right'>Registro:</td><td> &nbsp; &nbsp; ";            
                        
            echo "Usr: $Cpo[recepcionista]&nbsp; &nbsp; &nbsp; ";
            echo "Sucursal: $Cpo[suc] &nbsp; &nbsp; &nbsp; ";
            echo "Ult.usuario: $Cpo[usrmod]";
            echo "</td></tr>";

                                     
            echo "</table>";
    //Cierra tabla principal la de dos cuadros        
    echo "</td></tr>";        
    echo "</table>";  
echo "</form>";  
echo '</body>';

?>
<script type="application/javascript">
(function() {
    function ocultar(one, two, three, four, five) {
        $.each([one, two, three, four, five], function(index, elm) {
            if($(elm).css('display')) {
                $(elm).slideUp('fast');
            }
        });
    }
    jQuery(function($) {

        //-----Recepcion Menu
        $('#recepcion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-recepcion').css('display') == 'none') {
                $('#two-level-recepcion').slideDown('fast');
                ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-recepcion').mouseleave(function () {
            if($('#two-level-recepcion').css('display')) {
                $('#two-level-recepcion').slideUp('fast');
            }
        });
        //-----Facturacion Menu
        $('#facturacion').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-facturacion').css('display') == 'none') {
                $('#two-level-facturacion').slideDown('fast');
                ocultar('#two-level-catalogos', '#two-level-ingresos', '#two-level-reportes', '#two-level-recepcion', '#two-level-moviles');
            }
      	});
        // Close menu when mouse leaves Hidden Content
    	$('#two-level-facturacion').mouseleave(function () {
            if($('#two-level-facturacion').css('display')) {
                $('#two-level-facturacion').slideUp('fast');
            }
        });		

        //--------Catalogos Menu
        $('#catalogos').mouseover(function () {
          // Show hidden content IF it is not already showing
            if($('#two-level-catalogos').css('display') == 'none') {
                $('#two-level-catalogos').slideDown('fast');
                ocultar('#two-level-recepcion', '#two-level-ingresos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
            }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-catalogos').mouseleave(function () {
            if($('#two-level-catalogos').css('display')) {
                $('#two-level-catalogos').slideUp('fast');
            }
        });

        //-----Ingresos Menu
        $('#ingresos').mouseover(function () {
              // Show hidden content IF it is not already showing
              if($('#two-level-ingresos').css('display') == 'none') {
                  $('#two-level-ingresos').slideDown('fast');
                  ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-moviles');
              }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-ingresos').mouseleave(function () {
            if($('#two-level-ingresos').css('display')) {
                  $('#two-level-ingresos').slideUp('fast');
              }

        });

        //------Reportes Menu
        $('#reportes').mouseover(function () {
              // Show hidden content IF it is not already showing
            if($('#two-level-reportes').css('display') == 'none') {
                $('#two-level-reportes').slideDown('fast');
                ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-moviles', '#two-level-facturacion','#two-level-ingresos', '#two-level-procesos');
            }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-reportes').mouseleave(function () {
            if($('#two-level-reportes').css('display')) {
                $('#two-level-reportes').slideUp('fast');
            }
        });
		        //-----Moviles Menu
        $('#moviles').mouseover(function () {
              // Show hidden content IF it is not already showing
              if($('#two-level-moviles').css('display') == 'none') {
                  $('#two-level-moviles').slideDown('fast');
                  ocultar('#two-level-recepcion', '#two-level-catalogos', '#two-level-reportes', '#two-level-facturacion', '#two-level-ingresos');
              }
        });
        // Close menu when mouse leaves Hidden Content
        $('#two-level-moviles').mouseleave(function () {
            if($('#two-level-moviles').css('display')) {
                  $('#two-level-moviles').slideUp('fast');
              }

        });


    });
})();
</script>
</html>

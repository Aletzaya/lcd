<?php

session_start();

require("lib/lib.php");

include_once ("auth.php");

include_once ("authconfig.php");

include_once ("check.php");

$link     = conectarse();

//Librerias necesarias en en la carpeta de OMICROM
require_once 'nusoap.php';
require_once 'class.phpmailer.php';
//NOTA: es necesario copiar la libreria class.smtp.php a la misma carpeta

$busca    =   $_REQUEST[busca];
$op       =   $_REQUEST[op];

$CpoA     = mysql_query("SELECT fc.fecha,fc.cliente,fc.cantidad,fc.iva,fc.importe,
            clif.rfc,clif.nombre,fc.uuid,clif.correo,fc.usr,fc.observaciones
  	    FROM clif,fc WHERE fc.id='$busca' AND fc.cliente=clif.id");

$Cpo      = mysql_fetch_array($CpoA);



$Pdf      = "fae/archivos/" . $Cpo[uuid] . ".pdf";

$Xml      = "fae/archivos/" . $Cpo[uuid] . ".xml";
     
  if($busca=='NUEVO'){
      
     header("Location: clientesf.php?pagina=0&Sort=Asc&orden=clif.id&busca=");
      
  }elseif($op==9){ 


    $SmtpA = mysql_query("SELECT * FROM smtp WHERE smtpvalido = 1 ORDER BY id");

    $Smtp = mysql_fetch_array($SmtpA);

    /*
    if(!file_exists($directorioV14)){
        $Pdf = "fae/archivos/" . $Cpo[uuid] . ".pdf";
        $Xml = "fae/archivos/" . $Cpo[uuid] . ".xml";
    }else{
        $Pdf = "/var/www/lcd/fae/archivos/" . $Cpo[uuid] . ".pdf";
        $Xml = "/var/www/lcd/fae/archivos/" . $Cpo[uuid] . ".xml";
    }

    echo "Archivo Pdf " . $Pdf;
    */

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
    //$mail->SetFrom('facturacion@detisa.com.mx', 'detisa.com.mx');
    $mail->SetFrom('administracion@dulab.com.mx', 'Laboratorio Clinico Duran, factura electronica');

    //Set who the message is to be sent to
    $mail->AddAddress($_REQUEST[Correo], 'Servicios');

    /***************************************
    */
    
    
    
            $sql = "SELECT 
                  pdf_format, cfdi_xml, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@nombre') name32, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@Nombre') name33, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@rfc') rfc32, 
                  ExtractValue(cfdi_xml, '/cfdi:Comprobante/cfdi:Receptor/@Rfc') rfc33, 
                  uuid FROM facturas WHERE id_fc_fk = $busca ";
        $result = mysql_query($sql);
        $myrowsel = mysql_fetch_array($result);
        $receptor = $myrowsel['name'] . " (" . $myrowsel['rfc'] . ")";
        // Read attachments
        $mail->AddStringAttachment($myrowsel['pdf_format'], $Cpo['uuid'] . ".pdf", "base64", "application/pdf");
        $mail->AddStringAttachment($myrowsel['cfdi_xml'], $Cpo['uuid'] . ".xml", "base64", "application/xml");
        
        $mail->ContentType = 'multipart/mixed';
        //Set the subject line
        $mail->Subject = "Envío de Factura Electrónica " . $Cpo['uuid'];


    
    
    /*
    *************************************************************/
    //Attach an image file
    
    //$mail->AddAttachment($Pdf);
    //$mail->AddAttachment($Xml);
    /*
    $sql = "SELECT pdf_format, cfdi_xml, uuid FROM facturas WHERE uuid =  '$Cpo[uuid]'";
    $result = mysql_query($sql);
    while ($myrowsel = mysql_fetch_array($result)) {

        //Attach an image file
        $mail->AddStringAttachment($myrowsel['pdf_format'], $Cpo[uuid] . ".pdf", "base64", "application/pdf");
        $mail->AddStringAttachment($myrowsel['cfdi_xml'], $Cpo[uuid] . ".xml", "base64", "application/xml");
    }          
    */
    //Send the message, check for errors
    if (!$mail->Send()) {

        $Msj =  "Mailer Error: " . $mail->ErrorInfo;
    } else {

        $Msj = "Sus archivos Xml y Pdf han sido enviados con exito";
    }
    } catch (phpmailerException $e) {
        error_log($e->errorMessage());
        $Msj = $e->errorMessage();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $Msj = $e->getMessage();
    }
    
    header("Location: facturas.php?Msj=$Msj");


      /*
        require_once('tcpdf/config/lang/eng.php');
        require_once('tcpdf/tcpdf.php');

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



        $CiaA  = mysql_query("SELECT nombre, rfc, direccion, numeroext, numeroint, colonia, ciudad, estado, telefono, 
            numeroext, numeroint, codigo, iva, facclavesat,facturacion
            FROM cia
            WHERE id =1");

        $Cia   = mysql_fetch_array($CiaA);


        $SmtpA  =  mysql_query("SELECT * FROM smtp WHERE smtpvalido = 1 ORDER BY id");

        $Smtp = mysql_fetch_array($SmtpA);

        //*******************Envio de correo;
        //		Create a new PHPMailer instance
        $mail = new PHPMailer();

        //Tell PHPMailer to use SMTP
        $mail->IsSMTP();
        //Enable SMTP debugging
        //0 = off (for production use)
        //1 = client messages
        //2 = client and server messages
        $mail->SMTPDebug = 0;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';


        //Set the hostname of the mail server
        //$mail->Host = "mail.sta-dev.com";
        $mail->Host = $Smtp[smtpname];

        //Set the SMTP port number - likely to be 25, 465 or 587
        //$mail->Port = 587;
        //$mail->Port = $Smtp[smtpport];
	$mail->Port = 587;
//****************************************************************

	$mail->SMTPSecure = 'ssl';


        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;


        //Username to use for SMTP authentication
        //$mail->Username = "ja.ibanez@sta-dev.com";
        $mail->Username = $Smtp[smtpuser];


        //Password to use for SMTP authentication
        //$mail->Password = "jaio1979";
        $mail->Password = $Smtp[smtploginpass];

        //Set who the message is to be sent from
        //$mail->SetFrom('facturacion@detisa.com.mx', 'Consumo de combustible, factura electronica');

        $mail->SetFrom('direccion@dulab.com.mx', 'Laboratorio clinico Duran, factura electronica');


        //Set an alternative reply-to address
        //$mail->AddReplyTo('froylanayala@hotmail.com','First Last');
        //Set who the message is to be sent to
        //$mail->AddAddress('froylanayala@hotmail.com', 'El gallo de Tulantongo');
        $mail->AddAddress($_REQUEST[correo], 'Servicio clinico');
        //Set the subject line
        //$mail->Subject = 'Factura Estacion Oaxa, ES9447';
        $mail->Subject = "Laboratorio Clinico Duran S.A. de C.V.";

        $msg='Name:Froylan Ayala<br />
        Email:Correo <br />
        Subject: Fae<br />
        IP:Mi ip<br /><br />
        Message:<br />';

       //Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
       $mail->MsgHTML($msg, dirname(__FILE__));
       //$mail->MsgHTML(file_get_contents(prueba.html), dirname(__FILE__));

       $mail->Body = "Estimado cliente, le estamos enviando por este medio la factura electronica y al mismo tiempo nos reiteramos a sus ordenes para cualquier aclaracion al respecto, gracias por su preferencia.<br> ";


       //Replace the plain text body with one created manually
       $mail->AltBody = 'Envio de cfdi';

       //Attach an image file
       $mail->AddAttachment($Pdf);
       $mail->AddAttachment($Xml);

       //Send the message, check for errors
       if(!$mail->Send()) {

          echo "Mailer Error: " . $mail->ErrorInfo;

       }else {

          $Msj = "Sus archivos Xml y Pdf han sido enviados con exito";
       }
       
       */
}

  
   
  $Titulo   =   "Edita factura [$busca]"; 
  

  $lBd=false;

  require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

headymenu($Titulo,0); 

?>

<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}
function SiElimina(){
  if(confirm("ATENCION! Desea dar de Baja este registro?")){
     return(true);
   }else{
     document.form1.busca.value="NUEVO";
      return(false);
   }
}


function Completo(){
var lRt;
lRt=true;
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}
</script>

<?php

echo '<table width="100%" border="0">';
  echo '<tr>';
  echo "<td  width='10%' rowspan='2'>";      
         echo "$Gfont regresar &nbsp; &nbsp; <br>";
         echo "<a class='pg' href='facturas.php'><img src='lib/regresa.jpg' border='0'></a>";
      
   echo "</td>";
   echo "<td align='center'>";
   
   echo "<p>&nbsp</p>";
   
        echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

       cTable('95%','0');

       cInput("Id:","Text","5","Id","right",$busca,"40",false,true,"");

       cInput("Fecha: ","Text","10","Fecha","right",$Cpo[fecha],"10",true,true,'');

       cInput("Cliente:","Text","5","Cliente","right",$Cpo[cliente],"5",true,false,'');
       cInput("Cantidad:","Text","5","Cantidad","right",$Cpo[cantidad],"5",true,true,'');
       cInput("Iva:","Text","5","Iva","right",$Cpo[iva],"5",true,true,'');
       //cInput("Ieps:","Text","5","Ieps","right",$Cpo[ieps],"5",true,true,'');
       cInput("Importe:","Text","5","Importe","right",$Cpo[importe],"5",true,true,'');
       cInput('','text','','','','','',false,true,'Para su verificacion fiscal');
       cInput('','text','','','','','',false,true,'https://verificacfdi.facturaelectronica.sat.gob.mx');
       cInput("Cliente :","Text","05","Cliente","right",$Cpo[nombre],"55",true,true,'');
       cInput("Rfc :","Text","15","Rfc","right",$Cpo[rfc],"15",true,true,'');
       cInput("Folio fiscal:","Text","5","Uuid","right",$Cpo[uuid],"40",true,true,'');
       cInput("Observaciones:","Text","5","Observaciones","right",$Cpo[observaciones],"40",true,true,'');
       cInput("Usr:","Text","5","Usr","right",$Cpo[usr],"40",true,true,'');


       cTableCie();

       echo "<br>";
       
       echo "<div align='left' class='textos'>";
       echo "<form  name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";
       echo "Ingrese el email de su cliente para enviarlo por correo si asi lo desea, <br>  ";
       echo "<input class='textos' type'text' name='Correo' value='$Cpo[correo]' size='30' class='texto_tablas' onBlur='ValidaCorreo(this.value)'>";
       echo "<input type='submit' name='Boton' value='Enviar correo' class='Botones'>";
       echo "<input type='hidden' name='busca' value='$busca'>";
       echo "<input type='hidden' name='op' value='9'>";
       echo "</form>";

       echo "<font color='#990000'>$Msj</font>";

       echo "</div>";

       
	   /*
       cInput("Colonia:","Text","30","Colonia","right",$Cpo[colonia],"30",true,false,'');
       cInput("Municipio:","Text","30","Municipio","right",$Cpo[municipio],"30",true,false,'');
       cInput("Telefono:","Text","20","Telefono","right",$Cpo[telefono],"20",true,false,'');
       cInput("Contacto:","Text","40","Contacto","right",$Cpo[contacto],"40",true,false,'');

	   echo "<tr class='textosTabla'><td align='right' valign='bottom'>Observaciones: &nbsp;</td><td>";
       echo "<TEXTAREA NAME='Observaciones' style='background-color:$InputCol;color:#ffffff;' cols='52' rows='3' >$Cpo[observaciones]</TEXTAREA>";
       echo "</td></tr>";


   	   echo "<tr><td align='right' class='textosTabla'>$Gfont Activo: &nbsp;</td><td>";
       echo "<select name='Activo'>";
       echo "<option value='Si'>Si</option>";
       echo "<option value='No'>No</option>";
       if($lAg){
          echo "<option selected value='Si'>Si</option>";
       }else{
          echo "<option selected value='$Cpo[activo]'>$Cpo[activo]</option>";
		 }
       echo "</select>";
  	    echo "</td><tr>";

       //cInput('','text','','','','','',false,true,'Apellido paterno, materno y Nombre');


       */

       //echo Botones();

       mysql_close();

      echo "</form>";

  echo "</td>";
    
  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

mysql_close();

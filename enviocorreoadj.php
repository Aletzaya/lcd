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

$Orden  =   $_REQUEST[Orden];
$Correo = $_REQUEST[Correo]; 
$Correom = $_REQUEST[Correom]; 
$Correoi = $_REQUEST[Correoi]; 
$Entemailpac = $_REQUEST[entemailpac]; 
$Entemailmed = $_REQUEST[entemailmed]; 
$Entemailinst = $_REQUEST[entemailinst]; 
$Usr    = $check['uname'];
$Fecha  = date("Y-m-d H:i:s");

$SmtpA = mysql_query("SELECT * FROM smtp WHERE smtpvalido = 1 ORDER BY id");

$Smtp = mysql_fetch_array($SmtpA);


$ImagenesC  = mysql_query("SELECT archivo FROM enviarc WHERE enviarc.orden='$Orden'");
$ImagenesD  = mysql_query("SELECT archivo FROM enviarc WHERE enviarc.orden='$Orden'");

$Img= array('','','','','','','','','','','','');
$Path= array('','','','','','','','','','','','');
$RG=1;

while($Imagenes=mysql_fetch_array($ImagenesC)){

    $Img[$RG]  = $Imagenes[archivo];
    $Path[$RG] = "estudiospdf/".$Imagenes[archivo];
    $RG++;

}

$RG=1;

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
    $mail->Subject = "Envio de Resultados";

    $mail->Body = "CONFIDENCIALIDAD: La información contenida en este correo electrónico, incluyendo cualquier archivo adjunto, es privilegiada y confidencial y para uso exclusivo de los destinatarios y/o de quienes hayan sido autorizados específicamente para leerla. Si ud ha recibido este correo por error, favor de destruirlo y notificarlo al remitente a la dirección electrónica indicada. Cualquier divulgación, distribución o reproducción de este comunicado está estrictamente prohibida y sujeta a las sanciones establecidas en las leyes correspondientes. 'Laboratorio Clínico Duran, S.A. de C.V.' no será responsable en ningún momento por los cambios que este comunicado sufra en su transferencia. <br> <br> Estimado cliente, le estamos enviando por este medio el resultado de su estudio y al mismo tiempo nos reiteramos a sus ordenes para cualquier aclaracion al respecto, gracias por su preferencia.<br>";
  

    //Replace the plain text body with one created manually
    $mail->AltBody = 'Envio de Estudio';
    
     //Set who the message is to be sent from
    //$mail->SetFrom('facturacion@detisa.com.mx', 'detisa.com.mx');
    $mail->SetFrom('administracion@dulab.com.mx', 'Laboratorio Clinico Duran, envio de resultado');

    //Set who the message is to be sent to
    if($Entemailpac==1){
        $mail->AddAddress($Correo, 'Cliente');
        $Correo2=$Correo;
    }else{
        $Correo2='';
    }

    if($Entemailmed==1){
        $mail->AddAddress($Correom, 'Medico');
        $Correom2=$Correom;
    }else{
        $Correom2='';
    }

    if($Entemailinst==1){
        $mail->AddAddress($Correoi, 'Institucion');
        $Correoi2=$Correoi;
    }else{
        $Correoi2='';
    }

    //***************************************
    // Read attachments
    //$mail->AddStringAttachment($Formato, $Pdf, 'base64', 'application/pdf');


//    $ImagenesC  = mysql_query("SELECT archivo FROM envimg WHERE envimg.orden=$Orden");

//    while($Imagenes=mysql_fetch_array($ImagenesC)){


 //       $Img  = $Imagenes[archivo];
  //      $Path = "estudios/".$Imagenes[archivo];

    while($Imagenes=mysql_fetch_array($ImagenesD)){

        $mail->AddAttachment($Path[$RG],$Img[$RG],'base64','application/octet-stream');
        $RG++;

    }
           
    $mail->ContentType = 'multipart/mixed';
        //Set the subject line
    //$mail->Subject = "Envío de Resultado de estudio " . $Orden."_".$Estudio;
    $mail->Subject = "Envío de Resultado de estudio ";

    //Send the message, check for errors
    if (!$mail->Send()) {

        $Msj =  "Mailer Error: " . $mail->ErrorInfo;
        $Enviado='No';

    } else {

        $Msj = "Los Resultados han sido enviadas con exito";
        $Enviado='Si';

        $Sql=mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden'");

        while ($rowb = mysql_fetch_array($Sql)) {

              $Rarchivo=$rowb[archivo];

              $lUp2  = mysql_query("INSERT INTO logenvio (orden,fecha,usr,emailp,emailm,emaili,estudio) VALUES
                ('$Orden','$Fecha','$Usr','$Correo2','$Correom2','$Correoi2','$rowb[estudio]')");

              unlink("estudiospdf/".$Rarchivo);

        } 

        $cSqlE = mysql_query("DELETE FROM enviarc WHERE enviarc.orden='$Orden'");

    }

  } catch (phpmailerException $e) {
      error_log($e->errorMessage());
      $Msj = $e->errorMessage();
  } catch (Exception $e) {
      error_log($e->getMessage());
      $Msj = $e->getMessage();
  }
    
  header("Location: entregamail2.php?Orden=$Orden&Op='Envio'&Msj=$Msj&Enviado=$Enviado");

mysql_close();

?>
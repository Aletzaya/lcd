<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  require ("config.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");
	
	//Librerias necesarias en en la carpeta de OMICROM
	require_once 'nusoap.php';
	require_once 'class.phpmailer.php';

  $link         = conectarse();
  $Orden        = $_REQUEST[Orden];
  $Estudio      = $_REQUEST[Estudio];
  $destino      = $_REQUEST[destino];
  $Recibeencaja = $_REQUEST[Recibeencaja];
  $Op        = $_REQUEST[Op];
  $Usr       = $check['uname'];
  $Fechaest  = date("Y-m-d H:i:s");
  $Fecha  = date("Y-m-d H:i:s");
   
	if($Op=='1'){
				
		$Pdf      = "estudiospdf/".$Orden."-".$Estudio.".pdf";
		  
		$SmtpA = mysql_query("SELECT * FROM smtp WHERE smtpvalido = 1 ORDER BY id");

		$Smtp = mysql_fetch_array($SmtpA);

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
    $mail->Subject = "Entrega de resultado: $Orden - $Estudio";

  
    $mail->Body = "Estimado cliente, le estamos enviando por este medio el resultado de su estudio y al mismo tiempo nos reiteramos a sus ordenes para cualquier aclaracion al respecto, gracias por su preferencia.<br> ";

    //Replace the plain text body with one created manually
    $mail->AltBody = 'Envio de resultado';
    
     //Set who the message is to be sent from
    //$mail->SetFrom('facturacion@detisa.com.mx', 'detisa.com.mx');
    $mail->SetFrom('administracion@dulab.com.mx', 'Laboratorio Clinico Duran, resultado de estudio');

    //Set who the message is to be sent to
    $mail->AddAddress($_REQUEST[Correo], 'Servicios');

    //Attach an image file
    
    $mail->AddAttachment($Pdf);

    //Send the message, check for errors
    if (!$mail->Send()) {

        $Msj =  "Mailer Error: " . $mail->ErrorInfo;
    } else {

        $Msj = "Sus archivos Pdf han sido enviados con exito";
    }
    } catch (phpmailerException $e) {
        error_log($e->errorMessage());
        $Msj = $e->errorMessage();
    } catch (Exception $e) {
        error_log($e->getMessage());
        $Msj = $e->getMessage();
    }
	
	if($destino==1){

		$Up  = mysql_query("UPDATE otd SET enviamail = '$Fecha - $Usr'
				WHERE orden='$Orden' and estudio='$Estudio'"); 
				
	}elseif($destino==2){
		
		$Up  = mysql_query("UPDATE otd SET enviamailm = '$Fecha - $Usr'
				WHERE orden='$Orden' and estudio='$Estudio'"); 
				
	}elseif($destino==3){
		
		$Up  = mysql_query("UPDATE otd SET enviamaili = '$Fecha - $Usr'
				WHERE orden='$Orden' and estudio='$Estudio'"); 
				
	}
//    header("Location: facturas.php?Msj=$Msj");

}
                
  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='2'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";
          
echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

?>

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Orden.focus();

}

</script>

<?php

echo "<body bgcolor='#FFFFFF' onload='cFocus()'>";          
  
  echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

		echo "<font size='3'> <p align='center'><b>Envio de Estudios E-mail</b></p>";
		
         $HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.entemailpac,ot.entemailmed,
			     ot.entemailinst
		 		 FROM ot,cli
                 WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");
                 
         $He   = mysql_fetch_array($HeA);        		

 		   echo "<div align='left'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
 		   echo "<div align='left'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";
		
         $Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja,otd.creapdf,otd.enviamail,
		 		 otd.enviamailm,otd.enviamaili
		 		 FROM otd,est 
                 WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");
		
          echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";
          echo "<tr height='25' bgcolor='#CCCCCC'>";
          echo "<td align='center'>$Gfont2 Estudio</td>";
          echo "<td align='center'>$Gfont2 Descripcion</td>";
          echo "<td align='center'>$Gfont2 PDF</td>";   
          echo "<td align='center'>$Gfont2 PDF2</td>";   
          echo "<td align='center'>$Gfont2 Paciente</td>";   
		  echo "<td align='center'>$Gfont2 Medico</td>";
          echo "<td align='center'>$Gfont2 Clinica</td>";  
          echo "</tr>";
		  
		  $OtdC  = mysql_query("SELECT cli.cliente,cli.nombrec,cli.mail
			  FROM ot,cli
			  WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");            

		  $rgp=mysql_fetch_array($OtdC);
		
		  $OtdM  = mysql_query("SELECT med.nombrec,med.mail
					  FROM ot,med
					  WHERE ot.orden='$Orden' AND ot.medico=med.medico");            
		
		  $rgm=mysql_fetch_array($OtdM);
		
		  $OtdI  = mysql_query("SELECT inst.nombre,inst.mail
					  FROM ot,inst
					  WHERE ot.orden='$Orden' AND ot.institucion=inst.institucion");            
		
		  $rgi=mysql_fetch_array($OtdI);
              

        while($rg=mysql_fetch_array($Sql)){
        
            $Lugar = $rg[lugar];  

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
            echo "<td>$Gfont $rg[estudio]</td>";
            echo "<td>$Gfont $rg[descripcion]</td>";
			if($rg[creapdf]==''){
				echo "<td align='center'>$Gfont &nbsp; <a class='pg' href=javascript:wingral('pdfestudio.php?Orden=$Orden&Estudio=$rg[estudio]&Usr=$Usr')>CREA_PDF</a></td>";
			}else{
				echo "<td align='center'>$Gfont &nbsp; <a class='pg' href=javascript:wingral('pdfestudio.php?Orden=$Orden&Estudio=$rg[estudio]&Usr=$Usr')>
				<img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a></td>";
			}
				
			echo "<td align='center'>$Gfont &nbsp; <a class='pg' href=javascript:wingral('output.php')>CREA_PDF2</a></td>";

			if($He[entemailpac]=='1'){
				if($rg[enviamail]==''){
		    	    echo "<td align='center'><a class='pg' href='envioestpdf.php?Orden=$Orden&Estudio=$rg[estudio]&Correo=$rgp[mail]&Op=1&destino=1'>$Gfont $rgp[mail]</td>";
				}else{
	    	    	echo "<td align='center'><a class='pg' href='envioestpdf.php?Orden=$Orden&Estudio=$rg[estudio]&Correo=$rgp[mail]&Op=1&destino=1'>$Gfont $rgp[mail] <img src='lib/slc.png'>
					<br>$rg[enviamail]</td>";
				}
			}else{
				echo "<td align='center'>$Gfont &nbsp;</td>";
			}
			
			if($He[entemailmed]=='1'){
				if($rg[enviamailm]==''){
		    	    echo "<td align='center'><a class='pg' href='envioestpdf.php?Orden=$Orden&Estudio=$rg[estudio]&Correo=$rgm[mail]&Op=1&destino=2'>$Gfont $rgm[mail]</td>";
				}else{
		    	    echo "<td align='center'><a class='pg' href='envioestpdf.php?Orden=$Orden&Estudio=$rg[estudio]&Correo=$rgm[mail]&Op=1&destino=2'>$Gfont $rgm[mail] <img src='lib/slc.png'>
					<br>$rg[enviamailm]</td>";
				}
		   }else{
				echo "<td align='center'>$Gfont &nbsp;</td>";
		   }

			if($He[entemailinst]=='1'){
				if($rg[enviamaili]==''){
		    	    echo "<td align='center'><a class='pg' href='envioestpdf.php?Orden=$Orden&Estudio=$rg[estudio]&Correo=$rgi[mail]&Op=1&destino=3'>$Gfont $rgi[mail]</td>";
				}else{
		    	    echo "<td align='center'><a class='pg' href='envioestpdf.php?Orden=$Orden&Estudio=$rg[estudio]&Correo=$rgi[mail]&Op=1&destino=3'>$Gfont $rgi[mail] <img src='lib/slc.png'>
					<br>$rg[enviamaili]</td>";
				}
		   }else{
				echo "<td align='center'>$Gfont &nbsp;</td>";
		   }

			echo "</tr>";
            $nRng++;

        }            	   
  		
        echo "</table>";

  echo "</form>";         
  	                
echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?> 
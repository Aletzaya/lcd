<?php
  #Librerias
  session_start();

  require("lib/lib.php");

  $link    =	conectarse();

  $cEst    = $_REQUEST[Estudio];
  $busca   = $_REQUEST[busca];

  $EstA	   = mysql_query("SELECT descripcion FROM est WHERE estudio='$cEst'");
  $Est	   = mysql_fetch_array($EstA);

  $OtA	   = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,
  		     ot.observaciones,ot.institucion,
  			 cli.sexo,cli.nombrec,cli.fechan,ot.medicon,medi.nombre as nombremed,otd.texto,
  			 medi.cedula,medi.profesion,medi.sexo, otd.letra
  			 FROM ot,cli,otd LEFT JOIN medi ON otd.medico=medi.id
  			 WHERE  ot.orden='$busca' AND ot.cliente=cli.cliente
             AND otd.orden=ot.orden AND otd.estudio='$cEst'");

  $Ot	   = mysql_fetch_array($OtA);
  
      $OtdB  = mysql_query("SELECT cuatro,lugar FROM otd WHERE orden='$busca' AND estudio='$cEst' LIMIT 1");
      $Otb   = mysql_fetch_array($OtdB);
       
      if(substr($Otb[cuatro],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;

          $Fecha = date("Y-m-d");
          $Hora  = date("H:i");

          if($Otb[lugar] <= '5'){
          
             $Up = mysql_query("UPDATE otd set cuatro = '$Fecha $Hora', lugar='5' 
	                WHERE orden='$busca' AND estudio='$cEst'"); 

          }else{

             $Up = mysql_query("UPDATE otd set cuatro = '$Fecha $Hora' 
	               WHERE orden='$busca' AND estudio='$cEst'");   

          }	

	  }

	   $NumA2  = mysql_query("SELECT otd.estudio,otd.cuatro
	   FROM otd 
	   WHERE otd.orden='$busca' AND otd.cuatro='0000-00-00 00:00:00'");

		 if(mysql_num_rows($NumA2)==0){
			$lUp = mysql_query("UPDATE ot SET impreso='Si' WHERE orden='$busca'");
		 }else{ 
			$lUp = mysql_query("UPDATE ot SET impreso='No' WHERE orden='$busca'");
		 } 

if ($Ot[institucion]=='94' or $Ot[institucion]=='64'){
  $Fecha  = $Ot[fecha];
}else{
  $Fecha  = date('Y-m-d');
}

if ($Ot[institucion]<>'125'){

  $Ano    = substr($Fecha,2,2);
  $nMes   = substr($Fecha,5,2)*1;

  $aMes   = array("-","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
  $cFecha = "Texcoco Edo. de Mexico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
}else{
  $Ano    = substr($Fecha,2,2);
  $nMes   = substr($Fecha,5,2)*1;

  $aMes   = array("-","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
  $cFecha = " ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
}

if($Ot[medico] == 'MD'){
   $NombreMed = $Ot[medicon];
}else{

  //Medico que solicita el estudio
  $MedA      = mysql_query("SELECT nombrec FROM med WHERE medico='$Ot[medico]'");
  $Med       = mysql_fetch_array($MedA);
  $NombreMed = $Med[nombrec];

}

//$htmlcontent = getHeadNews($db);

$doc_title    = "Formato";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');
require_once("importeletras.php");

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

//Paramentro como LogotipoImg,,Nombre de la Empresa,Sub titulo
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

//$pdf->SetHeaderData('logo.jpg','30','Laboratorio Clinico Duran S.A. de C.V.','Fray Pedro de Gante #108 Texcoco Centro Tel. 95 41140 e-mail: rpublicas@dulab.com.mx');

//define ("PDF_HEADER_LOGO", "lgo.jpg");

//	Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Impresion de formatos");
// Tamaño de la letra pero de header(titulos);
define ("PDF_FONT_SIZE_MAIN", 8);

//Tamaño de la letra del body('time','BI',8) BI la hace renegrida;
$pdf->SetFont('helvetica', '', $Ot[letra]);

//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);

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

//Mando firma
//														posc horiz,posc vert,ancho,alto,,,,,150 entre mas grande mas pkña la img
//																										agrandas este numero y reduces los otros 2

$pdf->writeHTML('<table border="0"><tr><th align="right">'.$cFecha.' &nbsp; </th></tr></table><br><br>', true, 0, true, 1);

$pdf->writeHTML('<table border="0">
		         <tr><th align="left"><b>'.utf8_encode($Ot[nombrec]).'</b></th></tr>
		         <tr><td align="left">'.$cEst.': '.$Est[descripcion].'</td></tr>
		         <tr><td align="left">Dr(a).: '.utf8_encode($NombreMed).'</td></tr>
		         <tr><td align="left">Orden: '.$busca.' &nbsp;  Inst: '.$Ot[institucion].'</td></tr>
		         </tr></table><br>', true, 0, true, 1);

$Cadena = utf8_encode($Ot[texto]);
$Cadena = preg_replace('/[\r]/', '*', $Cadena);

$Lon  = strlen($Cadena);

$cTexto = '';

$Pos = 0;
while($Pos < $Lon){

	$Letra  = substr($Cadena,$Pos,1);

	if($Letra == '.'){
	   if(substr($Cadena,$Pos+1,1) == '*'){	//Cuando hay un punto y en seguida un carrier
		   $Letra = $Letra .'<br>';
	   }
	}elseif($Letra == '*'){
	   $Letra = "<br>";
	}elseif($Letra == ' '){
	   $Letra = '&nbsp; ';
	}

	$cTexto = $cTexto . $Letra;
	$Pos++;

}

$pdf->writeHTML($cTexto, true, 0, true, 1);

$pdf->writeHTML("<br><br><br>", true, 0, true, 1);

if($Ot[sexo] == 'F'){$Tit='Dra. ';}else{$Tit='Dr. ';}
$pdf->writeHTML('<p align="center"><font size="+3"><b>A t e n t a m e n t e</b></font></p><br>', true, 0, true, 1);
$pdf->writeHTML('<div align="center"><b>'.$Tit.$Ot[nombremed].'</b></div>', true, 0, true, 1);
$pdf->writeHTML('<div align="center"><b>'.$Ot[profesion].'</div>', true, 0, true, 1);
$pdf->writeHTML('<div align="center"><b>'.$Ot[cedula].'</div>', true, 0, true, 1);
ob_end_clean();
//Close and output PDF document
$pdf->Output();

?>
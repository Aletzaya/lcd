<?php

  #Librerias
  session_start();

  require("lib/lib.php");

  $link    =	conectarse();

//$htmlcontent = getHeadNews($db);

$doc_title    = "Formato";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
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
define ("PDF_HEADER_TITLE", "Impresion de resultados");
// Tamaño de la letra pero de header(titulos);
define ("PDF_FONT_SIZE_MAIN", 8);

//Tamaño de la letra del body('time','BI',8) BI la hace renegrida;
$pdf->SetFont('helvetica', '',8);

$pdf->SetHeaderData('logo.jpg','110', '','');
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

//Close and output PDF document
$pdf->Output();


?>
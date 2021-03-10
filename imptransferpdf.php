<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];
  $Team=$check['team'];

  $Titulo="Transferencia";

  $link=conectarse();

  $tamPag=10;

  $busca=$_REQUEST[busca];

  $Fecha=date("Y-m-d");
  $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30

  $HeA = mysql_query("SELECT *
         FROM trans
         WHERE id = '$busca'");

  $He=mysql_fetch_array($HeA);

//$htmlcontent = getHeadNews($db);

$doc_title    = "Transferencias";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');
require_once("importeletras.php");
require ("config.php");
//require_once('tcpdf2/tcpdf_include.php');

//create new PDF document (document units are set by default to millimeters)
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true,'UTF-8',false);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "A4");

$Team='LCD - Matriz';
$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','                                                                                                                                                 Movimiento al almacen                                                           Concepto: Transferencia');//define ("PDF_HEADER_LOGO", "logo_example.png");


//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Almacen");

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

$pdf->AddPage('','letter'); //Orientacion, tamaño de pagina

if($He[origen]=='invgral'){
  $De='General';
}elseif($He[origen]=='invmatriz'){
  $De='Matriz';
}elseif($He[origen]=='invtepex'){
  $De='Tepexpan';
}elseif($He[origen]=='invhf'){
  $De='HF';
}elseif($He[origen]=='invgralreyes'){
  $De='GralReyes';
}elseif($He[origen]=='invreyes'){
  $De='Reyes';
}elseif($He[origen]=='invcam'){
  $De='Camarones';
}

if($He[destino]=='invgral'){
  $Para='General';
}elseif($He[destino]=='invmatriz'){
  $Para='Matriz';
}elseif($He[destino]=='invtepex'){
  $Para='Tepexpan';
}elseif($He[destino]=='invhf'){
  $Para='HF';
}elseif($He[destino]=='invgralreyes'){
  $Para='GralReyes';
}elseif($He[destino]=='invreyes'){
  $Para='Reyes';
}elseif($He[destino]=='invcam'){
  $Para='Camarones';
}
$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<table align="center" width="100%" border="0"><tr><td align="left"><b> No.Transferencia: </b>'.$He[id].'</td><td align="left"><b>Fecha: </b>'.$He[fecha].' - '.$He[hora].'</td><td align="left"><b>Almacen De/Para: </b>'.$De.' / '.$Para.'</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$html='<table align="center" width="100%" border="0"><tr><td align="left"><b>Status: </b>'.$He[status].'</td><td align="left"><b>Usr.Entrg: </b>'.utf8_encode($He[usrorin]).'</td><td align="left"><b>Usr.Recb: </b>'.utf8_encode($He[usrdest]).'</td></tr></table>';

$pdf->writeHTML($html,true,false,true,false,'');

$html='<table align="center" width="100%" border="0"><tr style="background-color: #0d486a; color:#FFF;"><td align="center" width="150" height="30"><b> Producto </b></td><td align="center" width="500"><b> Descripcion </b></td><td align="center" width="150"><b> Cantidad </b></td><td align="center" width="170"><b> Costo </b></td><td align="center" width="170"><b> Importe </b></td></tr></table>';

$pdf->writeHTML($html,false,false,true,false,'');

$Sql = "SELECT transd.clave,transd.cantidad,transd.costo,invl.descripcion
       FROM transd, invl
       WHERE transd.id = '$busca' AND transd.clave = invl.clave
       ORDER BY transd.clave";

$Rolls = $Peso = $Defectos = 0;
$res   = mysql_query($Sql);

$pdf->SetFont('Helvetica', '', 7, '', 'false');

while( $rg = mysql_fetch_array($res) ){

  if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#e3f2ca';}

  $Sql2 = "SELECT *
       FROM invl
       WHERE invl.clave = '$rg[clave]'";

  $res2   = mysql_query($Sql2);
  $rg2 = mysql_fetch_array($res2);

  $html='<table align="center" width="100%" border="0"><tr style="background-color: '.$Fdo.';color: #000;"><td align="left" width="150" height="30">'.$rg[clave].'</td><td align="left" width="500">'.$rg[descripcion].'</td><td align="center" width="150">'.$rg[cantidad].'</td><td align="right" width="170">'.number_format($rg[costo]/$rg2[pzasmedida],"2").'</td><td align="right" width="170">'.number_format(($rg[costo]/$rg2[ pzasmedida])*$rg[cantidad],"2").'</td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  $Costo += $rg[costo]/$rg2[pzasmedida];  
  $Importe += ($rg[costo]/$rg2[pzasmedida])*$rg[cantidad];  
  $Cantidad += $rg[cantidad];  

  $nRng++;
                       
}   

$html='<table align="center" width="100%" border="0"><tr style="background-color: #0d486a; color:#FFF;"><td align="center" width="150" height="30"></td><td align="center" width="500"><b>Productos registrados: '.number_format($nRng,"0").'</b></td><td align="center" width="150"><b>'.number_format($Cantidad,"0").'</b></td><td align="right" width="170"><b>'.number_format($Costo,"2").'</b></td><td align="right" width="170"><b>'.number_format($Importe,"2").'</b></td></tr></table>';

  $pdf->writeHTML($html,true,false,true,false,'');

  $Imp = impletras($Importe,'pesos');

  $html='<table align="center" width="100%" border="0"><tr><td align="right" width="1050" height="30">'.$Imp.'</td></tr></table>';

  $pdf->writeHTML($html,true,false,true,false,'');


  $html='<table align="center" width="100%" border="0"><tr><td align="center" width="1050" height="30"></td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  $html='<table align="center" width="100%" border="0"><tr><td align="center" width="1050" height="30">____________________________________________</td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  $html='<table align="center" width="100%" border="0"><tr><td align="center" width="1050" height="30">Recibe: '.utf8_encode($He[usrdest]).'</td></tr></table>';

  $pdf->writeHTML($html,false,false,true,false,'');

  ob_end_clean();

$pdf->Output();

?>

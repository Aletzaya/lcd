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
require_once('tcpdf/tcpdf_include.php');

//create new PDF document (document units are set by default to millimeters)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set JPEG quality
$pdf->setJPEGQuality(75);

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

//	Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 15);
define ("PDF_MARGIN_BOTTOM", 15);


//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Impresion de resultados");
// Tamaño de la letra pero de header(titulos);
define ("PDF_FONT_SIZE_MAIN", 7);

//Tamaño de la letra del body('time','BI',8) BI la hace renegrida;
$pdf->SetFont('helvetica', '',10);

$pdf->SetHeaderData('logo2.jpg','70', '','');
//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetMargins(10, 7, 10);

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

$Estudio=$_REQUEST[Estudio];
$Orden=$_REQUEST[Orden];
$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$OtA=mysql_query("select ot.medico,cli.nombrec,cli.sexo,cli.fechan,med.nombrec,ot.medicon,ot.institucion,ot.diagmedico,
cli.afiliacion,ot.fecha 
from ot,cli,med 
where ot.orden='$Orden' and ot.cliente=cli.cliente and ot.medico=med.medico",$link);

$Ot=mysql_fetch_array($OtA);

if($Ot[medico] == 'MD'){
   $NombreMed = $Ot[medicon];
}else{

  //Medico que solicita el estudio
  $MedA      = mysql_query("SELECT nombrec FROM med WHERE medico='$Ot[medico]'");
  $Med       = mysql_fetch_array($MedA);
  $NombreMed = $Med[nombrec];

}

$Fecha=date("Y-m-d");
$Fecha2=date("Y-m-d H:i:s");
$Fechanac  = $Ot[fechan];
$array_nacimiento = explode ( "-", $Fechanac ); 
$array_actual = explode ( "-", $Fecha ); 
$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

if ($dias < 0){ 
	--$meses; 

	//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
	switch ($array_actual[1]) { 
		   case 1:     $dias_mes_anterior=31; break; 
		   case 2:     $dias_mes_anterior=31; break; 
		   case 3:  	$dias_mes_anterior=28; break; 
		   case 4:     $dias_mes_anterior=31; break; 
		   case 5:     $dias_mes_anterior=30; break; 
		   case 6:     $dias_mes_anterior=31; break; 
		   case 7:     $dias_mes_anterior=30; break; 
		   case 8:     $dias_mes_anterior=31; break; 
		   case 9:     $dias_mes_anterior=31; break; 
		   case 10:     $dias_mes_anterior=30; break; 
		   case 11:     $dias_mes_anterior=31; break; 
		   case 12:     $dias_mes_anterior=30; break; 
	} 

	$dias=$dias + $dias_mes_anterior; 
} 

//ajuste de posible negativo en $meses 
if ($meses < 0){ 
	--$anos; 
	$meses=$meses + 12; 
} 

if ($Ot[sexo]=='F'){ 
	$sexo='Femenino';
}else{
	$sexo='Masculino';
}

$Mes       = substr($Ot[fecha],5,2)*1;
$FechaLet  = substr($Ot[fecha],8,2)." de ".$aMes[$Mes]." del ".substr($Ot[fecha],0,4);


$pdf->writeHTML('<table border="0">
				 <tr>
				 	<td  rowspan="4" width="100%">&nbsp;</td>
		         	<td align="left" height="30" width="100%"><b>Paciente: '.utf8_encode($Ot[1]).'</b></td>
				 </tr>
		         <tr>
		         	<td align="left" height="30"><b>Edad: '.$anos.' A&ntilde;os '.$meses .' Meses &nbsp; &nbsp; &nbsp; Sexo: '.$sexo.'</b></td>
				 </tr>
		         <tr>
				 	<td align="left" height="30"><b>Medico: '.$NombreMed.'</b></td>
				 </tr>
		         <tr>
				 	<td align="left" height="30"><b>Orden: '.$Ot[institucion].' - '.$Orden.' &nbsp; Fecha: '.$FechaLet.'</b></td>
				 </tr>
		         </tr></table><br>', true, 0, true, 1);
				 
$EstA=mysql_query("select descripcion from est where estudio='$Estudio' ",$link);
$Est=mysql_fetch_array($EstA);

$pdf->writeHTML('<table border="0">
				 <tr>
		         	<td align="center" height="25" width="100%"><font size="+2"><b>'.$Est[descripcion].'</b></font></td>
		         </tr></table><br>', true, 0, true, 1);

$pdf->writeHTML('<table table width="100%" cellspacing="0" cellpadding="0" border="0">
				 <tr>
		         	<td align="center" height="15" width="100%" bgcolor="#cccccc"><font size="+1"><b>Elemento</b></font></td>
		         	<td align="center" height="15" width="100%" bgcolor="#cccccc"><font size="+1"><b>Resultado</b></font></td>
		         	<td align="center" height="15" width="100%" bgcolor="#cccccc"><font size="+1"><b>Ref. Min</b></font></td>
		         	<td align="center" height="15" width="100%" bgcolor="#cccccc"><font size="+1"><b>Ref. Max</b></font></td>
		         </tr></table>', true, false, true, false, 0);


$EleA=mysql_query("select * from ele where estudio='$Estudio' order by id",$link);

while($Ele=mysql_fetch_array($EleA)){
		$Id=$Ele[id];
		$ResA=mysql_query("select $Ele[tipo] from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
		$Res=mysql_fetch_array($ResA);

		$Resultado=$Res[0];
		if($Ele[tipo]=='l'){
			if($Res[0]=='N'){
				$Resultado='NEGATIVO';
			}else{
				$Resultado='POSITIVO';
			}
		}

		$pdf->writeHTML('<table width="100%" cellspacing="0" cellpadding="0" border="1">
			 <tr>
				<td align="center" height="40" width="100%">'.$Ele[descripcion].'</td>
				<td align="center" height="40" width="100%">'.$Resultado.'</td>
				<td align="center" height="40" width="100%">'.$Ele[min].'</td>
				<td align="center" height="40" width="100%">'.$Ele[max].'</td>
			 </tr>
			 	</table>', false, false, true, false, '');
}

$html = '<br><div style="text-align:center">
<img src="lib/cedula.png" alt="test alt attribute" width="350" height="200" border="0">
</div>';

$pdf->writeHTML($html, true, false, true, false, '');

$Usr = $_REQUEST[Usr];
$Up  = mysql_query("UPDATE otd SET creapdf = '$Fecha2 - $Usr'
		WHERE orden='$Orden' and estudio='$Estudio'"); 

//Close and output PDF document
$pdf->Output("estudiospdf/$Orden-$Estudio.pdf","F");
$pdf->Output();


?>
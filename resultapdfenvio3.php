<?php
  #Librerias
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  //$Suc    = $_COOKIE['TEAM'];        //Sucursal 
 $Usr=$check['uname'];
$Estudio=$_REQUEST[Estudio];
$Orden=$_REQUEST[Orden];
$alterno = $_REQUEST[alterno]; 
$Correo = $_REQUEST[correo]; 
$Correom = $_REQUEST[correom]; 
$Correoi = $_REQUEST[correoi]; 
$Entemailpac = $_REQUEST[entemailpac]; 
$Entemailmed = $_REQUEST[entemailmed]; 
$Entemailinst = $_REQUEST[entemailinst]; 

  $cEst    = $_REQUEST[Estudio];
  $busca   = $_REQUEST[Orden];

  $EstA	   = mysql_query("SELECT descripcion FROM est WHERE estudio='$cEst'");
  $Est	   = mysql_fetch_array($EstA);

  $OtA	   = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,
  		     ot.observaciones,ot.institucion,
  			 cli.sexo,cli.nombrec,cli.fechan,ot.medicon,medi.nombre as nombremed,otd.texto,
  			 medi.cedula,medi.profesion,medi.sexo, otd.letra,ot.suc
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
  $Fecha  = $Ot[fecha];

if ($Ot[institucion]<>'125'){

  $Ano    = substr($Fecha,2,2);
  $nMes   = substr($Fecha,5,2)*1;

  $aMes   = array("-","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

  if($Ot[suc]=='0'){
    $cFecha = "Texcoco, Edo. de Mexico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
  }elseif($Ot[suc]=='1'){
    $cFecha = "Texcoco, Edo. de Mexico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
  }elseif($Ot[suc]=='2'){
    $cFecha = "Texcoco, Edo. de Mexico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
  }elseif($Ot[suc]=='3'){
    $cFecha = "Tepexpan Acolman, Edo. de Mexico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
  }elseif($Ot[suc]=='4'){
    $cFecha = "Los Reyes, Edo. de Mexico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
  }elseif($Ot[suc]=='5'){
    $cFecha = "Azcapotzalco, Ciudad de M??xico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);
  }

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

// ********************  E N C A B E Z A D O  ****************


class MYPDF extends TCPDF {

    //Page header
    function Header() {
        
        $image_file2 = 'lib/DuranNvoBk.png';
        $this->Image($image_file2, 8, 5, 80, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    }

    // Page footer
    function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        $this->Cell(0, -35, 'Esta hoja forma parte del env??o por medio de un correo electr??nico, el fomato ORIGINAL podr?? ser solicitado en la sucursal donde se realiz?? el estudio.', 0, false, 'L', 0, '', 0, false, 'T', 'M');

        $this->SetY(-15);

        // Page number
        $this->Cell(0, -5, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }
}


// ******************** F I N     E N C A B E Z A D O  ****************

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

// set document information 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

define ("PDF_PAGE_FORMAT", "letter");

//  Define el tama??o del margen superior e inferior;
define ("PDF_MARGIN_TOP", 32);
define ("PDF_MARGIN_BOTTOM", 33);
// Tama??o de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Resultado".$Estudio);

//set margins
$pdf->SetMargins(12, PDF_MARGIN_TOP, 12);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

$pdf->setLanguageArray($l); //set language items
//initialize document
$pdf->AliasNbPages();

$pdf->AddPage('','letter'); //Orientacion, tama??o de pagina

$pdf->SetFont('Helvetica', '', 10, '', 'false');

$pdf->writeHTML('<table border="0"><tr><th align="right">'.$cFecha.' &nbsp; </th></tr></table><br>', true, 0, true, 1);

$pdf->writeHTML('<table border="0">
		         <tr><th align="left"><b>'.utf8_encode($Ot[nombrec]).'</b></th></tr>
		         <tr><td align="left">'.$cEst.': '.$Est[descripcion].'</td></tr>
		         <tr><td align="left">Dr(a).: '.utf8_encode($NombreMed).'</td></tr>
		         <tr><td align="left">Orden: '.$busca.' &nbsp;  Inst: '.$Ot[institucion].'</td></tr>
		         </tr></table><br>', true, 0, true, 1);

  $nota=$Ot[texto];
  $nota=nl2br($nota); //respeta salto de linea
  $nota=utf8_encode(ucfirst($nota)); //Coloca la primer letra en mayuscula en un parrafo
  $nota='<span style="text-align:justify;">'.$nota.'</span>';

$pdf->writeHTML($nota, true, 0, true, 1);

$pdf->writeHTML("<br><br><br>", true, 0, true, 1);

if($Ot[sexo] == 'F'){$Tit='Dra. ';}else{$Tit='Dr. ';}
$pdf->writeHTML('<p align="center"><font size="+3"><b>A t e n t a m e n t e</b></font></p><br>', true, 0, true, 1);
$pdf->SetFont('times', 'BI', 10, '', 'false');
$pdf->writeHTML('<div align="center"><b>'.$Tit.$Ot[nombremed].'</b></div>', true, 0, true, 1);

$pdf->SetFont('Helvetica', '', 10, '', 'false');
$pdf->writeHTML('<div align="center"> M??dico que interpreta </div>', true, 0, true, 1);

ob_end_clean();
//Close and output PDF document

$pdf->Output("estudiospdf/".$Orden."_".$Estudio.".pdf", 'F');
//header("Location: entregamail2.php?Orden=$Orden&Estudio=$Estudio&Correo=$Correo");
header("Location: enviocorreo.php?Orden=$Orden&Estudio=$Estudio&Correo=$Correo&Correom=$Correom&Correoi=$Correoi&entemailpac=$Entemailpac&entemailmed=$Entemailmed&entemailinst=$Entemailinst");

?>
<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $Usr = $_COOKIE[USERNAME];

  $FecI=$_REQUEST[FecI];

  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $HoraI=$_REQUEST[HoraI];

  $HoraF=$_REQUEST[HoraF];

  $Titulo=$_REQUEST[Titulo];
  
  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
  
  $SucursalD   = $_REQUEST[SucursalDe];

  if($SucursalD=="*"){
    $SucursalDe="";
  }else{
    $SucursalDe="and ot.suc=$SucursalD";
  }
  
  $SucursalP = $_REQUEST[Proveedor];

  if($SucursalP=="*"){
    $SucursalPara="";
  }else{
    $SucursalPara="and maqdet.mext='$SucursalP'";
  }

  $personal = $_REQUEST[personal];

  if($personal=="*"){
    $personale="";
  }else{
    $personale="and maqdet.usrenvext='$personal'";
  }

$Titulo = "Hoja de envio de Estudios - Orthin";

$cSql="SELECT maqdet.orden,maqdet.estudio,maqdet.mext,maqdet.fenvext,maqdet.henvext,maqdet.obsenv,ot.orden as ord,ot.suc,ot.institucion,ot.cliente,ot.medico,ot.medicon,cli.cliente,cli.apellidop,cli.apellidom,cli.nombre,cli.fechan,cli.sexo,est.estudio as estud,est.descripcion,est.clavealt,maqdet.usrenvext FROM maqdet, ot, cli, est WHERE maqdet.orden=ot.orden and maqdet.estudio = est.estudio AND ot.cliente = cli.cliente and maqdet.fenvext>='$FecI' and maqdet.fenvext <='$FecF' AND maqdet.henvext >='$HoraI' AND maqdet.henvext <='$HoraF' $SucursalDe $SucursalPara $personale order by maqdet.orden";

$UpA=mysql_query($cSql,$link);


$cSql2="SELECT * FROM authuser WHERE uname='$Usr'";

$UpA2=mysql_query($cSql2,$link);

$rg2=mysql_fetch_array($UpA2);

if($rg2[team]=='4'){
  $Sucur='20364';
}else{
  $Sucur='21134';
}

require ("config.php");

$doc_title    = "$Titulo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');

// ********************  E N C A B E Z A D O  ****************


class MYPDF extends TCPDF {

    //Page header
    function Header() {
    	global $FechaI,$FechaF,$Fecha,$Sucur;
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_file2 = 'lib/logoorthin.jpg';
        $this->Image($image_file2, 8, 5, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', 'B', 10);

        $this->writeHTML('<table border="0"><tr><td width="200">&nbsp;</td><td width="600"></td></tr><tr><td width="200">&nbsp;</td><td width="600"></td></tr><tr><td width="200">&nbsp;</td><td width="650" align="center">Solicitud de Estudios por Base</td></tr><tr><td width="200">&nbsp;</td><td width="600"></td></tr></table><hr>', false, 0);

        $this->SetFont('helvetica', 'B', 8);

        $this->writeHTML('<br><table width="100" border="0"><tr><td width="400"></td><td width="300">Nombre y Clave de laboratorio: </td><td width="400">Laboratorio Clinico Duran - '.$Sucur.' </td><td width="100">Fecha: </td><td width="300"> '.$Fecha.'</td></tr></table>', false, 0);

        $Tit1 = '<br><table border="1" width="98%"><tr align="center"><th width="20" rowspan="2"><b></b></th><th width="120" rowspan="2"><b><br />Apellido <br />Paterno</b></th><th width="120" rowspan="2"><b><br />Apellido<br />Materno</b></th><th width="200" rowspan="2"><b><br /><br />Nombre (s)</b></th><th width="100"><b><br />Sexo <br /></b></th><th width="100"><b><br />Fecha de Nacimiento</b></th><th colspan="2" width="200"><b><br />Toma de Muestra</b></th><th width="70" rowspan="2"><b><br />ID cliente <br /> o <br /> Paciente</b></th><th width="250" rowspan="2"><b><br />Nombre del Medico</b></th><th width="60" rowspan="2"><b><br />Clave de <br /> estudio</b></th><th rowspan="2"><b><br />Nombre del Estudio</b></th><th width="160" rowspan="2"><b><br />Observaciones</b></th></tr>';  

        $Tit1 .= '<tr align="center"><th width="100"><b> M=Masculino <br /> F=Femenino</b></th><th width="100"><b><br />(dd/mm/aaaa) </b></th><th width="100"><b>Fecha (dd/mm/aaaa)</b></th><th width="100"><b>Hora (hh:mm) (am o pm)</b></th></tr></table>';  

$this->SetFont('helvetica', '', 7);

$this->writeHTML($Tit1, false, 0);

    }

    // Page footer
    function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -35, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

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

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 50);
define ("PDF_MARGIN_BOTTOM", 18);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Reporte");

//set margins
$pdf->SetMargins(5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('helvetica', '', 8));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

$pdf->setLanguageArray($l); //set language items
//initialize document
$pdf->AliasNbPages();

$pdf->AddPage('L','letter'); //Orientacion P-Vertical L-Horizontal, tamaño de pagina

$pdf->SetFont('Helvetica', '', 6, '', 'false');

//***********   D A T O S   ***********

$Estudios=1;

$Tit2 = '<table border="1" width="98%">';

while($rg=mysql_fetch_array($UpA)) {

  if( ($Estudios % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo='#e3f2ca';}

//    $nommed=$rg2[nombrec];

  if($rg[medico]=='MD'){

    $nommed=$rg[medicon];

  }else{

      $cSql2="SELECT * FROM med WHERE med.medico='$rg[medico]'";

      $UpA2=mysql_query($cSql2,$link);

      $rg2=mysql_fetch_array($UpA2);  

      $nommed=$rg2[nombrec];

  }


  $cSql3="SELECT * FROM est WHERE est.estudio='$rg[estudio]'";

  $UpA3=mysql_query($cSql3,$link);

  $rg3=mysql_fetch_array($UpA3);  

  $cSql4="SELECT fechaest FROM otd WHERE otd.estudio='$rg[estudio]' and otd.orden='$rg[ord]'";

  $UpA4=mysql_query($cSql4,$link);

  $rg4=mysql_fetch_array($UpA4);  

  $ftoma=substr($rg4[fechaest], 0, 10);

  $fch2=explode("-",$ftoma);
  $tfecha2=$fch2[2]."-".$fch2[1]."-".$fch2[0];

  $htoma=substr($rg4[fechaest], 11, 5);

  $fch=explode("-",$rg[fechan]);
  $tfecha=$fch[2]."-".$fch[1]."-".$fch[0];

  $Tit3 .= '<tr style="background-color: '.$Fdo.';color: #000;" align="center"><th height="30" width="20"><br />'.$Estudios.'</th><th width="120"><br />'.utf8_encode(strtoupper($rg[apellidop])).'</th><th width="120"><br />'.utf8_encode(strtoupper($rg[apellidom])).'</th><th width="200"><br />'.utf8_encode(strtoupper($rg[nombre])).'</th><th width="100"><br />'.$rg[sexo].'</th><th width="100"><br />'.$tfecha.'</th><th width="100"><br />'.$tfecha2.'</th><th width="100"><br />'.$htoma.'</th><th width="70"><br /> '.$Sucur.' </th><th width="250"><br />'.$nommed.'</th><th width="60"><br />'.$rg[clavealt].'</th><th><br />'.$rg[descripcion].'</th><th width="160"></th></tr>'; 

  $Estudios++;

}

$Tit4 = '</table>';

$tbl = $Tit2.$Tit3.$Tit4;

$pdf->writeHTML($tbl, true, false, false, false, 'C');

// output the HTML content
ob_end_clean();
//Close and output PDF document
//$pdf->Output();

$pdf->Output("'Reporte'.pdf'", 'I');

?>


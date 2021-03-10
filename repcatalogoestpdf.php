<?php   
 function fetch_data()  
 {  
      session_start();

      require("lib/lib.php");

      $link=conectarse();

      if (!isset($_REQUEST[filtro])){
          $filtro = '*';
      }else{
          $filtro    = $_REQUEST[filtro];       
      }

      if (!isset($_REQUEST[filtro3])){
          $filtro3 = '*';
      }else{
          $filtro3    = $_REQUEST[filtro3];       
      }

      if (!isset($_REQUEST[filtro5])){
          $filtro5 = '*';
      }else{
          $filtro5    = $_REQUEST[filtro5];       
      }

      if (!isset($_REQUEST[filtro7])){
          $filtro7 = '*';
      }else{
          $filtro7    = $_REQUEST[filtro7];       
      }

      if (!isset($_REQUEST[filtro9])){
          $filtro9 = '*';
      }else{
          $filtro9    = $_REQUEST[filtro9];       
      }

      if (!isset($_REQUEST[filtro11])){
          $filtro11 = '*';
      }else{
          $filtro11    = $_REQUEST[filtro11];       
      }

      if (!isset($_REQUEST[filtro13])){
          $filtro13 = '*';
      }else{
          $filtro13    = $_REQUEST[filtro13];       
      }

      if (!isset($_REQUEST[filtro15])){
          $filtro15 = '*';
      }else{
          $filtro15    = $_REQUEST[filtro15];       
      }

      if (!isset($_REQUEST[filtro17])){
          $filtro17 = '*';
      }else{
          $filtro17    = $_REQUEST[filtro17];       
      }

      if (!isset($_REQUEST[filtro19])){
          $filtro19 = '*';
      }else{
          $filtro19    = $_REQUEST[filtro19];       
      }

      if (!isset($_REQUEST[filtro21])){
          $filtro21 = '*';
      }else{
          $filtro21    = $_REQUEST[filtro21];       
      }

      if (!isset($_REQUEST[filtro23])){
          $filtro23 = '*';
      }else{
          $filtro23    = $_REQUEST[filtro23];       
      }

      if($filtro<>'*'){
        $filtro2="and est.depto='$filtro'";
      }else{
        $filtro2=" ";
      }

      if($filtro3<>'*'){
        $filtro4="and est.subdepto='$filtro3'";
      }else{
        $filtro4=" ";
      }

      if($filtro5<>'*'){
        $filtro6="and est.proceso='$filtro5'";
      }else{
        $filtro6=" ";
      }

      if($filtro7<>'*'){
        $filtro8="and est.bloqbas='$filtro7'";
      }else{
        $filtro8=" ";
      }

      if($filtro9<>'*'){
        $filtro10="and est.activo='$filtro9'";
      }else{
        $filtro10=" ";
      }

      if($filtro11<>'*'){
        $filtro12="and est.bloqeqp='$filtro11'";
      }else{
        $filtro12=" ";
      }

      if($filtro13<>'*'){
        $filtro14="and est.bloqmue='$filtro13'";
      }else{
        $filtro14=" ";
      }

      if($filtro15<>'*'){
        $filtro16="and est.bloqcon='$filtro15'";
      }else{
        $filtro16=" ";
      }

      if($filtro17<>'*'){
        $filtro18="and est.bloqdes='$filtro17'";
      }else{
        $filtro18=" ";
      }

      if($filtro19<>'*'){
        $filtro20="and est.bloqadm='$filtro19'";
      }else{
        $filtro20=" ";
      }

      if($filtro21<>'*'){
        $filtro22="and est.bloqatn='$filtro21'";
      }else{
        $filtro22=" ";
      }

      if($filtro23<>'*'){
        $filtro24="and est.base='$filtro23'";
      }else{
        $filtro24=" ";
      }

      $output = '';  
      $sql = "SELECT * from est where est.estudio<>'' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 $filtro12 $filtro14 $filtro16 $filtro18 $filtro20 $filtro22 $filtro24 order by est.descripcion";  
      $result = mysql_query($sql, $link);  
      while($reg = mysql_fetch_array($result))  
      {  

        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;


      $deptoA=mysql_fetch_array(mysql_query("SELECT departamento,nombre FROM dep where dep.departamento='$reg[depto]'"));
      $depto = $deptoA[nombre];

      if($reg[activo]=='Si'){
        $st='Activo';
      }else{
        $st='Inactivo';
      }

      $regesthet='';  

      $conestA=mysql_query("SELECT * FROM conest where conest.estudio='$reg[estudio]'");

      // $regesthet=$conestA[conest];

      while ($regest = mysql_fetch_array($conestA)) {

        $regesthet=$regesthet.' '.$regest[conest];

      } 

    if($reg[bloqbas]=='Si'){
      $basico='<img alt="candadoc" src="images/candadoc.png" width="15" />';
    }else{
      $basico='';
    }

    if($reg[bloqeqp]=='Si'){
      $equipo='<img alt="candadoc" src="images/candadoc.png" width="15" />';
    }else{
      $equipo='';
    }

    if($reg[bloqmue]=='Si'){
      $muestra='<img alt="candadoc" src="images/candadoc.png" width="15" />';
    }else{
      $muestra='';
    }

    if($reg[bloqcon]=='Si'){
      $cont='<img alt="candadoc" src="images/candadoc.png" width="15" />';
    }else{
      $cont='';
    }

    if($reg[bloqdes]=='Si'){
      $descripcion='<img alt="candadoc" src="images/candadoc.png" width="15" />';
    }else{
      $descripcion='';
    }

    if($reg[bloqadm]=='Si'){
      $admin='<img alt="candadoc" src="images/candadoc.png" width="15" />';
    }else{
      $admin='';
    }

    if($reg[bloqatn]=='Si'){
      $atencion='<img alt="candadoc" src="images/candadoc.png" width="15" />';
    }else{
      $atencion='';
    }
       
      $nRng++;     
      $output .= '<tr><th width="40%">'.$nRng.'</th><th width="80%">'.$reg[estudio].'</th><th width="270%" align="left">'.$reg[descripcion].'</th><th width="100%">'.$depto.'</th><th width="100%">'.$reg[subdepto].'</th><th width="100%">'.$reg[proceso].'</th><th align="center">'.$st.'</th><th align="left" width="300%">'.$regesthet.'</th><th align="center" width="61%">'.$basico.'</th><th align="center" width="61%">'.$equipo.'</th><th align="center" width="61%">'.$muestra.'</th><th align="center" width="61%">'.$cont.'</th><th align="center" width="61%">'.$descripcion.'</th><th align="center" width="61%">'.$admin.'</th><th align="center" width="61%">'.$atencion.'</th><th align="center" width="90%">'.$reg[base].'</th></tr> 
                  ';  
      }  
      return $output;  
 }  


require ("config.php");

$Usr=$check['uname'];
$doc_title    = "$Titulo";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');

  // ********************  E N C A B E Z A D O  ****************

class MYPDF extends TCPDF {

    //Page header
    function Header() {
      global $FechaI,$FechaF;
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $image_file2 = 'lib/logo1.jpg';
        $this->Image($image_file2, 8, 5, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetFont('helvetica', 'B', 12);

        $this->writeHTML('<table width="100"><tr><th width="78"></th><th width="1000"></th></tr><tr><th width="78"></th><th width="1000"></th></tr><tr><th width="78">&nbsp;</th><th width="1000">Reporte de Catalogo de Estudios</th></tr></table><hr>', false, 0);


        $Tit = '<table width="100%" align="center" border="1"><tr bgcolor="#a2b2de"><th align="center" width="40%">#</th><th align="center" width="80%">Estudio</th><th align="left" width="268%">Descripcion</th><th align="center" width="100%">Depto</th><th align="center" width="100%">Subdepto</th><th align="center" width="100%">Proceso</th><th align="center">Status</th><th align="center" width="297%">Contenido</th><th align="center" width="60%">Bas</th><th align="center" width="60%">Eqp</th><th align="center" width="60%">Mue</th><th align="center" width="60%">Con</th><th align="center" width="60%">Desc</th><th align="center" width="60%">Adm</th><th align="center" width="60%">Atn</th><th align="center" width="90%">T.Est</th></tr></table>';

          $this->SetFont('helvetica', 'B', 9);

          $this->writeHTML($Tit, false, 0);

    }

    // Page footer
    function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, -55, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');

    }
}

      $obj_pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      $obj_pdf->SetCreator(PDF_CREATOR);  
      define ("PDF_MARGIN_TOP", 10);
      define ("PDF_MARGIN_BOTTOM", 18); 
      define ("PDF_HEADER_TITLE", "Catalogo de Estudios");
      //$obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");  
      //$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      //$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '28', PDF_MARGIN_RIGHT);  
      //$obj_pdf->setPrintHeader(false);  
      //$obj_pdf->setPrintFooter(false);  
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      $obj_pdf->SetFont('helvetica', '', 7);  
      $obj_pdf->AddPage('L','letter'); 

      $content = '<table width="100%" align="center" border="1">';  
      $content .= fetch_data();  
      $content .= '</table>';  
      $obj_pdf->writeHTML($content); 
      ob_end_clean(); 
      $obj_pdf->Output('Reporte.pdf', 'I');  
 
 ?>  

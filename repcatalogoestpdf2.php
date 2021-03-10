<?php
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

require ("config.php");

$Titulo = "Reporte de catalogo de estudios";

 function fetch_data()  
 {  
    $output = ''; 

     session_start();

    require("lib/lib.php");

    $link=conectarse();

    $cOtA="select * from est where est.estudio=BH";

    $OtA  = mysql_query($cOtA,$link);

      while($reg = mysql_fetch_array($OtA))  
      {   

          $deptoA=mysql_fetch_array(mysql_query("SELECT departamento,nombre FROM dep where departamento=$reg[depto]"));

          $depto = $deptoA[nombre];

          $nRng++;

      $output .= '
          <tr>
              <th>'.$nRng.'</th>
              <td>$reg[estudio]</td>
              <td>'.$reg[descripcion].'</td>
              <td>'.$depto.'</td>
              <td>'.$reg[subdepto].'</td>
              <td>'.$reg[proceso].'</td>
              <td align="center">'.$st.'</td>
              <td align="left">'.$regestdet.'</td>
              <td align="center">'.$basico.'</td>
              <td align="center">'.$equipo.'</td>
              <td align="center">'.$muestra.'</td>
              <td align="center">'.$cont.'</td>
              <td align="center">'.$descripcion.'</td>
              <td align="center">'.$admin.'</td>
              <td align="center">'.$atencion.'</td>
              <td align="center">'.$reg[base].'</td>
           </tr>  
       ';  
      }  
      return $output;  
 }  

 if(isset($_POST["create_pdf"]))  
 {  

  
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
    define ("PDF_MARGIN_TOP", 32);
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

    $pdf->SetFont('Helvetica', '', 7, '', 'false');

    //***********   D A T O S   ***********

    $content .= '  
        <table width="100%" align="center" border="1">
        <tr>
        <th align="center">#</th>
        <th align="center">Estudio</th>
        <th align="center" width="30%">Descripcion</th>
        <th align="center">Departamento</th>
        <th align="center">Subdepartamento</th>
        <th align="center">Proceso</th>
        <th align="center">Status</th>
        <th align="center">Contenido</th>
        <th align="center">Bas</th>
        <th align="center">Eqp</th>
        <th align="center">Mue</th>
        <th align="center">Con</th>
        <th align="center">Desc</th>
        <th align="center">Admin</th>
        <th align="center">Atn</th>
        <th align="center">T.Est</th>
        </tr>
    ';  
    $content .= fetch_data();  
    $content .= '</table>';  
    $obj_pdf->writeHTML($content);  
    $obj_pdf->Output('sample.pdf', 'I');  
 } 



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php
headymenu($Titulo, 0);

echo "<table align='center' width='90%' border='0'>";

echo "<td align='left'>$Gfont<b><font size='1'>Departamento</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
$depA=mysql_query("SELECT departamento,nombre FROM dep order by departamento");
while($dep=mysql_fetch_array($depA)){
    echo "<option value=$dep[departamento]> $dep[departamento]&nbsp;$dep[nombre]</option>";
    if($dep[departamento]==$filtro){$DesSuc=$dep[nombre];}
}
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro $DesSuc</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";

echo "<td align='left'>$Gfont<b><font size='1'>Subdepartamento</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro3' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
$subdepA=mysql_query("SELECT id,subdepto,nombre FROM depd where depd.departamento=$filtro order by id");
while($subdep=mysql_fetch_array($subdepA)){
    echo "<option value=$subdep[subdepto]> $subdep[id]&nbsp;$subdep[subdepto]</option>";
    if($subdep[subdepto]==$filtro3){$Desdep=$subdep[subdepto];}
}
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";

echo "<td align='left'>$Gfont<b><font size='1'>Proceso</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro5' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='TOMA SANGUINEA'>TOMA SANGUINEA</option>";
echo "<option value='RECOLECCION DE MUESTRA'>RECOLECCION DE MUESTRA</option>";
echo "<option value='REALIZACION DE ESTUDIOS'>REALIZACION DE ESTUDIOS</option>";
echo "<option value='TOMA DE MUESTRA CORPORAL'>TOMA DE MUESTRA CORPORAL</option>";
echo "<option value='SERVICIO'>'SERVICIO'</option>";
echo "<option value='MIXTO'>MIXTO</option>";
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";

echo "<td align='left'>$Gfont<b><font size='1'>Status</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro9' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Si'>Activo</option>";
echo "<option value='No'>Inactivo</option>";
    if($filtro9=='Si'){$Desta='Activo';}elseif($filtro9=='No'){$Desta='Inactivo';}else{$Desta='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desta</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Basicos</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro7' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro7=='Si'){$Desbas='Cerrado';}elseif($filtro7=='No'){$Desbas='Abierto';}else{$Desbas='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desbas</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Equipos</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro11' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro11=='Si'){$Deseqp='Cerrado';}elseif($filtro11=='No'){$Deseqp='Abierto';}else{$Deseqp='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Deseqp</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Muestras</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro13' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro13=='Si'){$Desmue='Cerrado';}elseif($filtro13=='No'){$Desmue='Abierto';}else{$Desmue='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desmue</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Cont</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro15' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro15=='Si'){$Descon='Cerrado';}elseif($filtro15=='No'){$Descon='Abierto';}else{$Descon='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Descon</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Descrip</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro17' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro17=='Si'){$Desdes='Cerrado';}elseif($filtro17=='No'){$Desdes='Abierto';}else{$Desdes='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desdes</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Admin</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro19' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro19=='Si'){$Desamd='Cerrado';}elseif($filtro19=='No'){$Desamd='Abierto';}else{$Desamd='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desamd</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Atn</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro21' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro21=='Si'){$Desatn='Cerrado';}elseif($filtro21=='No'){$Desatn='Abierto';}else{$Desatn='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desatn</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Tipo Est</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro23' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Individual'>Individual</option>";
echo "<option value='Asociado'>Asociado</option>";
echo "<option value='Agrupado'>Agrupado</option>";
echo "<option value='Mixto'>Mixto</option>";
echo "<option value='Asociado'>Combinado</option>";
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro23</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<form name='form2' method='get' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";
echo "</td><td aling='center'><a href=javascript:winuni('repcatalogoestpdf.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9')><font size='1'><b>PDF</b><font></a></td></tr></table>";

$Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";
echo "<table width='97%' align='center' border='0'>";
$Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";


$Tit = "<tr bgcolor='#a2b2de'><td align='center'>$Gfon #</td><td align='center'>$Gfon Estudio </td><td align='center' width='30%'>$Gfon Descripcion</td><td align='center'>$Gfon Departamento</td><td align='center'>$Gfon Subdepartamento</td><td align='center'>$Gfon Proceso</td><td align='center'>$Gfon Status</td><td align='center'>$Gfon Contenido</td><td align='center'>$Gfon Bas</td><td align='center'>$Gfon Eqp</td><td align='center'>$Gfon Mue</td><td align='center'>$Gfon Con</td><td align='center'>$Gfon Desc</td><td align='center'>$Gfon Admin</td><td align='center'>$Gfon Atn</td><td align='center'>$Gfon T.Est</td></tr>";

echo $Tit;

while ($reg = mysql_fetch_array($OtA)) {

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
    

    $deptoA=mysql_fetch_array(mysql_query("SELECT departamento,nombre FROM dep where dep.departamento=$reg[depto]"));
    $depto = $deptoA[nombre];

    if($reg[activo]=='Si'){
        $st='Activo';
    }else{
        $st='Inactivo';
    }

    $regestdet='';  

    $conestA=mysql_query("SELECT * FROM conest where conest.estudio='$reg[estudio]'");

   // $regestdet=$conestA[conest];

    while ($regest = mysql_fetch_array($conestA)) {

        $regestdet=$regestdet.'&nbsp'.$regest[conest];

    }

    if($reg[bloqbas]=='Si'){
      $basico="<img src='images/candadoc.png' border='0' width='15'>";
    }else{
      $basico=" ";
    }

    if($reg[bloqeqp]=='Si'){
      $equipo="<img src='images/candadoc.png' border='0' width='15'>";
    }else{
      $equipo=" ";
    }

    if($reg[bloqmue]=='Si'){
      $muestra="<img src='images/candadoc.png' border='0' width='15'>";
    }else{
      $muestra=" ";
    }

    if($reg[bloqcon]=='Si'){
      $cont="<img src='images/candadoc.png' border='0' width='15'>";
    }else{
      $cont=" ";
    }

    if($reg[bloqdes]=='Si'){
      $descripcion="<img src='images/candadoc.png' border='0' width='15'>";
    }else{
      $descripcion=" ";
    }

    if($reg[bloqadm]=='Si'){
      $admin="<img src='images/candadoc.png' border='0' width='15'>";
    }else{
      $admin=" ";
    }

    if($reg[bloqatn]=='Si'){
      $atencion="<img src='images/candadoc.png' border='0' width='15'>";
    }else{
      $atencion=" ";
    }
    
    $nRng++;

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td>$Gfont $nRng</td><td>$Gfont <a href=javascript:wingral('estudiopdf.php?busca=$reg[estudio]')><font size='1'> $reg[estudio]</a></td><td>$Gfont <a href=javascript:wingral('estudiose3.php?busca=$reg[estudio]')>$reg[descripcion]</a></font></td><td>$Gfont $depto</font></td><td>$Gfont $reg[subdepto]</font></td><td>$Gfont $reg[proceso]</font></td><td align='center'>$Gfont $st</font></td><td align='left'>$Gfont $regestdet</font></td><td align='center'>$Gfont $basico</font></td><td align='center'>$Gfont $equipo</font></td><td align='center'>$Gfont $muestra</font></td><td align='center'>$Gfont $cont</font></td><td align='center'>$Gfont $descripcion</font></td><td align='center'>$Gfont $admin</font></td><td align='center'>$Gfont $atencion</font></td><td align='center'>$Gfont $reg[base]</font></td></tr>";

}

echo "<tr bgcolor='#a2b2de'><td>&nbsp;</td><td>&nbsp;</td><td align='center' >$Gfont <b>Total de estudios:  $nRng </b></font></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

echo "</table>";

echo "<form method='post'>";
echo "<input type='submit' name='create_pdf' value='Create PDF' />";
echo "</form>";


mysql_close();
?>

</body>

</html>
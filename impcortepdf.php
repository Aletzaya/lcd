<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  require("importeletras.php");

  $link=conectarse();

  $level       = $check['level'];

  $Recepcionista=$_REQUEST[Recepcionista];
  $Recepcionista1=$_REQUEST[Recepcionista];

  if($Recepcionista=='*'){

    $Recepcionista=" ";

  }else{

    $Recepcionista=" and cja.usuario='$_REQUEST[Recepcionista]'";

  }
  
  $Sucursal     =   $_REQUEST[Sucursal];
  //$Sucursal     =   $Sucursal[0];
  $sucursalt = $_REQUEST[sucursalt];
  $sucursal0 = $_REQUEST[sucursal0];
  $sucursal1 = $_REQUEST[sucursal1];
  $sucursal2 = $_REQUEST[sucursal2];
  $sucursal3 = $_REQUEST[sucursal3];
  $sucursal4 = $_REQUEST[sucursal4];

  if($sucursalt=="1"){  
  
    $Sucursal="*";
    $Sucursal2= " * - Todas ";

  }else{
  
    if($sucursal0=="1"){  
      $Sucursal= " cja.suc=0";
      $Sucursal2= "Administracion - ";
    }
    
    if($sucursal1=="1"){ 
      $Sucursal2= $Sucursal2 . "Laboratorio - "; 
      if($Sucursal==""){
        $Sucursal= $Sucursal . " cja.suc=1";
      }else{
        $Sucursal= $Sucursal . " OR cja.suc=1";
      }
    }
    
    if($sucursal2=="1"){
      $Sucursal2= $Sucursal2 . "Hospital Futura - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " cja.suc=2";
      }else{
        $Sucursal= $Sucursal . " OR cja.suc=2";
      }
    }
    
    if($sucursal3=="1"){
      $Sucursal2= $Sucursal2 . "Tepexpan - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " cja.suc=3";
      }else{
        $Sucursal= $Sucursal . " OR cja.suc=3";
      }
    }
    
    if($sucursal4=="1"){
      $Sucursal2= $Sucursal2 . "Los Reyes - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " cja.suc=4";
      }else{
        $Sucursal= $Sucursal . " OR cja.suc=4";
      }
    }
  }

  $Fecha=$_REQUEST[Fecha];

  $Suc = $_COOKIE['TEAM'];        //Sucursal 

//$htmlcontent = getHeadNews($db);

$doc_title    = "Corte de caja";
$doc_subject  = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf2/config/lang/eng.php');
require_once('tcpdf2/tcpdf.php');
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

$Fechaact=date("Y-m-d");

$Hora=date("H:i");

$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

$Mes       = substr($Fechaact,5,2)*1;

$FechaLet  = substr($Fecha,8,2)." de ".$aMes[$Mes]." del ".substr($Fecha,0,4);


$Team='LCD - Matriz';
$pdf->SetHeaderData('logo.jpg', '45', '    Laboratorio Clinico Duran S.A. de C.V.','                                                                                                                                                 Corte de Caja: '.$_REQUEST[Recepcionista].'                                            '.$FechaLet.'');//define ("PDF_HEADER_LOGO", "logo_example.png");

//  Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 11);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Corte de Caja");

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

//$cSql="SELECT * FROM cja WHERE cja.fecha='$Fecha' '$Recepcionista' and ('$Sucursal') order by cja.orden";
if($Sucursal=="*"){
  $cSql2="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario,ot.institucion,ot.fecha as fechaot,ot.hora as horaorden,inst.condiciones,ot.suc FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND cja.orden=ot.orden and ot.institucion=inst.institucion and inst.condiciones='CREDITO' $Recepcionista order by ot.institucion,ot.orden,cja.id";
}else{
  $cSql2="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario,ot.institucion,ot.fecha as fechaot,ot.hora as horaorden,inst.condiciones,ot.suc FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND cja.orden=ot.orden and ot.institucion=inst.institucion and inst.condiciones='CREDITO' $and ($Sucursal) $Recepcionista order by ot.institucion,ot.orden,cja.id";
}

$UpA2=mysql_query($cSql2,$link);

$orden2=0;

while($registro2=mysql_fetch_array($UpA2)) {

        if($registro2[orden]<>$orden2){

          $VtasgralesI=$VtasgralesI+$registro2[importeot]; 

          $htmli= $htmli.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="270" line-height:"50%"; align="center">'.$registro2[institucion].' - '.$registro2[orden].'</td><td width="270" line-height:"50%"; align="right">'.number_format($registro2[importeot],'2').'</td></tr></table>';

        }

        $orden2=$registro2[orden];
}

if($Sucursal=="*"){
  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario,ot.institucion,ot.fecha as fechaot,ot.hora as horaorden,inst.condiciones,ot.suc FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND cja.orden=ot.orden and ot.institucion=inst.institucion and inst.condiciones='CONTADO' $Recepcionista order by ot.institucion,ot.orden,cja.id";

}else{
  $cSql="SELECT cja.id,cja.fecha,cja.orden,cja.hora,cja.importe,cja.tpago,ot.importe as importeot,ot.status,cja.usuario,ot.institucion,ot.fecha as fechaot,ot.hora as horaorden,inst.condiciones,ot.suc FROM cja,ot,inst WHERE cja.fecha='$Fecha' AND cja.orden=ot.orden and ot.institucion=inst.institucion and inst.condiciones='CONTADO' and ($Sucursal) $Recepcionista order by ot.institucion,ot.orden,cja.id";

}

$UpA=mysql_query($cSql,$link);

$Rgn=0;

$orden=0;

$institucion='';

$Vtasgrales=0;

while($registro=mysql_fetch_array($UpA)) {
  
    $Rgn++;

    if($registro[tpago]=='Efectivo'){

      $Efectivo=$Efectivo+$registro[importe];

    }elseif($registro[tpago]=='Tarjeta'){

      $Tarjeta=$Tarjeta+$registro[importe];

        $htmlT2= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="center">'.$registro[institucion].' - '.$registro[orden].'</td><td width="200" line-height:"50%"; align="right">'.number_format($registro[importe],'2').'</td></tr></table>';

        $htmlTar2=$htmlTar2.$htmlT2;

    }


    if($registro[fecha]==$registro[fechaot]){

      //$AbnA=mysql_query("SELECT sum(importe) as importe FROM cja WHERE orden='$registro[orden]' and cja.fecha <='$Fecha' $Recepcionista order by orden",$link);

      $AbnA=mysql_query("SELECT sum(importe) as importe FROM cja WHERE orden='$registro[orden]' and cja.fecha <='$Fecha' order by orden",$link);

      $Abonado=mysql_fetch_array($AbnA);

      $Adeudo=$registro[importeot]-$Abonado[importe];

      if($Adeudo>0.01){

        $Adeudos=$Adeudos+$Adeudo;

        if($institucion==''){

          $html5= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="center">'.$registro[institucion].' - '.$registro[orden].'</td><td width="200" line-height:"50%"; align="right">'.number_format($Adeudo,'2').'</td></tr></table>';
        $Adeudos2=$Adeudos2+$Adeudo;

        }elseif($institucion<>$registro[institucion]){

          $html5= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="right"><b>Total:</b></td><td width="200" line-height:"50%"; align="right"><b>$ '.number_format($Adeudos2,'2').'</b></td></tr></table>';

          $Adeudos2=0;
          
        }else{

          $html5= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="center">'.$registro[institucion].' - '.$registro[orden].'</td><td width="200" line-height:"50%"; align="right">'.number_format($Adeudo,'2').'</td></tr></table>';
            $Adeudos2=$Adeudos2+$Adeudo;
        }

        $html5= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="center">'.$registro[institucion].' - '.$registro[orden].'</td><td width="200" line-height:"50%"; align="right">'.number_format($Adeudo,'2').'</td></tr></table>';


          $html3 = $html3.$html5;

        $institucion=$registro[institucion];

      }   

      if($registro[orden]<>$orden){

          $DtoA=mysql_query("SELECT sum( precio * ( descuento /100 ) ) AS descuento,sum(precio) as precio FROM otd WHERE orden ='$registro[orden]' order by orden",$link);

          $Dto=mysql_fetch_array($DtoA); 

          if($Dto[descuento]>0){

              $Descto= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="center">'.$registro[institucion].' - '.$registro[orden].'</td><td width="200" line-height:"50%"; align="right">'.number_format($Dto[descuento],'2').'</td></tr></table>';

              $DescuentoT=$DescuentoT+$Dto[descuento];

          }else{

              $Descto='';
          }

          $DesctoT=$DesctoT.$Descto;   

          $Vtasgrales=$Vtasgrales+$registro[importeot];  

      }

    }else{

      $RecupB=mysql_query("SELECT sum(importe) as importe FROM cja WHERE orden='$registro[orden]' and cja.id<'$registro[id]' order by orden",$link);

      $RecupB=mysql_fetch_array($RecupB);

      if($RecupB[importe]>=1){

          $Recuperacion=$Recuperacion+$registro[importe];

          if($registro[tpago]=='Efectivo'){

            $Efectivor=$Efectivor+$registro[importe];

            $htmlE= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="center">'.$registro[institucion].' - '.$registro[orden].'</td><td width="200" line-height:"50%"; align="right">'.number_format($registro[importe],'2').'</td></tr></table>';

            $htmlEfe=$htmlEfe.$htmlE;

          }elseif($registro[tpago]=='Tarjeta'){

            $Tarjetar=$Tarjetar+$registro[importe];

            $htmlT= '<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="center">'.$registro[institucion].' - '.$registro[orden].'</td><td width="200" line-height:"50%"; align="right">'.number_format($registro[importe],'2').'</td></tr></table>';

            $htmlTar=$htmlTar.$htmlT;

          }

        }


    }

$orden=$registro[orden];

}

//$Efectivo='$ '.number_format($Efectivo),'2');



/*** T A B L A   P R I N C I P A L  ***/
$espacio= '<table border="0" cellspacing="0" cellpadding="0"><tr><td width="150" line-height:"50%";></td><td width="200" line-height:"50%";></td></tr></table>';

$Linea= '<table border="0" cellspacing="0" cellpadding="0" align="center"><tr><td width="550" line-height:"50%"; colspan="2">____________________________________________</td></tr></table>';

$ResponsableT= '<table border="0" cellspacing="0" cellpadding="0" align="center"><tr><td width="550" line-height:"50%"; colspan="2">Responsable de Turno / Nombre y Firma</td></tr></table>';

$ResponsableC= '<table border="0" cellspacing="0" cellpadding="0" align="center"><tr><td width="550" line-height:"50%"; colspan="2">Responsable de Corte / Nombre y Firma</td></tr></table>';




$pdf->SetFont('Helvetica', '', 9, '', 'false');

$html='<br><table border="0" cellspacing="0" cellpadding="0"><tr>';

$html= $html.'<td width="600" line-height:"50%";><table border="1" cellspacing="0" cellpadding="0"><tr><td width="150" line-height:"50%";>Vtas. Privado: </td><td width="200" line-height:"50%"; align="right">$ '.number_format($Vtasgrales,'2').'</td></tr></table>'.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="150" line-height:"50%";>Vtas. Institucion: </td><td width="200" line-height:"50%"; align="right">$ '.number_format($VtasgralesI,'2').'</td></tr></table>'.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="350" line-height:"50%"; bgcolor="#DAF7A6">Ventas Generales: </td><td width="200" line-height:"50%"; bgcolor="#DAF7A6" align="right">$ '.number_format($Vtasgrales+$VtasgralesI,'2').'</td></tr></table>'.$espacio.''.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="350" line-height:"50%"; bgcolor="#DAF7A6">Adeudos: </td><td width="200" line-height:"50%"; bgcolor="#DAF7A6" align="right">$ '.number_format($Adeudos,'2').'</td></tr></table>'.$espacio.''.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="350" line-height:"50%"; bgcolor="#DAF7A6">Descuentos: </td><td width="200" line-height:"50%"; bgcolor="#DAF7A6" align="right">$ '.number_format($DescuentoT,'2').'</td></tr></table>'.$espacio.''.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="150" line-height:"50%";>Recup. Efectivo: </td><td width="200" line-height:"50%"; align="right">'.number_format($Efectivor,'2').'</td></tr></table>'.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="150" line-height:"50%";>Recup. Tarjeta: </td><td width="200" line-height:"50%"; align="right">'.number_format($Tarjetar,'2').'</td></tr></table>'.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="350" line-height:"50%"; bgcolor="#DAF7A6">Total Recuperaciones: </td><td width="200" line-height:"50%"; bgcolor="#DAF7A6" align="right">$ '.number_format($Recuperacion,'2').'</td></tr></table>'.$espacio.''.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="350" line-height:"50%"; bgcolor="#DAF7A6">Total Efectivo: </td><td width="200" line-height:"50%"; bgcolor="#DAF7A6" align="right">$ '.number_format($Efectivo,'2').'</td></tr></table>'.$espacio.''.$espacio.'';

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="350" line-height:"50%"; bgcolor="#DAF7A6">Total Tarjeta: </td><td width="200" line-height:"50%"; bgcolor="#DAF7A6" align="right">$ '.number_format($Tarjeta,'2').' </td></tr></table>'.$espacio.''.$espacio.'';


//********* Letra *************//

$importetotal=$Efectivo+$Tarjeta;

$Letraimp=impletras($importetotal,"pesos ");

$Letra= '<table border="0" cellspacing="0" cellpadding="0" align="center"><tr><td width="550" line-height:"50%"; colspan="2">'.$Letraimp.'</td></tr></table>';

//********* Letra *************//


$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="350" line-height:"50%"; bgcolor="#DAF7A6">Total: </td><td width="200" line-height:"50%"; bgcolor="#DAF7A6" align="right">$ '.number_format($Efectivo+$Tarjeta,'2').'</td></tr></table>'.$espacio.''.$espacio.''.$espacio.''.$Letra.''.$espacio.''.$espacio.''.$espacio.''.$espacio.''.$Linea.''.$ResponsableT.''.$espacio.''.$espacio.''.$espacio.''.$espacio.''.$espacio.''.$Linea.''.$ResponsableC.''.$espacio.''.$espacio.''.$espacio.'';


//********* OTRAS INSTITUCIONES  **********

$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="540" line-height:"50%"; colspan="3" bgcolor="#DAF7A6" align="center">Otras Instituciones:</td></tr>';

$html= $html.'<tr><td width="270" line-height:"50%"; align="center">No. Ot.</td><td width="270" line-height:"50%"; align="center">Importe</td></tr></table>'.$htmli.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="270" line-height:"50%"; align="right"><b>Total Inst:</b></td><td width="270" line-height:"50%"; align="right"><b>$ '.number_format($VtasgralesI,'2').'</b></td></tr></table>'.$espacio.''.$espacio.'</td>';

//********* ADEUDOS  **********

$html= $html.'<td width="400" line-height:"50%";><table border="1" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%"; colspan="2" bgcolor="#DAF7A6"  align="center">Adeudos:</td></tr>';

$html= $html.'<tr><td width="200" line-height:"50%"; align="center">No. Ot.</td><td width="200" line-height:"50%"; align="center">Importe</td></tr></table>'.$html3.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="right"><b>Total Adeudos:</b></td><td width="200" line-height:"50%"; align="right"><b>$ '.number_format($Adeudos,'2').'</b></td></tr></table>'.$espacio.''.$espacio.'';





$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%"; colspan="2" bgcolor="#DAF7A6" align="center">Tarjetas:</td></tr>';

$html= $html.'<tr><td width="200" line-height:"50%"; align="center">No. Ot.</td><td width="200" line-height:"50%"; align="center">Importe</td></tr></table>'.$htmlTar2.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="right"><b>Total Tarjetas:</b></td><td width="200" line-height:"50%"; align="right"><b>$ '.number_format($Tarjeta,'2').'</b></td></tr></table>'.$espacio.''.$espacio.'';





$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%"; colspan="2" bgcolor="#DAF7A6" align="center">Recuperacion Efectivo:</td></tr>';

$html= $html.'<tr><td width="200" line-height:"50%"; align="center">No. Ot.</td><td width="200" line-height:"50%"; align="center">Importe</td></tr></table>'.$htmlEfe.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="right"><b>Total Recup. Efectivo:</b></td><td width="200" line-height:"50%"; align="right"><b>$ '.number_format($Efectivor,'2').'</b></td></tr></table>'.$espacio.''.$espacio.'';



$html= $html.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="400" line-height:"50%"; colspan="2" bgcolor="#DAF7A6" align="center">Recuperacion Tarjeta:</td></tr>';

$html= $html.'<tr><td width="200" line-height:"50%"; align="center">No. Ot.</td><td width="200" line-height:"50%"; align="center">Importe</td></tr></table>'.$htmlTar.'<table border="1" cellspacing="0" cellpadding="0"><tr><td width="200" line-height:"50%"; align="right"><b>Total Recup. Tarjeta:</b></td><td width="200" line-height:"50%"; align="right"><b>$ '.number_format($Tarjetar,'2').'</b></td></tr></table></td></tr></table>'.$espacio.'';



$pdf->writeHTML($html,false,false,true,false,'');

ob_end_clean();

$pdf->Output();

?>

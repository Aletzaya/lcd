<?php

  #Librerias
  session_start();
   
  require("lib/lib.php");
  $link=conectarse();
    
  $Ruta           = $_REQUEST[Ruta];
  $Mes            = $_REQUEST[Mes];
  $Institucion    = $_REQUEST[Institucion];
  $PeriodoI       = $_REQUEST[PeriodoI];
  $PeriodoF       = $_REQUEST[PeriodoF];
  $Medico         = $_REQUEST[Medico];
  
    
  $Cia     = "";
  $Dir     = "";                  
  
  $Elaboro= ucfirst(strtolower($Per[nombre]));
  

  $Fecha  = date('Y-m-d');  
  $Ano    = substr($Fecha,2,2);
  $nMes   = substr($Fecha,5,2)*1;
  
  //if($Cpo[servicio]==2){$MatPlg=" <b> Transporta Mat.y Res.Peligrosos</b>";}
  
  $aMes   = array("-","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
  $cFecha = "Texcoco Edo. de Mexico a: ". substr($Fecha,8,2)." de ".$aMes[$nMes]." del ".substr($Fecha,0,4);


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
$pdf->SetHeaderData('logo.jpg','30','Laboratorio Clinico Duran S.A. de C.V.','Fray Pedro de Gante #108 Texcoco Centro Tel. 95 41140 e-mail: rpublicas@dulab.com.mx');
                    
//define ("PDF_HEADER_LOGO", "lgo.jpg");

//	Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 22);
define ("PDF_MARGIN_BOTTOM", 15);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Impresion de formatos");
// Tamaño de la letra pero de header(titulos);
define ("PDF_FONT_SIZE_MAIN", 8);

//Tamaño de la letra del body('time','BI',8) BI la hace renegrida;
$pdf->SetFont('helvetica', '', 8);


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

$pdf->AddPage();

// set barcode
$pdf->SetBarcode(date("Y-m-d H:i:s", time()));
//$pdf->SetBarcode("Fabrica de Texcoco");
   

//Mando firma	
//														posc horiz,posc vert,ancho,alto,,,,,150 entre mas grande mas pkña la img
//																										agrandas este numero y reduces los otros 2


		if($Medico=="*"){
		
   		$cSql = "SELECT cmc.medico,med.nombrec,med.especialidad,med.dirconsultorio,med.locconsultorio,
  			med.m01,med.m02,med.m03,med.m04,med.m05,med.m06,med.m07,med.m08,med.m09,med.m10,med.m11,med.m12
   		FROM cmc,med,cli
   		WHERE 
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''
   		AND cmc.cliente=cli.cliente AND (cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4' OR cmc.inst = '10'
	      OR cmc.inst = '20' OR cmc.inst = '74') 
		   GROUP BY cmc.medico
		   ORDER BY cmc.medico, cmc.orden ";

	   }else{
   		$cSql = "SELECT cmc.medico,med.nombrec,med.especialidad,med.dirconsultorio,med.locconsultorio,
  			med.m01,med.m02,med.m03,med.m04,med.m05,med.m06,med.m07,med.m08,med.m09,med.m10,med.m11,med.m12
   		FROM cmc,med,cli,zns
   		WHERE 
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.medico=med.medico AND med.ruta='$Ruta' AND cmc.pagado=''
   		AND cmc.cliente=cli.cliente AND cmc.medico='$Medico' AND 
   		(cmc.inst = '1' OR cmc.inst ='3' OR cmc.inst = '4' OR cmc.inst = '10'
	      OR cmc.inst = '20' OR cmc.inst = '74') 
		   GROUP BY cmc.medico
		   ORDER BY cmc.medico, cmc.orden ";
	  }

$CpoA   = mysql_query($cSql);


while($Cpo=mysql_fetch_array($CpoA)){

   //if (file_exists($FrmGte) == true){       
   //    $pdf->Image('logo.jpg', 90, 220, 32, 32, '', '', '', true, 230);
   //}
   
   $pdf->writeHTML('<table border="0"><tr><th align="center"><b>Control de Visitas Medicas</b> </th></tr></table><br>', true, 0, true, 1);

   $pdf->writeHTML('<table border="0"><tr><th align="right">'.$cFecha.'</th></tr></table>', true, 0, true, 1);
	   
   $pdf->writeHTML("<p> </p><b>Medico: </b> ".$Cpo[medico]." &nbsp; ".$Cpo[nombrec]." &nbsp; &nbsp; &nbsp; <b>Especialidad:</b> ".$Cpo[especialidad]." &nbsp; &nbsp; &nbsp; <b> Sub-especialidad: </b>".$Cpo[subespecialidad],true,0); 

   $pdf->writeHTML("<br><b>Direccion: </b> ".$Cpo[dirconsultorio]." &nbsp; <b>Localidad: </b>".$Cpo[locconsultorio],true,0); 
   

   $pdf->writeHTML("<p>&nbsp;</p><b>Historial de pacientes por mes</b>",true,0); 
           
   $Num  = $nMes+1;  
   $dato = "";
   while($Num <= ($nMes+12) ){
        $DesMes = $aMes[$Num]; 
        $dato = $dato . '<th height="40" bgcolor="#b3b8ba" align="center" width="89">'.$DesMes.'</th>';
        $Num++;
   }    
   $Num = $nMes+1;  
   $dato = $dato . "</tr><tr>";
   while($Num <= ($nMes+12) ){
        //$DesMes = $aMes[$Num];
        if($Num > 12){$Pso = $Num - 12;}else{$Pso = $Num;} 
        $Campo  = "m".cZeros($Pso,2);
        $dato = $dato . '<th height="30" align="center" width="89">'.$Cpo[$Campo].' &nbsp; </th>';
        $Num++;
   }    
      
   $pdf->writeHTML('<table border="1" cellspacing="0" cellpadding="2"><tr>'.$dato.'</tr></table>', true, 0, true, 0);


   $pdf->writeHTML("<p>&nbsp;</p><b>Fecha de Visita:</b>_________________________ <b>Tiempos:</b> ____________________ <b>Entrevista:</b> ____________________",true,0); 


   $pdf->writeHTML("<p>&nbsp;</p><b>Guion Aplicado:</b>______________________________________________________________________________________________________",true,0); 


   $pdf->writeHTML("<p>&nbsp;</p><b>Comentario:</b>",true,0); 
   $pdf->writeHTML('<table border="1" cellspacing="0" cellpadding="2"><tr><td  height="150">&nbsp;</td></tr></table>', true, 0, true, 0);

   $pdf->writeHTML('<p> </p><p> </p><table border="0"><tr><th>&nbsp;</th><th align="right">FPM-02-01/01</th></tr></table>', true, 0, true, 1);


   //if (file_exists($FrmGte) == true){       
   //    $pdf->Image('logo.jpg', 90, 220, 32, 32, '', '', '', true, 230);
   //}

   //$pdf->writeHTML("<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>",true,0); 
   
   //$pdf->writeHTML('<table border="1" cellspacing="0" cellpadding="2"><tr><th height="100" width="200"> &nbsp; </th><th height="100" width="800">Laboratorio Clinico Duran S.A. de C.V.<br>Fray Pedro de Gante #108 Texcoco Centro Tel. 95 41140 e-mail: rpublicas@dulab.com.mx</th></tr></table>', true, 0, true, 0);


   $Cpo=mysql_fetch_array($CpoA);

   $pdf->writeHTML('<p>&nbsp;</p><br><p align="center"><font size="+2">Laboratorio Clinico Duran S.A. de C.V.</font></p>', true, 0, true, 1);
   $pdf->writeHTML('<p align="center">Fray Pedro de Gante #108 Texcoco Centro Tel. 95 41140 e-mail: rpublicas@dulab.com.mx</p><p>&nbsp;</p>', true, 0, true, 1);
   
   
   $pdf->writeHTML('<table border="0"><tr><th align="center"><b>Control de Visitas Medicas</b> </th></tr></table><br>', true, 0, true, 1);

   $pdf->writeHTML('<table border="0"><tr><th align="right">'.$cFecha.'</th></tr></table>', true, 0, true, 1);
	   
   $pdf->writeHTML("<p> </p><b>Medico: </b> ".$Cpo[medico]." &nbsp; ".$Cpo[nombrec]." &nbsp; &nbsp; &nbsp; <b>Especialidad:</b> ".$Cpo[especialidad]." &nbsp; &nbsp; &nbsp; <b> Sub-especialidad: </b>".$Cpo[subespecialidad],true,0); 

   $pdf->writeHTML("<br><b>Direccion: </b> ".$Cpo[dirconsultorio]." &nbsp; <b>Localidad: </b>".$Cpo[locconsultorio],true,0); 
   

   $pdf->writeHTML("<p>&nbsp;</p><b>Historial de pacientes por mes</b>",true,0); 
           
   $Num  = $nMes+1;  
   $dato = "";
   while($Num <= ($nMes+12) ){
        $DesMes = $aMes[$Num]; 
        $dato = $dato . '<th height="40" bgcolor="#b3b8ba" align="center" width="89">'.$DesMes.'</th>';
        $Num++;
   }    
   $Num = $nMes+1;  
   $dato = $dato . "</tr><tr>";
   while($Num <= ($nMes+12) ){
        //$DesMes = $aMes[$Num];
        if($Num > 12){$Pso = $Num - 12;}else{$Pso = $Num;} 
        $Campo  = "m".cZeros($Pso,2);
        $dato = $dato . '<th height="30" align="center" width="89">'.$Cpo[$Campo].' &nbsp; </th>';
        $Num++;
   }    
      
   $pdf->writeHTML('<table border="1" cellspacing="0" cellpadding="2"><tr>'.$dato.'</tr></table>', true, 0, true, 0);

   $pdf->writeHTML("<p>&nbsp;</p><b>Fecha de Visita:</b>_________________________ <b>Tiempos:</b> ____________________ <b>Entrevista:</b> ____________________",true,0); 


   $pdf->writeHTML("<p>&nbsp;</p><b>Guion Aplicado:</b>______________________________________________________________________________________________________",true,0); 


   $pdf->writeHTML("<p>&nbsp;</p><b>Comentario:</b>",true,0); 
   $pdf->writeHTML('<table border="1" cellspacing="0" cellpadding="2"><tr><td  height="150">&nbsp;</td></tr></table>', true, 0, true, 0);

   $pdf->writeHTML('<p> </p><p> </p><table border="0"><tr><th>&nbsp;</th><th align="right">FPM-02-01/01</th></tr></table>', true, 0, true, 1);

}

//Close and output PDF document
$pdf->Output();


?>
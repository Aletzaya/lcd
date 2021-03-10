<?php

  #Librerias
  session_start();
   
  require("lib/kaplib.php");

  $link  = conectarse();

  //header("Location: impeti.php?busca=$busca&pagina=$pagina");

  $Fecha=date("Y-m-d");
   
  $busca = $_REQUEST[busca];
 
  $Est   = $_REQUEST[Est];  
  
  $OtA   = mysql_query("SELECT ot.fecha,ot.cliente,cli.nombrec,cli.sexo,cli.fechan,ot.servicio,
           ot.institucion,ot.medico,ot.medicon 
           FROM ot,cli,inst 
           WHERE ot.orden='$busca' and ot.cliente=cli.cliente and inst.institucion=ot.institucion");
         //echo "select ot.fecha,ot.cliente,cli.nombrec from ot,cli where ot.orden='$busca' and ot.cliente=cli.cliente";

if($_REQUEST[op] =="1"){        //Para agregar uno nuevo;  
   
     $OtdA = mysql_query("SELECT otd.estudio,est.descripcion,est.depto,dep.nombre 
             FROM otd,est,dep 
             WHERE otd.estudio='$Est' AND otd.orden='$busca' AND est.estudio='$Est' AND dep.departamento=est.depto");
     //echo "select otd.estudio,est.descripcion,est.depto,dep.nombre from otd,est,dep where otd.estudio='$Est' and otd.orden='$busca' and est.estudio='$Est' and dep.departamento=est.depto";

	  $Up  = mysql_query("UPDATE otd set etiquetas = etiquetas + 1 
	         WHERE orden='$busca' and estudio='$Est'"); 
 
}else{		//Manda todos osea trae 3;
   
     $Fecha = date("Y-m-d");

     $Hora  = date("H:i");
   
     $OtdA = mysql_query("SELECT otd.estudio,otd.dos,otd.lugar,otd.uno 
             FROM otd
             WHERE otd.orden='$busca'");
             
     while($Otd  = mysql_fetch_array($OtdA)){        
          
	     if(substr($Otd[uno],0,4)=='0000'){
	        if($Otd[lugar] <= '2'){	         
              $lUp = mysql_query("UPDATE otd SET etiquetas = etiquetas + 1, lugar='2', uno='$Fecha $Hora' 
                     WHERE orden='$busca' AND estudio='$Otd[estudio]' limit 1");                     
           }else{           
              $lUp = mysql_query("UPDATE otd SET etiquetas = etiquetas + 1, uno='$Fecha $Hora' 
                     WHERE orden='$busca' AND estudio='$Otd[estudio]' limit 1");           
           }          
	     }else{	
	  
	       $Up   = mysql_query("UPDATE otd set etiquetas = etiquetas + 1 
	               WHERE orden='$busca' and estudio='$Otd[estudio]' limit 1");
	    }	
   
     }
   
   
     $OtdA = mysql_query("SELECT otd.estudio,est.descripcion,est.depto,dep.nombre 
             FROM otd,est,dep 
             WHERE otd.orden='$busca' AND otd.estudio=est.estudio AND est.depto=dep.departamento");
 
	  $Up  = mysql_query("UPDATE otd set etiquetas = etiquetas + 1 
	         WHERE orden='$busca'");
  
}

$Ot  = mysql_fetch_array($OtA);
	 
$MedA = mysql_query("SELECT nombrec FROM med WHERE medico='$Ot[medico]'");

$Med  = mysql_fetch_array($MedA); 

$Fecha2    = date("Y-m-d");
$fecha_nac = $Ot[fechan];
$dia       = substr($Fecha2, 8, 2);
$mes       = substr($Fecha2, 5, 2);
$anno      = substr($Fecha2, 0, 4);
$dia_nac   = substr($fecha_nac, 8, 2);
$mes_nac   = substr($fecha_nac, 5, 2);
$anno_nac  = substr($fecha_nac, 0, 4);

if($mes_nac>$mes){

	$calc_edad= $anno-$anno_nac-1;

}else{

	if($mes==$mes_nac AND $dia_nac>$dia){
		$calc_edad= $anno-$anno_nac-1; 
	}else{
		$calc_edad= $anno-$anno_nac;
	}

}

if($calc_edad>=200 or $calc_edad==0){
	$EDAD= "--- ";
}else{
	$EDAD= $calc_edad;
}


//$htmlcontent = getHeadNews($db);

$doc_title = "Formato";
$doc_subject = "recibos unicode";
$doc_keywords = "keywords para la busqueda en el PDF";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

//require_once("importeletras.php");

//create new PDF document (document units are set by default to millimeters)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

// **** Formato del tipo de hoja a imprimirdefine ("PDF_PAGE_FORMAT", "A4");

//Paramentro como LogotipoImg,,Nombre de la Empresa,Sub titulo
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
//$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, '           SERVICIOS TECNICOS Y ESPECIALIZADOS COPERNICO, S.A. DE C.V.','');
$pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, '');
//define ("PDF_HEADER_LOGO", "lgo.jpg");

//	Define el tamaño del margen superior e inferior;
define ("PDF_MARGIN_TOP", 3);
define ("PDF_MARGIN_BOTTOM", 15);
// Tamaño de la letra;
define ("PDF_FONT_SIZE_MAIN", 10);

//Titulo que va en el encabezado del archivo pdf;
define ("PDF_HEADER_TITLE", "Impresion de etiquetas");

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
   
$pdf->writeHTML("<font size='-1'>");   
while($Otd = mysql_fetch_array($OtdA)){

	//$pdf->writeHTML(str_repeat(" ",181)."Original-cliente", true, 0);

	//$pdf->writeHTML("<br><font size='-1'>      CONTRATO DE PRESTACION DE SERVICIOS DE INSPECCION Y/O VERIFICACION DE EMISION DE CONTAMINANTES", true, 0);

	//$pdf->writeHTML("<br>", true, 0);

   $pdf->writeHTML("                                     <b>No.Orden: $Ot[institucion] - $busca </b>", true, 0);
   
	$pdf->writeHTML("<br>", true, 0);
   
  	$pdf->writeHTML("$Ot[nombrec] ", true, 0);

	//$pdf->writeHTML("<br>", true, 0);
		
   if($Ot[servicio]=='Ordinaria'){

 	   $serv="Ordinaria";

	}else{
	
 	   if($Ot[servicio]=='Urgente'){
		
		   $serv="Urgente";
	
	   }else{
		
		   if($Ot[servicio]=='Express'){
			
			   $serv="Express";
			
			}else{
 
 			  if($Ot[servicio]=='Hospitalizado'){

 				  $serv="Hospitalizado";
			  }else{

				  $serv="Nocturno";
			  }
		  }
	  }
  }

  //$pdf->writeHTML("<br>", true, 0);

  $pdf->writeHTML("Edad: ".$EDAD." años       Sexo: $Ot[sexo]        $Ot[fecha]", true, 0);

  //$pdf->writeHTML("<br>", true, 0);

  $pdf->writeHTML(" $Otd[0]:".ucfirst(strtolower($Otd[1]))." - $serv", true, 0);

  $pdf->writeHTML("<br><br>", true, 0);
   
}




// output some content
//$pdf->SetFont("vera", "BI", 20);
//$pdf->Cell(0,10,"TEST Bold-Italic Cell",1,1,'C');

// output some UTF-8 test content
//$pdf->AddPage();
//$pdf->SetFont("FreeSerif", "", 12);
//$utf8text = file_get_contents("utf8test.txt", false); // get utf-8 text form file
//$pdf->SetFillColor(230, 240, 255, false);
//$pdf->Write(5,$utf8text, '', 1);


//Close and output PDF document
$pdf->Output();

//============================================================+
// END OF FILE                                                 
//============================================================+

?>
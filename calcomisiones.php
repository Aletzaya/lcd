
<?php

  session_start();

  require("lib/lib.php");
  $link        = conectarse();

  $Titulo      = "Genera comisiones por mes";

  if (isset($_REQUEST[FechaI])){
  
	  	$FechaI  = $_REQUEST[FechaI];
		$FechaF  = $_REQUEST[FechaF];

  }else{

      $FechaI = date("Y-m-")."01";
      $FechaF = date("Y-m-d");

  }	 

  if($_REQUEST[op]=='cc'){     

	  $Mes  = substr($FechaI,0,4).'-'.substr($FechaI,5,2);       
      		 
     $lUp  = mysql_query("DELETE FROM cmc WHERE mes='$Mes' AND tm='A'"); 		 
 	  
 	  $CpoA = mysql_query("SELECT ot.medico,ot.orden,ot.fecpago,otd.estudio,otd.descuento,otd.precio,
 	          (otd.precio*(1-(otd.descuento/100))) as importe,ot.cliente,
		       (importe*(med.comision/100)) as comisionA,med.comision,est.comision as estcomision,ot.institucion
        		 FROM ot,otd,med,est
		       WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.orden=otd.orden AND ot.medico=med.medico 
		       AND otd.estudio=est.estudio AND med.comision > 0 AND otd.estudio<>'INF-AB' AND otd.estudio<>'TOMCOV' AND ot.medico<>'MD' AND ot.medico<>'AQ'");

		       //AND otd.estudio=est.estudio AND med.comision > 0 AND otd.estudio<>'INF-AB' AND ot.pagada='Si'
		       
		$Medico = "XX";      
		 
      while($Cpo=mysql_fetch_array($CpoA)){
      
         if($Cpo[medico]<>$Medico OR $Cpo[orden]<>$Orden){
            if($Medico<>'XX'){
               //$Ord = $Orden;
  			      $lUp = mysql_query("INSERT INTO cmc (medico,mes,orden,tm,fecha,concepto,importe,comision,
  			             numestudios,cliente,inst) 
			             VALUES 
			             ('$Medico','$Mes','$Orden','A','$Fecha','$Estudios','$Importe','$Comision','$Num',
			             '$Cliente','$Inst')");
			   }          
  			   $Orden    = $Cpo[orden];
			   $Medico   = $Cpo[medico];			          
			   $Cliente  = $Cpo[cliente];
			   $Inst     = $Cpo[institucion];
			   $Estudios = "";
			   $Importe  = $Comision = $Num = 0;
			   $Fecha    = $Cpo[fecpago];
         }
         
         $Num ++;
			$Importe  += $Cpo[importe];
			$Estudios  = $Estudios.' '.$Cpo[estudio];
			if($Cpo[estcomision]>0){
			   $Comision += $Cpo[importe]*($Cpo[estcomision]/100);
			}else{   
			   $Comision += $Cpo[importe]*($Cpo[comision]/100);
			}   			    
	   }	

      header("Location: menu.php?cMes=$Mes&Msj='El calculo de comisiones fue creado con exito!'");	   	       
		       
	}


require ("config.php");							//Parametros de colores;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php

echo "<body bgcolor='#FFFFFF'>";

  headymenu($Titulo,1);


	 echo "<p> &nbsp; </p> $Gfont";
	 
    echo "<form name='form1' method='get' action='calcomisiones.php'>";

        echo "<p align='center'>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<INPUT TYPE='TEXT'  name='FechaI' size='9' value ='$FechaI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)> </p>";

        echo "<p align='center'>Fecha Final  [aaaa-mm-dd]: ";
        echo "<INPUT TYPE='TEXT'  name='FechaF' size='9' value ='$FechaF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)> </p>";

        echo "<p align='center'>&nbsp; &nbsp; &nbsp; <INPUT TYPE='SUBMIT' name='boton' value='Enviar'></p>";

		  echo "<input type='hidden' name='op' value='cc'>";
		  	
    echo "</form>";


echo "</body>";
echo "</html>";
  
mysql_close();

?>
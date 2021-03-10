<?php

  session_start();

  require("lib/importeletras.php");

  $Titulo  = "Recibos de comisiones";

  require("lib/lib.php");

  $link    = conectarse();

  $OrdenDef     = "";            //Orden de la tabla por default
  $tamPag       = 15;
  $nivel_acceso = 10; // Nivel de acceso para esta p�gina.

  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $Institucion = $_REQUEST[Institucion];
  $Medico      = $_REQUEST[Medico];
  $Status      = $_REQUEST[Status];
  $PeriodoI    = $_REQUEST[PeriodoI];
  $PeriodoF    = $_REQUEST[PeriodoF];
  $Ruta        = $_REQUEST[Ruta];
  
  if(isset($Ruta)){
  
  if($Institucion=="LCD"){
	  if($Medico=='*'){
   		$cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
         med.nombrec as nommedico,cli.nombrec,med.zona as zonas,zns.descripcion as nombrezona,cmc.concepto
   		FROM cmc,med,cli,zns
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.cliente=cli.cliente AND cmc.medico=med.medico AND med.zona=zns.zona 
   		AND cmc.inst <= '36' AND cmc.inst <> '2' AND cmc.inst <> '4'
	      AND cmc.inst <> '5' AND cmc.inst <> '6' AND cmc.inst <> '7' AND cmc.inst <> '8' 
	      AND cmc.inst <> '9' AND cmc.inst <> '10'
		   AND cmc.inst <> '11' AND med.ruta='$Ruta' AND cmc.pagado=''
		   ORDER BY cmc.medico, cmc.orden ";
	 }else{	   
   		$cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
         med.nombrec as nommedico,cli.nombrec,med.zona as zonas,zns.descripcion as nombrezona,cmc.concepto
   		FROM cmc,med,cli,zns
         WHERE
   		cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.cliente=cli.cliente AND cmc.medico=med.medico AND med.zona=zns.zona 
   		AND cmc.medico='$Medico' AND cmc.inst <= '36' AND cmc.inst <> '2' AND cmc.inst <> '4'
	      AND cmc.inst <> '5' AND cmc.inst <> '6' AND cmc.inst <> '7' AND cmc.inst <> '8' 
	      AND cmc.inst <> '9' AND cmc.inst <> '10'
		   AND cmc.inst <> '11' AND med.ruta='$Ruta' AND cmc.pagado='' 
		   ORDER BY cmc.medico, cmc.orden ";
    }
  }else{
  
  		if($Institucion=="*"){

     		if($Medico=="*"){
   		 $cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
          med.nombrec as nommedico,cli.nombrec,cmc.concepto
   		 FROM cmc,med,cli
          WHERE cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.cliente=cli.cliente AND cmc.medico=med.medico AND med.zona=zns.zona
          AND med.ruta='$Ruta' AND cmc.pagado=''
          ORDER BY cmc.medico, cmc.orden ";
		   }else{
   		 $cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
          med.nombrec as nommedico,cli.nombrec,cmc.concepto
   		 FROM cmc,med,cli,zns
		    WHERE cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.cliente=cli.cliente AND cmc.medico=med.medico AND med.zona=zns.zona 
		    AND cmc.medico='$Medico' AND med.ruta='$Ruta' AND cmc.pagado='' 
		    ORDER BY cmc.medico, cmc.orden ";
		  }
		  
		}else{
		
		  if($Medico=="*"){
   		 $cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
          med.nombrec as nommedico,cli.nombrec,med.zona as zonas,zns.descripcion as nombrezona,cmc.concepto
   		 FROM cmc,med,cli,zns
          WHERE
        	 cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.cliente=cli.cliente AND cmc.medico=med.medico AND cmc.inst='$Institucion' 
        	 AND med.zona=zns.zona AND med.ruta='$Ruta' AND cmc.pagado=''
        	 ORDER BY cmc.medico, cmc.orden ";
		  }else{
   		 $cSql = "SELECT cmc.inst,cmc.medico,cmc.orden,cmc.fecha,cmc.concepto,cmc.importe,cmc.comision,
          med.nombrec as nommedico,cli.nombrec,med.zona as zonas,zns.descripcion as nombrezona,cmc.concepto
   		 FROM cmc,med,cli,zns
          WHERE cmc.mes >= '$PeriodoI' AND cmc.mes <= '$PeriodoF' AND cmc.cliente=cli.cliente AND cmc.medico=med.medico AND cmc.medico='$Medico' 
          AND cmc.inst='$Institucion' AND med.zona=zns.zona AND med.ruta='$Ruta'  AND cmc.pagado='' 
          ORDER BY cmc.medico, cmc.orden ";
		  }
		}

	 }

  }
  

  $Usr = $HTTP_SESSION_VARS['usuario_login'];   

 require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<body bgcolor="#FFFFFF">

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php

   if(isset($Institucion)){ //Es que si enviaron la consulta;
      		  		       		  		 
      $rgA  = mysql_query($cSql);
      		  	echo $cSql;	  
      $Medico = "";
      
      $nRenglon = 0;
      		  		  
      while ($rg = mysql_fetch_array($rgA)){
				
			if($Medico<>$rg[medico]){

			   if($Medico<>''){

					$Letra = impletras($nCom," pesos ");

    				echo "<tr height='20'>";
					echo "<th with='10%' align='center'>$Gfont </th>";
   				echo "<th with='10%' align='center'>$Gfont </th>";
   				echo "<th with='10%' align='center'>$Gfont </th>";
   				echo "<th with='10%' align='center'>$Gfont $Letra </th>";
   				echo "<th with='10%' align='right'>$Gfont Total --------> </th>";
   				echo "<th with='30%' align='right'>$Gfont ".number_format($nImp,"2")."</th>";
   				echo "<th with='30%' align='right'>$Gfont ".number_format($nCom,"2")."</th>";
   				echo "<tr>";
        			echo "</table>";	        			
  					echo "<p align'center'>$Gfont Fecha/entrega: ________________ &nbsp; ";
  					echo " &nbsp; &nbsp; Quien recibe: __________________________________________";
  					echo " &nbsp; Firma: ______________________ </p>"; 
  					echo "<p>$Gfont Comentarios : __________________________________________________________________________________________________________</p>";
  					
  					$nRenglon += 3;
					if($nRenglon<30){$nLimite=30;}else{$nLimite=60;}
					while($nRenglon < $nLimite){
					    	echo "<p>&nbsp;</p>";
					    	$nRenglon++; 		         		        		        				   
					}  														
  					
			   }
			   
   			echo "<table width='100%' border='0'>";    //Encabezado
   			echo "<tr><td width='200' align='left'>";
   			echo "<img src='images/lgo.jpg' width='187' height='60'>";
   			echo "</td>";
   			echo "<td width='74%' align='center'>$Gfont <strong>Laboratorio Clinico Duran</strong><br>";
   			echo "$Gdir<br>";
   			echo "Recibo de pago de comisiones del periodo $Periodo<br>";
   			echo "</td></tr></table>";
   						   
            echo "<p>$Gfont <strong>Medico: $rg[medico].- $rg[nommedico] &nbsp; INST:_$rg[institucion] &nbsp; Zona: &nbsp; $rg[zonas] &nbsp; $rg[nombrezona]</strong></p>";

            //echo "<hr noshade style='color:3366FF;height:1px'>";
            echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
   			echo "<tr height='20'>";
				echo "<th with='10%' align='center'>$Gfont Inst. </th>";
   			echo "<th with='10%' align='center'>$Gfont Orden</th>";
   			echo "<th with='10%' align='center'>$Gfont Fecha</th>";
   			echo "<th with='10%' align='center'>$Gfont Paciente</th>";
   			echo "<th with='10%' align='center'>$Gfont Estudios</th>";
   			echo "<th with='30%' align='center'>$Gfont Importe</th>";
   			echo "<th with='10%' align='center'>$Gfont Comision</th>";
   			echo "<tr>";

				$Medico   = $rg[medico];
							
				$nImp     = $nCom = 0;
				
				$nRenglon = 5;

			}			
			
         echo "<tr height='20'><td align='right'>$Gfont $rg[inst] &nbsp; </td>";
         echo "<td>$Gfont $rg[orden]</td>";
         echo "<td>$Gfont $rg[fecha]</td>";
         echo "<td>$Gfont $rg[nombrec]</td>";
         echo "<td>$Gfont $rg[concepto]</td>";
         echo "<td align='right'>$Gfont ".number_format($rg[importe],"2")."</td>";
         echo "<td align='right'>$Gfont ".number_format($rg[comision],"2")."</td></tr>";
         $nImp += $rg[importe];
         $nCom += $rg[comision];
         $nRenglon++;
		}
		
   }else{		//Mando esto solo para no dejarlo en blanco;
   
     	echo "<table width='100%' height='80' border='0'>";    //Encabezado
   	echo "<tr><td width='26%' height='76'>";
   	echo "<p align=='left'><img src='images/lgo.jpg' width='187' height='61'></p>";
   	echo "</td>";
   	echo "<td width='74%' align='center'>$Gfont <strong>Laboratorio Clinico Duran</strong><br>";
   	echo "$Gdir<br>";
   	echo "Recibo de pago de comisiones del periodo $Periodo<br>";
   	echo "</td></tr></table>";			   
      echo "<p>$Gfont <strong>Medico: $rg[medico].- $rg[nommedico] &nbsp; INST:_$rg[institucion] &nbsp; Zona: &nbsp; $rg[zonas] &nbsp; $rg[nombrezona]</strong></p>";
      //echo "<hr noshade style='color:3366FF;height:1px'>";
      echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
   	echo "<tr>";
		echo "<th with='10%' align='center'>$Gfont Inst. </th>";
   	echo "<th with='10%' align='center'>$Gfont Orden</th>";
   	echo "<th with='10%' align='center'>$Gfont Fecha</th>";
   	echo "<th with='10%' align='center'>$Gfont Paciente</th>";
   	echo "<th with='10%' align='center'>$Gfont Estudios</th>";
   	echo "<th with='30%' align='center'>$Gfont Importe</th>";
   	echo "<th with='10%' align='center'>$Gfont Comision</th>";
   	echo "<tr>";

   }

   echo "<tr>";
	echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont </th>";
   echo "<th with='10%' align='center'>$Gfont $Letra </th>";
   echo "<th with='10%' align='right'>$Gfont Total --------> </th>";
   echo "<th with='30%' align='right'>$Gfont ".number_format($nImp,"2")."</th>";
   echo "<th with='30%' align='right'>$Gfont ".number_format($nCom,"2")."</th>";
   echo "<tr>";
   echo "</table>";	
  	echo "<div align'center'>$Gfont Fecha entrega: ___________________ &nbsp; &nbsp; Quien recibe: _________________________________________ &nbsp; Firma: _______________________ </div>"; 
   echo "<p>$Gfont Comentarios : __________________________________________________________________________________________________________</p>";

	echo "<p>&nbsp;</p>";
	 	
   echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

		  echo "<a href='menu.php'><img src='lib/regresa.jpg' border='0'></a> &nbsp; ";	

        echo "$Gfont Ruta: ";
  		  $RtaA=mysql_query("SELECT id,descripcion FROM ruta ORDER BY id");
        echo "<select name='Ruta'>";
        while ($Rta=mysql_fetch_array($RtaA)){
             echo "<option value=$Rta[id]> $Rta[descripcion]</option>";
             if($Rta[id]==$Ruta){$Des1=$Rta[descripcion];}
        }
        echo "<option selected value='$Ruta'>$Des1</option>";
        echo "</select> &nbsp; ";

        echo "Inicial: ";
        $CmcA = mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
        echo "<SELECT name='PeriodoI'>";
        while($Cmc=mysql_fetch_array($CmcA)){
             echo "<option value='$Cmc[0]'>$Cmc[0]</option>";
             if($Periodo=='$Cmc[mes]'){$Dsp1 = $Cmc[mes];}
        }
        echo "<option selected value='$Periodo'>$Periodo</option>";
        echo "</SELECT> &nbsp; ";

        echo "Final: ";
        $CmcA = mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
        echo "<SELECT name='PeriodoF'>";
        while($Cmc=mysql_fetch_array($CmcA)){
             echo "<option value='$Cmc[0]'>$Cmc[0]</option>";
             if($Periodo=='$Cmc[mes]'){$Dsp1 = $Cmc[mes];}
        }
        echo "<option selected value='$Periodo'>$Periodo</option>";
        echo "</SELECT>";

        echo " &nbsp; ";
        $InsA=mysql_query("SELECT institucion,nombre FROM inst");
        echo "<SELECT name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'> INSTITUCIONES LCD</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</SELECT>";
                
        echo " &nbsp; Medico, * todos: ";
        echo "<INPUT TYPE='TEXT'  name='Medico' size='4' value ='*'> &nbsp; ";
        /*
        echo "Medicos[Activo/Inactivo] : ";
        echo "<SELECT name='Status'>";
        echo "<option value='A'>Activo</option>";
        echo "<option value='I'>Inactivo</option>";
        echo "<option value=''>Ambos</option>";
        echo "</SELECT>";
		*/
        echo "&nbsp; &nbsp; <INPUT TYPE='SUBMIT' value='Enviar'>";

    echo "</form>";
   
   
	
   echo "<br>";

   echo "<div align='left'>";
   //echo "<form name='form1' method='post' action='pidedatos.php?cRep=4&fechas=1&FecI=$FecI&FecF=$FecF'>";
   echo "<form name='form1' method='post' action='menu.php'>";
   echo "         <input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
   echo "   </form>";
   echo "</div>";

echo "</body>";

echo "</html>";

mysql_close();




?>
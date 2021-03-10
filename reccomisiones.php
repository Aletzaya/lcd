<?php

  session_start();

  require("lib/importeletras.php");

  $Titulo  = "Relacion de comisiones";

  require("lib/lib.php");

  $link    = conectarse();

  $OrdenDef     = "";            //Orden de la tabla por default
  $tamPag       = 15;
  $nivel_acceso = 10; // Nivel de acceso para esta página.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }
  
  $FecI        = $_REQUEST[FecI];
  $FecF        = $_REQUEST[FecF];
  $Institucion = $_REQUEST[Institucion];
  $Medico      = $_REQUEST[Medico];
  $Status      = $_REQUEST[Status];
  
  if(!isset($Institucion)){
  
  if($Institucion=="LCD"){
   		$cSql="SELECT ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
        (importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,zns.descripcion as nombrezona,est.comision as estcomision
   		from ot,otd,med,cli,zns,est
        WHERE
   		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
        and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and ot.institucion <= '36' and ot.institucion <> '2' and ot.institucion <> '4'
	    and ot.institucion <> '5' and ot.institucion <> '6' and ot.institucion <> '7' and ot.institucion <> '8' and ot.institucion <> '9' and ot.institucion <> '10'
		and ot.institucion <> '11' and otd.estudio<>'INF-AB' order by ot.medico, otd.orden ";
  }else{
  		if($Institucion=="*"){

     		if($Medico=="*"){
		        $cSql="SELECT ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
        		(importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,zns.descripcion as nombrezona,est.comision as estcomision
		        from ot,otd,med,cli,zns,est
        		WHERE
		        ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
        		and med.comision > 0 and med.zona=zns.zona and otd.estudio<>'INF-AB' order by ot.medico, otd.orden ";
		     }else{
        		$cSql="SELECT ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
		        (importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,zns.descripcion as nombrezona,est.comision as estcomision
        		from ot,otd,med,cli,zns,est
		        WHERE
        		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
		        and med.comision > 0 and med.zona=zns.zona and ot.medico='$Medico' and otd.estudio<>'INF-AB' order by ot.medico, otd.orden ";
		     }
		}else{
		     if($Medico=="*"){
        		$cSql="SELECT ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
		        (importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,zns.descripcion as nombrezona,est.comision as estcomision
        		from ot,otd,med,cli,zns,est
		        WHERE
        		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
		        and med.comision > 0 and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' order by ot.medico, otd.orden ";
		     }else{
        		$cSql="SELECT ot.medico,ot.orden,ot.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
		        (importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,ot.institucion,med.zona as zonas,zns.descripcion as nombrezona,est.comision as estcomision
        		from ot,otd,med,cli,zns,est
		        WHERE
        		ot.fecha >= '$FecI' and ot.fecha <= '$FecF' and ot.orden=otd.orden and ot.cliente=cli.cliente and ot.medico=med.medico and otd.estudio=est.estudio
		        and med.comision > 0 and ot.medico='$Medico' and ot.institucion='$Institucion' and med.zona=zns.zona and otd.estudio<>'INF-AB' order by ot.medico, otd.orden ";
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

   echo "<table width='100%' height='80' border='0'>";    //Encabezado
   echo "<tr><td width='26%' height='76'>";
   echo "<p align=='left'><img src='images/Logotipo%20Duran4.jpg' width='187' height='61'></p>";
   echo "</td>";
   echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
   echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF </p>";
   echo "</td></tr></table>";
   echo "<p><strong><font size='1' face='Arial, Helvetica, sans-serif'>Medico: $registro[medico].- $registro[nommedico] &nbsp; INST:_$registro[institucion] &nbsp; Zona: &nbsp; $registro[zonas] &nbsp; $registro[nombrezona]</strong></p>";
   echo "<hr noshade style='color:3366FF;height:1px'>";

   echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
   echo "<tr>";
	echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Inst.</th>";
   echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</th>";
   echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</th>";
   echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</th>";
   echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</th>";
   echo "<th with='30%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</th>";
   echo "<th with='10%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Comision</th><tr>";

   if(!isset($Institucion)){
    
        $registro     = mysql_fetch_array($res);

        $Institucion  = $registro[institucion];
  		  $MedOrd       = $registro[medico].$registro[orden];
  		  
        if($registro[estcomision]>=1){ 
        
  			  $ComisionB = $registro[estcomision];

		  }else{

			  $Comision3 = $registro[precio]*(1-($registro[descuento]/100));	
			  $Comision2 = $Comision3*($registro[comision]/100);
			  $ComisionB = $Comision2;

		  }
		  
        $Estudios    = $registro[estudio];
		  $Contador    = 1;
  		  $ContadorEst = 0;
        if($registro[descuento] > 0){
           $Estudios="(DESCTO)".$registro[estudio];
           /*$Comision=0;*/
        }
        
        $Importe  = $registro[importe];
        $Medico   = $registro[medico];
        $Orden    = $registro[orden];
		  $Paciente = $registro[nombrec];
		  $Fecha    = $registro[fecha];

        while ($registro=mysql_fetch_array($res)){
             if($MedOrd     == $registro[medico].$registro[orden]){
                $Estudios    = $Estudios.", ".$registro[estudio];
                $Importe    += $registro[importe];
 				    $ContadorEst = $ContadorEst+1;
		          if($registro[estcomision]>=1){ 
					    $Comision2 = $registro[estcomision];
				    }else{
					    $Comision3 = $registro[precio]*(1-($registro[descuento]/100));	
					    $Comision2 = $Comision3*($registro[comision]/100);
				    }
				    
				    $ComisionB   += $Comision2;

             }else{
                 echo "<tr><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Institucion."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Fecha."</font></td>";
                 echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Paciente."</font></td>";
                 echo "<td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font></td>";
                 echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></td>";
                 echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ComisionB,'2')."</font></td></tr>";
				     $ImporteM  += $Importe; 
                 $ComisionM += $ComisionB;
				     $Contador   = $Contador+1;
   			     $ComisionB  = 0;
                 if($registro[medico]<>$Medico){   //Total del Medico
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>T o t a l e s : &nbsp; </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ComisionM,'2')."</strong></font></td></tr>";
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";
                    echo "</table>";

        			     echo "<table width='100%' height='80' border='0'>";    //Encabezado
                    echo "<tr><td width='26%' height='76'>";
                    echo "<p align=='left'><img src='images/Logotipo%20Duran4.jpg' width='187' height='61'></p>";
                    echo "</td>";
                    echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
                    echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF </p>";
                    echo "</td></tr></table>";

        			     echo "<p><strong>Medico: $registro[medico].- $registro[nommedico] &nbsp; INST:_$registro[institucion] &nbsp; Zona: $registro[zonas] &nbsp; $registro[nombrezona]</strong></p>";

        			     echo "<hr noshade style='color:3366FF;height:1px'>";

        			     echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
                    echo "<tr>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Inst.</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</th>";
                    echo "<th with='10%' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</th>";
                    echo "<th with='30%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</th>";
                    echo "<th with='10%' align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Comision</th><tr>";

                    $ImporteT  += $ImporteM;
                    $ComisionT += $ComisionM;
 				        $ContadorT += $ContadorM;

                    $ImporteM=0;
                    $ComisionM=0;
                 }
                 $Institucion = $registro[institucion];
				     $MedOrd      = $registro[medico].$registro[orden];
                 $Estudios    = $registro[estudio];
//				     $Comision=$registro[comision];
		           if($registro[estcomision]>=1){ 
					     $ComisionB=$registro[estcomision];
				     }else{
					     $Comision3=$registro[precio]*(1-($registro[descuento]/100));	
					     $Comision2=$Comision3*($registro[comision]/100);
					     $ComisionB+=$Comision2;
				     }
                 if($registro[descuento]>0){
                    $Estudios="(DESCTO)".$registro[estudio];
                    /*$Comision=0;*/
                 }
                 $Importe=$registro[importe];
                 $Medico=$registro[medico];
                 $Orden=$registro[orden];
 		 		     $Paciente=$registro[nombrec];
		 		     $Fecha=$registro[fecha];
            }
         }

         echo "<tr><td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Institucion."</font></td>";
         echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
         echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Fecha."</font></td>";
         echo "<td><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Paciente."</font></td>";
         echo "<td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font></td>";
         echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></td>";
         echo "<td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ComisionB,'2')."</font></td></tr>";
         
         $ImporteM+=$Importe;
         $ComisionM+=$ComisionB;
			$ContadorTEst=$Contador+$ContadorEst;

         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>T o t a l e s : &nbsp; </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteM,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ComisionM,'2')."</strong></font></td></tr>";
         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";
         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";

         $ImporteT+=$ImporteM;
         $ComisionT+=$ComisionM;

         echo "<tr><td>&nbsp;</td><td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>Pacientes: &nbsp; </strong></td><td align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;".number_format($Contador)."&nbsp;</strong></td><td align='center'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>&nbsp;E s t u d i o s : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".number_format($ContadorTEst)."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>G R A N &nbsp; T O T A L : </strong></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ImporteT,'2')."</strong></font></td><td align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>$&nbsp;".number_format($ComisionT,'2')."</strong></font></td></tr>";
         echo "</table>";

         echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
         echo "<tr>";
 		   echo "<tr>&nbsp;</tr>";
		   echo "<tr>&nbsp;</tr>";
	      echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Total Comision : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Letra=impletras($ComisionT,"pesos ");"</tr>";
		   echo "<tr>&nbsp;</tr>";
		   echo "<tr>&nbsp;</tr>";
  		   echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Fecha de entrega : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; _____________________________________ </tr>"; 
		   echo "<tr>&nbsp;</tr>";
 		   echo "<tr>&nbsp;</tr>";
  		   echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Nombre de quien recibe : &nbsp;&nbsp;_____________________________________ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 		 
   		echo "Firma : &nbsp;_____________________________________________ </tr>"; 
		   echo "<tr>&nbsp;</tr>";
		   echo "<tr>&nbsp;</tr>";
		   echo "<tr with='50%' align='left'><font size='2' face='Arial, Helvetica, sans-serif'>Comentarios : &nbsp;&nbsp;&nbsp;_________________________________________________________________________________________________________________________ </tr>"; 		 
		   echo "<tr>&nbsp;</tr>";
		   echo "</table>";
    }
	//fin while
	echo '</table>';

  }
  
   $FecI=$_REQUEST[FecI];

   $FecF=$_REQUEST[FecF];
   

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 
	 

	 echo "<p>&nbsp;</p>";
	 	
    echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

        echo "$Gfont Fecha Inicial: ";
        echo "<INPUT TYPE='TEXT'  name='FecI' size='9' value ='$FecI'>  &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> ";

        echo " &nbsp; Fecha Final: ";
        echo "<INPUT TYPE='TEXT'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> &nbsp; ";

        echo "Institucion : ";
        $InsA=mysql_query("SELECT institucion,nombre FROM inst");
        echo "<SELECT name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'> INSTITUCIONES LCD</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option></p>";
        echo "</SELECT>";
        
		  echo "<br><br>";        
        
        echo " &nbsp; Medico, * todos : ";
        echo "<INPUT TYPE='TEXT'  name='Medico' size='10' value ='*'> &nbsp; ";
        
        echo "Medicos[Activo/Inactivo] : ";
        echo "<SELECT name='Status'>";
        echo "<option value='A'>Activo</option>";
        echo "<option value='I'>Inactivo</option>";
        echo "<option value=''>Ambos</option>";
        echo "</SELECT>";

        echo "&nbsp; &nbsp; <INPUT TYPE='SUBMIT' value='Enviar'>";

    echo "</form>";
   
   
	
   echo "<br>";

   echo "<div align='left'>";
   echo "<form name='form1' method='post' action='pidedatos.php?cRep=4&fechas=1&FecI=$FecI&FecF=$FecF'>";
   echo "         <input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
   echo "   </form>";
   echo "</div>";

echo "</body>";

echo "</html>";

/*

  if($Institucion=="LCD"){
   		$cSql="SELECT cmc.medico,cmc.orden,cmc.fecha,otd.estudio,otd.descuento,otd.precio,(otd.precio*(1-(otd.descuento/100))) as importe,
        (importe*(med.comision/100)) as comisionA,med.comision,med.nombrec as nommedico,cli.nombrec,cmc.institucion,med.zona as zonas,
        zns.descripcion as nombrezona,est.comision as estcomision
   	  FROM cmc,otd,med,cli,zns,est
        WHERE
   	  cmc.fecha >= '$FecI' AND cmc.fecha <= '$FecF' AND cmc.orden=otd.orden AND cmc.cliente=cli.cliente 
   	  AND cmc.medico=med.medico AND otd.estudio=est.estudio
        AND med.comision > 0 AND med.zona=zns.zona AND cmc.medico='$Medico' AND cmc.institucion <= '36'
        AND cmc.institucion <> '2' AND cmc.institucion <> '4'
	     AND cmc.institucion <> '5' AND cmc.institucion <> '6' AND cmc.institucion <> '7' 
	     AND cmc.institucion <> '8' AND cmc.institucion <> '9' AND cmc.institucion <> '10'
		  AND cmc.institucion <> '11' AND otd.estudio<>'INF-AB' 
		  ORDER BY cmc.medico, otd.orden ";
  }else{
  		if($Institucion=="*"){

     		if($Medico=="*"){
		      $cSql="SELECT cmc.medico,cmc.orden,cmc.fecha,otd.estudio,otd.descuento,otd.precio,
		      (otd.precio*(1-(otd.descuento/100))) as importe,(importe*(med.comision/100)) as comisionA,
		      med.comision,med.nombrec as nommedico,cli.nombrec,cmc.institucion,med.zona as zonas,
		      zns.descripcion as nombrezona,est.comision as estcomision
		      FROM ot,otd,med,cli,zns,est
        		WHERE
		      cmc.fecha >= '$FecI' AND cmc.fecha <= '$FecF' AND cmc.orden=otd.orden AND cmc.cliente=cli.cliente 
		      AND cmc.medico=med.medico AND otd.estudio=est.estudio AND med.comision > 0 
		      AND med.zona=zns.zona AND otd.estudio<>'INF-AB' 
		      ORDER BY cmc.medico, otd.orden ";
		   }else{
        		$cSql="SELECT cmc.medico,cmc.orden,cmc.fecha,otd.estudio,otd.descuento,otd.precio,
        		(otd.precio*(1-(otd.descuento/100))) as importe,(importe*(med.comision/100)) as comisionA,
        		med.comision,med.nombrec as nommedico,cli.nombrec,cmc.institucion,med.zona as zonas,
        		zns.descripcion as nombrezona,est.comision as estcomision
        		FROM cmc,otd,med,cli,zns,est
		      WHERE
     		   cmc.fecha >= '$FecI' AND cmc.fecha <= '$FecF' AND cmc.orden=otd.orden AND cmc.cliente=cli.cliente 
     		   AND cmc.medico=med.medico AND otd.estudio=est.estudio AND med.comision > 0 
     		   AND med.zona=zns.zona AND cmc.medico='$Medico' AND otd.estudio<>'INF-AB' 
     		   ORDER BY cmc.medico, otd.orden ";
		   }
		}else{
		   if($Medico=="*"){
        		$cSql="SELECT cmc.medico,cmc.orden,cmc.fecha,otd.estudio,otd.descuento,otd.precio,
        		(otd.precio*(1-(otd.descuento/100))) as importe,(importe*(med.comision/100)) as comisionA,
        		med.comision,med.nombrec as nommedico,cli.nombrec,cmc.institucion,med.zona as zonas,
        		zns.descripcion as nombrezona,est.comision as estcomision
        		FROM cmc,otd,med,cli,zns,est
		      WHERE
        		cmc.fecha >= '$FecI' AND cmc.fecha <= '$FecF' AND cmc.orden=otd.orden AND cmc.cliente=cli.cliente 
        		AND cmc.medico=med.medico AND otd.estudio=est.estudio AND med.comision > 0 
        		AND cmc.institucion='$Institucion' AND med.zona=zns.zona AND otd.estudio<>'INF-AB' 
        		ORDER BY cmc.medico, otd.orden ";
		    }else{
        		$cSql="SELECT cmc.medico,cmc.orden,cmc.fecha,otd.estudio,otd.descuento,otd.precio,
        		(otd.precio*(1-(otd.descuento/100))) as importe,(importe*(med.comision/100)) as comisionA,
        		med.comision,med.nombrec as nommedico,cli.nombrec,cmc.institucion,med.zona as zonas,
        		zns.descripcion as nombrezona,est.comision as estcomision
        		FROM cmc,otd,med,cli,zns,est
		      WHERE
        		cmc.fecha >= '$FecI' AND cmc.fecha <= '$FecF' AND cmc.orden=otd.orden AND cmc.cliente=cli.cliente 
        		AND cmc.medico=med.medico AND otd.estudio=est.estudio
		      AND med.comision > 0 AND cmc.medico='$Medico' AND cmc.institucion='$Institucion' 
		      AND med.zona=zns.zona AND otd.estudio<>'INF-AB' 
		      ORDER BY cmc.medico, otd.orden ";
		   }
		}

	}
 }	  
*/

mysql_close();




?>
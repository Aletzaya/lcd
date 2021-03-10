<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $Institucion=$_REQUEST[Institucion];

  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

//  $Urgentes=$_REQUEST[Urgentes];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratoriio clinico</title>
</head>
<body>
<?php
//if($Urgentes == "S"){
	if(strlen($Institucion)>0){
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
    	$Titulo="Relacion de Pagos de Ordenes de trabajo del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec,  
		ot.institucion, ot.recepcionista, ot.hora, ot.importe,
		cja.orden as corden, cja.fecha as cfecha, cja.hora as chora, cja.usuario, cja.importe as cimporte
		FROM ot, cli, cja
		WHERE ot.fecha>='$Fechai' and ot.fecha <='$Fechaf' and ot.institucion='$Institucion' 
		AND ot.orden = cja.orden AND ot.cliente = cli.cliente
		order by ot.orden";
	}else{
    	$Titulo="Relacion de Pagos de Ordenes de trabajo del $Fechai al $Fechaf";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec,  
		ot.institucion, ot.recepcionista, ot.hora, ot.importe,
		cja.orden as corden, cja.fecha as cfecha, cja.hora as chora, cja.usuario, cja.importe as cimporte
		FROM ot, cli, cja
		WHERE ot.fecha>='$Fechai' and ot.fecha <='$Fechaf' 
		AND ot.orden = cja.orden AND ot.cliente = cli.cliente
		order by ot.orden";
	}
//}	

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" width="187" height="61"> 
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio 
        Clinico Duran</strong><br><font size="1">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "$Titulo"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='9'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Captura</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Abonos</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Adeudo</font></th>";
    echo "<tr><td colspan='9'><hr noshade></td></tr>";
    $Orden=0;
    $Importe=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
	$Estudios=0;
    while($rg=mysql_fetch_array($UpA)) {
    	if($Orden<>$rg[orden]){
    		if($Orden<>0){
				if($importeot-$Importe>=1){
					$Status="* * * A D E U D O * * * ";
					$letra="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
				}else{
					if($importeot-$Importe<=-1){
						$Status="* * * P A G A D O * * * ";
						$letra="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
					}else{
						$Status="* * * P A G A D O * * * ";
						$letra="<font color='#000000' size='1' face='Arial, Helvetica, sans-serif'>";
					}
				}

	    		echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'></font></th>";
	    		echo "<th align='left'>$letra<strong><u>$Status</u></strong></font></th>";
			    echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'></font></th>";
			    echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'></font></th>";
				echo "<th align='right'><hr>$letra Total OT: $</font></th>";
			  	echo "<th align='right'><hr>$letra $&nbsp;&nbsp;".number_format($importeot,'2')."&nbsp; </font></th>";
   				echo "<th align='right'><hr>$letra".number_format($Importe,'2')."</font></th>";
			   	echo "<th align='right'><hr>$letra".number_format(($importeot)-($Importe),'2')."&nbsp; </font></th>";
    			echo "</tr>";
    			
    			echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
 				$ImporteT+=$importeot;
     			$ImporteTA+=$Importe; 
    			$Importe=0;
    			$Descuento=0;  
    			$Ordenes++;
    		}
			$Rec=$rg[recepcionista];
    		echo "<tr>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
    		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[nombrec]</font></th>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[fecha]</font></th>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[hora]</font></th>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[recepcionista]</font></th>";
			echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($rg[importe],'2')."</font></th>";
			echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></th>";
    		echo "</tr>";
    	    $Orden=$rg[orden];
    	    $importeot=$rg[importe];
    	}
		echo "<tr>";
		echo "<th>";
		echo "<th>";
		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[cfecha]</font></th>";
		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[chora]</font></th>";
		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[usuario]</font></th>";
		echo "<th>";
   		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($rg[cimporte],'2')."</font></th>";
   		echo "</tr>"; 
		$Estudios++;
   		$Importe+=$rg[cimporte]; 
     }

		if($importeot-$Importe>=1){
			$Status="* * * A D E U D O * * * ";
			$letra="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			if($importeot-$Importe<=-1){
				$Status="* * * P A G A D O * * * ";
				$letra="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
			}else{
				$Status="* * * P A G A D O * * * ";
				$letra="<font color='#000000' size='1' face='Arial, Helvetica, sans-serif'>";
			}
		}

   	 echo "<tr>";
	 echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'></font></th>";
	 echo "<th align='left'>$letra<strong><u>$Status</u></strong></font></th>";
	 echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'></font></th>";
	 echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'></font></th>";
	 echo "<th align='right'><hr>$letra Total OT: $</font></th>";
	 echo "<th align='right'><hr>$letra $&nbsp;&nbsp;".number_format($importeot,'2')."&nbsp; </font></th>";
   	 echo "<th align='right'><hr>$letra".number_format($Importe,'2')."</font></th>";
	 echo "<th align='right'><hr>$letra".number_format(($importeot)-($Importe),'2')."&nbsp; </font></th>";
     echo "</tr>";
    		
	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
   	 $Ordenes++;		
 	 $ImporteT+=$importeot;
     $ImporteTA+=$Importe; 

   	
     echo "<tr><td colspan='9'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Abonos : $Estudios</font></th>";
     echo "<th></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>GRAN TOTAL --> $</font></th>";
     echo "<th></th>";
	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ImporteT,'2')."</font></th>";
   	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($ImporteTA,'2')."</font></th>";
   	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($ImporteT-$ImporteTA,'2')."</font></th>";
     echo "</tr>"; 

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];

              
     echo "</table>";
	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=3&fechas=1&FecI=$FecI&FecF=$FecF'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
?>
</font></font> 
<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=3&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
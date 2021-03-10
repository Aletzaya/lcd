<?php

  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  require("lib/kaplib.php");

  $link			=	conectarse();
  $Usr			=	$check['uname'];
  $busca		=	$_REQUEST[busca];
  $Sucursal             =	$_REQUEST[Sucursal];
  $Institucion          =       $_REQUEST[Institucion];
  $FecI			=	$_REQUEST[FecI];
  $FecF			=	$_REQUEST[FecF];

  $Fechai		=	$FecI;
  $Fechaf		=	$FecF;

  $Titulo		=	$_REQUEST[Titulo];

  $Urgentes		=	$_REQUEST[Urgentes];

  $Servicio             =	$_REQUEST[Servicio];	//1.todos 2.Urgentes 3.Express
  $DesctoS		=	$_REQUEST[Descto];

  $Fecha		=	date("Y-m-d");
  $Hora			=	date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php
//if($Depto=="*"){

if($Sucursal <> '*'){
    $CiaA = mysql_query("SELECT nombre FROM cia WHERE id='$Sucursal'");
    $Cia = mysql_fetch_array($CiaA);
    $TitSuc = " $Sucursal $Cia[nombre]";
}else{
    $TitSuc = " * todas, ";
}

if($Institucion <> '*'){
  $NomA   = mysql_query("select nombre from inst where institucion=$Institucion",$link);
  $Nombre = mysql_fetch_array($NomA);
  $TitIns = " Institucion : $Institucion $Nombre[0] ";
}else{
  $TitIns = " Institucion : * todas ";    
}   

if($Sucursal == '*'){  

    
   if($Institucion =='*'){       
      $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal : * todas, ".$TitIns;
      $cWhere =  " ot.fecha>='$Fechai' AND ot.fecha <='$Fechaf' AND
                 ot.orden = otd.orden AND otd.estudio = est.estudio AND est.depto='$_REQUEST[Depto]' AND
                 ot.medico=med.medico ";      
   }else{
      $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf Sucursal : * todas, ".$TitIns;
      $cWhere =  " ot.fecha>='$Fechai' AND ot.fecha <='$Fechaf' AND
                 ot.orden = otd.orden AND otd.estudio = est.estudio AND est.depto='$_REQUEST[Depto]' AND
                 ot.medico=med.medico AND ot.institucion='$Institucion'";             
   }         

   
}else{      //Con sucursal;
    
   if($Institucion =='*'){       
      $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf $TitSuc $TitIns";
      $cWhere =  " ot.suc='$Sucursal' AND ot.fecha>='$Fechai' AND ot.fecha <='$Fechaf' AND
                 ot.orden = otd.orden AND otd.estudio = est.estudio AND est.depto='$_REQUEST[Depto]' AND
                 ot.medico=med.medico ";      
   }else{
      $Titulo = "Relacion de Ordenes de trabajo del $Fechai al $Fechaf $TitSuc $TitIns";
      $cWhere =  " ot.suc='$Sucursal' AND ot.fecha>='$Fechai' AND ot.fecha <='$Fechaf' AND
                 ot.orden = otd.orden AND otd.estudio = est.estudio AND est.depto='$_REQUEST[Depto]' AND
                 ot.medico=med.medico AND ot.institucion='$Institucion'";             
   }             
    
}



$cSql = "SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio,
          otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec,
          ot.institucion,
          ot.recepcionista, ot.hora, ot.servicio, ot.fechae, ot.descuento as descto
          FROM otd, est, med, ot
          LEFT JOIN cli ON ot.cliente=cli.cliente
          WHERE ".$cWhere."
          ORDER BY ot.orden";


$UpA = mysql_query($cSql);

?>
<table width="100%" border="0">
  <tr>
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" width="187" height="61">
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio Clinico Duran</strong><br>
        <font size="1">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "$Titulo"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</font></th>";
	echo "<th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Precio</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Desc. %</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</font></th>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    $Orden=0;
    $Importe=0;
    $Descuento=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
	$Estudios=0;
    while($rg=mysql_fetch_array($UpA)) {
    	if($Orden<>$rg[orden]){
    		if($Orden<>0){
	    		echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;</font></th>";
	    		echo "<th align='left'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med2</font></th>";
			    echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'><strong><u>$Urgencia</u></strong></font></th>";
				echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total OT: $</font></th>";
   				echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></th>";
   				echo "<th align='center'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento)."</font></th>";
   				echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($Importe-$Descuento,'2')."&nbsp; </font></th>";
    			echo "</tr>";

    			echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
 				$ImporteT+=$Importe;
 				$DescuentoT+=$Descuento;
    			$Importe=0;
    			$Descuento=0;
    			$Ordenes++;
				$Med1="A";
				$Rec="B";
				$Urge2=0;
    		}
			$Rec=$rg[recepcionista];
    		echo "<tr>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
    		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2] <br> &nbsp; Afiliacion: $rg[3]</font></th>";
    		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha Cap.: $rg[fecha]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha Ent.: $rg[fechae]</font></th>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Hora Cap.: $rg[hora]</font></th>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif' color='#CCCCCC'>$Rec</font></th>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descto]</font></th>";
			echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[servicio]</font></th>";
    		echo "</tr>";
    	    $Orden=$rg[orden];
    	}
		echo "<tr>";
		echo "<th>";
		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]&nbsp;-&nbsp;</font></th>";
		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]&nbsp;</font></th>";
		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'></font></th>";
   		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($rg[precio],'2')."</font></th>";
   		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($rg[descuento])."</font></th>";
   		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($rg[8],'2')."</font></th>";
   		echo "</tr>";
		$Estudios++;
   		$Importe+=$rg[precio];
   		$Descuento+=($rg[precio]*($rg[descuento]/100));
		$Med=$rg[medico];
		if($rg[estudio]=="URG"){
			$Urge=1;
		}else{
			$Urge=0;
		}
		$Urge2=$Urge2+$Urge;
    	if($Med1<>$Med){
			$Med1=$Med;
			$Med2=$rg[nombrec];
	    	$Med3=$rg[medicon];
			if($Med1=="MD"){
	    		$Med2=$Med3;
			}
		}
    	if($rg[servicio]=="Urgente" or $Urge2<>0){
			$Urgencia="* * *  U R G E N C I A  * * * ";
		}else{
			$Urgencia=" ";
		}

     }

   	 echo "<tr>";
     echo "<th align='right'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med1&nbsp;-&nbsp;</font></th>";
  	 echo "<th align='left'><font color='#000000' size='1' face='Courier New, Courier, mono'>$Med2</font></th>";
     echo "<th align='left'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'><strong><u>$Urgencia</u></strong></font></th>";
	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>Total OT: $</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></th>";
   	 echo "<th align='center'><hr><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuento)."</font></th>";
   	 echo "<th align='right'><hr><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($Importe-$Descuento,'2')."</font></th>";
     echo "</tr>";

	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
   	 $Ordenes++;
 	 $ImporteT+=$Importe;
 	 $DescuentoT+=$Descuento;

     echo "<tr><td colspan='8'><hr noshade></td></tr>";

   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>GRAN TOTAL --> $</font></th>";
	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ImporteT,'2')."</font></th>";
   	 echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($DescuentoT)."</font></th>";
   	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($ImporteT-$DescuentoT,'2')."</font></th>";
     echo "</tr>";

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];


     echo "</table>";
     
echo "<div align='left'>";
echo "<a href='pidedatos.php?cRep=15'>";
echo "<img src='lib/regresa.jpg' border='0'></a> &nbsp &nbsp &nbsp ";
$FecI = $_REQUEST[FecI];
$FecF = $_REQUEST[FecF];
//echo "<form name='form1' method='post' action='pidedatos.php?cRep=3&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
//echo "</form>";
echo "</div>";

echo "</body>";
echo "</html>";

?>
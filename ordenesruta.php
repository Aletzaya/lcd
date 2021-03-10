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

  $Departamento=$_REQUEST[Departamento];

  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php
if(strlen($Departamento)>0){
	if(strlen($Institucion)>0){
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
   		$Titulo="Relacion de Ordenes de trabajo - Ruta Critica del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
		ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
		ot.status, ot.entfec, ot.enthra, ot.entusr, ot.recibio
		FROM ot, cli, otd, est, dep
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf' and dep.departamento=$Departamento and est.depto=$Departamento and ot.institucion='$Institucion' 
		order by ot.orden";
	}else{
   		$Titulo="Relacion de Ordenes de trabajo - Ruta Critica del $Fechai al $Fechaf";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
		ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
		ot.status, ot.entfec, ot.enthra, ot.entusr, ot.recibio
		FROM ot, cli, otd, est, dep
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf' and dep.departamento=$Departamento and est.depto=$Departamento
		order by ot.orden";
	}
}else{
	if(strlen($Institucion)>0){
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
   		$Titulo="Relacion de Ordenes de trabajo - Ruta Critica del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
		ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
		ot.status, ot.entfec, ot.enthra, ot.entusr, ot.recibio
		FROM ot, cli, otd, est
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf' and ot.institucion='$Institucion'
		order by ot.orden";
	}else{
   		$Titulo="Relacion de Ordenes de trabajo - Ruta Critica del $Fechai al $Fechaf";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, otd.estudio, est.descripcion, ot.institucion, ot.recepcionista, 
		ot.hora, ot.servicio, ot.fechae, otd.lugar, otd.uno, otd.dos, otd.tres, otd.cuatro, otd.cinco, otd.seis, 
		ot.status, ot.entfec, ot.enthra, ot.entusr, ot.recibio
		FROM ot, cli, otd, est
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf'
		order by ot.orden";
	}
}

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" alt="" width="187" height="61"> 
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio 
        Clinico Duran</strong><br><font size="1">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "$Titulo"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='98%' border='1' cellspacing='0' cellpadding='0' bordercolor='#FFFFFF'>";
    echo "<tr><td colspan='8'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Capt.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Etiq.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>R. Est.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Cap.</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Imp.</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Rece.</font></th>";
    echo "<tr><td colspan='8'><hr noshade></td></tr>";
    $Orden=0;
    $Ordenes=1;
	$Estudios=0;
    while($rg=mysql_fetch_array($UpA)) {
    	if($Orden<>$rg[orden]){
    		if($Orden<>0){
				 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
				$color=0;
    			$Ordenes++;
				$Urge2=0;
    		}
	    	if($rg[servicio]=="Urgente"){
				$Urgencia="* U R G E N C I A *";
				$Gfont4="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
			}else{
				$Urgencia=$rg[servicio];
				$Gfont4="<font size='1' face='Arial, Helvetica, sans-serif'>";
			}
			$Rec=$rg[recepcionista];
    		echo "<tr>";
    		echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[institucion]&nbsp;-&nbsp;$rg[orden]</font></th>";
    		echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[2]</font></th>";
    		echo "<th colspan='2' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Capturo: $Rec &nbsp;&nbsp;&nbsp;&nbsp; $Gfont4 $Urgencia</font></th>";
    		echo "<th colspan='4' align='left'><font size='1' face='Arial, Helvetica, sans-serif'>Entrego: $rg[entusr] &nbsp;&nbsp;&nbsp;&nbsp; Fecha - Hora: $rg[entfec]&nbsp;&nbsp;&nbsp;$rg[enthra]&nbsp;&nbsp;&nbsp;$rg[recibio]</font></th>";
    		echo "</tr>";
    	    $Orden=$rg[orden];
    	}

			$uno=$rg[uno];
			$dos=$rg[dos];
			$tres=$rg[tres];
			$cuatro=$rg[cuatro];
			$cinco=$rg[cinco];

			if($rg[cinco]<>'0000-00-00 00:00:00'){
				$pendiente=" ";
				$Gfont3="<font size='1' face='Arial, Helvetica, sans-serif'>";
			}else{
				$pendiente="Pendiente";
				$Gfont3="<font color='#FF6600' size='1' face='Arial, Helvetica, sans-serif'>";
			}


		if($rg[estudio]=="URG"){
			$Urge=1;
		}else{
			$Urge=0;
		}

    	if($Urge<>0){
			$Urgencia2="*  U R G  * ";
			$Gfont5="<font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>";
		}else{
			$Urgencia2=" ";
			$Gfont5="<font size='1' face='Arial, Helvetica, sans-serif'>";
		}

		if($color==0){
			$color2='#DADADA';
			$color=1;
		}else{
			$color2='#FFFFFF';
			$color=0;
		}

		echo "<tr>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$Gfont3 $pendiente &nbsp;&nbsp;&nbsp; </font></th>";
		echo "<th align='left' bgcolor=$color2><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]&nbsp;-&nbsp;$rg[descripcion]&nbsp;&nbsp;&nbsp; $Gfont5 $Urgencia2</font></th>";
		echo "<th align='center' bgcolor=$color2><font color='#990000'size='1' face='Arial, Helvetica, sans-serif'> $rg[fecha] &nbsp; $rg[hora]</font></th>";
		echo "<th align='center' bgcolor=$color2><font color='#336600' size='1' face='Arial, Helvetica, sans-serif'>$uno</font></th>";
		echo "<th align='center' bgcolor=$color2><font size='1' face='Arial, Helvetica, sans-serif'>$dos &nbsp;</font></th>";
		echo "<th align='center' bgcolor=$color2><font color='#000099' size='1' face='Arial, Helvetica, sans-serif'>$tres</font></th>";
		echo "<th align='center' bgcolor=$color2><font color='#990000' size='1' face='Arial, Helvetica, sans-serif'>$cuatro</font></th>";
		echo "<th align='center' bgcolor=$color2><font color='#336600' size='1' face='Arial, Helvetica, sans-serif'>$cinco</font></th>";
   		echo "</tr>"; 
   		echo "<tr></tr>"; 
   		echo "<tr></tr>"; 
   		echo "<tr></tr>"; 
   		echo "<tr></tr>"; 
		$Estudios++;
			$Urge=0;
     }

//	 echo "<tr><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th><th><hr>&nbsp;</th></tr>";
     echo "<tr><td colspan='8'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "</tr>"; 

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];

              
     echo "</table>";
	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=14&fechas=1&FecI=$FecI&FecF=$FecF'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
?>
</font></font> 
<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=14&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
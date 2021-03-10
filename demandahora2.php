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

  $Departamento=$_REQUEST[Departamento];

  $Urgentes=$_REQUEST[Urgentes];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratoriio clinico</title>
</head>
<body>
<?php
//if($Depto=="*"){
if($Urgentes == "S"){
	if(strlen($Institucion)>0){
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
    	$Titulo="Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
    	otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, ot.recepcionista, otd.status, ot.hora
		FROM ot, cli, otd, est, med
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf' and ot.medico=med.medico and ot.institucion='$Institucion' and ot.servicio='Urgente'
		order by ot.orden";
	}else{
    	$Titulo="Relacion de Ordenes de trabajo Urgentes del $Fechai al $Fechaf";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
    	otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, ot.recepcionista, otd.status, ot.hora
		FROM ot, cli, otd, est, med
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf' and ot.medico=med.medico and ot.servicio='Urgente'
		order by ot.orden";
	}
}else{
	if(strlen($Institucion)>0){
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
    	$Titulo="Relacion de Ordenes de trabajo del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion,
    	ot.institucion, ot.hora, est.depto, est.subdepto, dep.departamento
		FROM ot, cli, otd, est, med, dep
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf' and ot.institucion='$Institucion' and est.depto=dep.departamento
		order by dep.departamento, est.subdepto, ot.hora";
	}else{
    	$Titulo="Relacion de Ordenes de trabajo del $Fechai al $Fechaf";
	    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec, cli.afiliacion, otd.estudio, est.descripcion, otd.precio, 
    	otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, ot.recepcionista, ot.hora
		FROM ot, cli, otd, est, med
		WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
		ot.fecha <='$Fechaf' and ot.medico=med.medico
		order by ot.orden";
	}
}	

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
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Orden</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Estudios</font></th>";
	echo "<th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Precio</font></th>";		
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Desc. %</font></th>";
    echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>Importe</font></th>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    $Orden=0;
    $Importe=0;
    $Descuento=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
	$Estudios=0;
	$subdepto1=" ";

    while($rg=mysql_fetch_array($UpA)) {
	if($subdepto1==" "){
		$subdepto1=$rg[subdepto];
	}else{
    	if($subdepto1==$rg[subdepto]){
			echo "<tr>";
			echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[depto]</font></th>";
			echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[subdepto]&nbsp;-&nbsp;</font></th>";
			echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[hora]</font></th>";
			echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[estudio]&nbsp;-&nbsp;</font></th>";
			echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[descripcion]&nbsp;</font></th>";
   			echo "</tr>"; 
			$Estudios++;
		}else{
			echo "<tr>";
			echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>No. de Estudios: $subdepto1</font></th>";
			echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$Estudios</font></th>";
   			echo "</tr>"; 
			$Estudios2=$Estudios2+$Estudios;
			$Estudios=0;
     	}
		$subdepto1=$rg[subdepto];
	}
}
			echo "<tr>";
			echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>No. de Estudios: $subdepto1</font></th>";
			echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$Estudios</font></th>";
   			echo "</tr>"; 
			$Estudios2=$Estudios2+$Estudios;

    	
     echo "<tr><td colspan='8'><hr noshade></td></tr>";  
     
   	 echo "<tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : rg[6]</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios2</font></th>";
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
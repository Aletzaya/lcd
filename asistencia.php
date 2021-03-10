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

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

?>
<html>
<head>
<title>Sistema de Laboratoriio clinico</title>
</head>
<body>
<?php
if(strlen($Institucion)>0){
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
    	$Titulo="Reporte de Asistencia del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
		$cSql="SELECT mov.fecha,mov.empleado,emp.nombre,mov.hora,mov.status,emp.id,mov.mov,emp.inst,inst.alias 
		FROM inst,mov left join emp ON mov.empleado=emp.id 
		WHERE emp.inst=inst.institucion and mov.fecha>='$Fechai' and mov.fecha <='$Fechaf'
		order by mov.fecha, mov.hora";
}else{
		$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
		$Nombre=mysql_fetch_array($NomA);
    	$Titulo="Reporte de Asistencia del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
		$cSql="SELECT mov.fecha,mov.empleado,emp.nombre,mov.hora,mov.status,emp.id,mov.mov,emp.inst,inst.alias 
		FROM inst,mov left join emp ON mov.empleado=emp.id 
		WHERE emp.inst=inst.institucion and mov.fecha>='$Fechai' and mov.fecha <='$Fechaf'
		order by mov.fecha, mov.hora";
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
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Clave</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Empleado</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Hora</font></th>";		
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Status</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Movimiento</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Institucion</font></th>";
    echo "<tr><td colspan='7'><hr noshade></td></tr>";
    while($rg=mysql_fetch_array($UpA)) {
   		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[fecha]</font></th>";
   		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[empleado]</font></th>";
	    echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[nombre]</font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[hora]</font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[status]</font></th>";
		echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[mov]</font></th>";
		echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$rg[alias]</font></th>";
		echo "</tr>";   			
     }

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
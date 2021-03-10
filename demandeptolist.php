<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];
  
	$Sucursal     =   $_REQUEST[Sucursal];
	//$Sucursal     =   $Sucursal[0];
	$sucursalt = $_REQUEST[sucursalt];
	$sucursal0 = $_REQUEST[sucursal0];
	$sucursal1 = $_REQUEST[sucursal1];
	$sucursal2 = $_REQUEST[sucursal2];
	$sucursal3 = $_REQUEST[sucursal3];
	$sucursal4 = $_REQUEST[sucursal4];

  $Institucion=$_REQUEST[Institucion];

  $FecI=$_REQUEST[FecI];

  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];

  $Departamento=$_REQUEST[Departamento];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>

<?php
  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="*";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}
	}

	$Titulo="Demanda de estudios del $Fechai al $Fechaf";

	$cSql="SELECT otd.orden, otd.estudio, est.descripcion, est.depto, est.subdepto
	FROM otd, est, ot
	WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden =otd.orden
	GROUP BY otd.estudio";

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr>
    <td><div align='center'>
        <font size="4" face="Arial, Helvetica, sans-serif"><strong>Laboratorio Clinico Duran</strong></font><br>
        <font size="2"><?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Demanda de estudios del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]</p>";?>
        <font size="2"><?php // echo "$Titulo"; ?>
        </div>
    </td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
        $FechaAux=strtotime($Fecha);
        $nDias=strtotime("-1 days",$FechaAux);     //puede ser days month years y hasta -1 month menos un mes...
        $FechaAnt=date("Y-m-d",$nDias);
        echo "<table align='center' width='90%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Orden</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Estudio</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Descripcion</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>depto</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>subdepto</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>conestudio</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>conest</font></th>";

        $Subtotal=0;
		$Total=0;
		$Descuentos=0;
		$Noveces=0;
		$subdep=" ";
		$orden=0;
		$nordenes=0;

        while($registro=mysql_fetch_array($UpA)) {
			        echo "<tr>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$registro[orden]</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$registro[estudio]</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$registro[descripcion]</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$registro[depto]</font></td>";
			        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$registro[subdepto]</font></td>";

			        $cSqlB="SELECT *
					FROM conest
					WHERE conest.estudio='$registro[estudio]'";

					$UpB=mysql_query($cSqlB,$link);
        
        			while($registrob=mysql_fetch_array($UpB)) {
        				echo "<tr>";
						echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$registrob[estudio]</strong></font></td>";
				        echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$registrob[conest]</strong></font></td>";
				        echo "</tr>";
				    }
			        echo "</tr>";
			        echo "<tr><td colspan='9'><hr noshade></td></tr>";

			        mysql_data_seek($registrob, 0);

		}

echo "</table>";
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=9&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
?>
</font>
<div align="left">
<form name="form1" method="post" action="pidedatos.php?cRep=9&fechas=1&FecI=$FecI&FecF=$FecF">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
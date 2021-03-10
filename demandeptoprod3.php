<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

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

  $Servicio=$_REQUEST[Servicio];

  if($Servicio=="*"){
  	  $Servicio=" ";
  }else{
  	$Servicio=" and ot.servicio='$Servicio'";
  }

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");

  require ("config.php");
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
	
		$Sucursal=" ot.suc<>6";
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

	echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
	echo "<tr><th align='CENTER' bgcolor='#a2b2de'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Clave</font></th>";
	echo "<th align='CENTER' bgcolor='#a2b2de'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Estudio</font></th>";
	echo "<th align='CENTER' bgcolor='#a2b2de'><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Total</font></th>";

	$cSql=mysql_query("SELECT *
		FROM est
		WHERE est.base='Individual'
		GROUP BY est.estudio");

	while($Est=mysql_fetch_array($cSql)) {
		
		$Combin='';
		$Combin2='';
		$contarC=0;
		$contarest='';
 		
			$cSqlB=mysql_query("SELECT *
			FROM conest
			WHERE conest.conest = '$Est[estudio]' order by estudio");

			$EstD=mysql_fetch_array($cSqlB);

			$contarest=$EstD[estudio];

			if($contarest<>''){

					while($EstB=mysql_fetch_array($cSqlB)) {

						$cSqlC=mysql_query("SELECT otd.estudio, count(otd.orden) as cnt
						FROM otd, ot
						WHERE ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and otd.estudio = '$EstB[estudio]' and ($Sucursal)
						GROUP BY otd.estudio");

						$EstC=mysql_fetch_array($cSqlC);

						$contarC=$EstC[cnt];

						$Combin=$Combin."<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$EstB[estudio]</font></td>";
						$Combin2=$Combin2."<td align='center'><font size='1' face='Arial, Helvetica, sans-serif'>$contarC</font></td>";

						$contarCT=$contarCT+$contarC;
					}

					if($contarCT<>0){

						if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

				     	echo "<tr bgcolor=$Fdo onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
				    	echo "<td align='center' rowspan='2'><font size='2' face='Arial, Helvetica, sans-serif'><b>$Est[estudio]</b></font></td>";
				    	echo "<td align='left' rowspan='2'><font size='2' face='Arial, Helvetica, sans-serif'>$Est[descripcion]</font></ rowspan='2'td>";
				    	echo "<td align='center' rowspan='2'><font size='2' face='Arial, Helvetica, sans-serif'>$contarCT</font></td>";
				    	echo "$Combin";
				    	echo "<tr bgcolor=$Fdo onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>$Combin2</tr>";
				    	echo "</tr>";		
				    	$nRng++; 			
					}

			    	$contarCT='';
		
			}
	}

	echo "</table>";

	
?>
</font>
<div align="left">
<form name="form1" method="post" action="pidedatos.php?cRep=9&fechas=1&FecI=$FecI&FecF=$FecF">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
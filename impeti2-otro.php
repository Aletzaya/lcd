<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link  = conectarse();

  //header("Location: impeti.php?busca=$busca&pagina=$pagina");

  $Fecha=date("Y-m-d");
  $Hora  = date("H:i");


?>
<html>

<head>

<title>Impresion de etiquetas</title>

</head>

<body bgcolor='#FFFFFF' >

<?php

$busca = $_REQUEST[busca];
 
$Est   = $_REQUEST[Est];  
  
$OtA   = mysql_query("SELECT ot.fecha,ot.cliente,cli.nombrec,cli.sexo,cli.fechan,ot.servicio,ot.institucion,ot.medico,ot.medicon 
         FROM ot,cli,inst 
         WHERE ot.orden='$busca' and ot.cliente=cli.cliente and inst.institucion=ot.institucion");
         //echo "select ot.fecha,ot.cliente,cli.nombrec from ot,cli where ot.orden='$busca' and ot.cliente=cli.cliente";

if($_REQUEST[op] =="1"){        //Para agregar uno nuevo;  
   
     $OtdA = mysql_query("SELECT otd.estudio,est.descripcion,est.depto,dep.nombre 
             FROM otd,est,dep 
             WHERE otd.estudio='$Est' AND otd.orden='$busca' AND est.estudio='$Est' AND dep.departamento=est.depto");
     //echo "select otd.estudio,est.descripcion,est.depto,dep.nombre from otd,est,dep where otd.estudio='$Est' and otd.orden='$busca' and est.estudio='$Est' and dep.departamento=est.depto";

	  $Up  = mysql_query("UPDATE otd set etiquetas = etiquetas + 1 
	         WHERE orden='$busca' and estudio='$Est'"); 
 
}else{
   
     $OtdA = mysql_query("SELECT otd.estudio,est.descripcion,est.depto,dep.nombre 
             FROM otd,est,dep 
             WHERE otd.orden='$busca' AND otd.estudio=est.estudio AND est.depto=dep.departamento");
 
	  $Up  = mysql_query("UPDATE otd set etiquetas = etiquetas + 1 
	         WHERE orden='$busca'");
  
}

$Ot  = mysql_fetch_array($OtA);
	 
$MedA = mysql_query("SELECT nombrec FROM med WHERE medico='$Ot[medico]'");

$Med  = mysql_fetch_array($MedA); 

$Fecha2=date("Y-m-d");
$fecha_nac = $Ot[fechan];
$dia=substr($Fecha2, 8, 2);
$mes=substr($Fecha2, 5, 2);
$anno=substr($Fecha2, 0, 4);
$dia_nac=substr($fecha_nac, 8, 2);
$mes_nac=substr($fecha_nac, 5, 2);
$anno_nac=substr($fecha_nac, 0, 4);
if($mes_nac>$mes){
	$calc_edad= $anno-$anno_nac-1;
}else{
	if($mes==$mes_nac AND $dia_nac>$dia){
		$calc_edad= $anno-$anno_nac-1; 
	}else{
		$calc_edad= $anno-$anno_nac;
	}
}

if($calc_edad>=200 or $calc_edad==0){
	$EDAD= "--- ";
	}else{
	$EDAD= $calc_edad;
}


while($Otd = mysql_fetch_array($OtdA)){

     echo "<table width='100%' border='0' align='center'>";

     echo "<tr>";

     echo "<td width='100%' height='50' align='center'>";

        echo "<input name='Imp' face='Courier New, Courier, mono' type='submit' onClick='print()' value='No.Orden: $Ot[institucion] - $busca'><font size='1'>$Hora";

  		  //echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;#Ord: $busca</font>";

  		  echo "<br>";

  		  echo "<font size='1' face='Courier New, Courier, mono'><strong>$Ot[nombrec]</strong></font>";

  		  echo "<br>";
		
		  if($Ot[servicio]=='Ordinaria'){
			  $serv="ORDINARIA";
		  }else{
			  if($Ot[servicio]=='Urgente'){
			 	  $serv="URGENTE";
			  }else{
				  if($Ot[servicio]=='Express'){
					  $serv="EXPRESS";
				  }else{
					  if($Ot[servicio]=='Hospitalizado'){
						  $serv="HOSPITALIZADO";
					  }else{
						  $serv="NOCTURNO";
					  }
				  }
			  }
		  }
  		  echo "<font size='1' face='Courier New, Courier, mono'>Edad: ".$EDAD." a&ntilde;os&nbsp; Sexo:$Ot[sexo] &nbsp; $Ot[fecha]";

		  echo "<br>";

		  echo "<div align='center'><font size='1' face='Courier New, Courier, mono'><strong><u> $Otd[0] </u><font size='1' face='Courier New, Courier, mono'> - &nbsp;$Otd[1] - <u> $serv </u><div></strong>";

		  echo "<br>";
		  echo "<br>";
		  echo "<br>";

     echo "</td>";

     echo "</tr>";

     echo "</table>";
   
   echo "<br>";		//Espacios entre etiqueta y etiqueta;
   
}

echo "</body>";

echo "</html>";

?>
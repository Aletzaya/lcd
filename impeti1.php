<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link  = conectarse();

  //header("Location: impeti.php?busca=$busca&pagina=$pagina");

  $Fecha=date("Y-m-d");

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


while($Otd = mysql_fetch_array($OtdA)){

   if($Otd[2]=='1'){

     echo "<table width='100%' border='0' align='center'>";

     echo "<tr>";

     echo "<td width='100%' height='50' align='center'>";

        echo "<input name='Imp' face='Courier New, Courier, mono' type='submit' onClick='print()' value='No.Orden: $Ot[institucion] - $busca'>";

  		  //echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;#Ord: $busca</font>";

  		  echo "<br>";

  		  echo "<font size='2' face='Courier New, Courier, mono'><strong>$Ot[nombrec]</strong></font>";

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
  		  echo "<font size='1' face='Courier New, Courier, mono'>Edad:".($Fecha-$Ot[4])." &nbsp; Sexo:$Ot[sexo] &nbsp; $Ot[fecha]";

		  echo "<br>";

		  echo "<div align='center'><font size='1' face='Courier New, Courier, mono'><strong><u> $Otd[0] </u><font size='1' face='Courier New, Courier, mono'> - &nbsp;$Otd[1] - <u> $serv </u><div></strong>";

     echo "</td>";

     echo "</tr>";

     echo "</table>";

   }else{

     echo "<table width='50%' border='0' align='center'>";

     echo "<tr>";

     echo "<td width='50%' height='50' align='center'>";

  	  //echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;#Ord: $busca</font>";

	  echo "<img src='images/logo1.jpg' width='200' height='40'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong><br>&nbsp;Laboratorio Clinico Duran S.A de C.V.</strong></font>";
  	  echo "<br>";
  	  echo "<font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>$Ot[nombrec]</strong></font>";

  	  echo "<br>";
		
  	  echo "<font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Edad: ".($Fecha-$Ot[4])." &nbsp; Fecha:&nbsp;$Fecha";

	  echo "<br>";

	  echo "<div align='center'>$Otd[0] - &nbsp;$Otd[1]<div>";
		
	  echo "<br>";
		
     if($Ot[medico]=='MD'){$medic=$Ot[medicon];} else{$medic=$Med[nombrec];};
		
	  echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'><strong>DR. $medic</strong></font>";
		
	  echo "<br>";
		
     echo "<input name='Imp' face='Verdana, Arial, Helvetica, sans-serif' type='submit' onClick='print()' value='No.Orden: $Ot[institucion] - $busca'>";
		
     echo "</td>";

     echo "</tr>";

     echo "</table>";

   }
   
   echo "<br>";		//Espacios entre etiqueta y etiqueta;
   
}

echo "</body>";

echo "</html>";

?>
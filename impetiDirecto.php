<?php

  require("lib/kaplib.php");

  $link=conectarse();

  if($op =="1"){        //Para agregar uno nuevo

     $OtA=mysql_query("select ot.fecha,ot.cliente,cli.nombrec,cli.sexo,cli.fechan,ot.servicio from ot,cli where ot.orden='$busca' and ot.cliente=cli.cliente",$link);
     //echo "select ot.fecha,ot.cliente,cli.nombrec from ot,cli where ot.orden='$busca' and ot.cliente=cli.cliente";

     $OtdA=mysql_query("select otd.estudio,est.descripcion,est.depto,dep.nombre from otd,est,dep where otd.estudio='$Est' and otd.orden='$busca' and est.estudio='$Est' and dep.departamento=est.depto",$link);
     //echo "select otd.estudio,est.descripcion,est.depto,dep.nombre from otd,est,dep where otd.estudio='$Est' and otd.orden='$busca' and est.estudio='$Est' and dep.departamento=est.depto";

	 $Up=mysql_query("update otd set etiquetas = etiquetas + 1 where orden='$busca' and estudio='$Est'",$link);

     $Ot=mysql_fetch_array($OtA);

     $Otd=mysql_fetch_array($OtdA);

  	 //header("Location: impeti.php?busca=$busca&pagina=$pagina");

     $Fecha=date("Y-m-d");

 	 //echo "<font size='1' face='Verdana, Arial, Helvetica, sans-serif'>&nbsp;#Ord: $busca</font>";

     // echo "<font face='verdana' size='1'>";

  	 $text = " \n Ord: $busca  $Ot[fecha]";

     $text .= " \n ".substr($Ot[nombrec],0,22);

     $text .= " \n\r Edad:".($Fecha-$Ot[4])." Sex:$Ot[sexo]  $Ot[servicio] ";

  	 $text .= " \n $Otd[0]: $Otd[1] \n\r";

      $impresora = "\\recepcion1\Datamax E-4203";

	  //Inicio la conexion a la impresora

      $handle = printer_open($impresora);

	  //Seteo el formato y el tamaño de papel.
      printer_set_option($handle, PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM );
      printer_set_option($handle, PRINTER_PAPER_LENGTH, 100);
      printer_set_option($handle, PRINTER_PAPER_WIDTH, 150);

      //Imprimo en la impresora en forma bruta
      printer_write($handle, $text);
      printer_close($handle);

      header("Location: ordenesd.php?busca=$busca");

      }
 ?>
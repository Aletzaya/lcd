<?php
  session_start();
  $Titulo="Relacion de comisiones";
  require("lib/kaplib.php");
  $link=conectarse();
  $OrdenDef="";            //Orden de la tabla por default
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta página.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }

  $Usr=$HTTP_SESSION_VARS['usuario_login'];
  if($Institucion=="*"){
     if($Medico=="*"){
        $cSql="select oth.medico,oth.orden,oth.fecpago,otdh.estudio,otdh.descuento,(otdh.precio*(med.comision)/100) as comision,otdh.descuento,
        (otdh.precio*(1-(otdh.descuento/100))) as importe,med.nombrec as nommedico,cli.nombrec,oth.institucion
        from oth,otdh,med,cli
        where
        oth.fecpago >= '$FecI' and oth.fecpago <= '$FecF' and oth.orden=otdh.orden and oth.cliente = cli.cliente and oth.medico=med.medico
        and '$_REQUEST[Status]'=med.status order by oth.medico, otdh.orden ";
     }else{
        $cSql="select oth.medico,oth.orden,oth.fecpago,otdh.estudio,otdh.descuento,(otdh.precio*(med.comision)/100) as comision,otdh.descuento,
        (otdh.precio*(1-(otdh.descuento/100))) as importe,med.nombrec as nommedico,cli.nombrec,oth.institucion
        from oth,otdh,med,cli
        where
        oth.fecpago >= '$FecI' and oth.fecpago <= '$FecF' and oth.orden=otdh.orden and oth.cliente = cli.cliente and oth.medico=med.medico
        and '$_REQUEST[Status]'=med.status and oth.medico='$Medico' order by oth.medico, otdh.orden ";
     }
}else{
     if($Medico=="*"){
        $cSql="select oth.medico,oth.orden,oth.fecpago,otdh.estudio,otdh.descuento,(otdh.precio*(med.comision)/100) as comision,otdh.descuento,
        (otdh.precio*(1-(otdh.descuento/100))) as importe,med.nombrec as nommedico,cli.nombrec,oth.institucion
        from oth,otdh,med,cli
        where
        oth.fecpago >= '$FecI' and oth.fecpago <= '$FecF' and oth.orden=otdh.orden and oth.cliente = cli.cliente and oth.medico=med.medico
        and '$_REQUEST[Status]'=med.status and oth.institucion='$Institucion' order by oth.medico, otdh.orden ";
     }else{
        $cSql="select oth.medico,oth.orden,oth.fecpago,otdh.estudio,otdh.descuento,(otdh.precio*(med.comision)/100) as comision,otdh.descuento,
        (otdh.precio*(1-(otdh.descuento/100))) as importe,med.nombrec as nommedico,cli.nombrec,oth.institucion
        from oth,otdh,med,cli
        where
        oth.fecpago >= '$FecI' and oth.fecpago <= '$FecF' and oth.orden=otdh.orden and oth.cliente = cli.cliente and oth.medico=med.medico
        and '$_REQUEST[Status]'=med.status and oth.medico='$Medico' and oth.institucion='$Institucion' order by oth.medico, otdh.orden ";
     }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF">

<?php

  //echo $cSql;
  if(!$res=mysql_query($cSql,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados ò hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='comisiones.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{

        $registro=mysql_fetch_array($res);

        echo "<table width='100%' height='80' border='0'>";    //Encabezado
        echo "<tr><td width='26%' height='76'>";
        echo "<p align=='left'><img src='images/Logotipo%20Duran4.jpg' width='187' height='61'></p>";
        echo "</td>";
        echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
        echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF </p>";
        echo "</td></tr></table>";
        echo "<p>Medico: $registro[medico].- $registro[nommedico] &nbsp; INST:_$registro[institucion]</p>";
        echo "<hr noshade style='color:3366FF;height:1px'>";

        echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr>";
        echo "<th with='10%' align='left'>Orden</th>";
        echo "<th with='10%' align='left'>Fecha</th>";
        echo "<th with='10%' align='left'>Paciente</th>";
        echo "<th with='10%' align='left'>Estudios</th>";
        echo "<th with='30%' align='left'>Importe</th>";
        echo "<th with='10%' align='right'>Comision</th><tr>";

        $MedOrd=$registro[medico].$registro[orden];
        $Comision=$registro[comision];
        $Estudios=$registro[estudio];
        if($registro[descuento]>0){
           $Estudios="(DESCTO)".$registro[estudio];
           $Comision=0;
        }
        $Importe=$registro[importe];
        $Medico=$registro[medico];
        $Orden=$registro[orden];

        while ($registro=mysql_fetch_array($res)){
             if($MedOrd==$registro[medico].$registro[orden]){
                $Estudios=$Estudios.", ".$registro[estudio];
                $Importe+=$registro[importe];
                if($registro[descuento]==0){
                   $Comision+=$registro[comision];
                }
             }else{
                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
                 echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[fecpago]."</font></td>";
                 echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[nombrec]."</font></td>";
                 echo "<td align='left'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font></td>";
                 echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></td>";
                 echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Comision,'2')."</font></td></tr>";
                 $ImporteM+=$Importe;
                 $ComisionM+=$Comision;
                 if($registro[medico]<>$Medico){   //Total del Medico
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>T o t a l e s : &nbsp; </td><td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ImporteM,'2')."</font></td><td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ComisionM,'2')."</font></td></tr>";
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";
                    echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";
                    echo "</table>";

        			echo "<table width='100%' height='80' border='0'>";    //Encabezado
                    echo "<tr><td width='26%' height='76'>";
                    echo "<p align=='left'><img src='images/Logotipo%20Duran4.jpg' width='187' height='61'></p>";
                    echo "</td>";
                    echo "<td width='74%'><p align='center'><font size='3' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font></p>";
                    echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Relacion de pagos de comisiones del $FecI &nbsp; al  $FecF </p>";
                    echo "</td></tr></table>";

        			echo "<p>Medico: $registro[medico].- $registro[nommedico] &nbsp; INST:_$registro[institucion]</p>";

        			echo "<hr noshade style='color:3366FF;height:1px'>";

        			echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
                    echo "<tr>";
                    echo "<th with='10%' align='left'>Orden</th>";
                    echo "<th with='10%' align='left'>Fecha</th>";
                    echo "<th with='10%' align='left'>Paciente</th>";
                    echo "<th with='10%' align='left'>Estudios</th>";
                    echo "<th with='30%' align='left'>Importe</th>";
                    echo "<th with='10%' align='right'>Comision</th><tr>";

                    $ImporteT+=$ImporteM;
                    $ComisionT+=$ComisionM;

                    $ImporteM=0;
                    $ComisionM=0;
                 }
                 $MedOrd=$registro[medico].$registro[orden];
                 $Estudios=$registro[estudio];
                 $Comision=$registro[comision];
                 if($registro[descuento]>0){
                    $Estudios="(DESCTO)".$registro[estudio];
                    $Comision=0;
                 }
                 $Importe=$registro[importe];
                 $Medico=$registro[medico];
                 $Orden=$registro[orden];
            }
         }

                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$Orden."</font></td>";
                 echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[fecpago]."</font></td>";
                 echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[nombrec]."</font></td>";
                 echo "<td align='left'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$Estudios."</font></td>";
                 echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Importe,'2')."</font></td>";
                 echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Comision,'2')."</font></td></tr>";
                 $ImporteM+=$Importe;
                 $ComisionM+=$Comision;

         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>T o t a l e s : &nbsp; </td><td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ImporteM,'2')."</font></td><td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ComisionM,'2')."</font></td></tr>";
         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";
         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>&nbsp;</td></tr>";

         $ImporteT+=$ImporteM;
         $ComisionT+=$ComisionM;

         echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>G R A N &nbsp; T O T A L : </td><td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ImporteT,'2')."</font></td><td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($ComisionT,'2')."</font></td></tr>";
         echo "</table>";


    }
	//fin while
	echo '</table>';
    ?>
	<br>

    <div align='center'>
    <p align='center'><font face='verdana' size='-2'><a href='menu.php'>
    Regresar</a></font>
    </div>
    <div align="left">
      <form name="form1" method="post" action="menu.php">
           <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
      </form>
    </div>

</body>
</html>
<?
mysql_close();
?>
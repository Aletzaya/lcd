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
$Institucion  =   $_REQUEST[Institucion];
$Medico       =   $_REQUEST[Medico];
$Depto        =   $_REQUEST[Depto];

$Fechai       =   $_REQUEST[Fechai];
$Fechaf       =   $_REQUEST[Fechaf];

$Titulo       =   $_REQUEST[Titulo];

$Fecha=date("Y-m-d");

$Hora=date("H:i");

$reporte=$_REQUEST[reporte];

if($Medico <> '*' AND $Medico <> ''){$cMedico = " AND ot.medico='$Medico'"; $cTitMed= ' Medico: $Medico';}
   
echo "<html>";
echo "<head><title>Sistema de Laboratoriio clinico</title></head>";
echo "<body>";


        if($Sucursal <> '*'){
            $CiaA    = mysql_query("SELECT nombre FROM cia WHERE id='$Sucursal'");
            $Cia     = mysql_fetch_array($CiaA);
            $cTitSuc = " $Sucursal $Cia[nombre]";
        }else{           
           $cTitSuc = " * todas, ";
        }

        if($Sucursal=='*'){      
            
           if($Institucion=='*'){ 
              if($Depto == '*'){ 
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: * todas, Depto: * todos";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden" . $cMedico;
              }else{
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: $Institucion, Depto: $Depto";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden AND est.depto='$Depto'" . $cMedico;                 
              }
           }else {
              if($Depto == '*'){                
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: $Institucion, Depto: $Depto";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden AND ot.institucion=$Institucion " . $cMedico;
              }else{
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: $Institucion, Depto: $Depto";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden AND est.depto='$Depto' AND ot.institucion=$Institucion " . $cMedico;                  
              }   
           } 
        }else{
           if($Institucion=='*'){ 
              if($Depto == '*'){ 
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: $Institucion, Depto: $Depto";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden AND ot.suc='$Sucursal'" . $cMedico;
              }else{
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: $Institucion, Depto: $Depto";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden AND est.depto='$Depto' AND ot.suc='$Sucursal'" . $cMedico;                 
              }
           }else {
              if($Depto == '*'){                
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: $Institucion, Depto: $Depto";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden AND ot.institucion=$Institucion  AND ot.suc='$Sucursal'" . $cMedico;
              }else{
                 $Titulo  = "Demanda de estudios del $Fechai al $Fechaf Sucursal : $cTitSuc Inst: $Institucion, Depto: $Depto";
                 $cWhere  =  " otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' 
                               AND ot.orden=otd.orden AND est.depto='$Depto' AND ot.institucion=$Institucion AND ot.suc='$Sucursal'" . $cMedico;                  
              }   
           } 
            
        }
        

        $cSql   = "SELECT otd.estudio, est.descripcion, otd.precio, count(otd.orden), sum(otd.precio),sum(otd.precio * (otd.descuento/100)), 
                  count(ot.orden), est.clavealt
                  FROM otd, est, ot
                  WHERE $cWhere 
                  GROUP BY otd.estudio";

	$UpA=mysql_query($cSql);
        
        //echo $cSql;

	?>
	<table width="100%" border="0">
	  <tr>
    	<td><div align='center'>
        	<font size="4" face="Arial, Helvetica, sans-serif"><strong>Laboratorio Clinico Duran</strong></font><br>
	        <font size="2"><?php echo "$Fecha - $Hora"; ?><br>
    	    <font size="2"><?php echo " ".$Titulo . $cTitMed; ?>
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
        	echo "<tr><td colspan='8'><hr noshade></td></tr>";
	      echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Estudio</font></th>";
    	   echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Descripcion</font></th>";
    	   echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Clave</font></th>";
        	echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Precio</font></th>";
	      echo "<th align='CENTER'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>#Estudios</font></th>";
    	   echo "<th><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Sub-total</font></th>";
        	echo "<th><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Descuentos</font></th>";
	      echo "<th><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>I m p o r t e</font></th>";
    	   echo "<tr><td colspan='8'><hr noshade></td></tr>";
	      $Subtotal=0;
			$Total=0;
			$Descuentos=0;
			$Noveces=0;
    	    while($registro=mysql_fetch_array($UpA)) {
       ?>
	</font></font>
	<tr>
	  <td><font size="1" face="Arial, Helvetica, sans-serif"><? echo $registro[0]; ?></font></td>
	  <td><font size="1" face="Arial, Helvetica, sans-serif"><? echo $registro[1]; ?></font></td>
	  <td align='center'><font size="1" face="Arial, Helvetica, sans-serif"><? echo $registro[clavealt]; ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><? echo number_format($registro[2],"2"); ?></font></td>
	  <td align='center'><font size="1" face="Arial, Helvetica, sans-serif"><? echo number_format($registro[3]); ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><? echo number_format($registro[4],"2"); ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><? echo number_format($registro[5],"2"); ?></font></td>
	  <td align='right'><font size="1" face="Arial, Helvetica, sans-serif"><? echo number_format($registro[4]-$registro[5],"2"); ?></font></td>
	</tr>
	<font size="1" face="Arial, Helvetica, sans-serif">
	<?php
             $Noveces=$Noveces+$registro[3];
             $Descuentos=$Descuentos+$registro[5];
             $Subtotal=$Subtotal+$registro[4];
             $Total=$Total+($registro[4]-$registro[5]);
			 $Cuenta=$registro[6];
        }//fin while
        echo "<tr>";
        echo "<td><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp;</font></td>";
        echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>T o t a l e s </font></td>";
        echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
        echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
		echo "<td align='center'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces)."</strong></font></td>";
        echo "<td align='right'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Subtotal,'2')."</strong></font></td>";
        echo "<td align='right'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Descuentos,'2')."</strong></font></td>";
        echo "<td align='right'><hr><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Total,'2')."</strong></font></td>";
        echo "</tr>";

        echo "</table>";
        echo "<br>";
		echo "<br>";
		echo "<br>";
		
		  /*
        echo "<table align='center' width='75%' border='0' cellspacing='1' cellpadding='0'>";

       	echo "<tr>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Depto</font></th>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Nombre</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>No.Estudios</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> - </font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Sub-total</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> &nbsp; Desctos </font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>I m p o r t e</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> - </font></th>";
       	echo "</tr>";
        echo "<tr><td colspan='9'><hr noshade></td></tr>";

        $DeptoA=mysql_query("select departamento,nombre from dep order by departamento",$link);
        while($rg=mysql_fetch_array($DeptoA)) {
			if(strlen($Institucion)>0){
	        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100))
		    	FROM otd, est, ot
		    	WHERE otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' AND ot.orden=otd.orden
		    	and ot.institucion=$Institucion AND est.depto='$rg[0]' group by est.depto";
			}else{
	        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100))
		    	FROM otd, est, ot
		    	WHERE otd.estudio = est.estudio AND ot.fecha>='$Fechai' AND ot.fecha<='$Fechaf' AND ot.orden=otd.orden
		    	and est.depto='$rg[0]' group by est.depto";
			}
            $dmA=mysql_query($cSql,$link);
            if($dm=mysql_fetch_array($dmA)){
  	      	echo "<tr>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[0]."</font></th>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[1]."</font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[0],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format(($dm[0]/$Noveces)*100,'0')." % </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1]-$dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format((($dm[1]-$dm[2])/$Total)*100,'0')." % </font></th>";
        		echo "</tr>";
        	}
        }
        echo "<tr><td colspan='8'><hr noshade></td></tr>";
        echo "</table>";
        
		echo "<div align='center'>";
		echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=2'>Regresar</a></font>";
		echo "</div>";
	*/
echo '<div align="left">';
echo '<form name="form1" method="post" action="menu.php">';
      echo "<a href='pidedatos.php?cRep=16'><img src='lib/regresa.jpg' border='0'></a>";                
      echo " &nbsp &nbsp &nbsp ";
      echo '<input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">';
echo '</form>';

echo "</body>";
echo "</html>";

?>
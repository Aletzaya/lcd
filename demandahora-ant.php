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
<title>Sistema de Laboratoriio clinico</title>
</head>
<body>

<?php
        
if ($Sucursal <> '*') {
			
	if(strlen($Departamento)>0){
		if(strlen($Institucion)>0){
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Hora Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, 
			est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), dep.departamento, count(distinct ot.hora) as cuentahora
			,ot.suc
			FROM otd, est, ot, dep
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden 
			and ot.institucion=$Institucion and dep.departamento=$Departamento and est.depto=$Departamento
			AND ot.suc='$Sucursal'
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}else{
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, 
			est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), dep.departamento, count(distinct ot.hora) as cuentahora
			,ot.suc
			FROM otd, est, ot, dep
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden 
			and dep.departamento=$Departamento and est.depto=$Departamento AND ot.suc='$Sucursal'
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}
	}else{
		if(strlen($Institucion)>0){
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Hora Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, 
			est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), count(distinct ot.hora) as cuentahora, ot.suc
			FROM otd, est, ot
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and 
			ot.institucion=$Institucion AND ot.suc='$Sucursal'
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}else{
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, 
			est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), count(distinct ot.hora) as cuentahora, ot.suc
			FROM otd, est, ot
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden AND ot.suc='$Sucursal'
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}
	}
	
}else{
			
	if(strlen($Departamento)>0){
		if(strlen($Institucion)>0){
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Hora Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), dep.departamento, count(distinct ot.hora) as cuentahora
			FROM otd, est, ot, dep
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and ot.institucion=$Institucion and dep.departamento=$Departamento and est.depto=$Departamento
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}else{
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), dep.departamento, count(distinct ot.hora) as cuentahora
			FROM otd, est, ot, dep
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}
	}else{
		if(strlen($Institucion)>0){
			$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
			$Nombre=mysql_fetch_array($NomA);
			$Titulo="Demanda de estudios X Hora Detallado del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), count(distinct ot.hora) as cuentahora
			FROM otd, est, ot
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and ot.institucion=$Institucion
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}else{
			$Titulo="Demanda de estudios del $Fechai al $Fechaf";
			$cSql="SELECT otd.estudio, est.descripcion, count(otd.orden), count(distinct ot.orden), est.depto, est.subdepto, otd.orden, ot.hora, substr(ot.hora,0,2), count(distinct ot.hora) as cuentahora
			FROM otd, est, ot
			WHERE otd.estudio=est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
			GROUP BY est.depto, est.subdepto, otd.orden order by est.depto, est.subdepto, ot.hora";
		}
	}
}

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr>
    <td><div align='center'>
        <font size="4" face="Arial, Helvetica, sans-serif"><strong>Laboratorio Clinico Duran</strong></font><br>
        <font size="2"><?php echo "$Fecha - $Hora"; ?><br>
        <font size="2"><?php echo "$Titulo"; ?>
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
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Depto</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Subdepto</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Horario</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Detalle Hora</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Estudio</font></th>";
        echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Descripcion</font></th>";

        $Subtotal=0;
		$Total=0;
		$Descuentos=0;
		$Noveces=0;
		$subdep=" ";
		$orden=0;
		$nordenes=0;
		$Hora4=" ";

        while($registro=mysql_fetch_array($UpA)) {
		$Hora5=substr($registro[7],0,2);
   		if($Hora4<>$Hora5){
			$departamento=$registro[4];
			$subdepartamento=$registro[5];
			$Horas=" Hrs.";
			$Horas2=$Hora5;
		    echo "<tr><td></td><td></td><td></td><td></td><td colspan='5'><hr></td></tr>";		
//			if($Noveces<>0){
				echo "<tr>";
			    echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
			    echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
				echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
				echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>T o t a l</font></td>";
			    echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
				echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces2)."</strong></font></td>";
				echo "</tr>";
	 	        echo "<tr><td></td><td></td><td colspan='5'><hr noshade></td></tr>";
        		$Noveces3=$Noveces3+$Noveces2;
				$Nordenes3=$Nordenes3+$Nordenes2;
			    $Descuentos3=$Descuentos3+$Descuentos2;
		    	$Subtotal3=$Subtotal3+$Subtotal2;
		        $Total3=$Total3+$Total2;
		        $Noveces2=0;
			    $Nordenes2=0;
    	    	$Descuentos2=0;
			    $Subtotal2=0;
        		$Total2=0;						
//			}
		}else{
			$Horas2=" ";
			$Horas=" ";
			$departamento=$registro[4];
			$subdepartamento=$registro[5];
		}
    	if($subdep==$registro[5]){
			$departamento=" ";
			$subdepartamento=" ";
			}
?>
</font></font>
<tr>
  <td><font size="2" face="Arial, Helvetica, sans-serif"><strong><? echo $departamento; ?></strong></font></td>
  <td><font size="2" face="Arial, Helvetica, sans-serif"><strong><? echo $subdepartamento; ?></strong></font></td>
  <td><font size="2" face="Arial, Helvetica, sans-serif"><strong><? echo $Horas2; echo $Horas;?></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><? echo $registro[7]; ?></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><? echo $registro[0]; ?></font></td>
  <td><font size="1" face="Arial, Helvetica, sans-serif"><? echo $registro[1]; ?></font></td>
</tr>

<font size="1" face="Arial, Helvetica, sans-serif">
<?php
             $Noveces2=$Noveces2+$registro[cuentahora];
		     $Nordenes2=$Nordenes2+$registro[6];
             $Descuentos2=$Descuentos2+$registro[5];
             $Subtotal2=$Subtotal2+$registro[4];
             $Total2=$Total2+($registro[4]-$registro[5]);

             $Noveces=$Noveces2+$registro[3];
		     $Nordenes=$Nordenes2+$registro[6];
             $Descuentos=$Descuentos2+$registro[5];
             $Subtotal=$Subtotal2+$registro[4];
             $Total=$Total2+($registro[4]-$registro[5]);
			 $Cuenta=$Cuenta+$registro[6];
			 $subdep=$registro[5];
			 $Hora4=$Hora5;
        }//fin while
   		     $Noveces3=$Noveces3+$Noveces2;
		     $Nordenes3=$Nordenes3+$Nordenes2;
             $Descuentos3=$Descuentos3+$Descuentos2;
             $Subtotal3=$Subtotal3+$Subtotal2;
             $Total3=$Total3+$Total2;

        echo "<tr><td></td><td></td><td></td><td></td><td colspan='5'><hr></td></tr>";
        echo "<tr>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>T o t a l </font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'> - </font></td>";
		echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($Noveces2)."</strong></font></td>";
        echo "</tr>";

        echo "<tr></tr>";
        echo "<tr></tr>";
        echo "<tr></tr>";
        echo "<tr></tr>";

        echo "<tr>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>&nbsp;</font></td>";
        echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'> No. Ordenes : ".number_format($registro2[0])."</font></td>";
        echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>T o t a l &nbsp;&nbsp;  G r a l .</font></td>";
        echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'> - </font></td>";
        echo "<td align='center' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($Noveces3)."</strong></font></td>";
        echo "</tr>";

        echo "</table>";
        echo "<br>";
		echo "<br>";
		echo "<br>";
        echo "<table align='center' width='75%' border='0' cellspacing='1' cellpadding='0'>";

       	echo "<tr>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Depto</font></th>";
       	echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>Nombre</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>No. Estudios</font></th>";
       	echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'> - </font></th>";
       	echo "</tr>";
        echo "<tr><td colspan='8'><hr noshade></td></tr>";

        $DeptoA=mysql_query("select departamento,nombre from dep order by departamento",$link);
        while($rg=mysql_fetch_array($DeptoA)) {
			if(strlen($Departamento)>0){
				if(strlen($Institucion)>0){
		        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
			    	FROM otd, est, ot, dep
		    		WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento
		    		and ot.institucion=$Institucion and est.depto='$rg[0]' group by est.depto";
				}else{
		        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
			    	FROM otd, est, ot, dep
		    		WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden and dep.departamento=$Departamento and est.depto=$Departamento
		    		and est.depto='$rg[0]' group by est.depto";
				}
			}else{
				if(strlen($Institucion)>0){
		        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
			    	FROM otd, est, ot
		    		WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
		    		and ot.institucion=$Institucion and est.depto='$rg[0]' group by est.depto";
				}else{
		        	$cSql="SELECT count(otd.orden),sum(otd.precio),sum(otd.precio * (otd.descuento/100)),count(distinct otd.orden)
			    	FROM otd, est, ot
		    		WHERE otd.estudio = est.estudio and ot.fecha>='$Fechai' and ot.fecha<='$Fechaf' and ot.orden=otd.orden
		    		and est.depto='$rg[0]' group by est.depto";
				}
			}

            $dmA=mysql_query($cSql,$link);
            if($dm=mysql_fetch_array($dmA)){
  	      		echo "<tr>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[0]."</font></th>";
        		echo "<th align='left'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".$rg[1]."</font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[0],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format(($dm[0]/$Noveces3)*100,'0')." % </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($dm[1]-$dm[2],'2')." &nbsp; </font></th>";
        		echo "<th align='right'><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>".number_format((($dm[1]-$dm[2])/$Total3)*100,'0')." % </font></th>";
        		echo "</tr>";
        	}
        }
        echo "<tr><td colspan='8'><hr noshade></td></tr>";
        echo "</table>";
echo "<div align='center'>";
echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=8&fechas=1&FecI=$FecI&FecF=$FecF'>";
echo "Regresar</a></font>";
echo "</div>";
?>
</font>
<div align="left">
<form name="form1" method="post" action="pidedatos.php?cRep=8&fechas=1&FecI=$FecI&FecF=$FecF">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
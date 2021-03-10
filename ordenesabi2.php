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

  $Fechai=$_REQUEST[Fechai];

  $Fechaf=$_REQUEST[Fechaf];

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
//if($Depto=="*"){
if(strlen($Institucion)>0){
	$NomA=mysql_query("select nombre from inst where institucion=$Institucion",$link);
	$Nombre=mysql_fetch_array($NomA);
    $Titulo="Relacion de Ordenes de trabajo del $Fechai al $Fechaf Institucion : $Institucion $Nombre[0]";
    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec as nombrecli, cli.afiliacion, otd.estudio, est.descripcion, otd.precio,
    otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, ot.recepcionista,ot.diagmedico,ot.receta,ot.fecharec
	FROM ot, cli, otd, est, med
	WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
	ot.fecha <='$Fechaf' and ot.medico=med.medico and ot.institucion='$Institucion'
	order by ot.orden";
}else{
    $Titulo="Relacion de Ordenes de trabajo del $Fechai al $Fechaf";
    $cSql="SELECT ot.orden, ot.fecha, cli.nombrec as nombrecli, cli.afiliacion, otd.estudio, est.descripcion, otd.precio,
    otd.descuento, otd.precio * ( 1 - ( otd.descuento /100 ) ), ot.medico, ot.medicon, med.nombrec, ot.institucion, ot.recepcionista,ot.diagmedico,ot.receta,ot.fecharec
	FROM ot, cli, otd, est, med
	WHERE ot.cliente = cli.cliente AND ot.orden = otd.orden AND otd.estudio = est.estudio and ot.fecha>='$Fechai' and
	ot.fecha <='$Fechaf' and ot.medico=med.medico
	order by ot.orden";
}

$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr>
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" width="187" height="61">
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong> &nbsp; &nbsp; &nbsp; &nbsp; Laboratorio
        Clinico Duran</strong><br><font size="1">
        <font size="1"><?php echo "$Titulo"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<?php
    $Gfont="<font face='Arial, Helvetica, sans-serif' size='1'>";
    $Orden=0;
    $Importe=0;
    $Descuento=0;
    $ImporteT=0;
    $DescuentoT=0;
    $Ordenes=0;
	$Estudios=0;
	$Med1="A";
    $rg=mysql_fetch_array($UpA);
		$Med=$rg[medico];
    	if($Med1<>$Med){
			$Med1=$Med;
			$Med2=$rg[nombrec];
	    	$Med3=$rg[medicon];
			if($Med1=="MD"){
	    		$Med2=$Med3;
			}
		}
    echo "$Gfont <br><br><div><b>Orden: </b> $rg[orden] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Fecha Atn.: </b> $rg[fecha] &nbsp; &nbsp; &nbsp; &nbsp; <b>Paciente: </b>$rg[nombrecli] &nbsp; &nbsp; &nbsp; &nbsp; <b>Capt.:</b> $rg[recepcionista]</div>";
    echo "$Gfont <div> &nbsp; <b>Afiliac.:_</b> $rg[afiliacion] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Medico:_ </b> $rg[medico] _$Med2</div>";
    echo "$Gfont <div> &nbsp; <b>Receta:_</b> $rg[receta] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Fec.receta:_ </b> $rg[fecharec] &nbsp; &nbsp; &nbsp; &nbsp; <b>Diagnostico:_ </b>$rg[diagmedico] &nbsp; &nbsp; &nbsp; &nbsp; </div><br>";

    echo "<table align='center' width='80%' border='0' cellspacing='1' cellpadding='0'>";

    echo "<tr><td>$Gfont $rg[estudio] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </font></td> ";
    echo "<td>$Gfont $rg[descripcion]  </font></td>";
    echo "<td align='right'>$Gfont ".number_format($rg[8],'2')."</font></td>";
   	echo "</tr>";

	$Estudios++;
   	$Importe+=$rg[precio];
   	$Descuento+=($rg[precio]*($rg[descuento]/100));
	$Rec=$rg[recepcionista];
	$Orden=$rg[orden]; 

    while($rg=mysql_fetch_array($UpA)) {
		$Med1="A";
    	if($Orden<>$rg[orden]){
				$Med=$rg[medico];
    			if($Med1<>$Med){
					$Med1=$Med;
					$Med2=$rg[nombrec];
			    	$Med3=$rg[medicon];
					if($Med1=="MD"){
	    				$Med2=$Med3;
					}
				}

    		if($Orden<>0){
                echo "<tr><td>&nbsp;</td> ";
                echo "<td align='right'>$Gfont Total estudios $Estudios  </font></td>";
                echo "<td align='right'>$Gfont $ ".number_format($Importe,'2')."</font></td>";
                echo "</tr>";

                echo "</table>";
                echo "$Gfont <hr><br><div><b>Orden: </b> $rg[orden] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Fecha Atn.: </b> $rg[fecha] &nbsp; &nbsp; &nbsp; &nbsp; <b>Paciente: </b>$rg[nombrecli] &nbsp; &nbsp; &nbsp; &nbsp; <b>Capt.:</b> $rg[recepcionista]</div>";
                echo "$Gfont <div> &nbsp; <b>Afiliac.:_</b> $rg[afiliacion] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Medico:_ </b> $rg[medico] _$Med2 </div>";
                echo "$Gfont <div> &nbsp; <b>Receta:_</b> $rg[receta] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Fec.receta:_ </b> $rg[fecharec] &nbsp; &nbsp; &nbsp; &nbsp; <b>Diagnostico:_ </b>$rg[diagmedico] &nbsp; &nbsp; &nbsp; &nbsp; </div><br>";
 		 
 				$ImporteT+=$Importe;
 				$DescuentoT+=$Descuento;
				$Estudios=0;
    			$Importe=0;
    			$Descuento=0;
    			$Ordenes++;
				$Med1="A";
				$Rec="B";
                echo "<table align='center' width='80%' border='0' cellspacing='1' cellpadding='0'>";
    		}

    	    $Orden=$rg[orden]; 

    	}

	        echo "<tr><td>$Gfont $rg[estudio] &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </font></td> ";
    	    echo "<td>$Gfont $rg[descripcion]  </font></td>";
        	echo "<td align='right'>$Gfont ".number_format($rg[8],'2')."</font></td>";
	   		echo "</tr>";
	
			$Estudios++;
   			$Importe+=$rg[precio];
	   		$Descuento+=($rg[precio]*($rg[descuento]/100));
			$Rec=$rg[recepcionista];
     }

     echo "<tr><td>&nbsp;</td> ";
     echo "<td align='right'>$Gfont Total estudios $Estudios  </font></td>";
     echo "<td align='right'>$Gfont $ ".number_format($Importe,'2')."</font></td>";
     echo "</tr></table>";

   	 $Ordenes++;
 	 $ImporteT+=$Importe;
 	 $DescuentoT+=$Descuento;

/*
   	 echo "<table><tr>";
     echo "<th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>No. Ordenes : $Ordenes</font></th>";
     echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>No. Estudios : $Estudios</font></th>";
     echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>GRAN TOTAL --> $</font></th>";
	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($ImporteT,'2')."</font></th>";
   	 echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($DescuentoT)."</font></th>";
   	 echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'>$&nbsp;&nbsp;".number_format($ImporteT-$DescuentoT,'2')."</font></th>";
     echo "</tr></table>";
*/

	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=9'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
?></font></font>
<font size="1">&nbsp; </font>
<div align="left">
<form name="form1" method="post" action="menu.php">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
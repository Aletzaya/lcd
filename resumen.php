<?php

  session_start();
  $Titulo="Calculo de Comisiones";
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
  $cSql="select ot.institucion,dep.departamento,dep.nombre,sum(otd.precio*(1-(otd.descuento/100))),inst.nombre, count(*) from ot,otd,est,dep,inst where ot.fecha>='$FecI' and ot.fecha<='$FecF' and ot.orden=otd.orden and otd.estudio=est.estudio
  and est.depto=dep.departamento and ot.institucion=inst.institucion group by ot.institucion + dep.departamento";

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
        echo "<table width='100%' border='0'>";
        echo "<tr><td><div align='center'>";
        echo "<font size='4' face='Arial, Helvetica, sans-serif'><strong>Laboratorio Clinico Duran</strong></font><br>";
        echo "<font size='2'>$Fecha - $Hora<br>";
        echo "Resumes de estudios por Institucion y Depto. del $FecI al $FecF";
        echo "</div></td></tr></table>";

        echo "<tr><td colspan='8'><hr noshade></td></tr>";

        echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr>";
        echo "<th with='10%' align='left'>Inst.</th>";
        echo "<th with='30%' align='left'>Nombre</th>";
        echo "<th with='30%' align='left'>Departamento</th>";
        echo "<th with='10%' align='right'>No.Estudios</th>";
        echo "<th with='20%' align='right'>Importe</th></tr>";
        $cImporte=0;
        $cEstudios=0;
        $Timporte=0;
        $Testudios=0;
        $cInst="XXX";
        $lBd=true;
        while($registro=mysql_fetch_array($res)) {
            if($cInst<>$registro[0]){
               if($lBd){
                  $lBd=false;
               }else{
                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
                 echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
                 echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font>Total :</td>";
                 echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($cEstudios,"2")."</font></td>";
                 echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($cImporte,"2")."</font></td></tr>";

                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font>&nbsp;</td></tr>";

                 $Testudios=$Testudios+$cEstudios;
                 $Timporte=$Timporte+$cImporte;

                 $cEstudios=0;
                 $cImporte=0;
               }
               $cInst=$registro[0];
            }
            echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[0]."</font></td>";
            echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[4]."</font></td>";
            echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".$registro[1]." .-".$registro[2]."</font></td>";
            echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($registro[5],"2")."</font></td>";
            echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($registro[3],"2")."</font></td></tr>";
            $cEstudios=$cEstudios+$registro[5];
            $cImporte=$cImporte+$registro[3];
        }

        echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
        echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
        echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font>Sub total</td>";
        echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($cEstudios,"2")."</font></td>";
        echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($cImporte,"2")."</font></td></tr>";

        echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font>&nbsp;</td></tr>";

        $Testudios=$Testudios+$cEstudios;
        $Timporte=$Timporte+$cImporte;

        echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
        echo "<td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font></td>";
        echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'></font>GRAN TOTAL</td>";
        echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Testudios,"2")."</font></td>";
        echo "<td align='right'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>".number_format($Timporte,"2")."</font></td></tr>";

	}//fin while

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
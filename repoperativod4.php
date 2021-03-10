<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $Institucion  = $_REQUEST[Institucion];

  $Depto = $_REQUEST[depto];
  
  $Suc 			=   $_REQUEST[Suc];

  $FechaI		=	$_REQUEST[FecI];

  $FechaF		=	$_REQUEST[FecF];

  $Capt   = $_REQUEST[capt];

  $Etapa   = $_REQUEST[etapa];

 if($Capt=='SIN_REGISTRO'){
 	$Capt="";
 }else{
  $Capt=$Capt;
 }

 if($Suc<>'*'){
  $filtro4="and ot.suc='$Suc'";
 }else{
  $filtro4=" ";
 }

 if($Institucion<>'*'){
  $filtro6="and ot.institucion='$Institucion'";
 }else{
  $filtro6=" ";
 }

  if($Depto<>'*'){
  $filtro8="and est.depto='$Depto'";
 }else{
  $filtro8=" ";
 }

if($Etapa=='Atencion'){
  $filtro10="otd.usrest";
 }elseif($Etapa=='Captura'){
  $filtro10="otd.capturo";
 }elseif($Etapa=='Impresion'){
  $filtro10="otd.impest";
 }elseif($Etapa=='Proceso'){
  $filtro10="maqdet.usrrec";
 }

//  $Titulo=$_REQUEST[Titulo];

  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>

<?php

  $cSql="select maqdet.usrrec as usuarioP,date_format(ot.fecha,'%Y-%m') as fecha,count(*) as contar,ot.orden,ot.suc,ot.institucion,otd.estudio,est.depto,est.subdepto,sum(otd.precio) as precios,sum(otd.precio*(otd.descuento/100)) as descuentos,otd.statustom
        FROM ot,est,otd,maqdet
        WHERE ot.orden=otd.orden AND otd.estudio=est.estudio and maqdet.orden=ot.orden AND otd.estudio=est.estudio AND maqdet.estudio=otd.estudio and ot.fecha Between '$FechaI' And '$FechaF' $filtro4 $filtro6 $filtro8 and maqdet.usrrec='$Capt' GROUP BY otd.estudio";
/*
  $cSql="SELECT maqdet.usrrec as usuario,date_format(maqdet.fenv,'%Y-%m') as fecha,maqdet.orden,maqdet.mint,maqdet.estudio,est.depto,est.subdepto,count(*) as contar
  from maqdet,est
  WHERE maqdet.fenv Between '$FechaI' And '$FechaF' and maqdet.usrrec='$Capt' and maqdet.estudio=est.estudio GROUP BY maqdet.estudio";
*/
  $UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr>
    <td><div align='center'>
        <font size="4" face="Arial, Helvetica, sans-serif"><strong>Laboratorio Clinico Duran</strong></font><br>
        <font size="2"><?php echo "$Fecha - $Hora"; ?><br>
        <?php echo "<p align='center'><font size='2' face='Arial, Helvetica, sans-serif'>Demanda de estudios del $FechaI al $FechaF Sucursal: $Suc Institucion: $Institucion - $NomI[nombre] - Usuario: $Capt</p>";?>
        <font size="2"><?php // echo "$Titulo"; ?>
        </div>
    </td>
  </tr>
</table>
<font size="2" face="Arial, Helvetica, sans-serif"> <font size="1">
<?php

    echo "<table align='center' width='90%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Depto</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Subdepto</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Estudio</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>#Estudios</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Importe</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Descuentos</font></th>";
    echo "<th align='CENTER' bgcolor='#000000'><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Total</font></th>";

    $Subtotal=0;
		$Total=0;
		$Descuentos=0;
		$Noveces=0;
		$subdep=" ";
		$orden=0;
		$nordenes=0;
    $preciose=0;
    $Noveces2=1;

      while($registro=mysql_fetch_array($UpA)) {
          $departamento1=$registro[depto];
          $subdepartamento1=$registro[subdepto];
          $estudio1=$registro[estudio];

          $cSqla="SELECT orden,estudio,sum(precio) as precios,descuento
          from otd
          WHERE otd.orden=$registro[orden]";

          $UpAa=mysql_query($cSqla,$link);

          $registro2=mysql_fetch_array($UpAa);
            
              echo "<tr>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$departamento1</font></td>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$subdepartamento1</font></td>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>$estudio1</font></td>";
              echo "<td align='center'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($registro[contar])."</strong></font></td>";

              $precio1=$registro[contar]*$registro2[precios];

              echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($precio1,'2')."</strong></font></td>";

              $descuentos1=($registro2[descuento]*$registro2[precios])/100;

              echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($descuentos1,'2')."</strong></font></td>";

              $importe1=$precio1-$descuentos1;
              echo "<td align='right'><strong><font size='1' face='Arial, Helvetica, sans-serif'>".number_format($importe1,'2')."</strong></font></td>";
              echo "</tr>";

              $contarT=$contarT+$registro[contar];
              $precio1T=$precio1T+$precio1;
              $descuentos1T=$descuentos1T+$descuentos1;
              $importe1T=$importe1T+$importe1;
             
      }//fin while

      echo "<tr>";
      echo "<td align='CENTER' bgcolor='#000000'></td>";
      echo "<td align='CENTER' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'> No. Estudios : </font></td>";
      echo "<td align='CENTER' bgcolor='#000000'></td>";
      echo "<td align='center' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($contarT)."</strong></font></td>";
      echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($precio1T,'2')."</strong></font></td>";
      echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($descuentos1T,'2')."</strong></font></td>";
      echo "<td align='right' bgcolor='#000000'><strong><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>".number_format($importe1T,'2')."</strong></font></td>";
      echo "</tr>";

      echo "</table>";
      echo "<br>";

?>
</font>
<div align="left">
<form name="form1" method="post">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
  </form>
</div>
</body>
</html>
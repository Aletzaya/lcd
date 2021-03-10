<?php
session_start();
$Estudio=$_REQUEST[Estudio];
$Orden=$_REQUEST[Orden];
require('../lib/kaplib.php');
$link=conectarse();
$Fecha=date("Y-m-d");
$aMes = array(" ","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$EstA=mysql_query("select descripcion from est where estudio='$Estudio' ",$link);
$Est=mysql_fetch_array($EstA);
$EleA=mysql_query("select * from ele where estudio='$Estudio' order by id",$link);
$OtA=mysql_query("select ot.medico,cli.nombrec,cli.sexo,cli.fechan,med.nombrec,ot.medicon,ot.institucion,ot.diagmedico,cli.afiliacion,ot.fecha from ot,cli,med where ot.orden='$Orden' and ot.cliente=cli.cliente and ot.medico=med.medico",$link);
$Ot=mysql_fetch_array($OtA);
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
?>
<html>
<head>
<title>ANALISIS BACTERIOLOGICO DE ALIMENTOS</title>
</head>
<body>

<?php $Mes       = substr($Ot[fecha],5,2)*1; ?>
<?php $FechaLet  = substr($Ot[fecha],8,2)." de ".$aMes[$Mes]." del ".substr($Ot[fecha],0,4); ?>

<table width='100%' border='0'>
  <tr>
<td width='50%'>&nbsp;</td>
    <td> <hr>
      <strong><font size="1" face="Arial, Helvetica, sans-serif">PACIENTE :<?php if($calc_edad>=200 or $calc_edad==0){echo "$Ot[1] &nbsp;&nbsp;&nbsp; EDAD: --- a&ntilde;os. &nbsp;&nbsp;&nbsp; SEXO: $Ot[2]";} else{echo "$Ot[1] &nbsp;&nbsp;&nbsp; EDAD: $calc_edad a&ntilde;os.&nbsp;&nbsp; SEXO: $Ot[2]";} ?><br>
      <br>
      MEDICO : 
      <?php if($Ot[0]=='MD'){echo "$Ot[0] &nbsp;&nbsp; $Ot[5]";}else{echo "$Ot[0] &nbsp;&nbsp; $Ot[4]";} ?>
      <br>
      <br>
      ORDEN :&nbsp;<?php echo "$Ot[6]-$Orden &nbsp;&nbsp; $Ot[8] &nbsp;&nbsp; $Ot[7] &nbsp;&nbsp; FECHA: $FechaLet "; ?></font></strong> 
      <hr>
    </td>
</tr>
</table>
<p>&nbsp;</p>
<p align='center'><font size='4'><strong>ANALISIS BACTERIOLOGICO DE ALIMENTOS </strong></font></p>
<p align='center'>&nbsp;</p>
<table width='92%' border='1' align="center" cellpadding="2" cellspacing="0" bordercolor="#FFFFFF">
  <tr> 
    <td width='31%'><div align="left"><font face="Arial, Helvetica, sans-serif"><font size="2"></font></font></div></td>
    <td width='29%'><div align="center"><font face="Arial, Helvetica, sans-serif"><font size="2"></font></font></div></td>
    <td width='12%'><div align="center"><font face="Arial, Helvetica, sans-serif"><font size="2"></font></font></div></td>
    <td width='28%' bgcolor="#CCCCCC"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>LIMITES 
    PERMISIBLES</strong></font></div></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC">
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">Fecha de Recepci&oacute;n :&nbsp; </font></td>
    <td colspan="2" align='right'><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?php echo "$Res[c]"; ?></font></strong></div></td>
    <td rowspan="4"> <div align="center"><font face="Arial, Helvetica, sans-serif"><font size="2"></font></font></div> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>SEGUN NOM-093-SSA1-1994. Bienes y servicios tecnica de NMP(5), recuento en placa de mesofilicos aerobios, Staphylococcus aureus, Salmonella. </strong></font></div></td>
  </tr>
    <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">Fecha de analisis  :&nbsp; </font></div></td>
    <td colspan="2" align='right'> <div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?php echo "$Res[c]"; ?></font></strong></div>    </td>
  </tr>
    <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"></font></div>
      <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">Enviada en  :&nbsp;</font></div></td>
    <td colspan="2" align='right'><div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?php echo "$Res[c]"; ?></font></strong></div></td>
  </tr>
   <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">Tipo de Muestra :&nbsp;</font></td>
    <td colspan="2" align='right'> <div align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif"><?php echo "$Res[c]"; ?></font></strong></div></td>
  </tr>
   <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">Mesofilicos 
    Aerobios&nbsp;:&nbsp;</font></div></td>
    <td align='right'> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?php echo "$Res[c]"; ?></strong></font></div></td>
    <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">UFC/gr</font></div></td>
    <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">HASTA 
    10,000, 000 </font></div></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">Coliformes fecales  :&nbsp; </font></div></td>
    <td align='right'> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?php echo "$Res[c]"; ?></strong></font></div></td>
    <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">UFC/gr</font></div></td>
    <td> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif">400 NMP/g </font></div></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC">
    <td align='right'>Staphylococcus Areus :&nbsp; </td>
    <td align='right'><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?php echo "$Res[c]"; ?></strong></font></div></td>
    <td align='right'><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">UFC/gr</font></div></td>
    <td align='right'><div align="center">1,000<font size="2" face="Arial, Helvetica, sans-serif"> UFC/gr</font></div></td>
  </tr>
    <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC">
    <td align='right'>Salmonella ssp :&nbsp;</td>
    <td align='right'><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?php echo "$Res[c]"; ?></strong></font></div></td>
    <td align='right'><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">en 25 g. </font></div></td>
    <td align='right'><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">AUSENTE/25 g </font></div></td>
  </tr>
    <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
    <tr bordercolor="#CCCCCC">
      <td align='right'>Vibrio cholerae : &nbsp;</td>
      <td align='right'><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?php echo "$Res[c]"; ?></strong></font></div></td>
      <td align='right'><div align="center">/50 g </div></td>
      <td align='right'><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">AUSENTE/25 g </font></div></td>
    </tr>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'> <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">Observaciones:</font></div></td>
    <td colspan="3" align='right'> <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><strong><?php echo "$Res[t]"; ?>&nbsp;</strong></font></div>      <div align="center"></div>
    <div align="center"></div></td>
  </tr>
</table>
<table width="47%" height="132" border="2" align="left" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF">
  <tr>
    <td><div align="center">
        <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>A T E N T A M E N T E</strong></font></p>
        <p>&nbsp;</p>
    </div></td>
  </tr>
  <tr>
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">RAMON DUANA RAMIREZ</font></div></td>
  </tr>
  <tr>
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">QUIMICO FARMACO BIOLOGO</font></div></td>
  </tr>
  <tr>
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">RESPONSABLE</font></div></td>
  </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
 <input type='submit' name='Impresion' value='Impresion - <?php echo $Fecha;?>' onCLick='print()'>
</body>
</html>
<?php
mysql_close();
?>

<?php
session_start();
$Estudio=$_REQUEST[Estudio];
$Orden=$_REQUEST[Orden];
require('../lib/kaplib.php');
$link=conectarse();$Fecha=date("Y-m-d");
$EstA=mysql_query("select descripcion from est where estudio='$Estudio' ",$link);
$Est=mysql_fetch_array($EstA);
$EleA=mysql_query("select * from ele where estudio='$Estudio' order by id",$link);
$OtA=mysql_query("select ot.medico,cli.nombrec,cli.sexo,cli.fechan,med.nombrec,ot.medicon,ot.institucion,ot.diagmedico,cli.afiliacion from ot,cli,med where ot.orden='$Orden' and ot.cliente=cli.cliente and ot.medico=med.medico",$link);
$Ot=mysql_fetch_array($OtA);
$Edad=$Fecha-$Ot[3];
?>
<html>
<head>
<title>CITOQUIMICO DE DIALISIS</title>
</head>
<body>
<table width='100%' border='0'>
<tr>
<td width='40%'>&nbsp;</td>
    <td> <hr>
      <font size="1" face="Arial, Helvetica, sans-serif"><strong>PACIENTE :<?php echo "$Ot[1] &nbsp;&nbsp;&nbsp; EDAD: $Edad a&ntilde;os. &nbsp;&nbsp;&nbsp; SEXO: $Ot[2]"; ?><br>
      <br>
      MEDICO : 
      <?php if($Ot[0]=='MD'){echo "$Ot[0] &nbsp;&nbsp; $Ot[5]";}else{echo "$Ot[0] &nbsp;&nbsp; $Ot[4]";} ?>
      <br>
      <br>
      ORDEN :<?php echo "$Ot[6]-$Orden &nbsp;&nbsp; $Ot[8] &nbsp;&nbsp; $Ot[7] &nbsp;&nbsp; FECHA: $Fecha "; ?> 
      </strong></font> 
      <hr>
    </td>
</tr>
</table>
<p>&nbsp;</p>
<p align='center'><font size='4'><strong> CITOQUIMICO DE DIALISIS</strong></font></p>
<table width='66%' border='1' align="center" cellpadding="2" cellspacing="0" bordercolor="#FFFFFF">
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td width="27%" align='right'><font size="2" face="Arial, Helvetica, sans-serif">LEUCOCITOS 
      :&nbsp; &nbsp;</font></td>
    <td width="33%" align='right'><font size="2" face="Arial, Helvetica, sans-serif"><?php echo number_format($Res[n],'2'); ?></font></td>
    <td width="40%"><font size="2" face="Arial, Helvetica, sans-serif">x 10 ^ 
      3/uL</font></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">ERITROCITOS 
      :&nbsp; &nbsp;</font></td>
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif"><?php echo number_format($Res[n],'1'); ?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">x 10 ^ 6/uL</font></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">GLUCOSA 
      :&nbsp; &nbsp;</font></td>
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif"><?php echo number_format($Res[n],'2'); ?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">mg/dl</font></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">CLORO 
      :&nbsp; &nbsp;</font></td>
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif"><?php echo number_format($Res[n],'2'); ?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">meq/L</font></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">PROTEINAS 
      :&nbsp; &nbsp;</font></td>
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif"><?php echo number_format($Res[n],'2'); ?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">g/dl</font></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">TINCION 
      DE GRAM :&nbsp; &nbsp;</font></td>
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif"><?php echo "$Res[t]"; ?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><font size="2" face="Arial, Helvetica, sans-serif">OBSERVACIONES 
      :&nbsp; &nbsp;</font></td>
    <td colspan="2" align='left'><font size="2" face="Arial, Helvetica, sans-serif"><?php echo "$Res[t]"; ?></font></td>
  </tr>
</table>
<table width="47%" height="113" border="2" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF">
  <tr> 
    <td><div align="center"> 
        <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>A T E N 
          T A M E N T E</strong></font></p>
        <p>&nbsp;</p>
      </div></td>
  </tr>
  <tr> 
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">RAUL 
        RUFINO SARMIENTO</font></div></td>
  </tr>
  <tr> 
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">QUIMICO 
        FARMACO BIOLOGO CED. PROF. 3216788.</font></div></td>
  </tr>
  <tr> 
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">RESPONSABLE</font></div></td>
  </tr>
</table>
<form name='form1' method='post' action='../menu.php'>
 <input type='submit' name='Impresion' value='Impresion' onCLick='print()'>
</form>
</body>
</html>
<?php
mysql_close();
?>

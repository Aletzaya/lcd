<?php
session_start();
$Estudio=$_REQUEST[Estudio];
$Orden=$_REQUEST[Orden];
require('../lib/kaplib.php');
$link=conectarse();
$Fecha=date("Y-m-d");
$EstA=mysql_query("select descripcion from est where estudio='$Estudio' ",$link);
$Est=mysql_fetch_array($EstA);
$EleA=mysql_query("select * from ele where estudio='$Estudio' order by id",$link);
$OtA=mysql_query("select ot.medico,cli.nombrec,cli.sexo,cli.fechan,med.nombrec,ot.medicon,ot.institucion,ot.diagmedico,cli.afiliacion,ot.fecharec from ot,cli,med where ot.orden='$Orden' and ot.cliente=cli.cliente and ot.medico=med.medico",$link);
$Ot=mysql_fetch_array($OtA);
$Edad=$Fecha-$Ot[3];
?>
<html>
<head>
<title>AC-HIV</title>
</head>
<body>
<table width='100%' border='0'>
<tr>
<td width='50%'>&nbsp;</td>
    <td> <hr>
      <strong><font size="1" face="Arial, Helvetica, sans-serif">PACIENTE :<?php if($Edad>=200 or $Edad==0){echo "$Ot[1] &nbsp;&nbsp;&nbsp; EDAD: --- a&ntilde;os. &nbsp;&nbsp;&nbsp; SEXO: $Ot[2]";} else{echo "$Ot[1] &nbsp;&nbsp;&nbsp; EDAD: $Edad a&ntilde;os.&nbsp;&nbsp; SEXO: $Ot[2]";} ?><br>
      <br>
      MEDICO : 
      <?php if($Ot[0]=='MD'){echo "$Ot[0] &nbsp;&nbsp; $Ot[5]";}else{echo "$Ot[0] &nbsp;&nbsp; $Ot[4]";} ?>
      <br>
      <br>
      ORDEN :&nbsp;<?php echo "$Ot[6]-$Orden &nbsp;&nbsp; $Ot[8] &nbsp;&nbsp; $Ot[7] &nbsp;&nbsp; FECHA: $Ot[9] "; ?></font></strong> 
      <hr>
    </td>
</tr>
</table>
<p>&nbsp;</p>
<p align='center'><font size='4' face="Arial, Helvetica, sans-serif"><strong> HIV Ag/Ac - COMBO - </strong></font></p>
<table width='95%' border='1' align="center" cellpadding="2" cellspacing="0" bordercolor="#FFFFFF">
  <tr> 
    <td width='21%'>&nbsp;</td>
    <td width='26%'>&nbsp;</td>
    <td width='11%' bgcolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>UNIDAD</strong></font></div></td>
    <td width='42%' bgcolor="#CCCCCC"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>NORMAL</strong></font></div></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">RESULTADO : &nbsp; &nbsp;</font></strong></td>
    <td align='right'> <div align="center"><strong><font size="4" face="Arial, Helvetica, sans-serif"> 
        <?php echo "$Res[c]"; ?>
      </font></strong></div></td>
    <td><div align="center">
      <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>S/CO</strong></font></p>
    </div></td>
    <td><p align="center"><font size="2" face="Arial, Helvetica, sans-serif"><strong>0.1 A 0.8 NEGATIVO</strong></font>
      <hr>      
      <p align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif">0.8 A 1.2 ZONA GRIS</font></strong></p>
      <p align="justify">Resultados en zona gris (Zona de Alerta) se sugiere repetir estudio en 30 dias. </p>
      
      <hr>      <p align="center"><strong><font size="2" face="Arial, Helvetica, sans-serif">MAYOR  A 1.2 POSITIVO</font></strong></p>
    <p align="justify">Resultados mayor a 1.2 se necesita realizar prueba confirmatoria Western Blot ya que puede tratarse de un falso positivo por la presencia de otras infecciones virales, esta prueba no confirma la infeccion de SIDA.</p>     
    <hr>    <p align="justify">T&eacute;cnica: <font size="2" face="Arial, Helvetica, sans-serif">MEIA / Enzimoinmunoanalisis de Microparticulas </font></p></td>
  </tr>
  <?php
$Ele=mysql_fetch_array($EleA);
$Id=$Ele[id];
$ResA=mysql_query("select c,d,n,l,t from resul where orden='$Orden' and estudio='$Estudio' and elemento='$Id' ",$link);
$Res=mysql_fetch_array($ResA);
?>
  <tr bordercolor="#CCCCCC"> 
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">OBSERVACIONES 
      :&nbsp;&nbsp; &nbsp;</font></strong></td>
    <td colspan="3" align='right'><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?php echo "$Res[t]"; ?></font></div></td>
  </tr>
</table>
<table width="47%" height="113" border="2" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF">
  <tr> 
    <td><div align="center">
        <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>A T E N 
        T A M E N T </strong>E</font></p>
        <p>&nbsp;</p>
    </div></td>
  </tr>
  <tr>
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">ANA LILIA CUESTA GARDU&Ntilde;O</font></div></td>
  </tr>
  <tr>
    <td><div align="center">
        <p><font size="2" face="Arial, Helvetica, sans-serif">QUIMICO FARMACO BIOLOGO CED. PROF. 1015665</font></p>
    </div></td>
  </tr>
  <tr> 
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">RESPONSABLE</font></div></td>
  </tr>
</table>
<p align="right"><img src="../images/Cedula.JPG" width="185" height="260">&nbsp;&nbsp;&nbsp;<img src="../images/Cedula2.JPG" width="185" height="260"></p>
<p>
  <input type='submit' name='Impresion' value='Impresion' onCLick='print()'>
  </p>
</p>
</body>
</html>
<?php
mysql_close();
?>

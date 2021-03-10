<?php
  // Es una copia de clientes solo s cambia las url a clienteseord
  session_start();
  $busca=$_REQUEST[busca];
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body>
<form action="ordenesnvas.php" method="get" name="manda">
  <div align="right">
    <input type="submit" name="Original" value="Orden de Trabajo" onClick="print()">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    <input type="hidden" name="op" value="br">
  </div>
</form>

<?php
include("lib/kaplib.php");
$link=conectarse();

$cSqlO="select otd.estudio,est.descripcion,otd.precio,otd.descuento from otd,est where otd.estudio=est.estudio and otd.orden='$busca'";
$OtdA=mysql_query($cSqlO,$link);
$Otd=mysql_fetch_array($OtdA);

$CjaA=mysql_query("select sum(importe) from cja where orden='$busca'",$link);
$Cja=mysql_fetch_array($CjaA);

$OtA=mysql_query("select ot.cliente,ot.medico,ot.medicon,ot.fecha,ot.institucion,ot.fechae,ot.importe,inst.nombre from ot,inst where inst.institucion=ot.institucion and ot.orden='$busca'",$link);
$Ot=mysql_fetch_array($OtA);

$CliA=mysql_query("select * from cli where cliente='$Ot[cliente]'",$link);
$Cli=mysql_fetch_array($CliA);

$MedA=mysql_query("select nombrec from med where medico='$Ot[medico]'",$link);
$Med=mysql_fetch_array($MedA);

$Fecha=date("Y-m-d");

?>
<table width="65%" height="46" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF">
  <tr>
    <td height="40" valign="bottom">
        <div align="right"><font size="-2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>Col.
        Centro Texcoco, Edo. de M&eacute;x.</em></strong></font></div>
    </td>
  </tr>
</table>
<table width="52%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td height="14"> 
      <div align="right"><font size="-2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>C. P. 56100</em></strong></font></div></td>
  </tr>
</table>
<table width="96%" border="0" align="center">
  <tr>
    <td align='center' height="20"> <font size="2" face="Arial, Helvetica, sans-serif"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No.Orden 
      <?php echo $busca;?> &nbsp; &nbsp; Fecha : <?php echo $Fecha;?>&nbsp; </font> 
    </td>
  </tr>
</table>

<table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="15" align='left'><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2"><font size="1">&nbsp;<?php echo $Ot[0]."_".substr($Cli[nombrec],0,50);?>&nbsp;</font></font></font></td>
    <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align='right'><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td width="45%" rowspan="2" align='left'> 
      <p><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        <?php if($Ot[medico]=='MD'){echo $Ot[medico]."_".$Ot[medicon];} else{echo $Ot[medico]."_".$Med[nombrec];}?>
        </font> <font face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font> </p>
      </td>
    <td width="13%" height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td width="33%"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td rowspan="2" align='left' valign="top"> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font> <font size="1" face="Arial, Helvetica, sans-serif"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $Ot[institucion]."_".$Ot[nombre];?> 
      &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      </font> </td>
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" align='left'><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      &nbsp;</font> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Ot[observaciones];?></font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font> </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" align='left'><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" valign="top"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Ot[fechae];?></font></font></td>
    <td>&nbsp;</td>
    <td align='right'>&nbsp;</td>
    <td align='right'>&nbsp;</td>
  </tr>
  <tr> 
    <td height="20"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
      :&nbsp;&nbsp;&nbsp;</font></strong></td>
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Ot[6],"2"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></strong></td>
  </tr>
  <tr> 
    <td height="20"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Cja[0],"2");?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Ot[6]-$Cja[0],"2");?></font></td>
  </tr>
</table>


<?php

$cSqlO="select otd.estudio,est.descripcion,otd.precio,otd.descuento from otd,est where otd.estudio=est.estudio and otd.orden='$busca'";
$OtdA=mysql_query($cSqlO,$link);
$Otd=mysql_fetch_array($OtdA);

$CjaA=mysql_query("select sum(importe) from cja where orden='$busca'",$link);
$Cja=mysql_fetch_array($CjaA);

$OtA=mysql_query("select ot.cliente,ot.medico,ot.medicon,ot.fecha,ot.institucion,ot.fechae,ot.importe,inst.nombre from ot,inst where inst.institucion=ot.institucion and ot.orden='$busca'",$link);
$Ot=mysql_fetch_array($OtA);

$CliA=mysql_query("select * from cli where cliente='$Ot[cliente]'",$link);
$Cli=mysql_fetch_array($CliA);

$MedA=mysql_query("select nombrec from med where medico='$Ot[medico]'",$link);
$Med=mysql_fetch_array($MedA);

$Fecha=date("Y-m-d");

?>
<table width="65%" height="122" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF">
  <tr> 
    <td height="40" valign="bottom"> <div align="right"><font size="-2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>Col. 
        Centro Texcoco, Edo. de M&eacute;x.</em></strong></font></div></td>
  </tr>
</table>
<table width="52%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr> 
    <td height="14"> <div align="right"><font size="-2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>C. 
        P. 56100</em></strong></font></div></td>
  </tr>
</table>
<table width="96%" border="0" align="center">
  <tr>
    <td align='center' height="20"> <font size="2" face="Arial, Helvetica, sans-serif"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No.Orden 
      <?php echo $busca;?> &nbsp; &nbsp; Fecha : <?php echo $Fecha;?>&nbsp; </font> 
    </td>
  </tr>
</table>

<table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="15" align='left'><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2"><font size="1">&nbsp;&nbsp;<?php echo $Ot[0]."_".substr($Cli[nombrec],0,50);?>&nbsp;</font></font></font></td>
    <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align='right'><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td width="45%" rowspan="2" align='left'> 
      <p><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        <?php if($Ot[medico]=='MD'){echo $Ot[medico]."_".$Ot[medicon];} else{echo $Ot[medico]."_".$Med[nombrec];}?>
        </font> <font face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font> </p>
      </td>
    <td width="13%" height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td width="33%"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td rowspan="2" align='left' valign="top"> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp; 
      </font> <font size="1" face="Arial, Helvetica, sans-serif"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $Ot[institucion]."_".$Ot[nombre];?> 
      &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      </font> </td>
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td><font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" align='left'><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      &nbsp;</font> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Ot[observaciones];?></font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font> </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" align='left'><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" valign="top"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Ot[fechae];?></font></font></td>
    <td>&nbsp;</td>
    <td align='right'>&nbsp;</td>
    <td align='right'>&nbsp;</td>
  </tr>
  <tr> 
    <td height="20"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
      :&nbsp;&nbsp;&nbsp;</font></strong></td>
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Ot[6],"2"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></strong></td>
  </tr>
  <tr> 
    <td height="20"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Cja[0],"2");?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Ot[6]-$Cja[0],"2");?></font></td>
  </tr>
</table>


<?php

$cSqlO="select otd.estudio,est.descripcion,otd.precio,otd.descuento from otd,est where otd.estudio=est.estudio and otd.orden='$busca'";
$OtdA=mysql_query($cSqlO,$link);
$Otd=mysql_fetch_array($OtdA);

$CjaA=mysql_query("select sum(importe) from cja where orden='$busca'",$link);
$Cja=mysql_fetch_array($CjaA);

$OtA=mysql_query("select ot.cliente,ot.medico,ot.medicon,ot.fecha,ot.institucion,ot.fechae,ot.importe,inst.nombre from ot,inst where inst.institucion=ot.institucion and ot.orden='$busca'",$link);
$Ot=mysql_fetch_array($OtA);

$CliA=mysql_query("select * from cli where cliente='$Ot[cliente]'",$link);
$Cli=mysql_fetch_array($CliA);

$MedA=mysql_query("select nombrec from med where medico='$Ot[medico]'",$link);
$Med=mysql_fetch_array($MedA);

$Fecha=date("Y-m-d");

?>
<table width="65%" height="133" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#FFFFFF">
  <tr> 
    <td height="40" valign="bottom"> <div align="right"><font size="-2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>Col. 
        Centro Texcoco, Edo. de M&eacute;x.</em></strong></font></div></td>
  </tr>
</table>
<table width="52%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr> 
    <td height="14"> <div align="right"><font size="-2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>C. 
        P. 56100</em></strong></font></div></td>
  </tr>
</table>
<table width="96%" border="0" align="center">
  <tr>
    <td align='center' height="20"> <font size="2" face="Arial, Helvetica, sans-serif"> 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No.Orden 
      <?php echo $busca;?> &nbsp; &nbsp; Fecha : <?php echo $Fecha;?>&nbsp; </font> 
    </td>
  </tr>
</table>

<table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="15" align='left'><font face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="2"><font size="1">&nbsp;<?php echo $Ot[0]."_".substr($Cli[nombrec],0,50);?>&nbsp;</font></font></font></td>
    <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align='right'><font face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <tr> 
    <td width="45%" rowspan="2" align='left'> <p><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
        <?php if($Ot[medico]=='MD'){echo $Ot[medico]."_".$Ot[medicon];} else{echo $Ot[medico]."_".$Med[nombrec];}?>
        </font> <font face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
        </font> </p>
      </td>
    <td width="13%" height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td width="33%"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td rowspan="2" align='left' valign="top"> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font> <font size="1" face="Arial, Helvetica, sans-serif"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      <?php echo $Ot[institucion]."_".$Ot[nombre];?> &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
      </font> </td>
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15"> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" align='left'><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      &nbsp;</font> <font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Ot[observaciones];?></font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font> </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" align='left'><font size="1" face="Arial, Helvetica, sans-serif">&nbsp; 
      </font><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Otd[0];?></font> 
    </td>
    <td> <font size="1" face="Arial, Helvetica, sans-serif"><?php echo substr($Otd[1],0,28);?></font> 
    </td>
    <td width="9%" align='right'> <font size="1" face="Arial, Helvetica, sans-serif"> 
      <?php
      
       echo number_format($Otd[2]*(1-($Otd[3]/100)),"2"); 
       
		 $Otd=mysql_fetch_array($OtdA);
       
      ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font> </td>
  </tr>
  <tr> 
    <td height="15" valign="top"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size="1" face="Arial, Helvetica, sans-serif"><?php echo $Ot[fechae];?></font></font></td>
    <td>&nbsp;</td>
    <td align='right'>&nbsp;</td>
    <td align='right'>&nbsp;</td>
  </tr>
  <tr> 
    <td height="20"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">Total 
      :&nbsp;&nbsp;&nbsp;</font></strong></td>
    <td align='right'><strong><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Ot[6],"2"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></strong></td>
  </tr>
  <tr> 
    <td height="20"><font size="1" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Cja[0],"2");?></font></td>
    <td><font size="2" face="Arial, Helvetica, sans-serif">$&nbsp;&nbsp;<?php echo number_format($Ot[6]-$Cja[0],"2");?></font></td>
  </tr>
</table>


</body>
</html>
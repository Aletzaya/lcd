<?php
  // Es una copia de clientes solo s cambia las url a clienteseord
  session_start();
  $busca=$_REQUEST[busca];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Impresion Recibo</title>
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
 <style type="text/css">
<!--
.Estilo11 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10;
}
.Estilo18 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo20 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
.Estilo23 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo26 {font-size: 11px}
.Estilo28 {color: #CCCCCC}
.Estilo29 {font-size: 24px}
.Estilo31 {font-size: 12px}
.Estilo32 {font-size: 36px}
-->
 </style>
</head>

<body>
<?php
include("lib/kaplib.php");
$link=conectarse();

$cSqlO="select otd.estudio,est.descripcion,otd.precio,otd.descuento from otd,est where otd.estudio=est.estudio and otd.orden='$busca'";
$OtdA=mysql_query($cSqlO,$link);
$Otd=mysql_fetch_array($OtdA);

$CjaA=mysql_query("select sum(importe) from cja where orden='$busca'",$link);
$Cja=mysql_fetch_array($CjaA);

$OtA=mysql_query("select ot.cliente,ot.medico,ot.medicon,ot.fecha,ot.institucion,ot.fechae,ot.importe,inst.nombre,ot.servicio,ot.recepcionista from ot,inst where inst.institucion=ot.institucion and ot.orden='$busca'",$link);
$Ot=mysql_fetch_array($OtA);

$CliA=mysql_query("select * from cli where cliente='$Ot[cliente]'",$link);
$Cli=mysql_fetch_array($CliA);

$MedA=mysql_query("select nombrec from med where medico='$Ot[medico]'",$link);
$Med=mysql_fetch_array($MedA);

$Fecha=date("Y-m-d");
$Hora=date("H:i");

?>
<div id="Layer1" style="position:absolute; left:121px; top:240px; width:305px; height:34px; z-index:1; font-size: "4"; font-weight: bold; color: #CCCCCC; font-family: Arial, Helvetica, sans-serif;">
  <div align="center" class="Estilo28 Estilo32"><?php echo substr($Ot[servicio],0,28);?>&nbsp;</div>
</div>
<div id="Layer2" style="position:absolute; left:719px; top:496px; width:258px; height:30px; z-index:2; font-size: "4"; font-weight: bold; color: #CCCCCC; font-family: Arial, Helvetica, sans-serif;">
  <div align="center" class="Estilo28 Estilo29">&nbsp;<?php echo substr($Ot[servicio],0,28);?></div>
</div>
<table width="100%" height="813" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" class="Estilo18">
  <tr bordercolor="#FFFFFF">
    <td width="4%" height="108">&nbsp;</td>
    <td colspan="2" rowspan="2">&nbsp;</td>
    <td width="8%" rowspan="2">&nbsp;</td>
    <td width="10%">&nbsp;</td>
    <td width="13%">&nbsp;</td>
    <td width="5%" rowspan="2">&nbsp;</td>
    <td width="6%" rowspan="2">&nbsp;</td>
    <td width="3%" rowspan="2">&nbsp;</td>
    <td width="9%" rowspan="2">&nbsp;</td>
    <td width="9%" rowspan="2">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="32">&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Fecha;?></span></td>
    <td><span class="Estilo31"><?php echo $Hora;?></span></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="24" colspan="2"><span class="Estilo31"><strong><?php echo $Ot[institucion]." - ".$busca;?></strong></span></td>
    <td colspan="2"><span class="Estilo31"><strong><?php echo $Ot[0]." - ".substr($Cli[nombrec],0,50);?></strong></span></td>
    <td colspan="2">
      <span class="Estilo31">
      <?php if($Ot[medico]=='MD'){echo $Ot[medico]." - DR. ".$Ot[medicon];} else{echo $Ot[medico]." - DR. ".$Med[nombrec];}?>
    </span>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="22" colspan="2" bgcolor="#CCCCCC" ><div align="center" class="Estilo11 Estilo23 Estilo26">Clave</div>      
    <div align="center" class="Estilo18"></div></td>
    <td bgcolor="#CCCCCC"><span class="Estilo20 Estilo26">Estudios</span></td>
    <td bgcolor="#CCCCCC"><div align="center" class="Estilo11 Estilo31"><strong>Desc.</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center" class="Estilo11 Estilo31"><strong>Precio</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center" class="Estilo11 Estilo31"><strong>Importe</strong></div></td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="27" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
	  <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
</div></td>
    <td><div align="right" class="Estilo31">
	  <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
	<?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
        
    </div></td>
    <td>&nbsp;</td>
    <td colspan="4" rowspan="2"><form action="ordenesnvas.php" method="get" name="manda" class="Estilo31">
        <div align="center">
          <input type="submit" name="Original" value="No. Orden: <?php echo $Ot[institucion];?> - <?php echo $busca;?>" onClick="print()">
	      <input type="hidden" name="op" value="br">
        </div>
    </form></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="23" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="26" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
    <td colspan="3"><span class="Estilo31"><?php echo $Fecha;?></span></td>
    <td><span class="Estilo31"><?php echo $Hora;?></span></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="25" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
	<?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="26" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></span></td>
    <td><div align="center">
	  <div align="center" class="Estilo31">
	    <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
	    
      </div>
    <td><div align="right" class="Estilo31">
      <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
  <td colspan="5">      <span class="Estilo31"><?php echo $Ot[0]." - ".substr($Cli[nombrec],0,50);?></span></tr>
  <tr bordercolor="#FFFFFF">
    <td height="25" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="31" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
    <td colspan="4">
      <span class="Estilo31">
      <?php if($Ot[medico]=='MD'){echo $Ot[medico]." - DR. ".$Ot[medicon];} else{echo $Ot[medico]." - DR. ".$Med[nombrec];}?>
    </span>   </td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="25" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
      <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
</div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="24" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
    <td><span class="Estilo31">CAPTUR&Oacute;:</span></td>
    <td colspan="3"><span class="Estilo31">&nbsp;<?php echo $Ot[recepcionista];?></span></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="26" colspan="2"><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo31"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td>&nbsp;</td>
    <td bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>Estudios</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>Desc.</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>Precio</strong></div></td>
    <td bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>Importe</strong></div></td>
  </tr>
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
$Hora=date("H:i");

?>
  <tr bordercolor="#FFFFFF">
    <td colspan="3" rowspan="3" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo31"><strong>Observaciones:</strong> <br> 
      Si requiere factura favor de traer copia de su R.F.C. <br>
    Verificar que los datos de su recibo sean correctos. </span></td>
    <td colspan="2" bordercolor="#CCCCCC" bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>Importe Total :</strong></div></td>
    <td bordercolor="#CCCCCC"><div align="right" class="Estilo31"><strong>$&nbsp;<?php echo number_format($Ot[6],"2"); ?></strong></div></td>
    <td>&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="25" colspan="2" bordercolor="#CCCCCC" bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>A cuenta : </strong></div></td>
    <td bordercolor="#CCCCCC"><div align="right" class="Estilo31"><strong>$&nbsp;<?php echo number_format($Cja[0],"2");?></strong></div></td>
    <td>&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="27" colspan="2" bordercolor="#CCCCCC" bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>Resta : </strong></div></td>
    <td bordercolor="#CCCCCC"><div align="right" class="Estilo31"><strong>$&nbsp;<?php echo number_format($Ot[6]-$Cja[0],"2");?></strong></div></td>
    <td>&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td colspan="3" rowspan="11" bordercolor="#CCCCCC"><div align="center" class="Estilo31"></div>            <div align="center" class="Estilo31"><img src="Dia%20internacional%20de%20la%20mujer.jpg" width="340" height="300"></div>            </td>
    <td colspan="3" rowspan="11" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo31">Fray Pedro de Gante No. 108 Col. Centro. </div>      <div align="center" class="Estilo31">Texcoco, Edo. de M&eacute;x. ( Frente a Catedral ) </div>      <div align="center" class="Estilo31">Tels: ( 01 595 ) 95 4 11 40 - 95 4 62 96 </div>   
      <span class="Estilo31"><br>   
      </span>
      <div align="center" class="Estilo31"><strong>Horario:</strong></div>      <div align="center" class="Estilo31">Lunes a Viernes: 07:00 a 20:00 hrs. </div>      <div align="center" class="Estilo31">Sabados: 07: 00 a 16:00 hrs. </div>      <div align="center" class="Estilo31">Domingos: 08:00 a 14:00 hrs. </div>   
      <span class="Estilo31"><br>   
      </span>
    <div align="center" class="Estilo31"><strong>Horarios de Entrega:</strong></div>      <div align="center" class="Estilo31">Lun. a Sab. a partir de 15:00 hrs. del dia prometido </div>      <div align="center" class="Estilo31">Dom. a partir de  13:00 hrs. del dia prometido.</div>      <div align="center" class="Estilo31">Y posterior a la fecha de entrega en horario normal</div></td>
    <td height="26">&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="26">&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <div align="center">
          <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
        </div>
      </div>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td height="23">&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="Estilo31"><?php echo $Otd[0];?></span></td>
    <td><div align="center" class="Estilo31">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo31">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
    <td colspan="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>Importe Total : </strong></div></td>
    <td bordercolor="#CCCCCC"><div align="right" class="Estilo31"><strong>$&nbsp;<?php echo number_format($Ot[6],"2"); ?></strong></div></td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
    <td colspan="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC"><div align="center" class="Estilo31"><strong>A cuenta : </strong></div></td>
    <td bordercolor="#CCCCCC"><div align="right" class="Estilo31"><strong>$&nbsp;<?php echo number_format($Cja[0],"2");?></strong></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC" ><div align="center" class="Estilo31"><strong>Resta : </strong></div></td>
    <td bordercolor="#CCCCCC"><div align="right" class="Estilo31"><strong>$&nbsp;<?php echo number_format($Ot[6]-$Cja[0],"2");?></strong></div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="53">&nbsp;</td>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>

</body>
</html>

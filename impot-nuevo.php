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
Estilo4 {
	font-size: xx-small;
	font-family: Arial, Helvetica, sans-serif;
}
Estilo6 {font-family: Arial, Helvetica, sans-serif; font-size: x-small; }
Estilo7 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: x-small;
}
Estilo10 {color: #CCCCCC; font-weight: bold;}
Estilo11 {font-family: Arial, Helvetica, sans-serif}
Estilo12 {font-size: xx-small}
Estilo14 {font-family: Arial, Helvetica, sans-serif; font-size: x-small; font-weight: bold; }
-->
 </style>
</head>

<body>
<?php
include("lib/kaplib.php");
$link=conectarse();

$cSqlO="select otd.estudio,est.descripcion,otd.precio,otd.descuento,otd.orden from otd,est where otd.estudio=est.estudio and otd.orden='$busca'";
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
</span>
<div id="Layer1" style="position:absolute; left:121px; top:240px; width:305px; height:34px; z-index:1; font-size: 26; font-weight: bold; color: #CCCCCC; font-family: Arial, Helvetica, sans-serif;">
  <div align="center"><?php echo substr($Ot[servicio],0,28);?></div>
</div>
<div id="Layer2" style="position:absolute; left:719px; top:456px; width:258px; height:30px; z-index:2; font-size: 23; font-family: Arial, Helvetica, sans-serif;">
  <div align="center" class="Estilo10">&nbsp;<?php echo substr($Ot[servicio],0,28);?></div>
</div>
<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td width="4%" height="87" class="Estilo7">&nbsp;</td>
    <td colspan="2" rowspan="2" class="Estilo7">&nbsp;</td>
    <td width="8%" rowspan="2" class="Estilo7">&nbsp;</td>
    <td width="10%" class="Estilo7">&nbsp;</td>
    <td width="13%" class="Estilo7">&nbsp;</td>
    <td width="5%" rowspan="2" class="Estilo7">&nbsp;</td>
    <td width="6%" rowspan="2" class="Estilo7">&nbsp;</td>
    <td width="3%" rowspan="2" class="Estilo7">&nbsp;</td>
    <td width="9%" rowspan="2" class="Estilo7">&nbsp;</td>
    <td width="9%" rowspan="2" class="Estilo7">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="32">&nbsp;</td>
    <td><span class="Estilo7"><?php echo $Fecha;?></span></span></td>
    <td><span class="Estilo7"><?php echo $Hora;?></span></span></td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo6">
    <td height="24" colspan="2"><span class="Estilo7"></span></span><span class="Estilo7"><strong><?php echo $Ot[institucion]." - ".$busca;?></strong></span></td>
    <td colspan="2"><span class="Estilo7"><strong><?php echo $Ot[0]." - ".substr($Cli[nombrec],0,50);?></strong></span></span><span class="Estilo7"></span></td>
    <td colspan="2">
      <span class="Estilo4">
    <?php if($Ot[medico]=='MD'){echo $Ot[medico]." - DR. ".$Ot[medicon];} else{echo $Ot[medico]." - DR. ".$Med[nombrec];}?>
    </span></span></td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo7">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="22" colspan="2" bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Clave</strong></div>      <div align="center" class="Estilo7"></div></td>
    <td bgcolor="#CCCCCC" class="Estilo7"><span class="Estilo14">Estudios</span></td>
    <td bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Desc</strong>.</div></td>
    <td bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Precio</strong></div></td>
    <td bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Importe</strong></div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="4" class="Estilo7">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="27" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
	  <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
</div></td>
    <td><div align="right" class="Estilo7">
	  <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
	<?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
        
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="4" rowspan="2" class="Estilo7"><form action="file://///Server-lcd/Unidad%20de%20Disco%20Duro/Program%20Files/nusphere/apache/htdocs/ordenesnvas.php" method="get" name="manda" class="Estilo7">
        <div align="center">
          <input type="submit" name="Original" value="No. Orden: <?php echo $Ot[institucion];?> - <?php echo $Otd[orden];?>" onClick="print()">
        </div>
    </form></td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="23" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo6">
    <td height="26" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="3" class="Estilo7"><span class="Estilo7"><?php echo $Fecha;?></span></td>
    <td class="Estilo7"><span class="Estilo7"><?php echo $Hora;?></span></td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="25" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
	<?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="4" class="Estilo7">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="26" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
	  <div align="center" class="Estilo7">
	    <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
	    
      </div>
    <td><div align="right" class="Estilo7">
      <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="4" class="Estilo7"><span class="Estilo7"><?php echo $Ot[0]." - ".substr($Cli[nombrec],0,50);?></span></td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="25" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="4" class="Estilo7">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="31" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="4" class="Estilo7"><span class="Estilo7">
      <?php if($Ot[medico]=='MD'){echo $Ot[medico]." - DR. ".$Ot[medicon];} else{echo $Ot[medico]." - DR. ".$Med[nombrec];}?>
    </span></td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="25" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
      <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
</div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="4" class="Estilo7">&nbsp;</td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="24" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo7"><span class="Estilo7">CAPTUR&Oacute;:</span></td>
    <td colspan="3" class="Estilo7"><span class="Estilo7">&nbsp;<?php echo $Ot[recepcionista];?></span></td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo7">
    <td height="26" colspan="2"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td><span class="Estilo7"><?php echo substr($Otd[1],0,28);?></span></td>
    <td><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
    <td class="Estilo7">&nbsp;</td>
    <td bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Estudios</strong></div></td>
    <td bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Desc.</strong></div></td>
    <td bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Precio</strong></div></td>
    <td bgcolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"><strong>Importe</strong></div></td>
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
  <tr bordercolor="#FFFFFF" class="Estilo6">
    <td colspan="3" rowspan="3" bordercolor="#CCCCCC" bgcolor="#FFFFFF" class="Estilo7"><span class="Estilo7"><strong>Observaciones:</strong> <br> Si requiere factura favor de traer copia de su R.F.C. <br>
    Verificar que los datos de su recibo sean correctos. </span></td>
    <td colspan="2" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="Estilo6"><div align="center" class="Estilo7"><strong>Importe Total :</strong></div></td>
    <td bordercolor="#CCCCCC" class="Estilo6"><div align="right" class="Estilo7"><strong>$&nbsp;<?php echo number_format($Ot[6],"2"); ?></strong></div></td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo6">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo6">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo6">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="25" colspan="2" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="Estilo6"><div align="center" class="Estilo7"><strong>A cuenta : </strong></div></td>
    <td bordercolor="#CCCCCC" class="Estilo6"><div align="right" class="Estilo7"><strong>$&nbsp;<?php echo number_format($Cja[0],"2");?></strong></div></td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="27" colspan="2" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="Estilo6"><div align="center" class="Estilo7"><strong>Resta : </strong></div></td>
    <td bordercolor="#CCCCCC" class="Estilo6"><div align="right" class="Estilo7"><strong>$&nbsp;<?php echo number_format($Ot[6]-$Cja[0],"2");?></strong></div></td>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" class="Estilo6">
    <td colspan="3" rowspan="11" bordercolor="#CCCCCC" class="Estilo7"><div align="center" class="Estilo7"></div>            <div align="center" class="Estilo7"></div>            <table width="290" height="135" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#FFFFFF">
      <tr>
        <td width="77" class="Estilo7"><div align="center"><strong>Ultrasonido</strong></div></td>
        <td width="43" class="Estilo7"><div align="center"></div></td>
        <td width="41" class="Estilo7"><div align="center"></div></td>
        <td width="111" class="Estilo7"><div align="center"><strong>Mamografia</strong></div></td>
      </tr>
      <tr>
        <td rowspan="3" class="Estilo7"><div align="left"><img src="Ultrasonido%202.jpg" width="76" height="150"></div></td>
        <td colspan="2" class="Estilo7"><div align="center">Estudios de alta tecnolog&iacute;a</div></td>
        <td rowspan="3" class="Estilo7"><div align="right"><img src="MAMOGRAFIA5-B.jpg" width="110" height="110"></div></td>
      </tr>
      <tr>
        <td class="Estilo7"><div align="center"></div></td>
        <td class="Estilo7"><div align="center"></div></td>
      </tr>
      <tr>
        <td height="32" colspan="2" class="Estilo7"><div align="center">
          <div align="center">Pregunte por nuestros precios </div>
        </div></td>
      </tr>
      <tr>
        <td class="Estilo7"><div align="center"><strong>Tomografia</strong></div></td>
        <td class="Estilo7"><div align="center"></div></td>
        <td class="Estilo7"><div align="center"></div></td>
        <td class="Estilo7"><div align="center"><strong>Perfil Tiroideo</strong></div></td>
      </tr>
    </table>    </td>
    <td colspan="3" rowspan="11" bordercolor="#CCCCCC" bgcolor="#FFFFFF" class="Estilo6"><div align="center" class="Estilo7">Fray Pedro de Gante No. 108 Col. Centro. </div>      <div align="center" class="Estilo7">Texcoco, Edo. de M&eacute;x. ( Frente a Catedral ) </div>      <div align="center" class="Estilo7">Tels: ( 01 595 ) 95 4 11 40 - 95 4 62 96 </div>   
      <span class="Estilo7"><br>   
      </span>      <div align="center" class="Estilo7"><strong>Horario:</strong></div>      <div align="center" class="Estilo7">Lunes a Viernes: 07:00 a 20:00 hrs. </div>      <div align="center" class="Estilo7">Sabados: 07: 00 a 16:00 hrs. </div>      <div align="center" class="Estilo7">Domingos: 08:00 a 14:00 hrs. </div>   
      <span class="Estilo6 Estilo11 Estilo12"><br>   
    </span>      <div align="center" class="Estilo7"><strong>Horarios de Entrega:</strong></div>      <div align="center" class="Estilo7">Lun. a Sab. a partir de 15:00 hrs. del dia prometido </div>      <div align="center" class="Estilo7">Dom. a partir de  13:00 hrs. del dia prometido.</div>      <div align="center" class="Estilo7">Y posterior a la fecha de entrega en horario normal</div></td>
    <td height="26" class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="26" class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <div align="center">
          <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
        </div>
      </div>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo6">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo6">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td height="23" class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo6"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td height="25" class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td class="Estilo7">&nbsp;</td>
    <td class="Estilo6"><span class="Estilo7"><?php echo $Otd[0];?></span></td>
    <td class="Estilo6"><div align="center" class="Estilo7">
        <?php if($Otd[3]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[3])." %";}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format ($Otd[2],"2");}?>
    </div></td>
    <td class="Estilo6"><div align="right" class="Estilo7">
        <?php if($Otd[2]=='0' or $Otd[0]==''){echo " ";} else{echo number_format($Otd[2]*(1-($Otd[3]/100)),"2");}
			 $Otd=mysql_fetch_array($OtdA);
	?>
    </div></td>
  </tr>
  <tr>
    <td height="25" class="Estilo7">&nbsp;</td>
    <td colspan="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="Estilo6"><div align="center" class="Estilo7"><strong>Importe Total : </strong></div></td>
    <td bordercolor="#CCCCCC" class="Estilo6"><div align="right" class="Estilo7"><strong>$&nbsp;<?php echo number_format($Ot[6],"2"); ?></strong></div></td>
  </tr>
  <tr>
    <td height="25" class="Estilo7">&nbsp;</td>
    <td colspan="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="Estilo6"><div align="center" class="Estilo7"><strong>A cuenta : </strong></div></td>
    <td bordercolor="#CCCCCC" class="Estilo6"><div align="right" class="Estilo7"><strong>$&nbsp;<?php echo number_format($Cja[0],"2");?></strong></div></td>
  </tr>
  <tr>
    <td class="Estilo7">&nbsp;</td>
    <td colspan="3" bordercolor="#CCCCCC" bgcolor="#CCCCCC" class="Estilo6"><div align="center" class="Estilo7"><strong>Resta : </strong></div></td>
    <td bordercolor="#CCCCCC" class="Estilo6"><div align="right" class="Estilo7"><strong>$&nbsp;<?php echo number_format($Ot[6]-$Cja[0],"2");?></strong></div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td height="21" class="Estilo7">&nbsp;</td>
    <td colspan="4" class="Estilo6">&nbsp;</td>
  </tr>
</table>

</body>
</html>

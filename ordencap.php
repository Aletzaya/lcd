<?
require("aut_verifica.inc.php");
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
$Usr=$HTTP_SESSION_VARS['usuario_login'];
$Venta_ot=$Vta;
session_register("Venta_ot");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de ordenes nuevas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
function cFocus(){
  document.form2.Estudio.focus();
}
function MostrarVentana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=500,height=400,left=350,top=150")
} 
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
<link href="lib/texto.css" rel="stylesheet" type="text/css">
<body onload="cFocus()">
<?php
include("lib/kaplib.php");
$link=conectarse();
$cCpo=mysql_query("select inst,lista,cliente,medico,receta,fechar,observaciones,diagmedico,servicio,fechae from otnvas where venta=$Vta and usr='$Usr'",$link);
$cCpot=mysql_fetch_array($cCpo);
$cIns=mysql_query("select institucion,nombre from inst order by institucion",$link);
$MedA=mysql_query("select nombre from med where medico='$cCpot[medico]'",$link);
$Med=mysql_fetch_array($MedA);
$CliA=mysql_query("select nombre from cli where cliente=$cCpot[cliente]",$link);
$Cli=mysql_fetch_array($CliA);
$date=date("Y-m-d");
?>
<div id="Layer1" style="position:absolute; left:9px; top:11px; width:812px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr> 
      <td width="12%" height="76"><div align="center"><a href="ordenes.php"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="1"></a></div></td>
      <td width="88%"> 
        <?php
        echo "<form name='form1' method='post' action='movordnva.php'>";
        echo "<font size='2' color='#FFFFFF'>Institucion:";
		echo "<select name='Institucion'>";
		while ($Ins=mysql_fetch_array($cIns)){
            echo "<option value=$Ins[0]> $Ins[0]&nbsp$Ins[1]</option>"; 
			if($Ins[0]==$cCpot[inst]){$DesIns=$Ins[1];}
        } 
		if($cCpot[servicio]==""){$DisSer="Ordinaria";}else{$DisSer=$cCpot[servicio];}
		echo "<option selected>$cCpot[inst]&nbsp;$DesIns</option>";
    	echo "</select></font>";
		echo "<input type='hidden' name='Accion' value='Institucion'>";
		echo "<input type='hidden' name='Vta' value=$Vta>";
		echo "<input type='submit' name='Submit' value='Enter'>";
		echo " <font color='#FFFFFF' size='2'>&nbsp;&nbsp;Servicio</font>";
        echo "<select name='Servicio'>";
        echo "<option value='Ordinaria'>Ordinaria</option>";
        echo "<option value='Urgente'>Urgente</option>";
        echo "<option value='Express'>Express</option>";
        echo "<option value='Hospitalizado'>Hospitalizado</option>";
        echo "<option value='Nocturno'>Nocturno</option>";
	    echo "<option selected value=$DisSer>$DisSer</option>";
        echo "</select>";
	    echo "<input type='submit' name='Submit' value='Ok'>";
		echo "</form>";
		?>
        <p align="left"><font color="#0033FF" size="2">&nbsp;&nbsp;Venta:</font> 
          <font color="#0033FF" size="2"><a href="ordenesnvas.php?Vta=1">1</a> 
          <a href="ordenesnvas.php?Vta=2">2</a> <a href="ordenesnvas.php?Vta=3">3</a>&nbsp;<a href="ordenesnvas.php?Vta=4">4</a>&nbsp;<a href="ordenesnvas.php?Vta=5">5</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;En 
          linea &nbsp;:</font><font size="2">&nbsp;[<font color="#FF00FF"> 
          <?php if($cCpot[lista]==""){echo "---------";}else{echo "VTA$Vta";}?>
          &nbsp;</font>]<font color="#0033FF">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lista 
          de precio:<?php echo $cCpot[1]; ?></font> </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color="#FFFFFF"> 
          <a href="movordnva.php?Vta=<?php echo $Vta;?>&Usr=<?php echo $Usr;?>&Avta=No"><font size="2">Borra/tdo</font></a></font><font size="2"></font></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          &nbsp;<font color="#FFFFFF" size="2">Fecha/Entrega: <?php echo $cCpot[fechae];?></font></p></td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:9px; top:102px; width:814px; height:252px; z-index:2"> 
  <table width="100%">
    <tr> 
      <td width="5%">&nbsp;</td>
      <td width="6%" bgcolor="#0066FF"><font color="#FFFFFF" size="2">Estudio</font></td>
      <td width="59%" bgcolor="#0066FF"><font color="#FFFFFF">Descripcion</font></td>
      <td width="11%" bgcolor="#0066FF"><font color="#FFFFFF">Precio</font></td>
      <td width="7%" bgcolor="#0066FF"><font color="#FFFFFF">Dto</font></td>
      <td width="12%" bgcolor="#0066FF"><font color="#FFFFFF">Importe</font></td>
    </tr>
    <?php
	    //echo "El valor de Venta es $Venta_ot";
		$cSqlIni="select estudio,descripcion,precio,descuento from otdnvas where usr='$Usr' and venta='$Vta' order by estudio";
	    $result=mysql_query($cSqlIni,$link);
		$Importe=0;
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($lB){
			   printf("<tr><td><a href='movordnva.php?cKey=$row[estudio]&Usr=$Usr&Mv=E&Vta=$Vta'>%s</a></td><td>%s</td><td>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right >%s</td>",'Elim',$row[estudio],$row[descripcion],number_format($row[precio],"2"),number_format($row[descuento],"2"),number_format($row[precio]*(1-($row[descuento]/100)),"2"));
			   $lB=false;
			}else{	
			   printf("<tr><td bgcolor =\"CCCCFF\"><a href='movordnva.php?cKey=$row[estudio]&Usr=$Usr&Mv=E&Vta=$Vta'>%s</a></td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td>",'Elim',$row[estudio],$row[descripcion],number_format($row[precio],"2"),number_format($row[descuento],"2"),number_format($row[precio]*(1-($row[descuento]/100)),"2"));
			   $lB=true;
			}  
			$Importe=$Importe+$row[precio];
			$nRen=$nRen+1;
		}
	    printf("<tr><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td align=center bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td>",'','','Total de estudios ----------::>&nbsp;&nbsp;&nbsp;'. $nRen ,'','$',number_format($Importe,"2"));
		mysql_free_result($result);
		mysql_close($link);
		?>
  </table>
</div>
<div id="Layer4" style="position:absolute; left:11px; top:357px; width:812px; height:205px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="147" border="0">
    <tr> 
      <td width="4%" height="143"> <div align="center"> 
          <p><a href="recepcion.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div></td>
        <td width="54%">
        <form name="form2" method="post" action="movordnva.php">
          <p align="left"> <font color="#0000CC" size="2"><strong>Estudio:</strong></font> 
            <input type="hidden" name="Vta" value='<?php echo $Vta; ?>'>
            <input type="hidden" name="Accion" value="Estudio">
            <input name="Estudio" type="text" size="5" >
            <a href="AgrEstOrd.php?Vta=<?php echo $Vta?>&Usr=<?php echo $Usr;?>">&nbsp;&nbsp;&nbsp;<img src="lib/lupa_o.gif" alt="Busca en el catalogo de estudios" width="22" height="21" border="0"></a>&nbsp;&nbsp; 
          </p>
		</form>
		<form name="form3" method="post" action="movordnva.php">
          <p align="left"> <font color="#0000CC" size="2">Paciente: &nbsp; 
            <input name="Cliente" type="text" value='<?php echo $cCpot[cliente];?>' size="5">
            <input type="hidden" name="Vta" value='<?php echo $Vta; ?>'>
            <input type="hidden" name="Accion" value="Cliente">
            &nbsp; <a href="clientes.php"><img src="lib/lupa_o.gif" alt="Busca en el catalogo de pacientes" width="22" height="21" border="0"></a> 
            &nbsp;&nbsp;&nbsp;<?php echo $Cli[nombre];?> </font> </p>
		</form>
		<form name="form4" method="post" action="movordnva.php">
          <p align="left"><font color="#0000CC" size="2">Medico.:&nbsp; 
            <input name="Medico" type="text" value='<?php echo $cCpot[medico];?>' size="5">
            <input type="hidden" name="Vta" value='<?php echo $Vta; ?>'>
            <input type="hidden" name="Accion" value="Medico">
            &nbsp; <a href="medicos.php"><img src="lib/lupa_o.gif" alt="Catalogo de medicos" width="22" height="21" border="0"></a> 
            &nbsp;&nbsp;&nbsp;<?php echo $Med[nombre];?> </font> </p>
		  </form>
 		  <form name="form5" method="post" action="movordnva.php">
          <p align="left"> <font color="#0033FF" size="2">No.receta 
            <input name="Receta" type="text" value='<?php echo $cCpot[receta];?>' size="7">
            &nbsp;&nbsp;&nbsp;Fec.Receta 
            <input name="Fechar" type="text" size="8" value='<?php echo $date;?>'>
            <input type="submit" name="Submit" value="ok">
            <input type="hidden" name="Vta" value='<?php echo $Vta; ?>'>
            <input type="hidden" name="Accion" value="Receta">
            </font> </p>
		  </form>	
          
        <p align="left"> <a href="movordnva.php?Accion=Orden"><img src="lib/guarda.gif" alt="Genera la Orden de trabajo" width="51" height="48" border="0"></a></p>
          </td>
      <td width="42%">
	  <form name="form6" method="post" action="movordnva.php">
	    <p><font color="#0000CC" size="2">Diagnostico Medico:<br>
            <TEXTAREA NAME="Diagmedico" cols="50" rows="4" ><?php echo $cCpot[diagmedico]; ?></TEXTAREA>
        </font></p>
        <p><font color="#0000CC" size="2">Observaciones	
            <TEXTAREA NAME="Observaciones" cols="50" rows="4" ><?php echo $cCpot[observaciones]; ?></TEXTAREA>
        </font></p>
        <input type="hidden" name="Accion" value="Observacion">		
        <input type="hidden" name="Vta" value='<?php echo $Vta; ?>'>
        <input type="submit" name="Submit3" value="Ok">
        </form></td>
    </tr>
  </table>
</div>
</body>
</html>

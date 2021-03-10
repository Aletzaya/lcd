<?php
  require("lib/kaplib.php");
  $link=conectarse();
  if($op=="gu"){  //Guarda el Movto de Notas
      $Campo=$Nota1;
  	   $Sec=1;	     
      $OtpreA=mysql_query("select cue.id from otpre,cue where otpre.orden='$busca' and cue.id=otpre.pregunta",$link); 
 	   while($registro=mysql_fetch_array($OtpreA)){ 
          $Nota="Nota".ltrim($Sec);
          $Resp=$$Nota;
          $lUp=mysql_query("update otpre SET nota='$Resp' where orden='$busca' and pregunta='$registro[0]' limit 1",$link);
      	 $Sec=$Sec+1;
      }
      $lUp=mysql_query("update ot SET status='DEPTO' where orden='$busca' limit 1",$link);
      header("Location: ordenespre.php?pagina=$pagina");      
  }      
  $Tabla="ot";
  $Titulo="Cuestionario Pre-analitico";
  $OtA=mysql_query("select * from ot where orden='$busca' ",$link);
  $Ot=mysql_fetch_array($OtA);
  $CliA=mysql_query("select nombrec from cli where cliente=$Ot[cliente]",$link);
  $Cli=mysql_fetch_array($CliA);
  $MedA=mysql_query("select nombrec from med where medico='$Ot[medico]'",$link);
  $Med=mysql_fetch_array($MedA);
  $InsA=mysql_query("select nombre from inst where institucion=$Ot[institucion]",$link);
  $Ins=mysql_fetch_array($InsA);
  $cSql="select * from $Tabla where (orden= '$busca')";
  $lAg=$Nombre<>$Cpo[nombre];
  $Fecha=date("Y-m-d");
?>
<html>
<head>
<title><?php echo $Titulo;?></title>
<script language="JavaScript1.2">dqm__codebase = "menu/script/"</script>
<script language="JavaScript1.2" src="menu/menu.js"></script>
<script language="JavaScript1.2" src="menu/script/tdqm_loader.js">
</script>
</head>
<body bgcolor="#FFFFFF">
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><a href="ordenes.php?pagina=<?echo $pagina;?>"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%"><div align="right"></div></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633ff">
    <td width="86%"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="imagenes/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
   </td>
    <td width="14%" height="23">
      <div align="right">
      <script language="JavaScript1.2">generate_mainitems()</script>
     </div></td>
  </tr>
</table>
<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}
function SiElimina(){
  if(confirm("ATENCION! Desea dar de Baja este registro?")){
     return(true);
   }else{
     document.form1.busca.value="NUEVO";
      return(false);
   }
}


function Completo(){
var lRt;
lRt=true;
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}
</script>
<hr noshade style="color:66CC66;height:1px">
<table width="973" height="300" border="0">
  <tr>
   <td><div align="center"><a href="ordenespre.php"><img src="imagenes/SmallExit.bmp" border="0"></a></div></td>
   <td>
   <form name="form1" method="get" action="ordenese.php">
     <font color='#660066' size='3' >
     <p align="center"><font color="#0066FF" size="3" ><strong>Orden de Trabajo Nùmero : <?php echo $busca;?></strong></font></p>
     <p><font color='##0066FF' size='3' >Fecha Inicio: </font>&nbsp;<?php echo $Ot[fecha];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <font color='##0066FF' size='3'>Hora :</FONT> &nbsp;<?php echo $Ot[hora];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <font color='##0066FF' size='3'>Fecha de entrega :</FONT>&nbsp; <?php echo $Ot[fechae] ?></p>
     <p><font color='##0066FF' size='3'>Institucion:</FONT>  <?php echo "$Ot[institucion] $Ins[nombre]";?>
     <font color='##0066FF' size='3'>Servicio...:</FONT>  <?php echo $Ot[servicio];?>&nbsp;&nbsp;&nbsp;&nbsp;</p>
     <p><font color='##0066FF' size='3' >Paciente...:</FONT>
     <input name="Cliente" type="text" size="6" value="<?php echo $Ot[cliente];?>">&nbsp;&nbsp;&nbsp;
     <?php echo $Cli[nombrec];?></p>
     <p><font color='##0066FF' size='3'>Medico.....:</FONT>
     <input name="Medico" type="text" size="6" value="<?php echo $Ot[medico];?>">&nbsp;&nbsp;&nbsp;
     <?php echo $Med[nombrec];?>
     <font color="#0066FF" size="2">No.Receta..:
     <input name="Receta" type="text" size="10" value=<?php echo $Ot[receta];?>>
     Fecha
     <input name="Fecharec" type="text" size="9" value=<?php echo $Ot[fecharec];?> >
     </font></p>
     <p><font color="#0066FF" size="2">Diagnostico Medico:</font></p>
     <TEXTAREA NAME="Diagmedico" cols="60" rows="" ><?php echo $Ot[diagmedico];?></TEXTAREA>
     </p>
     <p><font color="#0066FF" size="2">Observaciones</font></p>
     <p><font color="#0066FF" size="2">
     <TEXTAREA NAME="Observaciones" cols="60" rows="3" ><?php echo $Ot[observaciones]; ?></TEXTAREA>
     </font></p>
     <input type="hidden" name="orden" value=<?php echo $orden; ?>>
     <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
     <input type="hidden" name="busca" value=<?php echo $busca; ?>>
  </form>
 <hr noshade style="color:FF0000;height:3px">
 <P align='center'><font color='#0066FF' size='4'><strong>CUESTIONARIO PRE-ANALITICO</strong></font></P>
  <?php
     $OtpreA=mysql_query("select cue.pregunta,otpre.nota,cue.id from otpre,cue where otpre.orden='$busca' and cue.id=otpre.pregunta",$link);
     echo "<form name='form2' method='get' action='ordenespree.php?op=gu'>";     
	     $Sec=1;	     
 	     while($registro=mysql_fetch_array($OtpreA)){ 
           $Campo="Nota".ltrim($Sec);
   	     echo "<p>"; 
   	     echo "$registro[0]<br>";
           echo "<TEXTAREA NAME='$Campo' cols='55' rows='3' >$registro[1]</TEXTAREA>";
           echo "</p>";
      	  $Sec=$Sec+1;
        }
        echo "<p>";
        echo "<input type='IMAGE' name='Guarda' src='imagenes/Guarda.gif' alt='Guarda los ultimos movimientos y salte' >&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "<input type='Reset' value='Recupera'>";
        echo "<input type='hidden' name='busca' value=$busca>";
        echo "<input type='hidden' name='op' value=gu>";
        echo "</p>";
     echo "</form>";   
  ?>
  </td>
  </tr>
  <tr>
    <td width="136" height="59">&nbsp;</td>
    <td width="768"><p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="55">&nbsp;</td>
  </tr>
</table>
<hr noshade style="color:FF0000;height:3px">
<td width="416" valign="top">
</td>
</body>
</html>
<?
mysql_close();
?>
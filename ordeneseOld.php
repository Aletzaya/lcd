<html>
<head>
<title>Laboratorio Duran</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function ValGeneral(){
var lRt;
lRt=true;
if(document.form1.Cliente.value==""){lRt=false;}
if(document.form1.Medico.value==""){lRt=false;}
if(!lRt){
	alert("Faltan datos por llenar, favor de verificar");
    return false;
}
    document.form2.Cliente.value=document.form1.Cliente.value
    document.form2.Medico.value=document.form1.Medico.value
    return true;
}

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);


function ValDato(cCampo){
if (cCampo=='Apellidop'){document.form2.Apellidop.value=document.form2.Apellidop.value.toUpperCase();
}if (cCampo=='Apellidom'){document.form2.Apellidom.value=document.form2.Apellidom.value.toUpperCase();
}if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();
}if (cCampo=='Subespecialidad'){document.form1.Subespecialidad.value=document.form1.Subespecialidad.value.toUpperCase();
}if (cCampo=='Telconsultorio'){document.form1.Telconsultorio.value=document.form1.Telconsultorio.value.toUpperCase();}
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<link href="lib/texto.css" rel="stylesheet" type="text/css">
<body class="texto" bgcolor="#FFFFFF" text="#000000">
<?php
include("lib/kaplib.php");
$link=conectarse();
$OtA=mysql_query("select * from ot where orden=$cKey ",$link);
$Ot=mysql_fetch_array($OtA);
$CliA=mysql_query("select nombrec from cli where cliente=$Ot[cliente]",$link);
$Cli=mysql_fetch_array($CliA);
$MedA=mysql_query("select nombrec from med where medico='$Ot[medico]'",$link);
$Med=mysql_fetch_array($MedA);
$InsA=mysql_query("select nombre from inst where institucion=$Ot[institucion]",$link);
$Ins=mysql_fetch_array($InsA);
?>
<table width="887" border="0" cellpadding="0" cellspacing="0" mm:layoutgroup="true" height="667">
  <tr> 
    <td width="1162" height="667" valign="top"> 
      <table width="100%" border="0" height="88" background="lib/fondo1.jpg">
        <tr>
          <td width="13%" height="72"> 
		    <?php
			   echo "<div align='center'><a href='ordenes.php?busca=$cKey'><img src='lib/logo2.jpg' width='100' height='80' border='0' border='1'></a></div>";  
			?>	
          </td>
          <td width="53%" height="72"> 
            <div align="center"><font color="#FFFFFF" size="2"><strong>Edicion de ordenes de trabajo</strong></font></div>
          </td>
          <td width="34%" height="72">&nbsp;</td>
        </tr>
      </table>
      <table width="100%" border="0" height="579">
        <tr>
          <td width="13%" height="575"> 
            <div align="center">
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p><a href="ordenes.php"><img src="lib/SmallExit.BMP" alt="Regresar" border="0"></a></p>
            </div>
          </td>
	        <td width="87%" height="575"> 
   		    <form name="form1" method="get" action="ordenese.php" >
              <p><font color="#009900" ><strong>Orden de Trabajo Nùmero :<?php echo $cKey;?></strong></font><font color="#0066FF" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></p>
              <p><font color="#0066FF" size="2">Fecha: 
                <?php echo $Ot[fecha];?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hora: 
                <?php echo $Ot[hora];?>
                </font></p>
              <p><font color="#0066FF" size="2">Importe....: $ 
                <?php echo $Ot[importe]; ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha 
                Entrega : <?php echo $Ot[fechae] ?>
                </font></p>
            <p><font color="#0066FF" size="2">Institucion: <?php echo "$Ot[institucion] $Ins[nombre]";?></p>
			<p>Servicio...: <?php echo $Ot[servicio];?></p>
			</font></p>
              <p><font color="#0066FF" size="2">Paciente...: 
                <input name="Cliente" type="text" size="6" value="<?php echo $Ot[cliente];?>">
                &nbsp;&nbsp;&nbsp;<?php echo $Cli[nombrec];?></font> 
			   </p>
               <p><font color="#0066FF" size="2">Medico.....:
                <input name="Medico" type="text" size="6" value="<?php echo $Ot[medico];?>">
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Med[nombrec];?> 
                </font></p>
			   </form>
  		       <form name="form2" method="get" action="movot.php" onSubmit="return ValGeneral();" >
              <p><font color="#0066FF" size="2">No.Receta..: 
                <input name="Receta" type="text" size="10" value=<?php echo $Ot[receta];?>>
                Fecha 
                <input name="Fecharec" type="text" size="9" value=<?php echo $Ot[fecharec];?> >
                </font></p>
               <p><font color="#0066FF" size="2">Diagnostico Medico:</font></p>
			   <p></p>
			   <TEXTAREA NAME="Diagmedico" cols="60" rows="5" ><?php echo $Ot[diagmedico];?></TEXTAREA>
			  </p>
              <p><font color="#0066FF" size="2">Observaciones</font></p>
              <p><font color="#0066FF" size="2"> 
              <TEXTAREA NAME="Observaciones" cols="60" rows="5" ><?php echo $Ot[observaciones]; ?></TEXTAREA>
              </font></p> 
              <p>&nbsp; </p>
			  </form>
		  </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
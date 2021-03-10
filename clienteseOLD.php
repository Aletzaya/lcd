<?
require("aut_verifica.inc.php");
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
?>
<html>
<head>
<title>Laboratorio Duran</title>
<script language="JavaScript" type="text/JavaScript">
<!--
function ValGeneral(){
var lRt;
lRt=true;
if(document.form2.Apellidom.value==""){lRt=false;}
if(document.form2.Apellidop.value==""){lRt=false;}
if(document.form2.Nombre.value==""){lRt=false;}
if(!lRt){
	alert("Faltan datos por llenar, favor de verificar");
    return false;
}
    document.form1.Apellidom.value=document.form2.Apellidom.value
    document.form1.Apellidop.value=document.form2.Apellidop.value
    document.form1.Nombre.value=document.form2.Nombre.value
    return true;
}

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function EliReg(){
if(confirm("ATENCION!\r Desea dar de Baja este registro?")){
   return(true);
}else{
   document.form1.cKey.value='NUEVO';
   return(false);
}
}

function ValDato(cCampo){
if (cCampo=='Apellidop'){document.form2.Apellidop.value=document.form2.Apellidop.value.toUpperCase();
}if (cCampo=='Apellidom'){document.form2.Apellidom.value=document.form2.Apellidom.value.toUpperCase();
}if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();
}if (cCampo=='Direccion'){document.form1.Direccion.value=document.form1.Direccion.value.toUpperCase();
}if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();
}if (cCampo=='Municipio'){document.form1.Municipio.value=document.form1.Municipio.value.toUpperCase();
}if (cCampo=='Telefono'){document.form1.Telefono.value=document.form1.Telefono.value.toUpperCase();
}if (cCampo=='Titular'){document.form1.Titular.value=document.form1.Titular.value.toUpperCase();
}if (cCampo=='Credencial'){document.form1.Credencial.value=document.form1.Credencial.value.toUpperCase();
}if (cCampo=='Codigo'){document.form1.Codigo.value=document.form1.Codigo.value.toUpperCase();
}if (cCampo=='Especialidad'){document.form1.Especialidad.value=document.form1.Especialidad.value.toUpperCase();
}if (cCampo=='Subespecialidad'){document.form1.Subespecialidad.value=document.form1.Subespecialidad.value.toUpperCase();
}if (cCampo=='Telconsultorio'){document.form1.Telconsultorio.value=document.form1.Telconsultorio.value.toUpperCase();}
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<link href="lib/texto.css" rel="stylesheet" type="text/css">
<body class="texto" bgcolor="#FFFFFF" text="#000000">
<table width="887" border="0" cellpadding="0" cellspacing="0" mm:layoutgroup="true" height="667">
  <tr>
    <td width="1162" height="667" valign="top">
      <table width="100%" border="0" height="88" background="lib/fondo1.jpg">
        <tr>
          <td width="13%" height="72">
		   <div align='center'><a href='clientes.php'><img src='lib/logo2.jpg' width='100' height='80' border='1'></a></div>
          </td>
          <td width="53%" height="72">
            <div align="center"><img src="lib/pacientes.jpg" width="150" height="25"></div>
          </td>
          <td width="34%" height="72">&nbsp;</td>
        </tr>
      </table>
      <table width="100%" border="0" height="482">
        <tr>
          <td width="7%" height="478">
            <div align="center">
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p>&nbsp;</p>
              <p><a href="clientes.php"><img src="lib/SmallExit.BMP" alt="Regresar" border="0"></a></p>
            </div>
          </td>
		    <?php
   		    //echo "Session var es " . $HTTP_SESSION_VARS['Faz'];
	        include("lib/kaplib.php");
	        $link=conectarse();
	        $tabla="cli";
			$lAg=true;
			$cZna=mysql_query("select zona,descripcion from zns order by zona",$link);
  		    $cIns=mysql_query("select institucion,alias from inst order by institucion",$link);
			if($Apellidom<>"" and $Apellidop<>"" and $Nombre<>""){
		       $cReg=mysql_query("select apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,credencial,codigo,fechan,sexo,rfc,titular,mail,padecimiento,refubicacion,nombrec,alta,afiliacion,observaciones,zona,institucion,expiracion,expira,cliente,fecha,cliente from $tabla where (nombre='$Nombre' and apellidop='$Apellidop' and apellidom='$Apellidom')",$link);
			}else{
    	       $cReg=mysql_query("select apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,credencial,codigo,fechan,sexo,rfc,titular,mail,padecimiento,refubicacion,nombrec,alta,afiliacion,observaciones,zona,institucion,expiracion,expira,cliente,fecha,cliente from $tabla where (cliente='$cKey')",$link);
			}
	        if($cCpo=mysql_fetch_array($cReg)){
			   $Apellidop=$cCpo[apellidop];
			   $Apellidom=$cCpo[apellidom];
			   $Nombre=$cCpo[nombre];
			   $cKey=$cCpo[cliente];
			   $lAg=false;
			}
	        $Fecha=date("Y-m-d");
			?>
            <td width="93%" height="478">
			  <form name='form2' method='get' action='movcli.php'>
			    <font color='#0000FF' size='2'>Cliente: <?php if($lAg){echo $cKey;}else{echo $cCpo[cliente];} ?></font>
                <p><font size='2' color='#0000FF'>Apellido pat.:
                <input name='Apellidop' type='text' size='15' value = '<?php echo $Apellidop;?>' onBlur=ValDato('Apellidop')>
                Apellido mat.:
                <input name='Apellidom' type='text' size='15' value = '<?php echo $Apellidom;?>' onBlur=ValDato('Apellidom')>
                Nombre:
                <input name='Nombre' type='text' size='10' value = '<?php echo $Nombre;?>' onBlur=ValDato('Nombre')>
		        <input type='hidden' name='cKey' value='<?php echo $cKey;?>'>
		        <input type='submit' name='Submit' value='busca'>
                </font></p>
			  </form>
     		  <form name="form1" method="get" action="movcli.php" onSubmit="return ValGeneral();" >
			  <p><font color="#0000FF" size="2">Direccion
                <input type="text" name="Direccion"  value ='<?php echo $cCpo[direccion]; ?>' onBlur="ValDato('Direccion')">
                Localidad
                <input type="text" name="Localidad" value ='<?php echo $cCpo[localidad]; ?>' onBlur="ValDato('Localidad')">
                Mpio:
                <input type="text" name="Municipio" value ='<?php if(!$lAg){echo $cCpo[municipio];} ?>' onBlur="ValDato('Municipio')">
                </font></p>
              <p><font color="#0000FF" size="2">Telefono :
                <input type="text" name="Telefono" size="10" value ='<?php echo $cCpo[telefono]; ?>' onBlur="ValDato('Telefono')" >
                Credencial
                <input type="text" name="Credencial" value ='<?php echo $cCpo[credencial]; ?>' onBlur="ValDato('Credencial')">
                Cod.Postal
                <input type="text" name="Codigo" size="5" value ='<?php echo $cCpo[codigo]; ?>' onBlur="ValDato('Codigo')">
                </font></p>
              <p><font color="#0000FF" size="2">F.Alta :
                <input type="text" name="Fecha" size="9" value ='<?php if(!$lAg){echo $cCpo[fecha];}else{echo $Fecha;} ?>' onBlur="ValDato('Fecha')">
                F.Nacimiento :
                <input type="text" name="Fechan" size="10" value ='<?php if(!$lAg){echo $cCpo[fechan];}else{echo $Fecha;}?>' >
                No.A&ntilde;os:
                <input type="text" size="2" name="Anos" value ='<?php echo $Fecha-$cCpo[fechan]; ?>' >
                Sexo
                <select name='Sexo'>
                  <option value="M">M</option>
                  <option value="F">F</option>
                  <option selected ><?php echo $cCpo[sexo]?></option>
                </select>
                </font></p>
              <p><font color="#0000FF" size="2">Titular
                <input type="text" name="Titular" value ='<?php if(!$lAg){echo $cCpo[titular];} ?>'onBlur="ValDato('Titular')">
                Mail
                <input type="text" name="Mail" value ='<?php if(!$lAg){echo $cCpo[mail];} ?>'>
                </font></p>
              <p><font color="#0000FF" size="2">Afiliacion:
                <input name="Afiliacion" type="text" value="<?php if(!$lAg){echo $cCpo[afiliacion];}?>">
                Expira:
                <input name="Expira" type="text" value="<?php if(!$lAg){echo $cCpo[expira];}?>" size="2">
                Fecha Expiracion:
                <input name="Expiracion" type="text"  value="<?php if(!$lAg){echo $cCpo[expiracion];}?>"size="8">
                </font></p>
              <p><font color="#0000FF" size="2">Zona:
			     <?php
			     echo "<select name='Zona'>";
		         while ($Zna=mysql_fetch_array($cZna)){
                      echo "<option value=$Zna[0]> $Zna[0]&nbsp$Zna[1]</option>";
					  if($Zna[0]==$cCpo[zona]){$DesZna=$Zna[1];}
                 }
		         echo "<option selected>$cCpo[zona]&nbsp;$DesZna</option>";
    	         echo "</select>";
				 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Institucion:";
			     echo "<select name='Institucion'>";
		         while ($Ins=mysql_fetch_array($cIns)){
                      echo "<option value=$Ins[0]> $Ins[0]&nbsp$Ins[1]</option>";
					  if($Ins[0]==$cCpo[institucion]){$DesIns=$Ins[1];}
                 }
		         echo "<option selected>$cCpo[institucion]&nbsp;$DesIns</option>";
    	         echo "</select>";
				 ?>
			  </font></p>
              <p><font color="#0000FF" size="2">Padecimientos:</font></p>
              <p><font color="#0000FF" size="2">
                <TEXTAREA NAME="Padecimiento" cols="70" rows="6" ><?php echo "$cCpo[padecimiento]"; ?></TEXTAREA>
                </font></p>
              <p><font color="#0000FF" size="2">Observaciones:</font></p>
              <p><font color="#0000FF" size="2">
                <TEXTAREA NAME="Observaciones" cols="70" rows="6" ><?php echo "$cCpo[observaciones]"; ?></TEXTAREA>
                </font> </p>
              <p><font size="2" color="#0000FF">Ref.Ubicacion</font></p>
              <font size="2">
              <textarea name="Refubicacion" cols="70" rows="6" ><?php echo "$cCpo[refubicacion]"; ?></textarea>
              </font> </p>
              <p>
              <input type="hidden" name="Apellidop">
			  <input type="hidden" name="Apellidom">
  		      <input type="hidden" name="Nombre">
              <input type="hidden" name="busca" value=<?php echo $busca; ?>>
              <input type="hidden" name="cKey" value=<?php echo $cKey; ?>>
              <input type="IMAGE" name="Guarda" src="lib/guardar.jpg" alt="Guarda los ultimos movimientos y salte" width="150" height="25" >
              &nbsp;&nbsp;
              <input type="IMAGE" name="Elimina" src="lib/eliminar.jpg" alt="Elimina este registro" onClick="EliReg()" width="150" height="25">
              </p>
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
<?php
  $Tabla="cli";
  $Titulo="Detalle de clientes ($busca)";
  require("lib/kaplib.php");
  $link=conectarse();
  if($Guarda_x > 0){		//Para agregar uno nuevo
      $Sp=" ";
      $Nombrec=trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
      $Fecha=date("Y-m-d");
      $Mes=substr($Fechan,5,2);
      $Dia=substr($Fechan,8,2);
      $Ano=substr($Fechan,0,4);
      if(!checkdate($Mes,$Dia,$Ano)){
         $Fecha=date("Y-m-d");
         $Fechan = substr($Fecha,0,4) - $Anos ."-".substr($Fecha,5,2)."-".substr($Fecha,8,2);
      }
      if($busca=='NUEVO'){
        $lUp=mysql_query("insert into $Tabla (apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,credencial,codigo,sexo,titular,mail,padecimiento,alta,fechan,nombrec,afiliacion,observaciones,zona,institucion,expiracion,expira,refubicacion,fecha) VALUES ('$Apellidop','$Apellidom','$Nombre','$Direccion','$Localidad','$Municipio','$Telefono','$Credencial','$Codigo','$Sexo','$Titular','$Mail','$Padecimiento','$Fecha','$Fechan','$Nombrec','$Afiliacion','$Observaciones','$Zona','$Institucion','$Expiracion','$Expira','$Refubicacion','$Fecha')",$link);
 	  }else{
         $lUp=mysql_query("update $Tabla SET apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',direccion='$Direccion',localidad='$Localidad',municipio='$Municipio',telefono='$Telefono',credencial='$Credencial',codigo='$Codigo',sexo='$Sexo',titular='$Titular',mail='$Mail',padecimiento='$Padecimiento',alta='$Alta',fechan='$Fechan',nombrec='$Prueba',afiliacion='$Afiliacion',observaciones='$Observaciones',zona='$Zona',institucion='$Institucion',refubicacion='$Refubicacion',expiracion='$Expiracion',expira='$Expira',fecha='$Fecha' where cliente='$cKey' limit 1",$link);
 	  }
      header("Location: clientes.php?pagina=$pagina");
  }elseif($Elimina_x>0){    // Para dar de baja
      $lUp=mysql_query("delete from $Tabla where cliente='$busca'",$link);
      header("Location: clientes.php?pagina=$pagina");
  }elseif($Lupa_x > 0){        //Para agregar uno nuevo
      $cSql="select * from $Tabla where (nombre='$Nombre' and apellidop='$Apellidop' and apellidom='$Apellidom')";
  }else{
      $cSql="select * from $Tabla where cliente='$busca'";
  }
  $cZna=mysql_query("select zona,descripcion from zns order by zona",$link);
  $cIns=mysql_query("select institucion,alias from inst order by institucion",$link);
  $CpoA=mysql_query($cSql,$link);
  if($Cpo=mysql_fetch_array($CpoA)){
     $Apellidop=$Cpo[apellidop];
     $Apellidom=$Cpo[apellidom];
     $Nombre=$Cpo[nombre];
 }
 $lAg=$Apellidop<>$Cpo[apellidop];
 $Fecha=date("Y-m-d");
?>
<html>
<head>
<title><?php echo $Titulo;?></title>
<meta http-equiv="Pragma" content="no-cache" />
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
</head>
<body bgcolor="#FFFFFF" onload="cFocus()">
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><a href="clientes.php?pagina=<?echo $pagina;?>"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></td>
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
    <td width="14%" height="24">
<div align="right">
 <script type="text/javascript" language="JavaScript1.2">
<!--
stm_bm(["menu4bb6",400,"","blank.gif",0,"","",0,0,19,19,144,1,0,0,""],this);
stm_bp("p0",[0,4,0,0,0,3,0,7,100,"",-2,"",-2,90,0,0,"#006699","#ffffff","",3,0,0,"#ffffff"]);
stm_ai("p0i0",[0,"Recepcion","","",-1,-1,0,"","_self","","","","",0,0,0,"arrow_r.gif","arrow_r.gif",7,7,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","bold 8pt Arial","bold 8pt Arial",0,0]);
stm_bp("p1",[1,4,0,0,0,3,0,0,100,"progid:DXImageTransform.Microsoft.Checkerboard(squaresX=12,squaresY=12,direction=down,enabled=0,Duration=0.25)",11,"",-2,85,0,0,"#7f7f7f","transparent","",3,0,0,"#000000"]);
stm_aix("p1i0","p0i0",[0,"Ordenes de trabajo","","",-1,-1,0,"","_self","","","","",0,0,0,"","",0,0,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","8pt Arial","8pt Arial"]);
stm_aix("p1i1","p1i0",[0,"Ingresos","","",-1,-1,0,"ingresos.php"]);
stm_aix("p1i2","p1i0",[0,"Corte de caja","","",-1,-1,0,"corte.php"]);
stm_ep();
stm_aix("p0i1","p0i0",[0,"Catalogos"]);
stm_bpx("p2","p1",[]);
stm_aix("p2i0","p1i0",[0,"Pacientes","","",-1,-1,0,"clientes.php"]);
stm_aix("p2i1","p1i0",[0,"Medico","","",-1,-1,0,"medicos.php"]);
stm_aix("p2i2","p1i0",[0,"Estudios","","",-1,-1,0,"estudios.php"]);
stm_aix("p2i3","p1i0",[0,"Zonas","","",-1,-1,0,"zonas.php"]);
stm_aix("p2i4","p1i0",[0,"Instituciones","","",-1,-1,0,"institu.php"]);
stm_aix("p2i5","p1i0",[0,"Lista de precios","","",-1,-1,0,"lista.php"]);
stm_aix("p2i6","p1i0",[0,"Estudios por institucion"]);
stm_aix("p2i7","p1i0",[0,"Departamentos","","",-1,-1,0,"depto.php"]);
stm_aix("p2i8","p1i0",[0,"Cuestionario Pre-analitico","","",-1,-1,0,"preguntas.php"]);
stm_aix("p2i9","p1i0",[0,"Cuetionario por Est.","","",-1,-1,0,"cuepre.php"]);
stm_ep();
stm_aix("p0i2","p0i0",[0,"Pre-analiticos"]);
stm_bpx("p3","p1",[]);
stm_aix("p3i0","p1i0",[0,"Pre-analiticos"]);
stm_ep();
stm_aix("p0i3","p0i0",[0,"Captura de resultados"]);
stm_bpx("p4","p1",[]);
stm_aix("p4i0","p1i0",[0,"Resultados","","",-1,-1,0,"resultados.php"]);
stm_ep();
stm_aix("p0i4","p0i0",[0,"Reportes","","",-1,-1,0,"Reportes.php"]);
stm_bpx("p5","p1",[]);
stm_aix("p5i0","p1i0",[0,"Baja reportes"]);
stm_ep();
stm_ep();
stm_em();
//-->
</script>
      </div></td>
  </tr>
</table>
<script language="JavaScript1.2">
function cFocus(){
  document.form2.Apellidop.focus();
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

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Apellidop'){document.form2.Apellidop.value=document.form2.Apellidop.value.toUpperCase();
}if (cCampo=='Apellidom'){document.form2.Apellidom.value=document.form2.Apellidom.value.toUpperCase();
}if (cCampo=='Nombre'){document.form1.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Direccion'){document.form1.Direccion.value=document.form1.Direccion.value.toUpperCase();
}if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();
}if (cCampo=='Telefono'){document.form1.Telefono.value=document.form1.Telefono.value.toUpperCase();
}if (cCampo=='Municipio'){document.form1.Municipio.value=document.form1.Municipio.value.toUpperCase();
}if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
}
</script>
<hr noshade style="color:3366FF;height:2px">
<table width="973" height="325" border="0">
  <tr><td></td>
  <td>
   <form name='form2' method='get' action='clientese.php'>
     <font color='#000099' size='4'>Cliente: <?php if($lAg){echo $busca;}else{echo $Cpo[cliente];} ?>&nbsp;&nbsp;&nbsp;Nombre completo: <? echo $Cpo[nombrec];?> </font>
     <p><font size='2' color='#0000FF'>Apellido pat.:
     <input name='Apellidop' type='text' size='15' value = '<?php echo $Apellidop;?>' onBlur=Mayusculas('Apellidop')>
     Apellido mat.:
     <input name='Apellidom' type='text' size='15' value = '<?php echo $Apellidom;?>' onBlur=Mayusculas('Apellidom')>
     Nombre:
     <input name='Nombre' type='text' size='10' value = '<?php echo $Nombre;?>' onBlur=Mayusculas('Nombre')>
     <input type="IMAGE" name="Lupa" src="imagenes/lupa.gif" alt="Busca un paciente con estos datos" >&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="hidden" name="orden" value=<?php echo $orden; ?>>
     <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
     <input type="hidden" name="busca" value=<?php echo $busca; ?>>
     </font></p>
  </form>
  <form name="form1" method="get" action="clientese.php" onSubmit="return Completo();" >
			  <p><font color="#0000FF" size="2">Direccion
                <input type="text" name="Direccion"  value ='<?php echo $Cpo[direccion]; ?>' onBlur="Mayusculas('Direccion')">
                Localidad
                <input type="text" name="Localidad" value ='<?php echo $Cpo[localidad]; ?>' onBlur="Mayusculas('Localidad')">
                Mpio:
                <input type="text" name="Municipio" value ='<?php if(!$lAg){echo $Cpo[municipio];} ?>' onBlur="Mayusculas('Municipio')">
                </font></p>
              <p><font color="#0000FF" size="2">Telefono :
                <input type="text" name="Telefono" size="10" value ='<?php echo $Cpo[telefono]; ?>' onBlur="Mayusculas('Telefono')" >
                Credencial
                <input type="text" name="Credencial" value ='<?php echo $Cpo[credencial]; ?>' onBlur="Mayusculas('Credencial')">
                Cod.Postal
                <input type="text" name="Codigo" size="5" value ='<?php echo $Cpo[codigo]; ?>' onBlur="Mayusculas('Codigo')">
                </font></p>
              <p><font color="#0000FF" size="2">F.Alta :
                <input type="text" name="Fecha" size="9" value ='<?php if(!$lAg){echo $Cpo[fecha];}else{echo $Fecha;} ?>' onBlur="Mayusculas('Fecha')">
                F.Nacimiento :
                <input type="text" name="Fechan" size="10" value ='<?php if(!$lAg){echo $Cpo[fechan];}else{echo $Fecha;}?>' >
                No.A&ntilde;os:
                <input type="text" size="2" name="Anos" value ='<?php echo $Fecha-$Cpo[fechan]; ?>' >
                Sexo
                <select name='Sexo'>
                  <option value="M">M</option>
                  <option value="F">F</option>
                  <option selected ><?php echo $Cpo[sexo]?></option>
                </select>
                </font></p>
              <p><font color="#0000FF" size="2">Titular
                <input type="text" name="Titular" value ='<?php if(!$lAg){echo $Cpo[titular];} ?>'onBlur="Mayusculas('Titular')">
                Mail
                <input type="text" name="Mail" value ='<?php if(!$lAg){echo $Cpo[mail];} ?>'>
                </font></p>
              <p><font color="#0000FF" size="2">Afiliacion:
                <input name="Afiliacion" type="text" value="<?php if(!$lAg){echo $Cpo[afiliacion];}?>">
                Expira:
                <input name="Expira" type="text" value="<?php if(!$lAg){echo $Cpo[expira];}?>" size="2">
                Fecha Expiracion:
                <input name="Expiracion" type="text"  value="<?php if(!$lAg){echo $Cpo[expiracion];}?>"size="8">
                </font></p>
              <p><font color="#0000FF" size="2">Zona:
			     <?php
			     echo "<select name='Zona'>";
		         while ($Zna=mysql_fetch_array($cZna)){
                      echo "<option value=$Zna[0]> $Zna[0]&nbsp$Zna[1]</option>";
					  if($Zna[0]==$Cpo[zona]){$DesZna=$Zna[1];}
                 }
		         echo "<option selected>$Cpo[zona]&nbsp;$DesZna</option>";
    	         echo "</select>";
				 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Institucion:";
			     echo "<select name='Institucion'>";
		         while ($Ins=mysql_fetch_array($cIns)){
                      echo "<option value=$Ins[0]> $Ins[0]&nbsp$Ins[1]</option>";
					  if($Ins[0]==$Cpo[institucion]){$DesIns=$Ins[1];}
                 }
		         echo "<option selected>$Cpo[institucion]&nbsp;$DesIns</option>";
    	         echo "</select>";
				 ?>
			  </font></p>
              <p><font color="#0000FF" size="2">Padecimientos:</font></p>
              <p><font color="#0000FF" size="2">
                <TEXTAREA NAME="Padecimiento" cols="70" rows="6" ><?php echo "$Cpo[padecimiento]"; ?></TEXTAREA>
                </font></p>
              <p><font color="#0000FF" size="2">Observaciones:</font></p>
              <p><font color="#0000FF" size="2">
                <TEXTAREA NAME="Observaciones" cols="70" rows="6" ><?php echo "$Cpo[observaciones]"; ?></TEXTAREA>
                </font> </p>
              <p><font size="2" color="#0000FF">Ref.Ubicacion</font></p>
              <font size="2">
              <textarea name="Refubicacion" cols="70" rows="6" ><?php echo "$Cpo[refubicacion]"; ?></textarea>
              </font> </p>
              <p>
              <input type="hidden" name="Apellidop">
			  <input type="hidden" name="Apellidom">
  		      <input type="hidden" name="Nombre">
              <input type="IMAGE" name="Guarda" src="imagenes/guarda.gif" alt="Guarda los ultimos movimientos y salte" >&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="IMAGE" name="Elimina" src="imagenes/elimina.gif" alt="Elimina este registro" onClick="SiElimina()">&nbsp;&nbsp;&nbsp;&nbsp;
              <input type='Reset' value='Recupera'>
              <input type="hidden" name="orden" value=<?php echo $orden; ?>>
              <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
              <input type="hidden" name="busca" value=<?php echo $busca; ?>>
              </p>
              <p>&nbsp; </p>
	</form>
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
<?php
  $Tabla="inst";
  $Titulo="Detalle de Institucion ($busca)";
  require("lib/kaplib.php");
  $link=conectarse();
  if($Guarda_x > 0){		//Para agregar uno nuevo
      if($busca=='NUEVO'){
        $lUp=mysql_query("insert into $Tabla (nombre,alias,direccion,localidad,municipio,referencia,rfc,director,subdirector,condiciones,envio,otro,status,codigo,telefono,fax,observaciones,servicio,administrativa,suplente) VALUES ('$Nombre','$Alias','$Direccion','$Localidad','$Municipio','$Referencia','$Rfc','$Director','$Subdirector','$Condiciones','$Envio','$Otro','$Status','$Codigo','$Telefono','$Fax','$Observaciones','$Servicio','$Administrativa','$Suplente')",$link);
 	  }else{
        //$lUp=mysql_query("update $Tabla SET nombre='$Nombre',alias='$Alias',direccion='$Direccion',localidad='$Localidad',municipio='$Municipio',referencia='$Referencia',rfc='$Rfc',director='$Director',subdirector='$Subdirector',condiciones='$Condiciones',envio='$Envio',otro='$Otro',status='$Status',codigo,'$Codigo',telefono='$Telefono',fax='$Fax',observaciones='$Observaciones',administrativa='$Administrativa',servicio='$Servicio' where institucion='$busca' limit 1",$link);
       $lUp=mysql_query("update $Tabla SET nombre='$Nombre',alias='$Alias',direccion='$Direccion',localidad='$Localidad',municipio='$Municipio',referencia='$Referencia',rfc='$Rfc',director='$Director',subdirector='$Subdirector',condiciones='$Condiciones',envio='$Envio',otro='$Otro',status='$Status',codigo='$Codigo',telefono='$Telefono',fax='$Fax',observaciones='$Observaciones',administrativa='$Administrativa',servicio='$Servicio',suplente='$Suplente' where institucion='$busca' limit 1",$link);
 	  }
      header("Location: institu.php?pagina=$pagina");
  }elseif($Elimina_x>0){    // Para dar de baja
      $lUp=mysql_query("delete from $Tabla where institucion='$busca'",$link);
      header("Location: institu.php?pagina=$pagina");
  }else{
      $cSql="select * from $Tabla where institucion='$busca'";
  }
  //echo "$cSql";
  $CpoA=mysql_query($cSql,$link);
  $Cpo=mysql_fetch_array($CpoA);
  $lAg=$busca<>$Cpo[institucion];
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
    <td width="14%" height="59"><a href="institu.php?pagina=<?echo $pagina;?>"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%"><div align="right"></div></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633ff">
    <td width="86%"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="Imagenes/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
   </td>
    <td width="14%" height="24">
<div align="right">
 <script type="text/javascript" language="JavaScript1.2">
<!--
stm_bm(["menu4bb6",400,"","blank.gif",0,"","",0,0,19,19,144,1,0,0,""],this);
stm_bp("p0",[0,4,0,0,0,3,0,7,100,"",-2,"",-2,90,0,0,"#006699","#ffffff","",3,0,0,"#ffffff"]);
stm_ai("p0i0",[0,"Recepcion","","",-1,-1,0,"","_self","","","","",0,0,0,"arrow_r.gif","arrow_r.gif",7,7,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","bold 8pt Arial","bold 8pt Arial",0,0]);
stm_bp("p1",[1,4,0,0,0,3,0,0,100,"progid:DXImageTransform.Microsoft.Checkerboard(squaresX=12,squaresY=12,direction=down,enabled=0,Duration=0.25)",11,"",-2,85,0,0,"#7f7f7f","transparent","",3,0,0,"#000000"]);
stm_aix("p1i0","p0i0",[0,"Ordenes de trabajo","","",-1,-1,0,"ordenes.php","_self","","","","",0,0,0,"","",0,0,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","8pt Arial","8pt Arial"]);
stm_aix("p1i1","p1i0",[0,"Ingresos","","",-1,-1,0,"ingresos.php"]);
stm_aix("p1i2","p1i0",[0,"Corte de caja","","",-1,-1,0,"corte.php"]);
stm_ep();
stm_aix("p0i1","p0i0",[0,"Catalogos"]);
stm_bpx("p2","p1",[]);
stm_aix("p2i0","p1i0",[0,"Pacientes","","",-1,-1,0,"clientes.php"]);
stm_aix("p2i1","p1i0",[0,"Medico","","",-1,-1,0,"medicos.php"]);
stm_aix("p2i2","p1i0",[0,"Estudios","","",-1,-1,0,"estudios.php"]);
stm_aix("p2i3","p1i0",[0,"Zonas","","",-1,-1,0,"institu.php"]);
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
if (cCampo=='Alias'){document.form1.Alias.value=document.form1.Alias.value.toUpperCase();}
if (cCampo=='Direccion'){document.form1.Direccion.value=document.form1.Direccion.value.toUpperCase();}
if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();}
if (cCampo=='Municipio'){document.form1.Municipio.value=document.form1.Municipio.value.toUpperCase();}
if (cCampo=='Referencia'){document.form1.Referencia.value=document.form1.Referencia.value.toUpperCase();}
if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
if (cCampo=='Director'){document.form1.Director.value=document.form1.Director.value.toUpperCase();}
if (cCampo=='Subdirector'){document.form1.Subdirector.value=document.form1.Subdirector.value.toUpperCase();}
if (cCampo=='Envio'){document.form1.Envio.value=document.form1.Envio.value.toUpperCase();}
if (cCampo=='Otro'){document.form1.Otro.value=document.form1.Otro.value.toUpperCase();}
if (cCampo=='Status'){document.form1.Status.value=document.form1.Status.value.toUpperCase();}
}
</script>
<hr noshade style="color:3366FF;height:2px">
<table width="973" height="325" border="0">
  <tr>
    <td><div align="center">
        <p>&nbsp;</p>
        <p><a href="institu.php?pagina=<? echo $pagina;?>"><img src="Imagenes/SmallExit.bmp" border="0"></a></p>
      </div></td>
  <td>
   <form name='form1' method='get' action='institue.php' onSubmit="return Completo();" >
        <font color='#000099' size='3'>Institucion:
        <?php if($lAg){echo $busca;}else{echo $Cpo[institucion];} ?>
        <p>Nombre..........:
        <input type="text" size="40" name="Nombre"  value ='<?php echo $Cpo[nombre]; ?>' onBlur="Mayusculas('Nombre')">
   	    &nbsp;&nbsp;&nbsp;Alias...............:
		<input name=Alias type=text size=15 value='<?php echo $Cpo[alias];?>' onBlur=Mayusculas('Alias')></p>
		<p>Direccion........:
		<input name=Direccion type=text size=45 value='<?php echo $Cpo[direccion];?>' onBlur=Mayusculas('Direccion')></p>
		<p>Colonia.........:
		<input name=Localidad type=text size=35 value='<?php echo $Cpo[localidad];?>' onBlur=Mayusculas('Localidad')>
		&nbsp;&nbsp;&nbsp;Municipio....:
		<input name=Municipio type=text size=30 value='<?php echo $Cpo[municipio];?>' onBlur=Mayusculas('Municipio')></p>
		<p>Ref. del lugar...:
		<input name=Referencia type=text size=50 value='<?php echo $Cpo[referencia];?>' onBlur=Mayusculas('Referencia')>
		Codigo Postal.:
		<input name=Codigo type=text size=7 value='<?php echo $Cpo[codigo];?>' onBlur=Mayusculas('Codigo')>
		</p>
		<p>R.f.c:
  		<input name=Rfc type=text size=10 value='<?php echo $Cpo[rfc];?>' onBlur=Mayusculas('Rfc')>
		Telefono:
		<input name=Telefono type=text size=15 value='<?php echo $Cpo[telefono];?>' onBlur=Mayusculas('Telefono')>
		&nbsp;&nbsp;&nbsp;Fax :
		<input name=Fax type=text size=13 value='<?php echo $Cpo[fax];?>' onBlur=Mayusculas('Fax')>
		&nbsp;&nbsp;&nbsp;L.Precios.:
    	<select name='Lista'>
         <option value=1>1</option>
		<option value=2>2</option>
		<option value=3>3</option>
		<option value=4>4</option>
		<option value=5>5</option>
        <option value=6>6</option>
		<option value=7>7</option>
		<option value=8>8</option>
		<option value=9>9</option>
		<option value=10>10</option>
		<option selected value=<?php echo $Cpo[lista];?>><?php echo $Cpo[lista];?></option>
		</select></p>
		<p>Mail.................: 
          <input name=Mail type=text size=40 value='<?php echo $Cpo[mail];?>'>
        </p>
        <p>Conds. de pago: 
          <select name='Condiciones'>
            <option value=CONTADO>CONTADO</option>
            <option value=CREDITO>CREDITO</option>
            <option selected value=<?php echo $Cpo[condiciones];?>><?php echo $Cpo[condiciones];?></option>
          </select>
          &nbsp;&nbsp;&nbsp;Envio de resultados: 
          <input name=Envio type=text size=20 value='<?php echo $Cpo[envio];?>' onBlur=Mayusculas('Envio')>
        </p>
        <p>Status...............: 
          <input name=Status type=text size=10 value='<?php echo $Cpo[status];?>' onBlur=Mayusculas('Status')>
          &nbsp;&nbsp;&nbsp;Otro dato : 
          <input name=Otro type=text size=20 value='<?php echo $Cpo[otro];?>' onBlur=Mayusculas('Otro')>
        </p>
        <p>Director...........: 
          <input name=Director type=text size=40 value='<?php echo $Cpo[director];?>' onBlur=Mayusculas('Director')>
        </p>
        <p>Sub-director....:
          <input name=Subdirector type=text size=40 value='<?php echo $Cpo[subdirector];?>' onBlur=Mayusculas('Subdirector')>
        </p>
        <p>Suplente..........:
          <input name=Suplente type=text size=40 value='<?php echo $Cpo[suplente];?>' onBlur=Mayusculas('Suplente')>
        </p>
		 <p><font color="#003399" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Observaciones</strong></font></p>
          <TEXTAREA NAME="Observaciones" cols="55" rows="5" ><?php echo "$Cpo[observaciones]";?></TEXTAREA>
		  <p><font color="#003399" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Caracteristicas de servicio</strong></font></p>
          <TEXTAREA NAME="Servicio" cols="55" rows="5" ><?php echo "$Cpo[servicio]";?></TEXTAREA>
		  <p><font color="#003399" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Caracteristicas Administrativas</strong></font></p>
          <TEXTAREA NAME="Administrativa" cols="55" rows="5" ><?php echo "$Cpo[administrativa]";?></TEXTAREA>
        </font> 
        <p>
        <input type="IMAGE" name="Guarda" src="imagenes/guarda.gif" alt="Guarda los ultimos movimientos y salte" >&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="IMAGE" name="Elimina" src="imagenes/elimina.gif" alt="Elimina este registro" onClick="SiElimina()">&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='Reset' value='Recupera'>
        <input type="hidden" name="orden" value=<?php echo $orden; ?>>
        <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
        <input type="hidden" name="busca" value=<?php echo $busca; ?>>
         <p>&nbsp; </p>
         </font>
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
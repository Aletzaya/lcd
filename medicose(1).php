<?php
  $Tabla="med";
  $Titulo="Detalle de medicos ($busca)";
  require("lib/kaplib.php");
  $link=conectarse();
  if($Guarda_x > 0){		//Para agregar uno nuevo
     $Sp=" ";
     $NomCom=trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
      if($busca=='NUEVO'){
         $lUp=mysql_query("insert into $Tabla (medico,apellidop,apellidom,nombre,rfc,cedula,codigo,nombrec,especialidad,subespecialidad,dirparticular,locparticular,telparticular,dirconsultorio,locconsultorio,telconsultorio,telcelular,mail,diasconsulta,hravisita,hraconsulta,zona,institucion,comisiones,telinstitucion,fecha,fechanac,comision,status,fecharev,refubicacion,servicio,observaciones) VALUES ('$Medico','$Apellidop','$Apellidom','$Nombre','$Rfc','$Cedula','$Codigo','$NomCom','$Especialidad','$Subespecialidad','$Dirparticular','$Locparticular','$Telparticular','$Dirconsultorio','$Locconsultorio','$Telconsultorio','$Telcelular','$Mail','$Diasconsulta','$Hravisita','$Hraconsulta','$Zona','$Institucion','$Comisiones','$Telinstitucion','$Fecha','$Fechanac','$Comision','Status','$Fecharev','$Refubicacion','$Servicio','$Observaciones')",$link);
 	  }else{
         $lUp=mysql_query("update $Tabla SET apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',rfc='$Rfc',cedula='$Cedula',codigo='$Codigo',nombrec='$NomCom',especialidad='$Especialidad',subespecialidad='$Subespecialidad',dirparticular='$Dirparticular',locparticular='$Locparticular',telparticular='$Telparticular',zona='$Zona',hraconsulta='$Hraconsulta',hravisita='$Hravisita',comisiones='$Comisiones',diasconsulta='$Diasconsulta',mail='$Mail',telcelular='$Telcelular',telconsultorio='$Telconsultorio',locconsultorio='$Locconsultorio',telinstitucion='$Telinstitucion',fecha='$Fecha',fechanac='$Fechanac',comision='$Comision',status='$Status',fecharev='$Fecharev',refubicacion='$Refubicacion',servicio='$Servicio',observaciones='$Observaciones' where medico='$busca' limit 1",$link);
 	  }
      header("Location: medicos.php?pagina=$pagina");
  }elseif($Elimina_x>0){    // Para dar de baja
      $lUp=mysql_query("delete from $Tabla where medico='$busca'",$link);
      header("Location: medicos.php?pagina=$pagina");
  }
  $cZna=mysql_query("select zona,descripcion from zns order by zona",$link);
  $cSql="select * from $Tabla where medico='$busca'";
  $CpoA=mysql_query($cSql,$link);
  $Cpo=mysql_fetch_array($CpoA);
  $lAg=$busca<>$Cpo[medico];
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
    <td width="14%" height="59"><a href="medicos.php?pagina=<?echo $pagina;?>"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></td>
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
  document.form1.Apellidop.focus();
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
if(document.form1.Apellidom.value==""){lRt=false;}
if(document.form1.Apellidop.value==""){lRt=false;}
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();
}if (cCampo=='Cedula'){document.form1.Cedula.value=document.form1.Cedula.value.toUpperCase();
}if (cCampo=='Apellidop'){document.form1.Apellidop.value=document.form1.Apellidop.value.toUpperCase();
}if (cCampo=='Apellidom'){document.form1.Apellidom.value=document.form1.Apellidom.value.toUpperCase();
}if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();
}if (cCampo=='Tipo'){document.form1.Dirparticular.value=document.form1.Dirparticular.value.toUpperCase();
}if (cCampo=='Locparticular'){document.form1.Locparticular.value=document.form1.Locparticular.value.toUpperCase();
}if (cCampo=='Dirparticular'){document.form1.Dirparticular.value=document.form1.Dirparticular.value.toUpperCase();
}if (cCampo=='Locconsultorio'){document.form1.Locconsultorio.value=document.form1.Locconsultorio.value.toUpperCase();
}if (cCampo=='Dirconsultorio'){document.form1.Dirconsultorio.value=document.form1.Dirconsultorio.value.toUpperCase();
}if (cCampo=='Codigo'){document.form1.Codigo.value=document.form1.Codigo.value.toUpperCase();
}if (cCampo=='Especialidad'){document.form1.Especialidad.value=document.form1.Especialidad.value.toUpperCase();
}if (cCampo=='Subespecialidad'){document.form1.Subespecialidad.value=document.form1.Subespecialidad.value.toUpperCase();
}if (cCampo=='Telconsultorio'){document.form1.Telconsultorio.value=document.form1.Telconsultorio.value.toUpperCase();
}if (cCampo=='Telcelular'){document.form1.Telcelular.value=document.form1.Telcelular.value.toUpperCase();
}if (cCampo=='Diasconsulta'){document.form1.Diasconsulta.value=document.form1.Diasconsulta.value.toUpperCase();
}if (cCampo=='Hravisita'){document.form1.Hravisita.value=document.form1.Hravisita.value.toUpperCase();
}if (cCampo=='Hraconsulta'){document.form1.Hraconsulta.value=document.form1.Hraconsulta.value.toUpperCase();}
}
</script>
<hr noshade style="color:3366FF;height:2px">
<table width="973" height="325" border="0">
  <tr>
    <td><div align="center"><a href="medicos.php?busca=<? echo $Cpo[medico] ?>"><img src="Imagenes/SmallExit.bmp" border="0"></a></div></td>
  <td>
   <form name="form1" method="get" action="medicose.php" onSubmit="return Completo();" >
              <font color="#0000FF"><b>Medico:
              <?php
              if($lAg){
			     echo "<input name=Medico type=text size=8 value = $Cpo[0] >";
			  }else{
                 echo $Cpo[0];
              }
              echo "&nbsp;&nbsp;&nbsp;".ltrim($Cpo[nombrec])."</b>";
              ?>
              &nbsp;&nbsp;Fecha
              alta:<input name="Fecha" type="text" size="8" value = <?php if($lAg){echo $Fecha;}else{echo $Cpo[fecha];}?> >
              &nbsp;&nbsp;Fecha Nac.:
              <input name="Fechanac" type="text" size="8" value = <?php echo $Cpo[fechanac];?> >
              <p>Apellido paterno: <input name="Apellidop" type="text" size="15" value ='<?php echo $Cpo[apellidop]; ?>'  onBlur="Mayusculas('Apellidop')">
              Apellido materno:<input name="Apellidom" type="text" size="15" value ='<?php echo $Cpo[apellidom]; ?>'  onBlur="Mayusculas('Apellidom')">
              Nombre:<input name="Nombre" type="text" size="15" value ='<?php echo $Cpo[nombre]; ?>'  onBlur="Mayusculas('Nombre')"></p>
              <p>Rfc
              <input type="text" name="Rfc" value ='<?php echo $Cpo[rfc]; ?>' onBlur="Mayusculas('Rfc')">
              Cedula
              <input type="text" name="Cedula" value ='<?php echo $Cpo[cedula]; ?>' onBlur="Mayusculas('Cedula')">
              Codigo postal
              <input type="text" name="Codigo" value ='<?php echo $Cpo[codigo]; ?>'onBlur="Mayusculas('Codigo')">
              </p>
            <p>Especialidad
              <input type="text" name="Especialidad" value ='<?php echo $Cpo[especialidad]; ?>'onBlur="Mayusculas('Especialidad')">
              Sub-especialidad
              <input type="text" name="Subespecialidad" value ='<?php echo $Cpo[subespecialidad]; ?>' onBlur="Mayusculas('Subespecialidad')">
              </p>
            <p>Direccion part.
              <input type="text" name="Dirparticular" size="20" value ='<?php echo $Cpo[dirparticular]; ?>' onBlur="Mayusculas('Dirparticular')">
              Localidad
              <input type="text" name="Locparticular" value ='<?php echo $Cpo[locparticular]; ?>' onBlur="Mayusculas('Locparticular')">
              Tel.
              <input type="text" name="Telparticular" value ='<?php echo $Cpo[telparticular]; ?>' onBlur="Mayusculas('Telparticular')" >
              </p>
              <p>Direccion cons
                <input type="text" name="Dirconsultorio" size="20" value ='<?php echo $Cpo[dirconsultorio]; ?>' onBlur="Mayusculas('Dirconsultorio')">
                Localidad
                <input type="text" name="Locconsultorio" value ='<?php echo $Cpo[locconsultorio]; ?>' onBlur="Mayusculas('Locconsultorio')">
                Tel.
                <input type="text" name="Telconsultorio" value ='<?php echo $Cpo[telconsultorio]; ?>' onBlur="Mayusculas('Telconsultorio')">
              </p>
              <p>Tel.celular
             <input type="text" name="Telcelular" value ='<?php echo $Cpo[telcelular]; ?>' onBlur="Mayusculas('Telcelular')">
             Mail
             <input type="text" name="Mail" size="35" value ='<?php echo $Cpo[mail]; ?>'>
             Tel.Inst.<input type="text" name="Telinstitucion" size="10" value ='<?php echo $Cpo[telinstitucion]; ?>' >
             </p>
             <p>Dias consulta
             <input type="text" name="Diasconsulta" size="25" value ='<?php echo $Cpo[diasconsulta]; ?>' onBlur="Mayusculas('Diasconsulta')" >
             Hra.visitas
             <input type="text" name="Hravisita" size="25" value ='<?php echo $Cpo[hravisita]; ?>' onBlur="Mayusculas('Hravisita')">
             Hra.consulta
             <input type="text" name="Hraconsulta" size="10" value ='<?php echo $Cpo[hraconsulta]; ?>' onBlur="Mayusculas('Hraconsulta')">
             </p>
             <p>%Comision :
             <input name="Comision" type="text" size="2" value = <?php echo $Cpo[comision];?> >
             Status[Activo/Inactivo]:
             <input name="Status" type="text" size="1" value = <?php echo $Cpo[status];?> >
             Fecha Revision:<input name="Fecharev" type="text" size="8" value = <?php echo $Cpo[fecharev];?> ></p>
             <p><font color="#0000FF" size="2">
             Zona:
             <?php
             echo "<select name='Zona'>";
             while ($Zna=mysql_fetch_array($cZna)){
                   echo "<option value=$Zna[zona]> $Zna[zona]&nbsp$Zna[descripcion]</option>";
                    if($Zna[zona]==$Cpo[zona]){$DesZna=$Zna[descripcion];}
             }
             echo "<option selected>$Cpo[zona]&nbsp;$DesZna</option>";
             echo "</select>";
             echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Niv.participacion :".$Cpo[participacion];
             ?>
             </font></p>
             <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Referencia de la ubicacion del consultorio:</strong></font></p>
             <TEXTAREA NAME="Refubicacion" cols="70" rows="5" ><?php echo "$Cpo[refubicacion]"; ?></TEXTAREA>
             <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Caracteristicas del Servicio:</strong></font></p>
            <p><font color="#0000FF">
            <TEXTAREA NAME="Servicio" cols="70" rows="5" ><?php echo "$Cpo[servicio]"; ?></TEXTAREA>
            </font></p>
            <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Observaciones:</strong></font></p>
            <p><font color="#0000FF">
            <TEXTAREA NAME="Observaciones" cols="70" rows="5" ><?php echo "$Cpo[observaciones]"; ?></TEXTAREA>
            </font></p>
            <p>
            <input type="IMAGE" name="Guarda" src="Imagenes/Guarda.gif" alt="Guarda cambios" >
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="IMAGE" name="Elimina" src="Imagenes/Elimina.gif" alt="Elimina Registro" onClick="SiElimina()">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type='Reset' value='Recupera'>
            <input type="hidden" name="orden" value=<?php echo $orden; ?>>
            <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
            <input type="hidden" name="busca" value=<?php echo $busca; ?>>
        </p>
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
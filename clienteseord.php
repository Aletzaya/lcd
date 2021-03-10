<?php
  // Es una copia de clientes solo s cambia las url a clienteseord
  session_start();

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Nombre=$_REQUEST[Nombre];

  $Apellidom=$_REQUEST[Apellidom];

  $Apellidop=$_REQUEST[Apellidop];

  $Tabla="cli";
  $Titulo="Detalle de clientes ($busca)";
  require("lib/kaplib.php");
  $link=conectarse();

  if($_REQUEST[Guarda_x] > 0){        //Para agregar uno nuevo
      $Sp=" ";
      $Fechan=$_REQUEST[Fechan];
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

         $lUp=mysql_query("insert into $Tabla (apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,credencial,codigo,sexo,titular,mail,padecimiento,alta,fechan,nombrec,afiliacion,observaciones,zona,institucion,expiracion,expira,refubicacion,fecha,clasificacion,complemento,otro)
         VALUES ('$Apellidop','$Apellidom','$Nombre','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]','$_REQUEST[Telefono]','$_REQUEST[Credencial]','$_REQUEST[Codigo]','$_REQUEST[Sexo]','$_REQUEST[Titular]','$_REQUEST[Mail]','$_REQUEST[Padecimiento]','$_REQUEST[Fecha]','$_REQUEST[Fechan]','$Nombrec','$_REQUEST[Afiliacion]','$_REQUEST[Observaciones]','$_REQUEST[Zona]','$_REQUEST[Institucion]','$_REQUEST[Expiracion]','$_REQUEST[Expira]','$_REQUEST[Refubicacion]','$_REQUEST[Fecha]','$_REQUEST[Clasificacion]','$_REQUEST[Complemento]','$_REQUEST[Otro]')",$link);

         $Id=mysql_insert_id();

         header("Location: ordenesnvas.php?op=cl&Cliente=$Id");

 	  }else{
         $lUp=mysql_query("update $Tabla SET apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',localidad='$_REQUEST[Localidad]',municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',credencial='$_REQUEST[Credencial]',codigo='$_REQUEST[Codigo]',sexo='$_REQUEST[Sexo]',titular='$_REQUEST[Titular]',mail='$_REQUEST[Mail]',padecimiento='$_REQUEST[Padecimiento]',alta='$_REQUEST[Alta]',fechan='$_REQUEST[Fechan]',nombrec='$Nombrec',afiliacion='$_REQUEST[Afiliacion]',observaciones='$_REQUEST[Observaciones]',zona='$_REQUEST[Zona]',institucion='$_REQUEST[Institucion]',refubicacion='$_REQUEST[Refubicacion]',expiracion='$_REQUEST[Expiracion]',expira='$_REQUEST[Expira]',fecha='$_REQUEST[Fecha]',clasificacion='$_REQUEST[Clasificacion]',complemento='$_REQUEST[Complemento]',otro='$_REQUEST[Otro]' where cliente='$busca' limit 1",$link);
         header("Location: ordenesnvas.php?op=cl&Cliente=$busca");
 	  }
  }elseif($_REQUEST[Lupa_x] > 0){        //Para agregar uno nuevo
      $cSql="select * from $Tabla where (nombre='$_REQUEST[Nombre]' and apellidop='$_REQUEST[Apellidop]' and apellidom='$_REQUEST[Apellidom]')";
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
     $busca=$Cpo[cliente];
 }
 $lAg=$_REQUEST[Apellidop]<>$Cpo[apellidop];
 $Fecha=date("Y-m-d");
?>
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<?php if($busca=="NVO"){echo "<body bgcolor='#FFFFFF' onload='cFocus1()'>";}else{echo "<body bgcolor='#FFFFFF' onload='cFocus2()'>";} ?>
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><a href="ordenesnvas.php?pagina=<?echo $pagina;?>"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%"><div align="right"></div></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633ff">
    <td width="86%"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
   </td>
    <td width="14%" height="24">
    <div align="right">
	 <!-- Menuuuuuuuuuu -->
    </div></td>
  </tr>
</table>
<script language="JavaScript1.2">
function cFocus1(){
  document.form2.Apellidop.focus();
}
function cFocus2(){
  document.form1.Fechan.focus();
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
}if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Direccion'){document.form1.Direccion.value=document.form1.Direccion.value.toUpperCase();
}if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();
}if (cCampo=='Telefono'){document.form1.Telefono.value=document.form1.Telefono.value.toUpperCase();
}if (cCampo=='Municipio'){document.form1.Municipio.value=document.form1.Municipio.value.toUpperCase();
}if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
}
</script>
<hr noshade style="color:3366FF;height:2px">
<table width="973" height="325" border="0">
  <tr>
    <td><div align="center">
        <p><a href="ordenesnvas.php?op=cl&Cliente=<?php echo  $busca; ?>"><img src="images/SmallExit.bmp" border="0"></a></p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
      </div></td>
  <td>
   <form name='form2' method='get' action='clienteseord.php'>
     <font color='#000099' size='4'>Cliente: <?php if($lAg){echo $busca;}else{echo $Cpo[cliente];} ?>&nbsp;&nbsp;&nbsp;Nombre completo: <? echo $Cpo[nombrec];?> </font>
     <p><font size='2' color='#0000FF'>Apellido pat.:
     <input name='Apellidop' type='text' size='15' value = '<?php echo $Apellidop;?>' onBlur=Mayusculas('Apellidop')>
     Apellido mat.:
     <input name='Apellidom' type='text' size='15' value = '<?php echo $Apellidom;?>' onBlur=Mayusculas('Apellidom')>
     Nombre:
     <input name='Nombre' type='text' size='10' value = '<?php echo $Nombre;?>' onBlur=Mayusculas('Nombre')>
     <input type="IMAGE" name="Lupa" src="images/lupa.gif" alt="Busca un paciente con estos datos" >&nbsp;&nbsp;&nbsp;&nbsp;
     <input type="hidden" name="orden" value=<?php echo $orden; ?>>
     <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
     <input type="hidden" name="busca" value=<?php echo 'NUEVO'; ?>>
     </font></p>
  </form>
  <form name="form1" method="get" action="clienteseord.php" onSubmit="return Completo();" >
            <p><font color="#0000FF" size="2">F.Alta :
              <input type="text" name="Fecha" size="9" value ='<?php if(!$lAg){echo $Cpo[fecha];}else{echo $Fecha;} ?>' onBlur="Mayusculas('Fecha')">
              F.Nacimiento :
              <input type="text" name="Fechan" size="10" value ='<?php if(!$lAg){echo $Cpo[fechan];}?>' >
              No.A&ntilde;os:
              <input type="text" size="4" name="Anos" value ='<?php echo $Fecha-$Cpo[fechan]; ?>' >
              Sexo
              <select name='Sexo'>
                <option value=M >M</option>
                <option value=F >F</option>
                <option selected ><?php if($Cpo[sexo]==''){echo 'M';}else{echo $Cpo[sexo];}?></option>
              </select>
              </font></p>
			  <p><font color="#0000FF" size="2">Direccion
              <input type="text" name="Direccion"  value ='<?php echo $Cpo[direccion]; ?>' onBlur="Mayusculas('Direccion')">
                Colonia
                <input type="text" name="Localidad" value ='<?php echo $Cpo[localidad]; ?>' onBlur="Mayusculas('Localidad')">
                Mpio:
                <input type="text" name="Municipio" value ='<?php if(!$lAg){echo $Cpo[municipio];} ?>' onBlur="Mayusculas('Municipio')">
                </font></p>
              <p><font color="#0000FF" size="2">Telefono :
                <input type="text" name="Telefono" size="25" value ='<?php echo $Cpo[telefono]; ?>' onBlur="Mayusculas('Telefono')" >
                Credencial
                <input type="text" name="Credencial" value ='<?php echo $Cpo[credencial]; ?>' onBlur="Mayusculas('Credencial')">
                Cod.Postal
                <input type="text" name="Codigo" size="5" value ='<?php echo $Cpo[codigo]; ?>' onBlur="Mayusculas('Codigo')">
                </font></p>
  
              <p><font color="#0000FF" size="2">Titular
                <input type="text" name="Titular" value ='<?php if(!$lAg){echo $Cpo[titular];} ?>'onBlur="Mayusculas('Titular')">
                Mail
                <input type="text" name="Mail" value ='<?php if(!$lAg){echo $Cpo[mail];} ?>'>
                Clasificacion fam.:
                <select name='Clasificacion'>
                  <option value='El mismo'>El mismo</option>
                  <option value=Esposa>Esposa</option>
                  <option value=Esposo>Esposo</option>
                  <option value=1er.hijo>1er.hijo</option>
                  <option value=2do.hijo>2do.hijo</option>
                  <option value=3er.hijo>3er.hijo</option>
                  <option value=4to.hijo>4o.hijo</option>
                  <option value=5to.hijo>5o.hijo</option>
                  <option value=Mamá>Mamá</option>
                  <option value=Papá>Papá</option>
                  <option value=Concubina>Concubina</option>
                  <option value=Otro>Otro</option>
                  <option selected><?php if($Cpo[clasificacion]==''){echo 'El mismo';}else{echo $Cpo[clasificacion];}?></option>
                </select>
                </font></p>
              
        <p><font color="#0000FF" size="2">Afiliacion: 
          <input name="Afiliacion" type="text" value="<?php if(!$lAg){echo $Cpo[afiliacion];}?>">
          Expira[S/N]: 
          <input name="Expira" type="text" value="<?php echo $Cpo[expira];?>" size="2">
          Fecha Expiracion:
          <input name="Expiracion" type="text"  value="<?php if(!$lAg){echo $Cpo[expiracion];}?>"size="8">
          </font></p>
        <p><font color="#0000FF" size="2">Dato complementario 
          <input name="Complemento" type="text" size="30" value="<?php if(!$lAg){echo $Cpo[complemento];}?>">
          &nbsp;&nbsp;&nbsp;Otro dato: 
          <input name="Otro" type="text" size="30" value="<?php if(!$lAg){echo $Cpo[otro];}?>">
          </font></p>
              <p><font color="#0000FF" size="2">Zona:
			     <?php
			     echo "<select name='Zona'>";
		         while ($Zna=mysql_fetch_array($cZna)){
                 echo "<option value=$Zna[zona]>$Zna[zona] $Zna[descripcion]</option>";
					  if($Zna[zona]==$Cpo[zona]){$DesZna=$Zna[descripcion];}
               }
                 if($Cpo[zona]==''){
                     echo "<option selected>1&nbsp;$DesZna</option>";
                 }else{
                     echo "<option selected>$Cpo[zona]&nbsp;$DesZna</option>";
                 }
    	         echo "</select>";
				 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Institucion:";
			     echo "<select name='Institucion'>";
		         while ($Ins=mysql_fetch_array($cIns)){
                      echo "<option value=$Ins[institucion]>$Ins[institucion] $Ins[alias]</option>";
					  if($Ins[institucion]==$Cpo[institucion]){$DesIns=$Ins[alias];}
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
              <input type="IMAGE" name="Guarda" src="images/Guarda.gif" alt="Guarda los ultimos movimientos y salte" >&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="IMAGE" name="Elimina" src="images/Elimina.gif" alt="Elimina este registro" onClick="SiElimina()">&nbsp;&nbsp;&nbsp;&nbsp;
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
<?php
  session_start();

  require("lib/kaplib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Nombre=$_REQUEST[Nombre];

  $Apellidom=$_REQUEST[Apellidom];

  $Apellidop=$_REQUEST[Apellidop];

  $Tabla="cli";

  $Titulo="Detalle de clientes ($busca)";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  $An=$_REQUEST[An];
      $Sp=" ";
//      $Fechan=$_REQUEST[Fechan];
      $Nombrec=trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
      $Fecha=date("Y-m-d");
      $Mes=substr($Fechan,5,2);
      $Dia=substr($Fechan,8,2);
      $Ano=substr($Fechan,0,4);

     if($An > 0){
//         $Fecha=date("Y-m-d");
         $Fechan = $Ano - $An ."-".$Mes."-".$Dia;
      }
      if($busca=='NUEVO'){

         $lUp=mysql_query("insert into $Tabla (apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,credencial,codigo,sexo,titular,mail,padecimiento,alta,fechan,nombrec,afiliacion,observaciones,zona,institucion,expiracion,expira,refubicacion,fecha,clasificacion,complemento,otro,usr)
         VALUES ('$Apellidop','$Apellidom','$Nombre','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]','$_REQUEST[Telefono]','$_REQUEST[Credencial]','$_REQUEST[Codigo]','$_REQUEST[Sexo]','$_REQUEST[Titular]','$_REQUEST[Mail]','$_REQUEST[Padecimiento]','$_REQUEST[Fecha]','$Fechan',
         '$Nombrec','$_REQUEST[Afiliacion]','$_REQUEST[Observaciones]','$_REQUEST[Zona]','$_REQUEST[Institucion]','$_REQUEST[Expiracion]','$_REQUEST[Expira]','$_REQUEST[Refubicacion]','$_REQUEST[Fechai]','$_REQUEST[Clasificacion]','$_REQUEST[Complemento]','$_REQUEST[Otro]','$Usr')",$link);

         $busca=mysql_insert_id();

       }else{
         $lUp=mysql_query("update $Tabla SET apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',localidad='$_REQUEST[Localidad]',municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',credencial='$_REQUEST[Credencial]',
         codigo='$_REQUEST[Codigo]',sexo='$_REQUEST[Sexo]',titular='$_REQUEST[Titular]',mail='$_REQUEST[Mail]',padecimiento='$_REQUEST[Padecimiento]',alta='$_REQUEST[Alta]',fechan='$Fechan',nombrec='$Nombrec',afiliacion='$_REQUEST[Afiliacion]',observaciones='$_REQUEST[Observaciones]',zona='$_REQUEST[Zona]',
         institucion='$_REQUEST[Institucion]',refubicacion='$_REQUEST[Refubicacion]',expiracion='$_REQUEST[Expiracion]',expira='$_REQUEST[Expira]',fecha='$_REQUEST[Fechai]',clasificacion='$_REQUEST[Clasificacion]',complemento='$_REQUEST[Complemento]',otro='$_REQUEST[Otro]',usr='$Usr' where cliente='$busca' limit 1",$link);
       }

      if($_REQUEST[Boton] == Aceptar){
         header("Location: clientes.php?busca=$busca");
      }

  }elseif($_REQUEST[Lupa_x] > 0){        //Para agregar uno nuevo

      $cSql="select * from $Tabla where (nombre='$_REQUEST[Nombre]' and apellidop='$_REQUEST[Apellidop]' and apellidom='$_REQUEST[Apellidom]')";

  }elseif($_REQUEST[Boton] == Cancelar){

         header("Location: clientes.php?busca=$busca&pagina=$pagina");

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
    // $busca=$Cpo[cliente];
 }
 
 if($busca=='NUEVO'){$lAg=true;}  
 //$lAg=$_REQUEST[Apellidop]<>$Cpo[apellidop];
  

 $Fecha=date("Y-m-d");

 require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body>

<?php headymenu($Titulo,0); ?>

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
<table width="100%" border="0">
    <?php
    echo "<tr>";
      echo "<td  width='15%' rowspan='2'>";
         echo "<a class='pg' href='clientes.php?pagina=$pagina'>$Gfont Regresar</font></a>";
         echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
      echo "</td>";
      echo "<td> $Gfont ";
      echo "<form name='form2' method='get' action='clientese.php'>";
           echo "Cliente: ";
           if($lAg){echo $busca;}else{echo $Cpo[cliente];}
           echo "&nbsp; &nbsp; Nombre completo: $Cpo[nombrec] ";
           echo "<p>Apellido pat.: ";
           echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop'>";
           //echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop' onBlur=Mayusculas('Apellidop')>";
           echo " Apellido mat.:";
           echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom'>";
           //echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom' onBlur=Mayusculas('Apellidom')>";
           echo " Nombre: ";
           echo " <input name='Nombre' type='text' size='10' value = '$Nombre'>";
           //echo " <input name='Nombre' type='text' size='10' value = '$Nombre' onBlur=Mayusculas('Nombre')>";
           echo " <input type='IMAGE' name='Lupa' src='images/lupa.gif' alt='Busca un paciente con estos datos' > ";

           echo "<input type='hidden' name='orden' value='$orden'>";
           echo "<input type='hidden' name='pagina' value='$pagina'>";
           echo "<input type='hidden' name='busca' value='NUEVO'>";
           echo "</font></p>";
     echo "</form>";

    ?>
  <form name="form1" method="get" action="clientese.php" onSubmit="return Completo();" >
            <p><font color="#0000FF" size="2">F.Alta :
              <input type="text" name="Fechai" size="10" value ='<?php if(!$lAg){echo $Cpo[fecha];}else{echo $Fecha;}?>' >
              &nbsp; F.Nacimiento :
              <input type="text" name="Fechan" size="10" value ='<?php if(!$lAg){echo $Cpo[fechan];}?>' >
              &nbsp; No.A&ntilde;os:
              <input type="text" size="4" name="An">
			  <?php $AnosX = $Fecha - $Cpo[fechan]; ?>
	          &nbsp; A&ntilde;os: &nbsp; <?php echo $AnosX; ?>
              &nbsp; Sexo
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
                <input type="text" name="Municipio" value ='<?php echo $Cpo[municipio]; ?>' onBlur="Mayusculas('Municipio')">
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

                 echo " &nbsp; &nbsp; Usuario: $Cpo[usr]";
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
              <?php

                echo "<input type='hidden' name='Apellidop'>";
                echo "<input type='hidden'name='Apellidom'>";
                echo "<input type='hidden' name='Nombre'>";

                echo Botones();

                mysql_close();
              ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
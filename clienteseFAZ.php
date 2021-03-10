<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr        = $check['uname'];
  $link       = conectarse();
  $busca      = $_REQUEST[busca];

  $Nombre     = $_REQUEST[Nombre];
  $Apellidom  = $_REQUEST[Apellidom];
  $Apellidop  = $_REQUEST[Apellidop];

  $Tabla      = "cli";

  $Titulo     = "Detalle por cliente";
  
  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      $Sp      = " ";
      $Fechan  = $_REQUEST[Fechan];
      $Nombrec = trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
      $Fecha   = date("Y-m-d");
      $Mes     = substr($Fechan,5,2);
      $Dia     = substr($Fechan,8,2);
      $Ano     = substr($Fechan,0,4);

     if(!checkdate($Mes,$Dia,$Ano)){
		 $Anos=$_REQUEST[Anos];	
         $Fecha=date("Y-m-d");
         $Fechan = substr($Fecha,0,4) - $Anos ."-".substr($Fecha,5,2)."-".substr($Fecha,8,2);
      }
      if($busca=='NUEVO'){

         $lUp = mysql_query("INSERT INTO $Tabla (apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,
                credencial,codigo,sexo,titular,mail,padecimiento,alta,fechan,nombrec,afiliacion,observaciones,zona,
                institucion,expiracion,expira,refubicacion,fecha,clasificacion,complemento,otro,usr,programa)
                VALUES 
                ('$Apellidop','$Apellidom','$Nombre','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]',
                '$_REQUEST[Telefono]','$_REQUEST[Credencial]','$_REQUEST[Codigo]','$_REQUEST[Sexo]','$_REQUEST[Titular]',
                '$_REQUEST[Mail]','$_REQUEST[Padecimiento]','$_REQUEST[Fecha]','$_REQUEST[Fechan]','$Nombrec',
                '$_REQUEST[Afiliacion]','$_REQUEST[Observaciones]','$_REQUEST[Zona]','$_REQUEST[Institucion]',
                '$_REQUEST[Expiracion]','$_REQUEST[Expira]','$_REQUEST[Refubicacion]','$_REQUEST[Fechai]',
                '$_REQUEST[Clasificacion]','$_REQUEST[Complemento]','$_REQUEST[Otro]','$Usr','$_REQUEST[Programa]')");

        $busca = mysql_insert_id();

       }else{
       
         $lUp = mysql_query("UPDATE $Tabla SET apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',
                nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',localidad='$_REQUEST[Localidad]',
                municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',credencial='$_REQUEST[Credencial]',
                codigo='$_REQUEST[Codigo]',sexo='$_REQUEST[Sexo]',titular='$_REQUEST[Titular]',mail='$_REQUEST[Mail]',
                padecimiento='$_REQUEST[Padecimiento]',alta='$_REQUEST[Alta]',fechan='$_REQUEST[Fechan]',
                nombrec='$Nombrec',afiliacion='$_REQUEST[Afiliacion]',observaciones='$_REQUEST[Observaciones]',
                zona='$_REQUEST[Zona]',institucion='$_REQUEST[Institucion]',refubicacion='$_REQUEST[Refubicacion]',
                expiracion='$_REQUEST[Expiracion]',expira='$_REQUEST[Expira]',fecha='$_REQUEST[Fechai]',
                clasificacion='$_REQUEST[Clasificacion]',complemento='$_REQUEST[Complemento]',otro='$_REQUEST[Otro]',
                usr='$Usr',programa='$_REQUEST[Programa]' 
                WHERE cliente='$busca' limit 1");
       }

      if($_REQUEST[Boton] == Aceptar){
         header("Location: clientes.php");
      }

  }elseif($_REQUEST[Lupa_x] > 0){        //Para agregar uno nuevo

      $cSql = "SELECT * FROM $Tabla WHERE (nombre='$_REQUEST[Nombre]' AND apellidop='$_REQUEST[Apellidop]' AND apellidom='$_REQUEST[Apellidom]')";

  }elseif($_REQUEST[Boton] == Cancelar){

         header("Location: clientes.php");

  }else{

      $cSql="SELECT * FROM $Tabla WHERE cliente='$busca'";

  }

  $cZna = mysql_query("SELECT zona,descripcion FROM zns order by zona");
  
  $cIns = mysql_query("SELECT institucion,alias FROM inst order by institucion");

  $CpoA = mysql_query($cSql);
  
  if($Cpo=mysql_fetch_array($CpoA)){
     $Apellidop=$Cpo[apellidop];
     $Apellidom=$Cpo[apellidom];
     $Nombre=$Cpo[nombre];
    // $busca=$Cpo[cliente];
 }
 
 if($busca=='NUEVO'){$lAg=true;}  
 //$lAg=$_REQUEST[Apellidop]<>$Cpo[apellidop];
  

 $Fecha=date("Y-m-d");
 
 
 $aPrg = array('Ninguno','Cliente frecuente','Diabetes','Chequeo medico','Otro');

 require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

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

<table width="100%" border="0">
    <?php
    echo "<tr>";

      echo "<td  width='5%'>";
      
         echo "<a href='clientes.php'><img src='lib/regresa.jpg' border='0'></a><p>&nbsp;</p><p>&nbsp;</p>";

      echo "</td>";
      
      echo "<td align='center'> $Gfont ";
      
      echo "<form name='form2' method='get' action='clientese.php'>";
      
           //if($lAg){echo $busca;}else{echo $Cpo[cliente];}

           echo "<div align='center'><font size='+1'>Cliente: $busca ";           
           echo "&nbsp; Nombre completo: $Cpo[nombrec]</font> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </div>";
           
           echo "<p>Apellido pat.: ";
           echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop' onBlur=Mayusculas('Apellidop')>";
           //echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop' onBlur=Mayusculas('Apellidop')>";
           echo " Apellido mat.:";
           echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom' onBlur=Mayusculas('Apellidom')>";
           //echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom' onBlur=Mayusculas('Apellidom')>";
           echo " Nombre: ";
           echo " <input name='Nombre' type='text' size='10' value = '$Nombre' onBlur=Mayusculas('Nombre')>";
           //echo " <input name='Nombre' type='text' size='10' value = '$Nombre' onBlur=Mayusculas('Nombre')>";
           echo " <input type='IMAGE' name='Lupa' src='images/lupa.gif' alt='Busca un paciente con estos datos' > ";

           echo "<input type='hidden' name='orden' value='$orden'>";
           echo "<input type='hidden' name='pagina' value='$pagina'>";
           echo "<input type='hidden' name='busca' value='NUEVO'>";
           echo "</font></p>";

     echo "</form>";
    

    echo "<form name='form1' method='get' action='clientese.php' onSubmit='return Completo();'>";
	 

		$Fecha2    = date("Y-m-d");
		$fecha_nac = $Cpo[fechan];
		$dia       = substr($Fecha2, 8, 2);
		$mes       = substr($Fecha2, 5, 2);
		$anno      = substr($Fecha2, 0, 4);
		$dia_nac   = substr($fecha_nac, 8, 2);
		$mes_nac   = substr($fecha_nac, 5, 2);
		$anno_nac  = substr($fecha_nac, 0, 4);
		
		if($mes_nac>$mes){
			$calc_edad= $anno-$anno_nac-1;
		}else{
			if($mes==$mes_nac AND $dia_nac>$dia){
				$calc_edad= $anno-$anno_nac-1; 
			}else{
				$calc_edad= $anno-$anno_nac;
			}
		}

              cTable('70%','0'); 
   
              cInput("Fecha alta:","Text","10","Fechai","right",$Fecha,"10",true,false,"<img src=lib/calendar.png border=0 onclick=displayCalendar(document.forms[0].Fechai,'yyyy-mm-dd',this)>");
		
              //cInput('Fecha alta:','text','10','Fechai','right',$Cpo[fechai],'10',false,false,'');
              cInput('Fecha nacimiento:','text','10','Fechan','right',$Cpo[fechan],'10',false,false,'');
              
              cInput('No. a&ntilde;os:','text','5','Ano','right',$calc_edad,'5',false,false,'');

          	  echo "<tr><td align='right'>$Gfont <b>Sexo:</b> &nbsp; </td><td>";
              echo "<SELECT name='Sexo'>";
              echo "<option value='M'>M</option>";
              echo "<option value='F'>F</option>";
              echo "<option SELECTED>$Cpo[sexo]</option>";
              echo "</SELECT>";
              echo "</td></tr>";
		
					/*
              <p>F.Alta :
              <input type="text" name="Fechai" size="10" value ='<?php if(!$lAg){echo $Cpo[fecha];}else{echo $Fecha;}?>' >
              &nbsp; F.Nacimiento :
              <input type="text" name="Fechan" size="10" value ='<?php if(!$lAg){echo $Cpo[fechan];}?>' >
              &nbsp; No.A&ntilde;os:
              <input type="text" name="Anos" size="4" value ='<?php echo $calc_edad; ?>' >
              &nbsp; Sexo
              <SELECT name='Sexo'>
                <option value=M >M</option>
                <option value=F >F</option>
                <option SELECTed ><?php if($Cpo[sexo]==''){echo 'M';}else{echo $Cpo[sexo];}?></option>
              </SELECT>
              </p>
   			   */
   			   
 				    			 
              
              cInput('Direccion:','text','30','Direccion','right',$Cpo[direccion],'40',false,false,'');
              cInput('Colonia:','text','20','Localidad','right',$Cpo[localidad],'20',false,false,'');
              cInput('Municipio:','text','20','Municipio','right',$Cpo[municipio],'20',false,false,'');
              cInput('Telefono:','text','20','Telefono','right',$Cpo[telefono],'20',false,false,'');
              cInput('Credencial:','text','20','Credencial','right',$Cpo[credencial],'20',false,false,'');
              cInput('Codigo postal:','text','10','Codigo','right',$Cpo[codigo],'10',false,false,'');
              cInput('Titular:','text','10','Titular','right',$Cpo[titular],'40',false,false,'');
              cInput('Mail:','text','20','Mail','right',$Cpo[mail],'20',false,false,'');

          	  echo "<tr><td align='right'>$Gfont <b>Clasificacion Familiar:</b> &nbsp; </td><td>";
              echo "<SELECT name='Clasificacion'>";
              echo "<option value='El mismo'>El mismo</option>";
              echo "<option value=Esposa>Esposa</option>";
              echo "<option value=Esposo>Esposo</option>";
              echo "<option value=1er.hijo>1er.hijo</option>";
              echo "<option value=2do.hijo>2do.hijo</option>";
              echo "<option value=3er.hijo>3er.hijo</option>";
              echo "<option value=4to.hijo>4o.hijo</option>";
              echo "<option value=5to.hijo>5o.hijo</option>";
              echo "<option value=Mam&aacute;>Mam&aacute;</option>";
              echo "<option value=Pap&aacute;>Pap&aacute;</option>";
              echo "<option value=Concubina>Concubina</option>";
              echo "<option value=Otro>Otro</option>";
              echo "<option SELECTED>$Cpo[clasificacion]</option>";
              echo "</SELECT>";
              echo "</td></tr>";
              
              cInput('Afiliacion:','text','20','Afiliacion','right',$Cpo[afiliacion],'20',false,false,'');
              
          	  echo "<tr><td align='right'>$Gfont <b>Expira:</b> &nbsp; </td><td>";
              echo "<SELECT name='Expira'>";
              echo "<option value='S'>S</option>";
              echo "<option value='N'>N</option>";
              echo "<option SELECTED>$Cpo[expira]</option>";
              echo "</SELECT>";
              echo "</td></tr>";
              
              cInput('Fecha de expiracion:','text','10','Expiracion','right',$Cpo[expiracion],'20',false,false,'');

              cInput('Dato complementario:','text','40','Complemento','right',$Cpo[complemento],'40',false,false,'');
              cInput('Otro dato:','text','40','Otro','right',$Cpo[otro],'40',false,false,'');

          	  echo "<tr><td align='right'>$Gfont <b>Zona:</b> &nbsp; </td><td>";

              echo "<SELECT name='Zona'>";
              while ($Zna=mysql_fetch_array($cZna)){
                 echo "<option value=$Zna[zona]>$Zna[zona] $Zna[descripcion]</option>";
                 if($Zna[zona]==$Cpo[zona]){$DesZna=$Zna[descripcion];}
              }
              if($Cpo[zona]==''){
                     echo "<option SELECTED>1&nbsp;$DesZna</option>";
              }else{
                     echo "<option SELECTED>$Cpo[zona]&nbsp;$DesZna</option>";
              }
              echo "</SELECT>";
              echo "</td></tr>";
    
              cTableCie();
              
                 echo "$Gfont Institucion:";
                 echo "<SELECT name='Institucion'>";
                 while ($Ins=mysql_fetch_array($cIns)){
                      echo "<option value=$Ins[institucion]>$Ins[institucion] $Ins[alias]</option>";
                      if($Ins[institucion]==$Cpo[institucion]){$DesIns=$Ins[alias];}
                 }
                 echo "<option SELECTed>$Cpo[institucion]&nbsp;$DesIns</option>";
                 echo "</SELECT>";

					  $nPrg = $Cpo[programa];
					  
                 echo " &nbsp; &nbsp; Programa d'salud:";
                 echo "<SELECT name='Programa'>";
                 echo "<option value='1'>1.Cliente frecuente</option>";
                 echo "<option value='2'>2.Diabetes</option>";
                 echo "<option value='3'>3.Chequeo medico</option>";
                 echo "<option value='4'>4.Otro</option>";
                 echo "<option value='0'>0.Ninguno</option>";
                 echo "<option SELECTed value='$Cpo[programa]'>$aPrg[$nPrg]</option>";
                 echo "</SELECT> <br><br> ";
                 echo "&nbsp;Usuario: $Cpo[usr]";
                 ?>
              </p>
              <p>Padecimientos:</p>
              <p>
                <TEXTAREA NAME="Padecimiento" cols="70" rows="6" ><?php echo "$Cpo[padecimiento]"; ?></TEXTAREA>
              </p>
              <p>Observaciones:</p>
              <p>
                <TEXTAREA NAME="Observaciones" cols="70" rows="6" ><?php echo "$Cpo[observaciones]"; ?></TEXTAREA>
              </p>
              
              <p>Ref.Ubicacion</p>
              
              <textarea name="Refubicacion" cols="70" rows="6" ><?php echo "$Cpo[refubicacion]"; ?></textarea>
              </p>
              <?php

                echo "<input type='hidden' name='Apellidop'>";
                echo "<input type='hidden'name='Apellidom'>";
                echo "<input type='hidden' name='Nombre'>";

                echo Botones();

  echo "Fecha final: ";
  echo "<input type='text' name='FechaF' value='$FechaF' size='8'> &nbsp;";
  echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)> &nbsp; &nbsp; ";

                mysql_close();
              ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
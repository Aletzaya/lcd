<?php
  session_start();

  require("lib/libN.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr        = $check['uname'];
  $link       = conectarse();
  $busca      = $_REQUEST[busca];

  $Nombre     = $_REQUEST[Nombre];
  $Apellidom  = $_REQUEST[Apellidom];
  $Apellidop  = $_REQUEST[Apellidop];
  $Vta=$_REQUEST[Vta];

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

     if(!checkdate($Mes,$Dia,$Ano)){		//En caso que el valor de Fec/Nac no sea correcto
           $Anos   = $_REQUEST[Anos];	
         $Fecha  = date("Y-m-d");
         $Fechan = substr($Fecha,0,4) - $Anos ."-".substr($Fecha,5,2)."-".substr($Fecha,8,2);
      }
      if($busca=='NUEVO'){

         $lUp = mysql_query("INSERT INTO $Tabla (apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,
                credencial,codigo,sexo,titular,mail,padecimiento,alta,fechan,nombrec,afiliacion,observaciones,zona,
                institucion,expiracion,expira,refubicacion,fecha,clasificacion,complemento,otro,usr,programa,colonia)
                VALUES 
                ('$Apellidop','$Apellidom','$Nombre','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]',
                '$_REQUEST[Telefono]','$_REQUEST[Credencial]','$_REQUEST[Codigo]','$_REQUEST[Sexo]','$_REQUEST[Titular]',
                '$_REQUEST[Mail]','$_REQUEST[Padecimiento]','$_REQUEST[Fecha]','$Fechan','$Nombrec',
                '$_REQUEST[Afiliacion]','$_REQUEST[Observaciones]','$_REQUEST[Zona]','$_REQUEST[Institucion]',
                '$_REQUEST[Expiracion]','$_REQUEST[Expira]','$_REQUEST[Refubicacion]','$Fecha',
                '$_REQUEST[Clasificacion]','$_REQUEST[Complemento]','$_REQUEST[Otro]','$Usr','$_REQUEST[Programa]','$_REQUEST[Colonia]')");

        $busca = mysql_insert_id();

       }else{

         $lUp = mysql_query("UPDATE $Tabla SET apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',
                nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',localidad='$_REQUEST[Localidad]',
                municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',credencial='$_REQUEST[Credencial]',
                codigo='$_REQUEST[Codigo]',sexo='$_REQUEST[Sexo]',titular='$_REQUEST[Titular]',mail='$_REQUEST[Mail]',
                padecimiento='$_REQUEST[Padecimiento]',alta='$_REQUEST[Alta]',fechan='$_REQUEST[Fechan]',
                nombrec='$Nombrec',afiliacion='$_REQUEST[Afiliacion]',observaciones='$_REQUEST[Observaciones]',
                zona='$_REQUEST[Zona]',institucion='$_REQUEST[Institucion]',refubicacion='$_REQUEST[Refubicacion]',
                expiracion='$_REQUEST[Expiracion]',expira='$_REQUEST[Expira]',
                clasificacion='$_REQUEST[Clasificacion]',complemento='$_REQUEST[Complemento]',otro='$_REQUEST[Otro]',
                usrmod='$Usr',programa='$_REQUEST[Programa]',fecmod='$Fecha',colonia='$_REQUEST[Colonia]' 
                WHERE cliente='$busca' limit 1");
       }

      if($_REQUEST[Boton] == Aceptar){
         header("Location: ordenesnvasN.php?op=cl&Cliente=$busca&Vta=$Vta");          
      }

  }elseif($_REQUEST[Boton] == 'Buscar'){        //Para agregar uno nuevo

      $cSql = "SELECT * FROM $Tabla WHERE (nombre='$_REQUEST[Nombre]' AND apellidop='$_REQUEST[Apellidop]' AND apellidom='$_REQUEST[Apellidom]')";

  }elseif($_REQUEST[Boton] == Cancelar){

         header("Location: clientes.php");

  }else{

      $cSql="SELECT * FROM $Tabla WHERE cliente='$busca'";

  }


  $CpoA = mysql_query($cSql);
  
  if($Cpo=mysql_fetch_array($CpoA)){
     $Apellidop=$Cpo[apellidop];
     $Apellidom=$Cpo[apellidom];
     $Nombre=$Cpo[nombre];
    // $busca=$Cpo[cliente];
 }
 
 if($_REQUEST[Apellidop]<>$Cpo[apellidop] AND $busca=='NUEVO'){$lAg=true;}

 $Fecha=date("Y-m-d");
 
 
// $aPrg = array('Ninguno','Cliente frecuente','Diabetes','Chequeo medico','Otro');
$aPrg = array('Ninguno','Cliente frecuente','Apoyo a la salud','Chequeo medico','Empleado','Familiar','Medico','Especializado');

 require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onLoad="cFocus1()">

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

      echo "<td  width='10%'>";
      
         echo "<a href='clientesordN.php?Vta=$Vta'><img src='lib/regresa.jpg' border='0'></a><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";

      echo "</td>";
      
      echo "<td align='left'> $Gfont ";
      
      echo "<form name='form2' method='get' action='clientesordeN.php?Vta=$_REQUEST[Vta]'>";
      
           //if($lAg){echo $busca;}else{echo $Cpo[cliente];}

           echo "<div align='left'><font size='+1'>Cliente: $busca &nbsp; $Cpo[nombrec]</font></div>";
           
           echo "<p>Apellido pat.: ";
           echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop' onBlur=Mayusculas('Apellidop')>";
           //echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop' onBlur=Mayusculas('Apellidop')>";
           echo " Apellido mat.:";
           echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom' onBlur=Mayusculas('Apellidom')>";
           //echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom' onBlur=Mayusculas('Apellidom')>";
           echo " Nombre: ";
           echo " <input name='Nombre' type='text' size='10' value = '$Nombre' onBlur=Mayusculas('Nombre')>";
           //echo " <input name='Nombre' type='text' size='10' value = '$Nombre' onBlur=Mayusculas('Nombre')>";
           //echo " <input type='IMAGE' name='Lupa' src='images/lupa.gif' alt='Busca un paciente con estos datos' > ";

           echo "<input type='submit' name='Boton' value='Buscar'>";
           
           echo "<input type='hidden' name='orden' value='$orden'>";
           echo "<input type='hidden' name='pagina' value='$pagina'>";
           echo "<input type='hidden' name='busca' value='NUEVO'>";
           echo "<input type='hidden' name='Vta' value='$Vta'>";
           echo "</font></p>";

     echo "</form>";
    

    echo "<form name='form1' method='get' action='clientesordeN.php?Vta=$Vta' onSubmit='return Completo();'>";


        $Fecha2     = date("Y-m-d");
        $fecha_nac  = $Cpo[fechan];
        $dia        = substr($Fecha2, 8, 2);
        $mes        = substr($Fecha2, 5, 2);
        $anno       = substr($Fecha2, 0, 4);
        $dia_nac    = substr($fecha_nac, 8, 2);
        $mes_nac    = substr($fecha_nac, 5, 2);
        $anno_nac   = substr($fecha_nac, 0, 4);
        $Fechan     = $Cpo[fechan];
        
        if ($mes_nac > $mes) {
            $calc_edad = $anno - $anno_nac - 1;
        } else {
            if ($mes == $mes_nac AND $dia_nac > $dia) {
                $calc_edad = $anno - $anno_nac - 1;
            } else {
                $calc_edad = $anno - $anno_nac;
            }
        }

        if ($lAg) {

            $Clasificacion = "El mismo";
            $Sexo = "M";
            $Zona = 1;
            $Institucion = 1;

            if ($_REQUEST[Estado] == '') {
                $Estado = "Estado de Mexico";
            } else {
                $Estado = $_REQUEST[Estado];
                $Municipio = $_REQUEST[Municipio];
                $Colonia = $_REQUEST[Colonia];
            }
        } else {

            if ($Cpo[estado] == '') {
                $Estado = "Estado de Mexico";
            } else {
                $Estado = $Cpo[estado];
            }

            if ($Cpo[municipio] == '') {
                $Municipio = $_REQUEST[Municipio];
            } else {
                $Municipio = $Cpo[municipio];
            }

            if ($Cpo[colonia] == '') {
                $Colonia = $_REQUEST[Colonia];
            } else {
                $Colonia = $Cpo[colonia];
            }

            $Clasificacion = $Cpo[clasificacion];
            //$Colonia       = $Cpo[colonia];
            $Sexo = $Cpo[sexo];
            $Codigo = $Cpo[codigo];
            $Anos = $Fecha - $Cpo[fechan];
            $Institucion = $Cpo[institucion];
            $Zona = $Cpo[zona];
        }
        cTable('70%', '0');

        cTable('70%', '0');

        echo "<tr><td align='right' height='23'>$Gfont Estado: </td><td>";
        echo "<SELECT name='Estado'>";
        echo "<option value='Aguascalientes'>Aguascalientes</option>";
        echo "<option value='Baja California'>Baja California</option>";
        echo "<option value='Campeche'>Campeche</option>";
        echo "<option value='Chiapas'>Chiapas</option>";
        echo "<option value='Chihuahua'>Chihuahua</option>";
        echo "<option value='Coahuila'>Coahuila</option>";
        echo "<option value='Colima'>Colima</option>";
        echo "<option value='Distrito Federal'>Distrito Federal</option>";
        echo "<option value='Durango'>Durango</option>";
        echo "<option value='Guanajuato'>Guanajuato</option>";
        echo "<option value='Guerrero'>Guerrero</option>";
        echo "<option value='Hidalgo'>Hidalgo</option>";
        echo "<option value='Jalisco'>Jalisco</option>";
        echo "<option value='Estado de Mexico'>Estado de Mexico</option>";
        echo "<option value='Michoacan'>Michoacan</option>";
        echo "<option value='Morelos'>Morelos</option>";
        echo "<option value='Nayarit'>Nayarit</option>";
        echo "<option value='Nuevo Leon'>Nuevo Leon</option>";
        echo "<option value='Oaxaca'>Oaxaca</option>";
        echo "<option value='Puebla'>Puebla</option>";
        echo "<option value='Queretaro'>Queretaro</option>";
        echo "<option value='Quintana Roo'>Quintana Roo</option>";
        echo "<option value='San Luis Potosi'>San Luis Potosi</option>";
        echo "<option value='Sinaloa'>Sinaloa</option>";
        echo "<option value='Sonora'>Sonora</option>";
        echo "<option value='Tabasco'>Tabasco</option>";
        echo "<option value='Tlaxcala'>Tlaxcala</option>";
        echo "<option value='Veracruz'>Veracruz</option>";
        echo "<option selected value='$Estado'>$Estado</option>";
        echo "</select>";
        echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
        echo "</td></tr>";

        $MpioA = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");

        echo "<tr><td align='right' height='30'>$Gfont Municipio: </td><td>";
        echo "<SELECT name='Municipio'>";
        while ($Mpio = mysql_fetch_array($MpioA)) {
            echo "<option value='$Mpio[0]'>$Mpio[0]</option>";
            //echo "<option value='".utf8_encode($Mpio[0])."'>".utf8_encode($Mpio[0])."</option>";             
        }
        echo "<option selected value='$Municipio'>$Municipio</option>";
        echo "</select>";
        echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
        echo "</td></tr>";

        $ColA = mysql_query("SELECT colonia,codigo FROM estados WHERE municipio = '$Municipio' ORDER BY colonia");

        echo "<tr><td align='right' height='30'>$Gfont Colonia: </td><td>";
        echo "<SELECT name='Colonia'>";
        while ($Col = mysql_fetch_array($ColA)) {
            echo "<option value='$Col[0]'>$Col[0]</option>";
            if ($Col[0] == $Colonia) {
                $Codigosis = $Col[codigo];
            }
        }
        echo "<option selected value='$Colonia'>$Colonia</option>";
        echo "</select>";
        echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
        echo "</td></tr>";

        echo "<tr><td align='right' height='30'>$Gfont Codigo postal: &nbsp; </td><td>";
        echo "<input type='text' name='Codigo' value='$Codigo' size='5'>$Gfont ";
        echo "Segun sistema:&nbsp;";
        echo "<input type='text' name='Codigosis' value='$Codigosis' size='5'> &nbsp; ";
        echo "</td></tr>";

        //cInput("Codigo postal: ","Text","10","Codigo","right",$Codigo,"10",false,false,'');

        cInput("Calle y n&uacute;mero: ", "Text", "60", "Direccion", "right", $Cpo[direccion], "40", false, false, '');

        cInput("Telefono: ", "Text", "20", "Telefono", "right", $Cpo[telefono], "20", false, false, '');

        cInput('', 'text', '', '', '', '', '', false, true, '-------------------------------');


        echo "<tr><td align='right'>$Gfont Fecha alta: &nbsp; </td><td>";
        echo "<input type='text' name='Fecha' value='$Fecha' size='10'>$Gfont ";
        echo "Fecha nac.:&nbsp;";
        echo "<input type='text' name='Fechan' value='$Fechan' size='10'> &nbsp; ";
        echo "No.A&ntilde;os:&nbsp;";
        echo "<input type='text' name='Anos' value='$Anos' size='5'> &nbsp; Sexo: ";
        echo "<SELECT name='Sexo'>";
        echo "<option value='M'>M</option>";
        echo "<option value='F'>F</option>";
        echo "<option selected value='$Sexo'>$Sexo</option>";
        echo "</select>";

        echo "</td></tr>";
        //cInput("Fecha alta: ","Text","10","Fecha","right",$Fecha,"10",false,false,'');
        //cInput("Fecha Nac.: ","Text","10","Fechan","right",$Cpo[fechan],"10",false,false,'');
        //cInput("No.A&ntilde;os: ","Text","5","Anos","right",$Anos,"5",false,false,'');

        cInput("Credencial: ", "Text", "20", "Credencial", "right", $Cpo[credencial], "20", false, false, '');
        cInput("Titular: ", "Text", "20", "Titular", "right", $Cpo[titular], "20", false, false, '');
        cInput("Mail: ", "Text", "40", "Mail", "right", $Cpo[mail], "40", false, false, '');

        echo "<tr><td align='right'>$Gfont Clasificacion familiar: &nbsp;</td><td>";
        echo "<SELECT name='Clasificacion'>";
        echo "<option value='El mismo'>El mismo</option>";
        echo "<option value=Esposa>Esposa</option>";
        echo "<option value=Esposo>Esposo</option>";
        echo "<option value=1er.hijo>1er.hijo</option>";
        echo "<option value=2do.hijo>2do.hijo</option>";
        echo "<option value=3er.hijo>3er.hijo</option>";
        echo "<option value=4to.hijo>4o.hijo</option>";
        echo "<option value=5to.hijo>5o.hijo</option>";
        echo "<option value=Mamá>Mamá</option>";
        echo "<option value=Papá>Papá</option>";
        echo "<option value=Concubina>Concubina</option>";
        echo "<option value=Otro>Otro</option>";
        echo "<option selected value='$Clasificacion'>$Clasificacion</option>";
        echo "</select>";
        echo "</td></tr>";

        cInput("Afiliacion: ", "Text", "40", "Afiliacion", "right", $Cpo[afiliacion], "40", false, false, '');

        echo "<tr><td align='right'>$Gfont Expira: &nbsp;</td><td>";
        echo "<SELECT name='Expira'>";
        echo "<option value='S'>S</option>";
        echo "<option value='N'>N</option>";
        echo "<option selected value='$Cpo[expira]'>$Cpo[expira]</option>";
        echo "</select>$Gfont ";
        echo "Fecha de expiracion:&nbsp;";
        echo "<input type='text' name='Expiracion' value='$Cpo[expiracion]' size='10'> &nbsp; ";

        echo "</td></tr>";

        //cInput("Fecha expiracion: ","Text","10","Expiracion","right",$Cpo[expiracion],"10",false,false,'');

        cInput("Otro dato: ", "Text", "40", "Otro", "right", $Cpo[otro], "40", false, false, '');


        $ZnaA = mysql_query("SELECT zona,descripcion FROM zns ORDER BY zona");
        echo "<tr><td align='right'>$Gfont Zona: </td><td>";
        echo "<SELECT name='Zona'>";
        while ($Zna = mysql_fetch_array($ZnaA)) {
            echo "<option value='$Zna[0]'>$Zna[0] $Zna[1]</option>";
            if ($Zna[0] == $Zona) {
                $DispZ = $Zna[1];
            }
        }
        echo "<option selected value='$Zona'>$DispZ</option>";
        echo "</select>";
        echo "</td></tr>";

        $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
        echo "<tr><td align='right'>$Gfont Institucion: </td><td>";
        echo "<SELECT name='Institucion'>";
        while ($Ins = mysql_fetch_array($InsA)) {
            echo "<option value='$Ins[0]'>$Ins[0] $Ins[1]</option>";
            if ($Ins[0] == $Institucion) {
                $DispI = $Ins[1];
            }
        }
        echo "<option selected value='$Institucion'>$DispI</option>";
        echo "</select>";
        echo "</td></tr>";

        echo "<tr><td align='right'>$Gfont Programa d'salud: </td><td>";

        $nPrg = $Cpo[programa];

         echo "<SELECT name='Programa'>";
         echo "<option value='1'>1.Cliente frecuente</option>";
         echo "<option value='2'>2.Apoyo a la salud</option>";
         echo "<option value='3'>3.Chequeo medico</option>";
         echo "<option value='4'>4.Empleado</option>";
         echo "<option value='5'>5.Familiar</option>";
         echo "<option value='6'>6.Medico</option>";
         echo "<option value='7'>7.Especializado</option>";
         echo "<option value='0'>0.Ninguno</option>";
         echo "<option SELECTED value='$Cpo[programa]'>$aPrg[$nPrg]</option>";
         echo "</SELECT> <br> ";
        
        echo "</td></tr>";

        echo "<tr><td align='right' valign='bottom'>$Gfont Padecimientos:&nbsp;</td><td>";
        echo "<TEXTAREA NAME='Padecimiento' cols='45' rows='2'>$Cpo[padecimiento]</TEXTAREA>";
        echo "</td></tr>";
        echo "<tr><td align='right' valign='bottom'>$Gfont Observaciones:&nbsp;</td><td>";
        echo "<TEXTAREA NAME='Observaciones' cols='45' rows='2'>$Cpo[observaciones]</TEXTAREA>";
        echo "</td></tr>";
        echo "<tr><td align='right' valign='bottom'>$Gfont Referencia de la ubicacion:&nbsp;</td><td>";
        echo "<TEXTAREA NAME='Refubicacion' cols='45' rows='2'>$Cpo[refubicacion]</TEXTAREA>";
        echo "</td></tr>";

        cTableCie();

        echo "$Gfont <p align='center'><b>Usr.alta:</b> $Cpo[usr] <b>Fecha:</b> $Cpo[fecha] &nbsp;&nbsp;&nbsp; <b>Usr.ult.mod:</b> $Cpo[usrmod] &nbsp; <b>Fecha:</b> $Cpo[fecmod] </p>";

        echo "<input type='hidden' name='Apellidop'>";
        echo "<input type='hidden'name='Apellidom'>";
        echo "<input type='hidden' name='Nombre'>";
        echo "<input type='hidden' name='Vta' value='$Vta'>";
        echo Botones();

        mysql_close();
        ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
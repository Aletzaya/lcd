<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");
  date_default_timezone_set("America/Mexico_City");
  $Usr    = $check['uname'];
  
  $fechmod= date("Y-m-d H:i:s");

  $link   = conectarse();

  $tamPag = 15;

  $pagina = $_REQUEST[pagina];
  $busca  = $_REQUEST[busca];
  $op     = $_REQUEST[op];

  $Tabla  = "est";

  $Titulo = "Detalle de estudios [$busca]";

  if($op=='Sn'){

    $lUp=mysql_query("INSERT into ests (estudio,descripcion) VALUES ('$busca','$_REQUEST[Sinonimo]')",$link);
  }

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo
      
 //     if($op=='Sn'){

 //       $lUp=mysql_query("INSERT into ests (estudio,descripcion) VALUES ('$busca','$_REQUEST[Sinonimo]')",$link);
     
        $cSql="SELECT estudio,descripcion,objetivo,condiciones,tubocantidad,tiempoest,entord,entexp,enthos,enturg,equipo,muestras,estpropio,subdepto,contenido,comision,dobleinterpreta,modify,fechmod,agrego,fechalta FROM $Tabla WHERE (estudio= '$busca')";

//     }else{

        $DepA=mysql_query("SELECT departamento FROM depd WHERE subdepto='$_REQUEST[Subdepto]'");

        $Dep=mysql_fetch_array($DepA);

        if($busca=='NUEVO'){

           $lUp=mysql_query("SELECT estudio FROM  $Tabla WHERE estudio='$_REQUEST[Estudio]' limit 1");

           $Exi=mysql_fetch_array($lUp);

           if($Exi[estudio]==$_REQUEST[Estudio]){

              $Msj="La Clave del estudio($_REQUEST[Estudio]) ya existe! favor de verificar...";

              header("Location: estudiose.php?Msj=$Msj&pagina=$pagina");

           }else{

              $lUp = mysql_query("INSERT into est (estudio,descripcion,objetivo,condiciones,tubocantidad,tiempoest,
                     entord,entexp,enthos,enturg,equipo,tecnica,muestras,estpropio,subdepto,contenido,comision,
                     observaciones,proceso,depto,clavealt,respradiologia,dobleinterpreta,modify,fechmod,agrego,fechalta)
                     VALUES
                     ('$_REQUEST[Estudio]','$_REQUEST[Descripcion]',ltrim('$_REQUEST[Objetivo]'),ltrim('$_REQUEST[Condiciones]'),
                     '$_REQUEST[Tubocantidad]','$_REQUEST[Tiempoest]','$_REQUEST[Entord]','$_REQUEST[Entexp]','$_REQUEST[Enthos]',
                     '$_REQUEST[Enturg]','$_REQUEST[Equipo]','$_REQUEST[Tecnica]','$_REQUEST[Muestras]',
                     '$_REQUEST[Estpropio]','$_REQUEST[Subdepto]','$_REQUEST[Contenido]','$_REQUEST[Comision]',
                     '$_REQUEST[Observaciones]','$_REQUEST[Proceso]','$Dep[0]','$_REQUEST[Clavealt]','$_REQUEST[Respradiologia]'
					 ,'$_REQUEST[Dobleinterpreta]','$Usr','$fechmod','$Usr','$fechmod')");

			 Btc($Titulo,$_REQUEST[Estudio]);

             header("Location: estudios.php?busca=$_REQUEST[Estudio]&pagina=$pagina");

           }
 	    }else{

           $lUp = mysql_query("UPDATE est SET descripcion='$_REQUEST[Descripcion]',objetivo='$_REQUEST[Objetivo]',
                  condiciones='$_REQUEST[Condiciones]',tubocantidad='$_REQUEST[Tubocantidad]',
                  tiempoest='$_REQUEST[Tiempoest]',entord='$_REQUEST[Entord]',entexp='$_REQUEST[Entexp]',
                  enthos='$_REQUEST[Enthos]',enturg='$_REQUEST[Enturg]',equipo='$_REQUEST[Equipo]',
                  tecnica='$_REQUEST[Tecnica]',muestras='$_REQUEST[Muestras]',estpropio='$_REQUEST[Estpropio]',
                  subdepto='$_REQUEST[Subdepto]',contenido='$_REQUEST[Contenido]',comision='$_REQUEST[Comision]',
                  observaciones='$_REQUEST[Observaciones]',proceso='$_REQUEST[Proceso]',depto='$Dep[0]',
                  clavealt='$_REQUEST[Clavealt]',respradiologia='$_REQUEST[Respradiologia]',activo='$_REQUEST[Activo]',
				  dobleinterpreta='$_REQUEST[Dobleinterpreta]',modify='$Usr',fechmod='$fechmod'
                  WHERE estudio='$busca' limit 1");

		   Btc("Actualizacion de estudio:  $_REQUEST[Estudio]",$_REQUEST[Estudio]);

           header("Location: estudios.php?pagina=$pagina&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*");


        }
 //    }

  }elseif($_REQUEST[Boton] == Cancelar){
    header("Location: estudios.php?pagina=$pagina&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*");
  }

  $cSql = "SELECT estudio,descripcion,objetivo,condiciones,tubocantidad,tiempoest,entord,entexp,enthos,enturg,
          equipo,muestras,estpropio,subdepto,contenido,comision,observaciones,proceso,clavealt,respradiologia,
		  activo,dobleinterpreta,modify,fechmod,agrego,fechalta
          FROM $Tabla WHERE (estudio= '$busca')";

  $CpoA  = mysql_query($cSql);
  $Cpo   = mysql_fetch_array($CpoA);
  $lAg   = $Descripcion<>$Cpo[Descripcion];
  $Fecha = date("Y-m-d");

  $Niv    = $_COOKIE['LEVEL'];

 require ("config.php");

?>
<html>
<head>
<title><?php echo $Titulo;?></title>
<meta http-equiv="Pragma" content="no-cache" />
</head>
<body bgcolor="#FFFFFF" onLoad="cFocus()">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">

function AbreVentana(url){
   window.open(url,"upword","width=300,height=400,left=600,top=150,scrollbars=no,location=no,dependent=yes,resizable=no");
}

function Vt(url){
   window.open(url,"conformacion","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=750,height=500,left=20,top=20")
}

function cFocus(){
  document.form1.Estudio.focus();
}

function Subephp(url){
   window.open(url,"upphp","width=300,height=400,left=600,top=150,scrollbars=no,location=no,dependent=yes,resizable=no");
}


function Completo(){
var lRt;
lRt=true;
if(document.form2.Apellidom.value==""){lRt=false;}
if(document.form2.Descripcion.value==""){lRt=false;}
if(document.form2.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
document.form1.Apellidom.value=document.form2.Apellidom.value
document.form1.Descripcion.value=document.form2.Descripcion.value
document.form1.Nombre.value=document.form2.Nombre.value
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Estudio'){document.form1.Estudio.value=document.form1.Estudio.value.toUpperCase();
}if (cCampo=='Descripcion'){document.form1.Descripcion.value=document.form1.Descripcion.value.toUpperCase();
}if (cCampo=='Tubocantidad'){document.form1.Tubocantidad.value=document.form1.Tubocantidad.value.toUpperCase();
}if (cCampo=='Equipo'){document.form1.Equipo.value=document.form1.Equipo.value.toUpperCase();
}if (cCampo=='Clavealt'){document.form1.Clavealt.value=document.form1.Clavealt.value.toUpperCase();
}if (cCampo=='Sinonimo'){document.form2.Sinonimo.value=document.form2.Sinonimo.value.toUpperCase();}
}
</script>

<table width="100%" border="0">
<?php
    echo "<tr>";
      echo "<td  width='7%' rowspan='2'>";
         echo "<a href='estudios.php?&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'><img src='lib/regresa.jpg' border='0'></a>";
         echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
      echo "</td>";

      echo "<td> $Gfont ";

      echo "<form name='form1' method='get' action='estudiose.php'>";

      echo "<table width='80%' border='0' align='center'>";

      echo "<tr><td align='right'>$Gfont Estudio $Gfon</td><td align='left'><input type='text' name='Estudio' value='$Cpo[0]' maxlength='7' onBlur=Mayusculas('Estudio') size='6'></td></tr>";

      echo "<tr><td align='right'>$Gfont Clave alterna </td><td align='left'><input type='text' name='Clavealt' value='$Cpo[clavealt]' maxlength='20' onBlur=Mayusculas('Clavealt') size='20'></td></tr>";

      echo "<tr><td align='right'>$Gfont Descripcion $Gfon</td><td align='left'><input type='text' name='Descripcion' value='$Cpo[descripcion]' maxlength='110' onBlur=Mayusculas('Descripcion') size='110'></td></tr>";

      echo "<tr><td align='right'>$Gfont Proceso a realizar $Gfon</td><td align='left'>";

      echo "<SELECT name=Proceso>";
      echo "<option value='TOMA SANGUINEA'>TOMA SANGUINEA</option>";
      echo "<option value='RECOLECCION DE MUESTRA'>RECOLECCION DE MUESTRA</option>";
      echo "<option value='REALIZACION DE ESTUDIOS'>REALIZACION DE ESTUDIOS</option>";
      echo "<option value='TOMA DE MUESTRA CORPORAL'>TOMA DE MUESTRA CORPORAL</option>";
      echo "<option value=SERVICIO>'SERVICIO'</option>";
      echo "<option SELECTed>$Cpo[proceso]</option>";
      echo "</SELECT>";
      echo "</td></tr>";

      echo "<tr><td align='right'>$Gfont Equipo a usar $Gfon</td><td align='left'><input type='text' name='Equipo' value='$Cpo[10]' maxlength='40' onBlur=Mayusculas('Equipo') size='40'></td></tr>";

      echo "<tr><td align='right'>$Gfont Comision $Gfon</td><td align='left'><input type='text' name='Comision' value='$Cpo[15]' maxlength='10' size='10'></td></tr>";

      echo "<tr><td align='right'>$Gfont Departamento $Gfon</td><td align='left'>$Gfont";

      $DepA=mysql_query("SELECT dep.nombre FROM dep,depd WHERE depd.subdepto='$Cpo[subdepto]' and depd.departamento=dep.departamento and '$Cpo[subdepto]'<>'' ");
      $Dep=mysql_fetch_array($DepA);
      echo "<strong> $Dep[0] &nbsp;&nbsp;</strong> ";

      echo "$Gfon</td></tr>";

      echo "<tr><td align='right'>$Gfont Sub-departamento $Gfon</td><td align='left'>";

      //$cSub=mysql_query("SELECT subdepto FROM depd");
      $cSub=mysql_query("SELECT depd.subdepto,dep.nombre FROM dep,depd WHERE dep.departamento=depd.departamento");
      
      echo "<SELECT name='Subdepto'>";
      while ($dep=mysql_fetch_array($cSub)){
             echo "<option value='$dep[0]'>$dep[1]: $dep[0]</option>";
      }
      echo "<option SELECTed value='$Cpo[13]'>$Cpo[13]</option>";
      echo "</SELECT>";

      echo "</td></tr><tr>";

      echo "<td align='right'>$Gfont Activo </td><td>";

      echo "<select name='Activo'>";
      echo "<option value='Si'>Si</option>";
      echo "<option value='No'>No</option>";
      echo "<option selected value='$Cpo[activo]'>$Cpo[activo]</option>";
      echo "</select>";
      echo "</td></tr>";
      
      
      
      echo "</table><br>";

      $Pag1="informes/".$busca."-descripcion.php";  	//Material
      $Pag2="informes/".$busca."-pre.php";   //Condiciones
      $Pag3="informes/".$busca."-indicaciones.php";   //Manual
      $Pag4="informes/".$busca."-instructivo.php";   //Objetivo
	  $estud = strtolower($Cpo[estudio]);            
	  
      echo "<div align='center' >$Gfont<b> Conformacion del producto</b></font></div>";
      echo "<table width='80%' border='1' cellpadding='6' cellspacing='0' align='center'>";
      echo "<tr><td align='center' bgcolor='#EDF9FE'><a class='ord' href=javascript:Vt('$Pag1')>$Gfont Descripcion del producto &nbsp;</font></a></td>
	  <td align='center' bgcolor='#EDF9FE'><a class='ord' href=javascript:Vt('$Pag2')>$Gfont Cuestionario pre-analitico &nbsp;</font></a></td>
	  <td align='center' bgcolor='#EDF9FE'><a class='ord' href=javascript:Vt('$Pag3')>$Gfont Indicaciones del paciente&nbsp;</font></a></td>
	  <td align='center' bgcolor='#EDF9FE'><a class='ord' href=javascript:Vt('$Pag4')>$Gfont Instructivo &nbsp;</font></a></td> 
	  <td align='center' bgcolor='#EDF9FE'><a class='ord' href=javascript:Vt('informes/$estud.php')>$Gfont Pant. Resultado &nbsp;</font></a></td></tr>";
      echo "</table>";

      echo "<br><br>";

      echo "<table width='90%' border='0' align='center'>";

      echo "<tr><td align='right'>$Gfont Tiemp .real del estudio $Gfon</td><td align='left'><input type='text' name='Tiempoest' value='$Cpo[5]' maxlength='7' size='6'></td>";

      echo "<td align='right'>$Gfont No.proyecs.y/o muestras $Gfon</td><td><input type='text' name='Muestras' value='$Cpo[11]' maxlength='3' size='3'></td>";

      echo "<td align='right'>$Gfont Est/propio[S/N] $Gfon</td><td>";

      echo "<SELECT name='Estpropio'>";
      echo "<option value='S'>S</option>";
      echo "<option value='N'>N</option>";
      echo "<option SELECTed>$Cpo[12]</option>";
      echo "</SELECT>";
      echo "</td>";

      echo "</tr>";

      echo "<tr><td align='right'>&nbsp;</td><td>&nbsp;</td><td align='right'>$Gfont <b> Dias de entrega </b> $Gfon</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

      echo "<tr>";

      echo "<td align='right'>$Gfont Ordinaria $Gfon</td><td> ";

      $nCt=0;
      echo "<SELECT name=Entord>";
      while ($nCt<=30){
          echo "<option value=$nCt>$nCt</option>";
          $nCt++;
      }
      echo "<option SELECTed>$Cpo[6]</option>";
      echo "</SELECT>";

      echo "</td>";

      echo "<td align='right'>$Gfont Express $Gfon</td><td><input name='Entexp' type='text' size='6' value=$Cpo[7]></td>";

      echo "<td align='right'>$Gfont Hospital $Gfon</td><td><input name='Enthos' type='text' size='6' value=$Cpo[8]></td></tr>";

	  echo "<tr>";
	  
	  echo "<td align='right'>$Gfont Doble/Interpretac[S/N] $Gfon</td><td>";

      echo "<SELECT name='Dobleinterpreta'>";
      echo "<option value='S'>S</option>";
      echo "<option value='N'>N</option>";
      echo "<option SELECTed>$Cpo[dobleinterpreta]</option>";
      echo "</SELECT>";
      echo "</td>";


      echo "<td align='right'>$Gfont Urgencias $Gfon</td><td><input name='Enturg' type='text' size='6' value=$Cpo[9]></td><td>&nbsp;</td><td>&nbsp;</td></tr>";

      echo "</table><br><br>";

      echo "<table width='95%' border='0' align='center'>";

      echo "<tr><td>$Gfont Objetivo $Gfon</td><td><TEXTAREA NAME='Objetivo' cols='110' rows='5' >$Cpo[2]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Condiciones $Gfon</td><td><TEXTAREA NAME='Condiciones' cols='110' rows='5' >$Cpo[3]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Contenido $Gfon</td><td><TEXTAREA NAME='Contenido' cols='110' rows='5' >$Cpo[14]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Observaciones $Gfon</td><td><TEXTAREA NAME='Observaciones' cols='110' rows='5' >$Cpo[observaciones]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Posibles respuesta <br> &nbsp; para radiologia e <br> &nbsp; imagen</td><td><TEXTAREA NAME='Respradiologia' cols='110' rows='10' >$Cpo[respradiologia]</TEXTAREA></td></tr>";
          
      echo "</table>";
	echo "<input type='IMAGE' name='Imprimir' src='images/print.png' onClick='print()'>";
	  if($Usr=='nazario' or $Usr=='emilio' or $Usr=='Dolores' or $Usr=='SANDYM' or $Usr=='fabiola' or $Usr=='Gerardo' or $Usr=='Alejandro'){

      	echo Botones();

      }
	  echo "$Gfont <p align='center'><b>Usr.alta: </b> $Cpo[agrego] &nbsp;&nbsp; <b>Fecha Alta:</b> $Cpo[fechalta] &nbsp;&nbsp;&nbsp; <b>Usr.ult.mod:</b> $Cpo[modify] &nbsp;&nbsp; <b>Fecha Modif.:</b> $Cpo[fechmod] </p>";
      echo "</form></font><br>";

      echo "<p align='right'>$Gfont Pruebas <a class='pg' href=javascript:Subephp('uploadfuentes.php?Up=$Up')><img src='lib/up.png' alt='Subir archivo' border='0'></a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
      echo "Subir fuentes </font><a class='pg' href=javascript:AbreVentana('uploadphp.php?Up=$Up')><img src='lib/up.png' alt='Subir archivo' border='0'></a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";
      echo "<a class='pg' href=javascript:AbreVentana('uploadword.php?Up=$Up')>$Gfont Subir formato de WORD </font><img src='lib/up.png' alt='Subir archivo' border='0'></a>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p>";

      echo "<form name='form2' method='get' action='estudiose.php'>";
      echo "<table width='60%' border='1' align='center'>";
      echo "<tr><td bgcolor='CCCCFF'>$Gfont Elim $Gfon </td><td bgcolor='CCCCFF'>$Gfont Sinonimos de este estudio $Gfon</td></tr>";

      $cSql="SELECT descripcion FROM ests WHERE estudio='$busca'";
      $result=mysql_query($cSql);
      while ($row=mysql_fetch_array($result)){
             printf("<tr><td align='center'><a href='estudiose.php?busca=$busca&Sinonimo=$row[0]&pagina=$pagina&op=El'>%s</a></td><td>%s</td>","<img src='lib/deleon.png' alt='Edita reg' border='0'>","$Gfont $row[0] $Gfon");
      }
      mysql_free_result($result);

      echo "</table>";
      echo "<p align='center'>$Gfont Sinonimo: $Gfon ";
      echo "<input name='Sinonimo' type='text' id='Sinonimo' onBlur=Mayusculas(Sinonimo) size='40'> &nbsp; ";
      echo "<input type='submit' name='Submit' value='Agrega'>";
      echo " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p>";
      echo "<input type='hidden' name='orden' value=$orden>";
      echo "<input type='hidden' name='op' value='Sn'>";
      echo "<input type='hidden' name='pagina' value=$pagina>";
      echo "<input type='hidden' name='busca' value=$busca>";
      echo "</form>";
	?>

  </td>
  </tr>
</table>
</body>
</html>
<?
mysql_close();
?>
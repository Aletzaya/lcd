<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr=$check['uname'];

  require("lib/lib.php");

  $link		=	conectarse();

  $tamPag	=	15;

  $op		=	$_REQUEST[op];

  $busca	=	$_REQUEST[busca];

  $pagina	=	$_REQUEST[pagina];

  $Fecha  = date("Y-m-d H:i");

  $Tabla="inst";

  $Titulo="Edita Institucion";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

    if($busca=='NUEVO'){
        $lUp=mysql_query("INSERT INTO $Tabla
        (nombre,alias,direccion,localidad,municipio,referencia,rfc,director,subdirector,condiciones,envio,otro,status,codigo,
        telefono,fax,observaciones,servicio,administrativa,suplente,todo,mail,enviomail,lista,descuento,msjadministrativo,promotorasig,usralta,fechalta,encargado,contacto,telcontacto,mailcontacto,responsable)
        VALUES
        ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]','$_REQUEST[Referencia]',
        '$_REQUEST[Rfc]','$_REQUEST[Director]','$_REQUEST[Subdirector]','$_REQUEST[Condiciones]','$_REQUEST[Envio]',
        '$_REQUEST[Otro]','$_REQUEST[Status]','$_REQUEST[Codigo]','$_REQUEST[Telefono]','$_REQUEST[Fax]','$_REQUEST[Observaciones]',
        '$_REQUEST[Servicio]','$_REQUEST[Administrativa]','$_REQUEST[Suplente]','$_REQUEST[Todo]','$_REQUEST[Mail]','$_REQUEST[Enviomail]','$_REQUEST[Lista]',
        '$_REQUEST[Descuento]','$_REQUEST[Msjadministrativo]','$_REQUEST[Promotorasig]','$Usr','$Fecha','$_REQUEST[Encargado]','$_REQUEST[Contacto]','$_REQUEST[Telcontacto]','$_REQUEST[Mailcontacto]','$_REQUEST[Responsable]')",$link);

            $lUp3  = mysql_query("INSERT INTO loginst (fecha,usr,institucion,concepto) VALUES ('$Fecha','$Usr','$_REQUEST[busca]','Da de Alta Institucion')");
 	  }else{

        $datosmed  = "SELECT * FROM $Tabla WHERE institucion='$busca'";

        $datosA  = mysql_query($datosmed);
        $datos   = mysql_fetch_array($datosA);

        $campomod = ' ';

        if (strcasecmp($datos[observaciones], $_REQUEST[Observaciones]) !== 0){
            $campomod = ', Observaciones';
        }

        if(strcasecmp($datos[servicio], $_REQUEST[Servicio]) !== 0){
            $campomod .= ', Servicio';
        }

        if(strcasecmp($datos[msjadministrativo], $_REQUEST[Msjadministrativo]) !== 0){
            $campomod .= ', Mensaje Administrativo';
        }

        if(strcasecmp($datos[responsable], $_REQUEST[Responsable]) !== 0){
            $campomod .= ', Responsable de Cobranza';
        }

        if(strcasecmp($datos[administrativa], $_REQUEST[Administrativa]) !== 0){
            $campomod .= ', Caracteristicas Administrativas';
        }

        $lUp=mysql_query("UPDATE $Tabla SET nombre='$_REQUEST[Nombre]',alias='$_REQUEST[Alias]',direccion='$_REQUEST[Direccion]',
        localidad='$_REQUEST[Localidad]',municipio='$_REQUEST[Municipio]',referencia='$_REQUEST[Referencia]',rfc='$_REQUEST[Rfc]',
        director='$_REQUEST[Director]',subdirector='$_REQUEST[Subdirector]',condiciones='$_REQUEST[Condiciones]',envio='$_REQUEST[Envio]',
        otro='$_REQUEST[Otro]',status='$_REQUEST[Status]',codigo='$_REQUEST[Codigo]',telefono='$_REQUEST[Telefono]',
        fax='$_REQUEST[Fax]',observaciones='$_REQUEST[Observaciones]',servicio='$_REQUEST[Servicio]',administrativa='$_REQUEST[Administrativa]',
        suplente='$_REQUEST[Suplente]',todo='$_REQUEST[Todo]',mail='$_REQUEST[Mail]',enviomail='$_REQUEST[Enviomail]',lista='$_REQUEST[Lista]',descuento='$_REQUEST[Descuento]',
        msjadministrativo='$_REQUEST[Msjadministrativo]',promotorasig='$_REQUEST[Promotorasig]',usrmod='$Usr',fechamod='$Fecha',encargado='$_REQUEST[Encargado]',contacto='$_REQUEST[Contacto]',telcontacto='$_REQUEST[Telcontacto]',mailcontacto='$_REQUEST[Mailcontacto]',responsable='$_REQUEST[Responsable]'
        where institucion='$busca'",$link);

        $lUp3  = mysql_query("INSERT INTO loginst (fecha,usr,institucion,concepto) VALUES ('$Fecha','$Usr','$_REQUEST[busca]','Modifica datos de Institucion $campomod')");

 	  }

      header("Location: institu.php?orden=inst.institucion&Sort=Asc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*");
  }elseif($_REQUEST[Boton] == Cancelar){

      header("Location: institu.php?orden=inst.institucion&Sort=Asc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*");

  }

  $CpoA=mysql_query("select * from $Tabla where institucion='$busca'",$link);

  $Cpo=mysql_fetch_array($CpoA);

  $lAg=$busca<>$Cpo[institucion];

  require ("config.php");


?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body>

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}

function Completo(){
var lRt;
lRt=true;
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;  require ("config.php");

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
if (cCampo=='Encargado'){document.form1.Encargado.value=document.form1.Encargado.value.toUpperCase();}
if (cCampo=='Contacto'){document.form1.Contacto.value=document.form1.Contacto.value.toUpperCase();}
}
</script>

<?php

  echo "<table width='100%' border='0'>";
  echo "<tr>";
  echo "<td width='10%' align='center'>";
  echo "<p><a class='pg' href='institu.php?orden=inst.institucion&Sort=Asc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Fech2=&Fech3='><img src='lib/regresa.jpg' border='0'></a></p>";
  echo "<p>&nbsp;</p>";
  echo "<p>&nbsp;</p>";
  echo "</td>";
  echo "<td align='center'>$Gfont ";

  echo "<form name='form1' method='get' action='institue.php' onSubmit='return Completo();' >";

  cTable('70%','0');

  cInput('Institucion:','text','10','Institucion','right',$busca,'10',false,true,'');
  cInput('Nombre:','text','70','Nombre','right',$Cpo[nombre],'70',false,false,'');
  cInput('Alias:','text','20','Alias','right',$Cpo[alias],'20',false,false,'');
  cInput('Direccion:','text','40','Direccion','right',$Cpo[direccion],'40',false,false,'');
  cInput('Colonia:','text','20','Localidad','right',$Cpo[localidad],'40',false,false,'');
  cInput('Municipio:','text','20','Municipio','right',$Cpo[municipio],'30',false,false,'');
  cInput('Referencia del lugar:','text','40','Referencia','right',$Cpo[referencia],'50',false,false,'');
  cInput('Codigo postal:','text','10','Codigo','right',$Cpo[codigo],'10',false,false,'');
  cInput('Rfc:','text','20','Rfc','right',$Cpo[rfco],'20',false,false,'');
  cInput('Telefono:','text','20','Telefono','right',$Cpo[telefono],'20',false,false,'');
  cInput('Fax:','text','20','Fax','right',$Cpo[fax],'20',false,false,'');

  echo "<tr><td align='right'>$Gfont <b>Lista de precio:</b> &nbsp; </td><td>";
  echo "<SELECT name='Lista'>";
  echo "<option value=1>1</option>";
	echo "<option value=2>2</option>";
	echo "<option value=3>3</option>";
	echo "<option value=4>4</option>";
	echo "<option value=5>5</option>";
  echo "<option value=6>6</option>";
	echo "<option value=7>7</option>";
	echo "<option value=8>8</option>";
	echo "<option value=9>9</option>";
	echo "<option value=10>10</option>";
	echo "<option value=11>11</option>";
  echo "<option value=12>12</option>";
  echo "<option value=13>13</option>";
  echo "<option value=14>14</option>";
  echo "<option value=15>15</option>";
  echo "<option value=16>16</option>";        
	echo "<option value=17>17</option>";
	echo "<option value=18>18</option>";
	echo "<option value=19>19</option>";
	echo "<option value=20>20</option>";	
	echo "<option value=21>21</option>";
	echo "<option value=22>22</option>";
	echo "<option value=23>23</option>";
  echo "<option selected value='$Cpo[lista]'>$Cpo[lista]</option>";
  echo "</SELECT>";
  echo "</td></tr>";

  cInput('Mail:','text','50','Mail','right',$Cpo[mail],'50',false,false,'');

  echo "<tr><td align='right'>$Gfont Envio de resultados por Mail: </td><td>$Gfont ";
  echo "<select name='Enviomail'>";
  echo "<option value='Si'>Si</option>";
  echo "<option value='No'>No</option>";
  echo "<option selected>$Cpo[enviomail]</option>";
  echo "</select>";
  echo "</td></tr>";

  echo "<tr><td align='right'>$Gfont Todos los estudios: &nbsp; </td><td>";
  echo "<select name='Todo'>";
  echo "<option value='Si'>Si</option>";
  echo "<option value='No'>No</option>";
  echo "<option selected value='$Cpo[todo]'>$Cpo[todo]</option>";
  echo "</select>";
  echo "</td></tr>";

  echo "<tr><td align='right'>$Gfont Conds. de pago: &nbsp; </td><td>";
  echo "<select name='Condiciones'>";
  echo "<option value=CONTADO>CONTADO</option>";
  echo "<option value=CREDITO>CREDITO</option>";
  echo "<option selected value='$Cpo[condiciones]'>$Cpo[condiciones]</option>";
  echo "</select>";
  echo "</td></tr>";

  cInput('Envio de resultados:','text','20','Envio','right',$Cpo[envio],'20',false,false,'');

  echo "<tr><td align='right'>$Gfont Status: &nbsp; </td><td>";
  echo "<select name='Status'>";
  echo "<option value=ACTIVO>ACTIVO</option>";
  echo "<option value=INACTIVO>INACTIVO</option>";
  echo "<option selected value='$Cpo[status]'>$Cpo[status]</option>";
  echo "</select>";
  echo "</td></tr>";
		
  cInput('Otros dato:','text','20','Otro','right',$Cpo[otro],'20',false,false,'');
  cInput('Director:','text','40','Director','right',$Cpo[director],'45',false,false,'');
  cInput('Sub-director:','text','40','Subdirector','right',$Cpo[subdirector],'40',false,false,'');
  cInput('Suplente:','text','40','Suplente','right',$Cpo[suplente],'45',false,false,'');
  cInput('Descuento Institucional:','text','5','Descuento','right',$Cpo[descuento],'5',false,false,'');
		
	echo "<tr><td align='right'>$Gfont Promotor Asig: &nbsp; </td><td>$Gfont ";
	echo "<select name='Promotorasig'>";
	echo "<option value='Promotor_A'>Promotor_A</option>";
	echo "<option value='Promotor_B'>Promotor_B</option>";
	echo "<option value='Promotor_C'>Promotor_C</option>"; 
	echo "<option value='Promotor_D'>Promotor_D</option>";
	echo "<option value='Promotor_E'>Promotor_E</option>";
	echo "<option value='Promotor_F'>Promotor_F</option>";
	echo "<option value='Base'>Base</option>";
	echo "<option selected>$Cpo[promotorasig]</option>";
	echo "</select>";
	echo "</td></tr>";

  cInput('Encargado de Institucion:','text','30','Encargado','right',$Cpo[encargado],'30',false,false,'');
  cInput('Contacto:','text','50','Contacto','right',$Cpo[contacto],'50',false,false,'');
  cInput('Telefono Contacto:','text','30','Telcontacto','right',$Cpo[telcontacto],'30',false,false,'');
  cInput('Mail Contacto:','text','50','Mailcontacto','right',$Cpo[mailcontacto],'50',false,false,'');

  cTableCie();

  echo "<div><strong>Responsable de cobranza</strong></div>";
  echo "<TEXTAREA NAME='Responsable' cols='70' rows='5'>$Cpo[responsable]</TEXTAREA>";
  echo "<div><strong>Observaciones</strong></div>";
  echo "<TEXTAREA NAME='Observaciones' cols='70' rows='5'>$Cpo[observaciones]</TEXTAREA>";
  echo "<div><strong>Caracteristicas de servicio</strong></div>";
  echo "<TEXTAREA NAME='Servicio' cols='70' rows='5'>$Cpo[servicio]</TEXTAREA>";
  echo "<div><strong>Caracteristicas Administrativas</strong></div>";
  echo "<TEXTAREA NAME='Administrativa' cols='70' rows='5'>$Cpo[administrativa]</TEXTAREA>";
  echo "<div><strong>Mensaje Administrativo</strong></div>";
  echo "<TEXTAREA NAME='Msjadministrativo' cols='70' rows='5'>$Cpo[msjadministrativo]</TEXTAREA>";

  echo Botones4();

  echo "<table width='100%' border='0' align='center'>";

  echo "<tr><td align='center'>$Gfont <b>Usr.alta: </b> $Cpo[usralta] &nbsp;&nbsp; <b>Fecha Alta:</b> $Cpo[fechalta] &nbsp;&nbsp;&nbsp; <b>Usr.ult.mod:</b> $Cpo[usrmod] &nbsp;&nbsp; <b>Fecha Modif.:</b> $Cpo[fechamod]</td></tr><tr><td></td></tr><tr><td align='center'><a href=javascript:wingral('logmodinst.php?busca=$busca')><font size='1'> *** Modificaciones ***</a>  </font></td></tr>";

  echo "$Gfont <tr><td></td></tr><tr><td align='center'><a href=javascript:wingral('institupdf.php?busca=$busca')> <img src='images/print.gif' alt='pdf' border='0'></a></td></tr>";
  echo "</table>";

  mysql_close();

  echo "</form>";
  echo "</td>";
  echo "</tr>";
  echo "</table>";
  echo "</body>";
  echo "</html>";
?>
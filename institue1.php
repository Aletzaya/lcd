<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr=$check['uname'];

  require("lib/kaplib.php");

  $link		=	conectarse();

  $tamPag	=	15;

  $op		=	$_REQUEST[op];

  $busca	=	$_REQUEST[busca];

  $pagina	=	$_REQUEST[pagina];

  $Tabla="inst";

  $Titulo="Edita Institucion";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=='NUEVO'){
        $lUp=mysql_query("INSERT INTO $Tabla
        (nombre,alias,direccion,localidad,municipio,referencia,rfc,director,subdirector,condiciones,envio,otro,status,codigo,telefono,fax,observaciones,servicio,administrativa,suplente,todo,mail,lista)
        VALUES
        ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]','$_REQUEST[Referencia]','$_REQUEST[Rfc]','$_REQUEST[Director]','$_REQUEST[Subdirector]','$_REQUEST[Condiciones]','$_REQUEST[Envio]',
        '$_REQUEST[Otro]','$_REQUEST[Status]','$_REQUEST[Codigo]','$_REQUEST[Telefono]','$_REQUEST[Fax]','$_REQUEST[Observaciones]','$_REQUEST[Servicio]','$_REQUEST[Administrativa]','$_REQUEST[Suplente]','$_REQUEST[Todo]','$_REQUEST[Mail]','$_REQUEST[Lista]')",$link);
 	  }else{
        $lUp=mysql_query("UPDATE $Tabla SET nombre='$_REQUEST[Nombre]',alias='$_REQUEST[Alias]',direccion='$_REQUEST[Direccion]',localidad='$_REQUEST[Localidad]',municipio='$_REQUEST[Municipio]',referencia='$_REQUEST[Referencia]',rfc='$_REQUEST[Rfc]',
        director='$_REQUEST[Director]',subdirector='$_REQUEST[Subdirector]',condiciones='$_REQUEST[Condiciones]',envio='$_REQUEST[Envio]',otro='$_REQUEST[Otro]',status='$_REQUEST[Status]',codigo='$_REQUEST[Codigo]',telefono='$_REQUEST[Telefono]',
        fax='$_REQUEST[Fax]',observaciones='$_REQUEST[Observaciones]',servicio='$_REQUEST[Servicio]',administrativa='$_REQUEST[Administrativa]',suplente='$_REQUEST[Suplente]',todo='$_REQUEST[Todo]',mail='$_REQUEST[Mail]',lista='$_REQUEST[Lista]' where institucion='$busca'",$link);
 	  }

      header("Location: institu.php?pagina=$pagina");

  }elseif($_REQUEST[Boton] == Cancelar){

      header("Location: institu.php?pagina=$pagina");

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
}
</script>

<?php

echo "<table width='100%' border='0'>";
    echo "<tr>";
    echo "<td width='10%' align='center'>";
    echo "<p><a class='pg' href='institu.php'><img src='lib/regresa.jpg' border='0'></a></p>";
    echo "<p>&nbsp;</p>";
    echo "<p>&nbsp;</p>";
    echo "</td>";
    echo "<td align='center'>$Gfont ";

    echo "<form name='form1' method='get' action='institue.php' onSubmit='return Completo();' >";

        //echo "$Gfont Institucion: ";
        //if($lAg){echo $busca;}else{echo $Cpo[institucion];}

        cTable('70%','0');

        cInput('Institucion:','text','10','Institucion','right',$busca,'10',false,true,'');
        cInput('Nombre:','text','40','Nombre','right',$Cpo[nombre],'40',false,false,'');
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
        echo "<option selected value='$Cpo[lista]'>$Cpo[lista]</option>";
        echo "</SELECT>";
        echo "</td></tr>";

        cInput('Mail:','text','40','Mail','right',$Cpo[mail],'40',false,false,'');

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
        cInput('Status:','text','10','Status','right',$Cpo[status],'20',false,false,'');
        cInput('Otros dato:','text','20','Otro','right',$Cpo[otro],'20',false,false,'');
        cInput('Director:','text','40','Director','right',$Cpo[director],'45',false,false,'');
        cInput('Sub-director:','text','40','Subdirector','right',$Cpo[subdirector],'40',false,false,'');
        cInput('Suplente:','text','40','Suplente','right',$Cpo[suplente],'45',false,false,'');

       cTableCie();


		  echo "<div><strong>Observaciones</strong></div>";
        echo "<TEXTAREA NAME='Observaciones' cols='70' rows='5'>$Cpo[observaciones]</TEXTAREA>";
		  echo "<div><strong>Caracteristicas de servicio</strong></div>";
        echo "<TEXTAREA NAME='Servicio' cols='70' rows='5'>$Cpo[servicio]</TEXTAREA>";
		  echo "<div><strong>Caracteristicas Administrativas</strong></div>";
        echo "<TEXTAREA NAME='Administrativa' cols='70' rows='5'>$Cpo[administrativa]</TEXTAREA>";

        echo Botones();

        mysql_close();


	echo "</form>";
   echo "</td>";
   echo "</tr>";
echo "</table>";
echo "</body>";
echo "</html>";
?>
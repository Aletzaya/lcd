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

  $Tabla      = "medi";

  $Titulo     = "Detalle por medico interno";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=='NUEVO'){

         $lUp = mysql_query("INSERT INTO medi (nombre,profesion,cedula,sexo)
                VALUES
                ('$_REQUEST[Nombre]','$_REQUEST[Profesion]','$_REQUEST[Cedula]','$_REQUEST[Sexo]')");

        $busca = mysql_insert_id();

       }else{

         $lUp = mysql_query("UPDATE medi SET nombre='$_REQUEST[Nombre]',profesion='$_REQUEST[Profesion]',
                cedula='$_REQUEST[Cedula]',sexo='$_REQUEST[Sexo]'
                WHERE id='$busca' limit 1");
       }


      if($_REQUEST[Boton] == Aceptar){
         header("Location: medicosi.php");
      }


  }elseif($_REQUEST[Boton] == Cancelar){

         header("Location: medicosi.php");

  }else{

      $cSql="SELECT * FROM medi WHERE id='$busca'";

  }


 $CpoA = mysql_query($cSql);

 $Cpo   = mysql_fetch_array($CpoA);

 $Fecha=date("Y-m-d");

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

<?php

echo '<table width="100%" border="0">';

    echo "<tr>";

      echo "<td  width='10%' valign='center'>";

         echo "<a href='medicosi.php'><img src='lib/regresa.jpg' border='0'></a>";

      echo "</td>";

      echo "<td align='center'> $Gfont ";

      echo "<form name='form1' method='get' action='medicosie.php' onSubmit='return Completo();'>";

             cTable('70%','0');
			 $Sexo= $Cpo[sexo];
             cInput("Nombre: ","Text","30","Nombre","right",$Cpo[nombre],"40",false,false,'');
             cInput("Profesion: ","Text","20","Profesion","right",$Cpo[profesion],"20",false,false,'');
             cInput("Cedula: ","Text","20","Cedula","right",$Cpo[cedula],"20",false,false,'');
             echo "<tr><td align='right'>Sexo:</td><td>";
             echo "<SELECT name='Sexo'>";
             echo "<option value='M'>M</option>";
             echo "<option value='F'>F</option>";
             echo "<option selected value='$Sexo'>$Sexo</option>";
             echo "</select>";
             echo "</td></tr>";
			 
			 $Depto= $Cpo[depto];
			 echo "<tr>";
			 echo "<td align='right'>Depto:</td><td>";
			 echo "<select name='Depto>";
			 $cDep=mysql_query("SELECT departamento,nombre FROM dep order by departamento");
			 while ($Dep=mysql_fetch_array($cDep)){
		 		echo "<option value=$Dep[nombre]>".ucwords(strtolower($Dep[nombre]))."</option>";
		 	 }
			 echo "<option selected value='$Depto'>$Depto</option>";
             echo "</select>";
             echo "</td></tr>";

             cTableCie();

             echo Botones();

             mysql_close();
    echo "</form>";
    echo "</td>";
    echo "</tr>";
echo "</table>";
echo "</body>";
echo "</html>";

?>
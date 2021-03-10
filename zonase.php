<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $tamPag=15;

  $op=$_REQUEST[op];

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Tabla="zns";

  $Titulo="Detalle de zonas ($busca)";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=='NUEVO'){
        $lUp=mysql_query("insert into $Tabla (descripcion,poblacion) VALUES ('$_REQUEST[Descripcion]','$_REQUEST[Poblacion]')",$link);
 	  }else{
         $lUp=mysql_query("update $Tabla SET descripcion='$_REQUEST[Descripcion]',poblacion='$_REQUEST[Poblacion]' where zona='$busca' limit 1",$link);
 	  }

      header("Location: zonas.php?pagina=$pagina");

  }elseif($_REQUEST[Boton] == Cancelar){

      header("Location: zonas.php?pagina=$pagina");

  }

  $CpoA=mysql_query("select * from $Tabla where zona='$busca'",$link);
  $Cpo=mysql_fetch_array($CpoA);

  $lAg=$Zona<>$Cpo[zona];

  require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onload="cFocus()">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">
function cFocus(){
  document.form1.Descripcion.focus();
}

function Completo(){
var lRt;
lRt=true;
if(document.form1.Descripcion.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
  if (cCampo=='Descripcion'){document.form1.Descripcion.value=document.form1.Descripcion.value.toUpperCase();}
  if (cCampo=='Poblacion'){document.form1.Poblacion.value=document.form1.Poblacion.value.toUpperCase();}
  if (cCampo=='Observacion'){document.form1.Observacion.value=document.form1.Observacion.value.toUpperCase();}
}

</script>

<?php

echo "<table width='100%' border='0'>";
    echo "<tr>";
    echo "<td width='15%' align='center'>";
       echo "<a class='pg' href='zonas.php'><img src='lib/regresa.jpg' border='0'></a>";
    echo "</td>";
    echo "<td>$Gfont ";

    echo "<p>&nbsp;</p>";

    echo "<form name='form1' method='get' action='zonase.php' onSubmit='return Completo();' >";
        echo "Zona: ";
        if($lAg){echo $busca;}else{echo $Cpo[zona];}
        echo "<p>Descripcion........";
        echo "<input type='text' name='Descripcion'  value ='$Cpo[descripcion]' size='40' onBlur='Mayusculas(Descripcion)'>";
        echo "</p>";
        echo "<p>Poblacion...........";
        echo "<input name='Poblacion' type='text' onBlur='Mayusculas(Poblacion)' value ='$Cpo[poblacion]' size='5'>";
        echo "</p>";

           echo Botones();

            mysql_close();
    echo "</form>";
  echo "</td>";
  echo "</tr>";
echo "</table>";
echo "</body>";
echo "</html>";
?>
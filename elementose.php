<?php

  session_start();

  $Estudio=$_SESSION['cVarVal'];

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $tamPag=15;

  $op=$_REQUEST[op];

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  //$Estudio=$_REQUEST[Estudio];

  $Id=$_REQUEST[Id];

  $Tabla="ele";

  $Titulo="Elemento del estudio $Estudio";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=='NUEVO'){

        $lUpA=mysql_query("select elemento from  $Tabla where estudio='$Estudio' and id='$Id'",$link);

        if($Exi=mysql_fetch_array($lUpA)){

           $Msj="El elemento $Elemento ya existe! favor de verificar...";

           header("Location: elementose.php?Msj=$Msj&pagina=$pagina&busca='NUEVO'");

       }else{

          $lUp=mysql_query("insert into $Tabla (id,tipo,longitud,estudio,decimales,descripcion) VALUES ('$Id','$_REQUEST[Tipo]','$_REQUEST[Longitud]','$Estudio','$_REQUEST[Decimales]','$_REQUEST[Descripcion]')",$link);

          //$id=mysql_insert_id();

          header("Location: elementos.php");

       }

 	 }else{

         $lUp=mysql_query("update $Tabla SET tipo='$_REQUEST[Tipo]',longitud='$_REQUEST[Longitud]',decimales='$_REQUEST[Decimales]',descripcion='$_REQUEST[Descripcion]' where estudio='$Estudio' and id='$Id' limit 1",$link);

         header("Location: elementos.php");

     }
  }

  $cSql="select * from $Tabla where estudio='$Estudio' and id = '$Id'";

  $CpoA=mysql_query($cSql,$link);

  $Cpo=mysql_fetch_array($CpoA);

  $lAg=$Pregunta<>$Cpo[pregunta];

  $Fecha=date("Y-m-d");

  require ("config.php");


?>

<html>
<head>
<title><?php echo $Titulo;?></title>
<meta http-equiv="Pragma" content="no-cache" />
</head>
<body bgcolor="#FFFFFF" onload="cFocus()">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Pregunta.focus();

}

function Completo(){

var lRt;

lRt=true;

if(document.form1.pregunta.value==""){lRt=false;}

if(!lRt){

    alert("Faltan datos por llenar, favor de verificar");

    return false;

}

return true;

}



function Mayusculas(cCampo){

if (cCampo=='Pregunta'){document.form1.Pregunta.value=document.form1.Pregunta.value.toUpperCase();}

}

</script>

<table width="100%" border="0">


  <tr>

   <td align='center' width="15%">
       <a class='pg' href="elementos.php">Regresar</a>
   </td>

   <td>
   <?php

      echo "<p>&nbsp;</p>";

      echo "<p>&nbsp;</p>";

      if($Cpo[tipo]=="c"){$Tt="C a r a c t e r";}elseif($Cpo[tipo]=="d"){$Tt="F e c h a";}elseif($Cpo[tipo]=="l"){$Tt="L o g i c o";}elseif($Cpo[tipo]=="n"){$Tt="N u m e r i c o";}else{$Tt="T e x t o";}

      echo "<form name='form1' method='get' action='elementose.php'>";


		echo "<table width='80%' border='0'>";

          echo "<tr><td align='right'>$Gfont Secuencia $Gfon </td><td><input type='text' name='Id' value ='$Id' maxlength='3' size='4'></td></tr>";

          echo "<tr><td align='right'>$Gfont Descripcion $Gfon </td><td><input type='text' name='Descripcion' value ='$Cpo[descripcion]' maxlength='20' size='25'></td></tr>";

          echo "<tr><td align='right'>$Gfont Tipo $Gfon </td><td>";

            echo "<select name='Tipo'>";

            echo "<option value='c'>1.- Caracter</option>";

            echo "<option value='n'>2.- Numerico</option>";

            echo "<option value='d'>3.- Fecha</option>";

            echo "<option value='l'>4.- Logico[Positivo/Negativo]</option>";

            echo "<option value='t'>5.- Texto</option>";

            echo "<option selected>$Tt</option>";

            echo "</select>";

         echo "</td></tr>";

         echo "<tr><td align='right'>$Gfont Longitud $Gfon </td><td><input type='text' name='Longitud' value ='$Cpo[longitud]' maxlength='5' size='5'></td></tr>";

         echo "<tr><td align='right'>$Gfont Decimales $Gfon </td><td><input type='text' name='Decimales' value ='$Cpo[decimales]' maxlength='5' size='5'></td></tr>";

         echo "</table>";

         echo "<p>&nbsp;</p>";

         echo Botones();

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
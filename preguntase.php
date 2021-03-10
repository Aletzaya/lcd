<?php

  session_start();

  require("lib/kaplib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $op=$_REQUEST[op];

  $Tabla="cue";

  $Pregunta=$_REQUEST[Pregunta];

  $Tipo=$_REQUEST[Tipo];

  $Titulo="Detalle de preguntas pre-analiticas ($busca)";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=='NUEVO'){
         $lUpA=mysql_query("select pregunta from  $Tabla WHERE pregunta='$Pregunta'");
         if($Exi=mysql_fetch_array($lUpA)){
            $Msj="La pregunta($Pregunta) ya existe! favor de verificar...";
            header("Location: preguntase.php?Msj=$Msj&pagina=$pagina&busca='NUEVO'");
         }else{
            $lUp=mysql_query("insert into $Tabla (pregunta,tipo,modalidad) VALUES ('$Pregunta','$Tipo','$_REQUEST[Modalidad]')",$link);
            //$id=mysql_insert_id();
         }
 	 }else{
         $lUp = mysql_query("update $Tabla SET pregunta='$Pregunta',tipo='$Tipo',modalidad='$_REQUEST[Modalidad]' 
                WHERE id='$busca' limit 1");
     }

     if($_REQUEST[Boton] == Aceptar){
        header("Location: preguntas.php?pagina=$pagina");
     }

  }elseif($_REQUEST[Boton] == Cancelar){
     header("Location: preguntas.php?pagina=$pagina");
  }

  $cSql="select * from $Tabla WHERE (id= '$busca')";

  $CpoA=mysql_query($cSql,$link);

  $Cpo=mysql_fetch_array($CpoA);

  $lAg=$Pregunta<>$Cpo[pregunta];

  $Fecha=date("Y-m-d");

  require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>
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

<body>

<?php headymenu($Titulo,0); ?>

<hr noshade style="color:3366FF;height:2px">

<table width="100%" border="0">

    <?php
    echo "<tr>";
      echo "<td  width='10%' rowspan='2'>";

         echo "<a class='pg' href='preguntas.php?pagina=$pagina'><img src='lib/regresa.jpg' border='0'></a>";

      echo "</td>";

      echo "<td> $Gfont ";

      echo "<p>&nbsp;</p><p>&nbsp;</p>";

      echo "<form name='form1' method='get' action='preguntase.php'>";

          echo "<table width='70%' align='center' border='0'>";

           echo "<tr><td align='right'>$Gfont Clave $Gfon </td><td>$Gfont $busca $Gfon </td></tr>";

           echo "<tr><td align='right'>$Gfont Modalidad </td><td>";
             echo " &nbsp; <select name='Modalidad'>";
             echo "<option value='General'>General</option>";
             echo "<option value='Porestudio'>Porestudio</option>";
             echo "<option selected>$Cpo[modalidad]</option>";
             echo "</select>";
          echo "</td></tr>";
           
           
           echo "<tr><td align='right'>$Gfont Pregunta $Gfon </td><td>$Gfont ";
           echo "<input type='text' name='Pregunta' value ='$Cpo[pregunta]' maxlength='70' onBlur=Mayusculas('Pregunta') size='50'> $Gfon ";
           echo "</td></tr>";

           echo "<tr><td align='right'>$Gfont Tipo $Gfon </td><td>";
             echo " &nbsp; <select name='Tipo'>";
             echo "<option value='Si/No'>1.- Si/No</option>";
             echo "<option value='Fecha'>2.- Fecha</option>";
             echo "<option value='Texto'>3.- Texto</option>";
             echo "<option value='Memo' >4.- Memo</option>";
             echo "<option selected>$Cpo[tipo]</option>";
             echo "</select>";
          echo "</td></tr>";

          echo "</table>";

          echo "<p>&nbsp;</p>";

          echo Botones();

    echo "</form>";

    mysql_close();

    ?>

  </td>

  </tr>

  </table>

  </body>

  </html>



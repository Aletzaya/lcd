<?php
  session_start();

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

  $Tabla="hr";

  $Titulo="Edita Horario";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=="NUEVO"){

          if($_REQUEST[Descripcion]<>""){
             $lUp=mysql_query("insert into $Tabla (descripcion) VALUES ('$_REQUEST[Descripcion]')",$link);
             $lBd=true;
          }else{
             $Msj="Lo siendo! como minimo debes poner la descripcion";
          }
      }else{

          $lUp=mysql_query("update $Tabla set descripcion='$_REQUEST[Descripcion]' where id='$busca' limit 1",$link);
          $lBd=true;

      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: horarios.php?pagina=$pagina");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
     header("Location: horarios.php?pagina=$pagina");
  }

  $CpoA=mysql_query("SELECT * from hr where id='$busca'",$link);
  $Cpo=mysql_fetch_array($CpoA);

  require ("config.php");

?>
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF" onload="cFocus()">

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
}
</script>
<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='20%' rowspan='2'><a class='pg' href='horarios.php?pagina=$pagina'>$Gfont Regresar</font></a></td>";

    echo "<td>";

       echo "<p>&nbsp;</p>";

       echo "<form name='form1' method='get' action='horariose.php' onSubmit='return Completo();'>";

             echo $Gfont;
             echo "<p> &nbsp; &nbsp; &nbsp; Horario..........: &nbsp; $busca</p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Descripcion....: &nbsp; ";
             echo "<input type='text' name='Descripcion' value='$Cpo[descripcion]' onBlur=Mayusculas('Descripcion') size='40'></p>";

             echo Botones();

            ?>

            </font>

      </form>

      </td>

      <td width="10%" rowspan="2">&nbsp;</td>

  </tr>

</table>
</body>
</html>
<?
mysql_close();
?>
<?php

  session_start();

  require("lib/lib.php");

  $link   = conectarse();

  $pagina = $_REQUEST[pagina];

  $busca  = $_REQUEST[busca];

  $Tabla  = "mql";

  $Msj    = "";

  $Titulo ="Detalle de maquiladores";

  $lAg    = $busca=='NUEVO';

  $Fecha  = date("Y-m-d");

  $cSql   = "select * from mql where id='$busca'";

  $CpoA   = mysql_query($cSql,$link);

  $Cpo    = mysql_fetch_array($CpoA);

  $lBd=false;

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  if($busca=="NUEVO"){

          if($_REQUEST[Nombre]<>""){

             $lUp=mysql_query("insert into $Tabla (nombre,direccion,colonia,ciudad,telefono,rfc,codigo,observaciones,alias) VALUES
             ('$_REQUEST[Nombre]','$_REQUEST[Direccion]','$_REQUEST[Colonia]','$_REQUEST[Ciudad]','$_REQUEST[Telefono]','$_REQUEST[Rfc]','$_REQUEST[Codigo]','$_REQUEST[Observaciones]','$_REQUEST[Alias]')",$link);

             $lBd=true;
          }else{
             $Msj="Lo siendo! como minimo debes poner el nombre ";
          }
      }else{
          $lUp=mysql_query("update $Tabla set nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',colonia='$_REQUEST[Colonia]',ciudad='$_REQUEST[Ciudad]',telefono='$_REQUEST[Telefono]',rfc='$_REQUEST[Rfc]',codigo='$_REQUEST[Codigo]',
          observaciones='$_REQUEST[Observaciones]',alias='$_REQUEST[Alias]' where id='$busca' limit 1",$link);
          $lBd=true;
      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: maquiladores.php?busca=$busca&Sort=Asc&orden=id");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      header("Location: maquiladores.php?busca=$busca&Sort=Asc&orden=id");
  }


  require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF"  onload="cFocus()">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
if (cCampo=='Direccion'){document.form1.Direccion.value=document.form1.Direccion.value.toUpperCase();}
if (cCampo=='Colonia'){document.form1.Colonia.value=document.form1.Colonia.value.toUpperCase();}
if (cCampo=='Ciudad'){document.form1.Ciudad.value=document.form1.Ciudad.value.toUpperCase();}
if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
}

function cFocus(){

  document.form1.Nombre.focus();

}
</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='10%' align='center'>";

        echo "<a href='maquiladores.php?busca=$busca&Sort=Asc&orden=id'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td>";

    echo "<td>";

       echo "<form name='form1' method='get' action='maquiladorese.php' onSubmit='return ValCampos();'>";

             echo $Gfont;
             echo "<p> &nbsp; &nbsp; &nbsp; Cuenta: &nbsp; $busca &nbsp; &nbsp; &nbsp; Nombre............: &nbsp; ";
             echo "<input type='text' name='Nombre' value='$Cpo[nombre]' onBlur=Mayusculas('Nombre') size='40'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Alias................: &nbsp; ";
             echo "<input type='text' name='Alias' value='$Cpo[alias]' size='10'></p>";
             echo "</p><p> &nbsp; &nbsp; &nbsp; Direccion..........: &nbsp; ";
             echo "<input type='text' name='Direccion' value='$Cpo[direccion]' onBlur=Mayusculas('Direccion') size='40'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Colonia............: &nbsp; ";
             echo "<input type='text' name='Colonia' value='$Cpo[colonia]' onBlur=Mayusculas('Colonia') size='25'>";
             echo "&nbsp; &nbsp; Cod.Postal:";
             echo "<input type='text' name='Codigo' value='$Cpo[codigo]' onBlur=Mayusculas('Codigo') size='7'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Ciudad.............: &nbsp; ";
             echo "<input type='text' name='Ciudad' value='$Cpo[ciudad]' onBlur=Mayusculas('Ciudad') size='25'>";
             echo " &nbsp; R.f.c.: ";
             echo "<input type='text' name='Rfc' value='$Cpo[rfc]' onBlur=Mayusculas('Rfc') size='17'></p>";
             echo " <p> &nbsp; &nbsp; &nbsp; Telefono...........: &nbsp; ";
             echo " <input type='text' name='Telefono' value='$Cpo[telefono]' onBlur=Mayusculas('Telefono') size='12'></p>";
             echo " <p> &nbsp; &nbsp; &nbsp; Contacto/observs:";
             echo "  &nbsp; <TEXTAREA NAME='Observaciones' cols='50' rows='1' >$Cpo[observaciones]</TEXTAREA></p>";
             echo Botones();

            ?>

            </font>

      </form>

      </td>

  </tr>

</table>

<?php

   CierraWin();

   mysql_close();

?>

</body>

</html>
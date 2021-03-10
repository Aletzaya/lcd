<?php

  session_start();

  require("lib/lib.php");

  $link   = conectarse();

  $pagina = $_REQUEST[pagina];

  $busca  = $_REQUEST[busca];

  $Tabla  = "noconformidad";

  $Msj    = "";

  $Titulo ="Detalle de no conformidades";

  $lAg    = $busca=='NUEVO';

  $Fecha  = date("Y-m-d");

  $cSql   = "select * from noconformidad where id='$busca'";

  $CpoA   = mysql_query($cSql,$link);

  $Cpo    = mysql_fetch_array($CpoA);

  $lBd=false;

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  if($busca=="NUEVO"){

          if($_REQUEST[concepto]<>""){

             $lUp=mysql_query("insert into $Tabla (concepto,observaciones) VALUES
             ('$_REQUEST[concepto]','$_REQUEST[Observaciones]')",$link);

             $lBd=true;
          }else{
             $Msj="Lo siendo! como minimo debes poner el nombre ";
          }
      }else{
          $lUp=mysql_query("update $Tabla set concepto='$_REQUEST[concepto]',observaciones='$_REQUEST[Observaciones]' where id='$busca' limit 1",$link);
          $lBd=true;
      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: noconformidades.php?busca=$busca&Sort=Asc&orden=id");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      header("Location: noconformidades.php?busca=$busca&Sort=Asc&orden=id");
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
if (cCampo=='concepto'){document.form1.concepto.value=document.form1.concepto.value.toUpperCase();}
if (cCampo=='Alias'){document.form1.Alias.value=document.form1.Alias.value.toUpperCase();}
if (cCampo=='Marca'){document.form1.Marca.value=document.form1.Marca.value.toUpperCase();}
if (cCampo=='Modelo'){document.form1.Modelo.value=document.form1.Modelo.value.toUpperCase();}
if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
}

function cFocus(){

  document.form1.concepto.focus();

}
</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='10%' align='center'>";

        echo "<a href='noconformidades.php?busca=$busca&Sort=Asc&orden=id'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td>";

    echo "<td>";

       echo "<form name='form1' method='get' action='noconformidadese.php' onSubmit='return ValCampos();'>";

             echo $Gfont;
             echo "<p> &nbsp; &nbsp; &nbsp; Cuenta: &nbsp; $busca &nbsp; &nbsp; &nbsp; Concepto............: &nbsp; ";
             echo "<input type='text' name='concepto' value='$Cpo[concepto]' onBlur=Mayusculas('concepto') size='80'></p>";
             echo " <p> &nbsp; &nbsp; &nbsp; Observaciones:";
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
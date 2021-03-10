<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link   = conectarse();

  $pagina = $_REQUEST[pagina];

  $busca  = $_REQUEST[busca];

  $Tabla  = "prv";

  $Msj    = "";

  $Usr=$check['uname'];

  $Fecha  = date("Y-m-d H:i");

  $Titulo ="Detalle de proveedores";

  $lAg    = $busca=='NUEVO';

  $lBd=false;

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

          $lUp=mysql_query("update $Tabla set nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',colonia='$_REQUEST[Colonia]',ciudad='$_REQUEST[Ciudad]',telefono='$_REQUEST[Telefono]',rfc='$_REQUEST[Rfc]',codigo='$_REQUEST[Codigo]',
          nota='$_REQUEST[Nota]',dias='$_REQUEST[Dias]',alias='$_REQUEST[Alias]',usrmod='$Usr',fechamod='$Fecha',respprv='$_REQUEST[respprv]' where id='$busca' limit 1",$link);

          $lUp3  = mysql_query("INSERT INTO logmodprv (fecha,usr,proveedor,concepto) VALUES ('$Fecha','$Usr','$_REQUEST[busca]','Modifica datos de Proveedor')");

          $lBd=true;

  }


  $cSql   = "select * from prv where id='$busca'";

  $CpoA   = mysql_query($cSql,$link);

  $Cpo    = mysql_fetch_array($CpoA);

  require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF"  onload="cFocus()">

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

echo "<table width='80%' border='0' align='center'>";

  echo "<tr>";

    echo "<td width='10%' align='center'>";

        echo "<a href='proveedores.php?pagina=$pagina'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td>";

    echo "<td>";

       echo "<form name='form1' method='get' action='proveedoresedet.php' onSubmit='return ValCampos();'>";

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
             echo " <p> &nbsp; &nbsp; &nbsp; Telefonos.........: &nbsp; ";
             echo " <TEXTAREA NAME='Telefono' cols='50' rows='2' >$Cpo[telefono]</TEXTAREA></p>";
             echo "</select>";
             echo " &nbsp; &nbsp; &nbsp; Dias de credito...: &nbsp; ";
             echo "<input type='text' name='Dias' value='$Cpo[dias]' size='10'></p>";
             echo " <p> &nbsp; &nbsp; &nbsp; Contacto/observs:";
             echo "  &nbsp; <TEXTAREA NAME='Nota' cols='50' rows='1' >$Cpo[nota]</TEXTAREA></p>";

             echo "<p> Resp. del Proveedor: &nbsp; ";
             echo "<input type='text' name='respprv' value='$Cpo[respprv]' onBlur=Mayusculas('Nombre') size='40'></p>";            // echo Botones();
  echo "</td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td>";
  echo "</td>";
  echo "<td align='center'>";

  echo Botones4();

  echo "<table width='100%' border='0' align='center'>";

  echo "<tr><td align='center'>$Gfont <b>Usr.alta: </b> $Cpo[usralta] &nbsp;&nbsp; <b>Fecha Alta:</b> $Cpo[fechalta] &nbsp;&nbsp;&nbsp; <b>Usr.ult.mod:</b> $Cpo[usrmod] &nbsp;&nbsp; <b>Fecha Modif.:</b> $Cpo[fechamod]</td></tr><tr><td></td></tr><tr><td align='center'><a href=javascript:wingral('logmodprv.php?busca=$busca')><font size='1'> *** Modificaciones ***</a>  </font></td></tr>";

  echo "$Gfont <tr><td></td></tr><tr><td align='center'><a href=javascript:wingral('proveedorespdf.php?busca=$busca')> <img src='images/print.gif' alt='pdf' border='0'></a></td></tr>";
  echo "</table>";

   mysql_close();

  echo "</form>";
  echo "</td>";
  echo "</tr>";
  echo "</table>";
  echo "</body>";
  echo "</html>";
?>
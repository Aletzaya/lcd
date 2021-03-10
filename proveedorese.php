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

  //$lAg    = $busca=='NUEVO';

  $cSql   = "select * from prv where id='$busca'";

  $CpoA   = mysql_query($cSql,$link);

  $Cpo    = mysql_fetch_array($CpoA);

  $lBd=false;

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  if($busca=='NUEVO'){

          if($_REQUEST[Nombre]<>''){

             $lUp=mysql_query("insert into $Tabla (nombre,direccion,colonia,ciudad,telefono,rfc,codigo,nota,dias,alias,usralta,fechalta,respprv,mail,status,depto) VALUES
             ('$_REQUEST[Nombre]','$_REQUEST[Direccion]','$_REQUEST[Colonia]','$_REQUEST[Ciudad]','$_REQUEST[Telefono]','$_REQUEST[Rfc]','$_REQUEST[Codigo]','$_REQUEST[Nota]','$_REQUEST[Dias]','$_REQUEST[Alias]','$Usr','$Fecha','$_REQUEST[respprv]','$_REQUEST[mail]','$_REQUEST[status]','$_REQUEST[depto]')",$link);

             $lBd=true;

              $lUp3  = mysql_query("INSERT INTO logmodprv (fecha,usr,proveedor,concepto) VALUES ('$Fecha','$Usr','$_REQUEST[busca]','Da de Alta Proveedor')");

          }else{
             $Msj="Lo siendo! como minimo debes poner el nombre ";
          }
      }else{
          $lUp=mysql_query("update $Tabla set nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',colonia='$_REQUEST[Colonia]',ciudad='$_REQUEST[Ciudad]',telefono='$_REQUEST[Telefono]',rfc='$_REQUEST[Rfc]',codigo='$_REQUEST[Codigo]',
          nota='$_REQUEST[Nota]',dias='$_REQUEST[Dias]',alias='$_REQUEST[Alias]',usrmod='$Usr',fechamod='$Fecha',respprv='$_REQUEST[respprv]',mail='$_REQUEST[mail]',status='$_REQUEST[status]',depto='$_REQUEST[depto]' where id='$busca' limit 1",$link);

          $lUp3  = mysql_query("INSERT INTO logmodprv (fecha,usr,proveedor,concepto) VALUES ('$Fecha','$Usr','$_REQUEST[busca]','Modifica datos de Proveedor')");

          $lBd=true;
      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: proveedores.php?pagina=$pagina");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      header("Location: proveedores.php?pagina=$pagina");
  }

  $lAg   = $busca<>$Cpo[id];

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

echo "<br /><br />";
echo "<table width='95%' border='0' align='center'>";

  echo "<tr>";

    echo "<td width='5%' align='center'>";

        echo "<a href='proveedores.php?pagina=$pagina'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td>";

    echo "<td>";

       echo "<form name='form1' method='get' action='proveedorese.php' onSubmit='return ValCampos();'>";
            
            echo "<table width='95%' border='0' align='center'>";

            echo "<tr><td align='right'>$Gfont Cuenta: &nbsp; </td><td align='left'>$Gfont $busca </td><td align='right'>$Gfont Nombre: &nbsp; </td>";
            echo "<td align='left'>$Gfont <input type='text' name='Nombre' value='$Cpo[nombre]' onBlur=Mayusculas('Nombre') size='40'></td></tr>";

            echo "<tr><td align='right'>$Gfont Alias: &nbsp; </td>";
            echo "<td align='left'>$Gfont <input type='text' name='Alias' value='$Cpo[alias]' size='10'></td>";

             echo "<td align='right'>$Gfont E-mail: &nbsp; </td>";
             echo "<td align='left'>$Gfont <input type='text' name='mail' value='$Cpo[mail]' size='60'></td>";

             echo "<tr><td align='right'>$Gfont Direccion: &nbsp; </td>";
             echo "<td align='left'>$Gfont <input type='text' name='Direccion' value='$Cpo[direccion]' onBlur=Mayusculas('Direccion') size='40'></td></tr>";

            echo "<tr><td align='right'>$Gfont Colonia: &nbsp; </td>";
             echo "<td align='left'>$Gfont <input type='text' name='Colonia' value='$Cpo[colonia]' onBlur=Mayusculas('Colonia') size='25'></td>";
             echo "<td align='right'>$Gfont Cod.Postal: &nbsp; </td>";
             echo "<td align='left'>$Gfont <input type='text' name='Codigo' value='$Cpo[codigo]' onBlur=Mayusculas('Codigo') size='7'></td></tr>";

             echo "<tr><td align='right'>$Gfont Ciudad: &nbsp; </td>";
             echo "<td align='left'>$Gfont <input type='text' name='Ciudad' value='$Cpo[ciudad]' onBlur=Mayusculas('Ciudad') size='25'> </td>";
             echo "<td align='right'>$Gfont R.F.C.: </td>";
             echo "<td align='left'>$Gfont <input type='text' name='Rfc' value='$Cpo[rfc]' onBlur=Mayusculas('Rfc') size='17'> </td></tr>";

             echo "<tr><td align='right'>$Gfont Telefonos: &nbsp; </td>";
             echo "<td align='left' colspan='3'>$Gfont <TEXTAREA NAME='Telefono' cols='80' rows='2' >$Cpo[telefono]</TEXTAREA></td></tr>";

             echo "<tr><td align='right'>$Gfont  Dias de credito: &nbsp; </td>";
             echo "<td align='left'>$Gfont <input type='text' name='Dias' value='$Cpo[dias]' size='10'></td></tr>";

             echo "<tr><td align='right'>$Gfont Contacto/observs:: &nbsp; </td>";
             echo "<td align='left'>$Gfont <TEXTAREA NAME='Nota' cols='50' rows='1' >$Cpo[nota]</TEXTAREA></td></tr>";

             echo "<tr><td align='right'>$Gfont Resp. del Proveedor: &nbsp; </td>";
             echo "<td align='left'>$Gfont <input type='text' name='respprv' value='$Cpo[respprv]' onBlur=Mayusculas('Nombre') size='40'></td></tr>";  

            echo "<tr><td align='right'>$Gfont Status: &nbsp; </td>";
            echo "<td align='left'>$Gfont <select name='status'>";
            echo "<option value='Activo'>Activo</option>";
            echo "<option value='Inactivo'>Inactivo</option>";
            echo "<option selected value=$Cpo[status]>$Cpo[status]</option>";
            echo "</select>";
            echo "</td></tr>";

            echo "<tr><td align='right'>$Gfont Departamento: &nbsp; </td>";
            echo "<td align='left'>$Gfont <select name='depto'>";
            echo "<option value='Insumos'>Insumos</option>";
            echo "<option value='Reactivo'>Reactivo</option>";
            echo "<option value='Papeleria'>Papeleria</option>";
            echo "<option value='Computacion'>Computacion</option>";
            echo "<option selected value=$Cpo[depto]>$Cpo[depto]</option>";
            echo "</select>";
            echo "</td></tr>";

            echo "</table>";

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
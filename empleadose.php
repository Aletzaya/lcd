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

  $Tabla="emp";

  $Titulo="Detalle de empleados";

  $nivel_acceso=10; // Nivel de acceso para esta página.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
     header ("Location: $redir?error_login=5");
     exit;
  }


  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=="NUEVO"){

             $lUp = mysql_query("INSERT INTO emp (nombre,direccion,colonia,municipio,telefono,credencial,horario,inst) 
                    VALUES
                    ('$_REQUEST[Nombre]','$_REQUEST[Direccion]','$_REQUEST[Colonia]','$_REQUEST[Municipio]',
                    '$_REQUEST[Telefono]','$_REQUEST[Credencial1]','$_REQUEST[Horario]','$_REQUEST[Inst]')");

             $lBd=true;
      }else{

          $lUp = mysql_query("UPDATE $Tabla 
                 SET nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',colonia='$_REQUEST[Colonia]',
                 municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',credencial='$_REQUEST[Credencial1]',
                 horario='$_REQUEST[Horario]',inst='$_REQUEST[Inst]' 
                 WHERE id='$busca' limit 1");

          $lBd = true;

      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: empleados.php?pagina=$pagina");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
     header("Location: empleados.php?pagina=$pagina");
  }


  require ("config.php");

  $CpoA=mysql_query("SELECT * from emp where id='$busca'",$link);
  $Cpo=mysql_fetch_array($CpoA);

?>
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">
function Completo(){
var lRt;
lRt=true;
if(document.form1.Credencial1.value != document.form1.Credencial2.value){lRt=false;}
if(!lRt){
    alert("No coincide el numero de credencial! favor de verificar");
    return false;
}
return true;
}

</script>

<table width="100%" border="0">

  <tr>

    <td width="20%" rowspan="2">&nbsp;</td>

    <td>

       <form name="form1" method="get" action="empleadose.php" onSubmit="return Completo();" >

            <?php

             echo $Gfont;
             echo "<p> &nbsp; &nbsp; &nbsp; Cuenta...........: &nbsp; $busca";
             echo "<p> &nbsp; &nbsp; &nbsp; Nombre..........: &nbsp; ";
             echo "<input type='text' name='Nombre' value='$Cpo[nombre]' onBlur=Mayusculas('Nombre') size='40'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Direccion........: &nbsp; ";
             echo "<input type='text' name='Direccion' value='$Cpo[direccion]' onBlur=Mayusculas('Direccion') size='40'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Colonia..........: &nbsp; ";
             echo "<input type='text' name='Colonia' value='$Cpo[colonia]' onBlur=Mayusculas('Colonia') size='25'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Municipio.......: &nbsp; ";
             echo "<input type='text' name='Municipio' value='$Cpo[municipio]' onBlur=Mayusculas('Municipio') size='25'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Telefono........: &nbsp; ";
             echo "<input type='text' name='Telefono' value='$Cpo[telefono]' onBlur=Mayusculas('Telefono') size='20'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; No.Credencial..: &nbsp; ";
             echo "<input type='password' name='Credencial1' value='$Cpo[credencial]' onBlur=Mayusculas('Credencial') size='10'>";
             echo " &nbsp; &nbsp; Re escribir password <input type='password' name='Credencial2' value='$Cpo[credencial]' onBlur=Mayusculas('Credencial') size='10'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Institucion......: &nbsp; ";
             echo "<select name='Inst'>";
             $InstA=mysql_query("select institucion,alias from inst ORDER BY institucion");
             while ($Ins=mysql_fetch_array($InstA)){
                   echo "<option value=$Ins[institucion]>$Ins[institucion] $Ins[alias]</option>";
                   if($Cpo[inst]==$Ins[institucion]){$cDes=$Ins[alias];}
             }
             echo "<option selected value=$Cpo[inst]>$Cpo[inst] $cDes</option>";
             echo "</select></p>";

             echo "<p> &nbsp; &nbsp; &nbsp; Horario...........: &nbsp; ";
             echo "<select name='Horario'>";
             $HrA=mysql_query("SELECT * FROM hr ORDER BY id");
             while ($Hr=mysql_fetch_array($HrA)){
                   echo "<option value=$Hr[id]>$Hr[id] $Hr[descripcion]</option>";
                   if($Cpo[horario]==$Hr[id]){$cDes=$Hr[descripcion];}
             }
             echo "<option selected value=$Cpo[horario]>$Cpo[horario] $cDes</option>";
             echo "</select></p>";


             echo Botones();

            ?>

            </font>

      </form>

      </td>

  </tr>

</table>
</body>
</html>
<?
mysql_close();
?>
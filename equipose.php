<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

   require("lib/lib.php");

  $link   = conectarse();

  $pagina = $_REQUEST[pagina];

  $busca  = $_REQUEST[busca];

  $Tabla  = "eqp";

  $Msj    = "";

  $Titulo ="Detalle de equipos";

  $lAg    = $busca=='NUEVO';

  $Fecha  = date("Y-m-d H:i:s");

  $Usr    = $check['uname'];

  $cSql   = "select * from eqp where id='$busca'";

  $CpoA   = mysql_query($cSql,$link);

  $Cpo    = mysql_fetch_array($CpoA);

  $lBd=false;

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  if($busca=="NUEVO"){

          if($_REQUEST[Nombre]<>""){

             $lUp=mysql_query("insert into $Tabla (nombre,alias,marca,modelo,observaciones,caracteristicas) VALUES
             ('$_REQUEST[Nombre]','$_REQUEST[Alias]','$_REQUEST[Marca]','$_REQUEST[Modelo]','$_REQUEST[Observaciones]','$_REQUEST[Caracteristicas]')",$link);

             $lBd=true;
          }else{
             $Msj="Lo siendo! como minimo debes poner el nombre ";
          }
      }else{
          $lUp=mysql_query("update $Tabla set nombre='$_REQUEST[Nombre]',alias='$_REQUEST[Alias]',marca='$_REQUEST[Marca]',modelo='$_REQUEST[Modelo]',observaciones='$_REQUEST[Observaciones]',caracteristicas='$_REQUEST[Caracteristicas]' where id='$busca' limit 1",$link);
          $lBd=true;
      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: equipos.php?busca=$busca&Sort=Asc&orden=id");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      header("Location: equipos.php?busca=$busca&Sort=Asc&orden=id");
  }

  if($_REQUEST[op] == Guardar){ 

    $lUp  = mysql_query("INSERT INTO regeq (id_eq,fecha,observaciones,usr) VALUES
    ('$_REQUEST[busca]','$Fecha','$_REQUEST[Obs]','$Usr')");

  }

  if($_REQUEST[op] == Guardar_res){ 

    $lUp  = mysql_query("INSERT INTO resregeq (id_reg,fecha,observaciones,usr) VALUES
    ('$_REQUEST[idres]','$Fecha','$_REQUEST[Obs2]','$Usr')");

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
if (cCampo=='Alias'){document.form1.Alias.value=document.form1.Alias.value.toUpperCase();}
if (cCampo=='Marca'){document.form1.Marca.value=document.form1.Marca.value.toUpperCase();}
if (cCampo=='Modelo'){document.form1.Modelo.value=document.form1.Modelo.value.toUpperCase();}
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

        echo "<a href='equipos.php?busca=$busca&Sort=Asc&orden=id'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td>";

    echo "<td>";

       echo "<form name='form1' method='get' action='equipose.php' onSubmit='return ValCampos();'>";

             echo $Gfont;
             echo "<p> &nbsp; &nbsp; &nbsp; Cuenta: &nbsp; $busca </p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Nombre..........: &nbsp; ";
             echo "<input type='text' name='Nombre' value='$Cpo[nombre]' onBlur=Mayusculas('Nombre') size='40'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Alias..............: &nbsp; ";
             echo "<input type='text' name='Alias' value='$Cpo[alias]' onBlur=Mayusculas('Alias') size='20'></p>";
             echo "</p><p> &nbsp; &nbsp; &nbsp; Marca............: &nbsp; ";
             echo "<input type='text' name='Marca' value='$Cpo[marca]' onBlur=Mayusculas('Marca') size='20'></p>";
             echo "<p> &nbsp; &nbsp; &nbsp; Modelo...........: &nbsp; ";
             echo "<input type='text' name='Modelo' value='$Cpo[modelo]' onBlur=Mayusculas('Modelo') size='20'>";
            echo " <p> &nbsp; &nbsp; &nbsp; Caracteristicas:";
             echo "  &nbsp; <TEXTAREA NAME='Caracteristicas' cols='70' rows='2' >$Cpo[caracteristicas]</TEXTAREA></p>";
             echo " <p> &nbsp; &nbsp; &nbsp; Observaciones:";
             echo "  &nbsp; <TEXTAREA NAME='Observaciones' cols='70' rows='2' >$Cpo[observaciones]</TEXTAREA></p>";
             echo Botones3();


              echo "<table width='70%' border='0' align='center'>";

              echo "<tr><td align='center'><font size='2'><b> * * *  B I T A C O R A * * * </b></font></td></tr>";

              echo "</table>";

             echo "<hr>";

              if($_REQUEST[Boton] == Agrega){ 

                  echo "<table width='70%' border='0' align='center'>";

                  echo "<tr><td align='center'><input type='submit' name='op' value='Guardar'> &nbsp; ";

                  echo " &nbsp; <input type='submit' name='op' value='Cancela'></td></tr>";

              }else{

                  if($_REQUEST[op] == Responder){ 

                    echo "<table width='70%' border='0' align='center'>";

                    echo "<tr><td align='center'></td></tr>";

                  }else{

                    echo "<table width='70%' border='0' align='center'>";

                    echo "<tr><td align='center'><input type='submit' name='Boton' value='Agrega'></td></tr>";

                 }



              }


             if($_REQUEST[Boton] == Agrega){ 

                echo "<table width='90%' border='0' align='center'>";

                echo "<tr><td align='right' width='20%'>$Gfont <b>Reg. de Evento: </b></td><td><TEXTAREA NAME='Obs' cols='110' rows='2' >$mql[observaciones]</TEXTAREA></td></tr>";

                echo "</table>";

              }

              if($_REQUEST[op] == Responder){ 

                  echo "<table width='70%' border='0' align='center'>";

                  echo "<input type='hidden' name='idres' value='$_REQUEST[idres]'>";

                  echo "<tr><td align='center'><input type='submit' name='op' value='Guardar_res'> &nbsp; ";

                  echo " &nbsp; <input type='submit' name='op' value='cancela'></td></tr>";

              }

              if($_REQUEST[op] == Responder){ 

                echo "<table width='90%' border='0' align='center'>";

                echo "<tr><td align='right' width='20%'>$Gfont <b>Respuesta: </b></td><td><TEXTAREA NAME='Obs2' cols='110' rows='2' >$mql2[observaciones]</TEXTAREA></td></tr>";

                echo "</table>";

              }

              $cSql2="(SELECT * FROM regeq WHERE id_eq= '$busca' order by fecha DESC)";

              $UpA=mysql_query($cSql2,$link);

              echo "<table width='90%' border='0' align='left'><tr bgcolor='#124558'>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Fecha</font></td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Registro</font></td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Usuario</font></td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Responder </font></td></tr>";

              while ($mql=mysql_fetch_array($UpA)){
                  if( ($nRng % 2) > 0 ){$Fdo=$Gfdogrid;}else{$Fdo=$Gfdogrid;}    //El resto de la division;

                  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                  echo "<td align='center'>$Gfont <font color='#000'>$mql[fecha]</font></td>";
                  echo "<td align='left'>$Gfont <font color='#000'>$mql[observaciones]</font></td>";
                  echo "<td align='center'>$Gfont <font color='#000'>$mql[usr]</font></td>";
                  echo "<td align='center'>$Gfont <font color='Red'><a href=equipose.php?busca=$busca&op=Responder&idres=$mql[id]>Responder</font></td></tr>";

                  $cSql3="(SELECT * FROM resregeq WHERE id_reg= '$mql[id]' order by fecha DESC)";

                  $UpB=mysql_query($cSql3,$link);

                  while ($mql2=mysql_fetch_array($UpB)){

                        echo "<tr bgcolor='#abccec' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#abccec';>";

                        echo "<td align='right'>$Gfont <font color='#000' size='1'>$mql2[fecha]</font></td>";
                        echo "<td align='left'>$Gfont <font color='#000' size='1'>$mql2[observaciones]</font></td>";
                        echo "<td align='right'>$Gfont <font color='#000' size='1'>$mql2[usr]</font></td>";
                        echo "<td align='center'>$Gfont <font color='red' size='1'><b>-</b></font></td></tr>";
                  }

                  $nRng++;    

              }

              echo "</table>";

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
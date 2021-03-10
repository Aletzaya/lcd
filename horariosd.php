<?php

  session_start();

  require ("config.php");

  require("lib/kaplib.php");

  require("lib/filtro.php");

  $link=conectarse();

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Titulo="DETALLE DE HORARIOS";

  $cFuncion="";                 //Variabla para la funciones estadistico de suma,media,...

  $OrdenDef="hrd.dia";            //Orden de la tabla por default

  $tamPag=15;

  $op=$_REQUEST[op];

  $Dia=$_REQUEST[Dia];

  $Tabla = "hrd";

  if($op=='ag'){				//Agrega producto por producto

     $HrA=mysql_query("select dia from hrd where id='$busca' and dia='$_REQUEST[Dia]'",$link);
     $Hr=mysql_fetch_array($HrA);

     if($Hr[dia]==''){

        $lUp=mysql_query("INSERT INTO `hrd` ( `id` , `dia` , `entradai` , `entradaf` , `salidai` , `salidaf` )
        VALUES ('$busca','$Dia','$_REQUEST[Entradai]','$_REQUEST[Entradaf]','$_REQUEST[Salidai]','$_REQUEST[Salidaf]')",$link);
        $Dia="";
     }else{

        $Msj="No puedes duplicar el dia!";

     }

  }elseif($op=='Ed'){

     $lUp=mysql_query("update hrd set entradai='$_REQUEST[Entradai]',entradaf='$_REQUEST[Entradaf]',salidai='$_REQUEST[Salidai]',salidaf='$_REQUEST[Salidaf]' where id='$busca' and dia='$_REQUEST[Dia]'",$link);
     $Dia="";
  }

  if($op=='Si'){                    //Elimina Registro
     $lUp=mysql_query("update invb SET ubi = 'TORREON' where id='$_REQUEST[cId]' limit 1",$link);
     $lUp=mysql_query("delete from sdd where id='$busca' and bolsa='$_REQUEST[cId]' limit 1",$link);
     $Msj="La bolsa : $_REQUEST[cId] a sido eliminada!";
  }

  $cSqlD="select * from hr where id = '$busca'";

  $cSql="select * from hrd where id='$busca' ";

  $HeA=mysql_query($cSqlD,$link);

  $He=mysql_fetch_array($HeA);

  $aDia=array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

  $Edit = array("","","Dia","Descripcion","Entrada Inicial","Entrada Final","Salida Inicial","Salida Final","","-","-","-","-","-","-","-");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php
 //headymenu($Titulo);
?>

<body bgcolor="#FFFFFF" onload="cFocus()">

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Bolsa.focus();

}

function Mayusculas(cCampo){

if (cCampo=='Producto'){document.form1.Producto.value=document.form1.Producto.value.toUpperCase();}

}

</script>

<?
   echo "<table align='center' width='90%' background='lib/fondo.gif' border='0' >";

   echo "<br><td><tr> $Gfont ";

   echo "<div align='center'> Horarios : $He[id] &nbsp; &nbsp; $He[descripcion] </font></div>";

   echo "</font></td><br></table>";

   if(!$res=mysql_query($cSql.$cWhe,$link)){

       cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

   }else{

      CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

      $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

      $res=mysql_query($sql,$link);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      while($registro=mysql_fetch_array($res)){

           echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";

			 if($He[status]<>'CERRADA'){
                echo "<td align='center'><a href='horariosd.php?busca=$busca&cId=$registro[bolsa]&op=El&pagina=$pagina'><img src='lib/dele.png' alt='Elimina Registro' border='0'></a></td>";
             }else{
                echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";
             }
             $nDia=$registro[dia];
             echo "<td align='center'><a href='horariosd.php?busca=$registro[id]&Dia=$registro[dia]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
             echo "<td align='right'>$Gfont $registro[dia] &nbsp; </font></td>";
             echo "<td align='right'>$Gfont $aDia[$nDia] &nbsp; </font></td>";
             echo "<td align='center'>$Gfont $registro[entradai] </font></td>";
             echo "<td align='center'>$Gfont $registro[entradaf]</font></td>";
             echo "<td align='center'>$Gfont $registro[salidai]</font></td>";
             echo "<td align='center'>$Gfont $registro[salidaf]</font></td>";
             echo "</tr>";
            $nRng++;

 	  }

     PonPaginacion(false);           #-------------------pon los No.de paginas-------------------

     echo "<table align='center' width='100%'>";

     echo "<tr>";

     echo "<td>";

	    echo "<form name='form1' method='get' action='horariosd.php' onSubmit='return ValGeneral();'>";

        echo "$Gfont Dia : ";

        echo "<input type='hidden' name='op' value='ag'>";
        $nDia="";
        if($Dia<>''){
           $CpoA=mysql_query("select * from hrd where id='$busca' and dia='$_REQUEST[Dia]'",$link);
           $Cpo=mysql_fetch_array($CpoA);
           echo "<input type='hidden' name='op' value='Ed'>";
           echo "<input type='hidden' name='Dia' value='$Cpo[dia]'>";
           $Dis='disabled';
           $nDia=$Cpo[dia];
        }

        echo "<select name='Dia' $Dis>";
        echo "<option value=1>Lunes</option>";
        echo "<option value=2>Martes</option>";
        echo "<option value=3>Miercoles</option>";
        echo "<option value=4>Jueves</option>";
        echo "<option value=5>Viernes</option>";
        echo "<option value=6>Sabado</option>";
        echo "<option value=0>Domingo</option>";
        echo "<option selected value=$Cpo[dia]>$aDia[$nDia]</option>";
        echo "</select>";

        echo " &nbsp; Entrada I.: ";
        echo "<input type='text' name='Entradai' value='$Cpo[entradai]' size='6' >";
        echo " &nbsp; Entrada F.: ";
        echo "<input type='text' name='Entradaf' value='$Cpo[entradaf]' size='6' >";
        echo " &nbsp; Salida I.: ";
        echo "<input type='text' name='Salidai' value='$Cpo[salidai]' size='6' >";
        echo " &nbsp; Salida F.: ";
        echo "<input type='text' name='Salidaf' value='$Cpo[salidaf]' size='6' >";

        echo " &nbsp; <input type='submit' name='ok' value='ok'>";

	    echo "<input type='hidden' name='busca' value=$busca>";

       echo "</form>";

     echo "</td></tr>";

     echo"<td>";

     echo "</table>";

     echo "<div><a class='pg' href='horarios.php'>Regresar</a></div>";

     }

    ?>


</body>

</html>

<?

mysql_close();

?>
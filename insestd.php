<?php
  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Estudio=$_REQUEST[Estudio];

  $Agrega=$_REQUEST[Agrega];

  $op=$_REQUEST[op];

  $Titulo="Estudios por institucion";

  $OrdenDef="estins.estudio";            //Orden de la tabla por default

  $Tabla="estins";

  if($cOp=='El'){
     $lUp=mysql_query("delete from $Tabla where estudio='$Estudio' and instituto='$busca' limit 1",$link);
     $Estudio='';
  }elseif($op=='Ag'){
     $lUpA=mysql_query("select todo from inst where institucion='$busca'",$link);
     $lUp=mysql_fetch_array($lUpA);
     if($lUp[0]=='Si'){
        $Msj='Institucion registrada para todos los estudios';
     }else{
       $lUp=mysql_query("select estudio from $Tabla where estudio='$Agrega' and instituto='$busca'",$link);
       if(mysql_fetch_array($lUp)){
          $Msj="El estudio se encuentra agregado";
       }else{
          $lUp=mysql_query("select estudio from est where estudio='$Agrega'",$link);
          if(!mysql_fetch_array($lUp)){
             $Msj="El estudio $Agrega no Existe!";
          }else{
             $lUp=mysql_query("insert into $Tabla (instituto,estudio) VALUES ('$busca','$Agrega')",$link);
          }
       }
    }
  }

  $cSql="select estins.estudio,est.descripcion from $Tabla,est where instituto='$busca' and estins.estudio=est.estudio ";

  $Edit = array("Elim","Estudio","Descripcion","-","-","-");

  require ("config.php");


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<script language="JavaScript1.2">
function Mayusculas(cCampo){
if (cCampo=='Agrega'){document.form2.Agrega.value=document.form2.Agrega.value.toUpperCase();}
}
</script>

<?php headymenu($Titulo,1);

  filtro($Tabla);           #---------------Si trae algo del filtro realizalo ----------------

  if(!$res=mysql_query($cSql.$cWhe,$link)){

     cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

  	    $HeA=mysql_query("select institucion,nombre,lista,telefono,todo from inst where institucion='$busca'",$link);
        $He=mysql_fetch_array($HeA);
        echo "$Gfont ";
        echo "<div align='center'>ESTUDIOS CONVENIDOS </div>";
        echo "<div align='center'>Institucion: $busca  $He[nombre]  Telefono: $He[telefono] Lista precios: $He[lista]</div> ";
        echo "</font>";

        if($He[todo]=='Si'){

           echo "<p>&nbsp;</p>";
           echo "<p>&nbsp;</p>";
           echo "<p>&nbsp;</p>";
           cMensaje("Esta Institucion incluye todos los estudios...");    #Manda mensaje de datos no existentes

        }else{
           CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

           $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
           $res=mysql_query($sql,$link);

           PonEncabezado();         #---------------------Encabezado del browse----------------------

           while($registro=mysql_fetch_array($res)){
            echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
            echo "<td align='center'><a href=$_SERVER[PHP_SELF]?busca=$busca&Estudio=$registro[estudio]&cOp=El&pagina=$pagina><img src='lib/deleon.png' alt='Elimina reg' border='0'></td>";
            echo "<td>$Gfont $registro[estudio]</font></td>";
            echo "<td>$Gfont $registro[descripcion]</font></td>";
            echo "</tr>";
            $nRng++;
          }

          PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

          //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

          echo "<form name='form2' method='get' action='$_SERVER[PHP_SELF]'>";
            echo "$Gfont Estudio: &nbsp;";
            echo "<input type='text' name='Agrega' size='10' maxlength='13' onBlur=Mayusculas('Agrega')>";
            echo " &nbsp; <input type='submit' name='Submit' value='Agrega'>";
            echo "<input type='hidden' name='pagina' value=$pagina>";
            echo "<input type='hidden' name='busca' value=$busca>";
            echo "<input type='hidden' name='op' value='Ag'>";
         echo "</form><br><br>";

       }//fin if

    }

    echo " &nbsp; <a class='pg' href='insest.php?busca=$busca'>Regresa</a>";

    mysql_close();

    ?>

</body>

</html>
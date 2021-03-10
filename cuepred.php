<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Estudio=$_REQUEST[Estudio];

  $Pregunta=$_REQUEST[Pregunta];

  $op=$_REQUEST[op];

  $Titulo="Cuestionario pre-analitico por estudio";

  $OrdenDef="pred.pre";            //Orden de la tabla por default

  if($op=='Ag'){
     $CueA=mysql_query("select pregunta from cue where id='$Pregunta'",$link);
     if($cCue=mysql_fetch_array($CueA)){
         $cCueA=mysql_query("select * from pred where pre='$Pregunta' and estudio='$busca'",$link);
         if($cCue=mysql_fetch_array($cCueA)){
             $Msj="La pregunta que desea Agregar al estudio ya existe!";
         }else{
             $lUp=mysql_query("insert into pred (estudio,pre) VALUES ('$busca','$Pregunta')",$link);
         }
     }else{
       $Msj="La clave($Pregunta) del cuestionario, No existe";
     }
  }elseif($op=='Si'){    // Para dar de ba
     $lUp=mysql_query("delete from pred where estudio='$busca' and pre='$cId' limit 1",$link);
  }

  $Tabla="pred";

  $cSqlH="select pre.estudio,est.descripcion from pre,est where pre.estudio=est.estudio and est.estudio='$busca'";

  $cSql="select pred.pre,cue.pregunta from pred,cue where pred.estudio='$busca' and  pred.pre=cue.id ";

  $Edit = array("Clave","Pregunta","Elim","-","-","-");

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,1);

  $HeA=mysql_query($cSqlH,$link);
  $He=mysql_fetch_array($HeA);

  echo "<br> $Gfont &nbsp; &nbsp; $He[estudio] &nbsp; $He[descripcion] $Gfon ";

  if(!$res=mysql_query($cSql,$link)){

     cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

     CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

     $sql=$cSql." ORDER BY pre ASC LIMIT ".$limitInf.",".$tamPag;

     $res=mysql_query($sql,$link);

     PonEncabezado();         #---------------------Encabezado del browse----------------------

     while($registro=mysql_fetch_array($res)){
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           //echo "<td align='center'><a href=preguntase.php?busca=$registro[id]&pagina=$pagina><img src='lib/edit.png' alt='Edita reg' border='0'></td>";
           echo "<td align='right'>$Gfont $registro[pre]$Gfon </td>";
           echo "<td>$Gfont $registro[pregunta]</font></td>";
           echo "<td align='center'><a href=cuepred.php?cId=$registro[pre]&op=El&pagina=$pagina&busca=$busca><img src='lib/deleon.png' alt='Borra reg' border='0'></td>";
           echo "<tr>";
           $nRng++;
        }//fin while

        PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------
        echo "<form name='form1' method='get' action='cuepred.php'>";
            echo "$Gfont Agrega : $Gfon";
            echo "<INPUT TYPE='TEXT'  name='Pregunta' align='left' size='4' maxlength='5'>";
            echo " &nbsp; <input type='submit' name='Submit' value='Agrega'>";
            echo "<input type='hidden' name='orden' value='$orden'>";
            echo "<input type='hidden' name='op' value='Ag'>";
            echo "<input type='hidden' name='pagina' value='$pagina'>";
            echo "<input type='hidden' name='busca' value='$busca'>";
      echo "</form>";
   }

   echo "<a class='pg' href='cuepre.php'><img src='lib/regresar.gif' border='0'></a>";

   ?>


</body>

</html>

</body>

</html>


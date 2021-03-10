<?php

  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $op=$_REQUEST[op];

  $Estudio=$_REQUEST[Estudio];

  $Agrega=$_REQUEST[Agrega];

  $op=$_REQUEST[op];

  $Titulo="Estudios que requieren de pre-analiticos";

  $OrdenDef="estudio";            //Orden de la tabla por default

  $Tabla="pre";

  if($op=='Si'){

     $lUp=mysql_query("delete from $Tabla where estudio='$_REQUEST[cId]' limit 1",$link);
     $Msj="El registro: $_REQUEST[cId] a sido dado de baja";

  }

  $cSql="select pre.estudio,est.descripcion from $Tabla,est where pre.estudio=est.estudio and est.descripcion like '%$busca%'";

  $Edit = array("Detalle","Estudio","Descripcion","Elim","-","Cpre.estudio","Cet.descripcion","-");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,1);

  if($_REQUEST[op]=='sm'){

     $cSum=mysql_query("select sum($_REQUEST[SumaCampo]) from $Tabla ".$cWhe,$link);

     $Suma=mysql_fetch_array($cSum);

     $cFuncion=" // --> $SumaCampo: ".number_format($Suma[0],"2");

  }

  if(!$res=mysql_query($cSql.$cWhe,$link)){

     cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

        $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
        $res=mysql_query($sql,$link);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a href=cuepred.php?busca=$registro[estudio]><img src='lib/browse.png' alt='Detalle' border='0'></td>";
           echo "<td>$Gfont $registro[estudio]</font></td>";
           echo "<td>$Gfont $registro[descripcion]</font></td>";
           echo "<td align='center'><a href=cuepre.php?cId=$registro[estudio]&op=El&pagina=$pagina><img src='lib/deleon.png' alt='Borra reg' border='0'></td>";
           echo "<tr>";
           $nRng++;
        }//fin while

        PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

        CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    mysql_close();

    ?>

</body>
</html>

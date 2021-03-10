<?php

  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $OrdenDef="cliente";            //Orden de la tabla por default

  $op=$_REQUEST[op];

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Tabla="cli";

  // Es una copia de clientes y se cambian todas las urls a clientesord y el exit al ordenesnvas com busca
  $Titulo="Catalogo de Pacientes";

  $tamPag=15;

  $Palabras  = str_word_count($busca);  //Dame el numero de palabras

  $P=str_word_count($busca,1);          //Metelas en un arreglo

  for ($i = 0; $i < $Palabras; $i++) {
      $cBus=$cBus."and cli.nombrec like '%$P[$i]%' ";
  }

  $cSql="select cli.cliente,cli.nombrec,cli.localidad,cli.telefono,cli.municipio,zns.descripcion from cli,zns where zns.zona=cli.zona $cBus ";

  $Edit = array("Edit","Selecc","Clave","Nombre","Colonia","Municipio","Zona","Telefono","-","-","Ncli.cliente","Ccli.nombrec","Ccli.localidad","Ccli.municipio","Czns.descripcion","Ccli.telefono");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,1);

  filtro($Tabla);           #---------------Si trae algo del filtro realizalo ----------------


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
           echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
           echo "<td align='center'><a href=clientese.php?busca=$registro[cliente]&pagina=$pagina><img src='lib/edit.png' alt='Edita reg' border='0'></td>";
           echo "<td align='center'><a href=ordenesnvas.php?Cliente=$registro[cliente]&op=cl><img src='lib/ok.png' alt='Elige este reg' border='0'></td>";
           echo "<td>$Gfont $registro[cliente]</font></td>";
           echo "<td>$Gfont $registro[nombrec]</font></td>";
           echo "<td>$Gfont $registro[localidad]</font></td>";
           echo "<td>$Gfont $registro[municipio]</font></td>";
           echo "<td>$Gfont $registro[descripcion]</font></td>";
           echo "<td>$Gfont $registro[telefono]</font></td>";
           echo "</tr>";
           $nRng++;
        }//fin while
        PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

        CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    mysql_close();

    ?>

</body>

</html>
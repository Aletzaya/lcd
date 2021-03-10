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
  
  if($busca > 0){            // busco por numero
    $cBus=" and cli.cliente >= '$busca' ";

  }else{                     // busco por nombre

     $Palabras  = str_word_count($busca);  //Dame el numero de palabras
     $P=str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
         $cBus=$cBus."and cli.nombrec like '%$P[$i]%' ";
     }

     //$cSql="select ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.status,ot.ubicacion,ot.institucion,ot.recepcionista from $Tabla,cli where $Tabla.cliente=cli.cliente and cli.nombrec like '%$busca%' ";
     $cSql = "SELECT ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.status,ot.ubicacion,ot.institucion,
             ot.recepcionista,ot.pagada, 
             FROM $Tabla,cli WHERE $Tabla.cliente=cli.cliente $cBus "; 

  }
  
  $cSql = "SELECT cli.cliente,cli.nombrec,cli.localidad,cli.telefono,cli.municipio,zns.descripcion,cli.numveces 
          FROM cli,zns WHERE zns.zona=cli.zona $cBus ";

  $Edit = array("Ed","Selc","Clave","Nombre","Colonia","Mpio","Zona","Telefono","Veces",
          "-","-","Ncli.cliente","Ccli.nombrec","Ccli.localidad","Ccli.municipio","Czns.descripcion","Ccli.telefono","Ccli.numveces");

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

  if($_SESSION['file']==$Tabla and isset($_SESSION['id'])){    #Si prendio la sesion(file) y id, genera el filtro

     $Usr=$_COOKIE['USERNAME'];

     if($_SESSION[id]=='99999'){

        $ftdA=mysql_query("select * from ftd where id='99999' and fil='$Tabla' order by orden",$link);

     }else{

       $ftdA=mysql_query("select * from ftd where id='$_SESSION[Id]' order by orden",$link);

     }

     while ($ftd=mysql_fetch_array($ftdA)){

         if(!isset($cWhe)){$cWhe=" and ";}

        $cWhe=$cWhe.$ftd[campo]." ".$ftd[signo]." ".$ftd[valor]." ".$ftd[yo]." ";

     }

     $fil_act="<font color='#c2353d'>FILTRO ACTIVO</font><br>";


  }

  if($_REQUEST[op]=='sm'){

     $cSum=mysql_query("select sum($_REQUEST[SumaCampo]) from $Tabla ".$cWhe,$link);

     $Suma=mysql_fetch_array($cSum);

     $cFuncion=" // --> $SumaCampo: ".number_format($Suma[0],"2");

  }

  //echo $cSql.$cWhe;
  
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
        
           //echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
           echo "<td align='center'><a href=clientese.php?busca=$registro[cliente]&pagina=$pagina><img src='lib/edit.png' alt='Edita reg' border='0'></td>";
           echo "<td align='center'><a href=ordenesnvas.php?Cliente=$registro[cliente]&op=cl><img src='lib/ok.png' alt='Elige este reg' border='0'></td>";
           echo "<td>$Gfont $registro[cliente]</font></td>";
           echo "<td>$Gfont $registro[nombrec]</font></td>";
           echo "<td>$Gfont $registro[localidad]</font></td>";
           echo "<td>$Gfont $registro[municipio]</font></td>";
           echo "<td>$Gfont $registro[descripcion]</font></td>";
           echo "<td>$Gfont $registro[telefono]</font></td>";

           echo "<td align='center'><a class='pg' href=javascript:wingral('repots.php?busca=$registro[0]')>$registro[numveces]</td>";
           
           //echo "<td>$Gfont $registro[numveces]</font></td>";
           
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
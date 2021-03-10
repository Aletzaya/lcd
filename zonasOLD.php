<?php

  session_start();

  require("lib/filtro.php");

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

  $Titulo="Catalogo de zonas";

  $Tabla="zns";

  $cSql="select zona,descripcion,poblacion from $Tabla ";

  $OrdenDef="zona";            //Orden de la tabla por default

  $cSql="select zona,descripcion,poblacion from $Tabla where descripcion like '%$busca%' ";

  $Edit = array("Edit","Detalle","Zona","Descripcion","Poblacio aprox","Elim","-","-","Nzns.zona","Czns.descripcion","Nzns.poblacion","-");

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
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a href=zonase.php?busca=$registro[zona]&pagina=$pagina><img src='lib/edit.png' alt='Edita reg' border='0'></a></td>";
           echo "<td align='center'><a href=zonasd.php?busca=$registro[zona]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";
           echo "<td align='right'>$Gfont $registro[zona] &nbsp; </font></td>";
           echo "<td>$Gfont $registro[descripcion]</font></td>";
           echo "<td align='right'>$Gfont ".number_format($registro[poblacion],"2")." &nbsp; </font></td>";
           echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina reg' border='0'></td>";
           $nRng++;
		}//fin while

        PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

        CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    mysql_close();

    ?>

</body>

</html>
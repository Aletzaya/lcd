<?php

  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $pagina=$_REQUEST[pagina];

  $orden=$_REQUEST[orden];

  $link=conectarse();

  $tamPag=15;

  $busca=$_REQUEST[busca];

  $Usr=$check['uname'];

  $Titulo="Ingresos de Caja";

  $op=$_REQUEST[op];

  $OrdenDef="id";            //Orden de la tabla por default

  $Tabla="cja";

  $Fecha=date("Y-m-d");

  session_register("Tabla"); //Tabla a usar en los filtros

  if(strlen($busca)==0){
     $cSql="select id,orden,fecha,hora,importe,usuario,tpago from $Tabla";
  }else{
     $cSql="select id,orden,fecha,hora,importe,usuario,tpago from $Tabla where orden ='$busca'";
  }
  $Edit = array("Imp","No.recibo","Orden","Fecha","Hora","Importe","Tpo.pago","Usuario","-","Ncja.id","Ncja.orden","Dcja.fecha","Dcja.hora","Ncja.importe","Ncja.tpago","Ccja.usuario");

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

  //echo "$cSql $cWhe";
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
           echo "<td align='center'><a href=impot.php?busca=$registro[orden]&pagina=$pagina&reimp=1><img src='lib/print.png' alt='Edita registro' border='0'></a></td>";
           echo "<td align='right'>$Gfont $registro[id] &nbsp; </font></td>";
           echo "<td align='right'>$Gfont $registro[orden] &nbsp; </font></td>";
           echo "<td align='right'>$Gfont $registro[fecha] &nbsp; </font></td>";
           echo "<td align='right'>$Gfont $registro[hora] &nbsp; </font></td>";
           echo "<td align='right'>$Gfont ".number_format($registro[importe],"2")." &nbsp; </font></td>";
           echo "<td align='right'>$Gfont $registro[tpago] &nbsp; </font></td>";
           echo "<td align='right'>$Gfont $registro[usuario] &nbsp; </font></td>";
           echo "</tr>";
           $nRng++;
		}//fin while
		echo "</table>";

        PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

        CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    mysql_close();

    ?>

</body>

</html>

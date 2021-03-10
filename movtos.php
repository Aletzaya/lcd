<?php

  session_start();

  require ("config.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  require("lib/filtro.php");

  $link=conectarse();

  $tamPag=15;

  $op=$_REQUEST[op];

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Titulo="Registro de movtos.de empleados";

  $OrdenDef="mov.fecha,mov.hora";            //Orden de la tabla por default

  $Columna="";

  $tamPag=15;

  if($op=='Si'){                    //Elimina Registro
     $lUp=mysql_query("delete from emp where id='$_REQUEST[cId]' limit 1",$link);
     $Msj="El registro: $_REQUEST[cId] a sido dado de baja";
  }


  $cSql="
  SELECT mov.fecha,mov.empleado,emp.nombre,mov.hora,mov.status,emp.id,mov.mov,emp.inst,inst.alias 
  FROM inst,mov left join emp ON mov.empleado=emp.id 
  WHERE emp.inst=inst.institucion and emp.nombre LIKE '%$busca%' 
  ";

  $Tabla="emp";

  $ColorA = array("","#0CFA20","#FCF803","#FA0202");

  $StatusA = array("Err","Normal","Tolerancia","Retardo");

  $MovtoA = array("-","Entrada","Salida","Entrada","Salida");

  $Edit = array("","Fecha","Clave","Nombre","Hora","Movto","Status","Institucion","","Dmov.fecha","Nemp.id","Cemp.nombre","Nmov.hora","Dmov.mov","Nmov.status","Cinst.alias");

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

        $ftdA=mysql_query("select * from ftd where usr='$Usr' and fil='$Tabla' order by orden",$link);
//        $ftdA=mysql_query("select * from ftd where id='99999' and fil='$Tabla' order by orden",$link);

     }else{

       $ftdA=mysql_query("select * from ftd where usr='$Usr' and fil='$Tabla' order by orden",$link);
//       $ftdA=mysql_query("select * from ftd where id='$_SESSION[Id]' order by orden",$link);

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

  //echo "$cSql $cWhe";                 //Checa la Instruccion SQL......

  if(!$res=mysql_query($cSql.$cWhe,$link)){

        cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

   }else{

      CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

      $sql=$cSql.$cWhe." ORDER BY ".$orden.", mov.mov ASC LIMIT ".$limitInf.",".$tamPag;

      $res=mysql_query($sql,$link);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      while($registro=mysql_fetch_array($GLOBALS[res])){
          $Sta=$registro[status];
          $Mov=$registro[mov];
          $Color=$ColorA[$Sta];
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a href='empleadose.php?busca=$registro[id]&pagina=$pagina'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
           //echo "<td align='center'><a href='empleados.php?cId=$registro[id]&op=El&pagina=$pagina'><img src='lib/dele.png' alt='Elimina Registro' border='0'></td>";
           //echo "<td align='center'><a href='empleadosd.php?busca=$registro[id]&pagina=$pagina'><img src='lib/browse.png' alt='Elimina Registro' border='0'></td>";
           echo "<td align='right'>$Gfont $registro[fecha] &nbsp;</font></a></td>";
           echo "<td align='right'>$Gfont $registro[id] &nbsp; </font></td>";
           echo "<td>$Gfont &nbsp; $registro[nombre]</font></td>";
           echo "<td align='center'>$Gfont $registro[hora]</font></td>";
           echo "<td>$Gfont &nbsp; $MovtoA[$Mov]</font></td>";
           echo "<td align='center'>$Gfont <font color='$Color' >&nbsp; $StatusA[$Sta] </font></font></td>";
           echo "<td align='center'>$Gfont &nbsp; $registro[alias] </font></font></td>";
           echo "</tr>";
          $nRng++;
      }
   }

   PonPaginacion(true);           #-------------------pon los No.de paginas-------------------

   CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

    ?>
</body>
</html>
<?
mysql_close();
?>
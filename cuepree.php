<?php
  session_start();

  require("lib/kaplib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Estudio=$_REQUEST[Estudio];

  $Agrega=$_REQUEST[Agrega];

  $op=$_REQUEST[op];

  $Tabla="pre";

  $Estudio=$_REQUEST[Estudio];

  $Titulo="Estudio para pre-analiticos ($busca)";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=='NUEVO'){
        $lEsA=mysql_query("select estudio from  est where estudio='$Estudio' limit 1",$link);
        if(mysql_fetch_array($lEsA)){
           $lUp=mysql_query("select estudio from $Tabla where estudio='$Estudio' limit 1",$link);
           if(mysql_fetch_array($lUp)){
              $Msj="El estudio($Estudio) ya existe! favor de verificar...";
           }else{
             $lUp=mysql_query("insert into $Tabla (estudio) VALUES ('$Estudio')",$link);
             $lBd=true;
           }
        }else{
            $Msj="El estudio($Estudio) NO existe! favor de verificar...";
        }
      }

      if($_REQUEST[Boton] == Aceptar and $lBd){
         header("Location: cuepre.php?pagina=$pagina");
      }

  }

  $cSql="select pre.estudio,est.descripcion from $Tabla,est where (pre.estudio=est.estudio and pre.estudio='$busca')";

  $CpoA=mysql_query($cSql,$link);
  $Cpo=mysql_fetch_array($CpoA);
  $lAg=$busca<>$Cpo[estudio];

  require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<script language="JavaScript1.2">
function Mayusculas(cCampo){
if (cCampo=='Estudio'){document.form1.Estudio.value=document.form1.Estudio.value.toUpperCase();}
}
</script>

<body>

<?php headymenu($Titulo,0); ?>

<hr noshade style="color:3366FF;height:2px">

<table width="100%" border="0">

    <?php
    echo "<tr>";

      echo "<td  width='20%' rowspan='2'>";

         echo "<a class='pg' href='cuepre.php?pagina=$pagina'><img src='lib/regresar.gif' border='0'></font></a>";

      echo "</td>";

      echo "<td>$Gfont ";

      echo "<p>&nbsp;</p>";

      echo "<form name='form1' method='get' action='cuepree.php'>";

      echo "<p>Estudio...........:";
      echo "<input type='text' name='Estudio' maxlength='7' onBlur=Mayusculas('Estudio') size='10'>";
      echo "</p>";

      echo "<p>&nbsp;</p>";

      echo Botones();

    echo "</form>";

    mysql_close();

    ?>

  </td>

  </tr>

  </table>

  </body>

  </html>


<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $tamPag=15;

  $op=$_REQUEST[op];

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Titulo="Reportes diversos";

  $OrdenDef="id";            //Orden de la tabla por default

  $Tabla="reportes";

  //session_register("Tabla"); //Tabla a usar en los filtros

  $cSql="select * from $Tabla ";

  require ("config.php");							//Parametros de colores;
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>

</head>
<script language="JavaScript1.2">
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
	alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=300,top=150")
}
</script>
<body>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

headymenu($Titulo,1);

  if(!$res=mysql_query($cSql.$cWhe,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados ò hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='reportes.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{
        $numeroRegistros=mysql_num_rows($res);
 		if(!isset($orden)){
 			$orden=$OrdenDef;
		}
		if(!isset($pagina)){
			$pagina=1;
			$inicio=1;
			$final=$tamPag;
		}
		//calculo del limite inferior
		$limitInf=($pagina-1)*$tamPag;
		//calculo del numero de paginas
		$numPags=ceil($numeroRegistros/$tamPag);
		if(!isset($pagina)){
			   $pagina=1;
			   $inicio=1;
			   $final=$tamPag;
		}else{
			$seccionActual=intval(($pagina-1)/$tamPag);
			$inicio=($seccionActual*$tamPag)+1;
			if($pagina<$numPags){
			   $final=$inicio+$tamPag-1;
			}else{
				$final=$numPags;
			}
		}
      
      $sql = $cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

		$res = mysql_query($sql,$link);

		echo "<table align='center' width='85%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr height='24' background='lib/bartit.gif'>";
		echo "<td> &nbsp; </td>";
		echo "<td align='center'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=descripcion'>Descripcion</a></td>";
      echo "<td align='center'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=muestras'>#Muests</a></td>";
      echo "</tr>";
		while($registro=mysql_fetch_array($res)){

           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           echo "<td><a href=javascript:Ventana('bajareporte.php?busca=$registro[id]&pagina=$pagina')>$Gfont Gra. reporte</a></td>";

           echo "<td><a href='reportese.php?busca=$registro[id]&pagina=$pagina'>$Gfont $registro[id]</a></td>";

		     echo "<td>$Gfont $registro[nombre]</td>";

			  echo "</tr>";

           $nRng++;
			  
		}//fin while
		echo "</table>";
	}//fin if
    ?>
	<br>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td align="center" valign="top">
    <?php
    if($pagina>$tamPag){
        echo "&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=1&orden=".$orden."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>"."|<"."</font></a>&nbsp;";
        echo "&nbsp;&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($inicio-15)."&orden=".$orden."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'><</font>";
        echo "</a>&nbsp;&nbsp;";
    }
    for($i=$inicio;$i<=$final;$i++)    {
        if($i==$pagina){
            echo "<font face='verdana' size='-2'><b>".$i."</b>&nbsp;</font>";
        }else{
            echo "<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".$i."&orden=".$orden."&busca=".$busca."'>";
            echo "<font face='verdana' size='-2'>".$i."</font></a>&nbsp;";
        }
        if($i>=$numPags){$i=$numPags+15;}
    }
    if($inicio+15<$numPags){
        echo "&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($inicio+15)."&orden=".$orden."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>></font></a>";
        echo "&nbsp;&nbsp;&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($numPags)."&orden=".$orden."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>".">|"."</font></a>&nbsp;";
    }
    ?>
	</td></tr>
	</table>

</body>
</html>
<?php
mysql_close();
?>
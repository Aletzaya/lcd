<?php

  session_start();

  require ("config.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  require("lib/filtro.php");

  $link=conectarse();

  $Tabla="est1";

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Titulo="REGISTRO DE ESTIRADORES 1er.PASO";

  $cFuncion="";                 //Variabla para la funciones estadistico de suma,media,...

  $OrdenDef="fecha";            //Orden de la tabla por default

  $tamPag=15;

  $op=$_REQUEST[op];

  $ParA=mysql_query("select * from pest1",$link);

  $Par=mysql_fetch_array($ParA);

  $cSql="select * from est1 where fecha >= '$busca'";

  $Edit = array("","","Fecha","Hora","Estirador","Lado","Pso por metro","Metros tomados","Desviacion","Diferencia",

  "","","Dfecha","Dhora","Nestirador","Clado","Npeso","Nmetros","Ndesviacion","NDiferencia");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,1); ?>

<script language="JavaScript1.2">

function win(url){

   window.open(url,"win","status=no,tollbar=no,menubar=no,width=300,height=350,left=250,top=150")

}

</script>

<?

  filtro($Tabla);   		#---------------Si trae algo del filtro realizalo ----------------

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

  //echo "$cSql $cWhe";                 //Checa la Instruccion SQL......

  if(!$res=mysql_query($cSql.$cWhe,$link)){

		cMensaje("No se encontraron resultados ò hay un error en el filtro");	#Manda mensaje de datos no existentes

   }else{

   	  CalculaPaginas();		#--------------------Calcual No.paginas-------------------------

      $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

		$res=mysql_query($sql,$link);

		PonEncabezado(); 		#---------------------Encabezado del browse----------------------

		while($registro=mysql_fetch_array($GLOBALS[res])){

			  if($registro[peso] < $Par[minimo] or $registro[peso] > $Par[maximo]){
			  		$Letra='#CD0C0C';	//Rojo
			  		$Disp='Checar';
			  }elseif($registro[peso]==$Par[ideal]){
			      $Letra='#058B02';		//Verde
			      $Disp='Ideal';
			  }else{
			      $Letra='#0066FF'; //Normal
			  		$Disp='Ok';
			  }
           echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
           echo "<td align='center'><a href='estiradores1m.php?Estirador=$registro[estirador]&Lado=$registro[lado]&Fecha=$registro[fecha]&Hora=$registro[hora]&pagina=$pagina'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
           echo "<td align='center'><a href='estiradores1.php?Estirador=$registro[estirador]&Fecha=$registro[fecha]&Hora=$registro[hora]&pagina=$pagina&cId=$registro[lado]&op=El'><img src='lib/dele.png' alt='Elimina Registro' border='0'></td>";
           echo "<td>$Gfont<b>$registro[fecha]</b></font></a></td>";
           echo "<td>$Gfont<b>$registro[hora]</b></font></td>";
           echo "<td>$Gfont<b>$registro[estirador]</b></font></td>";
           echo "<td>$Gfont<b>$registro[lado]</b></font></td>";
           echo "<td align='right'>$Gfont<b>".number_format($registro[peso],"2")."</b></font></td>";
           echo "<td align='right'>$Gfont<b>".number_format($registro[metros],"2")."</b></font></td>";
           echo "<td align='right'>$Gfont<b>".number_format($registro[desviacion],"2")."</b></font></td>";
           echo "<td align='right'>$Gfont<b>$Disp</b></font></a></td>";
           echo "</tr>";

		}

		echo "</table>";

	  }

	  PonPaginacion();   		#-------------------pon los No.de paginas-------------------

	  CuadroInferior($busca);	#-------------------Op.para hacer filtros-------------------

    ?>


</body>

</html>

<?

mysql_close();

?>
<?php
  $Titulo="Lista de precios";
  require("lib/kaplib.php");
  $link=conectarse();
  $tamPag=15;
  $nivel_acceso=10; // Nivel de acceso para esta página.
  if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
    header ("Location: $redir?error_login=5");
     exit;
  }
  $Usr=$HTTP_SESSION_VARS['usuario_login'];
  session_start();
  $Tabla="est";
  session_register("Tabla"); //Tabla a usar en los filtros
  $cSql="select estudio,descripcion,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10 from $Tabla ";
  $OrdenDef="estudio";            //Orden de la tabla por default
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style type="text/css">
<!--
a.ord:link {
    color: #FFFFFF;
    text-decoration: none;
}
a.ord:visited {
    color: #FFFFFF;
    text-decoration: none;
}
a.ord:hover {
    color: #00CC33;
    text-decoration: underline;
}
-->
</style>
<title><?php echo $Titulo;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Pragma" content="no-cache" />
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
</head>
<body bgcolor="#FFFFFF">
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><div align="left"><img src="lib/logo2.jpg" width="100" height="80"></div></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%"></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633FF">
    <td width="82%" bgcolor="#6633ff"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="Imagenes/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
    </td>
    <td width="18%" height="23"><div align="right"><script type="text/javascript" language="JavaScript1.2">
<!--
stm_bm(["menu4bb6",400,"","blank.gif",0,"","",0,0,19,19,144,1,0,0,""],this);
stm_bp("p0",[0,4,0,0,0,3,0,7,100,"",-2,"",-2,90,0,0,"#006699","#ffffff","",3,0,0,"#ffffff"]);
stm_ai("p0i0",[0,"Recepcion","","",-1,-1,0,"","_self","","","","",0,0,0,"arrow_r.gif","arrow_r.gif",7,7,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","bold 8pt Arial","bold 8pt Arial",0,0]);
stm_bp("p1",[1,4,0,0,0,3,0,0,100,"progid:DXImageTransform.Microsoft.Checkerboard(squaresX=12,squaresY=12,direction=down,enabled=0,Duration=0.25)",11,"",-2,85,0,0,"#7f7f7f","transparent","",3,0,0,"#000000"]);
stm_aix("p1i0","p0i0",[0,"Ordenes de trabajo","","",-1,-1,0,"ordenes.php","_self","","","","",0,0,0,"","",0,0,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","8pt Arial","8pt Arial"]);
stm_aix("p1i1","p1i0",[0,"Ingresos","","",-1,-1,0,"ingresos.php"]);
stm_aix("p1i2","p1i0",[0,"Corte de caja","","",-1,-1,0,"corte.php"]);
stm_ep();
stm_aix("p0i1","p0i0",[0,"Catalogos"]);
stm_bpx("p2","p1",[]);
stm_aix("p2i0","p1i0",[0,"Pacientes","","",-1,-1,0,"clientes.php"]);
stm_aix("p2i1","p1i0",[0,"Medico","","",-1,-1,0,"medicos.php"]);
stm_aix("p2i2","p1i0",[0,"Estudios","","",-1,-1,0,"estudios.php"]);
stm_aix("p2i3","p1i0",[0,"Zonas","","",-1,-1,0,"zonas.php"]);
stm_aix("p2i4","p1i0",[0,"Instituciones","","",-1,-1,0,"institu.php"]);
stm_aix("p2i5","p1i0",[0,"Lista de precios","","",-1,-1,0,"lista.php"]);
stm_aix("p2i6","p1i0",[0,"Estudios por listacion"]);
stm_aix("p2i7","p1i0",[0,"Departamentos","","",-1,-1,0,"depto.php"]);
stm_aix("p2i8","p1i0",[0,"Cuestionario Pre-analitico","","",-1,-1,0,"preguntas.php"]);
stm_aix("p2i9","p1i0",[0,"Cuetionario por Est.","","",-1,-1,0,"cuepre.php"]);
stm_ep();
stm_aix("p0i2","p0i0",[0,"Pre-analiticos"]);
stm_bpx("p3","p1",[]);
stm_aix("p3i0","p1i0",[0,"Pre-analiticos"]);
stm_ep();
stm_aix("p0i3","p0i0",[0,"Captura de resultados"]);
stm_bpx("p4","p1",[]);
stm_aix("p4i0","p1i0",[0,"Resultados","","",-1,-1,0,"resultados.php"]);
stm_ep();
stm_aix("p0i4","p0i0",[0,"Reportes","","",-1,-1,0,"Reportes.php"]);
stm_bpx("p5","p1",[]);
stm_aix("p5i0","p1i0",[0,"Baja reportes"]);
stm_ep();
stm_ep();
stm_em();
//-->
</script>
</div></td></tr>
</table>
<script language="JavaScript1.2">
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=350,height=320,left=250,top=250")
}
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

function win(url){
   window.open(url,"win","status=no,tollbar=no,menubar=no,width=300,height=350,left=250,top=150")
}
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}
</script>
<hr noshade style="color:3366FF;height:2px">
<?
  if(substr($op,0,1)=='!'){
     session_start();
     $filtro_nvo=substr($op,1);
     session_register("filtro_nvo");
  }elseif($op=='ft'){
     if(substr($Campo,0,1=='D') or $Signo=='like'){
       $Sig1="\'%";
       $Sig2="%\'";
     }elseif(substr($Campo,0,1)=='C'){
       $Sig1="\'";    // Esto es una comita p/k no me marque error or \"
       $Sig2="\'";
    }
    $Campo=substr($Campo,1);
    $lUp=mysql_query("insert into ftd (id,fil,campo,signo,sig1,valor,sig2,yo) VALUES ('99999','$Tabla','$Campo','$Signo','$Sig1','$Sig1$Valor$Sig2','$Sig2','$Yo')",$link);
    if($Yo==''){
      $filtro_nvo="99999";           //Guarda en la kooki k ya existe un filtro
      session_register("filtro_nvo");
    }
  }elseif($op=='br'){
     $lUp=mysql_query("delete from ftd where id='99999' and fil='$Tabla' ",$link);
     session_start();
     $filtro_nvo="";
     session_register("filtro_nvo");
  }

  if(strlen($HTTP_SESSION_VARS['filtro_nvo'])>0){
    $Id=$HTTP_SESSION_VARS['filtro_nvo'];
    $cWhe=" where ";
    if($Id=='99999'){
        $ftdA=mysql_query("select * from ftd where id='99999' and fil='$Tabla' order by orden",$link);
    }else{
       $ftdA=mysql_query("select * from ftd where id='$Id' order by orden",$link);
    }
    while ($ftd=mysql_fetch_array($ftdA)){
       $cWhe=$cWhe.$ftd[campo]." ".$ftd[signo]." ".$ftd[valor]." ".$ftd[yo]." ";
    }
  }elseif(strlen($busca)>0){ //Si trae algun valor a buscar
     if(strlen($busca)>=5){         // Es letra
        $cWhe=" where descripcion like '%$busca%'";
     }else{
        $cWhe=" where estudio >= '$busca' ";
     }
  }
  //echo "$cSql $cWhe";
  if(!$res=mysql_query($cSql.$cWhe,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados ò hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='lista.php?op=br'>";
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
        $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
		$res=mysql_query($sql,$link);
		echo "<div align='center'>";
		echo "<img src='Imagenes/corner-bottom-left.gif' width='15' height='12'>";
		echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Registros, ordenados por ".$orden;
        echo "</b></strong></font>";
		echo "<img src='Imagenes/corner-bottom-right.gif' width='15' height='12'>&nbsp;&nbsp;&nbsp;";
        //echo "<a href=javascript:Ventana('lisins.php?cKey=1')>1</a>&nbsp;&nbsp;";  opcion para listar lista por inst.PENDIENTE...
		echo "&nbsp;&nbsp;&nbsp; <a href=javascript:win('lisins.php?cKey=1')>1</a> <a href=javascript:win('lisins.php?cKey=2')>2</a> <a href=javascript:win('lisins.php?cKey=3')>3</a> <a href=javascript:win('lisins.php?cKey=4')>4</a> ";
		echo "<a href=javascript:win('lisins.php?cKey=5')>5</a> <a href=javascript:win('lisins.php?cKey=6')>6</a> <a href=javascript:win('lisins.php?cKey=7')>7</a> <a href=javascript:win('lisins.php?cKey=8')>8</a> <a href=javascript:win('lisins.php?cKey=9')>9</a> <a href=javascript:win('lisins.php?cKey=10')>10</a></div>";
		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td colspan='12'><hr noshade></td></tr>";  //long de la ralla no.campos k abarca
		echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=estudio'>Estudio</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=descripcion'>Descripcion</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt1'>Lta 1</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt2'>Lta 2</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt3'>Lta 3</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt4'>Lta 4</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt5'>Lta 5</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt6'>Lta 6</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt7'>Lta 7</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt8'>Lta 8</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt9'>Lta 9</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=lt10'>Lta 10</a></th>";
		while($registro=mysql_fetch_array($res))
		{
        ?>
        <tr bgcolor="#CEDADD" onMouseOver="this.style.backgroundColor='#E3DBFD';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CEDADD'"o"];">
        <td><a href=<?php echo 'listae.php?busca='.$registro[estudio].'&pagina='.$pagina; ?>><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[estudio]; ?></b></font></a></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[descripcion]; ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt1],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt2],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt3],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt4],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt5],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt6],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt7],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt8],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt9],"2"); ?></b></font></td>
        <td align = right><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[lt10],"2"); ?></b></font></td>
        </tr>
        <!-- fin tabla resultados -->
        <?
		}//fin while
		echo "</table>";
	}//fin if
    ?>
	<br>
	<table border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td align="center" valign="top">
    <?
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
        echo "&nbsp;&nbsp;&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($numPags-1)."&orden=".$orden."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>".">|"."</font></a>&nbsp;";
    }
    ?>
	</td></tr>
	</table>
<hr noshade style="color:66CC66;height:3px">
<table width="100%" border="0" background="Imagenes/fondo2.jpg">
  <tr bgcolor="#CEDADD">
    <td width="11%" height="86">
      <form name="form1" method="post" action=<?$_SERVER["PHP_SELF"]?>>
        <p align="center"><font color="#0066FF" face="verdana" size="-2"><strong>Busca:</strong></font></p>
        <p align="center"><font color="#0000FF" face="verdana" size="-2">
          <input type="text" name="busca" size="10" maxlength="13">
          </font></p>
        <ord align="center"><font class="ord" face="verdana" size="-2"><a class="ord" href="lista.php?op=br">Recarga y/ò limpia</a></font>
        </p>
        <input type="hidden" name="pagina" value="1" >
        <input type="hidden" name="inicio" value ="1" >
      </form></td>
      <td width="7%"><div align="center"><a href="listae.php?busca=NUEVO&pagina=<? echo $pagina;?>"><img src="Imagenes/Agrega.gif" alt="Agrega nvo.registro" width="48" height="47" border="0"></a></div></td>
      <td width="58%"> <form name="form2" method="get" action=<?$_SERVER["PHP_SELF"]?>>
        <input type="hidden" name="op" value="ft">
        <input type="hidden" name="pagina" value=<?php echo $pagina;?>>
        <input type="hidden" name="orden" value=<?php echo $orden;?>>
        <input type="hidden" name="busca" value=<?php echo $busca;?>>
        <select name="Campo">
          <option value="CAMPOS">Campos</option>
          <option value="Cest.estudio">Estudio</option>
          <option value="Cest.descripcion">Descripcion</option>
          <option value="Nest.lt1">lista 1</option>
          <option value="Nest.lt2">Lista 2</option>
          <option value="Nest.lt3">Lista 3</option>
          <option value="Nest.lt4">Lista 4</option>
          <option value="Nest.lt5">Lista 5</option>
        </select>
        <select name="Signo">
          <option value="SIGNO">Signo</option>
          <option value="=">Igual à</option>
          <option value=">">Mayor à</option>
          <option value=">=">Mayor igual à</option>
          <option value="<">Menor à</option>
          <option value="<=">Menor igual à</option>
          <option value="<>">Diferente dè</option>
          <option value="like">Contenga</option>
        </select>
        <input type="text" name="Valor"  size="8" maxlength="15">
        <select name="Yo">
          <option value=""></option>
          <option value=" and ">y</option>
          <option value=" or ">&ograve;</option>
        </select>
        <input name="filtro" type="IMAGE" src="Imagenes/Genera.bmp" alt="Crea filtro" >
        <?php
        echo "<a href='abrefil.php?pagina=$pagina&orden=$orden&busca=$busca&Fil=$Tabla'> <img src='Imagenes/filtros.gif' alt='Abre filtros guardados' border='0'></a>";
   	    if($HTTP_SESSION_VARS['filtro_nvo']=='99999'){
	       echo "<a href=javascript:Ventana('guardafil.php')>&nbsp;<img src='Imagenes/guarda.gif' alt='guarda filtro' border='0'></a>";
	    }
	    ?>
      </form></td>
      <td width="24%">
	  <form name='form3' method='get' action='lista.php' onSubmit='return ValSuma();'>
        <p>
          <select name='SumaCampo'>
            <option value='CAMPOS'>Suma</option>
          </select>
          <input type='submit' name='Submit' value='Ok'>
        </p>
        <p>
          <input type="hidden" name="op" value="sm">
          <input type="hidden" name="pagina" value=<?php echo $pagina;?>>
          <input type="hidden" name="orden" value=<?php echo $orden;?>>
          <input type="hidden" name="busca" value=<?php echo $busca;?>>
        </p>
      </form></td>
  </tr>
</table>
<td width="416" valign="top">
</td>
</body>
</html>
<?
mysql_close();
?>
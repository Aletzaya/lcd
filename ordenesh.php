<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $orden=$_REQUEST[orden];

  $Usr=$check['uname'];

  $Titulo="Historico de ords.de estudio";

  $OrdenDef="oth.orden";            //Orden de la tabla por default

  $tamPag=15;

  $Tabla="oth";

  session_register("Tabla"); //Tabla a usar en los filtros

  $cSql="select oth.orden,oth.fecha,oth.fechae,oth.cliente,cli.nombrec,oth.importe,oth.status,oth.ubicacion,oth.institucion,oth.recepcionista from $Tabla,cli where $Tabla.cliente=cli.cliente ";
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
<script language="JavaScript1.2">dqm__codebase = "menu/script/"</script>
<script language="JavaScript1.2" src="menu/menu.js"></script>
<script language="JavaScript1.2" src="menu/script/tdqm_loader.js">
</script>
</head>
<body bgcolor="#FFFFFF">
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><div align="left"><a href="menu.php"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></div></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633FF">
    <td width="82%" bgcolor="#6633ff"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/dot.gif" alt="Sistema Clinico(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
    </td>
    <td width="18%" height="23"><div align="right"><script language="JavaScript1.2">generate_mainitems()</script>
</div></td></tr>
</table>
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
     if(substr($Campo,0,1)=='D' or $Signo=='like'){
       $Sig1="\'%";
       $Sig2="%\'";
     }elseif(substr($Campo,0,1)=='C'){
       $Sig1="\'";    // Esto es una comita p/k no me marque error or \"
       $Sig2="\'";
    }
    $Campo=substr($Campo,1);
    $lUp=mysql_query("insert into ftd (id,fil,campo,signo,sig1,valor,sig2,yo,usr) VALUES ('99999','$Tabla','$Campo','$Signo','$Sig1','$Sig1$Valor$Sig2','$Sig2','$Yo','$Usr')",$link);
    if($Yo==''){
      $filtro_nvo="99999";
      session_register("filtro_nvo");
    }
  }elseif($op=='br'){
     $lUp=mysql_query("delete from ftd where id='99999' and fil='$Tabla' ",$link);
     session_start();
     $filtro_nvo="";
     session_register("filtro_nvo");
  }

  if(strlen($HTTP_SESSION_VARS['filtro_nvo'])>0){
    $cWhe=" and  ";
    $Id=$HTTP_SESSION_VARS['filtro_nvo'];
    if($Id=='99999'){
        $ftdA=mysql_query("select * from ftd where id='99999' and fil='$Tabla' order by orden",$link);
    }else{
       $ftdA=mysql_query("select * from ftd where id='$Id' order by orden",$link);
    }
    while ($ftd=mysql_fetch_array($ftdA)){
       $cWhe=$cWhe.$ftd[campo]." ".$ftd[signo]." ".$ftd[valor]." ".$ftd[yo]." ";
    }
  }elseif(strlen($busca)>0){
    if($busca>0){
      $cWhe=" and orden >= '$busca'";
   }else{
      $cWhe=" and cli.nombrec like '%$busca%'";   //Sino trae nada pon esto
   }
  }
  if($op=='sm'){
     $cSumA="select sum($SumaCampo) from $Tabla,cli where $Tabla.cliente=cli.cliente ".$cWhe;
     $cSum=mysql_query($cSumA,$link);
     $Suma=mysql_fetch_array($cSum);
     $Funcion=" / $SumaCampo: ".number_format($Suma[0],"2");
  }
  //echo "$cSql $cWhe";
  if(!$res=mysql_query($cSql.$cWhe,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados ò hay un error en el filtro</font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
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
                if($numeroRegistros > 15){$limitInf = ($numeroRegistros - $tamPag);}
            }
        }
        $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
		$res=mysql_query($sql,$link);
		echo "<div align='center'>";
		echo "<img src='images/corner-bottom-left.gif' width='15' height='12'>";
		echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Ordenes de estudio, ordenados por ".$orden.$Funcion."</b></strong></font>";
		echo "<img src='images/corner-bottom-right.gif' width='15' height='12'>";
		echo "</div>";
		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td colspan='10'><hr noshade></td></tr>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=orden'>Orden</a></th>";
        echo "<th bgcolor='#6633FF'></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=fechae'>Fecha</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=fechae'>Fecha/Ent</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=cliente'>Cliente</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=nombre'>Nombre</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=importe'>Importe</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=status'>Status</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=institucion'>Inst</a></th>";
        echo "<th bgcolor='#6633FF'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=recepcionista'>Recep</a></th>";
		while($registro=mysql_fetch_array($res)) { ?>
        <tr bgcolor="#CEDADD" onMouseOver="this.style.backgroundColor='#E3DBFD';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CEDADD'"o"];">
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[orden]; ?></b></font></td>
        <td><a href=<?php echo 'ordeneshd.php?busca='.$registro[orden].'&pagina='.$pagina; ?>><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo Det; ?></b></font></a></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[fecha]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[fechae]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[3]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[4]; ?></b></font></td>
        <td align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[5],"2"); ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[status]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[institucion]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[recepcionista]; ?></b></font></td>
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
    if($inicio+14<$numPags){
        echo "&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($inicio+15)."&orden=".$orden."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>></font></a>";
        echo "&nbsp;&nbsp;&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($numPags-1)."&orden=".$orden."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>".">|"."</font></a>&nbsp;";
    }
    ?>
	</td></tr>
	</table>
<hr noshade style="color:66CC66;height:3px">
<table width="100%" border="0" background="images/fondo2.jpg">
  <tr bgcolor="#CEDADD">
    <td width="11%" height="86">
      <form name="form1" method="post" action=<?$_SERVER["PHP_SELF"]?>>
        <p align="center"><font color="#0066FF" face="verdana" size="-2"><strong>Busca:</strong></font></p>
        <p align="center"><font color="#0000FF" face="verdana" size="-2">
          <input type="text" name="busca" size="10" >
          </font></p>
        <ord align="center"><font class="ord" face="verdana" size="-2"><a class="ord" href="ordenes.php?op=br">Recarga y/ò limpia</a></font>
        </p>
        <input type="hidden" name="pagina" value="1" >
        <input type="hidden" name="inicio" value ="1" >
      </form></td>
      <td width="7%"><div align="center"><a href="ordenesnvas.php?busca=NUEVO&pagina=<? echo $pagina;?>&Vta=1"><img src="images/Agrega.gif" alt="Agrega nvo.registro" width="48" height="47" border="0"></a></div></td>
      <td width="58%"> <form name="form2" method="get" action=<?$_SERVER["PHP_SELF"]?>>
        <input type="hidden" name="op" value="ft">
        <input type="hidden" name="pagina" value=<?php echo $pagina;?>>
        <input type="hidden" name="orden" value=<?php echo $orden;?>>
        <input type="hidden" name="busca" value=<?php echo $busca;?>>
        <select name="Campo">
          <option value="CAMPOS">Campos</option>
          <option value="Noth.orden">Orden</option>
          <option value="Doth.fecha">Fecha</option>
          <option value="Doth.fechae">Fecha Ent</option>
          <option value="Noth.cliente">Paciente</option>
          <option value="Ccli.nombrec">Nombre</option>
          <option value="Noth.importe">Importe</option>
          <option value="Coth.status">Status</option>
          <option value="Noth.institucion">Institucion</option>
          <option value="Coth.recepcionista">Recepcionista</option>
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
        <input name="filtro" type="IMAGE" src="images/Genera.bmp" alt="Crea filtro" >
        <?php
        echo "<a href='abrefil.php?pagina=$pagina&orden=$orden&busca=$busca&Fil=$Tabla'> <img src='images/filtros.gif' alt='Abre filtros guardados' border='0'></a>";
   	    if($HTTP_SESSION_VARS['filtro_nvo']=='99999'){
	       echo "<a href=javascript:Ventana('guardafil.php')>&nbsp;<img src='images/guarda.gif' alt='guarda filtro' border='0'></a>";
	    }
	    ?>
      </form></td>
      <td width="24%">
	  <form name='form3' method='get' action='ordenes.php' onSubmit='return ValSuma();'>
        <p>
          <select name='SumaCampo'>
            <option value='CAMPOS'>Suma</option>
            <option value='Importe'>Importe</OPTION>
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
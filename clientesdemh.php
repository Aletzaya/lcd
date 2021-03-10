<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Titulo="Clientes de mayor demanda H";

  require("lib/kaplib.php");

  $link=conectarse();

  $OrdenDef="ordenes";            //Orden de la tabla por default

  $orden=$_REQUEST[orden];

  $tamPag=15;

  $Tabla="oth";

  $Fecha=date("Y-m-d");

  session_register("Tabla"); //Tabla a usar en los filtros

  $op=$_REQUEST[op];

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $cSql="select count(oth.orden) as ordenes,oth.cliente,sum(oth.importe) from $Tabla group by cliente ";

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
<script language="JavaScript1.2" src="menu/script/tdqm_loader.js"></script>
<script language="JavaScript1.2">
function Ventana(url){
   window.open(url,"demcli","width=500,height=500,left=100,top=0,location=no,dependent=yes,resizable=yes");
}
</SCRIPT>

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
  if($op=='br'){
     $lUp=mysql_query("delete from ftd where id='99999' and fil='$Tabla' ",$link);
     $_SESSION['filtro_nvo']="";
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
        $sql=$cSql.$cWhe." ORDER BY ".$orden." DESC LIMIT ".$limitInf.",".$tamPag;
		$res=mysql_query($sql,$link);
		echo "<div align='center'>";
		echo "<img src='images/corner-bottom-left.gif' width='15' height='12'>";
		echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Cliente con mayor demanda</b></strong></font>";
		echo "<img src='images/corner-bottom-right.gif' width='15' height='12'>";
		echo "</div>";
		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td colspan='10'><hr noshade></td></tr>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Ordenes abiertas</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>No.Cliente</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Importe</font></th>";
		while($registro=mysql_fetch_array($res)){  ?>
          <tr bgcolor="#CEDADD" onMouseOver="this.style.backgroundColor='#E3DBFD';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CEDADD'"o"];">
          <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[ordenes]; ?></b></font></a></td>
          <td align='right'><a href="javascript:Ventana('demclih.php?busca=<?php echo $registro[1];?>')"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[1]; ?></b></font></td>
          <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[2],"2"); ?></b></font></td>
         </tr>
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
        echo "&nbsp;&nbsp;&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($numPags)."&orden=".$orden."&busca=".$busca."'>";
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
          <option value="Not.orden">Orden</option>
          <option value="Dot.fecha">Fecha</option>
          <option value="Dot.fechae">Fecha Ent</option>
          <option value="Not.cliente">Paciente</option>
          <option value="Ccli.nombrec">Nombre</option>
          <option value="Not.importe">Importe</option>
          <option value="Cot.status">Status</option>
          <option value="Not.institucion">Institucion</option>
          <option value="Cot.recepcionista">Recepcionista</option>
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
        <input type="text" name="Valor" value=<? echo $Fecha;?> size="10" maxlength="15">
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
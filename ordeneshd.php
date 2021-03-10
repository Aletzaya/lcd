<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $Usr=$check['uname'];

  $Msj="";
  $Fecha=date("Y-m-d");
  $Hora = date("h:i:s");

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $op=$_REQUEST[op];

  $Titulo="Detalle de la orden de estudio [$busca] Historico";

  $OrdenDef="otdh.estudio";            //Orden de la tabla por default
  $Tabla="otdh";
  session_register("Tabla"); //Tabla a usar en los filtros
  $cSqlA=mysql_query("select sum(importe) from cja where orden='$busca'",$link);
  $SqlS=mysql_fetch_array($cSqlA);
  $cSqlH="select oth.orden,oth.fecha,oth.fechae,oth.cliente,cli.nombrec,oth.importe,oth.ubicacion,oth.institucion,oth.medico,med.nombrec,oth.status,oth.recibio from oth,cli,med where oth.cliente=cli.cliente and oth.medico=med.medico and oth.orden='$busca'";
  $cSqlD="select otdh.estudio,otdh.status,est.descripcion,otdh.precio,otdh.descuento,est.muestras,otdh.etiquetas from otdh,est where otdh.estudio=est.estudio and otdh.orden='$busca'";
  $HeA=mysql_query($cSqlH,$link);
  $He=mysql_fetch_array($HeA);
  if($op='St'){
     if($Status=="Entregada"){
        if(($SqlS[0]+.5) >= $He[5]){    //Listo si la puede entregar, por k si esta pagada
           if($Recibio==""){
              $Msj='Favor de poner quien recibe el estudio';
           }else{
              $lUp=mysql_query("update ot set status='$Status',recibio='$Recibio',entusr='$Usr',entfec='$Fecha',enthra='$Hora' where orden='$busca'",$link);
              $HeA=mysql_query($cSqlH,$link);
              $He=mysql_fetch_array($HeA);
           }
        }else{
           $Msj='Aun hay Saldo por liquidar, No se puede entregar!';
        }
     }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style type="text/css">
<!--
a.ord:link {
    color: #66CC66;
    text-decoration: none;
}
a.ord:visited {
    color: #66CC66;
    text-decoration: none;
}
a.ord:hover {
    color: #000099;
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
    <td width="14%" height="59"><div align="left"><a href="ordenes.php"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></div></td>
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
    <td width="18%" height="23"><div align="right">
     <script language="JavaScript1.2">generate_mainitems()</script>
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

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=800,height=350,left=100,top=150")
}
</script>
<hr noshade style="color:000099;height:2px">
<?
  if($op=='sm'){
     $cSumA="select sum($SumaCampo) from $Tabla,cli where $Tabla.cliente=cli.cliente ".$cWhe;
     $cSum=mysql_query($cSumA,$link);
     $Suma=mysql_fetch_array($cSum);
     $Funcion=" / $SumaCampo: ".number_format($Suma[0],"2");
  }
   echo "<table align='center' width='90%' background='lib/fondo.gif' >";
   echo "<div align='center'><font color='#000099'><strong>DETALLE POR ODEN DE ESTUDIOS[HISTORICO]</strong></font></div>";
   echo "<td><font color='#0066FF' size='3' >";
   echo "<p align='center'><font color='#660066'>No.Orden : </font> $busca &nbsp;&nbsp;&nbsp; $He[nombre]  <font color='#660066'>Cliente:</font> $He[cliente] $He[4]";
   echo "<font color='#660066'>&nbsp;&nbsp;&nbsp;Fecha : </font> $He[fecha]<font color='#660066'>&nbsp;&nbsp;&nbsp;Fecha/entrega : </font> $He[fechae]</p>";
   echo "<p align='center'><font color='#660066'>Medico: </font>$He[medico] $He[9] <font color='#660066'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Importe : $ </font> ".number_format($He[importe],"2")."<font color='#660066'>&nbsp;&nbsp;&nbsp; Abonado : $</font> ".number_format($SqlS[0],'2');
   echo "&nbsp;&nbsp;&nbsp;<font color='#660066'>Saldo : </font>".number_format($He[importe]-$SqlS[0],"2")."&nbsp;&nbsp;&nbsp;[";
   echo "<a class='ord' href='ingreso.php?busca=$busca&pagina=$pagina'>NVO/INGRESO</a>]</p>";
   echo "<form name='form1' method='post' action='ordenesd.php?busca=$busca&pagina=$pagina&op=St'>";
      echo "<font color='#660066'>&nbsp;&nbsp;&nbsp;Status : </font> ";
      echo "<select name='Status'>";
      echo "<option value='Pagada'>Pagada</option>";
      echo "<option value='Pendiente'>Pendiente</option>";
      echo "<option value='Entregada'>Entregada</option>";
      echo "<option selected value = $He[status]>$He[status]</option>";
      echo "</select>";
      echo "<font color='#660066'>&nbsp;&nbsp;&nbsp;Recibo de resultados : </FONT> <input name='Recibio' value='$He[recibio]' type='text' maxlength='25' size='20' onBlur=Mayusculas('Recibio')>";
      echo "&nbsp;&nbsp;&nbsp;<input type='submit' name='Submit' value='Ok'>";
      echo "<font color='#990000' size='1'>$Msj</font>";
   echo "</form></font></td></table>";
   if(!$res=mysql_query($cSqlD." ORDER BY ".$OrdenDef,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{
      echo "<br>";
      $numeroRegistros=mysql_num_rows($res);
		echo "<div align='center'>";
		echo "<img src='images/corner-bottom-left.gif' width='15' height='12'>";
		echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Estudios</b></strong></font>";
		echo "<img src='images/corner-bottom-right.gif' width='15' height='12'>";
		echo "</div>";
		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td colspan='6'><hr noshade></td></tr>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Estudio</font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Descripcion</font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>#Muestras</font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>#Imp</font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'> - </font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Status</font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Precio</font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>%Dto</font></th>";
      echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Importe</font></th>";
	  while($registro=mysql_fetch_array($res))		{  ?>
        <tr bgcolor="#CEDADD" onMouseOver="this.style.backgroundColor='#E3DBFD';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CEDADD'"o"];">
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[estudio]; ?></b></font></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[descripcion]; ?></b></font></td>
        <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[muestras]; ?></b></font></td>
        <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[etiquetas],'2'); ?></b></font></td>
        <td><a href=javascript:Ventana(<?php echo "'impeti.php?op=1&busca=".$busca."&Est=$registro[estudio]'";?>)><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b>Imp</b></font></a></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[status]; ?></b></font></td>
        <td align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[precio],"2"); ?></b></font></td>
        <td align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[descuento],"2"); ?></b></font></td>
        <td align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[precio]*(1-$registro[descuento]/100),"2"); ?></b></font></td>
        </tr>
        <?
		}//fin while
		echo "</table>";
	}//fin if
    ?>
<p>&nbsp;</p>
<p><div align="center"><a href="ordenesh.php?pagina=<? echo $pagina;?>"><img src="images/SmallExit.bmp" border="0"></a></div><br>
<hr noshade style="color:66CC66;height:5px">
</p>
</body>
</html>
<?
mysql_close();
?>
<?php
  session_start();

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

  $Msj="";

  $Fecha=date("Y-m-d");

  $Hora = date("H:i");

  $Titulo="Ordenes en Pre-Analiticos";

  $OrdenDef="ot.fecha, ot.hora";            //Orden de la tabla por default

  $SqlA="select cli.nombre,ot.orden,ot.fecha,ot.hora from ot,cli where ot.status='PRE-A' and ot.cliente=cli.cliente ";
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
<script language="JavaScript1.2" src="menu/script/tdqm_loader.js"></script>
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
    <td width="82%" bgcolor="#6633ff"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
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

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}
</script>
<hr noshade style="color:000099;height:2px">
<?
   //echo $SqlA." ORDER BY ".$OrdenDef;
   if(!$res=mysql_query($SqlA." ORDER BY ".$OrdenDef,$link)){
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
		echo "<tr><td colspan='7'><hr noshade></td></tr>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Paciente</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Orden</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Fecha</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Hora</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Status</font></th>";
		  while($registro=mysql_fetch_array($res)){ ?>
          <tr bgcolor="#CEDADD" onMouseOver="this.style.backgroundColor='#E3DBFD';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CEDADD'"o"];">
          <td><a href=<?php echo 'ordenespree.php?busca='.$registro[1]; ?>><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[0]; ?></b></font></a></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[1]; ?></b></font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[2]; ?></b></font></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[3]; ?></b></font></td>
          <td align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[6]; ?></b></font></td>
          </tr>
        <!-- fin tabla resultados -->
        <?
		}//fin while
		echo "</table>";
	}//fin if
    ?>
<p>&nbsp;</p>
<p><div align="center"><a href="ordenes.php?pagina=<? echo $pagina;?>"><img src="images/SmallExit.bmp" border="0"></a></div><br>
<hr noshade style="color:66CC66;height:5px">
</p>
</body>
</html>
<?
mysql_close();
?>
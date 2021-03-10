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

  $Orden=$_REQUEST[Orden];

  $Op=$_REQUEST[Op];

  if($Op=="El"){    //Reporte de corte de caja

     $Fecha=date("Y-m-d");
     $Fecha=strtotime($Fecha);             //Convierto la fecha a Numeros

	 $FecNum=strtotime("-90 days",$Fecha);   //Le quito a Fecha tipo numero 90 dias, puede ser days month years y hasta -1 month menos un mes...
     $FechaI=date("Y-m-d",$FecNum);          //Convierto El resultado en Tipo Fecha

     //COPIA LA BASE DA DATOS
     $cSql="INSERT INTO otdh SELECT otd.orden, otd.estudio, otd.precio, otd.descuento, otd.libre1, otd.status, otd.etiquetas
     FROM otd, ot WHERE otd.orden = ot.orden AND ot.orden <= '$Orden' ";
     $res=mysql_query($cSql,$link);

     $cSql="insert into oth select * from ot where ot.orden <= '$Orden'";
     $res=mysql_query($cSql,$link);

     //$res=mysql_query("SELECT orden FROM oth ORDER BY `orden` DESC LIMIT 0 , 1",$link);
     //$ftd=mysql_fetch_array($res);
     //$Orden=$ftd[0];      // Tomo la ultima orden para eliminar apartir de este

     $cSql="insert into resulh select * from resul where orden <= '$Orden'";
     $res=mysql_query($cSql,$link);   //EL prob.de borrar esto es k ya no se van a reimprimir en el historico

     //BORRA LA BASE DA DATO
     $cSql="delete from otd WHERE orden <= '$Orden' ";
     $res=mysql_query($cSql,$link);

     $cSql="delete from ot where orden <= '$Orden'";
     $res=mysql_query($cSql,$link);

     $cSql="delete from resul where orden <= '$Orden'";
     $res=mysql_query($cSql,$link);

     header("Location: menu.php");

  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
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
    <td width="82%" bgcolor="#6633ff"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="imagenes/dot.gif" alt="Sistema Clinico(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
    </td>
    <td width="18%" height="23"><div align="right"><script language="JavaScript1.2">generate_mainitems()</script>
</div></td></tr>
</table>
<hr noshade style="color:3366FF;height:2px">
<P>&nbsp;</P>
<DIV ALIGN="center">
<font color='#660066' size='3' >
<?php
     echo "<form name='form1' method='get' action='pasahistorico.php'>";
        echo "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#0066FF'>";
        echo "<p>Transferencia de ordenes a historico</p><br>";

        echo "Se pasara a historico las ordenes que sean menor igual a : &nbsp; ";

        echo "<INPUT TYPE='TEXT'  name='Orden' size='5' value =''>";
        echo "<INPUT TYPE='HIDDEN'  name='Op' value='El'>";
        echo "</font><br><br>";
        echo "<INPUT TYPE='SUBMIT' value='Ok'>";
    echo "</form>";
?>
</font>
</DIV>
</body>
</html>
<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $op=$_REQUEST[op];

  $Tabla="depd";

  $Titulo="Detalle de subdepartamentos";

  if($_REQUEST[Guarda_x] > 0){		//Para agregar uno nuevo
      if($busca=='NUEVO'){
        $lUp=mysql_query("insert into $Tabla (departamento,subdepto,nombre) VALUES ('$_REQUEST[Depto]','$_REQUEST[Subdepto]','$_REQUEST[Nombre]]')",$link);
 	  }else{
        $lUp=mysql_query("update $Tabla SET nombre='$_REQUEST[Nombre]' where departamento='$_REQUEST[Depto]' and subdepto='$busca' limit 1",$link);
 	  }
      header("Location: deptod.php?busca=$Depto&pagina=$pagina");
  }elseif($_REQUEST[Elimina_x]>0){    // Para dar de baja
      $lUp=mysql_query("delete from $Tabla where departamento='$_REQUEST[Depto]' and subdepto='$busca' limit 1",$link);
      header("Location: deptod.php?busca=$Depto&pagina=$pagina");
  }
  //echo "$cSql";
  $cSqlH="select * from dep where departamento='$_REQUEST[Depto]'";
  $cSql="select * from depd where departamento='$_REQUEST[Depto]' and subdepto='$busca'";
?>
<html>
<head>
<title><?php echo $Titulo;?></title>
<script language="JavaScript1.2">dqm__codebase = "menu/script/"</script>
<script language="JavaScript1.2" src="menu/menu.js"></script>
<script language="JavaScript1.2" src="menu/script/tdqm_loader.js">
</script>
</head>
<body bgcolor="#FFFFFF" onload="cFocus()">
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><a href="deptod.php?busca=<? echo $Depto;?>&pagina=<?echo $pagina;?>"><img src="lib/logo2.jpg" width="100" height="80" border="0"></a></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50"></div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%"><div align="right"></div></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633ff">
    <td width="86%"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
   </td>
    <td width="14%" height="24">
<div align="right"></div></td>
  </tr>
</table>
<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}
function SiElimina(){
  if(confirm("ATENCION! Desea dar de Baja este registro?")){
     return(true);
   }else{
     document.form1.busca.value="NUEVO";
      return(false);
   }
}


function Completo(){
var lRt;
lRt=true;
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
if (cCampo=='Subdepto'){document.form1.Subdepto.value=document.form1.Subdepto.value.toUpperCase();}
}
</script>
<hr noshade style="color:3366FF;height:2px">
<?
  $HeA=mysql_query($cSqlH,$link);
  $He=mysql_fetch_array($HeA);
  echo "<table align='center' width='80%' background='lib/fondo.gif' >";
  echo "<div align='center'><font color='#000099'><strong>DETALLE POR DEPARTAMENTO</strong></font></div>";
  echo "<td><font color='##0066FF' size='3' >";
  echo "<div align='center'><font color='#660066'>Departamento: $busca </font> $He[nombre]</div><br> ";
  //"&nbsp;&nbsp;<font color='#660066'>Fecha: </font> $He[fecha] &nbsp;&nbsp;<font color='#660066'>Hora: </font> $He[hora]&nbsp;&nbsp;<font color='#660066'>Concepto: </font> $He[concepto] &nbsp;&nbsp;&nbsp;<font color='#660066'>Status: </font> $He[status]</div><br>";
  //echo "<div align='center'><font color='#660066'>Proveedor: </font>$He[proveedor] $He[10] &nbsp;&nbsp; <font color='#660066'>No.Documento: </font> $He[documento] &nbsp;&nbsp;&nbsp;<font color='#660066'> No.Movto.: </font> $He[movto] </div>";
  echo "</font></td></table>";
  $HeA=mysql_query($cSql,$link);
  $Cpo=mysql_fetch_array($HeA);
?>
<table width="973" height="325" border="0">
  <tr>
    <td><div align="center">
        <p>&nbsp;</p>
        <p><a href="deptod.php?busca=<? echo $Depto;?>&pagina=<? echo $pagina;?>"><img src="images/SmallExit.bmp" border="0"></a></p>
      </div></td>
  <td>
   <form name='form1' method='get' action='deptode.php' onSubmit="return Completo();" >
        <font color='#000099' size='3'>
        <p>Subdepartamento..........:
        <input type="text" size="10" name="Subdepto"  value ='<?php echo $Cpo[subdepto]; ?>' onBlur="Mayusculas('Subdepto')"></p>
   	    <p>Nombre........................:
		<input name=Nombre type=text size=30 value='<?php echo $Cpo[nombre];?>' onBlur=Mayusculas('Nombre')></p>
        <input type="IMAGE" name="Guarda" src="images/guarda.gif" alt="Guarda los ultimos movimientos y salte" >&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="IMAGE" name="Elimina" src="images/elimina.gif" alt="Elimina este registro" onClick="SiElimina()">&nbsp;&nbsp;&nbsp;&nbsp;
        <input type='Reset' value='Recupera'>
        <input type="hidden" name="orden" value=<?php echo $orden; ?>>
        <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
        <input type="hidden" name="busca" value=<?php echo $busca; ?>>
        <input type="hidden" name="Depto" value=<?php echo $Depto; ?>>
         <p>&nbsp; </p>
         </font>
	</form>
  </td>
  </tr>
  <tr>
    <td width="136" height="59">&nbsp;</td>
    <td width="768"><p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="55">&nbsp;</td>
  </tr>
</table>
<hr noshade style="color:FF0000;height:3px">
<td width="416" valign="top">
</td>
</body>
</html>
<?
mysql_close();
?>
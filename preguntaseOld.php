<?
$Tabla="cue";
$Titulo="Detalle del cuestionario";
$cSql="select * from $Tabla where id= '$busca'";
require("aut_verifica.inc.php");
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
$Usr=$HTTP_SESSION_VARS['usuario_login'];
include("lib/kaplib.php");
$link=conectarse();
$fin=0;
if($Guarda_x > 0){
   if($busca=='NUEVO'){
      $lUp=mysql_query("insert into $Tabla (pregunta) VALUES ('$Pregunta')",$link);
   }else{
      $lUp=mysql_query("update $Tabla SET pregunta='$Pregunta' where id='$busca' limit 1",$link);
   }
   header("Location: preguntas.php");
}elseif($Elimina_x > 0){
   $lUp=mysql_query("delete from $Tabla where id='$busca'",$link);
   header("Location: preguntas.php");
}
else{
   $CpoA=mysql_query($cSql,$link);
   $Cpo=mysql_fetch_array($CpoA);
   $lAg=$busca=='NUEVO';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<script language="JavaScript1.2">
function cFocus(){
  document.form1.Pregunta.focus();
}

function SiElimina(){
if(confirm("ATENCION!\r Desea dar de Baja este registro?")){
   return(true);
}else{
   document.form1.busca.value='NUEVO';
   return(false);
}
}
</script>
<link href="lib/texto.css" rel="stylesheet" type="text/css">
<body onload="cFocus()">
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;">
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76">
      <div align="center">
      <?php
      echo "<a class='p' href='preguntas.php'><img src='lib/logo2.jpg' alt='Regresar' width='100' height='80' border='1'></a>";
      ?>
      </div></td>
      <td width="70%">
        <p align="center"><font color="#FFFFFF"><strong><?php echo $Titulo;?></strong></font></p>
        <p align="center">&nbsp;</p>
      </td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>
</div>
<div align="center" id="Layer2" style="position:absolute; left:10px; top:100px; width:958px; height:352px; z-index:2">
   <br>
   <FORM name="form1" action="preguntase.php">
     <p><font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
     Id : <?php echo $busca;?></p>
     <p>Pregunta: <INPUT TYPE="TEXT"  name="Pregunta" size="40" value="<?php if(!$lAg){echo $Cpo[pregunta];} ?>"></font></p>
     <p>&nbsp;</p>
     <p>&nbsp;</p>
     <p>&nbsp;</p>
     <p>
     <input type="hidden" name="busca" value=<?php echo $busca; ?>>
     <input type="IMAGE" name="Guarda" src="lib/guardar.jpg" alt="Guarda los ultimos movimientos y salte" width="150" height="25" >
     &nbsp;&nbsp;
     <input type="IMAGE" name="Elimina" src="lib/eliminar.jpg" alt="Elimina este registro" onClick="SiElimina()" width="150" height="25">
     </p>
   </FORM>
</div>
<div id="Layer4" style="position:absolute; left:8px; top:459px; width:960px; height:83px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;">
  <table width="100%" height="102" border="0">
  </table>
</div>
</body>
</html>
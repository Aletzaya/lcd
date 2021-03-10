<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catalogo de zonas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
function EliReg(){
   if(confirm("ATENCION!\r Desea dar de Baja este registro?")){
	  return(true);	   
   }else{
      document.form1.cKey.value='NUEVO';
   	  return(false);
   }
} 
function ValDato(cCampo){//Aqui mando el nombre del campo poro como es 1 campo a validar pues ya lo hago directo
document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();
}
//-->
</script>
</head>
<body>
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="depto.php?busca=<?php echo $cKey; ?>"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="1"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><img src="lib/deptos.jpg" alt="Catalogo de departamentos" width="150" height="25"></p>
        <p align="center">&nbsp;</p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:11px; top:99px; width:958px; height:303px; z-index:2"> 
  <table width="100%" height="353" border="0">
    <tr> 
      <td width="23%" height="155"> <div align="center">
<p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div></td>
      <td width="0%">&nbsp;</td>
      <td width="77%">
	    <form name="form1" method="get" action="movdep.php" >
          <strong> 
          <?php
		  	include("lib/kaplib.php");
		    $link=conectarse();
	        $tabla="dep";
   	        $cReg=mysql_query("select departamento,nombre from $tabla where (departamento= '$cKey')",$link);
	        $cCpo=mysql_fetch_array($cReg);
	        $lAg=$cKey=='NUEVO';
		    echo "<p>Departamento : $cKey</P>";
		    echo "<p>Nombre :";
			echo "<input type=text name=Nombre  value = '$cCpo[1]' size=25 onBlur='ValDato()'></p>";
			?>
          </strong> 
          <p>&nbsp;</p>
          <p>
            <input type="hidden" name="busca" value=<?php echo $busca; ?>>
            <input type="hidden" name="cKey" value=<?php echo $cKey; ?>>
            <input type="IMAGE" name="Guarda" src="lib/guardar.jpg" alt="Guarda los ultimos movimientos y salte" width="150" height="25" >
            &nbsp;&nbsp; &nbsp;&nbsp; 
            <input type="IMAGE" name="Elimina" src="lib/eliminar.jpg" alt="Elimina este registro" onClick="EliReg()" width="150" height="25">
          </p>
        </form>
		<p>&nbsp;<a href="depto.php"><img src="lib/cancelar.jpg" width="150" height="25" border="0"></a></p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
<div id="Layer4" style="position:absolute; left:13px; top:452px; width:960px; height:54px; z-index:4;"> 
  <table width="100%" height="42" border="0">
    <tr> 
      <td width="16%" height="38"> 
        <div align="center"><a href="depto.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a> 
        </div></td>
      <td width="84%">
        <p>&nbsp;</p></td>		
    </tr>
  </table>
</div>
</body>
</html>

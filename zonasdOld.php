<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Localidades por zona</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function ValDato(cCampo){
if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();}
if (cCampo=='Observaciones'){document.form1.Observaciones.value=document.form1.Observaciones.value.toUpperCase();}
}
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>
<body>
<?php
include("lib/kaplib.php");
$link=conectarse();
$result=mysql_query("select descripcion from zns where zona='$cKey'",$link);
$Nom=mysql_fetch_array($result)
?>	
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:882px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr> 
      <td width="12%" height="76"><div align="center"><a href="zonas.php"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="0"></a></div></td>
      <td width="44%"><div align="center"><img src="lib/zonas.jpg" alt="Catalogo de zonas" width="150" height="25"></div></td>
      <td width="32%"> <p align="left"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Localidades 
          de la Zona: <?php echo $cKey;?></strong></font></p>
        <p align="left"><strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $Nom[0];?></font></strong></p>
	  </td>
      <td width="12%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:10px; top:100px; width:883px; height:352px; z-index:2"> 
  <table width="100%" >
    <tr bgcolor="#0066FF">
      <td width="12%" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="44%"><font color="#FFFFFF">Localidad</font></td>
      <td width="44%"><font color="#FFFFFF">Observaciones</font></td>
    </tr>
    <?php
		$cSql="select zona,localidad,observacion from znsd where zona='$cKey'";
     	$result=mysql_query($cSql,$link);
		$PriPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
		    if($lBd){
 			   printf("<tr><td><a href='movznsde.php?cKey=$cKey&Localidad=$row[1]'>%s</a></td><td>%s</td><td>%s</td>",'Elimina',$row[1],$row[2]);
			   $lBd=false;
			}else{
 			   printf("<tr><td bgcolor =\"CCCCFF\"><a href='movznsde.php?cKey=$cKey&Localidad=$row[1]'>%s</a></td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td>",'Elimina',$row[1],$row[2]);
			   $lBd=true;
			}
			   
		}
		mysql_free_result($result);
		mysql_close($link);
		?>
  </table>
</div>
<div id="Layer4" style="position:absolute; left:8px; top:459px; width:960px; height:83px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="102" border="0">
    <tr> 
      <td width="8%" height="94"> <div align="center"> 
          <p><a href="zonas.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
          <p>&nbsp;</p>
        </div></td>
      <td width="19%"> <p align="left">&nbsp;</p>
        <p>&nbsp;</p></td>
      <form name="form1" method="post" action="movznsd.php">
        <td width="69%"><p align="left"> <em><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Localidad</strong></font></em> 
            <strong><font color="#FFFFFF"> 
            <input type="hidden" name="cKey" value=<?php echo $cKey; ?>>
            <input name="Localidad" type="text" size="20" onBlur="ValDato('Localidad')" >
            Observaciones</font> <font color="#FFFFFF"> 
            <input type="text" name="Observaciones" onBlur="ValDato('Observaciones')"  >
            <input type="submit" name="Submit" value="Agregar">
            </font></strong></p>
          <p>&nbsp; </p></td>
      </form>
      <td width="4%"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>

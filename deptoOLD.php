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
//-->
</script>
</head>

<body>

<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="catalogos.php"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="1"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><img src="lib/deptos.jpg" alt="Catalogo de colores" width="150" height="25"></p>
        <p align="center">&nbsp;</p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:10px; top:100px; width:958px; height:352px; z-index:2"> 
  <table width="100%" >
    <tr bgcolor="#0066FF"> 
      <td width="7%" bgcolor="#FFFFFF">&nbsp;</td>
      <td width="9%"><font color="#FFFFFF">Departamento</font></td>
      <td width="53%"><font color="#FFFFFF">Nombre</font></td>
    </tr>
    <?php
		include("lib/kaplib.php");
		$link=conectarse();
		$cSql="select departamento,nombre from dep ";
	    $result=mysql_query($cSql."order by departamento limit 0,15",$link);
		$PriPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($PriPro==""){$PriPro=$row[0];}
			if($lB){
 			   printf("<tr><td><a href='deptod.php?cKey=$row[0]'>%s</a></td><td><a href='deptoe.php?cKey=$row[0]'>%s</a></td><td>%s</td>",'Detalle',$row[0],$row[1]);
			   $lB=false;
			}ELSE{
		       printf("<tr><td bgcolor=\"CCCCFF\"><a href='deptod.php?cKey=$row[0]'>%s</a></td> <td bgcolor =\"CCCCFF\"><a href='deptoe.php?cKey=$row[0]'>%s</a></td><td bgcolor=\"CCCCFF\">%s</td>",'Detalle',$row[0],$row[1]);
			   $lB=true;
			}   
			$nRen=$nRen+1;
			$UltPro=$row[0];
		}
		mysql_free_result($result);
		mysql_close($link);
		?>
  </table>
</div>
<div id="Layer4" style="position:absolute; left:8px; top:459px; width:960px; height:83px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="102" border="0">
    <tr> 
      <td width="4%" height="94"> <div align="center">
          <p><a href="catalogos.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
          <p>&nbsp;</p>
        </div></td>
      <td width="15%"> <p align="left"><a href="deptoe.php?cKey=NUEVO"><img src="lib/nuevo.bmp" alt="Agrega un nuevo departamento" border="0"></a></p>
        <p>&nbsp;</p></td>		
	    <td width="6%">
        <p align="center">&nbsp;</p>
        <p>&nbsp;</p></td>
      <td width="51%"> 
	  </td>
      <td width="9%"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>

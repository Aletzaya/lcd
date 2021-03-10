<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catalogo de instituciones</title>
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
        <p align="center"><img src="lib/estinst.jpg" alt="Catalogo de colores" width="150" height="25"></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:10px; top:100px; width:958px; height:352px; z-index:2"> 
  <table width="100%">
    <tr bgcolor="#0066FF"> 
      <td width="6%"><font color="#FFFFFF">Institucion</font></td>
      <td width="34%"><font color="#FFFFFF">Descripcion</font></td>
    </tr>
    <?php
		include("lib/kaplib.php");
		$link=conectarse();
		$cSql="select institucion,nombre from inst ";
		$cOrd=" order by institucion";
		if($busca==-1){                 // Vete hasta el final del Archivo
		   	$result=mysql_query($cSql,$link);
  			$fin=mysql_num_rows($result)-15;
			$result=mysql_query($cSql." order by institucion limit $fin,15",$link);
		}elseif(substr($busca,0,1)=='@'){   // Vete 15 Antes del Modelo inicial
		    $busca=substr($busca,1);
		   	$result=mysql_query($cSql."where institucion <= '$busca'",$link);
  			$fin=mysql_num_rows($result)-14;
 			$result=mysql_query($cSql."where institucion <= '$busca' ".$cOrd." limit $fin,15",$link);
		}elseif($busca<>""){   
		   if(strlen($busca)<=2){
		       $result=mysql_query($cSql."where institucion >= '$busca' order by institucion limit 0,15",$link);
		   }else{
		       $result=mysql_query($cSql."where nombre like '%$busca%' order by nombre limit 0,15",$link);
		   }	   
        }else{
     	    $result=mysql_query($cSql."order by institucion limit 0,15",$link);
		}
		$PriPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($PriPro==""){$PriPro=$row[0];}
			if($lBd){
			  printf("<tr><td><a href='instd.php?cKey=$row[0]'>%s</a></td><td>%s</td>",$row[0],$row[1]);
			  $lBd=false;
			}else{  
			  printf("<tr><td bgcolor=\"CCCCFF\"><a href='instd.php?cKey=$row[0]'>%s</a></td><td bgcolor=\"CCCCFF\">%s</td>",$row[0],$row[1]);
			  $lBd=true;
			}  
			$nRen=$nRen+1;
			$UltPro=$row[0];
		}
		if(substr($busca,0,1)=="*"){printf("<tr><td>%s</td><td>%s</td>",$row[0],'Total No.renglones '.number_format($nRen,"0"));}
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
      <td width="15%">
        <p align="left"><a href="estinstitucion.php?busca="><img src="lib/IniRec.bmp" alt="Ir al inicio del archivo" border="0"></a><a href="estinstitucion.php?busca=<?php echo '@'.$PriPro; ?>"><img src="lib/AntRec.bmp" border="0" alt="Pagina anterior"></a><a href="estinstitucion.php?busca=<?php echo $UltPro;?>"><img src="lib/SigRec.bmp" border="0" alt="Pagina siguiente"></a><a href="estinstitucion.php?busca=-1"><img src="lib/UltRec.bmp" border="0" alt="Ir al final del archivo"></a></p>
        <p>&nbsp;</p></td>		
	    <form name="form1" method="post" action="institu.php">
        <td width="15%"><p align="left">&nbsp; </p>
        <p>&nbsp; </p></td>
		</form>
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

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catalogo de estudios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MostrarVentana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=350,height=320,left=250,top=250")
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
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:852px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="catalogos.php"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="0"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><img src="lib/precios.jpg" alt="Catalogo de colores" width="150" height="25"></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:10px; top:100px; width:853px; height:352px; z-index:2"> 
  <table width="100%">
    <tr bgcolor="#0066FF"> 
      <td width="5%"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Estudio</font></td>
      <td width="45%"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Descripcion</font></td>
      <td width="5%" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=1')">1</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=2')">2</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=3')">3</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=4')">4</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=5')">5</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=6')">6</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=7')">7</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=8')">8</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=9')">9</a></font></td>
      <td width="5%" bgcolor="#FFFFFF"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="javascript:MostrarVentana('lisins.php?cKey=10')">10</a></font></td>
    </tr>
    <?php
		include("lib/kaplib.php");
		$link=conectarse();
		$cSql="select estudio,descripcion,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10 from est ";
		if($busca<>""){  
		   if(strlen($busca)<=5){
		       $result=mysql_query($cSql."where estudio >= '$busca' order by estudio limit 0,15",$link);
		   }else{
		       $result=mysql_query($cSql."where descripcion like '%$busca%' order by descripcion limit 0,15",$link);
		   }	   
        }else{
     	    $result=mysql_query($cSql."order by estudio limit 0,15",$link);
		}
		$PriPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($PriPro==""){$PriPro=$row[0];}
			if($lBd){
			   printf("<tr><td><a href='listae.php?cKey=$row[0]'>%s</a></td><td>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right>%s</td><td align=right>%s</td align=right><td align=right>%s</td>",$row[0],$row[1],number_format($row[2],"2"),number_format($row[3],"2"),number_format($row[4],"2"),number_format($row[5],"2"),number_format($row[6],"2"),number_format($row[7],"2"),number_format($row[8],"2"),number_format($row[9],"2"),number_format($row[10],"2"),number_format($row[11],"2"));
			   $lBd=false;
			}else{
			   printf("<tr><td bgcolor =\"CCCCFF\"><a href='listae.php?cKey=$row[0]'>%s</a></td><td  bgcolor =\"CCCCFF\" >%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td><td align=right bgcolor =\"CCCCFF\">%s</td>",$row[0],$row[1],$row[2],$row[3],$row[4],number_format($row[5],"2"),number_format($row[6],"2"),number_format($row[7],"2"),number_format($row[8],"2"),number_format($row[9],"2"),number_format($row[10],"2"),number_format($row[11],"2"));
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
<div id="Layer4" style="position:absolute; left:8px; top:459px; width:856px; height:83px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="102" border="0">
    <tr> 
      <td width="4%" height="94"> <div align="center">
          <p><a href="catalogos.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
          <p>&nbsp;</p>
        </div></td>
      <td width="15%">
        <p align="left"><a href="lista.php?busca="><img src="lib/IniRec.bmp" alt="Ir al inicio del archivo" border="0"></a><a href="lista.php?busca=<?php echo '@'.$PriPro; ?>"><img src="lib/AntRec.bmp" border="0" alt="Pagina anterior"></a><a href="lista.php?busca=<?php echo $UltPro;?>"><img src="lib/SigRec.bmp" border="0" alt="Pagina siguiente"></a><a href="lista.php?busca=-1"><img src="lib/UltRec.bmp" border="0" alt="Ir al final del archivo"></a></p>
        <p>&nbsp;</p></td>		
	    <form name="form1" method="post" action="lista.php">
        <td width="15%"><p align="left"> <font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>Busca:</em></strong></font> 
        <input name="busca" type="text" size="8" maxlength="10">
        </p>
        <p>&nbsp; </p></td>
		</form>
      <td width="6%">
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

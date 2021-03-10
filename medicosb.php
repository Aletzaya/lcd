<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catalogo de medicos</title>
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
<link href="lib/texto.css" rel="stylesheet" type="text/css">
<body >
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="ordenese.php?Vta=<?php echo $Vta;?>&Usr=<?php echo $Usr;?>>"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="1"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><img src="lib/medicos.jpg" alt="Catalogo de colores" width="150" height="25" border="1"></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:10px; top:100px; width:958px; height:329px; z-index:2"> 
  <table width="100%">
    <tr bgcolor="#0066FF"> 
      <td width="5%"><font color="#FFFFFF">Medico</font></td>
      <td width="36%"><font color="#FFFFFF">Nombre</font></td>
      <td width="24%"><font color="#FFFFFF">Especialidad</font></td>
      <td width="19%"><font color="#FFFFFF">Sub-especialidad</font></td>
      <td width="4%"><font color="#FFFFFF">Zona</font></td>
      <td width="12%"><font color="#FFFFFF">Tel.consultorio</font></td>
    </tr>
    <?php
		include("lib/kaplib.php");
		$link=conectarse();
		$cSqlIni="select medico,nombrec,especialidad,subespecialidad,zona,telconsultorio from med where $cSql order by nomc";
        if($busca<>""){  
		    if(strlen($busca)<=4){
		       $result=mysql_query("select medico,nombrec,especialidad,subespecialidad,zona,telconsultorio from med where medico >= '$busca' order by nombrec limit 0,15",$link);
		    }else{
		       $result=mysql_query("select medico,nombrec,especialidad,subespecialidad,zona,telconsultorio from med where nombrec like '%$busca%' order by nombrec limit 0,15",$link);
			}	   
        }else{
     	    $result=mysql_query("select medico,nombrec,especialidad,subespecialidad,zona,telconsultorio from med order by nombrec limit 0,15",$link);
		}
		$PriPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($PriPro==""){$PriPro=$row[0];}
			if($lB){
			   printf("<tr><td><a href='ordenese.php?Medico=$row[0]&Vta=$Vta&Usr=$Usr&Cliente=$Cliente'>%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>",$row[0],$row[1],$row[2],$row[3],$row[4],$row[5]);
			   $lB=false;
			}else{	
			   printf("<tr><td bgcolor =\"CCCCFF\"><a href='ordenese.php?Medico=$row[0]&Vta=$Vta&Usr=$Usr&Cliente=$Cliente'>%s</a></td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td>",$row[0],$row[1],$row[2],$row[3],$row[4],$row[5]);
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
<div id="Layer4" style="position:absolute; left:11px; top:431px; width:960px; height:83px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="102" border="0">
    <tr> 
      <td width="4%" height="94"> <div align="center">
          <p><a href="catalogos.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
          <p>&nbsp;</p>
        </div></td>
      <td width="20%">
        <p align="left"><a href="medicosb.php?Cliente=<?php echo $Cliente;?>&Usr=<?php echo $Usr?>&Vta=<?php echo $Vta?>"><img src="lib/IniRec.bmp" alt="Ir al inicio del archivo" border="0"></a><a href="medicosb.php?Cliente=<?php echo $Cliente;?>&Usr=<?php echo $Usr?>&Vta=<?php echo $Vta?>&busca=<?php echo '@'.$PriPro; ?>"><img src="lib/AntRec.bmp" border="0" alt="Pagina anterior"></a><a href="medicosb.php?Cliente=<?php echo $Cliente;?>&Usr=<?php echo $Usr?>&Vta=<?php echo $Vta?>&busca=<?php echo $UltPro;?>"><img src="lib/SigRec.bmp" border="0" alt="Pagina siguiente"></a><a href="medicosb.php?Cliente=<?php echo $Cliente;?>&Usr=<?php echo $Usr?>&Vta=<?php echo $Vta?>&busca=-1"><img src="lib/UltRec.bmp" border="0" alt="Ir al final del archivo"></a></p>
        <p>&nbsp;</p></td>		
	    <form name="form1" method="post" action="medicosb.php">
	    <input type="hidden" name="Cliente" value=<?php echo $Cliente;?>>
	    <input type="hidden" name="Vta" value=<?php echo $Vta;?>>
	    <input type="hidden" name="Usr" value=<?php echo $Usr;?>>
        <td width="13%"><p align="left"> <font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>Busca:</em></strong></font> 
        <input name="busca" type="text" size="8" >
        </p>
        <p>&nbsp; </p></td>
		</form>
      <td width="3%">
        <p align="center">&nbsp;</p>
        <p>&nbsp;</p></td>
      <td width="51%">&nbsp; </td>
      <td width="9%"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>

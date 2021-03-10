<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Cuestionario  por estudio pre-analitico</title>
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
<table width="100%" border="0">
  <tr>
    <td width="8%" bgcolor="#6666FF">&nbsp;</td>
    <td width="13%" bgcolor="#6666FF"><font color="#FFFFFF">Clave</font></td>
    <td width="79%" bgcolor="#6666FF"><font color="#FFFFFF">Pregunta</font></td>
  </tr>
  <?php
		include("lib/kaplib.php");
		$link=conectarse();
		$cSql="select * from cue ";
        if($busca==-1){                 // Vete hasta el final del Archivo
			$result=mysql_query("select * from cue",$link);
  			$fin=mysql_num_rows($result)-15;
			$result=mysql_query($cSql." order by pregunta limit $fin,15",$link);
		}elseif(substr($busca,0,1)=='@'){   // Vete 15 Antes
		    $busca=substr($busca,1);
		   	$result=mysql_query($cSql." where pregunta <= '$busca'",$link);
  			$fin=mysql_num_rows($result)-15;
			$result=mysql_query($cSql." where  pregunta <= '$busca' order by pregunta limit $fin,15",$link);
		}elseif($busca<>""){
		    $result=mysql_query($cSql." where pregunta >= '$busca' order by pregunta limit 0,15",$link);
        }else{
   	        $result=mysql_query($cSql."order by pregunta limit 0,15",$link);
		}
		$PriPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($PriPro==""){$PriPro=$row[0];}
			if($lBd){
			   printf("<tr><td><a href=AgrCue.php?Estudio=$row[0]&cKey=$cKey>%s</a></td><td>%s</td><td>%s</td>",'Agr',$row[0],$row[1]);
			   $lBd=false;
			}else{
			   printf("<tr><td bgcolor=\"CCCCFF\"><a href=AgrCue.php?Estudio=$row[0]&cKey=$cKey >%s</a></td><td bgcolor =\"CCCCFF\" >%s</td><td bgcolor =\"CCCCFF\" >%s</td>",'Agr',$row[0],$row[1]);
			   $lBd=true;
			}
			$nRen=$nRen+1;
			$UltPro=$row[0];
		}
		mysql_free_result($result);
		mysql_close($link);
	?>
</table>
<table width="75%" border="0">
  <tr>
    <td width="41%" height="81">
<p><img src="lib/SmallExit.BMP" onClick="JavaScript:window.close()" alt="Salir">&nbsp;
        </a><a href="AgrCue.php"> <img src="lib/IniRec.bmp" border="0" alt="Inicio del archivo">
        </a><a href="AgrCue.php?busca=<?php echo '@'.$PriPro; ?>&cKey=<?php echo $cKey?>"><img src="lib/AntRec.bmp" border="0" alt="Pagina anterior"></a>
        <a href="AgrCue.php?busca=<?php echo $UltPro;?>&cKey=<?php echo $cKey;?>"><img src="lib/SigRec.bmp" border="0" alt="Siguiente pagina"></a>
        <a href="AgrCue.php?busca=-1&cKey=<?php echo $cKey;?>"><img src="lib/UltRec.bmp" alt="Al final del archivo" border="0"></a>
      </p>
      </td>
    <td width="59%"><form name="form1" method="post" action="AgrCue.php">
        <p>&nbsp;</p>
        <p>Busca: 
          <input type="text" name="busca" size="8" maxlength="15">
        </p>
      </form></td>
  </tr>
</table>
<p>&nbsp;</p>
</td>
</p> 
</body>
</html>
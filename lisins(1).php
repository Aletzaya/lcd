<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Institcs X List/Precios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<div id="Layer2" style="position:absolute; left:10px; top:10px; width:300px; height:352px; z-index:2"> 
  <table width="100%" >
    <tr>
      <td> 
        <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#0066FF"><strong>Lista 
          de Precios No.<?php echo $cKey;?></strong></font> </div></td>
    </tr>
  </table>
  <table width="100%" >
    <tr>
      <td width="20%" bgcolor="#6633FF"><font color="#FFFFFF">Institucion</font></td>
      <td width="80%" bgcolor="#6633FF"><font color="#FFFFFF">Nombre</font></td>
     </tr>
	 <?php
		include("lib/kaplib.php");
		$link=conectarse();
   	    $result=mysql_query("select institucion,nombre from inst where lista=$cKey",$link);
		while ($row=mysql_fetch_array($result)){
		   if($lBd){
			 printf("<tr><td align=right>%s</td><td>%s</td>",$row[0],$row[1]);
			 $lBd=false;
		   }else{ 
		     printf("<tr><td bgcolor =\"CCCCFF\" align=right>%s</td><td bgcolor =\"CCCCFF\">%s</td>",$row[0],$row[1]);
			 $lBd=true;
           }
		}
		mysql_free_result($result);
		mysql_close($link);
		?>
  </table>
  <p align="center"><a href="javascript:window.close()"><img src="lib/SmallExit.BMP" border="0"></a></p>
</div>
</body>
</html>

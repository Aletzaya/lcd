<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $cKey=$_REQUEST[cKey];

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Institucion por lista de precios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>

<div align="center"><?php echo "$Gfont Lista  de Precios No. $cKey </font> "; ?></div>

  <table width='100%' border='0'>
    <tr>
      <td bgcolor="#b1b1b1"><font color="#FFFFFF">Inst</font></td>
      <td bgcolor="#b1b1b1"><font color="#FFFFFF">Nombre</font></td>
     </tr>
	 <?php
   	    $result=mysql_query("select institucion,nombre from inst where lista=$cKey",$link);
		while ($row=mysql_fetch_array($result)){
           echo "<tr><td>$Gfont $row[0]</font></td><td>$Gfont $row[1]</font></td></tr>";
		}
		mysql_free_result($result);
		mysql_close($link);
		?>
  </table>
  <p align="center"><a class='pg' href="javascript:window.close()">Cerrar esta ventana</a></p>
</div>
</body>
</html>
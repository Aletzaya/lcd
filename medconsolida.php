<?php

  session_start();

  //include_once ("auth.php");

  //include_once ("authconfig.php");

  //include_once ("check.php");

  require("lib/lib.php");

  $link    = conectarse();
  
  $Mes     = $_REQUEST[cMes];  
  
  //equire ("confignew.php");

?>

<html>

<head>
<title>Sistema de Laboratoriio clinico</title>
</head>

<body>

<?php

 
  if($_REQUEST[op] <> 'Fn'){
 
     echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
  
     //echo "<p align='center'><img src='lib/espera.gif'></p>";


     
     $CpoA = mysql_query("SELECT medico,count( * ) FROM cmc WHERE cmc.mes='$Mes' GROUP BY cmc.medico");

     $NumMes = "m".substr($Mes,5,2);

     $lUp = mysql_query("UPDATE med SET $NumMes = 0");

     while($rg=mysql_fetch_array($CpoA)){
      
		$lUp = mysql_query("UPDATE med SET $NumMes = $rg[1] WHERE medico='$rg[medico]'");
			
     }

     echo "<p align='center'>Listo! el total de ordenes por mes y medico fueron grabadas con exito</p>";
     
     echo "<p align='center'><a href='javascript:window.close()'>Cerra &eacute;sta ventana</a></p>"; 

     //header("Location: medconsolida.php?op=Fn");

  }
	   
?>

</div>
</body>
</html>
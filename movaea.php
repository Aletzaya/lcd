<?php
    //Agrega moviemientos al archivo de inst.detalle esto es los Est. X Ins.
    $tabla="estins";
    include("lib/kaplib.php");
    $link=conectarse();
	$lUp=mysql_query("select estudio from estins where instituto='$cKey' and estudio='$Estudio'");
	if(!$row=mysql_fetch_array($lUp)){
       $lUp=mysql_query("insert into $tabla (instituto,estudio) VALUES ('$cKey','$Estudio')",$link);
	}   
    header("Location: AgrEstIns.php?cKey=$cKey");	
	mysql_free_result($lUp);
    mysql_close($link);
?>
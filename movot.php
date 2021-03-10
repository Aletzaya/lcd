<?php
include("lib/kaplib.php");
$link=conectarse();
$date=date("Y-m-d");
$hora = date("h:i:s");
//echo "Estoy en MovOt Usr es $Usr $HTTP_GET_VARS[Cliente] $HTTP_GET_VARS[faz] $HTTP_GET_VARS[usuario_login]";
   $OtA=mysql_query("select * from otnvas where venta=$Vta and usr='$Usr'",$link);
   $OtN=mysql_fetch_array($OtA);
   $lUp=mysql_query("insert into ot (orden,cliente,fecha,hora,medico,fecharec,importe,institucion,observaciones,diagmedico) VALUES ('','$Cliente','$date','$hora','$Medico','$Fecharec',0,'$OtN[inst]','$Observaciones','$Diagmedico')",$link);		
   $Orden=mysql_insert_id();
   $OtdA=mysql_query("select * from otdnvas where usr='$Usr' and venta=$Vta",$link);
   while ($OtdN=mysql_fetch_array($OtdA)){
         $lUp=mysql_query("insert into otd (orden,estudio,precio,descuento) VALUES ('$Orden','$OtdN[estudio]','$OtdN[precio]','$OtdN[descuento]')",$link);		
   }
   //$lUp=mysql_query("delete from otdnvas where usr='$Usr' and venta=$Vta",$link);
   //$lUp=mysql_query("delete from otnvas where usr='$Usr' and venta=$Vta",$link);
    header("Location: ordenes.php");  	
    mysql_close($link);
?>
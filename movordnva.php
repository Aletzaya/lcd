<?php
require("aut_verifica.inc.php");
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
$Usr=$HTTP_SESSION_VARS['usuario_login'];
$Vta=$HTTP_SESSION_VARS['Venta_ot'];		
include("lib/kaplib.php");
$link=conectarse();
if($Accion=='Estudio'){		// Agrega Estudios a la Venta
   $OtnvaA=mysql_query("select lista,fechae,servicio from otnvas where usr='$Usr' and venta='$Vta'",$link);	
   if($Otnva=mysql_fetch_array($OtnvaA)){
        $Fecha=date("Y-m-d");
        $Lista="lt".ltrim($Otnva[lista]);
        $lUp=mysql_query("select estudio,descripcion,$Lista,entord,entexp,enthos,enturg from est  where estudio='$Estudio' ",$link);
	    if($cCpo=mysql_fetch_array($lUp)){
		   $Estudio=strtoupper($Estudio);
           $lUp=mysql_query("insert into otdnvas (usr,estudio,descripcion,descuento,precio,venta) VALUES ('$Usr','$Estudio','$cCpo[1]',0,'$cCpo[$Lista]','$Vta')",$link);
		   if($Otnva[servicio]=="Ordinaria"){
		      $Dias=$cCpo[entord];
		   }else{
		      $Dias=$cCpo[entexp]/24;
		   }
		   $nDias=substr($Fecha,8,2)+$Dias;
		   $Fechae=substr($Fecha,0,4)."-".substr($Fecha,5,2)."-".$nDias;	
		   if($Otnva[fechae] < $Fecha ){   //Checa y autaliza de fecha de entrada
	         $Otnva=mysql_query("update otnvas set fechae = '$Fechae' where usr='$Usr' and venta='$Vta'",$link);	
		   }	 
	       header("Location: ordenesnvas.php?Vta=$Vta");	   
	    }else{
		   $Msj="El Estudio $Estudio no existe, favor de verificar";
	       header("Location: msj.php?url=ordenesnvas.php?Vta=$Vta&Msj=$Msj");
	    }   
	}else{
	   $Msj="Aun no as elegido la Institucion(lista/precios), favor de verificar";
       header("Location: msj.php?url=ordenesnvas.php?Vta=$Vta&Msj=$Msj");
	}	
}elseif($Accion=="Institucion"){
   $lUp=mysql_query("select estudio from otdnvas where usr='$Usr' and venta=$Vta",$link);	#Checo k bno halla estudios capturados
   if(!$lU=mysql_fetch_array($lUp)){
      $Fecha=date("Y-m-d");
      $InsA=mysql_query("select lista from inst where institucion='$Institucion'",$link);	#Checo la lista de la institucion  
	  $Ins=mysql_fetch_array($InsA);
	  $Lista=$Ins[lista];
      $lUp=mysql_query("delete from otnvas where usr='$Usr' and venta='$Vta'",$link);
      $lUp=mysql_query("insert into otnvas (usr,inst,venta,lista,servicio,fechae) VALUES ('$Usr','$Institucion','$Vta','$Lista','$Servicio','$Fecha')",$link);	  
      header("Location: ordenesnvas.php?Vta=$Vta");
   }else{
      $Msj="Para poder cambiar la institucion, es necesario eliminar los estudios capturados";
      header("Location: msj.php?url=ordenesnvas.php?Vta=$Vta&Msj=$Msj");
   }
}elseif($Accion=="Orden"){     // Genera la Orden de trabajo
   $Fecha=date("Y-m-d");
   $hora = date("h:i:s");   
   $PsoA=mysql_query("select sum(precio) from otdnvas where usr='$Usr' and venta=$Vta",$link);
   $Pso=mysql_fetch_array($PsoA);
   $lOtA=mysql_query("select * from otnvas where usr='$Usr' and venta=$Vta",$link);
   $lOt=mysql_fetch_array($lOtA);
   $lUp=mysql_query("insert into ot (cliente,fecha,hora,medico,fecharec,fechae,institucion,diagmedico,observaciones,servicio,recepcionista,receta,importe)
        VALUES ('$lOt[cliente]','$Fecha','$Hora','$lOt[medico]','$lOt[fechar]','$lOt[fechae]','$lOt[inst]','$lOt[diagmedico]','$lOt[observaciones]','$lOt[servicio]','$Usr','$lOt[receta]',$Pso[0])",$link);
   $Id=mysql_insert_id();   	  
   $lUpA=mysql_query("select estudio,precio from otdnvas where usr='$Usr' and venta=$Vta",$link);	#Checo k bno halla estudios capturados   
   while($lUp=mysql_fetch_array($lUpA)){
      $lUpP=mysql_query("insert into otd (orden,estudio,precio) VALUES ($Id,'$lUp[estudio]','$lUp[precio]')",$link);	  
   }
   if($lOt[abono]>0){
      $lUp=mysql_query("insert into cja (orden,fecha,hora,usuario,importe) VALUES ($Id,'$Fecha','$hora','$Usr',$lOt[abono])",$link);	   
   }
   $lUp=mysql_query("delete from otnvas where usr='$Usr' and venta='$Vta'",$link);
   $lUp=mysql_query("delete from otdnvas where usr='$Usr' and venta='$Vta'",$link);
   //session_destroy();
   //mysql_free_result($lUp);
   mysql_close($link);
   header("Location: ordenes.php?busca=-1");
}elseif($Accion=="Cliente"){
   $CliA=mysql_query("select cliente from cli where cliente=$Cliente",$link);	
   if($Cli=mysql_fetch_array($CliA)){
      $lUp=mysql_query("update otnvas set cliente = $Cliente where usr='$Usr' and venta='$Vta'",$link);
      header("Location: ordenesnvas.php?Vta=$Vta");
   }else{
      $Msj="El No. de paciente ".$Cliente." no existe";
      header("Location: msj.php?url=ordenesnvas.php?Vta=$Vta&Msj=$Msj");
   }
}elseif($Accion=="Medico"){
   $Medico=strtoupper($Medico);
   $MedA=mysql_query("select medico from med where medico='$Medico'",$link);	
   if($Med=mysql_fetch_array($MedA)){
      $lUp=mysql_query("update otnvas set medico = '$Medico' where usr='$Usr' and venta='$Vta'",$link);
      header("Location: ordenesnvas.php?Vta=$Vta");
   }else{
      $Msj="La Clave del Medico ".$Medico ." no existe";
      header("Location: msj.php?url=ordenesnvas.php?Vta=$Vta&Msj=$Msj");
   }   	    	  
}elseif($Accion=="Receta"){
    $lUp=mysql_query("update otnvas set receta = '$Receta',fechar='$Fechar',abono=$Abono where usr='$Usr' and venta='$Vta'",$link);
    header("Location: ordenesnvas.php?Vta=$Vta");
}elseif($Accion=="Observacion"){
    $lUp=mysql_query("update otnvas set observaciones = '$Observaciones',diagmedico='$Diagmedico' where usr='$Usr' and venta='$Vta'",$link);
    header("Location: ordenesnvas.php?Vta=$Vta");   	  
}elseif($Avta=='No'){
  $lUp=mysql_query("delete from otdnvas where usr='$Usr' and venta=$Vta",$link);
  $lUp=mysql_query("delete from otnvas where usr='$Usr' and venta=$Vta",$link);
  header("Location: ordenesnvas.php?Vta=$Vta");  
}elseif($Mv=='E'){
   $lUp=mysql_query("delete from otdnvas where estudio='$cKey' and usr='$Usr' and venta='$Vta' limit 1",$link);
   header("Location: ordenesnvas.php?Vta=$Vta");
}else{
     $OtA=mysql_query("select lista from otnvas where usr = '$Usr' and venta='$Vta' ",$link);
     $Ot=mysql_fetch_array($OtA);
     if($Ot[lista]<>""){
	    $Lista="lt".ltrim($Ot[lista]);
        $lUp=mysql_query("select estudio,descripcion,$Lista from est  where estudio='$Estudio' ",$link);
	    if($cCpo=mysql_fetch_array($lUp)){
		   $Estudio=strtoupper($Estudio);
           $lUp=mysql_query("insert into otdnvas (usr,estudio,descripcion,descuento,precio,venta) VALUES ('$Usr','$Estudio','$cCpo[1]',0,'$cCpo[$Lista]','$Vta')",$link);
	       header("Location: ordenesnvas.php?Vta=$Vta");	   
	    }else{
		   $Msj="El Estudio $Estudio no existe, favor de verificar";
	       header("Location: msj.php?url=ordenesnvas.php?Vta=$Vta&Msj=$Msj");
	    }   
	}else{
	   $Msj="Aun no as elegido la Institucion(lista/precios), favor de verificar";
       header("Location: msj.php?url=ordenesnvas.php?Vta=$Vta&Msj=$Msj");
	}	
}
mysql_close($link);
?>
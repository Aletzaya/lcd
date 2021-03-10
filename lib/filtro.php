<?php

function filtro($Tabla){

global $pagina,$op,$Tabla,$Campo,$Signo;   #P k reconoscan el valor k traen

 if(substr($_REQUEST[op],0,1)=='!'){ // Cuando regresa de la peticion de filtros guardados

     $Num=substr($_REQUEST[op],1);

     $_SESSION['id']=$Num;    // le pongo el  nombre del usuario de la session
        
     $_SESSION['file']=$Tabla;	  

  }elseif($_REQUEST[op]=='ft'){
 
 	  $link=conectarse();

      //if(substr($_REQUEST[Campo],0,1)=='D' or $_REQUEST[Signo]=='like'){
     if($_REQUEST[Signo]=='like'){

       $Sig1="\'%";

       $Sig2="%\'";

     }elseif(substr($_REQUEST[Campo],0,1)=='D' or substr($_REQUEST[Campo],0,1)=='C'){

       $Sig1="\'";    // Esto es una comita p/k no me marque error or \"

       $Sig2="\'";

     }

     $Campo=substr($_REQUEST[Campo],1);

     $lUp=mysql_query("insert into ftd (id,fil,campo,signo,valor,yo,usr) VALUES ('99999','$Tabla','$Campo','$_REQUEST[Signo]','$Sig1$_REQUEST[Valor]$Sig2','$_REQUEST[Yo]','$_COOKIE[USERNAME]')",$link);

     if($_REQUEST[Yo]==''){

        $_SESSION['id']='99999';#Fin del filtro y activa las session de filtro activo
        
        $_SESSION['file']=$Tabla;

     }
	
  }elseif($_REQUEST[op]=='br'){

  	  $link=conectarse();

     //$lUp=mysql_query("delete from ftd where usr='$_COOKIE[USERNAME]' and fil='$Tabla' and id='99999' ",$link);
     
     $lUp=mysql_query("delete from ftd",$link);

     //session_destroy();   //Evito que borre la session solo las pongo el blanco

     $_SESSION['id']='';    
        
     $_SESSION['file']='';	  
     

  }
  
  return true;
  
}
  
?>
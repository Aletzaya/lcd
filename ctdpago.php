<?php

session_start();
  
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
  
require("lib/lib.php");

$link     = conectarse();  
  
if(isset($_REQUEST[busca])){        
    if($_REQUEST[busca] == ini ){                 
      //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)      
      $_SESSION["OnToy"] = array('','','cptpagod.referencia','Asc',$Retornar);         
    }elseif($_REQUEST[busca] <> ''){  
      $_SESSION['OnToy'][0]    =  $_REQUEST[busca];    
    }
}    

//Captura los valores que trae y metelos al array
if(isset($_REQUEST[pagina]))  { $_SESSION['OnToy'][1]   = $_REQUEST[pagina]; }
if(isset($_REQUEST[orden]))   { $_SESSION['OnToy'][2]   = $_REQUEST[orden];  } 
if(isset($_REQUEST[Sort]))    { $_SESSION['OnToy'][3]   = $_REQUEST[Sort];   }
if(isset($_REQUEST[Ret]))     { $_SESSION['OnToy'][4]   = $_REQUEST[Ret];    }


#Saco los valores de las sessiones los cuales normalmente no cambian;
$busca    = $_SESSION[OnToy][0];
$pagina   = $_SESSION[OnToy][1];
$OrdenDef = $_SESSION[OnToy][2];
$Sort     = $_SESSION[OnToy][3];        
  
  

//if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar  
//if(isset($_REQUEST[Sort]))   {  $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc o Desc
//if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena	
//if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

//#Saco los valores de las sessiones los cuales normalmente no cambian;
//$pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
//$Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
//$OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults
//$OrdenDef = "cli.nombrec";        //Orden de la tabla por defaults
//$busca    = $_SESSION[busca];        //Dato a busca
    
#Variables comunes;
$Titulo = "Diferentes pagos";
$op     = $_REQUEST[op];
$Msj    = $_REQUEST[Msj];
$Id     = 55; 
  

#Intruccion a realizar si es que mandan algun proceso
if($op=='Si'){                    //Elimina Registro
  	  $Msj="Lo siento opcion deshabilitada";
}elseif($op=='rs'){
      $Up=mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id");
      $op='';
}

#Tomo los datos principales campos a editar, tablas y filtros; 
$QryA  = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id='$Id'");
$Qry   = mysql_fetch_array($QryA);
  
if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
    
#Deshago la busqueda por palabras(una busqueda inteligte;
$Palabras  = str_word_count($busca);  //Dame el numero de palabras
if($Palabras > 1){
     $P=str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" $OrdenDef like '%$P[$i]%' ";}else{$BusInt=$BusInt." and $OrdenDef like '%$P[$i]%' ";}
     }
}else{
     $BusInt=" $OrdenDef like '%$busca%' ";  
}
  
  
//if( $busca < 'a'){
//   $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE cli.zona=zns.zona AND cli.cliente >= '$busca' $Qry[filtro]";
//}else{
#Armo el query segun los campos tomados de qrys;
$cSql= "SELECT $Qry[campos],cptpago.pago FROM $Qry[froms] LEFT JOIN cptpago "
        . "ON cptpagod.id_nvo = cptpago.id WHERE $BusInt";
//echo $cSql;

$aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
$aIzq   = array(" ","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
$aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
$aDer   = array();				        //Arreglo donde se meten los encabezados; Derechos;
$tamPag = $Qry[tampag];

require ("config.php");							//Parametros de colores;


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php

 headymenu($Titulo,1);

 //echo $cSql;
  
 if(!$res=mysql_query($cSql)){
 
        echo "$Gfont <p align='center'>No se encontraron resultados y/o hay un error en el filtro</p>";    #Manda mensaje de datos no existentes
        echo "<p align='center'>$Gfont Favor de presiona refresca...</p></font>";    #Manda mensaje de datos no existentes
		  $nRng=7;
		  $op='rs';        

  }else{

      CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
		
      $sql  = $cSql.$cWhe." ORDER BY id $Sort LIMIT ".$limitInf.",".$tamPag;
      //echo $sql;
      $res  = mysql_query($sql);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a class='seleccionar' href='$cLink?busca=$rg[id]'>Editar</a></td>";
           echo "<td>$Gfont $rg[id]</font></td>";
           echo "<td>$Gfont $rg[referencia]</font></td>";
           echo "<td>$Gfont $rg[cuenta]</font></td>";
           echo "<td>$Gfont $rg[pago]</font></td>";
           echo "</tr>";

           $nRng++;

      }

	
	}
     
    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------

    CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
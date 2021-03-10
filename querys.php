<?php

  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");

  $link=conectarse();  
  
  //Cuando entra por primera vez debe de traer estos parametros por default;
  if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar  
  if(isset($_REQUEST[Sort]))   {  $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc ò Desc
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena	
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por default
  $busca    = $_SESSION[busca];        //Dato a busca
        
  #Variables comunes;
  $Titulo="Tabla de Consultas(Querys)";
  $op=$_REQUEST[op];
  $Id=1; 										                 //Numero de query dentro de la base de datos

  #Intruccion a realizar si es que mandan algun proceso
  if($op=='Si'){                    //Elimina Registro
  	  $Msj="Lo siento opcion deshabilitda";
  }

  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA=mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id"); 
  $Qry=mysql_fetch_array($QryA);
  
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  #Armo el query segun los campos tomados de qrys;
  $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE nombre like '%$busca%' $Qry[filtro]";

  $aCps = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq=array("Edit","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos
  $aDat = SPLIT(",",$Qry[edi]);			//Arreglo donde llena el grid de datos        
  $aDer=array("Elim","-","-");				 //Arreglo donde se meten los encabezados; Derechos;
  $tamPag=$Qry[tampag];

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
  
  if(!$res=mysql_query($cSql,$link)){
        echo "$Gfont <p align='center'>No se encontraron resultados y/o hay un error en el filtro</p>";    #Manda mensaje de datos no existentes
        echo "<p align='center'>$Gfont Favor de presiona Refresca...</p></font>";    #Manda mensaje de datos no existentes
		  $nRng=7;
		  $op='rs';        
   }else{

      CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
		
      $sql=$cSql.$cWhe." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;
		
		//echo $sql;

      $res=mysql_query($sql);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos=strrpos($_SERVER[PHP_SELF],".");
      $cLink=substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #

      while($registro=mysql_fetch_array($res)){
           if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           echo "<tr bgcolor=$Fdo onMouseOut=this.style.backgroundColor=$Fdo;>";
           echo "<td align='center'><a href='$cLink?busca=$registro[id]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";

           Display($aCps,$aDat,$registro);           

           echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?op=99')><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
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
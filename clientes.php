<?php

session_start();
  
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
  
require("lib/lib.php");

$link     = conectarse();  
  
  if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}   #Pagina a editar  
  if(isset($_REQUEST[Sort]))   {  $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc o Desc
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}     #Campo por el cual se ordena 
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}     #Campo por el cual se ordena 

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];      //Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults
  $busca    = $_SESSION[busca];        //Dato a busca
    
#Variables comunes;
$Titulo = "Clientes";
$op     = $_REQUEST[op];
$Msj    = $_REQUEST[Msj];
$Id     = 5; 
  

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
            if(!isset($BusInt)){$BusInt=" cli.nombrec like '%$P[$i]%' ";}else{$BusInt=$BusInt." and cli.nombrec like '%$P[$i]%' ";}
     }
}else{
     $BusInt=" cli.nombrec like '%$busca%' ";  
}


if( $busca < 'a'){
   $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE cli.zona=zns.zona AND cli.cliente >= '$busca' $Qry[filtro]";
}else{
  #Armo el query segun los campos tomados de qrys;
  $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE cli.zona=zns.zona AND $BusInt $Qry[filtro]";
}    

//echo $cSql;

$aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
$aIzq   = array(" ","-","-"," ","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
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
		
      $sql  = $cSql.$cWhe." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;
      //echo $sql;
      $res  = mysql_query($sql);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a class='seleccionar' href='$cLink?busca=$rg[cliente]'>editar</a></td>";
           //echo "<td align='center'><a href='$cLinkd?busca=$rg[medico]'><img src='lib/browse.png' alt='Detalle' border='0'></td>";
		   
		    if($rg[status]=='Activo'){
           		echo "<td align='center'><a class='seleccionar' href=ordenesnvas.php?Cliente=$rg[cliente]&op=cl>seleccionar</a></td>";
			}else{
			    echo "<td align='center'><img src='lib/iconfalse.gif' alt='Elige este reg' border='0'></td>";			   
			}


           //Display($aCps,$aDat,$rg);           

           echo "<td>$Gfont " . ucwords(strtolower($rg[cliente]))."</td>";
           echo "<td><a class='pg' href=javascript:winuni('cambiacli.php?busca=$rg[cliente]')>".ucwords(strtolower($rg[nombrec]))."</a></td>";
           echo "<td>$Gfont " . ucwords(strtolower($rg[localidad]))."</td>";
           echo "<td>$Gfont " . ucwords(strtolower($rg[municipio]))."</td>";
           echo "<td>$Gfont " . ucwords(strtolower($rg[descripcion]))."</td>";
           echo "<td>$Gfont $rg[telefono]</font></td>";
           echo "<td>$Gfont $rg[numveces]</font></td>";
           echo "<td>$Gfont $rg[programa]</font></td>";
           echo "<td>$Gfont $rg[status]</font></td>";

           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si')><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></a></td>";
           //echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";

           echo "</tr>";

           $nRng++;

      }

	
	}
     
    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------

//    CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

  echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

  echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&orden=cli.cliente&busca=&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'>";

  echo "<label for='tags'><b>Buscar:</b></label>&nbsp;<div><input id='tags' type='text' name='busca' size='30' maxlength='30' placeholder='Clave o Nombre'> &nbsp; &nbsp; &nbsp; ";

  echo "<input type='hidden' name='pagina' value='1'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;
  //echo "<input type='hidden' name='Inst' value='$Inst'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

  echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&orden=cli.cliente&busca='>Limpia pantalla</a></div>";

  echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
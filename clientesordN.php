<?php

  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/libN.php");

  $link     = conectarse();  

  if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar  
  //if(isset($_REQUEST[Sort]))   {  $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc o Desc
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena	
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  //$OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults
  $OrdenDef = "nombrec";        //Orden de la tabla por defaults
  $busca    = $_SESSION[busca];        //Dato a busca
    
  #Variables comunes;
  $Titulo = "Clientes";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 5; 

  $Vta = $_REQUEST[Vta];
  //if(!isset($Msj)){
  //	  $Msj = "Para buscar por clave poner un guion al principio(-)";  
  //}										                 //Numero de query dentro de la base de datos

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
  
  
//  if( $busca < 'a'){
//     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE cli.zona=zns.zona AND cli.cliente >= '$busca' $Qry[filtro]";
//  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE cli.zona=zns.zona AND $BusInt $Qry[filtro]";
//  }   
  

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

<script language="JavaScript1.2">


function load(){
  document.form10.busca.focus();
}

</script>


<body bgcolor="#FFFFFF"  onload="load();">

<?php headymenu($Titulo,1);

  if(!$res=mysql_query($cSql.$cWhe,$link)){

     cMensaje("No se encontraron resultados � hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

      
        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

        $sql = $cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
        //echo $sql;
        $res = mysql_query($sql);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		    if($registro[status]=='Activo'){
           		echo "<td align='center'><a class='pg' href=ordenesnvasN.php?Cliente=$registro[cliente]&op=cl&Vta=$Vta>seleccionar</a></td>";
			}else{
			    echo "<td align='center'><img src='lib/iconfalse.gif' alt='Elige este reg' border='0'></td>";			   
			}

		    if($registro[status]=='Activo'){
           		echo "<td align='center'><a  class='pg' href=clientesordNe.php?busca=$registro[cliente]&pagina=$pagina&Vta=$Vta>editar</a></td>";
			}else{
			    echo "<td align='center'><img src='lib/iconfalse.gif' alt='Elige este reg' border='0'></td>";			   
			}
           echo "<td>$Gfont $registro[cliente]</font></td>";
           echo "<td>$Gfont $registro[nombrec]</font></td>";
           echo "<td>$Gfont $registro[localidad]</font></td>";
           echo "<td>$Gfont $registro[municipio]</font></td>";
           echo "<td>$Gfont $registro[descripcion]</font></td>";
           echo "<td>$Gfont $registro[telefono]</font></td>";
           echo "<td align='center'><a class='pg' href=javascript:wingral('repots.php?busca=$registro[0]')>$registro[numveces]</td>";
           echo "<td>$Gfont $registro[programa]</font></td>";
           echo "<td>$Gfont $registro[status]</font></td>";
           echo "</tr>";
           $nRng++;
		}//fin while

        PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

        CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    mysql_close();

    ?>

</body>

</html>
<?php

  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/lib.php");

  $link     = conectarse();  

  if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar  
  if(isset($_REQUEST[Sort]))   {  $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc o Desc
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena	
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults
  $busca    = $_SESSION[busca];        //Dato a busca
    
  #Variables comunes;
  $Titulo = "Instituciones";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 9; 
  $filtro    = $_REQUEST[filtro];       
  $filtro3    = $_REQUEST[filtro3];       
  $filtro5    = $_REQUEST[filtro5];       

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
            if(!isset($BusInt)){$BusInt=" inst.nombre like '%$P[$i]%' ";}else{$BusInt=$BusInt." and inst.nombre like '%$P[$i]%' ";}
     }
  }else{
     $BusInt=" inst.nombre like '%$busca%' ";  
  }
  
 if($filtro=='*'){
	$filtro2="and inst.condiciones<>' '";
 }else{
 	$filtro2="and inst.condiciones='$filtro'";
 }
 
 if($filtro3=='*'){
	$filtro4="and inst.status<>' '";
 }else{
 	$filtro4="and inst.status='$filtro3'";
 }
  
 if($filtro5=='*'){
  $filtro6=" ";
 }elseif($filtro5=='Actualizado'){
  $filtro6="and inst.fechamod<>'0000-00-00 00:00:00'";
 }elseif($filtro5=='Sin Actualizar'){
  $filtro6="and inst.fechamod='0000-00-00 00:00:00'";
 }
 
  if( $busca < 'a'){ //Busco por numero de inst.
      $cSql="SELECT $Qry[campos],inst.fechamod FROM $Qry[froms] WHERE inst.institucion >= '$busca' $Qry[filtro] $filtro2 $filtro4 $filtro6";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],inst.fechamod FROM $Qry[froms] WHERE $BusInt $Qry[filtro] $filtro2 $filtro4 $filtro6";
  }   
  //echo $cSql;
  

  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Ed","-","-","Imp","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array("Actualizado","-","-");			        //Arreglo donde se meten los encabezados; Derechos;
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
	      echo "<table align='right' width='20%' border='0' cellspacing='0' cellpadding='0' background='lib/degrada.jpg'>";

          echo "<tr align='right'>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Condiciones</b></font>";
    	  echo "<form name='form' method='post' action='institu.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5'>";
          echo "<select size='1' name='filtro' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='CONTADO'>Contado</option>";
          echo "<option value='CREDITO'>Credito</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro</option></p>";		  
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Status</b></font>";
    	  echo "<form name='form' method='post' action='institu.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5'>";
          echo "<select size='1' name='filtro3' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='ACTIVO'>Activo</option>";
          echo "<option value='INACTIVO'>Inactivo</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3</option></p>";		  
          echo "</select>";
		  echo"</b></td>";
		  echo "</form>";
      echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Actualizado</b></font>";
        echo "<form name='form' method='post' action='institu.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5'>";
          echo "<select size='1' name='filtro5' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='Actualizado'>Actualizado</option>";
          echo "<option value='Sin Actualizar'>Sin Actualizar</option>";
      echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5</option></p>";     
          echo "</select>";
      echo"</b></td>";
      echo "</form>";
		  echo"</tr></table>";

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           echo "<td align='center'><a href=institue.php?busca=$rg[institucion]><img src='lib/edit.png' alt='Edita registro' border='0'></a></td>";
           
          echo "<td align='center'><a href=javascript:wingral('institupdf.php?busca=$rg[institucion]')><img src='images/printdigital.png' alt='Impresion de registro' border='0' width='22'></td>";
           //echo "<td align='center'><a href=ordenesd.php?busca=$rg[orden]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";

           Display($aCps,$aDat,$rg);    

           if($rg[fechamod]=='0000-00-00 00:00:00'){
              $Actualiza=' ';
           }else{
              $Actualiza=substr($rg[fechamod], 0, 10);
           }       

          echo "<td align='center'>$Gfont <font size='1'>$Actualiza </font></td>";


           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si')><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></a></td>";
           //echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";

           echo "</tr>";

           $nRng++;

      }

	
	}
     
    PonPaginacion3(true);           #-------------------pon los No.de paginas-------------------

    CuadroInferior2($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
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
  $Titulo = "Catalogo de estudios";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 10; 
  $filtro    = $_REQUEST[filtro];       
  $filtro3    = $_REQUEST[filtro3];       
  $filtro5    = $_REQUEST[filtro5];       
  $filtro7    = $_REQUEST[filtro7];       
  $filtro9    = $_REQUEST[filtro9];       

  
  if(!isset($Msj)){
	  $Msj = "Para buscar por clave poner un guion al principio(-)";  
  }										                 //Numero de query dentro de la base de datos

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


  $con = "select est.estudio, est.descripcion from est";//consulta para seleccionar las palabras a buscar, esto va a depender de su base de datos
  $query = mysql_query($con);
  


  #Deshago la busqueda por palabras(una busqueda inteligte;
  $Palabras  = str_word_count($busca);  //Dame el numero de palabras
  if($Palabras > 1){
     $P=str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" est.descripcion like '%$P[$i]%' ";}else{$BusInt=$BusInt." and est.descripcion like '%$P[$i]%' ";}
     }
  }else{
     $BusInt=" est.descripcion like '%$busca%' ";  
  }
  
 if($filtro<>'*'){
 	$filtro2="and est.proceso='$filtro'";
 }else{
	$filtro2="and est.proceso<>'*'";
 }
 
 if($filtro3<>'*'){
 	$filtro4="and est.subdepto='$filtro3'";
 }else{
	$filtro4="and est.subdepto<>'*'";
 }

 if($filtro5<>'*'){
 	$filtro6="and est.activo='$filtro5'";
 }else{
	$filtro6="and est.activo<>'*'";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and est.estpropio='$filtro7'";
 }else{
	$filtro8="and est.estpropio<>'*'";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and est.depto='$filtro9'";
 }else{
	$filtro10="and est.depto<>'*'";
 }

  if(substr($busca,0,1)=='-'){
     $busca = substr($busca,1);
     $cSql  = "SELECT $Qry[campos] FROM $Qry[froms] WHERE est.estudio='$busca' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt $Qry[filtro] $filtro2 $filtro4 $filtro6 $filtro8 $filtro10";
  }   
  //echo $cSql;
  

  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("","-","-","Mat.Prim ","-","-","Imp","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array();				        //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");							//Parametros de colores;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

<script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />

</head>

<body bgcolor="#FFFFFF">

<script>
  $(function() {
    
    <?php
    
      while($row= mysql_fetch_array($query)) {//se reciben los valores y se almacenan en un arreglo
        $elementos[]= '"'.$row['descripcion'].'"';
        //$elementos[]= '"'.$row['estudio'].' - '.$row['descripcion'].'"';
    
      }

      $arreglo= implode(", ", $elementos);//junta los valores del array en una sola cadena de texto
    ?>  
    
    var availableTags=new Array(<?php echo $arreglo; ?>);//imprime el arreglo dentro de un array de javascript
        
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  });
  </script>

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

	  	  echo "<table align='right' width='50%' border='0' cellspacing='0' cellpadding='0'>";

          echo "<tr align='right'>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Depto</b></font>";
    	  echo "<form name='form' method='post' action='estudios.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro9' class='textosItalicos' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
		  $cDep=mysql_query("SELECT departamento,nombre FROM dep order by departamento");
		  while ($Dep=mysql_fetch_array($cDep)){
				 echo "<option value=$Dep[0]>$Dep[0]: ".ucwords(strtolower($Dep[1]))."</option>";
                if($Dep[0]==$filtro9){$Depto=$Dep[1];}
		  }
		  echo "<option SELECTed value='*'>$filtro9 $Depto</option>";
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";

		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Subdepto</b></font>";
    	  echo "<form name='form' method='post' action='estudios.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro3' class='textosItalicos' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
		  $cSub=mysql_query("SELECT depd.subdepto,dep.nombre FROM dep,depd WHERE dep.departamento=depd.departamento");
		  while ($dep=mysql_fetch_array($cSub)){
				 echo "<option value=$dep[0]>".ucwords(strtolower($dep[1])).": <b>".ucwords(strtolower($dep[0]))."</b></option>";
                if($dep[0]==$filtro3){$Desdepto=$dep[1];}
		  }
		  echo "<option SELECTed value='*'>$Desdepto: $filtro3</option>";
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Propio</b></font>";
    	  echo "<form name='form' method='post' action='estudios.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro7' class='textosItalicos' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='S'>Si</option>";
          echo "<option value='N'>No</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro7</option></p>";		  
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Activo</b></font>";
    	  echo "<form name='form' method='post' action='estudios.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro5' class='textosItalicos' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='Si'>Si</option>";
          echo "<option value='No'>No</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5</option></p>";		  
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Proceso</b></font>";
    	  echo "<form name='form' method='post' action='estudios.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro' class='textosItalicos' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
		  echo "<option value='TOMA SANGUINEA'>Toma Sanguinea</option>";
		  echo "<option value='RECOLECCION DE MUESTRA'>Recoleccion de Muestra</option>";
		  echo "<option value='REALIZACION DE ESTUDIOS'>Realizacion de Estudios</option>";
		  echo "<option value='TOMA DE MUESTRA CORPORAL'>Toma de muestra Corporal</option>";
		  echo "<option value=SERVICIO>'Servicio'</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro</option></p>";		  
          echo "</select>";
		  echo"</b></td>";
		  echo "</form>";
		  echo"</tr></table>";


      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e2.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a class='seleccionar' href='$cLink?busca=$rg[estudio]'>editar</a></td>";
           //echo "<td align='center'><a href='$cLinkd?busca=$rg[medico]'><img src='lib/browse.png' alt='Detalle' border='0'></td>";
           //echo "<td align='center'><a href=ordenesnvas.php?Medico=$rg[medico]&op=md><img src='lib/ok.png' alt='Elige este reg' border='0'></td>";

           echo "<td align='center'><a href='estudiosd.php?busca=$rg[estudio]'><img src='lib/browse.png' title='Consumos por estudio' border='0'></a></td>";

           echo "<td align='center'><a href=javascript:wingral('estudiopdf.php?busca=$rg[estudio]')> <img src='images/print.png' alt='pdf' border='0'></a></td>";
           
           Display($aCps,$aDat,$rg);           

           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si')><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></a></td>";
           //echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";

           echo "</tr>";

           $nRng++;

      }

	
	}
     
    PonPaginacion7(true);           #-------------------pon los No.de paginas-------------------

//    CuadroInferior2($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  //<form action="recibe.php" method="post">
  //<label for="tags">buscar </label>

  ///<input id="tags" name="nombre">
  ///  <input name="Enviar" type="submit" />
///</form>


      echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

      echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

      echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]?filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'>";

      echo "<label for='tags'><b>Buscar:</b></label>&nbsp;<input id='tags' type='text' name='busca' size='30' maxlength='50' placeholder='Clave o Nombre'> &nbsp; &nbsp; &nbsp; ";

      echo "<input type='hidden' name='pagina' value='1'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

      echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'>Limpia pantalla</a>";

      echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

      echo "</form>";

      echo "</td></tr></table>";
  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
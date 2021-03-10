<?php

  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/libN.php");

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
  $Titulo ="Medicos";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 2; 
  $filtro    = $_REQUEST[filtro];       
  $filtro3    = $_REQUEST[filtro3];       
  $filtro5    = $_REQUEST[filtro5];       
  $filtro7    = $_REQUEST[filtro7];       
  $filtro9    = $_REQUEST[filtro9];       
  $Vta = $_REQUEST[Vta];
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
  
  #Deshago la busqueda por palabras(una busqueda inteligte;
  $Palabras  = str_word_count($busca);  //Dame el numero de palabras
  if($Palabras > 1){
     $P=str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" med.nombrec like '%$P[$i]%' ";}else{$BusInt=$BusInt." and med.nombrec like '%$P[$i]%' ";}
     }
  }else{
     $BusInt=" med.nombrec like '%$busca%' ";  
  }
  
 if($filtro<>'*'){
 	$filtro2="and med.clasificacion='$filtro'";
 }else{
	$filtro2="and med.clasificacion<>'*'";
 }
 
 if($filtro3<>'*'){
 	$filtro4="and med.status='$filtro3'";
 }else{
	$filtro4="and med.status<>'*'";
 }

 if($filtro5<>'*'){
 	$filtro6="and med.promotorasig='$filtro5'";
 }else{
	$filtro6="and med.promotorasig<>'*'";
 }
 
 if($filtro7<>'*'){
 	$filtro8="and med.zona='$filtro7'";
 }else{
	$filtro8="and med.zona<>'*'";
 }
 
 if($filtro9<>'*'){
 	$filtro10="and med.ruta='$filtro9'";
 }else{
	$filtro10="and med.ruta<>'*'";
 }

  if(substr($busca,0,1)=='-'){
     $busca=substr($busca,1);
     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE med.medico='$busca' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],med.medico FROM $Qry[froms] WHERE $BusInt $Qry[filtro] $filtro2 $filtro4 $filtro6 $filtro8 $filtro10";
     //echo $cSql;
  }   
  

  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Selecc","-","-","Ed","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array("Eliminar","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");							//Parametros de colores;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

   echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

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
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Ruta</b></font>";
    	  echo "<form name='form' method='post' action='medicosN.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'>";
          echo "<select size='1' name='filtro9' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
  			 $RtaA=mysql_query("SELECT id,descripcion FROM ruta ORDER BY id");
             while ($Rta=mysql_fetch_array($RtaA)){
                   echo "<option value=$Rta[id]> $Rta[descripcion]</option>";
                   if($Rta[id]==$filtro9){$Des1=$Rta[descripcion];}
             }
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro9 $Des1</option></p>";		  
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Zona</b></font>";
    	  echo "<form name='form' method='post' action='medicosN.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'>";
          echo "<select size='1' name='filtro7' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
  			 $ZnaA=mysql_query("SELECT zona,descripcion FROM zns order by zona");
          while($Zna=mysql_fetch_array($ZnaA)){
                echo "<option value=$Zna[zona]> $Zna[zona]&nbsp;$Zna[descripcion]</option>";
                if($Zna[zona]==$filtro7){$DesZna=$Zna[descripcion];}
          }
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro7 $DesZna</option></p>";		  
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Promotor</b></font>";
    	  echo "<form name='form' method='post' action='medicosN.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'>";
          echo "<select size='1' name='filtro5' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='Promotor_A'>Promotor_A</option>";
          echo "<option value='Promotor_B'>Promotor_B</option>";
          echo "<option value='Promotor_C'>Promotor_C</option>";
          echo "<option value='Promotor_D'>Promotor_D</option>";
          echo "<option value='Promotor_E'>Promotor_E</option>";
          echo "<option value='Promotor_F'>Promotor_F</option>";
          echo "<option value='Base'>Base</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5</option></p>";		  
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Clasif</b></font>";
    	  echo "<form name='form' method='post' action='medicosN.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'>";
          echo "<select size='1' name='filtro' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='A'>A</option>";
          echo "<option value='B'>B</option>";
          echo "<option value='C'>C</option>";
          echo "<option value='D'>D</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro</option></p>";		  
          echo "</select>";
		  echo"</b></td><p>";
		  echo "</form>";
		  echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Status</b></font>";
    	  echo "<form name='form' method='post' action='medicosN.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'>";
          echo "<select size='1' name='filtro3' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
          echo "<option value='Activo'>Activo</option>";
          echo "<option value='Inactivo'>Inactivo</option>";
          echo "<option value='Defuncion'>Defuncion</option>";
          echo "<option value='Baja'>Baja</option>";
          echo "<option value='Otro'>Otro</option>";
		  echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3</option></p>";		  
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
           //echo "<td align='center'><a href='$cLinkd?busca=$rg[medico]'><img src='lib/browse.png' alt='Detalle' border='0'></td>";
  			if($rg[status]=='Baja' or $rg[status]=='Defuncion'){
			   echo "<td align='center'><img src='lib/iconfalse.gif' alt='Elige este reg' border='0'></td>";			   
			}else{
			   echo "<td align='center'><a href=ordenesnvasN.php?Medico=$rg[medico]&op=md&Vta=$Vta><img src='lib/ok.png' alt='Elige este reg' border='0'></td>";			   
			}
           echo "<td align='center'><a href='$cLink?busca=$rg[medico]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";

           Display($aCps,$aDat,$rg);           

           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si')><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></a></td>";
           echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";

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
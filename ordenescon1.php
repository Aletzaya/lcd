<?php

  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/lib.php");
  require ("config.php");

  $link     = conectarse();  
  
  $Usr    = $_COOKIE['USERNAME'];

  if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar  
  if(isset($_REQUEST[Sort]))   { $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc o Desc
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena	
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults
  $busca    = $_SESSION[busca];        //Dato a busca
    
  #Variables comunes;
  $Titulo = "Ordenes de estudio";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 6; 
  
  $Fecha=date("Y-m-d");

  $Fech2	=	$_REQUEST[Fech2];
  if (!isset($Fech2)){
      $Fech2 = date("Y-m-d");
  }

  $Fech3	=	$_REQUEST[Fech3];

  if (!isset($Fech3)){
      $Fech3 = date("Y-m-d");
  }

  if ($Fech2>$Fech3){
	  echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }

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
  
  if( $busca == ''){
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,entemailmed,entemailinst 
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente and ot.fecha>='$Fech2' and ot.fecha<='$Fech3'";
  }elseif( $busca < 'a'){
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,entemailmed,entemailinst 
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND ot.orden >= '$busca' $Qry[filtro]";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,entemailmed,entemailinst 
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND $BusInt $Qry[filtro]";
  }   
  //echo $cSql;
  
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Ed","-","-","Det","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array("Pgda","-","-","Rec","-","-","Engda","-","-","Correo","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");							//Parametros de colores;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>
<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<script language="JavaScript1.2">

function cFocus(){
  document.form10.busca.focus();
}

</script>

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

		echo "<form name='form' method='post' action='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3'>";
	
		echo "&nbsp; $Gfont <b> DE: $Fech2 </b></font><input type='hidden' readonly='readonly' name='Fech2' size='10' value ='$Fech2' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech2,'yyyy-mm-dd',this)>";
			
		echo "&nbsp; $Gfont <b><b> A: $Fech3 </b></font><input type='hidden' readonly='readonly' name='Fech3' size='10' value ='$Fech3' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech3,'yyyy-mm-dd',this)>";
	
		echo "</form>";
		
      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a href=ordenese.php?busca=$rg[orden]&pagina=$pagina><img src='lib/edit.png' alt='Edita registro' border='0'></a></td>";
           echo "<td align='center'><a href=ordenesd.php?busca=$rg[orden]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";

           Display($aCps,$aDat,$rg); 
		             
			if($rg[pagada]=='Si'){ 
				echo "<td align='center'>$Gfont $rg[pagada]</font></td>";
            }else{
				echo "<td align='center'><a class='pg' href=javascript:winuni('ingreso2.php?busca=$rg[orden]&boton=Enviar')$Gfont <font color='#F000000'> <b> No </b></font></a></td>";
            }
			
			if($rg[encaja]=='Si' OR $rg[status]=='Entregada'){ 
				echo "<td align='center'><a class='pg' href=javascript:winuni2('ordenrec.php?Recibeencaja=$Usr&Orden=$rg[orden]&boton=Enviar')><img src='lib/slc.png' border='0'></a></td>";
            }else{
				  echo "<td align='center'><a class='pg' href=javascript:winuni2('ordenrec.php?Recibeencaja=$Usr&Orden=$rg[orden]&boton=Enviar')$Gfont $rg[encaja]</a></td>";
            }
			
			if($rg[status]<>'Entregada'){    
				echo "<td align='center'><a class='pg' href=javascript:winuni2('ordenpac.php?Entregapac=$Usr&Orden=$rg[orden]&boton=Enviar')$Gfont No</a></td>";
            }else{
				echo "<td align='center'><a class='pg' href=javascript:winuni2('ordenpac.php?Entregapac=$Usr&Orden=$rg[orden]&boton=Enviar')><img src='lib/slc.png' border='0'></a></td>";
            }

			if($rg[entemailpac]=='1' or $rg[entemailmed]=='1' or $rg[entemailinst]=='1'){ 
				echo "<td align='center'><a class='pg' href=javascript:winuni2('entregamail.php?Orden=$rg[orden]')><img src='lib/email.png' border='0'></a></td>";   
            }else{
				echo "<td align='center'><a class='pg' href=javascript:winuni2('entregamail.php?Orden=$rg[orden]')>$Gfont No</a></td>";
            }
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
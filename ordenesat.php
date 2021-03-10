<?php

session_start();
  
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
  
require("lib/lib.php");

$link     = conectarse();  
$Usr    = $_COOKIE['USERNAME'];

if(isset($_REQUEST[busca])){        
    if($_REQUEST[busca] == ini ){                 
      //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)      
      $_SESSION["OnToy"] = array('','','ot.orden','Asc',$Retornar);         
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

$Suc=$_REQUEST[Suc];
$Serv=$_REQUEST[Serv];
$Etq=$_REQUEST[Etq];
$Rlz=$_REQUEST[Rlz];
$Cap=$_REQUEST[Cap];
$ERecp=$_REQUEST[ERecp];
$EPac=$_REQUEST[EPac];
$Imp=$_REQUEST[Imp];
//$Fecha=$_REQUEST[Fecha];
$con=$_REQUEST[con];

if($con==1){
	$Fecha=$_REQUEST[Fecha];
}else{
	$Fecha = date("Y-m-d");
}

#Variables comunes;
$Titulo = "Atencion a ordenes de trabajo";
$op     = $_REQUEST[op];
$Msj    = $_REQUEST[Msj];
$Id     = 6; 
  
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
  
if($Suc=='*'){
	$Suc2="";
}else{
	$Suc2="and ot.suc='$Suc'";
}

if($Serv=='*'){
	$Serv2="";
}else{
	$Serv2="and ot.servicio='$Serv'";
}

if($Etq=='*'){
	$Etq2="";
}elseif($Etq=='Si'){
	$Etq2="and ot.etiqueta='$Etq'";
}else{
	$Etq2="and ot.etiqueta='$Etq'";
}

if($Rlz=='*'){
	$Rlz2="";
}elseif($Rlz=='Si'){
	$Rlz2="and ot.realizacion='$Rlz'";
}elseif($Rlz=='No'){
	$Rlz2="and ot.realizacion='$Rlz'";
}else{
	$Rlz2="and ot.realizacion='$Rlz'";
}

if($Cap=='*'){
	$Cap2="";
}elseif($Cap=='Si'){
	$Cap2="and ot.captura='$Cap'";
}elseif($Cap=='No'){
	$Cap2="and ot.captura='$Cap'";
}else{
	$Cap2="and ot.captura='$Cap'";
}

if($Imp=='*'){
	$Imp2="";
}elseif($Imp=='Si'){
	$Imp2="and ot.impreso='$Imp'";
}elseif($Imp=='No'){
	$Imp2="and ot.impreso='$Imp'";
}else{
	$Imp2="and ot.impreso='$Imp'";
}

if($ERecp=='*'){
	$ERecp2="";
}elseif($ERecp=='Si'){
	$ERecp2="and ot.encaja='$ERecp'";
}elseif($ERecp=='No'){
	$ERecp2="and ot.encaja='$ERecp'";
}else{
	$ERecp2="and ot.encaja='$ERecp'";
}

if($EPac=='*'){
	$EPac2="";
}elseif($EPac=='Si'){
	$EPac2="and ot.status='Entregada'";
}elseif($EPac=='No'){
	$EPac2="and ot.status<>'Entregada'";
}else{
	$EPac2=" ";
}
#Armo el query segun los campos tomados de qrys;
$cSql = "SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.suc,ot.hora,ot.horae,ot.servicio,
		ot.etiqueta,ot.realizacion,ot.captura,ot.impreso,ot.pagada
	     FROM $Qry[froms] LEFT JOIN cli ON ot.cliente=cli.cliente 
         WHERE ot.fecha='$Fecha' AND $BusInt $Qry[filtro] $Suc2 $Serv2 $Etq2 $Rlz2 $Cap2 $ERecp2 $EPac2 $Imp2";
//}   
  

//echo $cSql;

$aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
$aIzq   = array(" ","-","-"," ","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
$aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
$aDer   = array("Pgda","-","-","Rec","-","-","Engda","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
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
 
	echo "<form name='form' method='post' action='ordenesat.php?Suc=*&Serv=*&Etq=*&Rlz=*&Cap=*&ERecp=*&EPac=*&Imp=*'>";
	echo "$Gfont<font size='1' class='Estilo9'><b>Fecha: </b></font>";
	echo "<input type='text' class='Estilo9' name='Fecha' size='9' value ='$Fecha' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fecha,'yyyy-mm-dd',this)>";
    echo "<input type='HIDDEN'  name='con' value='1'>";
	echo "</form>";
		
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

          echo "<table align='center' width='99%' border='0' cellspacing='0' cellpadding='2'>";

          echo "<tr height='20'>";
		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          
		  $SucA=mysql_query("select id from cia",$link); 		  
          echo "<select name='Suc' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>Suc*</option>";
          while ($Suc=mysql_fetch_array($SucA)){
                echo "<option value='$Suc[id]'>Suc $Suc[id]</option>";
          }
          $Suc=$_REQUEST[Suc];
		  echo "<option selected value='*'>Suc $_REQUEST[Suc]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
	      echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  echo "</form>";
//          echo "<td align='center'>$Gfont<font size='1'><b>Edit</b></td>";
//          echo "<td align='center'>$Gfont<font size='1'><b>Encab</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Inst-Ord</b></td>";
          echo "<td align='center'>$Gfont<font size='1'><b>Fech/Hra</b></td>";
//          echo "<td align='center'>$Gfont<font size='1'><b>Fech/Entr</b></td>";
		  echo "<td align='center'>$Gfont<font size='1'><b>Cliente</b></td>";
		  echo "<td align='center'>$Gfont<font size='1'><b>Importe</b></td>";
		  echo "<td align='center'>$Gfont<font size='1'><b>Recep</b></td>";
		  
		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          echo "<select name='Serv' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>*Todos</option>";
		  echo "<option value='Ordinaria'>Ordinaria</option>";
		  echo "<option value='Urgente'>Urgente</option>";
		  echo "<option value='Express'>Express</option>";
		  echo "<option value='Hospitalizado'>Hospitalizado</option>";
		  echo "<option value='Nocturno'>Nocturno</option>";
          $Serv=$_REQUEST[Serv];
		  echo "<option selected value='*'>Serv $_REQUEST[Serv]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
		  echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  echo "</form>";

		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          echo "<select name='Etq' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>*Todos</option>";
		  echo "<option value='Si'>Si</option>";
		  echo "<option value='No'>No</option>";
          $Etq=$_REQUEST[Etq];
		  echo "<option selected value='*'>Etq $_REQUEST[Etq]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
		  echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  echo "</form>";
		  
		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          echo "<select name='Rlz' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>*Todos</option>";
		  echo "<option value='Si'>Si</option>";
		  echo "<option value='No'>No</option>";
		  echo "<option value='PD'>PD</option>";
          $Rlz=$_REQUEST[Rlz];
		  echo "<option selected value='*'>Rlz $_REQUEST[Rlz]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
		  echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  echo "</form>";

		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          echo "<select name='Cap' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>*Todos</option>";
		  echo "<option value='Si'>Si</option>";
		  echo "<option value='No'>No</option>";
          $Cap=$_REQUEST[Cap];
		  echo "<option selected value='*'>Cap $_REQUEST[Cap]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
		  echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  echo "</form>";

		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          echo "<select name='Imp' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>*Todos</option>";
		  echo "<option value='Si'>Si</option>";
		  echo "<option value='No'>No</option>";
          $Imp=$_REQUEST[Imp];
		  echo "<option selected value='*'>Imp $_REQUEST[Imp]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
		  echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  echo "</form>";

		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          echo "<select name='ERecp' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>*Todos</option>";
		  echo "<option value='Si'>Si</option>";
		  echo "<option value='No'>No</option>";
          $ERecp=$_REQUEST[ERecp];
		  echo "<option selected value='*'>ERecp $_REQUEST[ERecp]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
		  echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  echo "</form>";

		  echo "<td align='center'>$Gfont<font size='-1'>";
    	  echo "<form name='form' method='post' action='ordenesat.php?Suc=$Suc&Serv=$Serv&Etq=$Etq&Rlz=$Rlz&Cap=$Cap&ERecp=$ERecp&EPac=$EPac&Imp=$Imp'>";
          echo "<select name='EPac' class='Estilo9' onchange=this.form.submit()>";
          echo "<option value='*'>*Todos</option>";
		  echo "<option value='Si'>Si</option>";
		  echo "<option value='No'>No</option>";
          $EPac=$_REQUEST[EPac];
		  echo "<option selected value='*'>EPac $_REQUEST[EPac]</option></p>";
          echo "</select></p>";
		  echo"</b></td>";
		  echo "<input type='HIDDEN'  name='con' value='1'>";
	      echo "<input type='HIDDEN'  name='Fecha' value='$Fecha'>";
		  
		  echo "</form>";
          echo "</tr>";              
		  
		  
//        PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

			  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
			  echo "<td align='center'>$Gfont <font size='1'><b>$rg[suc]</b></font></td>";
			  echo "<td align='right'>$Gfont <font size='1'>$rg[institucion] - </font><font><b>$rg[orden]</b></font></td>";
			  $hora2=substr($rg[hora],0,5);
			  echo "<td>$Gfont <font size='1'>$rg[fecha] - <b>$hora2</b></font></td>";
//			  echo "<td>$Gfont <font size='1'><b>$rg[fechae] - $rg[horae]</b></font></td>";
			  echo "<td>$Gfont $rg[nombrec]</font></td>";
			  //echo "<td align='right'>$Gfont <font size='1'><b>".number_format($rg[importe],"2")."</b></font></td>";
			
				if($rg[pagada]=='Si'){ 
					echo "<td align='right'>$Gfont <font size='1'><font color='#000000'>".number_format($rg[importe],"2")."</font></td>";
				}else{
					echo "<td align='right'><a class='pg' href=javascript:winuni('ingreso2.php?busca=$rg[orden]&boton=Enviar')
					$Gfont <font color='#F00000'><font size='1'>".number_format($rg[importe],"2")."</font></a></td>";
				}

			  echo "<td>$Gfont <font size='1'>$rg[recepcionista]</font></td>";

			  if($rg[servicio]=='Urgente'){ 
				  echo "<td>$Gfont <font color='#F000000'>$rg[servicio]</font></td>";
			  }else{
				  echo "<td>$Gfont $rg[servicio]</font></td>";
			  }

			  if($rg[etiqueta]=='Si'){ 
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atnetiqueta.php?Orden=$rg[orden]')><img src='lib/slc.png' border='0'></a></td>";
			 }else{
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atnetiqueta.php?Orden=$rg[orden]')>$Gfont No</a></td>";
			 }

			  if($rg[realizacion]=='Si'){ 
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atnrealizacion.php?Orden=$rg[orden]')><img src='lib/slc.png' border='0'></a></td>";
			 }elseif($rg[realizacion]=='PD'){
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atnrealizacion.php?Orden=$rg[orden]')><img src='lib/advertencia.png' border='0'></a></td>";
			 }else{
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atnrealizacion.php?Orden=$rg[orden]')>$Gfont No</a></td>";
			 }

			  if($rg[captura]=='Si'){ 
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atncaptura.php?Orden=$rg[orden]')><img src='lib/slc.png' border='0'></a></td>";
			 }elseif($rg[captura]=='PD'){
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atncaptura.php?Orden=$rg[orden]')><img src='lib/advertencia.png' border='0'></a></td>";
			 }else{
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atncaptura.php?Orden=$rg[orden]')>$Gfont No</a></td>";
			 }

			  if($rg[impreso]=='Si'){ 
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atnimpresion.php?Orden=$rg[orden]')><img src='lib/slc.png' border='0'></a></td>";
			 }else{
				echo "<td align='center'><a class='pg' href=javascript:winuni2('atnimpresion.php?Orden=$rg[orden]')>$Gfont No</a></td>";
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
           echo "</tr>";
           $nRng++;

      }

    }
     
    PonPaginacion4(true);           #-------------------pon los No.de paginas-------------------

    CuadroInferior4($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
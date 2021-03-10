<?php

session_start();
  
include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");
  
require("lib/lib.php");

$link     = conectarse();  
$Usr        = $check['uname'];
if(isset($_REQUEST[busca])){        
    if($_REQUEST[busca] == ini ){                 
      //Inicio arreglo(0=busca,1=pagina,2=orden,3=Asc,4=a donde regresa)      
      $_SESSION["OnToy"] = array('','','dpag_ref.tipopago','Asc',$Retornar);         
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
  
#Variables comunes;
$Titulo = "Diferentes pagos";
$op     = $_REQUEST[op];
$Msj    = $_REQUEST[Msj];
$Id     = 56; 

  $Fecha=date("Y-m-d");

  $Fech2  = $_REQUEST[Fech2];
  if (!isset($Fech2)){
      $Fech2 = date("Y-m-d");
  }

  $Fech3  = $_REQUEST[Fech3];

  if (!isset($Fech3)){
      $Fech3 = date("Y-m-d");
  }

  if ($Fech2>$Fech3){
    echo '<script language="javascript">alert("Fechas incorrectas... Verifique");</script>'; 
  }

if($_REQUEST[Ac]== 'Cancelada'){
    $Up=mysql_query("UPDATE dpag_ref SET cancelada = '1',cancelpor='$Usr' WHERE id=$_REQUEST[cId]");
}

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

#Armo el query segun los campos tomados de qrys;
$cSql= "SELECT $Qry[campos],dpag_ref.cerrada,cptpagod.referencia,cpagos.concepto conc_pag,dpag_ref.modifica FROM $Qry[froms] LEFT JOIN cptpagod "
        . "ON dpag_ref.id_ref=cptpagod.id LEFT JOIN cpagos ON dpag_ref.tipopago=cpagos.id WHERE $BusInt"
        . "AND cancelada='0'";
//echo $cSql;

$aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
$aIzq   = array(" ","-","-","Imp","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
$aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
$aDer   = array(" ","-","-","Mod","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
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

    echo "<form name='form' method='post' action='ctpagos.php?Fech2=$Fech2&Fech3=$Fech3'>";
  
    echo "&nbsp; $Gfont <font size='2'><b> DE: $Fech2 </b></font><input type='hidden' readonly='readonly' name='Fech2' size='10' value ='$Fech2' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech2,'yyyy-mm-dd',this)>";
      
    echo "&nbsp; $Gfont <font size='2'><b> A: $Fech3 </b></font><input type='hidden' readonly='readonly' name='Fech3' size='10' value ='$Fech3' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech3,'yyyy-mm-dd',this)>";
  
    echo "</form>";

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if($rg[modifica]==0){

              if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           }else{

              $Fdo='#e6b0aa';   //El resto de la division;

           }

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a class='seleccionar' href='$cLink?busca=$rg[id]'>Editar</a></td>";
           if($rg[cerrada] == '1'){
               echo "<td align='center'><a class='seleccionar' href=javascript:winuni('impgastos.php?busca=$rg[id]')><img src='lib/print.png' alt='Imprimir' border='0'></a></td>";
           }else{
               echo "<td align='center'><a class='seleccionar'> - </a></td>";
           }
           echo "<td>$Gfont <font size='1'> $rg[id]</font></td>";
           echo "<td align='right'>$Gfont <font size='1'> $rg[orden_h]</font></td>";
           echo "<td>$Gfont <font size='1'> $rg[referencia]</font></td>";
           echo "<td align='right'>$Gfont <font size='1'> $rg[fecha]</font></td>";
           echo "<td>$Gfont <font size='1'> $rg[observaciones]</font></td>";
           echo "<td align='right'>$Gfont <font size='1'> ".number_format($rg[monto],"2")."</font></td>";
           echo "<td>$Gfont <font size='1'> $rg[conc_pag]</font></td>";
           echo "<td align='right'>$Gfont <font size='1'> $rg[fechapago]</font></td>";
           echo "<td>$Gfont <font size='1'> $rg[recibe]</font></td>";
           echo "<td>$Gfont <font size='1'> $rg[concept]</font></td>";
           echo "<td>$Gfont <font size='1'> $rg[hospi]</font></td>";
           echo "<td>$Gfont <font size='1'> $rg[autoriza]</font></td>";
           echo "<td>$Gfont <font size='1'> <a class='seleccionar' href='ctpagos.php?Ac=Cancelada&cId=$rg[id]'>Cancelar</a></font></td>";
           if($rg[modifica]==0){

              echo "<td>$Gfont <font size='1'> </font></td>";

           }else{

              echo "<td>$Gfont <font size='1' color='red'><b>$rg[modifica]<b></font></td>";
           
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
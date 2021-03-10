<?php
  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/lib.php");

  $link     = conectarse();  

  if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar  
  if(isset($_REQUEST[Sort]))   {  $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc o Desc
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults
  $busca    = $_SESSION[busca];        //Dato a busca
  $Inst    = $_REQUEST[Inst];
  $contenido    = $_REQUEST[contenido];        //Dato a busca
  $Vta = $_REQUEST[Vta];


  $InsA   = mysql_query("SELECT alias,lista,descuento,condiciones,msjadministrativo FROM inst WHERE institucion='$Inst'",$link);    #Checo la lista de la institucion
  $Ins    = mysql_fetch_array($InsA);
    
  #Variables comunes;
  $Titulo = "Catalogo de estudios: ".$Inst." - ".$Ins[alias];
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
//  $Id     = 10;
  $Id     = 48; 
  
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

  $con = "select est.estudio, est.descripcion, est.activo from est where est.activo='Si'";//consulta para seleccionar las palabras a buscar, esto va a depender de su base de datos
  $query = mysql_query($con);
  
  #Deshago la busqueda por palabras(una busqueda inteligte;
  $Palabras  = str_word_count($busca);  //Dame el numero de palabras
  if($Palabras > 1){
     $P=str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" est.descripcion like '%$P[$i]%' ";}else{$BusInt=$BusInt." and est.descripcion like '%$P[$i]%' ";}
     }
  }else{
      if(!empty($contenido)){
        $BusInt=" est.contenido like '%$contenido%'"; 
      }else{
        $BusInt=" est.descripcion like '%$busca%'"; 
      }
   }
  
  $Lprecios="lt".$Ins[lista];

  if(substr($busca,0,1)=='-'){
     $busca = substr($busca,1);
     $cSql  = "SELECT $Qry[campos],activo,$Lprecios as listapre FROM $Qry[froms] WHERE est.estudio='$busca' and est.activo='Si'";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],activo,$Lprecios as listapre FROM $Qry[froms] WHERE $BusInt $Qry[filtro] and est.activo='Si'";
  }   
  
  //echo $cSql;
  
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
     
  $aIzq   = array("","-","-","Precio","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  //$aDer   = array("","-","-");                //Arreglo donde se meten los encabezados; Derechos;
  $aDer   = array();                //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");							//Parametros de colores;



echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
echo '<html>';
echo '<head>';

echo "<script language='JavaScript' src='js/jquery-1.5.1.min.js'></script>";
echo "<script language='JavaScript' src='js/jquery-ui-1.8.13.custom.min.js'></script>";
echo "<link type='text/css' href='css/ui-lightness/jquery-ui-1.8.13.custom.css' rel='stylesheet' />";
   
   headymenu($Titulo,1);
?>


<script language="JavaScript1.2">
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
	alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}


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

$res = mysql_query($cSql . $cWhe, $link);

CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

$sql = $cSql . $cWhe . " ORDER BY " . $orden . " ASC LIMIT " . $limitInf . "," . $tamPag;
$res = mysql_query($sql, $link);

PonEncabezado();         #---------------------Encabezado del browse----------------------

while ($registro = mysql_fetch_array($res)) {
    
    if (($nRng % 2) > 0) {
        $Fdo = 'FFFFFF';
    } else {
        $Fdo = $Gfdogrid;
    }    //El resto de la division;

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
  	if($registro[activo]=='No'){
 	    echo "<td align='center'><img src='lib/iconfalse.gif' alt='Elige este reg' border='0'></td>";			   
	}else{
    	echo "<td>$Gfont <a class='pg' href='ordenesnvasN.php?op=Estudio&Estudio=$registro[estudio]&Vta=$Vta'>Seleccionar</a></td>";
	}

      echo "<td align='right'>$Gfont $ ".number_format($registro[listapre],"2")."</td>";

     Display($aCps,$aDat,$registro);           

/*
    echo "<td>$Gfont $registro[estudio]</td>";
    echo "<td>$Gfont $registro[descripcion]</td>";
    //echo "<td>$Gfont $registro[muestras]</td>";
    echo "<td>$Gfont $registro[entord]</td>";
    echo "<td>$Gfont $registro[subdepto]</td>";
    echo "<td>$Gfont $registro[proceso]</td>";
    echo "<td>$Gfont </td>";
    echo "<td>$Gfont </td>";
    echo "<td>$Gfont </td>";
    echo "<td>$Gfont $registro[activo]</td>";
  */  
    echo "</tr>";
    $nRng++;
    
}//fin while

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";

echo "<tr><td>$Gfont <font color='#c0392b'> $Ins[msjadministrativo] </font></td></tr>";

echo "</table>";

PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

//CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

echo "<tr><td width='20%'>$Gfont ";

echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]'>";

echo "<label for='tags'><b>Buscar:</b></label>&nbsp;<div><input id='tags' type='text' name='busca' size='30' maxlength='50' placeholder='Clave o Nombre'> &nbsp; &nbsp; &nbsp; ";

echo "<input type='hidden' name='pagina' value='1'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;
echo "<input type='hidden' name='Inst' value='$Inst'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;
echo "<input type='hidden' name='Vta' value='$Vta'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

 echo "</form></td>";

echo "<td>$Gfont <form name='form11' method='post' action='$_SERVER[PHP_SELF]?'>";

echo "<b>Contenido:</b></label>&nbsp;<input type='text' name='contenido' size='30' maxlength='50' placeholder='Estudio que contenga'> &nbsp; &nbsp; &nbsp; ";

echo "<input type='hidden' name='pagina' value='1'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;
echo "<input type='hidden' name='Inst' value='$Inst'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;
echo "<input type='hidden' name='Vta' value='$Vta'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&orden=est.estudio&busca=&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Inst=$Inst&Vta=$Vta'>Limpia pantalla</a></div>";

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

echo "</form>";

echo "</td></tr></table>";


echo " <a href='ordenesnvasN.php?&Vta=$Vta'><img src='lib/regresar.gif' border=0></a> ";


mysql_close();

echo "</body>";

echo "</html>";

?>
	

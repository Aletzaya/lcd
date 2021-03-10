<?php
  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/libN.php");

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
  $Vta = $_REQUEST[Vta];
  #Variables comunes;
  $Titulo = "Catalogo de estudios";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 10; 
  
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
            if(!isset($BusInt)){$BusInt=" est.descripcion like '%$P[$i]%' ";}else{$BusInt=$BusInt." and est.descripcion like '%$P[$i]%' ";}
     }
  }else{
     $BusInt=" est.descripcion like '%$busca%' ";  
  }
  
  
  if(substr($busca,0,1)=='-'){
     $busca = substr($busca,1);
     $cSql  = "SELECT $Qry[campos],activo FROM $Qry[froms] WHERE est.estudio='$busca'";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],activo FROM $Qry[froms] WHERE $BusInt $Qry[filtro]";
  }   
  
  //echo $cSql;
  

  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array("Activo","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");							//Parametros de colores;



echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
echo '<html>';
echo '<head>';
 
   
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

    echo "<td>$Gfont <a class='pg' href='ordenesnvasN.php?op=Estudio&Estudio=$registro[estudio]&Vta=$Vta'>Seleccionar</a></td>";
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
    echo "</tr>";
    $nRng++;
    
}//fin while

PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------


echo " <a href='ordenesnvas.php'><img src='lib/regresar.gif' border=0></a> ";


mysql_close();

echo "</body>";

echo "</html>";

?>
	

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
  $Titulo = "Lista de precios";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 38; 
  
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
            if(!isset($BusInt)){$BusInt=" est.descripcion like '%$P[$i]%' ";}else{$BusInt=$BusInt." and est.descripcion like '%$P[$i]%' ";}
     }
  }else{
     $BusInt=" est.descripcion like '%$busca%' ";  
  }
  
  
//  if( $busca < 'a'){
//     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE  $BusInt";
//  }else{
//     #Armo el query segun los campos tomados de qrys;
//     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt";
//  }   
  //echo $cSql;
  
  if(substr($busca,0,1)=='-'){
     $busca = substr($busca,1);
     $cSql  = "SELECT $Qry[campos] FROM $Qry[froms] WHERE est.estudio='$busca'";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt $Qry[filtro]";
  }   

  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array(" ","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
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

        echo "<div align='left'>";
        echo "&nbsp;&nbsp;&nbsp; <a class='pg' href=javascript:winuni('lisins.php?cKey=1')>1</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=2')>2</a> 
		<a class='pg' href=javascript:winuni('lisins.php?cKey=3')>3</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=4')>4</a> ";
        echo "<a class='pg' href=javascript:winuni('lisins.php?cKey=5')>5</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=6')>6</a> 
		<a class='pg' href=javascript:winuni('lisins.php?cKey=7')>7</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=8')>8</a> 
		<a class='pg' href=javascript:winuni('lisins.php?cKey=9')>9</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=10')>10</a> ";
	echo "<a class='pg' href=javascript:winuni('lisins.php?cKey=11')>11</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=12')>12</a> 
	<a class='pg' href=javascript:winuni('lisins.php?cKey=13')>13</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=14')>14</a> 
	<a class='pg' href=javascript:winuni('lisins.php?cKey=15')>15</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=16')>16</a> 
	<a class='pg' href=javascript:winuni('lisins.php?cKey=17')>17</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=18')>18</a>
	<a class='pg' href=javascript:winuni('lisins.php?cKey=19')>19</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=20')>20</a>
  <a class='pg' href=javascript:winuni('lisins.php?cKey=21')>21</a> <a class='pg' href=javascript:winuni('lisins.php?cKey=22')>22</a>
  <a class='pg' href=javascript:winuni('lisins.php?cKey=23')>23</a> </div>";
      
      
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
           //echo "<td align='center'><a href='$cLink?busca=$rg[cliente]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
           //echo "<td align='center'><a href='$cLinkd?busca=$rg[medico]'><img src='lib/browse.png' alt='Detalle' border='0'></td>";
           //echo "<td align='center'><a href=ordenesnvas.php?Cliente=$rg[cliente]&op=cl><img src='lib/ok.png' alt='Elige este reg' border='0'></td>";

           echo "<td align='center'><a class='pg' href=listae.php?busca=$rg[estudio]&pagina=$pagina>editar</a></td>";
           //echo "<td align='center'><a href=ordenesd.php?busca=$rg[orden]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";

           Display($aCps,$aDat,$rg);           

           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si')><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></a></td>";
           //echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";

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
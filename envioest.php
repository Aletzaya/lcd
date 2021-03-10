
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
  $Titulo = "Entrega de Estudios";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 49; 

  $filtro    = $_REQUEST[filtro];       
  $filtro3    = $_REQUEST[filtro3];       
  $filtro5    = $_REQUEST[filtro5];       
  $filtro7    = $_REQUEST[filtro7];       
  $filtro9    = $_REQUEST[filtro9];   
  
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

 if($filtro<>'*'){
  $filtro2="and ot.suc='$filtro'";
 }else{
  $filtro2="and ot.suc<>'*'";
 }
 
 if($filtro3<>'*'){
  $filtro4="and ot.institucion='$filtro3'";
 }else{
  $filtro4="and ot.institucion<>'*'";
 }

 if($filtro5<>'*'){
  $filtro6="and ot.tentregamost='$filtro5'";
 }else{
  $filtro6="and ot.tentregamost<>'*'";
 }
 
 if($filtro7<>'*'){
  $filtro8="and ot.tentregamed='$filtro7'";
 }else{
  $filtro8="and ot.tentregamed<>'*'";
 }

   if($filtro9=='*'){
      $filtro10='';
  }elseif($filtro9=='1'){
      $filtro10='and ot.entemailpac=1';
  }elseif($filtro9=='2'){
      $filtro10='and ot.entwhatpac=1';
  }elseif($filtro9=='3'){
      $filtro10='and ot.entemailmed=1';
  }elseif($filtro9=='4'){
      $filtro10='and ot.entwhatmed=1';
  }elseif($filtro9=='5'){
      $filtro10='and ot.entemailinst=1';
  }  
 
 if($filtro9<>'*'){
  $filtro10="and ot.tentregadig='$filtro9'";
 }else{
  $filtro10="and ot.tentregadig<>'*'";
 }
  

  if( $busca == ''){
     $cSql="SELECT $Qry[campos],ot.suc,ot.tentregamost,ot.tentregamed,ot.tentregadig,ot.institucion,ot.orden,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entwhatpac,ot.entwhatmed
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10";
  }elseif( $busca < 'a'){
     $cSql="SELECT $Qry[campos],ot.suc,ot.tentregamost,ot.tentregamed,ot.tentregadig,ot.institucion,ot.orden,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entwhatpac,ot.entwhatmed
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND ot.orden >= '$busca' $Qry[filtro]";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],ot.suc,ot.tentregamost,ot.tentregamed,ot.tentregadig,ot.institucion,ot.orden,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.entwhatpac,ot.entwhatmed
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND $BusInt $Qry[filtro] $filtro2 $filtro4 $filtro6 $filtro8 $filtro10";
  }   
  //echo $cSql;
  
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Detalle","-","-","Suc","-","-","Inst-Orden","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array("Mostrador","-","-","Domicilio","-","-","Digital","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
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
        echo "<table align='right' width='100%' border='0' cellspacing='0' cellpadding='0'>";
          echo "<tr align='right'>";
          echo "<td>";
		echo "<form name='form' method='post' action='envioest.php?Fech2=$Fech2&Fech3=$Fech3&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
	
		echo "&nbsp; $Gfont <b> DE: $Fech2 </b></font><input type='hidden' readonly='readonly' name='Fech2' size='10' value ='$Fech2' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech2,'yyyy-mm-dd',this)>";
			
		echo "&nbsp; $Gfont <b><b> A: $Fech3 </b></font><input type='hidden' readonly='readonly' name='Fech3' size='10' value ='$Fech3' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech3,'yyyy-mm-dd',this)>";
	
		echo "</form>";
          echo "</td>";


;
      echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Sucursal</b></font>";
        echo "<form name='form' method='post' action='envioest.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>* Todas *</option>";
          echo "<option value='1'>Matriz</option>";
          echo "<option value='2'>OHF</option>";
          echo "<option value='3'>Tepexpan</option>";
          echo "<option value='4'>Reyes</option>";
          echo "<option value='5'>Camarones</option>";
          if($filtro=='*'){
              $Des1='* Todas *';
          }elseif($filtro=='1'){
              $Des1='Matriz';
          }elseif($filtro=='2'){
              $Des1='OHF';
          }elseif($filtro=='3'){
              $Des1='Tepexpan';
          }elseif($filtro=='4'){
              $Des1='Reyes';
          }elseif($filtro=='5'){
              $Des1='Camarones';
          }
          echo "<option selected value='*'>$Gfont <font size='-1'>$filtro $Des1</option></p>";     
          echo "</select>";
      echo"</b></td><p>";
      echo "</form>";
      echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Institucion</b></font>";
        echo "<form name='form' method='post' action='envioest.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro3' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>Todos*</option>";
         $ZnaA=mysql_query("SELECT institucion,nombre FROM inst order by institucion");
          while($Zna=mysql_fetch_array($ZnaA)){
                echo "<option value=$Zna[institucion]> $Zna[institucion]&nbsp;$Zna[nombre]</option>";
                if($Zna[institucion]==$filtro3){$DesZna=$Zna[nombre];}
                if($filtro3=='*'){$DesZna='Todos*';}
          }
      echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3 $DesZna</option></p>";     
          echo "</select>";
      echo"</b></td><p>";
      echo "</form>";
      echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Mostrador</b></font>";
        echo "<form name='form' method='post' action='envioest.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro5' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>* Todos *</option>";
          echo "<option value='1'>Matriz</option>";
          echo "<option value='2'>OHF</option>";
          echo "<option value='3'>Tepexpan</option>";
          echo "<option value='4'>Reyes</option>";
          echo "<option value='5'>Camarones</option>";
          if($filtro5=='*'){
              $Des5='* Todos *';
          }elseif($filtro5=='1'){
              $Des5='Matriz';
          }elseif($filtro5=='2'){
              $Des5='OHF';
          }elseif($filtro5=='3'){
              $Des5='Tepexpan';
          }elseif($filtro5=='4'){
              $Des5='Reyes';
          }elseif($filtro=='5'){
              $Des1='Camarones';
          }          echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5 $Des5</option></p>";     
          echo "</select>";
      echo"</b></td><p>";
      echo "</form>";
            echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Domicilio</b></font>";
        echo "<form name='form' method='post' action='envioest.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro7' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>* Todos *</option>";
          echo "<option value='1'>Paciente</option>";
          echo "<option value='2'>Medico</option>";
          echo "<option value='3'>Institucion</option>";
          if($filtro7=='*'){
              $Des7='* Todos *';
          }elseif($filtro7=='1'){
              $Des7='Paciente';
          }elseif($filtro7=='2'){
              $Des7='Medico';
          }elseif($filtro7=='3'){
              $Des7='Institucion';
          }      
          echo "<option selected value='*'>$Gfont <font size='-1'>$filtro7 $Des7</option></p>";     
          echo "</select>";
      echo"</b></td><p>";
      echo "</form>";
            echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Digital</b></font>";
        echo "<form name='form' method='post' action='envioest.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
          echo "<select size='1' name='filtro9' class='Estilo10' onchange=this.form.submit()>";
          echo "<option value='*'>* Todos *</option>";
          echo "<option value='1'>Email/Pac</option>";
          echo "<option value='2'>Whatsapp/Pac</option>";
          echo "<option value='3'>Email/Med</option>";
          echo "<option value='4'>Whatsapp/Med</option>";
          echo "<option value='5'>Email/Inst</option>";
          if($filtro9=='*'){
              $Des9='* Todos *';
          }elseif($filtro9=='1'){
              $Des9='Email/Pac';
          }elseif($filtro9=='2'){
              $Des9='Whatsapp/Pac';
          }elseif($filtro9=='3'){
              $Des9='Email/Med';
          }elseif($filtro9=='4'){
              $Des9='Whatsapp/Med';
          }elseif($filtro9=='5'){
              $Des9='Email/Inst';
          }      
          echo "<option selected value='*'>$Gfont <font size='-1'>$filtro9 $Des9</option></p>";     
          echo "</select>";
      echo"</b></td><p>";
      echo "</form>";
      echo"</tr></table>";

		
      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           echo "<td align='center'><a href=ordenesnvasNe.php?busca=$rg[orden]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";

            echo "<td align='right'>$Gfont <font size='-1'>$rg[suc]</b></font></td>";
            echo "<td align='right'>$Gfont <font size='-1'>$rg[institucion] - <b>$rg[orden]</b></font></td>";

           Display($aCps,$aDat,$rg); 

           //ot.,ot.tentregamed,ot.tentregadig
		             
    			if($rg[tentregamost]==1){ 
    				echo "<td align='center'>$Gfont Matriz</font></td>";
          }elseif($rg[tentregamost]==2){
            echo "<td align='center'>$Gfont OHF</font></td>";
          }elseif($rg[tentregamost]==3){
            echo "<td align='center'>$Gfont Tepexpan</font></td>";
          }elseif($rg[tentregamost]==4){
            echo "<td align='center'>$Gfont Reyes</font></td>";
          }elseif($rg[tentregamost]==5){
            echo "<td align='center'>$Gfont Camarones</font></td>";
          }else{
            echo "<td align='center'>$Gfont </font></td>";
          }

          if($rg[tentregamed]==1){ 
            echo "<td align='center'>$Gfont Paciente</font></td>";
          }elseif($rg[tentregamed]==2){
            echo "<td align='center'>$Gfont Medico</font></td>";
          }elseif($rg[tentregamed]==3){
            echo "<td align='center'>$Gfont Institucion</font></td>";
          }else{
            echo "<td align='center'>$Gfont </font></td>";
          }

          if($rg[entemailpac]==1){ 

             //             echo "<td align='center'>$Gfont - </td>";


            echo "<td align='center'>$Gfont Digital </font> <a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$rg[orden]')><img src='lib/email.png' border='0'></a></td>";

          }elseif($rg[entemailmed]==1){
           ///               echo "<td align='center'>$Gfont - </td>";


           echo "<td align='center'>$Gfont Digital </font> <a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$rg[orden]')><img src='lib/email.png' border='0'></a></td>";

          }elseif($rg[entemailinst]==1){

           // echo "<td align='center'>$Gfont - </td>";

              echo "<td align='center'>$Gfont Digital </font> <a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$rg[orden]')><img src='lib/email.png' border='0'> </a></td>";

          }elseif($rg[entwhatpac]==1){
           // echo "<td align='center'>$Gfont - </td>";

              echo "<td align='center'>$Gfont Digital </font> <img src='lib/whatsapp2.png' border='0'></td>";

          }elseif($rg[entwhatmed]==1){

            //  echo "<td align='center'>$Gfont - </td>";
              echo "<td align='center'>$Gfont Digital </font> <img src='lib/whatsapp2.png' border='0'></td>";

          }else{

            echo "<td align='center'>$Gfont </font></td>";

          }

          echo "</tr>";

           $nRng++;

      }
	}
     
    PonPaginacion3(true);           #-------------------pon los No.de paginas-------------------

  //  CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .
echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&orden=ot.orden&busca=&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'>";

echo "<label for='tags'><b>Buscar:</b></label>&nbsp;<div><input id='tags' type='text' name='busca' size='30' maxlength='30' placeholder='Clave o Nombre'> &nbsp; &nbsp; &nbsp; ";

echo "<input type='hidden' name='pagina' value='1'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;
//echo "<input type='hidden' name='Inst' value='$Inst'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&orden=ot.orden&busca=&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'>Limpia pantalla</a></div>";

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

echo "</form>";

echo "</td></tr></table>";

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
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
  $busca    = $_REQUEST[busca];        //Dato a busca
  $Suc    = $_REQUEST[Suc];        //Dato a busca
  $NC    = $_REQUEST[NC];        //Dato a busca

  if($Suc=="*"){
    $Sucursal="";
  }else{
    $Sucursal=" and ot.suc='$Suc'";
  }

  if($NC=="NC"){
    $Nocon=" and ot.noconformidad='Si'";
  }else{
    $Nocon=" ";
  }
    
  #Variables comunes;
  $Titulo = "Ordenes de trabajo";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 53; 
  
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
     //$Suc='*';
  }else{
     $BusInt=" cli.nombrec like '%$busca%' ";  
    // $Suc='*';
  }
  
  if( $busca == ''){
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,entemailmed,entemailinst 
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' $Sucursal $Nocon";
  }elseif( $busca < 'a'){
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,entemailmed,entemailinst 
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND ot.orden >= '$busca' $Qry[filtro]";
   $Suc='*';
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,entemailmed,entemailinst 
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND $BusInt $Qry[filtro]";
  }   
  //echo $cSql;
  
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  //$aIzq   = array("Ed","-","-","Det","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array("X","-","-","Lab","-","-","Rx","-","-","Otro","-","-","Det","-","-","Sts","-","-","Email","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
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

		echo "<form name='form' method='post' action='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc'>";
	
		echo "&nbsp; $Gfont <font size='2'><b> DE: $Fech2 </b></font><input type='hidden' readonly='readonly' name='Fech2' size='10' value ='$Fech2' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech2,'yyyy-mm-dd',this)>";
			
		echo "&nbsp; $Gfont <font size='2'><b> A: $Fech3 </b></font><input type='hidden' readonly='readonly' name='Fech3' size='10' value ='$Fech3' onchange=this.form.submit()> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fech3,'yyyy-mm-dd',this)>";
	
		echo "</form>";


    if($Suc=='*'){
      $Todas="<img src='images/all.png' width='22'>";
    }else{
      $Todas="<img src='images/alls.png' width='18'>";
    }
    
    if($Suc=='1'){
      $uno="<img src='images/one2.png' width='22'>";
    }else{
      $uno="<img src='images/ones.png' width='18'>";
    }

    if($Suc=='2'){
      $dos="<img src='images/two2.png' width='22'>";
    }else{
      $dos="<img src='images/twos.png' width='18'>";
    }
    
    if($Suc=='3'){
      $tres="<img src='images/three2.png' width='22'>";
    }else{
      $tres="<img src='images/threes.png' width='18'>";
    }
    
    if($Suc=='4'){
      $cuatro="<img src='images/four2.png' width='22'>";
    }else{
      $cuatro="<img src='images/fours.png' width='18'>";
    }

    if($NC=='NC'){
      $NoConform="<a class='vt' href='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&NC=C'><img src='images/ErrorCircle.png' width='22'></a>";
    }else{
      $NoConform="<a class='vt' href='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&NC=NC'><img src='images/ErrorCircle.png' width='18'></a>";
    }

    echo "&nbsp; $Gfont <font size='2'><b> Suc: </b></font><a class='vt' href='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=*'>$Todas</a>&nbsp; &nbsp;<a class='vt' href='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=1'>$uno</a>&nbsp; &nbsp;<a class='vt' href='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=2'>$dos</a>&nbsp; &nbsp;<a class='vt' href='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=3'>$tres</a>&nbsp; &nbsp;<a class='vt' href='ordenespro.php?Fech2=$Fech2&Fech3=$Fech3&Suc=4'>$cuatro</a>&nbsp; &nbsp;$NoConform";
		
      PonEncabezadopro();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){
      
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

          $detA = mysql_query("SELECT otd.orden,otd.estudio,est.depto
          FROM otd,est
          WHERE otd.estudio=est.estudio and est.depto=1 AND otd.orden=$rg[orden]");

          if(mysql_num_rows($detA)==0){

           //if(!$det=mysql_query($detA)){

              $lab=" - ";

            }else{

              $lab="<img src='lib/slc.png' border='0'>";

            }


            $detB = mysql_query("SELECT otd.orden,otd.estudio,est.depto
            FROM otd,est
            WHERE otd.estudio=est.estudio and est.depto=2 AND otd.orden=$rg[orden]");

            if(mysql_num_rows($detB)==0){

             //if(!$det=mysql_query($detA)){

                $rx=" - ";

              }else{

                $rx="<img src='lib/slc.png' border='0'>";

              }


            $detC  = mysql_query("SELECT otd.orden,otd.estudio,est.depto
            FROM otd,est
            WHERE otd.estudio=est.estudio and est.depto>2 AND otd.orden=$rg[orden]");

            if(mysql_num_rows($detC)==0){

             //if(!$det=mysql_query($detA)){

                $otros=" - ";

              }else{

                $otros="<img src='lib/slc.png' border='0'>";

              }


            $detD  = mysql_query("SELECT otd.orden,otd.estudio,otd.noconformidad
            FROM otd
            WHERE otd.orden=$rg[orden] and otd.noconformidad<>''");

            if(mysql_num_rows($detD)==0){

             //if(!$det=mysql_query($detA)){

                $noconformidad=" - ";

              }else{

                $noconformidad="<img src='images/ErrorCircle.png' border='0'>";

              }

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo'; height='22'>";
          // echo "<td align='center'><a href=ordenese.php?busca=$rg[orden]&pagina=$pagina><img src='lib/edit.png' alt='Edita registro' border='0'></a></td>";
          // echo "<td align='center'><a href=ordenesd.php?busca=$rg[orden]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";

           Display($aCps,$aDat,$rg); 

          echo "<td align='center'><a class='pg' href=javascript:winuni2('noconformidad.php?Orden=$rg[orden])>$noconformidad</a></td>";
          echo "<td align='center'><a class='pg' href=javascript:winuni2('detot.php?Orden=$rg[orden]&depto=1')>$lab</a></td>";
          echo "<td align='center'><a class='pg' href=javascript:winuni2('detot.php?Orden=$rg[orden]&depto=2')>$rx</a></td>";
          echo "<td align='center'><a class='pg' href=javascript:winuni2('detot.php?Orden=$rg[orden]&depto=3')>$otros</a></td>";
          echo "<td align='center'><a href=ordenesdet.php?busca=$rg[orden]&pagina=$pagina&Suc=$Suc><img src='images/editar_icon.png' alt='Detalle' border='0' width='13'></a></td>";
          echo "<td align='center'>$Gfont <font size='1'>$rg[status]</font></td>";

          if($rg[entemailpac]=='1' or $rg[entemailmed]=='1' or $rg[entemailinst]=='1'){ 
              echo "<td align='center'><a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$rg[orden]')><img src='lib/email.png' border='0'></a></td>";   
          }else{
              echo "<td align='center'><a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$rg[orden]')>$Gfont No</a></td>";
          }
		             
           $nRng++;

      }
	}
     
    PonPaginacionpro(true);           #-------------------pon los No.de paginas-------------------

    CuadroInferiorpro($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
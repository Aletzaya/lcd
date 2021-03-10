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
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}     #Campo por el cual se ordena 
  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults
  $busca    = $_SESSION[busca];        //Dato a busca
  $busca2    = $_REQUEST[busca];        //Dato a busca
  $Suc    = $_REQUEST[Suc];        //Dato a busca
  $NC    = $_REQUEST[NC];        //Dato a busca

    $Fech2= $_REQUEST[Fech2];
  $Fech3= $_REQUEST[Fech3];

  if (!isset($Ord)){
    $OrdenDef="ot.orden";            //Orden de la tabla por default
  }elseif($Ord=='ord'){
    $OrdenDef="ot.orden";            //Orden de la tabla por default
  }elseif($Ord=='suc'){
    $OrdenDef="ot.suc";            //Orden de la tabla por default
  }elseif($Ord=='pac'){
    $OrdenDef="cli.nombrec";            //Orden de la tabla por default
  }


  if($Suc=="*"){
    $Sucursal="";
  }else{
    $Sucursal=" and ot.suc='$Suc'";
  }
/*
  if($NC=="NC"){
    $Nocon=" and ot.noconformidad='Si'";
  }else{
    $Nocon=" ";
  }
*/
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
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.hora,ot.horae,ot.recepcionista,cli.numveces,cli.cliente,ot.importe,ot.servicio,ot.idprocedencia,ot.responsableco
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente and ot.fecha>='$Fech2' and ot.fecha<='$Fech3' $Sucursal $Nocon";
  }elseif( $busca < 'a'){
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.hora,ot.horae,ot.recepcionista,cli.numveces,cli.cliente,ot.importe,ot.servicio,ot.idprocedencia,ot.responsableco
	 FROM cli,$Qry[froms] WHERE ot.cliente=cli.cliente AND ot.orden >= '$busca' $Qry[filtro]";
   $Suc='*';
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos],ot.ubicacion,ot.encaja,ot.status,ot.pagada,ot.entemailpac,ot.entemailmed,ot.entemailinst,ot.hora,ot.horae,ot.recepcionista,cli.numveces,cli.cliente,ot.importe,ot.servicio,ot.idprocedencia,ot.responsableco
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

		echo "<form name='form' method='post' action='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc'>";
	
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

    if($Suc=='5'){
      $cinco="<img src='images/five2.png' width='22'>";
    }else{
      $cinco="<img src='images/fives.png' width='18'>";
    }
/*
    if($NC=='NC'){
      $NoConform="<a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&NC=C'><img src='images/ErrorCircle.png' width='22'></a>";
    }else{
      $NoConform="<a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&NC=NC'><img src='images/ErrorCircle.png' width='18'></a>";
    }
*/
    echo "&nbsp; $Gfont <font size='2'><b> Suc: </b></font><a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=*'>$Todas</a>&nbsp; &nbsp;<a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=1'>$uno</a>&nbsp; &nbsp;<a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=2'>$dos</a>&nbsp; &nbsp;<a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=3'>$tres</a>&nbsp; &nbsp;<a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=4'>$cuatro</a>&nbsp; &nbsp;<a class='vt' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=5'>$cinco</a>&nbsp; &nbsp;$NoConform";
		
     //PonEncabezadopro();         #---------------------Encabezado del browse----------------------

    echo "<table align='center' width='99%' border='0' cellspacing='0' cellpadding='0'>";

    echo "<tr height='25' bgcolor='#a2b2de'>";

    echo "<td align='center'>$Gfont <b>Ed</b></td>";
    echo "<td align='center'>$Gfont <b>Det</b></td>";
    echo "<td align='center'>$Gfont <b><a class='pg' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&Ord=suc'>Suc</b></a></td>";
    echo "<td align='center'>$Gfont <b><a class='pg' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&Ord=ord'>Inst-Ord</b></a></td>";
    echo "<td align='center'>$Gfont <b><a class='pg' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&Ord=fecha'>Fech/Hor Cap</b></a></td>";
    echo "<td align='center'>$Gfont <b><a class='pg' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&Ord=fechaent'>Fech/Hor Ent</b></a></td>";
    echo "<td align='center'>$Gfont <b><a class='pg' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&Ord=cliente'>Cliente</b></a></td>";
    echo "<td align='center'>$Gfont <font size='1'><b>NC</b></td>";
    echo "<td align='center'>$Gfont <font size='1'><b>T.Servicio</b></td>";
    echo "<td align='center'>$Gfont <b><a class='pg' href='ordenescon.php?Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&Ord=captura'>Captura</b></a></td>";
    echo "<td align='center'>$Gfont <font size='1'><b>Importe</b></td>";
    echo "<td align='center'>$Gfont <font size='1'><b>Pgda</b></td>";
    echo "<td align='center'>$Gfont <font size='1'><b>Rec</b><font size='1'></td>";
    echo "<td align='center'>$Gfont <font size='1'><b>Engda</b></td>";
    echo "<td align='center'>$Gfont <font size='1'><b>Correo</b></td>";


    echo "</tr>";              

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

            $horaCap = $rg[hora];
            $horaActual = date('H:i:s');
            $horaActual2 = abs(strtotime(date('H:i:s')));

            $detE  = mysql_query("SELECT otd.orden,otd.estudio,otd.statustom
            FROM otd
            WHERE otd.orden=$rg[orden] and otd.statustom<>''");

            if(mysql_num_rows($detE)==0){

             //if(!$det=mysql_query($detA)){
                $statustom="<img src='images/ErrorCircle.png' border='0'>";

                $minutos=RestarHoras($horaCap,$horaActual);

                $currentDate = abs(strtotime($horaCap));
                $futureDate = $currentDate+(60*10);
                $futureDate2 = $currentDate+(60*5);

                if($horaActual2>=$futureDate){

                  $Fdo="#d98880";

                }elseif($horaActual2>=$futureDate2){

                  $Fdo="#FFFF00";
                  
                }else{

                  $Fdo=$Fdo;

                }

                
              }else{

                $statustom=" - ";
                $Fdo=$Fdo;

                $minutos=" ";

              }

              if($rg[servicio]=='Urgente'){
                $colorservicio="RED";
              }else{
                $colorservicio="";
              }

              if($rg[idprocedencia]=='ambu'){
                  $alerta=" <img src='images/ambu.png' width='25' height='25'>";
              }elseif($rg[idprocedencia]=='silla'){
                  $alerta=" <img src='images/silla.png' width='25' height='25'>";
              }elseif($rg[idprocedencia]=='terceraedad'){
                  $alerta=" <img src='images/terceraedad.png' width='25' height='25'>";
              }elseif($rg[idprocedencia]=='problemasv'){
                  $alerta=" <img src='images/problemasv.png' width='25' height='25'>";
              }elseif($rg[idprocedencia]=='bebe'){
                  $alerta=" <img src='images/bebe.png' width='25' height='25'>";
              }else{                
                  $alerta=" ";
              }

              if($rg[responsableco]<>''){
                $Resp='Resp_$';
              }else{
                $Resp=' ';
              }



          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo'; height='23'>";
          echo "<td align='center'><a href=ordenese.php?busca=$rg[orden]&pagina=$pagina&Suc=$Suc&$Fech2&Fech3=$Fech3><img src='lib/edit.png' alt='Edita registro' border='0'></a></td>";
          echo "<td align='center'><a href=ordenesd.php?busca=$rg[orden]&pagina=$pagina&Fech2=$Fech2&Fech3=$Fech3&Suc=$Suc&busca2=$busca2><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";
          echo "<td align='center'>$Gfont <font size='2'><b>$rg[suc]</b></font></td>";
          echo "<td align='right'>$Gfont <font size='1'>$rg[institucion]-</font><font size='2'><b>$rg[orden]</b></font></td>";
          $hora2=substr($rg[hora],0,5);
          echo "<td align='center'>$Gfont <font size='2'><b>$rg[fecha]</b></font><font size='1'> $hora2</font></td>";
          $hora3=substr($rg[horae],0,5);
          echo "<td align='center'>$Gfont <font size='2'><b>$rg[fechae]</b></font><font size='1'> $hora3</font></td>";
          echo "<td align='left'>$Gfont <font size='1'>$rg[nombrec] [ <a class='pg' href=javascript:winuni2('repots.php?busca=$rg[cliente]')><font size='2'><b> $rg[numveces]</b></a></font> ] $minutos $alerta <a class='pg' href=javascript:winuni2('repotseco.php?busca=$rg[orden]')><font size='1' color='
          #21618c'><b> $Resp</b></a></font></td>";
          echo "<td align='center'><a class='pg' href=javascript:winuni2('noconformidadg.php?Orden=$rg[orden]')>$noconformidad</a></td>";
          echo "<td align='center'>$Gfont <font size='1' color='$colorservicio'>$rg[servicio]</font></td>";
          echo "<td align='center'>$Gfont <font size='1'>$rg[recepcionista]</font></td>";
          echo "<td align='right'>$Gfont <font size='1'><b>".number_format($rg[importe],'2')."</b></font></td>";

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
            //  echo "<td align='center'> - </td>";   
              echo "<td align='center'><a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$rg[orden]')><img src='lib/email.png' border='0'></a></td>";   
            }else{
                   //         echo "<td align='center'> - </td>";   

              echo "<td align='center'><a class='pg' href=javascript:winuni2('entregamail2.php?Orden=$rg[orden]')>$Gfont No</a></td>";
            }
           echo "</tr>";

		             
           $nRng++;

      }
	}
     
    PonPaginacionpro(true);           #-------------------pon los No.de paginas-------------------

    CuadroInferiorpro($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>

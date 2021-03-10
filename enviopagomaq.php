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
  $Titulo = "Envios Generados para Pago (Maquila)";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 58;

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

  if($op=='Elim'){     
      $Fechai=date("Y-m-d H:i"); 
      $Usr    = $check['uname'];              //Elimina Registro
      $Up=mysql_query("UPDATE generapago SET cancel='$Usr - $Fechai' WHERE generapago.id='$_REQUEST[Id]'");
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
            if(!isset($BusInt)){$BusInt=" mql.alias like '%$P[$i]%' ";}else{$BusInt=$BusInt." and mql.alias like '%$P[$i]%' ";}
     }
  }else{
     $BusInt=" mql.alias like '%$busca%' ";
  }


  if( $busca < 'a'){
     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE generapago.proveedor=mql.id and generapago.id >= '$busca' and generapago.cancel='' $Qry[filtro]";
  }else{
     #Armo el query segun los campos tomados de qrys;
     $cSql="SELECT $Qry[campos] FROM $Qry[froms] WHERE generapago.proveedor=mql.id and generapago.cancel='' and  $BusInt $Qry[filtro]";
     //echo $cSql;
  }


  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;

  //$aIzq   = array("Editar","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos
  $aDer   = array("RepGen","-","-","RepCot","-","-","Cancel","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");							//Parametros de colores;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

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

      $sql  = $cSql.$cWhe." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;
      //echo $sql;
      $res  = mysql_query($sql);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos    = strrpos($_SERVER[PHP_SELF],".");
    //  $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
     // $cLinkd = substr($_SERVER[PHP_SELF],0,$Pos).'d.php';     #

      while($rg=mysql_fetch_array($res)){

           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           Display($aCps,$aDat,$rg);

          $Inst1="SELECT instruccion from generapago WHERE generapago.id=$rg[id]";

          $Instruct  = mysql_query($Inst1);
          $Instruct1=mysql_fetch_array($Instruct);

           echo "<td align='center'><a href=javascript:winuni2('repenvioext.php?$Instruct1[instruccion]')><img src=lib/reporte2.png width='20'></td>";

           echo "<td align='center'><a href=javascript:winuni2('repenvioextadm.php?$Instruct1[instruccion]')><img src=lib/reporte3.png width='20'></td>";

          echo "<td align='center'><a class='Seleccionar'  href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;$rg[id]?','$_SERVER[PHP_SELF]?Id=$rg[id]&op=Elim');>eliminar</a></td>";

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
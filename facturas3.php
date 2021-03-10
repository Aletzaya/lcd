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
  $Suc      = $_COOKIE['TEAM'];        //Sucursal 

  #Variables comunes;
  $Titulo = "Facturacion ver 3.3";
  $op     = $_REQUEST[op];
  $Msj    = $_REQUEST[Msj];
  $Id     = 36;
  
  if($Suc == 0){
      
     $cQry = '';
      
  }else{
      
      $cQry = " suc = '$Suc' AND ";      
      
  }

  #Intruccion a realizar si es que mandan algun proceso
  if($_REQUEST[Boton] == 'Agregar' AND $_REQUEST[Descripcion]<>'' AND $_REQUEST[Umedida]<>''){

	  $lUp  = mysql_query("INSERT INTO inv (descripcion,umedida,rubro)
	          VALUES
	          ('$_REQUEST[Descripcion]','$_REQUEST[Umedida]','$_REQUEST[Rubro]')");

	  $Msj  = "Registro agregado con EXITO!";

  }elseif($_REQUEST[Boton] == 'Actualizar'){

     $lUp  = mysql_query("UPDATE inv SET descripcion='$_REQUEST[Descripcion]',rubro='$_REQUEST[Rubro]',
     			 umedida='$_REQUEST[Umedida]',activo='$_REQUEST[Activo]'
             WHERE id='$_REQUEST[cId]'");

  }elseif($op=='Si'){                    //Elimina rg

     $ExiA = mysql_query("SELECT id FROM ven WHERE vendedor='$_REQUEST[cId]' limit 1");

     $Exi  = mysql_fetch_array($ExiA);

     if ($Exi[id] <> ""){
 			$Msj = "No es posible eliminar el vendedor, existen ventas registradas";
     }else{
         $lUp  = mysql_query("DELETE FROM ven WHERE id='$_REQUEST[cId]' limit 1");
         $Msj  = "Registro eliminado";
     }


     //$Msj = "Opcion deshabilitada";

  }elseif($op=='rs'){
      $Up  = mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id");
      $op  = '';
  }

  #Tomo los datos principales campos a editar, tablas y filtros;
  $QryA    = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
  $Qry     = mysql_fetch_array($QryA);

  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}

  $Palabras  = str_word_count($busca);  //Dame el numero de palabras
  if($Palabras > 1){
     $P = str_word_count($busca,1);          //Metelas en un arreglo
     for ($i = 0; $i < $Palabras; $i++) {
         if(!isset($BusInt)){$BusInt=" clif.nombre LIKE  '%$P[$i]%' ";}else{$BusInt=$BusInt." AND clif.nombre like '%$P[$i]%' ";}
     }
  }else{
     $BusInt = "clif.nombre LIKE '%".$busca."%'";
  }


  if($Suc >= 1){
     #Armo el query segun los campos tomados de qrys;
     $cSql   = "SELECT $Qry[campos],fc.uuid,fc.status,fc.id, clif.formadepago as pago
                FROM $Qry[froms] LEFT JOIN clif ON fc.cliente=clif.id WHERE suc='$Suc' AND $BusInt $Qry[filtro]";
  }else{
     $cSql   = "SELECT $Qry[campos],fc.uuid,fc.status,fc.id, clif.formadepago as pago
                FROM $Qry[froms] LEFT JOIN clif ON fc.cliente=clif.id WHERE $BusInt $Qry[filtro]";
  }
  
  //echo $Sql;
  
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;

  $aIzq   = array("Ed","-","-","Det","-","-","Pdf","-","-","Xml","-","-");	  //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos
  $aDer   = array("Status","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");							//Parametros de colores;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

 echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

 headymenu($Titulo,1);

 //echo $cSql;

      $res   = mysql_query($cSql);

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

            $Pdf  = $rg[uuid] . ".pdf";
            $Xml  = $rg[uuid] . ".xml";                        
           
           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           //echo "<td align='center'><a href='$cLink?busca=$rg[cliente]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
           //echo "<td align='center'><a href='$cLinkd?busca=$rg[medico]'><img src='lib/browse.png' alt='Detalle' border='0'></td>";
           //echo "<td align='center'><a href=ordenesnvas.php?Cliente=$rg[cliente]&op=cl><img src='lib/ok.png' alt='Elige este reg' border='0'></td>";

           echo "<td align='center'><a href=facturas3e.php?busca=$rg[id]><img src='lib/edit.png' alt='Edita registro' border='0'></a></td>";

           echo "<td align='center'><a href=facturas3d.php?busca=$rg[id]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";

            if($rg[status] == 'Timbrada'){
                  echo "<td align='center'><a href=javascript:winuni('enviafile.php?busca=$Pdf')><img src='lib/Pdf.gif' alt='Imprime copia pdf' border='0'></td>";
                  echo "<td align='center'><a href=javascript:winuni('enviafile.php?busca=$Xml')><img src='lib/Xml.gif' alt='Obten archivo xml' border='0'></td>";
            }else{
                  echo "<td align='center'> </td>";
                  echo "<td align='center'> </td>";                            
            }
                                                
           
           //Display($aCps,$aDat,$rg);

           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si')><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></a></td>";
           //echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";

              echo "<td>$Gfont $rg[id]</font></td>";
              echo "<td>$Gfont $rg[folio]</font></td>";
              echo "<td>$Gfont $rg[fecha]</font></td>";
              echo "<td>$Gfont $rg[suc]</font></td>";
              echo "<td>$Gfont $rg[cliente]</font></td>";
              echo "<td>$Gfont $rg[nombre]</font></td>";
              echo "<td>$Gfont $rg[usr]</font></td>";
              echo "<td>$Gfont $rg[pago]</font></td>";
              echo "<td align='right'>$Gfont ".number_format($rg[cantidad],"0")."</font></td>";
              echo "<td align='right'>$Gfont ".number_format($rg[importe],"2")."</font></td>";
              if($rg[status] <> 'Cancelada'){    
                    echo "<td align='left'>&nbsp<a class='seleccionar' href='canfactura.php?busca=$rg[id]'>$rg[status]</a></td>";
              }else{
                    echo "<td align='left'>$Gfont<font color='#990000'>$rg[status]</td>";                        
              }    

            echo "</tr>";

            $nRng++;

      }


    PonPaginacion(true);           #-------------------pon los No.de paginas-------------------

    CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";

  echo "</html>";

  mysql_close();

?>
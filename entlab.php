<?php

  #Librerias
  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");
  
  $link=conectarse();
  
  if(isset($_REQUEST[pagina])) { $_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar  
  if(isset($_REQUEST[Sort]))   { $_SESSION['Sort'] = $_REQUEST[Sort];}        #Orden Asc o Desc
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena	
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por default
  $busca    = $_SESSION[busca];        //Dato a busca
    
  #Variables comunes;
  $Msj     = $_REQUEST[Msj];
  $Titulo = "Compras para laboratorio";
  $op     = $_REQUEST[op];
  $Id     = 15; 										                 //Numero de query dentro de la base de datos
  
  #Intruccion a realizar si es que mandan algun proceso
  if($op=='rs'){
      $Up=mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id",$link);
      $op='';
  }

  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA=mysql_query("select campos,froms,edi,tampag,filtro from qrys where id=$Id",$link);
  $Qry=mysql_fetch_array($QryA);
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  if($busca >= 'a'){
  
     #Deshago la busqueda por palabras(una busqueda inteligte;
     $Palabras  = str_word_count($busca);  //Dame el numero de palabras
     if($Palabras > 1){
        $P=str_word_count($busca,1);          //Metelas en un arreglo
        for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" el.concepto like '%$P[$i]%' ";}else{$BusInt=$BusInt." AND el.concepto like '%$P[$i]%' ";}
        }
     }else{
        $BusInt=" el.concepto like '%$busca%' ";  
     }

     #Armo el query segun los campos tomados de qrys;
     $cSql = "SELECT $Qry[campos],el.proveedor,prv.nombre,el.almacen FROM el,prv 
              WHERE el.proveedor=prv.id and $BusInt $Qry[filtro]";

  }else{
   
     $cSql = "SELECT $Qry[campos],el.proveedor,prv.nombre,el.almacen  FROM el,prv WHERE el.proveedor=prv.id and  el.id >= '$busca' $BusInt  $Qry[filtro]";
          
  }   
  
  //echo $cSql;
  
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Edit","-","-","Imp","-","-","Det","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			//Arreglo donde llena el grid de datos        
  $aDer   = array("Prov.","-","-","Almacen","-","-","Stat.","-","-");				 //Arreglo donde se meten los encabezados; Derechos;
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
        echo "<p align='center'>$Gfont Favor de presiona Refresca...</p></font>";    #Manda mensaje de datos no existentes
		  $nRng=7;
		  $op='rs';        

   }else{

      CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
		
      $sql=$cSql.$cWhe." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;
      
      //echo $sql;

      $res=mysql_query($sql,$link);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos=strrpos($_SERVER[PHP_SELF],".");
      $cLink=substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #

      while($rg=mysql_fetch_array($res)){
           
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF'; }else{$Fdo=$Gfdogrid;}

           //echo "<tr bgcolor=$Fdo onMouseOver=this.style.backgroundColor='#a7c3f2';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           echo "<tr bgcolor=$Fdo onMouseOver=this.style.backgroundColor='#a7c3f2';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           //echo "<tr bgcolor=$Fdo onMouseOut=this.style.backgroundColor=$Fdo;>";
           echo "<td align='center'><a href='$cLink?busca=$rg[id]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
           if($rg[status]=='CERRADA'){
              echo "<td align='center'><a href=javascript:wingral('impentlabpdf.php?busca=$rg[id]')><img src='lib/print.png' alt='Imprime' border='0'></td>";
           }else{
              echo "<td>&nbsp;</td>";
           }           
           echo "<td align='center'><a href='entlabd.php?busca=$rg[id]'><img src='lib/browse.png' alt='Modifica Registro' border='0'></td>";
           Display($aCps,$aDat,$rg);
		   if ($rg[status]=='Abierta' or $rg[status]=='ABIERTA'){
				$stat="slc.png";
			}else{
				$stat="candadocerr2.png";
			}
           // $PrvA=mysql_query("select * from prv where id=$rg[proveedor]",$link);
           // $Prv=mysql_fetch_array($PrvA);

            //echo "<td>$Gfont $rg[proveedor] - $Prv[nombre]</td>";
            echo "<td>$Gfont <font size='1'> $rg[proveedor] - $rg[nombre]</font></td>";

            if($rg[almacen]=='invgral'){
              $Almacenes='General';
            }elseif($rg[almacen]=='invmatriz'){
              $Almacenes='Matriz';
            }elseif($rg[almacen]=='invtepex'){
              $Almacenes='Tepexpan';
            }elseif($rg[almacen]=='invhf'){
              $Almacenes='HF';
            }elseif($rg[almacen]=='invgralreyes'){
              $Almacenes='Gral.Reyes';
            }elseif($rg[almacen]=='invreyes'){
              $Almacenes='Reyes';
            }

            echo "<td>$Gfont <font size='1'> $Almacenes</font></td>";

            echo "<td align='center'><a href=javascript:winuni2('pagosel.php?busca=$rg[id]')><img src=lib/$stat></td>";
           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
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
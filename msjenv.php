<?php

  #Librerias
  session_start();

  //if(isset($_REQUEST[Nomina])){$_SESSION['Nomina']=$_REQUEST[Nomina];}
  
  require("lib/lib.php");
  
  $link=conectarse();

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $pagina;			//Ojo saco pagina de la session
  $Sort     = "ASC";           //Orden ascendente o descendente
  $OrdenDef = "msj.id";        //Orden de la tabla por default
  $Usr      = $_COOKIE['USERNAME'];
    
  #Variables comunes;
  $Msj     = $_REQUEST[Msj];
  $Titulo  = "Mensajes";
  $Id      = 13; 										                 //Numero de query dentro de la base de datos
  $busca   = $_REQUEST[busca];        //Dato a busca
  
  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA   = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
  $Qry    = mysql_fetch_array($QryA);
      
  #Armo el query segun los campos tomados de qrys;
  $cSql  = "SELECT $Qry[campos],msj.id,msj.bd,msj.para FROM $Qry[froms] 
           WHERE msj.de='$Usr' OR msj.de='$Tda'";
  //echo $cSql;
           

  $aIzq   = array("&nbsp;","-","-","<b>Para</b>","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos  
  $aCps   = SPLIT(",",$Qry[campos]);		      // Es necesario para hacer el order by  desde lib;
  $aDat   = SPLIT(",",$Qry[edi]);			//Arreglo donde llena el grid de datos        
  $aDer   = array();				 //Arreglo donde se meten los encabezados; Derechos;
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

  echo "<table width='98%' border='0' align='center' cellpadding=0 cellspacing=0>";
  echo "<tr><td width='10%'><a href='javascript:window.close()'><img src='lib/regresa.jpg' border='0'></a></td>";
  echo "<td align='center'>$Gfont <b>Bienvenidos area de mensajes en linea</b> </font></td></table>";
 
  echo "<div align='right'><font color='#CCCCFF' size='1'>Hagamos de esto una buena herramienta &nbsp; </font></div>";

  if(!$res=mysql_query($cSql)){
        echo "$Gfont <p align='center'>No se encontraron resultados y/o hay un error en el filtro</p>";    #Manda mensaje de datos no existentes
        echo "<p align='center'>$Gfont Favor de presiona Refresca...</p></font>";    #Manda mensaje de datos no existentes
		  $nRng=7;
		  $op='rs';        
   }else{

     echo "<table width='98%' border='0' align='center' cellpadding=0 cellspacing=0><tr>";
     echo "<td width='50%' align='left'>$Gfont<b>USUARIO: [$Usr]</b> </td>";
     echo "<td width='50%' align='right'><img src='lib/mensajes.gif' border='0'> &nbsp; </td>";
     echo "</tr></table>";

	  echo "$Gfont <b>Menu principal</b><br>";

     echo "<table width='95%' border='1' align='center' cellpadding=0 cellspacing=0><tr>";
     echo "<td width='33%' align='center'>$Gfont <a class='pg' href='msjrec.php'><b>Mensajes recibidos</b></a></td>";
     echo "<td width='33%' align='center' bgcolor=$Gbarra>$Gfont <font color='#ffffff'><b>Mensajes enviados</b></td>";
     echo "<td align='center'>$Gfont <a class='pg' href='msjenve.php?busca=NUEVO'><b>Nuevo mensaje</b></a></td>";
     echo "</tr></table>";
    
     
     CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
		
     $sql   = $cSql." ORDER BY id ASC LIMIT ".$limitInf.",".$tamPag;      
     //echo $sql;

     $res   = mysql_query($sql);

     PonEncabezado();         #---------------------Encabezado del browse----------------------

     $Pos   = strrpos($_SERVER[PHP_SELF],".");

     $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
     
     while($rg=mysql_fetch_array($res)){
      
           if(($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;           
           if($rg[bd]==0){$Sts='<font color=#990000>Sin leer';}else{$Sts='Leido';}

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                     
			  if($rg[bd]==0){	                     
              echo "<td align='center' valign='bottom'><a class='pg' href='$cLink?Id=$rg[id]'><img src='lib/postal.jpg' border='0'></a></td>";
           }else{
              echo "<td align='center' valign='bottom'>$Gfont <b><a class='ord' href='$cLink?Id=$rg[id]'><img src='lib/mail.gif' border='0'></a></b></td>";
           }   

           echo "<td align='left'><a class='pg' href='$cLink?Id=$rg[id]&busca=$busca&st=$rg[bd]'>$Gfont <b> ";

           echo ucfirst(strtolower($rg[para]))."</a></b></td>";

           //Display($aCps,$aDat,$rg);           

           echo "<td align='center'>$Gfont $rg[fecha]</td>";
           echo "<td align='center'>$Gfont $rg[hora]</td>";
           echo "<td align='left'>$Gfont $rg[titulo]</td>";

           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;rg?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');><img src='lib/deleon.png' alt='Elimina rg' border='0'></a></td>";
           echo "</tr>";
           $nRng++;
    }
     
    PonPaginacion(false);           #-------------------pon los No.de paginas-------------------
  	 
    //CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .
    
  }

   
  echo "<div> &nbsp; <b><a class='ord' href='msjenve.php?busca=NUEVO'>Agrega</a></b></div>"; 
  echo "<div align='right'><font color='#CCCCFF' size='2'>";
  echo " Para asegurar una buena comunicaci&oacute;n es importante redactar adecuadamente los mensajes";
  echo "</font></div>";

  echo "<p align='center'><a class='ord' href='javascript:this.close()'>Cerrar esta ventana</a></p>";
  
  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
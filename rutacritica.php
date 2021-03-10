<?php

  session_start();
 
  if(!isset($_REQUEST[Depto])){$Depto=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[Depto];$Depto=$_REQUEST[Depto];}

  require ("config.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link    = conectarse();

  $Tabla   = "aut";

  $Usr    = $_COOKIE['USERNAME'];
  
  $Fecha   = date("Y-m-d");
  $Hora    = date("H:i");
  $tamPag  = 20;
  $pagina  = $_REQUEST[pagina];

  $Titulo  = "Ruta critica por estudio";
  
  $busca   = $_REQUEST[busca];
  
  $DepA    = mysql_query("SELECT * FROM dep");
  
  //if(!isset($busca)){$busca=1;}

  $Gfont="<font color='#414141' face='Arial, Helvetica, sans-serif'> <font size='2'>";
  $Gfont2="<font face='Arial, Helvetica, sans-serif'> <font size='2'>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php 

   echo "<div align='center'>$Gfont2 <font size='+1'> Laboratorio Clinico Duran</font></div>";
   echo "<div align='center'>Ruta critica al $Fecha / $Hora</div>";
   
   echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

   echo "<tr height='25' bgcolor='#CCCCCC'>";
   echo "<td align='center'>$Gfont2 Orden</td>";
   echo "<td align='center'>$Gfont2 Fec/Cap</td>";
   echo "<td align='center'>$Gfont2 Hora/Cap</td>";
   echo "<td align='center'>$Gfont2 Estudio</td>";
   echo "<td align='center'>$Gfont2 Fec/Ent</td>";
   
   echo "<td align='center'>$Gfont2 Etiqueta Impresa</td>";
   echo "<td align='center'>$Gfont2 Realizacion de estudios</td>";
   echo "<td align='center'>$Gfont2 Captura de resultados</td>";
   echo "<td align='center'>$Gfont2 Impresion de resultados</td>";
   echo "<td align='center'>$Gfont2 Estudio en Recepcion</td>";
   echo "</tr>"; 
 
if($Depto<>"*"){
 
    if($busca=='7'){	//Todos los deptos;

        $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                 FROM ot,otd,est 
                 WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.estudio=est.estudio 
                        AND est.depto='$Depto'";
    }else{
   
      if($busca==' '){			//Todas los estudios abiertos;
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM est,ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = ' ' AND otd.estudio=est.estudio 
                        AND est.depto='$Depto' AND mid(otd.uno,1,4)='0000'";
      }elseif($busca=='2'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM est,ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '2' AND otd.estudio=est.estudio 
                        AND est.depto='$Depto' AND mid(otd.dos,1,4)='0000'";
      }elseif($busca=='3'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM est,ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '3' AND otd.estudio=est.estudio 
                  AND est.depto='$Depto' AND mid(otd.tres,1,4)='0000'";
      }elseif($busca=='4'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM est,ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '4' AND otd.estudio=est.estudio 
                  AND est.depto='$Depto' AND mid(otd.cuatro,1,4)='0000'";
      }elseif($busca=='5'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM est,ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '5' AND otd.estudio=est.estudio 
                  AND est.depto='$Depto' AND mid(otd.cinco,1,4)='0000'";
      }elseif($busca=='6'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM est,ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '6' AND otd.estudio=est.estudio 
                  AND est.depto='$Depto' AND mid(otd.seis,1,4)='0000'";
      }            
                  
   }   
  
}elseif($Depto=="*"){

    if($busca=='7'){	//Todos los deptos;

        $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                 FROM ot,otd 
                 WHERE ot.status<>'Entregada' AND ot.orden=otd.orden"; 
    }else{
   
      if($busca==' '){			//Todas los estudios abiertos;
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = ' ' 
                  AND mid(otd.uno,1,4)='0000'";
      }elseif($busca=='2'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '2'  
                  AND mid(otd.dos,1,4)='0000'";
      }elseif($busca=='3'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '3'  
                  AND mid(otd.tres,1,4)='0000'";
      }elseif($busca=='4'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '4' 
                  AND mid(otd.cuatro,1,4)='0000'";
      }elseif($busca=='5'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '5' 
                  AND mid(otd.cinco,1,4)='0000'";
      }elseif($busca=='6'){
         $cSql = "SELECT ot.orden,ot.fechae,otd.estudio,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,ot.fecha,ot.hora,ot.institucion
                  FROM ot,otd 
                  WHERE ot.status<>'Entregada' AND ot.orden=otd.orden AND otd.lugar = '6'  
                  AND mid(otd.seis,1,4)='0000'";
      }            
                  
   }   

}            

   if(strlen($cSql)>0){
    
        $res = mysql_query($cSql);
        
        //echo $cSql;

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

        $Sql  = $cSql.$cWhe." ORDER BY otd.orden,otd.estudio ASC LIMIT ".$limitInf.",".$tamPag;
   
        $SqlA = mysql_query($Sql);

//        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($rg=mysql_fetch_array($SqlA)){  
            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
//           echo "<tr height='15'>";
           echo "<td>$Gfont $rg[institucion]&nbsp;-&nbsp;$rg[orden]</td>";
           echo "<td>$Gfont $rg[fecha]</td>";
           echo "<td>$Gfont $rg[hora]</td>";
           echo "<td>$Gfont $rg[estudio]</td>";
           echo "<td>$Gfont $rg[fechae]</td>";
                 	   
      	  /* 	     
		     if(substr($rg[uno],0,4)=='0000'){
           	 echo "<td align='center'><img src='lib/ok.gif'></td>";	
           }elseif(strtotime($rg[autcol]) <= $Fec){
           	 echo "<td bgcolor='#EB4948'>$Gfont $rg[autcol] ** </td>";	           
           }else{
           	 echo "<td bgcolor='#FFFF00'>$Gfont $rg[autcol]</td>";	                      
           }
           */
           if(substr($rg[uno],0,4)=='0000'){ //Normal aun comn tiempo y sin capturar result;
              if($rg[fechae] < $Fecha){
        	        echo "<td align='center'>$Gfont $rg[uno]</td>";         //Blanco;
              }elseif($rg[fechae] == $Fecha){
        	         echo "<td align='center'  bgcolor='#EB4948' >$Gfont $rg[uno]</td>"; //Amarillo
              }else{
        	         echo "<td align='center'  bgcolor='#FFFF00' >$Gfont $rg[uno]</td>"; //Rojo
        	     }    
           }else{
        	     echo "<td align='center'  bgcolor='#95e361' >$Gfont $rg[uno]</td>";      //Verde;
        	  }

           if(substr($rg[dos],0,4)=='0000'){ //Normal aun comn tiempo y sin capturar result;
              if($rg[fechae] < $Fecha){
        	        echo "<td align='center'>$Gfont $rg[dos]</td>";         //Blanco;
              }elseif($rg[fechae] == $Fecha){
        	         echo "<td align='center'  bgcolor='#EB4948' >$Gfont $rg[dos]</td>"; //Amarillo
              }else{
        	         echo "<td align='center'  bgcolor='#FFFF00' >$Gfont $rg[dos]</td>"; //Rojo
        	     }    
           }else{
        	     echo "<td align='center'  bgcolor='#95e361' >$Gfont $rg[dos]</td>";      //Verde;
        	  }

           if(substr($rg[tres],0,4)=='0000'){ //Normal aun comn tiempo y sin capturar result;
              if($rg[fechae] < $Fecha){
        	        echo "<td align='center'>$Gfont $rg[tres]</td>";         //Blanco;
              }elseif($rg[fechae] == $Fecha){
        	         echo "<td align='center'  bgcolor='#EB4948' >$Gfont $rg[tres]</td>"; //Amarillo
              }else{
        	         echo "<td align='center'  bgcolor='#FFFF00' >$Gfont $rg[tres]</td>"; //Rojo
        	     }    
           }else{
        	     echo "<td align='center'  bgcolor='#95e361' >$Gfont $rg[tres]</td>";      //Verde;
        	  }

           if(substr($rg[cuatro],0,4)=='0000'){ //Normal aun comn tiempo y sin capturar result;
              if($rg[fechae] < $Fecha){
        	        echo "<td align='center'>$Gfont $rg[cuatro]</td>";         //Blanco;
              }elseif($rg[fechae] == $Fecha){
        	         echo "<td align='center'  bgcolor='#EB4948' >$Gfont $rg[cuatro]</td>"; //Amarillo
              }else{
        	         echo "<td align='center'  bgcolor='#FFFF00' >$Gfont $rg[cuatro]</td>"; //Rojo
        	     }    
           }else{
        	     echo "<td align='center'  bgcolor='#95e361' >$Gfont $rg[cuatro]</td>";      //Verde;
        	  }

           if(substr($rg[cinco],0,4)=='0000'){ //Normal aun comn tiempo y sin capturar result;
              if($rg[fechae] < $Fecha){
        	        echo "<td align='center'>$Gfont $rg[cinco]</td>";         //Blanco;
              }elseif($rg[fechae] == $Fecha){
        	         echo "<td align='center'  bgcolor='#EB4948' >$Gfont $rg[cinco]</td>"; //Amarillo
              }else{
        	         echo "<td align='center'  bgcolor='#FFFF00' >$Gfont $rg[cinco]</td>"; //Rojo
        	     }    
           }else{
        	     echo "<td align='center'  bgcolor='#95e361' >$Gfont $rg[cinco]</td>";      //Verde;
        	  }

        	  	  			  
   		  echo "</tr>";
   		  
   		  $nRng++;
      }		  
         
   }//fin if

   echo "</table>$Gfont";
      
   PonPaginacion(false);      #-------------------pon los No.de paginas-------------------
      
   $aDpto = array("","","En proceso","Captura de resultados","Impresion de resultados","Pendientes por entregar a recepcion","Estudios en recepcion","Todas las etapas");

   if($busca==''){
      $Disp ='Sin imprimir etiqueta';
   }else{   
      $Disp  = $aDpto[$busca];
   }   

   echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";                  
      				 
     echo "$Gfont &nbsp; <a href='menu.php'><img src='lib/regresa.jpg' border='0'></a>";
     echo " &nbsp; Depto : ";
     echo "<SELECT name='Depto'>";
     while ($dep=mysql_fetch_array($DepA)){
           echo "<option value='$dep[0]'>$dep[1]</option>";
           if($dep[0]==$Depto){$Def=$dep[1];}
     }
     echo "<option value='*'>TODOS</option>";
     if($Depto=="*"){
      	echo "<option SELECTed value='*'>TODOS LOS DEPARTAMENTOS</option>";
     }else{
         echo "<option SELECTed value='$Depto'>$Def</option>";
     }
     echo "</SELECT> &nbsp; &nbsp; ";     
     
     echo "Estudios por etapa: &nbsp; ";
     echo "<select name='busca'>";
     echo "<option value=' '>Sin imprimir etiqueta</option>";
     echo "<option value='2'>En proceso</option>";
     echo "<option value='3'>Captura de resultados</option>";
     echo "<option value='4'>Impresion de resultados</option>";
     echo "<option value='5'>Pendientes por entregar a recepcion</option>";
     echo "<option value='6'>Estudios en recepcion</option>";
     echo "<option value='7'>Todas las etapas</option>";
     echo "<option selected value='$busca'>$Disp</option>";             
     echo "</select> &nbsp; ";        
     echo "<input type='submit' name='Boton' value='Enviar'> &nbsp; &nbsp; ";
     echo "<input type='hidden' name='pagina' value=1>";  
   
     echo  " &nbsp; <img src='lib/print.png' alt='Imprimir' border='0' onClick='window.print()'>";

     echo " &nbsp; &nbsp; &nbsp; &nbsp; <a class='pg' href=javascript:winuni('ordenrec.php?Recibeencaja=$Usr')>Entrega de Estudios a recepcion</a></td>";

   
   echo "</form>";         

   //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

  
   mysql_close();

  ?>

</body>

</html>
<?php

  session_start();
  if(!isset($_REQUEST[busca])){$busca=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[busca];}
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/lib.php");

  $link=conectarse();
  
  //if(isset($_REQUEST[pagina])) { $_SESSION[pagina] = $_REQUEST[pagina];}		#Pagina a editar  
  if(isset($_REQUEST[Sort]))   { $_SESSION[Sort] = $_REQUEST[Sort];}        #Orden Asc o Desc
  //if(isset($_REQUEST[orden]))  { $_SESSION[orden] = $_REQUEST[orden];}	   #Campo por el cual se ordena	
  //if(isset($_REQUEST[busca]))  { $_SESSION[busca] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $busca    = $_SESSION[cVarVal];        //Dato a busca;    
 
  #Variables comunes;
  $Msj      = $_REQUEST[Msj];
  $Titulo   = "Pedido Material de laboratorio";
  $op       = $_REQUEST[op];
  $Id       = 42; 	
  $Clave    = $_REQUEST[Clave];									                 //Numero de query dentro de la base de datos
  
  $pagina  = $_REQUEST[pagina];

  $OrdenDef="pedidod.idnvo";            //Orden de la tabla por default

  $Tabla = "pedidod";
  
  if($op=='ag' AND $Clave <> '' AND $_REQUEST[Cantidad]>0 ){
  
	  $InvA     = mysql_query("SELECT costo,existencia FROM invl WHERE clave='$Clave'");
	  $Inv      = mysql_fetch_array($InvA);
    
     //$Costo    = ($_REQUEST[Precio] * (1-($Dto[0]/100)))/$_REQUEST[Cantidad];
              
     $Up       = mysql_query("INSERT INTO pedidod (id,clave,cantidad,costo,existencia) 
                 VALUES 
                 ('$busca','$Clave',$_REQUEST[Cantidad],'$Inv[costo]','$Inv[existencia]')");
                 
	  
 //    $Up       = mysql_query("UPDATE invl SET existencia = existencia - $_REQUEST[Cantidad],costo = '$Inv[costo]'
 //                WHERE clave='$Clave' LIMIT 1");
     
     $Clave    = "";
                 
     Totaliza($busca);         
      
  }elseif($op=='ag' AND $_REQUEST[Producto] > 'a'){ 
     
     header("Location: cathos1.php?orden=hos.id&busca=$_REQUEST[Producto]");    
     
  }elseif($op=='Si'){                    //Elimina rg
	
	  $cId    = $_REQUEST[cId];	
     $CntA   = mysql_query("SELECT clave,cantidad FROM pedidod WHERE idnvo=$cId LIMIT 1");
     $Cnt    = mysql_fetch_array($CntA);	     
               
//     $Up     = mysql_query("UPDATE invl SET existencia=existencia + $Cnt[cantidad] WHERE clave='$Cnt[clave]' LIMIT 1");
     $Up     = mysql_query("DELETE FROM pedidod WHERE idnvo=$cId LIMIT 1");
	  	     
     Totaliza($busca);

     $Msj="Registro eliminado!";

  }
  
  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA  = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id",$link);
  $Qry   = mysql_fetch_array($QryA);
  
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  $HeA   = mysql_query("SELECT pedido.id, pedido.fecha, pedido.hora, pedido.cantidad, pedido.importe, pedido.status,
           pedido.recibio, pedido.concepto
           FROM pedido
           WHERE pedido.id = '$busca' ");

  $He    = mysql_fetch_array($HeA);


  $Sql   = "SELECT $Qry[campos],pedidod.idnvo FROM pedidod LEFT JOIN invl ON pedidod.clave = invl.clave
            
            WHERE pedidod.id = $busca";  

  //echo $Sql;
  
  $aCps  = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq  = array("&nbsp;","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos
  $aDat  = SPLIT(",",$Qry[edi]);			//Arreglo donde llena el grid de datos        
  $aDer  = array("Status","-","-","Elim","-","-");				 //Arreglo donde se meten los encabezados; Derechos;
  $tamPag= $Qry[tampag];
  
  require ("config.php");

  //echo $Sql;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

 headymenu($Titulo,false);

?>

<body bgcolor="#FFFFFF" onload="cFocus()">

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Rollo.focus();

}

function Mayusculas(cCampo){

if (cCampo=='Color'){document.form1.Color.value=document.form1.Color.value.toUpperCase();}

}

function ValGeneral(){
  if (document.form1.Rollo.value==""){
      alert("Es necesario poner el numero de rollo!");
      document.form1.Rollo.focus();
      return false;
  }
}


</script>

<?php
   echo "<div align='center'>$Gfont  ";

   echo "<b> No.salida : </b> $He[id] &nbsp; &nbsp; <b>Fecha: </b> $He[fecha] &nbsp; &nbsp;<b>Hora: </b> $He[hora] &nbsp; &nbsp; ";

   echo " <b> Concepto: </b> $He[concepto] </div>";
   
   echo "<div align='center'><b>Recibio:</b> $He[recibio] &nbsp; &nbsp;  &nbsp;<b>Cnt:</b> $He[cantidad] &nbsp; &nbsp;  &nbsp; ";
   
   echo "Importe: $ ".number_format($He[importe],"2")." &nbsp;</b>";
   
   echo " </div>";
   
  if(!$res=mysql_query($Sql)){

        echo "$Gfont <p align='center'>No se encontraron resultados y/o hay un error en el filtro</p>";    #Manda mensaje de datos no existentes
        echo "<p align='center'>$Gfont Favor de presiona Refresca...</p></font>";    #Manda mensaje de datos no existentes
		  $nRng=7;
		  $op='rs';        

   }else{   

		//echo $Sql;
		   
      CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
		
      $sql=$Sql.$cWel." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;
      
      $res=mysql_query($sql);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos   = strrpos($_SERVER[PHP_SELF],".");
      $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #

      while($rg=mysql_fetch_array($res)){
       
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
  
           echo "<tr bgcolor=$Fdo onMouseOut=this.style.backgroundColor=$Fdo;>";    

 		   echo "<td>&nbsp;</td>";

   		   $cantidad2 = $rg[existencia]-$rg[cantidad];

		   if($rg[existencia]<='0'){
			   $staped='Faltante';
			   $colorletra='#FF0000';
		   }else{
			   if($cantidad2 >= '1'){
					$staped='Disponible';
				    $colorletra='#666600';			   
		   		}else{
					if($cantidad2 == '0'){
						$staped='Ultima Pza';
  					    $colorletra='#FF3300';
		   		   }else{
					    $staped='Incompleto';	
  					    $colorletra='#FF0000';		   
				   }
				}
		   }
			  
           Display($aCps,$aDat,$rg);       

           echo "<td align='center'><font color=$colorletra>$staped</td>";
			  
		   if($He[status]<>'CERRADA'){	
              echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[idnvo]&op=Si')><img src='lib/deleon.png' alt='Elimina rg' border='0'></a></td>";
           }else{   
              echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina rg' border='0'></td>";
           }   

           echo "</tr>";
           $nRng++;


 	  }

     PonPaginacion(false);           #-------------------pon los No.de paginas-------------------
 
     //CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

     if($He[status]<>'CERRADA'){

	    echo "<form name='form1' method='get' action='pedidod.php' onSubmit='return ValGeneral();'>";

           echo " &nbsp; &nbsp; <a class='pg' href='pedido.php?orden=pedido.id'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";

           echo " &nbsp; Producto : ";

           echo "<input type='text' name='Clave' size='13' value='$Clave'> &nbsp; ";
  
           echo "<a href='invlab4.php?orden=invl.descripcion'><img src='lib/lupa_o.gif' alt='Catalogo' border='0'></a>&nbsp;";

           echo " &nbsp; Cnt : <input type='text' name='Cantidad' size='4' value=''> &nbsp; ";

           //echo " &nbsp; % Dto: <input type='text' name='Descuento' size='3' value=''> ";

           echo "<input type='hidden' name='op' value='ag'>";

          //echo "<input type='hidden' name='busca' value='$busca'>";

           echo " &nbsp; <INPUT TYPE='SUBMIT' name='Boton' value='Enviar'>";

        echo "</form>";

     }else{
     
        echo " &nbsp; &nbsp; <a class='pg' href='pedido.php'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";
     
     }

  }

  CierraWin();

  mysql_close();

  echo "</body>";

  echo "</html>";
  
  
function Totaliza($busca){

   
  $TotA  = mysql_query("SELECT sum(cantidad),sum(cantidad*costo),sum(costo*cantidad) FROM pedidod WHERE id=$busca");
  $Tot   = mysql_fetch_array($TotA);
  $Cnt   = $Tot[0]*1;
  $Imp   = $Tot[1]*1;
  //$Iva   = $Tot[2]*1;
     
  $Up    = mysql_query("UPDATE pedido SET cantidad=$Cnt,importe=$Imp WHERE id=$busca");
  
}  
  
?>

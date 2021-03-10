<?php

  session_start();
  if(!isset($_REQUEST[busca])){$busca=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[busca];}
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/lib.php");

  $link=conectarse();
  
  if(isset($_REQUEST[Sort]))   { $_SESSION[Sort] = $_REQUEST[Sort];}        #Orden Asc o Desc

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $busca    = $_SESSION[cVarVal];        //Dato a busca;    
 
  #Variables comunes;
  $Msj      = $_REQUEST[Msj];
  $Titulo   = "Salida Material de laboratorio";
  $op       = $_REQUEST[op];
  $Id       = 18; 	
  $Clave    = $_REQUEST[Clave];									                 //Numero de query dentro de la base de datos
  
  $pagina  = $_REQUEST[pagina];

  $OrdenDef="sld.idnvo";            //Orden de la tabla por default

  $Tabla = "sld";
  
  if($op=='ag' AND $Clave <> '' AND $_REQUEST[Cantidad]>0 ){
  
	  $InvA     = mysql_query("SELECT costo FROM invl WHERE clave='$Clave'");
	  $Inv      = mysql_fetch_array($InvA);
                  
     $Up       = mysql_query("INSERT INTO sld (id,clave,cantidad,costo) 
                 VALUES 
                 ('$busca','$Clave',$_REQUEST[Cantidad],'$Inv[costo]')");
     
     $Clave    = "";
                 
     Totaliza($busca);         
      
  }elseif($op=='ag' AND $_REQUEST[Producto] > 'a'){ 
     
     header("Location: cathos1.php?orden=hos.id&busca=$_REQUEST[Producto]");    
     
  }elseif($op=='Si'){                    //Elimina rg
	
	  $cId    = $_REQUEST[cId];	
      $Almacen = $_REQUEST[almacen];	
     $CntA   = mysql_query("SELECT clave,cantidad FROM sld WHERE idnvo=$cId LIMIT 1");
     $Cnt    = mysql_fetch_array($CntA);	     
               
     $Up     = mysql_query("DELETE FROM sld WHERE idnvo=$cId LIMIT 1");
	  	     
     Totaliza($busca);

     $Msj="Registro eliminado!";

  }
  
  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA  = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id",$link);
  $Qry   = mysql_fetch_array($QryA);
  
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  $HeA   = mysql_query("SELECT sl.id, sl.fecha, sl.hora, sl.cantidad, sl.importe, sl.status,
           sl.recibio, sl.concepto, sl.almacen
           FROM sl
           WHERE sl.id = '$busca' ");

  $He    = mysql_fetch_array($HeA);


  $Sql   = "SELECT $Qry[campos],sld.idnvo FROM sld LEFT JOIN invl ON sld.clave = invl.clave
            
            WHERE sld.id = $busca";  

  //echo $Sql;
  
  $aCps  = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq  = array("&nbsp;","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos
  $aDat  = SPLIT(",",$Qry[edi]);			//Arreglo donde llena el grid de datos        
  $aDer  = array("Elim","-","-");				 //Arreglo donde se meten los encabezados; Derechos;
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
			  
           Display($aCps,$aDat,$rg);           

			  if($He[status]<>'CERRADA'){	
              echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[idnvo]&op=Si&almacen=$He[almacen]')><img src='lib/deleon.png' alt='Elimina rg' border='0'></a></td>";
           }else{   
              echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina rg' border='0'></td>";
           }   

           echo "</tr>";
           $nRng++;


 	  }

     PonPaginacion(false);           #-------------------pon los No.de paginas-------------------
 
     //CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

     if($He[status]<>'CERRADA'){

	    echo "<form name='form1' method='get' action='sallabd.php' onSubmit='return ValGeneral();'>";

           echo " &nbsp; &nbsp; <a class='pg' href='sallab.php?orden=sl.id'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";

           echo " &nbsp; Producto : ";

           echo "<input type='text' name='Clave' size='13' value='$Clave'> &nbsp; ";
  
           echo "<a href='invlab2.php?orden=invl.descripcion'><img src='lib/lupa_o.gif' alt='Catalogo' border='0'></a>&nbsp;";

           echo " &nbsp; Cnt : <input type='text' name='Cantidad' size='4' value=''> &nbsp; ";

           //echo " &nbsp; % Dto: <input type='text' name='Descuento' size='3' value=''> ";

           echo "<input type='hidden' name='op' value='ag'>";

           echo "<input type='hidden' name='Almacen' value='$He[almacen]'>";

           echo " &nbsp; <INPUT TYPE='SUBMIT' name='Boton' value='Enviar'>";

        echo "</form>";

     }else{
     
        echo " &nbsp; &nbsp; <a class='pg' href='sallab.php'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";
     
     }

  }

  CierraWin();

  mysql_close();

  echo "</body>";

  echo "</html>";
  
  
function Totaliza($busca){

   
  $TotA  = mysql_query("SELECT sum(cantidad),sum(cantidad*costo),sum(costo*cantidad) FROM sld WHERE id=$busca");
  $Tot   = mysql_fetch_array($TotA);
  $Cnt   = $Tot[0]*1;
  $Imp   = $Tot[1]*1;
  //$Iva   = $Tot[2]*1;
     
  $Up    = mysql_query("UPDATE sl SET cantidad=$Cnt,importe=$Imp WHERE id=$busca");
  
}  
  
?>

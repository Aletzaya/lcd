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
  $Titulo   = "Consumo de productos por estudio";
  $op       = $_REQUEST[op];
  $Id       = 39; 	
  $Producto = $_REQUEST[Producto];									                 //Numero de query dentro de la base de datos
  
  $pagina  = $_REQUEST[pagina];

  $OrdenDef="estd.idnvo";            //Orden de la tabla por default

  $Tabla = "estd";
  
  if($_REQUEST[Boton]=='Agregar' AND $Producto <> '' AND $_REQUEST[Cantidad] > 0 ){
  
      $InvA     = mysql_query("SELECT iva FROM invl WHERE clave='$Clave'");
      $Inv      = mysql_fetch_array($InvA);
                 
      $Up       = mysql_query("INSERT INTO estd (estudio,producto,cantidad) 
                 VALUES 
                 ('$busca','$Producto','$_REQUEST[Cantidad]')");
                 	       
     $Producto  = "";
                 
     //Totaliza($busca);         
      
  }elseif($op=='Si'){                    //Elimina rg

     /* 
     $cId    = $_REQUEST[cId];	
     $CntA   = mysql_query("SELECT clave,cantidad FROM eld WHERE idnvo=$cId LIMIT 1");
     $Cnt    = mysql_fetch_array($CntA);	     
               
     $Up     = mysql_query("UPDATE invl SET existencia=existencia - $Cnt[cantidad] WHERE clave='$Cnt[clave]' LIMIT 1");
      
     */
     $Up     = mysql_query("DELETE FROM estd WHERE idnvo=$_REQUEST[cId] LIMIT 1");
	  	     
     //Totaliza($busca);

     $Msj="Registro eliminado!";

  }
  
  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA  = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id",$link);
  $Qry   = mysql_fetch_array($QryA);
  
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  $HeA   = mysql_query("SELECT descripcion
           FROM est
           WHERE est.estudio = '$busca' ");

  $He    = mysql_fetch_array($HeA);


  $Sql   = "SELECT $Qry[campos],estd.idnvo FROM estd LEFT JOIN invl ON estd.producto = invl.clave
            
            WHERE estd.estudio = '$busca'";  

  
  //echo $Sql;
  
  $aCps  = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq  = array("&nbsp;","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos
  $aDat  = SPLIT(",",$Qry[edi]);			//Arreglo donde llena el grid de datos        
  $aDer  = array(" ","-","-");				 //Arreglo donde se meten los encabezados; Derechos;
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

    echo "<br><table width='80%' border='1' cellpadding='2' cellspacing='0' align='center'>";

    echo "<tr><td align='center' bgcolor='#e1e1e1'>$Gfont ";

   echo "<b> Estudio: </b> $busca &nbsp; &nbsp; <b>Descripcion: </b> $He[descripcion]";
   
   echo "</td></tr></table>";
   
   $res=mysql_query($Sql);
		   
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
              echo "<td align='center'><a class='seleccionar' href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[idnvo]&op=Si')>eliminar</a></td>";
           }else{   
              echo "<td align='center'><img src='lib/deleoff.png' title='No es posible eliminar' border='0'></td>";
           }   

           echo "</tr>";
           $nRng++;


 	  }

     PonPaginacion(false);           #-------------------pon los No.de paginas-------------------
 
     //CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

     if($He[status]<>'CERRADA'){

	    //echo "<form name='form1' method='get' action='entlabd.php' onSubmit='return ValGeneral();'>";
            echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValCampos();'>";

            //echo " &nbsp; &nbsp; <a class='pg' href='entlab.php?orden=el.id'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";

           echo " &nbsp; &nbsp; <a class='pg' href='estudios.php?&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'><img src='lib/regresa.jpg' border='0'></a>&nbsp; &nbsp; ";

           echo "$Gfont &nbsp; Producto : ";

           echo "<input class='Input' type='text' name='Producto' size='13' value='$Producto'> &nbsp; ";
  
           echo "<a href='invlab3.php?orden=invl.descripcion'><img src='lib/lupa_o.gif' alt='Catalogo' border='0'></a>&nbsp;";

           echo " &nbsp; Cnt : <input class='Input' type='text' name='Cantidad' size='4' value=''> &nbsp; ";

           //echo " &nbsp; Costo : $ <input class='Input' type='text' name='Costo' size='7' value=''> &nbsp; ";

           //echo " &nbsp; % Dto: <input type='text' name='Descuento' size='3' value=''> ";

           //echo "<input type='hidden' name='op' value='ag'>";

          //echo "<input type='hidden' name='busca' value='$busca'>";

           echo " &nbsp; <INPUT TYPE='SUBMIT' name='Boton' value='Agregar'>";

        echo "</form>";

     }else{
     
        echo " &nbsp; &nbsp; <a class='pg' href='entlab.php?pagina=0'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";
     
     }

  CierraWin();

  mysql_close();

  echo "</body>";

  echo "</html>";
  
  
function Totaliza($busca){

   
  $TotA  = mysql_query("SELECT sum(cantidad),sum(cantidad*costo),sum(costo*cantidad),
           sum(cantidad*(costo*(iva/100))) 
           FROM eld WHERE id=$busca");
  $Tot   = mysql_fetch_array($TotA);
  $Cnt   = $Tot[0]*1;
  $Imp   = $Tot[1]*1;
  $Iva   = $Tot[3]*1;
     
  $Up    = mysql_query("UPDATE el SET cantidad=$Cnt,importe=$Imp,iva=$Iva WHERE id=$busca");
  
}  
  
?>
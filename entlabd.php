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
  $Titulo   = "Detalle Nota de Entrada";
  $op       = $_REQUEST[op];
  $Id       = 16; 	
  $Clave    = $_REQUEST[Clave];									                 //Numero de query dentro de la base de datos
  
  $Idproducto  = $_REQUEST[Idproducto];
  $pagina  = $_REQUEST[pagina];

  $OrdenDef="eld.idnvo";            //Orden de la tabla por default

  $Tabla = "eld";
  
  if($op=='ag' AND $Idproducto <> '' AND $_REQUEST[Cantidad]>0 AND $_REQUEST[Costo]>0){
  
      $InvA     = mysql_query("SELECT iva,pzasmedida FROM invl WHERE Id='$Idproducto'");
      $Inv      = mysql_fetch_array($InvA);

      if($_REQUEST[c_iva]==1){
        $Precio=$_REQUEST[Cantidad]*($_REQUEST[Costo]*1.16);
        $IvaA=$_REQUEST[Cantidad]*($_REQUEST[Costo]*.16);
      }else{
        $Precio=$_REQUEST[Cantidad]*$_REQUEST[Costo];
        $IvaA=0;
      }
                   
       $Up       = mysql_query("INSERT INTO eld (id,idproducto,clave,cantidad,costo,iva,civa,precio) 
                   VALUES 
                   ('$busca','$Idproducto','$Clave',$_REQUEST[Cantidad],'$_REQUEST[Costo]','$IvaA','$_REQUEST[c_iva]','$Precio')");  

        $TotA  = mysql_query("SELECT sum(cantidad),sum(precio),sum(iva) FROM eld WHERE id=$busca");
        $Tot   = mysql_fetch_array($TotA);
        $Cnt   = $Tot[0];
        $Imp   = $Tot[1];
        $Iva   = $Tot[2];
           
        $Up    = mysql_query("UPDATE el SET cantidad=$Cnt,importe=$Imp,iva=$Iva WHERE id=$busca");         
       $Clave    = "";
       $Idproducto = "";
                   
  }elseif($op=='ag' AND $_REQUEST[Clave] > 'a'){ 
     
     header("Location: invlab1.php?orden=invl.descripcion&busca=$_REQUEST[Clave]");    
     
  }elseif($op=='Si'){                    //Elimina rg
	
	  $cId    = $_REQUEST[cId];
    $Almacen = $_REQUEST[Almacen];	
     $CntA   = mysql_query("SELECT clave,cantidad FROM eld WHERE idnvo=$cId LIMIT 1");
     $Cnt    = mysql_fetch_array($CntA);	

    $InvA     = mysql_query("SELECT iva,pzasmedida FROM invl WHERE clave='$Clave'");
    $Inv      = mysql_fetch_array($InvA);

     $Up     = mysql_query("DELETE FROM eld WHERE idnvo=$cId LIMIT 1");
	  	     
      $TotA  = mysql_query("SELECT sum(cantidad),sum(precio),sum(iva) FROM eld WHERE id=$busca");
      $Tot   = mysql_fetch_array($TotA);
      $Cnt   = $Tot[0];
      $Imp   = $Tot[1];
      $Iva   = $Tot[2];
         
      $Up    = mysql_query("UPDATE el SET cantidad='$Cnt',importe='$Imp',iva='$Iva' WHERE id=$busca");

     $Msj="Registro eliminado!";

  }
  
  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA  = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id",$link);
  $Qry   = mysql_fetch_array($QryA);
  
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  $HeA   = mysql_query("SELECT el.id, el.fecha, el.hora, el.cantidad, el.importe, el.status,
           el.documento, el.concepto, el.iva, el.almacen, el.usr
           FROM el
           WHERE el.id = '$busca' ");

  $He    = mysql_fetch_array($HeA);


  $Sql   = "SELECT $Qry[campos],eld.idnvo,eld.idproducto,eld.civa FROM eld LEFT JOIN invl ON eld.clave = invl.clave WHERE eld.id = $busca";  

  $aCps  = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq  = array("Idproducto","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos
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

    if($He[almacen]=='invgral'){
      $Almacenes='General';
    }elseif($He[almacen]=='invmatriz'){
      $Almacenes='Matriz';
    }elseif($He[almacen]=='invtepex'){
      $Almacenes='Tepexpan';
    }elseif($He[almacen]=='invhf'){
      $Almacenes='HF';
    }elseif($He[almacen]=='invgralreyes'){
      $Almacenes='Gral.Reyes';
    }elseif($He[almacen]=='invreyes'){
      $Almacenes='Reyes';
    }

   echo "<div align='center'>$Gfont  ";

   echo "<b> Almacen: </b> $Almacenes &nbsp; &nbsp; <b> No.Entrada: </b> $He[id] &nbsp; &nbsp; <b>Fecha: </b> $He[fecha] &nbsp; &nbsp;<b>Hora: </b> $He[hora] &nbsp; &nbsp; ";

   echo " <b> Documento: </b> $He[documento] </div>";
   
   echo "<div align='center'><b>Usuario:</b> $He[usr] &nbsp; &nbsp;  &nbsp;<b>Concepto:</b> $He[concepto] &nbsp; &nbsp;  &nbsp;<b>Cnt:</b> $He[cantidad] &nbsp; &nbsp;  &nbsp; ";
   
   echo "<b>Importe: $ </b>".number_format($He[importe],"2")." &nbsp; </b>";
   
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

          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";    

          echo "<td>$Gfont $rg[idproducto]</td>";

          Display($aCps,$aDat,$rg);  

          if($He[status]<>'CERRADA'){	

              echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$rg[idnvo]&op=Si&almacen=$He[almacen]')><img src='lib/deleon.png' alt='Elimina rg' border='0'></a></td>";

          }else{   

              echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina rg' border='0'></td>";
          }   

          echo "</tr>";
          $nRng++;
 	  }

    $TotA  = mysql_query("SELECT sum(cantidad),sum(precio),sum(iva),sum(costo) FROM eld WHERE id=$busca");
    $Tot   = mysql_fetch_array($TotA);
    $Cnt   = $Tot[0];
    $Imp   = $Tot[1];
    $Iva   = $Tot[2];
    $Cost   = $Tot[3];

    echo "<tr>";    
    echo "<td align='right' colspan='3'><b>$Gfont Totales: </b></td>";
    echo "<td align='right' bgcolor='#b4d0de'>$Gfont <font size='1'><b>".number_format($Cnt,"2")." &nbsp;&nbsp;</b></td>";
    echo "<td align='right' bgcolor='#b4d0de'>$Gfont <font size='1'><b>".number_format($Cost,"2")." &nbsp;&nbsp;</b></td>";
    echo "<td align='right' bgcolor='#b4d0de'>$Gfont <font size='1'><b>".number_format($Iva,"2")." &nbsp;&nbsp;</b></td>";
    echo "<td align='right' bgcolor='#b4d0de'>$Gfont <font size='1'><b>".number_format($Imp,"2")." &nbsp;&nbsp;</b></td>";
    echo "</tr>";

     PonPaginacion(false);           #-------------------pon los No.de paginas-------------------
 
     //CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

     if($He[status]<>'CERRADA'){

	    echo "<form name='form1' method='get' action='entlabd.php' onSubmit='return ValGeneral();'>";

//           echo " &nbsp; &nbsp; <a class='pg' href='entlab.php?orden=el.id'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";

           echo " &nbsp; &nbsp; <a class='pg' href='entlab.php?orden=el.id'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";

           echo " &nbsp; Producto : ";

           echo "<input type='text' name='Clave' size='20' value='$Clave'> &nbsp; ";
  
           echo "<a href='invlab1.php?orden=invl.descripcion'><img src='lib/lupa_o.gif' alt='Catalogo' border='0'></a>&nbsp;";

           if($Idproducto<>''){

              echo " &nbsp; Cnt : <input type='text' name='Cantidad' size='4' value='' required> &nbsp; ";

              echo " &nbsp; Costo : $ <input type='text' name='Costo' size='7' value='' required> &nbsp; ";

              echo " &nbsp; IVA : <input type='checkbox' name='c_iva' value='1'>";

              //echo " &nbsp; % Dto: <input type='text' name='Descuento' size='3' value=''> ";

              echo "<input type='hidden' name='Idproducto' value='$Idproducto'>";

              echo "<input type='hidden' name='Almacen' value='$He[almacen]'>";

           }

           echo "<input type='hidden' name='op' value='ag'>";

           echo " &nbsp; <INPUT TYPE='SUBMIT' name='Boton' value='Enviar'>";

        echo "</form>";

     }else{
     
        echo " &nbsp; &nbsp; <a class='pg' href='entlab.php?orden=el.id'><img src='lib/regresar.gif' border='0'></a>&nbsp; &nbsp; ";
     
     }

  }

  CierraWin();

  mysql_close();

  echo "</body>";

  echo "</html>";  
  
?>

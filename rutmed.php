<?php

  #Librerias
  session_start();

  //if(isset($_REQUEST[Nomina])){$_SESSION['Nomina']=$_REQUEST[Nomina];}
  
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
  $Titulo  = "Rutas medicas";
  $op      = $_REQUEST[op];
  $Id      = 19; 										                 //Numero de query dentro de la base de datos
  
  
  #Intruccion a realizar si es que mandan algun proceso  
  if($_REQUEST[Boton] == 'Agregar' AND $_REQUEST[Descripcion]<>''){

	  $lUp  = mysql_query("INSERT INTO ruta (descripcion) 
	          VALUES 
	          ('$_REQUEST[Descripcion]')");
	  
	  $Msj  = "Registro agregado con EXITO!"; 	

  }elseif($_REQUEST[Boton] == 'Actualizar'){
  
     $lUp  = mysql_query("UPDATE ruta SET descripcion='$_REQUEST[Descripcion]'
             WHERE id='$_REQUEST[cId]'");

  }elseif($op=='Si'){                    //Elimina rg
     /*
      $ExiA = mysql_query("SELECT * FROM mov WHERE empleado='$_REQUEST[cId]' limit 1");
      $Emp=mysql_fetch_array($EmpA);
      if ($Emp[id]<>""){
 			$Msj="No es posible eliminar este empleado pues hay checadas registradas";
    	}else{			  
          $lUp=mysql_query("DELETE FROM bco WHERE id='$_REQUEST[cId]' limit 1");
          $Msj="Registro eliminado";
      }
     */

     $Msj = "Opcion deshabilitada";
      
  }elseif($op=='rs'){
      $Up=mysql_query("UPDATE qrys SET filtro='' WHERE id=$Id");
      $op='';
  }

  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
  $Qry  = mysql_fetch_array($QryA);
  
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  #Deshago la busqueda por palabras(una busqueda inteligte;
     $Palabras  = str_word_count($busca);  //Dame el numero de palabras
     if($Palabras > 1){
        $P=str_word_count($busca,1);          //Metelas en un arreglo
        for ($i = 0; $i < $Palabras; $i++) {
            if(!isset($BusInt)){$BusInt=" per.nombre like '%$P[$i]%' ";}else{$BusInt=$BusInt." AND per.nombre like '%$P[$i]%' ";}
        }
     }else{
        $BusInt=" ruta.descripcion like '%$busca%' ";  
     }
  
  #Armo el query segun los campos tomados de qrys;
  $cSql   = "SELECT $Qry[campos] FROM $Qry[froms] WHERE $BusInt $Qry[filtro]";

  //echo $cSql;
  
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Edit","-","-");				    //Arreglo donde se meten los encabezados; Izquierdos
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

<script language="JavaScript1.2">
function ValCampos(){

    if (document.form1.Departamento.value =="" || document.form1.Nombre.value =="" ){
       alert("Aun hay campos importantes en blanco");
       document.form1.Departamento.focus();
       return false;
    }
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}

function cFocus(){
  document.form1.Nombre.focus();
}



</script>


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
		
      $sql   = $cSql.$cWhe." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;
      
      //echo $sql;

      $res   = mysql_query($sql,$link);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos   = strrpos($_SERVER[PHP_SELF],".");
      $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'.php';     #

      while($rg=mysql_fetch_array($res)){
            
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           
           echo "<td align='center'><a href='$cLink?cId=$rg[id]&op=ed'><img src='lib/edit.png' alt='Modifica rg' border='0'></td>";

           echo "<td align='left'>$Gfont $rg[id]</td>";
           echo "<td align='left'>$Gfont $rg[descripcion]</td>";

           //Display($aCps,$aDat,$rg);           

           //echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;rg?','$_SERVER[PHP_SELF]?cId=$rg[id]&op=Si');><img src='lib/deleoff.png' alt='Elimina rg' border='0'></a></td>";
           //echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina rg' border='0'></td>";
           //echo "<td align='center'><a class='ord' href=javascript:winuni('./signup/signup.php?id=$NameFir')> <b>Firma</b> </td>";

           echo "</tr>";

           $nRng++;

      }
	
 	 }
     
    PonPaginacion(false);           #-------------------pon los No.de paginas-------------------

    if($op=='ed'){

		 $CpoA  = mysql_query("SELECT * FROM ruta WHERE id=$_REQUEST[cId]");
		 $Cpo   = mysql_fetch_array($CpoA);    
    
    }
    echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

			echo "<div>$Gfont ";
			
			if($op=='ed'){
			 
			 	echo " &nbsp; <a href=".$_SERVER['PHP_SELF']."><img src='lib/regresar.gif' border='0'></a> &nbsp; ";
			
			}else{
				
				echo " &nbsp; &nbsp; &nbsp; ";			
			
			}
			
						
			echo " &nbsp; Ruta: $Cpo[id] &nbsp; ";			

			echo " &nbsp; Descripcion: ";			
			echo "<input type='text' name='Descripcion' size='30' maxlenght='40' value='$Cpo[descripcion]' onBLur=Mayusculas('Descripcion')> &nbsp; ";

			if($op=='ed'){
			
  			   echo "<input type='hidden' name='cId' value='$Cpo[id]'>";
   			echo "<input type='submit' name='Boton' value='Actualizar'>";			
  			   
  			}else{
  			
			   echo "<input type='submit' name='Boton' value='Agregar'>";			
  			
  			}   			
						
			
			echo "</div>";

    echo "</form>";

    //CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
<?php

  session_start();
  
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");

  $link=conectarse();  

  //Cuando entra por primera vez debe de traer estos parametros por default;
  if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_REQUEST[pagina];			  // Ojooo en el detalle la pagina la tomamos de REQUEST no de la session
  $busca    = $_SESSION[busca];          // Dato a busca
  $OrdenDef = "filesd.orden";            // Orden de la tabla por default
    
  #Variables comunes;
  $Titulo = "Campos por tabla";
  $Return = 'tablas.php';
  $op     = $_REQUEST[op];
  $cId    = $_REQUEST[cId];
  $Id     = 4; 								  //Numero de query dentro de la base de datos

  if($op=='ag'){				//Agrega producto por producto

     $lUpA=mysql_query("SELECT campo FROM filesd WHERE id='$busca' AND campo='$_REQUEST[Campo]'");
     $lUp=mysql_fetch_array($lUpA);

     if($lUp[campo]==$_REQUEST[Campo]){		
 		  $Msj="El campo $_REQUEST[Campo] ya existe!";
 	 }else{		    
        $lUp=mysql_query("INSERT INTO filesd (id,campo,descripcion,tipo,longitud,edit,orden) 
        VALUES 
        ('$busca','$_REQUEST[Campo]','$_REQUEST[Descripcion]','$_REQUEST[Tipo]','$_REQUEST[Longitud]','$_REQUEST[Edit]','$_REQUEST[Orden]')");
    }

  }elseif($op=='Si'){                    //Elimina Registro
  
     $lUp  = mysql_query("DELETE FROM filesd WHERE idnvo='$cId' limit 1");
     $Msj  = "El Campo: $_REQUEST[Campo] ha sido dado de baja!";

  }elseif($op=='Ed'){
  
     $CpoA = mysql_query("SELECT * FROM filesd WHERE idnvo='$cId'");
     $Cpo  = mysql_fetch_array($CpoA);  

  }elseif($op=='Up'){

  	  $lUp  = mysql_query("UPDATE filesd SET descripcion='$_REQUEST[Descripcion]',longitud='$_REQUEST[Longitud]',
  	          tipo='$_REQUEST[Tipo]',edit='$_REQUEST[Edit]',orden='$_REQUEST[Orden]' 
  	          WHERE idnvo='$cId'");  

  }

  //Datos del Encabezado;
  $HeA  = mysql_query("SELECT tabla,descripcion FROM files WHERE id = '$busca'");
  $He   = mysql_fetch_array($HeA);


  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id=$Id");
  $Qry  = mysql_fetch_array($QryA);
  
  if(strlen($Qry[filtro])>5){$Dsp='Filtro activo';}
  
  #Armo el query segun los campos tomados de qrys;
  $cSql   = "SELECT $Qry[campos],filesd.id, filesd.idnvo FROM $Qry[froms] WHERE filesd.id = '$busca' $Qry[filtro]";
  //echo $cSql;
  	
  $aCps   = SPLIT(",",$Qry[campos]);		// Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Edit","-","-");	                 //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);			           //Arreglo donde llena el grid de datos        
  $aDer   = array("Elim","-","-");				        //Arreglo donde se meten los encabezados; Derechos;
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

 // echo $cSql;
  
  if(!$res=mysql_query($cSql)){
        echo "$Gfont <p align='center'>No se encontraron resultados y/o hay un error en el filtro</p>";    #Manda mensaje de datos no existentes
        echo "<p align='center'>$Gfont Favor de presiona Refresca...</p></font>";    #Manda mensaje de datos no existentes
		  $nRng=7;
		  $op='rs';        
   }else{
   
      echo "$Gfont <br> &nbsp; &nbsp; <b>Tabla[$busca]:</b> $He[tabla] &nbsp; &nbsp; ";
      echo "<b>Descripcion:</b> $He[descripcion] &nbsp; &nbsp; <b>Renglones por pagina: </b>".number_format($Qry[tampag],"2");

      CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
		
      $sql = $cSql.$cWhe." ORDER BY ".$orden." $Sort LIMIT ".$limitInf.",".$tamPag;

      $res = mysql_query($sql);

      PonEncabezado();         #---------------------Encabezado del browse----------------------

      $Pos   = strrpos($_SERVER[PHP_SELF],".");
      $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'.php';     #

      while($registro=mysql_fetch_array($res)){

           if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
           
           echo "<tr bgcolor=$Fdo onMouseOut=this.style.backgroundColor=$Fdo;>";
           echo "<td align='center'><a href='$cLink?op=Ed&cId=$registro[idnvo]&pagina=$pagina'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";

           Display($aCps,$aDat,$registro);           

           echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$registro[idnvo]&op=Si')><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
           echo "</tr>";
           $nRng++;

      }

	
	}
     
   PonPaginacion(false);           #-------------------pon los No.de paginas-------------------


	     echo "<form name='form1' method='get' action='$_SERVER[PHP_SELF]' onSubmit='return ValGeneral();'>";
        echo "&nbsp; <a href=$Return?busca=><img src='lib/regresa.jpg' border='0'></a> &nbsp; ";
	     
		  if($op=='Ed'){
    	      echo "<input type='hidden' name='cId' value='$cId'>";
    	      echo "<input type='hidden' name='op' value='Up'>";
    	      $Dis='disabled';    	      
		  }else{
	         echo "<input type='hidden' name='op' value='ag'>";
	         $Dis='enabled';
		  }    
		  
		  echo "$Gfont Campo: ";
        echo "<input type='text' value='$Cpo[campo]' name='Campo' size='8' $Dis>";
        
		  echo " &nbsp; Desc: ";
        echo "<input type='text' value='$Cpo[descripcion]' name='Descripcion' size='15' >";

        echo " &nbsp; Tipo: ";
        echo "<select name='Tipo'>";
        echo "<option value='char'>Caracter</option>";
        echo "<option value='int'>Entero</option>";
        echo "<option value='float'>Flotante</option>";
        echo "<option value='date'>Fecha</option>";
        echo "<option value='time'>Hora</option>";
        echo "<option value='texto'>Memo</option>";
        echo "<option selected>$Cpo[tipo]</option>";
        echo "</select> ";

		  echo " &nbsp; Long: ";
        echo "<input type='text' name='Longitud' size='3' value='$Cpo[longitud]' >";

		  echo " &nbsp; Secuencia: ";
        echo "<input type='text' name='Orden' size='3' value='$Cpo[orden]' >";
        
        echo " &nbsp; <input type='submit' name='ok' value='ok'>";

 	     echo "<input type='hidden' name='pagina' value=$pagina>";
	     echo "<input type='hidden' name='busca' value=$busca>";

       echo "</form>";

        echo "</div>";
   

  //CuadroInferior($busca);      #-------------------Siempre debe de estar por que cierra la tabla principal .

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?>
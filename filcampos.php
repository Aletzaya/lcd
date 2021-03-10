<?php

   #Librerias
   session_start();
   
   require("lib/lib.php");
  
   #Variables comunes;
   $Id       = $_REQUEST[Id]; 										//Numero de query dentro de la base de datos
   $Tabla    = "emp";
   $tamPag   = 15;
   $OrdenDef = "emp.id";            			//Orden de la tabla por default
   $Sort     = $_REQUEST[Sort];             //Orden ascendente o descendente
   $pagina   = $_REQUEST[pagina];
   $link     = conectarse();
			
	$QryA     = mysql_query("SELECT froms,campos,nombre,tampag FROM qrys WHERE id=$Id",$link);
	$Qry      = mysql_fetch_array($QryA);
	
   $aTablas  = SPLIT(",",$Qry[froms]);        
   for ($i = 0; $i < sizeof($aTablas); $i++) {
        $cPso=$aTablas[$i] ;
        if(strlen($cTablas) < 1 ){$cTablas="files.tabla= '$cPso'"; }else{ $cTablas=$cTablas." or files.tabla='$cPso'"; }	           
   }

   $cSql     = "SELECT files.tabla, filesd.campo, filesd.descripcion,files.descripcion,filesd.tipo
					FROM files, filesd
					WHERE ($cTablas) AND files.id = filesd.id";

	#Intruccion a realizar si es que mandan algun proceso
   if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo
      $res=mysql_query($cSql,$link);
       while($rg=mysql_fetch_array($res)){

 			  $cValor='v'.$rg[tabla].$rg[campo];
 			  $cSigno='s'.$rg[tabla].$rg[campo]; 			  
 			  $cYo='y'.$rg[tabla].$rg[campo]; 			  

 			  if($_REQUEST[$cSigno]<>'SIGNO'){	
 			      if($_REQUEST[$cSigno]==' like '){$Lt='%';}else{$Lt='';}
					if(strlen($cWhere)<3){		//Esto es cuando entra por primera vez;
					      if($_REQUEST[$cYo]=='FIN'){
							    $cWhere=" and ".$rg[tabla].".".$rg[campo].$_REQUEST[$cSigno]."\'$Lt".$_REQUEST[$cValor]."$Lt\'";
							 }else{
							    $cWhere=" and ".$rg[tabla].".".$rg[campo].$_REQUEST[$cSigno]."\'$Lt".$_REQUEST[$cValor]."$Lt\'".$_REQUEST[$cYo];							 
							 }   
					}else{
							$cWhere=$cWhere.$rg[tabla].".".$rg[campo].$_REQUEST[$cSigno]."\'$Lt".$_REQUEST[$cValor]."$Lt\'";
					}
					
 			  }
      }

       $Up=mysql_query("UPDATE qrys SET filtro='$cWhere',tampag='$_REQUEST[TamPag]' where id=$Id",$link);

       echo "<script language='javascript'>setTimeout('self.close();',100)</script>";
    
	}elseif($_REQUEST[Boton] == Cancelar ){        //Para agregar uno nuevo

         echo "<script language='javascript'>setTimeout('self.close();',0)</script>";
   
   }elseif($_REQUEST[op]=='rs'){
   
   		$lUp=mysql_query("update qrys set campos=defcampos,edi=defedi,tampag=15,filtro='' where id=$Id limit 1",$link);
         echo "<script language='javascript'>setTimeout('self.close();',0)</script>";
   
   }
      
   require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php

		echo "<div align='center'>$Gfont <font color='$GfdoTitulo' size='+1'><b> Proceso de filtros</b></font></div>";
		echo "<div align='center'><b> $Qry[nombre]</b></div>";

      echo "<form name='form1' method='get' action='$_SERVER[PHP_SELF]'>";


      echo "<table align='center' width='100%' border='1' cellpadding=2 cellspacing=0>";
      
      echo "<tr>";
      echo "<th  height='25' bgcolor='$Gfdogrid'>$Gfont <font color='#ffffff'> T a b l a </font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid'>$Gfont <font color='#ffffff'> C a m p o </font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid'>$Gfont <font color='#ffffff'> Tipo</font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid' align='center'>$Gfont <font color='#ffffff'> Condicion</font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid' align='center'>$Gfont <font color='#ffffff'> Valor</font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid' align='center'>$Gfont <font color='#ffffff'>y/o</font></th>";
      echo "</tr>";

      $res=mysql_query($cSql,$link);
      $nRen=0;
       while($registro=mysql_fetch_array($res)){

           if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
       
 			  $cValor='v'.$registro[tabla].$registro[campo];
			   $cSigno='s'.$registro[tabla].$registro[campo]; 			  
 			       $cYo='y'.$registro[tabla].$registro[campo]; 			  
 			  $Campo=$registro[0].".".$registro[1];
 			  
           //echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
           //echo "<tr bgcolor=$Fdo onMouseOut=this.style.backgroundColor=$Fdo;>";
           echo "<td>$Gfont ".strtolower($registro[3])." </font></td>";
           echo "<td>$Gfont $registro[2] </font></td>";
           echo "<td>$Gfont $registro[4] </font></td>";
           echo "<td>";
           echo "<select name=$cSigno>";
           echo "<option value='SIGNO'>Signo</option>";
           echo "<option value='='>Igual a</option>";
           echo "<option value='>'>Mayor a</option>";
           echo "<option value='>='>Mayor igual a</option>";
           echo "<option value='<'>Menor a</option>";
           echo "<option value='<='>Menor igual a</option>";
           echo "<option value='<>'>Diferente de</option>";
           echo "<option value=' like '>Contenga</option>";
           echo "</select>";
           echo "</td>";
           
           echo "<td align='center'><input type='text' name='$cValor' size='10'></td>";

           echo "<td>";
           echo "<select name=$cYo>";
           echo "<option value='FIN'>FIN</option>";
           echo "<option value=' and '> Y</option>";
           echo "<option value=' or '> O </option>";
           echo "</select>";
           echo "</td>";                      
           echo "</tr>";
           $nRng++;
      }
      cTableCie();

      echo "<table align='center' width='100%' border='0' cellpadding=0 cellspacing=0>";
      echo "<td height='30' align='center'>$Gfont No.renglones por pagina: <input type='text' name='TamPag' value='$Qry[tampag]' size='2'>";
      echo "</td><td align='center'>";
		echo "<a class='ord' href='$_SERVER[PHP_SELF]?Id=$Id&op=rs'>$Gfont Recarga filtro original</a> &nbsp; &nbsp; ";
      echo "</td></table>";
      
      echo "<div align='center'><a class='ord' href='javascript:window.close()'>Cerrar &eacute;sta Ventana </a></div>";
      
      echo "<input type='hidden' name='Id' value='$Id'>";      

      echo "<div align='center'>";
      Botones(); 
      echo "</div>";

	echo "</form>";
	
   echo "</body>";
   
   echo "</html>";
   
   mysql_close();
   
?>
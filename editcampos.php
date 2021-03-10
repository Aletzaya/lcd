<?php

  #Librerias
  session_start();
  require("lib/lib.php");
  
   #Variables comunes;
   $Id=$_REQUEST[Id]; 										//Numero de query dentro de la base de datos
   //$Tabla="emp";
   //$tamPag=15;
   //$OrdenDef="emp.id";            			//Orden de la tabla por default
   //$Sort=$_REQUEST[Sort];             //Orden ascendente o descendente
   //$busca=$_REQUEST[busca];
   //$pagina=$_REQUEST[pagina];
   $link=conectarse();
   
   if(!isset($Sort)){$Sort='Asc';}
			
	$QryA=mysql_query("SELECT froms,campos,nombre,tampag FROM qrys WHERE id=$Id",$link);
	$Qry=mysql_fetch_array($QryA);
	
   $aTablas = SPLIT(",",$Qry[froms]);        
   for ($i = 0; $i < sizeof($aTablas); $i++) {
         $cPso=$aTablas[$i] ;
   	   if(strlen($cTablas) < 1 ){$cTablas="files.tabla= '$cPso'"; }else{ $cTablas=$cTablas." or files.tabla='$cPso'"; }	           
   }

   $cSql="SELECT files.tabla, filesd.campo, filesd.descripcion,files.descripcion,filesd.tipo
	FROM files, filesd
	WHERE ($cTablas)
   AND files.id = filesd.id";

	#Intruccion a realizar si es que mandan algun proceso
   if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo
      $res=mysql_query($cSql,$link);
       while($rg=mysql_fetch_array($res)){
 			  $cVariable=$rg[tabla].$rg[campo];
 			  if($_REQUEST[$cVariable]=='Si'){
					if(strlen($Campos)<1){$Campos=$rg[0].".".$rg[1];}else{$Campos=$Campos.",".$rg[0].".".$rg[1];}

					if($rg[tipo]=='Int' or $rg[tipo]=='float'){$Tipo='N';}else{$Tipo='C';}					
					
					if(strlen($Edi)<1){$Edi=$rg[2].",".$rg[1].",$Tipo";}else{$Edi=$Edi . "," . $rg[2].",".$rg[1].",$Tipo";}
 			  }
      }

       $Up=mysql_query("UPDATE qrys SET campos='$Campos', edi = '$Edi' where id=$Id",$link);

       echo "<script language='javascript'>setTimeout('self.close();',100)</script>";
    
	}elseif($_REQUEST[Boton] == Cancelar ){        //Para agregar uno nuevo

         echo "<script language='javascript'>setTimeout('self.close();',0)</script>";
   
   }elseif($_REQUEST[op]=='rs'){
   
   		$lUp=mysql_query("update qrys set campos=defcampos,edi=defedi,tampag=15,filtro='' where id=$Id limit 1",$link);
         echo "<script language='javascript'>setTimeout('self.close();',0)</script>";
   
   }
      
   require ("confignew.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php

		echo "<div align='center'>$Gfont <font color='#003399'><font size='+1'>Desplegado de campos de la tabla de: <b> $Qry[nombre]</b></font></div><br>";

      echo "<form name='form1' method='get' action='$_SERVER[PHP_SELF]'>";


      echo "<table align='center' width='100%' border='1' cellpadding=1 cellspacing=0>";
    
      echo "<tr>";
      echo "<th bgcolor='$Gfdogrid'  height='25'>&nbsp;</th>";
      echo "<th bgcolor='$Gfdogrid'  height='25'>&nbsp;</th>";
      echo "<th bgcolor='$Gfdogrid' height='25' colspan='2'>$Gfont  Desplegar el campo</font></font></th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th  height='25' bgcolor='$Gfdogrid'>$Gfont T a b l a </font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid'>$Gfont C a m p o </font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid' align='center'>$Gfont Si </font></th>";
      echo "<th  height='25' bgcolor='$Gfdogrid' align='center'>$Gfont No </font></th>";
      echo "</tr>";

      $res=mysql_query($cSql,$link);
      $nRen=0;
       while($registro=mysql_fetch_array($res)){

           if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
       
 			  $cVariable=$registro[tabla].$registro[campo];
 			  $Campo=$registro[0].".".$registro[1];
           //echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
           //echo "<tr bgcolor=$Fdo onMouseOut=this.style.backgroundColor=$Fdo;>";
           echo "<td>$Gfont $registro[3] </font></td>";
           echo "<td>$Gfont $registro[2] </font></td>";
           if (ereg($Campo,$Qry[campos])){
               echo "<td align='center'><input type='radio' name='$cVariable' value='Si' checked></td>";
              echo "<td align='center'><input type='radio' name='$cVariable' value='No'></td>";
           }else{                      
              echo "<td align='center'><input type='radio' name='$cVariable' value='Si'></td>";
              echo "<td align='center'><input type='radio' name='$cVariable' value='No' checked></td>";
			  }
           echo "</tr>";
           $nRng++;
      }
      cTableCie();
      
      echo "<p align='center'><a class='pg' href='$_SERVER[PHP_SELF]?Id=$Id&op=rs'>Restablece los campos por default</a></p>";

      echo "<input type='hidden' name='Id' value='$Id'>";
      
      Botones();

	echo "</form>";
	
   echo "</body>";
   
   echo "</html>";
   
   mysql_close();
   
?>
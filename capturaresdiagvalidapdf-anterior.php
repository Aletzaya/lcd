<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link    = conectarse();

  $tamPag  = 15;

  $Usr     = $check[uname];
  $Depto   = $_REQUEST[Depto];
  $busca   = $_REQUEST[busca];
  $pagina  = $_REQUEST[pagina];
  $cId       = $_REQUEST[busca]; 

  $estudio = $_REQUEST[estudio];  
  $archivo = $_REQUEST[archivo];  
  $alterno = $_REQUEST[alterno]; 
  $lBd       = false; 
  $TFr=0; 

  $op      = $_REQUEST[op];
  
if($alterno=='0'){
	$tabla='elepdf';
}else{
	if($alterno=='1'){
		$tabla='elealtpdf';
	}else{
		$tabla='elealtpdf2';
	}
}

if($archivo<>''){
	$id   = $_REQUEST[id];
	unlink("estudios/$archivo");
	$Usrelim    = $_COOKIE['USERNAME'];
    $Fechaelim  = date("Y-m-d H:i:s");
    $lUp = mysql_query("UPDATE estudiospdf set usrelim='$Usrelim',fechaelim='$Fechaelim' where archivo='$archivo' and id='$id'");
}

if($busca == $cId){$lBd = true;}

if($busca <> $cId){ $busca = $cId;}
  
  require("fileupload-class.php");

$path = "estudios/";

$upload_file_name = "userfile";
	
// En este caso acepta todo, pero podemos filtrar que tipos de archivos queremos
$acceptable_file_types = "";

// Si no se le da una extension pone por default: ".jpg" or ".txt"
$default_extension = "";

// MODO: Si se intenta subir un archivo con el mismo nombre a:

// $path directory


// HAY OPCIONES:
//   1 = modo de sobreescritura
//   2 = crea un nuevo archivo con extension incremental
//   3 = no hace nada si existe (mayor proteccion)

$mode = 2;


if (isset($_REQUEST['submitted']) AND $lBd ) {

    // Crea un nueva instancia de clase
    $my_uploader = new uploader($_POST['language']);

    // OPCIONAL: Tamano maxino de archivos en bytes
    $my_uploader->max_filesize(3000000);

    // OPCIONAL: Si se suben imagenes puedes poner el ancho y el alto en pixeles 
    $my_uploader->max_image_size(1500, 1800); // max_image_size($width, $height)
    // Sube el archivo

    if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {

        $my_uploader->save_file($path, $mode);
    }

    if ($my_uploader->error) {
        echo $my_uploader->error . "<br><br>\n";
    } else {
        
        // Imprime el contenido del array (donde se almacenan los datos del archivo)...
        //print_r($my_uploader->file);

        $cNombreFile    = $my_uploader->file['name'];
        $Size           = $my_uploader->file['size'];
        $NombreOri      = $my_uploader->file['raw_name'];
        $Usr2    = $_COOKIE['USERNAME'];
       $Fechasub  = date("Y-m-d H:i:s");

        $lUp = mysql_query("INSERT INTO estudiospdf (id,archivo,usr,fechasub) VALUES ('$busca','$cNombreFile','$Usr2','$Fechasub')");

    }
}

  if($op=='rs'){  //Registra resultados
            
      $Msj = "";
		$OtdA   = mysql_query("SELECT status, capturo, tres, usrvalida, fechavalida, impest, impres, fr, creapdf FROM otd WHERE estudio='$estudio' AND orden='$busca'");
		$Otd    = mysql_fetch_array($OtdA);
		
	if($Otd[capturo]<>''){
		
       if($_REQUEST[Confirmacion]<>''){
       	
       	 $Pasw  = $_REQUEST[Confirmacion];
       	 $AccA  = mysql_query("SELECT uname FROM authuser 
       	          WHERE uname='$Usr' AND passwd=md5('$Pasw')");
       	          
       	 $Acc   = mysql_fetch_array($AccA);         
       	          
		   	          
				$lUp  = mysql_query("DELETE FROM resul WHERE orden='$busca' AND estudio='$estudio'");
						 if($Acc[uname] == $Usr){     

				$EleA = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' and tipo<>'e' and tipo<>'s' and tipo<>'z' and tipo<>'v' ORDER BY id");
				
				$Msj   = "Tus datos han sido validados y guardados con exito!";	
					   
				while($Ele=mysql_fetch_array($EleA)){
				
					$Rs=$Ele[id];
					
					$Resultado=$_REQUEST[$Rs];

					if($Ele[tipo]=="l"){
					
						$Campo='l';
					
					}elseif($Ele[tipo]=="d"){
					
						$Campo='d';
					
					}elseif($Ele[tipo]=="n"){
					
						$Campo='n';
					
						if($Resultado < $Ele[min]  OR $Resultado > $Ele[max]){
							$TFr=$TFr+1;
						}else{
							$TFr=$TFr+0;
						}
					
					
					}elseif($Ele[tipo]=="c"){
					
						$Campo='c';
					
					}else{
					
						$Campo='t';
					
					}

					$lUp = mysql_query("INSERT INTO resul (orden,estudio,elemento,$Campo) 
						   VALUES
						   ('$busca','$estudio','$Ele[id]','$Resultado')");
				}
				
				$Fecha = date("Y-m-d");
				$Hora  = date("H:i");
				
				if($TFr==0){
					$fr2='0';
				}else{
					$fr2='1';
				}	

				$Up = mysql_query("UPDATE otd SET fechavalida = '$Fecha $Hora', usrvalida='$Usr', alterno='$alterno', fr='$fr2', creapdf='pdf' WHERE orden='$busca' and estudio='$estudio'"); 

		}else {
		
			$Msj = $Msj . "<br>Lo siento! tu confirmacion no es correcta</br>";			    	
		}
	   }
		
	}else{

				$lUp  = mysql_query("DELETE FROM resul WHERE orden='$busca' AND estudio='$estudio'");
				
				$EleA = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' and tipo<>'e' and tipo<>'s' and tipo<>'z' and tipo<>'v' ORDER BY id");
				
				$Msj   = "Tus datos han sido validados y guardados con exito!";	
					   
				while($Ele=mysql_fetch_array($EleA)){
				
					$Rs=$Ele[id];
					
					$Resultado=$_REQUEST[$Rs];

					if($Ele[tipo]=="l"){
					
						$Campo='l';
					
					}elseif($Ele[tipo]=="d"){
					
						$Campo='d';
					
					}elseif($Ele[tipo]=="n"){
					
						$Campo='n';
					
						if($Resultado < $Ele[min]  OR $Resultado > $Ele[max]){
							$TFr=$TFr+1;
						}else{
							$TFr=$TFr+0;
						}
					
					
					}elseif($Ele[tipo]=="c"){
					
						$Campo='c';
					
					}else{
					
						$Campo='t';
					
					}

					$lUp = mysql_query("INSERT INTO resul (orden,estudio,elemento,$Campo) 
						   VALUES
						   ('$busca','$estudio','$Ele[id]','$Resultado')");
				}
				
				$Fecha = date("Y-m-d");
				$Hora  = date("H:i");
				
				if($TFr==0){
					$fr2='0';
				}else{
					$fr2='1';
				}	

                $Up = mysql_query("UPDATE otd SET tres='$Fecha $Hora', status='TERMINADA', capturo='$Usr', alterno='$alterno', fr='$fr2', creapdf='pdf' WHERE orden='$busca' and estudio='$estudio'");   
	}
       
  }

  $Tabla  = "ot";
  $Titulo = "Estudio por departamento";

  $EstA   = mysql_query("SELECT descripcion,proceso FROM est WHERE estudio='$estudio'");
  $Est    = mysql_fetch_array($EstA);

  $OtA    = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,ot.observaciones,inst.nombre,cli.sexo,cli.nombrec,cli.fechan,med.nombrec as nombremed,ot.institucion
      FROM ot,inst,cli,med 
      WHERE ot.cliente=cli.cliente AND ot.institucion = inst.institucion AND ot.medico=med.medico 
      AND  ot.orden='$busca' ");

  $Ot     = mysql_fetch_array($OtA);

  //$cSql   = "SELECT * FROM ot WHERE (orden= '$busca')";

  $lAg    = $Nombre<>$Cpo[nombre];

  $Fecha  = date("Y-m-d");

  require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF">
<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}

function elegir() {
 if (confirm('se borran los datos')) {
 alert('borrar');
 } else {
 alert('no se borra nada');
 }
 }

</script>

    <?php
	echo "<p align='center'><b>$Gfont <font size='+1'> $estudio - $Est[descripcion] </font></b></p>";

	echo "<table align='center' width='99%' border='0' cellspacing='0' cellpadding='0'>";
	echo "<tr>";
	echo "<td align='right' background='lib/ba2.jpg'>$Gfont <font size='-1' color='#C70039'><b>Datos Generales<b></font></td>";
	echo "</tr>";  
	echo "</table>";
	
	echo "<div align='left'>$Gfont <b>$Ot[institucion] - <font size='+1'>$busca &nbsp; &nbsp; &nbsp; <font size='+1'>".substr($Ot[nombrec],0,40)."</font></b></div>";
    echo "<div align='left'>$Gfont Fecha: &nbsp; $Ot[fecha] &nbsp; Hora : &nbsp; $Ot[hora] &nbsp; &nbsp; Fec/Ent : &nbsp; $Ot[fechae] </div>";
		
	echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";
	
	echo "<tr height='25' bgcolor='#cbe1fe'>";
	echo "<td align='center'>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Sexo</font></td>";
	echo "<td align='center'>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Edad</font></td>";
	echo "<td align='center'>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Medico</font></td>";		  
	echo "<td align='center'>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Servicio</font></td>";
	echo "</tr>";  
	
    echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
	if($Ot[sexo]=='F'){
		$sex='Femenino';
	}else{
		$sex='Masculino';
	}
	echo "<td align='center'>$Gfont <font size='-1'>$sex</font></td>";
	$anos=$Fecha-$Ot[fechan];
	echo "<td align='center'>$Gfont <font size='-1'>$anos A&ntilde;os</font></td>";
	echo "<td align='center'>$Gfont <font size='-1'>$Ot[medico] ".substr($Ot[nombremed],0,15)."</font></td>";		  
	echo "<td align='center'>$Gfont <font size='-1'>$Ot[servicio]</font></td>";
	echo "</tr>";  
	
	echo "<table align='center' width='90%'>";
	echo "<tr>";
	echo "<td width='7%'>";
	
	echo "<a class='pg' href='javascript:window.close()'>$Gfont regresar <br><img src='lib/regresa.jpg' border='0'></font></a>";
	  
	echo "</td>";
	echo "<td>";
	echo "<font face='verdana' size='2'><b>Diagnostico medico</b></font>";
	echo "<TEXTAREA NAME='Diagmedico' cols='40' rows='3' >$Ot[diagmedico]</TEXTAREA>";
	echo "</td>";
	echo "<td width='5%'></td>";
	echo "<td>";
	echo "<font face='verdana' size='2'><b>Observaciones</b></font>";
	echo "<TEXTAREA NAME='Observaciones' cols='40' rows='3' >$Ot[observaciones]</TEXTAREA>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	
	echo "<br>";
	
	echo "<table align='center' width='100%' border='0' cellspacing='0' cellpadding='0'>";
	echo "<tr>";
	echo "<td align='right'  background='lib/ba2.jpg'>$Gfont <font size='-1' color='#C70039'><b>Captura de Resultados<b></font></td>";
	echo "</tr>";  
	echo "</table>";

    $EleA   = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' ORDER BY id");
    
    $OtdA   = mysql_query("SELECT status,capturo,tres,usrvalida,fechavalida,impest,impres,fr,creapdf FROM otd WHERE estudio='$estudio' AND orden='$busca'");
    $Otd    = mysql_fetch_array($OtdA);


	if($Otd[capturo]<>''){
		
		if($Otd[usrvalida]==''){
			
			echo "<p align='center'><b>$Gfont <font size='-1' color='#C70039'>VALIDACION DE ESTUDIO</font></b></p>";
						
		}else{
			
			echo "<p align='center'><b>$Gfont <font size='-1' color='#C70039'>ACTUALIZACION DE CAPTURA</font></b></p>";
			
		}
	}

    echo "<div align='center'><b><font color='red'>$Msj</font></b></div>";

	if($alterno==0){				
       	echo "<div align='center'>$Gfont <font color='#330099'><b> Captura Standar: <img src='lib/slc.png'>";
    }else{
    	echo "<div align='center'>$Gfont <font color='#330099'><b> Captura Standar: <a class='pg' href='capturaresdiagvalidapdf.php?busca=$busca&estudio=$estudio&alterno=0'><img src='lib/iconfalse.gif'></b></a>";
    }   

	if($alterno==1){				
       	echo "$Gfont <font color='#330099'><b> Captura Alternativa: <img src='lib/slc.png'>";
    }else{
    	echo "$Gfont <font color='#330099'><b> Captura Alternativa: <a class='pg' href='capturaresdiagvalidapdf.php?busca=$busca&estudio=$estudio&alterno=1'><img src='lib/iconfalse.gif'></b></a>";
    }   

	if($alterno==2){				
       	echo "$Gfont <font color='#330099'><b> Captura Alternativa (2): <img src='lib/slc.png'></div><br>";
    }else{
    	echo "$Gfont <font color='#330099'><b> Captura Alternativa (2): <a class='pg' href='capturaresdiagvalidapdf.php?busca=$busca&estudio=$estudio&alterno=2'><img src='lib/iconfalse.gif'></b></a></div><br>";
    }   

    echo "<form name='form1' method='post' action='capturaresdiagvalidapdf.php?alterno=$alterno'>";

    echo "<table width='80%' align='center' border='0' cellpadding=0 cellspacing=0 bgcolor='#FFFFFF'>";


    while($Ele=mysql_fetch_array($EleA)){
    	
       $VlrA  = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[id]'");
   	   $Vlr   = mysql_fetch_array($VlrA);

       $Campo = $Ele[id];

   		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
  
		if($Otd[capturo]<>''){
  
			if($Vlr[n] < $Ele[min]  OR $Vlr[n] > $Ele[max]){
				$Fdo='FF0000';
				$Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>";
				$TFr=$TFr+1;
			}else{
				if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
				$Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
			}
		}else{
			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
			$Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>";
		}


		echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

		if($Ele[tipo]=="e"){

			$Alineacion=$Ele[alineacion];

			echo "<td align='center' colspan='4' style='background-color: #aeb5ee;color: #000;'><b><font size='2'>";
		  
			echo "$Ele[descripcion] &nbsp; ";

			echo "</b></font></td><td>";


		}elseif($Ele[tipo]=="s"){

			echo "<td align='center' colspan='4' style='background-color: #e8eeae;color: #000;'><b><font size='2'>";
		  
			echo "$Ele[descripcion] &nbsp; ";

			echo "</b></font></td><td>";

		}elseif($Ele[tipo]=="v"){

			echo "<td align='center' colspan='4' style='background-color: #FFFFFF;color: #FFFFFF;'>";
		  
			echo " &nbsp; ";

			echo "</td><td>";


		}elseif($Ele[tipo]=="z"){

		    echo "<tr height='21' style='background-color: #c2eeae ;color: #000;''>
			<td align='center'>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'><b>Elementos</b></td>
			<td align='center' colspan='2'>$Gfont<font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'> <b>Resultados</b></td>
			<td align='center'>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'><b>Valores de Referencia</b></td></tr>";


		}elseif($Ele[tipo]=="l"){

			echo "<td align='right'>$Gfont ";
		  
			echo "$Ele[descripcion] &nbsp; ";

			echo "</td>";

			echo "<td align='center'>$Gfont ";

		  if($Vlr[l]=='S'){
			$cLog="Positivo";
		  }elseif($Vlr[l]=='N'){
			$cLog="Negativo";
		  }
		 
		  echo "<SELECT name='$Campo'>";
		  echo "<option value='S'>Positivo</option>";
		  echo "<option value='N'>Negativo</option>";
		  echo "<option SELECTED value='$Vlr[l]'>$cLog</option>";
		  echo "</SELECT>";

		  echo "</td>";

		  echo "<td align='center'>$Gfont </td>";

		  echo "<td align='center'>$Gfont ".$Ele[vlogico]."</td>";
			
		}elseif($Ele[tipo]=="d"){

			echo "<td align='right'>$Gfont ";
		  
			echo "$Ele[descripcion] &nbsp; ";

			echo "</td>";

			echo "<td align='center' colspan='2'><input name='$Campo' value ='$Vlr[d]' type='text' size='11' ></td>";

		}elseif($Ele[tipo]=="n"){

				echo "<td align='right'>$Gfont ";
	  
				echo "$Ele[descripcion] &nbsp; ";

				echo "</td>";

				echo "<td align='center'><input name='$Campo' value ='$Vlr[n]' type='text' size=10> </td><td align='center'>$Gfont  $Ele[unidad]</td>";

				echo "<td align='center'>$Gfont ".number_format($Ele[min],'2')." - ".number_format($Ele[max],'2')."</td>";

		}elseif($Ele[tipo]=="c"){

			echo "<td align='right'>$Gfont ";
	  
			echo "$Ele[descripcion] &nbsp; ";

			echo "</td><td align='center'>$Gfont ";

			echo "<input name='$Campo' value ='$Vlr[c]' type='text' size='25'";

			echo "</td>";

			echo "<td align='center'>$Gfont </td>";

			echo "<td align='center'>$Gfont ".$Ele[vtexto]."</td>";

		}else{

			echo "<td align='right'>$Gfont ";
	  
			echo "$Ele[descripcion] &nbsp; ";

			echo "</td><td colspan='3' align='center'>$Gfont ";

			echo "<TEXTAREA NAME='$Campo' cols='70' rows='3' >$Vlr[t]</TEXTAREA></td>";

		}
			
		echo "</tr>";
		$nRng++;
	}
		
	echo "</table>";
	
	echo "<input type='hidden' name='estudio' value=$estudio>";	
	echo "<input type='hidden' name='busca' value=$busca>";
	echo "<input type='hidden' name='TFr' value=$TFr>";
	
	echo "<input type='hidden' name='op' value=rs>"; // Resultdos

	if($Otd[capturo]<>'' and $Otd[fr]=='1'){
		
		if($Otd[usrvalida]==''){
			
			echo "Para confirmar que tu captura es correcta, favor de poner tu clave de acceso($Usr)";
			
			echo "<input type='password' name='Confirmacion' value=''>";
			
		}else{
			
			echo "!!! Actualizacion de captura, favor de poner tu clave de acceso($Usr)!!!";
			
			echo "<input type='password' name='Confirmacion' value=''>";
			
		}
			
	}else{
		
		echo "!!! Actualizacion de captura, favor de poner tu clave de acceso($Usr)!!!";
		
		echo "<input type='password' name='Confirmacion' value=''>";
	}


    echo Botones2();
	
 	echo "<table align='center' width='95%' border='0' cellspacing='0' cellpadding='0'>";
	echo "<tr><td>$Gfont <b>Captura: </b>$Otd[capturo] - $Otd[tres]</font></td>"; 
	echo "<td>$Gfont <b>Valida: </b>$Otd[usrvalida] - $Otd[fechavalida]</font></td>"; 
	echo "<td>$Gfont <b>Imprime: </b>$Otd[impest] - $Otd[impres]</font></td></tr>"; 
	echo "</table>";
		
	echo "<table align='center' width='100%' border='0' cellspacing='0' cellpadding='0'>";
	echo "<tr>";
	echo "<td align='right' background='lib/ba2.jpg'>$Gfont <font size='-1' color='#C70039'><b>Arhivos complementarios<b></font></td>";
	echo "</tr>";  
	echo "</table>";

	echo "</form>";

    echo "<form enctype='multipart/form-data' action='capturaresdiag.php?busca=$busca&estudio=$estudio' method='POST'>";

    echo "<input type='hidden' name='submitted' value='true'>";		

    echo "<input class='content_txt' name='" . $upload_file_name ."' type='file'> &nbsp; ";

    echo "<input class='content_txt' type='submit' value='Subir archivo'>";
    echo "<input type='hidden' name='cId' value='$cId'> &nbsp; &nbsp; ";
    
    echo "</form>";
		
    echo "<div align='left'><a href=javascript:winuni('displayestudioslcd.php?busca=$busca')><img src='lib/desplegar.png'></a> &nbsp; &nbsp; &nbsp; ";   
	echo "<div>&nbsp; &nbsp; &nbsp; ";   
    $ImgA = mysql_query("SELECT * FROM estudiospdf WHERE id='$busca' and usrelim=''");
    while ($row = mysql_fetch_array($ImgA)) {  
        $Pdf = $row[archivo];  
        echo "<a href=javascript:winuni('enviafile2.php?busca=$Pdf')><img src='lib/Pdf.gif' title=$Pdf border='0'></a> &nbsp; &nbsp; ";                                
    } 
	
    echo "</div>";
	
	echo "<div>&nbsp; &nbsp; &nbsp; ";   
         
    $ImgA = mysql_query("SELECT * FROM estudiospdf WHERE id='$busca' and usrelim=''");
    while ($row = mysql_fetch_array($ImgA)) {  
        $Pdf = $row[archivo];  
        //echo "<a href=javascript:winuni('enviafile2.php?busca=$Pdf')><img src='lib/dele.png' title=Elimina_$Pdf border='0'></a> &nbsp; &nbsp; &nbsp;";                                
        //echo "<a class='pg' class='pg' href=javascript:wingral('borrar.php?archivo=$Pdf&id=$busca')><img src='lib/dele.png' title=Elimina_$Pdf border='0'></a> &nbsp; &nbsp; ";	                
        echo "<a href='capturaresdiag.php?archivo=$Pdf&id=$busca&busca=$busca&estudio=$estudio' onclick='return confirm(\"Desea eliminar el archivo?\")'>&nbsp;<img src='lib/dele.png' title=Elimina_$Pdf border='0'></a> &nbsp; &nbsp;";
	} 
    echo "</div>";

    echo "</td>";

    echo "</tr>";
		
	echo "<table align='center' width='100%' border='0' cellspacing='0' cellpadding='0'>";
	echo "<tr>";
	echo "<td align='right' background='lib/ba2.jpg'>$Gfont <font size='-1' color='#C70039'><b>Historial clinico<b></font></td>";
	echo "</tr>";  
	echo "</table>";
	
	echo "<table align='center' width='80%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr height='25' background='lib/bartit.gif'>";
    echo "<th>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>No.orden</th>";
    echo "<th>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Fecha</font></th>";
    echo "<th>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Estudio</font></th>";
    echo "<th>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Descripcion</font></th>";
    echo "<th>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>Resultado</font></th>";
    echo "</tr>";

    $OtdA = mysql_query("SELECT ot.orden, ot.fecha, otd.estudio, est.descripcion, est.depto,otd.alterno, otd.capturo, otd.usrvalida, otd.fr, otd.creapdf
			FROM ot,otd LEFT JOIN est ON otd.estudio=est.estudio
			WHERE ot.cliente = '$Ot[cliente]' AND ot.orden=otd.orden");

	while($reg=mysql_fetch_array($OtdA)){

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            $clnk=strtolower($reg[estudio]);

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            //echo "<td align='center'><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
            echo "<td>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>$reg[fecha]</font></td>";
            echo "<td>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>$reg[orden]</font></td>";
            echo "<td>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>$reg[estudio]</font></td>";
            echo "<td>$Gfont <font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'>$reg[descripcion]</font></td>";

			if($reg[capturo]<>'' and $reg[fr]=='0'){
				if($reg[depto] <> 2 ){
					if($reg[creapdf]=='pdf'){

						 echo "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&alterno=$reg[alterno]')><font size='1'>PDF</font></a></td>";
				 	}else{

						 echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$reg[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
					}

				}else{
					 echo "<td align='center'><a class='pg' href=javascript:wingral('pdfradiologia.php?busca=$reg[orden]&Estudio=$reg[estudio]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
				}
			}else{
				if($reg[capturo]<>'' and $reg[usrvalida]<>''){
					if($reg[depto] <> 2 ){
						if($reg[creapdf]=='pdf'){

							echo "<td align='center'><a href=javascript:wingral('resultapdf.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&alterno=$reg[alterno]')><font size='1'>PDF</font></a></td>";
				 		}else{

							echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$reg[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
						}
					}else{
						 echo "<td align='center'><a class='pg' href=javascript:wingral('pdfradiologia.php?busca=$reg[orden]&Estudio=$reg[estudio]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
					}
				}else{
					echo "<td align='center'>-</td>";
				}
			}
            echo "</tr>";
            $nRng++;

	}//fin while

	echo "</table> <br>";

    echo "</td>";

    echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

mysql_close();

?>
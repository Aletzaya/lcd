<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link    = conectarse();

  $tamPag  = 15;

  $op      = $_REQUEST[op];

  $Usr     = $check[uname];
  $Depto   = $_REQUEST[Depto];
  $busca   = $_REQUEST[busca];
  $pagina  = $_REQUEST[pagina];
  $cId       = $_REQUEST[busca]; 

  $estudio = $_REQUEST[estudio];  
  $archivo = $_REQUEST[archivo];  
  $alterno = $_REQUEST[alterno];  
  $lBd       = false;

if($alterno=='0'){
	$tabla='ele';
}else{
	if($alterno=='1'){
		$tabla='elealt';
	}else{
		$tabla='elealt2';
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

        /*
          $fp = fopen($path . $my_uploader->file['name'], "r");

          while(!feof($fp)) {

          $line = fgets($fp, 255);

          echo $line;

          }

          if ($fp) { fclose($fp); }

          }
         */
    }
}

//  if($op=='elim'){ 
	 
//		unlink('estudios/$archivo');
//  }
		
  if($op=="gu"){  //Guarda el Movto de Notas

     header("Location: estdeptores.php?pagina=$pagina&Depto=$Depto");

  }elseif($op=='rs'){  //Registra resultados

  		$lUp  = mysql_query("DELETE FROM resul WHERE orden='$busca' AND estudio='$estudio'");

      $EleA = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' ORDER BY id");
      
      $lBd  = true;
      
      $Msj = "";

	   while($Ele=mysql_fetch_array($EleA)){

			  $Rs=$Ele[id];

           $Resultado=$_REQUEST[$Rs];

			  //$Resultado=$$Rs;

   	          if($Ele[tipo]=="l"){
   	          	
   	          	$Campo='l';

   	          }elseif($Ele[tipo]=="d"){
   	          
   	            $Campo='d';

   	          }elseif($Ele[tipo]=="n"){
   	          	
   	          	$Campo='n';
   	          	
   	          	if($Ele[min] <> 0 OR $Ele[max] <> 0 ){
			
							if($Resultado < $Ele[min]  OR $Resultado > $Ele[max]){
								
								$Msj= $Msj."<div align='center'>Rebasas los limites en $Ele[descripcion] $Resultado, favor de verificar!!!</div>";
								$lBd = false;
								
							}   	          		
   	          		
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
       
       if($lBd or $_REQUEST[Confirmacion]<>''){
       	
       	 $Pasw  = $_REQUEST[Confirmacion];
       	 $AccA  = mysql_query("SELECT uname FROM authuser 
       	          WHERE uname='$Usr' AND passwd=md5('$Pasw')");
       	          
       	 $Acc   = mysql_fetch_array($AccA);         
       	          
		    if($Acc[uname] == $Usr  or $lBd ){       	          

				$Msj   = "Tus datos han sido guardados con exito!";	

             $OtdA  = mysql_query("SELECT tres,lugar FROM otd WHERE orden='$busca' AND estudio='$estudio' LIMIT 1");
             $Otd   = mysql_fetch_array($OtdA);
              
             if(substr($Otd[tres],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;

                $Fecha = date("Y-m-d");
                $Hora  = date("H:i");

                if($Otd[lugar] <= '4' OR $Otd[lugar] == '' ){
          
                   $Up = mysql_query("UPDATE otd set tres = '$Fecha $Hora', lugar='4', status='TERMINADA',capturo='$Usr',alterno='$alterno' 
	                      WHERE orden='$busca' and estudio='$estudio'"); 

                }else{

                   $Up = mysql_query("UPDATE otd set tres = '$Fecha $Hora', status='TERMINADA',capturo='$Usr',alterno='$alterno'
	                      WHERE orden='$busca' and estudio='$estudio'");   

                }	
		
		       }else{

                $Up = mysql_query("UPDATE otd SET status='TERMINADA', capturo='$Usr',alterno='$alterno'
	                   WHERE orden='$busca' and estudio='$estudio'");   
		 
		       }  
			   
				  $NumA  = mysql_query("SELECT otd.estudio 
					   FROM otd 
					   WHERE otd.orden='$busca' AND otd.capturo=''");
			
				 if(mysql_num_rows($NumA)==0){
						  $lUp = mysql_query("UPDATE ot SET captura='Si' WHERE orden='$busca'");
				 } 
		 
		    }else {

				$Msj = $Msj . "<br>Lo siento! tu confirmacion no es correcta</br>";			    	
		    	
		    }	 
       }
   }

  $Tabla  = "ot";
  $Titulo = "Estudio por departamento";

  $EstA   = mysql_query("SELECT descripcion,proceso FROM est WHERE estudio='$estudio'");
  $Est    = mysql_fetch_array($EstA);

  $OtA    = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,
            ot.observaciones,inst.nombre,cli.sexo,cli.nombrec,cli.fechan,med.nombrec as nombremed 
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
    
    echo "<table align='center' border='2' width='50%' cellspacing= '2' cellpading = '0'>";
    echo "<tr><td background='lib/bar.jpg' align='center'>$Gfont  $estudio - $Est[descripcion] ";
    echo "</td></tr></table>$Gfont";
    
    echo "<div align='center'>No.Orden : $busca &nbsp; Fecha: &nbsp; $Ot[fecha] &nbsp; Hora : &nbsp; $Ot[hora]";
    echo "Fec/Ent : &nbsp; $Ot[fechae] &nbsp; Tpo/servicio : $Ot[servicio]  </div>";
   
    
    echo "<p align='center'>Paciente: $Ot[cliente] ".substr($Ot[nombrec],0,17)."&nbsp; &nbsp; &nbsp;&nbsp;";
    echo "Inst.: $Ot[nombre] &nbsp; &nbsp; Med.: $Ot[medico] ".substr($Ot[nombremed],0,15);
    echo "</p>";
    echo "<div align='center'>";
    echo "Sexo: $Ot[sexo] &nbsp; &nbsp;";
    echo "Edad: ";
    echo $Fecha-$Ot[fechan]; 
    echo "A&ntilde;os </div>";

 echo "<table align='center' width='100%'>";
   echo "<tr>";
   echo "<td width='5%'>";
     
     echo "<a class='pg' href='javascript:window.close()'>$Gfont regresar <br><img src='lib/regresa.jpg' border='0'></font></a>";
          
   echo "</td>";
   echo "<td>";
     echo "<font face='verdana' color='#0066FF' size='2' >Diagnostico medico</font>";
     echo "<TEXTAREA NAME='Diagmedico' cols='40' rows='3' >$Ot[diagmedico]</TEXTAREA>";
   echo "</td>";
   echo "<td width='5%'></td>";
   echo "<td>";
   echo "<font face='verdana' color='#0066FF' size='2' >Observaciones</font>";
   echo "<TEXTAREA NAME='Observaciones' cols='40' rows='3' >$Ot[observaciones]</TEXTAREA>";
   echo "</td>";
   echo "</tr>";
echo "</table>";

echo "<br><br>";

echo "<table align='center' width='50%' border='2' cellspacing= '3' cellpading = '0'><tr><td background='lib/bar.jpg' align='center'><font size='2' face='verdana' color='#6D6D6D' >Pre-analiticos</font></td></tr></table>";

$OtpreA = mysql_query("SELECT cue.pregunta,otpre.nota,cue.id,cue.tipo 
          FROM otpre,cue 
          WHERE otpre.orden='$busca' AND otpre.estudio='$estudio' AND cue.id=otpre.pregunta");

echo "<form name='form3' method='get' action='capturares.php'>";

    echo "<table align='center' width='100%'>";
    echo "<tr><td align='right'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $Sec=1;
    while($registro=mysql_fetch_array($OtpreA)){
 		echo "<tr><td align='right'>$Gfont $registro[0] $Gfon </td><td>&nbsp;</td>";
        echo "<td>";
        $Campo="Nota".ltrim($Sec);
     	if($registro[3]=="Si/No"){
   	            echo "<SELECT name='$Campo'>";
                echo "<option value='Si'>Si</option>";
                echo "<option value='No'>No</option>";
                echo "<option SELECTED>$registro[1]</option>";
                echo "</SELECT>";
        }elseif($registro[3]=="Fecha"){
   	            echo "<input name='$Campo' value ='$registro[1]' type='text' size='8' >";
        }else{
                echo "<TEXTAREA NAME='$Campo' cols='50' rows='3' >$registro[1]</TEXTAREA>";
        }
   	    $Sec=$Sec+1;
   	    echo "</td></tr>";
    }
    echo "</table>";
    echo "<input type='hidden' name='estudio' value=$estudio>";
    echo "<input type='hidden' name='Depto' value=$Depto>";
    echo "<input type='hidden' name='op' value=pr>";

    //echo Botones();

    echo "</form><br>";



    //echo "<table align='center' width='50%' border='2' cellspacing= '3' cellpading = '0'><tr><td background='lib/bar.jpg' align='center'><font size='2' face='verdana' color='#6D6D6D'>Resultados</font></td></tr></table>";

    //SELECT resul.c,resul.d,resul.n,resul.l,resul.t,ele.tipo FROM resul,ele WHERE resul.orden='254' AND resul.estudio='BH' AND resul.elemento=ele.id;

    $EleA   = mysql_query("SELECT * FROM $tabla WHERE estudio='$estudio' ORDER BY id");
    
    $OtdA   = mysql_query("SELECT status FROM otd WHERE estudio='$estudio' AND orden='$busca'");
    $Otd    = mysql_fetch_array($OtdA);


    echo "<div align='center'><b><font color='red'>$Msj</font></b></div>";

	if($alterno==0){				
       	echo "<div align='center'>$Gfont <font color='#330099'><b> Captura Standar: <img src='lib/slc.png'>";
    }else{
    	echo "<div align='center'>$Gfont <font color='#330099'><b> Captura Standar: <a class='pg' href='capturaresdiag.php?busca=$busca&estudio=$estudio&alterno=0'><img src='lib/iconfalse.gif'></b></a>";
    }   

	if($alterno==1){				
       	echo "$Gfont <font color='#330099'><b> Captura Alternativa: <img src='lib/slc.png'>";
    }else{
    	echo "$Gfont <font color='#330099'><b> Captura Alternativa: <a class='pg' href='capturaresdiag.php?busca=$busca&estudio=$estudio&alterno=1'><img src='lib/iconfalse.gif'></b></a>";
    }   

	if($alterno==2){				
       	echo "$Gfont <font color='#330099'><b> Captura Alternativa (2): <img src='lib/slc.png'></div><br>";
    }else{
    	echo "$Gfont <font color='#330099'><b> Captura Alternativa (2): <a class='pg' href='capturaresdiag.php?busca=$busca&estudio=$estudio&alterno=2'><img src='lib/iconfalse.gif'></b></a></div><br>";
    }   

    echo "<form name='form1' method='post' action='capturaresdiag.php?alterno=$alterno'>";

    echo "<table width='90%' align='center' border='0' cellpadding=0 cellspacing=1 bgcolor='#FFFFFF'>";
			
    echo "<tr height='21' background='lib/bar.jpg'><td align='right'>$Gfont <b>Elementos</b></td><td align='center'>$Gfont <b>Valores</b></td><td>$Gfont <b>Min</b></td><td>$Gfont <b>Max</b></td></tr>";

    while($Ele=mysql_fetch_array($EleA)){
    	
     	   $VlrA  = mysql_query("SELECT * FROM resul WHERE orden='$busca' AND estudio='$estudio' AND elemento='$Ele[id]'");
   	   $Vlr   = mysql_fetch_array($VlrA);

         $Campo = $Ele[id];
   	  
   	  
         if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo='FFFFFF';}    //El resto de la division;

         echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
   	     	  
 		   echo "<td align='right'>$Gfont ";
 		  
   	     echo "$Ele[descripcion]";

         echo "</td><td>$Gfont ";

 	         if($Ele[tipo]=="l"){

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
                
   	      }elseif($Ele[tipo]=="d"){
   	            echo "<input name='$Campo' value ='$Vlr[d]' type='text' size='11' >";
   	      }elseif($Ele[tipo]=="n"){
   	            echo "<input name='$Campo' value ='$Vlr[n]' type='text' size=$Ele[longitud]>";
   	      }elseif($Ele[tipo]=="c"){
   	            echo "<input name='$Campo' value ='$Vlr[c]' type='text' size=$Ele[longitud]>";
   	      }else{
                echo "<TEXTAREA NAME='$Campo' cols='50' rows='3' >$Vlr[t]</TEXTAREA>";
            }

      	echo "</td><td>$Gfont ";

      	   if($Ele[tipo]==n){
  	            echo "".number_format($Ele[min],'4');       	   	      	   	
      		}

      	echo "</td><td>$Gfont ";

      	   if($Ele[tipo]==n){
  	            echo number_format($Ele[max],'4');       	   	      	   	      	
  	         }
  	            
      	echo "</td></tr>";
      	
      	$nRng++;
      	
        }

        echo "</table>";

        echo "<input type='hidden' name='estudio' value=$estudio>";

        echo "<input type='hidden' name='Depto' value=$Depto>";

        echo "<input type='hidden' name='op' value=rs>"; // Resultdos
        
		  if($Msj <> '' AND !$lBd){
		  	
		  	  echo "Para confirmar que tu captura es correcta, favor de poner tu clave de acceso($Usr)";
		  	  
		  	  echo "<input type='password' name='Confirmacion' value=''>";
		  	
		  	}        

        echo Botones();


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

	echo "<div align='center'><b>Historial clinico</b></div>";

	echo "<table align='center' width='95%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr height='25' background='lib/bartit.gif'>";
    echo "<th>$Gfont No.orden</th>";
    echo "<th>$Gfont Fecha</font></th>";
    echo "<th>$Gfont Estudio</font></th>";
    echo "<th>$Gfont Descripcion</font></th>";
    echo "<th>$Gfont Resultado</font></th>";
    echo "</tr>";

    $OtdA = mysql_query("SELECT ot.orden, ot.fecha,otd.estudio,est.descripcion,est.depto,otd.alterno
			FROM ot,otd LEFT JOIN est ON otd.estudio=est.estudio
			WHERE ot.cliente = '$Ot[cliente]' AND ot.orden=otd.orden");

	while($reg=mysql_fetch_array($OtdA)){

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            $clnk=strtolower($reg[estudio]);

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            //echo "<td align='center'><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
            echo "<td>$Gfont $reg[fecha]</font></td>";
            echo "<td>$Gfont $reg[orden]</font></td>";
            echo "<td>$Gfont $reg[estudio]</font></td>";
            echo "<td>$Gfont $reg[descripcion]</font></td>";
		    if($reg[depto] <> 2 ){
                 echo "<td align='center'><a class='pg' href=javascript:wingral('estdeptoimp.php?clnk=$clnk&Orden=$reg[orden]&Estudio=$reg[estudio]&Depto=TERMINADA&op=im&reimp=1&alterno=$reg[alterno]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
			}else{
                 echo "<td align='center'><a class='pg' href=javascript:wingral('pdfradiologia.php?busca=$reg[orden]&Estudio=$reg[estudio]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
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
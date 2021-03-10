<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();
$busca     = $_REQUEST[busca];

$Usr      =   $check['uname'];
$cId       = $_REQUEST[busca]; 

$estudio  =   $_REQUEST[estudio];
$archivo = $_REQUEST[archivo];  
$lBd       = false;

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
    $my_uploader->max_filesize(15000000);

    // OPCIONAL: Si se suben imagenes puedes poner el ancho y el alto en pixeles 
    $my_uploader->max_image_size(3500, 3500); // max_image_size($width, $height)
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

#Variables comunes;
$Titulo    = " Detalle de estudios";

require ("config.php");							   //Parametros de colores;

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>";
echo "<html>";
echo "<head>";

echo "<title>$Titulo</title>";

//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8_spanish_ci" />';    //Con esto salen las ñ y acentos;
echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";

echo '<meta content="text/html; charset=utf-8" http-equiv="content-type">';    

echo "<link href='lib/estilos_clap.css' rel='stylesheet' type='text/css'>";

echo "<meta http-equiv='refresh' content='800;url=http:/lcd/menu.php' />";

echo "</head>";

echo "<body bgcolor='#EFEFEF'>";    
        
echo "<br><table width='97%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
       
echo "<tr><td align='left'  bgcolor='#3300CC' class='titulo'><font color='#FFFFCC'><b> &nbsp; Estudios capturados </b></td></tr>";     

echo "<form enctype='multipart/form-data' action='displayestudioslcdimg.php?busca=$busca&estudio=$estudio' method='POST'>";

echo "<input type='hidden' name='submitted' value='true'>";     

echo "<input class='content_txt' name='" . $upload_file_name ."' type='file'> &nbsp; ";

echo "<input class='content_txt' type='submit' value='Subir archivo'>";
echo "<input type='hidden' name='cId' value='$cId'> &nbsp; &nbsp; ";

echo "</form>";
        
        
$ImgA = mysql_query("SELECT archivo,idnvo FROM estudiospdf WHERE id='$busca' and usrelim=''");
while ($row = mysql_fetch_array($ImgA)) {   
     $Pos   = strrpos($row[archivo],".");
     $cExt  = strtoupper(substr($row[archivo],$Pos+1,4));                             
     $foto  = $row[archivo];        
     if($cExt == 'PDF' ){                
      //  echo "<tr><td align='center'><a href=javascript:winuni('enviafile2.php?busca=$row[archivo]')><img src='lib/Pdf.gif' title='Estudios' border='0'></a></td></tr>";     
        echo "<tr><td align='center'><embed src='estudios/$foto' type='application/pdf' width='100%' height='1300px' /></td></tr>";   

     }elseif($cExt == 'DOCX' ){                                 
              
        echo "<tr><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=http://lcd-net.dyndns.org/lcd/estudios/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>";   

    }elseif($cExt == 'XLSX' ){                                 
              
        echo "<tr><td align='center'><iframe src='//view.officeapps.live.com/op/embed.aspx?src=http://lcd-net.dyndns.org/lcd/estudios/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>"; 

    }elseif($cExt == 'DCM' ){  

        echo "<a href='http://lcd-net.dyndns.org/lcd/estudios/$foto' style='width:100%; height:50%; border: none;min-height:500px;')><img src='lib/Pdf.gif' title=$Pdf border='0'></a> &nbsp; &nbsp; ";                

       // echo "<tr><td align='center'><iframe src='http://lcd-net.dyndns.org/lcd/estudios/$foto' style='width:100%; height:50%; border: none;min-height:500px;'></iframe></td></tr>";          

     }else{

        echo "<tr><td align='center'><IMG SRC='estudios/$foto' border='0' width='1200'></td></tr>";   
     } 
  
     echo "<tr class='content_txt'><td bgcolor='#2980b9' align='left' colspan='2'>".ucfirst(strtolower($row[archivo]))."</td><td align='center'><a class='Seleccionar' href='displayestudioslcdimg.php?archivo=$foto&id=$busca&busca=$busca&estudio=$estudio' onclick='return confirm(\"Desea eliminar el archivo?\")'><font color='blue'><img src='lib/dele.png' title=Elimina_$Pdf border='0'> - Eliminar</font></a></td></tr>";
     echo "<tr><td align='center'> &nbsp; </td></tr>";
}    
                    
echo "</table><br>";

        
echo "</td></tr>";

echo "</table>";                                               
                                           
echo "</body>";
    
echo "</html>";  
  
  

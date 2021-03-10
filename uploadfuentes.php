<?php

session_start();	
		
$Up=$_REQUEST[Up];


require("fileupload-class.php");

//$path = "informes/";
$path = "";

?>

<html>

<head><title>Subir programas</title></head>

<body>

<p align='center'><font color="#0066FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Subir archivos al servidor</b></font></p>


<?php


//require ("lib/config.php");

// es el nombre del campo archivo en el formulario

$upload_file_name = "userfile";

// modo ACCEPT  - si se quiere aceptar solo cierto tipo de archivo.

//los posibles son:

//

// OPCIONES INCLUDE:

//  text/plain

//  image/gif

//  image/jpeg

//  image/png



	// Acepta unicamente GIFS

	#$acceptable_file_types = "image/gifs";



	// Acepta gifs y jpg`s

	#$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg";



	// Acepta todo

   $acceptable_file_types = "";



// Si no se le da una extension pone por default: ".jpg" or ".txt"



	$default_extension = "";



// MODO: Si se intenta subir un archivo con el mismo nombre a:

// $path directory

//

// HAY OPCIONES:

//   1 = modo de sobreescritura

//   2 = crea un nuevo archivo con extension incremental

//   3 = no hace nada si existe (mayor proteccion)



	$mode = 1;





#--------------------------------#

# PHP

#--------------------------------#

	if (isset($_REQUEST['submitted'])) {





		// Crea un nueva instancia de clase

		$my_uploader = new uploader($_POST['language']);



		// OPCIONAL: Tamano maxino de archivos en bytes

		//$my_uploader->max_filesize(150000000);



		// OPCIONAL: Si se suben imagenes puedes poner el ancho y el alto en pixeles

		$my_uploader->max_image_size(800, 800); // max_image_size($width, $height)



		// Sube el archivo


		if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {

			$my_uploader->save_file($path, $mode);

		}



		if ($my_uploader->error) {

			echo $my_uploader->error . "<br><br>\n";



		} else {

			// Si subio bien

			echo "<font color='#0000FF' size='2' face='Verdana, Arial, Helvetica, sans-serif'> procedimiento exitoso! </font><br></p>";

			// Imprime el contenido del array (donde se almacenan los datos del archivo)...

			//print_r($my_uploader->file);

			// ...o imprime el archivo

			if(stristr($my_uploader->file['type'], "image")) {
				echo "<img src=\"" . $path . $my_uploader->file['name'] . "\" border=\"0\" alt=\"\">";

			} else {

				$fp = fopen($path . $my_uploader->file['name'], "r");

				while(!feof($fp)) {

					$line = fgets($fp, 255);

					echo $line;              //Despliega lo k suben en la pantalla

				}

				if ($fp) { fclose($fp); }

			}

 		}

 	}









#--------------------------------#

# HTML FORM

#--------------------------------#

?>

	<form enctype="multipart/form-data" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">

	<input type="hidden" name="submitted" value="true">

		

		<font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Archivo: <? printf($my_uploader->file['name']);?></font><br>

		<input name="<?= $upload_file_name; ?>" type="file">

		<br><br>
		
        <?php
				// Esto lo comente pk crea mensajes raros y lo paso al inicio del program
            //session_start();			

            $_SESSION['FILE'] = $my_uploader->file['name'];

       ?>

		<input type="submit" value="Subir archivo">

	</form>

	<hr>



<?php

 /**

	if (isset($acceptable_file_types) && trim($acceptable_file_types)) {

		print("<font color='#0000FF' size='2' face='Verdana, Arial, Helvetica, sans-serif'> Solo acepta fotografias con formato: </font><b>" . str_replace("|", " or ", $acceptable_file_types) . "</b> \n");

	}

**/

?>

<p>&nbsp;</p>

<div align="center"><a class='pg' href="javascript:close()">Cerrar ventana</a></div>

</body>

</html>
<?php

$orden	=	$_REQUEST[orden];

$uploaddir = '/var/www/lcd/estudios/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
	echo "Carpeta temporal: " . $_FILES['userfile']['tmp_name'];

echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "El archivo es válido y fue cargado exitosamente.\n";
		echo "Arhivo: " . $_FILES['userfile']['name'];
		$lUp  = mysql_query("UPDATE estudiospdf SET orden = $orden, archivo=". $_FILES['userfile']['name']."
     	WHERE orden=$orden");

} else {
    echo "¡Posible ataque de carga de archivos!\n";
}

echo 'Aquí hay más información de depurado:';
echo "print_r($_FILES)";

echo "print '</pre>'";


?>
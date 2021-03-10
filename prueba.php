<?php
/* Imprimir con tamaño de papal personalizado printer.php,v 1.0 06/06/2004 22:13:04 J.Escobar $ */

$nombre = "Juan Perez";
$direccion = "morelos # 35, TEXCOCO";
$desc = " Las mangas del chaleco";
$precio = "10.00 MX";
$producto = "123456789-XL";

    /* pongo el texto en la impresora */
//$text  = "\tORDEN NUMERO\n\r";  // Se pueden usar tabulaciones
//$text .= "Recibimos esta orden en: \n\r ".date("l, M dS Y")." a las \n\r".date("H:i:s")."\n\r";
$text .= "De: $nombre \n\r";
$text .= "Direccion: $direccion \n\r";
$text .= "EL contenido es el siguiente: \n\r";

//inicalizo la impresora con el tipo de driver de tectado .
$impresora = "Datamax E-4203";

//Inicio la conexion a la impresora
$handle = printer_open($impresora);

//Seteo el formato y el tamaño de papel.
    printer_set_option($handle, PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM );
    printer_set_option($handle, PRINTER_PAPER_LENGTH, 40);
    printer_set_option($handle, PRINTER_PAPER_WIDTH, 100);

//Imprimo en la impresora en forma bruta
    printer_write($handle, $text);
    printer_close($handle);
?>
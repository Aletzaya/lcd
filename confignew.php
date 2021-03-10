<?php

// PARAMENTROS DEL SISTEMA EN COLOR Y FONDOS

$Gcia       = "Laboratorio Clinico Duran";
$Gdir       = "Av.Fray Pedro de Gante No. Col Centro Texcoco Edo.de Mex.";

//$Gletra     = '#0000FF';       // Color de la letra dentro del grid
$Gletra     = '#6D6D6D';       // Color de la letra dentro del grid
//$Gletra     = '#0066FF';       // Color de la letra dentro del grid
$Gfdogrid   = "#CCCCFF";       // Fondo del Grid
$Gbarra     = "#E3DBFD";       // Color de la barra de movto dentro del grid
$GfdoTitulo = '#6633FF';       //Fondo del titulo del programa Sup.izquierdo
$GbarraSup  = '#0000FF';
$GrallaInf  = "#F59B0E";       // Color de la ralla inferiot debajo de la paginacion
$GbarTit    = '#6633FF';       //Fondo de los encabezados del grid  de los Titulos
$Gfdocuainf = "#C0CBB1";       //Fondo del cuadro Inferior

$Gfont="<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color=$Gletra> &nbsp;";
$Gfon=" &nbsp; </font>";

    echo "<style type='text/css'>";

    echo "a.ord:link { ";

    echo "     font-family: verdana, Arial, Helvetica, sans-serif;";

    echo "     font-size: 11px;";

    echo "    color: #767573;";
    //echo "    color: #767573;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.ord:visited {";

    echo "     font-family: verdana, Arial, Helvetica, sans-serif;";

    echo "     font-size: 11px;";

    echo "    color: #767573;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.ord:hover {";

    echo "     font-family: verdana, Arial, Helvetica, sans-serif;";

    echo "     font-size: 11px;";

    echo "    color: #C0C0C0;";

    echo "    text-decoration: none;";

    echo "}";

    echo "</style>";

    //Classe para la paginacion inferior
    echo "<style type='text/css'>";

    echo "a.pg:link { ";

    echo "    color: $Gletra;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.pg:visited {";

    echo "    color: $Gletra;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.pg:hover {";

    echo "    color: #419E43;";

    echo "}";

    echo "</style>";

    echo "<script language='JavaScript1.2'>";

    echo "function wingral(url){";
    echo "window.open(url,'wingeneral','status=no,tollbar=yes,scrollbars=yes,menubar=no,width=1000,height=600,left=10,top=50')";
    echo "}";

    echo "function winuni(url){";
    echo "window.open(url,'filtros','status=no,tollbar=yes,scrollbars=yes,menubar=no,width=750,height=600,left=100,top=80')";
    echo "}";

    echo "function confirmar(mensaje,url){";
    echo "if(confirm(mensaje)){document.location.href = url;}";
    echo "}";

    echo "</script>";    

?>
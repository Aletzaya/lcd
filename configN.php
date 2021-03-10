<?php

// PARAMENTROS DEL SISTEMA EN COLOR Y FONDOS

$Gcia       = "Laboratorio Clinico Duran";
$Gdir       = "Av.Fray Pedro de Gante No. Col Centro Texcoco Edo.de Mex.";

//$Gletra     = '#0000FF';       // Color de la letra dentro del grid
$Gletra     = '#6D6D6D';       // Color de la letra dentro del grid
$Gfdogrid   = "#e1eec3";       // Fondo del Grid
$Gfdogrid2   = "#F5F5DC";       // Fondo del Grid
$Gfdogrid3   = "#CCCCFF";       // Fondo del Grid
$Gbarra     = "#CEDADD";       // Color de la barra de movto dentro del grid
//$GfdoTitulo = '#6633FF';       //Fondo del titulo del programa Sup.izquierdo
$GfdoTitulo = '#003399';       //Fondo del titulo del programa Sup.izquierdo
$GbarraSup  = '#0000FF';
$GrallaInf  = "#F59B0E";       // Color de la ralla inferiot debajo de la paginacion
$GbarTit    = '#6633FF';       //Fondo de los encabezados del grid  de los Titulos
$Gfdocuainf = "#C0CBB1";       //Fondo del cuadro Inferior
$InputCol   = "#aad9aa";		//Input color;

$Gfont      = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color=$Gletra> &nbsp;";

$MargenIzq  = 20;	   //Margen Izquierdo;
$MargenAlt  = 5;		//Margen de la altura Top; 

$Gfon = " &nbsp; </font>";

    echo "<style type='text/css'>";

    echo "a.ord:link { ";

    echo "    color: #003399;";

    echo "    font-size: 11px;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.ord:visited {";

    echo "    color: #003399;";

    echo "    font-size: 11px;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.ord:hover {";

    echo "    color: #ffffff;";

    echo "    font-size: 11px;";

    echo "    text-decoration: underline;";

    echo "}";

    echo "a.pg:link { ";

    echo "    color: $Gletra;";

    echo "    font-size: 11px;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.pg:visited {";

    echo "    color: $Gletra;";
    
    echo "     font-size: 11px;";

    echo "    text-decoration: none;";

    echo "}";

    echo "a.pg:hover {";

    echo "    color: #003399;";

    echo "     font-size: 11px;";

    echo "}";


    echo "a.Seleccionar { font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: normal; color: #6D8693; text-decoration: none}";
    echo "a.Seleccionar:hover { font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: normal; color: #006633}";
    
    echo ".Botones {font-family: Tahoma, Geneva, sans-serif;font-size: 12px;color: #69b747;text-decoration: none;}";   

    echo ".Input {font-family: Tahoma, Geneva, sans-serif;font-size: 12px;color: #99999;text-decoration: none;}";   

    echo ".textos {font-family: Tahoma, Geneva, sans-serif;font-size: 12px;color: #003c72text-decoration: none;}";    

    echo ".cuadro_rojo {font-family: Tahoma, Geneva, sans-serif;font-size: 11px;font-weight: bold;color: #FFF;text-decoration: none;background-color: #F63;height: 14px;width: 14px;}";
    echo ".cuadro_rojo:hover {font-family: Tahoma, Geneva, sans-serif;font-size: 11px;font-weight: bold;color: #FFF;text-decoration: none;background-color:#CCC;height: 14px;width: 14px;}";    
    
    echo "</style>";
    
    echo "<script language='JavaScript1.2'>";

    echo "function wingral(url){";
    echo "window.open(url,'wingeneral','status=no,tollbar=yes,scrollbars=yes,menubar=no,width=1000,height=600,left=10,top=50')";
    echo "}";

    echo "function winuni(url){";
    echo "window.open(url,'filtros','status=no,tollbar=yes,scrollbars=yes,menubar=no,width=750,height=600,left=100,top=80')";
    echo "}";
	
    echo "function winuni2(url){";
    echo "window.open(url,'filtros','status=no,tollbar=yes,scrollbars=yes,menubar=no,width=750,height=400,left=100,top=80')";
    echo "}";

    echo "function winuni3(url){";
    echo "window.open(url,'filtros','status=no,tollbar=yes,scrollbars=yes,menubar=no,width=1000,height=600,left=100,top=80')";
    echo "}";

    echo "function confirmar(mensaje,url){";
    echo "if(confirm(mensaje)){document.location.href = url;}";
    echo "}";

    echo "</script>";    

    

?>
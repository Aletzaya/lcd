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

echo "</head>";

echo "<body bgcolor='#EFEFEF'>";    
        
echo "<br><table width='97%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
       
echo "<tr><td align='left'  bgcolor='#3300CC' class='titulo'><font color='#FFFFCC'><b> &nbsp; Estudios capturados </b></td></tr>";     
        
        
$ImgA = mysql_query("SELECT archivo,idnvo FROM estudiospdf WHERE id='$busca' and usrelim=''");
while ($row = mysql_fetch_array($ImgA)) {   
     $Pos   = strrpos($row[archivo],".");
     $cExt  = strtoupper(substr($row[archivo],$Pos+1,4));                             
     $foto  = $row[archivo];        

    echo "<tr><td align='center'><IMG SRC='estudios/$foto' border='0' width='800'></td></tr>";   
    echo "<tr><td align='center'><b>$row[archivo]</b></td></tr>"; 
    echo "<tr><td align='center'> &nbsp; </td></tr>"; 

}    
                    
echo "</table><br>";

        
echo "</td></tr>";

echo "</table>";                                               
                                           
echo "</body>";
    
echo "</html>";  
  
  

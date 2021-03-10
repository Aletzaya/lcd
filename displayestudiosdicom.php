<?php

#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");

$link     = conectarse();
$busca     = $_REQUEST[busca];
setcookie ("NOOT",$busca);

$Usr      =   $check['uname'];
$cId       = $_REQUEST[busca]; 

$estudio  =   $_REQUEST[estudio];
$archivo = $_REQUEST[archivo];  
$lBd       = false;

#Variables comunes;
$Titulo    = " Detalle de estudios";

require ("config.php");							   //Parametros de colores;


if($archivo<>''){
    $id   = $_REQUEST[id];
    unlink("dicom/$archivo");
    $Usrelim    = $_COOKIE['USERNAME'];
    $Fechaelim  = date("Y-m-d H:i:s");
    $lUp = mysql_query("UPDATE estudiosdicom set usrelim='$Usrelim',fechaelim='$Fechaelim' where archivo='$archivo' and id='$id'");
}

  $HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion
  FROM ot,cli
  WHERE ot.orden='$busca' AND ot.cliente=cli.cliente");
     
  $He   = mysql_fetch_array($HeA);              

?>

<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>

<title></title>

<link rel="stylesheet" type="text/css" href="css/dropzone.css">

</head>

<body>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script src="js/dropzone.js"></script>

<script src="js/app.js"></script>

<table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#f1f1f1" style="border-collapse: collapse; border: 1px solid #999;">
       
<tr><td align="center" bgcolor="#156687" class="titulo" colspan="3"><font color="#FFFFFF"><b> Carga de Imagenes DICOM</b></td></tr></table>

<form class="dropzone" id="mydrop" action="subirdicom.php">

<div class="fallback">

<input type="file" name="file" multiple id="archivos">

</div>

</form>

<script type="text/javascript">

    var dropzone = new Dropzone("#archivos", {
        url: 'subirdicom.php'
    });

</script>

<?php

echo "<div align='center'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
echo "<div align='center'> <b>No.ORDEN: $He[institucion] - $busca</b></div>";

echo "<br><table width='100%' border='0' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";
       
echo "<tr><td align='left'  bgcolor='#156687' class='titulo' colspan='3'><font color='#FFFFCC'><b> &nbsp; Estudios capturados </b></td></tr>";     
       
        
$ImgA = mysql_query("SELECT archivo,idnvo FROM estudiosdicom WHERE id='$busca' and usrelim=''");
while ($row = mysql_fetch_array($ImgA)) {   
     $Pos   = strrpos($row[archivo],".");
     $cExt  = strtoupper(substr($row[archivo],$Pos+1,4));                             
     $foto  = $row[archivo];        
    
   //echo "</a> &nbsp; &nbsp; <font color='#FFFFCC'>".ucfirst(strtolower($row[archivo]))."</font>";                

     echo "<tr class='content_txt'><td bgcolor='#2980b9' align='left' colspan='2'><img src='lib/dicom.jpg' border='0' width='30'> <font color='#FFFFCC'> ".ucfirst(strtolower($row[archivo]))."</font></td><td align='center'><a href='descargar.php?archivo=$foto'>Descargar archivo</a></td><td align='center'><a class='Seleccionar' href='displayestudiosdicom.php?archivo=$foto&id=$busca&busca=$busca&estudio=$estudio' onclick='return confirm(\"Desea eliminar el archivo?\")'><font color='blue'><img src='lib/dele.png' title=Elimina_$Pdf border='0'> - Eliminar</font></a></td></tr>";
    //echo "<tr><td align='center'> &nbsp; </td></tr>";
}    
                    
echo "</table><br>";

        
echo "</td></tr>";

echo "</table>";                                               
                                           
echo "</body>";
    
echo "</html>";  

?>
                           
</body>
    
</html>


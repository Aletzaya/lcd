<?php
  session_start();

  require("lib/kaplib.php");

  $link = conectarse();

  $busca  = $_REQUEST[busca];

  $Titulo = "Informacion Importante del cliente";

  $CpoA   = mysql_query("SELECT * FROM cli WHERE cliente='$busca'");
  
  $Cpo    = mysql_fetch_array($CpoA);
  
  $aPrg   = array('Ninguno','Cliente frecuente','Apoyo a la Salud','Chequeo medico','Empleado','Familiar','Medico','Especializado');

  require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body>

<?php 
    
    //headymenu($Titulo,0);
    

    echo "<br><p align='left'>$Gfont <font size='+1'>$Titulo</font></p>";    
    
    echo "<table width='100%' border='0'>";

    echo "<tr>";
      echo "<td  width='10%'>&nbsp;</td>";
      echo "<td>$Gfont"; 

				$nPrg  = $Cpo[programa];
				
            cTable('80%','0');

            cInput("Cuenta: ","Text","10","Cuenta","right",$busca,"10",false,true,'');
     
            cInput('','text','','','','','',false,true,'&nbsp;');

            cInput("Nombre: ","Text","40","Nombre","right",'<b>'.$Cpo[nombrec].'<b>',"40",false,true,'');
            cInput("Direccion: ","Text","40","Direccion","right",$Cpo[direccion],"40",false,true,'');
            cInput("No.veces: ","Text","40","Programa","right",$Cpo[numveces],"10",false,true,'');
            cInput("Programa: ","Text","40","Programa","right",'<b>'.$aPrg[$nPrg].'</b>',"10",false,true,'');
            //cInput("Nota: ","Text","40","Programa","right",$Cpo[observaciones],"10",false,true,'');

            cTableCie();
            
            echo "<p>&nbsp;</p>";
            
            echo " <b>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; N o t a : &nbsp; &nbsp;  ";
            echo " $Cpo[observaciones]</b>";
            
            echo "<p>&nbsp;</p>";
            
            echo "<p align='center'><b><a class='pg' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></b></p>";
            
      
      echo "</td>";
    echo "</tr>";
    echo "</table>";
    
echo "</body>";

echo "</html>";
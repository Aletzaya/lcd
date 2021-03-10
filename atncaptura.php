<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  require ("config.php");

  include_once ("auth.php"); 

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link         = conectarse();
   $Usr       = $check['uname'];
  $Orden        = $_REQUEST[Orden];
  $busca        = $_REQUEST[Orden];
  $Orden2        = $_REQUEST[Orden];
  $Estudio      = $_REQUEST[Estudio];
  $Entregapac = $_REQUEST[Entregapac];
  $Recibepac  = $_REQUEST[Recibepac];
  $Obsentrega  = $_REQUEST[Obsentrega];
  $Op        = $_REQUEST[Op];
  $status    = $_REQUEST[status];


  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='1'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";
          
echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

?>

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Obsentrega.focus();

}

</script>

<?php
 
echo "<body bgcolor='#FFFFFF' onload='cFocus()'>";          
  
  echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

      
		echo "<font size='2'><p align='center'><b>Estudios de la Orden</b></p>";

		   $aLug = array('Etiqueta','Etiqueta','Proceso','Captura','Impresion','Recepcion','Entregado');
		
         $HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.observaciones FROM ot,cli
                 WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");
                 
         $He   = mysql_fetch_array($HeA);        		

 		   echo "<div align='left'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
 		   echo "<div align='left'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";
		   
         $Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.uno,otd.impeti,otd.recibepac,otd.obsest,otd.etiquetas,otd.statustom,otd.usrest,otd.capturo,otd.cuatro
		 		  FROM otd,est 
                  WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");
		
           
          echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

          echo "<tr height='25' bgcolor='#CCCCCC'>";
          echo "<td align='center'>$Gfont <b>Estudio</b></td>";
          echo "<td align='center'>$Gfont <b>Descripcion</b></td>";
          echo "<td align='center'>$Gfont <b>Fecha de captura</b></td>";		  
          echo "<td align='center'>$Gfont <b>Captura</b></td>";
          echo "<td align='center'>$Gfont <b>Observaciones</b></td>";
          echo "</tr>";              

        while($rg=mysql_fetch_array($Sql)){
        
            $Lugar = $rg[lugar];  

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
            echo "<td>$Gfont $rg[estudio]</td>";
            echo "<td>$Gfont $rg[descripcion]</td>";
			echo "<td align='center'>$Gfont $rg[cuatro]</td>";
			echo "<td align='center'>$Gfont $rg[capturo]</td>";
  
			if($rg[obsest]<>''){				
				echo "<td align='center'>$Gfont<a class='pg' href=javascript:winuni2('obsest.php?Orden=$Orden&Estudio=$rg[estudio]')><img src='lib/messageon.png' border='0'></a></td>";
			}else{
				echo "<td align='center'>$Gfont<a class='pg' href=javascript:winuni2('obsest.php?Orden=$Orden&Estudio=$rg[estudio]')><img src='lib/messageoff.png' border='0'></a></td>";
			}   
            echo "</tr>";
            $nRng++;

        }          
		  	 
        echo "</table>";
		 //echo "<form name='form' method='post' action='ordenpac.php?'> ";
		echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td align='center'><form name='form1' method='post' action='atnrealizacion.php'>";
		echo "$Gfont <font size='2'><b>Observaciones:&nbsp;</b>";
		echo "<TEXTAREA NAME='Observaciones' cols='70' rows='3'>$He[observaciones]</TEXTAREA>";
		echo Botones2();
		echo "</td></tr>"; 
		echo "</table>";
		echo "<br>";
		
  echo "</form>";    
  
     
  	                
echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?> 
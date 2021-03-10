<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  require ("config.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link         = conectarse();
  $Orden        = $_REQUEST[Orden];
  $Estudio      = $_REQUEST[Estudio];
  $Obsest = $_REQUEST[obsest];
  $boton        = $_REQUEST[boton];
  $Usr    = $_COOKIE['USERNAME'];
  $Fechaobs  = date("Y-m-d H:i:s");

	if($boton=='Enviar'){
		
   		$Up  = mysql_query("UPDATE otd SET obsest='$Obsest',usrobsest='$Usr',fechobsest='$Fechaobs',status='TERMINADA'
	            WHERE orden='$Orden' and estudio='$Estudio'"); 
	}
  
  if(strlen($Orden)>4 AND strlen($Estudio)>0){

     $Fecha = date("Y-m-d");

     $Hora  = date("H:i");

	  $OtdA  = mysql_query("SELECT cli.nombrec,otd.obsest,otd.estudio,otd.orden,otd.usrobsest,otd.fechobsest,ot.institucion,est.descripcion
	           FROM ot,cli,otd,est
	           WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio' AND otd.estudio=est.estudio");
	
	  $Otd   = mysql_fetch_array($OtdA);        		
			   

  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='1'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='1'>";
          
echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

?>

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Orden.focus();

}

</script>

<?php

echo "<body bgcolor='#FFFFFF' onload='cFocus()'>";          
  
  echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

		echo "$Gfont <font size='+1' color='#e20909'><p align='center'><b>TERMINACION DE ESTUDIO</b></font></p>";
		
		echo "<div align='left'><font size='+2'> Orden: &nbsp; $Otd[institucion] - $Orden &nbsp; $Otd[nombrec]</font></div>";
		   
        echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

        echo "<tr height='25' bgcolor='#CCCCCC'>";
        echo "<td align='center'>$Gfont2 Estudio</td>";
        echo "<td align='center'>$Gfont2 Descripcion</td>";
        echo "<td align='center'>$Gfont2 Observacion</td>";
        echo "<td align='center'>$Gfont2 Fecha</td>";
        echo "<td align='center'>$Gfont2 Usr</td>";
        echo "</tr>";              

       if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
            echo "<td>$Gfont $Otd[estudio]</td>";
            echo "<td>$Gfont $Otd[descripcion]</td>";
			$Obsest = $_REQUEST[obsest];
       		echo "<td><input type='TEXT' name='obsest' size='40' value='$Otd[obsest]'>";
      		echo "<input type='hidden' name='Orden' value=$Orden>";
      		echo "<input type='hidden' name='Estudio' value=$Otd[estudio]>";
        	echo "<input type='submit' name='boton' value='Enviar'></td>";
            echo "<td>$Gfont $Otd[fechobsest]</td>";
            echo "<td>$Gfont $Otd[usrobsest]</td>";
            echo "</tr>";
        }            	   
  		
        echo "</table>";
          
        while($nRng<=6){  
            echo "<div align='center'>$Gfont &nbsp; </div>";
            $nRng++;
        }    
echo "</form>";         
  	                
echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?> 
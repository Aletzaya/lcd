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
  $noconformidad = $_REQUEST[noconformidad];
  $Tnoconformidad = $_REQUEST[Tnoconformidad];
  
  if($Tnoconformidad==1){
      $Tnoconformidad2='1 Insufic. Disponibilidad del Pac.';
  }elseif($Tnoconformidad==2){
      $Tnoconformidad2='2 Falta de capacitacion';
  }elseif($Tnoconformidad==3){
      $Tnoconformidad2='3 Falta de comunicacion';
  }elseif($Tnoconformidad==4){
      $Tnoconformidad2='4 Errores';
  }elseif($Tnoconformidad==5){
      $Tnoconformidad2='5 Accidentes';
  }elseif($Tnoconformidad==6){
      $Tnoconformidad2='Muestra Insufic / Inadecuada';
  }elseif($Tnoconformidad==7){
      $Tnoconformidad2='Preparacion Incorrecta';
  }elseif($Tnoconformidad==8){
      $Tnoconformidad2='Otro';
  }

  $Tnoconformidad3=$Tnoconformidad2.' : - '.$noconformidad;

  $boton        = $_REQUEST[boton];
  $Usr    = $_COOKIE['USERNAME'];
  $Fechano  = date("Y-m-d H:i:s");

	if($boton=='Enviar'){
		
   		$Up  = mysql_query("UPDATE otd SET noconformidad='$Tnoconformidad3',usrno='$Usr',fechano='$Fechano'
	            WHERE orden='$Orden' and estudio='$Estudio'"); 

      $Ups  = mysql_query("UPDATE ot SET noconformidad='Si' WHERE orden='$Orden'"); 

	}elseif($boton=='Borrar'){
    
      $Up  = mysql_query("UPDATE otd SET noconformidad='',usrno='',fechano=''
              WHERE orden='$Orden' and estudio='$Estudio'"); 

      $Ups  = mysql_query("UPDATE ot SET noconformidad='No' WHERE orden='$Orden'"); 

  }
  
  if(strlen($Orden)>4 AND strlen($Estudio)>0){

     $Fecha = date("Y-m-d");

     $Hora  = date("H:i");

	  $OtdA  = mysql_query("SELECT cli.nombrec,otd.noconformidad,otd.estudio,otd.orden,ot.institucion,est.descripcion,otd.fechano,otd.usrno
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

		echo "$Gfont <p align='center'><b>Observaciones por Estudio</b></p>";
		
    echo "<div align='left'><font size='+2'> Orden: &nbsp; $Otd[institucion] - $Orden &nbsp; $Otd[nombrec]</font></div>";

    echo "<div align='center'><font size='+1' color='blue'>$Otd[estudio] - &nbsp; $Otd[descripcion]</font></div>";

        echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

        echo "<tr height='25' bgcolor='#CCCCCC'>";
        echo "<td align='center'>$Gfont2 Tipo de No conformidad</td>";
        echo "<td align='center'>$Gfont2 Observacion</td>";
        echo "<td align='center'>$Gfont2 Fecha</td>";
        echo "<td align='center'>$Gfont2 Usr</td>";
        echo "</tr>";      

       if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";   

            $Tnoconformidad = substr($Otd[noconformidad], 0);                    

            echo "<td>";
            echo "<select name='Tnoconformidad'>";
            echo "<option value='1'>Insufic. Disponibilidad del Pac.</option>";
            echo "<option value='2'>Falta de capacitacion</option>";
            echo "<option value='3'>Falta de comunicacion</option>";
            echo "<option value='4'>Errores</option>";
            echo "<option value='5'>Accidentes</option>";
            echo "<option value='6'>Muestra Insufic / Inadecuada</option>";
            echo "<option value='7'>Preparacion Incorrecta</option>";
            echo "<option value='8'>Otro</option>";
            if($Tnoconformidad==1){
                $Tnoconformidad2='Insufic. Disponibilidad del Pac.';
            }elseif($Tnoconformidad==2){
                $Tnoconformidad2='Falta de capacitacion';
            }elseif($Tnoconformidad==3){
                $Tnoconformidad2='Falta de comunicacion';
            }elseif($Tnoconformidad==4){
                $Tnoconformidad2='Errores';
            }elseif($Tnoconformidad==5){
                $Tnoconformidad2='Accidentes';
            }elseif($Tnoconformidad==6){
                $Tnoconformidad2='Muestra Insufic / Inadecuada';
            }elseif($Tnoconformidad==7){
                $Tnoconformidad2='Preparacion Incorrecta';
            }elseif($Tnoconformidad==8){
                $Tnoconformidad2='Otro';
            }
            echo "<option selected value='1'> $Tnoconformidad2 </option></p>";
            echo "</select></td>";

    			$noconformidad = $_REQUEST[noconformidad];
       		echo "<td><input type='TEXT' name='noconformidad' size='70' value='$Otd[noconformidad]'>";
      		echo "<input type='hidden' name='Orden' value=$Orden>";
      		echo "<input type='hidden' name='Estudio' value=$Otd[estudio]>";
          echo "&nbsp; &nbsp; <input type='submit' name='boton' value='Enviar'>";
          if($Usr=='NAZARIO'){
            echo "&nbsp; &nbsp; &nbsp; <input type='submit' name='boton' value='Borrar'>";
          }
          echo "</td><td>$Gfont $Otd[fechano]</td>";
          echo "<td>$Gfont $Otd[usrno]</td>";
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
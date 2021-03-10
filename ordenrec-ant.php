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
  $Recibeencaja = $_REQUEST[Recibeencaja];
  $Op        = $_REQUEST[Op];
  
  if(strlen($Orden)>4 AND strlen($Estudio)>0){

     $Fecha = date("Y-m-d");

     $Hora  = date("H:i");

	  $NumA  = mysql_query("SELECT otd.estudio 
	           FROM otd 
	           WHERE otd.orden='$Orden' AND mid(otd.cinco,1,4)='0000'");
     
	  $OtdA  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.cinco,otd.recibeencaja
	           FROM ot,cli,otd 
	           WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio'");
			   
	if($Op=='1' and $Estudio=='TODOS'){
		
			  $OtdA2  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.cinco,otd.recibeencaja
	           FROM ot,cli,otd 
	           WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");

	           
	  if($Otd   = mysql_fetch_array($OtdA2)){
		  	 
			if(substr($Otd[cinco],0,4)){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
			
				if ( $Otd[recibeencaja]=' '){
	  	
           		$Up  = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Recibeencaja' 
	               		WHERE orden='$Orden' and recibeencaja=' '"); 
						
		       $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");


           		$Msj = "Estudio actualizado con exito!!!";
				}
		  
        	}else{

           		$Up  = mysql_query("UPDATE otd SET lugar = '6', recibeencaja='$Recibeencaja' WHERE orden='$Orden'");   
           		$Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
        	}
	  }

	}else{
		
		if($Otd   = mysql_fetch_array($OtdA)){

        	if(substr($Otd[cinco],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
	  	
           		$Up  = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Recibeencaja' 
	               WHERE orden='$Orden' AND estudio='$Estudio'"); 

          		 $Msj = "Estudio actualizado con exito!!!";
		  
       		 }else{

           		$Up  = mysql_query("UPDATE otd SET lugar = '6', recibeencaja='$Recibeencaja' WHERE orden='$Orden' AND estudio='$Estudio'");   
           		$Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
        	}
		}
		
	}

        if(mysql_num_rows($NumA)==1){
        	  $lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");
        }
        
     }else{
        $Msj='El estudio: $Estudio de la Orden: $Orden NO existe';	
     }
                
       
          
  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='2'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";
          
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

      
		echo "$Gfont <p align='center'><b>Informe de Estudios entregados a Recepcion</b></p>";

		   $aLug = array('Etiqueta','Etiqueta','Proceso','Captura','Impresion','Recepcion','Entregado');
		
         $HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion FROM ot,cli
                 WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");
                 
         $He   = mysql_fetch_array($HeA);        		

 		   echo "<div align='left'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
 		   echo "<div align='left'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";
		
         $Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja FROM otd,est 
                  WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");
		
           
          echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

          echo "<tr height='25' bgcolor='#CCCCCC'>";
          echo "<td align='center'>$Gfont2 Estudio</td>";
          echo "<td align='center'>$Gfont2 Descripcion</td>";
          echo "<td align='center'>$Gfont2 Lugar</td>";   
          echo "<td align='center'>$Gfont2 Recepcion</td>";   
		  echo "<td align='center'>$Gfont2 Fecha/hora</td>";
          echo "<td align='center'>$Gfont2 Recibio</td>";   
          echo "</tr>";              

        while($rg=mysql_fetch_array($Sql)){
        
            $Lugar = $rg[lugar];  

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
            echo "<td>$Gfont $rg[estudio]</td>";
            echo "<td>$Gfont $rg[descripcion]</td>";
            echo "<td align='left'>$Gfont &nbsp; $aLug[$Lugar]</td>";				
				if($Lugar == '6'){
               echo "<td align='center'>$Gfont &nbsp; <img src='lib/slc.png'></td>";
            }else{
               echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenrec.php?Orden=$Orden&Estudio=$rg[estudio]&Recibeencaja=$Recibeencaja'>Entregar</a></td>";
            }   
			echo "<td>$Gfont $rg[cinco]</td>";
            echo "<td>$Gfont $rg[recibeencaja]</td>";
            echo "</tr>";
            $nRng++;

        }            	   
  		
        echo "</table>";
          
        while($nRng<=6){  
            echo "<div align='center'>$Gfont &nbsp; </div>";
            $nRng++;
        }    

        echo "$Gfont <a class='pg' href='javascript:window.close()'><img src='lib/regresa.jpg' border='0'></a> &nbsp; &nbsp; ";
		echo "Recibe en caja: <input type='TEXT' name='Recibeencaja' readonly='readonly' size='10' value='$Recibeencaja'> "; 
       echo "&nbsp No.orden: <input type='TEXT' name='Orden' size='4' value=''> &nbsp; &nbsp; ";
        echo "<input type='submit' name='boton' value='Enviar'>";
		
		echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenrec.php?Orden=$Orden&Op=1&Recibeencaja=$Recibeencaja&Estudio=TODOS'>Entregar Todo</a></td>";

  echo "</form>";         
  	                
echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?> 
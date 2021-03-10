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
  $Orden2        = $_REQUEST[Orden];
  $Estudio      = $_REQUEST[Estudio];
  $Entregapac = $_REQUEST[Entregapac];
  $Recibepac  = $_REQUEST[Recibepac];
  $Obsentrega  = $_REQUEST[Obsentrega];
  $Op        = $_REQUEST[Op];
  
  if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo
        $lUp2   = mysql_query("UPDATE ot set observaciones='$_REQUEST[Observaciones]' WHERE orden='$Orden'");
  }

  
  if(strlen($Orden)>4 AND strlen($Estudio)>0){

     $Fecha = date("Y-m-d");

     $Hora  = date("H:i");

	  $NumA  = mysql_query("SELECT otd.estudio 
	           FROM otd 
	           WHERE otd.orden='$Orden' AND mid(otd.seis,1,4)='0000'");
     
	  $OtdA  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega
	  			FROM ot,cli,otd 
				WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio'");
				 
	if($Op=='2'){
		
			  $OtdA3  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega
	           FROM ot,cli,otd 
	           WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");
			   
	  
	  if($Otd   = mysql_fetch_array($OtdA3)){
		  	 
			if(substr($Otd[seis],0,4)){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
			
				if ( $Otd[obsentrega]=''){
	  	
           		$Up  = mysql_query("UPDATE otd SET obsentrega='$Obsentrega'
	               		WHERE orden='$Orden' and obsentrega=''"); 
				
				}
			}
	  }
	}

	if($Op=='1' and $Estudio=='TODOS'){
		
			  $OtdA2  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega
	           FROM ot,cli,otd 
	           WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");

	           
	  if($Otd   = mysql_fetch_array($OtdA2)){
		  	 
			if(substr($Otd[seis],0,4)){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
			
				if ( $Otd[entregapac]=' '){
	  	
           		$Up  = mysql_query("UPDATE otd SET seis = '$Fecha $Hora', lugar='6',entregapac='$Entregapac',otd.recibepac='$Recibepac',obsentrega='$Obsentrega'
	               		WHERE orden='$Orden' and entregapac=''"); 
						
		       $lUp = mysql_query("UPDATE ot SET status='Entregada' WHERE orden='$Orden'");


           		$Msj = "Estudio actualizado con exito!!!";
				}
		  
        	}else{

           		$Up  = mysql_query("UPDATE otd SET lugar = '6', entregapac='$Entregapac', recibepac='$Recibepac' ,obsentrega='$Obsentrega' WHERE orden='$Orden'");   
           		$Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
        	}
	  }

	}else{
		
		if($Otd   = mysql_fetch_array($OtdA)){

        	if(substr($Otd[seis],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
	  	
           		$Up  = mysql_query("UPDATE otd SET seis = '$Fecha $Hora', lugar='6',entregapac='$Entregapac',otd.recibepac='$Recibepac',otd.obsentrega='$Obsentrega' 
	               WHERE orden='$Orden' AND estudio='$Estudio'"); 

          		 $Msj = "Estudio actualizado con exito!!!";
		  
       		 }else{

           		$Up  = mysql_query("UPDATE otd SET lugar = '6', entregapac='$Entregapac',otd.recibepac='$Recibepac',otd.obsentrega='$Obsentrega'
						 WHERE orden='$Orden' AND estudio='$Estudio'");   
           		$Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
        	}
		}
		
	}

     if(mysql_num_rows($NumA)==1){
        	  $lUp = mysql_query("UPDATE ot SET status='Entregada' WHERE orden='$Orden'");
        }
        
     }else{
        $Msj='El estudio: $Estudio de la Orden: $Orden NO existe';	
     }
	 
                
       
          
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
		   
         $Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.uno,otd.impeti,otd.recibepac,otd.obsest,otd.etiquetas
		 		  FROM otd,est 
                  WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");
		
           
          echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

          echo "<tr height='25' bgcolor='#CCCCCC'>";
          echo "<td align='center'>$Gfont <b>Estudio</b></td>";
          echo "<td align='center'>$Gfont <b>Descripcion</b></td>";
          echo "<td align='center'>$Gfont <b>Etiq</b></td>";
          echo "<td align='center'>$Gfont <b>Imprime</b></td>";
          echo "<td align='center'>$Gfont <b>Fecha/hora Impr</b></td>";
          echo "<td align='center'>$Gfont <b>Observaciones</b></td>";
          echo "</tr>";              

        while($rg=mysql_fetch_array($Sql)){
        
            $Lugar = $rg[lugar];  

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
            echo "<td>$Gfont $rg[estudio]</td>";
            echo "<td>$Gfont $rg[descripcion]</td>";
            echo "<td align='center'>$Gfont <font size='1'>$rg[etiquetas]<a href=javascript:winuni2('impeti.php?op=1&busca=".$Orden."&Est=$rg[estudio]')><img src='lib/print.png' alt='Imprime' border='0'></a></td>";
            echo "<td>$Gfont $rg[impeti]</td>";
			echo "<td>$Gfont $rg[uno]</td>";
			if($rg[obsest]<>''){				
				echo "<td align='center'>$Gfont<a class='pg' href=javascript:winuni2('obsest.php?Orden=$Orden&Estudio=$rg[estudio]')><img src='lib/messageon.png' border='0'></a></td>";
			}else{
				echo "<td align='center'>$Gfont<a class='pg' href=javascript:winuni2('obsest.php?Orden=$Orden&Estudio=$rg[estudio]')><img src='lib/messageoff.png' border='0'></a></td>";
			}   
            echo "</tr>";
            $nRng++;

        }          
		  	 
        echo "</table>";
		echo "<br>&nbsp;<a class='pg' href=javascript:winuni2('impeti2.php?op=3&busca=$Orden')>$Gfont <font size='2' color='#000099'> Etiquetas</a><br>";
		 //echo "<form name='form' method='post' action='ordenpac.php?'> ";
		echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td align='center'><form name='form1' method='post' action='atnetiqueta.php'>";
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
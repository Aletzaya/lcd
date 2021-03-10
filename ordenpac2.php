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
  $Usr       = $check['uname'];
  $Fechaest  = date("Y-m-d H:i:s");
  
	if(strlen($Orden)>4 AND strlen($Estudio)>0){
	
		$Fecha = date("Y-m-d");
		
		$Hora  = date("H:i");
		
		$NumA  = mysql_query("SELECT otd.estudio 
			   FROM otd 
			   WHERE otd.orden='$Orden' AND mid(otd.seis,1,4)='0000'");
		
		$OtdA  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega,
							  otd.cinco,otd.recibeencaja
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
			  
		}elseif($Op=='1' and $Estudio=='TODOS'){
		
			$OtdA2  = mysql_query("SELECT cli.nombrec,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega
			otd.cinco,otd.recibeencaja
			FROM ot,cli,otd 
			WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden");

			if($Otd   = mysql_fetch_array($OtdA2)){
		  	 
				if(substr($Otd[seis],0,4)){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
			
					if ( $Otd[entregapac]=' '){
	  	
						$Up  = mysql_query("UPDATE otd SET seis = '$Fecha $Hora', lugar='6',entregapac='$Entregapac',otd.recibepac='$Recibepac',obsentrega='$Obsentrega'
							WHERE orden='$Orden' and entregapac=''"); 
						
						$lUp = mysql_query("UPDATE ot SET status='Entregada' WHERE orden='$Orden'");
					
						$Msj = "Estudio actualizado con exito!!!";
						
						if ( $Otd[recibeencaja]=' '){
							
							$Up  = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Entregapac' 
								WHERE orden='$Orden' and recibeencaja=' '"); 
								
							$lUp = mysql_query("UPDATE ot SET encaja='Si' WHERE orden='$Orden'");
						
						}

					}
		  
        		}else{

					$Up  = mysql_query("UPDATE otd SET lugar = '6', entregapac='$Entregapac', recibepac='$Recibepac' ,obsentrega='$Obsentrega' WHERE orden='$Orden'");   
					$Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
				}
			}
			
		}elseif($Op=='3'){
			
			if($Otd   = mysql_fetch_array($OtdA)){

				if(substr($Otd[seis],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
	  	
					$Up  = mysql_query("UPDATE otd SET seis = '$Fecha $Hora', lugar='6',entregapac='$Entregapac',otd.recibepac='$Recibepac',otd.obsentrega='$Obsentrega' 
					   WHERE orden='$Orden' AND estudio='$Estudio'"); 
	
					 $Msj = "Estudio actualizado con exito!!!";
					 
					if(substr($Otd[cinco],0,4)=='0000'){ //Actualizo la fecha y hora del paso 2, que es imprisiion de et.;
					
						$Up  = mysql_query("UPDATE otd SET cinco = '$Fecha $Hora', lugar='6',recibeencaja='$Entregapac' 
						WHERE orden='$Orden' AND estudio='$Estudio'"); 
						
						$Msj = "Estudio actualizado con exito!!!";
						
					}
		  
				 }else{

					$Up  = mysql_query("UPDATE otd SET lugar = '6', entregapac='$Entregapac',otd.recibepac='$Recibepac',otd.obsentrega='$Obsentrega'
							 WHERE orden='$Orden' AND estudio='$Estudio'");   
					$Msj = 'Unicamente se cambio de Ubicacion, ya se habia informado del cambio a recepcion';
				 }
			}
			
			if(mysql_num_rows($NumA)==1){
				
				  $lUp = mysql_query("UPDATE ot SET status='Entregada' WHERE orden='$Orden'");
	
			}

		}elseif($Op=='res'){
		
			$Up  = mysql_query("UPDATE otd SET seis = '0000-00-00 00:00:00', lugar='5',recibepac='', entregapac='', obsentrega=''
			   WHERE orden='$Orden' AND estudio='$Estudio'"); 
			   
			$Msj = 'Entrega de Est Paciente $Estudio RESTAURADA';
			
			$lUp2  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
			('$Fechaest','$Usr','Entrega al Pac RESTAURADA Est: $Estudio Ot: $Orden')");
			
			$lUp3 = mysql_query("UPDATE ot SET status='TERMINADA' WHERE orden='$Orden'");
		}

//	}elseif($Op=='ed'){
//		
//		$Up  = mysql_query("UPDATE otd SET seis = '0000-00-00 00:00:00', lugar='5',recibepac='', entregapac='', obsentrega=''
//		   WHERE orden='$Orden' AND estudio='$Estudio'"); 
//		$Msj = 'Entrega de Est Paciente $Estudio RESTAURADA';
// 	    $lUp2  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
//		('$Fechaest','$Usr','Entrega al Pac RESTAURADA Est: $Estudio Ot: $Orden')");
// 	    $lUp3 = mysql_query("UPDATE ot SET status='TERMINADA' WHERE orden='$Orden'");

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

		echo "<font size='2'><p align='center'><b>Informe de Estudios entregados al Paciente</b></p>";

		 $aLug = array('Etiqueta','Etiqueta','Proceso','Captura','Impresion','Recepcion','Entregado');
		
         $HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion FROM ot,cli
                 WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");
                 
         $He   = mysql_fetch_array($HeA);        		

 		   echo "<div align='left'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
 		   echo "<div align='left'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";
		   
         $Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.seis,otd.entregapac,otd.recibepac,otd.obsentrega
		 		  FROM otd,est 
                  WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");
				  
          echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";
          echo "<tr height='25' bgcolor='#CCCCCC'>";
          echo "<td align='center'>$Gfont <b> Estudio </b></td>";
          echo "<td align='center'>$Gfont <b> Descripcion </b></td>";
		  echo "<td align='center'>$Gfont <b> &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Paciente&Obsentrega=$Obsentrega'>Paciente </b></a></td>";
		  echo "<td align='center'>$Gfont <b> &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Familiar&Obsentrega=$Obsentrega'>Familiar</b></a></td>";
  		  echo "<td align='center'>$Gfont <b> &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Clinic/Inst&Obsentrega=$Obsentrega'>Clinic/Inst</b></a></td>";
		  echo "<td align='center'>$Gfont <b> &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Otro&Obsentrega=$Obsentrega'>Otro </b> </a></td>";
		  echo "<td align='center'>$Gfont <b> &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Op=2&Entregapac=$Entregapac&Estudio=TODOS&Obsentrega=$Obsentrega'>Observaciones</b> </a></td>";
		  echo "<td align='center'>$Gfont <b> Fecha/hora </b></td>";
          echo "<td align='center'>$Gfont <b> Entrego </b></td>";
		  if($Usr=='nazario' or $Usr=='MARYLIN'){
            echo "<td align='center'>$Gfont <b> Edita </b></td>";
          	echo "<td align='center'>&nbsp;</td>";   
		  }
          echo "</tr>";              

        while($rg=mysql_fetch_array($Sql)){
        
            $Lugar = $rg[lugar];  

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
            echo "<td>$Gfont $rg[estudio]</td>";
            echo "<td>$Gfont $rg[descripcion]</td>";
			if($rg[recibepac]=='Paciente'){				
               echo "<td align='center'>$Gfont &nbsp; <img src='lib/slc.png'></td>";
            }else{
				if($rg[recibepac]==''){
               		echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Paciente&Obsentrega=$Obsentrega&Op=3'>Paciente</a></td>";
				}else{
					 echo "<td align='center'>$Gfont &nbsp; - </td>";
				}
            }   
			if($rg[recibepac]=='Familiar'){
               echo "<td align='center'>$Gfont &nbsp; <img src='lib/slc.png'></td>";
            }else{
				if($rg[recibepac]==''){
               		echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Familiar&Obsentrega=$Obsentrega&Op=3'>Familiar</a></td>";
				}else{
					 echo "<td align='center'>$Gfont &nbsp; - </td>";
				}
            }   
			if($rg[recibepac]=='Clinic/Inst'){
               echo "<td align='center'>$Gfont &nbsp; <img src='lib/slc.png'></td>";
            }else{
				if($rg[recibepac]==''){
               		echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Clinic/Inst&Obsentrega=$Obsentrega&Op=3'>Clinic/Inst</a></td>";
				}else{
					 echo "<td align='center'>$Gfont &nbsp; - </td>";
				}
            }
			if($rg[recibepac]=='Otro'){
               echo "<td align='center'>$Gfont &nbsp; <img src='lib/slc.png'></td>";
            }else{
				if($rg[recibepac]==''){
              		 echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Entregapac=$Entregapac&Recibepac=Otro&Obsentrega=$Obsentrega&Op=3'>Otro</a></td>";
				}else{
					 echo "<td align='center'>$Gfont &nbsp; - </td>";
				}
            }
			
			if($rg[obsentrega]<>''){
               echo "<td align='center'>$Gfont $rg[obsentrega] &nbsp; <img src='lib/slc.png'></td>";
            }else{
				if($Obsentrega<>''){
					if($rg[recibepac]==''){
						$Recibepac2 = '';
						$Entregapac2 = $Entregapac;
					}else{
						$Recibepac2 = $rg[recibepac];
						$Entregapac2 = $rg[entregapac];
					}
               		echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Recibepac=$Recibepac2&Entregapac=$Entregapac2&Obsentrega=$Obsentrega&Op=3'>$Obsentrega</a></td>";
				}else{
					 echo "<td align='center'>$Gfont $rg[obsentrega] </td>";
				}
            }
	  		echo "<td>$Gfont $rg[seis]</td>";
            echo "<td>$Gfont $rg[entregapac]</td>";
			if($Usr=='nazario' or $Usr=='MARYLIN'){
				echo "<td align='center'>$Gfont &nbsp; </td>";
		//		echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Op=ed'>***EDITA</a></td>";
				echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='ordenpac.php?Orden=$Orden&Estudio=$rg[estudio]&Op=res'>***RESTAURAR</a></td>";
			}
            echo "</tr>";
            $nRng++;

        }          
		  	 
        echo "</table>";
		
		 //echo "<form name='form' method='post' action='ordenpac.php?'> ";
         echo "$Gfont <strong>Observaciones :</strong>: ";
         echo "<input class='textos' class='texto' name='Obsentrega' type='text' size='20'> &nbsp; ";
		 echo "<input type='submit' name='boton' value='OK'>";
         echo "<div align='center'>$Gfont &nbsp; </div>";
         echo "<div align='center'><strong> <font color='#F000000' Size=2>RECUERDA PONER PRIMERO LAS OBSERVACIONES Y DESPUES ELEGIR LA OPCION DE ENTREGA </strong></div> </font>";

         //echo "<a href='agrestord.php?Vta=$Vta&Usr=$Usr&orden=est.descripcion'></a>";
		 
 		 //echo "</form>";
		while($nRng<=5){  
            echo "<div align='center'>$Gfont &nbsp; </div>";
            $nRng++;
        }    

        echo "$Gfont <a class='pg' href='javascript:window.close()'><img src='lib/regresa.jpg' border='0'></a> &nbsp; &nbsp; ";
		echo "Entrega a paciente: <input type='TEXT' name='Entregapac' readonly='readonly' size='10' value='$Entregapac'> "; 
       echo "&nbsp No.orden: <input type='TEXT' name='Orden' size='4' value=$Orden> &nbsp; &nbsp; ";
        echo "<input type='submit' name='boton' value='Enviar'>";
		
  echo "</form>";    
  
     
  	                
echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?> 
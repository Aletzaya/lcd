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

  if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo
        $lUp2   = mysql_query("UPDATE ot set observaciones='$_REQUEST[Observaciones]' WHERE orden='$Orden'");
  }

  			 
  if($_REQUEST[op]=='1'){
	  if($_REQUEST[regis]=='1'){
			$Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
					  WHERE orden='$busca' AND estudio='$Estudio'");

			 $OtdA  = mysql_query("SELECT dos,lugar,estudio FROM otd WHERE orden='$busca' AND estudio='$Estudio'");
			 
			  while ($Otd  = mysql_fetch_array($OtdA)){	   
				 $Est  = $Otd[estudio];  
				  if(substr($Otd[dos],0,4)=='0000'){     
						if($Otd[lugar] <= '3'){	         
					  		$lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
							 WHERE orden='$busca' and estudio='$Estudio' limit 1");                     
					   }else{           
						  $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
							 WHERE orden='$busca' AND estudio='$Estudio' limit 1");           
						}
				 	}  	
			 	}

  	  }else{
       		$Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
	          WHERE orden='$busca' AND estudio='$Estudio'");
	  }
	  
	   $NumA1  = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$busca' AND otd.statustom='PENDIENTE'");
	   
	   $NumA2  = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$busca' AND otd.statustom=' '");

	 	if(mysql_num_rows($NumA1)>=1){
			  $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$busca'");
		}else{ 
			 if(mysql_num_rows($NumA2)==0){
				$lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$busca'");
			 }else{ 
			  	$lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$busca'");
 			 } 
		 } 
  }elseif($_REQUEST[op]=='2'){
	  if($_REQUEST[regis]=='1'){
       		$Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
	          WHERE orden='$busca' AND usrest=''");
		  			  
			 $OtdA  = mysql_query("SELECT dos,lugar,estudio,usrest FROM otd WHERE orden='$busca'");
			 
			  while ($Otd  = mysql_fetch_array($OtdA)){	   
				 $Est  = $Otd[estudio];  
				  if(substr($Otd[dos],0,4)=='0000'){     
						if($Otd[lugar] <= '3'){	         
					  		$lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
							 WHERE orden='$busca' AND usrest=''");                     
					   }else{           
						  $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
							 WHERE orden='$busca' AND usrest=''");           
						}
				 	}  	
			 	}
				
  	  }else{
		 $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
	          WHERE orden='$busca' AND usrest=''");
	  }
	  
	   $NumA1  = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$busca' AND otd.statustom='PENDIENTE'");
	   
	   $NumA2  = mysql_query("SELECT otd.estudio 
	   FROM otd 
	   WHERE otd.orden='$busca' AND otd.statustom=' '");

	 	if(mysql_num_rows($NumA1)>=1){
			  $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$busca'");
		}else{ 
			 if(mysql_num_rows($NumA2)==0){
				$lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$busca'");
			 }else{ 
			  	$lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$busca'");
 			 } 
		 } 

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
		   
         $Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.uno,otd.impeti,otd.recibepac,otd.obsest,otd.etiquetas,otd.statustom,otd.usrest,otd.fechaest
		 		  FROM otd,est 
                  WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");
		
           
          echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";

          echo "<tr height='25' bgcolor='#CCCCCC'>";
          echo "<td align='center'>$Gfont <b>Estudio</b></td>";
          echo "<td align='center'>$Gfont <b>Descripcion</b></td>";
          echo "<td align='center'>$Gfont <b><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=2&regis=1&status=TOMA/REALIZ'>Tma/Rea</b></a></td>";
          echo "<td align='center'>$Gfont <b><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=2&regis=1&status=RECOLECCION'>Recol</b></a></td>";
          echo "<td align='center'>$Gfont <b><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=2&Estudio=TODOS&status=PENDIENTE'>Pend</b></a></td>";
          echo "<td align='center'>$Gfont <b>Usr</b></td>";
          echo "<td align='center'>$Gfont <b>Fecha</b></td>";
          echo "<td align='center'>$Gfont <b>Observaciones</b></td>";
          echo "</tr>";              

        while($rg=mysql_fetch_array($Sql)){
        
            $Lugar = $rg[lugar];  

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
            echo "<td>$Gfont $rg[estudio]</td>";
            echo "<td>$Gfont $rg[descripcion]</td>";
			if($rg[statustom]=='TOMA/REALIZ'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($rg[statustom]==''){
               		echo "<td align='center'><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=1&Estudio=$rg[estudio]&regis=1&status=TOMA/REALIZ'>OK</b></a></td>";
				}else{
               		echo "<td align='center'><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=1&Estudio=$rg[estudio]&regis=1&status=TOMA/REALIZ'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }   
			if($rg[statustom]=='RECOLECCION'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($rg[statustom]==''){
               		echo "<td align='center'><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=1&Estudio=$rg[estudio]&regis=1&status=RECOLECCION'>OK</b></a></td>";
				}else{
               		echo "<td align='center'><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=1&Estudio=$rg[estudio]&regis=1&status=RECOLECCION'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }   
			if($rg[statustom]=='PENDIENTE'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($rg[statustom]==''){
               		echo "<td align='center'><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=1&Estudio=$rg[estudio]&status=PENDIENTE'>OK</b></a></td>";
				}else{
               		echo "<td align='center'><a class='pg' href='atnrealizacion.php?Orden=$Orden&op=1&Estudio=$rg[estudio]&status=PENDIENTE'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            } 
			
			echo "<td align='center'>$Gfont $rg[usrest]</td>";
			echo "<td align='center'>$Gfont $rg[fechaest]</td>";
  
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
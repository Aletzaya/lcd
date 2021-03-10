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
  $Busca        = $_REQUEST[Orden];
  $Estudio      = $_REQUEST[Estudio];
  $Recoleccion = $_REQUEST[Recoleccion];
  $Op = $_REQUEST[Op];
  $status    = $_REQUEST[status];
  $Suc    = $_REQUEST[Suc];
  $regis=$_REQUEST[regis];


  $boton        = $_REQUEST[boton];
  $Usr    = $_COOKIE['USERNAME'];
  $Fecha     = date("Y-m-d");
  $Fechaest  = date("Y-m-d H:i:s");
  $Hora      = date("H:i:s");

	if($Op==1){
		
   		$Up2  = mysql_query("UPDATE otd SET recoleccionest='$Recoleccion'
	            WHERE orden='$Orden' and estudio='$Estudio'"); 
      
      if($regis==1){

          $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
            WHERE orden='$Orden' AND estudio='$Estudio'");

          $OtdB  = mysql_query("SELECT dos,lugar,estudio FROM otd WHERE orden='$Orden' AND estudio='$Estudio'");
       
        while ($Otd2  = mysql_fetch_array($OtdB)){    
         $Est  = $Otd2[estudio];  
          if(substr($Otd2[dos],0,4)=='0000'){     
            if($Otd2[lugar] <= '3'){          
                $lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
               WHERE orden='$Orden' and estudio='$Estudio' limit 1");                     
             }else{           
              $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
               WHERE orden='$Orden' AND estudio='$Estudio' limit 1");           
            }
          }

          $SqlC="SELECT *
          FROM maqdet
          WHERE maqdet.orden='$Orden' AND maqdet.estudio='$Est'";

          $resC=mysql_query($SqlC,$link);

          $registro4=mysql_fetch_array($resC);

          if (empty($registro4)) {

            $lUp    = mysql_query("INSERT INTO maqdet (orden,estudio,mint,fenv,henv,usrenv)
            VALUES
            ('$Orden','$Est','$_REQUEST[Suc]','$Fecha','$Hora','$Usr')");
          }else{
            $lUp    = mysql_query("UPDATE maqdet SET orden='$Orden',estudio='$Est',mint='$_REQUEST[Suc]',fenv='$Fecha',henv='$Hora',usrenv='$Usr' WHERE maqdet.orden='$Orden' AND maqdet.estudio='$Est' limit 1");
          }   
        }

      }else{
          $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
            WHERE orden='$Orden' AND estudio='$Estudio'");
      }
    
     $NumA1  = mysql_query("SELECT otd.estudio 
     FROM otd 
     WHERE otd.orden='$Orden' AND otd.statustom='PENDIENTE'");
     
     $NumA2  = mysql_query("SELECT otd.estudio 
     FROM otd 
     WHERE otd.orden='$Orden' AND otd.statustom=' '");

    if(mysql_num_rows($NumA1)>=1){
        $lUp = mysql_query("UPDATE ot SET realizacion='PD' WHERE orden='$Orden'");
    }else{ 
       if(mysql_num_rows($NumA2)==0){
        $lUp = mysql_query("UPDATE ot SET realizacion='Si' WHERE orden='$Orden'");
       }else{ 
          $lUp = mysql_query("UPDATE ot SET realizacion='No' WHERE orden='$Orden'");
       } 
     } 

//      $Ups  = mysql_query("UPDATE ot SET noconformidad='Si' WHERE orden='$Orden'"); 
	}
  

	  $OtdA  = mysql_query("SELECT cli.nombrec,otd.estudio,otd.orden,ot.institucion,est.descripcion,otd.fechano,otd.usrno,otd.recoleccionest,ot.suc
	           FROM ot,cli,otd,est
	           WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente AND ot.orden=otd.orden AND otd.estudio='$Estudio' AND otd.estudio=est.estudio");
	
	  $Otd   = mysql_fetch_array($OtdA);        		
			   

  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='3'>";
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

echo "$Gfont <p align='center'><b>Observaciones por Estudio</b></p>";

echo "<div align='left'><font size='+1'> Orden: &nbsp; $Otd[institucion] - $Orden &nbsp; $Otd[nombrec]</font></div>";

echo "<div align='center'><font color='blue'>$Otd[estudio] - &nbsp; $Otd[descripcion]</font></div>";

echo "<table align='center' width='90%' border='1' cellspacing='0' cellpadding='0'>";

echo "<tr height='25' bgcolor='#CCCCCC'>";
echo "<td align='center' colspan='3'>$Gfont Tipo de Recoleccion</td>";
echo "</tr>";     

if($Otd[recoleccionest]=='Interna'){
  $Interna="<img src='lib/slc.png'>";
  $ExternaInstitucional=" ";
  $Remitida=" ";
}elseif($Otd[recoleccionest]=='ExternaInstitucional'){
  $Interna=" ";
  $ExternaInstitucional="<img src='lib/slc.png'>";
  $Remitida=" ";
}elseif($Otd[recoleccionest]=='Remitida'){
  $Interna=" ";
  $ExternaInstitucional=" ";
  $Remitida="<img src='lib/slc.png'>";
}else{
  $Interna=" ";
  $ExternaInstitucional=" ";
  $Remitida=" ";
}

if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

echo "<tr><td align='center' width='33%'><font color='blue' size='1'> &nbsp; <a class='pg' href='recoleccion.php?Orden=$Orden&Estudio=$Estudio&regis=1&status=RECOLECCION&Suc=$Otd[suc]&Op=1&Recoleccion=Interna'><img src='images/logo-lcd.jpg' width='80'><br>Interna</a> $Interna</td>";

echo "<td align='center' width='33%'><font color='blue' size='2'> &nbsp; <a class='pg' href='recoleccion.php?Orden=$Orden&Estudio=$Estudio&regis=1&status=RECOLECCION&Suc=$Otd[suc]&Op=1&Recoleccion=ExternaInstitucional'><img src='images/hospi.jpg' width='80'><br>Externa Institucional</a> $ExternaInstitucional</td>";

echo "<td align='center' width='33%'><font color='blue' size='2'> &nbsp; <a class='pg' href='recoleccion.php?Orden=$Orden&Estudio=$Estudio&regis=1&status=RECOLECCION&Suc=$Otd[suc]&Op=1&Recoleccion=Remitida'><img src='images/muestra.jpg' width='80'><br>Remitida Paciente/Familiar</a> $Remitida</td></tr>";

echo "</tr>";

echo "</table>";        
  	                
echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?> 
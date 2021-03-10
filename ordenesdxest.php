<?php

  session_start();

  require("lib/lib.php");
  date_default_timezone_set("America/Mexico_City");
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");


  $Usr       = $check['uname'];
  $busca     = $_REQUEST[busca];
  $pagina    = $_REQUEST[pagina];
  $orden     = $_REQUEST[orden];
  $Estudio   = $_REQUEST[Estudio];
  $status    = $_REQUEST[status];

  $Msj       = "";
  $Fecha     = date("Y-m-d");
  $Fechaest  = date("Y-m-d H:i:s");
  $Hora      = date("h:i:s");
  $Titulo    = "Detalle Estudio[$Estudio]";

  $link      = conectarse();

  $OrdenDef  = "otd.estudio";            //Orden de la tabla por default

  $Tabla     = "otd";
  
  if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo
        $lUp2   = mysql_query("UPDATE ot set observaciones='$_REQUEST[Observaciones]' WHERE orden='$busca'");
  }


  $cSqlH     = "SELECT ot.orden,ot.fecha,ot.hora,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,
                ot.medico,med.nombrec,ot.status,ot.recibio,ot.institucion,ot.pagada,ot.observaciones
                FROM ot,cli,med
                WHERE ot.cliente=cli.cliente AND ot.medico=med.medico AND ot.orden='$busca'";

  $cSqlD     = "SELECT otd.estudio,otd.status,est.descripcion,otd.precio,otd.descuento,est.muestras,otd.etiquetas,
  		        otd.capturo,est.depto,otd.recibeencaja,otd.cinco,otd.statustom
                FROM otd,est
                WHERE otd.estudio=est.estudio AND otd.orden='$busca' and otd.estudio='$Estudio'";

  $HeA       = mysql_query($cSqlH);
  $He        = mysql_fetch_array($HeA);
  
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

  
  
  if($_REQUEST[op]=='1'){
		
       $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
	          WHERE orden='$busca' AND estudio='$Estudio'");
			  
  }elseif($_REQUEST[op]=='2'){
	  
       $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
	          WHERE orden='$busca' AND usrest=''");

  }

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';

require ("config.php");

echo "<html>";

//echo "<head>";

echo "<title>$Titulo</title>";

?>

<script language="JavaScript1.2">
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
	alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=450,height=350,left=100,top=150")
}
function WinRes(url){
   window.open(url,"WinRes","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=900,height=500,left=30,top=80")
}

</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

//headymenu($Titulo,0);

   echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";

   echo "<tr bgcolor ='#618fa9'>";

   echo "<td>$Gfont <font color='#ffffff'>Cliente: $He[cliente] $He[5]</td>
   <td>$Gfont <font color='#ffffff'>Fecha/Hora: $He[fecha]&nbsp; $He[hora]&nbsp;Fecha/entrega: $He[fechae] </td>";

   echo "</tr>";

   echo "</table>";

   echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'><tr><td>$Gfont";

   if(!$res=mysql_query($cSqlD." ORDER BY ".$OrdenDef)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/� limpia.</a></font>";
 		echo "</div>";
 	}else{
      echo "<br>";
      $numeroRegistros=mysql_num_rows($res);

		echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr height='25' background='lib/bartit.gif'>";
        echo "<th>$Gfont <font size='1'>Estud</font></th>";
        echo "<th>$Gfont <font size='1'>Descripcion</font></th>";
        echo "<th>$Gfont <font size='1'>Tma/Rea</font></th>";
        echo "<th>$Gfont <font size='1'>Recol</font></th>";
        echo "<th>$Gfont <font size='1'>Pend</font></th>";
        echo "<th>$Gfont <font size='1'>Status</font></th>";
        echo "<th>$Gfont <font size='1'>Capturo</font></th>";

		  while($registro=mysql_fetch_array($res))		{

            $clnk=strtolower($registro[estudio]);

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td>$Gfont $registro[estudio]</font></td>";
            echo "<td>$Gfont<font size='1'>$registro[descripcion]</font></td>";
			if($registro[statustom]=='TOMA/REALIZ'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($registro[statustom]==''){
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesdxest.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=TOMA/REALIZ'>OK</b></a></td>";
				}else{
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesdxest.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=TOMA/REALIZ'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }   
			if($registro[statustom]=='RECOLECCION'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($registro[statustom]==''){
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesdxest.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=RECOLECCION'>OK</b></a></td>";
				}else{
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesdxest.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=RECOLECCION'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }   
			if($registro[statustom]=='PENDIENTE'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($registro[statustom]==''){
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesdxest.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&status=PENDIENTE'>OK</b></a></td>";
				}else{
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesdxest.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&status=PENDIENTE'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }
            echo "<td>$Gfont <font size='1'>$registro[status]</font></td>";
            echo "<td>$Gfont $registro[capturo]</font></td>";
            echo "</tr>";
            $nRng++;

		}//fin while

		echo "</table> <br>";

	}//fin if

echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
echo "<tr><td align='center'><form name='form1' method='post' action='ordenesdxest.php?busca=$busca&Estudio=$Estudio'>";
echo "$Gfont Observaciones:&nbsp;";
//echo "$Gfont <strong>Observaciones:&nbsp;</strong>";
echo "<TEXTAREA NAME='Observaciones' cols='70' rows='3'>$He[observaciones]</TEXTAREA>";
echo Botones2();
echo "</td></tr>"; 
echo "</table> <br>";
echo "</form>";

echo "</body>";

echo "</html>";

mysql_close();
?>
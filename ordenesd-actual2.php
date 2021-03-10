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
  $Estudio2   = $_REQUEST[Estudio];
  $status    = $_REQUEST[status];
  if(!isset($_REQUEST[cambia])){$Cambia='No';}
	
  $Msj       = "";
  $Fecha     = date("Y-m-d");
  $Fechaest  = date("Y-m-d H:i:s");
  $Hora      = date("h:i:s");
  $Titulo    = "Detalle orden[$busca]";

  $link      = conectarse();

  $OrdenDef  = "otd.estudio";            //Orden de la tabla por default
  $OrdenD  = "proceso.orden";            //Orden de la tabla por default

  $Tabla     = "otd";
    
  if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo
        $lUp2   = mysql_query("UPDATE ot set observaciones='$_REQUEST[Observaciones]' WHERE orden='$busca'");
  }
  if($_REQUEST[op] == 'cb'){        //Para agregar uno nuevo
		$lUp    = mysql_query("UPDATE ot set fechae = '$_REQUEST[Fechae]', horae = '$_REQUEST[Horae]' WHERE orden='$busca'");
		$Cambia='No';
 	    $lUp2  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
		('$Fechaest','$Usr','Modifica fecha/hora Ot: $busca')");
  }

  $cSqlH     = "SELECT ot.orden,ot.fecha,ot.hora,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,
                ot.medico,med.nombrec,ot.status,ot.recibio,ot.institucion,ot.pagada,ot.observaciones,ot.horae,cli.fechan,ot.suc
                FROM ot,cli,med
                WHERE ot.cliente=cli.cliente AND ot.medico=med.medico AND ot.orden='$busca'";

  $cSqlD     = "SELECT otd.estudio,otd.status,est.descripcion,otd.precio,otd.descuento,est.muestras,otd.etiquetas,
  		        otd.capturo,est.depto,otd.recibeencaja,otd.uno,otd.dos,otd.tres,otd.cuatro,otd.cinco,otd.seis,otd.statustom,
				otd.obsest,otd.usrest,otd.fechaest,otd.etiquetas
                FROM otd,est
                WHERE otd.estudio=est.estudio AND otd.orden='$busca'";

  $CjaA      = mysql_query("SELECT sum(importe) FROM cja WHERE orden='$busca'");
  $Cja       = mysql_fetch_array($CjaA);

  $HeA       = mysql_query($cSqlH);
  $He        = mysql_fetch_array($HeA);
  
  if($_REQUEST[op]=='El'){
	if($Usr=='nazario' or $Usr=='Javier' or $Usr=='emilio'){
		$OtdA  = mysql_query("SELECT status FROM otd WHERE orden='$busca' AND estudio='$Estudio'");
		$Otd   = mysql_fetch_array($OtdA);
		
		$cSqlE = "DELETE FROM otd WHERE estudio='$_REQUEST[Estudio]' AND orden='$busca' limit 1";
		$SqA   = mysql_query($cSqlE);
		$OtdA  = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
		$Otd   = mysql_fetch_array($OtdA);
		$lUp   = mysql_query("UPDATE ot set importe='$Otd[0]' WHERE orden='$busca'");
		
		$lUp  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
		('$Fechaest','$Usr','Elima estudio $_REQUEST[Estudio] Ot: $busca')");
		
        $OtC     = mysql_query("select importe from ot where orden='$busca'",$link);
        $Otd      = mysql_fetch_array($OtC);
        $cSqlB   = mysql_query("select sum(importe) from cja where orden='$busca'",$link);
        $Abonado = mysql_fetch_array($cSqlB);
        
        if(($Abonado[0] + .5) >= $Otd[0] ){
           $lUp=mysql_query("update ot Set pagada='Si',fecpago='$Fecha' where orden='$busca'",$link);
        }else{
           $lUp=mysql_query("update ot Set pagada='No' where orden='$busca'",$link);
		}
		
	}	
  }elseif($_REQUEST[op]=="Ag"){

    $OtA = mysql_query("SELECT descuento FROM ot WHERE orden='$busca'");
    $Ot  = mysql_fetch_array($OtA);

    $LtA = mysql_query("SELECT lista FROM inst WHERE institucion='$He[institucion]'");
    $Lt=mysql_fetch_array($LtA);
    $Lista="lt".ltrim($Lt[lista]);
    $lUp=mysql_query("SELECT estudio,$Lista FROM est WHERE estudio='$_REQUEST[Estudio]' ");
    if($cCpo=mysql_fetch_array($lUp)){
       $Estudio=strtoupper($_REQUEST[Estudio]);
       $lUp=mysql_query("insert into otd (orden,estudio,precio,status,descuento) VALUES ('$busca','$Estudio','$cCpo[$Lista]','DEPTO','$Ot[descuento]')");
       $OtdA=mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
       $Otd=mysql_fetch_array($OtdA);
       $lUp=mysql_query("UPDATE ot set importe='$Otd[0]' WHERE orden='$busca'");
		$lUp  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
		('$Fechaest','$Usr','Agrega estudio $_REQUEST[Estudio] Ot: $busca')");
   }else{
       $Msj="El Estudio [$Estudio] no existe, favor de verificar";
   }
   
	$OtC     = mysql_query("select importe from ot where orden='$busca'",$link);
	$Otd      = mysql_fetch_array($OtC);
	$cSqlB   = mysql_query("select sum(importe) from cja where orden='$busca'",$link);
	$Abonado = mysql_fetch_array($cSqlB);
	
	if(($Abonado[0] + .5) >= $Otd[0] ){
		$lUp=mysql_query("update ot Set pagada='Si',fecpago='$Fecha' where orden='$busca'",$link);
	}else{
		$lUp=mysql_query("update ot Set pagada='No' where orden='$busca'",$link);
	}
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
  
  if($_REQUEST[op]=='pcs'){
	 $cSqlG     = "SELECT * FROM proceso
		WHERE proceso.orden='$busca' AND proceso.est='$_REQUEST[Estudio]'";
		
//	$total = mysql_num_rows(mysql_query("SELECT proceso.orden
//			FROM proceso
//			WHERE proceso.orden=$busca AND proceso.est='$Estudio2'"));
//		
//	if($total==0){
	 if(!$resG==mysql_query($cSqlG." ORDER BY ".$OrdenD)){
		$lUp = mysql_query("INSERT INTO proceso
			  (orden,est,insumo,cnt,intext,unidad,usr,fecha,pcs)
			  VALUES
			  ('$busca','$Estudio','$_REQUEST[insumo]','$_REQUEST[cnt]','$_REQUEST[intext]','$_REQUEST[unidad]',
			   '$Usr','$Fechaest','$_REQUEST[pcs]')");
	 }else{
   		$lUp  = mysql_query("UPDATE proceso SET orden='$busca',est='$Estudio',insumo='$_REQUEST[insumo]',cnt='$_REQUEST[cnt]',
			   intext='$_REQUEST[intext]',unidad=$_REQUEST[unidad]',usr='$Usr',fecha='$Fechaest',pcs='$_REQUEST[pcs]'
	            WHERE orden='$busca' and est='$Estudio2'"); 
	 }
  }

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';

require ("config.php");

echo "<html>";

echo "<head>";

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

function bisiesto($anio_actual){ 
	$bisiesto=false; 
	//probamos si el mes de febrero del año actual tiene 29 días 
	  if (checkdate(2,29,$anio_actual)){ 
			$bisiesto=true; 
	  } 
		return $bisiesto; 
} 

</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

headymenu($Titulo,0);

			$horafin= $He[horae];
			$horaini=$He[hora];
			$fechaInicial = $He[fecha];
			$fechaInicial2 = date('Y-m-d');
			$fechaActual = $He[fechae];
			$horaInicial = abs(strtotime($He[hora]));
			$horaActual = abs(strtotime($He[horae]));
			$horaInicial2 = abs(strtotime(date('H:i:s')));
			$Hora3=date("H:i:s");

			$diff = abs(strtotime($fechaActual) - strtotime($fechaInicial));
			$diff2 = abs(strtotime($fechaInicial2) - strtotime($fechaActual));
			
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*23));
			
			$years2 = floor($diff2 / (365*60*60*24));
			$months2 = floor(($diff2 - $years2 * 365*60*60*24) / (30*60*60*24));
			$days2 = floor(($diff2 - $years2 * 365*60*60*24 - $months2*30*60*60*24)/ (60*60*23));

			if($diff>0){
				if($horaInicial>$horaActual){
					$days=$days;
					$mensaje=' ';
				}else{
					$days=$days;
					$mensaje=' ';
				}
			}else{
				if($horaInicial>$horaActual){
					$days=$days;
					$mensaje='Fecha Invalida';
				}else{
					$days=$days;
					$mensaje=' ';
				}
			}
			
			if($fechaInicial2>$fechaActual){
					$days2=' ';
					$mensaje2=' ';
					$reshora=0; 
			}else{
				if($fechaInicial2==$fechaActual and $horaInicial2>$horaActual){
					$days2=' ';
					$mensaje2=' ';
					$reshora=0; 
				}else{
					$days2=$days2;
					$mensaje2=' ';
					if($horaInicial2>=$horaActual){
						$reshora= RestarHoras($Hora3,$He[horae]);
						$days2=$days2-1;
					}else{
						$reshora= RestarHoras($Hora3,$He[horae]);
					}
				}
			}
						
			if($days==0){
				$dias=' ';
				$dias2=' ';
			}else{
				$dias= $days;
				$dias2='dias';
			}
			
			if($days2<=0){
				$dias3=' ';
				$dias4=' ';
			}else{
				$dias3= $days2;
				$dias4='dias';
			}


  if($op=='sm'){
     $cSumA="SELECT sum($SumaCampo) FROM $Tabla,cli WHERE $Tabla.cliente=cli.cliente ".$cWhe;
     $cSum=mysql_query($cSumA);
     $Suma=mysql_fetch_array($cSum);
     $Funcion=" / $SumaCampo: ".number_format($Suma[0],"2");
  }
  
      $Fechanac  = $He[fechan];
      $Fecha   = date("Y-m-d");
	  $array_nacimiento = explode ( "-", $Fechanac ); 
	  $array_actual = explode ( "-", $Fecha ); 
	  $anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
	  $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
	  $dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 

		if ($dias < 0) 
		{ 
			--$meses; 
		
			//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
			switch ($array_actual[1]) { 
				   case 1:     $dias_mes_anterior=31; break; 
				   case 2:     $dias_mes_anterior=31; break; 
				   case 3:  	$dias_mes_anterior=28; break; 
//						if (bisiesto($array_actual[0])) 
//						{ 
//							$dias_mes_anterior=29; break; 
//						} else { 
//							$dias_mes_anterior=28; break; 
//						} 
				   case 4:     $dias_mes_anterior=31; break; 
				   case 5:     $dias_mes_anterior=30; break; 
				   case 6:     $dias_mes_anterior=31; break; 
				   case 7:     $dias_mes_anterior=30; break; 
				   case 8:     $dias_mes_anterior=31; break; 
				   case 9:     $dias_mes_anterior=31; break; 
				   case 10:     $dias_mes_anterior=30; break; 
				   case 11:     $dias_mes_anterior=31; break; 
				   case 12:     $dias_mes_anterior=30; break; 
			} 
		
			$dias=$dias + $dias_mes_anterior; 
		} 

		//ajuste de posible negativo en $meses 
		if ($meses < 0) 
		{ 
			--$anos; 
			$meses=$meses + 12; 
		} 

	$sucursal=$He[suc];
	
   echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";

   echo "<tr bgcolor ='#618fa9'>";

	if($Cambia=='No'){
		echo "<td> <form name='form5' method='post' action='ordenesd.php?cambia=SI&busca=$busca'>$Gfont <font color='#ffffff'>Cliente: $He[cliente] $He[5] <p> &nbsp; Edad:  $anos A&ntilde;os $meses Meses &nbsp;&nbsp; F. Nac.: $He[fechan]</td>
		<td>$Gfont <font color='#ffffff'>Fech/Hra: $He[fecha]&nbsp;".substr($He[hora],0,5)."&nbsp;&nbsp;&nbsp;
		Fech/entga: $He[fechae] &nbsp;".substr($He[horae],0,5)."&nbsp; <INPUT TYPE='SUBMIT' name='Edita' value='Edita'>
		</td><td>$Gfont <font color='#ffffff'> Pagada: $He[pagada]";
		 if($Usr=='nazario' or $Usr=='Javier' or $Usr=='emilio'){
			 echo "&nbsp; [<a class='pg' href='ingreso3.php?busca=$busca&pagina=$pagina'><b>*INGRESOS</b></a>]</td>";
		 }
		echo "</form>";
	}else{
		echo "<td> <form name='form5' method='post' action='ordenesd.php?op=cb&busca=$busca'> $Gfont <font color='#ffffff'>Cliente: $He[cliente] $He[5] <p> &nbsp; Edad:  $anos A&ntilde;os $meses Meses &nbsp;&nbsp; F. Nac.: $He[fechan]</td>
		<td>$Gfont <font color='#ffffff'>Fech/Hra: $He[fecha]&nbsp;".substr($He[hora],0,5)."&nbsp;&nbsp;&nbsp;
		Fech/entga: <input name='Fechae' type= 'text' size='10' value='$He[fechae]'> &nbsp; <input name='Horae' type= 'text' size='8' value='$He[horae]'>
		<INPUT TYPE='SUBMIT' name='Cambia' value='Cambia'></td><td>$Gfont <font color='#ffffff'> Pagada: $He[pagada]";
		 if($Usr=='nazario' or $Usr=='Javier' or $Usr=='emilio'){
			 echo "&nbsp; [<a class='pg' href='ingreso3.php?busca=$busca&pagina=$pagina'><b>*INGRESOS</b></a>]</td>";
		 }
		echo "</form>";
	}
//   echo "<td>$Gfont <font color='#ffffff'>Cliente: $He[cliente] $He[5] <p> &nbsp; Edad:  $anos A&ntilde;os $meses Meses &nbsp;&nbsp; F. Nac.: $He[fechan]</td>
//   <td>$Gfont <font color='#ffffff'>Fech/Hra: $He[fecha]&nbsp;".substr($He[hora],0,5)."&nbsp;&nbsp;&nbsp;
//	Fech/entrega: $He[fechae] &nbsp;".substr($He[horae],0,5)."&nbsp; </td><td>$Gfont <font color='#ffffff'> Pagada: $He[pagada]</td>";
//   echo "</tr>";

   echo "<tr bgcolor ='#E1E1E1'>";

   echo "<td>$Gfont Medico: $He[medico] $He[10] </td><td>$Gfont Importe: $ ".number_format($He[importe],"2")." &nbsp; &nbsp; Abonado: $ ".number_format($Cja[0],'2').
   "</td><td>$Gfont Saldo: ".number_format($He[importe]-$Cja[0],"2")." &nbsp; [<a class='pg' href='ingreso.php?busca=$busca&pagina=$pagina'>NVO/INGRESO</a>]</td>";


   echo "</tr>";

   echo "</table>";

   echo "<table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'><tr><td>$Gfont";

//      echo "<a class='pg' href=javascript:winuni('preanalitico.php?busca=$busca')>Cuestionario pre-analitico </a>";

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
        //echo "<th>$Gfont </font></th>";
        echo "<th>$Gfont <font size='1'>Estud</font></th>";
        echo "<th>$Gfont <font size='1'>Resul</font></th>";
        echo "<th>$Gfont <font size='1'>Descripcion</font></th>";
        echo "<th>$Gfont <font size='1'>#M</font></th>";
        echo "<th>$Gfont <font size='1'>Etiq</font></th>";
        echo "<td align='center'>$Gfont <b><a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=2&regis=1&status=TOMA/REALIZ'>Tma/Rea</b></a></td>";
        echo "<td align='center'>$Gfont <b><a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=2&regis=1&status=RECOLECCION'>Recol</b></a></td>";
        echo "<td align='center'>$Gfont <b><a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=2&Estudio=TODOS&status=PENDIENTE'>Pend</b></a></td>";
        echo "<th>$Gfont <font size='1'>Status</font></th>";
        echo "<th>$Gfont <font size='1'>Capturo</font></th>";
        echo "<th>$Gfont <font size='1'>Precio</font></th>";
        echo "<th>$Gfont <font size='1'>%Dto</font></th>";
        echo "<th>$Gfont <font size='1'>Importe</font></th>";
        echo "<th>$Gfont <font size='1'>Elim</font></th>";
		echo "<th>$Gfont <font size='1'>Fech/hra Ent_Rec</font></th>";
        echo "<th>$Gfont <font size='1'>Recib_Recep</font></th>";
        echo "<th>$Gfont <font size='1'>Obs</font></th>";

		  while($registro=mysql_fetch_array($res))		{

            $clnk=strtolower($registro[estudio]);

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            //echo "<td align='center'><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
            echo "<td><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'>$Gfont<font size='1'>$registro[estudio]</font></td>";
            if($He[status]=='Entregada'){
			  if($registro[depto] <> 2 ){
                 echo "<td align='center'><a class='pg' href=javascript:WinRes('estdeptoimp.php?clnk=$clnk&Orden=$He[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im&reimp=1')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
			  }else{	
		  		 echo "<td><a href=javascript:wingral('pdfradiologia.php?busca=$busca&Estudio=$registro[estudio]')><img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a></td> ";
			  }

            }else{
               echo "<td align='center'>$Gfont </td>";
            }
            echo "<td>$Gfont<font size='1'>$registro[descripcion]</font></td>";
            echo "<td>$Gfont $registro[muestras]</font></td>";
            echo "<td align='center'>$Gfont <font size='1'>$registro[etiquetas]<a href=javascript:Ventana('impeti.php?op=1&busca=".$busca."&Est=$registro[estudio]')><img src='lib/print.png' alt='Imprime' border='0'></a></td>";

			if($registro[statustom]=='TOMA/REALIZ'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($registro[statustom]==''){
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=TOMA/REALIZ'>OK</b></a></td>";
				}else{
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=TOMA/REALIZ'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }   
			if($registro[statustom]=='RECOLECCION'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($registro[statustom]==''){
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=RECOLECCION'>OK</b></a></td>";
				}else{
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&regis=1&status=RECOLECCION'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }   
			if($registro[statustom]=='PENDIENTE'){				
               echo "<td align='center'>$Gfont<img src='lib/slc.png'></td>";
            }else{
				if($registro[statustom]==''){
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&status=PENDIENTE'>OK</b></a></td>";
				}else{
               		echo "<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Estudio=$registro[estudio]&status=PENDIENTE'><img src='lib/iconfalse.gif'></b></a></td>";
				}
            }
			if($registro[status]=='PENDIENTE'){
				$Gfont5="<font color='#f5a371' size='1' face='Arial, Helvetica, sans-serif'>";
			}elseif($registro[status]=='CANCELADA'){
				$Gfont5="<font color='#e20909' size='1' face='Arial, Helvetica, sans-serif'>";
			}else{
				$Gfont5="<font size='1' face='Arial, Helvetica, sans-serif'>";
			}
   
            echo "<td>$Gfont5 <font size='1'>$registro[status]</font></td>";
            echo "<td>$Gfont $registro[capturo]</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio],"2")."</font></td>";
            //echo "<td align='right'>$Gfont ".number_format($registro[descuento],"2")."</font></td>";

            echo "<td align='center'>$Gfont <a class='pg' href='descuentotd.php?busca=$busca&Estudio=$registro[estudio]'>$registro[descuento]</a></td>";


            echo "<td align='right'>$Gfont ".number_format($registro[precio]*(1-$registro[descuento]/100),"2")."</font></td>";
            echo "<td align='center'><a href=ordenesd.php?op=El&busca=$busca&Estudio=$registro[estudio]&pagina=$pagina><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
		    echo "<td>$Gfont <font size='1'>$registro[cinco]</font></td>";
            echo "<td>$Gfont $registro[recibeencaja]</font></td>";
			if($registro[obsest]<>''){				
          		echo "<td align='center'>$Gfont<a class='pg' href=javascript:winuni2('obsest.php?Orden=$busca&Estudio=$registro[estudio]')><img src='lib/messageon.png' border='0'></a></td>";
            }else{
          		echo "<td align='center'>$Gfont<a class='pg' href=javascript:winuni2('obsest.php?Orden=$busca&Estudio=$registro[estudio]')><img src='lib/messageoff.png' border='0'></a></td>";
            }   
            echo "</tr>";
            $nRng++;

		}//fin while

		echo "</table> <br>";

	}//fin if
	
echo "<table align='left' width='80%' border='0'>";
echo "<tr><td>";
echo " &nbsp; <a class='pg' href='ordenescon.php'><img src='lib/regresa.jpg' border=0></a> ";
echo "</td><td>";
echo " &nbsp; &nbsp; <a class='pg' href=javascript:Ventana('impeti2.php?op=3&busca=$busca')>Etiquetas</a>";
echo "</td><td>";

if($Usr=='nazario' or $Usr=='Javier' or $Usr=='emilio'){
	echo "<form name='form2' method='post' action='ordenesd.php?op=Ag&busca=$busca'>";
	echo "<font color='#0000CC' size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong> &nbsp; &nbsp; Estudio</strong>:</font>";
	echo "<input name='Estudio' type='text' size='5'>";
	echo "<input type='hidden' name='pagina' value='$pagina'>";
	echo "<INPUT TYPE='SUBMIT' name='Ok' value='ok'>";
	echo "</form>";
	echo "</td><td>";
	echo "<font color='#FF0000'>$Msj</font>";
}
echo "</td></tr>";
echo "</table>";

echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
echo "<tr><td align='center'><form name='form1' method='post' action='ordenesd.php'>";
echo "$Gfont Observaciones:&nbsp;";
//echo "$Gfont <strong>Observaciones:&nbsp;</strong>";
echo "<TEXTAREA NAME='Observaciones' cols='70' rows='3'>$He[observaciones]</TEXTAREA>";
echo Botones2();
echo "</td></tr>"; 

echo "</table>";
echo "<br>";


// ++++++++++++++++++++ Tabla de insumos +++++++++++++++++++

  $cSqlE     = "SELECT otd.estudio as estudio,est.descripcion,estd.estudio as estudios,estd.producto,estd.cantidad,invl.descripcion,est.estpropio,invl.clave
                FROM otd,est,estd,invl
                WHERE otd.estudio=est.estudio AND est.estudio=estd.estudio and estd.producto=invl.clave and otd.orden='$busca'";

	echo "<table align='center' width='100%' cellpadding='0' cellspacing='0' border='0'>";
   if(!$res2=mysql_query($cSqlE." ORDER BY ".$OrdenDef)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/� limpia.</a></font>";
 		echo "</div>";
 	}else{
      $numeroRegistros=mysql_num_rows($res2);
    echo "<form name='form5' method='get' action='ordenesd.php'>";

		echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr height='25' bgcolor ='#336666'>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Estudio</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Insumo</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Cantidad</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Int/Ext</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Unidad/Maquila</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Proces</font></th>";
		
		while($registro2=mysql_fetch_array($res2)){
			$estud=$registro2[estudio];
			$registro3 = mysql_fetch_array(mysql_query("SELECT *
					FROM proceso
					WHERE orden='$busca' AND est='$registro2[estudio]'"));

//			  $registro3     = mysql_fetch_array(mysql_query('SELECT *
//                FROM proceso
//                WHERE proceso.orden=$busca AND proceso.est=$registro2[estudio]'));
//			$resF=mysql_query($cSqlF." ORDER BY ".$OrdenD);
//			$registro3=mysql_fetch_array($resF);

				
			$total = mysql_num_rows(mysql_query("SELECT *
					FROM proceso
					WHERE proceso.orden='$busca' AND proceso.est='$registro2[estudio]'"));
//				
			if($total==0){
//			 if(!$resF==mysql_query($cSqlF." ORDER BY ".$OrdenD)){
				
				$cantidad3=$registro2[cantidad];				
				$propio=$registro2[estpropio];
				if($propio=='S'){
					$intext='Interno';
				}else{
					$intext='Externo';
				}
				
				$unidad=$sucursal;
				$pcs="<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=$unidad&pcs=1'>OK - $total</b></a></td>";
	
			}else{
				 $oo1  = mysql_query("SELECT * FROM proceso WHERE orden='$busca' AND est='$registro2[estudio]'");
				 $registro3   = mysql_fetch_array($oo1);
				$cantidad3=$registro3[cnt];				
				$intext=$registro3[intext];
				$unidad=$registro3[unidad];
				if($registro3(pcs)=='1'){
					$pcs="<td align='center'><img src='lib/slc.png'></b></a></td>";
				}else{
					$pcs="<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=$unidad&pcs=1'>OK</b></a></td>";
				}
			}
			
			if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
	
			echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
			echo "<td>$Gfont<font size='1'><b>$registro2[estudio]</font></td>";
			echo "<td>$Gfont<font size='1'><b>$registro2[descripcion]</font></td>";
			echo "<td align='center'>$Gfont<font size='1'><b>$cantidad3</b></font></td>";
			
			if($intext=='Interno'){
				echo "<td align='center'>$Gfont <img src='lib/slc.png'>Interno / 
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=Externo&unidad=$unidad'> Externo / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=Mixto&unidad=$unidad'> Mixto </b></a></td>";
			}elseif($intext=='Externo'){
				echo "<td align='center'>$Gfont <a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=Interno&unidad=$unidad'>Interno / </a>
				<img src='lib/slc.png'> Externo / 
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=Mixto&unidad=$unidad'> Mixto </b></a></td>";
			}else{
				echo "<td align='center'>$Gfont <a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=Interno&unidad=$unidad'>Interno / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=Externo&unidad=$unidad'> Externo / </a>
				<img src='lib/slc.png'> Mixto </b></td>";
			}

			if($unidad=='1'){
				echo "<td align='center'>$Gfont <img src='lib/slc.png'>LCD / 
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=2'> OHF / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=3'> TPX / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=4'> RYS / </a>			
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=5'> EXT </a>				
				</b></td>";
			}elseif($unidad=='2'){
				echo "<td align='center'>$Gfont <a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=1'>LCD / </a>
				<img src='lib/slc.png'> OHF /
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=3'> TPX / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=4'> RYS / </a>			
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=5'> EXT </a>				
				</b></td>";
			}elseif($unidad=='3'){
				echo "<td align='center'>$Gfont <a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=1'>LCD / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=2'> OHF / </a>
				<img src='lib/slc.png'> TPX /
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=4'> RYS / </a>			
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=5'> EXT </a>				
				</b></td>";
			}elseif($unidad=='4'){
				echo "<td align='center'>$Gfont <a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=1'>LCD / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=2'> OHF / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=3'> TPX / </a>
				<img src='lib/slc.png'> RYS /
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=5'> EXT </a>				
				</b></td>";
			}elseif($unidad=='5'){
				echo "<td align='center'>$Gfont <a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=1'>LCD / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=2'> OHF / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=3'> TPX / </a>
				<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=pcs&Estudio=$registro2[estudio]&insumo=$registro2[clave]&cnt=$cantidad3&intext=$intext&unidad=4'> RYS / </a>			
				<img src='lib/slc.png'> EXT /
				</b></td>";
			}
			
			echo $pcs;

			echo "</tr>";
	   echo "</form>";

		}
		echo "</table> <br>";
	}
	

//++++++++++++ Tabla de tiempos ++++++++++++++++++++++++++++++




echo "<table align='center' width='100%' cellpadding='0' cellspacing='0' border='0'>";
echo "<th align='center'><font color='#FF0000' size='2' face='Arial, Helvetica, sans-serif'>$mensaje</font></th>";
echo "<th align='center'><font size='2' color='#990033'>Tiempo Restante: $dias3 $dias4 $reshora</font></th>";
   if(!$res=mysql_query($cSqlD." ORDER BY ".$OrdenDef)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/� limpia.</a></font>";
 		echo "</div>";
 	}else{
      $numeroRegistros=mysql_num_rows($res);

		echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
        echo "<tr height='25' bgcolor ='#336666'>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Estudio</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Descripcion</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Toma / Realiz / Recolecc</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Entrega a recep</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Tiempo Real</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Excedido</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Etiq</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Toma</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Capt</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Impr</font></th>";
        echo "<th>$Gfont <font size='1' color='#FFFFFF'>Recep</font></th>";

		while($registro=mysql_fetch_array($res))		{

            $clnk=strtolower($registro[estudio]);
			$fechatn=substr($registro[fechaest],0,10);		
			$horatn=substr($registro[fechaest],11);
			$horafin=$horatn;
			$horaini=$registro[hora];
			
		if($registro[etiquetas]>=1){
			$imagen1="OKShield.png";
		}else{	
			$imagen1="ErrorCircle.png";
		}
			$unoa=$registro[uno];
			$uno=substr($unoa,10);
			$esp="_";
			$uno.=$esp;
			$uno.=$unoa;

		if($registro[dos]<>'0000-00-00 00:00:00'){
			$imagen2="OKShield.png";
		}else{	
			$imagen2="ErrorCircle.png";
		}
			$dosa=$registro[dos];
			$dos=substr($dosa,10);
			$esp="_";
			$dos.=$esp;
			$dos.=$dosa;

		if($registro[tres]<>'0000-00-00 00:00:00'){
			$imagen3="OKShield.png";
		}else{	
			$imagen3="ErrorCircle.png";
		}
			$tresa=$registro[tres];
			$tres=substr($tresa,10);
			$esp="_";
			$tres.=$esp;
			$tres.=$tresa;

		if($registro[cuatro]<>'0000-00-00 00:00:00'){
			$imagen4="OKShield.png";
		}else{	
			$imagen4="ErrorCircle.png";
		}
			$cuatroa=$registro[cuatro];
			$cuatro=substr($cuatroa,10);
			$esp="_";
			$cuatro.=$esp;
			$cuatro.=$cuatroa;

		if($registro[cinco]<>'0000-00-00 00:00:00'){
			$imagen5="OKShield.png";
			$pendiente=" ";
			$Gfont3="<font size='1' face='Arial, Helvetica, sans-serif'>";
			$cincob=$registro[cinco];
		}else{	
			$imagen5="ErrorCircle.png";
			$pendiente="Pendiente";
			$Gfont3="<font color='#FF6600' size='1' face='Arial, Helvetica, sans-serif'>";
			$cincob=' ';
		}
			$cincoa=$registro[cinco];
			$cinco=substr($cincoa,11);


            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td>$Gfont<font size='1'><b>$registro[estudio]</font></td>";
            echo "<td>$Gfont<font size='1'><b>$registro[descripcion]</font></td>";
            echo "<td><font size='1'><b>$rg[statustom] &nbsp;&nbsp;&nbsp; $fechatn &nbsp; $horatn &nbsp;&nbsp; $registro[usrest] &nbsp;&nbsp; ".RestarHoras($horaini,$horatn)."</font></td>";
			echo "<th align='left'><font size='1' color='#0000CC'>&nbsp; $cincob </font></th>";
			if($registro[cinco]<>'0000-00-00 00:00:00'){
				echo "<th align='center'><font size='1' color='#990033'>".RestarHoras($horatn,$cinco)."</font></th>";
			}else{
				echo "<th align='left'><font size='2' color='#009900'>&nbsp;&nbsp;&nbsp;</font></th>";
			}
			if($registro[cinco]=='0000-00-00 00:00:00' and $reshora=='0'){
				$texcedido=date('H:i:s');
				echo "<th align='left'><font size='2' color='#FF0000'>".RestarHoras($He[horae],$texcedido)."</font></th>";
			}else{
				echo "<th align='left'><font size='2' color='#FF0000'>&nbsp;&nbsp;&nbsp;</font></th>";
			}
			echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen1 alt=$uno></font></th>";
			echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen2 alt=$dos></font></th>";
			echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen3 alt=$tres></font></th>";
			echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen4 alt=$cuatro></font></th>";
			echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'><img src=images/$imagen5 alt=$cinco></font></th>";
            echo "</tr>";
            $nRng++;

		}//fin while

		echo "</table> <br>";
	}
	

echo "</form>";

echo "</body>";

echo "</html>";

mysql_close();
?>
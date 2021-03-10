<?php

  session_start();

  require("lib/lib.php");

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
  $Titulo    = "Detalle orden[$busca]";

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
                WHERE otd.estudio=est.estudio AND otd.orden='$busca'";

  $CjaA      = mysql_query("SELECT sum(importe) FROM cja WHERE orden='$busca'");
  $Cja       = mysql_fetch_array($CjaA);

  $HeA       = mysql_query($cSqlH);
  $He        = mysql_fetch_array($HeA);
  
  if($_REQUEST[op]=='El' AND $Usr=='vidal'){

     $OtdA  = mysql_query("SELECT status FROM otd WHERE orden='$busca' AND estudio='$Estudio'");
     $Otd   = mysql_fetch_array($OtdA);

        $cSqlE = "DELETE FROM otd WHERE estudio='$_REQUEST[Estudio]' AND orden='$busca' limit 1";
        $SqA   = mysql_query($cSqlE);
        $OtdA  = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
        $Otd   = mysql_fetch_array($OtdA);
        $lUp   = mysql_query("UPDATE ot set importe='$Otd[0]' WHERE orden='$busca'");
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
   }else{
       $Msj="El Estudio [$Estudio] no existe, favor de verificar";
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
  }elseif($_REQUEST[op]=='2'){
	  if($_REQUEST[regis]=='1'){
		  			  
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
				
       		$Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
	          WHERE orden='$busca' AND usrest=''");
  	  }else{
		 $Up  = mysql_query("UPDATE otd SET fechaest='$Fechaest', usrest='$Usr', statustom='$status', status='$status'
	          WHERE orden='$busca' AND usrest=''");
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

</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

headymenu($Titulo,0);

  if($op=='sm'){
     $cSumA="SELECT sum($SumaCampo) FROM $Tabla,cli WHERE $Tabla.cliente=cli.cliente ".$cWhe;
     $cSum=mysql_query($cSumA);
     $Suma=mysql_fetch_array($cSum);
     $Funcion=" / $SumaCampo: ".number_format($Suma[0],"2");
  }

   echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";

   echo "<tr bgcolor ='#618fa9'>";

   echo "<td>$Gfont <font color='#ffffff'>Cliente: $He[cliente] $He[5]</td>
   <td>$Gfont <font color='#ffffff'>Fecha/Hora: $He[fecha]&nbsp; $He[hora]&nbsp;Fecha/entrega: $He[fechae] </td><td>$Gfont <font color='#ffffff'> Pagada: $He[pagada]</td>";

   echo "</tr>";

   echo "<tr bgcolor ='#E1E1E1'>";

   echo "<td>$Gfont Medico: $He[medico] $He[10] </td><td>$Gfont Importe: $ ".number_format($He[importe],"2")." &nbsp; &nbsp; Abonado: $ ".number_format($Cja[0],'2').
   "</td><td>$Gfont Saldo: ".number_format($He[importe]-$Cja[0],"2")." &nbsp; [<a class='pg' href='ingreso.php?busca=$busca&pagina=$pagina'>NVO/INGRESO</a>]</td>";


   echo "</tr>";

   echo "</table>";

   echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'><tr><td>$Gfont";

      echo "<a class='pg' href=javascript:winuni('preanalitico.php?busca=$busca')>Cuestionario pre-analitico </a>";

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
        echo "<th>$Gfont </font></th>";
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
            echo "<td align='center'><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
            echo "<td>$Gfont $registro[estudio]</font></td>";
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
            echo "<td>$Gfont <font size='1'>$registro[status]</font></td>";
            echo "<td>$Gfont $registro[capturo]</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio],"2")."</font></td>";
            //echo "<td align='right'>$Gfont ".number_format($registro[descuento],"2")."</font></td>";

            echo "<td align='center'>$Gfont <a class='pg' href='descuentotd.php?busca=$busca&Estudio=$registro[estudio]'>$registro[descuento]</a></td>";


            echo "<td align='right'>$Gfont ".number_format($registro[precio]*(1-$registro[descuento]/100),"2")."</font></td>";
            echo "<td align='center'><a href=ordenesd.php?op=El&busca=$busca&Estudio=$registro[estudio]&pagina=$pagina><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
		    echo "<td>$Gfont <font size='1'>$registro[cinco]</font></td>";
            echo "<td>$Gfont $registro[recibeencaja]</font></td>";
			if($registro[obsest]<>''){				
				echo "<td align='center'><a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Paciente&Obsentrega=$Obsentrega')><img src='lib/slc.png' border='0'></a></td>";
            }else{
          		echo "<td align='center'>$Gfont<a class='pg' href='ordenesd.php?busca=$busca&pagina=$pagina&op=1&Entregapac=$Entregapac&Estudio=TODOS&Recibepac=Paciente&Obsentrega=$Obsentrega'>OK</b></a></td>";
            }   
            echo "</tr>";
            $nRng++;

		}//fin while

		echo "</table> <br>";

	}//fin if

echo " &nbsp; <a class='pg' href='ordenes.php'><img src='lib/regresa.jpg' border=0></a> ";

echo " &nbsp; &nbsp; <a class='pg' href=javascript:Ventana('impeti2.php?op=3&busca=$busca')>Etiquetas</a>";
echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
echo "<tr><td align='center'><form name='form1' method='post' action='ordenesd.php'>";
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
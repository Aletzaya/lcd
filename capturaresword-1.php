<?php

  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  require("lib/kaplib.php");

  require ("config.php");

  $link		=	conectarse();

  $tamPag	=	15;

  $Usr		=	$check['uname'];
  $Depto	=	$_REQUEST[Depto];
  $busca	=	$_REQUEST[busca];
  $pagina	=	$_REQUEST[pagina];
  $estudio	=	$_REQUEST[estudio];

  if($_REQUEST[Boton] == "Aceptar" OR $_REQUEST[Boton] == "Aplicar" ){  //Guarda el Movto de Notas

     $Fecha = date("Y-m-d");
     $Hora  = date("H:i");

     $lUp  = mysql_query("UPDATE otd SET texto = '$_REQUEST[Texto]',status='TERMINADA', letra = '$_REQUEST[Letra]',
     		 medico = '$_REQUEST[Medico]',tres = '$Fecha $Hora', lugar='4'
     		 WHERE orden='$busca' AND estudio='$estudio' limit 1");

	 if($_REQUEST[Boton] == 'Aceptar'){

        echo "<script language='javascript'>setTimeout('self.close();',100)</script>";

     }

  }

  $Tabla	= "ot";

  $Titulo	= "Estudio por departamento";

  $EstA		= mysql_query("SELECT descripcion,proceso,formato,respradiologia FROM est WHERE estudio='$estudio'");

  $Est		= mysql_fetch_array($EstA);

  $OtA		= mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,ot.observaciones,
  			  inst.nombre,cli.sexo,cli.nombrec,cli.fechan,med.nombrec as nombremed,otd.texto,otd.letra,
  			  otd.medico as medicores
   			  FROM ot,inst,cli,med,otd
      	      WHERE  ot.orden='$busca' AND ot.cliente=cli.cliente AND ot.institucion = inst.institucion AND ot.medico=med.medico
  			  AND otd.orden=ot.orden AND otd.estudio='$estudio'");

  $Ot		= mysql_fetch_array($OtA);

  $lAg		= $Nombre<>$Cpo[nombre];

  $Fecha	= date("Y-m-d");

  require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF">
<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}
function WinRes(url){
   window.open(url,"WinRes","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=900,height=500,left=30,top=80")
}

</script>

    <table align='center' border='2' width='50%' cellspacing= '3' cellpading = '0'><tr><td background='lib/bar.jpg' align='center'><font size='2' face='verdana' color='#6D6D6D'><?php echo "[ $estudio - $Est[descripcion] ]"; ?></font></td></tr></table>

    <div align='center'><font size='2' face='verdana' color='#6D6D6D'>
     <font color="#0066FF" size="2" >No.Orden : <?php echo $busca;?></font> &nbsp;
     <font color='#0066FF' size='2' >Fecha: </font>&nbsp;<?php echo $Ot[fecha];?> &nbsp;
     <font color='#0066FF' size='2'>Hora :</FONT> &nbsp;<?php echo $Ot[hora];?> &nbsp;
     <font color='#0066FF' size='2'>Fec/Ent :</FONT>&nbsp; <?php echo $Ot[fechae]?> &nbsp;
     <font color='#0066FF' size='2'>Tpo/servicio :</FONT><?php echo $Ot[servicio];?>
     </div><p align='center'>
     <font color='#0066FF' size='2' >Paciente : </FONT><?php echo $Ot[cliente]." - ".substr($Ot[nombrec],0,17);?>&nbsp;&nbsp;&nbsp;&nbsp;
     <font color='#0066FF' size='2'>Inst.:</FONT> <?php echo $Ot[nombre];?> &nbsp; &nbsp;
     <font color='#0066FF' size='2'>Med.: </FONT> <?php echo $Ot[medico]." - ".substr($Ot[nombremed],0,15);?>
     </p>
     <div align='center'>
     <font color='#0066FF' size='2' >Sexo : </FONT><?php echo $Ot[sexo];?> &nbsp; &nbsp;
     <font color='#0066FF' size='2'>Edad :</FONT> <?php echo $Fecha-$Ot[fechan]; ?>
     A&ntilde;os </div>


<table border='1' align='center' width='90%' border='1' cellpadding='1' cellspacing='0' >

  <tr>
    <td><font face='verdana' color='#0066FF' size='2' >Diagnostico medico: </font></td>
    <td><?php echo $Ot[diagmedico];?> &nbsp; </td>
  </tr><tr>
    <td><font face='verdana' color='#0066FF' size='2' >Observaciones: </font></td>
    <td><?php echo $Ot[observaciones]; ?> &nbsp; </td>
   </tr>
</table>

<br><br>

<?php

echo "<table align='center' width='50%' border='2' cellspacing= '3' cellpading = '0'><tr><td background='lib/bar.jpg' align='center'><font size='2' face='verdana' color='#6D6D6D' >Pre-analiticos</font></td></tr></table>";

$OtpreA = mysql_query("SELECT cue.pregunta,otpre.nota,cue.id,cue.tipo
          FROM otpre,cue
          WHERE otpre.orden='$busca' AND otpre.estudio='$estudio' AND cue.id=otpre.pregunta",$link);

    echo "<table align='center' width='100%'>";
    echo "<tr><td align='right'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $Sec=1;
    while($registro=mysql_fetch_array($OtpreA)){
 		echo "<tr><td align='right'>$Gfont $registro[0] $Gfon </td><td>&nbsp;</td>";
        echo "<td>";
        $Campo="Nota".ltrim($Sec);
     	  if($registro[3]=="Si/No"){
   	          echo "<select name='$Campo'>";
                echo "<option value='Si'>Si</option>";
                echo "<option value='No'>No</option>";
                echo "<option selected>$registro[1]</option>";
                echo "</select>";
        }elseif($registro[3]=="Fecha"){
   	          echo "<input name='$Campo' value ='$registro[1]' type='text' size='8' >";
        }else{
   	          echo "<input name='$Campo' value ='$registro[1]' type='text' size='80' >";
                //echo "<TEXTAREA NAME='$Campo' cols='50' rows='3' >$registro[1]</TEXTAREA>";
        }
   	  $Sec=$Sec+1;
   	  echo "</td></tr>";
    }
    echo "</table>";
    echo "<input type='hidden' name='estudio' value=$estudio>";
    echo "<input type='hidden' name='Depto' value=$Depto>";
    echo "<input type='hidden' name='op' value=pr>";

    //echo Botones();

    echo "</form><br>";

    echo "<table align='center' width='50%' border='2' cellspacing= '3' cellpading = '0'>";
    echo "<tr><td background='lib/bar.jpg' align='center'>";
    echo "<font size='2' face='verdana' color='#6D6D6D'>Resultados</font></td></tr></table><br>";

    echo "<form name='form3' method='get' action='capturaresword.php'>";

          if($Ot[texto]==''){
             //$Formato = $Est[formato];
             $Formato = $Est[respradiologia];
          }else{
             $Formato = $Ot[texto];
          }

          echo "<textarea name='Texto' rows='20' cols='80' style='width: 100%'>";
          echo $Formato;
          echo "</textarea>";


          echo "<input type='hidden' name='estudio' value=$estudio>";
          echo "<input type='hidden' name='Depto' value=$Depto>";

          echo "<input type='hidden' name='op' value='gu'>"; // Resultdos

		  echo "<div>";

		  echo "Tama&ntilde;o de letra:";

   	      echo "<select name='Letra'>";
          echo "<option value='7'>7</option>";
          echo "<option value='8'>8</option>";
          echo "<option value='9'>9</option>";
          echo "<option value='10'>10</option>";
          echo "<option value='11'>11</option>";
          echo "<option value='12'>12</option>";
          echo "<option value='$Ot[letra]' selected>$Ot[letra]</option>";
          echo "</select> &nbsp; Medico firmante: ";

		  $MedA = mysql_query("SELECT nombre,id
          		  FROM medi
                  ");
          echo "<select name='Medico'>";
          while($Med = mysql_fetch_array($MedA)){
                echo "<option value='$Med[id]'>$Med[nombre]</option>";
	            if($Ot[medicores] == $Med[id]){
	              $Dsp1 = $Med[nombre];
	            }
          }
          echo "<option selected='$Ot[medicores]'>$Dsp1</option>";
          echo "</select> &nbsp; ";


		  echo "<input type='submit' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='Boton' value='Aceptar'>";

		  echo "&nbsp; &nbsp; &nbsp; &nbsp;";

		  echo "<input type='submit' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='Boton' value='Aplicar'>";

		  echo "&nbsp; &nbsp; &nbsp; &nbsp;";

		  echo " &nbsp; <a href=javascript:wingral('pdfradiologia.php?busca=$busca&Estudio=$estudio')><img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a> &nbsp; ";

		  echo " &nbsp; <img src='lib/print.png' alt='Imprimir' border='0' onClick='window.print()'> &nbsp; ";

		  echo "<input type='hidden' name='pagina' value=$pagina >";

		  echo "<input type='hidden' name='busca' value=$busca >";

		 echo "</div>";


    echo "</form>";

	echo "<div align='center'><b>Historial clinico</b></div>";

	echo "<table align='center' width='95%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr height='25' background='lib/bartit.gif'>";
    echo "<th>$Gfont No.orden</th>";
    echo "<th>$Gfont Fecha</font></th>";
    echo "<th>$Gfont Estudio</font></th>";
    echo "<th>$Gfont Descripcion</font></th>";
    echo "<th>$Gfont Resultado</font></th>";
    echo "</tr>";

    $OtdA = mysql_query("SELECT ot.orden, ot.fecha,otd.estudio,est.descripcion,est.depto
			FROM ot,otd LEFT JOIN est ON otd.estudio=est.estudio
			WHERE ot.cliente = '$Ot[cliente]' AND ot.orden=otd.orden");

	while($reg=mysql_fetch_array($OtdA)){

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            $clnk=strtolower($reg[estudio]);

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            //echo "<td align='center'><a href='ordenesde.php?busca=$busca&Estudio=$registro[estudio]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";
            echo "<td>$Gfont $reg[fecha]</font></td>";
            echo "<td>$Gfont $reg[orden]</font></td>";
            echo "<td>$Gfont $reg[estudio]</font></td>";
            echo "<td>$Gfont $reg[descripcion]</font></td>";
		    if($registro[depto] <> 2 ){
                 echo "<td align='center'><a class='pg' href=javascript:WinRes('estdeptoimp.php?clnk=$clnk&Orden=$He[orden]&Estudio=$registro[estudio]&Depto=TERMINADA&op=im&reimp=1')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
			}else{
                 echo "<td align='center'><a class='pg' href='pdfradiologia.php?busca=$He[orden]&Estudio=$registro[estudio]'><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
			}
            echo "</tr>";
            $nRng++;

	}//fin while

	echo "</table> <br>";

    echo "</td>";

    echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";


mysql_close();

?>
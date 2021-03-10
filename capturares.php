<?php

  session_start();

  require("lib/kaplib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr     = $check['uname'];

  $link    = conectarse();

  $tamPag  = 15;

  $pagina  = $_REQUEST[pagina];

  $busca   = $_REQUEST[busca];

  $op      = $_REQUEST[op];

  $Status  = $_REQUEST[Status];

  $estudio = $_REQUEST[estudio];

  $Depto   = $_REQUEST[Depto];

  if($op=="gu"){  //Guarda el Movto de Notas

     $OtdA = mysql_query("SELECT dos,lugar FROM otd WHERE orden='$busca' AND estudio='$estudio' LIMIT 1");
 	  $Otd  = mysql_fetch_array($OtdA);
	     
     $Fecha = date("Y-m-d");
     $Hora  = date("H:i");

     if(substr($Otd[dos],0,4)=='0000'){
     
	     if($Otd[lugar] <= '3'){	         
           $lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
                  WHERE orden='$busca' and estudio='$estudio' limit 1");                     
        }else{           
           $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
                  WHERE orden='$busca' and estudio='$estudio' limit 1");           
		  }	
     }

     //if($Status<>"DEPTO"){		//vERSION anterior lo comente para que siempre lo haga;
     echo "<script language='javascript'>setTimeout('self.close();',100)</script>";

     //header("Location: estdepto.php?pagina=$pagina&Depto=$Depto");

  }elseif($op=="pr"){     // Pre-analiticos
     $Campo=$Nota1;
  	  $Sec=1;
      $OtpreA=mysql_query("SELECT cue.id from otpre,cue where otpre.orden='$busca' and cue.id=otpre.pregunta and otpre.estudio='$estudio'",$link);
 	  while($registro=mysql_fetch_array($OtpreA)){
          $Nota="Nota".ltrim($Sec);
          //$Resp=$$Nota;
          $Resp=$_REQUEST[$Nota];    	//Le pongo $ por k es una variable de variable

          $lUp=mysql_query("UPDATE otpre SET nota='$Resp' where orden='$busca' and pregunta='$registro[0]' and otpre.estudio='$estudio'",$link);
          $Sec=$Sec+1;
     }
     $lUp=mysql_query("UPDATE ot SET status='DEPTO' where orden='$busca' limit 1",$link);
     header("Location: estdepto.php?pagina=$pagina&Depto=$Depto");
     //header("Location: ordenespre.php?pagina=$pagina");
  }elseif($op=="up"){   // Cambai fecha de etrega
    $lUp=mysql_query("UPDATE ot SET fechae = '$_REQUEST[Fechae]',horae = '$_REQUEST[Horae]' WHERE orden='$busca'",$link);
  }
  
  $Tabla   = "ot";
  $Titulo  = "Estudio por departamento";
  
  $OtA      = mysql_query("SELECT * from ot where orden='$busca' ",$link);
  $Ot       = mysql_fetch_array($OtA);
  
  $CliA     = mysql_query("SELECT nombrec,sexo,fechan from cli where cliente=$Ot[cliente]",$link);
  $Cli      = mysql_fetch_array($CliA);

  $MedA     = mysql_query("SELECT nombrec from med where medico='$Ot[medico]'",$link);
  $Med      = mysql_fetch_array($MedA);

  $InsA     = mysql_query("SELECT nombre from inst where institucion=$Ot[institucion]",$link);
  $Ins      = mysql_fetch_array($InsA);

  $cSql     = "SELECT * from $Tabla where (orden= '$busca')";

  $lAg      = $Nombre<>$Cpo[nombre];
  $Fecha    = date("Y-m-d");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<script language="JavaScript1.2">

function cFocus(){
  document.form1.Nombre.focus();
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}
</script>
<body bgcolor="#FFFFFF">

<?php

  //headymenu($Titulo,1);

  echo "<table align='center' width='100%' border='0'>";
  //echo "<tr><td align='center' width='10%'><a class='pg' href='estdepto.php?Depto=$Depto'>Regresa<br><img src='lib/regresa.jpg' border='0'></a></td>";
  echo "<tr><td align='center' width='10%'><a class='pg' href='javascript:window.close()'>Regresa<br><img src='lib/regresa.jpg' border='0'></a></td>";
  echo "<td align='left'>$Gfont  <b>No.Orden : $busca </b>&nbsp; &nbsp; &nbsp; Fec/Inicio: &nbsp; $Ot[fecha] &nbsp; &nbsp; &nbsp; Hora: &nbsp;$Ot[hora] &nbsp; ";
  echo " Sexo: $Cli[sexo] &nbsp; &nbsp; &nbsp; Tpo/servicio : $Ot[servicio]<br>";

  echo " &nbsp; Paciente: $Ot[cliente] -  $Cli[nombrec] &nbsp; &nbsp; &nbsp; Inst.: $Ins[nombre]<br>";
  echo " &nbsp; Med.: $Ot[medico] - $Med[nombrec] &nbsp; &nbsp; &nbsp; Fec.Nac.: $Cli[fechan]";
  //echo "<p align='center'>Sexo: $Cli[sexo] &nbsp; &nbsp; &nbsp; Edad: $Fecha - $Cli[fechan] &nbsp; A&ntilde;os </p>";
  echo "</td></tr>";
  echo "</table>";

      echo "<div align='right'><form name='form9' method='post' action='capturares.php'><font size='-1'>";
      echo " &nbsp; Cambia Fecha de entrega: &nbsp; ";
      echo "<input type=text name='Fechae' value ='$Ot[fechae]' maxlength='10' size='8'>";
      echo " &nbsp;Hra: ";
      echo "<input type='text' name='Horae' value ='$Ot[horae]' maxlength='8' size='5'>&nbsp;";
      echo "<input type='hidden' name='busca' value='$busca'>";
      echo "<input type='hidden' name='pagina' value='$pagina'>";
      echo "<input type='hidden' name='Depto' value='$Depto'>";
      echo "<input type='hidden' name='estudio' value='$estudio'>";      
      echo "<input type='hidden' name='op' value='up'>";      
      echo "<input type='submit' name='Boton' value='Enviar'>";
      echo "&nbsp; &nbsp; &nbsp; </form></div>";

 echo "<table align='center' bgcolor=$Gbarra width='90%' border='0'>";
 echo "<tr><td align='center'>$Gfont <font color='#ffffff'>";
 echo "Diagnostico medico</font></font><br>";
 echo "<TEXTAREA NAME='Diagmedico' cols='50' rows='3' >$Ot[diagmedico]</TEXTAREA>";
 echo "</td>";
 echo "<td width='1%'></td>";
 echo "<td align='center'>$Gfont <font color='#ffffff'> ";
 echo "Observaciones</font></font><br>";
 echo "<TEXTAREA NAME='Observaciones' cols='50' rows='3' >$Ot[observaciones]</TEXTAREA>";
 echo "</td></tr>";
 echo "</table>";

 echo "<table align='center' width='100%' border='0'>";
 echo "<tr><td width='49%' align='center'>$Gfont <b> Cuestionario pre-analitico</b></td>";
 echo "<td background='lib/ba.gif'>&nbsp;</td>";
 echo "<td width='50%' align='center'>$Gfont <b> Status actual</b></td>";
 echo "</tr>";
 echo "</table>";

echo "<table width='98%' cellpadding='0' cellspacing='0' border='1'>";
   echo "<tr>";
   echo "<td align='center' width='49%'>$Gfont";

         $OtpreA=mysql_query("SELECT cue.pregunta,otpre.nota,cue.id,cue.tipo from otpre,cue where otpre.orden='$busca' and otpre.estudio='$estudio' and cue.id=otpre.pregunta",$link);
         echo "<form name='form3' method='get' action='capturares.php'>";
	     $Sec=1;
 	     while($registro=mysql_fetch_array($OtpreA)){
 			  echo "<p>";
              $Campo="Nota".ltrim($Sec);
   	          if($registro[3]=="Si/No"){
   	             echo "$registro[0] : ";
   	             echo "<SELECT name='$Campo'>";
                 echo "<option value='Si'>Si</option>";
                 echo "<option value='No'>No</option>";
                 echo "<option SELECTed>$registro[1]</option>";
                 echo "</SELECT>";
   	          }elseif($registro[3]=="Fecha"){
   	             echo "$registro[0] : ";
   	             echo "<input name='$Campo' value ='$registro[1]' type='text' size='8' >";
   	          }else{
   	             echo "$registro[0]<br>";
                 echo "<TEXTAREA NAME='$Campo' cols='40' rows='3' >$registro[1]</TEXTAREA>";
              }
      	      $Sec=$Sec+1;
      	      echo "</p>";
        }

        echo "<input type='hidden' name='estudio' value=$estudio>";
        echo "<input type='hidden' name='Depto' value=$Depto>";
        echo "<input type='hidden' name='op' value=pr>";

        echo Botones();

      echo "</form>";
      echo "</font>";
  echo "</td>";

  echo "<td bgcolor=$Gbarra>&nbsp;</td>";

  echo "<td width='50%' valign='top' align='center'>$Gfont ";

      $EstA = mysql_query("SELECT descripcion,proceso from est where estudio='$estudio'");
      $Est  = mysql_fetch_array($EstA);
      
      $OtdA = mysql_query("SELECT status from otd where estudio='$estudio' and orden='$busca'");
      $Otd  = mysql_fetch_array($OtdA);
      
      echo "<form name='form2' method='get' action='capturares.php?op=gu'>";
         echo "<p>Estudio :$estudio ".$Est[0].'</p>';
         echo "<p>Status del Estudio : ";

		echo "<SELECT name='Status'>";
        echo "<option value='$Est[1]'>$Est[1]</option>";
        echo "<option SELECTed value='$Otd[0]'>$Otd[0]</option>";
    	echo "</SELECT>";

      echo "<input type='hidden' name='estudio' value=$estudio>";
      echo "<input type='hidden' name='Depto' value=$Depto>";
      echo "<input type='hidden' name='op' value=gu>";

      echo Botones();

      echo "</form></font>";
    echo "</td>";
   echo "</tr>";

   echo "</table>";

mysql_close();

?>
</body>
</html>
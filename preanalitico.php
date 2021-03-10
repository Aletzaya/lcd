<?php

  session_start();

  require("lib/kaplib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr     = $check['uname'];

  $link    = conectarse();

  $tamPag  = 15;

  $busca   = $_REQUEST[busca];

  $op      = $_REQUEST[op];

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
  
  	  $lUp    = mysql_query("DELETE FROM otpre WHERE orden='$busca' AND estudio=''");
  	  
     $CueA   = mysql_query("SELECT cue.id 
               FROM cue WHERE modalidad='General'
               ORDER BY cue.id");

 	  while($rg = mysql_fetch_array($CueA)){

          $Campo = "Nota".ltrim($rg[id]);

          $Resp  = $_REQUEST[$Campo];    	//Le pongo $ por k es una variable de variable

          $lUp   = mysql_query("INSERT INTO otpre 
                   SET orden='$busca',pregunta=$rg[id], nota='$Resp'");

     }

     $Fecha = date("Y-m-d");
     $Hora  = date("H:i");
     $OtdA  = mysql_query("SELECT dos,lugar,estudio FROM otd WHERE orden='$busca'");
     
 	  while ($Otd  = mysql_fetch_array($OtdA)){	   
 	     $Est  = $Otd[estudio];  
     	  if(substr($Otd[dos],0,4)=='0000'){     
	        if($Otd[lugar] <= '3'){	         
              $lUp = mysql_query("UPDATE otd SET status='RESUL', lugar='3', dos='$Fecha $Hora' 
                     WHERE orden='$busca' and estudio='$Est' limit 1");                     
           }else{           
              $lUp = mysql_query("UPDATE otd SET status='RESUL', dos='$Fecha $Hora' 
                     WHERE orden='$busca' AND estudio='$Est' limit 1");           
		     }
		 }  	
     }

     echo "<script language='javascript'>setTimeout('self.close();',100)</script>";

  }elseif($op=="up"){   // Cambai fecha de etrega

    $lUp = mysql_query("UPDATE ot SET fechae = '$_REQUEST[Fechae]',horae = '$_REQUEST[Horae]' 
           WHERE orden='$busca'");

  }
  
  $Tabla   = "ot";
  
  $OtA      = mysql_query("SELECT * FROM ot WHERE orden='$busca' ");
  $Ot       = mysql_fetch_array($OtA);
  
  $CliA     = mysql_query("SELECT nombrec,sexo,fechan FROM cli WHERE cliente=$Ot[cliente]");
  $Cli      = mysql_fetch_array($CliA);

  $MedA     = mysql_query("SELECT nombrec FROM med WHERE medico='$Ot[medico]'");
  $Med      = mysql_fetch_array($MedA);

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
  echo "<td align='left'>$Gfont  <b>No.Orden : $busca </b>&nbsp; &nbsp; &nbsp; Fec/Inicio: &nbsp; $Ot[fecha] &nbsp; &nbsp; &nbsp; Hora: &nbsp;$Ot[hora] <br>";
  echo " &nbsp; Sexo: $Cli[sexo] &nbsp;  Tpo/servicio : $Ot[servicio]<br>";

  echo " &nbsp; Paciente: $Ot[cliente] -  $Cli[nombrec] <br>";
  echo " &nbsp; Med.: $Ot[medico] - $Med[nombrec] &nbsp; &nbsp; &nbsp; Fec.Nac.: $Cli[fechan]";
  //echo "<p align='center'>Sexo: $Cli[sexo] &nbsp; &nbsp; &nbsp; Edad: $Fecha - $Cli[fechan] &nbsp; A&ntilde;os </p>";
  echo "</td></tr>";
  echo "</table>";

      echo "<form name='form9' method='post' action='capturares.php'><font size='-1'>";
      echo "<p align='center'>";
      echo "$Gfont Cambia Fecha de entrega: &nbsp; ";
      echo "<input type=text name='Fechae' value ='$Ot[fechae]' maxlength='10' size='8'>";
      echo " &nbsp;Hra: ";
      echo "<input type='text' name='Horae' value ='$Ot[horae]' maxlength='8' size='5'>&nbsp;";
      echo "<input type='hidden' name='busca' value='$busca'>";
      echo "<input type='hidden' name='op' value='up'>";      
      echo "<input type='submit' name='Boton' value='Enviar'>";
      echo "</p>";
      echo "</form>";

 echo "<table align='center' bgcolor=$Gbarra width='85%' border='0'>";
 echo "<tr><td align='center'>$Gfont <font color='#ffffff'>";
 echo "Diagnostico medico</font></font><br>";
 echo "<TEXTAREA NAME='Diagmedico' cols='45' rows='3' >$Ot[diagmedico]</TEXTAREA>";
 echo "</td>";
 echo "<td width='1%'></td>";
 echo "<td align='center'>$Gfont <font color='#ffffff'> ";
 echo "Observaciones</font></font><br>";
 echo "<TEXTAREA NAME='Observaciones' cols='45' rows='3' >$Ot[observaciones]</TEXTAREA>";
 echo "</td></tr>";
 echo "</table>";

 echo "<form name='form3' method='get' action='preanalitico.php'>";

 echo "<div align='center'>$Gfont <b> Cuestionario pre-analitico</div>";

 echo "<table align='center' width='100%' border='0' cellpadding=0 cellspacing=0 bgcolor='#FFFFFF'>";

		  $lBd    = false;
        $OtpreA = mysql_query("SELECT cue.pregunta,otpre.nota,cue.id,cue.tipo 
                  FROM otpre,cue 
                  WHERE otpre.orden='$busca' AND otpre.pregunta=cue.id AND cue.modalidad='General'
                  ORDER BY cue.id");

        if(mysql_num_rows($OtpreA)<=5){		//Osea que aun no existe nada en la bd;

           $OtpreA = mysql_query("SELECT cue.pregunta,cue.id,cue.tipo 
                     FROM cue 
                     WHERE cue.modalidad='General'
                     ORDER BY cue.id");
			  $lBd    = true;                                   	
        }
                                    
 	     while($rg=mysql_fetch_array($OtpreA)){
 	     
              $Campo="Nota".ltrim($rg[id]);

   	        if($rg[tipo]=="Si/No"){ 	     
 			     	  echo "<tr><td height='40' width='100'>&nbsp;</td><td align='left'>$Gfont ".ucwords(strtolower($rg[pregunta]))." &nbsp; ";
   	           echo "<SELECT name='$Campo'>";
                 echo "<option value='Si'>Si</option>";
                 echo "<option value='No'>No</option>";
                 if(!$lBd){
                    echo "<option selected value='$rg[nota]'>$rg[nota]</option>";
                 }else{
                    echo "<option selected value='No'>No</option>";                 
                 }   
                 echo "</SELECT>";
                 echo "</td></tr>";
   	        }elseif($rg[tipo]=="Fecha"){
   	           echo "<tr><td width='100'>&nbsp;</td><td>$Gfont ".ucwords(strtolower($rg[pregunta]));
   	           echo "<input name='$Campo' value ='$rg[nota]' type='text' size='8'  maxlength='12'>";
   	           echo "</td></tr>";
   	        }elseif($rg[tipo]=="Texto"){
   	           echo "<tr><td width='100'>&nbsp;</td><td valign='bottom'>$Gfont ".ucwords(strtolower($rg[pregunta]))."</td></tr>";   	        
   	           echo "<tr><td width='100'>&nbsp;</td><td valign='top'>$Gfont <input name='$Campo' value ='$rg[nota]' type='text' size='30' maxlength='40'></td></tr>";
   	        }else{
   	           echo "<tr><td width='100'>&nbsp;</td><td>$Gfont ".ucwords(strtolower($rg[pregunta]))."</td></tr>";   	        
                 echo "<tr><td width='100'>&nbsp;</td><td><TEXTAREA NAME='$Campo' value='$rg[nota]' cols='40' rows='3' >$rg[1]</TEXTAREA></td></tr>";
              }
        }

 echo "</table>";

 echo "<input type='hidden' name='$busca' value='$busca'>";
 echo "<input type='hidden' name='op' value=pr>";
 
echo Botones();

echo "</form>";

mysql_close();

?>
</body>
</html>
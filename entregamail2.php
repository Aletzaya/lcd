<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  require ("config.php");

  include_once ("auth.php"); 

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link        = conectarse();
  $Orden       = $_REQUEST[Orden];
  $Cliente     = $_REQUEST[cliente];
  $mailpac     = $_REQUEST[mailpac];
  $mailmed     = $_REQUEST[mailmed];
  $mailinst 	= $_REQUEST[mailinst];
  $mailotro 	= $_REQUEST[mailotro];
  $Op        = $_REQUEST[Op];
  $Msj        = $_REQUEST[Msj];
  $Estudio        = $_REQUEST[Estudio];
  $alterno        = $_REQUEST[alterno];
  $Reg        = $_REQUEST[Reg];
  $Archivo        = $_REQUEST[Archivo];
  $Usr       = $check['uname'];
  $Fechaest  = date("Y-m-d H:i:s");


  if($_REQUEST[Reg]<>''){

    $ImagenesB  = mysql_fetch_array(mysql_query("SELECT * FROM envimg WHERE envimg.orden='$Orden' and envimg.reg='$Reg' and envimg.archivo='$Archivo'"));

    if($_REQUEST[Reg]==$ImagenesB[reg]){


      $cSqlE = mysql_query("DELETE FROM envimg WHERE envimg.orden='$Orden' and envimg.reg='$Reg' and envimg.archivo='$Archivo' limit 1");


    }else{

      if($_REQUEST[Reg]=='Todoimg'){

          $cSqlE = mysql_query("DELETE FROM envimg WHERE envimg.orden='$Orden'");

          $ImgB = mysql_query("SELECT archivo,idnvo FROM estudiospdf WHERE id='$Orden' and usrelim=''");

          $Reg2=1;

          while ($rowb = mysql_fetch_array($ImgB)) {

                $lUp    = mysql_query("INSERT INTO envimg (orden,reg,archivo) VALUES ('$Orden','$Reg2','$rowb[archivo]')");

                $Reg2++;

          }  

      }elseif($_REQUEST[Reg]=='Todoimgquitar'){

          $cSqlE = mysql_query("DELETE FROM envimg WHERE envimg.orden='$Orden'");

      }else{

          $lUp    = mysql_query("INSERT INTO envimg (orden,reg,archivo) VALUES ('$Orden','$Reg','$Archivo')");

      }

    }

  }

  //************ PDF

if($_REQUEST[Reg3]<>''){

  $ImagenesB  = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Archivo'"));

  if($_REQUEST[Archivo]==$ImagenesB[archivo]){

    $cSqlE = mysql_query("DELETE FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Archivo' limit 1");

    unlink("estudiospdf/".$Archivo);

  }else{

    if($_REQUEST[Reg3]=='Todoarch'){

        $cSqlE = mysql_query("DELETE FROM enviarc WHERE enviarc.orden='$Orden'");

        $Sql=mysql_query("SELECT * FROM otd WHERE otd.orden='$Orden' and otd.status='TERMINADA'");

        $Reg2=1;

        while ($rowb = mysql_fetch_array($Sql)) {

              $Rarchivo=$Orden."_".$rowb[estudio].".pdf";

              $lUp    = mysql_query("INSERT INTO enviarc (orden,reg,archivo) VALUES ('$Orden','$Reg2','$Rarchivo')");

              $Reg2++;

        }  

    }elseif($_REQUEST[Reg3]=='Todoarchquitar'){

        $cSqlE = mysql_query("DELETE FROM enviarc WHERE enviarc.orden='$Orden'");

        $Sql=mysql_query("SELECT * FROM otd WHERE otd.orden='$Orden' and otd.status='TERMINADA'");

        while ($rowb = mysql_fetch_array($Sql)) {

              $Rarchivo=$Orden."_".$rowb[estudio].".pdf";

              unlink("estudiospdf/".$Rarchivo);

        } 

    }else{ //////////////////*****************   OJO

        $lUp    = mysql_query("INSERT INTO enviarc (orden,reg,archivo,estudio) VALUES ('$Orden','1','$Archivo','$_REQUEST[Estudio]')");

        $Sql=mysql_query("SELECT depto FROM est WHERE est.estudio='$_REQUEST[Estudio]'");

        $rowc = mysql_fetch_array($Sql);

        if($rowc[depto]<>2){

            header("Location: resultapdfenvioadj.php?Orden=$Orden&Estudio=$_REQUEST[Estudio]&alterno=$_REQUEST[alterno]");

        }else{

            header("Location: resultapdfenvioadjrx.php?Orden=$Orden&Estudio=$_REQUEST[Estudio]&alterno=$_REQUEST[alterno]");

        }

    }

  }

}

  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='1'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";
  
  switch ($Op) 
    {
    case 'eps':
		  $Up  = mysql_query("UPDATE ot SET entemailpac='1'
				WHERE orden='$Orden'"); 
        break;
    case 'epn':
		  $Up  = mysql_query("UPDATE ot SET entemailpac='0'
				WHERE orden='$Orden'"); 
        break;
		
    case 'ems':
		  $Up  = mysql_query("UPDATE ot SET entemailmed='1'
				WHERE orden='$Orden'"); 
        break;
    case 'emn':
		  $Up  = mysql_query("UPDATE ot SET entemailmed='0'
				WHERE orden='$Orden'"); 
        break;
		
    case 'eis':
		  $Up  = mysql_query("UPDATE ot SET entemailinst='1'
				WHERE orden='$Orden'"); 
        break;
    case 'ein':
		  $Up  = mysql_query("UPDATE ot SET entemailinst='0'
				WHERE orden='$Orden'"); 
        break;
		
	  case 'actpac':
		  $Up  = mysql_query("UPDATE cli SET cli.mail='$mailpac'
				  WHERE cli.cliente='$Cliente'"); 
		    break;

//    case 'Envio':

 
//        break;

    }

  echo "<html>";

  echo "<head>";

  echo "<title>$Titulo</title>";

  echo "</head>";

  echo "<body bgcolor='#FFFFFF' onload='cFocus()'>";          
  
//  echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

  echo "<font size='3'><p align='center'><b>Entrega de resultados E-mail</b></p>";

  $HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.entemailpac,ot.entemailmed,
  ot.entemailinst
  FROM ot,cli
  WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");
	 
  $He   = mysql_fetch_array($HeA);        		

  echo "<div align='center'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
  echo "<div align='center'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";
			  
  echo "<table align='center' width='68%' border='1' cellspacing='0' cellpadding='1'>";
  echo "<tr height='25' bgcolor='#CCCCCC'>";
  echo "<td align='center'>$Gfont <font size='2'><b>&nbsp; </b></td>";
  echo "<td align='center'>$Gfont <font size='2'><b>E-mail </b></td>";
  echo "<td align='center'>$Gfont <font size='2'><b>Actualiza </b></td>";
  echo "<td align='center'>$Gfont <font size='2'><b>Envio </b></td>";  
  echo "</tr>";  

  $OtdC  = mysql_query("SELECT cli.cliente,cli.nombrec,cli.mail
			  FROM ot,cli
			  WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");            

  $rgp=mysql_fetch_array($OtdC);

  $OtdM  = mysql_query("SELECT med.nombrec,med.mail
			  FROM ot,med
			  WHERE ot.orden='$Orden' AND ot.medico=med.medico");            

  $rgm=mysql_fetch_array($OtdM);

  $OtdI  = mysql_query("SELECT inst.nombre,inst.mail
			  FROM ot,inst
			  WHERE ot.orden='$Orden' AND ot.institucion=inst.institucion");            

  $rgi=mysql_fetch_array($OtdI);
  
  echo "<tr>";
  echo "<form name='form2' method='post' action='entregamail2.php?Orden=$Orden&Op=actpac'>";		    
  echo "<td align='left'>Paciente:</td>";
  echo "<input type='hidden' name='cliente' value='$rgp[cliente]'>";
  echo "<td align='center'><input name='mailpac' value='$rgp[mail]' type='email' size='60'></td>";
  echo "<td align='center'><INPUT TYPE='SUBMIT' name='Actualiza' value='Actualiza'></td>";
  echo "</form>";
  if($He[entemailpac]=='1'){
	   echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Op=epn'><img src='lib/slc.png'></a></td>";
  }else{
	   echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Op=eps'>NO</a></td>";
  }
  
  echo "</tr><tr>";
  echo "<td align='left'>Medico:</td>";
  echo "<td align='center'>$rgm[mail]</td>";
  echo "<td align='center'>&nbsp;</td>";
  if($He[entemailmed]=='1'){
	   echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Op=emn'><img src='lib/slc.png'></a></td>";
  }else{
	   echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Op=ems'>NO</a></td>";
  }
  
  echo "</tr><tr>";
  echo "<td align='left'>Institucion:</td>";
  echo "<td align='center'>$rgi[mail]</td>";
  echo "<td align='center'>&nbsp;</td>";
  if($He[entemailinst]=='1'){
	   echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Op=ein'><img src='lib/slc.png'></a></td>";
  }else{
	   echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Op=eis'>NO</a></td>";
  }

  echo "</tr>";        
  echo "</table><br>";  

    $Sql=mysql_query("SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,ot.entemailpac,ot.entemailmed,ot.entemailinst,
  otd.status,otd.etiquetas,est.muestras,ot.institucion,otd.capturo,otd.recibeencaja,otd.cuatro,
  otd.recibeencaja,est.depto,ot.suc,otd.obsest,ot.observaciones,otd.alterno,otd.fechaest,otd.fr,otd.usrvalida,otd.lugar
  FROM ot,est,otd,cli
  WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.orden='$Orden'");

  //$Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja FROM otd,est WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");
    
  echo "<table align='center' width='68%' border='1' cellspacing='0' cellpadding='0'>";
  echo "<tr height='25' bgcolor='#CCCCCC'>";
  echo "<td align='center'>$Gfont2 Estudio</td>";
  echo "<td align='center'>$Gfont2 Descripcion</td>";
/*
  if($_REQUEST[Reg3]<>'Todoarch'){

    echo "<td align='center'>$Gfont2<a href='entregamail2.php?Orden=$Orden&Reg3=Todoarch&Archivo=At'>Adjuntar/arch</a></font></td>";

  }else{

    echo "<td align='center'>$Gfont2<a href='entregamail2.php?Orden=$Orden&Reg3=Todoarchquitar&Archivo=Qt'>Quita/arch</a></font></td>";

  }
*/
  echo "<td align='center'>$Gfont2 Adjuntar</font></td>";

  echo "<td align='center'>$Gfont2 Resultado</td>";   
  echo "<td align='center'>$Gfont2 Envio</td>";   
  echo "<td align='center'>$Gfont2 Fecha/hora</td>";
  echo "<td align='center'>$Gfont2 Envio</td>";  
  echo "</tr>";              

  while($rg=mysql_fetch_array($Sql)){

    $clnk=strtolower($rg[estudio]);
    $Estudio2=strtoupper($rg[estudio]);
        
    $Lugar = $rg[lugar];  

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
    echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
    echo "<td>$Gfont $rg[estudio]</td>";
    echo "<td>$Gfont $rg[descripcion]</td>";

      $Rarchivo=$Orden."_".$Estudio2.".pdf";

      if($rg[status]<>'' and $rg[usrvalida]<>''){


          if($rg[depto] <> 2 ){
              //********* adjuntar
              $ImagenesD  = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Rarchivo'"));

               if(isset($ImagenesD[archivo])){

                  $Seleccionado2="<IMG SRC='images/Si.png' border='0' width='18'> - Adjunto";

               }else{

                  $Seleccionado2="Adjuntar";

               }

              echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Reg3=A&Archivo=$Rarchivo&alterno=$rg[alterno]&Estudio=$rg[estudio]'>$Seleccionado2<font></a></td>"; 

              //********* 

              if($He[institucion]=='259'){

                echo "<td align='center'><a href=javascript:wingral('resultapdfmc.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><font size='1'><img src='pdfenv.png' alt='pdf' border='0'></font></a></td>";


              }else{

                echo "<td align='center'><a href=javascript:wingral('resultapdf3.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><font size='1'><img src='pdfenv.png' alt='pdf' border='0'></font></a></td>";

              }


          }else{    
              //********* adjuntar
              $ImagenesD  = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Rarchivo'"));

               if(isset($ImagenesD[archivo])){

                  $Seleccionado2="<IMG SRC='images/Si.png' border='0' width='18'> - Adjunto";

               }else{

                  $Seleccionado2="Adjuntar";

               }

              echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Reg3=A&Archivo=$Rarchivo&Estudio=$rg[estudio]'>$Seleccionado2<font></a></td>"; 

              //********* 
               echo "<td align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a></td> ";
          }

      }else{

          if($rg[depto] <> 2 ){

              echo "<td align='center'>-</td>";

              echo "<td align='center'>-</td>";

          }else{ 

                if($rg[status]=='TERMINADA'){
                  //********* adjuntar
                  $ImagenesD  = mysql_fetch_array(mysql_query("SELECT * FROM enviarc WHERE enviarc.orden='$Orden' and enviarc.archivo='$Rarchivo'"));

                   if(isset($ImagenesD[archivo])){

                      $Seleccionado2="<IMG SRC='images/Si.png' border='0' width='18'> - Adjunto";

                   }else{

                      $Seleccionado2="Adjuntar";

                   }

                  echo "<td align='center'><a class='pg' href='entregamail2.php?Orden=$Orden&Reg3=A&Archivo=$Rarchivo&Estudio=$rg[estudio]'>$Seleccionado2<font></a></td>"; 

                  //********* 
                  echo "<td align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><img src='pdfenv.png' title='Vista preliminar' border='0' ></a></td> ";

               }else{

                  echo "<td align='center'>-</td>";
                  echo "<td align='center'>-</td>";

               }
          }

//        echo "<td align='center'>-</td>";

      }

    $reenviado='Enviar';
    $cons="SELECT * FROM logenvio WHERE logenvio.orden='$Orden' and logenvio.estudio='$rg[estudio]' order by id desc";
    $reg=mysql_query($cons);
    if(!$regenvio=mysql_fetch_array($reg)){

          $reenviado='Enviar';

    }else{

          $reenviado='Reenviar';

    }

    if($rg[depto] == 2 ){

      echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='resultapdfenvio3.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&alterno=$rg[alterno]&correo=$rgp[mail]&correom=$rgm[mail]&correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'>$reenviado</a></td>";

    }else{    

      if($He[institucion]=='259'){

         echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='resultapdfenviomc.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&alterno=$rg[alterno]&correo=$rgp[mail]&correom=$rgm[mail]&correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'>$reenviado</a></td>";

      }else{

         echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='resultapdfenvio2.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&alterno=$rg[alterno]&correo=$rgp[mail]&correom=$rgm[mail]&correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'>$reenviado</a></td>";
     
      }

    }     
     
      echo "<td align='center'>$Gfont2 $regenvio[fecha]</td>";
      echo "<td align='center'>$Gfont2 $regenvio[usr]</td>";
      echo "</tr>";
      $nRng++;

  }

  echo "</table>"; 

  echo "<table align='center' width='68%' border='0' cellspacing='0' cellpadding='1'>";
  echo "<tr height='25' border='0'>";
  echo "<td align='center'></td>";
  echo "<td align='center'></td>";
  echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='enviocorreoadj.php?Orden=$Orden&Correo=$rgp[mail]&Correom=$rgm[mail]&Correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'><font size='2'><b>Envio de resultados Adjuntos<b></font></a></td>";
  echo "<td align='center'></td>";   
  echo "<td align='center'></td>";   
  echo "<td align='center'></td>";
  echo "<td align='center'></td>";  
  echo "</tr>";

  echo "</table>"; 

  echo "<div align='center'>"; 

  echo "<br>";

  echo "<font color='#990000' size='+2'><b>$Msj</b></font>";

  echo "</div>";



  echo "<div align='center'>"; 

  echo "<br>";

  echo "<font size='4'><b>Imagenes de los Estudios</b></font>";

  echo "</div>";

  echo "<table width='90%' border='0' align='center' cellpadding='2' cellspacing='1' bgcolor='#FFFFFF'>";  

  echo "<tr align='center'><td>";

  echo "<font size='2'><a href=javascript:wingral('displayestudioslcdimgenv.php?busca=$Orden')>Visor de Imagenes</a></font>";

  echo "</td></tr>";

  echo "</table>"; 

  echo "<table width='90%' border='0' align='center' cellpadding='2' cellspacing='1' bgcolor='#FFFFFF'>";  

  echo "<tr align='left'><td>";

  if($_REQUEST[Reg]<>'Todoimg'){

    echo "<font size='2'><a href='entregamail2.php?Orden=$Orden&Reg=Todoimg'>Adjuntar todas las imagenes</a></font>";

  }else{

    echo "<font size='2'><a href='entregamail2.php?Orden=$Orden&Reg=Todoimgquitar'>Quitar todas las imagenes</a></font>";

  }

  

  echo "</td></tr>";

  echo "</table>"; 

echo "<table width='90%' border='0' align='center' cellpadding='2' cellspacing='1' bgcolor='#FFFFFF' style='border-collapse: collapse; border: 1px solid #999;'>";  

echo "<tr>";

$R=0;
$ImgA = mysql_query("SELECT archivo,idnvo FROM estudiospdf WHERE id='$Orden' and usrelim=''");

while ($row = mysql_fetch_array($ImgA)) {   
     $Pos   = strrpos($row[archivo],".");
     $cExt  = strtoupper(substr($row[archivo],$Pos+1,4));                             
     $foto  = $row[archivo];   
     $R++; 

    $ImagenesC  = mysql_fetch_array(mysql_query("SELECT * FROM envimg WHERE envimg.orden='$Orden' and envimg.reg='$R'"));

     if(isset($ImagenesC[reg])){

        $Seleccionado="<IMG SRC='images/Si.png' border='0' width='18'>";

     }else{

        $Seleccionado=" ";

     }

    if( ($R % 2) > 0 ){$Fdo=$Gbarra;}    //El resto de la division;

    if($cExt=='PDF'){

      echo "<td align='center' onMouseOver=this.style.backgroundColor='$Fdo';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#FFFFFF';><a class='pg' href='entregamail2.php?Orden=$Orden&Reg=$R&Archivo=$row[archivo]'><img src='pdfenv.png' title='Vista preliminar' border='0' width='40'>$Seleccionado<br>$Gfont $R - $row[archivo] <font></a></td>"; 


    }else{

      echo "<td align='center' onMouseOver=this.style.backgroundColor='$Fdo';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#FFFFFF';><a class='pg' href='entregamail2.php?Orden=$Orden&Reg=$R&Archivo=$row[archivo]'><IMG SRC='estudios/$foto' border='0' width='70'>$Seleccionado<br>$Gfont $R - $row[archivo] <font></a></td>"; 

    }


      if($R == 5 or $R==10 or $R==15 or $R==20 or $R==25 or $R==30 or $R==35 or $R==40 or $R==45 or $R==50 or $R==55 or $R==60 or $R==65 or $R==70 or $R==75 or $R==80 or $R==85 or $R==90 or $R==95 or $R==100 or $R==105 or $R==110 or $R==115 or $R==120 or $R==125 or $R==130 or $R==135 or $R==140 or $R==145 or $R==150 or $R==155 or $R==160 or $R==165 or $R==170 or $R==175 or $R==180 or $R==185 or $R==190 or $R==195 or $R==200 or $R==205 or $R==210 or $R==215 or $R==220 or $R==225 or $R==230 or $R==235 or $R==240 or $R==245 or $R==250 or $R==255 or $R==260 or $R==265 or $R==270 or $R==275 or $R==280 or $R==285 or $R==290 or $R==295){
        echo "</tr>";
        echo "<tr>";
      }

}    

echo "</tr>";

echo "</table><br>";


echo "<table width='90%' border='0' align='center'>";  

echo "<tr align='center'><td>";

echo "$Gfont <a class='pg' href='enviocorreoimg.php?Orden=$Orden&Correo=$rgp[mail]&Correom=$rgm[mail]&Correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'><font size='2'><b>Envio de Imagenes<b></font></a>";

echo "</td></tr>";

echo "<tr align='right'><td>";

echo "$Gfont <a class='pg' href='enviocorreotodo.php?Orden=$Orden&Correo=$rgp[mail]&Correom=$rgm[mail]&Correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'><font size='3'><b>Envio de Archivos Adjuntos<b></font></a>";

echo "</td></tr>";

echo "</table>";                            

  echo "<br>";

  echo "<br>";

  echo "<div align='center'>"; 

  echo "<a href=javascript:wingral('logenvio.php?busca=$Orden')><font size='2'> *** Envios ***</a>";

  echo "</div>";

  echo "<br>";

  echo "<br>";

  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?> 
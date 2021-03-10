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
  $Enviado        = $_REQUEST[Enviado];
  $Usr       = $check['uname'];
  $Fechaest  = date("Y-m-d H:i:s");
  
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
  echo "<td align='center'>$Gfont2 Resultado</td>";   
  echo "<td align='center'>$Gfont2 Envio</td>";   
  echo "<td align='center'>$Gfont2 Fecha/hora</td>";
  echo "<td align='center'>$Gfont2 Envio</td>";  
  echo "</tr>";              

  while($rg=mysql_fetch_array($Sql)){

    $clnk=strtolower($rg[estudio]);
        
    $Lugar = $rg[lugar];  

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
    echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
    echo "<td>$Gfont $rg[estudio]</td>";
    echo "<td>$Gfont $rg[descripcion]</td>";
      if($rg[capturo]<>'' and $rg[usrvalida]<>''){

          if($rg[depto] <> 2 ){
              echo "<td align='center'><a href=javascript:wingral('resultapdf3.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im&alterno=$rg[alterno]')><font size='1'><img src='pdfenv.png' alt='pdf' border='0'></font></a></td>";
          }else{    //Radiologia
               //echo "<td align='center'><a class='pg' href='pdfradiologia.php?busca=$He[orden]&Estudio=$registro[estudio]'><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
               echo "<td align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><img src='lib/Pdf.gif' title='Vista preliminar' border='0' ></a></td> ";
          }

      }else{

          if($rg[depto] <> 2 ){

              echo "<td align='center'>-</td>";

          }else{ 

                if($rg[status]=='TERMINADA'){

                  echo "<td align='center'><a href=javascript:wingral('pdfradiologia3.php?busca=$rg[orden]&Estudio=$rg[estudio]')><img src='pdfenv.png' title='Vista preliminar' border='0' ></a></td> ";

               }else{

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


      echo "<td align='center'>$Gfont &nbsp; <a class='pg' href='resultapdfenvio2.php?clnk=$clnk&Orden=$rg[orden]&Estudio=$rg[estudio]&alterno=$rg[alterno]&correo=$rgp[mail]&correom=$rgm[mail]&correoi=$rgi[mail]&entemailpac=$He[entemailpac]&entemailmed=$He[entemailmed]&entemailinst=$He[entemailinst]'>$reenviado</a></td>";

    }     
     
      echo "<td align='center'>$Gfont2 $regenvio[fecha]</td>";
      echo "<td align='center'>$Gfont2 $regenvio[usr]</td>";
      echo "</tr>";
      $nRng++;

  }               

  echo "</table>"; 

  echo "<div align='center'>"; 

  echo "<br>";

  echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logenvio.php?busca=$Orden')><font size='2'> *** Envios ***</a>  ";

  echo "</div>";

  echo "<div align='center'>"; 

  echo "<br>";

  echo "<font color='#990000' size='+2'><b>$Msj</b></font>";

  echo "</div>";
		
  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?> 
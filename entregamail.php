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

  echo "<div align='left'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
  echo "<div align='left'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";
			  
  echo "<table align='left' width='68%' border='1' cellspacing='0' cellpadding='1'>";
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
  echo "<form name='form2' method='post' action='entregamail.php?Orden=$Orden&Op=actpac'>";		    
  echo "<td align='left'>Paciente:</td>";
  echo "<input type='hidden' name='cliente' value='$rgp[cliente]'>";
  echo "<td align='center'><input name='mailpac' value='$rgp[mail]' type='text' size='60'></td>";
  echo "<td align='center'><INPUT TYPE='SUBMIT' name='Actualiza' value='Actualiza'></td>";
  echo "</form>";
  if($He[entemailpac]=='1'){
	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=epn'><img src='lib/slc.png'></a></td>";
  }else{
	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=eps'>NO</a></td>";
  }
  
  echo "</tr><tr>";
  echo "<td align='left'>Medico:</td>";
  echo "<td align='center'>$rgm[mail]</td>";
  echo "<td align='center'>&nbsp;</td>";
  if($He[entemailmed]=='1'){
	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=emn'><img src='lib/slc.png'></a></td>";
  }else{
	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=ems'>NO</a></td>";
  }
  
  echo "</tr><tr>";
  echo "<td align='left'>Institucion:</td>";
  echo "<td align='center'>$rgi[mail]</td>";
  echo "<td align='center'>&nbsp;</td>";
  if($He[entemailinst]=='1'){
	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=ein'><img src='lib/slc.png'></a></td>";
  }else{
	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=eis'>NO</a></td>";
  }

//  echo "</tr><tr>";
//  echo "<form name='form3' method='post' action='entregamail.php?Orden=$Orden&Op=actotro&mailotro=$mailotro'>";		    
//  echo "<td align='left'>Otro:</td>";
//  echo "<td align='center'><input name='mailotro' value='$rgo[mailotro]' type='text' size='60'></td>";
//  echo "<td align='center'><INPUT TYPE='SUBMIT' name='Actualiza' value='Actualiza'></td>";
//  echo "</form>";
//  if($He[entemailotro]=='1'){
//	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=eon'><img src='lib/slc.png'></a></td>";
//  }else{
//	   echo "<td align='center'><a class='pg' href='entregamail.php?Orden=$Orden&Op=eos'>NO</a></td>";
//  }
//
  echo "</tr>";  
		
  echo "</body>";
  
  echo "</html>";
  
  mysql_close();
  
?> 
<?php

  session_start();

  require("lib/lib.php");

  $link=conectarse();

  $FechaI=$_REQUEST[FechaI];

  $FechaF=$_REQUEST[FechaF];
  
  $Ins   = $_REQUEST[Institucion];

  if (!isset($FechaI)){

      $FechaF=date("Y-m-d");

      $FechaI=date("Y-m-")."01";

  }
  
  $lU    = mysql_query("DELETE FROM rep");

  if($Ins == "*" OR $Ins==''){
   
     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, med.locconsultorio, count(*) as cantidad
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico
     GROUP BY med.estado,med.munconsultorio");
          			    
     $Titulo = "Clientes por entidad federativa del $FechaI al $FechaF";
			    
  }else{

     $OtA = mysql_query("SELECT med.estado, med.munconsultorio, med.locconsultorio, count(*) as cantidad
     FROM med, ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.medico=med.medico AND ot.institucion='$Ins'
     GROUP BY med.estado,med.munconsultorio");
   
     $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Ins'");
     $cIns  = mysql_fetch_array($cInsA);

     $Titulo = "Clientes por entidad federativa del $FechaI al $FechaF Institucion: $cIns[alias]";

  }

  
  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#ffffff'> &nbsp; ";

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php

  echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

  headymenu($Titulo,0);

  $Ini = 0+substr($FechaI,5,2);
  $Fin = 0+substr($FechaF,5,2);
  
  echo "<br>";

  echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=2 bgcolor='#FFFFFF'>";
  echo "<tr height='21' background='lib/bartit.gif' >"; 
  echo "<td>$Gfont Estado</td>";
  echo "<td>$Gfont Municipio</td>";
  echo "<td>$Gfont Colonia</td>";
  echo "<td align='center'>$Gfont Cantidad</td>";
  echo "</tr>";

  
  while($rg = mysql_fetch_array($OtA)){

      if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		echo "<td>$Gfont $rg[estado]</td>";
		echo "<td>$Gfont $rg[munconsultorio]</td>";
		echo "<td>$Gfont $rg[locconsultorio]</td>";
		echo "<td align='right'>$Gfont $rg[cantidad]</td>";
		echo "</tr>";

      $nCnt  += $rg[cantidad];
      $nRng++;
		
  }

  echo "<tr>";
  echo "<td>$Gfont </td>";
  echo "<td>$Gfont </td>";
  echo "<td>$Gfont Total ---> </td>";
  echo "<td align='right'>$Gfont ".number_format($nCnt,"0")."</td>";
  echo "</tr>";

  echo "</table>";

  echo "<table align='center' width='98%' border='0'>";

  echo "<tr><td width='3%'><a href='menu.php'><img src='lib/regresa.jpg' border='0'></a>";

  echo "</td><td>$Gfont ";

  echo "<form name='form1' method='post' action='repentidadmed.php'>";

  echo "Institucion: ";
  echo "<SELECT name='Institucion'>";
  $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
  while($Ins=mysql_fetch_array($InsA)){
        echo "<option value='$Ins[0]'>$Ins[1]</option>";  
  }      
  echo "<option value='*'>*, todas</option>";  
  echo "</select> &nbsp; ";

  echo " Fecha Inicial: <input type='text' name='FechaI' value='$FechaI' size='9' maxlength='10'>";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

  echo " Fecha Final: <input type='text' name='FechaF' value='$FechaF' size='9' maxlength='10'> ";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
 
  echo "<input type='submit' name='Boton' value='Enviar'>"; 

echo "</font></td><td>";

echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";

echo "</tr></table>";

mysql_close();

?>

</body>

</html>
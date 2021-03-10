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

  if($Ins == "*"){
   
     $UsrA = mysql_query("SELECT recepcionista FROM ot WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' GROUP BY recepcionista");
     while($Cpo = mysql_fetch_array($UsrA)) {
 		  $lUp = mysql_query("INSERT INTO rep (clave) VALUES ('$Cpo[recepcionista]')");	
  	  }

     $OtA =  mysql_query("SELECT ot.recepcionista, fecha, count( * ) AS cantidad, sum( ot.importe ) AS importe
		   	 FROM ot
			    WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF'
			    GROUP BY recepcionista, fecha");
     			    
     $Titulo = "Apertura de ordenes de recepcion del $FechaI al $FechaF";
			    
  }else{
   
    $UsrA = mysql_query("SELECT recepcionista FROM ot 
            WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.institucion='$Ins' 
            GROUP BY recepcionista");
    while($Cpo = mysql_fetch_array($UsrA)) {
 		  $lUp = mysql_query("INSERT INTO rep (clave) VALUES ('$Cpo[recepcionista]')");	
  	 }
     $OtA =  mysql_query("SELECT ot.recepcionista, fecha, count( * ) AS cantidad, sum( ot.importe ) AS importe
		   	 FROM ot
			    WHERE ot.fecha BETWEEN '$FechaI' AND '$FechaF' AND ot.institucion='$Ins'
			    GROUP BY recepcionista, fecha");

     $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Ins'");
     $cIns  = mysql_fetch_array($cInsA);

     $Titulo = "Apertura de ordenes de recepcion del $FechaI al $FechaF Institucion: $cIns[alias]";

  }

  
  $Usr = "xx";		   
   
  while($Cpo = mysql_fetch_array($OtA)) {
  	
		$Dia   = substr($Cpo[fecha],8,2);
  	   $Campo = "p".$Dia;
  	   
		$lUp   = mysql_query("UPDATE rep SET $Campo = '$Cpo[cantidad]' WHERE clave = '$Cpo[recepcionista]'");  			    

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
  echo "<tr height='21' background='lib/bartit.gif' ><td>$Gfont Recepcionista</td>";
  echo "<td align='right'>$Gfont 01</td><td align='right'>$Gfont 02</td><td align='right'>$Gfont 03</td><td align='right'>$Gfont 04</td><td align='right'>$Gfont 05</td>";
  echo "<td align='right'>$Gfont 06</td><td align='right'>$Gfont 07</td><td align='right'>$Gfont 08</td><td align='right'>$Gfont 09</td><td align='right'>$Gfont 10</td>";
  echo "<td align='right'>$Gfont 11</td><td align='right'>$Gfont 12</td><td align='right'>$Gfont 13</td><td align='right'>$Gfont 14</td><td align='right'>$Gfont 15</td>";
  echo "<td align='right'>$Gfont 16</td><td align='right'>$Gfont 17</td><td align='right'>$Gfont 18</td><td align='right'>$Gfont 19</td><td align='right'>$Gfont 20</td>";
  echo "<td align='right'>$Gfont 21</td><td align='right'>$Gfont 22</td><td align='right'>$Gfont 23</td><td align='right'>$Gfont 24</td><td align='right'>$Gfont 25</td>";
  echo "<td align='right'>$Gfont 26</td><td align='right'>$Gfont 27</td><td align='right'>$Gfont 28</td><td align='right'>$Gfont 29</td><td align='right'>$Gfont 30</td>";
  echo "<td align='right'>$Gfont 31</td><td align='right'>$Gfont Total</td>";
  echo "</tr>";	

  $RepA = mysql_query("SELECT * FROM rep ORDER BY clave");
  
  while($rg = mysql_fetch_array($RepA)){

      if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

      $nNum = $rg[p01]+$rg[p02]+$rg[p03]+$rg[p04]+$rg[p05]+$rg[p06]+$rg[p07]+$rg[p08]+$rg[p09]+$rg[10]+$rg[p11]+$rg[p12]+$rg[p13]+$rg[p14]+$rg[p15]+$rg[p16]+$rg[p17]+$rg[p18]+$rg[p19]+$rg[20]+$rg[p21]+$rg[p22]+$rg[p23]+$rg[p24]+$rg[p25]+$rg[p26]+$rg[p27]+$rg[p28]+$rg[p29]+$rg[30]+$rg[31];

		echo "<td>$Gfont $rg[clave]</td>";
		echo "<td align='right'>$Gfont $rg[p01]</td><td align='right'>$Gfont $rg[p02]</td><td align='right'>$Gfont $rg[p03]</td><td align='right'>$Gfont $rg[p04]</td><td align='right'>$Gfont $rg[p05]</td>";
		echo "<td align='right'>$Gfont $rg[p06]</td><td align='right'>$Gfont $rg[p07]</td><td align='right'>$Gfont $rg[p08]</td><td align='right'>$Gfont $rg[p09]</td><td align='right'>$Gfont $rg[p10]</td>";
		echo "<td align='right'>$Gfont $rg[p11]</td><td align='right'>$Gfont $rg[p12]</td><td align='right'>$Gfont $rg[p13]</td><td align='right'>$Gfont $rg[p14]</td><td align='right'>$Gfont $rg[p15]</td>";
		echo "<td align='right'>$Gfont $rg[p16]</td><td align='right'>$Gfont $rg[p17]</td><td align='right'>$Gfont $rg[p18]</td><td align='right'>$Gfont $rg[p19]</td><td align='right'>$Gfont $rg[p20]</td>";
		echo "<td align='right'>$Gfont $rg[p21]</td><td align='right'>$Gfont $rg[p22]</td><td align='right'>$Gfont $rg[p23]</td><td align='right'>$Gfont $rg[p24]</td><td align='right'>$Gfont $rg[p25]</td>";
		echo "<td align='right'>$Gfont $rg[p26]</td><td align='right'>$Gfont $rg[p27]</td><td align='right'>$Gfont $rg[p28]</td><td align='right'>$Gfont $rg[p29]</td><td align='right'>$Gfont $rg[p30]</td>";
		echo "<td align='right'>$Gfont $rg[p31]</td><td align='right'>$Gfont $nNum</td>";		
		echo "</tr>";
      $nRng++;
		
  }

  echo "</table>";

  echo "<table align='center' width='98%' border='0'>";

  echo "<tr><td width='3%'><a href='menu.php'><img src='lib/regresa.jpg' border='0'></a>";

  echo "</td><td>$Gfont ";

  echo "<form name='form1' method='post' action='reprecepcion.php'>";

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

echo "<form name='form2' method='post' action='menu.php'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";

echo "</tr></table>";

mysql_close();

?>

</body>

</html>
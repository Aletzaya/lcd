<?php

  session_start();

  require("lib/kaplib.php");

  $link=conectarse();

  $FechaI=$_REQUEST[FechaI];

  $FechaF=$_REQUEST[FechaF];

  if (!isset($FechaI)){

      $FechaF=date("Y-m-d");

      $FechaI=date("Y-m-")."01";

  }

  $Titulo="Demanda de afluencia de pacientes por dia y medico del $FechaI al $FechaF";


  $OtA=mysql_query("select med.medico,med.nombrec,ot.fecha,count(*) from ot,med
   WHERE ot.medico=med.medico and ot.fecha Between '$FechaI' And '$FechaF'
   GROUP BY ot.fecha,ot.medico order by ot.medico",$link);

  $Mes = array("","Enero","Feb","Mzo","Abr","Mayo","Jun","Jul","Agost","Sept","Oct","Nov","Dic");

  require ("config.php");

  $Gfon="<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#ffffff'> &nbsp; ";


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php

  headymenu($Titulo,0);

  $Tit="<tr bgcolor='$GfdoTitulo'><td align='right'>$Gfon <b>Medico </b></td><td>$Gfon <b>Nombre</b></td>";

  $In=strtotime($FechaI);
  $Fi=strtotime($FechaF);
  
  for($i = $In; $i <= $Fi; $i=strtotime("1 days",$i)){
      $Fec=date("Y-m-d",$i);              //Convierto El resultado en Tipo Fecha
      $Tit=$Tit."<td align='center'>$Gfon <b>".substr($Fec,8,2)."</b></font></td>";
  }

  $Tit=$Tit."<td align='right'>$Gfon <b>Tt</b></td></tr>";

  echo "<table width='98%' align='center' border='0'>";

  echo $Tit;
  $Med='XXX';
  $tCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0");
  while($reg=mysql_fetch_array($OtA)){
     if($reg[medico]<>$Med){
       if($Med<>'XXX'){
          $cTit='';
          $SubT=0;
          for($i = $In; $i <= $Fi; $i=strtotime("1 days",$i)){
              $Fec=date("Y-m-d",$i);              //Convierto El resultado en Tipo Fecha
			  $num=0+substr($Fec,8,2);
              $cTit=$cTit."<td align='right'>$Gfont $aCnt[$num]</font></td>";
			  $tCnt[$num] = $tCnt[$num] + $aCnt[$num];
              $SubT+=$aCnt[$num];
              $GraT+=$aCnt[$num];
          }
          echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';><td>$Gfont $Med</td><td>$Gfont $Nombre</font></td>";
          echo $cTit."<td align='right'>$Gfont $SubT</td></tr>";
       }
       $Med=substr($reg[medico],0,17);
       $Nombre=$reg[nombrec];
       $aCnt = array("0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0");
     }
     $Fec=$reg[fecha];
     $Pos=substr($Fec,8,2);
     $aCnt[$Pos]=$reg[3];
  }
  $cTit='';
  $SubT=0;
  for($i = $In; $i <= $Fi; $i=strtotime("1 days",$i)){
      $Fec=date("Y-m-d",$i);              //Convierto El resultado en Tipo Fecha
	  $num=substr($Fec,8,2);
      $cTit=$cTit."<td align='center'>$Gfont $aCnt[$num]</font></td>";
      $SubT+=$aCnt[$num];
      $GraT+=$aCnt[$num];
  }
  echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';><td>$Gfont $Med</td><td>$Gfont $Nombre</font></td>";
  echo $cTit."<td align='right'>$Gfont $SubT</td></tr>";

  $cTit='';
  for($i = $In; $i <= $Fi; $i=strtotime("1 days",$i)){
      $Fec=date("Y-m-d",$i);              //Convierto El resultado en Tipo Fecha
      $num=substr($Fec,8,2);
      $cTit=$cTit."<td align='right'>$Gfont $tCnt[$num]</font></td>";
  }
  echo "<tr><td>&nbsp;</td><td align='right'>$Gfont Totales</font></td>";
  echo $cTit."<td align='right'>$Gfont $GraT</td></tr>";


  echo "</table>";

echo "<table align='center' width='80%' border='0'>";

echo "<tr><td width='25%' >$Gfont ";

echo "<form name='form1' method='post' action='repdemandadia.php'><br><br>";

echo "<a class='pg' href='menu.php'>Regresar</a> &nbsp; ";

echo "Fecha Inicial: <input type='text' name='FechaI' value='$FechaI' size='10' maxlength='10'>";

echo "Fecha Final: </font><input type='text' name='FechaF' value='$FechaF' size='10' maxlength='10'> ";

echo " <INPUT TYPE='SUBMIT'  name='Ir' Value='Ir...'>";

echo "</form>";

echo "</td></tr></table>";

echo "<form name='form2' method='post' action='menu.php'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";

mysql_close();

?>

</body>

</html>
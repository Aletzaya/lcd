<?php

  session_start();

  require("lib/lib.php");

  $link       = conectarse();

  $FechaI     = $_REQUEST[FechaI];

  $FechaF     = $_REQUEST[FechaF];

  $Recibio    = $_REQUEST[Recibio];

  if(!isset($Recibio)){$Recibio='*';}

  if (!isset($FechaI)){

      $FechaF  = date("Y-m-d");

      $FechaI  = date("Y-m-")."01";

  }

  $Titulo="Reporte de salidas del $FechaI al $FechaF";

  if($Recibio == "*"){
     $OtA = mysql_query("SELECT sl.recibio, sld.clave, invl.descripcion, sum( sld.cantidad ) AS cnt, 
            sum( sld.cantidad * sld.costo ) AS cost
				FROM invl, sl, sld
				WHERE sl.fecha >= '$FechaI' AND sl.fecha <= '$FechaF' AND sl.id = sld.id AND sld.clave = invl.clave
				GROUP BY sld.clave
            ORDER BY invl.descripcion");
            
  }else{
     $OtA = mysql_query("SELECT sl.recibio, sld.clave, invl.descripcion, sum( sld.cantidad ) AS cnt, 
            sum( sld.cantidad * sld.costo ) AS cost
				FROM invl, sl, sld
				WHERE sl.fecha >= '$FechaI' AND sl.fecha <= '$FechaF' AND sl.id = sld.id AND sld.clave = invl.clave
					   AND sl.recibio='$Recibio'
				GROUP BY sld.clave
            ORDER BY invl.descripcion");

  }
  //SELECT fecha,count(*),sum(importe) from ot where fecha>='$FechaI' AND fecha <='$FechaF' group by fecha",$link);
  //$Ot=mysql_fetch_array($OtA);

  require ("config.php");

  //$Gfont="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000099'> &nbsp; ";
  //$Gfont2="<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'> &nbsp; ";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php

headymenu($Titulo,0);

if($Recibio == '*'){
   	echo "$Gfont &nbsp; Todas las salidas ";
}else{
   	echo "$Gfont Salidas para $Recibio ";
}

echo "<table width='98%' border='0' cellpadding='1' cellspacing='1' bordercolor=#CCCCCC><tr height='26' background='lib/bartit.gif'>";
echo "<td>$Gfont <b> Producto</b></td><td>$Gfont <b>Descripcion</b></td><td>$Gfont <b>Cnt</b></td>";
echo "<td>$Gfont <b>Importe</b></td></tr>";

while($reg=mysql_fetch_array($OtA)){

      if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		echo "<td>$Gfont $reg[clave]</td>";
		echo "<td>$Gfont $reg[descripcion]</td>";
		echo "<td align='right'>$Gfont ".number_format($reg[cnt],"2")."</td>";
		echo "<td align='right'>$Gfont ".number_format($reg[cost],"2")."</td>";
		echo "</tr>";
		$Cnt += $reg[cnt];
		$Imp += $reg[cost];

      $nRng++;
		
}	

echo "<tr>";
echo "<td>$Gfont $reg[producto]</td>";
echo "<td>$Gfont $reg[descripcion]</td>";
echo "<td align='right'>$Gfont ".number_format($Cnt,"2")."</td>";
echo "<td align='right'>$Gfont ".number_format($Imp,"2")."</td>";
echo "</tr></table>";

echo "<form name='form2' method='get' action='repsallab.php'><br><br>";

echo "<input type='submit' name='Imprimir' value='Imprimir'  onCLick='print()'> &nbsp; &nbsp; &nbsp; ";

echo "$Gfont Proveedor:";

echo "<SELECT name='Recibio'>";

$InsA=mysql_query("SELECT recibio FROM sl GROUP BY recibio ORDER BY recibio");

while ($Ins=mysql_fetch_array($InsA)){
     echo "<option value='$Ins[0]'>$Ins[0]</option>";
     if($Inst==$Ins[0]){$Des="$Ins[1]";}
}

if($Inst=='*'){$Des="* , &nbsp; TODOS";}

echo "<option value='*'> * , &nbsp; TODOS</option>";

echo "<option selected value=$Inst>$Des</option>";

echo "</SELECT>";

echo "&nbsp; Fecha Inicial: <input type='text' name='FechaI' value='$FechaI' size='10' maxlength='10'>";

echo "&nbsp; Fecha Final: </font><input type='text' name='FechaF' value='$FechaF' size='10' maxlength='10'> ";

echo " <INPUT TYPE='SUBMIT'  name='Enviar' Value='Enviar'>";

echo "</form>";

CierraWin();

mysql_close();

echo "</body>";

echo "</html>";

?>

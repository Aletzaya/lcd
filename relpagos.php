<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link    = conectarse();
  
  $Fecha   = date("Y-m-d");

  $Hora    = date("H:i");

  $Mes     = $_REQUEST[Mes];
  $Ruta    = $_REQUEST[Ruta];  
  
  require ("confignew.php");

?>

<html>

<head>
<title>Sistema de Laboratoriio clinico</title>
</head>

<body>

<?php

	echo "<table width='100%' border='0'>";
	echo "<tr>";
   echo "<td  align='center'>$Gfont <font size='+2'>";
   echo "<strong>Laboratorio Clinico Duran</strong></font><br>";
	echo "$Fecha - $Hora <br>";
   echo "Relacion de Pagos a medicos correspondiente al periodo: <b>$Mes</b> Ruta: <b>$Ruta</b>";
   echo "</td>";
	echo "</tr>";
	echo "</table>";

   echo "<table align='center' width='90%' border='0' cellspacing='1' cellpadding='0'>";
   echo "<tr>";
	echo "<th align='CENTER'>$Gfont Medico</th>";
   echo "<th align='CENTER'>$Gfont Nombre</font></th>";
   echo "<th align='CENTER'>$Gfont #Ordenes</font></th>";
   echo "<th align='CENTER'>$Gfont #Estudios</font></th>";
   echo "<th align='CENTER'>$Gfont Importe</font></th>";
   echo "<th align='CENTER'>$Gfont Comision</font></th>";
   echo "</tr>";
   echo "<tr><td colspan='6'><hr noshade></td></tr>";

   $CpoA = mysql_query("SELECT cmc.medico, med.nombrec, count( * ) , sum( cmc.numestudios ) , sum( cmc.importe ) , 
   sum( cmc.comision )
   FROM med, cmc
   WHERE cmc.mes='$Mes' AND med.ruta='$Ruta' AND cmc.medico = med.medico
   GROUP BY cmc.medico");

   while($rg=mysql_fetch_array($CpoA)){
      
         if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

         echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
         
         echo "<td align='left'>$Gfont ".ucwords(strtolower($rg[medico]))."</td>";
         echo "<td align='left'>$Gfont ".ucwords(strtolower($rg[nombrec]))."</td>";
         echo "<td align='right'>$Gfont $rg[2] &nbsp; </td>";
         echo "<td align='right'>$Gfont $rg[3] &nbsp; </td>";
         echo "<td align='right'>$Gfont ".number_format($rg[4],"2")." &nbsp; </td>";
         echo "<td align='right'>$Gfont ".number_format($rg[5],"2")." &nbsp; </td>";
         echo "</tr>";                    
         
         $nRng++;

			$Num++;
			$Ord += $rg[2];
			$Est += $rg[3];
			$Imp += $rg[4];
			$Com += $rg[5];
			
   }
   echo "<tr><td colspan='6'><hr noshade></td></tr>";
   echo "<tr><td align='left'>$Gfont </td>";
   echo "<td align='right'>$Gfont Total ----> &nbsp; </td>";
   echo "<td align='right'>$Gfont $Ord &nbsp; </td>";
   echo "<td align='right'>$Gfont $Est &nbsp; </td>";
   echo "<td align='right'>$Gfont ".number_format($Imp,"2")." &nbsp; </td>";
   echo "<td align='right'>$Gfont ".number_format($Com,"2")." &nbsp; </td>";
   echo "</tr>";                    
   
   echo "</table>";
       
   echo "<form name='form1' method='post' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";
        
   echo "<a href='menu.php'><img src='lib/regresa.jpg' border='0'></a> &nbsp; ";
        
   echo "$Gfont <b>Ruta medica:</b> ";
  	$RtaA=mysql_query("SELECT id,descripcion FROM ruta ORDER BY id");
   echo "<select name='Ruta'>";
   while ($Rta=mysql_fetch_array($RtaA)){
               echo "<option value=$Rta[id]> $Rta[descripcion]</option>";
   }
   echo "<option selected>Selecciona la ruta</option>";
   echo "</select>";
       
  	$PerA  = mysql_query("SELECT mes FROM cmc GROUP BY mes");
   echo "<select name='Mes'>";
   while ($Per=mysql_fetch_array($PerA)){
               echo "<option value=$Per[mes]> $Per[mes]</option>";
   }
   echo "<option selected>Periodo</option>";
   echo "</select> &nbsp; ";

   echo "<input type='submit' name='boton' value='Enviar'> &nbsp; &nbsp; &nbsp; ";
   
   if($Num > 0){
   
   	echo "<a class='ord' href=javascript:wingral('pdfhojasvis.php?Ruta=$Ruta&Mes=$Mes')>Genera hoja de visitas <img src='lib/cuadro.gif' border='0'> </a> &nbsp; &nbsp; ";

   	echo "<a class='ord' href=javascript:wingral('medconsolida.php?Mes=$Mes')>Consolida demanda de ordenes por medico en el mes <img src='lib/consolida.gif' border='0'> </a>";
	   
   }

   echo "</form>";
   
	?>

<div align="left">
<form name="form1" method="post" action="menu.php">
   <input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">
</form>
</div>
</body>
</html>
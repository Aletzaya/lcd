<?php

  session_start();

  date_default_timezone_set("America/Mexico_City");

  require ("config.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link      = conectarse();
  $Orden     = $_REQUEST[Orden];
  $Usr       = $check['uname'];
  $Fechaest  = date("Y-m-d H:i:s");
          
  $Gfont  = "<font color='#414141' face='Arial, Helvetica, sans-serif' size='2'>";
  $Gfont2 = "<font face='Arial, Helvetica, sans-serif' size='2'>";
          
echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

?>

<script language="JavaScript1.2">

function cFocus(){

  document.form1.Orden.focus();

}

</script>

<?php

echo "<body bgcolor='#FFFFFF'>";          
  
echo "$Gfont <p align='center'><b>Informe de No conformidades</b></p>";

$HeA  = mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,cli.nombrec,ot.institucion,ot.observaciones,ot.entemailpac,ot.entemailmed,ot.entemailinst FROM ot,cli
WHERE ot.orden='$Orden' AND ot.cliente=cli.cliente");

$He   = mysql_fetch_array($HeA);   


echo "<div align='left'> &nbsp; <font size='+2'>$He[nombrec]</font></div>";
echo "<div align='left'> &nbsp; Fecha: $He[fecha] Hra: $He[hora] Fecha de entrega: $He[fechae] &nbsp; <b>No.ORDEN: $He[institucion] - $Orden</b></div>";

$Sql  = mysql_query("SELECT otd.estudio,est.descripcion,otd.lugar,otd.cinco,otd.recibeencaja,otd.noconformidad,est.depto FROM otd,est 
WHERE otd.orden='$Orden' AND otd.estudio=est.estudio");

echo "<table align='center' width='99%' border='1' cellspacing='0' cellpadding='0'>";
echo "<tr height='25' bgcolor='#CCCCCC'>";
echo "<td align='center'>$Gfont2 Estudio</td>";
echo "<td align='center'>$Gfont2 Descripcion</td>";   
echo "<td align='center'>$Gfont2 No conformidad</td>";  
echo "</tr>";              

while($rg=mysql_fetch_array($Sql)){

  if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

  echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand'; onMouseOut=this.style.backgroundColor='$Fdo';>";                       
  echo "<td>$Gfont $rg[estudio]</td>";
  echo "<td>$Gfont $rg[descripcion]</td>";
  echo "<td>$Gfont $rg[noconformidad]</td>";
  echo "</tr>";
  $nRng++;

}        
echo "</table>";

while($nRng<=4){  
    echo "<div align='center'>$Gfont &nbsp; </div>";
    $nRng++;
}    

echo "$Gfont <a class='pg' href='javascript:window.close()'><img src='lib/regresa.jpg' border='0'></a> &nbsp; &nbsp; ";
  	                
echo "</body>";
  
echo "</html>";
  
mysql_close();
  
?> 
<?php

  session_start();

  require("lib/lib.php");
  date_default_timezone_set("America/Mexico_City");
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");


  $Usr       = $check['uname'];
  $orden     = $_REQUEST[Orden];
  $Estudio   = $_REQUEST[Estudio];
  $status    = $_REQUEST[status];

  $Msj       = "";
  $Fecha     = date("Y-m-d");
  $Fechaest  = date("Y-m-d H:i:s");
  $Hora      = date("h:i:s");
  $Titulo    = "Detalle Estudio[$Estudio]";

  $link      = conectarse();

  $OrdenDef  = "otd.estudio";            //Orden de la tabla por default

  $Tabla     = "otd";

  $cSqlH     = "SELECT ot.orden,ot.fecha,ot.hora,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,
                ot.medico,med.nombrec,ot.status,ot.recibio,ot.institucion,ot.pagada,ot.observaciones
                FROM ot,cli,med
                WHERE ot.cliente=cli.cliente AND ot.medico=med.medico AND ot.orden='$orden'";

  $cSqlD     = "SELECT maqdet.estudio,est.descripcion,maqdet.usrenvext,maqdet.fenvext,maqdet.henvext,
  				maqdet.mext,mql.alias
                FROM maqdet,est,mql
                WHERE maqdet.estudio=est.estudio AND maqdet.orden='$orden' and maqdet.estudio='$Estudio' and mql.id=maqdet.mext";

  $HeA       = mysql_query($cSqlH);
  $He        = mysql_fetch_array($HeA);
  

  $res=mysql_query($cSqlD);


echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';

require ("config.php");

echo "<html>";

//echo "<head>";

echo "<title>$Titulo</title>";

?>

<script language="JavaScript1.2">
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
	alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=450,height=350,left=100,top=150")
}
function WinRes(url){
   window.open(url,"WinRes","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=900,height=500,left=30,top=80")
}

</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

//headymenu($Titulo,0);

   echo "<br><table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'>";

   echo "<tr bgcolor ='#618fa9'>";

   echo "<td>$Gfont <font color='#ffffff'>Cliente: $He[cliente] $He[5]</td>
   <td>$Gfont <font color='#ffffff'>Fecha/Hora: $He[fecha]&nbsp; $He[hora]&nbsp;Fecha/entrega: $He[fechae] </td>";

   echo "</tr>";

   echo "</table>";

   echo "<table align='center' width='90%' cellpadding='0' cellspacing='1' border='0'><tr><td>$Gfont";

	echo "<table align='center' width='100%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr height='25' background='lib/bartit.gif'>";
    echo "<th>$Gfont <font size='1'>Estudio</font></th>";
    echo "<th>$Gfont <font size='1'>Descripcion</font></th>";
    echo "<th>$Gfont <font size='1'>Proveedor</font></th>";
    echo "<th>$Gfont <font size='1'>FechEnvio</font></th>";
    echo "<th>$Gfont <font size='1'>HorEnvio</font></th>";
    echo "<th>$Gfont <font size='1'>Usuario</font></th>";

		  while($registro=mysql_fetch_array($res))		{

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td>$Gfont<font size='1'>$registro[estudio]</font></td>";
            echo "<td>$Gfont<font size='1'>$registro[descripcion]</font></td>";
            echo "<td>$Gfont<font size='1'>$registro[alias]</font></td>";
            echo "<td>$Gfont<font size='1'>$registro[fenvext]</font></td>";
            echo "<td>$Gfont<font size='1'>$registro[henvext]</font></td>";
            echo "<td>$Gfont <font size='1'>$registro[usrenvext]</font></td>";
            echo "</tr>";
            $nRng++;

		}//fin while

		echo "</table> <br>";

echo "</td></tr>"; 
echo "</table> <br>";
echo "</body>";

echo "</html>";

mysql_close();
?>
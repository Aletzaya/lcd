<?php

  session_start();

  require ("config.php");

  require("lib/kaplib.php");

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $orden=$_REQUEST[orden];

  $resp=$_REQUEST[resp];

  $Msj="";

  $Titulo="Detalle de la orden de estudio [$busca]";

  $link=conectarse();

  $OrdenDef="otd.estudio";            //Orden de la tabla por default

  $Tabla="otd";

  $cSqlA=mysql_query("select sum(importe) from cja where orden='$busca'",$link);

  $SqlS=mysql_fetch_array($cSqlA);

  $cSqlH="select ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,ot.medico,med.nombrec,ot.status,ot.recibio,ot.institucion,ot.pagada from ot,cli,med where ot.cliente=cli.cliente and ot.medico=med.medico and ot.orden='$busca'";

  $cSqlD="select otd.estudio,otd.status,est.descripcion,otd.precio,otd.descuento,est.muestras,otd.etiquetas from otd,est where otd.estudio=est.estudio and otd.orden='$busca'";

  $HeA=mysql_query($cSqlH,$link);

  $He=mysql_fetch_array($HeA);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>

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
</script>
<?php

   echo "$Gfont <p align='center'><font color='#000099'><strong>DETALLE DE LA ORDEN DE ESTUDIOS $busca</strong></font></p>";
   echo "<p align='center'><font color='#660066'>No.Orden : </font> $busca &nbsp;&nbsp;&nbsp; $He[nombre]  <font color='#660066'>Cliente:</font> $He[cliente] $He[4]";
   echo "<font color='#660066'>&nbsp;&nbsp;&nbsp;Fecha : </font> $He[fecha]<font color='#660066'>&nbsp;&nbsp;&nbsp;Fecha/entrega : </font> $He[fechae]</p>";
   echo "<p align='center'><font color='#660066'>Medico: </font>$He[medico] $He[9] <font color='#660066'> &nbsp; &nbsp; Importe : $ </font> ".number_format($He[importe],"2")."<font color='#660066'>&nbsp; &nbsp; Abonado : $</font> ".number_format($SqlS[0],'2');
   echo " &nbsp; <font color='#660066'>Saldo : </font>".number_format($He[importe]-$SqlS[0],"2");
   
   //." &nbsp; [";
   
   //echo "<a class='pg' href='ingreso.php?busca=$busca&pagina=$pagina'>NVO/INGRESO</a>] &nbsp; &nbsp; Pagada: $He[pagada]</p>";

   echo "</font>";

   if(!$res=mysql_query($cSqlD." ORDER BY ".$OrdenDef,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{
      echo "<br>";
      $numeroRegistros=mysql_num_rows($res);
		echo "<div align='center'>";
		echo "<img src='images/corner-bottom-left.gif' width='15' height='12'>";
		echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Estudios</b></strong></font>";
		echo "<img src='images/corner-bottom-right.gif' width='15' height='12'>";
		echo "</div>";
		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td colspan='10'><hr noshade></td></tr>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Estudio</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Descripcion</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>#Muestras</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>#Imp</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Etiq</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Status</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Precio</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>%Dto</font></th>";
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Importe</font></th>";
        //echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Elim</font></th>";
		while($registro=mysql_fetch_array($res))		{
            echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
            echo "<td>$Gfont $registro[estudio]</font></td>";
            echo "<td>$Gfont $registro[descripcion]</font></td>";
            echo "<td>$Gfont $registro[muestras]</font></td>";
            echo "<td>$Gfont ".number_format($registro[etiquetas],"2")."</font></td>";
            echo "<td align='center'><a href=javascript:Ventana('impeti.php?op=1&busca=".$busca."&Est=$registro[estudio]')><img src='lib/print.png' alt='Imprime' border='0'></a></td>";
            echo "<td>$Gfont $registro[status]</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio],"2")."</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[descuento],"2")."</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio]*(1-$registro[descuento]/100),"2")."</font></td>";
            //echo "<td align='center'><a href=ordenesd.php?op=El&busca=$busca&Estudio=$registro[estudio]&pagina=$pagina><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
            echo "</tr>";
		}//fin while

		echo "</table> <br>";
 
      echo "<div align='center'><a class='pg' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></div>";

    if($resp=='si'){

          echo "<div align='center'> <a class='pg' href='repotseco.php?busca=$busca'><img src='lib/regresa.jpg' border=0></a> </div>";

    }else{

          echo "<div align='center'> <a class='pg' href='repots.php?busca=$He[cliente]'><img src='lib/regresa.jpg' border=0></a> </div>";
  
    }


		
      //echo " &nbsp; &nbsp; <a class='pg' href=javascript:Ventana('impeti2.php?op=3&busca=$busca')>Etiquetas</a>";

	}//fin if

echo "</body>";

echo "</html>";

mysql_close();
?>
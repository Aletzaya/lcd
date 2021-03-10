<?php

  session_start();

  require ("config.php");

  require("lib/kaplib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $orden=$_REQUEST[orden];

  $Msj="";

  $Fecha=date("Y-m-d");

  $Hora = date("h:i:s");

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

  if($_REQUEST[op]=='St'){

     if($_REQUEST[Status]=="Entregada"){
        if(($SqlS[0]+.5) >= $He[5]){    //Listo si la puede entregar, por k si esta pagada
           if($_REQUEST[Recibio]==""){
              $Msj='Favor de poner quien recibe el estudio';
           }else{
              $lUp=mysql_query("update ot set status='$_REQUEST[Status]',recibio='$_REQUEST[Recibio]',entusr='$Usr',entfec='$Fecha',enthra='$Hora' where orden='$busca'",$link);
              $HeA=mysql_query($cSqlH,$link);
              $He=mysql_fetch_array($HeA);
           }
        }
     }

  }elseif($_REQUEST[op]=='El' and $Usr=='vidal'){
     $OtdA=mysql_query("select status from otd where orden='$busca' and estudio='$Estudio'",$link);
     $Otd=mysql_fetch_array($OtdA);
     //if($Otd[0]=="DEPTO"){
        $cSqlE="delete from otd where estudio='$_REQUEST[Estudio]' and orden='$busca' limit 1";
        $SqA=mysql_query($cSqlE,$link);
        $OtdA=mysql_query("select sum(precio*(1-descuento/100)) from otd where orden='$busca'",$link);
        $Otd=mysql_fetch_array($OtdA);
        $lUp=mysql_query("update ot set importe='$Otd[0]' where orden='$busca'",$link);
     //}else{
     //   $Msj="Lo siento! este estudio ya tiene movimientos";
     //}
  }elseif($_REQUEST[op]=="Ag"){

    $OtA=mysql_query("select descuento from ot where orden='$busca'",$link);
    $Ot=mysql_fetch_array($OtA);

    $LtA=mysql_query("select lista from inst where institucion='$He[institucion]'",$link);
    $Lt=mysql_fetch_array($LtA);
    $Lista="lt".ltrim($Lt[lista]);
    $lUp=mysql_query("select estudio,$Lista from est where estudio='$_REQUEST[Estudio]' ",$link);
    if($cCpo=mysql_fetch_array($lUp)){
       $Estudio=strtoupper($_REQUEST[Estudio]);
//       $Estudio=strtoupper($Estudio);
       $lUp=mysql_query("insert into otd (orden,estudio,precio,status,descuento) VALUES ('$busca','$Estudio','$cCpo[$Lista]','DEPTO','$Ot[descuento]')",$link);
       $OtdA=mysql_query("select sum(precio*(1-descuento/100)) from otd where orden='$busca'",$link);
       $Otd=mysql_fetch_array($OtdA);
       $lUp=mysql_query("update ot set importe='$Otd[0]' where orden='$busca'",$link);
   }else{
       $Msj="El Estudio [$Estudio] no existe, favor de verificar";
   }

  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>

<?php headymenu($Titulo,0); ?>

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
  if($op=='sm'){
     $cSumA="select sum($SumaCampo) from $Tabla,cli where $Tabla.cliente=cli.cliente ".$cWhe;
     $cSum=mysql_query($cSumA,$link);
     $Suma=mysql_fetch_array($cSum);
     $Funcion=" / $SumaCampo: ".number_format($Suma[0],"2");
  }
   echo "$Gfont <p align='center'><font color='#000099'><strong>DETALLE DE LA ORDEN DE ESTUDIOS $busca</strong></font></p>";
   echo "<p align='center'><font color='#660066'>No.Orden : </font> $busca &nbsp;&nbsp;&nbsp; $He[nombre]  <font color='#660066'>Cliente:</font> $He[cliente] $He[4]";
   echo "<font color='#660066'>&nbsp;&nbsp;&nbsp;Fecha : </font> $He[fecha]<font color='#660066'>&nbsp;&nbsp;&nbsp;Fecha/entrega : </font> $He[fechae]</p>";
   echo "<p align='center'><font color='#660066'>Medico: </font>$He[medico] $He[9] <font color='#660066'> &nbsp; &nbsp; Importe : $ </font> ".number_format($He[importe],"2")."<font color='#660066'>&nbsp; &nbsp; Abonado : $</font> ".number_format($SqlS[0],'2');
   echo " &nbsp; <font color='#660066'>Saldo : </font>".number_format($He[importe]-$SqlS[0],"2")." &nbsp; [";
   echo "<a class='pg' href='ingreso.php?busca=$busca&pagina=$pagina'>NVO/INGRESO</a>] &nbsp; &nbsp; Pagada: $He[pagada]</p>";

   echo "<p align='center'>";
   echo "<form name='form1' method='post' action='ordenesd.php?busca=$busca&pagina=$pagina&op=St'>";
      echo "<font color='#660066'> ".str_repeat(" &nbsp; ",11)."Status : </font> ";
      echo "<select name='Status'>";
      echo "<option value='Pendiente'>Pendiente</option>";
      echo "<option value='Entregada'>Entregada</option>";
      echo "<option selected value = $He[status]>$He[status]</option>";
      echo "</select>";
      echo "<font color='#660066'>&nbsp; &nbsp;Recibio los resultados : </FONT> <input name='Recibio' value='$He[recibio]' type='text' maxlength='25' size='20' onBlur=Mayusculas('Recibio')>";
      echo "&nbsp; &nbsp;<input type='submit' name='Submit' value='Ok'>";
      echo "<font color='#990000' size='1'>$Msj</font>";
   echo "</form></p>";
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
        echo "<th bgcolor='#6633FF'><font color='#FFFFFF'>Elim</font></th>";
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
            echo "<td align='center'><a href=ordenesd.php?op=El&busca=$busca&Estudio=$registro[estudio]&pagina=$pagina><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
            echo "</tr>";
		}//fin while

		echo "</table> <br>";
      echo " &nbsp; &nbsp; <a class='pg' href=javascript:Ventana('impeti.php?op=3&busca=$busca')>Etiquetas</a>";

	}//fin if

    ?>

<p>&nbsp;</p>

<table align='center' width="100%" border="0" background='lib/fondo.gif' >
  <tr>
    <td>
      <form name='form2' method='post' action='ordenesd.php?op=Ag&busca=<?php echo $busca; ?>'>
        <font color="#0000CC" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> &nbsp; &nbsp; Estudio
        </strong>:</font>
        <input name='Estudio' type='text' size='5' >
        <INPUT TYPE="SUBMIT"  name="Ok" value="ok">
        <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
      </form>
    </td>
  <td>&nbsp;<?php echo "<font color='#FF0000'>$Msj</font>";?></td>
  </tr>
</table>
<a class='pg' href="ordenes.php?pagina=<? echo $pagina;?>"> &nbsp; &nbsp; Regresar</a><br>
</body>
</html>
<?php
mysql_close();
?>

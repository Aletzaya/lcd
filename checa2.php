<?php

  session_start();

  $_SESSION['usr']='Admin';

  require ("config.php");

  require("lib/kaplib.php");

  require("lib/filtro.php");

  $link=conectarse();

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Titulo="DETALLE DE HORARIOS";

  $cFuncion="";                 //Variabla para la funciones estadistico de suma,media,...

  $OrdenDef="hrd.dia";            //Orden de la tabla por default

  $tamPag=15;

  $op=$_REQUEST[op];

  $Dia=$_REQUEST[Dia];

  $Tabla = "hrd";

  $Msj="";

  if($_REQUEST[Credencial]<>''){				//Agrega producto por producto

     $Mov = array("entradai","entradaf","salidai","salidaf");

     $EmpA=mysql_query("select nombre,horario,id from emp where credencial='$_REQUEST[Credencial]'",$link);
     $Emp=mysql_fetch_array($EmpA);

     $Fecha=date("Y-m-d");
     $FechaIni=strtotime($Fecha);
     $DiaSem=date("w",$FechaIni);

     $HorasA=mysql_query("select * from hrd where id='$Emp[horario]' and dia='$DiaSem'",$link);
     $Horas=mysql_fetch_array($HorasA);

     $Hora = date("H:i");

     $Hora1 = date("H:i");         
     $Hora2 = strtotime("-60 min",strtotime($Hora1));
     $Hora  = date("H:i",$Hora2);


     $cPaso = $Mov[1];
     $cIni = $Horas[$cPaso];   		 		  //Tomo la hora de entrada
     $nHora = strtotime($cIni);               //Lo Convierto
     $nHraFin = strtotime("60 min",$nHora);    //Le sumo 15 minutos
     $nHraTol = strtotime("5 min",$nHora);    //Le sumo 5 minutos
     $nHraTol = date("H:i",$nHraTol);         //Convierto El resultado en Tipo time
     $nHraFin = date("H:i",$nHraFin);         //Convierto El resultado en Tipo time
     $lBd=false;

     for ($i = 0; $i <= 4; $i=$i+1) {

         $cPaso = $Mov[$i];
         $cIni = $Horas[$cPaso];

         $cPaso = $Mov[$i+1];
         $cFin = $Horas[$cPaso];

         if($Mov[$i+1]=="entradaf"){$cLimite=$nHraFin;}else{$cLimite=$cFin;}

//         if($Hora >= $cIni and $Hora <= $cLimite ){
            $Sta=1;
            if(($i+1)==1){   //Osea k es la entrada
               if($Hora <= $cFin){$Sta=1;}elseif($Hora <= $nHraTol){$Sta=2;}else{$Sta=3;}
            }
            $nMov=$i+1;
            $CheA=mysql_query("select empleado from mov where empleado='$Emp[id]' and fecha='$Fecha' and mov='$nMov'",$link);
            $Che=mysql_fetch_array($CheA);
            if($Che[0]==''){
               $lBd=true;
               $lUp=mysql_query("insert into mov (empleado,fecha,hora,mov,status) values ('$Emp[id]','$Fecha','$Hora','$nMov','$Sta')",$link);
               $i=10;
            }else{
               $Msj="La tarjeta ya ha sido registrada, para esta hora";
            }
//         }
     }
  }else{
            $Msj="La tarjeta No registrada";
  }

  $cSqlD="select * from hr where id = '$busca'";

  $cSql="select * from hrd where id='$busca' ";

  $HeA=mysql_query($cSqlD,$link);

  $He=mysql_fetch_array($HeA);

  $aDia=array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");

  $Edit = array("","","Dia","Descripcion","Entrada Inicial","Entrada Final","Salida Inicial","Salida Final","","-","-","-","-","-","-","-");

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php
 //headymenu($Titulo);
//<body onload="IniciarReloj24()">
?>

<body bgcolor="#FFFFFF" onload="cFocus()">

<script type="text/javascript">MostrarFechaActual()</script>


<script language="JavaScript1.2">

function MostrarFechaActual() {
var nombre_dia = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado")
var nombre_mes = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre")

var hoy_es = new Date();
dia_mes = hoy_es.getDate();
dia_semana = hoy_es.getDay();
mes = hoy_es.getMonth() + 1;
anyo = hoy_es.getYear();

if (anyo < 100) anyo = '19' + anyo
else if ( ( anyo > 100 ) && ( anyo < 999 ) ) { var cadena_anyo = new String(anyo) ; anyo = '20' + cadena_anyo.substring(1,3) }
document.write(nombre_dia[dia_semana] + ", " + dia_mes + " de " + nombre_mes[mes - 1] + " del " + anyo) }

// RELOJ 24 HORAS
var Reloj24H = null
var RelojEnMarcha = false

function DetenerReloj24 () {if(RelojEnMarcha) clearTimeout(Reloj24H); RelojEnMarcha = false }

function MostrarHoraActual() {
var ahora = new Date()
var hora = ahora.getHours()
var minuto = ahora.getMinutes()
var segundo = ahora.getSeconds()
var HHMMSS

if (hora < 10) {HHMMSS = "0" + hora} else {HHMMSS = " " + hora};
if (minuto < 10) {HHMMSS += ":0" + minuto} else {HHMMSS += ":" + minuto};
if (segundo < 10) {HHMMSS += ":0" + segundo} else {HHMMSS += ":" + segundo};

document.Reloj24H.digitos.value = HHMMSS;
Reloj24H = setTimeout("MostrarHoraActual()",1000)
RelojEnMarcha = true }

function IniciarReloj24() {
DetenerReloj24();
MostrarHoraActual() }


function cFocus(){

  document.form1.Credencial.focus();

}

function Mayusculas(cCampo){

if (cCampo=='Producto'){document.form1.Producto.value=document.form1.Producto.value.toUpperCase();}

}

// final -->

</script>

<table width='100%' border='0'>

<tr>

<td width='10%'><a href='menu.php'><img  src='lib/logo2.jpg' border='0'></a></td>

<td width='70%'>

<br><div align='center'><img src='lib/labclidur.jpg'></div>

<div align='center'><script type="text/javascript">MostrarFechaActual()</script></div>

</td>

<td width='20%' align='left'>

<form name="Reloj24H" action="">
<input style="color:#003399; text-align:center; font-size:25pt; font-weight:bold" type="text" size="7" name="digitos" value=" ">
</form>

</td>

<?php
//echo "<td width='15%'><img src='lib/logo3.jpg'></td>";

echo "</tr>";

echo "</table>";

       echo "<br><form name='form1' method='get' action='checa.php'>";

       echo "<p align='center'>$Gfont <font size='+1'> Sistema de control de Asistencias &nbsp; &nbsp; &nbsp; &nbsp; </font></font></p>";

       echo "$Gfont <p>&nbsp;</p>";

        echo "<div align='right'> ";

        echo " &nbsp; Credencial: ";

        echo "<input type='password' name='Credencial' size='17' >";

        echo " &nbsp; <input type='submit' name='ok' value='ok'> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ";

        echo "</div>";

       echo "</form>";


      echo "<p>&nbsp;</p><hr>";

      if($lBd){
         if($Sta==1){$Dis=' Normal';}elseif($Sta==2){$Dis=" Tolerancia";}else{$Dis=" Retardo !!";}
		 if($nMov==1 or $nMov==3){$ES=' ENTRADA';}else{$ES=' SALIDA';}
         echo "<p align='left'><font size='+1'>$Emp[nombre] &nbsp; / &nbsp; Hra. $Hora / &nbsp; Checada: $Dis / &nbsp; $ES</font></p>";
         echo "<p align='left'> $Horas[entradai] &nbsp; $Horas[entradaf]</p><hr>";

      }else{

         if($Msj<>''){

            echo "<p align='left'> $Emp[nombre] </font></p>";
            echo "<p align='left'>$Gfont <font size='+1'> $Msj  </font></font></p><hr>";

         }elseif($_REQUEST[Credencial]<>''){
            echo "<p align='left'>$Gfont <font size='+1'> $Emp[nombre] </font></font></p>";
            echo "<p align='left'>$Gfont <font size='+1'>Horario no disponible para checadas</font></font></p><hr>";
         }
      }
      echo "<p>&nbsp;</p>";

      echo "<p>&nbsp;</p>";

      echo "<div><a class='pg' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></div>";

    ?>

     <script type="text/javascript">IniciarReloj24()</script>

</body>

</html>

<?

mysql_close();

?>
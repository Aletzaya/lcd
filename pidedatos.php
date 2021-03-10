<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $level       = $check['level'];

  $Usr    = $_COOKIE['USERNAME'];

  $Titulo = "Captura de datos";
  $cRep=$_REQUEST[cRep];

  $op=$_REQUEST[op];

  $Fechas=$_REQUEST[fechas];

  if($cRep==1){    //Reporte de corte de caja
     $Titulo="Corte de Caja";
  }elseif($cRep==2){
     $Titulo="Demanda de Estudios";
  }elseif($cRep==3){
     $Titulo="Relacion de Ordenes abiertas";
  }elseif($cRep==4){
     $Titulo="Calculo de comisiones";
  }elseif($cRep==5){
     $Titulo="Calculo de comisiones";
  }elseif($cRep==6){
     $Titulo="Relacion de Comisiones";
  }elseif($cRep==7){
     $Titulo="Actualiza precios en ordenes de estudio";
  }elseif($cRep==8){
     $Titulo="Demanda de Estudios x Hora";
  }elseif($cRep==9){
     $Titulo="Reporte de Demanda de Ordenes x Depto Detalle del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==10){
     $Titulo="Reporte de Demanda de Ordenes x Depto Resumen del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==11){
     $Titulo="Reporte de Pagos de Ordenes de Trabajo del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==12){
     $Titulo="Reporte de Ordenes pendientes x entregar del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==13){
     $Titulo="Reporte de Ordenes Entregadas del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==14){
     $Titulo="Reporte de Ordenes - Ruta Critica del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==15){
     $Titulo="Reporte de Ordenes pendientes x entregar al Cliente del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==19){
     $Titulo="Reporte de Ordenes Abiertas* $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==20){
     $Titulo="Relacion Servicio Movil de Ordenes Abiertas* $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==21){
     $Titulo="Relacion de Ordenes Abiertas PEMEX* $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==22){
     $Titulo="Relacion Tiempos de entrega $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==23){
     $Titulo="Relacion Estudios por Depto $_REQUEST[FecI] al $_REQUEST[FecF]";
  }elseif($cRep==24){
     $Titulo="Relacion de Transferencias del $_REQUEST[FecI] al $_REQUEST[FecF]";
  }elseif($cRep==25){
     $Titulo="Reporte de proceso del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==26){
     $Titulo="Reporte de Pendientes x entregar del $_REQUEST[FechaI] al $_REQUEST[FechaF]";
  }elseif($cRep==27){
     $Titulo="Reporte de Envio de Muestras";
  }elseif($cRep==28){
     $Titulo="Reporte de Envio de Muestras Externas";
  }elseif($cRep==29){
     $Titulo="Reporte de Entrega";
  }elseif($cRep==30){
     $Titulo="Reporte de Ingresos Generales";
  }elseif($cRep==31){
     $Titulo="Reporte de Facturacion";
  }elseif($cRep==32){
     $Titulo="Corte de caja - Formato";
  }elseif($cRep==40){
     $Titulo="Demanda X Analito";
  }elseif($cRep==41){
     $Titulo="Reporte de Modificacion de Clave - Medico";
  }elseif($cRep==42){
     $Titulo="Reporte de Pagos - Resumen";
  }elseif($op=='ActPre'){
  	
   $cSql = "update otd,ot,est set otd.precio=$_REQUEST[Lista] where ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'
          and ot.orden=otd.orden and otd.estudio=est.estudio";
   
   $lUp  = mysql_query($cSql);
   
   $SqlA = mysql_query("SELECT orden FROM ot WHERE ot.fecha>='$_REQUEST[FechaI]' and ot.institucion='$_REQUEST[Institucion]'");
   while($Cpo=mysql_fetch_array($SqlA)){
   	  $busca = $Cpo[0];
   	  $ImpA  = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
        $Otd   = mysql_fetch_array($ImpA);
        $lUp   = mysql_query("UPDATE ot SET importe='$Otd[0]' WHERE orden='$busca'");   	   	
 	}


   header("Location: menu.php");


  }

  require ("config.php");							//Parametros de colores;

?>

<html>

<head>
<title><?php echo $Titulo;?></title>

</head>


<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<script language="JavaScript1.2">

function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}

</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

headymenu($Titulo,1);


 $Fecha   = date("Y-m-d");

 echo "<table border='0' width='100%' align='center' border=0 cellpadding=0 cellspacing=0>";

 echo "<tr><td height='300' align='center'>$Gfont ";

    
  if($cRep == 1 and ($level==9 or $level==7)){

     echo "<form name='form1' method='get' action='impcorte.php'>";

        echo "<p>Fecha : ";
        echo "<input type='text'  name='Fecha' size='9' value ='$Fecha'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fecha,'yyyy-mm-dd',this)>";
        echo "</p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//		echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        echo "</select></p>";                       

        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d a s </option>";
        echo "<option value='CONTA'> INSTITUCIONES - CONTADO </option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option></p>";
        echo "</select></p>";
        
        echo "Recepcionista, * todos : ";
        echo "<input type='text'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;<br><br>";
				
	      echo "Hra.inicial : ";
        echo "<input type='text' value='07:00' name='HoraI' size='6' >&nbsp;&nbsp;&nbsp;";
        echo "Hra.final : ";
        echo "<input type='text' value='14:00'  name='HoraF' size='6' >&nbsp;&nbsp;&nbsp;";
        echo "<input type='SUBMIT' value='Enviar'>";
	      echo "<p>&nbsp;</p>";
        echo "<input type='SUBMIT' name='Completo' value='Dia_Completo'>";

    echo "</form>";

  }elseif($cRep == 2){

    $Fechai=date("Y-m-d");

    echo "<form name='form1' method='get' action='demanda.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='Fechai' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fechai,'yyyy-mm-dd',this)>  </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='Fechaf' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fechaf,'yyyy-mm-dd',this)></p>";
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
        echo "<p>&nbsp;</p>";
	    echo "</form1>";

  }elseif($cRep == 3){
  	 
     if($Fechas == 1){
          $FecI=$_REQUEST[FecI];
          $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }	 

    echo "<form name='form1' method='get' action='ordenesabi.php'>";
    
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//        echo "<select multiple size='6' name='Sucursal[]'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        //echo "<option selected value='*'> * T o d o s </option>";
//        echo "</select></p>";                       

        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
		echo "<option value='LCD'>* INSTITUCIONES - LCD *</option>";
        echo "<option value='SLCD'>* INSTITUCIONES *** SIN LCD *</option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</select>";                          
        
        /*
        echo "<p>Tipo de servicio: ";
        echo "<select name='Servicio'>";
        echo "<option value='1'>* todos</option>";
        echo "<option value='2'>Urgentes</option>";
        echo "<option value='3'>Express</option>";
        echo "</select></p>";*/

        echo "<p>Solo Ordenes C/Descto [Si/No] : ";
        echo "<select name='Descto'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "<p>Tipo de Servicio : ";
        echo "<select name='Servicio'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='Ordinaria'> Ordinaria </option>";
        echo "<option value='Urgente'> Urgente </option>";
        echo "<option value='Nocturno'> Nocturno </option>";
        echo "<option value='Express'> Express </option>";
        echo "</select>";
		
		echo "<p> Recepcionista, * todos : ";
        echo "<input type='text'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;";
		
        echo "<br><br>&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

    echo "</form>";

  }elseif($cRep == 4){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='comisiones.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";

        echo "<p>Fecha Final  [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//		echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        echo "</select></p>";                       

        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'> INSTITUCIONES LCD</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option></p>";
        echo "</select>";
		
		  echo "<p>Promotor Asig. : ";
          echo "<select name='Promotor'>";
          echo "<option value='Promotor_A'>Promotor_A</option>";
          echo "<option value='Promotor_B'>Promotor_B</option>";
          echo "<option value='Promotor_C'>Promotor_C</option>"; 
          echo "<option value='Promotor_D'>Promotor_D</option>";
          echo "<option value='Promotor_E'>Promotor_E</option>";
          echo "<option value='Promotor_F'>Promotor_F</option>";
          echo "<option value='Base'>Base</option>";
          echo "<option selected value='*'> * T o d o s </option></p>";
          echo "</select>";

        echo "<p>Medico, * todos : ";
        echo "<input type='text'  name='Medico' size='10' value ='*'> &nbsp; &nbsp; </p>";
        echo "<p>Medicos con Status[Activo/Inactivo] : ";
        echo "<select name='Status'>";
        echo "<option value='A'>Activo</option>";
        echo "<option value='I'>Inactivo</option>";
        echo "<option value=''>Ambos</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'></p>";
    echo "</form>";

  }elseif($cRep == 5){

    $Fechai=date("Y-m-d");

    echo "<form name='form1' method='get' action='demandalab.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='Fechai' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fechai,'yyyy-mm-dd',this)> </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='Fechaf' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fechaf,'yyyy-mm-dd',this)> </p>";
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";
        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";

  }elseif($cRep == 6){

    $Fechai=date("Y-m-d");

    echo "<form name='form1' method='get' action='comisionesrel.php'>";
    
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)>";
        echo "</p>";

        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)></p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//		echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        echo "</select></p>";                       

        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='LCD'> INSTITUCIONES LCD</option>";
        while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option></p>";
        echo "</select>";
        
        echo "<p>Promotor Asig. : ";
        echo "<select name='Promotor'>";
          echo "<option value='Promotor_A'>Promotor_A</option>";
          echo "<option value='Promotor_B'>Promotor_B</option>";
          echo "<option value='Promotor_C'>Promotor_C</option>"; 
          echo "<option value='Promotor_D'>Promotor_D</option>";
          echo "<option value='Promotor_E'>Promotor_E</option>";
          echo "<option value='Promotor_F'>Promotor_F</option>";
          echo "<option value='Base'>Base</option>";
          echo "<option selected value='*'> * T o d o s </option></p>";
          echo "</select>";

        echo "<p>Medico, * todos : ";
        echo "<input type='text'  name='Medico' size='10' value ='*'> &nbsp; &nbsp; </p>";

        echo "<p>Medicos con Status[Activo/Inactivo] : ";
        echo "<select name='Status'>";
        echo "<option value='A'>Activo</option>";
        echo "<option value='I'>Inactivo</option>";
        echo "<option value=''>Ambos</option>";
        echo "</select>";
        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'></p>";

    echo "</form>";

  }elseif($cRep == 7){

    $FechaI=date("Y-m-d");

    echo "<form name='form1' method='get' action='pidedatos.php'>";
        echo "<p>Apartir de la fecha [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FechaI' size='9' value ='$FechaI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)> </p>";
        echo "<p>Institucion: ";
        $InsA=mysql_query("select institucion,nombre,alias from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        while ($Ins=mysql_fetch_array($InsA)){
            echo "<option value=$Ins[0]>$Ins[0] - $Ins[alias]</option>";
        }
        echo "</select></p>";

        echo "<p>Actualizar con la lista de precios: ";
        echo "<select name='Lista'>";
		echo "<option value='est.lt1'>Lista 1</option>";
		echo "<option value='est.lt2'>Lista 2</option>";
		echo "<option value='est.lt3'>Lista 3</option>";
		echo "<option value='est.lt4'>Lista 4</option>";
		echo "<option value='est.lt5'>Lista 5</option>";
		echo "<option value='est.lt6'>Lista 6</option>";
		echo "<option value='est.lt7'>Lista 7</option>";
		echo "<option value='est.lt8'>Lista 8</option>";
		echo "<option value='est.lt9'>Lista 9</option>";
		echo "<option value='est.lt10'>Lista 10</option>";
		echo "<option value='est.lt11'>Lista 11</option>";
		echo "<option value='est.lt12'>Lista 12</option>";
		echo "<option value='est.lt13'>Lista 13</option>";
		echo "<option value='est.lt14'>Lista 14</option>";
		echo "<option value='est.lt15'>Lista 15</option>";
		echo "<option value='est.lt16'>Lista 16</option>";
	    echo "<option value='est.lt17'>Lista 17</option>";
	    echo "<option value='est.lt18'>Lista 18</option>";
	    echo "<option value='est.lt19'>Lista 19</option>";
	    echo "<option value='est.lt20'>Lista 20</option>";
      echo "<option value='est.lt21'>Lista 21</option>";
      echo "<option value='est.lt22'>Lista 22</option>";
      echo "<option value='est.lt23'>Lista 23</option>";
        echo "</select></p>";
        echo "<input type='HIDDEN'  name='op' value='ActPre'>";
        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'></p>";
    echo "</form>";

  }elseif($cRep == 8){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='demandahora.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";
       
	   	echo "<p>Sucursal : ";
        echo "<select name='Sucursal'>";
        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
        echo "<option value='*'> * | T o d a s </option>";
        while ($Cia=mysql_fetch_array($CiaA)){
               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
        }
        echo "</select></p>";                       
	   
	    echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value='*'> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
	    echo "</form1>";

  }elseif($cRep == 9){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='demandepto.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//        echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        //echo "<option selected value='*'> * T o d o s </option>";
//        echo "</select></p>";                       
		
		echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Tipo de Servicio : ";
        echo "<select name='Servicio'>";
        echo "<option value='*'> *  T o d o s </option>";
        echo "<option value='Ordinaria'> Ordinaria </option>";
        echo "<option value='Urgente'> Urgente </option>";
        echo "<option value='Nocturno'> Nocturno </option>";
        echo "<option value='Express'> Express </option>";
        echo "</select>";

        echo "<p>Medico, * todos : ";
        echo "<input type='text'  name='Medico' size='10' value ='*'> &nbsp; &nbsp; </p>";


        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
	    echo "</form1>";

  }elseif($cRep == 10){

  	 if($Fechas == 1){

	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='demandepto2.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//	    echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        echo "</select></p>";                       

  		echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
	    echo "</form1>";

  }elseif($cRep == 11){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='adeudos.php'>";

        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";
        echo "<p>Solo Adeudos [Si/No] : ";
        echo "<select name='cAdeudo'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";
        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";

  }elseif($cRep == 12){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='ordenesabipend.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//        echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=1 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        echo "</select></p>";	
	
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Solo Estudios Pendientes [Si/No] : ";
        echo "<select name='Arecepcion'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "<p>Solo Estudios Externos [Si/No] : ";
        echo "<select name='Externo'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "<p><img src='lib/email.png' border='0'>&nbsp; Solo Entega por correo [Si/No] : ";
        echo "<select name='correo'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";

  }elseif($cRep == 13){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='ordenesentregadas.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";

  }elseif($cRep == 14){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='ordenesruta.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

    echo "</form>";

  }elseif($cRep == 15){
	  
  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='ordenespendcli.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Solo Estudios Pendientes [Si/No] : ";
        echo "<select name='Arecepcion'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";
	echo "<p>&nbsp;</p>";
	 
	  
  }elseif($cRep == 19){
 
  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='ordenesabidep.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";
        
        echo "<p>Sucursal : ";
        echo "<select name='Sucursal'>";
        echo "<option selected value='*'> * T o d o s </option>";
        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=1 ORDER BY id");
        echo "<option value='*'> * | T o d a s </option>";
        while ($Cia=mysql_fetch_array($CiaA)){
               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
        }
        echo "</select></p>";                       
        
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   	  echo "<select name='Institucion'>";     
        echo "<option value=''> *  T o d o s </option>";
  	     while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select></p>";
        
        
        echo "<p>Departamento : ";
        $DepA=mysql_query("select departamento,nombre from dep");
   	  echo "<select name='Depto'>";     
  	     while ($Dep=mysql_fetch_array($DepA)){
               echo "<option value='$Dep[0]'>$Dep[1]</option>";
        }
        echo "</select>";


        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

    echo "</form>";

    echo "<p>&nbsp;</p>";
    
  }elseif($cRep == 16){

    $Fechai=date("Y-m-d");

    echo "<form name='form1' method='get' action='demandadep.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='Fechai' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fechai,'yyyy-mm-dd',this)>  </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='Fechaf' size='9' value ='$Fechai'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fechaf,'yyyy-mm-dd',this)></p>";
        
        
        echo "<p>Sucursal : ";
        echo "<select name='Sucursal'>";
        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=1 ORDER BY id");
        echo "<option value='*'> * | T o d a s </option>";
        while ($Cia=mysql_fetch_array($CiaA)){
               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
        }
        //echo "<option selected value='*'> * T o d o s </option>";
        echo "</select></p>";
        
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select></p>";
               
        
        echo "<p>Departamento : ";
        $DepA=mysql_query("select departamento,nombre from dep");
        echo "<select name='Depto'>";     
        echo "<option value='*'>T o d o s</option>";        
        while ($Dep=mysql_fetch_array($DepA)){
              echo "<option value='$Dep[0]'>$Dep[1]</option>";
        }
        echo "</select></p>";

        echo "<p>Medico, * todos: ";
        echo "<input type='text' name='Medico' value='*' size='5'>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'></p>";
        echo "<p>&nbsp;</p>";
        echo "</form1>";

  }elseif($cRep == 17){

      if($Fechas == 1){
          $FecI=$_REQUEST[FecI];
          $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }   

     echo "<form name='form1' method='get' action='repfacturas.php'>";

    echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
    echo "<p>Fecha Final : [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
    echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
    echo "</table>";                 

    echo "<p>Tipo de Pago: ";
    echo "<select name='Tpago' class='Estilo10'";
    echo "<option value='*'>* Todos</option>";
    echo "<option value='Efectivo'>Efectivo</option>";
    echo "<option value='Tarjetacredito'>Tarjeta Credito</option>";
    echo "<option value='Cheque'>Cheque</option>";
    echo "<option value='Transferencia'>Transferencia</option>";
    echo "<option value='Tarjetadebito'>Tarjeta de debito</option>";
    echo "<option selected value ='*'>* Todos</option>";
    echo "</select></p>";

    echo "Recepcionista, * todos : ";
    echo "<input type='text'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;<br>";

    echo "<p>Status: ";
    echo "<select name='Status' class='Estilo10'";
    echo "<option value='*'>* Todos</option>";
    echo "<option value='Timbrada'>Timbrada</option>";
    echo "<option value='Cancelada'>Cancelada</option>";
    echo "<option value='Abierta'>Abierta</option>";
    echo "<option selected value ='*'>* Todos</option>";
    echo "</select></p>";
        
    echo "<input type='SUBMIT' value='Enviar'>";

    echo "</form>";

        
  }elseif($cRep == 18){

    $Fecha   = date('Y-m-d h:m');

    $FechaI = substr(date('Y-m-d'),0,8)."01";
    $FechaF = date('Y-m-d');

    echo "<form name='form1' method='get' action='repclixins.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FechaI' size='9' value ='$FechaI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>  </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FechaF' size='9' value ='$FechaF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></p>";

        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion");
        echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";
        
        echo "&nbsp; &nbsp; &nbsp; <input type='submit' value='Enviar'></p>";
        echo "<p>&nbsp;</p>";
        echo "</form1>";
		
  }elseif($cRep == 20){
  	 
     if($Fechas == 1){
          $FecI=$_REQUEST[FecI];
          $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }	 

    echo "<form name='form1' method='get' action='ordeneserv.php'>";
    
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
        echo "<select name='Sucursal'>";
        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=1 ORDER BY id");
        echo "<option value='*'> * | T o d a s </option>";
        while ($Cia=mysql_fetch_array($CiaA)){
               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
        }
        //echo "<option selected value='*'> * T o d o s </option>";
        echo "</select></p>";                       
        
        
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</select>";                          
        
        
        echo "<p>Tipo de servicio : ";
        echo "<select name='Servicio'>";
        echo "<option value='1'>* todos</option>";
        echo "<option value='2'>Urgentes</option>";
        echo "<option value='3'>Express</option>";
        echo "</select></p>";

        echo "<p>Servicio Movil : ";
        echo "<select name='Subdepto'>";
		echo "<option value='*'>* Todos</option>";
        echo "<option value='TOMA'>Tomas a Domicilio</option>";
        echo "<option value='RECOLECCION'>Recoleccion de Muestra</option>";
        echo "<option value='TRASLADO'>Traslados</option>";
	    echo "<option value='PORTATIL'>Portatiles</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

    echo "</form>";
	
}elseif($cRep == 21){
 
     if($Fechas == 1){
          $FecI=$_REQUEST[FecI];
          $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }	 

    echo "<form name='form1' method='get' action='ordenesabipmx.php'>";
    
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
        echo "<select name='Sucursal'>";
        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=1 ORDER BY id");
        echo "<option value='*'> * | T o d a s </option>";
        while ($Cia=mysql_fetch_array($CiaA)){
               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
        }
        //echo "<option selected value='*'> * T o d o s </option>";
        echo "</select></p>";                       
        
        
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</select>";                          
        
        
        echo "<p>Tipo de servicio: ";
        echo "<select name='Servicio'>";
        echo "<option value='1'>* todos</option>";
        echo "<option value='2'>Urgentes</option>";
        echo "<option value='3'>Express</option>";
        echo "</select></p>";

        echo "<p>Solo Ordenes C/Descto [Si/No] : ";
        echo "<select name='Descto'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

    echo "</form>";

    echo "<p>&nbsp;</p>";
	
}elseif($cRep == 22){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='tiemposentrega.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//		echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        echo "</select></p>";                       

		echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Solo Estudios Pendientes [Si/No] : ";
        echo "<select name='Arecepcion'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "<p>Solo Estudios Externos [Si/No] : ";
        echo "<select name='Externo'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";
	
  }elseif($cRep == 23){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    	echo "<form name='form1' method='get' action='ordenesabienv.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
        echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";
        
        echo "<p>Sucursal : ";
        echo "<select name='Sucursal'>";
        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=0 ORDER BY id");
        echo "<option value='*'> * | T o d a s </option>";
        while ($Cia=mysql_fetch_array($CiaA)){
               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
        }
        //echo "<option selected value='*'> * T o d o s </option>";
        echo "</select></p>";                       
		
		echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
	    echo "</form1>";
		
  }elseif($cRep == 24){
  	 
     if($Fechas == 1){
          $FecI=$_REQUEST[FecI];
          $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }	 

    echo "<form name='form1' method='get' action='reptransfer.php'>";
    
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

             echo "<p>De: ";
             echo "<select name='Origen'>";
             echo "<option value='invgral'>General</option>";
             echo "<option value='invmatriz'>Matriz</option>";
             echo "<option value='invtepex'>Tepexpan</option>";
             echo "<option value='invhf'>HF</option>";
             echo "<option value='invreyes'>Reyes</option>";
             echo "</select>";

             echo "<p>Para: ";
             echo "<select name='Origen'>";
             echo "<option value='invgral'>General</option>";
             echo "<option value='invmatriz'>Matriz</option>";
             echo "<option value='invtepex'>Tepexpan</option>";
             echo "<option value='invhf'>HF</option>";
             echo "<option value='invreyes'>Reyes</option>";
             echo "</select>";
		
        echo "<br><br>&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

    echo "</form>";

  }elseif($cRep == 25){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	   }	 

    echo "<form name='form1' method='get' action='ordenesabipend2.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";

//        echo "<p>Sucursal : ";
//        echo "<select name='Sucursal'>";
//        $CiaA  = mysql_query("SELECT id,alias FROM cia  WHERE id >=1 ORDER BY id");
//        echo "<option value='*'> * | T o d a s </option>";
//        while ($Cia=mysql_fetch_array($CiaA)){
//               echo "<option value='$Cia[0]'>$Cia[0] | $Cia[1]</option>";
//        }
//        echo "</select></p>";	
	
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Solo Estudios Pendientes [Si/No] : ";
        echo "<select name='Arecepcion'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "<p>Solo Estudios Externos [Si/No] : ";
        echo "<select name='Externo'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";
	
  }elseif($cRep == 26){

  	 if($Fechas == 1){
	  	  $FecI=$_REQUEST[FecI];
		  $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
	 }	 

    echo "<form name='form1' method='get' action='ordenesabipendlcd.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
		echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
		echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
		echo "</table>";
	
        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
   		echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
   		echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
 	    while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Solo Estudios Pendientes [Si/No] : ";
        echo "<select name='Arecepcion'>";
        echo "<option value='N'>No</option>";
        echo "<option value='S'>Si</option>";
        echo "</select>";

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</form>";

}elseif($cRep == 27){

     if($Fechas == 1){
        $FecI=$_REQUEST[FecI];
        $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
   }   

    echo "<form name='form1' method='get' action='repenvio.php'>";
    echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
    echo "<p>Fecha Final : [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

    echo "Hra.inicial : ";
    echo "<input type='text' value='07:00' name='HoraI' size='6' >&nbsp;&nbsp;&nbsp;";
    echo "Hra.final : ";
    echo "<input type='text' value='14:00'  name='HoraF' size='6' >&nbsp;&nbsp;&nbsp;";

    echo "<p>De Sucursal: ";
    $SucA=mysql_query("select * from cia order by id",$link);
    echo "<select name='SucursalDe'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Sucd=mysql_fetch_array($SucA)){
           echo "<option value='$Sucd[id]'>$Sucd[id] - $Sucd[alias]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";

    echo "<p>Para Sucursal: ";
    $SucB=mysql_query("select * from cia order by id",$link);
    echo "<select name='SucursalPara'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Sucp=mysql_fetch_array($SucB)){
           echo "<option value='$Sucp[id]'>$Sucp[id] - $Sucp[alias]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";

    echo "<p>";
  
    echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</p>";
    echo "</form>";
  }elseif($cRep == 28){

      if($Fechas == 1){
        $FecI=$_REQUEST[FecI];
        $FecF=$_REQUEST[FecF];
      }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
      }   

    echo "<form name='form1' method='get' action='repenvioextadm.php'>";
    echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
    echo "<p>Fecha Final : [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

    echo "Hra.inicial : ";
    echo "<input type='text' value='07:00' name='HoraI' size='6' >&nbsp;&nbsp;&nbsp;";
    echo "Hra.final : ";
    echo "<input type='text' value='14:00'  name='HoraF' size='6' >&nbsp;&nbsp;&nbsp;";

    echo "<p>De Sucursal: ";
    $SucA=mysql_query("select * from cia order by id",$link);
    echo "<select name='SucursalDe'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Sucd=mysql_fetch_array($SucA)){
           echo "<option value='$Sucd[id]'>$Sucd[id] - $Sucd[alias]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";

    echo "<p>Para Proveedor: ";
    $SucB=mysql_query("select * from mql order by id",$link);
    echo "<select name='Proveedor'>";
    echo "<option value='*'> *  T o d o s </option>";
    while ($Sucp=mysql_fetch_array($SucB)){
           echo "<option value='$Sucp[id]'>$Sucp[id] - $Sucp[alias]</option>";
    }
    echo "<option selected value='*'> * T o d o s </option>";
    echo "</select>";

    echo "<p>";
    
    echo "Personal, * todos : ";
    echo "<input type='text'  name='personal' value='*' size='15' >&nbsp;&nbsp;&nbsp;<br><br>";

    echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</p>";
    echo "</form>";

  }elseif($cRep == 29){

   if($Fechas == 1){
        $FecI=$_REQUEST[FecI];
      $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }   

    echo "<form name='form1' method='get' action='repentrega.php'>";
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
    echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
    echo "</table>";

    echo "<p>Institucion : ";
    $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
    echo "<select name='Institucion'>";
    echo "<option value=''> *  T o d o s </option>";
    while ($Ins=mysql_fetch_array($InsA)){
           echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
    }
    echo "<option selected value=''> * T o d o s </option>";
    echo "</select>";

    echo "<p>Mostrador : ";
    echo "<select name='Mostrador'>";
    echo "<option value=''> *  T o d o s </option>";
    echo "<option value='1'>Matriz</option>";
    echo "<option value='2'>OHF</option>";    
    echo "<option value='3'>Tepexpan</option>";    
    echo "<option value='4'>Los Reyes</option>";    
    echo "<option selected value=''> * T o d o s </option>";
    echo "</select>";

    echo "&nbsp; &nbsp; &nbsp; Domicilio : ";
    echo "<select name='Domicilio'>";
    echo "<option value=''> *  T o d o s </option>";
    echo "<option value='1'>Paciente</option>";
    echo "<option value='2'>Medico</option>";    
    echo "<option value='3'>Institucion</option>";       
    echo "<option selected value=''> * T o d o s </option>";
    echo "</select>";

    echo "&nbsp; &nbsp; &nbsp; Digital : ";
    echo "<select name='Digital'>";
    echo "<option value=''> *  T o d o s </option>";
    echo "<option value='1'>Email Pac</option>";
    echo "<option value='2'>Whatsap Pac</option>";    
    echo "<option value='3'>Email Med</option>";
    echo "<option value='4'>Whatsap Med</option>"; 
    echo "<option value='5'>Email Inst</option>";      
    echo "<option selected value=''> * T o d o s </option>";
    echo "</select>";
  
    echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";
    echo "</p>";
    echo "</form>";

  }elseif($cRep == 30){
     
     if($Fechas == 1){
          $FecI=$_REQUEST[FecI];
          $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }   

    echo "<form name='form1' method='get' action='impcorte2.php'>";
    
        echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
         echo "<p>Fecha Final : [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
    echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
    echo "</table>";

        echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
        echo "<select name='Institucion'>";
        echo "<option value='*'> *  T o d o s </option>";
      while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value='*'> * T o d o s </option>";
        echo "</select>"; 

    echo "<br><br>&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

    echo "</form>";

  }elseif($cRep == 32){

     echo "<form name='form1' method='get' action='impcortepdf.php' target='_black'>";

        echo "<p>Fecha : ";
        echo "<input type='text'  name='Fecha' size='9' value ='$Fecha'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].Fecha,'yyyy-mm-dd',this)>";
        echo "</p>";

        echo "<p>Sucursal : ";
    echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
    echo "</table>";

    echo "Recepcionista, * todos : ";
    echo "<input type='text'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;<br><br>";
    
    echo "<input type='SUBMIT' value='Enviar'>";

    echo "</form>";

 }elseif($cRep == 40){

     if($Fechas == 1){
        $FecI=$_REQUEST[FecI];
      $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }   

    echo "<form name='form1' method='get' action='demandeptoprod2.php'>";
        echo "<p>Fecha [aaaa-mm-dd]: ";
        echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
    echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
    echo "</table>";                     
    
    echo "<p>Institucion : ";
        $InsA=mysql_query("select institucion,nombre from inst order by institucion",$link);
      echo "<select name='Institucion'>";
        echo "<option value=''> *  T o d o s </option>";
      while ($Ins=mysql_fetch_array($InsA)){
               echo "<option value='$Ins[0]'>$Ins[0] - $Ins[1]</option>";
        }
        echo "<option selected value=''> * T o d o s </option>";
        echo "</select>";

        echo "<p>Departamento : ";
        $Depto=mysql_query("select departamento,nombre from dep",$link);
      echo "<select name='Departamento'>";
        echo "<option value=''> *  T o d o s </option>";
      while ($Depto1=mysql_fetch_array($Depto)){
               echo "<option value='$Depto1[0]'>$Depto1[1]</option>";
        }
      echo "<option selected value=''> * T o d o s </option>";
      echo "</select>";

      if($Usr=='GEM' or $Usr=='GERARDO' or $Usr=='ALEJANDRO' or $Usr=='NAZARIO' or $Usr=='YADIRA'  or $Usr=='ISIDRA'){

        echo "&nbsp; &nbsp; &nbsp; <input type='SUBMIT' value='Enviar'>";

      }
      echo "</form1>";

  }elseif($cRep == 41){

      if($Fechas == 1){
          $FecI=$_REQUEST[FecI];
          $FecF=$_REQUEST[FecF];
     }else{
         $FecI=date("Y-m-d");
         $FecF=date("Y-m-d");
     }   

     echo "<form name='form1' method='get' action='repcambiosmed.php'>";

    echo "<p>Fecha Inicial [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)> </p>";
    echo "<p>Fecha Final : [aaaa-mm-dd]: ";
    echo "<input type='text'  name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)> </p>";

        echo "<p>Sucursal : ";
    echo "<table align='center' width='20%' border='0' cellspacing='1' cellpadding='0'>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Texcoco Matriz</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr>";
    echo "<tr><td><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr>";
    echo "</table><br>";                 

    echo "Recepcionista, * todos : ";
    echo "<input type='text'  name='Recepcionista' value='*' size='10' >&nbsp;&nbsp;&nbsp;<br>";
        
    echo "<br><input type='SUBMIT' value='Enviar'>";

    echo "</form>";

  }elseif($cRep == 42){

    $Fecha    = date("Y-m-d");
    $FechaIni    = date("Y-m-d");

    echo "<form name='form1' method='get' action='detpagosr.php'>";
    echo "<table align='center' width='50%' border='0' cellspacing='1' cellpadding='0'>";


    echo "<p align='center'>Sucursal : ";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursalt' checked>* Todos</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal0'>0 Administracion</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal1'>1 Matriz</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal2'>2 Hospital Futura</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal3'>3 Tepexpan</font></td></tr>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal4'>4 Los Reyes</font></td></tr></p>";
    echo "<tr height='30' class='content_txt'><td></td><td align='left'><font size='2' face='Arial, Helvetica, sans-serif'><input type='checkbox' value='1' name='sucursal5'>5 Camarones</font></td></tr></p>";

     echo "<tr class='content_txt'><td align='right'>$Gfont Pagos: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT id,referencia FROM cptpagod ORDER BY referencia");
    echo "<select class='InputMayusculas' name='Pagos'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[referencia]'> &nbsp; $Ins[referencia] </option>";
    }
    echo "<option selected value=''> &nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Forma de pago: </td><td>&nbsp; ";
    $InsA = mysql_query("SELECT * FROM cpagos ORDER BY id");
    echo "<select class='InputMayusculas' name='Fpago'>";
    while ($Ins = mysql_fetch_array($InsA)) {
        echo "<option value='$Ins[id]'> &nbsp;$Ins[id].- $Ins[concepto] </option>";
    }   
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";

    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Usuario: </td><td>&nbsp; ";
    $InsA   = mysql_query("SELECT uname FROM authuser ORDER BY uname");
    echo "<select class='InputMayusculas' name='Usr'>";           
    while($Ins = mysql_fetch_array($InsA))
    {echo "<option value='$Ins[uname]'> &nbsp; $Ins[uname] </option>";}
    echo "<option selected value=''> &nbsp; Todos</option>";  //se va
    echo "</select> ";
    echo "</td></tr>";
          
    echo "<tr height='30' class='content_txt'><td align='right'>$Gfont Pagos cancelados: </td><td>&nbsp; ";
    echo "<select class='InputMayusculas' name='Cancelado'>";
    echo "<option value='1'>&nbsp; Si </option>";
    echo "<option value='0'>&nbsp; No</option>";
    echo "<option selected value=''>&nbsp; Todos</option>";
    echo "</select> ";
    echo "</td></tr>";
    
    echo "<tr height='30'>";
    echo "<td align='right' width='33%' >";
    echo "$Gfont Fecha inicial: ";
    echo "</td><td valign='center'> &nbsp ";
    echo "<input type='text' name='FechaI' value='$FechaIni' size='10'  class='content_txt'> ";
    echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";
    echo "</td></tr>";
    
    echo "<tr><td align='right'> ";
    echo "$Gfont Fecha final: ";
    echo "</td><td> &nbsp ";
    echo "<input type='text' name='FechaF' value='$Fecha' size='10'  class='content_txt'> ";      
    echo "<img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)></td></tr><tr><td>";
    echo "<br><br></td><td><input class='boton' type='submit' name='Boton' value='enviar'>";
    echo "</td></tr>";   
    echo "</table><br>";                 
    echo "</form>";

  }

  echo "</td></tr>";
  
  echo "<tr><td><a href='menu.php'><img src='lib/regresa.jpg' border='0'></a></td></tr>";

  echo "<tr background='lib/prueba.jpg'>";

  echo "<td><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p></td>";

  echo "</tr></table>";
    
echo "</body>";

echo "</html>";

?>

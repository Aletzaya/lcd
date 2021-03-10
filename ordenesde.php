<?php

  session_start();

  require("lib/kaplib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");


  $busca  = $_REQUEST[busca];

  $pagina = $_REQUEST[pagina];

  $orden  = $_REQUEST[orden];

  $Estudio = $_REQUEST[Estudio];

  $Usr    = $check['uname'];

  $Tabla  = "ot";

  $Titulo = "Estudio de la orden de trabajo [$busca]";

  $link   = conectarse();

  $lBd    = false;

  if($_REQUEST[Boton] == Eliminar ){        //Para agregar uno nuevo

	 $Fecha = date("Y-m-d H:m");

	 $PrmA  = mysql_query("SELECT password FROM cia WHERE id='1'");
	 $Prm   = mysql_fetch_array($PrmA);

	 $Clave = md5($_REQUEST[Passw1]);

	 if($Prm[0] == $Clave ){

       $lUp  = mysql_query("DELETE FROM otd WHERE orden='$busca' AND estudio='$_REQUEST[Estudio]'");

       $CpoA = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd
               WHERE orden='$busca'");

       $Cpo  = mysql_fetch_array($CpoA);

       $lUp  = mysql_query("UPDATE ot SET importe='$Cpo[0]' WHERE orden='$busca'");

       $lUp  = mysql_query("INSERT INTO logs (fecha,usr,concepto) VALUES
       		   ('$Fecha','$Usr','Elima estudio $_REQUEST[Estudio] Ot: $busca')");

       $lBd  = true;


    }else{
       $Msj="La clave no coincide, no es posible realizar ningun cambio";
    }


  }elseif($_REQUEST[Boton] == Abrir ){        //Para agregar uno nuevo

	 $PrmA  = mysql_query("SELECT password FROM cia WHERE id='1'");
	 $Prm   = mysql_fetch_array($PrmA);

	 $Clave = md5($_REQUEST[Passw]);

	 if($Prm[0] == $Clave ){

       $lUp  = mysql_query("UPDATE otd SET status='RESUL' WHERE orden='$busca' AND estudio='$_REQUEST[Estudio]'");

       $lBd  = true;

    }else{

       $Msj="La clave no coincide, no es posible ABRIR es estudio";
    }


  }elseif($_REQUEST[Boton] == Cancelar){

   header("Location: ordenes.php?pagina=$_REQUEST[pagina]");

  }

  if( $_REQUEST[Boton] == Eliminar and $lBd){        //Para r uno nuevo
      header("Location: ordenes.php?pagina=$_REQUEST[pagina]");
  }

  $OtA  = mysql_query("SELECT * from ot where orden='$busca' ");
  $Ot   = mysql_fetch_array($OtA);

  $CpoA = mysql_query("SELECT otd.estudio,est.descripcion FROM est,otd
          WHERE otd.orden='$busca' AND otd.estudio='$_REQUEST[Estudio]' AND est.estudio=otd.estudio ");

  $Cpo  = mysql_fetch_array($CpoA);

  require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<body>

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}
function SiElimina(){
  if(confirm("ATENCION! Desea dar de Baja este registro?")){
     return(true);
   }else{
     document.form1.busca.value="NUEVO";
      return(false);
   }
}


function Completo(){
var lRt;
lRt=true;
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}
</script>
<table width="100%" border="0">
  <tr>
    <td  width='10%' rowspan='2'>
      <?php
         echo "<a class='pg' href='ordenes.php'><img src='lib/regresa.jpg' border='0'></a>";
      ?>
   </td>
   <td align='center'>
   <form name="form1" method="post" action="ordenesde.php">
     <?php
       echo $Gfont;
       echo "<p align='center'><strong>Orden de Trabajo N�mero : $busca</strong></p>";
       echo "Fecha :";
       echo "<input name='Fecha' type= 'text' size='9' value='$Ot[fecha]'> &nbsp; &nbsp; ";
       echo "Hora : $Ot[hora] &nbsp; &nbsp; Importe....: $".number_format($Ot[importe],"2");
       echo " &nbsp; &nbsp; Fec/entrega : $Ot[fechae] </p> ";
       echo "<p>Fecha de entrega real : $Ot[entfec] &nbsp; &nbsp; Hora :$Ot[enthra] &nbsp; &nbsp; Recibio: $Ot[recibio] ";
       echo " &nbsp; &nbsp; Quien lo entrego: $Ot[entusr]";
       echo "<p>Institucion: $Ot[institucion] $Ins[nombre] Servicio...: $Ot[servicio] &nbsp; &nbsp; &nbsp;";
       echo "Razon del descuento...: $Ot[descuento] </p>";

       echo "<hr noshade style='color:66CC66;height:1px'>";

       echo "<p> &nbsp; Paciente...: $Ot[cliente] &nbsp; $Cli[nombrec] &nbsp; No.Receta..:";

       echo "<input name='Receta' type= 'text' size='10' value='$Ot[receta]'>";

       echo "</p>";

       cTable('70%','0');

       cInput("Estudio: ","Text","10","Estudio","right",$Cpo[estudio],"10",false,false,'');

       cInput("Descripcion: ","Text","40","Descripcion","right",$Cpo[descripcion],"40",false,false,'');

       cTableCie();

       echo "<input type='hidden' name='busca' value='$busca'>";
       echo "<input type='hidden' name='Estudio' value='$Estudio'>";

       //echo Elimina();

   	   echo "<br>";
   	   echo "<div align='center'>Para eliminar &eacute;ste movimiento, favor de poner el password y dar click en el boton de <b>Eliminar</b></div>";
       echo "<div align='center'>Password: ";
       echo "<input type='password' style='background-color:#bacbc2;color:#ffffff;font-weight:bold;' name='Passw1' size='15' maxlength='15'>";
       echo " &nbsp; <input type='submit' name='Boton' value='Eliminar'></div><br><br>";

   	   echo "<div align='center'>Para abrir y recapturar los resultados de esta orden favor de escribir el password  y dar click en el boton de <b>Abrir</b></div>";
       echo "<div align='center'>Password: ";
       echo "<input type='password' style='background-color:#bacbc2;color:#ffffff;font-weight:bold;' name='Passw' size='15' maxlength='15'>";
       echo " &nbsp; <input type='submit' name='Boton' value='Abrir'></div>";


  echo "</form>";

  echo "<div>$Msj</div>";

  echo "</td>";

  echo "<td width='15%'>&nbsp;</td>";

  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

mysql_close();

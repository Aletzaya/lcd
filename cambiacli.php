<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  $busca  = $_REQUEST[busca];

  $Titulo  = "Cambio de cliente [$busca]";

  $link    = conectarse();

  if($_REQUEST[Boton] == Aceptar AND $_REQUEST[Cuentanueva] <> '' ){        //Para agregar uno nuevo

	 //$PrmA  = mysql_query("SELECT password FROM cia WHERE id='1'");
	 //$Prm   = mysql_fetch_array($PrmA);

	 //$Clave = md5($_REQUEST[Password]);

	 //if($Prm[0] == $Clave ){
	 //}

	 $CliA  = mysql_query("SELECT cliente FROM cli WHERE cliente='$_REQUEST[Cuentanueva]'");
	 $Cli   = mysql_fetch_array($CliA);

    if($Cli[cliente] <> '') {		//Siexiste

		 $CliOldA  = mysql_query("SELECT numveces FROM cli WHERE cliente='$_REQUEST[busca]'");
		 $CliOld   = mysql_fetch_array($CliOldA);

		 $lUp        = mysql_query("UPDATE cli SET numveces=numveces + '$CliOld[0]' WHERE cliente='$_REQUEST[Cuentanueva]'");
		 //echo "UPDATE cli SET numveces=numveces + $CliOld[0] WHERE cliente='$_REQUEST[Cuentanueva]'";

		 $lUp        = mysql_query("UPDATE ot SET cliente = '$_REQUEST[Cuentanueva]' WHERE cliente='$_REQUEST[busca]'");
		 //echo "UPDATE ot SET cliente = '$_REQUEST[busca]' WHERE cliente='$_REQUEST[Cuentanueva]'";

		 //echo "DELETE FROM cli WHERE cliente='$_REQUEST[busca]'";
		 $lUp        = mysql_query("DELETE FROM cli WHERE cliente='$_REQUEST[busca]'");

		$Msj         = "La unificacion de cuentas se realizo con exito!";

    }else{

		$Msj   = "Lo siento, no es posible hacer el cambio, la cuenda $_REQUEST[Cuentanueva] NO existe";


    }

    //header("Location: ordenescon.php?pagina=$pagina&Msj?$Msj");

  }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
     // header("Location: ordenes.php?pagina=$_REQUEST[pagina]");
  }

  $OtA 	=	mysql_query("SELECT ot.orden,ot.fecha,cli.nombrec,ot.importe,fechae,ot.hora,ot.cliente FROM cli,ot WHERE orden='$busca' AND ot.cliente=cli.cliente");
  $Ot    = mysql_fetch_array($OtA);

  require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

//headymenu($Titulo,0);

?>

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
         echo "$Gfont regresar &nbsp; &nbsp; <br>";
         echo "<a class='pg' href='javascript:window.close()'><img src='lib/regresa.jpg' border='0'></a>";
      ?>
   </td>
   <td align='center'>
     <?php

       echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

       echo $Gfont;
       echo "<p align='center'><strong>Proceso para unificar cuentas: $busca</strong></p>";

       echo "<hr noshade style='color:66CC66;height:1px'>";


		 $CliA  = mysql_query("SELECT nombrec,direccion,localidad,estado,fechan,sexo,telefono FROM cli WHERE cliente='$busca'");
		 $Cli   = mysql_fetch_array($CliA);


			echo "<div align='center'><font color='#990000'>Cuenta a dar de baja</font></div>";

  	   	echo "<table width='70%' border='1' align='center' cellpadding='0' cellspacing='0' class='textosTabla'>";
			echo "<tr>";
			echo "<td align='right' width='30%'>$Gfont Cliente: $busca &nbsp;</td><td width='50%'>";
			echo "$Gfont ".ucwords(strtolower($Cli[nombrec]));
			echo "</td></tr>";
			echo "<tr><td align='right'>$Gfont Direccion: &nbsp;</td><td>$Gfont ";
			echo "$Cli[direccion]";
			echo "</td></tr>";
			echo "<tr><td align='right'>$Gfont Localidad: &nbsp;</td><td>&nbsp;";
			echo "$Gfont $Cli[localidad]";
			echo "</td></tr>";
			echo "<tr><td align='right'>$Gfont Estado: &nbsp;</td><td>&nbsp;";
			echo "$Gfont $Cli[estado]'";
			echo "</td></tr>";
			echo "<tr><td align='right'>$Gfont Telefono: </td><td>&nbsp;";
			echo "$Gfont $Cli[telefono]";
			echo "</td></tr>";
	   	echo "</table>";


		 echo "<p><b>En su lugar poner la cuenta Numero:</b>";

		 echo "<input type='text' name='Cuentanueva'  value='' size='5'> &nbsp; ";

		 //echo "<input type='submit' name='Boton' value='Enviar'>";


		 echo "<p>Atencion! la cuenta $Ot[cliente] sera dada de baja y todas las ordenes seran a signadas a la nueva cuenta</p>";

       //cInput('<b>Password :</b>','password','20','Password','right','','40',true,false,'es necesario para registrar el cambio');

		echo "<input type='hidden' name='busca' value='$busca'>";

       echo Botones();

  echo "</form>";

  echo "</td>";

  echo "<td width='15%'>&nbsp;</td>";

  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

mysql_close();

<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr        = $check['uname'];
  $link       = conectarse();
  $busca      = $_REQUEST[busca]; 
  
  $Return        = "clientesf.php";
  
  $Tabla      = "clif";

  $Titulo     = "Detalle por cliente";
  
  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo
      if($busca=='NUEVO'){

          $lUp = mysql_query("
                 INSERT INTO clif (nombre,direccion,colonia,municipio,telefono,alias,contacto,observaciones,tipodepago,limite,
                 codigo,rfc,correo,numeroint,numeroext,enviarcorreo,cuentaban,estado)
                 VALUES
                 ('$_REQUEST[Nombre]','$_REQUEST[Direccion]','$_REQUEST[Colonia]','$_REQUEST[Municipio]',
                 '$_REQUEST[Telefono]','$_REQUEST[Alias]','$_REQUEST[Contacto]','$_REQUEST[Observaciones]',
                 '$_REQUEST[Tipodepago]','$_REQUEST[Limite]','$_REQUEST[Codigo]','$_REQUEST[Rfc]','$_REQUEST[Correo]',
                 '$_REQUEST[Numeroint]','$_REQUEST[Numeroext]','$REQUEST[Enviarcorreo]','$_REQUEST[Cuentaban]',
                 '$_REQUEST[Estado]')
                 ");
          
                 //$id=mysql_insert_id();

          $Msj = "Registro dado de alta";

  }else{

        $lUp = mysql_query("UPDATE clif SET nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',
               colonia='$_REQUEST[Colonia]',municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',
               alias='$_REQUEST[Alias]',contacto='$_REQUEST[Contacto]',observaciones='$_REQUEST[Observaciones]',
               tipodepago='$_REQUEST[Tipodepago]',limite='$_REQUEST[Limite]',rfc='$_REQUEST[Rfc]',
               codigo='$_REQUEST[Codigo]',correo='$_REQUEST[Correo]',numeroint='$_REQUEST[Numeroint]',
               numeroext='$_REQUEST[Numeroext]',enviarcorreo='$_REQUEST[Enviarcorreo]',cuentaban='$_REQUEST[Cuentaban]',
			   estado='$_REQUEST[Estado]'
               WHERE id='$busca' limit 1");
               $Msj = "Registro actualizado";
  }

  header("Location: $Return?Msj=$Msj");

 }
  
 $cSql="SELECT * FROM $Tabla WHERE id='$busca'";

  

  $CpoA = mysql_query($cSql);
  
  if($Cpo=mysql_fetch_array($CpoA)){
     $Apellidop=$Cpo[apellidop];
     $Apellidom=$Cpo[apellidom];
     $Nombre=$Cpo[nombre];
    // $busca=$Cpo[cliente];
 }
 
 if($busca=='NUEVO'){$lAg=true;}  
 //$lAg=$_REQUEST[Apellidop]<>$Cpo[apellidop];
  

 $Fecha=date("Y-m-d");
 
 
 $aPrg = array('Ninguno','Cliente frecuente','Diabetes','Chequeo medico','Otro');

 require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onLoad="cFocus1()">

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">
function cFocus1(){
  document.form2.Apellidop.focus();
}
function cFocus2(){
  document.form1.Fechan.focus();
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
if(document.form2.Apellidom.value==""){lRt=false;}
if(document.form2.Apellidop.value==""){lRt=false;}
if(document.form2.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
document.form1.Apellidom.value=document.form2.Apellidom.value
document.form1.Apellidop.value=document.form2.Apellidop.value
document.form1.Nombre.value=document.form2.Nombre.value
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Apellidop'){document.form2.Apellidop.value=document.form2.Apellidop.value.toUpperCase();
}if (cCampo=='Apellidom'){document.form2.Apellidom.value=document.form2.Apellidom.value.toUpperCase();
}if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Direccion'){document.form1.Direccion.value=document.form1.Direccion.value.toUpperCase();
}if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();
}if (cCampo=='Telefono'){document.form1.Telefono.value=document.form1.Telefono.value.toUpperCase();
}if (cCampo=='Municipio'){document.form1.Municipio.value=document.form1.Municipio.value.toUpperCase();
}if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
}
</script>

<table width="100%" border="0">
    <?php
    echo "<tr>";

      echo "<td  width='10%'>";
      
         echo "<a href='clientesf.php'><img src='lib/regresa.jpg' border='0'></a><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";

      echo "</td>";
      
      echo "<td align='center'> $Gfont ";
      
	       	 echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos2();'>";

		       	if($lAg) {
       				$Ciudad = $Cia[municipio];
		       		$Estado = $Cia[estado];
       				$Enviarcorreo = 'No';
       				$TipoPago     = 'Efectivo';
       		  	}else {
       				$Ciudad 	  = $Cpo[municipio];
       				$Estado 	  = $Cpo[estado];
       				$TipoPago         = $Cpo[tipodepago];
       				$Enviarcorreo     = $Cpo[enviarcorreo];
       			}

       			cTable('95%','0');

		        cInput("Id:","Text","5","Id","right",$busca,"40",false,true,"");

      		 	cInput("Nombre: ","Text","80","Nombre","right",$Cpo[nombre],"120",true,false,'');

		       	cInput("Alias:","Text","20","Alias","right",$Cpo[alias],"12",true,false,'');

      		 	cInput("R.f.c.:","Text","15","Rfc","right",$Cpo[rfc],"15",true,false,'Sin guiones ni espacios en blanco');
		       	cInput("Calle:","Text","40","Direccion","right",$Cpo[direccion],"80",true,false,'');
                        
                        echo "<tr class='nombre_cliente'><td align='right' bgcolor='#e1e1e1'>$Gfont ";
                        echo "Numero exterior: ";
                        echo "</td><td>$Gfont ";
                        echo "<input type='text' name='Numeroext' size='10' value='$Cpo[numeroext]'> &nbsp ";
                        echo " Numero interior: ";
                        echo "<input type='text' name='Numeroint' size='10' value='$Cpo[numeroint]'> &nbsp ";                        
                        echo "</td><tr>";
                        
                        
      		 	//cInput("Numero exterior:","Text","5","Numeroext","right",$Cpo[numeroext],"5",true,false,'');
		       	//cInput("Numero interior:","Text","5","Numeroint","right",$Cpo[numeroint],"5",true,false,'');
       		 	cInput("Colonia:","Text","40","Colonia","right",$Cpo[colonia],"40",true,false,'');
       		 	cInput("Municipio &oacute; ciudad:","Text","30","Municipio","right",$Ciudad,"30",true,false,'');
       		 	cInput("Estado:","Text","30","Estado","right",$Estado,"30",true,false,'');
		       	cInput("Telefono:","Text","20","Telefono","right",$Cpo[telefono],"20",true,false,'');
      		 	cInput("Codigo postal:","Text","10","Codigo","right",$Cpo[codigo],"10",true,false,'');
       		 	cInput("Correo electronico:","Text","40","Correo","right",$Cpo[correo],"90",true,false,'');
                        echo "<tr class='nombre_cliente'><td align='right' bgcolor='#e1e1e1'>$Gfont Enviar correo: &nbsp;</td><td>$Gfont ";
       		 	echo "<select name='Enviarcorreo'>";
          		echo "<option value='Si'>Si</option>";
       		 	echo "<option value='No'>No</option>";
       	  		echo "<option selected value='$Cpo[enviarcorreo]'>$Cpo[enviarcorreo]</option>";
       	  		echo "</select> en caso de ser varios correos, favor de separar con: <b>; ";
  	    		echo "</td><tr>";
   	 		
                        echo "<tr><td align='right' class='nombre_cliente' bgcolor='#e1e1e1'>$Gfont Tipo de pago: &nbsp;</td><td>";
       		 	echo "<select name='Tipodepago'>";
       		 	//echo "<option value='Prepago'>Prepago</option>";
       		 	echo "<option value='Credito'>Credito</option>";
      		  	echo "<option value='Efectivo'>Efectivo</option>";
      		  	echo "<option value='Tarjeta'>Tarjeta</option>";               // <-- Esto es para marcar la venta que pagan 
      		  	//echo "<option value='Consignacion'>Consignacion</option>";     // <-- Esto es para marcar la venta que pagan 
       		 	//echo "<option value='Cancelado'>Cancelado</option>";           // con tarjeta de credito a estos clientes
		        echo "<option selected value='$TipoPago'>$TipoPago</option>";  //se va
    		        echo "</select>";
  	    		echo "</td><tr>";


                        
       		 	cInput("4 ultimos digitos de la cuenta:","Text","10","Cuentaban","right",$Cpo[cuentaban],"10",true,false,'en caso de pago por transferencia');


       		 	//cInput("Codigo postal:","Text","10","Codigo","right",$Cpo[codigo],"10",true,false,'');
       		 	cInput("Contacto:","Text","40","Contacto","right",$Cpo[contacto],"40",true,false,'');

       			//cInput("Limite de credito:","Text","10","Limite","right",$Cpo[limite],"10",true,false,' si es CREDITO, favor de poner una cantidad en limite de credito');
       		        //cInput('','text','','','','','',false,true,' de lo contrario marcara error de Saldo insuficiente');

                        cTableCie();

                         echo Botones();

                echo "</form>";

                mysql_close();
              ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
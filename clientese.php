<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $Usr        = $check['uname'];
  $link       = conectarse();
  $busca      = $_REQUEST[busca];

  $Nombre     = $_REQUEST[Nombre];
  $Apellidom  = $_REQUEST[Apellidom];
  $Apellidop  = $_REQUEST[Apellidop];
 
  $Tabla      = "cli";

  $Titulo     = "Detalle por cliente";
    
  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      $Sp      = " ";
      $Nombrec = trim($Apellidop).$Sp.trim($Apellidom).$Sp.trim($Nombre);
	  
	  $Fechan  = $_REQUEST[Fechan];
	  $Fecha   = date("Y-m-d");
	  $Mes     = substr($Fechan,5,2);
	  $Dia     = substr($Fechan,8,2);
	  $Ano     = substr($Fechan,0,4);
	
	  if(!checkdate($Mes,$Dia,$Ano)){		//En caso que el valor de Fec/Nac no sea correcto
		 $Anos   = $_REQUEST[Anos];	
		 $Fecha  = date("Y-m-d");
		 $Fechan = substr($Fecha,0,4) - $Anos ."-".substr($Fecha,5,2)."-".substr($Fecha,8,2);
	  }

      if($busca=='NUEVO'){

         $cSql = "INSERT INTO $Tabla (apellidop,apellidom,nombre,direccion,localidad,municipio,telefono,
                credencial,codigo,sexo,titular,mail,padecimiento,alta,fechan,nombrec,afiliacion,observaciones,zona,
                institucion,expiracion,expira,refubicacion,fecha,clasificacion,complemento,otro,usr,
                programa,colonia,curp,codigosis,status,celular,msjadv)
                VALUES 
                ('$Apellidop','$Apellidom','$Nombre','$_REQUEST[Direccion]','$_REQUEST[Localidad]','$_REQUEST[Municipio]',
                '$_REQUEST[Telefono]','$_REQUEST[Credencial]','$_REQUEST[Codigo]','$_REQUEST[Sexo]','$_REQUEST[Titular]',
                '$_REQUEST[Mail]','$_REQUEST[Padecimiento]','$_REQUEST[Fecha]','$Fechan','$Nombrec',
                '$_REQUEST[Afiliacion]','$_REQUEST[Observaciones]','$_REQUEST[Zona]','$_REQUEST[Institucion]',
                '$_REQUEST[Expiracion]','$_REQUEST[Expira]','$_REQUEST[Refubicacion]','$Fecha',
                '$_REQUEST[Clasificacion]','$_REQUEST[Complemento]','$_REQUEST[Otro]','$Usr',
                '$_REQUEST[Programa]','$_REQUEST[Colonia]','$_REQUEST[Curp]','$_REQUEST[Codigosis]','Activo',
				'$_REQUEST[Celular]','$_REQUEST[msjadv]')";

        if (!mysql_query($cSql)) {
            $Archivo = 'CLI';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }
        
        $busca = mysql_insert_id();

        $cSql  = '';
        
       }else{
       
         $cSql  = "UPDATE $Tabla SET apellidop='$_REQUEST[Apellidop]',apellidom='$_REQUEST[Apellidom]',
                nombre='$_REQUEST[Nombre]',direccion='$_REQUEST[Direccion]',localidad='$_REQUEST[Localidad]',
                municipio='$_REQUEST[Municipio]',telefono='$_REQUEST[Telefono]',credencial='$_REQUEST[Credencial]',
                codigo='$_REQUEST[Codigo]',sexo='$_REQUEST[Sexo]',titular='$_REQUEST[Titular]',mail='$_REQUEST[Mail]',
                padecimiento='$_REQUEST[Padecimiento]',alta='$_REQUEST[Alta]',fechan='$Fechan',
                nombrec='$Nombrec',afiliacion='$_REQUEST[Afiliacion]',observaciones='$_REQUEST[Observaciones]',
                zona='$_REQUEST[Zona]',institucion='$_REQUEST[Institucion]',refubicacion='$_REQUEST[Refubicacion]',
                expiracion='$_REQUEST[Expiracion]',expira='$_REQUEST[Expira]',
                clasificacion='$_REQUEST[Clasificacion]',complemento='$_REQUEST[Complemento]',otro='$_REQUEST[Otro]',
                usrmod='$Usr',programa='$_REQUEST[Programa]',fecmod='$Fecha',colonia='$_REQUEST[Colonia]',
                curp='$_REQUEST[Curp]',codigosis='$_REQUEST[Codigosis]',status='$_REQUEST[Status]',celular='$_REQUEST[Celular]',msjadv='$_REQUEST[msjadv]'   
                WHERE cliente='$busca' limit 1";
         
        if (!mysql_query($cSql)) {
            $Archivo = 'CLI';
            die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> $Archivo '  . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
        }

         
         
       }

      if($_REQUEST[Boton] == Aceptar){
         header("Location: clientes.php");
      }

  }elseif($_REQUEST[Lupa_x] > 0){        //Para agregar uno nuevo

      $cSql = "SELECT * FROM $Tabla WHERE (nombre='$_REQUEST[Nombre]' AND apellidop='$_REQUEST[Apellidop]' AND apellidom='$_REQUEST[Apellidom]')";

  }elseif($_REQUEST[Boton] == Cancelar){

         header("Location: clientes.php");

  }else{

      $cSql="SELECT * FROM $Tabla WHERE cliente='$busca'";

  }


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
 
 
$aPrg = array('Ninguno','Cliente frecuente','Apoyo a la salud','Chequeo medico','Empleado','Familiar','Medico','Especializado');

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
//if(document.form1.Fechan.value==""){lRt=false;}
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

function bisiesto($anio_actual){ 
	$bisiesto=false; 
	//probamos si el mes de febrero del a??o actual tiene 29 d??as 
	  if (checkdate(2,29,$anio_actual)){ 
			$bisiesto=true; 
	  } 
		return $bisiesto; 
} 

</script>

<table width="100%" border="0">
    <?php
    echo "<tr>";

      echo "<td  width='10%'>";
      
         echo "<a href='clientes.php'><img src='lib/regresa.jpg' border='0'></a><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";

      echo "</td>";
      
      echo "<td align='left'> $Gfont ";
      
      echo "<form name='form2' method='get' action='clientese2.php'>";
      
           //if($lAg){echo $busca;}else{echo $Cpo[cliente];}

           echo "<div align='center'><font size='+1'>Cliente: $busca &nbsp; $Cpo[nombrec]</font></div>";
           
           echo "<p>Apellido pat.: ";
           echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop' onBlur=Mayusculas('Apellidop')>";
           //echo "<input name='Apellidop' type='text' size='15' value = '$Apellidop' onBlur=Mayusculas('Apellidop')>";
           echo " Apellido mat.:";
           echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom' onBlur=Mayusculas('Apellidom')>";
           //echo "<input name='Apellidom' type='text' size='15' value = '$Apellidom' onBlur=Mayusculas('Apellidom')>";
           echo " Nombre: ";
           echo " <input name='Nombre' type='text' size='10' value = '$Nombre' onBlur=Mayusculas('Nombre')>";
           //echo " <input name='Nombre' type='text' size='10' value = '$Nombre' onBlur=Mayusculas('Nombre')>";
           echo " <input type='IMAGE' name='Lupa' src='images/lupa.gif' alt='Busca un paciente con estos datos' > ";

           echo "<input type='hidden' name='orden' value='$orden'>";
           echo "<input type='hidden' name='pagina' value='$pagina'>";
           echo "<input type='hidden' name='busca' value='NUEVO'>";
           echo "</font></p>";

     echo "</form>";
    

    echo "<form name='form1' method='get' action='clientese.php' onSubmit='return Completo();'>";
	 
      $Fechanac  = $Cpo[fechan];
      $Fecha   = date("Y-m-d");
	  $array_nacimiento = explode ( "-", $Fechanac ); 
	  $array_actual = explode ( "-", $Fecha ); 
	  $anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos a??os 
	  $meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
	  $dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos d??as 

		if ($dias < 0) 
		{ 
			--$meses; 
		
			//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
			switch ($array_actual[1]) { 
				   case 1:     $dias_mes_anterior=31; break; 
				   case 2:     $dias_mes_anterior=31; break; 
				   case 3:  $dias_mes_anterior=28; break;
//						if (bisiesto($array_actual[0])) 
//						{ 
//							$dias_mes_anterior=29; break; 
//						} else { 
//							$dias_mes_anterior=28; break; 
//						} 
				   case 4:     $dias_mes_anterior=31; break; 
				   case 5:     $dias_mes_anterior=30; break; 
				   case 6:     $dias_mes_anterior=31; break; 
				   case 7:     $dias_mes_anterior=30; break; 
				   case 8:     $dias_mes_anterior=31; break; 
				   case 9:     $dias_mes_anterior=31; break; 
				   case 10:     $dias_mes_anterior=30; break; 
				   case 11:     $dias_mes_anterior=31; break; 
				   case 12:     $dias_mes_anterior=30; break; 
			} 
		
			$dias=$dias + $dias_mes_anterior; 
		} 
//
		//ajuste de posible negativo en $meses 
		if ($meses < 0) 
		{ 
			--$anos; 
			$meses=$meses + 12; 
		} 

     			 if($lAg){

					 $Clasificacion = "El mismo";
					 $Sexo          = "M";
					 $Zona          = 1;
					 $Institucion   = 1;
					 
     			    if($_REQUEST[Estado]==''){
     			 	    $Estado    = "Estado de Mexico";
     			 	 }else{
     			 	    $Estado    = $_REQUEST[Estado];
     			 	    $Municipio = $_REQUEST[Municipio];
     			 	    $Colonia   = $_REQUEST[Colonia];
     			 	 }          			 	       	
     			 	 		   
     			 }else{
					 
					 if($_REQUEST[est]=='1'){$Estado=$_REQUEST[Estado];}else{$Estado=$Cpo[estado];}
					 
					 //if($Cpo[estado]==$_REQUEST[Estado]){$Estado=$Cpo[estado];}else{$Estado=$_REQUEST[Estado];}
					 
					 if($Cpo[municipio]==''){$Municipio=$_REQUEST[Municipio];}else{$Municipio=$Cpo[municipio];}

					 if($Cpo[colonia]==''){$Colonia=$_REQUEST[Colonia];}else{$Colonia=$Cpo[colonia];}
					      			 
					 $Clasificacion = $Cpo[clasificacion];     			 	 
     			 	 //$Colonia       = $Cpo[colonia];
     			 	 $Sexo		    = $Cpo[sexo];
     			 	 $Codigo		    = $Cpo[codigo];
//                $Anos          = $Fecha - $Cpo[fechan];
                $Institucion   = $Cpo[institucion];
					 $Zona          = $Cpo[zona];
     			 }
				
				if($_REQUEST[cod]==1){
					$cod2="where codigo='$_REQUEST[Codigosis]'";
				}else{
					$cod2='';
				}
									 
				
              cTable('70%','0'); 
   
             cTable('100%','0');
             

             $EdoA  = mysql_query("SELECT DISTINCT estado,codigo FROM estados $cod2 ORDER BY estado");
             
             echo "<tr><td align='right' height='30'>$Gfont Estado: </td><td>";
             echo "<SELECT name='Estado'>";
             while($Edo=mysql_fetch_array($EdoA)){
             	echo "<option value='$Edo[0]'>$Edo[0]</option>";             
             	//echo "<option value='".utf8_encode($Mpio[0])."'>".utf8_encode($Mpio[0])."</option>";             
             }
 			 if($_REQUEST[cod]<>1){
             	echo "<option selected value='$Estado'>$Estado</option>";
			 }
             echo "</select>";
			 echo "<input type='hidden' name='est' value='1'>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";
			 
			 if($_REQUEST[cod]==1){
             	$MpioA  = mysql_query("SELECT municipio FROM estados $cod2 GROUP BY municipio ORDER BY municipio");
			 }else{
              	$MpioA  = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");
			 }
			 
             echo "<tr><td align='right' height='30'>$Gfont Municipio: </td><td>";
             echo "<SELECT name='Municipio'>";
             while($Mpio=mysql_fetch_array($MpioA)){
             	echo "<option value='$Mpio[0]'>$Mpio[0]</option>";             
             	//echo "<option value='".utf8_encode($Mpio[0])."'>".utf8_encode($Mpio[0])."</option>";             
             }
  			 if($_REQUEST[cod]<>1){
	             echo "<option selected value='$Municipio'>$Municipio</option>";
			 }
             echo "</select>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";

			 if($_REQUEST[cod]==1){
	             $ColA  = mysql_query("SELECT colonia,codigo FROM estados $cod2 ORDER BY colonia");
			 }else{
	             $ColA  = mysql_query("SELECT colonia,codigo FROM estados WHERE municipio = '$Municipio' ORDER BY colonia");
			 }
             
             echo "<tr><td align='right' height='30'>$Gfont Colonia: </td><td>";
             echo "<SELECT name='Colonia'>";
             while($Col=mysql_fetch_array($ColA)){
             	echo "<option value='$Col[0]'>$Col[0]</option>";  
             	if($Col[0]==$Colonia){$Codigosis=$Col[codigo];}           
             }
  			 if($_REQUEST[cod]<>1){
	             echo "<option selected value='$Colonia'>$Colonia</option>";
			 }
             echo "</select>";
			 echo "<input type='hidden' name='cod' value='2'>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";
             
			 echo "<tr><td align='right' height='30'>$Gfont Codigo postal: &nbsp; </td><td>";
			 echo "<input type='text' name='Codigo' value='$Codigo' size='5'>$Gfont ";
							 
			 echo "Segun sistema:&nbsp;";
  			 if($_REQUEST[cod]<>1){
				 echo "<input type='text' name='Codigosis' value='$Codigosis' size='5'> &nbsp; ";
			 }else{
				 echo "<input type='text' name='Codigosis' value='$_REQUEST[Codigosis]' size='5'> &nbsp; ";
			 }
			 echo "<input type='hidden' name='cod' value='1'>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";
			 echo "</td></tr>";
             
             //cInput("Codigo postal: ","Text","10","Codigo","right",$Codigo,"10",false,false,'');

             cInput("Calle y n&uacute;mero: ","Text","60","Direccion","right",$Cpo[direccion],"40",false,false,'');

             cInput("Telefono: ","Text","20","Telefono","right",$Cpo[telefono],"20",false,false,'');
			 
             cInput("Celular: ","Text","20","Celular","right",$Cpo[celular],"20",false,false,'');

             cInput('','text','','','','','',false,true,'-------------------------------');


			 echo "<tr><td align='right'>$Gfont Fecha alta: &nbsp; </td><td>";
			 echo "<input type='text' name='Fecha' value='$Fecha' size='10'>$Gfont ";				 
			 echo "Fecha nac.:&nbsp;";
			 echo "<input type='text' name='Fechan' value='$Cpo[fechan]' size='10'> &nbsp; ";
			 echo "Edad:&nbsp;";
			 echo "<input type='text' name='Anos' value='$anos' size='3'> A&ntilde;os $meses Meses $dias Dias &nbsp;";
			 echo "Sexo:&nbsp;";				 
             echo "<SELECT name='Sexo'>";
             echo "<option value='M'>M</option>";
             echo "<option value='F'>F</option>";
             echo "<option selected value='$Sexo'>$Sexo</option>";
             echo "</select>";
				 
				 echo "</td></tr>";
             //cInput("Fecha alta: ","Text","10","Fecha","right",$Fecha,"10",false,false,'');

             //cInput("Fecha Nac.: ","Text","10","Fechan","right",$Cpo[fechan],"10",false,false,'');


             //cInput("No.A&ntilde;os: ","Text","5","Anos","right",$Anos,"5",false,false,'');
                               
             cInput("Credencial: ","Text","20","Credencial","right",$Cpo[credencial],"20",false,false,'');
             cInput("Titular: ","Text","20","Titular","right",$Cpo[titular],"20",false,false,'');
             cInput("Mail: ","Text","40","Mail","right",$Cpo[mail],"40",false,false,'');

             echo "<tr><td align='right'>$Gfont Clasificacion familiar: &nbsp;</td><td>";
             echo "<SELECT name='Clasificacion'>";
             echo "<option value='El mismo'>El mismo</option>";
             echo "<option value=Esposa>Esposa</option>";
             echo "<option value=Esposo>Esposo</option>";
             echo "<option value=1er.hijo>1er.hijo</option>";
             echo "<option value=2do.hijo>2do.hijo</option>";
             echo "<option value=3er.hijo>3er.hijo</option>";
             echo "<option value=4to.hijo>4o.hijo</option>";
             echo "<option value=5to.hijo>5o.hijo</option>";
             echo "<option value=Mam??>Mam??</option>";
             echo "<option value=Pap??>Pap??</option>";
             echo "<option value=Concubina>Concubina</option>";
             echo "<option value=Otro>Otro</option>";
             echo "<option selected value='$Clasificacion'>$Clasificacion</option>";
             echo "</select>";
             echo "</td></tr>";

             cInput("Afiliacion: ","Text","40","Afiliacion","right",$Cpo[afiliacion],"40",false,false,'');

             echo "<tr><td align='right'>$Gfont Expira: &nbsp;</td><td>";
             echo "<SELECT name='Expira'>";
             echo "<option value='S'>S</option>";
             echo "<option value='N'>N</option>";
             echo "<option selected value='$Cpo[expira]'>$Cpo[expira]</option>";
             echo "</select>$Gfont ";
				 echo "Fecha de expiracion:&nbsp;";
				 echo "<input type='text' name='Expiracion' value='$Cpo[expiracion]' size='10'> &nbsp; ";				 
             
             echo "</td></tr>";

             //cInput("Fecha expiracion: ","Text","10","Expiracion","right",$Cpo[expiracion],"10",false,false,'');

             cInput("Curp: ","Text","40","Curp","right",$Cpo[curp],"40",false,false,'');

             cInput("Otro dato: ","Text","40","Otro","right",$Cpo[otro],"40",false,false,'');


  				 $ZnaA = mysql_query("SELECT zona,descripcion FROM zns ORDER BY zona");             
             echo "<tr><td align='right'>$Gfont Zona: </td><td>";
             echo "<SELECT name='Zona'>";
             while($Zna=mysql_fetch_array($ZnaA)){
             	echo "<option value='$Zna[0]'>$Zna[0] $Zna[1]</option>"; 
             	if($Zna[0]==$Zona){$DispZ=$Zna[1];}            
             }
             echo "<option selected value='$Zona'>$DispZ</option>";
             echo "</select>";
             echo "</td></tr>";
             
  				 $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
             echo "<tr><td align='right'>$Gfont Institucion: </td><td>";
             echo "<SELECT name='Institucion'>";
             while($Ins=mysql_fetch_array($InsA)){
             	echo "<option value='$Ins[0]'>$Ins[0] $Ins[1]</option>";
             	if($Ins[0]==$Institucion){$DispI=$Ins[1];}             
             }
             echo "<option selected value='$Institucion'>$DispI</option>";
             echo "</select>";
             echo "</td></tr>";

             echo "<tr><td align='right'>$Gfont Programa d'salud: </td><td>";

					  $nPrg = $Cpo[programa];
					  
             echo "<SELECT name='Programa'>";
             echo "<option value='1'>1.Cliente frecuente</option>";
             echo "<option value='2'>2.Apoyo a la salud</option>";
             echo "<option value='3'>3.Chequeo medico</option>";
             echo "<option value='4'>4.Empleado</option>";
             echo "<option value='5'>5.Familiar</option>";
             echo "<option value='6'>6.Medico</option>";
             echo "<option value='7'>7.Especializado</option>";
             echo "<option value='0'>0.Ninguno</option>";
             echo "<option SELECTED value='$Cpo[programa]'>$aPrg[$nPrg]</option>";
             echo "</SELECT> <br> ";
             echo "</td></tr>";
                 
				 echo "<tr><td align='right' valign='bottom'>$Gfont Padecimientos:&nbsp;</td><td>";
             echo "<TEXTAREA NAME='Padecimiento' cols='45' rows='2'>$Cpo[padecimiento]</TEXTAREA>";
             echo "</td></tr>";   
				 echo "<tr><td align='right' valign='bottom'>$Gfont Observaciones:&nbsp;</td><td>";
             echo "<TEXTAREA NAME='Observaciones' cols='45' rows='2'>$Cpo[observaciones]</TEXTAREA>";
             echo "</td></tr>";   
         echo "<tr><td align='right' valign='bottom'>$Gfont Referencia de la ubicacion:&nbsp;</td><td>";
             echo "<TEXTAREA NAME='Refubicacion' cols='45' rows='2'>$Cpo[refubicacion]</TEXTAREA>";
             echo "</td></tr>";
             echo "<tr><td align='right' valign='bottom'>$Gfont Mensaje de Advertencia:&nbsp;</td><td>";
             echo "<TEXTAREA NAME='msjadv' cols='45' rows='2'>$Cpo[msjadv]</TEXTAREA>";
             echo "</td></tr>";  
			  
			  echo "<tr><td align='right'>$Gfont Status:</td><td>";
			  echo "<select name='Status'>";
			  echo "<option value='Activo'>Activo</option>";
			  echo "<option value='Inactivo'>Inactivo</option>";
			  echo "<option value='Defuncion'>Defuncion</option>";
			  echo "<option value='Baja'>Baja</option>";
			  echo "<option value='Otro'>Otro</option>";
			  echo "<option selected>$Cpo[status]</option>";
			  echo "</select>";
			  echo "</td></tr>";
			  
             cTableCie();

                echo "$Gfont <p align='center'><b>Usr.alta:</b> $Cpo[usr] <b>Fecha:</b> $Cpo[fecha] &nbsp;&nbsp;&nbsp; <b>Usr.ult.mod:</b> $Cpo[usrmod] &nbsp; <b>Fecha:</b> $Cpo[fecmod] </p>";

                echo "<input type='hidden' name='Apellidop'>";
                echo "<input type='hidden'name='Apellidom'>";
                echo "<input type='hidden' name='Nombre'>";

                echo Botones();

                mysql_close();
              ?>
    </form>
  </td>
  </tr>
</table>
</body>
</html>
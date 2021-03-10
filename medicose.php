<?php

  session_start();

  #Variables necesarias para el regreso;
  $pagina     = $_REQUEST[pagina];
  $orden      = $_REQUEST[orden];
  $Sort       = $_REQUEST[Sort];
  $busca      = $_REQUEST[busca];
  $Estado      = $_REQUEST[Estado];

  $Tabla      = "med";
  $Titulo     = "Detalle por medico";

  $Apellidop  = trim($_REQUEST[Apellidop]);
  $Apellidom  = trim($_REQUEST[Apellidom]);
  $Nombre     = trim($_REQUEST[Nombre]);
  $Fecha  = date("Y-m-d H:i");
  $Usr        = $_COOKIE['USERNAME'];

  require("lib/lib.php");

  $link       = conectarse();
  
  if($_REQUEST[opc]=='X'){
     $xx  = ' ';
	  $lUp  = mysql_query("UPDATE med SET ".
	          " nombrec = ".trim(apellidom)." prueba "." WHERE medico='BUJ'");	  
  }

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo     $Sp=" ";

     $NomCom=trim($Apellidop)."&nbsp;".trim($Apellidom)."&nbsp;".trim($Nombre);

     if($busca=='NUEVO'){

          $lUp = mysql_query("
          INSERT into $Tabla 
          (medico,apellidop,apellidom,nombre,rfc,cedula,codigo,nombrec,especialidad,subespecialidad,
          dirparticular,locparticular,telparticular,dirconsultorio,locconsultorio,telconsultorio,telcelular,
          mail,enviomail,diasconsulta,hravisita,hraconsulta,zona,institucion,comisiones,telinstitucion,fechaa,fechanac,comision,
          status,fecharev,refubicacion,servicio,observaciones,munconsultorio,munparticular,ruta,institucionp,
          estado,edocons,codigosis,usr,fecha,clasificacion,promotorasig)
          VALUES 
          ('$_REQUEST[Medico]','$_REQUEST[Apellidop]','$_REQUEST[Apellidom]','$_REQUEST[Nombre]','$_REQUEST[Rfc]','$_REQUEST[Cedula]','$_REQUEST[Codigo]','$NomCom','$_REQUEST[Especialidad]','$_REQUEST[Subespecialidad]',
          '$_REQUEST[Dirparticular]','$_REQUEST[Locparticular]','$_REQUEST[Telparticular]','$_REQUEST[Dirconsultorio]','$_REQUEST[Locconsultorio]','$_REQUEST[Telconsultorio]','$_REQUEST[Telcelular]',
          '$_REQUEST[Mail]','$_REQUEST[Enviomail]','$_REQUEST[Diasconsulta]','$_REQUEST[Hravisita]','$_REQUEST[Hraconsulta]','$_REQUEST[Zona]','$_REQUEST[Institucion]','$_REQUEST[Comisiones]',
          '$_REQUEST[Telinstitucion]','$_REQUEST[Fechaa]','$_REQUEST[Fechanac]','$_REQUEST[Comision]','$_REQUEST[Status]','$_REQUEST[Fecharev]','$_REQUEST[Refubicacion]','$_REQUEST[Servicio]','$_REQUEST[Observaciones]','$_REQUEST[Munconsultorio]',
          '$_REQUEST[Munparticular]','$_REQUEST[Ruta]','$_REQUEST[Institucionp]','$_REQUEST[Estado]','$_REQUEST[Edoconsu]','$_REQUEST[Codigosis]',
          '$Usr','$Fecha','$_REQUEST[Clasificacion]','$_REQUEST[Promotorasig]')"
          );

            $lUp3  = mysql_query("INSERT INTO loginst (fecha,usr,medico,concepto) VALUES ('$Fecha','$Usr','$_REQUEST[busca]','Da de Alta Medico')");
 	  }else{

        $datosmed  = "SELECT * FROM $Tabla WHERE medico='$busca'";

        $datosA  = mysql_query($datosmed);
        $datos   = mysql_fetch_array($datosA);

        $campomod = ' ';

        if (strcasecmp($datos[observaciones], $_REQUEST[Observaciones]) !== 0){
            $campomod = ', Observaciones';
        }

        if(strcasecmp($datos[servicio], $_REQUEST[Servicio]) !== 0){
            $campomod .= ', Servicio';
        }

        if(strcasecmp($datos[refubicacion], $_REQUEST[Refubicacion]) !== 0){
            $campomod .= ', Referencia de la ubicacion';
        }

         $lUp=mysql_query("UPDATE $Tabla SET 
         apellidop='$Apellidop',apellidom='$Apellidom',nombre='$Nombre',rfc='$_REQUEST[Rfc]',
         cedula='$_REQUEST[Cedula]',codigo='$_REQUEST[Codigo]',nombrec='$NomCom',
         especialidad='$_REQUEST[Especialidad]',subespecialidad='$_REQUEST[Subespecialidad]',
         dirparticular='$_REQUEST[Dirparticular]',dirconsultorio='$_REQUEST[Dirconsultorio]',
         locparticular='$_REQUEST[Locparticular]',telparticular='$_REQUEST[Telparticular]',
         zona='$_REQUEST[Zona]',hraconsulta='$_REQUEST[Hraconsulta]',hravisita='$_REQUEST[Hravisita]',
         comisiones='$_REQUEST[Comisiones]',diasconsulta='$_REQUEST[Diasconsulta]',mail='$_REQUEST[Mail]',enviomail='$_REQUEST[Enviomail]',telcelular='$_REQUEST[Telcelular]',telconsultorio='$_REQUEST[Telconsultorio]',locconsultorio='$_REQUEST[Locconsultorio]',telinstitucion='$_REQUEST[Telinstitucion]',
         fechaa='$_REQUEST[Fechaa]',fechanac='$_REQUEST[Fechanac]',comision='$_REQUEST[Comision]',
         status='$_REQUEST[Status]',fecharev='$_REQUEST[Fecharev]',refubicacion='$_REQUEST[Refubicacion]',
         servicio='$_REQUEST[Servicio]',observaciones='$_REQUEST[Observaciones]',
         munparticular='$_REQUEST[Munparticular]',munconsultorio='$_REQUEST[Munconsultorio]',
         institucion='$_REQUEST[Institucion]',ruta='$_REQUEST[Ruta]',institucionp='$_REQUEST[Institucionp]',
         estado='$_REQUEST[Estado]',edocons='$_REQUEST[Edocons]',codigosis='$_REQUEST[Codigosis]',fecmod='$Fecha',
		 usrmod='$Usr',clasificacion='$_REQUEST[Clasificacion]',promotorasig='$_REQUEST[Promotorasig]'
         WHERE medico='$busca' limit 1");


        $lUp3  = mysql_query("INSERT INTO logmed (fecha,usr,medico,concepto) VALUES ('$Fecha','$Usr','$_REQUEST[busca]','Modifica datos de Medico $campomod')");
         
 	  }

     header("Location: medicos.php?&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*");

  }elseif($_REQUEST[Boton] == Cancelar){

      header("Location: medicos.php?&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*");
	  
  }
 
  $cIns  = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
  $cSql  = "SELECT * FROM $Tabla WHERE medico='$busca'";

  $CpoA  = mysql_query($cSql);
  $Cpo   = mysql_fetch_array($CpoA);
  
  $lAg   = $busca<>$Cpo[medico];

  $aMes  = array("","Ene","Feb","Mzo","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic",);
  
  require ("confignew.php");

echo "<html>";

echo "<head>";

echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>";

echo "<title>$Titulo</title>";

headymenu($Titulo,0);

echo "</head>";

?>

<script language="JavaScript1.2">
function Completo(){
var lRt;
lRt=true;
if(document.form1.Apellidom.value==""){lRt=false;}
if(document.form1.Apellidop.value==""){lRt=false;}
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Apellidop'){document.form1.Apellidop.value=document.form1.Apellidop.value.toUpperCase();
}if (cCampo=='Medico'){document.form1.Medico.value=document.form1.Medico.value.toUpperCase();
}if (cCampo=='Apellidom'){document.form1.Apellidom.value=document.form1.Apellidom.value.toUpperCase();
}if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();
}
}
</script>

<?php

echo "<body bgcolor='#FFFFFF'>";

echo "<table width='100%' border='0'>";

echo "<tr><td width='10%' align='center'>";

   echo "<a href='medicos.php?&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'><img src='lib/regresa.jpg' border='0'></a>";

   echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";

echo "</td>";

echo "<td align='center'>$Gfont ";

   echo "<form name='form1' method='get' action='medicose.php' onSubmit='return Completo();' >";
          
             if($lAg){    
                        
                        
				if($_REQUEST[Estado]==''){$Estado="Estado de Mexico";}else{$Estado=$_REQUEST[Estado];}
               $Munparticular = $_REQUEST[Munparticular];
               $Locparticular = $_REQUEST[Locparticular];
				if($_REQUEST[Edocons]==''){$Edocons="Estado de Mexico";}else{$Edocons=$_REQUEST[Edocons];}
               $Munconsultorio = $_REQUEST[Munconsultorio];
               $Locconsultorio = $_REQUEST[Locconsultorio];
               
  			 	   $Apellidop = $_REQUEST[Apellidop];
  			 	   $Apellidom = $_REQUEST[Apellidom];
  			 	   $Nombre    = $_REQUEST[Nombre];
  			 	   $Medico    = $_REQUEST[Medico];  			 	     			 	
  			 	   $Sexo      = "M";
               $Fechaa    = $Fecha;

  			 	   $Dirparticular  = $_REQUEST[Dirparticular];  			 	     			 	
    
                            
             }else{

				   if($_REQUEST[Estado]<>''){$Estado=$_REQUEST[Estado];}else{$Estado=$Cpo[estado];}
               if($_REQUEST[Munparticular]<>''){$Munparticular = $_REQUEST[Munparticular];}else{$Munparticular=$Cpo[munparticular];}
               if($_REQUEST[Locparticular]<>''){$Locparticular = $_REQUEST[Locparticular];}else{$Locparticular=$Cpo[locparticular];}
				   if($_REQUEST[Edocons]<>''){$Edocons=$_REQUEST[Edocons];}else{$Edocons=$Cpo[edocons];}
               if($_REQUEST[Munconsultorio]<>''){$Munconsultorio = $_REQUEST[Munconsultorio];}else{$Munconsultorio=$Cpo[munconsultorio];}
               if($_REQUEST[Locconsultorio]<>''){$Locconsultorio = $_REQUEST[Locconsultorio];}else{$Locconsultorio=$Cpo[locconsultorio];}

               $Sexo      = $Cpo[sexo];
               $Codigo    = $Cpo[codigo];
          	   $Fechaa    = $Cpo[fechaa];
  			 	   $Apellidop = $Cpo[apellidop];
  			 	   $Apellidom = $Cpo[apellidom];
  			 	   $Nombre    = $Cpo[nombre];
  			 	   $Medico    = $Cpo[medico];
  
  			 	   $Dirparticular  = $Cpo[dirparticular];  			 	     			 	

             }
          
          
          echo "<font color='#003399' size='+1'><div align='left'><b> $Cpo[nombrec]</b></div></font><br>";
                              
          cTable('70%','0'); 
                   
          cInput('Medico:','text','8','Medico','right',$Medico,'8',true,$lAgr,'');
          cInput('Apellido paterno:','text','30','Apellidop','right',$Apellidop,'30',true,false,'');
          cInput('Apellido materno:','text','30','Apellidom','right',$Apellidom,'30',true,false,'');
          cInput('Nombre:','text','50','Nombre','right',$Nombre,'50',true,false,'');
          //cInput('','text','15','Nombre','right',$Cpo[nombrec],'40',false,true,'');
          cInput('Fecha alta:','text','10','Fechaa','right',$Fechaa,'10',false,false,'');
          cInput('Fecha Nacimiento:','text','10','Fechanac','right',$Cpo[fechanac],'10',false,false,'');
          cInput('R.f.c.:','text','15','Rfc','right',$Cpo[rfc],'15',false,false,'');
          cInput('Cedula:','text','15','Cedula','right',$Cpo[cedula],'15',false,false,'');
          cInput('Codigo postal:','text','5','Codigo','right',$Cpo[codigo],'5',false,false,'');
          cInput('Especialidad:','text','20','Especialidad','right',$Cpo[especialidad],'20',false,false,'');
          cInput('Sub-especialidad:','text','20','Subespecialidad','right',$Cpo[subespecialidad],'20',false,false,'');
  
          cInput('Datos particulares','','','','right','','','','','');

             echo "<tr><td align='right' height='23'>$Gfont Estado: </td><td>";
             echo "<SELECT name='Estado'>";
             echo "<option value='Aguascalientes'>Aguascalientes</option>";
             echo "<option value='Baja California'>Baja California</option>";
             echo "<option value='Campeche'>Campeche</option>";
             echo "<option value='Chiapas'>Chiapas</option>";
             echo "<option value='Chihuahua'>Chihuahua</option>";
             echo "<option value='Coahuila'>Coahuila</option>";
             echo "<option value='Colima'>Colima</option>";
             echo "<option value='Distrito Federal'>Distrito Federal</option>";
             echo "<option value='Durango'>Durango</option>";
             echo "<option value='Guanajuato'>Guanajuato</option>";
             echo "<option value='Guerrero'>Guerrero</option>";
             echo "<option value='Hidalgo'>Hidalgo</option>";
             echo "<option value='Jalisco'>Jalisco</option>";
             echo "<option value='Estado de Mexico'>Estado de Mexico</option>";
             echo "<option value='Michoacan'>Michoacan</option>";
             echo "<option value='Morelos'>Morelos</option>";
             echo "<option value='Nayarit'>Nayarit</option>";
             echo "<option value='Nuevo Leon'>Nuevo Leon</option>";
             echo "<option value='Oaxaca'>Oaxaca</option>";
             echo "<option value='Queretaro'>Queretaro</option>";
             echo "<option value='Quintana Roo'>Quintana Roo</option>";
             echo "<option value='San Luis Potosi'>San Luis Potosi</option>";
             echo "<option value='Sinaloa'>Sinaloa</option>";
             echo "<option value='Sonora'>Sonora</option>";
             echo "<option value='Tabasco'>Tabasco</option>";
             echo "<option value='Tlaxcala'>Tlaxcala</option>";
             echo "<option value='Veracruz'>Veracruz</option>";
             echo "<option selected value='$Estado'>$Estado</option>";
             echo "</select>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";

             $MpioA  = mysql_query("SELECT municipio FROM estados WHERE estado = '$Estado' GROUP BY municipio ORDER BY municipio");
             
             echo "<tr><td align='right' height='30'>$Gfont Municipio: </td><td>";
             echo "<SELECT name='Munparticular'>";
             while($Mpio=mysql_fetch_array($MpioA)){
             	echo "<option value='$Mpio[0]'>$Mpio[0]</option>";             
             	//echo "<option value='".utf8_encode($Mpio[0])."'>".utf8_encode($Mpio[0])."</option>";             
             }
             echo "<option selected value='$Munparticular'>$Munparticular</option>";
             echo "</select>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";
                          
             $ColA  = mysql_query("SELECT colonia,codigo FROM estados WHERE municipio = '$Munparticular' ORDER BY colonia");
             
             echo "<tr><td align='right' height='30'>$Gfont Colonia: </td><td>";
             echo "<SELECT name='Locparticular'>";
             while($Col=mysql_fetch_array($ColA)){
             	echo "<option value='$Col[0]'>$Col[0]</option>";  
             	if($Col[0]==$Locparticular){$Codigosis=$Col[codigo];}           
             }
             echo "<option selected value='$Locparticular'>$Locparticular</option>";
             echo "</select>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";

				 echo "<tr><td align='right' height='30'>$Gfont Codigo postal: &nbsp; </td><td>";
				 echo "<input type='text' name='Codigo' value='$Codigo' size='5'>$Gfont ";				 
				 echo "Segun sistema:&nbsp;";
				 echo "<input type='text' name='Codigosis' value='$Codigosis' size='5'> &nbsp; ";
				 echo "</td></tr>";
           
           cInput('Direccion:','text','30','Dirparticular','right',$Dirparticular,'40',false,false,'');


          //cInput('Colonia:','text','20','Locparticular','right',$Cpo[locparticular],'20',false,false,'');
          //cInput('Municipio:','text','20','Munparticular','right',$Cpo[munparticular],'20',false,false,'');
          //cInput('Telefono:','text','20','Telparticular','right',$Cpo[telparticular],'20',false,false,'');

          cInput('Datos del consultorio','','','','right','','','','','');

             echo "<tr><td align='right' height='23'>$Gfont Estado: </td><td>";
             echo "<SELECT name='Edocons'>";
             echo "<option value='Aguascalientes'>Aguascalientes</option>";
             echo "<option value='Baja California'>Baja California</option>";
             echo "<option value='Campeche'>Campeche</option>";
             echo "<option value='Chiapas'>Chiapas</option>";
             echo "<option value='Chihuahua'>Chihuahua</option>";
             echo "<option value='Coahuila'>Coahuila</option>";
             echo "<option value='Colima'>Colima</option>";
             echo "<option value='Distrito Federal'>Distrito Federal</option>";
             echo "<option value='Durango'>Durango</option>";
             echo "<option value='Guanajuato'>Guanajuato</option>";
             echo "<option value='Guerrero'>Guerrero</option>";
             echo "<option value='Hidalgo'>Hidalgo</option>";
             echo "<option value='Jalisco'>Jalisco</option>";
             echo "<option value='Estado de Mexico'>Estado de Mexico</option>";
             echo "<option value='Michoacan'>Michoacan</option>";
             echo "<option value='Morelos'>Morelos</option>";
             echo "<option value='Nayarit'>Nayarit</option>";
             echo "<option value='Nuevo Leon'>Nuevo Leon</option>";
             echo "<option value='Oaxaca'>Oaxaca</option>";
             echo "<option value='Queretaro'>Queretaro</option>";
             echo "<option value='Quintana Roo'>Quintana Roo</option>";
             echo "<option value='San Luis Potosi'>San Luis Potosi</option>";
             echo "<option value='Sinaloa'>Sinaloa</option>";
             echo "<option value='Sonora'>Sonora</option>";
             echo "<option value='Tabasco'>Tabasco</option>";
             echo "<option value='Tlaxcala'>Tlaxcala</option>";
             echo "<option value='Veracruz'>Veracruz</option>";
             echo "<option selected value='$Edocons'>$Edocons</option>";
             echo "</select>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";
                    
             $MpioA  = mysql_query("SELECT municipio FROM estados WHERE estado = '$Edocons' GROUP BY municipio ORDER BY municipio");
             
             echo "<tr><td align='right' height='30'>$Gfont Municipio: </td><td>";
             echo "<SELECT name='Munconsultorio'>";
             while($Mpio=mysql_fetch_array($MpioA)){
             	echo "<option value='$Mpio[0]'>$Mpio[0]</option>";             
             	//echo "<option value='".utf8_encode($Mpio[0])."'>".utf8_encode($Mpio[0])."</option>";             
             }
             echo "<option selected value='$Munconsultorio'>$Munconsultorio</option>";
             echo "</select>";
             echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";
                          
             $ColA  = mysql_query("SELECT colonia,codigo FROM estados WHERE municipio = '$Munconsultorio' ORDER BY colonia");
             
             echo "<tr><td align='right' height='30'>$Gfont Colonia: </td><td>";
             echo "<SELECT name='Locconsultorio'>";
             while($Col=mysql_fetch_array($ColA)){
             	echo "<option value='$Col[0]'>$Col[0]</option>";  
             	//if($Col[0]==$Locconsultorio){$Codigosis=$Col[codigo];}           
             }
             echo "<option selected value='$Locconsultorio'>$Locconsultorio</option>";
             echo "</select>";
             //echo " &nbsp; <input type='submit' name='Botonedo' value='Enviar'>";
             echo "</td></tr>";

				 /*
				 echo "<tr><td align='right' height='30'>$Gfont Codigo postal: &nbsp; </td><td>";
				 echo "<input type='text' name='Codigo' value='$Codigo' size='5'>$Gfont ";				 
				 echo "Segun sistema:&nbsp;";
				 echo "<input type='text' name='Codigosis' value='$Codigosis' size='5'> &nbsp; ";
				 echo "</td></tr>";                              
             */
             
          cInput('Direccion:','text','30','Dirconsultorio','right',$Cpo[dirconsultorio],'40',false,false,'');
          //cInput('Colonia:','text','20','Locconsultorio','right',$Cpo[locconsultorio],'20',false,false,'');
          //cInput('Municipio:','text','20','Munconsultorio','right',$Cpo[munconsultorio],'20',false,false,'');
          cInput('Telefono:','text','20','Telconsultorio','right',$Cpo[telconsultorio],'20',false,false,'');
          cInput('Tel.celular:','text','15','Telcelular','right',$Cpo[telcelular],'15',false,false,'');
          cInput('Mail:','text','50','Mail','right',$Cpo[mail],'50',false,false,'');
                  
          echo "<tr><td align='right'>$Gfont Envio de resultados por Mail: </td><td>$Gfont ";
          echo "<select name='Enviomail'>";
          echo "<option value='Si'>Si</option>";
          echo "<option value='No'>No</option>";
          echo "<option selected>$Cpo[enviomail]</option>";
          echo "</select>";
          echo "</td></tr>";

          cInput('Tel.Institucion:','text','15','Telinstitucion','right',$Cpo[telinstitucion],'15',false,false,'');
          cInput('Dias de consulta:','text','25','Diasconsulta','right',$Cpo[diasconsulta],'25',false,false,'');
          cInput('Hras.de cunsulta:','text','20','Hraconsulta','right',$Cpo[hraconsulta],'20',false,false,'');
          cInput('Hras.de visita:','text','25','Hravisita','right',$Cpo[hravisita],'25',false,false,'');

          cInput('% Comision:','text','2','Comision','right',$Cpo[comision],'2',false,false,'');

       	 echo "<tr><td align='right'>$Gfont Status: &nbsp; </td><td>$Gfont ";
          echo "<select name='Status'>";
          echo "<option value='Activo'>Activo</option>";
          echo "<option value='Inactivo'>Inactivo</option>";
          echo "<option value='Defuncion'>Defuncion</option>";
          echo "<option value='Baja'>Baja</option>";
          echo "<option value='Otro'>Otro</option>";
          echo "<option selected>$Cpo[status]</option>";
          echo "</select>";
          echo "</td></tr>";

       	  echo "<tr><td align='right'>$Gfont Clasificacion: &nbsp; </td><td>$Gfont ";
          echo "<select name='Clasificacion'>";
          echo "<option value='A'>A</option>";
          echo "<option value='B'>B</option>";
          echo "<option value='C'>C</option>";
          echo "<option value='D'>D</option>";
          echo "<option selected>$Cpo[clasificacion]</option>";
          echo "</select>";
          echo "</td></tr>";

       	 echo "<tr><td align='right'>$Gfont Promotor Asig: &nbsp; </td><td>$Gfont ";
          echo "<select name='Promotorasig'>";
          echo "<option value='Promotor_A'>Promotor_A</option>";
          echo "<option value='Promotor_B'>Promotor_B</option>";
          echo "<option value='Promotor_C'>Promotor_C</option>"; 
          echo "<option value='Promotor_D'>Promotor_D</option>";
          echo "<option value='Promotor_E'>Promotor_E</option>";
          echo "<option value='Promotor_F'>Promotor_F</option>";
          echo "<option value='Base'>Base</option>";
          echo "<option selected>$Cpo[promotorasig]</option>";
          echo "</select>";
          echo "</td></tr>";

          //cInput('Fecha revision:','text','8','Fecharev','right',$Cpo[fecharevi],'8',false,false,'');

       	 echo "<tr><td align='right'>$Gfont <b> Zona:</b> &nbsp; </td><td>$Gfont ";
  			 $ZnaA=mysql_query("SELECT zona,descripcion FROM zns order by zona");
          echo "<select name='Zona'>";
          while($Zna=mysql_fetch_array($ZnaA)){
                echo "<option value=$Zna[zona]> $Zna[zona]&nbsp;$Zna[descripcion]</option>";
                if($Zna[zona]==$Cpo[zona]){$DesZna=$Zna[descripcion];}
          }
          echo "<option selected>$Cpo[zona]&nbsp;$DesZna</option>";
          echo "</select>";
          echo "</td></tr>";

       	 echo "<tr><td align='right'>$Gfont <b>Institucion:</b> &nbsp; </td><td>$Gfont ";
  			 $cIns=mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
          echo "<select name='Institucion'>";
             while ($Ins=mysql_fetch_array($cIns)){
                   echo "<option value=$Ins[institucion]> $Ins[institucion]&nbsp;$Ins[alias]</option>";
                   if($Ins[institucion]==$Cpo[institucion]){$DesIns=$Ins[alias];}
             }
          echo "<option selected>$Cpo[institucion]&nbsp;$DesIns</option>";
          echo "</select>";
          echo "</td></tr>";

       	 echo "<tr><td align='right'>$Gfont <b>Institucion de pago:</b> &nbsp; </td><td>$Gfont ";
  			 $cIns=mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
          echo "<select name='Institucionp'>";
          while ($Ins=mysql_fetch_array($cIns)){
                   echo "<option value=$Ins[institucion]> $Ins[institucion]&nbsp;$Ins[alias]</option>";
                   if($Ins[institucion]==$Cpo[institucion]){$DesIns=$Ins[alias];}
          }
          echo "<option selected>$Cpo[institucionp]&nbsp;$DesIns</option>";
          echo "</select>";
          echo "</td></tr>";

       	 echo "<tr><td align='right'>$Gfont <b>Ruta medica:</b> &nbsp; </td><td>$Gfont ";
  			 $RtaA=mysql_query("SELECT id,descripcion FROM ruta ORDER BY id");
          echo "<select name='Ruta'>";
             while ($Rta=mysql_fetch_array($RtaA)){
                   echo "<option value=$Rta[id]> $Rta[descripcion]</option>";
                   if($Rta[id]==$Cpo[ruta]){$Des1=$Rta[descripcion];}
             }
          echo "<option selected value='$Cpo[ruta]'>$Des1</option>";
          echo "</select>";
          echo "</td></tr>";

          cInput('Nivel de participacion:','text','3','X','right',$Cpo[participacion],'3',false,true,'');

          cTableCie();

		                                
          echo "<p> Referencia de la ubicacion del consultorio: <br>";
          echo "<TEXTAREA NAME='Refubicacion' cols='60' rows='4' >$Cpo[refubicacion]</TEXTAREA>";
          echo "</p>";

          echo "<p>Caracteristicas del Servicio<br>";
          echo "<TEXTAREA NAME='Servicio' cols='60' rows='4' >$Cpo[servicio]</TEXTAREA>";
          echo "</p>";
          
          echo "<p>Observaciones <br>";
          echo "<TEXTAREA NAME='Observaciones' cols='60' rows='4' >$Cpo[observaciones]</TEXTAREA>";
			 echo "</p>";

          echo Botones4();

          echo "<table width='100%' border='0' align='center'>";

          echo "<tr><td align='center'>$Gfont <b>Usr.alta: </b> $Cpo[usr] &nbsp;&nbsp; <b>Fecha Alta:</b> $Cpo[fecha] &nbsp;&nbsp;&nbsp; <b>Usr.ult.mod:</b> $Cpo[usrmod] &nbsp;&nbsp; <b>Fecha Modif.:</b> $Cpo[fecmod]</td></tr><tr><td></td></tr><tr><td align='center'><a href=javascript:wingral('logmodmed.php?busca=$busca')><font size='1'> *** Modificaciones ***</a>  </font></td></tr>";

          echo "$Gfont <tr><td></td></tr><tr><td align='center'><a href=javascript:wingral('medicopdf.php?busca=$busca')> <img src='images/print.gif' alt='pdf' border='0'></a></td></tr>";
          echo "</table>";

          mysql_close();
			 
          //echo "<input type='IMAGE' name='Imprimir' src='lib/print.png' onClick='print()'>";

          
      echo "</form>";
      
  echo "</td>";
  
  echo "<td width='20%' valign='top' align='left'>$Gfont";
      $FecMes = "m".substr($Fecha,5,2);
      echo "<p>&nbsp;</p><div>Demanda de clientes</div>";
		echo "<table width='80%' border='0'>";
      for($i = 1; $i < 12; $i=$i+1){
          $Mes = "m".cZeros($i,2);
          if($FecMes==$Mes){
		      echo "<tr bgcolor='#aad9aa'><td align='right'>$Gfont $aMes[$i] </td><td align='right'>$Gfont $Cpo[$Mes]</td>";
		    }else{
		      echo "<tr><td align='right'>$Gfont $aMes[$i] </td><td align='right'>$Gfont $Cpo[$Mes]</td>";
		    }
		    echo "<td width='30%'>&nbsp;</td></tr>";
      }
  
      echo "</table>";
      
      echo "<p>&nbsp;</p>";
      
      echo "<a href=javascript:wingral('visitas.php?busca=$busca')>Historial/visitas</a></td>";

  echo "</td>";

  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

?>
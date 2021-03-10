<?php

  session_start();

  #Variables necesarias para el regreso;
  $pagina     = $_REQUEST[pagina];
  $orden      = $_REQUEST[orden];
  $Sort       = $_REQUEST[Sort];
  $busca      = $_REQUEST[busca];
  $Fecha      = date("Y-m-d");
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  $Tabla      = "vinst";
  $Titulo     = "Registro de visitas Inst";

  $Nombre     = trim($_REQUEST[Nombre]);

  require("lib/lib.php");

  $link       = conectarse();
  
  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo     $Sp=" ";

     if($busca=='NUEVO'){ 
     
          $lUp = mysql_query("
          INSERT into $Tabla 
          (periodo,fecha,inst,nota,movto,promotorasig,otro)
          VALUES 
          ('$_REQUEST[Periodo]','$Fecha','$_REQUEST[Inst]','$_REQUEST[Nota]','$_REQUEST[Movto]',
		  '$_REQUEST[Promotorasig]','$_REQUEST[Otro]')"
          );
          
 	  }else{
		if ($check["level"] != 9){
 	  
			 $lUp=mysql_query("UPDATE $Tabla SET 
			 periodo='$_REQUEST[Periodo]',inst='$_REQUEST[Inst]',
			 nota='$_REQUEST[Nota]',movto='$_REQUEST[Movto]',promotorasig='$_REQUEST[Promotorasig]',otro='$_REQUEST[Otro]' 
			 WHERE idpgs='$busca' limit 1");
		}else{
			 $lUp=mysql_query("UPDATE $Tabla SET 
			 periodo='$_REQUEST[Periodo]',fecha='$_REQUEST[Fecha]',
			 inst='$_REQUEST[Inst]',
			 nota='$_REQUEST[Nota]',movto='$_REQUEST[Movto]',promotorasig='$_REQUEST[Promotorasig]',otro='$_REQUEST[Otro]' 
			 WHERE idpgs='$busca' limit 1");
		}

 	  }

     header("Location: visitainst.php");
	 
  }elseif($_REQUEST[Boton] == Cancelar){
	  
     header("Location: visitainst.php");
	 
  }
  
  $CpoA  = mysql_query("SELECT vinst.id,vinst.fecha,vinst.inst,vinst.nota,vinst.periodo,inst.nombre,
  		   vinst.movto,vinst.promotorasig,vinst.otro
		   FROM vinst
  		   LEFT JOIN inst ON vinst.inst=inst.institucion	
           WHERE id='$busca'");
 
  $Cpo   = mysql_fetch_array($CpoA);
  

  require ("confignew.php");

echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

headymenu($Titulo,0);

echo "</head>";

?>

<script language="JavaScript1.2">
function Completo(){
var lRt;
lRt=true;
if(document.form1.Inst.value==""){lRt=false;}
if(document.form1.Movto.value==""){lRt=false;}
if(document.form1.Periodo.value==""){lRt=false;}
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

   echo "<a href='visitainst.php'><img src='lib/regresa.jpg' border='0'></a>";

   echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";

echo "</td>";

echo "<td align='center'>$Gfont ";

   echo "<form name='form1' method='get' action='visitainste.php' onSubmit='return Completo();' >";
          
          echo "<font color='#003399' size='+1'><div align='left'><b> $Cpo[nombre]</b></div></font><br>";
		  
		  if($busca == 'NUEVO'){
					 $Fecha      = date("Y-m-d");
					 $Inst  = $_REQUEST[Inst];
					 $MpoA  = mysql_query("SELECT * FROM inst WHERE institucion='$_REQUEST[Inst]'");
					 
					 $Mpo   = mysql_fetch_array($MpoA);
 					 $nombrem = $Mpo[nombre];
 					 $Promotorasig = $Mpo[promotorasig];
					 
		  }else{
					 $Fecha  = $Cpo[fecha];
					 $Inst  = $Cpo[inst];
					 $nombrem = $Cpo[nombre];
					 $Otro = $Cpo[otro];
  					 $Promotorasig = $Cpo[promotorasig];
		  }
		  
          cTable('70%','0'); 
                   
          cInput('Id:','text','8','Id','right',$Cpo[id],'8',true,true,''); 
 		  if ($check["level"] != 9){         
          	 cInput('Fecha:','text','10','Fecha','right',$Fecha,'10',true,true,'');
		  }else{
		  	 cInput('Fecha:','text','10','Fecha','right',$Fecha,'10',true,false,'');
		  }
      	  cInput('Institucion:','text','15','Inst','right',$Inst,'15',true,false,"
		  <a href='fmtinst1.php?orden=inst.institucion&Sort=Asc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Fech2=&Fech3='><img src='lib/lupa_o.gif' border='0'> </a>");
          cInput('Nombre:','text','40','Nombre','right',$nombrem,'40',true,true,'');
       	  echo "<tr><td align='right'>$Gfont <b> Periodo:</b> &nbsp; </td><td> ";
		  $PerA=mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
          echo "<select name='Periodo'>";
          while($Per=mysql_fetch_array($PerA)){
                echo "<option value=$Per[mes]>$Per[mes]</option>";
          }
          echo "<option selected value='$Cpo[periodo]'>$Cpo[periodo]</option>";
          echo "</select>";
          echo "</td></tr>";

       	  echo "<tr><td align='right'>$Gfont <b> Movto:</b> &nbsp; </td><td> ";
          echo "<select name='Movto'>";
          echo "<option value='Visita'>Visita</option>";
          echo "<option value='Pago'>Pago Comision</option>";
          echo "<option value='Garantia'>Garantia Servicio</option>";
          echo "<option value='Visitaesp'>Visita Especifica</option>";
          echo "<option selected value='$Cpo[movto]'>$Cpo[movto]</option>";
          echo "</select>";
          echo "</td></tr>";

       	  echo "<tr><td align='right' valign='top'>$Gfont <b>Observaciones de la visita </b> &nbsp; </td><td>";
          echo "<TEXTAREA NAME='Nota' cols='50' rows='4' >$Cpo[nota]</TEXTAREA>";
          echo "</td></tr>";

       	  echo "<tr><td align='right'>$Gfont <b>Visitado por:</b> &nbsp; </td><td> ";
          echo "<select name='Promotorasig'>";
          echo "<option value='Promotor_A'>Promotor_A</option>";
          echo "<option value='Promotor_B'>Promotor_B</option>";
          echo "<option value='Promotor_C'>Promotor_C</option>"; 
          echo "<option value='Promotor_D'>Promotor_D</option>";
          echo "<option value='Promotor_E'>Promotor_E</option>";
          echo "<option value='Promotor_F'>Promotor_F</option>";
          echo "<option value='Otro'>Otro</option>";
          echo "<option selected>$Promotorasig</option>";
          echo "</select>";
          echo "</td></tr>";
		  
          cInput('Otro:','text','35','Otro','right',$Otro,'35',true,false,'');

          cTableCie();
			 
          echo Botones();

          mysql_close();
			           
      echo "</form>";
      
  echo "</td>";

  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

?>
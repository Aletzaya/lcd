<?php

  session_start();

  #Variables necesarias para el regreso;
  $pagina     = $_REQUEST[pagina];
  $orden      = $_REQUEST[orden];
  $Sort       = $_REQUEST[Sort];
  $busca      = $_REQUEST[busca];

  $Tabla      = "cmc";
  $Titulo     = "Registro de movimientos";

  $Nombre     = trim($_REQUEST[Nombre]);
  $Fecha      = date("Y-m-d");

  require("lib/lib.php");

  $link       = conectarse();
  
  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo     $Sp=" ";

     if($busca=='NUEVO'){
          $lUp = mysql_query("
          INSERT into $Tabla 
          (medico,inst,cliente,mes,orden,fecha,concepto,importe,comision,numestudios,tm)
          VALUES 
          ('$_REQUEST[Medico]','$_REQUEST[Inst]','$_REQUEST[Cliente]','$_REQUEST[Mes]',
          '$_REQUEST[Orden]','$_REQUEST[Fecha]','$_REQUEST[Concepto]','$_REQUEST[Importe]',
          '$_REQUEST[Comision]','$_REQUEST[Numestudios]','M')"); //tm es tipo de movimiento Manual/Automatico;

 	  }else{
         $lUp=mysql_query("UPDATE $Tabla SET 
         medico='$_REQUEST[Medico]',
         inst='$_REQUEST[Inst]',cliente='$_REQUEST[Cliente]',mes='$_REQUEST[Mes]',
         orden='$_REQUEST[Orden]',fecha='$_REQUEST[Fecha]',concepto='$_REQUEST[Concepto]',
         importe='$_REQUEST[Importe]',comision='$_REQUEST[Comision]',numestudios='$_REQUEST[Numestudios]',
         tm='$_REQUEST[Importe]' 
         WHERE id='$busca' limit 1");
 	  }

     header("Location: movcmc.php");
     
  }
  
  $CpoA  = mysql_query("SELECT cmc.medico,cmc.inst,cmc.cliente,cmc.mes,cmc.orden,cmc.fecha,cmc.concepto,
  			  cmc.importe,cmc.comision,cmc.numestudios,med.nombrec
  			  FROM cmc
  			  LEFT JOIN med ON cmc.medico=med.medico	
           WHERE id='$busca'");
 
  $Cpo   = mysql_fetch_array($CpoA);
  
  $lAg   = $busca=='NUEVO';

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

   echo "<a href='movcmc.php'><img src='lib/regresa.jpg' border='0'></a>";

   echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";

echo "</td>";

echo "<td align='center'>$Gfont ";

   echo "<form name='form1' method='get' action='movcmce.php' onSubmit='return Completo();' >";
          
          if($lAg){
             $FechaA  = $Fecha;
             $Medico  = $_REQUEST[Medico];
          }else{
          	 $FechaA  = $Cpo[fecha];
          	 $Medico  = $Cpo[medico];
          }   
          
          echo "<font color='#003399' size='+1'><div align='left'><b> $Cpo[nombrec]</b></div></font><br>";
                              
          cTable('70%','0'); 
                   
          cInput('Id:','text','8','Id','right',$Cpo[id],'8',true,true,'');          

          cInput('Medico:','text','5','Medico','right',$Medico,'15',true,false,"<a href='fmtmed2.php'><img src='lib/lupa_o.gif' border='0'></a>");
       	 echo "<tr><td align='right'>$Gfont <b>Institucion:</b> &nbsp; </td><td>";
  			 $cIns=mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
          echo "<select name='Institucion'>";
             while ($Ins=mysql_fetch_array($cIns)){
                   echo "<option value=$Ins[institucion]> $Ins[institucion]&nbsp;$Ins[alias]</option>";
                   if($Ins[institucion]==$Cpo[inst]){$DesIns=$Ins[alias];}
             }
          echo "<option selected>$Cpo[inst]&nbsp;$DesIns</option>";
          echo "</select>";
          echo "</td></tr>";

          cInput('Cliente:','text','10','Cliente','right',$Cpo[cliente],'10',true,false,' 100 cliente varios ');

       	 echo "<tr><td align='right'>$Gfont <b> Periodo:</b> &nbsp; </td><td> ";
  			 $PerA=mysql_query("SELECT mes FROM cmc GROUP BY mes ORDER BY mes");
          echo "<select name='Mes'>";
          while($Per=mysql_fetch_array($PerA)){
                echo "<option value=$Per[mes]>$Per[mes]</option>";
          }
          echo "<option selected value='$Cpo[mes]'>$Cpo[mes]</option>";
          echo "</select>";
          echo "</td></tr>";

          cInput('Orden:','text','10','Orden','right',$Cpo[orden],'10',true,false,' S/D en caso de no tenerlo');
          cInput('Concepto:','text','40','Concepto','right',$Cpo[concepto],'40',true,false,'');          
          cInput('Importe:','text','10','Importe','right',$Cpo[importe],'10',false,false,'');
          cInput('Comision:','text','10','Comision','right',$Cpo[comision],'10',false,false,'');
          cInput('Numero de estudios:','text','10','Numestudios','right',$Cpo[numestudios],'10',false,false,'');

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
<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $orden=$_REQUEST[orden];

  $Suc=$_REQUEST[Suc];

  $Hora = date('G:i');

  $Fecha = date("Y-m-d");

  $Fecha2 = date("Y-m-d H:i");

  if($Suc==''){$Suc='*';}else{$Suc=$_REQUEST[Suc];}

  $Tabla="ot";

  $Titulo="Encabezado de la orden de trabajo [$busca]";

  $link=conectarse();

  $lBd=false;

  $Usr       = $check['uname'];

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	 $PrmA  = mysql_query("SELECT password FROM cia WHERE id='1'");
	 $Prm   = mysql_fetch_array($PrmA);
	 
	 $Clave = md5($_REQUEST[Password]);
	 	 
	  if($Prm[0] == $Clave or $Usr=='nazario' or $Usr=='ivett' or $Usr=='Javier'){          

       $MedA=mysql_query("select nombrec from med where medico='$_REQUEST[Medico]'");

        if($Med = mysql_fetch_array($MedA)){
          
          $lUp = mysql_query("UPDATE ot SET medico='$_REQUEST[Medico]',medicon='$_REQUEST[Medicon]',
                   receta='$_REQUEST[Receta]',diagmedico='$_REQUEST[Diagmedico]',
                   observaciones='$_REQUEST[Observaciones]',fecha='$_REQUEST[Fecha]',institucion='$_REQUEST[Institucion]' 
                   WHERE orden='$busca'");
            $lBd = true;

            $OtB=mysql_query("select medico,medicon,suc from ot where orden='$busca' ",$link);
            $Otb=mysql_fetch_array($OtB);

          $lUp3  = mysql_query("INSERT INTO logcambios (fecha,usr,orden,concepto,suc) VALUES
          ('$Fecha2','$Usr','$busca','Cambio de datos de la orden','$Otb[suc]')");

        }else{

            echo '<script language="javascript">alert("Favor de Checar, por que el medico que asignaste no Existe!");</script>';  

          //  $Msj="Favor de Checar, por que el medico que asignaste no Existe!";
        }
         
    }else{ 

          $CpoB = mysql_query("SELECT * FROM cambios WHERE id='1'");
          $Cpob = mysql_fetch_array($CpoB);

          if($Cpob[activo]<>'0' and $Fecha>=$Cpob[fechai] and $Fecha<=$Cpob[fechaf]){

            $OtB=mysql_query("select medico,medicon,suc from ot where orden='$busca' ",$link);
            $Otb=mysql_fetch_array($OtB);

            $lUp3  = mysql_query("INSERT INTO logcambios (fecha,usr,orden,concepto,suc) VALUES
            ('$Fecha2','$Usr','$busca','Cambio de datos de medico: Anterior $Otb[medico] - $Otb[medicon] Actual $_REQUEST[Medico] - $_REQUEST[Medicon]','$Otb[suc]')");

            $lUp = mysql_query("UPDATE ot SET medico='$_REQUEST[Medico]',medicon='$_REQUEST[Medicon]'
                   WHERE orden='$busca'");

            $lBd = true;

          }else{

            echo '<script language="javascript">alert("No es posible realizar ningun cambio");</script>';  
           
          }


    }

  }elseif($_REQUEST[Boton] == Cancelar){

           header("Location: ordenescon.php?pagina=$_REQUEST[pagina]");

  }elseif($busca=='NUEVO'){

      header("Location: ordenesnvas.php?busca=NUEVO&Vta=1");

  }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      header("Location: ordenescon.php?pagina=$_REQUEST[pagina]&Suc=$Suc");
  }

  $OtA=mysql_query("select * from ot where orden='$busca' ",$link);
  $Ot=mysql_fetch_array($OtA);
  $CliA=mysql_query("select nombrec from cli where cliente=$Ot[cliente]",$link);
  $Cli=mysql_fetch_array($CliA);
  $MedA=mysql_query("select nombrec from med where medico='$Ot[medico]'",$link);
  $Med=mysql_fetch_array($MedA);
  $InsA=mysql_query("select nombre from inst where institucion=$Ot[institucion]",$link);
  $Ins=mysql_fetch_array($InsA);
  $cSql="select * from $Tabla where (orden= '$busca')";
  $lAg=$Nombre<>$Cpo[nombre];
  $Fecha=date("Y-m-d");

  require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

headymenu($Titulo,0); 

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
         echo "<a class='pg' href='ordenescon.php?Suc=$Suc'><img src='lib/regresa.jpg' border='0'></a>";
      ?>
   </td>
   <td align='center'>
   <form name="form1" method="post" action="ordenese.php">
     <?php
       echo $Gfont;
       echo "<p align='center'><strong>Orden de Trabajo Nùmero : $busca</strong></p>";
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
	   
	   if($Ot[medico]=='MD'){

		   cInput("Clave Medico: ","Text","10","Medico","right",$Ot[medico],"10",false,false,$Ot[medicon]);
	   
	   }else{
		   
		   cInput("Clave Medico: ","Text","10","Medico","right",$Ot[medico],"10",false,false,$Med[nombrec]);

	   }
       cInput("Medico MD: ","Text","40","Medicon","right",$Ot[medicon],"40",false,false,'');

       cInput("Institucion: ","Text","10","Institucion","right",$Ot[institucion],"10",false,false,'');

		 echo "<tr><td align='right' valign='bottom'>$Gfont Diagnostico medico:&nbsp;</td><td>";
       echo "<TEXTAREA NAME='Observaciones' cols='45' rows='2'>$Ot[diagmedico]</TEXTAREA>";
       echo "</td></tr>";   
		 echo "<tr><td align='right' valign='bottom'>$Gfont Observaciones:&nbsp;</td><td>";
       echo "<TEXTAREA NAME='Observaciones' cols='45' rows='2' readonly>$Ot[observaciones]</TEXTAREA>";
       echo "</td></tr>";   
       
       cInput('<b>Password :</b>','password','20','Password','right','','40',true,false,'es necesario para registrar el cambio');

       cTableCie();
//		echo "<input type='hidden' name='pagina' value='$pagina'>";
       echo Botones2();
      
  echo "</form>";
  
  echo "</td>";
  
  echo "<td width='15%'>&nbsp;</td>";
  
  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

mysql_close();

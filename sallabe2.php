<?php

  session_start();

  require("lib/lib.php");

  $link  = conectarse();

  $busca = $_REQUEST[busca];
  $Tabla = "sl";
  $Msj   = "";

  $Titulo= "Detalle de salidas";

  $lAg   = $busca=='NUEVO';

  $cSql  = "SELECT * FROM sl WHERE id='$busca'";

  $CpoA  = mysql_query($cSql);

  $Cpo   = mysql_fetch_array($CpoA);

  $lBd   = false;

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  if($busca=="NUEVO"){         
         $Fecha = date("Y-m-d");
         $Hora  = date("H:i");        
         $lUp   = mysql_query("INSERT INTO sl (fecha,hora,concepto,recibio,status) 
                  VALUES
                  ('$_REQUEST[Fecha]','$Hora','$_REQUEST[Concepto]','$_REQUEST[Recibio]','ABIERTA')");
                 
         $Id    = mysql_insert_id();
                 
         $lBd   = true;
      }else{
      
         $lUp = mysql_query("UPDATE sl SET concepto='$_REQUEST[Concepto]',recibio='$_REQUEST[Recibio]',
                status='$_REQUEST[Status]',fecha='$_REQUEST[Fecha]'
                WHERE id='$busca' limit 1");
                      
        $lBd   = true;

      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: sallabe.php");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      if($busca=='NUEVO'){
         header("Location: sallabd.php?busca=$Id");
      }else{
         header("Location: sallab.php");      
      }   
  }


  require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF"  onload="cFocus()">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">

function Mayusculas(cCampo){
if (cCampo=='Nombre'){documsal.form1.Nombre.value=documsal.form1.Nombre.value.toUpperCase();}
}

function cFocus(){

  documsal.form1.Nombre.focus();

}
</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='10%' align='csaler'>";

        echo "<a href='sallab.php'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td><td align='center'>";

       echo "<form name='form1' method='get' action='sallabe.php' onSubmit='return ValCampos();'>";
       
        		 if(!$lAg){
        		 
        		    $Fecha  = $Cpo[fecha];
        		    $Hora   = $Cpo[hora]; 
        		 
        		 }else{
        		 
					 $Fecha  = date("Y-m-d");    		 
					 $Hora   = date("H:i");    		 
        		 
        		 }

				 echo "<p>&nbsp;</p>";
				 
             cTable('80%','0');

             cInput("No.Salida: ","Text","4","Id","right",$busca,"4",false,true,'');

             cInput("Fecha:","Text","9","Fecha","right",$Fecha,"10",false,false,'');

             cInput("Hora:","Text","5","Hora","right",$Hora,"5",false,true,'');

             cInput("","Text","","","","","",false,true,'');

             echo "<tr><td align='right'>$Gfont Concepto: &nbsp; ";
             echo "</td><td>";
             echo "<select name='Concepto'>";
             echo "<option value='Ajuste'>Ajuste</option>";
             echo "<option value='Resurtido'>Resurtido</option>";
             echo "<option selected value=$Cpo[concepto]>$Cpo[concepto]</option>";
             echo "</td></tr>";

             cInput("Recibio: ","Text","30","Recibio","right",$Cpo[recibio],"30",false,false,'');

             cInput("Cantidad: ","Text","12","Iva","right",$Cpo[cantidad],"10",false,true,'');

             cInput("Importe $: ","Text","12","Importe","right",$Cpo[importe],"12",false,true,'');
					
				 /*	
             echo "<tr><td align='right'>$Gfont Condiciones: ";
             echo "</td><td>";
             echo "<select name='Condiciones'>";
             echo "<option value='CREDITO'>CREDITO</option>";
             echo "<option value='CONTADO'>CONTADO</option>";
             echo "<option selected value=$Cpo[condiciones]>$Cpo[condiciones]</option>";
             echo "</td></tr>";

             cInput("Dias de credito: ","Text","5","Dias","right",$Cpo[dias],"5",false,false,'');
				 */
				 
 				 Stats($Cpo[status]);				 
				   				 
             cTableCie();

				 if($Cpo[status]<>'CERRADA'){
                echo Botones();
             }   


             mysql_close();

            echo "</font>";

      echo "</form>";

      echo "</td><td width='10%'>&nbsp;</td>";

  echo "</tr>";

echo "</table>";

CierraWin();

echo "</body>";

echo "</html>";

?>
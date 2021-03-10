<?php

  session_start();

  require("lib/lib.php");
  
  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $link  = conectarse();

  $busca = $_REQUEST[busca];
  $Tabla = "el";
  $Msj   = "";

  $Titulo= "Detalle de entradas";

  $lAg   = $busca=='NUEVO';

  $cSql  = "SELECT * FROM el WHERE id='$busca'";

  $CpoA  = mysql_query($cSql);

  $Cpo   = mysql_fetch_array($CpoA);

  $lBd   = false;
  
  $Usr    = $check['uname'];

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  if($busca=="NUEVO"){
         
         //$Fecha = date("Y-m-d");
         //$Hora  = date("H:i");
         
         $Fecha   = $_REQUEST[Fecha];
         $Hora    = $_REQUEST[Hora];        
         
         $lUp   = mysql_query("INSERT INTO el (fecha,hora,concepto,documento,status,proveedor,depto,usr,almacen) 
                  VALUES
                  ('$Fecha','$Hora','$_REQUEST[Concepto]','$_REQUEST[Documento]','ABIERTA','$_REQUEST[Proveedor]',
				  '$_REQUEST[Depto]','$Usr','$_REQUEST[Almacen]')");
                 
         $Id    = mysql_insert_id();
                 
         $lBd   = true;
      }else{
      
      	 /*
          if($Cpo[status]<>'CERRADA' and $_REQUEST[Status]=='CERRADA'){

            if(abs( ($Cpo[importea]+$Cpo[ivaa]) - ($Cpo[importe]+$Cpo[iva]) )<2){
               $lUp = mysql_query("UPDATE $Tabla SET proveedor='$_REQUEST[Proveedor]',documento='$_REQUEST[Documento]',
                      status='$_REQUEST[Status]',condiciones='$_REQUEST[Condiciones]',descuento='$_REQUEST[Descuento]'
                      WHERE id='$busca' limit 1");
               $lBd = true;
            }else{
               $Msj = "Lo siento, la suma de los productos no coincide con el dado en cantidad a pagar!";
            }

          }else{
          */
          
             $lUp   = mysql_query("UPDATE el SET documento='$_REQUEST[Documento]',concepto='$_REQUEST[Concepto]',
                      status='$_REQUEST[Status]',proveedor='$_REQUEST[Proveedor]',fecha='$_REQUEST[Fecha]',hora='$_REQUEST[Hora]',
					  depto='$_REQUEST[Depto]',usr='$Usr',almacen='$_REQUEST[Almacen]'                  
                      WHERE id='$busca' limit 1");
                      
             $lBd   = true;

      }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: entlabe.php");

   }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      if($busca=='NUEVO'){
         header("Location: entlabd.php?busca=$Id");
      }else{
         header("Location: entlab.php");      
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
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}

function cFocus(){

  document.form1.Nombre.focus();

}
</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='10%' align='center'>";

        echo "<a href='entlab.php'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td><td align='center'>";

       echo "<form name='form1' method='get' action='entlabe.php' onSubmit='return ValCampos();'>";
       
        		 if(!$lAg){
        		 
        		    $Fecha  = $Cpo[fecha];
        		    $Hora   = $Cpo[hora]; 
        		 
        		 }else{
        		 
					 $Fecha  = date("Y-m-d");    		 
					 $Hora   = date("H:i");    		 
        		 
        		 }

				 echo "<p>&nbsp;</p>";
				 
             cTable('80%','0');

             cInput("No.entrada: ","Text","4","Id","right",$busca,"4",false,true,'');

             cInput("Fecha:","Text","9","Fecha","right",$Fecha,"10",false,false,'');

             cInput("Hora:","Text","5","Hora","right",$Hora,"5",false,false,'');

             echo "<tr><td align='right'>$Gfont Proveedor: &nbsp; </td><td>";
             echo "<select name='Proveedor'>";
             $HilA=mysql_query("select id,alias from prv order by id ");
             while ($Hil=mysql_fetch_array($HilA)){
                   echo "<option value=$Hil[0]>$Hil[0] $Hil[1]</option>";
                   if($Cpo[proveedor]==$Hil[0]){$cDes=$Hil[1];}
             }
             echo "<option selected value=$Cpo[proveedor]>$cDes</option>";
             echo "</select></td></tr>";
             

             cInput("","Text","","","","","",false,true,'');

             echo "<tr><td align='right'>$Gfont Concepto: &nbsp; ";
             echo "</td><td>";
             echo "<select name='Concepto'>";
             echo "<option value='Inventario inicial'>Inventario inicial</option>";
             echo "<option value='Compras'>Compras</option>";
             echo "<option value='Devoluciones'>Devoluciones</option>";
             echo "<option value='Ajuste'>Ajuste</option>";
             echo "<option selected value=$Cpo[concepto]>$Cpo[concepto]</option>";
             echo "</td></tr>";

             cInput("No.Factura &oacute; Docto: ","Text","30","Documento","right",$Cpo[documento],"30",false,false,'');

             cInput("Cantidad: ","Text","12","Iva","right",$Cpo[cantidad],"10",false,true,'');

             cInput("Importe $: ","Text","12","Importe","right",$Cpo[importea],"12",false,true,'');
					
             echo "<tr><td align='right'>$Gfont Almacen: &nbsp; ";
             echo "</td><td>";
             echo "<select name='Almacen'>";
			 echo "<option value='invgral'>General</option>";
             echo "<option value='invmatriz'>Matriz</option>";
             echo "<option value='invtepex'>Tepexpan</option>";
             echo "<option value='invhf'>HF</option>";
             echo "<option value='invreyes'>Reyes</option>";
             echo "<option selected value=$Cpo[almacen]>$Cpo[almacen]</option>";
             echo "</td></tr>";
 
  			 cInput("","Text","","","","","",false,true,'');

             echo "<tr><td align='right'>$Gfont Departamento: &nbsp; ";
             echo "</td><td>";
             echo "<select name='Depto'>";
             echo "<option value='Mixto'>Mixto</option>";
             echo "<option value='Admvo'>Admvo</option>";
             echo "<option value='Laboratorio'>Laboratorio</option>";
             echo "<option value='Rayos X'>Rayos X</option>";
             echo "<option value='Ultrasonido'>Ultrasonido</option>";			 
             echo "<option selected value=$Cpo[depto]>$Cpo[depto]</option>";
             echo "</td></tr>";
			 						  
          echo "<tr><td align='right'>$Gfont <b>Status: </b>&nbsp; </td><td>";

          if($Cpo[status]<>'CERRADA' or $Usr=='nazario'){
                  echo "<select name='Status'>";
                  echo "<option value='ABIERTA'>ABIERTA</option>";
                  echo "<option value='CERRADA'>CERRADA</option>";
                  echo "<option selected value='$Cpo[status]'>$Cpo[status]</option>";

                  cTableCie();
                  echo Botones();
 
          }else{
              echo "<select name='Status' disabled>";
              echo "<option value='ABIERTA'>ABIERTA</option>";
              echo "<option value='CERRADA'>CERRADA</option>";
              echo "<option selected value='$Cpo[status]'>$Cpo[status]</option>";
              echo "</selected>";
              echo "</td></tr>"; 

              cTableCie();
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
<?php

//setcookie ("Ticket", "0000123");

session_start();

// Opens a connection to a MySQL server
$connection=mysql_connect ("localhost","root", "texcocolcd");

if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db("lcd", $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

require("../lib/lib.php");

$Msj = $_REQUEST[Msj];

if(isset($_REQUEST[Paso])){$_SESSION['cVarVal']=$_REQUEST[Paso];}

if(isset($_REQUEST[cOrden])){$_SESSION['cVar']=$_REQUEST[cOrden];}

if(isset($_REQUEST[cApellido])){$_SESSION['cApellido']=$_REQUEST[cApellido];}

$Paso       =  $_SESSION['cVarVal'];
$cOrden      =  $_SESSION['cVar'];
$cApellido  =  $_SESSION['cApellido'];
$Existe = false;

if($cOrden <> '' AND $Paso > 1){
    
   $CpoA  = mysql_query("SELECT ot.orden,cli.apellidom
            FROM cli,ot
            WHERE ot.orden = '$cOrden' AND ot.cliente=cli.cliente");

   $Cpo    = mysql_fetch_array($CpoA);   
   
   
   if(trim(strtoupper($Cpo[apellidom])) == trim($cApellido)){
      
      $Existe = true; 

      $Msj    = "Por el momento tenemos el sevicio en linea de estudios de laboratorio, proximamente rayos X";
      
   }else{

       $Msj = "Lo siento, la orden $cOrden NO existe"; 
       
      $_SESSION['cVarVal'] = "";    
      $_SESSION['cVar']    = "";        
      $Orden               = "";
       
   }
}

if($Paso == 99){
    
    $_SESSION['cVarVal'] = "";    
    $_SESSION['cVar'] = "";        

    
}

require ("../config.php");							//Parametros de colores;

?>

<html>
<head>
<title>Sistema Administrativo</title>

<link href='estilos_lcd.css' rel='stylesheet' type='text/css'>

<script language="JavaScript1.2">

  function cFocus(){

    document.Forma1.Ticket.focus();

  }

function Mayusculas(cCampo){

		if (cCampo=='username'){document.Sample.username.value=document.Sample.username.value.toUpperCase();}

}

function ValCampos(){
    
    //alert("La longitud es " + form1.Ticket.value.length );
    if (form1.Ticket.value.length == 0)	{
       alert("Es necesario escribir el numero de ticket que se encuentra en su recibo");
       document.form1.Ticket.focus();
       return false;
    }
    
    if (form1.Ticket.value.length < 8)	{
       alert("Lo siento tu numero de folio no existe");
       document.form1.Ticket.focus();
       return false;
    }

}

function WinRes(url){
   window.open(url,"WinRes","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=900,height=500,left=30,top=80")
}

</script>

</head>

<?php

//echo "<body background='fondo_gris.png' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onload='cFocus()'>";
echo "<body background='Fondo.jpg' leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' onload='cFocus()'>";

    if($Paso == 1){
 
        echo "<meta http-equiv='refresh' content='3;url=http:/lcd/consultas/index.php?Paso=2&Msj=$Msj'/>";

    }elseif($Paso == 99){
        
        echo "<meta http-equiv='refresh' content='3;url=http://dulab.com.mx/nov2.html'/>";
        //echo "<div align='left'> &nbsp <a class='pg' href='http://dulab.com.mx/nov2.html'>Salir</a></div>";

        
    }

     echo "<p>&nbsp;</p>";

     echo "<table width='900' height='500' border='1' cellpadding='0' cellspacing='0' align='center' class='texto_bienvenida_usuario' style='border-collapse: collapse; border: 2px solid #066;'>";

     echo "<tr bgcolor='#ffffff'>";

     echo "<td height='70' width='173'><img src='logo.jpg' border=0> &nbsp; <font size='+2'> SERVICIO DE CONSULTA EN LINEA</td></tr>";

     echo "<tr>";

     echo "<td height='380' width='100%' bgcolor='#ffffff' valign='top' align='left'><br>";

     
     
        if( $Existe ){
            
               $HeA  = mysql_query("SELECT ot.fecha,cli.nombrec,cli.direccion,cli.colonia,cli.municipio,ot.fecha,ot.hora
                        FROM ot
                        LEFT JOIN cli ON ot.cliente = cli.cliente
                        WHERE orden = '$cOrden'");
               
               $He  = mysql_fetch_array($HeA);
               
               
                 echo "<table width='700' height='60' border='1' cellpadding='0' cellspacing='0' align='center' class='textos_tablas' style='border-collapse: collapse; border: 1px solid #066;'>";
                 echo "<tr><td valign='top' class='texto_estado' bgcolor='#d1d1d1'>";
                 echo "<div>&nbsp Paciente:  $He[nombrec]</div>";
                 echo "<div>&nbsp Direccion: $He[direccion] &nbsp $He[colonia] &nbsp; $He[municipio] </div>";
                 echo "<div align='right'>&nbsp Fecha: $He[fecha] &nbsp $He[hora] &nbsp &nbsp </div>";
                 echo "</td></tr></table><br>";
                
                 echo "<table align='center' width='95%' border='1' cellpadding='1' cellspacing='0' style='border-collapse: collapse; border: 1px solid #066;'>";
                 echo "<tr bgcolor='#006666' class='titulos_tabla'>";
                 echo "<th>Estudio</th>";
                 echo "<th>Descripcion</th>";
                 echo "<th> </th>";
                 echo "<th> </th>";
                 echo "</tr>";

                 $CpoA  = mysql_query("SELECT otd.estudio, est.descripcion, otd.status
                        FROM otd
                        LEFT JOIN est ON otd.estudio = est.estudio
                        WHERE orden = '$cOrden'");
                 while($Cpo = mysql_fetch_array($CpoA)){
                     
           		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
              
                        $clnk=strtolower($Cpo[estudio]);

           		echo "<tr class='texto_tablas' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

           		echo "<td align='left'> &nbsp &nbsp $Cpo[estudio]</td>";
           		echo "<td align='left'>&nbsp &nbsp $Cpo[descripcion]</td>";

                        if($Cpo[status] == 'TERMINADA'){
                            echo "<td align='center'><img src='../lib/icontrue.gif'title='resultado disponible'></td>";                            
                            //echo "<td align='center'><a class='Seleccionar' href=javascript:WinRes('estdeptoimp.php?clnk=$clnk&Orden=$rg[1]&Estudio=$rg[estudio]&Depto=TERMINADA&op=im')Click aqui p/ver resultados</a></td>";
                            echo "<td align='center'><a class='pg' href=javascript:WinRes('estdeptoimp.php?clnk=$clnk&Estudio=$Cpo[estudio]')><img src='lib/print.png' alt='Imprime resultados' border='0'></a></td>";
                            
                        }else{
                   	    echo "<td align='center'><img src='../lib/iconfalse.gif' title='estudio en proceso'></td>";                            
                            echo "<td align='center'>Aun en proceso</td>";
                        }    
                        

           		echo "</tr>";

           		$nRng++;
                 }       
                 echo "</table>";                                                  
            
        }else{

            echo "<form name='form1' method='get' action='$resultpage' onSubmit='return ValCampos();'>";

                echo " &nbsp &nbsp &nbsp Favor de digitar su numero de folio: ";
                echo " &nbsp &nbsp <input type='text' style='background-color:#bacbc2; color:#ffffff;font-weight:bold;' name='cOrden' size='8' maxlength='16' onKeyUp='this.value = this.value.toUpperCase();' >";
                echo " &nbsp &nbsp Apellido materno: ";
                echo " <input type='text' style='background-color:#bacbc2; color:#ffffff;font-weight:bold;' name='cApellido' size='10' maxlength='16' onKeyUp='this.value = this.value.toUpperCase();' >";

                    echo " &nbsp <input type='submit' name='Boton' value='Enviar' class='texto_registros_down'>";

                    /*
                    echo "<table width='90%' border='0' align='center' class='texto_tablas'><tr>";
                    echo "<td align='left' width='50%'><a href='facturasd.php'><img src='lib/regresa.jpg' border=0></a>regresar</td><td align='center'>";
                    echo "<input type='submit' style='background:#990000; color:#ffffff;font-weight:bold;' name='Boton' value='Genera factura'>";
                    echo "</td></tr></table><br><br><br>";

                    */

                echo "<input type='hidden' name='Paso' value='1'>";

            echo "</form>";

           echo "<div align='center'><img src='doctor.jpg' border=0></div>";
     
        }
     
        //echo "<p align='center'>Pagina en estado de pruebas, en breve estara activa </p>";

    echo "</td></tr>";

	echo "<tr><td align='right' bgcolor='#ffffff'>";
        
        echo "<div align='left'> <img src='../lib/regresa.jpg'><a class='pg' href='?Paso=99'>Salir</a></div>";
        
        if($Paso == 1 ){
                    
            echo "<div><img src='../lib/working.gif'> &nbsp &nbsp Favor de esperar... buscando estudio &nbsp </div>";
            
        }elseif($Paso == 99){
                    
            echo "<div><img src='../lib/working.gif'> &nbsp &nbsp Favor de esperar... cerrando session &nbsp </div>";

        }else{    
        
            echo "<div> $Msj &nbsp </div>"; 
        
        }
         
        
        echo "</td></tr>";

    echo "</table>";

    echo"</td></tr></table>";

echo "</body>";

echo "</html>";

?>

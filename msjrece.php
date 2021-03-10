<?php 
    
  session_start();
  
  require("lib/lib.php"); 
        
  $link=conectarse(); 

  $Tda     = $_SESSION['Tda']; 
 
  #Variables necesarias para el regreso;
  $busca = $_REQUEST[busca];
  $Id 	= $_REQUEST[Id];    
  $St 	= $_REQUEST[St];
  $Usr   = $_COOKIE['USERNAME'];
      
  #Variables que cambian;
  $Tabla  = "msj";		//Clientes telas;
  $Titulo = "Edicion de mensajes"; 
  $Return = "msjrec.php";
 
  //$Usr      = $_COOKIE['USERNAME'];  
  //if($_REQUEST[Boton] == Aceptar AND $_SESSION['nivel'] >= 8){        //Para agregar uno nuevo
  if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo
  
      //$cCre=cZeros($_REQUEST[Credencial],5);

      if($busca=='NUEVO'){ 

          $Hora  = date("H:i");
     
          $Fecha = date("Y-m-d");

          $lUp   = mysql_query("INSERT INTO msj 
                   (para,de,fecha,hora,titulo,nota,tipo) 
                   VALUES
                   ('$_REQUEST[Para]','$Tda','$Fecha','$Hora','$_REQUEST[Titulo]','$_REQUEST[Nota]','2')
                   "); 
                 
                   //$id=mysql_insert_id();
 
          $Msj = "Mensaje agregador con exito";
          
          $op  = 2;

        
 	  }else{
 
        $lUp = mysql_query("UPDATE msj SET nota='$_REQUEST[Nota]',titulo='$_REQUEST[Titulo]'
               WHERE id='$busca' limit 1");
               $Msj = "Registro actualizado";
     }

     header("Location: $Return?Msj=$Msj&busca=$op");
 
 }elseif($_REQUEST[Boton] == Cancelar){

    header("Location: $Return");

 }
 
 $lAg   = $busca=='NUEVO';

 $Fecha = date("Y-m-d");

 require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

<script type='text/javascript' src='ckeditor/ckeditor.js'>
</script>


</head>

<body onLoad="cFocus()">

<?php 

    //headymenu($Titulo,1); 

  echo "<table width='98%' border='0' align='center' cellpadding=0 cellspacing=0>";
  echo "<tr><td width='10%'><a href='javascript:window.close()'><img src='lib/regresa.jpg' border='0'></a></td>";
  echo "<td align='center'>$Gfont <b>Bienvenidos area de mensajes en linea</b> </font></td></table>";
 
echo "<div align='right'><font color='#CCCCFF' size='1'>Hagamos de esto una buena herramienta &nbsp; </font></div>";

echo "<table width='98%' border='0' align='center' cellpadding=0 cellspacing=0><tr>";
echo "<td width='50%' align='left'>$Gfont<b><font color=$GbarraSup>USUARIO:</font> $Usr </b></td>";
echo "<td width='50%' align='right'><img src='lib/mensajes.gif' border='0'> &nbsp; </td>";
echo "</tr></table>";

echo "$Gfont <b>Menu principal</b><br>";

echo "<table width='98%' border='1' align='center' cellpadding=0 cellspacing=0><tr>";
echo "<td width='33%' align='center' bgcolor=$Gbarra color='#ffffff'>$Gfont <b>Mensajes recibidos</b></td>";
echo "<td width='33%' align='center'>$Gfont <a class='pg' href='msjenv.php'><b>Mensajes enviados</b></a></td>";
echo "<td align='center'>$Gfont <a class='pg' href='msjenve.php?busca=NUEVO'><b>Nuevo mensaje</b></a></td>";
echo "</tr></table>";

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td align='left' width='50'><a href='$Return'><img src='lib/regresar.gif' border='0'></a></td> ";

    echo "<td align='left'>$Gfont <br> ";

       echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";
		 
		    if($St==0){	//Osea k es un mensaje recibido y aun no lo han abirto;
 	          mysql_query("UPDATE msj SET bd = 1 WHERE id='$Id' LIMIT 1");
          }

          $CpoA  = mysql_query("SELECT * FROM msj WHERE id = '$Id'");

          $Cpo   = mysql_fetch_array($CpoA);
          
  			 echo "<p> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Fecha: $Cpo[fecha] &nbsp; Hora: [".$Cpo[hora]."]</p>";

  		    echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<b> De: $Cpo[de]";				 
          
          echo " &nbsp; Titulo:  &nbsp; $Cpo[titulo]</b>"; 
            			 
			 echo "<br><br>";

          echo "<div align='left'><font size='+1'> &nbsp; &nbsp; &nbsp; &nbsp; Texto  ";       
			 echo "</div></b></font>";
//          echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<TEXTAREA NAME='Nota' cols='70' rows='10' >$Cpo[nota]</TEXTAREA>";              
			 ?>
             
         <TEXTAREA id='Nota' NAME='Nota' cols='80' rows='7'><?php echo "$Cpo[nota]";?></TEXTAREA>
		  
		  <script type='text/javascript'>CKEDITOR.replace('Nota');
		  </script>
		 
		 <?php
          echo "<div align='center'>";

       mysql_close();

      echo "</form>";

      echo "</td>";

  echo "</tr>";

echo "</table>";



CierraWin();

echo "</body>";

echo "</html>";

?>
<?php 
    
  session_start();
  
  require("lib/lib.php"); 
        
  $link=conectarse(); 
 
  #Variables necesarias para el regreso;
  $busca = $_REQUEST[busca];
  $Id 	= $_REQUEST[Id];    
  $Usr   = $_COOKIE['USERNAME'];
      
  #Variables que cambian;
  $Tabla  = "msj";		//Clientes telas;
  $Titulo = "Mensaje nuevo"; 
  $Return = "msjenv.php?busca=$busca";
 
  //$Usr      = $_COOKIE['USERNAME'];  
  //if($_REQUEST[Boton] == Aceptar AND $_SESSION['nivel'] >= 8){        //Para agregar uno nuevo
  if($_REQUEST[Boton] == Aceptar){        //Para agregar uno nuevo
  
      //$cCre=cZeros($_REQUEST[Credencial],5);

      if($busca=='NUEVO'){ 

          $Hora  = date("H:i");
     
          $Fecha = date("Y-m-d");
          
          if($_REQUEST[Para]<>"*"){

             $lUp   = mysql_query("INSERT INTO msj 
                      (para,de,fecha,hora,titulo,nota,tipo) 
                      VALUES
                      ('$_REQUEST[Para]','$Usr','$Fecha','$Hora','$_REQUEST[Titulo]','$_REQUEST[Nota]','1')
                      "); 

			    if($_REQUEST[Cc]<>''){                 
                $lUp   = mysql_query("INSERT INTO msj 
                         (para,de,fecha,hora,titulo,nota,tipo) 
                         VALUES
                         ('$_REQUEST[Cc]','$Usr','$Fecha','$Hora','$_REQUEST[Titulo]','Cc: $_REQUEST[Nota]','1')
                          ");
             }             
                   //$id=mysql_insert_id();
 			 }else{
 		
             $ParaA = mysql_query("SELECT uname FROM authuser WHERE uname<>'$Usr' AND level >='5'");
             //$ParaA = mysql_query("SELECT uname FROM authuser ORDER BY uname");
             while($rg = mysql_fetch_array($ParaA)){
                $Pra   = $rg[0];
                $lUp   = mysql_query("INSERT INTO msj (para,de,fecha,hora,titulo,nota,tipo) 
                         VALUES
                         ('$Pra','$Usr','$Fecha','$Hora','$_REQUEST[Titulo]','$_REQUEST[Nota]','1')"); 
                
				 }
          }	  		

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

  echo "<table width='98%' border='0' align='center' cellpadding=0 cellspacing=0>";
  echo "<tr><td width='10%'><a href='javascript:window.close()'><img src='lib/regresa.jpg' border='0'></a></td>";
  echo "<td align='center'>$Gfont <b>Bienvenidos area de mensajes en linea</b> </font></td></table>";
 
echo "<div align='right'><font color='#CCCCFF' size='1'>Hagamos de esto una buena herramienta &nbsp; </font></div>";

echo "<table width='98%' border='0' align='center' cellpadding=0 cellspacing=0><tr>";
echo "<td width='50%' align='left'>$Gfont<b>USUARIO: [$Usr]</b> </td>";
echo "<td width='50%' align='right'><img src='lib/mensajes.gif' border='0'> &nbsp; </td>";
echo "</tr></table>";

echo "$Gfont <b>Menu principal</b><br>";

echo "<table width='98%' border='1' align='center' cellpadding=0 cellspacing=0><tr>";
echo "<td width='33%' align='center'>$Gfont <a class='pg' href='msjrec.php'><b>Mensajes recibidos</b></a></td>";
echo "<td width='33%' align='center'>$Gfont <a class='pg' href='msjenv.php'><b>Mensajes enviados</b></a></td>";
echo "<td width='33%' align='center' bgcolor=$Gbarra>$Gfont <font color='#ffffff'><b>Nuevo mensaje</b></td>";
echo "</tr></table>";

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td align='left' width='50'><a href='$Return'><img src='lib/regresar.gif' border='0'></a></td> ";

    echo "<td align='left'>$Gfont <br> ";

       echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

          $Hora  = date("H:i");

          $CpoA  = mysql_query("SELECT * FROM msj WHERE msj.id = '$Id'");

          $Cpo   = mysql_fetch_array($CpoA);

  			 echo "<p>&nbsp; <b>Fecha:</b> $Fecha &nbsp; <b>Hora:</b> [$Hora]</p>";

  		    echo "&nbsp; <img src='lib/postal.jpg' border='0'> &nbsp; <font color=$GbarraSup> <b> Para: ";				 
          echo "<select name='Para'>";
          $ParaA = mysql_query("SELECT uname FROM authuser WHERE uname<>'$Usr' ORDER BY uname");
          //$ParaA = mysql_query("SELECT uname FROM authuser ORDER BY uname");
          while($rg=mysql_fetch_array($ParaA)){
            //echo "<option value='$rg[0]'>".ucfirst(strtolower($rg[0]))."</option>";
            echo "<option value='$rg[0]'>$rg[0]</option>";
            if($rg[0]==$Cpo[para]){
               $Disp=$rg[0];
            }
          }
          echo "<option value='*'> * &nbsp; Todos</option>";
          echo "<option selected value='$Cpo[para]'>$Disp</option>";
          echo "</select> &nbsp; ";
          
          echo " &nbsp; Titulo:  &nbsp; ";
			 echo "<input type='TEXT' name='Titulo' value='$Cpo[titulo]' maxlength='40' size='30'><br><br>"; 

  		    echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <font color=$GbarraSup> <b> C.c.:&nbsp; ";				 
          echo "<select name='Cc'>";
          //$ParaA = mysql_query("SELECT id,alias FROM tds WHERE id<>'$Tda' ORDER BY id");
          $ParaA = mysql_query("SELECT uname FROM authuser WHERE uname<>'$Usr' ORDER BY uname");
          while($rg=mysql_fetch_array($ParaA)){
            //echo "<option value='$rg[0]'>".ucfirst(strtolower($rg[0]))."</option>";
            echo "<option value='$rg[0]'>$rg[0]</option>";
            if($rg[0]==$Cpo[para]){
               $Disp=$rg[0];
            }
          }
          //echo "<option value='*'> * &nbsp; Todos</option>";
          echo "<option selected value='$Cpo[para]'>$Disp</option>";
          echo "</select> &nbsp; ";


            			 
			 echo "<br><br>";

          echo "<div align='left'><font size='+1'> Texto  ";       
			 echo "</div></b></font>";

			 ?>
             
         <TEXTAREA id='Nota' NAME='Nota' cols='80' rows='7'><?php echo "$Cpo[nota]";?></TEXTAREA>
		  
		  <script type='text/javascript'>CKEDITOR.replace('Nota');
		  </script>
		 
		 <?php
		 
          echo "<div align='center'>";
          if($busca=='NUEVO'){
             Botones();
          }  
           
          echo "</div>";

          mysql_close();

      echo "</form>";

      echo "</td>";

  echo "</tr>";

echo "</table>";

echo "<div align='center'><a class='ord' href='javascript:this.close()'>Cerrar esta ventana</a></div>";

CierraWin();

echo "</body>";

echo "</html>";

?>
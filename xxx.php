<?php

  session_start();

  require("lib/lib.php");

  $link = conectarse();
  
  echo "<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
  
  if($_REQUEST[op]=='ok'){
  
     $NumA  = mysql_query("SELECT count(*) FROM ot WHERE cliente='$_REQUEST[CliAnt]'");
     $Num   = mysql_fetch_array($NumA);
     
     $Up  = mysql_query("UPDATE ot SET cliente='$_REQUEST[CliOri]' WHERE cliente='$_REQUEST[CliAnt]'");

     $Up  = mysql_query("UPDATE cli SET numveces=numveces+$Num[0] WHERE cliente='$_REQUEST[CliOri]'");

     $Up  = mysql_query("DELETE FROM cli WHERE cliente='$_REQUEST[CliAnt]' LIMIT 1 ");
		    
  }  
  
  
  
  
  if(!$_REQUEST[CliAnt] OR $_REQUEST[op]=='ok'){

    echo "<form name='form1' method='get' action='xxx.php'>";
    
       echo "<p align='center'>Dame el No.de cuenta a borrar: ";
    
       echo "<input type='text' name='CliAnt' value='' size='5'> ";
    
       echo " <input type='submit' name='Boton' value='Busca'>";
    
       echo "</p>";
       
    echo "</form>";   
    
    echo "<a href='xxx.php'>REGRESAR</a>";
    
    
  }elseif($_REQUEST[CliAnt] AND !$_REQUEST[CliOri]){
      
    echo "<form name='form1' method='get' action='xxx.php'>";

    echo "<input type='hidden' name='CliAnt' value='$_REQUEST[CliAnt]'> ";

    $CliEliA = mysql_query("SELECT nombrec FROM  cli WHERE cliente=$_REQUEST[CliAnt] LIMIT 1");
    
    $CliEli  = mysql_fetch_array($CliEliA); 

    echo "<p align='center'>";
          
 	 echo "Cliente a Eliminar: $_REQUEST[CliAnt] $CliEli[nombrec]"; 
 	 
 	 echo "</p>";
	  
    echo "<p align='center'>Que cuenta lo va a sustituir: ";
    
    echo "<input type='text' name='CliOri' value='' size='5'>";
    
    echo "<input type='submit' name='Boton' value='Busca'>";
    
    echo "</p>";
    
    echo "</form>";

    echo "<a href='xxx.php'>REGRESAR</a>";
      
  }else{
  
    echo "<form name='form1' method='get' action='xxx.php'>";

    echo "<input type='hidden' name='op' value='ok'> ";

    echo "<input type='hidden' name='CliAnt' value='$_REQUEST[CliAnt]'> ";

    echo "<input type='hidden' name='CliOri' value='$_REQUEST[CliOri]'> ";

    $CliEliA = mysql_query("SELECT nombrec FROM  cli WHERE cliente=$_REQUEST[CliAnt] LIMIT 1");
    
    $CliEli  = mysql_fetch_array($CliEliA); 

    echo "<p align='center'>";
          
 	 echo "<b>Se cargaran las ordenes del Cliente:</b> $_REQUEST[CliAnt] $CliEli[nombrec]"; 
 	 
 	 echo "</p>";
 	 
 	 

    $CliEliA = mysql_query("SELECT nombrec FROM  cli WHERE cliente=$_REQUEST[CliOri] LIMIT 1");
    
    $CliEli  = mysql_fetch_array($CliEliA); 


    echo "<p align='center'>";
          
 	 echo "<b>Al cliente: </b>$_REQUEST[CliOri] $CliEli[nombrec] "; 
    
    echo "</p>";

    echo "<p align='center'>";
    
    echo "<b> Y sera dado de baja el cliente: $_REQUEST[CliAnt] </b> &nbsp;   <input type='submit' name='Boton' value='Realiza el cambio'>";
    
    echo "</p>";

    echo "</form>";
    
    echo "<a href='xxx.php'>REGRESAR</a>";
  
  
  }  
    
  mysql_close();
  
  echo "<body>";
  

  echo "</body>";

echo "</html>";


  ?>


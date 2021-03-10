<?php
/*  
echo "Nivel ".$check['level'];
echo "grupo o sucursal".$check['team'];
echo "Status ".$check['status'];
echo "Nombre: ".$check['username'];
*/

session_start();

if (isset($_REQUEST[busca])) {  // Si trae algo entra y asigna los valores a session;
    $_SESSION['cVar']   = $_REQUEST[busca];    
}

require("lib/lib2.php");

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

   
if($_SESSION['cVarVal']=='Ini'){

    $_SESSION['cVarVal']='';
    
    
    setcookie ("TEAM", $check['team']);        //Sucursal
    setcookie ("LEVEL", $check['level']);
    
    //if($check['team']<>9){		// A que grupo pertenece osea es usuario restringido;
        
        if($_SESSION['cVar']<>$check['team']){
            
          header("Location: index.php?op=99");
            
         }    

    //}
}	  

/*
   if ( $check['team'] <> 'Admin' and $check['team']<>'Visor'){

        echo 'No tienes acceso a esta pagina.';

         exit();

   }

   
   if(strlen($check['team'])==1 || $check['team']=='Admin' ){    //Si es una tienda o Admin cambio
       $_SESSION['usr']='Admin';
         print "<SCRIPT></SCRIPT>\n";

   }

    //   echo "Vlor de session :  $check['status']  ";
*/

  require ("config.php");							//Parametros de colores;

   //<p align="center"><img src="images/logo1.jpg" width="400" height="200"></p>

?>

<html>
<head>

<title><?php echo $Titulo;?></title>

</head>

<?php 

  echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

  headymenu("Menu principal (inicio)",1);
  
  echo "<table border='0' width='100%' align='center' border=0 cellpadding=0 cellspacing=0>";

  echo "<tr><td height='280' align='center'>$Gfont ";

  echo "<p align='center'><img src='lib/doctor.jpg' border=0></p>"; 
  
  echo "<p align='center'>$Gfont <b>".$_REQUEST[Msj]."</b></p>";
    
  if($_REQUEST[cMes] <> ''){
  
  		echo "<p align='center'>$Gfont|a href <b>".$_REQUEST[Msj]."</b></p>";

  		if($_REQUEST[cMes]<>''){
  		
  			echo "<p align='center'><a href=javascript:wingral('medconsolida.php?op=cc&cMes=$_REQUEST[cMes]')>Consolida la demanda de cliente por medico en el catalogo de medicos</a></p>";
		  		
  		}
  
   }

  echo "</td></tr>";
     
  echo "<tr background='lib/prueba.jpg' height='80'>";

  echo "<td>&nbsp;</td>";

  echo "</tr></table>";

?>

<p>&nbsp;</p>


</body>

</html>
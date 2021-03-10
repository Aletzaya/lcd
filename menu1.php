<?php
  //echo "Nivel ".$check['level']." grupo ".$check['team']." Sta ".$check['status']." Nom: ".$check['username'];

   session_start();

   require("lib/kaplib.php");

   include_once ("auth.php");

   include_once ("authconfig.php");

   include_once ("check.php");

   $_SESSION['nivel']=$check['level'];

/*   if ( $check['team'] <> 'Admin' and $check['team']<>'Visor'){

        echo 'No tienes acceso a esta pagina.';

         exit();

   }
*/
   if(strlen($check['team'])==1 || $check['team']=='Admin' ){    //Si es una tienda o Admin cambio
       $_SESSION['usr']='Admin';
         print "<SCRIPT></SCRIPT>\n";

   }


//   echo "Vlor de session :  $check['status']  ";


?>

<html>
<head>

<title><?php echo $Titulo;?></title>

</head>
<body onload="window.open('mensaje.php','','width=100,height=20 0')" bgcolor="#FFFFFF">

<?php headymenu("Sistema de control clinico",1); ?>

<p>&nbsp;</p>

<p align="center"><a href="javascript:(opener=this).alert('Alerta Urgencia')"><img src="images/logo1.jpg" width="400" height="200"></p>
</body>
</html>
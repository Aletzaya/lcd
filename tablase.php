<?php

  session_start();
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  
  require("lib/lib.php");
  
  $link  = conectarse();

  //Cuando entra por primera vez debe de traer estos parametros por default;
  //if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  //Saco los valores de las sessiones los cuales normalmente no cambian;
  $busca  = $_REQUEST[busca];        //Dato a busca
  $Return = "tablas.php";
  $Id     = 1;

  #Variables que cambian;
  $Tabla  = "files";
  $Titulo = "Tablas del sistema [$busca]";

  if($_REQUEST[Boton] == Aceptar ){        //Para agregar uno nuevo
      if($busca == 'NUEVO'){
          $lUp = mysql_query("INSERT INTO $Tabla (tabla,descripcion) VALUES ('$_REQUEST[Tabla]','$_REQUEST[Descripcion]')",$link);
          //$id=mysql_insert_id();
      }else{
         $lUp  = mysql_query("UPDATE $Tabla SET 
         tabla='$_REQUEST[Tabla]',descripcion='$_REQUEST[Descripcion]' WHERE id='$busca' limit 1",$link);
      }

      header("Location: $Return");

 }elseif($_REQUEST[Boton] == Cancelar){

    header("Location: $Return");

 }


 $CpoA  = mysql_query("SELECT * FROM $Tabla WHERE id= '$busca'");

 $Cpo   = mysql_fetch_array($CpoA);

 $Fecha = date("Y-m-d");

 require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onload="cFocus()">

<?php headymenu($Titulo,1); ?>

<script language="JavaScript1.2">

function cFocus(){
  document.form1.Nombre.focus();
}

</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='10%' align='center'>";

        echo "<a href=$Return><img src='lib/regresa.jpg' border='0'></a>";

    echo "</td><td align='center'><br>";

       echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

       cTable('80%','0');
       
       cInput("Id:","Text","5","Id","right",$Cpo[id],"5",false,true,"");

       cInput("Tabla:","Text","10","Tabla","right",$Cpo[tabla],"10",false,false,"");

       //cInput('','text','','','','','',false,true,'Apellido paterno, materno y Nombre');

       cInput("Descripcion:","Text","40","Descripcion","right",$Cpo[descripcion],"40",false,false,'');

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
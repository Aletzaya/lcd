<?php

  session_start();
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/lib.php");
  
  $link=conectarse();

  //Cuando entra por primera vez debe de traer estos parametros por default;
  //if(isset($_REQUEST[busca]))  { $_SESSION['busca'] = $_REQUEST[busca];}	   #Campo por el cual se ordena	

  //Saco los valores de las sessiones los cuales normalmente no cambian;
  //$busca  = $_SESSION[busca];        //Dato a busca
  $busca  = $_REQUEST[busca];        //Dato a busca
  $Return = "querys.php";
  $Id     = 4;

  #Variables que cambian;
  $Tabla="qrys";
  $Titulo="Querys [$busca]";

  if($_REQUEST[Boton] == Aceptar ){        //Para agregar uno nuevo
      if($busca=='NUEVO'){
         $lUp=mysql_query("insert into $Tabla (nombre,tampag,froms,filtro,defcampos,defedi,campos,edi,lef) 
         VALUES 
         ('$_REQUEST[Nombre]','$_REQUEST[Tampag]','$_REQUEST[Froms]','$_REQUEST[Filtro]','$_REQUEST[Defcampos]',
         '$_REQUEST[Defedi]','$_REQUEST[Campos]','$_REQUEST[Edi]','$_REQUEST[Lef]')");
         //$id=mysql_insert_id();
 	 }else{
         $lUp=mysql_query("UPDATE $Tabla SET 
         nombre='$_REQUEST[Nombre]',tampag='$_REQUEST[Tampag]',froms='$_REQUEST[Froms]',defcampos='$_REQUEST[Defcampos]',
         defedi='$_REQUEST[Defedi]',campos='$_REQUEST[Campos]',edi='$_REQUEST[Edi]',ayuda='$_REQUEST[Ayuda]',
         lef='$_REQUEST[Lef]' 
         WHERE 
         id='$busca' limit 1");
    }

    header("Location: $Return?busca=");

 }elseif($_REQUEST[Boton] == Cancelar){

    header("Location: $Return");

 }


 $CpoA=mysql_query("select * from $Tabla where id= '$busca'",$link);

 $Cpo=mysql_fetch_array($CpoA);

 $Fecha=date("Y-m-d");

 require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onload="cFocus()">

<?php headymenu($Titulo,1); ?>

<script language="JavaScript1.2">

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
}

function cFocus(){
  document.form1.Nombre.focus();
}

</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='5%' align='center'>";

        echo "<a href=$Return><img src='lib/regresa.jpg' border='0'></a>";

    echo "</td><td align='center'><br>";

       echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

       cTable('80%','0');
       
       cInput("Id:","Text","5","Id","right",$Cpo[id],"40",false,true,"");

       cInput("Nombre:","Text","50","Nombre","right",$Cpo[nombre],"40",false,false,"");

       //cInput('','text','','','','','',false,true,'Apellido paterno, materno y Nombre');

       cInput("Tablas:","Text","40","Froms","right",$Cpo[froms],"40",false,false,'');

       cInput("Left Join:","Text","40","Lef","right",$Cpo[lef],"40",false,false,'');

       cInput("Renglones por pagina:","Text","2","Tampag","right",$Cpo[tampag],"2",false,false,'');

       cTableCie();

		echo $Gfont;
		       
       echo "<p align='center'>Unicamente campos a jalar sql <b> DEFAULT </b> <br>";
       echo "<TEXTAREA NAME='Defcampos' cols='80' rows='3' >$Cpo[defcampos]</TEXTAREA></p>";
       
       echo "<p align='center'>Encabezado a editar por campo <b>DEFAULT</b><br>";
       echo "<TEXTAREA NAME='Defedi' cols='80' rows='3' >$Cpo[defedi]</TEXTAREA></p>";
       
       echo "<p align='center'>Campos que se estan editando<br>";
       echo "<TEXTAREA NAME='Campos' cols='80' rows='3' >$Cpo[campos]</TEXTAREA></p>";

       echo "<p align='center'>Encabezado de campos que esta editando<br>";
       echo "<TEXTAREA NAME='Edi' cols='80' rows='3' >$Cpo[edi]</TEXTAREA></p>";
       
       echo "<p align='center'> Filtro <br> ";
       echo "<TEXTAREA NAME='Filtro' cols='80' rows='3' >$Cpo[filtro]</TEXTAREA></p>";

       echo "<hr><p align='center'> Texto de Ayuda <br> ";
       echo "<TEXTAREA NAME='Ayuda' cols='100' rows='4' >$Cpo[ayuda]</TEXTAREA></p>";
              
       echo Botones();

       mysql_close();

      echo "</form>";

      echo "</td>";

  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";

?>
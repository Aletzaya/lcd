<?php

  session_start();

  include_once ('auth.php');

  include_once ('authconfig.php');

  include_once ('check.php');

  require('lib/kaplib.php');

  $link   = conectarse();

  $Usr    = $check['uname'];

  $busca  = $_REQUEST[busca];

  $Tabla  = 'dep';

  $pagina = $_REQUEST[pagina];

  $Titulo = "Detalle departamento: $busca";

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

      if($busca=='NUEVO'){
        $lUp=mysql_query("select nombre from  $Tabla where nombre='$_REQUEST[Nombre]' limit 1",$link);
        $Exi=mysql_fetch_array($lUp);
        if( $Exi[nombre] == $_REQUEST[Nombre] ){
           $Msj='El nombre del departamento $Exi[nombre] ya existe! favor de verificar...';
       }else{
          $lUp=mysql_query("insert into $Tabla (nombre) VALUES ('$_REQUEST[Nombre]')",$link);
          //$id=mysql_insert_id();
          header('Location: depto.php');
       }
 	 }else{
         $lUp=mysql_query("update $Tabla SET nombre='$_REQUEST[Nombre]' where departamento='$busca' limit 1",$link);
         header('Location: depto.php?pagina=$pagina');
     }
 }elseif($_REQUEST[Boton] == Cancelar){

    header('Location: depto.php?pagina=$pagina');

 }


 $CpoA=mysql_query("select * from $Tabla where departamento= '$busca'",$link);

 $Cpo=mysql_fetch_array($CpoA);

 $lAg=$Nombre<>$Cpo[nombre];

 $Fecha=date('Y-m-d');

 require ('config.php');

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onload='cFocus()'>

<?php headymenu($Titulo,0); ?>

<script language='JavaScript1.2'>

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
  
    echo "<td width='15%' align='center'>";

       echo "<a href='depto.php?pagina=$pagina'><img src='lib/regresa.jpg' border='0'></a>";


    echo "</td>";

    echo "<td>$Gfont ";

	 echo "<p>&nbsp;</p><p>&nbsp;</p>";

    echo "<form name='form1' method='get' action='deptoe.php'>";

          cTable('70%','0'); 
                   
          cInput('Departamento:','text','8','Departamento','right',$busca,'8',true,true,'');
          cInput('Nombre:','text','30','Nombre','right',$Cpo[1],'30',true,false,'');
  
          //cInput('Datos particulares','','','','right','','','','','');

          //cInput('Nivel de participacion:','text','3','X','right',$Cpo[participacion],'3',false,true,'');


          cTableCie();

           echo Botones();

           mysql_close();
    echo "</form>";

  echo "</td>";

  echo "</tr>";

echo "</table>";

echo "</body>";

echo "</html>";
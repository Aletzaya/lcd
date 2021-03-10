<?php
  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $Usr     = $check['uname'];

  $link    = conectarse();

  $tamPag  = 12;

  $pagina  = $_REQUEST[pagina];

  $busca   = $_REQUEST[busca];

  $op      = $_REQUEST[op];

  $Titulo  = "Detalle de la Zona ".$busca;

  $Tabla   = "znsd";

  if($cOp=='El'){

     $Rg=mysql_query("delete from znsd where zona='$busca' and localidad='$_REQUEST[Localidad]' limit 1",$link);

  }elseif($cOp=='Ag'){

     $Rg=mysql_query("insert into znsd  (zona,localidad,observacion) VALUES ('$busca','$_REQUEST[Localidad]','$_REQUEST[Observacion]')",$link);

  }

  $HeA   = mysql_query("select * from zns where zona='$busca'",$link);

  $He    = mysql_fetch_array($HeA);

  $cSql  = "select * from $Tabla where zona='$busca'";

  $OrdenDef = "zona";            //Orden de la tabla por default

  $Edit     = array("Elim","Localidad","Nota","-","-","-");

  require ("config.php");

  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>

<body bgcolor="#FFFFFF">

<?php

   headymenu($Titulo,1);
?>

<script language="JavaScript1.2">
function Mayusculas(cCampo){
if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();}
if (cCampo=='Observacion'){document.form1.Observacion.value=document.form1.Observacion.value.toUpperCase();}
}
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
	alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}

function Completo(){
var lRt;
lRt=true;
if(document.form1.Localidad.value==""){lRt=false;}
if(document.form1.Observacion.value==""){lRt=false;}
if(!lRt){
   alert("Faltan datos por llenar, favor de verificar");
   return false;
}else{
  return true;
}
}
</script>

<?php

  echo "$Gfont <p align='center'>Zona : $busca $He[descripcion]</p>";

  filtro($Tabla);           #---------------Si trae algo del filtro realizalo ----------------


  if($_REQUEST[op]=='sm'){

     $cSum=mysql_query("select sum($_REQUEST[SumaCampo]) from $Tabla ".$cWhe,$link);

     $Suma=mysql_fetch_array($cSum);

     $cFuncion=" // --> $SumaCampo: ".number_format($Suma[0],"2");

  }

  if(!$res=mysql_query($cSql.$cWhe,$link)){

     cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

        $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
        $res=mysql_query($sql,$link);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){
           echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
           echo "<td align='center'><a href=zonasd.php?busca=$busca&Localidad=$registro[localidad]&cOp=El&pagina=$pagina><img src='lib/deleon.png' alt='Edita reg' border='0'></td>";
           echo "<td>$Gfont $registro[localidad]</font></td>";
           echo "<td>$Gfont $registro[observacion]</font></td>";
           //echo "<td>$Gfont $registro[poblacion]</font></td>";
           $nRng++;
		}//fin while

        PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

     echo "<form name='form1' method='get' action='zonasd.php' onSubmit='return Completo();'>";
          echo "<a class='pg' href='zonas.php'><img src='lib/regresa.jpg' border='0'></a>$Gfont ";
          echo " Localidad: ";
          echo "<input type='text' name='Localidad' onBlur='Mayusculas(Localidad)'>";
          echo "&nbsp; &nbsp; Nota: ";
          echo "<input type='text' name='Observacion' onBlur='Mayusculas(Observacion)'> &nbsp; ";
          echo "<input type='submit' name='Submit' value='Agrega'>";
          echo "<input type='hidden' name='cOp' value='Ag'>";
          echo "<input type='hidden' name='pagina' value='$pagina'>";
          echo "<input type='hidden' name='busca' value='$busca'>";
     echo "</form>";          
echo "</body>";

echo "</html>";


mysql_close();

?>
<?php
  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $Usr=$check['uname'];

  $busca    = $_REQUEST[busca];
  $pagina   = $_REQUEST[pagina];
  $op       = $_REQUEST[op];
  $cId      = $_REQUEST[cId];
  $tamPag   = 12;

  $OrdenDef = "depd.subdepto";            //Orden de la tabla por default

  $Tabla    = "depd";

  if($op=='El'){

     $Rg=mysql_query("DELETE from depd where departamento='$busca' and subdepto='$_REQUEST[Sub]' limit 1");

  }elseif($_REQUEST[Boton]=='Agregar'){

     $Rg=mysql_query("INSERT into depd  (departamento,subdepto,nombre) VALUES ('$busca','$_REQUEST[Sub]','$_REQUEST[Nombre]')");

  }elseif($_REQUEST[Boton]=='Actualizar'){

     $Rg = mysql_query("UPDATE depd SET subdepto='$_REQUEST[Sub]', nombre='$_REQUEST[Nombre]' WHERE id='$cId'");
  
  }

  $HeA    = mysql_query("SELECT nombre from dep where departamento='$busca'");

  $He     = mysql_fetch_array($HeA);

  $cSql   = "SELECT * FROM depd WHERE departamento='$busca'";

  $Edit   = array("Modifica","Sub-depto","Nombre","Elimina","-","-","-","-");

  $Titulo = "Sub-deptos de: $He[nombre]";

  require ("config.php");

  //echo "$Gfont <p align='center'>Departamento : $busca $He[nombre]</p>";

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
if (cCampo=='Sub'){document.form1.Sub.value=document.form1.Sub.value.toUpperCase();}
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}

function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}

function Completo(){
var lRt;
lRt=true;
if(document.form1.Sub.value==""){lRt=false;}
if(document.form1.Nombre.value==""){lRt=false;}
if(!lRt){
   alert("Faltan datos por llenar, favor de verificar");
   return false;
}else{
  return true;
}
}
</script>

<?php

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

        while($rg=mysql_fetch_array($res)){
        
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";        

           echo "<td align='center'><a href='deptod.php?busca=$busca&op=ed&cId=$rg[id]&pagina=$pagina'><img src='lib/edit.png' alt='Elimina reg' border='0'></td>";

           echo "<td>$Gfont $rg[subdepto]</font></td>";
           echo "<td>$Gfont $rg[nombre]</font></td>";
           echo "<td align='center'><a href='deptod.php?busca=$busca&Sub=$rg[subdepto]&op=El&pagina=$pagina'><img src='lib/deleoff.png' alt='Elimina reg' border='0'></td>";
           echo "</tr>";
           $nRng++;

		  }//fin while

        PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

     if($op=='ed'){

		 $CpoA  = mysql_query("SELECT * FROM depd WHERE id=$_REQUEST[cId]");
		 $Cpo   = mysql_fetch_array($CpoA);    
    
      }
 
		 
     echo "<form name='form1' method='get' action='deptod.php' onSubmit='return Completo();'>";
     echo $Gfont;
     
     if($op=='ed'){
        echo "regresar<br>";
        echo "<a href='deptod.php?busca=$busca'><img src='lib/regresa.jpg' border='0'></a>";
     }else{
        echo "<a href='depto.php'><img src='lib/regresa.jpg' border='0'></a>";
     }   

     echo "$Gfont Sub-depto :";
     echo "<input type='text' name='Sub' value='$Cpo[subdepto]' size='20' onBlur=Mayusculas('Sub')>";
     echo "&nbsp; Nombre :";
     echo "<input type='text' name='Nombre' value='$Cpo[nombre]' size='30' onBlur=Mayusculas('Nombre')>";
     echo "<input type='hidden' name='pagina' value='$pagina'>";
     echo "<input type='hidden' name='busca' value='$busca'>";
     
     if($op=='ed'){
			
  			   echo "<input type='hidden' name='cId' value='$Cpo[id]'>";
   			echo "<input type='submit' name='Boton' value='Actualizar'>";			
  			   
  		}else{
  			
			   echo "<input type='submit' name='Boton' value='Agregar'>";			
  			
  		}   			
     

     echo "</form>";


echo "</body>";

echo "</html>";

mysql_close();

?>
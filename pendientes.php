<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr     = $check['uname'];

  $link    = conectarse();

  $tamPag  = 15;

  $pagina  = $_REQUEST[pagina];

  $busca   = $_REQUEST[busca];

  $Estudio = $_REQUEST[Estudio];

  $op      = $_REQUEST[op];

  $Msj     = "";

  $Fecha   = date("Y-m-d");

  $Depto   = $_REQUEST[Depto];

  $Hora    = date("h:i:s");

  $Titulo  = "Estudios pendientes de entregar";

  $OrdenDef= "ot.fecha, ot.hora";            //Orden de la tabla por default

  $DepA    = mysql_query("SELECT * FROM dep",$link);

  $SubA    = mysql_query("SELECT subdepto,nombre FROM depd",$link);

  if(strlen($Depto)==0){$Depto='999';}

  if($Depto<>"*"){
  
 	 if(strlen($busca)>=1){

	     $SqlA = "SELECT cli.nombrec,ot.orden,ot.fechae,otd.estudio,est.descripcion,otd.status 
                FROM ot,est,otd,cli 
                WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.status<>'TERMINADA' 
                AND est.depto='$Depto' AND ot.fechae <= '$Fecha' AND ot.orden='$busca'";

  	 }else{

    	 $SqlA  = "SELECT cli.nombrec,ot.orden,ot.fechae,otd.estudio,est.descripcion,otd.status 
                FROM ot,est,otd,cli 
                WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio
                AND est.depto='$Depto' AND otd.status<>'TERMINADA' AND ot.fechae <= '$Fecha'";

  	 }
  	 
  }else{

	 if(strlen($busca)>=1){

	     $SqlA = "SELECT cli.nombrec,ot.orden,ot.fechae,otd.estudio,est.descripcion,otd.status 
	             FROM ot,est,otd,cli 
	             WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio 
	             AND otd.status<>'TERMINADA' AND est.depto='$Depto' AND ot.fechae <= '$Fecha' AND ot.orden='$busca'";

  	 }else{

    	 $SqlA  = "SELECT cli.nombrec,ot.orden,ot.fechae,otd.estudio,est.descripcion,otd.status 
    	          FROM ot,est,otd,cli 
    	          WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio 
    	          AND otd.status<>'TERMINADA' AND ot.fechae <= '$Fecha' AND est.depto='$Depto'";

  	 }
  	 
}


require ("config.php");							//Parametros de colores;
  

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>";

echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

echo "</head>";

echo "<body bgcolor='#FFFFFF'>";


  headymenu($Titulo,1);

?>

<script language="JavaScript1.2">
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=800,height=350,left=100,top=150")
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

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Win(url){
   window.open(url,"WinVen","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=650,height=400,left=50,top=150")
}
function Win2(url){
   window.open(url,"WinVen","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=350,height=400,left=50,top=150")
}

</script>

<?php

   echo "<table align='center' width='100%' border='0' >";
   echo "<tr><td>$Gfont";
   echo "<form name='form1' method='get' action='pendientes.php'>";
   echo " &nbsp; Departamento : ";
   echo "<SELECT name='Depto'>";
   while ($dep=mysql_fetch_array($DepA)){
           echo "<option value='$dep[0]'>$dep[1]</option>";
           if($dep[0]==$Depto){$Def=$dep[1];}
   }
   echo "<option value='*'>TODOS</option>";
   if($Depto=="*"){
    	echo "<option SELECTed value='*'>TODOS LOS DEPARTAMENTOS</option>";
   }else{
        echo "<option SELECTed value='$Depto'>$Def</option>";
   }
   echo "</SELECT>";
   
   /*
     echo "<font color='#660066'>&nbsp;&nbsp;&nbsp;Sub-depto : </FONT>";
	echo "<SELECT name='Sub'>";
	while ($sub=mysql_fetch_array($SubA)){
           echo "<option value='$sub[0]'>$sub[1]</option>";
    }
	echo "<option SELECTed value='*'>* todos</option>";
    echo "</SELECT>";
   */
   echo "&nbsp;&nbsp;&nbsp;Busca: ";
   echo "<input type='text' name='busca' size='6' maxlength='5'>";

   echo "&nbsp;&nbsp;&nbsp;<input type='submit' name='Submit' value='Ok'>";
   echo "<font color='#990000' size='1'>$Msj</font>";
   echo "</form></font></td>";
   echo "</tr></table>";

   //echo $SqlA." ORDER BY ".$OrdenDef;
   
   if(!$res=mysql_query($SqlA." ORDER BY ".$OrdenDef,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/ò limpia.</a></font>";
 		echo "</div>";
 	}else{

        $numeroRegistros=mysql_num_rows($res);
         if(!isset($orden)){
             $orden=$OrdenDef;
        }
        if(!isset($pagina)){
            $pagina=1;
            $inicio=1;
            $final=$tamPag;
        }
        //calculo del limite inferior
        $limitInf=($pagina-1)*$tamPag;
        //calculo del numero de paginas
        $numPags=ceil($numeroRegistros/$tamPag);
        if(!isset($pagina)){
               $pagina=1;
               $inicio=1;
               $final=$tamPag;
        }else{
            $seccionActual=intval(($pagina-1)/$tamPag);
            $inicio=($seccionActual*$tamPag)+1;
            if($pagina<$numPags){
               $final=$inicio+$tamPag-1;
            }else{
                $final=$numPags;
                if($numeroRegistros > 15){$limitInf = ($numeroRegistros - $tamPag);}
            }
        }
        
     $sql=$SqlA." ORDER BY ".$OrdenDef." ASC LIMIT ".$limitInf.",".$tamPag;
     $res=mysql_query($sql,$link);

	  echo "<div align='center'>";
	  echo "<img src='images/corner-bottom-left.gif' width='15' height='12'>";
	  echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Estudios</b></strong></font>";
	  echo "<img src='images/corner-bottom-right.gif' width='15' height='12'>";
	  echo "</div>";
	  
	  echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
     echo "<tr height='25' background='lib/bartit.gif'>";
     echo "<th>$Gfont Orden</font></th>";
     echo "<th>$Gfont Paciente</font></th>";
     echo "<th>$Gfont Fecha Ent</font></th>";
     echo "<th>$Gfont Estudio</font></th>";
     echo "<th>$Gfont Descripcion</font></th>";
     echo "<th>$Gfont Status</font></th>";
	  echo "</tr>";
	  
	  while($registro=mysql_fetch_array($res)){ 

           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
	  
          echo "<td>$Gfont $registro[orden]</td>";
          echo "<td>$Gfont ".substr($registro[nombrec],0,30)."</td>";
          echo "<td>$Gfont $registro[fechae]</td>";
          echo "<td>$Gfont $registro[estudio]</td>";
          echo "<td>$Gfont $registro[descripcion]</td>";
          echo "<td>$Gfont $registro[status]</td>";
          echo "</tr>";
          $nRng++;
        
	  }//fin while
	  echo "</table>";
	}

    echo "<table border='0' cellspacing='0' cellpadding='0' align='center'>";
    echo "<tr><td align='center' valign='top'>";

    if($pagina>$tamPag){
        echo "&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=1&orden=".$orden."&Depto=".$Depto."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>"."|<"."</font></a>&nbsp;";
        echo "&nbsp;&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($inicio-15)."&orden=".$orden."&Depto=".$Depto."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'><</font>";
        echo "</a>&nbsp;&nbsp;";
    }
    for($i=$inicio;$i<=$final;$i++){
        if($i==$pagina){
            echo "<font face='verdana' size='-2'><b>".$i."</b>&nbsp;</font>";
        }else{
            echo "<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".$i."&orden=".$orden."&Depto=".$Depto."&busca=".$busca."'>";
            echo "<font face='verdana' size='-2'>".$i."</font></a>&nbsp;";
        }
        if($i>=$numPags){$i=$numPags+15;}
    }
    if($inicio+15<$numPags){
        echo "&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($inicio+15)."&orden=".$orden."&Depto=".$Depto."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>></font></a>";
        echo "&nbsp;&nbsp;&nbsp;<a class='p' href='".$_SERVER["PHP_SELF"]."?pagina=".($numPags)."&orden=".$orden."&Depto=".$Depto."&busca=".$busca."'>";
        echo "<font face='verdana' size='2'>".">|"."</font></a>&nbsp;";
    }
    ?>
    </td></tr>
    </table>
<p>&nbsp;</p>
<p><div align="center"><a href="ordenes.php?pagina=<? echo $pagina;?>"><img src="images/SmallExit.bmp" border="0"></a></div><br>
</p>
</body>
</html>
<?
mysql_close();
?>

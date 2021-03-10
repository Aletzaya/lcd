<?php

  session_start();

 if(!isset($_REQUEST[Depto])){$Depto=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[Depto];$Depto=$_REQUEST[Depto];}

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $Estudio=$_REQUEST[Estudio];

  $op=$_REQUEST[op];

  $Msj="";

  $Fecha=date("Y-m-d");

  //$Depto=$_REQUEST[Depto];

  $Hora = date("h:i:s");

  $Titulo="Estudios por departamento";

  $OrdenDef="ot.orden";            //Orden de la tabla por default

  $DepA=mysql_query("SELECT * FROM dep",$link);

  $SubA=mysql_query("SELECT subdepto,nombre FROM depd",$link);

  if(strlen($Depto)==0){$Depto='999';}

  if($Depto<>"*"){

     $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
            otd.status,otd.etiquetas,est.muestras 
            FROM ot,est,otd,cli 
            WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.status='DEPTO' AND est.depto='$Depto' AND otd.orden >= '$busca'";

  }else{

     $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,
            otd.status,otd.etiquetas,est.muestras 
            FROM ot,est,otd,cli 
            WHERE ot.orden=otd.orden AND ot.cliente=cli.cliente AND otd.estudio=est.estudio AND otd.status='DEPTO' AND otd.orden >= '$busca'";

  }

  $Edit = array("Edit"," &nbsp; &nbsp; &nbsp; ","Paciente","Est","Descripcion","Orden","#Muest","#Imp","Fecha","Hora","Status","-","-","-","-","-","-","-","-","-","-","-");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
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
<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,1);

echo "<hr noshade style='color:000099;height:2px'>";

   echo "<div>$Gfont";
   echo "<form name='form1' method='post' action='estdepto.php'>";
      echo " &nbsp; &nbsp; Departamento : ";
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
      echo " &nbsp; &nbsp; Busca: ";
      echo "<input type='text' name='busca' size='6' maxlength='6'>";

      echo " &nbsp; <input type='submit' name='Submit' value='Ok'>";

      echo "<input type='hidden' name='pagina' value='1'>";
      
   echo "</form></font>";
   echo "</div>";

   if(!$res=mysql_query($SqlA." ORDER BY ".$OrdenDef,$link)){
        cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes
 	}else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
        
		  if($limitInf<0){$limitInf=0;} 

        $sql=$SqlA.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

        $res=mysql_query($sql,$link);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

	  while($registro=mysql_fetch_array($res)){
          if( ($nRng % 2) > 0 ){$Fdo='#FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

          //echo "<td align='center'><a class='pg' href='capturares.php?busca=$registro[1]&estudio=$registro[4]&Depto=$Depto&pagina=$pagina')> &nbsp; <img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
          
          echo "<td align='center'><a class='pg' href=javascript:wingral('capturares.php?busca=$registro[1]&estudio=$registro[4]&Depto=$Depto&pagina=$pagina')> &nbsp; <img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
          // echo "<a href=javascript:wingral('visitas.php?busca=$busca')>Historial/visitas</a></td>";
          
          echo "<td align='center'><a href=javascript:Ventana('impeti.php?op=1&busca=$registro[1]&Est=$registro[estudio]')><img src='lib/print.png' alt='Edita reg' border='0'> </a></td>";
          echo "<td>$Gfont ".substr($registro[0],0,30)."$Gfon</td>";
          echo "<td>$Gfont $registro[4] $Gfon</td>";
          echo "<td>$Gfont ".substr($registro[5],0,20)."$Gfon</td>";
          echo "<td>$Gfont $registro[1] $Gfon</td>";
          echo "<td>$Gfont $registro[8] $Gfon</td>";
          echo "<td>$Gfont $registro[7] $Gfon</td>";
          echo "<td>$Gfont $registro[2] $Gfon</td>";
          echo "<td>$Gfont".substr($registro[3],0,5)."$Gfon</td>";
          echo "<td>$Gfont $registro[6] $Gfon</td>";
          $nRng++;
	  }//fin while
	  echo "</table>";
      PonPaginacion(false);      #-------------------pon los No.de paginas-------------------
	}

    ?>
</body>
</html>
<?
mysql_close();
?>
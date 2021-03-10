<?php

  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  if(isset($_REQUEST[Depto])){			//Si trae valor entra y asignalo a una session

     $_SESSION['cVarVal']=$_REQUEST[Depto];

  }

  $Depto=$_SESSION['cVarVal'];

  $tamPag=15;

  $op=$_REQUEST[op];

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Msj="";

  $Fecha=date("Y-m-d");

  $Hora = date("H:i");

  //$Depto=$_REQUEST[Depto];

  $Titulo="Captura de resultados";

  $OrdenDef="ot.orden";            //Orden de la tabla por default

  $DepA=mysql_query("select * from dep",$link);

  $SubA=mysql_query("select subdepto,nombre from depd",$link);

  if(strlen($Depto)==0){$Depto='999';}

  if($Depto=='TERMINADA'){

     if(strlen($busca)>0){

      $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,otd.status,ot.institucion,est.depto
             FROM ot,est,otd,cli
             WHERE ot.orden=otd.orden and ot.cliente=cli.cliente and otd.estudio=est.estudio and otd.status='$Depto'
                   and ot.status<>'Entregada' and ot.orden='$busca'";

     }else{

      $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,otd.status,ot.institucion,est.depto
             FROM ot,est,otd,cli
             WHERE ot.orden=otd.orden and ot.cliente=cli.cliente and otd.estudio=est.estudio and otd.status='$Depto'
                   and ot.status<>'Entregada'";

     }

  }else{

    if(strlen($busca)>0){

       $SqlA="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,otd.status,ot.institucion,est.depto
             FROM ot,est,otd,cli
             WHERE ot.orden=otd.orden and ot.cliente=cli.cliente and otd.estudio=est.estudio and otd.status='RESUL'
                   and est.depto='$Depto' and ot.orden='$busca'";

            // WHERE ot.orden=otd.orden and ot.cliente=cli.cliente and otd.estudio=est.estudio

    }else{

       $SqlA ="SELECT cli.nombrec,ot.orden,ot.fecha,ot.hora,otd.estudio,est.descripcion,otd.status,ot.institucion,
               est.depto
               FROM ot,est,otd,cli
               WHERE ot.orden=otd.orden and ot.cliente=cli.cliente and otd.estudio=est.estudio and otd.status='RESUL'
                     and est.depto='$Depto'";

    }

  }

  $Edit = array("Edit","Orden","Paciente","Est","Descripcion","Inst","Fecha","Hora","Status","-","-","-","-","-","-","-","-","-");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<script language="JavaScript1.2">

function AbreVentana(url){
   window.open(url,"upword","width=300,height=400,left=600,top=150,scrollbars=no,location=no,dependent=yes,resizable=no");
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

function WinRes(url){
   window.open(url,"WinRes","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=900,height=600,left=30,top=80")
}
function WinResTex(url){
   window.open(url,"WinRes","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width='100%',height=600,left=30,top=80")
}

</script>

<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,1);


    echo "$Gfont";
    echo "<form name='form1' method='post' action='estdeptores.php'>";
    echo " &nbsp; Departamento :  ";
    echo "<select name='Depto'>";
    while ($dep=mysql_fetch_array($DepA)){
           echo "<option value='$dep[0]'>$dep[1]</option>";
           if($dep[0]==$Depto){$Def=$dep[1];}
    }
    echo "<option selected value='$Depto'>$Def</option>";
    echo "</select>";
    echo " &nbsp; No.Orden : ";

    echo "<input type='text' name='busca' size='6' maxlength='6'>";

    echo " &nbsp; &nbsp; <input type='submit' name='Submit' value='Ok'>";
    if($Depto=='TERMINADA'){$Msj="ORDENES PENDIENTES DE IMPRESION";}
    echo "<div align='center'><font color='#990000' size='3'>$Msj</font></div>";
    echo "</form></font>";

    if(!$res=mysql_query($SqlA." ORDER BY ".$OrdenDef,$link)){
       cMensaje("No se encontraron resultados � hay un error en el filtro");    #Manda mensaje de datos no existentes
    }else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

		  if($limitInf<0){$limitInf=0;}

        $sql=$SqlA.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

        $res=mysql_query($sql,$link);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){

          if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

		    if($registro[depto]==2){			//Word texto;
             echo "<td align='center'><a class='pg' href=javascript:WinResTex('capturaresword.php?busca=$registro[1]&estudio=$registro[4]&pagina=$pagina&Depto=$Depto')> &nbsp; <img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
          }else{
             echo "<td align='center'><a class='pg' href=javascript:WinRes('capturaresdiag.php?busca=$registro[1]&estudio=$registro[4]&pagina=$pagina&Depto=$Depto')> &nbsp; <img src='lib/edit.png' alt='Edita reg' border='0'> </td>";
			 }
          echo "<td>$Gfont $registro[1]</font></td>";
          echo "<td>$Gfont ".substr($registro[0],0,28)."</font></td>";
          echo "<td>$Gfont $registro[4] </font></td>";
          //echo "<td><a href=capturaresdiag.php?busca=$registro[1]&estudio=$registro[4]&pagina=$pagina&Depto=$Depto><font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#0066FF'><b>$registro[4]</b></font></a></td>";
          echo "<td>$Gfont $registro[5]</font></td>";
          echo "<td>$Gfont $registro[7]</font></td>";
          echo "<td>$Gfont $registro[2]</font></td>";
          echo "<td>$Gfont $registro[3]</font></td>";
          echo "<td>$Gfont $registro[6]</font></td>";
          echo "</tr>";
          $nRng++;


   	  }//fin while
	   echo "</table>";

      PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

      //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

    }//fin if

    mysql_close();

    ?>

</body>

</html>
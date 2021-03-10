<?php
  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $Usr=$check['uname'];
  $Fecha=date("Y-m-d H:i");
  $busca    = $_REQUEST[busca];
  $pagina   = $_REQUEST[pagina];
  $op       = $_REQUEST[op];
  $cId      = $_REQUEST[cId];
  $tamPag   = 12;
  $filtro    = $_REQUEST[filtro];       
  $filtro3    = $_REQUEST[filtro3];       

  $OrdenDef = "instfact.id";            //Orden de la tabla por default

  $Tabla    = "instfact";
  $Id     = 60; 


 if($filtro=='*'){
  $filtro2="";
 }else{
  $filtro2="and instfact.tpago='$filtro'";
 }
 
 if($filtro3=='*'){
  $filtro4="";
 }else{
  $filtro4="and instfact.status='$filtro3'";
 }


  #Tomo los datos principales campos a editar, tablas y filtros; 
  $QryA  = mysql_query("SELECT campos,froms,edi,tampag,filtro FROM qrys WHERE id='$Id'");
  $Qry   = mysql_fetch_array($QryA);

  if($_REQUEST[c_iva]=='1'){
    $iva=$_REQUEST[importe]*0.16;
  }else{
    $iva=0;
  }

  if($_REQUEST[status]==''){
    $statusO='Pendiente';
  }else{
    $statusO=$_REQUEST[status];
  }

  $total=$_REQUEST[importe]+$iva;

  if($op=='El'){

     $Rg=mysql_query("DELETE from instfact where id='$cId' limit 1");

  }elseif($_REQUEST[Boton]=='Agregar'){

      if($statusO=='Pendiente'){

         $lUp = mysql_query("UPDATE inst SET  factpend = factpend + 1 WHERE institucion='$busca' limit 1");
      }

     $Rg=mysql_query("INSERT into instfact (inst,fecha,importe,iva,c_iva,total,status,tpago,usr,documento,doctopago,fechacap,observaciones,factrem) VALUES ('$busca','$_REQUEST[fecha]','$_REQUEST[importe]','$iva','$_REQUEST[c_iva]','$total','$statusO','$_REQUEST[tpago]','$Usr','$_REQUEST[documento]','$_REQUEST[doctopago]','$Fecha','$_REQUEST[observaciones]','$_REQUEST[factrem]')");

  }elseif($_REQUEST[Boton]=='Actualizar'){

      if($statusO=='Pendiente'){

         $lUp = mysql_query("UPDATE inst SET  factpend = factpend + 1 WHERE institucion='$busca' limit 1");

      }else{

         $lUp = mysql_query("UPDATE inst SET  factpend = factpend - 1 WHERE institucion='$busca' limit 1");

      }

     $Rg = mysql_query("UPDATE instfact SET fecha='$_REQUEST[fecha]',importe='$_REQUEST[importe]',iva='$iva',c_iva='$_REQUEST[c_iva]',total='$total',status='$statusO',tpago='$_REQUEST[tpago]',usr='$Usr',documento='$_REQUEST[documento]',doctopago='$_REQUEST[doctopago]',fechacap='$Fecha',observaciones='$_REQUEST[observaciones]',factrem='$_REQUEST[factrem]' WHERE id='$cId'");

  }

  $HeA    = mysql_query("SELECT alias from inst where institucion='$busca'");

  $He     = mysql_fetch_array($HeA);

  $cSql   = "SELECT * FROM instfact WHERE inst='$busca' $filtro2 $filtro4";

  $Titulo = "Institución: $He[alias]";

  $aCps   = SPLIT(",",$Qry[campos]);    // Es necesario para hacer el order by  desde lib;
          
  $aIzq   = array("Ed","-","-");    //Arreglo donde se meten los encabezados; Izquierdos
  $aDat   = SPLIT(",",$Qry[edi]);                //Arreglo donde llena el grid de datos        
  $aDer   = array("Img","-","-"," ","-","-");             //Arreglo donde se meten los encabezados; Derechos;
  $tamPag = $Qry[tampag];

  require ("config.php");

  //echo "$Gfont <p align='center'>Departamento : $busca $He[nombre]</p>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

 <link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>
 
<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>


<title><?php echo $Titulo;?></title>

</head>

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

  if(!$res=mysql_query($cSql.$cWhe,$link)){

     cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

        $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
        $res=mysql_query($sql,$link);

        echo "<table align='right' width='20%' border='0' cellspacing='0' cellpadding='0' background='lib/degrada.jpg'>";

        echo "<tr align='right'>";
        echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Tipo de Pago</b></font>";
        echo "<form name='form1' method='post' action='institufacte.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3'>";
        echo "<select size='1' name='filtro' class='Estilo10' onchange=this.form.submit()>";
        echo "<option value='*'>Todos*</option>";
        echo "<option value='Efectivo'> Efectivo </option>";
        echo "<option value='Tarjeta'> Tarjeta </option>";
        echo "<option value='Cheque'> Cheque </option>";
        echo "<option value='Transferencia'> Transferencia </option>";
        echo "<option selected value='*'>$Gfont <font size='-1'>$filtro</option></p>";      
        echo "</select>";
        echo"</b></td><p>";
        echo "</form1>";

        echo "<td align='right'>$Gfont<b><font size='1' color='#009900'>Status</b></font>";
        echo "<form name='form1' method='post' action='institufacte.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3'>";
        echo "<select size='1' name='filtro3' class='Estilo10' onchange=this.form.submit()>";
        echo "<option value='*'>Todos*</option>";
        echo "<option value='Pendiente'> Pendiente </option>";
        echo "<option value='Pagada'> Pagada </option>";
        echo "<option value='Cancelada'> Cancelada </option>";
        echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3</option></p>";     
        echo "</select>";
        echo"</b></td>";
        echo "</form1>";

        //echo "&nbsp; <input type='SUBMIT' value='Ok'>";
        echo"</tr></table>";

        PonEncabezado2();         #---------------------Encabezado del browse----------------------

        while($rg=mysql_fetch_array($res)){
        
            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            if($cId==$rg[id]){
              $Fdo='#e6b0aa';
            }else{
              $Fdo=$Fdo;
            }

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";        

            echo "<td align='center'><a href='institufacte.php?busca=$busca&op=ed&cId=$rg[id]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3'><img src='lib/edit.png' alt='Edita reg' border='0'></td>";


            echo "<td>$Gfont $rg[id]</font></td>";
            echo "<td>$Gfont $rg[fecha]</font></td>";
            echo "<td>$Gfont $rg[documento]</font></td>";
            echo "<td align='right'>$Gfont ".number_format ($rg[importe],'2')."</font></td>";
            echo "<td align='right'>$Gfont ".number_format ($rg[iva],'2')."</font></td>";
            echo "<td align='right'>$Gfont ".number_format ($rg[total],'2')."</font></td>";
            echo "<td>$Gfont $rg[tpago]</font></td>";
            echo "<td>$Gfont $rg[status]</font></td>";
            echo "<td>$Gfont $rg[doctopago]</font></td>";
            echo "<td>$Gfont $rg[usr]</font></td>";
            echo "<td>$Gfont $rg[fechacap]</font></td>";
            echo "<td>$Gfont $rg[factrem]</font></td>";


            $ImgA = mysql_query("SELECT archivo FROM factinst WHERE id='$rg[id]' and usrelim=''");

            $Img=mysql_fetch_array($ImgA);

            if($Img[archivo]<>''){

              echo "<td align='center' colspan='2'><a href=javascript:wingral('displayfactinst.php?busca=$rg[id]')><img src='lib/desplegar.png' width='18'></a></td>";

            }else{

              echo "<td align='center' colspan='2'><a href=javascript:wingral('displayfactinst.php?busca=$rg[id]')><img src='lib/subir.jpg' alt='Up_Img' border='0' width='15'></a></td>";

            }

            if($Usr=='nazario' or $Usr=='sebastian'or $Usr=='Sebastian' or $Usr=='SEBASTIAN'){

              echo "<td align='center'><a href='institufacte.php?busca=$busca&cId=$rg[id]&op=El&pagina=$pagina&filtro=$filtro&filtro3=$filtro3'><img src='lib/deleoff.png' alt='Elimina reg' border='0'></td>";    

            }

            echo "</tr>";
            $nRng++;

  		  }//fin while

        PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

  }//fin if

  if($op=='ed'){

    $CpoA  = mysql_query("SELECT * FROM instfact WHERE id=$_REQUEST[cId]");
    $rg   = mysql_fetch_array($CpoA);    

  }

  echo "<form name='form1' method='get' action='institufacte.php?filtro=$filtro&filtro3=$filtro3'>";

  echo "<table align='center' width='100%' border='0'>";

  echo "<tr bgcolor='#d6eaf8'><td>"; 
  echo "$Gfont <b><font size='1'> Fecha : </b></font>";
  echo "</td>"; 

  
  if($rg[factrem]=='Factura'){

    echo "<td>"; 
    echo "$Gfont <b><font size='1'><input type='radio' name='factrem' value='Factura' required checked> # Factura / <input type='radio' name='factrem' value='Remision'> # Remision : </b></font>";
    echo "</td>";

  }elseif($rg[factrem]=='Remision'){

    echo "<td>"; 
    echo "$Gfont <b><font size='1'><input type='radio' name='factrem' value='Factura' required> # Factura / <input type='radio' name='factrem' value='Remision' checked> # Remision : </b></font>";
    echo "</td>";

  }else{

    echo "<td>"; 
    echo "$Gfont <b><font size='1'><input type='radio' name='factrem' value='Factura' required> # Factura / <input type='radio' name='factrem' value='Remision'> # Remision : </b></font>";
    echo "</td>";

  }

  echo "<td>"; 
  echo "$Gfont <b><font size='1'> Importe : </b></font>";
  echo "</td>"; 

  if($rg[c_iva]==1){

     echo "<td>"; 
     echo "$Gfont <b><font size='1'> IVA : <input type='checkbox' name='c_iva' value='1' checked></b></font>";
     echo "</td>"; 

  }else{

     echo "<td>"; 
     echo "$Gfont <b><font size='1'> IVA : <input type='checkbox' name='c_iva' value='1'></b></font>";
     echo "</td>"; 

  }

  echo "<td>"; 
  echo "$Gfont <b><font size='1'> Total : </b></font>";
  echo "</td>"; 

  echo "<td>"; 
  echo "$Gfont <b><font size='1'> Tipo de Pago : </b></font>";
  echo "</td>";

  echo "<td>"; 
  echo "$Gfont <b><font size='1'> Status : </b></font>";
  echo "</td>"; 

  echo "<td>"; 
  echo "$Gfont <b><font size='1'> Documento de pago : </b></font>";
  echo "</td>";

  echo "<tr bgcolor='#d6eaf8'><td>"; 

  echo "<input type='text' name='fecha' size='10' value ='$rg[fecha]' required> <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].fecha,'yyyy-mm-dd',this)></b></font>";
  echo "</td>"; 

  echo "<td>"; 
  echo "$Gfont <b><font size='1'><input type='text' name='documento' value='$rg[documento]' size='30' required></b></font>";
  echo "</td>"; 

  echo "<td>"; 
  echo "$Gfont <b><font size='1'><input type='number' name='importe' value='$rg[importe]' size='15' required></b></font>";
  echo "</td>"; 

  echo "<td>"; 
  echo "$Gfont <b><font size='1'>$ ".number_format ($rg[iva],"2")."</b></font>";
  echo "</td>"; 

  echo "<td>"; 
  echo "$Gfont <b><font size='1'>$ ".number_format ($rg[total],"2")."</b></font>";
  echo "</td>"; 

  echo "<td align='left'>$Gfont<b><font size='1'>";
  echo "<select size='1' name='tpago' class='Estilo10' required>";
  echo "<option value=''> Seleccione una opción</option>";
  echo "<option value='Efectivo'> Efectivo </option>";
  echo "<option value='Tarjeta'> Tarjeta </option>";
  echo "<option value='Cheque'> Cheque </option>";
  echo "<option value='Transferencia'> Transferencia </option>";
  echo "<option selected value='$rg[tpago]'>$Gfont <font size='-1'>$rg[tpago]</option>";     
  echo "</select>";
  echo"</b></font></td>";

  echo "<td align='left'>$Gfont<b><font size='1'>";
  echo "<select size='1' name='status' class='Estilo10'>";
  echo "<option value='Pendiente'> Pendiente </option>";
  echo "<option value='Pagada'> Pagada </option>";
  echo "<option value='Cancelada'> Cancelada </option>";
  echo "<option selected value='$rg[status]'>$Gfont <font size='-1'>$rg[status]</option>";     
  echo "</select>";
  echo"</b></font></td>";

  echo "<td>"; 
  echo "$Gfont <b><font size='1'><input type='text' name='doctopago' value='$rg[doctopago]' size='30'></b></font>";
  echo "</td>"; 

  echo "<input type='hidden' name='pagina' value='$pagina'>";
  echo "<input type='hidden' name='busca' value='$busca'>";
  echo "<input type='hidden' name='cId' value='$rg[id]'>";

  echo "</tr>"; 

  echo "<tr bgcolor='#d6eaf8'><td>"; 
  echo "$Gfont <b><font size='1'> Observaciones : </b></font>";
  echo "</td>"; 
  echo "<td colspan='4'>"; 
  echo "$Gfont <b><font size='1'><input type='text' name='observaciones' value='$rg[observaciones]' size='80' rows='3'></b></font>";
  echo "</td>"; 

  if($op=='ed'){

    echo "<td>";
    echo "$Gfont <b><font size='1'> Factura/Remision : </b></font>";
    echo "</td>";

    $ImgA = mysql_query("SELECT archivo FROM factinst WHERE id='$rg[id]' and usrelim=''");

    $Img=mysql_fetch_array($ImgA);

    if($Img[archivo]<>''){

      echo "<td align='center' colspan='2'><a href=javascript:wingral('displayfactinst.php?busca=$rg[id]')><img src='lib/desplegar.png' width='18'></a></td>";

    }else{

      echo "<td align='center' colspan='2'><a href=javascript:wingral('displayfactinst.php?busca=$rg[id]')><img src='lib/subir.jpg' alt='Up_Img' border='0' width='15'></a></td>";

    }

    echo "</tr>"; 

    echo "<input type='hidden' name='filtro' value='$filtro'>";
    echo "<input type='hidden' name='filtro3' value='$filtro3'>";

    echo "<tr><td></td><td colspan='3' align='center'>"; 
    echo "<input type='submit' name='Boton' value='Cancelar'>"; 
    echo "</td>"; 

    echo "<td colspan='3' align='center'>"; 

    echo "<input type='submit' name='Boton' value='Actualizar'>";     

  }else{


     echo "<td>";
     echo "</td>";
     echo "<td colspan='2'>";
     echo "</td>";
     echo "</tr>"; 

    echo "<tr><td></td><td colspan='3' align='center'>"; 
    echo "$Gfont <b><font size='1'> regresar <br>";
    echo "<a href='institufact.php?orden=inst.institucion&Sort=Asc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Fech2=&Fech3=&estudio=&suc='><img src='lib/regresa.jpg' border='0'></a></b></font>";
    echo "</td>"; 

    echo "<td colspan='3' align='center'>"; 

    echo "<input type='submit' name='Boton' value='Agregar'>"; 

  }  

  echo "</td><td></td>"; 

  echo "</tr>"; 
  echo "</table>"; 

  echo "</form>";

  echo "</body>";

  echo "</html>";

  mysql_close();

?>
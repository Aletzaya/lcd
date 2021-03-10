<?php

  session_start();

  require("lib/lib.php");

  $link        = conectarse();

  $FechaI      = $_REQUEST[FecI];
  $FechaF      = $_REQUEST[FecF];
  $Tpago      = $_REQUEST[Tpago];
  $Status      = $_REQUEST[Status];

  if($Tpago=='Efectivo'){
    $Tpago='and fc.formadepago=01';
  }elseif($Tpago=='Cheque'){
    $Tpago='and fc.formadepago=02';
  }elseif($Tpago=='Transferencia'){
    $Tpago='and fc.formadepago=03';
  }elseif($Tpago=='Tarjetacredito'){
    $Tpago='and fc.formadepago=04';
  }elseif($Tpago=='Tarjetadebito'){
    $Tpago='and fc.formadepago=28';
  }else{
    $Tpago='';
  }

  if($Status=='Abierta'){
    $Status="and fc.status='Abierta'";
  }elseif($Status=='Cancelada'){
    $Status="and fc.status='Cancelada'";
  }elseif($Status=='Timbrada'){
    $Status="and fc.status='Timbrada'";
  }else{
    $Status='';
  }
  $Recepcionista=$_REQUEST[Recepcionista];

  if($Recepcionista=='*'){
    $Recepcion='';
  }else{
    $Recepcion=" And fc.usr='$Recepcionista'";
  }

  
  $Sucursal     =   $_REQUEST[Sucursal];
  //$Sucursal     =   $Sucursal[0];
  $sucursalt = $_REQUEST[sucursalt];
  $sucursal0 = $_REQUEST[sucursal0];
  $sucursal1 = $_REQUEST[sucursal1];
  $sucursal2 = $_REQUEST[sucursal2];
  $sucursal3 = $_REQUEST[sucursal3];
  $sucursal4 = $_REQUEST[sucursal4];

    $Sucursal= "";
  
  if($sucursalt=="1"){  
  
    $Sucursal="*";
    $Sucursal2= " * - Todas ";
  }else{
  
    if($sucursal0=="1"){  
      $Sucursal= " fc.suc=0";
      $Sucursal2= "Administracion - ";
    }
    
    if($sucursal1=="1"){ 
      $Sucursal2= $Sucursal2 . "Laboratorio - "; 
      if($Sucursal==""){
        $Sucursal= $Sucursal . " fc.suc=1";
      }else{
        $Sucursal= $Sucursal . " OR fc.suc=1";
      }
    }
    
    if($sucursal2=="1"){
      $Sucursal2= $Sucursal2 . "Hospital Futura - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " fc.suc=2";
      }else{
        $Sucursal= $Sucursal . " OR fc.suc=2";
      }
    }
    
    if($sucursal3=="1"){
      $Sucursal2= $Sucursal2 . "Tepexpan - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " fc.suc=3";
      }else{
        $Sucursal= $Sucursal . " OR fc.suc=3";
      }
    }
    
    if($sucursal4=="1"){
      $Sucursal2= $Sucursal2 . "Los Reyes - ";
      if($Sucursal==""){
        $Sucursal= $Sucursal . " fc.suc=4";
      }else{
        $Sucursal= $Sucursal . " OR fc.suc=4";
      }
    }
  }

  // Es una copia de clientes y se cambian todas las urls a clientesord y el exit al ordenesnvas com busca
  $Titulo  = "Relacion de facturas emitidas del $FechaI al $FechaF sucursal $Sucursal2";

  if($Sucursal <> '*'){
              $cSql    = "SELECT fc.id,fc.folio,fc.fecha,fc.cliente,fc.cantidad,fc.importe,fc.iva,clif.nombre,fc.suc,fc.status,fc.usr,fc.formadepago
             FROM fc LEFT JOIN clif ON fc.cliente=clif.id
             WHERE date_format(fc.fecha,'%Y-%m-%d') Between '$FechaI' And '$FechaF' $Recepcion AND ($Sucursal) $Tpago $Status";
  }else{
              $cSql    = "SELECT fc.id,fc.folio,fc.fecha,fc.cliente,fc.cantidad,fc.importe,fc.iva,clif.nombre,fc.suc,fc.status,fc.usr,fc.formadepago
             FROM fc LEFT JOIN clif ON fc.cliente=clif.id
             WHERE date_format(fc.fecha,'%Y-%m-%d') Between '$FechaI' And '$FechaF' $Recepcion $Tpago $Status";      
  }

  $Edit   = array("Id","Folio","Fecha","Cuenta","Nombre","Cantidad","Importe","Iva","Total"
           ,"-","-","Nfc.Id","Nfc.folio","Dfc.fecha","Nfc.cuenta","Cclif.nombre","Nfc.cantidad","Nfc.importe","Nfc.iva","Cfc.total");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>    
<?php 

   headymenu($Titulo,1);

   $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";
   $Gfont = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";

        echo "<table align='center' width='95%' border='0' cellspacing='1' cellpadding='0'><tr bgcolor='#a2b2de'>";
        echo "<th align='CENTER' >$Gfon Suc</font></th>";
		    echo "<th align='CENTER' >$Gfon Id</font></th>";
        echo "<th align='CENTER' >$Gfon Folio</font></th>";
        echo "<th align='CENTER' >$Gfon Fecha</font></th>";
        echo "<th align='CENTER' >$Gfon Cuenta</font></th>";
        echo "<th align='CENTER' >$Gfon Nombre</font></th>";
        echo "<th align='CENTER' >$Gfon Cantidad</font></th>";
        echo "<th align='CENTER' >$Gfon Importe</font></th>";
        echo "<th align='CENTER' >$Gfon Iva</font></th>";
        echo "<th align='CENTER' >$Gfon Total</font></th>";
        echo "<th align='CENTER' >$Gfon T.pago</font></th>";
        echo "<th align='CENTER' >$Gfon Status</font></th>";
        echo "<th align='CENTER' >$Gfon Usuario</font></th>";
        echo "</tr>";
   
        $sql  = $cSql." ORDER BY id ASC ";

        //echo $sql;
        
        $res  = mysql_query($sql);

        //PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){
            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            $Tpago=$registro[formadepago];

              if($Tpago==01){
                $Tpago='Efectivo';
              }elseif($Tpago==02){
                $Tpago='Cheque';
              }elseif($Tpago==03){
                $Tpago='Transferencia';
              }elseif($Tpago==04){
                $Tpago='Tarjetacredito';
              }elseif($Tpago==28){
                $Tpago='Tarjetadebito';
              }

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            //echo "<td align='center'><a class='ord' href=javascript:winuni('repotsd.php?busca=$registro[orden]')><img src='lib/browse.png'  border='0'></td>";
            //echo "<td align='center'><a href=ordenesd.php?busca=$registro[orden]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";
            echo "<td>$Gfont $registro[suc]</font></a></td>";
			echo "<td>$Gfont $registro[id]</font></a></td>";
            echo "<td>$Gfont $registro[folio]</font></a></td>";
            echo "<td>$Gfont $registro[fecha]</font></td>";
            echo "<td>$Gfont $registro[cliente]</font></td>";
            echo "<td>$Gfont $registro[nombre]</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[cantidad],"2")."</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[importe],"2")."</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[iva],"2")."</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[importe]+$registro[iva],"2")."</font></td>";
            echo "<td align='center'>$Gfont $Tpago</font></td>";
            echo "<td align='center'>$Gfont $registro[status]</font></td>";
            echo "<td align='center'>$Gfont $registro[usr]</font></td>";
            echo "</tr>";
            $nRng++;
            $Tcantidad+=$registro[cantidad];
            $Timporte+=$registro[importe];
            $TGrlal+=$registro[importe]+$registro[iva];
            $Tiva+=$registro[iva];
        }//fin while

        echo "<tr bgcolor='#a2b2de'>";
        echo "<th align='CENTER' colspan='4'>$Gfon $nRng Registros</font></th>";
        echo "<th align='CENTER' >$Gfon </font></th>";
        echo "<th align='CENTER' >$Gfon </font></th>";
        echo "<th align='right' >$Gfon ".number_format($Tcantidad,"2")."</font></th>";
        echo "<th align='right' >$Gfon ".number_format($Timporte,"2")."</font></th>";
        echo "<th align='right' >$Gfon ".number_format($Tiva,"2")."</font></th>";
        echo "<th align='right' >$Gfon ".number_format($TGrlal,"2")."</font></th>";
        echo "<th align='CENTER' >$Gfon </font></th>";
        echo "<th align='CENTER' >$Gfon </font></th>";
        echo "<th align='CENTER' >$Gfon </font></th>";
        echo "</tr>";

        echo "</table>";	
        

        echo '<form name="form1" method="post" action="pidedatos.php?cRep=17">';
        echo "<div align='left'><a class='pg' href='pidedatos.php?cRep=17'><img src='lib/regresa.jpg' border='0'></a> &nbsp; &nbsp; &nbsp; &nbsp; ";
        echo '<input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">';
        echo "</div>";
        echo '</form>';
        
  mysql_close();

  ?>

</body>

</html>
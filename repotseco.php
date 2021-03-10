<?php

  session_start();

  require("lib/kaplib.php");

  $link     = conectarse();

  $OrdenDef = "ot.fecha";            //Orden de la tabla por default

  $busca    = $_REQUEST[busca];

  $Tabla    = "ot";

  // Es una copia de clientes y se cambian todas las urls a clientesord y el exit al ordenesnvas com busca
  $Titulo  = "Ordenes abiertas";

  $cSql    = "SELECT ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.status,ot.ubicacion,
             ot.institucion,ot.recepcionista,ot.responsableco 
             FROM ot,cli 
             WHERE ot.cliente=cli.cliente AND ot.orden = '$busca'";
  
  $Edit   = array("Edit","Det","Orden","Fecha","Cliente","Nombre","Importe","Status","Recep","Resp_Eco."
           ,"Not.orden","Dot.fecha","Not.cliente","Ccli.nombrec","Not.importe","Cot.status","Not.institucion","Cot.recepcionista","Cot.pagada");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
<body bgcolor="#FFFFFF">

<?php 

  //headymenu($Titulo,1);

  //echo $cSql.$cWhe;
  
        $sql  = $cSql.$cWhe." ORDER BY orden DESC ";

        //echo $sql;
        
        $res  = mysql_query($sql);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){
            echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
            echo "<td align='center'><a class='ord' href=javascript:winuni('repotsd.php?busca=$registro[orden]&resp=si')><img src='lib/browse.png'  border='0'></td>";
            //echo "<td align='center'><a href=ordenesd.php?busca=$registro[orden]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";
            echo "<td>$Gfont $registro[institucion] - $registro[orden]</font></a></td>";
            echo "<td>$Gfont $registro[fecha]</font></td>";
            echo "<td>$Gfont $registro[fechae]</font></td>";
            echo "<td align='right'>$Gfont $registro[3]</font></td>";
            echo "<td>$Gfont $registro[4]</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[5],"2")."</font></td>";
            echo "<td>$Gfont $registro[status]</font></td>";
            echo "<td>$Gfont $registro[recepcionista]</font></td>";
            echo "<td>$Gfont $registro[responsableco]</font></td>";
            echo "</tr>";
            $nRng++;
        }//fin while

		  echo "</table>";	
        
        echo "<p align='center'><a class='pg' href='javascript:window.close()'>CERRAR ESTA VENTANA</a></p>";

  mysql_close();

  ?>

</body>

</html>
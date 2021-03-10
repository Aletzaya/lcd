<?php

  session_start();

  require("lib/lib.php");

  $link        = conectarse();

  $FechaI      = $_REQUEST[FechaI];
  $FechaF      = $_REQUEST[FechaF];
  $Institucion = $_REQUEST[Institucion];

  // Es una copia de clientes y se cambian todas las urls a clientesord y el exit al ordenesnvas com busca
  $Titulo  = "Pacientes por institucion  del $FechaI al $FechaF Institucion $Institucion";

  $cSql    = "SELECT ot.cliente,cli.apellidop,cli.apellidom,cli.nombre,cli.nombrec,cli.municipio,cli.colonia,
              cli.direccion,cli.fechan,cli.sexo,cli.codigo,cli.telefono,cli.alta as fechaalta,
              cli.credencial,cli.mail,cli.curp,cli.fecmod as ultimamod,cli.otro,cli.usr as usralta
              FROM ot LEFT JOIN cli ON ot.cliente=cli.cliente
              WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.institucion='$Institucion'
              GROUP BY ot.cliente";      
  
  $Edit   = array("Folio","Fecha","Cuenta","Nombre","Cantidad","Importe","Iva","Total"
           ,"-","-","Nfc.folio","Dfc.fecha","Nfc.cuenta","Cclif.nombre","Nfc.cantidad","Nfc.importe","Nfc.iva","Cfc.total");

  
  //echo $cSql;
  
  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>    
<?php 

        headymenu($Titulo,1);

        echo "<table align='center' width='95%' border='0' cellspacing='1' cellpadding='0'><tr bgcolor='#B1B1B1'>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Cta</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Nombre</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Ap.paterno</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Ap.materno</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Nombre</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Direccion</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Colonia</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Mpio</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Fec.nac</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Sex</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Codigo</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Cod.sis</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Telf</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Fec.alt</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Credencial</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>O.dato</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Mail</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Curp</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Usr.alta</font></th>";
        echo "<th align='CENTER' ><font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#FFFFFF'>Ult.mod</font></th>";
        echo "</tr>";
   
        //$sql  = $cSql;

        //echo $sql;
        
        $res  = mysql_query($cSql);

        //PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){
            
            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
            
            echo "<tr bgcolor=$Gfdogrid onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Gfdogrid';>";
            echo "<td>$Gfont $registro[cliente]</font></a></td>";
            echo "<td>$Gfont $registro[nombrec]</font></a></td>";
            echo "<td>$Gfont $registro[apellidop]</font></td>";
            echo "<td>$Gfont $registro[apellidom]</font></td>";
            echo "<td>$Gfont $registro[nombre]</font></td>";
            echo "<td>$Gfont $registro[direccion]</font></td>";
            echo "<td>$Gfont $registro[colonia]</font></td>";
            echo "<td>$Gfont $registro[municipio]</font></td>";
            echo "<td>$Gfont $registro[fechan]</font></td>";
            echo "<td>$Gfont $registro[sexo]</font></td>";
            echo "<td>$Gfont $registro[codigo]</font></td>";
            echo "<td>$Gfont $registro[codigosis]</font></td>";
            echo "<td>$Gfont $registro[telefono]</font></td>";
            echo "<td>$Gfont $registro[fechaalta]</font></td>";
            echo "<td>$Gfont $registro[credencial]</font></td>";
            echo "<td>$Gfont $registro[otro]</font></td>";
            echo "<td>$Gfont $registro[mail]</font></td>";
            echo "<td>$Gfont $registro[curp]</font></td>";
            echo "<td>$Gfont $registro[usralta]</font></td>";
            echo "<td>$Gfont $registro[ultimamod]</font></td>";
            echo "</tr>";
            $nRng++;
        }//fin while

        echo "</table>";	
        

        echo '<form name="form1" method="post" action="pidedatos.php?cRep=17">';
        echo "<div align='left'><a class='pg' href='menu.php'>Regresar</a> &nbsp; &nbsp; &nbsp; &nbsp; ";
        echo '<input type="submit" name="Imprimir" value="Imprimir" onCLick="print()">';
        echo "</div>";
        echo '</form>';
        
  mysql_close();

  ?>

</body>

</html>
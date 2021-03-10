<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $link=conectarse();

  $tamPag=15;

  $op=$_REQUEST[op];

  $Usr=$check['uname'];

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $Tabla="reportes";

  $Titulo="Detalle del reporte [$busca]";

  if($op=="Ag"){    //Agrega una pregunta

     $lUp=mysql_query("insert into reportesd (id,variable,orden,pregunta,tipo,longitud) VALUES ('$busca','$_REQUEST[Variable]','$_REQUEST[Orden]','$_REQUEST[Pregunta]','$_REQUEST[Tipo]','$_REQUEST[Longitud]')",$link);

  }elseif($op=="Ah"){		//Para agregar el headers
     if($busca=='NUEVO'){
         $lUp=mysql_query("insert into reportes (nombre,descripcion,instruccion) VALUES ('$_REQUEST[Nombre]','$_REQUEST[Descripcion]','$_REQUEST[Instruccion]')",$link);
         $busca=mysql_insert_id();
     }else{
         $lUp=mysql_query("update reportes Set nombre='$_REQUEST[Nombre]',descripcion='$_REQUEST[Descripcion]',instruccion='$_REQUEST[Instruccion]' where id='$busca'",$link);
     }
  }elseif($op=="El"){    // Para dar de baja
      $lUp=mysql_query("delete from reportesd where id='$busca' and pregunta='$_REQUEST[Pregunta]' limit 1",$link);
      header("Location: reportese.php?busca=$busca&pagina=$pagina");
  }
  $cSql="select * from reportes where id='$busca'";
  $CpoA=mysql_query($cSql,$link);
  $Cpo=mysql_fetch_array($CpoA);
?>
<html>
<head>
<title><?php echo $Titulo;?></title>
<meta http-equiv="Pragma" content="no-cache" />
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
</head>
<body>
<?php 
echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

headymenu($Titulo,1);
?>
<script language="JavaScript1.2">
function SiElimina(){
  if(confirm("ATENCION! Desea dar de Baja este registro?")){
     return(true);
   }else{
     document.form1.busca.value="NUEVO";
      return(false);
   }
}
</script>
<hr noshade style="color:3366FF;height:2px">
<table width="973" height="325" border="0">
  <tr>
    <td><div align="center"><a href="reportes.php?pagina=<?php echo $pagina; ?>"><img src="images/SmallExit.bmp" border="0"></a></div></td>
  <td>
  <form name="form1" method="get" action="reportese.php">
   		  <p>
           <font color="#0000FF"><b>Numero......:
           <?php echo $busca;?>
           Nombre......:
           <input name="Nombre" type="text" size="20" value = <?php echo $Cpo[nombre];?> >
           </p>
           <p>Descripcion:
           <input name="Descripcion" type="text" size="68" value = <?php echo $Cpo[descripcion];?> >
           </p>
           <p>Instruccion SQL..:
           <br>
           <TEXTAREA NAME="Instruccion" cols="90" rows="5" ><?php echo "$Cpo[instruccion]"; ?></TEXTAREA>
           </p>
           <p><div align='center'>
           <input type="IMAGE" name="Guarda" src="images/Guarda.gif" alt="Guarda cambios" >
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <input type="IMAGE" name="Elimina" src="images/Elimina.gif" alt="Elimina Registro" onClick="SiElimina()">
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <input type='Reset' value='Recupera'>
           <input type="hidden" name="op" value="Ah">
           <input type="hidden" name="pagina" value=<?php echo $pagina; ?>>
           <input type="hidden" name="busca" value=<?php echo $busca; ?>>
           </div></p>
     </form>
     <div align="center"><font color="#0066FF" size="2">Registro de variables a pedir al ejecutar el reporte </font> </div>
     <form name="form2" method="get" action="reportese.php" >
     <table align='center' width='80%' border='0' cellspacing='1' cellpadding='0'>
	 <tr><td colspan='6'><hr noshade></td></tr>
     <td bgcolor='#6633FF'>&nbsp;</td>
     <td bgcolor='#6633FF'><font color="#FFFFFF">Orden</font></td>
     <td bgcolor='#6633FF'><font color="#FFFFFF">Pregunta</font></td>
     <td bgcolor='#6633FF'><font color="#FFFFFF">Nombre/Variable</font></td>
     <td bgcolor='#6633FF'><font color="#FFFFFF">Tipo</font></td>
     <td bgcolor='#6633FF'><font color="#FFFFFF">Longitud</font></td>
      <?php
  	   $result=mysql_query("select id,variable,pregunta,tipo,orden,longitud from reportesd where id='$busca' order by orden",$link);
	   while ($row=mysql_fetch_array($result)){
		  printf("<tr><td bgcolor =\"CCCCFF\"><a href='reportese.php?op=El&busca=$busca&Pregunta=$row[pregunta]&pagina=$pagina'>%s</a></td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td>",'Elim',$row[orden],$row[pregunta],$row[variable],$row[tipo],$row[longitud]);
		}
		mysql_free_result($result);
		mysql_close($link);
  		?>
  		</table>
  		<p> Orden: 
    	<input name="Orden" type="text" size="3">
    	Pregunta: 
    	<input type="Pregunta" name="Pregunta">
    	Variable:
    	<input name="Variable" type="text" size="8">
    	Tipo: 
    	<input name="Tipo" type="text" size="2">
    	Long:
    	<input name="Longitud" type="text" size="2">
    	<input type="hidden" name="busca" value='<?php echo $busca; ?>'>
    	<input type="hidden" name="op" value="Ag">
    	<input type="hidden" name="pagina" value='<?php echo $pagina; ?>'>
 		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="IMAGE" name="Guarda" src="images/Guarda.gif" alt="Guarda cambios" >
  		</p>
    </form>     
  </td>
  </tr>
  <tr>
    <td width="136" height="59">&nbsp;</td>
    <td width="768"><p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="55">&nbsp;</td>
  </tr>
</table>
<hr noshade style="color:FF0000;height:3px">
<td width="416" valign="top">
</td>
</body>
</html>
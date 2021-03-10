<html>
<head>
<title>Catalogo de estudios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
function EliReg(){
   if(confirm("ATENCION!\r Desea dar de Baja este registro?")){
	  return(true);	   
   }else{
      document.form1.cKey.value='NUEVO';
   	  return(false);
   }
} 
  
function ValDato(cCampo){//Aqui mando el nombre del campo poro como es 1 campo a validar pues ya lo hago directo
if(cCampo=="Estudio"){document.form1.Estudio.value=document.form1.Estudio.value.toUpperCase();}
if(cCampo=="Descripcion"){document.form1.Descripcion.value=document.form1.Descripcion.value.toUpperCase();}
if(cCampo=="Condiciones"){document.form1.Condiciones.value=document.form1.Condiciones.value.toUpperCase();}
if(cCampo=="Tubocantidad"){document.form1.Tubocantidad.value=document.form1.Tubocantidad.value.toUpperCase();}
if(cCampo=="Equipo"){document.form1.Equipo.value=document.form1.Equipo.value.toUpperCase();}
if(cCampo=="Sinonimo"){document.form2.Sinonimo.value=document.form2.Sinonimo.value.toUpperCase();}

}
//-->
</script>
</head>
<body>
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="estudios.php?busca=<?php echo $cKey; ?>"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="1"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><img src="lib/estudios.jpg" alt="Catalogo de colores" width="150" height="25"></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:11px; top:103px; width:958px; height:388px; z-index:2"> 
  <table width="100%" height="391" border="0">
    <tr> 
      <td width="9%" height="387"><div align="center"> </div></td>
      <td width="4%">&nbsp;</td>
      <td width="87%">
	      <form name="form1" method="get" action="movest.php">
          <?php
		    include("lib/kaplib.php");
	        $link=conectarse();
	        $tabla="est";
   	        $cReg=mysql_query("select estudio,descripcion,objetivo,condiciones,tubocantidad,tiempoest,entord,entexp,enthos,enturg,equipo,muestras,estpropio,subdepto,contenido,comision from $tabla where (estudio= '$cKey')",$link);
	        $cCpo=mysql_fetch_array($cReg);
	        $lAg=$cKey=='NUEVO';
			?>
          <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399"> 
          <p>Estudio...........: 
            <input type="text" name="Estudio" value ='<?php if(!$lAg){echo $cCpo[0];} ?>' maxlength="7" onBlur="ValDato('Estudio')" size="6">
          </p>
          <p>Descripcion......: 
            <input type="text" name="Descripcion" value ='<?php if(!$lAg){echo $cCpo[1];} ?>' maxlength="40" onBlur="ValDato('Descripcion')" size="50">
          </p>
          <p>Tubo y cnt.de muestra a tomar......: 
            <input name=Tubocantidad type=text size=30 value='<?php echo $cCpo[4];?>' onBlur="ValDato('Tubocantidad')">
          </p>
          <p>Equipo a Usar : 
            <input name="Equipo" type="text" size="20" value='<?php echo $cCpo[10];?>' onBlur="ValDato('Equipo')">
            &nbsp;&nbsp;&nbsp;Sub-Depto:
			<?php
			echo "<select name=Subdepto>";
			$cSub=mysql_query("select subdepto from depd",$link);
			while ($dep=mysql_fetch_array($cSub)){
                 echo "<option value=$dep[0]>$dep[0]</option>"; 
            } 
		    echo "<option selected value=$cCpo[13]>$cCpo[13]</option>";
    	    echo "</select>";
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;%Comision <input name="Comision" type="text" size="6" value='<?php echo $cCpo[15];?>'>
			</p>
           <p>Tiempo real del estudio: 
            <input name="Tiempoest" type="text" size="6" value='<?php echo $cCpo[5];?>'>
            Numero de Muestras: 
            <input name=" Muestras" type="text" size="5" value='<?php echo $cCpo[11];?>'>
            Estudio propio[S/N]: 
            <select name=Estpropio>
              <option value=S>S</option>
              <option value=N>N</option>
              <option selected><?php echo $cCpo[12]; ?></option>
            </select>
          </p>
          </font> 
          <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">Dias 
            de Entrega Ordinaria: 
            <?php 
			$nCt=0;
			echo "<select name=Entord>";
			while ($nCt<=30){
			  echo "<option value=$nCt>$nCt</option>";	
			  $nCt++;			
			}
            echo "<option selected>$cCpo[6]</option>";
            echo "</select>";
			?>
            Express: 
            <input name="Entexp" type="text" size="6" value=<?php echo $cCpo[7]; ?> >
            Hospital: 
            <input name="Enthos" type="text" size="6" value=<?php echo $cCpo[8]; ?> >
            Urgencias: 
            <input name="Enturg" type="text" size="6" value=<?php echo $cCpo[9]; ?> >
            </p> 
          <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Objetivo</strong></font> 
          <p> 
           <TEXTAREA NAME="Objetivo" cols="55" rows="5" ><?php echo "$cCpo[2]"; ?></TEXTAREA>
          </p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Condiciones 
            </strong></font></p>
          <p>
           <TEXTAREA NAME="Condiciones" cols="55" rows="5" ><?php echo "$cCpo[3]";?></TEXTAREA>
          </p>
          <p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Contenido</strong></font></p>
           <TEXTAREA NAME="Contenido" cols="55" rows="5" ><?php echo "$cCpo[14]";?></TEXTAREA>
          <p>&nbsp;</p>
       
            <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">
            <input type="hidden" name="busca" value=<?php echo $busca; ?>>
            <input type="hidden" name="cKey" value=<?php echo $cKey; ?>>
            <input type="IMAGE" name="Guarda" src="lib/guardar.jpg" alt="Guarda los ultimos movimientos y salte" width="150" height="25" >
            <input type="IMAGE" name="Elimina" src="lib/eliminar.jpg" alt="Elimina este registro" onClick="EliReg()" width="150" height="25">
            </font> </p>
        </form>
		<p>&nbsp;<a href="estudios.php?busca=-1"><img src="lib/cancelar.jpg" width="150" height="25" border="0"></a></p>
		<form name="form2" method="get" action="movestsin.php">
          <table width="64%" border="1">
            <tr>
              <td width="12%">&nbsp;</td>
              <td width="88%"><div align="center"><font color="#0066FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Sinonimos 
                  de este estudio</font></div></td>
            </tr>
            <?php
		  $cSql="select descripcion from ests where estudio='$cKey'";
     	  $result=mysql_query($cSql,$link);
		  while ($row=mysql_fetch_array($result)){
   	           printf("<tr><td bgcolor =\"CCCCFF\"><a href='movestsine.php?cKey=$cKey&Sinonimo=$row[0]'>%s</a></td><td bgcolor =\"CCCCFF\">%s</td>",'Elimina',$row[0]);
		  }			   
		  mysql_free_result($result);
		  ?>
          </table>
		  <p> <font color="#0066FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Sinonimo:</font> 
            <input name="Sinonimo" type="text" id="Sinonimo" onBlur="ValDato('Sinonimo')" size="40">
            <input type="submit" name="Submit" value="Agrega">
          </p>
       <input type="hidden" name="cKey" value=<?php echo $cKey; ?>>
		</form>
        <p>&nbsp;</p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>

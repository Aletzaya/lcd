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
}
</script>
</head>
<body>
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="lista.php?busca=<?php echo $cKey; ?>"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="0"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><img src="lib/precios.jpg" alt="Lista de precios" width="150" height="25"></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:11px; top:111px; width:958px; height:388px; z-index:2"> 
  <table width="100%" height="391" border="0">
    <tr> 
      <td width="16%" height="387"><div align="center"></div></td>
      <td width="2%">&nbsp;</td>
      <td width="82%">
	      <form name="form1" method="get" action="movlis.php">
		    <?php
		    include("lib/kaplib.php");
	        $link=conectarse();
	        $tabla="est";
   	        $cReg=mysql_query("select estudio,descripcion,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10 from  $tabla where (estudio= '$cKey')",$link);
	        $cCpo=mysql_fetch_array($cReg);
	        $lAg=$cKey=='NUEVO';
			?>
		    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">Estudio...........:<?php echo $cCpo[0]; ?></p>
			
          <p>Descripcion......: <?php if(!$lAg){echo $cCpo[1];} ?></p>
          <p>Precio 1........:<input type="numeric" name="Lt1" value ='<?php if(!$lAg){echo $cCpo[2];} ?>'  onBlur="ValDato('Lt1')" size="5"></p>
		  <p>Precio 2........:<input type="numeric" name="Lt2" value ='<?php if(!$lAg){echo $cCpo[3];} ?>'  onBlur="ValDato('Lt2')" size="5"></p>
          <p>Precio 3........:<input type="numeric" name="Lt3" value ='<?php if(!$lAg){echo $cCpo[4];} ?>'  onBlur="ValDato('Lt3')" size="5"></p>
          <p>Precio 4........:<input type="text" name="Lt4" value ='<?php if(!$lAg){echo $cCpo[5];} ?>'  onBlur="ValDato('Lt4')" size="5"></p>
          <p>Precio 5........:<input type="text" name="Lt5" value ='<?php if(!$lAg){echo $cCpo[6];} ?>'  onBlur="ValDato('Lt5')" size="5"></p>
          <p>Precio 6........:<input type="text" name="Lt6" value ='<?php if(!$lAg){echo $cCpo[7];} ?>'  onBlur="ValDato('Lt6')" size="5"></p>
          <p>Precio 7........:<input type="text" name="Lt7" value ='<?php if(!$lAg){echo $cCpo[8];} ?>'  onBlur="ValDato('Lt7')" size="5"></p>
          <p>Precio 8........:<input type="text" name="Lt8" value ='<?php if(!$lAg){echo $cCpo[9];} ?>'  onBlur="ValDato('Lt8')" size="5"></p>
          <p>Precio 9........:<input type="text" name="Lt9" value ='<?php if(!$lAg){echo $cCpo[10];} ?>'  onBlur="ValDato('Lt9')" size="5"></p>
          <p>Precio 10.......:<input type="text" name="Lt10" value ='<?php if(!$lAg){echo $cCpo[11];} ?>'  onBlur="ValDato('Lt10')" size="5"></p>
          <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399"><br>
            <input type="hidden" name="busca" value=<?php echo $busca; ?>>
            <input type="hidden" name="cKey" value=<?php echo $cKey; ?>>
            <input type="IMAGE" name="Guarda" src="lib/guardar.jpg" alt="Guarda los ultimos movimientos y salte" width="150" height="25" >
            <input type="IMAGE" name="Elimina" src="lib/eliminar.jpg" alt="Elimina este registro" onClick="EliReg()" width="150" height="25">
            </font> </p>
        </form>
		<p>&nbsp;<a href="catalogos.php?busca=-1"><img src="lib/cancelar.jpg" width="150" height="25" border="0"></a></p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>

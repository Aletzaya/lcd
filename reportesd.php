<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catalogo de pacientes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function filtro(){
if(document.form2.Ecampo.options[document.form2.Ecampo.selectedIndex].value=="CAMPOS"){
  alert("No as elegido el campo");
  return false;
}else{
    if(document.form2.Esigno.options[document.form2.Esigno.selectedIndex].value=="SIGNO"){
    alert("Favor de elegir el signo ò Instrucciòn logica");
    return false;
	}else{
	     if(document.form2.Evalor.value=="VALOR"){
		    alert("Aun no as dado el valor de la Comparaciòn");
			document.form2.Evalor.focus();
			return false;
		 }else{
		    if(document.form2.yo.options[document.form2.yo.selectedIndex].value==""){
     			cSql=document.form2.busca.value;
		        cSig=Tcampo(document.form2.Ecampo.options[document.form2.Ecampo.selectedIndex].value);
	 		    cSql="*"+cSql+document.form2.Ecampo.options[document.form2.Ecampo.selectedIndex].value.substring(1);
				cSql=cSql+" "+document.form2.Esigno.options[document.form2.Esigno.selectedIndex].value+" ";
				if(document.form2.Esigno.options[document.form2.Esigno.selectedIndex].value=="like" || document.form2.Ecampo.options[document.form2.Ecampo.selectedIndex].value.substring(0,1)=="D"){ 
		           cSql=cSql+"!¡"+document.form2.Evalor.value+"¡!";}
				else{
			       cSql=cSql+cSig+document.form2.Evalor.value+cSig;
				}
			    document.form2.busca.value=cSql;
				return true;
			}else{
			    cSql=document.form2.busca.value;   //Donde guarda el 1er sql y lo retoma el anidado
				cSig=Tcampo(document.form2.Ecampo.options[document.form2.Ecampo.selectedIndex].value);
	 		    cSql=cSql+document.form2.Ecampo.options[document.form2.Ecampo.selectedIndex].value.substring(1);
			    cSql=cSql+" "+document.form2.Esigno.options[document.form2.Esigno.selectedIndex].value+" ";
				if(document.form2.Esigno.options[document.form2.Esigno.selectedIndex].value=="like" || document.form2.Ecampo.options[document.form2.Ecampo.selectedIndex].value.substring(0,1)=="D"){
		           cSql=cSql+"!¡"+document.form2.Evalor.value+"¡!";}
				else{
			       cSql=cSql+cSig+document.form2.Evalor.value+cSig;
				}
			    //cSql=cSql+cSig+document.form2.Evalor.value+cSig;
				cSql=cSql+document.form2.yo.options[document.form2.yo.selectedIndex].value;
				document.form2.reset();
				document.form2.busca.value=cSql;
				return false;
			  }
		}
	}
}


function Tcampo(cCampo){  //Regresa el signo dep.del tipo campo Numerico,date o strng
 var cSigno="";
 if(cCampo.substring(0,1)=="C"){cSigno="!";}
 if(cCampo.substring(0,1)=="N"){cSigno="";}
 if(cCampo.substring(0,1)=="D"){cSigno="%"}
return cSigno;
}
}
//-->
</script>
</head>
<link href="lib/texto.css" rel="stylesheet" type="text/css">
<body >
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:958px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="menu.php"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="1"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><font color="#FFFFFF"><strong>Detalle de reportes</strong></font></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>









<div id="Layer4" style="position:absolute; left:9px; top:512px; width:960px; height:83px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="102" border="0">
    <tr> 
      <td width="4%" height="94"> <div align="center">
          <p><a href="catalogos.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
          <p>&nbsp;</p>
        </div></td>
      <td width="15%">
        <p align="left"><a href="clientes.php"><img src="lib/IniRec.bmp" alt="Ir al inicio del archivo" border="0"></a><a href="clientes.php?busca=<?php echo '@'.$PriPro; ?>"><img src="lib/AntRec.bmp" border="0" alt="Pagina anterior"></a><a href="clientes.php?busca=<?php echo $UltPro;?>"><img src="lib/SigRec.bmp" border="0" alt="Pagina siguiente"></a><a href="clientes.php?busca=-1"><img src="lib/UltRec.bmp" border="0" alt="Ir al final del archivo"></a></p>
        <p>&nbsp;</p></td>		
	    <form name="form1" method="post" action="clientes.php">
        <td width="15%"><p align="left">&nbsp; </p>
        <p>&nbsp; </p></td>
		</form>
      <td width="6%">
        <p align="center"><a href="clientese.php?cKey=NUEVO"><img src="lib/nuevo.bmp" alt="Agrega un nuevo cliente" border="0"></a></p>
        <p>&nbsp;</p></td>
      <td width="51%">&nbsp; </td>
      <td width="9%"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
<div id="Layer2" style="position:absolute; left:9px; top:104px; width:959px; height:335px; z-index:5"> 
  <form name="form1" method="get" action="movrep.php" >
    <table width="100%" height="87" border="0">
      <tr> 
        <td width="80%" height="83"> 
          <?php
  	    include("lib/kaplib.php");
	    $link=conectarse();
		$Rp=mysql_query("select id,nombre,descripcion,instruccion from reportes where id='$cKey'",$link);
        $cCpo=mysql_fetch_array($Rp);	
	    $lAg=$cKey=='NUEVO';
	    echo "<b><font face='Verdana, Arial, Helvetica, sans-serif' size='3' color='#3907b9'>Reporte No.:  $cKey</font></b>";
        ?>
          <div align="center"><font color="000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#0000FF">Nombre.........: 
            </font> </strong> 
            <input name="Nombre"  type="text" value ='<?php if(!$lAg){echo $cCpo[1];} ?>' size="20" >
            </font></div> <p align="center"><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Descripcion....:</strong> 
            </font><font color="000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
            <input name="Descripcion" type="text" value ='<?php if(!$lAg){echo $cCpo[2];} ?>' size="40" >
            </font></p></td>
      </tr>
    </table>
  
<p><font color="3907b9" size="2" face="Verdana, Arial, Helvetica, sans-serif">Instruccion(SQL)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  </font></p>
  
  <p><font color="3907b9" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    <textarea name="cSql" cols="65" rows="5"><?php echo "$cCpo[3]"; ?></textarea>
    </font></p>
    <p><a href="reportes.php"><img src="lib/SmallExit.BMP" border="0"></a></p>
<p><font color="3907b9" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="movrep.php">
<input type="hidden" name="cKey" value='<?php echo $cKey; ?>'>
      <input type="IMAGE" name="Guarda" src="lib/ImgGua.jpg" alt="Guarda los Datos">
</p>
</form>
</div>
</body>
</html>

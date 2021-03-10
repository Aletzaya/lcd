<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Catalogo de estudios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
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

</script>
</head>
<body>
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:852px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="catalogos.php"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="1"></a></div></td>
      <td width="70%"><p>&nbsp;</p>
        <p align="center"><img src="lib/estudios.jpg" alt="Catalogo de colores" width="150" height="25"></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:10px; top:100px; width:853px; height:352px; z-index:2"> 
  <table width="100%">
    <tr bgcolor="#0066FF"> 
      <td width="8%"><font color="#FFFFFF">Estudio</font></td>
      <td width="59%"><font color="#FFFFFF">Descripcion</font></td>
      <td width="13%"><font color="#FFFFFF">No.Muestras</font></td>
      <td width="20%"><font color="#FFFFFF">Dias entrega ordinaria</font></td>
    </tr>
    <?php
		include("lib/kaplib.php");
		$link=conectarse();
		$cSql="select estudio,descripcion,muestras,entord from est ";
		$cOrd=" order by estudio";		
		if (substr($busca,0,1)=='*'){
		   	$cSql=str_replace ('!', "'",$busca);
			$cSql=str_replace ('¡', "%",$cSql);
			$cSql=substr($cSql,1);
			$result=mysql_query($cSql."where $cFil order by estudio",$link);
			if(!$result){
     			$Msj="Error, favor de verificar los datos del filtro deseado ";
	            header("Location: msj.php?url=estudios.php&Msj=$Msj");
			}
		}elseif($busca==-1){                 // Vete hasta el final del Archivo
		   	$result=mysql_query($cSql,$link);
  			$fin=mysql_num_rows($result)-15;
			$result=mysql_query($cSql.$cOrd." limit $fin,15",$link);
		}elseif(substr($busca,0,1)=='@'){   // Vete 15 Antes del Modelo inicial
		    $busca=substr($busca,1);
		   	$result=mysql_query($cSql."where estudio <= '$busca'".$cOrd,$link);
  			$fin=mysql_num_rows($result)-15;
 			$result=mysql_query($cSql."where estudio <= '$busca' ".$cOrd." limit $fin,15",$link);
		}elseif($busca<>""){   
		    $result=mysql_query($cSql."where estudio >= '$busca'".$cOrd." limit 0,15",$link);
        }else{
		   	$result=mysql_query($cSql.$cOrd." limit 0,15",$link);
		}
		$PriPro="";
		$UltPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($PriPro==""){$PriPro=$row[0];}
			if($lBd){
			    printf("<tr><td><a href='estudiose.php?cKey=$row[0]'>%s</a></td><td>%s</td><td>%s</td><td>%s</td>",$row[0],$row[1],$row[2],$row[3]);
				$lBd=false;		   
			}else{
			    printf("<tr><td bgcolor =\"CCCCFF\"><a href='estudiose.php?cKey=$row[0]'>%s</a></td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td><td bgcolor =\"CCCCFF\">%s</td>",$row[0],$row[1],$row[2],$row[3]);
				$lBd=true;
	        }
			$nRen=$nRen+1;
			$UltPro=$row[0];
		}
		if(substr($busca,0,1)=="*"){printf("<tr><td>%s</td><td>%s</td>",$row[0],'Total No.renglones '.number_format($nRen,"0"));}
		mysql_free_result($result);
		mysql_close($link);
		?>
  </table>
</div>
<div id="Layer4" style="position:absolute; left:8px; top:459px; width:856px; height:83px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="102" border="0">
    <tr> 
      <td width="4%" height="94"> <div align="center">
          <p><a href="catalogos.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
          <p>&nbsp;</p>
        </div></td>
      <td width="15%"> <p align="left"><a href="estudios.php?busca="><img src="lib/IniRec.bmp" alt="Ir al inicio del archivo" border="0"></a><a href="estudios.php?busca=<?php echo '@'.$PriPro; ?>"><img src="lib/AntRec.bmp" border="0" alt="Pagina anterior"></a><a href="estudios.php?busca=<?php echo $UltPro;?>"><img src="lib/SigRec.bmp" border="0" alt="Pagina siguiente"></a><a href="estudios.php?busca=-1"><img src="lib/UltRec.bmp" border="0" alt="Ir al final del archivo"></a></p>
        <p>&nbsp;</p></td>		
	    <form name="form1" method="post" action="estudios.php">
        <td width="15%"><p align="left"> <font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>Busca:</em></strong></font> 
        <input name="busca" type="text" size="8" maxlength="10">
        </p>
        <p>&nbsp; </p></td>
		</form>
      <td width="6%">
        <p align="center"><a href="estudiose.php?cKey=NUEVO"><img src="lib/nuevo.bmp" alt="Agrega una nueva intitucion" border="0"></a></p>
        <p>&nbsp;</p></td>
      <td width="51%"> 
	  </td>
      <td width="9%"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>

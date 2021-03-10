<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Estudios por institucion</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
function ValDato(cCampo){
 document.form2.Estudio.value=document.form2.Estudio.value.toUpperCase();
}
function ValDato(cCampo){
 document.form2.Estudio.value=document.form2.Estudio.value.toUpperCase();
}
function MostrarVentana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=450,height=400,left=250,top=150")
}

//-->
</script>
</head>
<body>
<?php
include("lib/kaplib.php");
$link=conectarse();
$lIns=mysql_query("select nombre from inst where institucion='$cKey' limit 1",$link);
$cIns=mysql_fetch_array($lIns);
?>
<div id="Layer1" style="position:absolute; left:10px; top:12px; width:852px; height:88px; z-index:1; background-image:  url(lib/fondo.jpg); layer-background-image:  url(lib/fondo.jpg); border: 1px none #000000;"> 
  <table width="100%" height="89" border="0">
    <tr>
      <td width="16%" height="76"><div align="center"><a href="estinstitucion.php"><img src="lib/logo2.jpg" alt="Regresar" width="100" height="80" border="0"></a></div></td>
      <td width="70%"><p align="center"><font color="#FFFFFF" size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong>Estudios 
          que entran en el convenio</strong></font></p>
        <p align="center"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Para 
          el instituto: <?php echo "$cKey $cIns[0]"; ?></strong></font></p></td>
      <td width="14%">&nbsp;</td>
    </tr>
  </table>    
</div>
<div id="Layer2" style="position:absolute; left:5px; top:101px; width:854px; height:352px; z-index:2"> 
  <table width="100%">
    <tr bgcolor="#0066FF"> 
      <td width="8%">&nbsp;</td>
      <td width="8%"><font color="#FFFFFF">Estudio</font></td>
      <td width="59%"><font color="#FFFFFF">Descripcion</font></td>
    </tr>
    <?php
		$cSql="select estins.instituto,estins.estudio,est.descripcion from estins,est where estins.instituto='$cKey' and estins.estudio=est.estudio ";
        if($busca==-1){                 // Vete hasta el final del Archivo
			$result=mysql_query($cSql,$link);
  			$fin = mysql_num_rows($result) - 15;
			if($fin>15){$result=mysql_query($cSql." order by estins.estudio limit $fin,15",$link);
			}else{ $result=mysql_query($cSql." order by estins.estudio limit 0,15",$link); }
		}elseif(substr($busca,0,1)=='@'){   // Vete 15 Antes 
		    $busca=substr($busca,1);
		   	$result=mysql_query($cSql." and estins.estudio <= '$busca'",$link);
  			$fin=mysql_num_rows($result)-15;
			$result=mysql_query($cSql."and  estins.estudio <= '$busca' order by estins.estudio limit $fin,15",$link);
		}elseif($busca<>""){   
		    $result=mysql_query($cSql."and estins.estudio >= '$busca' order by estins.estudio limit 0,15",$link);
        }else{
   	        $result=mysql_query($cSql." order by estins.estudio limit 0,15",$link);
		}
    	$PriPro="";
		$nRen=0;
		while ($row=mysql_fetch_array($result)){
			if($PriPro==""){$PriPro=$row[0];}
			if($lBd){
				printf("<tr><td><a href=movdelestins.php?Estudio=$row[1]&cKey=$cKey>%s</a></td><td>%s</td><td>%s</td>","Elim",$row[1],$row[2]);
				$lBd=false;
			}else{	
			    printf("<tr><td bgcolor=\"CCCCFF\"><a href=movdelestins.php?Estudio=$row[1]&cKey=$cKey>%s</a></td><td bgcolor=\"CCCCFF\">%s</td><td bgcolor=\"CCCCFF\">%s</td>","Elim",$row[1],$row[2]);
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
<div id="Layer4" style="position:absolute; left:10px; top:456px; width:856px; height:22px; z-index:4; background-image: url(lib/fondo1.jpg); layer-background-image: url(lib/fondo1.jpg); border: 1px none #000000;"> 
  <table width="100%" height="98" border="0">
    <tr> 
      <td width="5%" height="94"> <div align="center"> 
          <p><a href="institu.php"><img src="lib/SmallExit.BMP" alt="Salir" border="0"></a></p>
        </div></td>
      <td width="23%"> <p align="left"><a href="instd.php?cKey=<?php echo $cKey;?>"><img src="lib/IniRec.bmp" alt="Ir al inicio del archivo" border="0"></a>
	  <a href="instd.php?busca=<?php echo '@'.$PriPro; ?>"><img src="lib/AntRec.bmp" border="0" alt="Pagina anterior"></a>
	  <a href="instd.php?busca=<?php echo $UltPro;?>"><img src="lib/SigRec.bmp" border="0" alt="Pagina siguiente"></a>
	  <a href="instd.php?busca=-1&cKey=<?php echo $cKey;?>"><img src="lib/UltRec.bmp" border="0" alt="Ir al final del archivo"></a></p></td>
      <form name="form1" method="post" action="instd.php?cKey=<?php echo $cKey;?>">
        <td width="19%"><p align="left"> <font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><em>Busca:</em></strong></font> 
            <input name="busca" type="text" size="8" maxlength="10">
          </p></td>
      </form>
       
      <td width="46%"><font color="#0000FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong></strong> 
        </font> <a href="javascript:MostrarVentana('AgrEstIns.php?cKey=<?php echo $cKey; ?>')"><img src="lib/lupa_o.gif" alt="Presiona click para editar el cat/estudios" width="22" height="21" border="0"></a>
      </td>
      <td width="7%"></form>
    </tr>
  </table>
</div>
</body>
</html>
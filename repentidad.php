<?php

  session_start();

  require("lib/lib.php");

  $link   = conectarse();

  $FechaI = $_REQUEST[FechaI];

  $FechaF = $_REQUEST[FechaF];
  
  $Institucion    = $_REQUEST[Institucion];
  
  $Sucursal    = $_REQUEST[Sucursal];
  
  $Det=$_REQUEST[Det];
  
  $Ref=$_REQUEST[Ref];
  
  if($Ref=="MD"){
	  $referencia=" and ot.medico='MD'";
  }elseif($Ref=="AQ"){
  	  $referencia=" and ot.medico='AQ'";
  }else{
  	  $referencia="";
  }

  if (!isset($FechaI)){

      $FechaF=date("Y-m-d");

      $FechaI=date("Y-m-")."01";

  }
  
  $lU    = mysql_query("DELETE FROM rep");
  
  if($Det<>'Si'){
	  
	  if($Sucursal == "*"){
	
		  if($Institucion == "*" OR $Institucion == ''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
						$referencia
						GROUP BY cli.estado,cli.municipio");
								
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente AND ot.institucion='$Institucion'
						$referencia
						GROUP BY cli.estado,cli.municipio");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Ins: $cIns[alias] $Ref";
		
		  }
		  
	  }else{
		  
		  if($Institucion == "*" OR $Institucion==''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente and ot.suc='$Sucursal'
						$referencia
						GROUP BY cli.estado,cli.municipio");
						
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF, Suc: $Sucursal - $cSuc[alias] $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
						AND ot.institucion='$Institucion' and ot.suc='$Sucursal' $referencia
						GROUP BY cli.estado,cli.municipio");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
			 
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias], Suc: $Sucursal - $cSuc[alias] $Ref";
		
		  }
 	 }

  }else{
	  
	  if($Sucursal == "*"){
	
		  if($Institucion == "*" OR $Institucion == ''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente $referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
								
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente AND ot.institucion='$Institucion'
						$referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias]  $Ref";
		
		  }
		  
	  }else{
		  
		  if($Institucion == "*" OR $Institucion==''){
		   
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente and ot.suc='$Sucursal'
						$referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
						
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF, Suc: $Sucursal - $cSuc[alias]  $Ref";
						
		  }else{
		
			 $OtA = mysql_query("SELECT cli.estado, cli.municipio, cli.colonia, count(*) as cantidad, sum(ot.importe) as importotal,ot.medico
						FROM cli, ot WHERE ot.fecha >= '$FechaI' AND ot.fecha <= '$FechaF' AND ot.cliente=cli.cliente 
						AND ot.institucion='$Institucion' and ot.suc='$Sucursal' $referencia
						GROUP BY cli.estado,cli.municipio,cli.colonia");
		   
			 $cInsA = mysql_query("SELECT alias FROM inst WHERE institucion='$Institucion'");
			 $cIns  = mysql_fetch_array($cInsA);
			 
			 $cSucA = mysql_query("SELECT alias FROM cia WHERE id='$Sucursal'");
			 $cSuc  = mysql_fetch_array($cSucA);
	
		
			 $Titulo = "Clientes por Entidad Fed. del $FechaI al $FechaF Inst: $cIns[alias], Suc.: $Sucursal - $cSuc[alias]  $Ref";
		
		  }
 	 }
  
  }
  
  $Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#ffffff'> &nbsp; ";

  require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<script type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php

  echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

  headymenu($Titulo,0);

  $Ini = 0+substr($FechaI,5,2);
  $Fin = 0+substr($FechaF,5,2);


  if($Det<>'Si'){

	  echo "<br>";
	
	  echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=2 bgcolor='#FFFFFF'>";
	  echo "<td>$Gfont Estado</td>";
	  echo "<td>$Gfont Municipio</td>";
	  echo "<td>$Gfont Cantidad</td>";
	  echo "<td>$Gfont Importe</td>";
	  echo "</tr>";
	
	  
	  while($rg = mysql_fetch_array($OtA)){
	
		  $Edo  = str_replace(" ","!",$rg[estado]);                      //Remplazo la comita p'k mande todo el string
		  $Mpio = str_replace(" ","!",$rg[municipio]);                      //Remplazo la comita p'k mande todo el string
	
		  if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
	
		  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
			//echo "<td align='center'><a href=javascript:winuni('repentdet.php?FechaI=$FechaI&FechaF=$FechaF&Estado=$rg[estado]&Municipio=$rg[municipio]&Colonia=$rg[colonia]')><img src='lib/browse.png' border=0></a></td>";
			echo "<td>$Gfont $rg[estado]</td>";
			echo "<td>$Gfont $rg[municipio]</td>";
			echo "<td align='right'>$Gfont $rg[cantidad]</td>";
			echo "<td align='right'>$Gfont ".number_format($rg[importotal],"2")."</td>";
			echo "</tr>";
	
		  $nCnt  += $rg[cantidad];
		  $nImpt  += $rg[importotal];
		  $nRng++;
			
	  }
	
	  echo "<tr>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont Total ---> </td>";
	  echo "<td align='right'>$Gfont ".number_format($nCnt,"0")."</td>";
	  echo "<td align='right'>$Gfont ".number_format($nImpt,"2")."</td>";
	  echo "</tr>";
	
	  echo "</table>";
	  
  }else{

	  echo "<br>";
	
	  echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=2 bgcolor='#FFFFFF'>";
	  echo "<tr height='21' background='lib/bartit.gif' >"; 
	  echo "<td align='center'>$Gfont Detalle</td>";
	  echo "<td>$Gfont Estado</td>";
	  echo "<td>$Gfont Municipio</td>";
	  echo "<td>$Gfont Colonia</td>";
	  echo "<td>$Gfont Cantidad</td>";
	  echo "<td>$Gfont Importe</td>";
	  echo "</tr>";
	
	  
	  while($rg = mysql_fetch_array($OtA)){
	
		  $Edo  = str_replace(" ","!",$rg[estado]);                      //Remplazo la comita p'k mande todo el string
		  $Mpio = str_replace(" ","!",$rg[municipio]);                      //Remplazo la comita p'k mande todo el string
		  $Col  = str_replace(" ","!",$rg[colonia]);                      //Remplazo la comita p'k mande todo el string
	
		  if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
	
		  echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
			//echo "<td align='center'><a href=javascript:winuni('repentdet.php?FechaI=$FechaI&FechaF=$FechaF&Estado=$rg[estado]&Municipio=$rg[municipio]&Colonia=$rg[colonia]')><img src='lib/browse.png' border=0></a></td>";
			echo "<td align='center'><a href=javascript:winuni3('repentdet.php?FecI=$FechaI&FecF=$FechaF&Col=$Col&Mpio=$Mpio&Edo=$Edo&Suc=$Sucursal&Institucion=$Institucion')><img src='lib/browse.png' border=0></a></td>";
			echo "<td>$Gfont $rg[estado]</td>";
			echo "<td>$Gfont $rg[municipio]</td>";
			echo "<td>$Gfont $rg[colonia]</td>";
			echo "<td align='right'>$Gfont $rg[cantidad]</td>";
			echo "<td align='right'>$Gfont ".number_format($rg[importotal],"2")."</td>";
			echo "</tr>";
	
		  $nCnt  += $rg[cantidad];
		  $nImpt  += $rg[importotal];
		  $nRng++;
			
	  }
	
	  echo "<tr>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont </td>";
	  echo "<td>$Gfont Total ---> </td>";
	  echo "<td align='right'>$Gfont ".number_format($nCnt,"0")."</td>";
	  echo "<td align='right'>$Gfont ".number_format($nImpt,"2")."</td>";
	  echo "</tr>";
	
	  echo "</table>";
  }

  echo "<table align='center' width='98%' border='0'>";

  echo "<tr><td width='3%'><a href='menu.php'><img src='lib/regresa.jpg' border='0'></a>";

  echo "</td><td>$Gfont ";

  echo "<form name='form1' method='post' action='repentidad.php'>";

  echo "&nbsp;&nbsp;&nbsp; Suc: $Sucursal";
  echo "<SELECT name='Sucursal'>";
  $SucA = mysql_query("SELECT id,alias FROM cia ORDER BY id");
  while($Suc=mysql_fetch_array($SucA)){
        echo "<option value='$Suc[0]'>$Suc[1]</option>";  
		  if($Sucursal == "*"){
			  $alias="* Todas";
		  }else{
			  $alias=$cSuc[alias];
		  }
  }      
  echo "<option value='*'>*, todas</option>";
  echo "<option selected value='$Sucursal'> $alias</option>"; 
  echo "</select> &nbsp; ";

  echo "Inst: $Institucion";
  echo "<SELECT name='Institucion'>";
  $InsA = mysql_query("SELECT institucion,alias FROM inst ORDER BY institucion");
  while($Ins=mysql_fetch_array($InsA)){
        echo "<option value='$Ins[0]'>$Ins[alias]</option>"; 
		  if($Institucion == "*"){
  			  $aliasinst="* Todas";
		  }else{
			  $aliasinst=$cIns[alias];
		  } 
  }      
  echo "<option value='*'>*, todas</option>"; 
  echo "<option selected value='$Institucion'> $aliasinst</option>";  
  echo "</select> &nbsp; ";

  echo " Fech Ini: <input type='text' name='FechaI' value='$FechaI' size='9' maxlength='10'>";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaI,'yyyy-mm-dd',this)>";

  echo " &nbsp;&nbsp; Fech Fin: <input type='text' name='FechaF' value='$FechaF' size='9' maxlength='10'> ";

  echo " &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FechaF,'yyyy-mm-dd',this)>";
  
 echo " &nbsp;&nbsp; Det:";
 echo "<select name='Det'>";
 echo "<option value='Si'>Si</option>";
 echo "<option value='No'>No</option>";
 echo "<option selected value='$Det'>$Det</option>";
 echo "</select> &nbsp;";


 echo " &nbsp;&nbsp; Ref:";
 echo "<select name='Ref'>";
 echo "<option value='MD'>MD</option>";
 echo "<option value='AQ'>AQ</option>";
  echo "<option value='*'>* Todos</option>";
 echo "<option selected value='$Ref'>$Ref</option>";
 echo "</select> &nbsp;&nbsp;";
 
 echo "<input type='submit' name='Boton' value='Enviar'>"; 

echo "</font></td><td>";

echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";

echo "</tr></table>";

mysql_close();

?>

</body>

</html>
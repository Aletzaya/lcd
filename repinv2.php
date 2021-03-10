<?php

session_start();

require("lib/lib.php");

$link=conectarse();

 if (!isset($_REQUEST[filtro])){
      $filtro = '*';
  }else{
      $filtro    = $_REQUEST[filtro];       
  }

  if (!isset($_REQUEST[filtro3])){
      $filtro3 = '*';
  }else{
      $filtro3    = $_REQUEST[filtro3];       
  }

  if (!isset($_REQUEST[filtro5])){
      $filtro5 = '*';
  }else{
      $filtro5    = $_REQUEST[filtro5];       
  }

  if (!isset($_REQUEST[filtro7])){
      $filtro7 = '';
  }else{
      $filtro7    = $_REQUEST[filtro7];       
  }

  if (!isset($_REQUEST[filtro9])){
      $filtro9 = '*';
  }else{
      $filtro9    = $_REQUEST[filtro9];       
  }

  if (!isset($_REQUEST[filtro11])){
      $filtro11 = '*';
  }else{
      $filtro11    = $_REQUEST[filtro11];       
  }

  if (!isset($_REQUEST[filtro13])){
      $filtro13 = '*';
  }else{
      $filtro13    = $_REQUEST[filtro13];       
  }

  if (!isset($_REQUEST[filtro15])){
      $filtro15 = '*';
  }else{
      $filtro15    = $_REQUEST[filtro15];       
  }

  if (!isset($_REQUEST[filtro17])){
      $filtro17 = '*';
  }else{
      $filtro17    = $_REQUEST[filtro17];       
  }

  if (!isset($_REQUEST[filtro19])){
      $filtro19 = '*';
  }else{
      $filtro19    = $_REQUEST[filtro19];       
  }

  if (!isset($_REQUEST[filtro21])){
      $filtro21 = '*';
  }else{
      $filtro21    = $_REQUEST[filtro21];       
  }

  if (!isset($_REQUEST[filtro23])){
      $filtro23 = '*';
  }else{
      $filtro23    = $_REQUEST[filtro23];       
  }

 if($filtro<>'*'){
    $filtro2="and invl.depto='$filtro'";
 }else{
    $filtro2=" ";
 }

 if($filtro3<>'*'){
    $filtro4="and invl.subdepto='$filtro3'";
 }else{
    $filtro4=" ";
 }

 if($filtro5<>'*'){
    $filtro6="and invl.presentacion='$filtro5'";
 }else{
    $filtro6=" ";
 }
 
 if($filtro7<>''){
    $filtro8="and invl.marca='$filtro7'";
 }else{
    $filtro8="";
 }
 
 if($filtro9<>'*'){
    $filtro10="and invl.status='$filtro9'";
 }else{
    $filtro10=" ";
 }
/*
 if($filtro11<>'*'){
    $filtro12="and est.bloqeqp='$filtro11'";
 }else{
    $filtro12=" ";
 }
*/
 if($filtro13<>'*'){
    $filtro14="and est.bloqmue='$filtro13'";
 }else{
    $filtro14=" ";
 }

 if($filtro15<>'*'){
    $filtro16="and est.bloqcon='$filtro15'";
 }else{
    $filtro16=" ";
 }

 if($filtro17<>'*'){
    $filtro18="and est.bloqdes='$filtro17'";
 }else{
    $filtro18=" ";
 }

 if($filtro19<>'*'){
    $filtro20="and est.bloqadm='$filtro19'";
 }else{
    $filtro20=" ";
 }

 if($filtro21<>'*'){
    $filtro22="and est.bloqatn='$filtro21'";
 }else{
    $filtro22=" ";
 }

 if($filtro23<>'*'){
    $filtro24="and est.base='$filtro23'";
 }else{
    $filtro24=" ";
 }

  if($filtro11=='*'){
   $Sucursal='sucursalt';
 }elseif($filtro11==0){
   $Sucursal='sucursal0';
 }elseif($filtro11==1){
   $Sucursal='sucursal1';
 }elseif($filtro11==2){
   $Sucursal='sucursal2';
 }elseif($filtro11==3){
   $Sucursal='sucursal3';
 }elseif($filtro11==4){
   $Sucursal='sucursal4';
 }

$Titulo = "Reporte de inventario de productos";

//$cOtA="select * from invl where $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 $filtro12 $filtro14 $filtro16 $filtro18 $filtro20 $filtro22 $filtro24 order by est.descripcion";

$cOtA="SELECT *
FROM invl where clave<>'' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10
ORDER BY invl.descripcion ASC";

$OtA  = mysql_query($cOtA,$link);

require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head class="header">

<link href="stylep.css" rel="stylesheet">

<title><?php echo $Titulo;?></title>

<?php
headymenu($Titulo, 0);

echo "<table align='center' width='97%' border='0'>";

echo "<form name='form' method='post' action='repinv.php'>";

echo "<td align='left'>$Gfont<b><font size='1'>Depto</b></font>";
echo "<select size='1' name='filtro' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
$depA=mysql_query("SELECT departamento,nombre FROM dep order by departamento");
while($dep=mysql_fetch_array($depA)){
    echo "<option value=$dep[departamento]> $dep[departamento]&nbsp;$dep[nombre]</option>";
    if($dep[departamento]==$filtro){$DesSuc=$dep[nombre];}
}
echo "<option selected value='$filtro'>$Gfont <font size='-1'>$filtro $DesSuc</option></p>";       
echo "</select>";
echo"</b></td>";


echo "<td align='left'>$Gfont<b><font size='1'>Subdepto</b></font>";
echo "<select size='1' name='filtro3' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
$subdepA=mysql_query("SELECT id,subdepto,nombre FROM depd where depd.departamento=$filtro order by id");
while($subdep=mysql_fetch_array($subdepA)){
    echo "<option value=$subdep[subdepto]> $subdep[id]&nbsp;$subdep[subdepto]</option>";
    if($subdep[subdepto]==$filtro3){$Desdep=$subdep[subdepto];}
}
echo "<option selected value='$filtro3'>$Gfont <font size='-1'>$filtro3</option></p>";       
echo "</select>";
echo"</b></td>";

echo "<td align='left'>$Gfont<b><font size='1'>Presentacion</b></font>";
echo "<select size='1' name='filtro5' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Piezas'>Piezas</option>";
echo "<option value='Cajas'>Cajas</option>";
echo "<option value='Paquetes'>Paquetes</option>";
echo "<option value='Bolsas'>Bolsas</option>";
echo "<option value='Kilos'>Kilos</option>";
echo "<option value='Litros'>Litros</option>";
echo "<option selected value='$filtro5'>$Gfont <font size='-1'>$filtro5</option></p>";       
echo "</SELECT>";
echo "</b></td>";

echo "<td align='left'>$Gfont<b><font size='1'>Status</b></font>";
echo "<select size='1' name='filtro9' class='Estilo9'>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Activo'>Activo</option>";
echo "<option value='Inactivo'>Inactivo</option>";
echo "<option selected value='$filtro9'>$Gfont <font size='-1'>$filtro9</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</font></td>";

echo "<td align='left'>$Gfont<b><font size='1'>Marca</b></font>";
echo "<input type='text' name='filtro7' size='30' maxlength='30' placeholder='Marca' value='$filtro7'>";
echo"</td>";

  echo "<td align='left'>$Gfont<b><font size='1'>Suc</b></font>";
  echo "<select size='1' name='filtro11' class='Estilo10'>";
  echo "<option value='*'>Todos*</option>";
  $SucA=mysql_query("SELECT id,alias FROM cia order by id");
  while($Suc=mysql_fetch_array($SucA)){
    echo "<option value=$Suc[id]> $Suc[id]&nbsp;$Suc[alias]</option>";
    if($Suc[id]==$filtro11){$DesSuc2=$Suc[alias];}
  }
  echo "<option selected value='$filtro11'>$Gfont <font size='-1'>$filtro11 $DesSuc2</option></p>";     
  echo "</select>";
  echo"</b></td>";

echo "<td><input type='SUBMIT' value='Ok'></td>";

  echo "</form>";
  echo "</font><td>";

echo "<form name='form2' method='get' action='menu.php'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";
echo "</td></tr></table>";

$Gfon = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";
echo "<table width='97%' align='center' border='0'>";
$Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";

if($Sucursal=='sucursalt'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td><td align='center'>$Gfon Matriz</td><td align='center'>$Gfon Tpx</td><td align='center'>$Gfon OHF</td><td align='center' bgcolor='#7dcea0'>$Gfon GralRys</td><td align='center'>$Gfon Reyes</td><td align='center' bgcolor='#7dcea0'>$Gfon Exist</td>";
}elseif($Sucursal=='sucursal0'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon Gral</td>";
}elseif($Sucursal=='sucursal1'){
  $Tit="<td align='center'>$Gfon Matriz</td>";
}elseif($Sucursal=='sucursal2'){
  $Tit="<td align='center'>$Gfon OHF</td>";
}elseif($Sucursal=='sucursal3'){
  $Tit="<td align='center'>$Gfon Tpx</td>";
 }elseif($Sucursal=='sucursal4'){
  $Tit="<td align='center' bgcolor='#7dcea0'>$Gfon GralRys</td><td align='center'>$Gfon Reyes</td>";
 }

echo "<tr bgcolor='#a2b2de'><td align='center'>$Gfon #</td><td align='center'>$Gfon Clave </td><td align='center' width='20%'>$Gfon Descripcion</td><td align='center'>$Gfon Marca</td><td align='center'>$Gfon Presentacion</td><td align='center'>$Gfon Min - Max</td><td align='center'>$Gfon Depto</td><td align='center'>$Gfon Subdepto</td>".$Tit."<td align='center'>$Gfon Status</td></tr>";
?>
</head>

<body bgcolor="#FFFFFF">

<?php

while ($reg = mysql_fetch_array($OtA)) {

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
    

    $deptoA=mysql_fetch_array(mysql_query("SELECT departamento,nombre FROM dep where dep.departamento=$reg[depto]"));
    $depto = $deptoA[nombre];

    $nRng++;

    $cSql1=mysql_query("SELECT count(*) as contar1 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.sucorigen=1");

    $reg1 = mysql_fetch_array($cSql1);

    $cSql2=mysql_query("SELECT count(*) as contar2 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.sucorigen=2");

    $reg2 = mysql_fetch_array($cSql2);

    $cSql3=mysql_query("SELECT count(*) as contar3 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.sucorigen=3");

    $reg3 = mysql_fetch_array($cSql3);

    $cSql4=mysql_query("SELECT count(*) as contar4 FROM invldet
    WHERE invldet.producto = '$reg[clave]' and invldet.sucorigen=4");

    $reg4 = mysql_fetch_array($cSql4);

    if($Sucursal=='sucursalt'){
      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $reg[invgral]</font></td><td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=1')>$Gfont $reg[invmatriz] - $reg1[contar1]</a></font></td><td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=3')>$Gfont $reg[invtepex] - $reg3[contar3]</font></a></td><td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=2')>$Gfont $reg[invhf] - $reg2[contar2]</font></a></td><td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $reg[invgralreyes]</font></td><td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=4')>$Gfont $reg[invreyes] - $reg4[contar4]</font></a></td><td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $reg[existencia]</font></td>";
    }elseif($Sucursal=='sucursal0'){
      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $reg[invgral]</font></td>";
    }elseif($Sucursal=='sucursal1'){
      $Tit2="<td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=1')>$Gfont $reg[invmatriz]</font></a></td>";
    }elseif($Sucursal=='sucursal2'){
      $Tit2="<td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=2')>$Gfont $reg[invhf]</font></a></td>";
    }elseif($Sucursal=='sucursal3'){
      $Tit2="<td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=3')>$Gfont $reg[invtepex]</a></font></td>";
    }elseif($Sucursal=='sucursal4'){
      $Tit2="<td align='center' bgcolor='#7dcea0' onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='#7dcea0';>$Gfont $reg[invgralreyes]</font></td><td align='center'><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=4')>$Gfont $reg[invreyes]</font></a></td>";
    }

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td onMouseOver=this.style.backgroundColor='#7fb3d5';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>$Gfont $nRng</td><td>$Gfont <a href=javascript:wingral('movimientosinv.php?clave=$reg[clave]')><img src='images/movimientos.png' onmouseover='this.width=25;this.height=25;' onmouseout='this.width=20;this.height=20;' width='20' height='20' alt='' /></a> - <a href=javascript:wingral('invlabe2.php?busca=$reg[id]')><font size='1'> $reg[clave]</a></td><td><a href=javascript:wingral('movimientosinvdet.php?clave=$reg[clave]&suc=*')>$Gfont $reg[descripcion]</font></a></td><td>$Gfont $reg[marca]</font></td><td>$Gfont $reg[presentacion]</font></td><td align='center'>$Gfont $reg[min] - $reg[max]</font></td><td>$Gfont $depto</font></td><td>$Gfont $reg[subdepto]</font></td>".$Tit2."<td align='center'>$Gfont $reg[status]</font></td></tr>";

}

echo "<tr bgcolor='#a2b2de'><td>&nbsp;</td><td>&nbsp;</td><td align='center' >$Gfont <b>Total de estudios:  $nRng </b></font></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

echo "</table>";


mysql_close();
?>

</body>

</html>